<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mriall_tb_model extends MY_Model {

	protected $pk = 'm_id';

	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'm_ticker'		=> 'm_ticker value is empty.',
		'm_usname'		=> 'm_usname value is empty.',
		'm_korname'		=> 'm_korname value is empty.',
		'm_exchange'	=> 'm_exchange value is empty.',
		'm_growth_score' 		=> 'm_growth_score value is empty.',
		'm_growth_stars' 		=> 'm_growth_stars value is empty.',
		'm_safety_score' 		=> 'm_safety_score value is empty.',
		'm_safety_stars' 		=> 'm_safety_stars value is empty.',
		'm_cashflow_score' 		=> 'm_cashflow_score value is empty.',
		'm_cashflow_stars' 		=> 'm_cashflow_stars value is empty.',
		'm_moat_score' 		=> 'm_moat_score value is empty.',
		'm_moat_stars' 		=> 'm_moat_stars value is empty.',
		'm_valuation_score' 		=> 'm_valuation_score value is empty.',
		'm_valuation_stars' 		=> 'm_valuation_stars value is empty.',
		'm_total_score' 		=> 'm_total_score value is empty.',
		'm_date' 		=> 'm_date value is empty.'
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
		//$params['my_fdate'] = date('Y-m-d H:i:s');
		//$params['my_udate'] = date('Y-m-d H:i:s');

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
