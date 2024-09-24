// Recuperar el nombre del usuario desde el LocalStorage
document.addEventListener('DOMContentLoaded', function() {
    const nombreUsuario = localStorage.getItem('nombreUsuario');
    if (nombreUsuario) {
        document.getElementById('nombreUsuario').textContent = nombreUsuario;
    } else {
        // Redirigir al usuario al formulario de nombre si no hay nombre guardado
        window.location.href = 'main.html';
    }
});
