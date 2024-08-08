
let filerobotImageEditor;
// event listener when upload image
document.getElementById('upload_image').addEventListener('change', function () {
    var uploadInput = document.getElementById('upload_image')
    var file = uploadInput.files[0];

    if (file) {
        if (file.size > 2000000) {
            show_toast('danger', 'error', 'Please upload file less than 2MB');
            return false;
        }

        // Create a FileReader object
        var reader = new FileReader();

        // Set up a function to run when the file is loaded
        reader.onload = function (e) {
            render_imageditor(e.target.result);
        };

        // Read the uploaded file as a data URL
        reader.readAsDataURL(file);

    }
});




function edit_image(personInfo) {

    var personInfo = selectedPerson;
    var myModal = new bootstrap.Modal(document.getElementById('modalEditImage'))
    myModal.show()

    var image = document.createElement('img');

    if (personInfo.photo != null) {
        image.src = "/storage/portraits/" + personInfo.photo;
        render_imageditor(image);
    }

}



// render image editor
function render_imageditor(source) {
    document.getElementById('save_image').disabled = false;
    var imageEditor = window.FilerobotImageEditor
    var TABS = imageEditor.TABS
    var TOOLS = imageEditor.TOOLS

    config = {
        source: source,
        tabsIds: [TABS.ADJUST, TABS.FILTERS],
        showBackButton: false,
        defaultSavedImageName: "Portrait",
        defaultSavedImageType: 'png',
        useBackendTranslations: false,
        removeSaveButton: true,
        crop: {
            noPresets: true,
            autoResize: false,
        }
    }

    filerobotImageEditor = new imageEditor(
        document.querySelector('#photo_editor_container'),
        config,
    );

    filerobotImageEditor.render({
        onClose: (closingReason) => {
        },
    });






}

// save image when click on save button
document.getElementById('save_image').addEventListener('click', function () {
    var personInfo = selectedPerson
    var imageData = filerobotImageEditor.getCurrentImgData().imageData;
    imageData.person_id = personInfo.personId;

    // create an xhr request
    let request = new XMLHttpRequest();

    // open the request to the "/pedigree/saveimage" url
    request.open("POST", "/pedigree/saveimage", true);

    // add content-type and csr token to header
    request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // callback on request sent
    request.onload = function () {

        // if request success
        if (this.status >= 200 && this.status < 400) {

            // get response data
            let data = JSON.parse(this.response);

            // if no error
            if (data.error == false) {
                // hide updateImge modal and custom modal


                const modal = document.getElementById('modalEditImage')
                const bsmodal = bootstrap.Modal.getInstance(modal)
                bsmodal.hide()

                const custommodal = document.getElementById('nodeModal');
                custommodal.style.display = 'none';

                show_toast('success', 'success', 'photo updated')

                // redraw graph
                draw_tree()

            } else {
                // if error
                show_toast('danger', 'error', data.msg)
            }
        } else {
            // if error
            show_toast('danger', 'error', "can't save photo, please try again !")
        }
    }

    // callback if request error
    request.onerror = function (xhr, status, error) {
        show_toast('danger', 'error', error)
        return null;
    };

    // send the request with image data
    request.send(JSON.stringify(imageData));

});


// destroy filerobot on close modal
document.getElementById('modalEditImage').addEventListener('hidden.bs.modal', event => {
    document.querySelector('#photo_editor_container').remove();
    // create new container
    let newDiv = document.createElement('div');
    newDiv.id = 'photo_editor_container';
    newDiv.style.width = '100%';
    newDiv.style.height = '600px';
    let parentDiv = document.getElementById('navs-top-upload');
    parentDiv.appendChild(newDiv);
    document.getElementById('upload_image').value = '';
    document.getElementById('save_image').disabled = true;
    document.getElementById('save_image_placeholder').disabled = true;
    const radioButtons = document.querySelectorAll('input[name="customRadioImage"]');
    radioButtons.forEach(radio => {
        radio.checked = false;
    });
    const customOptionImage = document.querySelectorAll('div.custom-option-image');
    customOptionImage.forEach(customOption => {
        customOption.classList.remove("checked");
    });

});



// select placeholder image

const radioButtons = document.querySelectorAll('input[name="customRadioImage"]');
const save_image_placeholder = document.getElementById('save_image_placeholder');

radioButtons.forEach(radio => {
    radio.addEventListener('click', function () {
        const checkedRadio = document.querySelector('input[name="customRadioImage"]:checked');
        save_image_placeholder.disabled = !checkedRadio;
    });
});

// save image
save_image_placeholder.addEventListener('click', function () {
    const checkedRadio = document.querySelector('input[name="customRadioImage"]:checked').value;
    var personInfo = selectedPerson
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/saveimage",
        type: 'POST',
        data: {
            'checkedImage': checkedRadio,
            'person_id' : personInfo.personId
        },
        encode: true,
        dataType: 'json',
        success: function(data) {
            if (data.error == false) {
                const modal = document.getElementById('modalEditImage')
                const bsmodal = bootstrap.Modal.getInstance(modal)
                bsmodal.hide()

                const custommodal = document.getElementById('nodeModal');
                custommodal.style.display = 'none';
                show_toast('success', 'success', 'photo updated')
                draw_tree()
            } else {
                show_toast('danger', 'error', "can't save photo, please try again !")
            }

        },
        error: function(xhr, status, error) {
            show_toast('danger', 'error', "can't save photo, please try again !")
            return null;
        }
    });
});