<?php
// Cargar la canción desde el JSON según el título
if (isset($_GET['song'])) {
    $jsonFile = __DIR__ . '/canciones.json';
    $titulo = $_GET['song'];

    if (file_exists($jsonFile)) {
        $canciones = json_decode(file_get_contents($jsonFile), true);
        $cancionEditar = null;

        // Buscar la canción por título
        foreach ($canciones as $cancion) {
            if ($cancion['titulo'] == $titulo) {
                $cancionEditar = $cancion;
                break;
            }
        }

        // Si encontramos la canción, mostramos el formulario con los datos precargados
        if ($cancionEditar) {
?>
            <h2>Editar Canción: <?php echo $cancionEditar['titulo']; ?></h2>
            <form action="actualizarCancion.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="original_title" value="<?php echo $cancionEditar['titulo']; ?>">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo $cancionEditar['titulo']; ?>" required><br>

                <label for="artista">Artista</label>
                <input type="text" id="artista" name="artista" value="<?php echo $cancionEditar['artista']; ?>" required><br>

                <label for="ficheroMusica">Fichero de música</label>
                <input type="file" id="ficheroMusica" name="ficheroMusica"><br>
                <p>Archivo actual: <?php echo basename($cancionEditar['ficheroMusica']); ?></p>

                <label for="ficheroCaratula">Fichero de carátula</label>
                <input type="file" id="ficheroCaratula" name="ficheroCaratula"><br>
                <p>Archivo actual: <?php echo basename($cancionEditar['ficheroCaratula']); ?></p>

                <label for="ficheroJuego">Fichero de juego</label>
                <input type="file" id="ficheroJuego" name="ficheroJuego"><br>
                <p>Archivo actual: <?php echo basename($cancionEditar['ficheroJuego']); ?></p>

                <input type="submit" value="Actualizar Canción">
            </form>
<?php
        } else {
            echo "Error: No se encontró la canción.";
        }
    } else {
        echo "Error: No se encontró el archivo JSON.";
    }
} else {
    echo "Error: No se proporcionó el título de la canción.";
}
?>
