<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Conf/Credenciales/Guardar',
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
    
    $("#conf_credenciales_defecto").attr("type", "password");
    
    function MostrarOcultarPass()
    {
        if ($("#conf_credenciales_defecto").attr("type") == "password") {
            $("#conf_credenciales_defecto").attr("type", "text");
        } else {
            $("#conf_credenciales_defecto").attr("type", "password");
        }
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('conf_credenciales_Titulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('conf_credenciales_Subtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

                                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="Campana/Ver" />

            <input type="hidden" name="conf_credenciales_id" value="<?php if(isset($arrRespuesta[0]["conf_credenciales_id"])){ echo $arrRespuesta[0]["conf_credenciales_id"]; } ?>" />

            
        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_long_min'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_long_min"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_long_max'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_long_max"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_req_upper'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_req_upper"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_req_num'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_req_num"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_req_esp'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_req_esp"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_duracion_min'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_duracion_min"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_duracion_max'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_duracion_max"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_tiempo_bloqueo'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_tiempo_bloqueo"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_credenciales_defecto'); ?>
                    
                    <span class="AyudaTooltip" data-balloon-length="large" data-balloon='<?php echo str_replace("<br />","&#10;",$this->mfunciones_generales->RequisitosFortalezaPassword()); ?>' data-balloon-pos="right" data-balloon-break> </span>
                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_credenciales_defecto"]; ?>
                    <a onclick="MostrarOcultarPass();"> <strong><?php echo $this->lang->line('MostrarOcultar'); ?> </strong></a>
                </td>

            </tr>

        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Conf/Credenciales/Menu');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('conf_credenciales_Pregunta'); ?></div>
        
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