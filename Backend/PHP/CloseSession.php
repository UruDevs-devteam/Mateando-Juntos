<?php
session_start();
session_unset();

// Destruir la sesión
session_destroy();

// Eliminar la cookie de la sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
echo '
<script>
    // Eliminar el token del localStorage
    localStorage.removeItem("jwtToken");

    // Mostrar mensaje de sesión cerrada
    alert("Sesión cerrada");

    // Redirigir a la página de inicio
    window.location.href = "../../Frontend/HTML/index.html";
</script>
';
exit();
