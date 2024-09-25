<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Canción</title>
    <link rel="stylesheet" href="../css/editar.css"> 
</head>
<body>
    <!-- Logo arriba a la izquierda -->
    <div class="logo">
        <a href="../index.php">
            <img src="../css/StepMania_Logo.png" alt="Logo del proyecto">
        </a>
    </div>

    <div class="container">
        <div class="form-container">
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
                        <h2>Editar Canción: <?php echo htmlspecialchars($cancionEditar['titulo']); ?></h2>
                        <form action="actualizarCancion.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="original_title" value="<?php echo htmlspecialchars($cancionEditar['titulo']); ?>">

                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($cancionEditar['titulo']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="artista">Artista</label>
                                <input type="text" id="artista" name="artista" value="<?php echo htmlspecialchars($cancionEditar['artista']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="ficheroMusica">Fichero de música</label>
                                <input type="file" id="ficheroMusica" name="ficheroMusica">
                                <p>Archivo actual: <?php echo htmlspecialchars(basename($cancionEditar['ficheroMusica'])); ?></p>
                            </div>

                            <div class="form-group">
                                <label for="ficheroCaratula">Fichero de carátula</label>
                                <input type="file" id="ficheroCaratula" name="ficheroCaratula">
                                <p>Archivo actual: <?php echo htmlspecialchars(basename($cancionEditar['ficheroCaratula'])); ?></p>
                            </div>

                            <div class="form-group">
                                <label for="ficheroJuego">Fichero de juego</label>
                                <input type="file" id="ficheroJuego" name="ficheroJuego">
                                <p>Archivo actual: <?php echo htmlspecialchars(basename($cancionEditar['ficheroJuego'])); ?></p>
                            </div>

                            <input type="submit" value="Actualizar Canción" class="main-button">
                        </form>
            <?php
                    } else {
                        echo "<p>Error: No se encontró la canción.</p>";
                    }
                } else {
                    echo "<p>Error: No se encontró el archivo JSON.</p>";
                }
            } else {
                echo "<p>Error: No se proporcionó el título de la canción.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
