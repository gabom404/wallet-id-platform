<?php
session_start();
include("conexion.php");
if (empty($_SESSION['now'])) {
    header('Location: login.php');
    exit();
} else {
    $nombre = $_SESSION['name'];
    $id_user = $_SESSION['id'];

    $query = safe_query($conexion, 'SELECT * FROM ventas WHERE id_cliente = ?', [$id_user]);
}

?>
<?php
include("navbar.php");
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-normal mb-0">Registro de compras:</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Movimientos:</h4>
                        <table class="table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th>Nombre del producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $query1 = safe_query($conexion, 'SELECT * FROM detalle_venta WHERE id_venta = ?', [$row['idv']]);
                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        $producto = $row1['id_producto'];
                                        $cantidad = $row1['cantidad'];
                                    }

                                    $query2 = safe_query($conexion, 'SELECT * FROM producto WHERE codproducto = ?', [$producto]);
                                    while ($row2 = mysqli_fetch_assoc($query2)) {
                                        $nproducto = $row2['descripcion'];
                                        $precio = $row2['precio_v'];
                                    }


                                    echo "<tr>";
                                    echo "<td>" . $nproducto . "</td>";
                                    echo "<td>" . $precio . "</td>";
                                    echo "<td>" . $cantidad . "</td>";
                                    echo "<td>" . $row['total'] . "</td>";
                                    echo "<td>" . $row['fecha'] . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>



</div>
</div>
</div>
<?php include("footer.php"); ?>
<script>
$(document).ready( function () {
$('#myTable').DataTable();
} );
</script>