<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ticker_tb_model extends MY_Model {

	protected $pk = 'tkr_id';


	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'tkr_table' 		=> 'tkr_table value is empty.',
		'tkr_permaticker' 		=> 'tkr_permaticker value is empty.',
		'tkr_ticker' 		=> 'tkr_ticker value is empty.',
		'tkr_name' 		=> 'tkr_name value is empty.',
		'tkr_exchange' 		=> 'tkr_exchange value is empty.',
		/*
		'tkr_isdelisted' 		=> 'tkr_isdelisted value is empty.',
		'tkr_category' 		=> 'tkr_category value is empty.',
		'tkr_cusips' 		=> 'tkr_cusips value is empty.',
		'tkr_siccode' 		=> 'tkr_siccode value is empty.',
		'tkr_sicsector' 		=> 'tkr_sicsector value is empty.',
		'tkr_sicindustry' 		=> 'tkr_sicindustry value is empty.',
		'tkr_famasector' 		=> 'tkr_famasector value is empty.',
		'tkr_famaindustry' 		=> 'tkr_famaindustry value is empty.',
		'tkr_sector' 		=> 'tkr_sector value is empty.',
		'tkr_industry' 		=> 'tkr_industry value is empty.',
		'tkr_scalemarketcap' 		=> 'tkr_scalemarketcap value is empty.',
		'tkr_scalerevenue' 		=> 'tkr_scalerevenue value is empty.',
		'tkr_relatedtickers' 		=> 'tkr_relatedtickers value is empty.',
		'tkr_currency' 		=> 'tkr_currency value is empty.',
		'tkr_location' 		=> 'tkr_location value is empty.',
		'tkr_lastupdated' 		=> 'tkr_lastupdated value is empty.',
		'tkr_firstadded' 		=> 'tkr_firstadded value is empty.',
		'tkr_firstpricedate' 		=> 'tkr_firstpricedate value is empty.',
		'tkr_lastpricedate' 		=> 'tkr_lastpricedate value is empty.',
		'tkr_firstquarter' 		=> 'tkr_firstquarter value is empty.',
		'tkr_lastquarter' 		=> 'tkr_lastquarter value is empty.',
		'tkr_secfilings' 		=> 'tkr_secfilings value is empty.',
		'tkr_companysite' 		=> 'tkr_companysite value is empty.',
		*/
		'tkr_created_at' 		=> 'tkr_created_at value is empty.',
		'tkr_updated_at' 		=> 'tkr_updated_at value is empty.'
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

    public function convertSyncInfo($row) {
        if( ! isset($row['tkr_ticker'])) {
            return $row;
        }
        $item = $this->common->get_sync_info($row['tkr_ticker'], $row['tkr_lastpricedate']);
        if( !sizeof($item)) return $row;

        $row['tkr_close'] = $item['last_price'];
        $row['tkr_rate'] = $item['diff_rate'];
		date_default_timezone_set("America/New_York");
        $row['tkr_lastpricedate'] = date('Y-m-d H:i:s', $item['updated_at']);

        $item_str = $this->common->get_sync_text($row['tkr_ticker'], $row['tkr_lastpricedate']);
        $row['tkr_rate_str'] = $item_str['diff_rate'];
        $row['tkr_diff_str'] = $item_str['diff_price'];

        return $row;
    }

    // 서비스 되는 티커 기본 정보 조회
    public function getTickerList($ticker_codes) {
        if( ! is_array($ticker_codes)) {
            $ticker_codes = array_filter(array(trim($ticker_codes)));
        }
        if(sizeof($ticker_codes) <= 0) {
            return $ticker_codes;
        }

        $params = array();
        $params['in']['tkr_ticker'] = $ticker_codes;
        $params['=']['tkr_table'] = 'SEP';
        $params['=']['tkr_isdelisted'] = 'N';
        $params['!=']['tkr_exchange'] = 'OTC';

        $extra = array();
        $extra['order_by'] = '';

        return $this->common->getDataByPk($this->getList($params, $extra)->getData(), 'tkr_ticker');
    }

    public function getTickerInfo($ticker) {
        $qry = "select tkr_industry from ticker_tb where tkr_ticker='".$ticker."' and tkr_table = 'SEP' ORDER BY tkr_lastupdated DESC LIMIT 1";
        $res = $this->db->query($qry)->row();
        return $res->tkr_industry;
    }

	protected function __filter($params) {
		$params['tkr_created_at'] = date('Y-m-d H:i:s');
		$params['tkr_updated_at'] = date('Y-m-d H:i:s');

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
