<?php
session_start();

include 'contador.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); 
    exit;
}

require 'funciones.php';

$cosas = new UsuarioCosas();
$usuarios = $cosas->obtenerTodos();
?>

<!DOCTYPE html>
<html>
<body>

<p>Hola, <?= $_SESSION['usuario_nombre'] ?> | <a href="logout.php">Salir</a></p>

<!-- Mostrar visitas aquí -->
<p><strong>Visitas totales:</strong> <?= $contador_global ?></p>
<p><strong>Tus visitas desde este navegador:</strong> <?= $user_visits ?></p>

<h2>Listado de usuarios</h2>

<table border="1">
<tr>
    <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Avatar</th><th>Acciones</th>
</tr>

<?php foreach ($usuarios as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= $u['nombre'] ?></td>
    <td><?= $u['email'] ?></td>
    <td><?= $u['rol'] ?></td>
    <td><img src="uploads/<?= $u['avatar'] ?>" width="50"></td>
    <td>
        <a href="user_info.php?id=<?= $u['id'] ?>">Ver</a>
        <a href="user_edit.php?id=<?= $u['id'] ?>">Editar</a>
        <a href="user_delete.php?id=<?= $u['id'] ?>">Eliminar</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<br><br>
<a href="user_create.php">Crear usuario</a>

</body>
</html>
