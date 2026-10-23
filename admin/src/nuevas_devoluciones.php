<?php include_once "includes/header.php";
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "Realizar Devolucion";
$sql = safe_query($conexion, 'SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?', [$id_user, $permiso]);
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-black text-center">
                WALLET ID
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Código de carnet:</label>
                                <input type="text" name="tel_cliente" id="tel_cliente" class="form-control" placeholder="Ingrese el código completo" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <input type="hidden" id="idcliente" value="1" name="idcliente" required>
                                <label>Nombre del cliente:</label>
                                <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" disabled required>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Grado:</label>
                                <input type="text" name="dir_cliente" id="dir_cliente" class="form-control" disabled required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> VENDEDOR</label>
                            <p style="font-size:15px; text-transform: uppercase; color:red"><?php echo $_SESSION['nombre']; ?></p>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label style="margin-top: 0;">Foto de perfil del cliente:</label>
                                <img src="../assets/img/default-profile-picture.png" class="img-fluid border" alt="Foto de perfil del cliente" id="imgcliente" name="imgcliente" width="250px" height="250px">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                Buscar producto a devolver:
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input id="ticket" class="form-control" type="text" name="producto" placeholder="Escanear, ingresar el código de barra del ticket">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table table-hover" id="tblDetalle">
                <thead class="thead-dark">
                    <tr>
                        <th>Código de Ticket</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Bruto</th>
                        <th>Precio Neto</th>
                        <th>Eliminar Producto</th>
                    </tr>
                </thead>
                <tbody id="detalle_devolucion">

                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total a devolver:</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <a href="#" class="btn btn-primary" id="btn_generar1"><i class="fas fa-dollar-sign"></i> Devolver</a>
    </div>
    <div class="col-md-6">
    </div>
</div>
<?php include_once "includes/footer.php"; ?>