            <!-- 주요 콘텐츠 -->
            <div class="step_area">
                <ul>
                    <li>1. 가입방법 선택</li>
                    <li>2. 결제하기</li>
                    <li class="active">3. 결제완료</li>
                </ul>
            </div>
            <!-- //step_area -->

            <div class="serviceStep">
                <div class="step_top">
                    <div class="payment_com">
                        <h2 class="login_title"><strong>초이스스탁US</strong> 정상적으로 결제가 <br>완료 되었습니다. </h2>
                    </div>
                    <!-- //payment_com -->
                </div>
                <!-- //step_top -->
            </div>
            <!-- //serviceStep -->

            <div class="payment_guide">
                <ul class="guide">
                    <li class="th">결제방법</li>
                    <li class="td_txt">신용카드</li>                    
                </ul>
                <ul class="guide">
                    <li class="th">결제일</li>
                    <li class="td_txt">월정기결제 - 매월 <?=substr($p_at_day, 6, 2)?>일</li>                    
                </ul>
                <ul class="guide">
                    <li class="th">결제금액</li>
					<?php if($is_first == TRUE) :?>
						<?php //if($is_event === true) :?>
						<li class="td_txt">이벤트가 &nbsp;&nbsp;&nbsp;<strong><?=($is_event === true)? number_format($pay_info['1']['event_price']):number_format($pay_info['1']['first_price'])?></strong>원 <span>(부가세포함)</span><span class="event_info">둘째 달 부터 정상가(<?=number_format($pay_info['1']['price'])?>원)로 결제됩니다.</span>
						<?php //else :?>
						<!--<li class="td_txt"><strong><?=(isset($p_at_eventprice) && $p_at_eventprice>0) ? number_format($p_at_eventprice) : number_format($price)?></strong>원 <span>(부가세포함)</span></li> -->
						<?php //endif;?>
					<?php else :?>
						<li class="td_txt"><strong><?=number_format($pay_info['1']['price'])?></strong>원 <span>(부가세포함)</span></li>                    
					<?php endif;?>
                </ul>
                <ul class="guide">
                    <li class="th">결제수단</li>
                    <li class="td_txt">신용카드 (<?=$cardno?>)</li>                    
                </ul>
            </div>
            <!-- //payment_guide -->

            <div class="payment_note">
                <ul class="note">
                    <li><span>결제 확인 및 취소/환불 신청은 '마이페이지'에서 확인</span>할 수 있습니다.<br>
                        <a href="/member/paylist" class="mod_btn">마이페이지</a></li>
                    <li>고객센터 <a href="tel:0262252300">02)6225-2300</a>, <a
                            href="mailto:hero@datahero.co.kr">hero@datahero.co.kr</a><br>
                        (평일 09:00~17:00 (주말, 공휴일 제외), 점심시간 (11:30~12:30)</li>
                </ul>

            </div>
            <!-- //payment_note -->

            <div class="link_home">
                <i></i><a href="/" class="link_home">홈으로 이동</a>
            </div>