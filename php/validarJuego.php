<?php
function validarJuego($juegoContenido) {
    $juegoLineas = explode("\n", trim($juegoContenido));

    // Validar que la primera línea sea el número de elementos
    if (!is_numeric($juegoLineas[0])) {
        return "Error: La primera línea debe ser el número de elementos.";
    }

    $numElementos = (int)$juegoLineas[0];

    // Validar cada línea después de la primera
    for ($i = 1; $i <= $numElementos; $i++) {
        if (!isset($juegoLineas[$i])) {
            return "Error: Faltan líneas en el archivo de juego.";
        }

        $partes = explode("#", $juegoLineas[$i]);
        if (count($partes) !== 3) {
            return "Error: Cada línea debe tener el formato 'codigo # instante_aparicion # instante_desaparicion'.";
        }

        // Validar el código de tecla (número entero)
        $codigoTecla = trim($partes[0]);
        if (!ctype_digit($codigoTecla)) {
            return "Error: El código de tecla debe ser un número entero.";
        }

        // Validar los instantes (números positivos)
        $instanteAparicion = (float)trim($partes[1]);
        $instanteDesaparicion = (float)trim($partes[2]);

        if ($instanteAparicion < 0 || $instanteDesaparicion < 0 || $instanteAparicion >= $instanteDesaparicion) {
            return "Error: Los instantes deben ser positivos y 'instante_aparicion' debe ser menor que 'instante_desaparicion'.";
        }
    }

    // Si todo está bien, devolver null (sin errores)
    return null;
}
?>
