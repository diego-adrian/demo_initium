<script type="text/javascript">
<?php
// Si no existe información, no se mostrará como tabla con columnas con órden
if(count($arrRespuesta[0]) > 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
<?php
}
?>    
    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_EditarUsuario(usuario_codigo) {
        var strParametros = "&usuario_codigo=" + usuario_codigo + "&tipo_accion=" + 1;
        Ajax_CargadoGeneralPagina('Usuario/Editar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_Restablecer(usuario_codigo) {
        var strParametros = "&usuario_codigo=" + usuario_codigo;
        Ajax_CargadoGeneralPagina('Usuario/Restablecer/PassPregunta', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 9; ?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('UsuarioTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('UsuarioSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Usuario/Editar')">
                <?php echo $this->lang->line('TablaOpciones_NuevoUsuario'); ?>
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Agencia/Ver')">
                Estructura de <br /><?php echo $this->lang->line('estructura_agencia'); ?>
            </span>
        </div>
            
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Regional/Ver')">
                Estructura de <br /><?php echo $this->lang->line('estructura_regional'); ?>
            </span>

        </div>
        
        <div style="clear: both"></div>
        
        <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
            <thead>
                <tr class="FilaCabecera">

                    <th style="width:5%;">
                       Nº 
                    </th>
                    
                    <!-- Similar al EXCEL -->
                    
                    <th style="width:10%;">
                       <?php echo $this->lang->line('Usuario_user'); ?>       </th>
                    <th style="width:15%;">
                        <?php echo $this->lang->line('Usuario_nombre'); ?>   </th>
                    <th style="width:15%;">
                        <?php echo $this->lang->line('Usuario_rol'); ?>   </th>
                    <th style="width:15%;">
                        <?php echo $this->lang->line('estructura_parent'); ?> <?php echo $this->lang->line('estructura_agencia'); ?> </th>
                    <th style="width:10%;">
                        <?php echo $this->lang->line('Usuario_activo'); ?>   </th>
                   
                    <!-- Similar al EXCEL -->
                    
                    <th style="width:30%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $mostrar = true;
                if (count($arrRespuesta[0]) == 0) 
                {
                    $mostrar = false;
                }

                if ($mostrar) 
                {                
                    $i=0;
                    $strClase = "FilaBlanca";
                    foreach ($arrRespuesta as $key => $value) 
                    {                    
                        $i++;
                        
                        ?> 
                        <tr class="<?php echo $strClase; ?>">
                            
                            <td style="text-align: center;">
                                <?php echo $i; ?>
                            </td>
                            
                            <!-- Similar al EXCEL -->
            
                            <td style="text-align: center;">
                                <?php echo $value["usuario_user"]; ?>
                            </td>                            
                            <td style="text-align: center;">
                                <?php echo $value["usuario_nombre_completo"]; ?>
                            </td>                            
                            <td style="text-align: center;">
                                <?php echo $value["usuario_rol"]; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $value["estructura_agencia_nombre"]; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $value["usuario_activo"]; ?>
                            </td>
                            
                            <!-- Similar al EXCEL -->
                            
                            <td style="text-align: center;">
                            
                                <span class="BotonSimple" onclick="Ajax_CargarAccion_DetalleUsuario(0, '<?php echo $value["usuario_codigo"]; ?>')">
                                    <?php echo $this->lang->line('TablaOpciones_Detalle'); ?>
                                </span>
                                <span class="BotonSimple" onclick="Ajax_CargarAccion_EditarUsuario('<?php echo $value["usuario_codigo"]; ?>')">
                                    <?php echo $this->lang->line('TablaOpciones_Editar'); ?>
                                </span>

                                <span class="BotonSimple" onclick="Ajax_CargarAccion_Restablecer('<?php echo $value["usuario_codigo"]; ?>')">
                                    <?php echo $this->lang->line('TablaOpciones_Restablecer'); ?>
                                </span>
                                
                            </td>
                            
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                    <?php
                    $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                    //endfor;
                }
                else 
                {
                ?>
                <tr>
                    <td style="width: 55%; color: #25728c;" colspan="<?php echo $cantidad_columnas; ?>">
                        <br><br>
                        <?php echo $this->lang->line('TablaNoRegistros'); ?>
                        <br><br>                                           
                    </td>                               
                </tr>
            <?php } ?>            
        </table>

        <div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>

<br /><br /><br /><br /><br /><br />