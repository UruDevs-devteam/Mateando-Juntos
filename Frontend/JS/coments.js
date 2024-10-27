
document.addEventListener('click', function(event) {
    // Referencias a los elementos del DOM
    var modal = document.getElementById("commentsModal");
    var closeModalBtn = document.querySelector(".close-coments");
    var submitCommentBtn = document.getElementById("submitComment");
    
    // Manejo del clic en el botón de comentario con ID "comentario"
    if (event.target.closest('#comentario')) {
        // Abre el modal al hacer clic en el botón de comentario
        modal.style.display = "block";
    }

    // Manejo del clic en la "X" para cerrar el modal
    if (event.target === closeModalBtn) {
        modal.style.display = "none";
    }

    // Manejo del clic fuera del modal para cerrarlo
    if (event.target === modal) {
        modal.style.display = "none";
    }

    // Manejo del envío del comentario
    if (event.target === submitCommentBtn) {
        var commentText = document.getElementById("commentText").value;
        if (commentText.trim() !== "") {
            alert('¡Comentario enviado: ' + commentText + '!');
            modal.style.display = "none"; // Cierra el modal después del envío
        } else {
            alert('Por favor, escribe un comentario antes de enviarlo.');
        }
    }
});