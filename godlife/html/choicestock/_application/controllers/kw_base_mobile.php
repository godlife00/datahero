<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
abstract class Kw_BaseMobile_Controller extends CI_Controller {
    // 컨트롤러 전체가 로그인 회원만 제공할 경우 컨트롤러에서 true로 설정하면 생성자에서 로긴 페이지로 redirect.
    // 특정 view 만 로긴 회원이어야 하는 컨트롤러는 해당 action method에서
    // if($this->check_signin() == false) {
    //        return;
    // }
    // 하여 사용하기.

    protected $signin_only = false;

    protected $header_data = array(
        'header_template' => '1',
        'header_type' => '', // 컨트롤러 메서드에서 재정의 가능. sch_heaher: 흰색 머리. 검색영역 숨김
        'head_title' => SERVICE_NAME,
        'head_url' => '',
        'show_alarm' => FALSE,

    );
    protected $partner_info = array();

    // 전종목 Ticker 정보. 그냥 들고 있자.
    protected $ticker_info_map = array();

    // 종목 검색시 클라이언트 브라우져 리소스가 감당토록 떨궈둘 검색 기업정보 저장소
    protected $search_ticker_list = array();

    // 인기주 TOP 50
    protected $popular_search_ticker = array();

    protected $is_mobile = false;
    protected $is_iphone = false;
    protected $is_open = false;
    protected $is_soft = false;

    function __construct() {
        parent::__construct();

        //if($this->signin_only == true && $this->check_signin() == false) {
        //    die();
        //}

        $this->_host_check();

        $this->load->model(array(
            DBNAME.'/ticker_tb_model',
            DBNAME.'/mri_tb_model',
            DBNAME.'/search_log_tb_model',
            DBNAME.'/notify_tb_model',
            DBNAME.'/sp500_tb_model',
            'business/itoozaapi',
        ));

		$this->load->helper('cookie');

        $ticker_info_file = 'ticker_info.json';

        //$file_path = WEBDATA.'/'.$ticker_info_file;
        $file_path = str_replace('hoon','datahero',WEBDATA).'/'.$ticker_info_file;

        if( is_file($file_path) ) {
            $this->ticker_info_map = json_decode(file_get_contents($file_path), true);
        }
        else {
            $params = array();
            $params['=']['tkr_is_active'] = 'YES';
            //2020.08.26 수정 $params['not in']['tkr_category'] = array('Domestic Warrant', 'ADR Warrant', 'Canadian Warrant');
            $params['raw'] = 'tkr_category not like \'%Warrant%\'';

            $extra = array();
            $extra['order_by'] = '';
            $extra['slavedb'] = true;
            $extra['cache_sec'] = 3600; // update 중에 떠져버리면 티커 코드가 6천개 중 5백개만 있을때 떠져버리곤 오래 지속된다. 캐싱 뺌

            // 전종목 정보 채우기
            $this->ticker_info_map = $this->common->getDataByPK($this->ticker_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
        }
        //echo sizeof($this->ticker_info_map);

        // 전종목 종목별 검색정보 만들기
        foreach($this->ticker_info_map as $tic => $ticker_row) {
            $this->search_ticker_list[$tic] = array(
                'ticker' => $tic,
                'name' => $ticker_row['tkr_name'].' '.$ticker_row['tkr_name_en']
            );
        }

        // 인기주 TOP 50
        /*****
        $params = array();
        $params['>=']['sl_created_at'] = date('Y-m-d H:i:s', time()-(86400*30)); // 30일전
        $extra = array(
            'fields' => array('sl_ticker', 'count(*) as cnt'),
            'group_by' => 'sl_ticker',
            'order_by' => 'cnt desc',
            'limit' => 50,
            'cache_sec' => 600, // 10분 캐싱
        );
        $this->popular_search_ticker = array_keys($this->common->getDataByPK($this->search_log_tb_model->getList($params, $extra)->getData(), 'sl_ticker'));
        *****/
        // 인기주 TOP 50(투자매력점수 80점 이상 & 시가총액 100억 달러 이상 종목 중 랜덤 3종목 노출)
        $add_params = array();
        $add_params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';
        $add_params['>=']['dly_marketcap'] = '10000';
        $add_params['>=']['m_biz_total_score'] = 80;

        $add_extra = array();
        $add_extra = array(
            'limit' => 200,
            'cache_sec' => 600*6*12, // 12시간 캐싱
        );
        $add_extra['slavedb'] = true;

        $fav_all = array();
        $fav_ticker = array();
        $fav_all = $this->mri_tb_model->getRecomStockList('total_score', $limit, $add_params, $add_extra);

        foreach($fav_all as $k => $v) {
            $fav_ticker[] = $v['m_ticker'];
        }
        $this->popular_search_ticker = $fav_ticker;

        // 알림
        $params = array();
        $params['=']['nt_is_active'] = 'YES';
        $params['!=']['nt_view_srv'] = 'W';
        $params['!=']['nt_table'] = 'master_tb';
        $params['<=']['nt_display_date'] = date('Y-m-d H:i:s');

        $extra = array();
        $extra['limit'] = 1;
        $extra['order_by'] = 'nt_display_date DESC';
        $extra['slavedb'] = true;
        $this->header_data['noti_list'] = $this->notify_tb_model->getList($params, $extra)->getData();
        //echo '<pre>'; print_r($this->header_data['noti_list']); 
        //$this->header_data['noti_table_map'] = $this->notify_tb_model->getTableMap();

        //장 시작시간 체크 
        $this->_open_check();

        $this->load->library('user_agent');

        if($this->agent->is_mobile()) {
            $this->is_mobile = TRUE;
        }

        if($this->agent->is_mobile('iphone')) {
            $this->is_iphone = TRUE;
        }

        if($this->is_soft === true) {
            $this->_soft_session();
        }
        else {
            $this->_partner_session();
        }
        //echo '<pre>'; print_r($this->session->all_userdata());
//https://kiwoom.choicestock.co.kr/kw_main?serviceYN=Y&customNum=SUY4UUs3M1JKTmhSTFREL0NVa1F2dz09
    }

    private function _host_check() {
        $host = $_SERVER['HTTP_HOST'];
        if(strstr($host, 'kiwoom2.choicestock.co.kr')) {
        //if(strstr($host, 'capdev.choicestock.co.kr')) {
            $this->is_soft = true;
        }
    }

    public function soft_check() {
        if(IS_REAL_SERVER && $this->is_soft === true) {
            $this->common->locationhref('/'.KW.'_stock/recommend');
            exit;
        }
    }

    private function _open_check() {

        $closed_day = array('20200907', '20201126', '20201225', '20210101', '20210118', '20210213', '20210402', '20210531', '20210705', '20210906', '20211125', '20211224');

        $yoil = date('w');
        $today = date('Ymd');
        $yesterday = date('Ymd',strtotime("-1 day", time()));

        if($yoil>0 && $yoil<7) {
            $check_time = intval(date('Hi'));
            if($yoil == 1) {
                if($check_time>=START_TIME && !in_array($today, $closed_day)) {
                    $this->is_open = true;
                }
            }
            else if($yoil == 6) {
                if($check_time<='920' && !in_array($yesterday, $closed_day)) {
                    $this->is_open = true;
                }
            }
            else {
                if(($check_time<='920' && !in_array($yesterday, $closed_day)) || ($check_time>=START_TIME && !in_array($today, $closed_day))) {
                    $this->is_open = true;
                }
            }
        }
    }

    public function member_check($user_id) {
		$this->load->model(DBNAME.'/member_tb_model');

		if( ! $this->member_tb_model->get($user_id)->isSuccess()) {

			$params = array(
				'm_id' => $user_id,
				'm_name' => 'kiwoom',
				'm_gender' => 'U',
				'm_path' => 'W',
				'm_reg_date' => date('Y-m-d H:i:s'),
				'm_mod_date' => date('Y-m-d H:i:s'),
				'm_login_date' => date('Y-m-d H:i:s'),
			);

			$this->member_tb_model->doInsert($params);
		}
		return;
	}

    private function _soft_session() {
        $pt_session = $this->input->get('pt');

        //if($pt_session === '') {
        //$this->is_soft = true;
        if($this->is_soft === true) {
            $pt_session = 'paid';
        }
        //$pt_session = 'paid';
        //}

        if($this->session->userdata('is_paid')==FALSE) {
            if($pt_session != '' && $pt_session == 'paid') {
                $this->session->set_userdata('is_paid', TRUE);
            }
        }
        else {
            if($pt_session != '' && $pt_session == 'logout') {
                $this->session->set_userdata('is_paid', FALSE);
            }
        }
        
        return;    
    }

    private function _partner_session() {
//서비스소개 : "https://kiwoom.choicestock.co.kr/kw_main/service"  + &serviceYN="Y 또는 N" &customNum="고객번호"
//홈 :  "https://kiwoom.choicestock.co.kr/kw_main" + &serviceYN="Y 또는 N" &customNum="고객번호"
//customNum : SUY4UUs3M1JKTmhSTFREL0NVa1F2dz09
        $pt_session = $this->input->get('serviceYN');
        $pt_usernum = $this->input->get('customNum');

        if($pt_session != '' && $pt_session == 'Y') {
            $this->session->set_userdata('is_paid', TRUE);
        }
        else {
            if($pt_session != '' && $pt_session == 'N') {
                //$this->session->set_userdata('is_paid', FALSE);
				//$this->session->unset_userdata('user_id');
				$this->session->sess_destroy();
            }
        }

        if($pt_usernum != '') {
			//암호화
			/*
			$pt_iv = str_repeat(chr(0), 16);
			$pt_key = "CH*ICE@DATAHERO"; //

			$pt_plain = "7615568";

			$pt_usernum = @openssl_encrypt($pt_plain, "AES-128-CBC", $pt_key, false, $pt_iv);
			echo 'pt_usernum=========>'.$pt_usernum; //PfoesMI+ST1OHqYyBIMhfA==


			//$pt_usernum = base64_decode($pt_usernum);
			$pt_usernum = openssl_decrypt($pt_usernum, "AES-128-CBC", $pt_key, false, $pt_iv);

			echo '<br><br>pt_usernum=========>'.$pt_usernum; exit; //7615568
			*/

			$pt_iv = str_repeat(chr(0), 16);
			$pt_key = "KIWO*OM@DATAHERO";

			$pt_usernum = base64_decode($pt_usernum);
			$pt_usernum = openssl_decrypt($pt_usernum, "AES-128-CBC", $pt_key, false, $pt_iv);

			//if($pt_usernum != '' && is_numeric($pt_usernum)) {
			if($pt_usernum != '') {
				$user_id = 'kw_'.$pt_usernum;
				$this->session->set_userdata('user_id', $user_id);
			}
        }
        
        return;    
    }

    protected function loginCheck(){
		$this->payCheck();
		/*
        return; //remove
        if(!$this->session->userdata('is_login')){
            $return_uri = urlencode($this->uri->uri_string());
            //redirect('/member/login?rd='.$return_uri);
            $this->common->locationhref('/member/login?ru='.$return_uri);
            exit;
        }
		*/
    }

    protected function payCheck(){
        if(!$this->session->userdata('is_paid')){
			echo '<script>';
			echo 'var agt = navigator.userAgent.toLowerCase();';
			echo 'if (agt.indexOf("iphone") != -1) {';
			echo '    window.location = "SuperStock://activityGoTo//sinchungpage";';
			echo '} else if (agt.indexOf("android") != -1) {';
			echo '    window.SuperStock.activityGoTo("sinchungpage");';
			echo '} else {';
			echo '    window.SuperStock.activityGoTo("sinchungpage");';
			echo '}';			
			echo '</script>';
            $this->common->locationhref('/kw_main');
            exit;
		}
    }

    protected function paramCheck(){
        return; //remove
        $strCookie = get_cookie('part_name');

        if($strCookie=='') {
            $this->common->locationhref('/');
            exit;
        }
    }

    protected function check_signin() {
        return; //remove
        $this->load->library('encrypt');
        $data = array();
        $data['sess_data'] = $this->session->all_userdata();
        if(
                isset($data['sess_data']['customer'])
                && isset($data['sess_data']['customer']['u_id'])
                && strlen($data['sess_data']['customer']['u_id']) > 0
          ){
            return true;
        }
        // logout user.
        if($this->input->is_ajax_request() === TRUE){
            echo json_encode(array('is_success' => false, 'code' => 'NotSignIn'));
            return FALSE;
        } else{
            $this->common->locationhref(HTTPS_SHOP_URL.'/account/login');
        }
        return false;
    }


    public function _view($view, $data=array(), $return_contents = false){

//if($_SERVER['REMOTE_ADDR'] == '1.214.48.194' ) {
//    echo '<br>name===>'.$this->partner_info['name'];
//    echo '<br>page===>'.$this->partner_info['page'];
    //echo '<br>key===>'.$this->partner_info['key'];
//}
        //echo '<pre>'; print_r($data);
        $data['sess_data'] = $this->session->all_userdata();

        $header = array();

        //심사용
        //$header['part_all'] = $this->partner_info['part_all'];

        //if($header['part_all']=='') {
        //    $header['part_all'] = get_cookie('part_all');
        //}

        $header['sess_data'] = $this->session->all_userdata();

        $this->load->library('Menu');
        $header['header_data'] = $this->header_data;
        $header['search_ticker_list'] = $this->search_ticker_list;
        /*
ogin..<pre>Array
(
    [header_template] => 1
    [header_type] =>
    [head_title] => 초이스스탁
    [head_url] => /
    [show_alarm] => 1
    [noti_list] => Array
        (
            [0] => Array
                (
                    [nt_id] => 228
                    [nt_title] => [미탐] 코카콜라 예상 EPS 0.44 (4월 4째주 주요 실적발표 기업)
                    [nt_content] =>
                    [nt_table] => explore_tb
                    [nt_pk] => 65
                    [nt_url] => /stock/research_view/65
                    [nt_is_active] => YES
                    [nt_display_date] => 2020-04-20 17:00:00
                    [nt_created_at] => 2020-04-20 13:32:07
                    [nt_updated_at] => 2020-04-21 14:46:45
                )

        )

    [noti_table_map] => Array
        (
            [recommend_tb] => 종목추천
            [analysis_tb] => 종목분석
            [explore_tb] => 미국주식 탐구생활
            [master_tb] => 대가의 종목
            [custom] => 알림
        )

        echo '<pre>'; print_r($header['header_data']);
        */

        $container_class = '';
        $show_footer = TRUE;
        $show_menu = TRUE;
        $show_type = '';
        $is_main = FALSE;
        $is_searching = FALSE;
        $searching_title = '';
        $is_sicon = TRUE;
        $is_headtop = FALSE;
        $is_morning = FALSE;
        $headtop_title = '';
        $back_url = '';
        $soft_menu = '';
        $soft_searching = FALSE;

        //$is_vt_banner = FALSE;
        $request_uri = $this->uri->segment(1).'/'.$this->uri->segment(2);

        if( ! isset($this->header_data['container_class'])) { // controller 메서드에서 $this->header_data['container_class'] = 'sub_search'; 같은 set 없을시만.
            //echo 'm='.$request_uri;
            $header['header_class'] = '';
            switch($request_uri) {
                case '/':
                case KW.'_main/':
                    $header['header_class'] = ' h_sub';
                    $is_main = TRUE;
                    //$is_vt_banner = TRUE;
                    //$show_footer = FALSE;
                    break;
/* remove
                case 'member/terms':
                case 'member/policy':
                    $show_type = '3';
                    $container_class = 'sub_terms';
                    $show_menu = false;
                    break;
                case 'member/notice':
                    $container_class = 'sub_alarm';
                    break;
remove */
                case KW.'_main/myticker':
                    $show_menu = false;
                case KW.'_main/onestop':
                case KW.'_main/search':
                    $container_class = 'sub_search schfix_inc';
                    $show_type = '2';
                    $is_searching = true;
                    $searching_title = '진단';
                    break;
                case KW.'_stock/recommend':
                    $header['header_class'] = ' h_sub';
                    $container_class = 'sub_recom sub_portfolio schfix_inc';
                    $show_type = '2';
                    $is_searching = ($this->is_soft===true) ? false:true;    //is_soft
                    $searching_title = '추천';
                    $soft_menu = 'recommend';
                    //$is_vt_banner = TRUE;
                    break;
                case KW.'_stock/recommend_view':
                    $container_class = 'sub_recom';
                    $show_type = '2';
                    $is_headtop = true;
                    $headtop_title = '종목추천';
                    $soft_menu = 'recommend';
                    $is_sicon = false;
                    break;
                case KW.'_stock/analysis':
                    $header['header_class'] = ' h_sub';
                    $container_class = 'sub_analysis';
                    $show_type = '2';
                    //$is_vt_banner = TRUE;
                    break;
                case KW.'_stock/analysis_view':
                    $container_class = 'sub_analysis';
                    $show_type = '2';
                    break;
                case KW.'_stock/winner':
                    $header['header_class'] = ' h_sub';
                    $container_class = 'sub_game schfix_inc';
                    $is_searching = ($this->is_soft===true) ? false:true;    //is_soft
                    $searching_title = '추천';
                    $soft_menu = 'recommend';
                    $show_type = '2';
                    break;
                case KW.'_stock/alarm':
                    $container_class = 'sub_alarm';
                    $show_menu = false;
                    $show_type = '3';
                    $is_headtop = true;
                    $headtop_title = '알림';
                    $is_morning = TRUE;
                    $is_sicon = false;
                    break;
                case KW.'_stock/recipe_intro':
                    $header['header_class'] = ' h_sub';
                    $container_class = 'sub_recipe schfix_inc';
                    $show_type = '2';
                    $is_searching = ($this->is_soft===true) ? false:true;    //is_soft
                    $soft_menu = 'attractive';
                    $searching_title = '발굴';
                    break;
                case KW.'_stock/recipe':
                    $container_class = 'sub_recipe schfix_inc';
                    $show_type = '2';
                    $is_headtop = true;
                    $is_sicon = ($this->is_soft===true) ? false:true;    //is_soft
                    $headtop_title = '투자레시피';
                    $soft_menu = 'attractive';
                    break;
                case KW.'_stock/vod':
                case KW.'_stock/research':
                    $header['header_class'] = ' h_sub';
                    $container_class = 'sub_research schfix_inc';
                    $show_type = '2';
                    $is_searching = ($this->is_soft===true) ? false:true;    //is_soft
                    $searching_title = '발굴';
                    $soft_menu = 'attractive';
                    break;
                case KW.'_stock/vod_view':
                    $container_class = 'sub_research';
                    $show_type = '2';
                    $is_headtop = true;
                    $headtop_title = '동영상';
                    $is_sicon = false;
                    $back_url = 'vod';
                    $soft_menu = 'attractive';
                    break;
                case KW.'_stock/research_view':
                    $container_class = 'sub_research';
                    $show_type = '2';
                    $is_headtop = true;
                    $headtop_title = '탐구생활';
                    $is_sicon = false;
                    $soft_menu = 'attractive';
                    break;
                case KW.'_stock/morning_view':
                case KW.'_stock/morning':
                    $container_class = 'sub_briefing';
                    $show_type = '2';
                    $is_headtop = true;
                    $is_sicon = false;
                    $headtop_title = '모닝브리핑';
                    break;
                case KW.'_stock/catch_info':
                    $header['header_class'] = ' h_sub';
                    $container_class = 'sub_catch schfix_inc';
                    $show_type = '2';
                    $is_searching = true;
                    $searching_title = '발굴';
                    break;
                case KW.'_search/primary_ticker':
                case KW.'_search/invest_charm':
                case KW.'_search/summary':
                case KW.'_search/finance_chart':
                case KW.'_search/invest':
                case KW.'_search/financials':
                case KW.'_search/alloca':
                case KW.'_search/competitor':
                    $show_type = '2';
                    $container_class = 'sub_search';
                    $is_headtop = true;
                    $is_sicon = true;
                    $soft_searching = false;
                    $headtop_title = '종목검색';
                    //$back_url = 'recommend';
                    $soft_menu = 'recommend';
                    break;
                case KW.'_attractiveness/attractive':
                    $header['header_class'] = ' h_sub';
                    $show_type = '2';
                    $container_class = 'sub_attract  schfix_inc';
                    $is_searching = ($this->is_soft===true) ? false:true;    //is_soft
                    $searching_title = '발굴';
                    $soft_menu = 'attractive';
                    break;
                case KW.'_main/menu':
                    $show_type = '3';
                    $container_class = 'sub_menu';
                    $header['header_class'] = ' h_sub m_sub';
                    break;
                case KW.'_main/service':
                case KW.'_main/service_guide':
                case KW.'_main/service_prm':
                    $show_type = '3';
                    //$is_vt_banner = TRUE;
                    $container_class = 'sub_service';
                    $is_headtop = true;
                    $headtop_title = '서비스소개';
                    $is_sicon = false;
                    $back_url = 'none';
                    $show_footer = FALSE;
                    break;
//remove        case 'payment/pay_free':
                case KW.'_main/service_free':
                    $show_type = '3';
                    $container_class = 'sub_payment sub_freeguide';
                    break;
            }
        } else {
            $container_class = $this->header_data['container_class'];
        }
        $header['container_class'] = $container_class;

        shuffle($this->popular_search_ticker);
        $top_popular_ticker = array();
        foreach(array_slice($this->popular_search_ticker, 0 ,3) as $val) {
            $top_popular_ticker[] = array('ticker' => $val, 'name' => $this->ticker_info_map[$val]['tkr_name']);
        }
        $header['top_popular_ticker'] = $top_popular_ticker;

        if($request_uri=='payment/card_regist'||$request_uri=='member/menu') {
            $header['header_contents_html'] = '';
        }
        else {
            $header['header_contents_html'] = '';
            //$header['header_contents_html'] = $this->menu->get_header_contents($this->header_data['header_template'], $header);
        }
        if(isset($data['footer_type']) && $data['footer_type'] !='')
        $show_type = $data['footer_type'];

        $footer = array();
        $footer['show_footer'] = $show_footer;
        $footer['type'] = $show_type;
        $footer['is_main'] = $is_main;
        //$footer['is_vt_banner'] = $is_vt_banner;
        //$footer['footer_notice'] = $data['footer_notice'];

        /*
        $part_name = get_cookie('part_name');

        if($part_name=='') {
            $part_name = $this->partner_info['part_name'];
        }

        $header['part_name'] = $part_name;
        */
        $header['header_template'] = $this->header_data['header_template'];

        if(isset($data['meta_title']) && $data['meta_title'] != '') {
            $header['meta_title'] = $data['meta_title'];
        }
        else {
            $header['meta_title'] = '투자를 쉽고 편리하게 - 미주미초이스';
        }

        
        $header['show_menu'] = $show_menu;
        $header['is_main'] = $is_main;
        $header['is_soft'] = $this->is_soft;
        $header['soft_menu'] = $soft_menu;

        $check_alarm = true;
        $check_time = date("YmdHis",time()-60*60*36);
        $noti_time = date('YmdHis', strtotime($this->header_data['noti_list'][0]['nt_display_date']));

        if(sizeof($this->header_data['noti_list']) > 0 && $check_time <= $noti_time) {
            $check_alarm = false;
        }
        $header['check_alarm'] = $check_alarm;
        $header['is_searching'] = $is_searching;
        $header['searching_title'] = $searching_title;
        $header['is_headtop'] = $is_headtop;
        $header['headtop_title'] = $headtop_title;
        $header['is_sicon'] = $is_sicon;
        $header['is_morning'] = $is_morning ;
        $header['back_url'] = $back_url;
        $header['soft_searching'] = $soft_searching;

        if( ! $return_contents) {

            $this->load->view('/'.KW.'/inc/header.php', $header);
            $this->load->view('/'.KW.'/'.$view.'.php', $data);
            $this->load->view('/'.KW.'/inc/footer.php', $footer);
        } else {

            //if($data['chkUri']!='kiwoom') {
            $result  = $this->load->view('/'.KW.'/inc/header.php', $header, true)."\n";
            $result .= $this->load->view('/'.KW.'/'.$view.'.php', $data, true)."\n";
            //}
            $result .= $this->load->view('/'.KW.'/inc/footer.php', $footer, true)."\n";
            return $result;
        }
    }

    /*
    저평가 : 현재주가 < [fairvalue3]*0.925
    적정가 : [fairvalue3]*0.925 <= 현재주가 <= [fairvalue3]*1.088
    고평가 : 현재주가 > [fairvalue3]*1.088
    */

    public function cal_valuation($fairvalue, $close) {

        if($fairvalue <= 0) return 'N/A';
        else {
            if( $close > $fairvalue * 1.088 ) {
                $star = 5;    //고평가
            }
            else if( $close >= $fairvalue * 0.925 ) {
                $star = 3;
            }
            else {
                $star = 1;    //저평가
            }

            return $star;
        }
    }

    protected $spider_comment = array (
        'dividend_5'    => '배당주 투자 대상으로 최고의 매력을 가지고 있습니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다.',
        'dividend_4'    => '배당주 투자 대상으로 매력이 있습니다. 꾸준히 배당금을 지급하고 있어 향후에도 안정적인 배당금 수입이 예상됩니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다. ',
        'dividend_3'    => '배당주 투자 매력은 보통입니다. 배당금을 지급하지만 상대적으로 배당 투자 매력은 떨어지는 편입니다. 배당 투자 목적으로만 주식을 매수하기에는 매력이 없습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다. ',
        'dividend_2'    => '배당주 투자 매력은 낮은 편입니다. 현재 배당금을 지급할 여력은 있으나, 배당에 적극적이지 않고 미래에도 꾸준히 안정적인 배당을 기대하는 건 현재로선 어려워 보입니다. 순이익 증가로 주가 상승을 기대할 수 있는 요인이 있는지 함께 검토하는 것이 좋습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다.',
        'dividend_1'    => '배당주 투자 대상으로는 매력이 없습니다. 과거 배당금 지급이 제한적이고, 미래 배당 지급 여력도 낮은 편입니다. 순이익 증가로 주가 상승을 기대할 수 있는 요인이 있는지 함께 검토하는 것이 좋습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다. ',
        'moat_5'    => '워렌 버핏이 강조하는 경제적해자가 가장 넓은 기업입니다. 넓은 경제적해자를 가진 기업은 현재의 고수익을 오랫동안 지킬 수 있는 사업독점력을 갖춘 회사를 말합니다. 향후에도 장기간 이익을 훼손하지 않고 고수익을 유지할 수 있어 꾸준한 주가 상승의 원동력이 됩니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ',
        'moat_4'    => '워렌 버핏이 강조하는 경제적해자가 넓은 기업입니다. 오랜기간 안정적으로 고수익을 달성한 기업으로 미래에도 현재의 수익을 유지할 가능성이 높은 기업입니다. 별점이 5점에서 4점으로 낮아졌다면 사업독점력이 약간 훼손됐다는 것을 의미하며, 0 ~ 3점에서 4점으로 올랐으면 사업독점력이 강화돼 긍정적인 신호로 해석합니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다.',
        'moat_3'    => '워렌 버핏이 강조하는 경제적해자가 보통인 기업입니다. 장기간 산업 평균 수준의 수익성을 유지해온 기업입니다. 별점이 작년 대비 낮아지는 추세라면 산업내 경쟁력이 약화되고 있다는 것을 말합니다. 이는 장기적으로 주가 상승의 걸림돌이 되기 때문에 꼼꼼한 점검이 필요합니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ',
        'moat_2'    => '워렌 버핏이 강조하는 경제적해자가 좁은 기업입니다. 제품과 서비스의 경쟁력을 높이지 못하면 향후 수익성이 훼손될 가능성이 있습니다. 다만, 4차 산업 등 신규 산업에 속한 기업들은 업력이 상대적으로 짧아 현재 경쟁력 보다 약간 낮은 점수를 받을 수도 있습니다. 따라서 신규 산업에 속한 기업은 사업독점력외에 수익성장성, 현금창출력 등의 투자지표도 함께 참고해서 투자의사를 결정하는 것이 좋습니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ',
        'moat_1'    => '워렌 버핏이 강조하는 경제적해자가 거의 없는 기업입니다. 경기 변동에 따라 수익성이 크게 움직일 가능성이 높습니다. 호황에는 수익을 내더라도, 불황에는 수익이 크게 훼손될 수 있는 만큼 투자할때 더욱 꼼꼼히 살펴야 합니다. 상장 후 10년 이내의 기업이 아니라면 장기적으로 낮은 별점을 받은 기업은 투자 대상에서 제외하는 것이 좋습니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ',
        'safety_5'    => '재무안전성이 매우 높은 기업입니다. 단기적으로 부도 위험이 없고, 불황이 닥쳐도 충분히 견딜 수 있는 우량한 재무구조를 갖춘 회사입니다. 재무안전성은 잃지 않는 투자를 위해 꼭 확인해야 할 항목입니다. 재무안전성이 높은 기업은  부도위험이 낮고, 불황이 닥쳐도 충분히 견딜수 있습니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다.',
        'safety_4'    => '재무안전성이 높은 기업입니다. 단기적으로 부도 위험이 낮은 편이며, 일반적인 기업에 비해 안전한 재무구조를 갖추고 있습니다. 미국 기업은 한국 기업에 비해 일반적으로 부채비율이 높기 때문에 이자보상배수, 현금흐름 등 여러가지 재무안전성 지표를 함께 살펴보는 것이 좋습니다.  ',
        'safety_3'    => '재무안전성이 보통인 기업입니다. 상장기업의 평균적인 재무안전성을 갖춘 회사입니다. 재무위험이 높지는 않지만 순이익이 크게 줄거나 현금흐름이 나빠지면 부정적인 신호로 해석합니다.',
        'safety_2'    => '재무안전성이 낮은 기업입니다. 순이익이 줄거나 현금흐름이 더 악화되면 단기적인 자금 부족이 나타날 수 있습니다. 재무안전성 별점이 낮아지고 있다면 재무안전성이 나빠지고 있는 신호입니다. 이 경우 투자 후보 대상 기업에서 제외하는 것이 좋습니다. ',
        'safety_1'    => '재무안전성이 매우 낮은 기업입니다. 잃지 않는 투자를 지향하는 투자자라면 투자 대상에서 제외하는 것이 좋습니다. 유상증자나 인수합병(M&A) 뉴스 등을 통해 주가가 급등하기도 하지만 행운에 기댄 투자보다는 합리적인 투자가 필요합니다.  ',
        'growth_5'    => '수익성장성이 매우 높은 기업입니다. 순이익이 전년 동기 대비 고성장(25% 이상)을 기록했고, 최근 5~6년간 연평균 15% 이상의 꾸준한 성장을 기록한 최고의 성장주입니다. 고 성장주를 찾는 투자자에게 가장 매력적인 기업입니다. 별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.',
        'growth_4'    => '수익성장성이 높은 기업입니다. 순이익이 전년 동기 대비 15% 이상 성장했고, 이 성장률을 향후 4년 간 유지하면 순이익을 현재 대비 약 2배 이상 늘릴 수 있는 성장기업입니다. 성장주를 찾는 투자자라면 관심을 가져야 할 기업입니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.',
        'growth_3'    => '수익성장성이 보통인 기업입니다. 순이익이 전년 동기 대비 7% 이상 성장했지만 다른 고 성장기업에 비하면 성장률이 낮은 편입니다. 매출과 이익이 다시 성장할 수 있는 제품(서비스) 라인업이 있는지 확인 후 투자하는 것이 좋습니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.',
        'growth_2'    => '수익성장성이 낮은 기업입니다. 순이익이 전년 동기 대비 증가했지만 상승률이 7% 이하로 성장주를 찾는 투자자에겐 매력이 낮은 회사입니다. 순이익 성장률 둔화가 이번 분기에 일시적인 상황인지 제품(서비스) 경쟁력 약화로 순이익 성장이 한계에 달한 것인지를 파악하는 게 중요합니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.',
        'growth_1'    => '순이익이 전년 동기 대비 감소한 회사입니다. 순이익의 성장은 주가 상승의 원동력입니다. 이익 성장이 없는 주가 상승은 모래 위에 성을 쌓는 것과 같아 언젠가는 무너지고 맙니다. 순이익이 성장하는 다른 기업을 찾는 것이 좋겠습니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.',
        'cashflow_5'    => '현금창출력이 가장 우수한 기업입니다. 현금은 기업의 혈액과도 같은 역할을 합니다. 현금은 기업의 재무 구조를 개선시키고, 성장을 위한 투자 자금으로 사용합니다. 또한, 주주를 위한 배당, 자사주 매입에도 사용할 수 있기 때문에 주주가치를 높이는데도 활용할 수 있습니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업이 받았습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.',
        'cashflow_4'    => '현금창출력이 우수한 기업입니다. 기업의 영업, 투자, 재무활동을 위한 현금흐름에 거의 문제가 없습니다. 영업활동 현금흐름이 꾸준한 플러스(+)를 유지하고 있어, 이를 바탕으로 성장을 위한 투자나 배당 등을 꾸준히 지급하고 있습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.',
        'cashflow_3'    => '현금창출력이 보통인 기업입니다. 영업활동 현금흐름이 플러스(+)를 기록하고 있어, 일반적으로 현금흐름에 문제는 없는 편입니다. 다만, 순이익이 크게 줄거나 적자로 전환할 경우 단기적인 현금흐름이 나빠질 수 있으니 부채비율, 이자보상배율 등 재무안전성도 함께 확인하는 것이 좋습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.',
        'cashflow_2'    => '현금창출력이 낮은 기업입니다. 회사의 영업활동 현금흐름이 향후 플러스(+)를 유지할 수 있는 지 확인이 필요합니다. 영업활동 현금흐름의 출발점은 순이익입니다. 순이익이 적자를 기록하면 영업활동 현금흐름은 일반적으로 마이너스(-)를 기록합니다. 지난 분기 대비 현금창출력 점수가 낮아졌다면 좀 더 꼼꼼히 기업의 재무 안전성을 체크해 보는 것이 좋습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.',
        'cashflow_1'    => '현금창출력이 매우 낮은 기업입니다. 순이익이 흑자를 기록하더라도 영업활동 현금흐름이 계속 마이너스(-)로 나타나면, 현금 부족에 따른 재무구조 악화와 부실이 발생합니다. 이는 기업가치와 주가 하락으로 이어집니다. 이 기업에 대한 현금흐름 개선 등 미래 실적 추정에 대한 확신이 없다면 가급적 투자는 피하는 것이 좋습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.'
    );

    function get_spcomment($item, $param=array()) {
        $comment = $this->spider_comment[$item];
        $comment = str_replace('#TOTAL#',number_format($param['total']),$comment);
        $comment = str_replace('#RATE#',$param['high_rate'],$comment);
        $comment = str_replace('#HIGH#',number_format($param['high']),$comment);
        return $comment;
    }


    protected $ticker_currency = array (
    'KRW'=>array('억원(KRW)', '100'),
    'USD'=>array('백만달러(USD)', '0'),
    'ARS'=>array('천만페소(ARS)', '10'),
    'AUD'=>array('백만달러(AUD)', '0'),
    'BRL'=>array('백만레알(BRL)', '0'),
    'CAD'=>array('백만달러(CAD)', '0'),
    'CHF'=>array('백만프랑(CHF)', '0'),
    'CLP'=>array('천만페소(CLP)', '10'),
    'CNY'=>array('백만위안(CNY)', '0'),
    'COP'=>array('억페소(COP)', '100'),
    'DKK'=>array('백만크로네(DKK)', '0'),
    'EUR'=>array('백만유로(EUR)', '0'),
    'GBP'=>array('백만파운드(GBP)', '0'),
    'HKD'=>array('백만달러(HKD)', '0'),
    'IDR'=>array('억루피아(IDR)', '100'),
    'ILS'=>array('백만세켈(ILS)', '0'),
    'INR'=>array('백만루피(INR)', '0'),
    'JPY'=>array('억엔(JPY)', '100'),
    'MXN'=>array('백만페소(MXN)', '0'),
    'MYR'=>array('백만링깃(MYR)', '0'),
    'NOK'=>array('백만크로네(NOK)', '0'),
    'PEN'=>array('백만누에보솔(PEN)', '0'),
    'PHP'=>array('백만페소(PHP)', '0'),
    'PLN'=>array('백만즈워티(PLN)', '0'),
    'RUB'=>array('천만루블(RUB)', '10'),
    'SEK'=>array('백만크로나(SEK)', '0'),
    'TRY'=>array('백만리라(TRY)', '0'),
    'TWD'=>array('천만달러(TWD)', '10'),
    'ZAR'=>array('백만란드(ZAR)','0'));

    protected $check_mon = array (
    'a'=>'1',
    'b'=>'2',
    'c'=>'3',
    'd'=>'4',
    'e'=>'5',
    'f'=>'6',
    'g'=>'7',
    'h'=>'8',
    'i'=>'9',
    'j'=>'10',
    'k'=>'11',
    'l'=>'12');

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

        //$this->ticekr_snp500_map = $this->sp500_tb_model->getList($snp_params, $snp_extra)->getData();
        $result = array_keys($this->common->getDataByPK($this->sp500_tb_model->getList($snp_params, $snp_extra)->getData(), 'sp5_ticker'));

        return $result;

    }

    public function like($ticker='', $total='') {

        if( $this->session->userdata('user_id') != '' && isset($ticker)) {

            $user_id = $this->session->userdata('user_id');

            $this->load->model(DBNAME.'/myitem_tb_model');

            $params = array();
            $params['=']['mi_user_id'] = $user_id;
            $params['=']['mi_ticker'] = $ticker;

            $extra = array(
                'fields' => 'mi_like',
                'slavedb' => true
            );

            $like_info = array();
            $like_info = array_shift($this->myitem_tb_model->getList($params, $extra)->getData());

            if($total =='Y') {

                $params = array();
                $params['slavedb'] = TRUE;
                $params['=']['mi_ticker'] = $ticker;
                $params['=']['mi_like'] = 'Y';
                $total_count = $this->myitem_tb_model->getCount($params)->getData();

                $like_info['total_count'] = $total_count;
            }

            return $like_info;
        }
        else {
            if($total =='Y' && isset($ticker)) {

                $this->load->model(DBNAME.'/myitem_tb_model');
                $params = array();
                $params['slavedb'] = TRUE;
                $params['=']['mi_ticker'] = $ticker;
                $params['=']['mi_like'] = 'Y';
                $total_count = $this->myitem_tb_model->getCount($params)->getData();

                $like_info = array();
                $like_info['total_count'] = $total_count;

                return $like_info;
            }
        } 
    }

    //적정주가 비율
    protected function get_fairrate($tkr_close, $mri_data) {

    /*
    $result['fairvalue5'] = round($tkr_valuation*0.7, 2);
    $result['fairvalue4'] = round($tkr_valuation*0.85, 2);
    $result['fairvalue3'] = $tkr_valuation;
    $result['fairvalue2'] = round($tkr_valuation*1.176, 2);
    $result['fairvalue1'] = round($tkr_valuation*1.43, 2);
    */
        if( $mri_data['m_v_fairvalue3'] <= '0' || $mri_data['m_v_fairvalue3'] == '' ) {
            return -10;
        }
        //$tkr_close = 74.10;

        $rate = 0;
        if( $tkr_close > $mri_data['m_v_fairvalue1'] ) { //매우 고평가
            $rate = 106;
        }
        else if( $tkr_close > $mri_data['m_v_fairvalue2'] && $tkr_close <= $mri_data['m_v_fairvalue1'] ) {
            //76~100
            $term = ($mri_data['m_v_fairvalue1'] - $mri_data['m_v_fairvalue2'])/25;
            $value = $mri_data['m_v_fairvalue1'];
            for($i=25;$i>0;$i--) {

                if($i<25) $value -= $term;
                if($tkr_close >= $value) break;
            }
            $rate = 75 + $i;
        }
        else if( $tkr_close > $mri_data['m_v_fairvalue3'] && $tkr_close <= $mri_data['m_v_fairvalue2'] ) {
            //51~75
            $term = ($mri_data['m_v_fairvalue2'] - $mri_data['m_v_fairvalue3'])/25;
            $value = $mri_data['m_v_fairvalue2'];

            for($i=25;$i>0;$i--) {
                if($i<25) $value -= $term;
                if($tkr_close >= $value) break;
            }
            $rate = 50 + $i;
        }
        else if( $tkr_close > $mri_data['m_v_fairvalue4'] && $tkr_close <= $mri_data['m_v_fairvalue3'] ) {
            //26~50
            $term = ($mri_data['m_v_fairvalue3'] - $mri_data['m_v_fairvalue4'])/25;
            $value = $mri_data['m_v_fairvalue3'];

            for($i=25;$i>0;$i--) {
                if($i<25) $value -= $term;
                if($tkr_close >= $value) break;
            }
            $rate = 25 + $i;
        }
        else if( $tkr_close > $mri_data['m_v_fairvalue5'] && $tkr_close <= $mri_data['m_v_fairvalue4'] ) {
            //1~25
            $term = ($mri_data['m_v_fairvalue4'] - $mri_data['m_v_fairvalue5'])/25;
            $value = $mri_data['m_v_fairvalue4'];

            for($i=25;$i>0;$i--) {
                if($i<25) $value -= $term;
                if($tkr_close >= $value) break;
            }
            $rate = $i;
        }
        else {    //매우 저평가
            $rate = -5;
        }

        return $rate;
    }

    function randstring($len){
        return; //remove
        $return_str = "";
        for ( $i = 0; $i < $len; $i++ ) {
            mt_srand((double)microtime()*1000000);
            $return_str .= substr('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(0,61), 1);
        }

        return $return_str;
    }

    public function send_push($push_info=array()) {
        return; //remove
        if(sizeof($push_info)>0) {

            switch ($push_info['template'])
            {
                /* 결제완료_월정기 */
                case "1":
                    $templatecode = 'bizp_2020042716593521562344580';
$message = <<<PHPSKIN
[결제완료]

#REPLACE_0#님, 결제가 완료되었습니다.

- 서비스명: #REPLACE_1# #REPLACE_2# #REPLACE_3#
- 결제금액 : #REPLACE_4#원 (부가세 포함)
- 결제일 : 매월 #REPLACE_5#일

#REPLACE_6#를 이용해주셔서 감사합니다.
PHPSKIN;
                //for($i=0;$i<$push_info['count'];$i++) {
                //    $message = str_replace('#REPLACE_'.$i.'#', $push_info['at_message']['replace_'.$i], $message);
                //}
                break;
                /* 입금확인 */
                case "2":
                    $templatecode = 'bizp_2020060414383626181816266';
$message = <<<PHPSKIN
[입금확인]

#REPLACE_0#님, 입금확인 되었습니다.

- 서비스명 : #REPLACE_1# #REPLACE_2# #REPLACE_3#
- 입금금액 : #REPLACE_4#원 (부가세 포함)

#REPLACE_5#를 이용해주셔서 감사합니다.
PHPSKIN;
                break;
                /* 입금취소 */
                case "3":
                    $templatecode = 'bizp_2020042717021321562360582';
$message = <<<PHPSKIN
[입금취소]

#REPLACE_0#님, 입금기한 내 입금확인이 되지 않아 자동 취소 되었습니다.

- 서비스명: #REPLACE_1# #REPLACE_2# #REPLACE_3#
- 입금금액 : #REPLACE_4#원 (부가세 포함)

취소된 신청건에 대해서는 결제페이지에서 재신청 바랍니다.
감사합니다.
PHPSKIN;
                break;
                /* 입금안내 */
                case "4":
                    $templatecode = 'bizp_2020060215301309898183118';
$message = <<<PHPSKIN
[입금안내]

#REPLACE_0#님, 입금계좌 안내드립니다.

- 입금계좌 : #REPLACE_1# #REPLACE_2# 
- 예금주 : (주)데이터히어로
- 입금금액 : #REPLACE_3#원 (부가세 포함)
 
#REPLACE_4#까지 입금확인이 안되면 신청이 취소됩니다.
PHPSKIN;
                break;
                /* 결제완료_기간결제 */
                case "5":
                    $templatecode = 'bizp_2020042717021203031082620';
$message = <<<PHPSKIN
[결제완료]

#REPLACE_0#님, 결제가 완료되었습니다.

- 서비스명: #REPLACE_1# #REPLACE_2# #REPLACE_3#
- 결제금액 : #REPLACE_4#원 (부가세 포함)

#REPLACE_5#를 이용해주셔서 감사합니다.
PHPSKIN;
                break;
                /* 서비스해지_기간결제 */
                case "6":
                    $templatecode = 'bizp_2020060110111026181361050';
$message = <<<PHPSKIN
[서비스해지]

#REPLACE_0#님, 서비스 해지가 처리되었습니다.

- 상품명 : #REPLACE_1# #REPLACE_2# #REPLACE_3# #REPLACE_4#원

그동안 #REPLACE_5#를 이용해 주셔서 감사드립니다.
PHPSKIN;
                break;
                /* 휴대폰 인증번호 */
                case "7":
                    $templatecode = 'bizp_2020042717021403031048623';
$message = <<<PHPSKIN
[#REPLACE_0#]
인증번호는 [#REPLACE_1#] 입니다.
PHPSKIN;
                //for($i=0;$i<$push_info['count'];$i++) {
                //    $message = str_replace('#REPLACE_'.$i.'#', $push_info['at_message']['replace_'.$i], $message);
                //}
                break;
                /* 서비스해지_월정기 */
                case "8":
                    $templatecode = 'bizp_2020042717021403031042622';
$message = <<<PHPSKIN
[서비스해지]

#REPLACE_0#님, 서비스 해지가 처리되었습니다.

- 상품명 : #REPLACE_1# #REPLACE_2# #REPLACE_3#
- 잔여 일수 : #REPLACE_4#일

잔여 일수까지는 서비스 이용이 가능합니다.
그동안 #REPLACE_5#를 이용해 주셔서 감사드립니다.
PHPSKIN;
                break;
                /* 관심종목 알리미_버튼형 */
                case "9":
                    $templatecode = 'bizp_2020060315532626181526211';
$message = <<<PHPSKIN
[초이스스탁US] #{5월21일} 관심종목이 도착했어요 
#{관심종목명_1} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_2} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_3} 
#{종가} #{전일대비} (#{300.50}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_4} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_5} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_6} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_7} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_8} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_9} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

#{관심종목명_10} 
#{종가} #{전일대비} (#{등락률}%)
#{05/08} 실적발표 (전년비#{233.50}%)
#{05/08} 배당락반영 $#{40.25}

▶ 본 메세지는 회원님의 정보 수신설정으로 발송되었습니다.
PHPSKIN;
                $push_info['button']['button_name']= '관심종목 바로가기';
                $push_info['button']['button_type']= 'WL';
                $push_info['button']['url_mobile']= 'https://choicestock.co.kr/main/search';
                $push_info['button']['url_pc']= '';
                break;
                /* 월정기 결제완료 안내 */
                case "10":
                    $templatecode = 'bizp_2020050816494403031642127';
$message = <<<PHPSKIN
[초이스스탁US] #REPLACE_0# #REPLACE_1#원이 정기 결제되었습니다.
PHPSKIN;
                break;
                /* 탐구생활 알리미 */
                case "11":
                    $templatecode = 'bizp_2020060814375609898471390';
$message = <<<PHPSKIN
[초이스스탁US] 업데이트 소식

[#REPLACE_0#]
#REPLACE_1#

▶ 본 메세지는 회원님의 정보 수신설정으로 발송되었습니다.
PHPSKIN;
                $push_info['button']['button_name']= '탐구생활 확인하기';
                $push_info['button']['button_type']= 'WL';
                $push_info['button']['url_mobile']= 'https://www.choicestock.co.kr/stock/research';
                $push_info['button']['url_pc']= '';
                break;
                /* 서비스해지_기간결제_환불신청안내 */
                case "12":
                    $templatecode = 'bizp_2020060110451826181571056';
$message = <<<PHPSKIN
[환불 신청]

#REPLACE_0#님의 #REPLACE_1# 환불이 신청되었습니다.

- 환불신청일 : #REPLACE_2#
- 상품명 : #REPLACE_3# #REPLACE_4# #REPLACE_5#
- 금액 : #REPLACE_6#원

담당자 확인 후, 환불 처리가 진행됩니다.
PHPSKIN;                        
                break;
                /* 결제안내(관리자전송용) */
                case "13":
                    $templatecode = 'bizp_2020060110524109898169034';
$message = <<<PHPSKIN
[결제 신청 안내]

- 결제일자 : #REPLACE_0#
- 결제자 : #REPLACE_1#
- 상품명 : #REPLACE_2# #REPLACE_3# #REPLACE_4#
- 결제수단: #REPLACE_5#
- 금액 : #REPLACE_6#원
PHPSKIN;                        
                break;
                /* 종목분석 알리미 */
                case "14":
                    $templatecode = 'bizp_2020060814375726181522441';
$message = <<<PHPSKIN
[초이스스탁US] 업데이트 소식

[#REPLACE_0#]
#REPLACE_1#

▶ 본 메세지는 회원님의 정보 수신설정으로 발송되었습니다.
PHPSKIN;
                $push_info['button']['button_name']= '종목분석 확인하기';
                $push_info['button']['button_type']= 'WL';
                $push_info['button']['url_mobile']= 'https://www.choicestock.co.kr/stock/analysis';
                $push_info['button']['url_pc']= '';
                break;
                /* 종목추천 알리미-목표가/신규등록 */
                case "15":
                    $templatecode = 'bizp_2020060814375626181797440';
$message = <<<PHPSKIN
[초이스스탁US] 업데이트 소식

[#REPLACE_0#]
#REPLACE_1#

▶ 본 메세지는 회원님의 정보 수신설정으로 발송되었습니다.
PHPSKIN;
                $push_info['button']['button_name']= '종목추천 확인하기';
                $push_info['button']['button_type']= 'WL';
                $push_info['button']['url_mobile']= 'https://www.choicestock.co.kr/stock/recommend';
                $push_info['button']['url_pc']= '';
                break;
                /* 콘텐츠 알리미_심플 */
                case "16":
                    $templatecode = 'bizp_2020060416362826181652291';
$message = <<<PHPSKIN
[초이스스탁US] 업데이트 소식

[#REPLACE_0#]
#REPLACE_1#

▶ 본 메세지는 회원님의 정보 수신설정으로 발송되었습니다.
PHPSKIN;
                break;
                /* 관심종목 알리미 - 1개 버튼없음 */
                case "17":
                    $templatecode = 'bizp_2020061614020200437766250';
$message = <<<PHPSKIN
[초이스스탁US] #REPLACE_0# 관심종목이 도착했어요 

#REPLACE_1#

▶관심종목 바로가기 #REPLACE_2#

※ 본 메세지는 회원님의 정보 수신설정으로 발송되었습니다.
PHPSKIN;
                break;
                /* 결제완료_월정기_2주무료(심플)(20.08/10) */
                case "18":
                    $templatecode = 'bizp_2020081013122605716492020';
$message = <<<PHPSKIN
[2주무료체험 신청완료]

#REPLACE_0#님, 프리미엄 무료체험 신청이 완료 되었습니다.
2주간 초이스스탁US를 자유롭게 이용하세요.

#REPLACE_1#일 전에 프리미엄 서비스를 해지하시면 요금이 청구되지 않습니다.
무약정으로 언제든지 해지하실 수 있습니다.
PHPSKIN;
                break;
                /* 관심종목 알리미 - 버튼형 */
                case "19":
                    $templatecode = 'bizp_2020073111095126115384627';
$message = <<<PHPSKIN
[초이스스탁US] #REPLACE_0# 설정하신 관심종목이 도착했어요

#REPLACE_1#

관심종목의 투자매력은 밤새 어떻게 바뀌었을까?
원스탑 진단에서 확인해보세요~

※ 본 메세지는 회원님의 정보 수신설정으로 발송되었습니다.
PHPSKIN;
                $push_info['button']['button_name'] = '원스톱 진단 바로가기';
                $push_info['button']['button_type'] = 'WL';
                $push_info['button']['url_mobile'] = 'https://www.choicestock.co.kr';
                $push_info['button']['url_pc']= '';
                break;
                /*문자메세지*/
                case "55":
                    $templatecode = '';
                    $message = $push_info['sms_message'];
                break;
            }

            for($i=0;$i<$push_info['count'];$i++) {
                $message = str_replace('#REPLACE_'.$i.'#', $push_info['at_message']['replace_'.$i], $message);
            }

            $push_type = $push_info['push_type'];
            $refkey = SRV_CODE.$push_type.str_pad(mt_rand(0,999),3,'0').$this->randstring(4).date('YmdHis'); // 상품주문번호(CS01+type+rand+date(YmdHis))
            $from = $push_info['from'];
            $to = $push_info['to'];

            //문자
            if($push_type=='sms') {
                $sms = array("message" => $message);
                $senderkey = $push_type.SRV_CODE.str_pad(mt_rand(0,999),2,'0').$this->randstring(4).date('YmdHis');
            }
            else {
                //$senderkey = PUSH_SKEY;
                //$templatecode = $push_info['template'];;
                //$templatecode = 'bizp_2020050810560521562359095';
                //$button_name = '';
                //$button_type = '';
                //$button_url_mobile = '';
                //$button_url_pc = '';
                /*
                $button_name = '관심종목 바로가기';
                $button_type = 'WL';
                $button_url_mobile = 'https://choicestock.co.kr/main/search';
                $button_url_pc = '';
                */
                //알림톡
                if(isset($push_info['button']) && sizeof($push_info['button'])>0) {
                    /* 버튼 type */
                    $sms =  array('senderkey' => PUSH_SKEY,
                                'templatecode' => $templatecode,
                                'message' => $message,
                                'button'  => array(array( 'name' => $push_info['button']['button_name'],
                                                    'type' => $push_info['button']['button_type'],
                                                    'url_mobile' => $push_info['button']['url_mobile'],
                                                    'url_pc' => $push_info['button']['url_pc']
                                            ))
                            );
                }
                else {
                    $sms =  array('senderkey' => PUSH_SKEY,
                            'templatecode' => $templatecode,
                            'message' => $message,
                            );
                }

            }

            $content = array($push_type => $sms);

            $data = array();
            $data["account"] = PUSH_ACCOUNT;
            $data["refkey"] = $refkey; //고객사에서 부여한 키 32자(최대)

            $data["type"] = $push_type;    //sms, lms, mms, at, ft, rcs
            $data["from"] = $from;
            $data["to"] = $to;
            $data["content"] = $content;
            /*
            echo 'Request :';
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            */
            $json_data = json_encode($data, JSON_UNESCAPED_SLASHES);
            $url = PUSH_API; //PUSH_API, PUSH_API_TEST
            $oCurl = curl_init();
            curl_setopt($oCurl,CURLOPT_URL,$url);
            curl_setopt($oCurl,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($oCurl,CURLOPT_NOSIGNAL, 1);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($oCurl, CURLOPT_HTTPHEADER,
            array('Accept: application/json', 'Content-Type: application/json'));
            curl_setopt($oCurl, CURLOPT_VERBOSE, true);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($oCurl);
            $curl_errno = curl_errno($oCurl);
            $curl_error = curl_error($oCurl);
            curl_close($oCurl);

            //$response['curl_errno'] = $curl_errno;
            //$response['curl_error'] = $curl_error;
            //[code] => 1000
            //[description] => ok
            //[refkey] => CS01at305SuJw20200529153822
            //[messagekey] => 200529153822889#at026972dataF1xq

            /**/
            $result = json_decode($response, true);
            return $result;
            /**/

            /*확인용
            echo 'Response :';
            echo '<pre>';
            print_r(json_decode($response, true));
            print_r($curl_error);
            echo '</pre>';
            확인용*/

        }
        else {
            //$result = array();
            //$result['code'] = 'false';
            //return $result;
            return false;
        }
/*
[문자]
{
    "account": "test",
    "refkey": "test1234",
    "type": "sms",
    "from": "07000000000",
    "to": "01000000000",
    "content": {
        "sms": {
            "message": "SMS 전송"
        }
    }
}

[알림톡]
{
    "account": "test",
    "refkey": "test1234",
    "type": "at",
    "from": "07000000000",
    "to": "01000000000",

    "content": {
        "at": {
            "senderkey": "12345",
            "templatecode": "template",
            "message": "알림톡 + 버튼(WL)",
            "button": [
            {
                "name": "웹 링크 버튼",
                "type": "WL",
                "url_mobile": https://www.daou.com
                "url_pc": "https://www.daou.com"
            }
            ]
        }
    }
}

[AT 전송 실패하는 경우, SMS (1 차) 대체 전송]
{
    "account": "test",
    "refkey": "test1234",
    "type": "at",
    "from": "07000000000",
    "to": "01000000000",

    "content": {
        "at": {
            "senderkey": "12345",
            "templatecode": "template",
            "message": "알림톡 + 버튼(WL)",
            "button": [
            {
                "name": "웹 링크 버튼",
                "type": "WL",
                "url_mobile": "https: //www.daou.com", "url_pc": "https://www.daou.com"
            }
            ]
        }
    },
    "resend": {
        "first": "sms"
    },
    "recontent": {
        "sms": {
        "message": "SMS 대체 발송"
    }
}
*/
    }

    protected $yoil = array("일", "월", "화", "수", "목", "금", "토");
    protected $win_trend = array('S'=>'up', 'N'=>'trans', 'W'=>'down');

    protected $pay_name = array (
    'CS01'=>'초이스스탁'
    );
/* remove
    protected $pay_method_name = array (
    'CARD'=>'카드',
    'BANK'=>'계좌이체',
    'VBANK'=>'가상계좌(무통장)',
    'COUPON'=>'쿠폰'
    );

    protected $pay_info = array(
        '1' => array(
          'ori_price'=>'44000',
          'price'=>CS01_PRICE_1,
          //'price'=>'3300',
          'period'=>'30',
          'type'=>'A',
          'freeday'=>'14',
          'month'=>'1' ),
        '2' => array(
          'ori_price'=>'132000',
          'price'=>CS01_PRICE_2,
          //'price'=>'13200',
          'period'=>'92',
          'type'=>'G',
          'freeday'=>'0',
          'month'=>'3' ),
        '3' => array(
          'ori_price'=>'264000',
          'price'=>CS01_PRICE_3,
          //'price'=>'26400',
          'period'=>'185',
          'type'=>'G',
          'freeday'=>'0',
          'month'=>'6' ),
    );
remove */
    //적정주가 오픈 티커 (15종목, 20.06/16)
    protected $open_ticker = array('AAPL', 'MSFT', 'AMZN', 'DAL', 'INTC', 'CCL', 'KO', 'GOOGL', 'T', 'MRK', 'JNJ', 'AMD', 'NVDA', 'O', 'XOM');

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

    public function auto_link($str) {
        $str = preg_replace("/&lt;/", "\t_lt_\t", $str);
        $str = preg_replace("/&gt;/", "\t_gt_\t", $str);
        $str = preg_replace("/&amp;/", "&", $str);
        $str = preg_replace("/&quot;/", "\"", $str);
        $str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
        $str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\">\\2</A>", $str);
        $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\">\\2</A>", $str);
        $str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
        $str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
        $str = preg_replace("/\t_lt_\t/", "&lt;", $str);
        $str = preg_replace("/\t_gt_\t/", "&gt;", $str);
        return $str;
    }
}