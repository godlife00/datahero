<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once $_SERVER['DOCUMENT_ROOT'].'/_application/controllers/adminpanel/base_admin.php';

class Main extends BaseAdmin_Controller{

    public function index() {
        $this->recommend();
        return;
    }

	public function recommend_profit() {
	
        if($this->input->is_ajax_request() === FALSE) {
            $this->common->alert('잘못된 접근입니다.');
			$this->common->locationhref('/');
			exit;
		}

		$pf_profit = $this->input->get('pf_profit');

		if($pf_profit=='') {
			$result = array('error' => '수익률 값이 없습니다.');
			exit(json_encode($result));
		}
		$data = array();
        $data['pf_profit'] = $pf_profit;
        $data = serialize($data);

		$pf_profit_file = 'pf_profit.json';
        $file_path = WEBDATA.'/'.$pf_profit_file;
        $file_backpath = $file_path . '.bak';
        
        touch($file_backpath);
        file_put_contents($file_backpath, $data);
        rename($file_backpath, $file_path);

		$success = TRUE;
		$result = array('success' => $success, 'val' => $pf_profit);
		exit(json_encode($result));
	}

    /**
     * 종목추천 리스트
    */
    public function recommend() {
        $this->load->model(DBNAME.'/recommend_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->get();

        $data = array();
        $active_map = $this->recommend_tb_model->getActiveMap();
        $data['active_map_sel'] = $this->common->genJqgridOption($active_map);
        $data['active_map'] = $this->common->genJqgridOption($active_map, true);

        $use_chart_map = $this->recommend_tb_model->getUseChartMap();
        $data['use_chart_map_sel'] = $this->common->genJqgridOption($use_chart_map);
        $data['use_chart_map'] = $this->common->genJqgridOption($use_chart_map, true);

        $endtype_map = $this->recommend_tb_model->getEndTypeMap();
        $data['endtype_map_sel'] = $this->common->genJqgridOption($endtype_map);

        $portfolio_map = $this->recommend_tb_model->getPortfolioMap();
        $data['portfolio_map_sel'] = $this->common->genJqgridOption($portfolio_map);
        $data['portfolio_map'] = $this->common->genJqgridOption($portfolio_map, true);

        $adjust_map = $this->recommend_tb_model->getAdjustMap();
        $data['adjust_map_sel'] = $this->common->genJqgridOption($adjust_map);
        $data['adjust_map'] = $this->common->genJqgridOption($adjust_map, true);

		$view_srv_map = $this->recommend_tb_model->getViewSrvMap();
        $data['view_srv_map_sel'] = $this->common->genJqgridOption($view_srv_map);
        $data['view_srv_map'] = $this->common->genJqgridOption($view_srv_map, true);

        if(isset($request['mode']) && $request['mode'] == 'list') {
            // ajax reqeust. ==> grid list data 

            $page = $this->input->get('page');
            $limit = $this->input->get('rows');
            $_search = $this->input->get('_search');
//echo 'search--->'.$_search;
            $params = array();
            $extra = array();

            if(isset($_GET['filters'])){
                $filters = $_GET['filters'];
                $params = $this->common->filter_to_params($filters, $_search, array('rc_id','tkr_close','rc_recom_price','rc_giveup_price','rc_goal_price','rc_display_date','rc_enddate','rc_view_count','rc_created_at','rc_updated_at'));
            }

            if(isset($params['=']['rc_ticker']) && strlen($params['=']['rc_ticker']) > 0) {
                $params['=']['rc_ticker'] = strtoupper($params['=']['rc_ticker']);
            }

            $params['join']['admin_tb'] = 'a_id = rc_admin_id';
            $params['join']['ticker_tb'] = 'rc_ticker = tkr_ticker';
            
            $extra['fields'] = array('rc_id','rc_ticker','rc_title','rc_subtitle','rc_recom_price','rc_giveup_price','rc_goal_price','rc_use_chart','rc_display_date','rc_portfolio','rc_view_srv','rc_view_count','rc_is_active','rc_endtype','rc_enddate','rc_created_at','rc_updated_at','a_loginid','tkr_name','tkr_close','tkr_rate_str');
            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->recommend_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->recommend_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $r['rc_ticker'] = $r['rc_ticker'].'('.$r['tkr_name'].')';
                $r['tkr_close'] = $r['tkr_close'].' '.$r['tkr_rate_str'];
                $r['rc_is_active'] = $active_map[$r['rc_is_active']];
                $r['rc_use_chart'] = $use_chart_map[$r['rc_use_chart']];
                $r['rc_endtype'] = $endtype_map[$r['rc_endtype']];
                $r['rc_portfolio'] = $portfolio_map[$r['rc_portfolio']];
                $r['rc_adjust'] = $adjust_map[$r['rc_adjust']];
                $r['rc_view_srv'] = $view_srv_map[$r['rc_view_srv']];
                $r['rc_title'] = $r['rc_title'];

                $json_data->rows[$k]['id'] = $r['rc_id'];
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

                $data['rc_created_at'] = date('Y-m-d H:i:s');
                $data['rc_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->recommend_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->recommend_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->recommend_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'recommend_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if(isset($request['edit_action_is']) && $request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->recommend_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->recommend_tb_model->getData();
                        $this->recommend_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'recommend_tb');
                    }
                    return;
                }
                
                // row 수정
                $request['rc_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->recommend_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'recommend_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->recommend_tb_model->get($id)->isSuccess()){
                            $del_data = $this->recommend_tb_model->getData();
                            $this->recommend_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'recommend_tb');
                        }
                    } else {
                        $this->recommend_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'recommend_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

		//포트폴리오 수익률
		$pf_profit_file = 'pf_profit.json';
		$file_path = WEBDATA.'/'.$pf_profit_file;
		if( is_file($file_path) ) {
            $file_data = unserialize(file_get_contents($file_path));
			$data['pf_profit'] = $file_data['pf_profit'];
		}
		else {
			$data['pf_profit'] = '0.00';
		}

        $this->_view('main/recommend', $data);
    }

    /**
     * 종목추천 등록/수정 폼
    */
    public function recommend_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/recommend_tb_model',
            DBNAME.'/admin_tb_model'
        ));
        $this->load->helper('form');

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/recommend?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'rc_id';

        if($id > 0) {
            $this->recommend_tb_model->get($id);
            if($this->recommend_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/recommend?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->recommend_tb_model->getData();
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

            $assign['display_date'] = date('Y-m-d', strtotime($values['rc_display_date']));
            $assign['display_time'] = date('H:i', strtotime($values['rc_display_date']));
            $assign['values'] = $values;

            $assign['admin_data'] = $this->admin_tb_model->get($values['rc_admin_id'])->getData();

        } else {
            // insert
            $fields = $this->recommend_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'rc_use_chart' : 
                       $values[$field] = 'YES';
                       break;
                    case 'rc_is_active' : 
                       $values[$field] = 'YES';
                       break;
                    case 'rc_endtype' : 
                       $values[$field] = 'ING';
                       break;
                    default : 
                        $values[$field] = '';
                }
            }
            $assign['display_date'] = '';
            $assign['display_time'] = '';
            $assign['values'] = $values;
        }

        $assign['select_rc_endtype'] = form_dropdown('rc_endtype', $this->recommend_tb_model->getEndTypeMap(), $values['rc_endtype'], 'id="rc_endtype"');

        $active_map = $this->recommend_tb_model->getActiveMap();
        $assign['active_map'] = $active_map;

        $use_chart_map = $this->recommend_tb_model->getUseChartMap();
        $assign['use_chart_map'] = $use_chart_map;

        $portfolio_map = $this->recommend_tb_model->getPortfolioMap();
        $assign['portfolio_map'] = $portfolio_map;

        $adjust_map = $this->recommend_tb_model->getAdjustMap();
        $assign['adjust_map'] = $adjust_map;

		$view_srv_map = $this->recommend_tb_model->getViewSrvMap();
        $assign['view_srv_map'] = $view_srv_map;
//echo '<pre>'; print_r($assign); 
		$this->_view('main/recommend_detail', $assign);
    }

    /**
     * 종목추천 등록/수정 처리 
    */
    public function recommend_process() {
        $this->load->model(array(
            DBNAME.'/recommend_tb_model',
            DBNAME.'/mri_tb_model'
        ));
        $request = $this->input->post();
        $request = array_map('trim', $request);

        $sess = $this->session->userdata('admin');

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update', 'delete')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/recommend?keep=yes');
            return;
        }

        if( ! (isset($request['rc_ticker']) && strlen($request['rc_ticker']) > 0)) {
            $this->common->alert('종목명을 입력하세요.');
            $this->common->historyback();
            return;
        }

        if( ! (
                is_numeric($request['rc_recom_price'])
                && is_numeric($request['rc_giveup_price'])
                && is_numeric($request['rc_goal_price'])
        )) {
            $this->common->alert('추천가/손절가/목표가는 숫자만 입력하세요.');
            $this->common->historyback();
            return;
        }

        $rc_ticker = strtoupper($request['rc_ticker']);
        if( ! $this->mri_tb_model->get($rc_ticker)->isSuccess()) {
            $this->common->alert('종목명이 유효하지 않습니다.');
            $this->common->historyback();
            return;
        }

        $mri_data = $this->mri_tb_model->getData();
/*
        if($mri_data['m_close'] <= $request['rc_giveup_price']) {
            $this->common->alert('손절가는 현재가보다 작아야 합니다.');
            $this->common->historyback();
            return;
        }

        if($mri_data['m_close'] >= $request['rc_goal_price']) {
            $this->common->alert('목표가는 현재가보다 커야 합니다.');
            $this->common->historyback();
            return;
        }
*/
        if(strtotime($request['rc_enddate']) > time()) {
            $this->common->alert('도달일은 미래일 수 없습니다.');
            $this->common->historyback();
            return;
        }
        if($request['rc_endtype'] == 'ING') {
            $request['rc_enddate'] = '0000-00-00';
        }

        $rc_id = $request['rc_id'];
        $display_date = $request['display_date'].' '.$request['display_time'].':00';

        $params = array(
            'rc_ticker' => $rc_ticker,
            'rc_invest_point' => $request['rc_invest_point'],
            'rc_event' => $request['rc_event'],
            'rc_recom_price' => $request['rc_recom_price'],
            'rc_giveup_price' => $request['rc_giveup_price'],
            'rc_goal_price' => $request['rc_goal_price'],
            'rc_endtype' => $request['rc_endtype'],
            'rc_enddate' => $request['rc_enddate'],
            'rc_use_chart' => $request['rc_use_chart'],
            'rc_display_date' => $display_date,
            'rc_is_active' => $request['rc_is_active'],
            'rc_portfolio' => $request['rc_portfolio'],
            'rc_view_srv' => $request['rc_view_srv'],
            'rc_title' => $request['rc_title'],
            'rc_subtitle' => $request['rc_subtitle'],
            'rc_is_update' => ($request['rc_is_update'] == 'Y') ? $request['rc_is_update']:'N',
            'rc_adjust' => $request['rc_adjust'],
            'rc_adjust_price' => $request['rc_adjust_price'],
            'rc_mid_price' => $request['rc_mid_price'],
        );

        if($request['mode'] == 'insert') { 
            $params['rc_admin_id'] = $sess['id'];

			if($request['rc_portfolio'] == 'Y') { 
				$params['rc_portregdate'] = date('Y-m-d H:i:s');
				$params['rc_portupdate'] = date('Y-m-d H:i:s');

				$check_date = date('YmdHis', strtotime($display_date));
				if($check_date <= date('YmdHis')) {
					/*이미 등록된 티커 초기화*/
					$pf_params = array();
					$pf_params['=']['rc_ticker'] = $rc_ticker;
					$pf_extra = array(
						'fields' => '*',
					);

					$pf_list = array();
					$pf_list = $this->recommend_tb_model->getList($pf_params, $pf_extra)->getData();

					foreach($pf_list as $pf_key=>$pf_val) {
						$update_params = array(
							'rc_portfolio' => 'N'
						);
						$this->recommend_tb_model->doUpdate($pf_val['rc_id'], $update_params);
					}
				}
			}

            if( ! $this->recommend_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            $rc_id = $this->recommend_tb_model->getData();
            $redirect_url = '/adminpanel/main/recommend_detail/'.$rc_id;

		} else if($request['mode'] == 'update') {
            $params['rc_updated_at'] = date('Y-m-d H:i:s');

			if($request['rc_portfolio'] == 'Y') { 
				if($request['rc_portregdate'] == '') {
					$params['rc_portregdate'] = date('Y-m-d H:i:s');
				}
				$params['rc_portupdate'] = date('Y-m-d H:i:s');

				$check_date = date('YmdHis', strtotime($display_date));
				if($check_date <= date('YmdHis')) {
					/*이미 등록된 티커 초기화*/
					$pf_params = array();
					$pf_params['!=']['rc_id'] = $rc_id;
					$pf_params['=']['rc_ticker'] = $rc_ticker;
					$pf_extra = array(
						'fields' => '*',
					);

					$pf_list = array();
					$pf_list = $this->recommend_tb_model->getList($pf_params, $pf_extra)->getData();

					foreach($pf_list as $pf_key=>$pf_val) {
						$update_params = array(
							'rc_portfolio' => 'N'
						);
						$this->recommend_tb_model->doUpdate($pf_val['rc_id'], $update_params);
					}
				}
			}

            if( ! $this->recommend_tb_model->doUpdate($rc_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            unset($params['rc_invest_point']);
            unset($params['rc_event']);
            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $rc_id, $log_array, 'recommend_tb');
            $redirect_url = '/adminpanel/main/recommend_detail/'.$rc_id;

        } else {
            if( ! $this->recommend_tb_model->doDelete($rc_id)->isSuccess()) {
                $this->common->alert('삭제 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $redirect_url = '/adminpanel/main/recommend?keep=yes';
        }

        $makeport_url = CS_URL.'/payment/makePortFolio';
        file_get_contents($makeport_url);

/*
        if(!$this->get_content($makeport_url)) {
			echo 'file make error'; exit;
		}
		//추천/포트폴리오파일 생성
        $params = array();
        $params['=']['rc_is_active'] = 'YES';
        //$params['<=']['rc_display_date'] = date('Y-m-d H:i:s');
        $params['!=']['rc_view_srv'] = 'W';
		$params['raw'] = '(rc_portfolio = \'Y\' || rc_exclude = \'Y\')';
        //$params['=']['rc_endtype'] = 'ING';
        $params['join']['ticker_tb'] = 'rc_ticker = tkr_ticker';

        $extra = array(
            'fields' => array('rc_id', 'rc_ticker', 'tkr_name', 'rc_title', 'rc_subtitle','rc_recom_price','rc_giveup_price','rc_goal_price', 'rc_endtype', 'rc_portfolio', 'rc_mid_price', 'rc_exclude', 'rc_enddate', 'rc_adjust', 'rc_adjust_price'),
            'order_by' => 'rc_display_date DESC',
        );

        $rc_all = $this->recommend_tb_model->getList($params, $extra)->getData();

  		$dup_check = array();
  		$rc_list = array();
		foreach($rc_all as $key=>$val) {
			if(!in_array($val['rc_ticker'], $dup_check)) {
				$dup_check[] = $val['rc_ticker'];
				$rc_list[] = $val;
			}
		}

        if(is_array($rc_list) && sizeof($rc_list) >0) {
            //$data = serialize($ticker_info);
            $data = json_encode($rc_list);
            $portfolio_file = 'portfolio.json';
			//define('WEBDATA', '/home/datahero/html/wallstreet/webdata');
            $file_path = WEBDATA.'/json/'.$portfolio_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $data);
            rename($file_backpath, $file_path);
        }
*/
		$this->common->alert('처리되었습니다.');
        $this->common->locationhref($redirect_url);
        return;
    }

    /**
     * 종목분석 리스트
    */
    public function analyze() {
        $this->load->model(DBNAME.'/analysis_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->get();

        $data = array();
        $active_map = $this->analysis_tb_model->getActiveMap();
        $data['active_map_sel'] = $this->common->genJqgridOption($active_map);
        $data['active_map'] = $this->common->genJqgridOption($active_map, true);

        $view_srv_map = $this->analysis_tb_model->getViewSrvMap();
        $data['view_srv_map_sel'] = $this->common->genJqgridOption($view_srv_map);
        $data['view_srv_map'] = $this->common->genJqgridOption($view_srv_map, true);

        if(isset($request['mode']) && $request['mode'] == 'list') {
            // ajax reqeust. ==> grid list data 

            $page = $this->input->get('page');
            $limit = $this->input->get('rows');
            $_search = $this->input->get('_search');

            $params = array();
            $extra = array();

            if(isset($_GET['filters'])){
                $filters = $_GET['filters'];
                $params = $this->common->filter_to_params($filters, $_search, array('an_id','an_view_count','an_display_date','an_created_at','an_updated_at'));
            }

            if(isset($params['=']['an_ticker']) && strlen($params['=']['an_ticker']) > 0) {
                $params['=']['an_ticker'] = strtoupper($params['=']['an_ticker']);
            }

            $params['join']['admin_tb'] = 'a_id = an_admin_id';
            
            $extra['fields'] = array('analysis_tb.*','a_loginid');

            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->analysis_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->analysis_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $r['an_is_active'] = $active_map[$r['an_is_active']];
                $r['an_view_srv'] = $view_srv_map[$r['an_view_srv']];
                $json_data->rows[$k]['id'] = $r['an_id'];
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

                $data['an_created_at'] = date('Y-m-d H:i:s');
                $data['an_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->analysis_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->analysis_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->analysis_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'analysis_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if(isset($request['edit_action_is']) && $request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->analysis_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->analysis_tb_model->getData();
                        $this->analysis_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'analysis_tb');
                    }
                    return;
                }
                
                // row 수정
                $request['an_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->analysis_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'analysis_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->analysis_tb_model->get($id)->isSuccess()){
                            $del_data = $this->analysis_tb_model->getData();
                            $this->analysis_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'analysis_tb');
                        }
                    } else {
                        $this->analysis_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'analysis_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

        $this->_view('main/analyze', $data);
    }

    /**
     * 종목분석 등록/수정 폼
    */
    public function analyze_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/analysis_tb_model',
            DBNAME.'/admin_tb_model'
        ));

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/analyze?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'an_id';

        if($id > 0) {
            $this->analysis_tb_model->get($id);
            if($this->analysis_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/analyze?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->analysis_tb_model->getData();
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

            $assign['display_date'] = date('Y-m-d', strtotime($values['an_display_date']));
            $assign['display_time'] = date('H:i', strtotime($values['an_display_date']));
            $assign['values'] = $values;

            $assign['admin_data'] = $this->admin_tb_model->get($values['an_admin_id'])->getData();

        } else {
            // insert
            $fields = $this->analysis_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'an_is_active' : 
                       $values[$field] = 'YES';
                       break;
                    default : 
                        $values[$field] = '';
                }
            }
            $assign['display_date'] = '';
            $assign['display_time'] = '';
            $assign['values'] = $values;
        }

        $active_map = $this->analysis_tb_model->getActiveMap();
        $assign['active_map'] = $active_map;

        $view_srv_map = $this->analysis_tb_model->getViewSrvMap();
        $assign['view_srv_map'] = $view_srv_map;

		$this->_view('main/analyze_detail', $assign);
    }

    /**
     * 종목분석 등록/수정 처리
    */
    public function analyze_process() {
        $this->load->model(array(
            DBNAME.'/analysis_tb_model',
            DBNAME.'/mri_tb_model'
        ));
        $request = $this->input->post();
        $request = array_map('trim', $request);

        $sess = $this->session->userdata('admin');

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update', 'delete')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/analyze?keep=yes');
            return;
        }

        if( ! (isset($request['an_ticker']) && strlen($request['an_ticker']) > 0)) {
            $this->common->alert('종목명을 입력하세요.');
            $this->common->historyback();
            return;
        }

        $an_ticker = strtoupper($request['an_ticker']);
        if( ! $this->mri_tb_model->get($an_ticker)->isSuccess()) {
            $this->common->alert('종목명이 유효하지 않습니다.');
            $this->common->historyback();
            return;
        }

        $an_id = $request['an_id'];
        $display_date = $request['display_date'].' '.$request['display_time'].':00';

        $params = array(
            'an_ticker' => $an_ticker,
            'an_content' => $request['an_content'],
            'an_is_active' => $request['an_is_active'],
            'an_display_date' => $display_date,
            'an_view_srv' => $request['an_view_srv'],
        );

        if($request['mode'] == 'insert') { 
            $params['an_admin_id'] = $sess['id'];
            if( ! $this->analysis_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $an_id = $this->analysis_tb_model->getData();
            $redirect_url = '/adminpanel/main/analyze_detail/'.$an_id;

			//종목분석push
			//$push_result = $this->common->restful_curl(CS_URL.'/payment/push_contents/2/'.$an_ticker);

        } else if($request['mode'] == 'update') {
            $params['an_updated_at'] = date('Y-m-d H:i:s');
            if( ! $this->analysis_tb_model->doUpdate($an_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            unset($params['an_content']);
            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $an_id, $log_array, 'analysis_tb');
            $redirect_url = '/adminpanel/main/analyze_detail/'.$an_id;

			//종목분석push
			//$push_result = $this->common->restful_curl(CS_URL.'/payment/push_contents/2/'.$an_ticker);

        } else {
            if( ! $this->analysis_tb_model->doDelete($an_id)->isSuccess()) {
                $this->common->alert('삭제 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $redirect_url = '/adminpanel/main/analyze?keep=yes';
        }


        $this->common->alert('처리되었습니다.');
        $this->common->locationhref($redirect_url);
        return;
    }

    /**
     * 탐구생활 배너(초이스스탁 메인)
    */
	public function explore_banner() {
	
        if($this->input->is_ajax_request() === FALSE) {
            $this->common->alert('잘못된 접근입니다.');
			$this->common->locationhref('/');
			exit;
		}

		$bn1_id = $this->input->get('bn1_id');
		$bn1_txt = $this->input->get('bn1_txt');
		$bn2_id = $this->input->get('bn2_id');
		$bn2_txt = $this->input->get('bn2_txt');

		if($bn1_id==''||$bn2_id==''||$bn1_txt==''||$bn2_txt=='') {
			$result = array('error' => '배너 입력값이 없습니다.');
			exit(json_encode($result));
		}
		$data = array();
        $data['bn1_id'] = $bn1_id;
        $data['bn1_txt'] = $bn1_txt;
        $data['bn2_id'] = $bn2_id;
        $data['bn2_txt'] = $bn2_txt;
        $data = serialize($data);

		$ex_banner_file = 'ex_banner.json';
        $file_path = WEBDATA.'/'.$ex_banner_file;
        $file_backpath = $file_path . '.bak';
        
        touch($file_backpath);
        file_put_contents($file_backpath, $data);
        rename($file_backpath, $file_path);

		$success = TRUE;
		$result = array('success' => $success);
		exit(json_encode($result));
	}

    /**
     * 탐구생활 리스트
    */
    public function explore() {
        $this->load->model(DBNAME.'/explore_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->get();

        $data = array();
        $active_map = $this->explore_tb_model->getActiveMap();
        $data['active_map_sel'] = $this->common->genJqgridOption($active_map);
        $data['active_map'] = $this->common->genJqgridOption($active_map, true);

        $view_srv_map = $this->explore_tb_model->getViewSrvMap();
        $data['view_srv_map_sel'] = $this->common->genJqgridOption($view_srv_map);
        $data['view_srv_map'] = $this->common->genJqgridOption($view_srv_map, true);

        $push_srv_map = $this->explore_tb_model->getPushSrvMap();
        $data['push_srv_map_sel'] = $this->common->genJqgridOption($push_srv_map);
        $data['push_srv_map'] = $this->common->genJqgridOption($push_srv_map, true);

		$pay_map = $this->explore_tb_model->getPayMap();
        $data['pay_map_sel'] = $this->common->genJqgridOption($pay_map);
        $data['pay_map'] = $this->common->genJqgridOption($pay_map, true);

		$inside_map = $this->explore_tb_model->getInsideMap();
        $data['inside_map_sel'] = $this->common->genJqgridOption($inside_map);
        $data['inside_map'] = $this->common->genJqgridOption($inside_map, true);

		if(isset($request['mode']) && $request['mode'] == 'list') {
            // ajax reqeust. ==> grid list data 

            $page = $this->input->get('page');
            $limit = $this->input->get('rows');
            $_search = $this->input->get('_search');

            $params = array();
            $extra = array();

            if(isset($_GET['filters'])){
                $filters = $_GET['filters'];
                $params = $this->common->filter_to_params($filters, $_search, array('e_id','e_display_date','e_view_count','e_created_at','e_updated_at'));
            }
            $params['join']['admin_tb'] = 'a_id = e_admin_id';
            

            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['fields'] = 'explore_tb.*, a_loginid';
            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->explore_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->explore_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $r['e_title'] = htmlspecialchars($r['e_title']);
                $r['e_view_count'] = number_format($r['e_view_count']);
                $r['e_is_active'] = $active_map[$r['e_is_active']];
                $r['e_view_srv'] = $view_srv_map[$r['e_view_srv']];
                $r['e_push_srv'] = $push_srv_map[$r['e_push_srv']];
                $r['e_pay'] = $pay_map[$r['e_pay']];
                $r['e_is_inside'] = $inside_map[$r['e_is_inside']];
                $r['e_kiwoom_off'] = $inside_map[$r['e_kiwoom_off']];

                $thumbnail = '';
                if(strlen($r['e_thumbnail']) > 0) {
                    $thumbnail = '<img src="'.$r['e_thumbnail'].'" style="width:80px;height:80px;" />';
                }
                $r['thumbnail'] = $thumbnail;
                $json_data->rows[$k]['id'] = $r['e_id'];
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

                $data['e_created_at'] = date('Y-m-d H:i:s');
                $data['e_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->explore_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->explore_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->explore_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'explore_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if(isset($request['edit_action_is']) && $request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->explore_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->explore_tb_model->getData();
                        $this->explore_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'explore_tb');
                    }
                    return;
                }
                
                // row 수정
                $request['e_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->explore_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'explore_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->explore_tb_model->get($id)->isSuccess()){
                            $del_data = $this->explore_tb_model->getData();
                            $this->explore_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'explore_tb');
                        }
                    } else {
                        $this->explore_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'explore_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

		//탐구생활 배너
		$ex_banner_file = 'ex_banner.json';
		$file_path = WEBDATA.'/'.$ex_banner_file;
		if( is_file($file_path) ) {
            $file_data = unserialize(file_get_contents($file_path));
			$data['bn1_txt'] = htmlspecialchars($file_data['bn1_txt']);
			$data['bn1_id'] = $file_data['bn1_id'];
			$data['bn2_txt'] = htmlspecialchars($file_data['bn2_txt']);
			$data['bn2_id'] = $file_data['bn2_id'];
		}
		else {
			$data['bn1_txt'] = '';
			$data['bn1_id'] = '';
			$data['bn2_txt'] = '';
			$data['bn2_id'] = '';
		}

		$this->_view('main/explore', $data);
    }

    /**
     * 탐구생활 등록/수정 폼
    */
    public function explore_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/explore_tb_model',
            DBNAME.'/admin_tb_model'
        ));

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/explore?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'e_id';

        if($id > 0) {
            $this->explore_tb_model->get($id);
            if($this->explore_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/explore?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->explore_tb_model->getData();
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

            $assign['display_date'] = date('Y-m-d', strtotime($values['e_display_date']));
            $assign['display_time'] = date('H:i', strtotime($values['e_display_date']));
            $assign['values'] = $values;

            $assign['admin_data'] = $this->admin_tb_model->get($values['e_admin_id'])->getData();

        } else {
            // insert
            $fields = $this->explore_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'e_is_active' : 
                       $values[$field] = 'YES';
                       break;
                    default : 
                        $values[$field] = '';
                }
            }
            $assign['display_date'] = '';
            $assign['display_time'] = '';
            $assign['values'] = $values;
        }

        $active_map = $this->explore_tb_model->getActiveMap();
        $assign['active_map'] = $active_map;

        $view_srv_map = $this->explore_tb_model->getViewSrvMap();
        $assign['view_srv_map'] = $view_srv_map;

        $push_srv_map = $this->explore_tb_model->getPushSrvMap();
        $assign['push_srv_map'] = $push_srv_map;

		$pay_map = $this->explore_tb_model->getPayMap();
        $assign['pay_map'] = $pay_map;

		$inside_map = $this->explore_tb_model->getInsideMap();
        $assign['inside_map'] = $inside_map;

		$this->_view('main/explore_detail', $assign);
    }

    /**
     * 탐구생활 등록/수정 처리
    */
    public function explore_process() {
        $this->load->model(DBNAME.'/explore_tb_model');
        $request = $this->input->post();
        $sess = $this->session->userdata('admin');

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update', 'delete')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/explore?keep=yes');
            return;
        }

        $e_id = $request['e_id'];
        $display_date = $request['display_date'].' '.$request['display_time'].':00';
        $params = array(
            'e_display_date' => $display_date,
            'e_title' => $request['e_title'],
            'e_content' => $request['e_content'],
            'e_is_active' => $request['e_is_active'],
            'e_thumbnail' => '',
            'e_view_srv' => $request['e_view_srv'],
            'e_push_srv' => $request['e_push_srv'],
            'e_pay' => $request['e_pay'],
            'e_is_inside' => ($request['e_is_inside']=='') ? 'N':$request['e_is_inside'],
            'e_kiwoom_off' => ($request['e_kiwoom_off']=='') ? '':$request['e_kiwoom_off'],
        );

        // content 중에 이미지가 존재하면 첫번재 이미지를 썸네일로 등록한다.
        $pattern = "/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i";
        preg_match_all($pattern, $request['e_content'], $matches);

        if(isset($matches[1][0]) && strlen($matches[1][0]) > 0) {
            $params['e_thumbnail'] = $matches[1][0];
        }

        if($request['mode'] == 'insert') { 
            $params['e_admin_id'] = $sess['id'];
            if( ! $this->explore_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $e_id = $this->explore_tb_model->getData();

			//탐구생활push
			//$push_result = $this->common->restful_curl(CS_URL.'/payment/push_contents/1/'.$e_id);

            $redirect_url = '/adminpanel/main/explore_detail/'.$e_id;

        } else if($request['mode'] == 'update') {
            $params['e_updated_at'] = date('Y-m-d H:i:s');
            if( ! $this->explore_tb_model->doUpdate($e_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            unset($params['e_content']);
            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $e_id, $log_array, 'explore_tb');

			//$push_result = $this->common->restful_curl(CS_URL.'/payment/push_contents/1/'.$e_id);

			$redirect_url = '/adminpanel/main/explore_detail/'.$e_id;

        } else {
            if( ! $this->explore_tb_model->doDelete($e_id)->isSuccess()) {
                $this->common->alert('삭제 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $redirect_url = '/adminpanel/main/explore?keep=yes';
        }

        $this->common->alert('처리되었습니다.');
        $this->common->locationhref($redirect_url);
        return;
    }
    

    /**
     * 알림관리 리스트
    */
    public function notify() {
        $this->load->model(DBNAME.'/notify_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->get();

        $data = array();
        $active_map = $this->notify_tb_model->getActiveMap();
        $data['active_map_sel'] = $this->common->genJqgridOption($active_map);

        $table_map = $this->notify_tb_model->getTableMap();
        $data['table_map_sel'] = $this->common->genJqgridOption($table_map);


        $view_srv_map = $this->notify_tb_model->getViewSrvMap();
        $data['view_srv_map_sel'] = $this->common->genJqgridOption($view_srv_map);
        $data['view_srv_map'] = $this->common->genJqgridOption($view_srv_map, true);


        if(isset($request['mode']) && $request['mode'] == 'list') {
            // ajax reqeust. ==> grid list data

            $page = $this->input->get('page');
            $limit = $this->input->get('rows');
            $_search = $this->input->get('_search');

            $params = array();
            $extra = array();

            if(isset($_GET['filters'])){
                $filters = $_GET['filters'];
                $params = $this->common->filter_to_params($filters, $_search, array('nt_id','nt_display_date','nt_created_at','nt_updated_at'));
            }

            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->notify_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->notify_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $r['nt_table'] = $table_map[$r['nt_table']];
                $r['nt_view_srv'] = $view_srv_map[$r['nt_view_srv']];
                $json_data->rows[$k]['id'] = $r['nt_id'];
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

                $data['nt_created_at'] = date('Y-m-d H:i:s');
                $data['nt_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->notify_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->notify_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->notify_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'notify_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if($request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->notify_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->notify_tb_model->getData();
                        $this->notify_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'notify_tb');
                    }
                    return;
                }

                // row 수정
                $request['nt_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->notify_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'notify_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->notify_tb_model->get($id)->isSuccess()){
                            $del_data = $this->notify_tb_model->getData();
                            $this->notify_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'notify_tb');
                        }
                    } else {
                        $this->notify_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'notify_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

        $this->_view('main/notify', $data);
    }

    /**
     * 알림관리 등록/수정 폼
    */
    public function notify_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/notify_tb_model',
        ));

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/notify?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'nt_id';

        if($id > 0) {
            $this->notify_tb_model->get($id);
            if($this->notify_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/notify?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->notify_tb_model->getData();
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
            
            $assign['display_date'] = date('Y-m-d', strtotime($values['nt_display_date']));
            $assign['display_time'] = date('H:i', strtotime($values['nt_display_date']));
            $assign['values'] = $values;

        } else {
            // insert
            $fields = $this->notify_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'nt_table' : 
                       $values[$field] = 'custom';
                       break;
                    case 'nt_is_active' : 
                       $values[$field] = 'YES';
                       break;
                    default : 
                        $values[$field] = '';
                }
            }

            $assign['display_date'] = '';
            $assign['display_time'] = '';
            $assign['values'] = $values;
        }

        $assign['active_map'] = $this->notify_tb_model->getActiveMap();
        $assign['table_map'] = $this->notify_tb_model->getTableMap();
        $view_srv_map = $this->notify_tb_model->getViewSrvMap();
        $assign['view_srv_map'] = $view_srv_map;


        $this->_view('main/notify_detail', $assign);
    }

    /**
     * 알림관리 등록/수정 처리
    */
    public function notify_process() {
        $this->load->model(DBNAME.'/notify_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->post();

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/notify?keep=yes');
            return;
        }

        $nt_id = $request['nt_id'];
        $display_date = $request['display_date'].' '.$request['display_time'].':00';
        $params = array(
            'nt_display_date' => $display_date,
            'nt_title' => $request['nt_title'],
            'nt_ticker' => $request['nt_ticker'],
            'nt_ticker_name' => $request['nt_ticker_name'],
            'nt_is_active' => $request['nt_is_active'],
            'nt_view_srv' => $request['nt_view_srv'],
        );

        if($request['mode'] == 'insert') { 
            $params['nt_table'] = $request['nt_table']; // custom
            $params['nt_url'] = $request['nt_url'];

            if( ! $this->notify_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $nt_id = $this->notify_tb_model->getData();

        } else if($request['mode'] == 'update') {
            $params['nt_updated_at'] = date('Y-m-d H:i:s');
            if( ! $this->notify_tb_model->doUpdate($nt_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $nt_id, $log_array, 'notify_tb');

        }

        $this->common->alert('처리되었습니다.');
        $this->common->locationhref('/adminpanel/main/notify_detail/'.$nt_id);
        return;
    }
    
    
    /**
     * CK Editor 이미지 업로드처리 (종목추천, 종목분석, 탐구생활)
    */
    public function upload_image() {
        $target = $this->uri->segment(4, FALSE);
        
        $allow_target = array('recommend', 'analyze', 'explore', 'vod_mjm');
        if( ! in_array($target, $allow_target)) {
            $response = array(
                'uploaded' => FALSE,
                'error' => array(
                    'message' => '올바르지 않은 접근입니다'
                )
            );
            echo json_encode($response);
            return;
        }

        $path = ATTACH_DATA.'/'.$target;
        if( ! is_dir($path)) {
            @mkdir($path, 0777);
            @chmod($path, 0777);
        }

        $path .= '/'.date('Ym');
        if( ! is_dir($path)) {
            @mkdir($path, 0777);
            @chmod($path, 0777);
        }

        $this->load->library('upload');
        $config = array();
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);
        if($this->upload->do_upload('upload')) {
            $result = $this->upload->data();

            $url = str_replace(WEBDATA, '', $result['full_path']);
            $url = '/webdata'.$url;
            $response = array(
                'uploaded' => TRUE,
                'fileName' => $result['file_name'],
                'url' => $url
            );
            echo json_encode($response);
            return;

        } else {
            $response = array(
                'uploaded' => FALSE,
                'error' => array(
                    'message' => strip_tags($this->upload->display_errors())
                )
            );
            echo json_encode($response);
            return;
        }
    }
    

    /**
     * ticker 정보 리턴
    */
    public function ajax_get_ticker_info() {
        if( ! $this->input->is_ajax_request()) {
            return;
        }

        $this->load->model(array(
            DBNAME.'/mri_tb_model',
            DBNAME.'/ticker_tb_model',
        ));

        $response = array(
            'is_success' => FALSE
        );

        $request = $this->input->post();

        if( ! (
                is_array($request)
                && array_key_exists('ticker', $request)
                && strlen($request['ticker']) > 0
        )) {
            $response['msg'] = '종목을 확인할 수 없습니다.';
            echo json_encode($response);
            return;
        }

        if( ! $this->mri_tb_model->get(strtoupper($request['ticker']))->isSuccess()) {
            $response['msg'] = '종목을 확인할 수 없습니다.';
            echo json_encode($response);
            return;
        }

		$params = array();
		$params['=']['tkr_ticker'] = strtoupper($request['ticker']);
		$extra = array(
			'order_by' => 'tkr_lastpricedate desc',
			'fields' => '*',
			'slavedb' => true,
			'limit' => '1'
		);

		$tkr_data = array_shift($this->ticker_tb_model->getList($params, $extra)->getData());

		$response['is_success'] = TRUE;
        $response['data'] = $tkr_data;
        echo json_encode($response);
        return;
    }


    /**
     * 대가종합 리스트
    */
    public function master() {
        $this->load->model(DBNAME.'/master_tb_model');
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
                $params = $this->common->filter_to_params($filters, $_search, array('ms_id','ms_stocks','ms_newstocks','ms_10yavgreturn','ms_portfoliodate','ms_created_at','ms_updated_at'));
            }
            

            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->master_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->master_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $json_data->rows[$k]['id'] = $r['ms_id'];
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

                $data['ms_created_at'] = date('Y-m-d H:i:s');
                $data['ms_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->master_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->master_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->master_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'master_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if(isset($request['edit_action_is']) && $request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->master_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->master_tb_model->getData();
                        $this->master_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'master_tb');
                    }
                    return;
                }
                
                // row 수정
                $request['ms_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->master_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'master_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->master_tb_model->get($id)->isSuccess()){
                            $del_data = $this->master_tb_model->getData();
                            $this->master_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'master_tb');
                        }
                    } else {
                        $this->master_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'master_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

        $this->_view('main/master', $data);
    }

    /**
     * 대가종합 상세
    */
    public function master_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/master_tb_model',
        ));

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/master?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'ms_id';

        if($id > 0) {
            $this->master_tb_model->get($id);
            if($this->master_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/master?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->master_tb_model->getData();
            $rp_ticker = array();

            foreach($values as $field => &$value) {
                switch($field) {
                   case 'ms_representative_ticker' : 
                       if(strlen($values['ms_representative_ticker']) > 0) {
                           $rp_ticker = explode(',', $values['ms_representative_ticker']);
                       }
                       break;
                    default : 
                        $value = htmlspecialchars($value);
                }
            }

            $assign['rp_ticker'] = $rp_ticker;
            $assign['values'] = $values;

        } else {
            // insert
            $fields = $this->master_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    default : 
                        $values[$field] = '';
                }
            }
            $assign['rp_ticker'] = array();
            $assign['values'] = $values;
        }
        $this->_view('main/master_detail', $assign);
    }

    /**
     * 대가종합 처리
    */
    public function master_process() {
        $this->load->model(array(
            DBNAME.'/master_tb_model',
        ));
        $request = $this->input->post();

        $sess = $this->session->userdata('admin');

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update', 'delete')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/master?keep=yes');
            return;
        }

        $ms_id = $request['ms_id'];

        $rp_ticker = implode(',', array_unique(array_filter(array_map('strtoupper', $request['ms_representative_ticker']))));

        $params = array(
            'ms_guru' => $request['ms_guru'],
            'ms_korguru' => $request['ms_korguru'],
            'ms_stocks' => $request['ms_stocks'],
            'ms_newstocks' => $request['ms_newstocks'],
            'ms_10yavgreturn' => $request['ms_10yavgreturn'],
            'ms_portfolioname' => $request['ms_portfolioname'],
            'ms_portfoliodate' => $request['ms_portfoliodate'],
            'ms_introduce' => $request['ms_introduce'],
            'ms_representative_ticker' => $rp_ticker
        );

        if(isset($_FILES['ms_image']) && strlen($_FILES['ms_image']['tmp_name']) > 0) {
            $this->load->library('upload');
            $config = array();
            $config['upload_path'] = ATTACH_DATA.'/master';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);
            if($this->upload->do_upload('ms_image')) {
                $result = $this->upload->data();
                $params['ms_image'] = $result['file_name'];
            }
        }

        if($request['mode'] == 'insert') { 
            if( ! $this->master_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $ms_id = $this->master_tb_model->getData();
            $redirect_url = '/adminpanel/main/master_detail/'.$ms_id;

        } else if($request['mode'] == 'update') {
            $params['ms_updated_at'] = date('Y-m-d H:i:s');
            if( ! $this->master_tb_model->doUpdate($ms_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $ms_id, $log_array, 'master_tb');
            $redirect_url = '/adminpanel/main/master_detail/'.$ms_id;

        }  else {
            if( ! $this->master_tb_model->doDelete($ms_id)->isSuccess()) {
                $this->common->alert('삭제 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $redirect_url = '/adminpanel/main/master?keep=yes';
        }

        $this->common->alert('처리되었습니다.');
        $this->common->locationhref($redirect_url);
        return;
    }

    /**
     * 대가종목편집
    */
    public function set_master_items() {
        $this->load->model(DBNAME.'/master_tb_model');

        $extra = array(
            'fields' => array('ms_id', 'ms_guru'),
            'order_by' => 'ms_id ASC',
        );
        $master = $this->master_tb_model->getList(array(), $extra)->getData();
        $data = array();
        $data['master'] = $master;
        $this->_view('main/set_master_items', $data);
    }

    /**
     * 대가종목편집 처리
    */
    public function set_master_items_process() {
        $this->load->model(array(
            DBNAME.'/notify_tb_model',
            DBNAME.'/master_tb_model'
        ));
        $request = $this->input->post();

        $change_files = array();

        foreach($request as $k => $content) {
            $filepath = MASTER_DATA.'/'.$k.'.info';
            if(is_file($filepath)) {
                $old_content = file_get_contents($filepath);
                if($old_content == $content) {
                    continue;
                }

                $this->common->logWrite('set_master_items', print_r(array(
                    'file' => $filepath,
                    'before' => $old_content,
                    'after' => $content
                ), true), $k);
            } else {
                $this->common->logWrite('set_master_items', print_r(array(
                    'file' => $filepath,
                    'new' => $content
                ), true), $k);
            }
            file_put_contents($filepath, $content);
            $change_files[] = $k;

            // 알림 동기화
            $pk = str_replace('master_', '', $k);
            $options = array(
                'nt_pk' => $pk,
                'nt_table' => 'master_tb'
            );
            $this->notify_tb_model->doSyncNotify('INSERT', $options);
        }

        if(sizeof($change_files) > 0) {
            $this->common->alert(implode('\n', $change_files).'\n\n수정 완료');
        }
        $this->common->locationhref('/adminpanel/main/set_master_items');
    }

	public function str_hex_dump($s)
	{
		$a = unpack('C*', $s);
		$i = 0;
		foreach ($a as $v) {
			$h = strtoupper(dechex($v));
			if (strlen($h)<2) $h = '0'.$h;
			//echo $h.' ';
			++$i;
		}
		//printf("(%d bytes)\n", $i);
		return $i;
	}

    /**
     * 모닝브리핑 리스트
    */
    public function morning() {
        $this->load->model(DBNAME.'/morning_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->get();

        $data = array();

        $active_map = $this->morning_tb_model->getActiveMap();
        $data['active_map_sel'] = $this->common->genJqgridOption($active_map);
        $data['active_map'] = $this->common->genJqgridOption($active_map, true);

		$push_map = $this->morning_tb_model->getPushMap();
        $data['push_map_sel'] = $this->common->genJqgridOption($push_map);
        $data['push_map'] = $this->common->genJqgridOption($push_map, true);

        if(isset($request['mode']) && $request['mode'] == 'list') {
            // ajax reqeust. ==> grid list data 

            $page = $this->input->get('page');
            $limit = $this->input->get('rows');
            $_search = $this->input->get('_search');

            $params = array();
            $extra = array();

            if(isset($_GET['filters'])){
                $filters = $_GET['filters'];
                $params = $this->common->filter_to_params($filters, $_search, array('mo_id','mo_title','mo_display_date','mo_created_at','mo_updated_at'));
            }
            

            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->morning_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->morning_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $r['mo_is_active'] = $active_map[$r['mo_is_active']];
                $r['mo_contents'] = iconv_substr($r['mo_contents'], 0, 64, 'utf-8');
                $json_data->rows[$k]['id'] = $r['mo_id'];
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

                $data['mo_created_at'] = date('Y-m-d H:i:s');
                $data['mo_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->morning_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->morning_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->morning_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'morning_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if(isset($request['edit_action_is']) && $request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->morning_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->morning_tb_model->getData();
                        $this->morning_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'morning_tb');
                    }
                    return;
                }
                
                // row 수정
                $request['mo_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->morning_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'morning_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->morning_tb_model->get($id)->isSuccess()){
                            $del_data = $this->morning_tb_model->getData();
                            $this->morning_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'morning_tb');
                        }
                    } else {
                        $this->morning_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'morning_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

        $this->_view('main/morning', $data);
    }

    /**
     * 모닝브리핑 상세
    */
    public function morning_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/morning_tb_model',
        ));

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/morning?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'mo_id';

        if($id > 0) {
            $this->morning_tb_model->get($id);
            if($this->morning_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/morning?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->morning_tb_model->getData();

			$length = iconv('UTF-8', 'EUC-KR', $values['mo_contents']);
			$values['length'] = $this->str_hex_dump($length); 

            $rp_ticker = array();

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

            //$assign['rp_ticker'] = $rp_ticker;
            $assign['display_date'] = date('Y-m-d', strtotime($values['mo_display_date']));
            $assign['display_time'] = date('H:i', strtotime($values['mo_display_date']));
            $assign['values'] = $values;

        } else {
            // insert
            $fields = $this->morning_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'mo_is_active' : 
                       $values[$field] = 'N';
                       break;
                    default : 
                        $values[$field] = '';
                }
            }
            //$assign['rp_ticker'] = array();
            $assign['display_date'] = '';
            $assign['display_time'] = '';
			$values['length'] = 0; 
            $assign['values'] = $values;
        }

        $active_map = $this->morning_tb_model->getActiveMap();
        $assign['active_map'] = $active_map;

		$push_map = $this->morning_tb_model->getPushMap();
        $assign['push_map'] = $push_map;

        $this->_view('main/morning_detail', $assign);
    }

    /**
     * 모닝브리핑 처리
    */
    public function morning_process() {
        $this->load->model(array(
            DBNAME.'/morning_tb_model',
        ));
        $request = $this->input->post();

        $sess = $this->session->userdata('admin');

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update', 'delete')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/morning?keep=yes');
            return;
        }

        $mo_id = $request['mo_id'];
        $display_date = $request['display_date'].' '.$request['display_time'].':00';

        //$rp_ticker = implode(',', array_unique(array_filter(array_map('strtoupper', $request['ms_representative_ticker']))));

        $params = array(
            'mo_title' => $request['mo_title'],
            'mo_is_active' => $request['mo_is_active'],
            'mo_contents' => $request['mo_contents'],
            'mo_display_date' => $display_date,
        );

        if($request['mode'] == 'insert') { 
            if( ! $this->morning_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $mo_id = $this->morning_tb_model->getData();
            $redirect_url = '/adminpanel/main/morning_detail/'.$mo_id;

        } else if($request['mode'] == 'update') {
            $params['mo_updated_at'] = date('Y-m-d H:i:s');
            if( ! $this->morning_tb_model->doUpdate($mo_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $mo_id, $log_array, 'morning_tb');
            $redirect_url = '/adminpanel/main/morning_detail/'.$mo_id;

        }  else {
            if( ! $this->morning_tb_model->doDelete($mo_id)->isSuccess()) {
                $this->common->alert('삭제 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $redirect_url = '/adminpanel/main/morning?keep=yes';
        }

        $this->common->alert('처리되었습니다.');
        $this->common->locationhref($redirect_url);
        return;
    }

    /**
     * 미주미동영상 리스트
    */
    public function vod_mjm() {
        $this->load->model(DBNAME.'/vod_mjm_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->get();

        $data = array();
        $active_map = $this->vod_mjm_tb_model->getActiveMap();
        $data['active_map_sel'] = $this->common->genJqgridOption($active_map);
        $data['active_map'] = $this->common->genJqgridOption($active_map, true);

        $view_srv_map = $this->vod_mjm_tb_model->getViewSrvMap();
        $data['view_srv_map_sel'] = $this->common->genJqgridOption($view_srv_map);
        $data['view_srv_map'] = $this->common->genJqgridOption($view_srv_map, true);

		$pay_map = $this->vod_mjm_tb_model->getPayMap();
        $data['pay_map_sel'] = $this->common->genJqgridOption($pay_map);
        $data['pay_map'] = $this->common->genJqgridOption($pay_map, true);


		if(isset($request['mode']) && $request['mode'] == 'list') {
            // ajax reqeust. ==> grid list data 

            $page = $this->input->get('page');
            $limit = $this->input->get('rows');
            $_search = $this->input->get('_search');

            $params = array();
            $extra = array();

            if(isset($_GET['filters'])){
                $filters = $_GET['filters'];
                $params = $this->common->filter_to_params($filters, $_search, array('vm_id','vm_display_date','vm_view_count','vm_created_at','vm_updated_at'));
            }
            $params['join']['admin_tb'] = 'a_id = vm_admin_id';
            

            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['fields'] = 'vod_mjm_tb.*, a_loginid';
            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->vod_mjm_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->vod_mjm_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $r['vm_title'] = htmlspecialchars($r['vm_title']);
                $r['vm_view_count'] = number_format($r['vm_view_count']);
                $r['vm_is_active'] = $active_map[$r['vm_is_active']];
                $r['vm_view_srv'] = $view_srv_map[$r['vm_view_srv']];
                $r['vm_pay'] = $pay_map[$r['vm_pay']];

                $thumbnail = '';
                if(strlen($r['vm_thumbnail']) > 0) {
                    $thumbnail = '<img src="'.$r['vm_thumbnail'].'" style="width:80px;height:80px;" />';
                }
                $r['thumbnail'] = $thumbnail;
                $json_data->rows[$k]['id'] = $r['vm_id'];
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

                $data['vm_created_at'] = date('Y-m-d H:i:s');
                $data['vm_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->vod_mjm_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->vod_mjm_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->vod_mjm_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'vod_mjm_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if(isset($request['edit_action_is']) && $request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->vod_mjm_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->vod_mjm_tb_model->getData();
                        $this->vod_mjm_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'vod_mjm_tb');
                    }
                    return;
                }
                
                // row 수정
                $request['vm_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->vod_mjm_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'vod_mjm_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->vod_mjm_tb_model->get($id)->isSuccess()){
                            $del_data = $this->vod_mjm_tb_model->getData();
                            $this->vod_mjm_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'vod_mjm_tb');
                        }
                    } else {
                        $this->vod_mjm_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'vod_mjm_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

		$this->_view('main/vod_mjm', $data);
    }

    /**
     * 미주미동영상 등록/수정 폼
    */
    public function vod_mjm_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/vod_mjm_tb_model',
            DBNAME.'/admin_tb_model'
        ));

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/vod_mjm?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'vm_id';

        if($id > 0) {
            $this->vod_mjm_tb_model->get($id);
            if($this->vod_mjm_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/vod_mjm?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->vod_mjm_tb_model->getData();
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

            $assign['display_date'] = date('Y-m-d', strtotime($values['vm_display_date']));
            $assign['display_time'] = date('H:i', strtotime($values['vm_display_date']));
            $assign['values'] = $values;

            $assign['admin_data'] = $this->admin_tb_model->get($values['vm_admin_id'])->getData();

        } else {
            // insert
            $fields = $this->vod_mjm_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'vm_is_active' : 
                       $values[$field] = 'YES';
                       break;
                    default : 
                        $values[$field] = '';
                }
            }
            $assign['display_date'] = '';
            $assign['display_time'] = '';
            $assign['values'] = $values;
        }

        $active_map = $this->vod_mjm_tb_model->getActiveMap();
        $assign['active_map'] = $active_map;

        $view_srv_map = $this->vod_mjm_tb_model->getViewSrvMap();
        $assign['view_srv_map'] = $view_srv_map;

		$pay_map = $this->vod_mjm_tb_model->getPayMap();
        $assign['pay_map'] = $pay_map;

        $this->_view('main/vod_mjm_detail', $assign);
    }

    /**
     * 미주미동영상 등록/수정 처리
    */
    public function vod_mjm_process() {
        $this->load->model(DBNAME.'/vod_mjm_tb_model');
        $request = $this->input->post();
        $sess = $this->session->userdata('admin');

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update', 'delete')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/vod_mjm?keep=yes');
            return;
        }

        $vm_id = $request['vm_id'];
        $display_date = $request['display_date'].' '.$request['display_time'].':00';
        $params = array(
            'vm_display_date' => $display_date,
            'vm_title' => $request['vm_title'],
            'vm_content' => $request['vm_content'],
            'vm_is_active' => $request['vm_is_active'],
            'vm_view_srv' => $request['vm_view_srv'],
            'vm_pay' => $request['vm_pay'],
            'vm_thumbnail' => '',
        );

        if(isset($_FILES['vm_main_thumbnail']) && strlen($_FILES['vm_main_thumbnail']['tmp_name']) > 0) {
            $this->load->library('upload');
            $config = array();
            $config['upload_path'] = ATTACH_DATA.'/vod_mjm';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);
            if($this->upload->do_upload('vm_main_thumbnail')) {
                $result = $this->upload->data();
                $params['vm_main_thumbnail'] = $result['file_name'];
            }
        }

        // content 중에 이미지가 존재하면 첫번재 이미지를 썸네일로 등록한다.
        $pattern = "/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i";
        preg_match_all($pattern, $request['vm_content'], $matches);

        if(isset($matches[1][0]) && strlen($matches[1][0]) > 0) {
            $params['vm_thumbnail'] = $matches[1][0];
        }

        if($request['mode'] == 'insert') { 
            $params['vm_admin_id'] = $sess['id'];
            if( ! $this->vod_mjm_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $vm_id = $this->vod_mjm_tb_model->getData();

            $redirect_url = '/adminpanel/main/vod_mjm_detail/'.$vm_id;

        } else if($request['mode'] == 'update') {
            $params['vm_updated_at'] = date('Y-m-d H:i:s');
            if( ! $this->vod_mjm_tb_model->doUpdate($vm_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            unset($params['vm_content']);
            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $vm_id, $log_array, 'vod_mjm_tb');

			$redirect_url = '/adminpanel/main/vod_mjm_detail/'.$vm_id;

        } else {
            if( ! $this->vod_mjm_tb_model->doDelete($vm_id)->isSuccess()) {
                $this->common->alert('삭제 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $redirect_url = '/adminpanel/main/vod_mjm?keep=yes';
        }

		//미주미동영상 파일 생성
        $params = array();
        $params['=']['vm_is_active'] = 'YES';
        //$params['<=']['vm_display_date'] = date('Y-m-d H:i:s');

        $extra = array(
            'fields' => array('vm_id', 'vm_title', 'vm_display_date', 'vm_view_srv', 'vm_main_thumbnail', 'vm_pay'),
            'order_by' => 'vm_display_date DESC',
			'limit' => 20,
        );

        $vod_mjm_list = $this->vod_mjm_tb_model->getList($params, $extra)->getData();

		if(is_array($vod_mjm_list) && sizeof($vod_mjm_list) > 0) {
            $data = json_encode($vod_mjm_list);
            $vod_mjm_file = 'vod_mjm_list.json';
            $file_path = WEBDATA.'/json/'.$vod_mjm_file;
            $file_backpath = $file_path . '.bak';

            touch($file_backpath);
            file_put_contents($file_backpath, $data);
            rename($file_backpath, $file_path);
        }

        $this->common->alert('처리되었습니다.');
        $this->common->locationhref($redirect_url);
        return;
    }


















    /**
     * 푸시 리스트
    */
    public function push() {
        $this->load->model(DBNAME.'/push_tb_model');
        $sess = $this->session->userdata('admin');
        $request = $this->input->get();

        $data = array();

		$push_map = $this->push_tb_model->getPushMap();
        $data['push_map_sel'] = $this->common->genJqgridOption($push_map);
        $data['push_map'] = $this->common->genJqgridOption($push_map, true);

		if(isset($request['mode']) && $request['mode'] == 'list') {
            // ajax reqeust. ==> grid list data 

            $page = $this->input->get('page');
            $limit = $this->input->get('rows');
            $_search = $this->input->get('_search');

            $params = array();
            $extra = array();

            if(isset($_GET['filters'])){
                $filters = $_GET['filters'];
                $params = $this->common->filter_to_params($filters, $_search, array('pu_id','pu_title','pu_display_date','pu_created_at','pu_updated_at'));
            }
            $params['join']['admin_tb'] = 'a_id = pu_admin_id';
            

            if(isset($request['sidx']) && strlen($request['sidx']) > 0) {
                $order_by = $request['sidx']." ".$request['sord'];
                $extra['order_by'] = array($order_by);
            }

            $extra['fields'] = 'push_tb.*, a_loginid';
            $extra['offset'] = ($page-1)*$limit;
            $extra['limit'] = $limit;

            $list = $this->push_tb_model->getList($params, $extra)->getData();

            $totalCount = $this->push_tb_model->getCount($params)->getData();

            $json_data = new stdClass;
            $json_data->rows = array();
            foreach($list as $k=>$r){
                $r['pu_title'] = htmlspecialchars($r['pu_title']);

                $json_data->rows[$k]['id'] = $r['pu_id'];
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

                $data['pu_created_at'] = date('Y-m-d H:i:s');
                $data['pu_updated_at'] = date('Y-m-d H:i:s');

                // todo. $request 데이터 이외 셋팅할 부분 이 아래에서 채우기. 함승목.

                if($this->push_tb_model->doInsert($data)->isSuccess() == false) {
                    print_r($this->push_tb_model->getErrorMsg());
                    return;
                }

                $act_key = $this->push_tb_model->getData();
                $log_array = array();
                $log_array['params'] = $data;
                $this->common->write_history_log($sess, 'INSERT', $act_key, $log_array, 'push_tb');
                return;
            }

            if(isset($request['id']) && isset($request['ids']) == false) {
                // 1 row 수정
                if(isset($request['edit_action_is']) && $request['edit_action_is'] == 'DEL') {
                    // 삭제 상태로 셋팅. 삭제.
                    if($this->push_tb_model->get($request['id'])->isSuccess()){
                        $del_data = $this->push_tb_model->getData();
                        $this->push_tb_model->doDelete($request['id']);
                        $act_key = $request['id'];
                        $log_array = array();
                        $log_array['params'] = $del_data;
                        $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'push_tb');
                    }
                    return;
                }
                
                // row 수정
                $request['pu_updated_at'] = date('Y-m-d H:i:s');
                //todo. Update시 추가 셋팅값 여기서 채우기. 함승목

                $this->push_tb_model->doUpdate($request['id'], $request);
                $act_key = $request['id'];
                $log_array = array();
                $log_array['params'] = $request;
                $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'push_tb');
                return;
            }

            // 일괄 상태변경

            $ids = explode(',',$request['ids']);

            if(sizeof($ids) > 0 && sizeof($request['multi_update_field_data_map']) > 0) {
                $data = $request['multi_update_field_data_map'];
                foreach($ids as $id) {
                    if($request['edit_action_is'] == 'DEL') {
                        if($this->push_tb_model->get($id)->isSuccess()){
                            $del_data = $this->push_tb_model->getData();
                            $this->push_tb_model->doDelete($id);
                            $act_key = $id;
                            $log_array = array();
                            $log_array['params'] = $del_data;
                            $this->common->write_history_log($sess, 'DELETE', $act_key, $log_array, 'push_tb');
                        }
                    } else {
                        $this->push_tb_model->doUpdate($id, $data);
                        $act_key = $id;
                        $log_array = array();
                        $log_array['params'] = $data;
                        $this->common->write_history_log($sess, 'UPDATE', $act_key, $log_array, 'push_tb');
                    }
                }
                echo 'success';
            } else {
                echo 'fail';
            }
            return;

        }

		$this->_view('main/push', $data);
    }

    /**
     * 푸시 등록/수정 폼
    */
    public function push_detail($id=0) {
        $this->load->model(array(
            DBNAME.'/push_tb_model',
            DBNAME.'/admin_tb_model'
        ));

        if( ! is_numeric($id)) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/push?keep=yes');
            return;
        }

        $id = intval($id);

        $assign = array();
        $assign['mode'] = 'insert';
        $assign['mode_kor'] = '등록';
        $assign['pk'] = 'pu_id';

        if($id > 0) {
            $this->push_tb_model->get($id);
            if($this->push_tb_model->isSuccess() == FALSE) {
                $this->common->alert('pk '.$id.' is empty.');
                $this->common->locationhref('/adminpanel/main/push?keep=yes');
                return;
            }
            $assign['mode'] = 'update';
            $assign['mode_kor'] = '수정';
            $values = $this->push_tb_model->getData();

			$length = iconv('UTF-8', 'EUC-KR', $values['pu_content']);
			$values['length'] = $this->str_hex_dump($length); 

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

            $assign['display_date'] = date('Y-m-d', strtotime($values['pu_display_date']));
            $assign['display_time'] = date('H:i', strtotime($values['pu_display_date']));
            $assign['values'] = $values;

            $assign['admin_data'] = $this->admin_tb_model->get($values['pu_admin_id'])->getData();

        } else {
            // insert
            $fields = $this->push_tb_model->getFields();
            $values = array();
            foreach($fields as $field) {
                switch($field) {
                    case 'pu_is_push' : 
                       $values[$field] = 'N';
                       break;
                    default : 
                        $values[$field] = '';
                }
            }
            $assign['display_date'] = '';
            $assign['display_time'] = '';
			$values['length'] = 0; 
            $assign['values'] = $values;
        }

		$push_map = $this->push_tb_model->getPushMap();
        $assign['push_map'] = $push_map;

        $this->_view('main/push_detail', $assign);
    }

    /**
     * 푸시 등록/수정 처리
    */
    public function push_process() {
        $this->load->model(DBNAME.'/push_tb_model');
        $request = $this->input->post();
        $sess = $this->session->userdata('admin');

        if( ! (is_array($request) && in_array($request['mode'], array('insert', 'update', 'delete')))) {
            $this->common->alert('올바르지 않은 접근입니다.');
            $this->common->locationhref('/adminpanel/main/push?keep=yes');
            return;
        }

        $pu_id = $request['pu_id'];
        $display_date = $request['display_date'].' '.$request['display_time'].':00';
        $params = array(
            'pu_title' => $request['pu_title'],
            'pu_content' => $request['pu_content'],
            'pu_is_push' => $request['pu_is_push'],
            'pu_all' => ($request['pu_all'] == 'Y') ? $request['pu_all']:'N',
            'pu_choice' => ($request['pu_choice'] == 'Y') ? $request['pu_choice']:'N',
            'pu_kiwoom' => ($request['pu_kiwoom'] == 'Y') ? $request['pu_kiwoom']:'N',
            'pu_display_date' => $display_date,
        );

        if($request['mode'] == 'insert') { 
            $params['pu_admin_id'] = $sess['id'];
            if( ! $this->push_tb_model->doInsert($params)->isSuccess()) {
                $this->common->alert('등록 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $pu_id = $this->push_tb_model->getData();

            $redirect_url = '/adminpanel/main/push_detail/'.$pu_id;

        } else if($request['mode'] == 'update') {
            $params['pu_updated_at'] = date('Y-m-d H:i:s');
            if( ! $this->push_tb_model->doUpdate($pu_id, $params)->isSuccess()) {
                $this->common->alert('수정 실패하였습니다.');
                $this->common->historyback();
                return;
            }

            //unset($params['pu_content']);
            $log_array = array();
            $log_array['params'] = $params;
            $this->common->write_history_log($sess, 'UPDATE', $pu_id, $log_array, 'push_tb');

			$redirect_url = '/adminpanel/main/push_detail/'.$pu_id;

        } else {
            if( ! $this->push_tb_model->doDelete($pu_id)->isSuccess()) {
                $this->common->alert('삭제 실패하였습니다.');
                $this->common->historyback();
                return;
            }
            $redirect_url = '/adminpanel/main/push?keep=yes';
        }

        $this->common->alert('처리되었습니다.');
        $this->common->locationhref($redirect_url);
        return;
    }
}
?>
