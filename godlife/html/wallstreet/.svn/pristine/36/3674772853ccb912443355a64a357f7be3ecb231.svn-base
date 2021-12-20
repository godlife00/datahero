$(document).ready(function () {
    /************************/
    /****  Modal popup  *****/
    /************************/
    //로그인 팝업
    var setWindow = $('.setting_pop');
    // Show Hide
    $('.clse_trigger').click(function () {
        setWindow.addClass('open');
    });
    $('#setting .close').click(function () {
        setWindow.removeClass('open');
    });
    // Hide Window
    setWindow.find('>.bg').mousedown(function (event) {
        setWindow.removeClass('open');
        return false;
    });
    //TOP
    $(window).scroll(function () {
        if ($(this).scrollTop() > 800) {
            $('.goTop').fadeIn();
        } else {
            $('.goTop').fadeOut();
        }
    });
    //햄버거 메뉴
    var burger = $('.menu-trigger');
    burger.each(function (index) {
        var $this = $(this);

        $this.on('click', function (e) {
            e.preventDefault();
            // $(this).toggleClass('active-' + (index+1));
            $(this).toggleClass('active-1');
        })
    });
    $(window).load(function () {
        /* 메뉴버튼을 눌렀을때, 오버레이부분을 클릭했을때*/
        $(".ninja-btn, .panel-overlay").click(function () {

            $(".ninja-btn, .panel-overlay, .panel").toggleClass("active"); //해당 영역에 toggleClass를 넣어줍니다
            /* panel overlay가 활성화 되어있는지를 체크합니다. */
            if ($(".panel-overlay").hasClass("active")) {
                $(".panel-overlay").fadeIn();
                $("html, body").css({ "overflow": "hidden", "height": "100%" });
            } else {
                $('.menu-trigger').removeClass('active-1');
                $(".panel-overlay").fadeOut();
                $("html, body").css({ "overflow": "auto", "height": "auto" });
            }
        });
        $('.menuClse').click(function () {
            $(".ninja-btn, .panel-overlay, .panel").toggleClass("active, active-1"); //해당 영역에 toggleClass를 넣어줍니다                    
            $(".panel-overlay").fadeOut();
        });
    });
    function animated_contents() {
        $(".zt-skill-bar > div ").each(function (i) {
            var $this = $(this),
                skills = $this.data('width');

            $this.css({ 'width': skills + '%' });

        });
    }
    if (jQuery().appear) {
        $('.zt-skill-bar').appear().on('appear', function () {
            animated_contents();
        });
    } else {
        animated_contents();
    }
    /*  Skill bar style */
    (function ($) {
        "use strict";
        $(function () {
            function animated_contents() {
                $(".zt-skill-bar > div ").each(function (i) {
                    var $this = $(this),
                        skills = $this.data('width');

                    $this.css({ 'width': skills + '%' });

                });
            }

            if (jQuery().appear) {
                $('.zt-skill-bar').appear().on('appear', function () {
                    animated_contents();
                });
            } else {
                animated_contents();
            }
        });
    }(jQuery));
    
    //푸터 하단 고정 search_top searching
    if ($('.search_top.searching').length) {    
        var winHeight = $(window).height();
        var footerHeight = $('#footer').height();    
        var bodyHeight = $('html, body').height() - footerHeight;    

        if(winHeight >= bodyHeight ) {            
            $('#footer').addClass('fix_footer');
        }
    }        
    if ($('.nondata').length) {    
        var winHeight = $(window).height();
        var footerHeight = $('#footer').height();    
        var bodyHeight = $('html, body').height() - footerHeight;    

        if(winHeight >= bodyHeight ) {            
            $('#footer').addClass('fix_footer');
        }
    }

    //검색
    if ($('.sub_search').length) {     
                $('#footer').removeClass('fix_footer');           
        $('.searchInput').focus().click();                
    }
    $('.globalStock .sub_search .searching .searchArea .searchInput').on("click", function () {      
        $('#footer').removeClass('fix_footer');        
    });
    $('.globalStock .sub_search .searching .searchArea .searchInput').on("keydown", function () {      
        $('#footer').removeClass('fix_footer');
        $(this).addClass('keydown');
        $('.globalStock .sub_search .searching .sch_autocomplete').addClass('focus_on').show();
    });
    $('.globalStock .sub_search .searching .searchArea .searchInput').on("focusout", function () {               
        $(this).removeClass('keydown');
        $('.globalStock .sub_search .searching .sch_autocomplete').hide();
    });    

    //알람
    if ($('.alarmArea').length) {
        $(".globalStock #header .headerTop .hm .alarm").on("click", function () {
            $('.globalStock .alarmArea').addClass('schFocus');
            $('#container').css('overflow', 'hidden').height(0);
        });
        $(".globalStock .layer_header .history_back").on("click", function () {
            $('.globalStock .alarmArea').removeClass('schFocus');
            $('#container').css('overflow', 'hidden').height('auto');
        });
    }
    $('.panel .btm li:first-child').on("click", function () {        
        $('.globalStock .alarmArea').addClass('schFocus');
        $('#container').css('overflow', 'hidden').height(0);
        $(".ninja-btn, .panel-overlay, .panel").removeClass("active");
        $(".btn_hamburger ").removeClass("active-1");
        $(".panel-overlay").fadeOut();
    });

    //탭메뉴
    $(".tabsArea .tab_content").hide();
    $(".tabsArea .tab_content:first").show();
    $(".tabsArea_2 .tab_content").hide();
    $(".tabsArea_2 .tab_content:first").show();
    $(".tabsArea_main .tab_content").hide();   
    $(".tabsArea_main .tab_content.tab_view").show(); 
    
    $("ul.tabs li").click(function () {
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tabsArea .tab_content").hide();
        $(".tabsArea_main .tab_content").hide();
        $(".tabsArea_main .tab_content").removeClass('tab_view');
        var activeTab = $(this).attr("rel");
        $("#" + activeTab).fadeIn();
    });
    $("ul.tabs_2 li").click(function () {
        $("ul.tabs_2 li").removeClass("active");
        $(this).addClass("active");
        $(".tabsArea_2 .tab_content").hide()
        var activeTab = $(this).attr("rel");
        $("#" + activeTab).fadeIn();
    });       
    $('.globalStock .sub_search .sub_mid .financecachart_area .tabs_menu span').on('click', function(){                                
        $(this).parent('div').children('span').removeClass("active");        
        $(this).addClass('active');                
    });   
    $('.globalStock .sub_search .sub_mid .search_financials_area .tabs_menu span').on('click', function(){                                
        $(this).parent('div').children('span').removeClass("active");        
        $(this).addClass('active');                
    });   
    $('.globalStock .sub_research .sub_mid.research_board .set span').on('click', function(){                                
        $(this).parent('div').children('span').removeClass("active");        
        $(this).addClass('active');                
    });
    $('.globalStock .sub_search .sub_mid.financials_chart .tabs_menu span').on('click', function(){                                
        $(this).parent('div').children('span').removeClass("active");        
        $(this).addClass('active');                
    });   
    
    
    
    // 종목검색 - 재무제표 매수,매도 의견 반영    
    $('.globalStock .sub_search .user_select.next').hide();
    $(".globalStock .sub_search .user_select.prev .detail li.deal span").on("click", function () {
        $('.globalStock .sub_search .user_select.prev').hide();
        $('.globalStock .sub_search .user_select.next').show();
    });

    $('.globalStock .sub_search .user_select.next .select_ch').on("click", function () {
        $('.globalStock .sub_search .user_select.prev').show();
        $('.globalStock .sub_search .user_select.next').hide();
    });

    var txtPsize = $('.globalStock #container .summary p').height();
    $(".globalStock .schChartMid .summary .close").on("click", function () {
        if ($(this).hasClass("show")) {
            $('.globalStock .schChartMid .summary .etc').slideUp(300);
            $('.globalStock .schChartMid .summary p').animate({
                width: "80%",
                height: '20px'
            }, 100).addClass('small');
            $(this).removeClass('show');

        } else {
            $('.globalStock .schChartMid .summary .etc').slideDown(300);
            $('.globalStock .schChartMid .summary p').animate({
                width: "100%",
                height: txtPsize
            }, 100).removeClass('small');
            $(this).addClass('show');
        }

        if ($(".globalStock .schChartMid .summary").hasClass("small")) {
            $(".globalStock .schChartMid .summary").removeClass('small');
        } else {
            $(".globalStock .schChartMid .summary").addClass('small');
        }
    });

    //swiper
    //메인 상단
    var swiper = new Swiper('.mainThemeSwiper', {
        autoHeight: true, //enable auto height
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //메인 주요지표
    var swiper = new Swiper('.swiper_indicators', {
        slidesPerView: 4,
        spaceBetween: 0,
        // freeMode: true,
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
    });
    //메인 차트슬라이드
    var swiper = new Swiper('.swiper-slideCharM', {
        slidesPerView: 1.1,
        slidesPerGroup: 1,
        loopAdditionalSlides: 1,
        spaceBetween: 10,
        centeredSlides: true,
        loop: false,
        loopFillGroupWithBlank: false,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //서브탑 차트슬라이드
    var swiper = new Swiper('.swiper-slideChar', {
        slidesPerView: 1,
        slidesPerGroup: 1,
        loopAdditionalSlides: 1,
        spaceBetween: 10,
        centeredSlides: true,
        loop: false,
        loopFillGroupWithBlank: false,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //대가의 종목
    var swiper = new Swiper('.swiper-master', {
        slidesPerView: 1.1,
        slidesPerGroup: 1,
        loopAdditionalSlides: 1,
        spaceBetween: 10,
        centeredSlides: true,
        loop: false,
        loopFillGroupWithBlank: false,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //전종목 투자매력도
    var swiper = new Swiper('.swiper-charm', {
        slidesPerView: 1.3,
        slidesPerGroup: 1,
        loopAdditionalSlides: 1,
        spaceBetween: 10,
        centeredSlides: true,
        loop: false,
        loopFillGroupWithBlank: false,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //서브 - 미국주식 탐구생활
    var swiper = new Swiper('.swiper-research', {
        slidesPerView: 1,
        slidesPerGroup: 1,
        loopAdditionalSlides: 1,
        spaceBetween: 10,
        centeredSlides: true,
        loop: true,
        loopFillGroupWithBlank: true,
        pagination: {
            el: '.swiper-pagination',
        },
    });
    //서브 - 종목검색 - 종목진단 - 투자매력도
    var swiper = new Swiper('.swiper-competChar', {
        slidesPerView: 2.2,
        slidesPerGroup: 1,        
        spaceBetween: 0,
        loop: false,
        loopFillGroupWithBlank: false
    });
    //서브 - 종목검색 로딩 텍스트 문구
    var swiper = new Swiper('.swiper_loding', {
        direction: 'vertical',
        slidesPerView: 3,
        spaceBetween: 0,
        centeredSlides: true,
        loop: true,
        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
        },
    });
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart0').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").css('height', '160').animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart1').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart2').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });    
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart3').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });  
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart4').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });  
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart5').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });  
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart6').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });  
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart7').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });  
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart8').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });  
    $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart #charm_comp_spider_chart9').on("click", function () {
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0');
        $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star .more ').hide();
        $(this).parent().children(".chart_star").show();
        $(this).parent().children(".chart_star").animate({
            'height': 160,
        }, 'fast', function () {
            console.log(this);
            $(this).parent().children().children(".more").show();
        });
    });  

    //종목분석 - 개별종목
    $('.globalStock .sub_analysis .sub_top.view .chart_area .topbtm_area .box.opinion_right .txt_guide').on("click", function () {        
        $('.guide_layer').css({'z-index' : 9997});
        $('.globalStock .sub_analysis .sub_top.view .chart_area .topbtm_area .box.opinion_right .guide_box').show();   
    });
    //전종목 투자매력도
    $('.globalStock .sub_attract .sub_mid.attract_sub .attract_table .txt_guide').on("click", function () {        
        $('.guide_layer').css({'z-index' : 9997});
        $('.globalStock .sub_attract .sub_mid.attract_sub .guide_box').show();   
    });    
    //종목분석
    $('.globalStock .sub_search .sub_mid .investCharm_area .chart_sum .txt_guide').on("click", function () {        
        $('.guide_layer').css({'z-index' : 9997});
        $('.globalStock .sub_search .sub_mid .investCharm_area .chart_sum .guide_box').show();     
    });
    $('.globalStock .sub_search .sub_mid .sum_guide_box .txt_guide').on("click", function () {                
        $('.guide_layer').css({'z-index' : 9997});
        $('.globalStock .sub_search .sub_mid .sum_guide_box .guide_box').show();     
    });
    //기업개요
    $('.globalStock .sub_search .sub_mid.competitors_table .txt_guide').on("click", function () {                
        $('.guide_layer').css({'z-index' : 9997});
        $('.globalStock .sub_search .sub_mid.competitors_table .guide_box').show();     
    });
    $('.globalStock .sub_search .sub_mid.competitors_table .title_guide').on("click", function () {        
        $('.guide_layer').css({'z-index' : 9997});
        $('.globalStock .sub_search .sub_mid.competitors_table .guide_box').show();     
    });
    
    //종목검색 상단
    $('.globalStock .sub_search .sub_mid.tabs_area .chart_area.diagnosis .chartData .charm .txt_guide').on("click", function () {        
        $('.guide_layer').css({'z-index' : 9997});
        $('.globalStock .sub_search .sub_mid.tabs_area .chart_area.diagnosis .chartData .charm .guide_box').show();         
    });    
    //clse
    $('.globalStock .sub_attract .sub_mid.attract_sub .guide_box .clse').on("click", function () {        
        $('.globalStock .sub_attract .sub_mid.attract_sub .guide_box').addClass('show');   
    });
    $('.globalStock .sub_search .sub_mid .investCharm_area .chart_sum .guide_box .clse').on("click", function () {        
        $('.globalStock .sub_search .sub_mid .investCharm_area .guide_box').addClass('show');    
    });
    $('.globalStock .sub_analysis .sub_top.view .chart_area .topbtm_area .box.opinion_right .guide_box .clse').on("click", function () {        
        $('.globalStock .sub_analysis .sub_top.view .chart_area .topbtm_area .box.opinion_right .guide_box').addClass('show');   
    });
    $('.globalStock .sub_search .sub_mid.competitors_table .guide_box').on("click", function () {        
        $('.globalStock .sub_search .sub_mid.competitors_table .guide_box').addClass('show');      
    });

    //대가의종목 카드 높이값    
    function equalHeights( $objs ) {
        var highest = 0;
        $objs.each(function() {
            thisHeight = $(this).height();
            if(thisHeight > highest ) {
                highest = thisHeight;
            }
        });
        $objs.height( highest );
    }
    $(function(){
        setTimeout(function(){
            equalHeights( $(".globalStock .master_area .master_slide") );
        }, 0);
    });
    
    // fix_table 테이블 상단 고정    
    if ($('.fix_table').length) {
        var fix_scrTop = $( '.fix_table' ).offset();
        $(html).scroll( function() {
            if ( $('html, body').scrollTop() > fix_scrTop.top ) {
                $( '.fix_table' ).addClass( 'jbFixed' );
                $( '.fix_table' ).animate({                    
                    top : '0'
                });
            }   
            else {
                $( '.fix_table' ).removeClass( 'jbFixed' );
            }                        
        });         
    }

    // 박스 펼치기 
    $(".globalStock .sub_search .sub_mid .investCharm_area .summary").after().on("click", function(){                
        // div 사이즈는 big, small        
        if ($(this).hasClass("big")) {                        
            $(this).children('div').animate({                
                height : '80px'
            },300);
            $(this).removeClass('big').addClass('small');                      
            
        } else {                                    
            $(this).children('div').animate({                    
                height : '100%'
            },300); 
            $(this).removeClass('small').addClass('big');                       
        }        
    });
    $(".globalStock .sub_search .sub_mid .investCharm_area .summary_guide").after().on("click", function(){                
        // div 사이즈는 big, small        
        if ($(this).hasClass("big")) {                        
            $(this).children('p').animate({                
                height : '75px'
            },300);
            $(this).removeClass('big').addClass('small');                      
            
        } else {                                    
            $(this).children('p').animate({                      
                height : '100%'
            },300); 
            $(this).removeClass('small').addClass('big');                       
        }        
    });

    //투자지표 툴팁        
    $('.globalStock .sub_search .sub_mid .search_financials_area .tableRanking .th_guide').on("click", function () {        
        $('.guide_box').hide();
        var posY = $(this).offset().top;        
        var thisIndex = $(this).parent().index();        
        $('.th_guide_hide .guide_box:eq(' + thisIndex + ')').fadeIn().css('top',posY + 26);
    }); 

    //기업개요 툴팁    
    $('.globalStock .sub_search .sub_mid .title_guide').on("click", function () {              
        $(this).next('.guide_box').show().addClass('hide');
    });  
    $('.globalStock .sub_search .sub_mid .tableRanking .th_guide.txt01').on("click", function () {                                        
        $('.guide_box').hide();
        var posY = $(this).position().top;        
        var thisIndex = $(this).parent().index();                        
        $('.th_guide_hide.txt01 .guide_box:eq(' + thisIndex + ')').fadeIn().css('top',posY + 26);
    });   
    $('.globalStock .sub_search .sub_mid .tableRanking .th_guide.txt02').on("click", function () {                        
        $('.guide_box').hide();
        var posY = $(this).position().top;        
        var thisIndex = $(this).parent().index();   
        $('.th_guide_hide.txt02 .guide_box:eq(' + thisIndex + ')').fadeIn().css('top',posY + 26);
    });      

    $('.guide_layer, .guide_box').on("click", function () {        
        $('.guide_layer').css({
            'z-index' : -1,
        });
        $('.guide_box').hide();         
    });

    $('html, body').click(function(e){   
        var etarget = $(e.target);
        if (!etarget.is('.th_guide, .th_guide span, .title_guide, .title_guide, .title_guide img, .txt_guide img, .highcharts-root, path')) {                        
            $('.guide_box').hide();            //툴팁숨김            
            $('.globalStock .sub_search .sub_mid.tabs_area .compet_chart .chart_star').hide().css('height', '0'); // 투자매력도 별점레이어 숨김
        } else {
            //
        }
    });

    //로딩바
    // $('#page_loading').show();    
});

//로딩바
// $(window).load(function(){              
//     setTimeout(function() {
//         $('#page_loading').hide();
//     }, 3000);    
//  }); 