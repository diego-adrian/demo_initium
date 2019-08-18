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
    
    function Ajax_CargarAccion_AsignarPerfilUsuario(usuario_codigo) {
        var strParametros = "&usuario_codigo=" + usuario_codigo + "&tipo_accion=" + 1;
        Ajax_CargadoGeneralPagina('Perfil/Usuario/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 9; ?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('PerfilUsuarioTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('PerfilUsuarioSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
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
                    <th style="width:25%;">
                        <?php echo $this->lang->line('Usuario_nombre'); ?>   </th>
                    <th style="width:20%;">
                        <?php echo $this->lang->line('Usuario_rol'); ?>   </th>
                    <th style="width:15%;">
                        <?php echo $this->lang->line('estructura_parent'); ?> <?php echo $this->lang->line('estructura_agencia'); ?> </th>
                    <th style="width:10%;">
                        <?php echo $this->lang->line('Usuario_activo'); ?>   </th>
                   
                    <!-- Similar al EXCEL -->
                    
                    <th style="width:15%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
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
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(0, '<?php echo $value["usuario_codigo"]; ?>')">
                                    <?php echo $value["usuario_user"]; ?>
                                </span>
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
                                <span class="BotonSimple" onclick="Ajax_CargarAccion_AsignarPerfilUsuario('<?php echo $value["usuario_codigo"]; ?>')">
                                    <?php echo $this->lang->line('TablaOpciones_asignar_perfil'); ?>
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