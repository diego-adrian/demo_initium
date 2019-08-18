<?php
/**
 * @file 
 * funciones para la generacion de cajas , options , textarea y validaciones propias de un formulario 
 * @brief LIBRERIA DE GENERACION DE OBJETOS HTML USIR
 * @author JRAD
 * @copyright 2017 JRAD
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
function LibreriaUsir_ValidarValoresLadoServidor($arr_validacion, $arrValoresPost, &$arrValoresRetorno) {
    foreach ($arrValoresPost as $strNombreCampo => $strValor) {
        if (isset($arr_validacion[$strNombreCampo])) {
            $validacion = $arr_validacion[$strNombreCampo];
            php_formatear_valores($strValor);
            if ($strValor == "" && $validacion->CAMPO_REQUERIDO) {
                js_error_div_javascript("Valor es requerido en:" . $validacion->TEXTO_DESCRIPTIVO);
                exit;
            }
            $blnRespuesta = php_validacion_valores($strValor, $validacion->TIPO_VALIDACION, $validacion->CARACTERES_MAXIMO, $validacion->ACENTOS, $validacion->ESPACIOS, $validacion->CARACTERES_ADICIONALES);
            if (!$blnRespuesta) {
                js_error_div_javascript("Valor invalido en:" . $validacion->TEXTO_DESCRIPTIVO);
                exit;
            }
        }
        $arrValoresRetorno[$strNombreCampo] = $strValor;
        //print_r($strNombreCampo+"--" +$strValor +"-------");
    }
    //print_r($arrValoresPost);
}
function LibreriaUsir_CadenaMayusculaUtf8($strCadenaEntrada) {
    return mb_strtoupper($strCadenaEntrada, 'UTF-8');
}
/**
 * Function para generar la cadena de validacion de  cajas en el lado del cliente usando la libreria jqueryvalidate
 * @brief VALIDACION DE JAVASCRIPT  
 * @author JRAD
 * @date Mar 21, 2014 
 * @param array $arrValidacionCajas array de objetos con las validaciones
 * @param string $strNombreVariableParaReglas     nombre que se dara a la variable que tendra las reglas, por defecto es "arrReglasValidacion"
 * @param string $strNombreVariableParaMensajes   nombre que se dara a la variable que tendra los mensjaes, por defecto es "arrMensajesValidacion"
 * @return string cadena de validacion jquery validate
 */
function LibreriaUsir_GeneracionJqueryValidate_ParaCajasFormulario($arrValidacionCajas, $strNombreVariableParaReglas = "arrReglasValidacion", $strNombreVariableParaMensajes = "arrMensajesValidacion") {
    $strCadenaJavaScriptReglas = "var $strNombreVariableParaReglas = { ";
    $strCadenaJavaScriptMensajes = "var $strNombreVariableParaMensajes = { ";
    $strDescripcionValidacion = "";
    foreach ($arrValidacionCajas as $strIdCaja => $strValor) {
        $objEntidadPublica_ValidacionCampos_Libreria = $strValor;
        $strCadenaJavaScriptReglas.="'$strIdCaja':{";
        $strCadenaJavaScriptMensajes.="'$strIdCaja':{";
        if ($objEntidadPublica_ValidacionCampos_Libreria->CAMPO_REQUERIDO) {
            $strCadenaJavaScriptReglas.="required:true,";
            // $strCadenaJavaScriptMensajes.="required:'Debe ingresar un valor en " . $objEntidadPublica_ValidacionCampos_Libreria->TEXTO_DESCRIPTIVO . "',";
            $strCadenaJavaScriptMensajes.="required:'Esta información es requerida.',";
        }
        if ($objEntidadPublica_ValidacionCampos_Libreria->TIPO_VALIDACION != "TODO") {
            if ($objEntidadPublica_ValidacionCampos_Libreria->VALOR_NO_ADMITIDO != '' && $objEntidadPublica_ValidacionCampos_Libreria->CAMPO_REQUERIDO) {
                $strCadenaJavaScriptReglas.="noesigual:'" . $objEntidadPublica_ValidacionCampos_Libreria->VALOR_NO_ADMITIDO . "',";
                $strCadenaJavaScriptMensajes.="noesigual:'Debe seleccionar un valor en: " . $objEntidadPublica_ValidacionCampos_Libreria->TEXTO_DESCRIPTIVO . "',";
            }
            if ($objEntidadPublica_ValidacionCampos_Libreria->TIPO_VALIDACION == FECHA) {
                //$strCadenaJavaScriptReglas.="date:true,";
                //$strCadenaJavaScriptMensajes.="date:'Debe ingresar una fecha valido en: " . $objEntidadPublica_ValidacionCampos_Libreria->TEXTO_DESCRIPTIVO . "',";
            }
            //echo "ver.........." . $objEntidadPublica_ValidacionCampos_Libreria->TIPO_VALIDACION;
            //echo "verdos..........." . $objEntidadPublica_ValidacionCampos_Libreria->TEXTO_DESCRIPTIVO;
            switch ($objEntidadPublica_ValidacionCampos_Libreria->TIPO_VALIDACION) {
                case LETRAS:
                    $strPatron = '^[a-zA-Z';
                    $strDescripcionValidacion = ' letras sin acento, ';
                    if ($objEntidadPublica_ValidacionCampos_Libreria->ACENTOS) {
                        $strPatron.= 'áéíóúÁÉÍÓÚÑñÜü';
                        $strDescripcionValidacion = ' letras, ';
                    }
                    break;
                case LETRAS_NUMEROS:
                    $strPatron = '^[a-zA-Z0-9';
                    $strDescripcionValidacion = ' letras sin acento,numeros, ';
                    if ($objEntidadPublica_ValidacionCampos_Libreria->ACENTOS) {
                        $strPatron.= 'áéíóúÁÉÍÓÚÑñÜü';
                        $strDescripcionValidacion = ' letras,numeros, ';
                    }
                    break;
                case NUMEROS:
                    $strPatron = '^[0-9';
                    $strDescripcionValidacion = ' numeros, ';
                    break;
                case FECHA:
                    $strPatron = '^(\\\d{1,2})(\\\/)(\\\d{1,2})(\\\/)(\\\d{4})$';
                    $strDescripcionValidacion = ' fechas de tipo: 31/12/2013, ';
                    break;
                case CUSTOM:
                    $strPatron = '^[';
                    break;
            }
            if ($objEntidadPublica_ValidacionCampos_Libreria->ESPACIOS) {
                $strPatron.= ' ';
                $strDescripcionValidacion.='espacios,';
            }
            if ($objEntidadPublica_ValidacionCampos_Libreria->TIPO_VALIDACION != FECHA) {
                $strCaracteresAdicionales = $objEntidadPublica_ValidacionCampos_Libreria->CARACTERES_ADICIONALES;
                $strCaracteresAdicionales = preg_quote($strCaracteresAdicionales);
                $strCaracteresAdicionales = addslashes($strCaracteresAdicionales);
                $strCaracteresAdicionales.="\\n";
                $strCaracteresAdicionales = str_ireplace('/', '\\\/', $strCaracteresAdicionales);
                $strPatron.= $strCaracteresAdicionales . ']{0,' . $objEntidadPublica_ValidacionCampos_Libreria->CARACTERES_MAXIMO . '}$';
            }
            //$strPatron = '^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]{0,5}$';
            if ($objEntidadPublica_ValidacionCampos_Libreria->CARACTERES_ADICIONALES != '') {
                $strDescripcionValidacion.=' y los caracteres:' . $objEntidadPublica_ValidacionCampos_Libreria->CARACTERES_ADICIONALES;
            }
            $strCadenaJavaScriptReglas.="miregex:\"$strPatron\",";
            $strPatron = "";
            //$strCadenaJavaScriptMensajes.="miregex:'valor invalido en el campo: " . $objEntidadPublica_ValidacionCampos_Libreria->TEXTO_DESCRIPTIVO . "<div>solo se admiten: " . trim(trim($strDescripcionValidacion), ',') . "</div> ',";
            $strCadenaJavaScriptMensajes.="miregex:'El valor digitado no es admitido',";
        }
        $strCadenaJavaScriptReglas = trim($strCadenaJavaScriptReglas, ',') . "},";
        $strCadenaJavaScriptMensajes = trim($strCadenaJavaScriptMensajes, ',') . "},";
    }
    $strCadenaJavaScriptReglas = trim($strCadenaJavaScriptReglas, ',');
    $strCadenaJavaScriptMensajes = trim($strCadenaJavaScriptMensajes, ',');
    $strCadenaJavaScriptReglas .= "};";
    $strCadenaJavaScriptMensajes .= "};";
    return $strCadenaJavaScriptReglas . $strCadenaJavaScriptMensajes;
}
/**
 * Function para contruir validacion de  cajas
 * @brief VALIDACION DE JAVASCRIPT  
 * @author JRAD
 * @date Mar 21, 2014 
 * @param String $strIdCaja  Id de la caja html 
 * @param Int $intMaxLenght   cantidad maxima de caracteres admitida
 * @param String $strValorDefecto  valor precargado
 * @param Boolean $blnCajaEnabled   indica si la caja estara enabled o disabled
 * @param Boolean $blnCajaVisible   indica si la caja esta visible o no 
 * @param String $strAtributosExtra   cadena para añadir propiedades personalizadas a la definicion de la caja
 * @return String   Cadena Html generada con la Caja 
 */
function LibreriaUsir_GeneracionCajaHtml($strIdCaja, $strParametros, $strValorDefecto = '', $strAtributosExtra = '', $strTooltip = '') {
//function LibreriaUsir_GeneracionCajaHtml($strIdCaja, $intMaxLenght, $strValorDefecto = '', $blnCajaEnabled = true, $blnCajaVisible = true, $strAtributosExtra = '') {
    $blnCajaEnabled = true;
    if (strrpos($strParametros, "DISABLED") !== false) {
        $blnCajaEnabled = false;
    }
    $blnCajaVisible = true;
    if (strrpos($strParametros, "NOVISIBLE") !== false) {
        $blnCajaVisible = false;
    }
    $intMaxLenght = 5000;
    if (strrpos($strParametros, "MAX") !== false) {
        $posicion = strrpos($strParametros, "MAX(");
        $posicion2 = strpos($strParametros, ")", $posicion);
        $intMaxLenght = intval(substr($strParametros, $posicion + 4, $posicion2 - ($posicion + 4)));
    }
    if (!$blnCajaEnabled) {
        $strAtributosExtra.= ' disabled="disabled" ';
    }
    if (!$blnCajaVisible) {
        $strAtributosExtra.= ' style="display:none;" ';
    }
    return "<input type='text' autocomplete='off' value=\"$strValorDefecto\" 
     id='$strIdCaja'  name='$strIdCaja' $strAtributosExtra 
     maxlength = '$intMaxLenght'   title='$strTooltip' onkeydown=\"return (event.keyCode != 13);\"           
      />";
    //onkeyup='this.value=this.value.toUpperCase()'
}
if (!function_exists('html_password')) {
    function LibreriaUsir_GeneracionCajaPasswordHtml($strIdCaja, $strParametros, $strAtributosExtra = '', $strTooltip = '') {
        $blnCajaEnabled = true;
        if (strrpos($strParametros, "DISABLED") !== false) {
            $blnCajaEnabled = false;
        }
        $blnCajaVisible = true;
        if (strrpos($strParametros, "NOVISIBLE") !== false) {
            $blnCajaVisible = false;
        }
        $strBloquearEnter = "onkeydown=\"return (event.keyCode != 13);\"";
        if (strrpos($strParametros, "SI_ENTER") !== false) {
            $strBloquearEnter = "";
        }
        $intMaxLenght = 5000;
        if (strrpos($strParametros, "MAX") !== false) {
            $posicion = strrpos($strParametros, "MAX(");
            $posicion2 = strpos($strParametros, ")", $posicion);
            $intMaxLenght = intval(substr($strParametros, $posicion + 4, $posicion2 - ($posicion + 4)));
        }
        if (!$blnCajaEnabled)
            $strAtributosExtra.= ' disabled="disabled" ';
        if (!$blnCajaVisible)
            $strAtributosExtra.= ' style="display:none;" ';
        return "<input type='password' autocomplete='off' 
     id='$strIdCaja'  name='$strIdCaja' $strAtributosExtra 
     maxlength = '$intMaxLenght' title='$strTooltip'     
     $strBloquearEnter  />";
    }
}
if (!function_exists('html_textarea')) {
    function html_textarea($strIdCaja, $strParametros, $strValorDefecto = "", $strAtributosExtra = '', $strTooltip = '', $strMaximo = '') {
        $blnCajaEnabled = true;
        if (strrpos($strParametros, "DISABLED") !== false) {
            $blnCajaEnabled = false;
        }
        $blnCajaVisible = true;
        if (strrpos($strParametros, "NOVISIBLE") !== false) {
            $blnCajaVisible = false;
        }
        if (!$blnCajaEnabled)
            $strAtributosExtra.= ' disabled="disabled" ';
        if (!$blnCajaVisible)//valida_longitud(id, num_caracteres_permitidos)
            $strAtributosExtra.= ' style="display:none;" ';

        /* if ($strMaximo == '')
          $strMaximo = 0; */

        return ' <textarea id="' . $strIdCaja . '"  name="' . $strIdCaja . '"  title="' . $strTooltip . '"
        rows=5 cols=20 style="height:16mm ;"  onKeyDown="valida_longitud(\'' . $strIdCaja
                . '\',' . $strMaximo . ')" onKeyUp="valida_longitud(\'' . $strIdCaja . '\',' . $strMaximo . ');" '
                . "$strAtributosExtra" . '>' . trim($strValorDefecto) . '</textarea>';
    }
}
if (!function_exists('html_select')) {
    function html_select($strIdSelect, $arrDatosCargar, $strCampoOptionId, $strCampoOptionDescrip, $strParametros, $strValorDefecto = "-1", $strPropiedadesExtra = '', $strHtmlOpcionManual = "") {
        $blnSelectEnabled = true;
        if (strrpos($strParametros, "DISABLED") !== false) {
            $blnSelectEnabled = false;
        }
        $blnOpcionSeleccionar = true;
        if (strrpos($strParametros, "SINSELECCIONAR") !== false) {
            $blnOpcionSeleccionar = false;
        }
        $blnSelectVisible = true;
        if (strrpos($strParametros, "NOVISIBLE") !== false) {
            $blnSelectVisible = false;
        }
        $blnMostrarOpcion_Otro = false;
        if (strrpos($strParametros, "CONOTRO") !== false) {
            $blnSelectVisible = true;
        }
        if (!$blnSelectEnabled)
            $strPropiedadesExtra.= ' disabled="disabled" ';
        if (!$blnSelectVisible)
            $strPropiedadesExtra.= ' style="display:none;" ';
        $strValorDefecto = trim(strval($strValorDefecto));
        if ($strValorDefecto == '')
            $strValorDefecto = "-1";
        $strCadenaHTML = "<select id='$strIdSelect' $strPropiedadesExtra  name='$strIdSelect' >";
        $strPropiedadOption = "";
        if ($strValorDefecto == "-1") {
            $strPropiedadOption = "selected='selected'";
        }
        if ($blnOpcionSeleccionar) {
            $strCadenaHTML .= "<option value='-1' $strPropiedadOption >--Seleccionar--</option>";
        }
        if ($strHtmlOpcionManual != "") {
            $strCadenaHTML .= $strHtmlOpcionManual;
        }
        $intDimension = count($arrDatosCargar);
        $strPropiedadOption = "";
        for ($i = 0; $i < $intDimension; $i++) {
            $strPropiedadOption = "";
            if ($strValorDefecto == $arrDatosCargar[$i][$strCampoOptionId]) {
                $strPropiedadOption = "selected='selected'";
            }
            $strCadenaHTML .="<option value='" .
                    $arrDatosCargar[$i][$strCampoOptionId] . "'
        mitext='" . $arrDatosCargar[$i][$strCampoOptionDescrip] . "'
        $strPropiedadOption >" .
                    $arrDatosCargar[$i][$strCampoOptionDescrip] . "</option>";
        }
        if ($blnMostrarOpcion_Otro) {
            $strCadenaHTML .= "<option value='-2' >--Elegir otra opcion--</option>";
        }
        $strCadenaHTML .= "</select>";
        return $strCadenaHTML;
    }
}
function html_GeneraMenuesTitulo($strDescripcionTitulo, $arrPermisosMenu, &$intCodigoSeccion, &$arrRubros, $rol_actual, &$intCodigoLink, &$arrLinksRubros) {
    array_push($arrPermisosMenu, ROL_SUPER_ADMIN);
    if (in_array($rol_actual, $arrPermisosMenu)) {
        $arrLinksRubros = array();
        $intCodigoSeccion++;
        $arrRubros[$intCodigoSeccion]["codigo"] = $intCodigoSeccion;
        $arrRubros[$intCodigoSeccion]["Descripcion"] = $strDescripcionTitulo;
    }
}
function html_GeneraMenuesLinks($strNombreLink, $strUrlLink, $arrPermisosMenu, &$arrRubros, &$arrLinksRubros, $rol_actual, &$intCodigoLink, &$intCodigoSeccion) {
    array_push($arrPermisosMenu, ROL_SUPER_ADMIN);
    if (in_array($rol_actual, $arrPermisosMenu)) {
        $intCodigoLink++;
        $arrLinksRubros[$intCodigoLink]["codigo"] = $intCodigoLink;
        $arrLinksRubros[$intCodigoLink]["nombreLink"] = $strNombreLink;
        $arrLinksRubros[$intCodigoLink]["urlLink"] = $strUrlLink;
        $arrRubros[$intCodigoSeccion]["links"] = $arrLinksRubros;
    }
}
function excel_GeneraColumnas($strNombreColumnaBd, $strTituloColumna, $intDimension, &$intContadorColumnas, &$arrColumnas, $blnValorFuncion = false, $objNombreFuncion = null,$strParametros="") {
    $intContadorColumnas++;
    $arrColumnas[$intContadorColumnas]["nombrebd"] = $strNombreColumnaBd;
    $arrColumnas[$intContadorColumnas]["titulo"] = $strTituloColumna;
    $arrColumnas[$intContadorColumnas]["dimension"] = $intDimension;
    $arrColumnas[$intContadorColumnas]["EsValorFuncion"] = $blnValorFuncion;
    $arrColumnas[$intContadorColumnas]["NombreFuncion"] = $objNombreFuncion;
    $arrColumnas[$intContadorColumnas]["Parametros"] = $strParametros;
}
function excel_GeneraDatos(&$numeroFila, &$arrDatosParaExcel,$arrColumnas, $arrResultado) {

    //$CI = & get_instance();
    $numeroColumna = 0;
    foreach ($arrColumnas as $columna) {
        $arrDatosParaExcel[$numeroFila][$numeroColumna++] = $columna["titulo"];
    }
    $numeroFila++;
    foreach ($arrResultado as $arrRegistro) {
        foreach ($arrColumnas as $columna) {
            if (!$columna["EsValorFuncion"]) {
                if($columna["Parametros"]=="numero"){
                    $arrDatosParaExcel[$numeroFila][$numeroColumna++] = $arrRegistro[$columna["nombrebd"]];
                }
                else{
                $arrDatosParaExcel[$numeroFila][$numeroColumna++] = " ".$arrRegistro[$columna["nombrebd"]];
                }
            } else {
                $nameFunction = $columna["NombreFuncion"];
                $arrDatosParaExcel[$numeroFila][$numeroColumna++] = $nameFunction($arrRegistro[$columna["nombrebd"]]);
            }
        }        
        $numeroFila++;
    }
}
if (!function_exists('html_caja_fecha')) {
    function html_caja_fecha($strIdCaja, $strParametros, $strFechaHoy = '', $strAtributosExtra = '') {
        $blnCajaEnabled = true;
        if (strrpos($strParametros, "DISABLED") !== false) {
            $blnCajaEnabled = false;
        }
        $blnCajaVisible = true;
        if (strrpos($strParametros, "NOVISIBLE") !== false) {
            $blnCajaVisible = false;
        }
        if (!$blnCajaEnabled)
            $strAtributosExtra.= ' readonly="readonly" ';
        // $strAtributosExtra.= ' disabled="disabled" ';
        if (!$blnCajaVisible)
            $strAtributosExtra.= ' style="display:none;" ';
        return "<input type='text' autocomplete='off' value=\"$strFechaHoy\" 
     id='$strIdCaja'  name='$strIdCaja' $strAtributosExtra 
     maxlength = '10'  onkeydown=\"return (event.keyCode != 13);\" 
      />";
        //<div class=\"TextoSimpleAyuda\">Ejemplo 31/12/2013</div>";
    }
}
if (!function_exists('js_invocacion_javascript')) {
    function js_invocacion_javascript($strCadenaScript) {
        echo "<div></div><script> $strCadenaScript </script>";
    }
}
if (!function_exists('js_error_div_javascript')) {
    function js_error_div_javascript($strCadenaMensajeScript) {
        //$strCadenaMensajeScript = "." . $strCadenaMensajeScript;
        // echo "<div style'display:none' clave='error'>error</div><script>Elementos_Mostrar_ErrorDiv(\"$strCadenaMensajeScript\",\"$strNombreDiv\"); </script>";
        echo "<div style=\"display:none\" clave='error'>error</div><br /> $strCadenaMensajeScript <br /><br />";
        exit;
    }
}
if (!function_exists('js_load_invocacion_javascript')) {
    function js_load_invocacion_javascript($strCadenaScript) {
        echo "<div></div><script>$(document).ready($strCadenaScript);</script>";
    }
}

if (!function_exists('js_error_servicio_div_javascript')) {

    function js_error_servicio_div_javascript($e) {
        //$strCadenaMensajeScript = "." . $strCadenaMensajeScript;
        // echo "<div style'display:none' clave='error'>error</div><script>Elementos_Mostrar_ErrorDiv(\"$strCadenaMensajeScript\",\"$strNombreDiv\"); </script>";
        $pos1 = strpos($e, "$$");
        $pos2 = strrpos($e, "$$");
        $cadena2 = substr($e, $pos1, $pos2 - $pos1);
        echo "<div style=\"display:none\" clave='error'>error</div><br /> $cadena2 
                <img style='width: 1cm;' src='imagenes/candado_minimo.png' alt='cge'> <br /><br />";
    }
}
?>