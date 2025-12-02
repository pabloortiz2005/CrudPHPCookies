<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit; }

require 'funciones.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $cosas = new UsuarioCosas();
    $cosas->eliminar($id);
}

header("Location: user_index.php");
exit;
