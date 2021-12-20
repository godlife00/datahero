<?php
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");
/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */
$page_title = "Dashboard";
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");


//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["dashboard"]["active"] = true;
include("inc/nav.php");

?>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
    //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
    //$breadcrumbs["New Crumb"] => "http://url.com"
    //$breadcrumbs[''] = '';
    include("inc/ribbon.php");
?>



	<!-- MAIN CONTENT -->
	<div id="content">

        <!-- row -->
		<div class="row">
			Hello, Hamt Default Project !!
        </div>

	     
	</div>
	<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->
