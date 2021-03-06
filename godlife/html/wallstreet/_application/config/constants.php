<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*
|--------------------------------------------------------------------------
| DB Query Debug 모드 ON/OFF
|--------------------------------------------------------------------------
|
| 페이지 로딩시 수행되는 모든 쿼리를 노출.
| 디버깅시에만 else문 안에서 true로 전환 후 커밋 전 반드시!! false로 되돌릴것
|
| 반드시!! else 문 안에서만 수정할것.
|
| 2013.02.19. 함승목.
|
*/

define ('SERVICE_NAME', '월가히어로');

define ('DBNAME', 'wstreet');

define ('SUPER_LEVEL', 9);

// 문자통 계정정보
define('SMS_ID', 'smstong_loginid');
define('SMS_PW', 'smstong_password');

$channel = 'cli';
define('APP_PATH', realpath(dirname(__FILE__).'/../..'));

if(IS_REAL_SERVER) {
    $allow_domains = array(
        'hero.datahero.co.kr'  => 'common',
        'hero-kiwoom.datahero.co.kr'  => 'hero-kiwoom',
        'hero-shinhan.datahero.co.kr'  => 'hero-shinhan',
        'us.datahero.co.kr'         => 'Admin',
    );
    if(isset($_SERVER['HTTP_HOST']) && strlen($_SERVER['HTTP_HOST']) > 0 ) {
        $host = strtolower($_SERVER['HTTP_HOST']);
        if( ! isset($allow_domains[$host])) {
            die('The access is incorrect.');
        }
        $channel = $allow_domains[$host];
    }
    define('ADMIN_DOMAIN', 'us.datahero.co.kr');
    define('API_URL', 'http://data-api.us.datahero.co.kr');
    define('CS_URL', 'https://www.choicestock.co.kr');

    define('WEBDATA', APP_PATH.'/webdata');
} else {
    $channel = 'devtest';
    //include realpath(dirname(__FILE__).'/dev_constants.php');


    if(strstr($_SERVER['HTTP_HOST'], 'uspub.datahero.co.kr')) {
	    define('ADMIN_DOMAIN', 'uspub.datahero.co.kr');
	    define('CS_URL', 'https://pub.choicestock.co.kr');
    }
	else {
	    define('ADMIN_DOMAIN', 'usdev.datahero.co.kr');
	    define('CS_URL', 'https://capdev.choicestock.co.kr');
	}

    define('API_URL', 'http://data-api.us.datahero.co.kr');
    define('WEBDATA', '/home/datahero/html/wallstreet/webdata');
}
define('CHANNEL', $channel);

define('QUANDL_WEBDATA', '/home/datahero/html/quandl/webdata');

define('HTTP_ADMIN_URL', 'http://'.ADMIN_DOMAIN);
define('HTTPS_ADMIN_URL','http://'.ADMIN_DOMAIN);
define('SUPPORT_TEL', '010-0000-0000'); // SMS Tong 발송자 전번으로 사용

define('ASSETS_URL', '/admin_assets');

define('SHOW_ALL_QUERY', false);// 개발자 디버그용. true시 실행 쿼리 전체 출력.

define('LOGIN_TITLE', '로그인'); 
define('LOGIN_COLOR', '#c44'); 
define('LOGIN_BTNTXT_COLOR', 'white'); 

define('ALL_RIGHT_RESERVED', ''); 

define('CACHE_TIME_S', 180); 
define('CACHE_TIME_M', 3600);
define('CACHE_TIME_L', 86400);

define('ATTACH_DATA', WEBDATA.'/attach_data');
define('MASTER_DATA', WEBDATA.'/master_data');

// SHOW_ALL_QUERY 가 true로 셋팅시 쿼리리스트 html들이 담길 글로벌 배열 
$show_all_query = array();

/* End of file constants.php */
/* Location: ./application/config/constants.php */