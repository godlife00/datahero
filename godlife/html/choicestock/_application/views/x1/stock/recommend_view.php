        <?php 
            $class = 'decrease';
            if($rc_data['ticker']['tkr_rate'] > 0) {
                $class = 'increase';
            }
			if(in_array($rc_data['rc_adjust'], array('U', 'D')) && $rc_data['rc_adjust_price'] > 0) :
				$rc_data['rc_goal_price'] = $rc_data['rc_adjust_price'];
			endif;
        ?>
            <div class="sub_top view">
                <div class="chart_area">
                    <div class="chartData">
                        <h2 class="title" title="<?=$rc_data['ticker']['tkr_name_en']?>"><a href="/<?=X1?>_search/invest_charm/<?=$rc_data['rc_ticker']?>"><?=$rc_data['ticker']['tkr_name']?></a></h2>
                        <a href="/<?=X1?>_search/invest_charm/<?=$rc_data['rc_ticker']?>" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                        <ul class="info">
                            <li class="sum"><span class="eng"><?=$rc_data['rc_ticker']?></span> </li>
                        </ul>
                        <!-- //info -->
                        <ul class="detail">
							<?php if($is_open === true) :?>
                            <li class="num"><span class='sync_price' data-ticker='<?=$rc_data['ticker']['tkr_ticker']?>' data-render="((el, txt, info) => { var tmp = txt.split('.'); return tmp[0]+'.<b>'+tmp[1]+'</b>';})"><?=$this->common->set_pricepoint($rc_data['ticker']['tkr_close'], '1')?></span></li>
                            <li class="per">
                                <span data-ticker='<?=$rc_data['ticker']['tkr_ticker']?>' data-render="((el, txt, info) => { el.removeClass('increase'); el.removeClass('decrease'); var c='decrease'; if(parseFloat(txt) > 0) { c='increase'; info.diff_rate = '+'+info.diff_rate; txt = '+ '+txt; } el.addClass(c); return txt + ' <span class='+c+'>('+info.diff_rate+'<b>%</b>)</span>'; })" class="sync_diff_price <?=$rc_data['ticker']['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$rc_data['ticker']['tkr_diff_str']?> <span class="<?=$class?>">(<?=$this->common->set_pricepoint($rc_data['ticker']['tkr_rate_str'], '2')?>)</span></span>
                            </li>
                            <li class="day"><?php if(isset($ticker['tkr_lastpricedate'])&&$ticker['tkr_lastpricedate']) echo date('y.m/d H:i', strtotime($ticker['tkr_lastpricedate'])).', ';?> USD</li>
							<?php else :?>
                            <li class="num"><?=$this->common->set_pricepoint($rc_data['ticker']['tkr_close'], '1')?></li>
                            <li class="per">
                                <span class="<?=$class?>"><?=$this->common->set_pricepoint($rc_data['ticker']['tkr_diff_str'], '1')?> <span>(<?=$this->common->set_pricepoint($rc_data['ticker']['tkr_rate_str'], '2')?>)</span></span>
                            </li>
                            <li class="day"><?php if(isset($rc_data['ticker']['tkr_lastpricedate'])&&$rc_data['ticker']['tkr_lastpricedate']) echo date('y.m/d', strtotime($rc_data['ticker']['tkr_lastpricedate'])).', ';?> USD</li>
							<?php endif;?>
                        </ul>
                        <div class="detail_data">
                            <table cellspacing="0" border="1" class="tableRanking">
                                <colgroup>
                                    <col width="">
                                    <col width="">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th class="recom"><span>추천가</span><span class="day"><?=date('y.m/d', strtotime($rc_data['rc_display_date']))?></span></th>
                                        <th class="stopLoss"><span>손절가</span></th>
                                        <th class="goal_num"><span>목표가
                                        <?php if($rc_data['rc_endtype'] == 'SUCCESS' || $rc_data['rc_endtype'] == 'SELL') :?>
                                        <span class="day"><?=date('y.m/d', strtotime($rc_data['rc_enddate']))?></span>
                                        <?php endif;?>
                                        </span></th>
                                    </tr>
                                    <tr>
                                        <td class="recom_num"><span><?=$this->common->set_pricepoint($rc_data['rc_recom_price'], '1')?></span></td>
                                        <td class="stopLoss_num"><span><?=$this->common->set_pricepoint($rc_data['rc_giveup_price'], '1')?></span></td>
                                        <td class="goal_num"><span><?=$this->common->set_pricepoint($rc_data['rc_goal_price'], '1')?></span></td>
                                    </tr>
                                </tbody>
                            </table>

                            <!--  수익률(기준가-현재가 수익%) 표시 -->
                            <div class="revenue_box" style="<?=($rc_data['rc_endtype'] != 'SUCCESS' && $rc_data['rc_endtype'] != 'SELL') ? '' : 'display: none;'?>">
                                <span class="title"><i></i> 수익률</span>
                                <span class="percent"><i></i> <?=number_format((($rc_data['ticker']['tkr_close']/$rc_data['rc_recom_price'])-1)*100,2)?><b>%</b></span>
                            </div>
                            <!-- //수익률(기준가-현재가 수익%) 표시 -->

                            <!-- 목표가 달성시 -->
                            <div class="attainment_box" style="<?=($rc_data['rc_endtype'] == 'SUCCESS' || $rc_data['rc_endtype'] == 'SELL') ? '' : 'display: none;'?>">
                                <span class="title"><i></i> <?=($rc_data['rc_endtype'] == 'SELL') ? '매도':'목표가달성'?></span>
                                <span class="percent"><i></i> <?=($rc_data['rc_endtype'] == 'SELL') ? number_format((($rc_data['rc_mid_price']/$rc_data['rc_recom_price'])-1)*100,2):number_format((($rc_data['rc_goal_price']/$rc_data['rc_recom_price'])-1)*100,2)?><b>%</b></span>
                            </div>
                            <!-- //목표가 달성시 -->

                        </div>

                        <!-- //detail -->
                        <div id="top_chart" class="containerArea_1"></div><!-- 종목추천 id = containerArea_1 -->
                        <script>
                        var params = [<?php foreach($chart_key as $val) echo "'".substr($val,5,5)."',";?>]
                        SubRecomViewChart('top_chart', [<?=$chart_value?>], params)
                        </script>

                    </div>
                    <!-- //chartData -->
                </div>
                <!-- //chart_area -->
            </div>
            <!-- //sub_top -->

            <div class="sub_mid">
                <div class="tabsArea">
                    <ul class="tabs tabs_two">
                        <li class="active" rel="tab1">투자포인트</li>
                        <li rel="tab2">투자매력</li>
                    </ul>
                    <div class="tab_container">
                        <!-- 투자포인트 -->
                        <div id="tab1" class="tab_content">
                        <?=str_replace('/webdata/attach_data','https://hero.datahero.co.kr/webdata/attach_data',$rc_data['rc_invest_point'])?>
                            <div class="catalyst">
                                <h4 class="title">주가촉매 이벤트</h4>
                                <div><?=$rc_data['rc_event']?></div>
                            </div>
                        </div>
                        <!-- //투자포인트 -->
                        <!-- 투자매력 -->
                        <div id="tab2" class="tab_content">
                            <?php if($rc_data['rc_use_chart'] == 'YES') : ?>
                            <h4 class="title">종목진단</h4>
                            <a href="/<?=X1?>_search/invest_charm/<?=$rc_data['rc_ticker']?>" class="more"><img src="/img/more_Black.png" alt="더보기"></a>

                            <div id="spider_chart" class="containerSpider_1"></div>
                            <script>SubRecomSpiderChart('spider_chart', [<?=$rc_data['m_biz_dividend_stars']?>,<?=$rc_data['m_biz_growth_stars']?>,<?=$rc_data['m_biz_moat_stars']?>,<?=$rc_data['m_biz_safety_stars']?>,<?=$rc_data['m_biz_cashflow_stars']?>])</script>

                            <!-- 투자매력도 id = containerSpider_1 -->

                            <span class="charm_num">
                                <strong><?=$rc_data['m_biz_total_score']?></strong> / 100
                                <span class="total">투자매력 종합점수</span>
                            </span>
                            <!-- //charm_num -->
                            <?php endif; ?>

                            <div class="data_table">
                                <h4 class="title">투자지표</h4>
                                <a href="/<?=X1?>_search/invest/<?=$rc_data['rc_ticker']?>" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                                <!-- 수익성(연환산) -->
                                <div>
                                    <table cellspacing="0" border="1" class="tableRanking">
                                        <colgroup>
                                            <col width="100%">
                                            <col width="70">
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <th colspan="2" class="th_ft"><span>수익성(연환산)</span><span class="day"><?=date('y.m/d', strtotime($mrt_data['sf1_reportperiod']))?></span></th>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>영업이익률</span></th>
                                                <td class="num"><span><?=(!is_numeric(str_replace('%','',$mrt_data['sf1_opmargin'])) || is_nan($mrt_data['sf1_opmargin']) || is_infinite($mrt_data['sf1_opmargin'])) ? 'N/A' : $this->common->set_pricepoint($mrt_data['sf1_opmargin'], '2')?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>ROA</span></th>
                                                <td class="num"><span><?=$this->common->set_pricepoint($mrt_data['sf1_roa'], '2');?></span></td>

                                            </tr>
                                            <tr>
                                                <th class="th"><span>ROE</span></th>
                                                <td class="num"><span><?=$this->common->set_pricepoint($mrt_data['sf1_roe'], '2');?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>ROIC</span></th>
                                                <td class="num"><span><?=$this->common->set_pricepoint($mrt_data['sf1_roic'], '2');?></span></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <!-- 안전성(최근분기) -->
                                    <table cellspacing="0" border="1" class="tableRanking">
                                        <colgroup>
                                            <col width="100%">
                                            <col width="70">
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <th colspan="2" class="th_ft"><span>안전성(최근분기)</span><span class="day"><?=(!isset($mrq_data['sf1_reportperiod'])||$mrq_data['sf1_reportperiod']=='') ? 'N/A':date('y.m/d', strtotime($mrq_data['sf1_reportperiod']))?></span></th>                                      
                                            </tr>
                                            <tr>
                                                <th class="th"><span>유동비율</span></th>
                                                <td class="num"><span><?=$this->common->set_pricepoint($mrq_data['sf1_currentratio'], '2');?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>차입금 비중</span></th>
                                                <td class="num"><span><?=$this->common->set_pricepoint($mrq_data['sf1_borrowtoassets'], '2');?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>매출채권회전일수</span></th>
                                                <td class="num"><span><?=$mrt_data['sf1_receiveturnoverdays']?><b>일</b></span></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                <?php
                                    //2020.08.26 변경 if(strtoupper($rc_data['ticker']['tkr_category'])=='ADR' || strtoupper($rc_data['ticker']['tkr_category'])=='ADR PRIMARY' || strtoupper($rc_data['ticker']['tkr_category'])=='CANADIAN' || strtoupper($rc_data['ticker']['tkr_category'])=='CANADIAN PRIMARY') {
									if( strstr(strtoupper($rc_data['ticker']['tkr_category']), 'ADR') || strstr(strtoupper($rc_data['ticker']['tkr_category']), 'CANADIAN') ) {
                                
                                        $mry_list = array_slice($mry_list, 0, 2);
                                        $curr = array_shift($mry_list);
                                        $before = array_pop($mry_list);
                                        $sf1_netinc = 'sf1_netinc';
                                    }
                                    else {
                                        if(sizeof($mrt_list)>5) {
                                            $mrt_list = array_slice($mrt_list, 0, 5);
                                        }
                                        $curr = array_shift($mrt_list);
                                        $before = array_pop($mrt_list);
                                        $sf1_netinc = 'sf1_netinccmnusd';
                                    }

                                    /*
                                     매출액 성장률, 순이익 성장률, R&D/매출액 비율 계산시 MRY 데이터로 계산. 
                                     1) 매출액 성장률 = (최근 MRY revenue / (T-1) MRY revenue)-1*100%
                                     2) 순이익 성장률 = (최근 MRY netinc / (T-1) MRY netinc)-1*100% 
                                     3) R&D/매출액 = (최근 MRT rnd / 최근 MRT revenue)*100%
                                    */

                                    //echo '<pre>'; print_r($mrt_list);
                                    //echo '<pre>'; print_r($curr);
                                    /*
                                    첨부한 이미지 파일(예:넷앱 NTAP)로 설명 드리겠습니다. 
                                    손익계산서 MRT 값입니다.
                                    매출액 성장률 = (5,762/6,174)-1*100%= -6.67%
                                    매출액 성장률 = (최근 MRT revenue / (T-4) MRT revenue)-1*100%
                                    순이익 성장률 = (991/335)-1*100% = 195.82%
                                    순이익 성장률 = (최근 MRT netinc / (T-4) 
                                    MRT netinc)-1*100%. (순이익 성장률은 종목분석 본문 중간에 나오는 순이익 성장률 값과 동일하게 나올겁니다)

                                     R&D/매출액 = (832/5,762)*100% = 14.43%
                                    R&D/매출액 = (최근 MRT rnd / 최근 MRT revenue)*100%
                                    //echo '<pre>'; print_r($mrt_list);
                                    */

                                    $rate = 0;
                                    if( $curr[$sf1_netinc] > 0 && $before[$sf1_netinc] < 0 ) {
                                        $rate = 1;
                                        $str_netinc = '흑자전환';
                                    }
                                    else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] < 0 ) {
                                        $str_netinc = '적자지속';
                                    }
                                    else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] > 0 ) {
                                        $str_netinc = '적자전환';
                                    }
                                    else {
                                        $rate = sprintf('%.2f', ($curr[$sf1_netinc] / $before[$sf1_netinc] -1) * 100);
                                        $str_netinc = $rate.'%';
                                    }

                                    $curr['sf1_rnd'] = str_replace(',','',$curr['sf1_rnd']);
                                    $curr['sf1_revenue'] = str_replace(',','',$curr['sf1_revenue']);
                                    $before['sf1_revenue'] = str_replace(',','',$before['sf1_revenue']);
                                    $mrt_revenue = sprintf('%.2f', ($curr['sf1_revenue'] / $before['sf1_revenue'] -1) * 100);
                                    //$mrt_netinc = sprintf('%.2f', ($curr['sf1_netinc'] / $before['sf1_netinc'] -1) * 100);

                                    if($curr['sf1_revenue']==0 || !is_numeric($curr['sf1_revenue'])) {
                                        $mrt_rndratio = 'N/A';
                                    }
                                    else {
                                        $mrt_rndratio = sprintf('%.2f', $curr['sf1_rnd'] / $curr['sf1_revenue'] * 100);
                                    }
                                ?>

                                    <!-- 성장성(전년대비) -->
                                    <table cellspacing="0" border="1" class="tableRanking">
                                        <colgroup>
                                            <col width="100%">
                                            <col width="70">
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <th colspan="2" class="th_ft"><span>성장성(전년대비)</span><span class="day"><?=(!isset($mrq_data['sf1_reportperiod'])||$mrq_data['sf1_reportperiod']=='') ? 'N/A':date('y.m/d', strtotime($mrq_data['sf1_reportperiod']))?></span></th>                                                
                                            </tr>
                                            <tr>
                                                <th class="th"><span>매출액 성장률</span></th>
                                                <td class="num"><span><?=$mrt_revenue?><b>%</b></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>순이익 성장률</span></th>
                                                <td class="num"><span><?=$this->common->set_pricepoint($str_netinc, '2');?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>R&D/매출액</span></th>
                                                <td class="num"><span><?=(!is_numeric($mrt_rndratio) || is_nan($mrt_rndratio) || is_infinite($mrt_rndratio)) ? 'N/A':$mrt_rndratio.'<b>%</b>'?></span></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <!-- 가치평가 -->
                                    <table cellspacing="0" border="1" class="tableRanking">
                                        <colgroup>
                                            <col width="100%">
                                            <col width="70">
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <th colspan="2" class="th_ft"><span>가치평가</span><span class="day"><?=(!isset($mrq_data['sf1_reportperiod'])||$mrq_data['sf1_reportperiod']=='') ? 'N/A':date('y.m/d', strtotime($mrq_data['sf1_reportperiod']))?></span></th>                                                
                                            </tr>
                                            <tr>
                                                <th class="th"><span>PER</span></th>
                                                <td class="num"><span><?=number_format($mrt_data['sf1_pe'], 2)?><b>배</b></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>PBR</span></th>
                                                <td class="num"><span><?=($mrt_data['sf1_pb'] == '' || $mrt_data['sf1_pb']== 'N/A') ? 'N/A' : @number_format($mrt_data['sf1_pb'], 2)?><b>배</b></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>PSR</span></th>
                                                <td class="num"><span><?=number_format($mrt_data['sf1_ps'], 2)?><b>배</b></span></td>
                                            </tr>
                                            <tr>
                                                <th class="th"><span>EV/EBIDTA</span></th>
                                                <td class="num"><span><?=number_format($mrt_data['sf1_evebitda'], 2)?><b>배</b></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- //투자매력 -->

                    </div>
                    <!-- .tab_container -->
                </div>
                <!-- //tabsArea -->
            </div>