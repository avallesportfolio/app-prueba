document.addEventListener('DOMContentLoaded', function() {
  // Obtener los datos del usuario del localStorage
  const user = JSON.parse(localStorage.getItem('user'));

  if (user) {
      // Actualizar la imagen de perfil (si tienes una URL de imagen en los datos del usuario)
      const profileImage = document.getElementById('profile-image');
      if (user.image) {
          profileImage.src = user.image;
      }

      // Actualizar el nombre y correo en la tarjeta de perfil
      document.getElementById('profile-name').textContent = user.name;
      document.getElementById('profile-email').textContent = user.email;

      // Actualizar el mensaje de bienvenida
      document.getElementById('welcome-name').textContent = user.name;

      // Cargar las reservas y comentarios del usuario
      loadReservations(user.id);
      loadReviews(user.id);
  } else {
      // Si no hay datos de usuario, redirigir al login
      window.location.href = '../login.html';
  }
});

function loadReservations(userId) {
  // Hacer una petición AJAX para obtener las reservas del usuario
  fetch(`get_reservations.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
          const reservationsContainer = document.getElementById('reservations');
          if (data.length > 0) {
              const reservationsList = data.map(reservation => `
                  <div class="card mb-3">
                      <div class="card-body">
                          <h5 class="card-title">${reservation.property_name}</h5>
                          <p class="card-text">Fecha de entrada: ${reservation.start_date}</p>
                          <p class="card-text">Fecha de salida: ${reservation.end_date}</p>
                          <p class="card-text">Estado: ${reservation.status}</p>
                      </div>
                  </div>
              `).join('');
              reservationsContainer.innerHTML = reservationsList;
          } else {
              reservationsContainer.innerHTML = '<p>No tienes reservas actualmente.</p>';
          }
      })
      .catch(error => {
          console.error('Error loading reservations:', error);
          document.getElementById('reservations').innerHTML = '<p>Error al cargar las reservas.</p>';
      });
}

function loadReviews(userId) {
  // Hacer una petición AJAX para obtener los comentarios del usuario
  fetch(`get_reviews.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
          const reviewsContainer = document.getElementById('reviews');
          if (data.length > 0) {
              const reviewsList = data.map(review => `
                  <div class="card mb-3">
                      <div class="card-body">
                          <h5 class="card-title">${review.property_name}</h5>
                          <p class="card-text">Calificación: ${review.rating}/5</p>
                          <p class="card-text">${review.comment}</p>
                          <small class="text-muted">Fecha: ${review.created_at}</small>
                      </div>
                  </div>
              `).join('');
              reviewsContainer.innerHTML = reviewsList;
          } else {
              reviewsContainer.innerHTML = '<p>No has hecho ningún comentario aún.</p>';
          }
      })
      .catch(error => {
          console.error('Error loading reviews:', error);
          document.getElementById('reviews').innerHTML = '<p>Error al cargar los comentarios.</p>';
      });
}