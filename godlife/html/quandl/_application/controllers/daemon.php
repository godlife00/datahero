<?php 
// Sharadar Quandl Data Sync Daemon 제작
// Hamt. 2019.5.19

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daemon extends CI_Controller {

    private $sync_infos = array();
    private $lastupdated;
    

    public function __construct() {
        set_time_limit(86000);
        ini_set('memory_limit', '2G');
        parent::__construct();
        if( ! $this->input->is_cli_request()) {
            die('cli only');
        }

        $dd=1;
        $min=date('i');
        if($min>20) $dd=3;

        $this->lastupdated = date('Y-m-d', time()-86400*$dd);
        $this->sync_infos = array(
            'ticker' => array(
                'table' => 'ticker_tb',
                'prefix' => 'tkr',
                'uniq' => array('tkr_ticker','tkr_permaticker', 'tkr_table'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/TICKERS.json?lastupdated.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                ),
            'indicator' => array(
                'table' => 'indicator_tb',
                'prefix' => 'idc',
                'uniq' => array('idc_table','idc_indicator'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/INDICATORS.json?api_key='.QDAPI_KEY
                ),
            'sf1' => array(
                'table' => 'sf1_tb',
                'prefix' => 'sf1',
                'uniq' => array('sf1_ticker','sf1_dimension','sf1_datekey','sf1_reportperiod'),
                //'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SF1.json?calendardate.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SF1.json?dimension=MRY,MRT,MRQ&api_key='.QDAPI_KEY
                ),
            'sf2' => array(
                'table' => 'sf2_tb',
                'prefix' => 'sf2',
                'uniq' => array('sf2_ticker','sf2_filingdate','sf2_formtype','sf2_ownername','sf2_rownum'),
                //'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SF2.json?filingdate.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SF2.json?filingdate.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                ),
            'sf3' => array(
                'table' => 'sf3_tb',
                'prefix' => 'sf3',
                'uniq' => array('sf3_ticker','sf3_investorname', 'sf3_securitytype','sf3_calendardate'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SF3.json?calendardate.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                ),
            'sf3a' => array(
                'table' => 'sf3a_tb',
                'prefix' => 'sf3a',
                'uniq' => array('sf3a_ticker','sf3a_calendardate'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SF3A.json?calendardate.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                ),
            'sf3b' => array(
                'table' => 'sf3b_tb',
                'prefix' => 'sf3b',
                'uniq' => array('sf3b_investorname', 'sf3b_calendardate'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SF3B.json?calendardate.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                ),
            'sep' => array(
                'table' => 'sep_tb',
                'prefix' => 'sep',
                'uniq' => array('sep_ticker','sep_date'),
                //'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SEP.json?lastupdated.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY,
                //'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SEP?api_key=b9Q9E2d-D3EMyH7FQ9qE&ticker=AAPL,TSLA&date.gte=1997-12-31&date.lte=2020-08-30',
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/sep.json?date.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY,
                ),
            'daily' => array(
                'table' => 'daily_tb',
                'prefix' => 'dly',
                'uniq' => array('dly_ticker','dly_date'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/daily.json?date.gte='.$this->lastupdated.'&api_key='.QDAPI_KEY
                ),
            'actions' => array(
                'table' => 'actions_tb',
                'prefix' => 'act',
                'uniq' => array('act_date','act_action','act_ticker','act_contraticker'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/ACTIONS.json?api_key='.QDAPI_KEY
                ),
            'sp500' => array(
                'table' => 'sp500_tb',
                'prefix' => 'sp5',
                'uniq' => array('sp5_ticker','sp5_date','sp5_action'),
                'json_url' => 'https://www.quandl.com/api/v3/datatables/SHARADAR/SP500.json?api_key='.QDAPI_KEY
                ),
        );

        $this->load->library(array(
            'HamtCrawler'
        ));
        $this->load->model(array(
            DBNAME.'/ticker_tb_model',
            DBNAME.'/indicator_tb_model',
            DBNAME.'/sf1_tb_model',
            DBNAME.'/sf2_tb_model',
            DBNAME.'/sf3_tb_model',
            DBNAME.'/sf3a_tb_model',
            DBNAME.'/sf3b_tb_model',
            DBNAME.'/sep_tb_model',
            DBNAME.'/company_tb_model',
            DBNAME.'/daily_tb_model',
            DBNAME.'/actions_tb_model',
            DBNAME.'/sp500_tb_model',
        ));

        $this->db->save_queries = FALSE;
    }

    public function sync_company() {
        if (($handle = fopen(WEBDATA."/company.csv", "r")) !== FALSE) {
            $idx = 0;
            $keys = array();
            
            $title_key_map = array(
                ''         => 'pass',
                'exchange'    => 'cp_exchange',
                'ticker'    => 'cp_ticker',
                'usname'    => 'cp_usname',
                'korname'    => 'cp_korname',
                '확인용'    => 'cp_is_confirmed',
                '다우30'    => 'cp_is_dow30',
                '나스닥100'    => 'cp_is_nasdaq100',
                'S&P500'    => 'cp_is_snp500',
                'marketcap'    => 'pass',
                'breifcompanyoverview'    => 'cp_short_description',
                'companyoverview'    => 'cp_description',
            );
            while (($row = fgetcsv($handle, 10240, "\t")) !== FALSE) {
                $idx++;
                echo $idx.' ) ';
                if($idx == 1) {
                    foreach($row as $field) {
                        if(! isset($title_key_map[$field])) {
                            echo '"'.$field.'"undefined key!';exit;
                        }
                        $keys[] = $title_key_map[$field];
                    }
                    echo "title init!\n";
                    continue;
                }

                $insert_param = array_combine($keys, $row);

                $insert_param['cp_is_confirmed'] = (strlen(trim($insert_param['cp_is_confirmed'])) > 0) ? 'YES' : 'NO';
                $insert_param['cp_is_dow30'] = (strlen(trim($insert_param['cp_is_dow30'])) > 0) ? 'YES' : 'NO';
                $insert_param['cp_is_nasdaq100'] = (strlen(trim($insert_param['cp_is_nasdaq100'])) > 0) ? 'YES' : 'NO';
                $insert_param['cp_is_snp500'] = (strlen(trim($insert_param['cp_is_snp500'])) > 0) ? 'YES' : 'NO';

                echo ' '.$insert_param['cp_ticker'].' ....';

                if($this->company_tb_model->get(array('cp_ticker' => $insert_param['cp_ticker']))->isSuccess()) {
                    // update
                    echo '.. Update..';
                    $db_row = $this->company_tb_model->getData();

                    $insert_param['cp_updated_at'] = date('Y-m-d H:i:s');
                    if($this->company_tb_model->doUpdate($db_row['cp_id'], $insert_param)->isSuccess() == false) {
                        echo $this->company_tb_model->getErrorMsg()."\n";
                        sleep(2);
                        continue;
                    }
                    echo "OK!\n";
                } else {
                    // insert
                    echo '.. Insert..';
                    if($this->company_tb_model->doInsert($insert_param)->isSuccess() == false) {
                        print_r($insert_param);
                        echo $this->company_tb_model->getErrorMsg()."\n";
                        sleep(2);
                        continue;
                    }
                    echo "OK!\n";
                }
            }
        }
    }

    public function sync_bulk_target($sync_info_key, $target_ticker='') {
        /*
           $row = 1;
           if (($handle = fopen("test.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
               $num = count($data);
               echo "<p> $num fields in line $row: <br /></p>\n";
               $row++;
               for ($c=0; $c < $num; $c++) {
                   echo $data[$c] . "<br />\n";
                  }
               }
               fclose($handle);
           }
        */
        $target_tickers = array(
        /*
            'AMZN',
            'AMZN', 
            'WMT', 
            'JNJ', 
            'MSFT', 
            'JPM',
            'DIS',
        */
        );
        if(strlen($target_ticker) > 0) {
            $target_tickers[] = strtoupper($target_ticker);
        }
        $info_key = $sync_info_key;;
        if( ! isset($this->sync_infos[$info_key])) {
            echo $sync_info_key.' key is not valid.'."\n";
            
        }

        $info = $this->sync_infos[$info_key];
        $model_name = $info['table'].'_model';

        $idx = 0;
        $keys = array();
        if( ! is_file(WEBDATA."/{$info_key}.csv")) {
            echo "File not exist!\n";
            exit;
        }
        if (($handle = fopen(WEBDATA."/{$info_key}.csv", "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 10240, ",")) !== FALSE) {
                $idx++;
                echo $idx.' ) ';
                if($idx == 1) {
                    foreach($row as $field) {
                        $keys[] = $info['prefix'].'_'.$field;
                    }
                    echo "title init!";
                    continue;
                }
                if(sizeof($row) != sizeof($keys)) {
                    echo 'row not match! '.sizeof($row).' != '.sizeof($keys)."\n";
                    sleep(2);
                    continue;
                }
                $row = array_combine($keys, $row);
                $row = $this->sync_filter($info['table'], $row);
                if(isset($row[$info['prefix'].'_ticker'])) {
                    if(sizeof($target_tickers) > 0 && !in_array($row[$info['prefix'].'_ticker'], $target_tickers)) {
                        echo 'pass'."\n";;
                        continue;
                    }
                    echo $row[$info['prefix'].'_ticker'].' ';
                }

                $uniq_search = array();
                foreach($info['uniq'] as $uf) {
                    $uniq_search[$uf] = $row[$uf];
                }
                if($this->$model_name->get($uniq_search)->isSuccess()) {
                    // Update
                    echo 'Update....';
                    $dbrow = $this->$model_name->getData();
                    $pk = $dbrow[$info['prefix'].'_id'];

                    if( ! $this->$model_name->doUpdate($pk, $row)->isSuccess()) {
                        echo 'Fail!!'."\n";
                        sleep(2);
                        continue;
                    }
                    echo 'Success'."\n";
                } else {
                    // Insert
                    echo 'Insert....';
                    if( ! $this->$model_name->doInsert($row)->isSuccess()) {
                        echo 'Fail! - ';
                        echo $this->$model_name->getErrorMsg()."\n";
                        sleep(2);
                        continue;
                    }
                    echo 'Success'."\n";
                }
            }
            fclose($handle);
        }
        
    }
    public function sync_target($sync_key) {
        set_time_limit(-1);
        ini_set('memory_limit', -1);

        $sync_infos = $this->sync_infos;

        if( ! isset($sync_infos[$sync_key])) {
            echo $sync_key . 'is not defined.'."\n";
            return;
        }

        $sync_info = $sync_infos[$sync_key];
        list($table, $prefix, $uniq_keys, $url) = array_values($sync_info);
        $next_cursor_id = $this->sync_table($table, $prefix, $uniq_keys, $url);
        while(trim($next_cursor_id)) {
            $next_cursor_id = $this->sync_table($table, $prefix, $uniq_keys, $url.'&qopts.cursor_id='.$next_cursor_id);
        }
        $this->delete_all_query_cache();
    }

    private function sync_table($table, $prefix, $uniq_keys, $json_url) {
        /*
        $prefix = 'tkr';
        $table = 'ticker_tb';
        $uniq_keys = array('tkr_ticker');
        $json_url = 'https://www.quandl.com/api/v3/datatables/SHARADAR/TICKERS.json?api_key=qNazFB9svr_C6rxNqmW8';
        */
        for($i = 0 ; $i < 10 ; $i++) {
            $orig_data = $this->hamtcrawler->getBody($json_url);
            $datas = json_decode($orig_data, true);
            if( ! is_array($datas)) {
                echo $json_url.' ';
                echo 'datas is not array!! '."\n";
                //echo 'Response IS >> '.$orig_data."\n\n";
                sleep(15);
                continue;
            }
            break;
        }
        if($i >= 10) {
            'Giveup...';
            exit;
        }
        echo "\n".'next_cursor_id : '.$datas['meta']['next_cursor_id']."\n";
        sleep(1);
        $newest_data = $this->get_key_value($datas, $prefix);
        print_r($this->sync_filter($table, $newest_data[0]));
        $total_count = sizeof($newest_data);
        foreach($newest_data as $idx => $row) {
            echo $table.' [ '.($idx+1).' / '.$total_count.' ] ';

            $uniq_filter = array();
            foreach($uniq_keys as $f) {
                $uniq_filter[$f] = $row[$f];
            }

            $row = $this->sync_filter($table, $row);

            if($this->{$table.'_model'}->get($uniq_filter)->isSuccess()) {
                // update
                $row[$prefix.'_updated_at'] = date('Y-m-d H:i:s');

                $dbrow = $this->{$table.'_model'}->getData();
                $pk = $dbrow[$prefix.'_id'];

                echo $pk.' pk Update....';

                if( ! $this->{$table.'_model'}->doUpdate($pk, $row)->isSuccess()) {
                    echo " Fail - ";
                    echo $this->stock_tb_model->getErrorMsg()."\n";
                    echo $this->{$table.'_model'}->getLastQuery()."\n";
                    sleep(5);
                    continue;
                }
                echo "OK\n";
            } else {
                echo 'Insert ';
                foreach($uniq_keys as $k) {
                    echo $k.':'.$row[$k].' ';
                }

                if( ! $this->{$table.'_model'}->doInsert($row)->isSuccess()) {
                    echo 'Fail - '.$this->{$table.'_model'}->getErrorMsg()."\n";
                    print_r($row);
                    sleep(5);
                    continue;
                }

                // ticker_tb 신규상장종목 등록시 company_tb에도 insert
                /*
                신규상장 종목 조건 :
                    $table == 'ticker_tb'
                    && $row['tkr_table'] = 'SF1' 
                    && $row['tkr_firstadded'] > date('Y-m-d', time()-86400*3) (3일전)
                */
                if( 
                    $table == 'ticker_tb'
                    && $row['tkr_table'] = 'SF1' 
                    && $row['tkr_firstadded'] > date('Y-m-d', time()-86400*3)
                ) {

                    if($this->company_tb_model->get(array('cp_ticker' => $row['tkr_ticker']))->isSuccess()) {
                        // update
                        echo '.. Update..';
                        $db_row = $this->company_tb_model->getData();

                        $insert_param['cp_updated_at'] = date('Y-m-d H:i:s');
                        if($this->company_tb_model->doUpdate($db_row['cp_id'], $insert_param)->isSuccess() == false) {
                            echo $this->company_tb_model->getErrorMsg()."\n";
                            sleep(2);
                            continue;
                        }
                        echo "update OK!\n";
                    } else {
                        // insert
                        echo '.. Insert..';
                        if($this->company_tb_model->doInsert(array(
                        'cp_exchange' => $row['tkr_exchange'],
                        'cp_ticker' => $row['tkr_ticker'],
                        'cp_usname' => $row['tkr_name'],
                        'cp_korname' => '신규 상장 종목',
                        'cp_is_confirmed' => 'NO',
                    ))->isSuccess() == false) {
                            print_r($row['tkr_ticker']);
                            echo $this->company_tb_model->getErrorMsg()."\n";
                            sleep(2);
                            continue;
                        }
                        echo "insert OK!\n";
                    }
/*                    
                    // 3일간 insert 시도 할 것이나 cp_ticker UNIQUE Field로 처음만 성공할거다.
                    if( ! $this->company_tb_model->doInsert(array(
                        'cp_exchange' => $row['tkr_exchange'],
                        'cp_ticker' => $row['tkr_ticker'],
                        'cp_usname' => $row['tkr_name'],
                        'cp_korname' => '신규 상장 종목',
                        'cp_is_confirmed' => 'NO',
                    ))->isSuccess() ) {
                        echo $this->company_tb_model->getErrorMsg()."\n";
                        sleep(2);
                        continue;
                    }
*/
                }
                echo "OK\n";
            }
        }
        echo "\n".'next_cursor_id : '.$datas['meta']['next_cursor_id']."\n";
        return $datas['meta']['next_cursor_id'];
    }

    // 자체 필드 만들어 내기 위한 필터
    private function sync_filter($table, $row) {
        switch(strtolower($table)) {
            case 'sf1_tb' :
                // 당좌자산. indicator엔 없는 항목을 만들어냄
                $row['sf1_assetsq'] = $row['sf1_cashneq'] + $row['sf1_investmentsc'] + $row['sf1_receivables'];
                $row['sf1_assetsncetc'] = $row['sf1_assetsnc'] - $row['sf1_investmentsnc'] - $row['sf1_ppnenet'] - $row['sf1_intangibles'] - $row['sf1_taxassets'];
                $row['sf1_liabilitiescetc'] = $row['sf1_liabilitiesc'] - $row['sf1_payables'] - $row['sf1_debtc'];
                $row['sf1_opexetc'] = $row['sf1_opex'] - $row['sf1_sgna'] - $row['sf1_rnd'];
                $row['sf1_liabilitiesncetc'] = $row['sf1_liabilitiesnc'] - $row['sf1_debtnc'] - $row['sf1_deferredrev'] - $row['sf1_taxliabilities'];
                $row['sf1_assetscetc'] = $row['sf1_assetsc'] - $row['sf1_assetsq'] - $row['sf1_inventory'];
                $row['sf1_nonopinc'] = $row['sf1_consolinc'] - $row['sf1_netincdis'] - $row['sf1_opinc'] + $row['sf1_intexp'] + $row['sf1_taxexp'];
                break;
        }
        return $row;
    }
    public function crawl() {

        $json = $this->hamtcrawler->getBody('https://www.quandl.com/api/v3/datatables/SHARADAR/TICKERS.json?api_key=qNazFB9svr_C6rxNqmW8&qopts.cursor_id=djFfMTAwMDFfMTU1MzkzMjczNg==');
        echo $json;
    }
    private function get_key_value($datas, $prefix) {

        if(strlen($prefix) > 0 && substr($prefix, -1, 1) != '_') {
            $prefix .= '_';
        }
        if( ! (isset($datas['datatable']) && isset($datas['datatable']['data']))) {
            echo 'datatable or datatable/data key not found';
            print_r($datas);
            exit;
        }
        $data = $datas['datatable']['data'];
        $columns = $datas['datatable']['columns'];
        if( ! is_array($columns)) {
            echo '$datas["datatable"]["columns"] is not array : ';
            print_r($datas);
            exit;
        }

        $fields = array();
        foreach($columns as $col) {
            $fields[] = $prefix.$col['name'];
        }

        $result = array();
        foreach($data as $idx => $row) {
            if(count($fields) != count($row)) {
                echo '$fields size != $row';
                exit;
            }
            foreach($row as $k => $v) {
                if($v == null) {
                    $row[$k] = '';
                }
            }
            $result[] = array_combine($fields, $row);
        }
        return $result;
    }



    public function test() {
        // TICKERS
        /*
        $datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/TICKERS.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        echo $this->get_create_table_query('ticker_tb', 'tkr', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // Indicator , Description
        $datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/INDICATORS.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        echo $this->get_create_table_query('indicator_tb', 'idc', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        */
        // SF1
        //$datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/SF1.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        //echo $this->get_create_table_query('sf1_tb', 'sf1', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // SF2
        //$datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/SF2.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        //echo $this->get_create_table_query('sf2_tb', 'sf2', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // SF3
        //$datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/SF3.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        //echo $this->get_create_table_query('sf3_tb', 'sf3', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // SF3a
        //$datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/SF3A.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        //echo $this->get_create_table_query('sf3a_tb', 'sf3a', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // SF3b
        //$datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/SF3B.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        //echo $this->get_create_table_query('sf3b_tb', 'sf3b', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // DAILY
        $datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/daily.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        echo $this->get_create_table_query('daily_tb', 'dly', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // SP500
        $datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/sp500.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        echo $this->get_create_table_query('sp500_tb', 'sp5', $datas['datatable']['columns'], $datas['datatable']['data'][0]);

        // ACTIONS
        $datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/actions.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        echo $this->get_create_table_query('actions_tb', 'act', $datas['datatable']['columns'], $datas['datatable']['data'][0]);


        /*
        // SEP
        $datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/SEP.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        echo $this->get_create_table_query('sep_tb', 'sep', $datas['datatable']['columns'], $datas['datatable']['data'][0]);
        */

        /*
        $datas = array(
            'datatable' => array(
                'data' => array(
                        array(
                            값,
                            값,
                            ...
                        )
                    ),
                'columns' => array(
                        array(
                            'name' => 필드명
                            'type' => 데이터타입
                        ),
                        ....
                    )
            )
            'meta' => array(
                'next_cursor_id' => ......
                )
        )
        */

        //echo print_r($datas['datatable']['columns']);
    }


    private function get_create_table_query($table_name, $prefix, $columns, $sample_row) {
        $map = array(
            'BigDecimal' => 'decimal',
            'Integer' => 'bigint(20)',
            'String' => 'varchar(255)',
            'Date' => 'date',
            'text' => 'text',
            'double' => 'double(17,3)',
        );

        if(strlen($prefix) > 0 && substr($prefix, -1, 1) != '_') {
            $prefix .= '_';
        }

        $lines = array();
        $lines[] = 'create table `'.$table_name.'` (';
        $lines[] = '`'.$prefix.'id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,';

        // Type Check!!
        $check_flag = true;
        $type_map = array();
        foreach($columns as $idx => $col) {
            $name = $col['name'];

            // api 상 필드명이 id면 우리 pk랑 겹친다. 알리자.
            if(strtolower($name) == 'id') {
                echo 'Duplicate PK Name : '.$name."\n";
                $check_flag = false;
                continue;
            }
            $type = $col['type'];
            $map_key = $type;
            if(substr($type, -1, 1) == ')') {
                $map_key = array_shift(explode('(', $type, 2));
            }


            // 지정되지 않은 필드타입은 정의 후 다시 돌리게. 알리자.
            if( ! isset($map[$map_key])) {
                echo 'Unknown Type : '.$map_key."\n";
                echo 'Value : '.$sample_row[$idx]."\n";
                $check_flag = false;
                continue;
            }

            switch($type) {
                case 'String' :
                    if(strlen($sample_row[$idx]) > 255) {
                        $type_map[$name] = 'text';
                    } else if(strlen($sample_row[$idx]) > 100) {
                        $type_map[$name] = 'varchar(255)';
                    } else if(strlen($sample_row[$idx]) > 20) {
                        $type_map[$name] = 'varchar(255)';
                    } else {
                        $type_map[$name] = str_replace($map_key, $map[$map_key], $type);
                    }
                    break;
                default :
                    $type_map[$name] = str_replace($map_key, $map[$map_key], $type);
            }
        }
        if( ! $check_flag) {
            exit;
        }


        // check 통과
        foreach($columns as $col) {
            $name = $col['name'];
            if( ! isset($type_map[$name])) {
                // 이럴 일은 없겠지... 
                echo 'Key Not found.';exit;
            }
            $type = $type_map[$name];
            $lines[] = "`{$prefix}{$name}` {$type} not null,";
        }

        $lines[] = "`{$prefix}created_at` datetime not null,";
        $lines[] = "`{$prefix}updated_at` datetime not null,";
        $lines[] = 'primary key ('.$prefix.'id)';
        $lines[] = ') ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';

        return implode("\n", $lines)."\n";
    }


    // 각 종목펼 5년 평균 ROE 캐시 만들기
    public function make_6yroe() {
        $this->sf1_tb_model->make6YRoe();
    }

    public function make_recent_report() {
        $this->load->model('business/historylib');

        // 최근 실적발표
        $params = array();
        $params['join']['ticker_tb'] = 'tkr_ticker = sf1_ticker';
        $params['join']['daily_tb'] = 'tkr_ticker = dly_ticker';
        $params['=']['sf1_dimension'] = 'MRQ'; 
        $params['=']['tkr_is_active'] = 'YES'; 
        $params['<=']['DATEDIFF(sf1_lastupdated, sf1_reportperiod)'] = '60'; 

        $extra = array(
            'fields' => array('tkr_ticker', 'tkr_name', 'tkr_rate', 'tkr_rate_str', 'tkr_close', 'tkr_exchange', 'sf1_netinccmnusd', 'dly_marketcap', 'sf1_lastupdated'),
            'order_by' => 'sf1_lastupdated desc, sf1_netinccmnusd desc',
            'limit' => 30,
            //'cache_sec' => 3600*12,
            'wstreetdb'=> true
        );
        //sf1_lastupdated
        $recent_report = $this->sf1_tb_model->getList($params, $extra)->getData();
        $data['recent_report'] = $recent_report;

        // 최근 실적발표 전년동기 대비 실적
        $recent_report_tickers = array_keys($this->common->getDataByPK($recent_report, 'tkr_ticker'));
        $recent_report_rates = $this->historylib->getIncomeGrowthRate($recent_report_tickers);
        $recent_report_day = array_values($recent_report_rates['lastupdated']);

        $data['recent_report_day'] = $recent_report_day[0];
        $data['recent_report_rates'] = $recent_report_rates['rate'];
        $data['recent_report_rates_pm'] = $recent_report_rates['rate_pm'];
        $data = serialize($data);
        $report_file = 'recent_report.inc';
        $file_path = WEBDATA.'/'.$report_file;
        $file_backpath = $file_path . '.bak';
        
        touch($file_backpath);
        file_put_contents($file_backpath, $data);
        rename($file_backpath, $file_path);

        echo "\n".'['.date("Y-m-d H:i:s")."] make_recent_report end!!\n";
    }

	public function sync_spiderrank() {

		echo "\n".'['.date("Y-m-d H:i:s")."] sync_spiderrank start!!\n";
        $this->load->model(DBNAME.'/mri_tb_model');
        $this->load->model(DBNAME.'/mriall_tb_model');

		$params = array();
		$extra = array(
			'order_by' => 'm_ticker asc',
			'wstreetdb' => true,
		);

		$mri_list = $this->mri_tb_model->getList($params, $extra)->getData();
		//echo '<pre>'; print_r($mri_list); exit;

		foreach($mri_list as $key=>$val) {
            if($this->mriall_tb_model->get(array('m_ticker' => $val['m_ticker'], 'm_sep_date' => $val['m_sep_date']))->isSuccess()) {
                $dbrow = $this->mriall_tb_model->getData();

				$update_params = array();
				$update_params['m_ranking'] = $val['m_ranking'];
				$update_params['m_highrank'] = $val['m_highrank'];

				$this->mriall_tb_model->doUpdate($dbrow['m_id'], $update_params);
			}
		}
		echo "\n".'['.date("Y-m-d H:i:s")."] sync_spiderrank end!!\n";
	}

    //public function sync_target($sync_key=sep) {
	public function sync_update_price() {

        set_time_limit(-1);
        ini_set('memory_limit', -1);

		echo "\n".'['.date("Y-m-d H:i:s")."] sync_update_price start!!\n";
		//select * from actions_tb a, ticker_tb b
		//where a.act_ticker = b.tkr_ticker 
		//and a.act_date = '2020-12-10' and a.act_action = 'split' and b.tkr_table = 'SEP' and b.tkr_isdelisted = 'N' and b.tkr_exchange in ('NYSE','NASDAQ','NYSEMKT','BATS') order by a.act_id desc		

		$rows = array_values($this->common->getDataByPK($this->sep_tb_model->getList(array(), array('order_by' => 'sep_date desc', 'limit' => 1))->getData(), 'sep_ticker'));
		$up_date = $rows[0]['sep_date'];

		if($up_date != '') {
		
			$params = array();
			$params['=']['tkr_table'] = 'SEP';
			$params['=']['tkr_isdelisted'] = 'N';
			$params['in']['tkr_exchange'] = array('NYSE', 'NASDAQ', 'NYSEMKT', 'BATS');
			$params['=']['act_date'] = $up_date;
			$params['=']['act_action'] = 'split';
			$params['join']['actions_tb'] = 'tkr_ticker = act_ticker';

			$extra = array(
				'fields' => '*',
			);

			$ticker_list = array();
			$ticker_list = $this->ticker_tb_model->getList($params, $extra)->getData();
			//echo '<pre>'; print_r($ticker_list); exit;
			//$tiker = 'HTBX|ROL|CYTH|EYPT|AYTU|NBRV|VIVE|TTNP|NCSM|MKC|AIV|LARK|HJLI|DLPN|CDR|TPX|UAN|HTGM|ENSV|BKYI|CVEO|TEO|LIXT|XYF|MDVL|DQ|RMED|MGEN|PDS|FET|SMLP|JILL|COGT|WYY|OCUP|PSHG|MDLY|JT|NTEC|HPR|HUSN|NEE|LLIT|TRQ|TC|GLBS|NCTY|CHFS|AXAS|ARRY|CVLB|RZLT|RUSHA|FURY|NVUS|NTES|KDNY|JE|GOVX|MRNS|NOG|AROW|ACET|EGLE|QRTEA|TREX|TOMZ|HGEN|BNL|BIOC|AEHL|SNSS|HGSH|AAPL|TSLA|MYT|GP|PLM|HX|CPTA|KTOV|POWI|ONVO|TYHT|CRTD|MRTN|OCN|VJET|IPI|CIG|ATNM|TOPS|CPE|NYC|VISL|HUSA|TLSA|ACHV|ARCO|TAOP|ATTO|SQFT|KLXE|SQBG|MCC|BVH|SCYX|AHT|JAKK|ITRG|SNFCA|SINO|ODP|IAC|RCEL|TNP|ALTM|SHIP|NNDM|SFUN|VRME|ACI|TEF|FBRX|FSK|VTOL|EFOI|HWBK|XSPA|GRPN|FSKR|VHI|LPI|EW|LRMR|AYRO|TLGT|HSTO|QLGN|INVO|RAND|RMBL|TMBR|MFH|FTSI|ACB|DSS|WINT|NBR|CYCC|TBLT|CREG|BNTC|MCEP|WISA|SALT|BAM|SONN|APVO|EKSO|ODFL|USAU|LUMO|TU|SUMR|ICD|MTP|TR|SGLB|WMG|GPRK|AVEO|PKBK|MEDS|NVIV|BLPH|BLCM|SGBX|SNES|CFRX|OPCH|MYO|GURE|GMBL|OVV|SDGR|SRRA|DUOT|FFBW|YTEN|PHIO|IDXG|TARA|RENN|INPX|EYES|JRJC|USEG|RVMD|NRBO|RCON|DCTH|QUIK|NVFY|PCSA|NAKD|PLX|ESEA|PIXY|ELYS|IMBI|TRXC|CLSK|FWP|FGBI|NMRD|SOS|LODE|SQNS|SNDE|AMRH|TNK|AKER|BIVI|NURO|MYSZ|DRIO|ALIM|OLB|MBCN|CRESY|SLS|VERO|TGS|TCON|IMMP|APDN|TNXP|';
			//$tikers = explode('|', $tiker); 
			//echo '<pre>';
			//$lte = date('Y-m-d');

			$lte = $up_date;

			$table = 'sep_tb';
			$prefix = 'sep';
			$uniq_keys = array('sep_ticker','sep_date');

			foreach($ticker_list as $val) {

				if($val['tkr_ticker'] != '') {
					$params = array();
					$params['=']['sep_ticker'] = $val['tkr_ticker'];

					$extra = array(
						'fields' => 'sep_date',
						'order_by' => 'sep_date asc',
						'limit' => '1',
					);

					$sep_data = array_shift($this->sep_tb_model->getList($params, $extra)->getData());

					if(sizeof($sep_data)>0) { 
						$url = 'https://www.quandl.com/api/v3/datatables/SHARADAR/SEP?api_key='.QDAPI_KEY.'&ticker='.$val['tkr_ticker'].'&date.gte='.$sep_data['sep_date'].'&date.lte='.$lte;
						//echo 'table==>'.$table.'<br>';
						//echo 'prefix==>'.$prefix.'<br>';
						//print_r($uniq_keys).'<br>';
						echo 'url==>'.$url."\n";

						$next_cursor_id = $this->sync_table($table, $prefix, $uniq_keys, $url);
						while(trim($next_cursor_id)) {
							$next_cursor_id = $this->sync_table($table, $prefix, $uniq_keys, $url.'&qopts.cursor_id='.$next_cursor_id);
						}
					}
				}
			}
		}

		echo "\n".'['.date("Y-m-d H:i:s")."] sync_update_price end!!\n";
	}

    /*
    public function sync_all() {
        set_time_limit(-1);
        ini_set('memory_limit', -1);

        $sync_infos = $this->sync_infos;

        foreach($sync_infos as $dbkey => $sync_info) {
            list($table, $prefix, $uniq_keys, $url) = array_values($sync_info);
            $next_cursor_id = $this->sync_table($table, $prefix, $uniq_keys, $url);
            while(trim($next_cursor_id)) {
                $next_cursor_id = $this->sync_table($table, $prefix, $uniq_keys, $url.'&qopts.cursor_id='.$next_cursor_id);
            }
        }
        exit;


        // Ticker

        $datas = json_decode($this->common->restful_curl($json_url), true);
        echo "\n".'next_cursor_id : '.$datas['meta']['next_cursor_id']."\n";
        sleep(5);
        $newest_data = $this->get_key_value($datas, $prefix);
        print_r($newest_data[0]);sleep(5);
        $total_count = sizeof($newest_data);
        foreach($newest_data as $idx => $row) {
            echo '[ '.($idx+1).' / '.$total_count.' ] ';

            $uniq_filter = array();
            foreach($uniq as $f) {
                $uniq_filter[$f] = $row[$f];
            }
            if($this->{$table.'_model'}->get($uniq_filter)->isSuccess()) {
                // update
                $row[$prefix.'_updated_at'] = date('Y-m-d H:i:s');

                $dbrow = $this->{$table.'_model'}->getData();
                $pk = $dbrow[$prefix.'_id'];

                echo $pk.' pk Update....';

                if( ! $this->{$table.'_model'}->doUpdate($pk, $row)->isSuccess()) {
                    echo " Fail - ";
                    echo $this->stock_tb_model->getErrorMsg()."\n";
                    echo $this->{$table.'_model'}->getLastQuery()."\n";
                    sleep(5);
                    continue;
                }
                echo "OK\n";
            } else {
                echo 'Insert ';
                foreach($uniq as $k) {
                    echo $k.':'.$row[$k].' ';
                }

                if( ! $this->{$table.'_model'}->doInsert($row)->isSuccess()) {
                    echo 'Fail - '.$this->{$table.'_model'}->getErrorMsg()."\n";
                    print_r($row);
                    sleep(5);
                    continue;
                }
                echo "OK\n";
            }
        }
        echo "\n".'next_cursor_id : '.$datas['meta']['next_cursor_id']."\n";
        exit;

        // SF1
        $datas = json_decode($this->common->restful_curl('https://www.quandl.com/api/v3/datatables/SHARADAR/SF1.json?api_key=qNazFB9svr_C6rxNqmW8'), true);
        $newest_data = $this->get_key_value($datas, 'sf1');
        print_r($newest_data);
    }
    */

    public function delete_all_query_cache(){
        $this->sf1_tb_model->deleteAllCache();
    }

}