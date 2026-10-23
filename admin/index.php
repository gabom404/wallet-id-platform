<?php
require "conexion.php";
session_start();
if (!empty($_SESSION['active'])) {
    header('location: src/');
} else {
    if (!empty($_POST)) {
        $alert = '';
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = '<div class="alert alert-danger" role="alert">
            Ingresar usuario y contraseña
            </div>';
        } else {


            $user = $_POST['usuario'];
            $clave = $_POST['clave'];
            $query = safe_query($conexion, 'SELECT * FROM usuario WHERE usuario = ? AND estado = 1', [$user]);


            mysqli_close($conexion);
            $resultado = mysqli_num_rows($query);
            if ($resultado > 0) {

                $row = $query->fetch_assoc();
                $hash = $row['clave'];
                $salt = "HelloKitty";
                if (password_verify($clave . $salt, $hash)) {

                    $_SESSION['active'] = true;
                    $_SESSION['idUser'] = $row['idusuario'];
                    $_SESSION['nombre'] = $row['nombre'];
                    $_SESSION['user'] = $row['usuario'];
                    header('location: src/');
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    ¡Contraseña incorrecta!
                    </div>';
                }
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                ¡Nombre de usuario incorrecto!
                </div>';
                session_destroy();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>WalletID - Campus Digital Wallet | Administración POS</title>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
    <link href="assets/css/styles.css" rel="stylesheet" />
    <script src="assets/js/all.min.js" crossorigin="anonymous"></script>

</head>

<body style="overflow-x: hidden; background: url(assets/img/index-bg.jpg); background-size: cover; background-position: center; ">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header text-center py-4">
                                    <img src="assets/img/wallet-id-logo.svg" alt="WalletID Logo" style="max-height: 65px; width: auto;">
                                </div>
                                <div class="card-body">
                                    <form action="" id="login_form" method="POST">
                                        <div class="form-group">
                                            <label class="small mb-1" for="usuario"><i class="fas fa-user"></i> Usuario:</label>
                                            <input class="form-control py-4" id="usuario" name="usuario" type="text" placeholder="Ingrese su nombre de usuario" />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="clave"><i class="fas fa-key"></i> Contraseña:</label>
                                            <input class="form-control py-4" id="clave" name="clave" type="password" placeholder="Ingrese su contraseña" />
                                        </div>
                                        <div class="alert alert-danger text-center d-none" id="alerta" role="alert">

                                        </div>
                                        <?php echo isset($alert) ? $alert : ''; ?>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; <?php echo date("Y"); ?> Wallet ID | Todos los derechos reservados</div>

                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/sweetalert2.all.min.js"></script>
    <script src="assets/js/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/js/Chart.bundle.min.js"></script>
    <script src="assets/js/funciones.js"></script>
</body>

</html>