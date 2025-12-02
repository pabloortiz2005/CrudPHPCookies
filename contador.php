<?php

$archivo_contador = __DIR__ . '/visitas.txt';   // archivo para almacenar el contador global
$cookie_global_name = 'contador_visitas';       // cookie que evita contar varias veces en el periodo
$cookie_user_name   = 'visitas_usuario';        // cookie que almacena visitas de este navegador
$periodo_segundos   = 24 * 60 * 60;             // ventana para la cookie global (aquí 24 horas)


/**
 * Lee el contador global desde archivo (si no existe, lo crea en 0).
 */
function leerContadorGlobal($archivo) {
    if (!file_exists($archivo)) {
        // crear con 0
        file_put_contents($archivo, "0", LOCK_EX);
        return 0;
    }
    $val = file_get_contents($archivo);
    return (int) trim($val);
}

/**
 * Incrementa de forma segura el contador global en +1 (usa flock).
 */
function incrementarContadorGlobal($archivo) {
    // abrir para lectura/escritura, crear si no existe
    $f = fopen($archivo, 'c+'); // c+ abre para lectura/escritura y crea si hace falta
    if (!$f) return false;

    // bloquear para evitar race conditions
    if (flock($f, LOCK_EX)) {
        // leer contenido actual
        rewind($f);
        $data = stream_get_contents($f);
        $count = (int) trim($data);
        $count++;
        // volver al inicio y truncar
        rewind($f);
        ftruncate($f, 0);
        fwrite($f, (string)$count);
        fflush($f);
        flock($f, LOCK_UN);
        fclose($f);
        return $count;
    } else {
        fclose($f);
        return false;
    }
}

// --- LÓGICA DE COOKIES ---
// Las cookies se envían en la cabecera

$contador_global = leerContadorGlobal($archivo_contador);
$contado_ahora = false;

// Si no existe la cookie global o ha caducado: incrementamos el contador global una vez y establecemos cookie.
// Esto evita contar la misma visita del mismo navegador varias veces dentro del periodo.
if (empty($_COOKIE[$cookie_global_name])) {
    $nuevo_valor = incrementarContadorGlobal($archivo_contador);
    if ($nuevo_valor !== false) {
        $contador_global = $nuevo_valor;
        // establecer cookie para bloquear conteo durante $periodo_segundos
        // usar HttpOnly y SameSite para más seguridad; Secure si trabajas por HTTPS
        setcookie($cookie_global_name, '1', [
            'expires' => time() + $periodo_segundos,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax' 
            
        ]);
        $contado_ahora = true;
    }
}

// Cookie que almacena visitas de este navegador (persistente)
$user_visits = isset($_COOKIE[$cookie_user_name]) ? (int) $_COOKIE[$cookie_user_name] : 0;
$user_visits++;
// Guardamos una cookie mucho tiempo
setcookie($cookie_user_name, (string)$user_visits, [
    'expires' => time() + (10 * 365 * 24 * 60 * 60),
    'path' => '/',
    'httponly' => true,
    'samesite'
]);

// Si necesitas el valor actualizado del global (si lo acabamos de incrementar):
$contador_global = leerContadorGlobal($archivo_contador);

// --- RESULTADO DISPONIBLE COMO VARIABLES ---
/*
 $contador_global  -> total de visitas únicas (según cookie global)
 $user_visits       -> número de visitas desde este navegador (cookie propia)
 $contado_ahora     -> true si se incrementó el contador global en esta petición
*/

?>
<div style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <p><strong>Visitas totales (únicas por navegador cada 24h):</strong> <?= htmlspecialchars($contador_global) ?></p>
    <p><strong>Tus visitas desde este navegador:</strong> <?= htmlspecialchars($user_visits) ?></p>
    <?php if ($contado_ahora): ?>
        <p style="color:green">Tu visita ha sido contabilizada (cookie aplicada por 24h).</p>
    <?php else: ?>
        <p style="color:gray">Ya contaste durante el periodo de 24 horas.</p>
    <?php endif; ?>
</div>
