<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit; }

require 'funciones.php';

$cosas = new UsuarioCosas();
$id = $_GET['id'];
$usuario = $cosas->obtenerPorId($id);

if (!$usuario) { echo "Usuario no encontrado"; exit; }

?>
<!DOCTYPE html>
<html>
<body>

<h2>Información del usuario</h2>

<p><strong>ID:</strong> <?= $usuario['id'] ?></p>
<p><strong>Nombre:</strong> <?= $usuario['nombre'] ?></p>
<p><strong>Email:</strong> <?= $usuario['email'] ?></p>
<p><strong>Rol:</strong> <?= $usuario['rol'] ?></p>
<p><strong>Fecha Alta:</strong> <?= $usuario['fecha_alta'] ?></p>
<p><img src="uploads/<?= $usuario['avatar'] ?>" width="120"></p>

<a href="user_index.php">Volver</a>

</body>
</html>
