<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base_mobile.php';
class Main extends BaseMobile_Controller {

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
	public function index()
	{
        $data = array();
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/recommend_tb_model',
            DBNAME.'/analysis_tb_model',
            DBNAME.'/explore_tb_model',
            DBNAME.'/mri_tb_model',
            DBNAME.'/sf1_tb_model',
            DBNAME.'/ticker_tb_model',
            DBNAME.'/master_tb_model',
        ));

		//애널리스트 의견
        $path = MASTER_DATA.'/an_opinion.inc';
        if(file_exists($path)) {
            $an_items = array();

            $contents = explode("\n", file_get_contents($path));
            $title = array();
            foreach($contents as $idx => $row) {
                $row = explode("\t", $row);
				if($idx>0){
					$an_items[$row[0]] = $row[1];
				}
            }
		}

        // 배당매력주
        $add_params = array();
        $add_extra = array();
        $add_params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
		$add_extra['cache_sec'] = 3600;
		$add_extra['slavedb'] = true;

        $data['top5_dividend'] = $this->mri_tb_model->getRecomStockList('dividend', 5, $add_params, $add_extra);

        // 최신검색 - 사용자 검색 최신 5건 노출
        // 최신검색 없으면 인기주 TOP 50 랜덤 5건 노출
        $tab_text = '';
        $tab_stock_data = array();
        if(isset($_COOKIE['search_history']) && strlen($_COOKIE['search_history']) > 0) {
            $tab_text = '최신검색';
            $search_ticker = array_filter(array_unique(array_map('trim', explode(',', $_COOKIE['search_history']))));
            $search_ticker = array_slice($search_ticker, 0, 5);
        } else {
            $tab_text = '인기검색';
            shuffle($this->popular_search_ticker);
            $search_ticker = array_slice($this->popular_search_ticker, 0 ,5);
        }

        if(is_array($search_ticker) && sizeof($search_ticker) > 0) {
            $params = array();
            $params['in']['m_ticker'] = $search_ticker;
            $extra = array(
                'fields' => array('m_ticker', 'm_biz_total_score'),
                'order_by' => ''
            );
			$extra['cache_sec'] = 3600;
			$extra['slavedb'] = true;
            $tab_stock_data = $this->mri_tb_model->getList($params, $extra)->getData();

            foreach($tab_stock_data as $key => $val) {
				$tab_stock_data[$key]['ticker'] = $this->ticker_info_map[$val['m_ticker']];
				$tab_stock_data[$key]['an_opinion'] = $an_items[$val['m_ticker']];
            }
            $tab_stock_data = $this->common->indexSort($search_ticker, $tab_stock_data, 'm_ticker');
        }
        $data['tab_text'] = $tab_text;
        $data['tab_stock_data'] = $tab_stock_data;


		// 최근 실적발표
		$recent_file = 'recent_report.inc';
		$file_path = QUANDL_WEBDATA.'/'.$recent_file;
		
		if( is_file($file_path) ) {
            $recent_report = unserialize(file_get_contents($file_path));
			$data['recent_report'] = $recent_report['recent_report'];
			$data['recent_report_rates'] = $recent_report['recent_report_rates'];
			$data['recent_report_rates_pm'] = $recent_report['recent_report_rates_pm'];
		}
		else {
			$params = array();
			$params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker';
			$params['=']['sf1_dimension'] = 'MRQ'; 
			$params['=']['tkr_is_active'] = 'YES'; 
			$extra = array(
				'fields' => array('tkr_ticker', 'tkr_name', 'tkr_rate', 'tkr_rate_str', 'tkr_close', 'sf1_netinccmnusd'),
				'order_by' => 'sf1_lastupdated desc, sf1_netinccmnusd desc',
				'limit' => 5
			);
			$extra['cache_sec'] = 3600;
			$extra['slavedb'] = true;
			//sf1_lastupdated
			$recent_report = $this->sf1_tb_model->getList($params, $extra)->getData();
			$data['recent_report'] = $recent_report;

			// 최근 실적발표 전년동기 대비 실적
			$recent_report_tickers = array_keys($this->common->getDataByPK($recent_report, 'tkr_ticker'));
			$recent_report_rates = $this->itoozaapi->getIncomeGrowthRate($recent_report_tickers);
			//echo '<pre>'; print_r($recent_report_rates);
			//$recent_report_rates = $recent_report_rates['rate'];
			//$recent_report_rates = $recent_report_rates['rate_pm'];
			$data['recent_report_rates'] = $recent_report_rates['rate'];
			$data['recent_report_rates_pm'] = $recent_report_rates['rate_pm'];
		}

        // 종목추천(최신작성 1건)
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['!=']['rc_endtype'] = 'FAIL';
        $params['!=']['rc_view_srv'] = 'C';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $extra = array(
            'order_by' => 'rc_created_at DESC',
            'limit' => '2',
        );
		$extra['cache_sec'] = 3600;
		$extra['slavedb'] = true;
        $recommend = $this->recommend_tb_model->getList($params, $extra)->getData();

        foreach($recommend as $key => $val) {
            $recommend[$key]['ticker'] = $this->ticker_info_map[$val['rc_ticker']];

            $chart_value = array();
            $chart = $this->itoozaapi->getSEPListForChart(array($val['rc_ticker']));
            if(isset($chart[$val['rc_ticker']]) && is_array($chart[$val['rc_ticker']])) {
                $chart_value = array_slice($chart[$val['rc_ticker']], -20, 20); // 1달 
            }
            $recommend[$key]['chart_value'] = implode(',', $chart_value);
        }

        $data['recommend'] = $recommend;

        // 종목분석 (최신작성 5건)
        $params = array();
        $params['=']['an_is_active'] = 'YES';
        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['an_view_srv'] = 'C';
        $params['join']['mri_tb'] = 'an_ticker = m_ticker';
        $extra = array(
            'fields' => array('analysis_tb.*', 'm_biz_total_score', 'm_valuation_score', 'm_valuation_stars', 'm_v_fairvalue3', 'm_close'),
            'order_by' => 'an_created_at DESC',
            'limit' => '5',
        );
		$extra['cache_sec'] = 3600;
		$extra['slavedb'] = true;
        $analysis = $this->analysis_tb_model->getList($params, $extra)->getData();
        foreach($analysis as $key => $val) {
            $analysis[$key]['ticker'] = $this->ticker_info_map[$val['an_ticker']];

			$analysis[$key]['expected_star'] = $this->cal_valuation($val['m_v_fairvalue3'], $val['m_close']);
			$analysis[$key]['an_opinion'] = $an_items[$val['an_ticker']];
        }
        $data['analysis'] = $analysis;
        
        // 탐구생활 (최신작성 3건)
        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['!=']['e_view_srv'] = 'C';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $extra = array(
            'order_by' => 'e_created_at DESC',
            'limit' => '3',
        );
		$extra['cache_sec'] = 3600;
		$extra['slavedb'] = true;
        $explore = $this->explore_tb_model->getList($params, $extra)->getData();
        $data['explore'] = $explore;


        // 대가의 종목
        $params = array();
        $extra = array(
            'order_by' => 'ms_updated_at DESC'
        );
		$extra['cache_sec'] = 3600;
		$extra['slavedb'] = true;
        $master = $this->master_tb_model->getList($params, $extra)->getData();
        foreach($master as $key => $val) {
            $rp_ticker = array();
            if(strlen($val['ms_representative_ticker']) > 0) {
                foreach(explode(',', $val['ms_representative_ticker']) as $rt) {
                    $rp_ticker[$rt] = array('ticker' => $rt, 'name' => $this->ticker_info_map[$rt]['tkr_name']);
                }
            }
            $master[$key]['rp_ticker'] = $rp_ticker;
        }
        $data['master'] = $master;


        // 전종목 투자매력도
        $add_params = array();
        $add_extra = array();
        $add_params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
        $add_params['>=']['dly_marketcap'] = '10000';
		$add_extra['cache_sec'] = 3600;
		$add_extra['slavedb'] = true;
		$all_total_score = $this->mri_tb_model->getRecomStockList('total_score', 10, $add_params, $add_extra);

        foreach($all_total_score as $key => $val) {
            $all_total_score[$key]['chart_value'] = $val['m_biz_growth_stars'].','.$val['m_biz_safety_stars'].','.$val['m_biz_cashflow_stars'].','.$val['m_biz_moat_stars'].','.$val['m_biz_dividend_stars'];
        }
        $data['all_total_score'] = $all_total_score;

        $data['growth'] = $this->mri_tb_model->getRecomStockList('growth', 5, $add_params, $add_extra);
        $data['moat'] = $this->mri_tb_model->getRecomStockList('moat', 5, $add_params, $add_extra);
 
        // 투자레시피
        $add_params = array();
        $add_params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
		$data['total_score'] = $this->mri_tb_model->getRecomStockList('total_score', 5, $add_params, $add_extra);

		$data['first_tab'] = rand(0,2);
        $this->header_data['header_template'] = '1';
        $this->header_data['show_alarm'] = TRUE;
        $this->_view('main/index', $data);
	}

    public function ajax_save_search_history() {
        $this->load->model(DBNAME.'/search_log_tb_model');
        $ticker = $this->input->get('ticker');
        if(isset($this->ticker_info_map[$ticker])) {
            $params = array(
                'sl_ticker' => $ticker,
            );
            $this->search_log_tb_model->doInsert($params);
        }

        if(isset($_COOKIE['search_history']) && strlen($_COOKIE['search_history']) > 0) {
            $search_history = explode(',', $_COOKIE['search_history']);
            array_unshift($search_history, $ticker);
            $search_history = array_slice($search_history, 0, 10);
            setcookie('search_history', implode(',', $search_history), time()+(86400*365), '/', $this->config->item('cookie_domain'));
        } else {
            setcookie('search_history', $ticker, time()+(86400*365), '/', $this->config->item('cookie_domain'));
        }
    }
    public function search() {
        $data = array();

        // 최신검색 - 사용자 검색 최신 5건 노출
        // 최신검색 없으면 인기주 TOP 50 랜덤 5건 노출
        $tab_text = '';
        $tab_stock_data = array();
        if(isset($_COOKIE['search_history']) && strlen($_COOKIE['search_history']) > 0) {
            $tab_text = '최신검색';
            $search_ticker = array_filter(array_unique(array_map('trim', explode(',', $_COOKIE['search_history']))));
            $search_ticker = array_slice($search_ticker, 0, 5);
        } else {
            $tab_text = '인기검색';
            shuffle($this->popular_search_ticker);
            $search_ticker = array_slice($this->popular_search_ticker, 0 ,5);
        }

		//애널리스트 의견
        $path = MASTER_DATA.'/an_opinion.inc';
        if(file_exists($path)) {
            $an_items = array();

            $contents = explode("\n", file_get_contents($path));
            $title = array();
            foreach($contents as $idx => $row) {
                $row = explode("\t", $row);
				if($idx>0){
					$an_items[$row[0]] = $row[1];
				}
            }
		}

        if(is_array($search_ticker) && sizeof($search_ticker) > 0) {
            $params = array();
            $params['in']['m_ticker'] = $search_ticker;
            $extra = array(
                'fields' => array('m_ticker', 'm_biz_total_score'),
                'order_by' => ''
            );
			$extra['cache_sec'] = 3600;
			$extra['slavedb'] = true;
            $tab_stock_data = $this->mri_tb_model->getList($params, $extra)->getData();

            foreach($tab_stock_data as $key => $val) {
                $tab_stock_data[$key]['ticker'] = $this->ticker_info_map[$val['m_ticker']];
                $tab_stock_data[$key]['an_opinion'] = $an_items[$val['m_ticker']];
            }
            $tab_stock_data = $this->common->indexSort($search_ticker, $tab_stock_data, 'm_ticker');
        }
        $data['tab_text'] = $tab_text;
        $data['tab_stock_data'] = $tab_stock_data;

        $this->header_data['header_template'] = '5';
        $this->header_data['head_title'] = '검색';
        $this->_view('main/search', $data);
    }

    public function service() {
        $this->header_data['header_template'] = '2';
        $this->header_data['show_alarm'] = TRUE;
        $this->header_data['head_title'] = '서비스소개';
        $this->_view('main/service');
    }

    public function service_guide() {
        $this->header_data['header_template'] = '2';
        $this->header_data['show_alarm'] = TRUE;
        $this->header_data['head_title'] = '서비스소개';
        $this->_view('main/service_guide');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
