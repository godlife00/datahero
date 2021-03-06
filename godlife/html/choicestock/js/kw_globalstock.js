$(document).ready(function () {
    /************************/
    /****  Modal popup  *****/
    /************************/
    //로그인 팝업
    var setWindow = $('.setting_pop');

    /* 개발처리
    var setpay_edt01 = $('.pay_edt_01');
    var setpay_edt02 = $('.pay_edt_02');
    var setpay_edt02_1 = $('.pay_edt_02_1');
    var setpay_edt02_2 = $('.pay_edt_02_2');
    var setcatch_edt01 = $('.catch_edt_01');
    var setcatch_edt02 = $('.catch_edt_02');
    var setcatch_edt03 = $('.catch_edt_03');
    var setcatch_edt03_2 = $('.catch_edt_03_2');
    var setcatch_edt03_3 = $('.catch_edt_03_3');
    */
    var setterms_01 = $('.pay_terms_01');
    var setterms_02 = $('.pay_terms_02');
    var setterms_03 = $('.pay_terms_03');

	// Show Hide
    $('.clse_trigger').click(function () {
        setWindow.addClass('open');
        $('html, body').css("overflow", "hidden");
    });

    /* 개발처리
    $('.show_pop01').click(function () {
        setpay_edt01.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop02').click(function () {
        setpay_edt02.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop02_1').click(function () {
        setpay_edt02_1.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop02_2').click(function () {
        setpay_edt02_2.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop03').click(function () {
        setWindow.removeClass('open');
        setcatch_edt01.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop04').click(function () {
        setWindow.removeClass('open');
        setcatch_edt02.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop05').click(function () {
        setWindow.removeClass('open');
        setcatch_edt03.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop05_2').click(function () {
        setWindow.removeClass('open');
        setcatch_edt03_2.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.show_pop05_3').click(function () {
        setWindow.removeClass('open');
        setcatch_edt03_3.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    */
    $('.age_pop01').click(function () {
        setterms_01.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.age_pop02').click(function () {
        setterms_02.addClass('open');
        $('html, body').css("overflow", "hidden");
    });
    $('.age_pop03').click(function () {
        setterms_03.addClass('open');
        $('html, body').css("overflow", "hidden");
    });

	$('#setting .close').click(function () {
        setWindow.removeClass('open');
        $('html, body').css("overflow", "scroll");
    });
    $('#pay_cncl .close').click(function () {
        setWindow.removeClass('open');
        $('html, body').css("overflow", "scroll");
    });
    $('#pay_cncl_tr .close').click(function () {
        setWindow.removeClass('open');
        $('html, body').css("overflow", "scroll");
    });
    $('#catch_cncl .close').click(function () {
        setWindow.removeClass('open');
        $('html, body').css("overflow", "scroll");
    });
    // Hide Window
    setWindow.find('>.bg').mousedown(function (event) {
        setWindow.removeClass('open');
        $('html, body').css("overflow", "scroll");
        return false;
    });    

    // 검색결과 박스 위치 조정
    if ($('.arrow_box').length) {
        var arrowLEft = $(".arrow_box.btm").offset();    
        if (arrowLEft.left < 100) {        
            $(".arrow_box.btm").addClass('left_22');
        } else if (arrowLEft.left > 600 ) {
            $(".arrow_box.btm").addClass('left_65');
        }
    }

    //select
    $(function () {
        var selectTarget = $('.selectbox select');

        // focus 가 되었을 때와 focus 를 잃었을 때
        selectTarget.on({
            'focus': function () {
                $(this).parent().addClass('focus');
            },
            'blur': function () {
                $(this).parent().removeClass('focus');
            }
        });

        selectTarget.change(function () {
            var select_name = $(this).children('option:selected').text();
            $(this).siblings('label').text(select_name);
            $(this).parent().removeClass('focus');
        });
    });

    // 캐치 리스트 갯수 체크
    var catch_length = $('.globalStock .catch_wrap .chart_catch').length;
    if (catch_length == 5) {
        $('.globalStock .catch_wrap .chart_catch_wrap').css('padding-bottom', '94px');
    } else {
        $('.globalStock .catch_wrap .chart_catch_wrap').css('padding-bottom', '0');
    }

    /* 목록 드래그 드롭 */
    $(function () {
        $("#sortable").sortable({
            handle: 'span',
        });
        $("#sortable").disableSelection();
    });
    
    // Hide Header on on scroll down
    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var navbarHeight = $('header').outerHeight();

    $(window).scroll(function (event) {
        didScroll = true;
    });
    setInterval(function () { if (didScroll) { hasScrolled(); didScroll = false; } }, 0);
    function hasScrolled() {
        clearTimeout($.data(this, 'scrollTimer'));
        var st = $(this).scrollTop();
        if (Math.abs(lastScrollTop - st) <= delta) return; if (st > lastScrollTop && st > navbarHeight) {
            // Scroll Down            
            $('.globalStock .gnb').slideUp(150);
            
        } else {
            // Scroll Up            
            $('.globalStock .gnb').show();      
        } lastScrollTop = st;
        
    }


    //푸터 하단 고정 search_top searching
    var winHeight = $(window).height();
    var footerHeight = $('#footer').height() - 66;
    var bodyHeight = $('html, body').height() - footerHeight;
    // if (winHeight >= bodyHeight) {
    //     $('#footer').addClass('fix_footer');
    //     console.log("add fix");
    // }

    // 마이페이지 footer 고정
    if ($('.globalStock .sub_login .mapage_service').length) {
        if (winHeight >= bodyHeight) {
            $('#footer').addClass('fix_footer');
        }
    }

    // #footer padding-bottom 계산
    if (!$('.globalStock .gnb').length) {        
        $('#footer').css('padding-bottom', '0');
    }
    if ($('.sub_payment .fix_btn').length) {
        $('#footer').css('padding-bottom', '55px');
    }

    //검색
    if ($('.sub_search').length) {
        // $('#footer').removeClass('fix_footer');
        $('.searchInput').focus().click();
    }
    $('.globalStock .sub_search .searching .searchArea .searchInput').on("click", function () {
        // $('#footer').removeClass('fix_footer');        
    });
    $('.globalStock .sub_search .searching .searchArea .searchInput').on("keydown", function () {
        // $('#footer').removeClass('fix_footer');
        $(this).addClass('keydown');
        $('.globalStock .sub_search .searching .searchArea .searchBtn').css('top', '15px');
        $('.globalStock .sub_search .searching .searchArea .sch_title').addClass('keydown');
        $('.globalStock .sub_search .searching .sch_autocomplete').addClass('focus_on').show();
    });
    $('.globalStock .sub_search .searching .searchArea .searchInput').on("focusout", function () {
        $('.globalStock .sub_search .searching .searchArea .sch_title').removeClass('keydown');
        $(this).removeClass('keydown');
        // $('.globalStock .sub_search .searching .searchArea .searchBtn').css('top', '9px');
        $('.globalStock .sub_search .searching .sch_autocomplete').hide();
    });
    $('.globalStock .main_searching .searchArea .searchInput').on("keydown", function () {
        // $('#footer').removeClass('fix_footer');
        $(this).addClass('keydown');
        $('.globalStock .main_searching .searchArea .searchBtn').css('top', '15px');
        $('.globalStock .main_searching .searchArea .sch_title').addClass('keydown');
        $('.globalStock .main_searching .sch_autocomplete').addClass('focus_on').show();
    });

    //탭메뉴
    $(".tabsArea .tab_content").hide();
    $(".tabsArea .tab_content:first").show();

    $("ul.tabs li").click(function () {
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tabsArea .tab_content").hide();
        var activeTab = $(this).attr("rel");
        $("#" + activeTab).fadeIn();
    });

    $("ul.tabs li").click(function () {
        $(".sub_mid.eventPicks_area .ptfo_area.ptfo_tabview").removeClass('ptfo_tabview');
        if ($('.recom_tabs li.ptfo').hasClass("active")) {            
            $('.globalStock .sub_mid .btn_more, .globalStock .main_btm.banner_area .notice').hide();
            $('.globalStock .main_btm').css('margin-top','0')
        } else {            
            $('.globalStock .sub_mid .btn_more, .globalStock .main_btm.banner_area .notice').show();
            $('.globalStock .main_btm').css('margin-top','20px')
        }        
    });

    // 종목추천 - 포트폴리오 탭 노출
    if ($('.globalStock .sub_recom .sub_mid.eventPicks_area .ptfo_area').hasClass('ptfo_tabview') == 1) {        
        $('.globalStock .main_btm.banner_area .notice').hide();
        $('.globalStock .main_btm').css('margin-top','0')
    } else {
        $('.globalStock .main_btm.banner_area .notice').show();
        $('.globalStock .main_btm').css('margin-top','20px')
    }
    $('.ptfo_area.ptfo_tabview ul.tabs li.new_recom').removeClass("active");
    $('.ptfo_area.ptfo_tabview ul.tabs li.ptfo').addClass("active");
    $('.ptfo_area.ptfo_tabview .tabsArea .tab_container .tab_content').show();
    $('.ptfo_area.ptfo_tabview .tabsArea .tab_container .tab_content:first').hide();

    $(".tableetabs_wrap .tabletabs_content").hide();
    $(".tableetabs_wrap .tabletabs_content:first").show();
    $('.globalStock .tabs_menu span:nth-child(1)').on('click', function () {
        $(this).parent('div').children('span').removeClass("active");
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tableetabs_wrap .tabletabs_content").hide();
        $('.tabletabs_content.tabs_01').fadeIn();
    });

    $('.globalStock .tabs_menu span:nth-child(2)').on('click', function () {
        $(this).parent('div').children('span').removeClass("active");
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tableetabs_wrap .tabletabs_content").hide();
        $('.tabletabs_content.tabs_02').fadeIn();
    });

    //전종목 투자매력도
    $('.globalStock .sub_attract .sub_mid.attract_sub .attract_table .txt_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
        $('.globalStock .sub_attract .sub_mid.attract_sub .guide_box').show();
    });

    //종목검색 상단
    $('.globalStock .sub_search .sub_mid.tabs_area .chart_area.diagnosis .chartData .charm .txt_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
        $('.globalStock .sub_search .sub_mid.tabs_area .chart_area.diagnosis .chartData .charm .guide_box').show();
    });
    $('.globalStock .sub_search .sub_mid.tabs_area .evaluation_data .txt_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
        $('.globalStock .sub_search .sub_mid.tabs_area .evaluation_data .guide_box').show();
    });
    $('.globalStock .sub_search .sub_mid.tabs_area .chart_area.diagnosis .data_attainment .txt_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
        $('.globalStock .sub_search .sub_mid.tabs_area .chart_area.diagnosis .data_attainment .guide_box').show();
    });


    //가이드 툴팁
    $('.globalStock .sub_search .sub_mid .investCharm_area .title_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
        $('.globalStock .guide_wrap .guide_box').show();
    });
    //가이드 툴팁 clse
    $('.globalStock .guide_box .clse').on("click", function () {
        $('.guide_layer').css({ 'z-index': -1 });
        $('.globalStock .guide_wrap .guide_box').hide();
    });

    //투자지표 툴팁        
    // $('.globalStock .sub_search .sub_mid .search_financials_area .tableRanking .th_guide').on("click", function () {        
    //     $('.guide_box').hide();
    //     var posY = $(this).offset().top;        
    //     var thisIndex = $(this).parent().index();        
    //     $('.th_guide_hide .guide_box:eq(' + thisIndex + ')').fadeIn().css('top',posY + 15);
    // }); 
    //기업개요 툴팁    
    $('.globalStock .sub_search .sub_mid .title_guide').on("click", function () {
        $(this).next('.guide_box').show().addClass('hide');
    });
    $('.globalStock .sub_search .sub_mid .tableRanking .th_guide.txt01').on("click", function () {
        $('.guide_box').hide();
        var posY = $(this).position().top;
        var thisIndex = $(this).parent().index();
        $('.th_guide_hide.txt01 .guide_box:eq(' + thisIndex + ')').fadeIn().css('top', posY + 15);
    });
    $('.globalStock .sub_search .sub_mid .tableRanking .th_guide.txt02').on("click", function () {
        $('.guide_box').hide();
        var posY = $(this).position().top;
        var thisIndex = $(this).parent().index();
        $('.th_guide_hide.txt02 .guide_box:eq(' + thisIndex + ')').fadeIn().css('top', posY + 15);
    });
    $('.guide_layer, .guide_box').on("click", function () {
        $('.guide_layer').css({
            'z-index': -1,
        });
        $('.guide_box').hide();
    });
    $('html, body').click(function (e) {
        var etarget = $(e.target);
        if (!etarget.is('.th_guide, .th_guide span, .title_guide, .title_guide, .title_guide img, .txt_guide img, .highcharts-root, path')) {
            $('.guide_box').hide();            //툴팁숨김            
            $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0'); // 투자매력도 별점레이어 숨김
        } else {
            //
        }
    });

    // 박스 펼치기 
    $(".globalStock .sub_search .sub_mid .investCharm_area .summary").after().on("click", function () {
        // div 사이즈는 big, small        
        if ($(this).hasClass("big")) {
            $(this).children('div').animate({
                height: '80px'
            }, 300);
            $(this).removeClass('big').addClass('small');

        } else {
            $(this).children('div').animate({
                height: '100%'
            }, 300);
            $(this).removeClass('small').addClass('big');
        }
    });
    $(".globalStock .sub_search .sub_mid .investCharm_area .summary_guide").after().on("click", function () {
        // div 사이즈는 big, small        
        if ($(this).hasClass("big")) {
            $(this).children('p').animate({
                height: '75px'
            }, 300);
            $(this).removeClass('big').addClass('small');

        } else {
            $(this).children('p').animate({
                height: '100%'
            }, 300);
            $(this).removeClass('small').addClass('big');
        }
    });

    // //관심종목 수정 버튼
    // $(window).scroll(function () {        
    //     if ($('.globalStock .sub_search .att_wrap .btn_list.fix_btn').scrollTop() < 80) {              
    //         console.log("11");
    //         $('.globalStock .sub_search .att_wrap .btn_list.fix_btn').css('position','fixed')
    //     } else if ($('.globalStock .sub_search .att_wrap .btn_list.fix_btn').scrollTop() < 80) {    
    //         $('.globalStock .sub_search .att_wrap .btn_list.fix_btn').css('position','relative')
    //     }
    // }); 

    //경쟁사 투자매력도
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .containercompet1').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").css('height', '204').animate({
            'height': 204,
        }, 'fast', function () {            
            $(this).parent().children().children(".more").show();
        });
    });

    //swiper
    //메인 상단
    var swiper = new Swiper('.attentionSwiper', {
        autoHeight: true, //enable auto height
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //메인 주식에 미치다
    var swiper = new Swiper('.mijumiSwiper', {
        slidesPerView: 2,
        slidesPerGroup: 2,
        loopAdditionalSlides: 1,
        spaceBetween: 10,        
    });
    var swiper = new Swiper('.event_anaySwiper', {
        slidesPerView: 1.4,
        slidesPerGroup: 1,
        loopAdditionalSlides: 1,
        spaceBetween: 10,
        centeredSlides: true,
        loop: true,
        loopFillGroupWithBlank: true,
    });
    var swiper = new Swiper('.catchSwiper, .catchSwiper2', {
        slidesPerView: 2.2,
        spaceBetween: 0,
        freeMode: true,
    });
    //서브 종목 추천
    var swiper = new Swiper('.recomSwiper', {
        autoHeight: true, //enable auto height
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //서브 검색 - 종목진단 - 투자매력
    var swiper = new Swiper('.swiper_competChar', {
        slidesPerView: 2,
        slidesPerGroup: 1,
        loopAdditionalSlides: 1,
        spaceBetween: 0,
        centeredSlides: true,
        loop: false,
        loopFillGroupWithBlank: false,
    });
    //서브 발굴 - 미국주식 탐구생활 
    var swiper = new Swiper('.swiper-research', {
        autoHeight: true, //enable auto height
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //서비스소개
    var swiper = new Swiper('.swiper_service', {
        autoHeight: true, //enable auto height
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //서브 발굴 - 승부주 
    var swiper = new Swiper('.gameSwiper', {
        setWrapperSize: true,
        pagination: {
            el: '.swiper-pagination',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    /************************/
    /****  add active  *****/
    /************************/
    //gnb
    $('.globalStock .gnb li').on("click", function () {
        $('.globalStock .gnb li').removeClass("active");
        $(this).addClass("active");
    });
    //메인 투자레시피
    $('.globalStock .main_mid.event_recipe .recipe_list li').on("click", function () {
        $('.globalStock .main_mid.event_recipe .recipe_list li').removeClass("active");
        $(this).addClass("active");
    });
    //결제
    $('.globalStock .sub_payment .serviceStep .step_box').on("click", function () {
        $('.globalStock .sub_payment .serviceStep .step_box').removeClass("active");
        $(this).addClass("active");
    });
    $('.globalStock .sub_login .agree_area .agree_from .label').on("click", function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
    $('.globalStock .sub_payment.sub_freeguide .agree_area .agree_from .label').on("click", function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
    $('.globalStock .sub_payment .payment_note .note_chk .txt').on("click", function () {
        $('.globalStock .sub_payment .payment_note .note_chk .txt').toggleClass("active");
    });
    $('.globalStock .sub_payment .card_info span.bns_num').on("click", function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
    $('.globalStock .sub_payment .payment_chk .txt').on("click", function () {
        $('.globalStock .sub_payment .payment_chk .txt').toggleClass("active");
    });
    $('.globalStock .sub_payment .payment_note .top .agree').on("click", function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass('active');
            $('.globalStock .sub_payment .agree_chk .txt').removeClass('active');
        } else {
            $(this).addClass('active');
            $('.globalStock .sub_payment .agree_chk .txt').addClass('active');
        }
    });
    $('.globalStock .sub_payment .agree_chk .txt').on("click", function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
    // 내정보 - 메뉴 비활성
    $('.globalStock .sub_menu .sub_top .menu_list li').on("click", function () {
        if ($(this).hasClass("inactive")) {
            $(this).removeClass('inactive');
        } else {
            $(this).addClass('inactive');
        }
    });

    //결제취소
    $('.globalStock .pay_edt .form_table .label_chk').on("click", function () {
        $(this).toggleClass("active");
    });
    //tabs
    $('.tab_area .tbas_lb li a').on("click", function () {
        $('.tab_area .tbas_lb li a').removeClass("active");
        $(this).addClass("active");
    });
    //전종목 투자매력도 Popup
    $('.globalStock .setting_pop .sort li').on("click", function () {
        $('.globalStock .setting_pop .sort li').removeClass("active");
        $(this).addClass("active");
    });
    $('.globalStock .md-modal .sort li').on("click", function () {
        $('.globalStock .md-modal .sort li').removeClass("active");
        $(this).addClass("active");
    });
    $('.globalStock .sub_research .sub_mid.research_board .set span').on("click", function () {
        $('.globalStock .sub_research .sub_mid.research_board .set span').removeClass("active");
        $(this).addClass("active");
    });

    /* 개발처리
        //관심종목 찾아보기
        $('.globalStock .catch_edt #catch_cncl .catch_from .label').on("click", function () {
            if ($(this).hasClass("active")) {                        
                $(this).removeClass('active');                  
            } else {                                    
                $(this).addClass('active');              
            }        
        });
    */
    //faq
    var article = $('.faq01 .faq .article');
    article.addClass('hide');
    article.find('.a').hide();
    var article2 = $('.faq02 .faq .article');
    article2.addClass('hide');
    article2.find('.a').hide();
    var article3 = $('.faq03 .faq .article');
    article3.addClass('hide');
    article3.find('.a').hide();
    var article4 = $('.faq04 .faq .article');
    article4.addClass('hide');
    article4.find('.a').hide();
    $('.faq01 .faq').find('.article:first .a').show();
    $('.faq01 .faq').find('.article:first').addClass('show').removeClass('hide');


    $('.faq01 .faq .article .trigger').click(function () {
        var myArticle = $(this).parents('.article:first');
        if (myArticle.hasClass('hide')) {
            article.addClass('hide').removeClass('show'); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            article.find('.a').slideUp(100); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            myArticle.removeClass('hide').addClass('show');
            myArticle.find('.a').slideDown(100);
        } else {
            myArticle.removeClass('show').addClass('hide');
            myArticle.find('.a').slideUp(100);
        }
    });

    $('.faq02 .faq .article .trigger').click(function () {
        var myArticle = $(this).parents('.article:first');
        if (myArticle.hasClass('hide')) {
            article2.addClass('hide').removeClass('show'); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            article2.find('.a').slideUp(100); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            myArticle.removeClass('hide').addClass('show');
            myArticle.find('.a').slideDown(100);
        } else {
            myArticle.removeClass('show').addClass('hide');
            myArticle.find('.a').slideUp(100);
        }
    });

    $('.faq03 .faq .article .trigger').click(function () {
        var myArticle = $(this).parents('.article:first');
        if (myArticle.hasClass('hide')) {
            article3.addClass('hide').removeClass('show'); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            article3.find('.a').slideUp(100); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            myArticle.removeClass('hide').addClass('show');
            myArticle.find('.a').slideDown(100);
        } else {
            myArticle.removeClass('show').addClass('hide');
            myArticle.find('.a').slideUp(100);
        }
    });

    $('.faq04 .faq .article .trigger').click(function () {
        var myArticle = $(this).parents('.article:first');
        if (myArticle.hasClass('hide')) {
            article4.addClass('hide').removeClass('show'); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            article4.find('.a').slideUp(100); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
            myArticle.removeClass('hide').addClass('show');
            myArticle.find('.a').slideDown(100);
        } else {
            myArticle.removeClass('show').addClass('hide');
            myArticle.find('.a').slideUp(100);
        }
    });

    $('.attract_sub .btn_schSet').on("click", function () {        
        $('html, body').css("overflow", "hidden");        
    });
    $('.pop_header .close, .md-overlay').on("click", function () {        
        $('.globalStock .md-modal').removeClass('md-show');
        $('html, body').css("overflow", "scroll");
    });

    // 원스톱 종목진단 div 높이 조절    
    var heightArray = $(".globalStock .sub_search .latest_results .tabsArea .onestep_chart .dgtic_results > div").map(function () {
        return $(this).height();
    }).get();
    var maxHeight = Math.max.apply(Math, heightArray);
    $(".globalStock .sub_search .latest_results .tabsArea .onestep_chart .dgtic_results > div").height(maxHeight);

    // 스크롤시 tab_scr bg 숨김
    var windowWidth = $('html, body').width();
    $('.globalStock .main_mid.event_recipe .tabsArea .tab_scr').css('width',windowWidth);
    $('.globalStock .main_mid.event_recipe .tabsArea > span').addClass('change');        

    $('.tabsArea .tab_scr').on("touchmove", function (e) {
        $('.globalStock .main_mid.event_recipe .tabsArea > span').hide();
    });
    $('.tabsArea .tab_scr').on("touchend", function (e) {
        $('.globalStock .main_mid.event_recipe .tabsArea > span').show();
    });

    (function (window) {

        'use strict';

        // class helper functions from bonzo https://github.com/ded/bonzo

        function classReg(className) {
            return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
        }

        // classList support for class management
        // altho to be fair, the api sucks because it won't accept multiple classes at once
        var hasClass, addClass, removeClass;

        if ('classList' in document.documentElement) {
            hasClass = function (elem, c) {
                return elem.classList.contains(c);
            };
            addClass = function (elem, c) {
                elem.classList.add(c);
            };
            removeClass = function (elem, c) {
                elem.classList.remove(c);
            };
        }
        else {
            hasClass = function (elem, c) {
                return classReg(c).test(elem.className);
            };
            addClass = function (elem, c) {
                if (!hasClass(elem, c)) {
                    elem.className = elem.className + ' ' + c;
                }
            };
            removeClass = function (elem, c) {
                elem.className = elem.className.replace(classReg(c), ' ');
            };
        }

        function toggleClass(elem, c) {
            var fn = hasClass(elem, c) ? removeClass : addClass;
            fn(elem, c);
        }

        var classie = {
            // full names
            hasClass: hasClass,
            addClass: addClass,
            removeClass: removeClass,
            toggleClass: toggleClass,
            // short names
            has: hasClass,
            add: addClass,
            remove: removeClass,
            toggle: toggleClass
        };

        // transport
        if (typeof define === 'function' && define.amd) {
            // AMD
            define(classie);
        } else {
            // browser global
            window.classie = classie;
        }

    })(window);

	var ModalEffects = (function () {
        function init() {
            var overlay = document.querySelector('.md-overlay');
            [].slice.call(document.querySelectorAll('.md-trigger')).forEach(function (el, i) {
                var modal = document.querySelector('#' + el.getAttribute('data-modal'));
                el.addEventListener('click', function (ev) {
                    classie.add(modal, 'md-show');                    
                });
            });
        }
        init();
    })();

	if ($('#modal-3').length) {
        $('.globalStock .sub_research .sub_mid.research_board .lst_type, .globalStock .sub_mid .tab_container .tab_content, .globalStock .sub_briefing .lst_type').on('click', '.md-trigger', function() {        
            $('.globalStock .first_month_3300#modal-3').addClass('md-show');                        
        });
    };
	
	// 키움초이스스탁 스크립트
    // if ($('.schfix_inc').length) {
    //     // 상단 검색 GNB 고정
    //     var jbOffset = $('.globalStock.kiwoom .schfix_inc .searching').offset();
    //     $(window).scroll(function () {
    //         if ($(document).scrollTop() > jbOffset.top) {        
    //             $('.globalStock.kiwoom .schfix_inc').addClass('fix_sch');
    //         }
    //         else {
    //             $('.globalStock.kiwoom .schfix_inc').removeClass('fix_sch');
    //         }
    //     });
    // }
    //검색    
    $('.globalStock .schfix_inc .searching .searchArea .searchInput').on("keydown", function () {                
        $(this).addClass('keydown');
        // $('.globalStock .schfix_inc .searching .searchArea .searchBtn').css({'top': 15,'right': 0});
        $('.globalStock .schfix_inc .searching .searchArea .sch_title').addClass('keydown');
        $('.globalStock .schfix_inc .searching .sch_autocomplete').addClass('focus_on').show();        
        $('.globalStock.kiwoom .schfix_inc .top_btn').hide();    
        $('.globalStock.kiwoom .schfix_inc .searching .searchArea').css('width','calc(100% - 30px)');            
    });
    $('.globalStock .schfix_inc .searching .searchArea .searchInput').on("focusout", function () {                
        $('.globalStock .schfix_inc .searching .searchArea .sch_title').removeClass('keydown');
        $(this).removeClass('keydown');
        // $('.globalStock .schfix_inc .searching .searchArea .searchBtn').css('top', '9px');
        $('.globalStock .schfix_inc .searching .sch_autocomplete').hide();                
        $('.globalStock.kiwoom .schfix_inc .top_btn').show();        
        $('.globalStock.kiwoom .schfix_inc .searching .searchArea').css('width','calc(100% - 70px)');        
    });

    //검색 미주미ver
    $('.globalStock .schfix_inc .searching.searching_mijumi .searchArea .searchInput').on("keydown", function () {                
        $(this).addClass('keydown');
        $('.globalStock .schfix_inc .searching.searching_mijumi .searchArea .searchBtn').css({
            'top': 15,
            'right': 0,
        });
        $('.globalStock .schfix_inc .searching.searching_mijumi .searchArea .sch_title').addClass('keydown');
        $('.globalStock .schfix_inc .searching.searching_mijumi .sch_autocomplete').addClass('focus_on').show();        
        $('.globalStock.kiwoom .schfix_inc .top_btn').hide();    
        $('.globalStock.kiwoom .schfix_inc .searching.searching_mijumi .searchArea').css('width','calc(100% - 30px)');        
    });
    $('.globalStock .schfix_inc .searching.searching_mijumi .searchArea .searchInput').on("focusout", function () {                        
        $('.globalStock .schfix_inc .searching.searching_mijumi .searchArea .sch_title').removeClass('keydown');
        $(this).removeClass('keydown');
        $('.globalStock .schfix_inc .searching.searching_mijumi .searchArea .searchBtn').css({
            'top': 11,
            'right': 30,
        });
        $('.globalStock .schfix_inc .searching.searching_mijumi .sch_autocomplete').hide();                
        $('.globalStock.kiwoom .schfix_inc .top_btn').show();        
        $('.globalStock.kiwoom .schfix_inc .searching.searching_mijumi .searchArea').css('width','calc(100%)');   
    });
    
    $('.globalStock #header .headerTop .hm .go_sch').on("click", function () {
        $('.globalStock.kiwoom .schfix_inc .searching.searching_mijumi').slideToggle(200);
    });
    $('.globalStock.kiwoom .schfix_inc .searching.searching_mijumi .searchArea .searchBtn').on("click", function () {
        $('.globalStock.kiwoom .schfix_inc .searching.searching_mijumi').slideToggle(200);
    });


    $('ul li:has(ul)').addClass('has-submenu');
    $('ul li ul').addClass('sub-menu');
    $('ul.dropdown li').hover(function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
    });
    var $menu = $('#menu'), $menulink = $('#spinner-form'), $search = $('#search'), $search_box = $('.search_box'), $menuTrigger = $('.has-submenu > a');
    $menulink.click(function (e) {
        $menulink.toggleClass('active');
        $menu.toggleClass('active');
        if ($search.hasClass('active')) {
            $('.menu.active').css('padding-top', '50px');
        }
    });
    $search.click(function (e) {
        e.preventDefault();
        $search_box.toggleClass('active');
    });
    $menuTrigger.click(function (e) {
        e.preventDefault();
        var t = $(this);
        t.toggleClass('active').next('ul').toggleClass('active');
    });
    $('ul li:has(ul)');
    $(function () {
        var e = $(document).scrollTop();
        var t = $('.nav_wrapper').outerHeight();
        $(window).scroll(function () {
            var n = $(document).scrollTop();
            if ($(document).scrollTop() >= 50) {
                $('.nav_wrapper').css('position', 'fixed');
            } else {
                $('.nav_wrapper').css('position', 'fixed');
            }
            if (n > t) {
                $('.nav_wrapper').addClass('scroll');
            } else {
                $('.nav_wrapper').removeClass('scroll');
            }
            if (n > e) {
                $('.nav_wrapper').removeClass('no-scroll');
            } else {
                $('.nav_wrapper').addClass('no-scroll');
            }
            e = $(document).scrollTop();
        });
    });

});