$(document).ready(function () {

    if ($('#chart_stock_line').length) {
        // D01. 주가-선차트(1개월)
        Highcharts.chart('chart_stock_line', {

            chart: {
                type: 'line',
                renderTo: 'chart_stock_line',
                backgroundColor: {
                    // linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
                },
                style: {
                    fontFamily: "'Lato', 'Noto Sans KR'"
                },
                height: 200,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            colors: ["#e1474e"],
            title: {
                style: {
                    'font-weight': "bold",
                    color: '#E0E0E3',
                    textTransform: 'uppercase',
                    fontSize: '0',
                },
                text: null
            },

            tooltip: {
                enabled: false,
            },

            xAxis: {
                title: {
                    text: null
                },
                lineColor: null,
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: null,
                showFirstLabel: false,
                tickColor: null,
                labels: {
                    enabled: false
                }
            },

            yAxis: {
                title: {
                    text: null
                },
                lineColor: null,
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: null,
                showFirstLabel: false,
                labels: {
                    enabled: false
                }
            },

            credits: {
                enabled: false,
            },

            legend: {
                enabled: false,
            },

            exporting: {
                enabled: false
            },

            series: [{
                name: '',
                data: [
                    0.2, 0.1, 0.1, 0.1, 0.3, 0.2, 0.3, 0.1, 0.7, 0.3, 0.2, 0.2,
                    0.3, 0.1, 0.3, 0.4, 0.3, 0.2, 0.3, 0.2, 0.4, 0.0, 0.9, 0.3,
                    0.7, 1.1, 1.8, 1.2, 1.4, 1.2, 0.9, 0.8, 0.9, 0.2, 0.4, 1.2,
                    0.3, 2.3, 1.0, 0.7, 1.0, 0.8, 2.0, 1.2, 1.4, 3.7, 2.1, 2.0,
                    1.5
                ]
            }],

            plotOptions: {
                series: {
                    enableMouseTracking: false,
                    label: {
                        connectorAllowed: false
                    },
                    pointStart: null,
                    marker: {
                        enabled: false,
                    }
                }
            },
        });
    }

    if ($('#chart_stock_line_mon6').length) {
        // D02. 주가-선차트(6개월)
        Highcharts.chart('chart_stock_line_mon6', {
            chart: {
                type: 'line',
                renderTo: 'chart_stock_line_mon6',
                backgroundColor: {
                    // linearGradient: { x1: 0, y1: 1, x2: 1, y2: 0 },
                },
                style: {
                    fontFamily: "'Lato', 'Noto Sans KR'"
                },
                height: 200,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            colors: ["#e1474e", "#ff3301"],
            title: {
                style: {
                    'font-weight': "bold",
                    color: '#E0E0E3',
                    textTransform: 'uppercase',
                    fontSize: '0',
                },
                text: null
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}"><b>{point.y:,.2f} 달러</b><br/>'
            },

            xAxis: [{
                categories: ['09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', , '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', , '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16', '09/16'],
                crosshair: true,
                labels: {
                    enabled: true,
                    step: 3,
                }
            }],

            yAxis: {
                labels: {
                    format: '{value}</b><br/>달러',
                },
                title: {
                    text: null
                },
            },

            credits: {
                enabled: false,
            },

            legend: {
                enabled: false,
            },

            exporting: {
                enabled: false
            },

            series: [{
                name: '',
                data: [
                    0.2, 0.1, 0.1, 0.1, 0.3, 0.2, 0.3, 0.1, 0.7, 0.3, 0.2, 0.2,
                    0.3, 0.1, 0.3, 0.4, 0.3, 0.2, 0.3, 0.2, 0.4, 0.0, 0.9, 0.3,
                    0.7, 1.1, 1.8, 1.2, 1.4, 1.2, 0.9, 0.8, 0.9, 0.2, 0.4, 1.2,
                    0.3, 2.3, 1.0, 0.7, 1.0, 0.8, 2.0, 1.2, 1.4, 3.7, 2.1, 2.0,
                    1.5
                ]
            }],

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                    pointStart: null,
                    marker: {
                        enabled: false,
                    },
                    fillOpacity: 0.1
                },
            },
        });
    }

    // D03. 재무제표-막대 /툴팁
    if ($('#chart_financials_column').length) {
        Highcharts.chart('chart_financials_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_financials_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['19.09/30', '19.010/30', '19.11/30', '19.12/30', '20.01/30',],
                crosshair: true
            }],

            yAxis: {
                title: {
                    text: null
                },
                lineColor: null,
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: null,
                showFirstLabel: false,
                labels: {
                    enabled: false
                }
            },

            title: {
                text: null
            },

            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            series: [{
                name: '매출액',
                data: [87, 45, 75]
            }, {
                name: '영업이익',
                data: [85, 24, 42]
            }, {
                name: '순이익',
                data: [64, 34, 24]
            }, {
                name: '자산총계',
                data: [264, 134, 324]
            }, {
                name: '부채총계',
                data: [364, 234, 124]
            }, {
                name: '자본총계',
                data: [164, 234, 324]
            }, {
                name: '영업활동',
                data: [364, 234, 124]
            }, {
                name: '투자활동',
                data: [64, 34, 24]
            }, {
                name: '재무활동',
                data: [64, 34, 24]
            }],

            plotOptions: {
                series: {
                    marker: {
                        enabled: false,
                    },
                    dataLabels: {
                        enabled: true,
                    }
                },
            },
        });
    }

    // D04. 투자매력 배당매력-막대/툴팁
    if ($('#chart_charm_column').length) {

        Highcharts.chart('chart_charm_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['시가배당률(%))'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_column_2').length) {

        Highcharts.chart('chart_charm_column_2', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_column_2',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['배당성향(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_column_3').length) {

        Highcharts.chart('chart_charm_column_3', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_column_3',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['순이익성장률(%))'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_column_4').length) {

        Highcharts.chart('chart_charm_column_4', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_column_4',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['잉여현금성장률(%))'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D05. 투자매력 사업독점력-막대 / 툴팁
    if ($('#chart_charm2_column').length) {

        Highcharts.chart('chart_charm2_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm2_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['5년 평균ROE(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm2_column_2').length) {

        Highcharts.chart('chart_charm2_column_2', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm2_column_2',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['5년 평균 영업이익률(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm2_column_3').length) {

        Highcharts.chart('chart_charm2_column_3', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm2_column_3',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['5년 평균 매출액성장률(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D05-A (금융)투자매력 > 사업독점력-막대/툴팁
    if ($('#chart_fincharm_column').length) {

        Highcharts.chart('chart_fincharm_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_fincharm_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['5년 평균ROE(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_fincharm_column_2').length) {

        Highcharts.chart('chart_fincharm_column_2', {
            chart: {
                type: 'column',
                renderTo: 'chart_fincharm_column_2',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['5년 평균 영업이익률(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_fincharm_column_3').length) {

        Highcharts.chart('chart_fincharm_column_3', {
            chart: {
                type: 'column',
                renderTo: 'chart_fincharm_column_3',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['5년 평균 자산 성장률(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D06. 투자매력 > 재무안전성-막대/툴팁
    if ($('#chart_charm_stability_column').length) {

        Highcharts.chart('chart_charm_stability_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_stability_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['부채비율(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_stability_column_2').length) {

        Highcharts.chart('chart_charm_stability_column_2', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_stability_column_2',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['유동비율(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_stability_column_3').length) {

        Highcharts.chart('chart_charm_stability_column_3', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_stability_column_3',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['이자보상배수(배)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_stability_column_4').length) {

        Highcharts.chart('chart_charm_stability_column_4', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_stability_column_4',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['차입금비중(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_stability_column_5').length) {

        Highcharts.chart('chart_charm_stability_column_5', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_stability_column_5',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['금융비용(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D06-A. (금융)투자매력 > 재무안전성-막대/툴팁
    if ($('#chart_fincharm_stability_column').length) {

        Highcharts.chart('chart_fincharm_stability_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_fincharm_stability_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['자기자본비율(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D07. 투자매력 > 수익성장성-막대/툴팁
    if ($('#chart_charm_revenue_column').length) {

        Highcharts.chart('chart_charm_revenue_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_revenue_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['5년 평균 ROE(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_revenue_column_2').length) {

        Highcharts.chart('chart_charm_revenue_column_2', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_revenue_column_2',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['순이익성장률(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D08. 투자매력 > 현금창출력-막대/툴팁
    if ($('#chart_charm_cash_column').length) {

        Highcharts.chart('chart_charm_cash_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_cash_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['잉여현금흐름비율(%)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_cash_column_2').length) {

        Highcharts.chart('chart_charm_cash_column_2', {
            chart: {
                type: 'column',
                renderTo: 'v',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['주가현금흐름배수(배)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }
    if ($('#chart_charm_cash_column_3').length) {

        Highcharts.chart('chart_charm_cash_column_3', {
            chart: {
                type: 'column',
                renderTo: 'chart_charm_cash_column_3',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['영업활동현금(백만달러) / 정수'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D08-A. (금융)투자매력 > 현금창출력-막대/툴팁
    if ($('#chart_fincharm_cash_column').length) {

        Highcharts.chart('chart_fincharm_cash_column', {
            chart: {
                type: 'column',
                renderTo: 'chart_fincharm_cash_column',
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
                height: 180,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },

            colors: ["#00CCBD", "#2C235D", "#6C63FF"],

            tooltip: {
                shared: true,
                crosshairs: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
            },

            xAxis: [{
                categories: ['영업활동현금(백만달러)'],
                crosshair: true,
                labels: {
                    style: {
                        color: '#8380A0',
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
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '업종평균',
                data: [100]
            }, {
                name: '전체평균',
                data: [300]
            }],

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
                        color: '#8380A0',
                        overflow: 'none',
                        format: '{point.y:,.2f}',
                    }
                }
            },
        });
    }

    // D09. 스파이더 차트 / 툴팁(숫자)
    if ($('#chart_spider').length) {

        Highcharts.chart('chart_spider', {
            chart: {
                polar: true,
                type: 'area',
                renderTo: 'chart_spider',
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
                plotShadow: false,
                marginTop: 80,    
                marginBottom: 80,
                marginLeft: 80,
                marginRight: 80,
                width: 380,
                height : 380,
            },           

            // colors: ["#6247A8"],

            title: {
                text: null
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 점</b><br/>'
            },

            pane: {
                size: '100%',
                center: ['50%', '50%'],
            },

            xAxis: {
                categories: ['배당매력<br>점수', '사업독점력<br>점수', '재무안전성<br>점수', '수익성장성<br>점수', '현금창출력<br>점수'],
                tickmarkPlacement: 'on',
                lineWidth: 0,
            },

            yAxis: {
                gridLineInterpolation: 'polygon',
                minorGridLineColor: '#E0E0E0',
                tickInterval: 1,                
                // min: 0,
                // max: 5,       
                labels: {
                    enabled: false
                }         
            },

            exporting: {
                enabled: false
            },

            credits: {
                enabled: false
            },

            legend: {
                enabled: false,
            },

            series: [{
                name: '투자매력도',
                data: [5, 0, 1, 5, 5],
                pointPlacement: 'on'
                
            }],

            plotOptions: {
                series: {
                    lineWidth: 1,
                    fillOpacity: 0.3
                }
            },

        });
    }

    // C09-A. 스파이더 - 목록/비교 미니차트
    if ($('#chart_spider_mini').length) {

        Highcharts.chart('chart_spider_mini', {
            chart: {
                polar: true,
                type: 'area',
                renderTo: 'chart_spider_mini',
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
                data: [1.5, 1.5, 2, 3, 5],
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

    //D010. 밸류에이션 스피드차트
    if ($('#containerservice2').length) {
        Highcharts.chart('containerservice2', {
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
                data: [3],
                yAxis: 0
            }]
        });
    }

    //D011. BIC차트
    /* 매출과이익 */
    if ($('#chart_BICchart011').length) {
        Highcharts.chart('chart_BICchart011', {
            chart: {
                type: 'column',
                zoomType: 'xy',
                renderTo: 'chart_BICchart011',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],
            title: {
                text: null,
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 백만달러</b><br/>',
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true,
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/>백만달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/>백만달러',
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

            series: [{
                style: {
                    fontSize: '0',
                },
                name: '매출액',
                type: 'column',
                data: [150, 126, 129, 234, 228, 121, 150, 126, 129, 234, 228, 121],

            }, {
                name: '영업이익',
                type: 'line',
                yAxis: 1,
                data: [49, 71.5, 20.4, 92, 44.0, 76.0, 49, 71.5, 20.4, 92, 44.0, 76.0],

            }, {
                name: '지배지분순이익',
                type: 'line',
                yAxis: 1,
                data: [19.9, 71.5, 106.4, 29.2, 14.0, 76.0, 19.9, 71.5, 106.4, 29.2, 14.0, 76.0],

            }],

            lang: {
                noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",
            },

            noData: {
                style: {
                    fontWeight: 'nomal',
                    fontSize: '1rem',
                    color: '#8380A0',
                    align: 'left'
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

    //D012. 이익률
    if ($('#chart_BICchart012').length) {
        Highcharts.chart('chart_BICchart012', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart012',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                text: null
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: {
                labels: {
                    format: '{value} %',
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

            series: [{
                type: 'line',
                data: [294, 71, 106, 429, 144.456, 656],
                name: '영업이익률',
            }, {
                type: 'line',
                data: [194.1, 95.6, 54.4, 29.9, 71.5, 106.4],
                name: '순이익률',
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

    //D013. 원가율
    if ($('#chart_BICchart013').length) {
        Highcharts.chart('chart_BICchart013', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart013',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> %',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                name: '매출원가율',
                data: [42, 52, 57, 69, 97, 11]
            }, {
                name: '영업비용비율',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82]
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

    //D014. 연구개발비
    if ($('#chart_BICchart014').length) {
        Highcharts.chart('chart_BICchart014', {
            chart: {
                type: 'column',
                zoomType: 'xy',
                renderTo: 'chart_BICchart014',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],
            title: {
                text: null,
            },

            tooltip: {
                shared: true,
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true,
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/>백만달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> %',
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

            series: [{
                style: {
                    fontSize: '0',
                },
                name: '연구개발비',
                type: 'column',
                data: [150, 126, 129, 234, 228, 121, 150, 126, 129, 234, 228, 121],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 백만달러</b><br/>',
                },
            }, {
                name: '연구개발비 비율',
                type: 'line',
                yAxis: 1,
                data: [49, 71.5, 20.4, 92, 44.0, 76.0, 49, 71.5, 20.4, 92, 44.0, 76.0],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>',
                },
            }],

            lang: {
                noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",
            },

            noData: {
                style: {
                    fontWeight: 'nomal',
                    fontSize: '1rem',
                    color: '#8380A0',
                    align: 'left'
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

    //D015. 부채비율과 유동비율    
    if ($('#chart_BICchart015').length) {
        Highcharts.chart('chart_BICchart015', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart015',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> %',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                name: '부채비율',
                data: [42, 52, 57, 69, 97, 11]
            }, {
                name: '유동비율',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82]
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

    //D016. 차입금&차입금비율
    if ($('#chart_BICchart016').length) {
        Highcharts.chart('chart_BICchart016', {
            chart: {
                type: 'column',
                zoomType: 'xy',
                renderTo: 'chart_BICchart016',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],
            title: {
                text: null,
            },

            tooltip: {
                shared: true,
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true,
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/>백만달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> %',
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

            series: [{
                style: {
                    fontSize: '0',
                },
                name: '차입금',
                type: 'column',
                data: [150, 126, 129, 234, 228, 121, 150, 126, 129, 234, 228, 121],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 백만달러</b><br/>',
                }
            }, {
                name: '차입금비율 비율',
                type: 'line',
                yAxis: 1,
                data: [49, 71.5, 20.4, 92, 44.0, 76.0, 49, 71.5, 20.4, 92, 44.0, 76.0],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>',
                }
            }],

            lang: {
                noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",
            },

            noData: {
                style: {
                    fontWeight: 'nomal',
                    fontSize: '1rem',
                    color: '#8380A0',
                    align: 'left'
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
    //D017. 영업이익&이자비용
    if ($('#chart_BICchart017').length) {
        Highcharts.chart('chart_BICchart017', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart017',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 백만달러</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> 백만달러',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                name: '영업이익',
                data: [42, 52, 57, 69, 97, 11]
            }, {
                name: '이자비용',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82]
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
    //D018. 이자보상배수
    if ($('#chart_BICchart018').length) {
        Highcharts.chart('chart_BICchart018', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart018',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                text: null
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
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

            series: [{
                type: 'line',
                data: [294, 71, 106, 429, 144.456, 656],
                name: '이자보상배수',
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
    //D019. 차입금&금융비용
    if ($('#chart_BICchart019').length) {
        Highcharts.chart('chart_BICchart019', {
            chart: {
                type: 'column',
                zoomType: 'xy',
                renderTo: 'chart_BICchart019',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],
            title: {
                text: null,
            },

            tooltip: {
                shared: true,
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true,
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/>백만달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> %',
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

            series: [{
                style: {
                    fontSize: '0',
                },
                name: '차입금',
                type: 'column',
                data: [150, 126, 129, 234, 228, 121, 150, 126, 129, 234, 228, 121],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 백만달러</b><br/>',
                },

            }, {
                name: '금융비용 비율',
                type: 'line',
                yAxis: 1,
                data: [49, 71.5, 20.4, 92, 44.0, 76.0, 49, 71.5, 20.4, 92, 44.0, 76.0],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>',
                },
            }],

            lang: {
                noData: "해당 데이터가 없습니다.<br> 데이터 선택 기간을 변경해 보세요.",
            },

            noData: {
                style: {
                    fontWeight: 'nomal',
                    fontSize: '1rem',
                    color: '#8380A0',
                    align: 'left'
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
    //D020. 자산구조
    if ($('#chart_BICchart020').length) {
        Highcharts.chart('chart_BICchart020', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart020',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 백만달러</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> 백만달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> 백만달러',
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

            series: [{
                name: '장기자본',
                data: [42, 52, 57, 69, 97, 11]
            }, {
                name: '자본총계',
                yAxis: 1,
                data: [126, 624, 412, 511, 310, 812]
            }, {
                name: '비유동자산',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82]
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
    //D021. 이익축적
    if ($('#chart_BICchart021').length) {
        Highcharts.chart('chart_BICchart021', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart021',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700", '#17a2b8'],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 백만달러</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> 백만달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> 백만달러',
                    style: {
                        color: ["#17a2b8"],
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

            series: [{
                name: '이익잉여금',
                data: [42, 52, 57, 69, 97, 11]
            }, {
                name: '당좌자산',
                yAxis: 1,
                data: [126, 624, 412, 511, 310, 812]
            }, {
                name: '장기투자자산',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82]
            }, {
                name: '유형자산',
                yAxis: 1,
                data: [116, 164, 142, 151, 130, 182]
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
    //D022. 주당배당금과 배당률
    if ($('#chart_BICchart022').length) {
        Highcharts.chart('chart_BICchart022', {
            chart: {
                type: 'column',
                zoomType: 'xy',
                renderTo: 'chart_BICchart022',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                name: '주당배당금',
                type: 'column',
                data: [117.0, 126.9, 129.5, 234.5, 228.2, 121.5],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 달러</b><br/>'
                },

            }, {
                name: '배당수익률',
                type: 'line',
                yAxis: 1,
                data: [49, 71.5, 20.4, 92, 44.0, 76.0],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
                },
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
    //D023. 배당성향
    if ($('#chart_BICchart023').length) {
        Highcharts.chart('chart_BICchart023', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart023',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value}</b><br/> %',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                name: '배당성향',
                data: [42, 52, 57, 69, 97, 11]
            }, {
                name: '배당수익률',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82]
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
    //D024. ROE 와 PBR
    if ($('#chart_BICchart024').length) {
        Highcharts.chart('chart_BICchart024', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart024',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                name: '자기자본이익률',
                data: [42, 52, 57, 69, 97, 11],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
                },
            }, {
                name: '주가순자산배수',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
                },
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
    //D025. ROE 듀퐁분석
    if ($('#chart_BICchart025').length) {
        Highcharts.chart('chart_BICchart025', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart025',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                name: '매출수익률',
                data: [42, 52, 57, 69, 97, 11],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
                },
            }, {
                name: '자산회전율',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
                },
            }, {
                name: '재무레버리지',
                yAxis: 1,
                data: [161, 164, 142, 151, 220, 182],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
                },
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
    //D026. ROA & ROE & ROIC
    if ($('#chart_BICchart026').length) {
        Highcharts.chart('chart_BICchart026', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart026',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} %',
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

            series: [{
                name: '총자산이익률',
                data: [42, 52, 57, 69, 97, 11],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
                },
            }, {
                name: '자기자본이익률',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
                },
            }, {
                name: '투하자본이익률',
                yAxis: 1,
                data: [161, 164, 142, 151, 220, 182],
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} %</b><br/>'
                },
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
    //D027. 운전자본 회전일수
    if ($('#chart_BICchart027').length) {
        Highcharts.chart('chart_BICchart027', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart027',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],

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
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} 일',
                    style: {
                        color: ["#623FA8"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
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

            series: [{
                type: 'line',
                data: [42, 52, 57, 69, 97, 11],
                name: '매출채권 회전일수',
            }, {
                type: 'line',
                data: [14.1, 9.6, 5.4, 9.9, 1.5, 06],
                name: '재고자산 회전일수',
            }, {
                type: 'line',
                data: [194.1, 95.6, 54.4, 29.9, 71.5, 106.4],
                name: '매입채무 회전일수',
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
    //D028. 현금회전일수
    if ($('#chart_BICchart028').length) {

    }
    //D029. 현금흐름표
    if ($('#chart_BICchart029').length) {
        Highcharts.chart('chart_BICchart029', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart029',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f}</b><br/>백만달러</b><br/>',
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: {
                labels: {
                    format: '{value}</b><br/>백만달러',
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

            series: [{
                type: 'line',
                data: [345, 71, 106, 129, 144, 176],
                name: '영업활동 현금흐름'
            }, {
                type: 'line',
                data: [16, 64, 42, 51, 30, 82],
                name: '투자활동 현금흐름',
            }, {
                type: 'line',
                data: [23, 63, 52, 31, 13, 34],
                name: '재무활동 현금흐름',
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
    //D030. 잉여현금흐름
    if ($('#chart_BICchart030').length) {
        Highcharts.chart('chart_BICchart030', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart030',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f}</b><br/>백만달러</b><br/>',
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} 백만달러',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }, { // Secondary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} 백만달러',
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

            series: [{
                type: 'line',
                data: [345, 71, 106, 129, 144, 176],
                name: '잉여현금흐름'
            }, {
                type: 'line',
                yAxis: 1,
                data: [16, 64, 42, 51, 30, 82],
                name: '지배지분 순이익',
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
    //D031. 잉여현금흐름 비율
    if ($('#chart_BICchart031').length) {
        Highcharts.chart('chart_BICchart031', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart031',
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
                marginBottom: 90,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#00CCBD", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f}</b><br/> %</b><br/>',
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{ // Primary yAxis
                title: {
                    text: null,
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: ["#00CCBD"],
                    }
                },
            }],

            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            series: [{
                type: 'line',
                data: [345, 71, 106, 129, 144, 176],
                name: '잉여현금흐름 비율'
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
    //D032. 주가수익배수
    if ($('#chart_BICchart032').length) {
        Highcharts.chart('chart_BICchart032', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart032',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
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

            series: [{
                name: '주가수익배수',
                data: [42, 52, 57, 69, 97, 11]
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
    //D033. 주가와 주당순이익
    if ($('#chart_BICchart033').length) {
        Highcharts.chart('chart_BICchart033', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart033',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 달러</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: {
                labels: {
                    format: '{value} 달러',
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

            series: [{
                name: '주당순이익',
                data: [42, 52, 57, 69, 97, 11]
            }, {
                name: '주가',
                type: 'line',
                data: [72, 32, 37, 69, 27, 31]
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
    //D034. 주가순자산배수
    if ($('#chart_BICchart034').length) {
        Highcharts.chart('chart_BICchart034', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart034',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8"],

            title: {
                text: null
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
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

            series: [{
                name: '주가순자산배수',
                data: [42, 52, 57, 69, 97, 11]
            }],

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
    //D035. 주가와 주당순자산
    if ($('#chart_BICchart035').length) {
        Highcharts.chart('chart_BICchart035', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart035',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 달러</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
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

            series: [{
                type: 'line',
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                name: '주당순자산'
            }, {
                name: '주가',
                type: 'line',
                yAxis: 1,
                data: [62, 53, 35, 34, 23, 12]
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
    //D036. 주가현금흐름배수(PCR)
    if ($('#chart_BICchart036').length) {
        Highcharts.chart('chart_BICchart036', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart036',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{
                labels: {
                    format: '{value} 배',
                    style: {
                        color: ["#623FA8"],
                    }
                },
                title: {
                    text: null,
                },
            }],

            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            series: [{
                type: 'line',
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                name: '주가현금흐름배수'
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
    //D037. 주가&주당현금흐름
    if ($('#chart_BICchart037').length) {
        Highcharts.chart('chart_BICchart037', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart037',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 달러</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
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

            series: [{
                type: 'line',
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                name: '주당현금흐름'
            }, {
                name: '주가',
                type: 'line',
                yAxis: 1,
                data: [62, 53, 35, 34, 23, 12]
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
    //D038. 주가매출액배수(PSR)
    if ($('#chart_BICchart038').length) {
        Highcharts.chart('chart_BICchart038', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart038',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{
                labels: {
                    format: '{value} 배',
                    style: {
                        color: ["#623FA8"],
                    }
                },
                title: {
                    text: null,
                },
            }],

            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            series: [{
                type: 'line',
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                name: '주가매출액배수'
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
    //D039. 주가&주당매출액
    if ($('#chart_BICchart039').length) {
        Highcharts.chart('chart_BICchart039', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart039',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 달러</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
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
            }],

            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            series: [{
                type: 'line',
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                name: '주당매출액'
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
    //D040. EV/EBITDA
    if ($('#chart_BICchart040').length) {
        Highcharts.chart('chart_BICchart040', {
            chart: {
                type: 'line',
                zoomType: 'xy',
                renderTo: 'chart_BICchart040',
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
                marginBottom: 70,
                height: 300,
                plotBorderColor: null,
                plotBorderWidth: null,
                plotShadow: false,
            },

            colors: ["#623FA8", "#FF9700"],

            title: {
                style: {
                    fontSize: '0',
                },
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name} : <b>{point.y:,.0f} 배</b><br/>'
            },

            xAxis: [{
                categories: ['2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
                crosshair: true
            }],

            yAxis: [{
                labels: {
                    format: '{value} 배',
                    style: {
                        color: ["#623FA8"],
                    }
                },
                title: {
                    text: null,
                },
            }],

            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            series: [{
                type: 'line',
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                name: 'EV/EBITDA'
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
});