<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once $_SERVER['DOCUMENT_ROOT'].'/_application/controllers/adminpanel/base_admin.php';

class Main extends BaseAdmin_Controller{

	public function index($tic='AAPL') {
		$this->load->model(array(
					'business/historylib',
					DBNAME.'/company_tb_model',
					));
		$request = $this->input->get();
		$dimension = $request['dimension'];
		if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
			$dimension = 'MRY';
		}
		$cell_type = $request['cell_type'];
		if( ! in_array($cell_type, array('data', 'ratio'))) {
			$cell_type = 'data';
		}

		if($cell_type == 'data') {
			$this->historylib->getFinStateList($tic, $dimension);
		} else {
			$this->historylib->getFinStateRatioList($tic, $dimension);
		}

		if( ! $this->historylib->isSuccess()) {
			echo $this->historylib->getErrorMsg();
			return;
		}

		$result = $this->historylib->getData();
		$ticker = $result['ticker'];
		$findata = $result['findata'];
		$sepdata = $result['sepdata'];

		$data = array();
		$data['dimension'] = $dimension;
		$data['cell_type'] = $cell_type;

		// 재무상태표 리스트
		$data['balancesheet_fields'] = $this->sf1_tb_model->getBalancesheetFields($ticker, $findata[0]);
		$data['balancesheet_titles'] = $this->historylib->getTableMap($result['balancesheet_type'], 'Indicator', 'kortitle');

		// 손익계산서 리스트
		$data['incomestate_fields'] = $this->sf1_tb_model->getIncomestateFields();
		$data['incomestate_titles'] = $this->historylib->getTableMap('incomestate', 'Indicator', 'kortitle');

		//현금흐름표 리스트
		$data['cashflow_fields'] = $this->sf1_tb_model->getCashflowFields();
		$data['cashflow_titles'] = $this->historylib->getTableMap('cashflow', 'Indicator', 'kortitle');

		// 재무&투자지표
		$data['fininvestindi_fields'] = $this->sf1_tb_model->getFinInvestIndiFields();
		$data['fininvestindi_titles'] = $this->historylib->getTableMap('fininvestindi', 'Indicator', 'kortitle');

		// 주가지표
		$sep_title_map = $this->historylib->getTableMap('pricesheet', 'Indicator', 'kortitle');
		$data['pricesheet_fields'] = array_keys($sep_title_map);
		$data['pricesheet_titles'] = $sep_title_map;

		$data['last_sep'] = $sepdata[0];

		// 기업정보
		$company_info = array();
		if($this->company_tb_model->get(array('cp_ticker' => $ticker['tkr_ticker']))->isSuccess()) {
			$company_info = $this->company_tb_model->getData();
		}
		$data['company_info'] = $company_info;



		// @ 기업 검색 리스트
		// 1. 유효 기업 전체 가져오기
		$ticker_params = array();
		$ticker_params['=']['tkr_table'] = 'SF1';
		$ticker_params['=']['tkr_isdelisted'] = 'N';

		$ticker_extra = array(
				'order_by' => 'tkr_ticker',
				'fields' => 'tkr_ticker, tkr_permaticker, tkr_name',
				);
		$ticker_list = $this->common->getDataByPK($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');

		// 2. 한국어 기업 정리
		$comp_params = array();
		$comp_params['=']['cp_is_confirmed'] = 'YES';

		$comp_extra = array(
				'order_by' => '',
				'fields' => 'cp_ticker, cp_korname'
				);
		$comp_list = $this->common->getDataByPK($this->company_tb_model->getList($comp_params, $comp_extra)->getData(), 'cp_ticker');

		// 3. 조합 
		$search_list = array();
		foreach($ticker_list as $t => $row) {
			$txt = sprintf('%s | %s (%s) ', $row['tkr_ticker'], $row['tkr_name'], $row['tkr_permaticker']);
			if(isset($comp_list[$t])) {
				$txt .= ' - '.$comp_list[$t]['cp_korname'];
			}
			$search_list[] = $txt;
		}
		$data['search_list'] = $search_list;
		$data['search_tickers'] = array_keys($ticker_list);
		// End of - 기업 검색 리스트





		// 재무&투자지표 표출형식
		$data['fininvestindi_format'] = $this->historylib->getTableMap('fininvestindi', 'Indicator', 'korunittype');
		$data['pricesheet_format'] = $this->historylib->getTableMap('pricesheet', 'Indicator', 'unittype');

		$data['data'] = $this->common->getDataByPK($findata, 'sf1_datekey');
		$data['ticker'] = $ticker;
		$data['sepdata'] = $sepdata;

		$exist_data = $this->session->all_userdata();
		if($exist_data['admin']['level'] == '2') {
			$this->common->locationhref('/adminpanel/data/company');
		}
		else {
			$this->_view('index', $data);
		}
	}


	// Rank list
	public function rank() {
		$this->load->model(array(
					'business/historylib',
					));

		$per_list = $this->historylib->getRankList('per');
		echo '<pre>';
		print_r($per_list);
		echo '</pre>';

		$pbr_list = $this->historylib->getRankList('pbr');
		echo '<pre>';
		print_r($pbr_list);
		echo '</pre>';

		$roe_list = $this->historylib->getRankList('roe');
		echo '<pre>';
		print_r($roe_list);
		echo '</pre>';

		$yield_list = $this->historylib->getRankList('yield');
		echo '<pre>';
		print_r($yield_list);
		echo '</pre>';
	}





}
?>
