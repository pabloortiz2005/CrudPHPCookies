<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit; }

require 'funciones.php';
$cosas = new UsuarioCosas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $avatarNombre = null;
    if (!empty($_FILES['avatar']['name'])) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatarNombre = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/" . $avatarNombre);
    }

    $usuario = new Usuario([
        'nombre' => $_POST['nombre'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'rol' => $_POST['rol'],
        'fecha_alta' => $_POST['fecha_alta'],
        'avatar' => $avatarNombre
    ]);

    $cosas->crear($usuario);
    header("Location: user_index.php"); exit;
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Crear usuario</h2>

<form method="POST" enctype="multipart/form-data">

Nombre: <input type="text" name="nombre" required><br><br>
Email: <input type="email" name="email" required><br><br>
Contraseña: <input type="password" name="password" required><br><br>
Rol: <input type="text" name="rol" required><br><br>
Fecha Alta: <input type="date" name="fecha_alta" required><br><br>
Avatar: <input type="file" name="avatar"><br><br>

<button type="submit">Crear</button>
</form>

</body>
</html>
