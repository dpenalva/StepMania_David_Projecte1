<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugar Canción</title>
    <link rel="stylesheet" href="css/jugar.css"> <!-- Archivo CSS para el juego -->
</head>
<body>
    <div class="game-container">
        <!-- Información de la canción -->
        <div class="song-info">
            <h2>Canción: <?php echo htmlspecialchars($cancion['titulo']); ?></h2>
            <p>Artista: <?php echo htmlspecialchars($cancion['artista']); ?></p>
            <p>Duración: <span id="duration"></span></p>
            <img src="<?php echo htmlspecialchars($cancion['ficheroCaratula']); ?>" alt="Carátula de la canción" class="song-cover">
        </div>

        <!-- Área del juego -->
        <div class="game-area">
            <div class="symbols-container" id="symbols-container">
                <!-- Aquí aparecerán los símbolos que el jugador debe presionar -->
            </div>
            <div class="progress-bar">
                <div id="progress" class="progress"></div>
            </div>
        </div>

        <!-- Puntuación del jugador -->
        <div class="score-info">
            <h2>Puntuación: <span id="score">0</span></h2>
            <p>Aciertos: <span id="hits">0</span></p>
            <p>Errores: <span id="misses">0</span></p>
        </div>
    </div>

    <?php
// Verificar que la canción se pasa como parámetro
if (isset($_GET['song'])) {
    $songTitle = $_GET['song'];

    // Cargar la canción desde el archivo JSON
    $jsonFile = 'canciones.json';
    if (file_exists($jsonFile)) {
        $canciones = json_decode(file_get_contents($jsonFile), true);
        foreach ($canciones as $cancion) {
            if ($cancion['titulo'] == $songTitle) {
                // Aquí puedes procesar la canción seleccionada
                echo "<h1>Jugando: " . htmlspecialchars($cancion['titulo']) . "</h1>";
                break;
            }
        }
    } else {
        echo "<p>No se encontró el archivo de canciones.</p>";
    }
} else {
    echo "<p>No se seleccionó ninguna canción.</p>";
}
?>


    <script src="js/jugar.js"></script> <!-- Archivo JavaScript para la lógica del juego -->
</body>
</html>
