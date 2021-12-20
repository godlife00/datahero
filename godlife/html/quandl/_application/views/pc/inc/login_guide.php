				<?php if($this->session->userdata('is_login') === false ){?>
                <p class="lockInfo"><span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span> <b>회원서비스</b>입니다. 회원은 베타 기간 동안 <i>서비스를 모두 무료로 이용</i>하실 수 있습니다.
                <a href="/auth/login?rd=<?=urlencode(str_replace('/index.php', '', $_SERVER["PHP_SELF"]));?>" class="login_trigger">[로그인]</a></p>
				<?php }?>
