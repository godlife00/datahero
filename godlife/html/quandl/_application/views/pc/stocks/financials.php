
	<?php 	
	$active_menu = 'financials';
	$ticker_code = $ticker['tkr_ticker'];
	include_once dirname(__FILE__).'/submenu.php'; 
	?>
                
        <?php

        $tabs = array(
            '손익계산서' => $incomestate_fields,
            '재무상태표' => $balancesheet_fields,
            '현금흐름표' => $cashflow_fields,
            //'투자지표' => $fininvestindi_fields,
            //'주가지표' => $pricesheet_fields,
        );
        $tab_titles = array(
            '손익계산서' => $incomestate_titles,
            '재무상태표' => $balancesheet_titles,
            '현금흐름표' => $cashflow_titles,
            //'투자지표' => $fininvestindi_titles,
            //'주가지표' => $pricesheet_titles,
        );


        // 서비스카테고리 분류값
        $tab_field_servicecategory_maps = array(
            //'투자지표' => $this->historylib->getTableMap('fininvestindi', 'Indicator', 'servicecategory'),
            '재무상태표' => $this->historylib->getTableMap($balancesheet_type, 'Indicator', 'servicecategory'),

        );
        if($balancesheet_type == 'balancesheet2') {
            unset($tab_field_servicecategory_maps['재무상태표']);
        }


	if($tab_idx < 0 || $tab_idx >= sizeof($tabs)) {
		$tab_idx = 0;
	}
        ?>

		<a name="list"></a>


            <div class="tableData">

                
                
                <ul class="competitors txtCalculation">

		<?php 
		foreach(array_keys($tabs) as $idx => $field_title) : 
		?>
                    <li><a href="#" class="<?=$idx == $tab_idx ? 'active fix-active' : ''?>" onclick="manager.setTabIdx(<?=$idx?>)"><?=$field_title?></a></li>
		<?php endforeach;?>
		   
                </ul>
				<!-- //competitors -->
				
				<div class="standard_reports">

					<div class="tableTab positionRe"><!-- tableTab 위치고정 class = positionRe -->
						<div class="tabsArea" id='wrap-dimension'>
					<span class="<?=($dimension=='MRT') ? 'fix-active' : ''?>" onclick="manager.setDimension('MRT', this);">연환산</span>
					<span class="<?=($dimension=='MRY') ? 'fix-active' : ''?>" onclick="manager.setDimension('MRY', this)">연간</span>
					<span class="<?=($dimension=='MRQ') ? 'fix-active' : ''?>" onclick="manager.setDimension('MRQ', this)">분기별</span>
						</div>
						<!-- //tabsArea -->
						<div class="tabsArea" id='wrap-celltype'>
				<span class="<?=($cell_type=='data') ? 'fix-active' : ''?>" onclick="manager.setCellType('data', this)">데이터</span>
				<span class="<?=($cell_type=='ratio') ? 'fix-active' : ''?>" onclick="manager.setCellType('ratio', this)">구성비율</span>
						</div>                    
					</div>
					<!-- //tableTab -->
					
					<div class="standard top"><!-- 최상단에 위치한 경우 레이아웃용 class = top -->                 
						<span class="title">기준</span>                    
						<div class="select open" style="width:110px;">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value"><?=$this->sf1_tb_model->getUnitnumText($ticker['tkr_currency'], $country_unitnum_map[$country_unitnum])?></button>
							<ul class="a_list">
								<?php 
								foreach($country_unitnum_map as $k => $v) : 

								if($ticker['convert_to_usd'] == true && substr($k, 0, 3) != 'us_') {
									// currency가 USD가 아닌 기업은 'kor_억원' 등 USD 외 단위보기 없애기.
									continue;
								}
	?>
								<li><a onclick="manager.setCountryUnitnum('<?=$k?>')"><?=$this->sf1_tb_model->getUnitnumText($ticker['tkr_currency'], $v)?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<!--<button class="simple"><i></i>간단히</button>-->
					</div>
					<!-- //standard -->

				</div>	






                <div class="tableScroll" id='table_scroll_div'>
                <!-- TABLE SCROLL DIV -->

		<?php 


        // tkr_category 가 ADR 인 종목이나 신규상장 등 자료 없는 회사는 전항목 N/A 표출을 위한 플래그 및 배열설정 처리
        $empty_page = false;
        if(sizeof($data) == 0) {
            $empty_page = true;
            $data['&nbsp;'] = array(array());
        }

        // * 이자비용은 손익계산서 기준, 주석에 있는 값은 미표시 => 표출조건
        $debt_intexp_noti = true;
		$cnt=0;
		foreach($tabs as $field_title => $fields) : 
			$field_title_map = $tab_titles[$field_title];
			$cnt++;
			if($cnt==1) $tab_class= 'tableBill';
			else if($cnt==2) $tab_class= 'tableReports';
			else $tab_class= 'tableCash';
		?>
                    <table cellspacing="0" border="0" id="ab_class" class="tableColtype typeScroll <?=$tab_class;?>">
                        <thead>
                            <tr class="fntfmly_num fix_tr"><!-- //th 상단 고정 class = fix_tr -->
                                <th scope="col"><span></span></th>
				    <?php foreach(array_keys($data) as $yyyymm) : ?>
			    	<th scope="col"><span><?=$yyyymm?></span></th>
			    	<?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 
			$last_servicecategory = '';
            $field_servicecategory_map = array();
            if(isset($tab_field_servicecategory_maps[$field_title])) {
                $field_servicecategory_map = $tab_field_servicecategory_maps[$field_title];
            }
			$line=0;
			foreach($fields as $depth => $key) : 
			    $title = $field_title_map[$key].$key;
				$line++;
			    $depth_num = count(explode('-', $depth));
			    $depth = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth_num-1);
			    $tr_depth_class = 'depth'.sprintf('%02d', $depth_num > 3 ? 3 : $depth_num);
			    if(isset($field_servicecategory_map[$key]) && $last_servicecategory != $field_servicecategory_map[$key]) : 
			    	$last_servicecategory = $field_servicecategory_map[$key];
			?>
			<tr class="depth01">
			<?
				if($cnt==2) {
			?>
				    <td><span><strong><?=$last_servicecategory?></strong></span></td>
					<td colspan="<?=sizeof($data)+1?>" class="tdLength"><span><strong></strong></span></td>
			<?
				}
				else {
			?>
				    <td colspan="<?=sizeof($data)+1?>"><span><strong><?=$last_servicecategory?></strong></span></td>
			<?
				}	
			?>
			</tr>
			<?php
			    endif;
			?>

			<?
				if($cnt==2 && ( $title == "자산총계" || $title == "부채총계" || $title == "자본총계" )) {
					$tr_depth_class = 'totalAsset';
				}

				if($cnt==2 && ($title == "유동자산" || $title == "비유동자산" || $title == "유동부채" || $title == "비유동부채")) {
					$tr_depth_class = 'depth02_totalAsset';
				}				
			?>
			<tr class="<?=$tr_depth_class?>">
			    <td><span><strong><?=$title?></strong></span></td>
			    <?php $col=0; foreach($data as $yyyymm => $row) : ?>
				<?php $col++;?>
			    <td><?php
				
				if($this->session->userdata('is_login') === false) {
					if($col>6) {
						echo '<span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span>';
					}
					else {
						if($cnt==1) {
							//if(($line>1&&$line<8)||($line>9&&$line<14)) {
							//	echo '<span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span>';
							//}
							//else {
								if(isset($row[$key]))
									echo $row[$key];
								else 
									echo 'N/A';
							//}
						}
						else if($cnt==2) {
							//if($line<11 || ($line>13&&$line<20)) {
							//	echo '<span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span>';
							//}
							//else {
								if(isset($row[$key]))
									echo $row[$key];
								else 
									echo 'N/A';
							//}
						}
						else {
							//if($line>3) {
							//	echo '<span class="lock"><img src="/img/globalstock/img/icon/lock.png" alt="로그인이 필요합니다"></span>';
							//}
							//else {
								if(isset($row[$key]))
									echo $row[$key];
								else 
									echo 'N/A';
							//}
						}
					}
				}
				else {
				
					if(isset($row[$key]))
						echo $row[$key];
					else 
						echo 'N/A';
				}

				?></td>
			    <?php endforeach; ?>
			</tr>
		    	<?php endforeach; ?>
                            
                        </tbody>
                    </table>
                    <?php if($title == '손익계산서' && $row['sf1_debt'] && ! $row['sf1_intexp']) $debt_intexp_noti = true; ?>
		<?php endforeach; ?>
                    
                <!-- TABLE SCROLL DIV -->
                </div>
                <!-- //tableScroll -->
				<?php include_once INC_PATH.'/login_guide.php'; ?>





                <?php if($empty_page) : ?>
                <p class="tableInfo" style="margin-top: 5px">* ADR(미국예탁증권), 신규상장 등으로 분기 데이터 없음</p>

                <?php endif; ?>

                <?php if($debt_intexp_noti) : ?>
                <p class="tableInfo" style="margin-top: 5px">* 이자비용은 손익계산서 기준, 주석에 있는 값은 미표시</p>
                <?php endif; ?>

                <!-- //standard -->

                <p class="dataLink">data from <a href="https://www.quandl.com/publishers/SHARADAR" target="_blank">Quandl and Sharadar</a></p>
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
    var _this = this;
	var params = {
		tab_idx : <?=intval($tab_idx)?>,
		country_unitnum : '<?=$country_unitnum?>',
		dimension : '<?=$dimension?>',
		cell_type : '<?=$cell_type?>'
	};
	$('div.tableData table').hide().eq(params.tab_idx).show();

	this.setTabIdx = function(idx) {
		if(idx < 0 || idx >= <?=sizeof($tabs)?>) {
			return;
		}
		params.tab_idx = idx;
		$('div.tableData table').hide().eq(params.tab_idx).show();

        $('#wrap-dimension span').show(); // 연환산, 연간, 분기
        $('#wrap-celltype span').show(); // 데이터, 구성비율
        switch(idx) {
            case 0 : // 손익계산서
                break;
            case 1 : // 재무상태표
                // 연환산 숨김
                var obj = $('#wrap-dimension span:eq(0)'); // 연환산, 연간, 분기
                obj.hide();
                if(obj.hasClass('fix-active')) {
                    // 연환산을 보고있던 상태면 연간으로 이동
                    this.setDimension('MRY');
                    return;
                }
                break;
            case 2 : // 현금흐름표
                // 구성비율 숨김
                var obj = $('#wrap-celltype span:eq(1)'); // 데이터, 구성비율
                obj.hide();
                if(obj.hasClass('fix-active')) {
                    // 구성비율을 보고있던 상태면 데이터로 이동
                    this.setCellType('data');
                    return;
                }
                break;
        }
	}
	this.setCellType = function(code, obj) {
        setFixActive(obj);
		params.cell_type = code;
		goUrl();
	}
	this.setDimension = function(code, obj) {
        setFixActive(obj);
		params.dimension = code;
		goUrl();
	}
	this.setCountryUnitnum = function(code) {
		params.country_unitnum = code;
		goUrl();
	}

    function setFixActive(obj) {
        $('span', obj.parentNode).removeClass('fix-active');
        $(obj).addClass('fix-active');
    }

	function goUrl() {
		var go_url = '?ajax=Y&'+$.param(params);
        $.post(go_url, function(res) {
            $('#table_scroll_div').html(res);
            _this.setTabIdx(params.tab_idx);
			function tableLength () {
                if ($('.tableColtype').hasClass("typeScroll")) {
                    var thLength = $('.globalStock #container .tableData .tableScroll .typeScroll th').filter(':visible').length;
                    if (thLength <= 12) {
                        var thWidth = ($('.globalStock #container .tableData .tableScroll').width() + 80 + (thLength * 5)) / thLength;
                        console.log(thWidth);
                        $('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th span').css({
                            'width' : thWidth
                        });
                        $('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th:first span').css({
                            'width' : 161
                        })
                    }
                }        
            }
            tableLength ();
        });
	}
}
var manager = new PageManager();
manager.setTabIdx(<?=$tab_idx?>);

function tableLength () {
	if ($('.tableColtype').hasClass("typeScroll")) {
		var thLength = $('.globalStock #container .tableData .tableScroll .typeScroll th').filter(':visible').length;
		if (thLength <= 12) {
			var thWidth = ($('.globalStock #container .tableData .tableScroll').width() + 80 + (thLength * 5)) / thLength;
			console.log(thWidth);
			$('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th span').css({
				'width' : thWidth
			});
			$('.globalStock #container .tableData .tableScroll .typeScroll th, .globalStock #container .tableData .tableScroll .typeScroll th:first span').css({
				'width' : 161
			})
		}
	}        
}
tableLength ();

</script>


