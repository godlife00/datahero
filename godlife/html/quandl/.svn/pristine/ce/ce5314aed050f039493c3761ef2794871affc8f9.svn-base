<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once $_SERVER['DOCUMENT_ROOT'].'/_application/controllers/base_pc.php';

class Mypage extends BasePC_Controller{
	public function __construct() {
		parent::__construct();
        $this->loginCheck();
		$this->load->model('quandl/myitem_tb_model');
        $this->load->library('pagination');
	}

	function mylist($page = '1') {

        if($page == null){
            $page = 1;
        }

        // 리스트 데이터 호출 설정
        $PAGE_ARTICLE_ROW = 10;
        $PAGE_OFFSET = ($page - 1) * $PAGE_ARTICLE_ROW ;
        $TOTAL_COUNT = count($this->myitem_tb_model->get_mylist( $PAGE_ARTICLE_ROW, $PAGE_OFFSET, 'C' ));

		$list = $this->myitem_tb_model->get_mylist( $PAGE_ARTICLE_ROW, $PAGE_OFFSET, 'D' );

		$arr_item_info = array();
		foreach( $list as $nKey=>$nVal) {
			$arr_item_info[] = $nVal['my_ticker'];
			$arr_item_info_snd[$nVal['my_ticker']] = $this->historylib->getTickerDailyLastRow($nVal['my_ticker']);
			$arr_item_info_thi[$nVal['my_ticker']] = $this->_getBaseData_roe($nVal['my_ticker']);
		}

		$my_ticker_price_map = $this->sep_tb_model->getTickersPrice($arr_item_info);

        // 페이징
        $config['base_url'] = '/member/mypage/mylist/';
        $config['total_rows'] = $TOTAL_COUNT;
        $config['per_page'] = $PAGE_ARTICLE_ROW;
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;

        $config['first_link'] = '<<'; //'<img src="/img/arw_first.gif" alt="">';
        $config['last_link'] = '>>'; //'<img src="/img/arw_last.gif" alt="">';
        $config['prev_link'] = '<';//'<img src="/img/arw_prev.gif" alt="">';
        $config['next_link'] = '>';//'<img src="/img/arw_next.gif" alt="">';
        $config['cur_tag_open'] = '<a href=""><strong>';
        $config['cur_tag_close'] = '</strong></a>';
        $config['num_links']=5;

        $this->pagination->initialize($config);
        $paging_html = $this->pagination->create_links();


		//인기종목
        $shuffle_ticker_codes = array(
                'MSFT', 'AAPL', 'GOOGL', 'AMZN', 'NFLX', 
                'DIS', 'UBER', 'TSLA', 'NVDA', 'AMD', 
                'SBUX', 'KO', 'MCD', 'NKE', 'INTC', 
                'FB', 'BABA', 'CSCO', 'BA', 'BRK.B', 
                'JNJ', 'WMT', 'BIDU', 'IBM', 'GM', 
                'WMT', 'GS', 'TWTR', 'ATVI', 'MMM'
        );
        shuffle($shuffle_ticker_codes);
        $popular_ticker_codes = array_slice($shuffle_ticker_codes, 0, 5);
		$popular_ticker_price_map = $this->sep_tb_model->getTickersPrice($popular_ticker_codes);
		

        // @ 급등종목 -->  가격정보 필요. display_tkckers에 담기.
        $top_plus_ticker_codes = $this->sep_tb_model->getTopPlusTickers(20);
		$top_ticker_price_map = $this->sep_tb_model->getTickersPrice($top_plus_ticker_codes);
		
		$data = array(
	   		'list'=>$list, 
	   		'my_ticker_info'=>$my_ticker_price_map, 
	   		'my_ticker_info_snd'=>$arr_item_info_snd, 
	   		'my_ticker_info_thi'=>$arr_item_info_thi, 
	   		'popular_ticker'=>$popular_ticker_price_map, 
	   		'top_ticker'=>$top_ticker_price_map, 
	   		'ticker_korean_map'=>$this->ticker_korean_map, 
            'paging_html' => $paging_html
		);

		$this->_view('member/mypage', $data);
	}

	function update_item() {
        $ticker = $this->input->post('ticker');
		if(!$ticker) {
			return;
		}
		else {
			$this->myitem_tb_model->update_mylist($ticker);
			$this->make_myticker();
		}

        redirect('/member/mypage/mylist');
		return;
	}

	private function _getBaseData_roe($ticker_code, $dimension='MRT', $cell_type='data', $pExtra=array()) {
		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRT';
		}
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		$pExtra = array(
			'with_summary' => true
		);

		if($cell_type == 'data') {
			$this->historylib->getFinStateList($ticker_code, $dimension, $pExtra);
		} else {
			// cell_type == ratio
			$this->historylib->getFinStateRatioList($ticker_code, $dimension, $pExtra);
		}

		if( ! $this->historylib->isSuccess()) {
			echo $this->historylib->getErrorMsg();
			return;
		}

		$result = $this->historylib->getData();
		$ticker = array_shift($result['last_mrt']);

		return $ticker;
	}
}
?>