function delete_person() {
    var personInfo = selectedPerson;
    // create a parent id
    if(personInfo.type == 'spouse'){
      const parentId = personInfo.personBeforeSpouseId+'-'+personInfo.personId
      // search all children where parentID eual to the created id
      const children = familyData.filter((p) => p.parentId === parentId);
      if(children.length > 0){
        show_toast('warning', 'warning', "To delete a person you must delete all children and all spouses","10000");
        return false;
      }
    }

    if(personInfo.type == 'person'){
      if(personInfo.spouseIds != undefined && personInfo.spouseIds.length > 0){
        var canDelete = true;

        // test if person has children
        personInfo.spouseIds.forEach((spouse, key, array) => { 
          var parentId = personInfo.personId+'-'+spouse
          var children = familyData.filter((p) => p.parentId === parentId);          
          if(children.length > 0){
            canDelete = false
            return false;
          }
        });

        // test if person has spouse
        if(personInfo.spouseIds.length > 0){
          canDelete = false
        }

        if(canDelete == false){
          show_toast('warning', 'warning', "To delete a person you must delete all children and all spouses","10000");
          return false;
        }
      }
      
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
            formDeletePerson.querySelector('.person_id').value = personInfo.personId;
            formDeletePerson.submit();
        }
      });
}