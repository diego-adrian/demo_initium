<?php
/**
 * @file 
 * Codigo que implementa el controlador para la contruccion de la pagina
 * @brief  CONTROLADOR DE LA ESTRUCTURA DE PAGINA
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador que implementa la estructura general de la pagina 
 * @brief CONTROLADOR DEL ESTRUCTURA DE PAGINA
 * @class Estructura_controller 
 */
class Estructura_controller extends CI_Controller {
    /**
     * Constructor de Clases
     * @brief CONSTRUCTOR DE CLASE
     * @author JRAD
     * @date Mar 21, 2014     
     */
    function __construct() {
        parent::__construct();
        session_start();
    }
    
    /**
     * Carga y construye distintas vistas para la construccion de la pagina
     * @brief CARGAR ESTRUCTURA PRINCIPAL
     * @author JRAD
     * @date Mar 21, 2014     
     */
    public function Pagina_ContruccionPrincipal() {
        $this->lang->load('estructura_pagina', 'castellano');   
        $this->lang->load('general', 'castellano');
        $this->load->library('FormularioValidaciones/login/Formulario_login');
        $this->formulario_login->DefinicionValidacionFormulario();
        $data["arrCajasHTML"] = $this->formulario_login->ConstruccionCajasFormulario(array());
        $data["strValidacionJqValidate"] = $this->formulario_login->GeneraValidacionJavaScript();
                
        $this->load->view('estructura_pagina/view_cabecera_pagina');
        
        
        $this->load->view('estructura_pagina/view_banner_general');          
        if (!isset($_SESSION["session_informacion"])) {
            session_destroy();
            $this->load->view('estructura_pagina/view_pagina_bienvenida', $data);            
        }        
        $this->load->view('estructura_pagina/view_pagina_pie');       
    }    
    
    
    
   
}
?>