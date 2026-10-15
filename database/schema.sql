-- =============================================================================
-- WalletID Platform - Database Schema
-- Campus Digital Wallet, Student ID Balance & POS Cashless System
-- Compatible with MySQL 5.7+, MariaDB 10.3+, MySQL 8.0+
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `wallet_id` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wallet_id`;

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. Table structure for table `configuracion`
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `configuracion`;
CREATE TABLE `configuracion` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `telefono` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `direccion` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 2. Table structure for table `usuario`
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `idusuario` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `correo` VARCHAR(100) NOT NULL UNIQUE,
  `usuario` VARCHAR(50) NOT NULL UNIQUE,
  `clave` VARCHAR(255) NOT NULL,
  `rol` VARCHAR(50) NOT NULL DEFAULT 'Admin',
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_usuario_login` (`usuario`, `estado`),
  INDEX `idx_usuario_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 3. Table structure for table `permisos`
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `permisos`;
CREATE TABLE `permisos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 4. Table structure for table `detalle_permisos`
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `detalle_permisos`;
CREATE TABLE `detalle_permisos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_permiso` INT NOT NULL,
  `id_usuario` INT NOT NULL,
  INDEX `idx_dp_usuario` (`id_usuario`),
  INDEX `idx_dp_permiso` (`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 5. Table structure for table `users` (Cardholders: Students / Teachers / Staff)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(150) NOT NULL,
  `id_code` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `grade` VARCHAR(50) DEFAULT NULL,
  `section` VARCHAR(50) DEFAULT NULL,
  `specialty` VARCHAR(100) DEFAULT NULL,
  `telephone_number` VARCHAR(30) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `responsible_names` VARCHAR(150) DEFAULT NULL,
  `responsible_phones` VARCHAR(50) DEFAULT NULL,
  `responsible_emails` VARCHAR(100) DEFAULT NULL,
  `date_birth` DATE DEFAULT NULL,
  `funds` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `state` TINYINT(1) NOT NULL DEFAULT 1,
  `image` VARCHAR(255) DEFAULT 'assets/img/default-profile-picture.png',
  `user_type` VARCHAR(50) DEFAULT 'Estudiante',
  INDEX `idx_users_id_code` (`id_code`),
  INDEX `idx_users_email` (`email`),
  INDEX `idx_users_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 6. Table structure for table `producto` (Inventory Catalog)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `codproducto` INT AUTO_INCREMENT PRIMARY KEY,
  `codigo` VARCHAR(50) NOT NULL UNIQUE,
  `descripcion` VARCHAR(200) NOT NULL,
  `precio` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `precio_v` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `existencia` INT NOT NULL DEFAULT 0,
  `existencia_m` INT NOT NULL DEFAULT 0,
  `ganancia_u` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `usuario_id` INT DEFAULT NULL,
  INDEX `idx_producto_codigo` (`codigo`),
  INDEX `idx_producto_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 7. Table structure for table `ventas` (POS Sales Transactions)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `idv` INT AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `id_usuario` INT NOT NULL,
  `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_ventas_cliente` (`id_cliente`),
  INDEX `idx_ventas_usuario` (`id_usuario`),
  INDEX `idx_ventas_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 8. Table structure for table `detalle_venta` (Itemized Purchase Lines)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `detalle_venta`;
CREATE TABLE `detalle_venta` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_producto` INT NOT NULL,
  `id_venta` INT NOT NULL,
  `barcode` VARCHAR(50) DEFAULT NULL,
  `cantidad` INT NOT NULL DEFAULT 1,
  `precio` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  INDEX `idx_dv_venta` (`id_venta`),
  INDEX `idx_dv_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 9. Table structure for table `fondos` (Balance Recharge Top-Ups)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `fondos`;
CREATE TABLE `fondos` (
  `idv` INT AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `id_usuario` INT NOT NULL,
  `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_fondos_cliente` (`id_cliente`),
  INDEX `idx_fondos_usuario` (`id_usuario`),
  INDEX `idx_fondos_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 10. Table structure for table `detalle_fondos` (Recharge Audit Logs)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `detalle_fondos`;
CREATE TABLE `detalle_fondos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_venta` INT NOT NULL,
  `cantidad` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `barcode` VARCHAR(50) DEFAULT NULL,
  INDEX `idx_df_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 11. Table structure for table `devoluciones` (Refunds / Returns Header)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `devoluciones`;
CREATE TABLE `devoluciones` (
  `idv` INT AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `id_usuario` INT NOT NULL,
  `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_dev_cliente` (`id_cliente`),
  INDEX `idx_dev_usuario` (`id_usuario`),
  INDEX `idx_dev_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 12. Table structure for table `detalle_devoluciones` (Refund Line Items)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `detalle_devoluciones`;
CREATE TABLE `detalle_devoluciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_producto` INT NOT NULL,
  `id_venta` INT NOT NULL,
  `barcode` VARCHAR(50) DEFAULT NULL,
  `cantidad` INT NOT NULL DEFAULT 1,
  `precio` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  INDEX `idx_ddev_venta` (`id_venta`),
  INDEX `idx_ddev_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 13. Table structure for table `detalle_temp` (POS Cashier Active Session Cart)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `detalle_temp`;
CREATE TABLE `detalle_temp` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` INT NOT NULL,
  `id_producto` INT NOT NULL,
  `cantidad` INT NOT NULL DEFAULT 1,
  `precio_venta` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  INDEX `idx_dtemp_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 14. Table structure for table `devoluciones_temp` (Return Active Session Cart)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `devoluciones_temp`;
CREATE TABLE `devoluciones_temp` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` INT NOT NULL,
  `id_producto` INT NOT NULL,
  `cantidad` INT NOT NULL DEFAULT 1,
  `precio_venta` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  INDEX `idx_devtemp_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
