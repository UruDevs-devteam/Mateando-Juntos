// Importar funciones compartidas
import { fetchData, getLikeCount, getLikeIconClass, fetchImages, GetSession, SeguidosSeguidores } from './shared_function.js';
document.addEventListener('DOMContentLoaded', initProfile);
// --------------------------------Selecciona el input de archivo y la imagen de vista previa en config usuario--------------------------------
const profileInput = document.getElementById('profile_picture');
const profilePreview = document.getElementById('profile-picture-img');
// Escucha cuando el usuario selecciona una imagen
profileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];  // Toma el primer archivo seleccionado
    if (file) {
        const reader = new FileReader();  // Crea un FileReader para leer el archivo

        reader.onload = function(e) {
            profilePreview.src = e.target.result;  // Establece la fuente de la imagen como la lectura del archivo
        }

        reader.readAsDataURL(file);  // Lee el archivo como una URL de datos
    }
});

//-------------------------------Perfil de usuario----------------------------------------------------------------------



async function GetPerfilPhoto(UserID) {
    try {
        const response = await fetchData(`http://localhost:8080/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${UserID}`);
        return {
            profilePhoto: response?.Foto_perfil ? `../../UsersUploads/${response.Foto_perfil}` : '../img/avatar_167770.png',
            biografia: response?.Biografia || '',
            privado: response?.Privado || false
        };
    } catch (error) {
        console.error('Error al obtener la información de perfil:', error);
        return {
            profilePhoto: '../img/avatar_167770.png',
            biografia: '',
            privado: false
        };
    }
}

async function fetchPostsPerfil(UsuarioID, Nombreuser) {
    try {
        const posts = await fetchData(`http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Post/${UsuarioID}`);
        const feedsSection = document.querySelector('.feeds');
        feedsSection.innerHTML = '';  // Limpiar contenido existente

        for (const post of posts) {
            const postDate = new Date(post.Fecha_creacion);
            const formattedDate = `${postDate.toLocaleDateString()} ${postDate.toLocaleTimeString()}`;
            const likeCount = await getLikeCount(Number(post.ID_post));
            const likeclass = await getLikeIconClass(Number(post.ID_post));
            const ProfileImage = await GetPerfilPhoto(post.ID_usuario);

            const article = `
                <article class="feed">
                    <div class="head">
                        <div class="user">
                            <div class="profile-photo">
                                <img src="${ProfileImage.profilePhoto}" alt="Profile Photo">
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
                        <p> ${post.Descripcion || 'Descripción no disponible'}</p>
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

async function initProfile() {
    const sessionData = await GetSession();
    if (sessionData && sessionData.ID_usuario) {
        const userId = sessionData.ID_usuario;
        const profilePhoto = await GetPerfilPhoto(userId);
        document.getElementById('FotoPerfil').src = profilePhoto.profilePhoto;
        document.getElementById('Nombre-Usuario').innerText = sessionData.Nombre_usuario;
        document.getElementById('Biografia').innerHTML = profilePhoto.biografia;
        fetchPostsPerfil(userId, sessionData.Nombre_usuario);
        SeguidosSeguidores(sessionData.ID_usuario);
    }
}
