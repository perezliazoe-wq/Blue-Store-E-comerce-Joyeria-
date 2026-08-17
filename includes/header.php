<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>BlueStore - Joyería Marina</title>

<link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="header">
    
    <div class="logo-area">
        <img src="img/logo.jpg" alt="BlueStore Logo" class="logo">
        <h1 class="titulo">BlueStore</h1>
    </div>

    <nav class="menu">
        <a href="index.php">Inicio</a>
        <a href="galeria.php">Galería</a>
        <a href="carrito.php">Carrito</a>

        <?php if(isset($_SESSION['user'])): ?>
            <a href="logout.php">Salir (<?php echo $_SESSION['user']; ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>

</header>

<div class="contenido">