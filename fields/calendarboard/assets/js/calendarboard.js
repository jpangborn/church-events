function builtCalendarBoard(M,Y){
    
    var M = M || $("#calendarboard").attr("data-month");
    var Y = Y || $("#calendarboard").attr("data-year");   
    
    var field_name = $("#calendarboard").attr("data-name");
    
    var _url = window.location.href;
    _url = _url.replace("/edit", "/field");
    _url += "/" + field_name + "/calendarboard/";
    
    // Dragging variables
    var event__index;
    var day__from;
    var day__to;    
    
    $.ajax({
        url: _url + "get-month-board/" + M + "/" + Y,
        type: 'GET',
        success: function(board) {
          $("#calendarboard").html(board);
          
          // event draggable
          $("div.event").draggable({
            containment: "#calendarboard",
            revert: "invalid",
            start: function(event, ui) {
              $(this).css("z-index",100);
              event__index = $(this).index();
              day__from = $(this).closest("a.day").attr("data-day");
            },
            stop: function(event, ui) {
              $(this).css("z-index",1);
            },            
          });
          
          // day droppable
          $("a.day").droppable({
            accept: "div.event",
            hoverClass: "drag-hover",
            drop: function(event, ui) {
              day__to = $(this).attr("data-day");
              //console.log(_url + "move-event/" + event__index + "/" + day__from + "/" + day__to);
              location.href = _url + "move-event/" + event__index + "/" + day__from + "/" + day__to
              
            }
          });          
        }
    });    
}

(function($) {
  $.fn.createCalendar = function() {
    return builtCalendarBoard();
  }
}(jQuery));