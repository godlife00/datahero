<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/../').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/../').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "미주미동영상 - ".ucfirst($mode_kor);

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder

$page_css[] = "products_detail.css";

include realpath(dirname(__FILE__).'/../').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["vod_mjm"]["active"] = true;
include realpath(dirname(__FILE__).'/../').'/inc/nav.php';

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
    //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
    //$breadcrumbs["New Crumb"] => "http://url.com"
    $breadcrumbs["월가히어로"] = "";
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
                        미주미초이스
                    <span>>  
                        <?=$page_title?>
                    </span>
                </h1>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->


        <div class="row">
            <div class="col-md-12">

                <form name='edit_form' id="edit_form" action="/adminpanel/main/vod_mjm_process" method="POST" enctype="multipart/form-data">
					<input type='hidden' name='mode' value='<?=$mode?>' />
					<input type='hidden' name='<?=$pk?>' value='<?=$values[$pk]?>' />

                <div class="well well-md">
                    <div class="jarviswidget product_tit" id="wid-id-5">
                   
                    <header>
                        <h2>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            미주미동영상 상세
                        </h2>
                    </header>

                    <!-- widget div-->
                    <div class="form-horizontal">
                        <fieldset>
									
							<?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
								<label class="col-md-2 control-label">No</label>
								<div class="col-md-10">
									<?=$values['vm_id']?>
								</div>
							</div>

                            <div class="form-group">
								<label class="col-md-2 control-label">작성자</label>
								<div class="col-md-10">
									<?=$admin_data['a_loginid']?>
								</div>
							</div>

                            <div class="form-group">
								<label class="col-md-2 control-label">조회수</label>
								<div class="col-md-10">
									<?=number_format($values['vm_view_count'])?>
								</div>
							</div>
							<?php endif; ?>

                            <div class="form-group">
								<label for="display_date" class="col-md-2 control-label">표출일 <span class="required-atr">*</span></label>
								<div class="col-md-10">
                                    <input type="text" name="display_date" id="display_date" value="<?=$display_date?>" placeholder="0000-00-00" autocomplete="off" required />
                                    <input type="text" name="display_time" id="display_time" value="<?=$display_time?>" placeholder="00:00" data-autoclose="true" autocomplete="off" required />
								</div>
							</div>

                            <div class="form-group">
								<label for="vm_title" class="col-md-2 control-label">제목 <span class="required-atr">*</span></label>
								<div class="col-md-10">
									<textarea name="vm_title" id="vm_title" rows="3" cols="80" required><?=$values['vm_title']?></textarea>
								</div>
							</div>
									
                            <div class="form-group">
								<label for="vm_is_active" class="col-md-2 control-label">게시물 표출 <span class="required-atr">*</span></label>
								<div class="col-md-10">
                                    <select name="vm_is_active">
                                        <?php foreach($active_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['vm_is_active'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
								</div>
							</div>

                            <div class="form-group">
                                <label for="vm_view_srv" class="col-md-2 control-label">표출 서비스 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="vm_view_srv">
                                        <?php foreach($view_srv_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['vm_view_srv'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="vm_pay" class="col-md-2 control-label">유료/무료 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="vm_pay">
                                        <?php foreach($pay_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['vm_pay'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr />

                            <div class="form-group">
                                <label for="vm_main_thumbnail" class="col-md-2 control-label">메인썸네일 (이미지) <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <?php if(strlen($values['vm_main_thumbnail']) > 0 && file_exists(ATTACH_DATA.'/vod_mjm/'.$values['vm_main_thumbnail'])) : ?>
                                    <img src="/webdata/attach_data/vod_mjm/<?=$values['vm_main_thumbnail']?>" alt="미주미동영상 이미지" style="border:solid 1px #ccc;"/>
                                    <?php endif; ?>
                                    <input type="file" class="btn btn-default" name="vm_main_thumbnail" id="vm_main_thumbnail" />
                                </div>
                            </div>

                            <div class="form-group">
								<label for="vm_content" class="col-md-2 control-label">내용 <span class="required-atr">*</span></label>
								<div class="col-md-10">
                                    <textarea name="vm_content" id="vm_content" required><?=$values['vm_content']?></textarea>
								</div>
							</div>

							<?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
								<label class="col-md-2 control-label">등록일</label>
								<div class="col-md-10"> <?=$values['vm_created_at']?> </div>
							</div>

                            <div class="form-group">
								<label class="col-md-2 control-label">수정일</label>
								<div class="col-md-10"> <?=$values['vm_updated_at']?> </div>
							</div>
							<?php endif; ?>

                        </fieldset>

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end .jarviswidget-->

                <div class="text-right">
                    <a href="/adminpanel/main/vod_mjm?keep=yes" class="btn btn-xs btn-primary">
                        <i class="fa fa-list-alt"></i> 
                        목록
                    </a>
                    <?php if($values[$pk] > 0) : ?>
                    <a href="javascript:delete_form();" class="btn btn-xs btn-warning">
                        <i class="fa fa-times"></i> 
                        삭제
                    </a>
                    <?php endif; ?>
                    <a href="javascript:submit_form();" class="btn btn-xs btn-danger">
                        <i class="fa fa-save"></i> 
                        저장
                    </a>
                </div>


<script type="text/javascript">
$(function() {
    var ckeditor_config = {
        height:400,
        resize_enabled : false,
        autoUpdateElement : true,
        enterMode : CKEDITOR.ENTER_BR,
        shiftEnterMode : CKEDITOR.ENTER_P,
        toolbarCanCollapse : true,
        uploadUrl : '/adminpanel/main/upload_image/vod_mjm',
        filebrowserUploadUrl : '/adminpanel/main/upload_image/vod_mjm',
    };
    CKEDITOR.replace('vm_content', ckeditor_config);

    addCalendar('display_date');
    addClock('display_time');
});

function submit_form() {
    var formid = $("#edit_form");
    formid.validate({
        ignore: [],
        invalidHandler: function(e,validator) {
        }
    });
    formid.submit();
}

function delete_form() {
    if(confirm('삭제하시겠습니까?\n삭제하시면 알림에 등록된 내역 모두 자동 삭제됩니다.')) {
        $('[name="mode"]', '#edit_form').val('delete');
        $('#edit_form').submit();
    }
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
		placement: 'bottom',
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

<script src="<?php echo ASSETS_URL; ?>/js/ckeditor/ckeditor.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/clockpicker/clockpicker.min.js"></script>