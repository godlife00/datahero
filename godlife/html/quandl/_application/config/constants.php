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

if( ! isset($_SERVER['REMOTE_ADDR'])) {
	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
}
define ('SERVICE_NAME', 'Quandl');

define ('DBNAME', 'quandl');



define ('SUPER_LEVEL', 9);

// 문자통 계정정보
define('SMS_ID', 'phoenixq');
define('SMS_PW', 'phoenixq');


$channel = 'cli';
if(IS_REAL_SERVER) {
	// 건드리지 말것!!
	define('ADMIN_DOMAIN', 'data-api.us.datahero.co.kr');
	define('SHOW_ALL_QUERY', false);	// 개발자 디버그용. Real Serve Block "false" 유지 필수!!!
    $allow_domains = array(
        'us-wm.datahero.co.kr' => 'quandl',
        'us-x1.datahero.co.kr' => 'quandl',
        'us-paxnet.datahero.co.kr' => 'quandl',
        'us-snek.datahero.co.kr' => 'quandl',
        'us-ge.datahero.co.kr' => 'quandl',
        'us153.datahero.co.kr' => 'quandl',
        'datahero.thewm.co.kr' => 'thewm',
        'data-api.us.datahero.co.kr' => 'wallstreet',
    );
    if(isset($_SERVER['HTTP_HOST']) && strlen($_SERVER['HTTP_HOST']) > 0 ) {
        $host = strtolower($_SERVER['HTTP_HOST']);
        if( ! isset($allow_domains[$host])) {
            die('The access is incorrect.');
        }
        $channel = $allow_domains[$host];
    }
	define('CS_URL', 'https://www.choicestock.co.kr');
	define('APP_PATH', realpath(dirname(__FILE__).'/../..'));
} 
else {

	//define('ADMIN_DOMAIN', 'test2.quandl2.hamt.kr');

    if(strstr($_SERVER['HTTP_HOST'], 'us153pub.datahero.co.kr')) {
		define('ADMIN_DOMAIN', 'us153pub.datahero.co.kr');
		define('CS_URL', 'https://pub.choicestock.co.kr');
		define('APP_PATH', '/home/godlife/html/quandl');
    }
	else {
		define('ADMIN_DOMAIN', 'us153dev.datahero.co.kr');
		define('CS_URL', 'https://capdev.choicestock.co.kr');
		define('APP_PATH', '/home/hoon/html/quandl');
	}

	define('SHOW_ALL_QUERY', false);	// 개발자 디버그용. true시 실행 쿼리 전체 출력.
    $channel = 'devtest';
}

define('CHANNEL', $channel);

define('HTTP_ADMIN_URL', 'http://'.ADMIN_DOMAIN);
define('HTTPS_ADMIN_URL','http://'.ADMIN_DOMAIN);
define('SUPPORT_TEL', '010-0000-0000'); // todo.

define('ASSETS_URL', '/admin_assets');
define('API_URL', 'http://data-api.us.datahero.co.kr');


define('LOGIN_TITLE', '로그인'); 
define('LOGIN_COLOR', '#c44'); 
define('LOGIN_BTNTXT_COLOR', 'white'); 

define('ALL_RIGHT_RESERVED', ''); 

define('APP_WALLPATH', '/home/datahero/html/wallstreet');
//define('APP_WALLPATH', realpath(dirname(__FILE__).'/../../../wallstreet'));

define('WEBDATA', APP_PATH.'/webdata');
define('WALL_WEBDATA', APP_WALLPATH.'/webdata');
define('INC_PATH', APP_PATH.'/_application/views/pc/inc');
define('SUPPORT_EMAIL', 'aaa@aaa.com'); // todo
define('HELP_EMAIL', 'help@aaa.com'); // todo.


define('CACHE_TIME_S', 180); 
define('CACHE_TIME_M', 3600);
define('CACHE_TIME_L', 86400);


//define('QDAPI_KEY', 'J_xMzybiszvGsxUi1bMC');   // 퀀들 API Key
//19/10/30변경
define('QDAPI_KEY', 'b9Q9E2d-D3EMyH7FQ9qE');   // 퀀들 API Key

/*장시작시간*/
define('START_TIME', '2330'); /* 매년 11월 초 ~ 3월 13일 : 23:30~0600, 썸머타임은 3월14일~11월 초 : 22:30~0500*/

// SHOW_ALL_QUERY 가 true로 셋팅시 쿼리리스트 html들이 담길 글로벌 배열 
$show_all_query = array();

/* End of file constants.php */
/* Location: ./application/config/constants.php */
