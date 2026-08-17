<?php

include 'includes/header.php';
include 'includes/db.php';
include 'includes/carrito_json.php';
require('fpdf/fpdf.php');

/* =========================
VERIFICAR LOGIN
========================= */

if(!isset($_SESSION['user'])){
    $_SESSION['redirect_after_login'] = "pagar.php";
    header("Location: login.php?msg=login_required");
    exit();
}

/* =========================
OBTENER CARRITO DESDE XML
========================= */

$session = session_id();
$items = obtenerCarrito($session);
$usuario = $_SESSION['user'];

/* =========================
CALCULAR TOTAL
========================= */

$total = 0;
foreach($items as $item){
    $total += (float)$item->precio * (int)$item->cantidad;
}

/* =========================
ACTUALIZAR STOCK EN MYSQL
========================= */

foreach($items as $item){
    $id = (int)$item->id;
    $cantidad = (int)$item->cantidad;
    $conn->query("UPDATE productos SET cantidad = cantidad - $cantidad WHERE id = $id AND cantidad >= $cantidad");
}

/* =========================
VACIAR CARRITO XML
========================= */

vaciarCarrito($session);

/* =========================
GENERAR COMPROBANTE PDF
========================= */

class PDF extends FPDF {
    function Header() {
        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(true, 15);
        
        // Logo
        if(file_exists('img/logo.jpg')){
            $this->Image('img/logo.jpg', 10, 5, 25);
        }
        
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 8, 'BlueStore', 0, 1, 'C');
        
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Joyería Marina - Elegancia y Estilo', 0, 1, 'C');
        $this->Cell(0, 5, 'Comprobante de Pago', 0, 1, 'C');
        
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Gracias por tu preferencia | Página ').$this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

// Información del comprobante
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Fecha: ' . date('d/m/Y H:i'), 0, 1);
$pdf->Cell(0, 6, 'Usuario: ' . utf8_decode($usuario), 0, 1);
$pdf->Ln(5);

// Tabla de productos
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(12, 31, 54);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 8, 'Producto', 1, 0, 'L', true);
$pdf->Cell(30, 8, 'Precio', 1, 0, 'R', true);
$pdf->Cell(30, 8, 'Cantidad', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Subtotal', 1, 1, 'R', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

foreach($items as $item){
    $nombre = utf8_decode(substr((string)$item->nombre, 0, 30));
    $precio = (float)$item->precio;
    $cantidad = (int)$item->cantidad;
    $subtotal = $precio * $cantidad;
    
    $pdf->Cell(70, 7, $nombre, 1, 0, 'L');
    $pdf->Cell(30, 7, '$' . number_format($precio, 2), 1, 0, 'R');
    $pdf->Cell(30, 7, $cantidad, 1, 0, 'C');
    $pdf->Cell(40, 7, '$' . number_format($subtotal, 2), 1, 1, 'R');
}

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100);
$pdf->Cell(40, 8, 'TOTAL:', 0, 0, 'R');
$pdf->Cell(40, 8, '$' . number_format($total, 2), 0, 1, 'R');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 9);
$pdf->MultiCell(0, 5, utf8_decode('Método de Pago: PayPal (Sandbox)' . "\n\n" . '¡Gracias por su compra en BlueStore!'));

// Generar PDF
$pdf->Output('Comprobante_BlueStore_' . date('YmdHis') . '.pdf', 'D');

/* =========================
MENSAJE DE CONFIRMACIÓN
========================= */

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pago Completado</title>
<style>
.mensaje-exito {
    max-width: 600px;
    margin: 40px auto;
    padding: 30px;
    background: linear-gradient(135deg, #a4bdd7 0%, #37557c 100%);
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.mensaje-exito h2 {
    color: white;
    font-size: 2em;
    margin: 0 0 15px 0;
}

.mensaje-exito p {
    color: white;
    font-size: 1.1em;
    margin: 10px 0;
}

.emoji {
    font-size: 3em;
    margin: 20px 0;
}

.botones {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.botones a {
    display: inline-block;
    padding: 12px 25px;
    background: white;
    color: #164370;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: all 0.3s;
}

.botones a:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

@media (max-width: 767px) {
    .mensaje-exito {
        margin: 20px 10px;
        padding: 20px;
    }
    
    .mensaje-exito h2 {
        font-size: 1.5em;
    }
    
    .botones {
        flex-direction: column;
    }
    
    .botones a {
        width: 100%;
        text-align: center;
    }
}
</style>
</head>
<body>

<div class="mensaje-exito">
    <div class="emoji">✅</div>
    <h2>¡Pago Realizado Correctamente!</h2>
    <p>Tu compra ha sido procesada exitosamente.</p>
    <p>Se ha generado un archivo PDF con el comprobante.</p>
    <p><strong>Total pagado: $<?php echo number_format($total, 2); ?></strong></p>
    <p style="font-size: 0.9em; margin-top: 20px;">Los productos han sido descontados del inventario.</p>
    
    <div class="botones">
        <a href="index.php">🏠 Ir al Inicio</a>
        <a href="galeria.php">🛍️ Continuar Comprando</a>
        <a href="carrito.php">🛒 Ver Carrito</a>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

</body>
</html>
