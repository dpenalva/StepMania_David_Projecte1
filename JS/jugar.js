// Variables globales para la puntuación y progreso
let score = 0;
let hits = 0;
let misses = 0;
let progress = 0;

// Elementos DOM
const scoreElement = document.getElementById('score');
const hitsElement = document.getElementById('hits');
const missesElement = document.getElementById('misses');
const progressElement = document.getElementById('progress');
const symbolsContainer = document.getElementById('symbols-container');

// Inicializar el juego
function startGame() {
    // Simulación de duración de la canción
    const songDuration = 100; // 100 segundos (simulación)
    document.getElementById('duration').textContent = songDuration + ' segundos';

    // Iniciar barra de progreso
    const interval = setInterval(() => {
        progress += 1;
        progressElement.style.width = (progress / songDuration) * 100 + '%';

        if (progress >= songDuration) {
            clearInterval(interval);
            endGame();
        }
    }, 1000);

    // Generar símbolos cada cierto tiempo (simulación)
    generateSymbols();
}

// Generar símbolos para que el jugador los presione
function generateSymbols() {
    // Simulación de generar un símbolo cada 2 segundos
    setInterval(() => {
        const symbol = document.createElement('div');
        symbol.textContent = "⬆"; // Puedes cambiar este símbolo por otros
        symbol.className = "symbol";
        symbolsContainer.appendChild(symbol);

        // Simulación de presionar la tecla correcta
        setTimeout(() => {
            // Eliminar el símbolo después de cierto tiempo
            symbolsContainer.removeChild(symbol);
            // Aumentar errores si no se presiona a tiempo
            misses++;
            missesElement.textContent = misses;
        }, 3000); // El símbolo desaparece después de 3 segundos
    }, 2000); // Genera un símbolo cada 2 segundos
}

// Lógica cuando termina la canción
function endGame() {
    alert(`Juego terminado! Puntuación: ${score}`);
    // Aquí puedes redirigir a la página de estadísticas o guardar la puntuación
}

// Iniciar el juego al cargar la página
window.onload = startGame;
