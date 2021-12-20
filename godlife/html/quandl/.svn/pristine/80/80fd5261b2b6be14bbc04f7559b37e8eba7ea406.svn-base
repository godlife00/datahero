<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indicator_tb_model extends MY_Model {

	protected $pk = 'idc_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'idc_table' 		=> 'idc_table value is empty.',
		'idc_indicator' 		=> 'idc_indicator value is empty.',
		'idc_isfilter' 		=> 'idc_isfilter value is empty.',
		'idc_isprimarykey' 		=> 'idc_isprimarykey value is empty.',
		'idc_title' 		=> 'idc_title value is empty.',
		/*
		'idc_description' 		=> 'idc_description value is empty.',
		'idc_unittype' 		=> 'idc_unittype value is empty.',
		*/
		'idc_created_at' 		=> 'idc_created_at value is empty.',
		'idc_updated_at' 		=> 'idc_updated_at value is empty.'
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
		$params['idc_created_at'] = date('Y-m-d H:i:s');
		$params['idc_updated_at'] = date('Y-m-d H:i:s');

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
