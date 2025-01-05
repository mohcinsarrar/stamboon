let selectedPerson;
let chart;
let familyData = [];
let treeConfiguration;
let constants = {
    rootId: "hidden_root",
}
let compact = false;
let date_format = '';

$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    url: "/pedigree/settings",
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      if (data.error == false) {
        date_format = data.settings.default_date
      } else {
        show_toast('error', 'error', data.error)
      }

    },
    error: function (xhr, status, error) {
      if ('responseJSON' in xhr) {
        show_toast('error', 'error', xhr.responseJSON.message)
      } else {
        show_toast('error', 'error', error)
      }

      return null;
    }
  });