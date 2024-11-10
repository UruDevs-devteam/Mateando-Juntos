// Importar funciones compartidas
import { fetchData, fetchImages, getLikeCount, getLikeIconClass, getProfileImage, GetSession,SeguidosSeguidores } from './shared_function.js';

// Función para obtener el parámetro "userId" de la URL
function getUserIdFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('userId');  // Obtiene el valor de 'userId' de la URL
}
async function verifyUser(userId) {
    const sessionData = await GetSession();
    if (userId == sessionData.ID_usuario) {
        window.location.href = '../HTML/Perfil.html';
    }else{
    
    }
}
// Función para inicializar el perfil de otro usuario
async function initProfileOtro() {
    const userId = getUserIdFromURL();
    await verifyUser(userId);
    if (userId) {
        // Obtener la imagen de perfil y otros datos
        const profilePhoto = await getProfileImage(userId);
        const userProfile = await fetchData(`http://localhost:8080/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${userId}`);

        document.getElementById('FotoPerfil').src = profilePhoto;
        document.getElementById('Nombre-Usuario').innerText = userProfile.Nombre_usuario || 'Nombre no disponible';
        document.getElementById('Biografia').innerText = userProfile.Biografia || 'No hay biografía disponible';
        SeguidosSeguidores(userId);
        // Cargar los posts del usuario
        await fetchPostsPerfil(userId, userProfile.Nombre_usuario);
    } else {
        console.error('No se encontró un userId en la URL');
    }
}

async function addSeguidor(User_ID_Seguido, User_ID_Seguidor) {
    const data = {
        User_ID_Seguido: User_ID_Seguido,
        User_ID_Seguidor: User_ID_Seguidor
    };
        console.log(data);
    try {
        const response = await fetchData('http://localhost:8080/Backend/APIs/API_Users/API_Usuarios.php/Seguidor', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (response.success) {
            alert('Ahora sigues a este usuario.');
            document.getElementById('seguir-button').innerText = 'Dejar de seguir';
            const seguidoresElement = document.getElementById('seguidores-count');
            const seguidoresCount = parseInt(seguidoresElement.innerText, 10);
            seguidoresElement.innerText = seguidoresCount + 1; // Incrementar el contador
        } else {
            console.log(response);
            console.error('Error al seguir al usuario:', response.error); // Asegúrate de que 'error' exista en la respuesta
        }
    } catch (error) {
        console.error('Error al realizar la solicitud:', error);
    }

}
async function deleteSeguidor(User_ID_Seguido, User_ID_Seguidor) {
    const data = {
        User_ID_Seguido: User_ID_Seguido,
        User_ID_Seguidor: User_ID_Seguidor
    };

    try {
        const response = await fetchData('http://localhost:8080/Backend/APIs/API_Users/API_Usuarios.php/Seguidor', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (response.success) {
            alert('Has dejado de seguir a este usuario.');
            document.getElementById('seguir-button').innerText = 'Seguir'; // Cambiar el texto del botón
            const seguidoresElement = document.getElementById('seguidores-count');
            const seguidoresCount = parseInt(seguidoresElement.innerText, 10);
            seguidoresElement.innerText = seguidoresCount - 1; // Decrementar el contador
        } else {
            console.error('Error al dejar de seguir al usuario:', response.error);
        }
    } catch (error) {
        console.error('Error al realizar la solicitud:', error);
    }
}
// Función para obtener y mostrar los posts de otro usuario
async function fetchPostsPerfil(userId, Nombreuser) {
    try {
        const posts = await fetchData(`http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Post/${userId}`);
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
                            <button class="button-icon coment" data-post-id="${post.ID_post}">
                                <i class="uil uil-comment-dots" id="coment-${post.ID_post}"></i>
                             </button>
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
document.getElementById('seguir-button').addEventListener('click', async () => {
    const sessionData = await GetSession();
    const userIdSeguidor = sessionData.ID_usuario; 
    const userIdSeguido = getUserIdFromURL(); // Este debería ser el seguidor
    const seguirButton = document.getElementById('seguir-button');
    if (seguirButton.innerText === 'Seguir') {
        await addSeguidor(userIdSeguido, userIdSeguidor); // Cambiado el orden de los parámetros
    } else {
        await deleteSeguidor(userIdSeguido, userIdSeguidor);
    }

});
document.getElementById('mensaje-button').addEventListener('click', () => {
    const profileUserId = getUserIdFromURL();
    
    // Redirigir a chats.html y pasar el contactId como parámetro
    window.location.href = `chats.html?contactId=${profileUserId}`;
});
// Llamar a la función cuando se cargue la página
document.addEventListener('DOMContentLoaded', initProfileOtro);
