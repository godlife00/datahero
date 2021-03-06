<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base_mobile.php';
class Attractiveness extends BaseMobile_Controller {

    function __construct() {
        parent::__construct();
        //$this->loginCheck();
	    //$this->paramCheck();
    }
    public function index() {
        $this->common->locationhref('/attractiveness/attractive');

    }

    public function attractive() {
        $this->load->model(DBNAME.'/mri_tb_model');
        $data = array();

        // 검색필터 맵 정의
        
        // 시가총액
        $marketcap_map = array(
            'all' => '전체',
            'under3billion' => '3억달러 미만',
            'under20billion' => '3억달러~20억달러 미만',
            'under100billion' => '20억달러~100억달러 미만',
            'over100billion' => '100억달러 이상',
        );

        // 순이익(연환산)
        $netincome_map = array(
            'all' => '전체',
            'up' => '흑자기업', 
            'down' => '적자기업',
        );

        // 정렬
        $sort_map = array(
            'total' => '종합',
            'dividend' => '배당<br>매력',
            'moat' => '사업<br>독점력',
            'safety' => '재무<br>안전성',
            'growth' => '수익<br>성장성',
            'cashflow' => '현금<br>창출력',
        );
        $data['marketcap_map'] = $marketcap_map;
        $data['netincome_map'] = $netincome_map;
        $data['sort_map'] = $sort_map;

        $result = $this->_get_attractive_data();
        $data['marketcap'] = $result['marketcap'];
        $data['netincome'] = $result['netincome'];
        $data['sort'] = $result['sort'];
        $data['content_html'] = $this->load->view('/mobile/attractiveness/attractive_list.php', array('list' => $result['list']), true);

        $this->header_data['header_template'] = '7';
        $this->header_data['head_title'] = '전종목 투자 매력도';

		$data['meta_title'] = '투자매력도 - 초이스스탁US';

		$data['is_event'] = $this->is_event;
        $this->_view('/attractiveness/attractive', $data);
    }

    public function ajax_get_attractive_list() {
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $result = $this->_get_attractive_data();
        $content_html = $this->load->view('/mobile/attractiveness/attractive_list.php', array('list' => $result['list']), true);
        echo $content_html;
        return;
    }

    private function _get_attractive_data() {
        $this->load->model(DBNAME.'/mri_tb_model');

        $request = $this->input->get();

        $limit = 50;
        $page = (isset($request['page']) && strlen($request['page']) > 0) ? $request['page'] : '1';

		if($page>1 && $this->session->userdata('is_paid') === FALSE ) {
			return;
		}

        $add_params = array();
        $add_params['join']['daily_tb'] = 'dly_ticker = tkr_ticker';

        $add_extra = array(
            'offset' => $limit * ($page-1),
        );


        // 시가총액
        $marketcap = (isset($request['marketcap']) && strlen($request['marketcap']) > 0) ? $request['marketcap'] : 'all';
        switch($marketcap) {
            case 'all': // 전체
                break;
            case 'under3billion': // 3억달러 미만
                $add_params['<']['dly_marketcap'] = '300';
                break;
            case 'under20billion': // 3억달러~20억달러 미만
                $add_params['>=']['dly_marketcap'] = '300';
                $add_params['<']['dly_marketcap'] = '2000';
                break;
            case 'under100billion': // 20억달러~100억달러 미만
                $add_params['>=']['dly_marketcap'] = '2000';
                $add_params['<']['dly_marketcap'] = '10000';
                break;
            case 'over100billion': // 100억달러 이상
                $add_params['>=']['dly_marketcap'] = '10000';
                break;
        }


        // 순이익(연환산)
        $netincome = (isset($request['netincome']) && strlen($request['netincome']) > 0) ? $request['netincome'] : 'all';
        switch($netincome) {
            case 'all':
                break;
            case 'up': // 흑자기업
                $add_params['>']['sf1_netinccmnusd'] = '0';
                break;
            case 'down': // 적자기업
                $add_params['<=']['sf1_netinccmnusd'] = '0';
                break;
        }
        // 정렬
        $sort = (isset($request['sort']) && strlen($request['sort']) > 0) ? $request['sort'] : 'total';
        switch($sort) {
            case 'total': // 종합
                $add_extra['order_by'] = 'm_biz_total_score desc, m_g_roe desc';
                break;
            case 'dividend': // 배당 매력
                $add_extra['order_by'] = 'm_biz_dividend_score desc, sf1_divyield desc';
                break;
            case 'moat': // 사업 독점력
				$add_params['join']['sf1_tb'] = 'sf1_ticker = tkr_ticker and sf1_dimension = "MRT"';
				$add_extra['order_by'] = 'm_biz_moat_score desc, sf1_opmargin desc';
                break;
            case 'safety': // 재무 안전성
                $add_extra['order_by'] = 'm_biz_safety_score desc, m_marketcap desc';
                break;
            case 'growth': // 수익 성장성
				$add_params['join']['sf1_tb'] = 'sf1_ticker = tkr_ticker and sf1_dimension = "MRT"';
                $add_extra['order_by'] = 'm_biz_growth_score desc, m_g_epsgr desc';
                break;
            case 'cashflow': // 현금 창출력
                $add_extra['order_by'] = 'm_biz_cashflow_score desc, m_marketcap desc';
                break;
        }
//echo '<pre>'; print_r($add_params);
        $list = $this->mri_tb_model->getRecomStockList('total_score', $limit, $add_params, $add_extra);

        $result = array();
        $result['marketcap'] = $marketcap;
        $result['netincome'] = $netincome;
        $result['sort'] = $sort;
        $result['list'] = $list;
        return $result;
    }

}
 
