<?php
session_start();
include("conexion.php");
if (empty($_SESSION['now'])) {
  header('Location: login.php');
  $id_user = $_SESSION['id'];
  exit();
}else{
  $nombre = $_SESSION['name'];
  $id_user = $_SESSION['id'];
  $query = safe_query($conexion, 'SELECT * FROM users WHERE id = ? AND state = 1', [$id_user]);
  $result = mysqli_fetch_assoc($query);
  $funds = $result['funds'];
  $codigo = $result['id_code'];
  $grado = $result['grade']; 

  $query1 = safe_query($conexion, 'SELECT * FROM ventas WHERE id_cliente = ? ORDER BY fecha DESC LIMIT 1', [$id_user]);
  $result1 = mysqli_fetch_assoc($query1);
  $last = $result1['total'];

  $query2 = safe_query($conexion, 'SELECT * FROM fondos WHERE id_cliente = ? ORDER BY fecha DESC LIMIT 1', [$id_user]);
  $result2 = mysqli_fetch_assoc($query2);
  $abono = $result2['total'];


}
?>
  <?php include("navbar.php")?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Bienvenido <?php echo $nombre ?></h3>
                  <h6 class="font-weight-normal mb-0">Sus datos son los siguientes:</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="images/dashboard.png" alt="people">
                  
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Fondos:</p>
                      <p class="fs-30 mb-2">$<?php echo $funds ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Código de carnet:</p>
                      <p class="fs-30 mb-2"><?php echo $codigo ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Última compra: </p>
                      <p class="fs-30 mb-2">$<?php echo $last ?></p>

                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Último abono:</p>
                      <p class="fs-30 mb-2">$<?php echo $abono ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
<?php include("footer.php");