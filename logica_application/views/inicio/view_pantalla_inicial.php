
<div id="divVistaMenuPantalla" align="center">   
    
    <script type="text/javascript">
    
        function CambiarVista(vista) {
            var strParametros = "&vista=" + vista;
            Ajax_CargadoGeneralPagina('Menu/Cambiar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    
    </script>
        
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">
        
        <?php
        
        if(isset($_SESSION['auxiliar_bandera_upload']) && $_SESSION['auxiliar_bandera_upload'] > 0)
        {
            $texto_auxilar = '';
            
            if($_SESSION['auxiliar_bandera_upload'] == 1)
            {
                $texto_auxilar = $this->lang->line('Correcto');
            }
            
            if($_SESSION['auxiliar_bandera_upload'] == 2)
            {
                $texto_auxilar = $this->lang->line('FormularioNoFile');
            }
        ?>
            <br /><br />

                <span class="PreguntaConfirmar">
                    <?php echo $texto_auxilar; ?>
                    <br /><br />
                </span>

            <div class="Centrado" style="width: 70%; text-align: center; padding-top: 10px;">
                <a class="BotonMinimalista" style="" onclick="Ajax_CargarOpcionMenu('<?php echo $_SESSION['auxiliar_bandera_upload_url']; ?>');">
                    <span><?php echo $this->lang->line('BotonAceptar'); ?></span>                            
                </a>
            </div>
        
        <?php
        
        $_SESSION['auxiliar_bandera_upload'] = 0;
        
        }
        else
        {

        ?>
        
            <div class="AnuncioTitulo">
                <i class="fa fa-home" aria-hidden="true"></i> Hola <?php echo $_SESSION["session_informacion"]["nombre"]; ?>
            </div>

            <div class="AnuncioTexto">
                Bienvenido(a) al <?php echo $this->lang->line('NombreSistema'); ?> 
                la herramienta tecnológica que te permite la adecuada y fácil gestión de procesos de afiliación propios de tu empresa,
                la verificaión de la documentación y la aprobación del proceso para un seguimiento oportuno y detección de cuellos de botella.

            </div>

            <div class="AnuncioTitulo">
                <i class="fa fa-shield" aria-hidden="true"></i> Nos importa tu Seguridad
            </div>

            <div class="AnuncioTexto">

                Es altamente recomendable que no compartas tus credenciales de acceso y que renueves tu contraseña periodicamente. Todas las acciones realizadas
                serán registradas en los Logs del Sistema.

                <br /><br />

                Tu último acceso fue el <?php echo $_SESSION["session_informacion"]["fecha_ultimo_acceso"]; ?>.

                <br /><br />

                <strong>Tu contraseña actual deberá ser renovada en <u> <?php echo $_SESSION["session_informacion"]["dias_cambio_password"]; ?> día(s). </u> </strong>


            </div>
        
        <?php
        
        }

        ?>
    
    </div>