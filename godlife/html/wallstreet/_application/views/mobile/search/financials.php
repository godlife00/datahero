            <?=$ticker_header?>
<?php if($sec_ticker=='' && $pri_ticker=='') {?>
            <div class="search_financials_area">
                <div class="tabs_menu">
                    <span onclick="location.href='/search/financials/<?=$ticker_code?>/MRT?pn=<?=$part_name;?>&pg=<?=$part_page;?>';" class="<?=$dimension=='MRT' ? 'active' : ''?>">연환산</span>
                    <span onclick="location.href='/search/financials/<?=$ticker_code?>/MRY?pn=<?=$part_name;?>&pg=<?=$part_page;?>';" class="<?=$dimension=='MRY' ? 'active' : ''?>">연간</span>
                    <span onclick="location.href='/search/financials/<?=$ticker_code?>/MRQ?pn=<?=$part_name;?>&pg=<?=$part_page;?>';" class="<?=$dimension=='MRQ' ? 'active' : ''?>">분기</span>
                    <strong class="unit"><?php if(isset($ticker_currency) && $ticker_currency) echo '* 단위 : '.$ticker_currency;?></strong>
                </div>

                <!-- //tabs_menu -->

<?php 
                $dates = array_keys($history);
                foreach($table_field_title_map as $table => $field_title_map) : 
                    $table_name = $table_title_map[$table];
                ?>
                
                <table cellspacing="0" border="1" class="tableRanking">
                    <colgroup>
                        <col width="">
                        <?php for($i = 0 ; $i < sizeof($dates) ; $i++) : ?>
                        <col width="">
                        <?php endfor; ?>
                    </colgroup>
                    <thead>
                        <tr>         
                            <th><h4 class="tltle"><?=$table_name?></h4></th>            
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
                            <td><?=number_format(str_replace(',','',$history[$d][$f])/$ticker_unit);?></td>
							<?php else :?>
                            <td><?=$history[$d][$f]?></td>
                            <?php endif;?>
							
							
							<?php endforeach; ?>
                        </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
                
                <?php endforeach; ?>                
                
                </div>
                <!-- //search_financials_area -->

            </div>
            <!-- //sub_mid -->

<?php }else{?>
	<!-- 종목검색 class = sub_search-->
	<div id="container" class="sub_search">
		<!-- 주요 콘텐츠 -->
		<div class="sub_mid nondata">
			<p class="nodata_guide"><strong><?=$sec_ticker;?></strong>의 상세 기업정보는 <strong><a href="/search/invest_charm/<?=$pri_ticker?>?pn=<?=$part_name;?>&pg=<?=$part_page;?>"><?=$pri_ticker?></a></strong> 종목에서 확인할 수 있습니다.</p>
		</div>
		<!-- //sub_mid nondata -->
	</div>
	<!-- //container -->
<?php }?>