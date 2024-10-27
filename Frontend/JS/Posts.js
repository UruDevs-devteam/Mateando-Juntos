import { fetchData, getLikeCount, getLikeIconClass, fetchImages,GetSession } from './shared_function.js';


async function updateUserName() { // Actualizar el nombre de usuario en la etiqueta
    try {
        const data = await GetSession();
        if (data.Nombre_usuario) {
            document.getElementById("Nombre_usuario").textContent = data.Nombre_usuario;
            const img = await getProfileImage(data.ID_usuario);
             document.getElementById('Foto_usuario').src = img;
        }
    } catch (error) {
        console.error('Error al obtener el usuario de la sesión:', error);
    }
    
}

//imagen de perfil
async function getProfileImage(userId) {
    try {
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${userId}`);
        if (response && response.Foto_perfil) {
            // La imagen puede estar en formato base64
            return `data:image/jpeg;base64,${response.Foto_perfil}`; 
        }else{
        return '../img/avatar_167770.png'; }// Imagen por defecto si no se encuentra
    } catch (error) {
        console.error('Error al obtener la imagen de perfil:', error);
        return '../img/avatar_167770.png'; // Imagen por defecto en caso de error
    }
}

// Obtener y mostrar los posts
async function fetchPosts() {
    try {
        const posts = await fetchData('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Posts');
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
                               <h3><a href="Perfil_Otro.html?userId=${post.ID_usuario}">${post.Nombre_usuario || 'Nombre no disponible'}</a></h3>
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
                            <button class="button-icon" id="comentario"><i class="uil uil-comment-dots"></i></button>
                            <button class="button-icon"><i class="uil uil-share-alt"></i></button>
                        </div>
                    </div>

                    <div class="caption">
                        <p>${post.Descripcion || 'Descripción no disponible'}</p>
                    </div>

                    <div class="modal-coments">
                        <h3>Comentarios</h3>
                        <ul id="comments-list-${post.ID_post}"></ul> <!-- Cambia el ID para cada post -->
                        <form id="commentForm-${post.ID_post}" action="guardar_comentario.php" method="POST">
                        <input type="hidden" name="ID_post" value="${post.ID_post}"> <!-- ID del post -->
                        <input type="hidden" name="ID_usuario" value="${post.ID_usuario}"> <!-- Debes pasar el ID del usuario, no el nombre -->
                        <textarea name="contenido" required placeholder="Escribe un comentario..."></textarea>
                        <button type="submit">Enviar</button>
                        </form>
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

// Publicar nuevo post
async function publishPost() {
    const descripcion = document.getElementById('postDescription').value;
    if (!descripcion) return alert("Por favor, escribe algo en la descripción.");

    try {
        const userData =await GetSession();
        if (!userData.ID_usuario) return alert("No se pudo obtener el usuario logueado.");

        const postData = {
            Titulo: "s",
            Descripcion: descripcion,
            ID_usuario: userData.ID_usuario
        };

        const postResponse = await fetchData('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Post', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(postData)
        });

        if (postResponse.success.result) {
            alert('Post publicado con éxito!');
            document.getElementById('postDescription').value = '';
            await uploadImage(postResponse.success.postId);  // Subir imagen si existe
            await fetchPosts();  // Recargar los posts después de subir la imagen
        } else {
            alert('Hubo un error al publicar el post.');
        }
    } catch (error) {
        console.error('Error al publicar el post:', error);
    }
}

// Subir imagen asociada al post
async function uploadImage(postId) {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) return;
    const reader = new FileReader();
    return new Promise((resolve, reject) => {
        reader.onloadend = async function () {
            try {
                const base64Image = reader.result.split(',')[1];
                const data = {
                    src: base64Image,
                    postId: postId
                };

                const response = await fetchData('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Multi', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (response.success) {
                    console.log('Imagen subida con éxito');
                    document.getElementById('fileInput').value = '';
                    resolve();
                } else {
                    console.error('Error al subir la imagen:', response.error);
                    reject(new Error('Error al subir la imagen'));
                }
            } catch (error) {
                console.error('Error al subir la imagen:', error);
                reject(error);
            }
        };
        reader.readAsDataURL(file);
    });
}

// Inicializar  y cargar datos al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    updateUserName();
    fetchPosts();
});
//escuchar el boton de publicar 
document.getElementById('publicarBtn').addEventListener('click', event => {
    event.preventDefault();
    publishPost();
});
//escuchar el boton para las imagenes
document.getElementById('Galery').addEventListener('click', event => {
    event.preventDefault();
    document.getElementById('fileInput').click();
});







