<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simulador de Baile - Menú Principal</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <!-- Logo añadido con enlace a main.html -->
  <div class="logo">
    <a href="main.html">
      <img src="css/StepMania_Logo.png" alt="Logo del proyecto" class="logo">
    </a>
  </div>

  <div class="container">
    <div class="left-panel">
      <div class="classification">
        <h2>Tabla de Clasificación</h2>
        <div class="classification-list">
          <!-- Aquí irán las entradas de la tabla de clasificación -->
        </div>
      </div>
    </div>

    <div class="center-panel">
      <h1>Bienvenido, <span id="nombreUsuario"></span></h1>
      <a href="jugar.php"><button class="main-button">PLAY</button></a>
      <a href="añadir.php"><button class="main-button">AÑADIR</button></a>
      <a href="como_jugar.php"><button class="main-button">CÓMO JUGAR?</button></a>
    </div>

    <div class="right-panel">
  <h2>Canciones Añadidas</h2>
  <div id="song-list">
    <?php
    $jsonFile = 'php/canciones.json';

    // Verificar si el archivo existe y contiene datos
    if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
        $canciones = json_decode(file_get_contents($jsonFile), true);

        // Ordenar las canciones alfabéticamente por título
        usort($canciones, function($a, $b) {
            return strcmp($a['titulo'], $b['titulo']);
        });

        // Mostrar las canciones
        if (is_array($canciones) && count($canciones) > 0) {
            foreach ($canciones as $cancion) {
                echo "<div class='song'>";
                
                // Mostrar carátula
                echo "<img src='{$cancion['ficheroCaratula']}' alt='Carátula de {$cancion['titulo']}' class='song-cover'>";
                
                // Mostrar información de la canción
                echo "<div class='song-info'>";
                echo "<p>{$cancion['titulo']} - {$cancion['artista']} ({$cancion['ficheroMusica']})</p>";
                echo "</div>";
                
                // Opciones: Jugar, Personalizar, Eliminar
                echo "<div class='song-options'>";
                echo "<button onclick=\"location.href='{$cancion['ficheroMusica']}'\"><i class='fas fa-play'></i></button>"; // Reproducir
                echo "<button onclick=\"location.href='php/editar.php?song={$cancion['titulo']}'\"><i class='fas fa-edit'></i></button>"; // Editar
                echo "<button onclick=\"eliminarCancion('{$cancion['titulo']}')\"><i class='fas fa-trash-alt'></i></button>"; // Eliminar
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p>No hay canciones añadidas.</p>";
        }
    } else {
        echo "<p>No hay canciones añadidas.</p>";
    }
    ?>
  </div>
</div>

<script>
// Función para eliminar una canción (puedes implementar la lógica en PHP para eliminar el archivo)
function eliminarCancion(titulo) {
    if (confirm('¿Estás seguro de que quieres eliminar ' + titulo + '?')) {
        // Lógica para eliminar la canción (se puede implementar en PHP)
        location.href = 'eliminar.php?titulo=' + titulo;
    }
}
</script>


  <script src="js/index.js"></script>
</body>
</html>
