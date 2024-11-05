// ---------------------------------------- FUNCIONES COMPARTIDAS ----------------------------------------
//obtiene la session 
export async function GetSession(){
    try {
        const data = await fetchData('http://localhost/Mateando-Juntos/Backend/PHP/getUserSession.php');
        return data;
    } catch (error) {
        console.error('Error al obtener el usuario de la sesión:', error);
        return;
    }
}

// Función para hacer llamados a la API
export async function fetchData(url, options = {}) {
    try {
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        console.error('Error en la solicitud de red:', error);
        throw error;
    }
}
//Imagenes para los posts
export async function fetchImages(postId) {
    try {
        const images = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Multi/${postId}`);
        const photoContainer = document.getElementById(`post-${postId}-photo`);

        if (Array.isArray(images) && images.length > 0) {
            images.forEach(image => {
                const imgElement = document.createElement('img');
                imgElement.src = `../../UsersUploads/${image}`;
                photoContainer.appendChild(imgElement);
            });
        } else {
            photoContainer.style.display = 'none';
        }
    } catch (error) {
        console.error('Error al obtener las imágenes:', error);
    }
}
// Obtener la cantidad de likes de un post
export async function getLikeCount(postId) {
    try {
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/Like/${postId}`);
        console.log('Respuesta obtenida de la API para el post likes', postId, ':', response);
        return parseInt(response, 10) || 0; // Devuelve 0 si no hay likes o no es un número
    } catch (error) {
        console.error('Error al obtener el contador de likes:', error);
        return 0;
    }
}

// Obtener el ícono de like según si el usuario ha dado like o no
export async function getLikeIconClass(postId) {
    let userId;
    try {
        const data = await fetchData('http://localhost/Mateando-Juntos/Backend/PHP/getUserSession.php');
        if (data.ID_usuario) {
            userId = data.ID_usuario;
        }
    } catch (error) {
        console.error('Error al obtener el usuario de la sesión:', error);
        return 'uil-heart'; // Clase por defecto en caso de error
    }

    try {
        // Llama a la API para verificar si el usuario ha dado like al post
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php/CheckLike/${userId}/${postId}`);
        return response.hasLiked ? 'fa fa-heart' : 'uil uil-heart';
    } catch (error) {
        console.error('Error al obtener el estado de like:', error);
        return 'uil-heart'; // Clase por defecto en caso de error
    }
}

// Obtener la imagen de perfil del usuario
export async function getProfileImage(userId) {
    try {
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${userId}`);
        if (response && response.Foto_perfil) {
            return `../../UsersUploads/${response.Foto_perfil}`;
        } else {
            return '../img/avatar_167770.png'; // Imagen por defecto si no se encuentra
        }
    } catch (error) {
        console.error('Error al obtener la imagen de perfil:', error);
        return '../img/avatar_167770.png'; // Imagen por defecto en caso de error
    }
}
// Obtener el nombre de perfil del usuario
export async function getProfilename(userId) {
    try {
        const response = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Users/API_Usuarios.php/Perfil/${userId}`);
        if (response && response.Nombre_usuario) {
            return response.Nombre_usuario;
        } else {
            return 'user name'; // Imagen por defecto si no se encuentra
        }
    } catch (error) {
        console.error('Error al obtener el nombre de perfil:', error);
        return 'user name'; // Imagen por defecto en caso de error
    }
}

//actualizar seguidos y seguidores de perfiles
export async function SeguidosSeguidores(userId) {
    try {
        // Obtener los usuarios que este usuario sigue (seguidos) y los que lo siguen (seguidores)
        const seguidos = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Users/API_Usuarios.php/Seguidos/${userId}`);
        const seguidores = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Users/API_Usuarios.php/Seguidores/${userId}`);

        // Actualizar los contadores en el frontend
        document.getElementById('seguidos-count').innerText = seguidos.length;
        document.getElementById('seguidores-count').innerText = seguidores.length;

        // Obtener los datos de la sesión para verificar si ya sigue a este usuario
        const sessionData = await GetSession();
        const currentUserId = sessionData.ID_usuario;
        // Verificar si el usuario actual ya sigue al perfil que está viendo
        const yaSiguiendo = seguidores.some(seg => seg.Perfil_usuario_Seguidor === currentUserId);
        const seguirButton = document.getElementById('seguir-button');
        if (yaSiguiendo) {
            seguirButton.innerText = 'Dejar de seguir';
        }

    } catch (error) {
        console.error('Error al obtener seguidos o seguidores:', error);
    }
}
// pone lindo el tiempo 
export function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const isSameDay = date.toDateString() === now.toDateString();
    if (!isSameDay) {
        return `${date.toLocaleDateString()} ${date.getHours()}:${String(date.getMinutes()).padStart(2, '0')}`;
    }
    return `${date.getHours()}:${String(date.getMinutes()).padStart(2, '0')}`;
}

//carga los chats
export async function loadChatList() {
    const sessionData = await GetSession();
    const userId = sessionData.ID_usuario;

    try {
        const contacts = await fetchData(`http://localhost/Mateando-Juntos/Backend/APIs/API_Chats/API_Chats.php/Chats/${userId}`);
        const chatListContainer = document.getElementById('chat-list');
        chatListContainer.innerHTML = ''; // Limpiar lista de chats

        for (const contact of contacts) {
            const UserName = contact.Nombre_usuario;
            const profilePhoto = await getProfileImage(contact.ID_usuario);
            const ultimoM = contact.UltimoMS;
            const date = contact.Fecha;

            const chatItem = document.createElement('div');
            chatItem.className = 'chat-item';
            chatItem.setAttribute('data-contact-id', contact.ID_usuario);
            chatItem.innerHTML = `
                 <article class="message">
                    <div class="profile-photo">
                        <img src="${profilePhoto}" alt="${UserName}'s profile" class="Foto_usuario">
                    </div>
                    <div class="message-body">
                         <h5>${UserName}</h5>
                         <p class="text-muted">${ultimoM || 'No hay mensajes aún'}</p>
                        <p class="chat-timestamp">${formatTimestamp(new Date(date))}</p>
                    </div>
                  </article>
     
            `;
            // Evento click para abrir el chat con el contacto seleccionado
            chatItem.addEventListener('click', () => {
                const contactId = contact.ID_usuario;
                window.location.href = `chats.html?contactId=${contactId}`; // Redirige a la página de chats con el ID en la URL
            })

            chatListContainer.appendChild(chatItem); // Agregar el chat a la lista
        }
    } catch (error) {
        console.error('Error al cargar la lista de chats:', error);
    }
}