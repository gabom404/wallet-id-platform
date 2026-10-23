<?php
include_once "includes/header.php";
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Registro Saldo";
$sql = safe_query($conexion, 'SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?', [$id_user, $permiso]);
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
$query = mysqli_query($conexion, "SELECT v.*, c.id, c.full_name, c.id_code FROM fondos v INNER JOIN users c ON v.id_cliente = c.id");
?>
<div class="table-responsive">
    <table class="table table-light" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>Código de Barra</th>
                <th>Cliente</th>
                <th>Carnet</th>
                <th>Total Abonado</th>
                <th>Fecha y Hora</th>
                <th>Generar Ticket</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) {
                $query1 = safe_query($conexion, 'SELECT barcode FROM detalle_fondos WHERE id_venta = ? ', [$row['idv']]);
                while ($barcode = mysqli_fetch_assoc($query1)) {
                    $barcoder = $barcode['barcode'];
                } ?>
                <tr>
                    <td><?php echo $barcoder; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['id_code']; ?></td>
                    <td><?php echo "$" . $row['total']; ?></td>
                    <td><?php $row['fecha'];
                        $date = new DateTime($row['fecha']);
                        echo $date->format('d/m/Y h:i A'); ?></td>
                    <td>
                        <a href="pdf/ticket.php?cl=<?php echo $row['id'] ?>&v=<?php echo $row['idv'] ?>" target="_blank" class="btn btn-purple"><i class="fas fa-receipt"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include_once "includes/footer.php"; ?>