<?php
if (isset($_GET['title'])) {
    $jsonFile = 'php/canciones.json';
    $title = $_GET['title'];

    if (file_exists($jsonFile)) {
        $canciones = json_decode(file_get_contents($jsonFile), true);

        // Buscar y eliminar la canción del array
        foreach ($canciones as $key => $cancion) {
            if ($cancion['title'] == $title) {
                // Eliminar archivos relacionados
                if (file_exists($cancion['url_song'])) {
                    unlink($cancion['url_song']); // Eliminar el archivo de la canción
                }
                if (file_exists($cancion['url_game'])) {
                    unlink($cancion['url_game']); // Eliminar el archivo de juego
                }
                if (file_exists($cancion['cover'])) {
                    unlink($cancion['cover']); // Eliminar la carátula
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
    } else {
        echo "Error: El archivo JSON no existe.";
    }
} else {
    echo "Error: No se proporcionó un título de canción.";
}
?>
