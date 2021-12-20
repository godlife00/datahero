            <!-- 주요 콘텐츠 -->
            <div class="night">
                <div class="mainTheme">
                    <p class="update_time"><strong>전일종가</strong> <?=date('y.m/d', strtotime($last_date))?>, USD</p>
                    <div id="vtab">
                        <ul>
                            <?php foreach($tab_info as $idx => $info) : ?>
                            <li class="tabMenu0<?=$idx+1?>"><?=$info['title']?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php foreach($tab_info as $idx => $info) : ?>
                        <!-- chartFour 4개 종목, chartTwo 2개 종목 -->


                        <?php if(sizeof($info['tickers']) == 4) : ?>
                        <div class="chartFour">

                            <?php foreach($info['tickers'] as $tic) : ?>
                            <div class="schChartTitle">
                                <div class="chartData">
                                    <h2 class="title"><a href="/wm_stocks/summary/<?=$tic;?>"><?=$ticker_korean_map[$tic]?></a></h2>
                                    <ul class="info">
                                        <li class="sum"><span class="eng"><?=$tic?></span> </li>
                                    </ul>
                                    <!-- //info -->
                                    <ul class="detail">
                                        <li class="num <?=$ticker_price_map[$tic]['diff_num'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker_price_map[$tic]['close']?></span></li>
                                        <li class="per">
                                            <span class="<?=$ticker_price_map[$tic]['diff_num'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker_price_map[$tic]['diff']?> <?=$ticker_price_map[$tic]['diff_rate']?></span> <!-- increase 증가, decrease 감소 -->
                                        </li>
                                    </ul>
                                    <!-- //detail -->
                                </div>
                                <!-- //chartData -->
                                <div class="chartSheet">
                                    <div id='main_tab_chart_<?=$tic?>' class="mainTheme_chart1"></div>
                                    <script>
                                    var sep_list_<?=$tic?> = [];

                                    <?php foreach(array_reverse($ticker_chart_map[$tic]) as $v) : ?>
                                    sep_list_<?=$tic?>.push(['<?=$v['sep_date']?>',<?=$v['sep_close']?> ]);
                                    <?php endforeach; ?>
                                        Highcharts.chart('main_tab_chart_<?=$tic?>', {

                                            chart: {
                                                type: 'line',
                                                renderTo: 'main_tab_chart_<?=$tic?>',
                                                backgroundColor: {
                                                    // linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
                                                    
                                                },     
                                                style: {
                                                    fontFamily: "'Lato', 'Noto Sans KR'"
                                                },
                                                plotBorderColor: null,
                                                plotBorderWidth: null,
                                                plotShadow: false
                                            },

                                            colors: ["#877edf"],
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
                                                pointFormat: '<span style="color:{series.color}"><b>{point.y:,.2f} 달러</b><br/>',
                                                backgroundColor: 'rgba(0, 0, 0, 0.85)',
                                                style: {
                                                    color: '#FFFFFF',
                                                    border: '1px solid #FFFFFF'
                                                },
                                            },

                                            xAxis: {
                                                title: {
                                                    text: null
                                                },            
                                                lineColor: null,
                                                minorGridLineWidth: 0,
                                                gridLineWidth: 0,
                                                alternateGridColor: null, 
                                                showFirstLabel:false,                                                
                                                tickColor:null,
                                                labels: {
                                                    enabled: false
                                                }
                                            },

                                            yAxis:{
                                                title: {
                                                    text: null
                                                },
                                                lineColor: null,
                                                minorGridLineWidth: 0,
                                                gridLineWidth: 0,
                                                alternateGridColor: null,
                                                showFirstLabel: false,
                                                labels: {
                                                    enabled: false
                                                }
                                            },

                                            credits: {
                                                enabled: false,
                                            },

                                            legend: {
                                                enabled: false,                                                
                                            },

                                            exporting : {
                                                enabled: false
                                            },

                                            series: [{
                                                name: '',
                                                data: sep_list_<?=$tic?>
                                            }],

                                            plotOptions: {
                                                series: {                    
                                                    label: {
                                                    connectorAllowed: false
                                                    },
                                                    marker: {
                                                        enabled: false,
                                                    } 
                                                }
                                            },
                                        });
                                    </script>

                                </div>
                                <!-- //chartSheet -->
                            </div>
                            <!-- //schChartTitle -->
                            <?php endforeach; ?>
                        </div>
                        <!-- //chartFour -->

                        <?php elseif(sizeof($info['tickers']) == 2) : ?>

                        <div>
                            <div class="chartTwo">


                                <?php foreach($info['tickers'] as $tic) : ?>
                                <div class="schChartTitle">
                                    <div class="chartData">
                                        <h2 class="title"><a href="/wm_stocks/summary/<?=$tic;?>"><?=$ticker_korean_map[$tic]?></a></h2>
                                        <ul class="info">
                                            <li class="sum"><span class="eng"><?=$tic?></span> </li>
                                        </ul>
                                        <!-- //info -->
                                        <ul class="detail">
                                            <li class="num <?=$ticker_price_map[$tic]['diff_num'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker_price_map[$tic]['close']?></span></li>
                                            <li class="per">
                                                <span class="<?=$ticker_price_map[$tic]['diff_num'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker_price_map[$tic]['diff']?> <?=$ticker_price_map[$tic]['diff_rate']?></span>
                                                <!-- increase 증가, decrease 감소 -->
                                            </li>
                                        </ul>
                                        <!-- //detail -->
                                    </div>
                                    <!-- //chartData -->
                                    <div class="chartSheet" style='text-align:center;'>
                                        <div id='main_tab_chart_<?=$tic?>' class="mainTheme2_chart1"></div>
                                        <script>
                                        var sep_list_<?=$tic?> = [];
                                        <?php foreach(array_reverse($ticker_chart_map[$tic]) as $v) : ?>
                                        sep_list_<?=$tic?>.push(['<?=$v['sep_date']?>',<?=$v['sep_close']?> ]);
                                        <?php endforeach; ?>
                                            Highcharts.chart('main_tab_chart_<?=$tic?>', {

                                                chart: {
                                                    type: 'area',
                                                    renderTo: 'main_tab_chart_<?=$tic?>',
                                                    backgroundColor: {
                                                        // linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
                                                        
                                                    },     
                                                    style: {
                                                        fontFamily: "'Lato', 'Noto Sans KR'"
                                                    },
                                                    plotBorderColor: null,
                                                    plotBorderWidth: null,
                                                    plotShadow: false
                                                },

                                                colors: ["#877edf"],
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
                                                    pointFormat: '<span style="color:{series.color}"><b>{point.y:,.2f} 달러</b><br/>',
                                                    backgroundColor: 'rgba(0, 0, 0, 0.85)',
                                                    style: {
                                                        color: '#FFFFFF',
                                                        border: '1px solid #FFFFFF'
                                                    },
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
                                                    labels: {
                                                        enabled: false
                                                    }
                                                },

                                                yAxis: {
                                                    title: {
                                                        text: null
                                                    },
                                                    lineColor: null,
                                                    minorGridLineWidth: 0,
                                                    gridLineWidth: 0,
                                                    alternateGridColor: null,
                                                    showFirstLabel: false,
                                                    labels: {
                                                        enabled: false
                                                    }
                                                },

                                                credits: {
                                                    enabled: false,
                                                },

                                                legend: {
                                                    enabled: false,                                                
                                                },

                                                exporting : {
                                                    enabled: false
                                                },

                                                series: [{
                                                    name: '',
                                                    data: sep_list_<?=$tic?>
                                                }],

                                                plotOptions: {
                                                    series: {
                                                        label: {
                                                            connectorAllowed: false
                                                        },
                                                        marker: {
                                                            enabled: false,
                                                        },
                                                        fillOpacity: 0.1
                                                    }
                                                },
                                            });
                                        </script>
                                    </div>
                                    <!-- //chartSheet -->
                                </div>
                                <!-- //schChartTitle -->

                                <?php endforeach; ?>

                            </div>
                            <!-- //chartTwo -->
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>



                    </div>
                    <!-- //vtab -->
                </div>
                <!-- //mainTheme -->
            </div>
            <!-- //night -->

            <div class="indicators">

                <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container">
                  <div class="tradingview-widget-container__widget"></div>
                  <div class="tradingview-widget-copyright">트레이딩뷰 제공 <a href="https://kr.tradingview.com" rel="noopener" target="_blank"><span class="blue-text">티커 테이프</span></a></div>
                  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                  {
                  "symbols": [
                    {
                      "description": "달러/원",
                      "proName": "FX_IDC:USDKRW"
                    },
                    {
                      "description": "금($/온스)",
                      "proName": "COMEX:GC1!"
                    },
                    {
                      "description": "은(COMEX)",
                      "proName": "COMEX:SI1!"
                    },
                    {
                      "description": "WTI(서부텍스사유)",
                      "proName": "NYMEX:CL1!"
                    },
                    {
                      "description": "두바이유",
                      "proName": "NYMEX:DCB1!"
                    },
                    {
                      "description": "천연가스",
                      "proName": "NYMEX_MINI:QG1!"
                    }
                  ],
                  "colorTheme": "light",
                  "isTransparent": false,
                  "displayMode": "adaptive",
                  "locale": "kr"
                }
                  </script>
                </div>
                <!-- TradingView Widget END -->
            </div>
            <!-- //indicators -->


            <div class="mainDivision">
                <!-- mainLeft -->
                <div class="mainLeft">
                    <h1 class="title">대표종목 <span class="day"><?=date('y.m/d', strtotime($snp500_info[0]['sepdata']['sep_date']))?>, USD</span></h1>
                    <?php foreach($snp500_info as $sKey => $sVal) :?>
                    
                    <div class="chartDate chartcard">
                        <table cellspacing="0" border="1" class="tableRowtype">
                            <colgroup>
                                <col width="50%">
                                <col width="">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="schChartTitle">
                                            <h2 class="title"><a href="/wm_stocks/summary/<?=$sVal['ticker']['tkr_ticker'];?>"><?=$ticker_korean_map[$sVal['ticker']['tkr_ticker']];?></a></h2>
                                            <ul class="info">
                                                <li class="sum"><span class="eng"><?=$sVal['ticker']['tkr_ticker'];?></span> </li>
                                                <li class="category"><?=$sVal['ticker']['tkr_exchange'];?></li>
                                            </ul>
                                            <!-- //info -->
                                            <ul class="detail">
                                                <li class="num"><?=number_format($sVal['sepdata']['sep_close'],2);?></li>
                                                <li class="per">
                                                    <span class="<?=$sVal['sepdata']['sep_diff_price'] > 0 ? 'in' : 'de'?>crease"><?=$sVal['sepdata']['sep_diff_price'];?> <span>(<?=$sVal['sepdata']['sep_diff_rate'];?>%)</span></span>
                                                    <!-- increase 증가, decrease 감소 -->
                                                </li>
                                            </ul>
                                            <!-- //detail -->
                                        </div>
                                        <!-- //schChartTitle -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="sum_txt"><?=($sVal['company_info']['cp_short_description']) ? $sVal['company_info']['cp_short_description'] : $sVal['company_info']['cp_description'];?></p>
                        <p class="total">시가총액 <strong><?=number_format($sVal['last_daily']['dly_marketcap'])?> 백만달러</strong></p>
                        <table cellspacing="0" border="1" class="tableRowtype card">
                            <colgroup>
                                <col width="">
                                <col width="">
                                <col width="">
                                <col width="">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>PER</th>
                                    <th>PBR</th>
                                    <th>ROE</th>
                                    <th>배당수익률</th>
                                </tr>
                                <tr>
                                    <td><?=number_format($sVal['last_daily']['dly_pe'], 2)?></td>
                                    <td><?=number_format($sVal['last_daily']['dly_pb'], 2)?></td>
                                    <td><?=is_numeric($sVal['last_mrt']['sf1_roe']) ? number_format($sVal['last_mrt']['sf1_roe']*100, 2).'<span class="unit">%</span>' : $sVal['last_mrt']['sf1_roe']?></td>
                                    <td><?=is_numeric($sVal['last_mry']['sf1_divyield']) ? number_format($sVal['last_mry']['sf1_divyield']*100, 2).'<span class="unit">%</span>' : $sVal['last_mry']['sf1_divyield']?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php endforeach;?>
                </div>
                <!-- //mainLeft -->

                <!-- mainRight -->
                <div class="mainRight">
                    <h1 class="title">최근 실적발표</h1>

                    <table cellspacing="0" border="1" class="tableColtype performance">
                        <colgroup>
                            <col width="">
                            <col width="">
                            <col width="">
                            <col width="">
                        </colgroup>
                        <thead>
                            <tr>
                                <th></th>
                                <th><strong>주가</strong></th>
                                <th><strong>발표순이익</strong></th>
                                <th><strong>전년대비</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt=0; foreach($recent_report as $val) : ?>
							<?php if($cnt>11) break;?>
                            <tr>
                                <td class="title"><a href="/wm_stocks/summary/<?=$val['tkr_ticker']?>"><?=$val['tkr_name']?><span
                                            class="ticker"><?=$val['tkr_ticker']?></span></a></td>
                                <td class="num">
                                    <span><?=$val['tkr_close']?></span>
                                    <span class="<?=($val['tkr_rate'] > 0) ? 'increase' : 'decrease'?>"><?=$val['tkr_rate_str']?></span>
                                    <!-- increase 증가, decrease 감소 -->
                                </td>
                                <td class="profit">
                                    <span><?=number_format($val['sf1_netinccmnusd']/1000000)?> <span class="dollar">백만달러</span></span>
                                </td>
                                <td class="moti">
                                    <span class="<?=($recent_report_rates_pm[$val['tkr_ticker']] > 0) ? 'increase' : 'decrease'?>"><?=str_replace('%','<br>%',$recent_report_rates[$val['tkr_ticker']]);?></span>
                                    <!--<span class="increase">2.34%</span>-->
                                </td>
                            </tr>
                            <?php $cnt++; endforeach; ?>
                        </tbody>
                    </table>

                </div>
                <!-- //mainRight -->
