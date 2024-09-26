let score = 0;
let hits = 0;
let misses = 0;
let currentTime = 0;

const songDuration = document.getElementById("duration");
const scoreElement = document.getElementById("score");
const hitsElement = document.getElementById("hits");
const missesElement = document.getElementById("misses");

const song = new Audio(cancionData.songUrl);
const progressBar = document.getElementById('progress');
const symbolsContainer = document.getElementById('symbols-container');
let songLength = 0;

// Evento que detecta cuando la metadata de la canción está cargada
song.addEventListener('loadedmetadata', function() {
    songLength = song.duration;
    songDuration.textContent = `${Math.floor(songLength / 60)}:${Math.floor(songLength % 60).toString().padStart(2, '0')}`;
});

// Manejo de errores al cargar la canción
song.addEventListener('error', function(e) {
    console.error('Error al cargar el audio:', e);
    alert('Error al cargar la canción.');
});

// Función para actualizar la barra de progreso
function updateProgressBar() {
    const progressPercentage = (song.currentTime / songLength) * 100;
    progressBar.style.width = progressPercentage + '%';
}

// Función para generar símbolos (flechas aleatorias)
function generateSymbols() {
    const symbols = ['⬆️', '⬇️', '⬅️', '➡️'];  // Flechas posibles
    const symbol = document.createElement('div');
    const randomSymbol = symbols[Math.floor(Math.random() * symbols.length)];
    symbol.classList.add('symbol');
    symbol.textContent = randomSymbol;
    symbolsContainer.appendChild(symbol);

    // Animar la caída de los símbolos
    symbol.style.top = '0%';
    symbol.style.left = '50%';
    symbol.style.transform = 'translateX(-50%)';
    symbol.style.position = 'absolute';
    symbol.style.animation = 'fall 3s linear';

    // Escuchar la tecla correspondiente una sola vez por símbolo
    function handleKeyPress(event) {
        let pressedKey = null;

        // Asociar las teclas con los símbolos
        switch (event.key) {
            case 'ArrowUp': pressedKey = '⬆️'; break;
            case 'ArrowDown': pressedKey = '⬇️'; break;
            case 'ArrowLeft': pressedKey = '⬅️'; break;
            case 'ArrowRight': pressedKey = '➡️'; break;
        }

        // Si la tecla es correcta
        if (pressedKey === randomSymbol) {
            hits++;
            score += 100;
            symbol.remove();  // Eliminar símbolo acertado
            document.removeEventListener('keydown', handleKeyPress);  // Eliminar el evento de esta flecha
            updateScore();  // Actualizar la puntuación
        }
    }

    document.addEventListener('keydown', handleKeyPress);

    // Eliminar el símbolo si no es presionado a tiempo
    symbol.addEventListener('animationend', function() {
        symbol.remove();
        misses++;
        score -= 50;
        document.removeEventListener('keydown', handleKeyPress);  // Eliminar el evento de esta flecha
        updateScore();
    });
}

// Función para actualizar la puntuación
function updateScore() {
    scoreElement.textContent = score;
    hitsElement.textContent = hits;
    missesElement.textContent = misses;
}

// Iniciar el juego una vez que la canción esté lista para reproducirse
song.addEventListener('canplaythrough', function() {
    song.play();

    const gameInterval = setInterval(() => {
        currentTime += 1;
        updateProgressBar();

        // Generar símbolos periódicamente (cada 2 segundos)
        if (currentTime % 2 === 0) {
            generateSymbols();
        }

        // Terminar el juego cuando la canción termine
        if (currentTime >= songLength) {
            clearInterval(gameInterval);
            alert('Juego terminado. Puntuación: ' + score);
        }
    }, 1000);
});
