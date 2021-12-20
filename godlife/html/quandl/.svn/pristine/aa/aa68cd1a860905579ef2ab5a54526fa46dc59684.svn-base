
            <div class="night"><!-- night 버전 -->
                <div class="mainTop">
                    <p class="topEventSum">투자를 쉽고 편리하게! <strong>미국주식가이드</strong></p>
                    <h1 class="topEvent">아이투자 미국주식 서비스</h1>
                    <div class="eventStart">
                        <a href="/guide/intro" class="linkEvent">미국주식 시작하기<i></i></a>
                    </div>                    
                </div>
                <!-- //mainTop -->

                
                <div class="mainTheme">
                    <div id="vtab">
                        <ul>
                            <?php foreach($tab_info as $idx => $info) : ?>
                            <li class="tabMenu0<?=$idx+1?>"><?=$info['title']?>
                                <p class="selectTxt"><?=$info['subtitle']?></p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php foreach($tab_info as $idx => $info) : ?>
                        <!-- chartFour 4개 종목, chartTwo 2개 종목 -->


                        <?php if(sizeof($info['tickers']) == 4) : ?>
                        <div class="chartFour">

                            <?php foreach($info['tickers'] as $tic) : ?>
                            <div class="schChartTitle">
                                <div class="chartData">
                                    <h2 class="title"><a href="/stocks/summary/<?=$tic;?>"><?=$ticker_info_map[$tic]['tkr_name']?></a></h2>
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
                                    <div id='main_tab_chart_<?=$tic?>' style="width: 100%; height: 100%;"></div>
                                    <script>
                                    var sep_list_<?=$tic?> = [];

                                    <?php foreach(array_reverse($ticker_chart_map[$tic]) as $v) : ?>
                                    sep_list_<?=$tic?>.push(['<?=$v['sep_date']?>',<?=$v['sep_close']?> ]);
                                    <?php endforeach; ?>
                                        Highcharts.chart('main_tab_chart_<?=$tic?>', {

                                            chart: {
                                                type: 'spline',
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
                                                backgroundColor: 'rgba(0, 0, 0, 0.85)',                                                                                
                                                style: {
                                                    color: '#ffffff',
                                                    border: '1px solid #ffffff'
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
                                                type: 'areaspline',
                                                minorTickInterval: 'auto',
                                                title: {
                                                    text: null
                                                },            
                                                lineColor: null,
                                                minorGridLineWidth: 0,
                                                gridLineWidth: 0,
                                                alternateGridColor: null,                                                
                                                showFirstLabel:false,
                                                labels: {
                                                    enabled: false
                                                }
                                            },

                                            credits: {
                                                text: 'itooza.com',
                                                href: 'https://www.itooza.com'                                                
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
                                                    pointStart: null,
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
                                        <h2 class="title"><a href="/stocks/summary/<?=$tic;?>"><?=$ticker_info_map[$tic]['tkr_name']?></a></h2>
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
                                        <div id='main_tab_chart_<?=$tic?>' style="width: 100%; height: 178px"></div>
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
                                                    backgroundColor: 'rgba(0, 0, 0, 0.85)',                                                                                
                                                    style: {
                                                        color: '#ffffff',
                                                        border: '1px solid #ffffff'
                                                    },                                                
                                                },

                                                xAxis: {
                                                    title: {
                                                        text: null
                                                    },
                                                    lineColor: null,
                                                    tickColor: null
                                                },

                                                yAxis: {
                                                    type: 'logarithmic',
                                                    minorTickInterval: 'auto',
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
                                                    text: 'itooza.com',
                                                    href: 'https://www.itooza.com'                                                
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
                                                        pointStart: null,
                                                        marker: {
                                                            enabled: false,
                                                        },
                                                        fillOpacity: 0.1
                                                    },
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
			<?php if($us_index0_1) {?>
            <div class="indicators">
                <strong class="title">주요지표</strong>
                <div class="indicatorsSwiper">
                    <!-- Swiper -->
                    <div class="swiper-container swiper_indicators">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <dl class="list">
                                    <dt>원/달러</dt>
                                    <dd class="num"><?=$us_index0_1;?></dd>
                                    <dd class="per"><span class="<?=$us_index0_2 == '+' ? 'increase' : 'decrease'?>"><?=$us_index0_2;?> <?=$us_index0_3;?> <?=$us_index0_2;?><?=$us_index0_4;?></span></dd>
                                </dl>
                            </div>
                            <div class="swiper-slide">
                                <dl class="list">
                                    <dt>금($/온스)</dt>
                                    <dd class="num"><?=$us_index1_1;?></dd>
                                    <dd class="per"><span class="<?=$us_index1_2 == '+' ? 'increase' : 'decrease'?>"><?=$us_index1_2;?> <?=$us_index1_3;?> <?=$us_index1_2;?><?=$us_index1_4;?></span></dd>
                                </dl>
                            </div>
                            <div class="swiper-slide">
                                <dl class="list">
                                    <dt>WTI 원유</dt>
                                    <dd class="num"><?=$us_index3_1;?></dd>
                                    <dd class="per"><span class="<?=$us_index3_2 == '+' ? 'increase' : 'decrease'?>"><?=$us_index3_2;?> <?=$us_index3_3;?> <?=$us_index3_2;?><?=$us_index3_4;?></span></dd>
                                </dl>
                            </div>
                            <div class="swiper-slide">                                
                                <dl class="list">
                                    <dt>브렌트유</dt>
                                    <dd class="num"><?=$us_index2_1;?></dd>
                                    <dd class="per"><span class="<?=$us_index2_2 == '+' ? 'increase' : 'decrease'?>"><?=$us_index2_2;?> <?=$us_index2_3;?> <?=$us_index2_2;?><?=$us_index2_4;?></span></dd>
                                </dl>
                            </div>
                            <div class="swiper-slide">                                
                                <dl class="list">
                                    <dt>천연가스</dt>
                                    <dd class="num"><?=$us_index4_1;?></dd>
                                    <dd class="per"><span class="<?=$us_index4_2 == '+' ? 'increase' : 'decrease'?>"><?=$us_index4_2;?> <?=$us_index4_3;?> <?=$us_index4_2;?><?=$us_index4_4;?></span></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <!-- //Swiper -->
                </div>
                <!-- //indicatorsSwiper -->                
                <script>
                var swiper = new Swiper('.swiper_indicators', {
                    slidesPerView: 4,                    
                    spaceBetween: 0,
                    freeMode: true,
                    loop: true,
                    pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    },
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false,
                    },
                });
                </script>
            </div>
            <!-- //indicators -->
			<?php }?>
            <div class="mainDivision">
                <!-- mainLeft -->
                <div class="mainLeft">
                    <div class="tableDiv">
                        <h1 class="rankingTitle">급등종목</h1>
                        <table cellspacing="0" border="1" class="tableRanking">                    
                            <colgroup>                                
                                <col width="">
                                <col width="">
                                <col width="">                        
                            </colgroup>                        
                            <?php
                            $row_cnt = 0;
                            foreach( $top_plus_ticker_codes as $tkr) :
                            if( ! isset($ticker_info_map[$tkr])) continue;
                            $row_cnt++;

                            ?>
                            <tr>                                
                                <td class="title"><a href="/stocks/summary/<?=$tkr?>"><?=isset($ticker_korean_map[$tkr]) ? $ticker_korean_map[$tkr] : $ticker_info_map[$tkr]['tkr_name']?> <span class="eng"><?=$ticker_info_map[$tkr]['tkr_name']?></span></a></td>
                                <td class="num"><?=$ticker_price_map[$tkr]['close']?></td>
                                <td class="per"><span class="<?=$ticker_price_map[$tkr]['diff_num'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker_price_map[$tkr]['diff_rate']?></span></td><!-- increase 증가, decrease 감소 -->
                            </tr>
                            <?php 
                            if($row_cnt >= 5) break;
                            endforeach; 
                            ?>
                        </table>
                    </div>
                    <!-- //tableDiv -->

                    <div class="tableDiv">
                        <h1 class="rankingTitle">인기종목</h1>
                        <table cellspacing="0" border="1" class="tableRanking">                    
                            <colgroup>
                                <col width="">
                                <col width="">
                                <col width="">                        
                            </colgroup>                       
                            <?php
                            foreach( $popular_ticker_codes as $tkr) : 
                            ?>
                            <tr>                                
                                <td class="title"><a href="/stocks/summary/<?=$tkr?>"><?=isset($ticker_korean_map[$tkr]) ? $ticker_korean_map[$tkr] : $ticker_info_map[$tkr]['tkr_name']?> <span class="eng"><?=$ticker_info_map[$tkr]['tkr_name']?></span></a></td>
                                <td class="num"><?=$ticker_price_map[$tkr]['close']?></td>
                                <td class="per"><span class="<?=$ticker_price_map[$tkr]['diff_num'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker_price_map[$tkr]['diff_rate']?></span></td><!-- increase 증가, decrease 감소 -->
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <!-- //tableDiv -->

                    <!-- 메인 중간 배너 -->
                    <div style="margin-top:30px; text-align: center;background: #f2f2f2; margin-right: 45px">
                        <ins class="adsbygoogle"
                            style="display:inline-block;width:855px;height:80px;text-align: center;"
                            data-ad-client="ca-pub-6896844206786605"
                            data-ad-slot="6334057067">
                        </ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                    <!-- //메인 중간 배너 -->

                    <div class="eventShopping">
                        <h2 class="shoppingTitle">종목발굴</h2>
                        <ul class="shopping txtShopping">
                            <li><a href="#" class="active_fix" onclick="$('#invest_finder table').hide();$('#invest_finder table:eq(0)').show();return false">저PER</a></li>
                            <li><a href="#" class="" onclick="$('#invest_finder table').hide();$('#invest_finder table:eq(1)').show();return false">저PBR</a></li>
                            <li><a href="#" class="" onclick="$('#invest_finder table').hide();$('#invest_finder table:eq(2)').show();return false">고ROE</a></li>
                            <li><a href="#" class="" onclick="$('#invest_finder table').hide();$('#invest_finder table:eq(3)').show();return false">고배당</a></li>
                        </ul>

                        <div id="invest_finder" class="tableData">
                            <!-- <p class="profitTitle">주당순이익 (분기)</p> -->
                            <?php 
                            $dp_field_map = array(
                                'low_per'       => array(
                                                    'main' => 'dly_pe',
                                                    'title'=> 'PER(배)',
                                                    'dot'=>2,
                                                    'list' => 'sf1_epsdil',
                                                    'list_title' => '주당순이익 (분기)',
                                                    ),
                                'low_pbr'       => array(
                                                    'main' => 'dly_pb',
                                                    'title'=> 'PBR(배)',
                                                    'dot'=>2,
                                                    'list' => 'sf1_bvps',
                                                    'list_title' => '주당순자산 (분기)',
                                                    ),
                                'high_roe'      => array(
                                                    'main' => 'sf1_roe',
                                                    'title'=> 'ROE(%)',
                                                    'dot'=>2,
                                                    'list' => 'sf1_roe',
                                                    'list_title' => '자기자본이익률 (연간)',
                                                    ),
                                'high_yield'    => array(
                                                    'main' => 'sf1_divyield',
                                                    'title'=> '배당수익률(%)',
                                                    'dot'=>2,
                                                    'list' => 'sf1_divyield',
                                                    'list_title' => '배당수익률 (연간)',
                                                    )
                            );


                            foreach($invest_finder as $list_type => $rows) : 
                            $last_price_date = array_pop(array_values($rows));
                            $last_price_date = date('n/j', strtotime($last_price_date['tkr_lastpricedate']));

							$sub_title = '';
							if($list_type=='low_per' || $list_type=='low_pbr') {
								$sub_title = '<br>'.$last_price_date;
							}
							else if($list_type=='high_roe') {
								$sub_title = '<br>연환산';
							}
							else {
								$sub_title = '<br>작년';
							}

                            $ticker_history_dates = array();
                            $valid_row_count = 0;
                            foreach($rows as $tkr => $row) {
                                if( ! is_numeric($row[ $dp_field_map[$list_type]['main'] ])) continue;
                                if( ! isset($ticker_history_map[$list_type][$tkr])) continue; // 4개 히스토리가 존재하지 않는 종목

                                $date_list = array_keys($ticker_history_map[$list_type][$tkr]);

                                if(sizeof($date_list) < 4) { // 발표 수량 부족 종목
                                    continue;
                                }

                                if(in_array($list_type, array('low_per', 'low_pbr'))) { // 분기 
                                    if( ! in_array(substr($date_list[0], -2), array('03','06','09','12'))) { 
                                        // 일반 월이 머리로 올라가도록. calendardate로 뿌려서 의미없어짐.
                                        continue;
                                    }
                                }

                                if(in_array($list_type, array('high_roe', 'high_yield'))) { // 년
                                    if( ! in_array(substr($date_list[0], -2), array('12'))) {
                                        // 일반 월이 머리로 올라가도록. calendardate로 뿌려서 의미없어짐.
                                        continue;
                                    }
                                }

                                // 마이너스 열 있는 종목 제거
                                $is_valid = true;
                                foreach($date_list as $dt) {
                                    if($ticker_history_map[$list_type][$tkr][$dt] <= 0) {
                                        $is_valid = false;
                                        break;
                                    }
                                }
                                if( ! $is_valid) {
                                    continue;
                                }
                                $ticker_history_dates[$date_list[0]] = $date_list;
                                $valid_row_count++;

                                if( $valid_row_count >= 10) {
                                    break;
                                }
                            }
                            krsort($ticker_history_dates);
                            $ticker_history_dates = array_shift($ticker_history_dates);



                            ?>

                            <table cellspacing="0" border="1" style='display:<?=$list_type == 'low_per' ? '' : 'none'?>' id='table_<?=$list_type?>' class="tableColtype">                                
                                <colgroup>
                                    <col width="16px">
                                    <col width="">
                                    <col width="120px">
                                    <col width="100px">

                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th colspan="2" rowspan="2"></th>
                                        <th rowspan="2"><strong>주가 <br><?=$last_price_date?></strong></th>
                                        <th rowspan="2"><strong><?=$dp_field_map[$list_type]['title'].$sub_title?></strong></th>
                                        <th colspan="4" class="profitTitle"><?=$dp_field_map[$list_type]['list_title']?></th>
                                    </tr>
                                    <tr class="fntfmly_num">
                                        <?php foreach($ticker_history_dates as $ticker_history_date) : ?>
                                        <th><span><?=$ticker_history_date?></span></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $no = 0; 
                                foreach($rows as $tkr => $row) : 

                                // 랭킹에 안뿌리고 skip할 종목들 거르기.
                                if( ! is_numeric($row[ $dp_field_map[$list_type]['main'] ])) continue; // 랭킹 필드가 N/A
                                if( ! isset($ticker_history_map[$list_type][$tkr]) || sizeof($ticker_history_map[$list_type][$tkr]) < 4) continue; // 4개 히스토리가 존재하지 않는 종목

                                // 4년 중 하나라도 0 이하 존재시 패스
                                $continue_flag = false;
                                //foreach($ticker_history_map[$list_type][$tkr] as $v) {


                                foreach($ticker_history_dates as $dtidx => $dt) {
                                    if($dtidx > 0 && ! isset($ticker_history_map[$list_type][$tkr][$dt])) {
                                        $continue_flag = true;
                                        break;
                                    }
                                    $v = @$ticker_history_map[$list_type][$tkr][$dt];

                                    if($v[$dp_field_map[$list_type]['list']] <= 0 || ($dtidx > 0 && ! is_numeric(@$v[$dp_field_map[$list_type]['list']]))) {
                                        $continue_flag = true;
                                        break;
                                    }
                                }
                                //20.01/10 임시로 주석 
								if($continue_flag == true) continue;

                                
                                $no++; 
                                $pow = 1;
                                if(in_array($list_type, array('high_roe', 'high_yield'))) {
                                    $pow = 100;
                                }								
                                ?>
                                    <tr>
                                        <td class="count"><?=$no?></td>
                                        <td><a href="/stocks/summary/<?=$tkr?>"><?=isset($ticker_korean_map[$tkr]) ? $ticker_korean_map[$tkr] : $row['tkr_name']?></a><span class="eng"><?=$tkr?> | <?=$row['tkr_name']?></span></td>
                                        <td><?=number_format($row['sep_close'], 2)?></td>

                                        <td><strong><?=number_format($row[ $dp_field_map[$list_type]['main'] ]*$pow, $dp_field_map[$list_type]['dot'])?></strong></td>
                                        <?php 
                                        $listidx = 0; 
                                        /*foreach(@$ticker_history_map[$list_type][$tkr] as $dt => $val) : */
                                        foreach($ticker_history_dates as $dt) : 
                                        $val = @$ticker_history_map[$list_type][$tkr][$dt];
                                        if( ! $val) {
                                            $val[ $dp_field_map[$list_type]['list'] ] = 'N/A';
                                        }
                                        
                                        ?>
                                        <td>
                                        <?php
                                        $cell_val = $val[ $dp_field_map[$list_type]['list'] ];
                                        if( ! is_numeric($cell_val)) {
                                            echo $cell_val;
                                        } else if( ! $cell_val) {
                                            echo 'N/A';
                                        } else {
                                            // 0 아닌 숫자.
                                            echo number_format($cell_val*$pow, $dp_field_map[$list_type]['dot']);
                                        }

                                        // 테이블 상단에 노출되는 년월과 다른 날짜꺼. 인중대표님이 따로 보여주지 말자 하셔서 주석.
                                        if($ticker_history_dates[$listidx] != $dt) {
                                             echo '<br /><span style="font-size:0.7em;">'.$dt.'</span>';
                                        }
                                        ?>
                                        </td>
                                        <?php $listidx++; endforeach; ?>

                                        <?php for($i = $listidx ; $i < 4 ; $i++) : ?>
                                        <td>N/A</td>
                                        <?php endfor; ?>

                                    </tr>
                                <?php 
                                if($no >= 15) break;
                                endforeach; 
                                ?>
                                </tbody>
                            </table>
                            <?php endforeach; ?>

                            <p class="dataLink mrt_30">data from <a href="https://www.quandl.com/" target="_blank">Quandl and Sharadar</a></p>
                        </div>
                        <!-- //tableData -->
                    </div>
                    <!-- //eventShopping -->
                </div>
                <!-- //mainLeft -->

                <!-- mainRight -->
                <div class="mainRight">
                    <div class="rightTop">                        
                        
                        <div class="event txtEvent tab_list">
                            <button class="tablinks" onclick="openCity(event, 'tab_list01'); return false"> <!-- tab_list01 관심종목 -->
                                <a href="#none" id="tab_list01_title" class="active_fix active">관심종목</a>
                            </button>
                            <button class="tablinks" onclick="openCity(event, 'tab_list02'); return false"> <!-- tab_list02 종목토론 -->
                                <a href="#none" id="tab_list02_title" class="active_fix">종목토론</a>
                            </button>
                        </div>
						<?php if($this->session->userdata('is_login')) {?>
						<?php	if(!$my_ticker_list) {?>
                        <div class="tabLogin" style="display: block;">
                            <P align='center'>:(<br> 등록된 관심종목이 없습니다.</P>                                
                        </div>
						<?php	}else {?>
                        <!-- 관심종목 -->
<?
	$my_cnt=0;
	$str_my_item_fir='';
	$str_my_item_snd='';
	 foreach($my_ticker_list as $nkey=>$nVal) {

		if( isset($ticker_korean_map[$nVal['ticker']])) {
			$my_ticker_kornm = $ticker_korean_map[$nVal['ticker']];
		}
		else {
			$my_ticker_kornm = $nVal['ticker'];		
		}
		
		if($nVal['diff_rate']>0) {
			$my_ticker_diff_cls = 'increase';
		}
		else {
			$my_ticker_diff_cls = 'decrease';
		}

		if(!$nVal['ticker']) break;
		if( $my_cnt < 6 ) { 
			$str_my_item_fir .= '<tr>';
			$str_my_item_fir .=	'	<td class="title"><a href="/stocks/summary/'.$nVal['ticker'].'" >'.$my_ticker_kornm.'</a></td>';
			$str_my_item_fir .=	'		<td class="num">'.$nVal['close'].'</td>';
			$str_my_item_fir .=	'	<td class="per"><span class="'.$my_ticker_diff_cls.'">'.$nVal['diff_rate'].'</span></td>';
			$str_my_item_fir .=	'</tr>';
		}
		else {
			$str_my_item_snd .= '<tr>';
			$str_my_item_snd .=	'	<td class="title"><a href="/stocks/summary/'.$nVal['ticker'].'" >'.$my_ticker_kornm.'</a></td>';
			$str_my_item_snd .=	'		<td class="num">'.$nVal['close'].'</td>';
			$str_my_item_snd .=	'	<td class="per"><span class="'.$my_ticker_diff_cls.'">'.$nVal['diff_rate'].'</span></td>';
			$str_my_item_snd .=	'</tr>';
		}
		$my_cnt++;
	 }
?>
                        <div id="tab_list01" class="tabcontent" style="display: block;" >
                            <div class="swiper-container swiper_event">
                                <ul class="swiper-wrapper">
                                    <li class="swiper-slide">
                                        <table cellspacing="0" border="1" class="tableRanking">
                                            <colgroup>
                                                <col width="140px">
                                                <col width="">
                                                <col width="60px">
                                            </colgroup>
                                            <tbody>
											<?=$str_my_item_fir;?>
                                            </tbody>
                                        </table>
                                    </li>
								<?php if($my_cnt>5) { ?>
                                    <li class="swiper-slide">
                                        <table cellspacing="0" border="1" class="tableRanking">
                                            <colgroup>
                                                <col width="140px">
                                                <col width="">
                                                <col width="60px">
                                            </colgroup>
                                            <tbody>
											<?=$str_my_item_snd;?>
                                            </tbody>
                                        </table>
                                    </li>
								<?php }?>
                                </ul>
                                <div class="swiper-pagination"></div>
                            </div>
                            <!-- Swiper -->
                            <script>
                                var swiper = new Swiper('.swiper_event', {
                                    loop: true,
                                    pagination: {
                                        el: '.swiper-pagination',
                                        clickable: true,
                                    },
                                });
                            </script>
                        </div>
                        <!-- //관심종목 -->
						<?php	}?>
						<?php }else {?>
                        <!-- 비로그인시 노출되는 로그인창 -->
                        <div class="tabLogin" style="display: block;">
                            <P>로그인 후,<br> 관심종목을 볼 수 있습니다.</P>                                
                            <a href="/auth/login" class="btnLogin login_trigger">로그인</a>
                        </div>
                        <!-- //tabLogin -->
						<?php }?>                        
                        <!-- 종목토론 -->
                        <div id="tab_list02" class="tabcontent">
                            <table cellspacing="0" border="1" class="tableRanking">
                                <colgroup>
                                    <col width="160px">
                                    <col width="">
                                    <col width="40px">
                                </colgroup>
                                <tbody>
								<?php $opt_cnt = 0; foreach($opinion_list as $nKey=>$nVal) :?>
								<?php $opt_cnt++; if($opt_cnt>5) break;?>
                                    <tr>
                                        <td class="title"><a href="/stocks/summary/<?=$nVal['mo_ticker'];?>"><?=isset($ticker_korean_map[$nVal['mo_ticker']]) ? $ticker_korean_map[$nVal['mo_ticker']] : $nVal['mo_ticker']?></a></td>
                                        <td class="condition <?=($nVal['mo_opinion']=='B') ? 'buying' : 'sell'?>"><i></i><?=($nVal['mo_opinion']=='B') ? '매수' : '매도'?></td>
                                        <td class="re"><span class="<?=($nVal['mo_opinion']=='B') ? 'increase' : 'decrease'?>"><?=$nVal['mo_count'];?></span></td>
                                    </tr>
								<?php endforeach;?>
                                </tbody>
                            </table>
                            <span class="term">최근 3개월</span>
                        </div>
                        <!-- //종목토론 -->

                        <script>
                            function openCity(evt, cityName) {
                                var i, tabcontent, tablinks;

								if(cityName=='tab_list02') {
									$('.tabLogin').css('display', 'none');
								}
								else {
									$('.tabLogin').css('display', 'block');
								}

                                tabcontent = document.getElementsByClassName("tabcontent");
                                for (i = 0; i < tabcontent.length; i++) {
                                    tabcontent[i].style.display = "none";
                                }
                                tablinks = document.getElementsByClassName("tablinks");
                                for (i = 0; i < tablinks.length; i++) {
                                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                                }
                                document.getElementById(cityName).style.display = "block";
                                evt.currentTarget.className += " active";

                            }  
                        </script>
                    </div>
                    <!-- //rightTop -->
                    <!-- 우측 배너 -->
                    <div style="margin-top: 30px; ">                        
                        <ins class="adsbygoogle"
                            style="display:inline-block;width:257px;height:275px"
                            data-ad-client="ca-pub-6896844206786605"
                            data-ad-slot="8125936295">
                        </ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                        <!-- <a href="https://us.itooza.com/"><img src="/img/globalstock/img/temp_bannerR.jpg" alt="투자를 쉽고 편리하게, 미국주식가이드"></a> -->
                    </div>
                    <!-- //우측 배너 -->

					<?php if($right_ticker_info) {?>
                    <div class="rightBtm">
                        <div class="chartDate">
                            <div class="swiper-container cardswiper">
                                <div class="swiper-wrapper">                                    
									
									<?php foreach($right_ticker_info as $nKey=>$nVal) : ?>
									<div class="swiper-slide">
                                        <table cellspacing="0" border="1" class="tableRowtype card">
                                            <colgroup>
                                                <col width="50%">
                                                <col>
                                            </colgroup>
                                            <tr>
                                                <td colspan="2" style="text-align: left;">
                                                    <div class="schChartTitle">
                                                        <h2 class="title"><a href="/stocks/summary/<?=$nVal['cp_ticker']?>"><?=$nVal['cp_usname']?></a></h2>
                                                        <ul class="info">
                                                            <li class="sum"><span class="eng"><?=$nVal['cp_ticker']?></span> </li>
                                                        </ul>
                                                        <a href="/stocks/summary/<?=$nVal['cp_ticker']?>" class="chartLink"><img src="/img/globalstock/img/go_more_btn.png" alt="더보기"></a>
                                                        <!-- //info -->
                                                        <ul class="detail">
                                                            <li class="num"><?=$nVal[$nVal['cp_ticker']]['close']?></span></li>
                                                            <li class="per">
                                                                <span class="<?if($nVal[$nVal['cp_ticker']]['diff_rate']>0) {echo 'increase'; $sign='+';} else {echo 'decrease'; $sign='';}?>"><??><?=$sign.$nVal[$nVal['cp_ticker']]['diff_num'];?> <span>(<?=$nVal[$nVal['cp_ticker']]['diff_rate'];?>)</span></span>
                                                                <!-- increase 증가, decrease 감소 -->
                                                            </li>
                                                            <li class="day"><?=date('y.m/d', strtotime($nVal['sepdata']['sep_date']));?>, USD</li>
                                                            <li class="category"><?=$nVal['ticker']['tkr_exchange']?></li>
                                                        </ul>
                                                        <!-- //detail -->
                                                    </div>
                                                    <!-- //schChartTitle -->
                                                    <div class="summary big">
                                                        <!-- div 사이즈는 big, small  -->
                                                        <p><?=$nVal['cp_short_description']?></p>
                                                        <ul class="etc">
                                                            <li class="industry"> <span>산업</span><?=$nVal['ticker']['tkr_industry']?></li>
                                                        </ul>
                                                        <!-- //etc -->
                                                    </div>
                                                    <!-- //summary -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">시가총액</th>
                                                <td><?=number_format($nVal['dly_marketcap'])?><span class="unit">백만</span></td>
                                            </tr>
                                            <tr>
                                                <th scope="col">주가수익배수 PER</th>
                                                <td><?=number_format($nVal['dly_pe'], 2)?></td>
                                            </tr>
                                            <tr>
                                                <th scope="col">주가순자산배수 PBR</th>
                                                <td><?=number_format($nVal['dly_pb'], 2)?></td>
                                            </tr>
                                            <tr>
                                                <th scope="col">자기자본이익률 ROE</th>
                                                <td><?=is_numeric($nVal['last_mrt']['sf1_roe']) ? number_format($nVal['last_mrt']['sf1_roe']*100, 2).'<span class="unit">%</span>' : $nVal['last_mrt']['sf1_roe']?></td>
                                            </tr>
                                            <tr>
                                                <th scope="col">배당수익률</th>
                                                <td><?=is_numeric($nVal['last_mry']['sf1_divyield']) ? number_format($nVal['last_mry']['sf1_divyield']*100, 2).'<span class="unit">%</span>' : $nVal['last_mry']['sf1_divyield']?></td>
                                            </tr>
                                            <!-- <tr>
                                                <th scope="col">주식MRI</th>
                                                <td>20점 &#47; 25</td>
                                            </tr> -->
                                        </table>
                                    </div>
                                    <!-- //swiper-slide -->
									<?php endforeach;?>
                                 </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination"></div>
                            </div>
                            <!-- //swiper-container -->
                            <script>
                                var swiper = new Swiper('.cardswiper', {
                                    autoplay: {
                                        delay: 3500,
                                        disableOnInteraction: false,
                                    },
                                    loop: true,
                                    pagination: {
                                        el: '.swiper-pagination',
                                        clickable: true,
                                    },
                                });
                            </script>
                        </div>
                        <!-- //chartDate -->

                    </div>
					<?php }?>
                    <!-- //rightBtm -->

                </div>
                <!-- //mainRight -->

                <div class="mainService">
                    <h3 class="h3Title">주요 서비스</h3>
                    <div class="swiper-container swiper-mainService">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide img01">
                                <a href="/guide/intro"><img src="/img/globalstock/img/txt/mainService_01.png" alt="종목분석을 누구나 쉽고 빠르게"
                                    class="img"></a>
                            </div>
                            <div class="swiper-slide img02">
                                <a href="/guide/intro"><img src="/img/globalstock/img/txt/mainService_02.png" alt="취향저격 스마트한 종목발굴"
                                    class="img"></a>
                            </div>
                            <div class="swiper-slide img03">
                                <a href="/guide/intro"><img src="/img/globalstock/img/txt/mainService_03.png" alt="종목정보는 한눈에 핵심만 쏙쏙"
                                    class="img"></a>
                            </div>
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                
                    <!-- Initialize Swiper -->
                    <script>
                        var swiper = new Swiper('.swiper-mainService', {
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            loop: true,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                        });
                    </script>
                </div>
                <!-- //mainService -->
                
                <div class="familyBanner">
                    <!--<div class="area left">
                        <a href="https://www.itooza.com/iw/index.htm" target="_blank">
                            <span class="img"><img src="/img/globalstock/img/iw_banner.png" alt="인더스트리 워치"></span>
                            <strong class="title">인더스트리 워치</strong>
                            <span class="txt">산업분석 빅 데이터 서비스</span>
                        </a>                
                    </div>
                    <div class="area center">
                        <a href="http://www.bookon.co.kr/" target="_blank">
                            <span class="img"><img src="/img/globalstock/img/bookon_banner.png" alt="인더스트리 워치"></span>
                            <strong class="title">아이투자 출판 <b>부크온</b></strong>
                            <span class="txt">돈 버는 투자자가 꼭 읽어야 할 책</span>
                        </a>
                    </div>-->
                    <div class="area right">
                        <span class="img"><img src="/img/globalstock/img/question.png" alt="서비스문의 02)723-9093"></span>
                        <strong class="title">서비스문의 <i>02)723-9093</i></strong>
                        <span class="txt">평일 09:30~11:20, 12:30~17:30</span>
                    </div>
                </div>
                <!-- //familyBanner -->
            </div>
            <!-- //mainDivision -->
            
