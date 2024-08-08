document.addEventListener("DOMContentLoaded", () => {




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


  function init_settings(settings) {

    const colorSpouse = document.querySelector('#color-picker-spouse');
    const colorBioChild = document.querySelector('#color-picker-bio-child');
    const colorAdopChild = document.querySelector('#color-picker-adop-child');

    const colorMale = document.querySelector('#color-picker-male');
    const colorFemale = document.querySelector('#color-picker-female');

    const colorMaleText = document.querySelector('#color-picker-text-male');
    const colorFemaleText = document.querySelector('#color-picker-text-female');

    // classic
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

    if (colorMaleText) {
      var colorMaleTextpickr = pickr.create({
        el: colorMaleText,
        theme: 'classic',
        default: settings.male_text_color,
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
        document.querySelector('input[name="male_text_color"]').value = settings.male_text_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="male_text_color"]').value = hexcolor;
        instance.hide()
      });
    }

    if (colorFemaleText) {
      var colorFemalepickr = pickr.create({
        el: colorFemaleText,
        theme: 'classic',
        default: settings.female_text_color,
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
        document.querySelector('input[name="female_text_color"]').value = settings.female_text_color;
      }).on('save', (color, instance) => {
        var hexcolor = '#' + color.toHEXA().join('')
        document.querySelector('input[name="female_text_color"]').value = hexcolor;
        instance.hide()
      });
    }



  }


});
