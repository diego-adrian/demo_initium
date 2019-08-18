<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
require(APPPATH.'/libraries/REST_Controller.php');
class servicios_app_controller extends REST_Controller
{ 
    public function __construct() {
            parent::__construct();
            $this->load->model('mfunciones_generales');
            $this->load->model('mfunciones_logica');
            $this->lang->load('general', 'castellano');
            $this->load->library('encrypt');
    }
    
    public function ServiciosAPP_post(){

        // 1. SE VERIFICA QUE SE ESTE ENVIANDO EL PARÁMETRO
        if(!isset($_POST['array_servicio']))
        {
            // Si no existe el parámetro se devuelve el error
            $arrError =  array(
                            "error" => true,
                            "errorMessage" => "Servicio Desconocido.",
                            "errorCode" => 101,
                            "result" => array(
                                "mensaje" => $this->lang->line('IncompletoApp')
                            )
                    );
            
            $this->response($arrError, 403);
        }

        // 2. SE CAPTURA EL ARRAY ENVIADO POR LA APP
            $arrRecibida = $this->input->post('array_servicio');
        
        // 3. SE CONVIERTE A ARRAY (SI ES QUE ES JSON)
        
            if($this->isJSON($arrRecibida))
            {
                    $arrRecibida = json_decode($arrRecibida, true);
            }
			
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRecibida);
            
			
        // 4. SE VERIFICA QUE EL FORMATO ESTE CORRECTO
        
            if(!empty($this->mfunciones_generales->VerificaEstructuraREST($arrRecibida)))
            {
                // Si no cumple con la estructura establecida, se devuelve el error 100 (Estructura Inválida del Array)            
                $arrError =  array(		
                                "error" => true,
                                "errorMessage" => "Estructura Invalida de la Solicitud.",
                                "errorCode" => 100,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 400);
            }
        
        // 5. SE VERIFICA QUE LAS CREDENCIALES (USUARIO Y CONTRASEÑA) CORRESPONDAN A UN USUARIO CON ACCESO
            $usuarioAPP = $arrRecibida[0]['identificador'];
            $passwordAPP = $arrRecibida[0]['password'];

            $arrResultadoLogin = $this->mfunciones_logica->VerificaCredencialesAPP($usuarioAPP, $passwordAPP);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultadoLogin);

            if (!isset($arrResultadoLogin[0]))
            {
                // Si las credenciales son incorrectas o el usaurio no existe, se muestra un mensaje de error     
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => "Credenciales Incorrectas.",
                                "errorCode" => 90,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->response($arrError, 401);
            }

            $usuario_codigo = $arrResultadoLogin[0]['usuario_activo'];

            if ($usuario_codigo == 0)
            {
                // Si las credenciales son incorrectas o el usaurio no existe, se muestra un mensaje de error     
                $arrError =  array(
                                "error" => true,
                                "errorMessage" => "La cuenta no esta activa, comuniquese con el Administrador del Sistema.",
                                "errorCode" => 91,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );
                $this->response($arrError, 401);
            }

        // 6. SE OBTIENE EL NOMBRE DEL SERVICIO
        $nombre_servicio = $arrRecibida[0]['servicio'];

        // 7. SE OBTIENE EL ARRAY DE LOS PARÁMETROS

        $arrParametros = $arrRecibida[0]['parametros'];
		
        // 8. SE LLAMA A LAS FUNCIONES DE ACUERDO AL NOMBRE DEL SERVICIO SOLICITADO
        
        switch ($nombre_servicio) 
        {
            
            /*************** FORMULARIOS DINÁMICOS - INICIO ****************************/
            
            case 'ListadoFormPublicados':

                    $arrResultado = $this->ListadoFormPublicados($arrParametros, $usuarioAPP, $nombre_servicio);                    
                
                break;
            
            
            case 'ListaElementosForm':

                    $arrResultado = $this->ListaElementosForm($arrParametros, $usuarioAPP, $nombre_servicio);                    
                
                break;
            
            case 'GuardarRegistroForm':

                    $arrResultado = $this->GuardarRegistroForm($arrParametros, $usuarioAPP, $nombre_servicio, $arrResultadoLogin[0]['ejecutivo_id']);                    
                
                break;
           
            
            /*************** FORMULARIOS DINÁMICOS - FIN ****************************/
            
			case 'ListadoCatalogo':

                    $arrResultado = $this->ListadoCatalogo($arrParametros);
			
            
            default:
                // Si no es ningún servicio disponible, se muestra un mensaje de error
                $arrError =  array(		
                                "error" => "true",
                                "errorMessage" => "Servicio Desconocido.",
                                "errorCode" => 101,
                                "result" => array(
                                    "mensaje" => $this->lang->line('IncompletoApp')
                                )
                        );

                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrError);
                
                $this->response($arrError, 403);

                break;
        }

        // 9. CON EL RESULTADO OBTENIDO DEL SERVICIO SOLICITADO, SE ENVÍA LA RESPUESTA
        $arrResultado = $this->mfunciones_generales->RespuestaREST($arrResultado);

        $this->response($arrResultado, 200);
    }    

    // Función Auxiliar
	
	function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	
    function array_keys_exist($array, $keys) {
            $count = 0;
            if ( ! is_array( $keys ) ) {
                    $keys = func_get_args();
                    array_shift( $keys );
            }
            foreach ( $keys as $key ) {
                    if ( array_key_exists( $key, $array ) ) {
                            $count ++;
                    }
            }

            return count( $keys ) === $count;
    }
    
    /*************** FORMULARIOS DINÁMICOS - INICIO ****************************/
    
    function ListadoFormPublicados($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_registro",
                    "tipo_bandeja"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_registro = $arrDatos['codigo_registro'];
            $tipo_bandeja = $arrDatos['tipo_bandeja'];
        
            
            if($tipo_bandeja != 1)
            {
                $arrResultado = array();
                return $arrResultado;
            }
            
            $arrLista[] = array(
                "form_id" => "1",
                "form_detalle" => "Información del Lead"
            );
            
            $arrLista[] = array(
                "form_id" => "2",
                "form_detalle" => "Administrar el Estado de mi Lead"
            );
            
            $arrLista[] = array(
                "form_id" => "3",
                "form_detalle" => "Actividades de Verificación"
            );
        
            $lst_resultado[0] = array(
                "codigo_registro" => $codigo_registro,
                "tipo_bandeja_codigo" => $tipo_bandeja,
                "tipo_bandeja_detalle" => "Gestión Leads",
                "lista_formularios" => $arrLista
            );
            

            return $lst_resultado;
    }
    
    function ListaElementosForm($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_registro",
                    "tipo_bandeja",
                    "form_id"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_registro = $arrDatos['codigo_registro'];
            $tipo_bandeja = $arrDatos['tipo_bandeja'];
            $form_id = $arrDatos['form_id'];
        
            
            if($tipo_bandeja != 1)
            {
                $arrResultado = array();
                return $arrResultado;
            }
            
            // Se define que formularios requieren el listado de campos del prospecto
            
            if($form_id == 1 || $form_id == 2)
            {
                $arrResultado1 = $this->mfunciones_logica->ListadoDetalleProspecto($codigo_registro);
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                if (isset($arrResultado1[0])) 
                {
                    $i = 0;

                    foreach ($arrResultado1 as $key => $value) 
                    {
                        $dir_geo = $value["prospecto_direccion_geo"];

                        if($value["prospecto_direccion_geo"] == 'null' || $value["prospecto_direccion_geo"] == 'null, null')
                        {
                            $dir_geo = GEO_BCP; 
                        }

                        $item_valor = array(
                            "tipo_persona_id" => $value["tipo_persona_id"],
                            "prospecto_idc" => $value["prospecto_idc"],
                            "prospecto_nombre_cliente" => $value["prospecto_nombre_cliente"],
                            "prospecto_empresa" => $value["prospecto_empresa"],
                            "prospecto_ingreso" => $value["prospecto_ingreso"],
                            "prospecto_direccion" => $value["prospecto_direccion"],
                            "prospecto_direccion_geo" => $dir_geo,
                            "prospecto_telefono" => $value["prospecto_telefono"],
                            "prospecto_celular" => $value["prospecto_celular"],
                            "prospecto_email" => $value["prospecto_email"],
                            "prospecto_tipo_lead" => $value["prospecto_tipo_lead"],
                            "prospecto_matricula" => $value["prospecto_matricula"],
                            "prospecto_fecha_contacto1" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["prospecto_fecha_contacto1"]),
                            "prospecto_monto_aprobacion" => $value["prospecto_monto_aprobacion"],
                            "prospecto_monto_desembolso" => $value["prospecto_monto_desembolso"],
                            "prospecto_fecha_desembolso" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["prospecto_fecha_desembolso"]),
                            "camp_nombre" => $value["camp_nombre"],
                            "etapa_nombre" => $value["etapa_nombre"],
                            "prospecto_etapa" => $value["prospecto_etapa"],
                            "prospecto_comentario" => $value["prospecto_comentario"]
                        );

                        $lst_resultado[$i] = $item_valor;
                        $i++;
                    }
                }
                else
                {
                    $arrResultado = array();
                    return $arrResultado;
                }
            }
            
            $contador_id = 0;
            
            switch ($form_id) {
                case 1:

                    /*** FORMULARIO 1: Información del Lead  INICIO ***/
                    
                    $contador_id = 100; // Cambiar por formulario - $form_id * 100

                    $form_titulo = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'center',
                        'label' => 'FORMULARIO DEL LEAD',
                    );
                    
                    $form_subtitulo1 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«INFO REFERENCIAL»',
                    );
                    
                    $form_subtitulo2 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«REGISTRO DE LA DATA»',
                    );
                    
                    $form_subtitulo3 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«UBICACIÓN»',
                    );
                    
                    $form_subtitulo4 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«CONTACTO»',
                    );
                    
                    $form_subtitulo5 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => ' ',
                    );
                    
                    $form_camp_nombre = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'true',
                                'required' => true,
                                'label' => '» DEPENDE DE CAMPAÑA:',
                                'description' => '☼ Este campo es de sólo lectura e indica la campaña de la que depende el Lead',
                                'placeholder' => 'Registre la Campaña',
                                'name' => 'camp_nombre',
                                'defaultValue' => $lst_resultado[0]['camp_nombre'],
                                'subtype' => 'text',
                                'maxlength' => '45',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_idc = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» IDC:',
                                'description' => '',
                                'placeholder' => 'Registre el IDC del Cliente',
                                'name' => 'prospecto_idc',
                                'defaultValue' => $lst_resultado[0]['prospecto_idc'],
                                'subtype' => 'text',
                                'maxlength' => '15',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_nombre_cliente = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» NOMBRE CLIENTE:',
                                'description' => '',
                                'placeholder' => 'Registre el Nombre del Cliente',
                                'name' => 'prospecto_nombre_cliente',
                                'defaultValue' => $lst_resultado[0]['prospecto_nombre_cliente'],
                                'subtype' => 'text',
                                'maxlength' => '150',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_empresa = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» EMPRESA:',
                                'description' => '',
                                'placeholder' => 'Registre el nombre de la Empresa',
                                'name' => 'prospecto_empresa',
                                'defaultValue' => $lst_resultado[0]['prospecto_empresa'],
                                'subtype' => 'text',
                                'maxlength' => '150',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_ingreso = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'number',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» INGRESO (Bs.):',
                            'description' => '',
                            'placeholder' => 'Registre el Ingreso',
                            'name' => 'prospecto_ingreso',
                            'defaultValue' => $lst_resultado[0]['prospecto_ingreso'],
                            'min' => '1',
                            'max' => '9999999999',
                            'step' => '1'
                        );
                    
                    $form_prospecto_direccion_geo = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'map',
                            'name' => 'prospecto_direccion_geo',
                            'label' => '» DIRECCIÓN GEO:',
                            'defaultValue' => $lst_resultado[0]['prospecto_direccion_geo'],
                        );
                    
                    $form_prospecto_direccion = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» DIRECCIÓN LITERAL:',
                                'description' => '',
                                'placeholder' => 'Registre la Dirección Literal',
                                'name' => 'prospecto_direccion',
                                'defaultValue' => $lst_resultado[0]['prospecto_direccion'],
                                'subtype' => 'text',
                                'maxlength' => '255',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_telefono = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'number',
                                'min' => '1',
                                'max' => '9999999999',
                                'step' => '1',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» TELÉFONO:',
                                'description' => '',
                                'placeholder' => 'Registre el Teléfono',
                                'name' => 'prospecto_telefono',
                                'defaultValue' => $lst_resultado[0]['prospecto_telefono'],
                                'maxlength' => '10',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_celular = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'number',
                                'min' => '1',
                                'max' => '9999999999',
                                'step' => '1',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» CELULAR:',
                                'description' => '',
                                'placeholder' => 'Registre el Celular',
                                'name' => 'prospecto_celular',
                                'defaultValue' => $lst_resultado[0]['prospecto_celular'],
                                'maxlength' => '10',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_email = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» CORREO ELECTRÓNICO:',
                                'description' => '',
                                'placeholder' => 'Registre el Correo',
                                'name' => 'prospecto_email',
                                'defaultValue' => $lst_resultado[0]['prospecto_email'],
                                'subtype' => 'email',
                                'maxlength' => '150',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_productos = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'checkbox-group',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» PRODUCTOS SELECCIONADOS:',
                            'description' => 'Los productos seleccionados por defecto son los de la campaña, puede seleccionar los que requiera.',
                            'name' => 'prospecto_productos',
                            'values' => $this->mfunciones_generales->GetValorCatalogo($codigo_registro, 'form_productos')
                        );
                    
                    $form_prospecto_fecha_contacto1 = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'date',
                            'subtype' => 'date',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» FECHA 1° CONTACTO:',
                            'description' => 'Puede registrar la fecha que realizó el primer contacto respecto al Lead.',
                            'placeholder' => 'Fecha 1° Contacto',
                            'name' => 'prospecto_fecha_contacto1',
                            'defaultValue' => $lst_resultado[0]['prospecto_fecha_contacto1'],
                        );
                    
                    $form_etapa_nombre = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'text',
                            'readonly' => 'true',
                            'required' => true,
                            'label' => '» ESTADO DEL LEAD:',
                            'description' => '☼ Este campo es de sólo lectura e indica el estado del Lead',
                            'placeholder' => 'Registre el Estado del Lead',
                            'name' => 'etapa_nombre',
                            'defaultValue' => $lst_resultado[0]['etapa_nombre'],
                            'subtype' => 'text',
                            'maxlength' => '45',
                            'className' => 'red form-control',
                        );
                    
                    // Nuevos campos
                    
                    $form_comentarios = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'textarea',
                                'subtype' => 'textarea',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» COMENTARIOS:',
                                'description' => '',
                                'placeholder' => 'Registre los Comentarios...',
                                'name' => 'prospecto_comentario',
                                'defaultValue' => $lst_resultado[0]['prospecto_comentario'],
                                'maxlength' => '1000',
                                'rows' => '3',
                                'className' => 'red form-control',
                            );
                    
                    $arrBody[] = $form_titulo;
                    $arrBody[] = $form_subtitulo1;
                    $arrBody[] = $form_camp_nombre;
                    $arrBody[] = $form_etapa_nombre;
                    $arrBody[] = $form_subtitulo2;
                    $arrBody[] = $form_prospecto_idc;
                    $arrBody[] = $form_prospecto_nombre_cliente;
                    $arrBody[] = $form_prospecto_empresa;
                    $arrBody[] = $form_prospecto_ingreso;
                    $arrBody[] = $form_prospecto_telefono;
                    $arrBody[] = $form_prospecto_celular;
                    $arrBody[] = $form_prospecto_email;
                    $arrBody[] = $form_prospecto_productos;
                    
                    $arrBody[] = $form_comentarios;
                    
                    $arrBody[] = $form_subtitulo3;
                    $arrBody[] = $form_prospecto_direccion_geo;
                    $arrBody[] = $form_prospecto_direccion;
                    $arrBody[] = $form_subtitulo4;
                    $arrBody[] = $form_prospecto_fecha_contacto1;
                    $arrBody[] = $form_subtitulo5;

                    $lst_resultado = array(
                        'codigo_registro' => $codigo_registro,
                        'form_id' => $form_id,
                        'form_detalle' => 'Información del Lead',
                        'tipo_bandeja' => $tipo_bandeja,
                        'lista_elementos' => $arrBody
                    );
                    
                    /*** FORMULARIO 1: Información del Lead  FIN ***/

                    break;

                case 2:
                    
                    /*** FORMULARIO 2: Estado del Lead  INICIO ***/
                    
                    $contador_id = 200; // Cambiar por formulario - $form_id * 100
                    
                    // Listado de Estados
                    
                    $arrResultado2 = $this->mfunciones_logica->ObtenerDatosFlujo(-1, 1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado2);

                    if (isset($arrResultado2[0]))
                    {
                        $i = 0;

                        foreach ($arrResultado2 as $key => $value2) {

                            if($lst_resultado[0]['prospecto_etapa'] == $value2["etapa_id"])
                            {
                                $item_valor = array(
                                    "value" => $value2["etapa_id"],
                                    "label" => $value2["etapa_nombre"],
                                    "selected" => true
                                );
                            }
                            else
                            {
                                $item_valor = array(
                                    "value" => $value2["etapa_id"],
                                    "label" => $value2["etapa_nombre"]
                                );
                            }
                            
                            $lst_etapa[$i] = $item_valor;

                            $i++;
                        }
                    }
                    
                    $form_titulo = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'center',
                        'label' => 'ACTUALIZAR ESTADO DEL LEAD',
                    );
                    
                    $form_subtitulo1 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«SELECCIONE EL ESTADO»',
                    );
                    
                    $form_subtitulo2 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«APROBACIÓN»',
                    );
                    
                    $form_subtitulo3 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«DESEMBOLSO»',
                    );
                    
                    $form_prospecto_etapa = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'radio-group',
                        'readonly' => 'false',
                        'required' => true,
                        'label' => '» ACTUALICE EL ESTADO DEL LEAD:',
                        'description' => '☼ El estado seleccionado se guardará en el seguimiento del Lead. Los montos de Aprobación o Desembolso sólo se registrarán si se selecciona el estado respectivo, caso contrario se mantendrá como 0. ',
                        'name' => 'prospecto_etapa',
                        'values' => $lst_etapa
                    );
                    
                    $form_prospecto_monto_aprobacion = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'number',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» INDIQUE EL MONTO DE APROBACIÓN (Bs.):',
                            'description' => '',
                            'placeholder' => 'Registre el Monto de Aprobación',
                            'name' => 'prospecto_monto_aprobacion',
                            'defaultValue' => $lst_resultado[0]['prospecto_monto_aprobacion'],
                            'min' => '1',
                            'max' => '9999999999',
                            'step' => '1'
                        );
                    
                    $form_prospecto_monto_desembolso = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'number',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» INDIQUE EL MONTO DE DESEMBOLSO (Bs.):',
                            'description' => '',
                            'placeholder' => 'Registre el Monto de Desembolso',
                            'name' => 'prospecto_monto_desembolso',
                            'defaultValue' => $lst_resultado[0]['prospecto_monto_desembolso'],
                            'min' => '1',
                            'max' => '9999999999',
                            'step' => '1'
                        );
                    
                    $form_prospecto_fecha_desembolso = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'date',
                            'subtype' => 'date',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» FECHA ESTIMADA DE DESEMBOLSO:',
                            'description' => 'Puede registrar la fecha estimada del desembolso.',
                            'placeholder' => 'Seleccione la fecha',
                            'name' => 'prospecto_fecha_desembolso',
                            'defaultValue' => $lst_resultado[0]['prospecto_fecha_desembolso'],
                        );
                    
                    $form_subtitulo5 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'center',
                        'label' => '☼ Para concluir el Lead, debe seleccionar la opción "Consolidar" en la Pantalla Resumen.',
                    );
                    
                    $form_subtitulo6 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => ' ',
                    );
                    
                    $arrBody[] = $form_titulo;
                    //$arrBody[] = $form_subtitulo1;
                    $arrBody[] = $form_prospecto_etapa;
                    $arrBody[] = $form_subtitulo2;
                    $arrBody[] = $form_prospecto_monto_aprobacion;
                    $arrBody[] = $form_subtitulo3;
                    $arrBody[] = $form_prospecto_monto_desembolso;
                    $arrBody[] = $form_prospecto_fecha_desembolso;
                    $arrBody[] = $form_subtitulo5;
                    $arrBody[] = $form_subtitulo6;
                    
                    $lst_resultado = array(
                        'codigo_registro' => $codigo_registro,
                        'form_id' => $form_id,
                        'form_detalle' => 'Estado del Lead',
                        'tipo_bandeja' => $tipo_bandeja,
                        'lista_elementos' => $arrBody
                    );
                    
                    /*** FORMULARIO 2: Estado del Lead  FIN ***/
                    
                    break;
                    
                case 3:
                    
                    /*** FORMULARIO 3: Actividades de Verificación  INICIO ***/
                    
                    $contador_id = 300; // Cambiar por formulario - $form_id * 100
                    
                    $form_titulo = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'center',
                        'label' => 'ACTIVIDADES DE VERIFICACIÓN',
                    );
                    
                    $form_subtitulo1 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«INFO ADICIONAL»',
                    );
                    
                    $form_subtitulo5 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => ' ',
                    );
                    
                    $form_prospecto_actividades = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'checkbox-group',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» SELECCIONE LAS ACTIVIDADES REALIZADAS:',
                            'description' => 'Seleccione las Actividades Realizadas en la Verificación.',
                            'name' => 'prospecto_actividades',
                            'values' => $this->mfunciones_generales->GetValorCatalogo($codigo_registro, 'form_actividades')
                        );
                    
                    $arrBody[] = $form_titulo;
                    //$arrBody[] = $form_subtitulo1;
                    $arrBody[] = $form_prospecto_actividades;
                    $arrBody[] = $form_subtitulo5;
                    
                    $lst_resultado = array(
                        'codigo_registro' => $codigo_registro,
                        'form_id' => $form_id,
                        'form_detalle' => 'Actividades de Verificación',
                        'tipo_bandeja' => $tipo_bandeja,
                        'lista_elementos' => $arrBody
                    );
                    
                    /*** FORMULARIO 3: Actividades de Verificación  FIN ***/
                    
                    break;
                
                case 4:

                    /*** FORMULARIO 4: NUEVO Lead  INICIO ***/
                    
                    $contador_id = 400; // Cambiar por formulario - $form_id * 100

                    $form_titulo = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'center',
                        'label' => 'FORMULARIO NUEVO LEAD',
                    );
                    
                    $form_subtitulo1 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«INFO REFERENCIAL»',
                    );
                    
                    $form_subtitulo2 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«REGISTRO DE LA DATA»',
                    );
                    
                    $form_subtitulo3 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«UBICACIÓN»',
                    );
                    
                    $form_subtitulo4 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '«CONTACTO»',
                    );
                    
                    $form_subtitulo5 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => '☼ Una vez guardado el Lead, puede continuar su registro ingresando a la Campaña.',
                    );
                    
                    $form_subtitulo6 = array(
                        'form_id' => $form_id,
                        'ele_id' => $contador_id++,
                        'type' => 'header',
                        'align' => 'right',
                        'label' => ' ',
                    );
                    
                    $form_camp_id = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'select',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» SELECCIONES LA CAMPAÑA:',
                            'description' => 'Para el registro de un nuevo Lead, debe seleccionar una Campaña de la cual dependerá.',
                            'placeholder' => 'Seleccione la Campaña',
                            'name' => 'camp_id',
                            'values' => $this->mfunciones_generales->GetValorCatalogo($codigo_registro, 'form_campanas')
                        );
                    
                    $form_prospecto_idc = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'text',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» IDC:',
                            'description' => '',
                            'placeholder' => 'Registre el IDC del Cliente',
                            'name' => 'prospecto_idc',
                            'defaultValue' => '',
                            'subtype' => 'text',
                            'maxlength' => '15',
                            'className' => 'red form-control',
                        );
                    
                    $form_prospecto_nombre_cliente = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'text',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» NOMBRE CLIENTE:',
                            'description' => '',
                            'placeholder' => 'Registre el Nombre del Cliente',
                            'name' => 'prospecto_nombre_cliente',
                            'defaultValue' => '',
                            'subtype' => 'text',
                            'maxlength' => '150',
                            'className' => 'red form-control',
                        );
                    
                    $form_prospecto_empresa = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'text',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» EMPRESA:',
                            'description' => '',
                            'placeholder' => 'Registre el nombre de la Empresa',
                            'name' => 'prospecto_empresa',
                            'defaultValue' => '',
                            'subtype' => 'text',
                            'maxlength' => '150',
                            'className' => 'red form-control',
                        );
                    
                    $form_prospecto_ingreso = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'number',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» INGRESO (Bs.):',
                            'description' => '',
                            'placeholder' => 'Registre el Ingreso',
                            'name' => 'prospecto_ingreso',
                            'defaultValue' => '',
                            'min' => '1',
                            'max' => '9999999999',
                            'step' => '1'
                        );
                    
                    $form_prospecto_direccion_geo = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'map',
                            'name' => 'prospecto_direccion_geo',
                            'label' => '» DIRECCIÓN GEO:',
                            'defaultValue' => GEO_BCP,
                        );
                    
                    $form_prospecto_direccion = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» DIRECCIÓN LITERAL:',
                                'description' => '',
                                'placeholder' => 'Registre la Dirección Literal',
                                'name' => 'prospecto_direccion',
                                'defaultValue' => '',
                                'subtype' => 'text',
                                'maxlength' => '255',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_telefono = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'number',
                                'min' => '1',
                                'max' => '9999999999',
                                'step' => '1',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» TELÉFONO:',
                                'description' => '',
                                'placeholder' => 'Registre el Teléfono',
                                'name' => 'prospecto_telefono',
                                'defaultValue' => '',
                                'maxlength' => '10',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_celular = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'number',
                                'min' => '1',
                                'max' => '9999999999',
                                'step' => '1',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» CELULAR:',
                                'description' => '',
                                'placeholder' => 'Registre el Celular',
                                'name' => 'prospecto_celular',
                                'defaultValue' => '',
                                'maxlength' => '10',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_email = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'text',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» CORREO ELECTRÓNICO:',
                                'description' => '',
                                'placeholder' => 'Registre el Correo',
                                'name' => 'prospecto_email',
                                'defaultValue' => '',
                                'subtype' => 'email',
                                'maxlength' => '150',
                                'className' => 'red form-control',
                            );
                    
                    $form_prospecto_fecha_contacto1 = array(
                            'form_id' => $form_id,
                            'ele_id' => $contador_id++,
                            'type' => 'date',
                            'subtype' => 'date',
                            'readonly' => 'false',
                            'required' => true,
                            'label' => '» FECHA 1° CONTACTO:',
                            'description' => 'Puede registrar la fecha que realizó el primer contacto respecto al Lead.',
                            'placeholder' => 'Fecha 1° Contacto',
                            'name' => 'prospecto_fecha_contacto1',
                            'defaultValue' => '',
                        );
                    
                    $form_comentarios = array(
                                'form_id' => $form_id,
                                'ele_id' => $contador_id++,
                                'type' => 'textarea',
                                'subtype' => 'textarea',
                                'readonly' => 'false',
                                'required' => true,
                                'label' => '» COMENTARIOS:',
                                'description' => '',
                                'placeholder' => 'Registre los Comentarios...',
                                'name' => 'prospecto_comentario',
                                'defaultValue' => '',
                                'maxlength' => '1000',
                                'rows' => '3',
                                'className' => 'red form-control',
                            );
                    
                    $arrBody[] = $form_titulo;
                    
                    $arrBody[] = $form_subtitulo2;
                    $arrBody[] = $form_prospecto_idc;
                    $arrBody[] = $form_prospecto_nombre_cliente;
                    $arrBody[] = $form_prospecto_empresa;
                    $arrBody[] = $form_prospecto_ingreso;
                    $arrBody[] = $form_prospecto_telefono;
                    $arrBody[] = $form_prospecto_celular;
                    $arrBody[] = $form_prospecto_email;
                    
                    $arrBody[] = $form_comentarios;
                    
                    $arrBody[] = $form_subtitulo3;
                    $arrBody[] = $form_prospecto_direccion_geo;
                    $arrBody[] = $form_prospecto_direccion;
                    
                    $arrBody[] = $form_subtitulo1;
                    $arrBody[] = $form_camp_id;
                    
                    $arrBody[] = $form_subtitulo4;
                    $arrBody[] = $form_prospecto_fecha_contacto1;
                    $arrBody[] = $form_subtitulo5;
                    $arrBody[] = $form_subtitulo6;

                    $lst_resultado = array(
                        'codigo_registro' => $codigo_registro,
                        'form_id' => $form_id,
                        'form_detalle' => 'Registro Nuevo Lead',
                        'tipo_bandeja' => $tipo_bandeja,
                        'lista_elementos' => $arrBody
                    );
                    
                    /*** FORMULARIO 4: NUEVO Lead  FIN ***/

                    break;
                
                default:
                    break;
            }
            
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($lst_resultado);
            
        return $lst_resultado;
    }
    
    function GuardarRegistroForm($arrDatos, $usuario, $nombre_servicio, $codigo_ejecutivo){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "codigo_registro",
                    "tipo_bandeja",
                    "form_id",
                    "arrElementos"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_registro = $arrDatos['codigo_registro'];
            $form_id = $arrDatos['form_id'];
            
            $arrElementos = $arrDatos['arrElementos'];
            
            $accion_fecha = date('Y-m-d H:i:s');
            
            $arrError[0] =  array(
                    "error" => true,
                    "errorMessage" => $this->lang->line('CamposRequeridos'),
                    "errorCode" => 96,
                    "result" => array(
                        "mensaje" => $this->lang->line('IncompletoApp')
                    )
            );
            
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrElementos);
            
            switch ($form_id) {
                
                case 1:

                    /*** FORMULARIO 1: Información del Lead  INICIO ***/

                    // Se busca en el elemento
                
                    foreach ($arrElementos as $key => $value) 
                    {
                        if(isset($value["name"]) && $value["name"] == "prospecto_idc")
                        {
                            $prospecto_idc = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('idc', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'IDC - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_nombre_cliente")
                        {
                            $prospecto_nombre_cliente = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('nombre_cliente', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Nombre del Cliente - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_empresa")
                        {
                            $prospecto_empresa = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('empresa', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Empresa - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_ingreso")
                        {
                            $prospecto_ingreso = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('ingreso', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Ingreso - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_direccion_geo")
                        {
                            $prospecto_direccion_geo = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('direccion', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Dirección Geo - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_direccion")
                        {
                            $prospecto_direccion = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('direccion', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Dirección - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_telefono")
                        {
                            $prospecto_telefono = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('telefono', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Teléfono - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_celular")
                        {
                            $prospecto_celular = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('celular', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Celular - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_email")
                        {
                            $prospecto_email = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('correo', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Correo Electrónico - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_productos")
                        {   
                            if($this->mfunciones_generales->VerificaCampoLead('string_vacio', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Seleccione al menos 1 producto - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                            
                            $arrProductos = explode(",", $value["value"]);
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_comentario")
                        {
                            $prospecto_comentario = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_fecha_contacto1")
                        {
                            $prospecto_fecha_contacto1 = $this->mfunciones_generales->getFormatoFechaDate($value["value"]);
                        }
                    }
                    
                    // Si esta todo Ok, se procede con el registro en la DB
                        
                    // PASO 1: Insertar los servicios/productos seleccionados del lead
                    // (se elimina todos los servicios/productos del lead para volver a insertarlos)
                    $this->mfunciones_logica->EliminarServiciosProspecto($codigo_registro);

                    if (isset($arrProductos)) 
                    {
                        foreach ($arrProductos as $key => $value) 
                        {
                            $this->mfunciones_logica->InsertarServiciosProspecto($codigo_registro, $value, $usuario, $accion_fecha);
                        }
                    }

                    $this->mfunciones_logica->UpdateLead_APP($prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_fecha_contacto1, $prospecto_comentario, $usuario, $accion_fecha, $codigo_registro);
                    
                    /*** FORMULARIO 1: Información del Lead  FIN ***/
                    
                    break;
                    
                case 2:
                    
                    /*** FORMULARIO 2: Estado del Lead  INICIO ***/
                    
                    foreach ($arrElementos as $key => $value) 
                    {
                        if(isset($value["name"]) && $value["name"] == "prospecto_etapa")
                        {
                            $prospecto_etapa = $value["value"];
                            
                            if($this->mfunciones_generales->VerificaCampoLead('string_vacio', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Seleecione el Estado - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                            
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_monto_aprobacion")
                        {
                            $prospecto_monto_aprobacion = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_monto_desembolso")
                        {
                            $prospecto_monto_desembolso = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_fecha_desembolso")
                        {
                            $prospecto_fecha_desembolso = $this->mfunciones_generales->getFormatoFechaDate($value["value"]);
                        }
                    }
                    
                    // Validaciones Etapa
                    
                    // Regla de Negocio: Al seleccionar "Rechazo" (7) y guardar, ya no podría volver a seleccionar "Desembolso" (8) y viceversa
                    
                        // Paso 1: Obtener los datos del Prospecto

                        $arrResultado1 = $this->mfunciones_logica->ListadoDetalleProspecto($codigo_registro);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

                        if (!isset($arrResultado1[0])) 
                        {
                            $arrResultado = array();
                            return $arrResultado;
                        }

                        // Paso 2: Se verifica el estado nuevo Vs el estado actual
                        
                        if($arrResultado1[0]['prospecto_etapa'] == 7 && $prospecto_etapa == 8)
                        {
                            $arrError[0]['errorMessage'] = 'El estado actual es ' . $arrResultado1[0]['etapa_nombre'] . ', no puede seleccionar éste estado.';

                            $this->response($arrError, 200); exit();
                        }
                        
                        if($arrResultado1[0]['prospecto_etapa'] == 8 && $prospecto_etapa == 7)
                        {
                            $arrError[0]['errorMessage'] = 'El estado actual es ' . $arrResultado1[0]['etapa_nombre'] . ', no puede seleccionar éste estado.';

                            $this->response($arrError, 200); exit();
                        }
                    
                    // Si es Aprobación ID = 6 se requiere el monto de aprobación
                    
                    if($prospecto_etapa == 6)
                    {
                        if($this->mfunciones_generales->VerificaCampoLead('ingreso', $prospecto_monto_aprobacion) != '')
                        {
                            $arrError[0]['errorMessage'] = 'Por favor debe registrar el Monto de Aprobación.';

                            $this->response($arrError, 200); exit();
                        }
                    }
                    elseif($prospecto_etapa <= 6)
                    {
                        $prospecto_monto_aprobacion = 0;
                    }
                    
                    // Si es Desembolso ID = 8 se requiere el monto y la fecha de desembolso
                    
                    if($prospecto_etapa == 8)
                    {
                        if($this->mfunciones_generales->VerificaCampoLead('ingreso', $prospecto_monto_desembolso) != '')
                        {
                            $arrError[0]['errorMessage'] = 'Por favor debe registrar el Monto de Desembolso.';

                            $this->response($arrError, 200); exit();
                        }
                    }
                    elseif($prospecto_etapa <= 8)
                    {
                        $prospecto_monto_desembolso = 0;
                        $prospecto_fecha_desembolso = '0000-00-00';
                    }
                    
                    // Si esta todo Ok, se procede con el registro en la DB
                    
                    $this->mfunciones_logica->UpdateEstadoLead_APP($prospecto_etapa, $prospecto_monto_aprobacion, $prospecto_monto_desembolso, $prospecto_fecha_desembolso, $usuario, $accion_fecha, $codigo_registro);
                    
                    // Se registra el seguimiento de la derivación y/o actualización de la etapa                 
                    $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_registro, $prospecto_etapa, $arrResultado1[0]['prospecto_etapa'], $usuario, $accion_fecha, 2);

                    /***  REGISTRAR SEGUIMIENTO ***/
                    $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_registro, $prospecto_etapa, 1, 'Actualiza Estado del Lead', $usuario, $accion_fecha);
                    
                    /*** FORMULARIO 2: Estado del Lead  FIN ***/
                    
                    break;
                    
                    
                case 3:    
                    
                    /*** FORMULARIO 3: Actividades de Verificación  INICIO ***/
                    
                    foreach ($arrElementos as $key => $value) 
                    {
                        if(isset($value["name"]) && $value["name"] == "prospecto_actividades")
                        {   
                            if($this->mfunciones_generales->VerificaCampoLead('idc', $value["value"]) != '')
                            {
                                $arrError[0]['errorMessage'] = 'Seleccione al menos 1 actividad - ' . $this->lang->line('CamposRequeridos');
                                
                                $this->response($arrError, 200); exit();
                            }
                            
                            $arrActividades = explode(",", $value["value"]);
                        }
                    }
                    
                    // Si esta todo Ok, se procede con el registro en la DB
                        
                    // PASO 1: Insertar las actividades seleccionadas del lead
                    // (se elimina todas las actividades del lead para volver a insertarlas)
                    $this->mfunciones_logica->EliminarActividadesProspecto($codigo_registro);

                    if (isset($arrActividades)) 
                    {
                        foreach ($arrActividades as $key => $value) 
                        {
                            $this->mfunciones_logica->InsertarActividadesProspecto($codigo_registro, $value, $usuario, $accion_fecha);
                        }
                    }
                    
                    /*** FORMULARIO 3: Actividades de Verificación  FIN ***/
                    
                    break;
                
                case 4:

                    /*** FORMULARIO 1: NUEVO Lead  INICIO ***/

                    // Se busca en el elemento
                
                    foreach ($arrElementos as $key => $value) 
                    {
                        
                        if(isset($value["name"]) && $value["name"] == "camp_id")
                        {
                            $camp_id = $value["value"];
                        }
                    
                        if(isset($value["name"]) && $value["name"] == "prospecto_idc")
                        {
                            $prospecto_idc = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_nombre_cliente")
                        {
                            $prospecto_nombre_cliente = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_empresa")
                        {
                            $prospecto_empresa = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_ingreso")
                        {
                            $prospecto_ingreso = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_direccion_geo")
                        {
                            $prospecto_direccion_geo = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_direccion")
                        {
                            $prospecto_direccion = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_telefono")
                        {
                            $prospecto_telefono = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_celular")
                        {
                            $prospecto_celular = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_email")
                        {
                            $prospecto_email = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_comentario")
                        {
                            $prospecto_comentario = $value["value"];
                        }
                        
                        if(isset($value["name"]) && $value["name"] == "prospecto_fecha_contacto1")
                        {
                            $prospecto_fecha_contacto1 = $this->mfunciones_generales->getFormatoFechaDate($value["value"]);
                        }
                    }
                    
                    // INICIO validaciones
                    
                    if($this->mfunciones_generales->VerificaCampoLead('string_vacio', $camp_id) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Debe seleccionar una Campaña.';

                        $this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('idc', $prospecto_idc) != '')
                    {
                        $arrError[0]['errorMessage'] = 'IDC - ' . $this->lang->line('CamposRequeridos');

                        $this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('nombre_cliente', $prospecto_nombre_cliente) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Nombre del Cliente - ' . $this->lang->line('CamposRequeridos');

                        $this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('empresa', $prospecto_empresa) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Empresa - ' . $this->lang->line('CamposRequeridos');

                        //$this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('ingreso', $prospecto_ingreso) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Ingreso - ' . $this->lang->line('CamposRequeridos');

                        //$this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('direccion', $prospecto_direccion_geo) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Dirección Geo - ' . $this->lang->line('CamposRequeridos');

                        $this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('direccion', $prospecto_direccion) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Dirección - ' . $this->lang->line('CamposRequeridos');

                        $this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('telefono', $prospecto_telefono) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Teléfono - ' . $this->lang->line('CamposRequeridos');

                        $this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('celular', $prospecto_celular) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Celular - ' . $this->lang->line('CamposRequeridos');

                        $this->response($arrError, 200); exit();
                    }

                    if($this->mfunciones_generales->VerificaCampoLead('correo', $prospecto_email) != '')
                    {
                        $arrError[0]['errorMessage'] = 'Correo Electrónico - ' . $this->lang->line('CamposRequeridos');

                        //$this->response($arrError, 200); exit();
                    }
                    
                    // FIN validaciones
                    
                    // Si esta todo Ok, se procede con el registro en la DB

                    // Paso Previo: Se obtiene los datos del ejecutivo de acuerdo a su usuario_user
                    
                    $prospecto_matricula = $usuario;
                    
                    $ejecutivo_id = $codigo_ejecutivo;
                    
                    // PASO 1: Se registra un nuevo Lead
                    
                    $arrResultado2 = $this->mfunciones_logica->InsertarLead_APP($ejecutivo_id, 1, -1, $camp_id, $accion_fecha, $prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, 2, $prospecto_matricula, $prospecto_comentario, $usuario, $accion_fecha);
                    
                    // PASO 2: Se captura el ID del registro recíen insertado en la tabla "prospecto"
                    $codigo_prospecto = $arrResultado2;

                        // PASO 3: Se crea la carpeta del prospecto

                        $path = RUTA_PROSPECTOS . 'afn_' . $codigo_prospecto;

                        if(!is_dir($path)) // Verifica si la carpeta no existe para poder crearla
                        {
                            mkdir($path, 0755, TRUE);
                        }

                            // Se crea el archivo html para evitar ataques de directorio
                            $cuerpo_html = '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                            write_file($path . '/index.html', $cuerpo_html);

                        // PASO 4: Se registra la fecha en el calendario del Ejecutivo de Cuentas

                        $cal_visita_ini = date('Y-m-d 08:00:00');
                        $cal_visita_fin = date('Y-m-d 15:00:00');

                        $this->mfunciones_logica->InsertarFechaCaendario($ejecutivo_id, $codigo_prospecto, 1, $cal_visita_ini, $cal_visita_fin, $usuario, $accion_fecha);

                        // PASO 5: Insertar los productos de la campaña
                        
                        // Listado de Servicios
                        $arrServicios = $this->mfunciones_logica->ObtenerServiciosCampana($camp_id);
                        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrServicios);
                        
                        if (isset($arrServicios[0])) 
                        {
                            foreach ($arrServicios as $key => $value2) 
                            {
                                $this->mfunciones_logica->InsertarServiciosProspecto($codigo_prospecto, $value2["servicio_id"], $usuario, $accion_fecha);
                            }
                        }

                        // PASO 6: Se registra el estado e Hito

                        $etapa_actual = 0;
                        $etapa_nueva = 1; // Lead Asignado

                        /**** REGISTRAR SEGUIMIENTO, ETAPA E HITO ****/        
                        $this->mfunciones_generales->SeguimientoHitoProspecto($codigo_prospecto, $etapa_nueva, $etapa_actual, $usuario, $accion_fecha, 0);
                        /***  REGISTRAR SEGUIMIENTO ***/
                        $this->mfunciones_logica->InsertSeguimientoProspecto($codigo_prospecto, $etapa_nueva, 0, 'Auto-Asignación Lead al Agente', $usuario, $accion_fecha);
                    
                        $codigo_registro = $codigo_prospecto;
                        
                    /*** FORMULARIO 1: NUEVO Lead  FIN ***/
                    
                    break;
                    
                default:
                    break;
            }
            
            $lst_resultado[0] = array(
                "codigo_registro" => $codigo_registro,
                "mensaje" => "El registro se guardó correctamente. Puede gestionar el Lead ingresando a la Campaña."
            );
            
            // Se guarda el Log para la auditoría
            $this->mfunciones_logica->InsertarAuditoriaMovil($nombre_servicio, json_encode($arrDatos), '0,0', $usuario, $accion_fecha);
            
            return $lst_resultado;
    }
    
    /*************** FORMULARIOS DINÁMICOS - FIN ****************************/
    
	function ListadoCatalogo($arrDatos){

            // Se verifica si cuenta con los parámetros necesarios y correctos
            $parametros = array(
                    "catalogo", "parent_codigo", "parent_tipo"
                    );
            // Si no son los parámetros que se requiere, se devuelve vacio
            if(!($this->array_keys_exist($arrDatos, $parametros)))
            {
                    $arrResultado = array();
                    return $arrResultado;
            }

            // Si Todo bien... se captura los datos y se procesa la información

            $codigo_catalogo = $arrDatos['catalogo'];
            $parent_codigo = $arrDatos['parent_codigo'];
            $parent_tipo = $arrDatos['parent_tipo'];
	
		// Listado de tablas del catálogo	
        $arrResultado = $this->mfunciones_logica->ObtenerCatalogo($codigo_catalogo, $parent_codigo, $parent_tipo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		
        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "catalogo_tipo_codigo" => $value["catalogo_tipo_codigo"],
                    "catalogo_codigo" => $value["catalogo_codigo"],
                    "catalogo_descripcion" => $value["catalogo_descripcion"]
                );
                $lst_resultado[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado[0] = $arrResultado;
        }

            return $lst_resultado;
    }
    
}