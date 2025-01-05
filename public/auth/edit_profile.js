/**
 *  Pages Authentication
 */

'use strict';
const formAuthentication = document.querySelector('#formAccountSettings');
const formDeleteAccount = document.querySelector('#formDeleteAccount');


document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // Form validation for Add new record
    var formValidationCountry = $('#formAccountSettings #country')
    if (formAuthentication) {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          firstname: {
            validators: {
              notEmpty: {
                message: 'Please enter your first name',
              }
            },
          },
          lastname: {
            validators: {
              notEmpty: {
                message: 'Please enter your last name',
              }
            },
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Please enter your email'
              },
              emailAddress: {
                message: 'Please enter valid email address'
              }
            }
          },
          password: {
            validators: {
              callback: {
                callback: function (value, validator, $field) {
                  const value_input = value.value;
                  if (value_input === '') {
                    // If the field is empty, skip validation
                    return true;
                  }
                  if (value_input.length < 8) {
                    return {
                      valid: false,
                      message: 'Password must be more than 8 characters',
                    };
                  }

                  if (value_input === value_input.toLowerCase()) {
                    return {
                      valid: false,
                      message: 'Password must contain at least one uppercase character',
                    };
                  }

                  if (value_input === value_input.toUpperCase()) {
                    return {
                      valid: false,
                      message: 'Password must contain at least one lowercase character',
                    };
                  }

                  if (value_input.search(/[0-9]/) < 0) {
                    return {
                      valid: false,
                      message: 'Password must contain at least one number',
                    };
                  }

                  var symbolRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
                  if (!symbolRegex.test(value_input)) {
                    return {
                      valid: false,
                      message: 'Password must contain at least one symbole',
                    };
                  }

                  return { valid: true };
                }
              }
            },
          },
          password_confirmation: {
            validators: {
              identical: {
                compare: function () {
                  return formAuthentication.querySelector('[name="password"]').value;
                },
                message: 'Password confirmation and password are not the same'
              }
            },
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-5'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),

          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      }).on('core.validator.validated', function (e) {

        if (!e.result.valid) {
          // Query all messages
          const messages = [].slice.call(formAuthentication.querySelectorAll('[data-field="' + e.field + '"][data-validator]'));

          messages.forEach((messageEle) => {
            const validator = messageEle.getAttribute('data-validator');
            messageEle.style.display = validator === e.validator ? 'block' : 'none';
          });
        }
        else {
          return false;
        }
      })

      if (formValidationCountry.length) {
        formValidationCountry.wrap('<div class="position-relative"></div>');
        formValidationCountry
          .select2({
            placeholder: 'Select your country',
            dropdownParent: formValidationCountry.parent()
          })
          .on('change.select2', function () {
            // Revalidate the color field when an option is chosen
            fv.revalidateField('country');
          });
      }
    }

    // form delete account
    if (formDeleteAccount) {
      const fv = FormValidation.formValidation(formDeleteAccount, {
        fields: {
          confirm: {
            validators: {
              notEmpty: {
                message: 'Please confirm your choice',
              }
            },
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-5'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),

          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      }).on('core.validator.validated', function (e) {

        if (!e.result.valid) {
          // Query all messages
          const messages = [].slice.call(formDeleteAccount.querySelectorAll('[data-field="' + e.field + '"][data-validator]'));

          messages.forEach((messageEle) => {
            const validator = messageEle.getAttribute('data-validator');
            messageEle.style.display = validator === e.validator ? 'block' : 'none';
          });
        }
        else {
          return false;
        }
      })
    }

    

  })();
});
