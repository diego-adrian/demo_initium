<script type="text/javascript">    
    $('#divContenidoElementoFlotante').attr("style", "height:auto;");         
    $('#password').keypress(function (e) {        
        if (e.which == 13) {            
            $('#FormTarjetaActivaAcceso').submit();
            e.preventDefault();
        }
    });    
    <?php echo $strValidacionJqValidate; ?>  
    Elementos_Habilitar_ObjetoARefComoSubmit("linkIngresarLogin", "FormTarjetaActivaAcceso");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormTarjetaActivaAcceso", 'Login/Autenticacion',
            '', 'divErrorRespuestLogin', 'SIN_CARGA', '', Elementos_Carga_Interna_Menus);

</script>


<form id="FormTarjetaActivaAcceso" method="post">       
    <div>        
        <table style='width: 100%;' class="Centrado">
            <tr>
                <td colspan="2">
                    <div class="FormularioSubtitulo">
                        <?php echo $this->lang->line('TituloLogin');?>                        
                    </div>
                    
                    <div class="FormularioSubtituloComentarioNormal">  
                        <?php echo $this->lang->line('SubTituloLogin'); ?>
                    </div>
                    
                    <div style="clear: both;"> </div>
                                        
                    <div id="divErrorRespuestLogin" class="mensajeBD"></div>                   
                    
                </td>
            </tr>    
            <tr>                                   
                <td colspan="2">
                    <div class="FormularioTituloCajas" >
                        <?php echo $this->lang->line('TituloCajaLogin'); ?>
                    </div>
                    <?php echo $arrCajasHTML["cuenta"]; ?>                         
                </td>

            </tr> 
            <tr>                                   
                <td colspan="3">
                    <div class="FormularioTituloCajas" >
                        <?php echo $this->lang->line('TituloCajaPassword'); ?>
                    </div>
                    <?php echo $arrCajasHTML["password"]; ?>                         
                </td>

            </tr>
            
            <tr>                                   
                <td colspan="2">
                    
                    <div class="FormularioTituloCajas" >
                        <?php echo $this->lang->line('TituloCajaCaptcha'); ?>
                    </div>
                    <?php echo $arrCajasHTML["imagen"]; ?>
                    
                </td>
            </tr>
            
            <tr>    
                <td valign="middle" align="center">
                    <span id="imgCaptchaLogin"><?php echo $imgTagHtml; ?></span>
                </td>
                
                <td valign="middle" align="left">
                    
                    <span class="AyudaTooltip" data-balloon-length="large" data-balloon='<?php echo $this->lang->line('Ayuda_captcha'); ?>' data-balloon-pos="up"> </span>
                    
                    <span class="recargar" onclick="recargar_captcha('imgCaptchaLogin');" style="cursor: pointer; font-size: 12px; font-weight: bold;">                                                                                    
                        Recargar Imagen
                    </span>
                    
                </td>

            </tr>
            
            <tr>
                <td colspan="2"  style="text-align: center" >      
                    <br/><br/>
                    
                    <div class="dos_columnas">
                        
                        <a  onclick="Elementos_General_MostrarElementoFlotante(false);"  
                            class="BotonMinimalista" >
                            <?php echo $this->lang->line('BotonCancelar');?>
                        </a>
                    </div>
                    
                    <div class="dos_columnas">
                        <a id="linkIngresarLogin"  class="BotonMinimalista" >
                            <?php echo $this->lang->line('BotonIngresar');?>
                        </a>
                    
                    </div>
                    
                </td>
            </tr>
            
        </table>               
    </div>
</form>

