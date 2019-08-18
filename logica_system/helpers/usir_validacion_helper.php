<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('php_validacion_valores')) {
    function php_validacion_valores($strCadenaTexto, $strTipoValidacion,$intDimension,$blnAcentos,$blnEspacios,$strExtras="" ) {
        //echo '--->'.$strExtras;
        $strPatron = "";
        //$strExtras = addslashes($strExtras);
        //echo '--->'.$strExtras;
        
        //echo '--->'.$strExtras;
        if($blnEspacios){
           $strExtras .= " ";
            
        }
        
        if ($strTipoValidacion=="TODO"){
            return true;
        }
        
        $strExtras=preg_quote($strExtras);
        $strExtras = str_ireplace('/', '\/', $strExtras);
        
        switch ($strTipoValidacion) {
            case "NUMEROS" :$strPatron = '/^[0-9' . $strExtras . ']{0,' . $intDimension . '}+$/';
                break;
            case "LETRAS" :$strPatron = '/^[a-zA-Z';
                if ($blnAcentos) $strPatron.='ÑñáéíóúÁÉÍÓÚÜü';
                 $strPatron.= $strExtras.']{0,' . $intDimension . '}+$/';
                break;
            case "LETRAS_NUMEROS" :$strPatron = '/^[a-zA-Z0-9';
                if ($blnAcentos) $strPatron.='ÑñáéíóúÁÉÍÓÚÜü';
                $strPatron.=$strExtras . ']{0,' . $intDimension . '}+$/';
                break;
            case "CUSTOM" :$strPatron = '/^[' . $strExtras . ']{0,' . $intDimension . '}+$/';
                break;
            case "FECHA" :$strPatron = '/^[0-9\/]{0,' . $intDimension . '}+$/';
                break;
//            case 50:return true;
//                break;
            default:return false;
                break;
        }
        $strCadenaComparar = $strCadenaTexto;
        //echo $strPatron.'      '.$strCadenaComparar.'<br />';
        $blnResultado = preg_match($strPatron, $strCadenaComparar);
        //if(!$blnResultado)echo $strPatron.'      '.$strCadenaComparar.'<br />';
        return $blnResultado;
    }
}
if (!function_exists('php_formatear_valores')) {
    function php_formatear_valores(&$strCadenaTexto) {

        $strCadenaTexto = trim(preg_replace("/\s+/", " ", $strCadenaTexto));
        $strCadenaTexto = trim(preg_replace("/^\s|\s$/", "", $strCadenaTexto));
        
        
        //$strCadenaTexto = trim(preg_replace("/=|;|&/","",$strCadenaTexto));
        //        if ($blnSlashes)
        //            $strCadenaTexto = addslashes($strCadenaTexto);
        $strCadenaTexto = mb_convert_case($strCadenaTexto, MB_CASE_UPPER, 'UTF-8');
        //$strCadenaTexto=preg_replace("/\s/","#",$strCadenaTexto);
    }
}
?>