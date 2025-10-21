<?php
session_start();
require_once '../db/conexion.php';

// Verificar si es agricultor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'agricultor') {
    header("Location: ../usuarios/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $categoria = $_POST['categoria'];
    $stock = intval($_POST['stock']);
    $unidad = $_POST['unidad_medida'];
    $certificado = isset($_POST['certificacion_organica']) ? 1 : 0;
    $agricultor_id = $_SESSION['usuario_id'];
    $imagen_url = null;

    // Subida de imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaDestino = "../uploads/productos/" . $nombreArchivo;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen_url = "uploads/productos/" . $nombreArchivo;
        }
    }

    // Validación y guardado
    if ($nombre && $precio > 0) {
        $stmt = $pdo->prepare("INSERT INTO productos 
            (nombre, descripcion, precio, categoria, stock, unidad_medida, agricultor_id, imagen_url, certificacion_organica)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $categoria, $stock, $unidad, $agricultor_id, $imagen_url, $certificado]);
        $mensaje = "✅ Producto agregado correctamente.";
    } else {
        $mensaje = "❌ Debes llenar todos los campos requeridos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Producto</title>
<style>
body { font-family: Arial; max-width: 600px; margin: 40px auto; }
form { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
label { display: block; margin-top: 10px; font-weight: bold; }
input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
button { background: #1A2D7A; color: white; padding: 10px; border: none; border-radius: 5px; width: 100%; margin-top: 15px; cursor: pointer; }
</style>
</head>
<body>
<h1>Agregar Producto</h1>
<?php if (isset($mensaje)) echo "<p>$mensaje</p>"; ?>
<form method="POST" enctype="multipart/form-data">
    <label>Nombre</label>
    <input type="text" name="nombre" required>

    <label>Descripción</label>
    <textarea name="descripcion" rows="3"></textarea>

    <label>Precio (COP)</label>
    <input type="number" step="0.01" name="precio" required>

    <label>Categoría</label>
    <select name="categoria" required>
        <option value="">Seleccione...</option>
        <option value="frutas">Frutas</option>
        <option value="verduras">Verduras</option>
        <option value="procesados">Procesados</option>
    </select>

    <label>Stock disponible</label>
    <input type="number" name="stock" min="0" required>

    <label>Unidad de medida</label>
    <select name="unidad_medida">
        <option value="kg">Kilogramo</option>
        <option value="libra">Libra</option>
        <option value="unidad">Unidad</option>
    </select>

    <label>Imagen del producto</label>
    <input type="file" name="imagen" accept="image/*">

    <label><input type="checkbox" name="certificacion_organica"> Certificación orgánica</label>

    <button type="submit">Guardar Producto</button>
</form>

<p><a href="listar.php">Ver mis productos</a></p>
</body>
</html>
