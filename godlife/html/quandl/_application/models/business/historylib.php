<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once dirname(__FILE__).'/BaseBusiness.php';

class Historylib extends BaseBusiness {

    private $grid_filenames = array(
        '티커(종목정보)'=> 'grid.ticker.csv',
        '재무상태표'     => 'grid.balancesheet.csv',
        '재무상태표2'     => 'grid.balancesheet2.csv',
        '손익계산서'     => 'grid.incomestate.csv',
        '현금흐름표'     => 'grid.cashflow.csv',
        '재무&투자지표' => 'grid.fininvestindi.csv',
        '주가지표'     => 'grid.pricesheet.csv',
    );

    // 생성자에서 CSV 정의 sheet 읽어와 채워둠.
    private $loaded_table_info_maps = array();

    // $grid_filenames 파일의 중간일므 (ticker, balancesheet, balancesheet2, ..) 모음
    private $info_file_codes = array(); 

    function __construct() {
        $this->load->model(array(
            DBNAME.'/ticker_tb_model',
            DBNAME.'/indicator_tb_model',
            DBNAME.'/sf1_tb_model',
            DBNAME.'/sf2_tb_model',
            DBNAME.'/sf3_tb_model',
            DBNAME.'/sf3a_tb_model',
            DBNAME.'/sf3b_tb_model',
            DBNAME.'/sep_tb_model',
            DBNAME.'/daily_tb_model',
        ));
        foreach($this->grid_filenames as $fname) {
            list($grid, $code, $csv) = explode('.', $fname, 3);
            $this->info_file_codes[] = $code;
        }
        $this->loadTableInfoMaps();
    }

    public function getGridFilenames() {
        return $this->grid_filenames;
    }

    public function getTableInfo($tab_name='') {
        if($tab_name == '') {
            return $this->loaded_table_info_maps;
        }
        if(isset($this->loaded_table_info_maps[$tab_name])) {
            return ($this->loaded_table_info_maps[$tab_name]);
        }
        return array();
    }

    // models/business/grid.filenmae.csv 를 읽어들여 key value 맵을 리턴함.`
    public function getTableMap($filename, $key_field, $value_field) {
        $key_field = strtolower($key_field);
        $value_field = strtolower($value_field);

        $data = array();
        $result = array();
        if(in_array(strtolower($filename), $this->info_file_codes)) {
            $data = $this->getTableInfo($filename);
        }
        return $this->common->array2Map($data, $key_field, $value_field);
    }

    // 스프래드시트에 정의한대로 데이터 구성
    private function loadTableInfoMaps() {
        if(sizeof($this->loaded_table_info_maps) > 0) return;
        $map = array();

        $files = $this->info_file_codes;

        // 일반적으로 테이블명이 테이블 필드 prefix로 적용되어있다. ex: sf1_tb.sf1_roe, sep_tb.sep_close, ...
        // 그런데 몇몇 테이블은 그렇지 않다. ticker_tb.tkr_id, daily_tb.dly_pb
        // 이러한 케이스(테이블명이 필드 프리픽스가 아닌 테이블) 정보를 정의함.
        $table_prefix_map = array(
                'tickers' => 'tkr',
                'daily' => 'dly',
                'company' => 'cp',
                );

        foreach($files as $file) {
            if( ! is_file(APPPATH."/models/business/grid.{$file}.csv")) {
                $map[$file][] = array();
                continue;
            }

            $idx = 0;
            $keys = array();
            if (($handle = fopen(APPPATH."/models/business/grid.{$file}.csv", "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 100000, "\t")) !== FALSE) {
                    $idx++;
                    if(sizeof(array_filter($row)) <= 0) continue;

                    if($idx == 1) {
                        $keys = array_map('strtolower', $row);
                        continue;
                    }

                    if(sizeof($row) != sizeof($keys)) {
                        echo "[ $idx ] ";
                        echo $file.' : size miss match'."\n";
                        echo sizeof($keys).' != '.sizeof($row)."\n";
                        continue;
                    }
                    $row = array_combine($keys, $row);

                    // Indicator 값을 sf1_tb field명으로 만들기.

                    if(isset($table_prefix_map[strtolower($row['table'])])) {
                        $row['indicator'] = strtolower($table_prefix_map[strtolower($row['table'])]).'_'.$row['indicator']; 
                    } else {
                        $row['indicator'] = strtolower($row['table']).'_'.$row['indicator']; 
                    }

                    $map[$file][$row['indicator']] = $row;
                }
                fclose($handle);
            }
        }
        $this->loaded_table_info_maps = $map;
    }


    // 랭킹 리스트 제공 메서드
    private $daily_last_row = array(); // lazy pattern
    private $daily_prev_row = array(); // lazy pattern
    public function getDailyLastRow() {
        if(sizeof($this->daily_last_row) <= 0) {
            $rows = $this->common->getDataByPK($this->daily_tb_model->getList(array(), array('order_by' => 'dly_date desc', 'limit' => 10, 'slavedb' => true))->getData(), 'dly_date');
            ksort($rows);
            $this->daily_last_row = array_pop($rows);

            $params = array();
            $params['<']['dly_date'] = $this->daily_last_row['dly_date'];
            $rows = $this->common->getDataByPK($this->daily_tb_model->getList($params, array('order_by' => 'dly_date desc', 'limit' => 10, 'slavedb' => true))->getData(), 'dly_date');
            $this->daily_prev_row = array_pop($rows);
        };
        return $this->daily_last_row;
    }

    // daily 테이블의 최근 들어간 날짜 리턴. 이는 sep, daily 등 매일 insert되는 테이블의 최근 기준이 될 수 있음.
    public function getDailyLastDate() {
        /*
		$last_row = $this->getDailyLastRow();
        $last_date = $last_row['dly_date'];
        return $last_date;
		*/

		$rows = $this->common->getDataByPK($this->sep_tb_model->getList(array(), array('order_by' => 'sep_date desc', 'limit' => 1))->getData(), 'sep_ticker');
		$last_row = array_shift($rows);
        $last_date = $last_row['sep_date'];

		return $last_date;
    }
    public function getDailyPrevDate() {
		/*
        $this->getDailyLastRow();
        $prev_date = $this->daily_prev_row['dly_date'];
        return $prev_date;
		*/

		$params = array();
		$params['<']['sep_date'] = $this->getDailyLastDate();
		$rows = $this->common->getDataByPK($this->sep_tb_model->getList($params, array('order_by' => 'sep_date desc', 'limit' => 1))->getData(), 'sep_ticker');
		
		$prev_row = array_shift($rows);
        $prev_date = $prev_row['sep_date'];
		
		return $prev_date;
    }

    // 특정 종목의 마지막 daily테이블 row 를 반환.
    public function getTickerDailyLastRow($ticker, $date='') {
        $params = array();
        $params['=']['dly_ticker'] = $ticker;
        if(strlen($date) >= 8) {
            $params['=']['dly_date'] = $date;
        }

        $daily_last_row = array_shift($this->daily_tb_model->getList($params, array('order_by' => 'dly_id desc', 'limit' => 1, 'slavedb' => true, 'cache_sec'=> 3600*24))->getData());
        return $daily_last_row;
    }


	// 속성별 랭킹. 메인 종목 발굴
	public function getRankList($type) {
		$allow_types = array('per', 'pbr', 'roe', 'yield');
		$type = strtolower(trim($type));
		if( ! in_array($type, $allow_types)) {
			return array();
		}


		// 변수 공통값 설정
		$to_calendardate = '';
		$from_calendardate = '';
		$col_count = 4;

		$sf1_dimension = '';
		$sf1_display_field = '';
		switch($type) {
			case 'per' :
				$sf1_dimension = 'MRQ';

				if(date('n') <= 3) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,1,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,1-3*$col_count,0,date('Y')));
				} else if(date('n') <= 6) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,4,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,4-3*$col_count,0,date('Y')));
				} else if(date('n') <= 9) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,7,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,7-3*$col_count,0,date('Y')));
				} else if(date('n') <= 12) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,10,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,10-3*$col_count,0,date('Y')));
				}

				$sf1_display_field = 'dly_pe';


				break;
			case 'pbr' :
				$sf1_dimension = 'MRQ';

				if(date('n') <= 3) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,1,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,1-3*$col_count,0,date('Y')));
				} else if(date('n') <= 6) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,4,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,4-3*$col_count,0,date('Y')));
				} else if(date('n') <= 9) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,7,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,7-3*$col_count,0,date('Y')));
				} else if(date('n') <= 12) {
					$to_calendardate 	= date('Y-m-d', mktime(0,0,0,10,0,date('Y')));
					$from_calendardate 	= date('Y-m-d', mktime(0,0,0,10-3*$col_count,0,date('Y')));
				}

				$sf1_display_field = 'dly_pb';

				break;
			case 'roe' :
				$sf1_dimension = 'MRY';

				$to_calendardate 	= date('Y-m-d', mktime(0,0,0,1,0,date('Y')+1));
				$from_calendardate 	= date('Y-m-d', mktime(0,0,0,1,0,date('Y')-1));

				$sf1_display_field = 'sf1_roe';

				break;
			case 'yield' :
				$sf1_dimension = 'MRY';

				$to_calendardate 	= date('Y-m-d', mktime(0,0,0,1,0,date('Y')));
				$from_calendardate 	= date('Y-m-d', mktime(0,0,0,1,0,date('Y')-$col_count));

				$sf1_display_field = 'sf1_divyield';
				break;
		}




		$last_date = $this->getDailyLastDate();

		$params = array();

		$extra = array(
			'fields' => '*',
			'limit' => 160,
            'cache_sec' => 3600,
		);

		$result = array();

		switch($type) {
			case 'per' :
				$params['dly_date'] = $last_date;
				$params['join']['ticker_tb'] = 'dly_ticker = tkr_ticker and tkr_lastpricedate = dly_date and tkr_table = "SEP" and tkr_industry not like "REIT%" and tkr_category not like "%ADR%" and tkr_currency = "USD" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
				$params['join']['sep_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date';
				$params['join']['sf1_tb'] = 'sf1_ticker = tkr_ticker and sf1_opinc > 100000000 and sf1_dimension = "MRT" and sf1_epsdil > 0 and sf1_calendardate = "2020-06-30"';
				$params['>']['dly_pe'] = 0;
				$extra['order_by'] = 'dly_pe asc'; // per
				$result = $this->common->getDataByPk($this->daily_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
				break;
				/*
				$params['dly_date'] = $last_date;
				$params['join']['ticker_tb'] = 'dly_ticker = tkr_ticker and tkr_lastpricedate = dly_date and tkr_table = "SEP" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
				$params['join']['sep_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date';

				$params['>']['dly_pe'] = 0;
				$extra['order_by'] = 'dly_pe asc'; // per
				$result = $this->common->getDataByPk($this->daily_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
				break;
				*/
			case 'pbr' :
				$params['dly_date'] = $last_date;
				$params['join']['ticker_tb'] = 'dly_ticker = tkr_ticker and tkr_lastpricedate = dly_date  and tkr_table = "SEP" and tkr_industry not like "REIT%" and tkr_category not like "%ADR%" and tkr_currency = "USD" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
				$params['join']['sep_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date';
				$params['join']['sf1_tb'] = 'sf1_ticker = tkr_ticker and sf1_opinc > 100000000 and sf1_dimension = "MRT" and sf1_epsdil > 0 and sf1_calendardate = "2020-06-30"';

				$params['>']['dly_pb'] = 0;
				$extra['order_by'] = 'dly_pb asc'; // pbr
				$result = $this->common->getDataByPk($this->daily_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
				break;
			case 'roe' :

                $roe_params = $params; // 빈배열
				$roe_params['>']['sf1_calendardate'] = $from_calendardate;
				$roe_params['=']['sf1_dimension'] = 'MRT';
				$roe_params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker and tkr_table="SEP" and tkr_industry not like "REIT%" and tkr_category not like "%ADR%" and tkr_currency = "USD"  and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
				$roe_params['join']['sep_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date';
				$roe_params['=']['sep_date'] = $last_date;
                $roe_params['>']['sf1_equityavg'] = 0;
                //$roe_params['<']['sf1_roe'] = 1;
                $roe_params['>']['sf1_opinc'] = '100000000';
                $roe_params['>']['sf1_epsdil'] = '0';

                $roe_extra = $extra;
                unset($roe_extra['limit']);
				$roe_extra['fields'] = 'max(sf1_id) as sf1_id';
				$roe_extra['order_by'] = '';
				$roe_extra['group_by'] = 'sf1_ticker';

                $sf1_ids = array_keys($this->common->getDataByPk($this->sf1_tb_model->getList($roe_params, $roe_extra)->getData(), 'sf1_id'));


				$params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker and tkr_table="SEP" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
				$params['join']['sep_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date';
				$params['=']['sep_date'] = $last_date;
                $params['in']['sf1_id'] = $sf1_ids;

				$extra['order_by'] = 'sf1_roe desc';

				$result = $this->common->getDataByPk($this->sf1_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
				break;
			case 'yield' :
				$params['=']['sf1_calendardate'] = $to_calendardate;
				$params['=']['sf1_dimension'] = 'MRY';
                $params['>']['sf1_opinc'] = '100000000';
				$params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker and tkr_table="SEP" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';
				$params['join']['sep_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date';
				$params['=']['sep_date'] = $last_date;

				$extra['order_by'] = 'sf1_divyield desc';
				//$extra['fields'] = 'ticker_tb.*, sf1_divyield, sf1_calendardate, sep_tb.*';
				$result = $this->common->getDataByPk($this->sf1_tb_model->getList($params, $extra)->getData(), 'tkr_ticker');
				break;
		}

		// 전반 데이터 획득 완료

		/*
		// 획득 종목의 최종 종가 구하기
		$tickers = array_keys($result);

		$sep_params = array();
		$sep_params['in']['sep_ticker'] = $tickers;
		$sep_params['=']['sep_date'] = $last_date;

		$sep_extra = array(
			'order_by' => '',
			'fields' => 'sep_ticker, sep_close'
		);
		$sep_list = $this->common->getDataByPK($this->sep_tb_model->getList($sep_params, $sep_extra)->getData(), 'sep_ticker');

        if(sizeof($tickers) != sizeof($sep_list)) {
            $empty_tickers = implode(',', array_diff($tickers, array_keys($sep_list)));
            echo $empty_tickers;exit;
        }

		// 최종 종가를 $result 각 row에 추가해주기
		foreach($result as &$row) {
			$row['last_close'] = $sep_list[$row['tkr_ticker']]['sep_close'];
		}


		// SF1 데이터 가져오기. 종복당 4개.
		$sf1_params = array();
		$sf1_params['in']['sf1_ticker'] = $tickers;
		$sf1_params['=']['sf1_dimension'] = $sf1_dimension;
		$sf1_params['between']['sf1_calendardate'] = array($from_calendardate, $to_calendardate);
		$sf1_params['>=']['sf1_reportperiod'] = $from_calendardate;

		$sf1_extra = array(
			'order_by' => 'sf1_ticker, sf1_calendardate desc',
		);

		$sf1_list = array();
		$sf1_list = $this->sf1_tb_model->getList($sf1_params, $sf1_extra)->getData();
		foreach($sf1_list as $sf1_row) {
			if( ! isset($result[$sf1_row['sf1_ticker']]['histories'])) {
				$result[$sf1_row['sf1_ticker']]['histories'] = array();
			}
			if(sizeof($result[$sf1_row['sf1_ticker']]['histories']) > $col_count) {
				continue;
			}

			$result[$sf1_row['sf1_ticker']]['histories'][$sf1_row['sf1_calendardate']] = $sf1_row[$sf1_display_field];
		}
		*/
		
		return $result;
	}
//20.01/13 스파이더차트 신규 공식 추가
    // 종목별 기업MRI 데이터 제공
    public function getTickerCompanyNewMRI($ticker) {
        $result = array(
            'growth' => array(),
            'safety' => array(),
            'cashflow' => array(),
            'moat' => array(),
            'dividend' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getNewMRIData($ticker, $k);
        }

        // 재무안정성이 1 이하면 밸류에이션 스타점수 0점 처리
        if($result['safety']['stars'] <= 1 && $result['dividend']['stars'] > 0) {
            $result['valuation']['stars'] = 0;
            $result['valuation']['star_memo'] = '재무안정성 미달로 0점 처리됨';
        }

        return $result;
    }

    // 종목별 주식MRI 데이터 제공
    public function getTickerNewMRI($ticker) {
        $result = array(
            'growth' => array(),
            'safety' => array(),
            'cashflow' => array(),
            'moat' => array(),
            'safety+cashflow' => array(),
            'dividend' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getNewMRIData($ticker, $k);
        }

        // 재무안정성이 1 이하면 밸류에이션 스타점수 0점 처리
        if($result['safety']['stars'] <= 1 && $result['valuation']['stars'] > 0) {
            $result['valuation']['stars'] = 0;
            $result['valuation']['star_memo'] = '재무안정성 미달로 0점 처리됨';
        }

        return $result;
    }

    // 종목별 주식MRI 데이터 제공, 구) getTickerCompanyMRI 매칭
    public function getTickerSpider($ticker) {
        $result = array(
            'growth' => array(),
            'safety' => array(),
            'cashflow' => array(),
            'moat' => array(),
            'dividend' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getSpiderData($ticker, $k);
        }

        return $result;
    }

    // 종목별 주식MRI 데이터 제공, 구)getTickerMRI 매칭
    public function getTickerValueSpider($ticker) {
        $result = array(
            'growth' => array(),
            'safety' => array(),
            'cashflow' => array(),
            'moat' => array(),
            'dividend' => array(),
            'valuation' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getSpiderVData($ticker, $k);
        }

        return $result;
    }

    // 종목별 주식MRI 데이터 제공, 구)getTickerMRI 매칭
    public function getTickerValueSpiderImsi($ticker) {
        $result = array(
            'growth' => array(),
            'safety' => array(),
            'cashflow' => array(),
            'moat' => array(),
            'valuation' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getSpiderData($ticker, $k);
        }

        return $result;
    }

    private function getSpiderVData($ticker, $mri_item) { //최종수정 2020.01/28
        $ticker = strtoupper($ticker);
        $mri_item = strtolower($mri_item);
        
        if( ! $this->ticker_tb_model->get(array(
            'tkr_table' => 'SEP', 
            'tkr_ticker' => $ticker, 
            'tkr_isdelisted' => 'N' 
            ))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();
        
        $tkr_fin = false;    //금융업
        $tkr_bank = false;    //은행업
        $tkr_insu = false;    //보험업
        $tkr_cscm = false;    //Credit Services, Capital Markets
        $tkr_adr = true;		//ADR
        $tkr_adr_value = true;  //ADR(20.05.26추가)
        $tkr_clsname = '';

        //if( $tdata['tkr_category'] == 'Domestic' || $tdata['tkr_category'] == 'Domestic Primary' || $tdata['tkr_category'] == 'Domestic Common Stock' ) { 2020.08.26수정
        if( strstr($tdata['tkr_category'], 'Domestic') ) {
            $tkr_adr = false;    //ADR
            $tkr_adr_value = false;    //ADR(밸류에이션)
        }

		//2020.08.26 변경 if(strtoupper($tdata['tkr_category'])=='ADR' || strtoupper($tdata['tkr_category']) =='ADR PRIMARY' || strtoupper($tdata['tkr_category'])=='CANADIAN' || strtoupper($tdata['tkr_category'])=='CANADIAN PRIMARY') {
		if( strstr(strtoupper($tdata['tkr_category']), 'ADR') || strstr(strtoupper($tdata['tkr_category']), 'CANADIAN') ) {
			$tkr_adr = true;    //ADR
			$tkr_adr_value = true;    //ADR(밸류에이션)
		}

        if($tdata['tkr_sector']=='Financial Services') {

            if( $tdata['tkr_industry'] == 'Banks - Diversified' || $tdata['tkr_industry'] == 'Banks - Global' || $tdata['tkr_industry'] == 'Banks - Regional' || $tdata['tkr_industry'] == 'Banks - Regional - Latin America' || $tdata['tkr_industry'] == 'Banks - Regional - US' || $tdata['tkr_industry'] == 'Savings & Cooperative Banks') {
                $tkr_bank = true;
                $tkr_clsname = '은행업';
            }
            else if( $tdata['tkr_industry'] == 'Insurance - Diversified' || $tdata['tkr_industry'] == 'Insurance - Life' || $tdata['tkr_industry'] == 'Insurance - Property & Casualty' || $tdata['tkr_industry'] == 'Insurance - Reinsurance' || $tdata['tkr_industry'] == 'Insurance - Specialty' || $tdata['tkr_industry'] == 'Insurance - Brokers') {
                $tkr_insu = true;
                $tkr_clsname = '보험업';
            }
            else if($tdata['tkr_industry'] == 'Credit Services' || $tdata['tkr_industry'] == 'Capital Markets') {
                $tkr_cscm = true;
                $tkr_clsname = $tdata['tkr_industry'];
            }
            else if($tdata['tkr_industry'] == 'Financial Data & Stock Exchanges') {
                //비금융업(20.02/13)
            }
            else {
                $tkr_fin = true;
                $tkr_clsname = '금융업';
            } 
        }

        $item_detail_map = array();

        //수익성장성
        //avg6yroe->20 MRT roe대체(2020.01/23)
        //$item_detail_map['growth']['avg6yroe'] = '';
        $item_detail_map['growth']['roe'] = '';
        $item_detail_map['growth']['epsgr'] = '';

        //재무안전성
        if($tkr_bank === true || $tkr_insu === true || $tkr_fin === true || $tkr_cscm === true) {
            $item_detail_map['safety']['bis'] = '';
        }
        else {
            $item_detail_map['safety']['crratio'] = '';
            $item_detail_map['safety']['debtratio'] = '';
            $item_detail_map['safety']['intcoverage'] = '';
            $item_detail_map['safety']['borrowingratio'] = '';
            $item_detail_map['safety']['financialcost'] = '';
        }
    
        //현금창출력
        if($tkr_fin === true || $tkr_bank === true || $tkr_insu === true || $tkr_cscm === true) {
            $item_detail_map['cashflow']['cashflow'] = '';
            $item_detail_map['cashflow']['ncfo2'] = '';
        }
        else {
            $item_detail_map['cashflow']['ncfo'] = '';
            $item_detail_map['cashflow']['pcr'] = '';
            $item_detail_map['cashflow']['cashflow'] = '';
            $item_detail_map['cashflow']['ncfo2'] = '';
            $item_detail_map['cashflow']['fcfonrevenue'] = '';
        }
        
        //사업독점력
        if($tkr_fin === true || $tkr_bank === true || $tkr_insu === true || $tkr_cscm === true) {
            $item_detail_map['moat']['roe'] = '';
            $item_detail_map['moat']['assetsgr'] = '';
            $item_detail_map['moat']['opmargin'] = '';
        }
        else {
            $item_detail_map['moat']['roe'] = '';
            $item_detail_map['moat']['opmargin'] = '';
            $item_detail_map['moat']['revenuegr'] = '';
            $item_detail_map['moat']['longtermdebt'] = '';
            $item_detail_map['moat']['netincncfo'] = '';
        }

        //배당매력도
        $item_detail_map['dividend']['epsgr2'] = '';
        $item_detail_map['dividend']['fcfgr'] = '';
        $item_detail_map['dividend']['divyield'] = '';
        $item_detail_map['dividend']['payoutratio'] = '';
        $item_detail_map['dividend']['dps'] = '';

        //밸류에이션
        $item_detail_map['valuation']['valuation'] = '';
        //$item_detail_map['valuation_new']['evebitdavaluation'] = '';
        //$item_detail_map['valuation_new']['yamaguchivaluation'] = '';
        
        if( ! array_key_exists($mri_item, $item_detail_map)) {
            return array();
        }

        $result = array();

        $first_flag = true;
        $total_score = 0;
        

        // 벨류에이션, 배당 매력에서 참조함
        $interest_rate = 0.0279; //0.0302=>0.0279(19.12/17변경) // 3.02% "적용금리". 추수 조정 가능성이 많음.

        //Financial Services 스파이더 점수 산정(01.20 적용)

        $g_roe = '';
        $g_epsgr = '';

        $s_bis = '';
        $s_crratio = '';
        $s_debtratio = '';
        $s_intcoverage = '';
        $s_boingratio = '';
        $s_fincost = '';

        $c_pcr = '';
        $c_cashflow = '';
        $c_ffrevenue = '';

        $m_roe = '';
        $m_assetsgr = '';
        $m_opmargin = '';
        $m_revenuegr = '';

        $d_epsgr2 = '';
        $d_fcfgr = '';
        $d_divyield = '';
        $d_poratio = '';
        $d_dps_year1 = '';
        $d_dps_year2 = '';
        $d_dps_year3 = '';
        $d_dps_year4 = '';
        $d_dps_year5 = '';
        $d_dps1 = '';
        $d_dps2 = '';
        $d_dps3 = '';
        $d_dps4 = '';
        $d_dps5 = '';

        foreach($item_detail_map[$mri_item] as $item_detail => $v) {
            $score = 0;
            $memo = '';

            // 수익 성장성
            if($mri_item == 'growth') {
                if($first_flag) {
                    $first_flag = false;

                    // avg6yroe 필요데이터 구하기
                    //$ticker_6yroe_map = $this->sf1_tb_model->getTicker6YRoeMap();
                    //avg6yroe->20 MRT roe대체(2020.01/23)

                    if($tkr_adr === true) {
                        $extra = array(
                                'limit' => 5,
                                );
                    }
                    else {
                        $extra = array(
                                'limit' => 21,
                                );
                    }
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
					$mrt_list = $mrt_list['findata'];


					if($tkr_adr === true) {
						$valuation_op_inc = ($mrt_list[0]['sf1_opinc']/$mrt_list[0]['sf1_fxusd']);
					}
					else {
						$valuation_op_inc = $mrt_list[0]['sf1_opinc'];
					}
//echo '<pre>';
//print_r($mrt_list);
                    if($tkr_adr === true) {
                        // epsgr 필요데이터 구하기
                        $extra = array(
                                'limit' => 2,
                                );
                    }
                    else {
                        // epsgr 필요데이터 구하기
                        $extra = array(
                                'limit' => 5,
                                );
                    }
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = $this->common->array2Map($mrt_data['findata'], 'sf1_datekey', 'sf1_epsdil');
//echo '<pre>';
//print_r($mrt_data);

                    $current = array_shift($mrt_data);
                    $before = array_pop($mrt_data);
                    $rate = @($current / $before -1)*100;
                    $rate = round($rate, 2);
                }

                if( $tkr_bank === true || $tkr_insu == true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_roe']*100;
                            }
                            $avg = round(array_sum($log)/count($log),2);

                            if( $avg > 10 ) {
                                $score = 10;
                            }
                            else if( $avg > 5 ) {
                                $score = 6;
                            }
                            else if( $avg > 3 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                
                            $memo = '(20 MRT roe AVG '.$avg.' 10점(roe>10), 6점(roe>5), 4점(roe>3), 2점(roe>0), 0점';
                            $g_roe = $avg;
                            break;
                        case 'epsgr' :

                            $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'% : 10(epsgr >= 25), 8(15 <= epsgr < 25), 6(7.2 <= epsgr < 15), 4(0 <= epsgr < 7.2), 2(epsgr < 0 && eps > 0 && (t-1y)eps < 0 ), 0( epsgr < 0 && (t-1y)eps < 0 or epsgr < 0) ';
                            $g_epsgr =$rate;
                            
                            if($current < 0 && $before < 0) {
                                $score = 0;
                            } else if($rate < 0 && $current > 0 && $before < 0) {
                                $score = 2;
                            } else if($rate < 0 ) {
                                $score = 0;
                            } else if($rate >= 25) {
                                $score = 10;
                            } else if($rate >= 15) {
                                $score = 8;
                            } else if($rate >= 7.2) {
                                $score = 6;
                            } else if($rate >= 0) {
                                $score = 4;
                            }
                            break;
                    }
                }
                else if( $tkr_fin === true || $tkr_cscm === true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_roe']*100;
                            }
                            $avg = round(array_sum($log)/count($log),2);

                            if( $avg > 15 ) {
                                $score = 10;
                            }
                            else if( $avg > 10 ) {
                                $score = 6;
                            }
                            else if( $avg > 5 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                
                            $memo = '(20 MRT roe AVG '.$avg.' 10점(roe>15), 6점(roe>10), 4점(roe>5), 2점(roe>0), 0점';
                            $g_roe = $avg;
                            break;
                        case 'epsgr' :

                            $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'% : 10(epsgr >= 25), 8(15 <= epsgr < 25), 6(7.2 <= epsgr < 15), 4(0 <= epsgr < 7.2), 2(epsgr < 0 && eps > 0 && (t-1y)eps < 0 ), 0( epsgr < 0 && (t-1y)eps < 0 or epsgr < 0) ';
                            $g_epsgr =$rate;
                            
                            if($current < 0 && $before < 0) {
                                $score = 0;
                            } else if($rate < 0 && $current > 0 && $before < 0) {
                                $score = 2;
                            } else if($rate < 0 ) {
                                $score = 0;
                            } else if($rate >= 25) {
                                $score = 10;
                            } else if($rate >= 15) {
                                $score = 8;
                            } else if($rate >= 7.2) {
                                $score = 6;
                            } else if($rate >= 0) {
                                $score = 4;
                            }
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_roe']*100;
                            }
                            $avg = round(array_sum($log)/count($log),2);

                            if( $avg > 15 ) {
                                $score = 10;
                            }
                            else if( $avg > 10 ) {
                                $score = 6;
                            }
                            else if( $avg > 5 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                
                            $memo = '(20 MRT roe AVG '.$avg.' 10점(roe>15), 6점(roe>10), 4점(roe>5), 2점(roe>0), 0점';
                            $g_roe = $avg;
                            break;
                        case 'epsgr' :

                            $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'%)';
                            $g_epsgr =$rate;
                            
                            if($current < 0 && $before < 0) {
                                $score = 0;
                            } else if($rate < 0 && $current > 0 && $before < 0) {
                                $score = 2;
                            } else if($rate < 0 ) {
                                $score = 0;
                            } else if($rate >= 25) {
                                $score = 10;
                            } else if($rate >= 15) {
                                $score = 8;
                            } else if($rate >= 7.2) {
                                $score = 6;
                            } else if($rate >= 0) {
                                $score = 4;
                            }
                            break;
                    }
                }
            }

            // 재무안전성
            if($mri_item == 'safety') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 1,
                            );
                    // currentratio, de 필요데이터 구하기
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);
                }

                if( $tkr_bank === true ) { 
                    switch($item_detail) {
                        case 'bis' :
                            if($tkr_adr === true) {
                                $bis = @( $mrt_data['sf1_equity'] / $mrt_data['sf1_assets'] * 100 );
                                $bis = round($bis, 2);
                            }
                            else {
                                $bis = @( $mrq_data['sf1_equity'] / $mrq_data['sf1_assets'] * 100 );
                                $bis = round($bis, 2);
                            }
                            if($bis>8) {
                                $score = 20;
                            }
                            else if($bis>6) {
                                $score = 12;
                            }
                            else if($bis>4) {
                                $score = 8;
                            }
                            else if($bis>2) {
                                $score = 4;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(bis : '.$bis.') 20(bis>8), 12(bis>6), 8(bis>4), 4(bis>2), 0';
                            $s_bis = $bis;
                            break;
                    }
                }
                else if( $tkr_fin === true || $tkr_insu === true || $tkr_cscm === true ) { 
                    switch($item_detail) {
                        case 'bis' :
                            if($tkr_adr === true) {
                                $bis = @( $mrt_data['sf1_equity'] / $mrt_data['sf1_assets'] * 100 );
                                $bis = round($bis, 2);
                            }
                            else {
                                $bis = @( $mrq_data['sf1_equity'] / $mrq_data['sf1_assets'] * 100 );
                                $bis = round($bis, 2);
                            }
                            if($bis>=10) {
                                $score = 20;
                            }
                            else if($bis>8) {
                                $score = 16;
                            }
                            else if($bis>6) {
                                $score = 12;
                            }
                            else if($bis>4) {
                                $score = 8;
                            }
                            else if($bis>2) {
                                $score = 4;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(bis : '.$bis.') 20(bis>=10), 16(bis>8), 12(bis>6), 8(bis>4), 4(bis>2), 0';
                            $s_bis = $bis;
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'crratio' :
                            //print_r($mrq_data);
                            if($tkr_adr === true) {
                                $score = $mrt_data['sf1_currentratio'] >= 1 ? 2 : 0;
                                $memo = '(currentratio '.round($mrt_data['sf1_currentratio'],2).' >= 1 ? 2 : 0)';
                                $s_crratio = round($mrt_data['sf1_currentratio'],2);
                            }
                            else {
                                $score = $mrq_data['sf1_currentratio'] >= 1 ? 2 : 0;
                                $memo = '(currentratio '.round($mrq_data['sf1_currentratio'],2).' >= 1 ? 2 : 0)';
                                $s_crratio = round($mrt_data['sf1_currentratio'],2);
                            }
                            break;
                        case 'debtratio' :
                            if($tkr_adr === true) {
                                if($mrt_data['sf1_de']=='N/A') {
                                    $score = 0;
                                }
                                else {
                                    $score = round($mrt_data['sf1_de'],2) <= 1.5 ? 2 : 0;
                                }
                                $memo = '(de '.round($mrt_data['sf1_de'],2).' <= 1.5 ? 2 : 0)';
                                $s_debtratio = round($mrt_data['sf1_de'],2);
                            }
                            else {
                                if($mrq_data['sf1_de']=='N/A') {
                                    $score = 0;
                                }
                                else {
                                    $score = round($mrq_data['sf1_de'],2) <= 1.5 ? 2 : 0;
                                }
                                $memo = '(de '.round($mrq_data['sf1_de'],2).' <= 1.5 ? 2 : 0)';
                                $s_debtratio = round($mrt_data['sf1_de'],2);
                            }
                            break;
                        case 'intcoverage' :
                            //print_r($mrt_data);
                            if( ! $mrt_data['sf1_intexpcoverage'] || $mrt_data['sf1_intexpcoverage'] == 'N/A') {
                                //$estim_intexpcoverage = ($mrt_data['sf1_debtc'] + $mrt_data['sf1_debtnc']) * 0.04;
                                //$estim_intexpcoverage = ($mrt_data['sf1_debtc']) * 0.04;
                                $estim_intexpcoverage = @( $mrt_data['sf1_opinc'] / ( $mrt_data['sf1_debtc'] * 0.04 ) );
                                $estim_intexpcoverage = round($estim_intexpcoverage, 2);

                                if($mrt_data['sf1_opinc']<0) {
                                    $score = 0;
                                }
                                else {
                                    if( $estim_intexpcoverage > 2 || ( $mrt_data['sf1_opinc'] > 0 && $mrt_data['sf1_intexp'] < 0 ) ) {
                                        $score = 8;
                                    }
                                    else if( $estim_intexpcoverage > 1 ) {
                                        $score = 4;
                                    }
                                    else {
                                        $score = 0;
                                    }
                                }
                                //$score = $estim_intexpcoverage >= 2 ? 8 : 0;
                                //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].' + '.$mrt_data['sf1_debtnc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                                //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                                $memo = '추정) opinc ' .$mrt_data['sf1_opinc']. ' / ( debtc '.$mrt_data['sf1_debtc'].' * 0.04 ) = ['.$estim_intexpcoverage.'] : 8(intcoverage > 2), 4(1 < intcoverage <= 2), 0(intcoverage <= 1), sf1_opinc:'.$mrt_data['sf1_opinc'].'sf1_intexp:'.$mrt_data['sf1_intexp'];
                                $s_intcoverage = $estim_intexpcoverage;
                            } else {
                                if($mrt_data['sf1_opinc']<0) {
                                    $score = 0;
                                }
                                else {
                                    if( $mrt_data['sf1_intexpcoverage'] > 2 || ( $mrt_data['sf1_opinc'] > 0 && $mrt_data['sf1_intexp'] < 0 ) ) {
                                        $score = 8;
                                    }
                                    else if( $mrt_data['sf1_intexpcoverage'] > 1 ) {
                                        $score = 4;
                                    }
                                    else {
                                        $score = 0;
                                    }
                                }
                                //$score = $mrt_data['sf1_intexpcoverage'] >= 2 ? 8 : 0;
                                $memo = '(intexpcoverage '.round($mrt_data['sf1_intexpcoverage'],2).' : 8(intcoverage > 2), 4(1 < intcoverage <= 2), 0(intcoverage <= 1), sf1_opinc:'.$mrt_data['sf1_opinc'].'sf1_intexp:'.$mrt_data['sf1_intexp'];
                                $s_intcoverage = round($mrt_data['sf1_intexpcoverage'],2);
                            }
                            break;
                        case 'borrowingratio' :
                            //$score = $mrt_data['sf1_borrowtoassets'] <= 0.3 ? 4 : 0;
                            if($mrt_data['sf1_borrowtoassets'] <= 0.3) {
                                $score = 4;
                            }
                            else if($mrt_data['sf1_borrowtoassets'] > 0.3 && $mrt_data['sf1_borrowtoassets'] <= 0.4) {
                                $score = 2;
                            }
                            else { //$mrt_data['sf1_borrowtoassets'] > 0.4 
                                $score = 0;
                            }
                            $memo = '(borrowtoassets '.round($mrt_data['sf1_borrowtoassets'],2).' : 4(borrowtoassets<=0.3), 2(0.3 < borrowtoassets <= 0.4), 0(borrowtoassets>0.4))';
                            $s_boingratio = round($mrt_data['sf1_borrowtoassets'],2);
                            break;
                        case 'financialcost' :
                            if($mrt_data['sf1_intexprevenue'] == 'N/A' || $mrt_data['sf1_intexprevenue'] == 0) {
                                $intexprevenue = (($mrq_data['sf1_debtc'] + $mrq_data['sf1_debtnc'])*0.04) / $mrt_data['sf1_revenue'];
                                $intexprevenue = round($intexprevenue,4);
                                //$score = $intexprevenue < 0.03 ? 4 : 0;
                                if($intexprevenue<=0.03) {
                                    $score = 4;
                                }
                                else if($intexprevenue>0.03 && $intexprevenue <=0.04) {
                                    $score = 2;
                                }
                                else { // $intexprevenue > 0.04 
                                    $score = 0;
                                }
                                
                                $memo = '( ((mrq debtc '.$mrq_data['sf1_debtc'].' + mrq debtnc '.$mrq_data['sf1_debtnc'].') * 0.04) / revenue '.$mrt_data['sf1_revenue'].' = ['.$intexprevenue.'] : 4(financialcost<=0.03), 2(0.03 < financialcost <= 0.04), 0(financialcost>0.04))';
                                $s_fincost = $intexprevenue;
                            } else {
                                //$score = $mrt_data['sf1_intexprevenue'] < 0.03 ?  4 : 0;
                                if($mrt_data['sf1_intexprevenue']<=0.03) {
                                    $score = 4;
                                }
                                else if($mrt_data['sf1_intexprevenue']>0.03 && $mrt_data['sf1_intexprevenue'] <=0.04) {
                                    $score = 2;
                                }
                                else { // $mrt_data['sf1_intexprevenue'] > 0.04 
                                    $score = 0;
                                }
                                $memo = '(intexprevenue '.round($mrt_data['sf1_intexprevenue'],2).' : 4(financialcost<=0.03), 2(0.03 < financialcost <= 0.04), 0(financialcost>0.04))';
                                $s_fincost = round($mrt_data['sf1_intexprevenue'],4);
                            }
                            break;
                    }
                }
            }

            // 현금 창출력
            if($mri_item == 'cashflow') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 4,
                            );
                    if($tkr_adr === true) {
                    
                    }
                    // currentratio, de 필요데이터 구하기
                    //$mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    //$mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];

                    //echo '<pre>';
                    //print_r($mrt_list);
                    $mrt_data = $mrt_list[0];
                    //echo '<pre>';
                    //print_r($mrt_data);
                }

                if( $tkr_bank === true || $tkr_cscm === true ) {
                    switch($item_detail) {
                        case 'cashflow' :
                        //sf1_ncfo:영업, sf1_ncff:재무, sf1_ncfi:투자
                            if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] > 0) {
                                $score = 10;
                            }
                            else if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] < 0) {
                                $score = 5;
                            }
                            else if($mrt_data['sf1_ncfo'] < 0) {
                                $score = 0;
                            }
                            $memo = '(ncfo : '.($mrt_data['sf1_ncfo']/1000000).'(백만달러), ncff : '.$mrt_data['sf1_ncff'].' : 10(ncfo > 0 && ncff > 0), 5(ncfo > 0 && ncff < 0), 0(ncfo < 0) )';
                            $c_cashflow = $mrt_data['sf1_ncfo']/1000000;
                            break;
                        case 'ncfo2' :
                            //$flag = true;
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_ncfo'];
                                //if($l['sf1_ncfo'] <= 0) {
                                //    $flag = false;
                                //}
                            }
                            //$score = $flag ? 6 : 0;
                            
                            if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']>0) {
                                $score = 10;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']<=0) {
                                $score = 8;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']<=0) {
                                $score = 5;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(4 MRT ncfo : '.implode(',', $log).' 4분기연속(10), 3분기연속(8), 2분기연속(5), 그외(0) )';
                            break;
                    }
                }
                else if( $tkr_fin === true || $tkr_insu == true ) {
                    switch($item_detail) {
                        case 'cashflow' :
                        //sf1_ncfo:영업, sf1_ncff:재무, sf1_ncfi:투자
                            if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] < 0) {
                                $score = 10;
                            }
                            else if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] > 0) {
                                $score = 5;
                            }
                            else if($mrt_data['sf1_ncfo'] < 0) {
                                $score = 0;
                            }
                            $memo = '(ncfo : '.($mrt_data['sf1_ncfo']/1000000).'(백만달러), ncff : '.$mrt_data['sf1_ncff'].' : 10(ncfo > 0 && ncff < 0), 5(ncfo > 0 && ncff > 0), 0(ncfo < 0) )';
                            $c_cashflow = $mrt_data['sf1_ncfo']/1000000;
                            break;
                        case 'ncfo2' :
                            //$flag = true;
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_ncfo'];
                                //if($l['sf1_ncfo'] <= 0) {
                                //    $flag = false;
                                //}
                            }
                            //$score = $flag ? 6 : 0;
                            
                            if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']>0) {
                                $score = 10;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']<=0) {
                                $score = 8;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']<=0) {
                                $score = 5;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(4 MRT ncfo : '.implode(',', $log).' 4분기연속(10), 3분기연속(8), 2분기연속(5), 그외(0) )';
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'ncfo' :
                            if($tkr_adr === true) {
                                if(isset($mrt_data['sf1_netinc_ori'])) {
                                    $score = $mrt_data['sf1_netinc_ori'] <= $mrt_data['sf1_ncfo'] ? 2 : 0;
                                    $memo = '(netinc '.$mrt_data['sf1_netinc_ori'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 2)';
                                }
                                else {
                                    $score = $mrt_data['sf1_netinc'] <= $mrt_data['sf1_ncfo'] ? 2 : 0;
                                    $memo = '(netinc '.$mrt_data['sf1_netinc'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 2)';
                                }
                            }
                            else {
                                $score = $mrt_data['sf1_netinc'] <= $mrt_data['sf1_ncfo'] ? 2 : 0;
                                $memo = '(netinc '.$mrt_data['sf1_netinc'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 2)';
                            }
                            break;
                        case 'pcr' :
                            $score = ($mrt_data['sf1_pc'] > 0 && $mrt_data['sf1_pc'] < 20) ? 2 : 0;
                            $memo = '((pc '.round($mrt_data['sf1_pc'],2).' > 0 && '.round($mrt_data['sf1_pc'],2).' < 20) ? 2 : 0)';
                            $c_pcr = round($mrt_data['sf1_pc'],2);
                            break;
                        case 'cashflow' :
                            //sf1_ncfo:영업, sf1_ncff:재무, sf1_ncfi:투자
                            if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] < 0) {
                                $score = 6;
                            }
                            else if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] > 0) {
                                $score = 3;
                            }
                            else if($mrt_data['sf1_ncfo'] < 0) {
                                $score = 0;
                            }
                            $memo = '(ncfo, ncff : '.($mrt_data['sf1_ncfo']/1000000).'(백만달러) > 0, '.$mrt_data['sf1_ncff'].' < 0 ? 6 : 0, 3(영업ncfo+/재무ncff+))';
                            $c_cashflow = $mrt_data['sf1_ncfo']/1000000;
                            break;
                        case 'ncfo2' :
                            //$flag = true;
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_ncfo'];
                                //if($l['sf1_ncfo'] <= 0) {
                                //    $flag = false;
                                //}
                            }
                            //$score = $flag ? 6 : 0;
                            
                            if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']>0) {
                                $score = 6;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']<=0) {
                                $score = 4;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']<=0) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(4 MRT ncfo : '.implode(',', $log).' 4분기연속(6), 3분기연속(4), 2분기연속(2), 그외(0) )';
                            break;
                        case 'fcfonrevenue' :
                            $score = $mrt_data['sf1_fcfonrevenue'] > 0.07 ?  4 : 0;
                            $memo = '(fcfonrevenue '.round($mrt_data['sf1_fcfonrevenue'],2).' > 0.07)';
                            $c_ffrevenue = round($mrt_data['sf1_fcfonrevenue'],2);
                            break;
                    }
                }
            }

            // 사업독점력
            if($mri_item == 'moat') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 21,
                            );
                    $mrq_list = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_list = $mrq_list['findata'];

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 21,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }

                if( $tkr_bank === true || $tkr_insu == true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_roe']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_roe']*100;
                                }
                            }
                            $avg = round(array_sum($log)/count($log),2);

                            if( $avg > 10 ) {
                                $score = 10;
                            }
                            else if( $avg > 5 ) {
                                $score = 6;
                            }
                            else if( $avg > 3 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                    
                            $memo = '(20 MRT roe AVG '.$avg.' : 10(roe > 10), 6(5< roe <= 10), 4(3< roe <= 5), 2(0< roe <= 3), 0(roe < 0)';
                            $m_roe = $avg; 
                            break;
                        case 'assetsgr' :
                            $log = array();
                            $cnt=0;
                            if($tkr_adr === true) {
                                foreach($mrt_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                                //echo '<pre>';
                                //print_r($log);
                            }
                            else {
                                foreach($mrq_list as $l) {
                                    if($cnt>20) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                            }
                            $current = array_shift($log);
                            $before = array_pop($log);

                            $rate = @( pow(($current / $before), (1/5)) - 1 ) * 100;
                            $rate = round($rate,2);
                            if( $rate > 5 ) {
                                $score = 5;
                            }
                            else if( $rate > 4 ) {
                                $score = 4;
                            }
                            else if( $rate > 3 ) {
                                $score = 3;
                            }
                            else if( $rate > 2 ) {
                                $score = 2;
                            }
                            else if( $rate > 1 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            if($tkr_adr === true) {
                                $memo = '(MRT assets / -5Y MRT assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            else {
                                $memo = '(MRQ assets / -5Y MRQ assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            $m_assetsgr = $rate;
                            break;
                        case 'opmargin' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_opmargin']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_opmargin']*100;
                                }
                            }
                            $avg = round(array_sum($log)/count($log),2);

                            if( $avg > 10 ) {
                                $score = 5;
                            }
                            else if( $avg > 8 ) {
                                $score = 4;
                            }
                            else if( $avg > 6 ) {
                                $score = 3;
                            }
                            else if( $avg > 4 ) {
                                $score = 2;
                            }
                            else if( $avg > 2 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            //$score = $avg >= 10 ? 4 : 0;
                            $memo = '(20 MRT opmargin AVG '.$avg.' : 5(opmargin > 10), 4(8 < opmargin <= 10), 3(6 < opmargin <= 8), 2(4 < opmargin <= 6), 1(2 < opmargin <= 4), 0)';
                            $m_opmargin = $avg;
                            break;
                    }

                }
                else if( $tkr_fin === true || $tkr_cscm === true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_roe']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_roe']*100;
                                }
                            }
                            $avg = round(array_sum($log)/count($log),2);

                            if( $avg > 15 ) {
                                $score = 10;
                            }
                            else if( $avg > 10 ) {
                                $score = 6;
                            }
                            else if( $avg > 5 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                    
                            $memo = '(20 MRT roe AVG '.$avg.' : 10(roe > 15), 6(10< roe <= 15), 4(5< roe <= 10), 2(0< roe <= 5), 0(roe < 0)';
                            $m_roe = $avg;
                            break;
                        case 'assetsgr' :
                            $log = array();
                            $cnt=0;
                            if($tkr_adr === true) {
                                foreach($mrt_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrq_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                            }
                            $current = array_shift($log);
                            $before = array_pop($log);

                            $rate = @( pow(($current / $before), (1/5)) - 1 ) * 100;
                            $rate = round($rate,2);
                            if( $rate > 5 ) {
                                $score = 5;
                            }
                            else if( $rate > 4 ) {
                                $score = 4;
                            }
                            else if( $rate > 3 ) {
                                $score = 3;
                            }
                            else if( $rate > 2 ) {
                                $score = 2;
                            }
                            else if( $rate > 1 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            if($tkr_adr === true) {
                                $memo = '(MRT assets / -5Y MRT assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            else {
                                $memo = '(MRQ assets / -5Y MRQ assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            break;
                            $m_assetsgr = $rate;
                        case 'opmargin' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_opmargin']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_opmargin']*100;
                                }
                            }
                            $avg = round(array_sum($log)/count($log),2);

                            if( $avg > 20 ) {
                                $score = 5;
                            }
                            else if( $avg > 15 ) {
                                $score = 4;
                            }
                            else if( $avg > 10 ) {
                                $score = 3;
                            }
                            else if( $avg > 5 ) {
                                $score = 2;
                            }
                            else if( $avg > 0 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            //$score = $avg >= 10 ? 4 : 0;
                            //$memo = '(20 MRT opmargin AVG '.$avg.' >= 10 ? 5 : 0)';
                            $memo = '(20 MRT opmargin AVG '.$avg.' : 5(opmargin > 20), 4(15 < opmargin <= 20), 3(10 < opmargin <= 15), 2(5 < opmargin <= 10), 1(0 < opmargin <= 5), 0)';
                            $m_opmargin = $avg;
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_roe']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_roe']*100;
                                }
                            }
                            $avg = round(array_sum($log)/count($log),2);
                            
                            if($avg > 15) {
                                $score = 6;
                            } 
                            else if($avg > 10) {
                                $score = 4;
                            }
                            else if($avg > 5) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(20 MRT roe AVG '.$avg.' : 6(roe > 15), 4(roe > 10), 2(roe > 5), 0(roe<=5))';
                            $m_roe = $avg;
                            break;
                        case 'opmargin' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_opmargin']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_opmargin']*100;
                                }
                            }
                            $avg = round(array_sum($log)/count($log),2);
                            if($avg>20) {
                                $score = 6;
                            }
                            else if($avg>15) {
                                $score = 5;
                            }
                            else if($avg>10) {
                                $score = 4;
                            }
                            else if($avg>5) {
                                $score = 2;
                            }
                            else if($avg>0) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(20 MRT opmargin AVG '.$avg.' : 6(avg > 20), 5(15 < avg <= 20), 4(10 < avg <= 15), 2(5 < avg <= 10), 1(0 < avg <= 5), 0(avg <= 0)';
                            $m_opmargin = $avg;
                            break;
                        case 'revenuegr' :
                            $log = array();
                            //echo '<pre>';
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_revenue'];
                                    $cnt++;
                                }
                                //print_r($log);
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_revenue'];
                                }
                            }
                            //print_r($log).'<br>';
                            $current = array_shift($log);
                            $before = array_pop($log);
                            //=(129814000000/93456000000)^(1/5)-1
                            $rate = ( pow(($current / $before), (1/5)) - 1 ) * 100;
                            $rate = round($rate,2);
                            if( $rate >= 7.2 ) {
                                $score =  4;
                            }
                            else if( $rate >= 3.6 ) {
                                $score =  2;
                            }
                            else {
                                $score =  0;
                            }

                            $memo = '(revenue / -5Y revenue)^(1/5)% rate=['.$rate.'] >= 7.2(4) >=3.6(2) 그 외(0) ) revenue=>'.$current.' revenue-5Y=>'.$before;
                            $m_revenuegr = $rate;
                            if(is_nan($m_revenuegr)){
                                $m_revenuegr = 0;
                            }
                            break;
                        case 'longtermdebt' :
                            if($tkr_adr === true) {
                                if(isset($mrt_data['sf1_netinc_ori'])) {
                                    $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc_ori']*3) ? 2 : 0;
                                    $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc_ori']*3).' ? 2 : 0)';
                                }
                                else {
                                    $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc']*3) ? 2 : 0;
                                    $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc']*3).' ? 2 : 0)';
                                }

                            }
                            else {
                                $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc']*3) ? 2 : 0;
                                $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc']*3).' ? 2 : 0)';
                            }
                            break;
                        case 'netincncfo' :
                            if($tkr_adr === true) {
                                if(isset($mrt_data['sf1_netinc_ori'])) {
                                    $score = (0 < $mrt_data['sf1_netinc_ori'] && $mrt_data['sf1_netinc_ori'] * 0.5 < $mrt_data['sf1_ncfo']) ?  2 : 0;
                                    $memo = '(netinc '.($mrt_data['sf1_netinc_ori']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc_ori'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 2 : 0)';
                                }
                                else {
                                    $score = (0 < $mrt_data['sf1_netinc'] && $mrt_data['sf1_netinc'] * 0.5 < $mrt_data['sf1_ncfo']) ?  2 : 0;
                                    $memo = '(netinc '.($mrt_data['sf1_netinc']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 2 : 0)';
                                }
                            }
                            else {
                                $score = (0 < $mrt_data['sf1_netinc'] && $mrt_data['sf1_netinc'] * 0.5 < $mrt_data['sf1_ncfo']) ?  2 : 0;
                                $memo = '(netinc '.($mrt_data['sf1_netinc']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 2 : 0)';
                            }
                            break;
                    }                
                }
            }

            // 배당매력
            if($mri_item == 'dividend') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );

                    $mry_list = $this->getFinStateList($ticker, 'MRY', $extra)->getData();
                    $mry_list = $mry_list['findata'];
                    $mry_data = $mry_list[0];

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                    //echo 'size=>'.sizeof($mrt_list).'<br><pre>';
                    //print_r($mrt_list);
                }
                switch($item_detail) {
                    case 'epsgr2' :
                        if($tkr_adr === true) {
                            $score_val = @($mrt_data['sf1_epsdil'] / $mrt_list[1]['sf1_epsdil'] -1) * 100;
                            $score_val = round($score_val,2);
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '((mrt epsdil '.round($mrt_data['sf1_epsdil'],2).' / t-1y mrt epsdil '.$mrt_list[1]['sf1_epsdil'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        else {
                            $score_val = @($mrt_data['sf1_epsdil'] / $mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'] -1) * 100;
                            $score_val = round($score_val,2);
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '((mrt epsdil '.round($mrt_data['sf1_epsdil'],2).' / t-1y mrt epsdil '.$mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        $d_epsgr2 = $score_val;
                        break;
                    case 'fcfgr' :
                        if($tkr_adr === true) {
                            $score_val = @($mrt_data['sf1_fcf'] / $mrt_list[1]['sf1_fcf'] -1) * 100;
                            $score_val = round($score_val,2);
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '( (mrt fcf '.$mrt_data['sf1_fcf'].' / t-1y mrt fcf '.$mrt_list[1]['sf1_fcf'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        else {
                            $score_val = @($mrt_data['sf1_fcf'] / $mrt_list[sizeof($mrt_list)-1]['sf1_fcf'] -1) * 100;
                            $score_val = round($score_val,2);
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '( (mrt fcf '.$mrt_data['sf1_fcf'].' / t-1y mrt fcf '.$mrt_list[sizeof($mrt_list)-1]['sf1_fcf'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        $d_fcfgr = $score_val;
                        break;
                    case 'divyield' :
                        //$score = $mry_data['sf1_divyield'] >= $interest_rate ? 6 : 0;
						$comp_rate = $interest_rate * 100;
						$comp_divyield = $mry_data['sf1_divyield'] * 100;

                        if($comp_rate < $comp_divyield ) {
                            $score = 6;
                        }
                        else if($comp_divyield>1 && $comp_divyield <= $comp_rate) {
                            $score = 4;
                        }
                        else if($comp_divyield>0 && $comp_divyield<=1) {
                            $score = 2;
                        }
                        else {
                            $score = 0;
                        }

                        $memo = '(divyield : '.round($mry_data['sf1_divyield'],4).', interest_rate : '.$interest_rate.' : 6(interest_rate < divyield), 4(1 < divyield <= interest_rate), 2(0 < interest_rate <= 1), 0)';
                        $d_divyield = round($mry_data['sf1_divyield'],4);
                        break;
                    case 'payoutratio' :
                        if( $mry_data['sf1_payoutratio']*100 >30 ) {
                            $score = 4;
                        }
                        else if ( $mry_data['sf1_payoutratio']*100 > 20 && $mry_data['sf1_payoutratio']*100 <= 30 ) {
                            $score = 3;
                        }
                        else if ( $mry_data['sf1_payoutratio']*100 > 10 && $mry_data['sf1_payoutratio']*100 <= 20 ) {
                            $score = 2;
                        }
                        else if ( $mry_data['sf1_payoutratio']*100 > 0 && $mry_data['sf1_payoutratio']*100 <= 10 ) {
                            $score = 1;
                        }
                        else {
                            $score = 0;
                        }

                        //$score = $mry_data['sf1_payoutratio']*100 >= 30 ? 4 : 0;
                        $memo = '(payoutratio '.(round($mry_data['sf1_payoutratio']*100,2)).' : 4(payoutratio > 30), 3(20 < payoutratio <= 30), 2(10 < payoutratio <= 20), 1(0 < payoutratio <= 10), 0 )';
                        $d_poratio = round($mry_data['sf1_payoutratio']*100,2);
                        break;
                    case 'dps' :
                        $log = array();
                        $flag = true;
                        $cnt=1;
                        foreach($mry_list as $l) {
                            $log[] = round($l['sf1_dps'],2);
                            //echo $l['sf1_calendardate'].'<br>';
                            if($l['sf1_dps'] <= 0) {
                                $flag = false;
                            }
                            ${"d_dps".$cnt} = round($l['sf1_dps'],2);
                            ${"d_dps_year".$cnt} = $l['sf1_calendardate'];
                            $cnt++;
                        }

                        //echo '<pre>';
                        //print_r($log);
                        //$score = $flag ? 6 : 0;

                        if($mry_list[0]['sf1_dps']>0&&$mry_list[1]['sf1_dps']>0&&$mry_list[2]['sf1_dps']>0&&$mry_list[3]['sf1_dps']>0&&$mry_list[4]['sf1_dps']>0) {
                            $score = 6;
                        }
                        else if($mry_list[0]['sf1_dps']>0&&$mry_list[1]['sf1_dps']>0&&$mry_list[2]['sf1_dps']>0&&$mry_list[3]['sf1_dps']>0&&$mry_list[4]['sf1_dps']<=0) {
                            $score = 4;
                        }
                        else if($mry_list[0]['sf1_dps']>0&&$mry_list[1]['sf1_dps']>0&&$mry_list[2]['sf1_dps']>0&&$mry_list[3]['sf1_dps']<=0) {
                            $score = 2;
                        }
                        else {
                            $score = 0;
                        }
                        $memo = '(4 MRY dps : '.implode(',', $log).' 5년연속(6), 4년연속(4), 3년연속(2), 그외(0) )';
                        break;
                }
            }
/*
적용공식 [formula] : 숫자(1~6)
승수 [multiple] : 숫자, 0<승수
5적정주가 [5star_fairvalue] = [fairvlaue]*0.7, 소수점 두자리까지, 이하 버림.
4적정주가 [4star_fairvalue] = [fairvlaue]*0.85, 소수점 두자리까지, 이하 버림.
3적정주가 [fairvalue] : 정수, 소수점 이하 버림.
2적정주가 [2star_fairvalue] =  [fairvlaue]*1.176, 소수점 두자리까지, 이하 버림.
1적정주가 [1star_fairvalue] =  [fairvlaue]*1.43, 소수점 두자리까지, 이하 버림.
*/
            // 밸류에이션
            if($mri_item == 'valuation') {

                //$result['interest_rate'] = $interest_rate;
                $v_formual = '';
                $str_multiple = '';
                $tkr_valuation = '';

                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 1,
                            );
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    //$mry_list = $this->getFinStateList($ticker, 'MRY', $extra)->getData();
                    //$mry_list = $mry_list['findata'];
                    //$mry_data = $mry_list[0];

                    //tkr_category이 Domestic 일 경우
                    // 2020.05.27 주석처리 $tkr_adr = false;
                    if($tkr_adr === true) {
                        //(MRT) 20개 데이터 대신 (MRY) 10개 데이터로 계산
                        // ※ ADR 등은 MRT 데이터가 없어, MRY(연간) 데이터로 계산
                        $extra = array(
                                'limit' => 11,
                                );
                        $mrt_list = $this->getFinStateList($ticker, 'MRY', $extra)->getData();
                    } 
                    else {
                        $extra = array(
                                'limit' => 21,
                                );
                        $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    }

                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                    
                    echo $tdata['tkr_category'];
                    //echo '<pre>';
                    //print_r($mrt_list);

                    //PBR 평균
					$pbr_print = array();
                    $sum = 0;
                    $mrt_count = count($mrt_list);
					$pbr_count = 0;
                    foreach( $mrt_list as $a ) {
						if( $a['sf1_pb'] > 0 ) {
	                        $sum += round($a['sf1_pb'], 2);
							$pbr_count++;
						}
                    }
                    $pbr_avg = $sum / $pbr_count;
                    $pbr_avg = round($pbr_avg, 1);

                    //PBR 표준편차
					$pbr_count = 0;
                    for ($i=0,$s=0;$i<$mrt_count;$i++)  {

						if( $mrt_list[$i]['sf1_pb'] > 0 ) {
	                        $s+= ($mrt_list[$i]['sf1_pb']-$pbr_avg)*($mrt_list[$i]['sf1_pb']-$pbr_avg);
							$pbr_count++;
						}
					}

                    $pbr_dev= sprintf('%0.1f',sqrt($s/$pbr_count));

                    $pbr_cal = @( $pbr_dev / $pbr_avg ) * 100;
                    $pbr_cal = round($pbr_cal);

//echo '<br>평균 PBR==>'.$pbr_avg.'  표준편차==>'.$pbr_dev.'  편차/평균==>'.$pbr_cal;

                    //상단 PBR 
                    $pbr_up = $pbr_avg + $pbr_dev;
                    //하단 PBR 
                    $pbr_dn = $pbr_avg - $pbr_dev;


					$pbr_print[] = '[PBR]';
					$pbr_print[] = '평균 PBR = '.$pbr_avg.', 표준편차 = '.$pbr_dev.', 편차/평균 = '.$pbr_cal.', 범위 = '.$pbr_up.'~'.$pbr_dn;

                    $pbr_r_sum = 0;
                    for ($i=0,$j=0;$i<$mrt_count;$i++) {
						$bold = false;
						if($mrt_list[$i]['sf1_pb']>0) {
							$pbr_lst = round($mrt_list[$i]['sf1_pb'],2);

							if( $pbr_lst <= $pbr_up && $pbr_lst >= $pbr_dn ) {
								$pbr_r_sum += $pbr_lst;
								$bold = true;
								$j++;
							}
						}
						if($bold) {
							$pbr_print[] = '<b>'.$mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_pb'].'</b>';
						}
						else {
							$pbr_print[] = $mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_pb'];
						}
                    } 

                    $pbr_r_avg = round($pbr_r_sum/$j, 2);
                    //echo 'j===>'.$j.'sum==>'.$pbr_r_sum.'수정평균==>'.($pbr_r_sum/$j);

                    //echo '<br>수정평균PBR==>'.$pbr_r_avg;


                    //PER 평균
					$per_print = array();
                    $sum = 0;
					$per_count = 0;
                    foreach( $mrt_list as $a ) {
						if( $a['sf1_pe'] > 0 ) {
							$sum += round($a['sf1_pe'], 2);
							$per_count++;
						}
						
                    }
                    $per_avg = $sum / $per_count;
                    $per_avg = round($per_avg, 1);


                    //PER 표준편차
					$per_count = 0;
                    for ($i=0,$s=0;$i<$mrt_count;$i++) {
						if($mrt_list[$i]['sf1_pe'] > 0) {
	                        $s+= ($mrt_list[$i]['sf1_pe']-$per_avg)*($mrt_list[$i]['sf1_pe']-$per_avg);
							$per_count++;
						}
					}

                    $per_dev= sprintf('%0.1f',sqrt($s/$per_count));
        
                    $per_cal = @( $per_dev / $per_avg ) * 100;
                    $per_cal = round($per_cal);
                    //echo '<br>평균 PER==>'.$per_avg.'  표준편차==>'.$per_dev.'  편차/평균==>'.$per_cal;

                    //상단 PER 
                    $per_up = $per_avg + $per_dev;
                    //하단 PER 
                    $per_dn = $per_avg - $per_dev;

					$per_print[] = '[PER]';
					$per_print[] = '평균 PER = '.$per_avg.', 표준편차 = '.$per_dev.', 편차/평균 = '.$per_cal.', 범위 = '.$per_up.'~'.$per_dn;

                    $per_r_sum = 0;
                    //echo '<br>per_up==>'.$per_up;
                    //echo '<br>per_dn==>'.$per_dn;
                    for ($i=0,$j=0;$i<$mrt_count;$i++) {
						$bold = false;
						if($mrt_list[$i]['sf1_pe']>0) {
							$per_lst = round($mrt_list[$i]['sf1_pe'],2);
							//echo '<br>'.$per_lst;
							if( $per_lst <= $per_up && $per_lst >= $per_dn ) {
								if( $per_lst > 0 && $per_lst < 50 ) {
									$per_r_sum += $per_lst;
									$bold = true;
									$j++;
								}
							}
						}

						if($bold) {
							$per_print[] = '<b>'.$mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_pe'].'</b>';
						}
						else {
							$per_print[] = $mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_pe'];
						}
                    } 
                    $per_r_avg = @round($per_r_sum/$j, 2);
                    //echo 'j===>'.$j.'sum==>'.$per_r_sum.'수정평균==>'.($per_r_sum/$j);

                    //echo '<br>수정평균PER==>'.$per_r_avg;

                    //ROE 평균
					$roe_print = array();
                    $sum = 0;
                    //현재 ROE
                    $roe_now = round($mrt_data['sf1_roe'],2)*100;
					$roe_count = 0;
                    foreach( $mrt_list as $a ) {
						if($a['sf1_roe']>0) {
	                        $sum += round($a['sf1_roe'], 2) * 100;
							$roe_count++;
						}
                    }
                    $roe_avg = $sum / $roe_count;
                    $roe_avg = round($roe_avg, 1);

                    //ROE 표준편차
					$roe_count = 0;
                    for ($i=0,$s=0;$i<$mrt_count;$i++) {
						if($mrt_list[$i]['sf1_roe']>0){
	                        $s+= (round($mrt_list[$i]['sf1_roe'],2)*100-$roe_avg)*(round($mrt_list[$i]['sf1_roe'],2)*100-$roe_avg);
							$roe_count++;
						}
                    }
                    $roe_dev= sprintf('%0.1f',sqrt($s/$roe_count));
        
                    $roe_cal = @( $roe_dev / $roe_avg ) * 100;
                    $roe_cal = round($roe_cal);
                    //echo '<br>평균 ROE==>'.$roe_avg.'  표준편차==>'.$roe_dev.'  편차/평균==>'.$roe_cal;

                    //상단 ROE 
                    $roe_up = $roe_avg + $roe_dev;
                    //하단 ROE 
                    $roe_dn = $roe_avg - $roe_dev;
                    //echo '<br>현재 ROE==>'.$roe_now.' 하단 ROE==>'.$roe_dn.' 상단 ROE==>'.$roe_up;

                    //echo '<br>현재 BPS : '.$mrt_data['sf1_bvps'].'현재 EPS : '.$mrt_data['sf1_eps'];

					$roe_print[] = '[ROE]';
					$roe_print[] = '평균 ROE = '.$roe_avg.', 표준편차 = '.$roe_dev.', 편차/평균 = '.$roe_cal.', 범위 = '.$roe_up.'~'.$roe_dn;

                    $roe_r_sum = 0;
                    for ($i=0,$j=0;$i<$mrt_count;$i++) {
						$bold = false;
						if($mrt_list[$i]['sf1_roe']>0) {
							$roe_lst = round($mrt_list[$i]['sf1_roe'],2) * 100;
							if( $roe_lst <= $roe_up && $roe_lst >= $roe_dn ) {
								$roe_r_sum += $roe_lst;
								$bold = true;
								$j++;
							}
						}

						if($bold) {
							$roe_print[] = '<b>'.$mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_roe'].'</b>';
						}
						else {
							$roe_print[] = $mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_roe'];
						}

                    } 
                    $roe_r_avg = round($roe_r_sum/$j, 2);

                    //echo '<br>수정평균ROE==>'.$roe_r_avg;

//ev/ebitda : sf1_evebitda
//EBITDA이익률 : sf1_ebitdamargin
//ebitda : sf1_ebitda

                    //EV/EBITDA 평균
					$ev_print = array();
                    $sum = 0;
					$ev_count = 0;
                    foreach( $mrt_list as $a ) {
						if($a['sf1_evebitda']>0) {
	                        $sum += round($a['sf1_evebitda'], 2);
							$ev_count++;
						}
                    }
                    $evebitda_avg = $sum / $ev_count;
                    $evebitda_avg = round($evebitda_avg, 1);


                    //EV/EBITDA 표준편차
					$ev_count = 0;
                    for ($i=0,$s=0;$i<$mrt_count;$i++) {
						if($mrt_list[$i]['sf1_evebitda']>0) {
	                        $s+= ($mrt_list[$i]['sf1_evebitda']-$evebitda_avg)*($mrt_list[$i]['sf1_evebitda']-$evebitda_avg);
							$ev_count++;
						}
					}
                    $evebitda_dev= sprintf('%0.1f',sqrt($s/$ev_count));
        
                    $evebitda_cal = @( $evebitda_dev / $evebitda_avg ) * 100;
                    $evebitda_cal = round($evebitda_cal);
                    //echo '<br>평균 EV/EBITDA==>'.$evebitda_avg.'  표준편차==>'.$evebitda_dev.'  편차/평균==>'.$evebitda_cal;

                    //상단 EV/EBITDA 
                    $evebitda_up = $evebitda_avg + $evebitda_dev;
                    //하단 EV/EBITDA 
                    $evebitda_dn = $evebitda_avg - $evebitda_dev;

					$ev_print[] = '[EV/EBITDA]';
					$ev_print[] = '평균 EV/EBITDA = '.$evebitda_avg.', 표준편차 = '.$evebitda_dev.', 편차/평균 = '.$evebitda_cal.', 범위 = '.$evebitda_up.'~'.$evebitda_dn;

                    $evebitda_r_sum = 0;
                    for ($i=0,$j=0;$i<$mrt_count;$i++) {
						$bold = false;
						if($mrt_list[$i]['sf1_evebitda']>0) {
							$evebitda_lst = round($mrt_list[$i]['sf1_evebitda'],2);
							if( $evebitda_lst <= $evebitda_up && $evebitda_lst >= $evebitda_dn ) {
								$evebitda_r_sum += $evebitda_lst;
								$bold = true;
								$j++;
							}
						}

						if($bold) {
							$ev_print[] = '<b>'.$mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_evebitda'].'</b>';
						}
						else {
							$ev_print[] = $mrt_list[$i]['sf1_calendardate'].' : '.$mrt_list[$i]['sf1_evebitda'];
						}

                    } 
                    $evebitda_r_avg = round($evebitda_r_sum/$j, 2);
                    //echo 'j===>'.$j.'sum==>'.$pbr_r_sum.'수정평균==>'.($pbr_r_sum/$j);
                    //echo '<br>수정평균 EV/EBITDA==>'.$evebitda_r_avg;

                    //EBITDA이익률 평균(sf1_ebitdamargin)
                    $sum = 0;
					$ebitda_count = 0;
            
                    $ebitdamargin_now = round($mrt_data['sf1_ebitdamargin'],2)*100;
                    
                    foreach( $mrt_list as $a ) {
						if($a['sf1_ebitdamargin']>0) {
	                        $sum += round($a['sf1_ebitdamargin'], 2) * 100;
							$ebitda_count++;
						}
                    }
                    $ebitdamargin_avg = $sum / $ebitda_count;
                    $ebitdamargin_avg = round($ebitdamargin_avg, 1);


                    //EBITDA이익률 표준편차
					$ebitda_count = 0;
                    for ($i=0,$s=0;$i<$mrt_count;$i++) {
						if($mrt_list[$i]['sf1_ebitdamargin']>0) {
	                        $s+= (round($mrt_list[$i]['sf1_ebitdamargin'],2)*100-$ebitdamargin_avg)*(round($mrt_list[$i]['sf1_ebitdamargin'],2)*100-$ebitdamargin_avg);
							$ebitda_count++;
						}
					}
                    $ebitdamargin_dev= sprintf('%0.1f',sqrt($s/$ebitda_count));
        
                    $ebitdamargin_cal = @( $ebitdamargin_dev / $ebitdamargin_avg ) * 100;
                    $ebitdamargin_cal = round($ebitdamargin_cal);
                    //echo '<br>평균 EBITDA이익률==>'.$ebitdamargin_avg.'  표준편차==>'.$ebitdamargin_dev.'  편차/평균==>'.$ebitdamargin_cal;

                    //상단 EV/EBITDA 
                    $ebitdamargin_up = $ebitdamargin_avg + $ebitdamargin_dev;
                    //하단 EV/EBITDA 
                    $ebitdamargin_dn = $ebitdamargin_avg - $ebitdamargin_dev;

                    $ebitdamargin_r_sum = 0;
                    for ($i=0,$j=0;$i<$mrt_count;$i++) {
						if($mrt_list[$i]['sf1_ebitdamargin']>0) {
							$ebitdamargin_lst = round($mrt_list[$i]['sf1_ebitdamargin'],2)*100;
							if( $ebitdamargin_lst <= $ebitdamargin_up && $ebitdamargin_lst >= $ebitdamargin_dn ) {
								$ebitdamargin_r_sum += $ebitdamargin_lst;
								$j++;
							}
						}
                    } 
                    $ebitdamargin_r_avg = @round(($ebitdamargin_r_sum/$j)/100, 2);
                    //echo 'j===>'.$j.'sum==>'.$pbr_r_sum.'수정평균==>'.($pbr_r_sum/$j);
                    //echo '<br>수정평균 EBITDA이익률==>'.$ebitdamargin_r_avg;
/* 
추후 함수로
function array_avg( &$arr )
{
    $sum = 0;
    foreach( $arr as $a )
        $sum += $a;
    return $sum / count($arr);
}

function array_dev( &$arr, $avg = NULL )
{
    if( $avg == NULL ) $avg = array_avg($arr);

    $dev = 0;
    foreach( $arr as $a )
        $dev += pow(($a - $avg),2);
    return sqrt($dev);
}

$array = array(1,2,3,4,100,200); // ① 값들
$sum = array_sum($array); // ② 합계
$cnt = count($array); // 배열 크기
$avg = sprintf('%0.4f',$sum/$cnt); // ③ 평균
for ($i=0,$s=0;$i<$cnt;$i++) $s+= ($array[$i]-$avg)*($array[$i]-$avg);
$std = sprintf('%0.4f',sqrt($s/$cnt)); // ④ 표준편차

echo '값들 : '.implode(', ',$array).'<br />'."\n";
echo '합계 : '.$sum.'<br />'."\n";
echo '평균 : '.$avg.'<br />'."\n";
echo '표준편차 : '.$std.'<br />'."\n";

출력 결과
값들 : 1, 2, 3, 4, 100, 200
합계 : 310
평균 : 51.6667
표준편차 : 75.2920
*/
                    /* 예전 자료 */
                    //$ticker_6yroe_map = $this->sf1_tb_model->getTickerWeight6YRoeMap();

                    //$daily_last_row = $this->getTickerDailyLastRow($ticker);

                    $price_map = $this->sep_tb_model->getTickersPrice(array($ticker));
					//echo '<pre>'; print_r($price_map);
                    $last_price = floatval(str_replace(',','',$price_map[$ticker]['close']));
					echo '<br>현재가 ===> '.$last_price;                    

                    $value_result = array(
                        'growth' => array(),
                        'safety' => array(),
                        'moat' => array(),
                    );

                    foreach($value_result as $k => &$v) {
                        $v = $this->getSpiderVData($ticker, $k);
                        //$v = $this->getSpiderData($ticker, $k);
                    }

                    //echo $value_result['safety']['total_score'].'<br>';
                    //echo $value_result['moat']['total_score'].'<br>';
                    //echo $value_result['growth']['total_score'].'<br>';
					//echo 'sf1_opinc===>'.$mrt_data['sf1_opinc'];
                    if($mrt_data['sf1_opinc'] > 20000000 && $value_result['safety']['total_score'] >= 8 &&  $value_result['moat']['total_score'] >= 4 &&  $value_result['growth']['total_score'] >= 4) {
                        echo '<br>sector : '.$tdata['tkr_sector'];    
                        echo ', industry : '.$tdata['tkr_industry'];  
						
						/* 2020.05.26추가 */
						if($tkr_adr_value===true) {
							$value_sf1_opinc = $mrt_data['sf1_opinc']/$mrt_data['sf1_fxusd'];
						}
						else {
							$value_sf1_opinc = $mrt_data['sf1_opinc'];
						}

                        if($tdata['tkr_sector'] == 'Financial Services' && $tdata['tkr_industry'] != 'Financial Data & Stock Exchanges') {
                            echo '<br>(PBR 표준편차 / PBR 평균 > 50%) : '.$pbr_cal.' > 50 ';
                            
							
							if($pbr_cal> 50) {

								/* 2020.4.22 수정 */
								/*
								if. 10.00달러 <=, 적정주가 표시 rounddown 0 (소수점 이하 버림)
								if, 1.00달러 <= 현재가 < 10.00달러, 적정주가 표시 roundup 1 (소수점 한자리 반올림)

								if, 1.00달러 > 현재가, 적정주가 표시 roundup 2 (소수점 두자리 반올림)
								적정주가외 매우저평가, 저평가, 고평가, 매우 고평가 가격은 모두 소수점 두자리까지 현재처럼 표시
								
								10.00달러 이상일때 적정주가 표시는 기존과 동일
								*/
/*
								if( 10 <= $last_price ) {
									$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg);
								}
								else if( 1 <= $last_price && $last_price <10 ) {
									$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg, 1);
								}
								else {
									$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg, 2);
								}	
*/
								$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg);
                                $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                echo '<br><br>밸류에이션 결과1 ==> Formula-1 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.'] ) == '.$tkr_valuation;
								echo '<pre>'; print_r($pbr_print);
                                $v_formual = '1';
                                //현재 BPS $mrt_data['sf1_bvps']
                                //수정 평균 PBR : $pbr_r_avg
                            }
                            else {
                                echo '<br>하단ROE('.$roe_dn.')<현재ROE('.$roe_now.')<상단ROE('.$roe_up.')';
                                if($roe_now>$roe_dn && $roe_now<$roe_up) {

									/* 2020.4.22 수정 */
/*
									if( 10 <= $last_price ) {
										$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg);
									}
									else if( 1 <= $last_price && $last_price <10 ) {
										$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg, 1);
									}
									else {
										$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg, 2);
									}	
*/
									$tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg);
                                    $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);

                                    echo '<br><br>밸류에이션 결과2 ==> Formula-1 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.'] ) == '.$tkr_valuation;
									echo '<pre>'; print_r($pbr_print);
                                    $v_formual = '1';
                                }
                                else {
                                    if($roe_now>0 && $roe_r_avg>0) {

                                        $str_cal_multiple = round($roe_now/$roe_r_avg,6);
                                        //$str_multiple = $this->_cal_multiple($mrt_data['sf1_opinc'], $str_cal_multiple);
                                        $str_multiple = $this->_cal_multiple($value_sf1_opinc, $str_cal_multiple);
                                        
                                        $str_max = '';
                                        if($str_cal_multiple!=$str_multiple) {
                                            $str_max = '(MAX)';
                                        }

										/* 2020.4.22 수정 */
/*
										if( 10 <= $last_price ) {
	                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple);
										}
										else if( 1 <= $last_price && $last_price <10 ) {
	                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple, 1);
										}
										else {
	                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple, 2);
										}	
*/
                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple);
                                        $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                        echo '<br><br>밸류에이션 결과 ==> Formula-2 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.'] * (현재ROE['.$roe_now.']/수정평균ROE['.$roe_r_avg.'])) == '.$tkr_valuation.' 승수'.$str_max.' : '.$str_multiple;
										echo '<pre>'; print_r($pbr_print); print_r($roe_print);
                                        $v_formual = '2';
                                    }
                                    else {

										/* 2020.4.22 수정 */
/*
										if( 10 <= $last_price ) {
	                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg);
										}
										else if( 1 <= $last_price && $last_price <10 ) {
	                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg, 1);
										}
										else {
	                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg, 2);
										}	
*/

                                        $tkr_valuation = round($mrt_data['sf1_bvps']*$pbr_r_avg);
                                        $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                        echo '<br><br>밸류에이션 결과 ==> Formula-2 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.']) == '.$tkr_valuation.'(현재ROE['.$roe_now.'], 수정평균ROE['.$roe_r_avg.'])';
										echo '<pre>'; print_r($pbr_print); print_r($roe_print);
                                        $v_formual = '2';
                                    }
                                }
                            }
                        }
                        else { //Sector가 Financial Services가 아닌경우
                            // 0<평균PER<50 and (PER표준편차/PER평균)<50
                            //echo '<br>평균PER(<50) : '.$per_avg;
                            //echo '<br>PER표준편차('.$per_dev.')/PER평균('.$per_avg.') < 50 : '.$per_cal;
                            echo '<br>0<평균PER('.$per_avg.')<50 and (0%<PER표준편차/PER평균) '.$per_cal.' <50%';
                            if($per_avg > 0 && $per_avg < 50 && $per_cal > 0 && $per_cal < 50 ){
                                // (최근 MRT) 영업이익 > 순이익 
                                echo '<br>영업이익 : '.number_format($mrt_data['sf1_opinc']).'> 순이익 : '.number_format($mrt_data['sf1_netinc']);
                                if( $mrt_data['sf1_opinc'] > $mrt_data['sf1_netinc']) {
                                    echo '<br>하단ROE('.$roe_dn.') < 현재ROE('.$roe_now.') < 상단ROE('.$roe_up.')';
                                    if( $tkr_adr_value === true ) {
                                        $str_sf1_epsdil = $mrt_data['sf1_epsusd'];
                                    }
                                    else {
                                        $str_sf1_epsdil = $mrt_data['sf1_epsdil'];
                                    }
                                    
                                    if($roe_now>$roe_dn && $roe_now<$roe_up ) { 

										/* 2020.4.22 수정 */
/*
										if( 10 <= $last_price ) {
	                                        $tkr_valuation = round($str_sf1_epsdil*$per_r_avg);
										}
										else if( 1 <= $last_price && $last_price <10 ) {
	                                        $tkr_valuation = round($str_sf1_epsdil*$per_r_avg, 1);
										}
										else {
	                                        $tkr_valuation = round($str_sf1_epsdil*$per_r_avg, 2);
										}	
*/

                                        $tkr_valuation = round($str_sf1_epsdil*$per_r_avg);
                                        $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                        echo '<br><br>밸류에이션 결과 ==> Formula-3 (현재 EPS ['.$str_sf1_epsdil.'] * 수정 평균 PER ['.$per_r_avg.'] ) == '.$tkr_valuation;
										echo '<pre>'; print_r($per_print);
                                        $v_formual = '3';
                                    }
                                    else {
                                        if($roe_now>0 && $roe_r_avg>0) {

                                            $str_cal_multiple = round($roe_now/$roe_r_avg,2);
                                            $str_multiple = $this->_cal_multiple($value_sf1_opinc, $str_cal_multiple);
                                            //$str_multiple = $this->_cal_multiple($mrt_data['sf1_opinc'], $str_cal_multiple);
                                            $str_max = '';
                                            if($str_cal_multiple!=$str_multiple) {
                                                $str_max = '(MAX)';
                                            }

											/* 2020.4.22 수정 */
/*
											if( 10 <= $last_price ) {
												$tkr_valuation = round($str_sf1_epsdil*$per_r_avg*$str_multiple);
											}
											else if( 1 <= $last_price && $last_price <10 ) {
												$tkr_valuation = round($str_sf1_epsdil*$per_r_avg*$str_multiple, 1);
											}
											else {
												$tkr_valuation = round($str_sf1_epsdil*$per_r_avg*$str_multiple, 2);
											}	
*/

											$tkr_valuation = round($str_sf1_epsdil*$per_r_avg*$str_multiple);
                                            $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);

                                            echo '<br><br>밸류에이션 결과 ==> Formula-4 (현재 EPS ['.$str_sf1_epsdil.'] * 수정 평균 PER ['.$per_r_avg.'] * (현재ROE ['.$roe_now.'] / 수정평균ROE ['.$roe_r_avg.']) ) == '.$tkr_valuation.' 승수'.$str_max.' : '.$str_multiple;
											echo '<pre>'; print_r($per_print); print_r($roe_print);
                                            $v_formual = '4';
                                        }
                                        else {
											/* 2020.4.22 수정 */
/*
											if( 10 <= $last_price ) {
												$tkr_valuation = round($str_sf1_epsdil*$per_r_avg);
											}
											else if( 1 <= $last_price && $last_price <10 ) {
												$tkr_valuation = round($str_sf1_epsdil*$per_r_avg, 1);
											}
											else {
												$tkr_valuation = round($str_sf1_epsdil*$per_r_avg, 2);
											}	
*/
											$tkr_valuation = round($str_sf1_epsdil*$per_r_avg);
                                            $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                            echo '<br><br>밸류에이션 결과 ==> Formula-4 (현재 EPS ['.$str_sf1_epsdil.'] * 수정 평균 PER ['.$per_r_avg.'] == '.$tkr_valuation.' (현재ROE ['.$roe_now.'], 수정평균ROE ['.$roe_r_avg.']) )';
											echo '<pre>'; print_r($per_print); print_r($roe_print);
                                            $v_formual = '4';
                                        }
                                    }
                                }
                                else {
                                    //EPS -> OPS로 대체 (=opinc/shareswadil)
                                    //ROE($roe_now) -> OPOE($opoe_now)로 대체 (=opinc/equity)
									if($tkr_adr_value === true) {
										$sf1_opinc = 'sf1_opinc';
										//$sf1_opinc = 'sf1_opinc_ori';
										//$sf1_ebitda = 'sf1_ebitda_ori';
										//$sf1_netinc = 'sf1_netinc_ori';
										$ops_now = $mrt_data['sf1_opinc_ori']/$mrt_data['sf1_netinccmn']*$mrt_data['sf1_epsusd'];
										$opoe_now = $mrt_data['sf1_opinc_ori']/$mrt_data['sf1_equityavg']*100;
									//echo '<br>sf1_opinc===>'.$mry_data['sf1_opinc_ori'];
									//echo '<br>sf1_netinccmn===>'.$mry_data['sf1_netinccmn'];
									//echo '<br>sf1_epsusd===>'.$mry_data['sf1_epsusd'];
										echo '<br>ADR:EPS -> OPS로 대체 (=opinc('.number_format($mrt_data['sf1_opinc_ori']).')/netinccmn('.number_format($mrt_data['sf1_netinccmn']).')*Epsusd'.$mrt_data['sf1_epsusd'].') : '.$ops_now;
										echo '<br>ADR:ROE -> OPOE로 대체 (=opinc('.number_format($mrt_data['sf1_opinc_ori']).')/equityav('.number_format($mrt_data['sf1_equityavg']).')*100) : '.$opoe_now;
									}
									else {
										$sf1_opinc = 'sf1_opinc';

										$ops_now = $mrt_data['sf1_opinc']/$mrt_data['sf1_shareswadil'];
										$opoe_now = $mrt_data['sf1_opinc_ori']/$mrt_data['sf1_equityavg']*100;
									
										echo '<br>EPS -> OPS로 대체 (=opinc('.number_format($mrt_data['sf1_opinc']).')/shareswadil('.number_format($mrt_data['sf1_shareswadil']).')) : '.$ops_now;
										echo '<br>ROE -> OPOE로 대체 (=opinc('.number_format($mrt_data['sf1_opinc']).')/equity('.number_format($mrt_data['sf1_equity']).')*100) : '.$opoe_now;
									}


                                    if($opoe_now>$roe_dn && $opoe_now<$roe_up ) { //하단ROE < OPOE대체 < 상단ROE

										/* 2020.4.22 수정 */
/*
										if( 10 <= $last_price ) {
			                                $tkr_valuation = round($ops_now*$per_r_avg);
										}
										else if( 1 <= $last_price && $last_price <10 ) {
	                                        $tkr_valuation = round($ops_now*$per_r_avg, 1);
										}
										else {
		                                    $tkr_valuation = round($ops_now*$per_r_avg, 2);
										}	
*/
		                                $tkr_valuation = round($ops_now*$per_r_avg);
                                        $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                        echo '<br><br>밸류에이션 결과 ==> Formula-3 (OPS대체 ['.$ops_now.'] * 수정 평균 PER ['.$per_r_avg.'] ) == '.$tkr_valuation;
										echo '<pre>'; print_r($per_print);
                                        $v_formual = '3';
                                    }
                                    else {
                                        if($opoe_now>0 && $roe_r_avg>0) {

                                            $str_cal_multiple = round($roe_now/$roe_r_avg,2);
                                            $str_multiple = $this->_cal_multiple($value_sf1_opinc, $str_cal_multiple);
                                           // $str_multiple = $this->_cal_multiple($mrt_data['sf1_opinc'], $str_cal_multiple);

                                            $str_max = '';
                                            if($str_cal_multiple!=$str_multiple) {
                                                $str_max = '(MAX)';
                                            }

											/* 2020.4.22 수정 */
/*
											if( 10 <= $last_price ) {
												$tkr_valuation = round($ops_now*$per_r_avg*$str_multiple);
											}
											else if( 1 <= $last_price && $last_price <10 ) {
												$tkr_valuation = round($ops_now*$per_r_avg*$str_multiple, 1);
											}
											else {
												$tkr_valuation = round($ops_now*$per_r_avg*$str_multiple, 2);
											}	
*/
											$tkr_valuation = round($ops_now*$per_r_avg*$str_multiple);
                                            $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                            echo '<br><br>밸류에이션 결과 ==> Formula-4 (OPS대체 ['.$ops_now.'] * 수정 평균 PER ['.$per_r_avg.'] * (OPOE대체 ['.$opoe_now.'] / 수정평균ROE ['.$roe_r_avg.']) ) == '.$tkr_valuation.' 승수'.$str_max.' : '.$str_multiple;
											echo '<pre>'; print_r($per_print); print_r($roe_print);
                                            $v_formual = '4';
                                        }
                                        else {
											/* 2020.4.22 수정 */
/*
											if( 10 <= $last_price ) {
												$tkr_valuation = round($ops_now*$per_r_avg);
											}
											else if( 1 <= $last_price && $last_price <10 ) {
												$tkr_valuation = round($ops_now*$per_r_avg, 1);
											}
											else {
												$tkr_valuation = round($ops_now*$per_r_avg, 2);
											}	
*/
											$tkr_valuation = round($ops_now*$per_r_avg);
                                            $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                            echo '<br><br>밸류에이션 결과 ==> Formula-4 (OPS대체 ['.$ops_now.'] * 수정 평균 PER ['.$per_r_avg.'] ) == '.$tkr_valuation.'(OPOE대체 ['.$opoe_now.'] / 수정평균ROE ['.$roe_r_avg.'])';
											echo '<pre>'; print_r($per_print); print_r($roe_print);
                                            $v_formual = '4';
                                        }
                                    }
                                }
                            }
                            else{ 
                                // 평균EV/EBITDA < 50 and (EV/EBITDA 표준편차/ EV/EBITDA 평균) < 50%
                                echo '<br>0 < 평균EV/EBITDA('.$evebitda_avg.') < 50 and ( 0% <EV/EBITDA 표준편차/ EV/EBITDA 평균) '.$evebitda_cal.' < 50%';
                                if($evebitda_avg > 0 && $evebitda_avg < 50 && $evebitda_cal > 0 && $evebitda_cal < 50) {
                                    echo '<br>하단 EBITDA 이익률('.$ebitdamargin_dn.') < 현재 EBITDA 이익률('.$ebitdamargin_now.') < 상단 EBITDA 이익률('.$ebitdamargin_up.')';
                                    if($ebitdamargin_now > $ebitdamargin_dn && $ebitdamargin_now < $ebitdamargin_up) {

										/* 2020.4.22 수정 */
/*
										if( 10 <= $last_price ) {
											$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg));
										}
										else if( 1 <= $last_price && $last_price <10 ) {
											$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg), 1);
										}
										else {
											$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg), 2);
										}	
*/
										$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg));
                                        $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                        echo '<br><br>밸류에이션 결과 ==> Formula-5 (현재 주당 EBITDA ['.round($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil'],2).'] * 수정평균 EV/EBITDA ['.$evebitda_r_avg.'] ) == '.$tkr_valuation;
										echo '<pre>'; print_r($ev_print);
                                        $v_formual = '5';
                                    }
                                    else {
                                        //주식수 $mrt_data['sf1_shareswadil']
                                        if($mrt_data['sf1_ebitdamargin']>0 && $ebitdamargin_r_avg>0) {

                                            $str_cal_multiple = round($mrt_data['sf1_ebitdamargin']/$ebitdamargin_r_avg,2);
                                            $str_multiple = $this->_cal_multiple($value_sf1_opinc, $str_cal_multiple);
                                            //$str_multiple = $this->_cal_multiple($mrt_data['sf1_opinc'], $str_cal_multiple);
                                            
                                            $str_max = '';
                                            if($str_cal_multiple!=$str_multiple) {
                                                $str_max = '(MAX)';
                                            }

											/* 2020.4.22 수정 */
/*
											if( 10 <= $last_price ) {
												$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg*$str_multiple));
											}
											else if( 1 <= $last_price && $last_price <10 ) {
												$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg*$str_multiple), 1);
											}
											else {
												$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg*$str_multiple), 2);
											}	
*/
											$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg*$str_multiple));
                                            $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);

											if($tkr_adr_value === true) {
												echo '<br><br>밸류에이션 결과 ==> Formula-6 (현재 주당 EBITDA ['.round($mrt_data['sf1_ebitda_ori']/$mrt_data['sf1_netinccmn']*$mrt_data['sf1_epsusd'],2).'] * 수정 평균 EV/EBITDA ['.$evebitda_r_avg.'] * (현재EBITDA 이익률 ['.$mrt_data['sf1_ebitdamargin'].'] /수정평균EBITDA 이익률 ['.$ebitdamargin_r_avg.'] ) ) == '.$tkr_valuation.' 승수'.$str_max.' : '.$str_multiple;
											}
											else {
												echo '<br><br>밸류에이션 결과 ==> Formula-6 (현재 주당 EBITDA ['.round($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil'],2).'] * 수정 평균 EV/EBITDA ['.$evebitda_r_avg.'] * (현재EBITDA 이익률 ['.$mrt_data['sf1_ebitdamargin'].'] /수정평균EBITDA 이익률 ['.$ebitdamargin_r_avg.'] ) ) == '.$tkr_valuation.' 승수'.$str_max.' : '.$str_multiple;
											}


											echo '<pre>'; print_r($ev_print);
                                            $v_formual = '6';
                                        }
                                        else {
											/* 2020.4.22 수정 */
/*
											if( 10 <= $last_price ) {
												$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg));
											}
											else if( 1 <= $last_price && $last_price <10 ) {
												$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg), 1);
											}
											else {
												$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg), 2);
											}	
*/
											$tkr_valuation = round(($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']*$evebitda_r_avg));
                                            $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);

											if($tkr_adr_value === true) {
												echo '<br><br>밸류에이션 결과... ==> Formula-6 (현재 주당 EBITDA ['.($mrt_data['sf1_ebitda_ori']/$mrt_data['sf1_netinccmn']*$mrt_data['sf1_epsusd']).'] * 수정 평균 EV/EBITDA ['.$evebitda_r_avg.']  ) == '.$tkr_valuation.' (현재EBITDA 이익률 ['.$mrt_data['sf1_ebitdamargin'].'], 수정평균EBITDA 이익률 ['.$ebitdamargin_r_avg.'] )';
											}
											else {
												echo '<br><br>밸류에이션 결과... ==> Formula-6 (현재 주당 EBITDA ['.($mrt_data['sf1_ebitda']/$mrt_data['sf1_shareswadil']).'] * 수정 평균 EV/EBITDA ['.$evebitda_r_avg.']  ) == '.$tkr_valuation.' (현재EBITDA 이익률 ['.$mrt_data['sf1_ebitdamargin'].'], 수정평균EBITDA 이익률 ['.$ebitdamargin_r_avg.'] )';
											}

											echo '<pre>'; print_r($ev_print);
                                            $v_formual = '6';
                                        }
                                    }
                                }
                                else {
                                    echo '<br>(PBR 표준편차 / PBR 평균 > 50%) : '.$pbr_cal.' > 50 ';
                                    if($pbr_cal> 50) {

										/* 2020.4.22 수정 */
/*
										if( 10 <= $last_price ) {
											$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg));
										}
										else if( 1 <= $last_price && $last_price <10 ) {
											$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg), 1);
										}
										else {
											$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg), 2);
										}	
*/
										$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg));
                                        $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                        echo '<br>밸류에이션 결과3 ==> Formula-1 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.'] ) == '.$tkr_valuation;
										echo '<pre>'; print_r($pbr_print);
                                        $v_formual = '1';
                                        //현재 BPS $mrt_data['sf1_bvps']
                                        //수정 평균 PBR : $pbr_r_avg
                                    }
                                    else {
                                        //하단ROE<현재ROE<상단ROE
                                        echo '<br>하단ROE('.$roe_dn.')<현재ROE('.$roe_now.')<상단ROE('.$roe_up.')';
                                        if($roe_now>$roe_dn && $roe_now<$roe_up) {

											/* 2020.4.22 수정 */
/*
											if( 10 <= $last_price ) {
												$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg));
											}
											else if( 1 <= $last_price && $last_price <10 ) {
												$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg), 1);
											}
											else {
												$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg), 2);
											}	
*/
											$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg));
                                            $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                            echo '<br><br>밸류에이션 결과4 ==> Formula-1 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.'] ) == '.$tkr_valuation;
											echo '<pre>'; print_r($pbr_print);
                                            $v_formual = '1';
                                        }
                                        else {
                                            if($roe_now>0 && $roe_r_avg>0) {

                                                $str_cal_multiple = round($roe_now/$roe_r_avg,2);
                                                $str_multiple = $this->_cal_multiple($value_sf1_opinc, $str_cal_multiple);
                                                //$str_multiple = $this->_cal_multiple($mrt_data['sf1_opinc'], $str_cal_multiple);

                                                $str_max = '';
                                                if($str_cal_multiple!=$str_multiple) {
                                                    $str_max = '(MAX)';
                                                }

												/* 2020.4.22 수정 */
/*
												if( 10 <= $last_price ) {
													$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple));
												}
												else if( 1 <= $last_price && $last_price <10 ) {
													$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple), 1);
												}
												else {
													$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple), 2);
												}	
*/
												$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg*$str_multiple));
                                                $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                                echo '<br><br>밸류에이션 결과 ==> Formula-2 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.'] * (현재ROE['.$roe_now.']/수정평균ROE['.$roe_r_avg.'])) == '.$tkr_valuation.' 승수'.$str_max.' : '.$str_multiple;
												echo '<pre>'; print_r($pbr_print); print_r($roe_print);
                                                $v_formual = '2';
                                            }
                                            else {
												/* 2020.4.22 수정 */
/*
												if( 10 <= $last_price ) {
													$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg));
												}
												else if( 1 <= $last_price && $last_price <10 ) {
													$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg), 1);
												}
												else {
													$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg), 2);
												}	
*/
												$tkr_valuation = round(($mrt_data['sf1_bvps']*$pbr_r_avg));
                                                $tkr_valuation = $this->_cal_valuation($tkr_valuation, $last_price);
                                                echo '<br><br>밸류에이션 결과 ==> Formula-2 (현재 BPS ['.$mrt_data['sf1_bvps'].'] * 수정 평균 PBR ['.$pbr_r_avg.']) == '.$tkr_valuation.'(현재ROE['.$roe_now.'], 수정평균ROE['.$roe_r_avg.'])';                                            
												echo '<pre>'; print_r($pbr_print); print_r($roe_print);
                                                $v_formual = '2';
                                            }
                                        }
                                    }
                                }                                
                            }
                        }
                        //echo '<br>현재가 : '.$last_price;
                    }
                    else {
                        echo '<br>영업이익 : '.$mrt_data['sf1_opinc'];
                        echo '<br>safety(>=8) : '.$value_result['safety']['total_score'];
                        echo '<br>moat(>=4) : '.$value_result['moat']['total_score'];
                        echo '<br>growth(>=4) : '.$value_result['growth']['total_score'];
                        echo '<br><br>밸류에이션 결과 ==> 별점 Star Score = 0점';
                        $score = 0;
                        $v_formual = '0';
                    }
                }
            }

            $total_score += $score;

            $result[$item_detail] = $score.$memo;
        }

		//echo $mrt_data['sf1_opinc'].'<br>';
//2020.05.26추가

//echo 'totoal_score==>'.$total_score;
		//최근 MRT opnic > 1억달러
		/*
		if($total_score>=75 && )
			echo '[[[높음]]]';
		*/

        //echo $total_score.'<br>';
        //echo '<pre>';
        //print_r($mri_item);
        $result['total_score'] = $total_score;
        $result['stars'] = sprintf('%.1f', $total_score / 4);
        $result['clsname'] = $tkr_clsname;
        
        if($mri_item == 'growth') {
            $result['g_roe'] = $g_roe;
            $result['g_epsgr'] = $g_epsgr;
			$result['valuation_op_inc'] = $valuation_op_inc;
        }
        else if($mri_item == 'safety') {
            $result['s_bis'] = $s_bis;
            $result['s_crratio'] = $s_crratio;
            $result['s_debtratio'] = $s_debtratio;
            $result['s_intcoverage'] = $s_intcoverage;
            $result['s_boingratio'] = $s_boingratio;
            $result['s_fincost'] = $s_fincost;
        }
        else if($mri_item == 'cashflow') {
            $result['c_pcr'] = $c_pcr;
            $result['c_cashflow'] = $c_cashflow;
            $result['c_ffrevenue'] = $c_ffrevenue;
        }
        else if($mri_item == 'moat') {
            $result['m_m_roe'] = $m_roe;
            $result['m_assetsgr'] = $m_assetsgr;
            $result['m_opmargin'] = $m_opmargin;
            $result['m_revenuegr'] = $m_revenuegr;
        }
        else if($mri_item == 'dividend') {
            $result['d_epsgr2'] = $d_epsgr2;
            $result['d_fcfgr'] = $d_fcfgr;
            $result['d_divyield'] = $d_divyield;
            $result['d_poratio'] = $d_poratio;
            $result['d_dps_year1'] = $d_dps_year1;
            $result['d_dps_year2'] = $d_dps_year2;
            $result['d_dps_year3'] = $d_dps_year3;
            $result['d_dps_year4'] = $d_dps_year4;
            $result['d_dps_year5'] = $d_dps_year5;
            $result['d_dps1'] = $d_dps1;
            $result['d_dps2'] = $d_dps2;
            $result['d_dps3'] = $d_dps3;
            $result['d_dps4'] = $d_dps4;
            $result['d_dps5'] = $d_dps5;
        }
        else if($mri_item == 'valuation') {
            $result['formula'] = $v_formual;
            if($str_multiple>0) {
                $result['multiple'] = $str_multiple;
            }
            else {
                $result['multiple'] = 'N/A';
            }
            $result['fairvalue5'] = @round($tkr_valuation*0.7, 2);
            $result['fairvalue4'] = @round($tkr_valuation*0.85, 2);
            $result['fairvalue3'] = $tkr_valuation;
            $result['fairvalue2'] = @round($tkr_valuation*1.176, 2);
            $result['fairvalue1'] = @round($tkr_valuation*1.43, 2);
        }

		return $result;
    }

    function _cal_valuation($tkr_valuation, $last_price) {
		//cho '<br>tkr_valuation==>'.$tkr_valuation;
		//echo '<br>last_price==>'.$last_price;
        if( $tkr_valuation == 0 ) {
            $tkr_valuation = 'N/A';
        }
        else {
            $tkr_diff_valuation = (($tkr_valuation/$last_price) - 1) * 100; 
			//echo '<br>tkr_diff_valuation==>'.$tkr_diff_valuation;
            if($tkr_diff_valuation>50) {

				if( 10 <= $last_price ) {
					$tkr_valuation = round($last_price * 1.5);
				}
				else if( 1 <= $last_price && $last_price <10 ) {
					$tkr_valuation = round($last_price * 1.5, 1);
				}
				else {
					$tkr_valuation = round($last_price * 1.5, 2);
				}	

                echo '<br>(현재가*1.5)';
            }
			else if($tkr_diff_valuation<-50) {

				if( 10 <= $last_price ) {
					$tkr_valuation = round($last_price * 0.5);
				}
				else if( 1 <= $last_price && $last_price <10 ) {
					$tkr_valuation = round($last_price * 0.5, 1);
				}
				else {
					$tkr_valuation = round($last_price * 0.5, 2);
				}	

				echo '<br>(괴리율최저치 -50)';
			}
        }

        return $tkr_valuation;
    }

    function _cal_multiple($opinc, $str_multiple) {
        
		$str_ret_multiple = $str_multiple;

		if($str_multiple<1) {

			if($opinc >= 10000000000) {
				//$str_ret_multiple = $str_multiple;
				if($str_ret_multiple<0.95) $str_ret_multiple = 0.95;
		   }
			else if($opinc>=5000000000 && $opinc<10000000000 ) {
				if($str_ret_multiple<0.9) $str_ret_multiple = 0.9;
			}
			else if($opinc>=1000000000 && $opinc<5000000000 ) {
				if($str_ret_multiple<0.85) $str_ret_multiple = 0.85;
			}
			else if($opinc>=500000000 && $opinc<1000000000 ) {
				if($str_ret_multiple<0.8) $str_ret_multiple = 0.8;
			}
			else if($opinc>=100000000 && $opinc<500000000 ) {
				if($str_ret_multiple<0.7) $str_ret_multiple = 0.7;
			}
			else {
				/*
				if( $str_multiple > 1.1) {
					$str_ret_multiple = 1.1;
				}
				*/
			}		
		}
		else {		

			if($opinc >= 10000000000) {
				$str_ret_multiple = $str_multiple;
			}
			else if($opinc>=5000000000 && $opinc<10000000000 ) {
				if( $str_multiple > 2) {
					$str_ret_multiple = 2;
				}
			}
			else if($opinc>=1000000000 && $opinc<5000000000 ) {
				if( $str_multiple > 1.5) {
					$str_ret_multiple = 1.5;
				}
			}
			else if($opinc>=500000000 && $opinc<1000000000 ) {
				if( $str_multiple > 1.3) {
					$str_ret_multiple = 1.3;
				}
			}
			else if($opinc>=100000000 && $opinc<500000000 ) {
				if( $str_multiple > 1.2) {
					$str_ret_multiple = 1.2;
				}
			}
			else {
				if( $str_multiple > 1.1) {
					$str_ret_multiple = 1.1;
				}
			}
		}

        return $str_ret_multiple;    
    }



    private function getSpiderData($ticker, $mri_item) {
        $ticker = strtoupper($ticker);
        $mri_item = strtolower($mri_item);
        
        if( ! $this->ticker_tb_model->get(array(
            'tkr_table' => 'SEP', 
            'tkr_ticker' => $ticker, 
            'tkr_isdelisted' => 'N' 
            ))->isSuccess()) {
            echo '티커 코드를 확인하세요.';
            return;
        }
        $tdata = $this->ticker_tb_model->getData();
        
        $tkr_fin = false;    //금융업
        $tkr_bank = false;    //은행업
        $tkr_insu = false;    //보험업
        $tkr_cscm = false;    //Credit Services, Capital Markets
        $tkr_adr = true;    //ADR
        $tkr_clsname = '';

        //if( $tdata['tkr_category'] == 'Domestic' || $tdata['tkr_category'] == 'Domestic Primary' || $tdata['tkr_category'] == 'Domestic Common Stock' ) { 2020.08.26수정
        if( strstr($tdata['tkr_category'], 'Domestic') ) {
            $tkr_adr = false;    //ADR
        }

        if($tdata['tkr_sector']=='Financial Services') {

            if( $tdata['tkr_industry'] == 'Banks - Diversified' || $tdata['tkr_industry'] == 'Banks - Global' || $tdata['tkr_industry'] == 'Banks - Regional' || $tdata['tkr_industry'] == 'Banks - Regional - Latin America' || $tdata['tkr_industry'] == 'Banks - Regional - US' || $tdata['tkr_industry'] == 'Savings & Cooperative Banks') {
                $tkr_bank = true;
                $tkr_clsname = '은행업';
            }
            else if( $tdata['tkr_industry'] == 'Insurance - Diversified' || $tdata['tkr_industry'] == 'Insurance - Life' || $tdata['tkr_industry'] == 'Insurance - Property & Casualty' || $tdata['tkr_industry'] == 'Insurance - Reinsurance' || $tdata['tkr_industry'] == 'Insurance - Specialty' || $tdata['tkr_industry'] == 'Insurance - Brokers') {
                $tkr_insu = true;
                $tkr_clsname = '보험업';
            }
            else if($tdata['tkr_industry'] == 'Credit Services' || $tdata['tkr_industry'] == 'Capital Markets') {
                $tkr_cscm = true;
                $tkr_clsname = $tdata['tkr_industry'];
            }
            else {
                $tkr_fin = true;
                $tkr_clsname = '금융업';
            } 
        }

        $item_detail_map = array();

        //수익성장성
        //avg6yroe->20 MRT roe대체(2020.01/23)
        //$item_detail_map['growth']['avg6yroe'] = '';
        $item_detail_map['growth']['roe'] = '';
        $item_detail_map['growth']['epsgr'] = '';

        //재무안전성
        if($tkr_bank === true || $tkr_insu === true || $tkr_fin === true || $tkr_cscm === true) {
            $item_detail_map['safety']['bis'] = '';
        }
        else {
            $item_detail_map['safety']['crratio'] = '';
            $item_detail_map['safety']['debtratio'] = '';
            $item_detail_map['safety']['intcoverage'] = '';
            $item_detail_map['safety']['borrowingratio'] = '';
            $item_detail_map['safety']['financialcost'] = '';
        }
    
        //현금창출력
        if($tkr_fin === true || $tkr_bank === true || $tkr_insu === true || $tkr_cscm === true) {
            $item_detail_map['cashflow']['cashflow'] = '';
            $item_detail_map['cashflow']['ncfo2'] = '';
        }
        else {
            $item_detail_map['cashflow']['ncfo'] = '';
            $item_detail_map['cashflow']['pcr'] = '';
            $item_detail_map['cashflow']['cashflow'] = '';
            $item_detail_map['cashflow']['ncfo2'] = '';
            $item_detail_map['cashflow']['fcfonrevenue'] = '';
        }
        
        //사업독점력
        if($tkr_fin === true || $tkr_bank === true || $tkr_insu === true || $tkr_cscm === true) {
            $item_detail_map['moat']['roe'] = '';
            $item_detail_map['moat']['assetsgr'] = '';
            $item_detail_map['moat']['opmargin'] = '';
        }
        else {
            $item_detail_map['moat']['roe'] = '';
            $item_detail_map['moat']['opmargin'] = '';
            $item_detail_map['moat']['revenuegr'] = '';
            $item_detail_map['moat']['longtermdebt'] = '';
            $item_detail_map['moat']['netincncfo'] = '';
        }

        //배당매력도
        $item_detail_map['dividend']['epsgr2'] = '';
        $item_detail_map['dividend']['fcfgr'] = '';
        $item_detail_map['dividend']['divyield'] = '';
        $item_detail_map['dividend']['payoutratio'] = '';
        $item_detail_map['dividend']['dps'] = '';

        //밸류에이션
        $item_detail_map['valuation']['valuation'] = '';
        //$item_detail_map['valuation']['pbroevaluation'] = '';
        //$item_detail_map['valuation']['evebitdavaluation'] = '';
        //$item_detail_map['valuation']['yamaguchivaluation'] = '';
        
        if( ! array_key_exists($mri_item, $item_detail_map)) {
            return array();
        }

        $result = array();

        $first_flag = true;
        $total_score = 0;
        

        // 벨류에이션, 배당 매력에서 참조함
        $interest_rate = 0.0279; //0.0302=>0.0279(19.12/17변경) // 3.02% "적용금리". 추수 조정 가능성이 많음.

        //Financial Services 스파이더 점수 산정(01.20 적용)

        foreach($item_detail_map[$mri_item] as $item_detail => $v) {
            $score = 0;
            $memo = '';

            // 수익 성장성
            if($mri_item == 'growth') {
                if($first_flag) {
                    $first_flag = false;

                    // avg6yroe 필요데이터 구하기
                    //$ticker_6yroe_map = $this->sf1_tb_model->getTicker6YRoeMap();
                    //avg6yroe->20 MRT roe대체(2020.01/23)

                    if($tkr_adr === true) {
                        $extra = array(
                                'limit' => 5,
                                );
                    }
                    else {
                        $extra = array(
                                'limit' => 21,
                                );
                    }
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];

                    if($tkr_adr === true) {
                        // epsgr 필요데이터 구하기
                        $extra = array(
                                'limit' => 2,
                                );
                    }
                    else {
                        // epsgr 필요데이터 구하기
                        $extra = array(
                                'limit' => 5,
                                );
                    }
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = $this->common->array2Map($mrt_data['findata'], 'sf1_datekey', 'sf1_epsdil');
//echo '<pre>';
//print_r($mrt_data);

                    $current = array_shift($mrt_data);
                    $before = array_pop($mrt_data);
                    $rate = @($current / $before -1)*100;
                }

                if( $tkr_bank === true || $tkr_insu == true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_roe']*100;
                            }
                            $avg = array_sum($log)/count($log);

                            if( $avg > 10 ) {
                                $score = 10;
                            }
                            else if( $avg > 5 ) {
                                $score = 6;
                            }
                            else if( $avg > 3 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                
                            $memo = '(20 MRT roe AVG '.$avg.' 10점(roe>10), 6점(roe>5), 4점(roe>3), 2점(roe>0), 0점';
                            break;
                        case 'epsgr' :

                            $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'% : 10(epsgr >= 25), 8(15 <= epsgr < 25), 6(7.2 <= epsgr < 15), 4(0 <= epsgr < 7.2), 2(epsgr < 0 && eps > 0 && (t-1y)eps < 0 ), 0( epsgr < 0 && (t-1y)eps < 0 or epsgr < 0) ';
                            
                            if($current < 0 && $before < 0) {
                                $score = 0;
                            } else if($rate < 0 && $current > 0 && $before < 0) {
                                $score = 2;
                            } else if($rate < 0 ) {
                                $score = 0;
                            } else if($rate >= 25) {
                                $score = 10;
                            } else if($rate >= 15) {
                                $score = 8;
                            } else if($rate >= 7.2) {
                                $score = 6;
                            } else if($rate >= 0) {
                                $score = 4;
                            }
                            break;
                    }
                }
                else if( $tkr_fin === true || $tkr_cscm === true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_roe']*100;
                            }
                            $avg = array_sum($log)/count($log);

                            if( $avg > 15 ) {
                                $score = 10;
                            }
                            else if( $avg > 10 ) {
                                $score = 6;
                            }
                            else if( $avg > 5 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                
                            $memo = '(20 MRT roe AVG '.$avg.' 10점(roe>15), 6점(roe>10), 4점(roe>5), 2점(roe>0), 0점';
                            break;
                        case 'epsgr' :

                            $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'% : 10(epsgr >= 25), 8(15 <= epsgr < 25), 6(7.2 <= epsgr < 15), 4(0 <= epsgr < 7.2), 2(epsgr < 0 && eps > 0 && (t-1y)eps < 0 ), 0( epsgr < 0 && (t-1y)eps < 0 or epsgr < 0) ';
                            
                            if($current < 0 && $before < 0) {
                                $score = 0;
                            } else if($rate < 0 && $current > 0 && $before < 0) {
                                $score = 2;
                            } else if($rate < 0 ) {
                                $score = 0;
                            } else if($rate >= 25) {
                                $score = 10;
                            } else if($rate >= 15) {
                                $score = 8;
                            } else if($rate >= 7.2) {
                                $score = 6;
                            } else if($rate >= 0) {
                                $score = 4;
                            }
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_roe']*100;
                            }
                            $avg = array_sum($log)/count($log);

                            if( $avg > 15 ) {
                                $score = 10;
                            }
                            else if( $avg > 10 ) {
                                $score = 6;
                            }
                            else if( $avg > 5 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                
                            $memo = '(20 MRT roe AVG '.$avg.' 10점(roe>15), 6점(roe>10), 4점(roe>5), 2점(roe>0), 0점';
                            break;
                        case 'epsgr' :

                            $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'%)';
                            
                            if($current < 0 && $before < 0) {
                                $score = 0;
                            } else if($rate < 0 && $current > 0 && $before < 0) {
                                $score = 2;
                            } else if($rate < 0 ) {
                                $score = 0;
                            } else if($rate >= 25) {
                                $score = 10;
                            } else if($rate >= 15) {
                                $score = 8;
                            } else if($rate >= 7.2) {
                                $score = 6;
                            } else if($rate >= 0) {
                                $score = 4;
                            }
                            break;
                    }
                }
            }

            // 재무안전성
            if($mri_item == 'safety') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 1,
                            );
                    // currentratio, de 필요데이터 구하기
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);
                }

                if( $tkr_bank === true ) { 
                    switch($item_detail) {
                        case 'bis' :
                            if($tkr_adr === true) {
                                $bis = @( $mrt_data['sf1_equity'] / $mrt_data['sf1_assets'] * 100 );
                            }
                            else {
                                $bis = @( $mrq_data['sf1_equity'] / $mrq_data['sf1_assets'] * 100 );
                            }
                            if($bis>8) {
                                $score = 20;
                            }
                            else if($bis>6) {
                                $score = 12;
                            }
                            else if($bis>4) {
                                $score = 8;
                            }
                            else if($bis>2) {
                                $score = 4;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(bis : '.$bis.') 20(bis>8), 12(bis>6), 8(bis>4), 4(bis>2), 0';
                            break;
                    }
                }
                else if( $tkr_fin === true || $tkr_insu === true || $tkr_cscm === true ) { 
                    switch($item_detail) {
                        case 'bis' :
                            if($tkr_adr === true) {
                                $bis = @( $mrt_data['sf1_equity'] / $mrt_data['sf1_assets'] * 100 );
                            }
                            else {
                                $bis = @( $mrq_data['sf1_equity'] / $mrq_data['sf1_assets'] * 100 );
                            }
                            if($bis>=10) {
                                $score = 20;
                            }
                            else if($bis>8) {
                                $score = 16;
                            }
                            else if($bis>6) {
                                $score = 12;
                            }
                            else if($bis>4) {
                                $score = 8;
                            }
                            else if($bis>2) {
                                $score = 4;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(bis : '.$bis.') 20(bis>=10), 16(bis>8), 12(bis>6), 8(bis>4), 4(bis>2), 0';
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'crratio' :
                            //print_r($mrq_data);
                            if($tkr_adr === true) {
                                $score = $mrt_data['sf1_currentratio'] >= 1 ? 2 : 0;
                                $memo = '(currentratio '.$mrt_data['sf1_currentratio'].' >= 1 ? 2 : 0)';
                            }
                            else {
                                $score = $mrq_data['sf1_currentratio'] >= 1 ? 2 : 0;
                                $memo = '(currentratio '.$mrq_data['sf1_currentratio'].' >= 1 ? 2 : 0)';
                            }
                            break;
                        case 'debtratio' :
                            if($tkr_adr === true) {
                                if($mrt_data['sf1_de']=='N/A') {
                                    $score = 0;
                                }
                                else {
                                    $score = $mrt_data['sf1_de'] <= 1.5 ? 2 : 0;
                                }
                                $memo = '(de '.$mrt_data['sf1_de'].' <= 1.5 ? 2 : 0)';
                            }
                            else {
                                if($mrq_data['sf1_de']=='N/A') {
                                    $score = 0;
                                }
                                else {
                                    $score = $mrq_data['sf1_de'] <= 1.5 ? 2 : 0;
                                }
                                $memo = '(de '.$mrq_data['sf1_de'].' <= 1.5 ? 2 : 0)';
                            }
                            break;
                        case 'intcoverage' :
                            //print_r($mrt_data);
                            if( ! $mrt_data['sf1_intexpcoverage'] || $mrt_data['sf1_intexpcoverage'] == 'N/A') {
                                //$estim_intexpcoverage = ($mrt_data['sf1_debtc'] + $mrt_data['sf1_debtnc']) * 0.04;
                                //$estim_intexpcoverage = ($mrt_data['sf1_debtc']) * 0.04;
                                $estim_intexpcoverage = @( $mrt_data['sf1_opinc'] / ( $mrt_data['sf1_debtc'] * 0.04 ) );
                                
                                if( $estim_intexpcoverage > 2 ) {
                                    $score = 8;
                                }
                                else if( $estim_intexpcoverage > 1 ) {
                                    $score = 4;
                                }
                                else {
                                    $score = 0;
                                }

                                //$score = $estim_intexpcoverage >= 2 ? 8 : 0;
                                //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].' + '.$mrt_data['sf1_debtnc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                                //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                                $memo = '추정) opinc ' .$mrt_data['sf1_opinc']. ' / ( debtc '.$mrt_data['sf1_debtc'].' * 0.04 ) = ['.$estim_intexpcoverage.'] : 8(intcoverage > 2), 4(1 < intcoverage <= 2), 0(intcoverage <= 1)';
                            } else {
                                if( $mrt_data['sf1_intexpcoverage'] > 2 ) {
                                    $score = 8;
                                }
                                else if( $mrt_data['sf1_intexpcoverage'] > 1 ) {
                                    $score = 4;
                                }
                                else {
                                    $score = 0;
                                }
                                //$score = $mrt_data['sf1_intexpcoverage'] >= 2 ? 8 : 0;
                                $memo = '(intexpcoverage '.$mrt_data['sf1_intexpcoverage'].' : 8(intcoverage > 2), 4(1 < intcoverage <= 2), 0(intcoverage <= 1)';
                            }
                            break;
                        case 'borrowingratio' :
                            //$score = $mrt_data['sf1_borrowtoassets'] <= 0.3 ? 4 : 0;
                            if($mrt_data['sf1_borrowtoassets'] <= 0.3) {
                                $score = 4;
                            }
                            else if($mrt_data['sf1_borrowtoassets'] > 0.3 && $mrt_data['sf1_borrowtoassets'] <= 0.4) {
                                $score = 2;
                            }
                            else { //$mrt_data['sf1_borrowtoassets'] > 0.4 
                                $score = 0;
                            }
                            $memo = '(borrowtoassets '.$mrt_data['sf1_borrowtoassets'].' : 4(borrowtoassets<=0.3), 2(0.3 < borrowtoassets <= 0.4), 0(borrowtoassets>0.4))';
                            break;
                        case 'financialcost' :
                            if($mrt_data['sf1_intexprevenue'] == 'N/A' || $mrt_data['sf1_intexprevenue'] == 0) {
                                $intexprevenue = (($mrq_data['sf1_debtc'] + $mrq_data['sf1_debtnc'])*0.04) / $mrt_data['sf1_revenue'];
                                
                                //$score = $intexprevenue < 0.03 ? 4 : 0;
                                if($intexprevenue<=0.03) {
                                    $score = 4;
                                }
                                else if($intexprevenue>0.03 && $intexprevenue <=0.04) {
                                    $score = 2;
                                }
                                else { // $intexprevenue > 0.04 
                                    $score = 0;
                                }
                                
                                $memo = '( ((mrq debtc '.$mrq_data['sf1_debtc'].' + mrq debtnc '.$mrq_data['sf1_debtnc'].') * 0.04) / revenue '.$mrt_data['sf1_revenue'].' = ['.$intexprevenue.'] : 4(financialcost<=0.03), 2(0.03 < financialcost <= 0.04), 0(financialcost>0.04))';
                            } else {
                                //$score = $mrt_data['sf1_intexprevenue'] < 0.03 ?  4 : 0;
                                if($mrt_data['sf1_intexprevenue']<=0.03) {
                                    $score = 4;
                                }
                                else if($mrt_data['sf1_intexprevenue']>0.03 && $mrt_data['sf1_intexprevenue'] <=0.04) {
                                    $score = 2;
                                }
                                else { // $mrt_data['sf1_intexprevenue'] > 0.04 
                                    $score = 0;
                                }
                                $memo = '(intexprevenue '.$mrt_data['sf1_intexprevenue'].' : 4(financialcost<=0.03), 2(0.03 < financialcost <= 0.04), 0(financialcost>0.04))';
                            }
                            break;
                    }
                }
            }

            // 현금 창출력
            if($mri_item == 'cashflow') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 4,
                            );
                    if($tkr_adr === true) {
                    
                    }
                    // currentratio, de 필요데이터 구하기
                    //$mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    //$mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];

                    //echo '<pre>';
                    //print_r($mrt_list);
                    $mrt_data = $mrt_list[0];
                    //echo '<pre>';
                    //print_r($mrt_data);
                }

                if( $tkr_bank === true || $tkr_cscm === true ) {
                    switch($item_detail) {
                        case 'cashflow' :
                        //sf1_ncfo:영업, sf1_ncff:재무, sf1_ncfi:투자
                            if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] > 0) {
                                $score = 10;
                            }
                            else if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] < 0) {
                                $score = 5;
                            }
                            else if($mrt_data['sf1_ncfo'] < 0) {
                                $score = 0;
                            }
                            $memo = '(ncfo : '.$mrt_data['sf1_ncfo'].', ncff : '.$mrt_data['sf1_ncff'].' : 10(ncfo > 0 && ncff > 0), 5(ncfo > 0 && ncff < 0), 0(ncfo < 0) )';
                            break;
                        case 'ncfo2' :
                            //$flag = true;
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_ncfo'];
                                //if($l['sf1_ncfo'] <= 0) {
                                //    $flag = false;
                                //}
                            }
                            //$score = $flag ? 6 : 0;
                            
                            if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']>0) {
                                $score = 10;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']<=0) {
                                $score = 8;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']<=0) {
                                $score = 5;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(4 MRT ncfo : '.implode(',', $log).' 4분기연속(10), 3분기연속(8), 2분기연속(5), 그외(0) )';
                            break;
                    }
                }
                else if( $tkr_fin === true || $tkr_insu == true ) {
                    switch($item_detail) {
                        case 'cashflow' :
                        //sf1_ncfo:영업, sf1_ncff:재무, sf1_ncfi:투자
                            if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] < 0) {
                                $score = 10;
                            }
                            else if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] > 0) {
                                $score = 5;
                            }
                            else if($mrt_data['sf1_ncfo'] < 0) {
                                $score = 0;
                            }
                            $memo = '(ncfo : '.$mrt_data['sf1_ncfo'].', ncff : '.$mrt_data['sf1_ncff'].' : 10(ncfo > 0 && ncff < 0), 5(ncfo > 0 && ncff > 0), 0(ncfo < 0) )';
                            break;
                        case 'ncfo2' :
                            //$flag = true;
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_ncfo'];
                                //if($l['sf1_ncfo'] <= 0) {
                                //    $flag = false;
                                //}
                            }
                            //$score = $flag ? 6 : 0;
                            
                            if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']>0) {
                                $score = 10;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']<=0) {
                                $score = 8;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']<=0) {
                                $score = 5;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(4 MRT ncfo : '.implode(',', $log).' 4분기연속(10), 3분기연속(8), 2분기연속(5), 그외(0) )';
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'ncfo' :
                            if($tkr_adr === true) {
                                if(isset($mrt_data['sf1_netinc_ori'])) {
                                    $score = $mrt_data['sf1_netinc_ori'] <= $mrt_data['sf1_ncfo'] ? 2 : 0;
                                    $memo = '(netinc '.$mrt_data['sf1_netinc_ori'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 2)';
                                }
                                else {
                                    $score = $mrt_data['sf1_netinc'] <= $mrt_data['sf1_ncfo'] ? 2 : 0;
                                    $memo = '(netinc '.$mrt_data['sf1_netinc'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 2)';
                                }
                            }
                            else {
                                $score = $mrt_data['sf1_netinc'] <= $mrt_data['sf1_ncfo'] ? 2 : 0;
                                $memo = '(netinc '.$mrt_data['sf1_netinc'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 2)';
                            }
                            break;
                        case 'pcr' :
                            $score = ($mrt_data['sf1_pc'] > 0 && $mrt_data['sf1_pc'] < 20) ? 2 : 0;
                            $memo = '((pc '.$mrt_data['sf1_pc'].' > 0 && '.$mrt_data['sf1_pc'].' < 20) ? 2 : 0)';
                            break;
                        case 'cashflow' :
                            //sf1_ncfo:영업, sf1_ncff:재무, sf1_ncfi:투자
                            if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] < 0) {
                                $score = 6;
                            }
                            else if($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncff'] > 0) {
                                $score = 3;
                            }
                            else if($mrt_data['sf1_ncfo'] < 0) {
                                $score = 0;
                            }
                            $memo = '(ncfo, ncff : '.$mrt_data['sf1_ncfo'].' > 0, '.$mrt_data['sf1_ncff'].' < 0 ? 6 : 0, 3(영업ncfo+/재무ncff+))';
                            break;
                        case 'ncfo2' :
                            //$flag = true;
                            $log = array();
                            foreach($mrt_list as $l) {
                                $log[] = $l['sf1_ncfo'];
                                //if($l['sf1_ncfo'] <= 0) {
                                //    $flag = false;
                                //}
                            }
                            //$score = $flag ? 6 : 0;
                            
                            if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']>0) {
                                $score = 6;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']>0&&$mrt_list[3]['sf1_ncfo']<=0) {
                                $score = 4;
                            }
                            else if($mrt_list[0]['sf1_ncfo']>0&&$mrt_list[1]['sf1_ncfo']>0&&$mrt_list[2]['sf1_ncfo']<=0) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(4 MRT ncfo : '.implode(',', $log).' 4분기연속(6), 3분기연속(4), 2분기연속(2), 그외(0) )';
                            break;
                        case 'fcfonrevenue' :
                            $score = $mrt_data['sf1_fcfonrevenue'] > 0.07 ?  4 : 0;
                            $memo = '(fcfonrevenue '.$mrt_data['sf1_fcfonrevenue'].' > 0.07)';
                            break;
                    }
                }
            }

            // 사업독점력
            if($mri_item == 'moat') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 21,
                            );
                    $mrq_list = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_list = $mrq_list['findata'];

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 21,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }

                if( $tkr_bank === true || $tkr_insu == true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_roe']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_roe']*100;
                                }
                            }
                            $avg = array_sum($log)/count($log);

                            if( $avg > 10 ) {
                                $score = 10;
                            }
                            else if( $avg > 5 ) {
                                $score = 6;
                            }
                            else if( $avg > 3 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                    
                            $memo = '(20 MRT roe AVG '.$avg.' : 10(roe > 10), 6(5< roe <= 10), 4(3< roe <= 5), 2(0< roe <= 3), 0(roe < 0)';
                            break;
                        case 'assetsgr' :
                            $log = array();
                            $cnt=0;
                            if($tkr_adr === true) {
                                foreach($mrt_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                                //echo '<pre>';
                                //print_r($log);
                            }
                            else {
                                foreach($mrq_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                            }
                            $current = array_shift($log);
                            $before = array_pop($log);

                            $rate = @( pow(($current / $before), (1/5)) - 1 ) * 100;

                            if( $rate > 5 ) {
                                $score = 5;
                            }
                            else if( $rate > 4 ) {
                                $score = 4;
                            }
                            else if( $rate > 3 ) {
                                $score = 3;
                            }
                            else if( $rate > 2 ) {
                                $score = 2;
                            }
                            else if( $rate > 1 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            if($tkr_adr === true) {
                                $memo = '(MRT assets / -5Y MRT assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            else {
                                $memo = '(MRQ assets / -5Y MRQ assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            break;
                        case 'opmargin' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_opmargin']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_opmargin']*100;
                                }
                            }
                            $avg = array_sum($log)/count($log);

                            if( $avg > 10 ) {
                                $score = 5;
                            }
                            else if( $avg > 8 ) {
                                $score = 4;
                            }
                            else if( $avg > 6 ) {
                                $score = 3;
                            }
                            else if( $avg > 4 ) {
                                $score = 2;
                            }
                            else if( $avg > 2 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            //$score = $avg >= 10 ? 4 : 0;
                            $memo = '(20 MRT opmargin AVG '.$avg.' : 5(opmargin > 10), 4(8 < opmargin <= 10), 3(6 < opmargin <= 8), 2(4 < opmargin <= 6), 1(2 < opmargin <= 4), 0)';
                            break;
                    }

                }
                else if( $tkr_fin === true || $tkr_cscm === true ) {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_roe']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_roe']*100;
                                }
                            }
                            $avg = array_sum($log)/count($log);

                            if( $avg > 15 ) {
                                $score = 10;
                            }
                            else if( $avg > 10 ) {
                                $score = 6;
                            }
                            else if( $avg > 5 ) {
                                $score = 4;
                            }
                            else if( $avg > 0 ) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                    
                            $memo = '(20 MRT roe AVG '.$avg.' : 10(roe > 15), 6(10< roe <= 15), 4(5< roe <= 10), 2(0< roe <= 5), 0(roe < 0)';
                            break;
                        case 'assetsgr' :
                            $log = array();
                            $cnt=0;
                            if($tkr_adr === true) {
                                foreach($mrt_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrq_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_assets'];
                                    $cnt++;
                                }
                            }
                            $current = array_shift($log);
                            $before = array_pop($log);

                            $rate = @( pow(($current / $before), (1/5)) - 1 ) * 100;

                            if( $rate > 5 ) {
                                $score = 5;
                            }
                            else if( $rate > 4 ) {
                                $score = 4;
                            }
                            else if( $rate > 3 ) {
                                $score = 3;
                            }
                            else if( $rate > 2 ) {
                                $score = 2;
                            }
                            else if( $rate > 1 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            if($tkr_adr === true) {
                                $memo = '(MRT assets / -5Y MRT assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            else {
                                $memo = '(MRQ assets / -5Y MRQ assets)^(1/5)% rate=['.$rate.'] 5(assetsgr>5), 4(4<assetsgr<=5), 3(3<assetsgr<=4), 2(2<assetsgr<=3), 1(1<assetsgr<=2), 0) assets=>'.$current.' assets-5Y=>'.$before;
                            }
                            break;
                        case 'opmargin' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_opmargin']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_opmargin']*100;
                                }
                            }
                            $avg = array_sum($log)/count($log);


                            if( $avg > 20 ) {
                                $score = 5;
                            }
                            else if( $avg > 15 ) {
                                $score = 4;
                            }
                            else if( $avg > 10 ) {
                                $score = 3;
                            }
                            else if( $avg > 5 ) {
                                $score = 2;
                            }
                            else if( $avg > 0 ) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }

                            //$score = $avg >= 10 ? 4 : 0;
                            $memo = '(20 MRT opmargin AVG '.$avg.' >= 10 ? 5 : 0)';
                            $memo = '(20 MRT opmargin AVG '.$avg.' : 5(opmargin > 20), 4(15 < opmargin <= 20), 3(10 < opmargin <= 15), 2(5 < opmargin <= 10), 1(0 < opmargin <= 5), 0)';
                            break;
                    }
                }
                else {
                    switch($item_detail) {
                        case 'roe' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_roe']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_roe']*100;
                                }
                            }
                            $avg = array_sum($log)/count($log);
                            
                            if($avg > 15) {
                                $score = 6;
                            } 
                            else if($avg > 10) {
                                $score = 4;
                            }
                            else if($avg > 5) {
                                $score = 2;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(20 MRT roe AVG '.$avg.' : 6(roe > 15), 4(roe > 10), 2(roe > 5), 0(roe<=5))';
                            break;
                        case 'opmargin' :
                            $log = array();
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>4) break;
                                    $log[] = $l['sf1_opmargin']*100;
                                    $cnt++;
                                }
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_opmargin']*100;
                                }
                            }
                            $avg = array_sum($log)/count($log);
                            if($avg>20) {
                                $score = 6;
                            }
                            else if($avg>15) {
                                $score = 5;
                            }
                            else if($avg>10) {
                                $score = 4;
                            }
                            else if($avg>5) {
                                $score = 2;
                            }
                            else if($avg>0) {
                                $score = 1;
                            }
                            else {
                                $score = 0;
                            }
                            $memo = '(20 MRT opmargin AVG '.$avg.' : 6(avg > 20), 5(15 < avg <= 20), 4(10 < avg <= 15), 2(5 < avg <= 10), 1(0 < avg <= 5), 0(avg <= 0)';
                            break;
                        case 'revenuegr' :
                            $log = array();
                            //echo '<pre>';
                            if($tkr_adr === true) {
                                $cnt=0;
                                foreach($mrt_list as $l) {
                                    if($cnt>5) break;
                                    $log[] = $l['sf1_revenue'];
                                    $cnt++;
                                }
                                //print_r($log);
                            }
                            else {
                                foreach($mrt_list as $l) {
                                    $log[] = $l['sf1_revenue'];
                                }
                            }
                            //print_r($log).'<br>';
                            $current = array_shift($log);
                            $before = array_pop($log);
                            //=(129814000000/93456000000)^(1/5)-1
                            $rate = ( pow(($current / $before), (1/5)) - 1 ) * 100;
                            
                            if( $rate >= 7.2 ) {
                                $score =  4;
                            }
                            else if( $rate >= 3.6 ) {
                                $score =  2;
                            }
                            else {
                                $score =  0;
                            }

                            $memo = '(revenue / -5Y revenue)^(1/5)% rate=['.$rate.'] >= 7.2(4) >=3.6(2) 그 외(0) ) revenue=>'.$current.' revenue-5Y=>'.$before;
                            break;
                        case 'longtermdebt' :
                            if($tkr_adr === true) {
                                if(isset($mrt_data['sf1_netinc_ori'])) {
                                    $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc_ori']*3) ? 2 : 0;
                                    $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc_ori']*3).' ? 2 : 0)';
                                }
                                else {
                                    $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc']*3) ? 2 : 0;
                                    $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc']*3).' ? 2 : 0)';
                                }
                            }
                            else {
                                $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc']*3) ? 2 : 0;
                                $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc']*3).' ? 2 : 0)';
                            }
                            break;
                        case 'netincncfo' :
                            if($tkr_adr === true) {
                                if(isset($mrt_data['sf1_netinc_ori'])) {
                                    $score = (0 < $mrt_data['sf1_netinc_ori'] && $mrt_data['sf1_netinc_ori'] * 0.5 < $mrt_data['sf1_ncfo']) ?  2 : 0;
                                    $memo = '(netinc '.($mrt_data['sf1_netinc_ori']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc_ori'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 2 : 0)';
                                }
                                else {
                                    $score = (0 < $mrt_data['sf1_netinc'] && $mrt_data['sf1_netinc'] * 0.5 < $mrt_data['sf1_ncfo']) ?  2 : 0;
                                    $memo = '(netinc '.($mrt_data['sf1_netinc']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 2 : 0)';
                                }
                            }
                            else {
                                $score = (0 < $mrt_data['sf1_netinc'] && $mrt_data['sf1_netinc'] * 0.5 < $mrt_data['sf1_ncfo']) ?  2 : 0;
                                $memo = '(netinc '.($mrt_data['sf1_netinc']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 2 : 0)';
                            }
                            break;
                    }                
                }
            }

            // 배당매력
            if($mri_item == 'dividend') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );

                    $mry_list = $this->getFinStateList($ticker, 'MRY', $extra)->getData();
                    $mry_list = $mry_list['findata'];
                    $mry_data = $mry_list[0];

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                    //echo 'size=>'.sizeof($mrt_list).'<br><pre>';
                    //print_r($mrt_list);
                }
                switch($item_detail) {
                    case 'epsgr2' :
                        if($tkr_adr === true) {
                            $score_val = @($mrt_data['sf1_epsdil'] / $mrt_list[1]['sf1_epsdil'] -1) * 100;
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '((mrt epsdil '.$mrt_data['sf1_epsdil'].' / t-1y mrt epsdil '.$mrt_list[1]['sf1_epsdil'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        else {
                            $score_val = @($mrt_data['sf1_epsdil'] / $mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'] -1) * 100;
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '((mrt epsdil '.$mrt_data['sf1_epsdil'].' / t-1y mrt epsdil '.$mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        break;
                    case 'fcfgr' :
                        if($tkr_adr === true) {
                            $score_val = @($mrt_data['sf1_fcf'] / $mrt_list[1]['sf1_fcf'] -1) * 100;
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '( (mrt fcf '.$mrt_data['sf1_fcf'].' / t-1y mrt fcf '.$mrt_list[1]['sf1_fcf'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        else {
                            $score_val = @($mrt_data['sf1_fcf'] / $mrt_list[sizeof($mrt_list)-1]['sf1_fcf'] -1) * 100;
                            $score = $score_val > 0 ? 2 : 0;
                            $memo = '( (mrt fcf '.$mrt_data['sf1_fcf'].' / t-1y mrt fcf '.$mrt_list[sizeof($mrt_list)-1]['sf1_fcf'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        }
                        break;
                    case 'divyield' :
                        //$score = $mry_data['sf1_divyield'] >= $interest_rate ? 6 : 0;
                        if($interest_rate < $mry_data['sf1_divyield'] ) {
                            $score = 6;
                        }
                        else if($mry_data['sf1_divyield']>1 && $mry_data['sf1_divyield'] <= $interest_rate) {
                            $score = 4;
                        }
                        else if($mry_data['sf1_divyield']>0 && $mry_data['sf1_divyield']<=1) {
                            $score = 2;
                        }
                        else {
                            $score = 0;
                        }

                        $memo = '(divyield : '.$mry_data['sf1_divyield'].', interest_rate : '.$interest_rate.' : 6(interest_rate < divyield), 4(1 < divyield <= interest_rate), 2(0 < interest_rate <= 1), 0)';
                        break;
                    case 'payoutratio' :
                        if( $mry_data['sf1_payoutratio']*100 >30 ) {
                            $score = 4;
                        }
                        else if ( $mry_data['sf1_payoutratio']*100 > 20 && $mry_data['sf1_payoutratio']*100 <= 30 ) {
                            $score = 3;
                        }
                        else if ( $mry_data['sf1_payoutratio']*100 > 10 && $mry_data['sf1_payoutratio']*100 <= 20 ) {
                            $score = 2;
                        }
                        else if ( $mry_data['sf1_payoutratio']*100 > 0 && $mry_data['sf1_payoutratio']*100 <= 10 ) {
                            $score = 1;
                        }
                        else {
                            $score = 0;
                        }

                        //$score = $mry_data['sf1_payoutratio']*100 >= 30 ? 4 : 0;
                        $memo = '(payoutratio '.($mry_data['sf1_payoutratio']*100).' : 4(payoutratio > 30), 3(20 < payoutratio <= 30), 2(10 < payoutratio <= 20), 1(0 < payoutratio <= 10), 0 )';
                        break;
                    case 'dps' :
                        $log = array();
                        $flag = true;
                        foreach($mry_list as $l) {
                            $log[] = $l['sf1_dps'];
                            if($l['sf1_dps'] <= 0) {
                                $flag = false;
                            }
                        }
                        //echo '<pre>';
                        //print_r($log);
                        //$score = $flag ? 6 : 0;

                        if($mry_list[0]['sf1_dps']>0&&$mry_list[1]['sf1_dps']>0&&$mry_list[2]['sf1_dps']>0&&$mry_list[3]['sf1_dps']>0&&$mry_list[4]['sf1_dps']>0) {
                            $score = 6;
                        }
                        else if($mry_list[0]['sf1_dps']>0&&$mry_list[1]['sf1_dps']>0&&$mry_list[2]['sf1_dps']>0&&$mry_list[3]['sf1_dps']>0&&$mry_list[4]['sf1_dps']<=0) {
                            $score = 4;
                        }
                        else if($mry_list[0]['sf1_dps']>0&&$mry_list[1]['sf1_dps']>0&&$mry_list[2]['sf1_dps']>0&&$mry_list[3]['sf1_dps']<=0) {
                            $score = 2;
                        }
                        else {
                            $score = 0;
                        }

                        $memo = '(4 MRY dps : '.implode(',', $log).' 5년연속(6), 4년연속(4), 3년연속(2), 그외(0) )';
                        break;
                }
            }

            // 밸류에이션
            if($mri_item == 'valuation') {
                $result['interest_rate'] = $interest_rate;

                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 1,
                            );
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);

                    $ticker_6yroe_map = $this->sf1_tb_model->getTickerWeight6YRoeMap();

                    $daily_last_row = $this->getTickerDailyLastRow($ticker);

                    $price_map = $this->sep_tb_model->getTickersPrice(array($ticker));
                    $last_price = floatval(str_replace(',','',$price_map[$ticker]['close']));

                    if($mrt_data['sf1_roe'] == 'N/A') {
                        $memo = '';
                        $avg6y_evebitda_map = $this->sf1_tb_model->getTicker6YEvEbitdaMap();
                        if($mrt_data['sf1_evebitda'] > 0 && $mrt_data['sf1_evebitda'] < 30 && $mrt_data['sf1_ebit'] > 0 && isset($avg6y_evebitda_map[$ticker])) {
                            // 2번째 공식
                            $item_detail = 'evebitdavaluation';
                            $reasonable_price = $mrt_data['sf1_ebitda'] * $avg6y_evebitda_map[$ticker] / $mrt_data['sf1_shareswadil'];
                            $memo = 'ebitda '.$mrt_data['sf1_ebitda'].' * avg6y_evebitda '.$avg6y_evebitda_map[$ticker].' / shareswadil '.$mrt_data['sf1_shareswadil'].' = reasonable_price '.$reasonable_price.')';
                        } 
                        else if($mrt_data['sf1_opinc'] > 0) {
                            // 3번째 공식
                            $item_detail = 'yamaguchivaluation';
                            $check_fields = array(
                                $mrt_data['sf1_opinc'],
                                $mrt_data['sf1_assetsc'],
                                $mrt_data['sf1_assetsnc'],
                                $mrt_data['sf1_ppnenet'],
                                $mrt_data['sf1_intangibles'],
                                $mrt_data['sf1_taxassets'],
                                $mrt_data['sf1_liabilitiesc'],
                                $mrt_data['sf1_liabilitiesnc'],
                                $mrt_data['sf1_shareswadil'],
                            );
                            if(in_array('N/A', $check_fields)) {
                                // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                                $score = 0;
                                $memo = '(1, 2, 3식 모두 매치 안됨)';
                                $total_score += $score;
                                $result['nomatch123'] = $score.$memo;
                                break;
                            }

                            $reasonable_price = (($mrt_data['sf1_opinc']*13.18) + $mrt_data['sf1_assetsc'] + ($mrt_data['sf1_assetsnc'] - $mrt_data['sf1_ppnenet'] - $mrt_data['sf1_intangibles'] - $mrt_data['sf1_taxassets']) - ($mrt_data['sf1_liabilitiesc'])*1.2 - $mrt_data['sf1_liabilitiesnc']) / $mrt_data['sf1_shareswadil'];
                            $memo = '((opinc '.$mrt_data['sf1_opinc'].'*13.18) + assetsc '.$mrt_data['sf1_assetsc'].' + (assetsnc '.$mrt_data['sf1_assetsnc'].' - ppnenet '.$mrt_data['sf1_ppnenet'].' - intangibles '.$mrt_data['sf1_intangibles'].' - taxassets '.$mrt_data['sf1_taxassets'].') - (liabilitiesc '.$mrt_data['sf1_liabilitiesc'].')*1.2 - liabilitiesnc '.$mrt_data['sf1_liabilitiesnc'].') / shareswadil '.$mrt_data['sf1_shareswadil'].' = ['.$reasonable_price.']'."\n";
                        } else {
                            // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                            $score = 0;
                            $memo = '(1, 2, 3식 모두 매치 안됨)';
                            $total_score += $score;
                            $result['nomatch123'] = $score.$memo;
                            break;
                        }

                        $low_rate = ($reasonable_price / $last_price -1) * 100;
                        $result['reasonable_price'] = $reasonable_price;
                        $result['undervalue'] = $low_rate;

                        if($low_rate >= 30) {
                            $score = 20;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                        } else if($low_rate >= 20) {
                            $score = 16;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                        } else if($low_rate >= 10) {
                            $score = 12;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                        } else if($low_rate >= 0) {
                            $score = 8;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                        } else if($low_rate >= -30) {
                            $score = 4;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                        }

                        $total_score += $score;
                        $result[$item_detail] = $score.$memo;
                        break;
                    }

                    // 1번째 공식
                    if(isset($ticker_6yroe_map[$ticker])) {
                        $item_detail = 'pbroevaluation';

                        if($ticker_6yroe_map[$ticker] < 0) {
                            $score = 0;
                            $memo = '(6y roe WegithAVG '.$ticker_6yroe_map[$ticker].' < 1)';
                            $total_score += $score;
                            $result[$item_detail] = $score.$memo;
                            break;
                        }

                        if($ticker_6yroe_map[$ticker] <= 80) {
                            $bvps = $mrq_data['sf1_bvps'];
                            $avg6y_roe = $ticker_6yroe_map[$ticker]/100;
                            $avg6y_pb = (($bvps * pow(1 + $avg6y_roe, 6.5)) / pow(1 + $interest_rate, 6.5)) / $bvps;

                            $reasonable_price = $avg6y_pb * $bvps;
                            $low_rate = ($reasonable_price / $last_price -1) * 100;

                            $result['reasonable_price'] = $reasonable_price.' (avg6y_pb '.$avg6y_pb.' * bvps '.$bvps.')';
                            $result['undervalue'] = $low_rate;

                            if($low_rate < -30 || floatval($mrt_data['sf1_roe']) < 0 || $mrq_data['sf1_opinc'] < 0) {
                                $score = 0;
                                $memo = '(저평가비율 '.$low_rate.' < 0 or roe '.$mrt_data['sf1_roe'].' < 0 or opinc '.$mrq_data['sf1_opinc'].' < 0)';
                                $memo .= "\n".'(저평가비율 '.$low_rate.' = (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 30) {
                                $score = 20;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 20) {
                                $score = 16;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 10) {
                                $score = 12;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 0) {
                                $score = 8;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= -30) {
                                $score = 4;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            }
                        }
                    }
                }
            }

            $total_score += $score;
            $result[$item_detail] = $score.$memo;
        }
        $result['total_score'] = $total_score;
        $result['stars'] = sprintf('%.1f', $total_score / 4);
        $result['clsname'] = $tkr_clsname;
        return $result;
    }

    private function getNewMRIData($ticker, $mri_item) {
        $ticker = strtoupper($ticker);
        $mri_item = strtolower($mri_item);
        
        $item_detail_map = array();
        $item_detail_map['growth']['avg6yroe'] = '';
        $item_detail_map['growth']['epsgr'] = '';

        $item_detail_map['safety']['crratio'] = '';
        $item_detail_map['safety']['debtratio'] = '';
        $item_detail_map['safety']['intcoverage'] = '';
        $item_detail_map['safety']['borrowingratio'] = '';
        $item_detail_map['safety']['financialcost'] = '';

        $item_detail_map['cashflow']['ncfo'] = '';
        $item_detail_map['cashflow']['pcr'] = '';
        $item_detail_map['cashflow']['cashflow'] = '';
        $item_detail_map['cashflow']['ncfo2'] = '';
        $item_detail_map['cashflow']['fcfonrevenue'] = '';

        $item_detail_map['moat']['roe'] = '';
        $item_detail_map['moat']['opmargin'] = '';
        $item_detail_map['moat']['revenuegr'] = '';
        $item_detail_map['moat']['longtermdebt'] = '';
        $item_detail_map['moat']['netincncfo'] = '';

        $item_detail_map['dividend']['epsgr2'] = '';
        $item_detail_map['dividend']['fcfgr'] = '';
        $item_detail_map['dividend']['divyield'] = '';
        $item_detail_map['dividend']['payoutratio'] = '';
        $item_detail_map['dividend']['dps'] = '';


        $item_detail_map['valuation']['pbroevaluation'] = '';
        $item_detail_map['valuation']['evebitdavaluation'] = '';
        $item_detail_map['valuation']['yamaguchivaluation'] = '';

        $item_detail_map['safety+cashflow']['intcoverage'] = '';
        $item_detail_map['safety+cashflow']['debtratio'] = '';
        $item_detail_map['safety+cashflow']['crratio'] = '';
        $item_detail_map['safety+cashflow']['borrowingratio'] = '';
        $item_detail_map['safety+cashflow']['financialcost'] = '';
        $item_detail_map['safety+cashflow']['cashflow'] = '';

        
        if( ! array_key_exists($mri_item, $item_detail_map)) {
            return array();
        }

        $result = array();

        $first_flag = true;
        $total_score = 0;
        
        $total_score_A = 0;
        $total_score_B = 0;

        // 벨류에이션, 배당 매력에서 참조함
        $interest_rate = 0.0279; //0.0302=>0.0279(19.12/17변경) // 3.02% "적용금리". 추수 조정 가능성이 많음.

        foreach($item_detail_map[$mri_item] as $item_detail => $v) {
            $score = 0;
            $score_A = 0;
            $score_B = 0;
            $memo = '';

            // 수익 성장성
            if($mri_item == 'growth') {
                if($first_flag) {
                    $first_flag = false;

                    // avg6yroe 필요데이터 구하기
                    $ticker_6yroe_map = $this->sf1_tb_model->getTicker6YRoeMap();


                    // epsgr 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = $this->common->array2Map($mrt_data['findata'], 'sf1_datekey', 'sf1_epsdil');

                    $current = array_shift($mrt_data);
                    $before = array_pop($mrt_data);
                    $rate = ($current / $before -1)*100;
                }
                switch($item_detail) {
                    case 'avg6yroe' :
                        //print_r($ticker_6yroe_map);
                        $score = @($ticker_6yroe_map[$ticker] >= 15 ? 10 : 0);
                        $score_A = $score;
                        $score_B = $score;
                        $memo = '(avg6yroe '.$ticker_6yroe_map[$ticker].'% >= 15)';
                        break;
                    case 'epsgr' :

                        $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'%)';
                        if($rate >= 25) {
                            $score = 10;
                        } else if($rate >= 15) {
                            $score = 8;
                        } else if($rate >= 7.2) {
                            $score = 6;
                        } else if($rate >= 0) {
                            $score = 4;
                        } else if($rate < 0 && $current > 0 && $before < 0) {
                            $score = 2;
                        } else if($rate < 0 ) {
                            $score = 0;
                        } 

                        $score_A = $score;
                        $score_B = $score;
                        break;
                }
            }

            // 재무안정성
            if($mri_item == 'safety') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 1,
                            );
                    // currentratio, de 필요데이터 구하기
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);
                }
                switch($item_detail) {
                    case 'crratio' :
                        //print_r($mrq_data);
                        $score = $mrq_data['sf1_currentratio'] >= 1 ? 2 : 0;
                        $score_B = $score;
                        $memo = '(currentratio '.$mrq_data['sf1_currentratio'].' >= 1)';
                        break;
                    case 'debtratio' :
                        $score = $mrq_data['sf1_de'] <= 1.5 ? 2 : 0;
                        $score_B = $score;
                        $memo = '(de '.$mrq_data['sf1_de'].' <= 1.5)';
                        break;
                    case 'intcoverage' :
                        //print_r($mrt_data);
                        if( ! $mrt_data['sf1_intexpcoverage'] || $mrt_data['sf1_intexpcoverage'] == 'N/A') {
                            //$estim_intexpcoverage = ($mrt_data['sf1_debtc'] + $mrt_data['sf1_debtnc']) * 0.04;
                            //$estim_intexpcoverage = ($mrt_data['sf1_debtc']) * 0.04;
                            $estim_intexpcoverage = $mrt_data['sf1_opinc'] / ( $mrt_data['sf1_debtc'] * 0.04 );
                            $score = $estim_intexpcoverage >= 2 ? 8 : 0;
                            $score_B = $score;

                            //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].' + '.$mrt_data['sf1_debtnc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                            //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                            $memo = '추정) opinc ' .$mrt_data['sf1_opinc']. ' / ( debtc '.$mrt_data['sf1_debtc'].' * 0.04 ) = ['.$estim_intexpcoverage.'] >= 2 ? 8 : 0';
                        } else {
                            $score = $mrt_data['sf1_intexpcoverage'] >= 2 ? 8 : 0;
                            $score_B = $score;
                            $memo = '(intexpcoverage '.$mrt_data['sf1_intexpcoverage'].' >= 2)';
                        }
                        break;
                    case 'borrowingratio' :
                        $score = $mrt_data['sf1_borrowtoassets'] <= 0.3 ? 4 : 0;
                        $score_B = $score;
                        $memo = '(borrowtoassets '.$mrt_data['sf1_borrowtoassets'].' <= 0.3)';
                        break;
                    case 'financialcost' :
                        if($mrt_data['sf1_intexprevenue'] == 'N/A' || $mrt_data['sf1_intexprevenue'] == 0) {
                            $intexprevenue = (($mrq_data['sf1_debtc'] + $mrq_data['sf1_debtnc'])*0.04) / $mrt_data['sf1_revenue'];
                            $score = $intexprevenue < 0.03 ? 4 : 0;
                            $score_B = $score;
                            $memo = '( ((mrq debtc '.$mrq_data['sf1_debtc'].' + mrq debtnc '.$mrq_data['sf1_debtnc'].') * 0.04) / revenue '.$mrt_data['sf1_revenue'].' = ['.$intexprevenue.'] < 0.03 ? 4 : 0 )';
                        } else {
                            $score = $mrt_data['sf1_intexprevenue'] < 0.03 ?  4 : 0;
                            $score_B = $score;
                            $memo = '(intexprevenue '.$mrt_data['sf1_intexprevenue'].' < 0.03)';
                        }
                        break;
                }
            }

            // 현금 창출력
            if($mri_item == 'cashflow') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 4,
                            );
                    // currentratio, de 필요데이터 구하기
                    //$mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    //$mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }
                switch($item_detail) {
                    case 'ncfo' :
                        $score = $mrt_data['sf1_netinc'] <= $mrt_data['sf1_ncfo'] ? 2 : 0;
                        $score_B = $score;
                        $memo = '(netinc '.$mrt_data['sf1_netinc'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 2)';
                        break;
                    case 'pcr' :
                        $score = ($mrt_data['sf1_pc'] > 0 && $mrt_data['sf1_pc'] < 20) ? 2 : 0;
                        $score_B = $score;
                        $memo = '((pc '.$mrt_data['sf1_pc'].' > 0 && '.$mrt_data['sf1_pc'].' < 20) ? 2 : 0)';
                        break;
                    case 'cashflow' :
                        $score = ($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncfi'] < 0 && $mrt_data['sf1_ncff'] < 0) ? 6 : 0;
                        $score_B = $score;
                        $memo = '(ncfo, ncfi, ncff : '.$mrt_data['sf1_ncfo'].','.$mrt_data['sf1_ncfi'].','.$mrt_data['sf1_ncff'].' > 0 ? 6 : 0)';
                        break;
                    case 'ncfo2' :
                        $flag = true;
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_ncfo'];
                            if($l['sf1_ncfo'] <= 0) {
                                $flag = false;
                            }
                        }
                        $score = $flag ? 6 : 0;
                        $score_B = $score;
                        $memo = '(4 MRT ncfo : '.implode(',', $log).' > 0)';
                        break;
                    case 'fcfonrevenue' :
                        $score = $mrt_data['sf1_fcfonrevenue'] > 0.07 ?  4 : 0;
                        $score_B = $score;
                        $memo = '(fcfonrevenue '.$mrt_data['sf1_fcfonrevenue'].' > 0.07)';
                        break;
                }
            }


            // 사업독점력
            if($mri_item == 'moat') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 1,
                            );
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 21,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }
                switch($item_detail) {
                    case 'roe' :
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_roe']*100;
                        }
                        $avg = array_sum($log)/count($log);
                        $score = $avg >= 15 ? 4 : 0;
                        $score_A = $score;
                        $score_B = $score;
                        $memo = '(20 MRT roe AVG '.$avg.' >= 15 ? 4 : 0)';
                        break;
                    case 'opmargin' :
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_opmargin']*100;
                        }
                        $avg = array_sum($log)/count($log);
                        $score = $avg >= 10 ? 4 : 0;
                        $score_A = $score;
                        $score_B = $score;
                        $memo = '(20 MRT opmargin AVG '.$avg.' >= 10 ? 4 : 0)';
                        break;
                    case 'revenuegr' :
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_revenue'];
                        }
                        $current = array_shift($log);
                        $before = array_pop($log);
                        //=(129814000000/93456000000)^(1/5)-1
                        $rate = ( pow(($current / $before), (1/5)) - 1 ) * 100;
                        $score =  $rate >= 7.2 ? 4 : 0;
                        $score_A = $score;
                        $score_B = $score;
                        $memo = '(revenue / -5Y revenue)^(1/5)% rate=['.$rate.'] >= 7.2 ? 4 : 0) revenue=>'.$current.' revenue-5Y=>'.$before;
                        break;
                    case 'longtermdebt' :
                        $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc']*3) ? 4 : 0;
                        $score_A = $score;
                        $score_B = $score;
                        $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc']*3).' ? 4 : 0)';
                        break;
                    case 'netincncfo' :
                        $score = (0 < $mrt_data['sf1_netinc'] && $mrt_data['sf1_netinc'] * 0.5 < $mrt_data['sf1_ncfo']) ?  4 : 0;
                        $score_A = $score;
                        $score_B = $score;
                        $memo = '(netinc '.($mrt_data['sf1_netinc']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 4 : 0)';
                        break;
                }
            }

            if($mri_item == 'safety+cashflow') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 1,
                            );
                    // currentratio, de 필요데이터 구하기
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);
                }
                switch($item_detail) {
                    case 'crratio' :
                        //print_r($mrq_data);
                        $score = $mrq_data['sf1_currentratio'] >= 1 ? 2 : 0;
                        $score_A = $score;
                        $memo = '(currentratio '.$mrq_data['sf1_currentratio'].' >= 1)';
                        break;
                    case 'debtratio' :
                        $score = $mrq_data['sf1_de'] <= 1.5 ? 2 : 0;
                        $score_A = $score;
                        $memo = '(de '.$mrq_data['sf1_de'].' <= 1.5)';
                        break;
                    case 'intcoverage' :
                        //print_r($mrt_data);
                        if( ! $mrt_data['sf1_intexpcoverage'] || $mrt_data['sf1_intexpcoverage'] == 'N/A') {
                            //$estim_intexpcoverage = ($mrt_data['sf1_debtc'] + $mrt_data['sf1_debtnc']) * 0.04;
                            //$estim_intexpcoverage = ($mrt_data['sf1_debtc']) * 0.04;
                            $estim_intexpcoverage = $mrt_data['sf1_opinc'] / ( $mrt_data['sf1_debtc'] * 0.04 );
                            $score = $estim_intexpcoverage >= 2 ? 8 : 0;
                            $score_A = $score;

                            //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].' + '.$mrt_data['sf1_debtnc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                            //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                            $memo = '추정) opinc ' .$mrt_data['sf1_opinc']. ' / ( debtc '.$mrt_data['sf1_debtc'].' * 0.04 ) = ['.$estim_intexpcoverage.'] >= 2 ? 8 : 0';
                        } else {
                            $score = $mrt_data['sf1_intexpcoverage'] >= 2 ? 8 : 0;
                            $score_A = $score;
                            $memo = '(intexpcoverage '.$mrt_data['sf1_intexpcoverage'].' >= 2)';
                        }
                        break;
                    case 'borrowingratio' :
                        $score = $mrt_data['sf1_borrowtoassets'] <= 0.3 ? 3 : 0;
                        $score_A = $score;
                        $memo = '(borrowtoassets '.$mrt_data['sf1_borrowtoassets'].' <= 0.3)';
                        break;
                    case 'financialcost' :
                        if($mrt_data['sf1_intexprevenue'] == 'N/A' || $mrt_data['sf1_intexprevenue'] == 0) {
                            $intexprevenue = (($mrq_data['sf1_debtc'] + $mrq_data['sf1_debtnc'])*0.04) / $mrt_data['sf1_revenue'];
                            $score = $intexprevenue < 0.03 ? 2 : 0;
                            $score_A = $score;
                            $memo = '( ((mrq debtc '.$mrq_data['sf1_debtc'].' + mrq debtnc '.$mrq_data['sf1_debtnc'].') * 0.04) / revenue '.$mrt_data['sf1_revenue'].' = ['.$intexprevenue.'] < 0.03 ? 2 : 0 )';
                        } else {
                            $score = $mrt_data['sf1_intexprevenue'] < 0.03 ?  2 : 0;
                            $score_A = $score;
                            $memo = '(intexprevenue '.$mrt_data['sf1_intexprevenue'].' < 0.03)';
                        }
                        break;
                    case 'cashflow' :
                        $extra = array(
                                'limit' => 4,
                                );
                        // currentratio, de 필요데이터 구하기
                        //$mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                        //$mrq_data = array_shift($mrq_data['findata']);

                        // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                        $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                        $mrt_list = $mrt_list['findata'];
                        $mrt_data = $mrt_list[0];
                        $score = ($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncfi'] < 0 && $mrt_data['sf1_ncff'] < 0) ? 3 : 0;
                        $score_A = $score;
                        $memo = '(ncfo, ncfi, ncff : '.$mrt_data['sf1_ncfo'].','.$mrt_data['sf1_ncfi'].','.$mrt_data['sf1_ncff'].' > 0 ? 3 : 0)';
                        break;
                }

            }

            // 배당매력
            if($mri_item == 'dividend') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );

                    $mry_list = $this->getFinStateList($ticker, 'MRY', $extra)->getData();
                    $mry_list = $mry_list['findata'];
                    $mry_data = $mry_list[0];

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }
                switch($item_detail) {
                    case 'epsgr2' :
                        $score_val = ($mrt_data['sf1_epsdil'] / $mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'] -1) * 100;
                        $score = $score_val > 0 ? 2 : 0;
                        $score_A = $score;
                        $memo = '((mrt epsdil '.$mrt_data['sf1_epsdil'].' / t-1y mrt epsdil '.$mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        break;
                    case 'fcfgr' :
                        $score_val = ($mrt_data['sf1_fcf'] / $mrt_list[sizeof($mrt_list)-1]['sf1_fcf'] -1) * 100;
                        $score = $score_val > 0 ? 2 : 0;
                        $score_A = $score;
                        $memo = '( (mrt fcf '.$mrt_data['sf1_fcf'].' / t-1y mrt fcf '.$mrt_list[sizeof($mrt_list)-1]['sf1_fcf'].' -1) * 100 = ['.$score_val.'] > 0 ? 2 : 0)';
                        break;
                    case 'divyield' :
                        $score = $mry_data['sf1_divyield'] >= $interest_rate ? 6 : 0;
                        $score_A = $score;
                        $memo = '(divyield '.$mry_data['sf1_divyield'].' >= interest_rate '.$interest_rate.' ? 6 : 0)';
                        break;
                    case 'payoutratio' :
                        $score = $mry_data['sf1_payoutratio']*100 >= 30 ? 4 : 0;
                        $score_A = $score;
                        $memo = '(payoutratio '.($mry_data['sf1_payoutratio']*100).' >= 30 ? 4 : 0)';
                        break;
                    case 'dps' :
                        $log = array();
                        $flag = true;
                        foreach($mry_list as $l) {
                            $log[] = $l['sf1_dps'];
                            if($l['sf1_dps'] <= 0) {
                                $flag = false;
                            }
                        }
                        $score = $flag ? 6 : 0;
                        $score_A = $score;
                        $memo = '(4 MRY dps : '.implode(',', $log).' > 0)';
                        break;
                }
            }

/*
            // 밸류에이션
            if($mri_item == 'valuation') {
                $result['interest_rate'] = $interest_rate;

                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 1,
                            );
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);

                    $ticker_6yroe_map = $this->sf1_tb_model->getTickerWeight6YRoeMap();

                    $daily_last_row = $this->getTickerDailyLastRow($ticker);

                    $price_map = $this->sep_tb_model->getTickersPrice(array($ticker));
                    $last_price = floatval(str_replace(',','',$price_map[$ticker]['close']));

                    if($mrt_data['sf1_roe'] == 'N/A') {
                        $memo = '';
                        $avg6y_evebitda_map = $this->sf1_tb_model->getTicker6YEvEbitdaMap();
                        if($mrt_data['sf1_evebitda'] > 0 && $mrt_data['sf1_evebitda'] < 30 && $mrt_data['sf1_ebit'] > 0 && isset($avg6y_evebitda_map[$ticker])) {
                            // 2번째 공식
                            $item_detail = 'evebitdavaluation';
                            $reasonable_price = $mrt_data['sf1_ebitda'] * $avg6y_evebitda_map[$ticker] / $mrt_data['sf1_shareswadil'];
                            $memo = 'ebitda '.$mrt_data['sf1_ebitda'].' * avg6y_evebitda '.$avg6y_evebitda_map[$ticker].' / shareswadil '.$mrt_data['sf1_shareswadil'].' = reasonable_price '.$reasonable_price.')';
                        } 
                        else if($mrt_data['sf1_opinc'] > 0) {
                            // 3번째 공식
                            $item_detail = 'yamaguchivaluation';
                            $check_fields = array(
                                $mrt_data['sf1_opinc'],
                                $mrt_data['sf1_assetsc'],
                                $mrt_data['sf1_assetsnc'],
                                $mrt_data['sf1_ppnenet'],
                                $mrt_data['sf1_intangibles'],
                                $mrt_data['sf1_taxassets'],
                                $mrt_data['sf1_liabilitiesc'],
                                $mrt_data['sf1_liabilitiesnc'],
                                $mrt_data['sf1_shareswadil'],
                            );
                            if(in_array('N/A', $check_fields)) {
                                // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                                $score = 0;
                                $memo = '(1, 2, 3식 모두 매치 안됨)';
                                $total_score += $score;
                                $result['nomatch123'] = $score.$memo;
                                break;
                            }

                            $reasonable_price = (($mrt_data['sf1_opinc']*13.18) + $mrt_data['sf1_assetsc'] + ($mrt_data['sf1_assetsnc'] - $mrt_data['sf1_ppnenet'] - $mrt_data['sf1_intangibles'] - $mrt_data['sf1_taxassets']) - ($mrt_data['sf1_liabilitiesc'])*1.2 - $mrt_data['sf1_liabilitiesnc']) / $mrt_data['sf1_shareswadil'];
                            $memo = '((opinc '.$mrt_data['sf1_opinc'].'*13.18) + assetsc '.$mrt_data['sf1_assetsc'].' + (assetsnc '.$mrt_data['sf1_assetsnc'].' - ppnenet '.$mrt_data['sf1_ppnenet'].' - intangibles '.$mrt_data['sf1_intangibles'].' - taxassets '.$mrt_data['sf1_taxassets'].') - (liabilitiesc '.$mrt_data['sf1_liabilitiesc'].')*1.2 - liabilitiesnc '.$mrt_data['sf1_liabilitiesnc'].') / shareswadil '.$mrt_data['sf1_shareswadil'].' = ['.$reasonable_price.']'."\n";
                        } else {
                            // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                            $score = 0;
                            $memo = '(1, 2, 3식 모두 매치 안됨)';
                            $total_score += $score;
                            $result['nomatch123'] = $score.$memo;
                            break;
                        }

                        $low_rate = ($reasonable_price / $last_price -1) * 100;
                        $result['reasonable_price'] = $reasonable_price;
                        $result['undervalue'] = $low_rate;

                        if($low_rate >= 30) {
                            $score = 20;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                        } else if($low_rate >= 20) {
                            $score = 16;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                        } else if($low_rate >= 10) {
                            $score = 12;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                        } else if($low_rate >= 0) {
                            $score = 8;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                        } else if($low_rate >= -30) {
                            $score = 4;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                        }

                        $total_score += $score;
                        $result[$item_detail] = $score.$memo;
                        break;
                    }

                    // 1번째 공식
                    if(isset($ticker_6yroe_map[$ticker])) {
                        $item_detail = 'pbroevaluation';

                        if($ticker_6yroe_map[$ticker] < 0) {
                            $score = 0;
                            $memo = '(6y roe WegithAVG '.$ticker_6yroe_map[$ticker].' < 1)';
                            $total_score += $score;
                            $result[$item_detail] = $score.$memo;
                            break;
                        }

                        if($ticker_6yroe_map[$ticker] <= 80) {
                            $bvps = $mrq_data['sf1_bvps'];
                            $avg6y_roe = $ticker_6yroe_map[$ticker]/100;
                            $avg6y_pb = (($bvps * pow(1 + $avg6y_roe, 6.5)) / pow(1 + $interest_rate, 6.5)) / $bvps;

                            $reasonable_price = $avg6y_pb * $bvps;
                            $low_rate = ($reasonable_price / $last_price -1) * 100;

                            $result['reasonable_price'] = $reasonable_price.' (avg6y_pb '.$avg6y_pb.' * bvps '.$bvps.')';
                            $result['undervalue'] = $low_rate;

                            if($low_rate < -30 || floatval($mrt_data['sf1_roe']) < 0 || $mrq_data['sf1_opinc'] < 0) {
                                $score = 0;
                                $memo = '(저평가비율 '.$low_rate.' < 0 or roe '.$mrt_data['sf1_roe'].' < 0 or opinc '.$mrq_data['sf1_opinc'].' < 0)';
                                $memo .= "\n".'(저평가비율 '.$low_rate.' = (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 30) {
                                $score = 20;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 20) {
                                $score = 16;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 10) {
                                $score = 12;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 0) {
                                $score = 8;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= -30) {
                                $score = 4;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            }
                        }


                    }
                }
            }
*/

            $total_score += $score;
            $total_score_A += $score_A;
            $total_score_B += $score_B;
            $result[$item_detail] = $score.$memo;
        }
        $result['total_score'] = $total_score;
        $result['total_score_A'] = $total_score_A;
        $result['total_score_B'] = $total_score_B;
        $result['stars'] = sprintf('%.1f', $total_score / 4);

        return $result;
    }


    private function getValMRIData($ticker, $mri_item, $tdata=array()) {
        $ticker = strtoupper($ticker);
        $mri_item = strtolower($mri_item);
        
        $item_detail_map = array();
        $item_detail_map['growth']['avg6yroe'] = '';
        $item_detail_map['growth']['epsgr'] = '';

        $item_detail_map['safety']['crratio'] = '';
        $item_detail_map['safety']['debtratio'] = '';
        $item_detail_map['safety']['intcoverage'] = '';
        $item_detail_map['safety']['borrowingratio'] = '';
        $item_detail_map['safety']['financialcost'] = '';

        $item_detail_map['cashflow']['ncfo'] = '';
        $item_detail_map['cashflow']['pcr'] = '';
        $item_detail_map['cashflow']['cashflow'] = '';
        $item_detail_map['cashflow']['ncfo2'] = '';
        $item_detail_map['cashflow']['fcfonrevenue'] = '';

        $item_detail_map['moat']['roe'] = '';
        $item_detail_map['moat']['opmargin'] = '';
        $item_detail_map['moat']['revenuegr'] = '';
        $item_detail_map['moat']['longtermdebt'] = '';
        $item_detail_map['moat']['netincncfo'] = '';

        $item_detail_map['dividend']['epsgr2'] = '';
        $item_detail_map['dividend']['fcfgr'] = '';
        $item_detail_map['dividend']['divyield'] = '';
        $item_detail_map['dividend']['payoutratio'] = '';
        $item_detail_map['dividend']['dps'] = '';


        $item_detail_map['valuation']['pbroevaluation'] = '';
        $item_detail_map['valuation']['evebitdavaluation'] = '';
        $item_detail_map['valuation']['yamaguchivaluation'] = '';

        $item_detail_map['safety+cashflow']['intcoverage'] = '';
        $item_detail_map['safety+cashflow']['debtratio'] = '';
        $item_detail_map['safety+cashflow']['crratio'] = '';
        $item_detail_map['safety+cashflow']['borrowingratio'] = '';
        $item_detail_map['safety+cashflow']['financialcost'] = '';
        $item_detail_map['safety+cashflow']['cashflow'] = '';

        if( ! array_key_exists($mri_item, $item_detail_map)) {
            return array();
        }
/*
echo '<pre>';
print_r($tdata);

Array
(
    [tkr_id] => 23920
    [tkr_table] => SEP
    [tkr_permaticker] => 198508
    [tkr_ticker] => MSFT
    [tkr_name] => Microsoft Corp
    [tkr_exchange] => NASDAQ
    [tkr_isdelisted] => N
    [tkr_category] => Domestic
    [tkr_cusips] => 594918104
    [tkr_siccode] => 7372
    [tkr_sicsector] => Services
    [tkr_sicindustry] => Services-Prepackaged Software
    [tkr_famasector] => 
    [tkr_famaindustry] => Business Services
    [tkr_sector] => Technology
    [tkr_industry] => Software - Infrastructure
    [tkr_scalemarketcap] => 6 - Mega
    [tkr_scalerevenue] => 5 - Large
    [tkr_relatedtickers] => 
    [tkr_currency] => USD
    [tkr_location] => Washington; U.S.A
    [tkr_lastupdated] => 2020-01-15
    [tkr_firstadded] => 2014-09-24
    [tkr_firstpricedate] => 1986-03-13
    [tkr_lastpricedate] => 2020-01-15
    [tkr_firstquarter] => 1992-12-31
    [tkr_lastquarter] => 2019-09-30
    [tkr_secfilings] => https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=0000789019
    [tkr_companysite] => http://www.microsoft.com
    [tkr_created_at] => 2019-03-30 17:48:38
    [tkr_updated_at] => 2020-01-16 08:53:58
)
*/
        $result = array();

        $first_flag = true;
        $total_score = 0;

        // 벨류에이션, 배당 매력에서 참조함
        $interest_rate = 0.0279; //0.0302=>0.0279(19.12/17변경) // 3.02% "적용금리". 추수 조정 가능성이 많음.

        foreach($item_detail_map[$mri_item] as $item_detail => $v) {
            $score = 0;
            $memo = '';

            // 밸류에이션
            if($mri_item == 'valuation') {
                //$result['interest_rate'] = $interest_rate;

                if($first_flag) {
                    $first_flag = false;



                    if( $tdata['tkr_category'] == 'Domestic' ) {
                    
                    }
                    else {
                    
                    }




                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 1,
                            );
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);

                    $ticker_6yroe_map = $this->sf1_tb_model->getTickerWeight6YRoeMap();

                    $daily_last_row = $this->getTickerDailyLastRow($ticker);

                    $price_map = $this->sep_tb_model->getTickersPrice(array($ticker));
                    $last_price = floatval(str_replace(',','',$price_map[$ticker]['close']));

                    if($mrt_data['sf1_roe'] == 'N/A') {
                        $memo = '';
                        $avg6y_evebitda_map = $this->sf1_tb_model->getTicker6YEvEbitdaMap();
                        if($mrt_data['sf1_evebitda'] > 0 && $mrt_data['sf1_evebitda'] < 30 && $mrt_data['sf1_ebit'] > 0 && isset($avg6y_evebitda_map[$ticker])) {
                            // 2번째 공식
                            $item_detail = 'evebitdavaluation';
                            $reasonable_price = $mrt_data['sf1_ebitda'] * $avg6y_evebitda_map[$ticker] / $mrt_data['sf1_shareswadil'];
                            $memo = 'ebitda '.$mrt_data['sf1_ebitda'].' * avg6y_evebitda '.$avg6y_evebitda_map[$ticker].' / shareswadil '.$mrt_data['sf1_shareswadil'].' = reasonable_price '.$reasonable_price.')';
                        } 
                        else if($mrt_data['sf1_opinc'] > 0) {
                            // 3번째 공식
                            $item_detail = 'yamaguchivaluation';
                            $check_fields = array(
                                $mrt_data['sf1_opinc'],
                                $mrt_data['sf1_assetsc'],
                                $mrt_data['sf1_assetsnc'],
                                $mrt_data['sf1_ppnenet'],
                                $mrt_data['sf1_intangibles'],
                                $mrt_data['sf1_taxassets'],
                                $mrt_data['sf1_liabilitiesc'],
                                $mrt_data['sf1_liabilitiesnc'],
                                $mrt_data['sf1_shareswadil'],
                            );
                            if(in_array('N/A', $check_fields)) {
                                // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                                $score = 0;
                                $memo = '(1, 2, 3식 모두 매치 안됨)';
                                $total_score += $score;
                                $result['nomatch123'] = $score.$memo;
                                break;
                            }

                            $reasonable_price = (($mrt_data['sf1_opinc']*13.18) + $mrt_data['sf1_assetsc'] + ($mrt_data['sf1_assetsnc'] - $mrt_data['sf1_ppnenet'] - $mrt_data['sf1_intangibles'] - $mrt_data['sf1_taxassets']) - ($mrt_data['sf1_liabilitiesc'])*1.2 - $mrt_data['sf1_liabilitiesnc']) / $mrt_data['sf1_shareswadil'];
                            $memo = '((opinc '.$mrt_data['sf1_opinc'].'*13.18) + assetsc '.$mrt_data['sf1_assetsc'].' + (assetsnc '.$mrt_data['sf1_assetsnc'].' - ppnenet '.$mrt_data['sf1_ppnenet'].' - intangibles '.$mrt_data['sf1_intangibles'].' - taxassets '.$mrt_data['sf1_taxassets'].') - (liabilitiesc '.$mrt_data['sf1_liabilitiesc'].')*1.2 - liabilitiesnc '.$mrt_data['sf1_liabilitiesnc'].') / shareswadil '.$mrt_data['sf1_shareswadil'].' = ['.$reasonable_price.']'."\n";
                        } else {
                            // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                            $score = 0;
                            $memo = '(1, 2, 3식 모두 매치 안됨)';
                            $total_score += $score;
                            $result['nomatch123'] = $score.$memo;
                            break;
                        }

                        $low_rate = ($reasonable_price / $last_price -1) * 100;
                        $result['reasonable_price'] = $reasonable_price;
                        $result['undervalue'] = $low_rate;

                        if($low_rate >= 30) {
                            $score = 20;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                        } else if($low_rate >= 20) {
                            $score = 16;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                        } else if($low_rate >= 10) {
                            $score = 12;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                        } else if($low_rate >= 0) {
                            $score = 8;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                        } else if($low_rate >= -30) {
                            $score = 4;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                        }

                        $total_score += $score;
                        $result[$item_detail] = $score.$memo;
                        break;
                    }

                    // 1번째 공식
                    if(isset($ticker_6yroe_map[$ticker])) {
                        $item_detail = 'pbroevaluation';

                        if($ticker_6yroe_map[$ticker] < 0) {
                            $score = 0;
                            $memo = '(6y roe WegithAVG '.$ticker_6yroe_map[$ticker].' < 1)';
                            $total_score += $score;
                            $result[$item_detail] = $score.$memo;
                            break;
                        }

                        if($ticker_6yroe_map[$ticker] <= 80) {
                            $bvps = $mrq_data['sf1_bvps'];
                            $avg6y_roe = $ticker_6yroe_map[$ticker]/100;
                            $avg6y_pb = (($bvps * pow(1 + $avg6y_roe, 6.5)) / pow(1 + $interest_rate, 6.5)) / $bvps;

                            $reasonable_price = $avg6y_pb * $bvps;
                            $low_rate = ($reasonable_price / $last_price -1) * 100;

                            $result['reasonable_price'] = $reasonable_price.' (avg6y_pb '.$avg6y_pb.' * bvps '.$bvps.')';
                            $result['undervalue'] = $low_rate;

                            if($low_rate < -30 || floatval($mrt_data['sf1_roe']) < 0 || $mrq_data['sf1_opinc'] < 0) {
                                $score = 0;
                                $memo = '(저평가비율 '.$low_rate.' < 0 or roe '.$mrt_data['sf1_roe'].' < 0 or opinc '.$mrq_data['sf1_opinc'].' < 0)';
                                $memo .= "\n".'(저평가비율 '.$low_rate.' = (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 30) {
                                $score = 20;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 20) {
                                $score = 16;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 10) {
                                $score = 12;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 0) {
                                $score = 8;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= -30) {
                                $score = 4;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            }
                        }
                    }
                }
            }

            $total_score += $score;
            $result[$item_detail] = $score.$memo;
        }
        $result['total_score'] = $total_score;
        //$result['total_score_A'] = $total_score_A;
        //$result['total_score_B'] = $total_score_B;
        $result['stars'] = sprintf('%.1f', $total_score / 4);

        return $result;
    }


    // 종목별 기업MRI 데이터 제공
    public function getTickerCompanyMRI($ticker) {
        $result = array(
            'growth' => array(),
            'safety' => array(),
            'cashflow' => array(),
            'moat' => array(),
            'dividend' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getMRIData($ticker, $k);
        }

        // 재무안정성이 1 이하면 밸류에이션 스타점수 0점 처리
        if($result['safety']['stars'] <= 1 && $result['dividend']['stars'] > 0) {
            $result['valuation']['stars'] = 0;
            $result['valuation']['star_memo'] = '재무안정성 미달로 0점 처리됨';
        }

        return $result;
    }

    // 종목별 주식MRI 데이터 제공
    public function getTickerValMRI($ticker, $tdata=array()) {
        $result = array(
            //'growth' => array(),
            //'safety' => array(),
            //'cashflow' => array(),
            //'moat' => array(),
            'valuation' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getValMRIData($ticker, $k, $tdata);
        }

/*
        // 재무안정성이 1 이하면 밸류에이션 스타점수 0점 처리
        if($result['safety']['stars'] <= 1 && $result['valuation']['stars'] > 0) {
            $result['valuation']['stars'] = 0;
            $result['valuation']['star_memo'] = '재무안정성 미달로 0점 처리됨';
        }
*/
        return $result;
    }


    // 종목별 주식MRI 데이터 제공
    public function getTickerMRI($ticker) {
        $result = array(
            'growth' => array(),
            'safety' => array(),
            'cashflow' => array(),
            'moat' => array(),
            'valuation' => array(),
        );
        foreach($result as $k => &$v) {
            $v = $this->getMRIData($ticker, $k);
        }

        // 재무안정성이 1 이하면 밸류에이션 스타점수 0점 처리
        if($result['safety']['stars'] <= 1 && $result['valuation']['stars'] > 0) {
            $result['valuation']['stars'] = 0;
            $result['valuation']['star_memo'] = '재무안정성 미달로 0점 처리됨';
        }

        return $result;
    }
    private function getMRIData($ticker, $mri_item) {
        $ticker = strtoupper($ticker);
        $mri_item = strtolower($mri_item);
        
        $item_detail_map = array();
        $item_detail_map['growth']['avg6yroe'] = '';
        $item_detail_map['growth']['epsgr'] = '';

        $item_detail_map['safety']['crratio'] = '';
        $item_detail_map['safety']['debtratio'] = '';
        $item_detail_map['safety']['intcoverage'] = '';
        $item_detail_map['safety']['borrowingratio'] = '';
        $item_detail_map['safety']['financialcost'] = '';

        $item_detail_map['cashflow']['ncfo'] = '';
        $item_detail_map['cashflow']['pcr'] = '';
        $item_detail_map['cashflow']['cashflow'] = '';
        $item_detail_map['cashflow']['ncfo2'] = '';
        $item_detail_map['cashflow']['fcfonrevenue'] = '';

        $item_detail_map['moat']['roe'] = '';
        $item_detail_map['moat']['opmargin'] = '';
        $item_detail_map['moat']['revenuegr'] = '';
        $item_detail_map['moat']['longtermdebt'] = '';
        $item_detail_map['moat']['netincncfo'] = '';

        $item_detail_map['dividend']['epsgr2'] = '';
        $item_detail_map['dividend']['fcfgr'] = '';
        $item_detail_map['dividend']['divyield'] = '';
        $item_detail_map['dividend']['payoutratio'] = '';
        $item_detail_map['dividend']['dps'] = '';


        $item_detail_map['valuation']['pbroevaluation'] = '';
        $item_detail_map['valuation']['evebitdavaluation'] = '';
        $item_detail_map['valuation']['yamaguchivaluation'] = '';

        if( ! array_key_exists($mri_item, $item_detail_map)) {
            return array();
        }

        $result = array();

        $first_flag = true;
        $total_score = 0;

        // 벨류에이션, 배당 매력에서 참조함
        $interest_rate = 0.0279; //0.0302=>0.0279(19.12/17변경) // 3.02% "적용금리". 추수 조정 가능성이 많음.

        foreach($item_detail_map[$mri_item] as $item_detail => $v) {
            $score = 0;
            $memo = '';

            // 수익 성장성
            if($mri_item == 'growth') {
                if($first_flag) {
                    $first_flag = false;

                    // avg6yroe 필요데이터 구하기
                    $ticker_6yroe_map = $this->sf1_tb_model->getTicker6YRoeMap();


                    // epsgr 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = $this->common->array2Map($mrt_data['findata'], 'sf1_datekey', 'sf1_epsdil');

                    $current = array_shift($mrt_data);
                    $before = array_pop($mrt_data);
                    $rate = ($current / $before -1)*100;
                }
                switch($item_detail) {
                    case 'avg6yroe' :
                        //print_r($ticker_6yroe_map);
                        $score = @($ticker_6yroe_map[$ticker] >= 15 ? 4 : 0);
                        $memo = '(avg6yroe '.$ticker_6yroe_map[$ticker].'% >= 15)';
                        break;
                    case 'epsgr' :

                        $memo = '(epsdil. current '.$current.' / before '.$before.' = '.$rate.'%)';
                        if($rate >= 25) {
                            $score = 16;
                        } else if($rate >= 15) {
                            $score = 12;
                        } else if($rate >= 7.2) {
                            $score = 8;
                        } else if($rate >= 0) {
                            $score = 4;
                        } else if($rate <= 0 && $current > 0 && $before < 0) {
                            $score = 4;
                        }
                        break;
                }
            }

            // 재무안정성
            if($mri_item == 'safety') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 1,
                            );
                    // currentratio, de 필요데이터 구하기
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);
                }
                switch($item_detail) {
                    case 'crratio' :
                        //print_r($mrq_data);
                        $score = $mrq_data['sf1_currentratio'] >= 1 ? 4 : 0;
                        $memo = '(currentratio '.$mrq_data['sf1_currentratio'].' >= 1)';
                        break;
                    case 'debtratio' :
                        $score = $mrq_data['sf1_de'] <= 1.5 ? 4 : 0;
                        $memo = '(de '.$mrq_data['sf1_de'].' <= 1.5)';
                        break;
                    case 'intcoverage' :
                        //print_r($mrt_data);
                        if( ! $mrt_data['sf1_intexpcoverage'] || $mrt_data['sf1_intexpcoverage'] == 'N/A') {
                            //$estim_intexpcoverage = ($mrt_data['sf1_debtc'] + $mrt_data['sf1_debtnc']) * 0.04;
                            //$estim_intexpcoverage = ($mrt_data['sf1_debtc']) * 0.04;
                            $estim_intexpcoverage = $mrt_data['sf1_opinc'] / ( $mrt_data['sf1_debtc'] * 0.04 );
                            $score = $estim_intexpcoverage >= 2 ? 4 : 0;

                            //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].' + '.$mrt_data['sf1_debtnc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                            //$memo = '추정) (debtc '.$mrt_data['sf1_debtc'].') * 0.04 = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                            $memo = '추정) opinc ' .$mrt_data['sf1_opinc']. ' / ( debtc '.$mrt_data['sf1_debtc'].' * 0.04 ) = ['.$estim_intexpcoverage.'] >= 2 ? 4 : 0';
                        } else {
                            $score = $mrt_data['sf1_intexpcoverage'] >= 2 ? 4 : 0;
                            $memo = '(intexpcoverage '.$mrt_data['sf1_intexpcoverage'].' >= 2)';
                        }
                        break;
                    case 'borrowingratio' :
                        $score = $mrt_data['sf1_borrowtoassets'] <= 0.3 ? 4 : 0;
                        $memo = '(borrowtoassets '.$mrt_data['sf1_borrowtoassets'].' <= 0.3)';
                        break;
                    case 'financialcost' :
                        if($mrt_data['sf1_intexprevenue'] == 'N/A' || $mrt_data['sf1_intexprevenue'] == 0) {
                            $intexprevenue = (($mrq_data['sf1_debtc'] + $mrq_data['sf1_debtnc'])*0.04) / $mrt_data['sf1_revenue'];
                            $score = $intexprevenue < 0.03 ? 4 : 0;
                            $memo = '( ((mrq debtc '.$mrq_data['sf1_debtc'].' + mrq debtnc '.$mrq_data['sf1_debtnc'].') * 0.04) / revenue '.$mrt_data['sf1_revenue'].' = ['.$intexprevenue.'] < 0.03 ? 4 : 0 )';
                        } else {
                            $score = $mrt_data['sf1_intexprevenue'] < 0.03 ?  4 : 0;
                            $memo = '(intexprevenue '.$mrt_data['sf1_intexprevenue'].' < 0.03)';
                        }
                        break;
                }
            }

            // 현금 창출력
            if($mri_item == 'cashflow') {
                if($first_flag) {
                    $first_flag = false;

                    $extra = array(
                            'limit' => 4,
                            );
                    // currentratio, de 필요데이터 구하기
                    //$mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    //$mrq_data = array_shift($mrq_data['findata']);

                    // intexpcoverage, borrowtoassets, intexpratio 필요데이터 구하기
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }
                switch($item_detail) {
                    case 'ncfo' :
                        $score = $mrt_data['sf1_netinc'] <= $mrt_data['sf1_ncfo'] ? 4 : 0;
                        $memo = '(netinc '.$mrt_data['sf1_netinc'].' <= ncfo'.$mrt_data['sf1_ncfo'].' ? 4)';
                        break;
                    case 'pcr' :
                        $score = ($mrt_data['sf1_pc'] > 0 && $mrt_data['sf1_pc'] < 20) ? 4 : 0;
                        $memo = '((pc '.$mrt_data['sf1_pc'].' > 0 && '.$mrt_data['sf1_pc'].' < 20) ? 4 : 0)';
                        break;
                    case 'cashflow' :
                        $score = ($mrt_data['sf1_ncfo'] > 0 && $mrt_data['sf1_ncfi'] < 0 && $mrt_data['sf1_ncff'] < 0) ? 4 : 0;
                        $memo = '(ncfo, ncfi, ncff : '.$mrt_data['sf1_ncfo'].','.$mrt_data['sf1_ncfi'].','.$mrt_data['sf1_ncff'].' > 0 ? 4 : 0)';
                        break;
                    case 'ncfo2' :
                        $flag = true;
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_ncfo'];
                            if($l['sf1_ncfo'] <= 0) {
                                $flag = false;
                            }
                        }
                        $score = $flag ? 4 : 0;
                        $memo = '(4 MRT ncfo : '.implode(',', $log).' > 0)';
                        break;
                    case 'fcfonrevenue' :
                        $score = $mrt_data['sf1_fcfonrevenue'] > 0.07 ?  4 : 0;
                        $memo = '(fcfonrevenue '.$mrt_data['sf1_fcfonrevenue'].' > 0.07)';
                        break;
                }
            }


            // 사업독점력
            if($mri_item == 'moat') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 1,
                            );
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 21,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }
                switch($item_detail) {
                    case 'roe' :
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_roe']*100;
                        }
                        $avg = array_sum($log)/count($log);
                        $score = $avg >= 15 ? 4 : 0;
                        $memo = '(20 MRT roe AVG '.$avg.' >= 15 ? 4 : 0)';
                        break;
                    case 'opmargin' :
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_opmargin']*100;
                        }
                        $avg = array_sum($log)/count($log);
                        $score = $avg >= 10 ? 4 : 0;
                        $memo = '(20 MRT opmargin AVG '.$avg.' >= 10 ? 4 : 0)';
                        break;
                    case 'revenuegr' :
                        $log = array();
                        foreach($mrt_list as $l) {
                            $log[] = $l['sf1_revenue'];
                        }
                        $current = array_shift($log);
                        $before = array_pop($log);
                        //=(129814000000/93456000000)^(1/5)-1
                        $rate = ( pow(($current / $before), (1/5)) - 1 ) * 100;
                        $score =  $rate >= 7.2 ? 4 : 0;
                        $memo = '(revenue / -5Y revenue)^(1/5)% rate=['.$rate.'] >= 7.2 ? 4 : 0) revenue=>'.$current.' revenue-5Y=>'.$before;
                        break;
                    case 'longtermdebt' :
                        $score = ($mrt_data['sf1_debtnc'] < $mrt_data['sf1_netinc']*3) ? 4 : 0;
                        $memo = '(debnc '.($mrt_data['sf1_debtnc']).' < netinc * 3 '.($mrt_data['sf1_netinc']*3).' ? 4 : 0)';
                        break;
                    case 'netincncfo' :
                        $score = (0 < $mrt_data['sf1_netinc'] && $mrt_data['sf1_netinc'] * 0.5 < $mrt_data['sf1_ncfo']) ?  4 : 0;
                        $memo = '(netinc '.($mrt_data['sf1_netinc']).' > 0 and netinc 1/2 '.($mrt_data['sf1_netinc'] * 0.5).' < ncfo '.($mrt_data['sf1_ncfo']).' ? 4 : 0)';
                        break;
                }
            }

            
            // 배당매력
            if($mri_item == 'dividend') {
                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );

                    $mry_list = $this->getFinStateList($ticker, 'MRY', $extra)->getData();
                    $mry_list = $mry_list['findata'];
                    $mry_data = $mry_list[0];

                    // roe, opmargin, revenue, netinc 필요데이터 구하기
                    $extra = array(
                            'limit' => 5,
                            );
                    $mrt_list = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_list = $mrt_list['findata'];
                    $mrt_data = $mrt_list[0];
                }
                switch($item_detail) {
                    case 'epsgr2' :
                        $score_val = ($mrt_data['sf1_epsdil'] / $mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'] -1) * 100;
                        $score = $score_val > 0 ? 4 : 0;
                        $memo = '((mrt epsdil '.$mrt_data['sf1_epsdil'].' / t-1y mrt epsdil '.$mrt_list[sizeof($mrt_list)-1]['sf1_epsdil'].' -1) * 100 = ['.$score_val.'] > 0 ? 4 : 0)';
                        break;
                    case 'fcfgr' :
                        $score_val = ($mrt_data['sf1_fcf'] / $mrt_list[sizeof($mrt_list)-1]['sf1_fcf'] -1) * 100;
                        $score = $score_val > 0 ? 4 : 0;
                        $memo = '( (mrt fcf '.$mrt_data['sf1_fcf'].' / t-1y mrt fcf '.$mrt_list[sizeof($mrt_list)-1]['sf1_fcf'].' -1) * 100 = ['.$score_val.'] > 0 ? 4 : 0)';
                        break;
                    case 'divyield' :
                        $score = $mry_data['sf1_divyield'] >= $interest_rate ? 4 : 0;
                        $memo = '(divyield '.$mry_data['sf1_divyield'].' >= interest_rate '.$interest_rate.' ? 4 : 0)';
                        break;
                    case 'payoutratio' :
                        $score = $mry_data['sf1_payoutratio']*100 >= 30 ? 4 : 0;
                        $memo = '(payoutratio '.($mry_data['sf1_payoutratio']*100).' >= 30 ? 4 : 0)';
                        break;
                    case 'dps' :
                        $log = array();
                        $flag = true;
                        foreach($mry_list as $l) {
                            $log[] = $l['sf1_dps'];
                            if($l['sf1_dps'] <= 0) {
                                $flag = false;
                            }
                        }
                        $score = $flag ? 4 : 0;
                        $memo = '(4 MRY dps : '.implode(',', $log).' > 0)';
                        break;
                }
            }


            // 밸류에이션
            if($mri_item == 'valuation') {
                $result['interest_rate'] = $interest_rate;

                if($first_flag) {
                    $first_flag = false;

                    // debtnc 필요데이터 구하기
                    $extra = array(
                            'limit' => 1,
                            );
                    $mrq_data = $this->getFinStateList($ticker, 'MRQ', $extra)->getData();
                    $mrq_data = array_shift($mrq_data['findata']);

                    $mrt_data = $this->getFinStateList($ticker, 'MRT', $extra)->getData();
                    $mrt_data = array_shift($mrt_data['findata']);

                    $ticker_6yroe_map = $this->sf1_tb_model->getTickerWeight6YRoeMap();

                    $daily_last_row = $this->getTickerDailyLastRow($ticker);

                    $price_map = $this->sep_tb_model->getTickersPrice(array($ticker));
                    $last_price = floatval(str_replace(',','',$price_map[$ticker]['close']));

                    if($mrt_data['sf1_roe'] == 'N/A') {
                        $memo = '';
                        $avg6y_evebitda_map = $this->sf1_tb_model->getTicker6YEvEbitdaMap();
                        if($mrt_data['sf1_evebitda'] > 0 && $mrt_data['sf1_evebitda'] < 30 && $mrt_data['sf1_ebit'] > 0 && isset($avg6y_evebitda_map[$ticker])) {
                            // 2번째 공식
                            $item_detail = 'evebitdavaluation';
                            $reasonable_price = $mrt_data['sf1_ebitda'] * $avg6y_evebitda_map[$ticker] / $mrt_data['sf1_shareswadil'];
                            $memo = 'ebitda '.$mrt_data['sf1_ebitda'].' * avg6y_evebitda '.$avg6y_evebitda_map[$ticker].' / shareswadil '.$mrt_data['sf1_shareswadil'].' = reasonable_price '.$reasonable_price.')';
                        } 
                        else if($mrt_data['sf1_opinc'] > 0) {
                            // 3번째 공식
                            $item_detail = 'yamaguchivaluation';
                            $check_fields = array(
                                $mrt_data['sf1_opinc'],
                                $mrt_data['sf1_assetsc'],
                                $mrt_data['sf1_assetsnc'],
                                $mrt_data['sf1_ppnenet'],
                                $mrt_data['sf1_intangibles'],
                                $mrt_data['sf1_taxassets'],
                                $mrt_data['sf1_liabilitiesc'],
                                $mrt_data['sf1_liabilitiesnc'],
                                $mrt_data['sf1_shareswadil'],
                            );
                            if(in_array('N/A', $check_fields)) {
                                // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                                $score = 0;
                                $memo = '(1, 2, 3식 모두 매치 안됨)';
                                $total_score += $score;
                                $result['nomatch123'] = $score.$memo;
                                break;
                            }

                            $reasonable_price = (($mrt_data['sf1_opinc']*13.18) + $mrt_data['sf1_assetsc'] + ($mrt_data['sf1_assetsnc'] - $mrt_data['sf1_ppnenet'] - $mrt_data['sf1_intangibles'] - $mrt_data['sf1_taxassets']) - ($mrt_data['sf1_liabilitiesc'])*1.2 - $mrt_data['sf1_liabilitiesnc']) / $mrt_data['sf1_shareswadil'];
                            $memo = '((opinc '.$mrt_data['sf1_opinc'].'*13.18) + assetsc '.$mrt_data['sf1_assetsc'].' + (assetsnc '.$mrt_data['sf1_assetsnc'].' - ppnenet '.$mrt_data['sf1_ppnenet'].' - intangibles '.$mrt_data['sf1_intangibles'].' - taxassets '.$mrt_data['sf1_taxassets'].') - (liabilitiesc '.$mrt_data['sf1_liabilitiesc'].')*1.2 - liabilitiesnc '.$mrt_data['sf1_liabilitiesnc'].') / shareswadil '.$mrt_data['sf1_shareswadil'].' = ['.$reasonable_price.']'."\n";
                        } else {
                            // 1, 2, 3, 공식 아무데도 부합되지 않는다.
                            $score = 0;
                            $memo = '(1, 2, 3식 모두 매치 안됨)';
                            $total_score += $score;
                            $result['nomatch123'] = $score.$memo;
                            break;
                        }

                        $low_rate = ($reasonable_price / $last_price -1) * 100;
                        $result['reasonable_price'] = $reasonable_price;
                        $result['undervalue'] = $low_rate;

                        if($low_rate >= 30) {
                            $score = 20;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                        } else if($low_rate >= 20) {
                            $score = 16;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                        } else if($low_rate >= 10) {
                            $score = 12;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                        } else if($low_rate >= 0) {
                            $score = 8;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                        } else if($low_rate >= -30) {
                            $score = 4;
                            $memo .= '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                        }

                        $total_score += $score;
                        $result[$item_detail] = $score.$memo;
                        break;
                    }

                    // 1번째 공식
                    if(isset($ticker_6yroe_map[$ticker])) {
                        $item_detail = 'pbroevaluation';

                        if($ticker_6yroe_map[$ticker] < 0) {
                            $score = 0;
                            $memo = '(6y roe WegithAVG '.$ticker_6yroe_map[$ticker].' < 1)';
                            $total_score += $score;
                            $result[$item_detail] = $score.$memo;
                            break;
                        }

                        if($ticker_6yroe_map[$ticker] <= 80) {
                            $bvps = $mrq_data['sf1_bvps'];
                            $avg6y_roe = $ticker_6yroe_map[$ticker]/100;
                            $avg6y_pb = (($bvps * pow(1 + $avg6y_roe, 6.5)) / pow(1 + $interest_rate, 6.5)) / $bvps;

                            $reasonable_price = $avg6y_pb * $bvps;
                            $low_rate = ($reasonable_price / $last_price -1) * 100;

                            $result['reasonable_price'] = $reasonable_price.' (avg6y_pb '.$avg6y_pb.' * bvps '.$bvps.')';
                            $result['undervalue'] = $low_rate;

                            if($low_rate < -30 || floatval($mrt_data['sf1_roe']) < 0 || $mrq_data['sf1_opinc'] < 0) {
                                $score = 0;
                                $memo = '(저평가비율 '.$low_rate.' < 0 or roe '.$mrt_data['sf1_roe'].' < 0 or opinc '.$mrq_data['sf1_opinc'].' < 0)';
                                $memo .= "\n".'(저평가비율 '.$low_rate.' = (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 30) {
                                $score = 20;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 20) {
                                $score = 16;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 20)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 10) {
                                $score = 12;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 10)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= 0) {
                                $score = 8;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= 0)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            } else if($low_rate >= -30) {
                                $score = 4;
                                $memo = '(저평가비율 [ (적정가 '.$reasonable_price.' / 종가 '.$last_price.' -1) * 100 = ['.$low_rate.'] >= -30)';
                                $total_score += $score;
                                $result[$item_detail] = $score.$memo;
                                break;
                            }
                        }


                    }
                }
            }
            $total_score += $score;
            $result[$item_detail] = $score.$memo;
        }
        $result['total_score'] = $total_score;
        $result['stars'] = sprintf('%.1f', $total_score / 4);

        return $result;
    }

    // 종목별 VChart Data 제공
    public function getVChartData($ticker, $dimension, $vchart_type, $chart_indicator, $extra=array()) {
        $map = array();

        $ticker = strtoupper($ticker);
        $dimension = strtoupper($dimension);

        if(isset($extra['sDate'])) 
            $sDate = $extra['sDate'];
        else 
            $sDate = '';

        if(isset($extra['eDate'])) 
            $eDate = $extra['eDate'];
        else 
            $eDate = '';

        // dimension 유효성 검사
        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
            $this->setErrorResult($dimension.' : 유효하지 않은 dimension');
            return $this;
        }

        $map['profit']['salesincome']       = array('title' => '매출액 & 이익');
        $map['profit']['margin']            = array('title' => '이익률');
        $map['profit']['cor']               = array('title' => '원가율');
        $map['profit']['rnd']               = array('title' => '연구개발비');

        $map['safety']['debtcr']            = array('title' => '부채비율 & 유동비율');
        $map['safety']['borrow']            = array('title' => '차입금 & 차입금비율');
        $map['safety']['opintexp']          = array('title' => '영업이익 & 이자비용');
        $map['safety']['intexpcoverage']    = array('title' => '이자보상배수');
        $map['safety']['debtcost']          = array('title' => '차입금 & 금융비용');

        $map['structure']['assetstructure'] = array('title' => '자산 구조');
        $map['structure']['profitaccum']    = array('title' => '이익 축적');
        $map['structure']['dividend']       = array('title' => '주당배당금 & 배당률');
        $map['structure']['payout']         = array('title' => '배당성향');

        $map['efficiency']['roepbr']        = array('title' => 'ROE & PBR');
        $map['efficiency']['dupont']        = array('title' => 'ROE 듀퐁분석');
        $map['efficiency']['roaroeroic']    = array('title' => 'ROA & ROE & ROIC');
        $map['efficiency']['turnoverdays']  = array('title' => '운전자본 회전일수');
        $map['efficiency']['ccc']           = array('title' => '현금 회전일수');

        $map['cashflow']['cashflow']        = array('title' => '현금흐름표');
        $map['cashflow']['freecashflow']    = array('title' => '잉여현금흐름');
        $map['cashflow']['fcfonrevenue']    = array('title' => '잉여현금흐름 비율');

        $map['valuation']['per']            = array('title' => '주가수익배수(PER)');
        $map['valuation']['priceeps']       = array('title' => '주가 & 주당순이익');
        $map['valuation']['pbr']            = array('title' => '주가순자산배수(PBR)');
        $map['valuation']['pricebps']       = array('title' => '주가 & 주당순자산');
        $map['valuation']['pcr']            = array('title' => '주가현금흐름배수(PCR)');
        $map['valuation']['pricecps']       = array('title' => '주가 & 주당현금흐름');
        $map['valuation']['psr']            = array('title' => '주가매출액배수(PSR)');
        $map['valuation']['pricesps']       = array('title' => '주가 & 주당매출액');
        $map['valuation']['evebitda']       = array('title' => 'EV/EBITDA');

//        $data['vc_safety_debtcr'] = $this->loadVchart( $ticker, 'MRT', 'safety', 'debtcr');
//        $data['vc_safety_borrow'] = $this->loadVchart( $ticker, 'MRT', 'safety', 'borrow');
//        public function getVChartData($ticker, $dimension, $vchart_type, $chart_indicator) {

        // $vchart_type, $chart_indicator 유효성 검사
        if( ! isset($map[$vchart_type][$chart_indicator])) {
            $this->setErrorResult($vchart_type.'_'.$chart_indicator.' : 유효하지 않은 type-indicator');
            return $this;
        }

        $extra = array(
            'limit' => 30,
            'sDate' => $sDate,
            'eDate' => $eDate,
        );
        //$extra['cache_sec'] = 3600*24;
        $extra['slavedb'] = true;
        $extra['bicchart'] = true;

        $data = $this->getFinStateList($ticker, $dimension, $extra)->getData();
        //$data = $this->getFinStateList($ticker, $dimension, $extra)->getData();
        $ticker_category = $data['ticker']['tkr_category'];
/*
echo '<pre>'; print_r($data['ticker']);
if($ticker == 'AMZN') {
    echo '<pre>';
    print_r($data['sepdata']);
}
http://us153dev.datahero.co.kr/index.php/api/getVChart/BABA/MRY/valuation/pricebps
*/
        $data = $this->common->getDataByPK($data['findata'], 'sf1_datekey');

//echo '<pre>'; print_r($data);

        $result = $map[$vchart_type][$chart_indicator];
        $result['data'] = array();
/*
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRT/profit/salesincome
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/profit/margin
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRQ/profit/cor
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/profit/rnd

        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/safety/debtcr
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/safety/borrow
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/safety/opintexp
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/safety/intexpcoverage
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/safety/debtcost

        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/structure/assetstructure
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/structure/profitaccum
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/structure/dividend
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/structure/payout

        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/efficiency/roepbr
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/efficiency/dupont
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/efficiency/roaroeroic
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/efficiency/turnoverdays
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/efficiency/ccc

        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/cashflow/cashflow
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/cashflow/freecashflow
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/cashflow/fcfonrevenue

        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/per
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/priceeps
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/pbr
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/pricebps
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/pcr
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/pricecps
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/psr
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/pricesps
        http://test2.quandl2.hamt.kr/test/vchart/amzn/MRY/valuation/evebitda
*/

        //2020.08.26 변경 if(strtoupper($ticker_category)=='ADR' || strtoupper($ticker_category) =='ADR PRIMARY' || strtoupper($ticker_category)=='CANADIAN' || strtoupper($ticker_category)=='CANADIAN PRIMARY') {
		if( strstr(strtoupper($ticker_category), 'ADR') || strstr(strtoupper($ticker_category), 'CANADIAN') ) {
  
            $sf1_opinc = 'sf1_opinc_ori';
            $sf1_netinc = 'sf1_netinc_ori';
        }
        else {
            $sf1_opinc = 'sf1_opinc';
            $sf1_netinc = 'sf1_netinc';
        }

        if($vchart_type == 'profit') {
            if($chart_indicator == 'salesincome') {
                $result['data']['L1_column_revenue'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_revenue');
                $result['data']['R1_line_opinc'] = $this->common->array2Map($data, 'sf1_datekey', $sf1_opinc);
                $result['data']['R2_line_netinc'] = $this->common->array2Map($data, 'sf1_datekey', $sf1_netinc);
            }
            else if($chart_indicator == 'margin') {
                $result['data']['L1_line_opmargin'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_opmargin');
                $result['data']['L2_line_netmargin'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_netmargin');
            }
            else if($chart_indicator == 'cor') {
                $result['data']['L1_line_costrevenueratio'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_costrevenueratio');
                $result['data']['L2_line_opexratio'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_opexratio');
            }
            else if($chart_indicator == 'rnd') {
                $result['data']['L1_column_rnd'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_rnd');
                $result['data']['R1_line_rndratio'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_rndratio');
            }
        }
        else if($vchart_type == 'safety') {
            if($chart_indicator == 'debtcr') {
                $result['data']['L1_line_de'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_de');
                $result['data']['R1_line_currentratio'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_currentratio');
            }
            else if($chart_indicator == 'borrow') {
                $result['data']['L1_column_debt'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_debt');
                $result['data']['R1_line_debtassets'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_debtassets');
            }
            else if($chart_indicator == 'opintexp') {
                $result['data']['L1_line_opinc'] = $this->common->array2Map($data, 'sf1_datekey',  $sf1_opinc);
                $result['data']['R1_line_intexp'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_intexp');
            }
            else if($chart_indicator == 'intexpcoverage') {
                $result['data']['L1_line_intexpcoverage'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_intexpcoverage');
            }
            else if($chart_indicator == 'debtcost') {
                $result['data']['L1_column_debt'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_debt');
                $result['data']['R1_line_intexpratio'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_intexpratio');
            }
        }
        else if($vchart_type == 'structure') {
            if($chart_indicator == 'assetstructure') {
                $result['data']['L1_line_liabilitiesnc_equity'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_liabilitiesnc_equity');
                $result['data']['L2_line_equity'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_equity');
                $result['data']['L3_line_assetsnc'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_assetsnc');
            }
            else if($chart_indicator == 'profitaccum') {
                $result['data']['L1_line_retearn'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_retearn');
                $result['data']['L2_line_assetsq'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_assetsq');
                $result['data']['L3_line_investmentsnc'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_investmentsnc');
                $result['data']['L4_line_ppnenet'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_ppnenet');
            }
            else if($chart_indicator == 'dividend') {
                $result['data']['L1_column_dps'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_dps');
                $result['data']['R1_line_divyield'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_divyield');
            }
            else if($chart_indicator == 'payout') {
                $result['data']['L1_line_payoutratio'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_payoutratio');
                $result['data']['R1_line_divyield'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_divyield');
            }
        }
        else if($vchart_type == 'efficiency') {
            if($chart_indicator == 'roepbr') {
                $result['data']['L1_line_roe'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_roe');
                $result['data']['R1_line_pb'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_pb');
            }
            else if($chart_indicator == 'dupont') {
                $result['data']['L1_line_ros'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_ros');
                $result['data']['R1_line_assetturnover'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_assetturnover');
                $result['data']['R2_line_assets_equity'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_assets_equity');
            }
            else if($chart_indicator == 'roaroeroic') {
                $result['data']['L1_line_roa'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_roa');
                $result['data']['L2_line_roe'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_roe');
                $result['data']['L3_line_roic'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_roic');
            }
            else if($chart_indicator == 'turnoverdays') {
                $result['data']['L1_line_receiveturnoverdays'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_receiveturnoverdays');
                $result['data']['L2_line_inventoryturnoverdays'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_inventoryturnoverdays');
                $result['data']['L3_line_payableturnoverdays'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_payableturnoverdays');
            }
            else if($chart_indicator == 'ccc') {
                $result['data']['L1_line_cashconversioncycle'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_cashconversioncycle');
            }
        }
        else if($vchart_type == 'cashflow') {
            if($chart_indicator == 'cashflow') {
                $result['data']['L1_line_ncfo'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_ncfo');
                $result['data']['L2_line_ncfi'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_ncfi');
                $result['data']['L3_line_ncff'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_ncff');
            }
            else if($chart_indicator == 'freecashflow') {
                $result['data']['L1_line_fcf'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_fcf');
                $result['data']['L2_line_netinc'] = $this->common->array2Map($data, 'sf1_datekey', $sf1_netinc);
            }
            else if($chart_indicator == 'fcfonrevenue') {
                $result['data']['L1_line_fcfonrevenue'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_fcfonrevenue');
            }
        }
        else if($vchart_type == 'valuation') {
            if($chart_indicator == 'per') {
                $result['data']['L1_line_pe'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_pe');
            }
            else if($chart_indicator == 'priceeps') {
                $result['data']['L1_line_epsdil'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_epsdil');
                $result['data']['R1_line_close'] = $this->_getSepClose($ticker, $result['data']['L1_line_epsdil']);
            }
            else if($chart_indicator == 'pbr') {
                $result['data']['L1_line_pb'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_pb');
            }
            else if($chart_indicator == 'pricebps') {
                $result['data']['L1_line_bvps'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_bvps');
                $result['data']['L2_line_close'] = $this->_getSepClose($ticker, $result['data']['L1_line_bvps']);
            }
            else if($chart_indicator == 'pcr') {
                $result['data']['L1_line_pc'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_pc');
            }
            else if($chart_indicator == 'pricecps') {
                $result['data']['L1_line_cps'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_cps');
                $result['data']['R1_line_close'] = $this->_getSepClose($ticker, $result['data']['L1_line_cps']);
            }
            else if($chart_indicator == 'psr') {
                $result['data']['L1_line_ps'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_ps');
            }
            else if($chart_indicator == 'pricesps') {
                $result['data']['L1_line_sps'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_sps');
                $result['data']['L2_line_close'] =  $this->_getSepClose($ticker, $result['data']['L1_line_sps']);
            }
            else if($chart_indicator == 'evebitda') {
                $result['data']['L1_line_evebitda'] = $this->common->array2Map($data, 'sf1_datekey', 'sf1_evebitda');
            }
        }

        $this->setSuccessResult($result);
        return $this;
    }


    // 필드별 밸류를 코드 규칙에 따라 변환함.
    public function convertRows($rows, $extra=array()) {
        // 파라메터 유효성 검사
        $allow_convert_types = array('us', 'kor');
        if(isset($extra['convert_type'])) {
            $extra['convert_type'] = strtolower($extra['convert_type']);
            if( ! in_array($extra['convert_type'], $allow_convert_types)) {
                unset($extra['convert_type']);
            } else {
                if(isset($extra['unitnum'])) {
                    $extra[$extra['convert_type'].'unitnum'] = $extra['unitnum'];
                    unset($extra['unitnum']);
                }
            }
        }

        $allow_info_file_codes = array_map('strtolower', $this->info_file_codes);
        $allow_info_file_codes[] = 'all';
        if(isset($extra['info_file_code'])) {
            $extra['info_file_code'] = strtolower($extra['info_file_code']);
            if( ! in_array($extra['info_file_code'], $allow_info_file_codes)) {
                unset($extra['info_file_code']);
            }
        }

        $allow_usunitnums = array('달러', '천', '백만');
        if(isset($extra['usunitnum'])) {
            $extra['usunitnum'] = trim($extra['usunitnum']);
            if( ! in_array($extra['usunitnum'], $allow_usunitnums)) {
                unset($extra['usunitnum']);
            }
        }

        $allow_korunitnums = array('억');
        if(isset($extra['korunitnum'])) {
            $extra['korunitnum'] = trim($extra['korunitnum']);
            if( ! in_array($extra['korunitnum'], $allow_korunitnums)) {
                unset($extra['korunitnum']);
            }
        }


        $default_extra = array(
            'convert_type' => 'us',        // us or kor
            'info_file_code' => 'all',     // all or ticker or balancesheet or balancesheet2 or incomestate or cashflow 
                            // or fininvestindi or pricesheet
            'usunitnum' => '백만',        // 달러 or 천 or 백만
            'korunitnum' => '억',        // 억
        );
        $extra = array_merge($default_extra, $extra);

        $info_file_codes = $this->info_file_codes;
        if($extra['info_file_code'] != 'all') {
            $info_file_codes = array(strtolower($extra['info_file_code']));
        }

        $currency = $this->common->getCurrency(); // 현재 환율. 한시간 캐싱된 결과.

        foreach($rows as &$row) {
            if(isset($row['is_converted_row'])) continue;

            foreach($row as $field => $value) {
                if( ! is_numeric($value)) {
                    continue;
                }

                foreach($info_file_codes as $map_key) {
                    $is_matched = false;
                    if(isset($this->loaded_table_info_maps[$map_key][$field]['usunitnumround'])) {
                        $usunitnumround = 0;
                        $isset_usunitnumround = false;
                        if(is_numeric($this->loaded_table_info_maps[$map_key][$field]['usunitnumround'])) {

                            $usunitnumround = $this->loaded_table_info_maps[$map_key][$field]['usunitnumround'];
                            $isset_usunitnumround = true;
                        }

                        if(isset($this->loaded_table_info_maps[$map_key][$field]['unittype'])) {
                            if(trim(strtolower($this->loaded_table_info_maps[$map_key][$field]['unittype'])) == '%') {
                                $value *= 100;
                                $usunitnumround = ($isset_usunitnumround) ? $usunitnumround : 2;
                                $row[$field] = number_format($value, $usunitnumround);
                                $row[$field] .= '%';
                                $is_matched = true;
                                break;
                            }

                            $numeric_unittypes = array(
                                'ratio',
                                'usd',
                                'usd/share',
                                'currency/share',
                                'units',
                                'days',
                            );
                            
                            if($isset_usunitnumround == false && in_array(trim(strtolower($this->loaded_table_info_maps[$map_key][$field]['unittype'])), $numeric_unittypes)) {
                                $isset_usunitnumround = true;
                            }
                        }

                        if($isset_usunitnumround) {
                            $row[$field] = number_format($value, $usunitnumround);
                        }

                        switch($extra['convert_type']) {
                            case 'us' :
                                if(trim($this->loaded_table_info_maps[$map_key][$field]['usunitnum']) == '백만') {
                                    switch(trim($extra['usunitnum'])) {
                                        case '백만' :
                                            $row[$field] = number_format($value / 1000000);
                                            if($value != 0 && abs(floatval(str_replace(',','',$row[$field]))) <= 1) {
                                                $row[$field] = number_format($value / 1000000, 2);
                                            }
                                            break;
                                        case '천' :
                                            $row[$field] = number_format($value / 1000);
                                            if($value != 0 && abs(floatval(str_replace(',','',$row[$field]))) <= 1) {
                                                $row[$field] = number_format($value / 1000, 2);
                                            }
                                        case '달러' :
                                            // 이미 달러
                                            break;
                                    }
                                }
                                break;
                            case 'kor' :
                                if(@trim($this->loaded_table_info_maps[$map_key][$field]['korunitnum']) == '억') {
                                    switch(trim($extra['korunitnum'])) {
                                        case '억' :
                                            $row[$field] = number_format($value * $currency / 100000000);
                                            break;
                                    }
                                }
                                break;
                        }
                        $is_matched = true;
                    }
                    if($is_matched) {
                        break;
                    } else if(is_numeric($value) && strpos($value, '.') !== false && strlen(array_pop(explode('.', $value))) > 2) {
                        // 소수점 3자리 이상 숫자는 2자리로 보이게 처리
                        $row[$field] = number_format($value, 2);
                    }
                }
            }
            if(is_array($row)) {
                $row['is_converted_row'] = true;
            } else {
                // 0 ?
            }
        }
        return $rows;
    }

    // @ 한 종목의 정보 비율 리스트
    public function getFinStateRatioList($tic, $dimension='MRY', $extra=array()) {
        $result = $this->getFinStateList($tic, $dimension, $extra)->getData();
        $findatas = $result['findata'];
        $field_calcstr_map['balancesheet'] = $this->getTableMap('balancesheet', 'Indicator', 'ratio');
        $field_calcstr_map['balancesheet2'] = $this->getTableMap('balancesheet2', 'Indicator', 'ratio');
        $field_calcstr_map['incomestate'] = $this->getTableMap('incomestate', 'Indicator', 'ratio');

        $new_findatas = array();

        // 연 분기 별 sf1 데이터들
        foreach($findatas as $findata) { 
            $new_findata = $findata;
            // 각 텝별 라티오 적용 리스트 그룹
            foreach($field_calcstr_map as $field_ratio_map) {
                // 리스트 각 필드별 라티오 적용 룰 리스트
                foreach($field_ratio_map as $f => $ratio) {
                    // 100 곱하는 형태 정의는 떼어내고 플래그 올려놓기
                    if( ! isset($findata[$f])) {
                        if( ! IS_REAL_SERVER) {
                            echo $f.' is not field';exit;
                        }
                        continue;
                    }
                    $ratio = trim(str_replace(' ', '', $ratio));
                    $pow100 = false;
                    if(substr($ratio, -4) == '*100') {
                        $pow100 = true;
                        $ratio = substr($ratio, 0, -4);
                    }


                    // 나누기 수식 대로 연산 수행
                    $fields = explode('/', $ratio);
                    $val = 0;
                    foreach($fields as $idx => $field) {
                        if( ! isset($findata['sf1_'.$field])) {
                            if( ! IS_REAL_SERVER) {
                                echo 'sf1_'.$field.' is not field';exit;
                            }
                            continue;
                        }
                        if($idx == 0) {
                            
                            $val = $findata['sf1_'.$field];
                            continue;
                        }

                        // 0으로 나누려는 케이스
                        if($findata['sf1_'.$field] == 0) continue;

                        $val /= $findata['sf1_'.$field];
                    }


                    if($pow100) {
                        $val *= 100;
                    }
                    $val = number_format($val, 2);
                    $val .= ' %';

                    $new_findata[$f] = $val;
                }
            }
            $new_findatas[] = $new_findata;
        }

        $result['findata'] = $new_findatas;

        $this->setSuccessResult($result);
        return $this;
    }

    // sf1_tb 테이블 셀렉 이후 가공 프로세스를 꼭 태워야 한다. - row 처리기
    // $ticker_row는 $sf1_row[sf1_ticker] 종목의 ticker 테이블 row. tkr_currency 키만 포함되어있으면 된다.
    // 미국달러로 컨버트 필요 row 컨버팅 할지말지 결정 여부로 쓰임.
    public function getSF1RowAfterProcess($ticker_row, $sf1_row, $extra=array()) {
        $row = $sf1_row;

        $row['sf1_costrevenueratio'] = @($row['sf1_cor'] / $row['sf1_revenue']);
        $row['sf1_opexratio'] = @($row['sf1_opex'] / $row['sf1_revenue']);
        $row['sf1_rndratio'] = @($row['sf1_rnd'] / $row['sf1_revenue']);

        $row['sf1_fcfonrevenue'] = @($row['sf1_fcf'] / $row['sf1_revenue']);
        $row['sf1_intexpcoverage'] = @($row['sf1_opinc'] / $row['sf1_intexp']);
        $row['sf1_debtassets'] = @($row['sf1_debt'] / $row['sf1_assets']);

        $row['sf1_intexpratio'] = @($row['sf1_intexp'] / $row['sf1_revenue']);
        $row['sf1_liabilitiesnc_equity'] = @($row['sf1_liabilitiesnc'] + $row['sf1_equity']);
        $row['sf1_assets_equity'] = @($row['sf1_assets'] / $row['sf1_equity']);

        $row['sf1_borrowtoassets'] = @($row['sf1_debt'] / $row['sf1_assets']);
        $row['sf1_intexprevenue'] = @($row['sf1_intexp'] / $row['sf1_revenue']);
        $row['sf1_receiveturnoverdays'] = @(365 / ( $row['sf1_revenue'] / $row['sf1_receivables'] ));
        $row['sf1_inventoryturnoverdays'] = @(365 / ( $row['sf1_cor'] / $row['sf1_inventory'] ));
        //$row['sf1_inventoryturnoverdays'] = @(365 / ( $row['sf1_cor'] / $row['sf1_inventory'] ));
        $row['sf1_netincdis'] *= -1;
        $row['sf1_assetsetc'] = @($row['sf1_assets']-$row['sf1_cashneq']-$row['sf1_investments']-$row['sf1_receivables']-$row['sf1_inventory']-$row['sf1_ppnenet']-$row['sf1_intangibles']-$row['sf1_taxassets']);
        $row['sf1_liabilitiesetc'] = @($row['sf1_liabilities']-$row['sf1_payables']-$row['sf1_debt']-$row['sf1_deferredrev']-$row['sf1_taxliabilities']-$row['sf1_deposits']);
        $row['sf1_cps'] = @($row['sf1_ncfo'] / $row['sf1_shareswa']);
        $row['sf1_pc'] = @($row['sf1_marketcap'] / ($row['sf1_ncfo']/$row['sf1_fxusd']));
        $row['sf1_payableturnoverdays'] = @(365 / ($row['sf1_revenue'] / $row['sf1_payables']));
        $row['sf1_cashconversioncycle'] = @($row['sf1_receiveturnoverdays'] + $row['sf1_inventoryturnoverdays'] - $row['sf1_payableturnoverdays']);
        $row['sf1_opmargin'] = @($row['sf1_opinc'] / $row['sf1_revenue']);
        $row['sf1_assetetc'] = @($row['sf1_assets'] - $row['sf1_cashneq'] - $row['sf1_investmentsc'] - $row['sf1_receivables'] - $row['sf1_inventory'] - $row['sf1_investmentsnc'] - $row['sf1_ppnenet'] - $row['sf1_intangibles'] - $row['sf1_taxassets']);
        $row['sf1_liabilitiecetc'] = @($row['sf1_liabilities'] - $row['sf1_payables'] - $row['sf1_debtc'] - $row['sf1_debtnc'] - $row['sf1_deferredrev'] - $row['sf1_taxliabilities']);


        // 분기 ROE, ROA, ROIC, 자산회전율 0.00% 표시 ==> N/A 처리
        $edit_fields = array(
                'equityavg',    // >sf1_equityavg 가 0 이면 ROE N/A
                'assetsavg',    //>sf1_assetsavg 가 0 이면 ROA, 자산회전율(assetturnover) N/A
                'invcapavg',    //>sf1_invdapavg 가 0이면 ROIC N/A
                'inventory',    //>재고자산(inventory) or 매출채권및기타채권값(receivables) 가 0이면 재고자산회전일수(inventoryturnoverdays), 매출채권회전일수(receiveturnoverdays) N/A 처리
                'intexp',       //>이자비용(intexp) 없는 기업 > 이자보상배수(intexpcoverage), 금융비용비율(intexprevenue) N/A 처리
                'assetsc',      //>sf1_assetsc 또는 sf1_liabilitiesc 0 이면([재무재표2] 사용하는 기업 포함) 유동비율(currentratio) N/A
                'shareswadil',  //> 가중평균희석주식수(shareswadil) 없는 경우 가중평균주식수(shareswa)로 표기



                // 이하 0이면 N/A
                'roe', 
                'roa', 
                'roic', 
                );
        foreach($edit_fields as $na_field) {
            switch($na_field) {
                case 'equityavg' :
                    //>sf1_equityavg 가 0 이면 ROE N/A

                    if($row['sf1_'.$na_field] == 0) {
                        $row['sf1_roe'] = 'N/A';
                    }
                    break;
                case 'assetsavg' :
                    //>sf1_assetsavg 가 0 이면 ROA, 자산회전율(assetturnover) N/A
                    if($row['sf1_'.$na_field] == 0) {
                        $row['sf1_roa'] = 'N/A';
                        $row['sf1_assetturnover'] = 'N/A';
                    }
                    break;
                case 'invcapavg' :
                    //>sf1_invdapavg 가 0이면 ROIC N/A
                    if($row['sf1_'.$na_field] == 0) {
                        $row['sf1_roic'] = 'N/A';
                    }
                    break;
                case 'inventory' :
                    //>재고자산(inventory), 매출채권및기타채권값(receivables) 가 0이면 재고자산회전일수(inventoryturnoverdays), 매출채권회전일수(receiveturnoverdays) N/A 처리
                    if($row['sf1_inventory'] == 0 || $row['sf1_receivables'] == 0) {
                        $row['sf1_inventoryturnoverdays'] = 'N/A';
                        $row['sf1_receiveturnoverdays'] = 'N/A';
                    }
                    break;
                case 'intexp' :
                    //>이자비용(intexp) 없는 기업 > 이자보상배수(intexpcoverage), 금융비용비율(intexprevenue) N/A 처리
                    if($row['sf1_'.$na_field] == 0) {
                        $row['sf1_intexpcoverage'] = 'N/A';
                        $row['sf1_intexprevenue'] = 'N/A';
                    }
                    break;
                case 'assetsc' :
                    //>sf1_assetsc 또는 sf1_liabilitiesc 0 이면([재무재표2] 사용하는 기업 포함) 유동비율(currentratio) N/A
                    if($row['sf1_assetsc'] == 0 || $row['sf1_liabilitiesc'] == 0) {
                        $row['sf1_currentratio'] = 'N/A';
                    }
                    break;
                case 'shareswadil' :
                    //> 가중평균희석주식수(shareswadil) 없는 경우 가중평균주식수(shareswa)로 표기
                    if($row['sf1_shareswadil'] == 0) {
                        $row['sf1_shareswadil'] = $row['sf1_shareswa'];
                    }
                    break;
                case 'equity' :
                    //> 가중평균희석주식수(shareswadil) 없는 경우 가중평균주식수(shareswa)로 표기
                    if($row['sf1_shareswadil'] == 0) {
                        $row['sf1_shareswadil'] = $row['sf1_shareswa'];
                    }
                    break;
                default :
                    if($row['sf1_'.$na_field] == 0) {
                        $row['sf1_'.$na_field] = 'N/A';
                    }
            }
        }

        // USD 가 아니면 epsdil, bvps, cps, fcfps, opinc, netinc 가 환율 계산 적용을 해야한다.
        // 검색->재무제표일 경우 SKIP(19.8.20)

        if( isset($extra['func_name']) ) {
            if( $extra['func_name'] != 'financials' ) {
                $row = $this->convert_to_usd($ticker_row, $row);
            }        
        }
        else {
            $row = $this->convert_to_usd($ticker_row, $row);
        }

        return $row;
    }

    private function _getSepClose($ticker, $arr_row=array()) {
        $arr_item = array();
        foreach($arr_row as $nKey => $nVal) {
            $arr_seplist = array();
            $arr_seplist = $this->sep_tb_model->getVchartList($ticker, $nKey);
            if($arr_seplist[0]['sep_close'])
                $arr_item[$nKey] = $arr_seplist[0]['sep_close'];
            else 
                $arr_item[$nKey] = '0';
            //$arr_item[$arr_seplist[0]['sep_date']] = $arr_seplist[0]['sep_close'];
        }
        return $arr_item;    
    }


    // sf1_tb 테이블 셀렉 이후 가공 프로세스를 꼭 태워야 한다. - list 처리기
    public function getSF1ListAfterProcess_bicchart($get_list_result, $extra=array()) {
        
        $data_group = $get_list_result;
        if(sizeof(array_filter($data_group)) <= 0) {
            return $data_group;
        }

        $ticker_codes = array_keys($this->common->getDataByPk($data_group, 'sf1_ticker'));
        $ticker_params = array();
        $ticker_params['in']['tkr_ticker'] = $ticker_codes;
        $ticker_extra = array();
        $ticker_extra['order_by'] = '';
        $ticker_extra['fields'] = 'tkr_ticker, tkr_currency';
        //$ticker_extra['cache_sec'] = 3600;
        $ticker_extra['slavedb'] = true;
        $ticker_map = $this->common->getDataByPk($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');

        foreach($data_group as &$row) {
            if(sizeof($row) <= 0) continue;
            $row = $this->getSF1RowAfterProcess($ticker_map[$row['sf1_ticker']], $row, $extra);
        }
        return $data_group;
    }


    // sf1_tb 테이블 셀렉 이후 가공 프로세스를 꼭 태워야 한다. - list 처리기
    public function getSF1ListAfterProcess($get_list_result, $extra=array()) {
        
        $data_group = $get_list_result;
        if(sizeof(array_filter($data_group)) <= 0) {
            return $data_group;
        }

        $ticker_codes = array_keys($this->common->getDataByPk($data_group, 'sf1_ticker'));
        $ticker_params = array();
        $ticker_params['in']['tkr_ticker'] = $ticker_codes;
        $ticker_extra = array();
        $ticker_extra['order_by'] = '';
        $ticker_extra['fields'] = 'tkr_ticker, tkr_currency';
        $ticker_extra['cache_sec'] = 3600;
        $ticker_extra['slavedb'] = true;
        $ticker_map = $this->common->getDataByPk($this->ticker_tb_model->getList($ticker_params, $ticker_extra)->getData(), 'tkr_ticker');

        foreach($data_group as &$row) {
            if(sizeof($row) <= 0) continue;
            $row = $this->getSF1RowAfterProcess($ticker_map[$row['sf1_ticker']], $row, $extra);
        }
        return $data_group;
    }

    // @ 한 종목의 정보 리스트
    public function getFinStateList($tic, $dimension='MRY', $extra=array()) {

        $default_params = array(
            'column_date_format' => '%Y.%m.%d',
            'with_summary' => false,
            'func_name' => '',
            'limit' => ''
        );

		$bicchart = $extra['bicchart'];
		$more = $extra['more'];
        if(isset($extra['sDate'])) 
            $sDate = $extra['sDate'];
        else 
            $sDate = '';

        if(isset($extra['eDate'])) 
            $eDate = $extra['eDate'];
        else 
            $eDate = '';

        foreach($extra as $k => $v) {
            if(isset($default_params[$k])) {
                $default_params[$k] = $v;
            }
        }
        
        if( ! is_numeric($default_params['limit']) || intval($default_params['limit']) <= 0) {
            unset($default_params['limit']);
        }
        $column_date_format = $default_params['column_date_format'];

        $params = array();
        $params['=']['tkr_table'] = 'SF1';
        $params['=']['tkr_ticker'] = $tic;
        $params['=']['tkr_isdelisted'] = 'N';

		if($bicchart) {
			$extra = array(
				'slavedb' => true,
			);
		}
		else {
			$extra = array(
				'slavedb' => true,
				'cache_sec' => 3600*24,
			);
		}

        $ticker = array_shift($this->ticker_tb_model->getList($params, $extra)->getData());

        if( !isset($ticker) || $tdata['tkr_isdelisted'] == 'Y') {
            $this->setErrorResult('[E01] 티커 코드를 확인하세요.');
            return $this;
        }

/*****
        if( ! $this->ticker_tb_model->get(array(
            'tkr_table' => 'SF1', 
            'tkr_ticker' => $tic, 
            'tkr_isdelisted ' => 'N' 
            ))->isSuccess()) {
            // todo. permaticker 다른 ticker코드 중복 제거
            //echo $this->ticker_tb_model->getLastQuery();
            $this->setErrorResult('[E01] 티커 코드를 확인하세요.');
            return $this;
        }
*****/
        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
            $dimension = 'MRY';
        }
        //$ticker = $this->ticker_tb_model->getData();

        //  ticker table 의 category 값이 ADR, ADR Primary, Canadian, Canadian Primary 중 하나고 Currency 값이 USD 아닌 경우
        //  컨버팅 하여 뿌려야 하는 필드들이 있다.  (epsdil, bvps, cps, fcfps, opinc, netinc)
        //  간단한 bool값으로 필요성을 체크할 수 있도록 플래그값 추가함.
        $ticker['convert_to_usd'] = $this->is_convert_to_usd($ticker);

        $last_mry = array(array());
        $last_mrt = array(array());
        $last_mrq = array(array());


        if($default_params['with_summary'] == true) {

            foreach(array('MRY', 'MRT', 'MRQ') as $dim) {
                $params = array();
                $params['=']['sf1_ticker'] = $tic;
                $params['=']['sf1_dimension'] = $dim;
                $params['>=']['sf1_reportperiod'] = (date('Y')-10).'-01-01';

                $extra = array();
                $extra['fields'] = array(
                        '*',
                        'date_format(sf1_reportperiod, "%Y.%m.%d") as sf1_datekey',
                        );
                $extra['order_by'] = 'sf1_datekey desc';
				if($more=='Y') {
	                $extra['limit'] = '10';
				}
				else if($more=='T') {
	                $extra['limit'] = '20';
				}
				else {
	                $extra['limit'] = '5';
				}
                $extra['slavedb'] = true;
				if(!$bicchart) {
	                $extra['cache_sec'] = 3600*12;
				}
                ${'last_'.strtolower($dim)} = $this->sf1_tb_model->getList($params, $extra)->getData();
            }
        }

        $params = array();
        $params['=']['sf1_ticker'] = $tic;
        $params['=']['sf1_dimension'] = $dimension;

        if( $sDate != "" && $eDate != "" ) {
            $syear = substr($sDate, 0, 4);
            $smonth = substr($sDate, 5, 2);
            $sday = substr($sDate, 7, 2);

            $eyear = substr($eDate, 0, 4);
            $emonth = substr($eDate, 5, 2);
            $eday = substr($eDate, 7, 2);

            $params['>=']['sf1_reportperiod'] = $syear.'-'.$smonth.'-'.$sday;
            $params['<=']['sf1_reportperiod'] = $eyear.'-'.$emonth.'-'.$eday;
        }
        else 
            $params['>=']['sf1_reportperiod'] = (date('Y')-10).'-01-01';

        $extra = array();
        $extra['fields'] = array(
        '*',
        'date_format(sf1_reportperiod, "%Y.%m.%d") as sf1_datekey',
        );
        $extra['order_by'] = 'sf1_datekey desc';
        if(isset($default_params['limit'])) {
            $extra['limit'] = $default_params['limit'];
        }

		if(!$bicchart) {
	        $extra['cache_sec'] = 3600*12;
		}
        $extra['slavedb'] = true;

        $data = $this->sf1_tb_model->getList($params, $extra)->getData();

        foreach(array('data', 'last_mry', 'last_mrq', 'last_mrt') as $data_group_str) {
            // create custom fields
			if($bicchart) {
	            ${$data_group_str} = $this->getSF1ListAfterProcess_bicchart(${$data_group_str}, $default_params);
			}
			else {
	            ${$data_group_str} = $this->getSF1ListAfterProcess(${$data_group_str}, $default_params);
			}
        }

		if($bicchart) {
	        $sep_list = $this->sep_tb_model->getPriceHistory_bicchart($tic);
		}
		else {
	        $sep_list = $this->sep_tb_model->getPriceHistory($tic);
		}

        $result = array(
            'findata' => $data,
            'ticker' => $ticker,
            'sepdata' => $sep_list,
            'last_mry' => $last_mry,
            'last_mrt' => $last_mrt,
            'last_mrq' => isset($last_mrq[0]) ? $last_mrq : $last_mrt, // baba 등 mrq가 없는 종목이 있다. 예외처리
            'balancesheet_type' => @$this->sf1_tb_model->getBalancesheetType($ticker, $data[0]),
        );

        $this->setSuccessResult($result);
        return $this;
    }

    public function getFinStateList_bicchart($tic, $dimension='MRY', $extra=array()) {

        $default_params = array(
            'column_date_format' => '%Y.%m.%d',
            'with_summary' => false,
            'func_name' => '',
            'limit' => ''
        );

        if(isset($extra['sDate'])) 
            $sDate = $extra['sDate'];
        else 
            $sDate = '';

        if(isset($extra['eDate'])) 
            $eDate = $extra['eDate'];
        else 
            $eDate = '';

        foreach($extra as $k => $v) {
            if(isset($default_params[$k])) {
                $default_params[$k] = $v;
            }
        }
        
        if( ! is_numeric($default_params['limit']) || intval($default_params['limit']) <= 0) {
            unset($default_params['limit']);
        }
        $column_date_format = $default_params['column_date_format'];

        $params = array();
        $params['=']['tkr_table'] = 'SF1';
        $params['=']['tkr_ticker'] = $tic;
        $params['=']['tkr_isdelisted'] = 'N';

        $extra = array(
            'slavedb' => true,
            //'cache_sec' => 3600*24,
        );

        $ticker = array_shift($this->ticker_tb_model->getList($params, $extra)->getData());

        if( !isset($ticker) || $tdata['tkr_isdelisted'] == 'Y') {
            $this->setErrorResult('[E01] 티커 코드를 확인하세요.');
            return $this;
        }

/*****
        if( ! $this->ticker_tb_model->get(array(
            'tkr_table' => 'SF1', 
            'tkr_ticker' => $tic, 
            'tkr_isdelisted ' => 'N' 
            ))->isSuccess()) {
            // todo. permaticker 다른 ticker코드 중복 제거
            //echo $this->ticker_tb_model->getLastQuery();
            $this->setErrorResult('[E01] 티커 코드를 확인하세요.');
            return $this;
        }
*****/
        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
            $dimension = 'MRY';
        }
        //$ticker = $this->ticker_tb_model->getData();

        //  ticker table 의 category 값이 ADR, ADR Primary, Canadian, Canadian Primary 중 하나고 Currency 값이 USD 아닌 경우
        //  컨버팅 하여 뿌려야 하는 필드들이 있다.  (epsdil, bvps, cps, fcfps, opinc, netinc)
        //  간단한 bool값으로 필요성을 체크할 수 있도록 플래그값 추가함.
        $ticker['convert_to_usd'] = $this->is_convert_to_usd($ticker);

        $last_mry = array(array());
        $last_mrt = array(array());
        $last_mrq = array(array());


        if($default_params['with_summary'] == true) {

            foreach(array('MRY', 'MRT', 'MRQ') as $dim) {
                $params = array();
                $params['=']['sf1_ticker'] = $tic;
                $params['=']['sf1_dimension'] = $dim;
                $params['>=']['sf1_reportperiod'] = (date('Y')-10).'-01-01';

                $extra = array();
                $extra['fields'] = array(
                        '*',
                        'date_format(sf1_reportperiod, "%Y.%m.%d") as sf1_datekey',
                        );
                $extra['order_by'] = 'sf1_datekey desc';
                $extra['limit'] = '5';
                $extra['slavedb'] = true;
                //$extra['cache_sec'] = 3600*12;
                ${'last_'.strtolower($dim)} = $this->sf1_tb_model->getList($params, $extra)->getData();
            }
        }

        $params = array();
        $params['=']['sf1_ticker'] = $tic;
        $params['=']['sf1_dimension'] = $dimension;

        if( $sDate != "" && $eDate != "" ) {
            $syear = substr($sDate, 0, 4);
            $smonth = substr($sDate, 5, 2);
            $sday = substr($sDate, 7, 2);

            $eyear = substr($eDate, 0, 4);
            $emonth = substr($eDate, 5, 2);
            $eday = substr($eDate, 7, 2);

            $params['>=']['sf1_reportperiod'] = $syear.'-'.$smonth.'-'.$sday;
            $params['<=']['sf1_reportperiod'] = $eyear.'-'.$emonth.'-'.$eday;
        }
        else 
            $params['>=']['sf1_reportperiod'] = (date('Y')-10).'-01-01';

        $extra = array();
        $extra['fields'] = array(
        '*',
        'date_format(sf1_reportperiod, "%Y.%m.%d") as sf1_datekey',
        );
        $extra['order_by'] = 'sf1_datekey desc';
        if(isset($default_params['limit'])) {
            $extra['limit'] = $default_params['limit'];
        }
        //$extra['cache_sec'] = 3600*12;
        $extra['slavedb'] = true;

        $data = $this->sf1_tb_model->getList($params, $extra)->getData();

        foreach(array('data', 'last_mry', 'last_mrq', 'last_mrt') as $data_group_str) {
            // create custom fields
            ${$data_group_str} = $this->getSF1ListAfterProcess_bicchart(${$data_group_str}, $default_params);
        }

        $sep_list = $this->sep_tb_model->getPriceHistory_bicchart($tic);


        $result = array(
            'findata' => $data,
            'ticker' => $ticker,
            'sepdata' => $sep_list,
            'last_mry' => $last_mry,
            'last_mrt' => $last_mrt,
            'last_mrq' => isset($last_mrq[0]) ? $last_mrq : $last_mrt, // baba 등 mrq가 없는 종목이 있다. 예외처리
            'balancesheet_type' => @$this->sf1_tb_model->getBalancesheetType($ticker, $data[0]),
        );

        $this->setSuccessResult($result);
        return $this;
    }

    // USD단위가 아닌 종목이라서 USD로 컨버팅이 필요한가?
    public function is_convert_to_usd($ticker) {
        if( ! isset($ticker['tkr_currency'])) print_r($ticker);
        return ! ($ticker['tkr_currency'] == 'USD');
    }

    // 경쟁사, 상위기업 sf1_tb_model->getCompetitor(), getIndustoryTop() 에서 호출하고있음. 이땐 데일리까지 다 넘어옴.
    public function convert_list_to_usd($sf1_rows_with_ticker) {
        foreach($sf1_rows_with_ticker as &$sf1_row) {
            if( ! isset($sf1_row['tkr_currency'])) {
                if( ! IS_REAL_SERVER) {
                    echo 'historylib.convert_list_to_usd 파라메터 오류. tkr_currency 없는 row';
                    print_r($sf1_row);exit;
                }
            }
            $sf1_row = $this->convert_to_usd($sf1_row, $sf1_row);
        }
        return $sf1_rows_with_ticker;
    }
    public function convert_to_usd($ticker, $sf1_row) {
        $row = $sf1_row;
        if(isset($row['is_converted_to_usd'])) {
            if( ! IS_REAL_SERVER) {
                echo 'historylib.convert_to_usd : 이미 USD 로 컨버팅 한 row를 또 USD 컨버팅 시도함! 로직 순차 확인!!';exit;
            }
            return $row;
        }

        $row['is_converted_to_usd'] = false;

        // 경쟁사 데이터 등에선 daily도 join된 채로 넘어옴.
        //자본총계[equity] (-)인 경우, [sf1_roe], [dly_pb], [부채비율(de)] "N/A" 처리
        if($row['sf1_equity'] <= 0) {
            $row['sf1_roe'] = 'N/A';
            $row['sf1_de'] = 'N/A';
            $row['sf1_pb'] = 'N/A';
            if(isset($row['dly_pb'])) $row['dly_pb'] = 'N/A';
        }

        // dly_marketcap은 million USD 이므로 100만 곱해주기
        if(isset($row['dly_marketcap'])) {
            $row['dly_marketcap'] *= 1000000;
        }


        if($this->is_convert_to_usd($ticker) == true) {
            $row['is_converted_to_usd'] = true;
            $row['sf1_netinc_ori'] = $row['sf1_netinc'];
            $row['sf1_opinc_ori'] = $row['sf1_opinc'];
            $row['sf1_ebitda_ori'] = $row['sf1_ebitda'];
            if(isset($row['sf1_epsdil'])) $row['sf1_epsdil'] = $row['sf1_epsusd'];
            if(isset($row['sf1_bvps'])) $row['sf1_bvps'] = @($row['sf1_bvps'] / $row['sf1_fxusd']);
            if(isset($row['sf1_cps'])) $row['sf1_cps'] =  @($row['sf1_cps'] / $row['sf1_fxusd']);
            if(isset($row['sf1_fcfps'])) $row['sf1_fcfps'] =  @($row['sf1_fcfps'] / $row['sf1_fxusd']);
            if(isset($row['sf1_opinc'])) $row['sf1_opinc'] =  @($row['sf1_opinc'] / $row['sf1_fxusd']);
            if(isset($row['sf1_ebitda'])) $row['sf1_ebitda'] =  @($row['sf1_ebitda'] / $row['sf1_fxusd']);
            if(isset($row['sf1_netinc'])) $row['sf1_netinc'] =  $row['sf1_netinccmnusd'];
        }
        else {
            $row['sf1_netinc_ori'] = $row['sf1_netinc'];
            $row['sf1_opinc_ori'] = $row['sf1_opinc'];
            $row['sf1_ebitda_ori'] = $row['sf1_ebitda'];
        }
        return $row;
    }

    // 전년동기 대비 실적
    public function getIncomeGrowthRate($tickers = array()) {
        if( ! is_array($tickers) && is_string($tickers) && strlen($tickers) > 0) {
            $tickers = array(strtoupper($tickers));
        }

        $result = array();
/*
        foreach($tickers as $ticker) {
            $res = $this->getBaseData($ticker, 'MRQ');

            if( ! (isset($res['last_mrq_list_nc']) && sizeof($res['last_mrq_list_nc']) > 0)) {
                $result[$ticker] = array();
                continue;
            }
            $avg_items = array();
            foreach($res['last_mrq_list_nc'] as $sf1_row) {
                $avg_items[] = str_replace(',','',$sf1_row['sf1_netinc']);
                if(sizeof($avg_items) >= 5) {
                    break;
                }
            }

            //$result['rate'][$ticker] = round($avg_items[0] / (array_sum($avg_items) / sizeof($avg_items)) * 100, 2);
            // ((2019년 4분기/2018년 4분기)-1)*100
            $result['rate'][$ticker] = round((($avg_items[0] / $avg_items[4]) - 1) * 100, 2);
            //$result['rate'][$ticker] = round($avg_items[0] / (array_sum($avg_items) / sizeof($avg_items)) * 100, 2);
            $result['incomes'][$ticker] = $avg_items;
        }
*/
        foreach($tickers as $ticker) {
            $res = $this->getBaseData($ticker, 'MRT');

            if( ! (isset($res['last_mrt_list']) && sizeof($res['last_mrt_list']) > 0)) {
                $result[$ticker] = array();
                continue;
            }
            $avg_items = array();
            $arr_lastupdated = array();

			$sf1_netinc = 'sf1_netinc'; //2020.12.28
			if($res['ticker']['tkr_currency'] != 'USD') $sf1_netinc = 'sf1_netinccmnusd'; //2020.12.28

            foreach($res['last_mrq_list'] as $sf1_row) {
                //$avg_items[] = str_replace(',','',$sf1_row['sf1_netinccmnusd']); //2020.12.28
                $avg_items[] = str_replace(',','',$sf1_row[$sf1_netinc]); //2020.12.28

                $arr_lastupdated[] = $sf1_row['sf1_lastupdated'];
                if(sizeof($avg_items) >= 5) {
                    break;
                }
            }

/*
1) 최근 순이익 > 0 and 비교시점 순이익 < 0 : 흑자전환
2) 최근 순이익 < 0 and 비교시점 순이익 < 0 : 적자지속
3) 최근 순이익 < 0 and 비교시점 순이익 > 0 : 적자전환
* 참고로 전년대비 성장률이 현재처럼 나오는 경우는 최근 순이익 > 0 and 비교시점 순이익 > 0 인 경우입니다.    
$avg_items[0] // 최근 순이익
$avg_items[4] // 비교시점 순이익
$rate = sprintf('%.2f', ($curr['sf1_netinccmnusd'] / $before['sf1_netinccmnusd'] -1) * 100);
*/
            $rate = 0;
            if( $avg_items[0] > 0 && $avg_items[4] < 0 ) {
                $rate = 1;
                $str_netinc = '흑자전환';
            }
            else if( $avg_items[0] < 0 && $avg_items[4] < 0 ) {
                $str_netinc = '적자지속';
            }
            else if( $avg_items[0] < 0 && $avg_items[4] > 0 ) {
                $str_netinc = '적자전환';
            }
            else {
                $rate = @sprintf('%.2f', ($avg_items[0] / $avg_items[4] -1) * 100);
                if(!is_numeric($rate)) {
                    $str_netinc = 'N/A';
                }
                else {
                    $str_netinc = $rate.'%';
                }
            }

            $result['rate'][$ticker] = $str_netinc;
            $result['rate_pm'][$ticker] = $rate;
    
            $result['incomes'][$ticker] = $avg_items;
            $result['lastupdated'][$ticker] = $arr_lastupdated[0];
        }

        return $result;
    }

    public function getBaseData($ticker_code, $dimension='MRT', $cell_type='data') {
        $pExtra = array(
            'with_summary' => true
        );

        $dimension = strtoupper($dimension);
        if( ! in_array($dimension, array('MRY', 'MRT', 'MRQ'))) {
            $dimension = 'MRT';
        }

        $cell_type = strtolower($cell_type);
        if( ! in_array($cell_type, array('data', 'ratio'))) {
            $cell_type = 'data';
        }

        if($cell_type == 'data') {
            $pExtra['cache_sec'] = 3600*24;
            $this->getFinStateList($ticker_code, $dimension, $pExtra);
        } else {
            // cell_type == ratio
            $this->getFinStateRatioList($ticker_code, $dimension, $pExtra);
        }

        if( ! $this->historylib->isSuccess()) {
            // ticker 똑같은 퍼마티커만 다른 종목은 실패난다.
            // todo
            //조치가 필요할듯..
            // select tkr_ticker, count(*) cou from ticker_tb where tkr_table = 'SEP' group by tkr_ticker having cou > 1 order by cou desc;
            // 하면 나오는 종목들...
            //echo $this->historylib->getErrorMsg();
            echo serialize(array());
            return;
        }

        $data = $this->getData();
        $data['findata'] = $this->convertRows($data['findata']);

        $data['last_mry_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mry']), 'sf1_datekey');
        $data['last_mrq_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mrq']), 'sf1_datekey');
        $data['last_mrt_list'] = $this->common->getDataByPK($this->historylib->convertRows($data['last_mrt']), 'sf1_datekey');
        
        $data['last_mrq_list_nc'] = $data['last_mrq'];

        $data['last_mry'] = array_shift(array_values($data['last_mry_list']));
        $data['last_mrq'] = array_shift(array_values($data['last_mrq_list']));
        $data['last_mrt'] = array_shift(array_values($data['last_mrt_list']));

        //echo serialize($data);
        return $data;
        //echo serialize($this->stocks->_getBaseData($ticker_code, $dimension, $cell_type, $pExtra));
    }

}
