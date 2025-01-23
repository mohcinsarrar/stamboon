

$(document).on("click", "#export", function () {

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
  
  d3.selectAll(".toolbar").remove();
  d3.selectAll(".node-button-g").attr('display','none');
  if(chart == undefined){
    show_toast('danger', 'error', "can't print, please add at least one person!")
    return false
  }
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

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
      url: "/fantree/print",
      type: 'POST',
      encode: true,
      dataType: 'json',
      success: function(data) {
          if (data.error == false) {
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
            
          } else {
              show_toast('danger', 'error', "can't print, print limit reached!")
          }

      },
      error: function(xhr, status, error) {
          show_toast('danger', 'error', "can't print, please try again !")
          return null;
      }
  });

  


});

function exportGraph(format) {
  let background;
  if(treeConfiguration.bg_template != '0'){
    background = 'white'
  }
  else{
    background = null
  }

  d3_to_png('#graph svg', 'familytree', {
      scale: format ,
      format: 'png',
      quality: 1,
      download: true,
      ignore: '.ignored'
    }).then(fileData => {
      
    });

    const modal = document.getElementById('exportModal')
    const bsmodal = bootstrap.Modal.getInstance(modal)
    bsmodal.hide()

  
}




function downloadPdf(format, orientation) {


  d3_to_png('#graph svg', 'familytree', {
    scale: 3,
    format: 'png',
    quality: 0.01,
    download: false,
    ignore: '.ignored',
    background: 'white'
  }).then(base64 => {

    var pdf = new jspdf.jsPDF({ orientation: orientation, unit: 'px', format: format });
    var img = new Image();
    img.src = base64;
    
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
  });


}


function exportImg({ full = false, scale = 3, onLoad = d => d, save = true, backgroundColor = "#FAFAFA" } = {}) {
  const that = this;
  const attrs = this.getChartState();
  const { svg: svgImg, root } = attrs
  let count = 0;
  const selection = svgImg.selectAll('img')
  let total = selection.size()

  const exportImage = () => {
      const transform = JSON.parse(JSON.stringify(that.lastTransform()));
      const duration = that.duration();
      if (full) {
          that.fit();
      }
      const { svg } = that.getChartState()

      setTimeout(d => {
          that.downloadImage({
              node: svg.node(), scale,
              isSvg: false,
              backgroundColor,
              onAlreadySerialized: d => {
                  that.update(root)
              },
              imageName: attrs.imageName,
              onLoad: onLoad,
              save
          })
      }, full ? duration + 10 : 0)
  }

  if (total > 0) {
      selection
          .each(function () {
              that.toDataURL(this.src, (dataUrl) => {
                  this.src = dataUrl;
                  if (++count == total) {
                      exportImage();
                  }
              })
          })
  } else {
      exportImage();
  }


}