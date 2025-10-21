<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: login.php");
    exit;
}
echo "<h1>Bienvenido, cliente {$_SESSION['usuario_nombre']} 🛒</h1>";
echo "<p><a href='../productos/buscar.php'>Ver productos</a> | <a href='../usuarios/logout.php'>Cerrar sesión</a></p>";
?>
