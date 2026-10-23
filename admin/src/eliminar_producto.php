<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "Administradores";
$sql = safe_query($conexion, 'SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?', [$id_user, $permiso]);
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $query_delete = safe_query($conexion, 'UPDATE producto SET estado = 0 WHERE codproducto = ?', [$id]);
    $query_delete_temp = safe_query($conexion, 'DELETE FROM detalle_temp WHERE id_producto = ?', [$id]);

    mysqli_close($conexion);
    header("Location: productos.php");
}
