-- =============================================================================
-- WalletID Platform - Demo Seed Data
-- Demo administrator, cardholders, inventory catalog, recharges & sales
-- =============================================================================

USE `wallet_id`;

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. Configuration Settings
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `configuracion`;
INSERT INTO `configuracion` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'WalletID - Campus Digital Wallet', '+1 (555) 019-8800', 'support@walletid.local', 'Central Campus - Student Service Center');

-- -----------------------------------------------------------------------------
-- 2. System Permissions
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `permisos`;
INSERT INTO `permisos` (`id`, `nombre`) VALUES
(1, 'Pagina Principal'),
(2, 'Administradores'),
(3, 'Comunidad Educativa'),
(4, 'Productos'),
(5, 'Realizar Venta'),
(6, 'Registro Venta'),
(7, 'Agregar Saldo'),
(8, 'Registro Abonos'),
(9, 'Realizar Devolucion'),
(10, 'Registro Devoluciones'),
(11, 'Informacion Ticket');

-- -----------------------------------------------------------------------------
-- 3. Admin Account
-- Credentials: Username: admin | Password: admin
-- (Authentication verifies BCrypt hash with salt 'HelloKitty')
-- BCrypt Hash: $2y$12$KorUMqRm7espsMOI1lob/u54E/4j95jhrnquumaIpTbtsNc7XIUfO
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `usuario`;
INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `estado`) VALUES
(1, 'Administrator', 'admin@walletid.local', 'admin', '$2y$12$KorUMqRm7espsMOI1lob/u54E/4j95jhrnquumaIpTbtsNc7XIUfO', 'Owner', 1);

-- Assign all permissions to primary administrator
TRUNCATE TABLE `detalle_permisos`;
INSERT INTO `detalle_permisos` (`id_permiso`, `id_usuario`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1);

-- -----------------------------------------------------------------------------
-- 4. Demo Cardholders (Users Portal)
-- Cardholder 1: Email: cardholder1@walletid.local | Password: password123 | Code: ID-2026-001 | Balance: $25.00
-- Cardholder 2: Email: cardholder2@walletid.local | Password: password123 | Code: ID-2026-002 | Balance: $15.50
-- BCrypt Hash: $2y$12$PisPRG6s9dJhZ09KhZL3hOh5Jum/diKb6Y9HZ9/e1rJtZIPqkC6aC
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `users`;
INSERT INTO `users` (`id`, `full_name`, `id_code`, `email`, `grade`, `section`, `specialty`, `telephone_number`, `password`, `responsible_names`, `responsible_phones`, `responsible_emails`, `date_birth`, `funds`, `state`, `image`, `user_type`) VALUES
(1, 'Alex Rivera', 'ID-2026-001', 'cardholder1@walletid.local', '12th Grade', 'A', 'Software Engineering', '555-0101', '$2y$12$PisPRG6s9dJhZ09KhZL3hOh5Jum/diKb6Y9HZ9/e1rJtZIPqkC6aC', 'Elena Rivera', '555-0102', 'elena.rivera@example.com', '2008-05-14', 25.00, 1, 'assets/img/default-profile-picture.png', 'Estudiante'),
(2, 'Sofia Gomez', 'ID-2026-002', 'cardholder2@walletid.local', '11th Grade', 'B', 'Electronics & Robotics', '555-0103', '$2y$12$PisPRG6s9dJhZ09KhZL3hOh5Jum/diKb6Y9HZ9/e1rJtZIPqkC6aC', 'Roberto Gomez', '555-0104', 'roberto.gomez@example.com', '2009-08-22', 15.50, 1, 'assets/img/default-profile-picture.png', 'Estudiante');

-- -----------------------------------------------------------------------------
-- 5. Sample Products (Cafeteria / Campus Store)
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `producto`;
INSERT INTO `producto` (`codproducto`, `codigo`, `descripcion`, `precio`, `precio_v`, `existencia`, `existencia_m`, `ganancia_u`, `estado`, `usuario_id`) VALUES
(1, 'PROD-001', 'Natural Orange Juice 500ml', 0.75, 1.25, 45, 10, 0.50, 1, 1),
(2, 'PROD-002', 'Roast Beef & Cheddar Panini', 1.80, 3.00, 30, 5, 1.20, 1, 1),
(3, 'PROD-003', 'Energy Protein Bar 60g', 0.85, 1.50, 50, 15, 0.65, 1, 1),
(4, 'PROD-004', 'Purified Mineral Water 600ml', 0.35, 0.75, 60, 20, 0.40, 1, 1),
(5, 'PROD-005', 'Campus Lunch Bowl (Chicken & Rice)', 2.20, 3.75, 25, 8, 1.55, 1, 1);

-- -----------------------------------------------------------------------------
-- 6. Sample Fund Recharge Records
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `fondos`;
INSERT INTO `fondos` (`idv`, `id_cliente`, `total`, `id_usuario`, `fecha`) VALUES
(1, 1, 25.00, 1, NOW() - INTERVAL 2 DAY),
(2, 2, 15.50, 1, NOW() - INTERVAL 1 DAY);

TRUNCATE TABLE `detalle_fondos`;
INSERT INTO `detalle_fondos` (`id`, `id_venta`, `cantidad`, `barcode`) VALUES
(1, 1, 25.00, '8810293847561'),
(2, 2, 15.50, '8810293847562');

-- -----------------------------------------------------------------------------
-- 7. Sample POS Sales Records
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `ventas`;
INSERT INTO `ventas` (`idv`, `id_cliente`, `total`, `id_usuario`, `fecha`) VALUES
(1, 1, 4.25, 1, NOW() - INTERVAL 1 DAY);

TRUNCATE TABLE `detalle_venta`;
INSERT INTO `detalle_venta` (`id`, `id_producto`, `id_venta`, `barcode`, `cantidad`, `precio`) VALUES
(1, 2, 1, '7411001002', 1, 3.00),
(2, 1, 1, '7411001001', 1, 1.25);

SET FOREIGN_KEY_CHECKS = 1;
