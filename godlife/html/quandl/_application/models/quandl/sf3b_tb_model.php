<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sf3b_tb_model extends MY_Model {

	protected $pk = 'sf3b_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'sf3b_calendardate' 		=> 'sf3b_calendardate value is empty.',
		'sf3b_investorname' 		=> 'sf3b_investorname value is empty.',
		'sf3b_shrholdings' 		=> 'sf3b_shrholdings value is empty.',
		'sf3b_cllholdings' 		=> 'sf3b_cllholdings value is empty.',
		'sf3b_putholdings' 		=> 'sf3b_putholdings value is empty.',
		'sf3b_wntholdings' 		=> 'sf3b_wntholdings value is empty.',
		'sf3b_dbtholdings' 		=> 'sf3b_dbtholdings value is empty.',
		'sf3b_prfholdings' 		=> 'sf3b_prfholdings value is empty.',
		'sf3b_fndholdings' 		=> 'sf3b_fndholdings value is empty.',
		'sf3b_undholdings' 		=> 'sf3b_undholdings value is empty.',
		'sf3b_shrunits' 		=> 'sf3b_shrunits value is empty.',
		'sf3b_cllunits' 		=> 'sf3b_cllunits value is empty.',
		'sf3b_putunits' 		=> 'sf3b_putunits value is empty.',
		'sf3b_wntunits' 		=> 'sf3b_wntunits value is empty.',
		'sf3b_dbtunits' 		=> 'sf3b_dbtunits value is empty.',
		'sf3b_prfunits' 		=> 'sf3b_prfunits value is empty.',
		'sf3b_fndunits' 		=> 'sf3b_fndunits value is empty.',
		'sf3b_undunits' 		=> 'sf3b_undunits value is empty.',
		'sf3b_shrvalue' 		=> 'sf3b_shrvalue value is empty.',
		'sf3b_cllvalue' 		=> 'sf3b_cllvalue value is empty.',
		'sf3b_putvalue' 		=> 'sf3b_putvalue value is empty.',
		'sf3b_wntvalue' 		=> 'sf3b_wntvalue value is empty.',
		'sf3b_dbtvalue' 		=> 'sf3b_dbtvalue value is empty.',
		'sf3b_prfvalue' 		=> 'sf3b_prfvalue value is empty.',
		'sf3b_fndvalue' 		=> 'sf3b_fndvalue value is empty.',
		'sf3b_undvalue' 		=> 'sf3b_undvalue value is empty.',
		'sf3b_totalvalue' 		=> 'sf3b_totalvalue value is empty.',
		'sf3b_percentoftotal' 		=> 'sf3b_percentoftotal value is empty.',
		'sf3b_created_at' 		=> 'sf3b_created_at value is empty.',
		'sf3b_updated_at' 		=> 'sf3b_updated_at value is empty.'
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
		$params['sf3b_created_at'] = date('Y-m-d H:i:s');
		$params['sf3b_updated_at'] = date('Y-m-d H:i:s');

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
