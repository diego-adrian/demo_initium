<?php
/**
 * @file 
 * Codigo que implementa el controlador para los formularios dinámicos
 * @brief  CONTROLADOR DE FORMULARIOS DINÁMICOS
 * @author JRAD
 * @date 2019
 * @copyright 2019 JRAD
 */
/**
 * Controlador para la gestión de los componentes de los formularios dinámicos
 * @brief CONTROLADOR DE FORMULARIOS DINÁMICOS
 * @class Login_controller 
 */
class form_controller extends MY_Controller {        
        
	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 39;
        
    function __construct() {
      parent::__construct();
      $this->load->model('mfunciones_generales');
      $this->load->model('mfunciones_logica');
      $this->lang->load('general', 'castellano');
      $this->load->library('encrypt');
      $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2019
     */
    public function Formulario_Ver() {
        
        $this->lang->load('general', 'castellano'); // Archivo de Lenguaje
        $this->load->model('mfunciones_generales'); // Funciones Generales
        $this->load->model('mfunciones_logica');    // Capa de Datos
        $this->load->model('form_dinamico');    // Capa de Datos
        $data["arrRespuesta"] = "VALORES";
        // $data["formularios"] = $this->form_dinamico->listadoFormularios();
        
        $this->load->view('form_dinamico/view_form_main', $data);
    }



    public function crearFormulario () {
      $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
      $this->load->view('form_dinamico/view_form_new', $data);
    }

    public function guardarFormulario () {
      $formatted = $this->input->post('json_stringify_formatted');
      $formio = $this->input->post('json_stringify_formio');
      echo($formatted);
      $params = [];
      $this->load->view('form_dinamico/view_form_main', $params);
    }


    public function mostrarFormulario_get ($id) {
      var_dump($id);
      $formulario = $this->form_dinamico->listadoFormularios($id);
      $formulario['componentes'] = $this->form_dinamico->listadoComponentesFormulario($id);
      $this->response($formulario, 200);
    }


    public function actualizarFormulario ($id) {
      $formulario = $this->input->post('formulario');
      if (!isset($formulario) || !isset($id)) {
        $arrError =  array(
          "error" => true,
          "errorMessage" => "falta el parametro formulario.",
          "errorCode" => 101,
          "result" => array(
              "mensaje" => $this->lang->line('IncompletoApp')
          )
        );
        $this->response($arrError, 403);
      }
      $this->form_dinamico->actualizarFormulario($id, $formulario, $formulario['componentes']);
      $respuesta = array('mensaje' => 'Modificacion Correcta.');
      $this->response($respuesta, 200);
    }


    public function borrarFormulario ($id) {
        $this->form_dinamico->borrarFormulario($id);
        $respuesta = array('mensaje' => 'Eliminacion Correcta.');
        $this->response($respuesta, 200);
    }
}
?>