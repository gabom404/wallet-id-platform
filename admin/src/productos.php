<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $preciov = $_POST['preciov'];
    $cantidad = $_POST['cantidad'];
    $cantidadm = $_POST['cantidadm'];
    $usuario_id = $_SESSION['idUser'];

    $alert = "";
    if (empty($codigo) || empty($producto) || $precio == "" || $preciov == "" || $cantidad == "" || $cantidadm == "") {
        $alert = '<div class="alert alert-warning" role="alert">
                ¡Completa los campos vacíos!
            </div>';
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                        ¡El código de barra del producto ya existe!
                    </div>';
        } else {
            if ($_POST['precio']  <= 0 || $_POST['preciov'] <= 0 || $_POST['cantidad'] <= 0 || $_POST['cantidadm'] <= 0) {
                $alert = '<div class="alert alert-danger" role="alert">
                ¡No se aceptan números negativos o cero!
            </div>';
            } else {

                $ganancia = ($preciov - $precio) * $cantidad;
                if ($ganancia <= 0) {
                    $alert = '<div class="alert alert-warning" role="alert">
        ¡Costos superan ingresos, afectando las ganancias!
      </div>';
                } else {
                    $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precio,precio_v,existencia,existencia_m,usuario_id) values ('$codigo', '$producto','$precio','$preciov','$cantidad','$cantidadm','$usuario_id')");
                    if ($query_insert) {
                        $alert = '<div class="alert alert-success" role="alert">
        ¡Producto agregado con éxito!
    </div>';
                    } else {
                        $alert = '<div class="alert alert-danger" role="alert">
        ¡Error al agregar el producto!
    </div>';
                    }
                }
            }
        }
    }
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>Nro</th>
                <th>Código de Barra</th>
                <th>Producto</th>
                <th>Precio Costo</th>
                <th>Precio Venta</th>
                <th>Cantidad</th>
                <th>Ganancia</th>
                <th>Aviso</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM producto");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $counter = 0;
                while ($data = mysqli_fetch_assoc($query)) {
                    $counter++;
                    if ($data['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                    } else {
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                    }
            ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <td><?php echo $data['codigo']; ?></td>
                        <td><?php echo $data['descripcion']; ?></td>
                        <td><?php echo "$" . $data['precio']; ?></td>
                        <td><?php echo "$" . $data['precio_v']; ?></td>
                        <td><?php echo $data['existencia']; ?></td>
                        <td><?php echo "$" . $data['ganancia_u']; ?></td>
                        <td>
                            <?php
                            if ($data['existencia'] > $data['existencia_m']) {
                                echo '<span class="badge badge-pill badge-success">Cantidad suficiente</span>';
                            } else if ($data['existencia'] <= $data['existencia_m']) {
                                echo '<span class="badge badge-pill badge-danger">Mínimo alcanzado</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo $estado ?></td>
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <!-- <a href="agregar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a> -->

                                <a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-eye-slash'></i> </button>
                                </form>
                            <?php } else { ?>
                                <form action="activar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="activar d-inline">
                                    <button class="btn btn-warning" type="submit"><i class='fas fa-eye'></i> </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>
<div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-black">
                <h5 class="modal-title" id="my-modal-title">Agregar Nuevo Producto</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>

                    <!-- <div class="form-group">
                        <div class="input-group">
                            <label for="codigo">Código de barra:</label>
                            <input type="number" placeholder="Ingrese el código de barra del producto" name="codigo" id="codigo" class="form-control">
                            <span class="input-group-text" onclick="codigodebarra_aleatorio(12, 15)"><i class="fas fa-random"></i></span>  value="&#xF074;"
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label for="codigo">Código de barra:</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" placeholder="Ingrese el código de barra del producto" name="codigo" id="codigo" oninput="validarCantidad(this)">
                            <span class="input-group-text btn btn-outline-light" onclick="codigodebarra_aleatorio(12, 13)"><i class="fas fa-random"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="producto">Producto:</label>
                        <input type="text" placeholder="Ingrese el nombre del producto" name="producto" id="producto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio costo:</label>
                        <input step=".01" placeholder="Ingrese el precio de costo del producto" class="form-control" name="precio" id="precio" min="0">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio venta:</label>
                        <input step=".01" placeholder="Ingrese el precio de venta del producto" class="form-control" name="preciov" id="preciov" min="0">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" placeholder="Ingrese la cantidad dl producto" class="form-control" name="cantidad" id="cantidad">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Aviso cantidad mínima:</label>
                        <input type="number" placeholder="Ingrese la cantidad mínima a considerar del producto" class="form-control" name="cantidadm" id="cantidadm">
                    </div>
                    <div class="form-group">
                        <label for="ganancia">Ganancia estimada:</label> <label for="ganancia" id="ganancia" name="ganancia"> $0.00</label>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i> Agregar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function codigodebarra_aleatorio(min, max) {
        var rango = Math.floor(Math.random() * (max - min + 1) + min);
        var num = '';
        for (var i = 0; i < rango; i++) {
            num += Math.floor(Math.random() * 10)
        }
        document.getElementById("codigo").value = num;
    }
</script>
<script type="text/javascript">
    function validarCantidad(input) {
        const regex = /^[0-9]+$/;
        const valor = input.value;

        if (!regex.test(valor)) {
            input.value = valor.replace(/\D/g, '');
        }
    }
</script>
<script>
    const precioCostoInput = document.getElementById('precio');
    const precioVentaInput = document.getElementById('preciov');
    const cantidadInput = document.getElementById('cantidad');
    const gananciaDiv = document.getElementById('ganancia');

    function calcularGanancia() {
        const precioCosto = parseFloat(precioCostoInput.value);
        const precioVenta = parseFloat(precioVentaInput.value);
        const cantidad = parseInt(cantidadInput.value);

        if (isNaN(precioCosto) || isNaN(precioVenta) || isNaN(cantidad)) {
            gananciaDiv.textContent = "$0.00";
            document.getElementById('ganancia').value = "0";
        } else {
            const ganancia = (precioVenta - precioCosto) * cantidad;
            gananciaDiv.textContent = `$${ganancia.toFixed(2)}`;
            document.getElementById('ganancia').value = ganancia.toFixed(2);
        }
    }

    precioCostoInput.addEventListener('input', calcularGanancia);
    precioVentaInput.addEventListener('input', calcularGanancia);
    cantidadInput.addEventListener('input', calcularGanancia);
</script>

<?php include_once "includes/footer.php"; ?>