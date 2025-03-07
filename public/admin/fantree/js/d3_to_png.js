function inlineStyles(source, target) {
    // inline style from source element to the target (detached) one
    const computed = window.getComputedStyle(source);
    for (const styleKey of computed) {
      target.style[styleKey] = computed[styleKey];
    }
  
    // recursively call inlineStyles for the element children
    for (let i = 0; i < source.children.length; i++) {
      inlineStyles(source.children[i], target.children[i]);
    }
  }


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



  async function embedImages(svgElement) {
    const images = svgElement.querySelectorAll('img');

    const imagePromises = Array.from(images).map(async (img) => {
        const url = img.getAttribute('src');
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

            img.setAttribute('src', dataUrl);
        }
    });

    await Promise.all(imagePromises);
  }

  function downloadSVG({ source, target, scale, format, quality }) {
    let svgData = new XMLSerializer().serializeToString(target);

    // Create a Blob object for the SVG
    let blob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
    let url = URL.createObjectURL(blob);
    console.log(url)

    // Create a download link
    let link = document.createElement("a");
    link.href = url;
    link.download = "images.svg";

    // Simulate a click to trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Clean up the object URL
    URL.revokeObjectURL(url);

    // Return a promise resolving to the blob URL (optional)
    return Promise.resolve(url);
}

  async function copyToCanvas2({ source, target, scale, format, quality }) {
    let canvas = document.createElement("canvas");
    let svgSize = source.getBoundingClientRect();
    const resolution = 4; // Increase for higher clarity

    canvas.width = svgSize.width * scale * resolution;
    canvas.height = svgSize.height * scale * resolution;
    canvas.style.width = svgSize.width + "px";
    canvas.style.height = svgSize.height + "px";

    let ctxt = canvas.getContext("2d");
    ctxt.scale(scale * resolution, scale * resolution);

    let svgData = new XMLSerializer().serializeToString(target);

    // Use Canvg to properly render the SVG onto the canvas
    const canvgInstance = await Canvg.Canvg.from(ctxt, svgData);
    await canvgInstance.render();

    return canvas.toDataURL(`image/${format === "jpg" ? "jpeg" : format}`, quality);
  }

  
  function copyToCanvas({ source, target, scale, format, quality }) {
    let svgData = new XMLSerializer().serializeToString(target);
    let canvas = document.createElement('canvas');
    let svgSize = source.getBoundingClientRect();

    //Resize can break shadows
    canvas.width = svgSize.width * scale;
    canvas.height = svgSize.height * scale;
    canvas.style.width = svgSize.width;
    canvas.style.height = svgSize.height;
  
    let ctxt = canvas.getContext('2d');
    ctxt.scale(scale, scale);
  
    let img = document.createElement('img');
  
    img.setAttribute(
      'src',
      'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svgData)
    );

    
    return new Promise(resolve => {
      
      img.onload = () => {
        
        ctxt.drawImage(img, 0, 0);
        
        // Log the base64 data URL after the image is drawn on the canvas
        const base64Data = canvas.toDataURL(`image/${format === 'jpg' ? 'jpeg' : format}`, quality);
        resolve(base64Data);
        
      };

    
    });
  }
  
  function downloadImage({ file, name, format }) {
    let a = document.createElement('a');
    a.download = `${name}.${format}`;
    a.href = file;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }
  
  async function d3_to_png(
    source,
    name,
    {
      scale = 1,
      format = 'png',
      quality = 0.92,
      download = true,
      ignores = null,
      cssinline = 1,
      background = false,
      fonts = null
    } = {}
  ) {

    
    // Accept a selector or directly a DOM Element
    source = source instanceof Element ? source : document.querySelector(source);
    
    

    // Create a new SVG element similar to the source one to avoid modifying the
    // source element.
    let target = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    target.innerHTML = source.innerHTML;
    for (const attr of source.attributes) {
      target.setAttribute(attr.name, attr.value);
    }

     // embed fonts
     if(fonts != null){
      await loadFonts(fonts,target)
    }

    
    // Set all the css styles inline on the target element based on the styles
    // of the source element
    if (cssinline === 1) {
      inlineStyles(source, target);
    }

    
    if (background == false) {
      target.style.background = 'white';
    }
    

    //Remove unwanted elements
    if (ignores != []) {
      ignores.forEach(ignore => {
        const elts = target.querySelectorAll(ignore);
      [].forEach.call(elts, elt => elt.parentNode.removeChild(elt));
      });
      
    }

    await embedImages(target); // Embed images in the SVG
    
   


  
    //Copy all html to a new canvas
    const file = await copyToCanvas({
      source,
      target,
      scale,
      format,
      quality
    });

    
    
    if (download) {
      downloadImage({ file, name, format });
    }
    

    return file;
  };