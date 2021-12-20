                                    <?php foreach($list as $key => $val) : ?>
                                    <tr>
                                        <td class="title">
                                            <a href="/search/invest_charm/<?=$val['m_ticker']?>"><?=$val['m_korname']?><span class="ticker"><?=$val['m_ticker']?></span></a>
                                        </td>
                                        <td class="num">
                                            <span><?=number_format($val['tkr_close'], 2)?></span>
                                            <span class="<?=$val['tkr_diff'] > 0 ? 'in' : 'de'?>crease"><?=$val['tkr_rate_str']?></span>
                                            <!-- increase 증가, decrease 감소 -->
                                        </td>
                                        <td>
                                            <div class="star_area">
                                                <div class="starRev">
                                                    <?php 
                                                        $stars = 0;
                                                        $price = 0;
                                                        if($type == 'dividend') {
                                                            $stars = $val['m_biz_dividend_stars'];
                                                            $price = number_format($val['sf1_divyield']*100, 2);
															$sign = '%';

                                                        } else if($type == 'growth') {
                                                            $stars = $val['m_biz_growth_stars'];
                                                            $price = number_format($val['m_g_epsgr'], 2);
															$sign = '<span class="dollar">%</span></span>';

                                                        } else if($type == 'moat') {
                                                            $stars = $val['m_biz_moat_stars'];
                                                            $price = number_format($val['sf1_opmargin'], 2);
															$sign = '%';

                                                        } else if($type == 'total_score') {
                                                            $stars = $val['m_biz_total_score'] / 20;
                                                            $price = number_format($val['m_g_roe'], 2);
															$sign = '<span class="dollar">%</span></span>';
                                                        }

														for($i = 1 ; $i <= 5 ; $i++) { 
															if($stars >= $i) {
																echo '<span class="starR on">별1</span>';
															}
															else {
																if($i-$stars <= 0.5) {
																	echo '<span class="starR on half">별1</span>';
																}
																else {
																	echo '<span class="starR">별1</span>';
																}
															}
														}
													?>
                                                    <!-- class = starR on 인 경우 별표시-->
                                                </div>
                                            </div>
                                            <!-- //star_area -->
                                        </td>
                                        <td class="allocation">
                                            <span><?=$price?> <?=$sign?></span>
                                        </td>
                                    </tr>                                    
                                    <?php endforeach; ?>
