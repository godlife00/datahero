	public function sep() {
		$this->load->model(DBNAME.'/sep_tb_model');
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
