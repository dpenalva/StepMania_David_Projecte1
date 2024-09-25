<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jsonFile = __DIR__ . '/canciones.json';
    $originalTitle = $_POST['original_title']; // Título original antes de la edición
    $titulo = $_POST['titulo'];  // Nuevo título
    $artista = $_POST['artista'];  // Nuevo artista

    // Cargar el archivo JSON
    if (file_exists($jsonFile)) {
        $canciones = json_decode(file_get_contents($jsonFile), true);

        // Buscar la canción original y actualizar los datos
        foreach ($canciones as $key => $cancion) {
            if ($cancion['titulo'] == $originalTitle) {
                // Actualizar los datos de la canción
                $canciones[$key]['titulo'] = $titulo;
                $canciones[$key]['artista'] = $artista;

                // Si se ha subido un nuevo archivo de música, actualizar el archivo
                if (!empty($_FILES['ficheroMusica']['name'])) {
                    $ficheroMusica = $_FILES['ficheroMusica'];
                    $musicaPath = 'uploads/' . basename($ficheroMusica['name']);
                    move_uploaded_file($ficheroMusica['tmp_name'], $musicaPath);
                    $canciones[$key]['ficheroMusica'] = $musicaPath;
                }

                // Si se ha subido un nuevo archivo de carátula, actualizar el archivo
                if (!empty($_FILES['ficheroCaratula']['name'])) {
                    $ficheroCaratula = $_FILES['ficheroCaratula'];
                    $caratulaPath = 'uploads/' . basename($ficheroCaratula['name']);
                    move_uploaded_file($ficheroCaratula['tmp_name'], $caratulaPath);
                    $canciones[$key]['ficheroCaratula'] = $caratulaPath;
                }

                // Si se ha subido un nuevo archivo de juego, actualizar el archivo
                if (!empty($_FILES['ficheroJuego']['name'])) {
                    $ficheroJuego = $_FILES['ficheroJuego'];
                    $juegoPath = 'uploads/' . basename($ficheroJuego['name']);
                    move_uploaded_file($ficheroJuego['tmp_name'], $juegoPath);
                    $canciones[$key]['ficheroJuego'] = $juegoPath;
                }

                // Guardar los cambios en el archivo JSON
                file_put_contents($jsonFile, json_encode($canciones));

                // Redirigir al index.php después de la actualización
                header('Location: ../index.php');
                exit;
            }
        }
    } else {
        echo "Error: No se encontró el archivo JSON.";
    }
}
?>
