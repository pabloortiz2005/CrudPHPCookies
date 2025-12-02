<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit; }

require 'funciones.php';

$cosas = new UsuarioCosas();
$id = $_GET['id'];
$usuario = $cosas->obtenerPorId($id);

if (!$usuario) { echo "Usuario no encontrado"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $avatar = $usuario['avatar'];

    if (!empty($_FILES['avatar']['name'])) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatar = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/" . $avatar);
    }

    $password = $usuario['password'];
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $userObj = new Usuario([
        'id' => $id,
        'nombre' => $_POST['nombre'],
        'email' => $_POST['email'],
        'password' => $password,
        'rol' => $_POST['rol'],
        'fecha_alta' => $_POST['fecha_alta'],
        'avatar' => $avatar
    ]);

    $cosas->actualizar($userObj);

    header("Location: user_index.php"); exit;
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Editar usuario</h2>

<form method="POST" enctype="multipart/form-data">

Nombre: <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>"><br><br>
Email: <input type="email" name="email" value="<?= $usuario['email'] ?>"><br><br>
Nueva contraseña (opcional): <input type="password" name="password"><br><br>
Rol: <input type="text" name="rol" value="<?= $usuario['rol'] ?>"><br><br>
Fecha Alta: <input type="date" name="fecha_alta" value="<?= $usuario['fecha_alta'] ?>"><br><br>
Avatar actual: <img src="uploads/<?= $usuario['avatar'] ?>" width="70"><br>
Nuevo avatar: <input type="file" name="avatar"><br><br>

<button type="submit">Guardar</button>

</form>

</body>
</html>
