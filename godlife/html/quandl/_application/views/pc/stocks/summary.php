	<?php 	
	$active_menu = 'summary';
	$ticker_code = $ticker['tkr_ticker'];
	include_once dirname(__FILE__).'/submenu.php'; 
	?>
                

                
            <div class="summary big"> <!-- div 사이즈는 big, small  -->
                <p><?=nl2br(isset($company_info['cp_short_description']) ? $company_info['cp_short_description'] : '' )?></p>
                <ul class="etc">
                    <li><span>섹터</span><strong><?=$ticker['tkr_sector']?></strong></li>
                    <li class="industry"> <span>산업</span> <?=$ticker['tkr_industry']?></li>
                    <li class="home"><span><img src="/img/globalstock/img/icon/home.png" alt="홈" ></span>  <a href="<?=$ticker['tkr_companysite']?>"><?=$ticker['tkr_companysite']?></a></li>
                    <li class="link"><span> <img src="/img/globalstock/img/icon/link.png" alt="링크"> </span><a href="<?=$ticker['tkr_secfilings']?>">SEC 전자공시</a></li>
                </ul>
                <!-- //etc -->
                <span class="close show">닫기 <i></i></span>
            </div>
            <!-- //summary -->



	        <div class="schChartArea">
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
                <div class="chartSection">
                    <!-- 차트영역입니다 -->                    
                    <div id="container1" style="width: 100%; height: 100%;"></div>
                    <script>
                        var sep_list = [];
                        <?php foreach($sepdata as $sl) : ?>
                            sep_list.push(['<?=$sl['sep_date']?>',<?=floatval($sl['sep_close'])?> ]);
                        <?php endforeach; ?>
                        var mychart = Highcharts.chart('container1', {

                            chart: {
                                type: 'spline',
                                renderTo: 'container1',
                                backgroundColor: {
                                    // linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
                                    stops: [
                                        [0, '#ffffff'],
                                        [1, '#ffffff']
                                    ]
                                },     
                                style: {
                                    fontFamily: "'Lato', 'Noto Sans KR'"
                                },
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
                                backgroundColor: 'rgba(0, 0, 0, 0.85)',                                
                                split: true,
                                style: {
                                    color: '#ffffff',
                                    border: '1px solid #ffffff'
                                },
                                pointFormat:' {series.name}: {point.y} 달러'
                            },

                            //_this : this,

                            xAxis:{
                                labels: {
                                    enabled: true
                                },            
                                tickInterval: 1,
                                labels: {
                                    enabled: true,
                                    formatter: function() { return ''},
                                }
                            },


                            yAxis:{
                                title: {
                                    text: null
                                },
                            },

                            credits: {
                                text: 'itooza.com',
                                href: 'http://www.itooza.com'                                                
                            },

                            legend: {
                                enabled: false
                            },

                            exporting : {
                                enabled: false
                            },

                            series: [{
                                name: '',
                                data: sep_list.slice(0, 120)
                            }],

                            

                            plotOptions: {
                                series: {
                                    label: {
                                        connectorAllowed: false
                                    },
                                    //pointStart: 2010,
                                    marker: {
                                        enabled: false,                                                        
                                    }
                                }
                            },
                        });
                    </script>
                    <script type="text/javascript">
                        


                       function changeChart(count) {
                            var list = sep_list.slice(0, count).reverse();
                            mychart.series[0].setData(list);

                        }

                        changeChart(120);

                    </script>
                </div>



                <div class="chartDate">
                    <span class="indicator">
                        <img src="/img/globalstock/img/icon/indicator.png" alt="통계지표보기" onclick="$(this).next().toggle()">
                        <!-- 투자지표 알아보기 말풍선 -->
                        <div class="ly_help hide"> <!-- 기본 hide로 숨김, 클릭시 class = view 로 변경 -->
                        <strong>투자지표 알아보기</strong>
                            <p><b>시가총액</b> <span>기본주식수x전일 종가</span></p>
                            <p><b>기업가치 EV</b> <span>전일 시가총액+차입금-현금및현금성자산</span></p>
                            <p><b>주식수</b> <span>가중평균희석주식수(미발표 기업은 가중평균주식수)</span></p>
                            <p><b>주당배당금</b> <span>최근년도 주당배당금</span></p>                            
                            <p><b>배당수익률</b> <span>최근년도 주당배당금/기말 주가</span></p>
                            <p><b>주가수익배수 PER</b> <span>전일 시가총액/최근 4분기 합산 보통주순이익</span></p>
                            <p><b>주가순자산배수 PBR</b> <span>전일 시가총액/최근 분기 자본총계</span></p>
                            <p><b>자기자본이익률 ROE</b> <span>최근 4분기 합산 보통주순이익/최근 4분기 평균자본</span></p>
                            <p><b>주당순이익 EPS</b> <span>보통주순이익/가중평균희석주식수</span></p>
                            <p><b>주당순자산 BPS</b> <span>자본총계/가중평균주식수</span></p>                                                        
                            <div class="edge_rgt"></div><!-- edge_lft, edge_cen, edge_rgt -->
                        </div>
                        <!-- //투자지표 알아보기 말풍선 -->
                    </span>
                    <table cellspacing="0" border="1" class="tableRowtype left">
                        <caption>상세 데이터</caption>
                        <colgroup>
                            <col width="50%">
                            <col>                                
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
                <!-- //chartDate -->


                <div class="top5">
                    <a href="/stocks/competitors/<?=$ticker_code?>" class="more"><img src="/img/globalstock/img/icon/more.png" alt="더보기"></a>

                    <?php foreach(array_values($industry_top_seps) as $idx => $itop_sep) : if($idx >= 5) break; ?>
                    <dl class="list">
                        <dt><a href='/stocks/summary/<?=$itop_sep['ticker']?>'><?=$itop_sep['ticker']?></a></dt>
                        <dd class="num"><?=$itop_sep['close']?></dd>
                        <dd class="per"><span class="<?=$itop_sep['diff_num'] > 0 ? 'in' : 'de'?>crease"><?=$itop_sep['diff']?> <?=$itop_sep['diff_rate']?></span></dd><!-- increase 증가, decrease 감소 -->
                    </dl>
                    <?php endforeach; ?>
                </div>
                <!-- //top5 -->
            </div>
            <!-- //schChartArea -->

 
        <?php
        $tabs = array(
            //'재무상태표' => $balancesheet_fields,
            //'손익계산서' => $incomestate_fields,
            //'현금흐름표' => $cashflow_fields,
            '투자지표' => $fininvestindi_fields,
            //'주가지표' => $pricesheet_fields,
        );


        $tab_titles = array(
            //'재무상태표' => $balancesheet_titles,
            //'손익계산서' => $incomestate_titles,
            //'현금흐름표' => $cashflow_titles,
            '투자지표' => $fininvestindi_titles,
            //'주가지표' => $pricesheet_titles,
        );

	foreach($tabs as $field_title => $fields) :
		$field_title_map = $tab_titles[$field_title];

        ?>

            <!-- 검색 - 푸터 위 배너광고영역 -->
            <div class="bannerArea">                                               
                <!-- <ins class="adsbygoogle"
                    style="display:block"                   
                    data-ad-client="ca-pub-6896844206786605"
                    data-ad-slot="6293979879"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script> -->                
            </div>
            <!-- //bannerArea -->
            <!-- //검색 - 푸터 위 배너광고영역 -->

            <div class="tableData bannerRight">                
                <h3 class="titleInvestment"> <img src="/img/txt/txt_investment.png" alt="투자지표"></h3>
                <div class="tableTab">                    
                    <span class="info">
                        <img src="/img/globalstock/img/icon/info.png" alt="안내" onclick="$(this).next().toggle()">                                    
                        <div class="ly_help hide"> <!-- 기본 hide로 숨김, 클릭시 class = view 로 변경 -->                            
                            <strong>투자지표 알아보기</strong>
                            <p><b>자기자본이익률(%) ROE</b> <span>최근 4분기 합산 보통주순이익/최근 4분기 평균자본</span></p>
                            <p><b>배당수익률(%) DY</b> <span>연간 주당배당금/기말 주가</span></p>
                            <p><b>주당순이익(달러) EPS</b> <span>보통주순이익/가중평균희석주식수</span></p>
                            <p><b>주당순자산(달러) BPS</b> <span>자본총계/가중평균주식수</span></p>                            
                            <p><b>주당배당금(달러) DPS</b> <span>연간 주당배당금</span></p>                                                                
                            <div class="edge_rgt"></div><!-- edge_lft, edge_cen, edge_rgt -->
                        </div>
                        <!-- //연환산 = 최근4분기 합계 말풍선 -->
                    </span>
                </div>
                <!-- //tableTab -->
                
                <table cellspacing="0" border="1" class="tableColtype sumtable">
                    <caption><img src="/img/globalstock/img/txt/txt_investment.png" alt="투자 지표"></caption>
                    <colgroup>
                        <col width="15%">
                        <col width="116px" span="8">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col" rowspan="2" class="bdrR"></th>                            
                            <th scope="col" colspan="3" class="thbder400 bdrR">최근 연간 실적</th>
                            <th scope="col" colspan="5" class="thbder400">최근 분기 실적</th>
                        </tr>
                        <tr class="fntfmly_num">                            

                            <?php foreach(array_slice(array_keys($last_mry_list), 0, 3) as $yyyymm) : ?>
                            <th scope="col"  class="thbder300"><span><?=$yyyymm?></span></th>
                            <?php endforeach; ?>
                            <?php for($i = sizeof(array_slice(array_keys($last_mry_list), 0, 3)) ; $i < 3 ; $i++) : ?>
                            <th scope="col"  class="thbder300"><span>N/A</span></th>
                            <?php endfor; ?>


                            <?php foreach(array_slice(array_keys($last_mrq_list), 0, 5) as $yyyymm) : ?>
                            <th scope="col" class="thbder300"><span><?=$yyyymm?></span></th>
                            <?php endforeach; ?>
                            <?php for($i = sizeof(array_slice(array_keys($last_mrq_list), 0, 5)) ; $i < 5 ; $i++) : ?>
                            <th scope="col"  class="thbder300"><span>N/A</span></th>
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
                        $depth_num = count(explode('-', $depth));
                        $depth = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth_num-1);
                        $tr_depth_class = 'depth'.sprintf('%02d', $depth_num > 3 ? 3 : $depth_num);

						//            if(isset($row['sf1_opinc'])) $row['sf1_opinc'] =  @($row['sf1_opinc'] / $row['sf1_fxusd']);


                    ?>
                            <tr>
                                <td><span><strong><?=$title?></strong></span></td>

                                <?php $col = 0; foreach(array_slice($last_mry_list, 0, 3) as $yyyymm => $row) : ?>
                                <?php 
                                    $col++;
                                switch($key) {
                                    case 'sf1_opinc_ratio' :
										$row['sf1_opinc_ratio'] =  @number_format(intval(str_replace(',','',$row['sf1_opinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100  * $row['sf1_fxusd'], 2).'%';
                                        break;
                                    case 'sf1_netinc_ratio' :
                                        $row['sf1_netinc_ratio'] = @number_format(intval(str_replace(',','',$row['sf1_netinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100 * $row['sf1_fxusd'], 2).'%';
                                        break;
                                }

								//if( $this->session->userdata('is_login') === false && $col > 1) {
								//	$row[$key] = '<span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span>';
								//}
                                ?>
                                <td class="<?=$col == 3 ? 'line' : ''?>"><?=$row[$key]?></td>
                                <?php 
                                endforeach; 

                                // 최근 연간실적이 3개 미달 처리
                                for($i = $col ; $i < 3 ; $i++) {
                                    $class = ($i == 2) ? 'line' : '';
                                    echo '<td class="'.$class.'">N/A</td>';
                                }
                                ?>
                                



                                <?php $col=0; foreach(array_slice($last_mrq_list, 0, 5) as $yyyymm => $row) : ?>
                                <?php 
									$col++;
                                switch($key) {
                                    case 'sf1_opinc_ratio' :
                                        $row['sf1_opinc_ratio'] = @number_format(intval(str_replace(',','',$row['sf1_opinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100 * $row['sf1_fxusd'], 2).'%';
                                        break;
                                    case 'sf1_netinc_ratio' :
                                        $row['sf1_netinc_ratio'] = @number_format(intval(str_replace(',','',$row['sf1_netinc'])) / (floatval(str_replace(',','',$row['sf1_revenue']))) * 100 * $row['sf1_fxusd'], 2).'%';
                                        break;
                                }

								//if( $this->session->userdata('is_login') === false && $col > 2) {
								//	$row[$key] = '<span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span>';
								//}

                                ?>
                                <td><?=$row[$key]?></td>
                                <?php 
                                endforeach; 

                                // 최근 연간실적이 3개 미달 처리
                                for($i = $col ; $i < 5 ; $i++) {
                                    echo '<td>N/A</td>';
                                }

                                ?>

                            </tr>
                    <?php
                    endforeach;
                    ?>

					</tbody>
                </table>

                <p class="dataLink">data from <a href="https://www.quandl.com/" target="_blank">Quandl and Sharadar</a></p>
            </div>
            <!-- //tableData -->            

            <div class="sum_rightbanner">
                <ins class="adsbygoogle"
                    style="display:block;width:100%;text-align: center;"                   
                    data-ad-client="ca-pub-6896844206786605"
                    data-ad-slot="1397231636"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <!-- //sum_rightbanner -->
            

            <?php endforeach; ?>

            <script>
            function change_dimension(dim, cell_type) {
                location.href='?dimension='+dim+'&cell_type='+cell_type+'#list';
            }
            </script>


