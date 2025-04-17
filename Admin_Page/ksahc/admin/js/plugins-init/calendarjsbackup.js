document.addEventListener('DOMContentLoaded', function() {
  var Calendar = FullCalendar.Calendar;
  var calendarEl = document.getElementById('calendar');

  $.ajax({
      url: "ajax/calendar.php",
      method: "POST",
      success: function(datas) {
          var data = JSON.parse(datas);

          var calendar = new Calendar(calendarEl, {
              headerToolbar: {
                  left: 'prev,next today',
                  center: 'title',
                  right: 'dayGridMonth,timeGridWeek,timeGridDay'
              },
              initialDate: '2024-10-01',
              navLinks: true,
              editable: true,
              droppable: true,
              dayMaxEvents: true,
              events: data, 
              eventDidMount: function(info) {
                    let eventDate = '2024-10-16';
                    let dayCell = document.querySelector(`[data-date="${eventDate}"]`);

                    if (dayCell) {
                        dayCell.classList.add('highlight-day');
                    }
                },
              eventClick: function(info) {

                  var start = info.event.start;
                  
                  document.getElementById('eventModal').classList.add('active');
              }
          });

          calendar.render();
      }
  });
});



