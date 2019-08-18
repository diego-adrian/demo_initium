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
class General_controller extends CI_Controller {
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
     * @brief CARGAR GENERALES
     * @author JRAD
     */
    
    public function CargarMenuPrincipal() {
		
        $this->lang->load('general', 'castellano');        
        $this->load->model('mfunciones_logica');
                
        $this->load->view('inicio/view_pantalla_inicial');
    }
    
    public function PaginaMantenimiento() {
		
        $this->lang->load('general', 'castellano');        
        $this->load->model('mfunciones_logica');
                
        $this->load->view('inicio/view_pantalla_mantenimiento');
    }
}
?>