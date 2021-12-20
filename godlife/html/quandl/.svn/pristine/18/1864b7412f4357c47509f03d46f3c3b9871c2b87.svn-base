<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => APP_URL.'/adminpanel'
);

/*
navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"url_target" => "_self",
	"icon" => "fa-home",
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)
*/

$page_nav = array(
	"dashboard" => array(
		"level"	=> "5",
		"title" => "Home",
		"icon" => "fa-home",
		"url" => APP_URL.'/adminpanel',
	),

	"data" => array(
		"level"	=> "9",
		"title" => "API 데이터",
		"icon" => "fa-database",
		"sub" => array(
			"sf_test" => array(
				'title' => 'SF Test',
				'url' => APP_URL.'/adminpanel/data/sf_test'
			),
			"company" => array(
				'title' => 'Company Info',
				'url' => APP_URL.'/adminpanel/data/company'
			),
			"ticker" => array(
				'title' => 'Ticker',
				'url' => APP_URL.'/adminpanel/data/ticker'
			),
			"indicator" => array(
				'title' => 'Indicator and Description',
				'url' => APP_URL.'/adminpanel/data/indicator'
			),

			"sf1" => array(
				'title' => 'SF1',
				'url' => APP_URL.'/adminpanel/data/sf1'
			),
			"sf2" => array(
				'title' => 'SF2',
				'url' => APP_URL.'/adminpanel/data/sf2'
			),
			"sf3" => array(
				'title' => 'SF3',
				'url' => APP_URL.'/adminpanel/data/sf3'
			),
			"sf3a" => array(
				'title' => 'SF3A',
				'url' => APP_URL.'/adminpanel/data/sf3a'
			),
			"sf3b" => array(
				'title' => 'SF3B',
				'url' => APP_URL.'/adminpanel/data/sf3b'
			),
			"sep" => array(
				'title' => 'SEP',
				'url' => APP_URL.'/adminpanel/data/sep'
			),
			"daily" => array(
				'title' => 'DAILY',
				'url' => APP_URL.'/adminpanel/data/daily'
			),
			"daily2" => array(
				'title' => 'DAILY (NYSE, NASDAQ, NYSEMKT)',
				'url' => APP_URL.'/adminpanel/data/daily/last_daily'
			),
			"actions" => array(
				'title' => 'ACTIONS',
				'url' => APP_URL.'/adminpanel/data/actions'
			),
			"sp500" => array(
				'title' => 'SP500',
				'url' => APP_URL.'/adminpanel/data/sp500'
			),
		)
	),

	"manage" => array(
		"level"	=> "9",
		"title" => "시스템",
		"icon" => "fa-gear",
		"sub" => array(

			"admins" => array(
				'title' => '어드민 관리',
				'url' => APP_URL.'/adminpanel/manage/admins'
			),

/*
			"sms" => array(
				'title' => 'SMS',
				'url' => APP_URL.'/adminpanel/manage/sms_list'
			),
*/
			"history" => array(
				'title' => '어드민 로그',
				'url' => APP_URL.'/adminpanel/manage/history'
			),
			"set_format" => array(
				'title' => '디스플레이 포멧 설정',
				'url' => APP_URL.'/adminpanel/manage/set_format'
			),
		)
	),


/*
	"mail" => array(
		"level"	=> "5",
		"title" => "메일",
		"icon" => "fa-envelope",
		"sub" => array(
			"send" => array(
				'title' => '메일발송',
				"url" => APP_URL.'/adminpanel/mail/mail_list_detail'
			),

			"list" => array(
				'title' => '발송 리스트',
				'url' => APP_URL.'/adminpanel/mail/mail_list'
			),
		)
	),
*/

);



//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
?>
