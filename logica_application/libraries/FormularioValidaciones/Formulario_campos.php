<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Formulario_campos {
    public $TIPO_VALIDACION = '';
    public $ACENTOS = true;
    public $ESPACIOS = true;        
    public $TEXTO_DESCRIPTIVO = 'cuenta de usuario';
    public $CAMPO_REQUERIDO = false;
    public $VALOR_NO_ADMITIDO = '';
    public $CARACTERES_MAXIMO = 5000;
    public $CARACTERES_MINIMO = 0;
    public $CARACTERES_ADICIONALES = '';
    public $CLASES_CSS = '';
    public function CargarOpcionesDefecto(){
    $this->TIPO_VALIDACION = '';
    $this->ACENTOS = true;
    $this->ESPACIOS = true;        
    $this->TEXTO_DESCRIPTIVO = 'cuenta de usuario';
    $this->CAMPO_REQUERIDO = false;
    $this->VALOR_NO_ADMITIDO = '';
    $this->CARACTERES_MAXIMO = 5000;
    $this->CARACTERES_MINIMO = 0;
    $this->CARACTERES_ADICIONALES = ''; 
    $this->TIPO_CAJA_TEXTO = ''; 
    }
    public function CargarOpcionesValidacion($strAlias,$strTipoValidacion,$strCadenaValidacion,$strCaracteresAdicionales='',$strTipoCaja=CAJATEXTO) {
        $this->CargarOpcionesDefecto();
        $this->TEXTO_DESCRIPTIVO = $strAlias;
        $this->TIPO_VALIDACION = $strTipoValidacion;
        $this->TIPO_CAJA_TEXTO=$strTipoCaja;
        if (strrpos($strCadenaValidacion, "SINACENTO") !== false) { 
            $this->ACENTOS = false;
        }
        if (strrpos($strCadenaValidacion, "SINESPACIO") !== false) { 
            $this->ESPACIOS = false ;
        }
        if (strrpos($strCadenaValidacion, "REQUERIDO") !== false) { 
            $this->CAMPO_REQUERIDO = true ;
        }
        if (strrpos($strCadenaValidacion, "MAX") !== false) { 
            $posicion = strrpos($strCadenaValidacion, "MAX("); 
            $posicion2 = strpos($strCadenaValidacion, ")",$posicion); 
            $this->CARACTERES_MAXIMO = intval(substr($strCadenaValidacion,$posicion+4,$posicion2-($posicion+4)));
        }
        if (strrpos($strCadenaValidacion, "CLASS_CSS") !== false) { 
            $posicion = strrpos($strCadenaValidacion, "CLASS_CSS("); 
            $posicion2 = strpos($strCadenaValidacion, ")",$posicion); 
            $this->CLASES_CSS =" class=\"". substr($strCadenaValidacion,$posicion+10,$posicion2-($posicion+10))."\" ";
        }
        if (strrpos($strCadenaValidacion, "MIN") !== false) { 
            $posicion = strrpos($strCadenaValidacion, "MIN("); 
            $posicion2 = strpos($strCadenaValidacion, ")",$posicion); 
            $this->CARACTERES_MINIMO = intval(substr($strCadenaValidacion,$posicion+4,$posicion2-($posicion+4)));
        }
        if (strrpos($strCadenaValidacion, "NOADMITIDO") !== false) { 
            $posicion = strrpos($strCadenaValidacion, "NOADMITIDO("); 
            $posicion2 = strpos($strCadenaValidacion, ")",$posicion); 
            $this->VALOR_NO_ADMITIDO = substr($strCadenaValidacion,$posicion+11,$posicion2-($posicion+11));
        }
        if($strCaracteresAdicionales!=''){
            $this->CARACTERES_ADICIONALES = $strCaracteresAdicionales;
        }
    }
    
}
?>