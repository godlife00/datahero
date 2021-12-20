            <div class="sub_top">
                <div class="swiper-container swiper-slideChar">
                    <div class="swiper-wrapper">
                    <?php 
                        foreach($top_recom as $idx => $tr) : 
                            $class = 'decrease';
                            if($tr['ticker']['tkr_rate'] > 0) {
                                $class = 'increase';
                            }
                    ?>
                        <div class="swiper-slide">
                            <div class="chart_area" data-id="<?=$tr['rc_id']?>">
                                <div class="chartData left_area">
                                    <h2 class="title" title="<?=$tr['ticker']['tkr_name_en']?>"><a href="/stock/recommend_view/<?=$tr['rc_id']?>"><?=$tr['ticker']['tkr_name']?></a></h2>
                                    <ul class="info">
                                        <li class="sum"><span class="eng"><?=$tr['rc_ticker']?></span> </li>
                                    </ul>
                                    <!-- //info -->
                                    <ul class="detail">
                                        <li class="num <?=$class?>"><?=$tr['ticker']['tkr_close']?></li>
                                        <li class="per">
                                            <span class="<?=$class?>"><?=$tr['ticker']['tkr_diff_str']?> <?=$tr['ticker']['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </li>
                                    </ul>
                                    <!-- //detail -->
                                    <div id="top_recom<?=$idx?>" class="containerS1"></div><!-- 종목추천 id = containerS1 -->
                                    <script>SubRecomTopChart('top_recom<?=$idx?>', [<?=$tr['chart_value']?>]);</script>
                                </div>
                                <!-- //chartData -->

                                <div class="right_area">
                                    <!--  수익률(추천가-현재가 수익%) 표시 -->
                                    <div class="revenue_box" style='<?=($tr['rc_endtype'] == 'ING') ? '' : 'display: none;'?>'>
                                        <span class="title"><i></i> 목표가</span>
                                        <span class="percent"><i></i> <?=$tr['rc_goal_price']?></span>
                                    </div>
                                    <!-- //수익률(추천가-현재가 수익%) 표시 -->

                                    <!-- 목표가 달성시 -->
                                    <div class="attainment_box" style="<?=($tr['rc_endtype'] == 'SUCCESS') ? '' : 'display: none;'?>">
                                        <span class="title"><i></i> 목표가달성</span>
                                        <span class="percent"><i></i> <?=date('y. m/d', strtotime($tr['rc_enddate']))?></span>
                                    </div>
                                    <!-- //목표가 달성시 -->
                                    
                                    <table cellspacing="0" border="1" class="tableRanking data_attainment">
                                        <colgroup>
                                            <col width="60px">
                                            <col width="">
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <th class="goal"><span>목표가</span></th>
                                                <td class="goal_num"><span><?=$tr['rc_goal_price']?></span></td>
                                            </tr>
                                            <tr>
                                                <th class="goal"><span>추천가</span></th>
                                                <td class="goal_num"><span><?=$tr['rc_recom_price']?></span></td>
                                            </tr>
                                            <tr>
                                                <th class=""><span>PER(배)</span></th>
                                                <td class=""><span><?=number_format($tr['dly_pe'], 2)?></span></td>
                                            </tr>
                                            <tr>
                                                <th class=""><span>PBR(배</span></th>
                                                <td class=""><span><?=number_format($tr['dly_pb'], 2)?></span></td>
                                            </tr>
                                            <tr>
                                                <th class=""><span>ROE(%)</span></th>
                                                <td class=""><span><?=number_format($tr['sf1_roe']*100, 2)?></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

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
                <div id="recom_list">
                    <?=$content_html?>
                </div>
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
                $.post('/stock/ajax_get_recommend_list', {'page': page}, function(res) {
                    if($.trim(res).length) {
                        $('#recom_list').append(res);
                    } else {
                        $('.btn_more').hide();
                    }
                    is_loading = false;
                });
            }

            $(function() {
                $('.chart_area').on('click', function() {
                    location.href = '/stock/recommend_view/' + $(this).data('id');
                });
            });
            </script>

