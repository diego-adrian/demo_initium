<?php

class Mfunciones_logica extends CI_Model {

    // Verifiación de Roles para el Acceso
    function ObtenerRolesMenu($menu_codigo, $rol_codigo)
    {        
        try 
        {
            $sql = "SELECT rol_id FROM rol_menu WHERE menu_id=? AND rol_id=? "; 

            $consulta = $this->db->query($sql, array($menu_codigo, $rol_codigo));

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
    
    function ObtenerMenuPorRol($rol_codigo)
    {        
        try 
        {
            $sql = "SELECT m.menu_nombre, m.menu_enlace AS 'menu_ruta' FROM rol_menu rm INNER JOIN rol r ON r.rol_id=rm.rol_id INNER JOIN menu m ON m.menu_id=rm.menu_id WHERE r.rol_id=? ORDER BY m.menu_orden, m.menu_nombre "; 

            $consulta = $this->db->query($sql, array($rol_codigo));

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
    
    // Auditoria

    function InsertarAuditoriaAcceso($auditoria_usuario, $auditoria_accion, $auditoria_fecha, $auditoria_ip) 
    {
        try 
        {
            $sql = "INSERT INTO auditoria_acceso (auditoria_usuario, auditoria_tipo_acceso, auditoria_fecha, auditoria_ip) VALUES (?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($auditoria_usuario, $auditoria_accion, $auditoria_fecha, $auditoria_ip));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function InsertarAuditoria($auditoria_usuario, $auditoria_fecha, $auditoria_tabla, $auditoria_accion, $auditoria_ip) 
    {        
        try 
        {
            $sql = "INSERT INTO auditoria (auditoria_usuario, auditoria_fecha, auditoria_tabla, auditoria_accion, auditoria_ip) VALUES (?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($auditoria_usuario, $auditoria_fecha, $auditoria_tabla, $auditoria_accion, $auditoria_ip));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UsuarioActualizarFechaLogin($fecha_login, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_fecha_ultimo_acceso = ? WHERE usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($fecha_login, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerAuditoriaTablas() 
    {        
        try
        {
            $sql = "SELECT table_name FROM auditoria GROUP BY table_name ORDER BY table_name ASC "; 

            $consulta = $this->db->query($sql);

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
    
    function ObtenerAuditoriaAcceso() 
    {        
        try
        {
            $sql = "SELECT auditoria_usuario FROM auditoria_acceso GROUP BY auditoria_usuario ORDER BY auditoria_usuario ASC "; 

            $consulta = $this->db->query($sql);

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
    
    function ObtenerAuditoriaTipoAcceso() 
    {        
        try
        {
            $sql = "SELECT auditoria_tipo_acceso FROM auditoria_acceso GROUP BY auditoria_tipo_acceso ORDER BY auditoria_tipo_acceso ASC "; 

            $consulta = $this->db->query($sql);

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
	
    function ObtenerAuditoriaUsuarios() 
    {        
        try 
        {
            $sql = "SELECT usuario_id, usuario_user, usuario_nombres, usuario_app, usuario_apm FROM usuarios ORDER BY usuario_app ASC "; 
            
            $consulta = $this->db->query($sql);

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
	
    function ReporteAuditoria($tabla, $usuario, $fecha_inicio, $fecha_fin, $filtro_fechas)
    {        
        try 
        {
            $criterio = " WHERE audit_id > 0";

            if($tabla != -1)
            {
                    $criterio .= " AND table_name='" . $tabla . "'";
            }

            if($usuario != -1)
            {
                    $criterio .= " AND accion_usuario='" . $usuario . "'";
            }

            if($filtro_fechas == 1)
            {
                    $criterio .= " AND accion_fecha BETWEEN '" . $fecha_inicio . " 00:00:01' AND '" . $fecha_fin . " 23:59:59'";
            }			
			
            $sql = "SELECT audit_id, accion_usuario, table_name, pk1, pk2, action, accion_fecha FROM auditoria " . $criterio; 

            $consulta = $this->db->query($sql, array($criterio));

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
	
    function ReporteAuditoriaDetalle($codigo)
    {        
        try 
        {
            $sql = "SELECT audit_meta_id, audit_id, col_name, old_value, new_value FROM auditoria_meta WHERE NOT(old_value <=> new_value) AND audit_id=? "; 

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ReporteAuditoriaAcceso($acceso, $accion, $fecha_inicio, $fecha_fin, $filtro_fechas)
    {        
        try 
        {
            $criterio = " WHERE auditoria_id > 0";

            if($acceso != -1)
            {
                $criterio .= " AND auditoria_usuario='" . $acceso . "'";
            }

            if($accion != -1)
            {
                $criterio .= " AND auditoria_tipo_acceso='" . $accion . "'";
            }

            if($filtro_fechas == 1)
            {
                $criterio .= " AND auditoria_fecha BETWEEN '" . $fecha_inicio . " 00:00:01' AND '" . $fecha_fin . " 23:59:59'";
            }			
			
            $sql = "SELECT auditoria_id, auditoria_usuario, auditoria_tipo_acceso, auditoria_fecha, auditoria_ip FROM auditoria_acceso " . $criterio; 

            $consulta = $this->db->query($sql, array($criterio));

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
    
    function InsertarAuditoriaMovil($auditoria_movil_servicio, $auditoria_movil_parametros, $auditoria_movil_geo, $accion_usuario, $accion_fecha)
    {
        try 
        {
            $sql = "INSERT INTO auditoria_movil(auditoria_movil_servicio, auditoria_movil_parametros, auditoria_movil_geo, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($auditoria_movil_servicio, $auditoria_movil_parametros, $auditoria_movil_geo, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // SERVICIOS REST APP
    
    function VerificaCredencialesAPP($usu_login, $usu_password) 
    {        
        try 
        {
            $sql = "SELECT CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) AS 'nombre_completo', ea.estructura_agencia_id as 'agencia_codigo', ea.estructura_agencia_nombre as 'agencia_detalle', u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_id, u.usuario_user, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_activo, e.ejecutivo_id 
                FROM usuarios u 
                INNER JOIN ejecutivo e ON u.usuario_id=e.usuario_id
                INNER JOIN estructura_agencia ea ON ea.estructura_agencia_id=u.estructura_agencia_id
                WHERE u.usuario_user = ? AND u.usuario_pass = ? AND u.usuario_rol = 2 LIMIT 1"; 

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
    
    function ObtenerBandejaProspectos($codigo_ejecutivo, $consolidado) 
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id, p.ejecutivo_id, p.empresa_id, e.empresa_categoria, p.prospecto_fecha_asignacion, p.prospecto_consolidado, p.prospecto_observado_app, p.prospecto_nombre_cliente AS 'empresa_nombre_legal', p.prospecto_empresa, p.prospecto_direccion AS 'empresa_direccion', p.prospecto_direccion_geo, p.prospecto_celular AS 'contacto', c.camp_id, c.camp_nombre, et.etapa_nombre, et.etapa_color 
                    FROM prospecto p 
                    INNER JOIN empresa e ON p.empresa_id=e.empresa_id 
                    INNER JOIN campana c ON c.camp_id=p.camp_id 
                    INNER JOIN hito ON hito.etapa_id=p.prospecto_etapa AND hito.prospecto_id=p.prospecto_id 
                    INNER JOIN etapa et ON et.etapa_id=p.prospecto_etapa
                    WHERE p.prospecto_consolidado=0 AND p.ejecutivo_id=? AND p.camp_id=? ORDER BY p.prospecto_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $consolidado));

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
    
    function ObtenerProspectosEjecutivo($codigo_ejecutivo) 
    {        
        try 
        {
            $sql = "SELECT p.prospecto_nombre_cliente, p.prospecto_empresa, CASE p.prospecto_tipo_lead WHEN 1 then 'Asignado' WHEN 2 then 'Proactivo' END AS 'prospecto_tipo_lead', p.prospecto_id, p.ejecutivo_id, p.tipo_persona_id, p.empresa_id, e.empresa_categoria, p.prospecto_fecha_asignacion, p.prospecto_consolidado, p.prospecto_observado_app, p.prospecto_estado_actual, CASE e.empresa_categoria WHEN 1 then e.empresa_nombre_legal WHEN 2 then e.empresa_nombre_establecimiento END AS 'empresa_nombre_legal', e.empresa_direccion_literal AS 'empresa_direccion', e.empresa_dato_contacto AS 'contacto'  FROM prospecto p, empresa e WHERE p.ejecutivo_id=? AND p.empresa_id=e.empresa_id ORDER BY p.prospecto_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function ObtenerObsProspectos($codigo_prospecto, $estado) 
    {        
        try 
        {
            $sql = "SELECT o.obs_id, o.prospecto_id, o.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app) as 'usuario_nombre', o.documento_id, d.documento_nombre, o.obs_tipo, o.obs_fecha, o.obs_detalle, o.obs_estado, o.accion_usuario, o.accion_fecha FROM observacion_documento o, usuarios u, documento d WHERE o.prospecto_id=? AND o.obs_estado=? AND o.usuario_id=u.usuario_id AND o.documento_id=d.documento_id ORDER BY obs_fecha ASC "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto, $estado));

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
    
    function VerificaCheckOut($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_id FROM prospecto WHERE prospecto_id=? AND prospecto_llamada=1 "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function UpdateCheckOut($fechaCheckIn, $geoCheckIn, $codigo_prospecto, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_llamada=1, prospecto_llamada_fecha=?, prospecto_llamada_geo=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_prospecto));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaCheckIn($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_id FROM prospecto WHERE prospecto_id=? AND prospecto_checkin=1 "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function UpdateCheckIn($fechaCheckIn, $geoCheckIn, $codigo_prospecto, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_checkin=1, prospecto_checkin_fecha=?, prospecto_checkin_geo=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_prospecto));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ObtenerDetalleProspecto_comercio($codigo_empresa, $codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT pros.prospecto_id, e.ejecutivo_id, pros.tipo_persona_id, e.empresa_id, e.empresa_consolidada, e.empresa_categoria, e.empresa_nit, e.empresa_adquiriente, e.empresa_tipo_sociedad, e.empresa_nombre_legal, e.empresa_nombre_fantasia, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, e.empresa_nombre_referencia, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional, c.cal_visita_ini, c.cal_visita_fin, pros.prospecto_idc, pros.prospecto_nombre_cliente, pros.prospecto_empresa, pros.prospecto_ingreso, pros.prospecto_direccion, pros.prospecto_direccion_geo, pros.prospecto_telefono, pros.prospecto_celular, pros.prospecto_email, pros.prospecto_tipo_lead, pros.prospecto_matricula, cam.camp_id, cam.camp_nombre FROM empresa e INNER JOIN prospecto pros ON pros.empresa_id=e.empresa_id INNER JOIN calendario c ON c.ejecutivo_id=pros.ejecutivo_id AND c.cal_id_visita=pros.prospecto_id INNER JOIN campana cam ON cam.camp_id=pros.camp_id WHERE e.empresa_categoria=1 AND c.cal_tipo_visita=1 AND pros.prospecto_consolidado = 0 AND e.empresa_id=? AND pros.prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_empresa, $codigo_prospecto));

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

    function ObtenerDetalleProspecto_establecimiento($codigo_empresa, $codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT pros.prospecto_id, e.ejecutivo_id, pros.tipo_persona_id, e.empresa_id, e.empresa_consolidada, e.empresa_categoria, e.empresa_depende, e.empresa_nit AS 'parent_nit', e.empresa_adquiriente AS 'parent_adquiriente', e.empresa_tipo_sociedad AS 'parent_tipo_sociedad', e.empresa_nombre_legal AS 'parent_nombre_legal', e.empresa_nombre_fantasia AS 'parent_nombre_fantasia', e.empresa_rubro AS 'parent_rubro', e.empresa_perfil_comercial AS 'parent_perfil_comercial', e.empresa_mcc AS 'parent_mcc', e.empresa_nombre_referencia, e.empresa_nombre_establecimiento, e.empresa_denominacion_corta, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional, c.cal_visita_ini, c.cal_visita_fin FROM empresa e INNER JOIN prospecto pros ON pros.empresa_id=e.empresa_id INNER JOIN calendario c ON c.ejecutivo_id=e.ejecutivo_id AND c.cal_id_visita=pros.prospecto_id WHERE e.empresa_categoria=2 AND c.cal_tipo_visita = 1 AND pros.prospecto_consolidado = 0 AND e.empresa_id=? AND pros.prospecto_id=? ";

            $consulta = $this->db->query($sql, array($codigo_empresa, $codigo_prospecto));

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

    function ObtenerServicios($estado)
    {        
        try 
        {
            $sql = "SELECT servicio_id, servicio_detalle, servicio_activo, accion_usuario, accion_fecha FROM servicio WHERE servicio_activo=? ";

            $consulta = $this->db->query($sql, array($estado));

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

    function ObtenerDetalleProspecto_servicios($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_servicio_id, p.servicio_id, s.servicio_detalle FROM prospecto_servicio p, servicio s WHERE prospecto_id=? AND p.servicio_id=s.servicio_id AND s.servicio_activo=1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function ObtenerComercioPorNIT($nit)
    {        
        try 
        {
            $sql = "SELECT empresa_id, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc FROM empresa WHERE empresa_nit=? AND empresa_categoria=1 AND empresa_consolidada=1 LIMIT 1 ";

            $consulta = $this->db->query($sql, array($nit));

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
    
    function ObtenerComercioPorNITSolicitud($nit)
    {        
        try 
        {
            $sql = "SELECT empresa_id, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_consolidada FROM empresa WHERE empresa_nit=? AND empresa_categoria=1 LIMIT 1 ";

            $consulta = $this->db->query($sql, array($nit));

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
    
    function ObtenerEmpresaNIT($nit)
    {        
        try 
        {
            $sql = "SELECT e.empresa_id, e.ejecutivo_id, e.empresa_consolidada, e.empresa_categoria, 
                    CASE e.empresa_categoria
                      WHEN 1 then e.empresa_nombre_legal
                      WHEN 2 then e.empresa_nombre_establecimiento
                    END AS 'empresa_nombre', e.empresa_tipo_sociedad, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, CONCAT(u.usuario_app, ' ', u.usuario_apm, ' ', u.usuario_nombres) as 'ejecutivo_nombre', ej.usuario_id FROM empresa e
                    INNER JOIN ejecutivo ej ON ej.ejecutivo_id=e.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                    WHERE empresa_consolidada=1 AND empresa_nit=? ";

            $consulta = $this->db->query($sql, array($nit));

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
    
    function ObtenerEmpresaNITEjecutivo($nit, $codigo_ejecutivo)
    {        
        try 
        {
            $sql = "SELECT empresa_id, ejecutivo_id, empresa_consolidada, empresa_categoria, empresa_nit, 
                    CASE empresa_categoria
                      WHEN 1 then IF(STRCMP(empresa_nombre_fantasia, '') = 0, empresa_nombre_legal, empresa_nombre_fantasia)
                      WHEN 2 then empresa_nombre_establecimiento
                    END AS 'empresa_nombre', empresa_tipo_sociedad, empresa_rubro, empresa_perfil_comercial, empresa_mcc FROM empresa WHERE CONCAT(empresa_nit, empresa_nombre_legal) LIKE CONCAT('%', ?, '%') ";

            $consulta = $this->db->query($sql, array($nit, $codigo_ejecutivo));

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
    
    function ObtenerHorariosEjecutivo($codigo_ejecutivo, $fecha_dia, $tipo_visita)
    {        
        try 
        {
            $criterio = "";
            if($fecha_dia != -1)
            {
                $criterio .= " AND cal_visita_ini BETWEEN '" . $fecha_dia . " 00:00:01' AND '" . $fecha_dia . " 23:59:59' ";
            }
            
            if($tipo_visita != -1)
            {
                $criterio .= " AND cal_tipo_visita=". $tipo_visita;
            }
            
            $sql = "SELECT ejecutivo_id, cal_id, cal_tipo_visita, cal_id_visita, cal_visita_ini, cal_visita_fin, empresa_id, empresa_categoria, empresa_nombre
                    FROM(
                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, 
                            CASE e.empresa_categoria
                                    WHEN 1 then e.empresa_nombre_legal
                                    WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN prospecto p ON p.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1 AND p.prospecto_checkin=0 AND p.prospecto_consolidado=0

                            UNION ALL

                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, 
                            CASE e.empresa_categoria
                                    WHEN 1 then e.empresa_nombre_legal
                                    WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN mantenimiento m ON m.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=m.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 AND m.mant_checkin=0 AND m.mant_estado=0
                            ) a WHERE ejecutivo_id=? " . $criterio . " ORDER BY cal_visita_ini ASC ";

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function VerificaNitExistente($data)
    {        
        try 
        {
            $sql = "SELECT empresa_id FROM empresa WHERE empresa_nit=? ";

            $consulta = $this->db->query($sql, array($data));

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
    
    function InsertarProspecto_comercioAPP($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO empresa(ejecutivo_id, empresa_categoria, empresa_depende, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_referencia, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_ha_desde, empresa_ha_hasta, empresa_dias_atencion, empresa_medio_contacto, empresa_email, empresa_dato_contacto, empresa_departamento, empresa_municipio, empresa_zona, empresa_tipo_calle, empresa_calle, empresa_numero, empresa_direccion_literal, empresa_direccion_geo, empresa_info_adicional, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function InsertarProspecto_establecimientoAPP($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_nombre_referencia, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO empresa(ejecutivo_id, empresa_categoria, empresa_depende, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_nombre_legal, empresa_nombre_fantasia, empresa_nombre_referencia, empresa_nombre_establecimiento, empresa_denominacion_corta, empresa_ha_desde, empresa_ha_hasta, empresa_dias_atencion, empresa_medio_contacto, empresa_email, empresa_dato_contacto, empresa_departamento, empresa_municipio, empresa_zona, empresa_tipo_calle, empresa_calle, empresa_numero, empresa_direccion_literal, empresa_direccion_geo, empresa_info_adicional, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $empresa_categoria, $empresa_depende, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_nombre_referencia, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function InsertarProspecto_APP($ejecutivo_id, $tipo_persona_id, $empresa_id, $prospecto_fecha_asignacion, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto(ejecutivo_id, tipo_persona_id, empresa_id, prospecto_fecha_asignacion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $tipo_persona_id, $empresa_id, $prospecto_fecha_asignacion, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function EliminarServiciosProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "DELETE FROM prospecto_servicio WHERE prospecto_id=? ";

            $this->db->query($sql, array($codigo_prospecto));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarServiciosProspecto($prospecto_id, $servicio_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO prospecto_servicio(prospecto_id, servicio_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($prospecto_id, $servicio_id, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarFechaCaendario($ejecutivo_id, $cal_id_visita, $cal_tipo_visita, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO calendario(ejecutivo_id, cal_id_visita, cal_tipo_visita, cal_visita_ini, cal_visita_fin, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $cal_id_visita, $cal_tipo_visita, $cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaProspectoConsolidado($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_consolidado, prospecto_estado_actual, prospecto_etapa, prospecto_observado_app, tipo_persona_id FROM prospecto WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function VerificaProspectoRechazo($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_consolidado, prospecto_estado_actual, prospecto_etapa, prospecto_observado_app, tipo_persona_id FROM prospecto WHERE prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function RechazoProspecto($rechazo_detalle, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_rechazado=2, prospecto_rechazado_fecha=?, prospecto_rechazado_detalle=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_fecha, $rechazo_detalle, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ActualizarProspecto($empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha, $prospecto_id, $empresa_id, $ejecutivo_id, $tipo_persona_id, $cal_visita_ini, $cal_visita_fin)
    {        
        try 
        {
            $sql = "UPDATE empresa SET empresa_tipo_sociedad=?, empresa_nombre_referencia=?, empresa_nombre_legal=?, empresa_nombre_fantasia=?, empresa_rubro=?, empresa_perfil_comercial=?, empresa_mcc=?, empresa_nombre_establecimiento=?, empresa_denominacion_corta=?, empresa_ha_desde=?, empresa_ha_hasta=?, empresa_dias_atencion=?, empresa_medio_contacto=?, empresa_email=?, empresa_dato_contacto=?, empresa_departamento=?, empresa_municipio=?, empresa_zona=?, empresa_tipo_calle=?, empresa_calle=?, empresa_numero=?, empresa_direccion_literal=?, empresa_direccion_geo=?, empresa_info_adicional=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? AND ejecutivo_id=? ";

            $this->db->query($sql, array($empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_direccion_geo, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id, $ejecutivo_id));
        
            $sql2 = "UPDATE prospecto SET tipo_persona_id=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $this->db->query($sql2, array($tipo_persona_id, $accion_usuario, $accion_fecha, $prospecto_id));
            
            $sql3 = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? AND cal_id_visita=?";

            $this->db->query($sql3, array($cal_visita_ini, $cal_visita_fin, $accion_usuario, $accion_fecha, $ejecutivo_id, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDocumentosEnviar($prospecto)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN prospecto p ON p.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_vigente=1 AND d.documento_enviar=1 AND d.documento_pdf IS NOT NULL AND p.prospecto_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($prospecto));

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
    
    function ObtenerDetalleEmpresaCorreo($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT camp.camp_nombre, p.prospecto_id, e.empresa_id, e.empresa_email, e.empresa_nombre_referencia, e.empresa_categoria as 'empresa_categoria_codigo', ej.ejecutivo_id, 
                        CASE e.empresa_categoria
                            WHEN 1 then e.empresa_nombre_legal
                            WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',
                        CASE e.empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                        END AS 'empresa_categoria',
                        CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_asignado_nombre', u.usuario_telefono as 'ejecutivo_asignado_contacto', u.usuario_email as 'ejecutivo_asignado_correo', u.usuario_id, e.empresa_nit, e.empresa_adquiriente, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional, p.tipo_persona_id
                    FROM empresa e
                    INNER JOIN prospecto p ON p.empresa_id=e.empresa_id
                    INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                    INNER JOIN campana camp ON camp.camp_id=p.camp_id
                    WHERE p.prospecto_id=? ";

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function ObtenerDetalleEmpresa($codigo_empresa)
    {        
        try 
        {
            $sql = "SELECT e.empresa_id, e.empresa_consolidada, e.empresa_tipo_sociedad, e.empresa_email, e.empresa_nombre_referencia, empresa_nombre_legal, empresa_nombre_fantasia, empresa_nombre_establecimiento, empresa_denominacion_corta, empresa_categoria,
                        CASE e.empresa_categoria
                            WHEN 1 then e.empresa_nombre_legal
                            WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',
                        CASE e.empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                        END AS 'empresa_categoria',
                        CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_asignado_nombre', u.usuario_telefono as 'ejecutivo_asignado_contacto', u.usuario_email as 'ejecutivo_asignado_correo', u.usuario_id, e.empresa_nit, e.empresa_adquiriente, e.empresa_rubro, e.empresa_perfil_comercial, e.empresa_mcc, e.empresa_ha_desde, e.empresa_ha_hasta, e.empresa_dias_atencion, e.empresa_medio_contacto, e.empresa_email, e.empresa_dato_contacto, e.empresa_departamento, e.empresa_municipio, e.empresa_zona, e.empresa_tipo_calle, e.empresa_calle, e.empresa_numero, e.empresa_direccion_literal, e.empresa_direccion_geo, e.empresa_info_adicional
                    FROM empresa e
                    INNER JOIN ejecutivo ej ON ej.ejecutivo_id=e.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                    WHERE e.empresa_id=? ";

            $consulta = $this->db->query($sql, array($codigo_empresa));

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
	
    function ObtenerDocumentosDigitalizar($prospecto)
    {        
        try 
        {
            $sql = "SELECT d.documento_id, d.documento_nombre FROM tipo_persona_documento tpd INNER JOIN documento d ON d.documento_id=tpd.documento_id INNER JOIN tipo_persona tp ON tp.tipo_persona_id=tpd.tipo_persona_id INNER JOIN prospecto p ON p.tipo_persona_id=tp.tipo_persona_id WHERE d.documento_vigente=1 AND p.prospecto_id=? ORDER BY d.documento_nombre ASC ";

            $consulta = $this->db->query($sql, array($prospecto));

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
    
    function VerificaDocumentosDigitalizar($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT pd.prospecto_documento_pdf, CONCAT(p.prospecto_carpeta, '_', p.prospecto_id) as 'prospecto_carpeta' FROM prospecto_documento pd, prospecto p WHERE p.prospecto_id=pd.prospecto_id AND pd.prospecto_id=? AND pd.documento_id=? ORDER BY pd.prospecto_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    function ObtenerDocumentoDigitalizar($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT pd.prospecto_documento_pdf, CONCAT(p.prospecto_carpeta, '_', p.prospecto_id) as 'prospecto_carpeta' FROM prospecto_documento pd, prospecto p WHERE p.prospecto_id=pd.prospecto_id AND pd.prospecto_id=? AND pd.prospecto_documento_id=? ORDER BY pd.prospecto_documento_id DESC LIMIT 1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    function ListaDocumentosDigitalizar($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT pd.prospecto_documento_id, pd.prospecto_documento_pdf, CONCAT(p.prospecto_carpeta, '_', p.prospecto_id) as 'prospecto_carpeta', pd.accion_fecha FROM prospecto_documento pd, prospecto p WHERE p.prospecto_id=pd.prospecto_id AND pd.prospecto_id=? AND pd.documento_id=? ORDER BY pd.prospecto_documento_id DESC ";

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    function ObtenerNombreDocumento($codigo_documento)
    {        
        try 
        {
            $sql = "SELECT documento_nombre, documento_vigente, documento_enviar, documento_pdf FROM documento WHERE documento_id=? ";

            $consulta = $this->db->query($sql, array($codigo_documento));

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
	
    function ObtenerNombreDocumentoEnviar($codigo_documento)
    {        
        try 
        {
            $sql = "SELECT documento_nombre, documento_vigente, documento_enviar, documento_pdf FROM documento WHERE documento_id=? AND documento_enviar=1 AND documento_pdf IS NOT NULL ";

            $consulta = $this->db->query($sql, array($codigo_documento));

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
	
    function InsertarDocumentoProspecto($prospecto_id, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO prospecto_documento(prospecto_id, documento_id, prospecto_documento_pdf, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) ";

            $consulta = $this->db->query($sql, array($prospecto_id, $documento_id, $prospecto_documento_pdf, $accion_usuario, $accion_fecha));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ActualizarProspecto_EnviarCumplimiento($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_estado_actual=1, prospecto_observado_app=0, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
	
    function ConsolidarProspecto($geolocalizacion, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_consolidado=1, prospecto_observado_app=0, prospecto_consolidar_geo=?, prospecto_consolidar_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($geolocalizacion, $accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ForzarConsolidarProspecto($geolocalizacion, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_estado_actual=3, prospecto_consolidado=1, prospecto_observado_app=0, prospecto_consolidar_geo=?, prospecto_consolidar_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($geolocalizacion, $accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ActualizarFlagAuxProspecto($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_aux_cump=?, prospecto_aux_legal=? WHERE prospecto_id=? ";

            $consulta = $this->db->query($sql, array($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id));
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Mantenimientos
    
    function ObtenerBandejaMantenimientos($codigo_ejecutivo, $estado)
    {        
        try 
        {
            $sql = "SELECT m.mant_id, m.ejecutivo_id, m.empresa_id, e.empresa_categoria, m.mant_fecha_asignacion, m.mant_estado, CASE e.empresa_categoria WHEN 1 then e.empresa_nombre_legal WHEN 2 then e.empresa_nombre_establecimiento END AS 'empresa_nombre_legal', e.empresa_direccion_literal AS 'empresa_direccion', e.empresa_dato_contacto AS 'contacto', c.cal_visita_ini, c.cal_visita_fin, e.empresa_direccion_geo FROM mantenimiento m INNER JOIN empresa e ON e.empresa_id=m.empresa_id INNER JOIN calendario c ON c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 WHERE m.ejecutivo_id=? AND m.mant_estado=? ORDER BY m.mant_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $estado));

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
    
    function ObtenerMantenimientosEjecutivos($codigo_ejecutivo)
    {        
        try 
        {
            $sql = "SELECT m.mant_id, m.ejecutivo_id, m.empresa_id, e.empresa_categoria, m.mant_fecha_asignacion, m.mant_estado, CASE e.empresa_categoria WHEN 1 then e.empresa_nombre_legal WHEN 2 then e.empresa_nombre_establecimiento END AS 'empresa_nombre_legal', e.empresa_direccion_literal AS 'empresa_direccion', e.empresa_dato_contacto AS 'contacto', c.cal_visita_ini, c.cal_visita_fin, e.empresa_direccion_geo FROM mantenimiento m INNER JOIN empresa e ON e.empresa_id=m.empresa_id INNER JOIN calendario c ON c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 WHERE m.ejecutivo_id=? ORDER BY m.mant_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function VerificaCheckInMantenimiento($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "SELECT mant_id FROM mantenimiento WHERE mant_id=? AND mant_checkin=1 "; 

            $consulta = $this->db->query($sql, array($codigo_mantenimiento));

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
    
    function UpdateCheckInMantenimiento($fechaCheckIn, $geoCheckIn, $codigo_mantenimiento, $usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE mantenimiento SET mant_checkin=1, mant_checkin_fecha=?, mant_checkin_geo=?, accion_usuario=?, accion_fecha=? WHERE mant_id=? "; 

            $consulta = $this->db->query($sql, array($fechaCheckIn, $geoCheckIn, $usuario, $accion_fecha, $codigo_mantenimiento));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerTareas()
    {        
        try 
        {
            $sql = "SELECT tarea_id, tarea_detalle, tarea_activo, accion_usuario, accion_fecha FROM tarea WHERE tarea_activo=1 "; 

            $consulta = $this->db->query($sql, array(0));

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
    
    function VerificaMantenimientoCompletado($codigo_mantenimiento) 
    {        
        try 
        {
            $sql = "SELECT mant_estado FROM mantenimiento WHERE mant_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_mantenimiento));

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
    
    function UpdateHorarioMantenimiento($fecha_visita_ini, $fecha_visita_fin, $codigo_ejecutivo, $codigo_mantenimiento, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE cal_tipo_visita=2 AND ejecutivo_id=? AND cal_id_visita=? "; 

            $consulta = $this->db->query($sql, array($fecha_visita_ini, $fecha_visita_fin, $accion_usuario, $accion_fecha, $codigo_ejecutivo, $codigo_mantenimiento));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarMantenimiento($ejecutivo_id, $empresa_id, $mant_fecha_asignacion, $accion_usuario, $accion_fecha)
    {        
        try 
        {            
            $sql = "INSERT INTO mantenimiento(ejecutivo_id, empresa_id, mant_fecha_asignacion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($ejecutivo_id, $empresa_id, $mant_fecha_asignacion, $accion_usuario, $accion_fecha));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function UpdateSubirDocumentoMantenimiento($mant_documento_adjunto, $accion_usuario, $accion_fecha, $codigo_mantenimiento)
    {        
        try 
        {
            $sql = "UPDATE mantenimiento SET mant_documento_adjunto=?, accion_usuario=?, accion_fecha=? WHERE mant_id=? "; 

            $consulta = $this->db->query($sql, array($mant_documento_adjunto, $accion_usuario, $accion_fecha, $codigo_mantenimiento));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function EliminarTareasMantenimiento($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "DELETE FROM mantenimiento_tarea WHERE mant_id=? ";

            $this->db->query($sql, array($codigo_mantenimiento));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarTareasMantenimiento($codigo_mantenimiento, $tarea_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO mantenimiento_tarea(mant_id, tarea_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($codigo_mantenimiento, $tarea_id, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function CompletarMantenimiento($mant_completado_geo, $mant_otro, $mant_otro_detalle, $accion_usuario, $accion_fecha, $codigo_mantenimiento)
    {        
        try 
        {
            $sql = "UPDATE mantenimiento SET mant_estado=1, mant_completado_fecha=?, mant_completado_geo=?, mant_otro=?, mant_otro_detalle=?, accion_usuario=?, accion_fecha=? WHERE mant_id=? ";

            $this->db->query($sql, array($accion_fecha, $mant_completado_geo, $mant_otro, $mant_otro_detalle, $accion_usuario, $accion_fecha, $codigo_mantenimiento));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Estadísticas
    
    function ReporteVisitasEntreFechas($codigo_ejecutivo, $fecha_ini, $fecha_fin)
    {        
        try 
        {
            $sql = " SELECT fecha, SUM(prospecto) AS 'prospecto', SUM(mantenimiento) AS 'mantenimiento'
                    FROM(
                            SELECT DATE(prospecto_consolidar_fecha) AS 'fecha', COUNT(*) AS 'prospecto', 0 AS 'mantenimiento' FROM prospecto WHERE ejecutivo_id=? AND prospecto_consolidado=1 AND prospecto_consolidar_fecha BETWEEN ? AND ? GROUP BY DATE(prospecto_consolidar_fecha)

                            UNION ALL

                            SELECT DATE(mant_completado_fecha) AS 'fecha', 0 AS 'prospecto', COUNT(*) AS 'mantenimiento' FROM mantenimiento WHERE ejecutivo_id=? AND mant_estado=1 AND mant_completado_fecha BETWEEN ? AND ? GROUP BY DATE(mant_completado_fecha)
                        ) a  
                    GROUP BY fecha
                    ORDER BY fecha ASC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $fecha_ini, $fecha_fin, $codigo_ejecutivo, $fecha_ini, $fecha_fin));

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
    
    // Entrega del Servicio
    
    function ObtenerBandejaEntregaServicio($codigo_ejecutivo, $entrega_servicio) 
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id, p.ejecutivo_id, p.empresa_id, e.empresa_categoria, p.prospecto_aceptado_afiliado_fecha, p.prospecto_entrega_servicio, CASE e.empresa_categoria WHEN 1 then e.empresa_nombre_legal WHEN 2 then e.empresa_nombre_establecimiento END AS 'empresa_nombre_legal', e.empresa_direccion_literal AS 'empresa_direccion', e.empresa_direccion_geo, e.empresa_dato_contacto AS 'contacto'  FROM prospecto p, empresa e WHERE p.prospecto_consolidado=1 AND p.prospecto_aceptado_afiliado=1 AND p.ejecutivo_id=? AND p.prospecto_entrega_servicio=? AND p.empresa_id=e.empresa_id ORDER BY p.prospecto_fecha_asignacion DESC "; 

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $entrega_servicio));

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
    
    function VerificaServicioEntregado($codigo_prospecto) 
    {        
        try 
        {
            $sql = "SELECT prospecto_entrega_servicio FROM prospecto WHERE prospecto_consolidado=1 AND prospecto_aceptado_afiliado=1 AND prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function CompletarEntregaServicio($geolocalizacion, $accion_usuario, $accion_fecha, $codigo_ejecutivo, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_entrega_servicio=1, prospecto_entrega_servicio_geo=?, prospecto_entrega_servicio_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_consolidado=1 AND prospecto_aceptado_afiliado=1 AND ejecutivo_id=? AND prospecto_id=? ";

            $this->db->query($sql, array($geolocalizacion, $accion_fecha, $accion_usuario, $accion_fecha, $codigo_ejecutivo, $codigo_prospecto));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // FIN SERVICIOS REST APP

    
    // Obtener Detalle del Catálogo
    function ObtenerDetalleCatalogo($data, $tipo)
    {        
        try 
        {
            $sql = "SELECT catalogo_id, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion FROM catalogo WHERE catalogo_codigo=? AND catalogo_tipo_codigo=? LIMIT 1";

            $consulta = $this->db->query($sql, array($data, $tipo));

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
    
    // Obtener Detalle del Catálogo de Tipo de Persona
    function ObtenerDetalleCatalogoTipo($data)
    {        
        try
        {
            if($data == -1)
            {
                $sql = "SELECT tipo_persona_id, categoria_persona_id, tipo_persona_nombre, tipo_persona_vigente FROM tipo_persona WHERE tipo_persona_vigente=1 AND tipo_persona_id>0 ";
            }
            else
            {
                $sql = "SELECT tipo_persona_id, categoria_persona_id, tipo_persona_nombre, tipo_persona_vigente FROM tipo_persona WHERE tipo_persona_id=? AND tipo_persona_vigente=1 AND tipo_persona_id>0 ";
            }
            $consulta = $this->db->query($sql, array($data));

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
    
    // Obtener Datos Usuario
    
    function ObtenerDatosUsuario($usuario_codigo) 
    {        
        try 
        {
            $sql = "SELECT u.usuario_id, u.usuario_user, u.estructura_agencia_id, a.estructura_agencia_nombre, r.estructura_regional_nombre, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_activo FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id WHERE usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo));

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
    
    function ObtenerCodigoRegionUsuario($usuario_codigo) 
    {        
        try 
        {
            $sql = "SELECT r.estructura_regional_id, r.estructura_regional_nombre FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON r.estructura_regional_id=a.estructura_regional_id WHERE u.usuario_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo));

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
    
    function ObtenerDetalleDatosUsuario($tipo_codigo, $codigo_usuario)
    {        
        try 
        {
            $criterio = 'usuario_id';
            
            if($tipo_codigo == 1)
            {
                $criterio = 'usuario_user';
            }
            
            if($tipo_codigo == 2)
            {
                $criterio = 'usuario_rol';
            }
            
            $sql = "SELECT u.usuario_id, u.usuario_user, u.estructura_agencia_id, a.estructura_agencia_nombre, r.estructura_regional_nombre, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_activo FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id WHERE $criterio = ? "; 

            $consulta = $this->db->query($sql, array($codigo_usuario));

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
    
    // Roles, Perfiles y Menus
    
    function ObtenerDatosRolesUsuario($estado)
    {        
        try 
        {
            $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol WHERE rol_estado!=? AND rol_id!=1"; 

            $consulta = $this->db->query($sql, array($estado));

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
    
    function ObtenerDatosRolAdministrador()
    {        
        try 
        {
            $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol WHERE rol_id=?"; 

            $consulta = $this->db->query($sql, 1);

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
    
    function ObtenerDatosRoles($estado)
    {        
        try 
        {
            $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol WHERE rol_estado!=? "; 

            $consulta = $this->db->query($sql, array($estado));

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
    
    function ObtenerCategoriaPersonas($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT categoria_persona_id, categoria_persona_nombre, categoria_persona_vigente, accion_usuario, accion_fecha FROM categoria_persona WHERE categoria_persona_vigente=1 "; 
            }
            else
            {
                $sql = "SELECT categoria_persona_id, categoria_persona_nombre, categoria_persona_vigente, accion_usuario, accion_fecha FROM categoria_persona WHERE categoria_persona_vigente=1 AND categoria_persona_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerPersonas($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT p.tipo_persona_id, p.categoria_persona_id, c.categoria_persona_nombre, p.tipo_persona_nombre, p.tipo_persona_vigente, p.accion_usuario, p.accion_fecha FROM tipo_persona p INNER JOIN categoria_persona c ON c.categoria_persona_id=p.categoria_persona_id WHERE p.tipo_persona_vigente=1 "; 
            }
            else
            {
                $sql = "SELECT p.tipo_persona_id, p.categoria_persona_id, c.categoria_persona_nombre, p.tipo_persona_nombre, p.tipo_persona_vigente, p.accion_usuario, p.accion_fecha FROM tipo_persona p INNER JOIN categoria_persona c ON c.categoria_persona_id=p.categoria_persona_id WHERE p.tipo_persona_vigente=1 AND p.tipo_persona_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerRoles($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol"; 
            }
            else
            {
                $sql = "SELECT rol_id, rol_nombre, rol_descirpcion, rol_estado, accion_usuario, accion_fecha FROM rol WHERE rol_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDatosMenu()
    {        
        try 
        {
            $sql = "SELECT menu_id, menu_nombre, menu_descripcion, menu_enlace, accion_usuario, accion_fecha FROM menu ORDER BY menu_nombre"; 

            $consulta = $this->db->query($sql);

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
    
    function ObtenerDatosRolMenu($rol_codigo, $menu_codigo)
    {        
        try 
        {
            $sql = "SELECT rol_menu_id, rol_id, menu_id, accion_usuario, accion_fecha FROM rol_menu WHERE rol_id=? AND menu_id=? "; 

            $consulta = $this->db->query($sql, array($rol_codigo, $menu_codigo));

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
    
    function ObtenerDatosPersonaDocumento($persona_codigo, $documento_codigo)
    {        
        try 
        {
            $sql = "SELECT tipo_persona_documento_id, tipo_persona_id, documento_id, accion_usuario, accion_fecha FROM tipo_persona_documento WHERE tipo_persona_id=? AND documento_id=? "; 

            $consulta = $this->db->query($sql, array($persona_codigo, $documento_codigo));

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
    
    function ObtenerDatosPerfiles($estado)
    {        
        try 
        {
            $sql = "SELECT perfil_id, perfil_nombre, perfil_descripcion, perfil_estado, accion_usuario, accion_fecha FROM perfil WHERE perfil_estado!=? ORDER BY perfil_nombre "; 

            $consulta = $this->db->query($sql, array($estado));

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
    
    function ObtenerPerfil($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT perfil_id, perfil_nombre, perfil_descripcion, perfil_estado, accion_usuario, accion_fecha FROM perfil ORDER BY perfil_nombre ASC "; 
            }
            else
            {
                $sql = "SELECT perfil_id, perfil_nombre, perfil_descripcion, perfil_estado, accion_usuario, accion_fecha FROM perfil WHERE perfil_id=? ORDER BY perfil_nombre ASC "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDatosUsuarioPerfil($usuario_codigo, $perfil_codigo)
    {        
        try 
        {
            $sql = "SELECT usuario_perfil_id, usuario_id, perfil_id, accion_usuario, accion_fecha FROM usuario_perfil WHERE usuario_id=? AND perfil_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $perfil_codigo));

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
    
    function EliminarPerfilUsuario($usuario_codigo)
    {        
        try 
        {
            $sql = "DELETE FROM usuario_perfil WHERE usuario_id=? "; 

            $consulta = $this->db->query($sql, array($usuario_codigo));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarPerfilUsuario($usuario_codigo, $perfil_codigo, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO usuario_perfil(usuario_id, perfil_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $perfil_codigo, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    // Agencias
    
    function ObtenerDatosAgencia($agencia_codigo)
    {        
        try 
        {
            if($agencia_codigo == -1)
            { 
                $sql = "SELECT a.estructura_agencia_id, a.estructura_regional_id AS 'parent_id', r.estructura_regional_nombre AS 'parent_detalle', a.estructura_agencia_nombre, a.accion_usuario, a.accion_fecha FROM estructura_agencia a INNER JOIN estructura_regional r ON r.estructura_regional_id=a.estructura_regional_id ORDER BY a.estructura_agencia_nombre "; 
            }
            else
            {
                $sql = "SELECT a.estructura_agencia_id, a.estructura_regional_id AS 'parent_id', r.estructura_regional_nombre AS 'parent_detalle', a.estructura_agencia_nombre, a.accion_usuario, a.accion_fecha FROM estructura_agencia a INNER JOIN estructura_regional r ON r.estructura_regional_id=a.estructura_regional_id WHERE estructura_agencia_id=? ORDER BY a.estructura_agencia_nombre "; 
            }

            $consulta = $this->db->query($sql, array($agencia_codigo));

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
    
    function ObtenerDatosRegional($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id "; 
            }
            else
            {
                $sql = "SELECT r.estructura_regional_id, r.estructura_entidad_id AS 'parent_id', e.estructura_entidad_nombre AS 'parent_detalle', r.estructura_regional_nombre, r.accion_usuario, r.accion_fecha FROM estructura_regional r INNER JOIN estructura_entidad e ON e.estructura_entidad_id=r.estructura_entidad_id WHERE estructura_regional_id=? "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerDatosEntidad($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT estructura_entidad_id, estructura_entidad_nombre, accion_usuario, accion_fecha FROM estructura_entidad "; 
            }
            else
            {
                $sql = "SELECT estructura_entidad_id, estructura_entidad_nombre, accion_usuario, accion_fecha FROM estructura_entidad WHERE estructura_entidad_id=? "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateAgencia($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE estructura_agencia SET estructura_regional_id=?,estructura_agencia_nombre=?,accion_usuario=?,accion_fecha=? WHERE estructura_agencia_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertAgencia($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO estructura_agencia(estructura_regional_id, estructura_agencia_nombre, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $estructura_agencia_nombre, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateRegional($codigo_parent, $estructura_regional_nombre, $accion_usuario, $accion_fecha, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE estructura_regional SET estructura_entidad_id=?, estructura_regional_nombre=?, accion_usuario=?, accion_fecha=? WHERE estructura_regional_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $estructura_regional_nombre, $accion_usuario, $accion_fecha, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertRegional($codigo_parent, $estructura_regional_nombre, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO estructura_regional(estructura_entidad_id, estructura_regional_nombre, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $estructura_regional_nombre, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Tipo de Persona
    
    function UpdatePersona($categoria_persona_id, $tipo_persona_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE tipo_persona SET categoria_persona_id=?, tipo_persona_nombre=?, accion_usuario=?, accion_fecha=? WHERE tipo_persona_id=? "; 
            
            $consulta = $this->db->query($sql, array($categoria_persona_id, $tipo_persona_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertPersona($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {            
            $sql = "INSERT INTO tipo_persona(categoria_persona_id, tipo_persona_nombre, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($catalogo_parent, $estructura_nombre, $nombre_usuario, $fecha_actual));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function EliminaDocumentoPersona($persona_codigo)
    {        
        try 
        {
            $sql = "DELETE FROM tipo_persona_documento WHERE tipo_persona_id=? "; 
            
            $consulta = $this->db->query($sql, array($persona_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarDocumentoPersona($persona_codigo, $documento_codigo, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO tipo_persona_documento(tipo_persona_id, documento_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($persona_codigo, $documento_codigo, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateRol($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE rol SET rol_nombre=?, rol_descirpcion=?, accion_usuario=?, accion_fecha=? WHERE rol_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertRol($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual)
    {        
        try 
        {            
            $sql = "INSERT INTO rol(rol_nombre, rol_descirpcion, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function EliminaMenuRol($rol_codigo)
    {        
        try 
        {
            $sql = "DELETE FROM rol_menu WHERE rol_id=? "; 
            
            $consulta = $this->db->query($sql, array($rol_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarMenuRol($codigo_rol, $codigo_menu, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO rol_menu(rol_id, menu_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_rol, $codigo_menu, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdatePerfil($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE perfil SET perfil_nombre=?, perfil_descripcion=?, accion_usuario=?, accion_fecha=? WHERE perfil_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $estructura_detalle, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaPermisoPorPerfil($codigo_usuario, $codigo_perfil)
    {        
        try 
        {
            $sql = "SELECT usuario_perfil_id, usuario_id, perfil_id FROM usuario_perfil WHERE usuario_id=? AND perfil_id=? ";

            $consulta = $this->db->query($sql, array($codigo_usuario, $codigo_perfil));

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
	
    // Documentos
	
    function ObtenerDocumento($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT documento_id, documento_nombre, documento_vigente, documento_enviar, documento_pdf, accion_usuario, accion_fecha FROM documento WHERE documento_vigente=1 "; 
            }
            else
            {
                $sql = "SELECT documento_id, documento_nombre, documento_vigente, documento_enviar, documento_pdf, accion_usuario, accion_fecha FROM documento WHERE documento_vigente AND documento_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
	
    function UpdateDocumento($documento_nombre, $documento_enviar, $documento_pdf, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE documento SET documento_nombre=?, documento_enviar=?, documento_pdf=?, accion_usuario=?, accion_fecha=? WHERE documento_id=? "; 
            
            $consulta = $this->db->query($sql, array($documento_nombre, $documento_enviar, $documento_pdf, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateDocumentoSinUpload($documento_nombre, $documento_enviar, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE documento SET documento_nombre=?, documento_enviar=?, accion_usuario=?, accion_fecha=? WHERE documento_id=? "; 
            
            $consulta = $this->db->query($sql, array($documento_nombre, $documento_enviar, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertDocumento($documento_nombre, $documento_enviar, $documento_pdf, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO documento(documento_nombre, documento_enviar, documento_pdf, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($documento_nombre, $documento_enviar, $documento_pdf, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    // Actividades
    
    function ObtenerActividades($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT act_id, act_detalle, act_activo, accion_usuario, accion_fecha FROM actividades WHERE act_activo=1 "; 
            }
            else
            {
                $sql = "SELECT act_id, act_detalle, act_activo, accion_usuario, accion_fecha FROM actividades WHERE act_activo=1 AND act_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateActividades($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE actividades SET act_detalle=?, accion_usuario=?, accion_fecha=? WHERE act_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertActividades($estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO actividades(act_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDetalleProspecto_actividades($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_actividades_id, p.act_id, a.act_detalle FROM prospecto_actividades p, actividades a WHERE prospecto_id=? AND p.act_id=a.act_id AND a.act_activo=1 ";

            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    function EliminarActividadesProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "DELETE FROM prospecto_actividades WHERE prospecto_id=? ";

            $this->db->query($sql, array($codigo_prospecto));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarActividadesProspecto($prospecto_id, $act_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO prospecto_actividades(prospecto_id, act_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";

            $this->db->query($sql, array($prospecto_id, $act_id, $accion_usuario, $accion_fecha));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Servicios
    
    function ObtenerServicio($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT servicio_id, servicio_detalle, servicio_activo, accion_usuario, accion_fecha FROM servicio WHERE servicio_activo=1 "; 
            }
            else
            {
                $sql = "SELECT servicio_id, servicio_detalle, servicio_activo, accion_usuario, accion_fecha FROM servicio WHERE servicio_activo=1 AND servicio_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateServicio($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE servicio SET servicio_detalle=?, accion_usuario=?, accion_fecha=? WHERE servicio_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertServicio($estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO servicio(servicio_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Tareas de Mantenimiento de Cartera
    
    function ObtenerTarea($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT tarea_id, tarea_detalle, accion_usuario, accion_fecha FROM tarea WHERE tarea_activo=1 "; 
            }
            else
            {
                $sql = "SELECT tarea_id, tarea_detalle, accion_usuario, accion_fecha FROM tarea WHERE tarea_activo=1 AND tarea_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateTarea($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE tarea SET tarea_detalle=?, accion_usuario=?, accion_fecha=? WHERE tarea_id=? "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertTarea($estructura_nombre, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO tarea(tarea_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($estructura_nombre, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Ejecutivos de Cuenta
    
    function ObtenerEjecutivo($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT e.ejecutivo_id, e.usuario_id, CONCAT(u.usuario_app, ' ', u.usuario_apm, ' ', u.usuario_nombres) AS 'usuario_nombre', e.ejecutivo_zona, 
                        CASE 
                        WHEN e.ejecutivo_zona='' OR e.ejecutivo_zona IS NULL THEN 'No'
                        WHEN e.ejecutivo_zona!='' OR e.ejecutivo_zona IS NOT NULL THEN 'Si'
                        END AS 'zona_registrada',
                        e.accion_usuario, e.accion_fecha FROM ejecutivo e
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id AND u.usuario_rol=2 ORDER BY u.usuario_app ASC ";
            }
            else
            {
                $sql = "SELECT e.ejecutivo_id, e.usuario_id, CONCAT(u.usuario_app, ' ', u.usuario_apm, ' ', u.usuario_nombres) AS 'usuario_nombre', e.ejecutivo_zona, 
                        CASE 
                        WHEN e.ejecutivo_zona='' OR e.ejecutivo_zona IS NULL THEN 'No'
                        WHEN e.ejecutivo_zona!='' OR e.ejecutivo_zona IS NOT NULL THEN 'Si'
                        END AS 'zona_registrada',
                        e.accion_usuario, e.accion_fecha FROM ejecutivo e
                        INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                        WHERE e.ejecutivo_id=? ORDER BY u.usuario_app ASC ";
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function HabilitarUsuariosEjecutivosCuenta($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT u.usuario_id, u.estructura_agencia_id, u.usuario_rol, CONCAT(usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'nombre_completo', u.usuario_user, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_recupera_token, u.usuario_recupera_solicitado, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.accion_fecha, u.accion_usuario, u.usuario_activo FROM usuarios u WHERE u.usuario_rol=2 "; 
            }

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function ObtenerUsuariosEjecutivosCuenta($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT u.usuario_id, u.estructura_agencia_id, u.usuario_rol, CONCAT(usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'nombre_completo', u.usuario_user, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_recupera_token, u.usuario_recupera_solicitado, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.accion_fecha, u.accion_usuario, u.usuario_activo FROM usuarios u INNER JOIN ejecutivo e ON e.usuario_id=u.usuario_id WHERE u.usuario_rol=2 "; 
            }
            else
            {
                $sql = "SELECT u.usuario_id, u.estructura_agencia_id, u.usuario_rol, CONCAT(usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'nombre_completo', u.usuario_user, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_recupera_token, u.usuario_recupera_solicitado, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.accion_fecha, u.accion_usuario, u.usuario_activo FROM usuarios u INNER JOIN ejecutivo e ON e.usuario_id=u.usuario_id WHERE u.usuario_rol=2 AND usuario_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateEjecutivo($codigo_usuario, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $this->load->model('mfunciones_generales');
            
            // Se verifica el ID de Ejecutivo del usuario
            $arrVerifica = $this->VerificarUsuarioEjecutivo($codigo_usuario);
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrVerifica);
            
            $sql1 = "UPDATE prospecto SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta1 = $this->db->query($sql1, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql2 = "UPDATE calendario SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta2 = $this->db->query($sql2, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql3 = "UPDATE empresa SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta3 = $this->db->query($sql3, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));
            
            $sql4 = "UPDATE mantenimiento SET ejecutivo_id=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? ";
            $consulta4 = $this->db->query($sql4, array($arrVerifica[0]['ejecutivo_id'], $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateZonaEjecutivo($zona, $nombre_usuario, $fecha_actual, $codigo_ejecutivo)
    {        
        try 
        {
            $sql = "UPDATE ejecutivo SET ejecutivo_zona=?, accion_usuario=?, accion_fecha=? WHERE ejecutivo_id=? "; 
            
            $consulta = $this->db->query($sql, array($zona, $nombre_usuario, $fecha_actual, $codigo_ejecutivo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificarUsuarioEjecutivo($usuario_codigo)
    {        
        try 
        {
            $sql = "SELECT ejecutivo_id FROM ejecutivo WHERE usuario_id=? ";         

            $consulta = $this->db->query($sql, array($usuario_codigo));

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
    
    function InsertEjecutivo($codigo_usuario, $nombre_usuario, $fecha_actual)
    {        
        try 
        {
            $sql = "INSERT INTO ejecutivo(usuario_id, accion_usuario, accion_fecha) VALUES (?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_usuario, $nombre_usuario, $fecha_actual));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Seguimiento a Ejecutivos de Cuenta
    
    function SeguimientoVisitasEjecutivo($codigo_ejecutivo, $fecha_ini, $fecha_fin, $filtro)
    {        
        try 
        {
            $criterio = '';
            
            switch ($filtro)
            {
                case 1:
                    $criterio = "AND tipo_visita='Lead'";
                break;
            
                case 2:
                        $criterio = "AND tipo_visita='Mantenimiento'";
                    break;
            
                default :
                    break;
            }
            
            $sql = "SELECT tipo_visita_codigo, tipo_visita, visita_id, ejecutivo_id, empresa_id, empresa_categoria, empresa_nombre, checkin, checkin_fecha, checkin_geo, cal_visita_ini
                    FROM(
                            SELECT '1' AS 'tipo_visita_codigo', 'Lead' AS 'tipo_visita', p.prospecto_id AS 'visita_id', p.ejecutivo_id, p.empresa_id, e.empresa_categoria,

                            CONCAT_WS(' | ', p.prospecto_nombre_cliente, p.prospecto_empresa) AS 'empresa_nombre',

                            p.prospecto_checkin AS 'checkin', p.prospecto_checkin_fecha AS 'checkin_fecha', p.prospecto_checkin_geo AS 'checkin_geo', p.prospecto_fecha_asignacion AS 'cal_visita_ini' FROM prospecto p 
                            INNER JOIN empresa e ON e.empresa_id=p.empresa_id
                            INNER JOIN calendario c ON c.cal_visita_ini AND c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1

                            WHERE prospecto_checkin=1

                            UNION ALL

                            SELECT '2' AS 'tipo_visita_codigo', 'Mantenimiento' AS 'tipo_visita', m.mant_id AS 'visita_id', m.ejecutivo_id, m.empresa_id, e.empresa_categoria,
                            CASE e.empresa_categoria
                               WHEN 1 then e.empresa_nombre_legal
                               WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre',
                            m.mant_checkin AS 'checkin', m.mant_checkin_fecha AS 'checkin_fecha', m.mant_checkin_geo AS 'checkin_geo', c.cal_visita_ini FROM mantenimiento m
                            INNER JOIN empresa e ON e.empresa_id=m.empresa_id
                            INNER JOIN calendario c ON c.cal_visita_ini AND c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2
                            WHERE mant_checkin=1
                            ) a
                    WHERE ejecutivo_id=? AND checkin_fecha BETWEEN ? AND ? $criterio ORDER BY  checkin_fecha ASC ";

            $consulta = $this->db->query($sql, array($codigo_ejecutivo, $fecha_ini, $fecha_fin, $filtro));

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
    
    function BandejaVisitasEjecutivo($codigo_usuario)
    {        
        try 
        {
            $sql = "SELECT tipo_visita_codigo, tipo_visita, visita_id, ejecutivo_id, empresa_id, empresa_categoria, empresa_nombre, checkin, checkin_fecha, checkin_geo, cal_visita_ini, usuario_id
                    FROM(
                        SELECT '1' AS 'tipo_visita_codigo', 'Prospecto' AS 'tipo_visita', p.prospecto_id AS 'visita_id', p.ejecutivo_id, p.empresa_id, e.empresa_categoria,

                        CASE e.empresa_categoria
                           WHEN 1 then e.empresa_nombre_legal
                           WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',

                        p.prospecto_checkin AS 'checkin', p.prospecto_checkin_fecha AS 'checkin_fecha', p.prospecto_checkin_geo AS 'checkin_geo', c.cal_visita_ini, u.usuario_id FROM prospecto p 
                        INNER JOIN empresa e ON e.empresa_id=p.empresa_id
                        INNER JOIN calendario c ON c.cal_visita_ini AND c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1
                        INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                            INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id

                        WHERE prospecto_checkin=1

                        UNION ALL

                        SELECT '2' AS 'tipo_visita_codigo', 'Mantenimiento' AS 'tipo_visita', m.mant_id AS 'visita_id', m.ejecutivo_id, m.empresa_id, e.empresa_categoria,
                        CASE e.empresa_categoria
                           WHEN 1 then e.empresa_nombre_legal
                           WHEN 2 then e.empresa_nombre_establecimiento
                        END AS 'empresa_nombre',
                        m.mant_checkin AS 'checkin', m.mant_checkin_fecha AS 'checkin_fecha', m.mant_checkin_geo AS 'checkin_geo', c.cal_visita_ini, u.usuario_id FROM mantenimiento m
                        INNER JOIN empresa e ON e.empresa_id=m.empresa_id
                        INNER JOIN calendario c ON c.cal_visita_ini AND c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=m.ejecutivo_id
                            INNER JOIN usuarios u ON u.usuario_id=ej.usuario_id
                        WHERE mant_checkin=1
                        ) a WHERE usuario_id=?";

            $consulta = $this->db->query($sql, array($codigo_usuario));

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
    
    // Horario Ejecutivos de Cuenta
    
    function HorarioVisitasEjecutivo($codigo_ejecutivo)
    {        
        try 
        {            
            $sql = "SELECT ejecutivo_id, cal_id, cal_tipo_visita, cal_id_visita, cal_visita_ini, cal_visita_fin, empresa_id, empresa_categoria, empresa_nombre
                    FROM(
                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, 
                            CASE e.empresa_categoria
                                    WHEN 1 then e.empresa_nombre_legal
                                    WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN prospecto p ON p.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=p.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1 AND p.prospecto_checkin=0 AND p.prospecto_consolidado=0

                            UNION ALL

                            SELECT ej.ejecutivo_id, c.cal_id, c.cal_tipo_visita, c.cal_id_visita, c.cal_visita_ini, c.cal_visita_fin, e.empresa_id, e.empresa_categoria, 
                            CASE e.empresa_categoria
                                    WHEN 1 then e.empresa_nombre_legal
                                    WHEN 2 then e.empresa_nombre_establecimiento
                            END AS 'empresa_nombre'
                            FROM empresa e
                            INNER JOIN mantenimiento m ON m.empresa_id=e.empresa_id 
                            INNER JOIN ejecutivo ej ON ej.ejecutivo_id=m.ejecutivo_id
                            INNER JOIN calendario c ON c.ejecutivo_id=ej.ejecutivo_id AND c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2 AND m.mant_checkin=0 AND m.mant_estado=0
                            ) a WHERE ejecutivo_id=? ORDER BY cal_visita_ini ASC ";

            $consulta = $this->db->query($sql, array($codigo_ejecutivo));

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
    
    function UpdateHorarioEjecutivo($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_horario)
    {        
        try 
        {
            $sql = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE cal_id=? "; 
            
            $consulta = $this->db->query($sql, array($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_horario));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateHorarioReVisita($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE calendario SET cal_visita_ini=?, cal_visita_fin=?, accion_usuario=?, accion_fecha=? WHERE cal_tipo_visita=1 AND cal_id_visita=? "; 
            
            $consulta = $this->db->query($sql, array($fecha_inicio, $fecha_fin, $nombre_usuario, $fecha_actual, $codigo_prospecto));
            
            $sql2 = "UPDATE prospecto SET prospecto_consolidado=0, prospecto_fecha_asignacion=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=?"; 
            
            $consulta2 = $this->db->query($sql2, array($fecha_inicio, $nombre_usuario, $fecha_actual, $codigo_prospecto));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Solicitudes de Prospectos
    
    function ObtenerSolicitudProspecto($codigo, $estado)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT solicitud_id, solicitud_nombre_persona, solicitud_nombre_empresa, solicitud_departamento, solicitud_ciudad, solicitud_zona, solicitud_telefono, solicitud_email, solicitud_direccion_literal, solicitud_direccion_geo, solicitud_rubro, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion FROM solicitud_afiliacion WHERE solicitud_confirmado<=1 AND solicitud_estado<=? AND solicitud_confirmado<=1 ORDER BY solicitud_fecha DESC "; 
            }
            else
            {
                $sql = "SELECT solicitud_id, solicitud_nombre_persona, solicitud_nombre_empresa, solicitud_departamento, solicitud_ciudad, solicitud_zona, solicitud_telefono, solicitud_email, solicitud_direccion_literal, solicitud_direccion_geo, solicitud_rubro, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion FROM solicitud_afiliacion WHERE solicitud_confirmado<=1 AND solicitud_estado<=? AND solicitud_id=? AND solicitud_confirmado<=1 ORDER BY solicitud_fecha DESC "; 
            }

            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerServiciosSolicitud($codigo_solicitud)
    {        
        try 
        {
            $sql = "SELECT s.solicitud_servicio_id, s.solicitud_id, s.servicio_id, se.servicio_detalle FROM solicitud_servicio s INNER JOIN servicio se ON se.servicio_id=s.servicio_id WHERE s.solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function RechazarSolicitudProspecto($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_mantenimiento SET solicitud_estado=2, solicitud_observacion=?, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AprobarSolicitudProspecto($nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_afiliacion SET solicitud_estado=1, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Servicios Campaña
    
    function ObtenerServiciosCampana($codigo_solicitud)
    {        
        try 
        {
            $sql = "SELECT c.campana_servicio_id, c.camp_id, c.servicio_id, se.servicio_detalle FROM campana_servicio c INNER JOIN servicio se ON se.servicio_id=c.servicio_id WHERE c.camp_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function ObtenerServicioCampana($camp_id, $servicio_codigo)
    {        
        try 
        {
            $sql = "SELECT campana_servicio_id, camp_id, servicio_id, accion_usuario, accion_fecha FROM campana_servicio WHERE camp_id=? AND servicio_id=? "; 

            $consulta = $this->db->query($sql, array($camp_id, $servicio_codigo));

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
    
    function Eliminar_SolicitudCampana($codigo_campana)
    {        
        try 
        {
            $sql = "DELETE FROM campana_servicio WHERE camp_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_campana));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarCampanaServicio($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO campana_servicio(camp_id, servicio_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Servicios Prospecto
    
    function ObtenerServicioSolicitud($solicitud_codigo, $servicio_codigo)
    {        
        try 
        {
            $sql = "SELECT solicitud_servicio_id, solicitud_id, servicio_id, accion_usuario, accion_fecha FROM solicitud_servicio WHERE solicitud_id=? AND servicio_id=? "; 

            $consulta = $this->db->query($sql, array($solicitud_codigo, $servicio_codigo));

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
    
    function UpdateSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_afiliacion SET solicitud_nombre_persona=?, solicitud_nombre_empresa=?, solicitud_departamento=?, solicitud_ciudad=?, solicitud_zona=?, solicitud_telefono=?, solicitud_email=?, solicitud_direccion_literal=?, solicitud_direccion_geo=?, solicitud_rubro=?, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $coordenadas_solicitud, $catalogo_rubro, $nombre_usuario, $fecha_actual, $estructura_id));
            
            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function InsertSolicitudProspecto($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $solicitud_direccion_geo, $solicitud_rubro, $solicitud_fecha, $accion_usuario, $accion_fecha, $ip, $token)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_afiliacion(solicitud_nombre_persona, solicitud_nombre_empresa, solicitud_departamento, solicitud_ciudad, solicitud_zona, solicitud_telefono, solicitud_email, solicitud_direccion_literal, solicitud_direccion_geo, solicitud_rubro, solicitud_fecha, accion_usuario, accion_fecha, solicitud_ip, solicitud_token, solicitud_confirmado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($solicitud_nombre_persona, $solicitud_nombre_empresa, $empresa_departamento, $empresa_municipio, $empresa_zona, $solicitud_telefono, $solicitud_email, $solicitud_direccion_literal, $solicitud_direccion_geo, $solicitud_rubro, $solicitud_fecha, $accion_usuario, $accion_fecha, $ip, $token, 1));
            
            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function Eliminar_SolicitudProspecto($codigo_solicitud)
    {        
        try 
        {
            $sql = "DELETE FROM solicitud_servicio WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarSolicitudServicio($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_servicio(solicitud_id, servicio_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud, $codigo_servicio, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Solicitudes de Mantenimiento
    
    function ObtenerSolicitudMantenimiento($codigo, $estado)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT solicitud_id, solicitud_nit, solicitud_nombre, solicitud_email, solicitud_otro, solicitud_otro_detalle, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion, accion_usuario, accion_fecha FROM solicitud_mantenimiento WHERE solicitud_estado=? AND solicitud_confirmado=1 ORDER BY solicitud_fecha DESC "; 
            }
            else
            {
                $sql = "SELECT solicitud_id, solicitud_nit, solicitud_nombre, solicitud_email, solicitud_otro, solicitud_otro_detalle, solicitud_fecha, solicitud_confirmado, solicitud_token, solicitud_ip, solicitud_estado, solicitud_observacion, accion_usuario, accion_fecha FROM solicitud_mantenimiento WHERE solicitud_estado=? AND solicitud_id=? AND solicitud_confirmado=1 ORDER BY solicitud_fecha DESC "; 
            }

            $consulta = $this->db->query($sql, array($estado, $codigo));

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
    
    function ObtenerTareasSolicitud($codigo_solicitud)
    {        
        try 
        {
            $sql = "SELECT s.solicitud_tarea_id, s.solicitud_id, s.tarea_id, t.tarea_detalle FROM solicitud_tarea s INNER JOIN tarea t ON t.tarea_id=s.tarea_id WHERE s.solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));

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
    
    function RechazarSolicitudMantenimiento($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE solicitud_afiliacion SET solicitud_estado=2, solicitud_observacion=?, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($solicitud_observacion, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }

    function InsertSolicitudMantenimiento($solicitud_nit, $solicitud_nombre, $solicitud_otro, $solicitud_otro_detalle, $solicitud_fecha, $solicitud_email, $solicitud_ip, $accion_usuario, $accion_fecha, $token)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_mantenimiento(solicitud_nit, solicitud_nombre, solicitud_otro, solicitud_otro_detalle, solicitud_fecha, solicitud_email, solicitud_ip, accion_usuario, accion_fecha, solicitud_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($solicitud_nit, $solicitud_nombre, $solicitud_otro, $solicitud_otro_detalle, $solicitud_fecha, $solicitud_email, $solicitud_ip, $accion_usuario, $accion_fecha, $token));
            
            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }

    function Eliminar_SolicitudTarea($codigo_solicitud)
    {        
        try 
        {
            $sql = "DELETE FROM solicitud_tarea WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarSolicitudTarea($codigo_solicitud, $codigo_tarea, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO solicitud_tarea(solicitud_id, tarea_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($codigo_solicitud, $codigo_tarea, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AprobarSolicitudMantenimiento($nombre_usuario, $fecha_actual, $estructura_id)
    {
        try 
        {
            $sql = "UPDATE solicitud_mantenimiento SET solicitud_estado=1, accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaSolicitudVisita($tabla, $token, $codigo)
    {        
        try 
        {
            $sql = "SELECT solicitud_id, solicitud_confirmado, solicitud_fecha FROM $tabla WHERE solicitud_token=? AND solicitud_id=? LIMIT 1 "; 
            
            $consulta = $this->db->query($sql, array($token, $codigo));

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
    
    function UpdateSolicitudVisita($tabla, $accion_usuario, $accion_fecha, $codigo)
    {        
        try 
        {
            $sql = "UPDATE $tabla SET solicitud_confirmado=1, solicitud_token='', accion_usuario=?, accion_fecha=? WHERE solicitud_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarEmpresaPayStudio($codigo_ejecutivo, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO empresa (empresa_depende, empresa_consolidada, empresa_categoria, ejecutivo_id, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array(-1, 1, 1, $codigo_ejecutivo, $empresa_nit, $empresa_adquiriente, $empresa_tipo_sociedad, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Flujo de Trabajo
    
    function ObtenerDatosFlujo($codigo_etapa, $codigo_flujo)
    {        
        try 
        {
            if($codigo_etapa == -1)
            {
                $sql = "SELECT e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.etapa_categoria, e.etapa_color, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_categoria=? AND e.etapa_id>0 ORDER BY e.etapa_orden ASC ";
                $consulta = $this->db->query($sql, array($codigo_flujo));
            }
            else
            {
                $sql = "SELECT e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.etapa_categoria, e.etapa_color, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id=? AND e.etapa_categoria=? AND e.etapa_id>0 ORDER BY e.etapa_orden ASC ";
                $consulta = $this->db->query($sql, array($codigo_etapa, $codigo_flujo));
            }

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
    
    function ObteneParentFlujo($codigo_etapa, $codigo_flujo)
    {        
        try 
        {
            if($codigo_flujo == -1)
            {
                $sql = "SELECT e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id!=? ORDER BY e.etapa_id ASC "; 
            }
            else
            {
                $sql = "SELECT e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id!=? AND e.etapa_id>0 AND e.etapa_categoria=? ORDER BY e.etapa_id ASC "; 
            }
            
            $consulta = $this->db->query($sql, array($codigo_etapa, $codigo_flujo));

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
    
    function UpdateFlujo($codigo_parent, $etapa_nombre, $etapa_detalle, $etapa_tiempo, $notificar, $codigo_rol, $etapa_color, $nombre_usuario, $fecha_actual, $estructura_id)
    {        
        try 
        {
            $sql = "UPDATE etapa SET etapa_depende=?, etapa_nombre=?, etapa_detalle=?, etapa_tiempo=?, etapa_notificar_correo=?, etapa_rol=?, etapa_color=?, accion_usuario=?, accion_fecha=? WHERE etapa_id=? "; 
            
            $consulta = $this->db->query($sql, array($codigo_parent, $etapa_nombre, $etapa_detalle, $etapa_tiempo, $notificar, $codigo_rol, $etapa_color, $nombre_usuario, $fecha_actual, $estructura_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObteneRolHijoFlujo($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT e.etapa_id, e.etapa_rol, r.rol_nombre, e.etapa_notificar_correo FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_depende=? "; 

            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function VerificaEnvioEtapa($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT e.etapa_id, e.etapa_id, e.etapa_nombre, e.etapa_detalle, e.etapa_depende, e.etapa_tiempo, e.etapa_notificar_correo, e.etapa_rol as 'rol_codigo', r.rol_nombre, e.etapa_categoria, e.accion_usuario, e.accion_fecha FROM etapa e INNER JOIN rol r ON r.rol_id=e.etapa_rol WHERE e.etapa_id=? ORDER BY e.etapa_id ASC "; 

            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function ObtenerParentEtapa($codigo_etapa)
    {        
        try 
        {
            $sql = "SELECT etapa_depende FROM etapa WHERE etapa_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_etapa));

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
    
    function HitosProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT p.prospecto_id, e.etapa_id, e.etapa_nombre, h.hito_fecha_ini FROM hito h INNER JOIN etapa e ON e.etapa_id=h.etapa_id INNER JOIN prospecto p ON p.prospecto_id=h.prospecto_id WHERE p.prospecto_id=? ORDER BY e.etapa_id ASC ";
            $consulta = $this->db->query($sql, array($codigo_prospecto));

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
    
    // PROSPECTOS
    
    function RechazarProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_rechazado=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarObservacionDoc($prospecto_id, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO observacion_documento(prospecto_id, usuario_id, documento_id, obs_tipo, obs_fecha, obs_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $usuario_id, $documento_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObservarDocProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_consolidado=0, prospecto_observado_app=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
        
    function UpdateObservacionDoc($obs_estado, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE observacion_documento SET obs_estado=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($obs_estado, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertSeguimientoProspecto($prospecto_id, $etapa_id, $seguimiento_accion, $seguimiento_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO seguimiento(prospecto_id, etapa_id, seguimiento_accion, seguimiento_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $etapa_id, $seguimiento_accion, $seguimiento_detalle, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEtapaProspecto($prospecto_etapa, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_observado=0, prospecto_etapa=?, prospecto_etapa_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_etapa, $accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ListadoDetalleProspecto($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT camp.camp_id, camp.camp_nombre, camp.camp_plazo, camp.camp_monto_oferta, camp.camp_tasa, p.prospecto_id, p.ejecutivo_id, u.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', p.tipo_persona_id, p.empresa_id, p.prospecto_fecha_asignacion, p.prospecto_carpeta, p.prospecto_etapa, et.etapa_nombre, p.prospecto_etapa_fecha, p.prospecto_checkin, p.prospecto_checkin_fecha, p.prospecto_checkin_geo, p.prospecto_consolidar_fecha, p.prospecto_consolidar_geo, p.prospecto_consolidado, p.prospecto_observado_app, p.prospecto_estado_actual, c.cal_visita_ini, c.cal_visita_fin, h.hito_fecha_ini as 'fecha_derivada_etapa', p.prospecto_observado, p.prospecto_idc, p.prospecto_nombre_cliente, p.prospecto_empresa, p.prospecto_ingreso, p.prospecto_direccion, p.prospecto_direccion_geo, p.prospecto_telefono, p.prospecto_celular, p.prospecto_email, p.prospecto_tipo_lead, p.prospecto_matricula, p.prospecto_fecha_contacto1, p.prospecto_monto_aprobacion, p.prospecto_monto_desembolso, p.prospecto_fecha_desembolso, CASE p.prospecto_tipo_lead WHEN 1 then 'Asignado' WHEN 2 then 'Proactivo' END AS 'prospecto_tipo_lead', p.prospecto_llamada, p.prospecto_llamada_fecha, p.prospecto_llamada_geo, p.prospecto_comentario FROM prospecto p
                    INNER JOIN campana camp ON camp.camp_id=p.camp_id
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id= e.usuario_id
                    INNER JOIN empresa emp ON emp.empresa_id=p.empresa_id
                    INNER JOIN calendario c ON c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1
                    INNER JOIN etapa et ON et.etapa_id=prospecto_etapa
                    INNER JOIN hito h ON h.etapa_id=p.prospecto_etapa AND h.prospecto_id=p.prospecto_id
                    WHERE p.prospecto_id=? ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function ListadoBandejasProspecto($filtro)
    {        
        try 
        {
            $sql = "SELECT etapa.etapa_nombre, cam.camp_id, cam.camp_nombre, cam.camp_desc, cam.camp_plazo, cam.camp_monto_oferta, cam.camp_tasa, cam.camp_fecha_inicio, p.prospecto_id, p.prospecto_llamada, p.prospecto_llamada_fecha, p.prospecto_llamada_geo, p.prospecto_idc, p.prospecto_nombre_cliente, p.prospecto_empresa, p.prospecto_ingreso, p.prospecto_direccion, p.prospecto_direccion_geo, p.prospecto_telefono, p.prospecto_celular, p.prospecto_email, p.prospecto_tipo_lead, p.prospecto_matricula, p.prospecto_fecha_contacto1, p.prospecto_monto_aprobacion, p.prospecto_monto_desembolso, p.prospecto_fecha_desembolso, p.ejecutivo_id, u.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', p.tipo_persona_id, 
                    p.empresa_id, emp.empresa_categoria as 'empresa_categoria_codigo',
                    p.prospecto_fecha_asignacion, p.prospecto_carpeta, p.prospecto_etapa, p.prospecto_etapa_fecha, p.prospecto_checkin, p.prospecto_checkin_fecha, p.prospecto_checkin_geo, p.prospecto_consolidar_fecha, p.prospecto_consolidar_geo, p.prospecto_consolidado, p. prospecto_observado, p.prospecto_observado_app, p.prospecto_estado_actual, c.cal_visita_ini, c.cal_visita_fin, h.hito_fecha_ini as 'fecha_derivada_etapa' FROM prospecto p
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=p.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id= e.usuario_id
                    INNER JOIN empresa emp ON emp.empresa_id=p.empresa_id
                    INNER JOIN calendario c ON c.cal_id_visita=p.prospecto_id AND c.cal_tipo_visita=1
                    INNER JOIN hito h ON h.etapa_id=p.prospecto_etapa AND h.prospecto_id=p.prospecto_id
                    INNER JOIN campana cam ON cam.camp_id=p.camp_id
                    INNER JOIN etapa ON etapa.etapa_id=p.prospecto_etapa
                    WHERE " . $filtro;
            
            $consulta = $this->db->query($sql, array($filtro));
            
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
    
    function HistorialObservacionesDoc($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT o.obs_id, o.prospecto_id, o.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', o.documento_id, d.documento_nombre, o.obs_tipo, o.obs_fecha, o.obs_detalle, o.obs_estado, o.accion_usuario, o.accion_fecha 
                    FROM observacion_documento o
                    INNER JOIN usuarios u ON u.usuario_id=o.usuario_id
                    INNER JOIN documento d ON d.documento_id=o.documento_id
                    WHERE o.prospecto_id=? ORDER BY o.obs_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HistorialObservacionesProc($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT o.obs_id, o.prospecto_id, o.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', o.etapa_id, e.etapa_nombre, o.obs_tipo, o.obs_fecha, o.obs_detalle, o.obs_estado, o.accion_usuario, o.accion_fecha FROM observacion o
                    INNER JOIN usuarios u ON u.usuario_id=o.usuario_id
                    INNER JOIN etapa e ON e.etapa_id=o.etapa_id
                    WHERE o.prospecto_id=? ORDER BY o.obs_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HistorialExcepcion($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT ex.excepcion_detalle_id, ex.prospecto_id, ex.etapa_id, e.etapa_nombre, ex.excepcion_detalle, ex.usuario_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) AS 'usuario_nombre', ex.accion_usuario, ex.accion_fecha FROM excepcion_detalle ex
                    INNER JOIN usuarios u ON u.usuario_id=ex.usuario_id
                    INNER JOIN etapa e ON e.etapa_id=ex.etapa_id
                    WHERE ex.prospecto_id=? ORDER BY ex.excepcion_detalle_id ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HistorialSeguimiento($codigo_prospecto)
    {        
        try 
        {
            $sql = "SELECT seguimiento_id, s.prospecto_id, s.etapa_id, e.etapa_nombre, s.seguimiento_accion, s.seguimiento_detalle, s.accion_usuario, CONCAT_WS(' ', u.usuario_nombres, u.usuario_app, u.usuario_apm) as usuario_nombre, s.accion_fecha 
                    FROM seguimiento s
                    INNER JOIN etapa e ON e.etapa_id=s.etapa_id
                    INNER JOIN usuarios u ON u.usuario_user=s.accion_usuario
                    WHERE s.prospecto_id=? ORDER BY s.accion_fecha ASC ";
            
            $consulta = $this->db->query($sql, array($codigo_prospecto));
            
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
    
    function HitoProspecto($prospecto_id, $etapa_nueva, $etapa_actual, $accion_usuario, $accion_fecha)
    {        
        try 
        {   
            // Paso 1: Se consulta si existen registros con los criterios
            $sql1 = "SELECT hito_id FROM hito WHERE prospecto_id=? AND etapa_id=?";
            $consulta1 = $this->db->query($sql1, array($prospecto_id, $etapa_nueva));
            
            if ($consulta1->num_rows() == 0)
            {
                // Paso 2: Se inserta la etapa siguiente siempre y cuando no este registrada
                $sql = "INSERT INTO hito(prospecto_id, etapa_id, hito_fecha_ini, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?)";
                $consulta = $this->db->query($sql, array($prospecto_id, $etapa_nueva, $accion_fecha, $accion_usuario, $accion_fecha));
            }
            
            // Paso 3: Se actualiza la fecha de finalziación de la etapa actual
            $sql2 = "UPDATE hito SET hito_fecha_fin=?, hito_finalizo=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND etapa_id=? ";
            $consulta2 = $this->db->query($sql2, array($accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id, $etapa_actual));
            
            // Paso 4: Se actualiza la tabla Observación para que todas las observaciones del prospecto se marquen como "Solucionadas"
            $sql3 = "UPDATE observacion SET obs_estado=0, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? AND obs_estado=1 ";
            $consulta3 = $this->db->query($sql3, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function EvaluacionAntecedentesProspecto($prospecto_rev, $prospecto_rev_informe, $prospecto_rev_pep, $prospecto_rev_match, $prospecto_rev_infocred, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_estado_actual=3, prospecto_rev=?, prospecto_rev_fecha=?, prospecto_rev_informe=?, prospecto_rev_pep=?, prospecto_rev_match=?, prospecto_rev_infocred=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_rev, $accion_fecha, $prospecto_rev_informe, $prospecto_rev_pep, $prospecto_rev_match, $prospecto_rev_infocred, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaequisitosProspecto($misma_info, $cambio_poder, $cambio_banco, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_misma_inf=?, prospecto_cambia_poder=?, prospecto_reporte_bancario=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($misma_info, $cambio_poder, $cambio_banco, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RevisionCumplimientoProspecto($prospecto_vobo_cumplimiento, $prospecto_aux_cump, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_vobo_cumplimiento=?, prospecto_vobo_cumplimiento_fecha=?, prospecto_aux_cump=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_vobo_cumplimiento, $accion_fecha, $prospecto_aux_cump, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Formulario MATCH
    
    function ListaDatosForm_Match($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT form_id, prospecto_id, form_razon_social, form_direccion, form_departamento, form_nit, form_representante_legal, form_ci, form_rubro, form_observacion, accion_usuario, accion_fecha FROM form_cumplimiento_match WHERE prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function InsertarForm_Match($prospecto_id, $accion_fecha, $accion_usuario)
    {        
        try 
        {
            $sql = "INSERT INTO form_cumplimiento_match(prospecto_id, accion_usuario, accion_fecha) VALUES (?, ?, ?)"; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $accion_fecha, $accion_usuario));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateForm_Match($form_razon_social, $form_direccion, $form_departamento, $form_nit, $form_representante_legal, $form_ci, $form_rubro, $form_observacion, $accion_usuario, $accion_fecha, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE form_cumplimiento_match SET
                    form_razon_social=?, 
                    form_direccion=?, 
                    form_departamento=?, 
                    form_nit=?, 
                    form_representante_legal=?, 
                    form_ci=?, 
                    form_rubro=?, 
                    form_observacion=?, 
                    accion_usuario=?, 
                    accion_fecha=? 
                    WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($form_razon_social, $form_direccion, $form_departamento, $form_nit, $form_representante_legal, $form_ci, $form_rubro, $form_observacion, $accion_usuario, $accion_fecha, $codigo_prospecto));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Formulario Sociedad
    
    function ListaDatosForm_Sociedad($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT form_id, prospecto_id, form_razon_social, form_nit, form_matricula_comercio, form_direccion, form_departamento, form_departamento_deseable, form_mcc, form_mcc_deseable, form_rubro, form_flujo_estimado, form_cuenta_bob, form_cuenta_usd, form_titular_cuenta, form_ci, form_representante_legal, form_lista_accionistas, form_requisitos_afiliacion, form_requisitos_afiliacion_deseable, form_infocred_cuenta_endeuda_est, form_infocred_cuenta_endeuda_rep, form_infocred_calificacion_riesgos_est, form_infocred_calificacion_riesgos_rep, form_infocred_deseable, form_pep_categorizado_est, form_pep_categorizado_rep, form_pep_codigo_est, form_pep_codigo_rep, form_pep_cargo_est, form_pep_cargo_rep, form_pep_institucion_est, form_pep_institucion_rep, form_pep_gestion_est, form_pep_gestion_rep, form_pep_deseable, form_lista_confidenciales_est, form_lista_confidenciales_rep, form_lista_deseable, form_match_observado_est, form_match_observado_rep, form_match_observado_deseable, form_lista_negativa_est, form_lista_negativa_rep, form_lista_negativa_deseable, form_comentarios, form_comentarios_deseable, form_firma_entrega_nombre, form_firma_entrega_cargo, form_firma_entrega_fecha, accion_usuario, accion_fecha FROM form_cumplimiento_sociedad WHERE prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function InsertarForm_Sociedad($prospecto_id, $accion_fecha, $accion_usuario)
    {        
        try 
        {
            $sql = "INSERT INTO form_cumplimiento_sociedad(prospecto_id, accion_usuario, accion_fecha) VALUES (?, ?, ?)"; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $accion_fecha, $accion_usuario));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateForm_Sociedad($form_razon_social, $form_nit, $form_matricula_comercio, $form_direccion, $form_departamento, $form_departamento_deseable, $form_mcc, $form_mcc_deseable, $form_rubro, $form_flujo_estimado, $form_cuenta_bob, $form_cuenta_usd, $form_titular_cuenta, $form_ci, $form_representante_legal, $form_lista_accionistas, $form_requisitos_afiliacion, $form_requisitos_afiliacion_deseable, $form_infocred_cuenta_endeuda_est, $form_infocred_cuenta_endeuda_rep, $form_infocred_calificacion_riesgos_est, $form_infocred_calificacion_riesgos_rep, $form_infocred_deseable, $form_pep_categorizado_est, $form_pep_categorizado_rep, $form_pep_codigo_est, $form_pep_codigo_rep, $form_pep_cargo_est, $form_pep_cargo_rep, $form_pep_institucion_est, $form_pep_institucion_rep, $form_pep_gestion_est, $form_pep_gestion_rep, $form_pep_deseable, $form_lista_confidenciales_est, $form_lista_confidenciales_rep, $form_lista_deseable, $form_match_observado_est, $form_match_observado_rep, $form_match_observado_deseable, $form_lista_negativa_est, $form_lista_negativa_rep, $form_lista_negativa_deseable, $form_comentarios, $form_comentarios_deseable, $form_firma_entrega_nombre, $form_firma_entrega_cargo, $form_firma_entrega_fecha, $accion_usuario, $accion_fecha, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE form_cumplimiento_sociedad SET
                    form_razon_social=?, 
                    form_nit=?, 
                    form_matricula_comercio=?, 
                    form_direccion=?, 
                    form_departamento=?, 
                    form_departamento_deseable=?, 
                    form_mcc=?, 
                    form_mcc_deseable=?, 
                    form_rubro=?, 
                    form_flujo_estimado=?, 
                    form_cuenta_bob=?, 
                    form_cuenta_usd=?, 
                    form_titular_cuenta=?, 
                    form_ci=?, 
                    form_representante_legal=?, 
                    form_lista_accionistas=?, 
                    form_requisitos_afiliacion=?, 
                    form_requisitos_afiliacion_deseable=?, 
                    form_infocred_cuenta_endeuda_est=?, 
                    form_infocred_cuenta_endeuda_rep=?, 
                    form_infocred_calificacion_riesgos_est=?, 
                    form_infocred_calificacion_riesgos_rep=?, 
                    form_infocred_deseable=?, 
                    form_pep_categorizado_est=?, 
                    form_pep_categorizado_rep=?, 
                    form_pep_codigo_est=?, 
                    form_pep_codigo_rep=?, 
                    form_pep_cargo_est=?, 
                    form_pep_cargo_rep=?, 
                    form_pep_institucion_est=?, 
                    form_pep_institucion_rep=?, 
                    form_pep_gestion_est=?, 
                    form_pep_gestion_rep=?, 
                    form_pep_deseable=?, 
                    form_lista_confidenciales_est=?, 
                    form_lista_confidenciales_rep=?, 
                    form_lista_deseable=?, 
                    form_match_observado_est=?, 
                    form_match_observado_rep=?, 
                    form_match_observado_deseable=?, 
                    form_lista_negativa_est=?, 
                    form_lista_negativa_rep=?, 
                    form_lista_negativa_deseable=?, 
                    form_comentarios=?, 
                    form_comentarios_deseable=?, 
                    form_firma_entrega_nombre=?, 
                    form_firma_entrega_cargo=?, 
                    form_firma_entrega_fecha=?, 
                    accion_usuario=?, 
                    accion_fecha=?
                    WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($form_razon_social, $form_nit, $form_matricula_comercio, $form_direccion, $form_departamento, $form_departamento_deseable, $form_mcc, $form_mcc_deseable, $form_rubro, $form_flujo_estimado, $form_cuenta_bob, $form_cuenta_usd, $form_titular_cuenta, $form_ci, $form_representante_legal, $form_lista_accionistas, $form_requisitos_afiliacion, $form_requisitos_afiliacion_deseable, $form_infocred_cuenta_endeuda_est, $form_infocred_cuenta_endeuda_rep, $form_infocred_calificacion_riesgos_est, $form_infocred_calificacion_riesgos_rep, $form_infocred_deseable, $form_pep_categorizado_est, $form_pep_categorizado_rep, $form_pep_codigo_est, $form_pep_codigo_rep, $form_pep_cargo_est, $form_pep_cargo_rep, $form_pep_institucion_est, $form_pep_institucion_rep, $form_pep_gestion_est, $form_pep_gestion_rep, $form_pep_deseable, $form_lista_confidenciales_est, $form_lista_confidenciales_rep, $form_lista_deseable, $form_match_observado_est, $form_match_observado_rep, $form_match_observado_deseable, $form_lista_negativa_est, $form_lista_negativa_rep, $form_lista_negativa_deseable, $form_comentarios, $form_comentarios_deseable, $form_firma_entrega_nombre, $form_firma_entrega_cargo, $form_firma_entrega_fecha, $accion_usuario, $accion_fecha, $codigo_prospecto));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // -- Evaluación Legal
    
    function ListaDatosEvaluacion($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT e.evaluacion_legal_id, e.prospecto_id, e.usuario_id, e.evaluacion_denominacion_comercial, e.evaluacion_razon_social, e.evaluacion_legal_nit_doc, e.evaluacion_legal_nit_al_numero, e.evaluacion_legal_nit_al_representante, e.evaluacion_legal_idem, e.evaluacion_legal_cert_doc, e.evaluacion_legal_cert_al_principal, e.evaluacion_legal_cert_al_secundaria, e.evaluacion_legal_cert_al_idem, e.evaluacion_legal_ci_doc, e.evaluacion_legal_ci_al_pertenece, e.evaluacion_legal_ci_al_vigente, e.evaluacion_legal_ci_al_fecnac, e.evaluacion_legal_ci_al_nombre, e.evaluacion_legal_test_doc, e.evaluacion_legal_test_al_numero, e.evaluacion_legal_test_al_duracion, e.evaluacion_legal_test_al_fecha, e.evaluacion_legal_test_al_objeto, e.evaluacion_legal_poder_doc, e.evaluacion_legal_poder_al_fecha, e.evaluacion_legal_poder_al_numero, e.evaluacion_legal_poder_al_firma, e.evaluacion_legal_poder_al_facultades, e.evaluacion_legal_funde_doc, e.evaluacion_legal_funde_al_fecemi, e.evaluacion_legal_funde_al_fecvig, e.evaluacion_legal_funde_al_idem, e.evaluacion_legal_funde_al_representante, e.evaluacion_legal_funde_al_denominacion, e.evaluacion_legal_resultado, e.evaluacion_legal_conclusion, e.evaluacion_pertenece_regional, r.estructura_regional_nombre, e.evaluacion_legal_fecha_solicitud, e.evaluacion_legal_fecha_evaluacion, e.evaluacion_legal_revisadopor, e.evaluacion_legal_estado, e.accion_usuario, e.accion_fecha FROM evaluacion_legal e INNER JOIN estructura_regional r ON r.estructura_regional_id=e.evaluacion_pertenece_regional WHERE prospecto_id=? LIMIT 1 "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    function VerificaEvaluacionLegal($prospecto_id)
    {        
        try 
        {
            $sql = "SELECT prospecto_id FROM evaluacion_legal WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id));
            
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
    
    function InsertarEvaluacionLegal($prospecto_id, $usuario_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO evaluacion_legal(prospecto_id, usuario_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?)"; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $usuario_id, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEvaluacionLegal($evaluacion_denominacion_comercial, $evaluacion_razon_social, $evaluacion_doc_nit, $evaluacion_nit, $evaluacion_representante_legal, $evaluacion_razon_idem, $evaluacion_doc_certificado, $evaluacion_actividad_principal, $evaluacion_actividad_secundaria, $evaluacion_certificado_idem, $evaluacion_doc_ci, $evaluacion_ci_pertenece, $evaluacion_ci_vigente, $evaluacion_ci_fecnac, $evaluacion_ci_titular, $evaluacion_doc_test, $evaluacion_numero_testimonio, $evaluacion_duracion_empresa, $evaluacion_fecha_testimonio, $evaluacion_objeto_constitucion, $evaluacion_doc_poder, $evaluacion_fecha_testimonio_poder, $evaluacion_numero_testimonio_poder, $evaluacion_firma_conjunta, $evaluacion_facultad_representacion, $evaluacion_doc_funde, $evaluacion_fundaempresa_emision, $evaluacion_fundaempresa_vigencia, $evaluacion_idem_escritura, $evaluacion_idem_poder, $evaluacion_idem_general, $evaluacion_resultado, $opcion_conclusion, $evaluacion_pertenece_regional, $evaluacion_fecha_solicitud, $evaluacion_fecha_evaluacion, $nombre_usuario, $fecha_actual, $codigo_prospecto)
    {        
        try 
        {
            $sql = "UPDATE evaluacion_legal SET 
                    evaluacion_denominacion_comercial=?, 
                    evaluacion_razon_social=?, 
                    evaluacion_legal_nit_doc=?, 
                    evaluacion_legal_nit_al_numero=?, 
                    evaluacion_legal_nit_al_representante=?, 
                    evaluacion_legal_idem=?, 
                    evaluacion_legal_cert_doc=?, 
                    evaluacion_legal_cert_al_principal=?, 
                    evaluacion_legal_cert_al_secundaria=?, 
                    evaluacion_legal_cert_al_idem=?, 
                    evaluacion_legal_ci_doc=?, 
                    evaluacion_legal_ci_al_pertenece=?, 
                    evaluacion_legal_ci_al_vigente=?, 
                    evaluacion_legal_ci_al_fecnac=?, 
                    evaluacion_legal_ci_al_nombre=?, 
                    evaluacion_legal_test_doc=?, 
                    evaluacion_legal_test_al_numero=?, 
                    evaluacion_legal_test_al_duracion=?, 
                    evaluacion_legal_test_al_fecha=?, 
                    evaluacion_legal_test_al_objeto=?, 
                    evaluacion_legal_poder_doc=?, 
                    evaluacion_legal_poder_al_fecha=?, 
                    evaluacion_legal_poder_al_numero=?, 
                    evaluacion_legal_poder_al_firma=?, 
                    evaluacion_legal_poder_al_facultades=?, 
                    evaluacion_legal_funde_doc=?, 
                    evaluacion_legal_funde_al_fecemi=?, 
                    evaluacion_legal_funde_al_fecvig=?, 
                    evaluacion_legal_funde_al_idem=?, 
                    evaluacion_legal_funde_al_representante=?, 
                    evaluacion_legal_funde_al_denominacion=?, 
                    evaluacion_legal_resultado=?, 
                    evaluacion_legal_conclusion=?, 
                    evaluacion_pertenece_regional=?, 
                    evaluacion_legal_fecha_solicitud=?, 
                    evaluacion_legal_fecha_evaluacion=?, 
                    accion_usuario=?, 
                    accion_fecha=?
                    WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($evaluacion_denominacion_comercial, $evaluacion_razon_social, $evaluacion_doc_nit, $evaluacion_nit, $evaluacion_representante_legal, $evaluacion_razon_idem, $evaluacion_doc_certificado, $evaluacion_actividad_principal, $evaluacion_actividad_secundaria, $evaluacion_certificado_idem, $evaluacion_doc_ci, $evaluacion_ci_pertenece, $evaluacion_ci_vigente, $evaluacion_ci_fecnac, $evaluacion_ci_titular, $evaluacion_doc_test, $evaluacion_numero_testimonio, $evaluacion_duracion_empresa, $evaluacion_fecha_testimonio, $evaluacion_objeto_constitucion, $evaluacion_doc_poder, $evaluacion_fecha_testimonio_poder, $evaluacion_numero_testimonio_poder, $evaluacion_firma_conjunta, $evaluacion_facultad_representacion, $evaluacion_doc_funde, $evaluacion_fundaempresa_emision, $evaluacion_fundaempresa_vigencia, $evaluacion_idem_escritura, $evaluacion_idem_poder, $evaluacion_idem_general, $evaluacion_resultado, $opcion_conclusion, $evaluacion_pertenece_regional, $evaluacion_fecha_solicitud, $evaluacion_fecha_evaluacion, $nombre_usuario, $fecha_actual, $codigo_prospecto));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RevisionLegalProspecto($prospecto_vobo_legal, $prospecto_aux_legal, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_vobo_legal=?, prospecto_vobo_legal_fecha=?, prospecto_aux_legal=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_vobo_legal, $accion_fecha, $prospecto_aux_legal, $accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function GenerarExcepcionProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_excepcion=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AccionExcepcionProspecto($accion_excepcion, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_excepcion=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_excepcion, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AccionExcepcionProspectoPDF($accion_excepcion, $documento_pdf, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_excepcion=?, prospecto_excepcion_acta=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_excepcion, $documento_pdf, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function AuxiliarCumpLegal($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_aux_cump=?, prospecto_aux_legal=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($prospecto_aux_cump, $prospecto_aux_legal, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function GenerarObservacionProspecto($accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET prospecto_observado=1, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta = $this->db->query($sql, array($accion_usuario, $accion_fecha, $prospecto_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarExcepcionProspecto($prospecto_id, $etapa_id, $excepcion_detalle, $usuario_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO excepcion_detalle(prospecto_id, etapa_id, excepcion_detalle, usuario_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $etapa_id, $excepcion_detalle, $usuario_id, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarObservacionProspecto($prospecto_id, $usuario_id, $etapa_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO observacion(prospecto_id, usuario_id, etapa_id, obs_tipo, obs_fecha, obs_detalle, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($prospecto_id, $usuario_id, $etapa_id, $obs_tipo, $obs_fecha, $obs_detalle, $accion_usuario, $accion_fecha));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // Para Aprobar el Prospecto
    
    function AprobarProspecto($accion_usuario, $accion_fecha, $prospecto_id, $empresa_id)
    {        
        try 
        {
            // Paso 1: Se actualiza la tabla "prospecto"
            
            $sql1 = "UPDATE prospecto SET prospecto_estado_actual=4, prospecto_aceptado_afiliado=1, prospecto_aceptado_afiliado_fecha=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta1 = $this->db->query($sql1, array($accion_fecha, $accion_usuario, $accion_fecha, $prospecto_id));
            
            // Paso 2: Se actualiza la tabla "empresa" para marcar el registro como "Empresa Consolidada"
            
            $sql2 = "UPDATE empresa SET empresa_consolidada=1, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
            
            $consulta2 = $this->db->query($sql2, array($accion_usuario, $accion_fecha, $empresa_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ResultadoVerificacion($prospecto_resultado_verificacion, $accion_usuario, $accion_fecha, $prospecto_id, $empresa_id)
    {        
        try 
        {
            // Paso 1: Se actualiza la tabla "prospecto"
            
            $sql1 = "UPDATE prospecto SET prospecto_estado_actual=4, prospecto_resultado_verificacion=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
            
            $consulta1 = $this->db->query($sql1, array((int)$prospecto_resultado_verificacion, $accion_usuario, $accion_fecha, $prospecto_id));
            
            // Paso 2: Se actualiza la tabla "empresa" para marcar el registro como "Empresa Consolidada"
            
            $sql2 = "UPDATE empresa SET empresa_consolidada=1, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
            
            $consulta2 = $this->db->query($sql2, array($accion_usuario, $accion_fecha, $empresa_id));
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // MANTENIMIENTOS
    
    function ListadoDetalleMantenimientos($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "SELECT m.mant_id, m.ejecutivo_id, CONCAT(u.usuario_nombres, ' ', u.usuario_app, ' ', u.usuario_apm) as 'ejecutivo_nombre', m.empresa_id, emp.empresa_categoria as 'empresa_categoria_codigo',

                    CASE emp.empresa_categoria
                        WHEN 1 then emp.empresa_nombre_legal
                        WHEN 2 then emp.empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE emp.empresa_categoria
                        WHEN 1 then 'Comercio'
                        WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria',

                    m.mant_fecha_asignacion, m.mant_estado, m.mant_checkin, m.mant_checkin_fecha, m.mant_checkin_geo, m.mant_completado_fecha, m.mant_completado_geo, m.mant_documento_adjunto, m.mant_otro, m.mant_otro_detalle, m.accion_usuario, m.accion_fecha, c.cal_visita_ini, c.cal_visita_fin FROM mantenimiento m 
                    INNER JOIN ejecutivo e ON e.ejecutivo_id=m.ejecutivo_id
                    INNER JOIN usuarios u ON u.usuario_id=e.usuario_id
                    INNER JOIN empresa emp ON emp.empresa_id=m.empresa_id
                    INNER JOIN calendario c ON c.cal_id_visita=m.mant_id AND c.cal_tipo_visita=2
                    WHERE m.mant_id=? ";
            
            $consulta = $this->db->query($sql, array($codigo_mantenimiento));
            
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
    
    function ObtenerDetalleMantenimiento_tareas($codigo_mantenimiento)
    {        
        try 
        {
            $sql = "SELECT mt.mantenimiento_tarea_id, mt.mant_id, mt.tarea_id, t.tarea_detalle FROM mantenimiento_tarea mt
                    INNER JOIN tarea t ON t.tarea_id=mt.tarea_id
                    WHERE mt.mant_id=? AND t.tarea_activo=1 ";

            $consulta = $this->db->query($sql, array($codigo_mantenimiento));

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
    
    // Credenciales
    
    function ObtenerDatosConf_General()
    {        
        try 
        {
            $sql = "SELECT conf_general_id, conf_general_key_google, conf_horario_feriado, conf_horario_laboral, conf_atencion_desde1 as 'conf_atencion_desde', conf_atencion_hasta2 as 'conf_atencion_hasta', conf_atencion_dias, accion_usuario, accion_fecha, conf_atencion_desde1, conf_atencion_hasta1, conf_atencion_desde2, conf_atencion_hasta2 FROM conf_general WHERE conf_general_id=? "; 

            $consulta = $this->db->query($sql, array('conf-001'));

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
    
    function UpdateDatosConf_General($conf_general_key_google, $conf_horario_feriado, $conf_horario_laboral, $conf_atencion_desde1, $conf_atencion_hasta1, $conf_atencion_desde2, $conf_atencion_hasta2, $conf_atencion_dias, $fecha_actual, $nombre_usuario, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_general SET conf_general_key_google=?, conf_horario_feriado=?, conf_horario_laboral=?, conf_atencion_desde1=?, conf_atencion_hasta1=?, conf_atencion_desde2=?, conf_atencion_hasta2=?, conf_atencion_dias=?, accion_usuario=?, accion_fecha=? WHERE conf_general_id=? "; 

            $consulta = $this->db->query($sql, array($conf_general_key_google, $conf_horario_feriado, $conf_horario_laboral, $conf_atencion_desde1, $conf_atencion_hasta1, $conf_atencion_desde2, $conf_atencion_hasta2, $conf_atencion_dias, $fecha_actual, $nombre_usuario, $conf_credenciales_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosConf_Credenciales() 
    {        
        try 
        {
            $sql = "SELECT conf_id, conf_long_min, conf_long_max, conf_req_upper, conf_req_num, conf_req_esp, conf_duracion_min, conf_duracion_max, conf_tiempo_bloqueo, conf_defecto, conf_ejecutivo_ic, accion_fecha, accion_usuario FROM conf_credenciales WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array('conf-001'));

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
    
    function UpdateDatosConf_Credenciales($conf_credenciales_long_min, $conf_credenciales_long_max, $conf_credenciales_req_upper, $conf_credenciales_req_num, $conf_credenciales_req_esp, $conf_credenciales_duracion_min, $conf_credenciales_duracion_max, $conf_credenciales_tiempo_bloqueo, $conf_credenciales_defecto, $fecha_actual, $nombre_usuario, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_credenciales  SET conf_long_min = ?, conf_long_max = ?, conf_req_upper = ?, conf_req_num = ?, conf_req_esp = ?, conf_duracion_min = ?, conf_duracion_max = ?, conf_tiempo_bloqueo = ?, conf_defecto = ?, accion_fecha = ?, accion_usuario = ? WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_credenciales_long_min, $conf_credenciales_long_max, $conf_credenciales_req_upper, $conf_credenciales_req_num, $conf_credenciales_req_esp, $conf_credenciales_duracion_min, $conf_credenciales_duracion_max, $conf_credenciales_tiempo_bloqueo, $conf_credenciales_defecto, $fecha_actual, $nombre_usuario, $conf_credenciales_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateDatosConf_IC($conf_ejecutivo_ic, $fecha_actual, $nombre_usuario, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_credenciales  SET conf_ejecutivo_ic = ?, accion_fecha = ?, accion_usuario = ? WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_ejecutivo_ic, $fecha_actual, $nombre_usuario, $conf_credenciales_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosConf_Correo() 
    {        
        try 
        {
            $sql = "SELECT conf_id, conf_protocol, conf_smtp_host, conf_smtp_port, conf_smtp_user, conf_smtp_pass, conf_mailtype, conf_charset, accion_usuario, accion_fecha FROM conf_correo WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array('conf-001'));

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
    
    function UpdateDatosConf_Correo($conf_protocol, $conf_smtp_host, $conf_smtp_port, $conf_smtp_user, $conf_smtp_pass, $conf_mailtype, $conf_charset, $nombre_usuario, $fecha_actual, $conf_credenciales_id)
    {        
        try 
        {
            $sql = "UPDATE conf_correo SET conf_protocol = ?,conf_smtp_host = ?,conf_smtp_port = ?,conf_smtp_user = ?,conf_smtp_pass = ?,conf_mailtype = ?,conf_charset = ?,accion_usuario = ?,accion_fecha = ? WHERE conf_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_protocol, $conf_smtp_host, $conf_smtp_port, $conf_smtp_user, $conf_smtp_pass, $conf_mailtype, $conf_charset, $nombre_usuario, $fecha_actual, $conf_credenciales_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosConf_PlantillaCorreo($codigo_plantilla) 
    {        
        try 
        {
            if($codigo_plantilla != -1)
            {
                $sql = "SELECT conf_plantilla_id, conf_plantilla_nombre, conf_plantilla_titulo_correo, conf_plantilla_contenido, accion_usuario, accion_fecha FROM conf_correo_plantillas WHERE conf_plantilla_id = ? ORDER BY conf_plantilla_nombre "; 
            }
            else 
            {
                $sql = "SELECT conf_plantilla_id, conf_plantilla_nombre, conf_plantilla_titulo_correo, conf_plantilla_contenido, accion_usuario, accion_fecha FROM conf_correo_plantillas ORDER BY conf_plantilla_nombre "; 
            }            

            $consulta = $this->db->query($sql, array($codigo_plantilla));

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
    
    function UpdateDatosConf_PlantillaCorreo($conf_plantilla_nombre, $conf_plantilla_titulo_correo, $conf_plantilla_contenido, $accion_usuario, $accion_fecha, $codigo_plantilla)
    {        
        try 
        {
            $sql = "UPDATE conf_correo_plantillas SET conf_plantilla_nombre = ?,conf_plantilla_titulo_correo = ?,conf_plantilla_contenido = ?,accion_usuario = ?,accion_fecha = ? WHERE conf_plantilla_id = ? "; 

            $consulta = $this->db->query($sql, array($conf_plantilla_nombre, $conf_plantilla_titulo_correo, $conf_plantilla_contenido, $accion_usuario, $accion_fecha, $codigo_plantilla));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    // CATÁLOGO

    function ObtenerCatalogo($tipo, $parent_codigo, $parent_tipo)
    {        
        try
        {            
            $criterio = '';
            if($parent_codigo != '-1' && $parent_tipo != '-1')
            {
                $criterio = " AND catalogo_parent=(SELECT catalogo_id FROM catalogo WHERE catalogo_tipo_codigo='" . $parent_tipo . "' AND catalogo_codigo=" . $parent_codigo . ")";
            }
            
            if($tipo == "-1")
            {
                    $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha FROM catalogo WHERE catalogo_id>0 " . $criterio . " ORDER BY catalogo_tipo_codigo "; 
            }
            else
            {
                    $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha FROM catalogo WHERE catalogo_tipo_codigo = ? " . $criterio . "  ORDER BY catalogo_tipo_codigo "; 
            }

            $consulta = $this->db->query($sql, array($tipo, $parent_codigo));

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
	
    function ObtenerDatosCatalogo($catalogo_codigo)
    {        
        try
        {
            $sql = "SELECT catalogo_id, catalogo_parent, catalogo_tipo_codigo, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha FROM catalogo WHERE catalogo_id = ? "; 

            $consulta = $this->db->query($sql, array($catalogo_codigo));

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
    
    function VeriricaDatosCatalogo($catalogo_tipo_codigo, $catalogo_codigo)
    {        
        try
        {
            $sql = "SELECT catalogo_id FROM catalogo WHERE catalogo_tipo_codigo=? AND catalogo_codigo=? "; 

            $consulta = $this->db->query($sql, array($catalogo_tipo_codigo, $catalogo_codigo));

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
	
    function InsertarCatalogo($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $accion_usuario, $accion_fecha) 
    {        
        try 
        {
            $sql = "INSERT INTO catalogo(catalogo_tipo_codigo, catalogo_parent, catalogo_codigo, catalogo_descripcion, accion_usuario, accion_fecha) VALUES (?,?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $accion_usuario, $accion_fecha));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateCatalogo($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $accion_usuario, $accion_fecha, $catalogo_id)
    {        
        try 
        {
            $sql = "UPDATE catalogo SET catalogo_tipo_codigo = ?, catalogo_parent = ?, catalogo_codigo = ?, catalogo_descripcion = ?, accion_usuario = ?, accion_fecha = ? WHERE catalogo_id = ? "; 
			
            $consulta = $this->db->query($sql, array($catalogo_tipo_codigo, $catalogo_parent, $catalogo_codigo, $catalogo_descripcion, $accion_usuario, $accion_fecha, $catalogo_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function VerificaUsuario($usuario_user) 
    {        
        try 
        {
            $sql = "SELECT usuario_id, usuario_user, usuario_pass FROM usuarios WHERE usuario_user=?"; 

            $consulta = $this->db->query($sql, array($usuario_user));

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
	
    function VerificaPass($usuario_codigo, $usuario_pass) 
    {        
        try 
        {
            $sql = "SELECT usuario_id, usuario_user, usuario_pass FROM usuarios WHERE usuario_id=? AND usuario_pass=?"; 

            $consulta = $this->db->query($sql, array($usuario_codigo, $usuario_pass));

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
    
    function InsertarUsuario($usuario_user, $usuario_pass, $usuario_fecha_creacion, $usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $accion_fecha, $accion_usuario, $usuario_parent, $usuario_rol, $usuario_activo) 
    {        
        try 
        {
            $sql = "INSERT INTO usuarios (usuario_user, usuario_pass, usuario_fecha_creacion, usuario_nombres, usuario_app, usuario_apm, usuario_email, usuario_telefono, usuario_direccion, accion_fecha, accion_usuario, usuario_fecha_ultimo_acceso, usuario_fecha_ultimo_password, estructura_agencia_id, usuario_rol, usuario_password_reset, usuario_activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($usuario_user, $usuario_pass, $usuario_fecha_creacion, $usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $accion_fecha, $accion_usuario, '1500-01-01', $accion_fecha, $usuario_parent, $usuario_rol, 1, $usuario_activo));

            $listaResultados = $this->db->insert_id();
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    function UpdateUsuario($usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $fecha_actual, $nombre_usuario, $usuario_parent, $usuario_rol, $usuario_activo, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_nombres = ?, usuario_app = ?, usuario_apm = ?, usuario_email = ?, usuario_telefono = ?, usuario_direccion = ?, accion_fecha = ?, accion_usuario = ?, estructura_agencia_id = ?, usuario_rol = ?, usuario_activo = ? WHERE usuarios.usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_nombres, $usuario_app, $usuario_apm, $usuario_email, $usuario_telefono, $usuario_direccion, $fecha_actual, $nombre_usuario, $usuario_parent, $usuario_rol, $usuario_activo, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RenovarPass($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_pass = ?, usuario_fecha_ultimo_password = ?, usuario_password_reset = 0, accion_fecha = ?, accion_usuario = ? WHERE usuarios.usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_pass, $fecha_actual, $fecha_actual, $nombre_usuario, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function RestablecerPass($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo) 
    {        
        try 
        {
            $sql = "UPDATE usuarios SET usuario_pass = ?, usuario_password_reset = 1, accion_fecha = ?, accion_usuario = ? WHERE usuarios.usuario_id = ? "; 

            $consulta = $this->db->query($sql, array($usuario_pass, $fecha_actual, $nombre_usuario, $usuario_codigo));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerLista_Usuarios() 
    {        
        try 
        {
            $sql = "SELECT u.usuario_id, u.usuario_user, u.estructura_agencia_id, a.estructura_agencia_nombre, r.estructura_regional_nombre, u.usuario_pass, u.usuario_fecha_creacion, u.usuario_fecha_ultimo_acceso, u.usuario_fecha_ultimo_password, u.usuario_password_reset, u.usuario_nombres, u.usuario_app, u.usuario_apm, u.usuario_email, u.usuario_telefono, u.usuario_direccion, u.usuario_rol, u.usuario_activo FROM usuarios u INNER JOIN estructura_agencia a ON a.estructura_agencia_id=u.estructura_agencia_id INNER JOIN estructura_regional r ON a.estructura_regional_id=r.estructura_regional_id ORDER BY usuario_user "; 

            $consulta = $this->db->query($sql);

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
    
    function VerDocumentoObservado($codigo_prospecto, $codigo_documento)
    {        
        try 
        {
            $sql = "SELECT obs_id FROM observacion_documento WHERE prospecto_id=? AND documento_id=? AND obs_estado=1 "; 

            $consulta = $this->db->query($sql, array($codigo_prospecto, $codigo_documento));

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
    
    /*************** LECTOR QR - INICIO ****************************/
    
    function ObtenerThemeQR($codigo_agencia)
    {
        try 
        {
            $sql = "SELECT c.comercio_theme_id, c.estructura_agencia_id, c.background_color, c.color_primary, c.color_secundary, c.button_background_color, c.button_text_color, c.url_web_view, c.titulo, c.comprobante_diseno, a.estructura_agencia_nombre 
                    FROM comercio_theme c
                    INNER JOIN estructura_agencia a ON a.estructura_agencia_id=c.estructura_agencia_id
                    WHERE c.estructura_agencia_id=? "; 

            $consulta = $this->db->query($sql, array($codigo_agencia));

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
    
    function ListaCategoriaQR($codigo_agencia, $codigo_categoria)
    {        
        try 
        {
            $condicion = '';
            
            if($codigo_agencia != -1)
            {
                $condicion = 'AND estructura_agencia_id=' . $codigo_agencia;
            }
            
            if($codigo_categoria == -1)
            {
                $sql = "SELECT categoria_qr_id, estructura_agencia_id, categoria_qr_nombre, categoria_qr_tipo, categoria_qr_plantilla_ok, categoria_qr_plantilla_error, categoria_qr_plantilla_notfound FROM categoria_qr WHERE 1 $condicion "; 
            }
            else
            {
                $sql = "SELECT categoria_qr_id, estructura_agencia_id, categoria_qr_nombre, categoria_qr_tipo, categoria_qr_plantilla_ok, categoria_qr_plantilla_error, categoria_qr_plantilla_notfound FROM categoria_qr WHERE categoria_qr_id=? $condicion "; 
            }
            $consulta = $this->db->query($sql, array($codigo_categoria));

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
    
    function ListaRegistroQR($codigo_registro, $codigo_categoria)
    {        
        try 
        {
            
            $condicion = '';
            
            if($codigo_categoria != -1)
            {
                $condicion = 'AND categoria_qr_id=' . $codigo_categoria;
            }
            
            if($codigo_registro == -1)
            {
                $sql = "SELECT registro_qr_id, categoria_qr_id, registro_qr_codigo, registro_qr_detalle, registro_qr_checkin FROM registro_qr WHERE 1 $condicion "; 
            }
            else
            {
                $sql = "SELECT registro_qr_id, categoria_qr_id, registro_qr_codigo, registro_qr_detalle, registro_qr_checkin FROM registro_qr WHERE registro_qr_codigo=? $condicion "; 
            }
            $consulta = $this->db->query($sql, array($codigo_registro));

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
    
    function ActualizarRegistroQR($codigo_registro) 
    {        
        try 
        {
            $sql = "UPDATE registro_qr SET registro_qr_checkin = 1 WHERE registro_qr_codigo = ? "; 

            $consulta = $this->db->query($sql, array($codigo_registro));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertarRegistroQR($categoria_qr_id, $registro_qr_codigo, $registro_qr_detalle, $registro_qr_checkin) 
    {        
        try 
        {
            $sql = "INSERT INTO registro_qr(categoria_qr_id, registro_qr_codigo, registro_qr_detalle, registro_qr_checkin) VALUES (?, ?, ?, ?) "; 

            $consulta = $this->db->query($sql, array($categoria_qr_id, $registro_qr_codigo, $registro_qr_detalle, $registro_qr_checkin));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    
    /*************** LECTOR QR - FIN ****************************/
    
    // Control de Cambio 04/07/2018 - Nuevo Módulo GESTIÓN DE PROSPECTOS (DOCUMENTO) Y EMPRESA
    
    function ObtenerDatosEmpresa($codigo_empresa) 
    {        
        $criterio = '';
        
        if($codigo_empresa != -1)
        {
            $criterio = 'AND empresa_id= ' . $codigo_empresa;
        }
        
        try 
        {
            $sql = "SELECT empresa_id, ejecutivo_id,
                    CASE empresa_categoria
                            WHEN 1 then IF(STRCMP(empresa_nombre_fantasia, '') = 0, empresa_nombre_legal, empresa_nombre_fantasia) 
                            WHEN 2 then empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria',
                    empresa_consolidada, empresa_categoria AS 'empresa_categoria_codigo', empresa_depende, empresa_nit, empresa_adquiriente, empresa_tipo_sociedad, empresa_nombre_referencia, empresa_nombre_legal, empresa_nombre_fantasia, empresa_rubro, empresa_perfil_comercial, empresa_mcc, empresa_nombre_establecimiento, empresa_denominacion_corta, empresa_ha_desde, empresa_ha_hasta, empresa_dias_atencion, empresa_medio_contacto, empresa_email, empresa_dato_contacto, empresa_departamento, empresa_municipio, empresa_zona, empresa_tipo_calle, empresa_calle, empresa_numero, empresa_direccion_literal, empresa_direccion_geo, empresa_info_adicional, accion_usuario, accion_fecha FROM empresa WHERE empresa_id>0 AND empresa_consolidada>=0 $criterio "; 

            $consulta = $this->db->query($sql, array($codigo_empresa));

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
    
    function UpdateEmpresaComercio($ejecutivo_id, $empresa_nit, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id)
    {        
        try 
        {
            $sql = "UPDATE empresa SET ejecutivo_id=?, empresa_nit=?, empresa_tipo_sociedad=?, empresa_nombre_referencia=?, empresa_nombre_legal=?, empresa_nombre_fantasia=?, empresa_rubro=?, empresa_perfil_comercial=?, empresa_mcc=?, empresa_ha_desde=?, empresa_ha_hasta=?, empresa_dias_atencion=?, empresa_medio_contacto=?, empresa_email=?, empresa_dato_contacto=?, empresa_departamento=?, empresa_municipio=?, empresa_zona=?, empresa_tipo_calle=?, empresa_calle=?, empresa_numero=?, empresa_direccion_literal=?, empresa_info_adicional=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
			
            $consulta = $this->db->query($sql, array($ejecutivo_id, $empresa_nit, $empresa_tipo_sociedad, $empresa_nombre_referencia, $empresa_nombre_legal, $empresa_nombre_fantasia, $empresa_rubro, $empresa_perfil_comercial, $empresa_mcc, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEmpresaEstablecimiento($ejecutivo_id, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id)
    {        
        try 
        {
            $sql = "UPDATE empresa SET ejecutivo_id=?, empresa_nombre_establecimiento=?, empresa_denominacion_corta=?, empresa_ha_desde=?, empresa_ha_hasta=?, empresa_dias_atencion=?, empresa_medio_contacto=?, empresa_email=?, empresa_dato_contacto=?, empresa_departamento=?, empresa_municipio=?, empresa_zona=?, empresa_tipo_calle=?, empresa_calle=?, empresa_numero=?, empresa_direccion_literal=?, empresa_info_adicional=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
			
            $consulta = $this->db->query($sql, array($ejecutivo_id, $empresa_nombre_establecimiento, $empresa_denominacion_corta, $empresa_ha_desde, $empresa_ha_hasta, $empresa_dias_atencion, $empresa_medio_contacto, $empresa_email, $empresa_dato_contacto, $empresa_departamento, $empresa_municipio, $empresa_zona, $empresa_tipo_calle, $empresa_calle, $empresa_numero, $empresa_direccion_literal, $empresa_info_adicional, $accion_usuario, $accion_fecha, $empresa_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertEmpresa($empresa_nit, $ejecutivo_id, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO empresa (empresa_nit, ejecutivo_id, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?) ";
			
            $consulta = $this->db->query($sql, array($empresa_nit, $ejecutivo_id, $accion_usuario, $accion_fecha));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function UpdateEmpresaGeo($empresa_direccion_geo, $accion_usuario, $accion_fecha, $empresa_id)
    {        
        try 
        {
            $sql = "UPDATE empresa SET empresa_direccion_geo=?, accion_usuario=?, accion_fecha=? WHERE empresa_id=? "; 
			
            $consulta = $this->db->query($sql, array($empresa_direccion_geo, $accion_usuario, $accion_fecha, $empresa_id));
			
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function ObtenerDatosGeoEmpresa($lista) 
    {
        try 
        {
            $sql = "SELECT empresa_id,
                    CASE empresa_categoria
                            WHEN 1 then IF(STRCMP(empresa_nombre_fantasia, '') = 0, empresa_nombre_legal, empresa_nombre_fantasia) 
                            WHEN 2 then empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE empresa_categoria
                            WHEN 1 then 'Comercio'
                            WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria',
                    empresa_direccion_geo
                    FROM empresa WHERE empresa_id IN ($lista) "; 

            $consulta = $this->db->query($sql, array($lista));

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
    
    function ObtenerNombreEmpresaProspecto($prospecto_id) 
    {
        try 
        {
            $sql = "SELECT e.empresa_id,
                    CASE e.empresa_categoria
                                    WHEN 1 then IF(STRCMP(e.empresa_nombre_fantasia, '') = 0, e.empresa_nombre_legal, e.empresa_nombre_fantasia) 
                                    WHEN 2 then e.empresa_nombre_establecimiento
                    END AS 'empresa_nombre',
                    CASE e.empresa_categoria
                                    WHEN 1 then 'Comercio'
                                    WHEN 2 then 'Establecimiento'
                    END AS 'empresa_categoria'
                    FROM prospecto p 
                    INNER JOIN empresa e ON e.empresa_id=p.empresa_id
                    WHERE p.prospecto_id=? "; 

            $consulta = $this->db->query($sql, array($prospecto_id));

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
    
    /* ********************************** */

    // FORMULARIOS DINÁMICOS
    
    function UpdateTipoPersonaProspecto($empresa_id, $empresa_geo, $tipo_persona_id, $prospecto_acciones, $prospecto_observaciones, $prospecto_reverificacion, $prospecto_refecha, $prospecto_firma, $accion_usuario, $accion_fecha, $prospecto_id)
    {        
        try 
        {
            $sql = "UPDATE prospecto SET tipo_persona_id=?, prospecto_acciones=?, prospecto_observaciones=?, prospecto_reverificacion=?, prospecto_refecha=?, prospecto_firma=?, accion_usuario=?, accion_fecha=? WHERE prospecto_id=? "; 
			
            $consulta = $this->db->query($sql, array($tipo_persona_id, $prospecto_acciones, $prospecto_observaciones, $prospecto_reverificacion, $prospecto_refecha, $prospecto_firma, $accion_usuario, $accion_fecha, $prospecto_id));

            $sql2 = "UPDATE empresa SET empresa_direccion_geo=? WHERE empresa_id=? "; 
			
            $consulta2 = $this->db->query($sql2, array($empresa_geo, $empresa_id));
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    /* ********************************** */
    
    // Tipos de Campana
    
    function ObtenerTipoCampana($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT camtip_id, camtip_nombre, camtip_desc, accion_usuario, accion_fecha FROM campana_tipo "; 
            }
            else
            {
                $sql = "SELECT camtip_id, camtip_nombre, camtip_desc, accion_usuario, accion_fecha FROM campana_tipo WHERE camtip_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    // Campañas
    
    function ObtenerCampana($codigo)
    {        
        try 
        {
            if($codigo == -1)
            {
                $sql = "SELECT c.camp_fecha_inicio, c.camp_id, c.camtip_id, ct.camtip_nombre, c.camp_nombre, c.camp_desc, c.camp_plazo, c.camp_monto_oferta, c.camp_tasa, c.accion_usuario, c.accion_fecha FROM campana c INNER JOIN campana_tipo ct ON ct.camtip_id=c.camtip_id "; 
            }
            else
            {
                $sql = "SELECT c.camp_fecha_inicio, c.camp_id, c.camtip_id, ct.camtip_nombre, c.camp_nombre, c.camp_desc, c.camp_plazo, c.camp_monto_oferta, c.camp_tasa, c.accion_usuario, c.accion_fecha FROM campana c INNER JOIN campana_tipo ct ON ct.camtip_id=c.camtip_id WHERE c.camp_id=? "; 
            }            

            $consulta = $this->db->query($sql, array($codigo));

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
    
    function UpdateCampana($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $accion_usuario, $accion_fecha, $camp_id)
    {        
        try 
        {
            $sql = "UPDATE campana SET camtip_id=?, camp_nombre=?, camp_desc=?, camp_plazo=?, camp_monto_oferta=?, camp_tasa=?, camp_fecha_inicio=?, accion_usuario=?, accion_fecha=? WHERE camp_id=? "; 
            
            $consulta = $this->db->query($sql, array($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $accion_usuario, $accion_fecha, $camp_id));

        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
    }
    
    function InsertCampana($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $accion_usuario, $accion_fecha)
    {        
        try 
        {
            $sql = "INSERT INTO campana(camtip_id, camp_nombre, camp_desc, camp_plazo, camp_monto_oferta, camp_tasa, camp_fecha_inicio, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) "; 
            
            $consulta = $this->db->query($sql, array($camtip_id, $camp_nombre, $camp_desc, $camp_plazo, $camp_monto_oferta, $camp_tasa, $camp_fecha_inicio, $accion_usuario, $accion_fecha));
            
            $listaResultados = $this->db->insert_id();
        }
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }
        
        return $listaResultados;
    }
    
    // -- Campañas de un Agente
    
    function ObtenerCampanaAgente($codigo_agente)
    {        
        try 
        {
            $sql = "SELECT camp_id FROM prospecto WHERE ejecutivo_id=? GROUP BY camp_id "; 

            $consulta = $this->db->query($sql, array($codigo_agente));

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
    
    // Verificar si existe un valor dado en una tabla dada
    
    function VerificaTablaCampo($tabla, $campo, $valor)
    {        
        try 
        {
            $sql = "SELECT $campo FROM $tabla WHERE $campo=? ";
            
            $consulta = $this->db->query($sql, array($valor));

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
    
    function ObtenerCodigoEtapaNombre($nombre_etapa)
    {        
        try 
        {
            $sql = "SELECT etapa_id, etapa_nombre FROM etapa WHERE UPPER(REPLACE(etapa_nombre, ' ', ''))=UPPER(REPLACE(?, ' ', '')) ";
            
            $consulta = $this->db->query($sql, array($nombre_etapa));

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
    
    function ObtenerCodigoCampanaNombre($nombre_campana)
    {        
        try 
        {
            $sql = "SELECT camp_id, camp_nombre FROM campana WHERE UPPER(REPLACE(camp_nombre, ' ', ''))=UPPER(REPLACE(?, ' ', '')) ";
            
            $consulta = $this->db->query($sql, array($nombre_campana));

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
    
    function ObtenerCodigoEjecutivoUsuario($matricula)
    {        
        try 
        {
            $sql = "SELECT e.ejecutivo_id, u.usuario_id FROM ejecutivo e INNER JOIN usuarios u ON u.usuario_id=e.usuario_id WHERE u.usuario_user=? ";
            
            $consulta = $this->db->query($sql, array($matricula));

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
    
    function InsertarLead_APP($prospecto_codigo_cliente, $prospecto_mensaje, $ejecutivo_id, $tipo_persona_id, $empresa_id, $camp_id, $prospecto_fecha_asignacion, $prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_tipo_lead, $prospecto_matricula, $prospecto_comentario, $nombre_usuario, $fecha_actual)
    {        
        try 
        {            
            $sql = "INSERT INTO prospecto(prospecto_codigo_cliente, prospecto_mensaje, ejecutivo_id, tipo_persona_id, empresa_id, camp_id, prospecto_fecha_asignacion, prospecto_idc, prospecto_nombre_cliente, prospecto_empresa, prospecto_ingreso, prospecto_direccion, prospecto_direccion_geo, prospecto_telefono, prospecto_celular, prospecto_email, prospecto_tipo_lead, prospecto_matricula, prospecto_comentario, accion_usuario, accion_fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $this->db->query($sql, array($prospecto_codigo_cliente, $prospecto_mensaje, $ejecutivo_id, $tipo_persona_id, $empresa_id, $camp_id, $prospecto_fecha_asignacion, $prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_tipo_lead, $prospecto_matricula, $prospecto_comentario, $nombre_usuario, $fecha_actual));

            $listaResultados = $this->db->insert_id();
            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

            return $listaResultados;
    }
    
    function UpdateLead_APP($prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_fecha_contacto1, $prospecto_comentario, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET prospecto_idc = ?, prospecto_nombre_cliente = ?, prospecto_empresa = ?, prospecto_ingreso = ?, prospecto_direccion = ?, prospecto_direccion_geo = ?, prospecto_telefono = ?, prospecto_celular = ?, prospecto_email = ?, prospecto_fecha_contacto1 = ?, prospecto_comentario = ?, accion_usuario = ?, accion_fecha = ? WHERE prospecto_id = ?";

            $this->db->query($sql, array($prospecto_idc, $prospecto_nombre_cliente, $prospecto_empresa, $prospecto_ingreso, $prospecto_direccion, $prospecto_direccion_geo, $prospecto_telefono, $prospecto_celular, $prospecto_email, $prospecto_fecha_contacto1, $prospecto_comentario, $accion_usuario, $accion_fecha, $codigo_registro));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
    function UpdateEstadoLead_APP($prospecto_etapa, $prospecto_monto_aprobacion, $prospecto_monto_desembolso, $prospecto_fecha_desembolso, $accion_usuario, $accion_fecha, $codigo_registro)
    {        
        try 
        {            
            $sql = "UPDATE prospecto SET prospecto_etapa = ?, prospecto_monto_aprobacion = ?, prospecto_monto_desembolso = ?, prospecto_fecha_desembolso = ?, accion_usuario = ?, accion_fecha = ? WHERE prospecto_id = ?";

            $this->db->query($sql, array($prospecto_etapa, $prospecto_monto_aprobacion, $prospecto_monto_desembolso, $prospecto_fecha_desembolso, $accion_usuario, $accion_fecha, $codigo_registro));

            
        } 
        catch (Exception $e) 
        {
            js_error_div_javascript($e . "<span style='font-size:3.5mm;'>
                Ocurrio un evento inesperado, intentelo mas tarde.</span>");
            exit();
        }

    }
    
}

?>