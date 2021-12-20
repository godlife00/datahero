    <?php     
    $active_menu = 'summary';
    $ticker_code = $ticker['tkr_ticker'];
    include_once dirname(__FILE__).'/wm_submenu.php'; 
    ?>            
            <div class="summary small"> <!-- div 사이즈는 big, small  -->
                <p><?=($company_info['cp_description'] != '') ? nl2br($company_info['cp_description']) : nl2br($company_info['cp_short_description'])?></p>
                <ul class="etc">
                    <li><span>섹터</span><strong><?=$ticker['tkr_sector']?></strong></li>
                    <li class="industry"> <span>산업</span> <?=$ticker['tkr_industry']?></li>
                    <li class="home"><span><img src="/img/icon/home.png" alt="홈" ></span>  <a href="<?=$ticker['tkr_companysite']?>" target="_blank"><?=$ticker['tkr_companysite']?></a></li>
                    <li class="link"><span> <img src="/img/icon/link.png" alt="링크"> </span><a href="<?=$ticker['tkr_secfilings']?>" target="_blank">SEC 전자공시</a></li>
                </ul>
                <!-- //etc -->
                <span class="close">더보기 <i></i></span> 
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
                                type: 'line',
                                renderTo: 'sum_topchart',
                                backgroundColor: {
                                    // linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
                                },
                                style: {
                                    fontFamily: "'Lato', 'Noto Sans KR'"
                                },
                                height: 300,                
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
                                    step : 25,              
                                }
                            }],


                            yAxis:{
                                title: {
                                    text: null
                                },
                            },

							credits: {
								text: '초이스스탁US',
								href: false,
								style: {
									fontSize: '12px',
									cursor: 'text',
								},
							},
							exporting: {
								buttons: {
									contextButton: {
										menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
									}
								},
							},

                            legend: {
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

                <div class="chartDate">
                    <span class="indicator">
                        <img src="/img/icon/indicator.png" alt="통계지표보기" onclick="$(this).next().toggle()">
                        <!-- 투자지표 알아보기 말풍선 -->
                        <div class="ly_help hide">
                            <!-- 기본 hide로 숨김, 클릭시 class = view 로 변경 -->
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

                </div>
                <!-- //chartDate -->

                <div class="top5">
                    <a href="/wm_stocks/competitors/<?=$ticker_code?>" class="more"><img src="/img/icon/more.png" alt="더보기"></a>

                    <?php foreach(array_values($industry_top_seps) as $idx => $itop_sep) : if($idx >= 5) break; ?>
                    <dl class="list">
                        <dt><a href='/wm_stocks/summary/<?=$itop_sep['ticker']?>'><?=$itop_sep['ticker']?></a></dt>
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
            <div class="tableData bannerRight">
                <h3 class="titleInvestment"> <img src="/img/wm/txt/txt_investment.png" alt="투자지표"></h3>
                <div class="tableTab">
                    <span class="info">
                        <img src="/img/icon/info.png" alt="안내"
                            onclick="$(this).next().toggle()">
                        <div class="ly_help">
                            <!-- 기본 hide로 숨김, 클릭시 class = view 로 변경 -->
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
                    <caption><img src="/img/txt/txt_investment.png" alt="투자 지표">
                    </caption>
                    <colgroup>
                        <col width="18%">
                        <col width="" span="8">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col" rowspan="2" class="bdrR"></th>
                            <th scope="col" colspan="3" class="thbder400 bdrR">최근 연간 실적</th>
                            <th scope="col" colspan="5" class="thbder400">최근 분기 실적</th>
                        </tr>
                        <tr class="fntfmly_num">
                            <?php foreach(array_slice(array_keys($last_mry_list), 0, 3) as $yyyymm) : ?>
                            <th scope="col"  class="thbder300"><span><?=date('y.m/d', strtotime(str_replace('.','-',$yyyymm)))?></span></th>
                            <?php endforeach; ?>
                            <?php for($i = sizeof(array_slice(array_keys($last_mry_list), 0, 3)) ; $i < 3 ; $i++) : ?>
                            <th scope="col"  class="thbder300"><span>N/A</span></th>
                            <?php endfor; ?>


                            <?php foreach(array_slice(array_keys($last_mrq_list), 0, 5) as $yyyymm) : ?>
                            <th scope="col" class="thbder300"><span><?=date('y.m/d', strtotime(str_replace('.','-',$yyyymm)))?></span></th>
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
            </div>
            <!-- //tableData -->

            <?php endforeach; ?>

            <script>
            function change_dimension(dim, cell_type) {
                location.href='?dimension='+dim+'&cell_type='+cell_type+'#list';
            }

            $(document).ready(function(){
                //기업개요
                document.domain = 'thewm.co.kr';
                //parent.setMenu('summary');
                window.parent.postMessage({ childData : ' summary ' }, '*'); 
            });
            </script>