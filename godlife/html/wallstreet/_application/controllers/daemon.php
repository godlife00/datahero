<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
crontab 등록할 메서드 :

# sf1, daily, ticker, mri 테이블 싱크 처리기  + 목표가 손절가 종료 관리
php index.php daemon data_sync

*/
class Daemon extends CI_Controller {
    public function __construct() {
        parent::__construct();

        ini_set('memory_limit', '2G');
        set_time_limit(3600);

        $this->load->model(array(
            'business/itoozaapi',
            DBNAME.'/ticker_tb_model',
            DBNAME.'/mri_tb_model',
            DBNAME.'/sf1_tb_model',
            DBNAME.'/daily_tb_model',
            DBNAME.'/recommend_tb_model',
            DBNAME.'/notify_tb_model',
            DBNAME.'/sep_tb_model',
            DBNAME.'/winner_tb_model',
            DBNAME.'/mriall_tb_model',
            DBNAME.'/sp500_tb_model',
        ));
    }

    // ticker_tb, mri_tb sync 처리기. 크론 배치 등록 필요.
    public function data_sync() {
        // 검색어 텍스트 수집

        //주가 기준일 가져오기(sep_tb=>sep_date(2020-03-04)
		/*
        $arr_sepdata = array();
        $arr_sepdata = $this->itoozaapi->getSepLastDate(1);
        $arr_sepdata = array_values($arr_sepdata);
        $chk_sep_date = $arr_sepdata[0]['sep_date'];
        $chk_sep_date = str_replace('-', '', $chk_sep_date);

        //ticker_tb tkr_lastpricedate(max) 가져오기
        $arr_tkrdata = array();
        $arr_tkrdata = $this->common->getDataByPK($this->ticker_tb_model->getList(array(), array('order_by' => 'tkr_lastpricedate desc', 'limit' => 1))->getData(), 'tkr_ticker');
        $arr_tkrdata = array_values($arr_tkrdata);
        $tkr_maxdate = $arr_tkrdata[0]['tkr_lastpricedate'];
        $tkr_maxdate = str_replace('-', '', $tkr_maxdate);

        if( $chk_sep_date <= $tkr_maxdate ) {
            echo '이미 업데이트 됐습니다. ['.date("Y-m-d H:i:s")."]\n";
            //exit;
        }
		*/
        //ticker price info 초기화
        $this->tickerprice_init();

        $name_kr_map = $this->itoozaapi->getTickerKoreanMap(1);
        //echo '<pre>';
        //print_r($name_kr_map);
        //exit;

        $info_map = $this->itoozaapi->getTickerInfoMap(1);

        // Ticker table sync
        $updated_at = date('Y-m-d H:i:s'); // 이번 SyncTime에 들어온 전 종목은 같은 수정시간으로 담고, 이 시간 업데이트에 안들어온 종목은 is_active = NO 되도록 함.

        $company_map = $this->itoozaapi->getCompanyList(1);

        $price_map = $this->itoozaapi->getTickerList(1);

        //echo '<pre>';
        //print_r($price_map);
        //exit;
        foreach($info_map as $ticker => $info) {
            // info
            $info['tkr_updated_at'] = $updated_at;
            $info['tkr_name_en'] = $info['tkr_name'];
            $info['tkr_is_active'] = 'YES';

            // name_kr_map
            echo '['.$ticker.'] name_kr_map==>'.$name_kr_map[$ticker]."\n";
            echo '['.$ticker.'] price_map==>'.$price_map[$ticker]['close']."\n";
            $info['tkr_name'] = isset($name_kr_map[$ticker]) ? $name_kr_map[$ticker] : $info['tkr_name'];;
            // price_map
            if(isset($price_map[$ticker])) {
                $info['tkr_close'] = $price_map[$ticker]['close'] ? str_replace(',','',$price_map[$ticker]['close']) : 0;
                //if(isset($ticker_close_map[$ticker])) {
                //    $info['tkr_close'] = $ticker_close_map[$ticker];
                //}
                $info['tkr_diff_str'] = $price_map[$ticker]['diff'] ? $price_map[$ticker]['diff'] : '-';
                $info['tkr_rate_str'] = $price_map[$ticker]['diff_rate'] ? $price_map[$ticker]['diff_rate'] : '-';
                $info['tkr_diff'] = $price_map[$ticker]['diff_num'] ? str_replace(',','',$price_map[$ticker]['diff_num']) : '0';
                $info['tkr_rate'] = $price_map[$ticker]['diff_rate_num'] ? str_replace(',','',$price_map[$ticker]['diff_rate_num']) : '0';
            } else {
                /*
                // 주말이면 diff가 - 로 모두 변하는거. 그냥 최종 종가 유지되게 수정
                if(isset($ticker_close_map[$ticker])) {
                    $info['tkr_close'] = $ticker_close_map[$ticker];
                }
                $info['tkr_diff_str'] = '-';
                $info['tkr_rate_str'] = '-';
                $info['tkr_diff'] = '0';
                $info['tkr_rate'] ='0';
                */
            }

            // company
            if(isset($company_map[$ticker])) {
                $info['tkr_description'] = $company_map[$ticker]['cp_description'] ? $company_map[$ticker]['cp_description'] : $company_map[$ticker]['cp_short_description'];
            } else {
                $info['tkr_description'] = '';
            }

            //print_r($info);

            if($this->ticker_tb_model->get(array('tkr_ticker' => $ticker))->isSuccess()) {
                // update
                $selected_row = $this->ticker_tb_model->getData();
                if( ! $this->ticker_tb_model->doUpdate($selected_row['tkr_id'], $info)->isSuccess()) {
                    echo $this->ticker_tb_model->getErrorMsg();
                    //echo '...UPDATE FAIL'."\n";
                } else {
                    //echo '...UPDATE DONE'."\n";
                }
            } else {
                // insert
                if( ! $this->ticker_tb_model->doInsert($info)->isSuccess()) {
                    echo $this->ticker_tb_model->getErrorMsg();
                    //echo '...INSERT FAIL'."\n";
                } else {
                    //echo '...INSERT DONE'."\n";
                }
            }

            // 이번에 안들어 온 종목 is_active = NO 처리 하기
            $active_set = array(
                'tkr_is_active' => 'NO'
            );
            $active_where_params = array();
            $active_where_params['!=']['tkr_updated_at'] = $updated_at;
            if( ! $this->ticker_tb_model->doMultiUpdate($active_set, $active_where_params)->isSuccess()) {
                // FAIL..
            }

        }

        unset($price_map);
        unset($company_map);


        //$this->sync_sf1();

        $this->sync_daily();

        // 추천 종목 종료일자와 성공여부 채우기(2020.09.18 end)
        //$this->fill_recommend_enddate();

        $this->delete_sync_query_cache();

        $this->make_ticker_info();
       
		$this->get_snp500();

        echo "\n".'['.date("Y-m-d H:i:s")."] data_sync success!!\n";
    }

    public function data_sync2() {

        //mri 테이블 sync
        $mri_map = $this->itoozaapi->getMRIList(1);

        $ticker_close_map = array();
        // mri data sync
        foreach($mri_map as $m) {
            if( ! $this->mri_tb_model->get($m['m_ticker'])->isSuccess()) {
                // insert
                $this->mri_tb_model->doInsert($m);
            } else {
                // update
                $this->mri_tb_model->doUpdate($m['m_ticker'], $m);
            }
            $ticker_close_map[$m['m_ticker']] = $m['m_close'];
        }
        unset($mri_map);

        $this->_make_valuation();

        $this->sync_sf1();

		$this->_make_spiderrank();

        $this->delete_sync2_query_cache();

        //$this->chart_cache();

        echo "\n".'['.date("Y-m-d H:i:s")."] data_sync2 success!!\n";
    }

    // sf1_tb Sync
    public function sync_sf1() {
        $updated_at = date('Y-m-d H:i:s');
        foreach(array('MRY','MRT','MRQ') as $dimension) {
            $sf1_list = $this->itoozaapi->getLastSF1List($dimension, array(), 1);
            foreach($sf1_list as $row) {
                $row['sf1_rndratio'] *= 100;
                $row['sf1_opmargin'] *= 100;
                if($this->sf1_tb_model->get(array('sf1_dimension' => $row['sf1_dimension'], 'sf1_ticker' => $row['sf1_ticker']))->isSuccess()) {
                    // update
                    $dbrow = $this->sf1_tb_model->getData();
                    $row['sf1_updated_at'] = $updated_at;
                    $this->sf1_tb_model->doUpdate($dbrow['sf1_id'], $row);
                } else {
                    // insert
                    $row['sf1_created_at'] = $updated_at;
                    $row['sf1_updated_at'] = $updated_at;
                    $this->sf1_tb_model->doInsert($row);
                }
            }
            unset($sf1_list);
        }
    }

    public function chart_cache() {

        echo "\n".'['.date("Y-m-d H:i:s")."] chart_cache start!!\n";
        $params = array();
        $params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
        //$params['>=']['dly_marketcap'] = '10000';
        //$params['>=']['m_biz_total_score'] = 80;

        $extra = array();
        $extra = array(
            'fields' => array('m_ticker'),
            'limit' => 10000,
        );
        $extra['slavedb'] = true;
        $valuation_list = array();
        $valuation_list = $this->mri_tb_model->getRecomStockList('total_score', $limit, $params, $extra);

        foreach($valuation_list as $val) {

            $strChartURL = 'https://hero.datahero.co.kr/search/finance_chart2/'. $val['m_ticker'].'?pn=kiwoom';
            $this->get_content($strChartURL);
            usleep(100000);
            $strChartURL = 'https://hero.datahero.co.kr/search/finance_chart2/'. $val['m_ticker'].'/MRT?pn=kiwoom';
            $this->get_content($strChartURL);
            usleep(100000);
            $strChartURL = 'https://hero.datahero.co.kr/search/finance_chart2/'. $val['m_ticker'].'/MRQ?pn=kiwoom';
            $this->get_content($strChartURL);
            usleep(100000);

            echo $val['m_ticker']."\n";
        }
        //print_r($fav_all);
        echo "\n".'['.date("Y-m-d H:i:s")."] tkr_cache end!!\n";
    }


    public function makeChartFile() {

        $params = array();
        $params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';

        $extra = array();
        $extra = array(
            'fields' => array('m_ticker'),
            'limit' => 10000,
        );
        $extra['slavedb'] = true;
        $valuation_list = array();
        $valuation_list = $this->mri_tb_model->getRecomStockList('total_score', $limit, $params, $extra);

//        $data['ticker'] = $this->ticker_info_map[$ticker];
//print_r($data['ticker']);
//print_r($valuation_list);
//        exit;
        $dimension = 'MRY';
        $cnt=0;
        foreach($valuation_list as $val) {
            if($cnt>10) break;
            $vchart_data = array();
            $this->itoozaapi->getCharmFinanceVChart($val['m_ticker'], 'MRY', '1');
            //usleep(100000);
            $this->itoozaapi->getCharmFinanceVChart($val['m_ticker'], 'MRT', '1');
            //usleep(100000);
            $this->itoozaapi->getCharmFinanceVChart($val['m_ticker'], 'MRQ', '1');

            echo $val['m_ticker']."\n";
            $cnt++;
        }
    }

    public function makeBaseData() {
        echo "\n".'['.date("Y-m-d H:i:s")."] makeBaseData start!!\n";
/*
        $params = array();
        $params['=']['tkr_is_active'] = 'YES';
        $extra = array();
        $extra = array(
            'fields' => array('tkr_ticker'),
        );
        $extra['slavedb'] = true;
*/
        $params = array();
        $params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';

        $extra = array();
        $extra = array(
            'fields' => array('m_ticker'),
            'limit' => 10000,
        );
        $extra['slavedb'] = true;
        $valuation_list = array();
        $valuation_list = $this->mri_tb_model->getRecomStockList('total_score', $limit, $params, $extra);

        foreach($valuation_list as $val) {
            $strTkrURL = 'https://hero.datahero.co.kr/search/invest_charm2/'. $val['m_ticker'].'?pn=kiwoom';
            $this->get_content($strTkrURL);
            //usleep(100000);
            echo $val['m_ticker']."\n";
        }
        echo "\n".'['.date("Y-m-d H:i:s")."] makeBaseData end!!\n";
    }

    public function call_homepage() {
        $strHomeURL = 'https://hero.datahero.co.kr?pn=kiwoom';
        $this->get_content($strHomeURL);
        echo "\n".'['.date("Y-m-d H:i:s")."] homepage call success!!\n";
    }

    public function tickerprice_init() {
        $strTkrPriceURL = 'http://us153.datahero.co.kr/stocks/makePriceMap';
        $result = $this->get_content($strTkrPriceURL);
        echo '<pre>';
        print_r($result);
    }

    // daily_tb Sync
    public function sync_daily() {
        // daily_tb sync
        $updated_at = date('Y-m-d H:i:s');
        $daily_list = $this->itoozaapi->getLastDailyList('', 1);

        print_r($daily_list);

        foreach($daily_list as $row) {
            if($this->daily_tb_model->get(array('dly_ticker' => $row['dly_ticker']))->isSuccess()) {
                // update
                $dbrow = $this->daily_tb_model->getData();
                $row['dly_updated_at'] = $updated_at;
                $this->daily_tb_model->doUpdate($dbrow['dly_id'], $row);
            } else {
                // insert
                $row['dly_created_at'] = $updated_at;
                $row['dly_updated_at'] = $updated_at;
                $this->daily_tb_model->doInsert($row);
            }
        }
        unset($daily_list);
    }

    public function push_test() {
        exit;
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['=']['rc_endtype'] = 'ING';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['join']['ticker_tb'] = 'rc_ticker = tkr_ticker';
        $params['raw'] = '(rc_goal_price >= tkr_close or rc_giveup_price >= tkr_close)';

        $extra = array();
        $extra['fields'] = array('rc_id', 'rc_ticker', 'rc_goal_price', 'rc_recom_price', 'rc_giveup_price', 'rc_succ_push', 'tkr_name', 'tkr_close', 'tkr_lastpricedate', 'IF(rc_goal_price <= tkr_close, "SUCCESS","FAIL") as endtype');
        $extra['order_by'] = '';

        $data = $this->recommend_tb_model->getList($params, $extra)->getData();

        //echo '<pre>'; print_r($data); exit;
        //echo $this->recommend_tb_model->getLastQuery()."\n";

        //$enddate = date('Y-m-d');
        //$result_log = array();
        //$cnt=0;
        foreach($data as $row) {
            //echo 'cnt===>'.$cnt.'<br>';
/*

            $update_params = array();
            $update_params['rc_endtype'] = $row['endtype'];
            //$update_params['rc_enddate'] = $enddate;
            $update_params['rc_enddate'] = $row['tkr_lastpricedate'];

            if( $update_params['rc_enddate'] == '' ) {
                $update_params['rc_enddate'] = $enddate;
            }

            $result_log[$row['rc_ticker']] = array(
                'recommend' => $row,
                'update_params' => $update_params,
                'is_success' => 'YES'
            );

            if( ! $this->recommend_tb_model->doUpdate($row['rc_id'], $update_params)->isSuccess()) {
                // 업데이트 실패 로그
                $result_log[$row['rc_ticker']]['is_success'] = 'NO!!!';
            }
*/
            //목표가 도달 시 알림 테이블에 insert
            if($row['rc_id'] == '2054') {
/*
                $suik = number_format((($row['tkr_close']/$row['rc_recom_price'])-1)*100,2);

                $nt_title = '\''.$row['tkr_name'].'('.$row['rc_ticker'].')\'가 목표가에 도달했습니다.(수익률 '.$suik.'%)';
                $nt_table = 'recommend_tb';
                $nt_pk = $row['rc_id'];
                $nt_url = '/stock/recommend_view/'.$nt_pk;
                $nt_is_active = 'YES';
                $nt_display_date = date('Y-m-d H:i:s');
                $nt_created_at = date('Y-m-d H:i:s');
                $nt_updated_at = date('Y-m-d H:i:s');

                $params = array(
                    'nt_title' => $nt_title,
                    'nt_table' => $nt_table,
                    'nt_pk' => $nt_pk,
                    'nt_url' => $nt_url,
                    'nt_is_active' => $nt_is_active,
                    'nt_display_date' => $nt_display_date,
                    'nt_created_at' => $nt_created_at,
                    'nt_updated_at' => $nt_updated_at,
                );

                $this->notify_tb_model->doInsert($params);
*/
                //종목추천push
                //echo CS_URL.'/payment/push_success/'.$row['rc_id'].'/'.$row['rc_ticker'];


                //exit;
                echo 'rc_succ_push==>['.$row['rc_succ_push'].']';
                if($row['rc_succ_push'] == 'N') {
                    echo 'rc_succ_push==>'.$row['rc_succ_push'];
                    $push_result = $this->common->restful_curl('https://capdev.choicestock.co.kr/payment/push_success/'.$row['rc_id'].'/'.$row['rc_ticker']);
                    $update_push = array();
                    $update_push['rc_succ_push'] = 'Y';
                    $this->recommend_tb_model->doUpdate($row['rc_id'], $update_push);

                    print_r($push_result);
                }
            }
            //$cnt++;
        }

        //$this->common->logWrite('recommend_enddate', print_r($result_log, true));
    }

    // 추천 종목 종료일자와 성공여부 채우기
    public function fill_recommend_enddate() {
		return;
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        $params['=']['rc_endtype'] = 'ING';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['join']['ticker_tb'] = 'rc_ticker = tkr_ticker';
        $params['raw'] = '(rc_goal_price <= tkr_close or rc_giveup_price >= tkr_close)';

        $extra = array();
        $extra['fields'] = array('rc_id', 'rc_ticker', 'rc_goal_price', 'rc_recom_price', 'rc_giveup_price', 'rc_succ_push', 'tkr_name', 'tkr_close', 'tkr_lastpricedate', 'IF(rc_goal_price <= tkr_close, "SUCCESS","FAIL") as endtype');
        $extra['order_by'] = '';

        $data = $this->recommend_tb_model->getList($params, $extra)->getData();
        //echo $this->recommend_tb_model->getLastQuery()."\n";

        $enddate = date('Y-m-d');
        $result_log = array();
		$push_list = array();
        foreach($data as $row) {
            $update_params = array();
            $update_params['rc_endtype'] = $row['endtype'];
            //$update_params['rc_enddate'] = $enddate;
            $update_params['rc_enddate'] = $row['tkr_lastpricedate'];

            if( $update_params['rc_enddate'] == '' ) {
                $update_params['rc_enddate'] = $enddate;
            }

            $result_log[$row['rc_ticker']] = array(
                'recommend' => $row,
                'update_params' => $update_params,
                'is_success' => 'YES'
            );

            if( ! $this->recommend_tb_model->doUpdate($row['rc_id'], $update_params)->isSuccess()) {
                // 업데이트 실패 로그
                $result_log[$row['rc_ticker']]['is_success'] = 'NO!!!';
            }

            //목표가 도달 시 알림 테이블에 insert
            if($row['endtype'] == 'SUCCESS') {

                $suik = number_format((($row['rc_goal_price']/$row['rc_recom_price'])-1)*100,2);

                $nt_title = '\''.$row['tkr_name'].'('.$row['rc_ticker'].')\'가 목표가에 도달했습니다.(수익률 '.$suik.'%)';
                $nt_table = 'recommend_tb';
                $nt_pk = $row['rc_id'];
                $nt_url = '/stock/recommend_view/'.$nt_pk;
                $nt_is_active = 'YES';
                $nt_display_date = date('Y-m-d H:i:s');
                $nt_created_at = date('Y-m-d H:i:s');
                $nt_updated_at = date('Y-m-d H:i:s');

                $params = array(
                    'nt_title' => $nt_title,
                    'nt_table' => $nt_table,
                    'nt_pk' => $nt_pk,
                    'nt_url' => $nt_url,
                    'nt_is_active' => $nt_is_active,
                    'nt_display_date' => $nt_display_date,
                    'nt_created_at' => $nt_created_at,
                    'nt_updated_at' => $nt_updated_at,
                );

                $this->notify_tb_model->doInsert($params);

                //종목추천push
                if($row['rc_succ_push'] == 'N' && $row['rc_view_srv'] != 'W' && !in_array($row['rc_ticker'], $push_list)) {
                    $push_result = $this->common->restful_curl(CS_URL.'/payment/push_success/'.$row['rc_id'].'/'.$row['rc_ticker']);
                    $update_push = array();
                    $update_push['rc_succ_push'] = 'Y';
                    $this->recommend_tb_model->doUpdate($row['rc_id'], $update_push);
					$push_list[] = $row['rc_ticker'];
                    print_r($push_result);
                }
            }
        }

        $this->common->logWrite('recommend_enddate', print_r($result_log, true));
    }

    public function delete_sync_query_cache(){
        $this->ticker_tb_model->deleteAllCache();
        $this->daily_tb_model->deleteAllCache();
        $this->recommend_tb_model->deleteAllCache();
    }

    public function delete_sync2_query_cache(){
        $this->sf1_tb_model->deleteAllCache();
        $this->mri_tb_model->deleteAllCache();
    }

    public function get_content($url) {
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)';
        $curlsession = curl_init();
        curl_setopt ($curlsession, CURLOPT_URL, $url);
        curl_setopt ($curlsession, CURLOPT_HEADER, 0);
        curl_setopt ($curlsession, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curlsession, CURLOPT_POST, 0);
        curl_setopt ($curlsession, CURLOPT_USERAGENT, $agent);
        curl_setopt ($curlsession, CURLOPT_REFERER, "");
        curl_setopt ($curlsession, CURLOPT_TIMEOUT, 3);
        $buffer = curl_exec ($curlsession);
        $cinfo = curl_getinfo($curlsession);
        curl_close($curlsession);
        if ($cinfo['http_code'] != 200)
        {
            return "";
        }
        return $buffer;
    }

    public function make_ticker_info() {

        $params = array();
        $params['=']['tkr_is_active'] = 'YES';
		//2020.08.26 수정 $params['not in']['tkr_category'] = array('Domestic Warrant', 'ADR Warrant', 'Canadian Warrant');
		$params['raw'] = 'tkr_category not like \'%Warrant%\'';

        $extra = array();
        $extra['order_by'] = '';
        $extra['slavedb'] = true;

        // 전종목 정보 채우기
        $ticker_info = $this->common->getDataByPK($this->ticker_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');

        if(count($ticker_info)>5000) {
            //$data = serialize($ticker_info);
            $data = json_encode($ticker_info);
            $ticker_info_file = 'ticker_info.json';
            $file_path = WEBDATA.'/'.$ticker_info_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $data);
            rename($file_backpath, $file_path);
        }
    }

    //public function make_win_file() {
    private function _make_win_file() {

		$params['=']['rc_is_active'] = 'YES';
        $params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['rc_view_srv'] = 'W';
        $params['=']['rc_portfolio'] = 'Y';
        //$params['in']['rc_endtype'] = array('ING', 'SUCCESS', 'SELL');

        $extra = array(
            'fields' => 'rc_ticker',
			'slavedb' => true,
        );

        $rc_list = $this->common->getDataByPK($this->recommend_tb_model->getList($params, $extra)->getData(), 'rc_ticker');
		$rc_list = array_keys($rc_list);
		
		$params = array();
        $params['>']['win_display_date'] = '20200729';
        $extra = array();
        $extra['fields'] = array('distinct(win_display_date)');
        $extra['limit'] = '10';
        $extra['order_by'] = 'win_display_date desc';

        $day_list = $this->winner_tb_model->getList($params, $extra)->getData();

		$winner_list = array();
        foreach($day_list as $day) {

            $params = array();
            $params['join']['ticker_tb'] = 'win_ticker = tkr_ticker';
	        $params['join']['daily_tb'] = 'win_ticker = dly_ticker';
            $params['=']['win_display_date'] = $day['win_display_date'];
            $params['=']['win_is_win'] = 'Y';
            $params['>']['win_biz_score'] = '65';
			$params['not in']['win_ticker'] = $rc_list;

            $extra = array();
            $extra['fields'] = array('winner_tb.*', 'tkr_name');
            $extra['order_by'] = 'dly_marketcap desc';
            //$extra['order_by'] = 'win_biz_score desc';

            $winner = $this->winner_tb_model->getList($params, $extra)->getData();

			/* 2020.09.29 정렬기준 시총으로 변경
			foreach ($winner as $win_key => $win_val) {
				$sort[$win_key] = round(($win_val['win_rc_price']/$win_val['win_close']-1)*100, 3);
			}
			array_multisort($sort, SORT_DESC, $winner);
			*/

            $winner_list[$day['win_display_date']] = $winner;
        }

        if(is_array($winner_list)) {
            //$data = serialize($ticker_info);
            $data = json_encode($winner_list);
            $winner_file = 'winner.json';
            $file_path = WEBDATA.'/'.$winner_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $data);
            rename($file_backpath, $file_path);
        }

		$params = array();
		$params['=']['win_display_date'] = $day_list[0]['win_display_date'];

		$extra = array();
		$extra['fields'] = array('win_ticker', 'win_short', 'win_medium', 'win_long');
		$extra['order_by'] = 'win_ticker';

        $winner_all = $this->common->getDataByPK($this->winner_tb_model->getList($params, $extra)->getData(), 'win_ticker');

        if(is_array($winner_all)) {
            //$data = serialize($ticker_info);
            $data = json_encode($winner_all);
            $winner_all_file = 'winner_all.json';
            $file_path = WEBDATA.'/'.$winner_all_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $data);
            rename($file_backpath, $file_path);
        }
    }

    public function make_win_data() {
	//10 13 * * 2-6 /usr/local/bin/php /home/datahero/html/wallstreet/index.php daemon make_win_data > /home/datahero/html/wallstreet/logdata/make_win_data.log

        if( ! $this->input->is_cli_request()) {
            die('cli only');
        }

        echo "\n".'['.date("Y-m-d H:i:s")."] make_win_data start!!\n";
	
		$closed_day = array('20200907', '20201126', '20201225', '20210101', '20210118', '20210215', '20210402', '20210531', '20210705', '20210906', '20211125', '20211224');

        $params = array();
        $params['=']['tkr_is_active'] = 'YES';
		//2020.08.26 수정 $params['not in']['tkr_category'] = array('Domestic Warrant', 'ADR Warrant', 'Canadian Warrant');
		$params['raw'] = 'tkr_category not like \'%Warrant%\'';

        $extra['order_by'] = 'tkr_ticker';
        $extra['slavedb'] = true;
        //echo '<pre>';
        // 전종목 정보 채우기
        $ticker_info = $this->common->getDataByPK($this->ticker_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
        foreach($ticker_info as $val) {

            $row = array();

            if($this->winner_tb_model->get(array('win_ticker' => $val['tkr_ticker'], 'win_date' => str_replace('-','',$val['tkr_lastpricedate'])))->isSuccess()) {
                // Update
                echo 'Update....['.$val['tkr_ticker']."]\n";
                $dbrow = $this->winner_tb_model->getData();

                $pk = $dbrow['win_id'];

                $row['win_close'] = $val['tkr_close'];

                $pre_params = array();
                $pre_params['=']['win_ticker'] = $val['tkr_ticker'];
                $pre_params['<']['win_date'] = $dbrow['win_date'];

                $pre_extra = array(
                    'order_by' => 'win_date desc',
                    'limit' => 1,
                    'slavedb' => true,
                );

                $pre_data = array_shift($this->winner_tb_model->getList($pre_params, $pre_extra)->getData());

                if(!is_array($pre_data)) {

                    $win_data['ticker'] = $val['tkr_ticker'];
                    $win_data['sep_date'] = $val['tkr_lastpricedate'];
                    $win_data['proc'] = 'update';

                    $win_close_list = $this->_get_win_predata($win_data);

                    $pre_data['win_close_5'] = $win_close_list['win_close_5'];
                    $pre_data['win_close_20'] = $win_close_list['win_close_20'];
                    $pre_data['win_close_60'] = $win_close_list['win_close_60'];
                    $pre_data['win_close_120'] = $win_close_list['win_close_120'];
                }

                $trend_data = array();
                $trend_data['db_data'] = $dbrow;
                $trend_data['term_data']['sep_close'] = $val['tkr_close'];
                $trend_data['pre_data'] = $pre_data;

                $trend_result = array();
                $trend_result = $this->_get_win_trend($trend_data);

                $row['win_short'] = $trend_result['win_short'];
                $row['win_medium'] = $trend_result['win_medium'];
                $row['win_long'] = $trend_result['win_long'];

                $winner_data=array();
                $winner_data['db_data'] = $dbrow;
                $winner_data['row_data'] = $row;

                $is_winner = $this->_get_is_winner($winner_data);

                $row['win_biz_score'] = $is_winner['win_biz_score'];
                $row['win_rc_price'] = $is_winner['win_rc_price'];
                $row['win_fairvalue'] = $is_winner['win_fairvalue'];
                $row['win_is_win'] = $is_winner['win_is_win'];

                if( ! $this->winner_tb_model->doUpdate($pk, $row)->isSuccess()) {
                    echo 'Fail!! ['.$val['tkr_ticker']."]\n";
                    sleep(1);
                    continue;
                }
                echo 'Success ['.$val['tkr_ticker']."]\n";
            }
            else {
                // Insert
                echo 'Insert....['.$val['tkr_ticker']."]\n";
                $row['win_ticker'] = $val['tkr_ticker'];
                $row['win_date'] = str_replace('-','',$val['tkr_lastpricedate']);
                $row['win_close'] = $val['tkr_close'];

                $win_data['ticker'] = $val['tkr_ticker'];
                $win_data['sep_date'] = $val['tkr_lastpricedate'];
                $win_data['proc'] = 'insert';

                $win_close_list = $this->_get_win_predata($win_data);

                $row['win_close_5'] = $win_close_list['win_close_5'];
                $row['win_close_20'] = $win_close_list['win_close_20'];
                $row['win_close_60'] = $win_close_list['win_close_60'];
                $row['win_close_120'] = $win_close_list['win_close_120'];

                $pre_params = array();
                $pre_params['=']['win_ticker'] = $val['tkr_ticker'];
                $pre_params['<']['win_date'] = str_replace('-','',$val['tkr_lastpricedate']);
                $pre_extra = array(
                    'order_by' => 'win_date desc',
                    'limit' => 1,
                    'slavedb' => true,

                );

                $pre_data = array_shift($this->winner_tb_model->getList($pre_params, $pre_extra)->getData());

                if(!is_array($pre_data)) {

                    $win_data['ticker'] = $val['tkr_ticker'];
                    $win_data['sep_date'] = $val['tkr_lastpricedate'];
                    $win_data['proc'] = 'update';

                    $win_close_list = $this->_get_win_predata($win_data);

                    $pre_data['win_close_5'] = $win_close_list['win_close_5'];
                    $pre_data['win_close_20'] = $win_close_list['win_close_20'];
                    $pre_data['win_close_60'] = $win_close_list['win_close_60'];
                    $pre_data['win_close_120'] = $win_close_list['win_close_120'];
                }

                $trend_data = array();
                $trend_data['db_data'] = $row;
                $trend_data['term_data']['sep_close'] = $val['tkr_close'];
                $trend_data['pre_data'] = $pre_data;

                $trend_result = array();
                $trend_result = $this->_get_win_trend($trend_data);

                $row['win_short'] = $trend_result['win_short'];
                $row['win_medium'] = $trend_result['win_medium'];
                $row['win_long'] = $trend_result['win_long'];

                $winner_data=array();
                $winner_data['db_data']['win_ticker'] = $row['win_ticker'];
                $winner_data['db_data']['win_date'] = $row['win_date'];
                $winner_data['db_data']['win_close'] = $row['win_close'];
                $winner_data['row_data'] = $row;

                $is_winner = $this->_get_is_winner($winner_data);

                $row['win_biz_score'] = $is_winner['win_biz_score'];
                $row['win_rc_price'] = $is_winner['win_rc_price'];
                $row['win_fairvalue'] = $is_winner['win_fairvalue'];
                $row['win_is_win'] = $is_winner['win_is_win'];

				//표기 날짜 처리
				if(date('w')=='6') {
					if(in_array(date('Ymd', time()+86400*2), $closed_day)) {
						$row['win_display_date'] = date('Ymd', time()+86400*3);
					}
					else {
						$row['win_display_date'] = date('Ymd', time()+86400*2);
					}
				}
				else {
					if(in_array(date('Ymd'),  $closed_day)) {
						$display_date = date('Ymd', time()+86400*1);
						$yoil = date("w",strtotime($display_date));
						if($yoil == '6') {
							$row['win_display_date'] = date('Ymd', time()+86400*3);
						}
						else {
							$row['win_display_date'] = date('Ymd', time()+86400*1);
						}
					}
					else {
						$row['win_display_date'] = date('Ymd');
					}
				}

                if( ! $this->winner_tb_model->doInsert($row)->isSuccess()) {
                    echo 'Fail! - ['.$val['tkr_ticker']."]\n";
                    echo $this->winner_tb_model->getErrorMsg()."\n";
                    sleep(1);
                    continue;
                }
                echo 'Success ['.$val['tkr_ticker']."]\n";
            }
        }

		$this->_make_win_file();

        echo "\n".'['.date("Y-m-d H:i:s")."] make_win_data end!!\n";
    }

    private function _get_is_winner($data=array()) {

        $result = array();

        //승부주계산
        $mri_params = array();
        $mri_params['=']['m_ticker'] = $data['db_data']['win_ticker'];
        $mri_params['=']['m_sep_date'] = $data['db_data']['win_date'];

        $mri_extra = array(
            'fields' => array('m_biz_total_score', 'm_v_fairvalue3', 'm_v_fairvalue2'),
            'quandldb' => true,
        );

        $mri_data = array_shift($this->mriall_tb_model->getList($mri_params, $mri_extra)->getData());

        $result['win_biz_score'] = $mri_data['m_biz_total_score'];
        $result['win_fairvalue'] = floor($mri_data['m_v_fairvalue3']);
        $result['win_rc_price'] = floor($mri_data['m_v_fairvalue2']);

        //1. 승부주 픽업 주식 조건 : 투자매력 점수 65점 and 현재가 < 고평가 and 단기,중기,장기 주가추세 모두 상향
		
		$win_rc_price  = $result['win_rc_price'] * 0.9;
		$is_win = false;
		if( ( $data['row_data']['win_short'] == 'S' && $data['row_data']['win_medium'] == 'S' ) || ( $data['row_data']['win_short'] == 'S' && $data['row_data']['win_long'] == 'S' ) || ( $data['row_data']['win_medium'] == 'S' && $data['row_data']['win_long'] == 'S' ) ) {
			$is_win = true;
		}
        //if( $result['win_biz_score'] >=65 && $data['db_data']['win_close'] < $win_rc_price && $data['row_data']['win_short'] == 'S' && $data['row_data']['win_medium'] == 'S' && $data['row_data']['win_long'] == 'S' ) {
        if( $result['win_biz_score'] >=65 && $data['db_data']['win_close'] < $win_rc_price && $is_win === true ) {
            $result['win_is_win'] = 'Y';
        }
        else {
            $result['win_is_win'] = '';
        }

        return $result;
    }

    private function _get_win_predata($data=array()) {

        $result = array();

        $sep_params = array();
        $sep_params['=']['sep_ticker'] = $data['ticker'];
        if($data['proc'] == 'insert') {
            $sep_params['<=']['sep_date'] = $data['sep_date'];
        }
        else {
            $sep_params['<']['sep_date'] = $data['sep_date'];
        }
        $sep_params['>=']['sep_date'] =  date('Y-m-d', strtotime('-7 months'));

        $sep_extra = array(
            'fields' => array('sep_date', 'sep_close'),
            'order_by' => 'sep_date desc',
            //'limit' => 121,
            'quandldb' => true,
        );

        $sep_list = $this->sep_tb_model->getList($sep_params, $sep_extra)->getData();

        $win_close_5 = 0;
        $win_close_20 = 0;
        $win_close_60 = 0;
        $win_close_120 = 0;
        $ma_close = 0;
        $cnt=0;
        foreach($sep_list as $sep) {
            if($cnt==5) {
                $win_close_5 = round($ma_close/5, 2);
            }
            else if($cnt==20) {
                $win_close_20 = round($ma_close/20, 2);
            }
            else if($cnt==60) {
                $win_close_60 = round($ma_close/60, 2);
            }
            else if($cnt==120) {
                $win_close_120 = round($ma_close/120, 2);
                break;
            }
            $ma_close += $sep['sep_close'];
            $cnt++;
        }

        if($win_close_5==0) {
            $win_close_5  = round($ma_close/$cnt, 2);
            $win_close_20 = $win_close_5;
            $win_close_60 = $win_close_5;
            $win_close_120 = $win_close_5;
        }
        else if($win_close_20==0) {
            $win_close_20  = round($ma_close/$cnt, 2);
            $win_close_60 = $win_close_20;
            $win_close_120 = $win_close_20;
        }
        else if($win_close_60==0) {
            $win_close_60  = round($ma_close/$cnt, 2);
            $win_close_120 = $win_close_60;
        }
        else if($win_close_120==0) {
            $win_close_120  = round($ma_close/$cnt, 2);
        }

        $result['win_close_5'] = $win_close_5;
        $result['win_close_20'] = $win_close_20;
        $result['win_close_60'] = $win_close_60;
        $result['win_close_120'] = $win_close_120;

        return $result;
    }
    private function _get_win_trend($data=array()) {

        $result = array();

        //단기
        if($data['term_data']['sep_close'] >= $data['db_data']['win_close_5'] && $data['db_data']['win_close_5'] > $data['db_data']['win_close_20'] && $data['db_data']['win_close_5'] > $data['pre_data']['win_close_5'] && $data['db_data']['win_close_20'] >  $data['pre_data']['win_close_20']) {
            $result['win_short'] = 'S';
        }
        else if( ($data['term_data']['sep_close'] < $data['db_data']['win_close_5'] && $data['db_data']['win_close_5'] <= $data['db_data']['win_close_20']) || ($data['term_data']['sep_close'] < $data['db_data']['win_close_5'] && $data['db_data']['win_close_5'] <= $data['pre_data']['win_close_5']) || ($data['term_data']['sep_close'] < $data['db_data']['win_close_5'] && $data['db_data']['win_close_20'] <= $data['pre_data']['win_close_20']) ) {
            $result['win_short'] = 'W';
        }
		else {
            $result['win_short'] = 'N';
		}
		/*
        else if($data['term_data']['sep_close'] < $data['db_data']['win_close_5'] && $data['db_data']['win_close_5'] > $data['db_data']['win_close_20'] && $data['db_data']['win_close_5'] > $data['pre_data']['win_close_5'] && $data['db_data']['win_close_20'] >  $data['pre_data']['win_close_20']) {
            $result['win_short'] = 'N';
        }
        else if($data['term_data']['sep_close'] >= $data['db_data']['win_close_5'] && $data['db_data']['win_close_5'] <= $data['db_data']['win_close_20'] && $data['db_data']['win_close_5'] > $data['pre_data']['win_close_5'] && $data['db_data']['win_close_20'] >  $data['pre_data']['win_close_20']) {
            $result['win_short'] = 'N';
        }
		*/

        //중기
        if($data['term_data']['sep_close'] >= $data['db_data']['win_close_20'] && $data['db_data']['win_close_20'] > $data['db_data']['win_close_60'] && $data['db_data']['win_close_20'] > $data['pre_data']['win_close_20'] && $data['db_data']['win_close_60'] >  $data['pre_data']['win_close_60']) {
            $result['win_medium'] = 'S';
        }
        else if( ($data['term_data']['sep_close'] < $data['db_data']['win_close_20'] && $data['db_data']['win_close_20'] <= $data['db_data']['win_close_60']) || ($data['term_data']['sep_close'] < $data['db_data']['win_close_20'] && $data['db_data']['win_close_20'] <= $data['pre_data']['win_close_20']) || ($data['term_data']['sep_close'] < $data['db_data']['win_close_20'] && $data['db_data']['win_close_60'] <= $data['pre_data']['win_close_60']) ) {
            $result['win_medium'] = 'W';
        }
		else {
            $result['win_medium'] = 'N';
		}

		/*
        else if($data['term_data']['sep_close'] < $data['db_data']['win_close_20'] && $data['db_data']['win_close_20'] > $data['db_data']['win_close_60'] && $data['db_data']['win_close_20'] > $data['pre_data']['win_close_20'] && $data['db_data']['win_close_60'] >  $data['pre_data']['win_close_60']) {
            $result['win_medium'] = 'N';
        }
        else if($data['term_data']['sep_close'] >= $data['db_data']['win_close_20'] && $data['db_data']['win_close_20'] <= $data['db_data']['win_close_60'] && $data['db_data']['win_close_20'] > $data['pre_data']['win_close_20'] && $data['db_data']['win_close_60'] >  $data['pre_data']['win_close_60']) {
            $result['win_medium'] = 'N';
        }
		*/

        //장기
        if($data['term_data']['sep_close'] >= $data['db_data']['win_close_60'] && $data['db_data']['win_close_60'] > $data['db_data']['win_close_120'] && $data['db_data']['win_close_60'] > $data['pre_data']['win_close_60'] && $data['db_data']['win_close_120'] >  $data['pre_data']['win_close_120']) {
            $result['win_long'] = 'S';
        }
        else if( ($data['term_data']['sep_close'] < $data['db_data']['win_close_60'] && $data['db_data']['win_close_60'] <= $data['db_data']['win_close_120']) || ($data['term_data']['sep_close'] < $data['db_data']['win_close_60'] && $data['db_data']['win_close_60'] <= $data['pre_data']['win_close_60']) || ($data['term_data']['sep_close'] < $data['db_data']['win_close_60'] && $data['db_data']['win_close_120'] <= $data['pre_data']['win_close_120']) || ($data['term_data']['sep_close'] >= $data['db_data']['win_close_60'] && $data['db_data']['win_close_60'] <= $data['db_data']['win_close_120'] && $data['db_data']['win_close_60'] > $data['pre_data']['win_close_60'] && $data['db_data']['win_close_120'] >  $data['pre_data']['win_close_120']) ) {
            $result['win_long'] = 'W';
        }
		else {
            $result['win_long'] = 'N';
		}
		/*
		else if($data['term_data']['sep_close'] < $data['db_data']['win_close_60'] && $data['db_data']['win_close_60'] > $data['db_data']['win_close_120'] && $data['db_data']['win_close_60'] > $data['pre_data']['win_close_60'] && $data['db_data']['win_close_120'] >  $data['pre_data']['win_close_120']) {
            $result['win_long'] = 'N';
        }
		*/
        return $result;
    }
    public function make_win_init() {
// /usr/local/bin/php /home/hoon/html/wallstreet/index.php daemon make_win_init
        if( ! $this->input->is_cli_request()) {
            die('cli only');
        }

        echo "\n".'['.date("Y-m-d H:i:s")."] make_win_init start!!\n";

        $params = array();
        $params['=']['tkr_is_active'] = 'YES';
        //$params['in']['tkr_ticker'] = array('ABMD');
        //$params['in']['tkr_ticker'] = array('AAPL', 'ABBV', 'AIG', 'APTV', 'INTC', 'JNJ', 'MED', 'PAYX', 'ACCD', 'BLCT');
		//2020.08.26 수정 $params['not in']['tkr_category'] = array('Domestic Warrant', 'ADR Warrant', 'Canadian Warrant');
		$params['raw'] = 'tkr_category not like \'%Warrant%\'';

		$extra['order_by'] = 'tkr_ticker';
        $extra['slavedb'] = true;
        //echo '<pre>';
        // 전종목 정보 채우기
        $ticker_info = $this->common->getDataByPK($this->ticker_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
        //print_r($ticker_info); exit;
        foreach($ticker_info as $val) {

            $term_params = array();
            $term_params['=']['sep_ticker'] = $val['tkr_ticker'];
            $term_params['>=']['sep_date'] =  date('Y-m-d', strtotime('-2 months'));

            $term_extra = array(
                'order_by' => 'sep_date desc',
                'limit' => 4,
                'quandldb' => true,

            );

            $term_list = $this->sep_tb_model->getList($term_params, $term_extra)->getData();

            foreach($term_list as $term) {
                $row = array();

                if($this->winner_tb_model->get(array('win_ticker' => $val['tkr_ticker'], 'win_date' => str_replace('-', '', $term['sep_date'])))->isSuccess()) {

                    // Update
                    //echo 'Update....['.$val['tkr_ticker']."]\n";
                    $dbrow = $this->winner_tb_model->getData();

                    $pk = $dbrow['win_id'];
                    //$row['win_close'] = $term['sep_close'];

                    $pre_params = array();
                    $pre_params['=']['win_ticker'] = $val['tkr_ticker'];
                    $pre_params['<']['win_date'] = str_replace('-','',$term['sep_date']);

                    $pre_extra = array(
                        'order_by' => 'win_date desc',
                        'limit' => 1,
                        'slavedb' => true,
                    );

                    $pre_data = array_shift($this->winner_tb_model->getList($pre_params, $pre_extra)->getData());

                    if(!is_array($pre_data)) {

                        $win_data['ticker'] = $val['tkr_ticker'];
                        $win_data['sep_date'] = $term['sep_date'];
                        $win_data['proc'] = 'update';

                        $win_close_list = $this->_get_win_predata($win_data);

                        $pre_data['win_close_5'] = $win_close_list['win_close_5'];
                        $pre_data['win_close_20'] = $win_close_list['win_close_20'];
                        $pre_data['win_close_60'] = $win_close_list['win_close_60'];
                        $pre_data['win_close_120'] = $win_close_list['win_close_120'];
                    }

                    $trend_data = array();
                    $trend_data['db_data'] = $dbrow;
                    $trend_data['term_data'] = $term;
                    $trend_data['pre_data'] = $pre_data;

                    $trend_result = array();
                    $trend_result = $this->_get_win_trend($trend_data);

                    $row['win_short'] = $trend_result['win_short'];
                    $row['win_medium'] = $trend_result['win_medium'];
                    $row['win_long'] = $trend_result['win_long'];


					$winner_data=array();
					$winner_data['db_data'] = $dbrow;
					$winner_data['row_data'] = $row;

					$is_winner = $this->_get_is_winner($winner_data);

					$row['win_biz_score'] = $is_winner['win_biz_score'];
					$row['win_rc_price'] = $is_winner['win_rc_price'];
					$row['win_fairvalue'] = $is_winner['win_fairvalue'];
					$row['win_is_win'] = $is_winner['win_is_win'];


                    if( ! $this->winner_tb_model->doUpdate($pk, $row)->isSuccess()) {
                        echo 'Fail!! ['.$val['tkr_ticker']."]\n";
                        sleep(1);
                        continue;
                    }
                    //echo 'Success ['.$val['tkr_ticker']."]\n";
                }
                else {
                    // Insert
                    //echo 'Insert....['.$val['tkr_ticker']."]\n";
                    $row['win_ticker'] = $val['tkr_ticker'];
                    $row['win_date'] = str_replace('-','',$term['sep_date']);
                    $row['win_close'] = $term['sep_close'];

                    $win_data['ticker'] = $val['tkr_ticker'];
                    $win_data['sep_date'] = $term['sep_date'];
                    $win_data['proc'] = 'insert';

                    $win_close_list = $this->_get_win_predata($win_data);

                    $row['win_close_5'] = $win_close_list['win_close_5'];
                    $row['win_close_20'] = $win_close_list['win_close_20'];
                    $row['win_close_60'] = $win_close_list['win_close_60'];
                    $row['win_close_120'] = $win_close_list['win_close_120'];

                    if( ! $this->winner_tb_model->doInsert($row)->isSuccess()) {
                        echo 'Fail! - ['.$val['tkr_ticker']."]\n";
                        echo $this->winner_tb_model->getErrorMsg()."\n";
                        sleep(1);
                        continue;
                    }
                    //echo 'Success ['.$val['tkr_ticker']."]\n";
                }
            }
        }
        echo "\n".'['.date("Y-m-d H:i:s")."] make_win_init end!!\n";
    }


    private function _get_pre_mriscore($ticker) {

		$pre_date = date('Ymd', time()-86400*30);
		$params = array();
        $params['=']['m_ticker'] = $ticker;
        $params['<']['m_sep_date'] = $pre_date;

        $extra = array(
			'fields' => 'm_biz_total_score',
            'order_by' => 'm_sep_date desc',
			'limit' => 1,
            'quandldb' => true,
            'cache_sec' => 3600
            //'cache_sec' => 3600*12
        );

        $result = $this->mriall_tb_model->getList($params, $extra)->getData();

		return $result;
    }

	private function _make_valuation() {
        if( ! $this->input->is_cli_request()) {
            die('cli only');
        }
		echo "\n".'['.date("Y-m-d H:i:s")."] _make_valuation start!!\n";

		$params = array();

		$extra = array(
			'fields' => array('m_ticker', 'm_biz_total_score', 'm_v_fairvalue3', 'm_biz_dividend_stars'),
			'order_by' => 'm_ticker desc',
			'slavedb' => true,
		);

		$mri_list = $this->mri_tb_model->getList($params, $extra)->getData();

		$valuation_list = array();

		foreach($mri_list as $key=>$val) {
			$pre_bizscore = array();
			$pre_bizscore = array_shift($this->_get_pre_mriscore($val['m_ticker']));
			$valuation_list[$val['m_ticker']]['m_biz_total_score'] = $val['m_biz_total_score'];
			$valuation_list[$val['m_ticker']]['pre_bizscore'] = $pre_bizscore['m_biz_total_score'];
			$valuation_list[$val['m_ticker']]['m_v_fairvalue3'] = $val['m_v_fairvalue3'];
			$valuation_list[$val['m_ticker']]['m_biz_dividend_stars'] = $val['m_biz_dividend_stars'];
		}
		//echo '<pre>'; print_r($valuation_list); exit;

        if(is_array($valuation_list)) {
			$valuation_list = json_encode($valuation_list);
            $valuation_file = 'valuation_list.json';
            $file_path = WEBDATA.'/json/'.$valuation_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $valuation_list);
            rename($file_backpath, $file_path);
        }

		echo "\n".'['.date("Y-m-d H:i:s")."] _make_valuation end!!\n";
	}

	public function make_part_banner() {
		
        if( ! $this->input->is_cli_request()) {
            die('cli only');
        }

		echo "\n".'['.date("Y-m-d H:i:s")."] make_part_banner start!!\n";

		$banner_list = array();
		// 종목추천(최신작성 1건)
		$params = array();
		$params['=']['rc_is_active'] = 'YES';
		$params['=']['rc_endtype'] = 'ING';
		$params['!=']['rc_view_srv'] = 'W';
		$params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['join']['ticker_tb'] = 'tkr_ticker = rc_ticker';
		$extra = array(
			'fields' =>  array('tkr_ticker', 'tkr_name', 'tkr_close', 'tkr_rate', 'rc_title', 'rc_subtitle', 'rc_goal_price', 'rc_recom_price', 'rc_adjust', 'rc_adjust_price'),
			'order_by' => 'rc_display_date DESC',
			'limit' => '1',
			'slavedb' => true,
		);

		$recommend = array_shift($this->recommend_tb_model->getList($params, $extra)->getData());
		if(in_array($recommend['rc_adjust'], array('U', 'D')) && $recommend['rc_adjust_price'] > 0) {
			$recommend['rc_goal_price'] = $recommend['rc_adjust_price'];
		}
		$recommend['ticker_revenue'] = round((($recommend['rc_goal_price']/$recommend['rc_recom_price'])-1)*100,2);	//예상수익률

		$banner_list[] = $recommend;

		$recommend = array();
		//최근 목표가 달성 종목
		$params = array();
		$params['=']['rc_is_active'] = 'YES';
		$params['=']['rc_endtype'] = 'SUCCESS';
		$params['!=']['rc_view_srv'] = 'W';
		$params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['join']['ticker_tb'] = 'tkr_ticker = rc_ticker';
		$extra = array(
			'fields' =>  array('tkr_ticker', 'tkr_name', 'tkr_close', 'tkr_rate', 'rc_id', 'rc_goal_price', 'rc_recom_price', 'rc_adjust', 'rc_adjust_price'),
			'order_by' => 'rc_enddate DESC',
			'limit' => '1',
			'slavedb' => true,
		);
		$recommend = array_shift($this->recommend_tb_model->getList($params, $extra)->getData());
		if(in_array($recommend['rc_adjust'], array('U', 'D')) && $recommend['rc_adjust_price'] > 0) {
			$recommend['rc_goal_price'] = $recommend['rc_adjust_price'];
		}
		$recommend['ticker_revenue'] = round((($recommend['rc_goal_price']/$recommend['rc_recom_price'])-1)*100,2);	//달성수익률

		$banner_list[] = $recommend;

		// 급등주 가져오기
		$params = array();
        $params['raw'] = array('tkr_lastpricedate = (select max(tkr_lastpricedate) from ticker_tb)');

		$extra = array(
			'fields' => array('tkr_ticker'),
			'order_by' => 'tkr_rate desc',
            'limit' => 1000
		);

		$extra['slavedb'] = true;
        $ticker_tb_data = array_keys($this->common->getDataByPK($this->ticker_tb_model->getList($params, $extra)->getData(), 'tkr_ticker'));

  		$snp500_ticker = array();
		
		
		$snp500_file = 'snp500.json';
		$file_path = str_replace('hoon','datahero',WEBDATA).'/json/'.$snp500_file;
		if( is_file($file_path) ) {
             $snp500_ticker = json_decode(file_get_contents($file_path), true);
		}

		$cnt=0;
  		$soaring_ticker = array();
		foreach($ticker_tb_data as $ticker) {
			if($cnt>100) break;
			if(in_array($ticker, $snp500_ticker)) {
				$soaring_ticker[] = $ticker;
				$cnt++;
		    }
		}
		
		//급등주
        if(is_array($soaring_ticker) && sizeof($soaring_ticker) > 0) {
            $params = array();
            $params['in']['m_ticker'] = $soaring_ticker;
            $params['>=']['m_biz_total_score'] = '70';
	        $params['join']['ticker_tb'] = 'm_ticker = tkr_ticker';
            $extra = array(
                'fields' => array('m_ticker', 'm_korname', 'm_biz_total_score', 'tkr_close', 'tkr_rate_str'),
                'order_by' => 'm_biz_total_score desc',
				'limit' => 1,
            );
			//$extra['cache_sec'] = 3600;
			$extra['slavedb'] = true;
            $soaring_stock_data = array_shift($this->mri_tb_model->getList($params, $extra)->getData());
        }

		if(is_array($soaring_stock_data) && sizeof($soaring_stock_data)>0) {
			$banner_list[] = $soaring_stock_data;
		}

        if(is_array($banner_list)) {
			$banner_list = json_encode($banner_list);
            $banner_file = 'partner_banner.json';
            $file_path = WEBDATA.'/json/'.$banner_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $banner_list);
            rename($file_backpath, $file_path);
        }

		echo "\n".'['.date("Y-m-d H:i:s")."] make_part_banner end!!\n";
	}

    public function get_snp500() {
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

        $snp500_list = array_keys($this->common->getDataByPK($this->sp500_tb_model->getList($snp_params, $snp_extra)->getData(), 'sp5_ticker'));

        if(is_array($snp500_list)) {
			$snp500_list = json_encode($snp500_list);
            $snp500_file = 'snp500.json';
            $file_path = WEBDATA.'/json/'.$snp500_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $snp500_list);
            rename($file_backpath, $file_path);
        }
    }

	private function _make_spiderrank() {
	//public function make_spiderrank() {
        if( ! $this->input->is_cli_request()) {
            die('cli only');
        }
		echo "\n".'['.date("Y-m-d H:i:s")."] _make_spiderrank start!!\n";

		$params = array();

		$extra = array(
			'order_by' => 'm_ticker asc',
			'slavedb' => true,
		);

		$mri_list = $this->mri_tb_model->getList($params, $extra)->getData();
		//echo '<pre>'; print_r($mri_list); exit;

		$mri_count = count($mri_list);
		
		echo '<br>'.$mri_count.'<br><br>';

		//$cnt=0;
		foreach($mri_list as $key=>$val) {
			//if($cnt>19) break;
			// 종합점수 순위($data['high_count'])
			$params = array();
			$params['>']['m_biz_total_score'] = ($val['m_biz_total_score'] =='') ? '0' : $val['m_biz_total_score'];
			$params['slavedb'] = true;
			$high_count = $this->mri_tb_model->getCount($params)->getData();
			$high_count += 1;
		
			// 종합점수 상위 퍼센트($data['total_rank_rate'])
			$top_rate = 0;
			$top_rate = round(($high_count) / $mri_count * 100, 2);
			if($top_rate>=1) $top_rate = floor($top_rate);
			
			//echo 'ticker==>'.$val['m_ticker'].'    high_count==>'.$high_count.'    top_rate==>'.$top_rate.'<br>';
			$update_params = array();
			$update_params['m_ranking'] = $high_count;
			$update_params['m_highrank'] = $top_rate;
			$this->mri_tb_model->doUpdate($val['m_ticker'], $update_params);
			//$cnt++;
		}

        echo "\n".'['.date("Y-m-d H:i:s")."] _make_spiderrank end!!\n";
	}
}