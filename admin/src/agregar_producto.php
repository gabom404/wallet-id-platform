<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (empty($_GET['id'])) {
    header("Location: productos.php");
} else {
    $id_producto = $_GET['id'];
    if (!is_numeric($id_producto)) {
        header("Location: productos.php");
    }
    $consulta = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
    $data_producto = mysqli_fetch_assoc($consulta);
}
if (!empty($_POST)) {
    $alert = "";
    if (($_POST['cantidad'] != "") || ($_POST['precio'] != "") || !empty($_POST['producto_id'])) {
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $producto_id = $_GET['id'];
        if ($_POST['precio'] <= 0) {
            $alert = '<div class="alert alert-danger" role="alert">
            ¡No se aceptan números negativos o cero!
        </div>';
        }
        if ($_POST['precio'] <= 0 || $_POST['cantidad'] <= 0) {
            $alert = '<div class="alert alert-danger" role="alert">
            ¡No se aceptan números negativos o cero!
        </div>';
        } else {
            $total = $cantidad + $data_producto['existencia'];
            $query_insert = mysqli_query($conexion, "UPDATE producto SET existencia = $total WHERE codproducto = $id_producto");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                        ¡Producto actualizado con éxito!
                    </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                        ¡Error al ingresar la cantidad!
                    </div>';
            }
            mysqli_close($conexion);
        }
    } else {
        $alert = '<div class="alert alert-warning" role="alert">
                        ¡Completa los campos vacíos!
                    </div>';
    }
}
?>
<div class="row">
    <div class="col-lg-6 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                Actualizar Productos
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="precio">Precio actual:</label>
                        <input type="text" class="form-control" value="<?php echo $data_producto['precio']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="precio">Cantidad de productos disponibles:</label>
                        <input type="number" class="form-control" value="<?php echo $data_producto['existencia']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="precio">Nuevo precio:</label>
                        <input step=".01" placeholder="Ingrese el nuevo precio del producto" name="precio" class="form-control" value="<?php echo $data_producto['precio']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Agregar cantidad:</label>
                        <input type="number" placeholder="Ingrese la cantidad del producto" name="cantidad" id="cantidad" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Actulizar
                        producto</button>
                    <a href="productos.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>