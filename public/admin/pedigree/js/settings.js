


  
  document.querySelector('#open_settings').addEventListener('click', function(event) {
    
    document.querySelectorAll('#settings input[name="placeholder_images_male"]').forEach((checkbox) => {
      checkbox.addEventListener('click', function(event) {
        // If the checkbox is already checked, prevent it from being unchecked
        if (this.checked) {
          document.querySelectorAll('#settings input[name="placeholder_images_male"]').forEach((cb) => {
            if (cb !== this) {
              cb.checked = false;
              cb.parentElement.classList.remove("checked")
            }
          });
        } else {
          // If trying to uncheck the only checked checkbox, prevent it
          event.preventDefault();
        }
      });
    });

    document.querySelectorAll('#settings input[name="placeholder_images_female"]').forEach((checkbox) => {
      checkbox.addEventListener('click', function(event) {
        // If the checkbox is already checked, prevent it from being unchecked
        if (this.checked) {
          document.querySelectorAll('#settings input[name="placeholder_images_female"]').forEach((cb) => {
            if (cb !== this) {
              cb.checked = false;
              cb.parentElement.classList.remove("checked")
            }
          });
        } else {
          // If trying to uncheck the only checked checkbox, prevent it
          event.preventDefault();
        }
      });
    });

    document.querySelectorAll('.customimagescheckbox').forEach((checkbox) => {
      checkbox.addEventListener('click', function(event) {
        // If the checkbox is already checked, prevent it from being unchecked
        if (this.checked) {
          document.querySelectorAll('.customimagescheckbox').forEach((cb) => {
            if (cb !== this) {
              cb.checked = false;
            }
          });
        } else {
          // If trying to uncheck the only checked checkbox, prevent it
          event.preventDefault();
        }
      });
    });

    document.querySelectorAll('.customimagescheckboxbg').forEach((checkbox) => {
      checkbox.addEventListener('click', function(event) {
        // If the checkbox is already checked, prevent it from being unchecked
        if (this.checked) {
          document.querySelectorAll('.customimagescheckboxbg').forEach((cb) => {
            if (cb !== this) {
              cb.checked = false;
            }
          });
        } else {
          // If trying to uncheck the only checked checkbox, prevent it
          event.preventDefault();
        }
      });
    });

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
          init_settings(data.settings)
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

    var myModal = new bootstrap.Modal(document.getElementById('settings'))
    myModal.show()

  });


  function init_settings(settings) {
    
    // select default portrait already selected
    maleIcon = settings.default_male_image.split(".")[0]
    femaleIcon = settings.default_female_image.split(".")[0]

    maleIconElement = document.querySelector('#settings input[name="placeholder_images_male"][value="'+maleIcon+'"]')
    maleIconElement.checked = true
    maleIconElement.parentElement.classList.add("checked")

    femaleIconElement = document.querySelector('#settings input[name="placeholder_images_female"][value="'+femaleIcon+'"]')
    femaleIconElement.checked = true
    femaleIconElement.parentElement.classList.add("checked")

    const colorMale = document.querySelector('#color-picker-male');
    const colorFemale = document.querySelector('#color-picker-female');
    const colorBlood = document.querySelector('#color-picker-blood');
    const colorNotBlood = document.querySelector('#color-picker-notblood');

    const colorText = document.querySelector('#color-picker-text');
    const colorBand = document.querySelector('#color-picker-band');
    
    const colorSpouse = document.querySelector('#color-picker-spouse');
    const colorBioChild = document.querySelector('#color-picker-bio-child');
    const colorAdopChild = document.querySelector('#color-picker-adop-child');


    // init colors types
    document.getElementById('boxColor').value=settings.box_color;

    // init node-template
    const checkbox = document.querySelector(`.customimagescheckbox[value="${settings.node_template}"]`);
    const checkboxbg = document.querySelector(`.customimagescheckboxbg[value="${settings.bg_template}"]`);

    if (checkbox) {
      checkbox.checked = true;
    }

    if (checkboxbg) {
      checkboxbg.checked = true;
    }
    // classic  

    if (colorMale) {
      var colorMalepickr = pickr.create({
        el: colorMale,
        theme: 'classic',
        default: settings.male_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="male_color"]').value = settings.male_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="male_color"]').value = hexcolor;
        instance.hide()
      });
    }


    if (colorFemale) {
      var colorFemalepickr = pickr.create({
        el: colorFemale,
        theme: 'classic',
        default: settings.female_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="female_color"]').value = settings.female_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="female_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorBlood) {
      var colorBloodpickr = pickr.create({
        el: colorBlood,
        theme: 'classic',
        default: settings.blood_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="blood_color"]').value = settings.blood_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="blood_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorNotBlood) {
      var colorNotBloodpickr = pickr.create({
        el: colorNotBlood,
        theme: 'classic',
        default: settings.notblood_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="notblood_color"]').value = settings.notblood_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="notblood_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorText) {
      var colorTextpickr = pickr.create({
        el: colorText,
        theme: 'classic',
        default: settings.text_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="text_color"]').value = settings.text_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="text_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorBand) {
      var colorBandpickr = pickr.create({
        el: colorBand,
        theme: 'classic',
        default: settings.band_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="band_color"]').value = settings.band_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="band_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorSpouse) {
      var colorSpousepickr = pickr.create({
        el: colorSpouse,
        theme: 'classic',
        default: settings.spouse_link_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="spouse_link_color"]').value = settings.spouse_link_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="spouse_link_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorBioChild) {
      var colorBioChildpickr = pickr.create({
        el: colorBioChild,
        theme: 'classic',
        default: settings.bio_child_link_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="bio_child_link_color"]').value = settings.bio_child_link_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="bio_child_link_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorAdopChild) {
      var colorAdopChildpickr = pickr.create({
        el: colorAdopChild,
        theme: 'classic',
        default: settings.adop_child_link_color,
        swatches: [],
        components: {
          // Main components
          preview: true,
          hue: true,

          // Input / output Options
          interaction: {
            rgba: true,
            hex: true,
            input: true,
            save: true
          }
        }
      }).on('init', instance => {
        document.querySelector('input[name="adop_child_link_color"]').value = settings.adop_child_link_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="adop_child_link_color"]').value = hexcolor;
        instance.hide()
      });
    }



    





  }



