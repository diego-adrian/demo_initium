<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('CODIGO_IDENTIFICADOR_SISTEMA',1);


define('LETRAS_NUMEROS','LETRAS_NUMEROS');
define('LETRAS','LETRAS');
define('NUMEROS','NUMEROS');
define('FECHA','FECHA');
define('CUSTOM','CUSTOM');
define('TODO','TODO');
define('CAJATEXTO','CAJATEXTO');
define('CAJAFECHA','CAJAFECHA');
define('CAJATEXTAREA','CAJATEXTAREA');

// KHIPU
define('KHIPU_ID','117815');
define('KHIPU_SECRET','37d2fefc76d158ddc5bd265609f43fa0a0da399a');

// KRAKEN

define('KRAKEN_USER', 'q1b5t8');
define('KRAKEN_PASS', 'TBSHySwsHsWScKYXxoSD');
define('KRAKEN_URL', 'https://demo.kraken.bo:8443/api/');
define('KRAKEN_SUBJECT', 'Pago de Ejemplo');

// ICONOS MAPA
define('GEO_BCP','-16.539989, -68.077975');
define('GEO_ATC','-16.5020815,-68.1328654');
//define('GEO_ATC','auto');
define('MARCADOR_ZONA','../../html_public/imagenes/marcador_zona.png');
define('MARCADOR_SOLICITUD','../../html_public/imagenes/marcador_pequeno.png');
define('MARCADOR_PROSPECTO','../../html_public/imagenes/marcador_naranja.png');
define('MARCADOR_MANTENIMIENTO','../../html_public/imagenes/marcador_azul.png');
define('MARCADOR_LOGO','../../html_public/imagenes/logo_initium.png');

// SISTEMA

define('PAGINADO_TABLA',5);
define('RUTA_DOCUMENTOS','logica_application/document_n_files/documentos/');
define('RUTA_PROSPECTOS','logica_application/document_n_files/prospectos/');
define('RUTA_MANTENIMIENTOS','logica_application/document_n_files/mantenimientos/');

define('IMAGEN_COMERCIO', '<i class="fa fa-building-o" aria-hidden="true"></i> ');
define('IMAGEN_ESTABLECIMIENTO', '<i class="fa fa-home" aria-hidden="true"></i> ');

define('ROL_SUPERVISOR_AGENCIA', 4);
define('ROL_CUMPLIMIENTO', 5);
define('ROL_LEGAL', 6);

define('PERFIL_VER_DOCUMENTOS', 4);
define('PERFIL_OBSERVAR_DOCUMENTOS', 5);
define('PERFIL_DETALLE_USUARIO', 8);
define('PERFIL_DETALLE_EMPRESA', 9);
define('PERFIL_DETALLE_PROSPECTO', 10);
define('PERFIL_DETALLE_MANTENIMIENTO', 12);
define('PERFIL_CALENDARIO_EJECUTIVO', 11);
define('PERFIL_HISTORIAL_EXCEPCION', 13);
define('PERFIL_HISTORIAL', 3);
define('PERFIL_DEVOLVER_PROSPECTO', 2);
define('PERFIL_SEGUIMIENTO', 14);
define('PERFIL_CONSULTAS_TODO', 7);

define('PREFIJO_EJECUTIVO', 'ECTA_');
define('PREFIJO_PROSPECTO', 'LEAD_');
define('PREFIJO_MANTENIMIENTO', 'MAN_');

define('ETAPA_BACKOFFICE', 0);
define('ETAPA_APP', 1);
define('ETAPA_SUPERVISOR', 2);
define('ETAPA_CUMPLIMIENTO', 4);
define('ETAPA_LEGAL', 5);
define('ETAPA_RECHAZO', 15);

define('ETAPA_EXCEPCION_CUMPLIMIENTO', 7);
define('ETAPA_EXCEPCION_JUSTIFICA', 8);
define('ETAPA_EXCEPCION_GESTIONA', 9);
define('ETAPA_EXCEPCION_ANALIZA', 10);

// <editor-fold defaultstate="collapsed" desc=" DE CODEIGNITER">

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


// </editor-fold>











