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
            marginTop: 50,    
            marginBottom: 130,
            height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#C4C4C4 ", "#404fc3", "#5CAC00"],

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
                    color: ["#959393"],
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
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 40,
            marginBottom: 130,
            height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#5CAC00"],

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
                style: {
                    color: ["#5CAC00"],
                },
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
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
/*
function SubSearchCharmFiChart3(obj_id, value, params) {
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
                    [0, '#FFFFFF'],
                    [1, '#FFFFFF']
                ]
            },
            style: {
                fontFamily: "'Lato', 'Noto Sans KR'"
            },
            marginTop: 50,
            marginBottom: 130,
            height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },
        colors: ["#404FC3", "#5CAC00"],
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
                    color: ["#404FC3"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value}</b><br/> %',
                style: {
                    color: ["#5CAC00"],
                }
            },
            opposite: true
        }],
		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
*/

function SubSearchCharmFiChart3(obj_id, value, params) {
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
            marginTop: 50,
            marginBottom: 130,
            //height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#5CAC00"],

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
                    color: ["#404fc3"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value}</b><br/> %',                
                style: {
                    color: ["#5CAC00"],
                }
            },
            opposite: true
        }],

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 50,
            marginBottom: 130,
            height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#C4C4C4", "#404fc3", "#5CAC00"],

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
                    color: ["#959393"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value} %',                
                style: {
                    color: ["#404fc3"],
                }
            },
            opposite: true
        }],

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 40,
            marginBottom: 130,
            //height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#5CAC00"],

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
                    color: ["#404fc3"],
                }
            },
        }, { // Secondary yAxis
            title: {
                text : null,
            },
            labels: {
                format: '{value} 배',
                style: {
                    color: ["#5CAC00"],
                }
            },
            opposite: true
        }],

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 40,
            marginBottom: 130,
            height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#C4C4C4", "#404fc3", "#5CAC00"],

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
                    color: ["#959393"],
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
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 50,
            marginBottom: 130,
            height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#C4C4C4", "#404fc3", "#5CAC00"],

        title: {
            style: {
                fontSize: '0',
            },
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f}</b>'+moneyunit+'</b><br/>',                        
        },
        
        xAxis: [{
            categories: params,
            crosshair: true,
            
        }],

        yAxis: {
            labels: {
                format: '{value}</b><br/>',                
                style: {
                    color: ["#959393"],
                }
            },
            title: {
                text: null,
            }
        },

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 40,
            marginBottom: 130,
            height: 400,
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
                style: {
                    color: ["#404fc3"],
                },
            },
            title: {
                text: null,
            }
        },

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 40,
            marginBottom: 130,
            //height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#5CAC00"],

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
                    color: ["#404fc3"],
                },
            },
            title: {
                text: null,
            },
        }, {
            labels: {
                format: '{value} 달러',      
                style: {
                    color: ["#5CAC00"],
                }                       
            },
            title: {
                text: null,
            },
            opposite: true
        }],

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
			marginTop: 40,
			marginBottom: 130,
			height: 400,
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
                style: {
                    color: ["#959393"],
                },
			},
			title: {
				text: null,
			}
		},

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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
            marginTop: 40,
            marginBottom: 130,
            //height: 400,
            plotBorderColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },

        colors: ["#404fc3", "#5CAC00"],

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
                    color: ["#404fc3"],
                }
            },
            title: {
                text: null,
            },
        }, {
            labels: {
                format: '{value} 달러',      
                style: {
                    color: ["#5CAC00"],
                }
            },
            title: {
                text: null,
            },
            opposite: true
        }],

		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
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


/* 적정가밴드 주가비교 */
function SubSearchCharmFiChart12(obj_id, value, params) {
	// 검색 - 적정가밴드 주가비교
	Highcharts.chart('sum_topchart_band', {
		chart: {
			type: 'line',
			zoomType: 'xy',
			renderTo: 'sum_topchart_band',
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
			marginTop: 40,
			marginBottom: 70,
			plotBorderColor: null,
			plotBorderWidth: null,
			plotShadow: false,
		},
		colors: ["#ff0000", "#aeceec", "#a6c9e9", "#4285f4", "#6ea4d4", "#5486b4"],            
		title: {
			text: null
		},
		tooltip: {
			shared: true,
			pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f}</b><br/>'
		},
		xAxis: [{
			categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
			crosshair: true
		}],
		yAxis: {
			labels: {
				format: '{value}',
				style: {
					color: ["#333333"],
				},
			},
			title: {
				text: null,
			}
		},
		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			}
		},
		series: [{
			type: 'line',
			data: [14908, 12908, 13908, 13908, 15908, 12908, 13989, 14989, 15989,  16989, 17989, 18989, 19111, 17111, 13111, 17111],
			name: '주가',
			lineWidth: 3
		}, {
			type: 'line',
			data: [12908, 12908, 12908, 12908, 12908, 12908, 8989, 8989, 8989,  8989, 8989, 8989, 18111, 18111, 18111, 18111],
			name: '매우저평가',
			// dashStyle: 'ShortDash',
			opacity: 0.6,
			className: 'label_none',
		}, {
			type: 'line',
			data: [13908, 13908, 13908, 13908, 13908, 13908, 9989, 9989, 9989,  9989, 9989, 9989, 19111, 19111, 19111, 19111],
			name: '저평가',
			dashStyle: 'ShortDash',
			opacity: 0.6,
			className: 'label_none',
		}, {
			type: 'line',
			data: [13908, 13908, 12908, 12908, 13908, 13908, 12989, 11989, 17989,  19989, 14989, 14989, 13111, 14111, 15111, 19231],
			name: '적정가',    
		}, {
			type: 'line',
			data: [15908, 15908, 15908, 15908, 15908, 15908, 11989, 11989, 11989,  11989, 11989, 11989, 21111, 21111, 21111, 21111],
			name: '고평가',
			dashStyle: 'ShortDash',
			opacity: 0.6,
			className: 'label_none',
		}, {
			type: 'line',
			data: [16908, 16908, 16908, 16908, 16908, 16908, 12989, 12989, 12989,  12989, 12989, 12989, 22111, 22111, 22111, 22111],
			name: '매우고평가',
			// dashStyle: 'ShortDash',
			opacity: 0.6,
			className: 'label_none',
		}],            
		plotOptions: {
			series: {
				marker: {
					enabled: false,
				}
			}
		},
	});
}

// 초이스스탁_서브 - 종목검사 >  배당탭 column 차트   
function SubSearchAllocLineChart(obj_id, value, params, moneyunit) {
	moneyunit = moneyunit || '%';
	var chart_colors = '';
	chart_colors = ["#404fc3", "#545872", "#d3d3d3"];
	if(obj_id=='alloc_line_chart2') {
		chart_colors = ["#545872"];
	}
    Highcharts.chart(obj_id, {
        chart: {
            type: 'column',
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
			plotBorderColor: null,
			plotBorderWidth: null,
			plotShadow: false
        },

        colors: chart_colors,
        tooltip: {
            shared: true,            
        },

        xAxis: [{
            categories: params,
            crosshair: true,
			labels: {
				style: {
					color: '#939393',
					fontSize: '0.85rem'
				}
			}
        }],

		yAxis: {
			title: {
				text: null
			},
			lineColor: null,
			minorGridLineWidth: 1,
			gridLineWidth: 0,
			lineWidth: 1,
			plotLines: [{
				color: '#c8c8c8',
				width: 1,
				value: 0
			}],
			alternateGridColor: null,
			showFirstLabel: false,
			breaks: [{
				from: 0,
				to: 100
			}],
			labels: {
				enabled: false
			}
		},

		title: {
			style: {
				'font-weight': "bold",
				color: '#E0E0E3',
				textTransform: 'uppercase',
				fontSize: '0',
			},
			text: ''
		},
        
		credits: {
			text: '초이스스탁US',
			href: false,
			style: {
				fontSize: '12px',
				cursor: 'text',
			},
		},
		exporting: {
			buttons: {
				contextButton: {
					menuItems: ["viewFullscreen", "printChart", "downloadPNG", "downloadJPEG"],
				}
			},
		},

        legend: {
            enabled: false
        },

        series: value,

        plotOptions: {
            series: {
                marker: {
                    enabled: false,
                }
            },
            column: {                
                minPointLength: 5,
                dataLabels: {
                    enabled: true,
                    crop: false,
                    color: '#939393',
                    overflow: 'none',
                    format: '{point.y:,.2f} '+moneyunit,                             
                }
            }
        },
    });
}