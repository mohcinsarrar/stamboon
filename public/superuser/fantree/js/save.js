

$(document).on("click", "#save", function () {

  if(chart != undefined){
    editChartStatus()
    show_toast('success', 'success', "fanchart state saved !")
  }
});

