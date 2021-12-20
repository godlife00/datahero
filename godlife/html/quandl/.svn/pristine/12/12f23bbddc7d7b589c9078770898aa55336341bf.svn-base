<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mri_tb_model extends MY_Model {

	protected $pk = 'm_ticker';

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

	function get_tkrList() {

		/*
$query_up = "delete from sic_theme";
if( !($result_up = db_query( $myconn, $query_up )) )
{
	echo $GLOBALS[DB_ERR_MSG] . "<br>\n";
	exit;
}

$query_up = "insert into sic_theme select * from sic_theme_new where status='1' ";
//echo $query_up;
if( !($result_up = db_query( $myconn, $query_up )) )
{
	echo $GLOBALS[DB_ERR_MSG] . "<br>\n";
	exit;
}

		$qry = "
			select a.tkr_ticker, b.cp_exchange, b.cp_usname, b.cp_korname from ticker_tb a, company_tb b where a.tkr_ticker = b.cp_ticker and a.tkr_isdelisted = 'N' and a.tkr_table = 'sf1' and a.tkr_exchange != 'OTC'
			(select distinct tkr_ticker from ticker_tb where tkr_isdelisted = 'N' and tkr_table = 'sf1' and tkr_exchange != 'OTC') as a left join company_tb as b 
			on a.tkr_ticker = b.cp_ticker
		";
		*/
		$qry = "select distinct a.tkr_ticker, a.tkr_sector, a.tkr_industry, b.cp_exchange, b.cp_usname, b.cp_korname, a.tkr_scalemarketcap from ticker_tb a, company_tb b where a.tkr_ticker = b.cp_ticker and a.tkr_isdelisted = 'N' and a.tkr_table = 'SEP' and a.tkr_exchange != 'OTC' and tkr_category not like '%Warrant%' and tkr_category not like '%Preferred%' order by tkr_ticker asc";

		$res = $this->db->query($qry)->result_array();

		return $res;
	}
	
	function get_data($tb) {
		$qry = "select * from ".$tb;
		return $this->db->query($qry)->result_array();
	}

	function get_sepClose($ticker) {

		if(!isset($ticker) || $ticker == '') {
			$res = 'ticker not exist';
			return $res;
		}
		else {
			$qry = "select date_format(sep_date, '%Y%m%d') sep_date, sep_close from sep_tb where sep_ticker=? order by sep_date desc limit 1";
			$bind[0] = $ticker;
			return $this->db->query($qry, $bind)->result_array();
		}

	}

	function get_dailyInfo($ticker) {

		if(!isset($ticker) || $ticker == '') {
			$res = 'ticker not exist';
			return $res;
		}
		else {
			$qry = "select dly_marketcap, dly_pb, dly_pe, dly_ps from daily_tb where dly_ticker=? order by dly_date desc limit 1";
			$bind[0] = $ticker;
			return $this->db->query($qry, $bind)->result_array();
		}
		
	}

	function get_sf1RoeDy ($ticker) {
	
		if(!isset($ticker) || $ticker == '') {
			$res = 'ticker not exist';
			return $res;
		}
		else {
			$qry = "select sf1_roe, sf1_divyield, sf1_netmargin from sf1_tb where sf1_ticker=? and sf1_dimension = 'MRT' order by sf1_datekey desc limit 1";
			$bind[0] = $ticker;
			return $this->db->query($qry, $bind)->result_array();
		}

	}

	function get_sf1Eps($ticker) { 

		if(!isset($ticker) || $ticker == '') {
			return false;
		}
		else { //sf1_datekey
			$qry = "select date_format(sf1_calendardate, '%Y%m') sf1_calendardate, sf1_epsdil from sf1_tb where sf1_ticker =? and sf1_dimension = 'MRQ' order by sf1_datekey desc limit 4";
			$bind[0] = $ticker;
			return $this->db->query($qry, $bind)->result_array();
		}
	}

	function get_maxTck () {

		$qry = "select sf1_ticker, sf1_calendardate from sf1_tb where sf1_dimension = 'MRQ' order by sf1_calendardate desc limit 1 ";
		return $this->db->query($qry)->result_array();

	}

	function get_sepdate ($tb, $col) {
		$qry = "select max(".$col.") ".$col." from ".$tb;
		return $this->db->query($qry)->result_array();
	}

	function get_list($limit, $offset, $param, $func) {

		$sort_qry = explode('_', $param['sort']);

		$w_1 = '';
		if($param['sel_margin']) {
			if($param['sel_margin'] == 'p') {
				$w_1 = ' and m_netmargin > 0';
			}
			else {
				$w_1 = ' and m_netmargin < 0';
			}
		}

		$w_2 = '';
		if($param['sel_marketcap']) {
			switch($param['sel_marketcap']) {
				case 1 : 
					$w_2 = ' and m_marketcap < 1';
					break;
				case 2 : 
					$w_2 = ' and ( m_marketcap >= 1 && m_marketcap < 10000)';
					break;
				case 3 : 
					$w_2 = ' and m_marketcap >= 10000';
					break;
				default : 
					$w_2 = ' and ( m_marketcap >= 1 && m_marketcap < 10000)';
					break;
			}
		}

		$w_3 = '';
		if($param['sel_sector']>0) {
			$w_3 = " and m_sector = '".$param['sel_sector']."'";
		}

		$w_4 = '';
		if($param['sel_industry']>0) {
			$w_4 = " and m_industry = '".$param['sel_industry']."'";
		}

		if($func == 'C') {
			$query = "select * from mri".$param['m_tb']."_tb where m_date = '".$param['m_date']."'".$w_1.$w_2.$w_3.$w_4;
		}
		else {
			if($sort_qry[0] != 'total') {
				$ord = ', m_total_score '.$sort_qry[1];
			}
			else {
				$ord = '';
			}

			$query = "select * from mri".$param['m_tb']."_tb where m_date = '".$param['m_date']."'".$w_1.$w_2.$w_3.$w_4." order by m_".$sort_qry[0]."_score ".$sort_qry[1]." ".$ord." limit ".$offset.", ".$limit;
		}
		//echo $query;
		return $this->db->query($query)->result_array();
	}

	function del_mri_tb($tb) {
		if(substr($tb, 0, 4) != 'mri_') {
			return false;
		}
		return $this->db->empty_table($tb);
	}


	function ins_mri() {
		$this->del_mri_tb('mri_tb');
		
		$qry = "insert into mri_tb select * from mri_tmp_tb ";
		$this->db->query($qry);

		//alter table mriall_tb auto_increment = 1
		$qry_all = "insert into mriall_tb (m_ticker,m_usname,m_korname,m_exchange,m_sector,m_industry,m_growth_score,m_growth_stars,m_safety_score,m_safety_stars,m_cashflow_score,m_cashflow_stars,m_moat_score,m_moat_stars,m_valuation_score,m_valuation_stars,m_total_score,m_biz_growth_score,m_biz_growth_stars,m_biz_safety_score,m_biz_safety_stars,m_biz_cashflow_score,m_biz_cashflow_stars,m_biz_moat_score,m_biz_moat_stars,m_biz_dividend_score,m_biz_dividend_stars,m_biz_total_score,m_sep_date,m_close,m_marketcap,m_netmargin,m_pbr,m_per,m_psr,m_roe,m_divyield,m_eps1,m_eps2,m_eps3,m_eps4,m_eps1_date,m_eps2_date,m_eps3_date,m_eps4_date,m_v_formula,m_v_multiple,m_v_fairvalue5,m_v_fairvalue4,m_v_fairvalue3,m_v_fairvalue2,m_v_fairvalue1,m_g_roe,m_g_epsgr,m_s_bis,m_s_crratio,m_s_debtratio,m_s_intcoverage,m_s_boingratio,m_s_fincost,m_c_pcr,m_c_cashflow,m_c_ffrevenue,m_m_assetsgr,m_m_roe,m_m_opmargin,m_m_revenuegr,m_d_epsgr2,m_d_fcfgr,m_d_divyield,m_d_poratio,m_d_dps_year1,m_d_dps_year2,m_d_dps_year3,m_d_dps_year4,m_d_dps_year5,m_d_dps1,m_d_dps2,m_d_dps3,m_d_dps4,m_d_dps5,m_scalemarketcap,m_ranking,m_highrank,m_date) select m_ticker,m_usname,m_korname,m_exchange,m_sector,m_industry,m_growth_score,m_growth_stars,m_safety_score,m_safety_stars,m_cashflow_score,m_cashflow_stars,m_moat_score,m_moat_stars,m_valuation_score,m_valuation_stars,m_total_score,m_biz_growth_score,m_biz_growth_stars,m_biz_safety_score,m_biz_safety_stars,m_biz_cashflow_score,m_biz_cashflow_stars,m_biz_moat_score,m_biz_moat_stars,m_biz_dividend_score,m_biz_dividend_stars,m_biz_total_score,m_sep_date,m_close,m_marketcap,m_netmargin,m_pbr,m_per,m_psr,m_roe,m_divyield,m_eps1,m_eps2,m_eps3,m_eps4,m_eps1_date,m_eps2_date,m_eps3_date,m_eps4_date,m_v_formula,m_v_multiple,m_v_fairvalue5,m_v_fairvalue4,m_v_fairvalue3,m_v_fairvalue2,m_v_fairvalue1,m_g_roe,m_g_epsgr,m_s_bis,m_s_crratio,m_s_debtratio,m_s_intcoverage,m_s_boingratio,m_s_fincost,m_c_pcr,m_c_cashflow,m_c_ffrevenue,m_m_assetsgr,m_m_roe,m_m_opmargin,m_m_revenuegr,m_d_epsgr2,m_d_fcfgr,m_d_divyield,m_d_poratio,m_d_dps_year1,m_d_dps_year2,m_d_dps_year3,m_d_dps_year4,m_d_dps_year5,m_d_dps1,m_d_dps2,m_d_dps3,m_d_dps4,m_d_dps5,m_scalemarketcap,m_ranking,m_highrank,m_date from mri_tmp_tb";

		$this->db->query($qry_all);
	}

	function set_mri($mri) {

		if($mri) {
			if(is_nan($mri['g_roe']) || is_infinite($mri['g_roe'])) $mri['g_roe'] = 0; 
			if(is_nan($mri['g_epsgr']) || is_infinite($mri['g_epsgr'])) $mri['g_epsgr'] = 0; 
			if(is_nan($mri['s_bis']) || is_infinite($mri['s_bis'])) $mri['s_bis'] = 0; 
			if(is_nan($mri['s_crratio']) || is_infinite($mri['s_crratio'])) $mri['s_crratio'] = 0; 
			if(is_nan($mri['s_crratio']) || is_infinite($mri['s_crratio'])) $mri['s_crratio'] = 0; 
			if(is_nan($mri['s_intcoverage']) || is_infinite($mri['s_intcoverage'])) $mri['s_intcoverage'] = 0; 
			if(is_nan($mri['s_boingratio']) || is_infinite($mri['s_boingratio'])) $mri['s_boingratio'] = 0; 
			if(is_nan($mri['s_fincost']) || is_infinite($mri['s_fincost'])) $mri['s_fincost'] = 0; 
			if(is_nan($mri['c_pcr']) || is_infinite($mri['c_pcr'])) $mri['c_pcr'] = 0; 
			if(is_nan($mri['c_cashflow']) || is_infinite($mri['c_cashflow'])) $mri['c_cashflow'] = 0; 
			if(is_nan($mri['c_ffrevenue']) || is_infinite($mri['c_ffrevenue'])) $mri['c_ffrevenue'] = 0; 
			if(is_nan($mri['m_m_roe']) || is_infinite($mri['m_m_roe'])) $mri['m_m_roe'] = 0; 
			if(is_nan($mri['m_assetsgr']) || is_infinite($mri['m_assetsgr'])) $mri['m_assetsgr'] = 0; 
			if(is_nan($mri['m_opmargin']) || is_infinite($mri['m_opmargin'])) $mri['m_opmargin'] = 0; 
			if(is_nan($mri['m_revenuegr']) || is_infinite($mri['m_revenuegr'])) $mri['m_revenuegr'] = 0; 
			if(is_nan($mri['d_epsgr2']) || is_infinite($mri['d_epsgr2'])) $mri['d_epsgr2'] = 0; 
			if(is_nan($mri['d_fcfgr']) || is_infinite($mri['d_fcfgr'])) $mri['d_fcfgr'] = 0; 
			if(is_nan($mri['d_divyield']) || is_infinite($mri['d_divyield'])) $mri['d_divyield'] = 0; 
			if(is_nan($mri['d_poratio']) || is_infinite($mri['d_poratio'])) $mri['d_poratio'] = 0; 

			$data = array (
				'm_ticker' => $mri['m_ticker'],
				'm_usname' => $mri['m_usname'],
				'm_korname' => $mri['m_korname'],
				'm_exchange' => $mri['m_exchange'],
				'm_sector' => $mri['m_sector'],
				'm_industry' => $mri['m_industry'],
				'm_growth_score' => $mri['m_growth_score'],
				'm_growth_stars' => $mri['m_growth_stars'],
				'm_safety_score' => $mri['m_safety_score'],
				'm_safety_stars' => $mri['m_safety_stars'],
				'm_cashflow_score' => $mri['m_cashflow_score'],
				'm_cashflow_stars' => $mri['m_cashflow_stars'],
				'm_moat_score' => $mri['m_moat_score'],
				'm_moat_stars' => $mri['m_moat_stars'],
				'm_valuation_score' => $mri['m_valuation_score'],
				'm_valuation_stars' => $mri['m_valuation_stars'],
				'm_total_score' => $mri['m_total_score'],
				'm_biz_growth_score' => $mri['m_biz_growth_score'],
				'm_biz_growth_stars' => $mri['m_biz_growth_stars'],
				'm_biz_safety_score' => $mri['m_biz_safety_score'],
				'm_biz_safety_stars' => $mri['m_biz_safety_stars'],
				'm_biz_cashflow_score' => $mri['m_biz_cashflow_score'],
				'm_biz_cashflow_stars' => $mri['m_biz_cashflow_stars'],
				'm_biz_moat_score' => $mri['m_biz_moat_score'],
				'm_biz_moat_stars' => $mri['m_biz_moat_stars'],
				'm_biz_dividend_score' => $mri['m_biz_dividend_score'],
				'm_biz_dividend_stars' => $mri['m_biz_dividend_stars'],
				'm_biz_total_score' => $mri['m_biz_total_score'],
				'm_sep_date' => $mri['m_sep_date'],
				'm_close' => $mri['m_close'],
				'm_marketcap' => $mri['m_marketcap'],
				'm_netmargin' => $mri['m_netmargin'],
				'm_pbr' => $mri['m_pbr'],
				'm_per' => $mri['m_per'],
				'm_psr' => $mri['m_psr'],
				'm_roe' => $mri['m_roe'],
				'm_divyield' => $mri['m_divyield'],
				'm_eps1' => $mri['m_eps1'],
				'm_eps2' => $mri['m_eps2'],
				'm_eps3' => $mri['m_eps3'],
				'm_eps4' => $mri['m_eps4'],
				'm_eps1_date' => $mri['m_eps1_date'],
				'm_eps2_date' => $mri['m_eps2_date'],
				'm_eps3_date' => $mri['m_eps3_date'],
				'm_eps4_date' => $mri['m_eps4_date'],
				'm_g_roe' => $mri['g_roe'],
				'm_g_epsgr' => $mri['g_epsgr'],
				'm_s_bis' => $mri['s_bis'],
				'm_s_crratio' => $mri['s_crratio'],
				'm_s_debtratio' => $mri['s_debtratio'],
				'm_s_intcoverage' => $mri['s_intcoverage'],
				'm_s_boingratio' => $mri['s_boingratio'],
				'm_s_fincost' => $mri['s_fincost'],
				'm_c_pcr' => $mri['c_pcr'],
				'm_c_cashflow' => $mri['c_cashflow'],
				'm_c_ffrevenue' => $mri['c_ffrevenue'],
				'm_m_roe' => $mri['m_m_roe'],
				'm_m_assetsgr' => $mri['m_assetsgr'],
				'm_m_opmargin' => $mri['m_opmargin'],
				'm_m_revenuegr' => $mri['m_revenuegr'],
				'm_d_epsgr2' => $mri['d_epsgr2'],
				'm_d_fcfgr' => $mri['d_fcfgr'],
				'm_d_divyield' => $mri['d_divyield'],
				'm_d_poratio' => $mri['d_poratio'],
				'm_d_dps_year1' => $mri['d_dps_year1'],
				'm_d_dps_year2' => $mri['d_dps_year2'],
				'm_d_dps_year3' => $mri['d_dps_year3'],
				'm_d_dps_year4' => $mri['d_dps_year4'],
				'm_d_dps_year5' => $mri['d_dps_year5'],
				'm_d_dps1' => $mri['d_dps1'],
				'm_d_dps2' => $mri['d_dps2'],
				'm_d_dps3' => $mri['d_dps3'],
				'm_d_dps4' => $mri['d_dps4'],
				'm_d_dps5' => $mri['d_dps5'],
				'm_v_formula' => $mri['v_formula'],
				'm_v_multiple' => $mri['v_multiple'],
				'm_v_fairvalue5' => $mri['v_fairvalue5'],
				'm_v_fairvalue4' => $mri['v_fairvalue4'],
				'm_v_fairvalue3' => $mri['v_fairvalue3'],
				'm_v_fairvalue2' => $mri['v_fairvalue2'],
				'm_v_fairvalue1' => $mri['v_fairvalue1'],
				'm_scalemarketcap' => $mri['m_scalemarketcap'],
				'm_ranking' => '',
				'm_highrank' => '',
				'm_date' => date('Ymd')
			);
			//print_r($data);
			$this->db->insert('mri_tmp_tb', $data);
		}
	}
}
?>
