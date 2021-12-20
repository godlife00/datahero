<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/wm_base_pc.php';
class Wm_main extends Wm_BasePC_Controller{

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
		//$this->IpCheck();
		$this->load->model(array(
					'business/historylib',
					DBNAME.'/company_tb_model',
					));
	}
	public function index()
	{
		$this->header_data['is_main_index'] = false;

		$data = array();

        // Tab Info 정의. 추후 어드민에서 찍은 데이터를 이 구조로 저장하시면 작업하시기 편리할도록.
        $tab_info = array();
        $tab_info[] = array(
            'title' => '원격의료',
            'subtitle' => '비대면 의료시장 선도 기업',
            'tickers' => array('TDOC', 'LVGO') // 4개 or 2개 로 구성할것
        );
        $tab_info[] = array(
            'title' => 'MAGA',
            'subtitle' => 'IT 기술/서비스 선도 기업',
            'tickers' => array('MSFT','AAPL','GOOGL','AMZN') // 4개 or 2개 로 구성할것
        );
        $tab_info[] = array(
            'title' => '반도체',
            'subtitle' => '5G 시대를 이끌 반도체 기업',
            'tickers' => array('QCOM', 'AVGO', 'TSM', 'NVDA')       // 4개 or 2개 로 구성할것
        );
        $tab_info[] = array(
            'title' => '코로나19',
            'subtitle' => '코로나19 백신 개발 중인 기업',
            'tickers' => arraY('MRNA', 'AZN', 'JNJ', 'BNTX')          // 4개 or 2개 로 구성할것
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


        // @ 공통처리
        // 노출 종목들 가격 및 등락율 정보 구하기
        $ticker_price_map = $this->sep_tb_model->getTickersPrice($display_tickers);
        $data['ticker_price_map'] = $ticker_price_map;
		//echo '<pre>'; print_r($ticker_price_map);
        // ticker 테이블 기본 정보들 채우기 (base_pc에서 일괄 구하고 있음. 상단 종목검색 json생성 때문에.)
        $data['ticker_info_map'] = $this->ticker_info_map;

        // 한글 기업정보
        $data['ticker_korean_map'] = $this->ticker_korean_map;

		$snp500_ticker_codes = array();
		$snp500_ticker_codes = $this->get_snp500();
		shuffle($snp500_ticker_codes);
		$snp500_ticker_codes = array_slice($snp500_ticker_codes, 0, 20);
		
		$snp500_info = array();

		$cnt=0;
		foreach($snp500_ticker_codes as $val) { 
			if($cnt>5) break;
			$tkr_info = array();
			$tkr_info = $this->_getBaseData_tkr($val);
			if( isset($tkr_info) && is_array($tkr_info) ) {
				$snp500_info[] = $tkr_info;
				$cnt++;
			}
		}

		$data['snp500_info'] = $snp500_info;

		$recent_file = 'recent_report.inc';
		$file_path = WEBDATA.'/'.$recent_file;
		
		if( is_file($file_path) ) {
		
            $recent_report = unserialize(file_get_contents($file_path));
			$data['recent_report'] = $recent_report['recent_report'];
			$data['recent_report_rates'] = $recent_report['recent_report_rates'];
			$data['recent_report_rates_pm'] = $recent_report['recent_report_rates_pm'];
		}

		$data['last_date'] = $this->historylib->getDailyLastDate();

 		$this->wm_view('main/wm_index', $data);
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
		$resp = json_decode($this->hamtcrawler->getBody('https://www.quandl.com/api/v3/datatables/SHARADAR/'.strtoupper($file).'.json?api_key='.QDAPI_KEY.'&qopts.export=true'), true);
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

        // company info
        $params = array();
        $params['=']['cp_ticker'] = $ticker_code;
        $extra = array();
        $extra['limit'] = 1;
		$extra['slavedb'] = true;
		$extra['cache_sec'] = 3600*24; ;
        $company_info = array_shift($this->company_tb_model->getList($params, $extra)->getData());
        $ticker['company_info'] = $company_info;

        $last_daily = $this->historylib->getTickerDailyLastRow($ticker_code);
        $ticker['last_daily'] = $last_daily;

/*
        $company_info = array();
        if($this->company_tb_model->get(array('cp_ticker' => $ticker['tkr_ticker']))->isSuccess()) {
            $company_info = $this->company_tb_model->getData();
        }
        $data['company_info'] = $company_info;


        // Daily 기업정보
        $last_daily = $this->historylib->getTickerDailyLastRow($ticker['tkr_ticker']);
        $data['last_daily'] = $last_daily;
*/
		return $ticker;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
