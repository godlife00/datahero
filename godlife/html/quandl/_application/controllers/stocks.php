<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'/base_pc.php';
class Stocks extends BasePC_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model(array(
                    'business/historylib',
                    DBNAME.'/company_tb_model',
                    DBNAME.'/myitem_tb_model'
                    ));
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
            $this->common->locationhref('/');
            exit;
            return;
        }

        $result = $this->historylib->getData();

        $ticker = $result['ticker'];

        // 계약범위 초과 방지를 위한 유효성 체크
        $redirect = false;
        if(
            $ticker['tkr_isdelisted'] == 'Y' ||
            $ticker['tkr_exchange'] == 'OTC'
        ) {
            $this->common->locationhref('/');
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

        $this->current_ticker_info = array(
            'name' => $ticker['tkr_name'],
            'korname' => isset($company_info['cp_korname']) ? $company_info['cp_korname'] : '',
            'ticker' => $ticker['tkr_ticker'],
            'exchange' => $ticker['tkr_exchange'],
            'diff_rate' => $last_sep['sep_diff_rate'],
            'diff_price' => $last_sep['sep_diff_price'],
            'close_price' => $last_sep['sep_close'],
        );

        return $data;
    }

    public function summary($ticker='') {

        $dimension = $this->input->get('dimension', 'MRT');
        $cell_type = $this->input->get('cell_type', 'data');

        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
            $dimension = 'MRT';
        }
        if( ! in_array($cell_type, array('data', 'ratio'))) {
            $cell_type = 'data';
        }

        if(strlen($ticker) <= 0) {
            $ticker = 'aapl';
        }

        $extra = array(
            'with_summary' => true
        );

        $data = $this->_getBaseData($ticker, $dimension, $cell_type, $extra);

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

        $data['like'] = $this->_like($ticker);
        
        $data['opinion'] = $this->_opinion($ticker);

        $this->_view('stocks/summary', $data);
    }
    public function invest($ticker) {
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
            $ticker = 'aapl';
        }

        $data = $this->_getBaseData($ticker, $dimension, $cell_type);
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

        $data['like'] = $this->_like($ticker);    
        $data['opinion'] = $this->_opinion($ticker);

        if( ! $is_ajax) {
            $this->_view('stocks/invest', $data);
        } else {
            $content = $this->_view('stocks/invest', $data, true);
            list($head, $result, $tail) = explode('<!-- TABLE SCROLL DIV -->', $content, 3);
            echo $result;
        }
    }

    public function invest_api($ticker, $dimension) {
        //$is_ajax = ($this->input->get('ajax') == 'Y');

        $country_unitnum_map = $this->sf1_tb_model->getCountryUnitnumMap();
        $country_unitnum = $this->input->get('country_unitnum', 'us_백만');
        //$tab_idx = $this->input->get('tab_idx');
        //$dimension = $this->input->get('dimension', 'MRT');
        //$cell_type = $this->input->get('cell_type', 'data');

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
            $ticker = 'aapl';
        }

        $data = $this->_getBaseData($ticker, $dimension, $cell_type);

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

		return $data;
/*
        if( ! $is_ajax) {
            $this->_view('stocks/invest', $data);
        } else {
            $content = $this->_view('stocks/invest', $data, true);
            list($head, $result, $tail) = explode('<!-- TABLE SCROLL DIV -->', $content, 3);
            echo $result;
        }
*/
    }

    public function financials($ticker='aapl') {
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
/*
        if(strlen($ticker) <= 0) {
            $ticker = 'aapl';
        }NFLX
*/

        $extra = array(
            'func_name' => 'financials'
        );

        $data = $this->_getBaseData($ticker, $dimension, $cell_type, $extra);
        $data['country_unitnum'] = $country_unitnum;
        $data['country_unitnum_map'] = $country_unitnum_map;
        $data['tab_idx'] = intval($tab_idx);

        $data['like'] = $this->_like($ticker);    
        $data['opinion'] = $this->_opinion($ticker);
        
        list($country, $unitnum) = explode('_', $country_unitnum, 2);
        $extra = array(
            'convert_type' => $country, 
            'unitnum' => $unitnum
        );
        $data['data'] = $this->historylib->convertRows($data['data'], $extra);

        if( ! $is_ajax) {
            $this->_view('stocks/financials', $data);
        } else {
            $content = $this->_view('stocks/financials', $data, true);
            list($head, $result, $tail) = explode('<!-- TABLE SCROLL DIV -->', $content, 3);
            echo $result;
        }
    }
    public function competitors($ticker) {
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
            $ticker = 'aapl';
        }

        $data = $this->_getBaseData($ticker, $dimension, $cell_type);

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
//		echo '<pre>'; print_r($competitor);
        $industry_top = $this->sf1_tb_model->getIndustryTop($pTicker, $pSf1Row, $pExtra);
        $industry_top = $this->historylib->convertRows($industry_top, $extra);
        $data['industry_top'] = $industry_top;

        $competitor_fields_and_titles = $this->sf1_tb_model->getCompetitorFields();
        $data['competitor_fields'] = $competitor_fields_and_titles['fields'];
        $data['competitor_titles'] = $competitor_fields_and_titles['titles'];

        $data['industry_top_fields'] = $competitor_fields_and_titles['fields'];
        $data['industry_top_titles'] = $competitor_fields_and_titles['titles'];

        $data['country_unitnum_map'] = $country_unitnum_map;

        $data['like'] = $this->_like($ticker);    
        $data['opinion'] = $this->_opinion($ticker);

        $this->_view('stocks/competitors', $data);
    }

    public function competitors_ws($ticker) {
        //$country_unitnum_map = $this->sf1_tb_model->getCountryUnitnumMap();
        //$country_unitnum = $this->input->get('country_unitnum', 'us_백만');
        //$dimension = $this->input->get('dimension', 'MRT');
        //$cell_type = $this->input->get('cell_type', 'data');
        //$tab_idx = $this->input->get('tab_idx');

        //if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
        $dimension = 'MRT';
        //}
        //if( ! in_array($cell_type, array('data', 'ratio'))) {
        $cell_type = 'data';
        //}
        //if( ! $country_unitnum || ! array_key_exists($country_unitnum, $country_unitnum_map)) {
        $country_unitnum = 'us_백만';
        //}

        if(strlen($ticker) <= 0) {
            $ticker = 'aapl';
        }

		$extra = array();
        $extra['bicchart'] = true;

        $data = $this->_getBaseData($ticker, $dimension, $cell_type, $extra);

        //$data['country_unitnum'] = $country_unitnum;
        //$data['country_unitnum_map'] = $country_unitnum_map;
        //$data['tab_idx'] = intval($tab_idx);

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
        
        $competitor = $this->sf1_tb_model->getCompetitor_nc($pTicker, $pSf1Row, $pExtra);
        $competitor = $this->historylib->convertRows($competitor, $extra);
        $data['competitor'] = $competitor;
//echo '<pre>'; print_r($data['competitor']);
        //$industry_top = $this->sf1_tb_model->getIndustryTop($pTicker, $pSf1Row, $pExtra);
        //$industry_top = $this->historylib->convertRows($industry_top, $extra);
        //$data['industry_top'] = $industry_top;

        //$competitor_fields_and_titles = $this->sf1_tb_model->getCompetitorFields();
        //$data['competitor_fields'] = $competitor_fields_and_titles['fields'];
        //$data['competitor_titles'] = $competitor_fields_and_titles['titles'];

        //$data['industry_top_fields'] = $competitor_fields_and_titles['fields'];
        //$data['industry_top_titles'] = $competitor_fields_and_titles['titles'];

        //$data['country_unitnum_map'] = $country_unitnum_map;

        //$data['like'] = $this->_like($ticker);    
        //$data['opinion'] = $this->_opinion($ticker);

        $this->_view('stocks/competitors', $data);
    }


	function gf_quicksort(&$postings, $lo, $hi, $index)
	{
		if($lo >= $hi)
			return;

		$mid = ($lo + $hi) / 2;

		if( $postings[$lo][$index] > $postings[$mid][$index] )
		{
			$tmp = $postings[$lo];
			$postings[$lo] = $postings[$mid];
			$postings[$mid] = $tmp;
		}

		if($postings[$mid][$index] > $postings[$hi][$index] )
		{
			$tmp = $postings[$mid];
			$postings[$mid] = $postings[$hi];
			$postings[$hi] = $tmp;

			if( $postings[$lo][$index] > $postings[$mid][$index] )
			{
				$tmp = $postings[$lo];
				$postings[$lo] = $postings[$mid];
				$postings[$mid] = $tmp;
			}
		}

		$left = $lo + 1;
		$right = $hi - 1;

		if ($left >= $right)
			return; 

		$partition = $postings[$mid]; //not kept, so no need to finalize

		for( ;; ) {
			while( $postings[$right][$index] > $partition[$index] )
			--$right;
			while($left < $right && $postings[$left][$index] <= $partition[$index] )
				++$left;
			if($left < $right)
			{
				$tmp = $postings[$left];
				$postings[$left] = $postings[$right];
				$postings[$right] = $tmp;
				--$right;
			}
			else
				break;
		}

		$this->gf_quicksort($postings, $lo, $left, $index);
		$this->gf_quicksort($postings, $left + 1, $hi, $index);
	}

	function _get_rank($arr_rank, $ticker) {

		$rank = array();
		for($j=0;$j<6;$j++){
			$count = 100;
			$grade = 1;

			rsort($arr_rank[$j], SORT_NUMERIC);

			for($num=0;$num<sizeof($arr_rank[$j]);$num++){
				$arrSort = explode('|',$arr_rank[$j][$num]);
				if($arrSort[0] == $count){
					$nowgrade = $grade;
				}else{
					$nowgrade = $num+1;
					$grade = $nowgrade;
					$count = $arrSort[0];
				}

				if($arrSort[1] == $ticker){
					$rank[$j] = $nowgrade;
					break;
				}
			}
		}

		return $rank;
	}

	function mri($ticker='', $dimension = 'MRT') {

		$data = array();

		$cell_type = $this->input->get('cell_type', 'data');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}

		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'AAPL';
		}

		$data = $this->_getBaseData($ticker, $dimension, $cell_type);

		$data['data'] = $this->historylib->convertRows($data['data']);
		$data['like'] = $this->_like($ticker);	
		$data['opinion'] = $this->_opinion($ticker);
		$data['dimension'] = $dimension;


		require_once WEBDATA.'/mri_data.php';

		for($i=0;$i<$ticker_cnt;$i++) {
			$arr_rank[0][$i] = $ticker_mri_info[$i]['total_score'].'|'.$ticker_mri_info[$i]['ticker'];
			$arr_rank[1][$i] = $ticker_mri_info[$i]['growth_score'].'|'.$ticker_mri_info[$i]['ticker'];
			$arr_rank[2][$i] = $ticker_mri_info[$i]['safety_score'].'|'.$ticker_mri_info[$i]['ticker'];
			$arr_rank[3][$i] = $ticker_mri_info[$i]['cashflow_score'].'|'.$ticker_mri_info[$i]['ticker'];
			$arr_rank[4][$i] = $ticker_mri_info[$i]['moat_score'].'|'.$ticker_mri_info[$i]['ticker'];
			$arr_rank[5][$i] = $ticker_mri_info[$i]['valuation_score'].'|'.$ticker_mri_info[$i]['ticker'];
		}

		$data['ticker_ranking'] = $this->_get_rank($arr_rank, $ticker);

		$ticker_mri = array();
		for($i=0;$i<$ticker_cnt;$i++) {
			if($ticker == $ticker_mri_info[$i]['ticker']) {
				$ticker_mri = $ticker_mri_info[$i];
			}
		}

		$data['mri_info'] = $ticker_mri;
		$data['mri_all_cnt'] = $ticker_cnt;


		//경쟁사 5개 가져오기
		$country_unitnum_map = $this->sf1_tb_model->getCountryUnitnumMap();
		$country_unitnum = $this->input->get('country_unitnum', 'us_백만');

		if( ! $country_unitnum || ! array_key_exists($country_unitnum, $country_unitnum_map)) {
			$country_unitnum = 'us_백만';
		}

		$data['country_unitnum'] = $country_unitnum;
		$data['country_unitnum_map'] = $country_unitnum_map;
		//$data['tab_idx'] = intval($tab_idx);

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

		$cnt=0;
		$comp_list = array();
		foreach($competitor as $cKey => $cVal) {
			if($cnt > 4) break;
			if($cVal['tkr_ticker'] != $ticker) {
				$comp_list[$cKey] = $cVal;
				$cnt++;
			}
		}

		$comp_mri = array();
		foreach($comp_list as $cKey => $cVal) {
			for($i=0;$i<$ticker_cnt;$i++) {
				if($cVal['tkr_ticker'] == $ticker_mri_info[$i]['ticker']) {
					$comp_mri[] = $ticker_mri_info[$i];
				}
			}
		}

		$data['competitor'] = $comp_mri;

		$data['vchart'] = true;

		$this->_view('stocks/mri', $data);
	}

	function vchart($ticker, $dimension='MRT') {
		$data = array();

		//$dimension = $this->input->get('dimension', 'MRT');

		$sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');

		$cell_type = $this->input->get('cell_type', 'data');

		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}

		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if(strlen($ticker) <= 0) {
			$ticker = 'aapl';
		}
		$data = $this->_getBaseData($ticker, $dimension, $cell_type);

		$data['data'] = $this->historylib->convertRows($data['data']);
		$data['like'] = $this->_like($ticker);	
		$data['opinion'] = $this->_opinion($ticker);
		$data['dimension'] = $dimension;

		if(!$sDate) 
			$data['sDate'] = date("Y.m/d",strtotime("-10 year"));
		else 
			$data['sDate'] = $sDate;

		if(!$eDate) 
			$data['eDate'] = date('Y.m/d');
		else 
			$data['eDate'] = $eDate;

		$data['vchart'] = true;


		$extra = array(
			'sDate' => $data['sDate'], 
			'eDate' => $data['eDate'] 
		);

		//==================================================================================================

		//수익성(매출액과 이익)
		$data['vc_profit_salesincome'] = $this->loadVchart( $ticker, $dimension, 'profit', 'salesincome', $extra);

		//수익성(이익률)
		$data['vc_profit_margin'] = $this->loadVchart( $ticker, $dimension, 'profit', 'margin', $extra);

		//수익성(원가율)
		$data['vc_profit_cor'] = $this->loadVchart( $ticker, $dimension, 'profit', 'cor', $extra);

		//수익성(연구개발비)
		$data['vc_profit_rnd'] = $this->loadVchart( $ticker, $dimension, 'profit', 'rnd', $extra);

		//==================================================================================================

		//안전성(부채비율&유동비율)
		$data['vc_safety_debtcr'] = $this->loadVchart( $ticker, $dimension, 'safety', 'debtcr', $extra);

		//안전성(차입금&차입금비율)
		$data['vc_safety_borrow'] = $this->loadVchart( $ticker, $dimension, 'safety', 'borrow', $extra);

		//안전성(영업이익&이자비용)
		$data['vc_safety_opintexp'] = $this->loadVchart( $ticker, $dimension, 'safety', 'opintexp', $extra);

		//안전성(이자보상배수)
		$data['vc_safety_intexpcoverage'] = $this->loadVchart( $ticker, $dimension, 'safety', 'intexpcoverage', $extra);
		
		//안전성(차입금&금융비용)
		$data['vc_safety_debtcost'] = $this->loadVchart( $ticker, $dimension, 'safety', 'debtcost', $extra);
		//==================================================================================================
		//자산구조(자산구조)
		$data['vc_structure_assetstructure'] = $this->loadVchart( $ticker, $dimension, 'structure', 'assetstructure', $extra);

		//자산구조(이익 축적)
		$data['vc_structure_profitaccum'] = $this->loadVchart( $ticker, $dimension, 'structure', 'profitaccum', $extra);

		//자산구조(주당배당금&배당률)
		$data['vc_structure_dividend'] = $this->loadVchart( $ticker, $dimension, 'structure', 'dividend', $extra);

		//자산구조(배당성향)
		$data['vc_structure_payout'] = $this->loadVchart( $ticker, $dimension, 'structure', 'payout', $extra);

		//==================================================================================================

		//효율성(ROE & PBR)
		$data['vc_efficiency_roepbr'] = $this->loadVchart( $ticker, $dimension, 'efficiency', 'roepbr', $extra);
	
		//효율성(ROE 듀퐁분석)
		$data['vc_efficiency_dupont'] = $this->loadVchart( $ticker, $dimension, 'efficiency', 'dupont', $extra);

		//효율성(ROA & ROE & ROIC)
		$data['vc_efficiency_roaroeroic'] = $this->loadVchart( $ticker, $dimension, 'efficiency', 'roaroeroic', $extra);

		//효율성(운전자본 회전일수)
		$data['vc_efficiency_turnoverdays'] = $this->loadVchart( $ticker, $dimension, 'efficiency', 'turnoverdays', $extra);

		//효율성(현금 회전일수)
		$data['vc_efficiency_ccc'] = $this->loadVchart( $ticker, $dimension, 'efficiency', 'ccc', $extra);

		//==================================================================================================

		//현금흐름(현금흐름표)
		$data['vc_cashflow_cashflow'] = $this->loadVchart( $ticker, $dimension, 'cashflow', 'cashflow', $extra);

		//현금흐름(잉여현금흐름)
		$data['vc_cashflow_freecashflow'] = $this->loadVchart( $ticker, $dimension, 'cashflow', 'freecashflow', $extra);
		
		//현금흐름(잉여현금흐름 비율)
		$data['vc_cashflow_fcfonrevenue'] = $this->loadVchart( $ticker, $dimension, 'cashflow', 'fcfonrevenue', $extra);
	
		//==================================================================================================

		//밸류에이션(주가수익배수(PER))
		$data['vc_valuation_per'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'per', $extra);

		//밸류에이션(주가 & 주당순이익)
		$data['vc_valuation_priceeps'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'priceeps', $extra);
	
		//밸류에이션(주가순자산배수(PBR))
		$data['vc_valuation_pbr'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'pbr', $extra);
		
		//밸류에이션(주가 & 주당순자산)
		$data['vc_valuation_pricebps'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'pricebps', $extra);
		
		//밸류에이션(주가현금흐름배수(PCR))
		$data['vc_valuation_pcr'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'pcr', $extra);
		
		//밸류에이션(주가 & 주당현금흐름)
		$data['vc_valuation_pricecps'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'pricecps', $extra);
		
		//밸류에이션(주가매출액배수(PSR))
		$data['vc_valuation_psr'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'psr', $extra);
		
		//밸류에이션(주가 & 주당매출액)
		$data['vc_valuation_pricesps'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'pricesps', $extra);

		//밸류에이션(EV/EBITDA)
		$data['vc_valuation_evebitda'] = $this->loadVchart( $ticker, $dimension, 'valuation', 'evebitda', $extra);

		//==================================================================================================

		//print_r($data['vc_profit_salesincome']);
		$this->_view('stocks/vchart', $data);
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
            $ticker = 'aapl';
        }

        $data = $this->_getBaseData($ticker, $dimension, $cell_type);
        $data['data'] = $this->historylib->convertRows($data['data']);
        $data['like'] = $this->_like($ticker);    
        $data['opinion'] = $this->_opinion($ticker);
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
            $ticker = 'aapl';
        }

        $data = $this->_getBaseData($ticker, $dimension, $cell_type);
        $data['data'] = $this->historylib->convertRows($data['data']);
        $data['like'] = $this->_like($ticker);    
        $data['opinion'] = $this->_opinion($ticker);
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
            $ticker = 'aapl';
        }

        $data = $this->_getBaseData($ticker, $dimension, $cell_type);
        $data['data'] = $this->historylib->convertRows($data['data']);
        $data['like'] = $this->_like($ticker);    
        $data['opinion'] = $this->_opinion($ticker);
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

    function makePriceMap() {
        $this->ticker_priceinfo_map = $this->sep_tb_model->getTickerPriceMap2();
        echo '<pre>';
        print_r($this->ticker_priceinfo_map);
    }
}
