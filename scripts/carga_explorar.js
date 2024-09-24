document.addEventListener('DOMContentLoaded', function() {
    // Realiza una solicitud para obtener la información de las propiedades
    fetch('../get_properties.php')
        .then(response => response.json()) // Convierte la respuesta a JSON
        .then(data => {
            const propertyList = document.getElementById('property-list'); // Contenedor para todas las propiedades

            // Itera sobre cada propiedad recibida
            data.forEach(property => {
                // Mapea las imágenes de la propiedad para crear elementos de carrusel
                const images = property.images.map((image, index) => `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                        <img src="data:image/jpeg;base64,${image}" class="d-block w-100" alt="${property.name}">
                    </div>
                `).join('');

                // Crea la estructura HTML para la tarjeta de la propiedad con carrusel
                const propertyCard = `
                    <div class="col">
                        <div class="card custom-card">
                            <div id="carousel${property.id}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    ${images}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel${property.id}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel${property.id}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${property.name}</h5>
                                <p class="card-text">${property.address}, ${property.city}</p>
                                <p class="card-text">$${property.price_per_night} por noche</p>
                                <a href="detalles_propiedad.html?id=${property.id}" class="btn btn-primary">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                `;
                
                // Inserta la tarjeta de propiedad en el contenedor
                propertyList.insertAdjacentHTML('beforeend', propertyCard);
            });
        })
        .catch(error => console.error('Error fetching properties:', error)); // Manejo de errores
});
