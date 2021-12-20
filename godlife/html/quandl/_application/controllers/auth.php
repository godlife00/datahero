<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'/base_pc.php';
class Auth extends BasePC_Controller{
	public function __construct() {
		parent::__construct();
	}

	function login() {
		if($this->session->userdata('is_login')) {
			redirect('/');
			return;
		}else {
			$pageUrl = $this->input->get('rd');

			$rd = 'http://us.itooza.com/auth/authentiation?rd='.$pageUrl;
			$rd = $this->ubase64_encode($rd);
			redirect('https://login.itooza.com/login.htm?qurl64='.$rd);
		}
	}

	function failLogin() {
		$this->session->set_flashdata('error_msg', '아이디와 패스워드를 확인해 주세요.');
		redirect('/auth/login');
		exit;
	}

	function authentiation() {
		$strUKey = get_cookie('UniqueKey');

		if($strUKey!='') {

			$strLoginURL = 'http://www.itooza.com/common/api_externsession.php?ssk=' . $strUKey;
			$strGetLoginData = iconv('EUC-KR', 'UTF-8', $this->get_content($strLoginURL));	
			//$strGetLoginData = file_get_contents($strLoginURL);
			$strArrLoginData = json_decode($strGetLoginData, true);
			
			if( $strArrLoginData['cklogin'] == 'OK' ) {
				$this->_set_session($strArrLoginData);

				$pageUrl = $this->input->get('rd');

				if($pageUrl) {
					redirect($pageUrl);
				}
				else {
					redirect('/');
				}
			}
			else {
				$this->failLogin();
			}
		}
		else {
			$this->failLogin();
		}
	}

	function logout() {
		$this->session->sess_destroy();
		delete_cookie('my_ticker');

		$strLogoutURL = 'http://www.itooza.com/common/logout_us.htm';
		redirect($strLogoutURL);

		exit;
	}

	function ubase64_encode( $src )
	{
		$desc = '';
		$desc = base64_encode( $src );
		$desc = str_replace('+', '|PLUS|', $desc);
		return( $desc );
	}

	function ubase64_decode( $src )
	{
		$desc = '';
		$desc = str_replace('|PLUS|', '+', $src);
		$desc = base64_decode( $desc );
		return( $desc );
	}
}
?>