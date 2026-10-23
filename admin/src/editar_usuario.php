<?php include_once "includes/header.php";
require "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Administradores";
$sql = safe_query($conexion, 'SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?', [$id_user, $permiso]);
$idusuario = $_REQUEST['id'];
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario'])) {
        $alert = '<div class="alert alert-warning" role="alert">¡Completa los campos vacíos!</div>';
    } else {
        $idusuario = $_REQUEST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $queryrol = safe_query($conexion, 'SELECT * FROM usuario WHERE idusuario = ?', [$idusuario]);
        $resultrol = mysqli_fetch_assoc($queryrol);
        if ($_POST['rol'] == '') {
            $rol = $resultrol['rol'];
        } else {
            $rol = $_POST['rol'];
        }
        $sql_update = safe_query($conexion, 'UPDATE usuario SET nombre = ?, correo = ? , usuario = ?, rol = ? WHERE idusuario = ?', [$nombre, $correo, $usuario, $rol, $idusuario]);
        $alert = '<div class="alert alert-success" role="alert">¡Datos actualizados con éxito!</div>';
    }
}

// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: usuarios.php");
}
$idusuario = base64_decode($_REQUEST['id']);
$sql = safe_query($conexion, 'SELECT * FROM usuario WHERE idusuario = ?', [$idusuario]);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: usuarios.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['idusuario'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $usuario = $data['usuario'];
        $rol = $data['rol'];
    }
}
?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary font-weight-bold">
                Actualizar Datos Usuario
            </div>
            <div class="card-body">
                <form class="" action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <input type="hidden" name="id" value="<?php echo $idusuario; ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre completo:</label>
                        <input type="text" placeholder="Ingrese el nombre a actualizar" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre; ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo institucional:</label>
                        <input type="email" placeholder="Ingrese el correo institucional a actualizar" class="form-control" name="correo" id="correo" value="<?php echo $correo; ?>">

                    </div>
                    <div class="form-group">
                        <label for="usuario">Nombre de usuario:</label>
                        <input type="text" placeholder="Ingrese el nombre de usuario a actualizar" class="form-control" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
                    </div>

                    <?php
                    $query = safe_query($conexion, 'SELECT * FROM usuario WHERE idusuario = ?', [$id_user]);
                    $datau = mysqli_fetch_array($query);
                    $rolu = $datau['rol'];
                    $idusuario = base64_decode($_REQUEST['id']);
                    $queryu = safe_query($conexion, 'SELECT * FROM usuario WHERE idusuario = ?', [$idusuario]);
                    $resultu = mysqli_fetch_assoc($queryu);
                    if ($id_user == $idusuario) {
                        echo '
                    <div class="form-group">
                        <label for="usuario">Rol de usuario:</label>
                        <input type="text" class="form-control" name="usuario" id="usuario" value="' . $rol . '" disabled>
                        </div>';
                    } else {
                        if ($resultu['rol'] == $datau['rol']) {
                            $opcion =  '<div class="form-group">
                            <label for="usuario">Rol de usuario:</label>
                            <input type="text" class="form-control" name="usuario" id="usuario" value="' . $rol . '" disabled>
                            </div></div>';
                        } else {

                            if ($rolu == "Owner") {
                                $opcion = '
                                <div class="form-group">
                                <label for="usuario">Rol de usuario:</label>
                                </div>
                                <input type="text" class="form-control" name="usuario" id="rol1" value="' . $rol . '" disabled>
                                <div class="form-group">
                                <label for="clave">Rol:</label>
                                <select class="form-control" value="' . $rol . '"  required name="rol" id="rol">
                                <option value="" disabled selected hidden>Seleccione un rol</option>
                                <option value="Owner">Owner</option>
                                <option value="Co-Owner">Co-Owner</option>
                                <option value="SuperAdmin">SuperAdmin</option>
                                <option value="Admin">Admin</option>
                                </select>
                                </div>';
                            } elseif ($rolu == "Co-Owner") {
                                $opcion = '
                                <div class="form-group">
                                <label for="usuario">Rol de usuario:</label>
                                <input type="text" class="form-control" name="usuario" id="usuario" value="' . $rol . '" disabled>
                                </div>
                                <div class="form-group">
                                <label for="clave">Rol:</label>
                                <select class="form-control" value="' . $rol . '"  required name="rol" id="rol">
                                <option value="" disabled selected hidden>Seleccione un rol</option>
                                <option value="Co-Owner">Co-Owner</option>
                                <option value="SuperAdmin">SuperAdmin</option>
                                <option value="Admin">Admin</option>
                                </select>
                                </div>';
                            } elseif ($rolu == "SuperAdmin") {
                                $opcion = '
                            <div class="form-group">
                            <label for="usuario">Rol de usuario:</label>
                            <input type="text" class="form-control" name="usuario" id="usuario" value="' . $rol . '" disabled>
                            </div>
                            <div class="form-group">
                            <label for="clave">Rol:</label>
                            <select class="form-control" value="' . $rol . '"  required name="rol" id="rol">
                            <option value="" disabled selected hidden>Seleccione un rol</option>
                            <option value="SuperAdmin">SuperAdmin</option>
                            <option value="Admin">Admin</option>
                            </select>
                            </div>';
                            } elseif ($rolu == "Admin") {
                                $opcion = '
                                <div class="form-group">
                                <option value="" disabled selected hidden>Seleccione un rol</option>
                                <input type="text" class="form-control" name="usuario" id="usuario" value="' . $rol . '" disabled>
                                </div>
                                </div>';
                            }
                        }
                        echo $opcion;
                    }
                    ?>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-clipboard-check"></i> Actualizar Datos</button>
                    <a href="usuarios.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Regresar</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>