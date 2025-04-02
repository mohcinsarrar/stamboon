
document.addEventListener("DOMContentLoaded", function() {

    
  const data = {
    "order" : 1,
    "name": "Person Name",
    "dob": "1900-01-01",
    "photo": "https://randomuser.me/api/portraits/men/76.jpg",
    "translateX" : null,
    "translateY" : null,
    "parents": [
      {
        "order" : 1,
        "name": "Parent 1",
        "dob": "1870-01-01",
        "photo": "https://randomuser.me/api/portraits/men/75.jpg",
        "translateX" : null,
        "translateY" : null,
        "parents": [
            {
                "order" : 1,
                "name": "Parent 11",
                "dob": "1870-01-01",
                "photo": "https://randomuser.me/api/portraits/men/75.jpg",
                "translateX" : null,
                "translateY" : null,
                "parents": [
                  // Grandparents
                ]
              },
              {
                "order" : 2,
                "name": "Parent 12",
                "dob": "1875-01-01",
                "photo": "https://randomuser.me/api/portraits/men/74.jpg",
                "translateX" : null,
                "translateY" : null,
                "parents": [
                  // Grandparents
                ]
              }
        ]
      },
      {
        "order" : 2,
        "name": "Parent 2",
        "dob": "1875-01-01",
        "photo": "https://randomuser.me/api/portraits/men/74.jpg",
        "translateX" : null,
        "translateY" : null,
        "parents": [
            {
                "order" : 1,
                "name": "Parent 21",
                "dob": "1870-01-01",
                "photo": "https://randomuser.me/api/portraits/men/75.jpg",
                "translateX" : null,
                "translateY" : null,
                "parents": [
                  // Grandparents
                ]
              },
              {
                "order" : 2,
                "name": "Parent 22",
                "dob": "1875-01-01",
                "photo": "https://randomuser.me/api/portraits/men/74.jpg",
                "translateX" : null,
                "translateY" : null,
                "parents": [
                  // Grandparents
                ]
              }
        ]
      }
    ]
  }

    // select the main container to get width and height
    const mainGraphDiv = d3.select("div#main_graph");

    // Get the width and height of div#graph
    const width = parseInt(mainGraphDiv.style("width")) - 16;
    const height = parseInt(mainGraphDiv.style("height"));
    const radius = Math.min(width, height) / 2;
    
    const svg = d3.select("div#graph") // select the graph container
        .append("svg")  // append a svg element
        .attr("width", width) // change width and height of th svg
        .attr("height", height);

        

    const chartGroup = svg.append("g") // append a g element to scg
    .attr("transform", `translate(${width / 2}, ${height - 80})`); // center the group in the middle of the svg
    
    const tree = d3.tree() // define tree layout
        .size([Math.PI, radius - 80]) // size of the  Radial layout
        .separation((a, b) => (a.parent === b.parent ? 1 : 2) / a.depth); // spacing between nodes
    
    // convert data to hierarchy and pass wich field (parents) conatins child nodes
    const root = d3.hierarchy(data, d => d.parents); 
    tree(root);
    
    /*
    // Draw Links (Lines Between Nodes)
    const links = chartGroup.selectAll(".link") // select all links
        .data(root.links()) // Binds the root.links() data (links between parent and child nodes).
        .enter() // Uses .enter() to create path elements for each link.
        .append("path")
        .attr("class", "link") // attribute link class to lonk element
        .attr("d", d3.linkRadial() // Uses d3.linkRadial() to generate the radial link paths change angle and raduis
          .angle(d =>  d.x) // Sets the angle of the link based on the node's position
          .radius(d => d.y)) // Sets the radius of the link based on the node's depth in the hierarchy
        .style("fill", "none") // change link style fill, stroke color and widht
        .style("stroke", "#555")
        .style("stroke-width", 2);
    */
        // Dimensions of the arc
const innerRadius = 130;
const outerRadius = 150;
const rectHeight = 30; // Length of the rectangle from apex to base
const rectWidth = 20;  // Width of the rectangle (base width)

// Create arc
chartGroup.append("g")
  .append("path")
  .attr("transform", "translate(0,0)")
  .attr("d", d3.arc()({
    innerRadius: innerRadius,
    outerRadius: outerRadius,
    startAngle: -Math.PI / 2,
    endAngle: Math.PI / 2
  }))
  .attr("fill", "#ddd"); // Example fill for the arc

// Number of rectangles
const numRectangles = 4;
const angleStep = Math.PI / numRectangles; // Divide arc equally

for (let i = 0; i < numRectangles; i++) {
  // Calculate the center angle of the current segment
  const midAngle = -Math.PI / 2 + angleStep * (i + 0.5);

  // Apex of the rectangle (outer corner)
  const apex = {
    x: outerRadius * Math.cos(midAngle),
    y: outerRadius * Math.sin(midAngle)
  };

  // Base corners of the rectangle (inner radius)
  const base1 = {
    x: innerRadius * Math.cos(midAngle) - (rectWidth / 2) * Math.sin(midAngle),
    y: innerRadius * Math.sin(midAngle) + (rectWidth / 2) * Math.cos(midAngle)
  };
  const base2 = {
    x: innerRadius * Math.cos(midAngle) + (rectWidth / 2) * Math.sin(midAngle),
    y: innerRadius * Math.sin(midAngle) - (rectWidth / 2) * Math.cos(midAngle)
  };

  // Draw the rectangle
  chartGroup.append("path")
    .attr("d", d3.line()([
      [apex.x, apex.y], // Apex corner
      [base1.x, base1.y], // Base left
      [base2.x, base2.y], // Base right
      [apex.x, apex.y]   // Close the shape
    ]))
    .attr("fill", "blue") // Example fill
    .attr("stroke", "black")
    .attr("stroke-width", 1);
}

});
