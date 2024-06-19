'use strict';

// chnage chart when change generation generations
document.querySelector('#generations').addEventListener('change', function () {
    generation = parseInt(this.value)
    draw_chart(generation, fanChart)
});

// change gradient colors 
document.querySelector('#template').addEventListener('change', function() {
    if (this.value == "gradient") {
        fanChart.configuration.showColorGradients = true;
        generation = parseInt($('#generations option:selected').val())
        draw_chart(generation, fanChart)
    } else {
        fanChart.configuration.showColorGradients = false;
        generation = parseInt($('#generations option:selected').val())
        draw_chart(generation, fanChart)
    }
});

// toggle empty node visibility
document.querySelector('#show_empty_node').addEventListener('change', function() {
    if (this.checked) {
        fanChart.configuration.hideEmptySegments = false;
        generation = parseInt($('#generations option:selected').val())
        draw_chart(generation, fanChart)
    } else {
        fanChart.configuration.hideEmptySegments = true;
        generation = parseInt($('#generations option:selected').val())
        draw_chart(generation, fanChart)
    }
});