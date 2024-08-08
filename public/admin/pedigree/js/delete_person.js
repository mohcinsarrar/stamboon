function delete_person() {
    var personInfo = selectedPerson;
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
            formDeletePerson.querySelector('.person_id').value = personInfo.personId;
            formDeletePerson.submit();
        }
      });
}