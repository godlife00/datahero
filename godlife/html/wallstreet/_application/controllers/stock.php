<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base_mobile.php';
class Stock extends BaseMobile_Controller {

    function __construct() {
        parent::__construct();
        $this->loginCheck();
    }

    public function index() {
        $this->common->locationhref('/stock/recommend');
    }

    // 종목추천 메인
    public function recommend() {
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/recommend_tb_model',
        ));

        $data = array();

        // 목표가 달성한 최신 추천 종목 3건
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['=']['rc_endtype'] = 'SUCCESS';
        $params['!=']['rc_view_srv'] = 'C';
        $params['=']['sf1_dimension'] = 'MRT';
        $params['join']['daily_tb'] = 'rc_ticker = dly_ticker';
        $params['join']['sf1_tb'] = 'rc_ticker = sf1_ticker';
        $extra = array(
            'fields' => array('recommend_tb.*', 'dly_pe', 'dly_pb', 'sf1_roe'),
            'order_by' => 'rc_enddate DESC',
            'limit' => '3',
        );
        $top_recom = $this->recommend_tb_model->getList($params, $extra)->getData();

        foreach($top_recom as $idx => $val) {
            $chart_value = array();
            $chart = $this->itoozaapi->getSEPListForChart(array($val['rc_ticker']));
            if(isset($chart[$val['rc_ticker']]) && is_array($chart[$val['rc_ticker']])) {
                $chart_value = array_slice($chart[$val['rc_ticker']], -20, 20);
            }
            $top_recom[$idx]['chart_value'] = implode(',', array_values($chart_value));
            $top_recom[$idx]['chart_key'] = array_keys($chart_value);
            $top_recom[$idx]['ticker'] = $this->ticker_info_map[$val['rc_ticker']];
        }
        $data['top_recom'] = $top_recom;


        // 종목추천 리스트
        $rc_list = $this->_get_recommend_data($page=1);
        $data['content_html'] = $this->load->view('/mobile/stock/recommend_list.php', array('rc_list' => $rc_list), true);

        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '종목 추천';
        $this->_view('/stock/recommend', $data);
    }


    // 종목추천 리스트 [더보기]
    public function ajax_get_recommend_list() {
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $request = $this->input->post();
        if( ! ( isset($request['page']) && is_numeric($request['page']) && strlen($request['page']) > 0)) {
            return;
        }

        $rc_list = $this->_get_recommend_data($request['page']);
        $content_html = $this->load->view('/mobile/stock/recommend_list.php', array('rc_list' => $rc_list), true);
        echo $content_html;
        return;
    }


    // (공통) 종목추천 리스트 가져오기.
    private function _get_recommend_data($page=1, $limit=15) {
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/recommend_tb_model',
        ));

        // 최근 1년간 등록된 종목추천 중 최신순으로 15건씩 리스팅. (손절가도달은 리스팅 제외)
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['!=']['rc_view_srv'] = 'C';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['in']['rc_endtype'] = array('ING', 'SUCCESS');
        $params['>=']['rc_created_at'] = date('Y-m-d H:i:s', time()-(86400*365)); // 1년 전
        $extra = array(
            'order_by' => 'rc_display_date DESC',
            'limit' => $limit,
            'offset' => $limit * ($page-1),
        );

        $rc_list = $this->recommend_tb_model->getList($params, $extra)->getData();

        foreach($rc_list as $idx => $val) {
            $chart_value = array();
            $chart = $this->itoozaapi->getSEPListForChart(array($val['rc_ticker']));
            if(isset($chart[$val['rc_ticker']]) && is_array($chart[$val['rc_ticker']])) {
                $chart_value = array_slice($chart[$val['rc_ticker']], -20, 20);
            }
            $rc_list[$idx]['chart_value'] = implode(',', array_values($chart_value));
            $rc_list[$idx]['chart_key'] = array_keys($chart_value);
            $rc_list[$idx]['ticker'] = $this->ticker_info_map[$val['rc_ticker']];
        }
        return $rc_list;
    }


    // 종목추천 상세
    public function recommend_view() {
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/recommend_tb_model',
            DBNAME.'/sf1_tb_model',
            DBNAME.'/daily_tb_model',
        ));

        $data = array();

        $rc_id = $this->uri->segment(3, FALSE);
        if( ! (is_numeric($rc_id) && $rc_id > 0)) {
            $this->common->historyback();
            return;
        }

        $params = array();
        $params['=']['rc_id'] = $rc_id;
        $params['=']['rc_is_active'] = 'YES';
        $params['!=']['rc_view_srv'] = 'C';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['join']['mri_tb'] = 'rc_ticker = m_ticker';
        $extra = array(
            'fields' => array('recommend_tb.*', 'm_biz_total_score', 'm_biz_dividend_score', 'm_biz_growth_score', 'm_biz_moat_score', 'm_biz_safety_score', 'm_biz_cashflow_score', 'm_biz_dividend_stars', 'm_biz_moat_stars', 'm_biz_growth_stars', 'm_biz_safety_stars', 'm_biz_cashflow_stars')
        );
        $rc_data = $this->recommend_tb_model->getList($params, $extra)->getData();

        if(sizeof($rc_data) != 1) {
            $this->common->historyback();
            return;
        }

        $rc_data = array_pop($rc_data);
		//$rc_data['rc_ticker'] = 'GOGL';
        $rc_data['ticker'] = $this->ticker_info_map[$rc_data['rc_ticker']];

        $data['rc_data'] = $rc_data;

        $chart_value = array();
        $chart = $this->itoozaapi->getSEPListForChart(array($rc_data['rc_ticker']));
        if(isset($chart[$rc_data['rc_ticker']]) && is_array($chart[$rc_data['rc_ticker']])) {
            $chart_value = array_slice($chart[$rc_data['rc_ticker']], (-20*6), (20*6)); // 6개월 
        }
        //$chart_value = implode(',', $chart_value);
        //$data['chart_value'] = $chart_value;
        $data['chart_value'] = implode(',', array_values($chart_value));
        $data['chart_key'] = array_keys($chart_value);


        // 투자지표
        $basedata = $this->itoozaapi->getBaseData($rc_data['rc_ticker'], 'MRY', 'data');
        //echo '<pre>';
        //print_r($basedata);
        //echo '</pre>';exit;

        $data['mrt_data'] = $basedata['last_mrt'];
        $data['mrq_data'] = $basedata['last_mrq'];
        $data['mry_data'] = $basedata['last_mry'];
        $data['sep_data'] = @array_shift($basedata['sepdata']);
        $data['mrt_list'] = $basedata['last_mrt_list'];
        $data['mry_list'] = $basedata['last_mry_list'];

        $params = array();
        $params['=']['dly_ticker'] = $rc_data['rc_ticker'];
        $extra = array();
        $extra['limit'] = 1;
        $daily_data = $this->daily_tb_model->getList($params, $extra)->getData();
        $data['daily_data'] = array_pop($daily_data);

        /*
        $params = array();
        $params['=']['sf1_ticker'] = $rc_data['rc_ticker'];
        $params['=']['sf1_dimension'] = 'MRT';
        $mrt_data = $this->sf1_tb_model->getList($params)->getData();
        $data['mrt_data'] = array_pop($mrt_data);

        $params = array();
        $params['=']['sf1_ticker'] = $rc_data['rc_ticker'];
        $params['=']['sf1_dimension'] = 'MRQ';
        $mrq_data = $this->sf1_tb_model->getList($params)->getData();
        $data['mrq_data'] = array_pop($mrq_data);
        */

        // 조회수 증가
        $update_params = array(
            'rc_view_count' => 'rc_view_count+1'
        );
        $this->recommend_tb_model->doUpdate($rc_id, $update_params, array('rc_view_count'));
//echo '<pre>';
//print_r($data['mrt_list']);
        $this->header_data['header_template'] = '3';
        $this->header_data['header_type'] = 'sch_heaher'; // 흰색 배경색에 종목명 노출, 검색영역 없는 타입 헤더
        $this->header_data['head_title'] = '종목추천';
        $this->header_data['back_url'] = '/stock/recommend';
        $this->_view('/stock/recommend_view', $data);
    }


    // 종목분석 메인
    public function analysis() {
        $this->load->model(array(
            DBNAME.'/analysis_tb_model',
        ));

        $data = array();

        // 투자매력점수 80점 이상인 최신등록 3건
        $params = array();
        $params['=']['an_is_active'] = 'YES';
        $params['!=']['an_view_srv'] = 'C';
        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
        $params['>=']['m_biz_total_score'] = '80';
        $params['join']['mri_tb'] = 'm_ticker = an_ticker';
        $extra = array(
            'fields' => array('analysis_tb.*', 'm_biz_total_score', 'm_v_fairvalue3', 'm_close'),
            'order_by' => 'an_created_at DESC',
            'limit' => '3',
        );

        $top_analy = $this->analysis_tb_model->getList($params, $extra)->getData();

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
        foreach($top_analy as $idx => $val) {
            $chart_value = array();

            $chart = $this->itoozaapi->getSEPListForChart(array($val['an_ticker']));
            if(isset($chart[$val['an_ticker']]) && is_array($chart[$val['an_ticker']])) {
                $chart_value = array_slice($chart[$val['an_ticker']], -20, 20);
            }
            $top_analy[$idx]['chart_value'] = implode(',', array_values($chart_value));
            $top_analy[$idx]['chart_key'] = array_keys($chart_value);

			$top_analy[$idx]['ticker'] = $this->ticker_info_map[$val['an_ticker']];
			
			$top_analy[$idx]['expected_star'] = $this->cal_valuation($val['m_v_fairvalue3'], $val['m_close']);
			$top_analy[$idx]['fairvalue'] = $val['m_v_fairvalue3'];
			$top_analy[$idx]['an_opinion'] = $an_items[$val['an_ticker']];
        }

		//$top_analy
		$data['top_analy'] = $top_analy;
        
		// 종목분석 리스트
        // 초기에 리스팅할 카운트 설정.
        $an_list = $this->_get_analysis_data($page=1);

        $list_data = array();
        $list_data['an_list'] = $an_list;
        $list_data['star_investopinion_map'] = $this->mri_tb_model->getStarInvestOpinionMap();
            
        $data['content_html'] = $this->load->view('/mobile/stock/analysis_list', $list_data, true);
        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '종목 분석';
        $this->_view('/stock/analysis', $data);
    }

    // 종목분석 리스트 [더보기]
    public function ajax_get_analysis_list() {
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $request = $this->input->post();
        if( ! ( isset($request['page']) && is_numeric($request['page']) && strlen($request['page']) > 0)) {
            return;
        }

        $page = $request['page'];
        $an_list = $this->_get_analysis_data($page);
        $list_data = array();
        $list_data['an_list'] = $an_list;
        $list_data['star_investopinion_map'] = $this->mri_tb_model->getStarInvestOpinionMap();
            
        echo $this->load->view('/mobile/stock/analysis_list', $list_data, true);


        return;
    }


    // (공통) 종목분석 리스트 가져오기.
    private function _get_analysis_data($page=1, $limit=30) {
        $this->load->model(array(
            DBNAME.'/analysis_tb_model',
        ));

        $params = array();
        $params['=']['an_is_active'] = 'YES';
        $params['!=']['an_view_srv'] = 'C';
        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
        $params['join']['mri_tb'] = 'an_ticker = m_ticker';
        $extra = array(
            'fields' => array('analysis_tb.*', 'm_biz_total_score', 'm_v_fairvalue3', 'm_close'),
            'order_by' => 'an_created_at DESC',
            'limit' => $limit,
            'offset' => $limit * ($page-1),
        );

        $an_list = $this->analysis_tb_model->getList($params, $extra)->getData();

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

        foreach($an_list as $idx => $val) {
            $chart_value = array();
            $chart = $this->itoozaapi->getSEPListForChart(array($val['an_ticker']));
            if(isset($chart[$val['an_ticker']]) && is_array($chart[$val['an_ticker']])) {
                $chart_value = array_slice($chart[$val['an_ticker']], (-20*6), (20*6)); // 6개월
            }
            $an_list[$idx]['chart_value'] = implode(',', array_values($chart_value));
            $an_list[$idx]['chart_key'] = array_keys($chart_value);
            $an_list[$idx]['ticker'] = $this->ticker_info_map[$val['an_ticker']];

			$an_list[$idx]['expected_star'] = $this->cal_valuation($val['m_v_fairvalue3'], $val['m_close']);
			$an_list[$idx]['fairvalue'] = $val['m_v_fairvalue3'];
			$an_list[$idx]['an_opinion'] = $an_items[$val['an_ticker']];
        }

        return $an_list;
    }

    // 종목분석 상세
    public function analysis_view() {
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/analysis_tb_model',
            DBNAME.'/daily_tb_model',
            DBNAME.'/sf1_tb_model',
        ));

        $data = array();

        $an_id = $this->uri->segment(3, FALSE);
        if( ! (is_numeric($an_id) && $an_id > 0)) {
            $this->common->historyback();
            return;
        }

        $params = array();
        $params['=']['an_id'] = $an_id;
        $params['=']['an_is_active'] = 'YES';
        $params['!=']['an_view_srv'] = 'C';
        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
        $params['join']['mri_tb'] = 'an_ticker = m_ticker';
        $extra = array(
            'fields' => array('analysis_tb.*', 'm_biz_total_score', 'm_v_fairvalue3', 'm_close'),
        );
        $an_data = $this->analysis_tb_model->getList($params, $extra)->getData();

        if(sizeof($an_data) != 1) {
            $this->common->historyback();
            return;
        }

        $an_data = array_pop($an_data);
		$an_data['ticker'] = $this->ticker_info_map[$an_data['an_ticker']];


		$expected_star = $this->cal_valuation($an_data['m_v_fairvalue3'], $an_data['m_close']);
		$an_data['expected_star']  = $expected_star;
		$an_data['fairvalue'] = $an_data['m_v_fairvalue3'];

        // 전체 종목 수
        $mri_count = $this->mri_tb_model->getCount()->getData();

		// 종합점수 상위 퍼센트
        $params = array();
        $params['>']['m_biz_total_score'] = $an_data['m_biz_total_score'];
		$mri_high_count = $this->mri_tb_model->getCount($params)->getData();

		$top_rate = 0;
        $top_rate = round(($mri_high_count+1) / $mri_count * 100, 2);
		if($top_rate>=1) $top_rate = floor($top_rate);
		$an_data['total_rank_rate'] = $top_rate; 

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
		$an_data['an_opinion'] = $an_items[$an_data['an_ticker']];
        $data['an_data'] = $an_data;

        $chart = array();
        $chart_value = array();
        $chart = $this->itoozaapi->getSEPListForChart(array($an_data['an_ticker']));
        if(isset($chart[$an_data['an_ticker']]) && is_array($chart[$an_data['an_ticker']])) {
            $chart_value = array_slice($chart[$an_data['an_ticker']], (-20*6), (20*6)); // 6개월 
        }
        //$chart_value = implode(',', $chart_value);
        //$data['chart_value'] = $chart_value;
        $data['close_chart_value'] = implode(',', array_values($chart_value));
        $data['close_chart_key'] = array_keys($chart_value);

        // 투자지표
        $basedata = $this->itoozaapi->getBaseData($an_data['an_ticker'], 'MRY', 'data');
        //echo '<pre>';
        //print_r($basedata);
        //echo '</pre>';exit;

        $data['mrt_data'] = $basedata['last_mrt'];
        $data['mrq_data'] = $basedata['last_mrq'];
        $data['orig_mrq_data'] = array_shift($this->sf1_tb_model->getList(array('sf1_ticker' => $an_data['an_ticker'], 'sf1_dimension' => 'MRQ'), array('limit' => 1))->getData());
        $data['mry_data'] = $basedata['last_mry'];
        $data['mrt_list'] = $basedata['last_mrt_list'];
        $data['mry_list'] = $basedata['last_mry_list'];
        $data['sep_data'] = array_shift($basedata['sepdata']);

        $params = array();
        $params['=']['dly_ticker'] = $an_data['an_ticker'];
        $extra = array();
        $extra['limit'] = 1;
        $daily_data = $this->daily_tb_model->getList($params, $extra)->getData();
        $data['daily_data'] = array_pop($daily_data);
		$data['ticker_currency'] = $this->ticker_currency[$basedata['ticker']['tkr_currency']][0];

        // 조회수 증가
        $update_params = array(
            'an_view_count' => 'an_view_count+1'
        );
        $this->analysis_tb_model->doUpdate($an_id, $update_params, array('an_view_count'));

		$this->header_data['header_template'] = '3';
        $this->header_data['head_title'] = '종목분석';
        $this->header_data['header_type'] = 'sch_heaher'; // 흰색 배경색에 종목명 노출, 검색영역 없는 타입 헤더
        $this->header_data['back_url'] = '/stock/analysis';
        $this->_view('/stock/analysis_view', $data);
    }


    // 투자 레시피
    public function recipe() {
        $this->load->model(DBNAME.'/mri_tb_model');

        $data = array();
        $list = $this->_get_recipe_data('dividend');
        $data['dividend_content_html'] = $this->load->view('/mobile/stock/recipe_list.php', array('type' => 'dividend', 'list' => $list), true);

        $list = $this->_get_recipe_data('growth');
        $data['growth_content_html'] = $this->load->view('/mobile/stock/recipe_list.php', array('type' => 'growth', 'list' => $list), true);

        $list = $this->_get_recipe_data('moat');
        $data['moat_content_html'] = $this->load->view('/mobile/stock/recipe_list.php', array('type' => 'moat', 'list' => $list), true);

        $list = $this->_get_recipe_data('total_score');
        $data['total_score_content_html'] = $this->load->view('/mobile/stock/recipe_list.php', array('type' => 'total_score', 'list' => $list), true);

        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '투자 레시피';
        $this->_view('/stock/recipe', $data);
    }

    public function ajax_get_recipe_list() {
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $type = $this->input->get('type');

        switch($type) {
            case 'dividend': // 배당매력주
                $list = $this->_get_recipe_data('dividend');
                break;
            case 'growth': // 이익성장주
                $list = $this->_get_recipe_data('growth');
                break;
            case 'moat': // 소비자독점
                $list = $this->_get_recipe_data('moat');
                break;
            case 'total_score': // 슈퍼스톡
                $list = $this->_get_recipe_data('total_score');
                break;
        }

        $content_html = $this->load->view('/mobile/stock/recipe_list.php', array('type' => $type, 'list' => $list), true);
        echo $content_html;
        return;
    }

    private function _get_recipe_data($type) {
        $this->load->model(DBNAME.'/mri_tb_model');

        $request = $this->input->get();

        $limit = 30; 
        $page = (isset($request['page']) && strlen($request['page']) > 0) ? $request['page'] : '1';

        $add_params = array();
        $add_params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
		if($type=='growth'||$type=='moat') {
	        $add_params['>=']['dly_marketcap'] = '10000';
		}
		$add_extra = array(
            'offset' => $limit * ($page-1),
        );
        $list = $this->mri_tb_model->getRecomStockList($type, $limit, $add_params, $add_extra);
        return $list;
    }


    public function research($order_by='created_at') {
        $this->load->model(DBNAME.'/explore_tb_model');

        $data = array();

        // 6개월간 조회수 높은 콘텐츠 TOP 5중 3건 랜덤노출
        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['!=']['e_view_srv'] = 'C';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $params['>=']['e_display_date'] = date('Y-m-d H:i:s', strtotime('-6 month'));
        $extra = array(
            'order_by' => 'e_view_count DESC',
            'limit' => '5',
        );
        $top_research = $this->explore_tb_model->getList($params, $extra)->getData();
        shuffle($top_research);
        $top_research = array_slice($top_research, 0, 3);
        $data['top_research'] = $top_research;


        if( ! in_array($order_by, array('created_at', 'view_count'))) {
            $order_by = 'created_at';
        }
        $data['order_by'] = $order_by;
 
        // 리스팅
        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['!=']['e_view_srv'] = 'C';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $extra = array(
            'order_by' => 'e_'.$order_by.' DESC',
            'limit' => '15',
        );
        $explore = $this->explore_tb_model->getList($params, $extra)->getData();
        $data['explore'] = $explore;

        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '미국주식 탐구생활';
        $this->_view('/stock/research', $data);
    }

    // 종목분석 리스트 [더보기]
    public function ajax_get_research_list() {
        $this->load->model(DBNAME.'/explore_tb_model');
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $request = $this->input->post();
        if( ! ( isset($request['page']) && is_numeric($request['page']) && strlen($request['page']) > 0)) {
            return;
        }

        $order_by = $request['order_by'];
        if( ! in_array($order_by, array('created_at', 'view_count'))) {
            $order_by = 'created_at';
        }

        $page = $request['page'];

        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['!=']['e_view_srv'] = 'C';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $extra = array(
            'order_by' => 'e_'.$order_by.' DESC',
            'offset' => ($page-1) * 15,
            'limit' => 15,
        );
        $explore = $this->explore_tb_model->getList($params, $extra)->getData();
        $list_data = array();
        $list_data['explore'] = $explore;
        echo $this->load->view('/mobile/stock/research_list', $list_data, true);
    }



    public function research_view($no=0) {
        $this->load->model(DBNAME.'/explore_tb_model');

        $data = array();

        if( ! $this->explore_tb_model->get($no)->isSuccess()) {
            $this->common->historyback();
            return;
        }

        $row = $this->explore_tb_model->getData();

        if(
            $row['e_is_active'] != 'YES' || $row['e_view_srv'] == 'C'
            || $row['e_display_date'] > date('Y-m-d H:i:s')
        ) {
            $this->common->historyback();
            return;
        }

        $data['row'] = $row;


        // 탐구생활 
        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['!=']['e_view_srv'] = 'C';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['e_id'] = $no;

        $extra = array(
            'order_by' => 'e_view_count DESC',
            'limit' => 3,
        );

        $explore = $this->explore_tb_model->getList($params, $extra)->getData();
        $data['explore'] = $explore;

        // 조회수 증가
        $update_params = array(
            'e_view_count' => 'e_view_count+1'
        );
        $this->explore_tb_model->doUpdate($no, $update_params, array('e_view_count'));


        $this->header_data['header_template'] = '9';
        $this->header_data['head_title'] = '미국주식 탐구생활';
        $this->header_data['back_url'] = '/stock/research';
        $this->_view('/stock/research_view', $data);
    }


    // 대가의 종목 리스트
    public function master() {
        $this->load->model(DBNAME.'/master_tb_model');

        $data = array();

        $params = array();
        $extra = array(
            'order_by' => 'ms_updated_at DESC'
        );
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
        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '대가의 종목';
        $this->_view('/stock/master', $data);
    }


    // 대가의 종목 상세
    public function master_view() {
        $this->load->model(DBNAME.'/master_tb_model');
        $data = array();

        $id = $this->uri->segment(3);
        if( ! (is_numeric($id) && strlen($id) > 0)) {
            $this->common->historyback();
            return;
        }

        if( ! $this->master_tb_model->get($id)->isSuccess()) {
            $this->common->historyback();
            return;
        }

        $master = $this->master_tb_model->getData();

        $rp_ticker = array();
        if(strlen($master['ms_representative_ticker']) > 0) {
            foreach(explode(',', $master['ms_representative_ticker']) as $rt) {
                $rp_ticker[$rt] = array('ticker' => $rt, 'name' => $this->ticker_info_map[$rt]['tkr_name']);
            }
        }
        $master['rp_ticker'] = $rp_ticker;
        $data['master'] = $master;

        // 대가종목
        $master_items = array(
            'items' => array(),
            'updated_at' => ''
        );

        $path = MASTER_DATA.'/master_'.$id.'.info';
        if(file_exists($path)) {
            $items = array();

            $contents = explode("\n", file_get_contents($path));
            $title = array();
            foreach($contents as $idx => $row) {
                $row = explode("\t", $row);
                if($idx == 0) {
                    $title = $row;
                    continue;
                }
                $items[] = array_combine($title, $row);
            }

            foreach($items as $key => $val) {
                if(isset($this->ticker_info_map[$val['ticker']])) {
                    $items[$key]['ticker_name'] = $this->ticker_info_map[$val['ticker']]['tkr_name'];
                    $items[$key]['action'] = $this->master_tb_model->getActionName($val['action']);

                    if($val['avgprice'] > 0) { // 수익률
						$val['avgprice'] = str_replace(',','',$val['avgprice']);
                        $items[$key]['returnrate'] = number_format((($this->ticker_info_map[$val['ticker']]['tkr_close'] / $val['avgprice']) - 1)*100,2);
                    } else {
                        $items[$key]['returnrate'] = 0;
                    }
                } else {
                    // ticker 에 없으면 노출하지 않는다. 
                    unset($items[$key]);
                }
            }

            $master_items['items'] = $items;
            $master_items['updated_at'] = filemtime($path);
        }
        $data['master_items'] = $master_items;
        $this->header_data['header_template'] = '3';
        $this->header_data['head_title'] = '대가의 종목';
        $this->header_data['header_type'] = 'sch_heaher'; // 흰색 배경색에 종목명 노출, 검색영역 없는 타입 헤더
        $this->_view('/stock/master_view', $data);
    }

	public function show_winner() {
	
        $this->load->model(DBNAME.'/sp500_tb_model');
        $this->load->model(DBNAME.'/recommend_tb_model');
        $this->load->model(DBNAME.'/winner_tb_model');
        $this->load->model(DBNAME.'/mri_tb_model');

		$snp_params = array();
        //$snp_params['=']['sp5_date'] = '2020-02-17';
        $snp_params['=']['sp5_action'] = 'current';
        $snp_params['raw'] = array('sp5_date = (select max(sp5_date) from sp500_tb)');
        $snp_params['join']['ticker_tb'] = 'sp5_ticker = tkr_ticker and tkr_table = "SF1" and tkr_isdelisted = "N" ';

        $snp_extra = array(
            'order_by' => 'sp5_date desc',
            'fields' => 'sp5_ticker',
            'quandldb' => true,
            'cache_sec' => 3600*12
        );

        $ticekr_snp500 = array_keys($this->common->getDataByPK($this->sp500_tb_model->getList($snp_params, $snp_extra)->getData(), 'sp5_ticker'));


		$params['=']['rc_is_active'] = 'YES';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['rc_view_srv'] = 'W';
        $params['=']['rc_portfolio'] = 'Y';
        $params['in']['rc_endtype'] = array('ING', 'SUCCESS');

        $extra = array(
            'fields' => 'rc_ticker',
			'slavedb' => true,
        );

        $rc_list = $this->common->getDataByPK($this->recommend_tb_model->getList($params, $extra)->getData(), 'rc_ticker');
		$rc_list = array_keys($rc_list);
		
		$params = array();
        $params['>']['win_display_date'] = '20201130';
        $extra = array();
        $extra['fields'] = array('distinct(win_display_date)');
        $extra['limit'] = '14';
        $extra['order_by'] = 'win_display_date desc';

        $day_list = $this->winner_tb_model->getList($params, $extra)->getData();

		$winner = array();
		$winner_list = array();

		foreach($day_list as $day) {

            $params = array();
            $params['join']['ticker_tb'] = 'win_ticker = tkr_ticker';
            $params['=']['win_display_date'] = $day['win_display_date'];
            $params['=']['win_is_win'] = 'Y';
            $params['>']['win_biz_score'] = '65';
			$params['not in']['win_ticker'] = $rc_list;

            $extra = array();
            $extra['fields'] = array('winner_tb.*', 'tkr_name');
            $extra['order_by'] = 'win_biz_score desc';

            $winner = $this->winner_tb_model->getList($params, $extra)->getData();

			foreach ($winner as $win_key => $win_val) {
				$sort[$win_key] = round(($win_val['win_rc_price']/$win_val['win_close']-1)*100, 3);
			}
			@array_multisort($sort, SORT_DESC, $winner);

            $winner_list[$day['win_display_date']] = $winner;
        }

		foreach($winner_list as $key=>$val) {
			$cnt=0;
			foreach($val as $win) {
				if($cnt>4) break;

				$snp500 = 'N';
				if(in_array($win['win_ticker'], $ticekr_snp500)) {
					$snp500 = 'Y';
				}

				$mri_data = array();

				$mri_params = array();
				$mri_params['=']['m_ticker'] = $win['win_ticker'];
				
				$mri_extra = array(
					'fields' => array('m_biz_growth_score', 'm_biz_safety_score', 'm_biz_cashflow_score', 'm_biz_moat_score', 'm_biz_dividend_score', 'm_biz_total_score'),
					'order_by' => ''
				);
				//$extra['cache_sec'] = 3600;
				$mri_extra['slavedb'] = true;
				
				$mri_data = array_shift($this->mri_tb_model->getList($mri_params, $mri_extra)->getData());
				//echo '<pre>'; print_r($mri_data);
				echo $key.'@'.$win['win_ticker'].'@'.$win['tkr_name'].'@'.$snp500.'@'.$mri_data['m_biz_growth_score'].'@'.$mri_data['m_biz_safety_score'].'@'.$mri_data['m_biz_cashflow_score'].'@'.$mri_data['m_biz_moat_score'].'@'.$mri_data['m_biz_dividend_score'].'@'.$mri_data['m_biz_total_score'].'<br>';
				$cnt++;
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
