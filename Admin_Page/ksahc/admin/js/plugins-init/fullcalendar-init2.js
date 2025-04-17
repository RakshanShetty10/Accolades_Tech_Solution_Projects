"use strict";

function fullCalender() {
  $.ajax({
    url: "ajax/calendar.php",
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
        // initialDate: "2024-10-15",
        initialDate:new Date(),
        weekNumbers: true,
        navLinks: true,
        nowIndicator: true,
        events: data,

        selectAllow: function (selectInfo) {
          var selectedDay = selectInfo.start.getUTCDay();
          var today = new Date();
          today.setHours(0, 0, 0, 0);
          return selectedDay !== 0 && selectInfo.start >= today;
        },

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
          var selectedDay = new Date(info.dateStr).getUTCDay();

          if (selectedDay !== 0 && new Date(info.dateStr) >= new Date()) {
            var modal = document.getElementById("calendarModal");
            var isEventOnDate = data.some(function (event) {
              return event.start === info.dateStr;
            });

            if (!isEventOnDate) {
              modal.style.display = "block";
              document.getElementById("selectedDate").value = info.dateStr;

              var selectedDate = new Date(info.dateStr);
              var options = { year: "numeric", month: "short", day: "numeric" };
              var formattedDate = selectedDate.toLocaleDateString(
                "en-US",
                options
              );

              document.getElementById("selectedDateDisplay").textContent =
                "Date: " + formattedDate;

              $.ajax({
                url: "ajax/calendar-drop.php",
                method: "POST",
                data: { date: info.dateStr },
                success: function (datam) {
                  document.getElementById("slotDropdown").innerHTML = datam;
                },
              });

              $.ajax({
                url: "ajax/calendar-slot.php",
                method: "POST",
                data: { date: info.dateStr },
                success: function (datas) {
                  document.getElementById("slotdata").innerHTML = datas;
                },
              });
              $.ajax({
                url: "ajax/calender-booked.php",
                method: "POST",
                data: { date: info.dateStr },
                success: function (datas) {
                  document.getElementById("slotdata2").innerHTML = datas;
                },
              });
            }
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
