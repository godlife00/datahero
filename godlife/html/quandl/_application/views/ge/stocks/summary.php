    <?php     
    $ticker_code = $ticker['tkr_ticker'];
    include_once dirname(__FILE__).'/submenu.php'; 
    ?>            

            <!-- 차트영역입니다 (mobile) -->
            <div class="chartSection chartSection_mobile">
                <!-- 차트영역입니다 #추가#-->
                <div id="sum_topchart_m" class="containerLine_1"></div>
            </div>
			<script>
				var sep_list = [];
				var sep_date = [];
				var sep_year = [];
				<?php foreach($sepdata as $sl) : ?>
					sep_list.push(['<?=date('y.m/d', strtotime($sl['sep_date']))?>',<?=floatval($sl['sep_close'])?> ]);
					sep_date.push(['<?=date('y.m/d', strtotime($sl['sep_date']))?>']);
					sep_year.push(['<?=substr($sl['sep_date'],0,4)?>']);
				<?php endforeach; ?>
				var mychart = Highcharts.chart('sum_topchart_m', {

					chart: {
						type: 'area',
						renderTo: 'sum_topchart_m',
						backgroundColor: {
							// linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
						},
						style: {
							fontFamily: "'Lato', 'Noto Sans KR'"
						},
						//height: 300,                
						plotBorderColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					colors: ["#e1474e"],
					title: {
					style: {
							'font-weight': "bold",
							color: '#E0E0E3',
							textTransform: 'uppercase',
							fontSize: '0',
						},
						text: null  
					},

					tooltip: {
						enabled: false,
					},

					xAxis: {
						title: {
							text: null
						},
						lineColor: null,
						minorGridLineWidth: 0,
						gridLineWidth: 0,
						alternateGridColor: null,
						showFirstLabel: false,
						tickColor: null,                
					},

					yAxis: [{
						title: {
							text: null
						},
					}, {
						title: {
							text: null
						},
						tickAmount: 5,
						lineColor: null,
						minorGridLineWidth: 0,                
						alternateGridColor: null,
						showFirstLabel: false,                
						opposite: true
					}],


					credits: {
						enabled: false,
					},

					legend: {
						enabled: false
					},

					exporting : {
						enabled: false
					},

					series: [{
						name: '',
		                yAxis: 1,
						data: sep_list.slice(0, 120)
					}],                            

					plotOptions: {
						area: {
							fillColor: {
								linearGradient: {
									x1: 0,
									y1: 0,
									x2: 0,
									y2: 1
								},
								stops: [
									[0, Highcharts.getOptions().colors[5]],
									[1, Highcharts.color(Highcharts.getOptions().colors[5]).setOpacity(0).get('rgba')]
								]
							},
						},
						series: {
							enableMouseTracking: false,
							label: {
								connectorAllowed: false
							},
							pointStart: null,
							marker: {
								enabled: false,
							}
						}
					},
				});
			</script>
            <!-- //차트영역입니다 (mobile) -->

            <div class="schChartArea">
                <div class="chartDate">
                    <div class="arc_left">
                        <table cellspacing="0" border="1" class="tableRowtype left">
                            <caption>상세 데이터</caption>
                            <colgroup>
                                <col width="50%">
                                <col width="">
                            </colgroup>
                                <tr>
                                    <th scope="col">시가총액</th>
                                    <td><?=number_format($last_daily['dly_marketcap'])?><span class="unit">백만달러</span></td>
                                </tr>
                                <tr>
                                    <th scope="col">기업가치 EV</th>
                                    <td><?=number_format($last_daily['dly_ev'])?><span class="unit">백만달러</span></td>
                                </tr>
                                <tr>
                                    <th scope="col">주식수</th>
                                    <td><?=($last_mrq['sf1_shareswadil'])?><span class="unit">주</span></td>
                                </tr>
                                <tr>
                                    <th scope="col">주당배당금</th>
                                    <td><?=number_format($last_mry['sf1_dps'], 2)?><span class="unit">달러</span></td>
                                </tr>
                                <tr>
                                    <th scope="col">배당수익률</th>
                                    <td><?=($last_mry['sf1_divyield'])?></td>
                                </tr>
                        </table>
                    </div>
                    <!-- //arc_left -->
                    <div class="arc_right">
                        <table cellspacing="0" border="1" class="tableRowtype right">
                            <caption>상세 데이터</caption>
                            <colgroup>
                                <col width="70%">
                                <col>
                            </colgroup>
                                <tr>
                                    <th scope="col">주가수익배수 PER</th>
                                    <td><?=number_format($last_daily['dly_pe'], 2)?><span class="unit">배</span></td>
                                </tr>
                                <tr>
                                    <th scope="col">주가순자산배수 PBR</th>
                                    <td><?=$last_mrt['sf1_equity'] > 0 ? number_format($last_daily['dly_pb'], 2).'배' : 'N/A'?></td>
                                </tr>
                                <tr>
                                    <th scope="col">자기자본이익률 ROE</th>
                                    <td><?=is_numeric($last_mrt['sf1_roe']) ? number_format($last_mrt['sf1_roe']*100, 2).'%' : $last_mrt['sf1_roe']?></td>
                                </tr>
                                <tr>
                                    <th scope="col">주당순이익 EPS</th>
                                    <td><?=number_format($last_mrt['sf1_epsdil'], 2)?><span class="unit">달러</span></td>
                                </tr>
                                <tr>
                                    <th scope="col">주당순자산 BPS</th>
                                    <td><?=number_format($last_mrq['sf1_bvps'], 2)?><span class="unit">달러</span></td>
                                </tr>
                        </table>
                    </div>
                    <!-- //arc_right -->

					<!-- 투자매력점수 -->
					<?php if($down_rand == '1') :?>
					<div class="invest_charm_b ic_pc imsi_div">
						<div class="chartData">
							<dl>
								<dt class="dt_title">투자매력점수</dt>
								<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
								<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
								<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
								<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
								<?php endif;?>
							</dl>
							<dl>
								<dt class="dt_title">적정주가</dt>
								<dd class="lock"><span><img src="/img/img_lock.png" alt="잠김"></span></dd>                                
							</dl>
							<dl>                                
								<dt class="dt_title"><span><strong>적중!</strong> 수익실현</span></dt>                                
								<dd class="proper">
									<span class="num increase"><?=$down_banner['ticker_revenue']?><b>%</b></span>
								</dd>
							</dl>
						</div>
						<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
					</div>
					<?php elseif($down_rand == '2') :?>
					<div class="invest_charm_b ic_pc imsi_div">
						<div class="chartData">
							<dl>
								<dt class="dt_title">투자매력점수</dt>
								<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
								<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
								<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
								<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
								<?php endif;?>
							</dl>
							<dl>
								<dt class="dt_title">적정주가</dt>
								<dd class="lock"><span><img src="/img/img_lock.png" alt="잠김"></span></dd>                                
							</dl>
							<dl>
								<dt class="dt_title">종목추천목표가</dt>                                
								<dd class="lock"><span><img src="/img/img_lock2.png" alt="잠김"></span></dd>                                
							</dl>
						</div>
						<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
					</div>
					<?php elseif($down_rand == '3') :?>
					<div class="invest_charm_b ic_pc imsi_div">
						<div class="chartData">
							<dl>
								<dt class="dt_title">투자매력점수</dt>
								<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
								<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
								<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
								<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
								<?php endif;?>
							</dl>
							<dl>
								<dt class="dt_title">배당매력</dt>
								<dd class="star">
									<div class="star_area">
										<div class="starRev">
											<?php 
											for($i = 1 ; $i <= 5 ; $i++) { 
												if($down_banner['valuation']['m_biz_dividend_stars'] >= $i) {
													echo '<span class="starR on">별1</span>';
												}
												else {
													if($i-$down_banner['valuation']['m_biz_dividend_stars'] <= 0.5) {
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
									<!-- //star_area -->
								</dd>
							</dl>
							<dl>
								<dt class="dt_title">적정주가</dt>
								<!-- <dt class="dt_title"><span><strong>적중!</strong> 수익실현</span></dt> -->                                
								<dd class="proper">
									<span class="num increase"><?=str_replace('.00', '.<b>00</b>', $down_banner['valuation']['m_v_fairvalue3'])?></span>
								</dd>
							</dl>
						</div>
						<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
					</div>
					<?php else :?>
						<?php if(is_array($down_banner['valuation']) && sizeof($down_banner['valuation'])>0) :?>
						<div class="invest_charm_b ic_pc imsi_div">
							<div class="chartData">
								<dl>
									<dt class="dt_title">투자매력점수</dt>
									<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
									<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
									<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
									<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
									<?php endif;?>
								</dl>
								<dl>
									<dt class="dt_title">배당매력</dt>
									<dd class="lock"><span><img src="/img/img_lock.png" alt="잠김"></span></dd>                                
								</dl>
								<dl>
									<dt class="dt_title">적정주가</dt>                                
									<dd class="lock"><span><img src="/img/img_lock2.png" alt="잠김"></span></dd>                                
								</dl>
							</div>
							<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
						</div>
						<?php endif;?>
					<?php endif;?>
					<!-- //투자매력점수 -->
                </div>
                <!-- //chartDate -->

                <!-- 차트영역입니다 (pc) -->
                <div class="chartSection chartSection_pc">

					<div class="chartTabsArea">
						<div class="chartTabs">
							<span onclick="changeChart(5)">5일</span>
							<span onclick="changeChart(20)">1개월</span>
							<span class="active" onclick="changeChart(120)">6개월</span>
							<span onclick="changeChart(240)">1년</span>
							<!-- <a href="chart.html" class="sizeCustom"><i></i>크게보기</a> -->
						</div>
					</div>
					<!-- //chartTabsArea -->
					<!-- 차트영역입니다 -->
					<div id="sum_topchart" class="containerLine_1"></div>

					<script>
						var sep_list = [];
						var sep_date = [];
						var sep_year = [];
						<?php foreach($sepdata as $sl) : ?>
							sep_list.push(['<?=date('y.m/d', strtotime($sl['sep_date']))?>',<?=floatval($sl['sep_close'])?> ]);
							sep_date.push(['<?=date('y.m/d', strtotime($sl['sep_date']))?>']);
							sep_year.push(['<?=substr($sl['sep_date'],0,4)?>']);
						<?php endforeach; ?>
						var mychart = Highcharts.chart('sum_topchart', {

							chart: {
								type: 'area',
								renderTo: 'sum_topchart',
								backgroundColor: {
									// linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
								},
								style: {
									fontFamily: "'Lato', 'Noto Sans KR'"
								},
								//height: 300,                
								plotBorderColor: null,
								plotBorderWidth: null,
								plotShadow: false
							},
							colors: ["#e1474e", "#ff3301"],
							title: {
							style: {
									'font-weight': "bold",
									color: '#E0E0E3',
									textTransform: 'uppercase',
									fontSize: '0',
								},
								text: null  
							},

							tooltip: {
								shared: true,
								pointFormat: '<span style="color:{series.color}"><b>{point.y:,.2f} 달러</b><br/>'
							},

							//_this : this,

							xAxis:[{
								categories:  sep_date.slice(0, 120),
								crosshair: true,
								labels: {
									enabled: true,      
									step : 10,              
								}
							}],

							yAxis: [{
								title: {
									text: null
								},
							}, {
								title: {
									text: null
								},
								tickAmount: 5,
								lineColor: null,
								minorGridLineWidth: 0,                
								alternateGridColor: null,
								showFirstLabel: false,                
								opposite: true
							}],

							credits: {
								enabled: false,
							},

							legend: {
								enabled: false
							},

							exporting : {
								enabled: false
							},

							series: [{
								name: '',
				                yAxis: 1,
								data: sep_list.slice(0, 120)
							}],                            

							plotOptions: {
								area: {
									fillColor: {
										linearGradient: {
											x1: 0,
											y1: 0,
											x2: 0,
											y2: 1
										},
										stops: [
											[0, Highcharts.getOptions().colors[5]],
											[1, Highcharts.color(Highcharts.getOptions().colors[5]).setOpacity(0).get('rgba')]
										]
									},
								},
								series: {
									label: {
										connectorAllowed: false
									},
									pointStart: null,
									marker: {
										enabled: false,
									},
									fillOpacity: 0.1
								},
							},
						});
					</script>
					<script type="text/javascript">
					   function changeChart(count) {
							var list = sep_list.slice(0, count).reverse();
							var datelist = sep_date.slice(0, count).reverse();
							var yearlist = sep_year.slice(0, count).reverse();
							if(count==240) {
								mychart.xAxis[0].update ({labels: { step : 48 }});
								mychart.xAxis[0].categories = yearlist;
							}
							else {
								if(count==5) {
									mychart.xAxis[0].update ({labels: { step : 0 }});
								}
								else if(count==20) {
									mychart.xAxis[0].update ({labels: { step : 4 }});
								}else {
									mychart.xAxis[0].update ({labels: { step : 25 }});
								}
								mychart.xAxis[0].categories = datelist;
							}
							mychart.series[0].setData(list);
						}
						changeChart(120);
					</script>
				</div>
                <!-- //차트영역입니다 (pc) -->

			</div>
            <!-- //schChartArea -->

            <div class="summary small"> <!-- div 사이즈는 big, small  -->
                <p><?=($company_info['cp_description'] != '') ? nl2br($company_info['cp_description']) : nl2br($company_info['cp_short_description'])?></p>
                <span class="close">더보기 <i></i></span> 
                <ul class="etc">
                    <li><span>섹터</span><strong><?=$ticker['tkr_sector']?></strong></li>
                    <li class="industry"> <span>산업</span> <?=$ticker['tkr_industry']?></li>
                    <li class="home"><span><img src="/img/icon/home.png" alt="홈" ></span>  <a href="<?=$ticker['tkr_companysite']?>" target="_blank"><?=$ticker['tkr_companysite']?></a></li>
                    <li class="link"><span> <img src="/img/icon/link.png" alt="링크"> </span><a href="<?=$ticker['tkr_secfilings']?>" target="_blank">SEC 전자공시</a></li>
                </ul>
                <!-- //etc -->
            </div>
            <!-- //summary -->

        <?php
        $tabs = array(
            '투자지표' => $fininvestindi_fields,
        );

        $tab_titles = array(
            '투자지표' => $fininvestindi_titles,
        );

    foreach($tabs as $field_title => $fields) :
        $field_title_map = $tab_titles[$field_title];
?>
            <div class="tableData bannerRight">
                <div class="tabsArea">
                    <h3 class="titleInvestment">투자지표</h3>
                    <ul class="tabs">
                        <li class="active" rel="tab1"><span>연간실적</span></li>
                        <li rel="tab2"><span>분기실적</span></li>
                    </ul>

                    <div class="tab_container">
                        <div id="tab1" class="tab_content">
                            <!-- pc 노출 테이블 table_pc -->
                            <table cellspacing="0" border="1" class="tableColtype sumtable table_pc">
                                <colgroup>
                                    <col width="22%">
                                    <col width="200px" span="8">
                                </colgroup>
                                <thead>
                                    <tr class="fntfmly_num">
                                        <?php $cnt=0; foreach(array_slice(array_keys($last_mry_list), 0, 8) as $yyyymm) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?> class="thbder300"><span><?=date('y.m/d', strtotime(str_replace('.','-',$yyyymm)))?></span></th>
                                        <?php $cnt++; endforeach; ?>
                                        <?php for($i = sizeof(array_slice(array_keys($last_mry_list), 0, 8)) ; $i < 8 ; $i++) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?>class="thbder300"><span>N/A</span></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // 손익계산서와 요약에서 다르게 표출하는 필드 덮어쓰기.
                                $field_title_map['sf1_ebitda'] = $fininvestindi_titles_sub['sf1_ebitda'];

                                foreach($fields as $depth => $key) : 
                                    $title = '';
                                    if(isset($field_title_map[$key])) {
                                        $title = $field_title_map[$key];
                                        $title = str_replace('(', ' (', $title);
                                    } else if(isset($fininvestindi_titles_sub[$key])) {
                                        $title = $fininvestindi_titles_sub[$key];
                                    }
                                ?>
                                    <tr>
                                        <td class="bdrRightW"><span title="<?=$title?>"><strong><?=$title?></strong></span></td>
                                        <?php $col = 0; foreach(array_slice($last_mry_list, 0, 8) as $yyyymm => $row) : ?>
                                        <?php 
                                            $col++;
                                        switch($key) {
                                            case 'sf1_opinc_ratio' :
                                                $row['sf1_opinc_ratio'] =  @number_format(intval(str_replace(',','',$row['sf1_opinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100  * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_opinc_ratio']) || $row['sf1_opinc_ratio'] == 'nan') {
													$row['sf1_opinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_opinc_ratio'] .= '';
												}
                                                break;
                                            case 'sf1_netinc_ratio' :
                                                $row['sf1_netinc_ratio'] = @number_format(intval(str_replace(',','',$row['sf1_netinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100 * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_netinc_ratio']) || $row['sf1_netinc_ratio'] == 'nan') {
													$row['sf1_netinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_netinc_ratio'] .= '';
												}
                                                break;
                                        }
                                        ?>
                                        <td class="<?=$col == 3 ? 'line' : ''?>"><?=str_replace('%','',$row[$key])?></td>
                                        <?php 
                                        endforeach; 
                                        // 최근 연간실적이 8개 미달 처리
                                        for($i = $col ; $i < 8 ; $i++) {
                                            $class = ($i == 2) ? 'line' : '';
                                            echo '<td class="'.$class.'">N/A</td>';
                                        }
                                        ?>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                                </tbody>
                            </table>

                            <!-- mobile 노출 테이블 table_mb -->
                            <table cellspacing="0" border="1" class="tableColtype sumtable table_mb">
                                <colgroup>
                                    <col width="20%">
                                    <col width="200px" span="5">
                                </colgroup>
                                <thead>
                                    <tr class="fntfmly_num">

                                        <?php $cnt=0; foreach(array_slice(array_keys($last_mry_list), 0, 5) as $yyyymm) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?> class="thbder300"><span><?=date('y.m/d', strtotime(str_replace('.','-',$yyyymm)))?></span></th>
                                        <?php $cnt++; endforeach; ?>
                                        <?php for($i = sizeof(array_slice(array_keys($last_mry_list), 0, 5)) ; $i < 5 ; $i++) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?>class="thbder300"><span>N/A</span></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // 손익계산서와 요약에서 다르게 표출하는 필드 덮어쓰기.
                                $field_title_map['sf1_ebitda'] = $fininvestindi_titles_sub['sf1_ebitda'];

                                foreach($fields as $depth => $key) : 
                                    $title = '';
                                    if(isset($field_title_map[$key])) {
                                        $title = $field_title_map[$key];
                                        $title = str_replace('(', ' (', $title);
                                    } else if(isset($fininvestindi_titles_sub[$key])) {
                                        $title = $fininvestindi_titles_sub[$key];
                                    }
                                ?>
                                    <tr>
                                        <td class="bdrRightW"><span title="<?=$title?>"><strong><?=str_replace('(','<br>(',$title)?></strong></span></td>

                                        <?php $col = 0; foreach(array_slice($last_mry_list, 0, 5) as $yyyymm => $row) : ?>
                                        <?php 
                                            $col++;
                                        switch($key) {
                                            case 'sf1_opinc_ratio' :
                                                $row['sf1_opinc_ratio'] =  @number_format(intval(str_replace(',','',$row['sf1_opinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100  * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_opinc_ratio']) || $row['sf1_opinc_ratio'] == 'nan') {
													$row['sf1_opinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_opinc_ratio'] .= '';
												}
                                                break;
                                            case 'sf1_netinc_ratio' :
                                                $row['sf1_netinc_ratio'] = @number_format(intval(str_replace(',','',$row['sf1_netinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100 * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_netinc_ratio']) || $row['sf1_netinc_ratio'] == 'nan') {
													$row['sf1_netinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_netinc_ratio'] .= '';
												}
                                                break;
                                        }
                                        ?>
                                        <td class="<?=$col == 3 ? 'line' : ''?>"><?=str_replace('%','',$row[$key])?></td>
                                        <?php 
                                        endforeach; 
                                        // 최근 연간실적이 5개 미달 처리
                                        for($i = $col ; $i < 5 ; $i++) {
                                            $class = ($i == 2) ? 'line' : '';
                                            echo '<td class="'.$class.'">N/A</td>';
                                        }
                                        ?>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="tab2" class="tab_content">
                            <!-- pc 노출 테이블 table_pc -->
                            <table cellspacing="0" border="1" class="tableColtype sumtable table_pc">
                                <colgroup>
                                    <col width="22%">
                                    <col width="200px" span="8">
                                </colgroup>
                                <thead>
                                    <tr class="fntfmly_num">

                                        <?php $cnt=0; foreach(array_slice(array_keys($last_mrq_list), 0, 8) as $yyyymm) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?> class="thbder300"><span><?=date('y.m/d', strtotime(str_replace('.','-',$yyyymm)))?></span></th>
                                        <?php $cnt++; endforeach; ?>
                                        <?php for($i = sizeof(array_slice(array_keys($last_mrq_list), 0, 8)) ; $i < 8 ; $i++) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?>class="thbder300"><span>N/A</span></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // 손익계산서와 요약에서 다르게 표출하는 필드 덮어쓰기.
                                $field_title_map['sf1_ebitda'] = $fininvestindi_titles_sub['sf1_ebitda'];

                                foreach($fields as $depth => $key) : 
                                    $title = '';
                                    if(isset($field_title_map[$key])) {
                                        $title = $field_title_map[$key];
                                        $title = str_replace('(', ' (', $title);
                                    } else if(isset($fininvestindi_titles_sub[$key])) {
                                        $title = $fininvestindi_titles_sub[$key];
                                    }
                                ?>
                                    <tr>
                                        <td class="bdrRightW"><span title="<?=$title?>"><strong><?=$title?></strong></span></td>
                                        <?php $col = 0; foreach(array_slice($last_mrq_list, 0, 8) as $yyyymm => $row) : ?>
                                        <?php 
                                            $col++;
                                        switch($key) {
                                            case 'sf1_opinc_ratio' :
                                                $row['sf1_opinc_ratio'] =  @number_format(intval(str_replace(',','',$row['sf1_opinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100  * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_opinc_ratio']) || $row['sf1_opinc_ratio'] == 'nan') {
													$row['sf1_opinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_opinc_ratio'] .= '';
												}
                                                break;
                                            case 'sf1_netinc_ratio' :
                                                $row['sf1_netinc_ratio'] = @number_format(intval(str_replace(',','',$row['sf1_netinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100 * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_netinc_ratio']) || $row['sf1_netinc_ratio'] == 'nan') {
													$row['sf1_netinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_netinc_ratio'] .= '';
												}
                                                break;
                                        }
                                        ?>
                                        <td class="<?=$col == 3 ? 'line' : ''?>"><?=str_replace('%','',$row[$key])?></td>
                                        <?php 
                                        endforeach; 
                                        // 최근 연간실적이 8개 미달 처리
                                        for($i = $col ; $i < 8 ; $i++) {
                                            $class = ($i == 2) ? 'line' : '';
                                            echo '<td class="'.$class.'">N/A</td>';
                                        }
                                        ?>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                                </tbody>
                            </table>

                            <!-- mobile 노출 테이블 table_mb -->
                            <table cellspacing="0" border="1" class="tableColtype sumtable table_mb">
                                <colgroup>
                                    <col width="20%">
                                    <col width="200px" span="5">
                                </colgroup>
                                <thead>
                                    <tr class="fntfmly_num">

                                        <?php $cnt=0; foreach(array_slice(array_keys($last_mrq_list), 0, 5) as $yyyymm) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?> class="thbder300"><span><?=date('y.m/d', strtotime(str_replace('.','-',$yyyymm)))?></span></th>
                                        <?php $cnt++; endforeach; ?>
                                        <?php for($i = sizeof(array_slice(array_keys($last_mrq_list), 0, 5)) ; $i < 5 ; $i++) : ?>
                                        <th scope="col" <?=($cnt==0) ? 'colspan="2"':''?>class="thbder300"><span>N/A</span></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                // 손익계산서와 요약에서 다르게 표출하는 필드 덮어쓰기.
                                $field_title_map['sf1_ebitda'] = $fininvestindi_titles_sub['sf1_ebitda'];

                                foreach($fields as $depth => $key) : 
                                    $title = '';
                                    if(isset($field_title_map[$key])) {
                                        $title = $field_title_map[$key];
                                        $title = str_replace('(', ' (', $title);
                                    } else if(isset($fininvestindi_titles_sub[$key])) {
                                        $title = $fininvestindi_titles_sub[$key];
                                    }
                                ?>
                                    <tr>
                                        <td class="bdrRightW"><span title="<?=$title?>"><strong><<?=str_replace('(','<br>(',$title)?></strong></span></td>

                                        <?php $col = 0; foreach(array_slice($last_mrq_list, 0, 5) as $yyyymm => $row) : ?>
                                        <?php 
                                            $col++;
                                        switch($key) {
                                            case 'sf1_opinc_ratio' :
                                                $row['sf1_opinc_ratio'] =  @number_format(intval(str_replace(',','',$row['sf1_opinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100  * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_opinc_ratio']) || $row['sf1_opinc_ratio'] == 'nan') {
													$row['sf1_opinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_opinc_ratio'] .= '';
												}
                                                break;
                                            case 'sf1_netinc_ratio' :
                                                $row['sf1_netinc_ratio'] = @number_format(intval(str_replace(',','',$row['sf1_netinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100 * $row['sf1_fxusd'], 2);
												if(@is_nan($row['sf1_netinc_ratio']) || $row['sf1_netinc_ratio'] == 'nan') {
													$row['sf1_netinc_ratio'] = 'N/A';
												}
												else {
													$row['sf1_netinc_ratio'] .= '';
												}
                                                break;
                                        }
                                        ?>
                                        <td class="<?=$col == 3 ? 'line' : ''?>"><?=str_replace('%','',$row[$key])?></td>
                                        <?php 
                                        endforeach; 
                                        // 최근 분기실적이 5개 미달 처리
                                        for($i = $col ; $i < 5 ; $i++) {
                                            $class = ($i == 2) ? 'line' : '';
                                            echo '<td class="'.$class.'">N/A</td>';
                                        }
                                        ?>

                                    </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

				<!-- 제휴사 배너 -->
				<?php if($up_rand == '0') :?>
				<div class="alliance_banner ab_mobile imsi_div">
					<h2 class="b_title"><?=($up_banner['rc_subtitle'] != '') ? $up_banner['rc_subtitle']:$up_banner['rc_title']?></h2>
					<p class="b_pdion"><span>예상 수익률</span><strong><?=$up_banner['ticker_revenue']?><b>%</b></strong></p>
					<a href="https://www.choicestock.co.kr/stock/recommend?pt=CSPART003" class="link_btn" target="_blank">종목추천 확인하기<i></i></a>
				</div>
				<?php elseif($up_rand == '1') :?>
				<div class="alliance_banner ab_mobile imsi_div">
					<h2 class="e_title">적중! 수익 실현 종목</h2>
					<p class="e_pdion"><span>수익률</span><strong><?=$up_banner['ticker_revenue']?><b>%</b></strong></p>
					<p class="event_name"><?=$up_banner['tkr_name']?></p>
					<a href="https://www.choicestock.co.kr/stock/recommend_view/<?=$up_banner['rc_id']?>?pt=CSPART003" class="e_link_btn" target="_blank">투자전략 확인하기<i></i></a>
				</div>
				<?php else :?>
				<div class="alliance_banner ab_mobile imsi_div">
					<table cellspacing="0" border="1" class="table_jump">
						<tbody>
							<tr>
								<th class="th_01"><span>투자매력 급등주</span></th>
								<th class="th_02"><span><?=str_replace('%','<b>%</b>',$up_banner['tkr_close']);?>  <?=$up_banner['tkr_close']?></span></th>
								<th class="th_03"><span>투자매력</span></th>
							</tr>
							<tr>
								<td class="td_01"><span><?=$up_banner['m_korname']?></span></td>
								<?php $up_banner['tkr_rate_str'] = str_replace('+','<b>+</b>',$up_banner['tkr_rate_str']);?>
								<?php $up_banner['tkr_rate_str'] = str_replace('-','<b>-</b>',$up_banner['tkr_rate_str']);?>
								<td class="td_02"><span><?=str_replace('%','<b>%</b>',$up_banner['tkr_rate_str']);?></span></td>
								<td class="td_03"><span><?=$up_banner['m_biz_total_score']?><b>점</b></span></td>
							</tr>
						</tbody>
					</table>
					<a href="https://www.choicestock.co.kr/attractiveness/attractive?sort=total&netincome=all&marketcap=over100billion&pt=CSPART003" class="j_link_btn" target="_blank">투자매력 종목 더보기<i></i></a>
				</div>
				<?php endif;?>
				<!-- 제휴사 배너 -->
            </div>
            <!-- //tableData -->
            <?php endforeach; ?>
