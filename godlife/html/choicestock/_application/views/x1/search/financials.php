            <?=$ticker_header?>
<?php if($sec_ticker=='' && $pri_ticker=='') {?>
            <div class="search_financials_area">
                <div class="tabs_menu">
                    <span onclick="location.href='/<?=X1?>_search/financials/<?=$ticker_code?>/MRT';" class="<?=$dimension=='MRT' ? 'active' : ''?>">연환산</span>
                    <span onclick="location.href='/<?=X1?>_search/financials/<?=$ticker_code?>/MRY';" class="<?=$dimension=='MRY' ? 'active' : ''?>">연간</span>
                    <span onclick="location.href='/<?=X1?>_search/financials/<?=$ticker_code?>/MRQ';" class="<?=$dimension=='MRQ' ? 'active' : ''?>">분기</span>
                    <strong class="unit"><?php if(isset($ticker_currency) && $ticker_currency) echo '* 단위 : '.$ticker_currency;?></strong>
                </div>
                <!-- //tabs_menu -->

<?php 
                $dates = array_keys($history);
                foreach($table_field_title_map as $table => $field_title_map) : 
                    $table_name = $table_title_map[$table];
                ?>

				<div class="scroll_table_wrap">
					<!-- 투자지표 table -->
					<h4 class="table_title"><?=$table_name?></h4>
					<div class="scroll_table">
						<?php if(sizeof($dates)>0) :?>
						<table cellspacing="0" border="1" class="tableRanking">
							<colgroup>
								<col width="">
								<?php for($i = 0 ; $i < sizeof($dates) ; $i++) : ?>
								<col width="">
								<?php endfor; ?>
							</colgroup>
							<thead>
								<tr>         
									<th><!--<h4 class="tltle"><?=$table_name?></h4>--></th>
									<?php foreach($dates as $d) : $d = date('y.m/d', strtotime(str_replace('.','-',$d)));?>
									<th><span><?=$d?></span></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach($field_title_map as $f => $tit) :
								?>
								<tr>
									<td><?=$tit?></td>
									<?php foreach($dates as $d) : ?>
									<?php if(strstr($f,'_ori')) $history[$d][$f] = number_format($history[$d][$f]/1000000);?>
									<?php if($ticker_unit>0) :?>
									<td><?=($f=='sf1_accoci'||$f=='sf1_retearn') ? number_format((str_replace(',','',$history[$d][$f])/$ticker_unit)/1000000) : number_format(str_replace(',','',$history[$d][$f])/$ticker_unit)?></td>
									<?php else :?>
									<td><?=($f=='sf1_accoci'||$f=='sf1_retearn') ? number_format($history[$d][$f]/1000000) : $history[$d][$f]?></td>
									<?php endif;?>
									<?php endforeach; ?>
								</tr>
								<?php
								endforeach;
								?>
							</tbody>
						</table>
						<?php else :?>
						<table cellspacing="0" border="1" class="tableRanking">
							<tbody>
								<?php 
								foreach($field_title_map as $f => $tit) :
								?>
								<tr>
									<td><?=$tit?></td><td>N/A</td>
								</tr>
								<?php
								endforeach;
								?>
							</tbody>

						</table>
						<?php endif;?>
					</div>
				</div>
                
                <?php endforeach; ?>                
                </div>
                <!-- //search_financials_area -->
            </div>
            <!-- //sub_mid -->

<?php }else{?>
		<!-- 주요 콘텐츠 -->
		<div class="sub_mid nondata">
			<p class="nodata_guide"><strong><?=$sec_ticker;?></strong>의 상세 기업정보는 <strong><a href="/<?=X1?>_search/invest_charm/<?=$pri_ticker?>"><?=$pri_ticker?></a></strong> 종목에서 확인할 수 있습니다.</p>
		</div>
		<!-- //sub_mid nondata -->
<?php }?>