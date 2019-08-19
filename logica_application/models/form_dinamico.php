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
  
  public function listadoFormularios($idformulario = null)
  {
      try {
        if (isset($idformulario)) {
          $sql = "
          SELECT * 
          FROM formulario
          WHERE id = $idformulario";
          
        } else {
          $sql = "
          SELECT *
          FROM formulario";
        }
      
        $consulta = $this->db->query($sql);
        $listaResultados = $consulta->result();
      } catch (Exception $e) {
        js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
        exit();
      }
    
      return $listaResultados;
  }

  public function listadoComponentesFormulario ($idformulario) {
      try {
        $sql = "
          SELECT c.*
          FROM formulario f
          INNER JOIN componente c ON c.fid_formulario = f.id
          WHERE id = $idformulario";          
        $consulta = $this->db->query($sql);
        $listaResultados = $consulta->result();
      } catch (Exception $e) {
        js_error_div_javascript($e . "<span style='font-size:3.5mm;'>Ocurrio un evento inesperado, intentelo mas tarde.</span>");
        exit();
      }
      return $listaResultados;
  }

  public function crearFormulario ($formulario) {
    try {
      $this->db->update('formulario', $formulario);
      return  $insert_id = $this->db->insert_id();
    } catch (Exception $e) {
      js_error_div_javascript($e . "<span style='font-size:3.5mm;'>Ocurrio un evento inesperado, intentelo mas tarde.</span>");
      exit();
    } 
  }

  public function actualizarFormulario ($idFormulario, $formulario, $componentes) {
    try { 
      $this->db->where('fid_formulario', $idFormulario);
      $this->db->delete('componente');
      $this->db->where('id', $idFormulario);
      $this->db->update('formulario', $formulario);
      $this->db->insert_batch('componente', $componentes);
    } catch (Exception $e) {
      js_error_div_javascript($e . "<span style='font-size:3.5mm;'>Ocurrio un evento inesperado, intentelo mas tarde.</span>");
      exit();
    } 
  }

  public function borrarFormulario ($idFormulario) {
    try {
      $this->db->where('fid_formulario', $idFormulario);
      $this->db->delete('componente');
      $this->db->where('id', $idFormulario);
      $this->db->delete('formulario');
    } catch (Exception $e) {
      js_error_div_javascript($e . "<span style='font-size:3.5mm;'>Error al eliminar el formulario.</span>");
      exit();
    }
  }
}