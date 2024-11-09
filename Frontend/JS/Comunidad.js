import { fetchData, fetchImages, getLikeCount, getLikeIconClass, getProfileImage, GetSession,loadChatList } from './shared_function.js';

async function getComuIdFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');  // Obtiene el valor de 'userId' de la URL
}
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

async function updateComunityinfo(comunityId) {
    const comunityName = document.getElementById('community-name');
    const description = document.getElementById('community-description');
    const membersElement = document.getElementById('community-members');
    const photo_comunity = document.getElementById('photo_comunity');

    try {
        const response = await fetchData(`http://localhost:8080/Backend/APIs/API_Groups/API_Groups.php/Group/${comunityId}`);
        const membersCount = await fetchData(`http://localhost:8080/Backend/APIs/API_Groups/API_Groups.php/Users/${comunityId}`);
        
        if (response) {
            comunityName.textContent = response.Nombre_comunidad || "Nombre no disponible";
            description.textContent = response.Descripcion || "Descripción no disponible";
            membersElement.textContent = `Miembros de la comunidad: ${membersCount || 0} `;
            photo_comunity.src = response.Url_fotocomunidad ? `../../UsersUploads/${response.Url_fotocomunidad}` : "ruta/a/imagen/por/defecto.jpg";
        } else {
            console.warn('Datos de la comunidad no encontrados');
        }
        
    } catch (error) {
        console.error('Error al obtener información de la comunidad:', error);
    }
}

async function fetchPosts(comunityId) {
    try {
        const posts = await fetchData(`http://localhost:8080/Backend/APIs/API_Groups/API_Groups.php/Posts/${comunityId}`);
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

        const postResponse = await fetchData('http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Post', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(postData)
        });

        if (postResponse.success.result) {
            alert('Post publicado con éxito!');
            document.getElementById('postDescription').value = '';
            await uploadImage(postResponse.success.postId);  // Subir imagen si existe
            await uploadPostCom(postResponse.success.postId); //sube el post a la comunidad
            const comunityId = await getComuIdFromURL();
            await fetchPosts(comunityId); 
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

                const response = await fetchData('http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Multi', {
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
async function uploadPostCom(ID_post) {
    const ID_comunidad = await getComuIdFromURL();
    console.log('ID_comunidad:', ID_comunidad); // Verificar el ID de la comunidad
    console.log('ID_post:', ID_post);     
    try {
        const data = {
            ID_comunidad: ID_comunidad,
            ID_post: ID_post
        };
        const response = await fetchData(`http://localhost:8080/Backend/APIs/API_Groups/API_Groups.php/AddPostToGroup`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                console.log('Response from API:', response);
                
        
    } catch (error) {
        console.error('Error al subir el post a la comunidad:', error);
    }
}



// Inicializar  y cargar datos al cargar la página
 document.addEventListener('DOMContentLoaded', async () => {
    const ID_comunidad = await getComuIdFromURL();
    document.getElementById('Community-Id').value = ID_comunidad;
    updateComunityinfo(ID_comunidad);
    updateUserName();
    fetchPosts(ID_comunidad);
    loadChatList();
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

document.getElementById('joinButton').addEventListener('click', async event => {
    event.preventDefault();
    const communityId = await getComuIdFromURL();
    const userdata = await GetSession(); // Reemplaza con tu método para obtener el ID del usuario
    const userId = userdata.ID_usuario
    joinCommunity(userId, communityId);
});

// Función para enviar la solicitud de unirse a la comunidad
async function joinCommunity(userId, communityId) {
    const data = {
        ID_usuario: userId,
        ID_comunidad: communityId
    };
    console.log(data);
    try {
        const response = await fetch('http://localhost/Mateando-Juntos/Backend/APIs/API_Groups/API_Groups.php/UserG', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            console.log("Unido a la comunidad con éxito");
        } else {
            console.warn("No se pudo unir a la comunidad");
        }
    } catch (error) {
        console.error('Error al unirse a la comunidad:', error);
    }
}

