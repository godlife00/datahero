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
                document.write('<script src="http://smartdevui.hamt.kr/js/libs/jquery-2.0.2.min.js"><\/script>');
            }
        </script>

        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="http://smartdevui.hamt.kr/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>


        <script>
        $( function() {
                $( "#tabs" ).tabs();
        } );
        </script>
        
    </head>
    <body style='padding:20px'>
        <h2><?=$ticker['tkr_name']?> ( <?=$ticker['tkr_ticker']?> | <?=$ticker['tkr_permaticker']?> )</h2>

        <div  style='padding:20px' id='tabs'>



        <?php

        $tabs = array(
            '재무상태표' => $balancesheet_fields,
            '손익계산서' => $incomestate_fields,
            '현금흐름표' => $cashflow_fields,
        );
        ?>

        <div style='margin-bottom:20px;'>
            <div class="btn-group btn-group-justified">
                <a href="javascript:change_dimension('MRY');" class="btn btn-default <?=($dimension=='MRY')? 'active' : ''?>">연간</a>
                <a href="javascript:change_dimension('MRT');" class="btn btn-default <?=($dimension=='MRT')? 'active' : ''?>">연환산</a>
                <a href="javascript:change_dimension('MRQ');" class="btn btn-default <?=($dimension=='MRQ')? 'active' : ''?>">분기</a>
            </div>
        </div>
    
        <ul>
        <?php foreach (array_keys($tabs) as $k => $t) : ?>
            <li><a href="#tabs-<?=($k+1)?>"><?=$t?></a></li>
        <?php endforeach; ?>
            
        </ul>


        <?php $k=0; foreach ($tabs as $field_title => $fields) : $k++; ?>

        <div class='nav nav-tabs' id='tabs-<?=$k?>'>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th><?=$field_title?></th>
                    <?php foreach(array_keys($data) as $yyyymm) : ?>
                    <th><?=date('Y/m/d', strtotime($yyyymm))?></th>
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
                    <td style='text-align:right;'><?=(is_numeric($row[$key]) && strpos($key, 'permaticker') === false) ? number_format($row[$key]) : $row[$key]?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <?php endforeach; ?>


        </div>

<script>
function change_dimension(dim) {
	location.href='?dimension='+dim;
}
</script>



    </body>
</html>
