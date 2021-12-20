            <?php 
                $class = 'decrease';
                if($an_data['ticker']['tkr_rate'] > 0) {
                    $class = 'increase';
                }
            ?>
            <div class="sub_top view">
                <div class="chart_area">
                    <div class="chartData">
                        <h2 class="title" title="<?=$an_data['ticker']['tkr_name_en']?>"><a href="/search/invest_charm/<?=$an_data['an_ticker']?>"><?=$an_data['ticker']['tkr_name']?></a></h2>
                        <a href="/search/invest_charm/<?=$an_data['an_ticker']?>" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>
                        <ul class="info">
                            <li class="sum"><span class="eng"><?=$an_data['an_ticker']?></span> </li>
                            <li class="day"><?php if(isset($an_data['ticker']['tkr_lastpricedate'])&&$an_data['ticker']['tkr_lastpricedate']) echo date('y.m/d', strtotime($an_data['ticker']['tkr_lastpricedate'])).', ';?> USD</li>
                        </ul>
                        <!-- //info -->
                        <ul class="detail">
                            <li class="num <?=$class?>"><?=$an_data['ticker']['tkr_close']?></li>
                            <li class="per">
                                <span class="<?=$class?>"><?=$an_data['ticker']['tkr_diff_str']?> (<?=$an_data['ticker']['tkr_rate_str']?>)</span>
                                <!-- increase 증가, decrease 감소 -->
                            </li>
                        </ul>
                        <!-- //detail -->
                    </div>
                    <!-- //chartData -->

                    <div class="topbtm_area">
                        <div class="box opinion_left">
                            <span class="invest">애널리스트 컨센서스</span>
                            <span class="recom <?=$this->mri_tb_model->getInvestOpinionByStar($an_data['an_opinion'], $divide=2)?>"><?=$this->mri_tb_model->getInvestOpinionByStar($an_data['an_opinion'])?></span>
                            <!-- 매수 : class = buying, 매도 : class = sell -->
                        </div>
                        <div class="box opinion_c">
                            <dl>
                                <dt class="charm">투자매력점수</dt>                                
                                <dd class="charm_num"> <strong><?=$an_data['m_biz_total_score']?></strong>
                                    <span class="percent">상위<?=$an_data['total_rank_rate'];?>%</span>
                                </dd>
                            </dl>
                        </div>
                        <div class="box opinion_right">
                            <dl>
                            <span class="txt_guide"><img src="/img/txt_guide@2x.png" alt="가이드보기"></span>
                                <!-- 투자의견, 투자매력 가이드 레이어 -->
                                <div class="guide_box hide">
                                    <span class="clse">닫기</span>
                                    <strong class="title">전종목 투자매력도 각 항목 설명</strong>
                                    <ul>
                                        <li><strong>애널리스트 컨센서스</strong>미국 증권사 애널리스트들이 제시한 투자의견 컨센서스(평균)를 표시합니다. 최소 10개 이상 증권사에서 투자의견을 제시한 종목만 제공합니다. 투자의견 컨센서스는 강력매수, 매수, 보유, 매도, 강력매도 5단계로 제시합니다.</li>
                                        <li><strong>투자매력 점수</strong>빅 데이터 전문 테크핀 기업인 (주)데이터히어로가 개발한 스파이더(SPIDER) 알고리즘에 따라 투자매력을 제시합니다. 기업의 투자매력을 판단하는 22개 요인(Factor) 분석에 따라 
                                            배당 매력, 사업 독점력, 재무안전성, 수익성장성, 현금창출력을 평가하고 이를 종합해 투자매력 점수와 순위를 제공합니다.
                                        </li>
                                        <li><strong>밸류에이션</strong>기업의 사업모델과 특성을 분석해 해당 기업에 최적화된 밸류에이션 알고리즘을 적용해 적정주가를 계산합니다. 이를 현재 주가와 비교해 저평가, 적정가, 고평가 3단계로 제시합니다.</li>                                        
                                    </ul>
                                </div>
                                <!-- //guide_box -->
                                <dt class="value">밸류에이션</dt>
                                <dd class="just">
                                    <figure class="highcharts-figure">
                                        <div id="value_chart" class="contaanalysisview1"></div>
                                        <!-- 종목분석 id = contaanalysisview1 -->
                                        <script>SubAnalyGaugeChart3('value_chart', [<?=$an_data['expected_star'];?>]);</script>
                                        <span class="proper"><?=$this->common->get_valuation_stars_text($an_data['expected_star'])?></span>
                                    </figure>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <!-- //right_area -->

                    <div id="analy_chart" class="contaanalysisview1"></div>
					<script>
					var params = [<?php foreach($close_chart_key as $val) echo "'".substr($val,5,5)."',";?>]
					SubRecomViewChart('analy_chart', [<?=$close_chart_value?>], params);
					</script>
                    <!-- 종목분석 id = contaanalysisview1 -->

                </div>
                <!-- //chart_area -->

            </div>

            <!-- //sub_top -->

            <div class="sub_mid view">

                <div class="guide_area">
                    <strong class="title">기업분석</strong>
                    <span class="day"><?=date('y. m/d', strtotime($an_data['an_display_date']))?></span>
                    <div><?=$an_data['an_content']?></div>
                </div>
                <!-- //guide_area -->

                <div class="performance">
                    <h4 class="title">최근실적</h4>
                    <span class="unit">* 기준 : <?=date('y.m/d', strtotime($mrt_data['sf1_reportperiod']))?>, <?=$ticker_currency;?>, 연환산</span>
                    <table cellspacing="0" border="1" class="tableRanking">
                        <colgroup>
                            <col width="">
                            <col width="">
                            <col width="">
                            <col width="">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class=""><span>매출액</span></th>
                                <th class=""><span>영업이익</span></th>
                                <th class=""><span>순이익</span></th>
                                <th class=""><span>전년동기비</span></th>
                            </tr>
                            <tr>
                                <td class=""><span><?=$mrt_data['sf1_revenueusd']?></span></td>
                                <td class=""><span><?=$mrt_data['sf1_opinc']?></span></td>
                                <td class=""><span><?=$mrt_data['sf1_netinc']?></span></td>
                                <?php

									//2020.08.26 변경 if(strtoupper($an_data['ticker']['tkr_category'])=='ADR' || strtoupper($an_data['ticker']['tkr_category'])=='ADR PRIMARY' || strtoupper($an_data['ticker']['tkr_category'])=='CANADIAN' || strtoupper($an_data['ticker']['tkr_category'])=='CANADIAN PRIMARY') {
									if( strstr(strtoupper($an_data['ticker']['tkr_category']), 'ADR') || strstr(strtoupper($an_data['ticker']['tkr_category']), 'CANADIAN') ) {
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
									echo '<pre>'; print_r($mrt_list);
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
                                <td class="<?=$rate > 0 ? 'increase' : 'decrease'?>"><span><?=$str_netinc;?></span></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <!-- //performance -->

                <div class="data_table">
                    <h4 class="title">투자지표</h4>
                    <a href="/search/invest/<?=$an_data['an_ticker']?>" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>
                    <div>
                        <!-- 수익성(연환산) -->
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
                                    <td class="num"><span><?=(!is_numeric(str_replace('%','',$mrt_data['sf1_opmargin'])) || is_nan($mrt_data['sf1_opmargin']) || is_infinite($mrt_data['sf1_opmargin'])) ? 'N/A':$mrt_data['sf1_opmargin']?></span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>ROA</span></th>
                                    <td class="num"><span><?=$mrt_data['sf1_roa']?></span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>ROE</span></th>
                                    <td class="num"><span><?=$mrt_data['sf1_roe']?></span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>ROIC</span></th>
                                    <td class="num"><span><?=$mrt_data['sf1_roic']?></span></td>
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
                                    <th colspan="2" class="th_ft"><span>안전성(최근분기)</span><span class="day"><?=date('y.m/d', strtotime($mrq_data['sf1_reportperiod']))?></span></th>                                               
                                </tr>
                                <tr>
                                    <th class="th"><span>유동비율</span></th>
                                    <td class="num"><span><?=$mrq_data['sf1_currentratio']?></span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>차입금 비중</span></th>
                                    <td class="num"><span><?=$mrq_data['sf1_borrowtoassets']?></span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>매출채권회전일수</span></th>
                                    <td class="num"><span><?=$mrt_data['sf1_receiveturnoverdays']?>일</span></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- 성장성(전년대비) -->
                        <table cellspacing="0" border="1" class="tableRanking">
                            <colgroup>
                                <col width="100%">
                                <col width="70">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th colspan="2" class="th_ft"><span>성장성(전년대비)</span><span class="day"><?=(!isset($orig_mrq_data['sf1_reportperiod'])||$orig_mrq_data['sf1_reportperiod']=='') ? date('y.m/d', strtotime($mrq_data['sf1_reportperiod'])):date('y.m/d', strtotime($orig_mrq_data['sf1_reportperiod']))?></span></th>                                      
                                </tr>
                                <tr>
                                    <th class="th"><span>매출액 성장률</span></th>
                                    <!--<td class="num"><span><?=$orig_mrq_data['sf1_revenueusd'] == 'N/A' ?: number_format($orig_mrq_data['sf1_revenueusd']/1000000, 2)?>%</span></td>-->
                                    <td class="num"><span><?=$mrt_revenue;?>%</span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>순이익 성장률</span></th>
                                    <!--<td class="num"><span><?=$orig_mrq_data['sf1_netinc'] == 'N/A' ?: number_format($orig_mrq_data['sf1_netinc']/1000000, 2)?>%</span></td>-->
                                    <td class="num"><span><?=$str_netinc;?></span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>R&D/매출액</span></th>
                                    <!--<td class="num"><span><?=number_format($orig_mrq_data['sf1_rndratio']*100, 2)?>%</span></td>-->
                                    <td class="num"><span><?=(!is_numeric($mrt_rndratio) || is_nan($mrt_rndratio) || is_infinite($mrt_rndratio)) ? 'N/A':$mrt_rndratio.'%'?></span></td>
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
                                    <th colspan="2" class="th_ft"><span>가치평가</span><span class="day"><?=date('y.m/d', strtotime($mrt_data['sf1_reportperiod']))?></span></th>                                                  
                                </tr>
                                <tr>
                                    <th class="th"><span>PER</span></th>
                                    <td class="num"><span><?=number_format($mrt_data['sf1_pe'], 2)?>배</span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>PBR</span></th>
                                    <td class="num"><span><?=($mrt_data['sf1_pb'] == '' || $mrt_data['sf1_pb']== 'N/A') ? 'N/A' : @number_format($mrt_data['sf1_pb'], 2)?>배</span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>PSR</span></th>
                                    <td class="num"><span><?=number_format($mrt_data['sf1_ps'], 2)?>배</span></td>
                                </tr>
                                <tr>
                                    <th class="th"><span>EV/EBIDTA</span></th>
                                    <td class="num"><span><?=number_format($mrt_data['sf1_evebitda'], 2)?>배</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div> <!-- //sub_mid -->
