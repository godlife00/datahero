<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recommend_tb_model extends MY_Model {

    protected $pk = 'rc_id';

    // NOT NULL 필드들에대한 정의. 각 모델에서 재정의
    protected $emptycheck_keys = array(

        'rc_ticker' 		=> 'rc_ticker value is empty.',
        'rc_invest_point' 		=> 'rc_invest_point value is empty.',
        'rc_event' 		=> 'rc_event value is empty.',
        'rc_recom_price' 		=> 'rc_recom_price value is empty.',
        'rc_giveup_price' 		=> 'rc_giveup_price value is empty.',
        'rc_goal_price' 		=> 'rc_goal_price value is empty.',
        'rc_display_date' 		=> 'rc_display_date value is empty.',
        //'rc_view_count' 		=> 'rc_view_count value is empty.',
        'rc_admin_id' 		=> 'rc_admin_id value is empty.',
        'rc_created_at' 		=> 'rc_created_at value is empty.',
        'rc_updated_at' 		=> 'rc_updated_at value is empty.'
        );

    // ENUM 필드마다 가질 수 있는 값들을 KEY => VALUE 형태의 배열로 정의. 각 모델에서 재정의
    protected $enumcheck_keys = array(

		);

    function __construct() {
		parent::__construct();
		//$this->db_name = array_pop(explode('/', dirname(__FILE__)));
		//$this->table = strtolower(substr(__CLASS__,0,-6));
		//$this->fields = $this->db->list_fields($this->table);
        $this->db = $this->load->database('wstreetdb', true);
		//$this->db_name = 'quandl';
		$this->table = strtolower(substr(__CLASS__,0,-6));
		$this->fields = $this->db->list_fields($this->table);
    }

    protected function __filter($params) {
        //$params['rc_created_at'] = date('Y-m-d H:i:s');
        //$params['rc_updated_at'] = date('Y-m-d H:i:s');
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
