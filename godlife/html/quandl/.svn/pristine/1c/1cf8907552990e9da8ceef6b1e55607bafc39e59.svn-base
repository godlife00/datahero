<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sf2_tb_model extends MY_Model {

	protected $pk = 'sf2_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'sf2_ticker' 		=> 'sf2_ticker value is empty.',
		'sf2_filingdate' 		=> 'sf2_filingdate value is empty.',
		'sf2_formtype' 		=> 'sf2_formtype value is empty.',
		'sf2_issuername' 		=> 'sf2_issuername value is empty.',
		'sf2_ownername' 		=> 'sf2_ownername value is empty.',
		/*
		'sf2_officertitle' 		=> 'sf2_officertitle value is empty.',
		'sf2_isdirector' 		=> 'sf2_isdirector value is empty.',
		'sf2_isofficer' 		=> 'sf2_isofficer value is empty.',
		'sf2_istenpercentowner' 		=> 'sf2_istenpercentowner value is empty.',
		'sf2_transactiondate' 		=> 'sf2_transactiondate value is empty.',
		'sf2_securityadcode' 		=> 'sf2_securityadcode value is empty.',
		'sf2_transactioncode' 		=> 'sf2_transactioncode value is empty.',
		'sf2_sharesownedbeforetransaction' 		=> 'sf2_sharesownedbeforetransaction value is empty.',
		'sf2_transactionshares' 		=> 'sf2_transactionshares value is empty.',
		'sf2_sharesownedfollowingtransaction' 		=> 'sf2_sharesownedfollowingtransaction value is empty.',
		'sf2_transactionpricepershare' 		=> 'sf2_transactionpricepershare value is empty.',
		'sf2_transactionvalue' 		=> 'sf2_transactionvalue value is empty.',
		'sf2_securitytitle' 		=> 'sf2_securitytitle value is empty.',
		'sf2_directorindirect' 		=> 'sf2_directorindirect value is empty.',
		'sf2_natureofownership' 		=> 'sf2_natureofownership value is empty.',
		'sf2_dateexercisable' 		=> 'sf2_dateexercisable value is empty.',
		'sf2_priceexercisable' 		=> 'sf2_priceexercisable value is empty.',
		'sf2_expirationdate' 		=> 'sf2_expirationdate value is empty.',
		'sf2_rownum' 		=> 'sf2_rownum value is empty.',
		*/
		'sf2_created_at' 		=> 'sf2_created_at value is empty.',
		'sf2_updated_at' 		=> 'sf2_updated_at value is empty.'
		);

	// ENUM 필드마다 가질 수 있는 값들을 KEY => VALUE 형태의 배열로 정의. 각 모델에서 재정의
	protected $enumcheck_keys = array(

		);

	function __construct() {
		parent::__construct();
		$this->db_name = array_pop(explode('/', dirname(__FILE__)));
		$this->table = strtolower(substr(__CLASS__,0,-6));
		$this->fields = $this->db->list_fields($this->table);
	}

	protected function __filter($params) {
		$params['sf2_created_at'] = date('Y-m-d H:i:s');
		$params['sf2_updated_at'] = date('Y-m-d H:i:s');

		return $params;
	}

	protected function __validate($params) {
		$success = parent::__validate($params);

		if($success == true) {
			// emptycheck_keys, enumcheck_keys 외 추가로 검사할 부분이 있으면
			// 여기에서 검사. 데이터에 문제 발견시

			// $this->setErrorResult("문제발견 내용");
			// return false;

			// 형태로 정의할것.

		}
		return $success;
	}
}

?>
