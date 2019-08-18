<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Usuario/Cambiar/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');
    
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('PassTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('PassSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

                                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php $strClase = "FilaBlanca"; ?>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_pass1'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["password_anterior"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_pass2'); ?>
                        <span class="AyudaTooltip" data-balloon-length="large" data-balloon='<?php echo str_replace("<br />","&#10;",$this->mfunciones_generales->RequisitosFortalezaPassword()); ?>' data-balloon-pos="right" data-balloon-break> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["password_nuevo"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_pass3'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["password_repetir"]; ?>
                    </td>

                </tr>
                
            </table>

        </form>    

        <br />

        <?php if (isset($respuesta)) { ?>
            <div class="mensajeBD"> 
                <div style="padding: 15px;">
                    <?php echo $respuesta ?>
                </div>
            </div>
        <?php } ?>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>

        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Menu/Principal');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a class="BotonMinimalista" id="btnGuardarDatosLista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
</div>