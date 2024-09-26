let score = 0;
let hits = 0;
let misses = 0;

const songDuration = document.getElementById("duration");
const scoreElement = document.getElementById("score");
const hitsElement = document.getElementById("hits");
const missesElement = document.getElementById("misses");

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
    const progressPercentage = (song.currentTime / songLength) * 100;
    progressBar.style.width = progressPercentage + '%';
}

// Función para mapear keyCode a event.key
function getEventKeyFromKeyCode(keyCode) {
    const keyCodeMap = {
        37: 'ArrowLeft',
        38: 'ArrowUp',
        39: 'ArrowRight',
        40: 'ArrowDown',
        32: ' ', // Espacio
        // Añadir más si es necesario
    };

    if (keyCodeMap[keyCode]) {
        return keyCodeMap[keyCode];
    } else {
        // Para letras y números
        return String.fromCharCode(keyCode);
    }
}

// Función para generar símbolos según el gameData
function generateSymbol(symbolData) {
    const symbol = document.createElement('div');
    symbol.classList.add('symbol');

    const keyCode = parseInt(symbolData.keyCode);
    const expectedKey = getEventKeyFromKeyCode(keyCode);

    // Mapear expectedKey a un símbolo para mostrar
    const keyToDisplaySymbol = {
        'ArrowLeft': '⬅️',
        'ArrowUp': '⬆️',
        'ArrowRight': '➡️',
        'ArrowDown': '⬇️',
        ' ': '⎵', // Espacio
        // Para letras y números, mostrar el carácter
    };

    const displaySymbol = keyToDisplaySymbol[expectedKey] || expectedKey.toUpperCase();

    symbol.textContent = displaySymbol;
    symbolsContainer.appendChild(symbol);

    // Establecer estilos y animación
    symbol.style.top = '0%';
    symbol.style.left = '50%';
    symbol.style.transform = 'translateX(-50%)';
    symbol.style.position = 'absolute';
    symbol.style.animation = `fall ${symbolData.timeDisappear - symbolData.timeAppear}s linear`;

    // Añadir símbolo al array de símbolos activos
    activeSymbols.push({
        element: symbol,
        expectedKey: expectedKey,
        removeTime: symbolData.timeDisappear
    });

    // Remover el símbolo después del tiempo de desaparición
    setTimeout(() => {
        // Verificar si el símbolo aún está en activeSymbols
        const index = activeSymbols.findIndex(s => s.element === symbol);
        if (index !== -1) {
            // El jugador no presionó la tecla a tiempo
            misses++;
            score -= 50;
            updateScore();

            // Remover símbolo del DOM y del array
            symbol.remove();
            activeSymbols.splice(index, 1);
        }
    }, (symbolData.timeDisappear - symbolData.timeAppear) * 1000);
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

    // Programar los símbolos según el gameData
    gameData.forEach(symbolData => {
        const timeToAppear = symbolData.timeAppear * 1000; // en milisegundos
        setTimeout(() => {
            generateSymbol(symbolData);
        }, timeToAppear);
    });

    // Actualizar la barra de progreso y controlar el tiempo
    const gameInterval = setInterval(() => {
        updateProgressBar();

        // Terminar el juego cuando la canción termine
        if (song.currentTime >= songLength) {
            clearInterval(gameInterval);
            alert('Juego terminado. Puntuación: ' + score);
        }
    }, 100);
});

// Escuchar eventos de teclado
document.addEventListener('keydown', function(event) {
    const pressedKey = event.key;

    for (let i = 0; i < activeSymbols.length; i++) {
        const symbolObj = activeSymbols[i];
        if (symbolObj.expectedKey.toLowerCase() === pressedKey.toLowerCase()) {
            // Tecla correcta presionada
            hits++;
            score += 100;
            updateScore();

            // Remover símbolo del DOM
            symbolObj.element.remove();

            // Remover símbolo del array de símbolos activos
            activeSymbols.splice(i, 1);

            // No es necesario seguir buscando
            break;
        }
    }
});
