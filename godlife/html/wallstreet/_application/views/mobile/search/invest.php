            <?=$ticker_header?>
<?php if($sec_ticker=='' && $pri_ticker=='') {?>
            <div class="search_financials_area">
                <div class="tabs_menu">
                    <span onclick="location.href='/search/invest/<?=$ticker_code?>/MRT?pn=<?=$part_name;?>&pg=<?=$part_page;?>';" class="<?=$dimension=='MRT' ? 'active' : ''?>">연환산</span>
                    <span onclick="location.href='/search/invest/<?=$ticker_code?>/MRY?pn=<?=$part_name;?>&pg=<?=$part_page;?>';" class="<?=$dimension=='MRY' ? 'active' : ''?>">연간</span>
                    <span onclick="location.href='/search/invest/<?=$ticker_code?>/MRQ?pn=<?=$part_name;?>&pg=<?=$part_page;?>';" class="<?=$dimension=='MRQ' ? 'active' : ''?>">분기</span>
                </div>
                <!-- //tabs_menu -->

                <?php 
                $dates = array_keys($history);
                foreach($table_field_title_map as $table => $field_title_map) : 
                    $table_name = $table_title_map[$table];
                ?>
                
                <table cellspacing="0" border="1" class="tableRanking table_search_invest">
                    <colgroup>
                        <col width="">
                        <?php for($i = 0 ; $i < sizeof($dates) ; $i++) : ?>
                        <col width="">
                        <?php endfor; ?>
                    </colgroup>
                    <thead>
                        <tr>
                            <th><h4 class="tltle"><?=$table_name?></h4></th>         
                            <?php foreach($dates as $d) : ?>
                            <th><span><?=substr($d,2)?></span></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
						//echo '<pre>'; print_r($field_title_map );
                        foreach($field_title_map as $f => $tit) :

							if($tit[0]=='이자보상배수') $tit[0]='이자보상<br>배수';
                        ?>
                        <tr>
                            <td class="th_guide"><span><?=$tit[0]?></span>
                            <div class="guide_box"><ul><li><?=$tit[1];?></li></ul></div></td>
                            <?php foreach($dates as $d) : ?>
                            <td><?=$history[$d][$f]?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
                <?php endforeach; ?>

                <p class="table_guide">* 재무데이터는 달러(USD)로 일괄조정</p>   
                
                <!-- 테이블 안내 툴팁 -->
                <div class="th_guide_hide">
                    <div class="guide_box"><ul><li>주당순이익(달러)</li></ul></div><!-- EPS -->
                    <div class="guide_box"><ul><li>주당순자산(달러)</li></ul></div><!-- BPS -->
                    <div class="guide_box"><ul><li>주당배당금(달러)</li></ul></div><!-- DPS -->
                    <div class="guide_box"><ul><li>주가수익배수(배)</li></ul></div><!-- PER -->
                    <div class="guide_box"><ul><li>주가순자산비율(배)</li></ul></div><!-- PBR -->
                    <div class="guide_box"><ul><li>주가매출액배수(배)</li></ul></div><!-- PSR -->
                    <div class="guide_box"><ul><li>자기자본이익률</li></ul></div><!-- ROE -->
                    <div class="guide_box"><ul><li>총자산이익률</li></ul></div><!-- ROA -->
                    <div class="guide_box"><ul><li>투하자본이익률</li></ul></div><!-- ROIC -->
                </div>
                <!-- //th_guide_hide -->
                
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