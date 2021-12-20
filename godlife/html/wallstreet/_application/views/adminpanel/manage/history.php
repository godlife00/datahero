<?php
//initilize the page
require_once realpath(dirname(__FILE__)).'/../inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__)).'/../inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage History";
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "jqgrid_custom.css";
$page_css[] = "ui.daterangepicker.css";
include realpath(dirname(__FILE__)).'/../inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["manage"]["sub"]["history"]["active"] = true;
include realpath(dirname(__FILE__)).'/../inc/nav.php';
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<style type="text/css">
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
        $breadcrumbs["System"] = "";
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
                       	Manage 
                    <span>>  
                        Admin History
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
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-x:scroll;">
            
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left:-20%">
  <div class="modal-dialog">
    <div class="modal-content" style="width:855px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">History Data</h4>
      </div>
      <div class="modal-body" id="myModal_body" style="max-height:780px; overflow-y:auto;">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-primary" data-dismiss="modal" id="close_modal">
            <i class="fa fa-times"></i>
            Close
        </button>
      </div>
    </div>
  </div>
</div>



<!-- ==========================CONTENT ENDS HERE ========================== -->


<script src="<?php echo ASSETS_URL; ?>/js/plugin/jqgrid/jquery.jqGrid.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/jqgrid/grid.locale-en.min.js"></script>
<script type="text/javascript">
	var main_grid;
    var cursel = -1;
    $(document).ready(function() {
        var search_option ={sopt:['eq', 'le', 'ge', 'ne', 'bw', 'ew', 'cn', 'in', 'ni']};
		var soption_eq = {sopt:['eq']};
		var soption_cn = {sopt:['cn']};

		var caption_html = "<b><i class='fa fa-table'>&nbsp;&nbsp;Manage History</i></b>"; 

         main_grid = jQuery("#jqgrid").jqGrid({
            caption : caption_html,
            sortname : 'h_id',
            url : "/adminpanel/manage/history?mode=list",
            datatype : "json",
            shrinkToFit:<?=($this->agent->is_mobile()) ? 'false' : 'true'?>,
            height : 'auto',
            colNames : ['ID', 'Access id', 'Access Name', 'Act', 'Access Table', 'Access Key', 'IP', 'created_at', 'created_at_from', 'created_at_to', 'serialize'],  
			jsonReader : {
			  id: "h_id",
		   	},
            colModel : [
                { name : 'h_id', index : 'h_id', search:true, searchoptions:soption_eq, width:80, align:'center'}, 
                { name : 'h_loginid', index : 'h_loginid', editable:false, search:true, searchoptions:soption_cn, align:'center'}, 
                { name : 'h_name', index : 'h_name', editable:false, search:true, searchoptions:soption_cn, align:'center'},
                { name : 'h_act_mode', index : 'h_act_mode', editable:false, search:true, searchoptions:soption_cn, align:'center'},
                { name : 'h_act_table', index : 'h_act_table', editable:false, search:true, searchoptions:soption_cn, align:'center'},
                { name : 'h_act_key', index : 'h_act_key', editable:false, search:true, searchoptions:soption_cn, align:'center'},
                { name : 'h_ip', index : 'h_ip', editable:false, search:true, searchoptions:soption_cn, align:'center'},
                { name : 'h_created_at', index : 'h_created_at', editable:false, search:true, stype:'between', align:'center'},
                { name : 'h_created_at_from', index : 'h_created_at_from', viewable:false, hidden:true},
                { name : 'h_created_at_to', index : 'h_created_at_to', viewable:false, hidden:true},
                { name : 'h_serialize', index : 'h_serialize', viewable:false, hidden:true}
            ],
			
            rowNum : 50,
            rowList : [20, 50, 100],
            pager : '#pjqgrid',
            toolbarfilter: true,
            hidegrid: false,
            viewrecords : true,
            sortorder : "desc",
			gridComplete : function(){
				$('#jqgrid tbody tr').each(function() {
					var td_vendor = $(this).children('td:eq(4)');

					if(td_vendor.html().length <= 0) return;
					td_vendor.css({fontSize:'1.1em', fontWeight : 'bold'});
				});

			},

			multipleSearch:true,
            multiselect : false,
            autowidth : true,
            onCellSelect: function(id, cell_idx, cell_value){
                var selData = $("#jqgrid").getRowData(id);
                var log_data = $.parseJSON(selData.h_serialize);
                var h_html = '<h3>Info</h3>';
                h_html += '<table class="table table-bordered"><thead><tr><th>Access</th><th>Act</th><th>Access Table</th><th>Access Key</th><th>IP</th><th>created_at</th></tr></thead><tr><td>'+selData.h_name+' ('+selData.h_loginid+')</td><td>'+selData.h_act_mode+'</td><td>'+selData.h_act_table+'</td><td>'+selData.h_act_key+'</td><td>'+selData.h_ip+'</td><td>'+selData.h_created_at+'</td></tr></table>';
                for(log in log_data){
                    if(typeof(log_data[log]) != 'object'){
                        h_html += '<div>';
                        h_html += '<h6 class="text-primary">';
                        h_html += '<i class="fa fa-chevron-circle-right"></i> '+log;
                        h_html += '</h6>';
                        h_html += '</div>';

                        h_html += '<table class="table table-striped table-bordered table-condensed" style="width:800px; font-size:9pt;">';
                        h_html += '<tr><th class="col-sm-3 col-md-3">'+log+'</th><td>'+log_data[log]+'</td></tr>';
                        h_html += '</table>';
                    } else{

                        h_html += '<div>';
                        h_html += '<h6 class="text-primary">';
                        h_html += '<i class="fa fa-chevron-circle-right"></i> '+log;
                        h_html += '</h6>';
                        h_html += '</div>';

                        h_html += '<table class="table table-striped table-bordered table-condensed" style="width:800px; font-size:9pt;">';
                        for(key in log_data[log]){
                            _obj_print(log_data[log][key]);
                            h_html += '<tr>';
                            h_html += '<th class="col-sm-3 col-md-3">'+key+'</td>';
                            h_html += '<td>'+_obj_print(log_data[log][key])+'</td>';
                            //h_html += '<td>'+log_data[log][key]+'</td>';
                            h_html += '</tr>';
                        }
                        h_html += '</table>';

                    }
                }
                $("#myModal_body").html(h_html);
                $("#myModal").modal('show');
            }
    });

    jQuery("#jqgrid").jqGrid('navGrid', "#pjqgrid", {
                edit : false,
                add : false,
                del : false,
                refresh:false,
                search:false
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

			add_datepicker('h_created_at');
    });

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


function _obj_print(obj) {
    if(typeof obj === 'object') {
        var output = '';
        for (var property in obj) {
            if(obj[property].length > 0) {
                output += '['+property+']'+' => '+obj[property]+'<BR> ';
            }
        }
        return output;
    }else {
        return obj;
    }
}
</script>
