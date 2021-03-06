			<!-- 주요 콘텐츠 -->
            <div class="loign_chk">
                <h2 class="login_title">회원로그인</h2>            
                <div class="id_chk">
                    <p class="sns_name"><?=$user_path=='N' ? '네이버' : '카카오'?> 이메일</p>
                    <p class="sns_chk"><?=$user_email;?></p>
                </div>      
                <!-- //id_chk -->
            </div>
            <!-- //loign_chk -->
			<form name="loginForm">
				<input type="hidden" name="user_path" value="<?=$user_path?>">
			</form>

            <div class="agree_area">
                <p class="txt">서비스 이용을 위해 약관을 확인하시고 동의해주세요.</p>
                <div class="agree_from">
                    <div class="label" id="terms_tab">
                        <p class="label_chk"><i></i>초이스스탁US 서비스 약관에 동의합니다.</p>                        
                        <a href="/member/terms"><span class="terms_btn">내용보기</span></a>
                    </div>
                    <div class="label" id="policy_tab">
                        <p class="label_chk"><i></i>개인정보 수집 및 이용에 동의합니다.</p>                        
                        <a href="/member/policy"><span class="terms_btn">내용보기</span></a>
                    </div>

					<div class="label" id="marketing_tab">
						<p class="label_chk"><i></i>뉴스레터, 특별 프로모션 정보를 수신합니다. (선택)</p>      
                    </div>

                    <div class="btnArea payBtn">                        
                        <a href="javascript:fnAgreeChk()" class="btn btn_save">동의<i></i></a>
                    </div>
                </div>
                <!-- //agree_from -->
            </div>
            <!-- //agree_area -->