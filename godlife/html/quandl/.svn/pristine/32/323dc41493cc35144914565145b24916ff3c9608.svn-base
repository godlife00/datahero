<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'//wm_base_pc.php';
class Wm_stocks extends Wm_BasePC_Controller{
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
	private function _getBaseData($ticker_code, $dimension='MRT', $cell_type='data', $pExtra=array()) {
		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

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
            $this->common->locationhref('/wm_main');
            exit;
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
		$data['is_open'] = $this->is_open;

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

		delete_cookie('dh_ticker');
		//검색종목 쿠키생성
        set_cookie('dh_ticker', $ticker, time()+86400, '.thewm.co.kr' );

		$extra = array(
			'with_summary' => true
		);

		$data = $this->_getBaseData($ticker, $dimension, $cell_type, $extra);
		
		if($data === false) {
			delete_cookie('dh_ticker');
			//검색종목 쿠키생성
			set_cookie('dh_ticker', 'TSLA', time()+86400, '.thewm.co.kr' );
			$this->common->locationhref('/wm_main');
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


		// 기본값에서 변경 필요부는 여기서부터 덮어쓰기
		// - 하단 투자지표 항목 및 타이틀
		$summary_fields_and_titles = $this->sf1_tb_model->getFinInvestIndiFields('summary');
		$data['fininvestindi_fields'] = $summary_fields_and_titles['fields'];
		$data['fininvestindi_titles_sub'] = $summary_fields_and_titles['titles'];

		$this->wm_view('stocks/wm_summary', $data);
	}
	public function invest($ticker='') {
		$is_ajax = ($this->input->get('ajax') == 'Y');

		$country_unitnum_map = $this->sf1_tb_model->getCountryUnitnumMap();
		$country_unitnum = $this->input->get('country_unitnum', 'us_백만');
		$tab_idx = $this->input->get('tab_idx');
		$dimension = $this->input->get('dimension', 'MRT');
		$cell_type = $this->input->get('cell_type', 'data');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}
		if( ! $country_unitnum || ! array_key_exists($country_unitnum, $country_unitnum_map)) {
			$country_unitnum = 'us_백만';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}

		$data = $this->_getBaseData($ticker, $dimension, $cell_type);

		if($data === false) {
			delete_cookie('dh_ticker');
			//검색종목 쿠키생성
			set_cookie('dh_ticker', 'TSLA', time()+86400, '.thewm.co.kr' );
			$this->common->locationhref('/wm_main');
			exit;
		}

		$data['country_unitnum'] = $country_unitnum;
		$data['country_unitnum_map'] = $country_unitnum_map;
		$data['tab_idx'] = intval($tab_idx);

		list($country, $unitnum) = explode('_', $country_unitnum, 2);
		$extra = array(
			'convert_type' => $country, 
			'unitnum' => $unitnum,
		);
		$data['data'] = $this->historylib->convertRows($data['data'], $extra);

		// - 투자지표 항목 및 타이틀
		$summary_fields_and_titles = $this->sf1_tb_model->getFinInvestIndiFields('invest');
		$data['fininvestindi_fields'] = $summary_fields_and_titles['fields'];
		//$data['fininvestindi_titles'] = $summary_fields_and_titles['titles'];

		$data['field_servicecategory_map'] = $this->historylib->getTableMap('fininvestindi', 'Indicator', 'servicecategory');

		//$data['like'] = $this->_like($ticker);	
		//$data['opinion'] = $this->_opinion($ticker);

        if( ! $is_ajax) {
		    $this->wm_view('stocks/wm_invest', $data);
        } else {
            $content = $this->wm_view('stocks/wm_invest', $data, true);
            list($head, $result, $tail) = explode('<!-- TABLE SCROLL DIV -->', $content, 3);
            echo $result;
        }
	}
	public function financials($ticker='') {
        $is_ajax = ($this->input->get('ajax') == 'Y');

		$country_unitnum_map = $this->sf1_tb_model->getCountryUnitnumMap();
		$country_unitnum = $this->input->get('country_unitnum', 'us_백만');
		$tab_idx = $this->input->get('tab_idx');
		$dimension = $this->input->get('dimension', 'MRY');
		$cell_type = $this->input->get('cell_type', 'data');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}
		if( ! $country_unitnum || ! array_key_exists($country_unitnum, $country_unitnum_map)) {
			$country_unitnum = 'us_백만';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}

		$extra = array(
			'func_name' => 'financials'
		);

		$data = $this->_getBaseData($ticker, $dimension, $cell_type, $extra);

		if($data === false) {
			delete_cookie('dh_ticker');
			//검색종목 쿠키생성
			set_cookie('dh_ticker', 'TSLA', time()+86400, '.thewm.co.kr' );
			$this->common->locationhref('/wm_main');
			exit;
		}

		$data['country_unitnum'] = $country_unitnum;
		$data['country_unitnum_map'] = $country_unitnum_map;
		$data['tab_idx'] = intval($tab_idx);

		//$data['like'] = $this->_like($ticker);	
		//$data['opinion'] = $this->_opinion($ticker);
		
		list($country, $unitnum) = explode('_', $country_unitnum, 2);
		$extra = array(
			'convert_type' => $country, 
			'unitnum' => $unitnum
		);

		$data['ticker_currency'] = $this->ticker_currency[$data['ticker']['tkr_currency']][0];
		$data['ticker_unit'] = $this->ticker_currency[$data['ticker']['tkr_currency']][1];

		$data['data'] = $this->historylib->convertRows($data['data'], $extra);
        if( ! $is_ajax) {
		    $this->wm_view('stocks/wm_financials', $data);
        } else {
            $content = $this->wm_view('stocks/wm_financials', $data, true);
            list($head, $result, $tail) = explode('<!-- TABLE SCROLL DIV -->', $content, 3);
            echo $result;
        }
	}
	public function competitors($ticker='') {
		$country_unitnum_map = $this->sf1_tb_model->getCountryUnitnumMap();
		$country_unitnum = $this->input->get('country_unitnum', 'us_백만');
		$dimension = $this->input->get('dimension', 'MRT');
		$cell_type = $this->input->get('cell_type', 'data');
		$tab_idx = $this->input->get('tab_idx');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}
		if( ! $country_unitnum || ! array_key_exists($country_unitnum, $country_unitnum_map)) {
			$country_unitnum = 'us_백만';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}

		$data = $this->_getBaseData($ticker, $dimension, $cell_type);

		if($data === false) {
			delete_cookie('dh_ticker');
			//검색종목 쿠키생성
			set_cookie('dh_ticker', 'TSLA', time()+86400, '.thewm.co.kr' );
			$this->common->locationhref('/wm_main');
			exit;
		}

		$data['country_unitnum'] = $country_unitnum;
		$data['country_unitnum_map'] = $country_unitnum_map;
		$data['tab_idx'] = intval($tab_idx);

		$pTicker = $data['ticker'];
		$pSf1Row = array_shift(array_values($data['data']));

		$pExtra = array(
			'dly_lastdate' => $data['last_daily']['dly_date'],
		);

		list($country, $unitnum) = explode('_', $country_unitnum, 2);
		$extra = array(
			'convert_type' => $country, 
			'unitnum' => $unitnum
		);
		$data['data'] = $this->historylib->convertRows($data['data'], $extra);
		
		$competitor = $this->sf1_tb_model->getCompetitor($pTicker, $pSf1Row, $pExtra);
		$competitor = $this->historylib->convertRows($competitor, $extra);

		$data['competitor'] = $competitor;

		$industry_top = $this->sf1_tb_model->getIndustryTop($pTicker, $pSf1Row, $pExtra);
		$industry_top = $this->historylib->convertRows($industry_top, $extra);
		$data['industry_top'] = $industry_top;

		$competitor_fields_and_titles = $this->sf1_tb_model->getCompetitorFields();
		$data['competitor_fields'] = $competitor_fields_and_titles['fields'];
		$data['competitor_titles'] = $competitor_fields_and_titles['titles'];

		$data['industry_top_fields'] = $competitor_fields_and_titles['fields'];
		$data['industry_top_titles'] = $competitor_fields_and_titles['titles'];

		$data['country_unitnum_map'] = $country_unitnum_map;

		//$data['like'] = $this->_like($ticker);	
		//$data['opinion'] = $this->_opinion($ticker);

		$this->wm_view('stocks/wm_competitors', $data);
	}

	public function trends() {

		$params = array();
        $params['!=']['tkr_sector'] = '';

		$extra = array(
				'fields' => 'tkr_sector',
				'order_by' => 'tkr_sector',
				'wstreetdb'=> true,
                'cache_sec' => 3600*12,
				);

		$sector = $this->ticker_tb_model->getList($params, $extra)->getData();

		$sector_list = array();
		foreach($sector as $sec) {
			$sector_list[] = $sec['tkr_sector'];
		}
		$sector_list = array_values(array_unique($sector_list));

		$data = array();
		
		$data['sector_list'] = $sector_list;

		$selected_sec = $this->input->get('selected_sec');

		if($selected_sec == '') {
			$selected_sec = $sector_list[0];
		}

		$limit = 30;
		
		//전체 종목 수
		$params = array();
        $params['raw'] = array('tkr_lastpricedate = (select max(tkr_lastpricedate) from ticker_tb)');
        $params['=']['tkr_sector'] = $selected_sec;
        $params['=']['tkr_is_active'] = 'YES';
        $params['=']['sf1_dimension'] = 'MRT';
		$params['join']['sf1_tb'] = 'tkr_ticker = sf1_ticker';

		$extra = array(
			'fields' => 'tkr_ticker, tkr_name, tkr_sector, tkr_close, tkr_rate_str, tkr_diff, tkr_rate',
			'order_by' => 'tkr_rate desc',
			'wstreetdb'=> true,
            'cache_sec' => 1800,
            'limit' => $limit
		);

		$total_params = array();
		$total_params = $params;
		$total_params['wstreetdb'] = true;
		$data['sec_total_count'] = $this->ticker_tb_model->getCount($total_params)->getData();


		//상승종목
		$rise_params = array();
		$rise_params = $params;
		$rise_params['>']['tkr_rate'] = 0;
		$rise_params['wstreetdb'] = true;
		$data['sec_rise_count'] = $this->ticker_tb_model->getCount($rise_params)->getData();

		$rise_params = array();
		$rise_params = $params;
		$rise_params['>']['tkr_rate'] = 0;
		$data['rise_ticker'] = $this->ticker_tb_model->getList($rise_params, $extra)->getData();


		//하락종목
		$fall_params = array();
		$fall_params = $params;
		$fall_params['<']['tkr_rate'] = 0;
		$fall_params['wstreetdb'] = true;
		$data['sec_fall_count'] = $this->ticker_tb_model->getCount($fall_params)->getData();

		$fall_params = array();
		$fall_params = $params;
		$fall_params['<']['tkr_rate'] = 0;

		$extra = array(
			'fields' => 'tkr_ticker, tkr_name, tkr_sector, tkr_close, tkr_rate_str, tkr_diff, tkr_rate',
			'order_by' => 'tkr_rate asc',
			'wstreetdb'=> true,
            'cache_sec' => 1800,
            'limit' => $limit
		);

		$data['fall_ticker'] = $this->ticker_tb_model->getList($fall_params, $extra)->getData();

		$data['last_date'] = $this->historylib->getDailyLastDate();
		$data['selected_sec'] = $selected_sec;

		$this->wm_view('stocks/wm_trends', $data);
	}

	public function performance($page = '1') {

        $this->load->model('business/historylib');
		$this->load->library('pagination');

		if($page == null){
			$page = 1;
		}

		$recent_file = 'recent_report.inc';
		$file_path = WEBDATA.'/'.$recent_file;
		
		if( is_file($file_path) ) {
            $recent_report_file = unserialize(file_get_contents($file_path));
		}

		// [recent_report_day] => 2020-06-22

		// 리스트 데이터 호출 설정
		$PAGE_ARTICLE_ROW = 12;
		$PAGE_OFFSET = ($page - 1) * $PAGE_ARTICLE_ROW;

		$params = array();
		$params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker';
		$params['join']['daily_tb'] = 'tkr_ticker = dly_ticker';
		$params['=']['sf1_dimension'] = 'MRQ'; 
		$params['=']['tkr_is_active'] = 'YES'; 
        $params['<=']['DATEDIFF(sf1_lastupdated, sf1_reportperiod)'] = '60'; 

		$params['wstreetdb'] = true;
		$TOTAL_COUNT = $this->sf1_tb_model->getCount($params)->getData();

		$data = array();
		
		if($page == 1 && sizeof($recent_report_file)>0) {

			$data['recent_report'] = $recent_report_file['recent_report'];
			$data['recent_report_rates'] = $recent_report_file['recent_report_rates'];
			$data['recent_report_rates_pm'] = $recent_report_file['recent_report_rates_pm'];
			$recent_report_day = $recent_report_file['recent_report_day'];
		}
		else {	
			// 최근 실적발표
			$params = array();
			$params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker';
			$params['join']['daily_tb'] = 'tkr_ticker = dly_ticker';
			$params['=']['sf1_dimension'] = 'MRQ'; 
			$params['=']['tkr_is_active'] = 'YES'; 
	        $params['<=']['DATEDIFF(sf1_lastupdated, sf1_reportperiod)'] = '60'; 
			$extra = array(
				'fields' => array('tkr_ticker', 'tkr_name', 'tkr_rate', 'tkr_rate_str', 'tkr_close', 'tkr_exchange', 'sf1_netinccmnusd', 'dly_marketcap'),
				'order_by' => 'sf1_lastupdated desc, sf1_netinccmnusd desc',
				'offset' => ($page-1) * $PAGE_ARTICLE_ROW,
				'limit' => $PAGE_ARTICLE_ROW,
				'cache_sec'=> 3600*2,
				'wstreetdb'=> true
			);

			$recent_report = $this->sf1_tb_model->getList($params, $extra)->getData();
	        $data['recent_report'] = $recent_report;

			// 최근 실적발표 전년동기 대비 실적
			$recent_report_tickers = array_keys($this->common->getDataByPK($recent_report, 'tkr_ticker'));
			$recent_report_rates = $this->historylib->getIncomeGrowthRate($recent_report_tickers);
			//echo '<pre>'; print_r($recent_report_rates); 
			$data['recent_report_rates'] = $recent_report_rates['rate'];
			$data['recent_report_rates_pm'] = $recent_report_rates['rate_pm'];
			$recent_report_day = array_shift(array_values($recent_report_rates['lastupdated']));
			//echo $recent_report_day;
			//exit;

		}		
		$data['recent_report_day'] = $recent_report_day;

		// 페이징
		$config['base_url'] = '/wm_stocks/performance';
		$config['total_rows'] = $TOTAL_COUNT;
		$config['per_page'] = $PAGE_ARTICLE_ROW;
		$config['use_page_numbers'] = TRUE;
        $config['suffix'] = '?'.http_build_query($_GET, '', "&");
		$config['uri_segment'] = 3;

		$config['first_link'] = '<img src="/img/wm/prev_p.png" alt="">';
		$config['last_link'] = '<img src="/img/wm/next_p.png" alt="">';
		$config['prev_link'] = '<img src="/img/wm/prev.png" alt="">';
		$config['next_link'] = '<img src="/img/wm/next.png" alt="">';
		$config['cur_tag_open'] = '<a href=""><strong>';
		$config['cur_tag_close'] = '</strong></a>';
		$config['num_links']=5;

		$this->pagination->initialize($config);
		$paging_html = $this->pagination->create_links();

		$data['total_rows'] = $TOTAL_COUNT;
		$data['paging_html'] = $paging_html;

		$this->wm_view('stocks/wm_performance', $data);
	}


    public function getCharmFinanceVChartFile($ticker_code, $dimension) {

        $dimension = strtoupper(trim($dimension));
        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) return;

        $indicator_charttype = array(
        'salesincome'   =>  'profit',
        'margin'        =>  'profit',

        'debtcr'        =>  'safety',

        'dividend'      =>   'structure',

        'roepbr'        =>  'efficiency',
        'turnoverdays'  =>  'efficiency',

        'cashflow'      =>    'cashflow',

        'per'           =>   'valuation',
        'priceeps'      =>   'valuation',
        'pbr'           =>   'valuation',
        'pricebps'      =>   'valuation',
        );

        $dimension_indi_map = array();
        $dimension_indi_map['MRT']['debtcr'] = 'MRQ';
        $dimension_indi_map['MRT']['pbr'] = 'MRQ';
        $dimension_indi_map['MRT']['pricebps'] = 'MRQ';

        $dimension_indi_map['MRQ']['roepbr'] = 'MRT';
        $dimension_indi_map['MRQ']['turnoverdays'] = 'MRT';
        $dimension_indi_map['MRQ']['per'] = 'MRT';
        $dimension_indi_map['MRQ']['priceeps'] = 'MRT';

        $result = array();
        foreach($indicator_charttype as $indicator => $chart_type ) {
            $dim = isset($dimension_indi_map[$dimension][$indicator]) ? $dimension_indi_map[$dimension][$indicator] : $dimension;
            $result[$indicator] = $this->getVChartFile($ticker_code, $dim, $chart_type, $indicator, $lifetime);
            if(sizeof($result) == 1 && $result[$indicator] == false) {
                return;
            }
            foreach($result[$indicator]['data'] as $axis => &$date_num_list) {
                foreach($date_num_list as $date => &$num) {
                    $num = round(floatval($num), 4);
                }
            }

        }
        return $result;
    }

    // VChart 파일 생성
    public function getVChartFile($ticker_code, $dimension, $vchart_type, $indicator) {
        $dimension = strtoupper(trim($dimension));
        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) return;

        $ticker_code = strtoupper(trim($ticker_code));

        return $this->_dataGetFileContents('getVChart/'.$ticker_code.'/'.$dimension.'/'.$vchart_type.'/'.$indicator);
    }

    private function _dataGetFileContents($method) {

        if(strstr($method, 'getVChart') && strstr($method, 'MRY')) {
            $cache_file = $this->cache_path_mry.'/'.str_replace(array('/',','),'__',$method).'.info';
        }
        else if(strstr($method, 'getVChart') && strstr($method, 'MRT')) {
            $cache_file = $this->cache_path_mrt.'/'.str_replace(array('/',','),'__',$method).'.info';
        }
        else if(strstr($method, 'getVChart') && strstr($method, 'MRQ')) {
            $cache_file = $this->cache_path_mrq.'/'.str_replace(array('/',','),'__',$method).'.info';
        }
        else if(strstr($method, 'getBaseData')) {
            $cache_file = $this->cache_path_base.'/'.str_replace(array('/',','),'__',$method).'.info';
			if(is_file($cache_file)) {
				if( time() - filemtime($cache_file) > 3600*24*7 ) {
					$cache_file = '';
				}
			}
        }
        else {
            $cache_file = $this->cache_path.'/'.str_replace(array('/',','),'__',$method).'.info';
        }

        if( is_file($cache_file) ) {
            $content = file_get_contents($cache_file);
            if(strpos($content, 'a:') !== 0) {

				touch($cache_file);
				$data = $this->common->restful_curl(API_URL.'/api/'.$method);
				file_put_contents($cache_file, $data);
				$data = @unserialize($data);
				return $data;

                //echo 'Response Error !'."\n";
                //echo $content;
            } else {
                return unserialize(file_get_contents($cache_file));
            }
        }
        else {
            touch($cache_file);
            $data = $this->common->restful_curl(API_URL.'/api/'.$method);
            file_put_contents($cache_file, $data);
            $data = @unserialize($data);
            return $data;
        }
    }

	public function vichart($ticker='', $dimension='MRY') {

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}

        $ticker = strtoupper($ticker);

        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
            $dimension = 'MRT';
        }

		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		delete_cookie('dh_ticker');
		//검색종목 쿠키생성
        set_cookie('dh_ticker', $ticker, time()+86400, '.thewm.co.kr' );

		$extra = array(
			'with_summary' => true
		);

		$data = $this->_getBaseData($ticker, $dimension, $cell_type, $extra);

		if($data === false) {
			delete_cookie('dh_ticker');
			//검색종목 쿠키생성
			set_cookie('dh_ticker', 'TSLA', time()+86400, '.thewm.co.kr' );
			$this->common->locationhref('/wm_main');
			exit;
		}

        $data['vchart_data'] = $this->getCharmFinanceVChartFile($ticker, $dimension);
		//echo '<pre>'; print_r($data['vchart_data']); exit;
        $data['dimension'] = $dimension;

		$data['ticker_currency'] = $this->ticker_currency_more[$data['ticker']['tkr_currency']][0];
		$data['ticker_unit'] = $this->ticker_currency_more[$data['ticker']['tkr_currency']][1];

		$this->wm_view('stocks/wm_vichart', $data);
	}

	public function chart($ticker) {
		$data = array();
		$dimension = $this->input->get('dimension', 'MRT');
		$cell_type = $this->input->get('cell_type', 'data');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}

		$data = $this->_getBaseData($ticker, $dimension, $cell_type);
		$data['data'] = $this->historylib->convertRows($data['data']);
		//$data['like'] = $this->_like($ticker);	
		//$data['opinion'] = $this->_opinion($ticker);
		$this->_view('stocks/chart', $data);
	}
	public function news($ticker) {
		$data = array();
		$dimension = $this->input->get('dimension', 'MRY');
		$cell_type = $this->input->get('cell_type', 'data');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRY';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}

		$data = $this->_getBaseData($ticker, $dimension, $cell_type);
		$data['data'] = $this->historylib->convertRows($data['data']);
		//$data['like'] = $this->_like($ticker);	
		//$data['opinion'] = $this->_opinion($ticker);
		$this->_view('stocks/news', $data);
	}
	public function talk($ticker) {
		$data = array();
		$dimension = $this->input->get('dimension', 'MRT');
		$cell_type = $this->input->get('cell_type', 'data');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'TSLA';
		}

		$data = $this->_getBaseData($ticker, $dimension, $cell_type);
		$data['data'] = $this->historylib->convertRows($data['data']);
		//$data['like'] = $this->_like($ticker);	
		//$data['opinion'] = $this->_opinion($ticker);
		$this->_view('stocks/talk', $data);
	}

	public function code_like($ticker) {
		
		$result = array();
		$this->output->set_content_type('application/json');

		if( !$ticker ) {
			$result = array('error' => '선택한 종목이 없습니다.');
			exit(json_encode($result));
		}

		if( $this->session->userdata('is_login') === true ) {


			$user_id = $this->session->userdata('user_id');
			$res = $this->myitem_tb_model->set_like_info($user_id, $ticker);
			$this->make_myticker();
			$success = TRUE;
			$result = array('success' => $success, 'res' => $res);
			exit(json_encode($result));
		}
		else {
			$result = array('error' => '로그인 후 이용해주세요.');
			exit(json_encode($result));
		}
	}

	function _like($ticker) {

		if( $this->session->userdata('is_login') === true ) {

			$user_id = $this->session->userdata('user_id');
			$res = $this->myitem_tb_model->get_like_info($user_id, $ticker);

			if($res) {
				return $res->my_like;
			}
		}
	}

	function _opinion($ticker) {

		if( $this->session->userdata('is_login') === true ) {

			$user_id = $this->session->userdata('user_id');
			$res = $this->myitem_tb_model->get_opinion_info($user_id, $ticker);

			$sell_cnt = $res['sell_opinion'];
			$buy_cnt = $res['buy_opinion'];
			
			if($sell_cnt>0)
				$res['sell_ratio'] = round($sell_cnt/($buy_cnt+$sell_cnt)*100);
			else 
				$res['sell_ratio'] = 0;

			if($buy_cnt>0)
				$res['buy_ratio'] = round($buy_cnt/($buy_cnt+$sell_cnt)*100);
			else 
				$res['buy_ratio'] = 0;

			if($res) {
				return $res;
			}
		}
	}

	function code_opinion($ticker, $opt) {

		$result = array();
		$this->output->set_content_type('application/json');

		if( !$ticker ) {
			$result = array('error' => '선택한 종목이 없습니다.');
			exit(json_encode($result));
		}

		if( $this->session->userdata('is_login') === true ) {

			$user_id = $this->session->userdata('user_id');
			$res = $this->myitem_tb_model->set_opinion_info($user_id, $ticker, $opt);

			$sell_cnt = $res['sell_opinion'];
			$buy_cnt = $res['buy_opinion'];
			
			if($sell_cnt > 0)
				$res['sell_ratio'] = round($sell_cnt/($buy_cnt+$sell_cnt)*100);
			else 
				$res['sell_ratio'] = 0;

			if($buy_cnt > 0)
				$res['buy_ratio'] = round($buy_cnt/($buy_cnt+$sell_cnt)*100);
			else
				$res['buy_ratio'] = 0;

			$success = TRUE;
			$result = array('success' => $success, 'res' => $res);

			$this->_get_opinion_list();
			exit(json_encode($result));
		}
		else {
			$result = array('error' => '로그인 후 이용해주세요.');
			exit(json_encode($result));
		}
	}

	private function _get_opinion_list() {
		$res = $this->myitem_tb_model->get_opinion_list();

		if($res) {
			$arr_optlist = array();
			foreach($res as $nKey => $nVal) {
				$arr_optlist[$nKey]['mo_ticker'] = $nVal['mo_ticker'];
				$arr_optlist[$nKey]['mo_opinion'] = $nVal['mo_opinion'];
				$arr_optlist[$nKey]['mo_count'] = $this->myitem_tb_model->get_opinion_count($nVal['mo_ticker'], $nVal['mo_opinion']);
			}

			$opinion_file_path = WEBDATA.'/opinion_list.inc';
			file_put_contents($opinion_file_path, json_encode($arr_optlist));
			return $arr_optlist;
		}	
	}

	private function makePriceMap() {
        $this->ticker_priceinfo_map = $this->sep_tb_model->getTickerPriceMap();
		echo '<pre>';
		print_r($this->ticker_priceinfo_map);
	}
}
