'use strict';

// chnage chart when change generation generations
document.querySelector('#generations').addEventListener('change', function () {
    generation = parseInt(this.value)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/fanchart/change_generations",
        type: 'POST',
        encode: true,
        dataType: 'json',
        data: {
            'generation': generation
        },
        success: function(data) {
            if (data.error == false) {
                show_toast('success', 'Success', data.msg)
                draw_chart(generation, fanChart)
            } else {
                show_toast('danger', 'Error', data.msg)
            }
        },
        error: function(xhr, status, error) {
            show_toast('danger','Error', error)
            return null;
        }
    });


});

// change gradient colors 
document.querySelector('#template').addEventListener('change', function() {
    var template = this.value;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/fanchart/change_template",
        type: 'POST',
        encode: true,
        dataType: 'json',
        data: {
            'template': template
        },
        success: function(data) {
            if (data.error == false) {
                show_toast('success', 'Success', data.msg)
                if (template == "gradient") {
                    fanChart.configuration.showColorGradients = true;
                    generation = parseInt($('#generations option:selected').val())
                    draw_chart(generation, fanChart)
                } else {
                    fanChart.configuration.showColorGradients = false;
                    generation = parseInt($('#generations option:selected').val())
                    draw_chart(generation, fanChart)
                }
            } else {
                show_toast('danger', 'Error', data.msg)
            }
        },
        error: function(xhr, status, error) {
            show_toast('danger','Error', error)
            return null;
        }
    });

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