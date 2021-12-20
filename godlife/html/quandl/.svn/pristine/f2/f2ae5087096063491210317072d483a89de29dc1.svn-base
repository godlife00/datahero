<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'/base_pc.php';
class Make_mri extends BasePC_Controller{
	public function __construct() {
		set_time_limit(0);
		ini_set('memory_limit', '2G');
		parent::__construct();
		$this->load->model(array(
					'business/historylib',
					DBNAME.'/mri_tb_model'
					));
	}

	function proc_mridata() {

		//주가 기준일 가져오기(daily_tb)
		$chk_sep_date = $this->historylib->getDailyLastDate();
		$chk_sep_date = str_replace('-', '', $chk_sep_date);

		//mri_tb에서 종가일(max) 가져오기
		$arr_msepdate = $this->mri_tb_model->get_sepdate('mri_tb', 'm_sep_date');
		$chk_msep_date = $arr_msepdate[0]['m_sep_date'];

		if( $chk_sep_date <= $chk_msep_date ) {
			echo '이미 업데이트 됐습니다. ['.date("Y-m-d H:i:s")."]\n";
			exit;
		}

		//echo '<pre>';
		$strIncVal = '<?'."\n"; 
		$strIncVal .= '$ticker_cnt = 0;'."\n"; 

		//mri_tmp테이블 삭제
		$res = $this->mri_tb_model->del_mri_tb('mri_tmp_tb');

		if(!$res) {
			echo '['.date("Y-m-d H:i:s")."] mri_tmp_tb delete error!!\n";
			exit;
		}

		//eps(분기) 데이터 가져오기
		$max_tkr = $this->mri_tb_model->get_maxTck();
		$quarter_info = $this->mri_tb_model->get_sf1Eps($max_tkr[0]['sf1_ticker']);

		$ticker_data = array();
		$ticker_data = $this->mri_tb_model->get_tkrList();

		if( isset($ticker_data) && is_array($ticker_data) ) {

			//수익 성장성, 재무 안전성, 현금 창출력, 사업 독점력, 배당 매력(기업), 밸류에이션(주식)
			$arrMriItem = array('growth', 'safety', 'cashflow', 'moat', 'dividend', 'valuation');
			//$arrMriBizItem = array('growth', 'safety', 'cashflow', 'moat', 'dividend');
	
			//sector, industry 정보 생성
			$arr_sec = $this->mri_tb_model->get_data('sector_tb');
			$arr_ind = $this->mri_tb_model->get_data('industry_tb');

			$arrAllTicker = array();
			foreach($ticker_data as $nKey => $nVal) {
				
				$arrTickerMri = array();
				$sep_close	  = array();
				$daily_info   = array();
				$sf1_roedy    = array();
				$sf1_eps	  = array();
				$arrMriInfo   = array();
				//$arrMriBizInfo= array();

				//종목코드
				$arrTickerMri['m_ticker'] = $nVal['tkr_ticker'];
				//시장구분
				$arrTickerMri['m_exchange'] = $nVal['cp_exchange'];
				//종목명(영문)
				$arrTickerMri['m_usname'] = $nVal['cp_usname'];
				//종목명(한글)
				$arrTickerMri['m_korname'] = $nVal['cp_korname'];
				//섹터
				$arrTickerMri['m_sector'] = $nVal['tkr_sector'];
				//산업
				$arrTickerMri['m_industry'] = $nVal['tkr_industry'];
				//대중소형 구분
				$arrTickerMri['m_scalemarketcap'] = $nVal['tkr_scalemarketcap'];
/*****
				//섹터
				if($nVal['tkr_sector']=='') {
					$arrTickerMri['m_sector'] = '12';
				}
				else{
					foreach($arr_sec as $sKey=>$sVal) {
						if($sVal['sec_name'] == $nVal['tkr_sector']) {
							$arrTickerMri['m_sector'] = $sVal['sec_id'];
							break;
						}
					}
				}
				//산업
				foreach($arr_ind as $iKey=>$iVal) {
					if($iVal['ind_name'] == $nVal['tkr_industry']) {
						$arrTickerMri['m_industry'] = $iVal['ind_id'];
						break;
					}
				}
				//스파이더V
				$arrMriInfo = $this->historylib->getTickerValueSpider($nVal['tkr_ticker']);
				$total_score = 0;
				foreach($arrMriItem as $itemKey => $itemVal) {
					//print_r($arrMriInfo[$itemVal]['total_score']);
					$arrTickerMri['m_'.$itemVal.'_score'] = $arrMriInfo[$itemVal]['total_score'];
					$arrTickerMri['m_'.$itemVal.'_stars'] = $arrMriInfo[$itemVal]['stars'];
					$total_score += $arrMriInfo[$itemVal]['total_score'];
				}
				//주식 mri 종합 점수
				$arrTickerMri['m_total_score'] = $total_score;
				
				//스파이더D
				$arrMriBizInfo = $this->historylib->getTickerSpider($nVal['tkr_ticker']);
				
				$biz_total_score = 0;				
				foreach($arrMriBizItem as $itemKey => $itemVal) {
					//print_r($arrMriBizInfo[$itemVal]['total_score']);
					$arrTickerMri['m_biz_'.$itemVal.'_score'] = $arrMriBizInfo[$itemVal]['total_score'];
					$arrTickerMri['m_biz_'.$itemVal.'_stars'] = $arrMriBizInfo[$itemVal]['stars'];
					$biz_total_score += $arrMriBizInfo[$itemVal]['total_score'];
				}
				//기업 mri 종합 점수
				$arrTickerMri['m_biz_total_score'] = $biz_total_score;
*****/

				$arrMriInfo = $this->historylib->getTickerValueSpider($nVal['tkr_ticker']);
				//print_r($arrMriInfo);
				$total_score = 0;
				$biz_total_score = 0;				
				foreach($arrMriItem as $itemKey => $itemVal) {
					//print_r($arrMriInfo[$itemVal]['total_score']);
					$arrTickerMri['m_'.$itemVal.'_score'] = $arrMriInfo[$itemVal]['total_score'];
					$arrTickerMri['m_'.$itemVal.'_stars'] = $arrMriInfo[$itemVal]['stars'];
					$total_score += $arrMriInfo[$itemVal]['total_score'];

					$arrTickerMri['m_biz_'.$itemVal.'_score'] = $arrMriInfo[$itemVal]['total_score'];
					$arrTickerMri['m_biz_'.$itemVal.'_stars'] = $arrMriInfo[$itemVal]['stars'];
					$biz_total_score += $arrMriInfo[$itemVal]['total_score'];
				}
				//주식 mri 종합 점수
				$arrTickerMri['m_total_score'] = $total_score;
				$arrTickerMri['m_biz_total_score'] = $biz_total_score;

				$arrTickerMri['g_roe'] = $arrMriInfo['growth']['g_roe'];
				$arrTickerMri['g_epsgr'] = $arrMriInfo['growth']['g_epsgr'];

				$arrTickerMri['s_bis'] = $arrMriInfo['safety']['s_bis'];
				$arrTickerMri['s_crratio'] = $arrMriInfo['safety']['s_crratio'];
				$arrTickerMri['s_debtratio'] = $arrMriInfo['safety']['s_debtratio'];
				$arrTickerMri['s_intcoverage'] = $arrMriInfo['safety']['s_intcoverage'];
				$arrTickerMri['s_boingratio'] = $arrMriInfo['safety']['s_boingratio'];
				$arrTickerMri['s_fincost'] = $arrMriInfo['safety']['s_fincost'];
				
				$arrTickerMri['c_pcr'] = $arrMriInfo['cashflow']['c_pcr'];
				$arrTickerMri['c_cashflow'] = $arrMriInfo['cashflow']['c_cashflow'];
				$arrTickerMri['c_ffrevenue'] = $arrMriInfo['cashflow']['c_ffrevenue'];

				$arrTickerMri['m_m_roe'] = $arrMriInfo['moat']['m_m_roe'];
				$arrTickerMri['m_assetsgr'] = $arrMriInfo['moat']['m_assetsgr'];
				$arrTickerMri['m_opmargin'] = $arrMriInfo['moat']['m_opmargin'];
				$arrTickerMri['m_revenuegr'] = $arrMriInfo['moat']['m_revenuegr'];

				$arrTickerMri['d_epsgr2'] = $arrMriInfo['dividend']['d_epsgr2'];
				$arrTickerMri['d_fcfgr'] = $arrMriInfo['dividend']['d_fcfgr'];
				$arrTickerMri['d_divyield'] = $arrMriInfo['dividend']['d_divyield'];
				$arrTickerMri['d_poratio'] = $arrMriInfo['dividend']['d_poratio'];
				$arrTickerMri['d_dps_year1'] = $arrMriInfo['dividend']['d_dps_year1'];
				$arrTickerMri['d_dps_year2'] = $arrMriInfo['dividend']['d_dps_year2'];
				$arrTickerMri['d_dps_year3'] = $arrMriInfo['dividend']['d_dps_year3'];
				$arrTickerMri['d_dps_year4'] = $arrMriInfo['dividend']['d_dps_year4'];
				$arrTickerMri['d_dps_year5'] = $arrMriInfo['dividend']['d_dps_year5'];

				$arrTickerMri['d_dps1'] = $arrMriInfo['dividend']['d_dps1'];
				$arrTickerMri['d_dps2'] = $arrMriInfo['dividend']['d_dps2'];
				$arrTickerMri['d_dps3'] = $arrMriInfo['dividend']['d_dps3'];
				$arrTickerMri['d_dps4'] = $arrMriInfo['dividend']['d_dps4'];
				$arrTickerMri['d_dps5'] = $arrMriInfo['dividend']['d_dps5'];

				$arrTickerMri['v_formula'] = $arrMriInfo['valuation']['formula'];
				$arrTickerMri['v_multiple'] = $arrMriInfo['valuation']['multiple'];
				$arrTickerMri['v_fairvalue5'] = $arrMriInfo['valuation']['fairvalue5'];
				$arrTickerMri['v_fairvalue4'] = $arrMriInfo['valuation']['fairvalue4'];
				$arrTickerMri['v_fairvalue3'] = $arrMriInfo['valuation']['fairvalue3'];
				$arrTickerMri['v_fairvalue2'] = $arrMriInfo['valuation']['fairvalue2'];
				$arrTickerMri['v_fairvalue1'] = $arrMriInfo['valuation']['fairvalue1'];

				//주가, 날짜(sep_tb)
				$sep_close = $this->mri_tb_model->get_sepClose($nVal['tkr_ticker']);
				$arrTickerMri['m_sep_date'] = $sep_close[0]['sep_date'];
				$arrTickerMri['m_close'] = $sep_close[0]['sep_close'];

				//시가총액, PER, PBR, PSR(daily_tb)
				$daily_info = $this->mri_tb_model->get_dailyInfo($nVal['tkr_ticker']);
				$arrTickerMri['m_marketcap'] = $daily_info[0]['dly_marketcap'];
				$arrTickerMri['m_pbr'] = $daily_info[0]['dly_pb'];
				$arrTickerMri['m_per'] = $daily_info[0]['dly_pe'];
				$arrTickerMri['m_psr'] = $daily_info[0]['dly_ps'];

				//roe, DY(divyield, 배당수익률), sf1
				$sf1_roedy = $this->mri_tb_model->get_sf1RoeDy($nVal['tkr_ticker']);
				$arrTickerMri['m_roe'] = $sf1_roedy[0]['sf1_roe'];
				$arrTickerMri['m_divyield'] = $sf1_roedy[0]['sf1_divyield'];
				$arrTickerMri['m_netmargin'] = $sf1_roedy[0]['sf1_netmargin'];

				//row, DY(divyield, 배당수익률), sf1
				$sf1_eps = $this->mri_tb_model->get_sf1Eps($nVal['tkr_ticker']);

				if(is_array($sf1_eps)) {
					foreach($quarter_info as $qKey => $qVal) {

						foreach($sf1_eps as $eKey => $eVal) {
							if($qVal['sf1_calendardate'] == $eVal['sf1_calendardate']) {
								$arrTickerMri['m_eps'.($qKey+1)] = $eVal['sf1_epsdil'];
								break;
							}
							else {
								$arrTickerMri['m_eps'.($qKey+1)] = '0.00';
							}
						}
						
						$arrTickerMri['m_eps'.($qKey+1).'_date'] = $qVal['sf1_calendardate'];

						if(!isset($arrTickerMri['m_eps'.($qKey+1)])) $arrTickerMri['m_eps'.($qKey+1)] = '0.00';
					}
				}
				else {
					$arrTickerMri['m_eps1'] = '0.00';
					$arrTickerMri['m_eps2'] = '0.00';
					$arrTickerMri['m_eps3'] = '0.00';
					$arrTickerMri['m_eps4'] = '0.00';
				}

				$this->mri_tb_model->set_mri($arrTickerMri);
				//$arrAllTicker[$nVal['tkr_ticker']] = $arrTickerMri;

				//, 'usname'=>'".$arrTickerMri['m_usname']."'
				$strIncVal .= "\$ticker_mri_info[\$ticker_cnt++]=array('ticker'=>'".$arrTickerMri['m_ticker']."', 'korname'=>'".$arrTickerMri['m_korname']."', 'growth_score'=>'".$arrTickerMri['m_growth_score']."', 'growth_stars'=>'".$arrTickerMri['m_growth_stars']."', 'safety_score'=>'".$arrTickerMri['m_safety_score']."', 'safety_stars'=>'".$arrTickerMri['m_safety_stars']."', 'cashflow_score'=>'".$arrTickerMri['m_cashflow_score']."', 'cashflow_stars'=>'".$arrTickerMri['m_cashflow_stars']."', 'moat_score'=>'".$arrTickerMri['m_moat_score']."', 'moat_stars'=>'".$arrTickerMri['m_moat_stars']."', 'valuation_score'=>'".$arrTickerMri['m_valuation_score']."', 'valuation_stars'=>'".$arrTickerMri['m_valuation_stars']."', 'total_score'=>'".$arrTickerMri['m_total_score']."', 'biz_growth_score'=>'".$arrTickerMri['m_biz_growth_score']."', 'biz_growth_stars'=>'".$arrTickerMri['m_biz_growth_stars']."', 'biz_safety_score'=>'".$arrTickerMri['m_biz_safety_score']."', 'biz_safety_stars'=>'".$arrTickerMri['m_biz_safety_stars']."', 'biz_cashflow_score'=>'".$arrTickerMri['m_biz_cashflow_score']."', 'biz_cashflow_stars'=>'".$arrTickerMri['m_biz_cashflow_stars']."', 'biz_moat_score'=>'".$arrTickerMri['m_biz_moat_score']."', 'biz_moat_stars'=>'".$arrTickerMri['m_biz_moat_stars']."', 'biz_dividend_score'=>'".$arrTickerMri['m_biz_dividend_score']."', 'biz_dividend_stars'=>'".$arrTickerMri['m_biz_dividend_stars']."', 'biz_total_score'=>'".$arrTickerMri['m_biz_total_score']."');\n";

				//if($nKey>150) break;
			}
			//mri_tmp_tb 데이터를 mri_tb, mriall_tb에 밀어준다
			$res = $this->mri_tb_model->ins_mri();
			$strIncVal .= '?>';
			
			$arr_opt_new = array();
			$arr_val_new = array();

			$arr_opt_new[] = array('0'=>'전체');
			$arr_val_new[] = array('0'=>'0');

			foreach($arr_sec as $aKey => $aVal) {
				$arr_val = array();
				$arr_opt = array();

				$arr_opt[] = array('0'=>'전체');
				$arr_val[] = array('0'=>'0');
				foreach($arr_ind as $iKey => $iVal) {
					if($aVal['sec_id'] == $iVal['ind_sec']) {
						$arr_opt[] = $iVal['ind_name'];
						$arr_val[] = $iVal['ind_id'];
					}
				}
				$arr_opt_new[] = $arr_opt;
				$arr_val_new[] = $arr_val;
			}

			$arrAllTicker['sep_date'] = $chk_sep_date;
			$arrAllTicker['mri_date'] = date('Ymd');
			$arrAllTicker['quarter_info'] = $quarter_info;
			$arrAllTicker['arr_val_new'] = $arr_val_new;
			$arrAllTicker['arr_opt_new'] = $arr_opt_new;
			$arrAllTicker['arr_sec'] = $arr_sec;
			$arrAllTicker['arr_ind'] = $arr_ind;
			
			$strJsonStr = json_encode($arrAllTicker);

			$mri_file = 'mri_data.json';
			$strWPath = WEBDATA.'/'.$mri_file;
			$strBakWPath = $strWPath . '.bak';

			file_put_contents($strBakWPath, $strJsonStr);
			rename($strBakWPath, $strWPath);


			$mri_inc_file = 'mri_data.php';
			$strWPath = WEBDATA.'/'.$mri_inc_file;
			$strBakWPath = $strWPath . '.bak';

			file_put_contents($strBakWPath, $strIncVal);
			rename($strBakWPath, $strWPath);

			//print_r($arrTickerMri);

			echo "\n".'['.date("Y-m-d H:i:s")."] success!!\n";
		}
	}

	public function proc_rankdata() {
		return;
		//$ticker = 'DIS';
        //$data['mri_data'] = $this->mri_tb_model->get(array('m_ticker' => $ticker))->getData();
/*
		echo '<pre>';
        
		// 전체 종목 수
		$params = array();
		$params['wstreetdb'] = true;
		//$params['cache_sec'] = 3600*24;
        $mri_count = $this->mri_tb_model->getCount($params)->getData();

		echo 'mri_count===>'.$mri_count.'<br>'; exit;

        // 종합점수 순위($data['high_count'])
        $params = array();
        $params['>']['m_biz_total_score'] = ($data['mri_data']['m_biz_total_score'] =='') ? '0' : $data['mri_data']['m_biz_total_score'];
		$params['wstreetdb'] = true;
		//$params['cache_sec'] = 3600*24;
        $data['high_count'] = $this->mri_tb_model->getCount($params)->getData();
		
        // 종합점수 상위 퍼센트($data['total_rank_rate'])
        //$params = array();
        //$params['>=']['m_biz_total_score'] = $data['mri_data']['m_biz_total_score'];
        //$mri_high_count = $this->mri_tb_model->getCount($params)->getData();

		$top_rate = 0;
        $top_rate = round(($data['high_count']+1) / $mri_count * 100, 2);
		if($top_rate>=1) $top_rate = floor($top_rate);
		$data['total_rank_rate'] = $top_rate; 
		print_r($data); exit;
*/	
	
	}
}
?>