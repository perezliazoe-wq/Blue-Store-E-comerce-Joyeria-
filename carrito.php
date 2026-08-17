<?php 
include 'includes/header.php';

// Verificar si el usuario está logueado
if(!isset($_SESSION['user'])){
    echo "<p style='text-align:center; margin-top:20px'>Debes iniciar sesión para ver tu carrito.</p>";
    echo "<p style='text-align:center'><a href='login.php' class='btn'>Ir a Login</a></p>";
    include 'includes/footer.php';
    exit;
}

include 'includes/carrito_json.php';

$session = session_id();
$items = obtenerCarrito($session);

$total = 0;

echo "<h2 style='text-align:center'>Tu Carrito de Compras</h2>";

if(count($items) > 0){

echo "<div class='table-wrapper'>";
echo "<table>";

echo "<tr>
<th>Imagen</th>
<th>Producto</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Subtotal</th>
<th>Acción</th>
</tr>";

foreach($items as $item){

$precio = (float)$item->precio;
$cantidad = (int)$item->cantidad;
$subtotal = $precio * $cantidad;

$total += $subtotal;

echo "<tr>";

echo "<td><img src='img/".$item->imagen."' width='60'></td>";

echo "<td>".$item->nombre."</td>";

echo "<td>$".number_format($precio,2)."</td>";

echo "<td>
<form action='acciones_carrito.php' method='POST' style='margin:0'>
<input type='hidden' name='action' value='update'>
<input type='hidden' name='id' value='".htmlspecialchars($item->id)."'>
<input type='number' name='cantidad' value='".$cantidad."' min='1' style='width:60px'>
<button type='submit' class='btn'>Actualizar</button>
</form>
</td>";

echo "<td>$".number_format($subtotal,2)."</td>";

echo "<td>
<a class='btn' href='acciones_carrito.php?action=delete&id=".htmlspecialchars($item->id)."'>
Eliminar
</a>
</td>";

echo "</tr>";

}

echo "</table>";
echo "</div>";

echo "<h3 style='text-align:center;margin-top:20px'>
Total: $".number_format($total,2)."
</h3>";

echo "<div style='text-align:center;margin-top:15px'>
<a href='data/cart.json' target='_blank' style='color: #666; font-size: 14px; text-decoration: none;'>Ver JSON del carrito</a>
</div>";

/* ============ BOTONES DE PAGO ============ */

echo "<div style='text-align:center;margin-top:30px'>";
echo "<p style='font-size:1.2em; color:#164370; margin:20px 0; font-weight:bold;'>Total a Pagar: $".number_format($total,2)."</p>";

// Botones de pago
echo "<div style='margin-top:30px; max-width:500px; margin-left:auto; margin-right:auto;'>";

echo "<a href='metodo_pago.php' class='btn' style='display:block;padding:14px;text-align:center;font-size:1.1em;margin-bottom:15px;background:#164370;'>Proceder al Pago</a>";

echo "<a href='galeria.php' style='display:block;padding:12px;text-align:center;color:#666;text-decoration:none;margin-top:15px;'>Continuar comprando</a>";

echo "</div>";
echo "</div>";

}
else{

echo "<p style='text-align:center'>Tu carrito está vacío.</p>";

}

?>

<?php
include 'includes/footer.php';
?>
