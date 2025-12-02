<?php

$archivo = __DIR__ . '/visitas.txt';
$cookie_name = 'visita_contada';
$periodo = 24 * 60 * 60; // 24 horas

// --- Asegurar que el archivo existe ---
if (!file_exists($archivo)) {
    file_put_contents($archivo, "0");
}

// --- Leer contador ---
$visitas = (int) file_get_contents($archivo);

// --- Ver si ya contamos la visita ---
if (empty($_COOKIE[$cookie_name])) {

    // Incrementar visitas
    $visitas++;
    file_put_contents($archivo, $visitas);

    // Crear cookie por 24h
    setcookie($cookie_name, '1', time() + $periodo, '/');
    $mensaje = "Tu visita fue contada.";
} else {
    $mensaje = "Tu visita ya se contó en las últimas 24 horas.";
}

?>
<div style="font-family:Arial; font-size:14px;">
    <p><strong>Visitas totales:</strong> <?= $visitas ?></p>
    <p><?= $mensaje ?></p>
</div>
