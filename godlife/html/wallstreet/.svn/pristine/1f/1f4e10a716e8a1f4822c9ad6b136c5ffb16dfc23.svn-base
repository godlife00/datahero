<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/../').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/../').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage Admins - ".ucfirst($mode);

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder

//$page_css[] = "products_detail.css";

include realpath(dirname(__FILE__).'/../').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["manage"]["sub"]["admins"]["active"] = true;
include realpath(dirname(__FILE__).'/../').'/inc/nav.php';

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
    //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
    //$breadcrumbs["New Crumb"] => "http://url.com"
    $breadcrumbs["Tables"] = "";
    include realpath(dirname(__FILE__).'/../').'/inc/ribbon.php';

if(isset($values[$pk]) == false) {
	$values[$pk] = '';
}

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
                       Manage
                    <span>>  
                       Admins
                    </span>
                </h1>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->


        <div class="row">
            <div class="col-md-12">

                <form name='edit_form' id="edit_form" action="/adminpanel/manage/admins_process" method="POST">
					<input type='hidden' name='mode' value='<?=$mode?>' />
					<input type='hidden' name='<?=$pk?>' value='<?=$values[$pk]?>' />

                <div class="well well-md">

                    <div class="jarviswidget product_tit" id="wid-id-5">
                   
                    <header>
                        <h2>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            Admins Detail
                        </h2>
                    </header>

                    <!-- widget div-->
                    <div class="form-horizontal">

                        <fieldset>
							


									
							<?php if($values[$pk] > 0 ) : ?>
                            				<div class="form-group">
								<label for="a_id" class="col-md-3 control-label">Id <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$values['a_id']?>
								</div>
							</div>
							<?php endif; ?>
									



									


									






									



									





							


									
                            				<div class="form-group">
								<label for="a_loginid" class="col-md-3 control-label">Loginid <span class="required-atr">*</span></label>
								<div class="col-md-8">
							<?php if($values[$pk] > 0 ) : ?>
									<?=$values['a_loginid']?>
							<?php else : ?>
									<input class="form-control input-xs" type="text" name="a_loginid" id="a_loginid" value="<?=strlen($values['a_loginid']) ? $values['a_loginid']:''?>" required>
							<?php endif; ?>
								</div>
							</div>
									



									


									






									



									





							


									
                            <div class="form-group">
								<label for="a_passwd" class="col-md-3 control-label">Passwd <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<input class="form-control input-xs" type="password" name="a_passwd" id="a_passwd" value="" />
								</div>
							</div>
									



									


									






									



									





							


									



									
                            <div class="form-group">
								<label for="a_name" class="col-md-3 control-label">Name <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<input class="form-control input-xs" type="text" name="a_name" id="a_name" value="<?=strlen($values['a_name']) ? $values['a_name']:''?>" required>
								</div>
							</div>
									


									






									



									





							


									



									
                            <div class="form-group">
								<label for="a_level" class="col-md-3 control-label">Level <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<input class="form-control input-xs" type="text" name="a_level" id="a_level" value="<?=strlen($values['a_level']) ? $values['a_level']:''?>" required>
								</div>
							</div>
									


									






									



									





							


									
							<?php if($values[$pk] > 0 ) : ?>
                            <div class="form-group">
								<label for="a_lastlogin_at" class="col-md-3 control-label">Lastlogin At <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$values['a_lastlogin_at']?>
								</div>
							</div>
							<?php endif; ?>
									



									


									






									



									





							


									
							<?php if($values[$pk] > 0 ) : ?>
                            <div class="form-group">
								<label for="a_created_at" class="col-md-3 control-label">Created At <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$values['a_created_at']?>
								</div>
							</div>
							<?php endif; ?>
									



									


									






									



									





							


									
							<?php if($values[$pk] > 0 ) : ?>
                            <div class="form-group">
								<label for="a_updated_at" class="col-md-3 control-label">Updated At <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$values['a_updated_at']?>
								</div>
							</div>
							<?php endif; ?>
									



									


									






									



									





							
                        </fieldset>

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end .jarviswidget-->



                <div class="text-right">
                    <a href="/adminpanel/manage/admins?keep=yes" class="btn btn-xs btn-primary">
                        <i class="fa fa-list-alt"></i> 
                        List
                    </a>
                    <a href="javascript:submit_form();" class="btn btn-xs btn-danger">
                        <i class="fa fa-save"></i> 
                        Save
                    </a>
                </div>
<script type="text/javascript">

function submit_form() {
    var formid = $("#edit_form");
    formid.validate({
        ignore: [],
        invalidHandler: function(e,validator) {
        }
    });
    formid.submit();
}

function addCalendar(obj_id, relation_obj_id, relation_from_or_to) {
	
	$('#'+obj_id).datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
		changeYear: true,
        onSelect: function (selectedDate) {
			if(relation_obj_id != null) {
				var rel = relation_from_or_to;
				if(rel == 'from') {
					rel = 'max';
				} else {
					rel = 'min';
				}
            	$('#'+relation_obj_id).datepicker('option', rel+'Date', selectedDate);
			}
        }
    });
}
function addClock(id) {
	$('#'+id).clockpicker({
		placement: 'top',
	    donetext: 'Done'
	});
}

</script>



                
                </div><!-- .well End -->
                </form>
            </div>
        </div>

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->


<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/clockpicker/clockpicker.min.js"></script>
