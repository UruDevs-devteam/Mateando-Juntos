// Función para realizar una solicitud de red genérica
async function fetchData(url, options = {}) {
    try {
        const response = await fetch(url, options);
        const data = await response.json();
        return data;
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
        }
    } catch (error) {
        console.error('Error al obtener el usuario de la sesión:', error);
    }
}

// Obtener y mostrar los posts
async function fetchPosts() {
    try {
        const posts = await fetchData('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Posts');
        const feedsSection = document.querySelector('.feeds');
        feedsSection.innerHTML = '';  // Limpiar contenido existente

        posts.forEach(post => {
            const postDate = new Date(post.Fecha_creacion);
            const formattedDate = `${postDate.toLocaleDateString()} ${postDate.toLocaleTimeString()}`;

            const article = `
                <article class="feed">
                    <div class="head">
                        <div class="user">
                            <div class="profile-photo">
                                <img src="../img/avatar_167770.png" alt="Profile Photo">
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
                            <span><i class="uil uil-heart"></i></span>
                            <span><i class="uil uil-comment-dots"></i></span>
                            <span><i class="uil uil-share-alt"></i></span>
                        </div>
                    </div>

                    <div class="caption">
                        <p><b>${post.Nombre_usuario || 'Nombre no disponible'}</b> ${post.Descripcion || 'Descripción no disponible'}</p>
                    </div>
                </article>
            `;
            feedsSection.insertAdjacentHTML('beforeend', article);

            // Cargar imágenes relacionadas con el post
            fetchImages(post.ID_post);
        });
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

            // Subir la imagen si hay una seleccionada y luego recargar los posts
            await uploadImage(postResponse.success.postId);
            fetchPosts();  // Recargar los posts inmediatamente después
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
    reader.onloadend = async function () {
        const base64Image = reader.result.split(',')[1];

        const data = {
            src: base64Image,
            postId: postId
        };

        try {
            const response = await fetchData('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Multi', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (response.success) {
                console.log('Imagen subida con éxito');
            } else {
                console.error('Error al subir la imagen:', response.error);
            }
        } catch (error) {
            console.error('Error al subir la imagen:', error);
        }
    };

    reader.readAsDataURL(file);
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