# WalletID

Billetera digital para un centro educativo: los estudiantes pagan en la cafetería o en la tienda con el saldo de su cuenta, sin efectivo. Son dos portales que comparten la misma base de datos, uno para la caja y la administración, y otro para los titulares.

*In English: a cashless campus wallet with two portals, one for cashiers and admins and one for cardholders, built with PHP and MySQL.*

## Qué hace

**Caja y administración** (`admin/`)

- Venta en caja que descuenta del saldo del titular, con ticket en PDF y código de barras.
- Recargas de saldo y su historial.
- Productos de la cafetería y la tienda, registro de ventas y devoluciones.
- Reporte de ventas en PDF y gráficas en el panel principal.
- Cuentas de administrador con permisos por módulo (ventas, recargas, devoluciones, productos y otros).

**Portal del titular** (`portal/`)

- Saldo disponible, historial de compras e historial de recargas.

## Tecnología

PHP con mysqli, MySQL o MariaDB, JavaScript con AJAX y JSON, Bootstrap (plantillas SB Admin y Skydash), FPDF, DataTables, Chart.js y SweetAlert2. Las contraseñas se guardan con bcrypt.

## Cómo correrlo

Requisitos: PHP 8 y MySQL o MariaDB (con XAMPP funciona).

```bash
mysql -u root -p < database/schema.sql   # crea la base wallet_id
mysql -u root -p < database/seeds.sql    # datos de prueba
cp config.example.php config.php         # usuario y contraseña de MySQL
php -S localhost:8000
```

- Caja y administración: http://localhost:8000/admin/
- Portal del titular: http://localhost:8000/portal/login.php

Usuarios de prueba (datos ficticios):

| Portal | Usuario | Contraseña |
|---|---|---|
| Caja y administración | `admin` | `admin` |
| Titular | `cardholder1@walletid.local` | `password123` |
| Titular | `cardholder2@walletid.local` | `password123` |

## Pendientes

- No tiene pruebas automatizadas.
- Las recargas solo se hacen en caja; el portal del titular es de consulta.
- La sal que se agrega a las contraseñas está escrita en el código de inicio de sesión; debería leerse de `config.php`.

## Licencia

[MIT](LICENSE)
