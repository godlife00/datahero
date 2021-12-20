<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
if( isset($company_info['cp_ticker']) && isset($company_info['cp_usname']) && isset($company_info['cp_korname']) ) {
	$sch_title = $company_info['cp_usname'].'('.$company_info['cp_ticker'].', '.$company_info['cp_korname']. ' | ';
}
else {
	$sch_title = '';
}

if( isset($company_info['cp_short_description']) ) {
	$sch_description = strip_tags($company_info['cp_short_description']);
}
else {
	$sch_description = '투자를 쉽고 편리하게, 미국주식가이드, 종목분석, 종목토론, 아이투자';
}
?>
<title><?=$sch_title;?>아이투자 미국주식</title>
<meta name="robots" content="<?=$sch_title;?>아이투자 미국주식">
<meta name="description" content="<?=$sch_description;?>">
<!-- 오픈 그래프 -->
<meta property="og:title" content="아이투자 미국주식" />
<meta property="og:type" content="website">
<meta property="og:description" content="투자를 쉽고 편리하게, 미국주식가이드, 종목분석, 종목토론, 아이투자">
<link rel="shortcut icon" href="/img/favicon2.ico" type="image/x-icon" />
<meta property="og:image" content="/img/globalstock/img/thumb-meta.png">
<meta property="og:image:width" content="410"/>
<meta property="og:image:height" content="210"/>
<meta property="og:url" content="http://us.itooza.com">
<!-- 소셜미디어
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="페이지 제목">
<meta name="twitter:description" content="페이지 설명">
<meta name="twitter:image" content="http://www.mysite.com/article/article1.html">
<meta name="twitter:domain" content="사이트 명"> -->
<!-- jquery-1.12 -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>

<!-- Swiper -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/js/swiper.min.js"></script>
<!-- css -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php if(isset($vchart) && $vchart==true) { ?>
<link rel="stylesheet" type="text/css" href="/css/globalstock_vchart.css?v=<?=filemtime(APP_PATH.'/css/globalstock_vchart.css')?>" />
<?php }else {?>
<link rel="stylesheet" type="text/css" href="/css/globalstock.css?v=<?=filemtime(APP_PATH.'/css/globalstock.css')?>" />
<?php }?>
<link rel="stylesheet" type="text/css" href="/css/override.css?v=<?=filemtime(APP_PATH.'/css/override.css')?>" />
<!-- js -->
<?php if(isset($vchart) && $vchart==true) { ?>
<script src="/js/globalstock_vchart.js?v=<?=filemtime(APP_PATH.'/js/globalstock_vchart.js')?>"></script>
<?php }else {?>
<script src="/js/globalstock.js?v=<?=filemtime(APP_PATH.'/js/globalstock.js')?>"></script>
<?php }?>
<script src="/js/globalstock.dev.js?v=<?=filemtime(APP_PATH.'/js/globalstock.dev.js')?>"></script>
<script async="" src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><!-- 구글 애드센스 자동광고-->
</head>

<body class="globalStock"> <!-- 미국주식 사이트는 class="globalStock"로 css 구분 -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55222462-3"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-55222462-3');

var search_ticker_list = <?=json_encode($search_ticker_list)?>;
</script>
    <div id="wrap" class="mainbg<?=$header_data['is_main_index'] ? '' : '-none'?>"><!-- 메인 bg용 class -->
        <div id="header">
            <div class="headerArea">                
			<h1 class="headerLogo"><a href='http://www.itooza.com/' target="_blank" ><img src="/img/globalstock/img/_h1_itzlogo.png" alt="아이투자" class="logo_itz"></a><a href='/'><img src="/img/globalstock/img/_h1_logo.png" alt="미국주식" class="logo_gs"></a></h1>
                <div class="searchArea">
                    <form action="#" onsubmit="return false">
                        <fieldset>
                            <input type="text" placeholder="종목명을 입력하세요." class="searchInput">
                            <input type="image" src="/img/globalstock/img/sch_btn.png" alt="검색" class="searchBtn" onclick="moveToSearch($('.globalStock #header .searchArea .AutoComplete'))">
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

			<div class="loginArea">
				<span class="loginTitle"><a href="/guide/intro">미국주식 시작하기</a></span>                
				<span class="loginId">
					<span class="log">
					<?php if($this->session->userdata('is_login')) {?>
						<a href="/member/mypage/mylist"><?=$this->session->userdata('user_filname');?></a>님 안녕하세요ㆍ<a href="/auth/logout" class="logout">로그아웃</a>                    
					<?php }else {?>
						<a href="/auth/login?rd=<?=urlencode(str_replace('/index.php', '', $_SERVER["PHP_SELF"]));?>" class="login">로그인</a>
					<?php }?>
					</span>
					<!-- //log -->
				</span>
			</div>
			<!-- //loginArea -->

		</div>         
		<!-- //headerArea -->

<?php if(sizeof($current_ticker_info) > 0) :  ?>
        <!-- 검색 요약 -->
        <div class="schSummary">
            <ul>
                <li class="title bd"><?=$current_ticker_info['name']?></li>
                <li class="sum"><?=$current_ticker_info['korname']?></li>
                <li class="d_num"><?=number_format($current_ticker_info['close_price'], 2)?></li>
                <li class="d_per <?=$current_ticker_info['diff_rate'] > 0 ? 'increase' : 'decrease'?>">(<?=$current_ticker_info['diff_rate'] > 0 ? '+' : ''?><?=number_format($current_ticker_info['diff_price'], 2)?>)</li><!-- increase 증가, decrease 감소 -->
            </ul>
        </div>
        <!-- //schSummary -->
<?php endif; ?>


	</div>
	<!-- //header -->
	<div id="nav"><!-- class = navFixed 상단고정 header -->            
		<div class="gnbArea">
			<ul class="gnb">
				<li><a href="#" class="active">스크리닝</a></li>
				<li><a href="/vchart">V차트</a></li>
				<li><a href="/mri">주식MRI</a></li>
				<li><a href="#">종목쇼핑</a></li>
				<li><a href="#">투자대가주</a></li>
				<li><a href="#">큰손추적</a></li>
				<li><a href="#">뉴스</a></li>
			</ul>
			<a href="#" class="logLink">프리미엄&#183;가입</a>
			<div class="searchArea searchFixed">
				<form action="#" onsubmit="return false">
					<fieldset>
						<input type="text" placeholder="종목명을 입력하세요." class="searchInput_fixed">
						<input type="image" src="/img/schFixd_btn.png" alt="검색" class="searchBtn" onclick="moveToSearch($('.globalStock .searchFixed .AutoComplete'))">
					</fieldset>                    
				</form>    

				<!-- 검색어 자동완성 -->
				<div class="AutoComplete"><!-- //자동완성 결과 노출 class : _show -->
					<ul>
					</ul>
				</div>
				<!-- //AutoComplete 검색어 자동완성 끝 -->

			</div>
			<!-- //searchArea searchFixed -->
		</div>
		<!-- //gnbArea -->
	</div>
	<!-- //nav -->
	<div id="container"<?php if($header_class) echo ' class="'.$header_class.'"';?> <?php if($header_class && $header_class == 'payment') echo 'style="padding-top: 43px; margin: 0 auto; max-width: 100%; min-width: 960px; width: 100%; box-sizing: border-box;"';?>> <!-- 주요 콘텐츠 -->