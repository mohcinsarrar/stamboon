// click on edit image button, load image placeholder and create image editor
document.getElementById('importimagebtn').addEventListener('click', function handleClick(event) {
    document.getElementById('img_placeholder_container').classList.remove("d-none");
    document.getElementById('img_placeholder').src = "";
    var sex = this.getAttribute('data-sex');
    var id = this.getAttribute('data-id');
    var selected_image = this.getAttribute('data-image');
    var portraits_checks = "";
    var images = [];
    var image = "";
    if(sex != 'null'){
    for (var i = 1; i <= 4; i++) {
        image = '/storage/portraits/' + sex + i + '.jpg';
        images.push(sex + i + '.jpg');
        portraits_checks += `<div class="col-md mb-md-0 mb-2">
            <div class="form-check custom-option custom-option-image custom-option-image-check rounded-circle">
              <input class="form-check-input customimagescheckbox" 
                     type="checkbox" 
                     value="${i}" 
                     id="customCheckboxImg${i}" 
                     name="imagescheckbox[]" 
                     ${selected_image ==  sex + i +".jpg"? "checked" : ""}
                     />
              <label class="form-check-label custom-option-content" for="customCheckboxImg${i}">
                <span class="custom-option-body">
                  <img class="rounded-circle d-block mx-auto" src="${image}" alt="img${i}" style="height: 255px;object-fit: cover;"/>
                </span>
              </label>
            </div>
          </div>`;
    }
    document.getElementById('portrait_check_container').innerHTML = portraits_checks
    }
    else{
        document.getElementById('img_placeholder_container').classList.add("d-none");
    }
    // inset image if exist
    var img_placeholder = document.getElementById('img_placeholder');
    var uploadInput = document.getElementById('upload_image');
    if (selected_image != 'null') {
        if (!images.includes(selected_image)) {
            img_placeholder.src = '/storage/portraits/' + selected_image
        }
    }
    //
    // create image editor
    render_imageditor()


    var myModal = new bootstrap.Modal(document.getElementById('modalCenter'));
    myModal.show();
});


// 
// change image editor when load new image
document.getElementById('upload_image').addEventListener('change', function() {
    var img_placeholder = document.getElementById('img_placeholder');
    var uploadInput = document.getElementById('upload_image')
    var file = uploadInput.files[0]; // Get the selected file

    if (file) {
        // Create a FileReader object
        var reader = new FileReader();

        // Set up a function to run when the file is loaded
        reader.onload = function(e) {
            img_placeholder.src = e.target
                .result; // Set the src attribute of the <img> tag with the loaded image data
            render_imageditor();
        };

        // Read the uploaded file as a data URL
        reader.readAsDataURL(file);


    }
});

// save placeholder image
$(document).on("click", "#save_img_placeholder", function() {
    var choix = $('input[name="imagescheckbox[]"]:checked').val();
    var id = $('#formUpdatePerson #person_id').val()
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/fanchart/updateimageplacholder",
        type: 'POST',
        data: {
            'choix': choix,
            'id': id
        },
        encode: true,
        dataType: 'json',
        success: function(data) {
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

        },
        error: function(xhr, status, error) {
            alert_msg('error', error)
            return null;
        }
    });
});