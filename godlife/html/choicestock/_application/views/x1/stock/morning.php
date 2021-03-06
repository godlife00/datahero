			<div class="headerTop">
                <h1 class="headerLogo"><span>모닝브리핑</span></h1>
                <a href="javascript:history.back();" class="his_back"><img src="/img/icon_back.png" alt="뒤로가기"></a>
            </div>
			<div class="sub_top">
                <div class="">
                    <div class="txt_box">
                        <p class="txt">전일 미국증시 주요지수와 포트폴리오 주요소식, 시황을 매일 아침 초이스스탁US 프리미엄 회원들께 발송합니다.</p>
                    </div>
                    <!-- //txt_box -->
                </div>
            </div>
            <!-- //sub_top -->

            <div class="sub_mid research_board">
                <ul id="analysis_list" class="lst_type">
                    <?php $cnt=0; foreach($morning as $exp) : ?>
                    <li>
                        <dl class="lst_type2 none_img<?=($this->session->userdata('is_paid')===FALSE) ? ' lst_lock':''?>">
                            <dt class="tit">
							<?php if($this->session->userdata('is_paid')===FALSE) :?>
							<a href="javascript:fnLogin();">
							<?php else :?>
							<a href="/<?=X1?>_stock/morning_view/<?=$exp['mo_id']?>">
							<?php endif;?>
							<strong><?=$exp['mo_title']?></strong></a><p class="day"><?=date('y.m/d',strtotime($exp['mo_display_date']))?></p></dt>
                        </dl>
                    </li>
						<?php if($cnt==2 || $cnt==6) :?>
							<?php if($this->session->userdata('is_paid')===FALSE) :?>
							<!-- 구글 에드센스 목록 중간 -->
							<div>                
								<!-- 인피드 광고 -->
								<ins class="adsbygoogle"
									style="display:block"
									data-ad-format="fluid"
									data-ad-layout-key="-gy+4-8-1l+3n"
									data-ad-client="ca-pub-6864430327621783"
									data-ad-slot="4860094807">
								</ins>
								<script>
									window.onload = function() {
										(adsbygoogle = window.adsbygoogle || []).push({});
									}  
								</script>
							</div>
							<!-- //구글 에드센스 -->
							<?php endif;?>
						<?php endif;?>
                    <?php $cnt++; endforeach; ?>
                </ul>
                <div class="btn_more">
                    <a href="javascript:;" onclick="view_more()"><i></i>더보기</a>
                </div>
                <!-- //btn_more -->
            </div>
            <!-- //sub_mid -->

            <script>
            var page = 1;
            var is_loading = false;
            function view_more() {
                if(is_loading) {
                    return;
                }
                is_loading = true;
                
                page += 1;
                $.post('/<?=X1?>_stock/ajax_get_morning_list', {'page': page}, function(res) {
                    if($.trim(res).length) {
                        $('#analysis_list').append(res);
                    } else {
                        $('.btn_more').hide();
                    }
                    is_loading = false;
                });
            }
            </script>