           	<?=$ticker_header?>
<?php if($sec_ticker=='' && $pri_ticker=='') {?>
			<div id="summary_line_chart" class="containersummary1"></div>
			<script>
			var params = [<?php foreach($close_chart_key as $val) echo "'".substr($val,5,5)."',";?>]
			SubSearchSummaryLineChart('summary_line_chart', [<?=$close_chart_value?>], params);
			</script>

            <!-- 종목검사 재무제표 class = containersummary1  -->            
            
            <!--<div class="sum_guide_box">
                <span class="txt_guide"><img src="/img/txt_guide@2x.png" alt="가이드보기"></span>-->
                <!-- 투자의견, 투자매력 가이드 레이어 -->
                <!--<div class="guide_box hide">
                    <strong class="title">! Check</strong>
                    <ul>
                        <li>- 저평가 우량주 : 고ROE 저PER, 고EPS 저PBR</li>
                        <li>- 배당주 : 주당배당금, 배당수익률, 주당순자산</li>
                    </ul>
                </div>-->
                <!-- //guide_box -->
            <!--</div>-->
            <!-- //sum_guide_box -->

			<div class="summary small">
				<!-- div 사이즈는 big, small  -->
				<div class="view_box">
					<p><?=nl2br($ticker['tkr_description'])?></p>
					<ul class="etc">
						<?php /*
						<li class="industry">산업 <?=$api_ticker['tkr_industry']?></li>
						<li class="home"><a href="<?=$api_ticker['tkr_companysite']?>" target="_blank"><?=$api_ticker['tkr_companysite']?></a></li>
						<li class="competitors">SEC 전자공시</li>
						<li class="link"><a href="<?=$api_ticker['tkr_secfilings']?>" target="_blank">바로가기</a></li>
						*/ ?>
						<li class="industry"> <span>산업</span><?=$ticker['tkr_industry']?></li>
						<?php
							$ticker['tkr_companysite'] = str_replace('http://','',$ticker['tkr_companysite']);
							$ticker['tkr_companysite'] = str_replace('https://','',$ticker['tkr_companysite']);
						?>
						<li class="home"></span><span>홈</span><!--<a href="<?=$ticker['tkr_companysite']?>" target="_blank">--><?=$ticker['tkr_companysite']?><!--</a>--></li>
						<!-- <li class="link"><span>sec전자공시</span> <a href="<?=$api_ticker['tkr_secfilings']?>" target="_blank">바로가기</a></li> -->

					</ul>
					<!-- //etc -->
					<!-- <span class="close show">펼치기 <i></i></span> -->
				</div>
				<!-- //view_box -->
			</div>




            <strong class="unit"><?php if(isset($ticker['tkr_lastpricedate'])&&$ticker['tkr_lastpricedate']) echo '* 기준 : '.date('y.m/d', strtotime($ticker['tkr_lastpricedate']));?></strong>
			<table cellspacing="0" border="1" class="tableRanking left guide_table">
			    <colgroup>
				<col width="50%">
				<col>
			    </colgroup>
			    <tbody>
				<tr>
                    <th scope="col" class="th_guide txt01"><span>시가총액</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>기본주식수x전일 종가</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=number_format($daily_data['dly_marketcap'])?><span class="unit">백만달러</span></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt01"><span>기업가치 EV</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>전일 시가총액+차입금-현금및현금성자산</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=number_format($daily_data['dly_ev'])?><span class="unit">백만달러</span></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt01"><span>주식수</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>가중평균희석주식수(미발표 기업은 가중평균주식수)</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td>
					<?php if(substr($ticker['tkr_category'], 0, 8)=='Domestic'){?>
					<?php	if($mrq_data['sf1_shareswadil']) echo $mrq_data['sf1_shareswadil']; else echo $mrq_data['sf1_shareswa'];?>
					<?php } else {?>
					<?=$mry_data['sf1_shareswadil']?>
					<?php }?>					
					<span class="unit">주</span></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt01"><span>주당배당금</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>최근년도 주당배당금</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=$mry_data['sf1_dps']?><span class="unit">달러</span></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt01"><span>배당수익률</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>최근년도 주당배당금/기말 주가</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=$mry_data['sf1_divyield']?></td>
				</tr>
			    </tbody>
            </table>
            <!-- 테이블 안내 툴팁 -->
            <div class="th_guide_hide txt01 sum">
                <div class="guide_box"><ul><li>기본주식수x전일 종가</li></ul></div><!-- 시가총액 -->
                <div class="guide_box"><ul><li>전일 시가총액+차입금-현금및현금성자산</li></ul></div><!-- 기업가치 EV -->
                <div class="guide_box"><ul><li>가중평균희석주식수(미발표 기업은 가중평균주식수)</li></ul></div><!-- 주식수 -->
                <div class="guide_box"><ul><li>최근년도 주당배당금</li></ul></div><!-- 주당배당금 -->
                <div class="guide_box"><ul><li>최근년도 주당배당금/기말 주가</li></ul></div><!-- 배당수익률 -->                
            </div>
            <!-- //th_guide_hide -->
			<table cellspacing="0" border="1" class="tableRanking right guide_table">
			    <colgroup>
				<col width="50%">
				<col>
			    </colgroup>
			    <tbody>
				<tr>
                    <th scope="col" class="th_guide txt02"><span>주가수익배수 PER</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>전일 시가총액/최근 4분기 합산 보통주순이익</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=number_format($daily_data['dly_pe'], 2)?><span class="unit">배</span></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt02"><span>주가순자산배수 PBR</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>전일 시가총액/최근 분기 자본총계</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=$mrt_data['sf1_equity'] > 0 ? number_format($daily_data['dly_pb'], 2).'<span class="unit">배</span>' : 'N/A'?></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt02"><span>자기자본이익률 ROE</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>최근 4분기 합산 보통주순이익/최근 4분기 평균자본</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=($mrt_data['sf1_roe'])?></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt02"><span>주당순이익 EPS</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>보통주순이익/가중평균희석주식수</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?=($mrt_data['sf1_epsdil'])?><span class="unit">달러</span></td>
				</tr>
				<tr>
                    <th scope="col" class="th_guide txt02"><span>주당순자산 BPS</span>
                        <div class="guide_box">                                        
                            <ul>
                                <li>자본총계/가중평균주식수</li>                                                                                        
                            </ul>
                        </div>
                    </th>
				    <td><?php if(substr($ticker['tkr_category'], 0, 8)=='Domestic') echo $mrq_data['sf1_bvps']; else echo $mry_data['sf1_bvps'];?><span class="unit">달러</span></td>
					
				</tr>
			    </tbody>
            </table>
                <!-- 테이블 안내 툴팁 -->
                <div class="th_guide_hide txt02 sum">                            
                    <div class="guide_box"><ul><li>전일 시가총액/최근 4분기 합산 보통주순이익</li></ul></div><!-- 주가수익배수 PER -->
                    <div class="guide_box"><ul><li>전일 시가총액/최근 분기 자본총계</li></ul></div><!-- 주가순자산배수 PBR -->
                    <div class="guide_box"><ul><li>최근 4분기 합산 보통주순이익/최근 4분기 평균자본</li></ul></div><!-- 자기자본이익률 ROE -->
                    <div class="guide_box"><ul><li>보통주순이익/가중평균희석주식수</li></ul></div><!-- 주당순이익 EPS -->
                    <div class="guide_box"><ul><li>자본총계/가중평균주식수</li></ul></div><!-- 주당순자산 BPS -->
                </div>
                <!-- //th_guide_hide -->
            
            	</div>
            	<!-- //tab_diagnosis_area -->
            </div>
            <!-- //sub_mid -->

            <div class="sub_mid financials_chart">
                <h4 class="title">재무제표</h4>
                <div class="tabs_menu" style="display: block;">
                    <span class="active" data-id="year">연간</span>
                    <span data-id="quarter">분기별</span>
                    <!--<strong class="unit">* 기준 : 19.12/24, 백만달러, 연환산</strong>-->
                    <strong class="unit"><?php if(isset($ticker_currency) && $ticker_currency) echo '* 기준 : '.$ticker_currency;?></strong>
                </div>
                <!-- //tabs_menu -->
                <?php 
                    // @ 연간 차트 
					$rev_year = explode(',',$mry_chart_key['sf1_revenue']);
					$ass_year = explode(',',$mry_chart_key['sf1_assets']);
					$ncf_year = explode(',',$mry_chart_key['sf1_ncfo']);

					//2020.08.26 변경 if(strtoupper($ticker['tkr_category'])=='ADR' || strtoupper($ticker['tkr_category']) =='ADR PRIMARY' || strtoupper($ticker['tkr_category'])=='CANADIAN' || strtoupper($ticker['tkr_category'])=='CANADIAN PRIMARY') {
					if( strstr(strtoupper($ticker['tkr_category']), 'ADR') || strstr(strtoupper($ticker['tkr_category']), 'CANADIAN') ) {

						$sf1_opinc = 'sf1_opinc_ori';
						$sf1_netinc = 'sf1_netinc_ori';
					}
					else {
						$sf1_opinc = 'sf1_opinc';
						$sf1_netinc = 'sf1_netinc';
					}
                ?>

                <div class="chartyear">
                    <div id="summary_column_year_chart1" class="containerfinancials1"></div>
                    <script>
					var tooltip = 'na';
					var chart_title = [<?php foreach($rev_year as $val) echo "'".substr($val,0,4)."',";?>];
                    var chart_value = [
                        {'name': '매출액', 'data': [<?=$mry_chart_value['sf1_revenue']?>]},
                        {'name': '영업이익', 'data': [<?=$mry_chart_value[$sf1_opinc]?>]},
                        {'name': '순이익', 'data': [<?=$mry_chart_value[$sf1_netinc]?>]}
                    ];
                    SubSearchSummaryColumnChart('summary_column_year_chart1', chart_value, chart_title, tooltip);</script>
                    <!-- 종목검사 재무제표 class = containerfinancials1  -->
                    <ul class="chart_legend">
                        <li><span><i></i>매출액</span></li>
                        <li><span><i></i>영업이익</span></li>
                        <li><span><i></i>순이익</span></li>
                    </ul>
                </div>
                <div class="chartyear">
                    <div id="summary_column_year_chart2" class="containerfinancials1"></div>
                    <script>
					var chart_title = [<?php foreach($ass_year as $val) echo "'".substr($val,0,4)."',";?>];
                    var chart_value = [
                        {'name': '자산총계', 'data': [<?=$mry_chart_value['sf1_assets']?>]},
                        {'name': '부채종계', 'data': [<?=$mry_chart_value['sf1_liabilities']?>]},
                        {'name': '자본총계', 'data': [<?=$mry_chart_value['sf1_equity']?>]}
                    ];
                    SubSearchSummaryColumnChart('summary_column_year_chart2', chart_value, chart_title, tooltip);</script>
                    <!-- 종목검사 재무제표 class = containerfinancials1  -->
                    <ul class="chart_legend">
                        <li><span><i></i>자산총계</span></li>
                        <li><span><i></i>부채종계</span></li>
                        <li><span><i></i>자본총계</span></li>
                    </ul>
                </div>
                <div class="chartyear">
                    <div id="summary_column_year_chart3" class="containerfinancials1"></div>
                    <script>
					var chart_title = [<?php foreach($ncf_year as $val) echo "'".substr($val,0,4)."',";?>];
                    var chart_value = [
                        {'name': '영업활동', 'data': [<?=$mry_chart_value['sf1_ncfo']?>]},
                        {'name': '투자활동', 'data': [<?=$mry_chart_value['sf1_ncfi']?>]},
                        {'name': '재무활동', 'data': [<?=$mry_chart_value['sf1_ncff']?>]}
                    ];
                    SubSearchSummaryColumnChart('summary_column_year_chart3', chart_value, chart_title, tooltip);</script>
                    <!-- 종목검사 재무제표 class = containerfinancials1  -->
                    <ul class="chart_legend">
                        <li><span><i></i>영업활동</span></li>
                        <li><span><i></i>투자활동</span></li>
                        <li><span><i></i>재무활동</span></li>
                    </ul>
                </div>

                <?php 
                    // @ 분기 차트 
					$rev_qrt = explode(',',$mrq_chart_key['sf1_revenue']);
					$ass_qrt = explode(',',$mrq_chart_key['sf1_assets']);
					$ncf_qrt = explode(',',$mrq_chart_key['sf1_ncfo']);
                ?>
                <div class="chartquarter" style="display:none;">
                    <div id="summary_column_quarter_chart1" class="containerfinancials1"></div>
                    <script>
					var chart_title = ['<?=substr($rev_qrt[0], 0, 7);?>', '<?=substr($rev_qrt[1], 0, 7);?>', '<?=substr($rev_qrt[2], 0, 7);?>'];
                    var chart_value = [
                        {'name': '매출액', 'data': [<?=$mrq_chart_value['sf1_revenue']?>]},
                        {'name': '영업이익', 'data': [<?=$mrq_chart_value[$sf1_opinc]?>]},
                        {'name': '순이익', 'data': [<?=$mrq_chart_value[$sf1_netinc]?>]}
                    ];
                    SubSearchSummaryColumnChart('summary_column_quarter_chart1', chart_value, chart_title, tooltip);</script>
                    <!-- 종목검사 재무제표 class = containerfinancials1  -->
                    <ul class="chart_legend">
                        <li><span><i></i>매출액</span></li>
                        <li><span><i></i>영업이익</span></li>
                        <li><span><i></i>순이익</span></li>
                    </ul>
                </div>
                <div class="chartquarter" style="display:none;">
                    <div id="summary_column_quarter_chart2" class="containerfinancials1"></div>
                    <script>
					var chart_title = ['<?=substr($ass_qrt[0], 0, 7);?>', '<?=substr($ass_qrt[1], 0, 7);?>', '<?=substr($ass_qrt[2], 0, 7);?>'];
                    var chart_value = [
                        {'name': '자산총계', 'data': [<?=$mrq_chart_value['sf1_assets']?>]},
                        {'name': '부채종계', 'data': [<?=$mrq_chart_value['sf1_liabilities']?>]},
                        {'name': '자본총계', 'data': [<?=$mrq_chart_value['sf1_equity']?>]}
                    ];
                    SubSearchSummaryColumnChart('summary_column_quarter_chart2', chart_value, chart_title, tooltip);</script>
                    <!-- 종목검사 재무제표 class = containerfinancials1  -->
                    <ul class="chart_legend">
                        <li><span><i></i>자산총계</span></li>
                        <li><span><i></i>부채종계</span></li>
                        <li><span><i></i>자본총계</span></li>
                    </ul>
                </div>
                <div class="chartquarter" style="display:none;">
                    <div id="summary_column_quarter_chart3" class="containerfinancials1"></div>
                    <script>
					var chart_title = ['<?=substr($ncf_qrt[0], 0, 7);?>', '<?=substr($ncf_qrt[1], 0, 7);?>', '<?=substr($ncf_qrt[2], 0, 7);?>'];
                    var chart_value = [
                        {'name': '영업활동', 'data': [<?=$mrq_chart_value['sf1_ncfo']?>]},
                        {'name': '투자활동', 'data': [<?=$mrq_chart_value['sf1_ncfi']?>]},
                        {'name': '재무활동', 'data': [<?=$mrq_chart_value['sf1_ncff']?>]}
                    ];
                    SubSearchSummaryColumnChart('summary_column_quarter_chart3', chart_value, chart_title, tooltip);</script>
                    <!-- 종목검사 재무제표 class = containerfinancials1  -->
                    <ul class="chart_legend">
                        <li><span><i></i>영업활동</span></li>
                        <li><span><i></i>투자활동</span></li>
                        <li><span><i></i>재무활동</span></li>
                    </ul>
                </div>
                
            </div>
            <!-- //sub_mid -->

            <div class="sub_mid competitors_table">
                <h4 class="tltle">경쟁사</h4>
                <span class="sum"><?=$api_ticker['tkr_industry']?></span>

                <span class="title_guide"><img src="/img/txt_guide@2x.png" alt="가이드보기"></span>
                <div class="guide_box hide">                                
                    <span class="clse">닫기</span>                        
                    <ul>
                        <li>경쟁사는 동일 업종 내 기업을 시가총액 규모로 그룹을 나누어(Mega, Large, Mid, Small, Micro, Nano) 동일 그룹에 속한 기업을 표시하고 있습니다. </li>
                    </ul>
                </div>
                
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
                            <th>매출액 <span>(백만달러)</span></th>
                            <th>PER<span>(배)</span></th>
                            <th>PBR<span>(배)</span></th>
                            <th>ROE<span>(%)</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(is_array($competitor) ? $competitor : array() as $cp) : ?>
                        <tr>
                            <td class="name"><?php if($part_page == ''){?><a href="/search/invest_charm/<?=$cp['tkr_ticker']?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?php }?><?=$ticker_info_map[$cp['tkr_ticker']]?>
                                <span class="ticker"><?=$cp['tkr_ticker']?></span><?php if($part_page == ''){?></a><?php }?></td>
                            <td><?=$cp['sf1_revenueusd']?></td>
                            <td><?=$cp['dly_pe']?></td><!-- increase 증가, decrease 감소 -->
                            <td><?=$cp['dly_pb']?></td>
                            <td><?=$cp['sf1_roe']?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
				<?php if($is_adr) :?>
                <p class="table_guide">* 매출액 : 연환산 기준</p>
                <p class="table_guide">* 최근 경쟁사 비교를 위해 USD값으로 일괄조정</p>
				<?php endif;?>
            </div>
            <!-- //sub_mid -->

<script>
$(function() {
    $('.financials_chart .tabs_menu span').on('click', function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        if($(this).data('id') == 'year') {
            $('div.chartyear').show();
            $('div.chartquarter').hide();
        } else {
            $('div.chartyear').hide();
            $('div.chartquarter').show();
        }
    });
});
</script>

<?php }else{?>
	<!-- 종목검색 class = sub_search-->
	<div id="container" class="sub_search">
		<!-- 주요 콘텐츠 -->
		<div class="sub_mid nondata">
			<p class="nodata_guide"><strong><?=$sec_ticker;?></strong>의 상세 기업정보는 <strong><?php if($part_page == ''){?><a href="/search/invest_charm/<?=$pri_ticker?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?php }?><?=$pri_ticker?><?php if($part_page == ''){?></a><?php }?></strong> 종목에서 확인할 수 있습니다.</p>
		</div>
		<!-- //sub_mid nondata -->
	</div>
	<!-- //container -->
<?php }?>