// Selecciona el input de archivo y la imagen de vista previa
const profileInput = document.getElementById('profile_picture');
const profilePreview = document.getElementById('profile-picture-img');

// Escucha cuando el usuario selecciona una imagen
profileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];  // Toma el primer archivo seleccionado
    if (file) {
        const reader = new FileReader();  // Crea un FileReader para leer el archivo
        
        reader.onload = function(e) {
            profilePreview.src = e.target.result;  // Establece la fuente de la imagen como la lectura del archivo
        }

        reader.readAsDataURL(file);  // Lee el archivo como una URL de datos
    }
});
