<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/../').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/../').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "종목추천 - ".ucfirst($mode_kor);

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder

$page_css[] = "products_detail.css";
$page_css[] = "your_style.css";

include realpath(dirname(__FILE__).'/../').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["recommend"]["active"] = true;
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

                <form name='edit_form' id="edit_form" action="/adminpanel/main/recommend_process" method="POST">
                    <input type='hidden' name='mode' value='<?=$mode?>' />
                    <input type='hidden' name='<?=$pk?>' value='<?=$values[$pk]?>' />
                    <input type='hidden' name='rc_portregdate' value='<?=$values['rc_portregdate']?>' />

                <div class="well well-md">
                    <div class="jarviswidget product_tit" id="wid-id-5">
                   
                    <header>
                        <h2>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            종목추천 상세
                        </h2>
                    </header>

                    <!-- widget div-->
                    <div class="form-horizontal">
                        <fieldset>
                                    
                            <?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">No</label>
                                <div class="col-md-10">
                                    <?=$values['rc_id']?>
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
                                    <?=number_format($values['rc_view_count'])?>
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
                                <label for="rc_ticker" class="col-md-2 control-label">종목명 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="ticker" name="rc_ticker" id="rc_ticker" value="<?=$values['rc_ticker']?>" onkeyup="get_ticker_info(this, 'tk_result')" required />
                                    <span class="tk_result"></span>
                                </div>
                            </div>

                            <hr />

                            <div class="form-group">
                                <label for="rc_recom_price" class="col-md-2 control-label">추천가 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" name="rc_recom_price" id="rc_recom_price" value="<?=$values['rc_recom_price']?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_giveup_price" class="col-md-2 control-label">손절가 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" name="rc_giveup_price" id="rc_giveup_price" value="<?=$values['rc_giveup_price']?>" required />
                                    <?php if($values['rc_endtype'] == 'FAIL') : ?>
                                    <span>&nbsp;&nbsp;* 도달 <?=$values['rc_enddate']?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
 
                            <div class="form-group">
                                <label for="rc_goal_price" class="col-md-2 control-label">목표가 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" name="rc_goal_price" id="rc_goal_price" value="<?=$values['rc_goal_price']?>" required />
                                    <?php if($values['rc_endtype'] == 'SUCCESS') : ?>
                                    <span>&nbsp;&nbsp;* 도달 <?=$values['rc_enddate']?></span>
                                    <?php endif; ?>


                                    <select name="rc_adjust">
                                        <?php foreach($adjust_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['rc_adjust'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>


                                    <input type="text" name="rc_adjust_price" id="rc_adjust_price" value="<?=($values['rc_adjust_price']>0) ? $values['rc_adjust_price']:''?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_mid_price" class="col-md-2 control-label">중간매도가 </label>
                                <div class="col-md-10">
                                    <input type="text" name="rc_mid_price" id="rc_mid_price" value="<?=($values['rc_mid_price']>0)? $values['rc_mid_price']:''?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_endtype" class="col-md-2 control-label">추천결과 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <?=$select_rc_endtype?>
                                    <span style='padding-left:30px;'>목표/손절/매도 도달일 : </span>
                                    <input type="text" name="rc_enddate" id="rc_enddate" value="<?=$values['rc_enddate']?>" placeholder="0000-00-00" autocomplete="off" />
                                    <br />
                                    <code>도달일은 [손절가 도달 or 목표가 도달 or 중간 매도] 일때만 노출됨</code>
                                </div>
                            </div>
                                       
                            <div class="form-group">
                                <label for="rc_use_chart" class="col-md-2 control-label">투자매력도 차트 표시 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="rc_use_chart">
                                        <?php foreach($use_chart_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['rc_use_chart'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_is_active" class="col-md-2 control-label">게시물 표출 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="rc_is_active">
                                        <?php foreach($active_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['rc_is_active'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_view_srv" class="col-md-2 control-label">표출 서비스 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="rc_view_srv">
                                        <?php foreach($view_srv_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['rc_view_srv'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_portfolio" class="col-md-2 control-label">포트폴리오 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <select name="rc_portfolio">
                                        <?php foreach($portfolio_map as $key => $val) : ?>
                                        <option value="<?=$key?>" <?=($values['rc_portfolio'] == $key) ? 'selected' : ''?>><?=$val?></option>
                                        <?php endforeach; ?>
                                    </select>

									&nbsp;&nbsp;&nbsp;<label for="rc_is_update">업데이트</label> <input type="checkbox" name="rc_is_update" value="Y" id="rc_is_update" <?=($values['rc_is_update'] == 'Y' ) ? 'checked':''?>>
                                </div>
                            </div>

							<hr />

                            <div class="form-group">
                                <label for="rc_title" class="col-md-2 control-label">제목 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="ticker" name="rc_title" id="rc_title" size="100" value="<?=$values['rc_title']?>" required />
                                </div>
                            </div>

							<div class="form-group">
                                <label for="rc_subtitle" class="col-md-2 control-label">짧은제목 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="ticker" name="rc_subtitle" id="rc_subtitle" size="30" value="<?=$values['rc_subtitle']?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_invest_point" class="col-md-2 control-label">투자포인트 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <textarea name="rc_invest_point" id="rc_invest_point" required><?=$values['rc_invest_point']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="rc_event" class="col-md-2 control-label">이벤트 <span class="required-atr">*</span></label>
                                <div class="col-md-10">
                                    <textarea name="rc_event" id="rc_event" required><?=$values['rc_event']?></textarea>
                                </div>
                            </div>


                            <?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">등록일</label>
                                <div class="col-md-10"> <?=$values['rc_created_at']?> </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">수정일</label>
                                <div class="col-md-10"> <?=$values['rc_updated_at']?> </div>
                            </div>
                            <?php endif; ?>
                                    
                        </fieldset>

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end .jarviswidget-->

                <div class="text-right">
                    <a href="/adminpanel/main/recommend?keep=yes" class="btn btn-xs btn-primary">
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
        height:300,
        resize_enabled : false,
        autoUpdateElement : true,
        enterMode : CKEDITOR.ENTER_BR,
        shiftEnterMode : CKEDITOR.ENTER_P,
        toolbarCanCollapse : true,
        uploadUrl : '/adminpanel/main/upload_image/recommend',
        filebrowserUploadUrl : '/adminpanel/main/upload_image/recommend',
    };
    CKEDITOR.replace('rc_invest_point', ckeditor_config);
    CKEDITOR.replace('rc_event', ckeditor_config);

    addCalendar('display_date');
    addCalendar('rc_enddate');
    addClock('display_time');

    <?php if($values[$pk] > 0) : ?>
    get_ticker_info($('#rc_ticker'), 'tk_result')
    <?php endif; ?>
});


function submit_form() {

    // ticker 유효성
    if($('#rc_ticker').hasClass('ticker_fail')) {
        alert('종목명이 유효하지 않습니다.')
        return;
    }

    var close_price = $('#rc_ticker').data('id'); // 현재가
    var recom_price = $('#rc_recom_price').val(); // 추천가
    var giveup_price = $('#rc_giveup_price').val(); // 손절가
    var goal_price = $('#rc_goal_price').val(); // 목표가

    if( ! (
            $.isNumeric(recom_price)
            && $.isNumeric(giveup_price)
            && $.isNumeric(goal_price)
    )) {
        alert('추천가/손절가/목표가 는 숫자만 입력하세요.');
        return;
    }
/*
    if(parseFloat(close_price) <= parseFloat(giveup_price)) {
        alert('손절가는 현재가보다 작아야 합니다.');
        return;
    }

    if(parseFloat(close_price) >= parseFloat(goal_price)) {
        alert('목표가는 현재가보다 커야 합니다.');
        return;
    }
*/
    var formid = $("#edit_form");
    formid.validate({
        ignore: [],
        invalidHandler: function(e,validator) {
        },
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
