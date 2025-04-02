function nodeClicked(d) {
    
    // check if offcanvas is open
    
    if(preventOverlayModel()){
      return;
    }  
  
    // store on global variable
    var personInfo = d.data
    selectedPerson = personInfo
  
  
    // insert person info in nodeModal
    const modal = document.getElementById('nodeModal');
    const modalBody = document.getElementById('nodeModalBody');
  
  
    modalBody.querySelector('.name').innerHTML = personInfo.firstname.toUpperCase() + ' ' + personInfo.lastname.toUpperCase();
  
    modalBody.querySelector('.birth').innerHTML = parseDateGlobal(personInfo.birth, target_format = treeConfiguration.default_date, target_date_style = 'string', target_separator = " ", date_style = 'string', separator = ' ');
    modalBody.querySelector('.death').innerHTML = parseDateGlobal(personInfo.death, target_format = treeConfiguration.default_date, target_date_style = 'string', target_separator = " ", date_style = 'string', separator = ' ')
  
    if (personInfo.photo != undefined && personInfo.photo !== null) {
      modalBody.querySelector('.personImage').src = "/storage/portraits_fantree/" + personInfo.photo;
    }
    else {
      if (personInfo.gender == 'M') {
  
        modalBody.querySelector('.personImage').src = "/storage/placeholder_portraits_fantree/" + treeConfiguration.default_male_image + ".jpg";
      }
      else {
        modalBody.querySelector('.personImage').src = "/storage/placeholder_portraits_fantree/" + treeConfiguration.default_female_image + ".jpg";
      }
    }


    // check if person can add parents and if max generations not reached
    nodes = chart.selectAll(".node").data()
    const nodes_count = nodes.filter(obj => !obj.data || obj.data.id != undefined).length;

    if(person_parent_length(personInfo) == 2 || d.depth + 1 >= treeConfiguration.max_generation || nodes_count >= treeConfiguration.max_nodes){
      modalBody.querySelector('#addParentsContainer').style.display = 'none';
    }
    else{
      modalBody.querySelector('#addParentsContainer').style.display = 'block';
      document.getElementById('nodeModalBody').querySelector('#addParents').addEventListener('click', (event) => {
        add_parents()
      });
    }

    // check if person has photo
    if(personInfo.photo == undefined){
        modalBody.querySelector('#deletePhotoContainer').style.display = 'none';
    }
    else{
        modalBody.querySelector('#deletePhotoContainer').style.display = 'block';
    }
  
  
    // Get the node's position
    const node = document.querySelector('[data-personId="' + personInfo.id + '"]').getBoundingClientRect();
  
  
    // Position the modal
    modal.style.left = `${node.left}px`;
    modal.style.top = `${node.top}px`;
    modal.style.z_index = "100"
  
    // add listener for buttons
    document.getElementById('nodeModalBody').querySelector('#nodeEdit').addEventListener('click', (event) => {
      edit_person()
    });
  
    // Show the modal
    modal.style.display = 'block';
  
  
  
  }
  