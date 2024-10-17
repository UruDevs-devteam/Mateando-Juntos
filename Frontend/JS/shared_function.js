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
            return `data:image/jpeg;base64,${response.Foto_perfil}`;
        } else {
            return '../img/avatar_167770.png'; // Imagen por defecto si no se encuentra
        }
    } catch (error) {
        console.error('Error al obtener la imagen de perfil:', error);
        return '../img/avatar_167770.png'; // Imagen por defecto en caso de error
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