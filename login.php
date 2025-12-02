<?php
session_start();
require 'funciones.php';

$cosas = new UsuarioCosas();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $usuario = $cosas->login($email);

    if ($usuario && password_verify($password, $usuario['password'])) {

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];

        header("Location: user_index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html>
<body>

<h2>Iniciar Sesión</h2>

<?php if ($error): ?>
<p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    Email: <input type="email" name="email" required><br><br>
    Contraseña: <input type="password" name="password" required><br><br>
    <button type="submit">Entrar</button>
</form>

</body>
</html>
