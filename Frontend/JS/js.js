document.getElementById('profile-picture').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profile-picture-img').src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('profile-picture-img').src = '#';
    }
});


/*-------------------------------------------------------------*/


    document.getElementById('darkmode').addEventListener('change', function () {
        document.body.classList.toggle('dark-mode', this.checked);
    });

    // Opción: Guardar la preferencia del usuario en localStorage
    if (localStorage.getItem('dark-mode') === 'true') {
        document.body.classList.add('dark-mode');
        document.getElementById('darkmode').checked = true;
    }

    document.getElementById('darkmode').addEventListener('change', function () {
        if (this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('dark-mode', 'true');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('dark-mode', 'false');
        }
    });
