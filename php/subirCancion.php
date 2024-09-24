<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $artista = $_POST['artista'];
    $juegoTextarea = $_POST['juegoTextarea'];

    // Asegúrate de que uploads/ esté en el directorio raíz
    $uploadDir = __DIR__ . '/../uploads/';  // __DIR__ asegura que estés en la carpeta correcta
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);  // Crea la carpeta si no existe en el directorio raíz
    }

    // Subir archivo de música
    $ficheroMusica = $_FILES['ficheroMusica'];
    $musicaPath = $uploadDir . basename($ficheroMusica['name']);
    move_uploaded_file($ficheroMusica['tmp_name'], $musicaPath);

    // Subir archivo de carátula
    $ficheroCaratula = $_FILES['ficheroCaratula'];
    $caratulaPath = $uploadDir . basename($ficheroCaratula['name']);
    move_uploaded_file($ficheroCaratula['tmp_name'], $caratulaPath);

    // Subir archivo de juego o usar textarea
    if (!empty($_FILES['ficheroJuego']['name'])) {
        $ficheroJuego = $_FILES['ficheroJuego'];
        $juegoPath = $uploadDir . basename($ficheroJuego['name']);
        move_uploaded_file($ficheroJuego['tmp_name'], $juegoPath);
    } elseif (!empty($juegoTextarea)) {
        $juegoPath = $uploadDir . $titulo . '_juego.txt';
        file_put_contents($juegoPath, $juegoTextarea);
    } else {
        echo "Error: Debes subir un fichero de juego o añadir datos en el textarea.";
        exit;
    }

    // Asegurarse de que el archivo JSON exista
    $jsonFile = __DIR__ . '/canciones.json';  // Crear/usar el archivo JSON en el directorio php/
    if (!file_exists($jsonFile)) {
        file_put_contents($jsonFile, json_encode([]));  // Crea el archivo JSON si no existe
    }

    $jsonData = json_decode(file_get_contents($jsonFile), true);

    // Añadir nueva canción
    $nuevaCancion = [
        'titulo' => $titulo,
        'artista' => $artista,
        'ficheroMusica' => 'uploads/' . basename($ficheroMusica['name']),
        'ficheroCaratula' => 'uploads/' . basename($ficheroCaratula['name']),
        'ficheroJuego' => 'uploads/' . basename($juegoPath),
    ];

    $jsonData[] = $nuevaCancion;
    file_put_contents($jsonFile, json_encode($jsonData));

    // Redirigir a la página principal
    header('Location: ../index.php');
    exit;
}
?>
