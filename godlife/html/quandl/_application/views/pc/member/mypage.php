			<script type="text/javascript">
			<!--
				function del_myitem(item) {
					if(item == '') {
						alert('선택한 종목이 없습니다.');
						return;
					}
					else {
						if( !confirm('선택하신 종목을 삭제하시겠습니까?') ) return;
							document.myitem.ticker.value = item;
							document.myitem.action = "/member/mypage/update_item";
							document.myitem.target = "_self";
			                document.myitem.submit();
					}
				}
			//-->
			</script>
			<h2 class="myPageTitle">마이페이지</h2>

			<div class="subDivision">
				<!-- subLeft -->
				<div class="subLeft">
					<form name="myitem" method="post">
					<input type="hidden" name="ticker" value="">
					</form>
					<div class="mapage_tabs">
						<ul class="tabs">
							<li rel="tab1" class="active active_fix" onClick="javascript:location.href='/member/mypage/mylist';"><!--관심종목--></li>
							<li rel="tab2"><!--스키리닝(오픈예정)--></li>
							<li rel="tab3"><!--종목쇼핑(오픈예정)--></li>
						</ul>
						<a href="https://login.itooza.com/news_membermodify.htm" class="linkMypage" target="_blank">회원정보ㆍ서비스가입 확인</a>
					</div>
					<!-- //mapage_tabs -->
					<div class="tableData">
						<div class="chkOnoff">
							<!--<label class="switch">
								<input type="checkbox">
								<span class="slider round"></span>
							</label>
							<p>관심종목 리포트 알림 <span>(플러스친구 필수)</span></p>-->
						</div>
						<!-- //chkOnoff -->

						<table cellspacing="0" border="1" class="tableColtype typeOrder bdrNone">
							<colgroup>
								<col width="200px">
								<col width="*" span="5">
								<col width="130px">
								<col width="38px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">종목</th>
									<th scope="col">주가</th>
									<th scope="col">등락율</th>
									<th scope="col"><b>PER</b><!--<i class="ascending"></i>--></th><!-- 오름차순 -->
									<th scope="col">PBR<!--<i class="descending"></i>--></th><!-- 내림차순 -->
									<th scope="col">ROE<!--<i class="ascending"></i>--></th><!-- 오름차순 -->
									<th scope="col"></th>
									<th scope="col"></th>
								</tr>
							</thead>
							<tbody>
							<?php
								if(is_array($list)) {
									foreach($list as $val) : 
							?>
								<tr>
									<td><a href="/stocks/summary/<?=$val['my_ticker'];?>" class="drop"><?=$val['my_korname'];?></a><span class="eng"><?=$val['my_usname'];?></span></td>
									<td><?=$my_ticker_info[$val['my_ticker']]['close'];?></td>
									<td class="<?=($my_ticker_info[$val['my_ticker']]['diff_rate']>0) ? 'increase' : 'decrease' ?>"><?=$my_ticker_info[$val['my_ticker']]['diff_rate'];?></td>
									<td><?=number_format($my_ticker_info_snd[$val['my_ticker']]['dly_pe'], 2);?></td>
									<td><?=number_format($my_ticker_info_snd[$val['my_ticker']]['dly_pb'], 2);?></td>
									<td><?=is_numeric($my_ticker_info_thi[$val['my_ticker']]['sf1_roe']) ? number_format($my_ticker_info_thi[$val['my_ticker']]['sf1_roe']*100, 2).'%' : $my_ticker_info_thi[$val['my_ticker']]['sf1_roe']?></td>
									<td class="category">
										<!--<span>종목쇼핑</span>
										<span>대가주</span>
										<span>스크리닝</span>
										<span>종목쇼핑</span>-->
									</td>
									<td><button class="btn_delete" onclick="javascript:del_myitem('<?=$val['my_ticker'];?>');"><img src="/img/globalstock/img/btn_delete.png" alt="삭제"></button></td>
								</tr>
							<?php 
									endforeach; 
								}	
							?>
							</tbody>
						</table>
					</div>
					<!-- //tableData -->

					<div class="paginate">
						<?= $paging_html ?>
					</div>

				</div>
				<!-- //subLeft -->

				<!-- subRight -->
				<div class="subRight">
					<div class="rightTop">
						<h1 class="rankingTitle">인기종목</h1>
						<table cellspacing="0" border="1" class="tableRanking">
							<colgroup>
								<col width="170px">
								<col width="50px">
							</colgroup>
							<tbody>
							<?php foreach($popular_ticker as $popVal) :?>
								<tr>
									<td class="title"><a href="/stocks/summary/<?=$popVal['ticker'];?>"><?=isset($ticker_korean_map[$popVal['ticker']]) ? $ticker_korean_map[$popVal['ticker']] : $popVal['ticker']?></a></td>
									<td class="per"><span class="<?=$popVal['diff_rate']>0 ? 'increase' : 'decrease'?>"><?=$popVal['diff_rate'];?></span></td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>

						<h1 class="rankingTitle">급등종목</h1>
						<table cellspacing="0" border="1" class="tableRanking">
							<colgroup>
								<col width="170px">
								<col width="50px">
							</colgroup>
							<tbody>

							<?php 
							$row_cnt=0; 
								foreach($top_ticker as $topVal) :
									if( ! isset($ticker_korean_map[$topVal['ticker']])) continue;
								$row_cnt++;
							?>
								<tr>
									<td class="title"><a href="/stocks/summary/<?=$topVal['ticker'];?>"><?=isset($ticker_korean_map[$topVal['ticker']]) ? $ticker_korean_map[$topVal['ticker']] : $topVal['ticker']?></a></td>
									<td class="per"><span class="<?=$topVal['diff_rate']>0 ? 'increase' : 'decrease'?>"><?=$topVal['diff_rate'];?></span></td>
								</tr>

                            <?php
								if($row_cnt >= 5) break;
								endforeach;
							?>

							</tbody>
						</table>

						<!-- 우측배너 광고 -->
						<div style="margin-top: 39px;">
							<!-- 사이드바-고정 -->
							<ins class="adsbygoogle"
								style="display:inline-block;width:257px;height:275px"
								data-ad-client="ca-pub-6896844206786605"
								data-ad-slot="8125936295">
							</ins>
							<script>
								(adsbygoogle = window.adsbygoogle || []).push({});
							</script>
							<!-- <a href="http://us.itooza.com/"><img src="/img/globalstock/img/temp_bannerRR.jpg" alt="투자를 쉽고 편리하게, 미국주식가이드"></a> -->
						</div>
					</div>
				</div>
				<!-- //subRight -->
			</div>
			<!-- //subDivision -->