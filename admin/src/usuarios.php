<?php include_once "includes/header.php";
error_reporting(0);
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Administradores";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

$query = mysqli_query($conexion, "SELECT * FROM usuario ORDER BY estado DESC");
$result = mysqli_num_rows($query);
$queryu = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_user");
$resultu = mysqli_fetch_assoc($queryu);
?>

<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#nuevo_usuario"><i class="fas fa-plus"></i></button>

<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-black">
                <h5 class="modal-title" id="my-modal-title">Crear Nuevo Administrador</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="agregar_administrador" action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">Nombre completo:</label>
                        <input type="text" class="form-control" placeholder="Ingrese el nombre completo" name="nombre" id="nombre">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo institucional:</label>
                        <input type="email" class="form-control" placeholder="Ingrese el correo institucional" name="correo" id="correo">
                    </div>
                    <div id="resultado" style="margin-bottom:.5rem; color: grey; font-size: 15px;"></div>

                    <div class="form-group">
                        <label for="usuario">Nombre de usuario:</label>
                        <input type="text" class="form-control" placeholder="Ingrese el nombre de usuario" name="usuario" id="usuario">
                    </div>
                    <div id="user-requirements" style="margin-bottom:.5rem; color: grey; font-size: 15px;"></div>
                    <div class="form-group">
                        <label for="clave">Contraseña:</label>
                        <input type="password" class="form-control" placeholder="Ingrese la contraseña" name="clave" id="clave">
                    </div>
                    <div id="password-requirements" style="margin-bottom:.5rem; color: grey; font-size: 15px;"></div>
                    <?php
                    if ($resultu["rol"] == "Owner") {
                        $acciones = '
                        <div class="form-group">
                        <label for="clave">Rol:</label>
                        <select class="form-control" placeholder="Ingrese la contraseña" required name="rol" id="rol">
                        <option value="Owner">Owner</option>
                        <option value="Co-Owner">Co-Owner</option>
                        <option value="SuperAdmin">SuperAdmin</option>
                        <option value="Admin" selected>Admin</option>
                        </select>                   
                        </div>';
                        echo $acciones;
                    } elseif ($resultu["rol"] == "Co-Owner") {
                        $acciones = '
                        <div class="form-group">
                        <label for="clave">Rol:</label>
                        <select class="form-control" placeholder="Ingrese la contraseña" required name="rol" id="rol">
                        
                        <option value="Co-Owner">Co-Owner</option>
                        <option value="SuperAdmin">SuperAdmin</option>
                        <option value="Admin" selected>Admin</option>
                        </select>                   
                        </div>';
                        echo $acciones;
                    } elseif ($resultu["rol"] == "SuperAdmin") {
                        $acciones = '
                        <div class="form-group">
                        <label for="clave">Rol:</label>
                        <select class="form-control" placeholder="Ingrese la contraseña" required name="rol" id="rol">
                        
                        <option value="SuperAdmin">SuperAdmin</option>
                        <option value="Admin">Admin</option>
                        </select>                   
                        </div>';
                        echo $acciones;
                    } elseif ($resultu["rol"] == "Admin") {
                        $acciones = '
                        <div class="form-group">
                        <label for="clave">Rol:</label>
                        <select class="form-control" placeholder="Ingrese la contraseña" required name="rol" id="rol">
                        
                        <option value="Admin" selected>Admin</option>
                        </select>                   
                        </div>';
                        echo $acciones;
                    }
                    ?>
                    <div class="form-group">
                        <button class="btn btn-primary" id="btn" type="submit"><i class="fas fa-plus"></i> Crear Administrador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php if (isset($_REQUEST['registro'])) {
    if ($_REQUEST['registro'] == 'success') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>✓Perfecto✓</strong> Los datos del administrador se han almacenado exitosamente
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
    }
}

?>
<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>Nro</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Correo Institucional</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";


            if ($result > 0) {
                $counter = 0;
                if ($resultu["rol"] == "Owner") {
                    while ($data = mysqli_fetch_assoc($query)) {

                        if ($idusuario = $data['idusuario'] == $id_user) {
                            $BotonEliminar = "";
                        } else {
                            $BotonEliminar = '<form action="eliminar_usuario.php?id=' . $data['idusuario'] . '" method="post" class="confirmar d-inline">
                        <button class="btn btn-danger" type="submit"><i class="fas fa-eye-slash"></i> </button>
                        </form> ';
                        }
                        if ($data['estado'] == 1) {
                            $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                            $acciones = '
                            <a href="rol.php?id=' . urlencode(base64_encode($data['idusuario'])) . '" class="btn btn-warning"><i class="fas fa-clipboard-list"></i></a>
                            <a href="editar_usuario.php?id=' . urlencode(base64_encode($data['idusuario'])) . '" class="btn btn-success"><i class="fas fa-user-edit"></i></a>';
                        } else {
                            $acciones = '';
                            $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                            $BotonEliminar = '<a href="activar_administrador.php?id=' . $data['idusuario'] . '" class="btn btn-warning"><i class="fas fa-eye"></i></a>';
                        }

                        $counter++;
                        $tabla = '
                <tr>
                <td>' . $counter . '</td>
                <td>' . $data['usuario'] . '</td>
                <td>' . $data['nombre'] . '</td>
                <td>' . $data['correo'] . '</td>
                <td>' . $estado . '</td>
                <td>
                ' . $acciones . $BotonEliminar . '
                </td>
                </tr>';
                        echo $tabla;
                    }
                } elseif ($resultu["rol"] == "Co-Owner") 
                {
                    $query1 = mysqli_query($conexion, "SELECT * FROM usuario WHERE rol != 'Owner' AND rol != 'Co-Owner'");
                    $counter++;
                    $query2 = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = '" . $id_user . "'");
                    $data2 = mysqli_fetch_assoc($query2);
                    if ($data2['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                        $acciones = '
                            <a href="rol.php?id=' . urlencode(base64_encode($data2['idusuario'])) . '" class="btn btn-warning"><i class="fas fa-clipboard-list"></i></a>
                            <a href="editar_usuario.php?id=' . urlencode(base64_encode($data2['idusuario'])) . '" class="btn btn-success"><i class="fas fa-user-edit"></i></a>';
                    } else {
                        $acciones = '';
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                        $BotonEliminar = '<a href="activar_administrador.php?id=' . $data2['idusuario'] . '" class="btn btn-warning"><i class="fas fa-eye"></i></a>';
                    
                    }   
                   
                    $tabla = '
                        <tr>
                        <td>' . $counter . '</td>
                        <td>' . $data2['usuario'] . '</td>
                        <td>' . $data2['nombre'] . '</td>
                        <td>' . $data2['correo'] . '</td>
                        <td>' . $estado . '</td>
                        <td>
                        ' . $acciones . $BotonEliminar.'
                        </td>
                        </tr>';
                    echo $tabla;

                    while ($data1 = mysqli_fetch_assoc($query1)) {

                        if ($idusuario = $data1['idusuario'] == $id_user) {
                            $BotonEliminar = "";
                        } else {
                            $BotonEliminar = '<form action="eliminar_usuario.php?id=' . $data1['idusuario'] . '" method="post" class="confirmar d-inline">
                        <button class="btn btn-danger" type="submit"><i class="fas fa-eye-slash"></i> </button>
                        </form> ';
                        }
                        if ($data1['estado'] == 1) {
                            $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                            $acciones = '
                            <a href="rol.php?id=' . urlencode(base64_encode($data1['idusuario'])) . '" class="btn btn-warning"><i class="fas fa-clipboard-list"></i></a>
                            <a href="editar_usuario.php?id=' . urlencode(base64_encode($data1['idusuario'])) . '" class="btn btn-success"><i class="fas fa-user-edit"></i></a>';
                        } else {
                            $acciones = '';
                            $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                            $BotonEliminar = '<a href="activar_administrador.php?id=' . $data1['idusuario'] . '" class="btn btn-warning"><i class="fas fa-eye"></i></a>';
                        }

                        $counter++;
                        $tabla1 = '
                <tr>
                <td>' . $counter . '</td>
                <td>' . $data1['usuario'] . '</td>
                <td>' . $data1['nombre'] . '</td>
                <td>' . $data1['correo'] . '</td>
                <td>' . $estado . '</td>
                <td>
                ' . $acciones . $BotonEliminar . '
                </td>
                </tr>';
                        echo $tabla1;
                    }
                    
                } elseif ($resultu["rol"] == "SuperAdmin") {
                    $query1 = mysqli_query($conexion, "SELECT * FROM usuario WHERE rol != 'Owner' AND rol != 'Co-Owner'  AND rol != 'SuperAdmin'");

                    $counter++;
                    $query2 = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = '" . $id_user . "'");
                    $data2 = mysqli_fetch_assoc($query2);
                    if ($data2['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                        $acciones = '
                            <a href="rol.php?id=' . urlencode(base64_encode($data2['idusuario'])) . '" class="btn btn-warning"><i class="fas fa-clipboard-list"></i></a>
                            <a href="editar_usuario.php?id=' . urlencode(base64_encode($data2['idusuario'])) . '" class="btn btn-success"><i class="fas fa-user-edit"></i></a>';
                    } else {
                        $acciones = '';
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                        $BotonEliminar = '<a href="activar_administrador.php?id=' . $data2['idusuario'] . '" class="btn btn-warning"><i class="fas fa-eye"></i></a>';
                    }

                    $tabla = '
                        <tr>
                        <td>' . $counter . '</td>
                        <td>' . $data2['usuario'] . '</td>
                        <td>' . $data2['nombre'] . '</td>
                        <td>' . $data2['correo'] . '</td>
                        <td>' . $estado . '</td>
                        <td>
                        ' . $acciones . $BotonEliminar . '
                        </td>
                        </tr>';
                    echo $tabla;

                    while ($data1 = mysqli_fetch_assoc($query1)) {

                        if ($idusuario = $data1['idusuario'] == $id_user) {
                            $BotonEliminar = "";
                        } else {
                            $BotonEliminar = '<form action="eliminar_usuario.php?id=' . $data1['idusuario'] . '" method="post" class="confirmar d-inline">
                        <button class="btn btn-danger" type="submit"><i class="fas fa-eye-slash"></i> </button>
                        </form> ';
                        }
                        if ($data1['estado'] == 1) {
                            $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                            $acciones = '
                            <a href="rol.php?id=' . urlencode(base64_encode($data1['idusuario'])) . '" class="btn btn-warning"><i class="fas fa-clipboard-list"></i></a>
                            <a href="editar_usuario.php?id=' . urlencode(base64_encode($data1['idusuario'])) . '" class="btn btn-success"><i class="fas fa-user-edit"></i></a>';
                        } else {
                            $acciones = '';
                            $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                            $BotonEliminar = '<a href="activar_administrador.php?id=' . $data1['idusuario'] . '" class="btn btn-warning"><i class="fas fa-eye"></i></a>';
                        }

                        $counter++;
                        $tabla1 = '
                <tr>
                <td>' . $counter . '</td>
                <td>' . $data1['usuario'] . '</td>
                <td>' . $data1['nombre'] . '</td>
                <td>' . $data1['correo'] . '</td>
                <td>' . $estado . '</td>
                <td>
                ' . $acciones . $BotonEliminar . '
                </td>
                </tr>';
                        echo $tabla1;
                    }
                } elseif ($resultu["rol"] == "Admin") {
                    $query1 = mysqli_query($conexion, "SELECT * FROM usuario WHERE rol != 'Owner' AND rol != 'Co-Owner' AND rol != 'SuperAdmin'  AND rol != 'Admin'");

                    $counter++;
                    $query2 = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = '" . $id_user . "'");
                    $data2 = mysqli_fetch_assoc($query2);
                    $acciones = '
                                <a href="rol.php?id=' . urlencode(base64_encode($data2['idusuario'])) . '" class="btn btn-warning"><i class="fas fa-clipboard-list"></i></a>
                                <a href="editar_usuario.php?id=' . urlencode(base64_encode($data2['idusuario'])) . '" class="btn btn-success"><i class="fas fa-user-edit"></i></a>';
                    $tabla = '
                                <tr>
                                <td>' . $counter . '</td>
                                <td>' . $data2['usuario'] . '</td>
                                <td>' . $data2['nombre'] . '</td>
                                <td>' . $data2['correo'] . '</td>
                                <td>' . $estado . '</td>
                                <td>
                                ' . $acciones . '
                                </td>
                                </tr>';
                    echo $tabla;

                    while ($data1 = mysqli_fetch_assoc($query1)) {
                        $counter++;
                        if ($data['estado'] == 0) {
                            $BotonEliminar = '';
                        } else {
                            $BotonEliminar =
                                '<form action="activar_producto.php?id="' . $data['idusuario'] . '" method="post" class="activar d-inline">
                                    <button class="btn btn-warning" type="submit"><i class="fas fa-eye"></i> </button>
                                </form>';
                        }

                        $acciones = '
                                    <a href="rol.php?id=' . urlencode(base64_encode($data1['idusuario'])) . '" class="btn btn-warning"><i class="fas fa-clipboard-list"></i></a>
                                    <a href="editar_usuario.php?id=' . urlencode(base64_encode($data1['idusuario'])) . '" class="btn btn-success"><i class="fas fa-user-edit"></i></a>';

                        $tabla1 = '
                                    <tr>
                                    <td>' . $counter . '</td>
                                    <td>' . $data1['usuario'] . '</td>
                                    <td>' . $data1['nombre'] . '</td>
                                    <td>' . $data1['correo'] . '</td>
                                    <td>' . $estado . '</td>
                                    <td>
                                    ' . $acciones . $BotonEliminar . '
                                    </td>
                                    </tr>';
                        echo $tabla1;
                    }
                }
            }
            ?>
        </tbody>
    </table>
</div>


<script>
    const userInput = document.getElementById('usuario');
    const userRequirements = document.getElementById('user-requirements');
    const passwordInput = document.getElementById('clave');
    const passwordRequirements = document.getElementById('password-requirements');
    const saveButton = document.getElementById('btn');
    const correoInput = document.getElementById('correo');
    const resultado = document.getElementById('resultado');

    function checkUserRequirements() {
        const user = userInput.value;
        let requirements = [];

        if (user.length < 5) {
            requirements.push('<span style="color: red;">&#x2717;</span> El usuario debe tener al menos 5 caracteres.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">El usuario debe tener al menos 5 caracteres.</span>');
        }

        if (!/[A-Z]/.test(user) || !/[a-z]/.test(user)) {
            requirements.push('<span style="color: red;">&#x2717;</span> El usuario debe tener al menos una mayúscula y minúscula.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">El usuario debe tener al menos una mayúscula y minúscula.</span>');
        }

        if (!/\d/.test(user)) {
            requirements.push('<span style="color: red;">&#x2717;</span> El usuario debe tener al menos un número.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">El usuario debe tener al menos un número.</span>');
        }

        if (!/\W/.test(user)) {
            requirements.push('<span style="color: red;">&#x2717;</span> El usuario debe tener al menos un símbolo.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">El usuario debe tener al menos un símbolo.</span>');
        }

        userRequirements.innerHTML = requirements.join('<br>');
        validarFormulario();
    }

    function checkPasswordRequirements() {
        const password = passwordInput.value;
        let requirements = [];

        if (password.length < 12) {
            requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos 12 caracteres.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos 12 caracteres.</span>');
        }

        if (!/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
            requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos una mayúscula y minúscula.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos una mayúscula y minúscula.</span>');
        }

        if (!/\d/.test(password)) {
            requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos un número.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos un número.</span>');
        }

        if (!/\W/.test(password)) {
            requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos un símbolo.');
        } else {
            requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos un símbolo.</span>');
        }

        passwordRequirements.innerHTML = requirements.join('<br>');
        validarFormulario();
    }

    function validarFormulario() {
        const userValid = userInput.value.length >= 6;
        const passwordValid = passwordInput.value.length >= 12;
        const correoValido = validarCorreo();

        saveButton.disabled = !(userValid && passwordValid && correoValido);
    }

    function validarCorreo() {
        const correo = correoInput.value.trim();
        const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

        if (!regex.test(correo)) {
            resultado.innerHTML = '<span style="color: red;">&#x2717;</span> El correo electrónico no tiene un formato válido';
            return false;
        }

        resultado.innerHTML = '<span style="color: green;">&#x2713;</span> El correo electrónico es válido';
        return true;
    }

    userInput.addEventListener('input', checkUserRequirements);
    passwordInput.addEventListener('input', checkPasswordRequirements);
    correoInput.addEventListener('input', validarFormulario);
    saveButton.addEventListener('click', validarFormulario);
</script>


<?php include_once "includes/footer.php"; ?>