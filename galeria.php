<?php
include 'includes/header.php';
include 'includes/db.php';

$productos = $conn->query("SELECT * FROM productos");
?>

<h2>Nuestra Colección</h2>

<div class="galeria">

<?php while($row = $productos->fetch_assoc()): ?>

    <div class="producto">

        <a href="producto.php?id=<?php echo $row['id']; ?>">
            <img src="img/<?php echo $row['imagen']; ?>">
        </a>

        <h3><?php echo ucfirst(strtolower($row['nombre'])); ?></h3>

        <p><?php echo htmlspecialchars(rtrim($row['descripcion'], '.')).'.'; ?></p>

        <div style="margin-top: 10px;">
            <a href="producto.php?id=<?php echo $row['id']; ?>" class="btn">Ver</a>
        </div>

    </div>

<?php endwhile; ?>

</div>

<?php include 'includes/footer.php'; ?>