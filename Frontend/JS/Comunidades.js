import { fetchData, fetchImages, getLikeCount, getLikeIconClass, getProfileImage, GetSession } from './shared_function.js';


async function fetchcomunitys() {
    try {
        const comunitys = await fetchData('http://localhost/Mateando-Juntos/Backend/APIs/API_Groups/API_Groups.php/Groups');
        for (const comunity of comunitys) {
            const profilephoto = comunity.Url_fotocomunidad;
            
            const article = `
                <article class="comunity"  data-id="${comunity.ID_comunidad}" >
                    <div class="profile-photo">
                        <img src="data:image/jpeg;base64,${profilephoto}" alt="Foto de la comunidad">
                    </div>
                    <div class="comunity-body">
                        <h5>${comunity.Nombre_comunidad}</h5>
                        <p class="caption">${comunity.Descripcion || 'Descripción no disponible'}</p>
                    </div>
                    <div class="seguir_comunidad">
                            <button><i class="fas fa-user-plus"></i> Unirse</button>
                        </div>
                </article>
            `;
            document.querySelector('.comunity-item').insertAdjacentHTML('beforeend', article);
        }
         
        // Agregar evento de clic para redirigir a `comunidad.html?id=ID_comunidad`
        document.querySelectorAll('.comunity').forEach(article => {
            article.addEventListener('click', function () {
                const communityId = this.getAttribute('data-id');
                if (communityId) {
                    window.location.href = `../HTML/comunidad.html?id=${communityId}`;
                }
            });
        });
    } catch (error) {
        console.error('Error al obtener las comunidades:', error);
    }
}




document.addEventListener('DOMContentLoaded', () => {
    fetchcomunitys();
    
    // Obtener el modal
    const modal = document.getElementById("createCommunityModal");
    const btn = document.getElementById("openModal");
    const span = document.getElementsByClassName("close")[0];

    // Abrir el modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Cerrar el modal cuando el usuario hace clic en <span> (x)
    span.onclick = function() {
        modal.style.display = "none";
    }
 
});

document.getElementById('joinButton').addEventListener('click', event => {
    console.log('yeeee');
});