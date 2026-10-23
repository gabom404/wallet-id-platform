<?php

# Incluyendo librerias necesarias #
require_once '../../conexion.php';
/* require_once 'fpdf/fpdf.php'; */
require_once 'fpdf/code128.php';

$pdf = new PDF_Code128('P', 'mm', array(80, 258));
$pdf->SetMargins(4, 10, 4);
$pdf->AddPage();
$pdf->SetTitle('Ticket Venta');

$id = $_GET['v'];
$factotal = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = safe_query($conexion, 'SELECT * FROM users WHERE id = ?', [$idcliente]);
$datosC = mysqli_fetch_assoc($clientes);
$ventas = safe_query($conexion, 'SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = ?', [$id]);

$ventas1 = safe_query($conexion, 'SELECT * FROM detalle_venta WHERE id_venta = ?', [$id]);
$resultado = mysqli_fetch_assoc($ventas1);
$precio = $resultado['precio'];
$prt = $resultado['cantidad'];
$cantidad = $resultado['cantidad'];
$barcode = $resultado['barcode'];
$query = safe_query($conexion, 'SELECT * FROM users WHERE id = ?', [$idcliente]);
$resultado = mysqli_fetch_assoc($query);
$cantidad_actual = $resultado['funds'];
$totalpagar = safe_query($conexion, 'SELECT SUM(precio * cantidad) AS totalpagar FROM detalle_venta WHERE id_venta = ?', [$id]);

# Cajero #
$qcashier = safe_query($conexion, 'SELECT * FROM ventas WHERE id_cliente = ?  AND idv = ?', [$idcliente, $id]);
$resultadoc = mysqli_fetch_assoc($qcashier);
$idcashier = $resultadoc['id_usuario'];
$q2cashier = safe_query($conexion, 'SELECT * FROM usuario WHERE idusuario = ?', [$idcashier]);
$nresultadoc = mysqli_fetch_assoc($q2cashier);
$cashier2 = $nresultadoc['nombre'];

# Encabezado y datos de la empresa #
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(0, 5, utf8_decode(strtoupper("COMPROBANTE DE COMPRA")), 0, 'C', false);

$pdf->Ln(2);

$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 5, utf8_decode($datos['nombre']), 0, 'C', false);
// Campo db direccion 
$pdf->MultiCell(0, 5, utf8_decode(!empty($datos['direccion']) ? $datos['direccion'] : "Campus Central"), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Teléfono: " . $datos['telefono']), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Soporte: " . $datos['email']), 0, 'C', false);

$pdf->Ln(1);
$pdf->Cell(0, 5, utf8_decode("------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(5);

date_default_timezone_set('America/El_Salvador');
$pdf->MultiCell(0, 5, utf8_decode("Fecha: " . date("d/m/Y h:i A")), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Caja Nro: " . $idcashier), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Cajero(a): " . $cashier2), 0, 'C', false);
/* $pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(0, 5, utf8_decode(strtoupper("Ticket Nro: 1")), 0, 'C', false); */
$pdf->SetFont('Arial', '', 9);

$pdf->Ln(1);
$pdf->Cell(0, 5, utf8_decode("------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(5);

$pdf->MultiCell(0, 5, utf8_decode("Cliente(a): " . $datosC['full_name']), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Código de Carnet: " . $datosC['id_code']), 0, 'C', false);
//$pdf->MultiCell(0, 5, utf8_decode("Grado: " . $datosC['grade']), 0, 'C', false);
if ($datosC['grade'] == '3er') {
    $grado = '3er año de bachillerato';
} else if ($datosC['grade'] == '2do') {
    $grado = '2do año de bachillerato';
} else if ($datosC['grade'] == '1ro') {
    $grado = '1er año de bachillerato';
} else {
    $grado = 'N/A';
}
$pdf->MultiCell(0, 5, utf8_decode("Grado: " . $grado), 0, 'C', false);

// $pdf->MultiCell(0, 5, utf8_decode("Dirección: San Salvador, El Salvador, Centro America"), 0, 'C', false);

$pdf->Ln(1);
$pdf->Cell(0, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(3);

# Tabla de productos #
$pdf->Cell(10, 5, utf8_decode("Cant."), 0, 0, 'C');
$pdf->Cell(53, 5, utf8_decode("Precio"), 0, 0, 'C');
// $pdf->Cell(15, 5, utf8_decode("Desc."), 0, 0, 'C');
$pdf->Cell(10, 5, utf8_decode("Total"), 0, 0, 'C');

$pdf->Ln(3);
$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');
$pdf->Ln(3);



/*----------  Detalles de la tabla  ----------*/
while ($row = mysqli_fetch_assoc($ventas)) {

    $pdf->MultiCell(0, 4, utf8_decode($row['descripcion']), 0, 'C', false);
    $pdf->Cell(10, 4, utf8_decode($row['cantidad']), 0, 0, 'C');
    $pdf->Cell(53, 4, utf8_decode("$" . $row['precio']), 0, 0, 'C');
    // $pdf->Cell(19, 4, utf8_decode("$0.00 USD"), 0, 0, 'C');
    $pdf->Cell(10, 4, utf8_decode("$" . number_format($row['cantidad'] * $row['precio'], 2, '.', ',')), 0, 0, 'C');
    $pdf->Ln(4);
    // $pdf->MultiCell(0, 4, utf8_decode("Garantía de fábrica: 2 Meses"), 0, 'C', false);
    $pdf->Ln(7);
}
/*----------  Fin Detalles de la tabla  ----------*/



$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');

$pdf->Ln(5);

# Impuestos & totales #
while ($row1 = mysqli_fetch_assoc($totalpagar)) {
    $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
    $totalpagar1 = $row1['totalpagar'];
    $pdf->Cell(22, 5, utf8_decode("TOTAL A PAGAR"), 0, 0, 'C');
    $pdf->Cell(32, 5, utf8_decode("$" . number_format($totalpagar1, 2, '.', ',')), 0, 0, 'C');
}

$pdf->Ln(5);

/* $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("IVA (13%)"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("+ $0.00 USD"), 0, 0, 'C');

$pdf->Ln(5); */

$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("SALDO RESTANTE"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$" . $cantidad_actual), 0, 0, 'C');

/* $pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("TOTAL PAGADO"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$100.00 USD"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("CAMBIO"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$30.00 USD"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("USTED AHORRA"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$0.00 USD"), 0, 0, 'C'); */

$pdf->Ln(10);

$pdf->MultiCell(0, 5, utf8_decode("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***"), 0, 'C', false);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 7, utf8_decode("¡Gracias por su compra!"), '', 0, 'C');

$pdf->Ln(9);

# Codigo de barras #
$pdf->Code128(5, $pdf->GetY(), $barcode, 70, 20);
$pdf->SetXY(0, $pdf->GetY() + 21);
$pdf->SetFont('Arial', '', 14);
$pdf->MultiCell(0, 5, utf8_decode($barcode), 0, 'C', false);

# Nombre del archivo PDF #
$pdf->Output("I", "Ticket_Venta.pdf", true);
