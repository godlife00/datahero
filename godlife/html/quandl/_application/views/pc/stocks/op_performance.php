            <!-- 주요 콘텐츠 -->
            <div class="performance_card">
                <h1 class="title">최근 실적발표 <span class="day"><?=(isset($recent_report_day) && $recent_report_day !='' ) ? date('y.m/d', strtotime($recent_report_day)).', ' : ''?>USD</span></h1>
				<?php $cnt=0; foreach($recent_report as $key => $val) :?>
				<?php if($cnt>11) break;?>
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
                                        <h2 class="title"><a href="/op_stocks/summary/<?=$val['tkr_ticker']?>"><?=$val['tkr_name']?></a></h2>
                                        <ul class="info">
                                            <li class="sum"><span class="eng"><?=$val['tkr_ticker']?></span> </li>
                                            <li class="category"><?=$val['tkr_exchange']?></li>
                                        </ul>
                                        <!-- //info -->
                                        <ul class="detail">
                                            <li class="num"><?=$val['tkr_close']?></li>
                                            <li class="per">
                                                <span class="<?=($val['tkr_rate'] > 0) ? 'increase' : 'decrease'?>"><?=$val['tkr_diff']?> <span>(<?=$val['tkr_rate_str']?>)</span></span>
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
                    <p class="total">시가총액 <strong><?=number_format($val['dly_marketcap'])?> 백만달러</strong></p>
                    <p class="total">발표순이익 <strong><?=number_format($val['sf1_netinccmnusd']/1000000)?> 백만달러</strong></p>
                    <p class="total">전년대비 성장률 <strong class="<?=($recent_report_rates_pm[$val['tkr_ticker']] > 0) ? 'increase' : 'decrease'?>"><?=$recent_report_rates[$val['tkr_ticker']]?></strong></p>
                    <!-- increase 증가, decrease 감소 -->
                </div>
				<?php $cnt++; endforeach;?>

				<div class="paginate">
					<?=$paging_html ?>
            </div>