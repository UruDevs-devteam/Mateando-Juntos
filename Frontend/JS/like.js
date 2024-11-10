async function fetchData(url, options = {}) {
    try {
        // Obtener el token JWT del almacenamiento local
        const token = localStorage.getItem('jwtToken');

        // Asegurarse de que si hay un token, lo incluimos en los encabezados
        const headers = {
            'Content-Type': 'application/json',
            'Authorization': token ? `Bearer ${token}` : ''
        };

        // Si se pasan opciones, las fusionamos con los encabezados
        const requestOptions = {
            ...options,
            headers: {
                ...headers,
                ...options.headers
            }
        };

        const response = await fetch(url, requestOptions);

        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error(`Error: ${response.status} - ${response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error en la solicitud de red:', error);
        throw error;
    }
}

// Función para manejar el envío del like
async function sendLike(postID, userID) {
    try {
        const likeIcon = document.getElementById(`likeButton-${postID}`);
        let method = 'POST';  // Cambiado a let para permitir reasignación
        if (likeIcon.className === "fa fa-heart") {
            method = 'DELETE';
        }

        const likedata = {
            ID_post: postID,
            ID_usuario: userID
        };

        const response = await fetchData('http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/like', {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(likedata)
        });

        if (response.success) {
            console.log('Like enviado con éxito');
            await updateLikes(postID);  // Actualiza el contador de likes
        } else {
            console.error('Error al enviar el like:', response.error);
        }
    } catch (error) {
        console.error('Error al enviar el like:', error);
    }
}

// Función para actualizar el contador de likes
async function updateLikes(postID) {
    try {
        const likeCount = await fetchData(`http://localhost:8080/Backend/APIs/API_PO_EV/API_Post_Events.php/Like/${postID}`);
        
        const counter = document.querySelector(`button[data-post-id="${postID}"] + #counter`);
        if (counter) {
            counter.textContent = likeCount || 0; // Actualiza el contador de likes
        // cambiar el like
        const likeIcon = document.getElementById(`likeButton-${postID}`);
        if(likeIcon.className == 'fa fa-heart'){
            likeIcon.className = 'uil uil uil-heart'; 
        }else{
        likeIcon.className = 'fa fa-heart';}
        }
    } catch (error) {
        console.error('Error al actualizar los likes:', error);
    }
}

// Manejo del evento click en el botón de like
document.addEventListener('click', async function(event) {
    if (event.target.closest('.like-button')) {
        const button = event.target.closest('.like-button');
        const postID = button.getAttribute('data-post-id');
        console.log('ID del post desde el botón de like:', postID); // Debugging line

        try {
            const data =   JSON.parse(localStorage.getItem('sessionData'));
            
            if (data.ID_usuario) {
                await sendLike(postID, data.ID_usuario);
            } else {
                console.error('No se pudo obtener el usuario logueado.');
            }
        } catch (error) {
            console.error('Error al obtener el usuario de la sesión:', error);
        }
    }
});



