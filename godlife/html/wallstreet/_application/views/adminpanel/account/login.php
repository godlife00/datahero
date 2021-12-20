<?php

//initilize the page
require_once(realpath(dirname(__FILE__).'/../')."/inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once(realpath(dirname(__FILE__).'/../')."/inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Log in ".SERVICE_NAME." Admin";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$page_css[] = "lockscreen.min.css";
$no_main_header = true;
include(realpath(dirname(__FILE__).'/..')."/inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- MAIN CONTENT -->

    <form class="lockscreen animated flipInX" name="adminLoginForm" action="<?=HTTPS_ADMIN_URL?>/adminpanel/account/login_action" method="POST">
		<input type='hidden' name='url' value="<?=isset($url) ? $url : ''?>" />
        <div class="logo">
            <h1 class="semi-bold" style="color:<?=LOGIN_COLOR?>;"><?php /*img src="<?php echo ASSETS_URL; ?>/img/logo-o.png" alt="" /> */ ?><?=SERVICE_NAME?> Admin</h1>
        </div>
        <div class='row'>
            <div class='col-md-12'>
                <h1><i class="text-muted air air-top-right hidden-mobile"></i><?=LOGIN_TITLE?></h1>
                
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="ID" name="admin_id" style="margin-bottom:1px;">
                    <input class="form-control" type="password" placeholder="Password" name="admin_pw">
                    <div class="input-group-btn">
                        <button class="btn" type="submit" style="background:<?=LOGIN_COLOR?>;color:<?=LOGIN_BTNTXT_COLOR?>;padding:20px; margin-left:10px;">
				인증
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <p class="font-xs margin-top-5">
            <?=ALL_RIGHT_RESERVED?>
        </p>
    </form>
</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->
<script type="text/javascript">
$(document).ready(function(){
    $("input[name='admin_id']").focus();
});
</script>
