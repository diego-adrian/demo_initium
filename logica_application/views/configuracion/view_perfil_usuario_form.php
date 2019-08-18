<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Perfil/Usuario/Guardar',
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
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('PerfilUsuarioTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('PerfilUsuarioFormSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

                                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

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

                </table>

                <br />

            <?php
            
            }
                
            ?>
                
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_perfil'); ?>
                    </td>

                    <td style="width: 70%;">                        
                        
                        <br />
                        
                        <?php
                                                
                            if(isset($arrPerfiles[0]))
                            {
                                $i = 0;
                                $checked = '';
                                
                                foreach ($arrPerfiles as $key => $value) 
                                {
                                    $checked = '';
                                    if($value["perfil_asignado"])
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    
                                    echo '<span class="AyudaTooltip" data-balloon-length="medium" data-balloon="' . $value["perfil_descripcion"] . '" data-balloon-pos="right"> </span>';
                                    echo '<input id="perfil' . $i , '" type="checkbox" name="perfil_list[]" '. $checked .' value="' . $value["perfil_id"] . '">';
                                    echo '<label for="perfil' . $i , '"><span></span>' . $value["perfil_nombre"] . '</label>';
                                    echo '<br /><br />';
                                    
                                    $i++;
                                }
                            }
                            
                        ?>
                        
                    </td>

                </tr>
                
            </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Perfil/Usuario/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
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