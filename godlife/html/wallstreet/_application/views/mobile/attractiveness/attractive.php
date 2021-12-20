
            <div class="sub_top">
                <div class="">                    
                    <div class="txt_box">
                        <p class="txt">
                        미국 주식 5,000개 기업의 투자매력도 순위를 제공합니다.<br><br>                                                
                        빅 데이터 전문 기업인 (주)데이터히어로가 개발한 스파이더 (SPIDER) 알고리즘에 따라 투자매력을 제시합니다.<br>투자매력을 판단하는 22개 요인 (Factor) 분석에 따라 <strong>배당 매력, 사업 독점력, 재무안전성, 수익성장성, 현금창출력</strong>을 평가해 투자매력 점수와 순위를 제공합니다.
                        </p>
                    </div>
                    <!-- //txt_box -->
                </div>                
            </div>
            <!-- //sub_top -->

            <div class="sub_mid attract_sub">

                <div class="set">
                    <ul>
                        <li class=""><strong>시가총액</strong></li>
                        <li class=""><?=$marketcap_map[$marketcap]?></li>
                        <li class=""><?=$netincome_map[$netincome]?></li>
                    </ul>
                    <a href="#setting" class="btn_schSet clse_trigger"><i></i>검색</a>
                </div>

                <p class="table_guide">*항목 점수/시가총액 순 정렬</p>
                <table cellspacing="0" border="1" class="tableRanking attract_table">
                    <colgroup>
                        <col width="100px">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                        <col width="">
                    </colgroup>
                    <tbody id="attractive_list">
                        <tr>
                        <th class="">
                                <span class="txt_guide"><img src="/img/txt_guide@2x.png" alt="가이드보기"></span>
                                <!-- 투자의견, 투자매력 가이드 레이어 -->
                                <div class="guide_box hide">
                                    <span class="clse">닫기</span>
                                    <strong class="title">전종목 투자매력도 각 항목 설명</strong>
                                    <ul>
                                        <li>- <strong>배당매력</strong> : 과거의 배당 지급 내역, 시가배당률, 배당성향, 향후 성장 가능성과 지급 여력 등을 종합해 평가합니다.</li>
                                        <li>- <strong>사업독점력</strong> : ROE, 낮은원가율, 영업활동 현금흐름, 연평균 성장률 등을 종합해 평가합니다.</li>
                                        <li>- <strong>재무안전성</strong> : 부채비율, 유동비율, 이자보상배수, 금융비용 등을 종합해 평가합니다.</li>                                        
                                        <li>- <strong>수익성장성</strong> : 순이익 성장률, 자기자본이익률(ROE) 등을 종합해 평가합니다.</li>                                        
                                        <li>- <strong>현금창출력</strong> : 영업활동 현금흐름, 잉여현금흐름, 현금흐름배수(PCR)등을 종합해 평가합니다.</li>                                        
                                    </ul>
                                </div>
                                <!-- //guide_box -->
                            </th>
                            <th class="">종합<?=($sort == 'total') ? '<i></i>' : ''?></th>
                            <th class="">배당<br>매력<?=($sort == 'dividend') ? '<i></i>' : ''?></th>
                            <th class="">사업<br>독점력<?=($sort == 'moat') ? '<i></i>' : ''?></th>
                            <th class="">재무<br>안전성<?=($sort == 'safety') ? '<i></i>' : ''?></th>
                            <th class="">수익<br>성장성<?=($sort == 'growth') ? '<i></i>' : ''?></th>
                            <th class="">현금<br>창출력<?=($sort == 'cashflow') ? '<i></i>' : ''?></th>
                        </tr>
                        <?=$content_html?>
                    </tbody>
                </table>

                <div class="btn_more">
                    <a href="javascript:;" onclick="view_more()"><i></i>더보기</a>
                </div>
                <!-- //btn_more -->

                <script>
                var page = 1;
                var is_loading = false;
                function view_more() {
                    if(is_loading) {
                        return;
                    }
                    is_loading = true;
                    
                    page += 1;

                    var url = '/attractiveness/ajax_get_attractive_list';
                    if(window.location.search.length > 0) { 
                        url += window.location.search + '&page=' + page;
                    } else {
                        url += '?page=' + page;
                    }
                    $.get(url, {}, function(res) {
                        if($.trim(res).length) {
                            $('#attractive_list').append(res);
                        } else {
                            $('.btn_more').hide();
                        }
                        is_loading = false;
                    });
                }
                </script>
                <!--<p class="dataLink">data from <a href="https://www.quandl.com/" target="_blank">Quandl and
                        Sharadar</a>
                </p>-->
            </div> <!-- //sub_mid -->

            <!-- Modal popup -->
            <!-- 전종목 투자매력도 -->
            <div class="setting_pop">
                <div class="bg"></div>
                <div id="setting" class="setting_area">
                    <div class="pop_header">
                        <a href="#setting_anchor" title="로그인 레이어 닫기" class="close"><img src="../img/clse.png" alt="팝업닫기"></a>
                        <h1 class="pop_title">검색</h1>
                    </div>
                    <!-- //pop_header -->                    

                    <div class="pop_con">

                        <form id="search_form" action="/attractiveness/attractive" method="GET">
                            <input type="hidden" name="sort" value="<?=$sort?>" />
                            <table cellspacing="0" border="1" class="tableRanking attract_table">
                                <colgroup>
                                    <col width="65%">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th><span>시가총액</span></th>
                                        <th><span>순이익(연환산)</span></th>
                                    </tr>
                                    <?php 
                                        $idx = 0;
                                        foreach($marketcap_map as $mk => $mv) : 
                                            $netincome_data = array_slice($netincome_map, $idx, 1);
                                    ?>
                                    <tr>
                                        <td>
                                            <input name="marketcap" type="radio" id="amount<?=$idx?>" value="<?=$mk?>" class="input_radio" <?=($mk == $marketcap) ? 'checked' : ''?>>
                                            <label for="amount<?=$idx?>"><?=$mv?></label>
                                        </td>
                                        <?php foreach($netincome_data as $nk => $nv) : ?>
                                        <td>
                                            <input name="netincome" type="radio" id="profit<?=$idx?>" value="<?=$nk?>" class="input_radio" <?=($nk == $netincome) ? 'checked' : ''?>>
                                            <label for="profit<?=$idx?>"><?=$nv?></label>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php $idx++; endforeach; ?>
                                </tbody>
                            </table>

                            <div class="pop_footer">
                            <span class="title">정렬</span>
                                <ul class="sort">
                                    <?php foreach($sort_map as $key => $val) : ?>
                                    <li class="<?=($key == $sort) ? 'active' : ''?>" data-id="<?=$key?>"><a href="javascript:;"><?=$val?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="btnArea edtBtn">
                                    <a href="javascript:;" class="btn btn_save">확인</a>
                                </div>
                            </div>
                        </form>

                    </div>

                    
                </div>
            </div>
            <!-- //setting_pop -->
<script>
$(function() {
    $('ul.sort li').on('click', function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        $('#search_form').find('[name="sort"]').val($(this).data('id'));
    });
    $('.btn_save').on('click', function() {
        $('#search_form').submit();
    });
});
</script>            
