$(document).ready(function () {

    $("ul.tabs li").click(function () {
        $(this).parent().children("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(this).parent().parent().children().children(".tabs_area .tab_content").hide();        
        $(".tabs_area .tab_content").removeClass('tab_view');
        var activeTab = $(this).attr("rel");
        $("#" + activeTab).fadeIn();
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
        $('.guide_box').addClass('hide');
        $('.globalStock .sub_analysis .sub_top.view .chart_area .topbtm_area .box.opinion_right .guide_box').removeClass('hide').addClass('show');
    });
    //전종목 투자매력도
    $('.globalStock .sub_attract .sub_mid.attract_sub .attract_table .txt_guide').on("click", function () {
        $('.guide_box').addClass('hide');
        $('.globalStock .sub_attract .sub_mid.attract_sub .guide_box').removeClass('hide').addClass('show');
    });
    //종목분석
    $('.globalStock .sub_search .sub_mid .investCharm_area .chart_sum .txt_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
        $('.globalStock .sub_search .sub_mid .investCharm_area .chart_sum .guide_box').show();
    });
    $('.globalStock .sub_search .sub_mid .sum_guide_box .txt_guide').on("click", function () {
        $('.guide_box').addClass('hide');
        $('.globalStock .sub_search .sub_mid .sum_guide_box .guide_box').removeClass('hide').addClass('show');
    });
    //기업개요
    $('.globalStock .sub_search .sub_mid.competitors_table .txt_guide').on("click", function () {
        $(this).next('.guide_box').show().addClass('hide');
        $('.globalStock .sub_search .sub_mid.competitors_table .guide_box').removeClass('hide').addClass('show');
    });
    $('.globalStock .sub_search .sub_mid.competitors_table .title_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
        $('.globalStock .sub_search .sub_mid.competitors_table .guide_box').show();
    });

    //종목검색 상단
    $('.globalStock .sub_search .sub_mid.tabs_area .chart_area.diagnosis .chartData .charm .txt_guide').on("click", function () {
        $('.guide_layer').css({ 'z-index': 9997 });
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
    function equalHeights($objs) {
        var highest = 0;
        $objs.each(function () {
            thisHeight = $(this).height();
            if (thisHeight > highest) {
                highest = thisHeight;
            }
        });
        $objs.height(highest);
    }
    $(function () {
        setTimeout(function () {
            equalHeights($(".globalStock .master_area .master_slide"));
        }, 0);
    });

    // fix_table 테이블 상단 고정    
    if ($('.fix_table').length) {
        var fix_scrTop = $('.fix_table').offset();
        $(html).scroll(function () {
            if ($('html, body').scrollTop() > fix_scrTop.top) {
                $('.fix_table').addClass('jbFixed');
                $('.fix_table').animate({
                    top: '0'
                });
            }
            else {
                $('.fix_table').removeClass('jbFixed');
            }
        });
    }

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

    //투자지표 툴팁        
    $('.globalStock .sub_search .sub_mid .search_financials_area .tableRanking .th_guide').on("click", function () {
        $('.guide_box').hide();
        var posY = $(this).offset().top;
        var thisIndex = $(this).parent().index();
        $('.th_guide_hide .guide_box:eq(' + thisIndex + ')').fadeIn().css('top', posY + 26);
    });

    //기업개요 툴팁    
    $('.globalStock .sub_search .sub_mid .title_guide').on("click", function () {
        $(this).next('.guide_box').show().addClass('hide');

    });
    $('.globalStock .sub_search .sub_mid .tableRanking .th_guide.txt01').on("click", function () {
        $('.guide_box').show().addClass('hide');
        $(this).children().removeClass('hide').addClass('show');
        $('.guide_box').hide();

        var posY = $(this).offset().top;
        var thisIndex = $(this).parent().index();
        $('.th_guide_hide.txt01 .guide_box:eq(' + thisIndex + ')').fadeIn().css('top', posY + 26);
    });
    $('.globalStock .sub_search .sub_mid .tableRanking .th_guide.txt02').on("click", function () {
        $('.guide_box').show().addClass('hide');
        $(this).children().removeClass('hide').addClass('show');
        $('.guide_box').hide();

        var posY = $(this).offset().top;
        var thisIndex = $(this).parent().index();
        $('.th_guide_hide.txt02 .guide_box:eq(' + thisIndex + ')').fadeIn().css('top', posY + 26);
    });

    $('.guide_layer').on("click", function () {
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

    // sctop_01 네비게이션 페이지 이동    
    $('.sctop_00').on("click", function () {
        console.log();
        $('html').animate({
            scrollTop: 0
        }, 400);
    });
    $('.sctop_01').on("click", function () {
        var posY = $('.offset_01').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_02').on("click", function () {
        var posY = $('.offset_02').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_03').on("click", function () {
        var posY = $('.offset_03').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_03_01').on("click", function () {
        var posY = $('.offset_03_01').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_04').on("click", function () {
        var posY = $('.offset_04').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_04_01').on("click", function () {
        var posY = $('.offset_04_01').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_05').on("click", function () {
        var posY = $('.offset_05').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_05_01').on("click", function () {
        var posY = $('.offset_05_01').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_06').on("click", function () {
        var posY = $('.offset_06').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_06_01').on("click", function () {
        var posY = $('.offset_06_01').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_07').on("click", function () {
        var posY = $('.offset_07').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_08').on("click", function () {
        var posY = $('.offset_08').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_08_01').on("click", function () {
        var posY = $('.offset_08_01').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_09').on("click", function () {
        var posY = $('.offset_09').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_09_01').on("click", function () {
        var posY = $('.offset_09_01').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_10').on("click", function () {
        var posY = $('.offset_10').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_11').on("click", function () {
        var posY = $('.offset_11').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_12').on("click", function () {
        var posY = $('.offset_12').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_13').on("click", function () {
        var posY = $('.offset_13').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_14').on("click", function () {
        var posY = $('.offset_14').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_15').on("click", function () {
        var posY = $('.offset_15').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_16').on("click", function () {
        var posY = $('.offset_16').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_17').on("click", function () {
        var posY = $('.offset_17').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_18').on("click", function () {
        var posY = $('.offset_18').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_19').on("click", function () {
        var posY = $('.offset_19').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_20').on("click", function () {
        var posY = $('.offset_20').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_21').on("click", function () {
        var posY = $('.offset_21').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_22').on("click", function () {
        var posY = $('.offset_22').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_23').on("click", function () {
        var posY = $('.offset_23').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_24').on("click", function () {
        var posY = $('.offset_24').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_25').on("click", function () {
        var posY = $('.offset_25').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_26').on("click", function () {
        var posY = $('.offset_26').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_27').on("click", function () {
        var posY = $('.offset_27').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_28').on("click", function () {
        var posY = $('.offset_28').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_29').on("click", function () {
        var posY = $('.offset_29').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_30').on("click", function () {
        var posY = $('.offset_30').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_31').on("click", function () {
        var posY = $('.offset_31').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_32').on("click", function () {
        var posY = $('.offset_32').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_33').on("click", function () {
        var posY = $('.offset_33').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_34').on("click", function () {
        var posY = $('.offset_34').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_35').on("click", function () {
        var posY = $('.offset_35').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_36').on("click", function () {
        var posY = $('.offset_36').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_37').on("click", function () {
        var posY = $('.offset_37').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_38').on("click", function () {
        var posY = $('.offset_38').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_39').on("click", function () {
        var posY = $('.offset_39').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });
    $('.sctop_40').on("click", function () {
        var posY = $('.offset_40').offset().top;
        console.log();
        $('html').animate({
            scrollTop: posY - 25
        }, 400);
    });

    /* 공통 ui */
    $(".globalStock #nav .gnb li a").on("click", function () {
        $(this).addClass('active active_fix');
    });
    $(".globalStock #nav .gnb li a").on("mouseleave", function () {
        $(this).addClass('active active_fix');
    });
    $(".globalStock #nav .gnb li a").on("mouseover", function () {
        $(".globalStock #nav .gnb li a").removeClass("active");
    });



});

function moveToSearch(div) {
    //var div = $('.globalStock #header .searchArea .AutoComplete');
    if($('li a._on', div).length > 0) {
        location.href= ($('li a._on:first', div).prop('href'));
    } else {
        if($('li.show', div).length > 0) {
            location.href= ($('li.show:first a', div).prop('href'));
        }
    }
}


// 검색어 자동완성    
$(function(){
        var top_search_action = false;
        var lis = [];
        $(".globalStock #header .searchArea .searchInput").on("keyup", function(e){
                var key = e.keyCode;
                switch(key) {
                    case 13:
                        e.preventDefault();
                        moveToSearch($('.globalStock #header .searchArea .AutoComplete'));
                        break;

                    case 9 :
                    case 16:
                    case 37:
                    case 38:
                    case 39:
                    case 40:
                    case 27:
                        break;

                    default :
                        var search_input = this;
                        if(top_search_action) { clearTimeout(top_search_action); }
                        top_search_action = setTimeout(function(){
                            doSearch(search_input);
                        }, 400);
                }
        });

        function doSearch(obj) {

            var div = $('.globalStock #header .searchArea .AutoComplete');

			for(var i in lis) {
                $('a._on', lis[i]).removeClass('_on');
            }
            lis = [];
            div.removeClass('_show');        
            if(obj.value.length <= 0) {
                return;
            }

            var search = obj.value.toUpperCase();
            var ul = $('ul:first', div);
            ul.html('');
            var match_li = null;
            var find_lis = [];
            for(var tic in search_ticker_list) {
                var ticker = tic.toUpperCase();
                if(ticker == search) {
                    // ticker 일치
					if ($('#pageIdxChart').length) {
			             match_li = $('<li><a href="/cms/main/chart/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>');
					}
					else { 
						match_li = $('<li><a href="/cms/main/index/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>');
					}
                    continue;
                }
                if((ticker+search_ticker_list[tic].name).toUpperCase().indexOf(search) >= 0) {
					if ($('#pageIdxChart').length) {
	                    find_lis.push($('<li><a href="/cms/main/chart/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>'));
					}
					else {
	                    find_lis.push($('<li><a href="/cms/main/index/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>'));
					}
                }
            }
            if(match_li !== null) {
                ul.append(match_li);
            }
            if(find_lis.length > 0) {
                ul.append(find_lis);
            }
            if(find_lis.length > 0 || match_li !== null) {
                $('li:first a', ul).addClass('_on');
                $('li a', ul).on('mouseover', function() {
                    $(this).addClass("_on");
                });
                $('li a', ul).on('mouseleave', function(){
                    $(this).removeClass("_on");
                });
                $('li a', ul).on('mousedown', function(){
                    location.href = this.href;
                });

                div.addClass('_show');
            }
        }


        $(".globalStock #header .searchArea .searchInput").on("focusin", function(){
            if(this.value.length) {
                if(top_search_action) { clearTimeout(top_search_action); }
                var search_input = this;
                top_search_action = setTimeout(function(){
                    doSearch(search_input);
                }, 300);
            }
        });
        $(".globalStock #header .searchArea .searchInput").on("focusout", function(){
            setTimeout(function(){$('.globalStock #header .searchArea .AutoComplete').removeClass('_show');}, 300);
        });
});

