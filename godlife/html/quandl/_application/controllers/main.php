<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base_pc.php';
class Main extends BasePC_Controller{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
        //$this->loginCheck();
		$this->IpCheck();
		$this->load->model(array(
					'business/historylib',
					DBNAME.'/company_tb_model',
					));
	}
	public function index()
	{
        if(IS_REAL_SERVER) {
	        $this->common->locationhref('/wm_main');
			exit;
		}
		else {
			if(!strstr($_SERVER['REMOTE_ADDR'], '61.74.181')) {
				redirect('https://www.choicestock.co.kr');
				exit;
			}
		}

		$this->header_data['is_main_index'] = true;

		$data = array();

        // Tab Info 정의. 추후 어드민에서 찍은 데이터를 이 구조로 저장하시면 작업하시기 편리할도록.
        $tab_info = array();
        $tab_info[] = array(
            'title' => 'FANG',
            'subtitle' => '미국 IT 업계를 선도하는 기업',
            'tickers' => array('FB', 'AMZN', 'NFLX', 'GOOGL')     // 4개 or 2개 로 구성할것
        );
        $tab_info[] = array(
            'title' => '태양광',
            'subtitle' => '신재생 에너지 선도 기업',
            'tickers' => array('JKS', 'CSIQ','FSLR','SEDG')                       // 4개 or 2개 로 구성할것
        );
        $tab_info[] = array(
            'title' => '4차산업',
            'subtitle' => 'IT기술로 4차산업을 이끄는 혁신기업',
            'tickers' => array('UBER', 'TSLA', 'NVDA', 'AMD')       // 4개 or 2개 로 구성할것
        );
        $tab_info[] = array(
            'title' => '5G',
            'subtitle' => '빅 데이터 핵심 기술 기업',
            'tickers' => arraY('QCOM', 'XLNX', 'AVGO', 'SWKS')          // 4개 or 2개 로 구성할것
        );
        $data['tab_info'] = $tab_info;


        // 텝 내 티커 차트 가져오기
        $chart_tickers = array();   // 텝에 노출되는 차트 필요한 티커들 모음
        foreach($tab_info as $info) {
            $chart_tickers = array_merge($chart_tickers, $info['tickers']);
        }

        // 텝 내 티커별 차트 데이터 획득.
        $ticker_chart_map = $this->sep_tb_model->getPriceHistory($chart_tickers, 30);
        $data['ticker_chart_map'] = $ticker_chart_map;

        // 메인에 노출되는 티커 코드 총 모음. @ ==> 최종가 조회용
        // 노출되는 모든 티커 코드 $display_tickers 에 다 담기.
        $display_tickers = $chart_tickers; 

        // @ 급등종목 -->  가격정보 필요. display_tkckers에 담기.
        $top_plus_ticker_codes = $this->sep_tb_model->getTopPlusTickers(20);

        $data['top_plus_ticker_codes'] = $top_plus_ticker_codes;
        $display_tickers = array_merge($display_tickers, $top_plus_ticker_codes);


        // @ 인기종목. 일단 우측 카드처럼 지정종목 중 랜덤.
        // -->  가격정보 필요. display_tkckers에 담기.
        $shuffle_ticker_codes = array(
                'MSFT', 'AAPL', 'GOOGL', 'AMZN', 'NFLX', 
                'DIS', 'UBER', 'TSLA', 'NVDA', 'AMD', 
                'SBUX', 'KO', 'MCD', 'NKE', 'INTC', 
                'FB', 'BABA', 'CSCO', 'BA', 'BRK.B', 
                'JNJ', 'WMT', 'BIDU', 'IBM', 'GM', 
                'T', 'GS', 'TWTR', 'ATVI', 'MMM'
        );
        shuffle($shuffle_ticker_codes);
        $popular_ticker_codes = array_slice($shuffle_ticker_codes, 0, 5);
        $data['popular_ticker_codes'] = $popular_ticker_codes;
        $display_tickers = array_merge($display_tickers, $popular_ticker_codes);


        // @ 투자종목 발굴
		$per_list = $this->historylib->getRankList('per');
        $pbr_list = $this->historylib->getRankList('pbr');
		$roe_list = $this->historylib->getRankList('roe');
		$yield_list = $this->historylib->getRankList('yield');
        $invest_finder = array(
            'low_per'   => $per_list,
            'low_pbr'   => $pbr_list,
            'high_roe' => $roe_list,
            'high_yield'=> $yield_list,
        );
        $data['invest_finder'] = $invest_finder;


        // @ 투자종목들의 주당순이익 분기값 구하기
        $data['ticker_history_map'] = array();
        $data['ticker_history_dates'] = array();
        foreach($invest_finder as $ifinder_type => $group) {


            if(sizeof($group) <= 0) {
                // 아침 API Sync중에 접근으로 캐싱이 떠지는 등 예외시 렌더링 지정 없도록 처리.
                $data['ticker_history_map'][$ifinder_type] = array();
                continue;
            }

            $params = array();
            $params['in']['sf1_ticker'] = array_keys($this->common->getDataByPk($group, 'tkr_ticker'));

			//if(strstr($_SERVER['REMOTE_ADDR'], '61.74.181') && $ifinder_type == 'high_roe') {
			if( $ifinder_type == 'high_roe') {
				//echo '<pre>'; print_r($date_list);
				$params['<']['sf1_datekey'] = '2020-01-01';
			}	
			else if( $ifinder_type == 'high_yield') {
				//echo '<pre>'; print_r($date_list);
				$params['<']['sf1_datekey'] = '2020-01-01';
			}	

            $extra = array();
            $extra['order_by'] = 'sf1_ticker, sf1_datekey desc';
            $extra['cache_sec'] = 3600;
            $extra['fields'] = array(
                    '*',
                    'date_format(sf1_calendardate, "%Y.%m") as sf1_datekey',
                    );

            $flag_shift = false;
            if($ifinder_type == 'high_yield') {
                $flag_shift = true;
            }
            switch($ifinder_type) {
                case 'low_per' :
                case 'low_pbr' :
                    $params['sf1_dimension'] = 'MRQ';
                    $params['>=']['sf1_reportperiod'] = (date('Y')-2).'-01-01';
                    break;
                case 'high_roe' :
                case 'high_yield' :
                    $params['sf1_dimension'] = 'MRY';
                    $params['>=']['sf1_reportperiod'] = (date('Y')-6).'-01-01';
                    break;
            }

            $ticker_history = $this->common->getDataByDuplPK($this->sf1_tb_model->getList($params, $extra)->getData(), 'sf1_ticker');
            foreach($ticker_history as &$tkrlist) {
                $tkrlist = $this->historylib->getSF1ListAfterProcess($tkrlist);
            }

            foreach($ticker_history as $tik => &$history_list) {
                $history_list = $this->common->getDataByPK($history_list, 'sf1_datekey');

                if(sizeof($history_list) > 4) {
                    if($flag_shift) {
                        $history_list = array_slice($history_list, 1, 4);
                    } else {
                        $history_list = array_slice($history_list, 0, 4);
                    }
                } else if($flag_shift) {
                    array_shift($history_list);
                }
            }
            $data['ticker_history_map'][$ifinder_type] = $ticker_history;
        }

        // @ 공통처리
        // 노출 종목들 가격 및 등락율 정보 구하기
        $ticker_price_map = $this->sep_tb_model->getTickersPrice($display_tickers);
        $data['ticker_price_map'] = $ticker_price_map;

        // ticker 테이블 기본 정보들 채우기 (base_pc에서 일괄 구하고 있음. 상단 종목검색 json생성 때문에.)
        $data['ticker_info_map'] = $this->ticker_info_map;

        // 한글 기업정보
        $data['ticker_korean_map'] = $this->ticker_korean_map;


        //$data['ticker_priceinfo_map'] = $this->ticker_priceinfo_map;
		//print_r($data['ticker_priceinfo_map']);
		
		//주요지표 가져오기
		//$us_index_info= $this->_get_usindex();

		$us_index_file = 'us_index.inc';
		$strWPath = WEBDATA.'/'.$us_index_file;

		$us_index_info = file_get_contents($strWPath);

		//$strUrl = 'http://www.itooza.com/stock/vUsindex.htm';
		//$us_index_info = $this->get_content($strUrl);
		//$us_index_info = '20190816|1,341.49|-|17.00|1.25%@금($/온스) |1,517.50|-|7.6|0.50%@브렌트유|58.64|+|0.41|0.70%@WIT 원유|54.87|+|0.4|0.73%@천연가스|2.20|-|0.03|1.35%';

		if($us_index_info) {
			$us_index_sep = explode('@', $us_index_info);
			$idx = 0;
			foreach( $us_index_sep as $val ) {
				$arr_us_index[$idx] = explode('|', $val); 
				$idx++;
			}
			//원/달러
			$data['us_index0_1'] = $arr_us_index[0][1];
			$data['us_index0_2'] = $arr_us_index[0][2];
			$data['us_index0_3'] = $arr_us_index[0][3];
			$data['us_index0_4'] = $arr_us_index[0][4];

			//금($/온스)
			$data['us_index1_1'] = $arr_us_index[1][1];
			$data['us_index1_2'] = $arr_us_index[1][2];
			$data['us_index1_3'] = $arr_us_index[1][3];
			$data['us_index1_4'] = $arr_us_index[1][4];

			//브렌트유
			$data['us_index2_1'] = $arr_us_index[2][1];
			$data['us_index2_2'] = $arr_us_index[2][2];
			$data['us_index2_3'] = $arr_us_index[2][3];
			$data['us_index2_4'] = $arr_us_index[2][4];
			
			//원유
			$data['us_index3_1'] = $arr_us_index[3][1];
			$data['us_index3_2'] = $arr_us_index[3][2];
			$data['us_index3_3'] = $arr_us_index[3][3];
			$data['us_index3_4'] = $arr_us_index[3][4];

			//천연가스啊胶
			$data['us_index4_1'] = $arr_us_index[4][1];
			$data['us_index4_2'] = $arr_us_index[4][2];
			$data['us_index4_3'] = $arr_us_index[4][3];
			$data['us_index4_4'] = $arr_us_index[4][4];
		}
		else {
			$data['us_index0_1'] = '';
		}

		//관심종목 처리
		$data['my_ticker_list'] = '';
		if( $this->session->userdata('is_login') === true ) {
			$my_ticker_list = '';
			$my_ticker_list = get_cookie('my_ticker');
			if($my_ticker_list) {
				$arr_my_ticker_list = explode('|', $my_ticker_list);

				$my_ticker_price_map = $this->sep_tb_model->getTickersPrice($arr_my_ticker_list);
				//print_r($my_ticker_price_map);
				$data['my_ticker_list'] = $my_ticker_price_map;
			}
		}

		//종목토론 
		$opinion_file = 'opinion_list.inc';
		$strOPath = WEBDATA.'/'.$opinion_file;
		$opinion_info = file_get_contents($strOPath);
		$opinion_info =  json_decode($opinion_info);

		$arr_opinion_info = array();
		
		$row=0;
		$key=0;
		foreach($opinion_info as $nKey=>$nVal) {

			if($row==0) {
				$arr_opinion_info[$key]['mo_ticker'] = $nVal->mo_ticker;
				$arr_opinion_info[$key]['mo_opinion'] = $nVal->mo_opinion;
				$arr_opinion_info[$key]['mo_count'] = $nVal->mo_count;
			}
			else {
				$cnt=0;
				foreach($arr_opinion_info as $dKey=>$dVal) {
					if($arr_opinion_info[$dKey]['mo_ticker'] == $nVal->mo_ticker)
						$cnt++;
				}
				if($cnt==0) {
					$key++;
					$arr_opinion_info[$key]['mo_ticker'] = $nVal->mo_ticker;
					$arr_opinion_info[$key]['mo_opinion'] = $nVal->mo_opinion;
					$arr_opinion_info[$key]['mo_count'] = $nVal->mo_count;
				}
			} 
			$row++;
		}

		$data['opinion_list'] = $arr_opinion_info;

		//우측 종목 박스 정보
        shuffle($shuffle_ticker_codes);
        $right_ticker_codes = array_slice($shuffle_ticker_codes, 0, 3);


		$right_ticker_info = array();

		foreach($right_ticker_codes as $nKey=>$nVal) {
		
			$company_info = array();
			if($this->company_tb_model->get(array('cp_ticker' => $nVal))->isSuccess()) {
				$company_info = $this->company_tb_model->getData();
			}

			$right_ticker_info[$nVal] = $company_info;	

			$arr_ticker_codes = array( $nVal );

			$right_ticker_info[$nVal] = array_merge($right_ticker_info[$nVal], $this->sep_tb_model->getTickersPrice($arr_ticker_codes));
			$right_ticker_info[$nVal]  = array_merge($right_ticker_info[$nVal], $this->historylib->getTickerDailyLastRow($nVal));
			$right_ticker_info[$nVal]  = array_merge($right_ticker_info[$nVal], $this->_getBaseData_tkr($nVal));
		}

		$data['right_ticker_info'] = $right_ticker_info;
		//echo '<pre>';
		//print_r($right_ticker_info);

 		$this->_view('main/index', $data);
	}

	// Rank list
	public function rank() {
		$this->load->model(array(
					'business/historylib',
					));

                    $this->sep_tb_model->saveCacheAllTickers();exit;

		$per_list = $this->historylib->getRankList('per');
		echo '<pre>LOW PER';
		print_r($per_list);
		echo '</pre>';

		$pbr_list = $this->historylib->getRankList('pbr');
		echo '<pre>LOW PBR';
		print_r($pbr_list);
		echo '</pre>';

		$roe_list = $this->historylib->getRankList('roe');
		echo '<pre>HIGH ROE';
		print_r($roe_list);
		echo '</pre>';

		$yield_list = $this->historylib->getRankList('yield');
		echo '<pre> HIGH YIELD';
		print_r($yield_list);
		echo '</pre>';
	}



	private function _get_usindex() {
		$strURL = 'https://www.itooza.com/stock/vUsindex.htm';
		return file_get_contents($strURL);
	}


	public function test() {
		//echo file_get_contents('http://www.itooza.com/stock/vExchange.htm');
		return;

		// map test
		$this->load->model('business/historylib');
		$field_unittype_map = $this->historylib->getTableMap('fininvestindi', 'Indicator', 'korunittype');
		print_r($field_unittype_map);
	}


	// Bulk 전체 파일 저장 후 적용
	public function save_file($file) {
		set_time_limit(9999);
		ini_set('memory_limit', '2G');
		$file = strtolower($file);
		if( ! in_array($file, array('ticker','indicator', 'sf1', 'sf2', 'sf3', 'sf3a', 'sf3b', 'sep', 'daily', 'sp500', 'actions'))) {
			echo $file.' is not filename.';return;
		}


		$this->load->library(array(
					'HamtCrawler'
					));
		echo strtoupper($file).' Crawling...';
		$resp = json_decode($this->hamtcrawler->getBody('https://www.quandl.com/api/v3/datatables/SHARADAR/'.strtoupper($file).'.json?api_key=J_xMzybiszvGsxUi1bMC&qopts.export=true'), true);
		echo "OK\n";

		$url = $resp['datatable_bulk_download']['file']['link'];
		//$ext = strtolower(array_pop(explode('.', $url)));
		$ext = 'zip';


		echo 'url : '.$url.' to '.WEBDATA.'/'.$file.'.'.$ext;
		echo "\n\n";
		echo 'Downloading....';

		$file_path = WEBDATA.'/'.$file.'.'.$ext;

		file_put_contents($file_path, file_get_contents($url));
		echo "OK\n";

		echo 'unzip...';
		shell_exec('unzip '.$file_path.' -d '.WEBDATA);
		echo "OK\n";
		echo 'unzip...';
		shell_exec('mv '.WEBDATA.'/SHARADAR_'.strtoupper($file).'_* '.WEBDATA.'/'.$file.'.csv');
		echo "OK\n";


		shell_exec('nohup /usr/local/bin/php '.APP_PATH.'/index.php daemon sync_bulk_target '.$file.' > '.APP_PATH.'/logdata/'.$file.'.log &');

	}

	private function _getBaseData_tkr($ticker_code, $dimension='MRT', $cell_type='data', $pExtra=array()) {
		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		$pExtra = array(
			'with_summary' => true
		);

		if($cell_type == 'data') {
			$this->historylib->getFinStateList($ticker_code, $dimension, $pExtra);
		} else {
			// cell_type == ratio
			$this->historylib->getFinStateRatioList($ticker_code, $dimension, $pExtra);
		}

		if( ! $this->historylib->isSuccess()) {
			echo $this->historylib->getErrorMsg();
			return;
		}

		$result = $this->historylib->getData();
		$ticker['ticker'] = $result['ticker'];
		$ticker['sepdata'] = array_shift($result['sepdata']);
		$ticker['last_mry'] = array_shift($result['last_mry']);
		$ticker['last_mrt'] = array_shift($result['last_mrt']);

		return $ticker;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
