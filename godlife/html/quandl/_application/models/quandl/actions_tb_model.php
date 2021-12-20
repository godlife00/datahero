<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Actions_tb_model extends MY_Model {

	protected $pk = 'act_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'act_date' 		=> 'act_date value is empty.',
		'act_action' 		=> 'act_action value is empty.',
		'act_ticker' 		=> 'act_ticker value is empty.',
		'act_name' 		=> 'act_name value is empty.',
		'act_value' 		=> 'act_value value is empty.',
		'act_contraticker' 		=> 'act_contraticker value is empty.',
		'act_contraname' 		=> 'act_contraname value is empty.',
		'act_created_at' 		=> 'act_created_at value is empty.',
		'act_updated_at' 		=> 'act_updated_at value is empty.'
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
		$params['act_created_at'] = date('Y-m-d H:i:s');
		$params['act_updated_at'] = date('Y-m-d H:i:s');

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
