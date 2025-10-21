<?php
session_start();
require_once '../db/conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'agricultor') {
    header("Location: ../usuarios/login.php");
    exit;
}

$agricultor_id = $_SESSION['usuario_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Verificar propiedad
    $stmt = $pdo->prepare("SELECT imagen_url FROM productos WHERE id=? AND agricultor_id=?");
    $stmt->execute([$id, $agricultor_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        // Eliminar imagen si existe
        if ($producto['imagen_url'] && file_exists("../" . $producto['imagen_url'])) {
            unlink("../" . $producto['imagen_url']);
        }

        // Eliminar registro
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id=? AND agricultor_id=?");
        $stmt->execute([$id, $agricultor_id]);
    }
}

header("Location: listar.php");
exit;
?>
