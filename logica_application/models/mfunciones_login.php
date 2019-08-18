<?php
/**
 * @file 
 * Codigo que implementa el MODELO para la autenticacion de usuario en el sistema
 * @brief  MODELO DE LOGUEO
 * @author JRAD
 * @date Mar 21, 2014
 * @copyright 2017 JRAD
 */
/**
 * Codigo que implementa el MODELO para la autenticacion de usuario en el sistema
 * @brief MODELO DEL LOGUEO
 * @class mfunciones_login 
 */
class mfunciones_login extends CI_Model {

    function ObtenerLista_AutenticacionPrincipal($usu_login, $usu_password) 
    {        
        try 
        {
            $sql = "SELECT CONCAT(usuario_nombres, ' ', usuario_app, ' ', usuario_apm) AS nombre_completo, usuario_rol, usuario_id, usuario_user, usuario_fecha_creacion, usuario_fecha_ultimo_acceso, usuario_fecha_ultimo_password, usuario_activo, usuario_password_reset FROM usuarios WHERE usuario_user = ? AND usuario_pass = ?"; 

            $consulta = $this->db->query($sql, array($usu_login, $usu_password));

            $listaResultados = $consulta->result_array();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
}
?>