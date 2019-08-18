
<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <div class="FormularioSubtitulo"><?php echo $this->lang->line('conf_credmenu_Titulo'); ?> </div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('conf_credmenu_Subtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <div align="left" class="Botones2Opciones">
            
           <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Conf/Credenciales/Ver');">
                <?php echo $this->lang->line('conf_credenciales_Titulo'); ?>
            </span>
        </div>
        
        <div align="left" class="Botones2Opciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Rol/Ver');">
                <?php echo $this->lang->line('RolTitulo'); ?>
            </span>
        </div>
        
        <div style="clear: both"></div>
		
		<div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>