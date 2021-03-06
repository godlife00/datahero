            <div class="sub_mid latest_results">
                <div class="tabsArea">
                    <ul class="tabs tabs_two">
                        <li class="active" rel="tab1">관심종목</li>
                        <li rel="tab2">인기종목</li>
                    </ul>
                    <div class="tab_container">
                        <!-- 관심종목 -->
                        <div id="tab1" class="tab_content">
                            <div class="one_step no_bg">
                                <span class="txt">나의 관심종목은 얼마나 매력적일까?</span>
                                <a href="/<?=HT?>_main/onestop" class="more"><span>원스톱 진단</span><img src="/img/more_yel.png" alt="더보기"></a>
                            </div>
                            <!-- //one_step -->
                            
                            <?php if($this->session->userdata('is_paid')===TRUE) :?>

								<?php if(is_array($tab_stock_data) && sizeof($tab_stock_data)>0) :?>
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
								<div class="no_data">
								    <p>관심종목의 투자매력 점수와<br>밸류에이션을 쉽게 확인할 수 있습니다.<br><br>
								        종목을 검색하여 종목명 옆의 하트(♥)를<br>
								        누르면 관심종목으로 등록됩니다.</p>
								</div>
								<?php endif;?>
							<?php else :?>
								<div class="no_data">
								    <p>관심종목의 투자매력 점수와<br>밸류에이션을 쉽게 확인할 수 있습니다.<br><br>
								        종목을 검색하여 종목명 옆의 하트(♥)를<br>
								        누르면 관심종목으로 등록됩니다.</p>
								</div>
                            <?php endif;?>

                            <?php if($this->session->userdata('is_paid')===TRUE && $myticker===TRUE):?>
                            <div class="btn_list">
                                <a href="/<?=HT?>_main/myticker">관심종목 관리</a>
                            </div> 
                            <?php endif;?>
                            
                            <?php //if($this->session->userdata('is_login')===FALSE) : ?>
                            <?php //include_once HT_INC_PATH.'/premium_banner.php'; ?>
                            <?php //endif;?>
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
