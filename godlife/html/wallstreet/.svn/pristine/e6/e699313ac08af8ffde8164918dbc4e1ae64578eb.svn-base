                <?php 
                foreach($rc_list as $r) : 
                    $class = 'decrease';
                    if($r['ticker']['tkr_rate'] > 0) {
                        $class = 'increase';
                    }
                ?>
                <div class="chart_area" data-id="<?=$r['rc_id']?>">
                    <div class="chartData">
                        <h4 class="event_name"><?=$r['ticker']['tkr_name']?></h4>
                        <ul class="detail">
                            <li class="num"><span class="<?=$class?>"><?=$r['ticker']['tkr_close']?></span></li>
                            <li class="per"><span class="<?=$class?>"><?=$r['ticker']['tkr_rate_str']?></span> <!-- increase 증가, decrease 감소 -->
                            </li>
                        </ul>
                        <!-- //detail -->
                        <div id="recom<?=$r['rc_id']?>" class="containerS2"></div><!-- 종목추천 id = containerS2 -->
                        <script>SubRecomListChart('recom<?=$r['rc_id']?>', [<?=$r['chart_value']?>]);</script>
                    </div>
                    <!-- //chartData -->

                    <div class="chartGoal">
                        <dl>
                            <dt>추천가</dt>
                            <dd><?=$r['rc_recom_price']?></dd>
                        </dl>
                        <dl>
                            <dt>목표가</dt>
                            <dd><?=$r['rc_goal_price']?></dd>
                        </dl>
                        <?php if($r['rc_endtype'] == 'SUCCESS') : ?>
                        <span class="attainment">달성 <?=date('y.m/d', strtotime($r['rc_enddate']))?></span>
                        <?php endif; ?>                        
                    </div>
                    <!-- //chartGoal -->
                </div>
                <?php endforeach; ?>
