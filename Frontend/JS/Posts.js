// 
async function fetchData(url, options = {}) {
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        console.error('Error en la solicitud de red:', error);
        throw error;
    }
}

// Actualizar el nombre de usuario en la etiqueta <p> con id 'Nombre_usuario'
async function updateUserName() {
    try {
        const data = await fetchData('http://localhost/Mateando-Juntos/Backend/PHP/getUserSession.php');
        if (data.Nombre_usuario) {
            document.getElementById("Nombre_usuario").textContent = data.Nombre_usuario;
            const img = await getProfileImage(data.ID_usuario);
             document.getElementById('Foto_usuario').src = img;
        }
    } catch (error) {
        console.error('Error al obtener el usuario de la sesión:', error);
    }
    
}
//traer cantidad likes
async function getLikeCount(postId) {
    try {
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Like/${postId}`);
         console.log('Respuesta obtenida de la API para el post likes', postId, ':', response);
        return parseInt(response, 10) || 0; // Devuelve 0 si likeCount no es un número válido
    } catch (error) {
        console.error('Error al obtener el contador de likes:', error);
        return 0;
    }
}

//traer si likeo o no
async function getLikeIconClass(postId) {
    try {
        const data = await fetchData('http://localhost/Mateando-Juntos/Backend/PHP/getUserSession.php');
        if (data.ID_usuario) {
            userId = data.ID_usuario;
        }
    } catch (error) {
        console.error('Error al obtener el usuario de la sesión:', error);
    }
    try {
        // Llama a la API para verificar si el usuario ha dado like al post
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/CheckLike/${userId}/${postId}`);
        // Verifica si el usuario ha dado like y devuelve la clase
        if (response.hasLiked) {
            return 'fa fa-heart';
        } else {
            return 'uil uil-heart';
        }
    } catch (error) {
        console.error('Error al obtener el estado de like:', error);
        return 'uil-heart'; // Clase por defecto en caso de error
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
                                <h3>${post.Nombre_usuario || 'Nombre no disponible'}</h3>
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
                        <p> ${post.Descripcion || 'Descripción no disponible'}</p>
                    </div>
                </article>
            `;
            console.log('Profile Image URL:', ProfileImage);
            feedsSection.insertAdjacentHTML('beforeend', article);

            // Cargar imágenes relacionadas con el post
            await fetchImages(post.ID_post);
        }
    } catch (error) {
        console.error('Error al obtener los posts:', error);
    }
}

// Obtener y mostrar imágenes de un post específico
async function fetchImages(postId) {
    try {
        const images = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Multi/${postId}`);
        const photoContainer = document.getElementById(`post-${postId}-photo`);

        if (Array.isArray(images) && images.length > 0) {
            images.forEach(image => {
                const imgElement = document.createElement('img');
                imgElement.src = `data:image/jpeg;base64,${image}`;
                photoContainer.appendChild(imgElement);
            });
        } else {
            photoContainer.style.display = 'none';
        }
    } catch (error) {
        console.error('Error al obtener las imágenes:', error);
    }
}

// Publicar nuevo post
async function publishPost() {
    const descripcion = document.getElementById('postDescription').value;
    if (!descripcion) return alert("Por favor, escribe algo en la descripción.");

    try {
        const userData = await fetchData('http://localhost/Mateando-Juntos/Backend/PHP/getUserSession.php');
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

// Inicializar eventos y cargar datos al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    updateUserName();
    fetchPosts();
});

document.getElementById('publicarBtn').addEventListener('click', event => {
    event.preventDefault();
    publishPost();
});

document.getElementById('Galery').addEventListener('click', event => {
    event.preventDefault();
    document.getElementById('fileInput').click();
});







