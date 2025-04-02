let selectedPerson;
let chart;
let familyData = [];
let treeConfiguration;
let constants = {
    rootId: "hidden_root",
}
let compact = false;
let date_format = '';


function get_pedigree_id2(){
  let pedigree_id = document.getElementById('main_graph').dataset.pedigreeid;
  return(pedigree_id);
}

$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  let pedigree_id = get_pedigree_id2();
  $.ajax({
    url: "/pedigree/settings/"+pedigree_id,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      if (data.error == false) {
        date_format = data.settings.default_date
      } else {
        show_toast('danger', 'error', data.error)
      }

    },
    error: function (xhr, status, error) {
      if ('responseJSON' in xhr) {
        show_toast('danger', 'error', xhr.responseJSON.message)
      } else {
        show_toast('danger', 'error', error)
      }

      return null;
    }
  });