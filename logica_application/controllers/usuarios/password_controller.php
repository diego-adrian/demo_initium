<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR DE LOGUEO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion de la autenticacion de usuarios 
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Password_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 0;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
        
    public function CambiarPass_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
		
        $this->load->view('usuarios/view_usuario_pass', $data);
        
    }
    
    public function CambiarPass_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $usuario_codigo = $_SESSION["session_informacion"]["codigo"];
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        $password_anterior = $this->input->post('password_anterior', TRUE);
        $password_nuevo = $this->input->post('password_nuevo', TRUE);
        $password_repetir = $this->input->post('password_repetir', TRUE);
        
        if ($password_nuevo != $password_repetir)
        {
            js_error_div_javascript($this->lang->line('PasswordNoCoincide'));
            exit();
        }
        
        if ($password_anterior == $password_nuevo)
        {
            js_error_div_javascript($this->lang->line('PasswordRepetido'));
            exit();
        }
        
        // PRIMERO SE VERIFICA SI LA CONTRASEÑA ANTERIOR ES CORRECTA
        
            $password_anterior = sha1(sha1($password_anterior));
        
            $arrResultado = $this->mfunciones_logica->VerificaPass($usuario_codigo, $password_anterior);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            if (!isset($arrResultado[0]))
            {
                js_error_div_javascript($this->lang->line('PasswordAnterior'));
                exit();
            }
        
        // Se pregunta si el tiempo mínimo de cambio de contraseña permite la renovación
            
            $arrResultado2 = $this->mfunciones_logica->ObtenerDatosUsuario($usuario_codigo);        
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);
            
            // Si la contraseña fue restablecida, puede ser cambiada    0 = No fue restablecida     1 = Si fue restablecida
            if($arrResultado2[0]['usuario_password_reset'] == 0)
            {
                if($this->mfunciones_generales->getDiasPassword($arrResultado2[0]['usuario_fecha_ultimo_password'], 'min') == 0)
                {                    
                    // Obtener la duración mínima de la contraseña
                    $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
                    
                    js_error_div_javascript($this->lang->line('PasswordNoRenueva') . $arrResultado3[0]['conf_duracion_min'] . ' día(s).');
                    exit();
                }
            }
            
        // Se verifica que la contraseña cumpla con los requisitos mínimos
        if($this->mfunciones_generales->VerificaFortalezaPassword($password_nuevo) != 'ok')
        {
            js_error_div_javascript($this->mfunciones_generales->VerificaFortalezaPassword($password_nuevo));
            exit();
        }
            
        $usuario_pass = sha1(sha1($password_nuevo));
        
        $this->mfunciones_logica->RenovarPass($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo);
        
        // AUDITORIA
            $auditoria_detalle = "Se modificó el password del usuario $nombre_usuario con código: $usuario_codigo";
            $auditoria_tabla = "usuarios";
            $this->mfunciones_generales->Auditoria($auditoria_detalle, $auditoria_tabla);
        
        $data["ruta_redireccion"] = 'Menu/Principal';
        
        // Actualizar el menu
        js_invocacion_javascript("$('#divCabeceraGeneral').load('Usuario/RenovarMenu');");
        
        $this->load->view('inicio/view_msj_ok', $data);        
    }
    
    public function RecargarMenu() {
        
        $this->lang->load('general', 'castellano');
        $this->lang->load('estructura_pagina', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Obtener la fecha del ultimo pass
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosUsuario($_SESSION["session_informacion"]["codigo"]);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
        $_SESSION["session_informacion"]["dias_cambio_password"] = $this->mfunciones_generales->getDiasPassword($arrResultado[0]['usuario_fecha_ultimo_password'], 'max');
        $_SESSION["session_informacion"]["password_reset"] = $arrResultado[0]["usuario_password_reset"];
        
        $data["arrRespuesta"] = $this->mfunciones_generales->ListadoMenu($_SESSION["session_informacion"]["rol_codigo"]);
        
        $this->load->view('login/view_menu_principal', $data);
        
    }
}
?>