<?php
//initilize the page
require_once realpath(dirname(__FILE__).'/..').'/inc/init.php';

//require UI configuration (nav, ribbon, etc.)
require_once realpath(dirname(__FILE__).'/..').'/inc/config.ui.php';

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Data Sf1";
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "jqgrid_custom.css";
$page_css[] = "ui.daterangepicker.css";
include realpath(dirname(__FILE__).'/..').'/inc/header.php';

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["data"]["sub"]["sf1"]["active"] = true;
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
                        Sf1
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

		var caption_html = "<b><i class='fa fa-table'>&nbsp;&nbsp;Data Sf1</i></b>"; 
		

         main_grid = jQuery("#jqgrid").jqGrid({
            caption : caption_html,
            sortname : 'sf1_id',
            url : "/adminpanel/data/sf1?mode=list",
            editurl : "/adminpanel/data/sf1?mode=edit",
            datatype : "json",
            shrinkToFit:<?=($this->agent->is_mobile()) ? 'false' : 'true'?>,
            height : 'auto',
            colNames : [
		'Id',
		'Name',
		'Ticker',
		'Dimension',
		'Calendardate',
		'Datekey',
		'Reportperiod',
		'Lastupdated',
		'Accoci',
		'Assets',
		'Assetsavg',
		'Assetsc',
		'Assetsnc',
		'Assetturnover',
		'Bvps',
		'Capex',
		'Cashneq',
		'Cashnequsd',
		'Cor',
		'Consolinc',
		'Currentratio',
		'De',
		'Debt',
		'Debtc',
		'Debtnc',
		'Debtusd',
		'Deferredrev',
		'Depamor',
		'Deposits',
		'Divyield',
		'Dps',
		'Ebit',
		'Ebitda',
		'Ebitdamargin',
		'Ebitdausd',
		'Ebitusd',
		'Ebt',
		'Eps',
		'Epsdil',
		'Epsusd',
		'Equity',
		'Equityavg',
		'Equityusd',
		'Ev',
		'Evebit',
		'Evebitda',
		'Fcf',
		'Fcfps',
		'Fxusd',
		'Gp',
		'Grossmargin',
		'Intangibles',
		'Intexp',
		'Invcap',
		'Invcapavg',
		'Inventory',
		'Investments',
		'Investmentsc',
		'Investmentsnc',
		'Liabilities',
		'Liabilitiesc',
		'Liabilitiesnc',
		'Marketcap',
		'Ncf',
		'Ncfbus',
		'Ncfcommon',
		'Ncfdebt',
		'Ncfdiv',
		'Ncff',
		'Ncfi',
		'Ncfinv',
		'Ncfo',
		'Ncfx',
		'Netinc',
		'Netinccmn',
		'Netinccmnusd',
		'Netincdis',
		'Netincnci',
		'Netmargin',
		'Opex',
		'Opinc',
		'Payables',
		'Payoutratio',
		'Pb',
		'Pe',
		'Pe1',
		'Ppnenet',
		'Prefdivis',
		'Price',
		'Ps',
		'Ps1',
		'Receivables',
		'Retearn',
		'Revenue',
		'Revenueusd',
		'Rnd',
		'Roa',
		'Roe',
		'Roic',
		'Ros',
		'Sbcomp',
		'Sgna',
		'Sharefactor',
		'Sharesbas',
		'Shareswa',
		'Shareswadil',
		'Sps',
		'Tangibles',
		'Taxassets',
		'Taxexp',
		'Taxliabilities',
		'Tbvps',
		'Workingcapital',
		'Created At',
		'Updated At',

		'Id_from',
		'Id_to',
		'Calendardate_from',
		'Calendardate_to',
		'Datekey_from',
		'Datekey_to',
		'Reportperiod_from',
		'Reportperiod_to',
		'Lastupdated_from',
		'Lastupdated_to',
		'Accoci_from',
		'Accoci_to',
		'Assets_from',
		'Assets_to',
		'Assetsavg_from',
		'Assetsavg_to',
		'Assetsc_from',
		'Assetsc_to',
		'Assetsnc_from',
		'Assetsnc_to',
		'Assetturnover_from',
		'Assetturnover_to',
		'Bvps_from',
		'Bvps_to',
		'Capex_from',
		'Capex_to',
		'Cashneq_from',
		'Cashneq_to',
		'Cashnequsd_from',
		'Cashnequsd_to',
		'Cor_from',
		'Cor_to',
		'Consolinc_from',
		'Consolinc_to',
		'Currentratio_from',
		'Currentratio_to',
		'De_from',
		'De_to',
		'Debt_from',
		'Debt_to',
		'Debtc_from',
		'Debtc_to',
		'Debtnc_from',
		'Debtnc_to',
		'Debtusd_from',
		'Debtusd_to',
		'Deferredrev_from',
		'Deferredrev_to',
		'Depamor_from',
		'Depamor_to',
		'Deposits_from',
		'Deposits_to',
		'Divyield_from',
		'Divyield_to',
		'Dps_from',
		'Dps_to',
		'Ebit_from',
		'Ebit_to',
		'Ebitda_from',
		'Ebitda_to',
		'Ebitdamargin_from',
		'Ebitdamargin_to',
		'Ebitdausd_from',
		'Ebitdausd_to',
		'Ebitusd_from',
		'Ebitusd_to',
		'Ebt_from',
		'Ebt_to',
		'Eps_from',
		'Eps_to',
		'Epsdil_from',
		'Epsdil_to',
		'Epsusd_from',
		'Epsusd_to',
		'Equity_from',
		'Equity_to',
		'Equityavg_from',
		'Equityavg_to',
		'Equityusd_from',
		'Equityusd_to',
		'Ev_from',
		'Ev_to',
		'Evebit_from',
		'Evebit_to',
		'Evebitda_from',
		'Evebitda_to',
		'Fcf_from',
		'Fcf_to',
		'Fcfps_from',
		'Fcfps_to',
		'Fxusd_from',
		'Fxusd_to',
		'Gp_from',
		'Gp_to',
		'Grossmargin_from',
		'Grossmargin_to',
		'Intangibles_from',
		'Intangibles_to',
		'Intexp_from',
		'Intexp_to',
		'Invcap_from',
		'Invcap_to',
		'Invcapavg_from',
		'Invcapavg_to',
		'Inventory_from',
		'Inventory_to',
		'Investments_from',
		'Investments_to',
		'Investmentsc_from',
		'Investmentsc_to',
		'Investmentsnc_from',
		'Investmentsnc_to',
		'Liabilities_from',
		'Liabilities_to',
		'Liabilitiesc_from',
		'Liabilitiesc_to',
		'Liabilitiesnc_from',
		'Liabilitiesnc_to',
		'Marketcap_from',
		'Marketcap_to',
		'Ncf_from',
		'Ncf_to',
		'Ncfbus_from',
		'Ncfbus_to',
		'Ncfcommon_from',
		'Ncfcommon_to',
		'Ncfdebt_from',
		'Ncfdebt_to',
		'Ncfdiv_from',
		'Ncfdiv_to',
		'Ncff_from',
		'Ncff_to',
		'Ncfi_from',
		'Ncfi_to',
		'Ncfinv_from',
		'Ncfinv_to',
		'Ncfo_from',
		'Ncfo_to',
		'Ncfx_from',
		'Ncfx_to',
		'Netinc_from',
		'Netinc_to',
		'Netinccmn_from',
		'Netinccmn_to',
		'Netinccmnusd_from',
		'Netinccmnusd_to',
		'Netincdis_from',
		'Netincdis_to',
		'Netincnci_from',
		'Netincnci_to',
		'Netmargin_from',
		'Netmargin_to',
		'Opex_from',
		'Opex_to',
		'Opinc_from',
		'Opinc_to',
		'Payables_from',
		'Payables_to',
		'Payoutratio_from',
		'Payoutratio_to',
		'Pb_from',
		'Pb_to',
		'Pe_from',
		'Pe_to',
		'Pe1_from',
		'Pe1_to',
		'Ppnenet_from',
		'Ppnenet_to',
		'Prefdivis_from',
		'Prefdivis_to',
		'Price_from',
		'Price_to',
		'Ps_from',
		'Ps_to',
		'Ps1_from',
		'Ps1_to',
		'Receivables_from',
		'Receivables_to',
		'Retearn_from',
		'Retearn_to',
		'Revenue_from',
		'Revenue_to',
		'Revenueusd_from',
		'Revenueusd_to',
		'Rnd_from',
		'Rnd_to',
		'Roa_from',
		'Roa_to',
		'Roe_from',
		'Roe_to',
		'Roic_from',
		'Roic_to',
		'Ros_from',
		'Ros_to',
		'Sbcomp_from',
		'Sbcomp_to',
		'Sgna_from',
		'Sgna_to',
		'Sharefactor_from',
		'Sharefactor_to',
		'Sharesbas_from',
		'Sharesbas_to',
		'Shareswa_from',
		'Shareswa_to',
		'Shareswadil_from',
		'Shareswadil_to',
		'Sps_from',
		'Sps_to',
		'Tangibles_from',
		'Tangibles_to',
		'Taxassets_from',
		'Taxassets_to',
		'Taxexp_from',
		'Taxexp_to',
		'Taxliabilities_from',
		'Taxliabilities_to',
		'Tbvps_from',
		'Tbvps_to',
		'Workingcapital_from',
		'Workingcapital_to',
		'Created At_from',
		'Created At_to',
		'Updated At_from',
		'Updated At_to'
		],
		  
			jsonReader : {
			  id: "sf1_id",
		   	},
            colModel : [
	    	{"name":"sf1_id","index":"sf1_id","align":"center","sortable":true,"search":true,"stype":"between","editable":false},

	    	{"name":"tkr_name","index":"tkr_name","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["cn"]},"editable":false},
	    	{"name":"sf1_ticker","index":"sf1_ticker","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false},
		{"name":"sf1_dimension","index":"sf1_dimension","align":"center","sortable":true,"search":true,"searchoptions":{"sopt":["eq"]},"editable":false},
		{"name":"sf1_calendardate","index":"sf1_calendardate","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
		{"name":"sf1_datekey","index":"sf1_datekey","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
		{"name":"sf1_reportperiod","index":"sf1_reportperiod","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},


		{'hidden':true,"name":"sf1_lastupdated","index":"sf1_lastupdated","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
		{'hidden':true,"name":"sf1_accoci","index":"sf1_accoci","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_assets","index":"sf1_assets","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_assetsavg","index":"sf1_assetsavg","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_assetsc","index":"sf1_assetsc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_assetsnc","index":"sf1_assetsnc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_assetturnover","index":"sf1_assetturnover","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_bvps","index":"sf1_bvps","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_capex","index":"sf1_capex","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_cashneq","index":"sf1_cashneq","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_cashnequsd","index":"sf1_cashnequsd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_cor","index":"sf1_cor","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_consolinc","index":"sf1_consolinc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_currentratio","index":"sf1_currentratio","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_de","index":"sf1_de","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_debt","index":"sf1_debt","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_debtc","index":"sf1_debtc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_debtnc","index":"sf1_debtnc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_debtusd","index":"sf1_debtusd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_deferredrev","index":"sf1_deferredrev","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_depamor","index":"sf1_depamor","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_deposits","index":"sf1_deposits","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_divyield","index":"sf1_divyield","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_dps","index":"sf1_dps","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ebit","index":"sf1_ebit","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ebitda","index":"sf1_ebitda","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ebitdamargin","index":"sf1_ebitdamargin","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ebitdausd","index":"sf1_ebitdausd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ebitusd","index":"sf1_ebitusd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ebt","index":"sf1_ebt","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_eps","index":"sf1_eps","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_epsdil","index":"sf1_epsdil","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_epsusd","index":"sf1_epsusd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_equity","index":"sf1_equity","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_equityavg","index":"sf1_equityavg","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_equityusd","index":"sf1_equityusd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ev","index":"sf1_ev","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_evebit","index":"sf1_evebit","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_evebitda","index":"sf1_evebitda","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_fcf","index":"sf1_fcf","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_fcfps","index":"sf1_fcfps","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_fxusd","index":"sf1_fxusd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_gp","index":"sf1_gp","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_grossmargin","index":"sf1_grossmargin","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_intangibles","index":"sf1_intangibles","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_intexp","index":"sf1_intexp","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_invcap","index":"sf1_invcap","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_invcapavg","index":"sf1_invcapavg","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_inventory","index":"sf1_inventory","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_investments","index":"sf1_investments","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_investmentsc","index":"sf1_investmentsc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_investmentsnc","index":"sf1_investmentsnc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_liabilities","index":"sf1_liabilities","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_liabilitiesc","index":"sf1_liabilitiesc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_liabilitiesnc","index":"sf1_liabilitiesnc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_marketcap","index":"sf1_marketcap","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ncf","index":"sf1_ncf","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_ncfbus","index":"sf1_ncfbus","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ncfcommon","index":"sf1_ncfcommon","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_ncfdebt","index":"sf1_ncfdebt","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ncfdiv","index":"sf1_ncfdiv","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_ncff","index":"sf1_ncff","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ncfi","index":"sf1_ncfi","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_ncfinv","index":"sf1_ncfinv","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ncfo","index":"sf1_ncfo","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ncfx","index":"sf1_ncfx","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_netinc","index":"sf1_netinc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_netinccmn","index":"sf1_netinccmn","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_netinccmnusd","index":"sf1_netinccmnusd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_netincdis","index":"sf1_netincdis","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_netincnci","index":"sf1_netincnci","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_netmargin","index":"sf1_netmargin","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_opex","index":"sf1_opex","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_opinc","index":"sf1_opinc","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_payables","index":"sf1_payables","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_payoutratio","index":"sf1_payoutratio","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_pb","index":"sf1_pb","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_pe","index":"sf1_pe","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_pe1","index":"sf1_pe1","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ppnenet","index":"sf1_ppnenet","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_prefdivis","index":"sf1_prefdivis","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_price","index":"sf1_price","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ps","index":"sf1_ps","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ps1","index":"sf1_ps1","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_receivables","index":"sf1_receivables","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_retearn","index":"sf1_retearn","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_revenue","index":"sf1_revenue","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_revenueusd","index":"sf1_revenueusd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_rnd","index":"sf1_rnd","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_roa","index":"sf1_roa","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_roe","index":"sf1_roe","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_roic","index":"sf1_roic","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_ros","index":"sf1_ros","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_sbcomp","index":"sf1_sbcomp","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_sgna","index":"sf1_sgna","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_sharefactor","index":"sf1_sharefactor","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_sharesbas","index":"sf1_sharesbas","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_shareswa","index":"sf1_shareswa","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_shareswadil","index":"sf1_shareswadil","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_sps","index":"sf1_sps","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_tangibles","index":"sf1_tangibles","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_taxassets","index":"sf1_taxassets","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{"name":"sf1_taxexp","index":"sf1_taxexp","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_taxliabilities","index":"sf1_taxliabilities","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_tbvps","index":"sf1_tbvps","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_workingcapital","index":"sf1_workingcapital","align":"center","sortable":true,"search":true,"stype":"between","editable":false},
		{'hidden':true,"name":"sf1_created_at","index":"sf1_created_at","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},
		{'hidden':true,"name":"sf1_updated_at","index":"sf1_updated_at","align":"center","sortable":true,"search":true,"stype":"between","attr":"onchange=\"jqgrid.triggerToolbar();\"","editable":false},




		{"name":"sf1_id_from","index":"sf1_id_from","viewable":false,"hidden":true},
		{"name":"sf1_id_to","index":"sf1_id_to","viewable":false,"hidden":true},
		{"name":"sf1_calendardate_from","index":"sf1_calendardate_from","viewable":false,"hidden":true},
		{"name":"sf1_calendardate_to","index":"sf1_calendardate_to","viewable":false,"hidden":true},
		{"name":"sf1_datekey_from","index":"sf1_datekey_from","viewable":false,"hidden":true},
		{"name":"sf1_datekey_to","index":"sf1_datekey_to","viewable":false,"hidden":true},
		{"name":"sf1_reportperiod_from","index":"sf1_reportperiod_from","viewable":false,"hidden":true},
		{"name":"sf1_reportperiod_to","index":"sf1_reportperiod_to","viewable":false,"hidden":true},
		{"name":"sf1_lastupdated_from","index":"sf1_lastupdated_from","viewable":false,"hidden":true},
		{"name":"sf1_lastupdated_to","index":"sf1_lastupdated_to","viewable":false,"hidden":true},
		{"name":"sf1_accoci_from","index":"sf1_accoci_from","viewable":false,"hidden":true},
		{"name":"sf1_accoci_to","index":"sf1_accoci_to","viewable":false,"hidden":true},
		{"name":"sf1_assets_from","index":"sf1_assets_from","viewable":false,"hidden":true},
		{"name":"sf1_assets_to","index":"sf1_assets_to","viewable":false,"hidden":true},
		{"name":"sf1_assetsavg_from","index":"sf1_assetsavg_from","viewable":false,"hidden":true},
		{"name":"sf1_assetsavg_to","index":"sf1_assetsavg_to","viewable":false,"hidden":true},
		{"name":"sf1_assetsc_from","index":"sf1_assetsc_from","viewable":false,"hidden":true},
		{"name":"sf1_assetsc_to","index":"sf1_assetsc_to","viewable":false,"hidden":true},
		{"name":"sf1_assetsnc_from","index":"sf1_assetsnc_from","viewable":false,"hidden":true},
		{"name":"sf1_assetsnc_to","index":"sf1_assetsnc_to","viewable":false,"hidden":true},
		{"name":"sf1_assetturnover_from","index":"sf1_assetturnover_from","viewable":false,"hidden":true},
		{"name":"sf1_assetturnover_to","index":"sf1_assetturnover_to","viewable":false,"hidden":true},
		{"name":"sf1_bvps_from","index":"sf1_bvps_from","viewable":false,"hidden":true},
		{"name":"sf1_bvps_to","index":"sf1_bvps_to","viewable":false,"hidden":true},
		{"name":"sf1_capex_from","index":"sf1_capex_from","viewable":false,"hidden":true},
		{"name":"sf1_capex_to","index":"sf1_capex_to","viewable":false,"hidden":true},
		{"name":"sf1_cashneq_from","index":"sf1_cashneq_from","viewable":false,"hidden":true},
		{"name":"sf1_cashneq_to","index":"sf1_cashneq_to","viewable":false,"hidden":true},
		{"name":"sf1_cashnequsd_from","index":"sf1_cashnequsd_from","viewable":false,"hidden":true},
		{"name":"sf1_cashnequsd_to","index":"sf1_cashnequsd_to","viewable":false,"hidden":true},
		{"name":"sf1_cor_from","index":"sf1_cor_from","viewable":false,"hidden":true},
		{"name":"sf1_cor_to","index":"sf1_cor_to","viewable":false,"hidden":true},
		{"name":"sf1_consolinc_from","index":"sf1_consolinc_from","viewable":false,"hidden":true},
		{"name":"sf1_consolinc_to","index":"sf1_consolinc_to","viewable":false,"hidden":true},
		{"name":"sf1_currentratio_from","index":"sf1_currentratio_from","viewable":false,"hidden":true},
		{"name":"sf1_currentratio_to","index":"sf1_currentratio_to","viewable":false,"hidden":true},
		{"name":"sf1_de_from","index":"sf1_de_from","viewable":false,"hidden":true},
		{"name":"sf1_de_to","index":"sf1_de_to","viewable":false,"hidden":true},
		{"name":"sf1_debt_from","index":"sf1_debt_from","viewable":false,"hidden":true},
		{"name":"sf1_debt_to","index":"sf1_debt_to","viewable":false,"hidden":true},
		{"name":"sf1_debtc_from","index":"sf1_debtc_from","viewable":false,"hidden":true},
		{"name":"sf1_debtc_to","index":"sf1_debtc_to","viewable":false,"hidden":true},
		{"name":"sf1_debtnc_from","index":"sf1_debtnc_from","viewable":false,"hidden":true},
		{"name":"sf1_debtnc_to","index":"sf1_debtnc_to","viewable":false,"hidden":true},
		{"name":"sf1_debtusd_from","index":"sf1_debtusd_from","viewable":false,"hidden":true},
		{"name":"sf1_debtusd_to","index":"sf1_debtusd_to","viewable":false,"hidden":true},
		{"name":"sf1_deferredrev_from","index":"sf1_deferredrev_from","viewable":false,"hidden":true},
		{"name":"sf1_deferredrev_to","index":"sf1_deferredrev_to","viewable":false,"hidden":true},
		{"name":"sf1_depamor_from","index":"sf1_depamor_from","viewable":false,"hidden":true},
		{"name":"sf1_depamor_to","index":"sf1_depamor_to","viewable":false,"hidden":true},
		{"name":"sf1_deposits_from","index":"sf1_deposits_from","viewable":false,"hidden":true},
		{"name":"sf1_deposits_to","index":"sf1_deposits_to","viewable":false,"hidden":true},
		{"name":"sf1_divyield_from","index":"sf1_divyield_from","viewable":false,"hidden":true},
		{"name":"sf1_divyield_to","index":"sf1_divyield_to","viewable":false,"hidden":true},
		{"name":"sf1_dps_from","index":"sf1_dps_from","viewable":false,"hidden":true},
		{"name":"sf1_dps_to","index":"sf1_dps_to","viewable":false,"hidden":true},
		{"name":"sf1_ebit_from","index":"sf1_ebit_from","viewable":false,"hidden":true},
		{"name":"sf1_ebit_to","index":"sf1_ebit_to","viewable":false,"hidden":true},
		{"name":"sf1_ebitda_from","index":"sf1_ebitda_from","viewable":false,"hidden":true},
		{"name":"sf1_ebitda_to","index":"sf1_ebitda_to","viewable":false,"hidden":true},
		{"name":"sf1_ebitdamargin_from","index":"sf1_ebitdamargin_from","viewable":false,"hidden":true},
		{"name":"sf1_ebitdamargin_to","index":"sf1_ebitdamargin_to","viewable":false,"hidden":true},
		{"name":"sf1_ebitdausd_from","index":"sf1_ebitdausd_from","viewable":false,"hidden":true},
		{"name":"sf1_ebitdausd_to","index":"sf1_ebitdausd_to","viewable":false,"hidden":true},
		{"name":"sf1_ebitusd_from","index":"sf1_ebitusd_from","viewable":false,"hidden":true},
		{"name":"sf1_ebitusd_to","index":"sf1_ebitusd_to","viewable":false,"hidden":true},
		{"name":"sf1_ebt_from","index":"sf1_ebt_from","viewable":false,"hidden":true},
		{"name":"sf1_ebt_to","index":"sf1_ebt_to","viewable":false,"hidden":true},
		{"name":"sf1_eps_from","index":"sf1_eps_from","viewable":false,"hidden":true},
		{"name":"sf1_eps_to","index":"sf1_eps_to","viewable":false,"hidden":true},
		{"name":"sf1_epsdil_from","index":"sf1_epsdil_from","viewable":false,"hidden":true},
		{"name":"sf1_epsdil_to","index":"sf1_epsdil_to","viewable":false,"hidden":true},
		{"name":"sf1_epsusd_from","index":"sf1_epsusd_from","viewable":false,"hidden":true},
		{"name":"sf1_epsusd_to","index":"sf1_epsusd_to","viewable":false,"hidden":true},
		{"name":"sf1_equity_from","index":"sf1_equity_from","viewable":false,"hidden":true},
		{"name":"sf1_equity_to","index":"sf1_equity_to","viewable":false,"hidden":true},
		{"name":"sf1_equityavg_from","index":"sf1_equityavg_from","viewable":false,"hidden":true},
		{"name":"sf1_equityavg_to","index":"sf1_equityavg_to","viewable":false,"hidden":true},
		{"name":"sf1_equityusd_from","index":"sf1_equityusd_from","viewable":false,"hidden":true},
		{"name":"sf1_equityusd_to","index":"sf1_equityusd_to","viewable":false,"hidden":true},
		{"name":"sf1_ev_from","index":"sf1_ev_from","viewable":false,"hidden":true},
		{"name":"sf1_ev_to","index":"sf1_ev_to","viewable":false,"hidden":true},
		{"name":"sf1_evebit_from","index":"sf1_evebit_from","viewable":false,"hidden":true},
		{"name":"sf1_evebit_to","index":"sf1_evebit_to","viewable":false,"hidden":true},
		{"name":"sf1_evebitda_from","index":"sf1_evebitda_from","viewable":false,"hidden":true},
		{"name":"sf1_evebitda_to","index":"sf1_evebitda_to","viewable":false,"hidden":true},
		{"name":"sf1_fcf_from","index":"sf1_fcf_from","viewable":false,"hidden":true},
		{"name":"sf1_fcf_to","index":"sf1_fcf_to","viewable":false,"hidden":true},
		{"name":"sf1_fcfps_from","index":"sf1_fcfps_from","viewable":false,"hidden":true},
		{"name":"sf1_fcfps_to","index":"sf1_fcfps_to","viewable":false,"hidden":true},
		{"name":"sf1_fxusd_from","index":"sf1_fxusd_from","viewable":false,"hidden":true},
		{"name":"sf1_fxusd_to","index":"sf1_fxusd_to","viewable":false,"hidden":true},
		{"name":"sf1_gp_from","index":"sf1_gp_from","viewable":false,"hidden":true},
		{"name":"sf1_gp_to","index":"sf1_gp_to","viewable":false,"hidden":true},
		{"name":"sf1_grossmargin_from","index":"sf1_grossmargin_from","viewable":false,"hidden":true},
		{"name":"sf1_grossmargin_to","index":"sf1_grossmargin_to","viewable":false,"hidden":true},
		{"name":"sf1_intangibles_from","index":"sf1_intangibles_from","viewable":false,"hidden":true},
		{"name":"sf1_intangibles_to","index":"sf1_intangibles_to","viewable":false,"hidden":true},
		{"name":"sf1_intexp_from","index":"sf1_intexp_from","viewable":false,"hidden":true},
		{"name":"sf1_intexp_to","index":"sf1_intexp_to","viewable":false,"hidden":true},
		{"name":"sf1_invcap_from","index":"sf1_invcap_from","viewable":false,"hidden":true},
		{"name":"sf1_invcap_to","index":"sf1_invcap_to","viewable":false,"hidden":true},
		{"name":"sf1_invcapavg_from","index":"sf1_invcapavg_from","viewable":false,"hidden":true},
		{"name":"sf1_invcapavg_to","index":"sf1_invcapavg_to","viewable":false,"hidden":true},
		{"name":"sf1_inventory_from","index":"sf1_inventory_from","viewable":false,"hidden":true},
		{"name":"sf1_inventory_to","index":"sf1_inventory_to","viewable":false,"hidden":true},
		{"name":"sf1_investments_from","index":"sf1_investments_from","viewable":false,"hidden":true},
		{"name":"sf1_investments_to","index":"sf1_investments_to","viewable":false,"hidden":true},
		{"name":"sf1_investmentsc_from","index":"sf1_investmentsc_from","viewable":false,"hidden":true},
		{"name":"sf1_investmentsc_to","index":"sf1_investmentsc_to","viewable":false,"hidden":true},
		{"name":"sf1_investmentsnc_from","index":"sf1_investmentsnc_from","viewable":false,"hidden":true},
		{"name":"sf1_investmentsnc_to","index":"sf1_investmentsnc_to","viewable":false,"hidden":true},
		{"name":"sf1_liabilities_from","index":"sf1_liabilities_from","viewable":false,"hidden":true},
		{"name":"sf1_liabilities_to","index":"sf1_liabilities_to","viewable":false,"hidden":true},
		{"name":"sf1_liabilitiesc_from","index":"sf1_liabilitiesc_from","viewable":false,"hidden":true},
		{"name":"sf1_liabilitiesc_to","index":"sf1_liabilitiesc_to","viewable":false,"hidden":true},
		{"name":"sf1_liabilitiesnc_from","index":"sf1_liabilitiesnc_from","viewable":false,"hidden":true},
		{"name":"sf1_liabilitiesnc_to","index":"sf1_liabilitiesnc_to","viewable":false,"hidden":true},
		{"name":"sf1_marketcap_from","index":"sf1_marketcap_from","viewable":false,"hidden":true},
		{"name":"sf1_marketcap_to","index":"sf1_marketcap_to","viewable":false,"hidden":true},
		{"name":"sf1_ncf_from","index":"sf1_ncf_from","viewable":false,"hidden":true},
		{"name":"sf1_ncf_to","index":"sf1_ncf_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfbus_from","index":"sf1_ncfbus_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfbus_to","index":"sf1_ncfbus_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfcommon_from","index":"sf1_ncfcommon_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfcommon_to","index":"sf1_ncfcommon_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfdebt_from","index":"sf1_ncfdebt_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfdebt_to","index":"sf1_ncfdebt_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfdiv_from","index":"sf1_ncfdiv_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfdiv_to","index":"sf1_ncfdiv_to","viewable":false,"hidden":true},
		{"name":"sf1_ncff_from","index":"sf1_ncff_from","viewable":false,"hidden":true},
		{"name":"sf1_ncff_to","index":"sf1_ncff_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfi_from","index":"sf1_ncfi_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfi_to","index":"sf1_ncfi_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfinv_from","index":"sf1_ncfinv_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfinv_to","index":"sf1_ncfinv_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfo_from","index":"sf1_ncfo_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfo_to","index":"sf1_ncfo_to","viewable":false,"hidden":true},
		{"name":"sf1_ncfx_from","index":"sf1_ncfx_from","viewable":false,"hidden":true},
		{"name":"sf1_ncfx_to","index":"sf1_ncfx_to","viewable":false,"hidden":true},
		{"name":"sf1_netinc_from","index":"sf1_netinc_from","viewable":false,"hidden":true},
		{"name":"sf1_netinc_to","index":"sf1_netinc_to","viewable":false,"hidden":true},
		{"name":"sf1_netinccmn_from","index":"sf1_netinccmn_from","viewable":false,"hidden":true},
		{"name":"sf1_netinccmn_to","index":"sf1_netinccmn_to","viewable":false,"hidden":true},
		{"name":"sf1_netinccmnusd_from","index":"sf1_netinccmnusd_from","viewable":false,"hidden":true},
		{"name":"sf1_netinccmnusd_to","index":"sf1_netinccmnusd_to","viewable":false,"hidden":true},
		{"name":"sf1_netincdis_from","index":"sf1_netincdis_from","viewable":false,"hidden":true},
		{"name":"sf1_netincdis_to","index":"sf1_netincdis_to","viewable":false,"hidden":true},
		{"name":"sf1_netincnci_from","index":"sf1_netincnci_from","viewable":false,"hidden":true},
		{"name":"sf1_netincnci_to","index":"sf1_netincnci_to","viewable":false,"hidden":true},
		{"name":"sf1_netmargin_from","index":"sf1_netmargin_from","viewable":false,"hidden":true},
		{"name":"sf1_netmargin_to","index":"sf1_netmargin_to","viewable":false,"hidden":true},
		{"name":"sf1_opex_from","index":"sf1_opex_from","viewable":false,"hidden":true},
		{"name":"sf1_opex_to","index":"sf1_opex_to","viewable":false,"hidden":true},
		{"name":"sf1_opinc_from","index":"sf1_opinc_from","viewable":false,"hidden":true},
		{"name":"sf1_opinc_to","index":"sf1_opinc_to","viewable":false,"hidden":true},
		{"name":"sf1_payables_from","index":"sf1_payables_from","viewable":false,"hidden":true},
		{"name":"sf1_payables_to","index":"sf1_payables_to","viewable":false,"hidden":true},
		{"name":"sf1_payoutratio_from","index":"sf1_payoutratio_from","viewable":false,"hidden":true},
		{"name":"sf1_payoutratio_to","index":"sf1_payoutratio_to","viewable":false,"hidden":true},
		{"name":"sf1_pb_from","index":"sf1_pb_from","viewable":false,"hidden":true},
		{"name":"sf1_pb_to","index":"sf1_pb_to","viewable":false,"hidden":true},
		{"name":"sf1_pe_from","index":"sf1_pe_from","viewable":false,"hidden":true},
		{"name":"sf1_pe_to","index":"sf1_pe_to","viewable":false,"hidden":true},
		{"name":"sf1_pe1_from","index":"sf1_pe1_from","viewable":false,"hidden":true},
		{"name":"sf1_pe1_to","index":"sf1_pe1_to","viewable":false,"hidden":true},
		{"name":"sf1_ppnenet_from","index":"sf1_ppnenet_from","viewable":false,"hidden":true},
		{"name":"sf1_ppnenet_to","index":"sf1_ppnenet_to","viewable":false,"hidden":true},
		{"name":"sf1_prefdivis_from","index":"sf1_prefdivis_from","viewable":false,"hidden":true},
		{"name":"sf1_prefdivis_to","index":"sf1_prefdivis_to","viewable":false,"hidden":true},
		{"name":"sf1_price_from","index":"sf1_price_from","viewable":false,"hidden":true},
		{"name":"sf1_price_to","index":"sf1_price_to","viewable":false,"hidden":true},
		{"name":"sf1_ps_from","index":"sf1_ps_from","viewable":false,"hidden":true},
		{"name":"sf1_ps_to","index":"sf1_ps_to","viewable":false,"hidden":true},
		{"name":"sf1_ps1_from","index":"sf1_ps1_from","viewable":false,"hidden":true},
		{"name":"sf1_ps1_to","index":"sf1_ps1_to","viewable":false,"hidden":true},
		{"name":"sf1_receivables_from","index":"sf1_receivables_from","viewable":false,"hidden":true},
		{"name":"sf1_receivables_to","index":"sf1_receivables_to","viewable":false,"hidden":true},
		{"name":"sf1_retearn_from","index":"sf1_retearn_from","viewable":false,"hidden":true},
		{"name":"sf1_retearn_to","index":"sf1_retearn_to","viewable":false,"hidden":true},
		{"name":"sf1_revenue_from","index":"sf1_revenue_from","viewable":false,"hidden":true},
		{"name":"sf1_revenue_to","index":"sf1_revenue_to","viewable":false,"hidden":true},
		{"name":"sf1_revenueusd_from","index":"sf1_revenueusd_from","viewable":false,"hidden":true},
		{"name":"sf1_revenueusd_to","index":"sf1_revenueusd_to","viewable":false,"hidden":true},
		{"name":"sf1_rnd_from","index":"sf1_rnd_from","viewable":false,"hidden":true},
		{"name":"sf1_rnd_to","index":"sf1_rnd_to","viewable":false,"hidden":true},
		{"name":"sf1_roa_from","index":"sf1_roa_from","viewable":false,"hidden":true},
		{"name":"sf1_roa_to","index":"sf1_roa_to","viewable":false,"hidden":true},
		{"name":"sf1_roe_from","index":"sf1_roe_from","viewable":false,"hidden":true},
		{"name":"sf1_roe_to","index":"sf1_roe_to","viewable":false,"hidden":true},
		{"name":"sf1_roic_from","index":"sf1_roic_from","viewable":false,"hidden":true},
		{"name":"sf1_roic_to","index":"sf1_roic_to","viewable":false,"hidden":true},
		{"name":"sf1_ros_from","index":"sf1_ros_from","viewable":false,"hidden":true},
		{"name":"sf1_ros_to","index":"sf1_ros_to","viewable":false,"hidden":true},
		{"name":"sf1_sbcomp_from","index":"sf1_sbcomp_from","viewable":false,"hidden":true},
		{"name":"sf1_sbcomp_to","index":"sf1_sbcomp_to","viewable":false,"hidden":true},
		{"name":"sf1_sgna_from","index":"sf1_sgna_from","viewable":false,"hidden":true},
		{"name":"sf1_sgna_to","index":"sf1_sgna_to","viewable":false,"hidden":true},
		{"name":"sf1_sharefactor_from","index":"sf1_sharefactor_from","viewable":false,"hidden":true},
		{"name":"sf1_sharefactor_to","index":"sf1_sharefactor_to","viewable":false,"hidden":true},
		{"name":"sf1_sharesbas_from","index":"sf1_sharesbas_from","viewable":false,"hidden":true},
		{"name":"sf1_sharesbas_to","index":"sf1_sharesbas_to","viewable":false,"hidden":true},
		{"name":"sf1_shareswa_from","index":"sf1_shareswa_from","viewable":false,"hidden":true},
		{"name":"sf1_shareswa_to","index":"sf1_shareswa_to","viewable":false,"hidden":true},
		{"name":"sf1_shareswadil_from","index":"sf1_shareswadil_from","viewable":false,"hidden":true},
		{"name":"sf1_shareswadil_to","index":"sf1_shareswadil_to","viewable":false,"hidden":true},
		{"name":"sf1_sps_from","index":"sf1_sps_from","viewable":false,"hidden":true},
		{"name":"sf1_sps_to","index":"sf1_sps_to","viewable":false,"hidden":true},
		{"name":"sf1_tangibles_from","index":"sf1_tangibles_from","viewable":false,"hidden":true},
		{"name":"sf1_tangibles_to","index":"sf1_tangibles_to","viewable":false,"hidden":true},
		{"name":"sf1_taxassets_from","index":"sf1_taxassets_from","viewable":false,"hidden":true},
		{"name":"sf1_taxassets_to","index":"sf1_taxassets_to","viewable":false,"hidden":true},
		{"name":"sf1_taxexp_from","index":"sf1_taxexp_from","viewable":false,"hidden":true},
		{"name":"sf1_taxexp_to","index":"sf1_taxexp_to","viewable":false,"hidden":true},
		{"name":"sf1_taxliabilities_from","index":"sf1_taxliabilities_from","viewable":false,"hidden":true},
		{"name":"sf1_taxliabilities_to","index":"sf1_taxliabilities_to","viewable":false,"hidden":true},
		{"name":"sf1_tbvps_from","index":"sf1_tbvps_from","viewable":false,"hidden":true},
		{"name":"sf1_tbvps_to","index":"sf1_tbvps_to","viewable":false,"hidden":true},
		{"name":"sf1_workingcapital_from","index":"sf1_workingcapital_from","viewable":false,"hidden":true},
		{"name":"sf1_workingcapital_to","index":"sf1_workingcapital_to","viewable":false,"hidden":true},
		{"name":"sf1_created_at_from","index":"sf1_created_at_from","viewable":false,"hidden":true},
		{"name":"sf1_created_at_to","index":"sf1_created_at_to","viewable":false,"hidden":true},
		{"name":"sf1_updated_at_from","index":"sf1_updated_at_from","viewable":false,"hidden":true},
		{"name":"sf1_updated_at_to","index":"sf1_updated_at_to","viewable":false,"hidden":true}],
			
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
				$('#jqgrid tbody tr').each(function() {
					var td_vendor = $(this).children('td:eq(3)');

					if(td_vendor.html().length <= 0) return;
					td_vendor.css({fontSize:'1.1em', fontWeight : 'bold', color:'blue'});
				});

				////////////// checkbox td 자체를 없애는 방식. //////////////////

				$('th#jqgrid_cb').hide().parent().next().find('th:first').hide();
				$('table.ui-jqgrid-btable tr').find('td:first').hide();

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

				var link_url = '/adminpanel/data/sf1_detail/'+id;
				switch(click_field) {
					case 'jqgrid_sf1_ticker' :
						link_url = '/main/finstate/'+cell_value;
						break;
				}

				if(link_url.length > 0) {
					window.open(link_url);
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
			var date_fields = ['sf1_calendardate','sf1_datekey','sf1_reportperiod','sf1_lastupdated','sf1_created_at','sf1_updated_at']; 
			for(var i = 0 ; i < date_fields.length ; i++) {
				add_datepicker(date_fields[i]);
			}
        
});




function set_edit_datepicker(id) {
	var fields = ['sf1_calendardate','sf1_datekey','sf1_reportperiod','sf1_lastupdated','sf1_created_at','sf1_updated_at']; 

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
        var url = '/adminpanel/data/sf1?mode=edit';
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
