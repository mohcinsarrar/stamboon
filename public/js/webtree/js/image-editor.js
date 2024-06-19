'use strict';
function render_imageditor() {
    var imageEditor = window.FilerobotImageEditor
    var TABS = imageEditor.TABS
    var TOOLS = imageEditor.TOOLS
    var img_placeholder = document.querySelector('#img_placeholder')

    config = {
        source: img_placeholder,
        tabsIds: [TABS.ADJUST, TABS.FILTERS],
        showBackButton: false,
        defaultSavedImageName: "person",
        crop: {
            minWidth: 500,
            minHeight: 500,
            noPresets: true,
            ratio: 'custom',
        }
    }
    var filerobotImageEditor = new imageEditor(
        document.querySelector('#editor_container'),
        config,
    );

    filerobotImageEditor.render({
        onClose: (closingReason) => {
        },
        onSave: (imageData, imageDesignState) => {
            var id = $('#formUpdatePerson #person_id').val()
            imageData.id = id;
            let request = new XMLHttpRequest();
            request.open("POST", "/fanchart/saveimage", true);
            request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
            request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')
                .getAttribute('content'));
            request.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    let data = JSON.parse(this.response);
                    if (data.error == false) {
                        alert_msg('success', data.msg)
                        generation = parseInt($('#generations option:selected').val());
                        draw_chart(generation, fanChart)
                        var canvas = document.getElementById('offcanvasUpdatePerson')
                        var bsOffcanvas = bootstrap.Offcanvas.getInstance(canvas)
                        bsOffcanvas.hide()
                        var modal = document.getElementById('modalCenter')
                        var bsmodal = bootstrap.Modal.getInstance(modal)
                        bsmodal.hide()
                    } else {
                        alert_msg('error', data.msg)
                    }
                } else {
                    alert_msg('error', data.msg)
                }
            }
            request.onerror = function(xhr, status, error) {
                alert_msg('error', error)
                return null;
            };
            request.send(JSON.stringify(imageData));
        }
    });


    document.getElementById('preview_image').addEventListener('click', function handleClick(event) {
        var image = filerobotImageEditor.getCurrentImgData().imageData.imageBase64
        document.querySelector('#previewImage #previewImageContainer').src = image;
        var myModal = new bootstrap.Modal(document.getElementById('previewImage'));
        myModal.show();
    });
}
