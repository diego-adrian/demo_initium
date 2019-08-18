<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR DE LOGUEO
 * @author JRAD
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion de la autenticacion de usuarios 
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Login_controller extends CI_Controller {
    /**
     * Constructor de Clases
     * @brief CONSTRUCTOR DE CLASE
     * @author JRAD
     */
    function __construct() {
        parent::__construct();
        session_start();		
    }
	
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     */
    
    public function CargarMenuPrincipal() {
		
        $this->lang->load('general', 'castellano');        
        $this->load->model('mfunciones_logica');
                
        $this->load->view('inicio/view_pantalla_inicial');
        
        if(!isset($_SESSION["conf_tiempo_bloqueo"]))
        {
            js_invocacion_javascript("window.location.reload();");
        }
    }
    
    public function Formulario_Login() {
        session_destroy();
        session_start();
        $this->lang->load('estructura_pagina', 'castellano');               
        $this->load->library('FormularioValidaciones/login/Formulario_login');
        
        // Captcha
        $this->load->library('LibreriasPersonalizadas/Construccion_captcha');
        $strTextoCaptcha = "";
        $imgTagHtml = $this->construccion_captcha->GetCaptcha($strTextoCaptcha);
        
        $data["imgTagHtml"] = $imgTagHtml;
        $_SESSION["session_captcha"] = array("Login" => $strTextoCaptcha);
        
        $this->formulario_login->DefinicionValidacionFormulario();
        $data["arrCajasHTML"] = $this->formulario_login->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_login->GeneraValidacionJavaScript();
        $this->load->view('login/view_login', $data);
    }
    /**
     * Cierra la session de usuario
     * @brief CERRAR SESION
     * @author JRAD  
     */
    public function Cerrar_Login() {
		$this->load->model('mfunciones_generales');
			// Se guarda el registro del acceso			
			$this->mfunciones_generales->AuditoriaAcceso('Logout');
        session_destroy();
        js_invocacion_javascript("location.reload();");
    }
    
    public function Recargar_captcha() {
        $this->load->model('mfunciones_generales');
        $this->load->library('LibreriasPersonalizadas/Construccion_captcha');

        $strTextoCaptcha = "";
        $imgTagHtml = $this->construccion_captcha->GetCaptcha($strTextoCaptcha);
        //$data["imgTagHtml"] = $imgTagHtml;
        $_SESSION["session_captcha"] = array("Login" => $strTextoCaptcha);
        print_r($imgTagHtml);
        //$this->load->view('login/view_login', $data);
    }
   
    /**
     * Valida la autenticacion de usuario
     * @brief VALIDAR LOGIN
     * @author JRAD   
     */
    public function Formulario_Autenticacion() {
        if (!isset($_SESSION)) {
            session_start();
        }        
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_login');
        
        if (!isset($_SESSION)) {
            session_start();
        }
        $arrCodigoCaptcha = $_SESSION["session_captcha"];

        if (!($_POST["imagen"] == $arrCodigoCaptcha["Login"])) {
            
            js_invocacion_javascript("recargar_captcha('imgCaptchaLogin')");
            js_error_div_javascript("EL CÓDIGO DE LA IMÁGEN NO ES CORRECTO.");
            return;
        }
        
        $usu_login = $_POST["cuenta"];
        $usu_password = $_POST["password"];
        
        $usu_password = sha1(sha1($usu_password));
        
        $arrResultado = $this->mfunciones_login->
        ObtenerLista_AutenticacionPrincipal($usu_login, $usu_password);
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                
        if (!isset($arrResultado[0]))
        {
            js_error_div_javascript("CUENTA DE USUARIO O CONTRASEÑA INCORRECTO");
			
            exit();
        }
        
        $usuario_codigo = $arrResultado[0]['usuario_activo'];
        
        if ($usuario_codigo == 0)
        {
            js_error_div_javascript("LA CUENTA NO ESTA ACTIVA, POR FAVOR COMUNIQUESE CON EL ADMINISTADOR DEL SISTEMA");
			
            exit();
        }
        
        $usuario_codigo = $arrResultado[0]['usuario_id'];
        
        $nombre_completo = $arrResultado[0]['nombre_completo'];
        $nombre_rol = $this->mfunciones_generales->getRolUsuario($arrResultado[0]["usuario_rol"]);
        $usuario_login = $arrResultado[0]['usuario_user'];
        
        $fecha_ultimo_acceso = $this->mfunciones_generales->getUltimoAcceso($arrResultado[0]['usuario_fecha_ultimo_acceso']);
        $dias_cambio_password =$this->mfunciones_generales->getDiasPassword($arrResultado[0]['usuario_fecha_ultimo_password'], 'max');
                
        $_SESSION["session_informacion"]["codigo"] = $usuario_codigo;
        $_SESSION["session_informacion"]["nombre"] = $nombre_completo;
        $_SESSION["session_informacion"]["rol"] = $nombre_rol;
        $_SESSION["session_informacion"]["rol_codigo"] = $arrResultado[0]["usuario_rol"];
        $_SESSION["session_informacion"]["login"] = $usuario_login;
        
        $_SESSION["session_informacion"]["fecha_ultimo_acceso"] = $fecha_ultimo_acceso;
        $_SESSION["session_informacion"]["dias_cambio_password"] = $dias_cambio_password;
        
        $_SESSION["session_informacion"]["password_reset"] = $arrResultado[0]["usuario_password_reset"];
        
        // Se establece el tiempo máximo de inactividad
        
        $_SESSION["conf_tiempo_bloqueo"] = time();
        
        // Se registra la fecha del Login        
        $this->mfunciones_generales->UsuarioActualizarFechaLogin($usuario_codigo);
        
        // Se guarda el registro del acceso
        $this->mfunciones_generales->AuditoriaAcceso('Login');
		
        $this->Menu_CargarInformacionUsuarioMenues();
    }
    /**
     * Muestra los Menues de usuario
     * @brief VALIDAR LOGIN
     * @author JRAD   
     */
    public function Menu_CargarInformacionUsuarioMenues() {
        $this->lang->load('estructura_pagina', 'castellano');
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        
        $data["arrRespuesta"] = $this->mfunciones_generales->ListadoMenu($_SESSION["session_informacion"]["rol_codigo"]);
        
        $this->load->view('login/view_menu_principal', $data);
        $this->load->view('inicio/view_pantalla_inicial');
        
    }    
}
?>