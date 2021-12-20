<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sep_tb_model extends MY_Model {

    protected $pk = 'sep_id';


    // NOT NULL 필드들에대한 정의. 각 모델에서 재정의
    protected $emptycheck_keys = array(

        'sep_ticker'         => 'sep_ticker value is empty.',
        'sep_date'         => 'sep_date value is empty.',
        /*
        'sep_open'         => 'sep_open value is empty.',
        'sep_high'         => 'sep_high value is empty.',
        'sep_low'         => 'sep_low value is empty.',
        'sep_close'         => 'sep_close value is empty.',
        'sep_volume'         => 'sep_volume value is empty.',
        'sep_dividends'         => 'sep_dividends value is empty.',
        'sep_closeunadj'         => 'sep_closeunadj value is empty.',
        'sep_lastupdated'         => 'sep_lastupdated value is empty.',
        */
        'sep_created_at'         => 'sep_created_at value is empty.',
        'sep_updated_at'         => 'sep_updated_at value is empty.'
        );

    // ENUM 필드마다 가질 수 있는 값들을 KEY => VALUE 형태의 배열로 정의. 각 모델에서 재정의
    protected $enumcheck_keys = array(

        );
    
    function __construct() {
        parent::__construct();
        $this->db_name = array_pop(explode('/', dirname(__FILE__)));
        $this->table = strtolower(substr(__CLASS__,0,-6));
        $this->fields = $this->db->list_fields($this->table);
    }

    // 특정 종목들의 가격 변동 히스토리. 차트용. ticker_codes 배열 가능. 
    // 웬만하면 모아서 한번만 쿼리하게 쓰기.
    public function getPriceHistory($ticker_codes, $before_day='366') {

        $sep_extra = array();
        $sep_extra['order_by'] = 'sep_ticker, sep_date asc'; // 아래서 뒤집는다.
        $sep_extra['cache_sec'] = 3600;
        $sep_extra['slavedb'] = true;

        $sep_params = array();
        if(is_array($ticker_codes)) {
            $sep_params['in']['sep_ticker'] = $ticker_codes;
        } else {
            $sep_params['=']['sep_ticker'] = $ticker_codes;
        }

        if( ! is_numeric($before_day) || $before_day < 1 || $before_day > 366) {
            $before_day = 120;
        }

        $sep_params['>']['sep_date'] = date('Y-m-d', time()-86400*$before_day);
        
        $sep_group = array();
        $sep_group = $this->common->getDataByDuplPK($this->getList($sep_params, $sep_extra)->getData(), 'sep_ticker');;
        foreach($sep_group as $tkr => &$sep_list) {
            //$sep_list = array_reverse($sep_list);

            $prev_item = array();
            foreach($sep_list as $i => &$sep_item) {
                if($i == 0) {
                    $prev_item = $sep_item;
                    continue;
                }

                $sep_item['sep_diff_price'] = sprintf('%.2f', $sep_item['sep_close'] - $prev_item['sep_close'] + $sep_item['sep_dividends']);
                $sep_item['sep_diff_rate'] = sprintf('%.2f', ($sep_item['sep_diff_price'] / ($prev_item['sep_close']-$sep_item['sep_dividends']) * 10000)/100);
                $prev_item = $sep_item;
            }

            $sep_list = array_reverse($sep_list);
            array_pop($sep_list);
        }

        if( ! is_array($ticker_codes)) {
            return array_pop($sep_group);
        }
        return $sep_group;
    }

    public function getPriceHistory_bicchart($ticker_codes, $before_day='366') {

        $sep_extra = array();
        $sep_extra['order_by'] = 'sep_ticker, sep_date asc'; // 아래서 뒤집는다.
        //$sep_extra['cache_sec'] = 3600;
        $sep_extra['slavedb'] = true;

        $sep_params = array();
        if(is_array($ticker_codes)) {
            $sep_params['in']['sep_ticker'] = $ticker_codes;
        } else {
            $sep_params['=']['sep_ticker'] = $ticker_codes;
        }

        if( ! is_numeric($before_day) || $before_day < 1 || $before_day > 366) {
            $before_day = 120;
        }

        $sep_params['>']['sep_date'] = date('Y-m-d', time()-86400*$before_day);
        
        $sep_group = array();
        $sep_group = $this->common->getDataByDuplPK($this->getList($sep_params, $sep_extra)->getData(), 'sep_ticker');;
        foreach($sep_group as $tkr => &$sep_list) {
            //$sep_list = array_reverse($sep_list);

            $prev_item = array();
            foreach($sep_list as $i => &$sep_item) {
                if($i == 0) {
                    $prev_item = $sep_item;
                    continue;
                }

                $sep_item['sep_diff_price'] = sprintf('%.2f', $sep_item['sep_close'] - $prev_item['sep_close'] + $sep_item['sep_dividends']);
                $sep_item['sep_diff_rate'] = sprintf('%.2f', ($sep_item['sep_diff_price'] / ($prev_item['sep_close']-$sep_item['sep_dividends']) * 10000)/100);
                $prev_item = $sep_item;
            }

            $sep_list = array_reverse($sep_list);
            array_pop($sep_list);
        }

        if( ! is_array($ticker_codes)) {
            return array_pop($sep_group);
        }
        return $sep_group;
    }

    public function getPriceHistory_last($ticker_codes, $sep_date='') {

        $sep_extra = array();
        $sep_extra['order_by'] = 'sep_ticker, sep_date asc'; // 아래서 뒤집는다.
        //$sep_extra['cache_sec'] = 3600;
        $sep_extra['slavedb'] = true;

		$before_day='366';

		if($sep_date == '') $sep_date = date('Y-m-d', strtotime('-1 days'));

        $sep_params = array();
        if(is_array($ticker_codes)) {
            $sep_params['in']['sep_ticker'] = $ticker_codes;
        } else {
            $sep_params['=']['sep_ticker'] = $ticker_codes;
        }
/*
        if( ! is_numeric($before_day) || $before_day < 1 || $before_day > 366) {
            $before_day = 120;
        }
*/
        $sep_params['<=']['sep_date'] = $sep_date;
        $sep_params['>']['sep_date'] = date('Ymd', strtotime($sep_date)-86400*$before_day);
		//echo 'sep_date--->'.$sep_date;        
        $sep_group = array();
        $sep_group = $this->common->getDataByDuplPK($this->getList($sep_params, $sep_extra)->getData(), 'sep_ticker');;
        foreach($sep_group as $tkr => &$sep_list) {
            //$sep_list = array_reverse($sep_list);

            $prev_item = array();
            foreach($sep_list as $i => &$sep_item) {
                if($i == 0) {
                    $prev_item = $sep_item;
                    continue;
                }

                $sep_item['sep_diff_price'] = sprintf('%.2f', $sep_item['sep_close'] - $prev_item['sep_close'] + $sep_item['sep_dividends']);
                $sep_item['sep_diff_rate'] = sprintf('%.2f', ($sep_item['sep_diff_price'] / ($prev_item['sep_close']-$sep_item['sep_dividends']) * 10000)/100);
                $prev_item = $sep_item;
            }

            $sep_list = array_reverse($sep_list);
            array_pop($sep_list);
        }

        if( ! is_array($ticker_codes)) {
            return array_pop($sep_group);
        }
        return $sep_group;
    }

    public function getTickerPriceMap() {
        $cache_file_path = WEBDATA.'/ticker_price_map.info';

        $yoil = date("N");
        $hour =    date("Hi");

        if( $yoil > 1 && $yoil < 7 && $hour > 910 && $hour < 1000 ) {
            if( is_file($cache_file_path) && intval(date('Hi', filemtime($cache_file_path))) < 910 ) {
                $last_date = $this->historylib->getDailyLastDate();
                $params = array();
                $params['=']['sep_date'] = $last_date;
                $params['join']['ticker_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date  and tkr_table = "SEP" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';

                $extra = array();
                $extra['order_by'] = '';
                $extra['fields'] = 'tkr_ticker';
                $extra['slavedb'] = true;

                $all_tickers = array_keys($this->common->getDataByPk($this->getList($params, $extra)->getData(), 'tkr_ticker'));

                $all_tickers_new = array();
                foreach( $all_tickers as $aKey=>$aVal ) {
                    if(!is_numeric($aVal)) {
                        $all_tickers_new[] = $aVal;
                    }
                }
                $result = $this->getTickersPrice($all_tickers_new);

                file_put_contents($cache_file_path, serialize($result));

                return $result;
            }
        }

        if( is_file($cache_file_path) && filemtime($cache_file_path) > time()-3600*12 ) {
            return unserialize(file_get_contents($cache_file_path));
        }

        $last_date = $this->historylib->getDailyLastDate();
        $params = array();
        $params['=']['sep_date'] = $last_date;
        $params['join']['ticker_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date  and tkr_table = "SEP" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';

        $extra = array();
        $extra['order_by'] = '';
        $extra['fields'] = 'tkr_ticker';
        $extra['slavedb'] = true;

        $all_tickers = array_keys($this->common->getDataByPk($this->getList($params, $extra)->getData(), 'tkr_ticker'));

        $all_tickers_new = array();
        foreach( $all_tickers as $aKey=>$aVal ) {
            if(!is_numeric($aVal)) {
                $all_tickers_new[] = $aVal;
            }
        }
        $result = $this->getTickersPrice($all_tickers_new);

        file_put_contents($cache_file_path, serialize($result));

        return $result;
    }

    public function getTickerPriceMap2() {
        $cache_file_path = WEBDATA.'/ticker_price_map.info';

        //if( is_file($cache_file_path) && filemtime($cache_file_path) > time()-3600 ) {
            //return unserialize(file_get_contents($cache_file_path));
        //}

        $last_date = $this->historylib->getDailyLastDate();
        $params = array();
        $params['=']['sep_date'] = $last_date;
        $params['join']['ticker_tb'] = 'sep_ticker = tkr_ticker and tkr_lastpricedate = sep_date  and tkr_table = "SEP" and tkr_isdelisted = "N" and tkr_exchange <> "OTC"';

        $extra = array();
        $extra['order_by'] = '';
        $extra['fields'] = 'tkr_ticker';

        $all_tickers = array_keys($this->common->getDataByPk($this->getList($params, $extra)->getData(), 'tkr_ticker'));

        $all_tickers_new = array();
        foreach( $all_tickers as $aKey=>$aVal ) {
            if(!is_numeric($aVal)) {
                $all_tickers_new[] = $aVal;
            }
        }
        $result = $this->getTickersPrice($all_tickers_new);

        file_put_contents($cache_file_path, serialize($result));

        return $result;
    }

    // 전 종목의 등락율 캐싱 저장. 
    public function getTopPlusTickers($limit=5) {
        $all_ticker = $this->getTickerPriceMap();

        $ticker_rate_map = $this->common->array2map($all_ticker, 'ticker', 'diff_rate_num');
        $rates = array_values($ticker_rate_map);
        $keys = array_keys($ticker_rate_map);
        array_multisort($rates, SORT_DESC, $keys);
        return array_slice($keys, 0, $limit);
    }

    // ticker code 배열을 받으면 해당 종목들의 종가와 등락폭, 율 맵을 반환.
    public function getTickersPrice($ticker_codes) {
        if( ! is_array($ticker_codes) || sizeof($ticker_codes) <= 0) {
            return array();
        }

        $last_date = $this->historylib->getDailyLastDate();
        $prev_date = $this->historylib->getDailyPrevDate();

        $sep_params = array();
        $sep_params['in']['sep_ticker'] = $ticker_codes;
        $sep_params['>=']['sep_date'] = $prev_date;

        $sep_extra = array(
            'order_by' => 'sep_ticker, sep_date desc',
            'fields' => 'sep_ticker, sep_close, sep_date, sep_dividends'
        );

        $ticker_prices = $this->common->getDataByDuplPK($this->getList($sep_params, $sep_extra)->getData(), 'sep_ticker');
        $result = array();
        foreach($ticker_prices as $ticker => $seps) {
            $seps = $this->common->getDataByPk($seps, 'sep_date');
            $item = array(
                'ticker' => $ticker,
                'close' => '-',
                'diff' => '-',
                'diff_num' => 0,
                'diff_rate' => '-',
                'diff_rate_num' => 0,
            );
            if(sizeof($seps) >= 2) {
                $dates = array_keys($seps);
                $last_date = $dates[0];
                $prev_date = $dates[1];

                //2020.02/12
                //"diff_price = [SEP table] close - (d-1)close + [SEP table] dividedns
                //diff_rate = ( [SEP table] close /( [SEP table] (d-1) close - [SEP table] dividedns ) - 1 ) * 100%"

                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
                $item['diff_num'] = round($seps[$last_date]['sep_close'] - $seps[$prev_date]['sep_close'] + $seps[$last_date]['sep_dividends'], 2);
                $item['diff'] = $item['diff_num'];
                $item['diff_rate_num'] = ($seps[$last_date]['sep_close'] / ($seps[$prev_date]['sep_close'] - $seps[$last_date]['sep_dividends'])-1) * 100;
/*
                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
                $item['diff_num'] = round($seps[$last_date]['sep_close'] - $seps[$prev_date]['sep_close'] - $seps[$last_date]['sep_dividends'], 2);
                $item['diff'] = $item['diff_num'];
                $item['diff_rate_num'] = ($seps[$last_date]['sep_close'] / ($seps[$prev_date]['sep_close'] + $seps[$last_date]['sep_dividends'])-1) * 100;

                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
                $item['diff_num'] = round($seps[$last_date]['sep_close'] - $seps[$prev_date]['sep_close'] + $seps[$prev_date]['sep_dividends'], 2);
                $item['diff'] = $item['diff_num'];
                $item['diff_rate_num'] = $item['diff_num'] / ($seps[$prev_date]['sep_close'] - $seps[$prev_date]['sep_dividends']) * 100;
*/               
                
                if($item['diff'] > 0) {
                    $item['diff'] = '+ '.number_format($item['diff'], 2);
                    $item['diff_rate'] = '+'.number_format($item['diff_rate_num'],2).'%';
                } else {
                    $item['diff'] = number_format($item['diff'], 2);
                    $item['diff_rate'] = number_format($item['diff_rate_num'],2).'%';
                }
            } else if(isset($seps[$last_date])) {
                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
            } else if(isset($seps[$prev_date])) {
                $item['close'] = number_format($seps[$prev_date]['sep_close'], 2);
            }

            $result[$ticker] = $item;
        }
        return $this->common->indexSort($ticker_codes, $result);
    }

    // ticker code 배열을 받으면 해당 종목들의 종가와 등락폭, 율 맵을 반환.
    public function getTickersPrice_last($ticker_codes, $sep_date) {
        if( ! is_array($ticker_codes) || sizeof($ticker_codes) <= 0) {
            return array();
        }
//echo '1'.$sep_date;
//exit;

        $last_date = $this->historylib_last->getDailyLastDate_last($sep_date);
        $prev_date = $this->historylib_last->getDailyPrevDate_last($sep_date);
//echo '<pre>11231<br>'; print_r($prev_date); exit;

        $sep_params = array();
        $sep_params['in']['sep_ticker'] = $ticker_codes;
        $sep_params['<=']['sep_date'] = $last_date;
        $sep_params['>=']['sep_date'] = $prev_date;

        $sep_extra = array(
            'order_by' => 'sep_ticker, sep_date desc',
            'fields' => 'sep_ticker, sep_close, sep_date, sep_dividends'
        );

        $ticker_prices = $this->common->getDataByDuplPK($this->getList($sep_params, $sep_extra)->getData(), 'sep_ticker');
//echo '<pre>'; print_r($ticker_prices); exit;

        $result = array();
        foreach($ticker_prices as $ticker => $seps) {
            $seps = $this->common->getDataByPk($seps, 'sep_date');
            $item = array(
                'ticker' => $ticker,
                'close' => '-',
                'diff' => '-',
                'diff_num' => 0,
                'diff_rate' => '-',
                'diff_rate_num' => 0,
            );
            if(sizeof($seps) >= 2) {
                $dates = array_keys($seps);
                $last_date = $dates[0];
                $prev_date = $dates[1];

                //2020.02/12
                //"diff_price = [SEP table] close - (d-1)close + [SEP table] dividedns
                //diff_rate = ( [SEP table] close /( [SEP table] (d-1) close - [SEP table] dividedns ) - 1 ) * 100%"

                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
                $item['diff_num'] = round($seps[$last_date]['sep_close'] - $seps[$prev_date]['sep_close'] + $seps[$last_date]['sep_dividends'], 2);
                $item['diff'] = $item['diff_num'];
                $item['diff_rate_num'] = ($seps[$last_date]['sep_close'] / ($seps[$prev_date]['sep_close'] - $seps[$last_date]['sep_dividends'])-1) * 100;
/*
                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
                $item['diff_num'] = round($seps[$last_date]['sep_close'] - $seps[$prev_date]['sep_close'] - $seps[$last_date]['sep_dividends'], 2);
                $item['diff'] = $item['diff_num'];
                $item['diff_rate_num'] = ($seps[$last_date]['sep_close'] / ($seps[$prev_date]['sep_close'] + $seps[$last_date]['sep_dividends'])-1) * 100;

                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
                $item['diff_num'] = round($seps[$last_date]['sep_close'] - $seps[$prev_date]['sep_close'] + $seps[$prev_date]['sep_dividends'], 2);
                $item['diff'] = $item['diff_num'];
                $item['diff_rate_num'] = $item['diff_num'] / ($seps[$prev_date]['sep_close'] - $seps[$prev_date]['sep_dividends']) * 100;
*/               
                
                if($item['diff'] > 0) {
                    $item['diff'] = '+ '.number_format($item['diff'], 2);
                    $item['diff_rate'] = '+'.number_format($item['diff_rate_num'],2).'%';
                } else {
                    $item['diff'] = number_format($item['diff'], 2);
                    $item['diff_rate'] = number_format($item['diff_rate_num'],2).'%';
                }
            } else if(isset($seps[$last_date])) {
                $item['close'] = number_format($seps[$last_date]['sep_close'], 2);
            } else if(isset($seps[$prev_date])) {
                $item['close'] = number_format($seps[$prev_date]['sep_close'], 2);
            }

            $result[$ticker] = $item;
        }
        return $this->common->indexSort($ticker_codes, $result);
    }

    protected function __filter($params) {
        $params['sep_created_at'] = date('Y-m-d H:i:s');
        $params['sep_updated_at'] = date('Y-m-d H:i:s');

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

    public function getVchartList($ticker, $sep_date) {
/*
        $this->db->select('max(sep_date) sep_date, sep_close');
        $this->db->from('sep_tb');
        $this->db->where('sep_ticker', $ticker);
        $this->db->like('sep_date', substr($sep_date, 0, 4).'-'.substr($sep_date, 5, 2)); 
*/


        $params = array();
        $params['=']['sep_ticker'] = $ticker;
        $params['like_']['sep_date'] = substr($sep_date, 0, 4).'-'.substr($sep_date, 5, 2);

        $extra = array(
            'fields' => 'sep_date, sep_close',
            'order_by' => 'sep_date desc',
            'limit' => 1,
            'slavedb' => true,
            'cache_sec' => 3600,
        );

        $sep_list = array_values($this->common->getDataByPK($this->sep_tb_model->getList($params, $extra)->getData(), 'sep_date'));
        return $sep_list;
/*****
        $this->db->select('sep_date, sep_close');
        $this->db->from('sep_tb');
        $this->db->where('sep_ticker', $ticker);
        $this->db->like('sep_date', substr($sep_date, 0, 4).'-'.substr($sep_date, 5, 2)); 
        $this->db->order_by('sep_date', 'desc');
        $this->db->limit(1);

        $query = $this->db->get('');
$sep_list = $query->result_array();
echo '<pre>';
print_r($sep_list);

        return $query->result_array();
*****/
    }
}
?>
