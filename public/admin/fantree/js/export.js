

$(document).on("click", "#export", function () {

  var type = document.querySelector('#exportModal #type').value;
  if (type == "pdf") {
    //document.querySelector('#exportModal #formatPdfContainer').style.display = "block";
    document.querySelector('#exportModal #orientationContainer').style.display = "block";
    //document.querySelector('#exportModal #formatContainer').style.display = "none";
  }
  else {
    if (type == "png") {
      //document.querySelector('#exportModal #formatPdfContainer').style.display = "none";
      document.querySelector('#exportModal #orientationContainer').style.display = "none";
      //document.querySelector('#exportModal #formatContainer').style.display = "block";
    }
  }

   // suggested_size
  /*
   const chartElement = d3.select('#chartGroup').node();
   let width = chartElement.getBoundingClientRect().width;
   let height = chartElement.getBoundingClientRect().height;
 
   const transformAttr = chartElement.getAttribute('transform');
    let scaleMatch;
    let scale;
    if(transformAttr != null){
      scaleMatch = transformAttr.match(/scale\(([\d\.]+)\)/);
      scale = scaleMatch ? parseFloat(scaleMatch[1]) : null;
    }
    else{

      scale = 1;
    }
 
   width = width / scale + 1000;
   height = height / scale;

 
   let best_size;  
   if(width<=1344){
     best_size = '1344 x 839 px';
   }
 
   if(width>1344 && width<= 2688){
     best_size = '2688 x 1678 px';
   }
 
   if(width>2688 && width<= 4032){
     best_size = '4032 x 2517 px';
   }
 
   if(width>4032 && width<= 5376){
     best_size = '5376 x 3356 px';
   }
 
   if(width>5376){
     best_size = '6720 x 4195 px';
   }
 
 
 
   document.querySelector('#suggested_size .msg').innerHTML = `For best printing quality choose a size above ${best_size}`
   */
  document.querySelector('#suggested_size .msg').innerHTML = `The print process take time, please wait for your tree to be downloaded`
  
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
    //document.querySelector('#exportModal #formatPdfContainer').style.display = "block";
    document.querySelector('#exportModal #orientationContainer').style.display = "block";
    //document.querySelector('#exportModal #formatContainer').style.display = "none";
  }
  else {
    if (type == "png") {
      //document.querySelector('#exportModal #formatPdfContainer').style.display = "none";
      document.querySelector('#exportModal #orientationContainer').style.display = "none";
      //document.querySelector('#exportModal #formatContainer').style.display = "block";
    }
  }


});


$(document).on("click", "#exportBtn", function () {
  document.getElementById('fit').click();

  var type = document.querySelector('#exportModal #type').value;
  var include_note = document.querySelector('#exportModal #include_note').checked;
  var include_weapon = document.querySelector('#exportModal #include_weapon').checked;

  document.querySelector('#exportModal #exportModalSpinner').classList.remove('d-none')
  document.querySelector('#exportModal #exportModalSpinner').classList.add('d-flex')

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
              //var format = document.querySelector('#exportModal #format').value;
              var format = 3;
              exportGraph(format,include_note,include_weapon)
              document.querySelector('#exportModal #exportModalSpinner').classList.remove('d-flex')
                document.querySelector('#exportModal #exportModalSpinner').classList.add('d-none')
            }
            else {
              if (type == "pdf") {
                //var formatpdf = document.querySelector('#exportModal #formatPdf').value;
                var formatpdf = 'a3';
                var orientation = document.querySelector('#exportModal #orientation').value;
                downloadPdf(formatpdf, orientation,include_note,include_weapon)
                document.querySelector('#exportModal #exportModalSpinner').classList.remove('d-flex')
                document.querySelector('#exportModal #exportModalSpinner').classList.add('d-none')
              }
            }
            
          } else {
              show_toast('danger', 'error', "can't print, print limit reached!")
              document.querySelector('#exportModal #exportModalSpinner').classList.remove('d-flex')
              document.querySelector('#exportModal #exportModalSpinner').classList.add('d-none')
          }

      },
      error: function(xhr, status, error) {
          show_toast('danger', 'error', "can't print, please try again !")
          document.querySelector('#exportModal #exportModalSpinner').classList.remove('d-flex')
          document.querySelector('#exportModal #exportModalSpinner').classList.add('d-none')
          return null;
      }
  });

  


});



function exportGraph(scale,include_note,include_weapon) {
  let background;
  if(treeConfiguration.bg_template != '0'){
    background = true
  }
  else{
    background = false
  }

  let ignores = [];

  if(include_note == false){
    ignores.push('.draggable')
  }

  if(include_weapon == false){
    ignores.push('.weapon')
  }

  d3_to_png('#graph svg', 'Fanchart', {
      scale: scale ,
      format: 'png',
      quality: 1,
      download: true,
      ignores: ignores,
      background: background,
      fonts: [{name:'Charm', url : 'https://fonts.gstatic.com/s/charm/v11/7cHmv4oii5K0MdYoK-4.woff2'}]
    }).then(fileData => {

    });

    const modal = document.getElementById('exportModal')
    const bsmodal = bootstrap.Modal.getInstance(modal)
    bsmodal.hide()

  
}




function downloadPdf(formatpdf, orientation,include_note,include_weapon) {
  let background;
  if(treeConfiguration.bg_template != '0'){
    background = true
  }
  else{
    background = false
  }

  let ignores = [];

  if(include_note == false){
    ignores.push('.draggable')
  }

  if(include_weapon == false){
    ignores.push('.weapon')
  }

  d3_to_png('#graph svg', 'Fanchart', {
      scale: 3 ,
      format: 'png',
      quality: 1,
      download: false,
      ignores: ignores,
      background: background,
      fonts: [{name:'Charm', url : 'https://fonts.gstatic.com/s/charm/v11/7cHmv4oii5K0MdYoK-4.woff2'}]
    }).then(fileData => {

      var img = new Image();
      
      img.onload = function () {
        var pdf = new jspdf.jsPDF({ orientation: orientation, unit: 'px', format: formatpdf });

        var pageWidth = pdf.internal.pageSize.getWidth();
        var pageHeight = pdf.internal.pageSize.getHeight();
      
        const imgAspectRatio = img.width / img.height;
        const pageAspectRatio = pageWidth / pageHeight;
      
        let drawWidth, drawHeight;
      
        if (imgAspectRatio > pageAspectRatio) {
          drawWidth = pageWidth;
          drawHeight = drawWidth / imgAspectRatio;
        } else {
          drawHeight = pageHeight;
          drawWidth = drawHeight * imgAspectRatio;
        }
      
        const xOffset = (pageWidth - drawWidth) / 2;
        const yOffset = (pageHeight - drawHeight) / 2;
      
        pdf.addImage(img, 'JPEG', 5, 5, drawWidth - 5 , drawHeight - 5);
        pdf.save('familytree.pdf');
      
      };
      img.src = fileData;
      
    });


  /*
  d3_to_png('#graph svg', 'familytree', {
    scale: scale,
    format: 'png',
    quality: 1,
    download: false,
    ignores: ignores,
    fonts: [{name:'Charm', url : 'https://fonts.gstatic.com/s/charm/v11/7cHmv4oii5K0MdYoK-4.woff2'}]
  }).then(fileData => {
    console.log(fileData)
    
    
    var img = new Image();
    
    img.src = fileData;

    img.onload = function () {

      
    };
    
    


  });
  */


  const modal = document.getElementById('exportModal')
    const bsmodal = bootstrap.Modal.getInstance(modal)
    bsmodal.hide()


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