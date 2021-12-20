<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__).'/base_pc.php';
class Test extends BasePC_Controller {

	public function vchart($ticker, $dimension, $type, $indicator) {
		$data = $this->load->model('business/historylib');

		if( ! $this->historylib->getVChartData($ticker, $dimension, $type, $indicator)->isSuccess()) {
			echo $this->historylib->getErrorMsg();
			return;
		}

		$result = $this->historylib->getData();
		//echo '<pre>';
		//print_r($result);
		//echo '</pre>';

		$this->_view('stocks/test', $result);
	}

    public function avg6y_roe() {
        $this->load->model('quandl/sf1_tb_model');
        $map = $this->sf1_tb_model->getTicker6YRoeMap();
        print_r($map);
    }

    public function getVmri($ticker='AAPL') {
        $this->load->model('business/historylib');

/*
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        if( ! $this->ticker_tb_model->get(array(
			'tkr_table' => 'SEP', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();
        if($tdata['tkr_isdelisted'] == 'Y') {
            echo '상폐종목입니다.';
            return;
        }
*/
        $mri = $this->historylib->getTickerMRI($ticker);
        $total = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
        }

		return $total;
        
		//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
        //echo '<h2>주식 MRI Total Score : '.$total.'</h2>';
        //echo '<pre>';
        //print_r($mri);
        //echo '</pre>';

        //echo '<hr />';

        //$mri = $this->historylib->getTickerCompanyMRI($ticker);
        //$total = 0;
        //foreach($mri as $item) {
        //   $total += $item['total_score'];
        //}
        //echo '<h2>기업 MRI Total Score : '.$total.'</h2>';
        //echo '<pre>';
        //print_r($mri);
        //echo '</pre>';

		//return $total;
    }


    public function mri($ticker='AAPL') {
        $this->load->model('business/historylib');

        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        if( ! $this->ticker_tb_model->get(array(
			'tkr_table' => 'SEP', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();
        if($tdata['tkr_isdelisted'] == 'Y') {
            echo '상폐종목입니다.';
            return;
        }

        $mri = $this->historylib->getTickerMRI($ticker);
        $total = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
        }
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
        echo '<h2>주식 MRI Total Score : '.$total.'</h2>';
        echo '<pre>';
        print_r($mri);
        echo '</pre>';

        echo '<hr />';

        $mri = $this->historylib->getTickerCompanyMRI($ticker);
        $total = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
        }
        echo '<h2>기업 MRI Total Score : '.$total.'</h2>';
        echo '<pre>';
        print_r($mri);
        echo '</pre>';
    }

    public function mri_val($ticker='AAPL') {
        $this->load->model('business/historylib');

        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        if( ! $this->ticker_tb_model->get(array(
			'tkr_table' => 'SEP', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();
        if($tdata['tkr_isdelisted'] == 'Y') {
            echo '상폐종목입니다.';
            return;
        }
//echo '<pre>';
//print_r($tdata);
        $mri = $this->historylib->getTickerValMRI($ticker, $tdata);
        $total = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
        }
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
        echo '<h2>주식 MRI Total Score : '.$total.'</h2>';
        echo '<pre>';
        print_r($mri);
        echo '</pre>';

        echo '<hr />';
    }

    public function spider($ticker='AAPL') {
        $this->load->model('business/historylib');

        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

		if( ! $this->ticker_tb_model->get(array(
			'tkr_table' => 'SEP', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();

        if($tdata['tkr_isdelisted'] == 'Y') {
            echo '상폐종목입니다.';
            return;
        }

        $mri = $this->historylib->getTickerSpider($ticker);
        $total = 0;
        //$total_A = 0;
        //$total_B = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
        }
        echo '<h2>'.$tdata['tkr_ticker'].' 스파이더 점수(D) : (성장성,  안전성, 현금창출력, 사업독점력, 배당 ) : '.$total.'</h2>';
		echo $tdata['tkr_name'];
        echo '<pre>';
        print_r($mri);
        echo '</pre>';

        echo '<hr />';

        $mri = $this->historylib->getTickerValueSpider($ticker);
        $total = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
        }
        echo '<h2>'.$tdata['tkr_ticker'].' 스파이더 점수(V) : (성장성,  안전성, 현금창출력, 사업독점력, 밸류에이션 ) : '.$total.'</h2>';
		echo $tdata['tkr_name'];
        echo '<pre>';
        print_r($mri);
        echo '</pre>';
    }


    public function spiderv_last_view($ticker='AAPL', $sep_date='') {
        $this->load->model('business/historylib_last');

        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        if( ! $this->ticker_tb_model->get(array(
			//'tkr_table' => 'SEP',  /* 2020.05.26 변경 SEP->SF1 */
			'tkr_table' => 'SF1', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();

		if($sep_date == '') $sep_date = date('Y-m-d', strtotime('-1 days'));

        $mri = $this->historylib_last->getTickerValueSpider_last($ticker, $sep_date);
        $total = 0;
//echo '<pre>';
//print_r($mri);
        foreach($mri as $item=>$item_val) {
			//if($item !='dividend') {
	            $total += $item_val['total_score'];
			//}
        }
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        echo '<h2>'.$tdata['tkr_ticker'].' 스파이더 점수(V) : (성장성,  안전성, 현금창출력, 사업독점력, 밸류에이션 ) : '.$total.'</h2>';
		//2020.05.26
		if($total>=75 && $mri['growth']['valuation_op_inc'] > 100000000) {
			echo '<br>[높음]';
		}
		else if($total>=50 && $total<75) {
			echo '<br>[보통]';
		}
		else if($total>=0 && $total<50) {
			echo '<br>[낮음]';
		}
		else {
			echo '<br>[없음]';
		}

		echo '<br>'.$tdata['tkr_name'];
        echo '<pre>';
        print_r($mri);
        echo '</pre>';
    }

    public function spiderv_last() {
        if( ! $this->input->is_cli_request()) {
            die('cli only');
        }

        set_time_limit(0);
		echo "\n".'['.date("Y-m-d H:i:s")."] spiderv_last start!!\n";

        $this->load->model('business/historylib_last');
		$this->load->model(DBNAME.'/value_tb_model');

		$arr_tkr = array('V','W','X','Y','Z');

		foreach($arr_tkr as $tkr_val) {
			$params = array();
			$params['=']['tkr_table'] = 'sf1';
			$params['!=']['tkr_exchange'] = 'OTC';
			$params['=']['tkr_isdelisted'] = 'N';
			$params['like_']['tkr_ticker'] = $tkr_val;

			$extra = array(
				'fields' => 'tkr_ticker',
				'slavedb' => true,
				'order_by' => 'tkr_ticker ASC',
			);

			$ticker_list = $this->ticker_tb_model->getList($params, $extra)->getData();

			foreach($ticker_list as $key=>$val) {
				echo "\n\n".'ticker===============> '.$val['tkr_ticker']."\n";
				for($year=2009;$year<2021;$year++) {

					for($mon=1;$mon<13;$mon++) {

						if($year=='2020'&& $mon>6) break;
						
						$sep_date = $year.'-'.sprintf('%02d',$mon);

						$sep_params = array();
						$sep_params['=']['sep_ticker'] = $val['tkr_ticker'];
						$sep_params['like_']['sep_date'] = $sep_date;

						$sep_extra = array(
							'fields' => 'sep_date',
							'slavedb' => true,
							'order_by' => 'sep_date ASC',
							'limit' => 1,
						);

						$sep_mon = $this->sep_tb_model->getList($sep_params, $sep_extra)->getData();

						if(is_array($sep_mon) && sizeof($sep_mon)>0) {
							$mri = array();
							$mri = $this->historylib_last->getTickerValueSpider_last($val['tkr_ticker'], str_replace('-','',$sep_mon[0]['sep_date']));

							if(is_array($mri) && sizeof($mri)>0) {
								$total = 0;

								foreach($mri as $item=>$item_val) {
									$total += $item_val['total_score'];
								}
							
								if( $mri['valuation']['fairvalue3'] == '') {
									$mri['valuation']['fairvalue3'] = 'N/A';
								}
								else {
									if(@is_nan($mri['valuation']['fairvalue3']) || @is_infinite($mri['valuation']['fairvalue3'])) {
										$mri['valuation']['fairvalue3'] = 'N/A';
									}
								}

								$insert_params = array(
									'v_ticker' => $val['tkr_ticker'],
									'v_date' => $sep_mon[0]['sep_date'],
									'v_close' => $mri['valuation']['last_price'],
									'v_fairvalue' => $mri['valuation']['fairvalue3'],
									'v_total_score' => $total,
								);

								$this->value_tb_model->doInsert($insert_params);
							}
						}
					}
				}
			}
		}

		echo "\n".'['.date("Y-m-d H:i:s")."] spiderv_last end!!\n";
    }

    public function spiderv($ticker='AAPL') {
        $this->load->model('business/historylib');

        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        if( ! $this->ticker_tb_model->get(array(
			//'tkr_table' => 'SEP',  /* 2020.05.26 변경 SEP->SF1 */
			'tkr_table' => 'SF1', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();

        if($tdata['tkr_isdelisted'] == 'Y') {
            echo '상폐종목입니다.';
            return;
        }

        $mri = $this->historylib->getTickerValueSpider($ticker);
        $total = 0;
//echo '<pre>';
//print_r($mri);
        foreach($mri as $item=>$item_val) {
			//if($item !='dividend') {
	            $total += $item_val['total_score'];
			//}
        }
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        echo '<h2>'.$tdata['tkr_ticker'].' 스파이더 점수(V) : (성장성,  안전성, 현금창출력, 사업독점력, 밸류에이션 ) : '.$total.'</h2>';
		//2020.05.26
		if($total>=75 && $mri['growth']['valuation_op_inc'] > 100000000) {
			echo '<br>[높음]';
		}
		else if($total>=50 && $total<75) {
			echo '<br>[보통]';
		}
		else if($total>=0 && $total<50) {
			echo '<br>[낮음]';
		}
		else {
			echo '<br>[없음]';
		}

		echo '<br>'.$tdata['tkr_name'];
        echo '<pre>';
        print_r($mri);
        echo '</pre>';
    }

    public function mri_new($ticker='AAPL') {
        $this->load->model('business/historylib');

        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

        if( ! $this->ticker_tb_model->get(array(
			'tkr_table' => 'SEP', 
			'tkr_ticker' => $ticker, 
			'tkr_isdelisted' => 'N' 
			))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();
        if($tdata['tkr_isdelisted'] == 'Y') {
            echo '상폐종목입니다.';
            return;
        }

        $mri = $this->historylib->getTickerNewMRI($ticker);
        $total = 0;
        $total_A = 0;
        $total_B = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
            $total_A += $item['total_score_A'];
            $total_B += $item['total_score_B'];
        }
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
        echo '<h2>스파이더 점수(성장성, 재무&현금창출력 통합, 사업독점력, 배당매력 4개 합산) : '.$total_A.'</h2>';
        echo '<h2>스파이더 점수(D) : (성장성,  안전성, 현금창출력, 사업독점력 4개 합산) : '.$total_B.'</h2>';
        echo '<h2>스파이더 점수(V) : '.$this->getVmri($ticker).'</h2>';
        echo '<pre>';
        print_r($mri);
        echo '</pre>';

        echo '<hr />';

/*
        $mri = $this->historylib->getTickerCompanyNewMRI($ticker);
        $total = 0;
        foreach($mri as $item) {
            $total += $item['total_score'];
        }
        echo '<h2>기업 MRI Total Score : '.$total.'</h2>';
        echo '<pre>';
        print_r($mri);
        echo '</pre>';
*/
    }


	public function daily_top_marketcap() {
		$this->load->model('quandl/daily_tb_model');

		$params = array();
		$params['join']['ticker_tb'] = 'tkr_ticker = dly_ticker';

		$extra = array();
		$extra['fields'] = 'dly_date, dly_ticker, tkr_name, dly_marketcap';
		$extra['order_by'] = 'dly_marketcap desc';
		$extra['limit'] = 20;


		$fp = fopen(WEBDATA.'/daily_top.csv', 'w');


		for($i = 365*20 ; $i >= 0 ; $i++) {
		//for($i = 20 ; $i >= 0 ; $i--) {
			$params['dly_date'] = date('Y-m-d', strtotime(' -'.$i.' days'));
			//select dly_date, dly_ticker, dly_marketcap from daily_tb where dly_date = '2019-07-22' order by dly_marketcap desc limit 20;
			$rows = $this->daily_tb_model->getList($params, $extra)->getData();
			foreach($rows as $idx => $row) {
				array_unshift($row, $idx+1);
				fputcsv($fp, $row);
			}
		}
		fclose($fp);
	}
	public function currency() {
		echo $this->common->getCurrency();
	}
	public function elastic_sf1() {
		$this->load->model(array(
					DBNAME.'/sf1_tb_model',
					));

		$params = array();
		$sf1_id = 2099991;
		$params['=']['sf1_id'] = $sf1_id;

		$extra = array(
				'order_by' => '',
				'fields' => 'sf1_id, sf1_ticker, sf1_created_at, sf1_updated_at',
				);

		$res = $this->sf1_tb_model->getElasticList($params, $extra)->getData();
		print_r($res);
		//exit;

		$up_params = array(
				'sf1_updated_at' => date('Y-m-d H:i:s'), // orig : 2019-05-25 18:45:58
				);

		echo "\n DB >>> ";
		echo $this->sf1_tb_model->doUpdate($sf1_id, $up_params)->isSuccess() ? 'Updated!' : 'Update Fail..';
		echo "\n";




		$res = $this->sf1_tb_model->getElasticList($params, $extra)->getData();
		print_r($res);
	}



    public function getDailyLastDate() {
		$this->load->model(DBNAME.'/sep_tb_model');
		$rows = $this->common->getDataByPK($this->sep_tb_model->getList(array(), array('order_by' => 'sep_date desc', 'limit' => 1))->getData(), 'sep_ticker');
		$last_row = array_shift($rows);
        $last_date = $last_row['sep_date'];

		echo '<pre>'; print_r($last_date);
		return $last_date;
    }
    public function getDailyPrevDate() {
		$this->load->model(DBNAME.'/sep_tb_model');
		
		$params = array();
		$params['<']['sep_date'] = $this->getDailyLastDate();
		$rows = $this->common->getDataByPK($this->sep_tb_model->getList($params, array('order_by' => 'sep_date desc', 'limit' => 1))->getData(), 'sep_ticker');
		
		$prev_row = array_shift($rows);
        $prev_date = $prev_row['sep_date'];
		
		echo '<pre>'; print_r($prev_date);
		return $prev_date;
    }



    public function getDailyLastRow() {
		//$this->load->model(DBNAME.'/sep_tb_model');
		//$rows = $this->common->getDataByPK($this->sep_tb_model->getList(array(), array('order_by' => 'sep_date desc', 'limit' => 1))->getData(), 'sep_ticker');

		//echo '<pre>'; print_r(array_shift($rows));

            $rows = $this->common->getDataByPK($this->daily_tb_model->getList(array(), array('order_by' => 'dly_date desc', 'limit' => 10, 'slavedb' => true))->getData(), 'dly_date');
            ksort($rows);
            $this->daily_last_row = array_pop($rows);

            $params = array();
            $params['<']['dly_date'] = $this->daily_last_row['dly_date'];
            $rows = $this->common->getDataByPK($this->daily_tb_model->getList($params, array('order_by' => 'dly_date desc', 'limit' => 10, 'slavedb' => true))->getData(), 'dly_date');
            $this->daily_prev_row = array_pop($rows);

		echo '<pre>'; print_r($this->daily_prev_row);

	}




}
