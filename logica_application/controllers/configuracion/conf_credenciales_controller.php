<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR DE CONFIGURACIÓN
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion de la autenticacion de usuarios 
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Conf_credenciales_controller extends MY_Controller {        
        
	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 2;
        
    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function menu() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
     
        $this->load->view('configuracion/view_credenciales_menu');
        
    }
    
    public function ConfForm_credenciales_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "conf_credenciales_id" => $value["conf_id"],
                    "conf_credenciales_long_min" => $value["conf_long_min"],
                    "conf_credenciales_long_max" => $value["conf_long_max"],
                    "conf_credenciales_req_upper" => $value["conf_req_upper"],
                    "conf_credenciales_req_num" => $value["conf_req_num"],
                    "conf_credenciales_req_esp" => $value["conf_req_esp"],
                    "conf_credenciales_duracion_min" => $value["conf_duracion_min"],
                    "conf_credenciales_duracion_max" => $value["conf_duracion_max"],
                    "conf_credenciales_tiempo_bloqueo" => $value["conf_tiempo_bloqueo"],
                    "conf_credenciales_defecto" => $value["conf_defecto"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);

        $data["arrRespuesta"] = $lst_resultado;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('configuracion/view_credenciales_form', $data);        
    }
    
    public function ConfForm_credenciales_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $conf_credenciales_id = $this->input->post('conf_credenciales_id', TRUE);
        $conf_credenciales_long_min = $this->input->post('conf_credenciales_long_min', TRUE);
        $conf_credenciales_long_max = $this->input->post('conf_credenciales_long_max', TRUE);
        $conf_credenciales_req_upper = $this->input->post('conf_credenciales_req_upper', TRUE);
        $conf_credenciales_req_num = $this->input->post('conf_credenciales_req_num', TRUE);
        $conf_credenciales_req_esp = $this->input->post('conf_credenciales_req_esp', TRUE);
        $conf_credenciales_duracion_min = $this->input->post('conf_credenciales_duracion_min', TRUE);
        $conf_credenciales_duracion_max = $this->input->post('conf_credenciales_duracion_max', TRUE);
        $conf_credenciales_tiempo_bloqueo = $this->input->post('conf_credenciales_tiempo_bloqueo', TRUE);
        $conf_credenciales_defecto = $this->input->post('conf_credenciales_defecto', TRUE);
                                
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($conf_credenciales_id == "" || $conf_credenciales_long_min == "" || $conf_credenciales_long_max == "" || $conf_credenciales_req_upper == -1 || $conf_credenciales_req_num == -1 || $conf_credenciales_req_esp == -1 || $conf_credenciales_duracion_min == "" || $conf_credenciales_duracion_max == "" || $conf_credenciales_tiempo_bloqueo == "" || $conf_credenciales_defecto == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if($conf_credenciales_tiempo_bloqueo < 1)
        {
            js_error_div_javascript($this->lang->line('FormularioTiempoInvalido'));
            exit();
        }
        
        if($conf_credenciales_long_min == 0 || $conf_credenciales_long_max == 0)
        {
            js_error_div_javascript($this->lang->line('FormularioLongInvalido'));
            exit();
        }
        
        if($conf_credenciales_long_min > $conf_credenciales_long_max)
        {
            js_error_div_javascript($this->lang->line('FormularioLongInvalido2'));
            exit();
        }        
                
        if($this->mfunciones_generales->VerificaFortalezaPassword($conf_credenciales_defecto) != 'ok')
        {
            js_error_div_javascript($this->mfunciones_generales->VerificaFortalezaPassword($conf_credenciales_defecto));
            exit();
        }
        
        $this->mfunciones_logica->UpdateDatosConf_Credenciales($conf_credenciales_long_min, $conf_credenciales_long_max, $conf_credenciales_req_upper, $conf_credenciales_req_num, $conf_credenciales_req_esp, $conf_credenciales_duracion_min, $conf_credenciales_duracion_max, $conf_credenciales_tiempo_bloqueo, $conf_credenciales_defecto, $fecha_actual, $nombre_usuario, $conf_credenciales_id);

        $this->ConfForm_credenciales_Ver();
    }
    
    public function Rol_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerRoles(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "estructura_codigo" => $value["rol_id"],
                    "estructura_nombre" => $value["rol_nombre"],
                    "estructura_detalle" => $value["rol_descirpcion"]
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
        
        $this->load->view('configuracion/view_rol_ver', $data);
        
    }
    
    public function RolForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $estructura_codigo = $_POST['codigo'];
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerRoles($estructura_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "estructura_codigo" => $value["rol_id"],
                        "estructura_nombre" => $value["rol_nombre"],
                        "estructura_detalle" => $value["rol_descirpcion"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;
        }
        else
        {
            $tipo_accion = 0;
            
            // INSERT
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            
            $estructura_codigo = 0;
        }
        
        // Listado de los menús
        $arrMenu = $this->mfunciones_logica->ObtenerDatosMenu();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMenu);                
                
        // Lista los perfiles disponibles

        if (isset($arrMenu[0])) 
        {
            $i = 0;

            foreach ($arrMenu as $key => $value) 
            {
                $item_valor = array(
                    "menu_id" => $value["menu_id"],
                    "menu_nombre" => $value["menu_nombre"],
                    "menu_descripcion" => $value["menu_descripcion"],
                    "menu_asignado" => $this->mfunciones_generales->GetMenuRol($estructura_codigo, $value["menu_id"])
                );
                $lst_resultado2[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado2[0] = $arrMenu;
        }

        $data["arrMenu"] = $lst_resultado2;
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('configuracion/view_rol_form', $data);        
    }
    
    public function Rol_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_nombre = $this->input->post('estructura_nombre', TRUE);
        $estructura_detalle = $this->input->post('estructura_detalle', TRUE);
        
        $arrMenu = $this->input->post('menu_list', TRUE);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMenu);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($estructura_nombre == "" || $estructura_detalle == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            if((int)$estructura_id == 0)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            $this->mfunciones_logica->UpdateRol($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id);
         
        }
        
        if($tipo_accion == 0)
        {
            // INSERT
            
            $estructura_id = $this->mfunciones_logica->InsertRol($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual);
        }
        
        // INSERTAR LOS MENÚS SELECCIONADOS
        
            // 1. Se eliminan los menús del rol
            $this->mfunciones_logica->EliminaMenuRol($estructura_id);
        
            // 2. Se registran los perfiles seleccionados
            
            if (isset($arrMenu[0])) 
            {
                foreach ($arrMenu as $key => $value) 
                {
                    $this->mfunciones_logica->InsertarMenuRol($estructura_id, $value, $nombre_usuario, $fecha_actual);
                }
            }
        
        $this->Rol_Ver();
    }
    
    public function Perfil_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerPerfil(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "estructura_codigo" => $value["perfil_id"],
                    "estructura_nombre" => $value["perfil_nombre"],
                    "estructura_detalle" => $value["perfil_descripcion"]
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
        
        $this->load->view('configuracion/view_perfil_ver', $data);
        
    }
    
    public function PerfilForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $estructura_codigo = $_POST['codigo'];
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerPerfil($estructura_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "estructura_codigo" => $value["perfil_id"],
                        "estructura_nombre" => $value["perfil_nombre"],
                        "estructura_detalle" => $value["perfil_descripcion"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;
        }
        else
        {
            $tipo_accion = 0;
            
            // INSERT
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            
            $estructura_codigo = 0;
        }
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('configuracion/view_perfil_form', $data);
        
    }
    
    public function Perfil_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_nombre = $this->input->post('estructura_nombre', TRUE);
        $estructura_detalle = $this->input->post('estructura_detalle', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($estructura_nombre == "" || $estructura_detalle == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            if((int)$estructura_id == 0)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            $this->mfunciones_logica->UpdatePerfil($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id);
         
        }        
        
        $this->Perfil_Ver();
    }
}
?>