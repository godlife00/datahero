<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no" />
    <title><?=$meta_title?></title>
    <meta name="robots" content="투자를 쉽고 편리하게 - 초이스스탁US">
    <meta name="description" content="투자를 쉽고 편리하게, 미국주식가이드, 종목분석, 종목토론, 데이터히어로">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="theme-color" content="#2C295D">
    <!-- 오픈 그래프 -->
    <meta property="og:title" content="<?=$meta_title?>" />
    <meta property="og:type" content="website">
    <meta property="og:description" content="투자를 쉽고 편리하게, 미국주식가이드, 종목분석, 종목토론, 데이터히어로">
    <meta property="og:image" content="/img/choicestockus_thumb.png">
    <meta property="og:image:width" content="410" />
    <meta property="og:image:height" content="210" />
    <meta property="og:url" content="www.choicestock.co.kr">
	<!-- 파비콘 favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
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
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
	<link rel="canonical" href="https://www.choicestock.co.kr">
	<!-- css -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/css/<?=(!IS_REAL_SERVER)? 'dev_':''?>globalstock.css?v=<?=filemtime(APP_PATH.'/css/globalstock.css')?>" />
    <!-- js -->
    <script src="/js/globalstock.js?v=<?=filemtime(APP_PATH.'/js/globalstock.js')?>"></script>
    <script src="/js/globalstock.dev.js?v=<?=filemtime(APP_PATH.'/js/globalstock.dev.js')?>"></script>
	<script src="/js/chartjs.dev.js?v=<?=filemtime(APP_PATH.'/js/chartjs.dev.js')?>"></script>
	<script src="/js/jquery.jscroll.js"></script>
    <script>
    var search_ticker_list = <?=json_encode($search_ticker_list)?>;
    </script>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-T9XN9KD');</script>
	<!-- End Google Tag Manager -->
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window,document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	 fbq('init', '644909519793122'); 
	fbq('track', 'PageView');
	</script>
	<noscript>
	 <img height="1" width="1" 
	src="https://www.facebook.com/tr?id=644909519793122&ev=PageView
	&noscript=1"/>
	</noscript>
	<!-- End Facebook Pixel Code -->
	<script async="" src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><!--구글 애드센스 자동광고-->
	<script type="text/javascript">
	$(document).ready(function(){
	    $(document).bind("contextmenu", function(e) {
	        return false;
	    });
	});
	</script>
</head>

<body class="globalStock" oncontextmenu="return false;">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154121923-4"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-154121923-4');
	</script>-->
	<?php if($is_main === TRUE) :?>

		<?php if($is_event === true) :?>
		<div class="top_banner event" id="layer_pop">
			<a href="/main/first_event" class="event_btn"><img src="/img/banner/1year_event.png" alt="오픈이벤트 당첨자 발표"></a>
			<label for="pop_today" class="btn_day"><input type="checkbox" name="pop_today" id="pop_today" /> 오늘 하루 열지 않기</label>
			<a href="javascript:closeWin();" class="btn_close"><img src="/img/banner/btn_clse.png" alt="닫기"></a>
		</div>
		<!-- 오늘 하루 열지 않기 -->
		<script language="Javascript" type="text/javascript">
			cookiedata = document.cookie;
			if (cookiedata.indexOf("ncookie=done") < 0) {
				document.getElementById('layer_pop').style.display = "inline";
			} else {
				document.getElementById('layer_pop').style.display = "none";
			}            
			function setCookie(name, value, expirehours) {
				var todayDate = new Date();
				todayDate.setHours(todayDate.getHours() + expirehours);
				document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
			}
			function closeWin() {
				if (document.getElementById("pop_today").checked) {
					setCookie("ncookie", "done", 24);
				}
				document.getElementById('layer_pop').style.display = "none";
			}
		</script>
		<!-- //오늘 하루 열지 않기 -->
		<?php endif;?>

		<?php if($this->session->userdata('free_notice') !=''):?>
		<!-- 3일 무료체험 상단배너 -->
		<div class="weeks_free3">
			<p><?=$this->session->userdata('free_notice')?> <a href="#" class="btn_close">X</a></p>
		</div>
		<script language="Javascript" type="text/javascript">
			$('.globalStock .weeks_free3 p .btn_close').on("click", function () {
				$('.globalStock .weeks_free3').slideUp();
			})        
		</script>
		<!-- //3일 무료체험 상단배너 -->
		<?php endif;?>

		<!-- 미주미 상단 협업프로모션-->
		<div class="mijumi_promotion top_banner">
			<h1 class="logo"><img src="/img/logo_mijumi.png" alt="미주미x데이터히어로"></h1>
			<p>미국주식투자를 위해 함께합니다.</p>
		</div>

	<?php endif;?>
	<div id="wrap">
		<?php if($show_menu === TRUE) :?>
        <!-- 하단 GNB -->
        <div class="gnb">
            <ul class="list">
                <li class="home<?=$header_template=='1' ? ' active':''?>"><a href="/"><i></i> 홈</a></li>
                <li class="stock<?=($header_template>='2' && $header_template<='3') ? ' active':''?>"><a href="/stock/recommend">추천</a></li>
                <li class="analysis<?=($header_template>='4' && $header_template<='5') ? ' active':''?>"><a href="/main/search">진단</a></li>
                <li class="excavation<?=(($header_template>='6' && $header_template<='9') || $header_template=='12') ? ' active':''?>"><a href="/attractiveness/attractive?sort=total&netincome=all&marketcap=over100billion">발굴</a></li>
                <li class="menu<?=$header_template=='16' ? ' active':''?>"><a href="/member/menu">메뉴</a></li>
            </ul>
        </div>
        <!-- //gnb -->
		<?php endif;?>
        <?=$header_contents_html?>
         <div id="container" class="<?=$container_class?>">
            <!-- 주요 콘텐츠 -->