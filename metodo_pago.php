<?php 
include 'includes/header.php';

// Verificar si el usuario está logueado
if(!isset($_SESSION['user'])){
    echo "<p style='text-align:center; margin-top:20px'>Debes iniciar sesión para proceder al pago.</p>";
    echo "<p style='text-align:center'><a href='login.php' class='btn'>Ir a Login</a></p>";
    include 'includes/footer.php';
    exit;
}

include 'includes/carrito_json.php';

$session = session_id();
$items = obtenerCarrito($session);

$total = 0;
foreach($items as $item){
    $total += (float)$item->precio * (int)$item->cantidad;
}

// Si el carrito está vacío
if(count($items) == 0){
    echo "<p style='text-align:center;margin-top:40px;'>Tu carrito está vacío.</p>";
    echo "<p style='text-align:center'><a href='galeria.php' class='btn'>Volver a la Galería</a></p>";
    include 'includes/footer.php';
    exit;
}

?>

<div style="max-width:700px; margin:40px auto; padding:0 20px;">
    <h2 style='text-align:center;color:#164370;margin-bottom:30px;'>Selecciona tu Método de Pago</h2>
    
    <!-- RESUMEN DEL CARRITO -->
    <div style="background:#a4bdd7; padding:20px; border-radius:10px; margin-bottom:30px;">
        <h3 style='color:white;margin-top:0;'>Resumen del Pedido</h3>
        <div style="max-height:200px;overflow-y:auto;">
            <?php foreach($items as $item): ?>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.3);color:white;">
                    <span><?php echo htmlspecialchars($item->nombre); ?> x<?php echo (int)$item->cantidad; ?></span>
                    <span>$<?php echo number_format((float)$item->precio * (int)$item->cantidad, 2); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="border-top:2px solid white;padding-top:15px;margin-top:15px;font-size:1.3em;font-weight:bold;color:white;display:flex;justify-content:space-between;">
            <span>TOTAL:</span>
            <span>$<?php echo number_format($total, 2); ?></span>
        </div>
    </div>
    
    <!-- OPCIONES DE PAGO -->
    <div>
        <!-- OPCIÓN 1: PAYPAL -->
        <div style="background:#FFC439;padding:20px;border-radius:10px;margin-bottom:20px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
            <h3 style='color:#111;margin-top:0;display:flex;align-items:center;gap:10px;'>
                💳 PayPal (Recomendado)
            </h3>
            <p style='color:#333;margin:10px 0;'>Método seguro y confiable con tu cuenta PayPal</p>
            
            <form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post'>
                <input type='hidden' name='cmd' value='_xclick'>
                <input type='hidden' name='business' value='U9Q9GSWP493BG'>
                <input type='hidden' name='item_name' value='Compra BlueStore - Joyería Marina'>
                <input type='hidden' name='amount' value='<?php echo number_format($total, 2); ?>'>
                <input type='hidden' name='currency_code' value='USD'>
                <input type='hidden' name='return' value='<?php echo ((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/bluestore/pagar.php'); ?>'>
                <input type='hidden' name='cancel_return' value='<?php echo ((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/bluestore/carrito.php'); ?>'>
                
                <button type='submit' style='width:100%;padding:14px;background:#0070ba;color:white;border:none;border-radius:5px;font-weight:bold;font-size:1.1em;cursor:pointer;'>
                    Pagar $<?php echo number_format($total, 2); ?> con PayPal
                </button>
            </form>
        </div>
        
        <!-- OPCIÓN 2: GENERAR TICKET -->
        <div style="background:#164370;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
            <h3 style='color:white;margin-top:0;display:flex;align-items:center;gap:10px;'>
                📄 Generar Ticket PDF
            </h3>
            <p style='color:#a4bdd7;margin:10px 0;'>Genera un comprobante en PDF sin procesar pago online</p>
            
            <a href='generar_reporte.php' target='_blank' onclick="setTimeout(function(){ window.location.href='carrito.php'; }, 1500);" style='display:block;padding:14px;background:#376395;color:white;text-decoration:none;border-radius:5px;font-weight:bold;font-size:1.1em;text-align:center;transition:all 0.3s;'>
                Descargar Ticket de $<?php echo number_format($total, 2); ?>
            </a>
        </div>
    </div>
    
    <!-- INFORMACIÓN DE SEGURIDAD -->
    <div style='background:#f0f0f0;padding:15px;border-radius:10px;margin-top:30px;font-size:0.9em;'>
        <p style='margin:0;color:#666;'><strong>🔒 Seguridad:</strong> Usamos PayPal Sandbox para pruebas. Tus datos están seguros.</p>
    </div>
    
    <!-- VOLVER -->
    <div style='text-align:center;margin-top:20px;'>
        <a href='carrito.php' style='color:#666;text-decoration:none;'>← Volver al carrito</a>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
