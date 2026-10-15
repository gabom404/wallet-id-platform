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
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['codigo']) || empty($_POST['producto']) || ($_POST['cantidad'] == "") || ($_POST['precio'] == "")) {
    $alert = '<div class="alert alert-warning" role="alert">
              ¡Completa los campos vacíos!
            </div>';
  } else {
    if ($_POST['precio'] <= 0) {
      $alert = '<div class="alert alert-danger" role="alert">
      ¡No se aceptan números negativos o cero!
    </div>';
    } else {
      $codproducto = $_GET['id'];
      $codigo = $_POST['codigo']; //parte de la qery codigo = '$codigo', //empty($_POST['codigo']) ||
      $producto = $_POST['producto'];
      $precio = $_POST['precio'];
      $preciov = $_POST['preciov'];
      $cantidad = $_POST['cantidad'];
      $cantidadm = $_POST['cantidadm'];

      $ganancia = ($preciov - $precio) * $cantidad;
      if ($ganancia <= 0) {
        $alert = '<div class="alert alert-warning" role="alert">
        ¡Costos superan ingresos, afectando las ganancias!
      </div>';
      } else {
        $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precio= '$precio', precio_v = '$preciov', existencia = '$cantidad', existencia_m = '$cantidadm' WHERE codproducto = $codproducto");
        if ($query_update) {
          $alert = '<div class="alert alert-success" role="alert">
                ¡Producto actualizado con éxito!
              </div>';
        } else {
          $alert = '<div class="alert alert-danger" role="alert">
                  ¡Error al modificar!
                </div>';
        }
      }
    }
  }
}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: productos.php");
} else {
  $id_producto = $_REQUEST['id'];
  if (!is_numeric($id_producto)) {
    header("Location: productos.php");
  }
  $query_producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto > 0) {
    $data_producto = mysqli_fetch_assoc($query_producto);
  } else {
    header("Location: productos.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-black font-weight-bold">
        Actualizar Datos Producto
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="codigo">Código de barra:</label>
            <div class="input-group mb-3">
              <input type="number" class="form-control" placeholder="Ingrese el código de barra del producto" name="codigo" id="codigo" value="<?php echo $data_producto['codigo']; ?>" oninput="validarCantidad(this)">
              <input class="input-group-text btn btn-outline-light" type="button" onclick="codigodebarra_aleatorio(12, 13)" value="&#xD9E;" /><!-- <i class="fas fa-random"></i> -->
            </div>
          </div>
          <div class="form-group">
            <label for="producto">Producto:</label>
            <input type="text" class="form-control" placeholder="Ingrese el nombre del producto" name="producto" value="<?php echo $data_producto['descripcion']; ?>">
          </div>
          <div class="form-group">
            <label for="precio">Precio costo:</label>
            <input step=".01" placeholder="Ingrese el precio de costo del producto" class="form-control" name="precio" id="precio" min="0" value="<?php echo $data_producto['precio']; ?>">
          </div>
          <div class="form-group">
            <label for="precio">Precio venta:</label>
            <input step=".01" placeholder="Ingrese el precio de venta del producto" class="form-control" name="preciov" id="preciov" min="0" value="<?php echo $data_producto['precio_v']; ?>">
          </div>
          <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" class="form-control" placeholder="Ingrese la cantidad del producto" name="cantidad" id="cantidad" value="<?php echo $data_producto['existencia']; ?>">
          </div>
          <div class="form-group">
            <label for="cantidad">Aviso cantidad mínima:</label>
            <input type="number" placeholder="Ingrese la cantidad mínima a considerar del producto" class="form-control" name="cantidadm" id="cantidadm" value="<?php echo $data_producto['existencia_m']; ?>">
          </div>
          <div class="form-group">
            <label for="ganancia">Ganancia estimada:</label> <label for="ganancia" id="ganancia" name="ganancia"> $<?php echo number_format(($data_producto['precio_v'] - $data_producto['precio']) * $data_producto['existencia'], 2); ?></label>
          </div>
          <button class="btn btn-primary" type="submit"><i class="fas fa-clipboard-check"></i> Actualizar Datos</button>
          <a href="productos.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Regresar</a>
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