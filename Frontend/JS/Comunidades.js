import { fetchData,loadChatList,getLikeCount,getLikeIconClass,getProfileImage,fetchImages} from './shared_function.js';


async function fetchcomunitys() {
    try {
        const comunitys = await fetchData('http://localhost:8080/Backend/APIs/API_Groups/API_Groups.php/Groups');
        for (const comunity of comunitys) {
            const profilephoto = comunity.Url_fotocomunidad;
            
            const article = `
                <article class="comunity"  data-id="${comunity.ID_comunidad}" >
                    <div class="profile-photo">
                        <img src="../../UsersUploads/${profilephoto}" alt="Foto de la comunidad">
                    </div>
                    <div class="comunity-body">
                        <h5>${comunity.Nombre_comunidad}</h5>
                        <p class="caption">${comunity.Descripcion || 'Descripción no disponible'}</p>
                    </div>
                    <div class="seguir_comunidad">
                            <button><i class="fas fa-user-plus"></i> Unirse</button>
                        </div>
                </article>
            `;
            document.querySelector('.comunity-item').insertAdjacentHTML('beforeend', article);
        }
         
        // Agregar evento de clic para redirigir a `comunidad.html?id=ID_comunidad`
        document.querySelectorAll('.comunity').forEach(article => {
            article.addEventListener('click', function () {
                const communityId = this.getAttribute('data-id');
                if (communityId) {
                    window.location.href = `../HTML/comunidad.html?id=${communityId}`;
                }
            });
        });
    } catch (error) {
        console.error('Error al obtener las comunidades:', error);
    }
}

async function fetchPosts() {
    try {
        const posts = await fetchData(`http://localhost:8080/Backend/APIs/API_Groups/API_Groups.php/TopLikedPosts`);
        const feedsSection = document.querySelector('.feeds');
        console.log("api",posts);
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


document.addEventListener('DOMContentLoaded', () => {
    fetchcomunitys();
    loadChatList();
    fetchPosts();
    // Obtener el modal
    const modal = document.getElementById("createCommunityModal");
    const btn = document.getElementById("openModal");
    const span = document.getElementsByClassName("close")[0];

    // Abrir el modal
    btn.onclick = function() {
        const token = localStorage.getItem('jwtToken');  // Obtener el token de localStorage
        document.getElementById('token').value = token;
        modal.style.display = "block";
    }

    // Cerrar el modal cuando el usuario hace clic en <span> (x)
    span.onclick = function() {
        modal.style.display = "none";
    }
 
});

document.getElementById('joinButton').addEventListener('click', event => {
    console.log('yeeee');
});