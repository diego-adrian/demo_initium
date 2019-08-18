<?php
class Mfunciones_generales extends CI_Model {
    //private $Consulta_soapx;
    private $cache_dias_laborales=null;

    function __construct() {
        parent::__construct();
        $CI = & get_instance();
        $CI->load->library('soap/Consulta_soap');
        $this->consulta_soap = $CI->consulta_soap;
    }
    
    
    // AUDITORIA
    
	function AuditoriaAcceso($tipo_acceso) {
            
            $this->load->model('mfunciones_logica');

            if(isset($_SESSION["session_informacion"]["login"]))
            {
                $auditoria_usuario = $_SESSION["session_informacion"]["login"];
            }
            else
            {
                $auditoria_usuario = "no_autenticado";
            }

            $auditoria_fecha = date('Y-m-d H:i:s');
            $auditoria_accion = $tipo_acceso;

            $auditoria_ip = $this->input->ip_address();

            $this->mfunciones_logica->InsertarAuditoriaAcceso($auditoria_usuario, $auditoria_accion, $auditoria_fecha, $auditoria_ip);
	}
	
	function Auditoria($accion_detalle, $tabla) {
		
		$this->load->model('mfunciones_logica');

		$auditoria_usuario = $_SESSION["session_informacion"]["login"];
		$auditoria_fecha = date('Y-m-d H:i:s');
		$auditoria_tabla = $tabla;
		$auditoria_accion = $accion_detalle;

		$auditoria_ip = $this->input->ip_address();

		//$this->mfunciones_logica->InsertarAuditoria($auditoria_usuario, $auditoria_fecha, $auditoria_tabla, $auditoria_accion, $auditoria_ip);
	}

    // FUNCIONALES PROPIOS DEL SISTEMA
    
	function getRolUsuario($data) {
		
		$this->load->model('mfunciones_logica');
		$this->lang->load('general', 'castellano');
		
		$arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerRoles($data);
		$this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		
		if (isset($arrResultado[0])) 
		{
			return $arrResultado[0]['rol_nombre'];
		}
		else
		{
			return 'Parámetro Invalido';
		}		
	}
    
        function GeneraPDF($vista, $datos, $orientacion='P') {
            $this->lang->load('general', 'castellano');
            // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
                if((int)(ini_get('memory_limit')) < 128)
                {
                    ini_set("memory_limit",'128M');
                }
            
            $html = $this->load->view($vista,$datos,true);
            $this->load->library('pdf');
            $pdf = $this->pdf->load();

            $reporte_generado = 'Reporte Generado ' . date('d/m/Y H:i') . ' (' . $_SESSION["session_informacion"]["login"] . ')';

            $header = array (
                'odd' => array (
                    'L' => array (
                        'content' => $this->lang->line('ReporteTituloIzquierda'),
                        'font-size' => 8,
                        'font-style' => '',
                        'font-family' => 'Arial',
                        'color'=>'#000000'
                    ),
                    'C' => array (
                        'content' => $this->lang->line('ReporteTituloCentro'),
                        'font-size' => 8,
                        'font-style' => '',
                        'font-family' => 'Arial',
                        'color'=>'#000000'
                    ),
                    'R' => array (
                        'content' => $this->lang->line('ReporteTituloDerecha'),
                        'font-size' => 8,
                        'font-style' => '',
                        'font-family' => 'Arial',
                        'color'=>'#000000'
                    ),
                    'line' => 1,
                ),
                'even' => array ()
            );

            $pdf->SetHeader($header);

            $pdf->SetHTMLFooter('<table border="0" style="text-align: right; font-family: \'Open Sans\', Arial, sans-serif; font-size: 11px; width: 100%"><tr><td align="left"> ' . $reporte_generado . ' </td><td>Página {PAGENO} de {nb}</td></td></table>');
            
            if($orientacion == 'L')
            {
                $pdf->AddPage('L');
            }
            
            $pdf->WriteHTML($html);

            /*        
            I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
            D: send to the browser and force a file download with the name given by name.
            F: save to a local file with the name given by name (may include a path).
            S: return the document as a string. name is ignored.
            */
            $pdf->Output('reporte_initium_' . date('Ymd_His') . '.pdf', 'I');
        }
        
        function GeneraPDF_SinHeader($vista, $datos) {
            $this->lang->load('general', 'castellano');
            // Ajusta el limite de memoria, puede no ser requerido según instalación del servidor
                if((int)(ini_get('memory_limit')) < 128)
                {
                    ini_set("memory_limit",'128M');
                }
            
            $html = $this->load->view($vista,$datos,true);
            $this->load->library('pdf');
            $pdf = $this->pdf->load();

            $pdf->WriteHTML($html);

            /*        
            I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
            D: send to the browser and force a file download with the name given by name.
            F: save to a local file with the name given by name (may include a path).
            S: return the document as a string. name is ignored.
            */
            $pdf->Output('reporte_initium_' . date('Ymd_His') . '.pdf', 'I');
        }
        
        function GeneraExcel($vista, $datos) {
    //        ini_set('display_errors', 1);
    //        ini_set('display_startup_errors', 1);
    //        error_reporting(E_ALL);
    //        ini_set("memory_limit",'32M');
    //        $tmpFile = tempnam("/tmp","tmp".microtime()."xls");
    //        file_put_contents($tmpFile,$this->load->view($vista,$datos,true));
    //        $this->load->library("Excel");
    //        $reader = PHPExcel_IOFactory::createReader("HTML");
    //        $objPHPExcel = $reader->load($tmpFile);
    //        $objPHPExcel->getActiveSheet()->setTitle("Reporte_INITIUM");
    //        unlink($tmpFile);
    //        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //        header('Content-Disposition: attachment;filename=reporte_initium_' . date('Ymd_His') . '.xlsx');
    //        header('Cache-Control: max-age=0');
    //                
    //        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    //        $writer->save("php://output");

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=reporte_initium_' . date('Ymd_His') . '.xls');
            header('Cache-Control: max-age=0');

            echo $this->load->view($vista,$datos,true);

        }
        
        function ArrGroupBy($array, $clave)
        {
            $result = array();
            foreach ($array as $element)
            {
                $result[$element[$clave]][] = $element;
            }
            
            return $result;
        }
        
        function EtapaHitosProspecto($array, $codigo_etapa)
        {
            
            //$this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($fechasEtapas[array_search(128, array_column($fechasEtapas,"etapa_id"))]["hito_fecha_ini"]); 
            
            $posicion = array_search($codigo_etapa, array_column($array,"etapa_id"));
            
            if(is_int($posicion))
            {
                return $this->getFormatoFechaD_M_Y_H_M($array[$posicion]["hito_fecha_ini"]);
            }
            else
            {
                return "--";
            }
        }
        
        function ObtenerColorAvance($total, $avance)
        {
            
            $rojo = 'background-color: #db1b1c; color: #ffffff;';
            $amarillo = 'background-color: #fec506; color: #ffffff;';
            $verde = 'background-color: #389317; color: #ffffff;';
            
            $total = (int)str_replace(",",".",$total);
            $avance = (int)str_replace(",",".",$avance);
            
            if($avance>=$total)
            {
                return $verde;
            }
            
            $resultado = ($avance*100)/$total;
            
            switch (1) {
                case ($resultado<=89):

                    return $rojo;
                    
                    break;
                
                case ($resultado>=90 && $resultado<=99):

                    return $amarillo;
                    
                    break;
                
                case ($resultado>=100):
                    
                    return $verde;
                    
                    break;

                default:
                    break;
            }
        }
        
        function ObtenerTimmingCampana($codigo_campana)
        {
            $arrResultado = $this->mfunciones_logica->ObtenerCampana($codigo_campana);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            // Días
                    
            // 1: Se obtiene la fecha de conclusión de la campaña en base a su fecha de inicio y el plazo

            $aux_fecha_inicio = new DateTime($arrResultado[0]["camp_fecha_inicio"]);
            $aux_fecha_inicio->add(new DateInterval('P' . $arrResultado[0]["camp_plazo"] . 'D'));
            $aux_fecha_final = $aux_fecha_inicio->format('Y-m-d');

            // 2: Se calcula la cantidad de días entre la fecha actual y la fecha de finalización de la campaña

            $fecha_actual = new DateTime(date("Y-m-d"));

            $aux_fecha_final = new DateTime($aux_fecha_final);

            if($fecha_actual > $aux_fecha_final)
            {
                $avance_campana_dias_porcentaje = "(finalizó) 100,00";
            }
            else
            {
                $aux_diferencia_dias = $aux_fecha_final->diff($fecha_actual)->format("%a");
            
                // 3: Se calcula los días avanzados en Número y Porcentaje

                if($aux_diferencia_dias >= $arrResultado[0]["camp_plazo"])
                {
                    $avance_campana_dias_numero = 0;
                }
                else
                {
                    $avance_campana_dias_numero = $arrResultado[0]["camp_plazo"] - $aux_diferencia_dias;
                }

                $avance_campana_dias_porcentaje = number_format((($avance_campana_dias_numero*100)/$arrResultado[0]["camp_plazo"]), 2, ',', '.');
            }
            
            return $avance_campana_dias_porcentaje;
        }
        
        function CalculoLeadAgenteCampana($agente, $campana)
        {
            $this->load->model('mfunciones_logica');
            $this->lang->load('general', 'castellano');
            
            $arrReporte = array();
            
            $filtro = 'p.ejecutivo_id=' . $agente . ' AND p.camp_id=' . $campana;
        
            $arrResultado = $this->mfunciones_logica->ListadoBandejasProspecto($filtro);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            
            if (!isset($arrResultado[0]))
            {
                js_error_div_javascript($this->lang->line('TablaNoResultados'));
                exit();
            }
            else
            {
                // Se inicializan las variables
            
                $campana_id = $arrResultado[0]["camp_id"];
                $campana_nombre = $arrResultado[0]["camp_nombre"];
                $campana_desc = $arrResultado[0]["camp_desc"];
                $campana_plazo = $arrResultado[0]["camp_plazo"];
                $campana_monto_oferta = $arrResultado[0]["camp_monto_oferta"];
                $campana_tasa = $arrResultado[0]["camp_tasa"];
                $campana_fecha_inicio = $arrResultado[0]["camp_fecha_inicio"];
                
                $agente_id = $arrResultado[0]["ejecutivo_id"];
                $agente_nombre = $arrResultado[0]["ejecutivo_nombre"];
                
                $contador_total = 0;

                $contador_asignado = 0;
                $contador_interes = 0;
                $contador_cierre = 0;
                $contador_entrega = 0;
                $contador_carpeta = 0;
                $contador_aprobacion = 0;
                $contador_rechazo = 0;
                $contador_desembolso = 0;
                
                $contador_nointeres = 0;

                $suma_aprobacion = 0;
                $suma_desembolso = 0;
                
                $porcentaje_asignado = 0;
                $porcentaje_interes = 0;
                $porcentaje_cierre = 0;
                $porcentaje_entrega = 0;
                $porcentaje_carpeta = 0;
                $porcentaje_aprobacion = 0;
                $porcentaje_rechazo = 0;
                $porcentaje_desembolso = 0;
                
                $avance_desembolso_numero = 0;
                $avance_desembolso_porcentaje = 0;
                
                $avance_campana_dias_numero = 0;
                $avance_campana_dias_porcentaje = 0;
                
                foreach ($arrResultado as $key => $value) 
                {
                    switch ($value["prospecto_etapa"]) {

                        case 1: $contador_asignado++; break;
                        case 2: $contador_interes++; break;
                        case 3: $contador_cierre++; break;
                        case 4: $contador_entrega++; break;
                        case 5: $contador_carpeta++; break;
                        case 6: $contador_aprobacion++; $suma_desembolso+=$value["prospecto_monto_desembolso"]; break;
                        case 7: $contador_rechazo++; break;
                        case 8: $contador_desembolso++; $suma_desembolso+=$value["prospecto_monto_desembolso"]; break;

                        case 10: $contador_nointeres++; break;
                    
                        default:
                            break;
                    }
                    
                    $contador_total++;
                }
                
                // REFERENCIA
                $arrReporte[0]["campana_id"] = $campana_id;
                $arrReporte[0]["campana_nombre"] = $campana_nombre;
                $arrReporte[0]["campana_desc"] = $campana_desc;
                $arrReporte[0]["campana_plazo"] = $campana_plazo;
                $arrReporte[0]["campana_monto_oferta"] = $campana_monto_oferta;
                $arrReporte[0]["campana_tasa"] = $campana_tasa;
                $arrReporte[0]["campana_fecha_inicio"] = $this->mfunciones_generales->getFormatoFechaD_M_Y($campana_fecha_inicio);
                $arrReporte[0]["agente_id"] = $agente_id;
                $arrReporte[0]["agente_nombre"] = $agente_nombre;
                // CONTADORES
                $arrReporte[0]["contador_total"] = $contador_total;
                $arrReporte[0]["contador_asignado"] = $contador_asignado;
                $arrReporte[0]["contador_interes"] = $contador_interes;
                $arrReporte[0]["contador_cierre"] = $contador_cierre;
                $arrReporte[0]["contador_entrega"] = $contador_entrega;
                $arrReporte[0]["contador_carpeta"] = $contador_carpeta;
                $arrReporte[0]["contador_aprobacion"] = $contador_aprobacion;
                $arrReporte[0]["contador_rechazo"] = $contador_rechazo;
                $arrReporte[0]["contador_desembolso"] = $contador_desembolso;
                
                $arrReporte[0]["contador_nointeres"] = $contador_nointeres;
                
                $arrReporte[0]["suma_aprobacion"] = $suma_aprobacion;
                $arrReporte[0]["suma_desembolso"] = $suma_desembolso;
                // PORCENTAJE
                $arrReporte[0]["porcentaje_asignado"] = number_format((($contador_asignado*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_interes"] = number_format((($contador_interes*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_cierre"] = number_format((($contador_cierre*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_entrega"] = number_format((($contador_entrega*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_carpeta"] = number_format((($contador_carpeta*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_aprobacion"] = number_format((($contador_aprobacion*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_rechazo"] = number_format((($contador_rechazo*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["porcentaje_desembolso"] = number_format((($contador_desembolso*100)/$contador_total), 2, ',', '.');
                
                $arrReporte[0]["porcentaje_nointeres"] = number_format((($contador_nointeres*100)/$contador_total), 2, ',', '.');
                
                // NIVEL AVANCE
                $arrReporte[0]["avance_desembolso_numero"] = number_format((($contador_desembolso*$campana_monto_oferta)/$contador_total), 2, ',', '.');
                $arrReporte[0]["avance_desembolso_porcentaje"] = number_format((($contador_desembolso*100)/$contador_total), 2, ',', '.');
                $arrReporte[0]["avance_desembolso_porcentaje_numero"] = number_format((($contador_desembolso*100)/$contador_total), 2, '.', '.');
                
                    // Días
                    
                    // 1: Se obtiene la fecha de conclusión de la campaña en base a su fecha de inicio y el plazo
                    
                    $aux_fecha_inicio = new DateTime($campana_fecha_inicio);
                    $aux_fecha_inicio->add(new DateInterval('P' . $campana_plazo . 'D'));
                    $aux_fecha_final = $aux_fecha_inicio->format('Y-m-d');
                    
                    $arrReporte[0]["campana_fecha_final"] = $this->mfunciones_generales->getFormatoFechaD_M_Y($aux_fecha_final);
                    
                    // 2: Se calcula la cantidad de días entre la fecha actual y la fecha de finalización de la campaña
                    
                    $fecha_actual = new DateTime(date("Y-m-d"));
                    
                    $aux_fecha_final = new DateTime($aux_fecha_final);
                    
                    if($fecha_actual > $aux_fecha_final)
                    {
                        $avance_campana_dias_porcentaje = "100,00";
                    }
                    else
                    {
                        $aux_diferencia_dias = $aux_fecha_final->diff($fecha_actual)->format("%a");

                        // 3: Se calcula los días avanzados en Número y Porcentaje

                        if($aux_diferencia_dias >= $arrResultado[0]["camp_plazo"])
                        {
                            $avance_campana_dias_numero = 0;
                        }
                        else
                        {
                            $avance_campana_dias_numero = $arrResultado[0]["camp_plazo"] - $aux_diferencia_dias;
                        }

                        $avance_campana_dias_porcentaje = number_format((($avance_campana_dias_numero*100)/$arrResultado[0]["camp_plazo"]), 2, ',', '.');
                    }
                    
                $arrReporte[0]["avance_campana_dias_numero"] = $avance_campana_dias_numero;
                $arrReporte[0]["avance_campana_dias_porcentaje"] = $avance_campana_dias_porcentaje;
                
                return $arrReporte;
            }
        }
        
        function ListadoCamapanaServicios()
        {
            $this->load->model('mfunciones_logica');
            
            // Se realiza la consulta 1 sóla vez a las campañas y servicios para guardarlo en una variable y no tener que consultar en la DB cada vez
            $arrCampana= $this->mfunciones_logica->ObtenerCampana(-1);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);

            $arrBase1 = array();

            $contador1 = 0;

            foreach ($arrCampana as $key => $value1) 
            {
                $clave_campana = $value1["camp_id"];

                $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($clave_campana);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                $arrBase2 = array();

                $contador2 = 0;

                foreach ($arrServicios as $key => $value2) 
                {
                    $arrBase2[$contador2] = $value2["servicio_id"];

                    $contador2++;
                }

                $arrBase1[$clave_campana] = array(
                    "servicios" => $arrBase2,
                );

                $contador1++;
            }
            
            return $arrBase1;
        }
        
        function VerificaReporteLegal($codigo_prospecto)
        {
            $arrResultado = $this->mfunciones_logica->ListaDatosEvaluacion($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        
        function VerificaFormSociedad($codigo_prospecto)
        {
            $arrResultado = $this->mfunciones_logica->ListaDatosForm_Sociedad($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        
        function VerificaFormMatch($codigo_prospecto)
        {
            $arrResultado = $this->mfunciones_logica->ListaDatosForm_Match($codigo_prospecto);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            if (isset($arrResultado[0])) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        
        function VerificaCampoLead($campo, $valor)
        {
            $this->lang->load('general', 'castellano');
            $this->load->model('mfunciones_generales');
            $this->load->model('mfunciones_logica');

            $error1 = 'Estructura y/o dimensión no válida';
            $error2 = 'La Campaña no esta registrada en la BD';
            $error3 = 'El Agente no esta registrado en la BD o no está habilitado';
            $error4 = 'El Estado no esta registrado en la BD o no está habilitado';

            $respuesta = '';

            switch ($campo) {

                case 'nombre_etapa':

                    $arrConsulta = $this->mfunciones_logica->ObtenerCodigoEtapaNombre($valor);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                    if(!isset($arrConsulta[0]))
                    {
                        $respuesta = $error4;
                    }

                    break;
                
                case 'nombre_campana':

                    $arrConsulta = $this->mfunciones_logica->ObtenerCodigoCampanaNombre($valor);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                    if(!isset($arrConsulta[0]))
                    {
                        $respuesta = $error2;
                    }

                    break;

                case 'string_vacio':

                    if($valor == '' || strlen((string)$valor) > 100)
                    {
                        $respuesta = $error1;
                    }

                    break;
                    
                 case 'idc':

                    if($valor == '' || strlen((string)$valor) > 15)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'nombre_cliente':

                    if($valor == '' || strlen((string)$valor) > 150)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'empresa':

                    if($valor == '' || strlen((string)$valor) > 150)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'ingreso':

                    if($valor == '' || !is_numeric($valor) || $valor <= 0)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'direccion':

                    if($valor == '' || strlen((string)$valor) > 255)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'telefono':

                    if($valor == '' || strlen((string)$valor) > 10)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'celular':

                    if($valor == '' || strlen((string)$valor) > 10)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'correo':

                    if($this->mfunciones_generales->VerificaCorreo($valor) == false || strlen((string)$valor) > 150)
                    {
                        $respuesta = $error1;
                    }

                    break;

                case 'matricula':

                    if($valor == '' || strlen((string)$valor) > 16)
                    {
                        $respuesta = $error1;

                        break;
                    }

                    $arrConsulta = $this->mfunciones_logica->ObtenerCodigoEjecutivoUsuario($valor);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConsulta);

                    if(!isset($arrConsulta[0]))
                    {
                        $respuesta = $error3;
                    }

                    break;

                default:
                    $respuesta = 'Sin valor';
            }

            return $respuesta;
        }
        
	function GetValorCatalogo($data, $tipo) {
		
		$this->load->model('mfunciones_logica');
		$this->lang->load('general', 'castellano');
		
		$resultado = "No Definido";
                
                if($tipo == 'form_campanas')
		{
                    // Paso 1: Se guarda en un array el listado completo de actividaes
                    
                    // Listado de Servicios
                    $arrCampana = $this->mfunciones_logica->ObtenerCampana(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);
                    
                    $arrListado = array();
                    
                    if(isset($arrCampana[0]))
                    {
                        foreach ($arrCampana as $key => $value) 
                        {
                            $arrListado[] = array(
                                'label' => $value["camp_nombre"],
                                'value' => $value["camp_id"]
                            );
                        }
                    }
                    
                    $resultado = $arrListado;
                }
                
                if($tipo == 'form_actividades')
		{
                    // Paso 1: Se guarda en un array el listado completo de actividaes
                    
                    // Listado de Servicios
                    $arrActividades = $this->mfunciones_logica->ObtenerActividades(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrActividades);
                    
                    // Paso 2: Se guarda en un array las actiidades seleccionados por el registro
                    
                    // Listado de Servicios/Productos Seleccionados
                    $arrActividadesLead = $this->mfunciones_logica->ObtenerDetalleProspecto_actividades($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrActividadesLead);
                    
                    $arrListado = array();
                    
                    if(isset($arrActividades[0]))
                    {
                        foreach ($arrActividades as $key => $value) 
                        {
                            if(is_int(array_search($value["act_id"], array_column($arrActividadesLead, 'act_id'))))
                            {
                                $existe_producto = 'true';
                            }
                            else
                            {
                               $existe_producto = 'false';
                            }
                            
                            $arrListado[] = array(
                                'label' => $value["act_detalle"],
                                'value' => $value["act_id"],
                                'selected' => $existe_producto
                            );
                        }
                    }
                    
                    $resultado = $arrListado;
                }
                
                if($tipo == 'form_productos')
		{
                    // Paso 1: Se guarda en un array el listado completo de productos
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerServicio(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
                    
                    // Paso 2: Se guarda en un array los productos seleccionados por el registro
                    
                    // Listado de Servicios/Productos Seleccionados
                    $arrServiciosLead = $this->mfunciones_logica->ObtenerDetalleProspecto_servicios($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServiciosLead);
                    
                    $arrListado = array();
                    
                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            if(is_int(array_search($value["servicio_id"], array_column($arrServiciosLead, 'servicio_id'))))
                            {
                                $existe_producto = 'true';
                            }
                            else
                            {
                               $existe_producto = 'false';
                            }
                            
                            $arrListado[] = array(
                                'label' => $value["servicio_detalle"],
                                'value' => $value["servicio_id"],
                                'selected' => $existe_producto
                            );
                        }
                    }
                    
                    $resultado = $arrListado;
                }
                
                if($tipo == 'lead_actividades')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_actividades($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' . $value["act_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'lead_actividades_plain')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_actividades($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' - ' . $value["act_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'lead_productos_plain')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_servicios($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' - ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'lead_productos')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerDetalleProspecto_servicios($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'campana_productos_plain')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' - ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'campana_productos')
		{
                    $aux = '';
                    
                    // Listado de Servicios
                    $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($data);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);

                    if(isset($arrServicios[0]))
                    {
                        foreach ($arrServicios as $key => $value) 
                        {
                            $aux .= ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> ' . $value["servicio_detalle"];
                            $aux .= "<br />";
                        }                                
                    }
                    else
                    {
                        $aux = $this->lang->line('TablaNoRegistrosMinimo');
                    }
                    
                    $resultado = $aux;
                }
                
                if($tipo == 'empresa_acciones')
		{
                    if($data == "")
                    {
                        $resultado = 'No se registraron acciones';
                    }
                    else
                    {
                        $aux = '';
                        
                        $separador = '<br /> - ';
                        
                        $arrData = explode(',', $data);
                        
                        foreach ($arrData as $key => $value) 
                        {
                            switch ($value) {              
                                case 1:
                                    $aux .= $separador . 'Llamada a la empresa para buscar referencia';
                                    break;
                                case 2:
                                    $aux .= $separador . 'Dejar nota de contacto';
                                    break;
                                case 3:
                                    $aux .= $separador . 'Formulario de constancia de visita llenado';
                                    break;
                                case 4:
                                    $aux .= $separador . 'Planilla de constancia de verificación y georrefenciación';
                                    break;
                                default:
                                    $aux .= '';
                                    break;
                            }
                        }
                        
                        $resultado = $aux;
                    }
                    
                }
                
                if($tipo == 'form_deseable')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Deseable';
                            break;
                        case 2:
                            $resultado = 'De Precaución';
                            break;
                        case 3:
                            $resultado = 'No Deseable';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'accion_catalogo')
		{
                    switch ($data) {              
                        case 0:
                            $resultado = 'Creación';
                            break;
                        case 1:
                            $resultado = 'Derivar Instancia';
                            break;
                        case 2:
                            $resultado = 'Observar';
                            break;
                        case 3:
                            $resultado = 'VoBo Cumplimiento';
                            break;
                        case 4:
                            $resultado = 'VoBo Legal';
                            break;
                        case 5:
                            $resultado = 'Aprobar Insertar en Core';
                            break;
                        case 6:
                            $resultado = 'Generar Excepción';
                            break;
                        case 7:
                            $resultado = 'Rechazar';
                            break;
                        case 8:
                            $resultado = 'Entrega de Servicio';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'ci_pertenece')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'Propietario';
                            break;
                        case 2:
                            $resultado = 'Representante Legal';
                            break;
                        default:
                            $resultado = '';
                            break;
                    }
                }
                
                if($tipo == 'evaluacion_doc')
		{
                    switch ($data) {              
                        case 1:
                            $resultado = 'No Aplica';
                            break;
                        case 2:
                            $resultado = 'Adjunto en File';
                            break;
                        case 3:
                            $resultado = 'Requisito con Excepción';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
                }
                
                if($tipo == 'estado_actual')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Creado';
                            break;                
                        case 1:
                            $resultado = 'En Pre-Revisión Cumplimiento';
                            break;
                        case 2:
                            $resultado = 'Completado Pre-Revisión Cumplimiento';
                            break;
                        case 3:
                            $resultado = 'Aprobado (en el flujo)';
                            break;
                        case 4:
                            $resultado = 'Afiliado';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'excepcion')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Sin excepción';
                            break;                
                        case 1:
                            $resultado = 'Excepción Generada';
                            break;
                        case 2:
                            $resultado = 'Excepción Aprobadaa';
                            break;
                        case 3:
                            $resultado = 'Excepción Rechazada';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'excepcion_estado')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Normal';
                            break;                
                        case 1:
                            $resultado = 'Excepción Generada';
                            break;
                        case 2:
                            $resultado = 'Excepción Aprobadaa';
                            break;
                        case 3:
                            $resultado = 'Excepción Rechazada';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
		if($tipo == 'parent')
		{
                    if($data == -1)
                    {
                            $resultado = '<i>Ninguno</i>';
                    }
                    else
                    {                 
                        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosCatalogo($data);
                        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                        if (isset($arrResultado1[0])) 
                        {
                            $resultado = $arrResultado1[0]['catalogo_tipo_codigo'] . ' | ' . $arrResultado1[0]['catalogo_descripcion'];                   
                        }
                        else 
                        {
                            $resultado = 'Parámetro Invalido';
                        }

                    }
		}
		
		if($tipo == 'activo')
		{
                    switch ($data) {
                        case 0:
                            $resultado = $this->lang->line('Catalogo_activo1');                   
                            break;                
                        case 1:
                            $resultado = $this->lang->line('Catalogo_activo2');
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'si_no')
		{
                    switch ($data) {
                        case 0:
                            $resultado = $this->lang->line('Catalogo_no');                   
                            break;                
                        case 1:
                            $resultado = $this->lang->line('Catalogo_si');
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'empresa_categoria')
		{
                    switch ($data) {
                        case 1:
                            $resultado = 'Comercio';                   
                            break;                
                        case 2:
                            $resultado = 'Establecimiento/Sucursal';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'tipo_observacion')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Sin Observación';                   
                            break;                
                        case 1:
                            $resultado = 'Observación Cumplimiento';
                            break;
                        case 2:
                            $resultado = 'Observación Legal';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'consolidado')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'No Consolidado';                   
                            break;                
                        case 1:
                            $resultado = 'Consolidado';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'estado_observacion')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Solucionado/Inactivo';                   
                            break;                
                        case 1:
                            $resultado = 'Activo';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'estado_observacion_corto')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Solucionado';                   
                            break;                
                        case 1:
                            $resultado = 'Activo';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'estado_mantenimiento')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Pendiente';                   
                            break;                
                        case 1:
                            $resultado = 'Completado';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'tipo_visita')
		{
                    switch ($data) {
                        case 1:
                            $resultado = 'Lead';                   
                            break;                
                        case 2:
                            $resultado = 'Mantenimiento';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'entregado')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Confirmación Pendiente';                   
                            break;                
                        case 1:
                            $resultado = 'Entrega del Servicio Confirmada';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
		
		if($tipo == 'se_envia')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'No se Envía';                   
                            break;                
                        case 1:
                            $resultado = 'Se Envía';
                            break;
                        default:
                            $resultado = 'Parámetro Invalido';
                            break;
                    }
		}
                
                if($tipo == 'estado_solicitud')
		{
                    switch ($data) {
                        case 0:
                            $resultado = 'Pendiente';                   
                            break;                
                        case 1:
                            $resultado = 'Aprobado';
                            break;
                        default:
                            $resultado = 'Rechazado';
                            break;
                    }
		}
		
		return($resultado);
	}
	
	function GetValorCatalogoDB($data, $tipo) {
		
		$this->load->model('mfunciones_logica');
	
		if($tipo == 'tipo_persona')
		{
                    $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleCatalogoTipo($data);
                    $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                    if (isset($arrResultado1[0])) 
                    {                
                        $resultado = $arrResultado1[0]['tipo_persona_nombre'];
                    } 
                    else 
                    {
                        $resultado = 'No Corresponde';
                    }
		}
		else
		{
                    $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleCatalogo($data, $tipo);
                    $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                    if (isset($arrResultado1[0])) 
                    {                
                            $resultado = $arrResultado1[0]['catalogo_descripcion'];
                    } 
                    else 
                    {
                            $resultado = 'Parámetro Invalido';
                    }
		}
		
		return($resultado);
	}
    
    function UsuarioActualizarFechaLogin($data) {
        
        $this->load->model('mfunciones_logica');
        
        // Se registra la fecha del Login
	$fecha_login = date('Y-m-d H:i:s');
        
        $this->mfunciones_logica->UsuarioActualizarFechaLogin($fecha_login, $data);  
    }
        
    function getUltimoAcceso($data) {
        
        $fecha = $this->getFormatoFechaD_M_Y_H_M($data);
        
        if($fecha == '30/11/-0001 00:00' || $fecha == '01/01/1500 00:00')
        {
            $resultado = "DÍA DE HOY";
        }
        else
        {
            $resultado = $fecha;
        }
        
        return($resultado);
    }
    
    function getDiasPassword($data, $tipo) {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();
        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if($tipo == 'max'){ $dias_cambio_pass = $arrResultado[0]['conf_duracion_max']; }
        
        if($tipo == 'min'){ $dias_cambio_pass = $arrResultado[0]['conf_duracion_min']; }
        
        $fecha_pass = strtotime($this->getFormatoFechaDateTime($data));
        
        $fecha_actual = strtotime(date('Y-m-d H:i:s'));
		
        $diferencia_dias = floor(($fecha_actual - $fecha_pass) / (60 * 60 * 24));
        
        if((int)$diferencia_dias < 0)
        { 
            $resultado = 0;
        }
        elseif ($tipo == 'max')
        {
            if (($dias_cambio_pass - $diferencia_dias) < 0)
            {
                $resultado = 0;
            }
            else
            {
                $resultado = $dias_cambio_pass - $diferencia_dias;
            }
        }
        else
        {
            if (($diferencia_dias - $dias_cambio_pass) < 0)
            {
                $resultado = 0;
            }
            else
            {
                $resultado = ($diferencia_dias - $dias_cambio_pass) + 1;
            }
        }
		
        return($resultado);
    }
    
    function ListadoMenu($codigo_rol) {
        
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_generales');

        // Se busca en la base de datos el listado de menus que pueden acceder al sistema de acuerdo al rol del usuario
        $arrMenu= $this->mfunciones_logica->ObtenerMenuPorRol($codigo_rol);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrMenu);
                
        return $arrMenu;        
    }
    
    function RequisitosFortalezaPassword() {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);                
        
        $conf_credenciales_long_min = $arrResultado[0]["conf_long_min"];
        $conf_credenciales_long_max = $arrResultado[0]["conf_long_max"];
        $conf_credenciales_req_upper = $arrResultado[0]["conf_req_upper"];
        $conf_credenciales_req_num = $arrResultado[0]["conf_req_num"];
        $conf_credenciales_req_esp = $arrResultado[0]["conf_req_esp"];
        
        $mensaje_error = "La contraseña debe cumplir: <br /><br /> - $conf_credenciales_long_min Caractéres Mínimo <br /> - $conf_credenciales_long_max Caractéres Máximo";
        
        if ($conf_credenciales_req_upper == 1) { $mensaje_error .= "<br /> - Almenos 1 Mayúscula"; }
        if ($conf_credenciales_req_num == 1) { $mensaje_error .= "<br /> - Almenos 1 Número"; }
        if ($conf_credenciales_req_esp == 1) { $mensaje_error .= "<br /> - Almenos 1 Caractér Especial (!@#$%&/¿?¡+)"; }
                
        $resultado = $mensaje_error;
        
        return($resultado);
    }
    
    function VerificaFortalezaPassword($data) {
        
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);                
        
        $conf_credenciales_long_min = $arrResultado[0]["conf_long_min"];
        $conf_credenciales_long_max = $arrResultado[0]["conf_long_max"];
        $conf_credenciales_req_upper = $arrResultado[0]["conf_req_upper"];
        $conf_credenciales_req_num = $arrResultado[0]["conf_req_num"];
        $conf_credenciales_req_esp = $arrResultado[0]["conf_req_esp"];
        
        $regex = '/^';
        if ($conf_credenciales_req_upper == 1) { $regex .= '(?=.*[A-Z])'; }                         // Almenos 1 Mayúscula
        if ($conf_credenciales_req_num == 1) { $regex .= '(?=.*\d)'; }                              // Almenos 1 Número
        if ($conf_credenciales_req_esp == 1) { $regex .= '(?=.*[!@#$%^&+=])'; }                     // Almenos 1 Caractér Especial
        $regex .= '.{' . $conf_credenciales_long_min . ',' . $conf_credenciales_long_max . '}$/';   // Debe cumplir el mínimo y máximo de caractéres
                
        if(preg_match($regex, $data)) 
        {
            $resultado = "ok";
        } 
        else 
        {
            $resultado = $this->RequisitosFortalezaPassword();
        }
        
        return($resultado);
    }
    
    // Funciones de modelado de jerarquía TREE
    
    function createTree(&$list, $parent){
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['etapa_id']])){
                $l['children'] = $this->createTree($list, $list[$l['etapa_id']]);
            }
            $tree[] = $l;
        } 
        return $tree;
    }
    
    function menu($arr) {
        echo "<ul>";
        foreach ($arr as $val) {

            if (!empty($val['children'])) {
                echo "<li> <span data-balloon-length='medium' data-balloon='" . $val['etapa_detalle'] . "' data-balloon-pos='right'><a onclick=\"Ajax_CargarAccion_EditarEtapa(" . $val['etapa_id'] . ", " . $val['etapa_categoria'] . ");\"> " . $val['etapa_nombre'] . ' <br /> (' . $val['rol_nombre'] . ') </a></span>';
                $this->menu($val['children']);
                echo "</li>";
            } else {
                echo "<li> <span data-balloon-length='medium' data-balloon='" . $val['etapa_detalle'] . "' data-balloon-pos='right'><a onclick=\"Ajax_CargarAccion_EditarEtapa(" . $val['etapa_id'] . ", " . $val['etapa_categoria'] . ");\"> " . $val['etapa_nombre'] . ' <br /> (' . $val['rol_nombre'] . ') </a></span> </li>';
            }
        }
        echo "</ul>";
    }
    
    
    // Funciones de modelado de jerarquía TREE FIN
    
    function GetDatosEmpresaCorreo($codigo_prospecto)
    {        
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
        // Listado Detalle Empresa
        $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleEmpresaCorreo($codigo_prospecto);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        return $arrResultado1;        
    }
    
    function GetDatosEmpresa($codigo_empresa)
    {        
        $this->load->model('mfunciones_logica');
        $this->lang->load('general', 'castellano');
        
        // BUSCAR LA INFORMACIÓN DE LA EMPRESA        
        // Listado Detalle Empresa
        $arrResultado1 = $this->mfunciones_logica->ObtenerDetalleEmpresa($codigo_empresa);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        return $arrResultado1;        
    }
    
    function ObtenerRegionUsuario($usuario_codigo)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCodigoRegionUsuario($usuario_codigo) ;
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['estructura_regional_id'];
        }
        else
        {
            return 0;
        }
    }
    
    function ObtenerNombreRegionUsuario($usuario_codigo)
    {
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerCodigoRegionUsuario($usuario_codigo) ;
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return $arrResultado[0]['estructura_regional_nombre'];
        }
        else
        {
            return 0;
        }
    }
            
    function EnviarCorreo($plantilla, $destinatario_correo, $destinatario_nombre, $arrayParametros = "", $arrayDocumentos = "", $arrayConCopia = "", $arrayConCopiaOculta = "") {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('mylibrary');
        
        ignore_user_abort(1); // Que continue aun cuando el usuario se haya ido
        
        // Dirección del Controlador LOGICA DE NEGOCIO
        $url = base_url() . "Correo/Enviar";
        //$url = "https://atc.redcetus.com/Correo/Enviar";
        
        // Se capturan los valores
        
        $param = array(
            'plantilla' => $plantilla,
            'destinatario_correo' => $destinatario_correo,
            'destinatario_nombre' => $destinatario_nombre,
            'arrayParametros' => $arrayParametros,
            'arrayDocumentos' => $arrayDocumentos,
            'arrayConCopia' => $arrayConCopia,
            'arrayConCopiaOculta' => $arrayConCopiaOculta,
            );
        
        $this->mylibrary->do_in_background($url, $param);
        
        return TRUE;
    }
    
    /*************** LECTOR QR - INICIO ****************************/
    
    function generate_uid($l = 8) {
        
        $unico = FALSE;
        
        while ($unico == FALSE) 
        {
            $str = "";
            for ($x = 0; $x < $l; $x++)
            {
                $str .= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1); 
            }
            
            // Preguntar en la tabla si existe el ID, caso contrario vuelve a generarlo
            
            $unico = TRUE;
        }
        
        
        return $str;
    }
    
    function GeneratorQR($data){
        
        
        $img_path = FCPATH . "html_public/qr_image/";
        
        // -----------------------------------
        // Remove old QR
        // -----------------------------------

        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec);

        $current_dir = @opendir($img_path);

        while ($filename = @readdir($current_dir))
        {
            if ($filename != "." and $filename != ".." and $filename != "index.html")
            {
                $name = str_replace(".png", "", $filename);

                if (($name + 57600) < $now)
                {
                    @unlink($img_path.$filename);
                }
            }
        }

        @closedir($current_dir);
        
        // Create QR Code
        
        $this->load->library('ciqrcode');
        $qr_image= $now . '.png';
        $params['data'] = $data;
        $params['level'] = 'H';
        $params['size'] = 8;
        $params['savename'] = $img_path . $qr_image;
        
        if($this->ciqrcode->generate($params))
        {
            return $qr_image;	
        }
        else
        {
            return FALSE;
        }
        
    }

/*************** LECTOR QR - FIN ****************************/
    
    function GeneraToken() {
        
        $token = sha1(uniqid(mt_rand(), false));
        return $token;
    }
    
    function EnviaCorreoVerificacion($tipo_visita, $solicitante_nombre, $solicitante_email, $identificador, $token) {
        
        // Se crea el URL para la confirmación de la Solicitud
        
        $url_confirmacion = site_url('Confirmar?visita=' . $tipo_visita . '&id=' . $identificador . '&token=' . $token);
                
        $correo_enviado = $this->EnviarCorreo('verificar_solicitud', $solicitante_email, $solicitante_nombre, $url_confirmacion, 0);

        if(!$correo_enviado)
        {
            return FALSE;
        }
        
        return TRUE;
        
    }
    
    function VisitaEnlaceCalendario($tipo_visita, $fecha_ini, $fecha_fin, $direccion)
    {
        $this->lang->load('general', 'castellano');
        
        // En el caso que no se haya definido la Zona Horaria
        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set("America/La_Paz");
        }

        $timezone  = +0; //(GMT -5:00) EST (U.S. & Canada)

        // Se convierten las fechas al formato del calendario

        $fecha_ini = $this->getFormatoFechaDateTime($fecha_ini);
        $fecha_fin = $this->getFormatoFechaDateTime($fecha_fin);
        
        $fecha_inicio = gmdate("Ymd\THis\Z", strtotime($fecha_ini) + 3600*($timezone+date("I")));
        $fecha_final = gmdate("Ymd\THis\Z", strtotime($fecha_fin) + 3600*($timezone+date("I")));

        $titulo = $this->lang->line('correo_calendario_titulo');

        // Dependiento del Tipo de Visita, se establece el Detalle del evento

        // 1 = Prospecto		2 = Mantenimiento
        if($tipo_visita == 1)
        {
            $detalle = $titulo . $this->lang->line('correo_calendario_afiliacion');
        }
        else
        {
            $detalle = $titulo . $this->lang->line('correo_calendario_mantenimiento');
        }

        // Se convierte los caractéres en el formato aceptado por la URL

        $titulo = rawurlencode($titulo);
        $detalle = rawurlencode($detalle);

        // No seteado
        $direccion_visita = rawurlencode($direccion);

        // Se construye el Enlace

        $enlace_calendario = '<a style="color: #f5811e; text-decoration: underline;" href="http://www.google.com/calendar/event?action=TEMPLATE&dates=' . $fecha_inicio . '%2F' . $fecha_final . '&text=' . $titulo . '&location=' . $direccion_visita . '&details=' . $detalle . '"> Agendar en Mi Calendario </a>';

        return $enlace_calendario;
    }
    
    function getDiasLaborales($start, $end) {
    
        // SE CALCULA EN HORAS
        
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $periodInterval = new DateInterval( "PT1H" );

        $period = new DatePeriod( $startDate, $periodInterval, $endDate );
        $count = 0;

        // Se obtienen los días te atención

        $this->load->model('mfunciones_logica');

        $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);

        $arrSemana = explode(",", $arrResultado3[0]['conf_atencion_dias']);

        $workingDays = $arrSemana; # formato = N (1 = Lunes, ...)
        $holidayDays = array(
                            '*-01-01',
                            '*-01-22',
                            '*-05-01',
                            '*-06-21',
                            '*-08-06',
                            '*-11-02',
                            '*-12-25',
                            '2018-02-12',
                            '2018-02-13',
                            '2018-03-30',
                            '2018-05-31',
                            '2019-03-04',
                            '2019-03-05',
                            '2019-04-19',
                            '2019-06-20',
                            '2020-02-24',
                            '2020-02-25',
                            '2020-04-10',
                            '2020-06-11',
                            '2021-02-15',
                            '2021-02-16',
                            '2021-04-02'

            ); # Días festivos... es necesario configurar para mas adelante	

        // Se obtienen los Horarios de Atención

        $hora1_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde1']));
        $hora1_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde1']));
        $hora1_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta1']));
        $hora1_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta1']));

        $hora2_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde2']));
        $hora2_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde2']));
        $hora2_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta2']));
        $hora2_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta2']));

        foreach($period as $date)
        {

            $startofday1 = clone $date;
            $startofday1->setTime($hora1_inicio_hora, $hora1_inicio_minuto);
            $startofday2 = clone $date;
            $startofday2->setTime($hora1_fin_hora, $hora1_fin_minuto);

            $endofday1 = clone $date;
            $endofday1->setTime($hora2_inicio_hora, $hora2_inicio_minuto);
            $endofday2 = clone $date;
            $endofday2->setTime($hora2_fin_hora, $hora2_fin_minuto);

            if( ($date >= $startofday1 && $date < $startofday2) || ($date >= $endofday1 && $date < $endofday2))
            {
                if (!in_array($date->format('N'), $workingDays)) continue;
                if (in_array($date->format('Y-m-d'), $holidayDays)) continue;
                if (in_array($date->format('*-m-d'), $holidayDays)) continue;

                $count++;
            }
        }

        return $count;
    }

    public function getDiasLaboralesCache($start, $end) {

        // SE CALCULA EN HORAS

        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $periodInterval = new DateInterval( "PT1H" );

        $period = new DatePeriod( $startDate, $periodInterval, $endDate );
        $count = 0;

        // Se obtienen los días te atención

        if ($this->cache_dias_laborales=== null) {
            $this->load->model('mfunciones_logica');
            $this->cache_dias_laborales = new stdClass();
            $arrResultado3 = $this->mfunciones_logica->ObtenerDatosConf_General();
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado3);
            $arrSemana = explode(",", $arrResultado3[0]['conf_atencion_dias']);
            $this->cache_dias_laborales->workingDays = $arrSemana; # formato = N (1 = Lunes, ...)
            $this->cache_dias_laborales->holidayDays = array(
                '*-01-01',
                '*-01-22',
                '*-05-01',
                '*-06-21',
                '*-08-06',
                '*-11-02',
                '*-12-25',
                '2018-02-12',
                '2018-02-13',
                '2018-03-30',
                '2018-05-31',
                '2019-03-04',
                '2019-03-05',
                '2019-04-19',
                '2019-06-20',
                '2020-02-24',
                '2020-02-25',
                '2020-04-10',
                '2020-06-11',
                '2021-02-15',
                '2021-02-16',
                '2021-04-02'
            ); # Días festivos... es necesario configurar para mas adelante

            // Se obtienen los Horarios de Atención


            $this->cache_dias_laborales->hora1_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde1']));
            $this->cache_dias_laborales->hora1_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde1']));
            $this->cache_dias_laborales->hora1_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta1']));
            $this->cache_dias_laborales->hora1_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta1']));

            $this->cache_dias_laborales->hora2_inicio_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_desde2']));
            $this->cache_dias_laborales->hora2_inicio_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_desde2']));
            $this->cache_dias_laborales->hora2_fin_hora = (int)date('H', strtotime($arrResultado3[0]['conf_atencion_hasta2']));
            $this->cache_dias_laborales->hora2_fin_minuto = (int)date('i', strtotime($arrResultado3[0]['conf_atencion_hasta2']));

        }


        foreach($period as $date)
        {
            $startofday1 = clone $date;
            $startofday1->setTime($this->cache_dias_laborales->hora1_inicio_hora, $this->cache_dias_laborales->hora1_inicio_minuto);
            $startofday2 = clone $date;
            $startofday2->setTime($this->cache_dias_laborales->hora1_fin_hora, $this->cache_dias_laborales->hora1_fin_minuto);
            $endofday1 = clone $date;
            $endofday1->setTime($this->cache_dias_laborales->hora2_inicio_hora, $this->cache_dias_laborales->hora2_inicio_minuto);
            $endofday2 = clone $date;
            $endofday2->setTime($this->cache_dias_laborales->hora2_fin_hora, $this->cache_dias_laborales->hora2_fin_minuto);
            if( ($date >= $startofday1 && $date < $startofday2) || ($date >= $endofday1 && $date < $endofday2))
            {
                if (!in_array($date->format('N'), $this->cache_dias_laborales->workingDays)) continue;
                if (in_array($date->format('Y-m-d'), $this->cache_dias_laborales->holidayDays)) continue;
                if (in_array($date->format('*-m-d'), $this->cache_dias_laborales->holidayDays)) continue;
                $count++;
            }
        }

        return $count;
    }

    function TiempoEtapaProspecto($fecha_asignacion_etapa, $codigo_etapa) {
        
        $this->load->model('mfunciones_logica');

        $resultado = 0;
        
        // Paso 1: Se obtiene los datos de la etapa
        
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);
        
        $tiempo_etapa = $arrEtapa[0]['etapa_tiempo'];
        
        // Paso 2: Se utiliza la función para obtener la diferencia
        
        $date1 = new DateTime($fecha_asignacion_etapa);
        $date2 = new DateTime(date('Y-m-d H:i:s'));

        //$diferencia_dias = $date2->diff($date1)->format("%a");
        
        // -> Para Horas
        $fecha_asignacion_etapa = date('Y-m-d H:i:s', strtotime($fecha_asignacion_etapa));
        $fecha_actual = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        
        // -> Para Días
        // $fecha_asignacion_etapa = date('Y-m-d', strtotime($fecha_asignacion_etapa));
        // $fecha_actual = date('Y-m-d', strtotime(date('Y-m-d H:i:s')));
        
        $diferencia_dias = $this->getDiasLaborales($fecha_asignacion_etapa, $fecha_actual);
        
        // Paso 4: Se obtiene el porcentaje 
        
        $total_porcentaje = 100 - round(($diferencia_dias*100)/$tiempo_etapa);
        
        return $total_porcentaje;
        
    }
    
    function TiempoEtapaColor($codigo) {
        
        $icono = '<span class="tiempo_0" title="Atrasado"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        
        if($codigo > 50)
        {
            $icono = '<span class="tiempo_100" title="A tiempo"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        }        
        elseif($codigo >= 0)
        {
            $icono = '<span class="tiempo_50" title="Pendiente"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        }
        elseif($codigo < 0)
        {
            $icono = '<span class="tiempo_0" title="Atrasado"><i class="fa fa-flag" aria-hidden="true"></i> </span>';
        }
        
        return $icono;        
    }
    
    function TiempoEtapaResumen($arrRecibido) {
        
        $contador_100 = 0;
        $contador_50 = 0;
        $contador_0 = 0;
        
        if (isset($arrRecibido[0])) 
        {                        
            foreach ($arrRecibido as $key => $value) 
            {
                if($value["tiempo_etapa"] > 50)
                {
                    $contador_100++;
                }
                elseif($value["tiempo_etapa"] >= 0)
                {
                    $contador_50++;
                }
                elseif($value["tiempo_etapa"] < 0)
                {
                    $contador_0++;
                }
            }
        }
        
        $arrResultado[0] = array(
                    "contador_100" => $contador_100,
                    "contador_50" => $contador_50,
                    "contador_0" => $contador_0
        );
        
        return $arrResultado;
    }
    
    /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO Y ENVIAR CORREO (último parámetro 1=Envio a etapas hijas    2=Envio a etapa específica ****/
    function SeguimientoHitoProspecto($prospecto_id, $etapa_nueva, $etapa_actual, $accion_usuario, $accion_fecha, $enviar_correo = 0) {
        
        $this->load->model('mfunciones_logica');
        
        /*** Actualizar Etapa del Prospecto ***/
        $this->mfunciones_logica->UpdateEtapaProspecto($etapa_nueva, $accion_usuario, $accion_fecha, $prospecto_id);
        
        $this->mfunciones_logica->HitoProspecto($prospecto_id, $etapa_nueva, $etapa_actual, $accion_usuario, $accion_fecha);
        
        // Si se indicó, se pregunta si se enviará el comercio
        // 1 = Se envía a las etapas Hijas de la Etapa Actual       2 = Se envía a una etapa en específico
        if($enviar_correo == 1)
        {
            $arrEtapa = $this->mfunciones_generales->ObteneRolHijoFlujo($etapa_actual);
                
            if (isset($arrEtapa[0]))
            {
                foreach ($arrEtapa as $key1 => $value1) 
                {
                    // 0 = No Envía Correo      1 = Sí Envía Correo
                    if($value1['etapa_notificar_correo'] == 1)
                    {
                        $rol = $value1['etapa_rol'];

                        $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

                        if (isset($arrResultado4[0])) 
                        {

                            foreach ($arrResultado4 as $key => $value) 
                            {
                                $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                                $destinatario_correo = $value['usuario_email'];

                                // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                                $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia', $destinatario_correo, $destinatario_nombre, $prospecto_id, 0);

                            }
                        }
                    }
                }
            }
        }
        
        // 1 = Se envía a las etapas Hijas de la Etapa Actual       2 = Se envía a una etapa en específico
        if($enviar_correo == 2)
        {
            $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($etapa_nueva);

            if (isset($arrEtapa[0]))
            {
                foreach ($arrEtapa as $key1 => $value1) 
                {
                    // 0 = No Envía Correo      1 = Sí Envía Correo
                    if($value1['etapa_notificar_correo'] == 1)
                    {
                        $rol = $value1['rol_codigo'];

                        $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

                        if (isset($arrResultado4[0])) 
                        {

                            foreach ($arrResultado4 as $key => $value) 
                            {
                                $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                                $destinatario_correo = $value['usuario_email'];

                                // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                                $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia', $destinatario_correo, $destinatario_nombre, $prospecto_id, 0);
                            }
                        }
                    }
                }
            }
        }
        
    }
    
    function ObservarDevolverProspecto($codigo_prospecto, $etapa_nueva, $etapa_actual, $nombre_usuario, $fecha_actual, $tipo_observacion, $observacion) {

        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, $etapa_nueva, $etapa_actual, $nombre_usuario, $fecha_actual, 0);
        /***  REGISTRAR SEGUIMIENTO ***/
        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, $etapa_nueva, 2, 'Observa y Devuelve el Prospecto: ' . $observacion, $nombre_usuario, $fecha_actual);

        // Se procede a actualizar el prospecto para registrar la Observación
        $this->mfunciones_logica->GenerarObservacionProspecto($nombre_usuario, $fecha_actual, $codigo_prospecto);
        
        // Se registra el detalle/justificación de la Observación/Justificación en su tabla y se marca el flag "observado" del prospecto como "1"
        $this->mfunciones_logica->InsertarObservacionProspecto($codigo_prospecto, $_SESSION["session_informacion"]["codigo"], $etapa_actual, $tipo_observacion, $fecha_actual, $observacion, $nombre_usuario, $fecha_actual);
        
        // Se envía el correo electrónico para notificar que se observó el prospecto
        $arrEtapa = $this->mfunciones_logica->VerificaEnvioEtapa($etapa_nueva);

        if (isset($arrEtapa[0]))
        {
            foreach ($arrEtapa as $key1 => $value1) 
            {
                $rol = $value1['rol_codigo'];

                $arrResultado4 = $this->mfunciones_logica->ObtenerDetalleDatosUsuario(2, $rol);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado4);

                if (isset($arrResultado4[0])) 
                {

                    foreach ($arrResultado4 as $key => $value) 
                    {
                        $destinatario_nombre = $value['usuario_nombres'] . ' ' . $value['usuario_app'] . ' ' . $value['usuario_apm'];
                        $destinatario_correo = $value['usuario_email'];

                        // SE PROCEDE CON EL ENVÍO DE CORREO ELECTRÓNICO
                        $correo_enviado = $this->mfunciones_generales->EnviarCorreo('notificar_instancia_observacion', $destinatario_correo, $destinatario_nombre, $codigo_prospecto, 0);

                    }
                }
            }
        }        
    }
    
    function GetDocumentoEnviar($codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumentoEnviar($codigo_documento);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
		
        if (isset($arrResultado1[0])) 
        {
            if($filtro == 'file')
            {			
                $ruta = RUTA_DOCUMENTOS;
                $documento = $arrResultado1[0]['documento_pdf'];

                $path = $ruta . $documento;

                if(file_exists($path))
                {
                        return $path;
                }
                else
                {
                        return FALSE;
                }

            }

            if($filtro == 'nombre')
            {
                return $arrResultado1[0]['documento_nombre'];
            }
            else
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }
    
    function GetDocDigitalizado($codigo_prospecto, $codigo_documento_prospecto, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDocumentoDigitalizar($codigo_prospecto, $codigo_documento_prospecto);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_PROSPECTOS;
            $documento = $arrResultado1[0]['prospecto_carpeta'] . '/' . $arrResultado1[0]['prospecto_carpeta'] . '_' .$arrResultado1[0]['prospecto_documento_pdf'];

            $path = $ruta . $documento;

            if(file_exists($path))
            {
                if($filtro == 'existe')
                {
                    return TRUE;
                }

                if($filtro == 'documento')
                {
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    $data = file_get_contents($path);
                    $base64 = base64_encode($data);

                    return $base64;
                }
            }
            else
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }
    
    function GetInfoDigitalizado($codigo_prospecto, $codigo_documento, $filtro) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->VerificaDocumentosDigitalizar($codigo_prospecto, $codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $ruta = RUTA_PROSPECTOS;
            $documento = $arrResultado1[0]['prospecto_carpeta'] . '/' . $arrResultado1[0]['prospecto_carpeta'] . '_' .$arrResultado1[0]['prospecto_documento_pdf'];

            $path = $ruta . $documento;

            if(file_exists($path))
            {
                if($filtro == 'existe')
                {
                    return TRUE;
                }

                if($filtro == 'documento')
                {
                    // Obtener la cadena base64 de un documento especifico de un prospecto específico
                    $data = file_get_contents($path);
                    $base64 = base64_encode($data);

                    return $base64;
                }
            }
            else
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
    }
	
    function GetDocumentoBase64_Ruta($ruta_documento) {
            
        // Obtener la cadena base64 de un documento especifico de un prospecto específico
        $data = file_get_contents($ruta_documento);
        $base64 = base64_encode($data);

        return $base64;
    }
    
    function GetPerfilUsuario($usuario_codigo, $perfil_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosUsuarioPerfil($usuario_codigo, $perfil_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetPermisoPorPerfil($codigo_usuario, $codigo_perfil) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->VerificaPermisoPorPerfil($codigo_usuario, $codigo_perfil);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetMenuRol($rol_codigo, $menu_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosRolMenu($rol_codigo, $menu_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetDocumentoPersona($persona_codigo, $documento_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerDatosPersonaDocumento($persona_codigo, $documento_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetServicioCampana($campana_codigo, $servicio_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerServicioCampana($campana_codigo, $servicio_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GetServicioSolicitud($solicitud_codigo, $servicio_codigo) {
            
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerServicioSolicitud($solicitud_codigo, $servicio_codigo);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function GuardarDocumentoBase64($codigo_prospecto, $codigo_documento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObtenerNombreDocumento($codigo_documento);        
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
			
            // 1. Obtener el nombre del documento ¿

            if (isset($arrResultado1[0])) 
            {
                $nombre_documento = $this->TextoNoAcentoNoEspacios($arrResultado1[0]['documento_nombre']);

                //Se añade la fecha y hora al final
                $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';

                $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto . '/afn_' . $codigo_prospecto . '_' . $nombre_documento;

                $pdf = $documento_pdf_base64;

                $decoded = base64_decode($pdf);			

                if(!file_put_contents($path, $decoded))
                {
                    if(file_exists ($path))
                    {
                        unlink($path);
                    }					

                    return FALSE;
                }
                else
                {
                    return $nombre_documento;
                }
            }
            else 
        {
            return FALSE;
        }
    }
    
    function GuardarDocumentoMantenimientoBase64($codigo_mantenimiento, $documento_pdf_base64) {
		
        $this->load->model('mfunciones_logica');

        $nombre_documento = 'Capacitacion';

        //Se añade la fecha y hora al final
        $nombre_documento .= '__' . date('Y-m-d_H_i_s') . '.pdf';

        $path = RUTA_MANTENIMIENTOS . 'man_' . $codigo_mantenimiento . '/man_' . $codigo_mantenimiento . '_' . $nombre_documento;

        $pdf = $documento_pdf_base64;

        $decoded = base64_decode($pdf);			

        if(!file_put_contents($path, $decoded))
        {
            if(file_exists ($path))
            {
                    unlink($path);
            }					

            return FALSE;
        }
        else
        {
            return $nombre_documento;
        }
    }
    
    function TextoNoAcentoNoEspacios($data)
    {		
        //Se quitan los puntos
        $data = str_replace(".", "", $data);
        //Se quitan las comas
        $data = str_replace(",", "", $data);
        //Se quitan los punto y coma
        $data = str_replace(";", "", $data);
        //Se quitan los slash
        $data = str_replace("/", "", $data);			
        //Se remplazan los espacios
        $data = str_replace(" ", "_", $data);


        $data = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $data
        );

        $data = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $data 
        );

        $data = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $data
        );

        $data = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                $data
        );

        $data = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $data
        );

        $data = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C'),
                $data
        );

        $data = substr($data, 0, 175);

        return $data;
    }

    // Función para la notificación por PUSH de la APP
    
    function EnviarNotificacionPush($tipo_notificacion, $tipo_visita, $codigo_visita)
    {
        
        // PASO 1: Dependiento del tipo de Visita, se obtienen sus datos
            // 1 = Prospecto        2 = Mantenimiento (Código de la empresa)        3 = Se recibe el ID del Agente
        if($tipo_visita == 1)
        {
            $arrDatosProspecto = $this->GetDatosEmpresaCorreo($codigo_visita);
            $empresa_nombre = $arrDatosProspecto[0]['camp_nombre'];
            $empresa_categoria = $arrDatosProspecto[0]['empresa_categoria'];
            $usuario_id = $arrDatosProspecto[0]['usuario_id'];
            
            $data_extra = 'prospect_to_home';
            $accion_click = 'OPEN_PROSPECT_ACTIVITY';
        }
        
        if($tipo_visita == 2)
        {
            $arrEmpresa = $this->GetDatosEmpresa($codigo_visita);
            
            $empresa_nombre = $arrEmpresa[0]['empresa_nombre'];
            $empresa_categoria = $arrEmpresa[0]['empresa_categoria'];
            $usuario_id = $arrEmpresa[0]['usuario_id'];
            
            $data_extra = 'maintenance_to_home';
            $accion_click = 'OPEN_MAINTENANCE_ACTIVITY';
            
        }
        
        if($tipo_visita == 3)
        {
            $usuario_id = $codigo_visita;
            
            $data_extra = 'prospect_to_home';
            $accion_click = 'OPEN_PROSPECT_ACTIVITY';
        }
        
        // Se setea en vacio (Cambiar cuando sea requerido)
        
        $data_extra = '';
        $accion_click = '';
        
        // PASO 2: Se obtienen los datos neceasarios del Prospecto/Mantenimiento
        
        $titulo = '';
        $mensaje = '';
        
        // 1 = Nuevo Prospecto CN-01        2 = Pre-Afiliación (revisión por cumplimiento) completada CN-02       3 = Prospecto Observado CN-03     4 = Asignar Visita CN-04
        
        switch ($tipo_notificacion) 
        {            
            case 1:
                    $titulo = 'Nuevos Leads Asignados';
                    $mensaje = 'Puede ver el detalle ingresando a la App';

                break;
            
            case 2:
                    $titulo = 'Pre-Afiliación Aprobada';
                    $mensaje = 'Aprobado pre-afiliación del ' . $empresa_categoria . ': ' . $empresa_nombre . ', puede continuar con su consolidación';

                break;
            
            case 3:
                    $titulo = 'Lead Observado';
                    $mensaje = 'Observado en: ' . $empresa_nombre;

                break;
            
            case 4:
                    $titulo = 'Nueva Visita Asignada';
                    $mensaje = $empresa_categoria . ': ' . $empresa_nombre;

                break;
            
            case 5:
                    $titulo = 'Se ha rechazado una Verificación';
                    $mensaje = $empresa_categoria . ': ' . $empresa_nombre;

                break;
            
            case 6:
                    $titulo = 'Se ha aprobado una Verificación';
                    $mensaje = $empresa_categoria . ': ' . $empresa_nombre;

                break;
            
            case 7:
                    $titulo = 'Bloque Re-Agendado Asignado';
                    $mensaje = 'Se le asignó un nuevo bloque';

                break;

            default:
                return FALSE;
                break;
        }
        
        
        // Key de la API de la consola de Google para Firebase
        $api_access_key = 'AAAAJmr4Mt8:APA91bEJBosYAJ53DWL0PGaUgutCjwEERBxuvHa4nB1Us5YXpwj82-TOCw5IDd8QvbZxg5RwdUObYtl6yCJDSF0qghNRCINg6I-cCyFCMpAebspzsgi_v8SxkcmkGJQ5y55HldmhJqeH';
        
        // Preparar el bundle
        $msg = array
                (
                    'title'         => $titulo,
                    'body'          => $mensaje,
                    'vibrate'       => 1,
                    'sound'         => 1
                );

        $fields = array
                (
                    'to'            => '/topics/senaf_' . $usuario_id,
                    'notification'  => $msg
                );

        $headers = array
                (
                    'Authorization: key=' . $api_access_key,
                    'Content-Type: application/json'
                );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        // echo $result;
    }
    
    function ObteneRolHijoFlujo($codigo_etapa)
    {
        $this->load->model('mfunciones_logica');

        $arrResultado1 = $this->mfunciones_logica->ObteneRolHijoFlujo($codigo_etapa);
        $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
        
        return $arrResultado1;
    }
    
    // Función para verificar si la etapa se configuró para el envío de correo o no
    function VerificaEtapaEnvio($codigo_etapa)
    {
        $this->load->model('mfunciones_logica');

        $arrResultado = $this->mfunciones_logica->VerificaEnvioEtapa($codigo_etapa);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            if($arrResultado[0]['etapa_notificar_correo'] == 1)
            {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    // Función para verificar si un documento de un prospecto esta observado
    function VerificaDocumentoObservado($codigo_prospecto, $codigo_documento)
    {
        $this->load->model('mfunciones_logica');

        $arrResultado = $this->mfunciones_logica->VerDocumentoObservado($codigo_prospecto, $codigo_documento);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
        
	/* FUNCIONES API REST */
	
        // Verifica que la estructura sea la correcta
	function VerificaEstructuraREST($arrayData)
	{            
		
		// Estructura Establecida
		
//            array(
//                "identificador" => "",    // Usuario
//                "password" => "",         // Contraseña
//                "servicio" => "",         // Nombre del Servicio a Ser utilizado
//                "parametros" => array()   // Listado de parámetros
//            );
		
		$a1 = array(
			"identificador" => "",
			"password" => "",
			"servicio" => "",
			"parametros" => array()
		);
		$this->Verificar_ConvertirArray_Hacia_Matriz($a1);
		
		$a2 =  $arrayData;
		
		$resultado = $this->array_diff_key_recursive($a1, $a2);

		return $resultado;
		
	}
    
	function array_diff_key_recursive($a1, $a2)
	{
		$r = array();

		foreach ($a1 as $k => $v)
		{
			if (is_array($v))
			{
				if (!isset($a2[$k]) || !is_array($a2[$k]))
				{
					$r[$k] = $a1[$k];
				}
				else
				{
					if ($diff = $this->array_diff_key_recursive($a1[$k], $a2[$k]))
					{
						$r[$k] = $diff;
					}
				}
			}
			else
			{
				if (!isset($a2[$k]) || is_array($a2[$k]))
				{
					$r[$k] = $v;
				}
			}
		}

		return $r;
	}
	
	function RespuestaREST($arrayData)
	{
		$error = "false";
		$errorMessage = "";
		$errorCode = 0;

		if(empty(array_filter($arrayData)))
		{
				$error = "true";
				$errorMessage = "O_o No se encontraron resultados con los criterios enviados. Puede intentar actualizar la página.";
				$errorCode = "99";
                                $arrayData = "No se realizó la operación.";
		}

		$aux = array(		
				"error" => $error,
				"errorMessage" => $errorMessage,
				"errorCode" => $errorCode,
				"result" => $arrayData		
		);

		$this->Verificar_ConvertirArray_Hacia_Matriz($aux);

		return $aux;
	}
        
        function ConsultaWebServiceNIT($nit)
	{
            
            $arrResultado = array();
            
            /* BORRAR DESPUES SOLO ES PARA PRUEBAS - REMPLAZAR CON LA BÚSQUEDA REAL EN EL WEB SERVICE DE PAYSTUDIO (NAZIR) */
            if($nit == '999')
            {
                
                // Estos valores deberían ser los de la respuesta del Web Service
                
                $parent_nit = "999";
                $parent_tipo_sociedad_codigo = "1";
                $parent_nombre_legal = "Empresa registrada en PayStudio";
                $parent_nombre_fantasia = "Existe en PayStudio";
                $parent_rubro_codigo = "1";
                $parent_perfil_comercial_codigo = "1";
                $parent_mcc_codigo = "5441";

                $arrResultado = array(		
                        "parent_id" => -1,
                        "parent_nit" => $parent_nit,
                        "parent_adquiriente_codigo" => 1,
                        "parent_adquiriente_detalle" => 'ATC SA',
                        "parent_tipo_sociedad_codigo" => $parent_tipo_sociedad_codigo,
                        "parent_tipo_sociedad_detalle" => $this->GetValorCatalogoDB($parent_tipo_sociedad_codigo, 'TPS'),
                        "parent_nombre_legal" => $parent_nombre_legal,
                        "parent_nombre_fantasia" => $parent_nombre_fantasia,
                        "parent_rubro_codigo" => $parent_rubro_codigo,
                        "parent_rubro_detalle" => $this->GetValorCatalogoDB($parent_rubro_codigo, 'RUB'),
                        "parent_perfil_comercial_codigo" => $parent_perfil_comercial_codigo,
                        "parent_perfil_comercial_detalle" => $this->GetValorCatalogoDB($parent_perfil_comercial_codigo, 'PEC'),
                        "parent_mcc_codigo" => $parent_mcc_codigo,
                        "parent_mcc_detalle" => $this->GetValorCatalogoDB($parent_mcc_codigo, 'MCC')
                );
            }

            $this->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);

            return $arrResultado;
	}
	
         /* Función para la incersión de la data a PayStudio a través de su Web Service*/
        function WS_InsertarPayStudio($arrayDatos)
	{
            
            // Si existe error, se muestra la respuesta del Web Service
            $mensaje_error = '';
            
            return $mensaje_error;
        }
        
    function Verificar_ConvertirArray_Hacia_Matriz(&$arrResultado) {
        if (!isset($arrResultado[0]) && $arrResultado != null) {
            $arrResultado = array($arrResultado);
        }
        return $arrResultado;
    }
    
    function VerificaFechaY_M_D($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if($date == '1900-01-01'){ return FALSE; }
        return $d && $d->format('Y-m-d') == $date;
    }
    
    function VerificaFechaD_M_Y($date) {
        $d = DateTime::createFromFormat('d/m/Y', $date);
        return $d && $d->format('d/m/Y') == $date;
    }
    
    function VerificaFechaD_M_Y_H_M($date) {
        $d = DateTime::createFromFormat('d/m/Y H:i', $date);
        return $d && $d->format('d/m/Y H:i') == $date;
    }
    
    function VerificaFechaH_M($date) {
        $d = DateTime::createFromFormat('H:i', $date);
        return $d && $d->format('H:i') == $date;
    }
    
    function VerificaCorreo($data) {
        
        return (filter_var($data, FILTER_VALIDATE_EMAIL));
    }
    
    function getFormatoFechaD_M_Y($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('d/m/Y');
        if ($fecha == "01/01/1900" || $fecha == "01/01/0001" || $dateTime == "0000-00-00") {
            $fecha = "";
        }
        return($fecha);
    }
    
    function getFormatoFechaH_M($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('H:i');
        return($fecha);
    }
    
    function getFormatoFechaD_M_Y_H_M($dateTime) {
        $fecha = new DateTime($dateTime);
        $fecha = $fecha->format('d/m/Y H:i');
        if ($fecha == "01/01/1900T00:00:00" || $fecha == "01/01/0001T00:00:00") {
            $fecha = "";
        }
        return($fecha);
    }
    
    function getFormatoFechaDate($cadenaFecha) {
        if ($cadenaFecha == "") {
            //$date ='1900-01-01';
            $cadenaFechaFormato = '1900-01-01';
        } else {
            $date = str_replace('/', '-', $cadenaFecha);
            $cadenaFechaFormato = date('Y-m-d', strtotime($date));
        }
        return($cadenaFechaFormato);
    }
    
    function getFormatoFechaDateTime($cadenaFecha) {
        if ($cadenaFecha == "") {
            //$date ='1900-01-01';
            $cadenaFechaFormato = '1900-01-01T00:00:00';
        } else {
            $date = str_replace('/', '-', $cadenaFecha);
            $cadenaFechaFormato = date('Y-m-d H:i:s', strtotime($date));
        }
        return($cadenaFechaFormato);
    }
    function getNumeroDecimal($valor_dinero) {
        if ($valor_dinero != "0,00") {
            $valor_dinero = str_replace(".", "", $valor_dinero);
            $numero_decimal = str_replace(",", ".", $valor_dinero);
        } else {
            $numero_decimal = str_replace("0,00", "0.00", $valor_dinero);
        }
        return $numero_decimal;
    }
    function getNumeroDecimalVacio($valor_dinero) {
        $numero_decimal = str_replace("0.00", "", $valor_dinero);
        return $numero_decimal;
    }
    function getNumeroEntero($valor_dinero) {
        $numero_decimal = str_replace(".00", "", $valor_dinero);
        return $numero_decimal;
    }
    function getNumeroEnteroVacio($valor_dinero) {
        $numero_decimal = str_replace("0.00", "", $valor_dinero);
        return $numero_decimal;
    }
    
    public function getMensajeRespuesta($arrRespuesta) {
        if (isset($arrRespuesta['err_existente'])) {
            if ($arrRespuesta['err_existente'] == 0) {
                $respuesta = $arrRespuesta['err_mensaje'];
            } else {
                $err_mensaje = $arrRespuesta['err_mensaje'];
                $err_base = strpos($err_mensaje, "#");
                if ($err_base !== FALSE) {
                    js_error_div_javascript($arrRespuesta['err_mensaje']);
                } else {
                    js_error_div_javascript("Ocurrio un error inesperado, vuelva a intentarlo");
                }
                exit();
            }
        } else {
            $respuesta = 'No se puede realizar la transaccion';
        }
        return $respuesta;
    }
    
    public function htmlArraytoSelected($listaParametricas, $nombreLista, $nombreObj, $idCaja, $codItem, $descripcion, $strValorCaja) {
        $arrLista = $listaParametricas[$nombreLista];
        $arrListaObj = $arrLista[$nombreObj];

        if (!isset($arrListaObj[0])) {
            $arrListaObj = array($arrListaObj);
        }

        $i = 0;
        foreach ($arrListaObj as $key => $value) {
            $arrayObjetos[$i] = array("id" => $value[$codItem], "campoDescrip" => $value[$descripcion]);
            $i++;
        }
        $arr_formulario_cajas[$idCaja] = html_select($idCaja, $arrayObjetos, 'id', 'campoDescrip', '', $strValorCaja);
        return $arr_formulario_cajas;
    }
    
}
?>