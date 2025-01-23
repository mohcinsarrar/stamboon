


  
  document.querySelector('#open_settings').addEventListener('click', function(event) {
    if(chart == undefined){
      return;
  }
    document.querySelectorAll('#settings input[name="default_male_image"]').forEach((checkbox) => {
      checkbox.addEventListener('click', function(event) {
        // If the checkbox is already checked, prevent it from being unchecked
        if (this.checked) {
          document.querySelectorAll('#settings input[name="default_male_image"]').forEach((cb) => {
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

    document.querySelectorAll('#settings input[name="default_female_image"]').forEach((checkbox) => {
      checkbox.addEventListener('click', function(event) {
        // If the checkbox is already checked, prevent it from being unchecked
        if (this.checked) {
          document.querySelectorAll('#settings input[name="default_female_image"]').forEach((cb) => {
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

          var myModal = new bootstrap.Modal(document.getElementById('settings'))
          myModal.show()

          
        } else {
          show_toast('danger', 'error', data.error)
        }
  
      },
      error: function (xhr, status, error) {
        if ('responseJSON' in xhr) {
          show_toast('danger', 'error', xhr.responseJSON.message)
        } else {
          show_toast('danger', 'error', error)
        }
  
        return null;
      }
    });

    

  });



  function initColorPicker(el, defaultColor, inputSelector) {

    const defaultColorValid = defaultColor || '#000000'; // Fallback to black

    
    const pickerVar = Pickr.create({
      container: document.querySelector('div#settings'),
      el: el,
      theme: 'classic',
      default: defaultColor,
      swatches: [],
      lockOpacity: true,
      appClass: 'custom-pickr',
      components: {
        preview: true,
        hue: true,
        interaction: {
          rgba: true,
          hex: true,
          input: true,
          save: true
        }
      }
    });

    pickerVar.on('init', instance => {
      document.querySelector(inputSelector).value = defaultColor;
    });

    pickerVar.on('save', (color, instance) => {
      const hexColor = '#' + color.toHEXA().join('');
      document.querySelector(inputSelector).value = hexColor;
      instance.hide();
    })


    return pickerVar
  }

  function init_settings(settings) {

    // change default date
    maleIconElement = document.querySelector('#settings select#default_date option[value="'+settings.default_date+'"]').selected = true;
    // select default portrait already selected
    maleIcon = settings.default_male_image.split(".")[0]
    femaleIcon = settings.default_female_image.split(".")[0]

    maleIconElement = document.querySelector('#settings input[name="default_male_image"][value="'+maleIcon+'"]')
    maleIconElement.checked = true
    maleIconElement.parentElement.classList.add("checked")

    femaleIconElement = document.querySelector('#settings input[name="default_female_image"][value="'+femaleIcon+'"]')
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

      initColorPicker(colorMale, settings.male_color, 'input[name="male_color"]')

    }


    if (colorFemale) {

      initColorPicker(colorFemale, settings.female_color, 'input[name="female_color"]')

    }

    if (colorBlood) {

      initColorPicker(colorBlood, settings.blood_color, 'input[name="blood_color"]')

    }

    if (colorNotBlood) {

      initColorPicker(colorNotBlood, settings.notblood_color, 'input[name="notblood_color"]')

    }

    if (colorText) {

      initColorPicker(colorText, settings.text_color, 'input[name="text_color"]')

    }

    if (colorBand) {

      initColorPicker(colorBand, settings.band_color, 'input[name="band_color"]')

    }

    if (colorSpouse) {

      initColorPicker(colorSpouse, settings.spouse_link_color, 'input[name="spouse_link_color"]')
    }

    if (colorBioChild) {

      initColorPicker(colorBioChild, settings.bio_child_link_color, 'input[name="bio_child_link_color"]')

    }

    if (colorAdopChild) {

      initColorPicker(colorAdopChild, settings.adop_child_link_color, 'input[name="adop_child_link_color"]')

    }



    





  }



