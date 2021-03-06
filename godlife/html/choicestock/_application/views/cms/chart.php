<script type="text/javascript">
	function CmsGaugeChart(obj_id, value) {
        Highcharts.chart(obj_id, {
            chart: {
                type: 'gauge',
                plotBackgroundColor: {
                    stops: [
                        [0, '#FFF4C6'],
                        [0.3, '#FFFFFF'],
                        [1, '#FFF4C6']
                    ]
                },
                plotBackgroundImage: null,
                height: 120
            },

            title: {
                text: ''
            },

            pane: [{
                startAngle: -90,
                endAngle: 90,
                background: null,
                center: ['10%', '100%'],
                size: 80
            }],

            exporting: {
                enabled: false
            },

            credits: {
                enabled: false,
            },

            tooltip: {
                enabled: false
            },

            yAxis: [{
                min: 0,
                max: 6,
                labels: {
                    enabled: false,
                },
                plotBands: [{
                    from: 0,
                    to: 2,
                    color: '#B6E8CF',
                    innerRadius: '100%',
                    outerRadius: '150%'
                }, {
                    from: 2,
                    to: 4,
                    color: '#00CCBD',
                    innerRadius: '100%',
                    outerRadius: '150%'
                }, {
                    from: 4,
                    to: 6,
                    color: '#476E8B',
                    innerRadius: '100%',
                    outerRadius: '150%'
                }],
            }],

            plotOptions: {
                gauge: {
                    dataLabels: {
                        enabled: false
                    },
                    dial: {
                        radius: '150%',
                        backgroundColor: '#FFD090',
                        baseWidth: 6,
                        topWidth: 6,
                        rearLength: '0'
                    }
                }
            },
            series: [{
				name: '',
				data: value, //?????? 1 ?????????, 3 ?????????, 5 1 ?????????
				yAxis: 0
            }]
        });
    }
	function CmsSpiderChartMini(obj_id, value) {

        Highcharts.chart(obj_id, {
            chart: {
                polar: true,
                type: 'area',
                renderTo: obj_id,
                backgroundColor: {
                    // linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
                    stops: [
                        [0, '#ffffff'],
                        [1, '#ffffff']
                    ]
                },
                style: {
                    fontFamily: "'Lato', 'Noto Sans KR'"
                },
                width: 240,
                height: 240,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            title: {
                text: null
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            pane: {
                size: '100%'
            },

            xAxis: {
                style: {
                    fontSize: '0',
                },
                categories: ['', '', '', '', ''],
                tickmarkPlacement: 'on',
                lineWidth: 0,
            },

            yAxis: {
                style: {
                    fontSize: '0',
                },
                gridLineInterpolation: 'polygon',
                lineWidth: 0,
                min: 1,
                max: 5,
                labels: {
                    enabled: false
                } 
            },

            exporting: {
                enabled: false
            },
    
            legend: {
                enabled: false
            },
    
            credits: {
                enabled: false
            },

            series: [{
                name: '',
			    data: value,
                pointPlacement: 'on',
                
            }],

            plotOptions: {
                series: {
                    enableMouseTracking: false,
                    lineWidth: 1,
                    fillOpacity: 0.3,
                    marker: {
                        enabled: false,
                    }
                }
            },

        })
    }

</script>
  
  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading sctop_00">???????????? <strong><?=$ticker['tkr_ticker']?></strong></div>
      <div class="list-group list-group-flush">
        <a href="#" class="sctop_01 list-group-item list-group-item-action bg-light">C01. ??????-?????????(1??????)</a>
        <a href="#" class="sctop_02 list-group-item list-group-item-action bg-light">C02. ??????-?????????(6??????)/??????</a>
        <a href="#" class="sctop_03 list-group-item list-group-item-action bg-light">C03. ????????????-??????/??????</a>
        <a href="#" class="sctop_04 list-group-item list-group-item-action bg-light">C04. ???????????? > ????????????-?????? / ??????</a>
        <a href="#" class="sctop_05 list-group-item list-group-item-action bg-light">C05. ???????????? > ???????????????-?????? / ??????</a>
        <!--<a href="#" class="sctop_05_01 list-group-item list-group-item-action bg-light">C05-A (??????)???????????? > ???????????????-?????? / ??????</a>-->
        <a href="#" class="sctop_06 list-group-item list-group-item-action bg-light">C06. ???????????? > ???????????????-?????? / ??????</a>
        <!--<a href="#" class="sctop_06_01 list-group-item list-group-item-action bg-light">C06-A. (??????)???????????? > ???????????????-?????? / ??????</a>-->
        <a href="#" class="sctop_07 list-group-item list-group-item-action bg-light">C07. ???????????? > ???????????????-?????? / ??????</a>
        <a href="#" class="sctop_08 list-group-item list-group-item-action bg-light">C08. ???????????? > ???????????????-?????? / ??????</a>
        <!--<a href="#" class="sctop_08_01 list-group-item list-group-item-action bg-light">C08-A. (??????)???????????? > ???????????????-?????? / ??????</a>-->
        <a href="#" class="sctop_09 list-group-item list-group-item-action bg-light">C09. ???????????? ?????? / ??????(??????)</a>
        <a href="#" class="sctop_09_01 list-group-item list-group-item-action bg-light">C09-A. ???????????? - ??????/?????? ????????????</a>
        <a href="#" class="sctop_10 list-group-item list-group-item-action bg-light">C010. ??????????????? ???????????????</a>
        <a href="#" class="sctop_11 list-group-item list-group-item-action bg-light">C011. BIC?????? - ???????????????</a>
        <a href="#" class="sctop_12 list-group-item list-group-item-action bg-light">C012. ?????????</a>
        <a href="#" class="sctop_13 list-group-item list-group-item-action bg-light">C013. ?????????</a>
        <a href="#" class="sctop_14 list-group-item list-group-item-action bg-light">C014. ???????????????</a>
        <a href="#" class="sctop_15 list-group-item list-group-item-action bg-light">C015. ??????????????? ????????????</a>
        <a href="#" class="sctop_16 list-group-item list-group-item-action bg-light">C016. ?????????&???????????????</a>
        <a href="#" class="sctop_17 list-group-item list-group-item-action bg-light">C017. ????????????&????????????</a>
        <a href="#" class="sctop_18 list-group-item list-group-item-action bg-light">C018. ??????????????????</a>
        <a href="#" class="sctop_19 list-group-item list-group-item-action bg-light">C019. ?????????&????????????</a>
        <a href="#" class="sctop_20 list-group-item list-group-item-action bg-light">C020. ????????????</a>
        <a href="#" class="sctop_21 list-group-item list-group-item-action bg-light">C021. ????????????</a>
        <a href="#" class="sctop_22 list-group-item list-group-item-action bg-light">C022. ?????????????????? ?????????</a>
        <a href="#" class="sctop_23 list-group-item list-group-item-action bg-light">C023. ????????????</a>
        <a href="#" class="sctop_24 list-group-item list-group-item-action bg-light">C024. ROE ??? PBR</a>
        <a href="#" class="sctop_25 list-group-item list-group-item-action bg-light">C025. ROE ????????????</a>
        <a href="#" class="sctop_26 list-group-item list-group-item-action bg-light">C026. ROA & ROE & ROIC</a>
        <a href="#" class="sctop_27 list-group-item list-group-item-action bg-light">C027. ???????????? ????????????</a>
        <a href="#" class="sctop_28 list-group-item list-group-item-action bg-light">C028. ??????????????????</a>
        <a href="#" class="sctop_29 list-group-item list-group-item-action bg-light">C029. ???????????????</a>
        <a href="#" class="sctop_30 list-group-item list-group-item-action bg-light">C030. ??????????????????</a>
        <a href="#" class="sctop_31 list-group-item list-group-item-action bg-light">C031. ?????????????????? ??????</a>
        <a href="#" class="sctop_32 list-group-item list-group-item-action bg-light">C032. ??????????????????</a>
        <a href="#" class="sctop_33 list-group-item list-group-item-action bg-light">C033. ????????? ???????????????</a>
        <a href="#" class="sctop_34 list-group-item list-group-item-action bg-light">C034. ?????????????????????</a>
        <a href="#" class="sctop_35 list-group-item list-group-item-action bg-light">C035. ????????? ???????????????</a>
        <a href="#" class="sctop_36 list-group-item list-group-item-action bg-light">C036. ????????????????????????(PCR)</a>
        <a href="#" class="sctop_37 list-group-item list-group-item-action bg-light">C037. ??????&??????????????????</a>
        <a href="#" class="sctop_38 list-group-item list-group-item-action bg-light">C038. ?????????????????????(PSR)</a>
        <a href="#" class="sctop_39 list-group-item list-group-item-action bg-light">C039. ??????&???????????????</a>
        <a href="#" class="sctop_40 list-group-item list-group-item-action bg-light">C040. EV/EBITDA</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

	  <?php
	  $active_menu = 'chart';
	  include_once dirname(__FILE__).'/inc/topmenu.php';
	  ?>

      <div class="container-fluid">
        <div id="container" class="sub_search sub_recom">
          <h1 class="mt-4 offset_01">C01. ??????-?????????(1??????)</h1>
          <div class="chart_div">
            <div class="chartSection">
              <!-- ????????????????????? -->
              <div id="chart_stock_line" class="chart_layout containerLine_1"></div>
              <script>SubRecomListChart('chart_stock_line', [<?=$chart_one?>]);</script>

            </div>
          </div>
          <!-- //chart_div -->

          <h1 class="mt-4 offset_02">C02. ??????-?????????(6??????)/??????</h1>
          <div class="chart_div">
            <div class="chartSection">
              <!-- ????????????????????? -->
              <div id="chart_stock_line_mon6" class="chart_layout containerLine_1"></div>

			  <script>
			  var params = [<?php foreach($chart_six_key as $val) echo "'".substr($val,5,5)."',";?>]
			  SubSearchSummaryLineChart('chart_stock_line_mon6', [<?=$chart_six?>], params);
			  </script>

            </div>
          </div>
          <!-- //chart_div -->
          <h1 class="mt-4 offset_03">C03. ????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">
            <!-- ????????????????????? -->
			<?php 

				// @ ?????? ?????? 
				$rev_year = explode(',',$mry_chart_key_03['sf1_revenue']);
				$ass_year = explode(',',$mry_chart_key_03['sf1_assets']);
				$ncf_year = explode(',',$mry_chart_key_03['sf1_ncfo']);

				//if(strtoupper($ticker['tkr_category'])=='ADR' || strtoupper($ticker['tkr_category']) =='ADR PRIMARY' || strtoupper($ticker['tkr_category'])=='CANADIAN' || strtoupper($ticker['tkr_category'])=='CANADIAN PRIMARY') {
				if($is_adr) {
					$sf1_opinc = 'sf1_opinc_ori';
					$sf1_netinc = 'sf1_netinc_ori';
				}
				else {
					$sf1_opinc = 'sf1_opinc';
					$sf1_netinc = 'sf1_netinc';
				}
			?>
              <div id="summary_column_year_chart1" class="chart_layout containerLine_1"></div>
				<script>
				var tooltip = 'na';
				var chart_title = [<?php foreach($rev_year as $val) echo "'".substr($val,0,4)."',";?>];
				var chart_value = [
					{'name': '?????????', 'data': [<?=$mry_chart_value_03['sf1_revenue']?>]},
					{'name': '????????????', 'data': [<?=$mry_chart_value_03[$sf1_opinc]?>]},
					{'name': '?????????', 'data': [<?=$mry_chart_value_03[$sf1_netinc]?>]}
				];
				SubSearchSummaryColumnChart('summary_column_year_chart1', chart_value, chart_title, tooltip);
				</script>

			  <div id="summary_column_year_chart2" class="chart_layout containerLine_1"></div>
			    <script>
				var chart_title = [<?php foreach($ass_year as $val) echo "'".substr($val,0,4)."',";?>];
				var chart_value = [
					{'name': '????????????', 'data': [<?=$mry_chart_value_03['sf1_assets']?>]},
					{'name': '????????????', 'data': [<?=$mry_chart_value_03['sf1_liabilities']?>]},
					{'name': '????????????', 'data': [<?=$mry_chart_value_03['sf1_equity']?>]}
				];
				SubSearchSummaryColumnChart('summary_column_year_chart2', chart_value, chart_title, tooltip);</script>

				<div id="summary_column_year_chart3" class="chart_layout containerLine_1"></div>
				<script>
				var chart_title = [<?php foreach($ncf_year as $val) echo "'".substr($val,0,4)."',";?>];
				var chart_value = [
					{'name': '????????????', 'data': [<?=$mry_chart_value_03['sf1_ncfo']?>]},
					{'name': '????????????', 'data': [<?=$mry_chart_value_03['sf1_ncfi']?>]},
					{'name': '????????????', 'data': [<?=$mry_chart_value_03['sf1_ncff']?>]}
				];
				SubSearchSummaryColumnChart('summary_column_year_chart3', chart_value, chart_title, tooltip);</script>


            </div>
          </div>
          <!-- //chart_div -->

          <h1 class="mt-4 offset_04">C04. ???????????? &gt; ????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">

			<?php
				//???????????????
				if($mri_data['m_d_divyield']=='') $mri_data['m_d_divyield']=0; else $mri_data['m_d_divyield'] = round($mri_data['m_d_divyield']*100, 3);
				if($indavg_scores['m_d_divyield']=='') $indavg_scores['m_d_divyield']=0; else $indavg_scores['m_d_divyield'] = round($indavg_scores['m_d_divyield']*100, 3);
				if($avg_scores['m_d_divyield']=='') $avg_scores['m_d_divyield']=0; else $avg_scores['m_d_divyield'] = round($avg_scores['m_d_divyield']*100, 2);
				
				//????????????
				if($mri_data['m_d_poratio']=='') $mri_data['m_d_poratio']=0; 
				if($indavg_scores['m_d_poratio']=='') $indavg_scores['m_d_poratio']=0;
				if($avg_scores['m_d_poratio']=='') $avg_scores['m_d_poratio']=0; 

				//??????????????????(%)
				if($mri_data['m_d_epsgr2']=='') $mri_data['m_d_epsgr2']=0; 
				if($indavg_scores['m_d_epsgr2']=='') $indavg_scores['m_d_epsgr2']=0;
				if($avg_scores['m_d_epsgr2']=='') $avg_scores['m_d_epsgr2']=0;

				//?????????????????????(%)
				if($mri_data['m_d_fcfgr']=='') $mri_data['m_d_fcfgr']=0;
				if($indavg_scores['m_d_fcfgr']=='') $indavg_scores['m_d_fcfgr']=0;
				if($avg_scores['m_d_fcfgr']=='') $avg_scores['m_d_fcfgr']=0;

				//??????5??? ?????????(??????)
				if($mri_data['m_d_dps1']=='') $mri_data['m_d_dps1']=0; 
				if($mri_data['m_d_dps2']=='') $mri_data['m_d_dps2']=0; 
				if($mri_data['m_d_dps3']=='') $mri_data['m_d_dps3']=0; 
				if($mri_data['m_d_dps4']=='') $mri_data['m_d_dps4']=0; 
				if($mri_data['m_d_dps5']=='') $mri_data['m_d_dps5']=0; 

				//if(strtoupper($ticker['tkr_category'])=='ADR' || strtoupper($ticker['tkr_category'])=='ADR PRIMARY' || strtoupper($ticker['tkr_category'])=='CANADIAN' || strtoupper($ticker['tkr_category'])=='CANADIAN PRIMARY') {
				if($is_adr) {
				
					$last_mry_list_do = array();
					$last_mry_list_do = @array_slice($last_mry_list, 0, 2);
					$curr = @array_shift($last_mry_list_do);
					$before = @array_pop($last_mry_list_do);
					$sf1_netinc = 'sf1_netinc';
				}
				else {
					$last_mry_list_do = array();
					$last_mry_list_do = $last_mrt_list;
					if(sizeof($last_mry_list_do)>5) {
						$last_mry_list_do = @array_slice($last_mry_list_do, 0, 5);
					}
					$curr = @array_shift($last_mry_list_do);
					$before = @array_pop($last_mry_list_do);
					$sf1_netinc = 'sf1_netinccmnusd';
				}
				
				$epsgr = 0;
				if( $curr[$sf1_netinc] > 0 && $before[$sf1_netinc] < 0 ) {
					$epsgr = 1;
					$str_netinc = '????????????';
				}
				else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] < 0 ) {
					$epsgr = 1;
					$str_netinc = '????????????';
				}
				else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] > 0 ) {
					$epsgr = 1;
					$str_netinc = '????????????';
				}

				if($epsgr) {
					$mri_data['m_d_epsgr2']=0;
					//$indavg_scores['m_g_epsgr']=0;
					//$avg_scores['m_g_epsgr']=0;
				}
			?>
              <!-- ????????????????????? -->
			  <div id="containeralloca_star1_1" class="chart_layout containerLine_1 ib_5"></div>
			  <script>
			    var chart_title = ['???????????????(%)'];
			    var chart_value = [
			  	{'name': '??????', 'data': [<?=$mri_data['m_d_divyield']?>]},
				{'name': '????????????', 'data': [<?=$indavg_scores['m_d_divyield']?>]},
				{'name': '????????????', 'data': [<?=$avg_scores['m_d_divyield']?>]}
			    ];
			    SubSearchSummaryColumnChart('containeralloca_star1_1', chart_value, chart_title);</script>


			  <div id="containeralloca_star1_2" class="chart_layout containerLine_1 ib_5"></div>
			  <script>
			    var chart_title = ['????????????(%)'];
			    var chart_value = [
			 	{'name': '??????', 'data': [<?=$mri_data['m_d_poratio']?>]},
				{'name': '????????????', 'data': [<?=$indavg_scores['m_d_poratio']?>]},
				{'name': '????????????', 'data': [<?=$avg_scores['m_d_poratio']?>]}
		  	    ];
			    SubSearchSummaryColumnChart('containeralloca_star1_2', chart_value, chart_title);</script>


			  <div id="containeralloca_star1_3" class="chart_layout containerLine_1 ib_5"></div>
			  <script>
				var chart_title = ['??????????????????(%)'];
				var ex_title = '<?=$str_netinc;?>';
				var chart_value = [
			 	{'name': '??????', 'data': [<?=$mri_data['m_d_epsgr2']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_d_epsgr2']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_d_epsgr2']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star1_3', chart_value, chart_title, ex_title);</script>

  			  <div id="containeralloca_star1_4" class="chart_layout containerLine_1 ib_5"></div>
				<script>
			  	  var chart_title = ['?????????????????????(%)'];
				  var chart_value = [
				  {'name': '??????', 'data': [<?=$mri_data['m_d_fcfgr']?>]},
				  {'name': '????????????', 'data': [<?=$indavg_scores['m_d_fcfgr']?>]},
				  {'name': '????????????', 'data': [<?=$avg_scores['m_d_fcfgr']?>]}
				  ];
				  SubSearchSummaryColumnChart('containeralloca_star1_4', chart_value, chart_title);</script>

		 	  <div id="containeralloca_star1_5" class="chart_layout containerLine_1 ib_5"></div>
			    <script>
				  var chart_title = ['??????5??? ?????????(??????)'];
				  var chart_value = [
				  {'name': '<?=substr($mri_data['m_d_dps_year5'],0,4);?>', 'data': [<?=$mri_data['m_d_dps5'];?>]},
				  {'name': '<?=substr($mri_data['m_d_dps_year4'],0,4);?>', 'data': [<?=$mri_data['m_d_dps4'];?>]},
				  {'name': '<?=substr($mri_data['m_d_dps_year3'],0,4);?>', 'data': [<?=$mri_data['m_d_dps3'];?>]},
				  {'name': '<?=substr($mri_data['m_d_dps_year2'],0,4);?>', 'data': [<?=$mri_data['m_d_dps2'];?>]},
				  {'name': '<?=substr($mri_data['m_d_dps_year1'],0,4);?>', 'data': [<?=$mri_data['m_d_dps1'];?>]}
   	 	 	 	  ];
				  SubSearchSummaryColumnChart('containeralloca_star1_5', chart_value, chart_title);</script>


              <!--<div id="chart_charm_column_2" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_column_3" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_column_4" class="chart_layout containerLine_1 ib_5"></div>
              <div class="chart_layout containerLine_1 ib_5">
                <span class="charm_num lately_allocation">
                  <strong>1.35 ??????</strong>
                  <span class="total" style="text-align: left;">??????5??? ?????????</span>
                </span>
              </div>-->
            </div>
          </div>
          <!-- //chart_div -->
          <h1 class="mt-4 offset_05">C05. ???????????? &gt; ???????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">
			<?php
				//5????????? ROE(%)
				if($mri_data['m_m_roe']=='') $mri_data['m_m_roe']=0; //else $mri_data['m_m_roe'] = round($mri_data['m_m_roe']*100, 2);
				if($indavg_scores['m_m_roe']=='') $indavg_scores['m_m_roe']=0; //else $indavg_scores['m_m_roe'] = round($indavg_scores['m_m_roe']*100, 2);
				if($avg_scores['m_m_roe']=='') $avg_scores['m_m_roe']=0; //else $avg_scores['m_m_roe'] = round($avg_scores['m_m_roe']*100, 2);

				//5????????? ???????????????(%)
				if($mri_data['m_m_opmargin']=='') $mri_data['m_m_opmargin']=0; //else $mri_data['m_m_opmargin'] = round($mri_data['m_m_opmargin']*100, 2);
				if($indavg_scores['m_m_opmargin']=='') $indavg_scores['m_m_opmargin']=0; //else $indavg_scores['m_m_opmargin'] = round($indavg_scores['m_m_opmargin']*100, 2);
				if($avg_scores['m_m_opmargin']=='') $avg_scores['m_m_opmargin']=0; //else $avg_scores['m_m_opmargin'] = round($avg_scores['m_m_opmargin']*100, 2);

				if($is_financial) {
					//5??? ?????? ?????? ?????????(%)
					if($mri_data['m_m_assetsgr']=='') $mri_data['m_m_assetsgr']=0; 
					if($indavg_scores['m_m_assetsgr']=='') $indavg_scores['m_m_assetsgr']=0; 
					if($avg_scores['m_m_assetsgr']=='') $avg_scores['m_m_assetsgr']=0; 
				}
				else {
					//5????????? ??????????????????(%)
					if($mri_data['m_m_revenuegr']=='') $mri_data['m_m_revenuegr']=0; //else $mri_data['m_m_revenuegr'] = round($mri_data['m_m_revenuegr']*100, 2);
					if($indavg_scores['m_m_revenuegr']=='') $indavg_scores['m_m_revenuegr']=0; //else $indavg_scores['m_m_revenuegr'] = round($indavg_scores['m_m_revenuegr']*100, 2);
					if($avg_scores['m_m_revenuegr']=='') $avg_scores['m_m_revenuegr']=0; //else $avg_scores['m_m_revenuegr'] = round($avg_scores['m_m_revenuegr']*100, 2);
				}
			?>
              <!-- ????????????????????? -->
				<?php if($is_financial){?>
				<div id="containeralloca_star2_1" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['5????????? ROE(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_m_roe']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_m_roe']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_m_roe']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star2_1', chart_value, chart_title);</script>

				<div id="containeralloca_star2_2" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['5????????? ???????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_m_opmargin']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_m_opmargin']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_m_opmargin']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star2_2', chart_value, chart_title);</script>

				<div id="containeralloca_star2_3" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['5??? ?????? ?????? ?????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_m_assetsgr']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_m_assetsgr']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_m_assetsgr']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star2_3', chart_value, chart_title);</script>

				<?php }else{?>
				<div id="containeralloca_star2_1" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['5????????? ROE(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_m_roe']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_m_roe']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_m_roe']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star2_1', chart_value, chart_title);</script>

				<div id="containeralloca_star2_2" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['5????????? ???????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_m_opmargin']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_m_opmargin']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_m_opmargin']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star2_2', chart_value, chart_title);</script>

				<div id="containeralloca_star2_3" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['5????????? ??????????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_m_revenuegr']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_m_revenuegr']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_m_revenuegr']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star2_3', chart_value, chart_title);</script>
				<?php }?>

              <!--<div id="chart_charm2_column" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm2_column_2" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm2_column_3" class="chart_layout containerLine_1 ib_5"></div>-->
            </div>
          </div>
          <!-- //chart_div -->
          <!--<h1 class="mt-4 offset_05_01">C05-A (??????)???????????? &gt; ???????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">-->
              <!-- ????????????????????? -->
              <!--<div id="chart_fincharm_column" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_fincharm_column_2" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_fincharm_column_3" class="chart_layout containerLine_1 ib_5"></div>-->
            <!--</div>
          </div>-->
          <!-- //chart_div -->
          <h1 class="mt-4 offset_06">C06. ???????????? &gt; ???????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">
			<?php
				//????????????(%)
				if($mri_data['m_s_debtratio']=='') $mri_data['m_s_debtratio']=0; else $mri_data['m_s_debtratio'] = round($mri_data['m_s_debtratio']*100, 2);
				if($indavg_scores['m_s_debtratio']=='') $indavg_scores['m_s_debtratio']=0; else $indavg_scores['m_s_debtratio'] = round($indavg_scores['m_s_debtratio']*100, 2);
				if($avg_scores['m_s_debtratio']=='') $avg_scores['m_s_debtratio']=0; else $avg_scores['m_s_debtratio'] = round($avg_scores['m_s_debtratio']*100, 2);

				//????????????(%)
				if($mri_data['m_s_crratio']=='') $mri_data['m_s_crratio']=0; else $mri_data['m_s_crratio'] = round($mri_data['m_s_crratio']*100, 2);
				if($indavg_scores['m_s_crratio']=='') $indavg_scores['m_s_crratio']=0; else $indavg_scores['m_s_crratio'] = round($indavg_scores['m_s_crratio']*100, 2);
				if($avg_scores['m_s_crratio']=='') $avg_scores['m_s_crratio']=0; else $avg_scores['m_s_crratio'] = round($avg_scores['m_s_crratio']*100, 2);

				//??????????????????(???)
				if($mri_data['m_s_intcoverage']=='') $mri_data['m_s_intcoverage']=0; else $mri_data['m_s_intcoverage'] = round($mri_data['m_s_intcoverage']);
				if($indavg_scores['m_s_intcoverage']=='') $indavg_scores['m_s_intcoverage']=0; else $indavg_scores['m_s_intcoverage'] = round($indavg_scores['m_s_intcoverage']);
				if($avg_scores['m_s_intcoverage']=='') $avg_scores['m_s_intcoverage']=0; else $avg_scores['m_s_intcoverage'] = round($avg_scores['m_s_intcoverage']);

				//???????????????(%)
				if($mri_data['m_s_boingratio']=='') $mri_data['m_s_boingratio']=0; else $mri_data['m_s_boingratio'] = round($mri_data['m_s_boingratio']*100, 2);
				if($indavg_scores['m_s_boingratio']=='') $indavg_scores['m_s_boingratio']=0; else $indavg_scores['m_s_boingratio'] = round($indavg_scores['m_s_boingratio']*100, 2);
				if($avg_scores['m_s_boingratio']=='') $avg_scores['m_s_boingratio']=0; else $avg_scores['m_s_boingratio'] = round($avg_scores['m_s_boingratio']*100, 2);

				//????????????(%)
				if($mri_data['m_s_fincost']=='') $mri_data['m_s_fincost']=0; else $mri_data['m_s_fincost'] = round($mri_data['m_s_fincost']*100, 2);
				if($indavg_scores['m_s_fincost']=='') $indavg_scores['m_s_fincost']=0; else $indavg_scores['m_s_fincost'] = round($indavg_scores['m_s_fincost']*100, 2);
				if($avg_scores['m_s_fincost']=='') $avg_scores['m_s_fincost']=0; else $avg_scores['m_s_fincost'] = round($avg_scores['m_s_fincost']*100, 2);

				if($is_financial) {
					//??????????????????(%)
					if($mri_data['m_s_bis']=='') $mri_data['m_s_bis']=0; //else $mri_data['m_s_bis'] = round($mri_data['m_s_bis']*100, 2);
					if($indavg_scores['m_s_bis']=='') $indavg_scores['m_s_bis']=0; //else $indavg_scores['m_s_bis'] = round($indavg_scores['m_s_bis']*100, 2);
					if($avg_scores['m_s_bis']=='') $avg_scores['m_s_bis']=0; //else $avg_scores['m_s_bis'] = round($avg_scores['m_s_bis']*100, 2);
				}
			?>
              <!-- ????????????????????? -->
				<?php if($is_financial){?>
				<div id="containeralloca_star3_1" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['??????????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_s_bis']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_s_bis']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_s_bis']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star3_1', chart_value, chart_title);</script>

				<?php }else {?>							
				<div id="containeralloca_star3_1" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_s_debtratio']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_s_debtratio']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_s_debtratio']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star3_1', chart_value, chart_title);</script>

				<div id="containeralloca_star3_2" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_s_crratio']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_s_crratio']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_s_crratio']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star3_2', chart_value, chart_title);</script>

				<div id="containeralloca_star3_3" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['??????????????????(???)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_s_intcoverage']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_s_intcoverage']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_s_intcoverage']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star3_3', chart_value, chart_title);</script>

				<div id="containeralloca_star3_4" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['???????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_s_boingratio']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_s_boingratio']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_s_boingratio']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star3_4', chart_value, chart_title);</script>
			
				<div id="containeralloca_star3_5" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_s_fincost']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_s_fincost']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_s_fincost']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star3_5', chart_value, chart_title);</script>
			<?php }?>
              <!--<div id="chart_charm_stability_column" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_stability_column_2" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_stability_column_3" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_stability_column_4" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_stability_column_5" class="chart_layout containerLine_1 ib_5"></div>-->
            </div>
          </div>
          <!-- //chart_div -->
          <!--<h1 class="mt-4 offset_06_01">C06-A. (??????)???????????? &gt; ???????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">-->
              <!-- ????????????????????? -->
              <!--<div id="chart_fincharm_stability_column" class="chart_layout containerLine_1 ib_5"></div>
            </div>
          </div>-->
          <!-- //chart_div -->
          <h1 class="mt-4 offset_07">C07. ???????????? &gt; ???????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">
			<?php
				//5????????? ROE(%)
				if($mri_data['m_g_roe']=='') $mri_data['m_g_roe']=0; 
				if($indavg_scores['m_g_roe']=='') $indavg_scores['m_g_roe']=0;
				if($avg_scores['m_g_roe']=='') $avg_scores['m_g_roe']=0; 

				//???????????????(%)
				if($mri_data['m_g_epsgr']=='') $mri_data['m_g_epsgr']=0;
				if($indavg_scores['m_g_epsgr']=='') $indavg_scores['m_g_epsgr']=0;
				if($avg_scores['m_g_epsgr']=='') $avg_scores['m_g_epsgr']=0;

				//if(strtoupper($ticker['tkr_category'])=='ADR' || strtoupper($ticker['tkr_category'])=='ADR PRIMARY' || strtoupper($ticker['tkr_category'])=='CANADIAN' || strtoupper($ticker['tkr_category'])=='CANADIAN PRIMARY') {
				if($is_adr) {
			
					$last_mry_list_do = array();
					$last_mry_list_do = @array_slice($last_mry_list, 0, 2);
					$curr = @array_shift($last_mry_list_do);
					$before = @array_pop($last_mry_list_do);
					$sf1_netinc = 'sf1_netinc';
				}
				else {
					$last_mry_list_do = array();
					$last_mry_list_do = $last_mrt_list;
					if(sizeof($last_mry_list_do)>5) {
						$last_mry_list_do = array_slice($last_mry_list_do, 0, 5);
					}
					$curr = array_shift($last_mry_list_do);
					$before = array_pop($last_mry_list_do);
					$sf1_netinc = 'sf1_netinccmnusd';
				}

				$epsgr = 0;
				if( $curr[$sf1_netinc] > 0 && $before[$sf1_netinc] < 0 ) {
					$epsgr = 1;
					$str_netinc = '????????????';
				}
				else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] < 0 ) {
					$epsgr = 1;
					$str_netinc = '????????????';
				}
				else if( $curr[$sf1_netinc] < 0 && $before[$sf1_netinc] > 0 ) {
					$epsgr = 1;
					$str_netinc = '????????????';
				}

				if($epsgr) {
					$mri_data['m_g_epsgr']=0;
					//$indavg_scores['m_g_epsgr']=0;
					//$avg_scores['m_g_epsgr']=0;
				}
			?>
              <!-- ????????????????????? -->
				<div id="containeralloca_star4_1" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['5??? ?????? ROE(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_g_roe']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_g_roe']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_g_roe']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star4_1', chart_value, chart_title);</script>

				<div id="containeralloca_star4_2" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['??????????????????(%)'];
				var ex_title = '<?=$str_netinc;?>';
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_g_epsgr']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_g_epsgr']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_g_epsgr']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star4_2', chart_value, chart_title, ex_title);</script>
              <!--<div id="chart_charm_revenue_column" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_revenue_column_2" class="chart_layout containerLine_1 ib_5"></div>-->
            </div>
          </div>
          <!-- //chart_div -->
          <h1 class="mt-4 offset_08">C08. ???????????? &gt; ???????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">
			<?php
				//????????????????????????(%)
				if($mri_data['m_c_ffrevenue']=='') $mri_data['m_c_ffrevenue']=0; else $mri_data['m_c_ffrevenue'] = round($mri_data['m_c_ffrevenue']*100, 2);
				if($indavg_scores['m_c_ffrevenue']=='') $indavg_scores['m_c_ffrevenue']=0; else $indavg_scores['m_c_ffrevenue'] = round($indavg_scores['m_c_ffrevenue']*100, 2);
				if($avg_scores['m_c_ffrevenue']=='') $avg_scores['m_c_ffrevenue']=0; else $avg_scores['m_c_ffrevenue'] = round($avg_scores['m_c_ffrevenue']*100, 2);

				//????????????????????????(???)
				if($mri_data['m_c_pcr']=='') $mri_data['m_c_pcr']=0; else $mri_data['m_c_pcr'] = round($mri_data['m_c_pcr']);
				if($indavg_scores_p['m_c_pcr']=='') $indavg_scores_p['m_c_pcr']=0; else $indavg_scores_p['m_c_pcr'] = round($indavg_scores_p['m_c_pcr']);
				if($avg_scores_p['m_c_pcr']=='') $avg_scores_p['m_c_pcr']=0; else $avg_scores_p['m_c_pcr'] = round($avg_scores_p['m_c_pcr']);

				//????????????????????????(%)
				if($mri_data['m_c_cashflow']=='') $mri_data['m_c_cashflow']=0; else $mri_data['m_c_cashflow'] = round($mri_data['m_c_cashflow']);
				if($indavg_scores['m_c_cashflow']=='') $indavg_scores['m_c_cashflow']=0; else $indavg_scores['m_c_cashflow'] = round($indavg_scores['m_c_cashflow']);
				if($avg_scores['m_c_cashflow']=='') $avg_scores['m_c_cashflow']=0; else $avg_scores['m_c_cashflow'] = round($avg_scores['m_c_cashflow']);
			?>
              <!-- ????????????????????? -->
				<?php if($is_financial){?>
				<div id="containeralloca_star5_3" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['??????????????????(????????????)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_c_cashflow']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_c_cashflow']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_c_cashflow']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star5_3', chart_value, chart_title);</script>
				<?php }else{?>
				<div id="containeralloca_star5_1" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['????????????????????????(%)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_c_ffrevenue']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_c_ffrevenue']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_c_ffrevenue']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star5_1', chart_value, chart_title);</script>

				<div id="containeralloca_star5_2" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['????????????????????????(???)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_c_pcr']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores_p['m_c_pcr']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores_p['m_c_pcr']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star5_2', chart_value, chart_title);</script>

				<div id="containeralloca_star5_3" class="chart_layout containerLine_1 ib_5"></div>
				<script>
				var chart_title = ['??????????????????(????????????)'];
				var chart_value = [
					{'name': '??????', 'data': [<?=$mri_data['m_c_cashflow']?>]},
					{'name': '????????????', 'data': [<?=$indavg_scores['m_c_cashflow']?>]},
					{'name': '????????????', 'data': [<?=$avg_scores['m_c_cashflow']?>]}
				];
				SubSearchSummaryColumnChart('containeralloca_star5_3', chart_value, chart_title);</script>
				<!-- ???????????? ???????????? class = containeralloca_star1  -->                                    
				<?php }?>

              <!--<div id="chart_charm_cash_column" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_cash_column_2" class="chart_layout containerLine_1 ib_5"></div>
              <div id="chart_charm_cash_column_3" class="chart_layout containerLine_1 ib_5"></div>-->
            </div>
          </div>
          <!-- //chart_div -->
          <!--<h1 class="mt-4 offset_08_01">C08-A. (??????)???????????? &gt; ???????????????-??????/??????</h1>
          <div class="chart_div">
            <div class="chartSection">-->
              <!-- ????????????????????? -->
              <!--<div id="chart_fincharm_cash_column" class="chart_layout containerLine_1 ib_5"></div>
            </div>
          </div>-->
          <!-- //chart_div -->
          <h1 class="mt-4 offset_09">C09. ???????????? ?????? / ??????(??????)</h1>
          <div class="chart_div">
            <div class="chartSection">
              <span class="charm_num ib_2">
                <span class="total">???????????? <strong><?=$mri_data['m_biz_total_score']?></strong> / 100 </span>
              </span>
              <!-- //charm_num -->
              <!-- ????????????????????? -->
              <div id="chart_spider" class="chart_layout containerLine_1 ib_2"></div>
              <script>SubSearchCharmSpiderChart('chart_spider', [<?=$mri_data['m_biz_dividend_stars']?>, <?=$mri_data['m_biz_moat_stars']?>, <?=$mri_data['m_biz_cashflow_stars']?>, <?=$mri_data['m_biz_growth_stars']?>, <?=$mri_data['m_biz_safety_stars']?>])</script>

              <table cellspacing="0" border="1" class="tableRanking table_alloca ib_3">
                <colgroup>
                  <col width="100px">
                  <col width="">
                  <col width="">
                </colgroup>
                    <tbody>
                        <tr>
                            <td class="title">
                                <span>????????????</span>
                            </td>
                            <td>
                                <div class="star_area">
                                    <div class="starRev">
										<?php 
										for($i = 1 ; $i <= 5 ; $i++) { 
											if($mri_data['m_biz_dividend_stars'] >= $i) {
												echo '<span class="starR on">???1</span>';
											}
											else {
												if($i-$mri_data['m_biz_dividend_stars'] <= 0.5) {
													echo '<span class="starR on half">???1</span>';
												}
												else {
													echo '<span class="starR">???1</span>';
												}
											}
										}
										?>
                                   </div>
                                </div>
                                <!-- //star_area -->
                            </td>
                            <td class="num">
                                <?php if($part_page!='et') :?><a href="/attractiveness/attractive?sort=dividend&netincome=all&marketcap=over100billion"><?php endif;?><span>?????? <?=$dividend_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <span>???????????????</span>
                            </td>
                            <td>
                                <div class="star_area">
                                    <div class="starRev">
										<?php 
										for($i = 1 ; $i <= 5 ; $i++) { 
											if($mri_data['m_biz_moat_stars'] >= $i) {
												echo '<span class="starR on">???1</span>';
											}
											else {
												if($i-$mri_data['m_biz_moat_stars'] <= 0.5) {
													echo '<span class="starR on half">???1</span>';
												}
												else {
													echo '<span class="starR">???1</span>';
												}
											}
										}
										?>
                                    </div>
                                </div>
                                <!-- //star_area -->
                            </td>
                            <td class="num">
                                <?php if($part_page!='et') :?><a href="/attractiveness/attractive?sort=moat&netincome=all&marketcap=over100billion"><?php endif;?><span>?????? <?=$moat_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <span>???????????????</span>
                            </td>
                            <td>
                                <div class="star_area">
                                    <div class="starRev">
										<?php 
										for($i = 1 ; $i <= 5 ; $i++) { 
											if($mri_data['m_biz_safety_stars'] >= $i) {
												echo '<span class="starR on">???1</span>';
											}
											else {
												if($i-$mri_data['m_biz_safety_stars'] <= 0.5) {
													echo '<span class="starR on half">???1</span>';
												}
												else {
													echo '<span class="starR">???1</span>';
												}
											}
										}
										?>
                                    </div>
                                </div>
                                <!-- //star_area -->
                            </td>
                            <td class="num">
                                <?php if($part_page!='et') :?><a href="/attractiveness/attractive?sort=safety&netincome=all&marketcap=over100billion"><?php endif;?><span>?????? <?=$safety_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <span>???????????????</span>
                            </td>
                            <td>
                                <div class="star_area">
                                    <div class="starRev">
										<?php 
										for($i = 1 ; $i <= 5 ; $i++) { 
											if($mri_data['m_biz_growth_stars'] >= $i) {
												echo '<span class="starR on">???1</span>';
											}
											else {
												if($i-$mri_data['m_biz_growth_stars'] <= 0.5) {
													echo '<span class="starR on half">???1</span>';
												}
												else {
													echo '<span class="starR">???1</span>';
												}
											}
										}
										?>
                                    </div>
                                </div>
                                <!-- //star_area -->
                            </td>
                            <td class="num">
                                <?php if($part_page!='et') :?><a href="/attractiveness/attractive?sort=growth&netincome=all&marketcap=over100billion"><?php endif;?><span>?????? <?=$growth_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                <span>???????????????</span>
                            </td>
                            <td>
                                <div class="star_area">
                                    <div class="starRev">
										<?php 
										for($i = 1 ; $i <= 5 ; $i++) { 
											if($mri_data['m_biz_cashflow_stars'] >= $i) {
												echo '<span class="starR on">???1</span>';
											}
											else {
												if($i-$mri_data['m_biz_cashflow_stars'] <= 0.5) {
													echo '<span class="starR on half">???1</span>';
												}
												else {
													echo '<span class="starR">???1</span>';
												}
											}
										}
										?>
                                    </div>
                                </div>
                                <!-- //star_area -->
                            </td>
                            <td class="num">
                                <?php if($part_page!='et') :?><a href="/attractiveness/attractive?sort=cashflow&netincome=all&marketcap=over100billion"><?php endif;?><span>?????? <?=$cashflow_rank_rate?>%</span><?php if($part_page!='et') :?></a><?php endif;?>
                            </td>
                        </tr>
                    </tbody>
              </table>
            </div>
          </div>
          <!-- //chart_div -->
          <h1 class="mt-4 offset_09_01">C09-A. ???????????? - ??????/?????? ????????????</h1>
          <div class="chart_div">
			<div id="chart_spider_mini" class="chart_layout containerLine_1 ib_2"></div>
			<script>CmsSpiderChartMini('chart_spider_mini', [<?=$mri_data['m_biz_dividend_stars']?>, <?=$mri_data['m_biz_moat_stars']?>, <?=$mri_data['m_biz_cashflow_stars']?>, <?=$mri_data['m_biz_growth_stars']?>, <?=$mri_data['m_biz_safety_stars']?>])</script>
          </div>

          <h1 class="mt-4 offset_10">C010. ??????????????? ???????????????</h1>
          <div class="chart_div">
            <!--<figure class="highcharts-figure">
              <div id="containerservice2" class="containerservice2"></div>
              <span class="proper">?????????</span>
            </figure>-->
			<figure class="highcharts-figure">           
				<div id="containerservice2" class="containerservice2"></div><!-- ???????????? id = containerS1 -->
				<span class="proper"><?=$this->common->get_valuation_stars_text($expected_star)?></span>
			</figure>
			<script>CmsGaugeChart('containerservice2', [<?=$expected_star;?>]);</script>
          </div>
<?php 
	$moneyvalue = 1000000;
	if($ticker_unit>0) $moneyvalue = $moneyvalue * $ticker_unit;
?>
          <h1 class="mt-4 offset_11">C011. BIC?????? - ???????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart011" class="chart_layout containerLine_1 ib_2"></div>
			<?php
			if($dimension=='MRT' || $dimension=='MRQ') $dp_year = '7'; else $dp_year = '4';
			$rev_key = array_keys(array_reverse($vchart_data['salesincome']['data']['L1_column_revenue']));
			$arr_l1 = array_values(array_reverse($vchart_data['salesincome']['data']['L1_column_revenue']));
			$arr_l1_cnt = count($arr_l1);
			$arr_l1_new = array();
			foreach($arr_l1 as $eVal) {
				if($eVal/$moneyvalue == 0 ) $arr_l1_cnt--;
				$arr_l1_new[] = $eVal/$moneyvalue;
			}
			if($arr_l1_cnt==0) $arr_l1_new = array();

			$arr_r1 = array_values(array_reverse($vchart_data['salesincome']['data']['R1_line_opinc']));
			$arr_r1_cnt = count($arr_r1);
			$arr_r1_new = array();
			foreach($arr_r1 as $eVal) {
				if($eVal/$moneyvalue == 0 ) $arr_r1_cnt--;
				$arr_r1_new[] = $eVal/$moneyvalue;
			}
			if($arr_r1_cnt==0) $arr_r1_new = array();

			$arr_r2 = array_values(array_reverse($vchart_data['salesincome']['data']['R2_line_netinc']));
			$arr_r2_cnt = count($arr_r2);
			$arr_r2_new = array();
			foreach($arr_r2 as $eVal) {
				if($eVal/$moneyvalue == 0 ) $arr_r2_cnt--;
				$arr_r2_new[] = $eVal/$moneyvalue;
			}
			if($arr_r2_cnt==0) $arr_r2_new = array();
			
			?>
			<script>
			var params = [<?php foreach($rev_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '?????????',
					type: 'column',
					data: [<?=implode(',',array_values($arr_l1_new))?>],
					tooltip: {
						// shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.1f} <?=$ticker_currency;?></b><br/>'
					},
				}, {
					name: '????????????',
					type: 'line',
					yAxis: 1,
					data: [<?=implode(',',array_values($arr_r1_new))?>],
					tooltip: {
						shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.1f} <?=$ticker_currency;?></b><br/>'
					},
				}, {
					name: '?????????????????????',
					type: 'line',
					yAxis: 1,
					data: [<?=implode(',',array_values($arr_r2_new))?>],
					tooltip: {
						shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.1f} <?=$ticker_currency;?></b><br/>'
					},
				}];
			SubSearchCharmFiChart1('chart_BICchart011', value, params, '<?=$ticker_currency;?>');
			</script>

          </div>

          <h1 class="mt-4 offset_12">C012. ?????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart012" class="chart_layout containerLine_1 ib_2"></div>
			<?php
			//echo '<pre>'; print_r($vchart_data['margin']['data']['L1_line_opmargin']);
			$arr_opkey = array_keys(array_reverse($vchart_data['margin']['data']['L1_line_opmargin']));
			$arr_l1 = array_values(array_reverse($vchart_data['margin']['data']['L1_line_opmargin']));
			$arr_l1_cnt = count($arr_l1);
			$arr_l1_new = array();
			foreach($arr_l1 as $eVal) {
				if(is_nan($eVal) || is_infinite($eVal)) $eVal = 0;
				if($eVal*100 == 0 ) $arr_l1_cnt--;
				$arr_l1_new[] = $eVal*100;
			}
			if($arr_l1_cnt==0) $arr_l1_new = array();

			$arr_l2 = array();
			$arr_l2 = array_values(array_reverse($vchart_data['margin']['data']['L2_line_netmargin']));
			$arr_l2_cnt = count($arr_l2);
			$arr_l2_new = array();
			foreach($arr_l2 as $eVal) {
				if($eVal*100 == 0 ) $arr_l2_cnt--;
				$arr_l2_new[] = $eVal*100;
			}
			if($arr_l2_cnt==0) $arr_l2_new = array();

			?>
			<script>
			var params = [<?php foreach($arr_opkey as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????????',
					type: 'line',
					data: [<?=implode(',',array_values($arr_l1_new))?>],
				}, {
					name: '????????????',
					type: 'line',
					data: [<?=implode(',',array_values($arr_l2_new))?>],
				}];
			SubSearchCharmFiChart2('chart_BICchart012', value, params);
			</script>

          </div>

          <h1 class="mt-4 offset_13">C013. ?????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart013" class="chart_layout containerLine_1 ib_2"></div>

			<?php
			/*
			$arr_cokey = array_keys(array_reverse($vchart_data['cor']['data']['L1_line_costrevenueratio']));
			$arr_l1 = array_values(array_reverse($vchart_data['cor']['data']['L1_line_costrevenueratio']));
			$arr_l1_cnt = count($arr_l1);
			$arr_l1_new = array();
			foreach($arr_l1 as $eVal) {
				if(is_nan($eVal) || is_infinite($eVal)) $eVal = 0;
				if($eVal*100 == 0 ) $arr_l1_cnt--;
				$arr_l1_new[] = $eVal*100;
			}
			if($arr_l1_cnt==0) $arr_l1_new = array();

			$arr_l2 = array();
			$arr_l2 = array_values(array_reverse($vchart_data['cor']['data']['L2_line_opexratio']));
			$arr_l2_cnt = count($arr_l2);
			$arr_l2_new = array();
			foreach($arr_l2 as $eVal) {
				if($eVal*100 == 0 ) $arr_l2_cnt--;
				$arr_l2_new[] = $eVal*100;
			}
			if($arr_l2_cnt==0) $arr_l2_new = array();

			<script>
			var type = 'line';
			var params = [<?php foreach($arr_cokey as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????????',
					type: 'line',
					data: [<?=implode(',',array_values($arr_l1_new))?>],
				}, {
					name: '??????????????????',
				    yAxis: 1,
					data: [<?=implode(',',array_values($arr_l2_new))?>],
				}];
			BicChart('chart_BICchart013', type, value, params);
			</script>
		  */?>
          </div>

          <h1 class="mt-4 offset_14">C014. ???????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart014" class="chart_layout containerLine_1 ib_2"></div>

			<?php
			/*
			$bc_data1 = array_keys(array_reverse($vchart_data['rnd']['data']['L1_column_rnd']));
			$bc_data2 = array_values(array_reverse($vchart_data['rnd']['data']['L1_column_rnd']));
			
			//echo '<pre>';
			//print_r($bc_data1);
			//print_r($bc_data2);
			
			$bc_data2_cnt = count($bc_data2);
			$bc_data2_new = array();
			foreach($bc_data2 as $val) {
				if(is_nan($val) || is_infinite($val)) $val = 0;
				if($val == 0 ) $bc_data2_cnt--;
				$bc_data2_new[] = $val;
			}
			if($bc_data2_cnt==0) $bc_data2_new = array();

			$bc_data3 = array();
			$bc_data3 = array_values(array_reverse($vchart_data['rnd']['data']['R1_line_rndratio']));
			$bc_data3_cnt = count($bc_data3);
			$bc_data3_new = array();
			foreach($bc_data3 as $val) {
				if(is_nan($val) || is_infinite($val)) $val = 0;
				if($val == 0 ) $bc_data3_cnt--;
				$bc_data3_new[] = $val;
			}
			if($bc_data3_cnt==0) $bc_data3_new = array();
			

			<script>
			var type = 'column';
			var params = [<?php foreach($bc_data1 as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????????',
					type: 'line',
					data: [<?=implode(',',array_values($bc_data2_new))?>],
				}, {
					name: '????????????',
				    yAxis: 1,
					data: [<?=implode(',',array_values($bc_data3_new))?>],
				}];
			BicChart('chart_BICchart014', type, value, params);
			</script>
		  */?>
          </div>

          <h1 class="mt-4 offset_15">C015. ??????????????? ????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart015" class="chart_layout containerLine_1 ib_2"></div>

			<?php
			$arr_l1 = array();

			if($dimension == 'MRT' && isset($vchart_data_mry) && $is_adr) {
				$de_key = array_keys(array_reverse($vchart_data_mry['debtcr']['data']['L1_line_de']));
				$arr_l1 = array_values(array_reverse($vchart_data_mry['debtcr']['data']['L1_line_de']));
			}
			else {
				$de_key = array_keys(array_reverse($vchart_data['debtcr']['data']['L1_line_de']));
				$arr_l1 = array_values(array_reverse($vchart_data['debtcr']['data']['L1_line_de']));
			}
			$arr_l1_cnt = count($arr_l1);
			$arr_l1_new = array();
			foreach($arr_l1 as $eVal) {
				if($eVal*100 == 0 ) $arr_l1_cnt--;
				$arr_l1_new[] = $eVal*100;
			}
			if($arr_l1_cnt==0) $arr_l1_new = array();

			$arr_r1 = array();
			if($dimension == 'MRT' && isset($vchart_data_mry) && $is_adr) {
				$arr_r1 = array_values(array_reverse($vchart_data_mry['debtcr']['data']['R1_line_currentratio']));
			}
			else {
				$arr_r1 = array_values(array_reverse($vchart_data['debtcr']['data']['R1_line_currentratio']));
			}
			$arr_r1_cnt = count($arr_r1);
			$arr_r1_new = array();
			foreach($arr_r1 as $eVal) {
				if($eVal*100 == 0 ) $arr_r1_cnt--;
				$arr_r1_new[] = $eVal*100;
			}
			if($arr_r1_cnt==0) $arr_r1_new = array();
			?>
			<script>
			var params = [<?php foreach($de_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '????????????',
					type: 'line',                               
					data: [<?=implode(',',array_values($arr_l1_new))?>],
				}, {
					name: '????????????',
					type: 'line',                                    
					yAxis: 1,     
					data: [<?=implode(',',array_values($arr_r1_new))?>],
				}];
			SubSearchCharmFiChart3('chart_BICchart015', value, params);
			</script>
          </div>

          <h1 class="mt-4 offset_16">C016. ?????????&???????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart016" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_17">C017. ????????????&????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart017" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_18">C018. ??????????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart018" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_19">C019. ?????????&????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart019" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_20">C020. ????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart020" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_21">C021. ????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart021" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_22">C022. ?????????????????? ?????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart022" class="chart_layout containerLine_1 ib_2"></div>
			<?php
			$dy_key = array_keys(array_reverse($vchart_data['dividend']['data']['R1_line_divyield']));
			$r1_dy = array_values(array_reverse($vchart_data['dividend']['data']['R1_line_divyield']));
			$ri_dy_cnt = count($r1_dy);
			$r1_dy_new = array();
			foreach($r1_dy as $eVal) {
				if($eVal*100 == 0 ) $ri_dy_cnt--;
				$r1_dy_new[] = $eVal*100;
			}
			if($ri_dy_cnt==0) $r1_dy_new = array();

			$l1_dps = array_values(array_reverse($vchart_data['dividend']['data']['L1_column_dps']));
			$l1_dps_cnt = count($l1_dps);
			$l1_dps_new = array();
			foreach($l1_dps as $eVal) {
				if($eVal == 0 ) $l1_dps_cnt--;
				$l1_dps_new[] = $eVal;
			}
			if($l1_dps_cnt==0) $l1_dps_new = array();

			?>
			<script>
			var params = [<?php foreach($dy_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????????',
					type: 'column',
					tooltip: {
						shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} ??????</b><br/>'                                        
					},
					data: [<?=implode(',',array_values($l1_dps_new))?>],
				}, {
					name: '???????????????',
					type: 'line',
					yAxis: 1,
					tooltip: {
						shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} %</b><br/>'                                        
					},
					data: [<?=implode(',',array_values($r1_dy_new))?>],
				}];
			SubSearchCharmFiChart4('chart_BICchart022', value, params);
			</script>
          </div>

          <h1 class="mt-4 offset_23">C023. ????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart023" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_24">C024. ROE ??? PBR</h1>
          <div class="chart_div">
            <div id="chart_BICchart024" class="chart_layout containerLine_1 ib_2"></div>
			<?php
			$roe_key = array_keys(array_reverse($vchart_data['roepbr']['data']['L1_line_roe']));
			$l1_roe = array_values(array_reverse($vchart_data['roepbr']['data']['L1_line_roe']));
			$l1_roe_cnt = count($l1_roe);
			$l1_roe_new = array();
			foreach($l1_roe as $eVal) {
				if($eVal*100 == 0 ) $l1_roe_cnt--;
				$l1_roe_new[] = $eVal*100;
			}
			if($l1_roe_cnt==0) $l1_roe_new = array();

			$r1_pb = array_values(array_reverse($vchart_data['roepbr']['data']['R1_line_pb']));
			$r1_pb_cnt = count($r1_pb);
			$r1_pb_new = array();
			foreach($r1_pb as $eVal) {
				if($eVal == 0 ) $r1_pb_cnt--;
				$r1_pb_new[] = $eVal;
			}
			if($r1_pb_cnt==0) $r1_pb_new = array();

			?>
			<script>
			var params = [<?php foreach($roe_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '?????????????????????',
					type: 'line',
					//tooltip: {                    
					//    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} %</b><br/>'                                        
					//},
					data: [<?=implode(',',array_values($l1_roe_new))?>],
				}, {
					name: '?????????????????????',
					type: 'line',
					yAxis: 1,
					//tooltip: {
					//    shared: true,
					//    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} ???</b><br/>'
					//},    
					data: [<?=implode(',',array_values($r1_pb_new))?>],
				}];
			SubSearchCharmFiChart5('chart_BICchart024', value, params);
			</script>
          </div>

          <h1 class="mt-4 offset_25">C025. ROE ????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart025" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_26">C026. ROA & ROE & ROIC</h1>
          <div class="chart_div">
            <div id="chart_BICchart026" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_27">C027. ???????????? ????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart027" class="chart_layout containerLine_1 ib_2"></div>
			<?php
			$turn_key = array_keys(array_reverse($vchart_data['turnoverdays']['data']['L1_line_receiveturnoverdays']));
			$l1_rt = array_values(array_reverse($vchart_data['turnoverdays']['data']['L1_line_receiveturnoverdays']));
			$l1_rt_cnt = count($l1_rt);
			$l1_rt_new = array();
			foreach($l1_rt as $eVal) {
				if($eVal == 0 ) $l1_rt_cnt--;
				$l1_rt_new[] = $eVal;
			}
			if($l1_rt_cnt==0) $l1_rt_new = array();

			$l2_it = array_values(array_reverse($vchart_data['turnoverdays']['data']['L2_line_inventoryturnoverdays']));
			$l2_it_cnt = count($l2_it);
			$l2_it_new = array();
			foreach($l2_it as $eVal) {
				if(is_nan($eVal) || is_infinite($eVal)) $eVal = 0;
				if($eVal == 0 ) $l2_it_cnt--;
				$l2_it_new[] = $eVal;
			}
			if($l2_it_cnt==0) $l2_it_new = array();

			$l3_pt = array_values(array_reverse($vchart_data['turnoverdays']['data']['L3_line_payableturnoverdays']));
			$l3_pt_cnt = count($l3_pt);
			$l3_pt_new = array();
			foreach($l3_pt as $eVal) {
				if($eVal == 0 ) $l3_pt_cnt--;
				$l3_pt_new[] = $eVal;
			}
			if($l3_pt_cnt==0) $l3_pt_new = array();
			?>
			<script>
			var params = [<?php foreach($turn_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????? ????????????',
					type: 'line',
					data: [<?=implode(',',array_values($l1_rt_new))?>],
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['turnoverdays']['data']['L1_line_receiveturnoverdays'])))?>],
				}, {
					name: '???????????? ????????????',
					type: 'line',
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['turnoverdays']['data']['L2_line_inventoryturnoverdays'])))?>],
					data: [<?=implode(',',array_values($l2_it_new))?>],
				}, {
					name: '???????????? ????????????',
					type: 'line',
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['turnoverdays']['data']['L3_line_payableturnoverdays'])))?>],
					data: [<?=implode(',',array_values($l3_pt_new))?>],
				}];
			SubSearchCharmFiChart6('chart_BICchart027', value, params);
			</script>
          </div>

          <h1 class="mt-4 offset_28">C028. ??????????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart028" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_29">C029. ???????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart029" class="chart_layout containerLine_1 ib_2"></div>

			<?php
			$ncfo_key = array_keys(array_reverse($vchart_data['cashflow']['data']['L1_line_ncfo']));
			$arr_li = array();
			$arr_l1 = array_values(array_reverse($vchart_data['cashflow']['data']['L1_line_ncfo']));
			$arr_l1_cnt = count($arr_l1);
			$arr_l1_new = array();
			foreach($arr_l1 as $eVal) {
				if($eVal/$moneyvalue == 0 ) $arr_l1_cnt--;
				$arr_l1_new[] = $eVal/$moneyvalue;
			}
			if($arr_l1_cnt==0) $arr_l1_new = array();

			$arr_l2 = array();
			$arr_l2 = array_values(array_reverse($vchart_data['cashflow']['data']['L2_line_ncfi']));
			$arr_l2_cnt = count($arr_l2);
			$arr_l2_new = array();
			foreach($arr_l2 as $eVal) {
				if($eVal/$moneyvalue == 0 ) $arr_l2_cnt--;
				$arr_l2_new[] = $eVal/$moneyvalue;
			}
			if($arr_l2_cnt==0) $arr_l2_new = array();

			$arr_l3 = array();
			$arr_l3 = array_values(array_reverse($vchart_data['cashflow']['data']['L3_line_ncff']));
			$arr_l3_cnt = count($arr_l3);
			$arr_l3_new = array();
			foreach($arr_l3 as $eVal) {
				if($eVal/$moneyvalue == 0 ) $arr_l3_cnt--;
				$arr_l3_new[] = $eVal/$moneyvalue;
			}
			if($arr_l3_cnt==0) $arr_l3_new = array();

			?>

			<script>
			var params = [<?php foreach($ncfo_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????? ????????????',
					type: 'line',
					data: [<?=implode(',',array_values($arr_l1_new))?>],
				}, {
					name: '???????????? ????????????',
					type: 'line',
					data: [<?=implode(',',array_values($arr_l2_new))?>],
				}, {
					name: '???????????? ????????????',
					type: 'line',
					data: [<?=implode(',',array_values($arr_l3_new))?>],
				}];
			SubSearchCharmFiChart7('chart_BICchart029', value, params, '<?=$ticker_currency;?>');
			</script>
          </div>

          <h1 class="mt-4 offset_30">C030. ??????????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart030" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_31">C031. ?????????????????? ??????</h1>
          <div class="chart_div">
            <div id="chart_BICchart031" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_32">C032. ??????????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart032" class="chart_layout containerLine_1 ib_2"></div>
			<?php
			$per_key = array_keys(array_reverse($vchart_data['per']['data']['L1_line_pe']));
			$l1_pe = array_values(array_reverse($vchart_data['per']['data']['L1_line_pe']));
			$l1_pe_cnt = count($l1_pe);
			$l1_pe_new = array();
			foreach($l1_pe as $eVal) {
				if($eVal == 0 ) $l1_pe_cnt--;
				$l1_pe_new[] = $eVal;
			}
			if($l1_pe_cnt==0) $l1_pe_new = array();
			?>
			<script>
			var params = [<?php foreach($per_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '??????????????????',
					type: 'line',
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['per']['data']['L1_line_pe'])))?>],
					data: [<?=implode(',',array_values($l1_pe_new))?>],
				}];
			SubSearchCharmFiChart8('chart_BICchart032', value, params);
			</script>
          </div>

          <h1 class="mt-4 offset_33">C033. ????????? ???????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart033" class="chart_layout containerLine_1 ib_2"></div>
			<?php
			$eps_key = array_keys(array_reverse($vchart_data['priceeps']['data']['L1_line_epsdil']));
			$l1_eps = array_values(array_reverse($vchart_data['priceeps']['data']['L1_line_epsdil']));
			$l1_eps_cnt = count($l1_eps);
			$l1_eps_new = array();
			foreach($l1_eps as $eVal) {
				if($eVal == 0 ) $l1_eps_cnt--;
				$l1_eps_new[] = $eVal;
			}
			if($l1_eps_cnt==0) $l1_eps_new = array();

			$r1_cl = array_values(array_reverse($vchart_data['priceeps']['data']['R1_line_close']));
			$r1_cl_cnt = count($r1_cl);
			$r1_cl_new = array();
			foreach($r1_cl as $eVal) {
				if($eVal == 0 ) $r1_cl_cnt--;
				$r1_cl_new[] = $eVal;
			}
			if($r1_cl_cnt==0) $r1_cl_new = array();

			?>
			<script>
			var params = [<?php foreach($eps_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????????',
					type: 'line',
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['priceeps']['data']['L1_line_epsdil'])))?>],
					data: [<?=implode(',',array_values($l1_eps_new))?>],
				}, {
					name: '??????',
					type: 'line',
					yAxis: 1,
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['priceeps']['data']['R1_line_close'])))?>],
					data: [<?=implode(',',array_values($r1_cl_new))?>],
				}];
			SubSearchCharmFiChart9('chart_BICchart033', value, params); 
			</script>
          </div>

          <h1 class="mt-4 offset_34">C034. ?????????????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart034" class="chart_layout containerLine_1 ib_2"></div>
			<?php

			if($dimension == 'MRT' && isset($vchart_data_mry) && $is_adr) {
				$pbr_key = array_keys(array_reverse($vchart_data_mry['pbr']['data']['L1_line_pb']));
				$l1_pb = array_values(array_reverse($vchart_data_mry['pbr']['data']['L1_line_pb']));
			}
			else {
				$pbr_key = array_keys(array_reverse($vchart_data['pbr']['data']['L1_line_pb']));
				$l1_pb = array_values(array_reverse($vchart_data['pbr']['data']['L1_line_pb']));
			}

			$l1_pb_cnt = count($l1_pb);
			$l1_pb_new = array();
			foreach($l1_pb as $eVal) {
				if($eVal == 0 ) $l1_pb_cnt--;
				$l1_pb_new[] = $eVal;
			}
			if($l1_pb_cnt==0) $l1_pb_new = array();
			?>
			<script>
			var params = [<?php foreach($pbr_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '?????????????????????',
					type: 'line',
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['pbr']['data']['L1_line_pb'])))?>],
					data: [<?=implode(',',array_values($l1_pb_new))?>],
				}];
			SubSearchCharmFiChart10('chart_BICchart034', value, params);
			</script>
          </div>

          <h1 class="mt-4 offset_35">C035. ????????? ???????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart035" class="chart_layout containerLine_1 ib_2"></div>
			<?php

			if($dimension == 'MRT' && isset($vchart_data_mry) && $is_adr) {
				$bvps_key = array_keys(array_reverse($vchart_data_mry['pricebps']['data']['L1_line_bvps']));
				$l1_bv = array_values(array_reverse($vchart_data_mry['pricebps']['data']['L1_line_bvps']));
			}
			else {
				$bvps_key = array_keys(array_reverse($vchart_data['pricebps']['data']['L1_line_bvps']));
				$l1_bv = array_values(array_reverse($vchart_data['pricebps']['data']['L1_line_bvps']));
			}

			$l1_bv_cnt = count($l1_bv);
			$l1_bv_new = array();
			foreach($l1_bv as $eVal) {
				if($eVal == 0 ) $l1_bv_cnt--;
				$l1_bv_new[] = $eVal;
			}
			if($l1_bv_cnt==0) $l1_bv_new = array();

			if($dimension == 'MRT' && isset($vchart_data_mry) && $is_adr) {
				$l2_cl = array_values(array_reverse($vchart_data_mry['pricebps']['data']['L2_line_close']));
			}
			else {
				$l2_cl = array_values(array_reverse($vchart_data['pricebps']['data']['L2_line_close']));
			}
			
			$l2_cl_cnt = count($l2_cl);
			$l2_cl_new = array();
			foreach($l2_cl as $eVal) {
				if($eVal == 0 ) $l2_cl_cnt--;
				$l2_cl_new[] = $eVal;
			}
			if($l2_cl_cnt==0) $l2_cl_new = array();
			?>
			<script>
			var params = [<?php foreach($bvps_key as $val) echo "'".substr($val,0,$dp_year)."',";?>];
			var value = [{
					name: '???????????????',
					type: 'line',
					//data: [<?=implode(',',array_values(array_reverse($vchart_data['pricebps']['data']['L1_line_bvps'])))?>],
					data: [<?=implode(',',array_values($l1_bv_new))?>],
				}, {
					name: '??????',
					type: 'line',
					yAxis: 1,
				   // data: [<?=implode(',',array_values(array_reverse($vchart_data['pricebps']['data']['L2_line_close'])))?>],
					data: [<?=implode(',',array_values($l2_cl_new))?>],
			}];
			SubSearchCharmFiChart11('chart_BICchart035', value, params);
			</script>
          </div>

          <h1 class="mt-4 offset_36">C036. ????????????????????????(PCR)</h1>
          <div class="chart_div">
            <div id="chart_BICchart036" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_37">C037. ??????&??????????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart037" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_38">C038. ?????????????????????(PSR)</h1>
          <div class="chart_div">
            <div id="chart_BICchart038" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_39">C039. ??????&???????????????</h1>
          <div class="chart_div">
            <div id="chart_BICchart039" class="chart_layout containerLine_1 ib_2"></div>
          </div>

          <h1 class="mt-4 offset_40">C040. EV/EBITDA</h1>
          <div class="chart_div">
            <div id="chart_BICchart040" class="chart_layout containerLine_1 ib_2"></div>
          </div>
        </div>
        <!-- //container -->
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
  <!-- /#wrapper -->