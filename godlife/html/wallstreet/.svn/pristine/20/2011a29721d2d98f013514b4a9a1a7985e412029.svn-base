
            <div class="sub_top">
                <div class="swiper-container swiper-slideChar">
                    <div class="swiper-wrapper">
                    <?php 
                        foreach($top_analy as $idx => $ta) : 
                            $class = 'decrease';
                            if($ta['ticker']['tkr_rate'] > 0) {
                                $class = 'increase';
                            }
                    ?>
                        <div class="swiper-slide">
                            <div class="chart_area" data-id="<?=$ta['an_id']?>">
                                <div class="chartData left_area">
                                    <h2 class="title" title="<?=$ta['ticker']['tkr_name_en']?>"><a href="/stock/analysis_view/<?=$ta['an_id']?>"><?=$ta['ticker']['tkr_name']?></a></h2>
                                    <ul class="info">
                                        <li class="sum"><span class="eng"><?=$ta['an_ticker']?></span> </li>
                                    </ul>
                                    <!-- //info -->
                                    <ul class="detail">
                                        <li class="num <?=$class?>"><?=$ta['ticker']['tkr_close']?></li>
                                        <li class="per">
                                            <span class="<?=$class?>"> <?=$ta['ticker']['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </li>
                                    </ul>
                                    <!-- //detail -->
                                    <div id="top_analy<?=$idx?>" class="containerS1"></div><!-- 종목추천 id = containerS1 -->
                                    <script>SubAnalyTopChart('top_analy<?=$idx?>', [<?=$ta['chart_value']?>]);</script>
                                </div>
                                <!-- //chartData -->

                                <div class="right_area">                                    
                                    <div class="opinion">
                                        <span class="invest">애널리스트 투자의견</span>
                                        <span class="recom <?=$this->mri_tb_model->getInvestOpinionByStar(intval($ta['an_opinion']), $divide=2)?>"><?=$this->mri_tb_model->getInvestOpinionByStar(intval($ta['an_opinion']))?></span>
                                        <!-- 매수 : class = buying, 매도 : class = sell -->
                                    </div>                                    

                                    <div class="chartbtm">
                                        <dl>
                                            <dt class="charm">투자매력</dt>
                                            <dd class="charm_num"><?=$ta['m_biz_total_score']?></dd>
                                        </dl>
                                        <dl>
                                            <dt class="value">밸류에이션</dt>
                                            <dd class="just">
                                                <figure class="highcharts-figure">           
                                                    <div id="top_value<?=$idx?>" class="contaanalysis1"></div><!-- 종목분석 id = containerS1 -->
                                                    <span class="proper"><?=$this->common->get_valuation_stars_text($ta['expected_star'])?></span>
                                                </figure>
                                                <script>SubAnalyGaugeChart('top_value<?=$idx?>', [<?=$ta['expected_star'];?>]);</script>
                                            </dd>
                                        </dl>
                                    </div>                                    
                                </div>
                                <!-- //right_area -->
                            </div>
                            <!-- //chart_area -->
                        </div>
                        <!-- //swiper-slide -->
                        <?php endforeach; ?>
                        
                    </div>
                    <!-- //swiper-wrapper -->
                    <div class="swiper-pagination"></div>
                </div>
                <!-- //swiper-container -->
            </div>
            <!-- //sub_top -->

            <div class="sub_mid eventPicks_area">
                <div id="analysis_list">
                    <?=$content_html?>
                </div>
                <!-- //chart_area -->
                <div class="btn_more">
                    <a href="javascript:;" onclick="view_more()"><i></i>더보기</a>
                </div>
                <!-- //btn_more -->
            </div> <!-- //sub_mid -->


            <script>
            var page = 1;
            var is_loading = false;
            function view_more() {
                if(is_loading) {
                    return;
                }
                is_loading = true;
                
                page += 1;
                $.post('/stock/ajax_get_analysis_list', {'page': page}, function(res) {
                    if($.trim(res).length) {
                        $('#analysis_list').append(res);
                    } else {
                        $('.btn_more').hide();
                    }
                    is_loading = false;
                });
            }

            $(function() {
                $('.chart_area').on('click', function() {
                    location.href = '/stock/analysis_view/' + $(this).data('id');
                });
            });
            </script>

