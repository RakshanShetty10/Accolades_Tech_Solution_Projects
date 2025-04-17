"use strict";

function fullCalender() {
  $.ajax({
    url: "ajax/calendar-holiday.php",
    method: "POST",
    success: function (datas) {
      var data = JSON.parse(datas);

      var calendarEl = document.getElementById("calendar");
      var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        selectable: true,
        selectMirror: true,
        editable: true,
        initialDate: new Date().toISOString().split("T")[0], 
        weekNumbers: true,
        navLinks: true,
        nowIndicator: true,
        events: data,

        datesSet: function (info) {
          data.forEach(function (event) {
            var eventDate = event.start;
            var dayCell = document.querySelector(`[data-date="${eventDate}"]`);

            if (dayCell) {
              dayCell.classList.add("highlight-day");
            }
          });
        },

        dateClick: function (info) {
          var clickedDate = new Date(info.dateStr);
          var today = new Date();
          today.setHours(0, 0, 0, 0); // Reset time for accurate comparison

          // Allow click only if the clicked date is today or in the future
          if (clickedDate >= today) {
            var modal = document.getElementById("calendarModal");
            modal.style.display = "block";
            document.getElementById("selectedDate").value = info.dateStr;

            // Format the date for display
            var options = { year: "numeric", month: "short", day: "numeric" };
            var formattedDate = clickedDate.toLocaleDateString(
              "en-US",
              options
            );
            document.getElementById("selectedDateDisplay").textContent =
              "Date: " + formattedDate;

            var dayOfWeek = clickedDate.getDay();
            var holidayCheckbox = document.getElementById("flexCheckDefault");
            holidayCheckbox.checked = dayOfWeek === 0; // Sunday
          }
        },
      });
      calendar.render();
    },
    error: function (xhr, status, error) {
      console.error("Error fetching events data: " + error);
    },
  });
}

document.addEventListener("DOMContentLoaded", function () {
  var modal = document.getElementById("calendarModal");
  var closeBtn = document.getElementsByClassName("close")[0];

  closeBtn.onclick = function () {
    modal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
  
});

jQuery(window).on("load", function () {
  setTimeout(function () {
    fullCalender();
  }, 1000);
});
