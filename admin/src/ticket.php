<?php
require("../conexion.php");
require('pdf/fpdf/fpdf.php');

if (isset($_POST['cantidad'])) {

    $id_cliente = $_POST['idcliente'];
    $id_usuario = $_POST['idusuario'];
    $codigo = $_POST['cod_cliente'];
    $cantidad = $_POST['cantidad'];
    $clientes = mysqli_query($conexion, "SELECT * FROM users WHERE id = $id_cliente");
    $datosC = mysqli_fetch_assoc($clientes);
    $config = mysqli_query($conexion, "SELECT * FROM configuracion");
    $datos = mysqli_fetch_assoc($config);
    $query = mysqli_query($conexion, "SELECT * FROM users WHERE full_name = '$codigo'");
    $resultado = mysqli_fetch_assoc($query);
    $nombre = $resultado['full_name'];
    $cantidad_actual = $resultado['funds'];
    $cantidad_total = $cantidad_actual + $cantidad;
    $query_insert = mysqli_query($conexion, "UPDATE  users SET funds = '$cantidad_total' WHERE full_name = '$codigo'");
    $insertar = mysqli_query($conexion, "INSERT INTO fondos(id_cliente, total, id_usuario) VALUES ($id_cliente, '$cantidad', $id_usuario)");
    $id_maximo = mysqli_query($conexion, "SELECT MAX(idv) AS total FROM fondos");
    $resultId = mysqli_fetch_assoc($id_maximo);
    $ultimoId = $resultId['total'];
    $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_fondos(id_venta, cantidad ) VALUES ($ultimoId, $cantidad)");

    if ($_POST['cantidad'] == "" || $_POST['cod_cliente'] == "") {
        echo '<script type="text/javascript">
		alert("Datos invalidos");
		</script>';
    } else {
        if ($query_insert) {
            $pdf = new FPDF($orientation = 'P', $unit = 'mm', array(45, 120));
            $nombre = $_POST['cod_cliente'];
            $telefono = $_POST['tel_cliente'];
            $cantidad = $_POST['cantidad'];
            $direccion = $_POST['dir_cliente'];
            $pdf->AddPage();
            $pdf->SetMargins(0, 1, 1);
            $pdf->SetTitle("Ventas");
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->setX(0);
            $pdf->Image("../assets/img/wallet-id-logo.svg", 8, 4, 30, 5, 'PNG');
            $pdf->Cell(1, 1, " ", 0, 1, 'L');
            $pdf->Cell(10, 5, "Contacto: ", 0, 0, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->setX(9);
            $pdf->Cell(10, 5, $datos['telefono'], 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(10, 5, utf8_decode("Dirección: "), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->setX(9.5);
            $pdf->Cell(10, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(10, 5, "Soporte: ", 0, 0, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->setX(8);
            $pdf->Cell(10, 5, utf8_decode($datos['email']), 0, 1, 'L');
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->SetFillColor(0, 0, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(45, 2, "Datos Personales", 1, 1, 'C', 1);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(17, 5, utf8_decode('Nombre completo: '), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->Cell(5, 5, utf8_decode($datosC['full_name']), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(10, 5, utf8_decode('Número de teléfono: '), 0, 0, 'L');
            $pdf->setX(19);
            $pdf->SetFont('Arial', '', 5);
            $pdf->Cell(10, 5, utf8_decode($datosC['telephone_number']), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(10, 5, utf8_decode('Dirección: '), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->Cell(10, 5, utf8_decode($datosC['address']), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(255, 255, 255);
            
            $pdf->SetFont('Arial', '', 5); 
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B',5);
            $pdf->SetTextColor(255, 255, 255);  
            $pdf->Cell(45, 2, "Estado de Cuenta", 1, 1, 'C', 1);
            $pdf->SetTextColor(0, 0, 0);
            
            $pdf->Cell(28, 5, 'Total abonado: ', 0, 0, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->setX(36);
            $pdf->Cell(28, 5,'$'. $cantidad, 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(28, 5, 'Saldo anterior: ', 0, 0, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->setX(36);
            $pdf->Cell(28, 5,'$'. $cantidad_actual, 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 5);
            $pdf->Cell(28, 5, 'Nuevo saldo : ', 0, 0, 'L');
            $pdf->SetFont('Arial', '', 5);
            $pdf->setX(36);
            $pdf->Cell(28, 5,'$'. $cantidad_total, 0, 0, 'L');
            
            
            //$ contador = 1;
            //while ($row = mysqli_fetch_assoc($saldo)) {
            //    $pdf->Cell(14, 5, $contador, 0, 0, 'L');
            //    $pdf->Cell(90, 5, $row['cantidad_actual'], 0, 0, 'L');
            //    $pdf->Cell(32, 5, $row['cantidad_total'], 0, 0, 'L');
            //    //$pdf->Cell(35, 5, number_format($row['cantidad'] * $row['precio'], 2, '.', ','), 0, 1, 'L');
            //    $contador++;
            //}
            
            $pdf->Output("ventas.pdf", "I");
            
        } else {
            $alert =
                $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, 'Error al generar el PDF!');
            $pdf->Output();
        }
    }
}
