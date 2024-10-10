function guardarNombre() {
    const nombreUsuario = document.getElementById('username').value;
    if (nombreUsuario) {
        // Crear una cookie con el nombre del usuario
        document.cookie = `nombreUsuario=${encodeURIComponent(nombreUsuario)}; path=/; max-age=3600`; // Expira en 1 hora
        return true;
    } else {
        alert('Por favor, introduce tu nombre.');
        return false;
    }
}
