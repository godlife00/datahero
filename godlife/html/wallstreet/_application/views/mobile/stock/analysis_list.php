                <?php 
                foreach($an_list as $r) : 
                    $updown_class = 'decrease';
                    if($r['ticker']['tkr_rate'] > 0) {
                        $updown_class = 'increase';
                    }
                ?>
                <div class="chart_area" data-id="<?=$r['an_id']?>">
                    <div class="chartData">
                        <h4 class="event_name"><?=$r['ticker']['tkr_name']?></h4>
                        <ul class="detail">
                            <li class="num"><span class="<?=$updown_class?>"><?=$r['ticker']['tkr_close']?></span></li>
                            <li class="per"><span class="<?=$updown_class?>"><?=$r['ticker']['tkr_rate_str']?></span>
                                <!-- increase 증가, decrease 감소 -->
                            </li>
                        </ul>
                        <!-- //detail -->
                    </div>
                    <!-- //chartData -->
                    <!-- 투자의견 -->
                    <div class="opinion">
                        <span class="invest">애널리스트 컨센서스</span>
                        <span class="recom <?=$this->mri_tb_model->getInvestOpinionByStar(intval($r['an_opinion']), $divide=2)?>"><?=$this->mri_tb_model->getInvestOpinionByStar(intval($r['an_opinion']))?></span>
                        <!-- 매수 : class = buying, 매도 : class = sell -->
                    </div>
                    <!-- //opinion -->

                    <div class="chartbtm">
                        <dl>
                            <dt class="charm">투자매력</dt>
                            <dd class="charm_num"><?=$r['m_biz_total_score']?></dd>
                        </dl>
                        <dl>
                            <dt class="value">밸류에이션</dt>
                            <dd class="just">
                                <figure class="highcharts-figure">
                                    <div id="list_value<?=$r['an_id']?>" class="contaanalysis2"></div>
                                    <span class="proper"><?=$this->common->get_valuation_stars_text($r['expected_star'])?></span>
                                </figure>
                                <script>SubAnalyGaugeChart2('list_value<?=$r['an_id']?>', [<?=$r['expected_star']?>]);</script>
                            </dd>
                        </dl>
                    </div>
                </div>
                <!-- //chart_area -->

                <?php 
                endforeach;
                ?>
