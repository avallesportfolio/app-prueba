document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: [
      {
        title: 'Reserva Alojamiento 1',
        start: '2024-06-20',
        end: '2024-06-22'
      },
      {
        title: 'Reserva Alojamiento 2',
        start: '2024-06-25',
        end: '2024-06-28'
      },
      {
        title: 'Reserva Alojamiento 3',
        start: '2024-07-01',
        end: '2024-07-03'
      }
    ],
    eventClick: function(info) {
      Swal.fire({
        title: info.event.title,
        html: `
          <p><strong>Inicio:</strong> ${info.event.start.toISOString().slice(0, 10)}</p>
          <p><strong>Fin:</strong> ${info.event.end ? info.event.end.toISOString().slice(0, 10) : 'No especificado'}</p>
          <button id="modifyReservationBtn" class="btn btn-primary">Modificar Reserva</button>
        `,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        preConfirm: () => {
          // Return a new Promise (resolve) here if you need to do asynchronous work
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Open the modal for editing
          document.getElementById('reservationTitle').value = info.event.title;
          document.getElementById('reservationStart').value = info.event.start.toISOString().slice(0, 10);
          document.getElementById('reservationEnd').value = info.event.end ? info.event.end.toISOString().slice(0, 10) : '';
          var editModal = new bootstrap.Modal(document.getElementById('editReservationModal'));
          editModal.show();
        }
      });

      document.getElementById('modifyReservationBtn').addEventListener('click', function() {
        Swal.close(); // Close the SweetAlert2 modal
      });
    }
  });
  calendar.render();
});

  