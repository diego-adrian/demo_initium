<?php

/**
 * Description of Fomulario_Inicio
 *
 * @author Joel Aliaga
 */
class Formulario_logica_general {

    private $arr_validacion;
    private $arr_title_tooltip;

    public function __construct() {
        $CI = & get_instance();
        $CI->load->library('FormularioValidaciones/Formulario_campos');        
        $this->arr_validacion = array();
        $this->arr_title_tooltip = array();
        $this->formulario_campos = $CI->formulario_campos;
    }

    public function DefinicionValidacionFormulario() {

        // EMPRESA
        
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nit', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["empresa_nit"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_legal', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_legal"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_fantasia', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_fantasia"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_establecimiento', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_establecimiento"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_denominacion_corta', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_denominacion_corta"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_nombre_referencia', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_nombre_referencia"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_ha_desde', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["empresa_ha_desde"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_ha_hasta', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["empresa_ha_hasta"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_dato_contacto', LETRAS_NUMEROS, 'MAX(200)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_dato_contacto"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_email', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_email"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_calle', LETRAS_NUMEROS, 'MAX(55)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_calle"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_numero', NUMEROS, 'MAX(6)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["empresa_numero"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_direccion_literal', LETRAS_NUMEROS, 'MAX(95)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_direccion_literal"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('empresa_info_adicional', LETRAS_NUMEROS, 'MAX(55)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["empresa_info_adicional"] = clone $this->formulario_campos;
        
        // CAMPAÑAS
        
        $this->formulario_campos->CargarOpcionesValidacion('camp_nombre', LETRAS_NUMEROS, 'MAX(45)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["camp_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_desc', LETRAS_NUMEROS, 'MAX(255)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["camp_desc"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_plazo', NUMEROS, 'MAX(11)|REQUERIDO', '');
        $arr_validacion["camp_plazo"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('camp_fecha_inicio', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["camp_fecha_inicio"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_monto_oferta', NUMEROS, 'MAX(11)|REQUERIDO', '');
        $arr_validacion["camp_monto_oferta"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('campana_tasa', NUMEROS, 'MAX(19)|REQUERIDO', '.');
        $arr_validacion["camp_tasa"] = clone $this->formulario_campos;
        

        // QR EXTERNO
        
        $this->formulario_campos->CargarOpcionesValidacion('qr_nombre', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '°|&|,|-|/|:|_|.|(|)|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["qr_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('qr_empresa', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '°|&|,|-|/|:|_|.|(|)|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["qr_empresa"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_email', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '.|@|-|_');
        $arr_validacion["qr_correo"] = clone $this->formulario_campos;

        // EXTERNO
        
        $this->formulario_campos->CargarOpcionesValidacion('contraseña de usuario', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["imagen"] = clone $this->formulario_campos;
                
        // USUARIOS
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_user', LETRAS_NUMEROS, 'SINACENTO|SINESPACIO|REQUERIDO|MAX(100)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-');
        $arr_validacion["usuario_user"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_nombres', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '-|.|:|\'');
        $arr_validacion["usuario_nombres"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_app', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '-|.|:|\'');
        $arr_validacion["usuario_app"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_apm', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '-|.|:|\'');
        $arr_validacion["usuario_apm"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_email', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '.|@|-|_');
        $arr_validacion["usuario_email"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_telefono', NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["usuario_telefono"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('usuario_direccion', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)');
        $arr_validacion["usuario_direccion"] = clone $this->formulario_campos;

        // pass
        $this->formulario_campos->CargarOpcionesValidacion('password_anterior', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["password_anterior"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('password_nuevo', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["password_nuevo"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('password_repetir', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["password_repetir"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - CREDENCIALES
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_long_min', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_long_min"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_long_max', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_long_max"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_duracion_min', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_duracion_min"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_duracion_max', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_duracion_max"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_tiempo_bloqueo', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_credenciales_tiempo_bloqueo"] = clone $this->formulario_campos;
                
        $this->formulario_campos->CargarOpcionesValidacion('conf_credenciales_defecto', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["conf_credenciales_defecto"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_ejecutivo_ic', NUMEROS, 'MAX(5)|REQUERIDO');
        $arr_validacion["conf_ejecutivo_ic"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - ENVÍO DE CORREOS
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_host', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO|SINESPACIO', '-|/|:|_|.');
        $arr_validacion["conf_correo_smtp_host"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_port', NUMEROS, 'MAX(10)|REQUERIDO|SINESPACIO');
        $arr_validacion["conf_correo_smtp_port"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_user', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-');
        $arr_validacion["conf_correo_smtp_user"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_smtp_pass', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["conf_correo_smtp_pass"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - PLANTILLA DE CORREOS
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_plantilla_nombre', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '.|:|_');
        $arr_validacion["conf_plantilla_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_plantilla_titulo_correo', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '.|:|_|-');
        $arr_validacion["conf_plantilla_titulo_correo"] = clone $this->formulario_campos;
        
        // CONFIGURACIÓN - GENERAL
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_general_key_google', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '.|:|_|-');
        $arr_validacion["conf_general_key_google"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_desde1', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_desde1"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_hasta1', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_hasta1"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_desde2', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_desde2"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('conf_atencion_hasta2', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["conf_atencion_hasta2"] = clone $this->formulario_campos;
        
        // AUDITORÍA

        $this->formulario_campos->CargarOpcionesValidacion('fecha_inicio', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["fecha_inicio"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('fecha_fin', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["fecha_fin"] = clone $this->formulario_campos;
        
        // CATÁLOGO
		
        $this->formulario_campos->CargarOpcionesValidacion('catalogo_codigo', NUMEROS, 'MAX(4)|REQUERIDO|SINESPACIO');
        $arr_validacion["catalogo_codigo"] = clone $this->formulario_campos;
		
        $this->formulario_campos->CargarOpcionesValidacion('catalogo_descripcion', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '.|:|_');
        $arr_validacion["catalogo_descripcion"] = clone $this->formulario_campos;
        
        // ESTRUCTURA
		
        $this->formulario_campos->CargarOpcionesValidacion('estructura_nombre', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["estructura_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('estructura_detalle', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["estructura_detalle"] = clone $this->formulario_campos;

        // DOCUMENTOS
		
        $this->formulario_campos->CargarOpcionesValidacion('documento_nombre', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', ',|-|/|:|_|.');
        $arr_validacion["documento_nombre"] = clone $this->formulario_campos;
        
        // SOLICITUD DE AFILIACIÓN
                
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_nombre_persona', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["solicitud_nombre_persona"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_nombre_empresa', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO', '°|&|,|-|/|:|_|.|(|)|@|#|$|%|&|/|¿|?|¡|+|.|*');
        $arr_validacion["solicitud_nombre_empresa"] = clone $this->formulario_campos;
                
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_telefono', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', ',|-|/|:|_|.|(|)');
        $arr_validacion["solicitud_telefono"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_email', LETRAS_NUMEROS, 'MAX(50)|REQUERIDO|SINESPACIO', '-|/|:|_|.|(|)|@');
        $arr_validacion["solicitud_email"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_direccion_literal', LETRAS_NUMEROS, 'MAX(250)|REQUERIDO', ',|-|/|:|_|.|(|)|°');
        $arr_validacion["solicitud_direccion_literal"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('solicitud_observacion', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', '-|/|:|_|.|(|)', CAJATEXTAREA);
        $arr_validacion["solicitud_observacion"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_nit', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["solicitud_nit"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_fecha_visita', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["solicitud_fecha_visita"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('solicitud_otro_detalle', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '-|/|:|_|.|(|)');
        $arr_validacion["solicitud_otro_detalle"] = clone $this->formulario_campos;
        
        // FLUJO DE TRABAJO
        
        $this->formulario_campos->CargarOpcionesValidacion('etapa_nombre', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', '-|/|:|_|.|(|)');
        $arr_validacion["etapa_nombre"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('etapa_detalle', LETRAS_NUMEROS, 'MAX(290)|REQUERIDO', '-|/|:|_|.|(|)|,', CAJATEXTAREA);
        $arr_validacion["etapa_detalle"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('etapa_tiempo', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["etapa_tiempo"] = clone $this->formulario_campos;
        
        // PROSPECTO
        
        $this->formulario_campos->CargarOpcionesValidacion('prospecto_justificar', LETRAS_NUMEROS, 'MAX(190)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["prospecto_justificar"] = clone $this->formulario_campos;
        
        // BANDEJAS
        
        $this->formulario_campos->CargarOpcionesValidacion('antecedentes_detalle', LETRAS_NUMEROS, 'MAX(290)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["antecedentes_detalle"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('excepcion_detalle', LETRAS_NUMEROS, 'MAX(290)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["excepcion_detalle"] = clone $this->formulario_campos;
        
        // -- Evaluación Legal
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_denominacion_comercial', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_denominacion_comercial"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_razon_social', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_razon_social"] = clone $this->formulario_campos;
        
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_nit', NUMEROS, 'MAX(20)|REQUERIDO|SINESPACIO', '');
        $arr_validacion["evaluacion_nit"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_representante_legal', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_representante_legal"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_actividad_principal', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_actividad_principal"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_actividad_secundaria', LETRAS_NUMEROS, 'MAX(90)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_actividad_secundaria"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_ci_fecnac', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_ci_fecnac"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_ci_titular', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_ci_titular"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_numero_testimonio', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_numero_testimonio"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_duracion_empresa', LETRAS_NUMEROS, 'MAX(20)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_duracion_empresa"] = clone $this->formulario_campos;

        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_testimonio', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_testimonio"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_objeto_constitucion', LETRAS_NUMEROS, 'MAX(90)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["evaluacion_objeto_constitucion"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_testimonio_poder', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_testimonio_poder"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_numero_testimonio_poder', LETRAS_NUMEROS, 'MAX(40)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,');
        $arr_validacion["evaluacion_numero_testimonio_poder"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fundaempresa_emision', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fundaempresa_emision"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fundaempresa_vigencia', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fundaempresa_vigencia"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_resultado', LETRAS_NUMEROS, 'MAX(490)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|°|"|\'|(|)|,', CAJATEXTAREA);
        $arr_validacion["evaluacion_resultado"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_solicitud', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_solicitud"] = clone $this->formulario_campos;
        
        $this->formulario_campos->CargarOpcionesValidacion('evaluacion_fecha_evaluacion', FECHA, 'SINACENTO|SINESPACIO|MAX(10)', '', CAJAFECHA);
        $arr_validacion["evaluacion_fecha_evaluacion"] = clone $this->formulario_campos;
        
        $this->arr_validacion = $arr_validacion;
    }

    public function DefinicionTitleToolTip() {
        $arr_title_tooltip["hoja_ruta"] = "Indique el N° de Hoja de Ruta";

        $this->arr_title_tooltip = $arr_title_tooltip;
    }

    public function ConstruccionCajasFormulario($arrValoresPorDefecto) {
        $i = 0;
        $arrCajasFormulario = array();
        //print_r($this->arr_validacion);
        foreach ($this->arr_validacion as $campo => $objvalidacion) {

            if ($objvalidacion->TIPO_CAJA_TEXTO === CAJATEXTO) {
                $arrCajasFormulario[$i++] = $campo;
            }
        }
        
        foreach ($arrCajasFormulario as $id_caja) {
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            if (isset($this->arr_validacion[$id_caja])) {
                $validacion = $this->arr_validacion[$id_caja];

                if (isset($this->arr_title_tooltip[$id_caja])) {
                    $titleTooltip = $this->arr_title_tooltip[$id_caja];
                } else {
                    $titleTooltip = '';
                }
                $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaHtml($id_caja, 'MAX(' . $validacion->CARACTERES_MAXIMO . ')', $strValorCaja, $validacion->CLASES_CSS, $titleTooltip);
            }
        }
        
        $id_caja = "password_anterior";
        $validacion = $this->arr_validacion[$id_caja];
        $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaPasswordHtml($id_caja, 'SI_ENTER|MAX(' . $validacion->CARACTERES_MAXIMO . ')');
        
        $id_caja = "password_nuevo";
        $validacion = $this->arr_validacion[$id_caja];
        $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaPasswordHtml($id_caja, 'SI_ENTER|MAX(' . $validacion->CARACTERES_MAXIMO . ')');
        
        $id_caja = "password_repetir";
        $validacion = $this->arr_validacion[$id_caja];
        $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaPasswordHtml($id_caja, 'SI_ENTER|MAX(' . $validacion->CARACTERES_MAXIMO . ')');
        
        // QR EXTERNO
        
        $id_caja = "qr_ciudad";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "La Paz", "campoDescrip" => "La Paz"),
            array("id" => "Santa Cruz", "campoDescrip" => "Santa Cruz"),
            array("id" => "Cochabamba", "campoDescrip" => "Cochabamba"),
            array("id" => "Tarija", "campoDescrip" => "Tarija"),
            array("id" => "Chuquisaca", "campoDescrip" => "Chuquisaca"),
            array("id" => "Oruro", "campoDescrip" => "Oruro"),
            array("id" => "Potosi", "campoDescrip" => "Potosi"),
            array("id" => "Beni", "campoDescrip" => "Beni"),
            array("id" => "Pando", "campoDescrip" => "Pando")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        
        $id_caja = "conf_credenciales_req_upper";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "0", "campoDescrip" => "No"),
            array("id" => "1", "campoDescrip" => "Si")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_credenciales_req_num";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "0", "campoDescrip" => "No"),
            array("id" => "1", "campoDescrip" => "Si")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_credenciales_req_esp";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "0", "campoDescrip" => "No"),
            array("id" => "1", "campoDescrip" => "Si")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_correo_protocol";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "mail", "campoDescrip" => "MAIL"),
            array("id" => "sendmail", "campoDescrip" => "SENDMAIL"),
            array("id" => "smtp", "campoDescrip" => "SMTP")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_correo_mailtype";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "text", "campoDescrip" => "TEXT"),
            array("id" => "html", "campoDescrip" => "HTML")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "conf_correo_charset";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "utf-8", "campoDescrip" => "UTF-8"),
            array("id" => "iso-8859-1", "campoDescrip" => "ISO-8859-1"),
            array("id" => "us-ascii", "campoDescrip" => "US-ASCII")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "catalogo_tipo_codigo";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "TPS", "campoDescrip" => "TPS"),
            array("id" => "RUB", "campoDescrip" => "RUB"),
            array("id" => "PEC", "campoDescrip" => "PEC"),
            array("id" => "MCC", "campoDescrip" => "MCC"),
            array("id" => "MCO", "campoDescrip" => "MCO"),
            array("id" => "DEP", "campoDescrip" => "DEP"),
            array("id" => "CIU", "campoDescrip" => "CIU"),
            array("id" => "ZON", "campoDescrip" => "ZON"),
            array("id" => "TPC", "campoDescrip" => "TPC")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);
        
        $id_caja = "antecedentes_resultado";
        $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
        $arrayTipoContratacion = Array(
            array("id" => "1", "campoDescrip" => "Aprobar Pre-Afiliación"),
            array("id" => "2", "campoDescrip" => "Rechazar Pre-Afiliación")
        );
        $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', '', $strValorCaja);

            // -- Evaluación Legal
            
            $id_caja = "evaluacion_doc_nit";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'1\')"');
            
            $id_caja = "evaluacion_doc_certificado";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'2\')"');
            
            $id_caja = "evaluacion_doc_ci";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'3\')"');
            
            $id_caja = "evaluacion_doc_test";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'4\')"');
            
            $id_caja = "evaluacion_doc_poder";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'5\')"');
            
            $id_caja = "evaluacion_doc_funde";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "No Aplica"),
                array("id" => "2", "campoDescrip" => "Adjunto en File"),
                array("id" => "3", "campoDescrip" => "Requisito con Excepción")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja, 'onchange="VerTablaEvaluacion(this, \'6\')"');
            
            $id_caja = "evaluacion_ci_pertenece";
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            $arrayTipoContratacion = Array(
                array("id" => "1", "campoDescrip" => "Propietario"),
                array("id" => "2", "campoDescrip" => "Representante Legal")
            );
            $arr_formulario_cajas[$id_caja] = html_select($id_caja, $arrayTipoContratacion, 'id', 'campoDescrip', 'SINSELECCIONAR', $strValorCaja);

            /////////////////////////////
        
        $i = 0;
        $arrCajasFormulario = array();
        foreach ($this->arr_validacion as $campo => $objvalidacion) {
            if ($objvalidacion->TIPO_CAJA_TEXTO == CAJAFECHA) {
                $arrCajasFormulario[$i++] = $campo;
            }
        }

        //$arrCajasFormulario = array("djbr_fecha","djbr_fecha_incom","djbr_fecha_indep","persona_fecha_nacimiento","mov_fecha_inicio","mov_fecha_final","mov_fecha_ingreso");
        //$id_caja = "persona_fecha_nacimiento";
        foreach ($arrCajasFormulario as $id_caja) {
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            if (isset($this->arr_validacion[$id_caja])) {
                $validacion = $this->arr_validacion[$id_caja];
                $arr_formulario_cajas[$id_caja] = html_caja_fecha($id_caja, 'DISABLED', $strValorCaja, $validacion->CLASES_CSS);
            }
        }


        $i = 0;
        $arrCajasFormulario = array();
        foreach ($this->arr_validacion as $campo => $objvalidacion) {
            if ($objvalidacion->TIPO_CAJA_TEXTO == CAJATEXTAREA) {
                $arrCajasFormulario[$i++] = $campo;
            }
        }
        //$arrCajasFormulario = array("cuentaDescribePercibePension","cuentaDescribeSalarioDocencia");
        //$id_caja = "base_legal_descripcion";
        foreach ($arrCajasFormulario as $id_caja) {
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            if (isset($this->arr_validacion[$id_caja])) {
                $validacion = $this->arr_validacion[$id_caja];
                $arr_formulario_cajas[$id_caja] = html_textarea($id_caja, '', $strValorCaja, $validacion->CLASES_CSS, '', $validacion->CARACTERES_MAXIMO);
            }
        }

        return $arr_formulario_cajas;
    }

    public function GeneraValidacionJavaScript() {
        return LibreriaUsir_GeneracionJqueryValidate_ParaCajasFormulario($this->arr_validacion);
    }

    public function ValidarValoresLadoServidor($arrValoresPost, $strNombreDivError, &$arrValoresRetorno) {
        $arr_validacion = $this->arr_validacion;
        LibreriaUsir_ValidarValoresLadoServidor($arr_validacion, $arrValoresPost, $strNombreDivError, $arrValoresRetorno);
    }

}

?>
