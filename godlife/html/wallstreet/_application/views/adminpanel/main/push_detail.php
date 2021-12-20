<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/../').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/../').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "푸시 - ".ucfirst($mode_kor);

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder

$page_css[] = "products_detail.css";
$page_css[] = "your_style.css";

include realpath(dirname(__FILE__).'/../').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["push"]["active"] = true;
include realpath(dirname(__FILE__).'/../').'/inc/nav.php';

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
#edit_form input[type="text"] {width:300px;}
</style>

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

                <form name='edit_form' id="edit_form" action="/adminpanel/main/push_process" method="POST">
                    <input type='hidden' name='mode' value='<?=$mode?>' />
                    <input type='hidden' name='<?=$pk?>' value='<?=$values[$pk]?>' />

                <div class="well well-md">
                    <div class="jarviswidget product_tit" id="wid-id-5">
                   
                    <header>
                        <h2>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            푸시 상세
                        </h2>
                    </header>

                    <!-- widget div-->
                    <div class="form-horizontal">
                        <fieldset>
                                    
                            <?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label">No</label>
                                <div class="col-md-9">
                                    <?=$values['pu_id']?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="display_date" class="col-md-1 control-label">전송일 <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="display_date" id="display_date" value="<?=$display_date?>" placeholder="0000-00-00" autocomplete="off" style="width:15%;" required />
                                    <input type="text" name="display_time" id="display_time" value="<?=$display_time?>" placeholder="00:00" data-autoclose="true" autocomplete="off" style="width:15%;" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pu_title" class="col-md-1 control-label">제목 <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="pu_title" id="pu_title" value="<?=$values['pu_title']?>" style="width:80%;"  required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pu_is_push" class="col-md-1 control-label">전송(Y/N) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <select name="pu_is_push">
                                        <?php foreach($push_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['pu_is_push'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>

									&nbsp;&nbsp;&nbsp;<label for="pu_all">전체</label> <input type="checkbox" name="pu_all" value="Y" id="pu_all" <?=($values['pu_all'] == 'Y' ) ? 'checked':''?>>
									&nbsp;&nbsp;&nbsp;<label for="pu_kiwoom">키움</label> <input type="checkbox" name="pu_kiwoom" value="Y" id="pu_kiwoom" <?=($values['pu_kiwoom'] == 'Y' ) ? 'checked':''?>>
									&nbsp;&nbsp;&nbsp;<label for="pu_choice">초이스</label> <input type="checkbox" name="pu_choice" value="Y" id="pu_choice" <?=($values['pu_choice'] == 'Y' ) ? 'checked':''?>>

                                </div>
                            </div>

							<div class="form-group">
                                <label for="pu_content" class="col-md-1 control-label"></label>
                                <div class="col-md-9">
								<strong>현재 <font color="red"><span id="titleByteChk"><?=$values['length']?></span></font></strong> byte / 1,800 byte
                                </div>
                            </div>

							<div class="form-group">
                                <label for="pu_content" class="col-md-1 control-label">내 용 <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <textarea name="pu_content" id="pu_content" style="width:80%;height:250px;" onkeyup="check_byte('pu_content', 'titleByteChk');" required /><?=$values['pu_content']?></textarea>
                                </div>
                            </div>

                            <!--<div class="form-group">
                                <label for="pu_guru" class="col-md-1 control-label">URL <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="pu_url" id="pu_url" value="<?=$values['pu_url']?>" style="width:80%;"  />
                                </div>
                            </div>-->

                            <?php if($values[$pk] > 0) : ?>
							<div class="form-group">
                                <label class="col-md-3 control-label">전송결과(Y/N)</label>
                                <div class="col-md-9"> <?=$values['pu_push']?> </div>
                            </div>

							<div class="form-group">
                                <label class="col-md-3 control-label">등록일</label>
                                <div class="col-md-9"> <?=$values['pu_created_at']?> </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">수정일</label>
                                <div class="col-md-9"> <?=$values['pu_updated_at']?> </div>
                            </div>
                            <?php endif; ?>
                                    
                        </fieldset>

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end .jarviswidget-->

                <div class="text-right">
                    <a href="/adminpanel/main/push?keep=yes" class="btn btn-xs btn-primary">
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
    addCalendar('display_date');
    addClock('display_time');
});

function submit_form() {
    var formid = $("#edit_form");
    formid.validate({
        ignore: [],
        invalidHandler: function(e,validator) {
        },
    });
/*
    if($('.ticker.ticker_fail').length > 0) {
        alert('유효하지 않은 종목명이 존재합니다.')
        return;
    }
*/  
    formid.submit();
}

function delete_form() {
    if(confirm('삭제하시겠습니까?')) {
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

function check_byte(content, target)
{
		var i = 0;
		var cnt = 0;
		var ch = '';
		var cont = document.getElementById(content).value;

		for (i=0; i<cont.length; i++) {
				ch = cont.charAt(i);
				if (escape(ch).length > 4) {
						cnt += 2;
				} else {
						cnt += 1;
				}
		}
		//if(cnt>40) alert("제목이40Byte를 초과 했습니다.");
		document.getElementById(target).innerHTML = cnt;
		return cnt;
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
