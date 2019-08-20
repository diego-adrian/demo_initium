<?php
/**
 * @file 
 * Pagina Principal de Llamada a Librerias 
 * @author JRAD
 * @date Mar 24, 2015
 * @copyright OWNER
 */

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8" />
        <title>
            :: <?php echo $this->lang->line('NombreSistema_corto'); ?> ::
        </title>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
        <link rel="stylesheet" type="text/css"
              href="html_public/css/estilo_general.css?ver=6" />
        <link rel="stylesheet" type="text/css"
              href="html_public/css/tooltip.css" />
        <link rel="stylesheet" type="text/css"
              href="html_public/css/font-awesome.css" />
        <link rel="stylesheet" type="text/css"
        <link rel="stylesheet" type="text/css"
              href="html_public/js/lib/themes/base/jquery.ui.all.css" />
        <script type="text/javascript"
        src="html_public/js/lib/jquery-1.10.2.min.js"></script>
        <script type="text/javascript"
        src="html_public/js/lib/formio.full.min.js"></script>
        <script type="text/javascript"
        src="html_public/js/lib/jquery.validate.min.js"></script>
        <script type="text/javascript"
        src="html_public/js/lib/ui/jquery-ui.custom.min.js"></script>
        <script type="text/javascript"
        src="html_public/js/lib/ui/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript"
        src="html_public/js/lib/ui/jquery.ui.datepicker-es.js"></script>
        
        <script type="text/javascript"
        src="html_public/js/js_pagina_logica.js"></script>

        <script type="text/javascript"
                src="html_public/js/lib/datatable/js/jquery.dataTables.min.js"></script>

        <script type="text/javascript"
                src="html_public/js/lib/datatable/js/jquery.dataTables.rowsGroup.js"></script>

        <script type="text/javascript"
                src="html_public/js/lib/charts/chart.bundle.js"></script>

        <script type="text/javascript"
        src="html_public/js/lib/transformaciones/numero_a_palabras.js"></script>

        <link rel="stylesheet" type="text/css"
              href="html_public/js/lib/datatable/css/jquery.dataTables.css" />

        <script type="text/javascript" src="html_public/js/lib/autocomplete/jquery-ui.js"></script>
        <script type="text/javascript" src="html_public/js/lib/autocomplete/autocomplete.js"></script>
        <style type="text/css" src="html_public/js/lib/autocomplete/jquery-ui.js"></style>
        <link rel="stylesheet" href="html_public/js/lib/autocomplete/jquery-ui.css"></link>
        
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, shrink-to-fit=no">
        <script type="text/javascript"
        src="html_public/js/lib/ui/jquery.ui.timepicker.min.js"></script>
        <script type="text/javascript"
        src="html_public/js/lib/ui/jquery.ui.timepicker-es.js"></script>
        
        <!-- favicons -->
        
        <link rel="apple-touch-icon" sizes="180x180" href="html_public/imagenes/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="html_public/imagenes/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="html_public/imagenes/favicons/favicon-16x16.png">
        <link rel="stylesheet" href="html_public/css/formio.full.min.css">
        <link rel="stylesheet" href="html_public/css/bootstrap.min.css">
        <link rel="manifest" href="html_public/imagenes/favicons/manifest.json">
        <link rel="mask-icon" href="html_public/imagenes/favicons/safari-pinned-tab.svg" color="#006699">
        <meta name="apple-mobile-web-app-title" content="Initium App">
        <meta name="application-name" content="Initium App">
        <meta name="theme-color" content="#ffffff">
        <link rel="shortcut icon" href="html_public/imagenes/favicons/favicon.ico?v=qA3LBrJxN9">
        
        <!-- favicons -->
            
        <script type="text/javascript">
        
            $(document).ajaxStart(function() {
                $("#PaginaPrincipal_Cargando").fadeIn("fast");
            });
            $(document).ajaxStop(function() {
                $("#PaginaPrincipal_Cargando").fadeOut("fast");
            });
            $.validator.addMethod(
                    "miregex",
                    function(value, element, regexp) {
                        var re = new RegExp(regexp);
                        return this.optional(element) || re.test(value);
                    },
                    "Existen caracteres no validos."
                    );
            $.validator.addMethod("noesigual", function(value, element, arg) {
                return arg != value;
            }, "Debe escoger un valor");
            $.datepicker.setDefaults($.datepicker.regional[ "es" ]);

        </script>
		
		<script>
			$(document).ready(function(){
				if(window.console || "console" in window) {
					console.log("%c ¡PRECAUCIÓN!", "color:#3c2c70; font-size:40px;");
					console.log("%c <?php echo $this->lang->line('Self-XSS'); ?>", "color:#003087; font-size:12px;");
				}
			});
		</script>
                    
                <script>
                    // Funcion para evitar ir atrás en el navegador
                    history.pushState(null, null, document.URL);
                    window.addEventListener('popstate', function () {
                        history.pushState(null, null, document.URL);
                    });

                </script>
                
    </head>

    <?php
    if (isset($_SESSION["session_informacion"])) 
    {
        echo '<body onload="Ajax_BuscarPermisosUsuario();" class="PaginaContenidoGeneral" style="left: 0;top:0;position:absolute;right:0;">';
    } 
    else 
    {
        echo '<body class="PaginaContenidoGeneral" style="left: 0;top:0;position:absolute;right:0;">';
    }
    
    echo form_open();
    echo form_close();
    ?>
    <div id="divFondoElementoFlotante" style="display:none" class="ModalFondoSemiTransparente"></div>
    <div id="divContenidoElementoFlotanteRecargar" style="display:none; height: 8cm;text-align: center" class="ElementoFlotante">
        <?php echo $this->lang->line('MensajeErrorRstf'); ?> <br /><br /><br />
        <a id="btnRecargar" class="BotonPequeno" onclick="location.reload()"> 
            <?php echo $this->lang->line('MensajeErrorBotonRstf'); ?> 
        </a>
    </div>
    <div id="divContenidoElementoFlotante" style="display:none" class="ElementoFlotante">            
        <span id="btnModalCerrarVentana" style="display: block; position: absolute; right: 5px; top: 10px"> 
            <a onclick="Elementos_General_MostrarElementoFlotante(false);" style="margin-left:0mm;">  
                <img src="html_public/imagenes/cerrar_modal.png" alt="cerrar" /></a>
        </span>
        <div id="divElementoFlotante" style="width:90%;" class="Centrado"></div>                
    </div>                           
    <div id="divCargarRespuestaScript" style="display:none" ></div>


