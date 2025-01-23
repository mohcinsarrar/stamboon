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
      'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)))
    );
    return new Promise(resolve => {
      img.onload = () => {
        ctxt.drawImage(img, 0, 0);
        resolve(
          canvas.toDataURL(`image/${format === 'jpg' ? 'jpeg' : format}`, quality)
        );
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
      ignore = null,
      cssinline = 1,
      background = null
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
    const backgroundImage = target.style.backgroundImage

    // Set all the css styles inline on the target element based on the styles
    // of the source element
    if (cssinline === 1) {
      inlineStyles(source, target);
    }

    if (background != null) {
      target.style.background = background;
    }
  
    //Remove unwanted elements
    if (ignore != null) {
      const elts = target.querySelectorAll(ignore);
      [].forEach.call(elts, elt => elt.parentNode.removeChild(elt));
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