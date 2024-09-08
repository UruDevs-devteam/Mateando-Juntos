document.addEventListener('DOMContentLoaded', function() {
    const feedsSection = document.querySelector('.feeds');

    fetch('http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Posts')
        .then(response => response.json())
        .then(posts => {
            // Limpiar contenido existente
            feedsSection.innerHTML = '';

            posts.forEach(post => {
                // Crear un nuevo elemento para cada post
                const article = document.createElement('article');
                article.className = 'feed';

                // Obtener datos del post
                const userName = post.Nombre_usuario || 'Nombre no disponible';
                const postDate = new Date(post.Fecha_creacion);
                const formattedDate = postDate.toLocaleDateString();
                const formattedTime = postDate.toLocaleTimeString();

                // Agregar contenido al artículo
                article.innerHTML = `
                    <div class="head">
                        <div class="user">
                            <div class="profile-photo">
                                <img src="../img/avatar_167770.png" alt="Profile Photo">
                            </div>
                            <div class="info">
                                <h3>${userName}</h3>
                                <small>${formattedDate} ${formattedTime}</small>
                            </div>
                        </div>
                        <span class="edit">
                            <i class="uil uil-ellipsis-h"></i>
                        </span>
                    </div>

                    <div class="photo">
                        <img src="../img/default-image.jpg" alt="Post Image">
                    </div>

                    <div class="caption">
                        <p><b>${post.Titulo || 'Título no disponible'}</b> ${post.Descripcion || 'Descripción no disponible'}</p>
                    </div>
                `;

                // Añadir el artículo a la sección de feeds
                feedsSection.appendChild(article);
            });
        })
        .catch(error => console.error('Error al obtener los posts:', error));
});
