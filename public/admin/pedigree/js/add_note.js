// inti params
const padding = 10;
const maxWidth = 250;  // Maximum width before wrapping occurs
const textSize = "18px";  // Text size
const fontFamily = "Arial"; // Font family
const rectWidth = maxWidth + padding * 2;
const lineHeight = 20; // Height of each line of text



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
            .attr("transform", `translate(${event.x}, ${event.y})`);
        var note_id = d3.select(this).attr("note_id");
        change_position(note_id, event.x, event.y);
    });

// change position of the note in DB
function change_position(note_id, xpos, ypos) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/editnoteposition",
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

// change text of the note in DB
function change_text(note_id, text) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/editnotetext",
        type: 'POST',
        data: {
            'note_id': note_id,
            'content': text,
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
                show_toast('success', 'success', 'note edited with success')
                
            } else {
                show_toast('danger', 'error', "can't edit note, please try again !")
            }

        },
        error: function (xhr, status, error) {
            show_toast('danger', 'error', "can't edit note, please try again !")
        }
    });
}
// Function to get the current position of a group (g) element
function getCurrentPosition(element) {
    const transform = element.attr("transform");
    if (transform) {
        const translate = transform.match(/translate\(([^,]+),([^\)]+)\)/);
        if (translate) {
            return {
                x: parseFloat(translate[1]),
                y: parseFloat(translate[2])
            };
        }
    }
    return { x: 0, y: 0 };
}

// Function to show the toolbar
function showToolbar(rectGroup) {
    var note_id = rectGroup.attr("note_id");
    // Remove any existing toolbars
    d3.selectAll(".toolbar").remove();

    // Get the current position of the rectangle group
    const { x: currentX, y: currentY } = getCurrentPosition(rectGroup);

    // Append a new toolbar group
    const toolbar = d3.select("#graph svg").append("g")
        .attr("class", "toolbar");

    // Add a background rectangle for the toolbar
    const toolbarWidth = 105;
    const toolbarHeight = 30;
    toolbar.append("rect")
        .attr("x", currentX)
        .attr("y", currentY - toolbarHeight - 5) // Position above the rectangle
        .attr("width", toolbarWidth)
        .attr("height", toolbarHeight)
        .attr("fill", "lightgray")
        .attr("rx", 5); // Rounded corners

    // Add the Edit button
    toolbar.append("text")
        .attr("x", currentX + 10)
        .attr("y", currentY - toolbarHeight / 2 - 5)
        .attr("dy", "0.35em")
        .text("Edit")
        .style("cursor", "pointer")
        .on("click", function () {
            // Handle edit functionality
            var currentText = rectGroup.selectAll("text")
            .nodes() // Get all the text nodes
            .map(node => node.textContent) // Extract the text content from each node
            .join(" ");

            edit_note(currentText, function (newText) {
                // Update the rectangle's text with the new text
                updateRectangleText(rectGroup, newText, maxWidth, padding, lineHeight)
                change_text(note_id, newText)
            });
            toolbar.remove();
        });

    // Add the Delete button
    toolbar.append("text")
        .attr("x", currentX + 55)
        .attr("y", currentY - toolbarHeight / 2 - 5)
        .attr("dy", "0.35em")
        .text("Delete")
        .style("cursor", "pointer")
        .on("click", function () {
            // Handle delete functionality
            delete_note(note_id);
            rectGroup.remove(); // Remove the rectangle group
            toolbar.remove();   // Remove the toolbar
            
            
        });
}

// function to add note to DB and draw the note in the chart
function add_note(text, xPos, yPos) {

    
    // save note to DB if its a new note
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/savenote",
        type: 'POST',
        data: {
            'content': text,
            'xpos': xPos,
            'ypos': yPos
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
                draw_note(text,xPos,yPos,data.note_id)
                show_toast('success', 'success', 'note added with success')
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

// function to edit note when user click on edit button on toolbar
function edit_note(currentText, onSave) {
    // Show modal and overlay
    document.querySelector('#editNoteModal #note').value = "";
    var myModal = new bootstrap.Modal(document.getElementById('editNoteModal'))

    // Set current text in the textarea
    document.querySelector('#editNoteModal #note').value = currentText;

    myModal.show()
    // Handle the edit button click in the modal
    document.querySelector('#editNoteModal #editNoteBtn').onclick = function () {
        const newText = document.querySelector('#editNoteModal #note').value;
        onSave(newText);  // Call the onSave callback with the new text
        
        // Hide modal and overlay
        myModal.hide()
    };


}

// function to delete note
function delete_note(note_id){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/deletenote",
        type: 'POST',
        data: {
            'note_id': note_id,
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
                show_toast('success', 'success', 'note deleted with success')
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
// get notes from DB
function get_notes() {

    // get notes from DB for the current padigree
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/getnotes",
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
function draw_note(text,xPos,yPos,note_id) {
    // draw the note in the graph
    const textContent = text;

    // Wrap the text into lines based on maxWidth
    const lines = wrapText(textContent, maxWidth);
    const rectHeight = lines.length * lineHeight + padding * 2; // Adjust height based on number of lines
    // Append the rectangle to the chart's SVG container

    const rectGroup = d3.select("#graph svg")
        .append("g")
        .attr("class", "draggable")
        .attr("note_id", note_id)
        .attr("transform", `translate(${xPos}, ${yPos})`) // Set initial position
        .on("click", function () {
            // Show toolbar when the rectangle is clicked
            showToolbar(d3.select(this));
        });

    // Append the rectangle to the group
    rectGroup.append("rect")
        .attr('x', 0) // Rectangle position relative to the group
        .attr('y', 0)
        .attr('width', rectWidth)
        .attr('height', rectHeight)
        .attr('fill', 'steelblue'); // Customize the fill color

    // Append each line of text inside the rectangle
    lines.forEach((line, index) => {
        rectGroup.append("text")
            .attr('x', padding) // Position the text with padding
            .attr('y', padding + index * lineHeight + lineHeight / 2) // Position each line
            .attr('dy', '0.35em') // Adjust the text baseline to the middle
            .text(line)
            .attr('fill', 'white') // Customize the text color
            .style("font-size", textSize) // Set font size for measuring width
            .style("font-family", fontFamily); // Set font family for measuring width
    });



    // Apply drag behavior to the group
    rectGroup.call(drag);
}
// update a rect text when user aedit the note
function updateRectangleText(rectGroup, newText, maxWidth, padding, lineHeight) {
    // Wrap the new text into lines based on maxWidth
    const lines = wrapText(newText, maxWidth);

    // Calculate the new height of the rectangle based on the number of lines
    const rectHeight = lines.length * lineHeight + padding * 2;

    // Update the height of the rectangle
    rectGroup.select("rect")
        .attr('height', rectHeight);

    // Remove existing text elements inside the rectangle
    rectGroup.selectAll("text").remove();

    // Append each line of the new text inside the rectangle
    lines.forEach((line, index) => {
        rectGroup.append("text")
            .attr('x', padding) // Position the text with padding
            .attr('y', padding + index * lineHeight + lineHeight / 2) // Position each line
            .attr('dy', '0.35em') // Adjust the text baseline to the middle
            .text(line)
            .attr('fill', 'white') // Customize the text color
            .style("font-size", "16px") // Set text size
            .style("font-family", "Arial"); // Set font family
    });
}

// create multiline text ftom text according to the max width
function wrapText(text, maxWidth) {
    const words = text.split(" ");
    let line = [];
    let lines = [];

    const tempText = d3.select("#graph svg").append("text")
        .attr("x", -9999) // Position it off-screen
        .attr("y", -9999)
        .attr("opacity", 0)
        .style("font-size", textSize) // Set font size for measuring width
        .style("font-family", fontFamily); // Set font family for measuring width

    words.forEach((word) => {
        line.push(word);
        tempText.text(line.join(" "));
        const lineWidth = tempText.node().getBBox().width;

        if (lineWidth > maxWidth && line.length > 1) {
            line.pop();
            lines.push(line.join(" "));
            line = [word];
        }
    });

    lines.push(line.join(" "));
    tempText.remove();
    return lines;
}

// show modal to add a note
$(document).on("click", "#addNote", function () {
    apply_change_node_position()
    document.querySelector('#addNoteModal #note').value = "";
    document.querySelector('#addNoteModal #addNoteBtn').disabled = true;
    var myModal = new bootstrap.Modal(document.getElementById('addNoteModal'))
    myModal.show()
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

// add note when user click on add note butto on modal #addNoteModal
$(document).on("click", "#addNoteBtn", function () {
    var text = document.querySelector('#addNoteModal #note').value;
    const xPos = 100; // X position on the chart
    const yPos = 50;  // Y position on the chart
    add_note(text, xPos, yPos);
    var myModalEl = document.getElementById('addNoteModal')
    var modal = bootstrap.Modal.getInstance(myModalEl) // Returns a Bootstrap modal instance
    modal.hide();
});


