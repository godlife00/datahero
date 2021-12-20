	<script type="text/javascript">
	<!--
		function code_like(code) {

			var href;

			if ( code == '') {
				alert('종목코드가 없습니다.');
				return;
			}

			href = '/stocks/code_like/'+code;

			$.ajax({
				url : href,
				type : 'get',
				dataType : 'json',
				cache : false,
				success : function(data) {
					if (data.error) {
						alert(data.error);
						$('.attention').attr('class', 'attention');
						location.href='/auth/login?rd=<?=urlencode(str_replace('/index.php', '', $_SERVER["PHP_SELF"]));?>';
						return;
					}
					else if (data.success) {
						if(data.res == 'Y') {
							$('.attention').attr('class', 'attention like');
						}
						else {
							$('.attention').attr('class', 'attention');
						}
					}
				}
			});
		}

		function code_opinion(code, opt) {

			var href;

			<?php if(!$this->session->userdata('is_login')) {?>
				$('#ly_login').css('display', 'block');
				if(opt=='B') {
					$('#ly_edge').attr('class', 'edge_lft');
				}
				else {
					$('#ly_edge').attr('class', 'edge_cen');
				}
				return;
			<?php }?>

			if(code == '') {
				alert('종목코드가 없습니다.');
				return;
			}
			
			if( $("input[name=opinion]").val() && !$("input[name=change_opinion]").val() ) {
				alert('변경하기를 클릭한 후 선택해 주세요.');
				return;
			}

			href = '/stocks/code_opinion/'+code+'/'+opt;

			$.ajax({
				url : href,
				type : 'get',
				dataType : 'json',
				cache : false,
				success : function(data) {
					if (data.error) {
						alert(data.error);
						location.href='/auth/login?rd=<?=urlencode(str_replace('/index.php', '', $_SERVER["PHP_SELF"]));?>';
						return;
					}
					else if (data.success) {
						var mop = data.res['my_opinion']['mo_opinion'];
						var sell = data.res['sell_ratio'];
						var buy = data.res['buy_ratio'];

						if(sell>0) sell += '%'; else sell = '';
						if(buy>0) buy += '%'; else buy = '';

						if(mop == 'B') {
							$('.buying').attr('class', 'buying chk');
							$('.sell').attr('class', 'sell');
						}
						else if(mop == 'S') {
							$('.sell').attr('class', 'sell chk');
							$('.buying').attr('class', 'buying');
						}
						else {
							$('.buying').attr('class', 'buying');
							$('.sell').attr('class', 'sell');
						}

						$('#sell').html(sell);
						$('#buy').html(buy);

						$('.sellBar').css({"width":sell});
						$('.buyingBar').css({"width":buy});

						$('#buy_count').html('('+data.res['buy_opinion']+')');
						$('#sell_count').html('('+data.res['sell_opinion']+')');

						$("input[name=change_opinion]").val('');

						if(!$("input[name=opinion]").val()) {
							$("input[name=opinion]").val(mop);
						}

						$('#myOpinion').css('display', 'none');
						$('#opinion').css('display', 'inline-block');
					}
				}
			});
		}

		function chnage_opinion() {
			$('#opinion').css('display', 'none');
			$('#myOpinion').css('display', 'inline-block');
			$("input[name=change_opinion]").val('Y');
		}

		function login_close() {
			$('#ly_login').css('display', 'none');
		}

		function vLink(ticker, dimension) {
			var sdate = $('#filterDate_s').val();
			var edate = $('#filterDate_e').val();

			sdate = sdate.substring(0,4)+sdate.substring(5,7)+sdate.substring(8,10);
			edate = edate.substring(0,4)+edate.substring(5,7)+edate.substring(8,10);

			if( sdate >= edate ) {
				alert('시작일은 종료일 보다 작아야 합니다.');
				document.vchartForm.sDate.focus();
				return;
			}
			document.vchartForm.action = "/stocks/vchart/"+ticker+"/"+dimension;
			document.vchartForm.target = "_self";
			document.vchartForm.submit();
		}

	//-->
	</script>
	<div class="schChartTitle">
		<form name="myForm" action="post">
			<input type="hidden" name="opinion" value="<?php if(isset($opinion['my_opinion']->mo_opinion)) echo $opinion['my_opinion']->mo_opinion;?>">
			<input type="hidden" name="change_opinion" value="">
		</form>

		<div>
			<h2 class="title"><?=$ticker['tkr_name']?></h2>
			<ul class="info">
				<li class="sum"><span class="eng"><?=$ticker['tkr_ticker']?></span> 

		<?php if(sizeof($company_info) > 0) : ?>
		<span class="span2"><?=$company_info['cp_korname']?></span></li>
		<?php endif; ?>
				<li class="category"><?=$ticker['tkr_exchange']?></li>
				<li class="attention<?php if($like=='Y') echo ' like'; else echo '';?>" onClick="javascript:code_like('<?=$company_info['cp_ticker'];?>');">관심종목 표시</li><!-- like 로 구분 -->

			</ul>
			<!-- //info -->
		</div>

		<ul class="detail">
			<li class="num"><?=number_format($last_sep['sep_close'], 2)?></li>
			<li class="per">
			<?php
			if($last_sep['sep_diff_rate'] == 0) { 
				echo '<span class="increase">0.00 <span>(+0.00%)</span></span> <!-- increase 증가, decrease 감소 -->';
			} else if($last_sep['sep_diff_rate'] > 0) {
				echo '<span class="increase">+'.number_format($last_sep['sep_diff_price'], 2).' <span>(+'.$last_sep['sep_diff_rate'].'%)</span></span> <!-- increase 증가, decrease 감소 -->';
			} else {
				echo '<span class="decrease">'.number_format($last_sep['sep_diff_price'], 2).' <span>('.$last_sep['sep_diff_rate'].'%)</span></span> <!-- increase 증가, decrease 감소 -->';
			}
			?>
            </li>
			<!-- //투표결과(매수, 매도) : 최근3개월/ 전체기간의 다수 투표 항목 -->
			<li class="day"><?=date('y.m/d', strtotime($last_sep['sep_date']))?>, USD</li>
		</ul>

                    <!-- 나의 투자의견은? -->
                <ul class="detail myOpinion" id="myOpinion" style='display: <?php if(!isset($opinion['my_opinion']->mo_opinion)) echo 'inline-block'; else echo 'none';?>'> 
                    <li class="myOpinion comment"><i></i> 나의 <b>투자의견</b>은?</li>
                    <li class="myOpinion deal">
                        <span class="buying<?php if(isset($opinion['my_opinion']->mo_opinion) && $opinion['my_opinion']->mo_opinion=='B') echo ' chk';?>" onClick="javascript:code_opinion('<?=$company_info['cp_ticker'];?>', 'B');"><i></i>매수</span> <!-- chk 로 구분 -->
                        <span class="sell<?php if(isset($opinion['my_opinion']->mo_opinion) && $opinion['my_opinion']->mo_opinion=='S') echo ' chk';?>" onClick="javascript:code_opinion('<?=$company_info['cp_ticker'];?>', 'S');"><i></i>매도</span>
                        <!-- 로그인 말풍선 -->
                        <div id= "ly_login" class="ly_help hide"> <!-- 기본 hide로 숨김, 비로그인시 class = view 로 변경 -->
                            <strong>주요활동분야란?</strong>
                            <p>로그인 후, 투자의견을 남겨주세요</p>
                            <div class="link"><a href="/auth/login?rd=<?=urlencode(str_replace('/index.php', '', $_SERVER["PHP_SELF"]));?>" class="login">로그인</a> <a href="javascript:login_close();" class="login">닫기</a></div>                        
                            <div id="ly_edge" class="edge_lft"></div><!-- edge_lft, edge_cen, edge_rgt -->
                        </div>
                        <!-- //로그인 말풍선 -->
                    </li>
                    <!-- // 나의 투자의견은? -->                                        
                    <!-- 투표결과(매수, 매도) : 최근3개월/ 전체기간의 다수 투표 항목 -->
                    <!-- class : opinion -->                                        
                </ul>
                    
                 <!-- 투자의견 -->
                <ul class="detail opinion" id="opinion" style='display: <?php if(!isset($opinion['my_opinion']->mo_opinion)) echo 'none'; else echo 'inline-block';?>'>                    
                    <li class="opinion comment"><i></i> <b>투자의견</b></li>                    
                    <li class="opinion months">
                        <div class="skillbarArea">
                            <span class="inbox"><img src="http://menu.itooza.com/globalstock/img/icon/buying_chk.png" alt="매수"></span>
                            <div class="zt-skill-bar">
                                <div class="buyingBar" style="width: <?php if(isset($opinion['buy_ratio'])) echo $opinion['buy_ratio']; else echo '0';?>%"><span id="buy"><?php if(isset($opinion['buy_ratio'])&&$opinion['buy_ratio']>0) echo $opinion['buy_ratio'].'%'; else echo '';?></span></div>
                                <div class="sellBar" style="width: <?php if(isset($opinion['sell_ratio'])) echo $opinion['sell_ratio']; else echo '0';?>%"><span id="sell"><?php if(isset($opinion['sell_ratio'])&&$opinion['sell_ratio']>0) echo $opinion['sell_ratio'].'%'; else echo '';?></span></div>
                            </div>
                            <span class="inbox"><img src="http://menu.itooza.com/globalstock/img/icon/sell_chk.png" alt="매도"></span>
                        </div>
                        <!-- //skillbarArea -->
                        <div class="data">
                            <span id="buy_count">(<?=number_format($opinion['buy_opinion']);?>)</span>
                            <span>최근 3개월</span>
                            <span id="sell_count">(<?=number_format($opinion['sell_opinion']);?>)</span>
                        </div>
                    </li>
                    
                    <li class="opinion change">                        
                        <a href="javascript:chnage_opinion();">변경하기</a>
                    </li>  
				</ul>
                <!-- //detail -->
            </div>
            <!-- //schChartTitle -->            

<?php if( isset($vchart) && $vchart === true ) {?>
			<div class="vchart_mid">
<?php }?>
			<ul class="tabs">                
				<li rel="tab1"<?=$active_menu == 'summary' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/summary/<?=$ticker_code?>'">요약</li>
				<li rel="tab2"<?=$active_menu == 'invest' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/invest/<?=$ticker_code?>'">투자지표</li>
				<li rel="tab3"<?=$active_menu == 'financials' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/financials/<?=$ticker_code?>'">재무제표</li>
				<li rel="tab4"<?=$active_menu == 'vchart' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/vchart/<?=$ticker_code?>'">V차트</li>
				<li rel="tab5"<?=$active_menu == 'mri' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/mri/<?=$ticker_code?>'">주식MRI</li>
				<li rel="tab6"<?=$active_menu == 'competitors' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/competitors/<?=$ticker_code?>'">경쟁사</li>
				<li rel="tab7"<?=$active_menu == 'chart' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/chart/<?=$ticker_code?>'">주가차트</li>
				<li rel="tab8"<?=$active_menu == 'news' ? ' class="fix-active"' : ''?> onclick="location.href='/stocks/news/<?=$ticker_code?>'">뉴스</li>
				<li rel="tab9"<?=$active_menu == 'talk' ? ' class="fix-active"' : ''?>>토론</li>
			</ul>


