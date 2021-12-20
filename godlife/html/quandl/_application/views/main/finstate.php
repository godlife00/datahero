<!DOCTYPE html>
<html lang="en-us" >
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

        <title> Normal Tables - SmartAdmin </title>
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/smartadmin-skins.min.css">

        <!-- SmartAdmin RTL Support is under construction-->
        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/smartadmin-rtl.min.css">

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/your_style.css"> -->

        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/your_style.css">

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="http://smartdevui.hamt.kr/css/demo.min.css">

        <!-- FAVICONS -->
        <?php /*
        <link rel="shortcut icon" href="http://smartdevui.hamt.kr/img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="http://smartdevui.hamt.kr/img/favicon/favicon.ico" type="image/x-icon">
        */ ?>

        <!-- GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- Specifying a Webpage Icon for Web Clip
             Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->

             <?php 
             /*
        <link rel="apple-touch-icon" href="http://smartdevui.hamt.kr/img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="http://smartdevui.hamt.kr/img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="http://smartdevui.hamt.kr/img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="http://smartdevui.hamt.kr/img/splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="http://smartdevui.hamt.kr/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="http://smartdevui.hamt.kr/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="http://smartdevui.hamt.kr/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

        */ 
        ?>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="/admin_assets/js/libs/jquery-2.0.2.min.js"><\/script>');
            }
        </script>

        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="/admin_assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>


        <script>
        $( function() {
		/*
		 * Tab
		 */
                $( "#tabs" ).tabs();

		/*
		 * AUTO COMPLETE AJAX
		 */
		var ticker_list = <?=json_encode($search_tickers)?>;
		$('#search_ticker').autocomplete({
			source : <?=json_encode($search_list)?>,
			select : ticker_search,
			autoFocus : true
		}).focus(function() {
			$(this).autocomplete("search", "");
		});

		$("#search_tickers").keydown(function(event){
			if(event.keyCode == 13) {
				if($("#search_tickers").val().length==0) {
					event.preventDefault();
					return false;
				}
			}
		});


		// 검색 수행 함수
		function ticker_search(event, ui) {
			if(ui.item == null) return;
			var val = ui.item.value;

			if(val.indexOf('|') > 0) {
				val = $.trim(val.split('|')[0]).toUpperCase();
			}

			if($.inArray(val, ticker_list) < 0) {
				$('#search_ticker').val('');
				return;
			}

			location.href='/main/finstate/'+val;
		}

		

        } );
        </script>
        
    </head>
    <body style='padding:20px'>
        <h2>
		<?=$ticker['tkr_name']?> ( <?=$ticker['tkr_ticker']?> | <?=$ticker['tkr_permaticker']?> ) &nbsp;&nbsp;&nbsp;
		<span style='color:black;font-weight:bold;'>$<?=number_format($last_sep['sep_close'], 2)?></span>
		<?php
		if($last_sep['sep_diff_rate'] == 0) { 
			echo '0%';
		} else if($last_sep['sep_diff_rate'] > 0) {
			echo '<span style="color:red;font-size:0.8em;font-weight:bold;">$ '.number_format($last_sep['sep_diff_price'], 2).' ( '.$last_sep['sep_diff_rate'].' % )</span>';
		} else {
			echo '<span style="color:blue;font-size:0.8em;font-weight:bold;">$ '.number_format($last_sep['sep_diff_price'], 2).' ( '.$last_sep['sep_diff_rate'].' % )</span>';
		}
		?>
		<span style='padding-left:20px;font-weight:bold;color:#369;'><a href='/stocks/financials/<?=$ticker['tkr_ticker']?>' target='_blank'>미국증시 사이트에서 보기</a></span>

		<span>
			<input id='search_ticker' class="form-control" placeholder="종목 검색.." type="text"/>
			<div id="log" class="font-xs margin-top-10 text-danger"></div>
		</span>

	</h2>

	<?php 
	if(sizeof($company_info) > 0) : 
		$company_fields = array(
		'cp_id',
		'cp_exchange',
		'cp_ticker',
		'cp_usname',
		'cp_korname',

		'tkr_siccode',
		'tkr_sicsector',
		'tkr_sicindustry',
		'tkr_sector',
		'tkr_industry',
		'tkr_scalemarketcap',
		'tkr_scalerevenue',

		'tkr_secfilings',
		'tkr_companysite',


		'cp_is_confirmed',
		'cp_is_dow30',
		'cp_is_nasdaq100',
		'cp_is_snp500',
		'cp_short_description',
		'cp_description',
		'cp_created_at',
		'cp_updated_at',
		);
		$company_info = array_merge($company_info, $ticker);
	?>
        <table class='table table-bordered'>
	<?php $idx=0; foreach($company_fields as $k) : $idx++;  ?>
	<?php
		$val = $company_info[$k];
		switch($k) {
			case 'tkr_secfilings':
			case 'tkr_companysite':
				$val = '<a href="'.$val.'" target="_blank">'.$val.'</a>';
				break;
			default :
				$val = nl2br($val);
		}
	?>

		<tr>
		<th width=><?=$k?></th>
		<td><?=$val?></td>
		</tr>
	<?php endforeach;  ?>
	</table>
	<?php endif; ?>

        <div  style='padding:20px' id='tabs'>



        <?php

        $tabs = array(
            '재무상태표' => $balancesheet_fields,
            '손익계산서' => $incomestate_fields,
            '현금흐름표' => $cashflow_fields,
            '재무&투자지표' => $fininvestindi_fields,
            '주가지표' => $pricesheet_fields,
        );

        $tab_titles = array(
            '재무상태표' => $balancesheet_titles,
            '손익계산서' => $incomestate_titles,
            '현금흐름표' => $cashflow_titles,
            '재무&투자지표' => $fininvestindi_titles,
            '주가지표' => $pricesheet_titles,
        );

        ?>

        <div style='margin-bottom:20px;'>
            <div class="btn-group btn-group-justified">
                <a href="javascript:change_dimension('MRY', '<?=$cell_type?>');" class="btn btn-default <?=($dimension=='MRY')? 'active' : ''?>">연간</a>
                <a href="javascript:change_dimension('MRT', '<?=$cell_type?>');" class="btn btn-default <?=($dimension=='MRT')? 'active' : ''?>">연환산</a>
                <a href="javascript:change_dimension('MRQ', '<?=$cell_type?>');" class="btn btn-default <?=($dimension=='MRQ')? 'active' : ''?>">분기</a>
            </div>
        </div>
 
        <div style='margin-bottom:20px;'>
            <div class="btn-group btn-group-justified">
                <a href="javascript:change_dimension('<?=$dimension?>','data');" class="btn btn-default <?=($cell_type=='data')? 'active' : ''?>">데이터</a>
                <a href="javascript:change_dimension('<?=$dimension?>','ratio');" class="btn btn-default <?=($cell_type=='ratio')? 'active' : ''?>">구성비율</a>
            </div>
        </div>
    
   
        <ul>
        <?php foreach (array_keys($tabs) as $k => $t) : ?>
            <li><a href="#tabs-<?=($k+1)?>"><?=$t?></a></li>
        <?php endforeach; ?>
            
        </ul>


        <?php 
	$k=0; 
	foreach ($tabs as $field_title => $fields) : 
		$k++; 
		$field_title_map = $tab_titles[$field_title];
	?>

        <div class='nav nav-tabs' id='tabs-<?=$k?>'>
        <table class='table table-bordered'>
	    <?php if($field_title == '주가지표') : ?>
	    <thead>
	    	<tr>
	    	<?php foreach($fields as $field) : ?>
		<th><?=$field_title_map[$field]?></th>
		<?php endforeach; ?>
	    	</tr>
	    </thead>
	    <tbody>
	    	<?php foreach($sepdata as $sep_row) : ?>
		<tr>
	    		<?php foreach($fields as $field) : ?>
		<td><?php
			switch($pricesheet_format[$field]) {
				case 'USD/share' :
					echo '$ '.number_format($sep_row[$field], 2);
					break;
				case '%' :
					if($sep_row['sep_diff_rate'] == 0) { 
						echo sprintf('%.2f', $sep_row[$field]).' %';
					} else if($sep_row['sep_diff_rate'] > 0) {
						echo '<span style="color:#d33;font-weight:bold;">';
						echo sprintf('%.2f', $sep_row[$field]).' %';
						echo '</span>';
					} else {
						echo '<span style="color:#33d;font-weight:bold;">';
						echo sprintf('%.2f', $sep_row[$field]).' %';
						echo '</span>';
					}
					break;
				case 'numeric' :
					echo number_format($sep_row[$field]);
					break;
				default :
					echo $sep_row[$field];
			}
		?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	    </tbody>
	    <?php else : ?>


            <thead>
                <tr>
                    <th><?=$field_title?></th>
                    <?php foreach(array_keys($data) as $yyyymm) : ?>
                    <th><?=$yyyymm?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($fields as $depth => $key) : 
                    $title = $field_title_map[$key];
                    $depth = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', count(explode('-', $depth))-1);
                ?>
                <tr>
                    <td><?=$depth.$title?></td>
                    <?php foreach($data as $yyyymm => $row) : ?>
                    <td style='text-align:right;'><?php

		
		    $cellval = $row[$key];
		    switch($field_title) {
		    	case '재무&투자지표' :
				if(isset($fininvestindi_format[$key])) {
					switch($fininvestindi_format[$key]) {
						case '%' :
							$cellval = number_format($cellval * 100, 2);
							$cellval .= ' %';
							break;
						case 'US달러' :
						case 'USD' :
							$cellval = '$ '.number_format($cellval, 2);
							break;
						case '배' :
							$cellval = number_format($cellval, 2);
							break;
						default : 
							if(is_numeric($cellval)) {
								$cellval = number_format($cellval);
							}

					}
				}
				break;
			default :
				if(is_numeric($cellval)) {
		    			$cellval = number_format($row[$key]);
				}
		    }

		    echo $cellval;
		    
		    ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
	    <?php endif; ?>
        </table>
        </div>

        <?php endforeach; ?>


        </div>

<script>
function change_dimension(dim, cell_type) {
	location.href='?dimension='+dim+'&cell_type='+cell_type;
}
</script>



    </body>
</html>
