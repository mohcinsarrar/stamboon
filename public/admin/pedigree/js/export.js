

$(document).on("click", "#export", function () {
  if(chart == undefined){
    return;
}
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

  // suggested_size

  const chartElement = d3.select('.chart').node();
  let width = chartElement.getBoundingClientRect().width;
  let height = chartElement.getBoundingClientRect().height;

  const transformAttr = chartElement.getAttribute('transform');
  const scaleMatch = transformAttr.match(/scale\(([\d\.]+)\)/);
  const scale = scaleMatch ? parseFloat(scaleMatch[1]) : null;

  width = width / scale;
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
      url: "/pedigree/print",
      type: 'POST',
      encode: true,
      dataType: 'json',
      success: function(data) {
          if (data.error == false) {
            if (type == "png") {
              var format = document.querySelector('#exportModal #format').value;
              exportGraph(format,include_note,include_weapon)
              document.querySelector('#exportModal #exportModalSpinner').classList.remove('d-flex')
                document.querySelector('#exportModal #exportModalSpinner').classList.add('d-none')
            }
            else {
              if (type == "pdf") {
                var format = document.querySelector('#exportModal #formatPdf').value;
                var orientation = document.querySelector('#exportModal #orientation').value;
                downloadPdf(format, orientation,include_note,include_weapon)
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

async function loadFonts(fonts,target){


  const imagePromises = Array.from(fonts).map(async (font) => {
    const url = font.url;
    if (url) {
        const dataUrl = await fetch(url)
            .then((res) => res.blob())
            .then((blob) => {
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result);
                    reader.readAsDataURL(blob);
                });
            });

            let defs = target.querySelector("defs");
            if (!defs) {
              defs = document.createElementNS("http://www.w3.org/2000/svg", "defs");
              target.insertBefore(defs, target.firstChild); // Insert at the top
            }
            const styleElement = document.createElementNS("http://www.w3.org/2000/svg", "style");
            styleElement.textContent = `
            @font-face {
              font-family: '${font.name}';
              src: url('${dataUrl}') format('woff2');
            }
          `;
            defs.appendChild(styleElement);
    }
});


}

async function exportGraph(format,include_note,include_weapon) {
  const target = document.querySelector(".svg-chart-container");

  let ignores = [];

  if(include_note == false){
    ignores.push('.draggable')
  }

  if(include_weapon == false){
    ignores.push('.weapon')
  }

  
  if (ignores != []) {
    ignores.forEach(ignore => {
      const elts = target.querySelectorAll(ignore);
      [].forEach.call(elts, elt => {

        elt.style.display = "none";

      });
    });
    
  }
  
  
    

  const fonts = [{name:'Charm', url : 'https://fonts.gstatic.com/s/charm/v11/7cHmv4oii5K0MdYoK-4.woff2'}]
  if(fonts != null){
    await loadFonts(fonts,target)
  }

  
  
  chart.exportImg({ full: false, scale: format ,onLoad: (base64) => {
    d3.selectAll(".node-button-g").attr('display','block');
    if (ignores != []) {
      ignores.forEach(ignore => {
        const elts = target.querySelectorAll(ignore);
        [].forEach.call(elts, elt => {
  
          elt.style.display = "block";
  
        });
      });
      
    }
    const modal = document.getElementById('exportModal')
    const bsmodal = bootstrap.Modal.getInstance(modal)
    bsmodal.hide()
  }})
  

    
  
}




async function downloadPdf(format, orientation,include_note,include_weapon) {


  const target = document.querySelector(".svg-chart-container");

  let ignores = [];

  if(include_note == false){
    ignores.push('.draggable')
  }

  if(include_weapon == false){
    ignores.push('.weapon')
  }

  
  if (ignores != []) {
    ignores.forEach(ignore => {
      const elts = target.querySelectorAll(ignore);
      [].forEach.call(elts, elt => {

        elt.style.display = "none";

      });
    });
    
  }
  
  
    

  const fonts = [{name:'Charm', url : 'https://fonts.gstatic.com/s/charm/v11/7cHmv4oii5K0MdYoK-4.woff2'}]
  if(fonts != null){
    await loadFonts(fonts,target)
  }


  chart.exportImg({
    save: false,
    full: false,
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
        pdf.save('Pedigree.pdf');

        d3.selectAll(".node-button-g").attr('display','block');

        if (ignores != []) {
          ignores.forEach(ignore => {
            const elts = target.querySelectorAll(ignore);
            [].forEach.call(elts, elt => {
      
              elt.style.display = "block";
      
            });
          });
          
        }
        
        const modal = document.getElementById('exportModal')
        const bsmodal = bootstrap.Modal.getInstance(modal)
        bsmodal.hide()
      };
    },
  });
}