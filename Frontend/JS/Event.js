

// Mostrar el modal
document.getElementById("Evento").addEventListener("click", function(event) {
    event.preventDefault();
    document.getElementById("modalEvento").style.display = "flex"; // Cambia a "flex" para centrar
});

// Ocultar el modal cuando se haga clic en la 'x'
document.querySelector(".close").addEventListener("click", function() {
    document.getElementById("modalEvento").style.display = "none";
});

// Ocultar el modal cuando se haga clic fuera de él
window.onclick = function(event) {
    if (event.target == document.getElementById("modalEvento")) {
        document.getElementById("modalEvento").style.display = "none";
    }
};



async function fetchData(url, options = {}) {
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        console.error('Error en la solicitud de red:', error);
        throw error;
    }
}





// Obtener y mostrar eventos
async function fetchEvents() {
    try {
        const events = await fetchData('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Events');
        const eventsSection = document.querySelector('.eventos .evento-item');
        eventsSection.innerHTML = '';  // Limpiar contenido existente
    
        for (const event of events) {
            const eventDate = new Date(event.Fecha_creacion);
            const formattedDate = `${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString()}`;
            const ProfileImage = await getProfileImage(event.ID_usuario);
            const article = `
                <article class="event">
                    <div class="event-header">
                        <div class="event-profile">
                            <div class="profile-photo">
                                <img src="${ProfileImage}" alt="Profile Photo">
                            </div>
                            <div class="event-info">
                                <h3>${event.Nombre_usuario}</h3>
                                <small>${formattedDate}</small>
                            </div>
                        </div>
                    </div>
    
                    <div class="event-description">
                        <p>${event.Descripcion || 'Descripción no disponible'}</p>
                    </div>
    
                    <div class="event-details">
                        <div class="detail-item">
                            <strong>Lugar:</strong> ${event.Lugar || 'Lugar no disponible'}
                        </div>
                        <div class="detail-item">
                            <strong>Fecha del encuentro:</strong> ${event.Fecha_encuentro || 'Fecha no disponible'}
                        </div>
                        <div class="detail-item">
                            <strong>Hora de inicio:</strong> ${event.Hora_inicio || 'Hora no disponible'}
                        </div>
                        <div class="detail-item">
                            <strong>Hora de fin:</strong> ${event.Hora_fin || 'Hora no disponible'}
                        </div>
                    </div>
                </article>
            `;
            eventsSection.insertAdjacentHTML('beforeend', article);
        }
    } catch (error) {
        console.error('Error al obtener los eventos:', error);
    }
}



// Obtener imagen de perfil
async function getProfileImage(userId) {
    try {
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${userId}`);
        return response?.Foto_perfil ? `data:image/jpeg;base64,${response.Foto_perfil}` : '../img/avatar_167770.png';
    } catch (error) {
        console.error('Error al obtener la imagen de perfil:', error);
        return '../img/avatar_167770.png';
    }
}

// Inicializar eventos y cargar datos al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    fetchEvents();
});