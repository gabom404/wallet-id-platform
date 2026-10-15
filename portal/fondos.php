<?php
session_start();
include("conexion.php");
if (empty($_SESSION['now'])) {
    header('Location: login.php');
    exit();
} else {
    $nombre = $_SESSION['name'];
    $id_user = $_SESSION['id'];

    $query = mysqli_query($conexion, "SELECT * FROM fondos WHERE id_cliente = '$id_user'");
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
                        <h3 class="font-weight-normal mb-0">Registro de fondos:</h3>
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
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($query)) {

                                    echo "<tr>";
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
<?php include("footer.php");
?>
<script>
$(document).ready( function () {
$('#myTable').DataTable();
} );
</script>