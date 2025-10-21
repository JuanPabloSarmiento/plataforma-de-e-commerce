<?php
session_start();
require_once '../db/conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'agricultor') {
    header("Location: ../usuarios/login.php");
    exit;
}

$agricultor_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT * FROM productos WHERE agricultor_id = ? ORDER BY id DESC");
$stmt->execute([$agricultor_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Productos</title>
<style>
body { font-family: Arial; max-width: 800px; margin: 40px auto; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
img { width: 80px; border-radius: 5px; }
a { color: #1A2D7A; text-decoration: none; font-weight: bold; }
</style>
</head>
<body>
<h1>Mis Productos</h1>
<p><a href="agregar.php">+ Agregar nuevo producto</a></p>
<table>
<tr>
    <th>Imagen</th>
    <th>Nombre</th>
    <th>Precio</th>
    <th>Stock</th>
    <th>Acciones</th>
</tr>
<?php foreach ($productos as $p): ?>
<tr>
    <td><?php if ($p['imagen_url']) echo "<img src='../{$p['imagen_url']}' alt='img'>"; ?></td>
    <td><?= htmlspecialchars($p['nombre']) ?></td>
    <td>$<?= number_format($p['precio'], 2) ?></td>
    <td><?= $p['stock'] ?></td>
    <td>
        <a href="editar.php?id=<?= $p['id'] ?>">Editar</a> |
        <a href="eliminar.php?id=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
