<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sf1_tb_model extends MY_Model {

	protected $pk = 'sf1_id';

/* 
Unique : 
	sf1_ticker
	sf1_dimention
	sf1_datekey
	sf1_reportperiod
*/

	// NOT NULL 필드들에대한 정의. 각 모델에서 재정의
	protected $emptycheck_keys = array(

		'sf1_ticker' 		=> 'sf1_ticker value is empty.',
		/*
		'sf1_dimension' 		=> 'sf1_dimension value is empty.', // MRQ : 분기  MRY : 연간  MRT : 연환산
		'sf1_calendardate' 		=> 'sf1_calendardate value is empty.',
		'sf1_datekey' 		=> 'sf1_datekey value is empty.',
		'sf1_reportperiod' 		=> 'sf1_reportperiod value is empty.',
		'sf1_lastupdated' 		=> 'sf1_lastupdated value is empty.',
		'sf1_accoci' 		=> 'sf1_accoci value is empty.',
		'sf1_assets' 		=> 'sf1_assets value is empty.',
		'sf1_assetsavg' 		=> 'sf1_assetsavg value is empty.',
		'sf1_assetsc' 		=> 'sf1_assetsc value is empty.',
		'sf1_assetsnc' 		=> 'sf1_assetsnc value is empty.',
		'sf1_assetturnover' 		=> 'sf1_assetturnover value is empty.',
		'sf1_bvps' 		=> 'sf1_bvps value is empty.',
		'sf1_capex' 		=> 'sf1_capex value is empty.',
		'sf1_cashneq' 		=> 'sf1_cashneq value is empty.',
		'sf1_cashnequsd' 		=> 'sf1_cashnequsd value is empty.',
		'sf1_cor' 		=> 'sf1_cor value is empty.',
		'sf1_consolinc' 		=> 'sf1_consolinc value is empty.',
		'sf1_currentratio' 		=> 'sf1_currentratio value is empty.',
		'sf1_de' 		=> 'sf1_de value is empty.',
		'sf1_debt' 		=> 'sf1_debt value is empty.',
		'sf1_debtc' 		=> 'sf1_debtc value is empty.',
		'sf1_debtnc' 		=> 'sf1_debtnc value is empty.',
		'sf1_debtusd' 		=> 'sf1_debtusd value is empty.',
		'sf1_deferredrev' 		=> 'sf1_deferredrev value is empty.',
		'sf1_depamor' 		=> 'sf1_depamor value is empty.',
		'sf1_deposits' 		=> 'sf1_deposits value is empty.',
		'sf1_divyield' 		=> 'sf1_divyield value is empty.',
		'sf1_dps' 		=> 'sf1_dps value is empty.',
		'sf1_ebit' 		=> 'sf1_ebit value is empty.',
		'sf1_ebitda' 		=> 'sf1_ebitda value is empty.',
		'sf1_ebitdamargin' 		=> 'sf1_ebitdamargin value is empty.',
		'sf1_ebitdausd' 		=> 'sf1_ebitdausd value is empty.',
		'sf1_ebitusd' 		=> 'sf1_ebitusd value is empty.',
		'sf1_ebt' 		=> 'sf1_ebt value is empty.',
		'sf1_eps' 		=> 'sf1_eps value is empty.',
		'sf1_epsdil' 		=> 'sf1_epsdil value is empty.',
		'sf1_epsusd' 		=> 'sf1_epsusd value is empty.',
		'sf1_equity' 		=> 'sf1_equity value is empty.',
		'sf1_equityavg' 		=> 'sf1_equityavg value is empty.',
		'sf1_equityusd' 		=> 'sf1_equityusd value is empty.',
		'sf1_ev' 		=> 'sf1_ev value is empty.',
		'sf1_evebit' 		=> 'sf1_evebit value is empty.',
		'sf1_evebitda' 		=> 'sf1_evebitda value is empty.',
		'sf1_fcf' 		=> 'sf1_fcf value is empty.',
		'sf1_fcfps' 		=> 'sf1_fcfps value is empty.',
		'sf1_fxusd' 		=> 'sf1_fxusd value is empty.',
		'sf1_gp' 		=> 'sf1_gp value is empty.',
		'sf1_grossmargin' 		=> 'sf1_grossmargin value is empty.',
		'sf1_intangibles' 		=> 'sf1_intangibles value is empty.',
		'sf1_intexp' 		=> 'sf1_intexp value is empty.',
		'sf1_invcap' 		=> 'sf1_invcap value is empty.',
		'sf1_invcapavg' 		=> 'sf1_invcapavg value is empty.',
		'sf1_inventory' 		=> 'sf1_inventory value is empty.',
		'sf1_investments' 		=> 'sf1_investments value is empty.',
		'sf1_investmentsc' 		=> 'sf1_investmentsc value is empty.',
		'sf1_investmentsnc' 		=> 'sf1_investmentsnc value is empty.',
		'sf1_liabilities' 		=> 'sf1_liabilities value is empty.',
		'sf1_liabilitiesc' 		=> 'sf1_liabilitiesc value is empty.',
		'sf1_liabilitiesnc' 		=> 'sf1_liabilitiesnc value is empty.',
		'sf1_marketcap' 		=> 'sf1_marketcap value is empty.',
		'sf1_ncf' 		=> 'sf1_ncf value is empty.',
		'sf1_ncfbus' 		=> 'sf1_ncfbus value is empty.',
		'sf1_ncfcommon' 		=> 'sf1_ncfcommon value is empty.',
		'sf1_ncfdebt' 		=> 'sf1_ncfdebt value is empty.',
		'sf1_ncfdiv' 		=> 'sf1_ncfdiv value is empty.',
		'sf1_ncff' 		=> 'sf1_ncff value is empty.',
		'sf1_ncfi' 		=> 'sf1_ncfi value is empty.',
		'sf1_ncfinv' 		=> 'sf1_ncfinv value is empty.',
		'sf1_ncfo' 		=> 'sf1_ncfo value is empty.',
		'sf1_ncfx' 		=> 'sf1_ncfx value is empty.',
		'sf1_netinc' 		=> 'sf1_netinc value is empty.',
		'sf1_netinccmn' 		=> 'sf1_netinccmn value is empty.',
		'sf1_netinccmnusd' 		=> 'sf1_netinccmnusd value is empty.',
		'sf1_netincdis' 		=> 'sf1_netincdis value is empty.',
		'sf1_netincnci' 		=> 'sf1_netincnci value is empty.',
		'sf1_netmargin' 		=> 'sf1_netmargin value is empty.',
		'sf1_opex' 		=> 'sf1_opex value is empty.',
		'sf1_opinc' 		=> 'sf1_opinc value is empty.',
		'sf1_payables' 		=> 'sf1_payables value is empty.',
		'sf1_payoutratio' 		=> 'sf1_payoutratio value is empty.',
		'sf1_pb' 		=> 'sf1_pb value is empty.',
		'sf1_pe' 		=> 'sf1_pe value is empty.',
		'sf1_pe1' 		=> 'sf1_pe1 value is empty.',
		'sf1_ppnenet' 		=> 'sf1_ppnenet value is empty.',
		'sf1_prefdivis' 		=> 'sf1_prefdivis value is empty.',
		'sf1_price' 		=> 'sf1_price value is empty.',
		'sf1_ps' 		=> 'sf1_ps value is empty.',
		'sf1_ps1' 		=> 'sf1_ps1 value is empty.',
		'sf1_receivables' 		=> 'sf1_receivables value is empty.',
		'sf1_retearn' 		=> 'sf1_retearn value is empty.',
		'sf1_revenue' 		=> 'sf1_revenue value is empty.',
		'sf1_revenueusd' 		=> 'sf1_revenueusd value is empty.',
		'sf1_rnd' 		=> 'sf1_rnd value is empty.',
		'sf1_roa' 		=> 'sf1_roa value is empty.',
		'sf1_roe' 		=> 'sf1_roe value is empty.',
		'sf1_roic' 		=> 'sf1_roic value is empty.',
		'sf1_ros' 		=> 'sf1_ros value is empty.',
		'sf1_sbcomp' 		=> 'sf1_sbcomp value is empty.',
		'sf1_sgna' 		=> 'sf1_sgna value is empty.',
		'sf1_sharefactor' 		=> 'sf1_sharefactor value is empty.',
		'sf1_sharesbas' 		=> 'sf1_sharesbas value is empty.',
		'sf1_shareswa' 		=> 'sf1_shareswa value is empty.',
		'sf1_shareswadil' 		=> 'sf1_shareswadil value is empty.',
		'sf1_sps' 		=> 'sf1_sps value is empty.',
		'sf1_tangibles' 		=> 'sf1_tangibles value is empty.',
		'sf1_taxassets' 		=> 'sf1_taxassets value is empty.',
		'sf1_taxexp' 		=> 'sf1_taxexp value is empty.',
		'sf1_taxliabilities' 		=> 'sf1_taxliabilities value is empty.',
		'sf1_tbvps' 		=> 'sf1_tbvps value is empty.',
		'sf1_workingcapital' 		=> 'sf1_workingcapital value is empty.',
		*/
		'sf1_created_at' 		=> 'sf1_created_at value is empty.',
		'sf1_updated_at' 		=> 'sf1_updated_at value is empty.'
		);

	// ENUM 필드마다 가질 수 있는 값들을 KEY => VALUE 형태의 배열로 정의. 각 모델에서 재정의
	protected $enumcheck_keys = array(

	);


	// sf1_row 를 선택적으로 넘기면 
	// > sector가 [ Financial Services ] 또는 [ Real Estate ]일때, 재무상태표2 사용
	// and [ assetsc ], [ assetsnc ], [ liabilitiesc ], [ liabilitiesnc ]가 모두 빈값( 0 ) 일때는
	// [재무상태표2] 사용
	public function getBalancesheetType($ticker, $sf1_row) {
		if(
			$ticker != null 
			&& is_array($ticker) 
			&& sizeof($ticker) > 0 
			&& isset($ticker['tkr_sector'])

			&& $sf1_row != null 
			&& is_array($sf1_row) 
			&& sizeof($sf1_row) > 0 
			&& isset($sf1_row['sf1_assetsc'])
			&& isset($sf1_row['sf1_assetsnc'])
			&& isset($sf1_row['sf1_liabilitiesc'])
			&& isset($sf1_row['sf1_liabilitiesnc'])
		) {
			if(in_array($ticker['tkr_sector'], array(
                'Financial Services', 
                'Real Estate', 
                'Residential Construction',

                'Industrials', // - 섹터 내 Rental & Leasing Services 인더스트리만
                'Consumer Cyclical', // - 섹터 내 Residential Construction 인더스트리만
            ))) {

                $sector_industry_conditions = array(
                        'Industrials'   => array('Rental & Leasing Services'), // - 섹터 내 Rental & Leasing Services 인더스트리만
                        'Consumer Cyclical' => array('Residential Construction'), // - 섹터 내 Residential Construction 인더스트리만
                );

                $industry_condition = true;
                if(isset($sector_industry_conditions[$ticker['tkr_sector']]) && ! in_array($ticker['tkr_industry'], $sector_industry_conditions[$ticker['tkr_sector']])) {
                    $industry_condition = false;
                }


				if(
                    $industry_condition
					&& floatval($sf1_row['sf1_assetsc']) == false 
					&& floatval($sf1_row['sf1_assetsnc']) == false 
					&& floatval($sf1_row['sf1_liabilitiesc']) == false 
					&& floatval($sf1_row['sf1_liabilitiesnc']) == false 
				) {
					return 'balancesheet2';
				}
			}
		}
		return 'balancesheet';
	}

	// 재무상태표 타입 필드 정의
	public function getBalancesheetFields($ticker=null, $sf1_row=null) {
		return $this->historylib->getTableMap($this->getBalancesheetType($ticker, $sf1_row), 'leveltree', 'Indicator');
	}
	
	// 손익계산서 필드 정의
	public function getIncomeStateFields() {
		return $this->historylib->getTableMap('incomestate', 'leveltree', 'Indicator');
	}

	// 손익계산서 필드 정의
	public function getCashflowFields() {
		return $this->historylib->getTableMap('cashflow', 'leveltree', 'Indicator');
	}


	// 투자지표 리스트 관련 정의
	protected $fininvestindi_titles = array(
		'sf1_epsdil'		=> '주당순이익',
		'sf1_bvps'		=> '주당순자산',
		'sf1_dps'		=> '주당배당금',
		'sf1_sps'		=> '주당매출액',
		'sf1_cps'		=> '주당현금흐름',
		'sf1_fcfps'		=> '주당 잉여현금흐름',
		'sf1_pe'		=> '주가수익배수',
		'sf1_pb'		=> '주가순자산배수',
		'sf1_ps'		=> '주가매출애배수',
		'sf1_pc'		=> '주가현금흐름배수',
		'sf1_evebitda'		=> 'EV/EBITDA',
		'sf1_divyield'		=> '배당수익률',
		'sf1_grossmargin'	=> '매출총이익률',
		'sf1_opmargin'		=> '영업이익률',
		'sf1_netmargin'		=> '순이익률',
		'sf1_ebitdamargin'	=> 'EBITDA이익률',
		'sf1_fcfonrevenue'	=> '매출액 잉여현금흐름 비율',
		'sf1_roe'		=> '자기자본이익률',
		'sf1_roa'		=> '총자산이익률',
		'sf1_roic'		=> '투하자본이익률',
		'sf1_de'		=> '부채비율',
		'sf1_currentratio'	=> '유동비율',
		'sf1_intexpcoverage'	=> '이자보상배율',
		'sf1_borrowtoassets'	=> '차입금비율',
		'sf1_intexprevenue'	=> '금융비용 비율',
		'sf1_assetturnover'	=> '자산회전율',
		'sf1_receiveturnoverdays'	=> '매출채권 회전일수',
		'sf1_inventoryturnoverdays'	=> '재고자산 회전일수',
	);
	protected $summary_fininvestindi_titles = array(
        /*
		'sf1_epsdil' 	=> 'EPS',
		'sf1_pe' 	=> 'PER',
		'sf1_bvps' 	=> 'BPS',
		'sf1_pb' 	=> 'PBR',
		'sf1_dps' 	=> 'DPS',
		'sf1_divyield' 	=> 'DY',
		'sf1_roe' 	=> 'ROE',
        */


        'sf1_revenueusd'        => '매출액 (백만달러)',
        'sf1_opinc'             => '영업이익 (백만달러)',
        'sf1_ebitda'            => 'EBITDA (백만달러)',
        'sf1_netinc'            => '순이익 (백만달러)',
        'sf1_opinc_ratio'       => '영업이익률 (%)', // (ratio) 표출시 환산
        'sf1_netinc_ratio'      => '순이익률 (%)', // (ratio) 표출시 환산
        'sf1_roe'               => 'ROE(%)',
        'sf1_divyield'          => '배당수익률(%)', 
        'sf1_epsdil'            => 'EPS(달러)',
        'sf1_bvps'              => 'BPS(달러)',
        'sf1_dps'               => 'DPS(달러)',
	);

	public function getFinInvestIndiFields($type='all') {
		switch($type) {
			case 'summary' : 
				return array(
					'fields' => array_keys($this->summary_fininvestindi_titles),
					'titles' => $this->summary_fininvestindi_titles,
				);
			case 'invest' :
				return array(
					'fields' => array_keys($this->fininvestindi_titles),
					'titles' => $this->fininvestindi_titles,
				);
			default :
				// all
				$temp = $this->historylib->getTableMap('fininvestindi', 'Indicator', 'korunittype');
				return array_keys($temp);
		}
	}



	private $competitor_titles = array(
		'tkr_name'	=> '종목명',
		//'cp_korname'	=> '종목명(국문)',
		'dly_marketcap'	=> '시가총액',
		'sf1_revenueusd'=> '매출액',
		'sf1_opinc'	=> '영업이익',
		'sf1_netinc'	=> '순이익',
		'dly_pe'	=> 'PER',
		'dly_pb'	=> 'PBR',
		'dly_ps'	=> 'PSR',
		'sf1_roe'	=> 'ROE',
		'sf1_roa'	=> 'ROA',
		'sf1_roic'	=> 'ROIC',
	);
	public function getCompetitorFields() {
		return array(
			'fields' => array_keys($this->competitor_titles),
			'titles' => $this->competitor_titles,
		);
	}

	private $country_unitnum_map = array(
		'us_백만' 	=> '백만달러',
		//'us_천' 	=> '천달러',
		//'us_달러' 	=> '달러',
		'kor_억'	=> '억원',
	);
	public function getCountryUnitnumMap() {
		return $this->country_unitnum_map;
	}
	public function getUnitnumText($currency, $unitnum) {
        if($currency == 'USD') {
            return $unitnum;
        }
        return $currency.'('.$unitnum.')';
    }



	function __construct() {
		parent::__construct();
		$this->db_name = array_pop(explode('/', dirname(__FILE__)));
		$this->table = strtolower(substr(__CLASS__,0,-6));
		$this->fields = $this->db->list_fields($this->table);

		//$this->elastic_table = $this->db_name.'-elastic_'.$this->table;
		//$this->elastic_fields = $this->fields;
	}

	// 특정 티커의 경쟁사 리스트 반환
	public function getCompetitor($ticker, $sf1_row, $custom_extra = array()) {
		$default_extra = array(
			'dly_lastdate' => '',
			'limit' => 10,
		);
		$extra = array_merge($default_extra, $custom_extra);


		$tic = $ticker['tkr_ticker'];
		$industry = $ticker['tkr_industry'];
		$sf1_calendardate = $sf1_row['sf1_calendardate'];
		$ticker_revenue = $sf1_row['sf1_revenueusd'];
        $scale_revenue = $ticker['tkr_scalerevenue'];

        $scale_group_map = array(
            '' => array(''),
            '1 - Nano' => array('1 - Nano', '2 - Micro'),
            '2 - Micro' => array('1 - Nano', '2 - Micro', '3 - Small'),
            '3 - Small' => array('2 - Micro', '3 - Small', '4 - Mid'),
            '4 - Mid' => array('3 - Small', '4 - Mid', '5 - Large'),
            '5 - Large' => array('4 - Mid', '5 - Large', '6 - Mega'),
            '6 - Mega' => array('5 - Large', '6 - Mega'),
        );

		$dly_lastdate = $extra['dly_lastdate'];
		if(strlen(trim($dly_lastdate)) < 8) {
			$dly_lastdate = $this->historylib->getDailyLastDate();
		}

		$orig_limit = $extra['limit'];
		$limit = (int)($orig_limit / 2);

        // 1. 같은 산업 내 기업 티커 수집
		$ticker_params = array();
		$ticker_params['=']['tkr_table'] = 'SF1';
		$ticker_params['=']['tkr_isdelisted'] = 'N';
		$ticker_params['!=']['tkr_exchange'] = 'OTC';
		$ticker_params['=']['tkr_industry'] = $industry;
		$ticker_params['in']['tkr_scalerevenue'] = $scale_group_map[$scale_revenue];

        $ticker_extra = array();
        $ticker_extra['order_by'] = ''; // 기본 PK desc 로 정의되는 'order by' 절이 없도록 하므로써 DBMS Sort 부하 절감.
        $ticker_extra['fields'] = 'tkr_ticker';
        $ticker_extra['slavedb'] = true;
        $ticker_extra['cache_sec'] = 3600*12;
        $competitor_tickers = array_keys($this->common->getDataByPk($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker'));
        // 2. 같은 산업 내 티커별 최신 MRT sf1_id 구하기. ("ticker" => "최신 MRT sf1_id"  map)
        //    이렇게 구한 sf1_id 기업 중에서만 찾으면 된다.
        $sf1_params = array();
        $sf1_params['in']['sf1_ticker'] = $competitor_tickers;
        $sf1_params['>']['sf1_calendardate'] = date('Y-m-d', mktime(0,0,0,1,1,date('Y')-1));
        $sf1_params['=']['sf1_dimension'] = 'MRT';

        $sf1_extra = array();
        $sf1_extra['fields'] = 'max(sf1_id) as sf1_id, sf1_ticker';
        $sf1_extra['group_by'] = 'sf1_ticker';
        $sf1_extra['order_by'] = '';
        $sf1_extra['slavedb'] = true;
        $sf1_extra['cache_sec'] = 3600*12;

        $sf1_ids = array_keys($this->common->getDataByPk($this->sf1_tb_model->getList($sf1_params, $sf1_extra)->getData(), 'sf1_id'));

        // 3. daily 마지막 날짜
        $dly_params = array();
        $dly_params['in']['dly_ticker'] = $competitor_tickers;
        $dly_params['>']['dly_date'] = date('Y-m-d', time()-86400*15);

        $dly_extra = array();
        $dly_extra['fields'] = 'max(dly_id) as dly_id, dly_ticker';
        $dly_extra['group_by'] = 'dly_ticker';
        $dly_extra['order_by'] = '';
        $dly_extra['slavedb'] = true;
        $dly_extra['cache_sec'] = 3600*12;

		$dly_ids = array_keys($this->common->getDataByPk($this->daily_tb_model->getList($dly_params, $dly_extra)->getData(), 'dly_id'));


        // 4. 대상 기업 중 revenue 조건으로 추리기
        $params = array();
		$params['=']['tkr_table'] = 'SF1';
		$params['=']['tkr_isdelisted'] = 'N';
		$params['=']['tkr_industry'] = $industry;
		$params['in']['tkr_scalerevenue'] = $scale_group_map[$scale_revenue];
		$params['join']['sf1_tb'] = 'tkr_ticker = sf1_ticker and sf1_dimension = "MRT"';
		$params['>']['sf1_revenueusd'] = $ticker_revenue;
        $params['in']['sf1_id'] = $sf1_ids;

		$params['join']['daily_tb'] = 'tkr_ticker = dly_ticker';
        $params['in']['dly_id'] = $dly_ids;

		$extra = array();
		$extra['order_by'] = 'sf1_revenueusd asc';
		$extra['limit'] = $orig_limit;
		$extra['fields'] = array(
		'tkr_ticker',
		'tkr_name',
		'tkr_currency',
		'tkr_category',
		//cp_korname
		'dly_marketcap',
		'sf1_revenueusd',
		'sf1_opinc',
		'sf1_netinc',
		'sf1_equity',
		'dly_pb',
		'dly_pe',
		'dly_ps',
		'sf1_roe',
		'sf1_roa',
		'sf1_roic',

	
		'sf1_epsusd',
		'sf1_fxusd',
		'sf1_netinccmnusd',
		'sf1_invcapavg',

		);

		$extra['slavedb'] = true;
		$extra['cache_sec'] = 3600*12;

		$preresult_above = $this->ticker_tb_model->getList($params, $extra)->getData(); // 필요한 만큼 담은 후 뒤집기 필요



		unset($params['>']['sf1_revenueusd']);
		$params['<=']['sf1_revenueusd'] = $ticker_revenue;

		$extra['order_by'] = 'sf1_revenueusd desc';
		$limit = $orig_limit - sizeof($preresult_above);
		$extra['limit'] = $orig_limit;
		$extra['slavedb'] = true;
		$extra['cache_sec'] = 3600*12;
		$preresult_below = $this->ticker_tb_model->getList($params, $extra)->getData();


		$result_above = array();
		$add_row_count = 4+((sizeof($preresult_below) >= 5) ? 0 : 5 - sizeof($preresult_below));
		foreach($preresult_above as $idx => $r) {
			if($idx >= $add_row_count) break;
			$result_above[] = $r;
		}
		$result_above = array_reverse($result_above);

		
		$result_below = array();
		$add_row_count = 6+((sizeof($preresult_above) >= 4) ? 0 : 4 - sizeof($preresult_above));
		foreach($preresult_below as $idx => $r) {
			if($idx >= $add_row_count) break;
			$result_below[] = $r;
		}

		$result = $this->common->getDataByPK(array_merge($result_above, $result_below), 'tkr_ticker');
		$result = $this->historylib->convert_list_to_usd($result); // USD 아닌기업 처리

		// 한글 회사명 덧붙이기
		$tickers = array_keys($result);

		$params = array();
		$params['in']['cp_ticker'] = $tickers;
		//$params['=']['cp_is_confirmed'] = 'YES';

		$extra = array();
		$extra['order_by'] = '';
		$extra['fields'] = 'cp_ticker, cp_korname';
		$extra['slavedb'] = true;
		$extra['cache_sec'] = 3600*12;

		$ticker_krname_map = $this->common->getDataByPK($this->company_tb_model->getList($params, $extra)->getData(), 'cp_ticker');
		foreach($ticker_krname_map as $ticker => $row) {
			if(strlen(trim($row['cp_korname'])) > 0) {
				$result[$ticker]['cp_korname'] = $row['cp_korname'];
			}
		}

		return $result;
	}

	// 특정 티커의 경쟁사 리스트 반환
	public function getCompetitor_nc($ticker, $sf1_row, $custom_extra = array()) {
		$default_extra = array(
			'dly_lastdate' => '',
			'limit' => 10,
		);
		$extra = array_merge($default_extra, $custom_extra);


		$tic = $ticker['tkr_ticker'];
		$industry = $ticker['tkr_industry'];
		$sf1_calendardate = $sf1_row['sf1_calendardate'];
		$ticker_revenue = $sf1_row['sf1_revenueusd'];
        $scale_revenue = $ticker['tkr_scalerevenue'];

        $scale_group_map = array(
            '' => array(''),
            '1 - Nano' => array('1 - Nano', '2 - Micro'),
            '2 - Micro' => array('1 - Nano', '2 - Micro', '3 - Small'),
            '3 - Small' => array('2 - Micro', '3 - Small', '4 - Mid'),
            '4 - Mid' => array('3 - Small', '4 - Mid', '5 - Large'),
            '5 - Large' => array('4 - Mid', '5 - Large', '6 - Mega'),
            '6 - Mega' => array('5 - Large', '6 - Mega'),
        );

		$dly_lastdate = $extra['dly_lastdate'];
		if(strlen(trim($dly_lastdate)) < 8) {
			$dly_lastdate = $this->historylib->getDailyLastDate();
		}

		$orig_limit = $extra['limit'];
		$limit = (int)($orig_limit / 2);

        // 1. 같은 산업 내 기업 티커 수집
		$ticker_params = array();
		$ticker_params['=']['tkr_table'] = 'SF1';
		$ticker_params['=']['tkr_isdelisted'] = 'N';
		$ticker_params['!=']['tkr_exchange'] = 'OTC';
		$ticker_params['=']['tkr_industry'] = $industry;
		$ticker_params['in']['tkr_scalerevenue'] = $scale_group_map[$scale_revenue];

        $ticker_extra = array();
        $ticker_extra['order_by'] = ''; // 기본 PK desc 로 정의되는 'order by' 절이 없도록 하므로써 DBMS Sort 부하 절감.
        $ticker_extra['fields'] = 'tkr_ticker';
        $ticker_extra['slavedb'] = true;
        //$ticker_extra['cache_sec'] = 3600*12;
        $competitor_tickers = array_keys($this->common->getDataByPk($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker'));
        // 2. 같은 산업 내 티커별 최신 MRT sf1_id 구하기. ("ticker" => "최신 MRT sf1_id"  map)
        //    이렇게 구한 sf1_id 기업 중에서만 찾으면 된다.
        $sf1_params = array();
        $sf1_params['in']['sf1_ticker'] = $competitor_tickers;
        $sf1_params['>']['sf1_calendardate'] = date('Y-m-d', mktime(0,0,0,1,1,date('Y')-1));
        $sf1_params['=']['sf1_dimension'] = 'MRT';

        $sf1_extra = array();
        $sf1_extra['fields'] = 'max(sf1_id) as sf1_id, sf1_ticker';
        $sf1_extra['group_by'] = 'sf1_ticker';
        $sf1_extra['order_by'] = '';
        $sf1_extra['slavedb'] = true;
        //$sf1_extra['cache_sec'] = 3600*12;

        $sf1_ids = array_keys($this->common->getDataByPk($this->sf1_tb_model->getList($sf1_params, $sf1_extra)->getData(), 'sf1_id'));

        // 3. daily 마지막 날짜
        $dly_params = array();
        $dly_params['in']['dly_ticker'] = $competitor_tickers;
        $dly_params['>']['dly_date'] = date('Y-m-d', time()-86400*15);

        $dly_extra = array();
        $dly_extra['fields'] = 'max(dly_id) as dly_id, dly_ticker';
        $dly_extra['group_by'] = 'dly_ticker';
        $dly_extra['order_by'] = '';
        $dly_extra['slavedb'] = true;
        //$dly_extra['cache_sec'] = 3600*12;

		$dly_ids = array_keys($this->common->getDataByPk($this->daily_tb_model->getList($dly_params, $dly_extra)->getData(), 'dly_id'));


        // 4. 대상 기업 중 revenue 조건으로 추리기
        $params = array();
		$params['=']['tkr_table'] = 'SF1';
		$params['=']['tkr_isdelisted'] = 'N';
		$params['=']['tkr_industry'] = $industry;
		$params['in']['tkr_scalerevenue'] = $scale_group_map[$scale_revenue];
		$params['join']['sf1_tb'] = 'tkr_ticker = sf1_ticker and sf1_dimension = "MRT"';
		$params['>']['sf1_revenueusd'] = $ticker_revenue;
        $params['in']['sf1_id'] = $sf1_ids;

		$params['join']['daily_tb'] = 'tkr_ticker = dly_ticker';
        $params['in']['dly_id'] = $dly_ids;

		$extra = array();
		$extra['order_by'] = 'sf1_revenueusd asc';
		$extra['limit'] = $orig_limit;
		$extra['fields'] = array(
		'tkr_ticker',
		'tkr_name',
		'tkr_currency',
		'tkr_category',
		//cp_korname
		'dly_marketcap',
		'sf1_revenueusd',
		'sf1_opinc',
		'sf1_netinc',
		'sf1_equity',
		'dly_pb',
		'dly_pe',
		'dly_ps',
		'sf1_roe',
		'sf1_roa',
		'sf1_roic',

	
		'sf1_epsusd',
		'sf1_fxusd',
		'sf1_netinccmnusd',
		'sf1_invcapavg',

		);

		$extra['slavedb'] = true;
		//$extra['cache_sec'] = 3600*12;

		$preresult_above = $this->ticker_tb_model->getList($params, $extra)->getData(); // 필요한 만큼 담은 후 뒤집기 필요



		unset($params['>']['sf1_revenueusd']);
		$params['<=']['sf1_revenueusd'] = $ticker_revenue;

		$extra['order_by'] = 'sf1_revenueusd desc';
		$limit = $orig_limit - sizeof($preresult_above);
		$extra['limit'] = $orig_limit;
		$extra['slavedb'] = true;
		//$extra['cache_sec'] = 3600*12;
		$preresult_below = $this->ticker_tb_model->getList($params, $extra)->getData();


		$result_above = array();
		$add_row_count = 4+((sizeof($preresult_below) >= 5) ? 0 : 5 - sizeof($preresult_below));
		foreach($preresult_above as $idx => $r) {
			if($idx >= $add_row_count) break;
			$result_above[] = $r;
		}
		$result_above = array_reverse($result_above);

		
		$result_below = array();
		$add_row_count = 6+((sizeof($preresult_above) >= 4) ? 0 : 4 - sizeof($preresult_above));
		foreach($preresult_below as $idx => $r) {
			if($idx >= $add_row_count) break;
			$result_below[] = $r;
		}

		$result = $this->common->getDataByPK(array_merge($result_above, $result_below), 'tkr_ticker');
		$result = $this->historylib->convert_list_to_usd($result); // USD 아닌기업 처리

		// 한글 회사명 덧붙이기
		$tickers = array_keys($result);

		$params = array();
		$params['in']['cp_ticker'] = $tickers;
		//$params['=']['cp_is_confirmed'] = 'YES';

		$extra = array();
		$extra['order_by'] = '';
		$extra['fields'] = 'cp_ticker, cp_korname';
		$extra['slavedb'] = true;
		//$extra['cache_sec'] = 3600*12;

		$ticker_krname_map = $this->common->getDataByPK($this->company_tb_model->getList($params, $extra)->getData(), 'cp_ticker');
		foreach($ticker_krname_map as $ticker => $row) {
			if(strlen(trim($row['cp_korname'])) > 0) {
				$result[$ticker]['cp_korname'] = $row['cp_korname'];
			}
		}

		return $result;
	}

	// 특정 티커의 산업 내 상위 기업 리스트 반환
	public function getIndustryTop($ticker, $sf1_row, $custom_extra = array()) {
		$default_extra = array(
			'limit' => 10,
		);
		$extra = array_merge($default_extra, $custom_extra);


		$tic = $ticker['tkr_ticker'];
		$industry = $ticker['tkr_industry'];
		$sf1_calendardate = $sf1_row['sf1_calendardate'];

		$limit = $extra['limit'];



        // 1. 같은 산업 내 기업 티커 수집
		$ticker_params = array();
		$ticker_params['=']['tkr_table'] = 'SF1';
		$ticker_params['=']['tkr_isdelisted'] = 'N';
		$ticker_params['!=']['tkr_exchange'] = 'OTC';
		$ticker_params['=']['tkr_industry'] = $industry;

        $ticker_extra = array();
        $ticker_extra['order_by'] = ''; // 기본 PK desc 로 정의되는 'order by' 절이 없도록 하므로써 DBMS Sort 부하 절감.
        $ticker_extra['fields'] = 'tkr_ticker, tkr_name';
        $competitor_tickers = array_keys($this->common->getDataByPk($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker'));
        // 2. 같은 산업 내 티커별 최신 MRT sf1_id 구하기. ("ticker" => "최신 MRT sf1_id"  map)
        //    이렇게 구한 sf1_id 기업 중에서만 찾으면 된다.
        $sf1_params = array();
        $sf1_params['in']['sf1_ticker'] = $competitor_tickers;
        $sf1_params['>']['sf1_calendardate'] = date('Y-m-d', mktime(0,0,0,1,1,date('Y')-1));
        $sf1_params['=']['sf1_dimension'] = 'MRT';

        $sf1_extra = array();
        $sf1_extra['fields'] = 'max(sf1_id) as sf1_id, sf1_ticker';
        $sf1_extra['group_by'] = 'sf1_ticker';
        $sf1_extra['order_by'] = '';

        $sf1_ids = array_keys($this->common->getDataByPk($this->sf1_tb_model->getList($sf1_params, $sf1_extra)->getData(), 'sf1_id'));

        // 3. daily 마지막 날짜
        $dly_params = array();
        $dly_params['in']['dly_ticker'] = $competitor_tickers;
        $dly_params['>']['dly_date'] = date('Y-m-d', time()-86400*15);

        $dly_extra = array();
        $dly_extra['fields'] = 'max(dly_id) as dly_id, dly_ticker';
        $dly_extra['group_by'] = 'dly_ticker';
        $dly_extra['order_by'] = '';

        $dly_ids = array_keys($this->common->getDataByPk($this->daily_tb_model->getList($dly_params, $dly_extra)->getData(), 'dly_id'));



        // 4. 대상 기업 중 revenue 조건으로 추리기
        $params = array();
		$params['=']['tkr_table'] = 'SF1';
		$params['=']['tkr_isdelisted'] = 'N';
		$params['!=']['tkr_exchange'] = 'OTC';
		$params['=']['tkr_industry'] = $industry;

		$params['join']['sf1_tb'] = 'tkr_ticker = sf1_ticker and sf1_dimension = "MRT"';
        $params['in']['sf1_id'] = $sf1_ids;

		$params['join']['daily_tb'] = 'tkr_ticker = dly_ticker';
        $params['in']['dly_id'] = $dly_ids;

		$extra = array();
		$extra['order_by'] = 'sf1_revenueusd desc';
		$extra['limit'] = $limit;
		$extra['fields'] = array(
		'tkr_ticker',
		'tkr_name',
		'tkr_currency',
		'tkr_category',
		//cp_korname
		'dly_marketcap',
		'sf1_revenueusd',
		'sf1_opinc',
		'sf1_netinc',
		'sf1_equity',
		'dly_pb',
		'dly_pe',
		'dly_ps',
		'sf1_roe',
		'sf1_roa',
		'sf1_roic',

	
		'sf1_epsusd',
		'sf1_fxusd',
		'sf1_netinccmnusd',
		'sf1_invcapavg',

		);


		$result = $this->common->getDataByPK($this->ticker_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
		$result = $this->historylib->convert_list_to_usd($result); // USD 아닌기업 처리

		// 한글 회사명 덧붙이기
		$tickers = array_keys($result);

		$params = array();
		$params['in']['cp_ticker'] = $tickers;
		//$params['=']['cp_is_confirmed'] = 'YES';

		$extra = array();
		$extra['order_by'] = '';
		$extra['fields'] = 'cp_ticker, cp_korname';

		$ticker_krname_map = $this->common->getDataByPK($this->company_tb_model->getList($params, $extra)->getData(), 'cp_ticker');
		foreach($ticker_krname_map as $ticker => $row) {
			if(strlen(trim($row['cp_korname'])) > 0) {
				$result[$ticker]['cp_korname'] = $row['cp_korname'];
			}
		}

		return $result;
	}

	protected function __filter($params) {
		$params['sf1_created_at'] = date('Y-m-d H:i:s');
		$params['sf1_updated_at'] = date('Y-m-d H:i:s');
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

    // lazy pattern
    private $ticker_6yroe_map = array();
    public function getTicker6YRoeMap() {
        if(sizeof($this->ticker_6yroe_map) <= 0) {
            $this->ticker_6yroe_map = unserialize(file_get_contents(WEBDATA.'/ticker_6yroe_map.info'));
        }
        return $this->ticker_6yroe_map;
    }

    private $ticker_weight_6yroe_map = array();
    public function getTickerWeight6YRoeMap() {
        if(sizeof($this->ticker_weight_6yroe_map) <= 0) {
            $this->ticker_weight_6yroe_map = unserialize(file_get_contents(WEBDATA.'/ticker_weight_6yroe_map.info'));
        }
        return $this->ticker_weight_6yroe_map;
    }

    private $ticker_6yevebitda_map = array();
    public function getTicker6YEvEbitdaMap() {
        if(sizeof($this->ticker_6yevebitda_map) <= 0) {
            $this->ticker_6yevebitda_map = unserialize(file_get_contents(WEBDATA.'/ticker_6yevebitda_map.info'));
        }
        return $this->ticker_6yroe_map;
    }

    public function make6YRoe() {
        // controller/daemon.php 의 make_6yroe() 에서 배치 돌며 생성한다. 일반 개발단에서 호출하지 않기!

        // select sf1_ticker, avg(sf1_roe)*100 as 6y_roe, avg(sf1_evebitda) as 6y_evebitda from sf1_tb where sf1_dimension = 'MRY' and sf1_datekey >= '2014-01-01' group by sf1_ticker;
        $params = array();
        $params['=']['sf1_dimension'] = 'MRY';

        $start_date = date('Y-m-d', mktime(0,0,0,1,1,date('Y')-6));
        $params['>=']['sf1_datekey'] = $start_date;

        $weight_for_minus = date('Y', strtotime($start_date))-1;

        $extra = array();
        $extra['fields'] = array(
            'sf1_ticker', 
            'avg(sf1_roe) * 100 as 6y_roe', 
            'sum(sf1_roe * (date_format(sf1_datekey, "%Y") - '.$weight_for_minus.')) / sum(date_format(sf1_datekey, "%Y") - '.$weight_for_minus.') * 100 as weight_6y_roe', 
            'avg(sf1_evebitda) as 6y_evebitda');
        $extra['order_by'] = '';
        $extra['group_by'] = 'sf1_ticker';

        $result = $this->getList($params, $extra)->getData();

        // 6yroe_map
        $map = $this->common->array2Map($result, 'sf1_ticker', '6y_roe');
        $tics = array_keys($map);
        $vals = array_values($map);
        array_multisort($vals, $tics);
        $avg6y_roe_map = array_combine($tics, $vals);
        file_put_contents(WEBDATA.'/ticker_6yroe_map.info', serialize($avg6y_roe_map));

        // weight_6yroe_map
        $map = $this->common->array2Map($result, 'sf1_ticker', 'weight_6y_roe');
        $tics = array_keys($map);
        $vals = array_values($map);
        array_multisort($vals, $tics);
        $avg6y_roe_map = array_combine($tics, $vals);
        file_put_contents(WEBDATA.'/ticker_weight_6yroe_map.info', serialize($avg6y_roe_map));

        // 6y_evebitda_map
        $map = $this->common->array2Map($result, 'sf1_ticker', '6y_evebitda');
        $tics = array_keys($map);
        $vals = array_values($map);
        array_multisort($vals, $tics);
        $avg6y_evebitda_map = array_combine($tics, $vals);
        file_put_contents(WEBDATA.'/ticker_6yevebitda_map.info', serialize($avg6y_evebitda_map));
    }


}

?>
