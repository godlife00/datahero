function moveToSearch(div) {
    //var div = $('.globalStock #header .searchArea .AutoComplete');

    if ($('li a._on', div).length > 0) {
        var keyword = $('li a._on:first span.schCode', div).html();
        setSearchHistory(keyword);
        location.href = '/search/invest_charm/' + keyword;
    } else {

        if ($('li.show', div).length > 0) {
            var keyword = $('li.show:first a', div).data('id');
            setSearchHistory(keyword);
            location.href = '/search/invest_charm/' + keyword;
        }
    }
}

// 검색어 저장
function setSearchHistory(keyword) {
    /*
    var save_history = '';
    var search_history = getCookie('search_history');
    var max = 10;

    if(search_history == null) {
        save_history = keyword;
    } else {
        search_history = search_history.split(',');

        if($.inArray(keyword, search_history) > -1) {
            search_history.splice($.inArray(keyword, search_history), 1);
        }
        search_history.unshift(keyword);
        if(search_history.length > max) {
            search_history = search_history.slice(0, 10);
        }
        save_history = search_history.join(',');
    }
    setCookie('search_history', save_history, 365);
    */

    $.ajax({
        url: '/main/ajax_save_search_history',
        type: 'GET',
        data: { 'ticker': keyword },
        async: false,
        success: function () {
        }
    });
}

function setCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}



/*****
// 검색어 자동완성    
$(function () {
    var top_search_action = false;
    var lis = [];
    $(".globalStock .searchArea .searchInput").on("keyup", function (e) {
        var key = e.keyCode;
        switch (key) {
            case 13:
                e.preventDefault();
                moveToSearch($('.globalStock #header .searchArea .AutoComplete'));
                break;

            case 9:
            case 16:
            case 37:
            case 38:
            case 39:
            case 40:
            case 27:
                break;

            default:
                var search_input = this;
                if (top_search_action) { clearTimeout(top_search_action); }
                top_search_action = setTimeout(function () {
                    doSearch(search_input);
                }, 400);
        }
    });

    function doSearch(obj) {
        var div = $('.globalStock .searchArea .AutoComplete');

        for (var i in lis) {
            $('a._on', lis[i]).removeClass('_on');
        }
        lis = [];
        div.removeClass('_show');
        if (obj.value.length <= 0) {
            return;
        }

        var search = obj.value.toUpperCase();
        var ul = $('ul:first', div);
        ul.html('');
        var match_li = null;
        var find_lis = [];
        for (var tic in search_ticker_list) {
            var ticker = tic.toUpperCase();
            if (ticker == search) {
                // ticker 일치
                match_li = $('<li><a href="javascript:;" class="_on" data-id="' + tic + '"><span class="schCode">' + tic + '</span><span class="schList">' + search_ticker_list[tic].name + '</span></a></li>');
                continue;
            }
            if ((ticker + search_ticker_list[tic].name).toUpperCase().indexOf(search) >= 0) {
                find_lis.push($('<li><a href="javascript:;" data-id="' + tic + '"><span class="schCode">' + tic + '</span><span class="schList">' + search_ticker_list[tic].name + '</span></a></li>'));
            }
        }
        if (match_li !== null) {
            ul.append(match_li);
        }
        if (find_lis.length > 0) {
            ul.append(find_lis);
        }
        if (find_lis.length > 0 || match_li !== null) {
            $('li:first a', ul).addClass('_on');
            $('li a', ul).on('mouseover', function () {
                $(this).addClass("_on");
            });
            $('li a', ul).on('mouseleave', function () {
                $(this).removeClass("_on");
            });
            $('li a', ul).on('mousedown', function () {
                var keyword = $(this).data('id');
                setSearchHistory(keyword);
                location.href = '/search/invest_charm/' + keyword;
            });
            div.addClass('_show');
        }
    }

    $(".globalStock .searchArea .searchInput").on("focusin", function () {
        if (this.value.length) {
            if (top_search_action) { clearTimeout(top_search_action); }
            var search_input = this;
            top_search_action = setTimeout(function () {
                doSearch(search_input);
            }, 300);
        }
    });
    $(".globalStock .searchArea .searchInput").on("focusout", function () {
        setTimeout(function () { $('.globalStock .searchArea .AutoComplete').removeClass('_show'); }, 300);
    });

});
*****/

// fixed 검색어 자동
$(function () {
    var top_search_action = false;
    var lis = [];
    $(".globalStock .searchArea .searchInput_fixed").on("keyup", function (e) {
        var key = e.keyCode;
        switch (key) {
            case 13:
                e.preventDefault();
                moveToSearch($('.globalStock .sch_autocomplete'));
                break;

            case 9:
            case 16:
            case 37:
            case 38:
            case 39:
            case 40:
            case 27:
                break;

            default:
                var search_input = this;
                if (top_search_action) { clearTimeout(top_search_action); }
                top_search_action = setTimeout(function () {
                    doSearch(search_input);
                }, 400);
        }
    });

    function doSearch(obj) {

        var div = $('.globalStock .sch_autocomplete');

        for (var i in lis) {
            $('a._on', lis[i]).removeClass('_on');
        }
        lis = [];
        div.removeClass('_show');
        if (obj.value.length <= 0) {
            return;
        }

        var search = obj.value.toUpperCase();
        var ul = $('ul:first', div);
        ul.html('');
        var match_li = null;
        var find_lis = [];
		//alert(search_ticker_list);
        //console.log(search_ticker_list);
        for (var tic in search_ticker_list) {
            var ticker = tic.toUpperCase();
            if (ticker == search) {
                // ticker 일치
                match_li = $('<li><a href="/search/invest_charm/' + tic + '"><span class="schCode">' + tic + '</span><span class="schList">' + search_ticker_list[tic].name + '</span></a></li>');
                continue;
            }
            if ((ticker + search_ticker_list[tic].name).toUpperCase().indexOf(search) >= 0) {
                find_lis.push($('<li><a href="/search/invest_charm/' + tic + '"><span class="schCode">' + tic + '</span><span class="schList">' + search_ticker_list[tic].name + '</span></a></li>'));
            }
        }
        if (match_li !== null) {
            ul.append(match_li);
        }
        if (find_lis.length > 0) {
            ul.append(find_lis);
        }
        if (find_lis.length > 0 || match_li !== null) {
            $('li:first a', ul).addClass('_on');
            $('li a', ul).on('mouseover', function () {
                $('li a', ul).removeClass('_on');
                $(this).addClass("_on");
            });
            $('li a', ul).on('mousedown', function () {
                location.href = this.href;
            });

            $('.globalStock .sch_autocomplete .no_result').hide();

            div.addClass('_show');
        } else {
            var no_result = '<p>"<strong>' + search + '</strong>"에 대한 검색결과가 없습니다.</p>';
            $('.globalStock .sch_autocomplete .no_result').html(no_result).show();
        }
    }


    $(".globalStock .searchArea .searchInput_fixed").on("focusin", function () {
        if (this.value.length) {
            if (top_search_action) { clearTimeout(top_search_action); }
            var search_input = this;
            top_search_action = setTimeout(function () {
                doSearch(search_input);
            }, 300);
        }
    });
    $(".globalStock .searchArea .searchInput_fixed").on("focusout", function () {
        setTimeout(function () { $('.globalStock .sch_autocomplete').removeClass('_show'); }, 100);
    });
    $(".globalStock .searchArea .sch_autocomplete li a").on("mouseleave", function () {
        $(".globalStock .sch_autocomplete li a").removeClass("_on");
    });
    $(".globalStock .sch_autocomplete li a").on("mouseover", function () {
        $(this).addClass('_on');
    });
});


$(document).ready(function () {
    $(".globalStock .schArea .searchArea .schBack").on("click", function () {
        $('#container').css('overflow', 'auto').height('');
        $('#footer').css('overflow', 'auto').height('');
    });

    //알람
    if ($('.panelAlarmMenu').length) {

        $(".globalStock #header .panelAlarmMenu").on("click", function () {
            $('.globalStock .alarmArea').addClass('schFocus');
            $('#container').css('overflow', 'hidden').height(0);
        });
    }


});

// override publish

