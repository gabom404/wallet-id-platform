<?php include_once "includes/header.php";

include "../conexion.php";

$id_user = $_SESSION['idUser'];

$permiso = "Comunidad Educativa";

$sql = safe_query($conexion, 'SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?', [$id_user, $permiso]);

$existe = mysqli_fetch_all($sql);

if (empty($existe) && $id_user != 1) {

    header("Location: permisos.php");

}

if (isset($_POST['crear_cliente'])) {

    $alert = "";

    if (

        empty($_POST['full_name'])

        ||  empty($_POST['id_code'])

        || empty($_POST['email'])

        || empty($_POST['grade'])

        || empty($_POST['section'])

        || empty($_POST['specialty'])

        || empty($_POST['telephone_number'])

        || empty($_POST['password'])

        || empty($_POST['responsible_names'])

        || empty($_POST['responsible_phones'])

        || empty($_POST['responsible_emails'])

        || empty($_POST['date_birth'])

        || empty($_POST['user_type'])

    ) {

        $alert = '<div class="alert alert-warning" role="alert">

                                    ¡Completa los campos vacíos!

                                </div>';

    } else {

        if (isset($_POST['crear_cliente'])) {

            $nombre = $_POST['full_name'];

            $codigo = $_POST['id_code'];

            $email = $_POST['email'];

            // $usuario_id = $_SESSION['idUser'];

            $grade = $_POST['grade'];

            $section = $_POST['section'];

            // $list_number = $_POST['list_number'];

            $specialty = $_POST['specialty'];

            // $gender = $_POST['gender'];

            $telephone_number = $_POST['telephone_number'];

            $password = hash("sha512", $_POST['password']);

            $responsible_names = $_POST['responsible_names'];

            // $dui = $_POST['dui'];

            $responsible_emails = $_POST['responsible_emails'];

            $responsible_phones = $_POST['responsible_phones'];

            /*             $nationality = $_POST['nationality'];

 */

            $datebirth = $_POST['date_birth'];

            /*             $address = $_POST['address'];

 */

            $user_type = $_POST['user_type'];

            /*             $municipality = $_POST['city'];

 */           /*  $department = $_POST['department']; */

            $state = '1';

 

            $result = 0;

            $query = safe_query($conexion, 'SELECT * FROM users WHERE full_name = ?', [$nombre]);

            $result = mysqli_fetch_array($query);

            if ($result > 0) {

                $alert = '<div class="alert alert-danger" role="alert">

                                    ¡El cliente ya existe!

                                </div>';

            } else {

                //, image, user_type

                if ($_FILES["image"]["name"] != '') {

                    $image = img();

                    $query_insert = safe_query($conexion, 'INSERT INTO users(full_name, id_code, email, grade, section, specialty, telephone_number, password, responsible_names,responsible_phones, responsible_emails, date_birth, state, image, user_type)

            values (?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?)', [$nombre, $codigo, $email, $grade, $section, $specialty, $telephone_number, $password, $responsible_names, $responsible_phones, $responsible_emails, $datebirth, $state, $image, $user_type]);

                    if ($query_insert) {

                        $alert = '<div class="alert alert-success" role="alert">

                                    ¡Cliente registrado!

                                </div>';

                    } else {

                        $alert = '<div class="alert alert-danger" role="alert">

                                    ¡Error al registrar!

                            </div>';

                    }

                } else {

                    $query_insert = safe_query($conexion, 'INSERT INTO users(full_name, id_code, email, grade, section, specialty, telephone_number, password, responsible_names,responsible_phones, responsible_emails, date_birth,state, image, user_type)

            values (?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, \'img/default-profile-picture.png\' ,?)', [$nombre, $codigo, $email, $grade, $section, $specialty, $telephone_number, $password, $responsible_names, $responsible_phones, $responsible_emails, $datebirth, $state, $user_type]);

                    if ($query_insert) {

                        $alert = '<div class="alert alert-success" role="alert">

                                    ¡Cliente registrado!

                                </div>';

                    } else {

                        $alert = '<div class="alert alert-danger" role="alert">

                                    ¡Error al registrar!

                            </div>';

                    }

                }

            }

        }

    }

    mysqli_close($conexion);

}

 

function img()

{

    if (isset($_FILES["image"])) {

        $data = ['uploadimageType' => strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION))];

        $id_code =  $_POST['id_code'];

        $destination = '../assets/picture/' . $id_code . '.' . $data['uploadimageType'];

        $move_destino = 'picture/' . $id_code . '.' . $data['uploadimageType'];

        move_uploaded_file($_FILES['image']['tmp_name'], $destination);

        return $move_destino;

    }

}

?>

<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#nuevo_cliente"><i class="fas fa-plus"></i></button>

<?php echo isset($alert) ? $alert : ''; ?>

<div class="table-responsive">

    <table class="table table-striped table-bordered" id="tbl">

        <thead class="thead-dark">

            <tr>

                <th>Nro</th>

                <th>Nombre completo</th>

                <th>Código de carnet</th>

                <th>Correo institucional</th>

                <th>Grado</th>

                <th>Sección</th>

                <th>Especialidad</th>

                <th>Número de teléfono</th>

                <th>Nombre de responsable</th>

                <th>Numero de responsable</th>

                <th>Correo de responsable</th>

                <th>Fecha de nacimiento</th>

                <th>Imagen</th>

                <th>Tipo</th>

                <th>Estado</th>

                <th>Acciones</th>

            </tr>

        </thead>

        <tbody>

            <?php

            include "../conexion.php";

 

            $query = mysqli_query($conexion, "SELECT * FROM users");

            $result = mysqli_num_rows($query);

            if ($result > 0) {

                $counter = 0;

                while ($data = mysqli_fetch_assoc($query)) {

                    $counter++;

                    if ($data['state'] == 1) {

                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';

                    } else {

                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';

                    }

            ?>

                    <tr>

                        <td><?php echo $counter; ?></td>

                        <td><?php echo $data['full_name']; ?></td>

                        <td><?php echo $data['id_code']; ?></td>

                        <td><?php echo $data['email']; ?></td>

                        <td><?php echo $data['grade']; ?></td>

                        <td><?php echo $data['section']; ?></td>

                        <td><?php echo $data['specialty']; ?></td>

                        <td><?php echo $data['telephone_number']; ?></td>

                        <td><?php echo $data['responsible_names']; ?></td>

                        <td><?php echo $data['responsible_phones']; ?></td>

                        <td><?php echo $data['responsible_emails']; ?></td>

                        <td><?php echo $data['date_birth']; ?></td>

                        <td><img width="100px" src="../assets/<?php echo $data['image']; ?>" alt=""> </td>

                        <td><?php echo $data['user_type']; ?></td>

                        <td><?php echo $estado; ?></td>

                        <td>

                            <?php if ($data['state'] == 1) { ?>

                                <a href="editar_cliente.php?id=<?php echo $data['id']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_cliente.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">

                                    <button class="btn btn-danger" type="submit"><i class='fas fa-eye-slash'></i> </button>

                                </form>

                            <?php } ?>

                        </td>

                    </tr>

            <?php }

            } ?>

        </tbody>

    </table>

</div>

<div id="nuevo_cliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header bg-primary text-black">

                <h5 class="modal-title" id="my-modal-title">Crear Nuevo Miembro</h5>

                <button class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="" method="post" autocomplete="off" enctype="multipart/form-data">

                    <div class="form-group">

                        <label for="nombre">Nombre completo:</label>

                        <input type="text" placeholder="Ingrese el nombre completo" required name="full_name" id="full_name" class="form-control" minlength="7" maxlength="50">

                    </div>

                    <div class="form-group">

                        <label for="codigo">Código de carnet:</label>

                        <input type="text" placeholder="Ingrese el código de carnet" required name="id_code" id="id_code" class="form-control" minlength="4" maxlength="10">

                    </div>

                    <div class="form-group">

                        <label for="correo">Correo institucional:</label>

                        <input type="email" placeholder="Ingrese el correo institucional" required name="email" id="email" class="form-control" minlength="4" maxlength="50">

                    </div>

                    <div id="resultado" style="margin-bottom:.5rem; color: grey; font-size: 15px;"></div>

 

                    <div class="form-group">

                        <label for="grado">Grado:</label>

                        <select class="form-control" required name="grade" id="grade" size="1">

                            <option value="" disabled selected hidden>Selecciona el grado</option>

                            <optgroup label="Parvularia">

                                <option value="Kinder 4">Kinder 4°</option>

                                <option value="Kinder 5">Kinder 5°</option>

                                <option value="Kinder 6">Kinder 6°</option>

                            <optgroup label="Primaria">

                                <option value="1 Grado">1° Grado</option>

                                <option value="2 Grado">2° Grado</option>

                                <option value="3 Grado">3° Grado</option>

                            <optgroup label="Segundo Ciclo">

                                <option value="4 Grado">4° Grado</option>

                                <option value="5 Grado">5° Grado</option>

                                <option value="6 Grado">6° Grado</option>

                            <optgroup label="Tercer Ciclo">

                                <option value="7 Grado">7° Grado</option>

                                <option value="8 Grado">8° Grado</option>

                                <option value="9 Grado">9° Grado</option>

                            <optgroup label="Bachillerato">

                                <option value="1er">1° Año</option>

                                <option value="2er">2° Año</option>

                                <option value="3er">3° Año</option>

                            </optgroup>

                            <!-- </optgroup>

                            </optgroup>

                            </optgroup>

                            </optgroup> -->

                        </select>

                    </div>

                    <div class="form-group">

                        <label for="section">Sección:</label>

                        <select class="form-control" required name="section" id="section" size="1">

                            <option value="" disabled selected hidden>Selecciona la sección</option>

                            <option value="A">A</option>

                            <option value="B">B</option>

                            <option value="C">C</option>

                        </select>

                    </div>

 

                    <div class="form-group">

                        <label for="especialty">Especialidad:</label>

                        <select class="form-control" required name="specialty" id="specialty" size="1">

                            <option value="" disabled selected hidden>Selecciona la especialidad</option>

                            <option value="General">General</option>

                            <option value="INFO">Sistemas Informáticos</option>

                            <option value="EMCA">Electromecánica</option>

                            <option value="Mecatronica">Mecatronica</option>

                            <option value="Salud">Salud</option>

                            <option value="Diseno Grafico">Diseño Gráfico</option>

                        </select>

                    </div>

 

                    <div class="form-group">

                        <label for="telefono">Número de teléfono:</label>

                        <input type="number" placeholder="Ingrese el número de teléfono" required name="telephone_number" id="telephone_number" class="form-control">

                    </div>

                    <div class="form-group">

                        <label for="contraseña">Contraseña:</label>

                        <input type="password" placeholder="Ingrese la contraseña" required name="password" id="password" class="form-control">

                    </div>

                    <div id="password-requirements" style="margin-bottom:.5rem; color: grey; font-size: 15px;"></div>

 

                    <div class="form-group">

                        <label for="nombreResponsable">Nombre del responsable:</label>

                        <input type="text" placeholder="Ingrese el nombre del responsable" required name="responsible_names" id="responsible_names" class="form-control">

                    </div>

 

                    <div class="form-group">

                        <label for="telefono">Número de teléfono del responsable:</label>

                        <input type="number" placeholder="Ingrese el número de teléfono del responsable" required name="responsible_phones" id="responsible_phones" class="form-control">

                    </div>

 

                    <div class="form-group">

                        <label for="gmail">Correo electrónico del responsable</label>

                        <input type="email" placeholder="Ingrese el correo electrónico del responsable" required name="responsible_emails" id="responsible_emails" class="form-control">

                    </div>

 

                    <div class="form-group">

                        <label for="fecha">Fecha de nacimiento:</label>

                        <input type="date" placeholder="Ingrese la fecha de nacimiento" required name="date_birth" id="date_birth" class="form-control">

                    </div>

 

                    <div class="form-group">

                        <label for="foto">Foto de perfil del cliente:</label>

                        <div id="imagePreview">

                            <img src="../assets/img/default-profile-picture.png" height="150" width="150" alt=""><br><br>

                        </div>

                        <input type="file" name="image" id="image" onchange="return fileValidation()" accept="image/jpg, image/png, image/jpeg" class="form-control-file">

                    </div>

 

                    <div class="form-group">

                        <label for="user_type">Selecciona el tipo de cliente:</label>

                        <input type="text" required placeholder="Selecciona / Ingresa el tipo de cliente" class="form-control" name="user_type" list="user_type">

                        <datalist id="user_type">

                            <option value="Estudiante">Estudiante</option>

                            <option value="Docente">Docente</option>

                            <option value="N/A">N/A</option>

                        </datalist>

                    </div>

                    <div class="form-group">

                        <button class="btn btn-primary" name="crear_cliente" id="btn" type="submit"><i class="fas fa-plus"></i> Crear Miembro</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

 

<script>

    const userInput = document.getElementById('usuario');

    const userRequirements = document.getElementById('user-requirements');

    const passwordInput = document.getElementById('clave');

    const passwordRequirements = document.getElementById('password-requirements');

    const saveButton = document.getElementById('btn');

    const correoInput = document.getElementById('correo');

    const resultado = document.getElementById('resultado');

 

 

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

 

    userInput.addEventListener('input', checkUserRequirements);

    passwordInput.addEventListener('input', checkPasswordRequirements);

    correoInput.addEventListener('input', validarFormulario);

    saveButton.addEventListener('click', validarFormulario);

</script>

<script>

    function fileValidation() {

        var fileInput =

            document.getElementById('image');

 

        var filePath = fileInput.value;

 

        // Allowing file type

        var allowedExtensions =

            /(\.png|\.jpg|\.jpeg)$/i;

 

        if (!allowedExtensions.exec(filePath)) {

            Swal.fire({

                position: 'top-end',

                icon: 'error',

                title: 'Por favor seleccione una imagen PNG',

                showConfirmButton: false,

                timer: 1500000000000

            })

            fileInput.value = '';

            return false;

        } else {

 

            // Image preview

            if (fileInput.files && fileInput.files[0]) {

                var reader = new FileReader();

                reader.onload = function(e) {

                    document.getElementById(

                            'imagePreview').innerHTML =

                        '<img height="150" width="150" src="' + e.target.result +

                        '"/><br><br>';

                };

 

                reader.readAsDataURL(fileInput.files[0]);

            }

        }

    }

</script>

 

<?php include_once "includes/footer.php"; ?>