<script type="text/javascript">
<?php
?>
function Ajax_CargarAccion_Editar(codigo) {
  var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
  Ajax_CargadoGeneralPagina('Tarea/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
}
</script>
<style>
.red {
  background-color: #b71c1c;
}
.blue {
  background-color: #FF6F00;
}
.btn {
  border: none;
  color: white;
  padding: 8px 12px;
  font-size: 12px;
  cursor: pointer;
}
</style>
<?php $cantidad_columnas = 1;?>
<div id="divVistaMenuPantalla" align="center">
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">
        <br /><br />
        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('FormularioDinamicoTitulo'); ?></div>
        <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('FormularioDinamicoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Formulario/Nuevo')">
                <?php echo $this->lang->line('FormularioDinamicoNuevo'); ?>
            </span>
        </div>
        <div id="divErrorBusqueda" class="mensajeBD">
        </div>
    </div>
</div>