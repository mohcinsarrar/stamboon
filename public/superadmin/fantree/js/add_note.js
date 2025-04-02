// inti params
const padding = 10;
const maxWidth = 250;
const maxHeight = 250;
const textSize = "18px";  // Text size
const fontFamily = "Arial"; // Font family
const rectWidth = maxWidth + padding * 2;
const lineHeight = 20; // Height of each line of text
const maxChars = 250;
let note_id_draggable;
// inti the drag behavior for note
const drag = d3.drag()
    .on("start", function (event, d) {
        // Bring the group to the front on drag start
        d3.select(this).raise();
    })
    .on("drag", function (event, d) {

        // Move the group
        d3.selectAll(".toolbar").remove();
        d3.select(this)
            .transition()
            .duration(1)
            .attr("transform", `translate(${event.x - maxWidth / 2}, ${event.y - maxHeight / 2})`);
        note_id_draggable = d3.select(this).attr("note_id");


    })
    .on("end", (event) => {

        change_position(note_id_draggable, event.x - maxWidth / 2, event.y - maxHeight / 2);
    });

// change position of the note in DB
function change_position(note_id, xpos, ypos) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/superadmin/fantree/editnoteposition/"+fantree_id,
        type: 'POST',
        data: {
            'note_id': note_id,
            'xpos': xpos,
            'ypos': ypos
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
            } else {
                return null;
            }

        },
        error: function (xhr, status, error) {
            return null;
        }
    });
}





// get notes from DB
function get_notes() {

    d3.select("#graph svg").selectAll('.draggable').remove();
    // get notes from DB for the current padigree
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/superadmin/fantree/getnotes/"+fantree_id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
                var notes = data.notes
                notes.forEach((note, index) => {
                    draw_note(note['content'], note['xpos'], note['ypos'], note['id'])
                });
            } else {
                show_toast('danger', 'error', "can't get notes, please try again !")
                return null;
            }

        },
        error: function (xhr, status, error) {
            show_toast('danger', 'error', "can't get notes, please try again !")
            return null;
        }
    });



}

// draw note on the graph
function draw_note(text, xPos, yPos, note_id) {
    // draw the note in the graph
    const textContent = text;
    const imageUrl = `/assets/img/notesbg/note${treeConfiguration.note_type}.png`

    const rectGroup = d3.select("#graph svg")
        .append("g")
        .attr("class", "draggable")
        .attr("note_id", note_id)
        .attr("transform", `translate(${xPos}, ${yPos})`) // Set initial position
        .on("mouseover", function () {
            d3.select(this)
                .select('#toolbar')
                .style('visibility', 'visible')
        })
        .on("mouseout", function () {
            d3.select(this)
                .select('#toolbar')
                .style('visibility', 'hidden')
        });

    // Append the rectangle to the group
    rectGroup.append("foreignObject")
        .attr('x', 0) // Rectangle position relative to the group
        .attr('y', 0)
        .attr('width', maxWidth)
        .attr('height', maxHeight)
        .html(d => {

            

            const toolbar_style =
                `
                visibility:hidden;
                font-family:var(--bs-body-font-family); 
                margin-bottom:10px
                `;
            const toolbar =
                `
                <div id="toolbar" class="btn-group" role="group" aria-label="Basic example" style="${toolbar_style}">
                    <button onclick="edit_note_modal(${note_id})" data-note-id="${note_id}" id="editNote" type="button" class="p-2 btn btn-label-secondary waves-effect"><i class="ti ti-pencil fs-4"></i></button>
                    <button onclick="delete_note(${note_id})" data-note-id="${note_id}" id="deleteNote" type="button" class="p-2 btn btn-label-secondary waves-effect"><i class="ti ti-trash fs-4"></i></button>
                </div>
                `;
            // note4, note5, note1

            const text_style =
                `
                font-family:'Charm';
                font-weight:200;
                font-style:normal;
                font-size:14px;
                text-align: justify;
                text-justify: distribute;
                color:${treeConfiguration.note_text_color};
                background-image: url(${imageUrl});
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
                width:${maxWidth}px;
                height:${maxHeight - 50}px;
                
                `;

            const text =
                `
                <div id="note-${note_id}" style="${text_style}">
                    <p data-note-id="${note_id}" class="noteText" style="padding:20px;margin:0px;">${textContent}</p>
                </div>
                `;


            return `${toolbar}${text}`;
        }); // Customize the fill color

    
    convertImageToBase64(imageUrl, function (base64String) {
        const replaced = base64String.replace(/(\r\n|\n|\r)/gm);
        rectGroup.select("#note-"+note_id).style('background-image',`url(${replaced}`)
    });

    // Apply drag behavior to the group
    rectGroup.call(drag);
}


// function to delete note
function delete_note(note_id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/superadmin/fantree/deletenote",
        type: 'POST',
        data: {
            'note_id': note_id,
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
                show_toast('success', 'success', 'note deleted with success')
                get_notes()
            } else {
                show_toast('danger', 'error', "can't delete note, please try again !")
                return null;
            }

        },
        error: function (xhr, status, error) {
            show_toast('danger', 'error', "can't add delete, please try again !")
            return null;
        }
    });
}


function edit_note_modal(note_id) {


    // Show modal and overlay
    document.querySelector('#editNoteModal #note').value = "";
    var myModal = new bootstrap.Modal(document.getElementById('editNoteModal'))

    // Set current text in the textarea
    const current_text = document.querySelector('.noteText[data-note-id="' + note_id + '"]').textContent
    document.querySelector('#editNoteModal #note').value = current_text;
    if (current_text != '') {
        document.querySelector('#editNoteModal #editNoteBtn').disabled = false;
    }
    else {
        document.querySelector('#editNoteModal #editNoteBtn').disabled = true;
    }

    myModal.show()

    const textArea = document.querySelector('#editNoteModal #note');
    const charCounter = document.querySelector("#editNoteModal #charCounter");
    
    charCounter.textContent = `${textArea.value.length} / ${maxChars}`;

    textArea.addEventListener("input", () => {
      const currentLength = textArea.value.length;

      // Update the counter
      

      // Disable input if max length is reached
      if (currentLength > maxChars) {
        textArea.value = textArea.value.substring(0, maxChars); // Ensure no excess characters
        charCounter.classList.add("warning");
      } else {
        charCounter.classList.remove("warning");
        charCounter.textContent = `${currentLength} / ${maxChars}`;
      }
    });
    
    // Handle the edit button click in the modal
    document.querySelector('#editNoteModal #editNoteBtn').onclick = function () {
        const newText = document.querySelector('#editNoteModal #note').value;
        if(newText.length > maxChars){
            show_toast('warning', 'warning', `note text max lenght is ${maxChars}`)
            return;
        } 
        change_text(note_id, newText)

        // Hide modal and overlay
        myModal.hide()
    };
}


// change text of the note in DB
function change_text(note_id, text) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/superadmin/fantree/editnotetext/"+fantree_id,
        type: 'POST',
        data: {
            'note_id': note_id,
            'content': text,
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
                show_toast('success', 'success', 'note edited with success')
                get_notes()

            } else {
                show_toast('danger', 'error', "can't edit note, please try again !")
            }

        },
        error: function (xhr, status, error) {
            show_toast('danger', 'error', "can't edit note, please try again !")
        }
    });
}
// change the disabled params for the button edit note when change note text
$(document).on("input", "#editNoteModal #note", function () {
    var text = document.querySelector('#editNoteModal #note').value;
    if (text != "") {
        document.querySelector('#editNoteModal #editNoteBtn').disabled = false;
    }
    else {
        document.querySelector('#editNoteModal #editNoteBtn').disabled = true;
    }
});




// show modal to add a note
$(document).on("click", "#addNote", function () {
    document.querySelector('#addNoteModal #note').value = "";
    document.querySelector('#addNoteModal #addNoteBtn').disabled = true;
    var myModal = new bootstrap.Modal(document.getElementById('addNoteModal'))
    myModal.show()

    const textArea = document.querySelector('#addNoteModal #note');
    const charCounter = document.querySelector("#addNoteModal #charCounter");

    charCounter.textContent = `${textArea.value.length} / ${maxChars}`;

    textArea.addEventListener("input", () => {
      const currentLength = textArea.value.length;

      // Update the counter
      

      // Disable input if max length is reached
      if (currentLength > maxChars) {
        textArea.value = textArea.value.substring(0, maxChars); // Ensure no excess characters
        charCounter.classList.add("warning");
      } else {
        charCounter.classList.remove("warning");
        charCounter.textContent = `${currentLength} / ${maxChars}`;
      }
    });
});

// change the disabled params for the button add note when change note text
$(document).on("input", "#addNoteModal #note", function () {
    var text = document.querySelector('#addNoteModal #note').value;
    if (text != "") {
        document.querySelector('#addNoteModal #addNoteBtn').disabled = false;
    }
    else {
        document.querySelector('#addNoteModal #addNoteBtn').disabled = true;
    }
});

// add note when user click on add note butto on modal #addNoteModal
$(document).on("click", "#addNoteBtn", function () {
    var text = document.querySelector('#addNoteModal #note').value;
    if(text.length > 250){
        show_toast('warning', 'warning', `note text max lenght is ${maxChars}`)
        return;
    } 
    const xPos = 100; // X position on the chart
    const yPos = 50;  // Y position on the chart
    add_note(text, xPos, yPos);
    var myModalEl = document.getElementById('addNoteModal')
    var modal = bootstrap.Modal.getInstance(myModalEl) // Returns a Bootstrap modal instance
    modal.hide();
});

// function to add note to DB and draw the note in the chart
function add_note(text, xPos, yPos) {


    // save note to DB if its a new note
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/superadmin/fantree/savenote/"+fantree_id,
        type: 'POST',
        data: {
            'content': text,
            'xpos': xPos,
            'ypos': yPos
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
                
                show_toast('success', 'success', 'note added with success')
                get_notes()
            } else {
                show_toast('danger', 'error', "can't add note, please try again !")
                return null;
            }

        },
        error: function (xhr, status, error) {
            show_toast('danger', 'error', "can't add note, please try again !")
            return null;
        }
    });


}







