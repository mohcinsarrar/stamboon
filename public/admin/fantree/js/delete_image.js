document.getElementById('nodeModalBody').querySelector('#nodeDeletePhoto').addEventListener('click', (event) => {
   delete_image()

});


function delete_image(){
    var personInfo = selectedPerson;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    result = $.ajax({
        url: "/fantree/deleteimage/"+fantree_id,
        type: 'POST',
        data: {
            'person_id' : personInfo.id
        },
        encode: true,
        dataType: 'json',
        success: function(data) {
            if (data.error == false) {
                const custommodal = document.getElementById('nodeModal');
                custommodal.style.display = 'none';
                show_toast('success', 'success', 'photo deleted')
                draw_tree()
                
            } else {
                show_toast('danger', 'error', "can't delete photo, please try again !")
            }

        },
        error: function(xhr, status, error) {
            console.log(error)
            show_toast('danger', 'error', "can't delete photo, please try again !")
            return null;
        }
    });


}