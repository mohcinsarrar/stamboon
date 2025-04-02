

$(document).on("click", "#save", function () {

  if(chart != undefined){
    editChartStatus()
    show_toast('success', 'success', "pedigree state saved !")
  }
});

