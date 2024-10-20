<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Añadir Canción</title>
  <link rel="stylesheet" href="css/añadir.css">
</head>
<body>
  <!-- Logo fuera de la caja principal y con un enlace al inicio -->
  <a href="index.php">
    <div class="logo">
      <img src="css/StepMania_Logo.png" alt="Logo del proyecto">
    </div>
  </a>

  <div class="add-song-container">
    <h1>Añadir Canción</h1>
    <form action="php/subirCancion.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" required>
      </div>

      <div class="form-group">
        <label for="artista">Artista</label>
        <input type="text" id="artista" name="artista" required>
      </div>

      <div class="form-group">
        <label for="ficheroMusica">Fichero de Música</label>
        <input type="file" id="ficheroMusica" name="ficheroMusica" accept=".mp3, .ogg" required>
      </div>

      <div class="form-group">
        <label for="ficheroCaratula">Fichero Logo</label>
        <input type="file" id="ficheroCaratula" name="ficheroCaratula" accept=".png, .jpg" required>
      </div>

      <div class="form-group">
        <label for="ficheroJuego">Fichero de Juego (TXT)</label>
        <input type="file" id="ficheroJuego" name="ficheroJuego" accept=".txt">
      </div>

      <div class="form-group">
        <label for="juegoTextarea">O añade datos del juego</label>
        <textarea id="juegoTextarea" name="juegoTextarea" rows="5" placeholder="Introduce los datos del juego en formato correcto"></textarea>
      </div>

      <div class="form-group">
        <input type="submit" value="Añadir Canción">
      </div>
    </form>
  </div>
</body>
</html>
