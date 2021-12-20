<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_tb_model extends MY_Model {

	protected $pk = 'cp_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		//'cp_exchange' 		=> 'cp_exchange value is empty.',
		'cp_ticker' 		=> 'cp_ticker value is empty.',
		//'cp_usname' 		=> 'cp_usname value is empty.',
		//'cp_korname' 		=> 'cp_korname value is empty.',
		//'cp_short_description' 		=> 'cp_short_description value is empty.',
		//'cp_description' 		=> 'cp_description value is empty.',
		'cp_created_at' 		=> 'cp_created_at value is empty.',
		'cp_updated_at' 		=> 'cp_updated_at value is empty.'
		);

	// ENUM 필드마다 가질 수 있는 값들을 KEY => VALUE 형태의 배열로 정의. 각 모델에서 재정의
	protected $enumcheck_keys = array(

		);

	protected $exchange_types = array(
		'NASDAQ' => 'NASDAQ',
		'NYSE' => 'NYSE',
		'BATS' => 'BATS',
		'IEX' => 'IEX',
		'INDEX' => 'INDEX',
		'NYSEARCA' => 'NYSEARCA',
		'NYSEMKT' => 'NYSEMKT',
		'OTC' => 'OTC',
	);
	public function getExchangeTypes() {
		return $this->exchange_types;
	}

	function __construct() {
		parent::__construct();
		$this->db_name = array_pop(explode('/', dirname(__FILE__)));
		$this->table = strtolower(substr(__CLASS__,0,-6));
		$this->fields = $this->db->list_fields($this->table);
	}

	protected function __filter($params) {
		if( ! isset($params['cp_created_at'])) {
			$params['cp_created_at'] = date('Y-m-d H:i:s');
			$params['cp_updated_at'] = date('Y-m-d H:i:s');
		}
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
