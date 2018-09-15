DROP DATABASE login;
CREATE DATABASE IF NOT EXISTS `login` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE login;
 
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
 
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL,
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
  `id_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id`);
 
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
 
ALTER TABLE `tipo_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;