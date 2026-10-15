<?php
require_once "../conexion.php";
session_start();
if (isset($_GET['q'])) {
    $datos = array();
    $nombre = $_GET['q'];
    $cliente = mysqli_query($conexion, "SELECT * FROM users WHERE full_name  LIKE '%$nombre%' AND state = 1 or id_code LIKE '%$nombre%' AND state = 1");
    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['id'];
        $data['name'] = $row['full_name'];
        $data['label'] = $row['full_name'];
        $data['grade'] = $row['grade'];
        $data['idcode'] = $row['id_code'];
        $data['imgcliente'] = $row['image'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}

if (isset($_GET['id_code'])) {
    $datos = array();
    $nombre = $_GET['id_code'];
    $cliente = mysqli_query($conexion, "SELECT * FROM users WHERE full_name  LIKE '%$nombre%'  AND state = 1 or id_code LIKE '%$nombre%' AND state = 1");
    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['id'];
        $data['label'] = $row['full_name'];
        $data['grade'] = $row['grade'];
        $data['idcode'] = $row['id_code'];
        $data['imgcliente'] = $row['image'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['pro'])) {
    $datos = array();
    $nombre = $_GET['pro'];
    $producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo LIKE '%" . $nombre . "%' OR descripcion LIKE '%" . $nombre . "%' AND estado = 1");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['codproducto'];
        $data['label'] = $row['codigo'] . ' - ' . $row['descripcion'];
        $data['value'] = $row['descripcion'];
        $data['precio'] = $row['precio'];
        $data['existencia'] = $row['existencia'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['ticket'])) {
    $datos = array();
    $nombre = $_GET['ticket'];
    // $productos = mysqli_query($conexion, "SELECT  id_producto, barcode, cantidad FROM detalle_venta WHERE barcode LIKE '$nombre%' ");
    $productos = mysqli_query($conexion, "SELECT  d.id_producto, d.barcode, d.cantidad, p.* FROM detalle_venta d, producto p WHERE d.barcode LIKE '$nombre%' AND d.id_producto = p.codproducto");
    while ($row = mysqli_fetch_assoc($productos)) {
        // $idprod = $row['id_producto'];
        // $value = $row['barcode'];
        // $existencia = $row['cantidad'];
        // $producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = '$idprod'");
        // while ($data = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['codproducto'];
        $data['value'] = $row['barcode'];
        $data['precio'] = $row['precio'];
        $data['existencia'] = $row['cantidad'];
        $data['label'] = $row['barcode'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalle'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion, p.codigo, p.existencia FROM detalle_temp d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_usuario = $id");
    $sumar = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id_producto'] = $row['id_producto'];
        $data['existencia'] = $row['existencia'];
        $data['codigo'] = $row['codigo'];
        $data['descripcion'] = $row['descripcion'];
        $data['cantidad'] = $row['cantidad'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = number_format($row['precio_venta'] * $row['cantidad'], 2, '.', ',');
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['detalledev'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion, p.codigo FROM devoluciones_temp d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_usuario = $id");
    $sumar = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM devoluciones_temp WHERE id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id_producto'] = $row['id_producto'];
        $data['codigo'] = $row['codigo'];
        $data['descripcion'] = $row['descripcion'];
        $data['cantidad'] = $row['cantidad'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = number_format($row['precio_venta'] * $row['cantidad'], 2, '.', ',');
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['delete_detalle'])) {
    $id_detalle = $_GET['id'];
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_producto = $id_detalle");
    $datos = mysqli_fetch_assoc($verificar);
    if ($datos['cantidad'] >= 1) {
        $cantidad = $datos['cantidad'];
        $precio = $datos['precio_venta'];
        $total = $datos['total'];
        $nuevototal =  $total - $cantidad * $precio;
        $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = $cantidad, total =$nuevototal WHERE id_producto = $id_detalle");
        if ($query) {
            // $msg = "restado";
            $query2 = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id_producto = $id_detalle");
            if ($query2) {
                $msg = "ok";
            } else {
                $msg = "Error";
            }
        } else {
            $msg = "Error";
        }
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['delete_devolucion'])) {
    $id_detalle = $_GET['id'];
    $verificar = mysqli_query($conexion, "SELECT * FROM devoluciones_temp WHERE id_producto = $id_detalle");
    $datos = mysqli_fetch_assoc($verificar);
    if ($datos['cantidad'] >= 1) {
        $cantidad = $datos['cantidad'];
        $precio = $datos['precio_venta'];
        $total = $datos['total'];
        $nuevototal =  $total - $cantidad * $precio;
        $query = mysqli_query($conexion, "UPDATE devoluciones_temp SET cantidad = $cantidad, total =$nuevototal WHERE id_producto = $id_detalle");
        if ($query) {
            // $msg = "restado";
            $query2 = mysqli_query($conexion, "DELETE FROM devoluciones_temp WHERE id_producto = $id_detalle");
            if ($query2) {
                $msg = "ok";
            } else {
                $msg = "Error";
            }
        } else {
            $msg = "Error";
        }
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['cantidad'])) {
    $id_detalle = $_GET['id'];
    $nueva_cantidad = $_GET['nuevaCantidad'];
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_producto = $id_detalle");
    $datos = mysqli_fetch_assoc($verificar);
    if ($nueva_cantidad >= 1) {
        $cantidad = $datos['cantidad'];
        $precio = $datos['precio_venta'];
        $total = $datos['total'];
        $nuevototal = $nueva_cantidad * $precio;
        // $nombreprod = mysqli_query($conexion, "SELECT * FROM producto WHERE descripcion = $id_detalle");
        // $nombre = mysqli_fetch_assoc($nombreprod);
        $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = $nueva_cantidad, total = $nuevototal WHERE id_producto = $id_detalle");
        if ($query) {
            $msg = "Listar";
        } else {
            $msg = "Error";
        }
        echo $msg;
        die();
    } else {
        $query = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id_producto = $id_detalle");
        if ($query) {
            $msg = "Eliminado";
        } else {
            $msg = "Error";
        }
        echo $msg;
        die();
    }
} else if (isset($_GET['cantidad_devolucion'])) {
    $id_detalle = $_GET['id'];
    $nueva_cantidad = $_GET['nuevaCantidad'];
    $verificar = mysqli_query($conexion, "SELECT * FROM devoluciones_temp WHERE id_producto = $id_detalle");
    $datos = mysqli_fetch_assoc($verificar);
    if ($nueva_cantidad >= 1) {
        $cantidad = $datos['cantidad'];
        $precio = $datos['precio_venta'];
        $total = $datos['total'];
        $nuevototal = $nueva_cantidad * $precio;
        // $nombreprod = mysqli_query($conexion, "SELECT * FROM producto WHERE descripcion = $id_detalle");
        // $nombre = mysqli_fetch_assoc($nombreprod);
        $query = mysqli_query($conexion, "UPDATE devoluciones_temp SET cantidad = $nueva_cantidad, total = $nuevototal WHERE id_producto = $id_detalle");
        if ($query) {
            $msg = "Listar";
        } else {
            $msg = "Error";
        }
        echo $msg;
        die();
    } else {
        $query = mysqli_query($conexion, "DELETE FROM devoluciones_temp WHERE id_producto = $id_detalle");
        if ($query) {
            $msg = "Eliminado";
        } else {
            $msg = "Error";
        }
        echo $msg;
        die();
    }
} else if (isset($_GET['procesarVenta'])) {
    $id_cliente = $_GET['id'];
    // $name = $_GET['nom_cliente'];
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    $query = mysqli_query($conexion, "SELECT * FROM users WHERE id = '$id_cliente'");
    $resultado = mysqli_fetch_assoc($query);
    $nombre = $resultado['full_name'];
    $cantidad_actual = $resultado['funds'];



    // consultamos detalles
    $querydetalles = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_usuario = $id_user");
    $resultd = mysqli_fetch_assoc($querydetalles);
    $qty = $resultd['cantidad'];
    $idproduct = $resultd['id_producto'];
    //consultamos la cantidad del producto
    $queryexistencia = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $idproduct");
    $resulte = mysqli_fetch_assoc($queryexistencia);
    $existenciastock = $resulte['existencia'];
    if ($existenciastock >= $qty) {
        if ($resultado['funds'] >= $result['total_pagar']) {
            $insertar = mysqli_query($conexion, "INSERT INTO ventas(id_cliente, total, id_usuario) VALUES ($id_cliente, '$total', $id_user)");
            $cantidad_total = $cantidad_actual - $total;
            $query_insert = mysqli_query($conexion, "UPDATE  users SET funds = '$cantidad_total' WHERE id = '$id_cliente'");
            if ($insertar) {
                $id_maximo = mysqli_query($conexion, "SELECT MAX(idv) AS total FROM ventas");
                $resultId = mysqli_fetch_assoc($id_maximo);
                $ultimoId = $resultId['total'];
                $consultaDetalle = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_usuario = $id_user");

                // ** PROBAR CON PISTOLA Y SI FUNCIONA CAMBIARSELO EN DEVOLUCIONES Y FONDOS **
                $random_number = rand(pow(10, 13 -  1), pow(10, 13) - 1);

                while ($row = mysqli_fetch_assoc($consultaDetalle)) {
                    $id_producto = $row['id_producto'];
                    $cantidad = $row['cantidad'];
                    $precio = $row['precio_venta'];
                    $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_venta(id_producto, id_venta, barcode, cantidad, precio) VALUES ($id_producto, $ultimoId, $random_number, $cantidad, '$precio')");
                    $stockActual = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
                    $stockNuevo = mysqli_fetch_assoc($stockActual);
                    $stockTotal = $stockNuevo['existencia'] - $cantidad;
                    $ganancia =  ($stockNuevo['precio_v'] - $stockNuevo['precio']) * $cantidad;
                    $ganancia2 = $ganancia + $stockNuevo['ganancia_u'];
                    $stock = mysqli_query($conexion, "UPDATE producto SET existencia = '$stockTotal' , ganancia_u = ' $ganancia2'  WHERE codproducto = '$id_producto'");
                }
                if ($insertarDet) {
                    $eliminar = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id_usuario = $id_user");
                    $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
                }
            } else {
                $msg = "error";
            }
        } else {
            $msg = "Fondos";
        }
    } else {
        $msg = "existencia";
    }
    echo json_encode($msg);
    die();
} else if (isset($_GET['procesarVenta1'])) {
    $id_cliente = $_GET['id'];
    // $name = $_GET['nom_cliente'];
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexion, "SELECT total, SUM(total) AS total_pagar FROM devoluciones_temp WHERE id_usuario = $id_user");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    $query = mysqli_query($conexion, "SELECT * FROM users WHERE id = '$id_cliente'");
    $resultado = mysqli_fetch_assoc($query);
    $nombre = $resultado['full_name'];
    $cantidad_actual = $resultado['funds'];
    // consultamos detalles
    $querydetalles = mysqli_query($conexion, "SELECT * FROM devoluciones_temp WHERE id_usuario = $id_user");
    $resultd = mysqli_fetch_assoc($querydetalles);
    $qty = $resultd['cantidad'];
    $idproduct = $resultd['id_producto'];
    //consultamos la cantidad del producto
    $queryexistencia = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $idproduct");
    $resulte = mysqli_fetch_assoc($queryexistencia);
    $existenciastock = $resulte['existencia'];
    if ($resultado['funds'] >= 0) {
        $insertar = mysqli_query($conexion, "INSERT INTO devoluciones(id_cliente, total, id_usuario) VALUES ($id_cliente, '$total', $id_user)");
        $cantidad_total = $cantidad_actual + $total;
        $query_insert = mysqli_query($conexion, "UPDATE  users SET funds = '$cantidad_total' WHERE id = '$id_cliente'");
        if ($insertar) {
            $id_maximo = mysqli_query($conexion, "SELECT MAX(idv) AS total FROM devoluciones");
            $resultId = mysqli_fetch_assoc($id_maximo);
            $ultimoId = $resultId['total'];
            $consultaDetalle = mysqli_query($conexion, "SELECT * FROM devoluciones_temp WHERE id_usuario = $id_user");
            while ($row = mysqli_fetch_assoc($consultaDetalle)) {
                $id_producto = $row['id_producto'];
                $cantidad = $row['cantidad'];
                $precio = $row['precio_venta'];
                //$random_number = rand(1, 9999);
                $random_number = rand(pow(10, 13 -  1), pow(10, 13) - 1);
                $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_devoluciones(barcode, id_producto, id_venta, cantidad, precio) VALUES ('$random_number', $id_producto, $ultimoId, $cantidad, '$precio')");
                $stockActual = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
                $stockNuevo = mysqli_fetch_assoc($stockActual);
                $stockTotal = $stockNuevo['existencia'] + $cantidad;
                $stock = mysqli_query($conexion, "UPDATE producto SET existencia = $stockTotal WHERE codproducto = $id_producto");
            }
            if ($insertarDet) {
                $eliminar = mysqli_query($conexion, "DELETE FROM devoluciones_temp WHERE id_usuario = $id_user");
                $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
            }
        } else {
            $msg = "error";
        }
    } else {
        $msg = "Fondos";
    }

    echo json_encode($msg);
    die();
}

if (isset($_GET['procesarVenta2'])) {
    $id_cliente = $_GET['id'];
    $cantidad = $_GET['Cantidad'];
    $id_user = $_SESSION['idUser'];
    // $cantidad = $_POST['cantidad'];
    if ($cantidad > 0) {
        if ($cantidad != "") {
            if ($cantidad >= 0 && $cantidad <= 100) {
                $clientes = mysqli_query($conexion, "SELECT * FROM users WHERE id = $id_cliente");
                $datosC = mysqli_fetch_assoc($clientes);
                $config = mysqli_query($conexion, "SELECT * FROM configuracion");
                $datos = mysqli_fetch_assoc($config);
                $query = mysqli_query($conexion, "SELECT * FROM users WHERE id = '$id_cliente'");
                $resultado = mysqli_fetch_assoc($query);
                $nombre = $resultado['full_name'];

                $cantidad_actual = $resultado['funds'];
                $cantidad_total = $cantidad_actual + $cantidad;

                if ($cantidad_total <= 100) {
                    $query_insert = mysqli_query($conexion, "UPDATE  users SET funds = '$cantidad_total' WHERE id = '$id_cliente'");
                    $insertar = mysqli_query($conexion, "INSERT INTO fondos(id_cliente, total, id_usuario) VALUES ('$id_cliente', '$cantidad', '$id_user')");
                    $id_maximo = mysqli_query($conexion, "SELECT MAX(idv) AS total FROM fondos");
                    $resultId = mysqli_fetch_assoc($id_maximo);
                    $ultimoId = $resultId['total'];
                    //$random_number = rand(1, 9999);
                    $random_number = rand(pow(10, 13 -  1), pow(10, 13) - 1);
                    $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_fondos(id_venta, cantidad, barcode) VALUES ('$ultimoId', '$cantidad', '$random_number')");
                    if ($query_insert) {
                        $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
                    } else {
                        $msg = "error";
                    }
                } else {
                    $msg = "dinero";
                }
            } else {
                $msg = "error";
            }
        }
    } else {
        $msg = "negativos";
    }
    echo json_encode($msg);
    die();
}




if (isset($_POST['action'])) {
    $id = $_POST['id'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $id_user = $_SESSION['idUser'];
    $total = $precio * $cant;
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_producto = $id AND id_usuario = $id_user");
    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + 1;
        $total_precio = $cantidad * $total;
        $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = $cantidad, total = '$total_precio' WHERE id_producto = $id AND id_usuario = $id_user");
        if ($query) {
            $msg = "actualizado";
        } else {
            $msg = "Error al ingresar";
        }
    } else {
        $query = mysqli_query($conexion, "INSERT INTO detalle_temp(id_usuario, id_producto, cantidad, precio_venta, total) VALUES ($id_user, $id, $cant, '$precio', $total)");
        if ($query) {
            $msg = "registrado";
        } else {
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
}


if (isset($_POST['actiondev'])) {
    $id = $_POST['id'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $id_user = $_SESSION['idUser'];
    $total = $precio * $cant;
    $verificar = mysqli_query($conexion, "SELECT * FROM devoluciones_temp WHERE id_producto = $id AND id_usuario = $id_user");
    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + 1;
        $total_precio = $cantidad * $total;
        $query = mysqli_query($conexion, "UPDATE devoluciones_temp SET cantidad = $cantidad, total = '$total_precio' WHERE id_producto = $id AND id_usuario = $id_user");
        if ($query) {
            $msg = "actualizado";
        } else {
            $msg = "Error al ingresar";
        }
    } else {
        $query = mysqli_query($conexion, "INSERT INTO devoluciones_temp(id_usuario, id_producto, cantidad, precio_venta, total) VALUES ($id_user, $id, $cant, '$precio', $total)");
        if ($query) {
            $msg = "registrado";
        } else {
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
}


if (isset($_POST['cambio'])) {
    $id = $_SESSION['idUser'];

    $actual = $_POST['actual'];
    $nueva = $_POST['nueva'];

    $salt1 = "HelloKitty";
    $hashed_actual = mysqli_real_escape_string($conexion, password_hash($actual . $salt1, PASSWORD_BCRYPT));

    $consulta = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id");
    $row = mysqli_fetch_assoc($consulta);
    $stored_hash = $row['clave'];

    if (password_verify($actual . $salt1, $stored_hash)) {
        $salt2 = "HelloKitty";
        $hashed_nueva = mysqli_real_escape_string($conexion, password_hash($nueva . $salt2, PASSWORD_BCRYPT));

        $query = mysqli_query($conexion, "UPDATE usuario SET clave = '$hashed_nueva' WHERE idusuario = $id");

        if ($query) {
            $msg = 'ok';
        } else {
            $msg = 'error';
        }
    } else {
        $msg = 'dif';
    }

    echo $msg;
    die();
}


if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $clave = mysqli_real_escape_string($conexion, $_POST['clave']);
    $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user' AND estado = 1");
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
            echo 'success';
        } else {
            echo 'wrong-pass';
        }
    } else {
        echo 'wrong-user';
        session_destroy();
    }
}
if (isset($_POST['agregar_administrador'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $user = $_POST['usuario'];
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];
    $query = mysqli_query($conexion, "SELECT * FROM usuario where correo = '$email'");
    $result = mysqli_fetch_array($query);
    $query2 = mysqli_query($conexion, "SELECT * FROM usuario where usuario = '$user'");
    $result2 = mysqli_fetch_array($query2);
    if ($result) {
        echo 'correo';
    } elseif ($result2) {
        echo 'user';
    } else {
        $salt1 = "HelloKitty";
        $hash1 = password_hash($clave . $salt1, PASSWORD_BCRYPT);
        $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,correo,usuario,clave, rol) values ('$nombre', '$email', '$user', '$hash1','$rol')");
        if ($query_insert) {
            $query_id = mysqli_query($conexion, "SELECT * FROM usuario ORDER BY idusuario DESC LIMIT 1");
            $fetch = mysqli_fetch_assoc($query_id);
            $id = $fetch['idusuario'];

            $insert_permiso = mysqli_query($conexion, "INSERT INTO detalle_permisos(id_permiso,id_usuario) values ('8', '$id')");
            if ($insert_permiso) {
                echo 'success';
            } else {
                echo 'error-permiso';
            }
        } else {
            echo 'error-insert';
        }
    }
}
