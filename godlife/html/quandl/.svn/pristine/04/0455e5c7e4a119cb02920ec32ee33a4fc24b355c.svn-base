<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'/ge_base_pc.php';
class Ge_stocks extends Ge_BasePC_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model(array(
					'business/historylib',
					DBNAME.'/company_tb_model',
					DBNAME.'/myitem_tb_model'
					));

        $this->cache_path = WALL_WEBDATA.'/itooza_cache';
        $this->cache_path_mry = WALL_WEBDATA.'/vchart_mry';
        $this->cache_path_mrt = WALL_WEBDATA.'/vchart_mrt';
        $this->cache_path_mrq = WALL_WEBDATA.'/vchart_mrq';
        $this->cache_path_base = WALL_WEBDATA.'/base_data';
	}

	public function index()
	{
        $this->common->locationhref('/ge_stocks/summary');
		exit;
	}

	private function _getBaseData($ticker_code, $dimension='MRT', $cell_type='data', $pExtra=array()) {
		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		$pExtra['more'] = 'Y';
		if($cell_type == 'data') {
			$this->historylib->getFinStateList($ticker_code, $dimension, $pExtra);
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
			//echo 'fail...'.$ticker_code;
			//exit;
            return false;
			//$this->common->locationhref('/wm_main');
            exit;
			//return;
		}

		$result = $this->historylib->getData();

		$ticker = $result['ticker'];

        // 계약범위 초과 방지를 위한 유효성 체크
        $redirect = false;
        if(
            $ticker['tkr_isdelisted'] == 'Y' ||
            $ticker['tkr_exchange'] == 'OTC'
        ) {
			return;
        }

		$findata = $result['findata'];
		$sepdata = $result['sepdata'];

		$data = array();
		$data['dimension'] = $dimension;
		$data['cell_type'] = $cell_type;
		$data['balancesheet_type'] = $result['balancesheet_type'];
		$data['last_mry'] = $result['last_mry'];
		$data['last_mrt'] = $result['last_mrt'];
		$data['last_mrq'] = $result['last_mrq'];
		// 재무상태표 리스트
		$data['balancesheet_fields'] = @$this->sf1_tb_model->getBalancesheetFields($ticker, $findata[0]);
		$data['balancesheet_titles'] = $this->historylib->getTableMap($result['balancesheet_type'], 'Indicator', 'kortitle');

		// 손익계산서 리스트
		$data['incomestate_fields'] = $this->sf1_tb_model->getIncomestateFields();
		$data['incomestate_titles'] = $this->historylib->getTableMap('incomestate', 'Indicator', 'kortitle');

		//현금흐름표 리스트
		$data['cashflow_fields'] = $this->sf1_tb_model->getCashflowFields();
		$data['cashflow_titles'] = $this->historylib->getTableMap('cashflow', 'Indicator', 'kortitle');

		// 재무&투자지표
		$data['fininvestindi_fields'] = $this->sf1_tb_model->getFinInvestIndiFields();
		$data['fininvestindi_titles'] = $this->historylib->getTableMap('fininvestindi', 'Indicator', 'kortitle');

		// 주가지표
		$sep_title_map = $this->historylib->getTableMap('pricesheet', 'Indicator', 'kortitle');
		$data['pricesheet_fields'] = array_keys($sep_title_map);
		$data['pricesheet_titles'] = $sep_title_map;

		$last_sep = $sepdata[0];
		$data['last_sep'] = $last_sep;

		// 기업정보
		$company_info = array();
		if($this->company_tb_model->get(array('cp_ticker' => $ticker['tkr_ticker']))->isSuccess()) {
			$company_info = $this->company_tb_model->getData();
		}
		$data['company_info'] = $company_info;


		// Daily 기업정보
		$last_daily = $this->historylib->getTickerDailyLastRow($ticker['tkr_ticker']);
		$data['last_daily'] = $last_daily;

		// @ 기업 검색 리스트
		// 1. 유효 기업 전체 가져오기
		$ticker_params = array();
		$ticker_params['=']['tkr_table'] = 'SF1';
		$ticker_params['=']['tkr_isdelisted'] = 'N';

		$ticker_extra = array(
				'order_by' => 'tkr_ticker',
				'fields' => 'tkr_ticker, tkr_permaticker, tkr_name',
				);
		$ticker_list = $this->common->getDataByPK($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');

		// 2. 한국어 기업 정리
		$comp_params = array();
		$comp_params['=']['cp_is_confirmed'] = 'YES';

		$comp_extra = array(
				'order_by' => '',
				'fields' => 'cp_ticker, cp_korname'
				);
		$comp_list = $this->common->getDataByPK($this->company_tb_model->getList($comp_params, $comp_extra)->getData(), 'cp_ticker');

		// 3. 조합 
		$search_list = array();
		foreach($ticker_list as $t => $row) {
			$txt = sprintf('%s | %s (%s) ', $row['tkr_ticker'], $row['tkr_name'], $row['tkr_permaticker']);
			if(isset($comp_list[$t])) {
				$txt .= ' - '.$comp_list[$t]['cp_korname'];
			}
			$search_list[] = $txt;
		}
		$data['search_list'] = $search_list;
		$data['search_tickers'] = array_keys($ticker_list);
		// End of - 기업 검색 리스트

		// 재무&투자지표 표출형식
		$data['fininvestindi_format'] = $this->historylib->getTableMap('fininvestindi', 'Indicator', 'korunittype');
		$data['pricesheet_format'] = $this->historylib->getTableMap('pricesheet', 'Indicator', 'unittype');

		$data['data'] = $this->common->getDataByPK($findata, 'sf1_datekey');
		$data['ticker'] = $ticker;
		$data['sepdata'] = $sepdata;

        // 실시간 최근수집 주가로 덮어쓰기
        $data['ticker'] = $this->ticker_tb_model->convertSyncInfo($data['ticker']);

		$this->current_ticker_info = array(
			'name' => $ticker['tkr_name'],
			'korname' => isset($company_info['cp_korname']) ? $company_info['cp_korname'] : '',
			'ticker' => $ticker['tkr_ticker'],
			'exchange' => $ticker['tkr_exchange'],
			'diff_rate' => $last_sep['sep_diff_rate'],
			'diff_price' => $last_sep['sep_diff_price'],
			'close_price' => $last_sep['sep_close'],
		);

		$this->load->model(DBNAME.'/mri_tb_model');
        $params = array();
        $params['=']['m_ticker'] = $ticker_code;
        $extra = array(
			'limit' => '1',
            'slavedb' => true,
        );
        $data['mri_data'] = array_shift($this->mri_tb_model->getList($params, $extra)->getData());
		return $data;
	}

	public function summary($ticker='') {

		$dimension = $this->input->get('dimension', 'MRT');
		$cell_type = $this->input->get('cell_type', 'data');
		//echo '<pre>'; print_r($_COOKIE['dh_ticker']);
		//echo $ticker;
		//exit;

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}
/*
		delete_cookie('dh_ticker');
		//검색종목 쿠키생성
        set_cookie('dh_ticker', $ticker, time()+86400, '.thewm.co.kr' );
*/
		$extra = array(
			'with_summary' => true
		);

		$data = $this->_getBaseData($ticker, $dimension, $cell_type, $extra);
	
		if($data === false) {
			//delete_cookie('dh_ticker');
			//검색종목 쿠키생성
			//set_cookie('dh_ticker', 'TSLA', time()+86400, '.thewm.co.kr' );
			$this->common->locationhref('/ge_stocks/summary');
			exit;
		}

		$pTicker = $data['ticker'];
		$pSf1Row = array_shift(array_values($data['data']));

		$pExtra = array(
			'dly_lastdate' => $data['last_daily']['dly_date'],
		);
        
		$data['industry_top'] = $this->sf1_tb_model->getIndustryTop($pTicker, $pSf1Row, $pExtra);

		$data['data'] = $this->historylib->convertRows($data['data']);

		$data['last_mry_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mry']), 'sf1_datekey');
		$data['last_mrq_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mrq']), 'sf1_datekey');
		$data['last_mrt_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mrt']), 'sf1_datekey');

		$data['last_mry'] = array_shift(array_values($data['last_mry_list']));
		$data['last_mrq'] = array_shift(array_values($data['last_mrq_list']));
		$data['last_mrt'] = array_shift(array_values($data['last_mrt_list']));

        $ticker_codes = array_keys($data['industry_top']);
        $data['industry_top_seps'] = $this->sep_tb_model->getTickersPrice($ticker_codes);

		//상단 배너 3종
		$rand = rand(0,2);

		$banner_file = 'partner_banner.json';
		$file_path = WALL_WEBDATA.'/json/'.$banner_file;

		if( is_file($file_path) ) {
             $banner_list = json_decode(file_get_contents($file_path), true);
		}
		//echo '<pre>'; print_r($banner_list); exit;

		if(is_array($banner_list[$rand]) && sizeof($banner_list[$rand])>0) {
			$data['up_rand'] = $rand;
			$data['up_banner'] = $banner_list[$rand];
		}
		else {
			//상단 배너 3종
			$rand = rand(0,1);
			$data['up_rand'] = $rand;
			$data['up_banner'] = $banner_list[$rand];
		}

		$valuation_file = 'valuation_list.json';
		$file_path = WALL_WEBDATA.'/json/'.$valuation_file;

		if( is_file($file_path) ) {
             $valuation_list = json_decode(file_get_contents($file_path), true);
		}
		
		//배너 4종
		$down_rand = '4';
		$down_banner = array();
	    $this->load->model(DBNAME.'/recommend_tb_model');

		//추천종목(목표가도달)
		$params = array();
		$recommend = array();
		$params['=']['rc_ticker'] = $ticker;
		$params['=']['rc_is_active'] = 'YES';
		$params['=']['rc_endtype'] = 'SUCCESS';
		$params['!=']['rc_view_srv'] = 'W';
		$params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
		$extra = array(
			'fields' =>  array('*'),
			'order_by' => 'rc_enddate DESC',
			'limit' => '1',
			'wstreetdb'=> true,
		);
		$recommend = array_shift($this->recommend_tb_model->getList($params, $extra)->getData());

		if(is_array($recommend) && sizeof($recommend)>0) {
			$recommend['ticker_revenue'] = round((($recommend['rc_goal_price']/$recommend['rc_recom_price'])-1)*100,2);	//달성수익률

			$down_rand = '1';
			$down_banner = $recommend;
			$down_banner['valuation'] = $valuation_list[$recommend['rc_ticker']];
		}

		if(is_array($down_banner) && sizeof($down_banner)>0) {
			$data['down_rand'] = $down_rand;
			$data['down_banner'] = $down_banner;
		}

		if(is_array($down_banner) && sizeof($down_banner)==0) {
			$params = array();
			$params['=']['rc_ticker'] = $ticker;
			$params['=']['rc_is_active'] = 'YES';
			$params['=']['rc_endtype'] = 'ING';
			$params['!=']['rc_view_srv'] = 'W';
			$params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
			$extra = array(
				'fields' =>  array('*'),
				'order_by' => 'rc_id DESC',
				'limit' => '1',
				'wstreetdb'=> true,
			);

			$recommend = array_shift($this->recommend_tb_model->getList($params, $extra)->getData());

			if(is_array($recommend) && sizeof($recommend) > 0) {
				$down_rand = '2';
				$down_banner['valuation'] = $valuation_list[$recommend['rc_ticker']];
			}		

			if(is_array($down_banner) && sizeof($down_banner)>0) {
				$data['down_rand'] = $down_rand;
				$data['down_banner'] = $down_banner;
			}
		}

		if(is_array($down_banner) && sizeof($down_banner)==0) {
			if( in_array($ticker, $this->open_ticker)) {
				$down_rand = '3';
				$down_banner['valuation'] = $valuation_list[$ticker];				
			}

			if(is_array($down_banner) && sizeof($down_banner)>0) {
				$data['down_rand'] = $down_rand;
				$data['down_banner'] = $down_banner;
			}
		}

		if(is_array($down_banner) && sizeof($down_banner)==0) {
			$data['down_rand'] = $down_rand;
			$data['down_banner']['valuation'] = $valuation_list[$ticker];
			//echo '<pre>'; print_r($data);
		}

        //if(strstr($_SERVER['REMOTE_ADDR'], '61.74.181')) {
		//}

		// 기본값에서 변경 필요부는 여기서부터 덮어쓰기
		// - 하단 투자지표 항목 및 타이틀
		$summary_fields_and_titles = $this->sf1_tb_model->getFinInvestIndiFields('summary');
		$data['fininvestindi_fields'] = $summary_fields_and_titles['fields'];
		$data['fininvestindi_titles_sub'] = $summary_fields_and_titles['titles'];
		$data['is_open'] = $this->is_open;

		$this->_view('stocks/summary', $data);
	}
}