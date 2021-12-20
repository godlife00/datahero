<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/..').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/..').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "대가종목편집";
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "jqgrid_custom.css";
$page_css[] = "ui.daterangepicker.css";
include realpath(dirname(__FILE__).'/..').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["master"]["active"] = true;
include realpath(dirname(__FILE__).'/..').'/inc/nav.php';
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
.fc-sun { color:red; }
.fc-sat { color:blue; }

div > div.ui-jqgrid-bdiv { height:auto !important; }
.ui-jqgrid .ui-jqgrid-toppager {height: 45px!important; padding:10px!important;}
.ui-jqgrid .ui-jqgrid-bdiv {
  overflow-x:auto; 
}
</style>

<div id="main" role="main">

    <?php
        //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
        //$breadcrumbs["New Crumb"] => "http://url.com"
        $breadcrumbs["월가히어로"] = "";
        include realpath(dirname(__FILE__).'/../').'/inc/ribbon.php';
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">

        <!-- row -->
        <div class="row">
            
            <!-- col -->
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark">
                    
                    <!-- PAGE HEADER -->
                    <i class="fa-fw fa fa-home"></i> 
                        월가히어로
                    <span>>  
                        <?=$page_title?>
                    </span>
                </h1>
            </div>
            <!-- end col -->
            
        </div>
        <!-- end row -->

        <!--
            The ID "widget-grid" will start to initialize all widgets below 
            You do not need to use widgets if you dont want to. Simply remove 
            the <section></section> and you can use wells or panels instead 
            -->

        <!-- widget grid -->
        <section class="">

            <!-- row -->
            <div class="row">
                
                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <div style='padding:20px' id='tabs'>
                        <form action='/adminpanel/main/set_master_items_process' method='POST'>
                            <ul>
                            <?php foreach($master as $k => $v) : ?>
                            <li><a href="#tabs-<?=($k+1)?>"><?=$v['ms_guru']?> (<?=$v['ms_id']?>)</a></li>
                            <?php endforeach; ?>
                            </ul>

                            <?php 
                            foreach($master as $k => $v) : 
                                $file_path = MASTER_DATA.'/master_'.$v['ms_id'].'.info';
                            ?>
                            <div class='nav nav-tabs' id='tabs-<?=($k+1)?>'>
                                <textarea name="master_<?=$v['ms_id']?>" style='width:100%;height:300px;'><?=is_file($file_path) ? file_get_contents($file_path) : ''?></textarea>
                            </div>
                            <?php endforeach; ?>
                            <br>
                            서비스에 영향을 미칩니다. 개발서버에서 먼저 확인 후 수정 저장 하세요.
                            <br>
                            <center> 
                                <a href="/adminpanel/main/master?keep=yes" class="btn btn-xl btn-primary"> 목록 </a>
                                <button type="submit" class="btn btn-xl btn-danger">저장</button>
                            </center>
                        </form>
                    </div>
                </article>
                <!-- WIDGET END -->
                
            </div>

            <!-- end row -->

        </section>
        <!-- end widget grid -->

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->



<!-- ==========================CONTENT ENDS HERE ========================== -->


<script>
$(function() {
    $("#tabs").tabs();
});
</script>


