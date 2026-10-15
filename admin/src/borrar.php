<?php
require("../conexion.php");
require('pdf/fpdf/fpdf.php');
$fecha = $_REQUEST['fecha'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$queryMAX = mysqli_query($conexion, "SELECT COUNT(idv) AS ventas_realizadas FROM ventas WHERE fecha LIKE '%$fecha%'");
$queryTOTAL = mysqli_query($conexion, "SELECT SUM(total) AS total_ventas FROM ventas WHERE fecha LIKE '%$fecha%'");
$ventas = mysqli_query($conexion,"SELECT * FROM ventas WHERE fecha LIKE '%$fecha%'");
$ventas_generadas = mysqli_fetch_assoc($queryMAX);
$total_diario = mysqli_fetch_assoc($queryTOTAL);

if (isset($_REQUEST['fecha'])) {
            $pdf = new FPDF($orientation = 'P', $unit = 'mm', array(210, 297));
            $pdf->AddPage();
            $pdf->SetMargins(0, 1, 1);
            $pdf->SetTitle("Ventas");
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->setX(0);
            // $pdf->Image("../assets/img/wallet-id-logo.svg", 8, 4, 30, 5, 'PNG');
            $pdf->Cell(1, 1, " ", 0, 1, 'L');
            $pdf->Cell(10, 5, "Contacto: ", 0, 0, 'L');
            $pdf->SetFont('Arial', '', 18);
            $pdf->setX(1);
            $pdf->Cell(10, 25, $datos['telefono'], 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(10, 1, utf8_decode("Dirección: "), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 17);
            $pdf->setX(1);
            $pdf->Cell(10, 25, utf8_decode($datos['direccion']), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(10, 1, "Soporte: ", 0, 0, 'L');
            $pdf->SetFont('Arial', '', 20);
            $pdf->setX(0);
            $pdf->Cell(10, 25, utf8_decode($datos['email']), 0, 1, 'L');
            // $pdf->Ln();
            // $pdf->SetFont('Arial', 'B', 5);
            // $pdf->SetFillColor(0, 0, 0);
            // $pdf->SetTextColor(255, 255, 255);
            // $pdf->Cell(45, 2, "Datos Personales", 1, 1, 'C', 1);
            // $pdf->SetTextColor(0, 0, 0);
            // $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(0, 0, utf8_decode('Total de ventas generadas: '.$ventas_generadas['ventas_realizadas'].''), 0, 1, 'L');
            $pdf->Cell(0, 20, utf8_decode('Total diario: '.$total_diario['total_ventas'].''), 0, 1, 'L');

            $pdf->SetFont('Arial', '', 20);
            while ($row = mysqli_fetch_assoc($ventas)) {
                $ventas_productos = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = '".$row['idv']."'");
                $descripcion_ventas = mysqli_fetch_assoc($ventas_productos);
                
                $pdf->MultiCell(100, 4, utf8_decode($descripcion_ventas['descripcion']), 0, 'L', false);
                $pdf->Cell(140, 0, utf8_decode($descripcion_ventas['cantidad']), 0, 0, 'C');
                $pdf->Cell(-50, 0, utf8_decode("$" . $descripcion_ventas['precio']), 0, 0, 'C');
                // $pdf->Cell(19, 4, utf8_decode("$0.00 USD"), 0, 0, 'C');
                $pdf->Cell(150, 0, utf8_decode("$" . number_format($descripcion_ventas['cantidad'] * $descripcion_ventas['precio'], 2, '.', ',')), 0, 0, 'C');
                $pdf->Ln(4);
                // $pdf->MultiCell(0, 4, utf8_decode("Garantía de fábrica: 2 Meses"), 0, 'C', false);
                $pdf->Ln(7);
            }

            $pdf->Ln(3);
   

            $pdf->Output("ventas.pdf", "I");
        } else {
            $alert =
                $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, 'Error al generar el PDF!');
            $pdf->Output();
        }

