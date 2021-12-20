		</div>
        <!-- //container -->
        <div id="footer"<?=($is_main===FALSE && $type != '4') ? ' class="type_sub"':''?>>
            <div class="notice">
                <div class="foot_info">
                    <div class="service_info">
                        <p>본 서비스는 투자판단에 참고용으로만 사용하실 수 있으며, 모든 투자판단은 투자자 본인의 책임으로 당사는 그 결과에 대해 법적인 일체의 책임을 지지 않습니다.</p>
                        <p class="dataLink">data from <a href="https://www.quandl.com/" target="_blank">Quandl and Sharadar</a></p>
                    </div>
                </div>
                <!-- //foot_info -->
            </div>
            <!-- //notice -->
        </div>
        <!-- //footer -->
    </div>
    <!-- //wrap -->
</body>

<!-- Modal popup -->
<!-- 팍스초이스 프리미엄 2주 무료로 이용하세요 -->
<div class="free_pop md-modal md-effect-3" id="modal-3"> <!-- class : md-show 가 없는 경우 팝업 숨김, md-show 있는 경우 팝업 노출  -->
</div>
<div class="md-overlay"></div><!-- the overlay element -->

<script>
var CurrentPriceSyncer = function() {
    var sync_sets = {};
    var last_price = {};

    var tickers = [];
    var current_info_map = [];

    this.init = function() {
        $('.sync_price').each((idx, el) => {
            var node = $(el);
            if(node.data('ticker') == null) return;
            cps.add(node.data('ticker'), node, 'price');
        });
        $('.sync_diff_rate').each((idx, el) => {
            var node = $(el);
            if(node.data('ticker') == null) return;
            cps.add(node.data('ticker'), node, 'diff_rate');
        });
        $('.sync_diff_price').each((idx, el) => {
            var node = $(el);
            if(node.data('ticker') == null) return;
            cps.add(node.data('ticker'), node, 'diff_price');
        });
    }

    this.add = function(ticker, el, value_type, callback) {
        value_type = value_type.toLowerCase();
        if($.inArray(value_type, ['price', 'diff_rate', 'diff_price', 'custom']) < 0) {
            return false;
        }
        if(sync_sets[ticker] == null) {
            tickers.push(ticker);
            sync_sets[ticker] = [];
        }
        sync_sets[ticker].push({
            target: el,
            type: value_type,
            cb: callback
        });
    }

    this.run = function(callback) {
        if(tickers.length <= 0) return;


        $.post('/api/getTickerPrice/'+(tickers.join('_')), {}, function(resp) {
            //console.log(resp);
            var change_flag = false;
            var callback_resp = {}; // 이번 틱에 변경 내역 있는 티커들만 담는다.
            for(var ticker in resp) {
                var info = resp[ticker];
                var nodes = sync_sets[ticker];
                var prev_price = last_price[ticker] == null ? 0 : last_price[ticker];;
                if(last_price[ticker] == null || last_price[ticker] != info.last_price) {
                    change_flag = true;
                    if(last_price[ticker] != null) {
                        prev_price = last_price[ticker];
                    }
                    last_price[ticker] = info.last_price;
                    callback_resp[ticker] = info;
                }
                for(var i in nodes) {
                    var n = nodes[i];
                    
                    switch(n.type) {
                        case 'price' :
                            if(last_price[ticker] != prev_price) {
                                var price = info.last_price;

                                if(n.target.data('render') != null) {
                                    eval('var renderer = '+n.target.data('render'));
                                    price = renderer(n.target, price, info);
                                }

                                // 깜빡이는 색깔 ff47474
                                n.target.stop().animate({'background-color':'#ddd'}, 500).html(price).animate({'background-color':'transparent'}, 500);
                            }
                            break;

                        case 'diff_rate' :
                            if(callback_resp[ticker] != null) {
                                var rate = info.diff_rate;

                                if(n.target.data('render') != null) {
                                    eval('var renderer = '+n.target.data('render'));
                                    rate = renderer(n.target, rate, info);
                                }
                                n.target.html(rate);
                            }
                            break;

                        case 'diff_price' :
                            if(callback_resp[ticker] != null) {
                                var diff_price= info.diff_price;

                                if(n.target.data('render') != null) {
                                    eval('var renderer = '+n.target.data('render'));
                                    diff_price = renderer(n.target, diff_price, info);
                                }
                                n.target.html(diff_price);
                            }
                            break;

                        case 'custom' :
                            break;
                    }
                }
            }
            if(change_flag) {
                callback(callback_resp, sync_sets);
            }
        }, 'json');
    }

    // Getters
    this.getSyncSets = function() {
        // 실시간 정보 연동 설정한 태그 정보 리턴
        return sync_sets;
    }
    this.getTickers = function() {
        // 실시간 정보 연동 설정한 종목 티커코드 리스트 리턴
        return tickers;
    }
}
var cps = new CurrentPriceSyncer();
cps.init();

$(function() {
    // 실시간 정보 싱커가 1개 이상 존재하면 구동!
    if(cps.getTickers().length > 0) {
        var current_info_callback = (current_data, nodes) => {
            // 실시간 연동 부 렌터더링 모두 마친 후 변경 있는 종목 정보만 콜백으로 정보 줌.
            // 이곳을 클래스 안으로 넣고 셋터를 구현하여
            // 페이지별 별도 처리부를 구현 가능함.

            //console.log(current_data);
            //console.log(nodes);
        };
        cps.run(current_info_callback);
        setInterval(() => {cps.run(current_info_callback);}, 5000);
    }
});
</script>
</html>