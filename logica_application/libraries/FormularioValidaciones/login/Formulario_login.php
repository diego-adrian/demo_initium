<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Formulario_login {
    private $arr_validacion;
    public function __construct() {
        $CI = & get_instance();
        $CI->load->library('FormularioValidaciones/Formulario_campos');
        $this->arr_validacion = array();
        $this->formulario_campos = $CI->formulario_campos;
    }
    public function DefinicionValidacionFormulario() {

        $this->formulario_campos->CargarOpcionesValidacion('cuenta de usuario', LETRAS_NUMEROS, 'SINACENTO|SINESPACIO|REQUERIDO|MAX(100)', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-');
        $arr_validacion["cuenta"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('codigo de imagen', LETRAS_NUMEROS, 'SINACENTO|SINESPACIO|REQUERIDO|MAX(12)');
        $arr_validacion["imagen"] = clone $this->formulario_campos;
        $this->formulario_campos->CargarOpcionesValidacion('contraseña de usuario', LETRAS_NUMEROS, 'MAX(100)|REQUERIDO', '!|@|#|$|%|&|/|¿|?|¡|+|.|*|_|-|=|>|<|(|)');
        $arr_validacion["password"] = clone $this->formulario_campos;
        
        $this->arr_validacion = $arr_validacion;
    }
    
    
    public function ConstruccionCajasFormulario($arr_valores_defecto) {
        $i = 0;
        $arrCajasFormulario = array();
        //print_r($this->arr_validacion);
        foreach ($this->arr_validacion as $campo => $objvalidacion) {

            if ($objvalidacion->TIPO_CAJA_TEXTO === CAJATEXTO) {
                $arrCajasFormulario[$i++] = $campo;
            }
        }
        
        foreach ($arrCajasFormulario as $id_caja) {
            $strValorCaja = isset($arrValoresPorDefecto[$id_caja]) ? $arrValoresPorDefecto[$id_caja] : "";
            if (isset($this->arr_validacion[$id_caja])) {
                $validacion = $this->arr_validacion[$id_caja];

                if (isset($this->arr_title_tooltip[$id_caja])) {
                    $titleTooltip = $this->arr_title_tooltip[$id_caja];
                } else {
                    $titleTooltip = '';
                }
                $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaHtml($id_caja, 'MAX(' . $validacion->CARACTERES_MAXIMO . ')', $strValorCaja, $validacion->CLASES_CSS, $titleTooltip);
            }
        }
        
        $id_caja = "password";
        $validacion = $this->arr_validacion[$id_caja];
        $arr_formulario_cajas[$id_caja] = LibreriaUsir_GeneracionCajaPasswordHtml($id_caja, 'SI_ENTER|MAX(' . $validacion->CARACTERES_MAXIMO . ')');
                
        return $arr_formulario_cajas;
    }
    public function GeneraValidacionJavaScript() {
        return LibreriaUsir_GeneracionJqueryValidate_ParaCajasFormulario($this->arr_validacion);
    }
}