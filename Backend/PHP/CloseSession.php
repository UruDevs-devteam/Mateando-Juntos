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
  alert("Sesion cerrada");
  window.location = "../../Frontend/HTML/index.html";
  </script>
     ';     