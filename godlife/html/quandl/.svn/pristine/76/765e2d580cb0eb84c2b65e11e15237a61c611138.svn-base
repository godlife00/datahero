$(document).ready(function(){

    //style
    // $('.globalStock #header .searchArea .searchInput::placeholder').css('color','red');
    

    //파일 다운로드
    var fileTarget = $('.filebox .upload-hidden');

    fileTarget.on('change', function(){
        if(window.FileReader){
            var filename = $(this)[0].files[0].name;
        } else {
            var filename = $(this).val().split('/').pop().split('\\').pop();
        }

        $(this).siblings('.upload-name').val(filename);
    });


    //달력
    $( "#sDate").datepicker();                                          
    $( "#eDate").datepicker();                                          
    
    //로그인 팝업
    var loginWindow = $('.mw_login');
    var login = $('#login');
    var uid = $('.i_text.uid');
    var upw = $('.i_text.upw');
    var oid = $('.i_text.oid');        
    
    // Show Hide
    $('.login_trigger').click(function(){
        loginWindow.addClass('open');
    });
    $('#login .close').click(function(){
        loginWindow.removeClass('open');
    });
    // o_login
    $('.o_anchor').click(function(){
        login.removeClass('g_login');
        login.addClass('o_login');
    });
    // g_login
    $('.g_anchor').click(function(){
        login.removeClass('o_login');
        login.addClass('g_login');
    });
    // Warning
    $('#keepid').change(function(){
        if($('#keepid[checked]')){
            $('.warning').toggleClass('open');
        };
    });
    // Input Clear
    var i_text = $('.item>.i_label').next('.i_text');
    $('.item>.i_label').css('position','absolute');
    i_text
        .focus(function(){
            $(this).prev('.i_label').css('visibility','hidden');
        })
        .blur(function(){
            if($(this).val() == ''){
                $(this).prev('.i_label').css('visibility','visible');
            } else {
                $(this).prev('.i_label').css('visibility','hidden');
            }
        })
        .change(function(){
            if($(this).val() == ''){
                $(this).prev('.i_label').css('visibility','visible');
            } else {
                $(this).prev('.i_label').css('visibility','hidden');
            }
        })
        .blur();
    // Validation
    $('#login>.g_login input[type=submit]').click(function(){
        if(uid.val() == '' && upw.val() == ''){
            alert('ID와 PASSWORD를 입력하세요!');
            return false;
        }
        else if(uid.val() == ''){
            alert('ID를 입력하세요!');
            return false;
        }
        else if(upw.val() == ''){
            alert('PASSWORD를 입력하세요!');
            return false;
        }
    });
    $('#login>.o_login input[type=submit]').click(function(){
        if(oid.val() == ''){
            alert('Open ID를 입력하세요!');
            return false;
        }
    });
    // ESC Event
    $(document).keydown(function(event){
        if(event.keyCode != 27) return true;
        if (loginWindow.hasClass('open')) {
            loginWindow.removeClass('open');
        }
        return false;
    });
    // Hide Window
    loginWindow.find('>.bg').mousedown(function(event){
        loginWindow.removeClass('open');
        return false;
    });   

    //서비스 가격 전체보기 팝업
    var priceviewWindow = $('.mw_priceview');
    var priceview = $('#priceview');

    // Show Hide
    $('.priceview_trigger').click(function(){
        priceviewWindow.addClass('open');
    });
    $('#priceview .close').click(function(){
        priceviewWindow.removeClass('open');
    });

    // ESC Event
    $(document).keydown(function(event){
        if(event.keyCode != 27) return true;
        if (priceviewWindow.hasClass('open')) {
            priceviewWindow.removeClass('open');
        }
        return false;
    });
    // Hide Window
    priceviewWindow.find('>.bg').mousedown(function(event){
        priceviewWindow.removeClass('open');
        return false;
    });   

    //결제 방법
    $('.globalStock #container .serviceSteep.step02 .bdr_area').on("click", function(){        
        $('.globalStock #container .paymentTerm').removeClass('auto_system');
        $(this).addClass('auto_system');
    });
    $('.globalStock #container .paymentTerm').on("click", function(){        
        $('.globalStock #container .serviceSteep.step02 .bdr_area').removeClass('auto_system');
        $(this).addClass('auto_system');
    });
    
    
    // 검색어 자동완성    
    $(".globalStock #header .searchArea .AutoComplete li a").on("click", function(){        
        $(this).addClass('_on');
    });
    $(".globalStock #header .searchArea .AutoComplete li a").on("mouseleave", function(){        
        $(".globalStock #header .searchArea .AutoComplete li a").removeClass("_on");        
    });
    $(".globalStock #header .searchArea .AutoComplete li a").on("mouseover", function(){        
        $(this).addClass('_on');
    });        
    $(".globalStock #header .searchArea .searchInput").on("keydown", function(){        
        $('.globalStock #header .searchArea .AutoComplete').addClass('_show');        
    });        
    $(".globalStock #header .searchArea .searchInput").on("focusout", function(){        
        $('.globalStock #header .searchArea .AutoComplete').removeClass('_show');
    });

    $(".globalStock .searchFixed .AutoComplete li a").on("click", function(){        
        $(this).addClass('_on');
    });
    $(".globalStock .searchFixed .AutoComplete li a").on("mouseleave", function(){        
        $(".globalStock .searchFixed .AutoComplete li a").removeClass("_on");        
    });
    $(".globalStock .searchFixed .AutoComplete li a").on("mouseover", function(){        
        $(this).addClass('_on');
    });        
    $(".globalStock .searchFixed .searchInput_fixed").on("keydown", function(){        
        $('.globalStock .searchFixed .AutoComplete').addClass('_show');        
    });        
    $(".globalStock .searchFixed .searchInput_fixed").on("focusout", function(){        
        $('.globalStock .searchFixed .AutoComplete').removeClass('_show');        
    });               
    //검색어 키보드
    
    // 푸터 알립니다
    var swiper = new Swiper('.swiper-container.list', {                    
        direction: 'vertical',                    
        loop: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        }
    });
    
    // $('.globalStock #container .schChartTitle .detail li').on("click", function(){
        // var barWidthBuy = $('.zt-skill-bar div.buyingBar').attr('style').replace(/[^0-9]/g,"");
        // var barWidthSel = $('.zt-skill-bar div.sellBar').attr('style').replace(/[^0-9]/g,"");
        // console.log(barWidthBuy, barWidthSel);
        // if (!barWidthSel == 100) {
        //     $('.zt-skill-bar div.sellBar').css('max-width','none');
        //     $('.zt-skill-bar div.buyingBar').css('display','none');

        // } else if (!barWidthBuy == 100) {
        //     $('.zt-skill-bar div.buyingBar').css('max-width','181px');            
        //     $('.zt-skill-bar div.sellBar').css('display','none');            
        // } else {

        // }        
    // });

    //table
    $( window ).scroll( function() {
        if ($('.tableColtype').hasClass("typeScroll")) {
            var trFixedTop = $('.typeScroll tr.fix_tr').filter(':visible').offset();                
            var positionTable = $('.typeScroll tr.fix_tr').filter(':visible').offset().top;
            var position = $(window).scrollTop() - positionTable + 89;            
            if ( $( document ).scrollTop() > trFixedTop.top - 89 ) {            
                $('.tableColtype').filter(':visible').each(function () {                       
                    $(this).children().children('tr').addClass('trFixed');           
                    $(this).children().children().children('th').css('top',position);            
                    $('.tableColtype th').css('border-bottom','2px solid #fff');            
                });
            } else {            
                $('.typeScroll tr.fix_tr').removeClass('trFixed');            
                $('.tableColtype th').css('border-bottom','2px solid #555');            
            } 
        }
    });

    function tableLength () {
        if ($('.tableColtype').hasClass("typeScroll")) {
            var thLength = $('.globalStock #container .tableData .tableScroll .typeScroll th').length;
            if (thLength <= 12) {
                var thWidth = ($('.globalStock #container .tableData .tableScroll').width() + 80 + (thLength * 5)) / thLength;
                console.log(thWidth);
                $('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th span').css({
                    'width' : thWidth
                });
                $('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th:first span').css({
                    'width' : 161
                })
    
            }
        }        
    }
    tableLength (); 

    $('table.sumtable .fntfmly_num th:nth-child(3)').addClass('bdrRightW');
    $('table.sumtable td:nth-child(1)').addClass('bdrRightW');

    //table 뎁스 디자인
    var tableTrLineChk = $('.globalStock #container .tableData .tableScroll .typeScroll tr.depth01').next('tr');
    $('.globalStock #container .tableData .tableScroll .typeScroll tr.depth01').each(function(){        
        if (!$(this).next().hasClass("depth02")) {        
            $(this).addClass('lineSolo');
        }
    });
    $('.globalStock #container .tableData .tableScroll .typeScroll.tableBill tr.depth01').each(function() {
        if ($(this).next().hasClass('depth02')) {            
            $(this).addClass('lastD01');
        }
    });
    $('.globalStock #container .tableData .tableScroll .typeScroll.tableBill tr.depth02').each(function() {
        if ($(this).next().hasClass('depth01')) {            
            $(this).addClass('lastD02');
        }
    });
    $('.globalStock #container .tableData .tableScroll .typeScroll.tableReports tr.depth03').each(function() {
        if ($(this).next().hasClass('depth02')) {            
            $(this).addClass('last03');
        } else if ($(this).next().hasClass('depth01')) {
            $(this).addClass('last03');
        }
    });

    //말줄임시 title 추가
    var txtEllipsis = $('.tableRanking a, .tableRanking span, .tableColtype a, .tableColtype span, .schChartTitle h2, .tableRowtype th');                    
    $(txtEllipsis).each( function() {
        var txtThis = $(this).text();            
        $(this).prop('title', txtThis);
    })

    //summary 정보 없는 경우
    $('.globalStock #container .summary .etc li').each(function(){        
        // console.log( $(this).text().length );
        if ($(this).text().length <= 3) {            
            $(this).hide();
        }
    });
    
    
    $(".globalStock #container .tableData .tableTab span").on("click", function(){
        addClassLine();
    });

    function addClassLine () {
        $('.globalStock tr.depth02 td, .globalStock tr.depth03 td').each(function(){
            var spanHeight = $(this).children('span').height();                        
            if (spanHeight >= 18) {            
                $(this).addClass('line2');
            }
        });
    }
    addClassLine ();
    

    //header    
    var jbOffset = $('.globalStock #header').offset();        
    if ( $( document ).scrollTop() > jbOffset.top ) {
        $('.globalStock #header').addClass( 'navFixed' );
        $('.globalStock #container').css('margin-top','48px');        
        $('.globalStock .mainbg').css('background-position','0 12px');
        $(".globalStock #header.navFixed .headerLogo img.logo_gs").attr("src", 'http://menu.itooza.com/globalstock/img/_h1_logo_s.png');
        $(".globalStock #header.navFixed .searchArea .searchBtn").attr("src", 'http://menu.itooza.com/globalstock/img/sch_btn_s.png');
    }
    else {
        $('.globalStock #header').removeClass( 'navFixed' );            
        $('.globalStock #container ').css('margin-top','0');
        $('.globalStock .mainbg').css('background-position','0 99px');        
        $(".globalStock #header .headerLogo img.logo_gs").attr("src", 'http://menu.itooza.com/globalstock/img/_h1_logo.png');
        $(".globalStock #header.navFixed .searchArea .searchBtn").attr("src", 'http://menu.itooza.com/globalstock/img/sch_btn.png');
    }
    
    $( window ).scroll( function() {        
        if ( $( document ).scrollTop() > jbOffset.top ) {            
            $('.globalStock #header').addClass( 'navFixed' );
            $('.globalStock #container ').css('margin-top','48px');            
            $('.globalStock .mainbg').css('background-position','0 12px');
            $(".globalStock #header.navFixed .headerLogo img.logo_gs").attr("src", 'http://menu.itooza.com/globalstock/img/_h1_logo_s.png');            
            $(".globalStock #header.navFixed .searchArea .searchBtn").attr("src", 'http://menu.itooza.com/globalstock/img/sch_btn_s.png');            
        }
        else {
            $('.globalStock #header').removeClass( 'navFixed' ).addClass('test');            
            $('.globalStock #container ').css('margin-top','0');
            $('.globalStock .mainbg').css('background-position','0 99px');
            $(".globalStock #header .headerLogo img.logo_gs").attr("src", 'http://menu.itooza.com/globalstock/img/_h1_logo.png');            
        }
    }); 
    $( window ).scroll( function() {        
        if ( $( document ).scrollTop() == 0 ) {                                    
            $(".globalStock #header .searchArea .searchBtn").addClass('111').attr("src", 'http://menu.itooza.com/globalstock/img/sch_btn.png');            
        }
    });    
    
    // select
    // Common
	var select_root = $('div.select');
	var select_value = $('.my_value');
	var select_a = $('div.select>ul>li>a');
	var select_input = $('div.select>ul>li>input[type=radio]');
	var select_label = $('div.select>ul>li>label');	
	// Radio Default Value
	$('div.my_value').each(function(){
		var default_value = $(this).next('.i_list').find('input[checked]').next('label').text();
		$(this).append(default_value);
	});	
	// Line
	select_value.bind('focusin',function(){$(this).addClass('outLine')});
	select_value.bind('focusout',function(){$(this).removeClass('outLine')});
	select_input.bind('focusin',function(){$(this).parents('div.select').children('div.my_value').addClass('outLine')});
	select_input.bind('focusout',function(){$(this).parents('div.select').children('div.my_value').removeClass('outLine')});	
	// Show
	function show_option(){
		$(this).parents('div.select:first').toggleClass('open');
	}	
	// Hover
	function i_hover(){
		$(this).parents('ul:first').children('li').removeClass('hover');
		$(this).parents('li:first').toggleClass('hover');
	}	
	// Hide
	function hide_option(){
		var t = $(this);
		setTimeout(function(){
			t.parents('div.select:first').removeClass('open');
		}, 1);
	}	
	// Set Input
	function set_label(){
		var v = $(this).next('label').text();
		$(this).parents('ul:first').prev('.my_value').text('').append(v);
		$(this).parents('ul:first').prev('.my_value').addClass('selected');
	}	
	// Set Anchor
	function set_anchor(){
		var v = $(this).text();
		$(this).parents('ul:first').prev('.my_value').text('').append(v);
		$(this).parents('ul:first').prev('.my_value').addClass('selected');
	}
	// Anchor Focus Out
	$('*:not("div.select a")').focus(function(){
		$('.a_list').parent('.select').removeClass('open');
	});			
	select_value.click(show_option);
	select_root.removeClass('open');
	select_root.mouseleave(function(){$(this).removeClass('open')});
	select_a.click(set_anchor).click(hide_option).focus(i_hover).hover(i_hover);
	select_input.change(set_label).focus(set_label);
    select_label.hover(i_hover).click(hide_option);
    
    //메인 세로탭 메뉴
    var $items = $('#vtab>ul>li');
    $items.click(function() {
        $items.removeClass('selected');
        $(this).addClass('selected');

        var index = $items.index($(this));
        $('#vtab>div').hide().eq(index).show();
    }).eq(0).click();

    //.globalStock #container .subDivision .subRight
    var leftHeight = $('.globalStock #container .subDivision .subLeft').height();
    var rightHeight = $('.globalStock #container .subDivision .subRight').height();

    if ( leftHeight >= rightHeight ) {
        $('.subRight').css('height', leftHeight);
    } else {
        $('.subLeft').css('height', rightHeight);
    }

    //메인 우측 관심종목,종목토론
    function openCity(evt, cityName) {        
        console.log("11111");
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";        
    } 

    // 탭메뉴
    $("ul.tabs li").on("click", function(){                        
        if ($(this).index() !== 3 && $(this).index() !== 4  && $(this).index() !== 8 ) {
            $("ul.tabs li").removeClass("active");        
            $(this).addClass('active active_fix');        
        }
    });    
    $("ul.tabs li").on("mouseleave", function(){                
        // $(this).addClass('active active_fix');
    });
    $("ul.tabs li").on("mouseover", function(){
        $("ul.tabs li").removeClass("active_fix");        
    });

    $(".globalStock #nav .gnb li a").on("click", function(){        
        $(this).addClass('active active_fix');
    });
    $(".globalStock #nav .gnb li a").on("mouseleave", function(){        
        $(this).addClass('active active_fix');
    });
    $(".globalStock #nav .gnb li a").on("mouseover", function(){
        $(".globalStock #nav .gnb li a").removeClass("active");        
    });

    $(".globalStock #container .schChartTitle .info li.attention").on("click", function(){
        $(this).toggleClass("like");
    });
    $(".cb_module .cb_lstcomment .cb_section .cd_attention").on("click", function(){
        $(this).toggleClass("like");
    });
    
    // $(".globalStock #container .schChartTitle .detail li.deal .sell").on("click", function(){
    //     $(this).toggleClass("chk");
    // });
    // $(".globalStock #container .schChartTitle .detail li.deal .buying").on("click", function(){
    //     $(this).toggleClass("chk");
    // });

    // $(".globalStock #container .tableData .tableTab span").on("mouseover", function(){
    //     $(this).parent().children('span').removeClass("active");        
    // });
    // $(".globalStock #container .tableData .tableTab span").on("mouseleave", function(){
    //     $(this).addClass('active active_fix');  
    // });    
    $(".globalStock #container .tableData .tableTab span").on("click", function(){
        $(this).parent().children('span').removeClass("active");
        $(this).addClass('active active_fix');  
    });
        
    $(".globalStock #container .eventShopping .txtShopping li a").on("mouseover", function(){
        $('.globalStock #container .eventShopping .txtShopping li a').removeClass("active");        
    });
    $(".globalStock #container .eventShopping .txtShopping li a").on("mouseleave", function(){
        $(this).removeClass('active');  
    });    
    $(".globalStock #container .eventShopping .txtShopping li a").on("click", function(){
        $('.globalStock #container .eventShopping .txtShopping li a').removeClass("active_fix");
        $(this).addClass('active active_fix');  
    });
    
    $(".globalStock #container .mainRight .rightTop .txtEvent button a").on("mouseleave", function(){
        $(this).addClass('active active_fix');  
    });    
    $(".globalStock #container .mainRight .rightTop .txtEvent button a").on("click", function(){
        $(this).addClass('active active_fix');  
    });
    
    
    var txtPsize = $('.globalStock #container .summary p').height();
    $(".globalStock #container .summary .close").on("click", function(){                
        if ($(this).hasClass("show")) {            
            $(this).removeClass('show');            
            $('.globalStock #container .summary .etc').slideUp(300);
            $('.globalStock #container .summary p').animate({
                width : "80%",
                height : '20px'
            },300).addClass('small');
            
        } else {            
            $(this).addClass('show');
            $('.globalStock #container .summary .etc').slideDown(300);
            $('.globalStock #container .summary p').animate({
                width : "100%",
                height : txtPsize
            },300).removeClass('small');
        }
        
        if ($(".globalStock #container .summary").hasClass("small")) {
           $(".globalStock #container .summary").removeClass('small');
        } else {
           $(".globalStock #container .summary").addClass('small');
        }
    });

    var txtPsize_ftr = $('.globalStock #container .summary_ftr p').height();        
    $('.globalStock #container .summary_ftr .close .txt').text('열기');
    $(".globalStock #container .summary_ftr .close").on("click", function(){          
        
        if ($(this).hasClass("show")) {            
            $(this).removeClass('show');                        
            $('.globalStock #container .summary_ftr .close .txt').text('닫기');
            $('.globalStock #container .summary_ftr .etc').slideUp(300);
            $('.globalStock #container .summary_ftr').animate({
                // width : "80%",
                height : '100%'                
            },300).addClass('small');
            
        } else {            
            $(this).addClass('show');
            $('.globalStock #container .summary_ftr .close .txt').text('열기');            
            $('.globalStock #container .summary_ftr .etc').slideDown(300);
            $('.globalStock #container .summary_ftr').animate({
                // width : "100%",
                height : '20px'
            },300).removeClass('small');
        }
        
        if ($(".globalStock #container .summary_ftr").hasClass("small")) {
           $(".globalStock #container .summary_ftr").removeClass('small');
        } else {
           $(".globalStock #container .summary_ftr").addClass('small');
        }
    });
        
    $(".globalStock #footer .familySite .familyLink").on("click", function(){                
        if ($(this).hasClass("hide")) {            
            $(this).removeClass('hide').addClass('view');
            $('.globalStock #footer .familySite .familyLink .ly_help').removeClass('hide').addClass('view');
        } else {            
            $(this).removeClass('view').addClass('hide');
            $('.globalStock #footer .familySite .familyLink .ly_help').removeClass('view').addClass('hide');
        }        
    });
    
    $(".globalStock #container .tableData .standard .simple").on("click", function(){                
        $(this).toggleClass('chk')
    });
    
    $(".globalStock #container .tableColtype.typeOrder th").on("click", function(){                
        if ($(this).children('i').hasClass("ascending")) {            
            $(this).children('i').removeClass('ascending').addClass('descending');            
        } else {   
            $(this).children('i').removeClass('descending').addClass('ascending');            
        } 
    });    

    $(".globalStock #container .tableData .competitors li a").on("click", function(){
        $(".globalStock #container .tableData .competitors li a").removeClass("active");
        $(this).addClass("active active_fix");      
        event.preventDefault();
        event.stopPropagation();  
    });
    $(".globalStock #container .tableData .competitors li a").on("mouseleave", function(){
        $(this).addClass("active_fix");      
    });
    // $(".globalStock #container .tableData .competitors li a").on("mouseover", function(){
    //     $(".globalStock #container .tableData .competitors li a").removeClass("active");        
    // });

    $(".globalStock #container .schChartArea .chartTabs span").on("click", function(){
        $(this).addClass("active active_fix");              
    });
    $(".globalStock #container .schChartArea .chartTabs span").on("mouseover", function(){
        $(".globalStock #container .schChartArea .chartTabs span").removeClass("active");        
        $(this).addClass("active active_fix");      
    });

    // $(".globalStock #container .chartArea .chartTabsArea .chartTabs span").on("click", function(){
    //     $(".globalStock #container .chartArea .chartTabsArea .chartTabs span").removeClass("active");
    //     $(this).addClass("active active_fix");              
    // });
    // $(".globalStock #container .chartArea .chartTabsArea .chartTabs span").on("mouseover", function(){
    //     $(".globalStock #container .chartArea .chartTabsArea .chartTabs span").removeClass("active");        
    //     $(this).addClass("active active_fix");      
    // });
    

    $(".globalStock #container .schChartArea .sizeCustom ").on("click", function(){        
        if ($(this).hasClass("sizeSmall")) {
            $(this).removeClass('sizeSmall').addClass('sizeBig');;            
            
            $('.globalStock #container .schChartArea .chartSection').addClass('sBig');
            $('.globalStock #container .schChartArea .chartSection #chart-container').addClass('sBig');
            $('.globalStock #container .schChartArea .chartDate').addClass('sBig');
        } else {
            $(this).removeClass('sizeBig').addClass('sizeSmall');
            $('.globalStock #container .schChartArea .chartSection').removeClass('sBig')
            $('.globalStock #container .schChartArea .chartSection #chart-container').removeClass('sBig');
            $('.globalStock #container .schChartArea .chartDate').removeClass('sBig');
        }
    });

        
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
    $('.faq01 .faq').find('.article:first .a').show();
    $('.faq01 .faq').find('.article:first').addClass('show').removeClass('hide');
    
	
	$('.faq01 .faq .article .trigger').click(function(){
		var myArticle = $(this).parents('.article:first');
		if(myArticle.hasClass('hide')){
			article.addClass('hide').removeClass('show'); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
			article.find('.a').slideUp(100); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
			myArticle.removeClass('hide').addClass('show');
			myArticle.find('.a').slideDown(100);
		} else {
			myArticle.removeClass('show').addClass('hide');
			myArticle.find('.a').slideUp(100);
		}
    });

    $('.faq02 .faq .article .trigger').click(function(){
		var myArticle = $(this).parents('.article:first');
		if(myArticle.hasClass('hide')){
			article2.addClass('hide').removeClass('show'); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
			article2.find('.a').slideUp(100); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
			myArticle.removeClass('hide').addClass('show');
			myArticle.find('.a').slideDown(100);
		} else {
			myArticle.removeClass('show').addClass('hide');
			myArticle.find('.a').slideUp(100);
		}
    });

    $('.faq03 .faq .article .trigger').click(function(){
		var myArticle = $(this).parents('.article:first');
		if(myArticle.hasClass('hide')){
			article3.addClass('hide').removeClass('show'); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
			article3.find('.a').slideUp(100); // 아코디언 효과를 원치 않으면 이 라인을 지우세요
			myArticle.removeClass('hide').addClass('show');
			myArticle.find('.a').slideDown(100);
		} else {
			myArticle.removeClass('show').addClass('hide');
			myArticle.find('.a').slideUp(100);
		}
    });


    
	// $('.faq .hgroup .trigger').click(function(){
	// 	var hidden = $('.faq .article.hide').length;
	// 	if(hidden > 0){
	// 		article.removeClass('hide').addClass('show');
	// 		article.find('.a').slideDown(100);
	// 	} else {
	// 		article.removeClass('show').addClass('hide');
	// 		article.find('.a').slideUp(100);
	// 	}
	// });
	
});