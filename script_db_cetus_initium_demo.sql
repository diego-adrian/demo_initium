-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-08-2019 a las 14:34:14
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cetus_initium_demo`
--
CREATE DATABASE IF NOT EXISTS `cetus_initium_demo` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cetus_initium_demo`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `zsp_generate_audit` (IN `audit_schema_name` VARCHAR(255), IN `audit_table_name` VARCHAR(255), OUT `script` LONGTEXT, OUT `errors` LONGTEXT)  main_block: BEGIN

			DECLARE trg_insert, trg_update, trg_delete, vw_audit, vw_audit_meta, out_errors LONGTEXT;
			DECLARE stmt, header LONGTEXT;
			DECLARE at_id1, at_id2 LONGTEXT;
			DECLARE c INTEGER;

				SET SESSION group_concat_max_len = 100000;

			SET out_errors := '';

				SET c := (SELECT COUNT(*) FROM information_schema.tables
					WHERE BINARY TABLE_SCHEMA = BINARY audit_schema_name 
						AND BINARY TABLE_NAME = BINARY audit_table_name);
			IF c <> 1 THEN
				SET out_errors := CONCAT( out_errors, '\n', 'The table you specified `', audit_schema_name, '`.`', audit_table_name, '` does not exists.' );
				LEAVE main_block;
			END IF;


				SET c := (SELECT COUNT(*) FROM information_schema.tables
					WHERE BINARY TABLE_SCHEMA = BINARY audit_schema_name 
						AND (BINARY TABLE_NAME = BINARY 'auditoria' OR BINARY table_name = BINARY 'auditoria_meta') );
			IF c <> 2 THEN
				SET out_errors := CONCAT( out_errors, '\n', 'Audit table structure do not exists, please check or run the audit setup script again.' );
			END IF;


				SET c := ( SELECT GROUP_CONCAT( TRIGGER_NAME SEPARATOR ', ') FROM information_schema.triggers
					WHERE BINARY EVENT_OBJECT_SCHEMA = BINARY audit_schema_name 
						AND BINARY EVENT_OBJECT_TABLE = BINARY audit_table_name 
						AND BINARY ACTION_TIMING = BINARY 'AFTER' AND BINARY TRIGGER_NAME NOT LIKE BINARY CONCAT('z', audit_table_name, '_%') GROUP BY EVENT_OBJECT_TABLE );
			IF c IS NOT NULL AND LENGTH(c) > 0 THEN
				SET out_errors := CONCAT( out_errors, '\n', 'MySQL 5 only supports one trigger per insert/update/delete action. Currently there are these triggers (', c, ') already assigned to `', audit_schema_name, '`.`', audit_table_name, '`. You must remove them before the audit trigger can be applied' );
			END IF;

			

				SET at_id1 := (SELECT COLUMN_NAME FROM information_schema.columns
					WHERE BINARY TABLE_SCHEMA = BINARY audit_schema_name 
						AND BINARY TABLE_NAME = BINARY audit_table_name
					AND column_key = 'PRI' LIMIT 1);

				SET at_id2 := (SELECT COLUMN_NAME FROM information_schema.columns
					WHERE BINARY TABLE_SCHEMA = BINARY audit_schema_name 
						AND BINARY TABLE_NAME = BINARY audit_table_name
					AND column_key = 'PRI' LIMIT 1,1);

				IF at_id1 IS NULL AND at_id2 IS NULL THEN 
				SET out_errors := CONCAT( out_errors, '\n', 'The table you specified `', audit_schema_name, '`.`', audit_table_name, '` does not have any primary key.' );
			END IF;



			SET header := CONCAT( 
				'-- --------------------------------------------------------------------\n',
				'-- MySQL Audit Trigger\n',
				'-- --------------------------------------------------------------------\n\n'		
			);

			
			SET trg_insert := CONCAT( 'DROP TRIGGER IF EXISTS `', audit_schema_name, '`.`z', audit_table_name, '_AINS`\n$$\n',
								'CREATE TRIGGER `', audit_schema_name, '`.`z', audit_table_name, '_AINS` AFTER INSERT ON `', audit_schema_name, '`.`', audit_table_name, '` FOR EACH ROW \nBEGIN\n', header );
			SET trg_update := CONCAT( 'DROP TRIGGER IF EXISTS `', audit_schema_name, '`.`z', audit_table_name, '_AUPD`\n$$\n',
								'CREATE TRIGGER `', audit_schema_name, '`.`z', audit_table_name, '_AUPD` AFTER UPDATE ON `', audit_schema_name, '`.`', audit_table_name, '` FOR EACH ROW \nBEGIN\n', header );
			SET trg_delete := CONCAT( 'DROP TRIGGER IF EXISTS `', audit_schema_name, '`.`z', audit_table_name, '_ADEL`\n$$\n',
								'CREATE TRIGGER `', audit_schema_name, '`.`z', audit_table_name, '_ADEL` AFTER DELETE ON `', audit_schema_name, '`.`', audit_table_name, '` FOR EACH ROW \nBEGIN\n', header );

			SET stmt := 'DECLARE auditoria_last_inserted_id BIGINT(20);\n\n';
			SET trg_insert := CONCAT( trg_insert, stmt );
			SET trg_update := CONCAT( trg_update, stmt );
			SET trg_delete := CONCAT( trg_delete, stmt );


					
			SET stmt := CONCAT( 'INSERT IGNORE INTO `', audit_schema_name, '`.auditoria (accion_usuario, table_name, pk1, ', CASE WHEN at_id2 IS NULL THEN '' ELSE 'pk2, ' END , 'action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), ', 
				'''', audit_table_name, ''', ', 'NEW.`', at_id1, '`, ', IFNULL( CONCAT('NEW.`', at_id2, '`, ') , '') );

			SET trg_insert := CONCAT( trg_insert, stmt, '''INSERT''); \n\n');

			SET stmt := CONCAT( 'INSERT IGNORE INTO `', audit_schema_name, '`.auditoria (accion_usuario, table_name, pk1, ', CASE WHEN at_id2 IS NULL THEN '' ELSE 'pk2, ' END , 'action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), ', 
				'''', audit_table_name, ''', ', 'OLD.`', at_id1, '`, ', IFNULL( CONCAT('OLD.`', at_id2, '`, ') , '') );

			SET trg_update := CONCAT( trg_update, stmt, '''UPDATE''); \n\n' );
			SET trg_delete := CONCAT( trg_delete, stmt, '''DELETE''); \n\n' );


			SET stmt := 'SET auditoria_last_inserted_id = LAST_INSERT_ID();\n';
			SET trg_insert := CONCAT( trg_insert, stmt );
			SET trg_update := CONCAT( trg_update, stmt );
			SET trg_delete := CONCAT( trg_delete, stmt );
			
			SET stmt := CONCAT( 'INSERT IGNORE INTO `', audit_schema_name, '`.auditoria_meta (audit_id, col_name, old_value, new_value) VALUES \n' );
			SET trg_insert := CONCAT( trg_insert, '\n', stmt );
			SET trg_update := CONCAT( trg_update, '\n', stmt );
			SET trg_delete := CONCAT( trg_delete, '\n', stmt );

			SET stmt := ( SELECT GROUP_CONCAT(' (auditoria_last_inserted_id, ''', COLUMN_NAME, ''', NULL, ',	
								CASE WHEN INSTR( '|binary|varbinary|tinyblob|blob|mediumblob|longblob|', LOWER(DATA_TYPE) ) <> 0 THEN 
									'''[UNSUPPORTED BINARY DATATYPE]''' 
								ELSE 						
									CONCAT('NEW.`', COLUMN_NAME, '`')
								END,
								'),'
							SEPARATOR '\n')
							FROM information_schema.columns
								WHERE BINARY TABLE_SCHEMA = BINARY audit_schema_name
									AND BINARY TABLE_NAME = BINARY audit_table_name );

			SET stmt := CONCAT( TRIM( TRAILING ',' FROM stmt ), ';\n\nEND\n$$' );
			SET trg_insert := CONCAT( trg_insert, stmt );



			SET stmt := ( SELECT GROUP_CONCAT('   (auditoria_last_inserted_id, ''', COLUMN_NAME, ''', ', 
								CASE WHEN INSTR( '|binary|varbinary|tinyblob|blob|mediumblob|longblob|', LOWER(DATA_TYPE) ) <> 0 THEN
									'''[SAME]'''
								ELSE
									CONCAT('OLD.`', COLUMN_NAME, '`')
								END,
								', ',
								CASE WHEN INSTR( '|binary|varbinary|tinyblob|blob|mediumblob|longblob|', LOWER(DATA_TYPE) ) <> 0 THEN
									CONCAT('CASE WHEN BINARY OLD.`', COLUMN_NAME, '` <=> BINARY NEW.`', COLUMN_NAME, '` THEN ''[SAME]'' ELSE ''[CHANGED]'' END')
								ELSE
									CONCAT('NEW.`', COLUMN_NAME, '`')
								END,
								'),'
							SEPARATOR '\n') 
							FROM information_schema.columns
								WHERE BINARY TABLE_SCHEMA = BINARY audit_schema_name 
									AND BINARY TABLE_NAME = BINARY audit_table_name );

			SET stmt := CONCAT( TRIM( TRAILING ',' FROM stmt ), ';\n\nEND\n$$' );
			SET trg_update := CONCAT( trg_update, stmt );



			SET stmt := ( SELECT GROUP_CONCAT('   (auditoria_last_inserted_id, ''', COLUMN_NAME, ''', ', 
								CASE WHEN INSTR( '|binary|varbinary|tinyblob|blob|mediumblob|longblob|', LOWER(DATA_TYPE) ) <> 0 THEN 
									'''[UNSUPPORTED BINARY DATATYPE]''' 
								ELSE 						
									CONCAT('OLD.`', COLUMN_NAME, '`')
								END,
								', NULL ),'
							SEPARATOR '\n') 
							FROM information_schema.columns
								WHERE BINARY TABLE_SCHEMA = BINARY audit_schema_name 
									AND BINARY TABLE_NAME = BINARY audit_table_name );


			SET stmt := CONCAT( TRIM( TRAILING ',' FROM stmt ), ';\n\nEND\n$$' );
			SET trg_delete := CONCAT( trg_delete, stmt );

			
			SET stmt = CONCAT( 
				'-- ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^\n',
				'-- --------------------------------------------------------------------\n',
				'-- Scritp de Auditoria para `',audit_schema_name, '`.`', audit_table_name, '`\n',
				'-- Fecha Generacion: ', NOW(), '\n',
				'-- Generado por CETUS GROUP - JRAD - : ', CURRENT_USER(), '\n',
				'-- BEGIN\n',
				'-- --------------------------------------------------------------------\n\n'	
				'DELIMITER $$',
				'\n\n-- [ `',audit_schema_name, '`.`', audit_table_name, '` After Insert Trigger Code ]\n',		
				'-- -----------------------------------------------------------\n',
				trg_insert,
				'\n\n-- [ `',audit_schema_name, '`.`', audit_table_name, '` After Update Trigger Code ]\n',
				'-- -----------------------------------------------------------\n',
				trg_update,
				'\n\n-- [ `',audit_schema_name, '`.`', audit_table_name, '` After Delete Trigger Code ]\n',		
				'-- -----------------------------------------------------------\n',
				trg_delete,
				'\n\n-- [ `',audit_schema_name, '`.`', audit_table_name, '` Audit Meta View ]\n',		
				'-- -----------------------------------------------------------\n',
				'-- --------------------------------------------------------------------\n',
				'-- END\n',
				'-- Scritp de Auditoria para `',audit_schema_name, '`.`', audit_table_name, '`\n',		
				'-- --------------------------------------------------------------------\n\n',
				'-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$\n'
				);

			
			SET script := stmt;
			SET errors := out_errors;
		END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `zsp_generate_batch_audit` (IN `audit_schema_name` VARCHAR(255), IN `audit_table_names` VARCHAR(255), OUT `out_script` LONGTEXT, OUT `out_error_msgs` LONGTEXT)  main_block: BEGIN

			DECLARE s, e, scripts, error_msgs LONGTEXT;
			DECLARE audit_table_name VARCHAR(255);
			DECLARE done INT DEFAULT FALSE;
			DECLARE cursor_table_list CURSOR FOR SELECT table_name FROM INFORMATION_SCHEMA.TABLES 
				WHERE BINARY TABLE_TYPE = BINARY 'BASE TABLE' 
					AND BINARY TABLE_SCHEMA = BINARY audit_schema_name
					AND LOCATE( BINARY CONCAT(table_name, ','), BINARY CONCAT(audit_table_names, ',') ) > 0;

			DECLARE CONTINUE HANDLER
				FOR NOT FOUND SET done = TRUE;

			SET scripts := '';
			SET error_msgs := '';

			OPEN cursor_table_list;

			cur_loop: LOOP
				FETCH cursor_table_list INTO audit_table_name;

				IF done THEN
					LEAVE cur_loop;
				END IF;

				CALL zsp_generate_audit(audit_schema_name, audit_table_name, s, e);

				SET scripts := CONCAT( scripts, '\n\n', IFNULL(s, '') );
				SET error_msgs := CONCAT( error_msgs, '\n\n', IFNULL(e, '') );

			END LOOP;

			CLOSE cursor_table_list;

			SET out_script := scripts;
			SET out_error_msgs := error_msgs;
		END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` int(11) NOT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `api_keys`
--

INSERT INTO `api_keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`, `accion_usuario`, `accion_fecha`) VALUES
(1, 1, '3igu8utjgis90omnbfgh3igu8utjgis90omnbfgh', 1, 0, 0, '127.0.0.1', 1234567890, NULL, NULL);

--
-- Disparadores `api_keys`
--
DELIMITER $$
CREATE TRIGGER `zapi_keys_ADEL` AFTER DELETE ON `api_keys` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'api_keys', OLD.`id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'id', OLD.`id`, NULL ),
  (auditoria_last_inserted_id, 'user_id', OLD.`user_id`, NULL ),
  (auditoria_last_inserted_id, 'key', OLD.`key`, NULL ),
  (auditoria_last_inserted_id, 'level', OLD.`level`, NULL ),
  (auditoria_last_inserted_id, 'ignore_limits', OLD.`ignore_limits`, NULL ),
  (auditoria_last_inserted_id, 'is_private_key', OLD.`is_private_key`, NULL ),
  (auditoria_last_inserted_id, 'ip_addresses', OLD.`ip_addresses`, NULL ),
  (auditoria_last_inserted_id, 'date_created', OLD.`date_created`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zapi_keys_AINS` AFTER INSERT ON `api_keys` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'api_keys', NEW.`id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'id', NULL, NEW.`id`),
(auditoria_last_inserted_id, 'user_id', NULL, NEW.`user_id`),
(auditoria_last_inserted_id, 'key', NULL, NEW.`key`),
(auditoria_last_inserted_id, 'level', NULL, NEW.`level`),
(auditoria_last_inserted_id, 'ignore_limits', NULL, NEW.`ignore_limits`),
(auditoria_last_inserted_id, 'is_private_key', NULL, NEW.`is_private_key`),
(auditoria_last_inserted_id, 'ip_addresses', NULL, NEW.`ip_addresses`),
(auditoria_last_inserted_id, 'date_created', NULL, NEW.`date_created`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zapi_keys_AUPD` AFTER UPDATE ON `api_keys` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'api_keys', OLD.`id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'id', OLD.`id`, NEW.`id`),
  (auditoria_last_inserted_id, 'user_id', OLD.`user_id`, NEW.`user_id`),
  (auditoria_last_inserted_id, 'key', OLD.`key`, NEW.`key`),
  (auditoria_last_inserted_id, 'level', OLD.`level`, NEW.`level`),
  (auditoria_last_inserted_id, 'ignore_limits', OLD.`ignore_limits`, NEW.`ignore_limits`),
  (auditoria_last_inserted_id, 'is_private_key', OLD.`is_private_key`, NEW.`is_private_key`),
  (auditoria_last_inserted_id, 'ip_addresses', OLD.`ip_addresses`, NEW.`ip_addresses`),
  (auditoria_last_inserted_id, 'date_created', OLD.`date_created`, NEW.`date_created`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `application_logs`
--

CREATE TABLE `application_logs` (
  `applicationlogid` int(11) NOT NULL,
  `application_name` varchar(50) DEFAULT NULL,
  `date_time` timestamp NULL DEFAULT NULL,
  `login` varchar(10) DEFAULT NULL,
  `ip_user` varchar(20) DEFAULT NULL,
  `action_held` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `audit_id` bigint(20) UNSIGNED NOT NULL,
  `accion_usuario` varchar(255) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `pk1` varchar(255) DEFAULT NULL,
  `pk2` varchar(255) DEFAULT NULL,
  `action` varchar(6) DEFAULT NULL COMMENT 'Values: insert|update|delete',
  `accion_fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_acceso`
--

CREATE TABLE `auditoria_acceso` (
  `auditoria_id` int(11) NOT NULL,
  `auditoria_usuario` varchar(50) NOT NULL,
  `auditoria_tipo_acceso` varchar(10) NOT NULL,
  `auditoria_fecha` datetime NOT NULL,
  `auditoria_ip` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_meta`
--

CREATE TABLE `auditoria_meta` (
  `audit_meta_id` bigint(20) NOT NULL,
  `audit_id` bigint(20) UNSIGNED NOT NULL,
  `col_name` varchar(255) DEFAULT NULL,
  `old_value` longtext,
  `new_value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_movil`
--

CREATE TABLE `auditoria_movil` (
  `auditoria_movil_id` int(11) NOT NULL,
  `auditoria_movil_servicio` varchar(100) DEFAULT NULL,
  `auditoria_movil_parametros` text,
  `auditoria_movil_geo` varchar(45) DEFAULT NULL COMMENT 'Coordenadas X y Y',
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

CREATE TABLE `calendario` (
  `cal_id` int(11) NOT NULL,
  `ejecutivo_id` int(11) NOT NULL,
  `cal_id_visita` int(11) DEFAULT NULL COMMENT 'Código Identificador de la visita (prospecto o mantenimiento)',
  `cal_tipo_visita` int(1) DEFAULT NULL COMMENT 'La visita puede ser prospecto (nuevo comercio o establecimiento/sucursal) o mantenimiento.\n\n1 = Prospecto\n2 = Mantenimiento',
  `cal_visita_ini` datetime DEFAULT NULL,
  `cal_visita_fin` datetime DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `calendario`
--

INSERT INTO `calendario` (`cal_id`, `ejecutivo_id`, `cal_id_visita`, `cal_tipo_visita`, `cal_visita_ini`, `cal_visita_fin`, `accion_usuario`, `accion_fecha`) VALUES
(163, 3, 121, 1, '2018-10-09 16:55:00', '2018-10-09 17:10:00', 'usuario.gestion', '2018-10-08 16:56:03'),
(164, 3, 122, 1, '2018-11-13 10:10:00', '2018-11-13 10:25:00', 'usuario.gestion', '2018-11-12 10:10:28'),
(165, 3, 123, 1, '2018-10-09 17:05:00', '2018-10-09 17:20:00', 'usuario.gestion', '2018-10-08 17:06:10'),
(166, 3, 124, 1, '2018-10-11 15:00:00', '2018-10-11 15:15:00', 'usuario.gestion', '2018-10-11 12:14:56'),
(167, 10, 125, 1, '2018-10-11 17:08:00', '2018-10-11 18:08:00', 'usuario.gestion', '2018-10-11 17:06:28'),
(168, 10, 126, 1, '2018-10-11 17:08:00', '2018-10-11 18:08:00', 'usuario.gestion', '2018-10-11 17:06:28'),
(169, 10, 127, 1, '2018-10-11 17:08:00', '2018-10-11 18:08:00', 'usuario.gestion', '2018-10-11 17:06:28'),
(170, 3, 1, 2, '2018-10-12 12:30:00', '2018-10-12 13:30:00', 'usuario.app', '2018-10-12 09:54:48'),
(171, 3, 2, 2, '2018-10-12 12:30:00', '2018-10-12 15:30:00', 'usuario.app', '2018-10-12 09:56:19'),
(172, 3, 3, 2, '2018-10-13 00:52:00', '2018-10-13 14:52:00', 'usuario.app', '2018-10-12 10:52:25'),
(173, 3, 4, 2, '2018-10-12 16:00:00', '2018-10-12 16:30:00', 'usuario.app', '2018-10-12 14:03:53'),
(174, 3, 5, 2, '2018-11-14 08:30:00', '2018-11-14 09:30:00', 'usuario.app', '2018-11-14 15:27:14'),
(175, 3, 6, 2, '2018-11-20 18:30:00', '2018-11-20 19:30:00', 'usuario.app', '2018-11-14 15:31:24'),
(176, 3, 7, 2, '2018-11-14 00:31:00', '2018-11-14 00:32:00', 'usuario.app', '2018-11-14 15:32:08'),
(177, 3, 8, 2, '2018-11-14 10:30:00', '2018-11-14 10:34:00', 'usuario.app', '2018-11-14 15:34:50'),
(178, 3, 9, 2, '2018-11-14 10:30:00', '2018-11-14 10:35:00', 'usuario.app', '2018-11-14 15:35:25'),
(179, 3, 10, 2, '2018-11-14 00:32:00', '2018-11-14 08:30:00', 'usuario.app', '2018-11-14 15:38:40'),
(180, 3, 129, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:43:56'),
(181, 3, 130, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:43:56'),
(182, 3, 131, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:47:56'),
(183, 3, 132, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:49:09'),
(184, 3, 133, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:49:54'),
(185, 3, 134, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:50:39'),
(186, 3, 135, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:54:07'),
(187, 3, 136, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:54:07'),
(188, 3, 137, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:58:11'),
(189, 3, 138, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:58:53'),
(190, 3, 139, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:59:53'),
(191, 3, 140, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 13:59:53'),
(192, 3, 141, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 14:01:31'),
(193, 3, 142, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 14:01:31'),
(194, 3, 143, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 14:06:33'),
(195, 3, 144, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 14:06:33'),
(196, 3, 145, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 14:58:41'),
(197, 3, 146, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:01:13'),
(198, 3, 147, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:01:13'),
(199, 3, 148, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:04:43'),
(200, 3, 149, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:04:43'),
(201, 3, 150, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:06:14'),
(202, 3, 151, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:06:14'),
(203, 3, 152, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:08:00'),
(204, 3, 153, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:08:00'),
(205, 3, 154, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:09:06'),
(206, 3, 155, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:09:06'),
(207, 3, 156, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:09:58'),
(208, 3, 157, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:09:58'),
(209, 3, 158, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:11:38'),
(210, 3, 159, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:11:38'),
(211, 3, 160, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:12:49'),
(212, 3, 161, 1, '2018-11-30 08:00:00', '2018-11-30 15:00:00', 'usuario.gestion', '2018-11-30 15:12:49'),
(213, 3, 162, 1, '2018-12-14 08:00:00', '2018-12-14 15:00:00', 'usuario.app', '2018-12-14 22:39:27'),
(214, 3, 163, 1, '2018-12-14 08:00:00', '2018-12-14 15:00:00', 'usuario.app', '2018-12-14 22:41:56'),
(215, 3, 164, 1, '2018-12-14 08:00:00', '2018-12-14 15:00:00', 'usuario.app', '2018-12-14 22:43:52'),
(216, 3, 165, 1, '2018-12-14 08:00:00', '2018-12-14 15:00:00', 'usuario.app', '2018-12-14 22:45:23'),
(217, 3, 166, 1, '2018-12-17 08:00:00', '2018-12-17 15:00:00', 'usuario.app', '2018-12-17 10:09:28'),
(218, 3, 167, 1, '2018-12-18 08:00:00', '2018-12-18 15:00:00', 'usuario.app', '2018-12-18 16:52:25'),
(219, 3, 168, 1, '2018-12-18 08:00:00', '2018-12-18 15:00:00', 'usuario.app', '2018-12-18 16:55:05'),
(220, 3, 169, 1, '2018-12-18 08:00:00', '2018-12-18 15:00:00', 'usuario.app', '2018-12-18 16:56:46'),
(221, 3, 170, 1, '2018-12-18 08:00:00', '2018-12-18 15:00:00', 'usuario.app', '2018-12-18 17:10:45'),
(222, 3, 11, 2, '2018-12-20 12:30:00', '2018-12-20 12:30:00', 'usuario.app', '2018-12-20 10:37:42'),
(223, 3, 120, 1, '2018-10-09 16:55:00', '2018-10-09 17:10:00', 'usuario.gestion', '2018-10-08 16:56:03'),
(224, 19, 171, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(225, 14, 172, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(226, 18, 173, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(227, 16, 174, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(228, 18, 175, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(229, 21, 176, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(230, 14, 177, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(231, 14, 178, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(232, 13, 179, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2019-03-29 14:13:50'),
(233, 19, 180, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(234, 18, 181, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(235, 21, 182, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(236, 22, 183, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(237, 16, 184, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(238, 21, 185, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(239, 21, 186, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(240, 15, 187, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(241, 16, 188, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(242, 15, 189, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(243, 14, 190, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(244, 13, 191, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2019-03-29 14:13:50'),
(245, 15, 192, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(246, 18, 193, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(247, 16, 194, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(248, 12, 195, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(249, 11, 196, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(250, 16, 197, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(251, 22, 198, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(252, 12, 199, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(253, 12, 200, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(254, 14, 201, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(255, 15, 202, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(256, 13, 203, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2019-03-29 14:13:50'),
(257, 11, 204, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(258, 13, 205, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2019-03-29 14:13:50'),
(259, 21, 206, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(260, 11, 207, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(261, 19, 208, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(262, 22, 209, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(263, 22, 210, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(264, 22, 211, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'usuario.gestion', '2018-12-27 11:59:31'),
(265, 11, 12, 2, '2018-12-29 09:00:00', '2018-12-29 14:00:00', 'B02978', '2018-12-27 12:47:42'),
(266, 18, 212, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'S40259', '2018-12-27 17:36:06'),
(267, 11, 213, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'B02978', '2018-12-27 17:37:08'),
(268, 11, 214, 1, '2018-12-27 08:00:00', '2018-12-27 15:00:00', 'B02978', '2018-12-27 17:37:13'),
(269, 3, 215, 1, '2019-07-10 08:00:00', '2019-07-10 15:00:00', 'usuario.app', '2019-07-10 11:29:40'),
(270, 3, 216, 1, '2019-07-11 08:00:00', '2019-07-11 15:00:00', 'usuario.gestion', '2019-07-11 11:01:13'),
(271, 3, 217, 1, '2019-07-11 08:00:00', '2019-07-11 15:00:00', 'usuario.gestion', '2019-07-11 11:08:23'),
(272, 3, 218, 1, '2019-07-11 08:00:00', '2019-07-11 15:00:00', 'usuario.gestion', '2019-07-11 11:15:18'),
(273, 3, 219, 1, '2019-07-11 08:00:00', '2019-07-11 15:00:00', 'usuario.gestion', '2019-07-11 11:15:18'),
(274, 3, 220, 1, '2019-07-11 08:00:00', '2019-07-11 15:00:00', 'usuario.gestion', '2019-07-11 11:15:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campana`
--

CREATE TABLE `campana` (
  `camp_id` int(11) NOT NULL,
  `camtip_id` int(11) NOT NULL,
  `camp_codigo` varchar(100) DEFAULT NULL COMMENT 'Codigo identificador del CRM de BCP',
  `camp_nombre` varchar(45) DEFAULT NULL,
  `camp_desc` varchar(255) DEFAULT NULL,
  `camp_plazo` int(11) DEFAULT NULL,
  `camp_monto_oferta` int(11) DEFAULT NULL,
  `camp_tasa` decimal(17,2) DEFAULT NULL,
  `camp_fecha_inicio` date DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `campana`
--

INSERT INTO `campana` (`camp_id`, `camtip_id`, `camp_codigo`, `camp_nombre`, `camp_desc`, `camp_plazo`, `camp_monto_oferta`, `camp_tasa`, `camp_fecha_inicio`, `accion_usuario`, `accion_fecha`) VALUES
(1, 1, NULL, 'CAPITAL_TRABAJO_PYME_201810', 'Capital Trabajo Pyme 201810', 90, 10000, '45.34', '2018-03-15', 'usuario.gestion', '2018-12-27 18:14:06'),
(2, 1, NULL, 'CREDITO_EFECTIVO_201810', 'Credito Efectivo 201810', 90, 343, '2323.26', '2018-12-28', 'usuario.gestion', '2018-12-27 17:05:39'),
(3, 1, NULL, 'PASIVOS_AHO_CONSUMO_201810', 'Pasivos Ahorro Consumo 201810', 90, 45654, '12.25', '2018-12-28', 'usuario.gestion', '2018-12-27 17:06:32'),
(5, 1, NULL, 'PASIVOS_AHO_PREMIUM_201810', 'Pasivos Ahorro Premium', 90, 45000, '12.50', '2018-12-28', 'usuario.gestion', '2018-12-27 17:06:53'),
(6, 1, NULL, 'PASIVOS_AHO_PYME_201810', 'Pasivos Ahorro Pyme 201810', 90, 321, '321.00', '2018-12-28', 'usuario.gestion', '2018-12-27 17:07:11'),
(7, 1, NULL, 'REFINANCIAMIENTO_PYME_201810', 'Refinanciamiento Pyme 201810', 90, 321, '321.00', '2018-12-28', 'usuario.gestion', '2018-12-27 17:07:33'),
(8, 1, NULL, 'TARJETA_CREDITO_201810', 'Tarjeta Credito', 90, 321, '31.00', '2018-12-28', 'usuario.gestion', '2018-12-27 17:08:04'),
(9, 1, NULL, 'CAPITAL_TRABAJO_PYME_201903', 'CAPITAL_TRABAJO_PYME_201903', 0, 100, '0.00', '0000-00-00', 'usuario.gestion', '2019-07-10 18:40:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campana_servicio`
--

CREATE TABLE `campana_servicio` (
  `campana_servicio_id` int(11) NOT NULL,
  `camp_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `campana_servicio`
--

INSERT INTO `campana_servicio` (`campana_servicio_id`, `camp_id`, `servicio_id`, `accion_usuario`, `accion_fecha`) VALUES
(179, 2, 4, 'usuario.gestion', '2018-12-27 17:05:39'),
(180, 3, 2, 'usuario.gestion', '2018-12-27 17:06:32'),
(181, 5, 2, 'usuario.gestion', '2018-12-27 17:06:53'),
(182, 6, 2, 'usuario.gestion', '2018-12-27 17:07:11'),
(183, 7, 3, 'usuario.gestion', '2018-12-27 17:07:33'),
(184, 8, 5, 'usuario.gestion', '2018-12-27 17:08:04'),
(186, 1, 1, 'usuario.gestion', '2018-12-27 18:14:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campana_tipo`
--

CREATE TABLE `campana_tipo` (
  `camtip_id` int(11) NOT NULL,
  `camtip_nombre` varchar(45) DEFAULT NULL,
  `camtip_desc` varchar(255) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `campana_tipo`
--

INSERT INTO `campana_tipo` (`camtip_id`, `camtip_nombre`, `camtip_desc`, `accion_usuario`, `accion_fecha`) VALUES
(1, 'Tipo Estándar', 'Tipo 1', 'test', '2018-11-13 12:26:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `catalogo_id` int(11) NOT NULL,
  `catalogo_parent` int(11) DEFAULT '-1' COMMENT 'Si la lista depende de algun registro (p.e. Departamento => Ciudad), se registra el ID del registro padre',
  `catalogo_tipo_codigo` varchar(5) NOT NULL COMMENT 'IDENTIFICARÁ EL TIPO DE CATÁLOGO\n\nTipo de Sociedad: (TPS)\nRubro: (RUB)\nPerfil Comercial: (PEC)\nMCC: (MCC)\nMedio de Contacto: (MCO)\nDepartamento: (DEP)\nMunicipio/Ciudad: (CIU)\nZona/Localidad: (ZON)\nTipo de Calle: (TPC)',
  `catalogo_codigo` int(4) NOT NULL,
  `catalogo_descripcion` varchar(100) NOT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`catalogo_id`, `catalogo_parent`, `catalogo_tipo_codigo`, `catalogo_codigo`, `catalogo_descripcion`, `accion_usuario`, `accion_fecha`) VALUES
(1, -1, 'DEP', 1, 'La Paz', 'usuario.gestion', '2017-09-04 17:05:45'),
(2, -1, 'DEP', 2, 'Cochabamba', 'usuario.gestion', '2017-08-29 09:25:35'),
(3, -1, 'DEP', 3, 'Santa Cruz', 'usuario.gestion', '2017-08-29 09:25:23'),
(4, -1, 'DEP', 4, 'Beni', 'usuario.gestion', '2017-08-29 09:26:39'),
(5, -1, 'DEP', 5, 'Pando', 'usuario.gestion', '2017-08-29 09:28:04'),
(6, -1, 'DEP', 6, 'Oruro', 'usuario.gestion', '2017-08-29 09:29:06'),
(7, -1, 'DEP', 7, 'Potosí', 'usuario.gestion', '2017-08-29 09:29:17'),
(8, -1, 'DEP', 8, 'Tarija', 'usuario.gestion', '2017-08-29 09:29:36'),
(9, -1, 'DEP', 9, 'Chuquisaca', 'usuario.gestion', '2017-08-29 09:29:53'),
(10, -1, 'TPS', 1, 'Sociedad Anónima', 'usuario.gestion', '2017-09-05 12:13:32'),
(11, 13, 'RUB', 1, 'Actividades de Dulcería', 'usuario.gestion', '2017-10-26 11:56:25'),
(12, -1, 'PEC', 1, 'Unipersonal', 'usuario.gestion', '2017-09-04 15:21:27'),
(13, -1, 'MCC', 5441, 'Dulcería', 'usuario.gestion', '2017-09-04 15:24:40'),
(14, -1, 'MCO', 1, 'Teléfono', 'usuario.gestion', '2017-09-04 15:27:10'),
(15, 1, 'CIU', 1, 'La Paz', 'usuario.gestion', '2017-11-27 09:34:43'),
(16, 15, 'ZON', 1, 'Zona Sur', 'usuario.gestion', '2017-09-04 17:10:20'),
(17, -1, 'TPC', 1, 'Avenida', 'usuario.gestion', '2017-09-04 15:40:53'),
(18, -1, 'MCO', 2, 'Celular', 'usuario.gestion', '2017-09-05 12:32:19'),
(19, -1, 'MCO', 3, 'Correo Lento', 'usuario.gestion', '2017-10-18 11:16:40'),
(20, -1, 'RUB', 4722, 'AGENCIA DE VIAJES', 'usuario.gestion', '2017-11-30 10:31:00'),
(21, -1, 'RUB', 5813, 'BARES/DISCOTECAS', 'usuario.gestion', '2017-11-30 10:31:00'),
(22, -1, 'RUB', 5651, 'BOUTIQUES', 'usuario.gestion', '2017-11-30 10:31:00'),
(23, -1, 'RUB', 5541, 'ESTACIONES DE SERVICIO VENTA GASOLINA/GNV', 'usuario.gestion', '2017-11-30 10:31:00'),
(24, -1, 'RUB', 5912, 'FARMACIAS', 'usuario.gestion', '2017-11-30 10:31:00'),
(25, -1, 'RUB', 7011, 'HOTELES', 'usuario.gestion', '2017-11-30 10:31:00'),
(26, -1, 'RUB', 5942, 'LIBRERIAS', 'usuario.gestion', '2017-11-30 10:31:00'),
(27, -1, 'RUB', 5812, 'RESTAURANTES', 'usuario.gestion', '2017-11-30 10:31:00'),
(28, -1, 'RUB', 5499, 'MICROMERCADOS', 'usuario.gestion', '2017-11-30 10:31:00'),
(29, -1, 'RUB', 5977, 'TIENDAS DE COSMÉTICOS', 'usuario.gestion', '2017-11-30 10:31:00'),
(30, -1, 'RUB', 5712, 'TIENDAS DE MUEBLES', 'usuario.gestion', '2017-11-30 10:31:00'),
(31, -1, 'RUB', 5533, 'TALLERES TIENDAS DE SERVICIO AUTOM', 'usuario.gestion', '2019-03-29 14:12:51'),
(32, -1, 'RUB', 5655, 'TIENDAS DE ROPA DEPORTIVA', 'usuario.gestion', '2017-11-30 10:31:00'),
(33, -1, 'RUB', 5661, 'TIENDAS DE ZAPATOS', 'usuario.gestion', '2017-11-30 10:31:00'),
(34, -1, 'TPS', 2, 'Unipersonal', 'usuario.gestion', '2018-04-10 17:45:58'),
(35, 3, 'CIU', 11, 'Santa Cruz de la Sierrra', 'usuario.gestion', '2018-10-09 10:43:05'),
(36, 9, 'CIU', 21, 'Sucre', 'usuario.gestion', '2018-10-09 11:49:19'),
(37, 8, 'CIU', 22, 'Tarija', 'usuario.gestion', '2018-10-09 11:49:49'),
(38, 7, 'CIU', 23, 'Potosí', 'usuario.gestion', '2018-10-09 11:50:11'),
(39, 2, 'CIU', 24, 'Cochabamba', 'usuario.gestion', '2018-10-09 11:50:31'),
(40, 6, 'CIU', 25, 'Oruro', 'usuario.gestion', '2018-10-09 11:50:53'),
(41, 4, 'CIU', 26, 'Beni', 'usuario.gestion', '2018-10-09 11:51:40'),
(42, 5, 'CIU', 27, 'Pando', 'usuario.gestion', '2018-10-09 11:52:15'),
(43, 15, 'ZON', 11, 'Mallasa', 'usuario.gestion', '2018-10-11 10:28:53'),
(44, 15, 'ZON', 12, 'San Antonio', 'usuario.gestion', '2018-10-11 10:30:43'),
(45, 15, 'ZON', 14, 'Periférica', 'usuario.gestion', '2018-10-11 10:31:02'),
(46, 15, 'ZON', 15, 'Max Paredes', 'usuario.gestion', '2018-10-11 10:31:24'),
(47, 15, 'ZON', 16, 'Centro', 'usuario.gestion', '2018-10-11 10:31:44'),
(48, 15, 'ZON', 17, 'Cotahuma', 'usuario.gestion', '2018-10-11 10:32:51'),
(49, 15, 'ZON', 123, 'Zongo', 'usuario.gestion', '2018-10-11 10:33:13'),
(50, 15, 'ZON', 122, 'Hampaturi', 'usuario.gestion', '2018-10-11 10:34:16'),
(51, -1, 'RUB', 52, 'TRANSPORTE Y ALMACENAMIENTO', 'usuario.gestion', '2018-10-11 10:41:11');

--
-- Disparadores `catalogo`
--
DELIMITER $$
CREATE TRIGGER `zcatalogo_ADEL` AFTER DELETE ON `catalogo` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'catalogo', OLD.`catalogo_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'catalogo_id', OLD.`catalogo_id`, NULL ),
  (auditoria_last_inserted_id, 'catalogo_parent', OLD.`catalogo_parent`, NULL ),
  (auditoria_last_inserted_id, 'catalogo_tipo_codigo', OLD.`catalogo_tipo_codigo`, NULL ),
  (auditoria_last_inserted_id, 'catalogo_codigo', OLD.`catalogo_codigo`, NULL ),
  (auditoria_last_inserted_id, 'catalogo_descripcion', OLD.`catalogo_descripcion`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zcatalogo_AINS` AFTER INSERT ON `catalogo` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'catalogo', NEW.`catalogo_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'catalogo_id', NULL, NEW.`catalogo_id`),
(auditoria_last_inserted_id, 'catalogo_parent', NULL, NEW.`catalogo_parent`),
(auditoria_last_inserted_id, 'catalogo_tipo_codigo', NULL, NEW.`catalogo_tipo_codigo`),
(auditoria_last_inserted_id, 'catalogo_codigo', NULL, NEW.`catalogo_codigo`),
(auditoria_last_inserted_id, 'catalogo_descripcion', NULL, NEW.`catalogo_descripcion`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zcatalogo_AUPD` AFTER UPDATE ON `catalogo` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'catalogo', OLD.`catalogo_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'catalogo_id', OLD.`catalogo_id`, NEW.`catalogo_id`),
  (auditoria_last_inserted_id, 'catalogo_parent', OLD.`catalogo_parent`, NEW.`catalogo_parent`),
  (auditoria_last_inserted_id, 'catalogo_tipo_codigo', OLD.`catalogo_tipo_codigo`, NEW.`catalogo_tipo_codigo`),
  (auditoria_last_inserted_id, 'catalogo_codigo', OLD.`catalogo_codigo`, NEW.`catalogo_codigo`),
  (auditoria_last_inserted_id, 'catalogo_descripcion', OLD.`catalogo_descripcion`, NEW.`catalogo_descripcion`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_credenciales`
--

CREATE TABLE `conf_credenciales` (
  `conf_id` varchar(8) NOT NULL,
  `conf_long_min` int(11) NOT NULL,
  `conf_long_max` int(11) NOT NULL,
  `conf_req_upper` int(1) NOT NULL,
  `conf_req_num` int(1) NOT NULL,
  `conf_req_esp` int(1) NOT NULL,
  `conf_duracion_min` int(11) NOT NULL,
  `conf_duracion_max` int(11) NOT NULL,
  `conf_tiempo_bloqueo` int(11) NOT NULL,
  `conf_defecto` varchar(50) NOT NULL,
  `conf_ejecutivo_ic` int(11) DEFAULT NULL COMMENT 'Índice de Cumplimiento para los reportes ',
  `accion_fecha` datetime NOT NULL,
  `accion_usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `conf_credenciales`
--

INSERT INTO `conf_credenciales` (`conf_id`, `conf_long_min`, `conf_long_max`, `conf_req_upper`, `conf_req_num`, `conf_req_esp`, `conf_duracion_min`, `conf_duracion_max`, `conf_tiempo_bloqueo`, `conf_defecto`, `conf_ejecutivo_ic`, `accion_fecha`, `accion_usuario`) VALUES
('conf-001', 4, 20, 0, 0, 0, 1, 60, 600, 'SesamoDemo123', 9, '2019-03-29 14:13:37', 'usuario.gestion');

--
-- Disparadores `conf_credenciales`
--
DELIMITER $$
CREATE TRIGGER `zconf_credenciales_ADEL` AFTER DELETE ON `conf_credenciales` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'conf_credenciales', OLD.`conf_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'conf_id', OLD.`conf_id`, NULL ),
  (auditoria_last_inserted_id, 'conf_long_min', OLD.`conf_long_min`, NULL ),
  (auditoria_last_inserted_id, 'conf_long_max', OLD.`conf_long_max`, NULL ),
  (auditoria_last_inserted_id, 'conf_req_upper', OLD.`conf_req_upper`, NULL ),
  (auditoria_last_inserted_id, 'conf_req_num', OLD.`conf_req_num`, NULL ),
  (auditoria_last_inserted_id, 'conf_req_esp', OLD.`conf_req_esp`, NULL ),
  (auditoria_last_inserted_id, 'conf_duracion_min', OLD.`conf_duracion_min`, NULL ),
  (auditoria_last_inserted_id, 'conf_duracion_max', OLD.`conf_duracion_max`, NULL ),
  (auditoria_last_inserted_id, 'conf_tiempo_bloqueo', OLD.`conf_tiempo_bloqueo`, NULL ),
  (auditoria_last_inserted_id, 'conf_defecto', OLD.`conf_defecto`, NULL ),
  (auditoria_last_inserted_id, 'conf_ejecutivo_ic', OLD.`conf_ejecutivo_ic`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zconf_credenciales_AINS` AFTER INSERT ON `conf_credenciales` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'conf_credenciales', NEW.`conf_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'conf_id', NULL, NEW.`conf_id`),
(auditoria_last_inserted_id, 'conf_long_min', NULL, NEW.`conf_long_min`),
(auditoria_last_inserted_id, 'conf_long_max', NULL, NEW.`conf_long_max`),
(auditoria_last_inserted_id, 'conf_req_upper', NULL, NEW.`conf_req_upper`),
(auditoria_last_inserted_id, 'conf_req_num', NULL, NEW.`conf_req_num`),
(auditoria_last_inserted_id, 'conf_req_esp', NULL, NEW.`conf_req_esp`),
(auditoria_last_inserted_id, 'conf_duracion_min', NULL, NEW.`conf_duracion_min`),
(auditoria_last_inserted_id, 'conf_duracion_max', NULL, NEW.`conf_duracion_max`),
(auditoria_last_inserted_id, 'conf_tiempo_bloqueo', NULL, NEW.`conf_tiempo_bloqueo`),
(auditoria_last_inserted_id, 'conf_defecto', NULL, NEW.`conf_defecto`),
(auditoria_last_inserted_id, 'conf_ejecutivo_ic', NULL, NEW.`conf_ejecutivo_ic`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zconf_credenciales_AUPD` AFTER UPDATE ON `conf_credenciales` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'conf_credenciales', OLD.`conf_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'conf_id', OLD.`conf_id`, NEW.`conf_id`),
  (auditoria_last_inserted_id, 'conf_long_min', OLD.`conf_long_min`, NEW.`conf_long_min`),
  (auditoria_last_inserted_id, 'conf_long_max', OLD.`conf_long_max`, NEW.`conf_long_max`),
  (auditoria_last_inserted_id, 'conf_req_upper', OLD.`conf_req_upper`, NEW.`conf_req_upper`),
  (auditoria_last_inserted_id, 'conf_req_num', OLD.`conf_req_num`, NEW.`conf_req_num`),
  (auditoria_last_inserted_id, 'conf_req_esp', OLD.`conf_req_esp`, NEW.`conf_req_esp`),
  (auditoria_last_inserted_id, 'conf_duracion_min', OLD.`conf_duracion_min`, NEW.`conf_duracion_min`),
  (auditoria_last_inserted_id, 'conf_duracion_max', OLD.`conf_duracion_max`, NEW.`conf_duracion_max`),
  (auditoria_last_inserted_id, 'conf_tiempo_bloqueo', OLD.`conf_tiempo_bloqueo`, NEW.`conf_tiempo_bloqueo`),
  (auditoria_last_inserted_id, 'conf_defecto', OLD.`conf_defecto`, NEW.`conf_defecto`),
  (auditoria_last_inserted_id, 'conf_ejecutivo_ic', OLD.`conf_ejecutivo_ic`, NEW.`conf_ejecutivo_ic`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_general`
--

CREATE TABLE `conf_general` (
  `conf_general_id` varchar(8) NOT NULL,
  `conf_general_key_google` varchar(100) DEFAULT NULL,
  `conf_horario_feriado` int(1) DEFAULT NULL,
  `conf_horario_laboral` int(1) DEFAULT NULL,
  `conf_atencion_desde1` time DEFAULT NULL,
  `conf_atencion_hasta1` time DEFAULT NULL,
  `conf_atencion_desde2` time DEFAULT NULL,
  `conf_atencion_hasta2` time DEFAULT NULL,
  `conf_atencion_dias` varchar(45) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `conf_general`
--

INSERT INTO `conf_general` (`conf_general_id`, `conf_general_key_google`, `conf_horario_feriado`, `conf_horario_laboral`, `conf_atencion_desde1`, `conf_atencion_hasta1`, `conf_atencion_desde2`, `conf_atencion_hasta2`, `conf_atencion_dias`, `accion_usuario`, `accion_fecha`) VALUES
('conf-001', 'AIzaSyBm9pnK5Oasw0903QQD3j8VSPA-Z4o18zs', 1, 1, '08:30:00', '12:30:00', '15:00:00', '18:30:00', '1,2,3,4,5,', '2019-03-29 14:11:32', '0000-00-00 00:00:00');

--
-- Disparadores `conf_general`
--
DELIMITER $$
CREATE TRIGGER `zconf_general_ADEL` AFTER DELETE ON `conf_general` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'conf_general', OLD.`conf_general_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'conf_general_id', OLD.`conf_general_id`, NULL ),
  (auditoria_last_inserted_id, 'conf_general_key_google', OLD.`conf_general_key_google`, NULL ),
  (auditoria_last_inserted_id, 'conf_horario_feriado', OLD.`conf_horario_feriado`, NULL ),
  (auditoria_last_inserted_id, 'conf_horario_laboral', OLD.`conf_horario_laboral`, NULL ),
  (auditoria_last_inserted_id, 'conf_atencion_desde1', OLD.`conf_atencion_desde1`, NULL ),
  (auditoria_last_inserted_id, 'conf_atencion_hasta1', OLD.`conf_atencion_hasta1`, NULL ),
  (auditoria_last_inserted_id, 'conf_atencion_desde2', OLD.`conf_atencion_desde2`, NULL ),
  (auditoria_last_inserted_id, 'conf_atencion_hasta2', OLD.`conf_atencion_hasta2`, NULL ),
  (auditoria_last_inserted_id, 'conf_atencion_dias', OLD.`conf_atencion_dias`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zconf_general_AINS` AFTER INSERT ON `conf_general` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'conf_general', NEW.`conf_general_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'conf_general_id', NULL, NEW.`conf_general_id`),
(auditoria_last_inserted_id, 'conf_general_key_google', NULL, NEW.`conf_general_key_google`),
(auditoria_last_inserted_id, 'conf_horario_feriado', NULL, NEW.`conf_horario_feriado`),
(auditoria_last_inserted_id, 'conf_horario_laboral', NULL, NEW.`conf_horario_laboral`),
(auditoria_last_inserted_id, 'conf_atencion_desde1', NULL, NEW.`conf_atencion_desde1`),
(auditoria_last_inserted_id, 'conf_atencion_hasta1', NULL, NEW.`conf_atencion_hasta1`),
(auditoria_last_inserted_id, 'conf_atencion_desde2', NULL, NEW.`conf_atencion_desde2`),
(auditoria_last_inserted_id, 'conf_atencion_hasta2', NULL, NEW.`conf_atencion_hasta2`),
(auditoria_last_inserted_id, 'conf_atencion_dias', NULL, NEW.`conf_atencion_dias`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zconf_general_AUPD` AFTER UPDATE ON `conf_general` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'conf_general', OLD.`conf_general_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'conf_general_id', OLD.`conf_general_id`, NEW.`conf_general_id`),
  (auditoria_last_inserted_id, 'conf_general_key_google', OLD.`conf_general_key_google`, NEW.`conf_general_key_google`),
  (auditoria_last_inserted_id, 'conf_horario_feriado', OLD.`conf_horario_feriado`, NEW.`conf_horario_feriado`),
  (auditoria_last_inserted_id, 'conf_horario_laboral', OLD.`conf_horario_laboral`, NEW.`conf_horario_laboral`),
  (auditoria_last_inserted_id, 'conf_atencion_desde1', OLD.`conf_atencion_desde1`, NEW.`conf_atencion_desde1`),
  (auditoria_last_inserted_id, 'conf_atencion_hasta1', OLD.`conf_atencion_hasta1`, NEW.`conf_atencion_hasta1`),
  (auditoria_last_inserted_id, 'conf_atencion_desde2', OLD.`conf_atencion_desde2`, NEW.`conf_atencion_desde2`),
  (auditoria_last_inserted_id, 'conf_atencion_hasta2', OLD.`conf_atencion_hasta2`, NEW.`conf_atencion_hasta2`),
  (auditoria_last_inserted_id, 'conf_atencion_dias', OLD.`conf_atencion_dias`, NEW.`conf_atencion_dias`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejecutivo`
--

CREATE TABLE `ejecutivo` (
  `ejecutivo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ejecutivo_zona` varchar(45) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ejecutivo`
--

INSERT INTO `ejecutivo` (`ejecutivo_id`, `usuario_id`, `ejecutivo_zona`, `accion_usuario`, `accion_fecha`) VALUES
(3, 10, '-16.499627326341034, -68.13318092451988', 'usuario.gestion', '2018-02-15 23:23:38');

--
-- Disparadores `ejecutivo`
--
DELIMITER $$
CREATE TRIGGER `zejecutivo_ADEL` AFTER DELETE ON `ejecutivo` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'ejecutivo', OLD.`ejecutivo_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'ejecutivo_id', OLD.`ejecutivo_id`, NULL ),
  (auditoria_last_inserted_id, 'usuario_id', OLD.`usuario_id`, NULL ),
  (auditoria_last_inserted_id, 'ejecutivo_zona', OLD.`ejecutivo_zona`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zejecutivo_AINS` AFTER INSERT ON `ejecutivo` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'ejecutivo', NEW.`ejecutivo_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'ejecutivo_id', NULL, NEW.`ejecutivo_id`),
(auditoria_last_inserted_id, 'usuario_id', NULL, NEW.`usuario_id`),
(auditoria_last_inserted_id, 'ejecutivo_zona', NULL, NEW.`ejecutivo_zona`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zejecutivo_AUPD` AFTER UPDATE ON `ejecutivo` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'ejecutivo', OLD.`ejecutivo_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'ejecutivo_id', OLD.`ejecutivo_id`, NEW.`ejecutivo_id`),
  (auditoria_last_inserted_id, 'usuario_id', OLD.`usuario_id`, NEW.`usuario_id`),
  (auditoria_last_inserted_id, 'ejecutivo_zona', OLD.`ejecutivo_zona`, NEW.`ejecutivo_zona`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `empresa_id` int(11) NOT NULL,
  `ejecutivo_id` int(11) NOT NULL,
  `empresa_consolidada` int(1) DEFAULT '1' COMMENT 'Esta tabla registra a todas las empresas e incluso las que están en prospecto. \n\n0 = Sigue siendo evaluada (no se puede dar mantenimiento)\n1 = Esta afiliada (Se tiene que cambiar el valor cuando se consolide toda la información y se la inyecte a PayStudio)',
  `empresa_categoria` int(1) DEFAULT '1' COMMENT 'Se define la cateogría de la empresa\n\n1 = Comercio\n2 = Establecimiento',
  `empresa_depende` int(11) DEFAULT '0' COMMENT 'Se establece si depende de alguna empresa, en el caso de que sea establecimiento o "empresa_categoria = 2".\n\nEn el caso que el comercio este registrado en ATC este campo será "-1"',
  `empresa_nit` varchar(25) DEFAULT NULL COMMENT '(Comercio)',
  `empresa_adquiriente` int(4) DEFAULT NULL COMMENT '(Comercio)\n(ATC S.A) Constante',
  `empresa_tipo_sociedad` int(4) DEFAULT NULL COMMENT '(Comercio)',
  `empresa_nombre_referencia` varchar(60) DEFAULT NULL,
  `empresa_nombre_legal` varchar(60) DEFAULT NULL COMMENT '(Comercio)',
  `empresa_nombre_fantasia` varchar(60) DEFAULT NULL COMMENT '(Comercio)',
  `empresa_rubro` int(4) DEFAULT NULL COMMENT '(Comercio)',
  `empresa_perfil_comercial` int(4) DEFAULT NULL COMMENT '(Comercio)',
  `empresa_mcc` int(4) DEFAULT NULL COMMENT '(Comercio)',
  `empresa_nombre_establecimiento` varchar(60) DEFAULT NULL COMMENT '(Establecimiento)',
  `empresa_denominacion_corta` varchar(20) DEFAULT NULL COMMENT '(Establecimiento)',
  `empresa_ha_desde` time DEFAULT NULL COMMENT '(Establecimiento)\n\nHorario de atención desde',
  `empresa_ha_hasta` time DEFAULT NULL COMMENT '(Establecimiento)\n\nHorario de atención hasta',
  `empresa_dias_atencion` varchar(60) DEFAULT NULL COMMENT '(Establecimiento)',
  `empresa_medio_contacto` int(4) DEFAULT NULL COMMENT '(Establecimiento)',
  `empresa_email` varchar(60) DEFAULT NULL,
  `empresa_dato_contacto` varchar(45) DEFAULT NULL COMMENT '(Establecimiento)',
  `empresa_departamento` int(4) DEFAULT NULL COMMENT '(Establecimiento)',
  `empresa_municipio` int(4) DEFAULT NULL COMMENT 'Municipio/Ciudad',
  `empresa_zona` int(4) DEFAULT NULL,
  `empresa_tipo_calle` int(4) DEFAULT NULL,
  `empresa_calle` varchar(60) DEFAULT NULL,
  `empresa_numero` int(8) DEFAULT NULL,
  `empresa_direccion_literal` varchar(100) DEFAULT NULL COMMENT 'Dirección literal obtenida del punto geolocalizado',
  `empresa_direccion_geo` varchar(45) DEFAULT NULL COMMENT 'Dirección X, Y obtenida del punto geolocalizado',
  `empresa_info_adicional` varchar(60) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='La empresa puede ser Comercio (categoría Madre) o Establecimiento (Categoría Hija)' ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`empresa_id`, `ejecutivo_id`, `empresa_consolidada`, `empresa_categoria`, `empresa_depende`, `empresa_nit`, `empresa_adquiriente`, `empresa_tipo_sociedad`, `empresa_nombre_referencia`, `empresa_nombre_legal`, `empresa_nombre_fantasia`, `empresa_rubro`, `empresa_perfil_comercial`, `empresa_mcc`, `empresa_nombre_establecimiento`, `empresa_denominacion_corta`, `empresa_ha_desde`, `empresa_ha_hasta`, `empresa_dias_atencion`, `empresa_medio_contacto`, `empresa_email`, `empresa_dato_contacto`, `empresa_departamento`, `empresa_municipio`, `empresa_zona`, `empresa_tipo_calle`, `empresa_calle`, `empresa_numero`, `empresa_direccion_literal`, `empresa_direccion_geo`, `empresa_info_adicional`, `accion_usuario`, `accion_fecha`) VALUES
(-1, 3, 0, 1, 0, '0', 1, 0, 'Empresa', 'Empresa', '', 0, 0, 0, NULL, NULL, '00:00:00', '00:00:00', '', 0, '', '0', 1, 0, 0, 0, '', 0, '0', '-16.51160263564477, -68.12914247094727', '', 'usuario.gestion', '2018-05-03 10:51:55');

--
-- Disparadores `empresa`
--
DELIMITER $$
CREATE TRIGGER `zempresa_ADEL` AFTER DELETE ON `empresa` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'empresa', OLD.`empresa_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'empresa_id', OLD.`empresa_id`, NULL ),
  (auditoria_last_inserted_id, 'ejecutivo_id', OLD.`ejecutivo_id`, NULL ),
  (auditoria_last_inserted_id, 'empresa_consolidada', OLD.`empresa_consolidada`, NULL ),
  (auditoria_last_inserted_id, 'empresa_categoria', OLD.`empresa_categoria`, NULL ),
  (auditoria_last_inserted_id, 'empresa_depende', OLD.`empresa_depende`, NULL ),
  (auditoria_last_inserted_id, 'empresa_nit', OLD.`empresa_nit`, NULL ),
  (auditoria_last_inserted_id, 'empresa_adquiriente', OLD.`empresa_adquiriente`, NULL ),
  (auditoria_last_inserted_id, 'empresa_tipo_sociedad', OLD.`empresa_tipo_sociedad`, NULL ),
  (auditoria_last_inserted_id, 'empresa_nombre_referencia', OLD.`empresa_nombre_referencia`, NULL ),
  (auditoria_last_inserted_id, 'empresa_nombre_legal', OLD.`empresa_nombre_legal`, NULL ),
  (auditoria_last_inserted_id, 'empresa_nombre_fantasia', OLD.`empresa_nombre_fantasia`, NULL ),
  (auditoria_last_inserted_id, 'empresa_rubro', OLD.`empresa_rubro`, NULL ),
  (auditoria_last_inserted_id, 'empresa_perfil_comercial', OLD.`empresa_perfil_comercial`, NULL ),
  (auditoria_last_inserted_id, 'empresa_mcc', OLD.`empresa_mcc`, NULL ),
  (auditoria_last_inserted_id, 'empresa_nombre_establecimiento', OLD.`empresa_nombre_establecimiento`, NULL ),
  (auditoria_last_inserted_id, 'empresa_denominacion_corta', OLD.`empresa_denominacion_corta`, NULL ),
  (auditoria_last_inserted_id, 'empresa_ha_desde', OLD.`empresa_ha_desde`, NULL ),
  (auditoria_last_inserted_id, 'empresa_ha_hasta', OLD.`empresa_ha_hasta`, NULL ),
  (auditoria_last_inserted_id, 'empresa_dias_atencion', OLD.`empresa_dias_atencion`, NULL ),
  (auditoria_last_inserted_id, 'empresa_medio_contacto', OLD.`empresa_medio_contacto`, NULL ),
  (auditoria_last_inserted_id, 'empresa_email', OLD.`empresa_email`, NULL ),
  (auditoria_last_inserted_id, 'empresa_dato_contacto', OLD.`empresa_dato_contacto`, NULL ),
  (auditoria_last_inserted_id, 'empresa_departamento', OLD.`empresa_departamento`, NULL ),
  (auditoria_last_inserted_id, 'empresa_municipio', OLD.`empresa_municipio`, NULL ),
  (auditoria_last_inserted_id, 'empresa_zona', OLD.`empresa_zona`, NULL ),
  (auditoria_last_inserted_id, 'empresa_tipo_calle', OLD.`empresa_tipo_calle`, NULL ),
  (auditoria_last_inserted_id, 'empresa_calle', OLD.`empresa_calle`, NULL ),
  (auditoria_last_inserted_id, 'empresa_numero', OLD.`empresa_numero`, NULL ),
  (auditoria_last_inserted_id, 'empresa_direccion_literal', OLD.`empresa_direccion_literal`, NULL ),
  (auditoria_last_inserted_id, 'empresa_direccion_geo', OLD.`empresa_direccion_geo`, NULL ),
  (auditoria_last_inserted_id, 'empresa_info_adicional', OLD.`empresa_info_adicional`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zempresa_AINS` AFTER INSERT ON `empresa` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'empresa', NEW.`empresa_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'empresa_id', NULL, NEW.`empresa_id`),
(auditoria_last_inserted_id, 'ejecutivo_id', NULL, NEW.`ejecutivo_id`),
(auditoria_last_inserted_id, 'empresa_consolidada', NULL, NEW.`empresa_consolidada`),
(auditoria_last_inserted_id, 'empresa_categoria', NULL, NEW.`empresa_categoria`),
(auditoria_last_inserted_id, 'empresa_depende', NULL, NEW.`empresa_depende`),
(auditoria_last_inserted_id, 'empresa_nit', NULL, NEW.`empresa_nit`),
(auditoria_last_inserted_id, 'empresa_adquiriente', NULL, NEW.`empresa_adquiriente`),
(auditoria_last_inserted_id, 'empresa_tipo_sociedad', NULL, NEW.`empresa_tipo_sociedad`),
(auditoria_last_inserted_id, 'empresa_nombre_referencia', NULL, NEW.`empresa_nombre_referencia`),
(auditoria_last_inserted_id, 'empresa_nombre_legal', NULL, NEW.`empresa_nombre_legal`),
(auditoria_last_inserted_id, 'empresa_nombre_fantasia', NULL, NEW.`empresa_nombre_fantasia`),
(auditoria_last_inserted_id, 'empresa_rubro', NULL, NEW.`empresa_rubro`),
(auditoria_last_inserted_id, 'empresa_perfil_comercial', NULL, NEW.`empresa_perfil_comercial`),
(auditoria_last_inserted_id, 'empresa_mcc', NULL, NEW.`empresa_mcc`),
(auditoria_last_inserted_id, 'empresa_nombre_establecimiento', NULL, NEW.`empresa_nombre_establecimiento`),
(auditoria_last_inserted_id, 'empresa_denominacion_corta', NULL, NEW.`empresa_denominacion_corta`),
(auditoria_last_inserted_id, 'empresa_ha_desde', NULL, NEW.`empresa_ha_desde`),
(auditoria_last_inserted_id, 'empresa_ha_hasta', NULL, NEW.`empresa_ha_hasta`),
(auditoria_last_inserted_id, 'empresa_dias_atencion', NULL, NEW.`empresa_dias_atencion`),
(auditoria_last_inserted_id, 'empresa_medio_contacto', NULL, NEW.`empresa_medio_contacto`),
(auditoria_last_inserted_id, 'empresa_email', NULL, NEW.`empresa_email`),
(auditoria_last_inserted_id, 'empresa_dato_contacto', NULL, NEW.`empresa_dato_contacto`),
(auditoria_last_inserted_id, 'empresa_departamento', NULL, NEW.`empresa_departamento`),
(auditoria_last_inserted_id, 'empresa_municipio', NULL, NEW.`empresa_municipio`),
(auditoria_last_inserted_id, 'empresa_zona', NULL, NEW.`empresa_zona`),
(auditoria_last_inserted_id, 'empresa_tipo_calle', NULL, NEW.`empresa_tipo_calle`),
(auditoria_last_inserted_id, 'empresa_calle', NULL, NEW.`empresa_calle`),
(auditoria_last_inserted_id, 'empresa_numero', NULL, NEW.`empresa_numero`),
(auditoria_last_inserted_id, 'empresa_direccion_literal', NULL, NEW.`empresa_direccion_literal`),
(auditoria_last_inserted_id, 'empresa_direccion_geo', NULL, NEW.`empresa_direccion_geo`),
(auditoria_last_inserted_id, 'empresa_info_adicional', NULL, NEW.`empresa_info_adicional`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zempresa_AUPD` AFTER UPDATE ON `empresa` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'empresa', OLD.`empresa_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'empresa_id', OLD.`empresa_id`, NEW.`empresa_id`),
  (auditoria_last_inserted_id, 'ejecutivo_id', OLD.`ejecutivo_id`, NEW.`ejecutivo_id`),
  (auditoria_last_inserted_id, 'empresa_consolidada', OLD.`empresa_consolidada`, NEW.`empresa_consolidada`),
  (auditoria_last_inserted_id, 'empresa_categoria', OLD.`empresa_categoria`, NEW.`empresa_categoria`),
  (auditoria_last_inserted_id, 'empresa_depende', OLD.`empresa_depende`, NEW.`empresa_depende`),
  (auditoria_last_inserted_id, 'empresa_nit', OLD.`empresa_nit`, NEW.`empresa_nit`),
  (auditoria_last_inserted_id, 'empresa_adquiriente', OLD.`empresa_adquiriente`, NEW.`empresa_adquiriente`),
  (auditoria_last_inserted_id, 'empresa_tipo_sociedad', OLD.`empresa_tipo_sociedad`, NEW.`empresa_tipo_sociedad`),
  (auditoria_last_inserted_id, 'empresa_nombre_referencia', OLD.`empresa_nombre_referencia`, NEW.`empresa_nombre_referencia`),
  (auditoria_last_inserted_id, 'empresa_nombre_legal', OLD.`empresa_nombre_legal`, NEW.`empresa_nombre_legal`),
  (auditoria_last_inserted_id, 'empresa_nombre_fantasia', OLD.`empresa_nombre_fantasia`, NEW.`empresa_nombre_fantasia`),
  (auditoria_last_inserted_id, 'empresa_rubro', OLD.`empresa_rubro`, NEW.`empresa_rubro`),
  (auditoria_last_inserted_id, 'empresa_perfil_comercial', OLD.`empresa_perfil_comercial`, NEW.`empresa_perfil_comercial`),
  (auditoria_last_inserted_id, 'empresa_mcc', OLD.`empresa_mcc`, NEW.`empresa_mcc`),
  (auditoria_last_inserted_id, 'empresa_nombre_establecimiento', OLD.`empresa_nombre_establecimiento`, NEW.`empresa_nombre_establecimiento`),
  (auditoria_last_inserted_id, 'empresa_denominacion_corta', OLD.`empresa_denominacion_corta`, NEW.`empresa_denominacion_corta`),
  (auditoria_last_inserted_id, 'empresa_ha_desde', OLD.`empresa_ha_desde`, NEW.`empresa_ha_desde`),
  (auditoria_last_inserted_id, 'empresa_ha_hasta', OLD.`empresa_ha_hasta`, NEW.`empresa_ha_hasta`),
  (auditoria_last_inserted_id, 'empresa_dias_atencion', OLD.`empresa_dias_atencion`, NEW.`empresa_dias_atencion`),
  (auditoria_last_inserted_id, 'empresa_medio_contacto', OLD.`empresa_medio_contacto`, NEW.`empresa_medio_contacto`),
  (auditoria_last_inserted_id, 'empresa_email', OLD.`empresa_email`, NEW.`empresa_email`),
  (auditoria_last_inserted_id, 'empresa_dato_contacto', OLD.`empresa_dato_contacto`, NEW.`empresa_dato_contacto`),
  (auditoria_last_inserted_id, 'empresa_departamento', OLD.`empresa_departamento`, NEW.`empresa_departamento`),
  (auditoria_last_inserted_id, 'empresa_municipio', OLD.`empresa_municipio`, NEW.`empresa_municipio`),
  (auditoria_last_inserted_id, 'empresa_zona', OLD.`empresa_zona`, NEW.`empresa_zona`),
  (auditoria_last_inserted_id, 'empresa_tipo_calle', OLD.`empresa_tipo_calle`, NEW.`empresa_tipo_calle`),
  (auditoria_last_inserted_id, 'empresa_calle', OLD.`empresa_calle`, NEW.`empresa_calle`),
  (auditoria_last_inserted_id, 'empresa_numero', OLD.`empresa_numero`, NEW.`empresa_numero`),
  (auditoria_last_inserted_id, 'empresa_direccion_literal', OLD.`empresa_direccion_literal`, NEW.`empresa_direccion_literal`),
  (auditoria_last_inserted_id, 'empresa_direccion_geo', OLD.`empresa_direccion_geo`, NEW.`empresa_direccion_geo`),
  (auditoria_last_inserted_id, 'empresa_info_adicional', OLD.`empresa_info_adicional`, NEW.`empresa_info_adicional`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estructura_agencia`
--

CREATE TABLE `estructura_agencia` (
  `estructura_agencia_id` int(11) NOT NULL,
  `estructura_regional_id` int(11) NOT NULL,
  `estructura_agencia_nombre` varchar(50) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estructura_agencia`
--

INSERT INTO `estructura_agencia` (`estructura_agencia_id`, `estructura_regional_id`, `estructura_agencia_nombre`, `accion_usuario`, `accion_fecha`) VALUES
(1, 1, 'Centro', 'usuario.gestion', '2018-12-24 11:57:30'),
(2, 1, 'Occidente', 'usuario.gestion', '2018-12-24 11:57:43'),
(3, 1, 'Oficina Nacional Administrativa', 'usuario.gestion', '2018-12-24 11:57:53'),
(4, 1, 'Oriente', 'usuario.gestion', '2018-12-24 11:57:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estructura_entidad`
--

CREATE TABLE `estructura_entidad` (
  `estructura_entidad_id` int(11) NOT NULL,
  `estructura_entidad_nombre` varchar(50) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estructura_entidad`
--

INSERT INTO `estructura_entidad` (`estructura_entidad_id`, `estructura_entidad_nombre`, `accion_usuario`, `accion_fecha`) VALUES
(1, 'Entidad', 'usuario.gestion', '2017-09-15 12:30:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estructura_regional`
--

CREATE TABLE `estructura_regional` (
  `estructura_regional_id` int(11) NOT NULL,
  `estructura_entidad_id` int(11) NOT NULL,
  `estructura_regional_nombre` varchar(50) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estructura_regional`
--

INSERT INTO `estructura_regional` (`estructura_regional_id`, `estructura_entidad_id`, `estructura_regional_nombre`, `accion_usuario`, `accion_fecha`) VALUES
(1, 1, 'La Paz', 'usuario.gestion', '2017-11-21 16:22:03'),
(2, 1, 'Santa Cruz', 'usuario.gestion', '2017-09-15 13:49:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etapa`
--

CREATE TABLE `etapa` (
  `etapa_id` int(11) NOT NULL,
  `etapa_depende` int(11) NOT NULL DEFAULT '0' COMMENT 'Identificador que indica de que etapa depende.\n\nSi es "0" no tiene dependencia',
  `etapa_nombre` varchar(40) DEFAULT NULL,
  `etapa_detalle` varchar(300) DEFAULT NULL,
  `etapa_tiempo` int(11) DEFAULT NULL COMMENT 'Tiempo en horas',
  `etapa_notificar_correo` int(1) DEFAULT '1' COMMENT '0 = No se envía correo de notificación\n1 = Si se envía correo de notificación',
  `etapa_rol` int(11) DEFAULT NULL COMMENT 'ID del rol asociado a la etapa',
  `etapa_categoria` int(11) DEFAULT '0' COMMENT '0 = Pre-Afiliación Empresa Aceptante\n1 = Afiliación Empresa Aceptante\n2 = Excepción',
  `etapa_orden` int(2) DEFAULT '0',
  `etapa_color` varchar(50) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `etapa`
--

INSERT INTO `etapa` (`etapa_id`, `etapa_depende`, `etapa_nombre`, `etapa_detalle`, `etapa_tiempo`, `etapa_notificar_correo`, `etapa_rol`, `etapa_categoria`, `etapa_orden`, `etapa_color`, `accion_usuario`, `accion_fecha`) VALUES
(0, 0, 'Back Office', 'Cargado Masivo de Leads', 0, 0, 1, 10, 0, '#003366', 'usuario.gestion', '2017-10-09 13:24:44'),
(1, 0, 'Lead Asignado', 'Estado del Lead sólo como asignado', 3, 0, 2, 1, 1, '#003366', 'usuario.gestion', '2019-07-11 11:48:28'),
(2, 1, 'Interes', 'Etapa del Flujo', 2, 0, 2, 1, 2, '#003366', 'usuario.gestion', '2019-07-11 11:49:25'),
(3, 2, 'Cierre', 'Etapa del Flujo', 2, 0, 2, 1, 4, '#003366', 'usuario.gestion', '2018-10-11 15:43:41'),
(4, 3, 'Entrega de papeles', 'Etapa del Flujo', 2, 0, 2, 1, 5, '#003366', 'usuario.gestion', '2018-10-11 15:43:41'),
(5, 4, 'Ingreso de carpeta', 'Etapa del Flujo', 2, 0, 2, 1, 6, '#003366', 'usuario.gestion', '2018-10-11 15:43:41'),
(6, 5, 'Aprobación', 'Etapa del Flujo', 2, 0, 2, 1, 7, '#003366', 'usuario.gestion', '2018-10-11 15:43:41'),
(7, 6, 'Rechazo', 'Etapa del Flujo', 2, 0, 2, 1, 8, '#003366', 'usuario.gestion', '2018-10-11 15:43:41'),
(8, 7, 'Desembolso', 'Etapa del Flujo', 2, 0, 2, 1, 9, '#003366', 'usuario.gestion', '2018-10-11 15:43:41'),
(9, 7, 'Terminado', 'Lead Terminado en el flujo', 2, 0, 2, 10, 10, '#003366', 'usuario.gestion', '2018-10-11 15:43:41'),
(10, 2, 'No Interés/Cancelado', 'Lead marcado como No Interés', 2, 0, 2, 1, 3, '#003366', 'usuario.gestion', '2019-03-29 14:13:25');

--
-- Disparadores `etapa`
--
DELIMITER $$
CREATE TRIGGER `zetapa_ADEL` AFTER DELETE ON `etapa` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO `cetus_proyecto_initium`.auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'etapa', OLD.`etapa_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO `cetus_proyecto_initium`.auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'etapa_id', OLD.`etapa_id`, NULL ),
  (auditoria_last_inserted_id, 'etapa_depende', OLD.`etapa_depende`, NULL ),
  (auditoria_last_inserted_id, 'etapa_nombre', OLD.`etapa_nombre`, NULL ),
  (auditoria_last_inserted_id, 'etapa_detalle', OLD.`etapa_detalle`, NULL ),
  (auditoria_last_inserted_id, 'etapa_tiempo', OLD.`etapa_tiempo`, NULL ),
  (auditoria_last_inserted_id, 'etapa_notificar_correo', OLD.`etapa_notificar_correo`, NULL ),
  (auditoria_last_inserted_id, 'etapa_rol', OLD.`etapa_rol`, NULL ),
  (auditoria_last_inserted_id, 'etapa_categoria', OLD.`etapa_categoria`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zetapa_AINS` AFTER INSERT ON `etapa` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO `cetus_proyecto_initium`.auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'etapa', NEW.`etapa_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO `cetus_proyecto_initium`.auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'etapa_id', NULL, NEW.`etapa_id`),
(auditoria_last_inserted_id, 'etapa_depende', NULL, NEW.`etapa_depende`),
(auditoria_last_inserted_id, 'etapa_nombre', NULL, NEW.`etapa_nombre`),
(auditoria_last_inserted_id, 'etapa_detalle', NULL, NEW.`etapa_detalle`),
(auditoria_last_inserted_id, 'etapa_tiempo', NULL, NEW.`etapa_tiempo`),
(auditoria_last_inserted_id, 'etapa_notificar_correo', NULL, NEW.`etapa_notificar_correo`),
(auditoria_last_inserted_id, 'etapa_rol', NULL, NEW.`etapa_rol`),
(auditoria_last_inserted_id, 'etapa_categoria', NULL, NEW.`etapa_categoria`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zetapa_AUPD` AFTER UPDATE ON `etapa` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO `cetus_proyecto_initium`.auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'etapa', OLD.`etapa_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO `cetus_proyecto_initium`.auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'etapa_id', OLD.`etapa_id`, NEW.`etapa_id`),
  (auditoria_last_inserted_id, 'etapa_depende', OLD.`etapa_depende`, NEW.`etapa_depende`),
  (auditoria_last_inserted_id, 'etapa_nombre', OLD.`etapa_nombre`, NEW.`etapa_nombre`),
  (auditoria_last_inserted_id, 'etapa_detalle', OLD.`etapa_detalle`, NEW.`etapa_detalle`),
  (auditoria_last_inserted_id, 'etapa_tiempo', OLD.`etapa_tiempo`, NEW.`etapa_tiempo`),
  (auditoria_last_inserted_id, 'etapa_notificar_correo', OLD.`etapa_notificar_correo`, NEW.`etapa_notificar_correo`),
  (auditoria_last_inserted_id, 'etapa_rol', OLD.`etapa_rol`, NEW.`etapa_rol`),
  (auditoria_last_inserted_id, 'etapa_categoria', OLD.`etapa_categoria`, NEW.`etapa_categoria`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hito`
--

CREATE TABLE `hito` (
  `hito_id` int(11) NOT NULL,
  `prospecto_id` int(11) NOT NULL,
  `etapa_id` int(11) NOT NULL,
  `hito_fecha_ini` datetime DEFAULT NULL,
  `hito_fecha_fin` datetime DEFAULT NULL,
  `hito_finalizo` int(1) DEFAULT '0' COMMENT 'Por defecto el hito sólo marca la fecha de inicio, y al terminar la etapa recién se actualiza la fecha de finalización.\n\n0 = No finalizado\n1 = Si finalizado',
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `hito`
--

INSERT INTO `hito` (`hito_id`, `prospecto_id`, `etapa_id`, `hito_fecha_ini`, `hito_fecha_fin`, `hito_finalizo`, `accion_usuario`, `accion_fecha`) VALUES
(390, 121, 1, '2018-10-08 16:56:03', NULL, 0, 'usuario.gestion', '2018-10-08 16:56:03'),
(397, 125, 1, '2018-10-11 17:06:28', '2018-10-11 17:32:34', 1, 'des.gert22', '2018-10-11 17:32:34'),
(398, 126, 1, '2018-10-11 17:06:28', NULL, 0, 'usuario.gestion', '2018-10-11 17:06:28'),
(399, 127, 1, '2018-10-11 17:06:28', NULL, 0, 'usuario.gestion', '2018-10-11 17:06:28'),
(400, 125, 2, '2018-10-11 17:21:16', '2018-10-12 10:54:56', 1, 'usuario.gestion', '2018-10-12 10:54:56'),
(405, 146, 1, '2018-11-30 15:01:13', NULL, 0, 'usuario.gestion', '2018-11-30 15:01:13'),
(406, 147, 1, '2018-11-30 15:01:13', NULL, 0, 'usuario.gestion', '2018-11-30 15:01:13'),
(407, 148, 1, '2018-11-30 15:04:43', NULL, 0, 'usuario.gestion', '2018-11-30 15:04:43'),
(408, 149, 1, '2018-11-30 15:04:43', '2018-12-18 12:29:11', 1, 'usuario.app', '2018-12-18 12:29:11'),
(409, 150, 1, '2018-11-30 15:06:14', NULL, 0, 'usuario.gestion', '2018-11-30 15:06:14'),
(410, 151, 1, '2018-11-30 15:06:14', NULL, 0, 'usuario.gestion', '2018-11-30 15:06:14'),
(411, 152, 1, '2018-11-30 15:08:00', NULL, 0, 'usuario.gestion', '2018-11-30 15:08:00'),
(412, 153, 1, '2018-11-30 15:08:00', NULL, 0, 'usuario.gestion', '2018-11-30 15:08:00'),
(413, 154, 1, '2018-11-30 15:09:06', NULL, 0, 'usuario.gestion', '2018-11-30 15:09:06'),
(414, 155, 1, '2018-11-30 15:09:06', NULL, 0, 'usuario.gestion', '2018-11-30 15:09:06'),
(415, 156, 1, '2018-11-30 15:09:58', NULL, 0, 'usuario.gestion', '2018-11-30 15:09:58'),
(416, 157, 1, '2018-11-30 15:09:58', NULL, 0, 'usuario.gestion', '2018-11-30 15:09:58'),
(417, 158, 1, '2018-11-30 15:11:38', '2019-01-03 19:00:41', 1, 'usuario.app', '2019-01-03 19:00:41'),
(418, 159, 1, '2018-11-30 15:11:38', NULL, 0, 'usuario.gestion', '2018-11-30 15:11:38'),
(419, 160, 1, '2018-11-30 15:12:49', '2018-12-18 16:39:04', 1, 'usuario.app', '2018-12-18 16:39:04'),
(420, 161, 1, '2018-11-30 15:12:49', '2018-12-14 21:05:10', 1, 'usuario.app', '2018-12-14 21:05:10'),
(421, 161, 2, '2018-12-14 21:05:10', '2018-12-14 21:07:29', 1, 'usuario.app', '2018-12-14 21:07:29'),
(422, 161, 3, '2018-12-14 21:07:29', '2018-12-14 21:07:58', 1, 'usuario.app', '2018-12-14 21:07:58'),
(423, 165, 1, '2018-12-14 22:45:23', '2018-12-17 09:48:29', 1, 'usuario.app', '2018-12-17 09:48:29'),
(424, 162, 1, '2018-12-14 22:45:23', NULL, 0, 'usuario.app', '2018-12-14 22:45:23'),
(425, 165, 2, '2018-12-17 09:48:29', NULL, 0, 'usuario.app', '2018-12-17 09:48:29'),
(426, 166, 1, '2018-12-17 10:09:28', '2018-12-18 17:44:01', 1, 'usuario.app', '2018-12-18 17:44:01'),
(427, 160, 2, '2018-12-18 16:39:04', '2018-12-27 10:22:37', 1, 'usuario.app', '2018-12-27 10:22:37'),
(428, 167, 1, '2018-12-18 16:52:25', NULL, 0, 'usuario.app', '2018-12-18 16:52:25'),
(429, 168, 1, '2018-12-18 16:55:05', '2018-12-19 09:56:08', 1, 'usuario.app', '2018-12-19 09:56:08'),
(430, 169, 1, '2018-12-18 16:56:46', '2018-12-27 15:54:51', 1, 'usuario.app', '2018-12-27 15:54:51'),
(431, 170, 1, '2018-12-18 17:10:45', '2018-12-18 17:12:26', 1, 'usuario.app', '2018-12-18 17:12:26'),
(432, 170, 2, '2018-12-18 17:11:56', '2018-12-18 17:12:11', 1, 'usuario.app', '2018-12-18 17:12:11'),
(433, 170, 7, '2018-12-18 17:12:26', '2018-12-18 17:12:36', 1, 'usuario.app', '2018-12-18 17:12:36'),
(435, 160, 3, '2018-12-27 10:22:37', '2018-12-27 10:23:28', 1, 'usuario.app', '2018-12-27 10:23:28'),
(436, 160, 8, '2018-12-27 10:23:28', NULL, 0, 'usuario.app', '2018-12-27 10:23:28'),
(437, 171, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(438, 172, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(439, 173, 1, '2018-12-27 11:59:31', '2018-12-30 17:23:14', 1, 'S40259', '2018-12-30 17:23:14'),
(440, 174, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(441, 175, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(442, 176, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(443, 177, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(444, 178, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(445, 179, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(446, 180, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(447, 181, 1, '2018-12-27 11:59:31', '2018-12-27 17:17:58', 1, 'S40259', '2018-12-27 17:17:58'),
(448, 182, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(449, 183, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(450, 184, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(451, 185, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(452, 186, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(453, 187, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(454, 188, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(455, 189, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(456, 190, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(457, 191, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(458, 192, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(459, 193, 1, '2018-12-27 11:59:31', '2018-12-27 17:08:56', 1, 'S40259', '2018-12-27 17:08:56'),
(460, 194, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(461, 195, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(462, 196, 1, '2018-12-27 11:59:31', '2018-12-27 12:33:13', 1, 'B02978', '2018-12-27 12:33:13'),
(463, 197, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(464, 198, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(465, 199, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(466, 200, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(467, 201, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(468, 202, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(469, 203, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(470, 204, 1, '2018-12-27 11:59:31', '2018-12-27 16:16:29', 1, 'B02978', '2018-12-27 16:16:29'),
(471, 205, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(472, 206, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(473, 207, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(474, 208, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(475, 209, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(476, 210, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(477, 211, 1, '2018-12-27 11:59:31', NULL, 0, 'usuario.gestion', '2018-12-27 11:59:31'),
(478, 196, 2, '2018-12-27 12:33:13', '2018-12-27 12:38:00', 1, 'B02978', '2018-12-27 12:38:00'),
(479, 196, 8, '2018-12-27 12:38:00', NULL, 0, 'B02978', '2018-12-27 12:38:00'),
(480, 169, 8, '2018-12-27 15:54:51', '2019-02-07 13:07:11', 1, 'usuario.app', '2019-02-07 13:07:11'),
(481, 204, 2, '2018-12-27 16:16:29', NULL, 0, 'B02978', '2018-12-27 16:16:29'),
(482, 193, 8, '2018-12-27 17:08:56', '2018-12-27 17:09:06', 1, 'S40259', '2018-12-27 17:09:06'),
(483, 181, 8, '2018-12-27 17:17:58', '2018-12-27 17:18:12', 1, 'S40259', '2018-12-27 17:18:12'),
(484, 173, 8, '2018-12-30 17:23:14', NULL, 0, 'S40259', '2018-12-30 17:23:14'),
(485, 212, 1, '2018-12-27 17:36:06', NULL, 0, 'S40259', '2018-12-27 17:36:06'),
(486, 213, 1, '2018-12-27 17:37:08', NULL, 0, 'B02978', '2018-12-27 17:37:08'),
(487, 214, 1, '2018-12-27 17:37:13', NULL, 0, 'B02978', '2018-12-27 17:37:13'),
(488, 158, 10, '2019-01-03 19:00:41', '2019-02-07 13:06:44', 1, 'usuario.app', '2019-02-07 13:06:44'),
(489, 158, 3, '2019-02-07 13:06:44', NULL, 0, 'usuario.app', '2019-02-07 13:06:44'),
(490, 169, 4, '2019-02-07 13:07:11', '2019-02-07 13:07:55', 1, 'usuario.app', '2019-02-07 13:07:55'),
(491, 169, 5, '2019-02-07 13:07:55', '2019-02-07 13:08:18', 1, 'usuario.app', '2019-02-07 13:08:18'),
(492, 169, 2, '2019-02-07 13:08:18', '2019-02-07 13:08:54', 1, 'usuario.app', '2019-02-07 13:08:54'),
(493, 169, 6, '2019-02-07 13:08:54', NULL, 0, 'usuario.app', '2019-02-07 13:08:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `menu_nombre` varchar(40) DEFAULT NULL,
  `menu_descripcion` varchar(100) DEFAULT NULL,
  `menu_enlace` varchar(100) DEFAULT NULL,
  `menu_orden` int(11) DEFAULT '0' COMMENT '0 = Configuración\n1 = Parámetros\n2 = Negocio\n3 = Reportes',
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_nombre`, `menu_descripcion`, `menu_enlace`, `menu_orden`, `accion_usuario`, `accion_fecha`) VALUES
(1, 'Gestión de Usuarios y Estructura', 'Podrá gestionar los Usuarios del sistema y la Estructura de la entidad', 'Usuario/Listar', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(2, 'Configurar Credenciales y Roles', 'Administrar los parámetros de las Credenciales (fortaleza, etc), gestión de Roles y Permisos', 'Conf/Credenciales/Menu', 0, 'usuario.gestion', '2017-07-21 00:00:00'),
(39, 'Formularios Dinámicos', 'Módulo de gestión de los Formularios Dinámicos', 'Formularios/Ver', 2, 'usuario.gestion', '2018-11-08 09:26:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `perfil_id` int(11) NOT NULL,
  `perfil_nombre` varchar(40) DEFAULT NULL,
  `perfil_descripcion` varchar(100) DEFAULT NULL,
  `perfil_estado` int(1) DEFAULT '1' COMMENT '0 = No vigente\n1 = Vigente',
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`perfil_id`, `perfil_nombre`, `perfil_descripcion`, `perfil_estado`, `accion_usuario`, `accion_fecha`) VALUES
(1, 'Aprobar Siguiente Instancia', 'Aprobar Siguiente Instancia dentro del flujo de trabajo', 0, 'usuario.gestion', '2017-09-17 19:46:03'),
(2, 'Devolver Anterior Instancia', 'Devolver el proceso a la instancia anterior del flujo', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(3, 'Historial Observaciones', 'Visualizar el Historial Observaciones', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(4, 'Ver Documentos', 'Permite visualizar los documentos del sistema así como los digitalizados', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(5, 'Observar Documentos', 'Permite Observar los Documentos del Prospecto', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(7, 'Consultas (Todo)', 'Permite realizar Consultas a nivel nacional, sin este permiso sólo podrá consultar a nivel regional', 1, 'usuario.gestion', '2017-11-21 15:22:03'),
(8, 'Detalle Usuario', 'Ver el Detalle de la información del Usuario', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(9, 'Detalle Campaña', 'Ver el Detalle de Campaña', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(10, 'Detalle Prospecto', 'Ver el Detalle del Prospecto', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(11, 'Ver Horario Ejecutivo Cuentas', 'Permite visualizar el calendario de las visitas del ejecutivo de cuenta', 1, 'usuario.gestion', '2017-08-23 08:28:34'),
(12, 'Detalle Mantenimiento', 'Ver el Detalle del Mantenimiento de Cartera', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(13, 'Detalle Avance Agente', 'Visualizar el Detalle Avance Agente', 1, 'usuario.gestion', '2017-07-21 00:00:00'),
(14, 'Ver Seguimiento del Prospecto', 'Visualizar el Seguimiento del Prospecto', 1, 'usuario.gestion', '2017-07-21 00:00:00');

--
-- Disparadores `perfil`
--
DELIMITER $$
CREATE TRIGGER `zperfil_ADEL` AFTER DELETE ON `perfil` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'perfil', OLD.`perfil_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'perfil_id', OLD.`perfil_id`, NULL ),
  (auditoria_last_inserted_id, 'perfil_nombre', OLD.`perfil_nombre`, NULL ),
  (auditoria_last_inserted_id, 'perfil_descripcion', OLD.`perfil_descripcion`, NULL ),
  (auditoria_last_inserted_id, 'perfil_estado', OLD.`perfil_estado`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zperfil_AINS` AFTER INSERT ON `perfil` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'perfil', NEW.`perfil_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'perfil_id', NULL, NEW.`perfil_id`),
(auditoria_last_inserted_id, 'perfil_nombre', NULL, NEW.`perfil_nombre`),
(auditoria_last_inserted_id, 'perfil_descripcion', NULL, NEW.`perfil_descripcion`),
(auditoria_last_inserted_id, 'perfil_estado', NULL, NEW.`perfil_estado`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zperfil_AUPD` AFTER UPDATE ON `perfil` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'perfil', OLD.`perfil_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'perfil_id', OLD.`perfil_id`, NEW.`perfil_id`),
  (auditoria_last_inserted_id, 'perfil_nombre', OLD.`perfil_nombre`, NEW.`perfil_nombre`),
  (auditoria_last_inserted_id, 'perfil_descripcion', OLD.`perfil_descripcion`, NEW.`perfil_descripcion`),
  (auditoria_last_inserted_id, 'perfil_estado', OLD.`perfil_estado`, NEW.`perfil_estado`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prospecto`
--

CREATE TABLE `prospecto` (
  `prospecto_id` int(11) NOT NULL,
  `ejecutivo_id` int(11) NOT NULL,
  `tipo_persona_id` int(11) NOT NULL COMMENT 'El tipo de persona definirá que documentos requiere digitalizarse',
  `empresa_id` int(11) NOT NULL,
  `camp_id` int(11) NOT NULL,
  `prospecto_fecha_asignacion` datetime DEFAULT NULL COMMENT 'Fecha de la asignación al Ejecutivo de Cuentas',
  `prospecto_carpeta` varchar(20) DEFAULT 'afn' COMMENT 'Nombre de la carpeta que se creará con el prospecto.\n\nSera: "afn_1" -> AFN_(Id correlativo del prospecto)',
  `prospecto_etapa` int(1) DEFAULT '0' COMMENT 'Referenica de la etapa actual del prospectode la tabla "etapa"',
  `prospecto_etapa_fecha` datetime DEFAULT NULL COMMENT 'Fecha en que le fue asignada la etapa, que ayudará para calcular los tiempos de la etapa como tal',
  `prospecto_checkin` int(1) DEFAULT '0' COMMENT '0 = Aún no realizó el CheckIn\n1 = Ya realizó el CheckIn',
  `prospecto_checkin_fecha` varchar(45) DEFAULT NULL,
  `prospecto_checkin_geo` varchar(45) DEFAULT NULL,
  `prospecto_llamada` int(1) DEFAULT '0' COMMENT '0 = Aún no realizó la Llamada\n1 = Ya realizó la Llamada',
  `prospecto_llamada_fecha` datetime DEFAULT NULL,
  `prospecto_llamada_geo` varchar(45) DEFAULT NULL,
  `prospecto_consolidar_fecha` varchar(45) DEFAULT NULL,
  `prospecto_consolidar_geo` varchar(45) DEFAULT NULL,
  `prospecto_consolidado` int(1) DEFAULT '0' COMMENT '¿Está consolidado?\n\n0 = No\n1 = Si',
  `prospecto_observado_app` int(1) DEFAULT '0' COMMENT 'Marca el registro si esta observado para el Ejecutivo de Cuentas\n\n0 = Sin observación\n1 = Observación Cumplimiento\n2 = Observación Legal',
  `prospecto_estado_actual` int(1) DEFAULT '0' COMMENT '0 = Creado\n1 = En Pre-Revisión Cumplimiento (APP)\n2 = Completado Pre-Revisión Cumplimiento (APP)\n3 = Aprobado (entra al flujo)\n4 = Afiliado',
  `prospecto_observado` int(1) DEFAULT NULL,
  `prospecto_idc` varchar(15) DEFAULT NULL,
  `prospecto_nombre_cliente` varchar(150) DEFAULT NULL,
  `prospecto_empresa` varchar(150) DEFAULT NULL,
  `prospecto_ingreso` int(11) DEFAULT NULL,
  `prospecto_direccion` varchar(255) DEFAULT NULL,
  `prospecto_direccion_geo` varchar(45) DEFAULT NULL,
  `prospecto_telefono` varchar(10) DEFAULT NULL,
  `prospecto_celular` varchar(10) DEFAULT NULL,
  `prospecto_email` varchar(150) DEFAULT NULL,
  `prospecto_tipo_lead` int(1) DEFAULT NULL COMMENT 'Tipo de Lead\n\n1 = Asignado por Marketing (cargado masivo)\n2 = Registrado por el agente',
  `prospecto_matricula` varchar(20) DEFAULT NULL,
  `prospecto_fecha_contacto1` date DEFAULT NULL,
  `prospecto_monto_aprobacion` int(11) DEFAULT '0',
  `prospecto_monto_desembolso` int(11) DEFAULT '0',
  `prospecto_fecha_desembolso` date DEFAULT NULL,
  `prospecto_comentario` varchar(2000) DEFAULT 'Sin comentario',
  `prospecto_codigo_cliente` varchar(45) DEFAULT NULL,
  `prospecto_mensaje` varchar(100) DEFAULT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `prospecto`
--

INSERT INTO `prospecto` (`prospecto_id`, `ejecutivo_id`, `tipo_persona_id`, `empresa_id`, `camp_id`, `prospecto_fecha_asignacion`, `prospecto_carpeta`, `prospecto_etapa`, `prospecto_etapa_fecha`, `prospecto_checkin`, `prospecto_checkin_fecha`, `prospecto_checkin_geo`, `prospecto_llamada`, `prospecto_llamada_fecha`, `prospecto_llamada_geo`, `prospecto_consolidar_fecha`, `prospecto_consolidar_geo`, `prospecto_consolidado`, `prospecto_observado_app`, `prospecto_estado_actual`, `prospecto_observado`, `prospecto_idc`, `prospecto_nombre_cliente`, `prospecto_empresa`, `prospecto_ingreso`, `prospecto_direccion`, `prospecto_direccion_geo`, `prospecto_telefono`, `prospecto_celular`, `prospecto_email`, `prospecto_tipo_lead`, `prospecto_matricula`, `prospecto_fecha_contacto1`, `prospecto_monto_aprobacion`, `prospecto_monto_desembolso`, `prospecto_fecha_desembolso`, `prospecto_comentario`, `prospecto_codigo_cliente`, `prospecto_mensaje`, `accion_usuario`, `accion_fecha`) VALUES
(119, 3, 1, -1, 1, '2018-05-03 10:51:55', 'afn', 2, '2018-05-03 11:15:41', 1, '2018-05-03 10:59:11', '-16.5098159, -68.1267671', 1, '2018-12-18 10:27:21', '16.25452154, 16.9548754', '2018-05-03 11:13:12', '-16.5098164, -68.1268186', 1, 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 10:27:21'),
(121, 3, 1, -1, 1, '2018-10-08 16:56:03', 'afn', 2, '2018-10-09 17:03:32', 1, '2018-10-09 17:03:28', '-16.509873, -68.1267706', 0, NULL, NULL, '2018-10-09 17:03:32', '-16.5098714, -68.1267697', 1, 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-10-09 17:03:32'),
(125, 10, 1, -1, 1, '2018-10-11 17:06:28', 'afn', 2, '2018-10-12 10:54:56', 1, '2018-10-11 17:21:11', '-16.5098581, -68.1267779', 0, NULL, NULL, '2018-10-11 17:32:34', '-16.5099009, -68.1267704', 0, 1, 4, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 16:58:25'),
(126, 10, 1, -1, 1, '2018-10-11 17:06:28', 'afn', 1, '2018-10-11 17:06:28', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-10-11 17:06:28'),
(127, 10, 1, -1, 1, '2018-10-11 17:06:28', 'afn', 1, '2018-10-11 17:06:28', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-10-11 17:06:28'),
(141, 3, 1, -1, 1, '2018-11-30 14:01:31', 'afn', 0, NULL, 1, '2018-12-18 13:31:26', '-16.5098797, -68.1267713', 1, '2018-12-18 13:31:15', '-16.5098695, -68.1267838', NULL, NULL, 0, 0, 0, NULL, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:31:26'),
(142, 3, 1, -1, 2, '2018-11-30 14:01:31', 'afn', 0, NULL, 1, '2018-12-18 13:36:04', '-16.50989, -68.1267754', 1, '2018-12-18 13:35:30', '-16.5098762, -68.1267667', NULL, NULL, 0, 0, 0, NULL, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:36:04'),
(143, 3, 1, -1, 1, '2018-11-30 14:06:33', 'afn', 0, NULL, 1, '2018-12-18 13:20:17', '-16.5098747, -68.1267678', 1, '2018-12-18 13:20:17', '-16.5098747, -68.1267678', NULL, NULL, 0, 0, 0, NULL, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:20:17'),
(144, 3, 1, -1, 2, '2018-11-30 14:06:33', 'afn', 0, NULL, 1, '2018-12-18 13:38:54', '-16.5098796, -68.1267615', 1, '2018-12-18 13:38:42', '-16.5098814, -68.1267615', NULL, NULL, 0, 0, 0, NULL, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:38:54'),
(145, 3, 1, -1, 1, '2018-11-30 14:58:41', 'afn', 0, NULL, 1, '2018-12-18 13:32:18', '-16.5098969, -68.1267651', 1, '2018-12-18 13:32:05', '-16.5098764, -68.1267641', NULL, NULL, 0, 0, 0, NULL, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:32:18'),
(146, 3, 1, -1, 1, '2018-11-30 15:01:13', 'afn', 1, '2018-11-30 15:01:13', 1, '2018-12-18 13:17:33', '-16.5098699, -68.1267604', 1, '2018-12-18 13:17:17', '-16.5098768, -68.1267674', NULL, NULL, 0, 0, 0, 0, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:17:33'),
(147, 3, 1, -1, 2, '2018-11-30 15:01:13', 'afn', 1, '2018-11-30 15:01:13', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 0, 0, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-17 19:03:01'),
(148, 3, 1, -1, 1, '2018-11-30 15:04:43', 'afn', 1, '2018-11-30 15:04:43', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 1, 0, 0, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-17 19:00:44'),
(149, 3, 1, -1, 2, '2018-11-30 15:04:43', 'afn', 1, '2018-12-18 12:29:11', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, '0000-00-00', 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 12:29:11'),
(150, 3, 1, -1, 1, '2018-11-30 15:06:14', 'afn', 1, '2018-11-30 15:06:14', 1, '2018-12-18 13:16:26', '-16.5098783, -68.1267627', 1, '2018-12-18 13:16:18', '-16.5098892, -68.1267659', NULL, NULL, 0, 0, 0, 0, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:16:26'),
(151, 3, 1, -1, 2, '2018-11-30 15:06:14', 'afn', 1, '2018-11-30 15:06:14', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-11-30 15:06:14'),
(152, 3, 1, -1, 1, '2018-11-30 15:08:00', 'afn', 1, '2018-11-30 15:08:00', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-11-30 15:08:00'),
(153, 3, 1, -1, 2, '2018-11-30 15:08:00', 'afn', 1, '2018-11-30 15:08:00', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-11-30 15:08:00'),
(154, 3, 1, -1, 1, '2018-11-30 15:09:06', 'afn', 1, '2018-11-30 15:09:06', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-11-30 15:09:06'),
(155, 3, 1, -1, 2, '2018-11-30 15:09:06', 'afn', 1, '2018-11-30 15:09:06', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-11-30 15:09:06'),
(156, 3, 1, -1, 1, '2018-11-30 15:09:58', 'afn', 1, '2018-11-30 15:09:58', 0, '2018-12-18 13:12:27', '-16.5098966, -68.1267695', 1, '2018-12-18 13:12:17', '-16.5098924, -68.1267669', NULL, NULL, 0, 0, 0, 0, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 13:12:27'),
(157, 3, 1, -1, 2, '2018-11-30 15:09:58', 'afn', 1, '2018-11-30 15:09:58', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-11-30 15:09:58'),
(158, 3, 1, -1, 1, '2018-11-30 15:11:38', 'afn', 3, '2019-02-07 13:06:44', 1, '2018-12-18 13:12:58', '-16.5098698, -68.1267622', 1, '2018-12-18 13:12:58', '-16.5098698, -68.1267622', NULL, NULL, 0, 0, 0, 0, '102012345', 'Juan Perez pp', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', NULL, 0, 0, '0000-00-00', 'Sin comentario', NULL, NULL, 'usuario.app', '2019-02-07 13:06:44'),
(159, 3, 1, -1, 2, '2018-11-30 15:11:38', 'afn', 1, '2018-11-30 15:11:38', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '5461200', 'Marta Gomez', 'Empresa de Marta', 45001, 'Calle 343', '-16.539989, -68.077975', '454564654', '685604', 'mart@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-11-30 15:11:38'),
(160, 3, 1, -1, 1, '2018-11-30 15:12:49', 'afn', 8, '2018-12-27 10:23:28', 1, '2018-12-18 12:27:52', '-16.5098625, -68.1267776', 1, '2018-12-18 12:27:52', '-16.5098625, -68.1267776', '2018-12-18 16:39:04', '-16.5098414, -68.1267673', 0, 1, 0, 0, '102012345', 'Juan Perez', 'Empresa de Juan', 5000, 'Calle 123', '-16.539989, -68.077975', '27877878', '72888888', 'juan@juan.com', 1, 'usuari', '2018-12-18', 0, 5000, '2019-01-02', 'Sin comentario', NULL, NULL, 'usuario.app', '2019-07-10 11:25:55'),
(161, 3, 1, -1, 2, '2018-11-30 15:12:49', 'afn', 2, '2018-12-14 21:07:58', 1, '2018-12-12 11:02:28', '-16.5098702, -68.1267782', 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '12345', 'Cliente nombre', 'La empresa del cliente', 8000, 'Sopocachi Abaroa', '-16.5098601,-68.1267823', '299999', '72123456', 'xkeinte@correo.net', 1, 'usuari', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-14 21:07:58'),
(162, 3, 1, -1, 2, '2018-12-14 22:39:27', 'afn', 1, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 'IDC123', 'Nombre Nuevo', 'Empresa Nueva', 5600, 'Dirección NUeva', '16.22,16.33', '27945', '72018', 'asd@asd.org', 2, 'usuario.app', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-14 22:39:27'),
(163, 3, 1, -1, 2, '2018-12-14 22:41:56', 'afn', 0, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 'IDC123', 'Nombre Nuevo', 'Empresa Nueva', 5600, 'Dirección NUeva', '16.22,16.33', '27945', '72018', 'asd@asd.org', 2, 'usuario.app', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-14 22:41:56'),
(164, 3, 1, -1, 2, '2018-12-14 22:43:52', 'afn', 0, NULL, 1, '2018-12-27 10:07:35', '-16.5098627, -68.1267704', 1, '2018-12-27 10:07:25', '-16.50986, -68.1267691', NULL, NULL, 0, 0, 0, NULL, 'IDC123', 'Nombre Nuevo', 'Empresa Nueva', 5600, 'Dirección NUeva', '16.22,16.33', '27945', '72018', 'asd@asd.org', 2, 'usuario.app', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-27 10:07:35'),
(165, 3, 1, -1, 2, '2018-12-14 22:45:23', 'afn', 2, '2018-12-17 09:48:29', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 'IDC123', 'Nombre Nuevo', 'Empresa Nueva', 5600, 'Dirección NUeva', '-16.5098449,-68.1268573', '27945', '72018', 'asd@asd.org', 2, 'usuario.app', '2018-12-17', 0, 0, '0000-00-00', 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-17 09:48:29'),
(166, 3, 1, -1, 3, '2018-12-17 10:09:28', 'afn', 1, '2018-12-18 17:44:01', 1, '2018-12-18 11:55:39', '-16.509905, -68.126747', 1, '2018-12-18 11:55:39', '-16.509905, -68.126747', '2018-12-18 17:44:01', '-16.5098776, -68.1267617', 0, 1, 0, 0, 'idc lunes', 'cliente lunes', 'empresa lunes', 1234, 'calle lunes', '-16.539989, -68.077975', '291', '720', 'lunes@mail.com', 2, 'usuario.app', '2018-12-17', 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-18 17:47:59'),
(167, 3, 1, -1, 3, '2018-12-18 16:52:25', 'afn', 1, '2018-12-18 16:52:25', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '6859076 LP', 'Gonzalo Tret', 'Dert', 536, 'dirección ', '-16.539989, -68.077975', '233333', '72018907', 'jraymondad@gmail.com', 2, 'usuario.app', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 16:52:25'),
(168, 3, 1, -1, 3, '2018-12-18 16:55:05', 'afn', 1, '2018-12-19 09:56:08', 1, '2018-12-19 09:55:37', '-16.5098927, -68.1267656', 1, '2018-12-19 09:55:42', '-16.5098745, -68.1267679', '2018-12-19 09:56:08', '-16.5098741, -68.1267677', 1, 0, 0, 0, '686999 Cbb', 'Andres q', 'empresa Andrés ', 6501, 'calle ', '-16.539989, -68.077975', '+591767278', '733333', 'andres.quintanilla@khipu.com', 2, 'usuario.app', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-19 09:56:08'),
(169, 3, 1, -1, 1, '2018-12-18 16:56:46', 'afn', 6, '2019-02-07 13:08:54', 1, '2018-12-18 16:57:15', '-16.5105648, -68.1267688', 1, '2018-12-18 16:57:21', '-16.5105648, -68.1267688', NULL, NULL, 0, 0, 0, 0, '4845445 LP', 'Andres Quintanilla', 'CG', 13131, 'San Miguel', '-16.539989, -68.077975', '+591610018', '61001871', 'qpandres@gmail.com', 2, 'usuario.app', NULL, 582, 0, '0000-00-00', 'Sin comentario', NULL, NULL, 'usuario.app', '2019-02-07 13:08:54'),
(170, 3, 1, -1, 3, '2018-12-18 17:10:45', 'afn', 2, '2018-12-18 17:12:36', 1, '2018-12-18 17:11:15', '-16.5098988, -68.1267753', 1, '2018-12-18 17:11:25', '-16.5098988, -68.1267753', NULL, NULL, 0, 0, 0, 0, '5098684 ', 'Rodrigo Gonzales', 'CIES', 4500, 'Av Arce #4321', '-16.539989, -68.077975', '0', '75071200', 'rodrigogonzaleszuazo@gmail.com', 2, 'usuario.app', NULL, 0, 0, '0000-00-00', 'Sin comentario', NULL, NULL, 'usuario.app', '2018-12-18 17:12:36'),
(171, 19, 1, -1, 2, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '00672936OR', 'VELARDE MENDOZA JAVIER ANTONIO', 'MONOPOL LTDA.', 5938, 'AV ALEXANDER Y CALLE 23 CONDOMINIO GIRASOLES EDIF LOS ALAMOS', '-16.539989, -68.077975', '0', '78888680', 'mleaplaza@cetus-group.net', 1, 'S22566', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(172, 14, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '00792952CB', 'GARRETT GARRON RENE CARLOS JUAN', '-', 1, 'CALLE CA ENRIQUE ARCE N 2249  N.2249', '-16.539989, -68.077975', '4246995', '12345678', 'mleaplaza@cetus-group.net', 1, 'S40258', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(173, 18, 1, -1, 7, '2018-12-27 11:59:31', 'afn', 8, '2018-12-30 17:23:14', 0, NULL, NULL, 0, NULL, NULL, '2018-12-30 17:21:03', '-16.5098988, -68.1267753', 0, 1, 0, 0, '02079234LP', 'APAZA HERRERA ROBERTO', '-', 1, 'AV CIRCUNVALACION ZONA FLOR DE IRPAVI 120', '-16.539989, -68.077975', '2257975', '76250081', 'mleaplaza@cetus-group.net', 1, 'S40259', NULL, 0, 333555, '2018-12-30', 'Sin comentario', NULL, NULL, 'S40259', '2018-12-30 17:23:14'),
(174, 16, 1, -1, 6, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '02523909LP', 'ROMERO NN RAUL ISIDRO', '-', 1, 'CA.INNOMINADA N.300', '-16.539989, -68.077975', '44350423', '12345678', 'mleaplaza@cetus-group.net', 1, 'S47460', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(175, 18, 1, -1, 7, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '02539459LP', 'GUTIERREZ-ARANDA DE MARCA ROSARIO', '-', 1, 'CA CALASASAYA 1657', '-16.539989, -68.077975', '0', '73092555', 'mleaplaza@cetus-group.net', 1, 'S40259', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(176, 21, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '02690020LP', 'GONZALES CORIZA NELSON CAYO', '-', 1, 'AV.R A BARRIO ALTO SAN PEDRO N.6547 MZ.E16 BRR.VILLA FATIMA', '-16.539989, -68.077975', '78181583', '78181583', 'mleaplaza@cetus-group.net', 1, 'S32238', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(177, 14, 1, -1, 6, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '02713340LP', 'ESPINOZA COLQUE ISMAEL YONNY', '-', 1, 'CALLE TAPACARI ENTRE CALLE ESTEBAN ARCE Y CALLE AN', '-16.539989, -68.077975', '4550009', '12345678', 'mleaplaza@cetus-group.net', 1, 'S40258', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(178, 14, 1, -1, 6, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03116312OR', 'CHOQUE CONDORI IVAN MARCELO', '-', 1, 'CA.LUCIA ALCOCER N.2126 BRR.JAIHUAYCO', '-16.539989, -68.077975', '4560033', '79785157', 'mleaplaza@cetus-group.net', 1, 'S40258', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(179, 13, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03204380SC', 'ARTEAGA AGUILERA LOSMAR AUGUSTO', '-', 1, 'CA.TRISTAN ROCA FINAL N.0', '-16.539989, -68.077975', '3516553', '70077733', 'mleaplaza@cetus-group.net', 1, 'B00351', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2019-03-29 14:13:50'),
(180, 19, 1, -1, 5, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03329714LP', 'TORO VDA. DE VIRREIRA RAQUEL VERONICA', '-', 2769, 'AV.GOBLES Y CALLE 16 N.170', '-16.539989, -68.077975', '2724963', '70616377', 'mleaplaza@cetus-group.net', 1, 'S22566', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(181, 18, 1, -1, 7, '2018-12-27 11:59:31', 'afn', 8, '2018-12-27 17:18:12', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03337661LP', 'MUR PASTEN PABLO CARLOS', '-', 1, 'CA.1 N.14', '-16.539989, -68.077975', '999999', '71543253', 'mleaplaza@cetus-group.net', 1, 'S40259', '2018-12-27', 0, 367044, '2019-01-08', 'Sin comentario', NULL, NULL, 'S40259', '2018-12-27 17:18:12'),
(182, 21, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03375833LP', 'ALVAREZ FLORES WENDY ESTHER', '-', 1, 'CA.OTERO DE LA VEGA N.273 Z. SAN PEDRO', '-16.539989, -68.077975', '2490936', '79564021', 'mleaplaza@cetus-group.net', 1, 'S32238', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(183, 22, 1, -1, 3, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03393462LP', 'TORRICO MENDEZ ROSARIO PAMELA', '-', 1357, 'AV BUSCH N.1572 D.8B ED.BOSTON Z. MIRAFLORES 1572', '-16.539989, -68.077975', '2225051', '12345678', 'mleaplaza@cetus-group.net', 1, 'S38828', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(184, 16, 1, -1, 7, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03440757LP', 'AMARRO RAMOS LUIS ALBERTO', '-', 1, 'AV BLANCO GALINDO 2478', '-16.539989, -68.077975', '72775488', '72775488', 'mleaplaza@cetus-group.net', 1, 'S47460', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(185, 21, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03450041LP', 'AYCA RIVERA RUBEN', '-', 1, 'CALLE REPUBLICA DOMINICANA AL LADO DEL MATERNO INFANTIL 1941', '-16.539989, -68.077975', '2', '12345678', 'mleaplaza@cetus-group.net', 1, 'S32238', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(186, 21, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03464698LP', 'LOAYZA CERRUTO DIEGO GONZALO', '-', 1, 'CA 26 A MEDIA CUADRA DE AV THE STRONGEST 8', '-16.539989, -68.077975', '2713958', '73053264', 'mleaplaza@cetus-group.net', 1, 'S32238', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(187, 15, 1, -1, 5, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03766567PE', 'TANG NN HUOY KUNG', '-', 5539, 'CA AV VILLAZON KM 1 URBANIZACION PARAISO CASA NRO 5 ZONA MES', '-16.539989, -68.077975', '4533413', '12345678', 'mleaplaza@cetus-group.net', 1, 'S66981', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(188, 16, 1, -1, 6, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03766993CB', 'CAMARGO LOPEZ MARCELO', '-', 1, 'CA.MANUEL ISIDORO BELZU N.0', '-16.539989, -68.077975', '4258891', '72151487', 'mleaplaza@cetus-group.net', 1, 'S47460', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(189, 15, 1, -1, 8, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03770803CB', 'ZOTEZ ARAOZ LITZIE MARGOT', 'SERVEI S.R.L.', 5843, 'CA CIRUELOS Y CALLE LUIS CALVO 2', '-16.539989, -68.077975', '4458713', '70716426', 'mleaplaza@cetus-group.net', 1, 'S66981', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(190, 14, 1, -1, 6, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03772875CB', 'NAVA CHAMBI ROXANA', '-', 1, 'AV.PETROLERA KM 4 Y MEDIO Y CALLE NORUEGA N.0', '-16.539989, -68.077975', '44237481', '12345678', 'mleaplaza@cetus-group.net', 1, 'S40258', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(191, 13, 1, -1, 6, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03855426SC', 'PREST MENDOZA LUIS', '-', 1, 'AV ALEMANA Y 7MO ANILLO CONDOMINIO LA SIERRA DPTO.205 BARRIO', '-16.539989, -68.077975', '3524502', '12345678', 'mleaplaza@cetus-group.net', 1, 'B00351', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2019-03-29 14:13:50'),
(192, 15, 1, -1, 5, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03902054SC', 'CHOQUE FLORES ROBERT', '-', 4775, 'CA LADISLAO CABRERA ENTRE AVENIDA AYACUCHO Y CALLE NATANIEL', '-16.539989, -68.077975', '4256219', '72222623', 'mleaplaza@cetus-group.net', 1, 'S66981', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(193, 18, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 8, '2018-12-27 17:09:06', 1, '2018-12-27 17:05:00', '-16.5100352, -68.1267697', 1, '2018-12-27 17:04:51', '-16.5100058, -68.12677', NULL, NULL, 0, 0, 0, 0, '04250707LP', 'APAZA PACO ELSA VERONICA', 'Empresa01', 1667, 'calle 9 calacoto esq. Ormachea', '-16.541107031199232,-68.091662786901', '2257975', '7626261', 'mleaplaza@cetus-group.net', 1, 'S40259', '2018-12-28', 0, 3254, '2018-12-31', 'Sin comentario', NULL, NULL, 'S40259', '2018-12-27 17:09:06'),
(194, 16, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '04502898CB', 'ALMARAZ MONTANO EDGAR', '-', 1, 'CA.LUIS REVOLLO N.0', '-16.539989, -68.077975', '4704638', '71775300', 'mleaplaza@cetus-group.net', 1, 'S47460', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(195, 12, 1, -1, 3, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '04612977SC', 'MOLINA NUNEZ-VELA INES', '-', 1577, 'CA.27 DE MAYO N.144 BRR.BARRIOLINDO', '-16.539989, -68.077975', '3338737', '70020820', 'mleaplaza@cetus-group.net', 1, 'S25691', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(196, 11, 1, -1, 5, '2018-12-27 11:59:31', 'afn', 8, '2018-12-27 12:38:00', 1, '2018-12-27 12:36:54', '-16.509932, -68.1267454', 1, '2018-12-27 12:36:51', '-16.509932, -68.1267454', NULL, NULL, 0, 0, 0, 0, '04679062SC', 'REQUE ZAPATA RICHARD', '-', 3473, 'AV.LA PENA UV SJ11 DIST 29 MZ.18 URB.JARDINES DEL SUR Z.', '-16.539989, -68.077975', '3215400', '71053455', 'mleaplaza@cetus-group.net', 1, 'B02978', NULL, 0, 5000, '2019-01-30', 'Sin comentario', NULL, NULL, 'B02978', '2018-12-27 12:38:00'),
(197, 16, 1, -1, 6, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '04765128LP', 'MEDRANO FRANCO SAHARA MARIBEL', '-', 1, 'CA.LUCIA ALCOCER N.2554', '-16.539989, -68.077975', '4234633', '72707043', 'mleaplaza@cetus-group.net', 1, 'S47460', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(198, 22, 1, -1, 8, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '04805871LP', 'VASQUEZ VASQUEZ SUSANA TATIANA', 'INMOBILIARIA KANTUTANI S.A.', 957, 'CA.51 N.99 I.BRR.CHASQUIPAMPA', '-16.539989, -68.077975', '2795436', '72084574', 'mleaplaza@cetus-group.net', 1, 'S38828', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(199, 12, 1, -1, 3, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '05224630CB', 'MARQUEZ APARICIO GABRIEL MARCELO', '-', 1530, 'CALLE LOS TUCANES UV 175 URB PALMA DORADA ENTRE 5TO Y 6TO', '-16.539989, -68.077975', '33553471', '12345678', 'mleaplaza@cetus-group.net', 1, 'S25691', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(200, 12, 1, -1, 3, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '05224780CB', 'VARGAS LASERNA GABRIEL', '-', 1457, 'CA.FERMIN PERALTA BARRIO EQUIPETROL N.125', '-16.539989, -68.077975', '3425953', '70364576', 'mleaplaza@cetus-group.net', 1, 'S25691', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(201, 14, 1, -1, 7, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '05271361CB', 'UNZUETA SACHSE JORGE LUIS', '-', 1, 'CA LAS BEGONIAS Z ALTO ARANJUEZ SN', '-16.539989, -68.077975', '4291202', '76460660', 'mleaplaza@cetus-group.net', 1, 'S40258', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(202, 15, 1, -1, 5, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '05301600CB', 'VILLARROEL SCHNEIDER OMAR', '-', 8636, 'CA.JUNIN ENTRE CALLE LA PAZ Y CALLE TENIENT N.0', '-16.539989, -68.077975', '4763577', '71495525', 'mleaplaza@cetus-group.net', 1, 'S66981', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(203, 13, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '05384111SC', 'GUTIERREZ TAPIA LUIS ENRIQUE', '-', 1, 'CA 1 URBANIZACION LOS PENOCOS AVENIDA PANAMERICANO UV 91A MZ', '-16.539989, -68.077975', '3472969', '60835352', 'mleaplaza@cetus-group.net', 1, 'B00351', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2019-03-29 14:13:50'),
(204, 11, 1, -1, 5, '2018-12-27 11:59:31', 'afn', 2, '2018-12-27 16:16:29', 1, '2018-12-27 12:49:01', '-16.5098993, -68.126768', 1, '2018-12-27 16:14:02', '-16.5099069, -68.126791', NULL, NULL, 0, 0, 0, 0, '06326380SC', 'LINARES ARANDIA ROMMEL ALBERTO', 'Componente Digital ', 4151, 'CA.SOYUBO UV 340 N.55 MZ.001 URB.LA BADECO CASA 55', '-16.539989, -68.077975', '3462732', '69201007', 'mleaplaza@cetus-group.net', 1, 'B02978', '2018-12-27', 0, 0, '0000-00-00', 'Sin comentario', NULL, NULL, 'B02978', '2018-12-27 16:16:29'),
(205, 13, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '07832981SC', 'ARTEAGA ROJAS LIMBERTH', '-', 1, 'AV SANTOS DUMONT ENTRE 7MO Y 8VO ANILLO BARRIO LAS AMERICAS', '-16.539989, -68.077975', '71617124', '67848033', 'mleaplaza@cetus-group.net', 1, 'B00351', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2019-03-29 14:13:50'),
(206, 21, 1, -1, 1, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '09160604LP', 'LEON CHOQUE SANDRA CAROLINA', '-', 1, 'CA ASPIAZU ZONA LA MERCED 1381', '-16.539989, -68.077975', '2218775', '69740894', 'mleaplaza@cetus-group.net', 1, 'S32238', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(207, 11, 1, -1, 5, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '10755101PE', 'TALAVERA SOLALINDE FRANCISCO ENRIQUE BEHMACK', '-', 5570, 'AV SAN MARTIN HOTEL CAMINO REAL DEPTO 329 ENTRE 3RO Y 4TO AN', '-16.539989, -68.077975', '0', '69201778', 'mleaplaza@cetus-group.net', 1, 'B02978', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(208, 19, 1, -1, 2, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '02440401LP', 'TORRICO TERRAZAS DANIELA ZORKA', 'COMPANEX BOLIVIA S.A.', 3227, 'CA LAS ACACIAS UBANIZACION ISLA VERDE NRO 267 ZONA MALLASILL', '-16.539989, -68.077975', '2745664', '12345678', 'mleaplaza@cetus-group.net', 1, 'S22566', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(209, 22, 1, -1, 2, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '10267665PE', 'VILLA MOLINA ANDRES ESTEBAN', '-', 5135, 'CA 2 NO 80 Z ACHUMANI BARRIO PAMIRPAMPA CERAL CLUB ALEMAN CA', '-16.539989, -68.077975', '72154300', '72154300', 'mleaplaza@cetus-group.net', 1, 'S38828', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(210, 22, 1, -1, 2, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03919485SC', 'MORALES CONCHA VILMA PATRICIA', '-', 2040, 'AV 6 DE AGOSTO ED. ALIANZA P.23 DPTO. 02 2190', '-16.539989, -68.077975', '71961420', '12345678', 'mleaplaza@cetus-group.net', 1, 'S38828', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(211, 22, 1, -1, 2, '2018-12-27 11:59:31', 'afn', 1, '2018-12-27 11:59:31', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '03391703LP', 'ALVAREZ PORTUGAL RICARDO FERNANDO', 'CAJA NACIONAL DE SALUD LA PAZ', 1326, 'AV MUNOZ REYES ESQUINA CALLE 3 NO 200 A TRES CUADRAS DEL PUE', '-16.539989, -68.077975', '2772622', '76204305', 'mleaplaza@cetus-group.net', 1, 'S38828', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'usuario.gestion', '2018-12-27 11:59:31'),
(212, 18, 1, -1, 1, '2018-12-27 17:36:06', 'afn', 1, '2018-12-27 17:36:06', 1, '2018-12-27 17:37:05', '-16.5098865, -68.1267671', 1, '2018-12-27 17:37:02', '-16.509885, -68.1267651', NULL, NULL, 0, 0, 0, 0, '4845445', 'Andrés Q.', 'khipu', 3500, 'Av 20 de Octubre 402. Torre Zafiro', '-16.539989, -68.077975', '76727810', '76727810', 'andres.quintanilla@khipu.com', 2, 'S40259', '2018-12-27', 0, 0, NULL, 'Sin comentario', NULL, NULL, 'S40259', '2018-12-27 17:39:24'),
(213, 11, 1, -1, 1, '2018-12-27 17:37:08', 'afn', 1, '2018-12-27 17:37:08', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '6098284 ', 'Freddy Castillo ', 'Imcruz', 4000, 'Calacoto ', '-16.539989, -68.077975', '2791875', '60876543', 'mleaplaza@cetus-group.net', 2, 'B02978', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'B02978', '2018-12-27 17:37:08'),
(214, 11, 1, -1, 1, '2018-12-27 17:37:13', 'afn', 1, '2018-12-27 17:37:13', 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '6098284 ', 'Freddy Castillo ', 'Imcruz', 4000, 'Calacoto ', '-16.539989, -68.077975', '2791875', '60876543', 'mleaplaza@cetus-group.net', 2, 'B02978', NULL, 0, 0, NULL, 'Sin comentario', NULL, NULL, 'B02978', '2018-12-27 17:37:13');

--
-- Disparadores `prospecto`
--
DELIMITER $$
CREATE TRIGGER `zprospecto_ADEL` AFTER DELETE ON `prospecto` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'prospecto', OLD.`prospecto_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'prospecto_id', OLD.`prospecto_id`, NULL ),
  (auditoria_last_inserted_id, 'ejecutivo_id', OLD.`ejecutivo_id`, NULL ),
  (auditoria_last_inserted_id, 'tipo_persona_id', OLD.`tipo_persona_id`, NULL ),
  (auditoria_last_inserted_id, 'empresa_id', OLD.`empresa_id`, NULL ),
  (auditoria_last_inserted_id, 'camp_id', OLD.`camp_id`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_fecha_asignacion', OLD.`prospecto_fecha_asignacion`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_carpeta', OLD.`prospecto_carpeta`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_etapa', OLD.`prospecto_etapa`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_etapa_fecha', OLD.`prospecto_etapa_fecha`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_checkin', OLD.`prospecto_checkin`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_checkin_fecha', OLD.`prospecto_checkin_fecha`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_checkin_geo', OLD.`prospecto_checkin_geo`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_llamada', OLD.`prospecto_llamada`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_llamada_fecha', OLD.`prospecto_llamada_fecha`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_llamada_geo', OLD.`prospecto_llamada_geo`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_consolidar_fecha', OLD.`prospecto_consolidar_fecha`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_consolidar_geo', OLD.`prospecto_consolidar_geo`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_consolidado', OLD.`prospecto_consolidado`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_observado_app', OLD.`prospecto_observado_app`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_estado_actual', OLD.`prospecto_estado_actual`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_observado', OLD.`prospecto_observado`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_idc', OLD.`prospecto_idc`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_nombre_cliente', OLD.`prospecto_nombre_cliente`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_empresa', OLD.`prospecto_empresa`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_ingreso', OLD.`prospecto_ingreso`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_direccion', OLD.`prospecto_direccion`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_direccion_geo', OLD.`prospecto_direccion_geo`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_telefono', OLD.`prospecto_telefono`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_celular', OLD.`prospecto_celular`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_email', OLD.`prospecto_email`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_tipo_lead', OLD.`prospecto_tipo_lead`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_matricula', OLD.`prospecto_matricula`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_fecha_contacto1', OLD.`prospecto_fecha_contacto1`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_monto_aprobacion', OLD.`prospecto_monto_aprobacion`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_monto_desembolso', OLD.`prospecto_monto_desembolso`, NULL ),
  (auditoria_last_inserted_id, 'prospecto_fecha_desembolso', OLD.`prospecto_fecha_desembolso`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zprospecto_AINS` AFTER INSERT ON `prospecto` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'prospecto', NEW.`prospecto_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'prospecto_id', NULL, NEW.`prospecto_id`),
(auditoria_last_inserted_id, 'ejecutivo_id', NULL, NEW.`ejecutivo_id`),
(auditoria_last_inserted_id, 'tipo_persona_id', NULL, NEW.`tipo_persona_id`),
(auditoria_last_inserted_id, 'empresa_id', NULL, NEW.`empresa_id`),
(auditoria_last_inserted_id, 'camp_id', NULL, NEW.`camp_id`),
(auditoria_last_inserted_id, 'prospecto_fecha_asignacion', NULL, NEW.`prospecto_fecha_asignacion`),
(auditoria_last_inserted_id, 'prospecto_carpeta', NULL, NEW.`prospecto_carpeta`),
(auditoria_last_inserted_id, 'prospecto_etapa', NULL, NEW.`prospecto_etapa`),
(auditoria_last_inserted_id, 'prospecto_etapa_fecha', NULL, NEW.`prospecto_etapa_fecha`),
(auditoria_last_inserted_id, 'prospecto_checkin', NULL, NEW.`prospecto_checkin`),
(auditoria_last_inserted_id, 'prospecto_checkin_fecha', NULL, NEW.`prospecto_checkin_fecha`),
(auditoria_last_inserted_id, 'prospecto_checkin_geo', NULL, NEW.`prospecto_checkin_geo`),
(auditoria_last_inserted_id, 'prospecto_llamada', NULL, NEW.`prospecto_llamada`),
(auditoria_last_inserted_id, 'prospecto_llamada_fecha', NULL, NEW.`prospecto_llamada_fecha`),
(auditoria_last_inserted_id, 'prospecto_llamada_geo', NULL, NEW.`prospecto_llamada_geo`),
(auditoria_last_inserted_id, 'prospecto_consolidar_fecha', NULL, NEW.`prospecto_consolidar_fecha`),
(auditoria_last_inserted_id, 'prospecto_consolidar_geo', NULL, NEW.`prospecto_consolidar_geo`),
(auditoria_last_inserted_id, 'prospecto_consolidado', NULL, NEW.`prospecto_consolidado`),
(auditoria_last_inserted_id, 'prospecto_observado_app', NULL, NEW.`prospecto_observado_app`),
(auditoria_last_inserted_id, 'prospecto_estado_actual', NULL, NEW.`prospecto_estado_actual`),
(auditoria_last_inserted_id, 'prospecto_observado', NULL, NEW.`prospecto_observado`),
(auditoria_last_inserted_id, 'prospecto_idc', NULL, NEW.`prospecto_idc`),
(auditoria_last_inserted_id, 'prospecto_nombre_cliente', NULL, NEW.`prospecto_nombre_cliente`),
(auditoria_last_inserted_id, 'prospecto_empresa', NULL, NEW.`prospecto_empresa`),
(auditoria_last_inserted_id, 'prospecto_ingreso', NULL, NEW.`prospecto_ingreso`),
(auditoria_last_inserted_id, 'prospecto_direccion', NULL, NEW.`prospecto_direccion`),
(auditoria_last_inserted_id, 'prospecto_direccion_geo', NULL, NEW.`prospecto_direccion_geo`),
(auditoria_last_inserted_id, 'prospecto_telefono', NULL, NEW.`prospecto_telefono`),
(auditoria_last_inserted_id, 'prospecto_celular', NULL, NEW.`prospecto_celular`),
(auditoria_last_inserted_id, 'prospecto_email', NULL, NEW.`prospecto_email`),
(auditoria_last_inserted_id, 'prospecto_tipo_lead', NULL, NEW.`prospecto_tipo_lead`),
(auditoria_last_inserted_id, 'prospecto_matricula', NULL, NEW.`prospecto_matricula`),
(auditoria_last_inserted_id, 'prospecto_fecha_contacto1', NULL, NEW.`prospecto_fecha_contacto1`),
(auditoria_last_inserted_id, 'prospecto_monto_aprobacion', NULL, NEW.`prospecto_monto_aprobacion`),
(auditoria_last_inserted_id, 'prospecto_monto_desembolso', NULL, NEW.`prospecto_monto_desembolso`),
(auditoria_last_inserted_id, 'prospecto_fecha_desembolso', NULL, NEW.`prospecto_fecha_desembolso`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zprospecto_AUPD` AFTER UPDATE ON `prospecto` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'prospecto', OLD.`prospecto_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'prospecto_id', OLD.`prospecto_id`, NEW.`prospecto_id`),
  (auditoria_last_inserted_id, 'ejecutivo_id', OLD.`ejecutivo_id`, NEW.`ejecutivo_id`),
  (auditoria_last_inserted_id, 'tipo_persona_id', OLD.`tipo_persona_id`, NEW.`tipo_persona_id`),
  (auditoria_last_inserted_id, 'empresa_id', OLD.`empresa_id`, NEW.`empresa_id`),
  (auditoria_last_inserted_id, 'camp_id', OLD.`camp_id`, NEW.`camp_id`),
  (auditoria_last_inserted_id, 'prospecto_fecha_asignacion', OLD.`prospecto_fecha_asignacion`, NEW.`prospecto_fecha_asignacion`),
  (auditoria_last_inserted_id, 'prospecto_carpeta', OLD.`prospecto_carpeta`, NEW.`prospecto_carpeta`),
  (auditoria_last_inserted_id, 'prospecto_etapa', OLD.`prospecto_etapa`, NEW.`prospecto_etapa`),
  (auditoria_last_inserted_id, 'prospecto_etapa_fecha', OLD.`prospecto_etapa_fecha`, NEW.`prospecto_etapa_fecha`),
  (auditoria_last_inserted_id, 'prospecto_checkin', OLD.`prospecto_checkin`, NEW.`prospecto_checkin`),
  (auditoria_last_inserted_id, 'prospecto_checkin_fecha', OLD.`prospecto_checkin_fecha`, NEW.`prospecto_checkin_fecha`),
  (auditoria_last_inserted_id, 'prospecto_checkin_geo', OLD.`prospecto_checkin_geo`, NEW.`prospecto_checkin_geo`),
  (auditoria_last_inserted_id, 'prospecto_llamada', OLD.`prospecto_llamada`, NEW.`prospecto_llamada`),
  (auditoria_last_inserted_id, 'prospecto_llamada_fecha', OLD.`prospecto_llamada_fecha`, NEW.`prospecto_llamada_fecha`),
  (auditoria_last_inserted_id, 'prospecto_llamada_geo', OLD.`prospecto_llamada_geo`, NEW.`prospecto_llamada_geo`),
  (auditoria_last_inserted_id, 'prospecto_consolidar_fecha', OLD.`prospecto_consolidar_fecha`, NEW.`prospecto_consolidar_fecha`),
  (auditoria_last_inserted_id, 'prospecto_consolidar_geo', OLD.`prospecto_consolidar_geo`, NEW.`prospecto_consolidar_geo`),
  (auditoria_last_inserted_id, 'prospecto_consolidado', OLD.`prospecto_consolidado`, NEW.`prospecto_consolidado`),
  (auditoria_last_inserted_id, 'prospecto_observado_app', OLD.`prospecto_observado_app`, NEW.`prospecto_observado_app`),
  (auditoria_last_inserted_id, 'prospecto_estado_actual', OLD.`prospecto_estado_actual`, NEW.`prospecto_estado_actual`),
  (auditoria_last_inserted_id, 'prospecto_observado', OLD.`prospecto_observado`, NEW.`prospecto_observado`),
  (auditoria_last_inserted_id, 'prospecto_idc', OLD.`prospecto_idc`, NEW.`prospecto_idc`),
  (auditoria_last_inserted_id, 'prospecto_nombre_cliente', OLD.`prospecto_nombre_cliente`, NEW.`prospecto_nombre_cliente`),
  (auditoria_last_inserted_id, 'prospecto_empresa', OLD.`prospecto_empresa`, NEW.`prospecto_empresa`),
  (auditoria_last_inserted_id, 'prospecto_ingreso', OLD.`prospecto_ingreso`, NEW.`prospecto_ingreso`),
  (auditoria_last_inserted_id, 'prospecto_direccion', OLD.`prospecto_direccion`, NEW.`prospecto_direccion`),
  (auditoria_last_inserted_id, 'prospecto_direccion_geo', OLD.`prospecto_direccion_geo`, NEW.`prospecto_direccion_geo`),
  (auditoria_last_inserted_id, 'prospecto_telefono', OLD.`prospecto_telefono`, NEW.`prospecto_telefono`),
  (auditoria_last_inserted_id, 'prospecto_celular', OLD.`prospecto_celular`, NEW.`prospecto_celular`),
  (auditoria_last_inserted_id, 'prospecto_email', OLD.`prospecto_email`, NEW.`prospecto_email`),
  (auditoria_last_inserted_id, 'prospecto_tipo_lead', OLD.`prospecto_tipo_lead`, NEW.`prospecto_tipo_lead`),
  (auditoria_last_inserted_id, 'prospecto_matricula', OLD.`prospecto_matricula`, NEW.`prospecto_matricula`),
  (auditoria_last_inserted_id, 'prospecto_fecha_contacto1', OLD.`prospecto_fecha_contacto1`, NEW.`prospecto_fecha_contacto1`),
  (auditoria_last_inserted_id, 'prospecto_monto_aprobacion', OLD.`prospecto_monto_aprobacion`, NEW.`prospecto_monto_aprobacion`),
  (auditoria_last_inserted_id, 'prospecto_monto_desembolso', OLD.`prospecto_monto_desembolso`, NEW.`prospecto_monto_desembolso`),
  (auditoria_last_inserted_id, 'prospecto_fecha_desembolso', OLD.`prospecto_fecha_desembolso`, NEW.`prospecto_fecha_desembolso`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prospecto_servicio`
--

CREATE TABLE `prospecto_servicio` (
  `prospecto_servicio_id` int(11) NOT NULL,
  `prospecto_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `prospecto_servicio`
--

INSERT INTO `prospecto_servicio` (`prospecto_servicio_id`, `prospecto_id`, `servicio_id`, `accion_usuario`, `accion_fecha`) VALUES
(237, 119, 1, 'usuario.gestion', '2018-05-03 10:51:55'),
(239, 121, 1, 'usuario.gestion', '2018-10-08 16:56:03'),
(244, 125, 1, 'usuario.gestion', '2018-10-11 17:06:28'),
(245, 126, 1, 'usuario.gestion', '2018-10-11 17:06:28'),
(246, 127, 1, 'usuario.gestion', '2018-10-11 17:06:28'),
(247, 127, 4, 'usuario.gestion', '2018-10-11 17:06:28'),
(259, 141, 1, 'usuario.gestion', '2018-11-30 14:01:31'),
(260, 141, 4, 'usuario.gestion', '2018-11-30 14:01:31'),
(261, 142, 2, 'usuario.gestion', '2018-11-30 14:01:31'),
(262, 142, 4, 'usuario.gestion', '2018-11-30 14:01:31'),
(263, 143, 1, 'usuario.gestion', '2018-11-30 14:06:33'),
(264, 143, 4, 'usuario.gestion', '2018-11-30 14:06:33'),
(265, 144, 2, 'usuario.gestion', '2018-11-30 14:06:33'),
(266, 144, 4, 'usuario.gestion', '2018-11-30 14:06:33'),
(267, 145, 1, 'usuario.gestion', '2018-11-30 14:58:41'),
(268, 145, 4, 'usuario.gestion', '2018-11-30 14:58:41'),
(269, 146, 1, 'usuario.gestion', '2018-11-30 15:01:13'),
(270, 146, 4, 'usuario.gestion', '2018-11-30 15:01:13'),
(271, 147, 2, 'usuario.gestion', '2018-11-30 15:01:13'),
(272, 147, 4, 'usuario.gestion', '2018-11-30 15:01:13'),
(273, 148, 1, 'usuario.gestion', '2018-11-30 15:04:43'),
(274, 148, 4, 'usuario.gestion', '2018-11-30 15:04:43'),
(275, 149, 2, 'usuario.gestion', '2018-11-30 15:04:43'),
(276, 149, 4, 'usuario.gestion', '2018-11-30 15:04:43'),
(277, 150, 1, 'usuario.gestion', '2018-11-30 15:06:14'),
(278, 150, 4, 'usuario.gestion', '2018-11-30 15:06:14'),
(279, 151, 2, 'usuario.gestion', '2018-11-30 15:06:14'),
(280, 151, 4, 'usuario.gestion', '2018-11-30 15:06:14'),
(281, 152, 1, 'usuario.gestion', '2018-11-30 15:08:00'),
(282, 152, 4, 'usuario.gestion', '2018-11-30 15:08:00'),
(283, 153, 2, 'usuario.gestion', '2018-11-30 15:08:00'),
(284, 153, 4, 'usuario.gestion', '2018-11-30 15:08:00'),
(285, 154, 1, 'usuario.gestion', '2018-11-30 15:09:06'),
(286, 154, 4, 'usuario.gestion', '2018-11-30 15:09:06'),
(287, 155, 2, 'usuario.gestion', '2018-11-30 15:09:06'),
(288, 155, 4, 'usuario.gestion', '2018-11-30 15:09:06'),
(289, 156, 1, 'usuario.gestion', '2018-11-30 15:09:58'),
(290, 156, 4, 'usuario.gestion', '2018-11-30 15:09:58'),
(291, 157, 2, 'usuario.gestion', '2018-11-30 15:09:58'),
(292, 157, 4, 'usuario.gestion', '2018-11-30 15:09:58'),
(293, 158, 1, 'usuario.gestion', '2018-11-30 15:11:38'),
(294, 158, 4, 'usuario.gestion', '2018-11-30 15:11:38'),
(295, 159, 2, 'usuario.gestion', '2018-11-30 15:11:38'),
(296, 159, 4, 'usuario.gestion', '2018-11-30 15:11:38'),
(354, 161, 1, 'usuario.app', '2018-12-13 18:59:37'),
(355, 161, 2, 'usuario.app', '2018-12-13 18:59:37'),
(356, 161, 3, 'usuario.app', '2018-12-13 18:59:37'),
(357, 161, 4, 'usuario.app', '2018-12-13 18:59:37'),
(358, 161, 5, 'usuario.app', '2018-12-13 18:59:37'),
(359, 161, 2, 'usuario.app', '2018-12-14 18:06:09'),
(360, 162, 4, 'usuario.app', '2018-12-14 22:39:27'),
(361, 162, 2, 'usuario.app', '2018-12-14 22:39:27'),
(364, 163, 4, 'usuario.app', '2018-12-14 22:41:56'),
(365, 163, 2, 'usuario.app', '2018-12-14 22:41:56'),
(366, 163, 2, 'usuario.app', '2018-12-14 22:41:56'),
(368, 164, 4, 'usuario.app', '2018-12-14 22:43:52'),
(369, 164, 2, 'usuario.app', '2018-12-14 22:43:52'),
(370, 164, 2, 'usuario.app', '2018-12-14 22:43:52'),
(374, 165, 2, 'usuario.app', '2018-12-17 09:48:01'),
(375, 165, 4, 'usuario.app', '2018-12-17 09:48:01'),
(377, 166, 1, 'usuario.app', '2018-12-17 10:11:18'),
(378, 166, 2, 'usuario.app', '2018-12-17 10:11:18'),
(379, 166, 4, 'usuario.app', '2018-12-17 10:11:18'),
(380, 166, 5, 'usuario.app', '2018-12-17 10:11:18'),
(387, 167, 2, 'usuario.app', '2018-12-18 16:52:25'),
(388, 168, 2, 'usuario.app', '2018-12-18 16:55:05'),
(389, 169, 1, 'usuario.app', '2018-12-18 16:56:46'),
(390, 169, 4, 'usuario.app', '2018-12-18 16:56:46'),
(391, 170, 2, 'usuario.app', '2018-12-18 17:10:45'),
(392, 171, 4, 'usuario.gestion', '2018-12-27 11:59:31'),
(393, 172, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(394, 173, 3, 'usuario.gestion', '2018-12-27 11:59:31'),
(395, 174, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(396, 175, 3, 'usuario.gestion', '2018-12-27 11:59:31'),
(397, 176, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(398, 177, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(399, 178, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(400, 179, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(401, 180, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(403, 182, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(404, 183, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(405, 184, 3, 'usuario.gestion', '2018-12-27 11:59:31'),
(406, 185, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(407, 186, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(408, 187, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(409, 188, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(410, 189, 5, 'usuario.gestion', '2018-12-27 11:59:31'),
(411, 190, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(412, 191, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(413, 192, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(415, 194, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(416, 195, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(417, 196, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(418, 197, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(419, 198, 5, 'usuario.gestion', '2018-12-27 11:59:31'),
(420, 199, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(421, 200, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(422, 201, 3, 'usuario.gestion', '2018-12-27 11:59:31'),
(423, 202, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(424, 203, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(426, 205, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(427, 206, 1, 'usuario.gestion', '2018-12-27 11:59:31'),
(428, 207, 2, 'usuario.gestion', '2018-12-27 11:59:31'),
(429, 208, 4, 'usuario.gestion', '2018-12-27 11:59:31'),
(430, 209, 4, 'usuario.gestion', '2018-12-27 11:59:31'),
(431, 210, 4, 'usuario.gestion', '2018-12-27 11:59:31'),
(432, 211, 4, 'usuario.gestion', '2018-12-27 11:59:31'),
(433, 204, 2, 'B02978', '2018-12-27 16:16:02'),
(434, 204, 3, 'B02978', '2018-12-27 16:16:02'),
(435, 193, 1, 'S40259', '2018-12-27 17:08:19'),
(436, 193, 2, 'S40259', '2018-12-27 17:08:19'),
(437, 193, 4, 'S40259', '2018-12-27 17:08:19'),
(438, 193, 5, 'S40259', '2018-12-27 17:08:19'),
(439, 181, 1, 'S40259', '2018-12-27 17:15:01'),
(440, 181, 2, 'S40259', '2018-12-27 17:15:01'),
(441, 181, 3, 'S40259', '2018-12-27 17:15:01'),
(443, 213, 1, 'B02978', '2018-12-27 17:37:08'),
(444, 214, 1, 'B02978', '2018-12-27 17:37:13'),
(445, 212, 1, 'S40259', '2018-12-27 17:39:24'),
(446, 212, 2, 'S40259', '2018-12-27 17:39:24'),
(447, 212, 4, 'S40259', '2018-12-27 17:39:24'),
(450, 160, 1, 'usuario.app', '2019-07-10 11:25:55'),
(451, 160, 4, 'usuario.app', '2019-07-10 11:25:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL,
  `rol_nombre` varchar(40) DEFAULT NULL,
  `rol_descirpcion` varchar(100) DEFAULT NULL,
  `rol_estado` int(1) DEFAULT '1' COMMENT '0 = No vigente\n1 = Vigente',
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_id`, `rol_nombre`, `rol_descirpcion`, `rol_estado`, `accion_usuario`, `accion_fecha`) VALUES
(1, 'Administrador', 'Administrador Principal del Sistema', 1, 'usuario.gestion', '2019-08-12 13:41:58'),
(2, 'Ejecutivo/Agente Móvil (APP)', 'Rol principal de acceso a la solución móvil', 1, 'usuario.gestion', '2018-11-13 12:08:51');

--
-- Disparadores `rol`
--
DELIMITER $$
CREATE TRIGGER `zrol_ADEL` AFTER DELETE ON `rol` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'rol', OLD.`rol_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'rol_id', OLD.`rol_id`, NULL ),
  (auditoria_last_inserted_id, 'rol_nombre', OLD.`rol_nombre`, NULL ),
  (auditoria_last_inserted_id, 'rol_descirpcion', OLD.`rol_descirpcion`, NULL ),
  (auditoria_last_inserted_id, 'rol_estado', OLD.`rol_estado`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zrol_AINS` AFTER INSERT ON `rol` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'rol', NEW.`rol_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'rol_id', NULL, NEW.`rol_id`),
(auditoria_last_inserted_id, 'rol_nombre', NULL, NEW.`rol_nombre`),
(auditoria_last_inserted_id, 'rol_descirpcion', NULL, NEW.`rol_descirpcion`),
(auditoria_last_inserted_id, 'rol_estado', NULL, NEW.`rol_estado`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zrol_AUPD` AFTER UPDATE ON `rol` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'rol', OLD.`rol_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'rol_id', OLD.`rol_id`, NEW.`rol_id`),
  (auditoria_last_inserted_id, 'rol_nombre', OLD.`rol_nombre`, NEW.`rol_nombre`),
  (auditoria_last_inserted_id, 'rol_descirpcion', OLD.`rol_descirpcion`, NEW.`rol_descirpcion`),
  (auditoria_last_inserted_id, 'rol_estado', OLD.`rol_estado`, NEW.`rol_estado`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_menu`
--

CREATE TABLE `rol_menu` (
  `rol_menu_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol_menu`
--

INSERT INTO `rol_menu` (`rol_menu_id`, `rol_id`, `menu_id`, `accion_usuario`, `accion_fecha`) VALUES
(304, 5, 19, 'usuario.gestion', '2017-11-07 09:32:37'),
(305, 5, 22, 'usuario.gestion', '2017-11-07 09:32:37'),
(306, 6, 24, 'usuario.gestion', '2017-11-07 09:32:49'),
(358, 7, 26, 'usuario.gestion', '2017-11-10 14:05:06'),
(386, 8, 27, 'usuario.gestion', '2017-11-10 17:16:57'),
(476, 11, 30, 'usuario.gestion', '2017-11-20 09:36:43'),
(477, 9, 28, 'usuario.gestion', '2017-11-20 09:36:59'),
(478, 9, 30, 'usuario.gestion', '2017-11-20 09:36:59'),
(479, 10, 30, 'usuario.gestion', '2017-11-20 09:37:11'),
(480, 10, 23, 'usuario.gestion', '2017-11-20 09:37:11'),
(642, 4, 20, 'usuario.gestion', '2018-02-15 23:53:40'),
(643, 4, 30, 'usuario.gestion', '2018-02-15 23:53:40'),
(644, 4, 11, 'usuario.gestion', '2018-02-15 23:53:40'),
(645, 4, 14, 'usuario.gestion', '2018-02-15 23:53:40'),
(646, 4, 16, 'usuario.gestion', '2018-02-15 23:53:40'),
(773, 13, 33, 'usuario.gestion', '2018-10-11 15:38:42'),
(774, 13, 4, 'usuario.gestion', '2018-10-11 15:38:42'),
(775, 13, 5, 'usuario.gestion', '2018-10-11 15:38:42'),
(776, 13, 6, 'usuario.gestion', '2018-10-11 15:38:42'),
(777, 13, 2, 'usuario.gestion', '2018-10-11 15:38:42'),
(778, 13, 18, 'usuario.gestion', '2018-10-11 15:38:42'),
(779, 13, 7, 'usuario.gestion', '2018-10-11 15:38:42'),
(780, 13, 11, 'usuario.gestion', '2018-10-11 15:38:42'),
(781, 13, 9, 'usuario.gestion', '2018-10-11 15:38:42'),
(782, 13, 10, 'usuario.gestion', '2018-10-11 15:38:42'),
(783, 13, 1, 'usuario.gestion', '2018-10-11 15:38:42'),
(784, 13, 32, 'usuario.gestion', '2018-10-11 15:38:42'),
(785, 13, 23, 'usuario.gestion', '2018-10-11 15:38:42'),
(786, 13, 14, 'usuario.gestion', '2018-10-11 15:38:42'),
(787, 13, 20, 'usuario.gestion', '2018-10-11 15:38:42'),
(788, 13, 8, 'usuario.gestion', '2018-10-11 15:38:42'),
(789, 13, 12, 'usuario.gestion', '2018-10-11 15:38:42'),
(790, 12, 21, 'usuario.gestion', '2018-10-11 15:39:42'),
(791, 12, 29, 'usuario.gestion', '2018-10-11 15:39:42'),
(792, 12, 22, 'usuario.gestion', '2018-10-11 15:39:42'),
(793, 12, 23, 'usuario.gestion', '2018-10-11 15:39:42'),
(794, 12, 12, 'usuario.gestion', '2018-10-11 15:39:42'),
(903, 2, 4, 'usuario.gestion', '2018-11-13 12:08:51'),
(904, 2, 21, 'usuario.gestion', '2018-11-13 12:08:51'),
(905, 2, 1, 'usuario.gestion', '2018-11-13 12:08:51'),
(1104, 14, 4, 'usuario.gestion', '2018-12-27 16:54:10'),
(1105, 14, 36, 'usuario.gestion', '2018-12-27 16:54:10'),
(1106, 14, 6, 'usuario.gestion', '2018-12-27 16:54:10'),
(1107, 14, 13, 'usuario.gestion', '2018-12-27 16:54:10'),
(1108, 14, 15, 'usuario.gestion', '2018-12-27 16:54:10'),
(1109, 14, 30, 'usuario.gestion', '2018-12-27 16:54:10'),
(1110, 14, 18, 'usuario.gestion', '2018-12-27 16:54:10'),
(1111, 14, 38, 'usuario.gestion', '2018-12-27 16:54:10'),
(1112, 14, 11, 'usuario.gestion', '2018-12-27 16:54:10'),
(1113, 14, 37, 'usuario.gestion', '2018-12-27 16:54:10'),
(1114, 14, 7, 'usuario.gestion', '2018-12-27 16:54:10'),
(1115, 14, 39, 'usuario.gestion', '2018-12-27 16:54:10'),
(1116, 14, 9, 'usuario.gestion', '2018-12-27 16:54:10'),
(1117, 14, 10, 'usuario.gestion', '2018-12-27 16:54:10'),
(1118, 14, 31, 'usuario.gestion', '2018-12-27 16:54:10'),
(1119, 14, 23, 'usuario.gestion', '2018-12-27 16:54:10'),
(1120, 14, 16, 'usuario.gestion', '2018-12-27 16:54:10'),
(1121, 14, 20, 'usuario.gestion', '2018-12-27 16:54:10'),
(1122, 14, 8, 'usuario.gestion', '2018-12-27 16:54:10'),
(1123, 14, 12, 'usuario.gestion', '2018-12-27 16:54:10'),
(1129, 1, 39, 'usuario.gestion', '2019-08-12 13:41:58'),
(1130, 1, 1, 'usuario.gestion', '2019-08-12 13:41:58');

--
-- Disparadores `rol_menu`
--
DELIMITER $$
CREATE TRIGGER `zrol_menu_ADEL` AFTER DELETE ON `rol_menu` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'rol_menu', OLD.`rol_menu_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'rol_menu_id', OLD.`rol_menu_id`, NULL ),
  (auditoria_last_inserted_id, 'rol_id', OLD.`rol_id`, NULL ),
  (auditoria_last_inserted_id, 'menu_id', OLD.`menu_id`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zrol_menu_AINS` AFTER INSERT ON `rol_menu` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'rol_menu', NEW.`rol_menu_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'rol_menu_id', NULL, NEW.`rol_menu_id`),
(auditoria_last_inserted_id, 'rol_id', NULL, NEW.`rol_id`),
(auditoria_last_inserted_id, 'menu_id', NULL, NEW.`menu_id`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zrol_menu_AUPD` AFTER UPDATE ON `rol_menu` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'rol_menu', OLD.`rol_menu_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'rol_menu_id', OLD.`rol_menu_id`, NEW.`rol_menu_id`),
  (auditoria_last_inserted_id, 'rol_id', OLD.`rol_id`, NEW.`rol_id`),
  (auditoria_last_inserted_id, 'menu_id', OLD.`menu_id`, NEW.`menu_id`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `servicio_id` int(11) NOT NULL,
  `servicio_detalle` varchar(45) DEFAULT NULL,
  `servicio_activo` int(1) DEFAULT '1',
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`servicio_id`, `servicio_detalle`, `servicio_activo`, `accion_usuario`, `accion_fecha`) VALUES
(1, 'Capital Trabajo', 1, 'usuario.gestion', '2019-03-29 14:34:14'),
(2, 'Pasivos de Ahorro', 1, 'usuario.gestion', '2018-12-24 11:31:33'),
(3, 'Refinanciamiento de Pyme', 1, 'usuario.gestion', '2018-12-24 11:32:10'),
(4, 'Crédito de Efectivo', 1, 'usuario.gestion', '2018-12-24 11:31:48'),
(5, 'Tarjeta de Crédito', 1, 'usuario.gestion', '2018-12-24 11:32:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_persona`
--

CREATE TABLE `tipo_persona` (
  `tipo_persona_id` int(11) NOT NULL,
  `categoria_persona_id` int(11) NOT NULL,
  `tipo_persona_nombre` varchar(45) DEFAULT NULL,
  `tipo_persona_vigente` int(1) DEFAULT '1',
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_persona`
--

INSERT INTO `tipo_persona` (`tipo_persona_id`, `categoria_persona_id`, `tipo_persona_nombre`, `tipo_persona_vigente`, `accion_usuario`, `accion_fecha`) VALUES
(0, 1, 'Sucursal', 0, 'usuario.gestion', '2017-07-18 00:00:00'),
(1, 1, 'Encontrada, contactada y verificada', 1, 'usuario.gestion', '2018-10-10 17:12:55'),
(2, 1, 'Encontrada, contactada sin verificacion', 1, 'usuario.gestion', '2018-10-10 17:13:01'),
(3, 1, 'Encontrada, no contactada', 1, 'usuario.gestion', '2018-10-10 17:13:06'),
(4, 1, 'No encontrada y con dirección existente', 1, 'usuario.gestion', '2018-10-10 17:13:12'),
(5, 1, 'No encontrada y sin dirección existente', 1, 'usuario.gestion', '2018-10-10 17:13:17');

--
-- Disparadores `tipo_persona`
--
DELIMITER $$
CREATE TRIGGER `ztipo_persona_ADEL` AFTER DELETE ON `tipo_persona` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'tipo_persona', OLD.`tipo_persona_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'tipo_persona_id', OLD.`tipo_persona_id`, NULL ),
  (auditoria_last_inserted_id, 'categoria_persona_id', OLD.`categoria_persona_id`, NULL ),
  (auditoria_last_inserted_id, 'tipo_persona_nombre', OLD.`tipo_persona_nombre`, NULL ),
  (auditoria_last_inserted_id, 'tipo_persona_vigente', OLD.`tipo_persona_vigente`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ztipo_persona_AINS` AFTER INSERT ON `tipo_persona` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'tipo_persona', NEW.`tipo_persona_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'tipo_persona_id', NULL, NEW.`tipo_persona_id`),
(auditoria_last_inserted_id, 'categoria_persona_id', NULL, NEW.`categoria_persona_id`),
(auditoria_last_inserted_id, 'tipo_persona_nombre', NULL, NEW.`tipo_persona_nombre`),
(auditoria_last_inserted_id, 'tipo_persona_vigente', NULL, NEW.`tipo_persona_vigente`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ztipo_persona_AUPD` AFTER UPDATE ON `tipo_persona` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'tipo_persona', OLD.`tipo_persona_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'tipo_persona_id', OLD.`tipo_persona_id`, NEW.`tipo_persona_id`),
  (auditoria_last_inserted_id, 'categoria_persona_id', OLD.`categoria_persona_id`, NEW.`categoria_persona_id`),
  (auditoria_last_inserted_id, 'tipo_persona_nombre', OLD.`tipo_persona_nombre`, NEW.`tipo_persona_nombre`),
  (auditoria_last_inserted_id, 'tipo_persona_vigente', OLD.`tipo_persona_vigente`, NEW.`tipo_persona_vigente`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `estructura_agencia_id` int(11) NOT NULL,
  `usuario_rol` int(11) NOT NULL,
  `usuario_user` varchar(20) NOT NULL,
  `usuario_pass` varchar(100) NOT NULL,
  `usuario_fecha_creacion` datetime NOT NULL,
  `usuario_fecha_ultimo_acceso` datetime NOT NULL,
  `usuario_fecha_ultimo_password` datetime NOT NULL,
  `usuario_password_reset` int(1) NOT NULL,
  `usuario_recupera_token` varchar(100) DEFAULT NULL,
  `usuario_recupera_solicitado` int(1) DEFAULT NULL,
  `usuario_nombres` varchar(100) NOT NULL,
  `usuario_app` varchar(50) NOT NULL,
  `usuario_apm` varchar(50) NOT NULL,
  `usuario_email` varchar(150) NOT NULL,
  `usuario_telefono` varchar(100) NOT NULL,
  `usuario_direccion` varchar(1000) NOT NULL,
  `accion_fecha` datetime NOT NULL,
  `accion_usuario` varchar(20) NOT NULL,
  `usuario_activo` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `estructura_agencia_id`, `usuario_rol`, `usuario_user`, `usuario_pass`, `usuario_fecha_creacion`, `usuario_fecha_ultimo_acceso`, `usuario_fecha_ultimo_password`, `usuario_password_reset`, `usuario_recupera_token`, `usuario_recupera_solicitado`, `usuario_nombres`, `usuario_app`, `usuario_apm`, `usuario_email`, `usuario_telefono`, `usuario_direccion`, `accion_fecha`, `accion_usuario`, `usuario_activo`) VALUES
(1, 4, 1, 'usuario.gestion', '7b2e9f54cdff413fcde01f330af6896c3cd7e6cd', '2017-04-14 00:00:00', '2019-08-12 13:53:34', '2019-08-12 10:39:53', 0, NULL, NULL, 'Joel', 'Aliaga', 'Duran', 'jraymondad@gmail.com', '24156847', 'C asd', '2019-08-12 13:30:05', 'usuario.gestion', 1),
(10, 1, 2, 'usuario.app', '7b2e9f54cdff413fcde01f330af6896c3cd7e6cd', '2017-07-14 11:27:31', '2019-07-11 11:16:02', '2019-08-12 10:02:15', 0, NULL, NULL, 'Maria Reneé', 'Romero', 'Lazarte', 'jraymondad@gmail.com', '65160294', 'C/ Calama 4444', '2018-12-31 10:02:15', 'usuario.app', 1);

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `zusuarios_ADEL` AFTER DELETE ON `usuarios` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'usuarios', OLD.`usuario_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'usuario_id', OLD.`usuario_id`, NULL ),
  (auditoria_last_inserted_id, 'estructura_agencia_id', OLD.`estructura_agencia_id`, NULL ),
  (auditoria_last_inserted_id, 'usuario_rol', OLD.`usuario_rol`, NULL ),
  (auditoria_last_inserted_id, 'usuario_user', OLD.`usuario_user`, NULL ),
  (auditoria_last_inserted_id, 'usuario_pass', OLD.`usuario_pass`, NULL ),
  (auditoria_last_inserted_id, 'usuario_fecha_creacion', OLD.`usuario_fecha_creacion`, NULL ),
  (auditoria_last_inserted_id, 'usuario_fecha_ultimo_acceso', OLD.`usuario_fecha_ultimo_acceso`, NULL ),
  (auditoria_last_inserted_id, 'usuario_fecha_ultimo_password', OLD.`usuario_fecha_ultimo_password`, NULL ),
  (auditoria_last_inserted_id, 'usuario_password_reset', OLD.`usuario_password_reset`, NULL ),
  (auditoria_last_inserted_id, 'usuario_recupera_token', OLD.`usuario_recupera_token`, NULL ),
  (auditoria_last_inserted_id, 'usuario_recupera_solicitado', OLD.`usuario_recupera_solicitado`, NULL ),
  (auditoria_last_inserted_id, 'usuario_nombres', OLD.`usuario_nombres`, NULL ),
  (auditoria_last_inserted_id, 'usuario_app', OLD.`usuario_app`, NULL ),
  (auditoria_last_inserted_id, 'usuario_apm', OLD.`usuario_apm`, NULL ),
  (auditoria_last_inserted_id, 'usuario_email', OLD.`usuario_email`, NULL ),
  (auditoria_last_inserted_id, 'usuario_telefono', OLD.`usuario_telefono`, NULL ),
  (auditoria_last_inserted_id, 'usuario_direccion', OLD.`usuario_direccion`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'usuario_activo', OLD.`usuario_activo`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zusuarios_AINS` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'usuarios', NEW.`usuario_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'usuario_id', NULL, NEW.`usuario_id`),
(auditoria_last_inserted_id, 'estructura_agencia_id', NULL, NEW.`estructura_agencia_id`),
(auditoria_last_inserted_id, 'usuario_rol', NULL, NEW.`usuario_rol`),
(auditoria_last_inserted_id, 'usuario_user', NULL, NEW.`usuario_user`),
(auditoria_last_inserted_id, 'usuario_pass', NULL, NEW.`usuario_pass`),
(auditoria_last_inserted_id, 'usuario_fecha_creacion', NULL, NEW.`usuario_fecha_creacion`),
(auditoria_last_inserted_id, 'usuario_fecha_ultimo_acceso', NULL, NEW.`usuario_fecha_ultimo_acceso`),
(auditoria_last_inserted_id, 'usuario_fecha_ultimo_password', NULL, NEW.`usuario_fecha_ultimo_password`),
(auditoria_last_inserted_id, 'usuario_password_reset', NULL, NEW.`usuario_password_reset`),
(auditoria_last_inserted_id, 'usuario_recupera_token', NULL, NEW.`usuario_recupera_token`),
(auditoria_last_inserted_id, 'usuario_recupera_solicitado', NULL, NEW.`usuario_recupera_solicitado`),
(auditoria_last_inserted_id, 'usuario_nombres', NULL, NEW.`usuario_nombres`),
(auditoria_last_inserted_id, 'usuario_app', NULL, NEW.`usuario_app`),
(auditoria_last_inserted_id, 'usuario_apm', NULL, NEW.`usuario_apm`),
(auditoria_last_inserted_id, 'usuario_email', NULL, NEW.`usuario_email`),
(auditoria_last_inserted_id, 'usuario_telefono', NULL, NEW.`usuario_telefono`),
(auditoria_last_inserted_id, 'usuario_direccion', NULL, NEW.`usuario_direccion`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'usuario_activo', NULL, NEW.`usuario_activo`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zusuarios_AUPD` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'usuarios', OLD.`usuario_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'usuario_id', OLD.`usuario_id`, NEW.`usuario_id`),
  (auditoria_last_inserted_id, 'estructura_agencia_id', OLD.`estructura_agencia_id`, NEW.`estructura_agencia_id`),
  (auditoria_last_inserted_id, 'usuario_rol', OLD.`usuario_rol`, NEW.`usuario_rol`),
  (auditoria_last_inserted_id, 'usuario_user', OLD.`usuario_user`, NEW.`usuario_user`),
  (auditoria_last_inserted_id, 'usuario_pass', OLD.`usuario_pass`, NEW.`usuario_pass`),
  (auditoria_last_inserted_id, 'usuario_fecha_creacion', OLD.`usuario_fecha_creacion`, NEW.`usuario_fecha_creacion`),
  (auditoria_last_inserted_id, 'usuario_fecha_ultimo_acceso', OLD.`usuario_fecha_ultimo_acceso`, NEW.`usuario_fecha_ultimo_acceso`),
  (auditoria_last_inserted_id, 'usuario_fecha_ultimo_password', OLD.`usuario_fecha_ultimo_password`, NEW.`usuario_fecha_ultimo_password`),
  (auditoria_last_inserted_id, 'usuario_password_reset', OLD.`usuario_password_reset`, NEW.`usuario_password_reset`),
  (auditoria_last_inserted_id, 'usuario_recupera_token', OLD.`usuario_recupera_token`, NEW.`usuario_recupera_token`),
  (auditoria_last_inserted_id, 'usuario_recupera_solicitado', OLD.`usuario_recupera_solicitado`, NEW.`usuario_recupera_solicitado`),
  (auditoria_last_inserted_id, 'usuario_nombres', OLD.`usuario_nombres`, NEW.`usuario_nombres`),
  (auditoria_last_inserted_id, 'usuario_app', OLD.`usuario_app`, NEW.`usuario_app`),
  (auditoria_last_inserted_id, 'usuario_apm', OLD.`usuario_apm`, NEW.`usuario_apm`),
  (auditoria_last_inserted_id, 'usuario_email', OLD.`usuario_email`, NEW.`usuario_email`),
  (auditoria_last_inserted_id, 'usuario_telefono', OLD.`usuario_telefono`, NEW.`usuario_telefono`),
  (auditoria_last_inserted_id, 'usuario_direccion', OLD.`usuario_direccion`, NEW.`usuario_direccion`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'usuario_activo', OLD.`usuario_activo`, NEW.`usuario_activo`);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_perfil`
--

CREATE TABLE `usuario_perfil` (
  `usuario_perfil_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `accion_usuario` varchar(20) DEFAULT NULL,
  `accion_fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario_perfil`
--

INSERT INTO `usuario_perfil` (`usuario_perfil_id`, `usuario_id`, `perfil_id`, `accion_usuario`, `accion_fecha`) VALUES
(392, 10, 9, 'usuario.gestion', '2017-12-04 16:21:30'),
(393, 10, 12, 'usuario.gestion', '2017-12-04 16:21:30'),
(394, 10, 10, 'usuario.gestion', '2017-12-04 16:21:30'),
(395, 10, 4, 'usuario.gestion', '2017-12-04 16:21:30'),
(407, 1, 7, 'usuario.gestion', '2018-01-24 12:07:10'),
(408, 1, 9, 'usuario.gestion', '2018-01-24 12:07:10'),
(409, 1, 12, 'usuario.gestion', '2018-01-24 12:07:10'),
(410, 1, 10, 'usuario.gestion', '2018-01-24 12:07:10'),
(411, 1, 8, 'usuario.gestion', '2018-01-24 12:07:10'),
(412, 1, 2, 'usuario.gestion', '2018-01-24 12:07:10'),
(413, 1, 3, 'usuario.gestion', '2018-01-24 12:07:10'),
(414, 1, 13, 'usuario.gestion', '2018-01-24 12:07:10'),
(415, 1, 5, 'usuario.gestion', '2018-01-24 12:07:10'),
(416, 1, 4, 'usuario.gestion', '2018-01-24 12:07:10'),
(417, 1, 11, 'usuario.gestion', '2018-01-24 12:07:10'),
(418, 1, 14, 'usuario.gestion', '2018-01-24 12:07:10'),
(419, 11, 9, 'usuario.gestion', '2018-10-11 15:36:52'),
(420, 11, 2, 'usuario.gestion', '2018-10-11 15:36:52'),
(427, 12, 9, 'usuario.gestion', '2018-10-11 16:48:15'),
(428, 12, 12, 'usuario.gestion', '2018-10-11 16:48:15'),
(429, 12, 10, 'usuario.gestion', '2018-10-11 16:48:15'),
(430, 12, 8, 'usuario.gestion', '2018-10-11 16:48:15'),
(431, 12, 5, 'usuario.gestion', '2018-10-11 16:48:15'),
(432, 12, 4, 'usuario.gestion', '2018-10-11 16:48:15');

--
-- Disparadores `usuario_perfil`
--
DELIMITER $$
CREATE TRIGGER `zusuario_perfil_ADEL` AFTER DELETE ON `usuario_perfil` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'usuario_perfil', OLD.`usuario_perfil_id`, 'DELETE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'usuario_perfil_id', OLD.`usuario_perfil_id`, NULL ),
  (auditoria_last_inserted_id, 'usuario_id', OLD.`usuario_id`, NULL ),
  (auditoria_last_inserted_id, 'perfil_id', OLD.`perfil_id`, NULL ),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NULL ),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NULL );

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zusuario_perfil_AINS` AFTER INSERT ON `usuario_perfil` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, NEW.accion_usuario ), 'usuario_perfil', NEW.`usuario_perfil_id`, 'INSERT'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
(auditoria_last_inserted_id, 'usuario_perfil_id', NULL, NEW.`usuario_perfil_id`),
(auditoria_last_inserted_id, 'usuario_id', NULL, NEW.`usuario_id`),
(auditoria_last_inserted_id, 'perfil_id', NULL, NEW.`perfil_id`),
(auditoria_last_inserted_id, 'accion_usuario', NULL, NEW.`accion_usuario`),
(auditoria_last_inserted_id, 'accion_fecha', NULL, NEW.`accion_fecha`);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `zusuario_perfil_AUPD` AFTER UPDATE ON `usuario_perfil` FOR EACH ROW BEGIN

DECLARE auditoria_last_inserted_id BIGINT(20);

INSERT IGNORE INTO auditoria (accion_usuario, table_name, pk1, action)  VALUE ( IFNULL( @auditoria_accion_usuario, OLD.accion_usuario ), 'usuario_perfil', OLD.`usuario_perfil_id`, 'UPDATE'); 

SET auditoria_last_inserted_id = LAST_INSERT_ID();

INSERT IGNORE INTO auditoria_meta (audit_id, col_name, old_value, new_value) VALUES 
  (auditoria_last_inserted_id, 'usuario_perfil_id', OLD.`usuario_perfil_id`, NEW.`usuario_perfil_id`),
  (auditoria_last_inserted_id, 'usuario_id', OLD.`usuario_id`, NEW.`usuario_id`),
  (auditoria_last_inserted_id, 'perfil_id', OLD.`perfil_id`, NEW.`perfil_id`),
  (auditoria_last_inserted_id, 'accion_usuario', OLD.`accion_usuario`, NEW.`accion_usuario`),
  (auditoria_last_inserted_id, 'accion_fecha', OLD.`accion_fecha`, NEW.`accion_fecha`);

END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `application_logs`
--
ALTER TABLE `application_logs`
  ADD PRIMARY KEY (`applicationlogid`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `pk_index` (`table_name`,`pk1`,`pk2`) USING BTREE;

--
-- Indices de la tabla `auditoria_acceso`
--
ALTER TABLE `auditoria_acceso`
  ADD PRIMARY KEY (`auditoria_id`);

--
-- Indices de la tabla `auditoria_meta`
--
ALTER TABLE `auditoria_meta`
  ADD PRIMARY KEY (`audit_meta_id`),
  ADD KEY `fk_auditoria_meta_auditoria1_idx` (`audit_id`);

--
-- Indices de la tabla `auditoria_movil`
--
ALTER TABLE `auditoria_movil`
  ADD PRIMARY KEY (`auditoria_movil_id`);

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`catalogo_id`);

--
-- Indices de la tabla `conf_credenciales`
--
ALTER TABLE `conf_credenciales`
  ADD PRIMARY KEY (`conf_id`);

--
-- Indices de la tabla `conf_general`
--
ALTER TABLE `conf_general`
  ADD PRIMARY KEY (`conf_general_id`);

--
-- Indices de la tabla `ejecutivo`
--
ALTER TABLE `ejecutivo`
  ADD PRIMARY KEY (`ejecutivo_id`),
  ADD KEY `fk_ejecutivo_usuarios1_idx` (`usuario_id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`empresa_id`),
  ADD KEY `fk_empresa_ejecutivo1_idx` (`ejecutivo_id`);

--
-- Indices de la tabla `estructura_agencia`
--
ALTER TABLE `estructura_agencia`
  ADD PRIMARY KEY (`estructura_agencia_id`),
  ADD KEY `fk_estructura_agencia_estructura_regional1_idx` (`estructura_regional_id`);

--
-- Indices de la tabla `estructura_entidad`
--
ALTER TABLE `estructura_entidad`
  ADD PRIMARY KEY (`estructura_entidad_id`);

--
-- Indices de la tabla `estructura_regional`
--
ALTER TABLE `estructura_regional`
  ADD PRIMARY KEY (`estructura_regional_id`),
  ADD KEY `fk_estructura_regional_estructura_entidad1_idx` (`estructura_entidad_id`);

--
-- Indices de la tabla `etapa`
--
ALTER TABLE `etapa`
  ADD PRIMARY KEY (`etapa_id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`perfil_id`);

--
-- Indices de la tabla `prospecto`
--
ALTER TABLE `prospecto`
  ADD PRIMARY KEY (`prospecto_id`),
  ADD KEY `fk_prospecto_tipo_persona1_idx` (`tipo_persona_id`),
  ADD KEY `fk_prospecto_empresa1_idx` (`empresa_id`),
  ADD KEY `fk_prospecto_ejecutivo1_idx` (`ejecutivo_id`),
  ADD KEY `fk_prospecto_campana1_idx` (`camp_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `rol_menu`
--
ALTER TABLE `rol_menu`
  ADD PRIMARY KEY (`rol_menu_id`),
  ADD KEY `fk_rol_menu_rol1_idx` (`rol_id`),
  ADD KEY `fk_rol_menu_menu1_idx` (`menu_id`);

--
-- Indices de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  ADD PRIMARY KEY (`tipo_persona_id`),
  ADD KEY `fk_tipo_persona_categoria_persona1_idx` (`categoria_persona_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `fk_usuarios_estructura_agencia1_idx` (`estructura_agencia_id`),
  ADD KEY `fk_usuarios_rol1_idx` (`usuario_rol`);

--
-- Indices de la tabla `usuario_perfil`
--
ALTER TABLE `usuario_perfil`
  ADD PRIMARY KEY (`usuario_perfil_id`),
  ADD KEY `fk_usuario_perfil_usuarios1_idx` (`usuario_id`),
  ADD KEY `fk_usuario_perfil_perfil1_idx` (`perfil_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `application_logs`
--
ALTER TABLE `application_logs`
  MODIFY `applicationlogid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `audit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;
--
-- AUTO_INCREMENT de la tabla `auditoria_acceso`
--
ALTER TABLE `auditoria_acceso`
  MODIFY `auditoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1039;
--
-- AUTO_INCREMENT de la tabla `auditoria_meta`
--
ALTER TABLE `auditoria_meta`
  MODIFY `audit_meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3022;
--
-- AUTO_INCREMENT de la tabla `auditoria_movil`
--
ALTER TABLE `auditoria_movil`
  MODIFY `auditoria_movil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=862;
--
-- AUTO_INCREMENT de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `catalogo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT de la tabla `ejecutivo`
--
ALTER TABLE `ejecutivo`
  MODIFY `ejecutivo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `empresa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;
--
-- AUTO_INCREMENT de la tabla `estructura_agencia`
--
ALTER TABLE `estructura_agencia`
  MODIFY `estructura_agencia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `estructura_entidad`
--
ALTER TABLE `estructura_entidad`
  MODIFY `estructura_entidad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `estructura_regional`
--
ALTER TABLE `estructura_regional`
  MODIFY `estructura_regional_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `etapa`
--
ALTER TABLE `etapa`
  MODIFY `etapa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `perfil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `prospecto`
--
ALTER TABLE `prospecto`
  MODIFY `prospecto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;
--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `rol_menu`
--
ALTER TABLE `rol_menu`
  MODIFY `rol_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1131;
--
-- AUTO_INCREMENT de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  MODIFY `tipo_persona_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT de la tabla `usuario_perfil`
--
ALTER TABLE `usuario_perfil`
  MODIFY `usuario_perfil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria_meta`
--
ALTER TABLE `auditoria_meta`
  ADD CONSTRAINT `fk_auditoria_meta_auditoria1` FOREIGN KEY (`audit_id`) REFERENCES `auditoria` (`audit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ejecutivo`
--
ALTER TABLE `ejecutivo`
  ADD CONSTRAINT `fk_ejecutivo_usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `fk_empresa_ejecutivo1` FOREIGN KEY (`ejecutivo_id`) REFERENCES `ejecutivo` (`ejecutivo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `estructura_agencia`
--
ALTER TABLE `estructura_agencia`
  ADD CONSTRAINT `fk_estructura_agencia_estructura_regional1` FOREIGN KEY (`estructura_regional_id`) REFERENCES `estructura_regional` (`estructura_regional_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `estructura_regional`
--
ALTER TABLE `estructura_regional`
  ADD CONSTRAINT `fk_estructura_regional_estructura_entidad1` FOREIGN KEY (`estructura_entidad_id`) REFERENCES `estructura_entidad` (`estructura_entidad_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prospecto`
--
ALTER TABLE `prospecto`
  ADD CONSTRAINT `fk_prospecto_ejecutivo1` FOREIGN KEY (`ejecutivo_id`) REFERENCES `ejecutivo` (`ejecutivo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prospecto_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`empresa_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prospecto_tipo_persona1` FOREIGN KEY (`tipo_persona_id`) REFERENCES `tipo_persona` (`tipo_persona_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `rol_menu`
--
ALTER TABLE `rol_menu`
  ADD CONSTRAINT `fk_rol_menu_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rol_menu_rol1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`rol_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  ADD CONSTRAINT `fk_tipo_persona_categoria_persona1` FOREIGN KEY (`categoria_persona_id`) REFERENCES `categoria_persona` (`categoria_persona_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_estructura_agencia1` FOREIGN KEY (`estructura_agencia_id`) REFERENCES `estructura_agencia` (`estructura_agencia_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_rol1` FOREIGN KEY (`usuario_rol`) REFERENCES `rol` (`rol_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_perfil`
--
ALTER TABLE `usuario_perfil`
  ADD CONSTRAINT `fk_usuario_perfil_perfil1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`perfil_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_perfil_usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
