            <div class="sub_top">
                <div class="">
                    <h4 class="title"><strong>종목발굴을 위한</strong>만가지 레시피</h4>
                    <p class="txt">투자자들이 어려워하는 미국주식의 종목발굴을 위해 다양한 컨셉의 종목발굴 투자레시피를 알려드립니다.</p>
                </div>
                <!-- //best_research -->

            </div>
            <!-- //sub_top -->

            <div class="sub_mid recipe_tabs">

                <div class="tabsArea_2">
                    <ul class="tabs_2">
                        <li class="active" rel="tab4">배당매력주</li>
                        <li rel="tab5">이익성장주</li>
                        <li rel="tab6">소비자독점</li>
                        <li rel="tab7">슈퍼스톡</li>
                    </ul>
                    <div class="tab_container">
                        <!-- 배당매력주 -->
                        <div id="tab4" class="tab_content" style="display: block;">
                            <div class="remark">
                                <p>초보도 벌 수 있는 투자의 정석 <br> “고배당주에 투자하라”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="1" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="70px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody id="dividend_list">
                                    <tr>                                        
                                        <td colspan="2" class="data_guide t01">주가</td>
                                        <td class="data_guide t02">배당매력</td>
                                        <td class="data_guide t03">배당수익률</td>
                                    </tr>
                                    <?=$dividend_content_html?>
                                </tbody>
                            </table>

                            <div class="btn_more">
                                <a href="javascript:;" onclick="view_more('dividend', this)"><i></i>더보기</a>
                            </div>
                            <!-- //btn_more -->
                        </div>
                        <!-- //배당매력주 -->

                        <!-- 이익성장주 -->
                        <div id="tab5" class="tab_content" style="display: none;">
                            <div class="remark">
                                <p>위대한 기업을 찾는 공식 <br> “내일의 넷플릭스를 찾아라”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="1" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="70px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody id="growth_list">
                                    <tr>                                        
                                        <td colspan="2" class="data_guide t01">주가</td>
                                        <td class="data_guide t02">수익성장성</td>
                                        <td class="data_guide t03">순이익성장률</td>
                                    </tr>
                                    <?=$growth_content_html?>
                                </tbody>
                            </table>

                            <div class="btn_more">
                                <a href="javascript:;" onclick="view_more('growth', this)"><i></i>더보기</a>
                            </div>
                            <!-- //btn_more -->
                        </div>
                        <!-- //이익성장주 -->

                        <!-- 소비자독점 -->
                        <div id="tab6" class="tab_content" style="display: none;">
                            <div class="remark">
                                <p>스노우볼 투자전략의 핵심 <br> “소비자 독점 기업을 찾아라”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="1" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="70px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody id="moat_list">
                                    <tr>                                        
                                        <td colspan="2" class="data_guide t01">주가</td>
                                        <td class="data_guide t02">사업독점력</td>
                                        <td class="data_guide t03">영업이익률</td>
                                    </tr>
                                    <?=$moat_content_html?>
                                </tbody>
                            </table>

                            <div class="btn_more">
                                <a href="javascript:;" onclick="view_more('moat', this)"><i></i>더보기</a>
                            </div>
                            <!-- //btn_more -->
                        </div>
                        <!-- //소비자독점 -->

                        <!-- 슈퍼스톡 -->
                        <div id="tab7" class="tab_content" style="display: none;">
                            <div class="remark">
                                <p>슈퍼스톡 <br> “뛰는 주 위에 나는 주, 슈퍼종목을 찾아라!”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="1" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="70px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody id="total_score_list">
                                    <tr>                                        
                                        <td colspan="2" class="data_guide t01">주가</td>
                                        <td class="data_guide t02">투자매력</td>
                                        <td class="data_guide t03">5년 ROE</td>
                                    </tr>
                                    <?=$total_score_content_html?>
                                </tbody>
                            </table>

                            <div class="btn_more">
                                <a href="javascript:;" onclick="view_more('total_score', this)"><i></i>더보기</a>
                            </div>
                            <!-- //btn_more -->
                        </div>
                        <!-- //슈퍼스톡 -->
                    </div>
                    <!-- .tab_container -->                    
                </div>

                <!--<p class="dataLink">data from <a href="https://www.quandl.com/" target="_blank">Quandl and
                        Sharadar</a>
                </p>-->

            </div> <!-- //sub_mid -->


<script>
var is_loading = false;
var type_page_map = {
    'dividend': 1,
    'growth': 1,
    'moat': 1,
    'total_score': 1,
};
function view_more(type, obj) {
    if(is_loading) {
        return;
    }
    is_loading = true;
    type_page_map[type] += 1;

    $.get('/stock/ajax_get_recipe_list', {'type': type, 'page': type_page_map[type]}, function(res) {
        if($.trim(res).length) {
            $('#'+type+'_list').append(res);
        } else {
            $(obj).parent().hide();
        }
        is_loading = false;
    });
}
</script>
