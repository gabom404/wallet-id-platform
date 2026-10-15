<?php session_start();
ob_start();
if (empty($_SESSION['active'])) {
    header('location: ../');
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>WalletID - Campus Digital Wallet | Panel de Administración</title>
    <link rel="shortcut icon" href="../assets/img/favicon.png" type="image/x-icon">
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css">
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a href="index.php" class="navbar-brand">
            <img src="../assets/img/wallet-id-logo-white.svg" alt="WalletID Logo" style="height: 38px; width: auto;">
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

        <!-- Navbar-->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['user']; ?> <i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#nuevo_pass">Cambiar contraseña</a>
                    <div class="dropdown-divider"></div>
                    <!-- <a class="dropdown-item" href="http://localhost:81/wallet_id_en_system/src/">Idioma (Inglés)</a> -->
                    <!-- <div class="dropdown-divider"></div> -->
                    <a class="dropdown-item" href="salir.php">Cerrar sesión</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Administradores";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="usuarios.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Administradores
                        </a>';
                        }
                        ?>
                        <?php
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Comunidad Educativa";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="clientes.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Comunidad Educativa
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Productos";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="productos.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-box-open"></i></div>
                        Productos
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Realizar Venta";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="ventas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                        Realizar Venta
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Registro Venta";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '  <a class="nav-link" href="lista_ventas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                        Registro Venta
                        </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Agregar Saldo";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="fondos.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>
                        Agregar Saldo
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Registro Abonos";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="lista_fondos.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                        Registro Abonos
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Realizar Devolucion";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="nuevas_devoluciones.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                        Realizar Devolución
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Registro Devoluciones";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="devoluciones.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chevron-down"></i></div>
                       Registro Devoluciones
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Informacion Ticket";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="config.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-info"></i></div>
                        Información Ticket
                    </a>';
                        }
                        ?>
                        <?php include_once "includes/header.php";
                        require("../conexion.php");
                        $id_user = $_SESSION['idUser'];
                        $permiso = "Informacion Ticket";
                        $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
                        $existe = mysqli_fetch_all($sql);
                        if (empty($existe) && $id_user != 1) {
                        } else {
                            echo '<a class="nav-link" href="reporte.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                        Reporte Diario
                    </a>';
                        }
                        ?>
                    </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid mt-2">