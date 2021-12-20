<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
abstract class Px_BasePC_Controller extends CI_Controller {
    // 컨트롤러 전체가 로그인 회원만 제공할 경우 컨트롤러에서 true로 설정하면 생성자에서 로긴 페이지로 redirect.
    // 특정 view 만 로긴 회원이어야 하는 컨트롤러는 해당 action method에서 
    // if($this->check_signin() == false) {
    //        return;
    // } 
    // 하여 사용하기. 
    protected $signin_only = false; 

    protected $header_data = array(
            'is_main_index' => false,
            ); 

    protected $current_ticker_info = array();

    // 종목 검색시 클라이언트 브라우져 리소스가 감당토록 떨궈둘 검색 기업정보 저장소
    protected $search_ticker_list = array();

    // 티커코드 => 한글 기업명 맵. 떠놓고 받아쓰기.
    protected $ticker_korean_map = array();     // 전 종목 중 한글 컨펌 완료 입력된 정보
    protected $ticker_priceinfo_map = array();  // 전종목 가격, 등락율 등 저장소
    protected $ticker_info_map = array();  // 전종목 Ticker 정보. 그냥 들고 있자.
    protected $is_open = false;
    // S&P500 티커
    //protected $ticekr_snp500_map = array();  

    function __construct() {
        parent::__construct();

        if($this->signin_only == true && $this->check_signin() == false) {
            die();
        }

        //더벨 체크
        //$this->wm_logincheck();

		//echo '<pre>.'; print_r($_COOKIE['dh_ticker']);

        $this->load->model(array(
                    DBNAME.'/ticker_tb_model',
                    DBNAME.'/company_tb_model',
                    DBNAME.'/myitem_tb_model',
                    DBNAME.'/sp500_tb_model',
                    'business/historylib',
                    ));

        $this->load->helper('cookie');
		//echo '<pre>'; print_r($_COOKIE['dh_ticker']);

        // @ 기업 검색 리스트
        // 1. 유효 기업 전체 가져오기
        $ticker_params = array();
        $ticker_params['=']['tkr_table'] = 'SF1';
        //$ticker_params['=']['tkr_table'] = 'SEP';
        $ticker_params['=']['tkr_isdelisted'] = 'N';
        $ticker_params['!=']['tkr_exchange'] = 'OTC';

        $ticker_extra = array(
                'order_by' => 'tkr_ticker',
                'fields' => 'tkr_ticker, tkr_permaticker, tkr_name, tkr_lastpricedate, tkr_sector, tkr_industry, tkr_exchange, tkr_category, tkr_currency, tkr_companysite, tkr_table',
                'slavedb' => true,
                'cache_sec' => 3600*12,
                );
        $ticker_list = $this->common->getDataByPK($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');

        /*
        $ticker_params['=']['tkr_table'] = 'SEP';
        $ticker_list_sep = $this->common->getDataByPK($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');
        $ticker_list = array_intersect_key($ticker_list, $ticker_list_sep);
        */

        $this->ticker_info_map = $ticker_list;

        // 2. 한국어 기업 정리
        $comp_params = array();
        //$comp_params['=']['cp_is_confirmed'] = 'YES';

        $comp_extra = array(
                'order_by' => '',
                'fields' => 'cp_ticker, cp_korname',
                'cache_sec' => 3600,
                );

        $comp_list = $this->common->getDataByPK($this->company_tb_model->getList($comp_params, $comp_extra)->getData(), 'cp_ticker');
        
        $comp_list_new = array();
//echo '<pre>';
//print_r($ticker_list);

        foreach($ticker_list as $cKey => $cVal ) {

            $co_korname='';
            $co_korname = $comp_list[$cKey]['cp_korname'];

            if($cVal['tkr_exchange'] == 'NASDAQ' || $cVal['tkr_exchange'] == 'NYSE' || $cVal['tkr_exchange'] == 'NYSEMKT') {
                if($cVal['tkr_category'] == 'ADR Preferred' || $cVal['tkr_category'] == 'ADR Secondary' || $cVal['tkr_category'] == 'Canadian Preferred' || $cVal['tkr_category'] == 'Canadian Secondary' || $cVal['tkr_category'] == 'Domestic Preferred' || $cVal['tkr_category'] == 'Domestic Secondary') {
                
                    $params = array();
                    $params['=']['tkr_name'] = $cVal['tkr_name'];
                    $params['=']['tkr_table'] = 'SF1';

                    $extra = array(
                        'order_by' => 'tkr_isdelisted',
                        'fields' => array('tkr_ticker'),
                        'limit' => '1'
                    );
                    $extra['cache_sec'] = 3600*12;

                    $data = $this->ticker_tb_model->getList($params, $extra)->getData();
                    $co_korname = $comp_list[$data[0]['tkr_ticker']]['cp_korname'];
                } 
            }

            $comp_list_new[$cKey]['cp_ticker'] = $cKey;
            $comp_list_new[$cKey]['cp_korname'] = $co_korname;
        }

        $this->ticker_korean_map = $this->common->array2map($comp_list_new, 'cp_ticker', 'cp_korname');
        //print_r($this->ticker_korean_map);
        $this->ticker_priceinfo_map = $this->sep_tb_model->getTickerPriceMap();
        //echo '<pre>';
        //print_r($this->ticker_priceinfo_map);
       

        //select distinct sp5_ticker from sp500_tb where sp5_action = 'current' and sp5_date = (select max(sp5_date) from sp500_tb) order by sp5_date desc
/*
        $arr_tkrdata = array();
        $arr_tkrdata = $this->common->getDataByPK($this->ticker_tb_model->getList(array(), array('order_by' => 'tkr_lastpricedate desc', 'limit' => 1))->getData(), 'tkr_ticker');
        $arr_tkrdata = array_values($arr_tkrdata);
        $tkr_maxdate = $arr_tkrdata[0]['tkr_lastpricedate']; 
        $tkr_maxdate = str_replace('-', '', $tkr_maxdate);

*/

        // 3. 조합 
        $search_list = array();
        foreach($ticker_list as $t => $row) {
            $txt = array(
                    'ticker' => $row['tkr_ticker'],
                    'name' => $row['tkr_name'],
                    );
            if(isset($comp_list_new[$t])) {
                $txt['name'] = $comp_list_new[$t]['cp_korname'].' '.$row['tkr_name'];
            }
            $search_list[$row['tkr_ticker']] = $txt;
        }
        $this->search_ticker_list = $search_list;

        //장 시작시간 체크 
        $this->_open_check();

		//echo '<pre>'; print_r($search_list); exit;
        // End of - 기업 검색 리스트
        //아이투자 로그인 체크
        //$this->loginConnect();
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

    protected function wm_logincheck(){
		return; //remove
		$refer = $_SERVER['HTTP_REFERER'];
		$ip_address = $_SERVER['REMOTE_ADDR'];

		//echo $_SERVER['HTTP_HOST'];
		//if(strstr('http://us-wm.datahero.co.kr', $_SERVER['HTTP_HOST'])) {
		//}
		//else {
		if( $ip_address != '112.217.169.122' && $ip_address != '1.222.69.93' && $ip_address != ' 1.214.48.95' ) {
			if($refer=='' || !strstr($refer, 'thewm.co.kr') ) {
				$this->common->locationhref('/error');
				exit;
			}
		}
		//}
        //$wmSiteAuth = get_cookie('wmSiteAuth');
	}

    protected function loginCheck(){
		return; //remove
		//$_SERVER['HTTP_REFERER'];
        if(!$this->session->userdata('is_login')){
            $return_uri = urlencode($this->uri->uri_string());
            redirect('/auth/login?rd='.$return_uri);
            exit;
        }
    }
    
    private function loginConnect() {
		return; //remove
        $strUKey = get_cookie('UniqueKey');

        $strLoginURL = 'http://www.itooza.com/common/api_externsession.php?ssk=' . $strUKey;
        $strGetLoginData = iconv('EUC-KR', 'UTF-8', $this->get_content($strLoginURL));    
        $strArrLoginData = json_decode($strGetLoginData, true);

        if($strArrLoginData['cklogin'] == 'OK') {
            if(!$this->session->userdata('is_login')) {
                $this->_set_session($strArrLoginData);
            }
        }
        else {
            $this->session->sess_destroy();
            delete_cookie('my_ticker');            
        }        
    }

    function _set_session($strArrLoginData) {
		return; //remove
        /*
        "cklogin":"OK" => 로그인여부(OK:로그인, FAIL:비로그인)
        ,"ckadmin":"OK" => 관리자여부(OK:관리자, FAIL:관리자아님)
        ,"ckfeeP":"FAIL" => 프리미엄 결제 유효 여부(OK:유효, FAIL:만료또는 결제안함)
        ,"ckfeeS":"FAIL" => 스탠다드 결제 유효 여부(OK:유효, FAIL:만료또는 결제안함)
        ,"retmsg":"" => 에러난경우 에러 메시지 출력
        ,"ID":"netstar7" => 아이디
        ,"NAME":"\ubc15\ub178\uc911" => 이름
        ,"REALFILNAME":"\ub124\ud750" => 필명
        ,"LEVEL":"15" => 레벨

        */
        if($strArrLoginData['ckadmin']=='OK') $strArrLoginData['ckadmin'] = TRUE; else $strArrLoginData['ckadmin'] = FALSE;
        $this->session->set_userdata(
            array(
                'is_login'=> TRUE, 
                'is_admin'=>$strArrLoginData['ckadmin'],
                'is_feeP'=>$strArrLoginData['ckfeeP'],
                'is_feeS'=>$strArrLoginData['ckfeeS'],
                'retmsg'=>$strArrLoginData['retmsg'],
                'user_id'=>$strArrLoginData['ID'],
                'user_name'=>$strArrLoginData['NAME'],
                'user_filname'=>$strArrLoginData['REALFILNAME'],
                'user_level'=>$strArrLoginData['LEVEL']
            )
        );  
        
        //관심종목 cookie
        $this->make_myticker();
    }

    public function get_snp500() {
		return; //remove
        $snp_params = array();
        //$snp_params['=']['sp5_date'] = '2020-02-17';
        $snp_params['=']['sp5_action'] = 'current';
        $snp_params['raw'] = array('sp5_date = (select max(sp5_date) from sp500_tb)');
        $snp_params['join']['ticker_tb'] = 'sp5_ticker = tkr_ticker and tkr_table = "SF1" and tkr_isdelisted = "N" ';

        $snp_extra = array(
            'order_by' => 'sp5_date desc',
            'fields' => 'sp5_ticker',
            'slavedb' => true,
            'cache_sec' => 3600*12
        );

        //$this->ticekr_snp500_map = $this->sp500_tb_model->getList($snp_params, $snp_extra)->getData();
        $result = array_keys($this->common->getDataByPK($this->sp500_tb_model->getList($snp_params, $snp_extra)->getData(), 'sp5_ticker'));

        return $result;

    }
    public function make_myticker() {
		return; //remove
        $PAGE_ARTICLE_ROW = 12;
        $PAGE_OFFSET = 0;
        $list = $this->myitem_tb_model->get_mylist( $PAGE_ARTICLE_ROW, $PAGE_OFFSET, 'D' );

        $my_ticker_list = '';

        foreach($list as $nKey=>$nVal) {
            $my_ticker_list .= $nVal['my_ticker'].'|';
        }

        if($my_ticker_list) {
            set_cookie('my_ticker', $my_ticker_list, time()+86400);
        }
    }

    public function IpCheck() {
		return; //remove
        // 개발용 프로젝트는 허용. IS_REAL_SERVER 는 document_root /index.php 최상단에서 설정하고 있음. 2019.8.10. hamt
        if( ! IS_REAL_SERVER) {
            return;
        }
        return; // 주말 QC리스트 쳐낸거 확인 요청으로. 주석처리.

        if( $_SERVER['REMOTE_ADDR'] != '106.255.245.2' ) {
            redirect('https://us153.datahero.co.kr');
            exit;
        }    
    }

    public function popup_view($view, $data){
		return; //remove
        $data['sess_data'] = $this->session->all_userdata();

        $this->load->view('/pc/inc/popup_header.php');
        $this->load->view('/pc/'.$view, $data); 
        $this->load->view('/pc/inc/popup_footer.php');
    }
/*
    public function _view($view, $data=array(), $return_contents = false){
		return; //remove
        $data['sess_data'] = $this->session->all_userdata();

        $header = array();
        $header['sess_data'] = $this->session->all_userdata();
        $header['search_ticker_list'] = $this->search_ticker_list;
        $header['current_ticker_info'] = $this->current_ticker_info;
        $header['header_data'] = $this->header_data;
        $header['header_class'] = '';
        if(isset($data['company_info']))
            $header['company_info'] = $data['company_info'];

        $footer = array();

        if( ! $return_contents) {
            $this->load->view('/px/inc/header.php', $header);
            $this->load->view('/px/'.$view.'.php', $data); 
            $this->load->view('/px/inc/footer.php', $footer);
        } else {
            $result  = $this->load->view('/px/inc/header.php', $header, true)."\n";
            $result .= $this->load->view('/px/'.$view.'.php', $data, true)."\n"; 
            $result .= $this->load->view('/px/inc/footer.php', $footer, true)."\n";
            return $result;
        }
    }
*/
    public function _view($view, $data=array(), $return_contents = false){
        //$data['sess_data'] = $this->session->all_userdata();

        $header = array();
        //$header['sess_data'] = $this->session->all_userdata();
        $header['search_ticker_list'] = $this->search_ticker_list;
        $header['current_ticker_info'] = $this->current_ticker_info;
        $header['header_data'] = $this->header_data;
        $header['header_class'] = '';
        if(isset($data['company_info']))
            $header['company_info'] = $data['company_info'];
        $footer = array();

        if( ! $return_contents) {
            $this->load->view('/px/inc/header.php', $header);
            $this->load->view('/px/'.$view.'.php', $data); 
            $this->load->view('/px/inc/footer.php', $footer);
        } else {
            $result  = $this->load->view('/px/inc/header.php', $header, true)."\n";
            $result .= $this->load->view('/px/'.$view.'.php', $data, true)."\n"; 
            $result .= $this->load->view('/px/inc/footer.php', $footer, true)."\n";
            return $result;
        }
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

	protected $ticker_currency_more = array (
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

    protected $ticker_currency = array (
    'KRW'=>array('억원', '100'), 
    'USD'=>array('백만달러', '0'), 
    'ARS'=>array('천만페소', '10'), 
    'AUD'=>array('백만달러', '0'), 
    'BRL'=>array('백만레알', '0'), 
    'CAD'=>array('백만달러', '0'), 
    'CHF'=>array('백만프랑', '0'), 
    'CLP'=>array('천만페소', '10'), 
    'CNY'=>array('백만위안', '0'), 
    'COP'=>array('억페소', '100'), 
    'DKK'=>array('백만크로네', '0'), 
    'EUR'=>array('백만유로', '0'), 
    'GBP'=>array('백만파운드', '0'), 
    'HKD'=>array('백만달러', '0'), 
    'IDR'=>array('억루피아', '100'), 
    'ILS'=>array('백만세켈', '0'), 
    'INR'=>array('백만루피', '0'), 
    'JPY'=>array('억엔', '100'), 
    'MXN'=>array('백만페소', '0'), 
    'MYR'=>array('백만링깃', '0'), 
    'NOK'=>array('백만크로네', '0'), 
    'PEN'=>array('백만누에보솔', '0'), 
    'PHP'=>array('백만페소', '0'), 
    'PLN'=>array('백만즈워티', '0'), 
    'RUB'=>array('천만루블', '10'), 
    'SEK'=>array('백만크로나', '0'), 
    'TRY'=>array('백만리라', '0'), 
    'TWD'=>array('천만달러', '10'), 
    'ZAR'=>array('백만란드','0'));

	protected $open_ticker = array('AAPL', 'MSFT', 'AMZN', 'DAL', 'INTC', 'CCL', 'KO', 'GOOGL', 'T', 'MRK', 'JNJ', 'AMD', 'NVDA', 'O', 'XOM');
}
?>
