let score = 0;
let hits = 0;
let misses = 0;
let isPaused = false; // Estado de pausa
let gameInterval;
let timeIntervals = []; // Arreglo para guardar los timeouts
const scheduledSymbols = []; // Para evitar duplicar símbolos
let songStarted = false;

const songDuration = document.getElementById("duration");
const scoreElement = document.getElementById("score");
const hitsElement = document.getElementById("hits");
const missesElement = document.getElementById("misses");
const pauseButton = document.getElementById("pause-button");

const song = new Audio(cancionData.songUrl);
const progressBar = document.getElementById('progress');
const symbolsContainer = document.getElementById('symbols-container');
let songLength = 0;

const gameData = cancionData.gameData;
const activeSymbols = [];

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
    if (!isPaused) {
        const progressPercentage = (song.currentTime / songLength) * 100;
        progressBar.style.width = progressPercentage + '%';
    }
}

// Función para mapear keyCode a event.key
function getEventKeyFromKeyCode(keyCode) {
    const keyCodeMap = {
        37: 'ArrowLeft',
        38: 'ArrowUp',
        39: 'ArrowRight',
        40: 'ArrowDown',
        32: ' ', // Espacio
    };

    return keyCodeMap[keyCode] || String.fromCharCode(keyCode);
}

// Función para generar símbolos según el gameData
function generateSymbol(symbolData) {
    // Evitar duplicar símbolos
    if (scheduledSymbols.includes(symbolData)) {
        return;
    }
    scheduledSymbols.push(symbolData);

    const symbol = document.createElement('div');
    symbol.classList.add('symbol');

    const keyCode = parseInt(symbolData.keyCode);
    const expectedKey = getEventKeyFromKeyCode(keyCode);

    const keyToDisplaySymbol = {
        'ArrowLeft': '⬅️',
        'ArrowUp': '⬆️',
        'ArrowRight': '➡️',
        'ArrowDown': '⬇️',
        ' ': '⎵', // Espacio
    };

    const displaySymbol = keyToDisplaySymbol[expectedKey] || expectedKey.toUpperCase();

    symbol.textContent = displaySymbol;
    symbolsContainer.appendChild(symbol);

    symbol.style.top = '0%';
    symbol.style.left = '50%';
    symbol.style.transform = 'translateX(-50%)';
    symbol.style.position = 'absolute';

    const animationDuration = (symbolData.timeDisappear - symbolData.timeAppear) * 1000;
    symbol.style.animation = `fall ${animationDuration / 1000}s linear`;

    activeSymbols.push({
        element: symbol,
        expectedKey: expectedKey,
        removeTime: symbolData.timeDisappear,
        animationDuration: animationDuration,
    });

    const removeTimeout = setTimeout(() => {
        const index = activeSymbols.findIndex(s => s.element === symbol);
        if (index !== -1 && !isPaused) {
            misses++;
            score -= 50;
            updateScore();

            symbol.remove();
            activeSymbols.splice(index, 1);
        }
    }, animationDuration);

    // Guardar el timeoutId para poder pausarlo
    symbol.removeTimeoutId = removeTimeout;
}

// Función para actualizar la puntuación
function updateScore() {
    scoreElement.textContent = score;
    hitsElement.textContent = hits;
    missesElement.textContent = misses;
}

// Guardar la puntuación en la clasificación
function guardarPuntuacion() {
    const nombreUsuario = localStorage.getItem('nombreUsuario') || 'Invitado';  // Obtener el nombre del localStorage
    const data = {
        jugador: nombreUsuario,
        cancion: cancionData.titulo,
        puntuacion: score,
        fecha: new Date().toLocaleString()
    };

    fetch('../php/guardar_puntuacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Puntuación guardada correctamente');
        } else {
            alert('Error al guardar la puntuación');
        }
    });
}

function gameLoop() {
    if (isPaused) return;

    const currentTime = song.currentTime;

    // Generar símbolos que deben aparecer en este momento
    gameData.forEach(symbolData => {
        if (!symbolData.generated && symbolData.timeAppear <= currentTime) {
            generateSymbol(symbolData);
            symbolData.generated = true;
        }
    });

    updateProgressBar();

    if (currentTime >= songLength) {
        clearInterval(gameInterval);
        alert('Juego terminado. Puntuación: ' + score);
        guardarPuntuacion();
    }
}

function startGame() {
    if (songStarted) return;
    songStarted = true;

    song.play();

    gameInterval = setInterval(gameLoop, 50);
}

// Iniciar el juego
if (song.readyState >= 3) { // HAVE_FUTURE_DATA
    startGame();
} else {
    song.addEventListener('canplaythrough', startGame);
}

// Escuchar eventos de teclado
document.addEventListener('keydown', function(event) {
    if (isPaused) return;

    const pressedKey = event.key;
    let symbolHit = false;

    for (let i = 0; i < activeSymbols.length; i++) {
        const symbolObj = activeSymbols[i];
        if (symbolObj.expectedKey.toLowerCase() === pressedKey.toLowerCase()) {
            hits++;
            score += 100;
            updateScore();

            symbolObj.element.remove();
            activeSymbols.splice(i, 1);

            symbolHit = true;
            break;
        }
    }

    if (!symbolHit) {
        misses++;
        score -= 50;
        updateScore();
    }
});

// Manejar el botón de pausa
pauseButton.addEventListener('click', function() {
    if (!isPaused) {
        song.pause();
        isPaused = true;
        pauseButton.textContent = 'Reanudar';

        // Pausar todas las animaciones
        activeSymbols.forEach(symbolObj => {
            const computedStyle = window.getComputedStyle(symbolObj.element);
            const topValue = computedStyle.getPropertyValue('top');
            symbolObj.element.style.top = topValue;
            symbolObj.element.style.animation = 'none';
            clearTimeout(symbolObj.removeTimeoutId);
            // Calcular el tiempo restante
            symbolObj.remainingTime = (symbolObj.removeTime - song.currentTime) * 1000;
        });

    } else {
        song.play();
        isPaused = false;
        pauseButton.textContent = 'Pausa';

        // Reanudar las animaciones
        activeSymbols.forEach(symbolObj => {
            symbolObj.element.style.animation = `fall ${symbolObj.remainingTime / 1000}s linear`;
            // Reiniciar el timeout para eliminar el símbolo
            symbolObj.removeTimeoutId = setTimeout(() => {
                const index = activeSymbols.findIndex(s => s.element === symbolObj.element);
                if (index !== -1 && !isPaused) {
                    misses++;
                    score -= 50;
                    updateScore();

                    symbolObj.element.remove();
                    activeSymbols.splice(index, 1);
                }
            }, symbolObj.remainingTime);
        });
    }
});
