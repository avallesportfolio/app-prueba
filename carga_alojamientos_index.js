document.addEventListener('DOMContentLoaded', function() {
    // Realiza una solicitud para obtener la información de las propiedades
    fetch('./get_properties.php')
        .then(response => response.json()) // Convierte la respuesta a JSON
        .then(data => {
            const propertyList = document.getElementById('property-list'); // Contenedor para las propiedades mejor valoradas
            const allPropertiesList = document.getElementById('all-properties-list'); // Contenedor para todas las propiedades

            // Itera sobre cada propiedad recibida
            data.forEach(property => {
                // Mapea las imágenes de la propiedad para crear elementos de carrusel
                const images = property.images.map((image, index) => `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                        <img src="data:image/jpeg;base64,${image}" class="d-block w-100" alt="${property.name}">
                    </div>
                `).join('');

                // Función para crear la tarjeta de propiedad
                const createPropertyCard = (property, idSuffix = '') => `
                    <div class="col">
                        <a href="./pages/detalles_propiedad.html?id=${property.id}" class="text-decoration-none text-dark">
                            <div class="card custom-card">
                                <div id="carousel${property.id}${idSuffix}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        ${images}
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel${property.id}${idSuffix}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel${property.id}${idSuffix}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">${property.name}</h5>
                                    <p class="card-text">${property.address}, ${property.city}</p>
                                    <p class="card-text">$${property.price_per_night} por noche</p>
                                </div>
                            </div>
                        </a>
                    </div>
                `;

                // Inserta la tarjeta de propiedad en el contenedor de propiedades mejor valoradas
                propertyList.insertAdjacentHTML('beforeend', createPropertyCard(property));

                // Inserta la tarjeta de propiedad en el contenedor de todas las propiedades con un sufijo único
                allPropertiesList.insertAdjacentHTML('beforeend', createPropertyCard(property, '-all'));
            });
        })
        .catch(error => console.error('Error fetching properties:', error)); // Manejo de errores
});

