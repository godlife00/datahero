<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/../').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/../').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "대가종합 - ".ucfirst($mode_kor);

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder

$page_css[] = "products_detail.css";
$page_css[] = "your_style.css";

include realpath(dirname(__FILE__).'/../').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["master"]["active"] = true;
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

                <form name='edit_form' id="edit_form" action="/adminpanel/main/master_process" method="POST" enctype="multipart/form-data">
                    <input type='hidden' name='mode' value='<?=$mode?>' />
                    <input type='hidden' name='<?=$pk?>' value='<?=$values[$pk]?>' />

                <div class="well well-md">
                    <div class="jarviswidget product_tit" id="wid-id-5">
                   
                    <header>
                        <h2>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            대가종합 상세
                        </h2>
                    </header>

                    <!-- widget div-->
                    <div class="form-horizontal">
                        <fieldset>
                                    
                            <?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label">No</label>
                                <div class="col-md-9">
                                    <?=$values['ms_id']?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="ms_guru" class="col-md-3 control-label">Guru (대가 영문명) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="ms_guru" id="ms_guru" value="<?=$values['ms_guru']?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_korguru" class="col-md-3 control-label">Korean Guru (대가 한글명) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="ms_korguru" id="ms_korguru" value="<?=$values['ms_korguru']?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_stocks" class="col-md-3 control-label">Stocks (편입종목수) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="ms_stocks" id="ms_stocks" value="<?=$values['ms_stocks']?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_newstocks" class="col-md-3 control-label">Newstocks (신규 편입종목수) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="ms_newstocks" id="ms_newstocks" value="<?=$values['ms_newstocks']?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_10yavgreturn" class="col-md-3 control-label">10yavgreturn (10년 연평균 수익률(%)) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="ms_10yavgreturn" id="ms_10yavgreturn" value="<?=$values['ms_10yavgreturn']?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_portfolioname" class="col-md-3 control-label">Portfolioname (포트폴리오 이름) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="ms_portfolioname" id="ms_portfolioname" value="<?=$values['ms_portfolioname']?>" required />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="ms_portfoliodate" class="col-md-3 control-label">Portfoliodate (포트폴리오 기준일) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="ms_portfoliodate" id="ms_portfoliodate" value="<?=$values['ms_portfoliodate']?>" placeholder="0000-00-00" autocomplete="off" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_introduce" class="col-md-3 control-label">Introduce (소개글) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <textarea name="ms_introduce" id="ms_introduce" style="width:80%;height:200px;" required /><?=$values['ms_introduce']?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_image" class="col-md-3 control-label">Image (이미지) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <?php if(strlen($values['ms_image']) > 0 && file_exists(ATTACH_DATA.'/master/'.$values['ms_image'])) : ?>
                                    <img src="/webdata/attach_data/master/<?=$values['ms_image']?>" alt="대가 이미지" style="border:solid 1px #ccc;"/>
                                    <?php endif; ?>
                                    <input type="file" class="btn btn-default" name="ms_image" id="ms_image" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ms_representative_ticker" class="col-md-3 control-label">Representative Ticker (대표종목) <span class="required-atr">*</span></label>
                                <div class="col-md-9">
                                    <?php for($i = 0; $i < 5; $i++) : ?>
                                    <div class="ticker_box">
                                        <input type="text" class="ticker" name="ms_representative_ticker[]" value="<?=(isset($rp_ticker[$i])) ? $rp_ticker[$i] : ''?>" onkeyup="get_ticker_info(this, 'tk_result<?=$i?>')" style="width:150px;" />
                                        <span class="tk_result<?=$i?>"></span>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>


                            <?php if($values[$pk] > 0) : ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label">등록일</label>
                                <div class="col-md-9"> <?=$values['ms_created_at']?> </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">수정일</label>
                                <div class="col-md-9"> <?=$values['ms_updated_at']?> </div>
                            </div>
                            <?php endif; ?>
                                    
                        </fieldset>

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end .jarviswidget-->

                <div class="text-right">
                    <a href="/adminpanel/main/master?keep=yes" class="btn btn-xs btn-primary">
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
    addCalendar('ms_portfoliodate');

    $('.ticker_box .ticker').each(function(k,v) {
        get_ticker_info($(this), 'tk_result'+k);
    });
});


function submit_form() {
    var formid = $("#edit_form");
    formid.validate({
        ignore: [],
        invalidHandler: function(e,validator) {
        },
    });

    if($('.ticker.ticker_fail').length > 0) {
        alert('유효하지 않은 종목명이 존재합니다.')
        return;
    }
    
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

<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/clockpicker/clockpicker.min.js"></script>
