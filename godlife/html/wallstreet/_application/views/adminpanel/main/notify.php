<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/..').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/..').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "알림관리";
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "jqgrid_custom.css";
$page_css[] = "ui.daterangepicker.css";
include realpath(dirname(__FILE__).'/..').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["notify"]["active"] = true;
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

                        <table id="jqgrid"></table>
                        <div id="pjqgrid"></div>

                        <br>

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


<script src="<?php echo ASSETS_URL; ?>/js/plugin/jqgrid/jquery.jqGrid.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/jqgrid/grid.locale-en.min.js"></script>

<script type="text/javascript">
    var main_grid;
    var cursel = -1;
    $(document).ready(function() {
            "use strict";

		var caption_html = "<b><i class='fa fa-table'>&nbsp;&nbsp;<?=$page_title?></i></b>"; 

		caption_html += "<a href='/adminpanel/main/notify_detail' class='btn btn-danger btn-xs' style='float:right;margin-left:5px'>";
		caption_html += "<i class='fa fa-plus-circle'> Add New Item</i>";
		caption_html += "</a>";

         main_grid = jQuery("#jqgrid").jqGrid({
            caption : caption_html,
            sortname : 'nt_id',
            url : "/adminpanel/main/notify?mode=list",
            editurl : "/adminpanel/main/notify?mode=edit",
            datatype : "json",
            shrinkToFit:<?=($this->agent->is_mobile()) ? 'false' : 'true'?>,
            height : 'auto',
            colNames : [
                'Id',
                'Title',
                'Table',
                'Ticker',
                'TickerName',
                'Pk',
                'Url',
                'Is Active',
                '표출서비스',
                'Display Date',
                'Created At',
                'Updated At',
                'Id_from','Id_to',
                'Display Date_from', 'Display Date_to',
                'Created At_from','Created At_to',
                'Updated At_from','Updated At_to'
            ],
            jsonReader : {
              id: "nt_id",
            },
            colModel : [
                {"name":"nt_id","index":"nt_id","align":"center","sortable":true,"search":true,"stype":"between","editable":false, "width":"50"},
                {"name":"nt_title","index":"nt_title","align":"left","sortable":true,"search":true,"searchoptions":{"sopt":["cn"]},"editable":false},
                {"name":"nt_table","index":"nt_table","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$table_map_sel?>"},"editable":false, "width":"70"},
                {"name":"nt_ticker","index":"nt_ticker","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false, "width":"50"},
                {"name":"nt_ticker_name","index":"nt_ticker_name","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false, "width":"70"},
                {"name":"nt_pk","index":"nt_pk","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false, "width":"50"},
                {"name":"nt_url","index":"nt_url","align":"left","sortable":true,"search":true,"searchoptions":{"sopt":["cn"]},"editable":false},
                {"name":"nt_is_active","index":"nt_is_active","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$active_map_sel?>"},"editable":false, "width":"50"},
	            {"name":"nt_view_srv","index":"nt_view_srv","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$view_srv_map_sel?>"},"editable":true,"edittype":"select","editoptions":{"sopt":["eq"],"value":"<?=$view_srv_map?>"}, "width":"100"},
                {"name":"nt_display_date","index":"nt_display_date","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false, "width":"80"},
                {"name":"nt_created_at","index":"nt_created_at","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false, "width":"80"},
                {"name":"nt_updated_at","index":"nt_updated_at","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false, "width":"80"},

                {"name":"nt_id_from","index":"nt_id_from","viewable":false,"hidden":true},
                {"name":"nt_id_to","index":"nt_id_to","viewable":false,"hidden":true},
                {"name":"nt_display_date_from","index":"nt_display_date_from","viewable":false,"hidden":true},
                {"name":"nt_display_date_to","index":"nt_display_date_to","viewable":false,"hidden":true},
                {"name":"nt_created_at_from","index":"nt_created_at_from","viewable":false,"hidden":true},
                {"name":"nt_created_at_to","index":"nt_created_at_to","viewable":false,"hidden":true},
                {"name":"nt_updated_at_from","index":"nt_updated_at_from","viewable":false,"hidden":true},
                {"name":"nt_updated_at_to","index":"nt_updated_at_to","viewable":false,"hidden":true}
            ],

            rowNum : 50,
            rowList : [20, 50, 100],
            pager : '#pjqgrid',
            toppager : true,
            toolbarfilter: true,
            hidegrid: false,
            viewrecords : true,
            sortorder : "desc",
            gridComplete : function(){
                ////// 특정필드 굵게, 글씨 크게 강조
                /*
                $('#jqgrid tbody tr').each(function() {
                    var td_vendor = $(this).children('td:eq(4)');

                    if(td_vendor.html().length <= 0) return;
                    td_vendor.css({fontSize:'1.1em', fontWeight : 'bold'});
                });
                */

                ////////////// checkbox td 자체를 없애는 방식. //////////////////

                //$('th#jqgrid_cb').hide().parent().next().find('th:first').hide();
                //$('table.ui-jqgrid-btable tr').find('td:first').hide();

                //////////////////////////////////////////////////////////////////



/*
                ////////////   체크 못하게 할 항목의 checkbox만 없애기. //////////

                // 헤더 체크박스 없애기
                $('th#jqgrid_cb input[type="checkbox"]').hide();

                // 조건 row내 체크박스 없애기. 아래 checkbox 없는 row 선태못하게 하기와 연관..
                var ids = jQuery("#jqgrid").jqGrid('getDataIDs');
                for(var i=0;i < ids.length;i++){
                    var cl = ids[i];
                    var user_count = $('tr#'+cl+' td[aria-describedby="jqgrid_ug_count"]').html();
                    if(parseInt(user_count.split(',').join('')) > 0) {
                        // 조건에 따라 특정 row 체크박스만 없애기
                        $('tr#'+cl+' input[type="checkbox"]:first').remove();
                    }
                }
                //////////////////////////////////////////////////////////////////
*/


                ///////////////////////////////////////////////////////////////////////////////////////////////

                // 마지막 리스트를 기억하기 위한 부분. Hamt.

                if((typeof $.cookie) != 'undefined') {

                    $.cookie('jqgrid_last_postdata', JSON.stringify({url:location.href.split('?')[0], data:this.p.postData}));

                    if(location.href.indexOf('keep=yes') > 0) {

                        var postdata = this.p.postData;

                        if((typeof postdata.filters) != 'undefined') {

                            var filters = JSON.parse(postdata.filters);

                            for(var i = 0 ; i < filters.rules.length ; i++) {

                                $('div.ui-'+this.id+'-hbox select[name="'+filters.rules[i].field+'"]').val(filters.rules[i].data);
                                $('div.ui-'+this.id+'-hbox input[name="'+filters.rules[i].field+'"]').val(filters.rules[i].data);

                            }

                        }

                    }

                }

                ///////////////////////////////////////////////////////////////////////////////////////////////



            },

            onSelectRow :  function(id) {
                // 수정모드에서 달력 제공
                set_edit_datepicker(id); // todo. date type field 달력제공 알맞게 설정
                if($('#'+id+' input[type="checkbox"]').length <= 0) {
                    // checkbox 없는 row는 활성화 안되게 하기.
                    $('#'+id).removeClass('ui-state-highlight').attr('aria-selected', 'false');
                    return false;
                }
            },

            multipleSearch:true,
            multiselect : true,
            autowidth : true,
            onCellSelect: function(id, cell_idx, cell_value){

                cursel = id;
                if(cell_idx == 0) {
                    return;
                }

                var edit_fields = [];
                var click_field = $('tr#'+id+' td:eq('+cell_idx+')').attr('aria-describedby');

                for(var i = 0 ; i < edit_fields.length ; i++) {
                    if('jqgrid_'+edit_fields[i] == click_field) {
                        $(this).editRow(id, true);
                        return;
                    }
                }

				var edit_url = '/adminpanel/main/notify_detail/'+id;
                if(edit_url.length > 0) {
                    location.href = edit_url;
                    return;
                }
            }
    });





jQuery("#jqgrid").jqGrid('navGrid', "#pjqgrid", {
                edit : false,
                add : false,
                del : false,
                refresh:true,
                search:false,
                cloneToTop:true
            },
            {},
            {},
            {},
            {sopt:['eq', 'ne', 'bw', 'ew', 'cn', 'in', 'ni']}
            );
            //jQuery("#jqgrid").jqGrid('inlineNav', "#pjqgrid");
            /* Add tooltips */
            $('.navtable .ui-pg-button').tooltip({
                container : 'body'
            }
    );


            jQuery("#jqgrid").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, searchOperators:true});



            // remove classes
            $(".ui-jqgrid").removeClass("ui-widget ui-widget-content");
            $(".ui-jqgrid-view").children().removeClass("ui-widget-header ui-state-default");
            $(".ui-jqgrid-labels, .ui-search-toolbar").children().removeClass("ui-state-default ui-th-column ui-th-ltr");
            $(".ui-jqgrid-pager").removeClass("ui-state-default");
            $(".ui-jqgrid").removeClass("ui-widget-content");

            // add classes
            $(".ui-jqgrid-htable").addClass("table table-bordered table-hover");
            $(".ui-jqgrid-btable").addClass("table table-bordered table-striped");


            $(".ui-pg-div").removeClass().addClass("btn btn-sm btn-primary");
            $(".ui-icon.ui-icon-plus").removeClass().addClass("fa fa-plus");
            $(".ui-icon.ui-icon-pencil").removeClass().addClass("fa fa-pencil");
            $(".ui-icon.ui-icon-trash").removeClass().addClass("fa fa-trash-o");
            $(".ui-icon.ui-icon-search").removeClass().addClass("fa fa-search");
            $(".ui-icon.ui-icon-refresh").removeClass().addClass("fa fa-refresh");
            $(".ui-icon.ui-icon-disk").removeClass().addClass("fa fa-save").parent(".btn-primary").removeClass("btn-primary").addClass("btn-success");
            $(".ui-icon.ui-icon-cancel").removeClass().addClass("fa fa-times").parent(".btn-primary").removeClass("btn-primary").addClass("btn-danger");

            $( ".ui-icon.ui-icon-seek-prev" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
            $(".ui-icon.ui-icon-seek-prev").removeClass().addClass("fa fa-backward");

            $( ".ui-icon.ui-icon-seek-first" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
            $(".ui-icon.ui-icon-seek-first").removeClass().addClass("fa fa-fast-backward");

            $( ".ui-icon.ui-icon-seek-next" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
            $(".ui-icon.ui-icon-seek-next").removeClass().addClass("fa fa-forward");

            $( ".ui-icon.ui-icon-seek-end" ).wrap( "<div class='btn btn-sm btn-default'></div>" );
            $(".ui-icon.ui-icon-seek-end").removeClass().addClass("fa fa-fast-forward");

            // todo. set Date(calendar) format field
            //add_datepicker('dc_created_at');
            //add_datepicker('dc_used_at');

            $("#save_row").click(function() {
                if(cursel != -1) {
                    jQuery("#jqgrid").jqGrid('saveRow',cursel);
                }
            });

            // date picker
            var date_fields = ['nt_display_date', 'nt_created_at','nt_updated_at'];
            for(var i = 0 ; i < date_fields.length ; i++) {
                add_datepicker(date_fields[i]);
            }

});




function set_edit_datepicker(id) {
    var fields = ['nt_created_at','nt_updated_at'];

    var struct = {
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            /*
            onClose: function (selectedDate) {
                //$('#'+id+'_[[todo.포커스필드명]]').focus();
            }
            */
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>'
        };
    for(var i = 0 ; i < fields.length ; i++) {
        $('#'+id+'_'+fields[i]).datepicker(struct);
    }
}

function add_datepicker(field_id) {
    var from_struct = {
            dateFormat: 'yy-mm-dd',
            defaultDate: "-1d",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function (selectedDate) {
                $("#gs_"+field_id+"_to").datepicker("option", "minDate", selectedDate);
            }
        };
    var to_struct = {
            dateFormat: 'yy-mm-dd',
            defaultDate: "+0d",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function (selectedDate) {
                $("#gs_"+field_id+"_from").datepicker("option", "maxDate", selectedDate);
            }
        };
    $("#gs_"+field_id+"_from").datepicker(from_struct);
    $("#gs_"+field_id+"_to").datepicker(to_struct);
}
function update_status() {
    var ids = $("#jqgrid").jqGrid('getGridParam', 'selarrrow');

    if(ids.length < 1) {
        alert('Please check row');
        return ;
    }

    // todo. get param value

    if(ids.length > 0) {
        var url = '/adminpanel/main/notify?mode=edit';
        var param = ''; // todo.  set param value (a=1&b=2..)
        $.post(url, param, function(data){
            if(data == 'success') {
                jqgrid.p.isFirstRequest = true;
                var postdata = $("#jqgrid").jqGrid('getGridParam', 'postData');

                $("#jqgrid").jqGrid('setGridParam', {postData: postdata});
                $("#jqgrid").trigger("reloadGrid");
            }
        });
    }
}
</script>

