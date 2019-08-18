<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Conf/General/Guardar',
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

    var startTimeTextBox1 = $('#conf_atencion_desde1');
    var endTimeTextBox1 = $('#conf_atencion_hasta1');

    $.timepicker.timeRange(
        startTimeTextBox1,
        endTimeTextBox1,
        {
            minInterval: (1000*60*60), // 1hr
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );
    
    var startTimeTextBox3 = $('#conf_atencion_hasta1');
    var endTimeTextBox3 = $('#conf_atencion_desde2');

    $.timepicker.timeRange(
        startTimeTextBox3,
        endTimeTextBox3,
        {
            minInterval: (1000*60*60), // 1hr
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );
    
    var startTimeTextBox2 = $('#conf_atencion_desde2');
    var endTimeTextBox2 = $('#conf_atencion_hasta2');

    $.timepicker.timeRange(
        startTimeTextBox2,
        endTimeTextBox2,
        {
            minInterval: (1000*60*60), // 1hr
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );
    
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('conf_general_Titulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('conf_general_Subtitulo'); ?></div>
                
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

                                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="Campana/Ver" />

            <input type="hidden" name="conf_general_id" value="<?php if(isset($arrRespuesta[0]["conf_general_id"])){ echo $arrRespuesta[0]["conf_general_id"]; } ?>" />

            
        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_general_key_google'); ?>
                    
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="La Key de Google tiene que estar habilitada para Mapas y Calendario" data-balloon-pos="right"> </span>
                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_general_key_google"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_horario_feriado'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Al habilitar esta opción, se mostrarán los días festivos en el calendario" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <input id="feriado1" name="conf_horario_feriado" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_feriado"]==0) echo "checked='checked'"; ?> value="0" />
                    <label for="feriado1" class=""><span></span><?php echo $this->lang->line('Catalogo_no'); ?></label>

                    &nbsp;&nbsp;

                    <input id="feriado2" name="conf_horario_feriado" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_feriado"]==1) echo "checked='checked'"; ?> value="1" />
                    <label for="feriado2" class=""><span></span><?php echo $this->lang->line('Catalogo_si'); ?></label>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_horario_laboral'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Si esta opción no esta habilitada, el calendario aceptará horarios de todo el día" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <input id="activo1" name="conf_horario_laboral" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_laboral"]==0) echo "checked='checked'"; ?> value="0" />
                    <label for="activo1" class=""><span></span><?php echo $this->lang->line('Catalogo_no'); ?></label>

                    &nbsp;&nbsp;

                    <input id="activo2" name="conf_horario_laboral" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_laboral"]==1) echo "checked='checked'"; ?> value="1" />
                    <label for="activo2" class=""><span></span><?php echo $this->lang->line('Catalogo_si'); ?></label>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_atencion_desde1'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Si requiere establecer sólo un turno, puede igualar el segundo periodo, por ejemplo: 08:30 a 12:30 y 12:30 a 18:30 " data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_atencion_desde1"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_atencion_hasta1'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_atencion_hasta1"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_atencion_desde2'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_atencion_desde2"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_atencion_hasta2'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_atencion_hasta2"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_atencion_dias'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Sólo se aceptarán horarios en los días seleccionados" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    
                    <?php $seleccion = ''; if (in_array("1", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                    <input id="d1" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="1">
                    <label for="d1"><span></span>Lunes</label>
                    
                    <br /><br />
                                        
                    <?php $seleccion = ''; if (in_array("2", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                    <input id="d2" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="2">
                    <label for="d2"><span></span>Martes</label>
                    
                    <br /><br />
                                        
                    <?php $seleccion = ''; if (in_array("3", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                    <input id="d3" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="3">
                    <label for="d3"><span></span>Miércoles</label>
                    
                    <br /><br />
                                        
                    <?php $seleccion = ''; if (in_array("4", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                    <input id="d4" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="4">
                    <label for="d4"><span></span>Jueves</label>
                    
                    <br /><br />
                                        
                    <?php $seleccion = ''; if (in_array("5", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                    <input id="d5" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="5">
                    <label for="d5"><span></span>Viernes</label>
                    
                    <br /><br />
                                        
                    <?php $seleccion = ''; if (in_array("6", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                    <input id="d6" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="6">
                    <label for="d6"><span></span>Sábado</label>
                    
                    <br /><br />
                                        
                    <?php $seleccion = ''; if (in_array("0", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                    <input id="d7" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="0">
                    <label for="d7"><span></span>Domingo</label>
                    
                </td>

            </tr>

        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Menu/Principal');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
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