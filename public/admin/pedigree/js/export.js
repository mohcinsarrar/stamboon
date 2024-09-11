

$(document).on("click", "#export", function () {
  d3.selectAll(".toolbar").remove();
  d3.selectAll(".node-button-g").attr('display','none');
  var myModal = new bootstrap.Modal(document.getElementById('exportModal'))
  myModal.show()

});



$(document).on("change", "#exportModal #type", function () {
  var type = document.querySelector('#exportModal #type').value;
  if (type == "pdf") {
    document.querySelector('#exportModal #formatPdfContainer').style.display = "block";
    document.querySelector('#exportModal #orientationContainer').style.display = "block";
    document.querySelector('#exportModal #formatContainer').style.display = "none";
  }
  else {
    if (type == "png") {
      document.querySelector('#exportModal #formatPdfContainer').style.display = "none";
      document.querySelector('#exportModal #orientationContainer').style.display = "none";
      document.querySelector('#exportModal #formatContainer').style.display = "block";
    }
  }


});


$(document).on("click", "#exportBtn", function () {
  var type = document.querySelector('#exportModal #type').value;


  if (type == "png") {
    var format = document.querySelector('#exportModal #format').value;
    exportGraph(format)
  }
  else {
    if (type == "pdf") {
      var format = document.querySelector('#exportModal #formatPdf').value;
      var orientation = document.querySelector('#exportModal #orientation').value;
      downloadPdf(format, orientation)
    }
  }


});

function exportGraph(format) {

  chart.exportImg({ full: true, scale: format ,onLoad: (base64) => {
    d3.selectAll(".node-button-g").attr('display','block');
    const modal = document.getElementById('exportModal')
    const bsmodal = bootstrap.Modal.getInstance(modal)
    bsmodal.hide()
  }})
  
}




function downloadPdf(format, orientation) {

  chart.exportImg({
    save: false,
    full: true,
    onLoad: (base64) => {
      var pdf = new jspdf.jsPDF({ orientation: orientation, unit: 'px', format: format });
      var img = new Image();
      img.src = base64;
      img.onload = function () {

        // get pdf sizes
        var pageWidth = pdf.internal.pageSize.getWidth();
        var pageHeight = pdf.internal.pageSize.getHeight();

        const imgAspectRatio = img.width / img.height;
        const pageAspectRatio = pageWidth / pageHeight;

        // compute image sizes to fit page and keep aspect ration
        let drawWidth, drawHeight;

        // Determine whether to scale based on width or height
        if (imgAspectRatio > pageAspectRatio) {
          // Scale based on width
          drawWidth = pageWidth;
          drawHeight = drawWidth / imgAspectRatio;
        } else {
          // Scale based on height
          drawHeight = pageHeight;
          drawWidth = drawHeight * imgAspectRatio;
        }

        // Center the image on the page
        const xOffset = (pageWidth - drawWidth) / 2;
        const yOffset = (pageHeight - drawHeight) / 2;


        pdf.addImage(
          img,
          'JPEG',
          5,
          5,
          drawWidth - 5,
          drawHeight - 5
        );
        pdf.save('chart.pdf');

        d3.selectAll(".node-button-g").attr('display','block');
        const modal = document.getElementById('exportModal')
        const bsmodal = bootstrap.Modal.getInstance(modal)
        bsmodal.hide()
      };
    },
  });
}