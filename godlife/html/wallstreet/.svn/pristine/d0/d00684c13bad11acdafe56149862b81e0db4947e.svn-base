<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/../').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/../').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "알림관리 - ".ucfirst($mode_kor);

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder

$page_css[] = "products_detail.css";

include realpath(dirname(__FILE__).'/../').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["notify"]["active"] = true;
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
                        월가히어로
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

                <form name='edit_form' id="edit_form" action="/adminpanel/main/notify_process" method="POST">
                    <input type='hidden' name='mode' value='<?=$mode?>' />
                    <input type='hidden' name='<?=$pk?>' value='<?=$values[$pk]?>' />
                    <input type='hidden' name='nt_table' value='<?=$values['nt_table']?>' />

                <div class="well well-md">
                    <div class="jarviswidget product_tit" id="wid-id-5">
                   
                    <header>
                        <h2>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            알림관리 상세
                        </h2>
                    </header>

                    <!-- widget div-->
                    <div class="form-horizontal">
                        <fieldset>
                                    
                            <?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">No</label>
                                <div class="col-md-10">
                                    <?=$values['nt_id']?>
                                </div>
                            </div>
                            <?php endif; ?>
                                    
                            <div class="form-group">
                                <label for="nt_table" class="col-md-2 control-label">이벤트 종류</label>
                                <div class="col-md-10">
                                    <strong>[ <?=$table_map[$values['nt_table']]?> ]</strong>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nt_title" class="col-md-2 control-label">제목 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" name="nt_title" id="nt_title" value="<?=$values['nt_title']?>" style="width:80%;" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nt_ticker" class="col-md-2 control-label">티커 </label>
                                <div class="col-md-10">
                                    <input type="text" name="nt_ticker" id="nt_ticker" value="<?=$values['nt_ticker']?>" style="width:10%;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nt_ticker_name" class="col-md-2 control-label">티커명 </label>
                                <div class="col-md-10">
                                    <input type="text" name="nt_ticker_name" id="nt_ticker_name" value="<?=$values['nt_ticker_name']?>" style="width:10%;">
                                </div>
                            </div>

							<?php if($values['nt_table'] != 'custom') : ?>
                            <div class="form-group">
                                <label for="nt_pk" class="col-md-2 control-label">PK(번호)</label>
                                <div class="col-md-10">
                                    <strong><?=$values['nt_pk']?></strong>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="nt_url" class="col-md-2 control-label">링크</label>
                                <div class="col-md-10">
                                    <input type="text" name="nt_url" id="nt_url" value="<?=$values['nt_url']?>" style="width:80%;" <?=($values['nt_table'] != 'custom') ? 'disabled' : ''?>>
                                    <code>내부링크로 연결시 '/' 부터 입력하세요. ex) /search/summary/AAPL</code>
                                </div>
                            </div>
                                    
                            <div class="form-group">
                                <label for="display_date" class="col-md-2 control-label">표출일 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" name="display_date" id="display_date" value="<?=$display_date?>" placeholder="0000-00-00" autocomplete="off" required />
                                    <input type="text" name="display_time" id="display_time" value="<?=$display_time?>" placeholder="00:00" data-autoclose="true" autocomplete="off" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nt_is_active" class="col-md-2 control-label">게시물 표출 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="nt_is_active">
                                        <?php foreach($active_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['nt_is_active'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nt_view_srv" class="col-md-2 control-label">표출 서비스 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="nt_view_srv">
                                        <?php foreach($view_srv_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['nt_view_srv'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">등록일</label>
                                <div class="col-md-10"> <?=$values['nt_created_at']?> </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">수정일</label>
                                <div class="col-md-10"> <?=$values['nt_updated_at']?> </div>
                            </div>
                            <?php endif; ?>

                        </fieldset>

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end .jarviswidget-->

                <div class="text-right">
                    <a href="/adminpanel/main/notify?keep=yes" class="btn btn-xs btn-primary">
                        <i class="fa fa-list-alt"></i> 
                        목록
                    </a>
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

<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/clockpicker/clockpicker.min.js"></script>
