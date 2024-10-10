<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
    // Guardar el nombre de usuario en la sesión y en la cookie
    $_SESSION['nombreUsuario'] = $_POST['username'];
    setcookie('nombreUsuario', $_POST['username'], time() + (86400 * 30), "/"); // 30 días

    // Redirigir al index.php
    header("Location: ../index.php");
    exit;
} else {
    echo "Error: Debes introducir un nombre.";
}
?>
