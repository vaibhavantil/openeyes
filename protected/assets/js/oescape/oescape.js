$(document).ready(function () {

  var left = $('.oes-left-side'),
    right = $('.oes-right-side'),
    size, percent;


// setup resize buttons
  // buttons have data-area attribute: small, medium, large and full
  $('.js-oes-area-resize').click(function( e ){
    e.stopPropagation();
    $('.js-oes-area-resize.selected').removeClass('selected');
    $(this).addClass('selected');
    var str = $(this).data('area');
    switch(str){
      case 'small':
        size = 500;
        percent = '30%';
        break;
      case 'medium':
        size = 700;
        percent = '50%';
        break;
      case 'large':
        size = 900;
        percent = '70%';
        break;
      case 'full':
        size = null;  // null, when passed to highcharts makes chart fill container
        break;
    }

    // fullsize requires some tweaking
    if(size == null){
      left.css({"min-width":"500px", "width":"100%"});
      right.hide();
    } else {
      left.css({"min-width": size + "px", "width": percent});
      right.show();
    }
    var highcarts_list = $('.highchart-section');
    for (var i = 0; i<  highcarts_list.length; i++){
      if ($(highcarts_list[i]).is(":visible")){
        $(highcarts_list[i]).highcharts().reflow();
      }
    }
  });

  //switch between right and left eye
  $('.js-oes-eyeside-right').click(function (e) {
    e.preventDefault();
    $(this).addClass('selected');
    $('.js-oes-eyeside-left').removeClass('selected');
    $('.highcharts-right').show();
    $('.highcharts-left').hide();
    $('.highcharts-right').highcharts().redraw(e);

  });

  $('.js-oes-eyeside-left').click(function (e) {
    e.preventDefault();
    $(this).addClass('selected');
    $('.js-oes-eyeside-right').removeClass('selected');
    $('.highcharts-right').hide();
    $('.highcharts-left').show();
    $('.highcharts-left').highcharts().redraw(e);
  });

  // exit oescape and go back to last viewed (non-oes) page
  $('#js-exit-oescape').click( function(){
    if(localStorage.getItem("lastPage")){
      window.location = localStorage.getItem("lastPage");
    } else {
      window.location.href = $(this).data('link');
    }
  });

});


function addSeries(chart, title, data, options){
  chart.addSeries(Object.assign({
    name: title,
    data: data,
  }, options));
}

function setYLabels(tick_positions, tick_labels){
  return {
    formatter: function () {
      for (var i=0; i<tick_positions.length; i++)
        if (tick_positions[i] === this.value)
          return tick_labels[i];
      return null;
    }
  }
}

//Takes a list (sorted smallest to largest) and removes overlapping labels
function pruneYTicks(ticks, plotHeight, label_height){
  var new_ticks = [];
  new_ticks['tick_position'] = [];
  new_ticks['tick_labels'] = [];
  var plot_min = Math.min.apply(null, ticks.tick_position);
  var plot_max = Math.max.apply(null, ticks.tick_position);
  var high_point = Number.NEGATIVE_INFINITY;
  var plot_per_data = plotHeight / (plot_max - plot_min);

  for( var ii = 0; ii < ticks.tick_position.length; ii++){
    var tick_lower = (ticks.tick_position[ii] - plot_min) * plot_per_data - (label_height / 2);
    if(tick_lower < high_point){
      continue;
    }
    high_point = tick_lower + label_height;

    new_ticks.tick_position.push(ticks.tick_position[ii]);
    new_ticks.tick_labels.push(ticks.tick_labels[ii]);
  }
  return new_ticks;
}

function cleanVATicks(ticks, options, charts, axis_index){
  //The magic number here is pretty much unobtainable, it refers to the height of the label, if you can get it
  //programmatically, please do it
  ticks = pruneYTicks(ticks, charts.right.yAxis[axis_index].height, 12);
  options['yAxis'][axis_index]['tickPositions'] = ticks['tick_position'];
  options['yAxis'][axis_index]['labels'] = setYLabels(ticks['tick_position'], ticks['tick_labels']);
  charts.left.update(options);
  charts.right.update(options);
  charts.left.redraw();
  charts.right.redraw();
}