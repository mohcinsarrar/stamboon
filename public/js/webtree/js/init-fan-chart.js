
'use strict';

// init the fanchart
function init_chart(config) {
    const fanChart = new WebtreesFanChart.FanChart(
        "#webtrees-fan-chart-container", {
        labels: config.labels,
        // generations: parseInt(storage.read("generations")),
        // fanDegree: parseInt(storage.read("fanDegree")),
        // defaultColor: data.defaultColor,
        // fontScale: parseInt(storage.read("fontScale")),
        hideEmptySegments: false,
        // showColorGradients: storage.read("showColorGradients"),
        // showParentMarriageDates: storage.read("showParentMarriageDates"),
        showImages: config.showImages,
        showSilhouettes: config.showSilhouettes,
        rtl: config.rtl,
        //innerArcs: 1,
        cssFiles: config.cssFiles
    }
    );
    return fanChart;
}

// load data from controller 
function draw_chart(generation, chart) {

    var url = "/fanchart/get/" + generation;
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            chart.draw(data.data);
        },
        error: function(xhr, status, error) {
            return null;
        }
    });

}


// msg alerts
function alert_msg(icon, title) {
    Swal.fire({
        icon: icon,
        title: title,
        customClass: {
            confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
    });
}