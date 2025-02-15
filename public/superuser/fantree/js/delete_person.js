document.getElementById('nodeModalBody').querySelector('#nodeDelete').addEventListener('click', (event) => {
  delete_person()
});

function delete_person() {

    var personInfo = selectedPerson;

    // prevent delete root person
    if(personInfo.order == 0){
      show_toast('danger', 'error', "can't delete root person, just edit it !")
      return false;
    }

    // test if person has parent
    if(person_parent_length(personInfo) > 0){
      show_toast('danger', 'error', "can't delete person, first delete parents !")
      return false;
    }
    


    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
            var formDeletePerson = document.getElementById('formDeletePerson');
            formDeletePerson.querySelector('.person_id').value = personInfo.id;
            console.log(formDeletePerson)
            formDeletePerson.submit();
        }
      });
}