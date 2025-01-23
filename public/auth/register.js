/**
 *  Pages Authentication
 */

 'use strict';
 const formAuthentication = document.querySelector('#formAuthentication');
 
 document.addEventListener('DOMContentLoaded', function (e) {
   (function () {

    var formValidationCountry = $('#formAuthentication #country')

     // Form validation for Add new record
     if (formAuthentication) {
       const fv = FormValidation.formValidation(formAuthentication, {
         fields: {
          firstname: {
            validators: {
              notEmpty: {
                message: 'Please enter your first name'
              }
            }
          },
          lastname: {
            validators: {
              notEmpty: {
                message: 'Please enter your last name'
              }
            }
          },
          country: {
            validators: {
              notEmpty: {
                message: 'Please enter your country'
              }
            }
          },
          address: {
            validators: {
              notEmpty: {
                message: 'Please enter your address'
              }
            }
          },
          city: {
            validators: {
              notEmpty: {
                message: 'Please enter your city'
              }
            }
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
                notEmpty: {
                    message: 'Please enter your password',
                },
                callback: {
                  callback: function (value, validator, $field) {
                    const value_input = value.value;

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
                    if(!symbolRegex.test(value_input)){
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
                notEmpty: {
                  message: 'Please confirm your password',
                },
                identical: {
                  compare: function () {
                    return formAuthentication.querySelector('[name="password"]').value;
                  },
                  message: 'Password confirmation and password are not the same'
                }
            },
          },
           terms: {
             validators: {
               notEmpty: {
                 message: 'Please agree terms & conditions'
               }
             }
           }
         },
         plugins: {
           trigger: new FormValidation.plugins.Trigger(),
           bootstrap5: new FormValidation.plugins.Bootstrap5({
             eleValidClass: '',
             rowSelector: '.mb-3'
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
       }).on('core.validator.validated', function(e) {
    
        if (!e.result.valid) {
            // Query all messages
            const messages = [].slice.call(formAuthentication.querySelectorAll('[data-field="' + e.field + '"][data-validator]'));
    
            messages.forEach((messageEle) => {
                const validator = messageEle.getAttribute('data-validator');
                messageEle.style.display = validator === e.validator ? 'block' : 'none';
            });
        }
        else{
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
            formValidationCountry.select2('close');
          });
      }
     }


     

   })();
 });
 