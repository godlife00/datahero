<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once dirname(__FILE__).'/stocks.php';
// Stocks 컨트롤러 로직을 그대로 사용하고 뷰로 assign 하는 데이터만 받아 쓸 수 있도록
class StocksWrapper extends Stocks {
    private $resultData = array();

    // @ BasePC override
    public function popup_view($view, $data){
        $this->resultData = $data;
        return;
    }

    // @ BasePC override
    public function _view($view, $data=array(), $return_contents = false){
        $this->resultData = $data;
        return;
    }

    public function getData() {
        return $this->resultData;
    }
}


class Api extends BasePC_Controller{
    private $stocks;

    public function __construct() {
		parent::__construct();
		$this->load->model(array(
					'business/historylib',
					DBNAME.'/company_tb_model',
					DBNAME.'/myitem_tb_model',
					DBNAME.'/mri_tb_model',
					));
        $this->stocks = new StocksWrapper();
	}


    /*
        stocks 컨트롤러 내 메서드가 구하는 데이터 넘겨받기

        $ticker_code : ticker code
        $dimension : MRY or MRT or MRQ
        $cell_type : data or ratio

        //$pExtra : // 적합 형태로 기본제공하고, 파라메터 제외함.
        historylib->getFinStateList($ticker_code, $dimension, $pExtra)
        historylib->getFinStateRatioList($ticker_code, $dimension, $pExtra)
                  으로 넘겨짐
                  아래 정의의 덮어쓰기로 사용.

                  $default_params = array(
                    'column_date_format' => '%Y.%m.%d',
                    'with_summary' => false,
                    'func_name' => '',
                    'limit' => ''
                  );
    */
	public function getBaseData($ticker_code, $dimension='MRT', $cell_type='data') {
		$pExtra = array(
			'with_summary' => true
		);

        $dimension = strtoupper($dimension);
        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}

        $cell_type = strtolower($cell_type);
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if($cell_type == 'data') {
			$this->historylib->getFinStateList($ticker_code, $dimension, $pExtra);
			//$this->historylib->getFinStateList_bicchart($ticker_code, $dimension, $pExtra);

		} else {
			// cell_type == ratio
			$this->historylib->getFinStateRatioList($ticker_code, $dimension, $pExtra);
		}

		if( ! $this->historylib->isSuccess()) {
            // ticker 똑같은 퍼마티커만 다른 종목은 실패난다.
            // todo
            //조치가 필요할듯..
            // select tkr_ticker, count(*) cou from ticker_tb where tkr_table = 'SEP' group by tkr_ticker having cou > 1 order by cou desc;
            // 하면 나오는 종목들...
			//echo $this->historylib->getErrorMsg();
            echo serialize(array());
			return;
		}

		$data = $this->historylib->getData();
        $data['findata'] = $this->historylib->convertRows($data['findata']);

		$data['last_mry_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mry']), 'sf1_datekey');
		$data['last_mrq_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mrq']), 'sf1_datekey');
		$data['last_mrt_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mrt']), 'sf1_datekey');
		
		$data['last_mrq_list_nc'] = $data['last_mrq'];

		$data['last_mry'] = array_shift(array_values($data['last_mry_list']));
		$data['last_mrq'] = array_shift(array_values($data['last_mrq_list']));
		$data['last_mrt'] = array_shift(array_values($data['last_mrt_list']));

        echo serialize($data);

        //echo serialize($this->stocks->_getBaseData($ticker_code, $dimension, $cell_type, $pExtra));
	}

    // 티커 코드별 상단 검색바 자동완성 검색용 텍스트 제공
    public function getSearchTickerList() {
        echo serialize($this->search_ticker_list);
    }

    // 티커 코드별 한국어 설정 맵핑 정보 제공
    public function getTickerKoreanMap() {

        // @ 기업 검색 리스트
        // 1. 유효 기업 전체 가져오기
        $ticker_params = array();
        //$ticker_params['=']['tkr_table'] = 'SF1';
        $ticker_params['=']['tkr_table'] = 'SEP';
        $ticker_params['=']['tkr_isdelisted'] = 'N';
        $ticker_params['!=']['tkr_exchange'] = 'OTC';

        $ticker_extra = array(
                'order_by' => 'tkr_ticker',
                'fields' => 'tkr_ticker, tkr_permaticker, tkr_name, tkr_lastpricedate, tkr_sector, tkr_industry, tkr_exchange, tkr_category, tkr_currency, tkr_companysite, tkr_table',
                'slavedb' => true,
                'cache_sec' => 3600*12,
                );

        $ticker_list = $this->common->getDataByPK($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');

        /*
        $ticker_params['=']['tkr_table'] = 'SEP';
        $ticker_list_sep = $this->common->getDataByPK($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');
        $ticker_list = array_intersect_key($ticker_list, $ticker_list_sep);
        */

        $this->ticker_info_map = $ticker_list;

        // 2. 한국어 기업 정리
        $comp_params = array();
        //$comp_params['=']['cp_is_confirmed'] = 'YES';

        $comp_extra = array(
                'order_by' => '',
                'fields' => 'cp_ticker, cp_korname',
                'cache_sec' => 3600*12,
                );

        $comp_list = $this->common->getDataByPK($this->company_tb_model->getList($comp_params, $comp_extra)->getData(), 'cp_ticker');
		
		$comp_list_new = array();
//echo '<pre>';
//print_r($ticker_list);
		foreach($ticker_list as $cKey => $cVal ) {

			$co_korname='';
			$co_korname = $comp_list[$cKey]['cp_korname'];

			if($cVal['tkr_exchange'] == 'NASDAQ' || $cVal['tkr_exchange'] == 'NYSE' || $cVal['tkr_exchange'] == 'NYSEMKT') {
				// 2020.08.26 수정 if($cVal['tkr_category'] == 'ADR Preferred' || $cVal['tkr_category'] == 'ADR Secondary' || $cVal['tkr_category'] == 'Canadian Preferred' || $cVal['tkr_category'] == 'Canadian Secondary' || $cVal['tkr_category'] == 'Domestic Preferred' || $cVal['tkr_category'] == 'Domestic Secondary') {
				if( strstr(strtoupper($cVal['tkr_category']), 'PREFERRED') || strstr(strtoupper($cVal['tkr_category']), 'SECONDAR') ) {

					$params = array();
					$params['=']['tkr_name'] = $cVal['tkr_name'];
					$params['=']['tkr_table'] = 'SF1';

					$extra = array(
		                'order_by' => 'tkr_isdelisted',
						'fields' => array('tkr_ticker'),
						'limit' => '1'
					);
					$extra['cache_sec'] = 3600;

					$data = $this->ticker_tb_model->getList($params, $extra)->getData();
					$co_korname = $comp_list[$data[0]['tkr_ticker']]['cp_korname'];
				} 
			}

			$comp_list_new[$cKey]['cp_ticker'] = $cKey;
			$comp_list_new[$cKey]['cp_korname'] = $co_korname;
		}

	    $ticker_korean_map = array();
        $ticker_korean_map = $this->common->array2map($comp_list_new, 'cp_ticker', 'cp_korname');
		//echo '<pre>';
		//print_r($ticker_korean_map);

        echo serialize($ticker_korean_map);
    }

    // 티커 코드별 종목정보 맵 제공
    public function getTickerInfoMap() {
        echo serialize($this->ticker_info_map);
    }

    // 티커 코드별 거래가 정보 맵 제공
    public function getTickerList() {
        echo serialize($this->ticker_priceinfo_map);
    }

	// 티커 배당 정보 제공
	public function getDpsTkr($ticker) {
        $params = array(); // 빈배열
        $params['=']['sf1_ticker'] = $ticker;
        $params['=']['sf1_dimension'] = 'MRY';

		$extra = array();
        $extra['fields'] = 'sf1_dps';
        $extra['order_by'] = 'sf1_datekey desc';
		$extra['cache_sec'] = 3600*24;

	    $data = $this->sf1_tb_model->getList($params, $extra)->getData();
        echo serialize($data);
	}

    // mri 정보 제공
    public function getMRIList() {
        $this->load->model(DBNAME.'/mri_tb_model');
        $params = array();
        $extra = array(
            'order_by' => '',
            'slavedb' => true,
        );
		//$extra['cache_sec'] = 3600*24;
		//$extra['cache_sec'] = 3600;

        echo serialize($this->common->getDataByPK($this->mri_tb_model->getList($params, $extra)->getData(), 'm_ticker'));
    }

    // Company 기입 정보 제공
    public function getCompanyList() {
        $this->load->model(DBNAME.'/company_tb_model');

        $params = array();
        //$params['!=']['cp_description'] = '';

        $extra = array(
            'order_by' => '',
            'fields' => 'cp_ticker, cp_korname, cp_product, cp_brand, cp_competition, cp_short_description, cp_description',
        );
		$extra['cache_sec'] = 3600*24;
        echo serialize($this->common->getDataByPK($this->company_tb_model->getList($params, $extra)->getData(), 'cp_ticker'));
    }

    // 티커별 디멘션별 최종 값 제공 API
    public function getLastSF1($dimension='', $ticker='') {
        ini_set('memory_limit', '1G');

        if(strlen($dimension) != 3) {
            echo '[1] Dimension Param Error';
            return;
        }
        $dimension = strtoupper($dimension);
        if( ! in_array($dimension, array('MRY','MRQ','MRT'))) {
            echo '[2] Dimension Param Error';
            return;
        }

        $params = array(); // 빈배열
        $params['>']['sf1_calendardate'] = date('Y-m-d', strtotime(' -14 month '));
        $params['=']['sf1_dimension'] = $dimension;
        $params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker and tkr_table="SF1" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
        if(strlen($ticker) > 0) {
            $ticker = strtoupper($ticker);
            if(strpos($ticker,',') !== false) {
                $ticker = explode(',',$ticker);
                $ticker = array_map('trim', $ticker);
                $ticker = array_filter($ticker);
                $params['in']['sf1_ticker'] = $ticker;
            } else {
                $params['=']['sf1_ticker'] = $ticker;
            }
        }

		$extra = array();
        $extra['fields'] = 'max(sf1_id) as sf1_id';
        $extra['order_by'] = '';
        $extra['group_by'] = 'sf1_ticker';
		$extra['cache_sec'] = 3600*24;

        $sf1_ids = array_keys($this->common->getDataByPk($this->sf1_tb_model->getList($params, $extra)->getData(), 'sf1_id'));

        if(sizeof($sf1_ids) <= 0) {
            echo 'Empty';
            return;
        }

        $params = array();
        $params['in']['sf1_id'] = $sf1_ids;

        $extra = array();
        $extra['order_by'] = '';
		$extra['cache_sec'] = 3600*24;

        $data = $this->sf1_tb_model->getList($params, $extra)->getData();
        $data = $this->historylib->getSF1ListAfterProcess($data);
        $data = $this->common->getDataByPk($data, 'sf1_ticker');

        echo serialize($data);
    }


    // 티커별 최종 Daily값 제공 API
    public function getLastDaily($ticker='') {
        ini_set('memory_limit', '1G');

        $params = array(); // 빈배열
		$params['join']['ticker_tb'] = 'dly_ticker = tkr_ticker and tkr_lastpricedate = dly_date and tkr_table = "SEP" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
        if(strlen($ticker) > 0) {
            $ticker = strtoupper($ticker);
            if(strpos($ticker,',') !== false) {
                $ticker = explode(',',$ticker);
                $ticker = array_map('trim', $ticker);
                $ticker = array_filter($ticker);
                $params['in']['tkr_ticker'] = $ticker;
            } else {
                $params['=']['tkr_ticker'] = $ticker;
            }
        }
        $extra = array();
        $extra['order_by'] = '';
        $extra['fields'] = 'daily_tb.*';
		$extra['cache_sec'] = 3600*24;
        $data = $this->common->getDataByPk($this->daily_tb_model->getList($params, $extra)->getData(), 'dly_ticker');

        echo serialize($data);
    }

    // SEP 리스트- 콤마로 최대 100개까지 조회
    public function getSEPList($ticker) {
        if(strlen($ticker) > 0) {
            $ticker = strtoupper($ticker);
            if(strpos($ticker,',') !== false) {
                $ticker = explode(',',$ticker);
                $ticker = array_map('trim', $ticker);
                $ticker = array_filter($ticker);
                if(sizeof($ticker) > 100) {
                    echo 'Too Large';
                    return;
                }
            } else {
                $ticker = array(trim($ticker));
            }
        } else {
            echo 'Check Param';
            return;
        }

        //$params = array();
        //$params['>']['sep_date'] = date('Y-m-d', time()-86400*180);

        //$extra = array();
        //$extra['order_by'] = '';
        //$extra['order_by'] = 'sep_ticker, sep_date';

        //$result = $this->sep_tb_model->getList($params, $extra)->getData();
        $data = $this->sep_tb_model->getPriceHistory($ticker, 180);

        echo serialize($data);
    }

    // 특정 티커의 경쟁사 제공
    public function getCompetitor($ticker) {
        $this->stocks->competitors_ws($ticker);
        $result = $this->stocks->getData();

        $data = $result['competitor'];
        echo serialize($data);
    }

    // 특정 티커의 투자지표 제공
    public function getInvest($ticker, $dimension) {
        $data =  $this->stocks->invest_api($ticker, $dimension);
        echo serialize($data);
    }

	//Domestic Primary 처리
	public function getPrimaryTkr($ticker) {
        $this->load->model('business/historylib');

		$data = array();
        $params = array();
        $params['=']['tkr_ticker'] = $ticker;
        $params['=']['tkr_isdelisted'] = 'N';


        $params['in']['tkr_exchange'] = array('NASDAQ', 'NYSE', 'NYSEMKT');
        //2020.08.26 수정 $params['in']['tkr_category'] = array('ADR Preferred', 'ADR Secondary', 'Canadian Preferred', 'Canadian Secondary', 'Domestic Preferred', 'Domestic Secondary');
        $params['raw'] = '(tkr_category like \'%Preferred%\' || tkr_category like \'%Secondar%\')';

        $extra = array(
            'fields' => array('tkr_name'),
            'limit' => '1'
        );
		$extra['cache_sec'] = 3600*24;
		$extra['slavedb'] = true;

		$result = $this->ticker_tb_model->getList($params, $extra)->getData();

		if(isset($result) && is_array($result)) {
			$params = array();
			$params['=']['tkr_name'] = $result[0]['tkr_name'];
			$params['=']['tkr_table'] = 'SF1';

			$extra = array(
                'order_by' => 'tkr_isdelisted',
				'fields' => array('tkr_ticker', 'tkr_name'),
				'limit' => '1'
			);
			$extra['cache_sec'] = 3600*24;
			$extra['slavedb'] = true;
			$data = $this->ticker_tb_model->getList($params, $extra)->getData();
		}
		//print_r($data);
		echo serialize($data);
	}

    // MRI Detail 제공
    public function getMRIDetail($ticker) {
        $this->load->model('business/historylib');

/*
        if( ! $this->ticker_tb_model->get(array(
			'tkr_table' => 'SEP', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            return;
        }

		//$data = $this->ticker_tb_model->getList($params, $extra)->getData();
        $tdata = $this->ticker_tb_model->getData();
		//echo '<pre>'; print_r($data);
		echo '<pre>'; print_r($tdata);
*/

		$params = array();
		$params['=']['tkr_table'] = 'SEP';
		$params['=']['tkr_ticker'] = $ticker;
		$params['=']['tkr_isdelisted'] = 'N';

		$extra = array(
			'slavedb' => true,
			'cache_sec' => 3600*24,
		);

		$tdata = array_shift($this->ticker_tb_model->getList($params, $extra)->getData());

		//exit;
        if( !isset($tdata) || $tdata['tkr_isdelisted'] == 'Y') {
            return;
        }

        $mri = $this->historylib->getTickerMRI($ticker);

        foreach($mri as $cate => &$items) {
            foreach($items as &$item) {
                $item = floatval($item);
            }
        }

        echo serialize($mri);
    }

    // MRI Detail 제공
    public function getBizMRIDetail($ticker) {
        $this->load->model('business/historylib');

        if( ! $this->ticker_tb_model->get(array(
			'tkr_table' => 'SEP', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            return;
        }
        $tdata = $this->ticker_tb_model->getData();
        if($tdata['tkr_isdelisted'] == 'Y') {
            return;
        }

        $mri = $this->historylib->getTickerCompanyMRI($ticker);
        foreach($mri as $cate => &$items) {
            foreach($items as &$item) {
                $item = floatval($item);
            }
        }

        echo serialize($mri);
    }

    public function getVChart($ticker, $dimension, $vchart_type, $indicator) {
        $this->load->model('business/historylib');
        if( ! $this->historylib->getVChartData($ticker, $dimension, $vchart_type, $indicator)->isSuccess()) {
            return;
        }
		//echo '<pre>';
		//print_r($this->historylib->getData());
		//http://us153dev.datahero.co.kr/index.php/api/getVChart/WPM/MRY/profit/salesincome
        echo serialize($this->historylib->getData());        
    }

	public function getSepLastDate() {
		$rows = $this->common->getDataByPK($this->sep_tb_model->getList(array(), array('order_by' => 'sep_date desc', 'limit' => 1))->getData(), 'sep_ticker');
        echo serialize($rows);	
	}

	public function getSepData($ticker) {
		$this->load->model(DBNAME.'/sep_tb_model');

		if(isset($ticker) && $ticker !='') {
			$params = array();
			$params['=']['sep_ticker'] = $ticker;

			$extra = array(
				'fields' => '*',
				'order_by' => 'sep_date desc',
				'limit' => '1',
				'slavedb' => true
			);

			$sep_data = $this->sep_tb_model->getList($params, $extra)->getData();
		    echo serialize($sep_data);
		}
	}
	public function getSepCount($view_date) {
		$this->load->model(DBNAME.'/sep_tb_model');

        $params = array();
        $params['=']['sep_date'] =  date('Y-m-d', strtotime($view_date));
		$params['slavedb'] = true;
        $sep_count = $this->sep_tb_model->getCount($params)->getData();
	    echo serialize($sep_count);
	}
	// todo....
}