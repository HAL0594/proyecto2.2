DROP DATABASE login;
CREATE DATABASE IF NOT EXISTS `login` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE login;
 
CREATE TABLE IF NOT EXISTS `terceros` (
  `id_tercero` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `no_cuenta` int(11) NOT NULL,
  `nombre_tercero` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
 
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(130) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `last_session` datetime DEFAULT NULL,
  `activacion` int(11) NOT NULL DEFAULT '0',
  `telefono` int(11) NOT NULL DEFAULT '0',
  `no_cuenta` int(11) NOT NULL DEFAULT '0',
  `token` varchar(40) NOT NULL,
  `password_request` int(11) DEFAULT '0',
  `id_tipo` int(11) NOT NULL,
  `saldo_cuenta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
ALTER TABLE `terceros`
  ADD PRIMARY KEY (`id_tercero`);
 
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

ALTER TABLE `terceros`
  MODIFY `id_tercero` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;