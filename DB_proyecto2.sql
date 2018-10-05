DROP DATABASE DBBANCA;
CREATE DATABASE IF NOT EXISTS `DBBANCA` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE DBBANCA;
 
CREATE TABLE IF NOT EXISTS `terceros` (
  `id_tercero` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` int(11) NOT NULL,
  `no_cuenta` int(11) NOT NULL,
  `permitido` int(11) NOT NULL,
  `token` varchar(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;
 
 
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(130) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `last_session` datetime DEFAULT NULL,
  `activacion` int(11) NOT NULL DEFAULT '0',
  `telefono` int(11) NOT NULL DEFAULT '0',
  `no_cuenta` int(11) NOT NULL DEFAULT '0',
  `token` varchar(40),
  `password_request` int(11) DEFAULT '0',
  `id_tipo` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;
 
CREATE TABLE IF NOT EXISTS `cuentas` (
  `no_cuenta` INT AUTO_INCREMENT PRIMARY KEY,
  `NombreCuenta` varchar(50),
  `DPI` varchar(50),
  `saldo` DOUBLE NOT NULL,
  `estado` boolean NOT NULL,
  `PIN` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `transacciones` (
  `id_Trans` INT AUTO_INCREMENT PRIMARY KEY,
  `descripcion` varchar(50),
  `no_cuenta_origen` int(11),
  `no_cuenta_destino` int(11),
  `cantidad` DOUBLE NOT NULL,
  `Fecha_Transaccion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `cuentas` (`no_cuenta`, `NombreCuenta`, `DPI`, `saldo`, `estado`, `PIN`) VALUES 
(NULL, 'Hugo Lepe', '0101-123456-123', '1000', '1', '123'),
(NULL, 'Marvin Cortez', '0101-123456-12543', '1000', '1','123');

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `password`, `nombre`, `correo`, `last_session`, `activacion`, `telefono`, `no_cuenta`, `token`, `password_request`, `id_tipo`) VALUES 
(NULL, 'HAL', '$2y$10$yT22kzUk9RuhStYxmiBei.a4k41urP30/3/BYR5TsTL0y.XLzqHhu', 'HUGO', 'lepe0594@gmail.com', NULL, '1', '123', '10000', NULL, '0', '2'),
(NULL, 'mcortez', '$2y$10$yT22kzUk9RuhStYxmiBei.a4k41urP30/3/BYR5TsTL0y.XLzqHhu', 'MARVIN', 'rjorge828@gmail.com', NULL, '1', '123', '10001', NULL, '0', '1');

INSERT INTO `terceros` (`id_usuario`, `no_cuenta`, `permitido`, `token`) VALUES 
(10000, 10001, 1,'8fe163dcb45290e153a9270b517bfe40');




CREATE TRIGGER `ActualizaSaldo` BEFORE INSERT ON `transacciones`
 FOR EACH ROW BEGIN
UPDATE cuentas SET saldo = saldo+new.cantidad WHERE no_cuenta = NEW.no_cuenta_destino;
IF NEW.no_cuenta_origen > 0 then 
UPDATE cuentas SET saldo = saldo-new.cantidad WHERE no_cuenta = NEW.no_cuenta_origen;
END IF;

END

