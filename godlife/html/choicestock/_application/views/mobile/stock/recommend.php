			<?php include_once INC_PATH.'/ticker_submenu.php'; ?>

			<?php if($tab == '1') :?>
            <div class="sub_top">
                <div class="">
                    <div class="txt_box">
                        <p class="txt">
                            <strong>투자포인트, 추천가, 목표가, 매도가</strong>를 포함한 종목 리포트를 제공합니다.<br>
                            실시간 리딩은 따로 제공하지 않으니, 종목 리포트를 참고하여 목표가, 손절가를 꼭 지켜주시기
                            바랍니다.
                        </p>
                    </div>
                    <!-- //txt_box -->
                </div>
                <!-- Swiper -->
                <div class="swiper-container recomSwiper">
                    <div class="swiper-wrapper">
                    <?php 
                        foreach($top_recom as $idx => $tr) : 
                            $class = 'decrease';
                            if($tr['ticker']['tkr_rate'] > 0) {
                                $class = 'increase';
                            }

							if(in_array($tr['rc_adjust'], array('U', 'D')) && $tr['rc_adjust_price'] > 0) :
								$tr['rc_goal_price'] = $tr['rc_adjust_price'];
							endif;
                    ?>
                        <div class="swiper-slide">
                            <div class="chart_area" data-id="<?=$tr['rc_id']?>">
                                <div class="chartData left_area">
                                    <h2 class="title" title="<?=$tr['ticker']['tkr_name_en']?>"><a href="/stock/recommend_view/<?=$tr['rc_id']?>"><?=$tr['ticker']['tkr_name']?></a></h2>
                                    <ul class="info">
                                        <li class="sum"><span class="eng"><?=$tr['rc_ticker']?></span> </li>
                                    </ul>
                                    <!-- //info -->
                                    <ul class="detail">
										<?php if($is_open === true) :?>
										<li class="num"><span class='sync_price' data-ticker='<?=$tr['ticker']['tkr_ticker']?>' data-render="((el, txt, info) => { var tmp = txt.split('.'); return tmp[0]+'.<b>'+tmp[1]+'</b>';})"><?=$this->common->set_pricepoint($tr['ticker']['tkr_close'], '1')?></span></li>
										<li class="per"><span class='<?=$class?> sync_diff_rate' data-ticker='<?=$tr['ticker']['tkr_ticker']?>' data-render="((el, txt, info) => { el.removeClass('increase'); el.removeClass('decrease'); var c= 'decrease'; if(parseFloat(txt) > 0) { c='increase'; txt = '+'+txt;} el.addClass(c);  return txt + '<b>%</b>'; })"><?=$this->common->set_pricepoint($tr['ticker']['tkr_rate_str'], '2')?></span>
                                        </li>
										<?php else :?>
                                        <li class="num"><?=$this->common->set_pricepoint($tr['ticker']['tkr_close'], '1')?></li>
                                        <li class="per">
                                            <span class="<?=$class?>"><?=$this->common->set_pricepoint($tr['ticker']['tkr_rate_str'], '2')?></span>
                                        </li>
										<?php endif;?>
									</ul>
                                    <!-- //detail -->
                                    <div id="top_recom<?=$idx?>" class="containerS1"></div><!-- 종목추천 id = containerS1 -->
                                    <script>SubRecomTopChart('top_recom<?=$idx?>', [<?=$tr['chart_value']?>]);</script>
                                </div>
                                <!-- //chartData -->

                                <div class="right_area">
                                    <!--  수익률(기준가-현재가 수익%) 표시 -->
                                    <div class="revenue_box" style='<?=($tr['rc_endtype'] == 'ING') ? '' : 'display: none;'?>'>
                                        <span class="title"><i></i> 목표가</span>
                                        <span class="percent"><i></i> <?=$tr['rc_goal_price']?></span>
                                    </div>
                                    <!-- //수익률(기준가-현재가 수익%) 표시 -->

                                    <!-- 목표가 달성시 -->
                                    <div class="attainment_box" style="<?=($tr['rc_endtype'] == 'SUCCESS') ? '' : 'display: none;'?>">
                                        <span class="title"><i></i> 목표가달성</span>
                                        <span class="percent"><i></i> <?=number_format((($tr['rc_goal_price']/$tr['rc_recom_price'])-1)*100,2)?><b>%</b></span>
                                    </div>
                                    <!-- //목표가 달성시 -->
                                    <table cellspacing="0" border="1" class="tableRanking data_attainment">
                                        <tbody>
                                            <tr>
                                                <th class="goal"><span>목표가</span></th>
                                                <td class="goal_num"><span><?=$this->common->set_pricepoint($tr['rc_goal_price'], '1')?></span></td>

                                            </tr>
                                            <tr>
                                                <th class="goal"><span>추천가</span></th>
                                                <td class="goal_num"><span><?=$this->common->set_pricepoint($tr['rc_recom_price'], '2')?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="t"><span>PER(배)</span></th>
                                                <td class="n"><span><?=number_format($tr['dly_pe'], 2)?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="t"><span>PBR(배</span></th>
                                                <td class="n"><span><?=number_format($tr['dly_pb'], 2)?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="t"><span>ROE(%)</span></th>
                                                <td class="n"><span><?=number_format($tr['sf1_roe']*100, 2)?></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- //chart_area -->
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                <!-- //swiper-container -->
            </div>
            <!-- //sub_top -->
			<?php else :?>
            <div class="sub_top">
                <div class="">
                    <div class="txt_box">
                        <p class="txt">
                            종목추천 및 실시간 리딩 서비스를 제공합니다.<br>
                            매수부터 매도까지 포트폴리오에 필요한 모든 서비스를 제공합니다.
                            <span class="port_time">(*포트 시작 : 2020. 03/01)</span>
                        </p>
                    </div>
                    <!-- //txt_box -->
                </div>
            </div>
            <!-- //sub_top -->
			<?php endif;?>

            <div class="sub_mid eventPicks_area">

				<?php if($tab == '1') : ?>
					
					<?php if($is_event === true) : ?>
					<div class="banner_1yearevent">
						<span class="cho_prm"><i></i>초이스스탁US 프리미엄</span>
						<span class="y_box"><img src="/img/banner/img_y_box.png" alt="이벤트"></span>
						<a href="/payment/choice" class="weeks_free">[첫달 900원!]</a>
					</div>
					<?php else :?>
					<?php if($this->session->userdata('is_paid')===FALSE || $this->session->userdata('free_notice') != '') :?>
					<div class="banner_prm">
						<span class="cho_prm"><i></i>초이스스탁US 프리미엄</span>
						<a href="/payment/choice" class="weeks_free">[첫달 3,000원!]</a>
					</div>
					<?php endif;?>
					<?php endif;?>
				<?php endif;?>

				<!-- 포트폴리오 -->
                <div class="ptfo_area">
					<?php if($tab=='portfolio') :?>
                    <div class="detail_data">
                        <div class="line">
                            <span class="th">편입종목</span>
                            <span class="td"><?=$pf_count?> <i>개</i></span>
                        </div>
                        <div class="line">
                            <span class="th">수익률</span>
                            <span class="td <?=($pf_profit>0)? 'increase':'decrease'?>"><?=$pf_profit;?><b>%</b></span>
                        </div>
                    </div>
					<?php endif;?>

					<?php if($this->session->userdata('is_paid')===FALSE) :?>
					<!-- 구글 에드센스 투자포인트 상단 -->
					<div style="margin:15px 15px 0; text-align: center;">                
						<!-- 디스플레이(수평) -->
						<ins class="resize_width_AD adsbygoogle"
							style="display:inline-block"
							data-ad-client="ca-pub-6864430327621783"
							data-ad-slot="9421426429"></ins>
						<script>
						window.onload = function() {
							(adsbygoogle = window.adsbygoogle || []).push({});
						}
						</script>						
					</div>
					<!-- //구글 에드센스 -->
					<?php endif;?>

                    <div class="tabsArea">
						<?php if($tab=='1') :?>
                        <span class="day"><?=date('y.m/d', strtotime(date('Ymd')))?> 기준</span>
                        <div class="tab_container">
                            <!-- 종목추천 -->
                            <div id="recomm_tab" class="tab_content">
							<?=$content_html?>
                            </div>
                        </div>
						<?php else :?>
                        <ul class="tabs recom_tabs">
                            <li class="new_recom active" rel="tab1"><span>편입종목</span></li>
                            <li class="ptfo" rel="tab2"><span>제외종목</li>
                        </ul>
                        <span class="day"><?=date('y.m/d', strtotime(date('Ymd')))?> 기준</span>
                        <div class="tab_container">
                            <!-- 편입종목 -->
                            <div id="tab1" class="tab_content">
                                <!-- 포트폴리오 테이블 -->
                                <table cellspacing="0" border="1" class="tableRanking sum_table">
                                    <colgroup>
                                        <col width="16px">
                                        <col width="">
                                        <col width="">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>주가</th>
                                            <th>목표가</th>
                                            <th>수익률</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($portfolio_list as $key=>$val) :
										if($val['rc_display_date'] > date('Y-m-d H:i:s')) continue;
										$class = 'decrease';
										if($val['rc_rate'] > 0) {
											$class = 'increase';
										}

										if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :
											$val['rc_goal_price'] = $val['rc_adjust_price'];
										endif;
									?>
                                        <tr>
                                            <td class="name">
											<?php if($this->session->userdata('is_paid')===FALSE) :?>
											<a href="#" data-modal="<?=($is_event === true) ? 'modal-4':'modal-3'?>" class="btn_free md-trigger"><?=iconv_substr($val['tkr_name'], 0, 1, 'utf-8')?><span class="remark"><div class="txt_filter size_M"><i></i><i></i><i></i><i></i></div></span><span class="ticker"><span class="remark"><div class="txt_filter size_S"><i></i><i></i><i></i><i></i></div></span></span></a></td>
											<?php else :?>
											<a href="/stock/recommend_view/<?=$val['rc_id']?>"><?=$val['tkr_name']?><span class="ticker"><?=$val['rc_ticker']?></span></a></td>
											<?php endif;?>
											<td class="num">
                                                <span class=""><?=$this->common->set_pricepoint($val['rc_close'], '1')?></span>
												<span class="<?=$class?> pp"><?=$this->common->set_pricepoint($val['rc_rate_str'], '2')?></span>
                                            </td>                                            
											<?php if($this->session->userdata('is_paid')===FALSE) :?>
											<td class="prm_lock"><span><img src="/img/prm_lock.png" alt="잠김"></span></td>
											<?php else :?>
											<td class="goal"><?=$this->common->set_pricepoint($val['rc_goal_price'], '1')?></td>
											<?php endif;?>
											
                                            <td class="num">
                                                <span class="<?=($val['rc_profit_rate']>0) ? 'increase':'decrease'?>"><?=$this->common->set_pricepoint($val['rc_profit_rate'], '1')?><b>%</b></span>
                                            </td>
                                        </tr>
									<?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 제외종목 -->
                            <div id="tab2" class="tab_content">
                                <!-- 포트폴리오 테이블 -->
                                <table cellspacing="0" border="1" class="tableRanking sum_table">
                                    <colgroup>
                                        <col width="16px">
                                        <col width="">
                                        <col width="">
                                        <col width="">
                                        <col width="">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>추천가</th>
                                            <th>매도가</th>
                                            <th>수익률</th>
                                        </tr>
                                    </thead>
									<?php foreach($exclude_list as $key=>$val) :
										if($val['rc_display_date'] > date('Y-m-d H:i:s') || in_array($val['rc_ticker'], $pf_ticker_list)) continue;
										$class = 'decrease';
										if($val['rc_rate'] > 0) {
											$class = 'increase';
										}

										if(in_array($val['rc_adjust'], array('U', 'D')) && $val['rc_adjust_price'] > 0) :
											$val['rc_goal_price'] = $val['rc_adjust_price'];
										endif;
									?>
                                        <tr>
                                            <td class="name">
											<?php if($this->session->userdata('is_paid')===FALSE) :?>
											<a href="#" data-modal="<?=($is_event === true) ? 'modal-4':'modal-3'?>" class="btn_free md-trigger"><?=$val['tkr_name']?><span class="ticker"><?=$val['rc_ticker']?></span></a></td>
											<?php else :?>
											<a href="/stock/recommend_view/<?=$val['rc_id']?>"><?=$val['tkr_name']?><span class="ticker"><?=$val['rc_ticker']?></span></a></td>
											<?php endif;?>
											<td class="goal"><?=$this->common->set_pricepoint($val['rc_recom_price'], '1')?></td>                                            
											<td class="num"><span class="">
												<?php if($val['rc_endtype'] == 'SELL') :?>	
												<?=$this->common->set_pricepoint($val['rc_mid_price'], '1')?>
												<?php elseif($val['rc_endtype'] == 'FAIL') :?>
												<?=$this->common->set_pricepoint($val['rc_giveup_price'], '1')?>
												<?php else :?>
												<?=$this->common->set_pricepoint($val['rc_goal_price'], '1')?>
												<?php endif;?>
												</span><span class="decrease pp"><?=date('y.m/d', strtotime($val['rc_enddate']))?></span>
											</td>
                                            <td class="num">
                                                <span class="<?=($val['rc_profit_rate']>0) ? 'increase':'decrease'?>"><?=$this->common->set_pricepoint($val['rc_profit_rate'], '1')?><b>%</b></span>
                                            </td>
                                        </tr>
									<?php endforeach;?>
                                </table>
                            </div>
                        </div>
						<?php endif;?>
                    </div>				
                </div>				
				<?php if($tab == '1') :?>
                <div class="btn_more" id="recommend_more">
                    <a href="javascript:;" onclick="view_more()"><i></i>더보기</a>
                </div>

				<script>
				
				var page = 1;
				var is_loading = false;
				function view_more() {
					if(is_loading) {
						return;
					}
					is_loading = true;
					
					page += 1;
					$.post('/stock/ajax_get_recommend_list', {'page': page}, function(res) {
						if($.trim(res).length) {
							$('#recomm_tab').append(res);
						} else {
							$('.btn_more').hide();
						}
						is_loading = false;
					});
				}
				
				$(function() {
					$('.sub_top .chart_area').on('click', function() {
						location.href = '/stock/recommend_view/' + $(this).data('id');
					});
				});
				</script>
				<?php endif;?>

				<?php if($is_event === true) :?>
				<div class="banner_1yearevent">
					<span class="cho_prm"><i></i>초이스스탁US 프리미엄</span>
					<span class="y_box"><img src="/img/banner/img_y_box.png" alt="이벤트"></span>
					<a href="/payment/choice" class="weeks_free">[첫달 900원!]</a>
				</div>
				<?php else :?>
					<?php if($this->session->userdata('is_paid')===FALSE || $this->session->userdata('free_notice') != '') :?>
					<div class="banner_prm">
						<span class="cho_prm"><i></i>초이스스탁US 프리미엄</span>
						<a href="/payment/choice" class="weeks_free">[첫달 3,000원!]</a>
					</div>
					<?php endif;?>
				<?php endif;?>

                <!-- //btn_more -->
            </div> <!-- //sub_mid -->

			<?php if($this->session->userdata('is_paid')===FALSE) :?>
			<!-- 구글 에드센스 본문 하단 -->
			<div style="margin:15px 15px 0; text-align: center;">                
				<!-- 디스플레이(사각) -->    
				<ins class="adsbygoogle" style="display:block"
					data-ad-client="ca-pub-6864430327621783"
					data-ad-slot="4092900518"
					data-ad-format="auto"
					data-full-width-responsive="true">
				</ins>
				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
			<!-- //구글 에드센스 -->
			<?php endif;?>