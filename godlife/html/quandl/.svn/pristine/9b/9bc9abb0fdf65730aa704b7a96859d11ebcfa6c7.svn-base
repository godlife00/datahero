    <div class="schChartTitle">

		<div>
			<h2 class="title"><?=(isset($company_info['cp_korname']) && $company_info['cp_korname'] != '') ? $company_info['cp_korname']:$ticker['tkr_name']?></h2>
			<ul class="info">
				<li class="sum"><span class="eng"><?=$ticker['tkr_ticker']?></span></li>
				<li class="category"><?=$ticker['tkr_exchange']?></li>
				<?php if($mri_data['m_biz_dividend_score'] >= 16) : ?>
				<li class="category"><a href="https://www.choicestock.co.kr/stock/recipe/dividend?pt=CSPART003">배당매력주</a></li>
				<?php endif; ?>
				<?php if($mri_data['m_biz_growth_score'] >= 16) : ?>
				<li class="category"><a href="https://www.choicestock.co.kr/stock/recipe/growth?pt=CSPART003">이익성장</a></li>
				<?php endif; ?>
				<?php if($mri_data['m_biz_moat_score'] >= 16) : ?>
				<li class="category"><a href="https://www.choicestock.co.kr/stock/recipe/moat?pt=CSPART003">소비자독점주</a></li>
				<?php endif; ?>
			</ul>
			<!-- //info -->
		</div>

        <ul class="detail">
			<?php if($is_open === true) :?>
            <li class="num">
			<span class='sync_price' data-ticker='<?=$ticker['tkr_ticker']?>' data-render="((el, txt, info) => { var tmp = txt.split('.'); return tmp[0]+'.<b>'+tmp[1]+'</b>';})"><?=$ticker['tkr_close']?></span>
			</li>
            <li class="per">
			<span data-ticker='<?=$ticker['tkr_ticker']?>' data-render="((el, txt, info) => { el.removeClass('increase'); el.removeClass('decrease'); var c='decrease'; if(parseFloat(txt) > 0) { c='increase'; info.diff_rate = '+'+info.diff_rate; txt = '+ '+txt; } el.addClass(c); return txt + ' <span>('+info.diff_rate+'<b>%</b>)</span>'; })" class="sync_diff_price <?=$ticker['tkr_rate'] > 0 ? 'increase' : 'decrease'?>"><?=$ticker['tkr_diff_str']?> <span>(<?=$ticker['tkr_rate_str']?>)</span></span>
            </li>
            <li class="day"><?php if(isset($ticker['tkr_lastpricedate'])&&$ticker['tkr_lastpricedate']) echo date('y.m/d H:i', strtotime($ticker['tkr_lastpricedate'])).', ';?> USD</li>
			<?php else :?>
            <li class="num"><?=number_format($last_sep['sep_close'], 2)?></li>
            <li class="per">
			<?php
			if($last_sep['sep_diff_rate'] == 0) { 
				echo '<span class="increase">0.00 <span>(+0.00%)</span></span> <!-- increase 증가, decrease 감소 -->';
			} else if($last_sep['sep_diff_rate'] > 0) {
				echo '<span class="increase">+'.number_format($last_sep['sep_diff_price'], 2).' <span>(+'.$last_sep['sep_diff_rate'].'%)</span></span> <!-- increase 증가, decrease 감소 -->';
			} else {
				echo '<span class="decrease">'.number_format($last_sep['sep_diff_price'], 2).' <span>('.$last_sep['sep_diff_rate'].'%)</span></span> <!-- increase 증가, decrease 감소 -->';
			}
			?>
            </li>
            <!-- //투표결과(매수, 매도) : 최근3개월/ 전체기간의 다수 투표 항목 -->
            <li class="day"><?=date('y.m/d', strtotime($last_sep['sep_date']))?>, USD</li>
			<?php endif;?>
        </ul>

		<ul class="info_icon">
			<li><?=$ticker['tkr_exchange']?></li>
			<?php if($mri_data['m_biz_dividend_score'] >= 16) : ?>
			<li><a href="https://www.choicestock.co.kr/stock/recipe/dividend?pt=CSPART003">배당매력주</a></li>
			<?php endif; ?>
			<?php if($mri_data['m_biz_growth_score'] >= 16) : ?>
			<li><a href="https://www.choicestock.co.kr/stock/recipe/growth?pt=CSPART003">이익성장</a></li>
			<?php endif; ?>
			<?php if($mri_data['m_biz_moat_score'] >= 16) : ?>
			<li><a href="https://www.choicestock.co.kr/stock/recipe/moat?pt=CSPART003">소비자독점주</a></li>
			<?php endif; ?>
		</ul>

		<!-- 제휴사 배너 -->
		<?php if($up_rand == '0') :?>
		<div class="alliance_banner ab_pc imsi_div">
			<h2 class="b_title"><?=($up_banner['rc_subtitle'] != '') ? $up_banner['rc_subtitle']:$up_banner['rc_title']?></h2>
			<p class="b_pdion"><span>예상 수익률</span><strong><?=$up_banner['ticker_revenue']?><b>%</b></strong></p>
			<a href="https://www.choicestock.co.kr/stock/recommend?pt=CSPART003" class="link_btn" target="_blank">종목추천 확인하기<i></i></a>
		</div>
		<?php elseif($up_rand == '1') :?>
		<div class="alliance_banner ab_pc imsi_div">
			<h2 class="e_title">적중! 수익 실현 종목</h2>
			<p class="e_pdion"><span>수익률</span><strong><?=$up_banner['ticker_revenue']?><b>%</b></strong></p>
			<p class="event_name"><?=$up_banner['tkr_name']?></p>
			<a href="https://www.choicestock.co.kr/stock/recommend_view/<?=$up_banner['rc_id']?>?pt=CSPART003" class="e_link_btn" target="_blank">투자전략 확인하기<i></i></a>
		</div>
		<?php else :?>
		<div class="alliance_banner ab_pc imsi_div">
			<table cellspacing="0" border="1" class="table_jump">
				<tbody>
					<tr>
						<th class="th_01"><span>투자매력 급등주</span></th>
						<th class="th_02"><span><?=$up_banner['tkr_close']?></span></th>
						<th class="th_03"><span>투자매력</span></th>
					</tr>
					<tr>
						<td class="td_01"><span><?=$up_banner['m_korname']?></span></td>
						<?php $up_banner['tkr_rate_str'] = str_replace('+','<b>+</b>',$up_banner['tkr_rate_str']);?>
						<?php $up_banner['tkr_rate_str'] = str_replace('-','<b>-</b>',$up_banner['tkr_rate_str']);?>
						<td class="td_02"><span><?=str_replace('%','<b>%</b>',$up_banner['tkr_rate_str']);?></span></td>
						<td class="td_03"><span><?=$up_banner['m_biz_total_score']?><b>점</b></span></td>
					</tr>
				</tbody>
			</table>
			<a href="https://www.choicestock.co.kr/attractiveness/attractive?sort=total&netincome=all&marketcap=over100billion&pt=CSPART003" class="j_link_btn" target="_blank">투자매력 종목 더보기<i></i></a>
		</div>
		<?php endif;?>
		<!-- 제휴사 배너 -->
    </div>

	<!-- 투자매력점수 -->
	<?php if($down_rand == '1') :?>
	<div class="invest_charm_b ic_mobile imsi_div">
		<div class="chartData">
			<dl>
				<dt class="dt_title">투자매력점수</dt>
				<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
				<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
				<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
				<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
				<?php endif;?>
			</dl>
			<dl>
				<dt class="dt_title"><span><strong>적중!</strong> 수익실현</span></dt>                                
				<dd class="proper">
					<span class="num increase"><?=$down_banner['ticker_revenue']?><b>%</b></span>
				</dd>
			</dl>
		</div>
		<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
	</div>
	<?php elseif($down_rand == '2') :?>
	<div class="invest_charm_b ic_mobile imsi_div">
		<div class="chartData">
			<dl>
				<dt class="dt_title">투자매력점수</dt>
				<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
				<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
				<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
				<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
				<?php endif;?>
			</dl>
			<dl>
				<dt class="dt_title">종목추천목표가</dt>                                
				<dd class="lock"><span><img src="/img/img_lock2.png" alt="잠김"></span></dd>                                
			</dl>
		</div>
		<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
	</div>
	<?php elseif($down_rand == '3') :?>
	<div class="invest_charm_b ic_mobile imsi_div">
		<div class="chartData">
			<dl>
				<dt class="dt_title">투자매력점수</dt>
				<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
				<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
				<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
				<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
				<?php endif;?>
			</dl>
			<dl>
				<dt class="dt_title">적정주가</dt>
				<!-- <dt class="dt_title"><span><strong>적중!</strong> 수익실현</span></dt> -->                                
				<dd class="proper">
					<span class="num increase"><?=str_replace('.00', '.<b>00</b>', $down_banner['valuation']['m_v_fairvalue3'])?></span>
				</dd>
			</dl>
		</div>
		<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
	</div>
	<?php else :?>
		<?php if(is_array($down_banner['valuation']) && sizeof($down_banner['valuation'])>0) :?>
			<div class="invest_charm_b ic_mobile imsi_div">
				<div class="chartData">
					<dl>
						<dt class="dt_title">투자매력점수</dt>
						<dd class="num"><strong><?=$down_banner['valuation']['m_biz_total_score']?></strong>점</dd>
						<?php if($down_banner['valuation']['pre_bizscore']>0) :?>
						<?php $score_diff = $down_banner['valuation']['m_biz_total_score']-$down_banner['valuation']['pre_bizscore'];?>
						<dd class="prepare <?=($score_diff>0) ? 'increase':'decrease'?>">전월대비 <strong><?=($score_diff>0) ? '<b>+</b>'.$score_diff : str_replace('-','<b>-</b>',$score_diff)?></strong></dd>
						<?php endif;?>
					</dl>
					<dl>
						<dt class="dt_title">적정주가</dt>                                
						<dd class="lock"><span><img src="/img/img_lock2.png" alt="잠김"></span></dd>                                
					</dl>
				</div>
				<a href="https://www.choicestock.co.kr/main/service?pt=CSPART003" target="_blank" class="show_pop">분석보기<i></i></a>
			</div>
		<?php endif;?>
	<?php endif;?>
	<!-- //투자매력점수 -->