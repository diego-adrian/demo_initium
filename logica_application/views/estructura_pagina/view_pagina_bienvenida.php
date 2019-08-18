<?php
/**
 * @file 
 * Pagina Bienvenida 
 * @author JRAD
 * @date Junio, 2017
 * @copyright OWNER
 */

?> 

<script type="text/javascript">      
    $('#password').keypress(function (e) {        
        if (e.which == 13) {            
            $('#FormTarjetaActivaAcceso').submit();
            e.preventDefault();
        }
    });    
    <?php echo $strValidacionJqValidate; ?>  
        
    Elementos_Habilitar_ObjetoARefComoSubmit("linkIngresarLogin", "FormTarjetaActivaAcceso");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm('FormTarjetaActivaAcceso', 'Login/Autenticacion',
            '', 'divErrorRespuestLogin', 'SIN_CARGA', '', Elementos_Carga_Interna_Menus);
</script>

<div id="divPaginaPresentacionGeneral">
    <div class="MenuBarraPrinc">
		
		<div class="FondoBannerImagen_logo"> </div>
				
		<div class="FondoBannerImagen_candado"> </div>
    </div>
    
	<div class="container portada">

		<div class="title-section titulo_ocultar" style="margin-top: 150px; margin-left: 100px; text-align: left;">

			<h1 class="portada_titulo"><?php echo $this->lang->line('TituloBienvenidaDetalle'); ?></h1>

		</div>

		<div class="title-section titulo_mostrar" style="margin-top: 64px; margin-left: 17px; text-align: left;">

			<h1 class="portada_titulo"><?php echo $this->lang->line('TituloBienvenidaDetalle'); ?></h1>

		</div>

		<br />

                <div align="center" style="margin-left: 10px; margin-right: 10px;">
		
                    <a class="MenuSeccionMinimalista" onclick="Ajax_CargarLogin();">
                        <span class="TextoGrande"> <?php echo $this->lang->line('TituloBienvenidaBoton'); ?> </span><br /> 
                        <span class="TextoMinimo">  <?php echo $this->lang->line('NombreSistema'); ?>   </span>
                    </a>
			
		</div>
		
	</div>
	
</div>
