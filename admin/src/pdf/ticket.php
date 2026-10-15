<?php

# Incluyendo librerias necesarias #
require_once '../../conexion.php';
// require_once 'fpdf/fpdf.php';
require_once 'fpdf/code128.php';

$pdf = new PDF_Code128('P', 'mm', array(80, 258));
$pdf->SetMargins(4, 10, 4);
$pdf->AddPage();
$pdf->SetTitle('Ticket Saldo');

$id = $_GET['v'];
$factotal = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM users WHERE id = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas1 = mysqli_query($conexion, "SELECT * FROM detalle_fondos WHERE id_venta = $id");
$resultado = mysqli_fetch_assoc($ventas1);
$cantidad = $resultado['cantidad'];
$barcode = $resultado['barcode'];
$query = mysqli_query($conexion, "SELECT * FROM users WHERE id = '$idcliente'");
$resultado = mysqli_fetch_assoc($query);
$cantidad_actual = $resultado['funds'];
$cantidad_total = number_format($cantidad_actual - $cantidad, 2, '.', ',');

# Cajero #
$qcashier = mysqli_query($conexion, "SELECT * FROM fondos WHERE id_cliente = $idcliente  AND idv = '$id'");
$resultadoc = mysqli_fetch_assoc($qcashier);
$idcashier = $resultadoc['id_usuario'];
$q2cashier = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $idcashier");
$nresultadoc = mysqli_fetch_assoc($q2cashier);
$cashier2 = $nresultadoc['nombre'];

# Encabezado y datos de la empresa #
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(0, 5, utf8_decode(strtoupper("COMPROBANTE DE RECARGA")), 0, 'C', false);

$pdf->Ln(2);

$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 5, utf8_decode(!empty($datos['nombre']) ? $datos['nombre'] : "WalletID - Campus Digital Wallet"), 0, 'C', false);
$pdf->MultiCell(0, 5, utf8_decode("Dirección: " . (!empty($datos['direccion']) ? $datos['direccion'] : "Campus Central")), 0, 'C', false);
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

$pdf->Ln(5);

$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("TOTAL ABONADO"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$" . $cantidad), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("SALDO ANTERIOR"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$" . $cantidad_total), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(72, 5, utf8_decode("-------------------------------------------------------------------"), 0, 0, 'C');

$pdf->Ln(5);

$pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("NUEVO SALDO"), 0, 0, 'C');
$pdf->Cell(32, 5, utf8_decode("$" . $cantidad_actual), 0, 0, 'C');

$pdf->Ln(10);

$pdf->MultiCell(0, 5, utf8_decode("*** Para poder realizar un reclamo o devolución debe de presentar este ticket ***"), 0, 'C', false);

/* $pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 7, utf8_decode("Gracias por preferirnos"), '', 0, 'C'); */

$pdf->Ln(4);

# Codigo de barras #
$pdf->Code128(5, $pdf->GetY(), $barcode, 70, 20);
$pdf->SetXY(0, $pdf->GetY() + 21);
$pdf->SetFont('Arial', '', 14);
$pdf->MultiCell(0, 5, utf8_decode($barcode), 0, 'C', false);

# Nombre del archivo PDF #
$pdf->Output("I", "Ticket_Saldo.pdf", true);
