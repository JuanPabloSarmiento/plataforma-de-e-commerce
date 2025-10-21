<?php
session_start();
require_once '../db/conexion.php';

// Verificar si es agricultor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'agricultor') {
    header("Location: ../usuarios/login.php");
    exit;
}

$agricultor_id = $_SESSION['usuario_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener producto existente
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? AND agricultor_id = ?");
$stmt->execute([$id, $agricultor_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die("Producto no encontrado o no autorizado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = $_POST['categoria'];
    $stock = intval($_POST['stock']);
    $unidad = $_POST['unidad_medida'];
    $certificado = isset($_POST['certificacion_organica']) ? 1 : 0;
    $imagen_url = $producto['imagen_url'];

    // Si se sube nueva imagen, reemplazar
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaDestino = "../uploads/productos/" . $nombreArchivo;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen_url = "uploads/productos/" . $nombreArchivo;
        }
    }

    $stmt = $pdo->prepare("UPDATE productos 
        SET nombre=?, descripcion=?, precio=?, categoria=?, stock=?, unidad_medida=?, imagen_url=?, certificacion_organica=? 
        WHERE id=? AND agricultor_id=?");
    $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $unidad, $imagen_url, $certificado, $id, $agricultor_id]);

    $mensaje = "✅ Producto actualizado correctamente.";
    // Refrescar datos
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? AND agricultor_id = ?");
    $stmt->execute([$id, $agricultor_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Producto</title>
<style>
body { font-family: Arial; max-width: 600px; margin: 40px auto; }
form { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
label { display: block; margin-top: 10px; font-weight: bold; }
input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
button { background: #1A2D7A; color: white; padding: 10px; border: none; border-radius: 5px; width: 100%; margin-top: 15px; cursor: pointer; }
img { width: 100px; margin-top: 10px; }
</style>
</head>
<body>
<h1>Editar Producto</h1>
<?php if (isset($mensaje)) echo "<p>$mensaje</p>"; ?>
<form method="POST" enctype="multipart/form-data">
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

    <label>Descripción</label>
    <textarea name="descripcion" rows="3"><?= htmlspecialchars($producto['descripcion']) ?></textarea>

    <label>Precio (COP)</label>
    <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>

    <label>Categoría</label>
    <select name="categoria" required>
        <option value="frutas" <?= $producto['categoria']=='frutas'?'selected':'' ?>>Frutas</option>
        <option value="verduras" <?= $producto['categoria']=='verduras'?'selected':'' ?>>Verduras</option>
        <option value="procesados" <?= $producto['categoria']=='procesados'?'selected':'' ?>>Procesados</option>
    </select>

    <label>Stock</label>
    <input type="number" name="stock" min="0" value="<?= $producto['stock'] ?>" required>

    <label>Unidad de medida</label>
    <select name="unidad_medida">
        <option value="kg" <?= $producto['unidad_medida']=='kg'?'selected':'' ?>>Kilogramo</option>
        <option value="libra" <?= $producto['unidad_medida']=='libra'?'selected':'' ?>>Libra</option>
        <option value="unidad" <?= $producto['unidad_medida']=='unidad'?'selected':'' ?>>Unidad</option>
<?php
session_start();
require_once '../db/conexion.php';

// Verificar si es agricultor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'agricultor') {
    header("Location: ../usuarios/login.php");
    exit;
}

$agricultor_id = $_SESSION['usuario_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener producto existente
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? AND agricultor_id = ?");
$stmt->execute([$id, $agricultor_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die("Producto no encontrado o no autorizado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = $_POST['categoria'];
    $stock = intval($_POST['stock']);
    $unidad = $_POST['unidad_medida'];
    $certificado = isset($_POST['certificacion_organica']) ? 1 : 0;
    $imagen_url = $producto['imagen_url'];

    // Si se sube nueva imagen, reemplazar
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaDestino = "../uploads/productos/" . $nombreArchivo;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen_url = "uploads/productos/" . $nombreArchivo;
        }
    }

    $stmt = $pdo->prepare("UPDATE productos 
        SET nombre=?, descripcion=?, precio=?, categoria=?, stock=?, unidad_medida=?, imagen_url=?, certificacion_organica=? 
        WHERE id=? AND agricultor_id=?");
    $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $unidad, $imagen_url, $certificado, $id, $agricultor_id]);

    $mensaje = "✅ Producto actualizado correctamente.";
    // Refrescar datos
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ? AND agricultor_id = ?");
    $stmt->execute([$id, $agricultor_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Producto</title>
<style>
body { font-family: Arial; max-width: 600px; margin: 40px auto; }
form { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
label { display: block; margin-top: 10px; font-weight: bold; }
input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
button { background: #1A2D7A; color: white; padding: 10px; border: none; border-radius: 5px; width: 100%; margin-top: 15px; cursor: pointer; }
img { width: 100px; margin-top: 10px; }
</style>
</head>
<body>
<h1>Editar Producto</h1>
<?php if (isset($mensaje)) echo "<p>$mensaje</p>"; ?>
<form method="POST" enctype="multipart/form-data">
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

    <label>Descripción</label>
    <textarea name="descripcion" rows="3"><?= htmlspecialchars($producto['descripcion']) ?></textarea>

    <label>Precio (COP)</label>
    <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>

    <label>Categoría</label>
    <select name="categoria" required>
        <option value="frutas" <?= $producto['categoria']=='frutas'?'selected':'' ?>>Frutas</option>
        <option value="verduras" <?= $producto['categoria']=='verduras'?'selected':'' ?>>Verduras</option>
        <option value="procesados" <?= $producto['categoria']=='procesados'?'selected':'' ?>>Procesados</option>
    </select>

    <label>Stock</label>
    <input type="number" name="stock" min="0" value="<?= $producto['stock'] ?>" required>

    <label>Unidad de medida</label>
    <select name="unidad_medida">
        <option value="kg" <?= $producto['unidad_medida']=='kg'?'selected':'' ?>>Kilogramo</option>
        <option value="libra" <?= $producto['unidad_medida']=='libra'?'selected':'' ?>>Libra</option>
        <option value="unidad" <?= $producto['unidad_medida']=='unidad'?'selected':'' ?>>Unidad</option>
