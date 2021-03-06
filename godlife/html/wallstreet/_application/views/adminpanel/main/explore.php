<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/..').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/..').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "탐구생활";
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "jqgrid_custom.css";
$page_css[] = "ui.daterangepicker.css";
include realpath(dirname(__FILE__).'/..').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["main"]["sub"]["explore"]["active"] = true;
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
		<div>배너문구(L) <input type="text" name="bn1_txt" value="<?=$bn1_txt?>" size="45">
		배너ID(L) <input type="text" name="bn1_id" value="<?=$bn1_id?>" size="2">
		배너문구(R) <input type="text" name="bn2_txt" value="<?=$bn2_txt?>" size="45">
		배너ID(R) <input type="text" name="bn2_id" value="<?=$bn2_id?>" size="2">
		&nbsp;&nbsp;<a href='javascript:fnExMainBanner();'>저장</a></div>
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
		
		caption_html += "<a href='/adminpanel/main/explore_detail' class='btn btn-danger btn-xs' style='float:right;margin-left:5px'>";
		caption_html += "<i class='fa fa-plus-circle'> Add New Item</i>";
		caption_html += "</a>";
		

         main_grid = jQuery("#jqgrid").jqGrid({
            caption : caption_html,
            sortname : 'e_id',
            url : "/adminpanel/main/explore?mode=list",
            editurl : "/adminpanel/main/explore?mode=edit",
            datatype : "json",
            shrinkToFit:<?=($this->agent->is_mobile()) ? 'false' : 'true'?>,
            height : 'auto',
            colNames : [
                '썸네일',
                'No',
                '작성자',
                '제목',
                '표출일',
                '표출',
                '내부',
                '푸시',
                '유료/무료',
                '조회수',
                '노출',
                '등록일',
                '수정일',
                'No_from', 'No_to',
                '표출일_from', '표출일_to',
                '조회수_from', '조회수_to',
                '등록일_from', '등록일_to',
                '수정일_from', '수정일_to',
            ],  
			jsonReader : {
			  id: "e_id",
		   	},
            colModel : [
			{"name":"thumbnail","index":"thumbnail","align":"center","sortable":false,"search":false,"editable":false,"width":"100"},
			{"name":"e_id","index":"e_id","align":"center","sortable":false,"search":true,"stype":"between","editable":false},
			{"name":"a_loginid","index":"a_loginid","align":"center","sortable":false,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false},
			{"name":"e_title","index":"e_title","align":"left","sortable":false,"search":true,"searchoptions":{"sopt":["cn"]},"editable":false, "width":"400"},
			{"name":"e_display_date","index":"e_display_date","align":"center","sortable":false,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
            {"name":"e_view_srv","index":"e_view_srv","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$view_srv_map_sel?>"},"editable":true,"edittype":"select","editoptions":{"sopt":["eq"],"value":"<?=$view_srv_map?>"}, "width":"100"},
		    {"name":"e_is_inside","index":"e_is_inside","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$inside_map_sel?>"},"editable":true,"edittype":"select","editoptions":{"sopt":["eq"],"value":"<?=$inside_map?>"}, "width":"60"},
            {"name":"e_push_srv","index":"e_push_srv","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$push_srv_map_sel?>"},"editable":true,"edittype":"select","editoptions":{"sopt":["eq"],"value":"<?=$push_srv_map?>"}, "width":"100"},
            {"name":"e_pay","index":"e_pay","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$pay_map_sel?>"},"editable":true,"edittype":"select","editoptions":{"sopt":["eq"],"value":"<?=$pay_map?>"}, "width":"100"},
			{"name":"e_view_count","index":"e_view_count","align":"center","sortable":false,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
			{"name":"e_is_active","index":"e_is_active","align":"center","sortable":true,"search":true,"stype":"select","searchoptions":{"sopt":["eq"],"value":"<?=$active_map_sel?>"},"editable":true,"edittype":"select","editoptions":{"sopt":["eq"],"value":"<?=$active_map?>"}, "width":"100"},
			{"name":"e_created_at","index":"e_created_at","align":"center","sortable":false,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
			{"name":"e_updated_at","index":"e_updated_at","align":"center","sortable":false,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
			{"name":"e_id_from","index":"e_id_from","viewable":false,"hidden":true},
			{"name":"e_id_to","index":"e_id_to","viewable":false,"hidden":true},
			{"name":"e_display_date_from","index":"e_display_date_from","viewable":false,"hidden":true},
			{"name":"e_display_date_to","index":"e_display_date_to","viewable":false,"hidden":true},
			{"name":"e_view_count_from","index":"e_view_count_from","viewable":false,"hidden":true},
			{"name":"e_view_count_to","index":"e_view_count_to","viewable":false,"hidden":true},
			{"name":"e_created_at_from","index":"e_created_at_from","viewable":false,"hidden":true},
			{"name":"e_created_at_to","index":"e_created_at_to","viewable":false,"hidden":true},
			{"name":"e_updated_at_from","index":"e_updated_at_from","viewable":false,"hidden":true},
			{"name":"e_updated_at_to","index":"e_updated_at_to","viewable":false,"hidden":true}
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

				var edit_fields = ['e_is_active'];
				var click_field = $('tr#'+id+' td:eq('+cell_idx+')').attr('aria-describedby');

				for(var i = 0 ; i < edit_fields.length ; i++) {
					if('jqgrid_'+edit_fields[i] == click_field) {
						$(this).editRow(id, true);
						return;
					}
				}

				var edit_url = '/adminpanel/main/explore_detail/'+id;
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
			var date_fields = ['e_display_date','e_created_at','e_updated_at']; 
			for(var i = 0 ; i < date_fields.length ; i++) {
				add_datepicker(date_fields[i]);
			}
        
});




function set_edit_datepicker(id) {
	var fields = []; 

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
        var url = '/adminpanel/main/explore?mode=edit';
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
function fnExMainBanner() {

	var bn1_id = $('input[name=bn1_id]').val();
	var bn1_txt = $('input[name=bn1_txt]').val();
	var bn2_id = $('input[name=bn2_id]').val();
	var bn2_txt = $('input[name=bn2_txt]').val();
	if(bn1_id == '' || bn1_txt == '' || bn2_id == '' || bn2_txt == '') {
		alert('탐구생활 배너 문구를 입력해 주세요.');
		return;
	} 
	var href = '/adminpanel/main/explore_banner?bn1_id='+encodeURIComponent(bn1_id)+'&bn1_txt='+encodeURIComponent(bn1_txt)+'&bn2_id='+encodeURIComponent(bn2_id)+'&bn2_txt='+encodeURIComponent(bn2_txt);

	$.ajax({
		url : href,
		type : 'get',
		dataType : 'json',
		cache : false,
		success : function(data) {
			if (data.error) {
				alert(data.error);
				return;
			}
			else if (data.success) {
			}
		}
	});
}

</script>
