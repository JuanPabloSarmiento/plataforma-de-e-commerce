<?php
// Archivo: usuarios/login.php
session_start();
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password_plain = $_POST['password'];

    if (empty($email) || empty($password_plain)) {
        $error = "Debes ingresar tu correo y contraseña.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password_plain, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            if ($usuario['tipo_usuario'] === 'agricultor') {
                header("Location: ../usuarios/dashboard_agricultor.php");
            } else {
                header("Location: ../usuarios/dashboard_cliente.php");
            }
            exit;
        } else {
            $error = "Credenciales incorrectas.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        form { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
        h1 { color: #1A2D7A; text-align: center; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #1A2D7A; color: white; padding: 10px; width: 100%; margin-top: 15px; border: none; border-radius: 5px; cursor: pointer; }
        .msg { margin-top: 10px; padding: 10px; border-radius: 5px; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <?php if (isset($error)): ?>
        <div class="msg error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Correo electrónico</label>
        <input type="email" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
    </p>
</body>
</html>
