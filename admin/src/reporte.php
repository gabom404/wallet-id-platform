<?php
include_once "includes/header.php";
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Registro Venta";
$sql = safe_query($conexion, 'SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?', [$id_user, $permiso]);
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

// $query = mysqli_query($conexion, "SELECT v.*, c.id, c.full_name, c.id_code FROM ventas v INNER JOIN users c ON v.id_cliente = c.id");
if (empty($_POST['fechaf'])) {
    date_default_timezone_set('America/El_Salvador');
    $fecha = date('Y-m-d');
} else {
    $fecha = $_POST['fechaf'];
}

    $query = safe_query($conexion, 'SELECT * FROM ventas WHERE fecha LIKE ?', ['%' . $fecha . '%']);

?>
<form action="reporte.php" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <input id="fechaf" class="form-control" type="date" name="fechaf" >
                                    <div class="form-group">
                        <button class="btn btn-primary" id="btn" type="submit"><i class="fas fa-plus"></i> Buscar</button>
                    </div>
</form>
<div class="table-responsive">
    <table class="table table-light" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>Reporte diario</th>
                <th>Fecha</th>
                <th>Ventas realizadas</th>
                <th>Total del día</th>
                <th>Generar Ticket</th>
            </tr>
        </thead>
        <tbody>
            <?php $row = mysqli_fetch_assoc($query);
            if(empty($row['fecha'])){

            }else{
                $queryMAX = safe_query($conexion, 'SELECT COUNT(idv) AS ventas_realizadas FROM ventas WHERE fecha LIKE ?', ['%' . $fecha . '%']);
                $queryTOTAL = safe_query($conexion, 'SELECT SUM(total) AS total_ventas FROM ventas WHERE fecha LIKE ?', ['%' . $fecha . '%']);
                    while ($rowm = mysqli_fetch_assoc($queryMAX)) {
                        while ($rowt = mysqli_fetch_assoc($queryTOTAL)) {
                            $cantidad_ventas = $rowm['ventas_realizadas'];
                            $total_vendido = $rowt['total_ventas'];
                ?>
                            <tr>
                                <td><?php  ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['fecha'])); ?></td>
                                <td><?php echo $cantidad_ventas; ?></td>
                                <td><?php echo "$" . $total_vendido; ?></td>
                               <?php $date = new DateTime($row['fecha']);
                                ?>
                                <td>
                                    <a href="generar_reporte.php?fecha=<?php echo $fecha?>" target="_blank" class="btn btn-purple"><i class="fas fa-receipt"></i></a>
                                </td>
                            </tr>
                <?php   }
                    }
            }

            ?>
        </tbody>
    </table>
</div>
<?php include_once "includes/footer.php"; ?>