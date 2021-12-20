<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'/base_pc.php';
class Guide extends BasePC_Controller{
	public function __construct() {
		parent::__construct();
	}

    public function page($view){

        $header = array();
        $header['sess_data'] = $this->session->all_userdata();
        $header['search_ticker_list'] = $this->search_ticker_list;
        $header['current_ticker_info'] = $this->current_ticker_info;
        $header['header_data'] = $this->header_data;
		$header['header_class'] = 'serviceGuide';

        $data = array();
        $footer = array();

		$this->load->view('/pc/inc/header.php', $header);
		$this->load->view('/pc/guide/'.$view.'.php', $data); 
		$this->load->view('/pc/inc/footer.php', $footer);
    }
}
?>