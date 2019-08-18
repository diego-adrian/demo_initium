<?php
/**
 * Created by PhpStorm.
 * User: Itancara
 * Date: 15/08/2019
 * Time: 8:58
 */

class Form_dinamico extends CI_Model
{
  //private $Consulta_soapx;
  private $cache_dias_laborales = null;
  
  function __construct()
  {
    parent::__construct();
    $CI = &get_instance();
    $CI->load->library('soap/Consulta_soap');
    $this->consulta_soap = $CI->consulta_soap;
  }
  
  public function listadoFormularios()
  {
      try {
        $sql = "SELECT * FROM usuarios";
      
        $consulta = $this->db->query($sql);
      
        $listaResultados = $consulta->result_array();
      } catch (Exception $e) {
        js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
        exit();
      }
    
      return $listaResultados;
  }
}