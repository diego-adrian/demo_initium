<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR DE CONFIGURACIÓN GENERAL
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion de la autenticacion de usuarios 
 * @brief CONTROLADOR DE CONFIGURACIÓN GENERAL
 * @class Login_controller 
 */
class Conf_general_controller extends MY_Controller {        
        
	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 13;
        
    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    public function ConfForm_general_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "conf_general_id" => $value["conf_general_id"],
                    "conf_general_key_google" => $value["conf_general_key_google"],
                    "conf_horario_feriado" => $value["conf_horario_feriado"],
                    "conf_horario_laboral" => $value["conf_horario_laboral"],
                    "conf_atencion_desde1" => $this->mfunciones_generales->getFormatoFechaH_M($value["conf_atencion_desde1"]),
                    "conf_atencion_hasta1" => $this->mfunciones_generales->getFormatoFechaH_M($value["conf_atencion_hasta1"]),
                    "conf_atencion_desde2" => $this->mfunciones_generales->getFormatoFechaH_M($value["conf_atencion_desde2"]),
                    "conf_atencion_hasta2" => $this->mfunciones_generales->getFormatoFechaH_M($value["conf_atencion_hasta2"]),
                    "conf_atencion_dias" => $value["conf_atencion_dias"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        }
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

        $data["arrDias"] = explode(",", $lst_resultado[0]['conf_atencion_dias']);
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);

        $data["arrRespuesta"] = $lst_resultado;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('configuracion/view_general_form', $data);        
    }
    
    public function ConfForm_general_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $conf_general_id = $this->input->post('conf_general_id', TRUE);
        $conf_general_key_google = $this->input->post('conf_general_key_google', TRUE);
        $conf_horario_feriado = $this->input->post('conf_horario_feriado', TRUE);
        $conf_horario_laboral = $this->input->post('conf_horario_laboral', TRUE);
        
        $conf_atencion_desde1 = $this->input->post('conf_atencion_desde1', TRUE);
        $conf_atencion_hasta1 = $this->input->post('conf_atencion_hasta1', TRUE);
        $conf_atencion_desde2 = $this->input->post('conf_atencion_desde2', TRUE);
        $conf_atencion_hasta2 = $this->input->post('conf_atencion_hasta2', TRUE);
        
        $arrDias = $this->input->post('dias_list', TRUE);
                
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrDias);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($conf_general_id == "" || $conf_general_key_google == "" || $conf_atencion_desde1 == "" || $conf_atencion_hasta1 == "" || $conf_atencion_desde2 == "" || $conf_atencion_hasta2 == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        if(strtotime($conf_atencion_desde1) > strtotime($conf_atencion_hasta1))
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios'));
            exit();
        }
        
        if(strtotime($conf_atencion_desde2) > strtotime($conf_atencion_hasta2))
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios'));
            exit();
        }
        
        if(strtotime($conf_atencion_hasta1) > strtotime($conf_atencion_desde2))
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios'));
            exit();
        }
        
        $conf_atencion_dias = '';
        
        if (isset($arrDias[0])) 
        {
            foreach ($arrDias as $key => $value) 
            {
                $conf_atencion_dias .= $value . ',';
            }
        }
        
        $this->mfunciones_logica->UpdateDatosConf_General($conf_general_key_google, $conf_horario_feriado, $conf_horario_laboral, $conf_atencion_desde1, $conf_atencion_hasta1, $conf_atencion_desde2, $conf_atencion_hasta2, $conf_atencion_dias, $fecha_actual, $nombre_usuario, $conf_general_id);

        $this->ConfForm_general_Ver();
    }    
}
?>