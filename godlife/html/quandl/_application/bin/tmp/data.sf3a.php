
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

				// todo. $request ????????? ?????? ????????? ?????? ??? ???????????? ?????????. ?????????.

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
				// 1 row ??????
				if($request['edit_action_is'] == 'DEL') {
					// ?????? ????????? ??????. ??????.
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
				
				// row ??????
				$request['sf3a_updated_at'] = date('Y-m-d H:i:s');
				//todo. Update??? ?????? ????????? ????????? ?????????. ?????????

				$this->sf3a_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'sf3a_tb');
				return;
			}

			// ?????? ????????????

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
