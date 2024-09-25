<?php
if (isset($_GET['titulo'])) {
    $jsonFile = __DIR__ . '/canciones.json';
    $titulo = $_GET['titulo']; // Título recibido desde el parámetro URL

    if (file_exists($jsonFile)) {
        $canciones = json_decode(file_get_contents($jsonFile), true);

        // Buscar y eliminar la canción del array
        foreach ($canciones as $key => $cancion) {
            if ($cancion['titulo'] == $titulo) {
                // Eliminar archivos relacionados
                if (file_exists($cancion['ficheroMusica'])) {
                    unlink($cancion['ficheroMusica']); // Eliminar el archivo de la canción
                }
                if (file_exists($cancion['ficheroJuego'])) {
                    unlink($cancion['ficheroJuego']); // Eliminar el archivo de juego
                }
                if (file_exists($cancion['ficheroCaratula'])) {
                    unlink($cancion['ficheroCaratula']); // Eliminar la carátula
                }

                // Eliminar la canción del array
                unset($canciones[$key]);
                break;
            }
        }

        // Guardar los cambios en el archivo JSON
        file_put_contents($jsonFile, json_encode(array_values($canciones)));

        // Redirigir a la página principal
        header('Location: ../index.php');
        exit;
    }
} else {
    echo "Error: No se proporcionó un título de canción.";
}
?>
