
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
