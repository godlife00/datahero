<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>미식가</title>
    <meta name="robots" content="데이터히어로 미국주식">
    <meta name="description" content="투자를 쉽고 편리하게, 미국주식가이드, 종목분석, 종목토론, 데이터히어로">
    <!-- 오픈 그래프 -->
    <meta property="og:type" content="website">
    <meta property="og:description" content="투자를 쉽고 편리하게, 미국주식가이드, 종목분석, 종목토론, 데이터히어로">
    <!-- <meta property="og:image" content="https://image.ajunews.com/images/site/img/module/vietnam/favicon_400x210.png"> -->
    <meta property="og:image:width" content="410" />
    <meta property="og:image:height" content="210" />
    <meta property="og:url" content="">
    <!-- jquery-1.12 -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- highcharts -->
	<script src="https://code.highcharts.com/highcharts.js"></script> 
	<script src="https://code.highcharts.com/modules/series-label.js"></script> 
	<script src="https://code.highcharts.com/highcharts-more.js"></script> 
	<script src="https://code.highcharts.com/modules/solid-gauge.js"></script> 
	<script src="https://code.highcharts.com/modules/exporting.js"></script> 
	<script src="https://code.highcharts.com/modules/export-data.js"></script> 
	<script src="https://code.highcharts.com/modules/accessibility.js"></script> 
	<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/js/swiper.min.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/css/wm_globalstock.css" />
    <!-- js -->
    <script src="/js/wm_globalstock.js"></script>
	<script src="/js/wm_chartjs.dev.js?v=<?=filemtime(APP_PATH.'/js/wm_chartjs.dev.js')?>"></script>
</head>

<body class="globalStock"> <!-- 미국주식 사이트는 class="globalStock"로 css 구분 -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154121923-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-154121923-3');
  var search_ticker_list = <?=json_encode($search_ticker_list)?>;

$(document).ready(function() {
		$(document).bind("contextmenu", function(e){return false;});
		$(document).bind("selectstart", function(e){return false;});
		$(document).bind("dragstart", function(e){return false;});
});
</script>
    <div id="wrap">
        <div id="header">
            <div class="headerArea">
                <h1 class="headerLogo">종목명</h1>
                <div class="searchArea">

                    <form action="#" onsubmit="return false">
                        <fieldset>
                            <input type="text" placeholder="종목명을 입력하세요." class="searchInput">
                            <input type="image" src="/img/wm/sch_btn.png" alt="검색" class="searchBtn" onclick="moveToSearch($('.globalStock #header .searchArea .AutoComplete'))">
                        </fieldset>                    
                    </form>                               
                    
                    <!-- 검색어 자동완성 -->
                    <div class="AutoComplete"><!-- //자동완성 결과 노출 class : _show -->
                        <ul>
                        </ul>
                    </div>

                    <!-- //AutoComplete 검색어 자동완성 끝 -->
                </div>
                <!-- //searchArea -->
            </div>
            <!-- //headerArea -->
        </div>
        <!-- //header -->

        <div id="container">