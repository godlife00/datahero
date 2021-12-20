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
                    match_li = $('<li><a href="/stocks/summary/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>');
                    continue;
                }
                if((ticker+search_ticker_list[tic].name).toUpperCase().indexOf(search) >= 0) {
                    find_lis.push($('<li><a href="/stocks/summary/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>'));
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

// fixed 검색어 자동
$(function(){
        var top_search_action = false;
        var lis = [];
        $(".globalStock .searchFixed .searchInput_fixed").on("keyup", function(e){
                var key = e.keyCode;
                switch(key) {
                    case 13:
                        e.preventDefault();
                        moveToSearch($('.globalStock .searchFixed .AutoComplete'));
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

            var div = $('.globalStock .searchFixed .AutoComplete');

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
                    match_li = $('<li><a href="/stocks/summary/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>');
                    continue;
                }
                if((ticker+search_ticker_list[tic].name).toUpperCase().indexOf(search) >= 0) {
                    find_lis.push($('<li><a href="/stocks/summary/'+tic+'"><span class="schCode">'+tic+'</span><span class="schList">'+search_ticker_list[tic].name+'</span></a></li>'));
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


        $(".globalStock .searchFixed .searchInput_fixed").on("focusin", function(){
            if(this.value.length) {
                if(top_search_action) { clearTimeout(top_search_action); }
                var search_input = this;
                top_search_action = setTimeout(function(){
                    doSearch(search_input);
                }, 300);
            }
        });
        $(".globalStock .searchFixed .searchInput_fixed").on("focusout", function(){
            setTimeout(function(){$('.globalStock .searchFixed .AutoComplete').removeClass('_show');}, 100);
        });
        $(".globalStock .searchFixed .AutoComplete li a").on("mouseleave", function(){
            $(".globalStock .searchFixed .AutoComplete li a").removeClass("_on");
        });
        $(".globalStock .searchFixed .AutoComplete li a").on("mouseover", function(){
            $(this).addClass('_on');
        });
});

