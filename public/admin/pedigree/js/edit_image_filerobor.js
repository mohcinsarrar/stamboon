function edit_image(personInfo){

    var myModal = new bootstrap.Modal(document.getElementById('modalEditImage'))
    myModal.show()

    var image = document.createElement('img');
    if(personInfo.photo != null){
        image.src = personInfo.photo;
        render_imageditor(personInfo,image);
    }
    

    // change image editor when load new image
    document.getElementById('upload_image').addEventListener('change', function() {
        var uploadInput = document.getElementById('upload_image')
        var file = uploadInput.files[0]; // Get the selected file

        if (file) {
            // Create a FileReader object
            var reader = new FileReader();

            // Set up a function to run when the file is loaded
            reader.onload = function(e) {
                render_imageditor(personInfo,e.target.result);
            };

            // Read the uploaded file as a data URL
            reader.readAsDataURL(file);


        }
    });
}






// render image editor
function render_imageditor(personInfo,source) {
    var imageEditor = window.FilerobotImageEditor
    var TABS = imageEditor.TABS
    var TOOLS = imageEditor.TOOLS

    config = {
        source: source,
        tabsIds: [TABS.ADJUST, TABS.FILTERS],
        showBackButton: false,
        defaultSavedImageName: "Portrait",
        defaultSavedImageType: 'png',
        useBackendTranslations : false,
        crop: {
            minWidth: 500,
            minWHeight: 500,
            noPresets: true,
            autoResize: false,
        }
    }
    var filerobotImageEditor = new imageEditor(
        document.querySelector('#photo_editor_container'),
        config,
    );

    filerobotImageEditor.render({
        onClose: (closingReason) => {
        },
        onSave: (imageData, imageDesignState) => {
            // get person id from personInfo and edd it with save image request
            imageData.id = personInfo.personId;

            
            // create an xhr request
            let request = new XMLHttpRequest();

            // open the request to the "/pedigree/saveimage" url
            request.open("POST", "/pedigree/saveimage", true);

            // add content-type and csr token to header
            request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
            request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // callback on request sent
            request.onload = function() {
                
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
            request.onerror = function(xhr, status, error) {
                show_toast('danger', 'error', error)
                return null;
            };

            // send the request with image data
            request.send(JSON.stringify(imageData));
        }
    });


    // perview edited image
    document.getElementById('preview_image').addEventListener('click', function handleClick(event) {
        console.log('fff')
        var image = filerobotImageEditor.getCurrentImgData().imageData.imageBase64
        document.querySelector('#previewImage #previewImageContainer').src = image;
        var myModal = new bootstrap.Modal(document.getElementById('previewImage'));
        myModal.show();
    });
}

