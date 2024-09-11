// Actualizar el nombre de usuario en la etiqueta <p> con id 'Nombre_usuario'
function updateUserName() {
    return fetch('http://localhost/Mateando-Juntos/Backend/PHP/getUserSession.php')
        .then(response => response.json())
        .then(data => {
            if (data.Nombre_usuario) {
                document.getElementById("Nombre_usuario").textContent = data.Nombre_usuario;
            }
        })
        .catch(error => console.error('Error al obtener el usuario de la sesión:', error));
}

// Obtener y mostrar los posts
function fetchPosts() {
    fetch('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Posts')
        .then(response => response.json())
        .then(posts => {
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
                        <div class="photo" id="post-${post.ID_post}-photo">
                            <img src="../img/default-image.jpg" alt="Post Image">
                        </div>
                        <div class="caption">
                            <p>${post.Descripcion || 'Descripción no disponible'}</p>
                        </div>
                    </article>
                `;

                feedsSection.insertAdjacentHTML('beforeend', article);  // Insertar artículo

                // Cargar imágenes relacionadas con el post
                fetchImages(post.ID_post);
            });
        })
        .catch(error => console.error('Error al obtener los posts:', error));
}

// Obtener y mostrar imágenes de un post específico
function fetchImages(postId) {
    fetch(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Multi/${postId}`)
        .then(response => response.json())
        .then(images => {
            const photoContainer = document.getElementById(`post-${postId}-photo`);

            if (Array.isArray(images) && images.length > 0) {
                // Limpiar la imagen predeterminada
                photoContainer.innerHTML = '';

                images.forEach(image => {
                    const imgElement = document.createElement('img');
                    imgElement.src = `data:image/jpeg;base64,${image}`; // Mostrar imagen en formato Base64
                    photoContainer.appendChild(imgElement);
                });
            }
        })
        .catch(error => console.error('Error al obtener las imágenes:', error));
}

// Publicar nuevo post
function publishPost() {
    const descripcion = document.getElementById('postDescription').value;
    if (!descripcion) return alert("Por favor, escribe algo en la descripción.");

    fetch('http://localhost/Mateando-Juntos/Backend/PHP/getUserSession.php')
        .then(response => response.json())
        .then(data => {
            if (!data.ID_usuario) return alert("No se pudo obtener el usuario logueado.");

            const postData = {
                Titulo: "s",  // Cambia esto según sea necesario
                Descripcion: descripcion,
                ID_usuario: data.ID_usuario
            };

            return fetch('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Post', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(postData)
            });
        })
        .then(response => response.json())
        .then(data => {
            if (data.success.result) {
                alert('Post publicado con éxito!');
                document.getElementById('postDescription').value = '';  // Limpiar el campo de descripción
                fetchPosts();  // Recargar los posts

                // Subir la imagen si hay una seleccionada
                uploadImage(data.success.postId);
            } else {
                alert('Hubo un error al publicar el post.');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Subir imagen asociada al post
function uploadImage(postId) {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) return true;  // No hacer nada si no se selecciona un archivo

    const reader = new FileReader();
    reader.onloadend = function () {
        // Obtener la imagen en Base64
        const base64Image = reader.result.split(',')[1]; // Obtener solo la parte Base64 del resultado

        // Crear el objeto de datos para enviar
        const data = {
            src: base64Image,
            postId: postId
        };

        // Enviar los datos a la API
        fetch('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Multi', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Imagen subida con éxito');
            } else {
                console.error('Error al subir la imagen:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    };

    reader.readAsDataURL(file); // Iniciar la conversión a Base64
}

// Inicializar eventos y cargar datos al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    updateUserName();  // Actualizar el nombre de usuario
    fetchPosts();      // Cargar los posts
});

document.getElementById('publicarBtn').addEventListener('click', event => {
    event.preventDefault();
    publishPost();
});

document.getElementById('Galery').addEventListener('click', event => {
    event.preventDefault();
    document.getElementById('fileInput').click();
});
