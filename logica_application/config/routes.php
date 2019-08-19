<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// MENÚ PRINCIPAL

$route['Menu/Principal'] = "login/login_controller/CargarMenuPrincipal";
$route['Menu/Cambiar'] = "login/login_controller/CambiarMenu";

// ----------- FORMULARIOS DINÁMICOS INICIO -----------

$route['Formularios/Ver'] = "form_dinamico/form_controller/Formulario_Ver";

$route['Formularios/crear'] = "form_dinamico/form_controller/Formulario_Ver";

$route['Formularios/mostrar/(:num)'] = "form_dinamico/form_controller/mostrarFormulario/$1";
$route['Formularios/eliminar/(:num)'] = "form_dinamico/form_controller/Formulario_Ver";
$route['Formularios/guardar/(:num)'] = "form_dinamico/form_controller/Formulario_Ver";

// ----------- FORMULARIOS DINÁMICOS FIN -----------


// Campañas

$route['Campana/Ver'] = "campana/campana_controller/Campana_Ver";
$route['Campana/Registro'] = "campana/campana_controller/CampanaForm";
$route['Campana/Guardar'] = "campana/campana_controller/Campana_Guardar";
$route['Campana/Detalle'] = "campana/detalle_controller/CampanaDetalle";

// Importación Masiva

$route['Importacion/Ver'] = "importacion_masiva/importacion_controller/Importacion_Form";
$route['Importacion/Subir'] = "importacion_masiva/importacion_controller/Importacion_Subir";
$route['Importacion/Resultado'] = "importacion_masiva/importacion_controller/Importacion_Resultado";
$route['Importacion/Guardar'] = "importacion_masiva/importacion_controller/Importacion_Guardar";

// QR

$route['Qr/Externo/Registro'] = "qr_externo/externo_controller/SolicitudFormProspecto";
$route['Qr/Externo/Guardar'] = "qr_externo/externo_controller/Solicitud_Guardar";

$route['Qr/Externo/Reporte'] = "qr_externo/externo_controller/ReporteAsistenciaTabs";

$route['Qr/Externo/Dell'] = "qr_externo/externo_controller/ReporteAsistenciaDell";

$route['Qr/Externo/Initium'] = "qr_externo/externo_controller/ReporteAsistencia";

$route['Qr/Externo/EnvioManual'] = "qr_externo/externo_controller/EnvioManual";

// QR - khipu

$route['Qr/Externo/Return'] = "qr_externo/externo_controller/Solicitud_Return";
$route['Qr/Externo/Cancel'] = "qr_externo/externo_controller/Solicitud_Cancel";
$route['Qr/Externo/Notify'] = "qr_externo/externo_controller/Solicitud_Notify";

$route['Qr/Externo/Test'] = "qr_externo/externo_controller/test";


$route['Qr/Categoria/Ver'] = "qr_categoria/bandeja_controller/Bandeja_Ver";
$route['Qr/Registro/Reporte'] = "qr_categoria/bandeja_controller/PDF_Categoria";


// Control de Cambio 04/07/2018 - Nuevo Módulo GESTIÓN DE PROSPECTOS (DOCUMENTO) Y EMPRESA

$route['Registro/Ver'] = "gestion/gestion_controller/menu";
$route['Registro/Prospecto/Ver'] = "gestion/gestion_controller/ProspectoBandeja_Ver";
$route['Registro/Prospecto/Consolidar'] = "gestion/gestion_controller/ForzarConsolidarProspecto";
$route['Registro/Documento/Ver'] = "gestion/gestion_controller/DocumentosProspecto_Ver";
$route['Registro/Documento/Form'] = "gestion/gestion_controller/DocumentosProspecto_Form";
$route['Registro/Documento/Guardar'] = "gestion/gestion_controller/DocumentosProspecto_Guardar";

// --

$route['Registro/Empresa/Ver'] = "gestion/gestion_controller/EmpresaBandeja_Ver";
$route['Registro/Empresa/Form'] = "gestion/gestion_controller/DatosEmpresa_Form";
$route['Registro/Select/Cargar'] = "gestion/gestion_controller/PoblarListaCatalogo";
$route['Registro/Empresa/Guardar'] = "gestion/gestion_controller/DatosEmpresa_Guardar";

$route['Nuevo/Empresa/Form'] = "gestion/gestion_controller/DatosEmpresaNuevo_Form";
$route['Nuevo/Empresa/Guardar'] = "gestion/gestion_controller/DatosEmpresaNuevo_Guardar";


$route['Empresa/Zona/Ver'] = "gestion/gestion_controller/Empresa_Zona_Ver";
$route['Empresa/Zona/Mapa'] = "gestion/gestion_controller/Empresa_Zona_Mapa";
$route['Empresa/Zona/Guardar'] = "gestion/gestion_controller/Empresa_Zona_Guardar";

$route['Empresa/Geo/Ver'] = "gestion/gestion_controller/Empresa_Recibe_Geo";
$route['Empresa/Geo/Mapa'] = "gestion/gestion_controller/Empresa_Geo_Mapa";

$route['Empresa/Enviar/Geo'] = "gestion/gestion_controller/Empresa_Recibe_Geo";

// PAGINA MANTENIMIENTO

$route['Mantenimiento/Mantenimiento'] = "login/general_controller/PaginaMantenimiento";

// ENVIO CORREO SEGUNDO PLANO
$route['Correo/Enviar'] = "envio_correo/envio_correo_controller/EnvioCorreo";

// SERVICIO REST PARA LA APP
$route['Servicios/App'] = "api/servicios_app_controller/ServiciosAPP";

// USUARIOS

$route['Usuario/Listar'] = "usuarios/usuarios_controller/ListaUsuarios";
$route['Usuario/Editar'] = "usuarios/usuarios_controller/UsuarioForm";
$route['Usuario/Guardar'] = "usuarios/usuarios_controller/UsuarioForm_Guardar";
$route['Usuario/Detalle'] = "usuarios/detalle_controller/UsuarioDetalle";

$route['Usuario/Restablecer/PassPregunta'] = "usuarios/usuarios_controller/RestablecerPassPregunta";
$route['Usuario/Restablecer/Pass'] = "usuarios/usuarios_controller/RestablecerPass";

$route['Usuario/Cambiar/Pass'] = "usuarios/password_controller/CambiarPass_Ver";
$route['Usuario/Cambiar/Guardar'] = "usuarios/password_controller/CambiarPass_Guardar";

$route['Usuario/RenovarMenu'] = "usuarios/password_controller/RecargarMenu";

// CONFIGURACIÓN - CREDENCIALES

$route['Conf/Credenciales/Menu'] = "configuracion/conf_credenciales_controller/menu";
$route['Conf/Credenciales/Ver'] = "configuracion/conf_credenciales_controller/ConfForm_credenciales_Ver";
$route['Conf/Credenciales/Guardar'] = "configuracion/conf_credenciales_controller/ConfForm_credenciales_Guardar";

// CONFIGURACIÓN - CREDENCIALES

$route['Conf/Correo/Ver'] = "configuracion/conf_smtp_controller/ConfForm_correo_Ver";
$route['Conf/Correo/Guardar'] = "configuracion/conf_smtp_controller/ConfForm_correo_Guardar";

$route['Conf/Correo/Plantilla'] = "configuracion/conf_correo_controller/ConfForm_PlantillaRegistro_Ver";
$route['Conf/Correo/Plantilla/Cargar'] = "configuracion/conf_correo_controller/ConfForm_PlantillaRegistro_Cargar";
$route['Conf/Correo/Plantilla/Guardar'] = "configuracion/conf_correo_controller/ConfForm_PlantillaRegistro_Guardar";

// CONFIGURACIÓN - GENERALES

$route['Conf/General/Ver'] = "configuracion/conf_general_controller/ConfForm_general_Ver";
$route['Conf/General/Guardar'] = "configuracion/conf_general_controller/ConfForm_general_Guardar";

// AUDITORÍA

$route['Auditoria/Ver'] = "auditoria/auditoria_controller/Auditoria_Ver";
$route['Auditoria/Resultado'] = "auditoria/auditoria_controller/Auditoria_Resultado";
$route['Auditoria/Detalle'] = "auditoria/auditoria_controller/Auditoria_Detalle";
$route['Auditoria/Excel'] = "auditoria/auditoria_controller/Auditoria_Excel";

$route['Auditoria/Acceso/Ver'] = "auditoria/auditoria_controller/Auditoria_Acceso_Ver";
$route['Auditoria/Acceso/Resultado'] = "auditoria/auditoria_controller/Auditoria_Acceso_Resultado";
$route['Auditoria/Acceso/Excel'] = "auditoria/auditoria_controller/Auditoria_Acceso_Excel";

// CONFIGURACIÓN - CATÁLOGO

$route['Catalogo/Ver'] = "configuracion/conf_catalogo_controller/Catalogo_Ver";
$route['Catalogo/Registro'] = "configuracion/conf_catalogo_controller/CatalogoForm";
$route['Catalogo/Guardar'] = "configuracion/conf_catalogo_controller/CatalogoForm_Guardar";

// ESTRUCTURA AGENCIA, REGIONAL

$route['Agencia/Ver'] = "usuarios/usuarios_controller/Agencia_Ver";
$route['Agencia/Registro'] = "usuarios/usuarios_controller/AgenciaForm";
$route['Agencia/Guardar'] = "usuarios/usuarios_controller/Agencia_Guardar";

$route['Regional/Ver'] = "usuarios/usuarios_controller/Regional_Ver";
$route['Regional/Registro'] = "usuarios/usuarios_controller/RegionalForm";
$route['Regional/Guardar'] = "usuarios/usuarios_controller/Regional_Guardar";

$route['Rol/Ver'] = "configuracion/conf_credenciales_controller/Rol_Ver";
$route['Rol/Registro'] = "configuracion/conf_credenciales_controller/RolForm";
$route['Rol/Guardar'] = "configuracion/conf_credenciales_controller/Rol_Guardar";

// PERFILES

$route['Perfil/Ver'] = "configuracion/conf_credenciales_controller/Perfil_Ver";
$route['Perfil/Registro'] = "configuracion/conf_credenciales_controller/PerfilForm";
$route['Perfil/Guardar'] = "configuracion/conf_credenciales_controller/Perfil_Guardar";

$route['Perfil/Usuario/Ver'] = "configuracion/conf_perfiles_controller/ListaPerfilUsuarios";
$route['Perfil/Usuario/Registro'] = "configuracion/conf_perfiles_controller/PerfilUsuarioForm";
$route['Perfil/Usuario/Guardar'] = "configuracion/conf_perfiles_controller/PerfilUsuario_Guardar";

// DOCUMENTO

$route['Documento/Ver'] = "documento/documento_controller/Documento_Ver";
$route['Documento/Registro'] = "documento/documento_controller/DocumentoForm";
$route['Documento/Guardar'] = "documento/documento_controller/Documento_Guardar";

$route['Documento/Visualizar'] = "documento/ver_documento_controller/Documento_Visor";

// TIPO DE PERSONA Y LA RELACIÓN CON LOS DOCUMENTOS

$route['Persona/Ver'] = "persona/persona_controller/Persona_Ver";
$route['Persona/Registro'] = "persona/persona_controller/PersonaForm";
$route['Persona/Guardar'] = "persona/persona_controller/Persona_Guardar";

// ACTIVIDADES

$route['Actividades/Ver'] = "actividades/actividades_controller/Actividades_Ver";
$route['Actividades/Registro'] = "actividades/actividades_controller/ActividadesForm";
$route['Actividades/Guardar'] = "actividades/actividades_controller/Actividades_Guardar";

// SERVICIOS

$route['Servicio/Ver'] = "servicio/servicio_controller/Servicio_Ver";
$route['Servicio/Registro'] = "servicio/servicio_controller/ServicioForm";
$route['Servicio/Guardar'] = "servicio/servicio_controller/Servicio_Guardar";

// TAREAS DE MANTENIMIENTO DE CARTERA

$route['Tarea/Ver'] = "tarea/tarea_controller/Tarea_Ver";
$route['Tarea/Registro'] = "tarea/tarea_controller/TareaForm";
$route['Tarea/Guardar'] = "tarea/tarea_controller/Tarea_Guardar";

// EJECUTIVOS DE CUENTA

$route['Ejecutivo/Ver'] = "ejecutivos_cuenta/ejecutivo_controller/Ejecutivo_Ver";
$route['Ejecutivo/Registro'] = "ejecutivos_cuenta/ejecutivo_controller/EjecutivoForm";
$route['Ejecutivo/Guardar'] = "ejecutivos_cuenta/ejecutivo_controller/Ejecutivo_Guardar";

$route['Ejecutivo/Prospecto/Ver'] = "ejecutivos_cuenta/ejecutivo_controller/EjecutivoProspectos_Ver";
$route['Ejecutivo/Mantenimiento/Ver'] = "ejecutivos_cuenta/ejecutivo_controller/EjecutivoMantenimientos_Ver";

$route['Ejecutivo/Zona/Ver'] = "ejecutivos_cuenta/ejecutivo_controller/Ejecutivo_Zona_Ver";
$route['Ejecutivo/Zona/Mapa'] = "ejecutivos_cuenta/ejecutivo_controller/Ejecutivo_Zona_Mapa";
$route['Ejecutivo/Zona/Guardar'] = "ejecutivos_cuenta/ejecutivo_controller/Ejecutivo_Zona_Guardar";

$route['Ejecutivo/Mapa/Ver'] = "ejecutivos_cuenta/ejecutivo_controller/Ejecutivo_Mapa_Ver";
$route['Ejecutivo/Mapa/Mapa'] = "ejecutivos_cuenta/ejecutivo_controller/Ejecutivo_Mapa_Mapa";

$route['Seguimiento/Ver'] = "ejecutivos_cuenta/seguimiento_controller/Seguimiento_Ver";
$route['Seguimiento/Resultado'] = "ejecutivos_cuenta/seguimiento_controller/SeguimientoForm";
$route['Seguimiento/Resultado/Excel'] = "ejecutivos_cuenta/seguimiento_controller/SeguimientoForm_Excel";
$route['Seguimiento/Resultado/Mapa'] = "ejecutivos_cuenta/seguimiento_controller/Seguimiento_Mapa";

$route['Ejecutivo/Horario'] = "ejecutivos_cuenta/ejecutivo_controller/EjecutivoHorario_Ver";
$route['Ejecutivo/Horario/Ver'] = "ejecutivos_cuenta/ejecutivo_controller/EjecutivoHorario_Horario";
$route['Ejecutivo/Horario/Guardar'] = "ejecutivos_cuenta/ejecutivo_controller/EjecutivoHorario_Guardar";

$route['Ejecutivo/Lectura/Horario'] = "ejecutivos_cuenta/detalle_controller/EjecutivoHorario_Ver";
$route['Ejecutivo/Lectura/Horario/Ver'] = "ejecutivos_cuenta/detalle_controller/EjecutivoHorario_Horario";

$route['Ejecutivo/Metrica/Ver'] = "ejecutivos_cuenta/ejecutivo_controller/ConfForm_metrica_Ver";
$route['Ejecutivo/Metrica/Guardar'] = "ejecutivos_cuenta/ejecutivo_controller/ConfForm_metrica_Guardar";

$route['Bandeja/Ejecutivo/Ver'] = "bandeja_ejecutivo/bandeja_controller/Bandeja_Ver";

// EMPRESA

$route['Empresa/Detalle'] = "empresa/detalle_controller/EmpresaDetalle";

$route['Empresa/Registro'] = "empresa/empresa_controller/Empresa_Ver";
$route['Empresa/Verificar/NIT'] = "empresa/empresa_controller/VerificarNIT";
$route['Empresa/Guardar'] = "empresa/empresa_controller/Empresa_Guardar";

// SOLICITUD DE AFILIACIÓN

$route['Solicitud/Afiliacion/Ver'] = "solicitud_prospecto/solicitud_controller/Solicitud_Ver";
$route['Solicitud/Afiliacion/Registro'] = "solicitud_prospecto/solicitud_controller/SolicitudForm";
$route['Solicitud/Afiliacion/Guardar'] = "solicitud_prospecto/solicitud_controller/Solicitud_Guardar";
$route['Solicitud/Afiliacion/Detalle'] = "solicitud_prospecto/solicitud_controller/SolicitudDetalle";
$route['Solicitud/Rechazar/Ver'] = "solicitud_prospecto/solicitud_controller/SolicitudRechazar";
$route['Solicitud/Rechazar/Guardar'] = "solicitud_prospecto/solicitud_controller/SolicitudRechazar_Guardar";

$route['Solicitud/Zona/Ver'] = "solicitud_prospecto/solicitud_controller/Solicitud_Zona_Ver";
$route['Solicitud/Zona/Mapa'] = "solicitud_prospecto/solicitud_controller/Solicitud_Zona_Mapa";
$route['Solicitud/Zona/Guardar'] = "solicitud_prospecto/solicitud_controller/Solicitud_Zona_Guardar";

$route['Solicitud/Aprobar/Registro'] = "solicitud_prospecto/solicitud_controller/AprobarForm";
$route['Solicitud/Aprobar/Guardar'] = "solicitud_prospecto/solicitud_controller/Aprobar_Guardar";
$route['Solicitud/Verificar/NIT'] = "solicitud_prospecto/solicitud_controller/VerificarNIT";

$route['Solicitud/Mapa/Ver'] = "solicitud_prospecto/solicitud_controller/Solicitud_Mapa_Ver";
$route['Solicitud/Mapa/Mapa'] = "solicitud_prospecto/solicitud_controller/Solicitud_Mapa_Mapa";

$route['Solicitud/Enviar/Documentos'] = "solicitud_prospecto/detalle_controller/SolicitudEnviarDocumentos";
$route['Solicitud/Enviar/Guardar'] = "solicitud_prospecto/detalle_controller/SolicitudEnviar_Guardar";

// SOLICITUDES

// Registro en la Web Interno
$route['Solicitud/Menu'] = "solicitud/interno_controller/menu";
$route['Solicitud/Interno/Prospecto'] = "solicitud/interno_controller/SolicitudFormProspecto";
$route['Solicitud/Interno/Mantenimiento'] = "solicitud/interno_controller/SolicitudFormMantenimiento";

$route['Interno/Prospecto/Guardar'] = "solicitud/interno_controller/Prospecto_Guardar";
$route['Interno/Mantenimiento/Guardar'] = "solicitud/interno_controller/Mantenimiento_Guardar";

// Registro en la Web Externo

$route['Solicitud/Externo/Prospecto'] = "solicitud/externo_controller/SolicitudFormProspecto";
$route['Solicitud/Externo/Zona'] = "solicitud/externo_controller/Solicitud_Zona_Ver";
$route['Solicitud/Externo/Mapa'] = "solicitud/externo_controller/Solicitud_Zona_Mapa";
$route['Externo/Zona/Guardar'] = "solicitud/externo_controller/Solicitud_Zona_Guardar";
$route['Externo/Prospecto/Guardar'] = "solicitud/externo_controller/Prospecto_Guardar";

$route['Solicitud/Externo/Mantenimiento'] = "solicitud/externo_controller/SolicitudFormMantenimiento";
$route['Externo/Mantenimiento/Guardar'] = "solicitud/externo_controller/Mantenimiento_Guardar";

$route['Externo/RecargarImagenCaptcha'] = "solicitud/externo_controller/Recargar_captcha";

// SOLICITUD DE MANTENIMIENTOS

$route['Solicitud/Mantenimiento/Ver'] = "solicitud_mantenimiento/solicitud_controller/Solicitud_Ver";
$route['Solicitud/Mantenimiento/Registro'] = "solicitud_mantenimiento/solicitud_controller/SolicitudForm";
$route['Solicitud/Mantenimiento/Guardar'] = "solicitud_mantenimiento/solicitud_controller/Solicitud_Guardar";
$route['Solicitud/Mantenimiento/Detalle'] = "solicitud_mantenimiento/solicitud_controller/SolicitudDetalle";
$route['Mantenimiento/Rechazar/Ver'] = "solicitud_mantenimiento/solicitud_controller/SolicitudRechazar";
$route['Mantenimiento/Rechazar/Guardar'] = "solicitud_mantenimiento/solicitud_controller/SolicitudRechazar_Guardar";

$route['Mantenimiento/Aprobar/Registro'] = "solicitud_mantenimiento/solicitud_controller/AprobarForm";
$route['Mantenimiento/Aprobar/Guardar'] = "solicitud_mantenimiento/solicitud_controller/Aprobar_Guardar";
$route['Mantenimiento/Verificar/NIT'] = "solicitud_mantenimiento/solicitud_controller/VerificarNIT";

// CONFIRMAR SOLICITUD DE VISITA

$route['Confirmar'] = "verificar/verificar_controller/Verificar_Guardar";

// REPORTES

$route['Reportes/Ver'] = "reportes/reportes_controller/Reportes_Ver";
$route['Reportes/Generar'] = "reportes/reportes_controller/Reportes_Generar";
$route['Reportes/AgregarFiltro'] = "reportes/reportes_controller/Reportes_Agregar_Filtro";
$route['Reportes/ValoresFiltro'] = "reportes/reportes_controller/Reportes_Valores_Filtro";

// CONSULTAS Y SEGUIMIENTO

$route['Consultas/Ver'] = "consultas/consultas_controller/Reportes_Ver";
$route['Consultas/Generar'] = "consultas/consultas_controller/Reportes_Generar";
$route['Consultas/AgregarFiltro/Prospecto'] = "consultas/consultas_controller/Reportes_Agregar_Filtro_Prospecto";
$route['Consultas/AgregarFiltro/Mantenimiento'] = "consultas/consultas_controller/Reportes_Agregar_Filtro_Mantenimiento";
$route['Consultas/ValoresFiltro'] = "consultas/consultas_controller/Reportes_Valores_Filtro";

// BANDEJAS

// -- Evaluación Riesgos Pre-Afiliación

$route['Bandeja/Preafiliacion/Ver'] = "bandeja_evaluacion_pre/bandeja_controller/Bandeja_Ver";
$route['Bandeja/Antecedentes/Form'] = "bandeja_evaluacion_pre/bandeja_controller/Antecedentes_Form";
$route['Bandeja/Antecedentes/Guardar'] = "bandeja_evaluacion_pre/bandeja_controller/Antecedentes_Guardar";

// -- Verificación Requisitos

$route['Bandeja/Verificacion/Ver'] = "bandeja_verificacion_requisitos/bandeja_controller/Bandeja_Ver";
$route['Bandeja/Verificacion/Form'] = "bandeja_verificacion_requisitos/bandeja_controller/Requisitos_Form";
$route['Bandeja/Verificacion/Guardar'] = "bandeja_verificacion_requisitos/bandeja_controller/Requisitos_Guardar";

$route['Bandeja/GenerarExc/Form'] = "bandeja_verificacion_requisitos/bandeja_controller/GenerarExcepcion_Form";
$route['Bandeja/GenerarExc/Guardar'] = "bandeja_verificacion_requisitos/bandeja_controller/GenerarExcepcion_Guardar";

$route['Bandeja/Reverificar/Ver'] = "bandeja_reverificar/bandeja_controller/Bandeja_Ver";
$route['Bandeja/Reverificar/Form'] = "bandeja_reverificar/bandeja_controller/Requisitos_Form";
$route['Bandeja/Reverificar/Guardar'] = "bandeja_reverificar/bandeja_controller/Requisitos_Guardar";

// -- Cumplimeiento

$route['Bandeja/Cumplimiento/Ver'] = "bandeja_cumplimiento/bandeja_controller/Bandeja_Ver";
$route['Bandeja/Cumplimiento/Form'] = "bandeja_cumplimiento/bandeja_controller/Cumplimiento_Form";
$route['Bandeja/Cumplimiento/Guardar'] = "bandeja_cumplimiento/bandeja_controller/Cumplimiento_Guardar";

$route['Cumplimiento/Form/Sociedad'] = "bandeja_cumplimiento/bandeja_controller/Sociedad_Form";
$route['Cumplimiento/Sociedad/Guardar'] = "bandeja_cumplimiento/bandeja_controller/Sociedad_Form_Guardar";
$route['Cumplimiento/FormLista/Adicionar'] = "bandeja_cumplimiento/bandeja_controller/Adicionar_Item_Array";
$route['Cumplimiento/FormLista/Quitar'] = "bandeja_cumplimiento/bandeja_controller/Quitar_Item_Array";
$route['Cumplimiento/FormLista/Ver'] = "bandeja_cumplimiento/bandeja_controller/Ver_Item_Array";
$route['Cumplimiento/Sociedad/PDF'] = "bandeja_cumplimiento/bandeja_controller/Sociedad_PDF";

$route['Cumplimiento/Form/Match'] = "bandeja_cumplimiento/bandeja_controller/Match_Form";
$route['Cumplimiento/Match/Guardar'] = "bandeja_cumplimiento/bandeja_controller/Match_Form_Guardar";
$route['Cumplimiento/Match/PDF'] = "bandeja_cumplimiento/bandeja_controller/Match_PDF";

// -- Legal

$route['Bandeja/Legal/Ver'] = "bandeja_legal/bandeja_controller/Bandeja_Ver";
$route['Bandeja/Legal/Form'] = "bandeja_legal/bandeja_controller/Legal_Form";
$route['Bandeja/Legal/Guardar'] = "bandeja_legal/bandeja_controller/Legal_Guardar";

$route['Legal/Evaluacion/Form'] = "bandeja_legal/bandeja_controller/Evaluacion_Form";
$route['Legal/Evaluacion/Guardar'] = "bandeja_legal/bandeja_controller/Evaluacion_Guardar";
$route['Evaluacion/Reporte'] = "bandeja_legal/bandeja_controller/Evaluacion_PDF";

// -- Aprobación

$route['Bandeja/Aprobacion/Ver'] = "bandeja_aprobacion/bandeja_controller/Bandeja_Ver";
$route['Bandeja/Aprobacion/Form'] = "bandeja_aprobacion/bandeja_controller/Aprobacion_Form";
$route['Bandeja/Aprobacion/Guardar'] = "bandeja_aprobacion/bandeja_controller/Aprobacion_Guardar";

// -- Excepción - Justifcar e Informar

$route['Excepcion/Justifica/Ver'] = "bandeja_excepcion_justifica/bandeja_controller/Bandeja_Ver";
$route['Excepcion/Justifica/Form'] = "bandeja_excepcion_justifica/bandeja_controller/Justifica_Form";
$route['Excepcion/Justifica/Guardar'] = "bandeja_excepcion_justifica/bandeja_controller/Justifica_Guardar";

// -- Excepción - Gestionar Excepción

$route['Excepcion/Gestion/Ver'] = "bandeja_excepcion_gestion/bandeja_controller/Bandeja_Ver";
$route['Excepcion/Gestion/Form'] = "bandeja_excepcion_gestion/bandeja_controller/Gestion_Form";
$route['Excepcion/Gestion/Guardar'] = "bandeja_excepcion_gestion/bandeja_controller/Gestion_Guardar";

// -- Excepción - Analizar Excepción

$route['Excepcion/Analisis/Ver'] = "bandeja_excepcion_analiza/bandeja_controller/Bandeja_Ver";
$route['Excepcion/Analisis/Form'] = "bandeja_excepcion_analiza/bandeja_controller/Analisis_Form";
$route['Excepcion/Analisis/Guardar'] = "bandeja_excepcion_analiza/bandeja_controller/Analisis_Guardar";

// -- Rechazo

$route['Bandeja/Rechazo/Ver'] = "bandeja_rechazo/bandeja_controller/Bandeja_Ver";
$route['Bandeja/Rechazo/Form'] = "bandeja_rechazo/bandeja_controller/Rechazo_Form";
$route['Bandeja/Rechazo/Guardar'] = "bandeja_rechazo/bandeja_controller/Rechazo_Guardar";

// -- Supervisor de Agencia

$route['Bandeja/Agencia/Ver'] = "bandeja_supervisor_agencia/bandeja_controller/Bandeja_Ver";

// PROSPECTOS

$route['Prospecto/Documento/Ver'] = "prospecto/detalle_controller/DocumentosProspectos_Ver";

$route['Prospecto/Documento/Historico'] = "prospecto/detalle_controller/DocumentosProspectoHistorico_Ver";

$route['Prospecto/Documento/Unico'] = "prospecto/detalle_controller/DocumentosProspectos_Ver_Unico";
$route['Prospecto/ObservaDoc/Ver'] = "prospecto/detalle_controller/ObservaDoc_Ver";
$route['Prospecto/ObservaDoc/Guardar'] = "prospecto/detalle_controller/ObservaDoc_Guardar";
$route['Prospecto/ObservaDoc/Remitir'] = "prospecto/detalle_controller/ObservaDoc_Remitir";

$route['Prospecto/ObservaProc/Ver'] = "prospecto/detalle_controller/ObservarDevolver_Form";
$route['Prospecto/ObservaProc/Guardar'] = "prospecto/detalle_controller/ObservarDevolver_Guardar";

$route['Prospecto/Detalle'] = "prospecto/detalle_controller/ProspectoDetalle";

$route['Prospecto/Historial'] = "prospecto/detalle_controller/Historial_Ver";
$route['Prospecto/Historial/Excepcion'] = "prospecto/detalle_controller/HistorialExcepcion_Ver";
$route['Prospecto/Seguimiento'] = "prospecto/detalle_controller/HistorialSeguimiento_Ver";

$route['Agente/Seguimiento'] = "prospecto/detalle_controller/SeguimientoAgente";

// MANTENIMIENTOS

$route['Mantenimiento/Detalle'] = "mantenimiento/detalle_controller/MantenimientoDetalle";

// FLUJO DE TRABAJO

$route['Flujo/Ver'] = "flujo/flujo_controller/Flujo_Ver";
$route['Flujo/Ver/Detalle'] = "flujo/flujo_controller/Flujo_VerDetalle";
$route['Flujo/Registro'] = "flujo/flujo_controller/FlujoForm";
$route['Flujo/Guardar'] = "flujo/flujo_controller/Flujo_Guardar";


// <editor-fold defaultstate="collapsed" desc="Documentacion">
/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */
// </editor-fold>
$route['default_controller'] = "estructura_pagina/Estructura_controller/Pagina_ContruccionPrincipal";
$route['Principal'] = "estructura_pagina/Estructura_controller/Pagina_ContruccionPrincipal";
// <editor-fold defaultstate="collapsed" desc="RUTAS DE FORMULARIO DE LOGIN">
$route['Login/Formulario'] = "login/login_controller/Formulario_Login";
$route['Login/Autenticacion'] = "login/login_controller/Formulario_Autenticacion";

$route['Login/RecargarImagenCaptcha'] = "login/login_controller/Recargar_captcha";

$route['Login/MenusPermisos'] = "login/login_controller/Menu_CargarInformacionUsuarioMenues";
$route['Login/CerrarLogin'] = "login/login_controller/Cerrar_Login";
$route['Login/FormularioNuevaCuenta'] = "login/login_controller/Formulario_CargarFormularioNuevaCuenta";
$route['Login/FormularioActivarCuenta'] = "login/login_controller/Formulario_CargarFormularioActivarTarjeta";
$route['Login/MenuPrincipal'] = "login/login_controller/Menu_PantallaPrincipal";
$route['Login/Comprobacion'] = "login/login_controller/Formulario_ComprobacionCuenta";
// </editor-fold>

//$route['default_controller'] = "welcome";
$route['404_override'] = '';