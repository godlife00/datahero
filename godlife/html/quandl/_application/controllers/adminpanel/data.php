<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once $_SERVER['DOCUMENT_ROOT'].'/_application/controllers/adminpanel/base_admin.php';

class Data extends BaseAdmin_Controller{
	public function sf1() {
		$this->load->model(DBNAME.'/sf1_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        	$data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('sf1_id','sf1_calendardate','sf1_datekey','sf1_reportperiod','sf1_lastupdated','sf1_accoci','sf1_assets','sf1_assetsavg','sf1_assetsc','sf1_assetsnc','sf1_assetturnover','sf1_bvps','sf1_capex','sf1_cashneq','sf1_cashnequsd','sf1_cor','sf1_consolinc','sf1_currentratio','sf1_de','sf1_debt','sf1_debtc','sf1_debtnc','sf1_debtusd','sf1_deferredrev','sf1_depamor','sf1_deposits','sf1_divyield','sf1_dps','sf1_ebit','sf1_ebitda','sf1_ebitdamargin','sf1_ebitdausd','sf1_ebitusd','sf1_ebt','sf1_eps','sf1_epsdil','sf1_epsusd','sf1_equity','sf1_equityavg','sf1_equityusd','sf1_ev','sf1_evebit','sf1_evebitda','sf1_fcf','sf1_fcfps','sf1_fxusd','sf1_gp','sf1_grossmargin','sf1_intangibles','sf1_intexp','sf1_invcap','sf1_invcapavg','sf1_inventory','sf1_investments','sf1_investmentsc','sf1_investmentsnc','sf1_liabilities','sf1_liabilitiesc','sf1_liabilitiesnc','sf1_marketcap','sf1_ncf','sf1_ncfbus','sf1_ncfcommon','sf1_ncfdebt','sf1_ncfdiv','sf1_ncff','sf1_ncfi','sf1_ncfinv','sf1_ncfo','sf1_ncfx','sf1_netinc','sf1_netinccmn','sf1_netinccmnusd','sf1_netincdis','sf1_netincnci','sf1_netmargin','sf1_opex','sf1_opinc','sf1_payables','sf1_payoutratio','sf1_pb','sf1_pe','sf1_pe1','sf1_ppnenet','sf1_prefdivis','sf1_price','sf1_ps','sf1_ps1','sf1_receivables','sf1_retearn','sf1_revenue','sf1_revenueusd','sf1_rnd','sf1_roa','sf1_roe','sf1_roic','sf1_ros','sf1_sbcomp','sf1_sgna','sf1_sharefactor','sf1_sharesbas','sf1_shareswa','sf1_shareswadil','sf1_sps','sf1_tangibles','sf1_taxassets','sf1_taxexp','sf1_taxliabilities','sf1_tbvps','sf1_workingcapital','sf1_created_at','sf1_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;
			$extra['fields'] = 'sf1_tb.*, tkr_name';

			$params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker and tkr_table = "SF1"';

			$list = $this->sf1_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->sf1_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['sf1_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['sf1_created_at'] = date('Y-m-d H:i:s');
				$data['sf1_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->sf1_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->sf1_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->sf1_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'sf1_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->sf1_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->sf1_tb_model->getData();
                        $this->sf1_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf1_tb');
                    }
					return;
				}
				
				// row 수정
				$request['sf1_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->sf1_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf1_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->sf1_tb_model->get($id)->isSuccess()){
                            $del_data = $this->sf1_tb_model->getData();
                            $this->sf1_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf1_tb');
                        }
					} else {
						$this->sf1_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf1_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sf1', $data);
	}
	public function sf1_detail($sf1_id) {
		$this->load->model(array(
			DBNAME.'/sf1_tb_model',
			DBNAME.'/ticker_tb_model',
		));

		$this->sf1_tb_model->get($sf1_id);
		if( ! $this->sf1_tb_model->isSuccess()) {
			$this->common->historyback();
			return;
		}
		$data = $this->sf1_tb_model->getData();

		$ticker = $this->ticker_tb_model->get(array('tkr_ticker' => $data['sf1_ticker'], 'tkr_table' => 'SF1'))->getData();
		$this->_view('data/sf1_detail', array(
			'values' => $data,
			'ticker' => $ticker,
			'field_title_map' => array(),
		));
	}

	public function ticker() {
		$this->load->model(DBNAME.'/ticker_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('tkr_id','tkr_lastupdated','tkr_firstadded','tkr_firstpricedate','tkr_lastpricedate','tkr_firstquarter','tkr_lastquarter','tkr_created_at','tkr_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->ticker_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->ticker_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['tkr_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['tkr_created_at'] = date('Y-m-d H:i:s');
				$data['tkr_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->ticker_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->ticker_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->ticker_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'ticker_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->ticker_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->ticker_tb_model->getData();
                        $this->ticker_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'ticker_tb');
                    }
					return;
				}
				
				// row 수정
				$request['tkr_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->ticker_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'ticker_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->ticker_tb_model->get($id)->isSuccess()){
                            $del_data = $this->ticker_tb_model->getData();
                            $this->ticker_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'ticker_tb');
                        }
					} else {
						$this->ticker_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'ticker_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/ticker', $data);
	}

	public function indicator() {
		$this->load->model(DBNAME.'/indicator_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('idc_id','idc_created_at','idc_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->indicator_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->indicator_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['idc_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['idc_created_at'] = date('Y-m-d H:i:s');
				$data['idc_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->indicator_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->indicator_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->indicator_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'indicator_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->indicator_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->indicator_tb_model->getData();
                        $this->indicator_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'indicator_tb');
                    }
					return;
				}
				
				// row 수정
				$request['idc_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->indicator_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'indicator_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->indicator_tb_model->get($id)->isSuccess()){
                            $del_data = $this->indicator_tb_model->getData();
                            $this->indicator_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'indicator_tb');
                        }
					} else {
						$this->indicator_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'indicator_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/indicator', $data);
	}

	public function sf2() {
		$this->load->model(DBNAME.'/sf2_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('sf2_id','sf2_filingdate','sf2_transactiondate','sf2_sharesownedbeforetransaction','sf2_transactionshares','sf2_sharesownedfollowingtransaction','sf2_transactionpricepershare','sf2_transactionvalue','sf2_dateexercisable','sf2_priceexercisable','sf2_expirationdate','sf2_rownum','sf2_created_at','sf2_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->sf2_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->sf2_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['sf2_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['sf2_created_at'] = date('Y-m-d H:i:s');
				$data['sf2_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->sf2_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->sf2_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->sf2_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'sf2_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->sf2_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->sf2_tb_model->getData();
                        $this->sf2_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf2_tb');
                    }
					return;
				}
				
				// row 수정
				$request['sf2_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->sf2_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf2_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->sf2_tb_model->get($id)->isSuccess()){
                            $del_data = $this->sf2_tb_model->getData();
                            $this->sf2_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf2_tb');
                        }
					} else {
						$this->sf2_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf2_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sf2', $data);
	}

	public function sf3() {
		$this->load->model(DBNAME.'/sf3_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('sf3_id','sf3_calendardate','sf3_value','sf3_units','sf3_price','sf3_created_at','sf3_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->sf3_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->sf3_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['sf3_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['sf3_created_at'] = date('Y-m-d H:i:s');
				$data['sf3_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->sf3_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->sf3_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->sf3_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'sf3_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->sf3_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->sf3_tb_model->getData();
                        $this->sf3_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf3_tb');
                    }
					return;
				}
				
				// row 수정
				$request['sf3_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->sf3_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf3_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->sf3_tb_model->get($id)->isSuccess()){
                            $del_data = $this->sf3_tb_model->getData();
                            $this->sf3_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf3_tb');
                        }
					} else {
						$this->sf3_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf3_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sf3', $data);
	}

	public function sep() {
		$this->load->model(array(
            DBNAME.'/sep_tb_model',
            DBNAME.'/ticker_tb_model',
        ));
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('sep_id','sep_date','sep_open','sep_high','sep_low','sep_close','sep_volume','sep_dividends','sep_closeunadj','sep_lastupdated','sep_created_at','sep_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->sep_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->sep_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
            foreach($list as $k=>$r){
                $strIndustry = '';
                $strIndustry =  $this->ticker_tb_model->getTickerInfo($r['sep_ticker']);
                if(!$strIndustry) $strIndustry = 'null';
                $r['tkr_industry'] = $strIndustry;
                $json_data->rows[$k]['id'] = $r['sep_id'];
                $json_data->rows[$k]['cell'] = $r;
            }

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['sep_created_at'] = date('Y-m-d H:i:s');
				$data['sep_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->sep_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->sep_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->sep_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'sep_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->sep_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->sep_tb_model->getData();
                        $this->sep_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sep_tb');
                    }
					return;
				}
				
				// row 수정
				$request['sep_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->sep_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sep_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->sep_tb_model->get($id)->isSuccess()){
                            $del_data = $this->sep_tb_model->getData();
                            $this->sep_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sep_tb');
                        }
					} else {
						$this->sep_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sep_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sep', $data);
	}



	public function sf3a() {
		$this->load->model(DBNAME.'/sf3a_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('sf3a_id','sf3a_calendardate','sf3a_shrholders','sf3a_cllholders','sf3a_putholders','sf3a_wntholders','sf3a_dbtholders','sf3a_prfholders','sf3a_fndholders','sf3a_undholders','sf3a_shrunits','sf3a_cllunits','sf3a_putunits','sf3a_wntunits','sf3a_dbtunits','sf3a_prfunits','sf3a_fndunits','sf3a_undunits','sf3a_shrvalue','sf3a_cllvalue','sf3a_putvalue','sf3a_wntvalue','sf3a_dbtvalue','sf3a_prfvalue','sf3a_fndvalue','sf3a_undvalue','sf3a_totalvalue','sf3a_percentoftotal','sf3a_created_at','sf3a_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->sf3a_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->sf3a_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['sf3a_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['sf3a_created_at'] = date('Y-m-d H:i:s');
				$data['sf3a_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->sf3a_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->sf3a_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->sf3a_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'sf3a_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->sf3a_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->sf3a_tb_model->getData();
                        $this->sf3a_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf3a_tb');
                    }
					return;
				}
				
				// row 수정
				$request['sf3a_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->sf3a_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf3a_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->sf3a_tb_model->get($id)->isSuccess()){
                            $del_data = $this->sf3a_tb_model->getData();
                            $this->sf3a_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf3a_tb');
                        }
					} else {
						$this->sf3a_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf3a_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sf3a', $data);
	}


	public function sf3b() {
		$this->load->model(DBNAME.'/sf3b_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('sf3b_id','sf3b_calendardate','sf3b_shrholdings','sf3b_cllholdings','sf3b_putholdings','sf3b_wntholdings','sf3b_dbtholdings','sf3b_prfholdings','sf3b_fndholdings','sf3b_undholdings','sf3b_shrunits','sf3b_cllunits','sf3b_putunits','sf3b_wntunits','sf3b_dbtunits','sf3b_prfunits','sf3b_fndunits','sf3b_undunits','sf3b_shrvalue','sf3b_cllvalue','sf3b_putvalue','sf3b_wntvalue','sf3b_dbtvalue','sf3b_prfvalue','sf3b_fndvalue','sf3b_undvalue','sf3b_totalvalue','sf3b_percentoftotal','sf3b_created_at','sf3b_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->sf3b_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->sf3b_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['sf3b_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['sf3b_created_at'] = date('Y-m-d H:i:s');
				$data['sf3b_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->sf3b_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->sf3b_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->sf3b_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'sf3b_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->sf3b_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->sf3b_tb_model->getData();
                        $this->sf3b_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf3b_tb');
                    }
					return;
				}
				
				// row 수정
				$request['sf3b_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->sf3b_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf3b_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->sf3b_tb_model->get($id)->isSuccess()){
                            $del_data = $this->sf3b_tb_model->getData();
                            $this->sf3b_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sf3b_tb');
                        }
					} else {
						$this->sf3b_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf3b_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sf3b', $data);
	}


	public function company() {
		$this->load->model(DBNAME.'/company_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();
            $extra['fields'] = 'company_tb.*, ticker_tb.tkr_isdelisted';

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('cp_id','cp_created_at','cp_updated_at'));
			}
            $params['join']['ticker_tb'] = 'cp_ticker = tkr_ticker and tkr_table = "SF1"';
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->company_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->company_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['cp_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['cp_created_at'] = date('Y-m-d H:i:s');
				$data['cp_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->company_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->company_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->company_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'company_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->company_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->company_tb_model->getData();
                        $this->company_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'company_tb');
                    }
					return;
				}
				
				// row 수정
				$request['cp_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->company_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'company_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->company_tb_model->get($id)->isSuccess()){
                            $del_data = $this->company_tb_model->getData();
                            $this->company_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'company_tb');
                        }
					} else {
						$this->company_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'company_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/company', $data);
	}




	public function company_detail($id=0) {
		$this->load->model(array(
			DBNAME.'/company_tb_model',
			DBNAME.'/ticker_tb_model',
		));

		$id = intval($id);

		$assign = array();
		$assign['mode'] = 'insert';
		$assign['pk'] = 'cp_id';
		
		if($id > 0) {
			$this->company_tb_model->get($id);
			if($this->company_tb_model->isSuccess() == false) {
				$this->common->alert('pk '.$id.' is empty.');
				$this->common->locationhref('/adminpanel/data/company?keep=yes');
				return;
			}
			$assign['mode'] = 'update';
			$values = $this->company_tb_model->getData();
			foreach($values as $field => &$value) {
				switch($field) {
					// todo. 디폴트값 설정은 여기서
					/*
					case 'g_status' : 
						$value = form_dropdown('dr_groups[]', $user_group_sel, $selected_groups, "multiple style='height:200px;width:215px;' required");
						break;
					*/
					default : 
						$value = htmlspecialchars($value);
				}
			}
			if($this->ticker_tb_model->get(array(
				'tkr_ticker' => $values['cp_ticker'],
				'tkr_table' => 'SF1',
			))->isSuccess()) {
				$ticker_info = $this->ticker_tb_model->getData();
				
				if($ticker_info['tkr_exchange'] != $values['cp_exchange']) {
					$values['cp_exchange'] = $ticker_info['tkr_exchange'];
					$this->company_tb_model->doUpdate($id, array(
						'cp_exchange' => $ticker_info['tkr_exchange'],
					));
				}
			}

			$assign['values'] = $values;
		} else {
			// insert
			$fields = $this->company_tb_model->getFields();
			$values = array();
			foreach($fields as $field) {
				switch($field) {
					// todo. 디폴트값 설정은 여기서
					/*
					case 'g_created_at' : 
						$values[$field] = date('Y-m-d H:i:s');
						break;
					*/
					default : 
						$values[$field] = '';
				}
			}
			$assign['values'] = $values;
		}

		$exchange_types = $this->company_tb_model->getExchangeTypes();
		$exchange_types = array_merge(array('' => '선택'), $exchange_types);
		$assign['select_exchange'] = form_dropdown('cp_exchange', $exchange_types, $assign['values']['cp_exchange'], 'required');

		$yes_no = array(''=> '선택', 'YES' => 'YES', 'NO' => 'NO');
		$no_yes  = array(''=> '선택', 'NO' => 'NO', 'YES' => 'YES');
		$assign['select_is_confirmed'] = form_dropdown('cp_is_confirmed', $no_yes, $assign['values']['cp_is_confirmed'], 'required');
		$assign['select_is_dow30'] = form_dropdown('cp_is_dow30', $yes_no, $assign['values']['cp_is_dow30'], 'required');
		$assign['select_is_nasdaq100'] = form_dropdown('cp_is_nasdaq100', $yes_no, $assign['values']['cp_is_nasdaq100'], 'required');
		$assign['select_is_snp500'] = form_dropdown('cp_is_snp500', $yes_no, $assign['values']['cp_is_snp500'], 'required');




		$this->_view('data/company_detail', $assign);
	}

	public function company_process() {
		$request = $this->input->post();

		$id = $request['cp_id'];
		unset($request['cp_id']);

		$edit_url = '/adminpanel/data/company_detail/'.$id;
		$list_url = '/adminpanel/data/company?keep=yes';

		$this->load->model(DBNAME.'/company_tb_model');

		$field_list = $this->company_tb_model->getFields();

		$data = array();
		foreach($field_list as $key) {
			if(array_key_exists($key, $request)) {
				$data[$key] = $request[$key];
				continue;
			}

			if(array_key_exists($key.'_date', $request) && array_key_exists($key.'_time', $request)) {
				$data[$key] = $request[$key.'_date'].' '.$request[$key.'_time'];
			}
		}

		if($request['mode'] == 'update') {
			$data['cp_updated_at'] = date('Y-m-d H:i:s');
			// todo. add update data set.


			if($this->company_tb_model->doUpdate($id, $data)->isSuccess() == false) {
				$this->common->alert($this->company_tb_model->getErrorMsg());
			}
			$this->common->locationhref($edit_url);
			return;
		} else if($request['mode'] == 'insert') {
			$data['cp_created_at'] = date('Y-m-d H:i:s');
			$data['cp_updated_at'] = date('Y-m-d H:i:s');
			// todo. add update data set.

			if($this->company_tb_model->doInsert($data)->isSuccess() == false) {
				$this->common->alert($this->company_tb_model->getErrorMsg());
			}
			$id = $this->company_tb_model->getData();
			$edit_url = '/adminpanel/data/company_detail/'.$id;
			$this->common->locationhref($edit_url);
			return;
		} else if($request['mode'] == 'delete') {
			if($this->company_tb_model->doDelete($id)->isSuccess() == false) {
				$this->common->alert($this->company_tb_model->getErrorMsg());
			}
			$this->common->locationhref($list_url);
			return;
		}
	}
	public function daily($type='all') {
		$this->load->model(DBNAME.'/daily_tb_model');
		$this->load->model('business/historylib');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        	$data = array(
			'type' => $type,
		);

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('dly_id','dly_date','dly_lastupdated','dly_ev','dly_evebit','dly_evebitda','dly_marketcap','dly_pb','dly_pe','dly_ps','dly_created_at','dly_updated_at'));
			}

			if($type == 'last_daily') {
				$last_daily = $this->historylib->getDAilyLastRow();
				$params['=']['dly_date'] = $last_daily['dly_date'];
				$params['join']['ticker_tb'] = 'tkr_ticker = dly_ticker and tkr_table = "SF1" and tkr_isdelisted = "N"';
				$params['in']['tkr_exchange'] = array(
					'NYSE', 
					'NASDAQ', 
					'NYSEMKT',
				);

				$extra['fields'] = 'daily_tb.*';
			}			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->daily_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->daily_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['dly_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['dly_created_at'] = date('Y-m-d H:i:s');
				$data['dly_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->daily_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->daily_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->daily_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'daily_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->daily_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->daily_tb_model->getData();
                        $this->daily_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'daily_tb');
                    }
					return;
				}
				
				// row 수정
				$request['dly_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->daily_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'daily_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->daily_tb_model->get($id)->isSuccess()){
                            $del_data = $this->daily_tb_model->getData();
                            $this->daily_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'daily_tb');
                        }
					} else {
						$this->daily_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'daily_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/daily', $data);
	}

	public function actions() {
		$this->load->model(DBNAME.'/actions_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('act_id','act_date','act_value','act_created_at','act_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->actions_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->actions_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['act_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['act_created_at'] = date('Y-m-d H:i:s');
				$data['act_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->actions_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->actions_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->actions_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'actions_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->actions_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->actions_tb_model->getData();
                        $this->actions_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'actions_tb');
                    }
					return;
				}
				
				// row 수정
				$request['act_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->actions_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'actions_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->actions_tb_model->get($id)->isSuccess()){
                            $del_data = $this->actions_tb_model->getData();
                            $this->actions_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'actions_tb');
                        }
					} else {
						$this->actions_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'actions_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/actions', $data);
	}

	public function sp500() {
		$this->load->model(DBNAME.'/sp500_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('sp5_id','sp5_date','sp5_created_at','sp5_updated_at'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->sp500_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->sp500_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['sp5_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['sp5_created_at'] = date('Y-m-d H:i:s');
				$data['sp5_updated_at'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->sp500_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->sp500_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->sp500_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'sp500_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->sp500_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->sp500_tb_model->getData();
                        $this->sp500_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sp500_tb');
                    }
					return;
				}
				
				// row 수정
				$request['sp5_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->sp500_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sp500_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->sp500_tb_model->get($id)->isSuccess()){
                            $del_data = $this->sp500_tb_model->getData();
                            $this->sp500_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'sp500_tb');
                        }
					} else {
						$this->sp500_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sp500_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sp500', $data);
	}
	public function sf_test() {
		$this->load->model(DBNAME.'/myitem_tb_model');
		$sess = $this->session->userdata('admin');
		$request = $this->input->get();

        $data = array();

		if(isset($request['mode']) && $request['mode'] == 'list') {
			// ajax reqeust. ==> grid list data 

			$page = $this->input->get('page');
			$limit = $this->input->get('rows');
			$_search = $this->input->get('_search');

			$params = array();
			$extra = array();

			if(isset($_GET['filters'])){
				$filters = $_GET['filters'];
				$params = $this->common->filter_to_params($filters, $_search, array('my_fdate','my_udate'));
			}
			

			if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
				$order_by = $request['sidx']." ".$request['sord'];
				$extra['order_by'] = array($order_by);
			}

			$extra['offset'] = ($page-1)*$limit;
			$extra['limit'] = $limit;

			$list = $this->myitem_tb_model->getList($params, $extra)->getData();

			$totalCount = $this->myitem_tb_model->getCount($params)->getData();

			$json_data = new stdClass;
			$json_data->rows = array();
			foreach($list as $k=>$r){
					$json_data->rows[$k]['id'] = $r['my_id'];
					$json_data->rows[$k]['cell'] = $r;
			}

			$json_data->total = ceil($totalCount / $limit);
			$json_data->page = $page;
			$json_data->records = $totalCount;

			echo json_encode($json_data);
			return;
		}
		if(isset($request['mode']) && $request['mode'] == 'edit') {
			$request = $this->input->post();

			if(isset($request['oper']) && $request['oper'] == 'add') {
				$data = $request;
				unset($data['oper']);
				unset($data['id']);

				$data['my_fdate'] = date('Y-m-d H:i:s');
				$data['my_udate'] = date('Y-m-d H:i:s');

				// todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

				if($this->myitem_tb_model->doInsert($data)->isSuccess() == false) {
					print_r($this->myitem_tb_model->getErrorMsg());
					return;
				}

                $act_key = $this->myitem_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'myitem_tb');
				return;
			}

			if(isset($request['id']) && isset($request['ids']) == false) {
				// 1 row 수정
				if($request['edit_action_is'] == 'DEL') {
					// 삭제 상태로 셋팅. 삭제.
                    if($this->myitem_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->myitem_tb_model->getData();
                        $this->myitem_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'myitem_tb');
                    }
					return;
				}
				
				// row 수정
				$request['my_udate'] = date('Y-m-d H:i:s');
				//todo. Update시 추가 셋팅값 여기서 채우기. 함승목

				$this->myitem_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'myitem_tb');
				return;
			}

			// 일괄 상태변경

			$ids = explode(',',$request['ids']);

			if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
				$data = $request['multi_update_field_data_map'];
				foreach($ids as $id) {
					if($request['edit_action_is'] == 'DEL') {
                        if($this->myitem_tb_model->get($id)->isSuccess()){
                            $del_data = $this->myitem_tb_model->getData();
                            $this->myitem_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'myitem_tb');
                        }
					} else {
						$this->myitem_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'myitem_tb');
					}
				}
				echo 'success';
			} else {
				echo 'fail';
			}
			return;

		}

		$this->_view('data/sf_test', $data);
	}

}
