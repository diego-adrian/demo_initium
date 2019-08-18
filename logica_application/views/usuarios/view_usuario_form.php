<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Usuario/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('UsuarioTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('UsuarioSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

                                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="Campana/Ver" />

            <input type="hidden" name="usuario_codigo" value="<?php if(isset($arrRespuesta[0]["usuario_codigo"])){ echo $arrRespuesta[0]["usuario_codigo"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />

            <?php
            
            if($tipo_accion == 1)
            {
                
            ?>
            
                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('Usuario_user'); ?>
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["usuario_user"]; ?>
                        </td>
                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('Usuario_fecha_creacion'); ?>
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["usuario_fecha_creacion"]; ?>
                        </td>
                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('Usuario_fecha_acceso'); ?>
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["usuario_fecha_ultimo_acceso"]; ?>
                        </td>
                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">
                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('Usuario_fecha_password'); ?>
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["usuario_fecha_ultimo_password"]; ?>
                        </td>
                    </tr>

                </table>

                <br />

            <?php
            
            }
                
            ?>
                
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php
            
                if($tipo_accion != 1)
                {

                ?>
                
                    <?php $strClase = "FilaGris"; ?>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('Usuario_user'); ?>
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrCajasHTML["usuario_user"]; ?>
                        </td>

                    </tr>
                
                <?php            
                }
                else
                {
                ?>
                    <?php $strClase = "FilaBlanca"; ?>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('Usuario_activo'); ?>
                            <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_usuario_activo'); ?>' data-balloon-pos="right"> </span>
                        </td>

                        <td style="width: 70%;">                
                            <input id="usuario_activo1" name="usuario_activo" type="radio" class="" <?php if($arrRespuesta[0]["usuario_activo"]==0) echo "checked='checked'"; ?> value="0" />
                            <label for="usuario_activo1" class=""><span></span><?php echo $this->lang->line('Catalogo_activo1'); ?></label>

                            &nbsp;&nbsp;
                            
                            <input id="usuario_activo2" name="usuario_activo" type="radio" class="" <?php if($arrRespuesta[0]["usuario_activo"]==1) echo "checked='checked'"; ?> value="1" />
                            <label for="usuario_activo2" class=""><span></span><?php echo $this->lang->line('Catalogo_activo2'); ?></label>
                        </td>

                    </tr>
                <?php
                }
                ?>
                
                <?php $strClase = "FilaBlanca"; ?>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_nombre'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["usuario_nombres"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_app'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["usuario_app"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_apm'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["usuario_apm"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_email'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["usuario_email"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_telefono'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["usuario_telefono"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_direccion'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["usuario_direccion"]; ?>
                    </td>

                </tr>
                
            </table>
            
            <br />
                
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('estructura_parent'); ?> <?php echo $this->lang->line('estructura_agencia'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php
                        
                            $codigo_agencia = "";
                        
                            if(isset($arrRespuesta[0]["estructura_agencia_id"]))
                            { 
                                $codigo_agencia = $arrRespuesta[0]["estructura_agencia_id"];
                            }
                        
                            if(isset($arrAgencia[0]))
                            {
                                echo html_select('usuario_parent', $arrAgencia, 'estructura_agencia_id', 'estructura_agencia_nombre', '', $codigo_agencia);
                            }
                            
                        ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_rol'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php
                        
                            $codigo_rol = "";
                        
                            if(isset($arrRespuesta[0]["usuario_codigo"]))
                            { 
                                $codigo_rol = $arrRespuesta[0]["usuario_rol"];
                            }
                        
                            if(isset($arrRoles[0]))
                            {
                                echo html_select('usuario_rol', $arrRoles, 'rol_id', 'rol_nombre', '', $codigo_rol);
                            }
                            
                        ?>
                    </td>

                </tr>
                
            </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Usuario/Listar');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('UsuarioPregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
		
		<br />

        <?php if (isset($respuesta)) { ?>
            <div class="mensajeBD"> 
                <div style="padding: 15px;">
                    <?php echo $respuesta ?>
                </div>
            </div>
        <?php } ?>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>
		
    </div>
</div>