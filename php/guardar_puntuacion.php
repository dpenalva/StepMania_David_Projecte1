<?php
session_start();

// Leer los datos enviados por POST
$data = [
    'jugador' => $_POST['jugador'],
    'cancion' => $_POST['cancion'],
    'puntuacion' => $_POST['puntuacion'],
    'fecha' => $_POST['fecha']
];

// Verificar si el nombre de usuario está en la cookie o en la sesión
if (!isset($_SESSION['nombreUsuario']) && isset($_COOKIE['nombreUsuario'])) {
    $_SESSION['nombreUsuario'] = $_COOKIE['nombreUsuario'];
}

$nombreUsuario = $_SESSION['nombreUsuario'] ?? 'Invitado';
$data['jugador'] = $nombreUsuario; // Actualizar el nombre del jugador con el correcto

// Cargar el archivo de clasificación
$archivoClasificacion = __DIR__ . '/clasificacion.json';
$clasificacion = file_exists($archivoClasificacion) ? json_decode(file_get_contents($archivoClasificacion), true) : [];

// Verificar si el jugador ya tiene una puntuación registrada para esa canción
$existe = false;
foreach ($clasificacion as &$entrada) {
    if ($entrada['jugador'] === $data['jugador'] && $entrada['cancion'] === $data['cancion']) {
        // Si ya existe una entrada y la nueva puntuación es mayor, se actualiza
        if ($data['puntuacion'] > $entrada['puntuacion']) {
            $entrada['puntuacion'] = $data['puntuacion'];
            $entrada['fecha'] = $data['fecha'];
        }
        $existe = true;
        break;
    }
}

// Si no existe una entrada, se crea una nueva
if (!$existe) {
    $clasificacion[] = $data;
}

// Guardar los cambios en el archivo JSON
file_put_contents($archivoClasificacion, json_encode($clasificacion, JSON_PRETTY_PRINT));

// Redirigir de nuevo al index.php o a la página que desees
header("Location: ../index.php");
exit;
?>
