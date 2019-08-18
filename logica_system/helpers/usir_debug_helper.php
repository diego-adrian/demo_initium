<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
if (!function_exists('usir_debug_array')) {

    function usir_debug_array($arrDebug) {
        if (!isset($arrDebug)) {
            echo ">>>>>>>>>>>>>>>>>>>>>no existen datos";
            return;
        }
        echo "<pre>";
        print_r($arrDebug);
        echo "</pre>";
    }
    
}
function usir_div_error_modal($strMensaje) {
        echo "<div style='width:100%;text-align:center;heigth:1cm;' class='error'><br />";
        echo $strMensaje;
        echo "<br /><br /></div>";
    }
?>