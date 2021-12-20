<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/../').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/../').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Data Company - ".ucfirst($mode);

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder

//$page_css[] = "products_detail.css";

include realpath(dirname(__FILE__).'/../').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["data"]["sub"]["company"]["active"] = true;
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
                       Data
                    <span>>  
                       Company
                    </span>
                </h1>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->


        <div class="row">
            <div class="col-md-12">

                <form name='edit_form' id="edit_form" action="/adminpanel/data/company_process" method="POST">
					<input type='hidden' id='mode' name='mode' value='<?=$mode?>' />
					<input type='hidden' name='<?=$pk?>' value='<?=$values[$pk]?>' />
					<input type='hidden' id='cp_product' name='cp_product' value='<?=$values['cp_product'];?>' />
					<input type='hidden' id='cp_brand' name='cp_brand' value='<?=$values['cp_brand'];?>' />

                <div class="well well-md">

                    <div class="jarviswidget product_tit" id="wid-id-5">
                   
                    <header>
                        <h2>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            Company Detail
                        </h2>
                    </header>

                    <!-- widget div-->
                    <div class="form-horizontal">

                        <fieldset>
							


									
							<?php if($values[$pk] > 0 ) : ?>
                            <div class="form-group">
								<label for="cp_id" class="col-md-3 control-label">Id <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$values['cp_id']?>
								</div>
							</div>
							<?php endif; ?>
									
									
                            <div class="form-group">
								<label for="cp_exchange" class="col-md-3 control-label">Exchange <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$select_exchange?>
								</div>
							</div>
									


									
                            <div class="form-group">
								<label for="cp_ticker" class="col-md-3 control-label">Ticker <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<input class="form-control input-xs" type="text" name="cp_ticker" id="cp_ticker" value="<?=strlen($values['cp_ticker']) ? $values['cp_ticker']:''?>" required>
								</div>
							</div>
									

									
                            <div class="form-group">
								<label for="cp_usname" class="col-md-3 control-label">Usname <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<input class="form-control input-xs" type="text" name="cp_usname" id="cp_usname" value="<?=strlen($values['cp_usname']) ? $values['cp_usname']:''?>" required>
								</div>
							</div>
									
									
                            <div class="form-group">
								<label for="cp_korname" class="col-md-3 control-label">Korname <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<input class="form-control input-xs" type="text" name="cp_korname" id="cp_korname" value="<?=strlen($values['cp_korname']) ? $values['cp_korname']:''?>" required>
								</div>
							</div>
							
							<!-- 신규항목 추가(19.10/29) : Product, Brand, Competition, Significant Customer -->

							<!--<input type="text" style="width:100%;padding:0px;" name="cp_brand" id="gs_cp_brand" value="">-->
							<?php
								if( isset($values['cp_product']) && $values['cp_product'] != '') {
									$arr_cp_product = explode(',', $values['cp_product']);
								}
								else {
									$arr_cp_product[0] = '';
									$arr_cp_product[1] = '';
									$arr_cp_product[2] = '';
									$arr_cp_product[3] = '';
									$arr_cp_product[4] = '';
								}

								if( isset($values['cp_brand']) && $values['cp_brand'] != '') {
									$arr_cp_brand = explode(',', $values['cp_brand']);
								}
								else {
									$arr_cp_brand[0] = '';
									$arr_cp_brand[1] = '';
									$arr_cp_brand[2] = '';
									$arr_cp_brand[3] = '';
									$arr_cp_brand[4] = '';
								}
							?>
                            <div class="form-group">
								<label for="cp_product" class="col-md-3 control-label">Product <span class="required-atr"></span></label>
								<div class="col-md-8">
									<input style="width:19%;padding:0px;" type="text" name="cp_product_1" id="cp_product_1" value="<?=strlen($arr_cp_product[0]) ? $arr_cp_product[0]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_product_2" id="cp_product_2" value="<?=strlen($arr_cp_product[1]) ? $arr_cp_product[1]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_product_3" id="cp_product_3" value="<?=strlen($arr_cp_product[2]) ? $arr_cp_product[2]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_product_4" id="cp_product_4" value="<?=strlen($arr_cp_product[3]) ? $arr_cp_product[3]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_product_5" id="cp_product_5" value="<?=strlen($arr_cp_product[4]) ? $arr_cp_product[4]:''?>">
								</div>
							</div>

                            <div class="form-group">
								<label for="cp_brand" class="col-md-3 control-label">Brand <span class="required-atr"></span></label>
								<div class="col-md-8">
									<input style="width:19%;padding:0px;" type="text" name="cp_brand_1" id="cp_brand_1" value="<?=strlen($arr_cp_brand[0]) ? $arr_cp_brand[0]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_brand_2" id="cp_brand_2" value="<?=strlen($arr_cp_brand[1]) ? $arr_cp_brand[1]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_brand_3" id="cp_brand_3" value="<?=strlen($arr_cp_brand[2]) ? $arr_cp_brand[2]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_brand_4" id="cp_brand_4" value="<?=strlen($arr_cp_brand[3]) ? $arr_cp_brand[3]:''?>">
									<input style="width:19%;padding:0px;" type="text" name="cp_brand_5" id="cp_brand_5" value="<?=strlen($arr_cp_brand[4]) ? $arr_cp_brand[4]:''?>">
								</div>
							</div>

                            <div class="form-group">
								<label for="cp_competition" class="col-md-3 control-label">Competition <span class="required-atr"></span></label>
								<div class="col-md-8">
									<!--<input class="form-control input-xs" type="text" name="cp_competition" id="cp_competition" value="<?=strlen($values['cp_competition']) ? $values['cp_competition']:''?>">-->
									<textarea class="form-controls" style='width:100%;height:60px;' name="cp_competition" id="cp_competition"><?=strlen($values['cp_competition']) ? $values['cp_competition']:''?></textarea>

								</div>
							</div>

                            <div class="form-group">
								<label for="cp_scustomer" class="col-md-3 control-label">Significant Customer <span class="required-atr"></span></label>
								<div class="col-md-8">
									<!--<input class="form-control input-xs" type="text" name="cp_scustomer" id="cp_scustomer" value="<?=strlen($values['cp_scustomer']) ? $values['cp_scustomer']:''?>">-->
									<textarea class="form-controls" style='width:100%;height:60px;' name="cp_scustomer" id="cp_scustomer"><?=strlen($values['cp_scustomer']) ? $values['cp_scustomer']:''?></textarea>
								</div>
							</div>									

                            <div class="form-group">
								<label for="cp_riskfactor" class="col-md-3 control-label">Risk Factor <span class="required-atr"></span></label>
								<div class="col-md-8">
									<!--<input class="form-control input-xs" type="text" name="cp_scustomer" id="cp_scustomer" value="<?=strlen($values['cp_scustomer']) ? $values['cp_scustomer']:''?>">-->
									<textarea class="form-controls" style='width:100%;height:60px;' name="cp_riskfactor" id="cp_riskfactor"><?=strlen($values['cp_riskfactor']) ? $values['cp_riskfactor']:''?></textarea>
								</div>
							</div>									

							<!-- 4개 항목 제거 추가(19.10/29) : Is Confirmed, Is Dow30, Is Nasdaq100, Is Snp500 -->
                            <!--<div class="form-group">
								<label for="cp_is_confirmed" class="col-md-3 control-label">Is Confirmed <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$select_is_confirmed?>
								</div>
							</div>
																	
                            <div class="form-group">
								<label for="cp_is_dow30" class="col-md-3 control-label">Is Dow30 <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$select_is_dow30?>
								</div>
							</div>
																	
                            <div class="form-group">
								<label for="cp_is_nasdaq100" class="col-md-3 control-label">Is Nasdaq100 <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$select_is_nasdaq100?>
								</div>
							</div>
																	
                            <div class="form-group">
								<label for="cp_is_snp500" class="col-md-3 control-label">Is Snp500 <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$select_is_snp500?>
								</div>
							</div>-->
									
                            <div class="form-group">
								<label for="cp_short_description" class="col-md-3 control-label">Short Description <span class="required-atr"></span></label>
								<div class="col-md-8">
									<textarea class="form-controls" style='width:100%;height:60px;' name="cp_short_description" id="cp_short_description"><?=strlen($values['cp_short_description']) ? $values['cp_short_description']:''?></textarea>
								</div>
							</div>
									
                            <div class="form-group">
								<label for="cp_description" class="col-md-3 control-label">Description <span class="required-atr"></span></label>
								<div class="col-md-8">
									<textarea class="form-controls" style='width:100%;height:200px;' name="cp_description" id="cp_description"><?=strlen($values['cp_description']) ? $values['cp_description']:''?></textarea>
								</div>
							</div>
									
							<?php if($values[$pk] > 0 ) : ?>
                            <div class="form-group">
								<label for="cp_created_at" class="col-md-3 control-label">Created At <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$values['cp_created_at']?>
								</div>
							</div>
							<?php endif; ?>	
									
							<?php if($values[$pk] > 0 ) : ?>
                            <div class="form-group">
								<label for="cp_updated_at" class="col-md-3 control-label">Updated At <span class="required-atr">*</span></label>
								<div class="col-md-8">
									<?=$values['cp_updated_at']?>
								</div>
							</div>
							<?php endif; ?>

                        </fieldset>

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end .jarviswidget-->



                <div class="text-right">
                    <a href="/adminpanel/data/company?keep=yes" class="btn btn-xs btn-primary">
                        <i class="fa fa-list-alt"></i> 
                        List
                    </a>

                    <a href="javascript:doDelete();" class="btn btn-xs btn-warning">
                        <i class="fa fa-list-alt"></i> 
                        Delete
                    </a>

                    <a href="javascript:submit_form();" class="btn btn-xs btn-danger">
                        <i class="fa fa-save"></i> 
                        Save
                    </a>
                </div>
<script type="text/javascript">


<?php if($mode == 'update') : ?>
function doDelete() {
    var formid = $("#edit_form");
    formid.validate({
        ignore: [],
        invalidHandler: function(e,validator) {
        }
    });

    $('#mode').val('delete');
    formid.submit();
}
<?php endif; ?>


function submit_form() {
	var formid = $("#edit_form");

	formid.validate({
        ignore: [],
        invalidHandler: function(e,validator) {
        }
    });
	var product='';
	var brand='';
	for( i=1; i<6; i++) {
		if($('#cp_product_'+i).val()) {
			product += $('#cp_product_'+i).val() + ',';
		}

		if($('#cp_brand_'+i).val()) {
			brand += $('#cp_brand_'+i).val() + ',';
		}
	}
	if(product != '' ) {
		product = product.slice(0,-1);
	}

	if(brand != '' ) {
		brand = brand.slice(0,-1);
	}

	$("#cp_product").val(product);
	$("#cp_brand").val(brand);

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
