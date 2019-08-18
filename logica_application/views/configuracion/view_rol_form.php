<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Rol/Guardar',
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
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('RolTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('RolSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["estructura_codigo"])){ echo $arrRespuesta[0]["estructura_codigo"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />

        <?php
        
        $display = '';
        
        if(isset($arrRespuesta[0]) && $arrRespuesta[0]["estructura_codigo"]<2)
        {
            $display = ' display: none;';
            
            echo '
                <table class="tablaresultados Mayuscula" style="width: 100% !important;<?php echo $display; ?>" border="0">
                    <tr class="FilaBlanca">
                        <td style="width: 30%; font-weight: bold;">
                            ' . $this->lang->line('estructura_nombre') . '
                        </td>
                        <td style="width: 70%; font-weight: bold;">
                            ' . $arrRespuesta[0]['estructura_nombre'] . '
                        </td>
                    </tr>
                </table>
                ';
        }
        
        ?>
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;<?php echo $display; ?>" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_nombre'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_nombre"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_detalle'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["estructura_detalle"]; ?>
                </td>

            </tr>
        </table>
            
        <br />
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('estructura_menu'); ?>
                    </td>

                    <td style="width: 70%;">                        
                        
                        <br />
                        
                        <?php
                        
                            if(isset($arrMenu[0]))
                            {
                                $i = 0;
                                $checked = '';
                                
                                foreach ($arrMenu as $key => $value) 
                                {
                                    $checked = '';
                                    if($value["menu_asignado"])
                                    {
                                        $checked = 'checked="checked"';
                                    }
                                    
                                    echo '<span class="AyudaTooltip" data-balloon-length="medium" data-balloon="' . $value["menu_descripcion"] . '" data-balloon-pos="right"> </span>';
                                    echo '<input id="menu' . $i , '" type="checkbox" name="menu_list[]" '. $checked .' value="' . $value["menu_id"] . '">';
                                    echo '<label for="menu' . $i , '"><span></span>' . $value["menu_nombre"] . '</label>';
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
            <a onclick="Ajax_CargarOpcionMenu('Rol/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('estructura_Pregunta'); ?></div>
        
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