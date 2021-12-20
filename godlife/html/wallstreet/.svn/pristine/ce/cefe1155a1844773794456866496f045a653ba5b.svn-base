<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
abstract class BaseMobile_Controller extends CI_Controller {
    // 컨트롤러 전체가 로그인 회원만 제공할 경우 컨트롤러에서 true로 설정하면 생성자에서 로긴 페이지로 redirect.
    // 특정 view 만 로긴 회원이어야 하는 컨트롤러는 해당 action method에서 
    // if($this->check_signin() == false) {
    //		return;
    // } 
    // 하여 사용하기. 

    protected $signin_only = false; 

    protected $header_data = array(
        'header_template' => '1',
        'header_type' => '', // 컨트롤러 메서드에서 재정의 가능. sch_heaher: 흰색 머리. 검색영역 숨김
        'head_title' => SERVICE_NAME,
        'show_alarm' => FALSE,

    ); 
	protected $partner_info = array();

    // 전종목 Ticker 정보. 그냥 들고 있자.
    protected $ticker_info_map = array();  

    // 종목 검색시 클라이언트 브라우져 리소스가 감당토록 떨궈둘 검색 기업정보 저장소
    protected $search_ticker_list = array();

    // 인기주 TOP 50
    protected $popular_search_ticker = array();  
    protected $is_shinhan = false;

    function __construct() {
        parent::__construct();

        if($this->signin_only == true && $this->check_signin() == false) {
            die();
        }

        $this->_host_check();

        $this->load->model(array(
            DBNAME.'/ticker_tb_model',
            DBNAME.'/mri_tb_model',
            DBNAME.'/search_log_tb_model',
            DBNAME.'/notify_tb_model',
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
        $params['!=']['nt_view_srv'] = 'C';
        $params['<=']['nt_display_date'] = date('Y-m-d H:i:s');

        $extra = array();
        $extra['limit'] = 20;
        $extra['order_by'] = 'nt_display_date DESC';
		$extra['slavedb'] = true;
        $this->header_data['noti_list'] = $this->notify_tb_model->getList($params, $extra)->getData();
        $this->header_data['noti_table_map'] = $this->notify_tb_model->getTableMap();

		$this->partner_info['part_name'] = $this->input->get('pn');
		$this->partner_info['part_page'] = $this->input->get('pg');
		$this->partner_info['part_move'] = $this->input->get('pm');
		//파라미터체크
		$this->loginConnect();

		if(!IS_REAL_SERVER) {
			//$this->IpCheck();
		}
	}

    private function _host_check() {
        $host = $_SERVER['HTTP_HOST'];
        if(strstr($host, 'hero-shinhan.datahero.co.kr')) {
        //if(strstr($host, 'capdev.choicestock.co.kr')) {
            $this->is_shinhan = true;
        }
    }

    public function IpCheck() {
        // 개발용 프로젝트는 허용. IS_REAL_SERVER 는 document_root /index.php 최상단에서 설정하고 있음. 2019.8.10. hamt
        if(!strstr($_SERVER['REMOTE_ADDR'], '61.74.181')) {
            redirect('https://hero.datahero.co.kr');
            exit;
        }
    }

    protected function loginCheck(){
		$strCookie = get_cookie('part_name');

		if($strCookie=='') {
			$this->common->locationhref('/');
			exit;
		}
    }
	
	private function loginConnect() {
		$strCookie = get_cookie('part_name');
		$strParam = $this->partner_info['part_name'];
		$is_correct = true;

		if( $strCookie == '' ) {
			if($strParam == 'kiwoom' || $strParam == 'shinhan') {
				set_cookie('part_name', $strParam, time()+86400*365);
			}
			else if($strParam != '') {
				$check_day = intval(date('md'));

				//abcdefghijkl ahero05
				$p = substr($strParam,0,1);
				$check_param = $this->check_mon[$p];
				
				if(substr($strParam,1,4) != 'hero') {
					$is_correct = false;
				}

				if(substr($strParam,5,2) > 31) {
					$is_correct = false;
				}

				$check_param .= substr($strParam,5,2);

				if(is_numeric($check_param) && $is_correct) {
					if($check_day<=$check_param) {
						set_cookie('part_name', $strParam, time()+86400);
					}
				}
			}
		}
		else {
			if( $strCookie != 'kiwoom' && $strCookie != 'shinhan' ) {
				$check_day = intval(date('md'));

				//abcdefghijkl ahero05
				$p = substr($strCookie,0,1);
				$check_param = $this->check_mon[$p];

				if(substr($strCookie,1,4) != 'hero') {
					$is_correct = false;
				}

				if(substr($strCookie,5,2) > 31) {
					$is_correct = false;
				}

				$check_param .= substr($strCookie,5,2);

				if(is_numeric($check_param) && $is_correct) {
					if($check_day>$check_param) {
						delete_cookie('part_name');
					}
				}
				else {
					delete_cookie('part_name');
				}
			}
		}
	}

    protected function check_signin() {
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

	private function _set_session() {
		$this->session->set_userdata(
			array(
				'is_kiwoom'=> TRUE, 
				'is_eltop'=> TRUE
			)
		);  
	}

    public function cms_view($view, $data=array(), $return_contents = false){
        //$data['sess_data'] = $this->session->all_userdata();

        $header = array();
        //$header['sess_data'] = $this->session->all_userdata();
        $header['search_ticker_list'] = $this->search_ticker_list;
        //$header['current_ticker_info'] = $this->current_ticker_info;
        //$header['header_data'] = $this->header_data;
        //$header['header_class'] = '';
        //if(isset($data['company_info']))
        //    $header['company_info'] = $data['company_info'];
        $footer = array();

        if( ! $return_contents) {
            $this->load->view('/cms/inc/header.php', $header);
            $this->load->view('/cms/'.$view.'.php', $data); 
            $this->load->view('/cms/inc/footer.php', $footer);
        } else {
            $result  = $this->load->view('/cms/inc/header.php', $header, true)."\n";
            $result .= $this->load->view('/cms/'.$view.'.php', $data, true)."\n"; 
            $result .= $this->load->view('/cms/inc/footer.php', $footer, true)."\n";
            return $result;
        }
    }

    public function popup_view($view, $data){
        $data['sess_data'] = $this->session->all_userdata();

        $this->load->view('/mobile/inc/popup_header.php');
        $this->load->view('/mobile/'.$view, $data); 
        $this->load->view('/mobile/inc/popup_footer.php');
    }

    public function _view($view, $data=array(), $return_contents = false){

//if($_SERVER['REMOTE_ADDR'] == '1.214.48.194' ) {
//	echo '<br>name===>'.$this->partner_info['name'];
//	echo '<br>page===>'.$this->partner_info['page'];
	//echo '<br>key===>'.$this->partner_info['key'];
//}

        $data['sess_data'] = $this->session->all_userdata();

        $header = array();
        $header['sess_data'] = $this->session->all_userdata();

        $this->load->library('Menu');
        $header['header_data'] = $this->header_data;
        $header['search_ticker_list'] = $this->search_ticker_list;

        $container_class = '';
        $show_footer = TRUE;
		$show_type = '';
        $request_uri = $this->uri->segment(1).'/'.$this->uri->segment(2);


        if( ! isset($this->header_data['container_class'])) { // controller 메서드에서 $this->header_data['container_class'] = 'sub_search'; 같은 set 없을시만.
            switch($request_uri) {
                case 'main/service':
                case 'main/service_guide':
                    $container_class = 'sub_service';
                    $show_footer = FALSE;
                    break;
                case 'main/search':
                    $container_class = 'sub_search';
                    break;
                case 'stock/recommend':
                    $container_class = 'sub_recom';
					$show_type = '1';
                    break;
                case 'stock/recommend_view':
                    $container_class = 'sub_recom';
					$show_type = '1';
                    break;
                case 'stock/analysis':
                    $container_class = 'sub_analysis';
                    break;
                case 'stock/analysis_view':
                    $container_class = 'sub_analysis';
                    break;
                case 'stock/recipe':
                    $container_class = 'sub_recipe';
                    break;
                case 'stock/research':
                    $container_class = 'sub_research';
                    break;
                case 'stock/research_view':
                    $container_class = 'sub_research';
                    break;
                case 'stock/master':
                    $container_class = 'sub_master';
					break;
                case 'stock/master_view':
                    $container_class = 'sub_master';
					break;
                case 'search/primary_ticker':
                case 'search/invest_charm':
                case 'search/summary':
                case 'search/finance_chart':
                case 'search/invest':
                case 'search/financials':
                case 'search/alloca':
                    $container_class = 'sub_search';
                    break;
                case 'attractiveness/attractive':
                    $container_class = 'sub_attract';
                    break;
                case 'master/lists':
                    $container_class = 'sub_master';
                    break;
                case 'master/view':
                    $container_class = 'sub_master';
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

		if($this->partner_info['part_page'] != 'et') {
	        $header['header_contents_html'] = $this->menu->get_header_contents($this->header_data['header_template'], $header);
		}

        $footer = array();
        $footer['show_footer'] = $show_footer;
        $footer['type'] = $show_type;

		$part_name = get_cookie('part_name');

		if($part_name=='') {
			$part_name = $this->partner_info['part_name'];
		}

        $header['part_name'] = $part_name;
        $header['is_shinhan'] = $this->is_shinhan;
    
        if( ! $return_contents) {

            $this->load->view('/mobile/inc/header.php', $header);
            $this->load->view('/mobile/'.$view.'.php', $data); 
            $this->load->view('/mobile/inc/footer.php', $footer);
        } else {
			
			//if($data['chkUri']!='kiwoom') {
			$result  = $this->load->view('/mobile/inc/header.php', $header, true)."\n";
	        $result .= $this->load->view('/mobile/'.$view.'.php', $data, true)."\n"; 
			//}
            $result .= $this->load->view('/mobile/inc/footer.php', $footer, true)."\n";
            return $result;
        }
    }

	/*
	저평가 : 현재주가 < [fairvalue3]*0.925
	적정가 : [fairvalue3]*0.925 <= 현재주가 <= [fairvalue3]*1.088
	고평가 : 현재주가 > [fairvalue3]*1.088
	*/
	public function cal_valuation($fairvalue, $close) {

		if( $close > $fairvalue * 1.088 ) {
			$star = 5;	//고평가
		}
		else if( $close >= $fairvalue * 0.925 ) {
			$star = 3;
		}
		else {
			$star = 1;	//저평가
		}

		return $star;
	}	

	protected $spider_comment = array (
		'dividend_5'	=> '배당주 투자 대상으로 최고의 매력을 가지고 있습니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다.', 
		'dividend_4'	=> '배당주 투자 대상으로 매력이 있습니다. 꾸준히 배당금을 지급하고 있어 향후에도 안정적인 배당금 수입이 예상됩니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다. ', 
		'dividend_3'	=> '배당주 투자 매력은 보통입니다. 배당금을 지급하지만 상대적으로 배당 투자 매력은 떨어지는 편입니다. 배당 투자 목적으로만 주식을 매수하기에는 매력이 없습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다. ', 
		'dividend_2'	=> '배당주 투자 매력은 낮은 편입니다. 현재 배당금을 지급할 여력은 있으나, 배당에 적극적이지 않고 미래에도 꾸준히 안정적인 배당을 기대하는 건 현재로선 어려워 보입니다. 순이익 증가로 주가 상승을 기대할 수 있는 요인이 있는지 함께 검토하는 것이 좋습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다.', 
		'dividend_1'	=> '배당주 투자 대상으로는 매력이 없습니다. 과거 배당금 지급이 제한적이고, 미래 배당 지급 여력도 낮은 편입니다. 순이익 증가로 주가 상승을 기대할 수 있는 요인이 있는지 함께 검토하는 것이 좋습니다. 배당 매력은 과거의 배당 지급 내역, 시가배당률, 배당성향은 물론, 향후 배당 성장 가능성과 지급 여력을 판단하는 순이익과 잉여현금 성장성 등을 종합해 평가합니다. ', 
		'moat_5'	=> '워렌 버핏이 강조하는 경제적해자가 가장 넓은 기업입니다. 넓은 경제적해자를 가진 기업은 현재의 고수익을 오랫동안 지킬 수 있는 사업독점력을 갖춘 회사를 말합니다. 향후에도 장기간 이익을 훼손하지 않고 고수익을 유지할 수 있어 꾸준한 주가 상승의 원동력이 됩니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ', 
		'moat_4'	=> '워렌 버핏이 강조하는 경제적해자가 넓은 기업입니다. 오랜기간 안정적으로 고수익을 달성한 기업으로 미래에도 현재의 수익을 유지할 가능성이 높은 기업입니다. 별점이 5점에서 4점으로 낮아졌다면 사업독점력이 약간 훼손됐다는 것을 의미하며, 0 ~ 3점에서 4점으로 올랐으면 사업독점력이 강화돼 긍정적인 신호로 해석합니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다.', 
		'moat_3'	=> '워렌 버핏이 강조하는 경제적해자가 보통인 기업입니다. 장기간 산업 평균 수준의 수익성을 유지해온 기업입니다. 별점이 작년 대비 낮아지는 추세라면 산업내 경쟁력이 약화되고 있다는 것을 말합니다. 이는 장기적으로 주가 상승의 걸림돌이 되기 때문에 꼼꼼한 점검이 필요합니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ', 
		'moat_2'	=> '워렌 버핏이 강조하는 경제적해자가 좁은 기업입니다. 제품과 서비스의 경쟁력을 높이지 못하면 향후 수익성이 훼손될 가능성이 있습니다. 다만, 4차 산업 등 신규 산업에 속한 기업들은 업력이 상대적으로 짧아 현재 경쟁력 보다 약간 낮은 점수를 받을 수도 있습니다. 따라서 신규 산업에 속한 기업은 사업독점력외에 수익성장성, 현금창출력 등의 투자지표도 함께 참고해서 투자의사를 결정하는 것이 좋습니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ', 
		'moat_1'	=> '워렌 버핏이 강조하는 경제적해자가 거의 없는 기업입니다. 경기 변동에 따라 수익성이 크게 움직일 가능성이 높습니다. 호황에는 수익을 내더라도, 불황에는 수익이 크게 훼손될 수 있는 만큼 투자할때 더욱 꼼꼼히 살펴야 합니다. 상장 후 10년 이내의 기업이 아니라면 장기적으로 낮은 별점을 받은 기업은 투자 대상에서 제외하는 것이 좋습니다. 사업독점력은 장기간  ROE, 낮은 원가율, 영업활동 현금흐름, 연평균 매출 성장률 등을 종합해 평가합니다. ', 
		'safety_5'	=> '재무안전성이 매우 높은 기업입니다. 단기적으로 부도 위험이 없고, 불황이 닥쳐도 충분히 견딜 수 있는 우량한 재무구조를 갖춘 회사입니다. 재무안전성은 잃지 않는 투자를 위해 꼭 확인해야 할 항목입니다. 재무안전성이 높은 기업은  부도위험이 낮고, 불황이 닥쳐도 충분히 견딜수 있습니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다.', 
		'safety_4'	=> '재무안전성이 높은 기업입니다. 단기적으로 부도 위험이 낮은 편이며, 일반적인 기업에 비해 안전한 재무구조를 갖추고 있습니다. 미국 기업은 한국 기업에 비해 일반적으로 부채비율이 높기 때문에 이자보상배수, 현금흐름 등 여러가지 재무안전성 지표를 함께 살펴보는 것이 좋습니다.  ', 
		'safety_3'	=> '재무안전성이 보통인 기업입니다. 상장기업의 평균적인 재무안전성을 갖춘 회사입니다. 재무위험이 높지는 않지만 순이익이 크게 줄거나 현금흐름이 나빠지면 부정적인 신호로 해석합니다.', 
		'safety_2'	=> '재무안전성이 낮은 기업입니다. 순이익이 줄거나 현금흐름이 더 악화되면 단기적인 자금 부족이 나타날 수 있습니다. 재무안전성 별점이 낮아지고 있다면 재무안전성이 나빠지고 있는 신호입니다. 이 경우 투자 후보 대상 기업에서 제외하는 것이 좋습니다. ', 
		'safety_1'	=> '재무안전성이 매우 낮은 기업입니다. 잃지 않는 투자를 지향하는 투자자라면 투자 대상에서 제외하는 것이 좋습니다. 유상증자나 인수합병(M&A) 뉴스 등을 통해 주가가 급등하기도 하지만 행운에 기댄 투자보다는 합리적인 투자가 필요합니다.  ', 
		'growth_5'	=> '수익성장성이 매우 높은 기업입니다. 순이익이 전년 동기 대비 고성장(25% 이상)을 기록했고, 최근 5~6년간 연평균 15% 이상의 꾸준한 성장을 기록한 최고의 성장주입니다. 고 성장주를 찾는 투자자에게 가장 매력적인 기업입니다. 별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업만이 받았습니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.', 
		'growth_4'	=> '수익성장성이 높은 기업입니다. 순이익이 전년 동기 대비 15% 이상 성장했고, 이 성장률을 향후 4년 간 유지하면 순이익을 현재 대비 약 2배 이상 늘릴 수 있는 성장기업입니다. 성장주를 찾는 투자자라면 관심을 가져야 할 기업입니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.', 
		'growth_3'	=> '수익성장성이 보통인 기업입니다. 순이익이 전년 동기 대비 7% 이상 성장했지만 다른 고 성장기업에 비하면 성장률이 낮은 편입니다. 매출과 이익이 다시 성장할 수 있는 제품(서비스) 라인업이 있는지 확인 후 투자하는 것이 좋습니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.', 
		'growth_2'	=> '수익성장성이 낮은 기업입니다. 순이익이 전년 동기 대비 증가했지만 상승률이 7% 이하로 성장주를 찾는 투자자에겐 매력이 낮은 회사입니다. 순이익 성장률 둔화가 이번 분기에 일시적인 상황인지 제품(서비스) 경쟁력 약화로 순이익 성장이 한계에 달한 것인지를 파악하는 게 중요합니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.', 
		'growth_1'	=> '순이익이 전년 동기 대비 감소한 회사입니다. 순이익의 성장은 주가 상승의 원동력입니다. 이익 성장이 없는 주가 상승은 모래 위에 성을 쌓는 것과 같아 언젠가는 무너지고 맙니다. 순이익이 성장하는 다른 기업을 찾는 것이 좋겠습니다. 수익성장성은 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.', 
		'cashflow_5'	=> '현금창출력이 가장 우수한 기업입니다. 현금은 기업의 혈액과도 같은 역할을 합니다. 현금은 기업의 재무 구조를 개선시키고, 성장을 위한 투자 자금으로 사용합니다. 또한, 주주를 위한 배당, 자사주 매입에도 사용할 수 있기 때문에 주주가치를 높이는데도 활용할 수 있습니다.별점 5점은 미국 주식 #TOTAL#개 중 상위 #RATE#%인 #HIGH#개 기업이 받았습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.', 
		'cashflow_4'	=> '현금창출력이 우수한 기업입니다. 기업의 영업, 투자, 재무활동을 위한 현금흐름에 거의 문제가 없습니다. 영업활동 현금흐름이 꾸준한 플러스(+)를 유지하고 있어, 이를 바탕으로 성장을 위한 투자나 배당 등을 꾸준히 지급하고 있습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.', 
		'cashflow_3'	=> '현금창출력이 보통인 기업입니다. 영업활동 현금흐름이 플러스(+)를 기록하고 있어, 일반적으로 현금흐름에 문제는 없는 편입니다. 다만, 순이익이 크게 줄거나 적자로 전환할 경우 단기적인 현금흐름이 나빠질 수 있으니 부채비율, 이자보상배율 등 재무안전성도 함께 확인하는 것이 좋습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.', 
		'cashflow_2'	=> '현금창출력이 낮은 기업입니다. 회사의 영업활동 현금흐름이 향후 플러스(+)를 유지할 수 있는 지 확인이 필요합니다. 영업활동 현금흐름의 출발점은 순이익입니다. 순이익이 적자를 기록하면 영업활동 현금흐름은 일반적으로 마이너스(-)를 기록합니다. 지난 분기 대비 현금창출력 점수가 낮아졌다면 좀 더 꼼꼼히 기업의 재무 안전성을 체크해 보는 것이 좋습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.', 
		'cashflow_1'	=> '현금창출력이 매우 낮은 기업입니다. 순이익이 흑자를 기록하더라도 영업활동 현금흐름이 계속 마이너스(-)로 나타나면, 현금 부족에 따른 재무구조 악화와 부실이 발생합니다. 이는 기업가치와 주가 하락으로 이어집니다. 이 기업에 대한 현금흐름 개선 등 미래 실적 추정에 대한 확신이 없다면 가급적 투자는 피하는 것이 좋습니다. 현금창출력은 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR) 등을 종합해 평가합니다.'
	); 

	function get_spcomment($item, $param=array()) {
		$comment = $this->spider_comment[$item];
		$comment = str_replace('#TOTAL#',number_format($param['total']),$comment);
		$comment = str_replace('#RATE#',$param['high_rate'],$comment);
		$comment = str_replace('#HIGH#',number_format($param['high']),$comment);
		return $comment;
	}


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
}
