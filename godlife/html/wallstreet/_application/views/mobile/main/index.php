            <div class="main_top">
                <div class="tabsArea_main bdr_radius">
                    <ul class="tabs set_tabs">
                        <!-- 설정버튼이 있는 경우 class = set_exist, 없는 경우 삭제 -->
                        <li <?=$first_tab==0 ? 'class=active' : '' ?> rel="tab1">배당매력주</li>
                        <li <?=$first_tab==1 ? 'class=active' : '' ?> rel="tab2"><?=$tab_text?></li>
                        <li <?=$first_tab==2 ? 'class=active' : '' ?> rel="tab3">최근 실적발표</li>
                    </ul>
                    <!--<span class="set"><i></i> 설정</span>-->
                    <div class="tab_container">
                        <!-- 배당매력주 -->
                        <div id="tab1" class="tab_content<?=$first_tab==0 ? ' tab_view' : '' ?>">
                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="100px">
                                    <col width="80px">
                                    <col width="110px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td class="data_guide t04">주가</td>
                                        <td class="data_guide t01">배당매력</td>
                                        <td class="data_guide t02">배당수익률</td>
                                    </tr>
                                    <?php foreach($top5_dividend as $row) : ?>
                                    <tr>
                                        <td class="title">
                                            <a href="/search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                        </td>
                                        <td class="num">
                                            <span><?=$row['tkr_close']?></span>
                                            <span class="<?=$row['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$row['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </td>
                                        <td>                                            

                                            <div class="star_area">
                                                <div class="starRev">     
                                                    <?php 
                                                    for($i = 1 ; $i <= 5 ; $i++) { 
                                                        if($row['m_biz_dividend_stars'] >= $i) {
                                                            echo '<span class="starR on">별1</span>';
                                                        }
                                                        else {
                                                            if($i-$row['m_biz_dividend_stars'] <= 0.5) {
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
                                        </td>
                                        <td class="allocation">
                                            <span><?=number_format($row['sf1_divyield']*100, 2)?> %</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <!-- //배당매력주 -->
                        <!-- 최신검색 or 인기검색 -->
                        <div id="tab2" class="tab_content<?=$first_tab==1 ? ' tab_view' : '' ?>"><!-- class = tab_view 로 show -->
                            <table cellspacing="0" border="0" class="tableRanking">
                                <colgroup>
                                    <col width="100px">
                                    <col width="80px">
                                    <col width="110px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="data_guide t03">주가</td>
                                        <td class="data_guide t03">투자매력</td>
                                        <td class="data_guide t03">증권사의견</td>
                                    </tr>
                                <?php 
                                    foreach($tab_stock_data as $val) : 
                                        $class = 'decrease';
                                        if($val['ticker']['tkr_rate'] > 0) {
                                            $class = 'increase';
                                        }
                                ?>
                                    <tr>
                                        <td class="title"><a href="/search/invest_charm/<?=$val['ticker']['tkr_ticker']?>"><?=$val['ticker']['tkr_name']?><span class="ticker"><?=$val['ticker']['tkr_ticker']?></span></a></td>
                                        <td class="num">
                                            <span><?=$val['ticker']['tkr_close']?></span>
                                            <span class="<?=$class?>"><?=$val['ticker']['tkr_rate_str']?></span>
                                        </td>
                                        <td class="score"><span><?=$val['m_biz_total_score']?>점</span></td>
                                        <td class="recom"><span><?=$this->mri_tb_model->getInvestOpinionByStar($val['an_opinion'])?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- //최신검색 -->
                        <!-- 최근 실적발표 -->
                        <div id="tab3" class="tab_content<?=$first_tab==2 ? ' tab_view' : '' ?>">
                            <table cellspacing="0" border="0" class="tableRanking">
                                <colgroup>
                                    <col width="100px">
                                    <col width="80px">
                                    <col width="110px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td colspan="2" class="data_guide t03">주가</td>
                                    <td class="data_guide t03">발표순이익</td>
                                    <td class="data_guide t03">전년대비</td>
                                </tr>
                                <?php $cnt=0; foreach($recent_report as $val) : ?>
                                    <?php if($cnt>4) break;?>
                                    <tr>
                                        <td class="title"><a href="/search/invest_charm/<?=$val['tkr_ticker']?>"><?=$val['tkr_name']?><span class="ticker"><?=$val['tkr_ticker']?></span></a></td>
                                        <td class="num">
                                            <span><?=$val['tkr_close']?></span>
                                            <span class="<?=($val['tkr_rate'] > 0) ? 'increase' : 'decrease'?>"><?=$val['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </td>
                                        <td class="profit">
                                            <span><?=number_format($val['sf1_netinccmnusd']/1000000)?> <span class="dollar">백만달러</span></span>
                                        </td>                                        
                                        <td class="moti">
                                            <span class="<?=($recent_report_rates_pm[$val['tkr_ticker']] > 0) ? 'increase' : 'decrease'?>"><?=$recent_report_rates[$val['tkr_ticker']]?></span>
                                            <!--<span class="<?=($recent_report_rates[$val['tkr_ticker']] > 0) ? 'increase' : 'decrease'?>"><?=number_format($recent_report_rates[$val['tkr_ticker']],2)?>%</span>-->
                                        </td>
                                    </tr>
                                    <?php $cnt++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- //최근 실적발표 -->
                    </div>
                    <!-- .tab_container -->
                </div>
                <!-- //tabsArea_main -->
            </div>
            <!-- //main_top -->

            <div class="main_mid eventPicks_area">
            <?php if(is_array($recommend) && sizeof($recommend) > 0) : ?>
                <h3 class="title"><a href="/stock/recommend">종목추천</a></h3>
                <a href="/stock/recommend" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>
                <?php 
                foreach($recommend as $key => $val) : 
                    $class = 'decrease';
                    if($val['ticker']['tkr_rate'] > 0) {
                        $class = 'increase';
                    }
                ?>
                <div class="chart_area" onclick="location.href = '/stock/recommend_view/<?=$val['rc_id']?>';">
                    <div class="chartData">
                        <h4 class="event_name"><a href="/stock/recommend_view/<?=$val['rc_id']?>"><?=$val['ticker']['tkr_name']?></a></h4>
                        <ul class="detail">
                            <li class="num"><span class="<?=$class?>"><?=$val['ticker']['tkr_close']?></span></li>
                            <li class="per"><span class="<?=$class?>"><?=$val['ticker']['tkr_rate_str']?></span> <!-- increase 증가, decrease 감소 -->
                            </li>
                        </ul>
                        <!-- //detail -->
                        <div id="recommand<?=$key?>" class="containerM1"></div><!-- 종목추천 id = containerM1 -->
                        <script>MainLineChart('recommand<?=$key?>', [<?=$val['chart_value']?>]);</script>
                    </div>
                    <!-- //chartData -->
                    <div class="chartGoal">
                        <dl>
                            <dt>추천가</dt>
                            <dd><?=$val['rc_recom_price']?></dd>
                        </dl>
                        <dl>
                            <dt>목표가</dt>
                            <dd><?=$val['rc_goal_price']?></dd>
                        </dl>
                    </div>
                    <!-- //chartGoal -->
                </div>
                <?php endforeach; endif; ?>
            </div>
            <!-- //eventPicks_area -->

            <div class="main_mid event_anay">
                <?php if(is_array($analysis) && sizeof($analysis) > 0) : ?>
                <h3 class="title"><a href="/stock/analysis">종목분석</a></h3>
                <a href="/stock/analysis" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>

                <div class="slideChartArea">
                    <div class="swiper-container swiper-slideCharM">
                        <div class="swiper-wrapper">
                        <?php 
                            foreach($analysis as $idx => $an) : 
                                $class = 'decrease';
                                if($an['ticker']['tkr_rate'] > 0) {
                                    $class = 'increase';
                                }
                        ?>
                            <div class="swiper-slide" onclick="location.href='/stock/analysis_view/<?=$an['an_id']?>'">
                                <div class="chart_area">
                                    <div class="chartData">
                                        <h4 class="event_name"><a href="/stock/analysis_view/<?=$an['an_id']?>"><?=$an['ticker']['tkr_name']?></a></h4>
                                        <ul class="detail">
                                            <li class="num"><span class="<?=$class?>"><?=$an['ticker']['tkr_close']?></span></li>
                                            <li class="per"><span class="<?=$class?>"><?=$an['ticker']['tkr_rate_str']?></span>
                                                <!-- increase 증가, decrease 감소 -->
                                            </li>
                                        </ul>
                                        <!-- //detail -->
                                    </div>
                                    <!-- //chartData -->
                                    <!-- 투자의견 -->
                                    <div class="opinion">
                                        <span class="invest">애널리스트 컨센서스</span>
                                        <span class="recom <?=$this->mri_tb_model->getInvestOpinionByStar($an['an_opinion'], $divide=2)?>"><?=$this->mri_tb_model->getInvestOpinionByStar($an['an_opinion'])?></span>
                                        <!-- 매수 : class = buying, 매도 : class = sell -->
                                    </div>
                                    <!-- //opinion -->

                                    <div class="chartbtm">
                                        <dl>
                                            <dt class="charm">투자매력</dt>
                                            <dd class="charm_num"><?=$an['m_biz_total_score']?></dd>
                                        </dl>
                                        <dl>
                                            <dt class="value">밸류에이션</dt>
                                            <dd class="just">
                                                <figure class="highcharts-figure">
                                                    <div id="analysis<?=$idx?>" class="containerM2"></div>
                                                    <span class="proper"><?=$this->common->get_valuation_stars_text($an['expected_star'])?></span>
                                                </figure>
                                                <script>MainGaugeChart('analysis<?=$idx?>', [<?=$an['expected_star']?>]);</script>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <!-- //chart_area -->
                            </div>
                            <!-- //swiper-slide -->
                            <?php endforeach; ?>
                        </div>
                        <!-- //swiper-wrapper -->
                    </div>
                    <!-- //swiper-container -->
                </div>
                <!-- slideChartArea -->
                <?php endif; ?>
            </div>
            <!-- //event_anay -->

            <div class="main_mid research_area">
                <?php if(is_array($explore) && sizeof($explore) > 0) : ?>
                <h3 class="title"><a href="/stock/research">미국주식 탐구생활</a></h3>
                <a href="/stock/research" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>

                <ul class="lst_type">
                    <?php foreach($explore as $exp) : ?>
                    <li>
                        <dl class="lst_type2">
                            <dt class="tit"><a href="/stock/research_view/<?=$exp['e_id']?>"><strong><?=nl2br($exp['e_title'])?></strong></a></dt>
                            <?php if(strlen($exp['e_thumbnail']) > 0) : ?>
                            <dd class="photo"><a href="/stock/research_view/<?=$exp['e_id']?>"><img src="<?=$exp['e_thumbnail']?>" alt=""></a></dd>
                            <?php endif; ?>
                        </dl>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <!-- //research_area -->

            <div class="main_mid master_area">
                <?php if(is_array($master) && sizeof($master) > 0) : ?>
                <h3 class="title"><a href="/stock/master">대가의 종목</a></h3>
                <a href="/stock/master" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>

                <div class="swiper-container swiper-master">
                    <div class="swiper-wrapper">
                        <?php foreach($master as $mas) : ?>
                        <div class="swiper-slide">
                            <div class="master_slide">
                                <dl class="master">
                                    <dt class="name"><a href="/stock/master_view/<?=$mas['ms_id']?>"><?=$mas['ms_korguru']?> <span>(<?=$mas['ms_guru']?>)</span></dt>
                                    <dd class="sum"><?=$mas['ms_introduce']?></dd>
                                    <dd class="img">
                                        <?php if(strlen($mas['ms_image']) > 0 && file_exists(ATTACH_DATA.'/master/'.$mas['ms_image'])) : ?>
                                        <img src="/webdata/attach_data/master/<?=$mas['ms_image']?>" alt="<?=$mas['ms_korguru']?>" />
                                        <?php else : ?>
                                        <img src="/img/warrent_Buffett_ra.png" alt="<?=$mas['ms_korguru']?>" />
                                        <?php endif; ?>
                                    </dd>
                                </dl>
                                <p class="tag">
                                    <?php foreach($mas['rp_ticker'] as $key => $val) : ?>
                                    <span><?=$val['name']?></span>
                                    <?php endforeach; ?>
                                </p>
                            </div>
                            <!-- //master_area -->
                        </div>
                        <!-- //swiper-slide -->
                        <?php endforeach; ?>
                    </div>
                    <!-- //swiper-wrapper -->
                </div>
                <!-- //swiper-master -->
                <?php endif; ?>
            </div>
            <!-- //master_area -->

            <div class="main_mid charm_area">
                <?php if(is_array($all_total_score) && sizeof($all_total_score) > 0) : ?>
                <h3 class="title"><a href="/attractiveness/attractive?sort=total&netincome=all&marketcap=over100billion">전종목 투자매력도</a></h3>
                <a href="/attractiveness/attractive?sort=total&netincome=all&marketcap=over100billion" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>

                <div class="slideChartArea">
                    <div class="swiper-container swiper-charm">
                        <div class="swiper-wrapper">
                        <?php 
                            foreach($all_total_score as $idx => $ts) : 
                                $class = 'decrease';
                                if($ts['tkr_rate'] > 0) {
                                    $class = 'increase';
                                }
                        ?>
                            <div class="swiper-slide" onclick="location.href='/search/invest_charm/<?=$ts['m_ticker']?>'">
                                <div class="chart_area">
                                    <div class="chartData">
                                        <h4 class="event_name"><a href="/search/invest_charm/<?=$ts['m_ticker']?>"><?=$ts['m_korname']?></a></h4>                                        
                                        <ul class="detail">
                                            <li class="num"><span class="<?=$class?>"><?=number_format($ts['tkr_close'], 2)?></span></li>
                                            <li class="per">
                                                <span class="<?=$class?>"><?=$ts['tkr_diff_str']?> <span>(<?=$ts['tkr_rate_str']?>)</span></span>
                                                <!-- increase 증가, decrease 감소 -->
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- //chartData -->

                                    <div class="chartbtm">
                                        <dl>
                                            <dt class="charm"><?=$ts['m_biz_total_score']?></dt>
                                            <dd class="charm_num">투자매력</dd>
                                        </dl>
                                        <div id="all_total_score<?=$idx?>" class="containerM3"></div>
                                        <script>MainSpiderChart('all_total_score<?=$idx?>', [<?=$ts['chart_value']?>]);</script>
                                    </div>
                                    <!-- //chartbtm -->
                                </div>
                                <!-- //chart_area -->
                            </div>
                            <!-- //swiper-slide -->
                            <?php endforeach; ?>
                        </div>
                        <!-- //swiper-wrapper -->
                    </div>
                    <!-- //swiper-charm -->
                </div>
                <!-- //slideChartArea -->
                <?php endif; ?>

            </div>
            <!-- //charm_area -->

            <!-- 투자 레시피 -->
            <div class="main_btm recipe_area">
                <h3 class="title"><a href="/stock/recipe">투자레시피</a></h3>
                <a href="/stock/recipe" class="more"><img src="/img/more_Black@2x.png" alt="더보기"></a>
                <div class="tabsArea_2">
                    <ul class="tabs_2">
                        <li rel="tab4">배당매력주</li>
                        <li rel="tab5">이익성장주</li>
                        <li rel="tab6">소비자독점</li>
                        <li class="active" rel="tab7">슈퍼스톡</li>
                    </ul>
                    <div class="tab_container">
                        <!-- 슈퍼스톡 -->
                        <div id="tab7" class="tab_content">
                            <div class="remark">
                                <p>슈퍼스톡 <br> “뛰는 주 위에 나는 주, 슈퍼종목을 찾아라!”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="80px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td class="data_guide t01">주가</td>
                                        <td class="data_guide t01">투자매력</td>
                                        <td class="data_guide t02">5년 ROE</td>
                                    </tr>
                                    <?php foreach($total_score as $row) : ?>
                                    <tr>
                                        <td class="title">
                                            <a href="/search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                        </td>
                                        <td class="num">
                                            <span><?=number_format($row['tkr_close'], 2)?></span>
                                            <span class="<?=$row['tkr_diff'] > 0 ? 'in' : 'de'?>crease"><?=$row['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </td>
                                        <td>
                                            <div class="star_area">
                                                <div class="starRev">
                                                    <?php 
                                                    for($i = 1 ; $i <= 5 ; $i++) { 
                                                        if($row['m_biz_total_score'] / 20 >= $i) {
                                                            echo '<span class="starR on">별1</span>';
                                                        }
                                                        else {
                                                            if($i-($row['m_biz_total_score']/20) <= 0.5) {
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
                                        </td>
                                        <td class="allocation">
                                            <span><?=number_format($row['m_g_roe'], 2)?> %</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- //슈퍼스톡 -->
                        <!-- 배당매력주 -->
                        <div id="tab4" class="tab_content">
                            <div class="remark">
                                <p>초보도 벌 수 있는 투자의 정석 <br> “고배당주에 투자하라”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="80px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td class="data_guide t04">주가</td>
                                        <td class="data_guide t01">배당매력</td>
                                        <td class="data_guide t02">배당수익률</td>
                                    </tr>
                                    <?php foreach($top5_dividend as $row) : ?>
                                    <tr>
                                        <td class="title">
                                            <a href="/search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                        </td>
                                        <td class="num">
                                            <span><?=number_format($row['tkr_close'], 2)?></span>
                                            <span class="<?=$row['tkr_diff'] > 0 ? 'in' : 'de'?>crease"><?=$row['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </td>
                                        <td>
                                            <div class="star_area">
                                                <div class="starRev">
                                                    <?php 
                                                    for($i = 1 ; $i <= 5 ; $i++) { 
                                                        if($row['m_biz_dividend_stars'] >= $i) {
                                                            echo '<span class="starR on">별1</span>';
                                                        }
                                                        else {
                                                            if($i-$row['m_biz_dividend_stars'] <= 0.5) {
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
                                        </td>
                                        <td class="allocation">
                                            <span><?=number_format($row['sf1_divyield']*100, 2)?> %</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <!-- //배당매력주 -->
                        <!-- 이익성장주 -->
                        <div id="tab5" class="tab_content">
                            <div class="remark">
                                <p>위대한 기업을 찾는 공식 <br> “내일의 넷플릭스를 찾아라”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="80px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td class="data_guide t01">주가</td>
                                        <td class="data_guide t01">수익성장성</td>
                                        <td class="data_guide t02">순이익성장률</td>
                                    </tr>
                                    <?php foreach($growth as $row) : ?>
                                    <tr>
                                        <td class="title">
                                            <a href="/search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                        </td>
                                        <td class="num">
                                            <span><?=number_format($row['tkr_close'], 2)?></span>
                                            <span class="<?=$row['tkr_diff'] > 0 ? 'in' : 'de'?>crease"><?=$row['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </td>
                                        <td>
                                            <div class="star_area">
                                                <div class="starRev">
                                                    <?php 
                                                    for($i = 1 ; $i <= 5 ; $i++) { 
                                                        if($row['m_biz_growth_stars'] >= $i) {
                                                            echo '<span class="starR on">별1</span>';
                                                        }
                                                        else {
                                                            if($i-$row['m_biz_growth_stars'] <= 0.5) {
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
                                        </td>
                                        <td class="allocation">
                                            <span><?=number_format($row['m_g_epsgr'], 2)?> %</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- //이익성장주 -->
                        <!-- 소비자독점 -->
                        <div id="tab6" class="tab_content">
                            <div class="remark">
                                <p>스노우볼 투자전략의 핵심 <br> “소비자 독점 기업을 찾아라”</p>
                            </div>
                            <!-- //remark -->
                            <table cellspacing="0" border="0" class="tableRanking type_2Line">
                                <colgroup>
                                    <col width="80px">
                                    <col width="80px">
                                    <col width="100px">
                                    <col width="">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td class="data_guide t01">주가</td>
                                        <td class="data_guide t01">사업독점력</td>
                                        <td class="data_guide t02">영업이익률</td>
                                    </tr>
                                    <?php foreach($moat as $row) : ?>
                                    <tr>
                                        <td class="title">
                                            <a href="/search/invest_charm/<?=$row['m_ticker']?>"><?=$row['m_korname']?><span class="ticker"><?=$row['m_ticker']?></span></a>
                                        </td>
                                        <td class="num">
                                            <span><?=number_format($row['tkr_close'], 2)?></span>
                                            <span class="<?=$row['tkr_diff'] > 0 ? 'in' : 'de'?>crease"><?=$row['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </td>
                                        <td>
                                            <div class="star_area">
                                                <div class="starRev">
                                                    <?php 
                                                    for($i = 1 ; $i <= 5 ; $i++) { 
                                                        if($row['m_biz_moat_stars'] >= $i) {
                                                            echo '<span class="starR on">별1</span>';
                                                        }
                                                        else {
                                                            if($i-$row['m_biz_moat_stars'] <= 0.5) {
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
                                        </td>
                                        <td class="allocation">
                                            <span><?=number_format($row['sf1_opmargin'], 2)?> %</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- //소비자독점 -->
                    </div>
                    <!-- .tab_container -->
                </div>
                <!-- //tabsArea -->
            </div>
            <!-- //main_btm //recipe_area -->


