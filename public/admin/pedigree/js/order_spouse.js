
function order_spouse() {
    var personInfo = selectedPerson;
    console.log(personInfo)


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let pedigree_id = get_pedigree_id();
    $.ajax({
        url: "/pedigree/getpersons/"+pedigree_id,
        type: 'POST',
        data: {'ids':personInfo.spouseIds},
        encode: true,
        dataType: 'json',
        success: function(data) {
            if (data.error == false) {
                init_order_spouses(data.persons)
            } else {
                // Handle the error - display an error message or take appropriate action
                show_toast('danger', 'error', data.msg)
            }
        },
        error: function(xhr, status, error) {
            // Handle any errors from the request
            show_toast('danger', 'error', error)
        }
    });


    jQuery(function($) {
        var panelList = $('#draggablePanelList');

        panelList.sortable({
            // Only make the .panel-heading child elements support dragging.
            // Omit this to make then entire <li>...</li> draggable.
            update: function() {
                $('.panel', panelList).each(function(index, elem) {
                     var $listItem = $(elem),
                         newIndex = $listItem.index();

                     // Persist the new indices.
                });
            }
        });
    });



    var myOffcanvas = document.getElementById('offcanvasOrderSpouse')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()

}


function init_order_spouses(spouses){

    var draggablePanelList = document.querySelector("#offcanvasOrderSpouse #draggablePanelList");
    draggablePanelList.innerHTML = '';
    spouses.forEach(spouse => {
        var birth = parseDateGlobal(spouse.birth,target_format, target_date_style = 'string', target_separator = " ",  date_style = 'number', separator = '-');
        var death = parseDateGlobal(spouse.death,target_format, target_date_style = 'string', target_separator = " ",  date_style = 'number', separator = '-');
        var photo = null;
        if (spouse.photo != undefined) {
            photo = "/storage/portraits/" + spouse.photo;
        }
        else {
            if (spouse.sex == 'M') {
                photo = treeConfiguration.maleIcon;
            }
            else {
                photo = treeConfiguration.femaleIcon;
            }
        }
        var item = `
        <div class="row mx-0 border shadow-sm mb-3 panel" style="cursor: move;" draggable="true" data-id="${spouse.personId}">
            <div class="card mb-0 border-0 shadow-none p-2">
                <div class="row g-0">
                    <div class="col-4">
                        <img src="${photo}" class="card-img card-img-left personImage rounded-0" alt="..." style="width: 80px;">
                    </div>
                    <div class="col-8">
                        <div class="card-body p-0 ms-3">
                            <h6 class="card-title name mb-2">${spouse.name}</h6>
                            <p class="card-text mb-1">Birth : <small class="text-muted birth">${birth}</small></p>
                            <p class="card-text mb-1">Death : <small class="text-muted death">${death}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        draggablePanelList.innerHTML += item;
        
    });

}

document.querySelector("#saveOrderSpouses").addEventListener('click', function () {
    var draggablePanelList = document.querySelectorAll("#offcanvasOrderSpouse #draggablePanelList .panel");
    var new_order = [];
    draggablePanelList.forEach(panel => {
        new_order.push(panel.dataset.id)
    });


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let pedigree_id = get_pedigree_id();
    $.ajax({
        url: "/pedigree/orderspouses/"+pedigree_id,
        type: 'POST',
        data: {'spouses':new_order},
        encode: true,
        dataType: 'json',
        success: function(data) {
            if (data.error == false) {
                // hide update person offcanvas
                var offCanvasElement = document.getElementById('offcanvasOrderSpouse');
                var offCanvas = bootstrap.Offcanvas.getInstance(offCanvasElement);
                offCanvas.hide();
                // hide show person info modal
                var modal = document.getElementById('nodeModal');
                modal.style.display = 'none';
                draw_tree();
                show_toast('success', 'success', data.msg)
            } else {
                // Handle the error - display an error message or take appropriate action
                show_toast('danger', 'error', data.msg)
            }
        },
        error: function(xhr, status, error) {
            // Handle any errors from the request
            show_toast('danger', 'error', error)
        }
    });
    

});

