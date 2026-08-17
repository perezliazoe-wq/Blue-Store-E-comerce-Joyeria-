<?php
// Iniciar sesión para obtener el session_id() del usuario
session_start();

// 1. Incluir la librería FPDF y el archivo del XML
require('fpdf/fpdf.php');
include 'includes/carrito_json.php';

// 2. Obtener los datos del carrito ANTES de crear el PDF para calcular la altura
$session = session_id();
$items = obtenerCarrito($session);
$total = 0;

// --- CÁLCULO DE ALTURA DINÁMICA DEL TICKET ---
// Base: 70mm (Logo y Títulos) + 35mm (Totales y Despedida) + 15mm (Márgenes) = 120mm base
// Por cada producto sumamos 8 milímetros.
$cantidad_productos = count($items);
$alto_dinamico = 120 + ($cantidad_productos * 8);

// 3. Crear nuestra propia clase heredando de FPDF
class PDF extends FPDF {
    
    function Header() {
        $this->SetMargins(5, 5, 5);
        $this->SetAutoPageBreak(true, 10);
        
        if(file_exists('img/logo.jpg')){
            $this->Image('img/logo.jpg', 35, 5, 30);
        }
        $this->Ln(32); 
        
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 6, 'BlueStore', 0, 1, 'C');
        
        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 5, 'Fecha: ' . date('d/m/Y'), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 8, 'TICKET DE COMPRA', 0, 1, 'C');
        $this->Ln(2);
        
        // Encabezados de tabla
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(10, 8, 'Cant.', 1, 0, 'C');
        $this->Cell(40, 8, 'Producto', 1, 0, 'C');
        $this->Cell(18, 8, 'Precio', 1, 0, 'C');
        $this->Cell(22, 8, 'Subtotal', 1, 1, 'C'); 
    }

    function Footer() {
        // Al usar SetY(-10), se ajusta automáticamente al fondo de nuestra altura dinámica
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(0, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

// 4. Creación del objeto PDF pasándole nuestro ALTO DINÁMICO EXACTO
$pdf = new PDF('P', 'mm', array(100, $alto_dinamico));
$pdf->AliasNbPages(); 
$pdf->AddPage();      
$pdf->SetFont('Arial', '', 8);

// 5. Imprimir los productos
if ($cantidad_productos > 0) {
    
    foreach ($items as $item) {
        $cantidad = (int)$item->cantidad;
        $nombre_completo = utf8_decode((string)$item->nombre);
        $nombre_corto = substr($nombre_completo, 0, 20); // Recorte por seguridad
        
        $precio = (float)$item->precio;
        $subtotal = $precio * $cantidad;
        $total += $subtotal;
        
        $pdf->Cell(10, 8, $cantidad, 1, 0, 'C');
        $pdf->Cell(40, 8, $nombre_corto, 1, 0, 'L');
        $pdf->Cell(18, 8, '$' . number_format($precio, 2), 1, 0, 'R');
        $pdf->Cell(22, 8, '$' . number_format($subtotal, 2), 1, 1, 'R');
    }
    
    // Imprimir el Total
    $pdf->Ln(2); 
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50); 
    $pdf->Cell(18, 8, 'TOTAL:', 0, 0, 'R');
    $pdf->Cell(22, 8, '$' . number_format($total, 2), 0, 1, 'R');
    
    // Despedida
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(0, 6, utf8_decode('¡Gracias por su preferencia!'), 0, 1, 'C');
    $pdf->Cell(0, 6, utf8_decode('BlueStore - Elegancia y estilo'), 0, 1, 'C');
    
    // --- VACIAR EL CARRITO EN EL JSON ---
    vaciarCarrito($session);
    
} else {
    $pdf->Cell(0, 10, utf8_decode('No hay productos en su ticket.'), 1, 1, 'C');
}

// 8. Salida del PDF
$pdf->Output('Ticket_BlueStore.pdf', 'I');
?>
