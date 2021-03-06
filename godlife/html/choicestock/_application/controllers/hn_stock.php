<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/hn_base_mobile.php';
class Hn_stock extends Hn_BaseMobile_Controller {

    function __construct() {
        parent::__construct();
        //$this->paramCheck();
    }

    public function index() {
        //$this->common->locationhref('/hn_stock/choice_api');
        $this->common->locationhref('/'.HN.'_stock/recommend');
    }

    public function choice_api($type='') {

        $api_key = $this->input->get('api_key');

        if($type=='' || $api_key == '') {
            $result = array();
            $result['retmsg'] = 'argument error[type or key is null]';
            echo json_encode($result);
            //    $strJsonStr = json_encode($stockbot_array);
            exit;
        }

        if($api_key != API_KEY_X1) {
            $result = array();
            $result['retmsg'] = 'key match error';
            echo json_encode($result);
            exit;
        }

        //종목
        if($type=='A') {
            //echo '<pre>'; print_r($this->search_ticker_list);
            //exit;
            echo json_encode($this->search_ticker_list);
            exit;
            /*
            $result = array();
            $ticker_list = $this->ticker_info_map;
            $cnt=0;
            foreach($ticker_list as $key=>$val) {
                $result[$cnt]['ticker'] = $val['tkr_ticker'];
                $result[$cnt]['ticker_en'] = $val['tkr_name_en'];
                $result[$cnt]['ticker_kor'] = $val['tkr_name'];
                $cnt++;
            }
            //echo '<pre>'; print_r($result);
            echo serialize($result);
            exit;
            */
        }
        else if($type=='B') {
            $this->load->model(DBNAME.'/recommend_tb_model');
            $this->load->model(DBNAME.'/mri_tb_model');

            $result = array();
            $cnt=0;
            //최근 추천 종목

            // 종목추천(최신작성 1건)
            $params = array();
            $params['=']['rc_is_active'] = 'YES';
            $params['=']['rc_endtype'] = 'ING';
            $params['!=']['rc_view_srv'] = 'W';
            $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
            $extra = array(
                'order_by' => 'rc_display_date DESC',
                'limit' => '1',
                'slavedb' => true,
            );

            $recommend = $this->recommend_tb_model->getList($params, $extra)->getData();

            $result[0]['ticker'] = $recommend[0]['rc_ticker'];    //티커
            //$result[0]['ticker_kor'] = $this->ticker_info_map[$recommend[0]['rc_ticker']]['tkr_name']; //티커한글명

            if($recommend[0]['rc_subtitle'] =='') {
                $result[0]['ticker_title'] = $recommend[0]['rc_title'];    //서브제목
            }
            else {
                $result[0]['ticker_title'] = $recommend[0]['rc_subtitle'];    //서브제목
            }

            if(in_array($recommend[0]['rc_adjust'], array('U', 'D')) && $recommend[0]['rc_adjust_price'] > 0) :
                $recommend[0]['rc_goal_price'] = $recommend[0]['rc_adjust_price'];
            endif;

            $result[0]['ticker_revenue'] = round((($recommend[0]['rc_goal_price']/$recommend[0]['rc_recom_price'])-1)*100,2);    //예상수익률
            $result[0]['link'] = '/'.HN.'_stock/recommend';

            //최근 목표가 달성 종목
            $params = array();
            $params['=']['rc_is_active'] = 'YES';
            $params['=']['rc_endtype'] = 'SUCCESS';
            $params['!=']['rc_view_srv'] = 'W';
            $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
            $extra = array(
                'order_by' => 'rc_enddate DESC',
                'limit' => '1',
                'cache_sec' => 3600,
                'slavedb' => true,
            );
            $recommend = $this->recommend_tb_model->getList($params, $extra)->getData();

            $result[1]['ticker'] = $recommend[0]['rc_ticker'];    //티커
            $result[1]['ticker_kor'] = $this->ticker_info_map[$recommend[0]['rc_ticker']]['tkr_name']; //티커한글명
            $result[1]['ticker_title'] = '적중! 수익 실현 종목';    //서브제목

            if(in_array($recommend[0]['rc_adjust'], array('U', 'D')) && $recommend[0]['rc_adjust_price'] > 0) :
                $recommend[0]['rc_goal_price'] = $recommend[0]['rc_adjust_price'];
            endif;

            $result[1]['ticker_revenue'] = round((($recommend[0]['rc_goal_price']/$recommend[0]['rc_recom_price'])-1)*100,2);    //달성수익률
            $result[1]['link'] = '/'.HN.'_stock/recommend_view/'.$recommend[0]['rc_id'];

            //투자매력도 상위 급등주 노출
            $params = array();
            $params['join']['ticker_tb'] = 'tkr_ticker = m_ticker and tkr_is_active = "YES"';
            $params['join']['daily_tb'] = 'dly_ticker = m_ticker';
            $params['>=']['dly_marketcap'] = '10000';
            $params['>=']['m_biz_total_score'] = '80';
            $params['not in']['m_ticker'] = array($result[0]['ticker'], $result[1]['ticker']);

            $extra = array(
                'fields' =>  array('m_ticker', 'm_biz_total_score', 'tkr_close', 'tkr_rate'),
                'order_by' => 'tkr_rate DESC',
                'limit' => '1',
                'cache_sec' => 3600,
                'slavedb' => true,
            );
            $recommend = $this->mri_tb_model->getList($params, $extra)->getData();

            $result[2]['ticker'] = $recommend[0]['m_ticker'];    //티커
            $result[2]['ticker_kor'] = $this->ticker_info_map[$recommend[0]['m_ticker']]['tkr_name']; //티커한글명
            $result[2]['ticker_title'] = '투자매력 급등주';    //서브제목
            $result[2]['ticker_close'] = number_format($recommend[0]['tkr_close']);    //종가
            $result[2]['ticker_fluct'] = $recommend[0]['tkr_rate'];    //등락률
            $result[2]['ticker_score'] = $recommend[0]['m_biz_total_score'];    //투자매력점수
            $result[2]['link'] = '/'.HN.'_attractiveness/attractive?sort=total&netincome=all&marketcap=over100billion';

            //echo '<pre>'; print_r($result);
            echo json_encode($result);
            exit;
        }
        else if($type=='C') {
            $result = array();
            $ticker_list = $this->ticker_info_map;
            $cnt=0;
            foreach($ticker_list as $key=>$val) {
                $result[$cnt]['ticker'] = $val['tkr_ticker'];
                $result[$cnt]['ticker_en'] = $val['tkr_name_en'];
                $result[$cnt]['ticker_kor'] = $val['tkr_name'];
                $cnt++;
            }
            //echo '<pre>'; print_r($result);
            echo json_encode($result);
            exit;
        }
        else if($type=='D') {
            //1. 급등주/인기주 구분
            //2. 티커
            //3. 티커한글명
            //4. 티커 영문
            //5. 현재가
            //6. 등락률

            //급등주, 인기종목
            $popular_stock_data = array();

            //인기검색
            shuffle($this->popular_search_ticker);
            $popular_ticker = array_slice($this->popular_search_ticker, 0, 10);

            if(is_array($popular_ticker) && sizeof($popular_ticker) > 0) {
                
                $params = array();
                $params['in']['m_ticker'] = $popular_ticker;
                $extra = array(
                    'fields' => array('m_ticker', 'm_biz_total_score', 'm_v_fairvalue3'),
                    'order_by' => ''
                );
                $extra['cache_sec'] = 3600;
                $extra['slavedb'] = true;
                $popular_stock_data = $this->mri_tb_model->getList($params, $extra)->getData();
                
                foreach($popular_stock_data as $key => $val) {
                    $popular_stock_data[$key]['ticker'] = $this->ticker_info_map[$val['m_ticker']];
                    //$popular_stock_data[$key]['an_opinion'] = $an_items[$val['m_ticker']];
                    //$popular_stock_data[$key]['expected_star'] = $this->cal_valuation($val['m_v_fairvalue3'], $popular_stock_data[$key]['ticker']['tkr_close']);
                }
                $popular_stock_data = $this->common->indexSort($popular_ticker, $popular_stock_data, 'm_ticker');
            }

            if(is_array($popular_stock_data) && sizeof($popular_stock_data) > 0) {
                $cnt=0;
                foreach($popular_stock_data as $key => $val) {
                    $result[$cnt]['type'] = 'popular';
                    $result[$cnt]['ticker'] = $val['ticker']['tkr_ticker'];
                    $result[$cnt]['ticker_kor'] = $val['ticker']['tkr_name'];
                    $result[$cnt]['ticker_en'] = $val['ticker']['tkr_name_en'];
                    $result[$cnt]['ticker_close'] = $val['ticker']['tkr_close'];
                    $result[$cnt]['ticker_rate'] = $val['ticker']['tkr_rate_str'];
                    $cnt++;
                }
            }

            // 급등주 가져오기
            $params = array();
            $params['raw'] = array('tkr_lastpricedate = (select max(tkr_lastpricedate) from ticker_tb)');

            $extra = array(
                'fields' => array('tkr_ticker'),
                'order_by' => 'tkr_rate desc',
                'limit' => 2000
            );

            $hour = date("Hi");

            if($hour > 910 && $hour < 925) {
                $extra['cache_sec'] = 180;
            }
            else {
                $extra['cache_sec'] = 3600*12;
            }
            $extra['slavedb'] = true;
            $ticker_tb_data = array_keys($this->common->getDataByPK($this->ticker_tb_model->getList($params, $extra)->getData(), 'tkr_ticker'));

            $snp500_ticker = array();
            $snp500_ticker = $this->get_snp500();

            $cnt=0;
            $soaring_ticker = array();
            foreach($ticker_tb_data as $ticker) {
                if($cnt>9) break;
                if(in_array($ticker, $snp500_ticker)) {
                    $soaring_ticker[] = $ticker;
                    $cnt++;
                }
            }
        
            if(is_array($soaring_ticker) && sizeof($soaring_ticker) > 0) {
                $params = array();
                $params['in']['m_ticker'] = $soaring_ticker;
                $extra = array(
                    'fields' => array('m_ticker', 'm_biz_total_score', 'm_v_fairvalue3'),
                    'order_by' => ''
                );
                $extra['cache_sec'] = 3600;
                $extra['slavedb'] = true;
                $soaring_stock_data = $this->mri_tb_model->getList($params, $extra)->getData();

                foreach($soaring_stock_data as $key => $val) {
                    $soaring_stock_data[$key]['ticker'] = $this->ticker_info_map[$val['m_ticker']];
                    //$soaring_stock_data[$key]['an_opinion'] = $an_items[$val['m_ticker']];
                    //$soaring_stock_data[$key]['expected_star'] = $this->cal_valuation($val['m_v_fairvalue3'], $soaring_stock_data[$key]['ticker']['tkr_close']);
                }
                $soaring_stock_data = $this->common->indexSort($soaring_ticker, $soaring_stock_data, 'm_ticker');
            }

            if(is_array($soaring_stock_data) && sizeof($soaring_stock_data) > 0) {
                foreach($soaring_stock_data as $key => $val) {
                    $result[$cnt]['type'] = 'soaring';
                    $result[$cnt]['ticker'] = $val['ticker']['tkr_ticker'];
                    $result[$cnt]['ticker_kor'] = $val['ticker']['tkr_name'];
                    $result[$cnt]['ticker_en'] = $val['ticker']['tkr_name_en'];
                    $result[$cnt]['ticker_close'] = $val['ticker']['tkr_close'];
                    $result[$cnt]['ticker_rate'] = $val['ticker']['tkr_rate_str'];
                    $cnt++;
                }
            }
            //echo '<pre>'; print_r($result); exit;
            echo json_encode($result);
            exit;
        }
    }

    // 종목추천 메인
    public function recommend($tab='1') {
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/recommend_tb_model',
        ));

        //$tab = $this->input->get('pg');

        $data = array();
/*
        // 목표가 달성한 최신 추천 종목 3건
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['rc_view_srv'] = 'W';
        $params['=']['rc_endtype'] = 'SUCCESS';
        $params['=']['sf1_dimension'] = 'MRT';
        $params['join']['daily_tb'] = 'rc_ticker = dly_ticker';
        $params['join']['sf1_tb'] = 'rc_ticker = sf1_ticker';
        $extra = array(
            'fields' => array('recommend_tb.*', 'dly_pe', 'dly_pb', 'sf1_roe'),
            'order_by' => 'rc_enddate DESC',
            'slavedb' => true,
            'limit' => '6',
        );
        $top_recom_list = $this->recommend_tb_model->getList($params, $extra)->getData();
        $cnt=0;
        $top_recom = array();
        $ticker_list = array();
        foreach($top_recom_list as $val) {
            if($cnt>2) break;
            if(!in_array($val['rc_ticker'], $ticker_list)) {
                $ticker_list[] = $val['rc_ticker'];
                $top_recom[] = $val;
                $cnt++;
            }
        }
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
*/
        $data['ticker_submenu'] = ($tab =='1') ? 'recomm':'portfolio';

        // 종목추천 리스트
        $rc_list = $this->_get_recommend_data($page=1);
        /*
        $rc_portlist = $this->_get_portfolio_data();
        
        //echo '<pre>'; print_r($rc_portlist);
        $rc_profit_total = 0;
        foreach ($rc_portlist as $key => $val) {
            $sort[$key] = $val['rc_profit_rate'];
            $rc_profit_total += $val['rc_profit_rate'];
        }
        array_multisort($sort, SORT_DESC, $rc_portlist);
        */

        $data['tab'] = $tab;

        if($tab == '1') {
            $data['content_html'] = $this->load->view('/'.HN.'/stock/recommend_list.php', array('rc_list' => $rc_list, 'tab' => '1', 'is_event' => $this->is_event), true);
            //$data['content_pp_html'] = $this->load->view('/mobile/stock/recommend_list.php', array('rc_list' => $rc_portlist, 'tab' => '2', 'is_event' => $this->is_event), true);
        }
        else {

            $pf_profit_file = 'pf_profit.json';
            
            $file_path = WEBDATA.'/'.$pf_profit_file;
            //$file_path = str_replace('hoon','datahero',WEBDATA).'/'.$pf_profit_file;

            if( is_file($file_path) ) {
                $file_data = unserialize(file_get_contents($file_path));
                $data['pf_profit'] = $file_data['pf_profit'];
            }
            else {
                $data['pf_profit'] = '0.00';
            }

            $portfoli_file = 'portfolio.json';
            $file_path = WEBDATA.'/json/'.$portfoli_file;

            if( is_file($file_path) ) {
                 //$portfolio_list = json_decode(file_get_contents($file_path), true);
                 $portfolio = json_decode(file_get_contents($file_path), true);
                 $portfolio_list = $portfolio['portfolio'];
                 $exclude_list = $portfolio['exclude'];

            }
            $pf_count=0;
            $pf_ticker_list = array();
            foreach($portfolio_list as $idx => $val) {
                
                if($val['rc_display_date'] > date('Y-m-d H:i:s')) continue;

                $portfolio_list[$idx]['rc_close'] = $this->ticker_info_map[$val['rc_ticker']]['tkr_close'];
                $portfolio_list[$idx]['rc_rate'] = $this->ticker_info_map[$val['rc_ticker']]['tkr_rate'];
                $portfolio_list[$idx]['rc_rate_str'] = $this->ticker_info_map[$val['rc_ticker']]['tkr_rate_str'];
                
                if($val['rc_endtype'] == 'SELL') {
                    $portfolio_list[$idx]['rc_profit_rate'] = number_format((($portfolio_list[$idx]['rc_mid_price']/$val['rc_recom_price'])-1)*100, 2);
                }
                else if($val['rc_endtype'] == 'FAIL') {
                    $portfolio_list[$idx]['rc_profit_rate'] = number_format((($portfolio_list[$idx]['rc_giveup_price']/$val['rc_recom_price'])-1)*100, 2);
                }
                else if($val['rc_endtype'] == 'SUCCESS') {
                    if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :
                        $portfolio_list[$idx]['rc_profit_rate'] = number_format((($portfolio_list[$idx]['rc_adjust_price']/$val['rc_recom_price'])-1)*100, 2);
                    else :
                        $portfolio_list[$idx]['rc_profit_rate'] = number_format((($portfolio_list[$idx]['rc_goal_price']/$val['rc_recom_price'])-1)*100, 2);
                    endif;
                }
                else {
                    $portfolio_list[$idx]['rc_profit_rate'] = number_format((($portfolio_list[$idx]['rc_close']/$val['rc_recom_price'])-1)*100, 2);
                }

                $pf_ticker_list[] = $val['rc_ticker'];
                $pf_count++;
            }

            $data['pf_count'] = $pf_count;

            $rc_profit_total = 0;
            foreach ($portfolio_list as $key => $val) {
                $sort[$key] = $val['rc_profit_rate'];
                $rc_profit_total += $val['rc_profit_rate'];
            }
            array_multisort($sort, SORT_DESC, $portfolio_list);

            $data['portfolio_list'] = $portfolio_list;
            $data['pf_ticker_list'] = $pf_ticker_list;

            foreach($exclude_list as $idx => $val) {
                
                if($val['rc_display_date'] > date('Y-m-d H:i:s')) continue;

                $exclude_list[$idx]['rc_close'] = $this->ticker_info_map[$val['rc_ticker']]['tkr_close'];
                $exclude_list[$idx]['rc_rate'] = $this->ticker_info_map[$val['rc_ticker']]['tkr_rate'];
                $exclude_list[$idx]['rc_rate_str'] = $this->ticker_info_map[$val['rc_ticker']]['tkr_rate_str'];
                
                if($val['rc_endtype'] == 'SELL') {
                    $exclude_list[$idx]['rc_profit_rate'] = number_format((($exclude_list[$idx]['rc_mid_price']/$val['rc_recom_price'])-1)*100, 2);
                }
                else if($val['rc_endtype'] == 'FAIL') {
                    $exclude_list[$idx]['rc_profit_rate'] = number_format((($exclude_list[$idx]['rc_giveup_price']/$val['rc_recom_price'])-1)*100, 2);
                }
                else if($val['rc_endtype'] == 'SUCCESS') {
                    if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :
                        $exclude_list[$idx]['rc_profit_rate'] = number_format((($exclude_list[$idx]['rc_adjust_price']/$val['rc_recom_price'])-1)*100, 2);
                    else :
                        $exclude_list[$idx]['rc_profit_rate'] = number_format((($exclude_list[$idx]['rc_goal_price']/$val['rc_recom_price'])-1)*100, 2);
                    endif;
                }
                else {
                    $exclude_list[$idx]['rc_profit_rate'] = number_format((($exclude_list[$idx]['rc_close']/$val['rc_recom_price'])-1)*100, 2);
                }
            }

			//$rc_profit_total = 0;
			$sort = array();
			foreach ($exclude_list as $key => $val) {
				$sort[$key] = $val['rc_profit_rate'];
				//$rc_profit_total += $val['rc_profit_rate'];
			}
            array_multisort($sort, SORT_DESC, $exclude_list);

            $data['exclude_list'] = $exclude_list;
        }

        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '초이스스탁';
        $this->header_data['head_url'] = '/';

        $data['meta_title'] = ($tab=='1') ? '종목추천 - 초이스스탁US' : '포트폴리오 - 초이스스탁US';
        //15min
        $data['is_open'] = $this->is_open;
        $data['is_event'] = $this->is_event;
        $data['is_soft'] = $this->is_soft;

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
        $content_html = $this->load->view('/'.HN.'/stock/recommend_list.php', array('rc_list' => $rc_list, 'tab' => '1'), true);
        echo $content_html;
        return;
    }


    // (공통) 종목추천 리스트 가져오기.
    private function _get_recommend_data($page=1, $limit=30) {
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/recommend_tb_model',
        ));

        // 최근 5년간 등록된 종목추천 중 최신순으로 15건씩 리스팅. (손절가도달은 리스팅 제외)
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['rc_view_srv'] = 'W';
        //$params['in']['rc_endtype'] = array('ING', 'SUCCESS', 'SELL');
        $params['>=']['rc_created_at'] = date('Y-m-d H:i:s', time()-(86400*365*5)); // 5년 전
        $extra = array(
            'order_by' => 'rc_display_date DESC',
            'limit' => $limit,
            'slavedb' => true,
            'offset' => $limit * ($page-1),
        );

        $rc_list = $this->recommend_tb_model->getList($params, $extra)->getData();

        foreach($rc_list as $idx => $val) {
            $chart_value = array();
            //$chart = $this->itoozaapi->getSEPListForChart(array($val['rc_ticker']));
            if(isset($chart[$val['rc_ticker']]) && is_array($chart[$val['rc_ticker']])) {
                $chart_value = array_slice($chart[$val['rc_ticker']], -20, 20);
            }
            $rc_list[$idx]['chart_value'] = implode(',', array_values($chart_value));
            $rc_list[$idx]['chart_key'] = array_keys($chart_value);
            $rc_list[$idx]['ticker'] = $this->ticker_info_map[$val['rc_ticker']];

            if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :
                $val['rc_goal_price'] = $val['rc_adjust_price'];
            endif;

            if($val['rc_endtype'] == 'SUCCESS') {
                $rc_list[$idx]['rc_profit_rate'] = number_format((($val['rc_goal_price']/$val['rc_recom_price'])-1)*100, 2);
            }
            else if($val['rc_endtype'] == 'FAIL') {
                $rc_list[$idx]['rc_profit_rate'] = number_format((($val['rc_giveup_price']/$val['rc_recom_price'])-1)*100, 2);
            }
            else if($val['rc_endtype'] == 'SELL') {
                $rc_list[$idx]['rc_profit_rate'] = number_format((($val['rc_mid_price']/$val['rc_recom_price'])-1)*100, 2);
            }
            else {
                $rc_list[$idx]['rc_profit_rate'] = number_format((($rc_list[$idx]['ticker']['tkr_close']/$val['rc_recom_price'])-1)*100, 2);
            }
        }
        return $rc_list;
    }

    // (공통) 포트폴리오 리스트 가져오기.
    private function _get_portfolio_data() {
        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/recommend_tb_model',
        ));

        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['rc_view_srv'] = 'W';
        $params['=']['rc_portfolio'] = 'Y';
        $params['=']['rc_endtype'] = 'ING';
        //$params['in']['rc_endtype'] = array('ING', 'SUCCESS');
        //$params['>=']['rc_created_at'] = date('Y-m-d H:i:s', time()-(86400*365)); // 1년 전
        $extra = array(
            'order_by' => 'rc_display_date DESC',
            'slavedb' => true,
        );

        $rc_list = $this->recommend_tb_model->getList($params, $extra)->getData();

        foreach($rc_list as $idx => $val) {
            $rc_list[$idx]['ticker'] = $this->ticker_info_map[$val['rc_ticker']];

            if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :
                $val['rc_goal_price'] = $val['rc_adjust_price'];
            endif;

            if($val['rc_endtype'] == 'SUCCESS') {
                $rc_list[$idx]['rc_profit_rate'] = number_format((($val['rc_goal_price']/$val['rc_recom_price'])-1)*100, 2);
            }
            else {
                $rc_list[$idx]['rc_profit_rate'] = number_format((($rc_list[$idx]['ticker']['tkr_close']/$val['rc_recom_price'])-1)*100, 2);
            }
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
        $params['!=']['rc_view_srv'] = 'W';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['join']['mri_tb'] = 'rc_ticker = m_ticker';
        $extra = array(
            'fields' => array('recommend_tb.*', 'm_biz_total_score', 'm_biz_dividend_score', 'm_biz_growth_score', 'm_biz_moat_score', 'm_biz_safety_score', 'm_biz_cashflow_score', 'm_biz_dividend_stars', 'm_biz_moat_stars', 'm_biz_growth_stars', 'm_biz_safety_stars', 'm_biz_cashflow_stars'),
            'slavedb' => true,
        );
        $rc_data = $this->recommend_tb_model->getList($params, $extra)->getData();

        if(sizeof($rc_data) != 1) {
            $this->common->historyback();
            return;
        }

        $rc_data = array_pop($rc_data);

        $free_ticker = get_cookie('free_ticker');

        if($rc_data['rc_endtype'] !='SUCCESS' && $rc_data['rc_endtype'] !='SELL' && $free_ticker != $rc_data['rc_ticker']) {
            /*
			$link_type = $this->input->get('type');

            if($link_type=='at'){
                $this->loginCheck();
                $this->payCheck();
            }
            else {
                $this->payCheck();
                $this->loginCheck();
            }
			*/
			$this->payCheck();
        }

        //$rc_data['rc_ticker'] = 'GOGL';
        $rc_data['ticker'] = $this->ticker_info_map[$rc_data['rc_ticker']];

        //추천일 구하기(20.11/05 수정)
        $params = array();
        $params['=']['rc_ticker'] = $rc_data['rc_ticker'];
        $params['!=']['rc_view_srv'] = 'W';
        $params['!=']['rc_endtype'] = 'ING';
        $params['<=']['rc_display_date'] = $rc_data['rc_display_date'];
        $extra = array(
            'fields' => array('rc_display_date'),
            'order_by' => 'rc_display_date DESC',
            'limit' => '1',
            'slavedb' => true,
        );

        $recom_enddata = array_shift($this->recommend_tb_model->getList($params, $extra)->getData());

        $params = array();
        $params['=']['rc_ticker'] = $rc_data['rc_ticker'];
        $params['!=']['rc_view_srv'] = 'W';
        $params['=']['rc_endtype'] = 'ING';

        if(is_array($recom_enddata) && isset($recom_enddata['rc_display_date'])){
            $params['>']['rc_display_date'] = $recom_enddata['rc_display_date'];
        }
        else {
            $params['<=']['rc_display_date'] = $rc_data['rc_display_date'];
        }

        $extra = array(
            'fields' => array('rc_display_date'),
            'order_by' => 'rc_display_date ASC',
            'limit' => '1',
            //'cache_sec' => 3600,
            'slavedb' => true,
        );

        $recom_firstdata = array_shift($this->recommend_tb_model->getList($params, $extra)->getData());

        if(is_array($recom_firstdata) && isset($recom_firstdata['rc_display_date'])) {
            $rc_data['rc_display_date'] = $recom_firstdata['rc_display_date'];
        }
        else {
            if($rc_data['rc_endtype'] != 'ING') {
                $params = array();
                $params['=']['rc_ticker'] = $rc_data['rc_ticker'];
                $params['!=']['rc_view_srv'] = 'W';
                $params['=']['rc_endtype'] = 'ING';
                $params['<']['rc_display_date'] = $recom_enddata['rc_display_date'];
                $recom_firstdata = array_shift($this->recommend_tb_model->getList($params, $extra)->getData());
                if(is_array($recom_firstdata) && isset($recom_firstdata['rc_display_date'])) {
                    $rc_data['rc_display_date'] = $recom_firstdata['rc_display_date'];
                }
            }
        }

        if($this->is_soft === true){
            $host = 'hana2';
        }
        else {
            $host = 'hana';
        }

        $rc_data['rc_invest_point'] = str_replace('https://www.choicestock.co.kr/','https://'.$host.'.choicestock.co.kr/'.HN.'_',$rc_data['rc_invest_point']);

        $data['rc_data'] = $rc_data;
        //echo '<pre>'; print_r($rc_data);
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
        //$data['footer_type'] = '3';
        //$data['footer_notice'] = true;

        $this->header_data['header_template'] = '2';
        //$this->header_data['header_type'] = 'sch_heaher'; // 흰색 배경색에 종목명 노출, 검색영역 없는 타입 헤더
        $this->header_data['head_title'] = '종목추천';
        $this->header_data['back_url'] = '/'.HN.'_stock/recommend';
        //$this->header_data['back_url'] = '/';

          
        $data['meta_title'] = (($rc_data['rc_title'] != '') ? $rc_data['rc_title'].' | ':'').'종목추천 - 초이스스탁US';
        //15min
        $data['is_open'] = $this->is_open;
        if($this->is_open === true) {
            $data['ticker'] = $this->ticker_tb_model->convertSyncInfo($rc_data['ticker']);
        }
        $this->_view('/stock/recommend_view', $data);
    }

    // 종목분석 메인
    public function analysis() {
        return; //remove
        if($this->session->userdata('user_level') != '9') {
            $this->common->locationhref('/');
            exit;
        }

        $this->load->model(array(
            DBNAME.'/analysis_tb_model',
        ));

        $data = array();

        // 투자매력점수 80점 이상인 최신등록 3건
        $params = array();
        $params['=']['an_is_active'] = 'YES';
        $params['!=']['an_view_srv'] = 'W';
        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
        $params['>=']['m_biz_total_score'] = '80';
        $params['join']['mri_tb'] = 'm_ticker = an_ticker';
        $extra = array(
            'fields' => array('analysis_tb.*', 'm_biz_total_score', 'm_v_fairvalue1', 'm_v_fairvalue2', 'm_v_fairvalue3', 'm_v_fairvalue4', 'm_v_fairvalue5', 'm_close'),
            'order_by' => 'an_created_at DESC',
            'limit' => '3',
            'cache_sec' => 3600,
            'slavedb' => true,
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
            $top_analy[$idx]['fairvalue_rate'] =  $this->get_fairrate($top_analy[$idx]['ticker']['tkr_close'], $val);

            if($top_analy[$idx]['fairvalue_rate']== -5) $top_analy[$idx]['fairvalue_rate'] = 0;
            else if($top_analy[$idx]['fairvalue_rate'] == 106) $top_analy[$idx]['fairvalue_rate'] = 100;

            //$top_analy[$idx]['expected_star'] = $this->cal_valuation($val['m_v_fairvalue3'], $val['m_close']);
            //$top_analy[$idx]['fairvalue'] = $val['m_v_fairvalue3'];
            //$top_analy[$idx]['an_opinion'] = $an_items[$val['an_ticker']];
        }

        //$top_analy
        $data['top_analy'] = $top_analy;
        
        // 종목분석 리스트
        // 초기에 리스팅할 카운트 설정.
        $an_list = $this->_get_analysis_data($page=1);

        $list_data = array();
        $list_data['an_list'] = $an_list;
        $list_data['star_investopinion_map'] = $this->mri_tb_model->getStarInvestOpinionMap();
            
        $data['content_html'] = $this->load->view('/'.HN.'/stock/analysis_list', $list_data, true);
        $this->header_data['header_template'] = '2';
        $data['ticker_submenu'] = 'analysis';
        $this->header_data['head_title'] = '종목 분석';

        //심사용
        //$data['part_all'] = get_cookie('part_all');

        $data['meta_title'] = '종목분석 - 초이스스탁US';
        $this->_view('/stock/analysis', $data);
    }

    // 종목분석 리스트 [더보기]
    public function ajax_get_analysis_list() {
        return; //remove
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $request = $this->input->post();
        if( ! ( isset($request['page']) && is_numeric($request['page']) && strlen($request['page']) > 0)) {
            return;
        }

        if($request['page']>1 && $this->session->userdata('is_paid') === FALSE ) {
            return;
        }

        $page = $request['page'];
        $an_list = $this->_get_analysis_data($page);
        $list_data = array();
        $list_data['an_list'] = $an_list;
        $list_data['star_investopinion_map'] = $this->mri_tb_model->getStarInvestOpinionMap();
            
        echo $this->load->view('/'.HN.'/stock/analysis_list', $list_data, true);


        return;
    }

    // (공통) 종목분석 리스트 가져오기.
    private function _get_analysis_data($page=1, $limit=30) {
        return; //remove
        $this->load->model(array(
            DBNAME.'/analysis_tb_model',
        ));

        $params = array();
        $params['=']['an_is_active'] = 'YES';
        $params['!=']['an_view_srv'] = 'W';
        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
        $params['join']['mri_tb'] = 'an_ticker = m_ticker';
        $extra = array(
            'fields' => array('analysis_tb.*', 'm_biz_total_score', 'm_v_fairvalue1', 'm_v_fairvalue2', 'm_v_fairvalue3', 'm_v_fairvalue4', 'm_v_fairvalue5', 'm_close'),
            'order_by' => 'an_created_at DESC',
            'limit' => $limit,
            'offset' => $limit * ($page-1),
            'cache_sec' => 3600,
            'slavedb' => true,
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

            $an_list[$idx]['fairvalue_rate'] =  $this->get_fairrate($an_list[$idx]['ticker']['tkr_close'], $val);

            if($an_list[$idx]['fairvalue_rate']== -5) $an_list[$idx]['fairvalue_rate'] = 0;
            else if($an_list[$idx]['fairvalue_rate'] == 106) $an_list[$idx]['fairvalue_rate'] = 100;

            //$an_list[$idx]['expected_star'] = $this->cal_valuation($val['m_v_fairvalue3'], $val['m_close']);
            //$an_list[$idx]['fairvalue'] = $val['m_v_fairvalue3'];
            //$an_list[$idx]['an_opinion'] = $an_items[$val['an_ticker']];
        }

        return $an_list;
    }

    // 종목분석 상세
    public function analysis_view() {
        return; //remove
        if($this->session->userdata('user_level') != '9') {
            $this->common->locationhref('/');
            exit;
        }

        $link_type = $this->input->get('type');

        if($link_type=='at'){
            $this->loginCheck();
            $this->payCheck();
        }
        else {
            $this->payCheck();
            $this->loginCheck();
        }

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
        $params['!=']['an_view_srv'] = 'W';
        $params['=']['an_is_active'] = 'YES';
        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
        $params['join']['mri_tb'] = 'an_ticker = m_ticker';
        $extra = array(
            'fields' => array('analysis_tb.*', 'm_biz_total_score', 'm_v_fairvalue1', 'm_v_fairvalue2', 'm_v_fairvalue3', 'm_v_fairvalue4', 'm_v_fairvalue5', 'm_close'),
            'slavedb' => true,
        );
        $an_data = $this->analysis_tb_model->getList($params, $extra)->getData();

        if(sizeof($an_data) != 1) {
            $this->common->historyback();
            return;
        }

        $an_data = array_pop($an_data);
        $an_data['ticker'] = $this->ticker_info_map[$an_data['an_ticker']];

        $an_data['fairvalue_rate'] =  $this->get_fairrate($an_data['ticker']['tkr_close'], $an_data);

        if($an_data['fairvalue_rate']== -5) $an_data['fairvalue_rate'] = 0;
        else if($an_data['fairvalue_rate'] == 106) $an_data['fairvalue_rate'] = 100;

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
        //echo '<pre>'; print_r($an_data); 

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

        //echo '<pre>';
        //print_r($data['orig_mrq_data']);
        //echo '</pre>';exit;

        // 조회수 증가
        $update_params = array(
            'an_view_count' => 'an_view_count+1'
        );
        $this->analysis_tb_model->doUpdate($an_id, $update_params, array('an_view_count'));

        $this->header_data['header_template'] = '3';
        $this->header_data['head_title'] = '종목분석';
        $this->header_data['header_type'] = 'sch_heaher'; // 흰색 배경색에 종목명 노출, 검색영역 없는 타입 헤더
        $this->header_data['back_url'] = '/stock/analysis';
        //$this->header_data['back_url'] = '/';

        //$data['footer_notice'] = true;
        $data['meta_title'] = '종목분석 - 초이스스탁US';
        $this->_view('/stock/analysis_view', $data);
    }

    // 투자 레시피 Intro
    public function recipe_intro() {

        $data = array();
        $this->header_data['header_template'] = '6';
        $this->header_data['head_title'] = '투자 레시피';
        $data['meta_title'] = '투자레시피 - 초이스스탁US';
        $data['is_soft'] = $this->is_soft;
        $this->_view('/stock/recipe_intro', $data);
    }

    // 투자 레시피
    public function recipe($type='dividend') {
        $this->load->model(DBNAME.'/mri_tb_model');

        $data = array();

        switch($type) {
            case 'dividend':
                $data['title'] = '배당매력주';
                $data['content'] = '초보도 벌 수 있는 투자의 정석<br>‘고배당주에 투자하라’';
                $data['subtitle'] = '★ 배당매력 / % 배당수익률';
				$data['bmimg'] = 'bgimg01';
                $list = $this->_get_recipe_data('dividend');
                break;
            case 'growth': 
                $data['title'] = '이익성장주';
                $data['content'] = '위대한 기업을 찾는 공식<br>‘내일의 넷플릭스를 찾아라’';
                $data['subtitle'] = '★ 수익성장성 / % 순이익성장률';
				$data['bmimg'] = 'bgimg02';
                $list = $this->_get_recipe_data('growth');
                break;
            case 'moat':
                $data['title'] = '소비자독점';
                $data['content'] = '워런 버핏 투자 전략의 핵심<br>‘소비자 독점 기업을 찾아라’';
                $data['subtitle'] = '★ 사업독점력 / % 영업이익률';
				$data['bmimg'] = 'bgimg03';
                $list = $this->_get_recipe_data('moat');
                break;
            case 'total_score': 
                $data['title'] = '슈퍼스톡';
                $data['content'] = '뛰는 주 위에 나는 주<br>‘슈퍼 종목을 찾아라’';
                $data['subtitle'] = '★ 투자매력 / % 5년ROE';
				$data['bmimg'] = 'bgimg04';
                $list = $this->_get_recipe_data('total_score');
                break;
            case 'earnings': 
                $data['title'] = '실적발표';
                $data['content'] = '이번 분기 어닝 서프라이즈 기업은?<br>‘기업은 실적으로 말한다’';
                $data['subtitle'] = '발표순이익(백만달러), % 전년대비';
				$data['bmimg'] = 'bgimg05';
                $list = $this->_get_recipe_data('earnings');
                break;
        }

        $list = $this->_get_recipe_data($type);
        $data['score_content_html'] = $this->load->view('/'.HN.'/stock/recipe_list.php', array('type' => $type, 'list' => $list), true);
        $data['type'] = $type;
        $data['up_date'] = $list[0]['m_sep_date'];

        if($type=='earnings') {
            $data['up_date'] = $list['recent_report_day'];
        }

        $this->header_data['header_template'] = '6';
        $this->header_data['head_title'] = '투자 레시피';
        $this->header_data['back_url'] = '/'.HN.'_stock/recipe_intro';

        $data['meta_title'] = $data['title'].' | 투자레시피 - 초이스스탁US';
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
            case 'earnings': // 실적발표
                $list = $this->_get_recipe_data('earnings');
                break;
        }

        $content_html = $this->load->view('/'.HN.'/stock/recipe_list.php', array('type' => $type, 'list' => $list), true);
        echo $content_html;
        return;
    }

    private function _get_recipe_data($type) {
        $this->load->model(DBNAME.'/mri_tb_model');

        $request = $this->input->get();

        $limit = 30; 
        $page = (isset($request['page']) && strlen($request['page']) > 0) ? $request['page'] : '1';

        if($type == 'earnings') {

            $recent_file = 'recent_report.inc';
            $file_path = QUANDL_WEBDATA.'/'.$recent_file;
            if( is_file($file_path) ) {
                $recent_report_file = unserialize(file_get_contents($file_path));
            }                
            if($page == 1 && sizeof($recent_report_file)>0) {
                $list = $recent_report_file;
            }
            else {
                $this->load->model(array(
                    'business/itoozaapi',
                    DBNAME.'/sf1_tb_model',
                ));

                // 최근 실적발표
                $params = array();
                $params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker';
                $params['join']['daily_tb'] = 'tkr_ticker = dly_ticker';
                $params['=']['sf1_dimension'] = 'MRQ'; 
                $params['=']['tkr_is_active'] = 'YES'; 
                $params['<=']['DATEDIFF(sf1_lastupdated, sf1_reportperiod)'] = '60'; 

                $extra = array(
                    'fields' => array('tkr_ticker', 'tkr_name', 'tkr_rate', 'tkr_rate_str', 'tkr_close', 'tkr_exchange', 'sf1_netinccmnusd', 'dly_marketcap', 'sf1_lastupdated'),
                    'order_by' => 'sf1_lastupdated desc, sf1_netinccmnusd desc',
                    'offset' => $limit * ($page-1),
                    'limit' => $limit,
                    'cache_sec' => 3600*2,
                    'slavedb' => true,
                );

                $recent_report = $this->sf1_tb_model->getList($params, $extra)->getData();
                $list['recent_report'] = $recent_report;

                // 최근 실적발표 전년동기 대비 실적
                $recent_report_tickers = array_keys($this->common->getDataByPK($recent_report, 'tkr_ticker'));
                $recent_report_rates = $this->itoozaapi->getIncomeGrowthRate($recent_report_tickers);

                $list['recent_report_rates'] = $recent_report_rates['rate'];
                $list['recent_report_rates_pm'] = $recent_report_rates['rate_pm'];
                $list['recent_report_day'] = array_shift(array_values($recent_report_rates['lastupdated']));
            }
        }
        else {
            $add_params = array();
            $add_params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
            if($type=='growth'||$type=='moat') {
                $add_params['>=']['dly_marketcap'] = '10000';
            }
            $add_extra = array(
                'offset' => $limit * ($page-1),
            );
            $add_extra['slavedb'] = true;
            $add_extra['cache_sec'] = 3600*2;

            $list = $this->mri_tb_model->getRecomStockList($type, $limit, $add_params, $add_extra);
        }

        return $list;
    }

    public function research($order_by='display_date') {
        $this->load->model(DBNAME.'/explore_tb_model');

        $data = array();

        // 6개월간 조회수 높은 콘텐츠 TOP 5중 3건 랜덤노출
        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['!=']['e_view_srv'] = 'W';
        $params['!=']['e_is_inside'] = 'Y';
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

        if( ! in_array($order_by, array('display_date', 'view_count'))) {
            $order_by = 'display_date';
        }
        $data['order_by'] = $order_by;
 
        // 리스팅
        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['e_view_srv'] = 'W';
        $params['!=']['e_is_inside'] = 'Y';
        $extra = array(
            'order_by' => 'e_'.$order_by.' DESC',
            'limit' => '15',
        );
        $explore = $this->explore_tb_model->getList($params, $extra)->getData();
        $data['explore'] = $explore;

        $this->header_data['header_template'] = '8';
        $this->header_data['head_title'] = '미국주식 탐구생활';

        $data['meta_title'] = '탐구생활 - 초이스스탁US';
        $data['is_event'] = $this->is_event;
        $data['is_soft'] = $this->is_soft;
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
        if( ! in_array($order_by, array('display_date', 'view_count'))) {
            $order_by = 'display_date';
        }

        $page = $request['page'];

        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['e_view_srv'] = 'W';
        $params['!=']['e_is_inside'] = 'Y';
        $extra = array(
            'order_by' => 'e_'.$order_by.' DESC',
            'offset' => ($page-1) * 15,
            'limit' => 15,
        );
        $explore = $this->explore_tb_model->getList($params, $extra)->getData();
        $list_data = array();
        $list_data['explore'] = $explore;
        $list_data['is_event'] = $this->is_event;
        echo $this->load->view('/'.HN.'/stock/research_list', $list_data, true);
    }


    public function research_view($no=0) {
        $this->load->model(DBNAME.'/explore_tb_model');

        $data = array();

        if( ! $this->explore_tb_model->get($no)->isSuccess()) {
            $this->common->historyback();
            return;
        }

        $row = $this->explore_tb_model->getData();

        if($row['e_is_active'] != 'YES' || $row['e_display_date'] > date('Y-m-d H:i:s') || ($row['e_view_srv'] != '' && $row['e_view_srv'] != 'C') || $row['e_is_inside'] == 'Y') {
            $this->common->historyback();
            return;
        }

        $link_type = $this->input->get('type');

        if( $row['e_pay'] == 'Y') {
			/*
            if($link_type=='at'){
                $this->loginCheck();
                $this->payCheck();
            }
            else {
                $this->payCheck();
                $this->loginCheck();
            }
			*/
            $this->payCheck();
        }

        if($this->is_soft === true){
            $host = 'hana2';
        }
        else {
            $host = 'hana';
        }

        $row['e_content'] = str_replace('https://www.choicestock.co.kr/','https://'.$host.'.choicestock.co.kr/'.HN.'_',$row['e_content']);

        $data['row'] = $row;


        // 탐구생활 
        $params = array();
        $params['=']['e_is_active'] = 'YES';
        $params['<=']['e_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['e_view_srv'] = 'W';
        $params['!=']['e_is_inside'] = 'Y';
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
        $this->header_data['back_url'] = '/'.HN.'_stock/research';
        //$this->header_data['back_url'] = '/';

        $data['meta_title'] = $row['e_title'].' | 탐구생활 - 초이스스탁US';

        $this->_view('/stock/research_view', $data);
    }

    public function morning() {
        $this->load->model(DBNAME.'/morning_tb_model');

        $data = array();
        // 리스팅
        $params = array();
        $params['=']['mo_is_active'] = 'Y';
        $params['<=']['mo_display_date'] = date('Y-m-d H:i:s');
        $extra = array(
            'order_by' => 'mo_display_date DESC',
            'limit' => '15',
        );
        $morning = $this->morning_tb_model->getList($params, $extra)->getData();
        $data['morning'] = $morning;

        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '모닝브리핑';

        $data['meta_title'] = '모닝브리핑 - 초이스스탁US';
        $data['is_event'] = $this->is_event;
        $this->header_data['back_url'] = '/';

        $this->_view('/stock/morning', $data);
    }

    // 종목분석 리스트 [더보기]
    public function ajax_get_morning_list() {
        $this->load->model(DBNAME.'/morning_tb_model');
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $request = $this->input->post();
        if( ! ( isset($request['page']) && is_numeric($request['page']) && strlen($request['page']) > 0)) {
            return;
        }

        $page = $request['page'];

        $params = array();
        $params['=']['mo_is_active'] = 'Y';
        $params['<=']['mo_display_date'] = date('Y-m-d H:i:s');
        $extra = array(
            'order_by' => 'mo_display_date DESC',
            'offset' => ($page-1) * 15,
            'limit' => 15,
        );
        $morning = $this->morning_tb_model->getList($params, $extra)->getData();
        $list_data = array();
        $list_data['morning'] = $morning;
        $list_data['is_event'] = $this->is_event;
        echo $this->load->view('/'.HN.'/stock/morning_list', $list_data, true);
    }

    public function morning_view($no=0) {
		/*
        $link_type = $this->input->get('type');

        if($link_type=='at'){
            $this->loginCheck();
            $this->payCheck();
        }
        else {
            $this->payCheck();
            $this->loginCheck();
        }
		*/
        $this->payCheck();

        $this->load->model(DBNAME.'/morning_tb_model');

        $data = array();

        if( ! $this->morning_tb_model->get($no)->isSuccess()) {
            $this->common->historyback();
            return;
        }

        $row = $this->morning_tb_model->getData();

        if($row['mo_display_date'] > date('Y-m-d H:i:s') || $row['mo_is_active'] == 'N') {
            $this->common->historyback();
            return;
        }

        $row['mo_contents'] =  $this->auto_link($row['mo_contents']);
        $data['row'] = $row;

        // 조회수 증가
        
        $update_params = array(
            'mo_view_count' => 'mo_view_count+1'
        );
        $this->morning_tb_model->doUpdate($no, $update_params, array('mo_view_count'));

        //$this->header_data['header_template'] = '4';
        $this->header_data['head_title'] = '모닝브리핑';
        $this->header_data['back_url'] = '/'.HN.'_stock/morning';
        //$this->header_data['back_url'] = '/';
        $data['meta_title'] = $row['mo_title'].' | 모닝브리핑 - 초이스스탁US';

        $this->_view('/stock/morning_view', $data);
    }

    // 알림 리스트
    public function alarm() {

        // 알림
        $params = array();
        $params['=']['nt_is_active'] = 'YES';
        $params['!=']['nt_table'] = 'master_tb';
        $params['!=']['nt_view_srv'] = 'W';
        $params['<=']['nt_display_date'] = date('Y-m-d H:i:s');

        $extra = array();
        $extra['limit'] = 20;
        $extra['order_by'] = 'nt_display_date DESC';
        $extra['slavedb'] = true;
        $list = $this->notify_tb_model->getList($params, $extra)->getData();

        //echo '<pre>'; print_r($list);
        $data = array();
        //$data['noti_list'] = $list;
        $this->header_data['noti_list'] = $list; //$this->notify_tb_model->getList($params, $extra)->getData();
        $this->header_data['noti_table_map'] = $this->notify_tb_model->getTableMap();
        /*
        echo '<pre>'; print_r($list);
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
        */
        $this->header_data['header_template'] = '10';
        $this->header_data['back_url'] = '/';
        $this->header_data['head_title'] = '알림';
        $this->_view('/stock/alarm', $data);
    }

    // 대가의 종목 리스트
    public function master() {
        return; //remove
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
        return; //remove
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

    public function push_control($push='') {
        return; //remove
        $push = strtoupper($push);
        if( in_array($push, array('Y', 'N'))) {
            $this->load->model(DBNAME.'/control_tb_model');
            $con_id = '1';
            $update_params = array(
                'con_status' => $push,
            );
            $this->control_tb_model->doUpdate($con_id, $update_params);
            $this->common->locationhref('/'.HN.'_stock/view_close');
            exit;
        }
        else {
            $this->common->locationhref('/');
            exit;
        }
    }

    //전일종가 갯수 확인
    public function view_close($view_date='') {
        return; //remove
        //$this->load->model(DBNAME.'/ticker_tb_model');
        $this->load->model(DBNAME.'/control_tb_model');

        if($view_date=='' || strlen($view_date) != 8 || !is_numeric($view_date) ) $view_date = date('Ymd', strtotime('-1 days'));
        
        if($this->control_tb_model->get(array('con_id' => '1'))->isSuccess()) {
            $con_row = $this->control_tb_model->getData();
        }
        else {
            echo 'control_tb :: select error!!';
            exit;
        }

        if($con_row['con_status'] == 'Y') {
            $push_status = '전송가능';
        }
        else {
            $push_status = '전송불가';
        }

        $week = array('일', '월', '화', '수', '목', '금', '토');

        $params = array();
        $params['=']['tkr_lastpricedate'] =  date('Y-m-d', strtotime($view_date));
        $params['slavedb'] = true;
        $ticker_count = $this->ticker_tb_model->getCount($params)->getData();
        $sep_count = $this->itoozaapi->getSepCount($view_date);

        $ticker1 = $this->itoozaapi->getSEPListForChart(array('MSFT'));
        $ticker1 = array_reverse(array_slice($ticker1['MSFT'], -3, 3));

        $ticker2 = $this->itoozaapi->getSEPListForChart(array('AAPL'));
        $ticker2 = array_reverse(array_slice($ticker2['AAPL'], -3, 3));

        echo  '[ '.date('Y년 m월 d일', strtotime($view_date)).'  '.$week[date('w',strtotime($view_date))].'요일 ]';
        echo '<br><br>';
        echo '□ 현재상태 : '.$push_status;
        echo '<br><br>';
        echo '□ ticker_tb 개수 : '.number_format($ticker_count);
        echo '<br><br>';
        echo '□ sep_tb 개수 : '.number_format($sep_count);
        echo '<br><br>';
        echo '마이크로소프트(MSFT)<br>';
        foreach($ticker1 as $key=>$val) {
            echo $key.' : '.$val.'<br>';        
        }
        echo '<br>';
        echo '애플(AAPL)<br>';
        foreach($ticker2 as $key=>$val) {
            echo $key.' : '.$val.'<br>';        
        }
        echo '<br>';
        echo '※ ticker_tb 개수가 5,000미만 일 경우 push 중지';
        echo '<br><br>';
        echo '<a href="/stock/push_control/Y">push 전송</a> || <a href="/stock/push_control/N">push 중지</a>';
    }

    public function add_catch() {

        $this->payCheck();
        //$this->loginCheck();

        $pg = $this->input->get('page');
        $idx = $this->input->get('idx');
        $mode = $this->input->get('mode');

        if($pg < 1 || $mode != 'auto' || $idx < 1) {
            exit;
        }

        $user_id = $this->session->userdata('user_id');
        $is_next = true;
        //캐치 확인
        $this->load->model(DBNAME.'/catch_tb_model');

        $params = array();
        $params['=']['c_user_id'] = $user_id;

        $extra = array(
            'fields' => 'c_tickerlist, c_type, c_size, c_sector',
            'slavedb' => true
        );

        $catch_list = array_shift($this->catch_tb_model->getList($params, $extra)->getData());
        
        if(is_array($catch_list) && sizeof($catch_list)>0 && $catch_list['c_tickerlist'] != '') {

            $ticker_list = explode('|',$catch_list['c_tickerlist']);
            $sector_list = explode('|',$catch_list['c_sector']);

            $total_count = count($ticker_list)-1;
            $cnt = $pg*30;

            $ticker_list = array_slice($ticker_list, $cnt, 30);
            
            if(($total_count-$cnt)<=30) $is_next = false;

            $this->load->model(array(
                'business/itoozaapi',
                DBNAME.'/recommend_tb_model',
                DBNAME.'/ticker_tb_model',
                DBNAME.'/mri_tb_model',
            ));

            $params = array();
            $params['in']['tkr_ticker'] = array_filter($ticker_list);
            //$params['=']['tkr_is_active'] = 'YES';
            $extra = array(
                'fields' => array('tkr_ticker'),
                //'cache_sec' => 3600*6,
                'slavedb' => true,
                'order_by' => 'tkr_rate desc'
            );

            $ticker_list = $this->ticker_tb_model->getList($params, $extra)->getData();

            if(is_array($ticker_list) && sizeof($ticker_list)>0) {

                $ticker_rep = array();

                if(is_array($sector_list) && sizeof($sector_list) > 0) {
                    foreach($sector_list as $key=>$val) {
                        if($val=='1') {
                            $ticker_rep[] = $this->ticker_rep[$key];
                        }
                    }
                    shuffle($ticker_rep);
                }

                $catch_all = '';
                foreach($ticker_list as $key => $ticker) {

                    if($ticker['tkr_ticker']!='') {

                        $ticker_info[$ticker['tkr_ticker']] = $this->ticker_info_map[$ticker['tkr_ticker']];

                        // 종목추천에 등록된 ticker 인지 확인
                        $ticker_info[$ticker['tkr_ticker']]['is_recom_ticker'] = FALSE;

                        $params = array();
                        $params['=']['rc_ticker'] = $ticker['tkr_ticker'];
                        $params['=']['rc_is_active'] = 'YES';
                        $params['!=']['rc_view_srv'] = 'W';
                        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
                        $params['in']['rc_endtype'] = array('ING','SUCCESS','SELL');
                        $extra = array(
                            'order_by' => 'rc_id DESC',
                            'slavedb' => TRUE
                        );

                        $recom_data = $this->recommend_tb_model->getList($params, $extra)->getData();
                        if(sizeof($recom_data) > 0) {
                            $recom = array_shift($recom_data);
                            $ticker_info[$ticker['tkr_ticker']]['is_recom_ticker'] = TRUE;
                            $ticker_info[$ticker['tkr_ticker']]['recom_link'] = '/'.HN.'_stock/recommend_view/'.$recom['rc_id'];
                        }


                        $ticker_info[$ticker['tkr_ticker']]['like'] = $this->like($ticker['tkr_ticker'], 'Y');

                        //스파이더 데이터 가져오기
                        $params = array();
                        $params['=']['m_ticker'] = $ticker['tkr_ticker'];
                        $extra = array(
                            'fields' => array('m_biz_dividend_score', 'm_biz_growth_score', 'm_biz_moat_score', 'm_biz_total_score', 'm_v_fairvalue1', 'm_v_fairvalue2', 'm_v_fairvalue3', 'm_v_fairvalue4', 'm_v_fairvalue5', 'm_close', 'm_scalemarketcap'),
                            'slavedb' => true,
                        );

                        $mri_info = array_shift($this->mri_tb_model->getList($params, $extra)->getData());

                        $ticker_info[$ticker['tkr_ticker']]['mri'] = $mri_info;
                        $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] =  $this->get_fairrate($ticker_info[$ticker['tkr_ticker']]['tkr_close'], $mri_info);
                        if($ticker_info[$ticker['tkr_ticker']]['fairvalue_rate']== -5) $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] = 0;
                        else if($ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] == 106) $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] = 100;
                        
                        
                        $chart_value = array();
                        $chart = $this->itoozaapi->getSEPListForChart(array($ticker['tkr_ticker']));

                        if(isset($chart[$ticker['tkr_ticker']]) && is_array($chart[$ticker['tkr_ticker']])) {
                            $chart_value = array_slice($chart[$ticker['tkr_ticker']], -20, 20);
                        }

                        $ticker_info[$ticker['tkr_ticker']]['chart_value'] = implode(',', array_values($chart_value));
                        $ticker_info[$ticker['tkr_ticker']]['chart_key'] = array_keys($chart_value);

                        $tkr_close = $this->common->set_pricepoint($ticker_info[$ticker['tkr_ticker']]['tkr_close'], '1');

                        $updown = 'decrease';
                        if($ticker_info[$ticker['tkr_ticker']]['tkr_rate'] > 0) $updown = 'increase';

                        $tkr_rate_str = $this->common->set_pricepoint($ticker_info[$ticker['tkr_ticker']]['tkr_rate_str'], '2');
                        
                        $lastpricedate = '';
                        if(isset($ticker_info[$ticker['tkr_ticker']]['tkr_lastpricedate'])&&$ticker_info[$ticker['tkr_ticker']]['tkr_lastpricedate']) $lastpricedate = date('y.m/d', strtotime($ticker_info[$ticker['tkr_ticker']]['tkr_lastpricedate'])).', ';
                        
                        
                        $recom = '';
                        if($ticker_info[$ticker['tkr_ticker']]['is_recom_ticker']) {
                            $recom  = '<div class="go_page">'."\n";
                            $recom .= '    <a href="'.$ticker_info[$ticker['tkr_ticker']]['recom_link'].'"><span class="quarter recom">추천</span></a>'."\n";
                            $recom .= '    <a href="'.$ticker_info[$ticker['tkr_ticker']]['recom_link'].'" class="more"><img src="/img/more_white.png" alt="더보기"></a>'."\n";
                            $recom .= '</div>'."\n";
                        }

                        $fairvalue  = '<span class="i_graph no_value">'."\n";
                        $fairvalue .= '<span class="g_bar"><span class="g_action" style="left: 50%;"></span></span>'."\n";
                        $fairvalue .= '</span>'."\n";
                        if($ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] >= -5) {
                            $fairvalue  = '<span class="i_graph">'."\n";
                            $fairvalue .= '<span class="g_bar"><span class="g_action" style="left: '.$ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'].'%;"></span></span>'."\n";
                            $fairvalue .= '</span>'."\n";
                        }
                        $recipe = '';

                        if($ticker_info[$ticker['tkr_ticker']]['mri']['m_biz_dividend_score'] >= 16) {
                            $recipe = '<li><a href="/'.HN.'_stock/recipe/dividend">배당주</a></li>'."\n";
                        }
                        if($ticker_info[$ticker['tkr_ticker']]['mri']['m_biz_growth_score'] >= 16) {
                            $recipe .= '<li><a href="/'.HN.'_stock/recipe/growth">성장주</a></li>'."\n";
                        }
                        if($ticker_info[$ticker['tkr_ticker']]['mri']['m_biz_moat_score'] >= 16) {
                            $recipe .= '<li><a href="/'.HN.'_stock/recipe/moat">가치주</a></li>'."\n";
                        }

                        $marketcap = '소';
                        if(in_array($ticker_info[$ticker['tkr_ticker']]['mri']['m_scalemarketcap'], array('5 - Large','6 - Mega'))) {
                            $marketcap = '대';
                        }
                        else if(in_array($ticker_info[$ticker['tkr_ticker']]['mri']['m_scalemarketcap'], array('4 - Mid'))) {
                            $marketcap = '중';
                        }

                        $attention = '';
                        if($ticker_info[$ticker['tkr_ticker']]['like']['mi_like']=='Y') {
                            $attention = ' on';
                        }
                        $ru = urlencode(HN.'_stock/catch_info');
                        $tkr_like = number_format($ticker_info[$ticker['tkr_ticker']]['like']['total_count']);

                        $tkr_industry = '';
                        $choice_ticker = '';
                        $choice_name = '';
                        $industry_commnet = '';

                        $tkr_industry = $ticker_info[$ticker['tkr_ticker']]['tkr_industry'];

                        foreach($ticker_rep as $ind_val) {
                            if($tkr_industry==$ind_val['industry'] && $ticker_info[$ticker['tkr_ticker']]['tkr_name'] != $ind_val['name'] ) {
                                $choice_ticker = $ind_val['ticker'];
                                $choice_name = $ind_val['name'];
                                break;
                            }
                        }

                        if($choice_ticker != '' && $choice_name != '') {
                            $industry_commnet = '<span class="same_selt"><a href="/'.HN.'_search/invest_charm/'.$choice_ticker.'">'.$choice_name.'</a>와(과) 같은 업종입니다</span>';
                        }
$pre = HN;
$catch_list = <<<PHPSKIN
<div class="chart_catch">
    <div class="data_area">
        <h2 class="title"><a href="/{$pre}_search/invest_charm/{$ticker['tkr_ticker']}#">{$ticker_info[$ticker['tkr_ticker']]['tkr_name']}</a></h2>
        <ul class="info">
            <li class="sum"><span class="eng">{$ticker['tkr_ticker']}</span> </li>
        </ul>
        <ul class="detail">
            <li class="num">
                <span>{$tkr_close}</span>
            </li>
            <li class="per">
                <span class="{$updown}">{$ticker_info[$ticker['tkr_ticker']]['tkr_diff_str']}
                <span>({$tkr_rate_str})</span></span>
            </li>
            <li class="day">{$lastpricedate}USD</li>
        </ul>
        {$recom}
        <div class="area">
            <div id="top_analy_{$ticker['tkr_ticker']}" class="containerS1"></div>
                <script>SubAnalyTopChart('top_analy_{$ticker['tkr_ticker']}', [{$ticker_info[$ticker['tkr_ticker']]['chart_value']}]);</script>
                <div class="analysis_score">
                    <span class="score"><strong>{$ticker_info[$ticker['tkr_ticker']]['mri']['m_biz_total_score']}</strong> 점</span>
                    <div class="chart_analysis">
                        <div class="line">
                            {$fairvalue}
                            <ul class="evaluation">
                                <li>저</li>
                                <li>적정가</li>
                                <li>고</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <ul class="info_icon">
            <li>{$ticker_info[$ticker['tkr_ticker']]['tkr_exchange']}</li>
            {$recipe}
            <li>{$marketcap}형주</li>
        </ul>
    </div>
    <p class="attention{$attention}" id="catch_icon_{$idx}" onClick="javascript:fnMyitem('{$ticker['tkr_ticker']}', '{$ru}', 'Y', '{$idx}');"><i></i>관심 <span id='catch_count_{$idx}'>({$tkr_like})</span></p>
    {$industry_commnet}
</div>
PHPSKIN;
                        $catch_all .= $catch_list."\n";
                        $idx++;
                    }
                }

                if($catch_all!='') {
                    $nextPage = $pg + 1;
                    //echo '<br>nextPage==>'.$nextPage;
                    echo $catch_all;
                    if($is_next === true) {
                        echo '<div class="next"><a href="/'.HN.'_stock/add_catch?mode=auto&idx='.$idx.'&page='.$nextPage.'" class="nextPage">다음 페이지</a></div>';
                    }
                }
            }
        }
    }

    public function catch_info() {

        //$this->payCheck();
        //$this->loginCheck();

        $catch_list = array();
        $ticker_info = array();
        $is_next = false;

        if($this->session->userdata('is_paid') === TRUE) {
            $user_id = $this->session->userdata('user_id');

            //캐치 확인
            $this->load->model(DBNAME.'/catch_tb_model');

            $params = array();
            $params['=']['c_user_id'] = $user_id;

            $extra = array(
                'fields' => 'c_tickerlist, c_type, c_size, c_sector',
                'slavedb' => true
            );

            $catch_list = array_pop($this->catch_tb_model->getList($params, $extra)->getData());
        
            if(is_array($catch_list) && sizeof($catch_list)>0 && $catch_list['c_tickerlist'] != '') {
                $ticker_list = explode('|',$catch_list['c_tickerlist']);
                $total_count = count($ticker_list)-1;

                if($total_count>30) $is_next = true;

                $ticker_list = array_slice($ticker_list, 0, 30);

                $this->load->model(array(
                    'business/itoozaapi',
                    DBNAME.'/recommend_tb_model',
                    DBNAME.'/analysis_tb_model',
                    DBNAME.'/ticker_tb_model',
                    DBNAME.'/mri_tb_model',
                ));

                $params = array();
                $params['in']['tkr_ticker'] = array_filter($ticker_list);
                //$params['=']['tkr_is_active'] = 'YES';
                $extra = array(
                    'fields' => array('tkr_ticker'),
                    //'cache_sec' => 3600*6,
                    'slavedb' => true,
                    'order_by' => 'tkr_rate desc'
                );

                $ticker_list = $this->ticker_tb_model->getList($params, $extra)->getData();

                foreach($ticker_list as $key => $ticker) {

                    if($ticker['tkr_ticker']!='') {

                        $ticker_info[$ticker['tkr_ticker']] = $this->ticker_info_map[$ticker['tkr_ticker']];

                        // 종목추천에 등록된 ticker 인지 확인
                        $ticker_info[$ticker['tkr_ticker']]['is_recom_ticker'] = FALSE;

                        $params = array();
                        $params['=']['rc_ticker'] = $ticker['tkr_ticker'];
                        $params['=']['rc_is_active'] = 'YES';
                        $params['!=']['rc_view_srv'] = 'W';
                        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
                        $params['in']['rc_endtype'] = array('ING','SUCCESS','SELL');
                        $extra = array(
                            'order_by' => 'rc_id DESC',
                            'slavedb' => TRUE
                        );

                        $recom_data = $this->recommend_tb_model->getList($params, $extra)->getData();
                        if(sizeof($recom_data) > 0) {
                            $recom = array_shift($recom_data);
                            $ticker_info[$ticker['tkr_ticker']]['is_recom_ticker'] = TRUE;
                            $ticker_info[$ticker['tkr_ticker']]['recom_link'] = '/'.HN.'_stock/recommend_view/'.$recom['rc_id'];
                        }

                        // 종목분석에 등록된 ticker 인지 확인
                        /*
                        $ticker_info[$ticker['tkr_ticker']]['is_analysis_ticker'] = FALSE;

                        $params = array();
                        $params['=']['an_ticker'] = $ticker['tkr_ticker']; 
                        $params['!=']['an_view_srv'] = 'W';
                        $params['=']['an_is_active'] = 'YES';
                        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
                        $extra = array(
                            'order_by' => 'an_id DESC',
                            'slavedb' => TRUE
                        );

                        $analysis_data = $this->analysis_tb_model->getList($params, $extra)->getData();
                        if(sizeof($analysis_data) > 0) {
                            $analy = array_shift($analysis_data);
                            $ticker_info[$ticker['tkr_ticker']]['is_analysis_ticker'] = TRUE;
                            $ticker_info[$ticker['tkr_ticker']]['analysis_link'] = '/'.HN.'_stock/analysis_view/'.$analy['an_id'];
                        }
                        */

                        $ticker_info[$ticker['tkr_ticker']]['like'] = $this->like($ticker['tkr_ticker'], 'Y');

                        //스파이더 데이터 가져오기
                        $params = array();
                        $params['=']['m_ticker'] = $ticker['tkr_ticker'];
                        $extra = array(
                            'fields' => array('m_biz_dividend_score', 'm_biz_growth_score', 'm_biz_moat_score', 'm_biz_total_score', 'm_v_fairvalue1', 'm_v_fairvalue2', 'm_v_fairvalue3', 'm_v_fairvalue4', 'm_v_fairvalue5', 'm_close', 'm_scalemarketcap'),
                            'slavedb' => true,
                        );

                        $mri_info = array_shift($this->mri_tb_model->getList($params, $extra)->getData());

                        $ticker_info[$ticker['tkr_ticker']]['mri'] = $mri_info;
                        $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] =  $this->get_fairrate($ticker_info[$ticker['tkr_ticker']]['tkr_close'], $mri_info);
                        if($ticker_info[$ticker['tkr_ticker']]['fairvalue_rate']== -5) $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] = 0;
                        else if($ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] == 106) $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] = 100;
                        
                        
                        $chart_value = array();
                        $chart = $this->itoozaapi->getSEPListForChart(array($ticker['tkr_ticker']));

                        if(isset($chart[$ticker['tkr_ticker']]) && is_array($chart[$ticker['tkr_ticker']])) {
                            $chart_value = array_slice($chart[$ticker['tkr_ticker']], -20, 20);
                        }

                        $ticker_info[$ticker['tkr_ticker']]['chart_value'] = implode(',', array_values($chart_value));
                        $ticker_info[$ticker['tkr_ticker']]['chart_key'] = array_keys($chart_value);
                    }
                }
            }
        }
        else {

            //최근 본 종목(임시)
            //$search_ticker = array_filter(array_unique(array_map('trim', explode(',', $_COOKIE['search_history']))));
            //$search_ticker_codes = array_slice($search_ticker, 0, 5);
            $catch_list = $this->input->get('catch_list');
            $sector_list_free = explode('|',$this->input->get('sector_list'));

            if(isset($catch_list) && $catch_list != '') {
                $catch_list = explode('|',$catch_list);
            }

            if(is_array($catch_list) && sizeof($catch_list)>0) {

                $this->load->model(array(
                    'business/itoozaapi',
                    DBNAME.'/recommend_tb_model',
                    DBNAME.'/analysis_tb_model',
                    DBNAME.'/ticker_tb_model',
                    DBNAME.'/mri_tb_model',
                ));

                $params = array();
                $params['in']['tkr_ticker'] = array_filter($catch_list);
                //$params['=']['tkr_is_active'] = 'YES';
                $params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
                $extra = array(
                    'fields' => array('tkr_ticker'),
                    'cache_sec' => 3600*6,
                    'slavedb' => true,
                    'order_by' => 'dly_marketcap desc'
                );

                $ticker_list = $this->ticker_tb_model->getList($params, $extra)->getData();
                $cnt=0;
                foreach($ticker_list as $key => $ticker) {

                    if($ticker['tkr_ticker']!='') {
                        if($cnt>7) break;
                        $ticker_info[$ticker['tkr_ticker']] = $this->ticker_info_map[$ticker['tkr_ticker']];

                        // 종목추천에 등록된 ticker 인지 확인
                        $ticker_info[$ticker['tkr_ticker']]['is_recom_ticker'] = FALSE;

                        $params = array();
                        $params['=']['rc_ticker'] = $ticker['tkr_ticker'];
                        $params['!=']['rc_view_srv'] = 'W';
                        $params['=']['rc_is_active'] = 'YES';
                        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
                        $params['in']['rc_endtype'] = array('ING','SUCCESS','SELL');
                        $extra = array(
                            'order_by' => 'rc_id DESC',
                            'slavedb' => TRUE
                        );

                        $recom_data = $this->recommend_tb_model->getList($params, $extra)->getData();
                        if(sizeof($recom_data) > 0) {
                            $recom = array_shift($recom_data);
                            $ticker_info[$ticker['tkr_ticker']]['is_recom_ticker'] = TRUE;
                            $ticker_info[$ticker['tkr_ticker']]['recom_link'] = '/'.HN.'_stock/recommend_view/'.$recom['rc_id'];
                        }

                        // 종목분석에 등록된 ticker 인지 확인
                        /*
                        $ticker_info[$ticker['tkr_ticker']]['is_analysis_ticker'] = FALSE;

                        $params = array();
                        $params['=']['an_ticker'] = $ticker['tkr_ticker']; 
                        $params['!=']['an_view_srv'] = 'W';
                        $params['=']['an_is_active'] = 'YES';
                        $params['<=']['an_display_date'] = date('Y-m-d H:i:s');
                        $extra = array(
                            'order_by' => 'an_id DESC',
                            'slavedb' => TRUE
                        );

                        $analysis_data = $this->analysis_tb_model->getList($params, $extra)->getData();
                        if(sizeof($analysis_data) > 0) {
                            $analy = array_shift($analysis_data);
                            $ticker_info[$ticker['tkr_ticker']]['is_analysis_ticker'] = TRUE;
                            $ticker_info[$ticker['tkr_ticker']]['analysis_link'] = '/'.HN.'_stock/analysis_view/'.$analy['an_id'];
                        }
                        */

                        $ticker_info[$ticker['tkr_ticker']]['like'] = $this->like($ticker['tkr_ticker'], 'Y');

                        //스파이더 데이터 가져오기
                        $params = array();
                        $params['=']['m_ticker'] = $ticker['tkr_ticker'];
                        $extra = array(
                            'fields' => array('m_biz_dividend_score', 'm_biz_growth_score', 'm_biz_moat_score', 'm_biz_total_score', 'm_v_fairvalue1', 'm_v_fairvalue2', 'm_v_fairvalue3', 'm_v_fairvalue4', 'm_v_fairvalue5', 'm_close', 'm_scalemarketcap'),
                            'slavedb' => true,
                        );

                        $mri_info = array_shift($this->mri_tb_model->getList($params, $extra)->getData());

                        $ticker_info[$ticker['tkr_ticker']]['mri'] = $mri_info;
                        $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] =  $this->get_fairrate($ticker_info[$ticker['tkr_ticker']]['tkr_close'], $mri_info);
                        if($ticker_info[$ticker['tkr_ticker']]['fairvalue_rate']== -5) $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] = 0;
                        else if($ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] == 106) $ticker_info[$ticker['tkr_ticker']]['fairvalue_rate'] = 100;
                        
                        
                        $chart_value = array();
                        $chart = $this->itoozaapi->getSEPListForChart(array($ticker['tkr_ticker']));

                        if(isset($chart[$ticker['tkr_ticker']]) && is_array($chart[$ticker['tkr_ticker']])) {
                            $chart_value = array_slice($chart[$ticker['tkr_ticker']], -20, 20);
                        }

                        $ticker_info[$ticker['tkr_ticker']]['chart_value'] = implode(',', array_values($chart_value));
                        $ticker_info[$ticker['tkr_ticker']]['chart_key'] = array_keys($chart_value);

                        $cnt++;
                    }
                }
            }
        }

        $this->header_data['header_template'] = '12';
        $this->header_data['show_alarm'] = FALSE;

        $this->header_data['head_title'] = '초이스스탁';
        $this->header_data['head_url'] = '/';

        $data = array();
        $data['meta_title'] = '종목캐치 - 초이스스탁US';
        $data['ticker_submenu'] = 'catch_info';

        $data['catch_list'] = $ticker_info;

        $type_list = array();
        $type_list = explode('|',$catch_list['c_type']);
        $size_list = array();
        $size_list = explode('|',$catch_list['c_size']);
        $sector_list = array();
        $sector_list = explode('|',$catch_list['c_sector']);

        //$industry_list = array();
        $ticker_rep = array();

        if($this->session->userdata('is_paid') === FALSE) {
            $sector_list = $sector_list_free;
        }

        if(is_array($sector_list) && sizeof($sector_list) > 0) {
            foreach($sector_list as $key=>$val) {
                if($val=='1') {
                    $ticker_rep[] = $this->ticker_rep[$key];
                }
            }
            shuffle($ticker_rep);
        }
        //echo '<pre>'; print_r($ticker_rep);

        $data['c_type'] = $type_list;
        $data['c_type_list'] = $catch_list['c_type'];
       
        $data['c_size'] = $size_list;
        $data['c_size_list'] = $catch_list['c_size'];
        
        $data['c_sector'] = $sector_list;
        $data['c_sector_list'] = $catch_list['c_sector'];
        $data['ticker_rep'] = $ticker_rep;
        $data['is_event'] = $this->is_event;
        $data['is_next'] = $is_next;

        $this->_view('/stock/catch', $data);
    }

    public function set_catch() {
        //$this->loginCheck();

        $user_id = $this->session->userdata('user_id');

        $c_type = $this->input->post('catchType');
        $c_size = $this->input->post('catchSize');
        $c_sector = $this->input->post('catchSector');

        $arr_type = explode('|', $c_type);
        $arr_size = explode('|', $c_size);
        $arr_sector = explode('|', $c_sector);

        $sector_list = array();
        //$industry_list = array();
        foreach($arr_sector as $key=>$val) {
            if($val == '1') {
                if(!in_array($this->ticker_sector[$key], $sector_list)) {
                    $sector_list[] = $this->ticker_sector[$key];
                }
            }
        }

        if( is_array($arr_type) && sizeof($arr_type) > 0 && is_array($arr_size) && sizeof($arr_size) > 0 && is_array($arr_sector) && sizeof($arr_sector) > 0 ) {

            $this->load->model(DBNAME.'/mri_tb_model');
            $params = array();
            $params['join']['daily_tb'] = 'dly_ticker = m_ticker';

            if(isset($arr_type[0]) && $arr_type[0] == '1') {
                $params['>=']['m_biz_dividend_stars'] = 3.5;
            }

            if(isset($arr_type[1]) && $arr_type[1] == '1') {
                $params['>=']['m_biz_growth_stars'] = 3.5;
            }
            
            if(isset($arr_type[2]) && $arr_type[2] == '1') {
                $params['>=']['m_biz_moat_stars'] = 3.5;
            }

            $scalemarketcap = array();
            if(isset($arr_size[0]) && $arr_size[0] == '1') {
                $scalemarketcap[] = '5 - Large';
                $scalemarketcap[] = '6 - Mega';
            }

            if(isset($arr_size[1]) && $arr_size[1] == '1') {
                $scalemarketcap[] = '4 - Mid';
            }

            if(isset($arr_size[2]) && $arr_size[2] == '1') {
                $scalemarketcap[] = '1 - Nano';
                $scalemarketcap[] = '2 - Micro';
                $scalemarketcap[] = '3 - Small';
            }

            if(sizeof($scalemarketcap)>0) {
                $params['in']['m_scalemarketcap'] = $scalemarketcap;
            }

            $params['in']['m_sector'] = $sector_list;

            $extra = array(
                'fields' => array('m_ticker'),
                //'order_by' => 'an_created_at DESC',
                //'limit' => '3',
                'slavedb' => true,
            );

            $mri_ticker = $this->mri_tb_model->getList($params, $extra)->getData();

            $c_tickerlist = '';
            foreach($mri_ticker as $val) {
                $c_tickerlist .= $val['m_ticker'].'|';
            }

            if(isset($user_id) && $user_id != '' && $this->session->userdata('is_paid') === TRUE) {
                $this->load->model(DBNAME.'/catch_tb_model');

                if($this->catch_tb_model->get(array('c_user_id' => $user_id))->isSuccess()) {
                    
                    $update_params = array(
                        'c_tickerlist' => $c_tickerlist,
                        'c_type' => $c_type,
                        'c_size' => $c_size,
                        'c_sector' => $c_sector,
                        'c_industry' => $c_industry,
                        'c_mod_date' => date('Y-m-d H:i:s')
                    );

                    $this->catch_tb_model->doUpdate($user_id, $update_params);
                }
                else {

                    $params = array(
                        'c_user_id' => $user_id,
                        'c_tickerlist' => $c_tickerlist,
                        'c_type' => $c_type,
                        'c_size' => $c_size,
                        'c_sector' => $c_sector,
                        'c_sector' => $c_industry,
                        'c_reg_date' => date('Y-m-d H:i:s'),
                        'c_mod_date' => date('Y-m-d H:i:s')
                    );

                    $this->catch_tb_model->doInsert($params);
                }
            }
        }
        else {
            $success = FALSE;
            $result = array('success' => $success, 'msg' => '캐치 정보가 없습니다.');
            exit(json_encode($result));
            //$this->common->alert('캐치 정보가 없습니다.');
        }

        $success = TRUE;
        $result = array('success' => $success, 'catch_list' => $c_tickerlist, 'sector_list' => $c_sector);
        exit(json_encode($result));

        //$this->common->locationhref('/'.HN.'_stock/catch_info?catch_list='.$c_tickerlist.'&sector_list='.$c_sector);
        //exit;
    }

    public function winner() {
        $this->header_data['header_template'] = '2';
        $this->header_data['head_title'] = '초이스스탁';
        $this->header_data['head_url'] = '/';
        $data= array();
        $data['meta_title'] = '종목추천 - 초이스스탁US';

        $winner_file = 'winner.json';
        $file_path = str_replace('hoon','datahero',WEBDATA).'/'.$winner_file;

        if( is_file($file_path) ) {
             $winner_list = json_decode(file_get_contents($file_path), true);
        }

        $data['yoil'] = $this->yoil;
        $data['win_trend'] = $this->win_trend;
        $data['winner_list'] = $winner_list;
        $data['ticker_submenu'] = 'winner';        
        $data['is_event'] = $this->is_event;

        $this->_view('/stock/winner', $data);
    }

    protected $ticker_sector = array (
        '0'=>'Technology',                //아마존
        '1'=>'Industrials',                //보잉
        '2'=>'Consumer Cyclical',        //넷플릭스
        '3'=>'Consumer Defensive',        //월마트
        '4'=>'Consumer Defensive',        //코카콜라
        '5'=>'Healthcare',                //존슨앤존슨
        '6'=>'Energy',                    //엑슨모빌
        '7'=>'Real Estate',                //아메리카타워
        '8'=>'Utilities',                //넥스트에라
        '9'=>'Consumer Cyclical',        //테슬라
        '10'=>'Technology',                //마이크로소프트
        '11'=>'Consumer Defensive',        //펩시코
        '12'=>'Consumer Cyclical',        //스타벅스
        '13'=>'Communication Services',    //AT&T
        '14'=>'Financial Services',        //버크셔헤서웨이
        '15'=>'Basic Materials',        //뉴몬트
        '16'=>'Financial Services',        //비자
        '17'=>'Industrials',            //쓰리엠
        '18'=>'Financial Services',        //뱅크오브아메리카
        '19'=>'Energy',                    //쉐브론
        '20'=>'Technology',                //애플
        '21'=>'Communication Services',    //버라이즌
        '22'=>'Healthcare',                //유나이티드헬스그룹
        '23'=>'Basic Materials',        //포스코
        '24'=>'Energy',                    //로열더치셀
        '25'=>'Real Estate',            //리얼티인컴
        '26'=>'Healthcare',                //휴매나
        '27'=>'Utilities',                //듀크
        '28'=>'Real Estate',            //코스타
        '29'=>'Industrials'                //부킹홀딩스
    );

    protected $ticker_rep = array (
        '0'=>array('ticker' => 'AMZN', 'name' => '아마존', 'industry'=>'Internet Retail'),                    //아마존
        '1'=>array('ticker' => 'BA',   'name' => '보잉', 'industry'=>'Aerospace & Defense'),            //보잉
        '2'=>array('ticker' => 'NFLX', 'name' => '넷플릭스', 'industry'=>'Entertainment'),                //넷플릭스
        '3'=>array('ticker' => 'WMT',  'name' => '월마트', 'industry'=>'Discount Stores'),                    //월마트
        '4'=>array('ticker' => 'KO',   'name' => '코카콜라', 'industry'=>'Beverages - Non-Alcoholic1'),    //코카콜라
        '5'=>array('ticker' => 'JNJ',  'name' => '존슨앤존슨', 'industry'=>'Drug Manufacturers - General'),    //존슨앤존슨
        '6'=>array('ticker' => 'XOM',  'name' => '엑슨모빌', 'industry'=>'Oil & Gas Integrated'),            //엑슨모빌
        '7'=>array('ticker' => 'AMT',  'name' => '아메리카타워', 'industry'=>'REIT - Specialty'),            //아메리카타워
        '8'=>array('ticker' => 'NEE',  'name' => '넥스트에라', 'industry'=>'Utilities - Regulated Electric'),    //넥스트에라
        '9'=>array('ticker' => 'TSLA', 'name' => '테슬라', 'industry9'=>'Auto Manufacturers'),                    //테슬라
        '10'=>array('ticker' => 'MSFT','name' => '마이크로소프트', 'industry'=>'Software - Infrastructure'),    //마이크로소프트
        '11'=>array('ticker' => 'PEP', 'name' => '펩시코', 'industry'=>'Beverages - Non-Alcoholic'),            //펩시코
        '12'=>array('ticker' => 'SBUX','name' => '스타벅스', 'industry'=>'Restaurants'),                        //스타벅스
        '13'=>array('ticker' => 'T',   'name' => 'AT&T', 'industry'=>'Telecom Services'),                //AT&T
        '14'=>array('ticker' => 'BRK.B','name' => '버크셔헤서웨이', 'industry'=>'Insurance - Diversified'),        //버크셔헤서웨이
        '15'=>array('ticker' => 'NEM',  'name' => '뉴몬트', 'industry'=>'Gold'),                                //뉴몬트
        '16'=>array('ticker' => 'V',    'name' => '비자', 'industry'=>'Credit Services'),                    //비자
        '17'=>array('ticker' => 'MMM',  'name' => '쓰리엠', 'industry'=>'Specialty Industrial Machinery'),        //쓰리엠
        '18'=>array('ticker' => 'BAC',  'name' => '뱅크오브아메리카', 'industry'=>'Banks - Global'),            //뱅크오브아메리카
        '19'=>array('ticker' => 'CVX',  'name' => '쉐브론', 'industry'=>'Oil & Gas Integrated'),            //쉐브론
        '20'=>array('ticker' => 'AAPL', 'name' => '애플', 'industry'=>'Consumer Electronics'),            //애플
        '21'=>array('ticker' => 'VZ',   'name' => '버라이즌', 'industry'=>'Telecom Services'),        //버라이즌
        '22'=>array('ticker' => 'UNH',  'name' => '유나이티드헬스 그룹', 'industry'=>'Healthcare Plans'),    //유나이티드헬스그룹
        '23'=>array('ticker' => 'PKX',  'name' => '포스코', 'industry'=>'Steel'),                            //포스코
        '24'=>array('ticker' => 'RDS.A','name' => '로열더치셀', 'industry'=>'Oil & Gas Integrated'),        //로열더치셀
        '25'=>array('ticker' => 'O',    'name' => '리얼티인컴', 'industry'=>'REIT - Retail'),            //리얼티인컴
        '26'=>array('ticker' => 'HUM',  'name' => '휴매나', 'industry'=>'Healthcare Plans'),                //휴매나
        '27'=>array('ticker' => 'DUK',  'name' => '듀크', 'industry'=>'Utilities - Regulated Electric'),    //듀크
        '28'=>array('ticker' => 'CSGP', 'name' => '코스타', 'industry'=>'Real Estate Services'),            //코스타
        '29'=>array('ticker' => 'BKNG', 'name' => '부킹홀딩스', 'industry'=>'Travel Services')            //부킹홀딩스
    );
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */