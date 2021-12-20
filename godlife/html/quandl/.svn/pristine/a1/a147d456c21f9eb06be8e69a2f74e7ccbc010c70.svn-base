    <div class="schChartTitle">
        <div>
            <h2 class="title"><?=$ticker['tkr_name']?></h2>
            <ul class="info">
                <li class="sum"><span class="eng"><?=$ticker['tkr_ticker']?></span> 

        <?php if(sizeof($company_info) > 0) : ?>
        <span class="span2"><?=$company_info['cp_korname']?></span></li>
        <?php endif; ?>
                <li class="category"><?=$ticker['tkr_exchange']?></li>
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
    </div>
    <!-- //schChartTitle -->            
    <ul class="tabs">                
        <li rel="tab1"<?=$active_menu == 'summary' ? ' class="active"' : ''?> onclick="location.href='/wm_stocks/summary/<?=$ticker_code?>'">요약</li>
        <li rel="tab2"<?=$active_menu == 'invest' ? ' class="active"' : ''?> onclick="location.href='/wm_stocks/invest/<?=$ticker_code?>'">투자지표</li>
        <li rel="tab3"<?=$active_menu == 'financials' ? ' class="active"' : ''?> onclick="location.href='/wm_stocks/financials/<?=$ticker_code?>'">재무제표</li>
        <li rel="tab6"<?=$active_menu == 'competitors' ? ' class="active"' : ''?> onclick="location.href='/wm_stocks/competitors/<?=$ticker_code?>'">경쟁사</li>
        <li rel="tab5"<?=$active_menu == 'vichart' ? ' class="active"' : ''?> onclick="location.href='/wm_stocks/vichart/<?=$ticker_code?>'">재무차트</li>
    </ul>


