"use strict";
!function(){
    let cardColor, headingColor, textMuted, bodyColor, borderColor, theme;

    theme = isDarkStyle ? (
        cardColor = config.colors_dark.cardColor,
        headingColor = config.colors_dark.headingColor,
        textMuted = config.colors_dark.textMuted,
        bodyColor = config.colors_dark.bodyColor,
        borderColor = config.colors_dark.borderColor,
        "dark"
    ) : (
        cardColor = config.colors.cardColor,
        headingColor = config.colors.headingColor,
        textMuted = config.colors.textMuted,
        bodyColor = config.colors.bodyColor,
        borderColor = config.colors.borderColor,
        "light"
    );

    // Radial Bar Chart Customization
    document.querySelectorAll(".chart-report").forEach(function(chartElement) {
        let chartColor = config.colors[chartElement.dataset.color];
        let chartSeries = chartElement.dataset.series;
    
        let radialBarOptions = {
            chart: { height: 120, width: 120, type: "radialBar" },
            plotOptions: {
                radialBar: {
                    hollow: { size: "50%" },
                    dataLabels: { show: false }, // Hide percentage labels
                    track: { background: borderColor }
                }
            },
            stroke: { lineCap: "round" },
            colors: [chartColor],
            grid: { padding: { top: 15, bottom: -5, left: 0, right: 0 } },
            series: [chartSeries],
            labels: [""]
        };
    
        new ApexCharts(chartElement, radialBarOptions).render();
    });
    

    // Analytics Bar Chart Customization
    let analyticsBarChartElement = document.querySelector("#analyticsBarChart");

    if (analyticsBarChartElement) {
        let analyticsBarChartOptions = {
            chart: { height: 300, type: "bar", toolbar: { show: true } },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "15%",
                    borderRadius: 5
                }
            },
            dataLabels: { enabled: true },
            colors: [config.colors.primary, config.colors_label.primary],
            series: [
                { name: "2021", data: [10, 12, 18, 25, 19, 27, 35, 32, 15] },
                { name: "2020", data: [8, 9, 15, 20, 14, 22, 29, 27, 13] }
            ],
            grid: { borderColor: borderColor, padding: { bottom: 0 } },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                axisBorder: { show: true },
                axisTicks: { show: true },
                labels: { style: { colors: textMuted } }
            },
            yaxis: {
                min: 0,
                max: 40,
                tickAmount: 4,
                labels: { style: { colors: textMuted } }
            },
            legend: { show: true },
            tooltip: { y: { formatter: function(value) { return "$ " + value + "K"; } } }
        };

        new ApexCharts(analyticsBarChartElement, analyticsBarChartOptions).render();
    }

    // Referral Line Chart Customization
    let referralLineChartElement = document.querySelector("#referralLineChart");

    if (referralLineChartElement) {
        let referralLineChartOptions = {
            series: [{ data: [0, 150, 25, 100, 15, 149] }],
            chart: { height: 120, type: "line", toolbar: { show: false } },
            markers: {
                size: 7,
                colors: "transparent",
                strokeColors: "transparent",
                strokeWidth: 3,
                discrete: [{
                    fillColor: cardColor,
                    seriesIndex: 0,
                    dataPointIndex: 5,
                    strokeColor: config.colors.success,
                    strokeWidth: 3,
                    size: 8,
                    radius: 4
                }],
                hover: { size: 8 }
            },
            dataLabels: { enabled: false },
            stroke: { width: 3, curve: "smooth" },
            grid: { show: false, padding: { top: -15, bottom: -15 } },
            colors: [config.colors.success],
            xaxis: { show: false, axisBorder: { show: false }, axisTicks: { show: false }, labels: { show: false } },
            yaxis: { labels: { show: false } }
        };

        new ApexCharts(referralLineChartElement, referralLineChartOptions).render();
    }

    // Conversion Bar Chart Customization
    let conversionBarChartElement = document.querySelector("#conversionBarchart");

    if (conversionBarChartElement) {
        let conversionBarChartOptions = {
            chart: { height: 120, stacked: true, type: "bar", toolbar: { show: true }, sparkline: { enabled: false } },
            plotOptions: {
                bar: {
                    columnWidth: "30%",
                    borderRadius: 4
                }
            },
            colors: [config.colors.primary, config.colors.warning],
            series: [
                { name: "New Clients", data: [80, 160, 240, 220, 40, 60, 160, 190, 55, 160, 250, 150, 80, 40, 70, 130] },
                { name: "Retained Clients", data: [-110, -65, -45, -130, -80, -50, -70, -60, -80, -35, -70, -50, -60, -80, -50, -60] }
            ]
        };

        new ApexCharts(conversionBarChartElement, conversionBarChartOptions).render();
    }

    // Impression Donut Chart
    let impressionDonutChartElement = document.querySelector("#impressionDonutChart");

    if (impressionDonutChartElement) {
        let impressionDonutChartOptions = {
            chart: { height: 185, fontFamily: "IBM Plex Sans", type: "donut" },
            dataLabels: { enabled: false },
            grid: { padding: { bottom: -10 } },
            series: [80, 30, 60],
            labels: ["Social", "Email", "Search"],
            stroke: { width: 0, lineCap: "round" },
            colors: [config.colors.primary, config.colors.info, config.colors.warning],
            plotOptions: {
                pie: {
                    donut: {
                        size: "90%",
                        labels: {
                            show: true,
                            name: { fontSize: "0.938rem", offsetY: 20 },
                            value: {
                                show: true,
                                fontSize: "1.625rem",
                                fontFamily: "Rubik",
                                fontWeight: "500",
                                color: headingColor,
                                offsetY: -20,
                                formatter: function(val) { return val; }
                            },
                            total: {
                                show: true,
                                label: "Impression",
                                color: bodyColor,
                                formatter: function(chart) {
                                    return chart.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                show: true,
                position: "bottom",
                horizontalAlign: "center",
                labels: { colors: bodyColor, useSeriesColors: false },
                markers: { width: 10, height: 10, offsetX: -3 }
            }
        };

        new ApexCharts(impressionDonutChartElement, impressionDonutChartOptions).render();
    }

    // Conversation Line Chart
    let conversationChartElement = document.querySelector("#conversationChart");

    if (conversationChartElement) {
        let conversationChartOptions = {
            series: [{ data: [50, 100, 0, 60, 20, 30] }],
            chart: { height: 40, type: "line", zoom: { enabled: false }, sparkline: { enabled: true }, toolbar: { show: false } },
            dataLabels: { enabled: false },
            tooltip: { enabled: false },
            stroke: { curve: "smooth", width: 3 },
            grid: { show: false, padding: { top: 5, left: 10, right: 10, bottom: 5 } },
            colors: [config.colors.primary],
            fill: {
                type: "gradient",
                gradient: {
                    shade: theme,
                    type: "horizontal",
                    opacityFrom: 0,
                    opacityTo: 0.9,
                    stops: [0, 30, 70, 100]
                }
            },
            xaxis: { labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { labels: { show: false } }
        };

        new ApexCharts(conversationChartElement, conversationChartOptions).render();
    }

    // Income Line Chart
    let incomeChartElement = document.querySelector("#incomeChart");

    if (incomeChartElement) {
        let incomeChartOptions = {
            series: [{ data: [40, 70, 38, 90, 40, 65] }],
            chart: { height: 40, type: "line", zoom: { enabled: false }, sparkline: { enabled: true }, toolbar: { show: false } },
            dataLabels: { enabled: false },
            tooltip: { enabled: false },
            stroke: { curve: "smooth", width: 3 },
            grid: { show: false, padding: { top: 10, left: 10, right: 10, bottom: 0 } },
            colors: [config.colors.warning],
            fill: {
                type: "gradient",
                gradient: {
                    shade: theme,
                    type: "horizontal",
                    opacityFrom: 0,
                    opacityTo: 0.9,
                    stops: [0, 30, 70, 100]
                }
            },
            xaxis: { labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { labels: { show: false } }
        };

        new ApexCharts(incomeChartElement, incomeChartOptions).render();
    }

    // Registrations Bar Chart
    let registrationsBarChartElement = document.querySelector("#registrationsBarChart");

    if (registrationsBarChartElement) {
        let registrationsBarChartOptions = {
            chart: { height: 95, width: 155, type: "bar", toolbar: { show: false } },
            plotOptions: {
                bar: {
                    barHeight: "80%",
                    columnWidth: "50%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 2,
                    distributed: true
                }
            },
            grid: { show: false, padding: { top: -20, bottom: -20, left: 0, right: 0 } },
            colors: [
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors.warning,
                config.colors_label.warning,
                config.colors_label.warning
            ],
            dataLabels: { enabled: false },
            series: [{ data: [30, 55, 45, 95, 70, 50, 65] }],
            legend: { show: false },
            xaxis: { categories: ["M", "T", "W", "T", "F", "S", "S"], axisBorder: { show: false }, axisTicks: { show: false }, labels: { show: false } },
            yaxis: { labels: { show: false } }
        };

        new ApexCharts(registrationsBarChartElement, registrationsBarChartOptions).render();
    }

    // Sales Bar Chart
    let salesChartElement = document.querySelector("#salesChart");

    if (salesChartElement) {
        let salesChartOptions = {
            chart: { height: 120, parentHeightOffset: 0, type: "bar", toolbar: { show: false } },
            plotOptions: {
                bar: {
                    barHeight: "100%",
                    columnWidth: "25px",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 5,
                    distributed: true,
                    colors: { backgroundBarColors: [config.colors_label.primary, config.colors_label.primary, config.colors_label.primary, config.colors_label.primary], backgroundBarRadius: 5 }
                }
            },
            grid: { show: false, padding: { top: -30, left: -12, bottom: 10 } },
            colors: [config.colors.primary],
            dataLabels: { enabled: false },
            series: [{ data: [60, 35, 25, 75, 15, 42, 85] }],
            legend: { show: false },
            xaxis: {
                categories: ["S", "M", "T", "W", "T", "F", "S"],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: headingColor, fontSize: "13px" } }
            },
            yaxis: { labels: { show: false } },
            responsive: [
                { breakpoint: 1440, options: { plotOptions: { bar: { columnWidth: "30%" } } } },
                { breakpoint: 1200, options: { plotOptions: { bar: { columnWidth: "15%" } } } },
                { breakpoint: 768, options: { plotOptions: { bar: { columnWidth: "12%" } } } },
                { breakpoint: 450, options: { plotOptions: { bar: { columnWidth: "19%" } } } }
            ]
        };

        new ApexCharts(salesChartElement, salesChartOptions).render();
    }



  
// Store the chart instance globally or in a scope where it can be accessed

}();

// Function to get the current series value
function getGrowthRadialChartValue(currentvalue='30') {

    let cardColor, headingColor, textMuted, bodyColor, borderColor, theme;

    theme = isDarkStyle ? (
        cardColor = config.colors_dark.cardColor,
        headingColor = config.colors_dark.headingColor,
        textMuted = config.colors_dark.textMuted,
        bodyColor = config.colors_dark.bodyColor,
        borderColor = config.colors_dark.borderColor,
        "dark"
    ) : (
        cardColor = config.colors.cardColor,
        headingColor = config.colors.headingColor,
        textMuted = config.colors.textMuted,
        bodyColor = config.colors.bodyColor,
        borderColor = config.colors.borderColor,
        "light"
    );

    let growthRadialChartInstance;
 
    let growthRadialChartElement = document.querySelector("#growthRadialChart");

    if (growthRadialChartElement) {
        let growthRadialChartOptions = {
            chart: { 
                id: 'growthRadialChart', // Ensure the chart ID is set
                height: 230, 
                fontFamily: "IBM Plex Sans", 
                type: "radialBar", 
                sparkline: { show: true } 
            },
            grid: { show: false, padding: { top: -25 } },
            plotOptions: {
                radialBar: {
                    size: 100,
                    startAngle: -135,
                    endAngle: 135,
                    offsetY: 10,
                    hollow: { size: "55%" },
                    track: { strokeWidth: "50%", background: borderColor },
                    dataLabels: {
                        value: {
                            offsetY: -15,
                            color: headingColor,
                            fontFamily: "Rubik",
                            fontWeight: 500,
                            fontSize: "26px"
                        },
                        name: {
                            fontSize: "15px",
                            color: bodyColor,
                            offsetY: 24
                        }
                    }
                }
            },
            colors: [config.colors.danger],
            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "horizontal",
                    shadeIntensity: 0.5,
                    gradientToColors: [config.colors.primary],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            stroke: { dashArray: 3 },
            series: [currentvalue],
            labels: ["Registred"]
        };

        // Initialize the chart and store the instance
        growthRadialChartInstance = new ApexCharts(growthRadialChartElement, growthRadialChartOptions);
        growthRadialChartInstance.render();
    }

    // alert('test');  // This will show an alert for testing purposes

    // Check if the chart instance is available
    if (growthRadialChartInstance) {
        // Get the series value from the chart instance
        let series = growthRadialChartInstance.w.config.series;
        console.log('Current Value:', series);  // Logs the current value of the chart
    } else {
        console.error('Chart instance is not available.');
    }
}

   