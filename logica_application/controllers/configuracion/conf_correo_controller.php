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
 * Controlador para la administracion de la configuración de Correo
 * @brief CONTROLADOR DE CONFIGURACIÓN DE CORREO
 * @class Login_controller 
 */
class Conf_correo_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 3;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
    
    public function ConfForm_PlantillaRegistro_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $codigo_plantilla = -1;
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {                
                $item_valor = array(
                    "conf_plantilla_id" => $value["conf_plantilla_id"],
                    "conf_plantilla_nombre" => $value["conf_plantilla_nombre"],
                    "conf_plantilla_titulo_correo" => $value["conf_plantilla_titulo_correo"],
                    "conf_plantilla_contenido" => $value["conf_plantilla_contenido"],
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
                
        $this->load->view('enviar_email/view_plantillas', $data);        
    }
    
    public function ConfForm_PlantillaRegistro_Cargar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // Se captura los valores
        
        $codigo_plantilla = $this->input->post('codigo_plantilla', TRUE);
        
        if($codigo_plantilla == -1)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
		
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_PlantillaCorreo($codigo_plantilla);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {                
                $item_valor = array(
                    "conf_plantilla_id" => $value["conf_plantilla_id"],
                    "conf_plantilla_nombre" => $value["conf_plantilla_nombre"],
                    "conf_plantilla_titulo_correo" => $value["conf_plantilla_titulo_correo"],
                    "conf_plantilla_contenido" => $value["conf_plantilla_contenido"],
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
        
        $this->load->view('enviar_email/view_plantillas_editar', $data);        
    }
    
    public function ConfForm_PlantillaRegistro_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
                
        $codigo_plantilla = $this->input->post('conf_correo_id', TRUE);
        $conf_plantilla_nombre = $this->input->post('conf_plantilla_nombre', TRUE);
        $conf_plantilla_titulo_correo = $this->input->post('conf_plantilla_titulo_correo', TRUE);
        $conf_plantilla_contenido = $this->input->post('conf_plantilla_contenido', TRUE);
                
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');        
        
        if($conf_plantilla_nombre == "" || $conf_plantilla_titulo_correo == "" || $conf_plantilla_contenido == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        $this->mfunciones_logica->UpdateDatosConf_PlantillaCorreo($conf_plantilla_nombre, $conf_plantilla_titulo_correo, $conf_plantilla_contenido, $nombre_usuario, $fecha_actual, $codigo_plantilla);

        // AUDITORIA
        $auditoria_detalle = "Se actualizaron parámetros de la plantilla: $conf_plantilla_nombre | $conf_plantilla_titulo_correo";
        $auditoria_tabla = "conf_correo_plantillas";
        $this->mfunciones_generales->Auditoria($auditoria_detalle, $auditoria_tabla);
        
        $this->ConfForm_PlantillaRegistro_Ver();
    }
}
?>