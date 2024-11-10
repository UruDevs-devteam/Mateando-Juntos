import { fetchData, getLikeCount, getLikeIconClass, getProfileImage, GetSession } from './shared_function.js';
document.addEventListener('click', async function (event) {
    if (event.target.closest('.coment')) {
        const button = event.target.closest('.coment');
        const postID = button.getAttribute('data-post-id');

        console.log('ID del post desde el botón de comentario:', postID); // Depuración

        try {
            const userdata = await GetSession(); // Obtiene el ID del usuario de la sesión
            const userId = userdata.ID_usuario;
            openCommentsModal(postID, userId);
        } catch (error) {
            console.error('Error al obtener el usuario de la sesión:', error);
        }
    }
});

// Función para abrir el modal de comentarios
function openCommentsModal(postID, userId) {
    // Referencia al modal y a los campos ocultos
    const modal = document.getElementById("modal-coments");
    const closeBtn = document.querySelector('.close-coments');
    const postInput = document.getElementById("comment-post-id");
    const userInput = document.getElementById("comment-user-id");

    // Asigna el ID del post y del usuario a los campos ocultos
    postInput.value = postID;
    userInput.value = userId;

    // Muestra el modal
    modal.style.display = "flex";
    loadComments(postID);


    // Añade el evento para cerrar el modal al hacer clic en la "X"
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

}

async function loadComments(postID) {
    const commentsSection = document.getElementById('Coments-seccion');
    commentsSection.innerHTML = ''; // Limpiar comentarios existentes

    try {
        // Llamada a la API para obtener los comentarios del post
        const comments = await fetchData(`http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Coments/${postID}`);

        // Generar el HTML para cada comentario
        for (const comment of comments)  {
            const profileImage = await getProfileImage(comment.ID_usuario);
            const commentElement = `
                <div class="comment">
                 <a href="Perfil_Otro.html?userId=${comment.ID_usuario}" target="_blank" class="profile-photo" >
                            <img src="${profileImage}" alt="Foto de perfil de ${comment.Nombre_usuario}" class="profile-photo">
                        </a>
                    <p><strong>${comment.Nombre_usuario}:</strong> ${comment.Contenido}</p>
                    <small>${new Date(comment.Fecha_creacion).toLocaleString()}</small>
                </div>
            `;
            commentsSection.insertAdjacentHTML('beforeend', commentElement);
        };
    } catch (error) {
        console.error('Error al cargar comentarios:', error);
        commentsSection.innerHTML = '<p>Error al cargar los comentarios.</p>';
    }
}

// Función de envío de comentario
async function submitComment(event) {
    event.preventDefault();

    // Obtener los valores del formulario
    const postID = document.getElementById("comment-post-id").value;
    const userID = document.getElementById("comment-user-id").value;
    const content = document.getElementById("commentText").value;

    try {
        // Enviar el comentario a la API mediante una solicitud POST
        const response = await fetchData('http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Coment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ID_post: postID, ID_usuario: userID, Contenido: content })
        });

        ;

        if (response.success) {
            // Obtener la imagen de perfil del usuario
            const profileImage = await getProfileImage(userID);
            const userdata = await GetSession(userID);
            // Añadir el nuevo comentario a la sección sin recargar la página
            const newComment = `
                <div class="comment">
                    <a href="Perfil_Otro.html?userId=${userID}" target="_blank" class="profile-photo">
                        <img src="${profileImage}" alt="Foto de perfil de ${userdata.Nombre_usuario}" class="profile-photo">
                    </a>
                    <p><strong>${userdata.Nombre_usuario}:</strong> ${content}</p>
                    <small>${new Date().toLocaleString()}</small>
                </div>
            `;
            document.getElementById("Coments-seccion").insertAdjacentHTML('beforeend', newComment);
            document.getElementById("commentText").value = ''; // Limpiar el campo de comentario
        } else {
            console.error('Error al guardar el comentario:', response.error);
        }
    } catch (error) {
        console.error('Error al enviar el comentario:', error);
    }
}

// Agregar el evento de envío al formulario
document.getElementById('commentForm').addEventListener('submit', submitComment);