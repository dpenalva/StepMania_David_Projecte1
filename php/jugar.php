<?php
// Verificar que se recibe el parámetro 'song' en la URL
$cancion = null; // Inicializar la variable $cancion como null
if (isset($_GET['song'])) {
    $songTitle = $_GET['song'];

    // Cargar la lista de canciones desde el archivo JSON
    $jsonFile = __DIR__ . '/canciones.json';

    if (file_exists($jsonFile)) {
        $canciones = json_decode(file_get_contents($jsonFile), true);

        // Buscar la canción correspondiente al título proporcionado
        foreach ($canciones as $c) {
            if ($c['titulo'] == $songTitle) {
                $cancion = $c;
                break;
            }
        }

        // Verificar si se encontró la canción
        if (!$cancion) {
            echo "<p>Error: No se encontró la canción solicitada.</p>";
            exit;
        }
    } else {
        echo "<p>Error: No se encontró el archivo JSON.</p>";
        exit;
    }
} else {
    echo "<p>Error: No se proporcionó una canción para jugar.</p>";
    exit;
}

// Añadir cancionData para pasar al JavaScript
$cancionData = [
    'songUrl' => '../' . $cancion['ficheroMusica'],
    'coverUrl' => '../' . $cancion['ficheroCaratula'],
    'titulo' => $cancion['titulo'],
    'artista' => $cancion['artista']
];

// Leer y parsear el fichero de juego
function parseGameData($content) {
    $lines = explode("\n", trim($content));
    $numElements = (int)trim($lines[0]);
    $gameData = [];
    for ($i = 1; $i <= $numElements; $i++) {
        if (!isset($lines[$i])) {
            // Error handling
            continue;
        }
        $parts = explode("#", $lines[$i]);
        if (count($parts) !== 3) {
            // Error handling
            continue;
        }
        $keyCode = trim($parts[0]);
        $timeAppear = floatval(trim($parts[1]));
        $timeDisappear = floatval(trim($parts[2]));
        $gameData[] = [
            'keyCode' => $keyCode,
            'timeAppear' => $timeAppear,
            'timeDisappear' => $timeDisappear
        ];
    }
    return $gameData;
}

// Ruta al fichero de juego
$gameDataFile = __DIR__ . '/../' . $cancion['ficheroJuego'];
if (file_exists($gameDataFile)) {
    $gameDataContent = file_get_contents($gameDataFile);
    $gameData = parseGameData($gameDataContent);
    $cancionData['gameData'] = $gameData;
} else {
    echo "<p>Error: No se encontró el archivo de datos del juego.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta y enlaces -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugar Canción</title>
    <link rel="stylesheet" href="../css/jugar.css">
    <style>
        /* Estilos para el logo */
        .logo img {
            width: 100px;
            height: auto;
            position: absolute;
            top: 10px;
            left: 10px;
        }
    </style>
</head>
<body>
    <!-- Logo con enlace a index.php -->
    <div class="logo">
        <a href="../index.php">
            <img src="../css/StepMania_Logo.png" alt="Logo del proyecto">
        </a>
    </div>

    <div class="game-container">
        <!-- Información de la canción -->
        <div class="song-info">
            <h2>Canción: <?php echo htmlspecialchars($cancionData['titulo']); ?></h2>
            <p>Artista: <?php echo htmlspecialchars($cancionData['artista']); ?></p>
            <p>Duración: <span id="duration">Cargando...</span></p>
            <img src="<?php echo htmlspecialchars($cancionData['coverUrl']); ?>" alt="Carátula de la canción" class="song-cover">
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

    <!-- Pasamos cancionData a JavaScript -->
    <script>
        const cancionData = <?php echo json_encode($cancionData); ?>;
    </script>
    <script src="../js/jugar.js"></script>
</body>
</html>
