<?php include_once "includes/header.php";

include "../conexion.php";

$id_user = $_SESSION['idUser'];

$permiso = "Comunidad Educativa";

$sql = safe_query($conexion, 'SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?', [$id_user, $permiso]);

$existe = mysqli_fetch_all($sql);

if (empty($existe) && $id_user != 1) {

    header("Location: permisos.php");
}

if (isset($_POST['editar_cliente'])) {

    $alert = "";

    if (

        empty($_POST['full_name'])

        ||  empty($_POST['id_code'])

        || empty($_POST['email'])

        || empty($_POST['grade'])

        || empty($_POST['section'])

        || empty($_POST['specialty'])

        || empty($_POST['telephone_number'])

        /*         || empty($_POST['password'])

 */        || empty($_POST['responsible_names'])

        || empty($_POST['responsible_phones'])

        || empty($_POST['responsible_emails'])

        || empty($_POST['date_birth'])

        || empty($_POST['user_type'])

    ) {

        $alert = '<div class="alert alert-warning" role="alert">¡Todo los campos son requeridos!</div>';
    } elseif (isset($_POST['editar_cliente'])) {

        $idcliente = $_POST['id'];

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

        /*         $password = hash("sha512", $_POST['password']);

 */

        $responsible_names = $_POST['responsible_names'];

        // $dui = $_POST['dui'];

        $responsible_emails = $_POST['responsible_emails'];

        $responsible_phones = $_POST['responsible_phones'];

        /*             $nationality = $_POST['nationality'];

 */

        $datebirth = $_POST['date_birth'];

        /*             $address = $_POST['address'];

 */

        /*             $municipality = $_POST['city'];

 */           /*  $department = $_POST['department']; */

        $state = '1';

        $usertype = $_POST['user_type'];





        $sql_update = safe_query($conexion, 'UPDATE users SET

                full_name = ?,

                id_code = ?,

                email = ? ,

                grade = ?,

                section = ? ,

                specialty = ? ,

                telephone_number = ? ,

                responsible_names = ?,

                responsible_phones = ?,

                responsible_emails = ?,

                date_birth = ?

                user_type = ?

                WHERE id = ?', [$nombre, $codigo, $email, $grade, $section, $specialty, $telephone_number, $responsible_names, $responsible_phones, $responsible_emails, $datebirth, $usertype, $idcliente]);



        if ($sql_update) {

            $alert = '<div class="alert alert-success" role="alert">¡Cliente actualizado correctamente!</div>';
        } else {

            $alert = '<div class="alert alert-danger" role="alert">¡Error al actualizar el cliente!</div>';
        }
    }
}

// Mostrar Datos



if (empty($_REQUEST['id'])) {

    header("Location: clientes.php");
}

$idcliente = $_REQUEST['id'];

$sql = safe_query($conexion, 'SELECT * FROM users WHERE id = ?', [$idcliente]);

$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {

    header("Location: clientes.php");
} else {

    if ($data = mysqli_fetch_array($sql)) {

        // $idcliente = $data['idcliente'];

        // $nombre = $data['nombre'];

        // $telefono = $data['telefono'];

        // $direccion = $data['direccion'];

        $nombre = $data['full_name'];

        $codigo = $data['id_code'];

        $email = $data['email'];

        // $usuario_id = $_SESSION['idUser'];

        $grade = $data['grade'];

        $section = $data['section'];

        $specialty = $data['specialty'];

        $telephone_number = $data['telephone_number'];

        $password = $data['password'];

        $responsible_names = $data['responsible_names'];

        $responsible_emails = $data['responsible_emails'];

        $responsible_phones = $data['responsible_phones'];

        $datebirth = $data['date_birth'];

        // $state = $data['state'];
        $usertype = $data['user_type'];

    }
}

?>

<!-- Begin Page Content -->

<div class="container-fluid">



    <div class="row">

        <div class="col-lg-6 m-auto">

            <div class="card">

                <div class="modal-header bg-primary text-black">

                    <h5 class="modal-title" id="my-modal-title">Actualizar Miembro Institucional</h5>

                </div>

                <div class="card-body">

                    <form class="" action="" method="post">

                        <?php echo isset($alert) ? $alert : ''; ?>

                        <input type="hidden" name="id" value="<?php echo $idcliente; ?>">

                        <!-- <div class="form-group">

                            <label for="nombre">Nombre</label>

                            <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">

                        </div>

                        <div class="form-group">

                            <label for="telefono">Teléfono</label>

                            <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">

                        </div>

                        <div class="form-group">

                            <label for="direccion">Dirección</label>

                            <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">

                        </div> -->

                        <div class="form-group">

                            <label for="nombre">Nombre completo:</label>

                            <input type="text" placeholder="Ingrese el nombre completo" name="full_name" id="full_name" class="form-control" value="<?php echo $nombre; ?>">

                        </div>

                        <div class="form-group">

                            <label for="nombre">Código de carnet:</label>

                            <input type="text" placeholder="Ingrese el código de carnet" name="id_code" id="id_code" class="form-control" value="<?php echo $codigo; ?>">

                        </div>

                        <div class="form-group">

                            <label for="nombre">Correo institucional:</label>

                            <input type="mail" placeholder="Ingrese el correo institucional" name="email" id="email" class="form-control" value="<?php echo $email; ?>">

                        </div>

                        <div class="form-group">

                            <label for="nombre">Grado:</label>

                            <!-- <input type="text" placeholder="Ingrese el grado" name="grade" id="grade" class="form-control" value="<?php echo $grade; ?>"> -->
                            <select class="form-control" required name="grade" id="grade" size="1">


                                <?php
                                switch ($grade) {

                                        ///////Bachillerato////////////

                                    case $grade == "3er":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option selected value="3er">3° Año</option>';
                                        break;

                                    case $grade == "2do":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option selected value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "1er":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option selected value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;

                                        ///////3erciclo//////

                                    case $grade == "9 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option selected value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "8 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option selected value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "7 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option selected value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;

                                        ////////SegundoCiclo////////////

                                    case $grade == "6 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option selected value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "5 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                        <option selected value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "4 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option selected value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                        break;
                                        /////////////PrimerCiclo/////////////
                                    case $grade == "3 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                            <option value="Kinder 5">Kinder 5°</option>
                                            <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                            <option value="2 Grado">2° Grado</option>
                                            <option selected value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                            <option value="5 Grado">5° Grado</option>
                                            <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                            <option value="8 Grado">8° Grado</option>
                                            <option value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                            <option value="2do">2° Año</option>
                                            <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "2 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                            <option value="Kinder 5">Kinder 5°</option>
                                            <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                            <option selected value="2 Grado">2° Grado</option>
                                            <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                            <option value="5 Grado">5° Grado</option>
                                            <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                            <option value="8 Grado">8° Grado</option>
                                            <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                            <option value="2do">2° Año</option>
                                            <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "1 Grado":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                            <option value="Kinder 5">Kinder 5°</option>
                                            <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option selected value="1 Grado">1° Grado</option>
                                            <option value="2 Grado">2° Grado</option>
                                            <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option  value="4 Grado">4° Grado</option>
                                            <option value="5 Grado">5° Grado</option>
                                            <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                            <option value="8 Grado">8° Grado</option>
                                            <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                            <option value="2do">2° Año</option>
                                            <option value="3er">3° Año</option>';
                                        break;
                                        ///////////////Kinder///////////////
                                    case $grade == "Kinder 6":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                                <option value="Kinder 5">Kinder 5°</option>
                                                <option selected value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                                <option value="2 Grado">2° Grado</option>
                                                <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                                <option value="5 Grado">5° Grado</option>
                                                <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                                <option value="8 Grado">8° Grado</option>
                                                <option value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                                <option value="2do">2° Año</option>
                                                <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "Kinder 5":
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                                <option selected value="Kinder 5">Kinder 5°</option>
                                                <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                                <option value="2 Grado">2° Grado</option>
                                                <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option value="4 Grado">4° Grado</option>
                                                <option value="5 Grado">5° Grado</option>
                                                <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                                <option value="8 Grado">8° Grado</option>
                                                <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                                <option value="2do">2° Año</option>
                                                <option value="3er">3° Año</option>';
                                        break;

                                    case $grade == "Kinder 4":
                                        $opcion5 = '<option selected value="Kinder 4">Kinder 4°</option>
                                                <option value="Kinder 5">Kinder 5°</option>
                                                <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                                <option value="2 Grado">2° Grado</option>
                                                <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option  value="4 Grado">4° Grado</option>
                                                <option value="5 Grado">5° Grado</option>
                                                <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                                <option value="8 Grado">8° Grado</option>
                                                <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                                <option value="2do">2° Año</option>
                                                <option value="3er">3° Año</option>';
                                        break;

                                    default:
                                        $opcion6 = '<option value="" disabled selected hidden>Selecciona el grado</option>';
                                        $opcion5 = '<option value="Kinder 4">Kinder 4°</option>
                                        <option value="Kinder 5">Kinder 5°</option>
                                        <option value="Kinder 6">Kinder 6°</option>';
                                        $opcion4 = '<option value="1 Grado">1° Grado</option>
                                        <option value="2 Grado">2° Grado</option>
                                        <option value="3 Grado">3° Grado</option>';
                                        $opcion3 = '<option  value="4 Grado">4° Grado</option>
                                        <option value="5 Grado">5° Grado</option>
                                        <option value="6 Grado">6° Grado</option>';
                                        $opcion2 = '<option value="7 Grado">7° Grado</option>
                                        <option value="8 Grado">8° Grado</option>
                                        <option  value="9 Grado">9° Grado</option>';
                                        $opcion1 = '<option value="1er">1° Año</option>
                                        <option value="2do">2° Año</option>
                                        <option value="3er">3° Año</option>';
                                }
                                ?>


                                    <?php
                                    echo $opcion6;
                                    ?>
                                <optgroup label="Parvularia">

                                    <?php
                                    echo $opcion5;
                                    ?>

                                <optgroup label="Primaria">

                                    <?php
                                    echo $opcion4;
                                    ?>

                                <optgroup label="Segundo Ciclo">

                                    <?php
                                    echo $opcion3;
                                    ?>

                                <optgroup label="Tercer Ciclo">

                                    <?php
                                    echo $opcion2;
                                    ?>
                                <optgroup label="Bachillerato">
                                    <?php
                                    echo $opcion1;
                                    ?>

                                </optgroup>

                            </select>

                        </div>

                        <div class="form-group">

                            <label for="nombre">Sección:</label>

                            <!-- <input type="text" placeholder="Ingrese la sección" name="section" id="section" class="form-control" value="<?php echo $section; ?>"> -->
                            <select class="form-control" required name="grade" id="grade" size="1">


                                <?php
                                switch ($section) {

                                        ///////Bachillerato////////////

                                    case $section == "A":
                                        $opcion7 =  '<option selected value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>';
                                        break;

                                    case $section == "B":
                                        $opcion7 = '<option value="A">A</option>
                                        <option selected value="B">B</option>
                                        <option value="C">C</option>';
                                        break;

                                    case $section == "C":
                                        $opcion7 = '<option value="A">A</option>
                                        <option value="B">B</option>
                                        <option selected value="C">C</option>';
                                        break;

                                    default:
                                        $opcion8 = '<option select value="" disabled selected hidden>Selecciona la sección</option>';
                                        $opcion7 = '<option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>';
                                    }
                                ?>


                                    <?php
                                    echo $opcion8;
                                    ?>
                                <optgroup label="Sección">

                                    <?php
                                    echo $opcion7;
                                    ?>

                                </optgroup>

                            </select>

                        </div>

                        <!--  <div class="form-group">

                            <label for="nombre">Número de lista:</label>

                            <input type="text" placeholder="Ingrese el número de lista" name="list_number" id="list_number" class="form-control" value="<?php echo $list_number; ?>">

                        </div> -->

                        <div class="form-group">

                            <label for="nombre">Especialidad:</label>

                            <!-- <input type="text" placeholder="Ingrese la especialidad" name="specialty" id="specialty" class="form-control" value="<?php echo $specialty;     ?>"> -->
                             <select class="form-control" required name="grade" id="grade" size="1">


                                <?php
                                switch ($specialty) {

                                        ///////Bachillerato////////////

                                    case $specialty == "General":
                                        $opcion9 =  '<option selected value="General">General</option>
                                        <option value="INFO">Sistemas Informáticos</option>
                                        <option value="EMCA">Electromecánica</option>
                                        <option value="Mecatronica">Mecatronica</option>
                                        <option value="Salud">Salud</option>
                                        <option value="Diseno Grafico">Diseno Grafico</option>';
                                        break;

                                    case $specialty == "INFO":
                                        $opcion9 =  '<option value="General">General</option>
                                        <option selected value="INFO">Sistemas Informáticos</option>
                                        <option value="EMCA">Electromecánica</option>
                                        <option value="Mecatronica">Mecatronica</option>
                                        <option value="Salud">Salud</option>
                                        <option value="Diseno Grafico">Diseno Grafico</option>';
                                        break;

                                    case $specialty == "EMCA":
                                        $opcion9 =  '<option value="General">General</option>
                                        <option value="INFO">Sistemas Informáticos</option>
                                        <option selected value="EMCA">Electromecánica</option>
                                        <option value="Mecatronica">Mecatronica</option>
                                        <option value="Salud">Salud</option>
                                        <option value="Diseno Grafico">Diseno Grafico</option>';
                                        break;
                                    case $specialty == "Mecatronica":
                                        $opcion9 =  '<option value="General">General</option>
                                        <option value="INFO">Sistemas Informáticos</option>
                                        <option value="EMCA">Electromecánica</option>
                                        <option selected value="Mecatronica">Mecatronica</option>
                                        <option value="Salud">Salud</option>
                                        <option value="Diseno Grafico">Diseno Grafico</option>';
                                        break;
                                    case $specialty == "Salud":
                                        $opcion9 =  '<option value="General">General</option>
                                        <option value="INFO">Sistemas Informáticos</option>
                                        <option value="EMCA">Electromecánica</option>
                                        <option value="Mecatronica">Mecatronica</option>
                                        <option selected value="Salud">Salud</option>
                                        <option value="Diseno Grafico">Diseno Grafico</option>';
                                        break;
                                    case $specialty == "Diseno Grafico":
                                        $opcion9 =  '<option value="General">General</option>
                                        <option value="INFO">Sistemas Informáticos</option>
                                        <option value="EMCA">Electromecánica</option>
                                        <option value="Mecatronica">Mecatronica</option>
                                        <option value="Salud">Salud</option>
                                        <option selected value="Diseno Grafico">Diseno Grafico</option>';
                                        break;

                                    default:
                                        $opcion10 = '<option select value="" disabled selected hidden>Selecciona la Especialidad</option>';
                                        $opcion9 =  '<option value="General">General</option>
                                        <option value="INFO">Sistemas Informáticos</option>
                                        <option value="EMCA">Electromecánica</option>
                                        <option value="Mecatronica">Mecatronica</option>
                                        <option value="Salud">Salud</option>
                                        <option value="Diseno Grafico">Diseno Grafico</option>';
                                    }
                                ?>


                                    <?php
                                    echo $opcion10;
                                    ?>
                                <optgroup label="Especialidad">

                                    <?php
                                    echo $opcion9;
                                    ?>

                                </optgroup>

                            </select>
                        </div>

                        <!--  <div class="form-group">

                            <label for="nombre">Género:</label>

                            <input type="text" placeholder="Ingrese el género" name="gender" id="gender" class="form-control" value="<?php echo $gender; ?>">

                        </div> -->

                        <div class="form-group">

                            <label for="telefono">Número de teléfono:</label>

                            <input type="number" placeholder="Ingrese el número de teléfono" name="telephone_number" id="telephone_number" class="form-control" value="<?php echo $telephone_number; ?>">

                        </div>

                        <!--  <div class="form-group">

                            <label for="nombre">Contraseña:</label>

                            <input type="password" placeholder="Ingrese la contraseña" name="password" id="password" class="form-control" value="<?php echo $password; ?>">

                        </div> -->

                        <div class="form-group">

                            <label for="nombre">Nombre del responsable:</label>

                            <input type="text" placeholder="Ingrese el nombre del responsable" name="responsible_names" id="responsible_names" class="form-control" value="<?php echo $responsible_names; ?>">

                        </div>

                        <!-- <div class="form-group">

                            <label for="nombre">DUI:</label>

                            <input type="text" placeholder="Ingrese el DUI" name="dui" id="dui" class="form-control" value="<?php echo $dui; ?>">

                        </div> -->

                        <div class="form-group">

                            <label for="nombre">Correo electrónico del responsable</label>

                            <input type="text" placeholder="Ingrese el correo electrónico del resposable" name="responsible_emails" id="responsible_emails" class="form-control" value="<?php echo $responsible_emails; ?>">

                        </div>

                        <div class="form-group">

                            <label for="telefono">Número de teléfono del responsable:</label>

                            <input type="number" placeholder="Ingrese el número de teléfono del responsable" name="responsible_phones" id="responsible_phones" class="form-control" value="<?php echo $responsible_phones; ?>">

                        </div>

                        <!--  <div class="form-group">

                            <label for="nombre">Nacionalidad:</label>

                            <input type="text" placeholder="Ingrese la nacionalidad" name="nationality" id="nationality" class="form-control" value="<?php echo $nationality; ?>">

                        </div> -->

                        <div class="form-group">

                            <label for="nombre">Fecha de nacimiento:</label>

                            <input type="text" placeholder="Ingrese la fecha de nacimiento" name="date_birth" id="date_birth" class="form-control" value="<?php echo $datebirth; ?>">

                        </div>

                        <!--  <div class="form-group">

                            <label for="direccion">Dirección:</label>

                            <input type="text" placeholder="Ingrese la dirección" name="address" id="address" class="form-control" value="<?php echo $address; ?>">

                        </div>

                        <div class="form-group">

                            <label for="nombre">Código postal:</label>

                            <input type="text" placeholder="Ingrese el código postal" name="zip_code" id="zip_code" class="form-control" value="<?php echo $zip_code; ?>">

                        </div>

                        <div class="form-group">

                            <label for="nombre">Municipio:</label>

                            <input type="text" placeholder="Ingrese el municipio" name="city" id="city" class="form-control" value="<?php echo $city; ?>">

                        </div>

                        <div class="form-group">

                            <label for="nombre">Departamento:</label>

                            <input type="text" placeholder="Ingrese el departamento" name="department" id="department" class="form-control" value="<?php echo $department; ?>">

                        </div> -->

                        <div class="form-group">

                        <label for="foto">Foto de perfil del cliente:</label>

                        <div id="imagePreview">

                            <img src="../assets/img/default-profile-picture.png" height="150" width="150" alt=""><br><br>

                        </div>

                        <input type="file" name="image" id="image" onchange="return fileValidation()" accept="image/jpg, image/png, image/jpeg" class="form-control-file">

                        <div class="form-group">

                        <label for="user_type">Selecciona el tipo de cliente:</label>

                        <!-- <input type="text" required placeholder="Selecciona / Ingresa el tipo de cliente" class="form-control" name="user_type" list="user_type"> -->

                        <select class="form-control" required name="grade" id="grade" size="1">


                                <?php
                                switch ($usertype) {

                                        ///////Bachillerato////////////

                                    case $section == "Estudiante":
                                        $opcion11 =  '<option selected value="Estudiante">Estudiante</option>
                                        <option value="Docente">Docente</option>
                                        <option value="N/A">N/A</option>';
                                        break;

                                    case $section == "Docente":
                                        $opcion11 ='<option value="Estudiante">Estudiante</option>
                                        <option selected value="Docente">Docente</option>
                                        <option value="N/A">N/A</option>';
                                        break;

                                    case $section == "N/A":
                                        $opcion11 = '<option value="Estudiante">Estudiante</option>
                                        <option value="Docente">Docente</option>
                                        <option selected value="N/A">N/A</option>';
                                        break;

                                    default:
                                        $opcion12 = '<option select value="" disabled selected hidden>Selecciona el tipo de cliente</option>';
                                        $opcion11 = '<option value="Estudiante">Estudiante</option>
                                        <option value="Docente">Docente</option>
                                        <option selected value="N/A">N/A</option>';
                                    }
                                ?>


                                    <?php
                                    echo $opcion12;
                                    ?>
                                <optgroup label="Sección">

                                    <?php
                                    echo $opcion11;
                                    ?>

                                </optgroup>

                            </select>

                    </div>

                    </div>

                        <button type="submit" class="btn btn-primary" name="editar_cliente"><i class="fas fa-user-edit"></i> Actulizar Datos</button>

                        <a href="clientes.php" class="btn btn-danger">Regresar</a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- /.container-fluid -->

<?php include_once "includes/footer.php"; ?>