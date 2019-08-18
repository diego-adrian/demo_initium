<?php
/**
 * @file 
 * Codigo que implementa el controlador para ga gestión del catálogo del sistema
 * @brief  CATÁLOGO DEL SISTEMA
 * @author JRAD
 * @date 2017
 * @copyright 2017 JRAD
 */
/**
 * Controlador para ga gestión del catálogo del sistema
 * @brief CATÁLOGO DEL SISTEMA
 * @class Conf_catalogo_controller 
 */
class Conf_catalogo_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 6;

    function __construct() {
        parent::__construct();
    }
    
    /**
     * carga la vista para el formulario de configuración
     * @brief CARGAR CONFIGURACIÓN
     * @author JRAD
     * @date 2017
     */
	 
    public function Catalogo_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
		// Listado de tablas del catálogo	
        $arrResultado = $this->mfunciones_logica->ObtenerCatalogo('-1', '-1', '-1');
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
		
        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) 
            {
                $item_valor = array(
                    "catalogo_id" => $value["catalogo_id"],
                    "catalogo_parent_codigo" => $value["catalogo_parent"],
                    "catalogo_parent_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["catalogo_parent"], 'parent'),
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

		$data["arrRespuesta"] = $lst_resultado;
		
        $this->load->view('configuracion/view_catalogo_ver', $data);
    }
	 
    public function CatalogoForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // Cargar el catálogo para establecer registros hijos
        $arrResultado1 = $this->mfunciones_logica->ObtenerCatalogo(-1, -1, -1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);

        if (isset($arrResultado1[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado1 as $key => $value) 
            {
                $item_valor = array(
                    "lista_codigo" => $value["catalogo_id"],
                    "lista_valor" => $value["catalogo_tipo_codigo"] . ' | ' . $value["catalogo_descripcion"],
                );
                $lst_resultado1[$i] = $item_valor;

                $i++;
            }
        } 
        else 
        {
            $lst_resultado1[0] = $arrResultado1;
        }        
        
        $data["arrListadoDepende"] = $lst_resultado1;
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $catalogo_codigo = $this->input->post('catalogo_codigo', TRUE);
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosCatalogo($catalogo_codigo);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "catalogo_id" => $value["catalogo_id"],
                        "catalogo_parent" => $value["catalogo_parent"],
                        "catalogo_tipo_codigo" => $value["catalogo_tipo_codigo"],
                        "catalogo_codigo" => $value["catalogo_codigo"],
                        "catalogo_descripcion" => $value["catalogo_descripcion"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
                
                $catalogo_parent = $value["catalogo_parent"];
                
                $data["catalogo_parent"] = $catalogo_parent;
            } 
            else 
            {
                $lst_resultado[0] = $arrResultado;
            }
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario($lst_resultado[0]);
            
            $data["arrRespuesta"] = $lst_resultado;
        }
        else
        {
            $tipo_accion = 0;
            
            // INSERT
            
            $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
            
        }
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('configuracion/view_catalogo_form', $data);        
    }
	
    public function CatalogoForm_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $catalogo_tipo_codigo = $this->input->post('catalogo_tipo_codigo', TRUE);
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        $catalogo_codigo = $this->input->post('catalogo_codigo', TRUE);
        $catalogo_descripcion = $this->input->post('catalogo_descripcion', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($catalogo_tipo_codigo == -1 || $catalogo_codigo == "" || $catalogo_descripcion == "")
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        // Validar que el registro no exista y no exista duplicidad        
        $arrResultado5 = $this->mfunciones_logica->VeriricaDatosCatalogo($catalogo_tipo_codigo, $catalogo_codigo);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado5);
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $catalogo_id = $this->input->post('catalogo_id', TRUE);
            
            if (isset($arrResultado5[0])) 
            {
                // Sólo permite editar su propio registro
                if($arrResultado5[0]['catalogo_id'] != $catalogo_id)
                {
                    js_error_div_javascript($this->lang->line('FormularioRegistroExiste'));
                    exit();
                }
            }
            
            $this->mfunciones_logica->UpdateCatalogo($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $nombre_usuario, $fecha_actual, $catalogo_id);
      
        }
        
        if($tipo_accion == 0)
        {            
            // INSERT

            if (isset($arrResultado5[0])) 
            {
                js_error_div_javascript($this->lang->line('FormularioRegistroExiste'));
                exit();
            }
            
            $this->mfunciones_logica->InsertarCatalogo($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $nombre_usuario, $fecha_actual);
            
        }
        
        $this->Catalogo_Ver();        
    }
}
?>