            <div class="sub_mid latest_results">
                <div class="tabsArea">
                    <ul class="tabs tabs_two">
                        <li class="active" rel="tab1"><a href='/<?=HT?>_main/search'>관심종목</a></li>
                        <li rel="tab2">인기종목</li>
                    </ul>

                    <div class="tab_container">
                        <!-- 관심종목 -->
                        <div id="tab1" class="tab_content">


                            <div class="onestep_guide">
                                <h2 class="h2_title">관심종목 원스톱 진단</h2>
                                <a href="/<?=HT?>_main/search" class="more"><span>관심종목</span><img src="/img/more_white.png" alt="더보기"></a>
                                <p class="txt">관심종목(♥)으로 설정한 투자유망 종목과 투자유의 종목으로 한 눈에 볼 수 있도록 진단합니다.</p>
                            </div>

                            <?php //if($this->session->userdata('is_paid')===TRUE) :?>

                            <?php if($this->session->userdata('is_paid')===TRUE && is_array($tab_stock_data) && sizeof($tab_stock_data)>0) :?>
                            <div class="onestep_chart">
                                <div class="chart_wrap">
                                    <span></span>
                                    <div class="labels_y">
                                        <span>고평가</span>
                                        <span>밸류에이션</span>
                                        <span>저평가</span>
                                    </div>
                                    <div class="labels_x">
                                        <span>저</span>
                                        <span>투자매력</span>
                                        <span>고</span>
                                    </div>
                                    <figure class="highcharts-figure">
                                        <div id="onestep_chart1_1"></div>

<?php //iconv_substr($val['ticker']['tkr_name'], 0, 1, 'utf-8')?>

                                        <script>
                                            var value = [{
                                                data: [
                                                    <?php $hopeful=''; $careful=''; $cnt=0;?>
                                                    <?php foreach($tab_stock_data as $val) :?>
                                                    <?php if(iconv_strlen($val['ticker']['tkr_name'], 'UTF-8')>7) :?>
                                                    <?php $ticker_name = iconv_substr($val['ticker']['tkr_name'], 0, 7, 'utf-8').'..'?>
                                                    <?php else :?>
                                                    <?php $ticker_name = $val['ticker']['tkr_name']?>
                                                    <?php endif;?>
                                                    <?php $is_careful = FALSE?>
                                                    <?php if($val['fairvalue_rate']>=0) :?>
                                                    <?php $total_value += $val['m_biz_total_score']?>
                                                    <?php $total_fairvalue += $val['fairvalue_rate']?>
                                                    { x: <?=$val['m_biz_total_score']?>, y: <?=$val['fairvalue_rate']?>, name: '<?=$val['ticker']['tkr_ticker']?>'},
                                                    <?php $cnt++?>
                                                    <?php endif;?>
                                                    <?php if($val['m_biz_total_score']<40 || $val['m_biz_safety_score'] < 8 || $val['m_biz_cashflow_score'] < 8) :?>
                                                    <?php $careful .= '<li><a href="/'.HT.'_search/invest_charm/'.$val['ticker']['tkr_ticker'].'">'.$ticker_name.'</a></li>'."\n"?>
                                                    <?php $is_careful = TRUE?>
                                                    <?php endif;?>
                                                    <?php if($val['m_biz_total_score']>=65 && $val['ticker']['tkr_close'] < $val['m_v_fairvalue2']) :?>
                                                    <?php if($is_careful === FALSE):?>
                                                    <?php $hopeful .= '<li><a href="/'.HT.'_search/invest_charm/'.$val['ticker']['tkr_ticker'].'">'.$ticker_name.'</a></li>'."\n"?>
                                                    <?php endif;?>
                                                    <?php endif;?>
                                                    <?php endforeach;?>
                                                ]
                                            }];
                                            OneStopChart('onestep_chart1_1', value);
                                        </script>
                                        <div class="average" style="left: <?=round($total_value/$cnt)?>%; top: <?=(100-round($total_fairvalue/$cnt))?>%;">종목<br>평균</div>
                                    </figure>
                                </div>
                                <!-- //chart_wrap -->
                                <p class="nodata_guide">* 밸류에이션이 N/A인 종목은 제외됩니다.</p>
                                <div class="dgtic_results">
                                    <div class="hope">
                                        <h3 class="h3_title">투자 유망 종목</h3>
                                        <ul><?=($hopeful == '') ? '<li class="no_event">해당하는 종목이 없습니다</li>':$hopeful?></ul>
                                    </div>
                                    <!-- //hope -->
                                    <div class="caution">
                                        <h3 class="h3_title">투자 유의 종목</h3>
                                        <ul><?=($careful == '') ? '<li class="no_event">해당하는 종목이 없습니다</li>':$careful?></ul>
                                    </div>
                                    <!-- //caution -->
                                    <p class="nodata_guide tr_left">* 투자 유망/유의 종목은 투자매력 점수, 밸류에이션, 재무 안전성 등을 반영한 포트폴리오 진단 알고리즘으로 평가합니다. </p>
                                </div>
                                <!-- //dgtic_results -->
                            </div>
                            <!-- //onestep_chart -->

                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="100px">
                                    <col width="">
                                    <col width="">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                <?php 
                                    foreach($tab_stock_data as $val) : 
                                        $class = 'decrease';
                                        if($val['ticker']['tkr_rate'] > 0) {
                                            $class = 'increase';
                                        }
                                ?>
                                    <tr>
                                        <td class="title"><a href="/<?=HT?>_search/invest_charm/<?=$val['ticker']['tkr_ticker']?>"><?=$val['ticker']['tkr_name']?><span class="ticker"><?=$val['ticker']['tkr_ticker']?></span></a></td>
                                        <td class="num">
                                            <span><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1');?></span>
                                            <span class="<?=$class?>"><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2');?></span>
                                        </td>
                                        <td class="score"><span><?=$val['m_biz_total_score']?><b>점</b></span></td>
                                        <td class="num">
                                            <?php if($val['m_v_fairvalue3']>0) :?>
                                            <span><?=$this->common->set_pricepoint($val['m_v_fairvalue3'], '1');?></span>
                                            <span class="hit">적정주가</span>
                                            <?php else :?>
                                            <span class="na">N/A</span>
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else:?>

                            <!-- 원스톱 진단 샘플화면 -->
                            <div class="onestep_sample">
                                <a href="javascript:fnSinChung();" class="sample_box md-trigger">
                                    <strong>원스톱 진단</strong>샘플화면입니다.</a>

                                <!-- 2주 무료 이용 -->
                                <?php //if($this->session->userdata('is_paid')===FALSE) :?>
                                <?php //include_once HT_INC_PATH.'/premium_banner.php'; ?>
                                <?php //endif;?>

                            </div>
                            <!-- //onestep_sample -->

                            <!--<div class="no_data">
                                <p>관심종목의 투자매력 점수와<br>밸류에이션을 쉽게 확인할 수 있습니다.<br><br>
                                    종목을 검색하여 종목명 옆의 하트(♥)를<br>
                                    누르면 관심종목으로 등록됩니다.</p>
                            </div>-->


                            <?php endif;?>

                            <?php //endif;?>



                            <!--<div class="one_step">
                                <span class="txt">초이스스탁US에서 추천하는 취향저격 종목을 확인하세요</span>
                                <a href="/<?=HT?>_stock/catch_info" class="more"><span>종목 캐치</span><img src="/img/more_yel.png" alt="더보기"></a>
                            </div>-->
                        </div>

                        <!-- 인기종목 -->
                        <div id="tab2" class="tab_content">
                            <div class="">
                                <div class="txt_box">
                                    <p class="txt">투자자가 가장 많이 매매하고 있는 인기종목의 투자매력점수 및 적정주가를 제공합니다.</p>
                                </div>
                                <!-- //txt_box -->
                            </div>

                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="100px">
                                    <col width="">
                                    <col width="">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                <?php 
                                    foreach($popular_stock_data as $val) : 
                                        $class = 'decrease';
                                        if($val['ticker']['tkr_rate'] > 0) {
                                            $class = 'increase';
                                        }
                                ?>
                                    <tr>
                                        <td class="title"><a href="/<?=HT?>_search/invest_charm/<?=$val['ticker']['tkr_ticker']?>"><?=$val['ticker']['tkr_name']?><span class="ticker"><?=$val['ticker']['tkr_ticker']?></span></a></td>
                                        <td class="num">
                                            <span><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1');?></span>
                                            <span class="<?=$class?>"><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2');?></span>
                                        </td>
                                        <td class="score"><span><?=$val['m_biz_total_score']?><b>점</b></span></td>
                                        <?php if($this->session->userdata('is_paid')===TRUE) :?>
                                        <td class="recom"><span>
                                        <?php
                                            $value_score_txt = 'N/A';
                                            if($val['expected_star']=='1') $value_score_txt = '저평가';
                                            else if($val['expected_star']=='3') $value_score_txt = '적정가';
                                            else if($val['expected_star']=='5') $value_score_txt = '고평가';                                        
                                        ?>
                                        <?=$value_score_txt?></span></td>
                                        <?php else :?>
                                        <td class="prm_lock">
                                            <span><a href="/<?=HT?>_main/service"><img src="/img/prm_lock.png" alt="잠김"></a></span>
                                        </td>
                                        <?php endif;?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>

                            <h2 class="tab_title">급등주</h2>
                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="100px">
                                    <col width="">
                                    <col width="">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                <?php 
                                    foreach($soaring_stock_data as $val) : 
                                        $class = 'decrease';
                                        if($val['ticker']['tkr_rate'] > 0) {
                                            $class = 'increase';
                                        }
                                ?>
                                    <tr>
                                        <td class="title"><a href="/<?=HT?>_search/invest_charm/<?=$val['ticker']['tkr_ticker']?>"><?=$val['ticker']['tkr_name']?><span class="ticker"><?=$val['ticker']['tkr_ticker']?></span></a></td>
                                        <td class="num">
                                            <span><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1');?></span>
                                            <span class="<?=$class?>"><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2');?></span>
                                        </td>
                                        <td class="score"><span><?=$val['m_biz_total_score']?><b>점</b></span></td>
                                        <?php if($this->session->userdata('is_paid')===TRUE) :?>
                                        <td class="recom"><span>
                                        <?php
                                            $value_score_txt = 'N/A';
                                            if($val['expected_star']=='1') $value_score_txt = '저평가';
                                            else if($val['expected_star']=='3') $value_score_txt = '적정가';
                                            else if($val['expected_star']=='5') $value_score_txt = '고평가';                                        
                                        ?>
                                        <?=$value_score_txt?></span></td>
                                        <?php else :?>
                                        <td class="prm_lock">
                                            <span><a href="/<?=HT?>_main/service"><img src="/img/prm_lock.png" alt="잠김"></a></span>
                                        </td>
                                        <?php endif;?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- //tab_container -->
                </div>
                <!-- //tabsArea -->

            </div>
            <!-- //sub_mid -->
