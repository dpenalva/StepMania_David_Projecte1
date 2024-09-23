function guardarNombre() {
    const nombreUsuario = document.getElementById('username').value;
    if (nombreUsuario) {
        localStorage.setItem('nombreUsuario', nombreUsuario);
        return true; // Permite que el formulario se envíe
    } else {
        alert('Por favor, introduce tu nombre.');
        return false; // Evita que el formulario se envíe si no hay nombre
    }
}
