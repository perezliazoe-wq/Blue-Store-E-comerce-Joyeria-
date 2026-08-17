<?php

session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

include 'includes/carrito_json.php';

// Compatible con PHP 5.5: se reemplaza el operador ?? por isset()
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}

$session = session_id();

/* =========================
AGREGAR PRODUCTO
========================= */

if($action == "add"){

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];
$imagen = $_POST['imagen'];

agregarAlCarrito($session, $id, $nombre, $precio, $cantidad, $imagen);

header("Location: carrito.php");
exit;

}

/* =========================
ACTUALIZAR
========================= */

if($action == "update"){

$id = $_POST['id'];
$cantidad = $_POST['cantidad'];

actualizarCantidad($session, $id, $cantidad);

header("Location: carrito.php");
exit;

}

/* =========================
ELIMINAR
========================= */

if($action == "delete"){

$id = $_GET['id'];

eliminarDelCarrito($session, $id);

header("Location: carrito.php");
exit;

}