<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    /**
     Lógica para el control de Autenticación
     */

    protected $access = "*";

    public function __construct()
    {
            parent::__construct();
            session_start();
            $this->VerificaAutenticacion();
    }

    public function VerificaAutenticacion() 
    {	
        $this->lang->load('general', 'castellano');
        
        // Se pregunta si el usuario esta atutenticado y pertenece al rol solicitado 
        if (! $this->permission_check())
        {
            echo "<div style='padding-left: 5%; padding-right: 5%; font-family: arial; border: 1px solid;' align='center'><div class='mensajeBD'>" . $this->lang->line('NoAutorizado') . "</div></div>";

            // Se procede a guardar el log del acceso no autorizado			
            $this->load->model('mfunciones_generales');
            $this->mfunciones_generales->AuditoriaAcceso('Forbidden');

            exit();
        }
        
        // Si el tiempo de inactividad supera al definido, se cierra la sesión
        if($this->auto_logout("conf_tiempo_bloqueo"))
        {
            session_unset();
            session_destroy();
            js_invocacion_javascript("window.location.reload();");
            
            exit();
        }        
    }

    public function permission_check()
    {
        $this->load->model('mfunciones_logica');
        $this->load->model('mfunciones_generales');
        
        if(isset($_SESSION['session_informacion']['codigo']))
        {
            $codigo_menu_acceso = $this->codigo_menu_acceso;
            
            // En el caso que el menú sea "0" quiere decir que es libre y cualquier usuario puede ingresar
            if($codigo_menu_acceso == 0)
            {
                return true;
            }
            
            // Se busca en la base de datos el listado de roles que pueden acceder al sistema
            $arrRoles= $this->mfunciones_logica->ObtenerRolesMenu($codigo_menu_acceso, $_SESSION["session_informacion"]["rol_codigo"]);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrRoles);
            
            foreach ($arrRoles as $key => $value) 
            {
                if($value["rol_id"] == $_SESSION["session_informacion"]["rol_codigo"])
                {
                    return true;
                }
            }
        }

        return false;
    }

    public function auto_logout($field)
    {
        $this->load->model('mfunciones_generales');
        $this->load->model('mfunciones_logica');
        
            // Obtener el passoword por defecto   
            $arrResultado = $this->mfunciones_logica->ObtenerDatosConf_Credenciales();        
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrResultado);
            $conf_tiempo_bloqueo = ($arrResultado[0]['conf_tiempo_bloqueo'] * 60);
        
        
        $t = time();
        $t0 = $_SESSION[$field];
        $diff = $t - $t0;
        if ($diff > $conf_tiempo_bloqueo || !isset($t0))
        {
            return true;
        }
        else
        {
            $_SESSION[$field] = time();
        }
    }

}