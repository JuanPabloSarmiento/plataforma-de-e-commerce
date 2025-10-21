<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'agricultor') {
    header("Location: login.php");
    exit;
}
echo "<h1>Bienvenido, agricultor {$_SESSION['usuario_nombre']} 🌱</h1>";
echo "<p><a href='../productos/agregar.php'>Agregar producto</a> | <a href='../usuarios/logout.php'>Cerrar sesión</a></p>";
?>
