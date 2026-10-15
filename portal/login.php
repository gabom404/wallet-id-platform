<?php
require "conexion.php";
session_start();
if (!empty($_SESSION['active'])) {
  header('location: dashboard.php');
} else {
  if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['email']) || empty($_POST['clave'])) {
      $alert = '<div class="alert alert-danger" role="alert">
            Ingresar usuario y contraseña
            </div>';
    } else {
      $email = mysqli_real_escape_string($conexion, $_POST['email']);
      $clave = mysqli_real_escape_string($conexion, $_POST['clave']);
      $query = mysqli_query($conexion, "SELECT * FROM users WHERE email = '$email' AND state = 1");
      mysqli_close($conexion);
      $resultado = mysqli_num_rows($query);
      if ($resultado > 0) {
        $row = $query->fetch_assoc();
        $hash = $row['password'];
        $salt = "HelloKitty";
        if (password_verify($_POST['clave'] . $salt, $hash)) {
          $_SESSION['now'] = true;
          $_SESSION['id'] = $row['id'];
          $_SESSION['name'] = $row['full_name'];
          // $_SESSION['user'] = $row['usuario'];
          header('location: dashboard.php');
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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>WalletID - Campus Digital Wallet | Portal de Usuario</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo text-center">
                <img src="images/wallet-id-logo.svg" alt="WalletID Logo" style="max-height: 55px; width: auto;">
              </div>
              <?php echo isset($alert) ? $alert : ''; ?>  
              <h6 class="font-weight-light">Inicia sesión para continuar.</h6>
              <form class="pt-3" action="" id="login_form" method="POST">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" placeholder="Ingrese Correo Electrónico" name="email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="clave" placeholder="Ingrese Contraseña" name="clave">
                </div>
                <input type="submit" value="Iniciar sesión" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit">
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
</body>

</html>