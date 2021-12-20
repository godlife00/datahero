<!DOCTYPE html>
<html lang="en"<?=($part_name=='kiwoom') ? ' class="kiwoom"' : ''?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no" />
    <title>월가히어로 </title>
    <meta name="robots" content="월가히어로 ">
    <meta name="description" content="월가히어로 ">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="theme-color" content="#2C295D">
    <!-- 오픈 그래프 -->
    <meta property="og:type" content="website">
    <meta property="og:description" content="월가히어로 ">
    <!-- <meta property="og:image" content="favicon_400x210.png"> -->
    <meta property="og:image:width" content="410" />
    <meta property="og:image:height" content="210" />
    <meta property="og:url" content="http://월가히어로 ">
    <!-- jquery-1.12 -->
    <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- highcharts -->
    <script src="//code.highcharts.com/highcharts.js"></script>
    <script src="//code.highcharts.com/modules/series-label.js"></script>
    <script src="//code.highcharts.com/highcharts-more.js"></script>
    <script src="//code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="//code.highcharts.com/modules/exporting.js"></script>
    <script src="//code.highcharts.com/modules/export-data.js"></script>
    <script src="//code.highcharts.com/modules/accessibility.js"></script>
    <script src="//code.highcharts.com/modules/no-data-to-display.js"></script>
    <script src="//code.highcharts.com/modules/broken-axis.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <!--script src="js/chartjs.js"></script-->

    <!-- Swiper -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/js/swiper.min.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<?php if($is_shinhan === TRUE) :?>
    <link rel="stylesheet" type="text/css" href="/css/globalstock_shinhan.css?v=<?=filemtime(APP_PATH.'/css/globalstock_shinhan.css')?>" />
	<?php else :?>
    <link rel="stylesheet" type="text/css" href="/css/globalstock.css?v=<?=filemtime(APP_PATH.'/css/globalstock.css')?>" />
	<?php endif;?>
    <!--<link rel="stylesheet" type="text/css" href="/css/globalstock.dev.css?v=<?=filemtime(APP_PATH.'/css/globalstock.dev.css')?>" />-->
    <!-- js -->
    <script src="/js/globalstock.js?v=<?=filemtime(APP_PATH.'/js/globalstock.js')?>"></script>
    <script src="/js/globalstock.dev.js?v=<?=filemtime(APP_PATH.'/js/globalstock.dev.js')?>"></script>
    <script src="/js/chartjs.dev.js?v=<?=filemtime(APP_PATH.'/js/chartjs.dev.js')?>"></script>
    <script>
    var search_ticker_list = <?=json_encode($search_ticker_list)?>;
    </script>
</head>

<body class="globalStock">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <?php //if(CHANNEL == 'kiwoom') : // 키움증권 용 GA 코드 처리 ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-154121923-2"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-154121923-2');
    </script>
    <?php //endif; ?>

    <!-- 검색, 자동완성 영역 -->
    <div class="schArea">
        <!-- class = schFocus 로 검색, 자동완성 레이어 노출됩니다. -->
        <!-- 종목명 검색창 -->
        <div class="searchArea">
            <a href="#" class="schBack"><img src="/img/sch_back.png" alt="뒤로가기"></a>
            <form action="" name="topsearch" onsubmit="var v = $('#autocomplete_list li a._on span.schCode').html(); if(v.length > 0 && $('#autocomplete_list').get(0).children.length > 0) { this.action='/search/invest_charm/'+v; setSearchHistory(v); return true; }; return false;">
                <fieldset>
                    <input type="text" placeholder="종목명을 입력하세요." name='keyword' class="searchInput" autocomplete="off">
                    <input type="image" src="/img/icon_searchB@2x.png" alt="검색" class="searchBtn">
                </fieldset>
            </form>

            <!-- 최근 검색어 -->
            <div class="AutoLatelySch">
                <!-- //최근 검색어 결과 노출 class : _show -->
                <ul>
                </ul>
            </div>
            <!-- //AutoLatelySch 최근 검색어 끝 -->

            <!-- 검색어 자동완성 -->
            <div class="AutoComplete">
                <!-- //자동완성 결과 노출 class : _show -->
                <ul id='autocomplete_list'>
                </ul>
            </div>
            <!-- //AutoComplete 검색어 자동완성 끝 -->
        </div>
        <!-- //종목명 검색창 -->
    </div>
    <!-- //schArea 검색, 자동완성 영역 -->


    <!-- 알림 영역 -->
    <div class="alarmArea">
        <!-- class = schFocus 로 검색, 자동완성 레이어 노출됩니다. -->
        <!-- 알림 리스트 -->
        <div class="layer_header">
            <div class="history_back" title="이전페이지">
                <a class="menu-trigger" href="#">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>
            <!-- //history_back -->
            <div class="headerTop">
                <h1 class="headerLogo"><a href="#">알림</a></h1>
            </div>
            <!-- //headerTop -->
        </div>
        <!-- //layer_header -->
        <div class="alarmList">
            <ul>
                <?php if( ! sizeof($header_data['noti_list'])) : ?>
                <li style='margin-top:30px;text-align:center;' class='title'>새로운 알림이 없습니다.</li>
                <?php endif; ?>
                
                <?php foreach($header_data['noti_list'] as $noti_item) : ?>
                <li>
                    <a href="<?=(strlen($noti_item['nt_url']) > 3 ) ? $noti_item['nt_url'] : '#'?>">
                        <span class="title"><?=$header_data['noti_table_map'][$noti_item['nt_table']]?></span>
                        <span class="day"><?=date('m/d', strtotime($noti_item['nt_display_date']))?></span>
						<span class="sum">
						<?php if($noti_item['nt_table']=='recommend_tb') :?>
							<?=$noti_item['nt_title']?><?=($noti_item['nt_ticker_name'] !='' && $noti_item['nt_ticker'] !='') ? ' - '.$noti_item['nt_ticker_name'].'('.$noti_item['nt_ticker'].')':''?>
						<?php else :?>
						<?=$noti_item['nt_title']?>
						<?php endif;?>
						</span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- //alarmList -->
    </div>
    <!-- //alarmArea 알람 -->

    <div id="wrap">
        <?=$header_contents_html?>
         <div id="container" class="<?=$container_class?>">
            <!-- 주요 콘텐츠 -->