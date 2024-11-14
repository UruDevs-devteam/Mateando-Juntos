import { GetSession, fetchData, getProfileImage, getProfilename,formatTimestamp } from './shared_function.js'; 

// Función para cargar lista de chats
async function loadChatList() {
    const sessionData = await GetSession();
    const userId = sessionData.ID_usuario;
    try {
        const contacts = await fetchData(`http://localhost:8080/Backend/APIs/API_Chats/API_Chats.php/Chats/${userId}`);
        const chatListContainer = document.getElementById('chat-list');
        chatListContainer.innerHTML = ''; // Limpiar lista de chats

        for (const contact of contacts) {
            const UserName = contact.Nombre_usuario;
            const profilePhoto = await getProfileImage(contact.ID_usuario);
            const ultimoM = contact.UltimoMS;
            const date = contact.Fecha;

            const chatItem = document.createElement('div');
            chatItem.className = 'chat-item';
            chatItem.setAttribute('data-contact-id',contact.ID_usuario);
            chatItem.innerHTML = `
                <img src="${profilePhoto}" alt="${UserName}'s profile" class="profile-photo">
                <div class="chat-details">
                    <p>${UserName}</p>
                    <span class="last-message">${ultimoM || 'No hay mensajes aún'}</span>
                    <span class="chat-timestamp">${formatTimestamp(new Date(date))}</span>
                </div>
            `;
            chatItem.addEventListener('click', () => {
                loadMessages(contact.ID_usuario); // Cargar los mensajes con el contacto
                document.getElementById('chat-input').style.display = 'block';
                document.getElementById('chat-header-name').innerText = contact.Nombre_usuario;
                const headerProfilePhoto = document.getElementById('header-profile-photo');
                headerProfilePhoto.src = profilePhoto; // Establecer la fuente de la foto de perfil
                headerProfilePhoto.style.display = 'block'; // Mostrar la foto de perfil
                const sendButton = document.getElementById('send-button');
                sendButton.setAttribute('data-contact-id', contact.ID_usuario); // Asignar ID del contacto
            });
            chatListContainer.appendChild(chatItem); // Agregar el chat a la lista
        }
    } catch (error) {
        console.error('Error al cargar la lista de chats:', error);
    }
}

// Función para enviar un mensaje (guarda en DB)
async function sendMessage(userId) {
    const messageInput = document.getElementById('message-input');
    const messageText = messageInput.value.trim();
    const sendButton = document.getElementById('send-button');
    const ContactId = parseInt(sendButton.getAttribute('data-contact-id'), 10);

    if (messageText === '' || !ContactId) {
        return; // No enviar mensajes vacíos o sin seleccionar un chat
    }
    sendButton.disabled = true; // Evitar duplicados

    const data = {
        senderId: userId,
        receiverId: ContactId,
        contenido: messageText
    };

    try {
        const response = await fetchData('http://localhost:8080/Backend/APIs/API_Chats/API_Chats.php/Mensaje', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        if (response) {
            messageInput.value = ''; // Limpiar el campo
            await loadMessages(ContactId); // Recargar los mensajes
            updateLastMessage(ContactId, messageText); // Actualizar la vista del último mensaje
        } else {
            console.error('Error al enviar el mensaje:', response.message);
        }
    } catch (error) {
        console.error('Error en la solicitud de envío de mensaje:', error);
    } finally {
        sendButton.disabled = false; // Reactivar el botón
    }
}

// Crear el chat visualmente en frontend sin guardar en la base de datos
async function createChatFrontend(contactId, contactName, profilePhoto) {
    const chatListContainer = document.getElementById('chat-list');
    console.log(chatListContainer);
    const existingChatItem = chatListContainer.querySelector(`[data-contact-id="${contactId}"]`);
    if (existingChatItem) {
        console.log(`El chat con el usuario ${contactId} ya existe, seleccionando...`);
        existingChatItem.click(); // Simular clic en el chat existente
        return;
    }
    console.log(existingChatItem);
    // Si no existe, crear un nuevo chat visualmente
    const chatItem = document.createElement('div');
    chatItem.className = 'chat-item';
    chatItem.setAttribute('data-contact-id', contactId); // Agregar data-contact-id para identificación

    chatItem.innerHTML = `
        <img src="${profilePhoto}" alt="${contactName}'s profile" class="profile-photo">
        <div class="chat-details">
            <p>${contactName}</p>
            <span class="last-message">No hay mensajes aún</span>
            <span class="chat-timestamp"></span>
        </div>
    `;

    chatItem.addEventListener('click', () => {
        loadMessages(contactId);
        document.getElementById('chat-input').style.display = 'block';
        document.getElementById('chat-header-name').innerText = contactName;
        const headerProfilePhoto = document.getElementById('header-profile-photo');
        headerProfilePhoto.src = profilePhoto;
        headerProfilePhoto.style.display = 'block';
        const sendButton = document.getElementById('send-button');
        sendButton.setAttribute('data-contact-id', contactId);
    });

    // Añadir el nuevo chat a la lista
    chatListContainer.appendChild(chatItem);
    chatItem.click(); // Simular un clic para cargar los mensajes automáticamente
}

// Cargar mensajes desde la DB
async function loadMessages(ContactId) {
    const messagesContainer = document.getElementById('chat-messages');
    messagesContainer.innerHTML = ''; // Limpiar mensajes existentes
    const sessionData = await GetSession();
    const userId = sessionData.ID_usuario;
    UpdateSeen(userId,ContactId);

    try {
        const messages = await fetchData(`http://localhost:8080/Backend/APIs/API_Chats/API_Chats.php/Mensajes/${ContactId}/${userId}`);
        const fragment = document.createDocumentFragment(); // Para optimizar la inserción
        messages.forEach(message => {
            const messageElement = document.createElement('div');
            messageElement.className = message.ID_usuario_envia === userId ? 'message sent' : 'message received';
            messageElement.innerHTML = `
                <p>${message.Contenido}</p>
                <span class="timestamp">${formatTimestamp(message.Fecha_envio)}</span>
                ${message.leeido ? '<span class="read-status">Leído</span>' : ''}
            `;
            fragment.appendChild(messageElement);
        });
        messagesContainer.appendChild(fragment);
        messagesContainer.scrollTo({
            top: messagesContainer.scrollHeight,
            behavior: 'smooth' // Animación suave al desplazarse al final
        });
    } catch (error) {
        console.error('Error al cargar mensajes:', error);
    }
}
//actualizar visto 
async function UpdateSeen (userId,ContactId){
    const data = {
        userId: userId,
        contactId: ContactId
    };
    console.log(data);
    try {
        const response = await fetchData(`http://localhost:8080/Backend/APIs/API_Chats/API_Chats.php/MarcarLeidos`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        console.log(response);
        // Verificar el estado de la respuesta
        if (!response) {
            console.error('Error al marcar los mensajes como leídos:', response);
        }
    } catch (error) {
        console.error('Error al enviar la solicitud para marcar los mensajes como leídos:', error);
    }
}
// Actualizar último mensaje en la vista de chatitem
function updateLastMessage(contactId, lastMessage) {
    const chatItems = document.querySelectorAll('.chat-item');
    chatItems.forEach(item => {
        const sendButton = document.getElementById('send-button');
        const contact = parseInt(sendButton.getAttribute('data-contact-id'), 10);
        if (contact === contactId) {
            const lastMessageSpan = item.querySelector('.last-message');
            lastMessageSpan.textContent = lastMessage;
        }
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    await loadChatList(); // Cargar la lista de chats
    const sessionData = await GetSession();
    const userId = sessionData.ID_usuario;

    const params = new URLSearchParams(window.location.search);
    const contactId = params.get('contactId');
    const contactName = await getProfilename(contactId);
    const profilePhoto = await getProfileImage(contactId);

    if (contactId && contactName && profilePhoto) {
        // Crear visualmente el chat en frontend
        await createChatFrontend(contactId, contactName, profilePhoto);
    }

    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    // Enviar mensaje al presionar Enter
    messageInput.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            sendMessage(userId);
        }
    });

    // Enviar mensaje al hacer clic en el botón
    sendButton.addEventListener('click', function () {
        sendMessage(userId);
    });
});


