<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/..').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/..').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Data Mri";
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "jqgrid_custom.css";
$page_css[] = "ui.daterangepicker.css";
include realpath(dirname(__FILE__).'/..').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["data"]["sub"]["mri"]["active"] = true;
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
        $breadcrumbs["data"] = "";
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
                        Data
                    <span>>  
                        Mri
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

		var caption_html = "<b><i class='fa fa-table'>&nbsp;&nbsp;Data Mri</i></b>"; 
		

         main_grid = jQuery("#jqgrid").jqGrid({
            caption : caption_html,
            sortname : 'm_ticker',
            url : "/adminpanel/data/mri?mode=list",
            editurl : "/adminpanel/data/mri?mode=edit",
            datatype : "json",
            shrinkToFit:<?=($this->agent->is_mobile()) ? 'false' : 'true'?>,
            height : 'auto',
            colNames : ['Ticker','Usname','Korname','Exchange','Sector','Industry','Growth Score','Growth Stars','Safety Score','Safety Stars','Cashflow Score','Cashflow Stars','Moat Score','Moat Stars','Valuation Score','Valuation Stars','Total Score','Biz Growth Score','Biz Growth Stars','Biz Safety Score','Biz Safety Stars','Biz Cashflow Score','Biz Cashflow Stars','Biz Moat Score','Biz Moat Stars','Biz Dividend Score','Biz Dividend Stars','Biz Total Score','Sep Date','Close','Marketcap','Netmargin','Pbr','Per','Psr','Roe','Divyield','Eps1','Eps2','Eps3','Eps4','Eps1 Date','Eps2 Date','Eps3 Date','Eps4 Date','Date','Growth Score_from','Growth Score_to','Growth Stars_from','Growth Stars_to','Safety Score_from','Safety Score_to','Safety Stars_from','Safety Stars_to','Cashflow Score_from','Cashflow Score_to','Cashflow Stars_from','Cashflow Stars_to','Moat Score_from','Moat Score_to','Moat Stars_from','Moat Stars_to','Valuation Score_from','Valuation Score_to','Valuation Stars_from','Valuation Stars_to','Total Score_from','Total Score_to','Biz Growth Score_from','Biz Growth Score_to','Biz Growth Stars_from','Biz Growth Stars_to','Biz Safety Score_from','Biz Safety Score_to','Biz Safety Stars_from','Biz Safety Stars_to','Biz Cashflow Score_from','Biz Cashflow Score_to','Biz Cashflow Stars_from','Biz Cashflow Stars_to','Biz Moat Score_from','Biz Moat Score_to','Biz Moat Stars_from','Biz Moat Stars_to','Biz Dividend Score_from','Biz Dividend Score_to','Biz Dividend Stars_from','Biz Dividend Stars_to','Biz Total Score_from','Biz Total Score_to','Sep Date_from','Sep Date_to','Close_from','Close_to','Marketcap_from','Marketcap_to','Netmargin_from','Netmargin_to','Pbr_from','Pbr_to','Per_from','Per_to','Psr_from','Psr_to','Roe_from','Roe_to','Divyield_from','Divyield_to','Eps1_from','Eps1_to','Eps2_from','Eps2_to','Eps3_from','Eps3_to','Eps4_from','Eps4_to','Eps1 Date_from','Eps1 Date_to','Eps2 Date_from','Eps2 Date_to','Eps3 Date_from','Eps3 Date_to','Eps4 Date_from','Eps4 Date_to','Date_from','Date_to'],  
			jsonReader : {
			  id: "m_ticker",
		   	},
            colModel : [

{"name":"m_ticker","index":"m_ticker","align":"center","sortable":false,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false},
{hidden:true,"name":"m_usname","index":"m_usname","align":"left","sortable":false,"search":true,"searchoptions":{"sopt":["cn"]},"editable":false},
{"name":"m_korname","index":"m_korname",'width':'150px',"align":"left","sortable":false,"search":true,"searchoptions":{"sopt":["cn"]},"editable":false},
{"name":"m_exchange","index":"m_exchange","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false},
{"name":"m_sector","index":"m_sector","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false},
{"name":"m_industry","index":"m_industry","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false},

{"name":"m_growth_score","index":"m_growth_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_growth_stars","index":"m_growth_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_safety_score","index":"m_safety_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_safety_stars","index":"m_safety_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_cashflow_score","index":"m_cashflow_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_cashflow_stars","index":"m_cashflow_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_moat_score","index":"m_moat_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_moat_stars","index":"m_moat_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_valuation_score","index":"m_valuation_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_valuation_stars","index":"m_valuation_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_total_score","index":"m_total_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_biz_growth_score","index":"m_biz_growth_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_biz_growth_stars","index":"m_biz_growth_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_biz_safety_score","index":"m_biz_safety_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_biz_safety_stars","index":"m_biz_safety_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_biz_cashflow_score","index":"m_biz_cashflow_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_biz_cashflow_stars","index":"m_biz_cashflow_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_biz_moat_score","index":"m_biz_moat_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_biz_moat_stars","index":"m_biz_moat_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_biz_dividend_score","index":"m_biz_dividend_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_biz_dividend_stars","index":"m_biz_dividend_stars","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_biz_total_score","index":"m_biz_total_score","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

{"name":"m_sep_date","index":"m_sep_date","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},

{"name":"m_close","index":"m_close","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{"name":"m_marketcap","index":"m_marketcap","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{"name":"m_netmargin","index":"m_netmargin","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{"name":"m_pbr","index":"m_pbr","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{"name":"m_per","index":"m_per","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{"name":"m_psr","index":"m_psr","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{"name":"m_roe","index":"m_roe","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{"name":"m_divyield","index":"m_divyield","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_eps1","index":"m_eps1","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_eps2","index":"m_eps2","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_eps3","index":"m_eps3","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_eps4","index":"m_eps4","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
{hidden:true,"name":"m_eps1_date","index":"m_eps1_date","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
{hidden:true,"name":"m_eps2_date","index":"m_eps2_date","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
{hidden:true,"name":"m_eps3_date","index":"m_eps3_date","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
{hidden:true,"name":"m_eps4_date","index":"m_eps4_date","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
{"name":"m_date","index":"m_date","align":"center","sortable":true,"search":true,"stype":"between","editable":false},



{"name":"m_growth_score_from","index":"m_growth_score_from","viewable":false,"hidden":true},
{"name":"m_growth_score_to","index":"m_growth_score_to","viewable":false,"hidden":true},
{"name":"m_growth_stars_from","index":"m_growth_stars_from","viewable":false,"hidden":true},
{"name":"m_growth_stars_to","index":"m_growth_stars_to","viewable":false,"hidden":true},
{"name":"m_safety_score_from","index":"m_safety_score_from","viewable":false,"hidden":true},
{"name":"m_safety_score_to","index":"m_safety_score_to","viewable":false,"hidden":true},
{"name":"m_safety_stars_from","index":"m_safety_stars_from","viewable":false,"hidden":true},
{"name":"m_safety_stars_to","index":"m_safety_stars_to","viewable":false,"hidden":true},
{"name":"m_cashflow_score_from","index":"m_cashflow_score_from","viewable":false,"hidden":true},
{"name":"m_cashflow_score_to","index":"m_cashflow_score_to","viewable":false,"hidden":true},
{"name":"m_cashflow_stars_from","index":"m_cashflow_stars_from","viewable":false,"hidden":true},
{"name":"m_cashflow_stars_to","index":"m_cashflow_stars_to","viewable":false,"hidden":true},
{"name":"m_moat_score_from","index":"m_moat_score_from","viewable":false,"hidden":true},
{"name":"m_moat_score_to","index":"m_moat_score_to","viewable":false,"hidden":true},
{"name":"m_moat_stars_from","index":"m_moat_stars_from","viewable":false,"hidden":true},
{"name":"m_moat_stars_to","index":"m_moat_stars_to","viewable":false,"hidden":true},
{"name":"m_valuation_score_from","index":"m_valuation_score_from","viewable":false,"hidden":true},
{"name":"m_valuation_score_to","index":"m_valuation_score_to","viewable":false,"hidden":true},
{"name":"m_valuation_stars_from","index":"m_valuation_stars_from","viewable":false,"hidden":true},
{"name":"m_valuation_stars_to","index":"m_valuation_stars_to","viewable":false,"hidden":true},
{"name":"m_total_score_from","index":"m_total_score_from","viewable":false,"hidden":true},
{"name":"m_total_score_to","index":"m_total_score_to","viewable":false,"hidden":true},
{"name":"m_biz_growth_score_from","index":"m_biz_growth_score_from","viewable":false,"hidden":true},
{"name":"m_biz_growth_score_to","index":"m_biz_growth_score_to","viewable":false,"hidden":true},
{"name":"m_biz_growth_stars_from","index":"m_biz_growth_stars_from","viewable":false,"hidden":true},
{"name":"m_biz_growth_stars_to","index":"m_biz_growth_stars_to","viewable":false,"hidden":true},
{"name":"m_biz_safety_score_from","index":"m_biz_safety_score_from","viewable":false,"hidden":true},
{"name":"m_biz_safety_score_to","index":"m_biz_safety_score_to","viewable":false,"hidden":true},
{"name":"m_biz_safety_stars_from","index":"m_biz_safety_stars_from","viewable":false,"hidden":true},
{"name":"m_biz_safety_stars_to","index":"m_biz_safety_stars_to","viewable":false,"hidden":true},
{"name":"m_biz_cashflow_score_from","index":"m_biz_cashflow_score_from","viewable":false,"hidden":true},
{"name":"m_biz_cashflow_score_to","index":"m_biz_cashflow_score_to","viewable":false,"hidden":true},
{"name":"m_biz_cashflow_stars_from","index":"m_biz_cashflow_stars_from","viewable":false,"hidden":true},
{"name":"m_biz_cashflow_stars_to","index":"m_biz_cashflow_stars_to","viewable":false,"hidden":true},
{"name":"m_biz_moat_score_from","index":"m_biz_moat_score_from","viewable":false,"hidden":true},
{"name":"m_biz_moat_score_to","index":"m_biz_moat_score_to","viewable":false,"hidden":true},
{"name":"m_biz_moat_stars_from","index":"m_biz_moat_stars_from","viewable":false,"hidden":true},
{"name":"m_biz_moat_stars_to","index":"m_biz_moat_stars_to","viewable":false,"hidden":true},
{"name":"m_biz_dividend_score_from","index":"m_biz_dividend_score_from","viewable":false,"hidden":true},
{"name":"m_biz_dividend_score_to","index":"m_biz_dividend_score_to","viewable":false,"hidden":true},
{"name":"m_biz_dividend_stars_from","index":"m_biz_dividend_stars_from","viewable":false,"hidden":true},
{"name":"m_biz_dividend_stars_to","index":"m_biz_dividend_stars_to","viewable":false,"hidden":true},
{"name":"m_biz_total_score_from","index":"m_biz_total_score_from","viewable":false,"hidden":true},
{"name":"m_biz_total_score_to","index":"m_biz_total_score_to","viewable":false,"hidden":true},
{"name":"m_sep_date_from","index":"m_sep_date_from","viewable":false,"hidden":true},
{"name":"m_sep_date_to","index":"m_sep_date_to","viewable":false,"hidden":true},
{"name":"m_close_from","index":"m_close_from","viewable":false,"hidden":true},
{"name":"m_close_to","index":"m_close_to","viewable":false,"hidden":true},
{"name":"m_marketcap_from","index":"m_marketcap_from","viewable":false,"hidden":true},
{"name":"m_marketcap_to","index":"m_marketcap_to","viewable":false,"hidden":true},
{"name":"m_netmargin_from","index":"m_netmargin_from","viewable":false,"hidden":true},
{"name":"m_netmargin_to","index":"m_netmargin_to","viewable":false,"hidden":true},
{"name":"m_pbr_from","index":"m_pbr_from","viewable":false,"hidden":true},
{"name":"m_pbr_to","index":"m_pbr_to","viewable":false,"hidden":true},
{"name":"m_per_from","index":"m_per_from","viewable":false,"hidden":true},
{"name":"m_per_to","index":"m_per_to","viewable":false,"hidden":true},
{"name":"m_psr_from","index":"m_psr_from","viewable":false,"hidden":true},
{"name":"m_psr_to","index":"m_psr_to","viewable":false,"hidden":true},
{"name":"m_roe_from","index":"m_roe_from","viewable":false,"hidden":true},
{"name":"m_roe_to","index":"m_roe_to","viewable":false,"hidden":true},
{"name":"m_divyield_from","index":"m_divyield_from","viewable":false,"hidden":true},
{"name":"m_divyield_to","index":"m_divyield_to","viewable":false,"hidden":true},
{"name":"m_eps1_from","index":"m_eps1_from","viewable":false,"hidden":true},
{"name":"m_eps1_to","index":"m_eps1_to","viewable":false,"hidden":true},
{"name":"m_eps2_from","index":"m_eps2_from","viewable":false,"hidden":true},
{"name":"m_eps2_to","index":"m_eps2_to","viewable":false,"hidden":true},
{"name":"m_eps3_from","index":"m_eps3_from","viewable":false,"hidden":true},
{"name":"m_eps3_to","index":"m_eps3_to","viewable":false,"hidden":true},
{"name":"m_eps4_from","index":"m_eps4_from","viewable":false,"hidden":true},
{"name":"m_eps4_to","index":"m_eps4_to","viewable":false,"hidden":true},
{"name":"m_eps1_date_from","index":"m_eps1_date_from","viewable":false,"hidden":true},
{"name":"m_eps1_date_to","index":"m_eps1_date_to","viewable":false,"hidden":true},
{"name":"m_eps2_date_from","index":"m_eps2_date_from","viewable":false,"hidden":true},
{"name":"m_eps2_date_to","index":"m_eps2_date_to","viewable":false,"hidden":true},
{"name":"m_eps3_date_from","index":"m_eps3_date_from","viewable":false,"hidden":true},
{"name":"m_eps3_date_to","index":"m_eps3_date_to","viewable":false,"hidden":true},
{"name":"m_eps4_date_from","index":"m_eps4_date_from","viewable":false,"hidden":true},
{"name":"m_eps4_date_to","index":"m_eps4_date_to","viewable":false,"hidden":true},
{"name":"m_date_from","index":"m_date_from","viewable":false,"hidden":true},
{"name":"m_date_to","index":"m_date_to","viewable":false,"hidden":true}],
			
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

				var edit_url = '';
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
			var date_fields = ['m_sep_date','m_eps1_date','m_eps2_date','m_eps3_date','m_eps4_date','m_date']; 
			for(var i = 0 ; i < date_fields.length ; i++) {
				add_datepicker(date_fields[i]);
			}
        
});




function set_edit_datepicker(id) {
	var fields = ['m_sep_date','m_eps1_date','m_eps2_date','m_eps3_date','m_eps4_date','m_date']; 

	var struct = {
            dateFormat: 'yymmdd',
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
            dateFormat: 'yymmdd',
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
            dateFormat: 'yymmdd',
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
        var url = '/adminpanel/data/mri?mode=edit';
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
