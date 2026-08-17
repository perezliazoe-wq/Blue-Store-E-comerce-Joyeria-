<?php
include 'includes/header.php';
include 'includes/db.php';

// Verificar si el usuario está logueado
$usuarioLogueado = isset($_SESSION['user']);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = $conn->query("SELECT * FROM productos WHERE id = $id");

$producto = $result->fetch_assoc();
?>

<div class="producto-container">

<?php if($producto): ?>

<h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>

<img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="producto-img">

<p class="precio">$<?php echo number_format($producto['precio'],2); ?></p>

<p class="descripcion">
<?php echo htmlspecialchars($producto['descripcion']); ?>
</p>

<?php if($usuarioLogueado): ?>
<form action="acciones_carrito.php" method="POST" class="form-carrito">

<input type="hidden" name="action" value="add">

<input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

<input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">

<input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">

<input type="hidden" name="imagen" value="<?php echo htmlspecialchars($producto['imagen']); ?>">

<label>Cantidad:</label>

<input type="number" name="cantidad" value="1" min="1" class="cantidad-input">

<br><br>

<button type="submit" class="btn">Agregar al carrito</button>

</form>
<?php else: ?>
<div style="margin-top: 20px; padding: 15px; background-color: #f0f0f0; border-radius: 5px; text-align: center;">
    <p style="margin: 0 0 10px 0;"><strong>Para agregar este producto al carrito, debes iniciar sesión.</strong></p>
    <a href="login.php" class="btn">Iniciar Sesión</a>
    <p style="margin: 10px 0 0 0; font-size: 14px;">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
</div>
<?php endif; ?>

<?php else: ?>

<p>Producto no encontrado.</p>

<?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>