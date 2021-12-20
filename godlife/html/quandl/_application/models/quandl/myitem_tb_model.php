<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myitem_tb_model extends MY_Model {

	protected $pk = 'my_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'my_userid'		=> 'my_userid value is empty.',
		'my_ticker'		=> 'my_ticker value is empty.',
		'my_usname'		=> 'my_usname value is empty.',
		'my_korname'	=> 'my_korname value is empty.',
		'my_fdate' 		=> 'my_fdate value is empty.',
		'my_udate' 		=> 'my_udate value is empty.',
		'my_like' 		=> 'my_like value is empty.'
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
		$params['my_fdate'] = date('Y-m-d H:i:s');
		$params['my_udate'] = date('Y-m-d H:i:s');

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

	function get_like_info($user_id, $ticker) {
        $res = $this->db->get_where('myitem_tb',array('my_userid'=>$user_id, 'my_ticker'=>$ticker))->row();
        return $res;
	}

	function set_like_info($user_id, $ticker) {

		$res = $this->get_like_info($user_id, $ticker);
		if($res) {
			if($res->my_like=='Y') {
				$like='N';
			}
			else {
				$like='Y';
			}

			$data = array (
				'my_udate' => date('Y-m-d H:i:s'),
				'my_like' => $like
			);
			$this->db->where('my_userid', $user_id);
			$this->db->where('my_ticker', $ticker);
			$result = $this->db->update('myitem_tb', $data);
		}
		else {
			$like='Y';

			$res = $this->get_ticker_info($ticker);
			if($res) {
				$my_usname = $res->cp_usname;
				$my_korname = $res->cp_korname;
			}

			$data = array (
				'my_userid' => $user_id,
				'my_ticker' => $ticker,
				'my_usname' => $my_usname,
				'my_korname' => $my_korname,
				'my_fdate' => date('Y-m-d H:i:s'),
				'my_udate' => date('Y-m-d H:i:s'),
				'my_like' => $like
			);

			$this->db->insert('myitem_tb', $data);
		}

		return $like;
	}

	function get_ticker_info($ticker) {
		$res = $this->db->get_where('company_tb',array('cp_ticker'=>$ticker))->row();
        return $res;
	}

	function get_mylist($limit, $offset, $func) {

		$user_id = $this->session->userdata('user_id');

		$this->db->select('*');
		$this->db->from('myitem_tb');
		$this->db->where('my_userid', $user_id);
		$this->db->where('my_like', 'Y');

		if($func == 'C') {
			$query = $this->db->get('');
		}
		else {
			$this->db->order_by('my_udate', 'desc');
			$query = $this->db->get('', $limit, $offset);
		}

		return $query->result_array();
	}

	function update_mylist($ticker) {

		if($ticker) {
			$user_id = $this->session->userdata('user_id');

			$data = array (
				'my_udate' => date('Y-m-d H:i:s'),
				'my_like' => 'N'
			);
			$this->db->where('my_userid', $user_id);
			$this->db->where('my_ticker', $ticker);
			$result = $this->db->update('myitem_tb', $data);
			return $result;
		}
	}

	function set_opinion_info($user_id, $ticker, $opt) {

		$res = $this->get_opinion_info($user_id, $ticker);

		if(isset($res['my_opinion']->mo_opinion)) {

			if($res['my_opinion']->mo_opinion != $opt) {

				$data = array (
					'mo_udate' => date('Y-m-d H:i:s'),
					'mo_opinion' => $opt
				);
				$this->db->where('mo_userid', $user_id);
				$this->db->where('mo_ticker', $ticker);
				$this->db->update('myopinion_tb', $data);
			}
		}
		else {

			$data = array (
				'mo_userid' => $user_id,
				'mo_ticker' => $ticker,
				'mo_fdate' => date('Y-m-d H:i:s'),
				'mo_udate' => date('Y-m-d H:i:s'),
				'mo_opinion' => $opt
			);

			$this->db->insert('myopinion_tb', $data);
		}

		$res = $this->get_opinion_info($user_id, $ticker);
		return $res;
	}

	function get_opinion_info($user_id, $ticker) {
        $res = $this->db->get_where('myopinion_tb',array('mo_userid'=>$user_id, 'mo_ticker'=>$ticker))->row();

		$time = time();
		$q_month = date("Ymd",strtotime("-3 month", $time));

		$sell_count = 0;
        $this->db->from('myopinion_tb');
        $this->db->where('mo_ticker', $ticker);
        $this->db->where('mo_opinion', 'S');
		$this->db->where('date_format(mo_udate, "%Y%m%d") >=', $q_month, FALSE);
        $this->db->select('COUNT(*)');
        $sell_count = $this->db->count_all_results();

		$buy_count = 0;
        $this->db->from('myopinion_tb');
        $this->db->where('mo_ticker', $ticker);
        $this->db->where('mo_opinion', 'B');
		$this->db->where('date_format(mo_udate, "%Y%m%d") >=', $q_month, FALSE);
        $this->db->select('COUNT(*)');
        $buy_count = $this->db->count_all_results();

		$result = array();
		$result['my_opinion'] = $res;
		$result['sell_opinion'] = $sell_count;
		$result['buy_opinion'] = $buy_count;		

        return $result;
	}

	function get_opinion_list() {
		$this->db->order_by('mo_udate', 'desc');
		$this->db->limit(10);
		$query = $this->db->get('myopinion_tb');

		return $query->result_array();
	}

	function get_opinion_count($ticker, $opt) {
		
		$time = time();
		$q_month = date("Ymd",strtotime("-3 month", $time));

        $this->db->from('myopinion_tb');
        $this->db->where('mo_ticker', $ticker);
        $this->db->where('mo_opinion', $opt);
		$this->db->where('date_format(mo_udate, "%Y%m%d") >=', $q_month, FALSE);
        $this->db->select('COUNT(*)');
        return $this->db->count_all_results();
	}
}
?>
