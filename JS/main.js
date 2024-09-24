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
function guardarCancion() {
    const titulo = document.getElementById('titulo').value;
    const artista = document.getElementById('artista').value;

    const nuevaCancion = {
        title: titulo,
        artist: artista,
        // Aquí podrías añadir otros campos si los necesitas
    };

    // Obtener las canciones anteriores almacenadas en localStorage
    const cancionesAnteriores = JSON.parse(localStorage.getItem('addedSongs')) || [];

    // Añadir la nueva canción
    cancionesAnteriores.push(nuevaCancion);

    // Guardar nuevamente en localStorage
    localStorage.setItem('addedSongs', JSON.stringify(cancionesAnteriores));

    alert('¡Canción añadida con éxito!');

    return true;
}
