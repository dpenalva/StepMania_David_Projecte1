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

// Recuperar canciones almacenadas en localStorage
document.addEventListener('DOMContentLoaded', function() {
    const songListElement = document.getElementById('song-list');
    const songs = JSON.parse(localStorage.getItem('addedSongs')) || [];

    // Mostrar las canciones en la lista de la derecha
    songs.forEach(song => {
        const songDiv = document.createElement('div');
        songDiv.classList.add('song');

        songDiv.innerHTML = `
            <p>${song.title} - ${song.artist}</p>
            <button>▶</button>
            <button>✎</button>
            <button>🗑</button>
        `;

        songListElement.appendChild(songDiv);
    });
});
