<?php
// Archivo: usuarios/registro.php
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password_plain = $_POST['password'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($password_plain) || empty($tipo_usuario)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico no válido.";
    } elseif (strlen($password_plain) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Este correo ya está registrado.";
        } else {
            // Encriptar contraseña
            $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

            // Insertar usuario
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nombre, email, password, tipo_usuario, telefono, direccion)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            if ($stmt->execute([$nombre, $email, $password_hash, $tipo_usuario, $telefono, $direccion])) {
                $success = "✅ Usuario registrado correctamente. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar el usuario.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 40px auto; }
        form { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
        h1 { color: #1A2D7A; text-align: center; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #1A2D7A; color: white; padding: 10px; width: 100%; margin-top: 15px; border: none; border-radius: 5px; cursor: pointer; }
        .msg { margin-top: 10px; padding: 10px; border-radius: 5px; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <h1>Registro de Usuario</h1>

    <?php if (isset($error)): ?>
        <div class="msg error"><?= $error ?></div>
    <?php elseif (isset($success)): ?>
        <div class="msg success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre completo</label>
        <input type="text" name="nombre" required>

        <label>Correo electrónico</label>
        <input type="email" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <label>Tipo de usuario</label>
        <select name="tipo_usuario" required>
            <option value="">Selecciona una opción</option>
            <option value="agricultor">Agricultor (Vendedor)</option>
            <option value="cliente">Cliente (Comprador)</option>
        </select>

        <label>Teléfono</label>
        <input type="text" name="telefono">

        <label>Dirección</label>
        <textarea name="direccion" rows="2"></textarea>

        <button type="submit">Registrarme</button>
    </form>
</body>
</html>
