<?php
// Incluir el archivo para validar el archivo de juego
require_once('validarJuego.php');
require_once('getid3-MASTER/getid3/getid3.php'); // Asegúrate de que la ruta es correcta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $artista = $_POST['artista'];
    $juegoTextarea = $_POST['juegoTextarea'];

    // Asegurarse de que el título y el artista no están vacíos
    if (empty($titulo) || empty($artista)) {
        echo "Error: Todos los campos son obligatorios.";
        exit;
    }

    // Asegurarse de que uploads/ esté en el directorio raíz
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Crea la carpeta si no existe
    }

    // Subir archivo de música
    $ficheroMusica = $_FILES['ficheroMusica'];
    $musicaExtension = pathinfo($ficheroMusica['name'], PATHINFO_EXTENSION);
    $musicaExtension = strtolower($musicaExtension); // Asegurarse de que la extensión esté en minúsculas

    if (!in_array($musicaExtension, ['mp3', 'ogg'])) {
        echo "Error: Solo se permiten archivos de música en formato MP3 o OGG.";
        exit;
    }

    $musicaPath = $uploadDir . basename($ficheroMusica['name']);
    move_uploaded_file($ficheroMusica['tmp_name'], $musicaPath);

    // Obtener la duración del archivo de música
    $getID3 = new getID3;
    $fileInfo = $getID3->analyze($musicaPath);

    // Convertir la duración de segundos a formato minutos:segundos
    $duracionSegundos = (float)$fileInfo['playtime_seconds']; // Duración en segundos
    $minutos = floor($duracionSegundos / 60);
    $segundos = floor($duracionSegundos % 60);

    // Formatear para que siempre aparezcan 2 dígitos en los segundos (ejemplo: 01:05)
    $duracionFormateada = sprintf('%02d:%02d', $minutos, $segundos);

    // Subir archivo de carátula
    $ficheroCaratula = $_FILES['ficheroCaratula'];
    $caratulaPath = $uploadDir . basename($ficheroCaratula['name']);
    move_uploaded_file($ficheroCaratula['tmp_name'], $caratulaPath);

    // Verificación: Si el usuario sube un archivo TXT y también escribe en el textarea, lanzar error
    if (!empty($_FILES['ficheroJuego']['name']) && !empty($juegoTextarea)) {
        echo "Error: No puedes subir un archivo de juego y añadir datos manualmente a la vez.";
        exit;
    }

    // Subir archivo de juego o validar datos manuales
    if (!empty($_FILES['ficheroJuego']['name'])) {
        $ficheroJuego = $_FILES['ficheroJuego'];
        $juegoPath = $uploadDir . basename($ficheroJuego['name']);
        move_uploaded_file($ficheroJuego['tmp_name'], $juegoPath);

        // Validar el archivo de juego subido
        $juegoContenido = file_get_contents($juegoPath);
        $error = validarJuego($juegoContenido);
        if ($error) {
            echo $error;
            exit;
        }
    } elseif (!empty($juegoTextarea)) {
        // Validar el contenido manual del textarea
        $error = validarJuego($juegoTextarea);
        if ($error) {
            echo $error;
            exit;
        }

        // Guardar los datos manuales en un archivo
        $juegoPath = $uploadDir . $titulo . '_juego.txt';
        file_put_contents($juegoPath, $juegoTextarea);
    } else {
        echo "Error: Debes subir un fichero de juego o añadir datos en el textarea.";
        exit;
    }

    // Asegurarse de que el archivo JSON exista
    $jsonFile = __DIR__ . '/canciones.json';
    if (!file_exists($jsonFile)) {
        file_put_contents($jsonFile, json_encode([]));  // Crear archivo JSON vacío si no existe
    }

    $jsonData = json_decode(file_get_contents($jsonFile), true);

    // Añadir nueva canción
    $nuevaCancion = [
        'titulo' => $titulo,
        'artista' => $artista,
        'ficheroMusica' => 'uploads/' . basename($ficheroMusica['name']),
        'ficheroCaratula' => 'uploads/' . basename($ficheroCaratula['name']),
        'ficheroJuego' => 'uploads/' . basename($juegoPath),
        'duracion' => $duracionFormateada, // Guardar la duración formateada
    ];

    $jsonData[] = $nuevaCancion;
    file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT));

    // Redirigir a la página principal
    header('Location: ../index.php');
    exit;
}
?>
