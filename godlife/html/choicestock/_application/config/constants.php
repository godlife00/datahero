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

define ('SERVICE_NAME', '초이스스탁');

define ('DBNAME', 'wstreet');



define ('SUPER_LEVEL', 9);

// 문자통 계정정보
define('SMS_ID', 'smstong_loginid');
define('SMS_PW', 'smstong_password');


$channel = 'cli';
define('APP_PATH', realpath(dirname(__FILE__).'/../..'));
define('APP_WALLPATH', realpath(dirname(__FILE__).'/../../../wallstreet'));

if(IS_REAL_SERVER) {
    $allow_domains = array(
        'choicestock.co.kr'  => 'common',
        'www.choicestock.co.kr'  => 'common',
        'm.choicestock.co.kr'  => 'common',
        'x1.choicestock.co.kr'  => 'x1',
        'choicestock.toogo.kr'  => 'toogo',
        'kiwoom.choicestock.co.kr'  => 'kiwoom',
        'kiwoom2.choicestock.co.kr'  => 'kiwoom2',
        'paxnet.choicestock.co.kr'  => 'paxnet',
        'hantoo.choicestock.co.kr'  => 'hantoo',
        'hana.choicestock.co.kr'  => 'hana',
        'eugene.choicestock.co.kr'  => 'eugene',
        'demo.choicestock.co.kr'  => 'demo',
    );
    if(isset($_SERVER['HTTP_HOST']) && strlen($_SERVER['HTTP_HOST']) > 0 ) {
        $host = strtolower($_SERVER['HTTP_HOST']);
        if( ! isset($allow_domains[$host])) {
            die('The access is incorrect.');
        }
        $channel = $allow_domains[$host];
    }
    define('ADMIN_DOMAIN', 'www.choicestock.co.kr');
    define('API_URL', 'http://data-api.us.datahero.co.kr');

    define('WEBDATA', APP_WALLPATH.'/webdata');
    define('HOME_URL', 'https://www.choicestock.co.kr');
    //define('WEBDATA', '/home/datahero/html/wallstreet/webdata');

	/* SERVICE */
	define('MID_G', 'dataherosm'); // 상점아이디
	define('MERCHANTKEY_G', 'lEPFshOgfTLEt60rnoAoR7fFkWortqVwwaDkoeFzy3X3BoZ6qrSYdOr3Oz84b07yntg/s/KgK6xz5SbwHHFc6g=='); // 상점키

	define('MID_A', 'datahero1m'); // 상점아이디(월자동)
	define('MERCHANTKEY_A', 'fXgoF9p0EP1X8BUZ43S6JXx/fCXDggZ2JHdopu/7FN11nGhuOBhjDw+IO8lk7N5xKQWP5thxYbB4tplTxH7v2Q=='); // 상점키(월자동)

	define('CS01_PRICE_1', '33000'); // 월결제금액
	define('CS01_PRICE_2', '148500'); // 3개월금액
	define('CS01_PRICE_3', '264000'); // 6개월금액
	/* SERVICE */
} else {
    $channel = 'devtest';
    //include @realpath(dirname(__FILE__).'/dev_constants.php');

    if(strstr($_SERVER['HTTP_HOST'], 'pub.choicestock.co.kr')) {
	    define('ADMIN_DOMAIN', 'pub.choicestock.co.kr');
	    define('HOME_URL', 'https://pub.choicestock.co.kr');
	    define('WEBDATA', '/home/datahero/html/wallstreet/webdata');
    }
	else {
	    define('ADMIN_DOMAIN', 'capdev.choicestock.co.kr');
	    define('HOME_URL', 'https://capdev.choicestock.co.kr');
	    define('WEBDATA', APP_WALLPATH.'/webdata');
	}

    define('API_URL', 'http://data-api.us.datahero.co.kr');

	/* TEST */
	define('MID_G', 'nicepay00m'); // 상점아이디
	define('MERCHANTKEY_G', 'EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=='); // 상점키

	define('MID_A', 'nictest04m'); // 상점아이디(월자동)
	define('MERCHANTKEY_A', 'b+zhZ4yOZ7FsH8pm5lhDfHZEb79tIwnjsdA0FBXh86yLc6BJeFVrZFXhAoJ3gEWgrWwN+lJMV0W4hvDdbe4Sjw=='); // 상점키(월자동)

	define('CS01_PRICE_1', '33000'); // 월결제금액
	define('CS01_PRICE_2', '148500'); // 3개월금액
	define('CS01_PRICE_3', '264000'); // 6개월금액
	/* TEST */
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

define('X1', 'x1'); //이머니
define('KW', 'kw'); //키움
define('PX', 'px'); //팍스넷
define('HT', 'ht'); //한투
define('HN', 'hn'); //하나
define('EG', 'eg'); //유진

define('INC_PATH', APP_PATH.'/_application/views/mobile/inc');
define('X1_INC_PATH', APP_PATH.'/_application/views/'.X1.'/inc');
define('KW_INC_PATH', APP_PATH.'/_application/views/'.KW.'/inc');
define('PX_INC_PATH', APP_PATH.'/_application/views/'.PX.'/inc');
define('HT_INC_PATH', APP_PATH.'/_application/views/'.HT.'/inc');
define('HN_INC_PATH', APP_PATH.'/_application/views/'.HN.'/inc');
define('EG_INC_PATH', APP_PATH.'/_application/views/'.EG.'/inc');
define('LOG_PATH', APP_PATH.'/logdata');

define('NAVER_CLIENT_ID', 'peKb_1mkZckcca2_j77t');
define('NAVER_CLIENT_SECRET', 'QWcZaoKfcr');

define('KAKAO_CLIENT_ID', '77ec55bcf2ba8cefb81015bb5174e953');
define('KAKAO_CLIENT_SECRET', 'cNAvPLK7e1Z5UQ2jFH45x3mUXH2IuvZC');

define('SRV_CODE', 'CS01');
define('SRV_NAME', '초이스스탁');
define('SRV_PRICE', '55000');

define('PUSH_SKEY', '2467016041cb2140759f3518821433721e7b5136');
define('PUSH_API', 'https://api.bizppurio.com/v2/message');
define('PUSH_API_TEST', 'https://dev-api.bizppurio.com:10443/v2/message');
define('PUSH_ACCOUNT', 'datahero1');
define('PUSH_FROM', '0262252300');
define('ADMIN_PHONE', '01050176402');

/*이머니-X1*/
define('API_KEY_X1', 'K_zTp4dxld30uyEEe08GO7');
/*장시작시간*/
define('START_TIME', '2330'); /* 매년 11월 초 ~ 3월 13일 : 23:30~0600, 썸머타임은 3월14일~11월 초 : 22:30~0500*/

// SHOW_ALL_QUERY 가 true로 셋팅시 쿼리리스트 html들이 담길 글로벌 배열 
$show_all_query = array();

/* End of file constants.php */
/* Location: ./application/config/constants.php */