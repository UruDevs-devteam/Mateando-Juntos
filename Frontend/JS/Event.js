// Inicializar el mapa y el marcador
let map;    // mapa
let marker; // Marcador

async function fetchData(url, options = {}) {
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        console.error('Error en la solicitud de red:', error);
        throw error; // Lanza error para manejo externo
    }
}

// Inicializar el mapa
function initMap() {
    const latitud = -34.899307040589754;  // Coordenadas de Montevideo
    const longitud = -56.16268158133608;
    map = L.map('map').setView([latitud, longitud], 13); // Inicializar el mapa

    // Añadir capas del mapa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Capturar clic en el mapa
    map.on('click', async function(e) {
        const { lat, lng } = e.latlng; // Obtener coordenadas del clic
        
        // Actualizar inputs ocultos
        document.getElementById('Latitud').value = lat;
        document.getElementById('Longitud').value = lng;

        const lugarNombre = await obtenerNombreLugar(lat, lng); // Obtener nombre del lugar
        document.getElementById('Lugar').value = lugarNombre; // Actualizar input de ubicación

        // Agregar o mover el marcador
        if (marker) {
            marker.setLatLng([lat, lng]); // Mover marcador
        } else {
            marker = L.marker([lat, lng]).addTo(map); // Crear nuevo marcador
        }


    });
}

// Función para obtener el nombre del lugar usando Nominatim
async function obtenerNombreLugar(lat, lng) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
        const data = await response.json();
        return data.display_name || 'Ubicación desconocida'; // Nombre del lugar o mensaje por defecto
    } catch (error) {
        console.error('Error al obtener el nombre del lugar:', error);
        return 'Error al obtener el lugar'; // Mensaje de error en caso de fallo
    }
}

// Configurar y manejar el modal
function setupModalEventHandlers() {
    const modal = document.getElementById("modalEvento");

    document.getElementById("Evento").addEventListener("click", function(event) {
        event.preventDefault();
        modal.style.display = "flex"; // Mostrar modal

        // Redibujar el mapa después de un retraso
        setTimeout(() => map.invalidateSize(), 300);
    });

    // Ocultar el modal al hacer clic en 'x'
    document.querySelector(".close").addEventListener("click", function() {
        modal.style.display = "none";
    });
}


// Obtener imagen de perfil
async function getProfileImage(userId) {
    try {
        const response = await fetchData(`http://localhost:8080/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${userId}`);
        return response?.Foto_perfil ? `../../UsersUploads/${response.Foto_perfil}` : '../img/avatar_167770.png'; // Imagen del perfil o por defecto
    } catch (error) {
        console.error('Error al obtener la imagen de perfil:', error);
        return '../img/avatar_167770.png'; // Imagen por defecto en caso de error
    }
}

// Obtener y mostrar eventos
async function fetchEvents() {
    try {
        const events = await fetchData('http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Events');
        const eventsSection = document.querySelector('.eventos .evento-item');
        eventsSection.innerHTML = ''; // Limpiar contenido existente

        for (const event of events) {
            const eventDate = new Date(event.Fecha_creacion);
            const formattedDate = `${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString()}`; // Formatear fecha
            const profileImage = await getProfileImage(event.ID_usuario); // Obtener imagen de perfil

            // Crear HTML para el evento
            const article = `
                <article class="event">
                    <div class="event-header">
                        <div class="event-profile">
                            <div class="profile-photo">
                                <img src="${profileImage}" alt="Profile Photo"> <!-- Imagen de perfil -->
                            </div>
                            <div class="event-info">
                                <h3>${event.Nombre_usuario}</h3>
                                <small>${formattedDate}</small> <!-- Fecha formateada -->
                            </div>
                        </div>
                    </div>
                    <h1 class="event-title">${event.Titulo || 'Título no disponible'}</h1> <!-- Título del evento -->
                    <div class="event-description">
                        <p>${event.Descripcion || 'Descripción no disponible'}</p> <!-- Descripción del evento -->
                    </div>
                    <div class="event-details">
                        <div class="detail-item"><strong>Lugar:</strong> ${event.Lugar || 'Lugar no disponible'}</div>
                        <div class="detail-item"><strong>Fecha del encuentro:</strong> ${event.Fecha_encuentro || 'Fecha no disponible'}</div>
                        <div class="detail-item"><strong>Hora de inicio:</strong> ${event.Hora_inicio || 'Hora no disponible'}</div>
                        <div class="detail-item"><strong>Hora de fin:</strong> ${event.Hora_fin || 'Hora no disponible'}</div>
                    </div>
                    <div class="event-map" id="map-${event.ID_evento}" style="height: 300px;"></div> <!-- Contenedor para el mapa -->
                </article>
            `;
            eventsSection.insertAdjacentHTML('beforeend', article); // Insertar el artículo en la sección de eventos

            // Inicializar el mapa para este evento
            initMapForEvent(event.Latitud, event.Longitud, `map-${event.ID_evento}`);
        }
    } catch (error) {
        console.error('Error al obtener los eventos:', error); // Manejar errores
    }
}

// Inicializar el mapa para cada evento
function initMapForEvent(latitud, longitud, mapId) {
    const map = L.map(mapId).setView([latitud, longitud], 13); // Inicializar el mapa para el evento

    // Añadir capas del mapa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Crear un marcador en la ubicación del evento
    L.marker([latitud, longitud]).addTo(map).bindPopup('Ubicación del evento').openPopup();
}

// Inicializar mapa y eventos al cargar la página
window.onload = function() {
    initMap();  // Inicializar el mapa
    setupModalEventHandlers();  // Configurar los eventos del modal
    fetchEvents();  // Cargar los eventos
};
