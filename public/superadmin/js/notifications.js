/**
 * App Email
 */

 'use strict';

 document.addEventListener('DOMContentLoaded', function () {
   (function () {
    load_notification()
     const emailList = document.querySelector('.email-list'),
       emailListItems = [].slice.call(document.querySelectorAll('.email-list-item')),
       emailListItemInputs = [].slice.call(document.querySelectorAll('.email-list-item-input')),
       emailView = document.querySelector('.app-email-view-content'),
       emailFilters = document.querySelector('.email-filters'),
       emailFilterByFolders = [].slice.call(document.querySelectorAll('.email-filter-folders li')),
       emailEditor = document.querySelector('.email-editor'),
       appEmailSidebar = document.querySelector('.app-email-sidebar'),
       appOverlay = document.querySelector('.app-overlay'),
       emailReplyEditor = document.querySelector('.email-reply-editor'),
       bookmarkEmail = [].slice.call(document.querySelectorAll('.email-list-item-bookmark')),
       selectAllEmails = document.getElementById('email-select-all'),
       emailSearch = document.querySelector('.email-search-input'),
       toggleCC = document.querySelector('.email-compose-toggle-cc'),
       toggleBCC = document.querySelector('.email-compose-toggle-bcc'),
       emailCompose = document.querySelector('.app-email-compose'),
       emailListDelete = document.querySelector('.email-list-delete'),
       emailListRead = document.querySelector('.email-list-read'),
       refreshEmails = document.querySelector('.email-refresh'),
       emailViewContainer = document.getElementById('app-email-view'),
       emailFilterFolderLists = [].slice.call(document.querySelectorAll('.email-filter-folders li')),
       emailListItemActions = [].slice.call(document.querySelectorAll('.email-list-item-actions li'));
    
       // load notification
       function load_notification(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        
            $.ajax({
                url: "/profile/notifications/load",
                type: 'GET',
                encode: true,
                dataType: 'json',
                success: function(data) {
                    if (data.error == false) {
                        // get notifications list
                        var notifications = data.notifications
                        var notifications_unread = 0;

                        var list = document.querySelector('div.email-list ul');
                        console.log(list)
                        list.innerHTML = "";
                        notifications.forEach(notification => {
                            var notification_html = `<li class="email-list-item" data-starred="true" data-bs-toggle="sidebar" data-target="#app-email-view">
                                <div class="d-flex align-items-center">
                                <div class="form-check mb-0">
                                    <input class="email-list-item-input form-check-input" type="checkbox" id="email-${notification.id}">
                                    <label class="form-check-label" for="email-1"></label>
                                </div>
                                <div class="email-list-item-content ms-2 ms-sm-0 me-2">
                                    <span class="h6 email-list-item-username me-2">${notification.title}</span>
                                    <span class="email-list-item-subject d-xl-inline-block d-block"> ${notification.subtitle}</span>
                                </div>
                                <div class="email-list-item-meta ms-auto d-flex align-items-center">`;
                                    if(notification.read_at == null){
                                        notifications_unread = notifications_unread + 1;
                                        notification_html = notification_html +`<span class="email-list-item-label badge badge-dot bg-danger d-none d-md-inline-block me-2" data-label="private"></span>`;
                                    }
                                notification_html = notification_html +`<small class="email-list-item-time text-muted" style="width: 100px !important;">${notification.created_at}</small>
                                    <ul class="list-inline email-list-item-actions text-nowrap">
                                    <li class="list-inline-item email-read " > <i class='ti ti-mail-opened markasread' id="markasread-${notification.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark as read"></i> </li>
                                    <li class="list-inline-item email-delete" > <i class='ti ti-trash delete' id="delete-${notification.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></i></li>
                                    </ul>
                                </div>
                                </div>
                            </li>`;
                            list.innerHTML += notification_html;
                        });

                        if(document.querySelector('#notifications-count') == null){
                            return false;
                        }
                        // notification unread count
                        document.querySelector('#notifications-count').innerHTML = notifications_unread;

                        // mars as read single
                        [].slice.call(document.querySelectorAll('.markasread')).forEach(emailListItemInput => {
                            emailListItemInput.addEventListener('click', e => {
                                var id = e.target.id.replace("markasread-", "")
                                var item_selected = [id]
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                            
                                $.ajax({
                                    url: "/profile/notifications/markasread",
                                    type: 'POST',
                                    data: {
                                        'notifications': item_selected,
                                    },
                                    encode: true,
                                    dataType: 'json',
                                    success: function(data) {
                                        if (data.error == false) {
                                            show_toast('success', 'success', data.msg);
                                            load_notification()
                                            document.getElementById('email-select-all').checked = 0;
                                            [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => (c.checked = 0));
                                        } else {
                                            show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                                        }
                            
                                    },
                                    error: function(xhr, status, error) {
                                        show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                                        return null;
                                    }
                                });
                            });
                        });

                        // mars as read single
                        [].slice.call(document.querySelectorAll('.delete')).forEach(emailListItemInput => {
                            emailListItemInput.addEventListener('click', e => {
                                var id = e.target.id.replace("delete-", "")
                                var item_selected = [id]
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                            
                                $.ajax({
                                    url: "/profile/notifications/delete",
                                    type: 'POST',
                                    data: {
                                        'notifications': item_selected,
                                    },
                                    encode: true,
                                    dataType: 'json',
                                    success: function(data) {
                                        if (data.error == false) {
                                            show_toast('success', 'success', data.msg);
                                            load_notification()
                                            document.getElementById('email-select-all').checked = 0;
                                            [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => (c.checked = 0));
                                        } else {
                                            show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                                        }
                            
                                    },
                                    error: function(xhr, status, error) {
                                        show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                                        return null;
                                    }
                                });
                            });
                        });
                        
                    } else {
                        show_toast('danger', 'error', "can't load notifications, please try again !")
                    }
        
                },
                error: function(xhr, status, error) {
                    show_toast('danger', 'error', "can't load notifications, please try again !")
                    return null;
                }
            });
       }
     // Initialize PerfectScrollbar
     // ------------------------------
     // Email list scrollbar
     if (emailList) {
       let emailListInstance = new PerfectScrollbar(emailList, {
         wheelPropagation: false,
         suppressScrollX: true
       });
     }
 
     // Sidebar tags scrollbar
     if (emailFilters) {
       new PerfectScrollbar(emailFilters, {
         wheelPropagation: false,
         suppressScrollX: true
       });
     }
 
     // Email view scrollbar
     if (emailView) {
       new PerfectScrollbar(emailView, {
         wheelPropagation: false,
         suppressScrollX: true
       });
     }


 
     // Select all
    document.getElementById('email-select-all').addEventListener('click', e => {
        
         if (e.currentTarget.checked) {
           [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => (c.checked = 1));
         } else {
           [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => (c.checked = 0));
         }
       });
     
    // mark as read all
     var markasread_all = document.querySelector("#markasread-all");
     
     markasread_all.addEventListener('click', e => {
        var item_selected = [];
        [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => {
            if(c.checked){
                var id = c.id.replace("email-", "")
                item_selected.push(parseInt(id))
            }
        });
        if(item_selected.length == 0){
            show_toast('warning', 'Warning', "select at least one notification")
            return false;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        $.ajax({
            url: "/profile/notifications/markasread",
            type: 'POST',
            data: {
                'notifications': item_selected,
            },
            encode: true,
            dataType: 'json',
            success: function(data) {
                if (data.error == false) {
                    show_toast('success', 'success', data.msg);
                    load_notification()
                    document.getElementById('email-select-all').checked = 0;
                    [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => (c.checked = 0));
                } else {
                    show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                }
    
            },
            error: function(xhr, status, error) {
                show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                return null;
            }
        });

        
      });
      
      // delete all
      var delete_all = document.querySelector("#delete-all");
     
      delete_all.addEventListener('click', e => {
        var item_selected = [];
        [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => {
            if(c.checked){
                var id = c.id.replace("email-", "")
                item_selected.push(parseInt(id))
            }
        });
        if(item_selected.length == 0){
            show_toast('warning', 'Warning', "select at least one notification")
            return false;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        $.ajax({
            url: "/profile/notifications/delete",
            type: 'POST',
            data: {
                'notifications': item_selected,
            },
            encode: true,
            dataType: 'json',
            success: function(data) {
                if (data.error == false) {
                    show_toast('success', 'success', data.msg);
                    load_notification()
                    document.getElementById('email-select-all').checked = 0;
                    [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(c => (c.checked = 0));
                } else {
                    show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                }
    
            },
            error: function(xhr, status, error) {
                show_toast('danger', 'error', "can't mark notifications as read, please try again !")
                return null;
            }
        });

        
      });
     // Select single email
       [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(emailListItemInput => {
         emailListItemInput.addEventListener('click', e => {
           e.stopPropagation();
           // Check input count to reset the indeterminate state
           let emailListItemInputCount = 0;
           [].slice.call(document.querySelectorAll('.email-list-item-input')).forEach(emailListItemInput => {
             if (emailListItemInput.checked) {
               emailListItemInputCount++;
             }
           });
 
           if (emailListItemInputCount < [].slice.call(document.querySelectorAll('.email-list-item-input')).length) {
             if (emailListItemInputCount == 0) {
               selectAllEmails.indeterminate = false;
             } else {
               selectAllEmails.indeterminate = true;
             }
           } else {
             if (emailListItemInputCount == [].slice.call(document.querySelectorAll('.email-list-item-input')).length) {
               selectAllEmails.indeterminate = false;
               selectAllEmails.checked = true;
             } else {
               selectAllEmails.indeterminate = false;
             }
           }
         });
       });
       
       


 
     // Delete multiple email
     if (emailListDelete) {
       emailListDelete.addEventListener('click', e => {
         emailListItemInputs.forEach(emailListItemInput => {
           if (emailListItemInput.checked) {
             emailListItemInput.parentNode.closest('li.email-list-item').remove();
           }
         });
         selectAllEmails.indeterminate = false;
         selectAllEmails.checked = false;
       });
     }



   })();
 });
 