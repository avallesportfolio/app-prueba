/*
// Simulación de la carga del mapa y comentarios
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa (simulación)
    var map = document.getElementById('map');
    map.innerHTML = '<p>Mapa de ubicación (Google Maps se integrará aquí)</p>';

    // Cargar comentarios (simulación)
    var comments = document.getElementById('comments');
    comments.innerHTML = `
        <div class="mb-3">
            <strong>Usuario 1</strong>
            <p>Comentario 1</p>
        </div>
        <div class="mb-3">
            <strong>Usuario 2</strong>
            <p>Comentario 2</p>
        </div>
    `;
});
*/
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el ID de la propiedad desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    const propertyId = urlParams.get('id');

    if (propertyId) {
        // Realiza una solicitud para obtener los detalles de la propiedad
        fetch(`../get_property_details.php?id=${propertyId}`)
            .then(response => response.json()) // Convierte la respuesta a JSON
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Actualiza los elementos de la página con los detalles de la propiedad
                document.getElementById('property-name').textContent = data.name;
                document.getElementById('property-location').textContent = `${data.address}, ${data.city}`;
                document.getElementById('price').textContent = `$${data.price_per_night} por noche`;
                document.getElementById('description').textContent = data.description;

                const hostImage = document.getElementById('host-image');
                hostImage.src = data.host.image ? `data:image/jpeg;base64,${data.host.image}` : '../images/user.png';
                hostImage.alt = data.host.name;

                document.getElementById('host-name').textContent = data.host.name;
                document.getElementById('host-phone').textContent = data.host.phone;
                document.getElementById('host-email').textContent = data.host.email;
                document.getElementById('host-address').textContent = data.host.address;

                // Actualiza las imágenes de la propiedad
                const mainImage = document.getElementById('main-image');
                if (data.images.length > 0) {
                    mainImage.src = `data:image/jpeg;base64,${data.images[0]}`;
                    mainImage.alt = data.name;
                }

                const imageElements = document.querySelectorAll('.secondary-image');
                data.images.slice(1, 4).forEach((image, index) => {
                    if (imageElements[index]) {
                        imageElements[index].src = `data:image/jpeg;base64,${image}`;
                        imageElements[index].alt = `${data.name} - Imagen ${index + 1}`;
                    }
                });

                // Cargar las imágenes en el modal
                const galleryContainer = document.getElementById('gallery');
                galleryContainer.innerHTML = ''; // Limpiar el contenedor de imágenes
                data.images.forEach(image => {
                    const imageElement = document.createElement('div');
                    imageElement.classList.add('col-md-4', 'modal-image-container');
                    imageElement.innerHTML = `<img src="data:image/jpeg;base64,${image}" class="img-fluid rounded" alt="${data.name}">`;
                    galleryContainer.appendChild(imageElement);
                });

                // Cargar las reseñas de la propiedad
                const reviewsContainer = document.getElementById('comments');
                reviewsContainer.innerHTML = ''; // Limpiar el contenedor de comentarios
                data.reviews.forEach(review => {
                    const reviewElement = document.createElement('div');
                    reviewElement.classList.add('review');
                    reviewElement.innerHTML = `
                        <p><strong>${review.reviewer_name}</strong> dice:</p>
                        <p>Rating: ${review.rating}/5</p>
                        <p>${review.comment}</p>
                        <p><small>${new Date(review.created_at).toLocaleDateString()}</small></p>
                    `;
                    reviewsContainer.appendChild(reviewElement);
                });
            })
            .catch(error => console.error('Error fetching property details:', error)); // Manejo de errores
    } else {
        console.error('No property ID found in URL');
    }
});

