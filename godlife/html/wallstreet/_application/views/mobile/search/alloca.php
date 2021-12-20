                <?=$ticker_header?>
<?php if($sec_ticker=='' && $pri_ticker=='') {?>
                <div>
                    <div class="alloca_star">
                        <h2 class="title">배당주 투자매력도</h2>
                        <div class="star_area">
                            <div class="starRev">
							<?php 
							for($i = 1 ; $i <= 5 ; $i++) { 
								if($mri_data['m_biz_dividend_stars'] >= $i) {
									echo '<span class="starR on">별1</span>';
								}
								else {
									if($i-$mri_data['m_biz_dividend_stars'] <= 0.5) {
										echo '<span class="starR on half">별1</span>';
									}
									else {
										echo '<span class="starR">별1</span>';
									}
								}
							}
							?>
                            </div>
                        </div>
                        <div class="star_ranking">
							<?php if($part_page == '') {?>
                            <a href="/stock/recipe" class="">상위 <strong><?=$dividend_rank_rate?></strong>% <img src="/img/more_Black@2x.png" alt="더보기"></a>
							<?php }else {?>
							상위 <strong><?=$dividend_rank_rate?></strong>% 
							<?php }?>

                        </div>

                    </div>
                    <!-- //alloca_star -->


                    <!-- //alloca_star -->

                    <div class="alloca_info">
						<?php if(isset($dividend)&&$dividend):?>
                        <div class="info"><p><?=$dividend;?></p></div>
						<?php endif;?>
						<?php if($dy_count>0):?>
                        <div class="term">
                            이 종목은 주당배당금이 <strong>최근 <?=$dy_count;?>년 연속 상승</strong>하고 있습니다. (연간 기준)
                        </div>
						<?php endif;?>
                        <div class="table_area">
                            <table cellspacing="0" border="1" class="tableRanking table_alloca">
                                <colgroup>
                                    <col width="60px">
                                    <col width="">
                                    <col width="60px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th class="title">배당수익률</th>
                                        <td class="num"><?=$last_mry['sf1_divyield']?></td>                                        
                                    </tr>
                                    <tr>
                                        <th class="title">주당배당금</th>
                                        <td class="num"><?=$last_mry['sf1_dps']?>달러</td>                                        
                                    </tr>
                                    <tr>
                                        <th class="title">잉여현금성장률</th>
                                        <td class="num"><?=$mri_data['m_d_fcfgr']?>%</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table cellspacing="0" border="1" class="tableRanking table_alloca">
                                <colgroup>
                                    <col width="60px">
                                    <col width="">
                                    <col width="60px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>                                        
                                        <th class="title">배당성향</th>
                                        <td class="num"><?=$last_mry['sf1_payoutratio']?></td>
                                    </tr>
                                    <tr>
                                        <th class="title">순이익성장률</th>
                                        <td class="num"><?=$mri_data['m_d_epsgr2']?>%</td>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>

                        <?php if($mri_data['m_biz_dividend_stars'] > 0) : ?>
                        <div class="allocachart_area">
                            <h3 class="title">배당수익률</h3>
                            <div id="alloc_line_chart1" class="containeralloca1"></div>
                            <script>
							var params = [<?php foreach($chart_divyield_key as $val) echo "'".substr($val,0,4)."',";?>];
                            var value = [{
                                name: '배당수익률',
                                tooltip: {                    
                                    pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.2f} %</b><br/>'                    
                                },
                                data: [<?=implode(',', $chart_divyield)?>]
                            }];
                            SubSearchAllocLineChart('alloc_line_chart1', value, params)
                            </script>
                            <!-- 종목검색 - 배당 id = containeralloca1 -->

                            <h3 class="title">주당배당금</h3>
                            <div id="alloc_line_chart2" class="containeralloca1"></div>
                            <script>
							var params = [<?php foreach($chart_dps_key as $val) echo "'".substr($val,0,4)."',";?>];
                            var value = [{
                                name: '주당배당금',
                                tooltip: {                    
                                    pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.2f} 달러</b><br/>'                    
                                },
                                data: [<?=implode(',', $chart_dps)?>]
                            }];
                            SubSearchAllocLineChart('alloc_line_chart2', value, params, '달러')
                            </script>
                            <!-- 종목검색 - 배당 id = containeralloca1 -->

                            <h3 class="title">배당성향</h3>
                            <div id="alloc_line_chart3" class="containeralloca1"></div>
                            <script>
							var params = [<?php foreach($chart_payoutratio_key as $val) echo "'".substr($val,0,4)."',";?>];
                            var value = [{
                                name: '배당성향',
                                tooltip: {                    
                                    pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f} %</b><br/>'                    
                                },
                                data: [<?=implode(',', $chart_payoutratio)?>]
                            }];
                            SubSearchAllocLineChart('alloc_line_chart3', value, params)
                            </script>
                            <!-- 종목검색 - 배당 id = containeralloca1 -->

                        </div>
                        <!-- //allocachart_area -->
                        <?php endif; ?>
                    </div>
                </div>
                <!--<p class="dataLink">data from <a href="https://www.quandl.com/" target="_blank">Quandl and
                        Sharadar</a>
                </p>-->
            </div>
            <!-- //sub_mid -->
<?php }else{?>
	<!-- 종목검색 class = sub_search-->
	<div id="container" class="sub_search">
		<!-- 주요 콘텐츠 -->
		<div class="sub_mid nondata">
			<p class="nodata_guide"><strong><?=$sec_ticker;?></strong>의 상세 기업정보는 <strong><a href="/search/invest_charm/<?=$pri_ticker?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?=$pri_ticker?></a></strong> 종목에서 확인할 수 있습니다.</p>
		</div>
		<!-- //sub_mid nondata -->
	</div>
	<!-- //container -->
<?php }?>

