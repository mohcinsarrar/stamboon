$(document).on("click", "#import-gedcom", function() {
    const file = $('#gedcom').prop('files')[0];
    var modalElement = document.getElementById('uploadFile');
    var modal = bootstrap.Modal.getInstance(modalElement);


    if (!file) {
        show_toast('error', 'error', 'No file selected')
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    Swal.fire({
        title: 'Are you sure?',
        text: "If you import a new Gedcom file, you will lose your current family tree",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, import it!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: "/pedigree/importgedcom",
                type: 'POST',
                data: formData,
                encode: true,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    modal.hide();
                    if (data.error == false) {
                        show_toast('success', 'upload file', data.msg)
                        draw_tree()
                    } else {
                        show_toast('error', 'error', data.error)
                    }
        
                },
                error: function(xhr, status, error) {
                    modal.hide();
                    if ('responseJSON' in xhr) {
                        show_toast('error', 'error', xhr.responseJSON.message)
                    } else {
                        show_toast('error', 'error', error)
                    }
        
                    return null;
                }
            });
        }
      });

    

});