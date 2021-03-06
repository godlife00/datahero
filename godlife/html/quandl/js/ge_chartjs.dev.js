/* 매출과 이익 */
function SubSearchCharmFiChart1(obj_id, value, params, moneyunit) {

    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });

	Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 30,    
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#bebccb", "#404fc3", "#ffc400"],

        title: {
            text : null,
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.1f} '+moneyunit+'</b><br/>',            
        },

        xAxis: [{
            categories: params,
            crosshair: true,            
            
        }],

        yAxis: [{ // Primary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value}</br><br/>',                
                style: {
                    color: ["#00CCBD"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value}</b><br/>',                
                style: {
                    color: ["#623FA8"],
                }
            },
            opposite: true
        }],

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',                
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200204 name 변경됨. 매출액, 영업이익, 지배지분순이익

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        dataLabels: {
            enabled: false,
        },

        plotOptions: {                
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}

/* 이익률 */
function SubSearchCharmFiChart2(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 20,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#ffc400"],

        title: {
            text: null
        },

        xAxis: [{
            categories:  params,
            crosshair: true,
            
        }],

        yAxis: {
            labels: {
                format: '{value} %',                
            },
            title: {
                text: null,
            }
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} %</b><br/>'            
        },

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200203 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}

/* 부채비율과 유동비율 */
function SubSearchCharmFiChart3(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 20,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#ffc400"],

        title: {
            style: {
                fontSize: '0',
            },
        },

        //tooltip: {
        //    shared: true,
        //    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} %</b><br/>'            
        //},

		tooltip: {
            shared: true,
            useHTML: true,
            formatter: function() {
                var s = this.x+'<br>';
                $.each(this.points, function(i, point) {
					if(point.y == 0) {
						//s += point.series.name+'<b>N/A</b><br>';
						s += '<span style="color:'+point.series.color+'">'+point.series.name+' : <b>N/A</b><br/>' 
					}
					else {
						s += '<span style="color:'+point.series.color+'">'+point.series.name+' : <b>'+point.y.toFixed(2)+' %</b><br/>' 
					}

                });
                return s;
            },
            shared: true
        },

        xAxis: [{
            categories: params,
            crosshair: true,            
        }],

        yAxis: [{ // Primary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value}</b><br/> %',                
                style: {
                    color: ["#623FA8"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value}</b><br/> %',                
                style: {
                    color: ["#FF9700"],
                }
            },
            opposite: true
        }],

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200204 name 변경됨. 부채비율, 유동비율

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}

/* 주당배당금과 배당률 */
function SubSearchCharmFiChart4(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 30,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#bebccb", "#404fc3", "#ffc400"],

        title: {
            style: {
                fontSize: '0',
            },
        },

        tooltip: {
            shared: true,                
        },

        xAxis: [{
            categories: params,
            crosshair: true,
            
        }],

        yAxis: [{ // Primary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value}</b><br/>달러',                
                style: {
                    color: ["#00CCBD"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value} %',                
                style: {
                    color: ["#623FA8"],
                }
            },
            opposite: true
        }],

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200203 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}

/* ROE 과 PBR */
function SubSearchCharmFiChart5(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 20,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#ffc400"],

        title: {
            style: {
                fontSize: '0',
            },
        },

		tooltip: {
            shared: true,
            useHTML: true,
            formatter: function() {
                var s = this.x+'<br>';
				var d = '';
                $.each(this.points, function(i, point) {
					if(point.series.name=='자기자본이익률') d='%'; else d='배';
					if(point.y == 0) {
						//s += point.series.name+'<b>N/A</b><br>';
						s += '<span style="color:'+point.series.color+'">'+point.series.name+' : <b>N/A</b><br/>' 
					}
					else {
						s += '<span style="color:'+point.series.color+'">'+point.series.name+' : <b>'+point.y.toFixed(2)+' '+d+'</b><br/>' 
					}

                });
                return s;
            },
            shared: true
        },

        xAxis: [{
            categories: params,
            crosshair: true,
            
        }],

        yAxis: [{ // Primary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value} %',                
                style: {
                    color: ["#623FA8"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value} 배',
                style: {
                    color: ["#FF9700"],
                }
            },
            opposite: true
        }],

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200204 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}

/* 운전자본 회전일수 */
function SubSearchCharmFiChart6(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 20,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#bebccb", "#404fc3", "#ffc400"],

        title: {
            style: {
                fontSize: '0',
            },
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 일</b><br/>'
        },
        
        xAxis: [{
            categories:  params,
            crosshair: true,
            
        }],

        yAxis: [{ // Primary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value} 일',
                style: {
                    color: ["#623FA8"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value} 일',
                style: {
                    color: ["#FF9700"],
                }
            },
            opposite: true
        }],

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200204 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}

/* 현금흐름표 */
function SubSearchCharmFiChart7(obj_id, value, params, moneyunit) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 30,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#bebccb", "#404fc3", "#ffc400"],

        title: {
            style: {
                fontSize: '0',
            },
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.1f}</b><br/>'+moneyunit+'</b><br/>',                        
        },
        
        xAxis: [{
            categories: params,
            crosshair: true,
            
        }],

        yAxis: {
            labels: {
                format: '{value}</b><br/>',                
            },
            title: {
                text: null,
            }
        },

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200203 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}


/* 주가수익배수(PER) */
function SubSearchCharmFiChart8(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 20,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3"],
        
        title: {
            style: {
                fontSize: '0',
            },
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} 배</b><br/>'            
        },
        
        xAxis: [{
            categories: params,
            crosshair: true,
            
        }],

        yAxis: {
            labels: {
                format: '{value} 배',                          
            },
            title: {
                text: null,
            }
        },

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200203 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}

/* 주가와 주당순이익 */
function SubSearchCharmFiChart9(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 20,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#ffc400"],

        title: {
            style: {
                fontSize: '0',
            },
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} 달러</b><br/>'            
        },   
        
        xAxis: [{
            categories: params,
            crosshair: true,
            
        }],

        yAxis: [{
            labels: {
                format: '{value} 달러', 
                style: {
                    color: ["#623FA8"],
                }                           
            },
            title: {
                text: null,
            },
        }, {
            labels: {
                format: '{value} 달러',      
                style: {
                    color: ["#FF9700"],
                }                       
            },
            title: {
                text: null,
            },
            opposite: true
        }],

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200204 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}


/* 주가순자산배수(PBR) */
function SubSearchCharmFiChart10(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
	Highcharts.chart(obj_id, {
		chart: {
			type: 'line',
			zoomType: 'xy',
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
			marginTop: 20,
			marginBottom: 120,
			//height: 300,
			plotBorderColor: null,
			plotBorderWidth: null,
			plotShadow: false,
		},

		colors: ["#404fc3"],

		title: {
			text: null
		},

		tooltip: {
			shared: true,
			pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} 배</b><br/>'                
		},
		
		xAxis: [{
			categories: params,
            crosshair: true,
            
		}],

		yAxis: {
			labels: {
				format: '{value} 배',                              
			},
			title: {
				text: null,
			}
		},

		credits: {
			enabled: false
		},

		exporting: {
			enabled: false
		},

		series: value, //200204 변경됨 chartjs.js 참고

		lang: {
			noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
		},

		noData: {
			style: {
				fontWeight: 'nomal',
				fontSize: '1rem',
				color: '#8380A0',                    
			}
		},

		plotOptions: {
			series: {
				marker: {
					enabled: false,
				}
			}
		},
	});
}

/* 주가와 주당순자산 */
function SubSearchCharmFiChart11(obj_id, value, params) {
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
            zoomType: 'xy',
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
            marginTop: 20,
            marginBottom: 120,
            //height: 300,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#ffc400"],

        title: {
            style: {
                fontSize: '0',
            },
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.2f} 달러</b><br/>'
        }, 
        
        xAxis: [{
            categories: params,
            crosshair: true,
            
        }],

        yAxis: [{
            labels: {
                format: '{value} 달러', 
                style: {
                    color: ["#623FA8"],
                }                           
            },
            title: {
                text: null,
            },
        }, {
            labels: {
                format: '{value} 달러',      
                style: {
                    color: ["#FF9700"],
                }                       
            },
            title: {
                text: null,
            },
            opposite: true
        }],

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },

        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },

        series: value, //200204 변경됨 chartjs.js 참고

        lang: {
            noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",                
        },

        noData: {
            style: {
                fontWeight: 'nomal',
                fontSize: '1rem',
                color: '#8380A0',
                align : 'left'
            }
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            }
        },
    });
}
