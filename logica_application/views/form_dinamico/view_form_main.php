<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
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
    function Ajax_CargarAccion_Editar(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Tarea/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 5;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('FormularioDinamicoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('FormularioDinamicoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        CONTENIDO DEL MÓDULO
		
		<div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>