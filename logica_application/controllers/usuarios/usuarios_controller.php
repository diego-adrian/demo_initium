<?php
/**
 * @file 
 * Codigo que implementa el controlador para la autenticacion de usuario en el sistema
 * @brief  CONTROLADOR DE LOGUEO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Controlador para la administracion de la autenticacion de usuarios 
 * @brief CONTROLADOR DEL LOGUEO
 * @class Login_controller 
 */
class Usuarios_controller extends MY_Controller {

	// Se establece el codigo del MENU, en base a eso se verá si el usuario tiene el permiso necesario, para ver el código revise la tabla "menu"
	protected $codigo_menu_acceso = 1;

    function __construct() {
        parent::__construct();
    }
    /**
     * carga la vista para el formulario de login
     * @brief CARGAR LOGIN
     * @author JRAD
     * @date Mar 21, 2014     
     */
    
    public function CargarMenuPrincipal() {
		
        $this->lang->load('general', 'castellano');        
        $this->load->model('mfunciones_logica');
                
        $this->load->view('inicio/view_pantalla_inicial');
    }
    
    public function ListaUsuarios() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerLista_Usuarios();
        
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0])) 
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "usuario_codigo" => $value["usuario_id"],
                    "usuario_user" => $value["usuario_user"],
                    "usuario_nombre_completo" => $value["usuario_nombres"] . " " . $value["usuario_app"] . " " . $value["usuario_apm"],                    
                    "usuario_email" => $value["usuario_email"],
                    "usuario_telefono" => $value["usuario_telefono"],
                    "usuario_direccion" => $value["usuario_direccion"],
                    "usuario_rol" => $this->mfunciones_generales->getRolUsuario($value["usuario_rol"]),
                    "usuario_fecha_creacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y($value["usuario_fecha_creacion"]),
                    "usuario_activo" => $this->mfunciones_generales->GetValorCatalogo($value["usuario_activo"], 'activo'),
                    "estructura_agencia_nombre" => $value["estructura_agencia_nombre"]
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
        
        $this->load->view('usuarios/view_listado_usuarios', $data);        
    }
    
    public function UsuarioForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $usuario_codigo = $_POST['usuario_codigo'];
            
            $arrResultado = $this->mfunciones_logica->ObtenerDatosUsuario($usuario_codigo);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $dato_utlimo_ingreso = 'Aún no Ingresó al Sistema';
                    
                    if($value["usuario_fecha_ultimo_acceso"] != '1500-01-01 00:00:00')
                    {
                        $dato_utlimo_ingreso = $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["usuario_fecha_ultimo_acceso"]);
                    }
                    
                    $item_valor = array(
                        "usuario_codigo" => $value["usuario_id"],
                        "usuario_user" => $value["usuario_user"],
                        "estructura_agencia_id" => $value["estructura_agencia_id"],
                        "usuario_fecha_creacion" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["usuario_fecha_creacion"]),
                        "usuario_fecha_ultimo_acceso" => $dato_utlimo_ingreso,
                        "usuario_fecha_ultimo_password" => $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($value["usuario_fecha_ultimo_password"]),
                        "usuario_nombres" => $value["usuario_nombres"],
                        "usuario_app" => $value["usuario_app"],
                        "usuario_apm" => $value["usuario_apm"],
                        "usuario_email" => $value["usuario_email"],
                        "usuario_telefono" => $value["usuario_telefono"],
                        "usuario_direccion" => $value["usuario_direccion"],
                        "usuario_rol" => $value["usuario_rol"],
                        "usuario_rol_detalle" => $this->mfunciones_generales->getRolUsuario($value["usuario_rol"]),
                        "usuario_activo" => $value["usuario_activo"],
                        "usuario_activo_detalle" => $this->mfunciones_generales->GetValorCatalogo($value["usuario_activo"], 'activo'),                        
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
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
            
            $usuario_codigo = 0;
        }
        
        // Listado de las Agencias
        $arrAgencia = $this->mfunciones_logica->ObtenerDatosAgencia(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrAgencia);
        $data["arrAgencia"] = $arrAgencia;
        
        // Listado de los roles
        $estado_rol = 0; // Inversa 0=Activos 1=Inactivos
        $arrRoles = $this->mfunciones_logica->ObtenerDatosRoles($estado_rol);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRoles);                
        $data["arrRoles"] = $arrRoles;
        
        // Listado de los perfiles
        $estado_perfil = 0; // Inversa 0=Activos 1=Inactivos
        $arrPerfiles = $this->mfunciones_logica->ObtenerDatosPerfiles($estado_perfil);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRoles);                
                
            // Lista los perfiles disponibles
        
            if (isset($arrPerfiles[0])) 
            {
                $i = 0;

                foreach ($arrPerfiles as $key => $value) 
                {
                    $item_valor = array(
                        "perfil_id" => $value["perfil_id"],
                        "perfil_nombre" => $value["perfil_nombre"],
                        "perfil_descripcion" => $value["perfil_descripcion"],
                        "perfil_asignado" => $this->mfunciones_generales->GetPerfilUsuario($usuario_codigo, $value["perfil_id"])
                    );
                    $lst_resultado2[$i] = $item_valor;

                    $i++;
                }
            } 
            else 
            {
                $lst_resultado2[0] = $arrPerfiles;
            }
            
            $data["arrPerfiles"] = $lst_resultado2;
        
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        if($tipo_accion == 3)
        {
            $this->load->view('usuarios/view_usuario_detalle', $data);
        }
        else
        {
            $this->load->view('usuarios/view_usuario_form', $data);
        }        
    }
    
    public function UsuarioForm_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $usuario_nombres = $this->input->post('usuario_nombres', TRUE);
        $usuario_app = $this->input->post('usuario_app', TRUE);
        $usuario_apm = $this->input->post('usuario_apm', TRUE);
        $usuario_email = $this->input->post('usuario_email', TRUE);
        $usuario_telefono = $this->input->post('usuario_telefono', TRUE);
        $usuario_direccion = $this->input->post('usuario_direccion', TRUE);
        $usuario_parent = $this->input->post('usuario_parent', TRUE);
        
        $usuario_rol = $this->input->post('usuario_rol', TRUE);        
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        $arrPerfil = $this->input->post('perfil_list', TRUE);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrPerfil);
        
        // Validaciones
                
        $separador = '<br /> - ';
        $error_texto = '';
        
        if($usuario_nombres == '')
        {
            $error_texto .= $separador . $this->lang->line('Usuario_nombre');
        }
        
        if($usuario_app == '')
        {
            $error_texto .= $separador . $this->lang->line('Usuario_app');
        }
        
        if($usuario_apm == '')
        {
            $error_texto .= $separador . $this->lang->line('Usuario_apm');
        }
        
        if($this->mfunciones_generales->VerificaCorreo($usuario_email) == false)
        {
            $error_texto .= $separador . $this->lang->line('Usuario_email');
        }
        
        if(!filter_var($usuario_telefono, FILTER_VALIDATE_FLOAT) !== false)
        {
            $error_texto .= $separador . $this->lang->line('Usuario_telefono');
        }
        
        if($usuario_direccion == '')
        {
            $error_texto .= $separador . $this->lang->line('Usuario_direccion');
        }
        
        if($usuario_rol == '-1')
        {
            $error_texto .= $separador . $this->lang->line('Usuario_rol');
        }
        
        if($usuario_parent == '-1')
        {
            $error_texto .= $separador . $this->lang->line('estructura_parent') . $this->lang->line('estructura_agencia');
        }
        
        if($error_texto != '')
        {
            js_error_div_javascript($this->lang->line('CamposObligatorios') . $error_texto);
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $usuario_codigo = $this->input->post('usuario_codigo', TRUE);
            
            $usuario_activo = $this->input->post('usuario_activo', TRUE);
            
            $this->mfunciones_logica->UpdateUsuario($usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $fecha_actual, $nombre_usuario, $usuario_parent, $usuario_rol, $usuario_activo, $usuario_codigo);
         
        }
        
        if($tipo_accion == 0)
        {            
            // INSERT
            
            $usuario_user = $this->input->post('usuario_user', TRUE);
            
            if(filter_var($usuario_user, FILTER_VALIDATE_FLOAT) !== false)
            {
                js_error_div_javascript($this->lang->line('UsuarioError'));
                exit();
            }
            
            if(strlen($usuario_user) < 4)
            {
                js_error_div_javascript($this->lang->line('UsuarioError_corto'));
                exit();
            }
            
            
            
            $usuario_activo = 1;
            
            $arrayUsuariosReservados = 
                    array(
                        "ADMIN", 
                        "ADMINISTRADOR", 
                        "ADMINISTRATOR", 
                        "SA", 
                        "ROOT"
                        );
        
            if (in_array(strtoupper($usuario_user), $arrayUsuariosReservados)) 
            {
                js_error_div_javascript($this->lang->line('PasswordNoAceptado'));
                exit();
            }
            
                // Obtener el passoword por defecto        
                $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                $password_defecto = $arrResultado[0]['conf_defecto'];
                $usuario_pass = sha1(sha1($password_defecto)); // Es por defecto
            
			// PRIMERO SE VERIFICA SI EL NOMBRE DE USUARIO NO ESTA EN USO
                
            $arrResultado1 = $this->mfunciones_logica->VerificaUsuario($usuario_user);

            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado1);
            
            if (isset($arrResultado1[0]))
            {
                js_error_div_javascript($this->lang->line('UsuarioIncorrecto'));
                exit();
            }            
			
            $usuario_codigo = $this->mfunciones_logica->InsertarUsuario($usuario_user, $usuario_pass, $fecha_actual, $usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $fecha_actual, $nombre_usuario, $usuario_parent, $usuario_rol, $usuario_activo);
                        
        }
        
        $this->ListaUsuarios();        
    }
    
    public function CambiarPass_Ver() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        $data["arrCajasHTML"] = $this->formulario_logica_general->ConstruccionCajasFormulario(array());
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
		
        $this->load->view('usuarios/view_usuario_pass', $data);
        
    }
    
    public function RestablecerPassPregunta() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $usuario_codigo = $this->input->post('usuario_codigo', TRUE);
        
		$data["usuario_codigo"] = $usuario_codigo;
		
        $this->load->view('usuarios/view_usuario_pass_pregunta', $data);        
    }
	
    public function RestablecerPass() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $usuario_codigo = $this->input->post('usuario_codigo', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
            // Obtener el passoword por defecto        
            $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            $password_defecto = $arrResultado[0]['conf_defecto'];
        
        $usuario_pass = sha1(sha1($password_defecto)); // Es por defecto
        
        $this->mfunciones_logica->RestablecerPass($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo);
        
        // AUDITORIA
        $auditoria_detalle = "Se modificó el password del usuario con código $usuario_codigo";
        $auditoria_tabla = "usuarios";
        $this->mfunciones_generales->Auditoria($auditoria_detalle, $auditoria_tabla);
        
	$this->ListaUsuarios();
        
    }
    
    public function Agencia_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosAgencia(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "estructura_id" => $value["estructura_agencia_id"],
                    "parent_id" => $value["parent_id"],
                    "parent_detalle" => $value["parent_detalle"],                    
                    "estructura_nombre" => $value["estructura_agencia_nombre"]
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
        
        $this->load->view('usuarios/view_agencia_ver', $data);
        
    }
    
    public function AgenciaForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $estructura_codigo = $_POST['codigo'];
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerDatosAgencia($estructura_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "estructura_id" => $value["estructura_agencia_id"],
                        "parent_id" => $value["parent_id"],
                        "parent_detalle" => $value["parent_detalle"],                    
                        "estructura_nombre" => $value["estructura_agencia_nombre"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
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
            
            $estructura_codigo = 0;
        }
        
        // Listado de las Regionales
        $arrParent = $this->mfunciones_logica->ObtenerDatosRegional(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);                
        $data["arrParent"] = $arrParent;
        
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('usuarios/view_agencia_form', $data);
        
    }
    
    public function Agencia_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_nombre = $this->input->post('estructura_nombre', TRUE);
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($estructura_nombre == "" || $catalogo_parent == -1)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            if((int)$estructura_id == 0)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            $this->mfunciones_logica->UpdateAgencia($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id);
         
        }
        
        if($tipo_accion == 0)
        {            
            // INSERT
            
            $this->mfunciones_logica->InsertAgencia($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual);
                        
        }
        
        $this->Agencia_Ver();        
    }
    
    public function Regional_Ver() {
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        $arrResultado = $this->mfunciones_logica->ObtenerDatosRegional(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
        
        if (isset($arrResultado[0]))
        {
            $i = 0;
            
            foreach ($arrResultado as $key => $value) {
                $item_valor = array(
                    "estructura_id" => $value["estructura_regional_id"],
                    "parent_id" => $value["parent_id"],
                    "parent_detalle" => $value["parent_detalle"],                    
                    "estructura_nombre" => $value["estructura_regional_nombre"]
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
        
        $this->load->view('usuarios/view_regional_ver', $data);
        
    }
    
    public function RegionalForm() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        $this->load->library('FormularioValidaciones/logica_general/Formulario_logica_general');
        
        $this->formulario_logica_general->DefinicionValidacionFormulario();
        
        // 0=Insert    1=Update
        
        $tipo_accion = 0;
        
        if(isset($_POST['tipo_accion']))
        {
            $tipo_accion = $_POST['tipo_accion'];
            
            // UPDATE
            
            $estructura_codigo = $_POST['codigo'];
            
            $arrResultado = $arrResultado = $this->mfunciones_logica->ObtenerDatosRegional($estructura_codigo);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
                        
            if (isset($arrResultado[0])) 
            {
                $i = 0;

                foreach ($arrResultado as $key => $value) 
                {
                    $item_valor = array(
                        "estructura_id" => $value["estructura_regional_id"],
                        "parent_id" => $value["parent_id"],
                        "parent_detalle" => $value["parent_detalle"],                    
                        "estructura_nombre" => $value["estructura_regional_nombre"]
                    );
                    $lst_resultado[$i] = $item_valor;

                    $i++;
                }
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
            
            $estructura_codigo = 0;
        }
        
        // Listado de las Regionales
        $arrParent = $this->mfunciones_logica->ObtenerDatosEntidad(-1);
        $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrParent);                
        $data["arrParent"] = $arrParent;
        
        
        $data["tipo_accion"] = $tipo_accion;
        
        $data["strValidacionJqValidate"] = $this->formulario_logica_general->GeneraValidacionJavaScript();
        
        $this->load->view('usuarios/view_regional_form', $data);
        
    }
    
    public function Regional_Guardar() {
        
        $this->lang->load('general', 'castellano');
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
        // Se capturan los datos
        
        $estructura_nombre = $this->input->post('estructura_nombre', TRUE);
        $catalogo_parent = $this->input->post('catalogo_parent', TRUE);
        
        $nombre_usuario = $_SESSION["session_informacion"]["login"];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if($estructura_nombre == "" || $catalogo_parent == -1)
        {
            js_error_div_javascript($this->lang->line('FormularioIncompleto'));
            exit();
        }
        
        // 0=Insert    1=Update
        
        $tipo_accion = $this->input->post('tipo_accion', TRUE);
        
        if($tipo_accion == 1)
        {
            // UPDATE
            $estructura_id = $this->input->post('estructura_id', TRUE);
            
            if((int)$estructura_id == 0)
            {
                js_error_div_javascript($this->lang->line('FormularioIncompleto'));
                exit();
            }
            
            $this->mfunciones_logica->UpdateRegional($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id);
         
        }
        
        if($tipo_accion == 0)
        {            
            // INSERT
            
            $this->mfunciones_logica->InsertRegional($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual);
        }
        
        $this->Regional_Ver();
    }    
}
?>