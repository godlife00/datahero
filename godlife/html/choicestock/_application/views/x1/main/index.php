			<a href="/<?=X1?>_stock/morning" class="temp_briefinglink"><img src="/img/icon_alarm.png" alt="모닝브리핑"></a>
			<!-- 로그인 체크 -->
            <div class="log_chk">
                <?php //if(rand(0, 1)) :?>
                <!-- 회원로그인 -->
                <div class="login">
                    <p><span class="user_name">초이스스탁US</span>미국주식 쉽고 편리하게!</p>
                </div>
                <?php //else :?>
                <!-- 비로그인 -->
                <!--<div class="logout">
                    <p>* 원활한 서비스 이용을 위해 <span>메일 인증이 필요</span>합니다. <a href="">[자세히]</a></p>
                </div>-->
                <?php //endif;?>

            </div>
            <!-- //log_chk -->

            <div class="main_searching">
                <!-- 종목명 검색창 -->
                <div class="searchArea">

                    <form action=""  name="topsearch" onsubmit="var v = $('#autocomplete_list li a._on span.schCode').html(); if(v.length > 0 && $('#autocomplete_list').get(0).children.length > 0) { this.action='/<?=X1?>_search/invest_charm/'+v; setSearchHistory(v); return true; }; return false;">
                        <fieldset>
                            <input type="text" name='keyword' autocomplete="off" placeholder="종목명 또는 심볼을 입력하세요." class="searchInput searchInput_fixed">
                            <input type="image" src="/img/icon_search.png" alt="검색" class="searchBtn">
                        </fieldset>
                    </form>
                </div>
                <!-- //종목명 검색창 -->

                <!-- 검색어 입력시 자동완성 -->
                <div class="sch_autocomplete">
                    <!-- //자동완성 결과 노출 class : _show -->
                    <!-- 검색결과 있을경우 -->
                    <ul id='autocomplete_list'>
                    </ul>

                    <!-- 검색결과 없을경우 -->
                    <div class="no_result" >
                        <p>"<strong></strong>"에 대한 검색결과가 없습니다.</p>
                    </div>
                    <!-- //no_result -->
                </div>
                <!-- //sch_autocomplete -->
            </div>
            <!-- //sub_top -->
            <?php if($is_recommend === true || $this->session->userdata('is_paid')===FALSE) :?>
            <?php if(is_array($recommend) && sizeof($recommend) > 0) : ?>
            <div class="main_top recommend_area">
                <h3 class="title"><a href="/<?=X1?>_stock/recommend">종목추천</a></h3>
                <a href="/<?=X1?>_stock/recommend" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                <div class="recom_list">
                <?php
                $rcnt=0;
                foreach($recommend as $key => $val) :
                    $class = 'decrease';
                    if($val['ticker']['tkr_rate'] > 0) {
                        $class = 'increase';
                    }
                ?>
                    <div class="chart_area">
                        <div class="chartData">
                            <h4 class="event_name">
                            <?php if($rcnt>0 && $this->session->userdata('is_paid')===FALSE) :?>
                            <a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><?=iconv_substr($val['ticker']['tkr_name'], 0, 1, 'utf-8')?><span class="remark"><div class="txt_filter size_B"><i></i><i></i><i></i><i></i></div></span>
                            <?php else :?>
                            <a href="/<?=X1?>_stock/recommend_view/<?=$val['rc_id']?>"><?=$val['ticker']['tkr_name']?>
                            <?php endif;?>
                            </a></h4>
                            <ul class="info">
                                <li class="sum"><span class="eng"><?=($rcnt>0 && $this->session->userdata('is_paid')===FALSE) ? '<span class="remark"><div class="txt_filter size_M"><i></i><i></i><i></i><i></i></div></span>':$val['ticker']['tkr_ticker']?></span> </li>
                            </ul>
                            <ul class="detail">
                                <?php if($is_open === true) :?>
								<li class="num"><span <?=($rcnt>0 && $this->session->userdata('is_paid')===FALSE) ? '' : " class='sync_price' data-ticker='{$val['ticker']['tkr_ticker']}' data-render=\"((el, txt, info) => { var tmp = txt.split('.'); return tmp[0]+'.<b>'+tmp[1]+'</b>';})\""?> ><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1')?></span></li>
                                <li class="per"><span <?=($rcnt>0 && $this->session->userdata('is_paid')===FALSE) ? " class='{$class}'" : " class='{$class} sync_diff_rate' data-ticker='{$val['ticker']['tkr_ticker']}' data-render=\"((el, txt, info) => { el.removeClass('increase'); el.removeClass('decrease'); var c= 'decrease'; if(parseFloat(txt) > 0) { c='increase'; txt = '+'+txt;} el.addClass(c);  return txt + '<b>%</b>'; })\""?> ><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2')?></span>
                                </li>
								<?php else :?>
                                <li class="num"><span><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1')?></span></li>
                                <li class="per"><span class="<?=$class?>"><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2')?></span></li>
								<?php endif;?>
                            </ul>
                            <!-- //detail -->
                        </div>
                        <!-- //chartData -->
                        <div class="chartGoal">
                            <dl>
                                <dt>목표가</dt>
                                <?php if($this->session->userdata('is_paid')===TRUE || $rcnt==0) :?>
                                <dd><?=(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0)? $this->common->set_pricepoint($val['rc_adjust_price'], '1'):$this->common->set_pricepoint($val['rc_goal_price'], '1')?></dd>
                                <?php else :?>
                                <dd class="prm_lock"><a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><img src="/img/prm_lockB_mint.png" alt="잠김"></a></dd>
                                <?php endif;?>
                            </dl>
                            <dl>
                                <dt>수익률</dt>
                                <?php if($val['rc_endtype'] == 'SUCCESS') :?>
									<?php if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :?>
									<dd class="<?=((($val['rc_adjust_price']/$val['rc_recom_price'])-1)*100>0) ? 'increase':'decrease'?>"><?=number_format((($val['rc_adjust_price']/$val['rc_recom_price'])-1)*100,2)?><b>%</b></dd>
									<?php else :?>
									<dd class="<?=((($val['rc_goal_price']/$val['rc_recom_price'])-1)*100>0) ? 'increase':'decrease'?>"><?=number_format((($val['rc_goal_price']/$val['rc_recom_price'])-1)*100,2)?><b>%</b></dd>
									<?php endif;?>
                                <?php else :?>
                                <dd class="<?=((($val['ticker']['tkr_close']/$val['rc_recom_price'])-1)*100>0) ? 'increase':'decrease'?>"><?=number_format((($val['ticker']['tkr_close']/$val['rc_recom_price'])-1)*100,2)?><b>%</b></dd>
                                <?php endif;?>
                            </dl>
                        </div>
                        <!-- //chartGoal -->
                    </div>
                    <!-- //chart_area -->

                <?php $rcnt++; endforeach;?>
                </div>
                <!-- //recom_list -->

                <div class="ptfo_recom">
                    <h4 class="ptfo_title">추천포트폴리오</h4>
                    <dl class="revenue">
                        <dt>수익률</dt>
                        <dd class="increase"><?=$pf_profit?><b>%</b></dd>
                        <!-- increase 증가, decrease 감소 -->
                    </dl>
                    <a href="/<?=X1?>_stock/recommend/portfolio" class="more">종목 <span><?=$pf_count?>개</span><img src="/img/more_Black.png" alt="더보기"></a>
                </div>
				<?php if(is_array($portfolio_list) && sizeof($portfolio_list) > 0) :?>
				<div class="ptfo_datatable">
					<table cellspacing="0" border="1" class="tableRanking ptfo_table">
						<tbody>
							<?php foreach($portfolio_list as $portfolio) :
								$class = 'decrease';
								if($portfolio['rc_rate_str'] > 0) {
									$class = 'increase';
								}
							?>
							<tr>
								<td class="name">
								<?php if($this->session->userdata('is_paid')===FALSE) :?>
								<a href="javascript:fnLogin();"><?=iconv_substr($portfolio['tkr_name'], 0, 1, 'utf-8')?><span class="remark"><div class="txt_filter size_M"><i></i><i></i><i></i><i></i></div></span></a>
								<?php else :?>
									<a href="/<?=X1?>_stock/recommend_view/<?=$portfolio['rc_id']?>"><?=$portfolio['tkr_name']?></a>
								<?php endif;?>
								</td>
								<td class="num">
									<span class=""><?=$this->common->set_pricepoint($portfolio['rc_close'], '1')?></span>
									<span class="<?=$class?> pp"><?=$this->common->set_pricepoint($portfolio['rc_rate_str'], '2')?></span>
								</td>
								<td class="num">
									<span class="yield">수익률</span>
									<span class="<?=($portfolio['rc_profit_rate']>0) ? 'increase':'decrease'?>"><?=$this->common->set_pricepoint($portfolio['rc_profit_rate'], '1')?><b>%</b></span>
								</td>
							</tr>
							<?php endforeach;?>

						</tbody>
					</table>
				</div>
				<?php endif;?>
                <!-- //ptfo_recom -->

                <?php if(isset($winner_list) && is_array($winner_list)) :?>
                <!-- 승부주 -->
                <div class="main_mid game_area">
                    <h3 class="title"><a href="/<?=X1?>_stock/winner">승부주</a> <span class="remark">- 스탁히어로 알고리즘 종목추천</span></h3>
                    <a href="/<?=X1?>_stock/winner" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                    <div class="gametable_box">
                        <ul class="game_th">
                            <li>추천가</li>
                            <li>목표가</li>
                            <li>수급분석</li>
                        </ul>
                        <table cellspacing="0" border="0" class="tableRanking type_2Line">
                            <tbody>
                            <?php $win=0; foreach($winner_list as $winner) :?>

                                <tr>
                                    <td class="title">
                                    <?php if($this->session->userdata('is_paid')===TRUE || $win<1) :?>
                                    <a href="/<?=X1?>_search/invest_charm/<?=$winner['win_ticker']?>"><?=$winner['tkr_name']?><span class="ticker"><?=$winner['win_ticker']?>
                                    <?php else :?>
                                    <a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><?=iconv_substr($winner['tkr_name'], 0, 1, 'utf-8')?><span class="remark"><div class="txt_filter size_M"><i></i><i></i><i></i><i></i></div></span><span class="ticker"><span class="remark"><div class="txt_filter size_S"><i></i><i></i><i></i><i></i></div></span></span>
                                    <?php endif;?>
                                    </span></a>
                                    </td>
                                    <td class="num">
                                        <span><?=$this->common->set_pricepoint(number_format($winner['win_close'], 2), '1')?></span>
                                    </td>
                                    <?php if($this->session->userdata('is_paid')===TRUE || $win<1) :?>
                                    <td class="num">
                                        <span>
                                        <?php if(isset($winner['win_fairvalue']) && $winner['win_fairvalue']>0 && round((($winner['win_fairvalue']/$winner['win_close'])-1)*100) >= 30) :?>
                                        <?=$this->common->set_pricepoint(number_format($winner['win_fairvalue'], 2), '1')?>
                                        <?php else :?>
                                        <?=$this->common->set_pricepoint(number_format($winner['win_rc_price'], 2), '1')?>
                                        <?php endif;?>
                                        </span>
                                    </td>
                                    <?php else :?>
                                    <td class="prm_lock"><span><a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><img src="/img/prm_lock_mint.png" alt="잠김"></a></span></td>
                                    <?php endif;?>
                                    <td class="trans up"><i></i><span>강세</span></td>
                                </tr>
                            <?php $win++; endforeach;?>

                            </tbody>
                        </table>

                    </div>
                    <!-- //gametable_box -->
                </div>
                <!-- //game_area -->
                <?php endif;?>

                <?php include_once X1_INC_PATH.'/main_contents_banner.php'; ?>

			</div>
            
			<!-- //main_top -->
            <?php endif;?>
            <?php endif;?>

            <div class="main_mid attention_area">
                <h3 class="title"><a href="/<?=X1?>_main/search">관심종목</a></h3>
                <a href="/<?=X1?>_main/search" class="more"><img src="/img/more_Black.png" alt="더보기"></a>

               <?php //if(is_array($recommend) && sizeof($recommend) > 0) : ?>

                <!-- Swiper -->
                <div class="swiper-container attentionSwiper">
                    <div class="swiper-wrapper">

                    <?php
                        $cnt=0;
                        foreach($tab_stock_data as $val) :
                            $class = 'decrease';
                            if($val['ticker']['tkr_rate'] > 0) {
                                $class = 'increase';
                            }
                            $value_score_txt = 'N/A';
                            if($val['expected_star']=='1') $value_score_txt = '저평가';
                            else if($val['expected_star']=='3') $value_score_txt = '적정가';
                            else if($val['expected_star']=='5') $value_score_txt = '고평가';
                            $cnt++;
                    ?>

                            <?php if(($cnt%3)==1) :?>
                            <div class="swiper-slide">
                                <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                    <colgroup>
                                        <col width="100px">
                                        <col width="">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <tbody>
                            <?php endif;?>
                                        <tr>
                                            <td class="title"><a href="/<?=X1?>_search/invest_charm/<?=$val['ticker']['tkr_ticker']?>"><?=$val['ticker']['tkr_name']?><span class="ticker"><?=$val['ticker']['tkr_ticker']?></span></a>
                                            </td>
                                            <td class="num">
                                                <span><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1')?></span>
                                                <span class="<?=$class?>"><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2')?></span>
                                            </td>
                                            <td class="score"><span><?=$val['m_biz_total_score']?><b>점</b></span></td>
                                            <?php //if($this->session->userdata('is_paid')===TRUE) :?>
                                            <td class="recom"><span><?=$value_score_txt?></span></td>
                                            <?php //else :?>
                                            <!--<td class="prm_lock"><span><a href="/main/service_prm"><img src="/img/prm_lock.png" alt="잠김"></a></span></td>-->
                                            <?php //endif;?>
                                        </tr>

                            <?php if(($cnt%3)==0):?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif;?>
                            <!-- //swiper-slide -->

                    <?php endforeach;?>
                    <?php if(($cnt%3)!=0):?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif;?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                <!-- //attentionSwiper -->
                <div class="one_step">
                    <span class="txt">나의 관심종목은 얼마나 매력적일까?</span>
                    <a href="/<?=X1?>_main/onestop" class="more"><span>원스톱 진단</span><img src="/img/more_yel.png"
                            alt="더보기"></a>
                </div>
                <!-- //one_step -->

                <?php if($is_recommend === false && $this->session->userdata('is_paid')===TRUE) :?>
                <?php if(is_array($recommend) && sizeof($recommend) > 0) : ?>
                <div class="main_top recommend_area">
                    <h3 class="title"><a href="/<?=X1?>_stock/recommend">종목추천</a></h3>
                    <a href="/<?=X1?>_stock/recommend" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                    <div class="recom_list">
                    <?php
                    $rcnt=0;
                    foreach($recommend as $key => $val) :
                        $class = 'decrease';
                        if($val['ticker']['tkr_rate'] > 0) {
                            $class = 'increase';
                        }
                    ?>
                        <div class="chart_area">
                            <div class="chartData">
                                <h4 class="event_name">
                                <?php if($rcnt>0 && $this->session->userdata('is_paid')===FALSE) :?>
                                <a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><?=iconv_substr($val['ticker']['tkr_name'], 0, 1, 'utf-8')?><span class="remark"><div class="txt_filter size_B"><i></i><i></i><i></i><i></i></div></span>
                                <?php else :?>
                                <a href="/<?=X1?>_stock/recommend_view/<?=$val['rc_id']?>"><?=$val['ticker']['tkr_name']?>
                                <?php endif;?>
                                </a></h4>
                                <ul class="info">
                                    <li class="sum"><span class="eng"><?=($rcnt>0 && $this->session->userdata('is_paid')===FALSE) ? '<span class="remark"><div class="txt_filter size_M"><i></i><i></i><i></i><i></i></div></span>':$val['ticker']['tkr_ticker']?></span> </li>
                                </ul>
                                <ul class="detail">
									<?php if($is_open === true) :?>
									<li class="num"><span <?=($rcnt>0 && $this->session->userdata('is_paid')===FALSE) ? '' : " class='sync_price' data-ticker='{$val['ticker']['tkr_ticker']}' data-render=\"((el, txt, info) => { var tmp = txt.split('.'); return tmp[0]+'.<b>'+tmp[1]+'</b>';})\""?> ><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1')?></span></li>
                                    <li class="per"><span <?=($rcnt>0 && $this->session->userdata('is_paid')===FALSE) ? " class='{$class}'" : " class='{$class} sync_diff_rate' data-ticker='{$val['ticker']['tkr_ticker']}' data-render=\"((el, txt, info) => { el.removeClass('increase'); el.removeClass('decrease'); var c= 'decrease'; if(parseFloat(txt) > 0) { c='increase'; txt = '+'+txt;} el.addClass(c);  return txt + '<b>%</b>'; })\""?> ><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2')?></span>
									<?php else :?>
                                    <li class="num"><span><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1')?></span></li>
                                    <li class="per"><span class="<?=$class?>"><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2')?></span>
									<?php endif;?>
                                    </li>
                                </ul>
                                <!-- //detail -->
                            </div>
                            <!-- //chartData -->

                            <div class="chartGoal">
                                <dl>
                                    <dt>목표가</dt>
                                    <?php if($this->session->userdata('is_paid')===TRUE || $rcnt==0) :?>
							        <dd><?=(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0)? $this->common->set_pricepoint($val['rc_adjust_price'], '1'):$this->common->set_pricepoint($val['rc_goal_price'], '1')?></dd>
                                    <?php else :?>
                                    <dd class="prm_lock"><a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><img src="/img/prm_lockB_mint.png" alt="잠김"></a></dd>
                                    <?php endif;?>
                                </dl>
                                <dl>
                                    <dt>수익률</dt>
                                    <?php if($val['rc_endtype'] == 'SUCCESS') :?>
										<?php if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :?>
										<dd class="<?=((($val['rc_adjust_price']/$val['rc_recom_price'])-1)*100>0) ? 'increase':'decrease'?>"><?=number_format((($val['rc_adjust_price']/$val['rc_recom_price'])-1)*100,2)?><b>%</b></dd>
										<?php else :?>
	 									<dd class="<?=((($val['rc_goal_price']/$val['rc_recom_price'])-1)*100>0) ? 'increase':'decrease'?>"><?=number_format((($val['rc_goal_price']/$val['rc_recom_price'])-1)*100,2)?><b>%</b></dd>
										<?php endif;?>
                                    <?php else :?>
                                    <dd class="<?=((($val['ticker']['tkr_close']/$val['rc_recom_price'])-1)*100>0) ? 'increase':'decrease'?>"><?=number_format((($val['ticker']['tkr_close']/$val['rc_recom_price'])-1)*100,2)?><b>%</b></dd>
                                    <?php endif;?>
                                </dl>
                            </div>
                            <!-- //chartGoal -->
                        </div>
                        <!-- //chart_area -->
                    <?php $rcnt++; endforeach;?>
                    </div>
                    <!-- //recom_list -->

                    <div class="ptfo_recom">
                        <h4 class="ptfo_title">추천포트폴리오</h4>
                        <dl class="revenue">
                            <dt>수익률</dt>
                            <dd class="increase"><?=$pf_profit?><b>%</b></dd>
                            <!-- increase 증가, decrease 감소 -->
                        </dl>
                        <a href="/<?=X1?>_stock/recommend/portfolio" class="more">종목 <span><?=$pf_count?>개</span><img src="/img/more_Black.png" alt="더보기"></a>
                    </div>

                    <?php if(is_array($portfolio_list) && sizeof($portfolio_list) > 0) :?>
					<div class="ptfo_datatable">
						<table cellspacing="0" border="1" class="tableRanking ptfo_table">
							<tbody>
                                <?php foreach($portfolio_list as $portfolio) :
									$class = 'decrease';
									if($portfolio['rc_rate_str'] > 0) {
										$class = 'increase';
									}
								?>
								<tr>
									<td class="name">
									<?php if($this->session->userdata('is_paid')===FALSE) :?>
									<a href="javascript:fnLogin();"><?=iconv_substr($portfolio['tkr_name'], 0, 1, 'utf-8')?><span class="remark"><div class="txt_filter size_M"><i></i><i></i><i></i><i></i></div></span></a>
									<?php else :?>
										<a href="/<?=X1?>_stock/recommend_view/<?=$portfolio['rc_id']?>"><?=$portfolio['tkr_name']?></a>
									<?php endif;?>
									</td>
									<td class="num">
										<span class=""><?=$this->common->set_pricepoint($portfolio['rc_close'], '1')?></span>
										<span class="<?=$class?> pp"><?=$this->common->set_pricepoint($portfolio['rc_rate_str'], '2')?></span>
									</td>
									<td class="num">
										<span class="yield">수익률</span>
										<span class="<?=($portfolio['rc_profit_rate']>0) ? 'increase':'decrease'?>"><?=$this->common->set_pricepoint($portfolio['rc_profit_rate'], '1')?><b>%</b></span>
									</td>
								</tr>
								<?php endforeach;?>

							</tbody>
						</table>
					</div>
					<?php endif;?>
                    <!-- //ptfo_recom -->

                    <?php if(isset($winner_list) && is_array($winner_list)) :?>
                    <!-- 승부주 -->
                    <div class="main_mid game_area">
                        <h3 class="title"><a href="/<?=X1?>_stock/winner">승부주</a> <span class="remark">- 스탁히어로 알고리즘 종목추천</span></h3>
                        <a href="/<?=X1?>_stock/winner" class="more"><img src="/img/more_Black.png" alt="더보기"></a>
                        <div class="gametable_box">
                            <ul class="game_th">
                                <li>추천가</li>
                                <li>목표가</li>
                                <li>수급분석</li>
                            </ul>
                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <tbody>
                                <?php $win=0; foreach($winner_list as $winner) :?>

                                    <tr>
                                        <td class="title">
                                        <?php if($this->session->userdata('is_paid')===TRUE || $win<1) :?>
                                        <a href="/<?=X1?>_search/invest_charm/<?=$winner['win_ticker']?>"><?=$winner['tkr_name']?><span class="ticker"><?=$winner['win_ticker']?>
                                        <?php else :?>
                                        <a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><?=iconv_substr($winner['tkr_name'], 0, 1, 'utf-8')?><span class="remark"><div class="txt_filter size_M"><i></i><i></i><i></i><i></i></div></span><span class="ticker"><span class="remark"><div class="txt_filter size_S"><i></i><i></i><i></i><i></i></div></span></span>
                                        <?php endif;?>
                                        </span></a>
                                        </td>
                                        <td class="num">
                                            <span><?=$this->common->set_pricepoint(number_format($winner['win_close'], 2), '1')?></span>
                                        </td>
                                        <?php if($this->session->userdata('is_paid')===TRUE || $win<1) :?>
                                        <td class="num">
                                            <span>
                                            <?php if(isset($winner['win_fairvalue']) && $winner['win_fairvalue']>0 && round((($winner['win_fairvalue']/$winner['win_close'])-1)*100) >= 30) :?>
                                            <?=$this->common->set_pricepoint(number_format($winner['win_fairvalue'], 2), '1')?>
                                            <?php else :?>
                                            <?=$this->common->set_pricepoint(number_format($winner['win_rc_price'], 2), '1')?>
                                            <?php endif;?>
                                            </span>
                                        </td>
                                        <?php else :?>
                                        <td class="prm_lock"><span><a href="javascript:fnLogin();"><!--<a href="#" data-modal="modal-3" class="btn_free md-trigger">--><img src="/img/prm_lock_mint.png" alt="잠김"></a></span></td>
                                        <?php endif;?>
                                        <td class="trans up"><i></i><span>강세</span></td>
                                    </tr>
                                <?php $win++; endforeach;?>

                                </tbody>
                            </table>

                        </div>
                        <!-- //gametable_box -->
                    </div>
                    <!-- //game_area -->
                    <?php endif;?>

                    <?php include_once X1_INC_PATH.'/main_contents_banner.php'; ?>

                    <?php if($this->session->userdata('is_login')===FALSE):?>
                    <!-- 20200611 로그인만해도 3일 무료 체험! 이벤트 배너 -->
                    <div class="middle_banner event main_event">
                        <a href="javascript:fnLogin();" class="link_banner"><img src="/img/banner_20200611.png" alt="로그인만해도 3일 무료 체험!"></a>
                    </div>
                    <!-- //middle_banner -->
                    <?php endif;?>

                </div>
                <!-- //main_top -->
                <?php endif;?>
                <?php endif;?>
            </div>
            <!-- //eventPicks_area -->

            <div class="main_mid catch_area">
                <div class="bg_box">
                    <h3 class="title"><a href="/<?=X1?>_stock/catch_info">종목캐치</a></h3>
                    <p class="txt">나의 취향에 맞는 종목</p>
                    <a href="/<?=X1?>_stock/catch_info" class="more"><img src="/img/more_white.png" alt="더보기"></a>
                </div>
                <!-- //bg_box -->

                <?php if($this->session->userdata('is_paid')===TRUE) :?>
                <div class="list_area d01">
                    <div class="swiper-container catchSwiper">
                        <div class="swiper-wrapper">
                            <?php foreach($catch_ticker_data as $key => $val) : ?>
                            <?php
                                $class = 'decrease';
                                if($val['ticker']['tkr_rate'] > 0) {
                                    $class = 'increase';
                                }
                            ?>
                            <div class="swiper-slide">
                                <ul class="list">
                                    <li class="title"><a href="/<?=X1?>_search/invest_charm/<?=$val['ticker']['tkr_ticker']?>"><?=$val['ticker']['tkr_name']?><span class="ticker"><?=$val['ticker']['tkr_ticker']?></span></a></li>
                                    <li class="num">
                                        <span><?=$this->common->set_pricepoint($val['ticker']['tkr_close'], '1')?></span>
                                        <span class="<?=$class?>"><?=$this->common->set_pricepoint($val['ticker']['tkr_rate_str'], '2')?></span>
                                    </li>
                                    <?php if($val['m_v_fairvalue3']>0) :?>
                                    <li class="recom"><span>적정주가 <strong><?=$val['m_v_fairvalue3']?></strong></span></li>
                                    <?php else :?>
                                    <li class="recom"><span>적정주가 N/A</span></li>
                                    <?php endif;?>
                                </ul>
                                <!-- //list -->
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
                <!-- //list_area -->
                <?php else :?>
                <div class="prm_div weeks_free">
                    <div class="box">
                        <div class="left">
                            <p class="title"><i></i>취향저격의 투자매력이 높은 종목을 ‘캐치’하세요! </p>
                            <p class="txt">내가 선호하는 종목을 파악하여 유사한 종목을 추천합니다. </p>
                        </div>
                        <div class="right">
                            <p><a href="/<?=X1?>_stock/catch_info" class="btn_free">추천종목 확인<i></i></a></p>
                        </div>
                    </div>
                </div>
                <?php endif;?>

            </div>
            <!-- //catch_area -->

            <div class="main_mid event_recipe">
                <h3 class="title"><a href="/<?=X1?>_stock/recipe_intro">투자레시피</a></h3>
                <a href="/<?=X1?>_stock/recipe_intro" class="more"><img src="/img/more_Black.png" alt="더보기"></a>

                <div class="tabsArea">
                    <span><!-- 화살표를 위한 span --></span>
                    <div class="tab_scr">
						<ul class="tabs recipe_tabs">
							<li class="active" rel="tab1"><span>최근실적</span></li>
							<li rel="tab2"><span>투자매력</span></li>
							<li rel="tab3"><span>배당주</span></li>
							<li rel="tab4"><span>성장주</span></li>
							<li rel="tab5"><span>슈퍼스톡</span></li>
							<li rel="tab6"><span>소비자독점주</span></li>
						</ul>
                    </div>
                    <!-- //tab_scr -->

                    <div class="tab_container">
                        <!-- 최근실적 -->
                        <div id="tab1" class="tab_content">
                            <div class="tableth_box">
                                <ul class="game_th">
                                    <li>발표순이익</li>
                                    <li>전년대비</li>
                                </ul>
                                <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                    <tbody>
		                            <?php $rc=0; foreach($recent_report as $val) : ?>
									<?php if($rc>4) break;?>
                                        <tr>
                                            <td class="title t_short"><a href="/<?=X1?>_search/invest_charm/<?=$val['tkr_ticker']?>"><?=$val['tkr_name']?><span class="ticker"><?=$val['tkr_ticker']?></span></a></td>
                                            <td class="num">
												<span><?=$this->common->set_pricepoint($val['tkr_close'], '1')?></span>
												<span class="<?=($val['tkr_rate'] > 0) ? 'increase' : 'decrease'?>"><?=$this->common->set_pricepoint($val['tkr_rate_str'], '2')?></span>
                                                <!-- increase 증가, decrease 감소 -->
                                            </td>
                                            <td class="num profit">
                                                <span><?=number_format($val['sf1_netinccmnusd']/1000000)?></span>
                                                <span class="hit">백만달러</span>
                                            </td>
                                            <td class="num last_year">
                                                <span class="<?=($recent_report_rates_pm[$val['tkr_ticker']] > 0) ? 'increase' : 'decrease'?>"><?=$this->common->set_pricepoint($recent_report_rates[$val['tkr_ticker']], '2')?></span>
                                            </td>
                                        </tr>
		                            <?php $rc++; endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- 투자매력 -->
                        <div id="tab2" class="tab_content">
                            <div class="tableth_box">
                                <ul class="game_th">
                                    <li class="ths_2">투자매력점수</li>
                                </ul>
                                <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                    <colgroup>
                                        <col width="100px">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <tbody>
                                        <?php foreach($all_total_score as $row) : ?>
                                        <tr>
                                            <td class="title"><a href="/<?=X1?>_search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                            </td>
                                            <td class="num">
                                                <span><?=$this->common->set_pricepoint($row['tkr_close'], '1')?></span>
                                                <span class="<?=$row['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$this->common->set_pricepoint($row['tkr_rate_str'], '2')?></span>
                                            </td>
                                            <td class="score"><span><?=$row['m_biz_total_score']?><b>점</b></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 배당매력주 -->
                        <div id="tab3" class="tab_content">
                            <div class="tableth_box">
                                <ul class="game_th">
                                    <li class="ths_2">배당수익률%</li>
                                </ul>
                                <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                    <colgroup>
                                        <col width="100px">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <tbody>
                                        <?php foreach($dividend as $row) : ?>
                                        <tr>
                                            <td class="title"><a href="/<?=X1?>_search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                            </td>
                                            <td class="num">
                                                <span><?=$this->common->set_pricepoint($row['tkr_close'], '1')?></span>
                                                <span class="<?=$row['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$this->common->set_pricepoint($row['tkr_rate_str'], '2')?></span>
                                            </td>
                                            <td class="num last_year"><span class="increase"><?=number_format($row['sf1_divyield']*100, 2)?> <b>%</b></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 이익성장주 -->
                        <div id="tab4" class="tab_content">
                            <div class="tableth_box">
                                <ul class="game_th">
                                    <li class="ths_2">순이익성장률%</li>
                                </ul>
                                <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                    <colgroup>
                                        <col width="100px">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <tbody>
                                        <?php foreach($growth as $row) : ?>
                                        <tr>
                                            <td class="title"><a href="/<?=X1?>_search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                            </td>
                                            <td class="num">
                                                <span><?=$this->common->set_pricepoint($row['tkr_close'], '1')?></span>
                                                <span class="<?=$row['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$this->common->set_pricepoint($row['tkr_rate_str'], '2')?></span>
                                            </td>
                                            <td class="num last_year"><span class="increase"><?=number_format($row['m_g_epsgr'], 2)?> <b>%</b></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 슈퍼스톡 -->
                        <div id="tab5" class="tab_content">
                            <div class="tableth_box">
                                <ul class="game_th">
                                    <li class="ths_2">5년 ROE%</li>
                                </ul>
                                <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                    <colgroup>
                                        <col width="100px">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <tbody>
                                        <?php foreach($total_score as $row) : ?>
                                        <tr>
                                            <td class="title"><a href="/<?=X1?>_search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                            </td>
                                            <td class="num">
                                                <span><?=$this->common->set_pricepoint($row['tkr_close'], '1')?></span>
                                                <span class="<?=$row['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$this->common->set_pricepoint($row['tkr_rate_str'], '2')?></span>
                                            </td>
                                            <td class="num last_year"><span class="increase"><?=number_format($row['m_g_roe'], 2)?> <b>%</b></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 소비자독점주 -->
                        <div id="tab6" class="tab_content">
                            <div class="tableth_box">
                                <ul class="game_th">
                                    <li class="ths_2">영업이익률%</li>
                                </ul>
                                <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                    <colgroup>
                                        <col width="100px">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <tbody>
                                        <?php foreach($moat as $row) : ?>
                                        <tr>
                                            <td class="title"><a href="/<?=X1?>_search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                            </td>
                                            <td class="num">
                                                <span><?=$this->common->set_pricepoint($row['tkr_close'], '1')?></span>
                                                <span class="<?=$row['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$this->common->set_pricepoint($row['tkr_rate_str'], '2')?></span>
                                            </td>
                                            <td class="num last_year"><span class="increase"><?=number_format($row['sf1_opmargin'], 2)?> <b>%</b></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //event_recipe -->


            <div class="main_btm banner_area">
                <!-- //banner -->
                <?php include_once X1_INC_PATH.'/main_banner1.php'; ?>
                <!-- //banner -->
            </div>
            <!-- //main_btm //recipe_area -->
