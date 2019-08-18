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
class Detalle_controller extends MY_Controller {

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
    
    public function UsuarioDetalle() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // 0=Insert    1=Update

        if(!isset($_POST['codigo_usuario']))
        {
            js_error_div_javascript($this->lang->line('NoAutorizado'));
            exit();
        }
        else
        {
            if(!$this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_USUARIO))
            {
                js_error_div_javascript($this->lang->line('NoAutorizadoPerfil'));
                exit();
            }
            else 
            {
            
                $tipo_codigo = $this->input->post('tipo_codigo', TRUE);
                $codigo_usuario = $this->input->post('codigo_usuario', TRUE);

                $arrResultado = $this->mfunciones_logica->ObtenerDetalleDatosUsuario($tipo_codigo, $codigo_usuario);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

                if (isset($arrResultado[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado as $key => $value) 
                    {
                        $item_valor = array(
                            "usuario_codigo" => $value["usuario_id"],
                            "usuario_user" => $value["usuario_user"],
                            "usuario_fecha_creacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["usuario_fecha_creacion"]),
                            "estructura_agencia_nombre" => $value["estructura_agencia_nombre"],
                            "estructura_regional_nombre" => $value["estructura_regional_nombre"],
                            "usuario_nombres" => $value["usuario_nombres"],
                            "usuario_app" => $value["usuario_app"],
                            "usuario_apm" => $value["usuario_apm"],
                            "usuario_email" => $value["usuario_email"],
                            "usuario_telefono" => $value["usuario_telefono"],
                            "usuario_direccion" => $value["usuario_direccion"],
                            "usuario_rol" => $value["usuario_rol"],
                            "usuario_rol_detalle" => $this->mfunciones_generales->getRolUsuario($value["usuario_rol"]),
                            "usuario_activo" => $value["usuario_activo"],
                            "usuario_activo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["usuario_activo"], 'activo'),                        
                        );
                        $lst_resultado[$i] = $item_valor;

                        $i++;
                    }
                } 
                else 
                {
                    $lst_resultado[0] = $arrResultado;
                }

                $data["arrRespuesta"] = $lst_resultado;

                $this->load->view('usuarios/view_usuario_detalle', $data);
            }
        }
    }
}
?>