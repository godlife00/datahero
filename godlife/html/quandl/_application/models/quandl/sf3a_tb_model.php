<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sf3a_tb_model extends MY_Model {

	protected $pk = 'sf3a_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'sf3a_calendardate' 		=> 'sf3a_calendardate value is empty.',
		'sf3a_ticker' 		=> 'sf3a_ticker value is empty.',
		'sf3a_name' 		=> 'sf3a_name value is empty.',
		'sf3a_shrholders' 		=> 'sf3a_shrholders value is empty.',
		'sf3a_cllholders' 		=> 'sf3a_cllholders value is empty.',
		'sf3a_putholders' 		=> 'sf3a_putholders value is empty.',
		'sf3a_wntholders' 		=> 'sf3a_wntholders value is empty.',
		'sf3a_dbtholders' 		=> 'sf3a_dbtholders value is empty.',
		'sf3a_prfholders' 		=> 'sf3a_prfholders value is empty.',
		'sf3a_fndholders' 		=> 'sf3a_fndholders value is empty.',
		'sf3a_undholders' 		=> 'sf3a_undholders value is empty.',
		'sf3a_shrunits' 		=> 'sf3a_shrunits value is empty.',
		'sf3a_cllunits' 		=> 'sf3a_cllunits value is empty.',
		'sf3a_putunits' 		=> 'sf3a_putunits value is empty.',
		'sf3a_wntunits' 		=> 'sf3a_wntunits value is empty.',
		'sf3a_dbtunits' 		=> 'sf3a_dbtunits value is empty.',
		'sf3a_prfunits' 		=> 'sf3a_prfunits value is empty.',
		'sf3a_fndunits' 		=> 'sf3a_fndunits value is empty.',
		'sf3a_undunits' 		=> 'sf3a_undunits value is empty.',
		'sf3a_shrvalue' 		=> 'sf3a_shrvalue value is empty.',
		'sf3a_cllvalue' 		=> 'sf3a_cllvalue value is empty.',
		'sf3a_putvalue' 		=> 'sf3a_putvalue value is empty.',
		'sf3a_wntvalue' 		=> 'sf3a_wntvalue value is empty.',
		'sf3a_dbtvalue' 		=> 'sf3a_dbtvalue value is empty.',
		'sf3a_prfvalue' 		=> 'sf3a_prfvalue value is empty.',
		'sf3a_fndvalue' 		=> 'sf3a_fndvalue value is empty.',
		'sf3a_undvalue' 		=> 'sf3a_undvalue value is empty.',
		'sf3a_totalvalue' 		=> 'sf3a_totalvalue value is empty.',
		'sf3a_percentoftotal' 		=> 'sf3a_percentoftotal value is empty.',
		'sf3a_created_at' 		=> 'sf3a_created_at value is empty.',
		'sf3a_updated_at' 		=> 'sf3a_updated_at value is empty.'
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
		$params['sf3a_created_at'] = date('Y-m-d H:i:s');
		$params['sf3a_updated_at'] = date('Y-m-d H:i:s');

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
