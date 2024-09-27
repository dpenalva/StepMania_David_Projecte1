<?php
session_start();

$inputData = json_decode(file_get_contents('php://input'), true);

$jugador = $inputData['jugador'];
$cancion = $inputData['cancion'];
$puntuacion = (int)$inputData['puntuacion'];

$archivoClasificacion = __DIR__ . '/clasificacion.json';

// Leer el archivo de clasificación
if (file_exists($archivoClasificacion)) {
    $clasificacion = json_decode(file_get_contents($archivoClasificacion), true);
} else {
    $clasificacion = [];
}

// Buscar si el jugador ya tiene una puntuación para la misma canción
$encontrado = false;
foreach ($clasificacion as &$entrada) {
    if ($entrada['jugador'] === $jugador && $entrada['cancion'] === $cancion) {
        // Si la nueva puntuación es mayor, actualizarla
        if ($puntuacion > $entrada['puntuacion']) {
            $entrada['puntuacion'] = $puntuacion;
        }
        $encontrado = true;
        break;
    }
}

// Si no se encontró una entrada previa, añadir una nueva
if (!$encontrado) {
    $clasificacion[] = [
        'jugador' => $jugador,
        'cancion' => $cancion,
        'puntuacion' => $puntuacion
    ];
}

// Guardar la clasificación actualizada
file_put_contents($archivoClasificacion, json_encode($clasificacion, JSON_PRETTY_PRINT));

echo json_encode(['success' => true]);
?>
