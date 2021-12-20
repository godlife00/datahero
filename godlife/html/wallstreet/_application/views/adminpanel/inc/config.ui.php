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


	"main" => array(
		"level"	=> "9",
		"title" => "월가히어로",
		"icon" => "fa-gear",
		"sub" => array(
			"recommend" => array(
				'title' => '종목추천',
				'url' => APP_URL.'/adminpanel/main/recommend'
			),
			"analyze" => array(
				'title' => '종목분석',
				'url' => APP_URL.'/adminpanel/main/analyze'
			),
			"explore" => array(
				'title' => '탐구생활',
				'url' => APP_URL.'/adminpanel/main/explore'
			),
			"master" => array(
				'title' => '대가종합',
				'url' => APP_URL.'/adminpanel/main/master'
			),
			"notify" => array(
				'title' => '알림관리',
				'url' => APP_URL.'/adminpanel/main/notify'
			),
			"morning" => array(
				'title' => '모닝브리핑',
				'url' => APP_URL.'/adminpanel/main/morning'
			),
			"vod_mjm" => array(
				'title' => '미주미동영상',
				'url' => APP_URL.'/adminpanel/main/vod_mjm'
			),
			"push" => array(
				'title' => '푸시',
				'url' => APP_URL.'/adminpanel/main/push'
			),
		)
	),
	"data" => array(
        "level"	=> "9",
		"title" => "API 데이터",
		"icon" => "fa-database",
		"sub" => array(
			"ticker" => array(
				'title' => '종목 데이터',
				'url' => APP_URL.'/adminpanel/data/ticker'
			),
			"spider" => array(
				'title' => '종목별 SPIDER',
				'url' => APP_URL.'/adminpanel/data/spider'
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


			"sms" => array(
				'title' => 'SMS',
				'url' => APP_URL.'/adminpanel/manage/sms_list'
			),
			"history" => array(
				'title' => '어드민 로그',
				'url' => APP_URL.'/adminpanel/manage/history'
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
