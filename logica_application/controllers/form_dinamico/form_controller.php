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
        
        // ----------- FORMULARIOS DINÁMICOS INICIO -----------

        // -- Contenido

        // ----------- FORMULARIOS DINÁMICOS FIN -----------

        $data["arrRespuesta"] = "VALORES";
        
        $this->load->view('form_dinamico/view_form_main', $data);        
    }
}
?>