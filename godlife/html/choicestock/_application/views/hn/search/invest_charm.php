                <?=$ticker_header?>
<?php if($sec_ticker=='' && $pri_ticker=='') {?>
                <div class="chart_area diagnosis">
                    <div class="chartData">
                        <dl>
                            <dt class="charm">투자매력
                                <span class="txt_guide"><img src="/img/txt_guide.png" alt="가이드보기"></span>
                                <div class="guide_box">
                                    <span class="clse">닫기</span>
                                    <strong class="title">투자매력 점수는 높을수록 좋고, 우량 기업 여부를 판단하는데 유용합니다. 우량 기업 이상의 점수를 받고, 저평가 (적정주가에서 확인)된 기업을 매수 후보로 검토하면 좋습니다.</strong>
                                    <ul>
                                        <li>- <strong>81~100점</strong> : 초우량 기업</li>
                                        <li>- <strong>66~80점</strong> : 우량 기업</li>
                                        <li>- <strong>51~65점</strong> : 보통 기업</li>
                                        <li>- <strong>31~50점</strong> : 주의 기업</li>
                                        <li>- <strong>0~30점</strong> : 위험 기업</li>
                                    </ul>
                                </div>
                            </dt>
                            </dt>
                            <dd class="charm_num"><?=$mri_data['m_biz_total_score']?></dd>
                            <?php if($pre_mriscore>0) :?>
                            <?php $score_diff = $mri_data['m_biz_total_score']-$pre_mriscore;?>
                            <dd class="charm">전월대비 <strong><?=($score_diff>0) ? '+'.$score_diff : $score_diff?></strong></dd>
                            <?php endif;?>
                        </dl>
                    </div>
                    <!-- //chartData -->
                    <div class="difference">
                        <p class="dt">전체 <strong><?=number_format($high_count+1);?></strong>위, 상위 <strong><?=$total_rank_rate;?></strong>%</p>
                        <span class="dd">(<?=number_format($sp_totalcount);?>개 평가기업 중)</span>
                        <p class="dt">동일업종 <?=$industry_high_count+1?>위, 상위 <?=$industry_rank_rate;?>%</p>
                        <span class="dd">(업종 <?=$ticker['tkr_industry']?>)</span>
                    </div>
                    <!-- //difference -->
                    <div class="data_attainment">
                        <ul>
                            <li>PER<span><?=number_format($daily_data['dly_pe'], 2)?><b>배</b></span></li>
                            <li>PBR<span><?=$last_mrt['sf1_equity'] > 0 ? number_format($daily_data['dly_pb'], 2).'<b>배</b>' : 'N/A'?></span></li>
                            <li>ROE<span><?=$this->common->set_pricepoint($mrt_data['sf1_roe'], '2')?></span></li>
                            <li>DY<span><?=$this->common->set_pricepoint($mry_data['sf1_divyield'], '2')?></span></li>
                        </ul>                                                
                    </div>
                </div>
                <?php if($fairvalue_rate >= -5) :?>
                    <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                    <div class="value_chart">
                        <div class="chart_analysis">
                            <!--<div class="arrow_box btm" style="left: <?=$fairvalue_rate?>%;">
                                <span>현재가</span>
                                <strong><?=$this->common->set_pricepoint($ticker['tkr_close'], '1');?></strong>
                            </div>-->

                            <div class="arrow_box btm" style="left: <?=$fairvalue_rate?>%;">
                                <span>현재가</span>
                                <?php if($is_open === true) :?>
                                <span class='sync_price' data-ticker='<?=$ticker['tkr_ticker']?>' data-render="((el, txt, info) => { var tmp = txt.split('.'); return tmp[0]+'.<b>'+tmp[1]+'</b>';})"><strong><?=$this->common->set_pricepoint($ticker['tkr_close'], '1');?></strong></span>
                                <?php else :?>
                                <strong><?=$this->common->set_pricepoint($ticker['tkr_close'], '1');?></strong>
                                <?php endif;?>
                            </div>

                            <div class="line">
                                <div class="bg_line"></div>
                                <span class="i_graph">
                                    <span class="g_bar"><span class="g_action" style="left: <?=$fairvalue_rate?>%;"></span></span>
                                </span>
                                <ul class="evaluation">
                                    <li>매우<br>저평가</li>
                                    <li>저평가</li>
                                    <li>
                                        <div class="arrow_box top">
                                            <span>적정가</span>
                                            <strong><?=$this->common->set_pricepoint($mri_data['m_v_fairvalue3'], '1');?></strong>
                                        </div>
                                    </li>
                                    <li>고평가</li>
                                    <li>매우<br>고평가</li>
                                </ul>
                                <!-- //evaluation -->
                            </div>
                        </div>
                    </div>
                    <?php else :?>
                    <div class="prm_value_chart">
                        <a href="javascript:fnSinChung();" class="btn_free"><img src="/img/prm_value_chart_mijimi.png" alt="초이스스탁US 프리미엄 2주 무료이용"></a>                    
                    </div>
                    <?php endif;?>

                    <div class="evaluation_data">
                        <span class="txt_guide"><img src="/img/txt_guide.png" alt="가이드보기"></span>
                        <div class="guide_box">
                            <span class="clse">닫기</span>                                    
                            <ul>
                                <li>적정주가는 기업의 과거 실적과 이익 성장 유지 가능성 및 향후 전망을 반영해 계산합니다. 투자매력 점수가 우량 기업 이상이고, 현재가가 저평가 상태에 있는 기업에 투자하는 것이 좋습니다.<br>
                                    <br>
                                    적극 매수 : 현재가 &#60; 매우 저평가<br>
                                    매수 : 매우 저평가 &#60; 현재가&#60; 적정가<br>
                                    보유 : 저평가 &#60; 현재가 &#60; 고평가<br>
                                    매도 : 고평가 &#60; 현재가
                                </li>
                            </ul>
                        </div>
                        <table cellspacing="0" border="1" class="tableRanking evaluation_table">
                            <colgroup>
                                <col width="">
                                <col width="">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th class="title">
                                        <span>저평가</span>
                                    </th>
                                    <td>
                                        <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                                        <span>< <?=$this->common->set_pricepoint($mri_data['m_v_fairvalue4'], '1');?></span>
                                        <?php else :?>
                                        <span>< XXX.<b>xx</b></span>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="title">
                                        <span>매우저평가</span>
                                    </th>
                                    <td>
                                        <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                                        <span>< <?=$this->common->set_pricepoint($mri_data['m_v_fairvalue5'], '1');?></span>
                                        <?php else :?>
                                        <span>< XXX.<b>xx</b></span>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellspacing="0" border="1" class="tableRanking evaluation_table">
                            <colgroup>
                                <col width="">
                                <col width="">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th class="title">
                                        <span>고평가</span>
                                    </th>
                                    <td>
                                        <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                                        <span>> <?=$this->common->set_pricepoint($mri_data['m_v_fairvalue2'], '1');?></span>
                                        <?php else :?>
                                        <span>> XXX.<b>xx</b></span>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="title">
                                        <span>매우고평가</span>
                                    </th>
                                    <td>
                                        <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                                        <span>> <?=$this->common->set_pricepoint($mri_data['m_v_fairvalue1'], '1');?></span>
                                        <?php else :?>
                                        <span>> XXX.<b>xx</b></span>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- //evaluation_data -->
                <?php else :?>
                    <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                    <div class="no_value">
                        <img src="/img/prm_value_chart2.png" alt="밸류에이션에 필요한 데이터가 충분하지 않아 적정주가를 제시하지 않습니다.">
                    </div>
                    <?php else :?>
                    <div class="prm_value_chart">
                        <a href="javascript:fnSinChung();" class="btn_free"><img src="/img/prm_value_chart_mijimi.png" alt="초이스스탁US 프리미엄 2주 무료이용"></a>                    
                    </div>
                    <?php endif;?>
                <?php endif;?>

                <div class="investCharm_area">
                    <div class="chart_sum">
                        <div class="summary small">
                            <!-- div 사이즈는 big, small  -->
                            <div class="view_box">
                                <p><?=nl2br($ticker['tkr_description'])?></p>
                                <ul class="etc">
                                    <li class="industry"> <span>산업</span><?=$ticker['tkr_industry']?></li>
                                    <?php
                                        $ticker['tkr_companysite'] = str_replace('http://','',$ticker['tkr_companysite']);
                                        $ticker['tkr_companysite'] = str_replace('https://','',$ticker['tkr_companysite']);
                                    ?>
                                    <li class="home"></span><span>홈</span><a href="http://<?=$ticker['tkr_companysite']?>" target="_blank"><?=$ticker['tkr_companysite']?></a></li>
                                    <li class="link"><span>sec전자공시</span> <a href="<?=$api_ticker['tkr_secfilings']?>" target="_blank">바로가기</a></li>
                                </ul>
                            </div>
                            <!-- <span class="close show">펼치기 <i></i></span> -->
                        </div>
                    </div> <!-- //chart_sum -->

                    <div class="tab_diagnosis_area">
                        <div id="charm_top_spider_chart" class="containercharm1"></div>
                        <script>SubRecomSpiderChart('charm_top_spider_chart', [<?=$mri_data['m_biz_dividend_stars']?>, <?=$mri_data['m_biz_moat_stars']?>, <?=$mri_data['m_biz_cashflow_stars']?>, <?=$mri_data['m_biz_growth_stars']?>, <?=$mri_data['m_biz_safety_stars']?>])</script>

                        <span class="charm_num">
                            <strong><?=$mri_data['m_biz_total_score']?></strong> / 100
                            <span class="total">투자매력 종합점수</span>
                        </span>
                        <!-- //charm_num -->

                        <table cellspacing="0" border="1" class="tableRanking table_alloca">
                            <colgroup>
                                <col width="100px">
                                <col width="">
                                <col width="">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td class="title">
                                        <span>배당매력</span>
                                    </td>
                                    <td>
                                        <div class="star_area">
                                            <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($mri_data['m_biz_dividend_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$mri_data['m_biz_dividend_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </td>
                                    <td class="num">
                                        <?php if($part_page!='et') :?><a href="/<?=HN?>_attractiveness/attractive?sort=dividend&netincome=all&marketcap=over100billion"><?php endif;?><span>상위 <?=$dividend_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">
                                        <span>사업독점력</span>
                                    </td>
                                    <td>
                                        <div class="star_area">
                                            <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($mri_data['m_biz_moat_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$mri_data['m_biz_moat_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </td>
                                    <td class="num">
                                        <?php if($part_page!='et') :?><a href="/<?=HN?>_attractiveness/attractive?sort=moat&netincome=all&marketcap=over100billion"><?php endif;?><span>상위 <?=$moat_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">
                                        <span>재무안전성</span>
                                    </td>
                                    <td>
                                        <div class="star_area">
                                            <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($mri_data['m_biz_safety_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$mri_data['m_biz_safety_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </td>
                                    <td class="num">
                                <?php if($part_page!='et') :?><a href="/<?=HN?>_attractiveness/attractive?sort=safety&netincome=all&marketcap=over100billion"><?php endif;?><span>상위 <?=$safety_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">
                                        <span>수익성장성</span>
                                    </td>
                                    <td>
                                        <div class="star_area">
                                            <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($mri_data['m_biz_growth_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$mri_data['m_biz_growth_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </td>
                                    <td class="num">
                                        <?php if($part_page!='et') :?><a href="/<?=HN?>_attractiveness/attractive?sort=growth&netincome=all&marketcap=over100billion"><?php endif;?><span>상위 <?=$growth_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">
                                        <span>현금창출력</span>
                                    </td>
                                    <td>
                                        <div class="star_area">
                                            <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($mri_data['m_biz_cashflow_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$mri_data['m_biz_cashflow_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </td>
                                    <td class="num">
                                        <?php if($part_page!='et') :?><a href="/<?=HN?>_attractiveness/attractive?sort=cashflow&netincome=all&marketcap=over100billion"><?php endif;?><span>상위 <?=$cashflow_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        

						<?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                        <div class="hiding_box">
                            <div class="alloca_star">
                                <div class="alloca_box">
                                    <div class="small_star">
                                        <h2 class="title">배당매력</h2>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($mri_data['m_biz_dividend_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$mri_data['m_biz_dividend_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">업종</span>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($indavg_scores['m_biz_dividend_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$indavg_scores['m_biz_dividend_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </div>
                                    <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">전체</span>
                                        <span>
                                            <div class="star_area">
                                                <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($avg_scores['m_biz_dividend_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$avg_scores['m_biz_dividend_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <!-- //star_area -->
                                        </span>
                                    </div> <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <p class="txt"><?=$mri_data['dividend'];?></p>

                                <div class="chart_box">
                                    <?php
                                        //시가배당률
                                        if($mri_data['m_d_divyield']=='') $mri_data['m_d_divyield']=0; else $mri_data['m_d_divyield'] = round($mri_data['m_d_divyield']*100, 3);
                                        if($indavg_scores['m_d_divyield']=='') $indavg_scores['m_d_divyield']=0; else $indavg_scores['m_d_divyield'] = round($indavg_scores['m_d_divyield']*100, 3);
                                        if($avg_scores['m_d_divyield']=='') $avg_scores['m_d_divyield']=0; else $avg_scores['m_d_divyield'] = round($avg_scores['m_d_divyield']*100, 2);
                                        
                                        //배당성향
                                        if($mri_data['m_d_poratio']=='') $mri_data['m_d_poratio']=0; 
                                        if($indavg_scores['m_d_poratio']=='') $indavg_scores['m_d_poratio']=0;
                                        if($avg_scores['m_d_poratio']=='') $avg_scores['m_d_poratio']=0; 
                                
                                        //순이익성장률(%)
                                        if($mri_data['m_d_epsgr2']=='') $mri_data['m_d_epsgr2']=0; 
                                        if($indavg_scores['m_d_epsgr2']=='') $indavg_scores['m_d_epsgr2']=0;
                                        if($avg_scores['m_d_epsgr2']=='') $avg_scores['m_d_epsgr2']=0;
                                    
                                        //잉여현금성장률(%)
                                        if($mri_data['m_d_fcfgr']=='') $mri_data['m_d_fcfgr']=0;
                                        if($indavg_scores['m_d_fcfgr']=='') $indavg_scores['m_d_fcfgr']=0;
                                        if($avg_scores['m_d_fcfgr']=='') $avg_scores['m_d_fcfgr']=0;

                                        //최근5년 배당금(달러)
                                        if($mri_data['m_d_dps1']=='') $mri_data['m_d_dps1']=0; 
                                        if($mri_data['m_d_dps2']=='') $mri_data['m_d_dps2']=0; 
                                        if($mri_data['m_d_dps3']=='') $mri_data['m_d_dps3']=0; 
                                        if($mri_data['m_d_dps4']=='') $mri_data['m_d_dps4']=0; 
                                        if($mri_data['m_d_dps5']=='') $mri_data['m_d_dps5']=0; 

                                        //2020.08.26 변경 if(strtoupper($ticker['tkr_category'])=='ADR' || strtoupper($ticker['tkr_category'])=='ADR PRIMARY' || strtoupper($ticker['tkr_category'])=='CANADIAN' || strtoupper($ticker['tkr_category'])=='CANADIAN PRIMARY') {
                                        if( strstr(strtoupper($ticker['tkr_category']), 'ADR') || strstr(strtoupper($ticker['tkr_category']), 'CANADIAN') ) {
                                        
                                            $last_mry_list_do = array();
                                            $last_mry_list_do = array_slice($last_mry_list, 0, 2);
                                            $curr = array_shift($last_mry_list_do);
                                            $before = array_pop($last_mry_list_do);
                                            $sf1_netinc = 'sf1_netinc';
                                        }
                                        else {
                                            $last_mry_list_do = array();
                                            $last_mry_list_do = $last_mrt_list;
                                            if(sizeof($last_mry_list_do)>5) {
                                                $last_mry_list_do = array_slice($last_mry_list_do, 0, 5);
                                            }
                                            $curr = array_shift($last_mry_list_do);
                                            $before = array_pop($last_mry_list_do);
                                            $sf1_netinc = 'sf1_netinccmnusd';
                                        }
                                        
                                        $epsgr = 0;
                                        if( $curr[$sf1_netinc] > 0 && $before[$sf1_netinc] < 0 ) {
                                            $epsgr = 1;
                                            $str_netinc = '흑자전환';
                                        }
                                        else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] < 0 ) {
                                            $epsgr = 1;
                                            $str_netinc = '적자지속';
                                        }
                                        else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] > 0 ) {
                                            $epsgr = 1;
                                            $str_netinc = '적자전환';
                                        }

                                        if($epsgr) {
                                            $mri_data['m_d_epsgr2']=0;
                                            //$indavg_scores['m_g_epsgr']=0;
                                            //$avg_scores['m_g_epsgr']=0;
                                        }
                                    ?>
                                    <div id="containeralloca_star1_1" class="containeralloca_star1 wd3"></div>
                                    <script>
                                    var chart_title = ['시가배당률(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_d_divyield']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_d_divyield']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_d_divyield']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star1_1', chart_value, chart_title);
                                    </script>

                                    <!-- 종목검사 종목진단 class = containeralloca_star1  -->
                                    <div id="containeralloca_star1_2" class="containeralloca_star1 wd3"></div>
                                    <script>
                                    var chart_title = ['배당성향(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_d_poratio']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_d_poratio']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_d_poratio']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star1_2', chart_value, chart_title);
                                    </script>


                                    <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>

                                    <div id="containeralloca_star1_3" class="containeralloca_star1 wd3"></div>
                                    <script>
                                    var chart_title = ['순이익성장률(%)'];
                                    var ex_title = '<?=$str_netinc;?>';
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_d_epsgr2']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_d_epsgr2']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_d_epsgr2']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star1_3', chart_value, chart_title, ex_title);
                                    </script>

                                    <div id="containeralloca_star1_4" class="containeralloca_star1 wd3"></div>
                                    <script>
                                    var chart_title = ['잉여현금성장률(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_d_fcfgr']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_d_fcfgr']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_d_fcfgr']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star1_4', chart_value, chart_title);
                                    </script>

                                    <div id="containeralloca_star1_5" class="containeralloca_star1 wd3"></div>
                                    <script>
                                    var chart_title = ['최근 5년 배당금(달러)'];
                                    var chart_value = [
                                        {'name': '<?=substr($mri_data['m_d_dps_year5'],0,4);?>', 'data': [<?=$mri_data['m_d_dps5'];?>]},
                                        {'name': '<?=substr($mri_data['m_d_dps_year4'],0,4);?>', 'data': [<?=$mri_data['m_d_dps4'];?>]},
                                        {'name': '<?=substr($mri_data['m_d_dps_year3'],0,4);?>', 'data': [<?=$mri_data['m_d_dps3'];?>]},
                                        {'name': '<?=substr($mri_data['m_d_dps_year2'],0,4);?>', 'data': [<?=$mri_data['m_d_dps2'];?>]},
                                        {'name': '<?=substr($mri_data['m_d_dps_year1'],0,4);?>', 'data': [<?=$mri_data['m_d_dps1'];?>]}

                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star1_5', chart_value, chart_title);
                                    </script>
                                    <?php endif;?>
                                    <ul class="chart_legend">
                                        <li><span><i></i>종목</span></li>
                                        <li><span><i></i>업종평균</span></li>
                                        <li><span><i></i>전체평균</span></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- //alloca_star -->

                            <?php if($this->session->userdata('is_paid')===FALSE && $open_ticker===FALSE) :?>
                            <?php //include_once HN_INC_PATH.'/premium_banner.php'; ?>
                            <?php endif;?>
                            
                            <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                            <div class="alloca_star">
                                <div class="alloca_box">
                                    <div class="small_star">
                                        <h2 class="title">사업독점력</h2>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($mri_data['m_biz_moat_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$mri_data['m_biz_moat_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">동일업종 평균</span>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($indavg_scores['m_biz_moat_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$indavg_scores['m_biz_moat_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </div>
                                    <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">전체기업 평균</span>
                                        <span>
                                            <div class="star_area">
                                                <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($avg_scores['m_biz_moat_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$avg_scores['m_biz_moat_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <!-- //star_area -->
                                        </span>
                                    </div> <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <p class="txt"><?=$mri_data['moat'];?></p>

                                <?php
                                    //5년평균 ROE(%)
                                    if($mri_data['m_m_roe']=='') $mri_data['m_m_roe']=0; //else $mri_data['m_m_roe'] = round($mri_data['m_m_roe']*100, 2);
                                    if($indavg_scores['m_m_roe']=='') $indavg_scores['m_m_roe']=0; //else $indavg_scores['m_m_roe'] = round($indavg_scores['m_m_roe']*100, 2);
                                    if($avg_scores['m_m_roe']=='') $avg_scores['m_m_roe']=0; //else $avg_scores['m_m_roe'] = round($avg_scores['m_m_roe']*100, 2);

                                    //5년평균 영업이익률(%)
                                    if($mri_data['m_m_opmargin']=='') $mri_data['m_m_opmargin']=0; //else $mri_data['m_m_opmargin'] = round($mri_data['m_m_opmargin']*100, 2);
                                    if($indavg_scores['m_m_opmargin']=='') $indavg_scores['m_m_opmargin']=0; //else $indavg_scores['m_m_opmargin'] = round($indavg_scores['m_m_opmargin']*100, 2);
                                    if($avg_scores['m_m_opmargin']=='') $avg_scores['m_m_opmargin']=0; //else $avg_scores['m_m_opmargin'] = round($avg_scores['m_m_opmargin']*100, 2);

                                    if($is_financial) {
                                        //5년 평균 자산 성장률(%)
                                        if($mri_data['m_m_assetsgr']=='') $mri_data['m_m_assetsgr']=0; 
                                        if($indavg_scores['m_m_assetsgr']=='') $indavg_scores['m_m_assetsgr']=0; 
                                        if($avg_scores['m_m_assetsgr']=='') $avg_scores['m_m_assetsgr']=0; 
                                    }
                                    else {
                                        //5년평균 매출액성장률(%)
                                        if($mri_data['m_m_revenuegr']=='') $mri_data['m_m_revenuegr']=0; //else $mri_data['m_m_revenuegr'] = round($mri_data['m_m_revenuegr']*100, 2);
                                        if($indavg_scores['m_m_revenuegr']=='') $indavg_scores['m_m_revenuegr']=0; //else $indavg_scores['m_m_revenuegr'] = round($indavg_scores['m_m_revenuegr']*100, 2);
                                        if($avg_scores['m_m_revenuegr']=='') $avg_scores['m_m_revenuegr']=0; //else $avg_scores['m_m_revenuegr'] = round($avg_scores['m_m_revenuegr']*100, 2);
                                    }
                                ?>
                                <div class="chart_box">
                                    <?php if($is_financial){?>
                                    <div id="containeralloca_star2_1" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['5년평균 ROE(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_m_roe']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_m_roe']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_m_roe']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star2_1', chart_value, chart_title);</script>

                                    <div id="containeralloca_star2_2" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['5년평균 영업이익률(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_m_opmargin']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_m_opmargin']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_m_opmargin']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star2_2', chart_value, chart_title);</script>

                                    <div id="containeralloca_star2_3" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['5년 평균 자산 성장률(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_m_assetsgr']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_m_assetsgr']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_m_assetsgr']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star2_3', chart_value, chart_title);</script>

                                    <?php }else{?>
                                    <div id="containeralloca_star2_1" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['5년평균 ROE(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_m_roe']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_m_roe']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_m_roe']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star2_1', chart_value, chart_title);</script>

                                    <div id="containeralloca_star2_2" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['5년평균 영업이익률(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_m_opmargin']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_m_opmargin']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_m_opmargin']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star2_2', chart_value, chart_title);</script>

                                    <div id="containeralloca_star2_3" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['5년평균 매출액성장률(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_m_revenuegr']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_m_revenuegr']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_m_revenuegr']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star2_3', chart_value, chart_title);</script>
                                    <?php }?>
                                    <ul class="chart_legend">
                                        <li><span><i></i>종목</span></li>
                                        <li><span><i></i>업종평균</span></li>
                                        <li><span><i></i>전체평균</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="alloca_star">
                                <div class="alloca_box">
                                    <div class="small_star">
                                        <h2 class="title">재무안전성</h2>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($mri_data['m_biz_safety_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$mri_data['m_biz_safety_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">동일업종 평균</span>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($indavg_scores['m_biz_safety_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$indavg_scores['m_biz_safety_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </div>
                                    <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">전체기업 평균</span>
                                        <span>
                                            <div class="star_area">
                                                <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($avg_scores['m_biz_safety_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$avg_scores['m_biz_safety_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <!-- //star_area -->
                                        </span>
                                    </div> <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->
                                <p class="txt"><?=$mri_data['safety'];?><?php if(!$is_financial) echo ' 재무안전성은 부채비율, 유동비율, 이자보상배수, 금융비용 등을 종합해 평가합니다.';?></p>
                                <?php
                                    //부채비율(%)
                                    if($mri_data['m_s_debtratio']=='') $mri_data['m_s_debtratio']=0; else $mri_data['m_s_debtratio'] = round($mri_data['m_s_debtratio']*100, 2);
                                    if($indavg_scores['m_s_debtratio']=='') $indavg_scores['m_s_debtratio']=0; else $indavg_scores['m_s_debtratio'] = round($indavg_scores['m_s_debtratio']*100, 2);
                                    if($avg_scores['m_s_debtratio']=='') $avg_scores['m_s_debtratio']=0; else $avg_scores['m_s_debtratio'] = round($avg_scores['m_s_debtratio']*100, 2);

                                    //유동비율(%)
                                    if($mri_data['m_s_crratio']=='') $mri_data['m_s_crratio']=0; else $mri_data['m_s_crratio'] = round($mri_data['m_s_crratio']*100, 2);
                                    if($indavg_scores['m_s_crratio']=='') $indavg_scores['m_s_crratio']=0; else $indavg_scores['m_s_crratio'] = round($indavg_scores['m_s_crratio']*100, 2);
                                    if($avg_scores['m_s_crratio']=='') $avg_scores['m_s_crratio']=0; else $avg_scores['m_s_crratio'] = round($avg_scores['m_s_crratio']*100, 2);

                                    //이자보상배수(배)
                                    if($mri_data['m_s_intcoverage']=='') $mri_data['m_s_intcoverage']=0; else $mri_data['m_s_intcoverage'] = round($mri_data['m_s_intcoverage']);
                                    if($indavg_scores['m_s_intcoverage']=='') $indavg_scores['m_s_intcoverage']=0; else $indavg_scores['m_s_intcoverage'] = round($indavg_scores['m_s_intcoverage']);
                                    if($avg_scores['m_s_intcoverage']=='') $avg_scores['m_s_intcoverage']=0; else $avg_scores['m_s_intcoverage'] = round($avg_scores['m_s_intcoverage']);

                                    //차입금비중(%)
                                    if($mri_data['m_s_boingratio']=='') $mri_data['m_s_boingratio']=0; else $mri_data['m_s_boingratio'] = round($mri_data['m_s_boingratio']*100, 2);
                                    if($indavg_scores['m_s_boingratio']=='') $indavg_scores['m_s_boingratio']=0; else $indavg_scores['m_s_boingratio'] = round($indavg_scores['m_s_boingratio']*100, 2);
                                    if($avg_scores['m_s_boingratio']=='') $avg_scores['m_s_boingratio']=0; else $avg_scores['m_s_boingratio'] = round($avg_scores['m_s_boingratio']*100, 2);

                                    //금융비용(%)
                                    if($mri_data['m_s_fincost']=='') $mri_data['m_s_fincost']=0; else $mri_data['m_s_fincost'] = round($mri_data['m_s_fincost']*100, 2);
                                    if($indavg_scores['m_s_fincost']=='') $indavg_scores['m_s_fincost']=0; else $indavg_scores['m_s_fincost'] = round($indavg_scores['m_s_fincost']*100, 2);
                                    if($avg_scores['m_s_fincost']=='') $avg_scores['m_s_fincost']=0; else $avg_scores['m_s_fincost'] = round($avg_scores['m_s_fincost']*100, 2);

                                    if($is_financial) {
                                        //자기자본비율(%)
                                        if($mri_data['m_s_bis']=='') $mri_data['m_s_bis']=0; //else $mri_data['m_s_bis'] = round($mri_data['m_s_bis']*100, 2);
                                        if($indavg_scores['m_s_bis']=='') $indavg_scores['m_s_bis']=0; //else $indavg_scores['m_s_bis'] = round($indavg_scores['m_s_bis']*100, 2);
                                        if($avg_scores['m_s_bis']=='') $avg_scores['m_s_bis']=0; //else $avg_scores['m_s_bis'] = round($avg_scores['m_s_bis']*100, 2);
                                    }
                                ?>
                                <div class="chart_box">
                                    <?php if($is_financial){?>
                                    <div id="containeralloca_star3_1" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['자기자본비율(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_s_bis']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_s_bis']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_s_bis']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star3_1', chart_value, chart_title);</script>

                                    <?php }else {?>                            
                                    <div id="containeralloca_star3_1" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['부채비율(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_s_debtratio']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_s_debtratio']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_s_debtratio']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star3_1', chart_value, chart_title);</script>

                                    <div id="containeralloca_star3_2" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['유동비율(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_s_crratio']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_s_crratio']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_s_crratio']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star3_2', chart_value, chart_title);</script>

                                    <div id="containeralloca_star3_3" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['이자보상배수(배)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_s_intcoverage']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_s_intcoverage']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_s_intcoverage']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star3_3', chart_value, chart_title);</script>

                                    <div id="containeralloca_star3_4" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['차입금비중(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_s_boingratio']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_s_boingratio']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_s_boingratio']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star3_4', chart_value, chart_title);</script>
                                
                                    <div id="containeralloca_star3_5" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['금융비용(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_s_fincost']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_s_fincost']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_s_fincost']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star3_5', chart_value, chart_title);</script>
                                <?php }?>                            
                                    <ul class="chart_legend">
                                        <li><span><i></i>종목</span></li>
                                        <li><span><i></i>업종평균</span></li>
                                        <li><span><i></i>전체평균</span></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- //alloca_star -->
                            <div class="alloca_star">
                                <div class="alloca_box">
                                    <div class="small_star">
                                        <h2 class="title">수익성장성</h2>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($mri_data['m_biz_growth_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$mri_data['m_biz_growth_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">동일업종 평균</span>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($indavg_scores['m_biz_growth_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$indavg_scores['m_biz_growth_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </div>
                                    <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">전체기업 평균</span>
                                        <span>
                                            <div class="star_area">
                                                <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($avg_scores['m_biz_growth_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$avg_scores['m_biz_growth_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <!-- //star_area -->
                                        </span>
                                    </div> <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <p class="txt"><?=$mri_data['growth'];?></p>

                                <?php
                                    //5년평균 ROE(%)
                                    if($mri_data['m_g_roe']=='') $mri_data['m_g_roe']=0; 
                                    if($indavg_scores['m_g_roe']=='') $indavg_scores['m_g_roe']=0;
                                    if($avg_scores['m_g_roe']=='') $avg_scores['m_g_roe']=0; 

                                    //이익성장률(%)
                                    if($mri_data['m_g_epsgr']=='') $mri_data['m_g_epsgr']=0;
                                    if($indavg_scores['m_g_epsgr']=='') $indavg_scores['m_g_epsgr']=0;
                                    if($avg_scores['m_g_epsgr']=='') $avg_scores['m_g_epsgr']=0;

                                    //2020.08.26 변경 if(strtoupper($ticker['tkr_category'])=='ADR' || strtoupper($ticker['tkr_category'])=='ADR PRIMARY' || strtoupper($ticker['tkr_category'])=='CANADIAN' || strtoupper($ticker['tkr_category'])=='CANADIAN PRIMARY') {
                                    if( strstr(strtoupper($ticker['tkr_category']), 'ADR') || strstr(strtoupper($ticker['tkr_category']), 'CANADIAN') ) {
                                
                                        $last_mry_list_do = array();
                                        $last_mry_list_do = array_slice($last_mry_list, 0, 2);
                                        $curr = array_shift($last_mry_list_do);
                                        $before = array_pop($last_mry_list_do);
                                        $sf1_netinc = 'sf1_netinc';
                                    }
                                    else {
                                        $last_mry_list_do = array();
                                        $last_mry_list_do = $last_mrt_list;
                                        if(sizeof($last_mry_list_do)>5) {
                                            $last_mry_list_do = array_slice($last_mry_list_do, 0, 5);
                                        }
                                        $curr = array_shift($last_mry_list_do);
                                        $before = array_pop($last_mry_list_do);
                                        $sf1_netinc = 'sf1_netinccmnusd';
                                    }
            
                                    $epsgr = 0;
                                    if( $curr[$sf1_netinc] > 0 && $before[$sf1_netinc] < 0 ) {
                                        $epsgr = 1;
                                        $str_netinc = '흑자전환';
                                    }
                                    else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] < 0 ) {
                                        $epsgr = 1;
                                        $str_netinc = '적자지속';
                                    }
                                    else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] > 0 ) {
                                        $epsgr = 1;
                                        $str_netinc = '적자전환';
                                    }

                                    if($epsgr) {
                                        $mri_data['m_g_epsgr']=0;
                                        //$indavg_scores['m_g_epsgr']=0;
                                        //$avg_scores['m_g_epsgr']=0;
                                    }
                                ?>

                                <div class="chart_box">
                                    <div id="containeralloca_star4_1" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['5년 평균 ROE(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_g_roe']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_g_roe']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_g_roe']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star4_1', chart_value, chart_title);</script>

                                    <div id="containeralloca_star4_2" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['순이익성장률(%)'];
                                    var ex_title = '<?=$str_netinc;?>';
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_g_epsgr']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_g_epsgr']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_g_epsgr']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star4_2', chart_value, chart_title, ex_title);</script>
                                    <ul class="chart_legend">
                                        <li><span><i></i>종목</span></li>
                                        <li><span><i></i>업종평균</span></li>
                                        <li><span><i></i>전체평균</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="alloca_star">
                                <div class="alloca_box">
                                    <div class="small_star">
                                        <h2 class="title">현금창출력</h2>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($mri_data['m_biz_cashflow_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$mri_data['m_biz_cashflow_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">동일업종 평균</span>
                                        <div class="star_area">
                                            <div class="starRev">
                                            <?php 
                                            for($i = 1 ; $i <= 5 ; $i++) { 
                                                if($indavg_scores['m_biz_cashflow_stars'] >= $i) {
                                                    echo '<span class="starR on">별1</span>';
                                                }
                                                else {
                                                    if($i-$indavg_scores['m_biz_cashflow_stars'] <= 0.5) {
                                                        echo '<span class="starR on half">별1</span>';
                                                    }
                                                    else {
                                                        echo '<span class="starR">별1</span>';
                                                    }
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <!-- //star_area -->
                                    </div>
                                    <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <div class="alloca_box">
                                    <div class="small_star">
                                        <span class="title">전체기업 평균</span>
                                        <span>
                                            <div class="star_area">
                                                <div class="starRev">
                                                <?php 
                                                for($i = 1 ; $i <= 5 ; $i++) { 
                                                    if($avg_scores['m_biz_cashflow_stars'] >= $i) {
                                                        echo '<span class="starR on">별1</span>';
                                                    }
                                                    else {
                                                        if($i-$avg_scores['m_biz_cashflow_stars'] <= 0.5) {
                                                            echo '<span class="starR on half">별1</span>';
                                                        }
                                                        else {
                                                            echo '<span class="starR">별1</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <!-- //star_area -->
                                        </span>
                                    </div> <!-- //small_star -->
                                </div>
                                <!-- //alloca_box -->

                                <p class="txt"><?=$mri_data['cashflow'];?></p>

                                <?php
                                    //잉여현금흐름비율(%)
                                    if($mri_data['m_c_ffrevenue']=='') $mri_data['m_c_ffrevenue']=0; else $mri_data['m_c_ffrevenue'] = round($mri_data['m_c_ffrevenue']*100, 2);
                                    if($indavg_scores['m_c_ffrevenue']=='') $indavg_scores['m_c_ffrevenue']=0; else $indavg_scores['m_c_ffrevenue'] = round($indavg_scores['m_c_ffrevenue']*100, 2);
                                    if($avg_scores['m_c_ffrevenue']=='') $avg_scores['m_c_ffrevenue']=0; else $avg_scores['m_c_ffrevenue'] = round($avg_scores['m_c_ffrevenue']*100, 2);

                                    //주가현금흐름배수(배)
                                    if($mri_data['m_c_pcr']=='') $mri_data['m_c_pcr']=0; else $mri_data['m_c_pcr'] = round($mri_data['m_c_pcr']);
                                    if($indavg_scores_p['m_c_pcr']=='') $indavg_scores_p['m_c_pcr']=0; else $indavg_scores_p['m_c_pcr'] = round($indavg_scores_p['m_c_pcr']);
                                    if($avg_scores_p['m_c_pcr']=='') $avg_scores_p['m_c_pcr']=0; else $avg_scores_p['m_c_pcr'] = round($avg_scores_p['m_c_pcr']);

                                    //영업활동현금흐름(%)
                                    if($mri_data['m_c_cashflow']=='') $mri_data['m_c_cashflow']=0; else $mri_data['m_c_cashflow'] = round($mri_data['m_c_cashflow']);
                                    if($indavg_scores['m_c_cashflow']=='') $indavg_scores['m_c_cashflow']=0; else $indavg_scores['m_c_cashflow'] = round($indavg_scores['m_c_cashflow']);
                                    if($avg_scores['m_c_cashflow']=='') $avg_scores['m_c_cashflow']=0; else $avg_scores['m_c_cashflow'] = round($avg_scores['m_c_cashflow']);
                                ?>

                                <div class="chart_box">
                                    <?php if($is_financial){?>
                                    <div id="containeralloca_star5_3" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['영업활동현금(백만달러)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_c_cashflow']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_c_cashflow']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_c_cashflow']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star5_3', chart_value, chart_title);</script>
                                    <?php }else{?>
                                    <div id="containeralloca_star5_1" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['잉여현금흐름비율(%)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_c_ffrevenue']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_c_ffrevenue']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_c_ffrevenue']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star5_1', chart_value, chart_title);</script>

                                    <div id="containeralloca_star5_2" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['주가현금흐름배수(배)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_c_pcr']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores_p['m_c_pcr']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores_p['m_c_pcr']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star5_2', chart_value, chart_title);</script>

                                    <div id="containeralloca_star5_3" class="containeralloca_star1"></div>
                                    <script>
                                    var chart_title = ['영업활동현금(백만달러)'];
                                    var chart_value = [
                                        {'name': '종목', 'data': [<?=$mri_data['m_c_cashflow']?>]},
                                        {'name': '업종평균', 'data': [<?=$indavg_scores['m_c_cashflow']?>]},
                                        {'name': '전체평균', 'data': [<?=$avg_scores['m_c_cashflow']?>]}
                                    ];
                                    SubSearchSummaryColumnChart('containeralloca_star5_3', chart_value, chart_title);</script>
                                    <!-- 종목검사 종목진단 class = containeralloca_star1  -->                                    
                                    <?php }?>
                                    <ul class="chart_legend">
                                        <li><span><i></i>종목</span></li>
                                        <li><span><i></i>업종평균</span></li>
                                        <li><span><i></i>전체평균</span></li>
                                    </ul>
                                </div>
                            </div>
                            <?php endif;?>
                        </div>
                        <!-- //hiding_box -->
						<?php endif;?>

                        <?php if($this->session->userdata('is_paid')===TRUE || $open_ticker===TRUE) :?>
                        <div class="compet_chart">
                            <h3 class="title">경쟁사 투자매력도</h3>
                            <div class="swiper-container swiper_competChar">
                                <div class="swiper-wrapper">
                                    <?php foreach($competitor_data as $key => $row) : ?>
                                    <div class="swiper-slide">
                                        <div class="chart_area">
                                            <div class="chartData">
                                                <div id="charm_comp_spider_chart<?=$key?>" class="containercompet1"></div>
                                                <script>SubSearchCharmCompSpiderChart('charm_comp_spider_chart<?=$key?>', [<?=$row['m_biz_dividend_stars']?>, <?=$row['m_biz_moat_stars']?>, <?=$row['m_biz_cashflow_stars']?>, <?=$row['m_biz_growth_stars']?>, <?=$row['m_biz_safety_stars']?>])</script>
                                                <!-- 종목검색 - 종목진단 - 경쟁사투자매력도 id = containercompet1 -->
                                                <span class="chart_name"><?php if($part_page == ''){?><a href="/<?=HN?>_search/invest_charm/<?=$row['m_ticker'];?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?php }?><?=$row['m_korname']?> (<?=$row['m_ticker']?>)<?php if($part_page == ''){?></a><?php }?></span>
                                                
                                                <div class="chart_star">
                                                    <div class="star_area">
                                                        <span class="title">배당</span>
                                                        <div class="starRev">
                                                        <?php 
                                                        for($i = 1 ; $i <= 5 ; $i++) { 
                                                            if($row['m_biz_dividend_stars'] >= $i) {
                                                                echo '<span class="starR on">별1</span>';
                                                            }
                                                            else {
                                                                if($i-$row['m_biz_dividend_stars'] <= 0.5) {
                                                                    echo '<span class="starR on half">별1</span>';
                                                                }
                                                                else {
                                                                    echo '<span class="starR">별1</span>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                    <!-- //star_area -->
                                                    <div class="star_area">
                                                        <span class="title">사업</span>
                                                        <div class="starRev">
                                                        <?php 
                                                        for($i = 1 ; $i <= 5 ; $i++) { 
                                                            if($row['m_biz_moat_stars'] >= $i) {
                                                                echo '<span class="starR on">별1</span>';
                                                            }
                                                            else {
                                                                if($i-$row['m_biz_moat_stars'] <= 0.5) {
                                                                    echo '<span class="starR on half">별1</span>';
                                                                }
                                                                else {
                                                                    echo '<span class="starR">별1</span>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                    <!-- //star_area -->
                                                    <div class="star_area">
                                                        <span class="title">재무</span>
                                                        <div class="starRev">
                                                        <?php 
                                                        for($i = 1 ; $i <= 5 ; $i++) { 
                                                            if($row['m_biz_safety_stars'] >= $i) {
                                                                echo '<span class="starR on">별1</span>';
                                                            }
                                                            else {
                                                                if($i-$row['m_biz_safety_stars'] <= 0.5) {
                                                                    echo '<span class="starR on half">별1</span>';
                                                                }
                                                                else {
                                                                    echo '<span class="starR">별1</span>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                    <!-- //star_area -->
                                                    <div class="star_area">
                                                        <span class="title">수익</span>
                                                        <div class="starRev">
                                                        <?php 
                                                        for($i = 1 ; $i <= 5 ; $i++) { 
                                                            if($row['m_biz_growth_stars'] >= $i) {
                                                                echo '<span class="starR on">별1</span>';
                                                            }
                                                            else {
                                                                if($i-$row['m_biz_growth_stars'] <= 0.5) {
                                                                    echo '<span class="starR on half">별1</span>';
                                                                }
                                                                else {
                                                                    echo '<span class="starR">별1</span>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                    <!-- //star_area -->
                                                    <div class="star_area">
                                                        <span class="title">현금</span>
                                                        <div class="starRev">
                                                        <?php 
                                                        for($i = 1 ; $i <= 5 ; $i++) { 
                                                            if($row['m_biz_cashflow_stars'] >= $i) {
                                                                echo '<span class="starR on">별1</span>';
                                                            }
                                                            else {
                                                                if($i-$row['m_biz_cashflow_stars'] <= 0.5) {
                                                                    echo '<span class="starR on half">별1</span>';
                                                                }
                                                                else {
                                                                    echo '<span class="starR">별1</span>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                    <!-- //star_area -->
                                                    <?php if($part_page == ''){?><a href="/<?=HN?>_search/invest_charm/<?=$row['m_ticker']?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>'" class="more">자세히 <i>>></i> </a><?php }?>
                                                </div>
                                                <!-- //chart_star -->
                                            </div>
                                        </div>
                                        <!-- //chart_area -->
                                    </div>
                                    <!-- //swiper-slide -->
                                    <?php endforeach; ?>
                                </div>
                                <!-- //swiper-wrapper -->
                            </div>
                        </div>
                        <!-- //compet_chart -->

                            <?php if($is_soft===false) :?>
                            <!--<a href="/<?=HN?>_main/service_guide" class="compet_chart_uses">
                                <h3 class="title">투자매력 <strong>스파이더차트</strong> 활용 가이드</h3>
                                <img src="/img/more_Black.png" alt="더보기" class="more">
                            </a>-->
                            <?php endif;?>
                        <!-- //compet_chart_uses -->
                        <?php endif;?>
                    </div>
                    <!-- //tab_diagnosis_area -->
                </div>
                <!-- //investCharm_area -->
            </div>
            <!-- //sub_mid -->
<?php }else{?>
        <!-- 주요 콘텐츠 -->
        <div class="sub_mid nondata">
            <p class="nodata_guide"><strong><?=$sec_ticker;?></strong>의 상세 기업정보는 <strong><?php if($part_page == ''){?><a href="/<?=HN?>_search/invest_charm/<?=$pri_ticker?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?php }?><?=$pri_ticker?><?php if($part_page == ''){?></a><?php }?></strong> 종목에서 확인할 수 있습니다.</p>
        </div>
        <!-- //sub_mid nondata -->
<?php }?>
