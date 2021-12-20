    <?php
    $active_menu = 'invest';
    $ticker_code = $ticker['tkr_ticker'];
    include_once dirname(__FILE__).'/op_submenu.php';
    ?>

        <?php
        $tabs = array(
            //'손익계산서' => $incomestate_fields,
            //'재무상태표' => $balancesheet_fields,
            //'현금흐름표' => $cashflow_fields,
            '투자지표' => $fininvestindi_fields,
            //'주가지표' => $pricesheet_fields,
        );
        $tab_titles = array(
            //'재무상태표' => $balancesheet_titles,
            //'손익계산서' => $incomestate_titles,
            //'현금흐름표' => $cashflow_titles,
            '투자지표' => $fininvestindi_titles,
            //'주가지표' => $pricesheet_titles,
        );
    if($tab_idx < 0 || $tab_idx >= sizeof($tabs)) {
        $tab_idx = 0;
    }
        ?>

        <div class="tableData">
                <!-- <ul class="competitors txtCalculation">
                    <li><img src="/img/txt/txt_investment.png" alt="투자지표"></h3></li>
                </ul> -->
                <!-- //competitors -->
                <div class="tableTab positionRe"><!-- tableTab 위치고정 class = positionRe -->
                    <div class="tabsArea">
                        <span <?=($dimension=='MRT') ? 'class="active"' : ''?> onclick="manager.setDimension('MRT', this);">연환산</span>
                        <span <?=($dimension=='MRY') ? 'class="active"' : ''?> onclick="manager.setDimension('MRY', this);">연간</span>
                        <span <?=($dimension=='MRQ') ? 'class="active"' : ''?> onclick="manager.setDimension('MRQ', this);">분기별</span>
                        <strong class="info">
                            <img src="/img/icon/info.png" alt="안내" title="하단 투자지표 각 항목 산식 참조" onclick="$(this).next().toggle()">
                            <!-- 연환산 = 최근4분기 합계 말풍선 -->
                            <div class="ly_help small hide"> <!-- 기본 hide로 숨김, 클릭시 class = view 로 변경 -->
                                <p>연환산 = 최근4분기 합계</p>
                                <div class="edge_rgt"></div><!-- edge_lft, edge_cen, edge_rgt -->
                            </div>
                            <!-- //연환산 = 최근4분기 합계 말풍선 -->
                        </strong>
                    </div>
                    <!-- //tabsArea -->
                </div>
                <!-- //tableTab -->

                <div class="tableScroll" id='table_scroll_div'>
                <!-- TABLE SCROLL DIV -->
            <?php


        // tkr_category 가 ADR 인 종목이나 신규상장 등 자료 없는 회사는 전항목 N/A 표출을 위한 플래그 및 배열설정 처리
        $empty_page = false;

        if(sizeof($data) == 0) {
            $empty_page = true;
            $data['&nbsp;'] = array(array());
        }
//echo '<pre>'; print_r($data);
            foreach($tabs as $field_title => $fields) :
            $field_title_map = $tab_titles[$field_title];
            ?>
                    <table cellspacing="0" border="0" class="tableColtype typeScroll tableInvest typeScroll_mouseover" style="display: table;">
                        <caption><img src="/img/txt/txt_investment.png" alt="투자 지표"></caption>
                        <thead>
                            <tr class="fntfmly_num fix_tr"><!-- //th 상단 고정 class = fix_tr -->
                                <th scope="col" style="border-bottom: 2px solid rgb(85, 85, 85); top: 54px;"><span title=""></span></th>
                                <?php $max=0; foreach(array_keys($data) as $yyyymm) : ?>
                                <?php $max++; if($max>41) break;?>
                    <th scope="col" style="border-bottom: 2px solid rgb(85, 85, 85); top: 54px;"><span><?=strlen($yyyymm)>8 ? date('y.m/d', strtotime(str_replace('.','-',$yyyymm))) : ''?></span></th>
                    <?php endforeach; ?>
                    <?=($max==10) ? '<th>&nbsp;</th><th>&nbsp;</th>' : ''?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

            $last_servicecategory = '';
            $line=0;
            foreach($fields as $depth => $key) :
                $title = $field_title_map[$key];
                $line++;
                $depth_num = count(explode('-', $depth));
                $depth = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth_num-1);
                $tr_depth_class = 'depth'.sprintf('%02d', ($depth_num > 2 ? 2 : $depth_num)+1);
                if($last_servicecategory != $field_servicecategory_map[$key]) :
                    $last_servicecategory = $field_servicecategory_map[$key];
            ?>
            <tr class="depth01 trFixed">
                <td class="tdfix"><span><strong><?=$last_servicecategory?></strong></span></td>
                <td class="tdLength" colspan="<?=sizeof($data)?>">
                    <span><strong></strong></span>
                </td>
            </tr>
            <?php
                endif;
            ?>
            <tr class="<?=$tr_depth_class?> trFixed">
                <td class="th_guide"><span><strong><?=$title?></strong></span></td>

                <?php $col=0; foreach($data as $yyyymm => $row) : ?>
                <?php
                    $col++;
                    if($col>41) break;
                    /*
                    특정 필드는 커스텀 하여 뿌리게 요청받았다. 요청 내용은 아래와 같음.

                    "손익계산서-구성비율과 다름

                    >> 아래와 같이 적용 indicator 수정
                    grossmargin → (ratio)gp
                    netmargin → (ratio)netinc
                    ebitdamargin → (ratio)ebitda"

                    소수점 두자리 표시로 통일합니다. 두자리가 0으로 모두 끝난다 하더라도 두자리까지 표시합니다. - In-joong Kim => 김보미 할당

                    19.08.20처리
                    매출채권 회전일수(일)    SF1    receiveturnoverdays    inventory 값 없거나 0 이면 receiveturnoverdays N/A 처리
                    재고자산 회전일수(일)    SF1    inventoryturnoverdays    receivables 값 없거나 0 이면 inventoryturnoverdays N/A 처리
                    SF1    ROIC    sf1 테이블의 invcapavg 가 0보다 작을 경우, roic는 N/A 처리 (투자지표, 경쟁사 등에 적용되는 데이터 일괄 적용)
                    */
                    $dpval = isset($row[$key]) ? $row[$key] : 'N/A';
                    if(in_array($key, array('sf1_grossmargin', 'sf1_netmargin', 'sf1_ebitdamargin', 'sf1_receiveturnoverdays', 'sf1_inventoryturnoverdays', 'sf1_roic'))) {
                        switch($key) {
                            case 'sf1_grossmargin' :
                                $dpval = @number_format(floatval(str_replace(',','',$row['sf1_gp'])) / floatval(str_replace(',','',$row['sf1_revenue']))*100, 2).'%';
                                break;
                            case 'sf1_netmargin' :
                                $dpval = @number_format(floatval(str_replace(',','',$row['sf1_netinc'])) / floatval(str_replace(',','',$row['sf1_revenue']))*100, 2).'%';
                                break;
                            case 'sf1_ebitdamargin' :
                                $dpval = @number_format(floatval(str_replace(',','',$row['sf1_ebitda'])) / floatval(str_replace(',','',$row['sf1_revenue']))*100, 2).'%';
                                break;
                            case 'sf1_receiveturnoverdays' :
                                $dpval = ( !$row['sf1_receivables'] || $row['sf1_receivables'] == 0 ) ? 'N/A' : number_format(365/(floatval(str_replace(',','',$row['sf1_revenue']))/floatval(str_replace(',','',$row['sf1_receivables']))));
                                break;
                            case 'sf1_inventoryturnoverdays' :
                                $dpval = ( !$row['sf1_inventory'] || $row['sf1_inventory'] == 0 ) ? 'N/A' : number_format(365/(floatval(str_replace(',','',$row['sf1_cor']))/floatval(str_replace(',','',$row['sf1_inventory']))));
                                break;
                            case 'sf1_roic' :
                                $dpval = ( $row['sf1_invcapavg'] < 0 ) ? 'N/A' : $row['sf1_roic'];
                                break;
                        }
                    }
/*
                    if( $this->session->userdata('is_login') === false ) {

                        if( $col > 6 ) {
                            $dpval = '<span class="lock"><img src="/img/icon/lock.png" alt="로그인이 필요합니다"></span>';
                        }

                    }
*/
                    if(@is_nan($dpval) || $dpval === 'nan%'){
                        $dpval = 'N/A';
                    }
?>
                <td <?=($dpval=='N/A') ? 'class="na"':''?>>
<?php
                    echo $dpval;
                ?>
                </td>
                <?php endforeach; ?>

            </tr>
                <?php endforeach; ?>

                        </tbody>
                    </table>
            <?php endforeach; ?>

<?php /*
                    <script>
                        $(document).ready(function(){
                            var tableColnum = $('.typeScroll_mouseover th').length;
                            // 테이블 empty td colspan 설정하기.
                            $(".typeScroll_mouseover .depth01 td").hide();
                            $(".typeScroll_mouseover .depth01").each(function() {
                    $('td:first', this).attr("colspan", tableColnum).show();
                            });
                        });
                    </script>
*/ ?>
                <!-- TABLE SCROLL DIV -->

                    <!-- 테이블 안내 툴팁 -->
                    <div class="th_guide_hide">
                        <div class="guide_box">
                            <ul>
                                <li>
                                    <!-- 주당지표 -->
                                </li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>보통주순이익 / 가중평균희석주식수</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>자본총계 / 가중평균주식수</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>주당배당금(달러)</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>매출액 / 가중평균주식수</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>영업활동 현금흐름 / 가중평균주식수</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>잉여현금흐름 / 가중평균주식수</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>
                                    <!-- 가치평가 -->
                                </li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>기말 시가총액 / 최근 4분기 합산 보통주순이익</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>기말 시가총액 / 최근 분기 자본총계</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>기말 시가총액 / 최근 4분기 합산 매출액</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>기말 시가총액 / 최근 4분기 합산 영업활동 현금흐름</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(기말 시가총액+차입금-현금및현금성자산) / (감가상각비+법인세비용+이자비용+순이익)</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>주당배당금 / 기말 주가</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>
                                    <!--수익성-->
                                </li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(매출총이익/매출액)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(영업이익/매출액)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(지배지분 순이익/매출액)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(EBITDA/매출액)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(잉여현금흐름/매출액)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(최근 4분기 합산 보통주순이익/최근 4분기 평균자본)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(최근 4분기 합산 보통주순이익/최근 4분기 평균자산)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(EBIT/최근 4분기 평균투하자본)*100, 투하자본 = 차입금+자산총계-무형자산-현금및현금성자산-유동부채</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>
                                    <!--안정성-->
                                </li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(부채총계/자본총계)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(유동자산/유동부채)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>영업이익 / 이자비용</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(차입금/자산총계)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(이자비용/매출액)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>
                                    <!--효율성-->
                                </li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>(매출액/최근 4분기 평균자산)*100</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>365 / (매출액 / 매출채권및기타채권)</li>
                            </ul>
                        </div>
                        <div class="guide_box">
                            <ul>
                                <li>365 / (매출원가 / 재고자산)</li>
                            </ul>
                        </div>
                    </div>
                    <!-- //th_guide_hide -->

                </div>
                <!-- //tableScroll -->
                <?php //include_once INC_PATH.'/login_guide.php'; ?>
                <?php if(isset($ticker['tkr_currency'])&&$ticker['tkr_currency']!='USD') {?>
                    <p class="tableInfo" style="margin-top: 5px">* 위 재무데이터는 USD로 일괄 조정</p>
                <?php }?>


                <?php if($empty_page) : ?>
                <p class="tableInfo" style="margin-top: 5px">* ADR(미국예탁증권), 신규상장 등으로 분기 데이터 없음</p>

                <?php endif; ?>

                <div class="standard" style="display:none;">
                    <span class="title">기준</span>
                    <div class="select open" style="width:82px;">
                        <span class="ctrl"><span class="arrow"></span></span>
                        <button type="button" class="my_value"><?=$this->sf1_tb_model->getUnitnumText($ticker['tkr_currency'], $country_unitnum_map[$country_unitnum])?></button>
                        <ul class="a_list">
            <?php foreach($country_unitnum_map as $k => $v) :
                    if($ticker['convert_to_usd'] == true && substr($k, 0, 3) != 'us_') {
                        // currency가 USD가 아닌 기업은 'kor_억원' 등 USD 외 단위보기 없애기.
                        continue;
                    }
            ?>
                            <li><a onclick="manager.setCountryUnitnum('<?=$k?>')"><?=$this->sf1_tb_model->getUnitnumText($ticker['tkr_currency'], $v)?></a></li>
            <?php endforeach; ?>
                        </ul>
                    </div>
                    <button class="simple"><i></i>간단히</button>
                </div>
                <!-- //standard -->
            </div>
            <!-- //tableData -->

<script>
var PageManager = function() {
    var _this = this;
    var params = {
        tab_idx : <?=intval($tab_idx)?>,
        country_unitnum : '<?=$country_unitnum?>',
        dimension : '<?=$dimension?>',
        cell_type : '<?=$cell_type?>'
    };
    $('div.tableData table').hide().eq(params.tab_idx).show();

    this.setTabIdx = function(idx) {
        if(idx < 0 || idx >= <?=sizeof($tabs)?>) {
            return;
        }
        params.tab_idx = idx;
        $('div.tableData table').hide().eq(params.tab_idx).show();
    }
    this.setCellType = function(code) {
        params.cell_type = code;
        goUrl();
    }
    this.setDimension = function(code, obj) {
        setFixActive(obj);
        params.dimension = code;
        goUrl();
    }
    this.setCountryUnitnum = function(code, obj) {
        setFixActive(obj);
        params.country_unitnum = code;
        goUrl();
    }

    function setFixActive(obj) {
        $('span', obj.parentNode).removeClass('fix-active');
        $(obj).addClass('fix-active');
    }

    function goUrl() {
        var go_url = '?ajax=Y&'+$.param(params);
        $.post(go_url, function(res) {
            $('#table_scroll_div').html(res);
            _this.setTabIdx(params.tab_idx);
            function tableLength () {
                if ($('.tableColtype').hasClass("typeScroll")) {
                    /*
                    var thLength = $('.globalStock #container .tableData .tableScroll .typeScroll th').length;
                    if (thLength <= 12) {
                        var thWidth = ($('.globalStock #container .tableData .tableScroll').width() + 80 + (thLength * 5)) / thLength;
                        console.log(thWidth);
                        $('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th span').css({
                            'width' : thWidth
                        });
                        $('.globalStock #container .tableData .tableScroll .typeScroll th, .gltable_scroll_divobalStock #container .tableData .tableScroll .typeScroll th:first span').css({
                            'width' : 161
                        })
                    }
                    */
                    var thLength = $('.globalStock #container .tableData .tableScroll .typeScroll th').length;
                    if (thLength <= 7) {
                        var thWidth = ((($('.globalStock #container .tableData .tableScroll').width()) / thLength) + (195 / (thLength - 1)));
                        $('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th span').css({
                            'width' : thWidth
                        });
                        $('.globalStock #container .tableData .tableScroll .typeScroll tr:first th:first, .globalStock #container .tableData .tableScroll .typeScroll tr:first th:first span').css({
                            'width' : 195
                        })
                    }
                }
            }
            tableLength ();
        });

    }
}

var manager = new PageManager();

</script>