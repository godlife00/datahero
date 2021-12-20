			<!-- 주요 콘텐츠 -->
            <div class="trends_list">
                <h1 class="title">산업동향 <span class="day"><?=(isset($last_date) && $last_date !='' ) ? '기준일 : '.date('y.m/d', strtotime($last_date)).', ':''?>USD</span></h1>

                <div class="standard">
                    <div class="select open">
                        <span class="ctrl"><span class="arrow"></span></span>

                        <button type="button" class="my_value"><?=$selected_sec?></button>
                        <ul class="a_list">
							<?php foreach($sector_list as $sec) :?>
							<?php //if($selected_sec != $sec) :?>
                            <li><a href="javascript:location.href='/op_stocks/trends?selected_sec=<?=$sec?>'"><?=$sec?></a></li>
                            <!--<li><a href="javascript:fnTrends('<?=$sec?>');"><?=$sec?></a></li>-->
							<?php //endif;?>
							<?php endforeach;?>
                        </ul>
                    </div>
                    <span class="trends_num">총 <strong><?=number_format($sec_total_count)?></strong> 종목 (상승 <?=number_format($sec_rise_count)?>종목 / 하락 <?=number_format($sec_fall_count)?>종목)</span>
                </div>
                <!-- //standard -->

                <div class="table_left">
                    <h2 class="h2_title">상승 종목</h2>
                    <table cellspacing="0" border="1" class="tableColtype trends">
                        <colgroup>
                            <col width="">
                            <col width="">
                            <col width="">
                            <col width="">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>종목명</th>
                                <th><strong>종가</strong></th>
                                <th><strong>등락률</strong></th>
                            </tr>
                        </thead>
                        <tbody>
							<?php foreach($rise_ticker as $rise) :?>
                            <tr>
                                <td class="title"><a href="/op_stocks/summary/<?=$rise['tkr_ticker']?>"><?=$rise['tkr_name']?><span
                                            class="ticker"><?=$rise['tkr_ticker']?></span></a></td>
                                <td class="num">
                                    <span><?=$rise['tkr_close']?></span>
                                </td>
                                <td class="moti">
                                    <span class="increase">+<?=$rise['tkr_diff']?> <span class="increase">(<?=$rise['tkr_rate_str']?>)</span></span>
                                    <!-- increase 증가, decrease 감소 -->
                                </td>
                            </tr>
							<?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="table_right">
                    <h2 class="h2_title">하락 종목</h2>
                    <table cellspacing="0" border="1" class="tableColtype trends">
                        <colgroup>
                            <col width="">
                            <col width="">
                            <col width="">
                            <col width="">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>종목명</th>
                                <th><strong>종가</strong></th>
                                <th><strong>등락률</strong></th>
                            </tr>
                        </thead>
                        <tbody>
							<?php foreach($fall_ticker as $fall) :?>
                            <tr>
                                <td class="title"><a href="/op_stocks/summary/<?=$fall['tkr_ticker']?>"><?=$fall['tkr_name']?><span
                                            class="ticker"><?=$fall['tkr_ticker']?></span></a></td>
                                <td class="num">
                                    <span><?=$fall['tkr_close']?></span>
                                </td>
                                <td class="moti">
                                    <span class="decrease"><?=$fall['tkr_diff']?> <span class="decrease">(<?=$fall['tkr_rate_str']?>)</span></span>
                                    <!-- increase 증가, decrease 감소 -->
                                </td>
                            </tr>
							<?php endforeach;?>
                        </tbody>
                    </table>

                </div>