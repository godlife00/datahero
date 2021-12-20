	<?php 	
	$active_menu = 'competitors';
	$ticker_code = $ticker['tkr_ticker'];
	include_once dirname(__FILE__).'/submenu.php'; 

 
        $tabs = array(
	    '경쟁사' 		=> $competitor,
	    '산업 상위종목' 	=> $industry_top,
        );
	$tab_titles = array(
	    '경쟁사' 		=> $competitor_titles,
	    '산업 상위종목' 	=> $industry_top_titles,
            //'재무상태표' => $balancesheet_titles,
            //'손익계산서' => $incomestate_titles,
            //'현금흐름표' => $cashflow_titles,
            //'주가지표' => $pricesheet_titles,
        );

	if($tab_idx < 0 || $tab_idx >= sizeof($tabs)) {
		$tab_idx = 0;
	}
	?>
         
                
            
            <div class="tableData">
                <ul class="competitors txtCompetitors">

		<?php 
		foreach(array_keys($tabs) as $idx => $field_title) : 
		?>
                    <li><a href="#" class="<?=$idx == $tab_idx ? 'active' : ''?>" onclick="manager.setTabIdx(<?=$idx?>)"><?=$field_title?></a></li>
		<?php endforeach;?>
                </ul>
                <!-- //competitors -->

                <div class="table_nav">
                    <span>산업</span>
                    <span><?=$ticker['tkr_industry']?></span>
                </div>
				<!-- //table_nav -->

				<div class="standard">                    
					<span class="title">기준</span>
					<span style="color: #666; font-size: 12px; font-weight: bold;">백만달러</span>
				</div>
				
				<div class="standard" style="display:none">                    
                    <span class="title">기준</span>
                    <!-- <div class="select open" style="width:90px;">
                        <span class="ctrl"><span class="arrow"></span></span>
                        <button type="button" class="my_value">달러 USD</button>
                        <ul class="a_list">
                        <li><a href="#1">달러 USD</a></li>
                        <li><a href="#2">원화 KRW</a></li>                        
                        </ul>
                    </div> -->
                    <div class="select open" style="width:82px;">
                        <span class="ctrl"><span class="arrow"></span></span>
                        <button type="button" class="my_value"><?=$this->sf1_tb_model->getUnitnumText($ticker['tkr_currency'], $country_unitnum_map[$country_unitnum])?></button>
                        <?php 
                        /*
                        <ul class="a_list">
                        <li><a href="#1">달러</a></li>
                        <li><a href="#2">TWD(백만)</a></li>
                        <li><a href="#3">TWD(천만)</a></li>
                        </ul>
                        */
                        ?>
                    </div>
                    <!--<button class="simple"><i></i>간단히</button>-->
                </div>
                <!-- //standard -->

		<?php 

		foreach($tabs as $field_title => $data) : 
			$field_title_map = $tab_titles[$field_title];
		?>

                <table cellspacing="0" border="1" class="tableColtype typeOrder competitorsTable">
                    <caption><img src="/img/globalstock/img/txt/txt_investment.png" alt="투자 지표"></caption>
                    <colgroup>
                        <col width="210px">
                        <col width="107px" span="4">
                        <col width="*" span="6">
                    </colgroup>
                    <thead>
                        <tr>
			    <?php 
			    foreach($field_title_map as $title) : 
			    ?>
                            <th scope="col"><?=$title?></th>
			    <?php 
			    endforeach; 
			    ?>
			    <?php 
			    /*
			    // publishing
                            <th scope="col">시가총액</th>
                            <th scope="col"><b>매출액</b></i></th>
                            <th scope="col">영업이익</th>
                            <th scope="col">순이익</th>
                            <th scope="col"><b>PER</b><i class="ascending"></i></th><!-- 오름차순 -->
                            <th scope="col">PBR<i class="descending"></i></th><!-- 내림차순 -->
                            <th scope="col">PSR<i class="ascending"></i></th><!-- 오름차순 -->
                            <th scope="col">ROE<i class="ascending"></i></th><!-- 오름차순 -->
                            <th scope="col">ROA<i class="ascending"></i></th><!-- 오름차순 -->
                            <th scope="col">ROIC<i class="ascending"></i></th><!-- 오름차순 -->
			    */
			    ?>
                        </tr>
                    </thead>
                    <tbody>                        
		    	<?php 

                $convert_usd_exists = false;
				$line=0;
                foreach($data as $row) : 
							$line++;
                    		$is_convert_to_usd = $this->historylib->is_convert_to_usd($row);
                            if( ! $convert_usd_exists && $is_convert_to_usd) {
                                $convert_usd_exists = true;
                            }
                ?>
                        <tr<?php if($row['tkr_ticker'] == $ticker['tkr_ticker']) echo ' class="active"'; else echo '';?>>
			    <?php 
						$col=0;

			            foreach(array_keys($field_title_map) as $field) : 
					$col++;
			    	if($field == 'tkr_name') {
					$style=($row['tkr_ticker'] == $ticker['tkr_ticker']) ? : '';
			    ?>

					
					<?php
						//if( $this->session->userdata('is_login') === false ) {
						if( $this->session->userdata('is_login') == 'a' ) {
							//if( $line < 5 && $row['tkr_ticker'] != $ticker['tkr_ticker']) {
					?>

								<td><a href="/stocks/summary/<?=$row['tkr_ticker']?>" class="drop" <?=$style?>><?=isset($row['cp_korname']) ? $row['cp_korname'] : $row['tkr_name']?></a><span class="eng"><?=$row['tkr_name']?></span></td>
					<?php
						}
						else {
					?>
								<td><a href="/stocks/summary/<?=$row['tkr_ticker']?>" class="drop" <?=$style?>><?=isset($row['cp_korname']) ? $row['cp_korname'] : $row['tkr_name']?></a><span class="eng"><?=$row['tkr_name']?></span></td>
					<?php
						}
					?>
							</td>
			    <?php } else {
							//if( $col > 5 && $this->session->userdata('is_login') === false) {
							if( $col > 5 && $this->session->userdata('is_login') == 'a') {
								echo '<td><span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span></td>';
							}
							else {
								if($field=='sf1_roic') {
									if( isset($row['sf1_invcapavg']) && $row['sf1_invcapavg'] < 0 ) {
										$row[$field] = 'N/A';
									}

									$dot = '';if($is_convert_to_usd && in_array($field, array('sf1_revenueusd', 'sf1_opinc', 'sf1_netinc'))) $dot = '*';
									echo '<td>'.$dot.$row[$field].'</td>';
								}
								else {
									$dot = '';if($is_convert_to_usd && in_array($field, array('sf1_revenueusd', 'sf1_opinc', 'sf1_netinc'))) $dot = '*';
									echo '<td>'.$dot.$row[$field].'</td>';
								}
							}
				?> 
			    <?php }?>

			    <?php endforeach; ?>
                        </tr>
			<?php endforeach; ?>
                    </tbody>
                </table>
                
        <?php endforeach; ?>
		<?php include_once INC_PATH.'/login_guide.php'; ?>
				
				<p class="tableInfo">* 매출액, 영업이익, 순이익 : 연환산(최근 4분기 합) 기준</p>

                <?php if( $convert_usd_exists ) : ?>
                <p class="tableInfo" style="margin-top: 5px">* 경쟁사 비교를 위해 USD값으로 일괄 조정</p><!-- usd 외 기업이 포함되어 있을 경우 하단 주석 추가 표시 -->
                <?php endif; ?>
                
                <p class="dataLink">data from <a href="https://www.quandl.com/" target="_blank">Quandl and Sharadar</a></p>
            </div>
			<!-- //tableData -->            
			
			<!-- 검색결과 하단 가로 배너 -->
            <div style="margin-top:30px; text-align: center;">
                <ins class="adsbygoogle"
                    style="display:block;text-align: center;"
                    data-ad-client="ca-pub-6896844206786605"
                    data-ad-slot="6293979879"
                    data-ad-format="auto"
                    data-full-width-responsive="true">
                </ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <!-- //검색결과 하단 가로 배너 -->

<script>
var PageManager = function() {
	var params = {
		tab_idx : <?=intval($tab_idx)?>,
		country_unitnum : '<?=$country_unitnum?>'
	};
	$('div.tableData table').hide().eq(params.tab_idx).show();

	this.setTabIdx = function(idx) {
		if(idx < 0 || idx >= <?=sizeof($tabs)?>) {
			return;
		}
		params.tab_idx = idx;
		$('div.tableData table').hide().eq(params.tab_idx).show();
	}
	this.setCountryUnitnum = function(code) {
		params.country_unitnum = code;
		goUrl();
	}
	function goUrl() {
		var go_url = '?'+$.param(params);
		location.href = go_url;
	}
}
var manager = new PageManager();

</script>


