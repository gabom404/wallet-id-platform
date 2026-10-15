<?php
    require('pdf/fpdf/fpdf.php');

class PDF extends FPDF
{
function Header()                           
{
    require("../conexion.php");
    $fecha = $_REQUEST['fecha'];
    $config = mysqli_query($conexion, "SELECT * FROM configuracion");
    $datos = mysqli_fetch_assoc($config);
    $queryMAX = mysqli_query($conexion, "SELECT COUNT(idv) AS ventas_realizadas FROM ventas WHERE fecha LIKE '%$fecha%'");
    $queryTOTAL = mysqli_query($conexion, "SELECT SUM(total) AS total_ventas FROM ventas WHERE fecha LIKE '%$fecha%'");
    $ventas_generadas = mysqli_fetch_assoc($queryMAX);
    $total_diario = mysqli_fetch_assoc($queryTOTAL);

    date_default_timezone_set('America/El_Salvador');

    $this->setY(12);
    $this->setX(10);
    
        // $this->Image('img/shinheky.png',25,5,33);
    
    $this->SetFont('times', 'B', 13);
    
    $this->Text(85, 15, utf8_decode('WALLETID - CAMPUS DIGITAL WALLET'), 'L');
    
    $this->Text(37, 21, utf8_decode(!empty($datos['direccion']) ? $datos['direccion'] : 'Campus Central'));
    $this->Text(88,27, utf8_decode('Tel: '.$datos['telefono'].''));
    $this->Text(78,33, utf8_decode($datos['email']));
    
    // $this->Image('img/shinheky.png',160,5,33);
    
    //información de # de factura
    // $this->SetFont('Arial','B',10);   
    // $this->Text(150,48, utf8_decode('FACTURA N°:'));
    // $this->SetFont('Arial','',10);  
    // $this->Text(176,48, '2002');
    
    
    
    // Agregamos los datos del cliente
    $this->SetFont('Arial','B',10);    
    $this->Text(10,48, utf8_decode('Fecha:'));
    $this->SetFont('Arial','',10);    
    $this->Text(25,48, $_REQUEST['fecha']);
    
    
    
    
    // Agregamos los datos de la factura
    $this->SetFont('Arial','B',10);    
    $this->Text(10,54, utf8_decode('Total de ventas:'));
    $this->SetFont('Arial','',10);    
    $this->Text(38,54, $ventas_generadas['ventas_realizadas']);
    
    $this->Ln(50);
}

function Footer()
{
     $this->SetFont('helvetica', 'B', 8);
        $this->SetY(-15);
        $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(95,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(10,287,200,287);
        $this->Cell(0,5,utf8_decode("WalletID - Campus Digital Wallet © Todos los derechos reservados."),0,0,"C");
        
}


}



$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);



$pdf->setY(60);$pdf->setX(135);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20, 7, utf8_decode('No #'),1,0,'C',0);
$pdf->Cell(95, 7, utf8_decode('Nombre'),1,0,'C',0);
$pdf->Cell(20, 7, utf8_decode('Cantidad'),1,0,'C',0);
$pdf->Cell(25, 7, utf8_decode('Precio'),1,0,'C',0);
$pdf->Cell(25, 7, utf8_decode('Total'),1,1,'C',0);
require("../conexion.php");
$fecha = $_REQUEST['fecha'];
$queryMAX = mysqli_query($conexion, "SELECT COUNT(idv) AS ventas_realizadas FROM ventas WHERE fecha LIKE '%$fecha%'");
$queryTOTAL = mysqli_query($conexion, "SELECT SUM(total) AS total_ventas FROM ventas WHERE fecha LIKE '%$fecha%'");
$ventas_generadas = mysqli_fetch_assoc($queryMAX);
$total_diario = mysqli_fetch_assoc($queryTOTAL);

$ventas = mysqli_query($conexion,"SELECT * FROM ventas WHERE fecha LIKE '%$fecha%'");
$i = 1;
while ($row = mysqli_fetch_assoc($ventas)) {
    $ventas_productos = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = '".$row['idv']."'");
    $descripcion_ventas = mysqli_fetch_assoc($ventas_productos);
// En esta parte estan los encabezados

   
    $pdf->SetFont('Arial','',10);

    //Aqui inicia el for con todos los productos
// for ($i=0; $i < 5; $i++) { 
   
    $pdf->Cell(20, 7, $i++,1,0,'L',0);
    $pdf->Cell(95, 7, utf8_decode($descripcion_ventas['descripcion']),1,0,'L',0);
    $pdf->Cell(20, 7, utf8_decode($descripcion_ventas['cantidad']),1,0,'R',0);
    $pdf->Cell(25, 7, utf8_decode($descripcion_ventas['precio']),1,0,'R',0);
    $pdf->Cell(25, 7, utf8_decode("$" . number_format($descripcion_ventas['cantidad'] * $descripcion_ventas['precio'], 2, '.', ',')),1,1,'R',0);
    

}


//// Apartir de aqui esta la tabla con los subtotales y totales

$pdf->Ln(10);

        $pdf->SetX(40);
        $pdf->Cell(70,6,utf8_decode('Total generado en el día:'),1,0);
        $pdf->Cell(60,6,utf8_decode("$" . number_format($total_diario['total_ventas'], 2, '.', ',')),1,1,'R',0);
 



$pdf->Output();
?>