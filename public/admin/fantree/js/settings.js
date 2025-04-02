document.querySelector('#open_settings').addEventListener('click', function (event) {

  document.querySelectorAll('#settings input[name="default_male_image"]').forEach((checkbox) => {
    checkbox.addEventListener('click', function (event) {
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
        this.parentElement.classList.add("checked")
        event.preventDefault();
      }
    });
  });

  document.querySelectorAll('#settings input[name="default_female_image"]').forEach((checkbox) => {
    checkbox.addEventListener('click', function (event) {
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
        this.parentElement.classList.add("checked")
        event.preventDefault();
      }
    });
  });

  document.querySelectorAll('#settings input[name="note_type"]').forEach((checkbox) => {
    checkbox.addEventListener('click', function (event) {
      // If the checkbox is already checked, prevent it from being unchecked
      if (this.checked) {
        document.querySelectorAll('#settings input[name="note_type"]').forEach((cb) => {
          if (cb !== this) {
            cb.checked = false;
            cb.parentElement.classList.remove("checked")
          }
        });
      } else {
        // If trying to uncheck the only checked checkbox, prevent it
        this.parentElement.classList.add("checked")
        event.preventDefault();
      }
    });
  });

  document.querySelectorAll('.customimagescheckbox').forEach((checkbox) => {
    checkbox.addEventListener('click', function (event) {
      // If the checkbox is already checked, prevent it from being unchecked
      if (this.checked) {
        document.querySelectorAll('.customimagescheckbox').forEach((cb) => {
          if (cb !== this) {
            cb.checked = false;
            cb.parentElement.classList.remove("checked")
          }
        });
      } else {
        // If trying to uncheck the only checked checkbox, prevent it
        this.parentElement.classList.add("checked")
        event.preventDefault();
      }
    });
  });


  document.querySelectorAll('.customimagescheckboxbg').forEach((checkbox) => {
    checkbox.addEventListener('click', function (event) {
      // If the checkbox is already checked, prevent it from being unchecked
      if (this.checked) {
        document.querySelectorAll('.customimagescheckboxbg').forEach((cb) => {
          if (cb !== this) {

            cb.checked = false;
            cb.parentElement.classList.remove("checked")
          }
        });
      } else {
        // If trying to uncheck the only checked checkbox, prevent it
        this.parentElement.classList.add("checked")
        event.preventDefault();
      }
    });
  });

  init_settings(treeConfiguration)

  var myModal = new bootstrap.Modal(document.getElementById('settings'))
  myModal.show()



});


document.querySelector('#settings form').addEventListener('submit', (event) => {
  // Execute your custom function
  // Prevent the default form submission
  event.preventDefault();

  // Create a FormData object to gather form data
  const formData = new FormData(document.querySelector('#settings form'));

  // Convert FormData to a plain object
  const data = Object.fromEntries(formData.entries());


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  let fantree_id = get_fantree_id()
  result = $.ajax({
    url: "/fantree/settings/"+fantree_id,
    type: 'POST',
    data: data,
    encode: true,
    dataType: 'json',
    success: function (data) {
      if (data.error == false) {
        const modal = document.getElementById('settings')
        const bsmodal = bootstrap.Modal.getInstance(modal)
        bsmodal.hide()
        show_toast('success', 'success', data.message)
        draw_tree()

      } else {
        show_toast('danger', 'error', "can't edit settings, please try again !")
      }

    },
    error: function (xhr, status, error) {

      show_toast('danger', 'error', "can't edit settings, please try again !")
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
  maleIconElement = document.querySelector('#settings select#default_date option[value="' + settings.default_date + '"]').selected = true;
  // select default portrait already selected
  maleIcon = settings.default_male_image.split(".")[0]
  femaleIcon = settings.default_female_image.split(".")[0]

  maleIconElement = document.querySelector('#settings input[name="default_male_image"][value="' + maleIcon + '"]')
  maleIconElement.checked = true
  maleIconElement.parentElement.classList.add("checked")

  femaleIconElement = document.querySelector('#settings input[name="default_female_image"][value="' + femaleIcon + '"]')
  femaleIconElement.checked = true
  femaleIconElement.parentElement.classList.add("checked")



  notetype = document.querySelector('#settings input[name="note_type"][value="' + settings.note_type + '"]')
  notetype.checked = true
  notetype.parentElement.classList.add("checked")

  const colorMale = document.querySelector('#color-picker-male');
  const colorFemale = document.querySelector('#color-picker-female');

  const colorText = document.querySelector('#color-picker-text');
  const colorFather = document.querySelector('#color-picker-father');
  const colorMother = document.querySelector('#color-picker-mother');

  const colorBand = document.querySelector('#color-picker-band');

  const colorNoteText = document.querySelector('#color-picker-note');

  // init photos types and direction
  document.querySelector('#settings input[name="photos_type"][value="' + settings.photos_type + '"]').checked = true
  document.querySelector('#settings input[name="photos_direction"][value="' + settings.photos_direction + '"]').checked = true

  // init filter
  document.querySelector('#settings input[name="default_filter"][value="' + settings.default_filter + '"]').checked = true

  // init node-template

  const checkbox = document.querySelector(`.customimagescheckbox[value="${settings.node_template}"]`);
  const checkboxbg = document.querySelector(`.customimagescheckboxbg[value="${settings.bg_template}"]`);

  if (checkbox) {
    checkbox.checked = true;
    checkbox.parentElement.classList.add("checked")
  }

  if (checkboxbg) {
    checkboxbg.checked = true;
    checkboxbg.parentElement.classList.add("checked")
  }
  // classic  

  if (colorMale) {

    initColorPicker(colorMale, settings.male_color, 'input[name="male_color"]')

  }


  if (colorFemale) {

    initColorPicker(colorFemale, settings.female_color, 'input[name="female_color"]')

  }

  if (colorText) {

    initColorPicker(colorText, settings.text_color, 'input[name="text_color"]')

  }


  if (colorFather) {

    initColorPicker(colorFather, settings.father_link_color, 'input[name="father_link_color"]')
  }

  if (colorMother) {

    initColorPicker(colorMother, settings.mother_link_color, 'input[name="mother_link_color"]')

  }

  if (colorBand) {

    initColorPicker(colorBand, settings.band_color, 'input[name="band_color"]')

  }

  if (colorNoteText) {
    initColorPicker(colorNoteText, settings.note_text_color, 'input[name="note_text_color"]')
  }










}



