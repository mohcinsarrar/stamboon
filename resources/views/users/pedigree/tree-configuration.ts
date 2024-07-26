export default new (class TreeConfiguration {
    chartContainer = '.chart-container'; // root svg
  
    // height & width
    nodeWidth = 150;
    nodeWidthSpouse = 320;
    nodeWidthDiff = this.nodeWidthSpouse - this.nodeWidth * 2;
    linkShift = Math.round((this.nodeWidth + this.nodeWidthDiff) / 2); // for moving the link coming from parent
    nodeHeight = 40;
    nodeHeightSpouse = 40;
  
    // margins
    siblingsMargin = 20;
    childrenMargin = 30;
    neightbourMargin = 10;
    rootMargin = 0;
  
    // child & spouse lines
    linkStroke = 'lightgray';
    linkStrokeWidth = 2;
    connectionStroke = 'black';
    connectionStrokeWidth = 2;
  })();