<?php
    /**
     * Libreria de Lenguaje para los módulos del sistema
     * @brief LIBRERIA LENGUAJE
     * @author Joel Aliaga Duran
     * @date Nov 2016
     */   

$lang["NombreSistema"] = ":: Initium :: Ambiente de Desarrollo";
$lang["NombreSistema_corto"] = ":: Demo Initium ::";


// ----------- FORMULARIOS DINÁMICOS INICIO -----------

$lang["FormularioDinamicoTitulo"] = "Formularios Dinámicos";
$lang["FormularioDinamicoSubtitulo"] = "Explicación detallada pero puntual de las acciones que el usuario puede realizar. Este texto esta en archivo de lenguaje";

$lang["FormularioDinamicoNuevo"] = "Nuevo Formulario";
$lang["FormularioDinamicoNombre"] = "Nombre";
$lang["FormularioDinamicoDescripcion"] = "Descripcion";
$lang["FormularioDinamicoPublicado"] = "Publicado";

$lang["conf_formulario_insertar"] = "Guardar el nuevo formulario creado";

// ----------- FORMULARIOS DINÁMICOS FIN -----------


// MENSAJES AL USUARIO

$lang["Self-XSS"] = "Este apartado es sólo para desarrolladores. Por favor no ingrese o haga correr ningún código aquí, cualquier intento de Self-XSS en el '" . $lang["NombreSistema"] . "' será pasible a las acciones legales pertinentes.";

$lang["NoAutorizado"] = "<br />ACCESO DENEGADO - El acceso no autorizado será pasible a las acciones legales pertinentes.<br /><br />";

$lang["NoAutorizadoPerfil"] = "No tiene los permisos requeridos para ver la información. Si lo requiere comuníquese con el Administrador.";

$lang["RequiereRenovarPass"] = "<i> HAS SUPERADO EL TIEMPO MÁXIMO DE VALIDEZ DE TU CONTRASEÑA. DEBES RENOVARLA PARA PODER CONTINUAR </i>";

$lang["UsuarioIncorrecto"] = "El nombre de usuario elegido ya está en uso. Por favor elija otro.";
$lang["UsuarioError"] = "No puede utilizar el nombre de usuario elegido, por favor defina uno válido.";
$lang["UsuarioError_corto"] = "El nombre de usuario elegido es muy corto, por favor defina uno válido.";

$lang["PasswordAnterior"] = "La contraseña actual no es correcta.";

$lang["PasswordNoCoincide"] = "La contraseña repetida no coincide.";

$lang["PasswordRepetido"] = "La nueva contraseña no puede ser igual a la actual.";

$lang["PasswordNoRenueva"] = "No puede renovar su contraseña ahora. La duración mínima de la contraseña es de ";

$lang["PasswordNoAceptado"] = "El nombre de usuario no es correcto, por favor elija otro.";

$lang["FormularioIncompleto"] = "Por favor debe completar todos los campos.";

$lang["FormularioNoDetalle"] = "Debe registrar la Observación o Justificación.";

$lang["FormularioNoPreguntas"] = "Para continuar debe responder afirmativamente a los requisitos indicados.";

$lang["FormularioRegistroExiste"] = "Ya existe un registro con la información proporcionada, por favor revise el formulario.";

$lang["FormularioNoEjecutivo"] = "El usuario seleccionado, ya está asignado como Agente, seleccione otro, o si requiere asignar Leads/Mantenimientos, puede \"Transferir Cuentas\"";

$lang["CamposObligatorios"] = "Los campos obligatorios deben estar completos y con el tipo de dato correcto.";

$lang["CamposRequeridos"] = "Porfavor complete la info con el tipo de dato correcto.";

$lang["CamposCorreo"] = "El Correo Electrónico no es correcto.";

$lang["FormularioNoGeo"] = "Aún no definió la geolocalización.";

$lang["FormularioNIT"] = "No se puede registrar el Comercio, el NIT ya se encuentra registrado.";

$lang["FormularioNo_NIT"] = "No existe un Comercio Afiliado con el NIT indicado.";

$lang["FormularioNIT_revision"] = "La empresa con el NIT indicado no está afiliada, se encuentra en revisión o como prospecto.";

$lang["FormularioSinOpciones"] = "No seleccionó ninguna opción para procesar la solicitud.";

$lang["FormularioDocumentoError"] = "El documento no fue digitalizado o es incorrecto.";

$lang["FormularioNoEnviadoPre"] = "Antes de consolidar, debe enviar la Documentación del prospecto a Cumplimiento.";

$lang["FormularioYaEnviadoPre"] = "El Prospecto ya fue remitido a Cumplimiento.";

$lang["FormularioNoAprobadoPre"] = "El prospecto aún se encuentra en evaluación de pre- Verificación.";

$lang["FormularioNoRequiereCumplimiento"] = "El tipo de empresa (Establecimiento) no requiere ser remitido a cumplimiento.";

$lang["FormularioYaConsolidado"] = "El prospecto está consolidado, no se puede realizar modificaciones.";

$lang["FormularioNoNotificacion"] = "No se pudo notificar a la instancia respectiva, porfavor vuelva a intentarlo. Si este mensaje persiste comuníquese con el administrador.";

$lang["FormularioNoEnvio"] = "No se envió los documentos a la Empresa Aceptante, por favor verifique que la dirección de correo de la Empresa Aceptante y los documentos solicitados sean correctos y vuelva a intentarlo.";

$lang["FormularioYaCompletado"] = "El mantenimiento ya fue completado, no se puede realizar modificaciones.";

$lang["FormularioNoEncontroNIT"] = "No se encontraron resultados, verifique que el NIT sea correcto. Si la empresa está registrada en el CORE, solicite al administrador que la registre en el sistema y que se le asigne a usted.";

$lang["FormularioYaEntregado"] = "El servicio ya se marcó como entregado, no puede realizar la acción solicitada.";

$lang["FormularioFechas"] = "Las fechas están incorrectas.";

$lang["FormularioFiltros"] = "Debe seleccionar al menos un criterio.";

$lang["FormularioLongInvalido"] = "Por favor debe establecer una longitud mayor a 0.";

$lang["FormularioLongInvalido2"] = "La longitud mínima no puede ser mayor a la longitud máxima.";

$lang["FormularioTiempoInvalido"] = "Por favor debe establecer un tiempo de bloqueo mayor a 0.";

$lang["CorreoFallo"] = "Paso algo al enviar el correo electrónico. Por favor revise su configuración.";

$lang["FormularioNoFile"] = "No seleccionó ningún documento para subir al sistema o no es correcto. Por favor vuelva a intentarlo.";

$lang["MenuPrincipal"] = "Menú Principal";

$lang["exportar"] = "EXPORTAR";

$lang["IncompletoApp"] = "No se realizó la operación.";

// AYUDA EN PANTALLA

$lang["Ayuda_password"] = "Debe tener mínimamente";

$lang["Ayuda_usuario_activo"] = "Si el usuario no está activado, no podrá acceder al sistema.";

$lang["Ayuda_variables_correo"] = "Puede usar las siguientes variables en la plantilla de correo. Sólo copie y pegue la variable donde requiera que aparezca. Considere que no todas las variables aplican para todas plantillas.";

$lang["Ayuda_categoria_catalogo"] = "TPS: Tipo de Sociedad<br />RUB: Rubro<br />PEC: Perfil Comercial<br />MCC: MCC<br />MCO: Medio de Contacto<br />DEP: Departamento<br />CIU: Municipio/Ciudad<br />ZON: Zona/Localidad<br />TPC: Tipo de Calle";

$lang["MostrarOcultar"] = " <i class='fa fa-eye' aria-hidden='true'></i> Mostrar/Ocultar";

// PREGUNTA CONFIRMACIÓN

$lang["PreguntaTitulo"] = "VAMOS A HACER ESTO:";
$lang["PreguntaContinuar"] = "¿ESTAS SEGURO? ";
$lang["BotonAceptar"] = "PROCEDER";
$lang["BotonAceptar_enviar"] = " <i class='fa fa-handshake-o' aria-hidden='true'></i> Enviar mi Solicitud Ahora!";
$lang["BotonCancelar"] = "VOLVER";
$lang["BotonSalir"] = "CANCELAR";

$lang["BotonContinuar"] = "CONTINUAR";

$lang["BotonVolver1"] = "<== VOLVER AL PASO 1";
$lang["BotonVolver2"] = "<== VOLVER AL PASO 2";

$lang["BotonContinuar1"] = "CONTINUAR AL PASO 2 ==>";
$lang["BotonContinuar2"] = "CONTINUAR AL PASO 3 ==>";

$lang["Correcto"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> La Acción Solicitada se Efectuó Correctamente";

// MENU PRINCIPAL

$lang["CreditoMoneda"] = "Mensaje(s)";

$lang["CambiarPass"] = "Renovar mi Contraseña";

// TEXTO DE LOS BOTÓNES / OPCIONES

$lang["TablaOpciones"] = "Opciones";
$lang["TablaOpciones_asignar_perfil"] = "Asignar <br /> Perfil";
$lang["TablaOpciones_Editar"] = "Editar <br />Registro";
$lang["TablaOpciones_Detalle"] = "Ver <br />Detalle";
$lang["TablaOpciones_NuevoUsuario"] = "Registrar <br /> Usuario";
$lang["TablaOpciones_Seleccionar"] = "Seleccionar";
$lang["TablaOpciones_CargarPlantilla"] = "Cargar Plantilla";

$lang["TablaOpciones_Restablecer"] = "Restablecer Contraseña";

$lang["TablaOpciones_CampanaNumerosNuevo"] = "(+) Nuevo";
$lang["TablaOpciones_CampanaNumerosQuitar"] = "Quitar";
$lang["TablaOpciones_ExportaExcel"] = "EXPORTAR A EXCEL";
$lang["TablaOpciones_ExportaPDF"] = "EXPORTAR A PDF";

$lang["TablaOpciones_VerDocumento"] = "Ver <br /> Documento";
$lang["TablaOpciones_SubirDocumento"] = "Cargar Documento PDF";

$lang["TablaOpciones_Rechazar"] = "Rechazar<br />Solicitud";
$lang["TablaOpciones_actualizar_data"] = "Actualizar<br />Información";

$lang["TablaOpciones_aceptar_solicitud"] = "Aprobar<br />Solicitud";

$lang["TablaNoRegistros"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> Aún No se Registró Información </div>";

$lang["TablaNoRegistrosMinimo"] = "<i class='fa fa-meh-o' aria-hidden='true'></i> Sin Registros";

$lang["TablaNoResultados"] = "NO SE ENCONTRARON REGISTROS";

$lang["TablaNoObservaciones"] = "<div class='PreguntaConfirmar'> <i class='fa fa-smile-o' aria-hidden='true'></i> ¡Hooray! No hay Observaciones Registradas </div>";

$lang["TablaNoPendientes"] = "<div class='PreguntaConfirmar'> <i class='fa fa-smile-o' aria-hidden='true'></i> ¡Hooray! Todo al Día por Aquí </div>";

$lang["TablaNoResultadosBusqueda"] = "No se encontraron resultados con los criterios enviados. Por favor intente con otros criterios.";

$lang["TablaNoResultadosReporte"] = "<div class='mensajeBD'> <br /> <i class='fa fa-meh-o' aria-hidden='true'></i> No se encontraron resultados con los criterios indicados <br /><br /></div>";

$lang["TablaOpciones_CampanaEstado1"] = "En Espera";
$lang["TablaOpciones_CampanaEstado2"] = "Pendiente";
$lang["TablaOpciones_CampanaEstado3"] = "Completado";


// IMPORTACIÓN MASIVA DE LEADS

$lang["ImportacionFormTitulo"] = "Cargado masivo de Leads";
$lang["ImportacionFormSubtitulo"] = "En este apartado podrá seleccionar un archivo en formato .XLS (Excel) con el formato ya establecido, para proceder con la subida masiva de los registros y asignación a los agentes respectivos.";
$lang["ImportacionFormDoc"] = "Proceda a cargar el archivo";
$lang["ImportacionFormDocAyuda"] = "Evite que el archivo contenga pestañas adicionales en el archivo que causen mayor tamaño de subida. Recuerde mantener el formato del archivo de subida";
$lang["TablaOpciones_SubirExcel"] = "Cargar Documento Excel";

$lang["import_agente"] = "Agente";
$lang["import_campana"] = "Campaña";
$lang["import_idc"] = "IDC";
$lang["import_nombre_cliente"] = "Nombre Cliente";
$lang["import_empresa"] = "Empresa";
$lang["import_ingreso"] = "Ingreso";
$lang["import_direccion"] = "Dirección";
$lang["import_direccion_geo"] = "Geolocalización del Lead";
$lang["import_telefono"] = "Teléfono";
$lang["import_celular"] = "Celular";
$lang["import_correo"] = "Email";
$lang["import_matricula"] = "Matrícula Asignada";
$lang["import_tipo_lead"] = "Tipo Registro";
$lang["import_matricula_corto"] = "Matrícula";

$lang["lead_primer_contacto"] = "Fecha 1° Contacto";

$lang["lead_monto_aprobacion"] = "Monto Aprobación";
$lang["lead_monto_desembolso"] = "Monto Desembolso";
$lang["prospecto_fecha_desembolso"] = "Fecha Desembolso";

$lang["lead_seguimiento_agente"] = "<i class='fa fa-signal' aria-hidden='true'></i> Detalle Avance Agente";

$lang["ImportacionResultadoTitulo"] = "Resultado de la Importación: ";
$lang["ImportacionResultadoSubtitulo"] = "En este apartado podrá verificar el resultado del cargado masivo, y si corresponde la correción del formato. Estos registros, en este paso, sólo están guardados temporalmente, sólo se guardarán en la base de datos una vez que se complete el proceso.";

$lang["import_verificar"] = "IMPORTAR Y VERIFICAR";
$lang["import_guardar"] = "GUARDAR REGISTROS";

$lang["import_titulo_verificar"] = "<i class='fa fa-binoculars' aria-hidden='true'></i> Puede verificar los registros a ser guardados en la siguiente matriz:";

$lang["import_titulo_error"] = " Revise, subsane el archivo de cargado y  vuelva a intentarlo";

$lang["aprobar_importar_Pregunta"] = "Proceder a guardar los registros importados y asignarlos a los agentes indicados de acuerdo a la matrícula.<br /><br />Esta acción podría demorar dependiendo de la cantidad de registros.";

$lang["lead_seg_estado_operacion_titulo"] = "<strong>A continuación se muestra el Resumen de las Operaciones según el Estado Registrado de las Campañas del Agente:</strong>";



// GESTIÓN DE CAMPAÑAS

$lang["CampanaTitulo"] = "Gestión de Campañas";
$lang["CampanaSubtitulo"] = "En este apartado podrá gestionar las campañas que contendrán los leads de los agentes.";

$lang["CampanaFormTitulo"] = "Gestión de Campañas";
$lang["CampanaFormSubtitulo"] = "En este apartado podrá gestionar las campañas que contendrán los leads de los agentes.";

$lang["campana_tipo"] = "Tipo";
$lang["campana_nombre"] = "Nombre";
$lang["campana_desc"] = "Descripción";
$lang["campana_plazo"] = "Plazo";
$lang["campana_monto_oferta"] = "Monto Oferta";
$lang["campana_tasa"] = "Tasa";
$lang["campana_fecha_inicio"] = "Fecha Inicio";
$lang["campana_servicios"] = "Productos Asociados";

$lang["campana_pregunta"] = "Actualizar la información de la Estructura. Los datos modificados de la Campaña modificarán los resultados y reportes generados.";

$lang["campana_nombre_error"] = "El nombre de la campaña ya está en uso, por favor elija otro.";

// REGISTRO WEB

$lang["RegistroWebTitulo"] = "Registro de Solicitudes de Visita";
$lang["RegistroWebSubtitulo"] = "En este apartado podrá registrar las solicitudes de Nuevos Registros o Tareas";

$lang["RegistroWeb_afiliacion"] = "Cliente Nuevo<br /> Registro";
$lang["RegistroWeb_mantenimiento"] = "Cliente Registrado <br /> Contácta a tu agente";

$lang["RegistroWeb_menu"] = "Registro de Solicitudes de Visita <i class='fa fa-handshake-o' aria-hidden='true'></i>";

// VALORES CATÁLOGOS

$lang["Catalogo_activo1"] = "No Activo";
$lang["Catalogo_activo2"] = "Activo";

$lang["Catalogo_si"] = "Si";
$lang["Catalogo_no"] = "No";

$lang["Catalogo_no_corresponde"] = "No Corresponde";

// USUARIOS

$lang["DetalleRegistroTitulo"] = " <i class='fa fa-commenting-o' aria-hidden='true'></i> Detalle del Registro";

$lang["UsuarioTitulo"] = "Administrar Usuarios";
$lang["UsuarioSubtitulo"] = "En este apartado podrá gestionar a los usuarios del sistema, así como la estructura organizacional. Por favor complete los campos del formulario, todos los campos son obligatorios.";

$lang["Usuario_user"] = "Matrícula";
$lang["Usuario_nombre"] = "Nombre";
$lang["Usuario_app"] = "Paterno";
$lang["Usuario_apm"] = "Materno";
$lang["Usuario_email"] = "Email";
$lang["Usuario_telefono"] = "Teléfono";
$lang["Usuario_direccion"] = "Dirección";
$lang["Usuario_rol"] = "Rol";
$lang["Usuario_perfil"] = "Perfil";
$lang["Usuario_activo"] = "¿Activo?";
$lang["Usuario_fecha_creacion"] = "Fecha Registro";
$lang["Usuario_fecha_acceso"] = "Fecha Último Ingreso";
$lang["Usuario_fecha_password"] = "Fecha Última Contraseña";


$lang["Usuario_pass1"] = "Contraseña Actual";
$lang["Usuario_pass2"] = "Ingrese su Nueva Contraseña";
$lang["Usuario_pass3"] = "Repita su Contraseña";

$lang["UsuarioPregunta"] = "Registrar Información del Usuario";

// CAMBIAR CONTRASEÑA

$lang["PassTitulo"] = "Renovar mi Contraseña";
$lang["PassSubtitulo"] = "Ingrese su contraseña anterior y su nueva contraseña.";
$lang["PassPregunta"] = "Restablecer la contraseña del usuario seleccionado a la definida por defecto.";

// CONFIGRUACIÓN - CREDENCIALES

$lang["conf_credmenu_Titulo"] = "Configuración de Credenciales y Gestión de Roles";
$lang["conf_credmenu_Subtitulo"] = "Por favor seleccione la opción requerida continuar.";

$lang["conf_credenciales_Titulo"] = "Configuración de Credenciales";
$lang["conf_credenciales_Subtitulo"] = "Para configurar los parámetros de las credenciales de usuario, por favor complete todos los campos del formulario.";

$lang["conf_credenciales_long_min"] = "Longitud Mínima";
$lang["conf_credenciales_long_max"] = "Longitud Máxima";
$lang["conf_credenciales_req_upper"] = "Requiere al menos una Mayúscula";
$lang["conf_credenciales_req_num"] = "Requiere al menos un Número";
$lang["conf_credenciales_req_esp"] = "Requiere al menos un Caractér Especial";
$lang["conf_credenciales_duracion_min"] = "Duración Mínima de la Contraseña (Días)";
$lang["conf_credenciales_duracion_max"] = "Duración Máxima de la Contraseña (Días)";
$lang["conf_credenciales_tiempo_bloqueo"] = "Tiempo de Bloqueo (Minutos)";
$lang["conf_credenciales_defecto"] = "Contraseña para Restablecimiento";
$lang["conf_ejecutivo_ic"] = "Índice de Cumplimiento para los Verificadores ";

$lang["conf_credenciales_Pregunta"] = "Actualizar la configuración de las credenciales.";

// CONFIGRUACIÓN - ENVÍO DE CORREO

$lang["conf_correo_Titulo"] = "Configuración de Envío de Correo";
$lang["conf_correo_Subtitulo"] = "Por favor complete todos los campos del formulario. Debe verificar que la configuración proporcionada sea aceptada por la configuración de su Firewall y/o políticas de seguridad de su red.";

$lang["conf_correo_protocol"] = "Protocolo";
$lang["conf_correo_smtp_host"] = "Nombre Host";
$lang["conf_correo_smtp_port"] = "Puerto";
$lang["conf_correo_smtp_user"] = "Usuario Correo";
$lang["conf_correo_smtp_pass"] = "Contraseña Correo";
$lang["conf_correo_mailtype"] = "Tipo Correo";
$lang["conf_correo_charset"] = "Codificación de Caractéres";

$lang["conf_correo_Pregunta"] = "Actualizar la configuración del envío de correo.";

// CONFIGRUACIÓN - PLANTILLA DE CORREOS

$lang["conf_plantilla_correo_Titulo"] = "Plantillas de Correo";
$lang["conf_plantilla_correo_Subtitulo"] = "En este apartado se listarán todas las plantillas disponibles del sistema, para editar el título y contenido de cada plantilla, haga clic en la plantilla que requiera editar. Puede utilizar el editor HTML incorporado  para los colores estilos de la plantilla, y al estar conforme con el resultado haga clic en \"Aceptar\"";

$lang["conf_plantilla_nombre"] = "Nombre corto de la Plantilla";
$lang["conf_plantilla_titulo_correo"] = "Título del Correo a Enviar";
$lang["conf_plantilla_variables_correo"] = "Variables disponibles";
$lang["conf_plantilla_variables_correo_def"] = "{nombre_sistema} {nombre_corto} {destinatario_nombre} {destinatario_correo} {titulo_correo} {emisor_nombre} {link_verificacion_solicitud} {codigo_prospecto} {codigo_mantenimiento} {list_tareas_mant} {empresa_nombre} {empresa_categoria} {ejecutivo_asignado_nombre} {ejecutivo_asignado_contacto} {fecha_visita} {fecha_evento_googlecalendar}";

$lang["correo_calendario_titulo"] = "Visita de mi Ejecutivo de Cuentas de ATC";
$lang["correo_calendario_afiliacion"] = " - Verificación";
$lang["correo_calendario_mantenimiento"] = " - Tareas de Mantenimiento de Cartera";


// CONFIGURACIÓN GENERALES

$lang["conf_general_Titulo"] = "Configuración General del Sistema";
$lang["conf_general_Subtitulo"] = "En este apartado podrá gestionar los parámetros generales del sistema, así como el manejo del calendario y el horario y días de atención. Es <u>muy importante</u> que establezca estos parámetros con la información real de la entidad, debido a que la lógica del negocio se basará en los tiempos indicados.";

$lang["conf_general_key_google"] = "Key de Google para Mapas y Calendario";
$lang["conf_horario_feriado"] = "¿Mostrar Días Festivos en Calendario?";
$lang["conf_horario_laboral"] = "¿Restringir Horario de Trabajo en Calendario?";

$lang["conf_atencion_desde1"] = "Turno Mañana desde";
$lang["conf_atencion_hasta1"] = "Turno Mañana hasta";
$lang["conf_atencion_desde2"] = "Turno Tarde desde";
$lang["conf_atencion_hasta2"] = "Turno Tarde hasta";
$lang["conf_atencion_dias"] = "Días de Atención";

// AUDITORÍA

$lang["AuditoriaTitulo"] = "Auditoría del Sistema - Acciones";
$lang["AuditoriaSubtitulo"] = "En este apartado podrá ver la auditoría de las acciones realizadas en el sistema, para visualizar la auditoría seleccione la tabla, usuario o en un rango de fechas.";

$lang["auditoria_tabla"] = "Tabla del Sistema";
$lang["auditoria_fechas"] = "Fechas";

$lang["auditoria_tabla_corta"] = "Tabla";
$lang["auditoria_accion"] = "Acción";
$lang["auditoria_pk"] = "PK";
$lang["auditoria_usuario"] = "Usuario";
$lang["auditoria_fecha"] = "Fecha";
$lang["auditoria_columna"] = "Columna";
$lang["auditoria_valor_anterior"] = "Valor Anterior";
$lang["auditoria_valor_nuevo"] = "Valor Nuevo";
$lang["auditoria_Reporte"] = "Reporte de Auditoría";

$lang["AuditoriaAccesoTitulo"] = "Auditoría del Sistema - Acceso";
$lang["AuditoriaAccesoSubtitulo"] = "En este apartado podrá ver la auditoría de los accesos realizadas en el sistema, para visualizar la auditoría seleccione el filtro o en un rango de fechas.";

$lang["auditoria_usuario_detectado"] = "Usuario Detectado";
$lang["auditoria_tipo_acceso"] = "Acceso Detectado";
$lang["auditoria_tipo_ip"] = "IP";

// CONFIGRUACIÓN - CATÁLOGOS

$lang["CatalogoTitulo"] = "Catálogo del Sistema";
$lang["CatalogoSubtitulo"] = "En este apartado podrá gestionar el catálogo utilizado para el registro de información del sistema y en la APP.";

$lang["catalogo_tipo_codigo"] = "Categoría";
$lang["catalogo_codigo"] = "Código";
$lang["catalogo_parent"] = "Depende de (opcional)";
$lang["catalogo_descripcion"] = "Valor";

$lang["TablaOpciones_NuevoCatalogo"] = "Nuevo Registro";
$lang["conf_catalogo_Pregunta"] = "Actualizar el Catálogo del Sistema.";

// ESTRCUTRA AGENCIA, SUCURSAL, ROLES Y PERFILES

$lang["AgenciaTitulo"] = "Estructura de Agencias";
$lang["AgenciaSubtitulo"] = "En este apartado podrá gestionar las Agencias.";

$lang["RegionalTitulo"] = "Estructura de Sucursales";
$lang["RegionalSubtitulo"] = "En este apartado podrá gestionar las Sucursales.";

$lang["estructura_agencia"] = "Agencia";
$lang["estructura_regional"] = "Sucursal";
$lang["estructura_entidad"] = "Casa Principal";

$lang["estructura_nombre"] = "Nombre";
$lang["estructura_parent"] = "Depende de";
$lang["estructura_detalle"] = "Descripción";

$lang["estructura_Pregunta"] = "Registrar la información de la estructura.";

$lang["estructura_menu"] = "Seleccione el Menú/Módulo al que puede ingresar el Rol";

$lang["RolTitulo"] = "Roles y Asignación de Módulos";
$lang["RolSubtitulo"] = "En este apartado podrá gestionar los Roles del Sistema, referido a su nombre, descripción y el acceso a los módulos del sistema.";

// PERFILES

$lang["PerfilUsuarioTitulo"] = "Asignación de Perfiles a Usuarios";
$lang["PerfilUsuarioSubtitulo"] = "Puede entenderse el perfil como el conjunto de permisos específicos otorgados a usuarios determinados para el acceso a la información en los diferentes módulos del sistema como visualizar el detalle de los registros y otros. <br /> Para continuar, seleccione al usuario requerido de la tabla.";
$lang["PerfilUsuarioFormSubtitulo"] = "Seleccione los perfiles del listado y seleccione \"Aceptar\".";

$lang["PerfilTitulo"] = "Perfiles de Usuario";
$lang["PerfilSubtitulo"] = "En este apartado podrá gestionar los Perfiles del Sistema, referido a su nombre y descripción.";

// DOCUMENTO

$lang["DocumentoTitulo"] = "Gestión de Documentos (Formularios y/o Cartas)";
$lang["DocumentoSubtitulo"] = "En este apartado podrá gestionar los documentos utilizados en el Sistema. Establezca qué documentos puede enviarse a la Empresa Aceptante por correo electrónico.";

$lang["documento_nombre"] = "Nombre";
$lang["documento_enviar"] = "¿Se envía a la Empresa Aceptante?";
$lang["documento_pdf"] = "PDF del Documento";
$lang["documento_tiene_adjunto"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Ya Existe un Documento";

$lang["documento_Pregunta"] = "Actualizar la información del Documento";

// TIPO DE PERSONA

$lang["PersonaTitulo"] = "Gestión de Tipos de Registro";
$lang["PersonaSubtitulo"] = "En este apartado podrá gestionar los tipos de registro utilizados en el Sistema y su relación con los documentos que son requeridos para su Verificación.";

$lang["estructura_documento"] = "Seleccione el Documento requerido para el Tipo de Persona";

// SERVICIOS

$lang["ServicioTitulo"] = "Gestión de Productos Ofrecidos";
$lang["ServicioSubtitulo"] = "En este apartado podrá gestionar los productos ofrecidos relacionados a las campañas utilizados en el Sistema.";

// ACTIVIDADES

$lang["ActividadesTitulo"] = "Gestión de Actividades de Verificación";
$lang["ActividadesSubtitulo"] = "En este apartado podrá gestionar las Actividades de Verificación utilizadas en el Sistema.";

$lang["prospecto_actividades"] = "Actividades Verificación";

// TAREAS DE MANTENIMIENTO DE CARTERA

$lang["TareaTitulo"] = "Gestión de Tareas de Mantenimiento de Cartera";
$lang["TareaSubtitulo"] = "En este apartado podrá gestionar las tareas de mantenimiento de cartera que efectúan los Verificadores, utilizados en el Sistema.";

// VERIFICADORES

$lang["EjecutivoTitulo"] = "Gestión de Agentes ";
$lang["EjecutivoSubtitulo"] = "En este apartado podrá gestionar los Agentes del Sistema que son los usuarios que utilizarán la APP. Como requisito indispensable para asignar a un Agente, tiene que ser un usuario registrado y con el Rol correspondiente al \"Ejecutivo/Agente Móvil (APP)\" <br /><br /> Podrá realizar la transferencia de cuentas y el seguimiento de Visitas Realizadas.";

$lang["EjecutivoTitulo_nuevo"] = "Habilitar un Usuario como Ejecutivo de Cuentas";
$lang["EjecutivoSubtitulo_nuevo"] = "Asigne un Usuario con Rol de tipo \"Ejecutivo de Cuentas\" para que se le asigne Leads y/o Mantenimientos.";

$lang["EjecutivoTitulo_editar"] = "Transferir Cuenta";
$lang["EjecutivoSubtitulo_editar"] = "En este apartado podrá realizar el cambio y traspaso de las cuentas (Leads/Mantenimientos) del Ejecutivo de Cuentas, a fin de asignarlo a otro usuario.";


$lang["ejecutivo_nombre"] = "Nombre del Agente";
$lang["ejecutivo_ejecutivo"] = "Ejecutivo";
$lang["ejecutivo_zona"] = "Zona Asignada";

$lang["ejecutivo_Pregunta1"] = "Habilitar como Ejecutivo de Cuentas (Leads/Mantenimientos) al usuario seleccionado.";
$lang["ejecutivo_Pregunta2"] = "Transferir la cuenta (Leads/Mantenimientos) al usuario seleccionado.";

$lang["TablaOpciones_habilitar_ejecutivo"] = "Habilitar Ejecutivo <br /> de Cuentas";

$lang["TablaOpciones_transferir"] = "Transferir <br /> Cuenta";

$lang["TablaOpciones_horario"] = "Gestionar <br /> Horario";

$lang["TablaOpciones_ver_horario"] = "Ver <br /> Horario";

$lang["TablaOpciones_asignar_zona"] = "Asignar <br /> Zona";
$lang["TablaOpciones_prospectos_asignados"] = "Leads <br /> Asignados";
$lang["TablaOpciones_mantenimientos_asignados"] = "Mantenimientos <br /> Asignados";

$lang["TablaOpciones_prospectos_asignados_titulo"] = "Listando Leads Asignados al Agente";
$lang["TablaOpciones_mantenimientos_asignados_titulo"] = "Mantenimientos Asignados al Agente";

$lang["TablaOpciones_visitas_asignados"] = "Visitas <br /> Asignados";

$lang["ejecutivo_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Al transferir la cuenta, los leads y empresas serán reasignados y el usuario ya no podrá acceder a la APP.";

$lang["ejecutivo_sin_zona"] = "El Ejecutivo de Cuentas no tiene asignada una zona, ubique el marcador en la zona requerida.";

$lang["ejecutivo_ubicacion_actual"] = "<i class='fa fa-globe' aria-hidden='true'></i> Ver Ubicación Actual.";

$lang["EjecutivoTitulo_zona"] = "Asignar Zona al Ejecutivo de Cuentas";
$lang["EjecutivoSubitulo_zona"] = "Para Guardar la ubicación de la Zona del Ejecutivo de Cuentas, solamente ubique el marcador y se guardará automáticamente. Si no ve el marcador o requiere registrar la posición manulmente puede hacer 'doble-clic' sobre el mapa.";

$lang["ejecutivo_ejecutivo_zona"] = "Zona del Ejecutivo de Cuentas";

$lang["EjecutivoTitulo_Mapa"] = "Mapa de Zonas de los Verificadores ";

$lang["TablaOpciones_mapa_ejecutivo"] = "Mapa de <br /> Zonas Ejecutivos";

$lang["TablaOpciones_ejecutivo_indice"] = "Índice de <br /> Cumplimiento";

$lang["SeguimientoTitulo"] = "Tracking de Agentes";
$lang["SeguimientoSubtitulo"] = "En este apartado podrá realizar el seguimiento de las ubicaciones de las visitas realizadas por los Agentes respecto al \"Check de la Visita\" efectuado. Para realizar el seguimiento: <br /><br /> 1. Seleccione el Agente <br /> 2. Seleccione el Rango de Fechas <br /> 3. Seleccione el Tipo de Visita a mostrar <br /> 4. Seleccione el Formato del Reporte que requiera ver";

$lang["ejecutivo_seguimiento_visita"] = "Tipo de Visita";
$lang["ejecutivo_seguimiento_fecha_visita"] = "Fecha Agendada";
$lang["ejecutivo_seguimiento_fecha"] = "Fecha Check Visita";
$lang["ejecutivo_seguimiento_prospecto"] = "Leads";
$lang["ejecutivo_seguimiento_mantenimiento"] = "Otas Tareas";
$lang["ejecutivo_seguimiento_formato_reporte"] = "Formato del Reporte";

$lang["seguimiento_Reporte"] = "Reporte de Seguimiento de Visitas de los Verificadores ";

$lang["HorarioTitulo"] = "Horario de Visitas del Ejecutivo de Cuentas";
$lang["HorarioSubtitulo"] = "En este apartado podrá gestionar los horarios de las visitas que <u> aún no realizaron el Check-In o aún no estén Consolidadas</u>.";

$lang["EjecutivoTitulo_metrica"] = "Índice de Cumplimiento";
$lang["EjecutivoSubtitulo_metrica"] = "<br /> En este apartado podrá establecer la Meta o Índice de Cumplimiento de Vísitas por Día de los Verificadores. <br /><br /> Esta Información se verá reflejada en las estadísticas de la APP del Ejecutivo de Cuentas.";

$lang["ejecutivo_indice_Pregunta"] = "Actualizar el Índice de Cumplimiento, también se actualizará el parámetro de las estadísticas de la APP.";

$lang["BandejaEjecutivoTitulo"] = "Operaciones - Visitas Realizadas";
$lang["BandejaEjecutivoSubtitulo"] = "En este apartado podrá visualizar las visitas (Leads o Mantenimientos) que le hayan sido asignadas.";


// Prospectos

$lang["prospecto_codigo"] = "Lead";
$lang["prospecto_tipo_persona"] = "Tipo Persona";
$lang["prospecto_tipo_empresa"] = "Tipo Empresa";
$lang["prospecto_nombre_empresa"] = "Nombre";
$lang["prospecto_fecha_asignaccion"] = "Fecha Asignación";
$lang["prospecto_fecha_consolidado"] = "Fecha Consolidado";
$lang["prospecto_estado_consolidado"] = "Estado Consolidado";

// EMPRESA

$lang["empresa_consolidada_detalle"] = "¿Consolidado?";
$lang["empresa_categoria_detalle"] = "Categoría";
$lang["empresa_nit"] = "NIT o Identificador";
$lang["empresa_adquiriente_detalle"] = "Adquiriente";
$lang["empresa_tipo_sociedad_detalle"] = "Tipo de Sociedad";
$lang["empresa_nombre_legal"] = "Nombre Legal";
$lang["empresa_nombre_fantasia"] = "Nombre Fantasía";
$lang["empresa_nombre_establecimiento"] = "Establecimiento";
$lang["empresa_denominacion_corta"] = "Denominación Corta";
$lang["empresa_rubro_detalle"] = "¿A qué te dedicas?";
$lang["empresa_perfil_comercial_detalle"] = "Perfil Comercial";
$lang["empresa_mcc_detalle"] = "MCC";
$lang["empresa_nombre_referencia"] = "Nombre Referencia";
$lang["empresa_ha_desde"] = "Atiende desde";
$lang["empresa_ha_hasta"] = "Atiende hasta";
$lang["empresa_dias_atencion"] = "Días Atención";
$lang["empresa_medio_contacto_detalle"] = "Medio Contacto";
$lang["empresa_dato_contacto"] = "Dato Contacto";
$lang["empresa_email"] = "Correo Electrónico";
$lang["empresa_departamento_detalle"] = "Departamento";
$lang["empresa_municipio_detalle"] = "Municipio/Ciudad";
$lang["empresa_zona_detalle"] = "Zona";
$lang["empresa_tipo_calle_detalle"] = "Tipo Calle";
$lang["empresa_calle"] = "Calle";
$lang["empresa_numero"] = "Número";
$lang["empresa_direccion_literal"] = "Dirección Literal";
$lang["empresa_info_adicional"] = "Info Adicional";
$lang["ejecutivo_asignado_nombre"] = "Ejecutivo Asignado";
$lang["ejecutivo_asignado_contacto"] = "Contacto Ejecutivo";

$lang["EmpresaTitulo"] = "Registro de Comercio de el CORE y Asignación";
$lang["EmpresaSubtitulo"] = "En este apartado podrá registrar un Comercio que se encuentre en el CORE y asignarlo a un Ejecutivo de Cuentas, a fin de poder registrar Mantenimientos de Cartera.";

$lang["empresa_paso1"] = "Paso 1/2 - Identifique el Comercio registrado en el CORE a través del NIT";

$lang["empresa_paso2"] = "Paso 2/2 - Asigne la Empresa a un Ejecutivo de Cuentas";

$lang["empresa_parametro"] = "Campo Registrado";
$lang["empresa_valor"] = "Valor";

$lang["aprobar_empresa_Pregunta"] = "Registrar el Comercio de el CORE en el Sistema y asignarlo al Ejecutivo de Cuentas seleccionado.";

$lang["empresa_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> El Comercio de el CORE fue registrado correctamente en el sistema y asociado al Ejecutivo de Cuentas seleccionado.";

// SOLICITUD

$lang["solicitud_prospecto_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> La Solicitud de Verificación se registró correctamente, la Empresa Aceptante recibió un correo electrónico con el enlace de confirmación de la solicitud.";
$lang["solicitud_mantenimiento_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> La Solicitud de Mantenimiento se registró correctamente, la Empresa Aceptante recibió un correo electrónico con el enlace de confirmación de la solicitud.";

$lang["externo_prospecto_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> ¡Excelente! Tu solicitud se guardo correctamente. <br /> Te hemos enviado un correo electrónico para que puedas confirmar tu solicitud. Una vez que la hayas confirmado, podremos continuar con el registro. <br /><br /> Gracias...";
$lang["externo_mantenimiento_guardado"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Excelente! Tu solicitud se guardo correctamente. <br /> Te hemos enviado un correo electrónico para que puedas confirmar tu solicitud. Una vez que la hayas confirmado, podremos continuar con el registro. <br /><br /> Gracias...";

$lang["externo_captcha"] = "Ingrese el código que aparecen en la imágen";

// -- Externo

$lang["externo_prospecto_titulo"] = " <i class='fa fa-id-badge' aria-hidden='true'></i> Contactar un Agente";
$lang["externo_prospecto_subtitulo"] = "Para poder registrarse, por favor complete el siguiente formulario y con gusto nos comunicaremos con usted a la brevedad posible.";

$lang["SolicitudTitulo_externomapa"] = " <i class='fa fa-home' aria-hidden='true'></i> Donde te encuentras";
$lang["SolicitudTitulo_externomapa_sub"] = "Mueva el marcador para guardar su posición, si no lo ve o requiere registrar la posición manulmente puede hacer 'doble-clic' sobre el mapa.";

$lang["externo_mantenimiento_titulo"] = "Contácta a tu Ejecutivo de Cuentas";
$lang["externo_mantenimiento_subtitulo"] = "Bienvenido, para contactar con tu Ejecutivo de Cuentas por favor complete los siguientes campos.";

// SOLICITUD DE VERIFICACION

$lang["AfiliacionTitulo"] = "Solicitudes de revisión de empresas ";
$lang["AfiliacionSubtitulo"] = "En este apartado podrá realizar el último filtro para la revisión documental e información requerida, suficiente y necesaria, para la  verificación de empresas.";

$lang["solicitud_nombre_persona"] = "Tu nombre Completo";
$lang["solicitud_nombre_empresa"] = "¿Cuál es tu empresa?";
$lang["solicitud_ciudad"] = "¿En que Departamento te encuentras?";
$lang["solicitud_telefono"] = "Tu Teléfono o Móvil";
$lang["solicitud_email"] = "Email de Contácto";
$lang["solicitud_direccion_literal"] = "¿Dónde te ubicamos?";
$lang["solicitud_direccion_geo"] = "Geolocalización";
$lang["solicitud_direccion_geo_des"] = "Quiero ver mi dirección en mapa";
$lang["solicitud_rubro"] = "¿A qué te dedicas?";
$lang["solicitud_fecha"] = "Fecha Solicitud";
$lang["solicitud_ip"] = "IP";
$lang["solicitud_estado"] = "Estado";
$lang["solicitud_servicios"] = "¿Qué Servicios Necesitas?";
$lang["solicitud_observacion"] = "Observación";

$lang["solicitud_estado_pendiente"] = "Pendientes";
$lang["solicitud_estado_aprobado"] = "Aprobados";
$lang["solicitud_estado_cancelado"] = "Rechazados";

$lang["RechazarTitulo"] = "Rechazar Solicitud";
$lang["rechazar_Pregunta"] = "Rechazar la Solicitud de Verificación";
$lang["rechazar_Pregunta2"] = "Rechazar la Solicitud de Mantenimiento";

$lang["NuevoSolicitudTitulo"] = "Registrar Solicitudes de Verificación";
$lang["EditarSolicitudTitulo"] = "Editar Solicitudes de Verificación";
$lang["EditarSolicitudSubtitulo"] = "Complete el formulario con la información recopilada con la Empresa Aceptante. Esta información será utilizada para la creación del Prospecto.";

$lang["editar_solicitud_Pregunta"] = "Registrar la Solicitud de Verificación";
$lang["editar_solicitud_Pregunta2"] = "Registrar la Solicitud de Mantenimiento";

$lang["SolicitudTitulo_zona"] = " <i class='fa fa-flag-o' aria-hidden='true'></i> Geolocalización del Solicitante";
$lang["SolicitudSubitulo_zona"] = "Para Guardar la ubicación del Solicitante, solamente ubique el marcador y se guardará automáticamente. Si no ve el marcador o requiere registrar la posición manulmente puede hacer 'doble-clic' sobre el mapa.";

$lang["aprobar_solicitud_paso1"] = "Primero - Seleccione el tipo de registro ";
$lang["aprobar_solicitud_paso2"] = "Etapa de Selección del Agente ";
$lang["aprobar_solicitud_paso3"] = "Por último - Verifique el resumen del registro";

$lang["solicitud_tipo_persona"] = "Tipo de Persona";
$lang["solicitud_categoria_empresa"] = "Categoría Empresa";
$lang["categoria_empresa_comercio"] = "Titular";
$lang["categoria_empresa_sucrusal"] = "Beneficiario";
$lang["solicitud_verificar_nit"] = "¿Es correcto?";
$lang["solicitud_ver_mapa"] = "Visualizar Mapa";
$lang["solicitud_ver_calendario"] = "Visualizar Calendario";

$lang["SolicitudTitulo_aprobar"] = "¡Excelente! Vamos a continuar";
$lang["SolicitudSubitulo_aprobar"] = "Para aceptar la Solicitud, complete el formulario 
(todos los campos son obligatorios):";

$lang["Ayuda_solicitud_nit"] = "Verifique primeramente el NIT para asegurarse que el prospecto ser trata de un Comercio o un Establecimiento/Sucursal. La verificación será respecto a empresas afiliadas en el CORE o registradas en el Sistema.";
$lang["Ayuda_solicitud_catergoria"] = "¿Verificó el NIT? Si la empresa está afiliada correspondería a \"Nuevo ". $lang["categoria_empresa_sucrusal"] . "\"";
$lang["Ayuda_solicitud_tipo"] = "El Tipo de Persona definirá que tipo de documentación será solicitada";

$lang["DetalleNITTitulo"] = "Verificación del NIT";

$lang["nit_advertencia"] = "La Empresa se encuentra afiliada sólo en el CORE.";
$lang["no_nit_advertencia"] = "No se encontró la empresa afiliada en el CORE o el Sistema con el NIT indicado. <br /><br /> Correspondería a \"Nuevo ". $lang["categoria_empresa_comercio"] . "\"";
$lang["no_nit_encontrado"] = "No se encontró la empresa afiliada en el CORE o el Sistema con el NIT indicado";
$lang["verifique_nit"] = "Verifique el NIT.";

$lang["verifique_nit_mantenimiento"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> No se encontró ninguna empresa con el NIT indicado, verifique el NIT. </div>";
$lang["verifique_nit_solo_paystudio"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> La empresa se encuentra registrada sólo en el CORE, por favor solicite a la instancia correspondiente que registre esta empresa en el sistema.</div>";
$lang["verifique_nit_ya_registrado"] = "<div class='PreguntaConfirmar'> <i class='fa fa-meh-o' aria-hidden='true'></i> El comercio ya se encuentra registrado en el sistema, no es necesario volver a registrarlo. </div>";

$lang["verifique_nit_registrado"] = "El comercio ya se encuentra registrado en el sistema, verifique el NIT. </div>";

$lang["solicitud_mapa_ejecutivos"] = "Identifique y Seleccione al Agente más cercano al Cliente";
$lang["solicitud_asignar_fecha"] = "Asignar Fecha y Hora de la Visita";
$lang["solicitud_tiempo_visita"] = "Tiempo de Visita";

$lang["Ayuda_tiempo_visita"] = "Si requiere otros tiempos, puede solicitar el cambio de la fecha y hora de la Visita al administrador";
$lang["Ayuda_asignar_fecha"] = "Para asignar una fecha y hora disponible, puede visualizar el calendario del Ejecutivo de Cuentas seleccionado";

$lang["SolicitudTitulo_mapa"] = " <i class='fa fa-search' aria-hidden='true'></i> ¿Cuál Ejecutivo de Cuentas se encuentra más cerca?";

$lang["aprobar_solicitud_Pregunta"] = "Aprobar la Solicitud y Crear un Nuevo Prospecto con el NIT indicado, el Ejecutivo de Cuentas seleccionado y la fecha y hora de la visita asignada";

$lang["aprobar_solicitud_guardado"] = "<br /><br /> El cliente recibió un correo de Notificación de la Visita y el Agente Asignado fue Notificado <br /><br /> ¿Qué requiere hacer ahora?";

$lang["ProspectoEnviarDocumentos"] = "Envíar Cartas y/o Formularios";

$lang["DetalleEnvioTitulo"] = " <i class='fa fa-envelope-o' aria-hidden='true'></i> Seleccione las cartas y/o formularios";
$lang["DetalleEnvioSubTitulo"] = "El listado de cartas y/o formularios se cargan de acuerdo al Tipo de Persona del Prospecto (puede tardar unos segundos)";

$lang["EnvioGuardado"] = "Se enviaron las cartas y/o formularios seleccionados a la EA";

// SOLICITUD DE MANTENIMIENTO

$lang["NuevoMantenimientoTitulo"] = "Registrar Solicitudes de Mantenimiento";
$lang["MantenimientoTitulo"] = "Solicitudes de Mantenimiento";
$lang["MantenimientoSubtitulo"] = "En este apartado podrá visualizar las solicitudes de Verificacion de empresas";

$lang["mantenimiento_otro"] = "Detalle otro Mantenimiento";
$lang["mantenimiento_otro_elegir"] = "¿Requiere otro mantenimiento?";
$lang["mantenimiento_tareas"] = "Tareas Solicitadas";

$lang["aprobar_mantenimiento_paso1"] = "Paso 1/3 - Identifique el comercio o establecimiento que requiere el mantenimiento";
$lang["aprobar_mantenimiento_paso2"] = "Paso 2/3 - Asigne la fecha y hora de la Visita al Ejecutivo de Cuentas Seleccionado";
$lang["aprobar_mantenimiento_paso3"] = "Paso 3/3 - Para concluir, Verifique  que la Información de la Solicitud este Correcta";

$lang["Ayuda_mantenimiento_nit"] = "Con el NIT indicado por el cliente, seleccione la empresa que requiere el Mantenimiento.";

$lang["aprobar_mantenimiento_Pregunta"] = "Aprobar la Solicitud y Crear un Nuevo Mantenimiento con el NIT indicado, el Ejecutivo de Cuentas seleccionado y la fecha y hora de la visita asignada";

// PROSPECTO

$lang["DocumentoProspectoTitulo"] = " <i class='fa fa-camera' aria-hidden='true'></i> Listado cartas y/o formularios ";
$lang["DocumentoProspectoTituloHistorial"] = " <i class='fa fa-history' aria-hidden='true'></i> Historial ";
$lang["documento_no_digitalizado"] = "No Digitalizado";
$lang["documento_si_digitalizado"] = "Visualizar Documento";
$lang["documento_si_digitalizado_historial"] = "Ver Historial";
$lang["documento_observar"] = "Observar";

$lang["documento_remitir_observación"] = "Remitir Observaciones";
$lang["documento_remitir_consulta"] = "¿Esta seguro de remitir las observaciones del(los) documento(s) al Ejecutivo de Cuentas?";


$lang["ObservarDocTitulo"] = "Se procederá a Observar el Documento";
$lang["ObservarDocSubtitulo"] = "Para observar el Documento, indique la acción solicitada al Ejecutivo de Cuentas lo más claro posible.";

$lang["prospecto_justificar"] = "Detalle la Observación/Justificación";
$lang["ObsDoc_Pregunta"] = "Observar Documento. Al realizar esta acción se marcará el documento como observado y deberá ser devuelto al Ejecutivo de Cuentas a través de la opción ubicada en \"Revisar Documentación\".";

$lang["prospecto_obs_doc_guardado"] = " Se realizó la observación del documento indicado correctamente y el Ejecutivo de Cuentas asignado recibió un correo de Notificación.";
$lang["prospecto_obs_proc_guardado"] = " Se realizó la observación del prospecto indicado correctamente.";
$lang["prospecto_obs_doc_volver"] = "Volver a la Bandeja";


$lang["prospecto_id"] = "Código Asignado (interno)";
$lang["tipo_persona_detalle"] = "Tipo de Persona";
$lang["prospecto_misma_inf"] = "Misma Información";
$lang["prospecto_cambia_poder"] = "Cambio de Poder";
$lang["prospecto_reporte_bancario"] = "Cambio Reporte Bancario";
$lang["empresa_categoria"] = "Categoría Empresa";
$lang["prospecto_etapa_fecha"] = "Fecha Etapa";
$lang["empresa_nombre"] = "Nombre Empresa";
$lang["empresa_ejecutivo"] = "Ejecutivo de Cuentas";
$lang["prospecto_etapa_actual"] = "Etapa Actual";
$lang["prospecto_fecha_asignacion"] = "Fecha Asignación";
$lang["prospecto_etapa_nombre"] = "Nombre Etapa";
$lang["prospecto_excepcion"] = "Excepción";
$lang["prospecto_excepcion_acta"] = "Acta Excepción";
$lang["prospecto_rev"] = "Revisión Antecedentes";
$lang["prospecto_rev_fecha"] = "Fecha Revisión Antecedentes";
$lang["prospecto_rev_informe"] = "Resultado Revisión Antecedentes";
$lang["prospecto_rev_pep"] = "Antecedentes PEP";
$lang["prospecto_rev_match"] = "Antecedentes MATCH";
$lang["prospecto_rev_infocred"] = "Antecedentes INFOCRED";
$lang["prospecto_checkin"] = "Check Visita del Lead";
$lang["prospecto_checkin_fecha"] = "Fecha Check Visita";
$lang["prospecto_checkin_geo"] = "Geolocalización Visita del Lead";

$lang["prospecto_llamada"] = "Check Llamada del Lead";
$lang["prospecto_llamada_fecha"] = "Fecha Check Llamada";
$lang["prospecto_llamada_geo"] = "Geolocalización Llamada del Lead";

$lang["prospecto_consolidar_fecha"] = "Fecha Consolidado";
$lang["prospecto_consolidado"] = "Consolidado";
$lang["prospecto_consolidado_geo"] = "Geolocalización Consolidado";

$lang["prospecto_vobo_cumplimiento"] = "VoBo Cumplimiento";
$lang["prospecto_vobo_cumplimiento_fecha"] = "Fecha VoBo Cumplimiento";
$lang["prospecto_vobo_legal"] = "VoBo Legal";
$lang["prospecto_vobo_legal_fecha"] = "Fecha VoBo Legal";
$lang["prospecto_estado_actual"] = "Estado Actual";
$lang["prospecto_rechazado"] = "Rechazado";
$lang["prospecto_rechazado_fecha"] = "Rechazo Fecha Notificación";
$lang["prospecto_rechazado_detalle"] = "Rechazo Detalle";
$lang["prospecto_aceptado_afiliado"] = "Afiliado en Nazir";
$lang["prospecto_aceptado_afiliado_fecha"] = "Fecha Verificación en Nazir";
$lang["prospecto_entrega_servicio"] = "Servicio Entregado";
$lang["prospecto_entrega_servicio_fecha"] = "Fecha Servicio Entregado";
$lang["cal_visita_ini"] = "Visita Agendada Empezó";
$lang["cal_visita_fin"] = "Visita Agendada Terminó";

$lang["prospecto_no_evaluacion"] = "No Registrado";
$lang["prospecto_evaluacion"] = "Evaluación Legal para EA";
$lang["prospecto_excepcion_no_acta"] = "No se adjuntó ningún documento";

$lang["prospecto_estado"] = "Flujo";

$lang["DetalleProspectoTitulo"] = " <i class='fa fa-search' aria-hidden='true'></i> Detalle del Lead";

$lang["DetalleHistorialObservacion"] = " <i class='fa fa-comments-o' aria-hidden='true'></i> Historial Observaciones";
$lang["DetalleComentariosExcepcion"] = " <i class='fa fa-comments-o' aria-hidden='true'></i> Notas Excepción";
$lang["DetalleHistorialSeguimiento"] = " <i class='fa fa-road' aria-hidden='true'></i> Seguimiento del Prospecto";

$lang["observacion_fecha"] = "Fecha";
$lang["observacion_fecha_digitalizacion"] = "Fecha Digitalización";
$lang["observacion_usuario_deriva"] = "Derivado Por";
$lang["observacion_usuario_realizado"] = "Realizado Por";
$lang["observacion_usuario_accion"] = "Acción Realizada";
$lang["observacion_tipo"] = "Tipo";
$lang["observacion_documento"] = "Documento";
$lang["observacion_etapa"] = "Etapa";
$lang["observacion_derivado_etapa"] = "Derivado a Etapa";
$lang["observacion_detalle"] = "Detalle";
$lang["observacion_excepcion_detalle"] = "Comentario/Justificación";
$lang["observacion_estado"] = "Estado";

$lang["ObsProcTitulo"] = "Observar y Devolver Prospecto";
$lang["ObsProcSubtitulo"] = "Procederá a observar y devolver el prospecto a la instancia anterior. Para ello debe registrar un comentario o justificación. <br /> Al devolver un prospecto, <u> el tiempo asignado a su etapa seguirá corriendo. </u>";

// MANTENIMIENTO

$lang["DetalleMantenimientoTitulo"] = " <i class='fa fa-commenting-o' aria-hidden='true'></i> Detalle del Mantenimiento";

$lang["mant_id"] = "Código Asignado (interno)";
$lang["mant_fecha_asignacion"] = "Fecha Asignación";
$lang["mant_estado"] = "Estado del Mantenimiento";
$lang["mant_checkin"] = "Check-In con la EA";
$lang["mant_checkin_fecha"] = "Fecha del Check-In";
$lang["mant_completado_fecha"] = "Fecha Completado";
$lang["mant_documento_adjunto"] = "Existe Documento Adjunto";
$lang["mant_documento_adjunto_detalle"] = "Ver Documento";
$lang["mant_tareas_realizadas"] = "Tareas Realizadas";

// BANDEJA SUPERVISOR DE AGENCIA

$lang["SupervisorAgenciaTitulo"] = "Operaciones - Solicitudes de Pre-Verificación";
$lang["SupervisorAgenciaSubtitulo"] = "En este apartado podrá efectuar acciones sobre lo siguiente: <br /><br />- Solicitudes de pre- Verificación derivados por el Ejecutivo de Cuentas.<br />- Observar Documentación.<br />- Autorizar Documentación.";

$lang["TablaOpciones_revisar_documentacion"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Revisar <br /> Documentación";
$lang["TablaOpciones_observar_devolver"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Observar y <br /> Devolver";
$lang["TablaOpciones_autorizar_documentacion"] = "Autorizar<br /> Documentación";

// BANDEJA CUMPLIMIENTO

$lang["TablaOpciones_mostrar_resumen"] = "<i class='fa fa-eye' aria-hidden='true'></i> Mostrar/Ocultar Resumen de Mi Bandeja";

$lang["TablaOpciones_revisar_antecedentes"] = "Antecedentes <br /> y Remitir";

$lang["AntecedentesTitulo"] = "Revisión de Antecedentes y Remitir Prospecto";
$lang["AntecedentesSubtitulo"] = "En este apartado podrá registrar el resultado de la Evaluación de Antecedentes y remitir el Prospecto a la instancia siguiente.";

$lang["antecedentes_pep"] = "Cuenta con antecedentes PEP";
$lang["antecedentes_match"] = "Cuenta con antecedentes MATCH";
$lang["antecedentes_infocred"] = "Cuenta con antecedentes INFOCRED";

$lang["antecedentes_resultado"] = "Recomendación de la Revisión";

$lang["Ayuda_antecedentes_recomendacion"] = "Al aprobar el prospecto, ingresará al flujo de Verificación, caso contrario se derivará a la instancia respectiva para notificar a la EA";

$lang["antecedentes_detalle"] = "Registre el resultado de la Revisión";

$lang["antecedentes_Pregunta"] = "Completar la Revisión de Antecedentes y remitir el prospecto a la siguiente instancia. ¿Son correctos los resultados para PEP, MATCH e INFOCRED?";

// BANDEJA VERIFICACIÓN DE REQUISITOS

$lang["VerificacionTitulo"] = "Supervisión de Leads Consolidados";
$lang["VerificacionSubtitulo"] = "En este apartado podrá efectuar la revisión de los Leads que hayan sido Consolidados así el estado alcanzado. <br /><br /> Puede Ver el detalle de los registros que tengan enlaces habilitados haciendo clic sobre los mismos.";

$lang["TablaOpciones_revisar_requisitos"] = "Requisitos <br /> y Remitir";

$lang["TablaOpciones_solicitar_excepcion"] = "Solicitar <br /> Excepción";

$lang["RequisitosTitulo"] = "Verificación de Requisitos y Remitir Prospecto";
$lang["RequisitosSubtitulo"] = "En este apartado podrá registrar el resultado de la Verificación de Requisitos a fin de establecer si el Establecimiento/Sucursal cuenta con la misma información del Comercio o requiere otra información, y remitir el Prospecto a la instancia siguiente para continuar con la Verificación.";

$lang["requisitos_titulo_opcion"] = "Opción";
$lang["requisitos_titulo_requisito"] = "Requisito";

$lang["requisitos_misma_info"] = "¿Cuenta con todos los requisitos solicitado?";
$lang["requisitos_misma_info_des"] = "Si el cliente cuenta con todos los requisitos que le fueron solicitados, puede continuar con la Verificación";

$lang["requisitos_cambio_poder"] = "Otras opciones";
$lang["requisitos_cambio_poder_des"] = "Puede registrar otras opciones de acuerdo al flujo";

$lang["requisitos_cambio_bancario"] = "¿Reporte Bancario con fotocopia de C.I.?";
$lang["requisitos_cambio_bancario_des"] = "Puede observar los documentos requeridos, solicitando que el Ejecutivo de Cuentas digitalice el mismo para continuar con el proceso. Se derivará el prospecto a Cumplimiento.";

$lang["requisitos_Pregunta"] = "Completar la Verificación de Requisitos de la Empresa Aceptante con la opción seleccionada. ¿Todos los documentos e información son suficientes y necesarios?";

$lang["GenerarExcTitulo"] = "Está a punto de Generar una Excepción para el Prospecto";
$lang["GenerarExcSubtitulo"] = "Procederá a Generar una Excepción para el Prospecto, para ello, registre la observación/justificación.";

$lang["excepcion_advertencia"] = "<i class='fa fa-envelope' aria-hidden='true'></i> Se remitirá también un Correo Electrónico a la instancia correspondiente para solicitar la Excepción";
$lang["excepcion_detalle"] = "Registre la Observación/Justificación (máx 300 carac.)";

$lang["excepcion_Pregunta"] = "Solicitar la Generación de Excepción del Prospecto indicado";

$lang["TablaOpciones_ver_historial"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Historial <br /> Observaciones";
$lang["TablaOpciones_ver_historial_excepcion"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Notas <br /> Excepción";

$lang["TablaOpciones_observar_devolver"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Observar y <br /> Devolver";

$lang["observar_devolver_Pregunta"] = "Observar y Devolver el Prospecto a la instancia anterior. Considere que el tiempo de su etapa seguirá corriendo, deberá hacer seguimiento del prospecto.";

// BANDEJA CUMPLIMIENTO

$lang["CumplimientoTitulo"] = "Revisión Cumplimiento";
$lang["CumplimientoSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y brindar su Visto Bueno.";

$lang["TablaOpciones_revisar_cumplimiento"] = "Revisar <br /> y Remitir";

$lang["TablaOpciones_cumplimiento_f1"] = "Formulario <br /> Sociedad";

$lang["TablaOpciones_cumplimiento_f2"] = "Formulario <br /> Match";

$lang["cumplimiento_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Opción Seleccionada. <br /><br /> ¿Procedió con la revisión pertinente y suficiente de su instancia que establece la recomendación seleccionada?";

$lang["cumplimiento_opcion_vobo"] = "Marcar Visto Bueno (Vo.Bo.)";
$lang["cumplimiento_opcion_vobo_des"] = "Se marcará la revisión de Cumplimiento con el Visto Bueno de la instancia y se derivará a la instancia respectiva del flujo.";

$lang["cumplimiento_opcion_rechazar"] = "Recomendar Rechazar el Prospecto";
$lang["cumplimiento_opcion_rechazar_des"] = "De acuerdo a la revisión realizada, se rechazará el prospecto y se derivará a la instancia respectiva del flujo.";

// -- Formulario Sociedad

$lang["CumplimientoSociedadTitulo"] = "Formulario de Información Confidencial de la Persona Jurídica";
$lang["CumplimientoSociedadSubtitulo"] = "En este apartado podrá registrar la información del formulario, por favor complete todos los campos para continuar. <br /><br /> La primera vez que ingrese al formulario, los campos se autocompletarán con la información registrada en el sistema.";

$lang["form_sociedad_sector1"] = "DATOS BASICOS CONOCIMIENTO DEL CLIENTE";
$lang["form_sociedad_sector2"] = "CONSULTAS ADICIONALES";
$lang["form_sociedad_sector3"] = "FIRMAS FUNCIONARIOS Vo.Bo.";

$lang["form_razon_social"] = "Razón Social / Nombre Comercial";
$lang["form_nit"] = "NIT o Identificador";
$lang["form_matricula_comercio"] = "Matrícula Comercio";
$lang["form_direccion"] = "Dirección";

$lang["form_mcc"] = "Giro - Actividad económica";
$lang["form_rubro"] = "Actividad Específica";

$lang["form_criterios_aceptacion"] = "Criterios de aceptación";

$lang["form_flujo_estimado"] = "Flujo estimado de ventas (US$/mes)";
$lang["form_cuenta_bob"] = "Cuenta Bancaria Bs./Banco";
$lang["form_cuenta_usd"] = "Cuenta Bancaria US$/Banco";
$lang["form_titular_cuenta"] = "Nombre de Titular de Cta.";
$lang["form_ci"] = "C.I.";
$lang["form_representante_legal"] = "Representante Legal";

$lang["form_lista_accionistas"] = "Listado Accionistas/Propietarios";

$lang["form_requisitos_afiliacion"] = "REQUISITOS DE VERIFICACION ";

$lang["form_consultas_titulo1"] = "Establecimiento";
$lang["form_consultas_titulo2"] = "Representante Legal";

$lang["form_infocred_cuenta_endeuda"] = "Cuenta con endeudamiento en el SFN";
$lang["form_infocred_calificacion_riesgos"] = "Calificación de Riesgo asignada";
$lang["form_pep_categorizado"] = "Se encuentra categorizado como PEP";
$lang["form_pep_codigo"] = "Código PEP";
$lang["form_pep_cargo"] = "Cargo Desempeñado (más elevado)";
$lang["form_pep_institucion"] = "Institución en la que se desempeñó";
$lang["form_pep_gestion"] = "Gestión en la que se desempeñó";
$lang["form_lista_confidenciales"] = "Se encuentra registrado en Lista OFAC ";
$lang["form_match_observado"] = "Se encuentra observado en el Match";
$lang["form_lista_negativa"] = "Se encuentra registrado en Listas";
$lang["form_comentarios"] = "COMENTARIOS RELACIONADOS CON SU APRECIACIÓN";
$lang["form_firma_entrega1_nombre"] = "Nombre Elabora";
$lang["form_firma_entrega1_cargo"] = "Cargo Elabora";
$lang["form_firma_entrega1_fecha"] = "Fecha Elabora";
$lang["form_firma_entrega2_nombre"] = "Nombre Vo.Bo.";
$lang["form_firma_entrega2_cargo"] = "Cargo Vo.Bo.";
$lang["form_firma_entrega2_fecha"] = "Fecha Vo.Bo.";
$lang["form_firma_comercial_nombre"] = "Nombre Recepciona";
$lang["form_firma_comercial_cargo"] = "Cargo Recepciona";
$lang["form_firma_comercial_fecha"] = "Fecha Recepciona";

$lang["form_accionista_nombre"] = "Propietario / Principales Accionistas / Directorio";
$lang["form_accionista_documento"] = "Nro. Documento";
$lang["form_accionista_participacion"] = "% Participación";

$lang["cumplimiento_form1_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Información Registrada. <br /><br /> ¿Procedió con la revisión pertinente y suficiente de su instancia que establece los criterios registrados?";

// -- Formulario Match

$lang["CumplimientoMatchTitulo"] = "Formulario Diario de Consultas MATCH";
$lang["CumplimientoMatchSubtitulo"] = "En este apartado podrá registrar la información del formulario, por favor complete todos los campos para continuar. <br /><br /> La primera vez que ingrese al formulario, los campos se autocompletarán con la información registrada en el sistema, para los Comercios se buscará primeramente la información registrada en el Formulario Sociedad.";

$lang["form_observacion"] = "Observaciones";

// BANDEJA LEGAL

$lang["LegalTitulo"] = "Revisión Legal Prospecto";
$lang["LegalSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y brindar su Visto Bueno y para el caso de los Comercios generar la Evaluación Legal para Empresas Aceptantes.";

$lang["legal_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Opción Seleccionada. <br /><br /> ¿Procedió con la revisión pertinente y suficiente de su instancia que establece la recomendación seleccionada de acuerdo a la categoría de la empresa?";

$lang["legal_opcion_vobo"] = "Marcar Visto Bueno (Vo.Bo.)";
$lang["legal_opcion_vobo_des"] = "De acuerdo al resultado del análisis legal, se marcará la revisión de Cumplimiento con el Visto Bueno de la instancia y se derivará a la instancia respectiva del flujo.";

$lang["legal_opcion_rechazar"] = "Recomendar Rechazar el Prospecto";
$lang["legal_opcion_rechazar_des"] = "De acuerdo al resultado del análisis legal se podrá rechazar el prospecto y se derivará a la instancia respectiva del flujo.";

$lang["legal_evaluacion_opcion"] = "Reporte Adjunto";
$lang["legal_evaluacion"] = "Evaluación Legal para Empresas Aceptantes";
$lang["legal_generar_reporte"] = "<i class='fa fa-file-pdf-o' aria-hidden='true'></i> Ver Reporte";
$lang["legal_editar_reporte"] = "<i class='fa fa-pencil-square-o' aria-hidden='true'></i> Editar Reporte";

$lang["legal_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Debe Registrar la " . $lang["legal_evaluacion"] . " antes de continuar.";

$lang["TablaOpciones_evaluacion_legal"] = "Evaluación <br /> Legal";

$lang["EvalLegalTitulo"] = "Formulario de Evaluación Legal para Empresas Aceptantes";
$lang["EvalLegalSubtitulo"] = "En este apartado podrá registrar la información de la Evaluación Legal. Es necesario que complete correctamente este formulario antes de Remitir el Prospecto.";

// -- Formulario Evaluación

$lang["evaluacion_denominacion_comercial"] = "Denominación Comercial";
$lang["evaluacion_razon_social"] = "Razón Social";

$lang["evaluacion_revision_documental"] = "Evaluación Documental";

$lang["evaluacion_razon_idem"] = "Razon Social idem a datos generales";

$lang["evaluacion_fotocopia_nit"] = "Fotocopia de NIT";

$lang["evaluacion_nit"] = "Número NIT";
$lang["evaluacion_representante_legal"] = "Nombre del Representante Legal";

$lang["evaluacion_fotocopia_certificado"] = "Fotocopia de Certificado de Inscripción";
$lang["evaluacion_actividad_principal"] = "Actividad Principal";
$lang["evaluacion_actividad_secundaria"] = "Actividad Secundaria";

$lang["evaluacion_fotocopia_ci"] = "Fotocopia de Cédula de Identidad";
$lang["evaluacion_ci_pertenece"] = "Cédula de Identidad pertenece a";
$lang["evaluacion_ci_vigente"] = "Vigente";
$lang["evaluacion_ci_fecnac"] = "Fecha de nacimiento";
$lang["evaluacion_ci_titular"] = "Nombre del Titular de la CI";

$lang["evaluacion_fotocopia_testimonio"] = "Fotocopia de Testimonio de Constitución";
$lang["evaluacion_numero_testimonio"] = "Número de Testimonio";
$lang["evaluacion_duracion_empresa"] = "Duración de la Empresa";
$lang["evaluacion_fecha_testimonio"] = "Fecha del testimonio";
$lang["evaluacion_objeto_constitucion"] = "Objeto de la constitución";

$lang["evaluacion_fotocopia_poder"] = "Fotocopia de Poder de Representante Legal";
$lang["evaluacion_fecha_testimonio_poder"] = "Fecha del Testimonio";
$lang["evaluacion_numero_testimonio_poder"] = "Número de Testimonio";
$lang["evaluacion_firma_conjunta"] = "Firma Conjunta";
$lang["evaluacion_facultad_representacion"] = "Presenta Facultades de Representacion";

$lang["evaluacion_fotocopia_fundaempresa"] = "Fotocopia de Registro FUNDEMPRESA";
$lang["evaluacion_fundaempresa_emision"] = "Fecha de emision";
$lang["evaluacion_fundaempresa_vigencia"] = "Fecha de vigencia";
$lang["evaluacion_idem_escritura"] = "Objeto idem a escritura de constitucion";
$lang["evaluacion_idem_poder"] = "Representante Legal Idem a poder";
$lang["evaluacion_idem_general"] = "Denominacion Comercial idem a datos generales";

$lang["evaluacion_resultado"] = "RESULTADO DE ANALISIS LEGAL";


$lang["evaluacion_titulo_opcion"] = "CONCLUSIONES";
$lang["opcion_conclusion"] = "CONCLUSIONES";

$lang["evaluacion_conclusion1"] = "PROCEDER CON LA AFILIACION";
$lang["evaluacion_conclusion2"] = "PROCEDER CON LA AFILIACION BAJO RESPONSABILIDAD DEL AREA SOLICITANTE";
$lang["evaluacion_conclusion3"] = "NO PROCEDER CON LA AFILIACION";

$lang["evaluacion_pertenece_regional"] = "PERTENECIENTE A LA REGIÓN DE";

$lang["evaluacion_fecha_solicitud"] = "FECHA DE SOLICITUD";
$lang["evaluacion_fecha_evaluacion"] = "FECHA DE EVALUACIÓN";

$lang["evaluacion_regional_ayuda"] = "Los datos mostrados son los registrados en el catálogo del sistema";

$lang["evaluacion_Pregunta"] = "¿Completó correctamente los datos generales, la evaluación documental y el análisis legal respectivo?";


// BANDEJA APROBACIÓN PAYSTUDIO

$lang["AprobacionTitulo"] = "Verificación de empresas visitadas";
$lang["AprobacionSubtitulo"] = " En este apartado podrá visualizar las solicitudes de verificación de empresas.";

$lang["aprobacion_Pregunta"] = "Está a punto de aprobar el Prospecto indicado y se procederá con la Inserción de la Información al Core del Sistema. <br /><br /> ¡ESTA ACCIÓN NO SE PUEDE DESHACER!";

$lang["TablaOpciones_aprobar"] = "APROBAR PARA <br /> PAYSTUDIO";

$lang["AprobacionFormTitulo"] = "¡Está a punto de Aprobar el Prospecto!";
$lang["AprobacionFormSubtitulo"] = "En este apartado podrá realizar el último filtro para la revisión documental e información requerida, suficiente y necesaria, para la inserción en el CORE (Nazir). Por favor complete los siguientes pasos:";

$lang["aprobar_paso1"] = "Paso 1/3 - Revise y Verifique la Información del Prospecto";
$lang["aprobar_paso2"] = "Paso 2/3 - Revise y Verifique la Información que será insertada en el CORE";
$lang["aprobar_paso3"] = "Paso 3/3 - Marque los Requisitos de Verificación";

$lang["aprobacion_1_des"] = "¿Toda la información del Prospecto así como su documentación es correcta, suficiente y se encuentra verificada por las instancias correspondientes?";
$lang["aprobacion_2_des"] = "Confirmar que la información requerida del prospecto se aprobará";

$lang["aprobacion_advertencia"] = "<i class='fa fa-lock' aria-hidden='true'></i> No puede continuar hasta que complete la información requerida por el CORE respecto a: <br />";

$lang["error_WS_el CORE"] = "<i class='fa fa-plug' aria-hidden='true'></i> Pasó algo con el Web Service de Inserción (el CORE). No se completó el proceso, por favor comuníquese con el Administrador del Sistema.";

$lang["prospecto_aprobado_guardado"] = "¡Hooray! El Prospecto fue correctamente Aprobado";

// BANDEJA EXCEPCIÓN - JUSTIFICAR

$lang["JustificaTitulo"] = "Excepciones (Justificar e Informar)";
$lang["JustificaSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y determinar la acción correspondiente.";

$lang["excepcion_generada_Pregunta"] = "Completar la Revisión de la Empresa Aceptante con la Opción Seleccionada. <br /><br />";

$lang["justificar_opcion_vobo"] = "Continuar con la Excepción - Justificar e Informar";
$lang["justificar_opcion_vobo_des"] = "De acuerdo al resultado del análisis realizado, se derivará a la instancia correspondiente para continuar con la Excepción.";

$lang["excepcion_opcion_rechazar"] = "Recomendar Rechazar el Prospecto";
$lang["excepcion_opcion_rechazar_des"] = "De acuerdo al resultado del análisis efectuado se podrá rechazar el prospecto y se derivará a la instancia respectiva del flujo.";

$lang["acta_excepcion_pdf"] = "Adjuntar el acta de reunión (PDF)";
$lang["acta_excepcion_pdf_ok"] = " <i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Ok, listo para subir";

// BANDEJA EXCEPCIÓN - GESTIONAR

$lang["GestionTitulo"] = "Excepciones (Gestionar Excepción)";
$lang["GestionSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y determinar la acción correspondiente.";

$lang["gestion_opcion_vobo"] = "Continuar con la Excepción - Gestionar Excepción";
$lang["gestion_opcion_vobo_des"] = "De acuerdo al resultado del análisis realizado, se derivará a la instancia correspondiente para continuar con la Excepción.";

// BANDEJA EXCEPCIÓN - ANALIZAR

$lang["AnalisisTitulo"] = "Excepciones (Analizar Excepción)";
$lang["AnalisisSubtitulo"] = "En este apartado podrá realizar la revisión de los leads respectiva de esta instancia y determinar la acción correspondiente.";

$lang["analisis_opcion_vobo"] = "Autorizar Excepción";
$lang["analisis_opcion_vobo_des"] = "De acuerdo al resultado del análisis realizado, se deberá justificar brevemente la aprobación y adjuntar el acta de reunión para que posteriormente se derive a la instancia correspondiente para continuar con el flujo correspondiente.";

$lang["analisis_opcion_rechazar"] = "Instruir Cancelar Verificación";

// BANDEJA RECHAZO

$lang["RechazoTitulo"] = "Notificar Rechazo";
$lang["RechazoSubtitulo"] = "En este apartado podrá realizar la Notificación de Rechazo de los leads que hayan sido rechazados.";

$lang["RechazoFormTitulo"] = "¡Confirmar el Rechazo del Prospecto!";
$lang["RechazoFormSubtitulo"] = "En este apartado podrá realizar la Notificación de Rechazo, registre el detalle de la razón del rechazo y proceda a notificar a la Empresa Aceptante de manera cordial, clara y oportuna a la Empresa Aceptante.";

$lang["TablaOpciones_notificar_rechazar"] = "Notificar <br /> Rechazo";

$lang["rechazo_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Recuerde Notificar el rechazo a la Empresa Aceptante. Esta acción no se puede deshacer.";

$lang["excepcion_generada_Pregunta"] = "Rechazar la Verificación del Prospecto con la observación/justificación registrada.";

// FLUJO DE TRABAJO

$lang["FlujoTitulo"] = "Etapas del BPM y Tiempos";
$lang["FlujoSubtitulo"] = "En este apartado puede visualizar las etapas del flujo seleccionado y administrar los <u>tiempos</u> de cada una de ellas y las opciones de notificación.";

$lang["etapa_seleccion"] = "Seleccione el Flujo";

$lang["etapa_nombre"] = "Nombre Corto";
$lang["etapa_detalle"] = "Descripción";
$lang["etapa_parent"] = "Consecutivo de";
$lang["etapa_tiempo"] = "Tiempo máximo de la etapa (en horas)";
$lang["etapa_notificar_correo"] = "¿Notificar Instancia por Correo?";

$lang["Ayuda_etapa_rol"] = "Los usuarios con el Rol establecido serán los actores directos de esta etapa";
$lang["Ayuda_etapa_tiempo"] = "Se respetará los días laborales configurados en el sistema y los feriados";
$lang["Ayuda_etapa_envio"] = "Puede configurar si esta etapa recibirá la notificación por correo electrónico cuando le deriven un prospecto";

$lang["estructura_Pregunta"] = "Actualizar la información de la Estructura";

$lang["flujo_advertencia"] = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Considere que la modificación de la etapa del flujo podría ocasionar incongruencia de datos en los leads en proceso.";

// REPORTES

$lang["ReporteTitulo"] = "Reportes - Toma de Decisiones";
$lang["ReporteSubtitulo"] = "En este apartado podrá generar, a través de multifiltros interdependientes, los reportes del sistema respecto a: <br /><br /> - Graficación de las Etapas del Flujo y Seguimiento de Leads. <br /> - Seguimiento de Campañas / Leads  <br /> - Top Documentación Digitalizada Observada.<br /> - Construcción de Reportes Tabla Pivot";

$lang["reportes_ayuda"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Puede Marcar/Desmarcar las etapas y puede ver el gráfico en pantalla completa haciendo doble clic en el.";

$lang["reportes_ayuda_pivot"] = "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Seleccione los campos para definirlos como filas y/o columnas y construir el reporte que requiera.";

$lang["reportes_tipo_reporte"] = "Tipo de Reporte";
$lang["reportes_opciones_agrupamiento"] = "Agrupar por";
$lang["reportes_opciones_dato_mostrar"] = "Dato a mostrar";
$lang["reportes_generar_tabla"] = "<i class='fa fa-table' aria-hidden='true'></i> Generar Reporte";
$lang["reportes_generar_grafica"] = "<i class='fa fa-bar-chart' aria-hidden='true'></i> Generar Gráfica";
$lang["reportes_toggle_resumen"] = "Mostrar/Ocultar Resumen";
$lang["reportes_boton_agregar_filtro"] = " <i class='fa fa-cog' aria-hidden='true'></i> AGREGAR FILTRO";
$lang["reportes_opciones_filtro"] = "Filtrar por";
$lang["reportes_exportar_pdf"] = " <i class='fa fa-file-pdf-o' aria-hidden='true'></i> Exportar a PDF";
$lang["reportes_exportar_excel"] = " <i class='fa fa-file-excel-o' aria-hidden='true'></i> Exportar a Excel";

$lang["reportes_opciones_agrupamiento_ayuda"] = "Refiere al criterio principal para mostrar los resultados, que pueden ser utilizado para un seguimiento específico o para efectuar comparaciones";
$lang["reportes_opciones_dato_mostrar_ayuda"] = "Refiere al dato que será sujeto de seguimiento, por ejemplo en horas trabajadas o empresas registradas";

$lang["ReporteTituloIzquierda"] = ":: " . $lang["NombreSistema_corto"] . " ::";
$lang["ReporteTituloCentro"] = " ";
$lang["ReporteTituloDerecha"] = "Reportes";

// CONSULTAS Y SEGUIMIENTO

$lang["ConsultaTitulo"] = "Consultas y Seguimiento";
$lang["ConsultaSubtitulo"] = "En este apartado podrá realizar las consultas respecto a los leads y mantenimientos registrados en el sistema. Los resultados se mostrarán regionalizados de acuerdo a los permisos con los que cuente. <br /><br />Puede generar las consultas utilizando múltiples filtros de acuerdo a lo que requiera y para ver el detalle haga clic en “Más Opciones”.";

$lang["consulta_prospectos"] = "Consultas Leads";
$lang["consulta_mantenimientos"] = "Consultas Mantenimientos";

$lang["consulta_pregunta_reporte"] = "Al cambiar el Tipo de Reporte se borrarán los filtros seleccionados ¿Desea Continuar?";
$lang["consulta_listado_pendiente"] = "<p style='text-align: center;'><i> Pendiente </i></p>";

$lang["consulta_opciones"] = "Más <br /> Opciones";
$lang["consulta_volver"] = "Volver al Reporte";

$lang["consulta_opciones_detalle"] = " <i class='fa fa-search' aria-hidden='true'></i> Detalle <br /> Lead";
$lang["consulta_opciones_documentos"] = " <i class='fa fa-camera' aria-hidden='true'></i> Ver <br /> Documentos";
$lang["consulta_opciones_empresa"] = "<i class='fa fa-id-card-o' aria-hidden='true'></i> Detalle <br /> Campaña";
$lang["consulta_opciones_ejecutivo"] = "<i class='fa fa-user-o' aria-hidden='true'></i> Detalle <br /> Agente";
$lang["consulta_opciones_observaciones"] = "<i class='fa fa-comment-o' aria-hidden='true'></i> Observaciones <br /> al Lead";
$lang["consulta_opciones_comentarios_excepcion"] = "<i class='fa fa-signal' aria-hidden='true'></i> Detalle <br /> Avance Agente";
$lang["consulta_opciones_seguimiento"] = "<i class='fa fa-road' aria-hidden='true'></i> Detalle <br /> Seguimiento";

?>
