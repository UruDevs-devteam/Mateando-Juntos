// Importar funciones compartidas
import { fetchData, fetchImages, getLikeCount, getLikeIconClass, getProfileImage } from './shared_function.js';

// Función para obtener el parámetro "userId" de la URL
function getUserIdFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('userId');  // Obtiene el valor de 'userId' de la URL
}

// Función para inicializar el perfil de otro usuario
async function initProfileOtro() {
    const userId = getUserIdFromURL();
    if (userId) {
        // Obtener la imagen de perfil y otros datos
        const profilePhoto = await getProfileImage(userId);
        const userProfile = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${userId}`);

        document.getElementById('FotoPerfil').src = profilePhoto;
        document.getElementById('Nombre-Usuario').innerText = userProfile.Nombre_usuario || 'Nombre no disponible';
        document.getElementById('Biografia').innerText = userProfile.Biografia || 'No hay biografía disponible';

        // Cargar los posts del usuario
        await fetchPostsPerfil(userId, userProfile.Nombre_usuario);
    } else {
        console.error('No se encontró un userId en la URL');
    }
}

// Función para obtener y mostrar los posts de otro usuario
async function fetchPostsPerfil(userId, Nombreuser) {
    try {
        const posts = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Post/${userId}`);
        const feedsSection = document.querySelector('.feeds');
        feedsSection.innerHTML = '';  // Limpiar contenido existente

        for (const post of posts) {
            const postDate = new Date(post.Fecha_creacion);
            const formattedDate = `${postDate.toLocaleDateString()} ${postDate.toLocaleTimeString()}`;
            const likeCount = await getLikeCount(Number(post.ID_post));
            const likeclass = await getLikeIconClass(Number(post.ID_post));
            const ProfileImage = await getProfileImage(post.ID_usuario);

            const article = `
                <article class="feed">
                    <div class="head">
                        <div class="user">
                            <div class="profile-photo">
                                <img src="${ProfileImage}" alt="Profile Photo">
                            </div>
                            <div class="info">
                                <h3>${Nombreuser || 'Nombre no disponible'}</h3>
                                <small>${formattedDate}</small>
                            </div>
                        </div>
                        <span class="edit"><i class="uil uil-ellipsis-h"></i></span>
                    </div>

                    <div class="photo" id="post-${post.ID_post}-photo"></div>

                    <div class="action-buttons">
                        <div class="interaction-buttons">
                            <button class="button-icon like-button" data-post-id="${post.ID_post}">
                                <i class="${likeclass}" id="likeButton-${post.ID_post}"></i>
                            </button>
                            <text id="counter">${likeCount}</text>
                            <button class="button-icon"><i class="uil uil-comment-dots"></i></button>
                            <button class="button-icon"><i class="uil uil-share-alt"></i></button>
                        </div>
                    </div>

                    <div class="caption">
                        <p>${post.Descripcion || 'Descripción no disponible'}</p>
                    </div>
                </article>
            `;
            feedsSection.insertAdjacentHTML('beforeend', article);

            // Cargar imágenes relacionadas con el post
            await fetchImages(post.ID_post);
        }
    } catch (error) {
        console.error('Error al obtener los posts:', error);
    }
}

// Llamar a la función cuando se cargue la página
document.addEventListener('DOMContentLoaded', initProfileOtro);
