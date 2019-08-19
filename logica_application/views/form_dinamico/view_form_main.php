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
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Usuario/Editar')">
                <?php echo $this->lang->line('FormularioDinamicoNuevo'); ?>
            </span>
        </div>
        
        <?php
        $i = 0;
        $strClase = "FilaBlanca";
        echo '<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
            <thead>
                <tr class="FilaCabecera">
                    <th style="width:5%;">Nº </th>
                    <th style="width:10%;">'.$this->lang->line('FormularioDinamicoNombre').'</th>
                    <th style="width:15%;">'.$this->lang->line('FormularioDinamicoDescripcion').'</th>
                    <th style="width:15%;">'.$this->lang->line('FormularioDinamicoPublicado').'</th>
                    <th style="width:30%;">'.$this->lang->line('TablaOpciones').'</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($formularios as $formulario)
                {
                $i++;
                echo '
                <tr class="'.$strClase.'">
                    <td style="text-align: center;">'.$i.'</td>
                    <td style="text-align: center;">'.$formulario->nombre.'</td>
                    <td style="text-align: center;">'.$formulario->descripcion.'</td>
                    <td style="text-align: center;">'.$formulario->publicado.'</td>
                    <td style="text-align: center;">
                        <button class="btn blue"><i class="fa fa-pencil"></i></button>
                        <button class="btn red"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>';
                }
                
                echo '
            </tbody>
        </table>';
        ?>
        <div id="divErrorBusqueda" class="mensajeBD">
        </div>
    </div>
</div>