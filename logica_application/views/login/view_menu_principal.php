<?php
/**
 * @file 
 * Banner Arriba Pantalla  
 * @author JRAD
 * @date Mar 24, 2015
 * @copyright OWNER
 */
?> 

<div id="divVistaMenuPrincipal" >
    
    <script type="text/javascript">
    
        function MenuEfecto() {
            
            $('#slider_menu').toggleClass('control-sidebar-open');
        }

    </script>
    
    <?php if (isset($_SESSION["session_informacion"])) { ?>              
        <div class="MenuBarraPrinc">
            <div class="FondoBannerImagen_opciones" onclick="MenuEfecto();"> </div>
            
            <div class="FondoBannerImagen_texto"> 
            
                <i><- El menu es el cubo</i> <?php echo $this->lang->line('NombreSistema');?>
                
            </div>
            
        </div>
    
        <aside id="slider_menu" class="control-sidebar" >

		<div class="DatosLogin_movil">                    
                    
                    <!-- Datos del Usuario-->
                    <i class="fa fa-user-circle-o" aria-hidden="true" style="display: inline !important;"></i> <?php echo $this->lang->line('LoginBienvenida');?> <?php echo $_SESSION["session_informacion"]["nombre"]; ?>
                    <br />
                    <i class="fa fa-users" aria-hidden="true" style="display: inline !important;"></i> Rol: <?php echo $_SESSION["session_informacion"]["rol"]; ?>
                    
                    <hr>
                    <!-- Opciones del Usuario -->
                    
                    <?php
                    
                    if($_SESSION["session_informacion"]["dias_cambio_password"] == 0)
                    {
                        echo $this->lang->line('RequiereRenovarPass');
                    }
                    elseif($_SESSION["session_informacion"]["password_reset"] == 1)
                    {
                        echo "ESTIMADO USUARIO, PARA UTILIZAR EL SISTEMA POR FAVOR DEBE RENOVAR SU CONTRASEÑA.";
                    }
                    else
                    {
                    ?>                    
                        <a onclick="Ajax_CargarOpcionMenu('Menu/Principal');"> <?php echo $this->lang->line('MenuPrincipal');?> </a>

                        <?php

                            foreach ($arrRespuesta as $key => $value) 
                            {
                                echo "<br /> <a onclick=\"Ajax_CargarOpcionMenu('" . $value["menu_ruta"] . "');\"> " . $value["menu_nombre"] . " </a>";
                            }

                        ?>
                    
                    <?php                    
                    }                    
                    ?>
                        
                    <hr>
                    <!-- Opciones de la cuenta -->
                    <a onclick="Ajax_CargarOpcionMenu('Usuario/Cambiar/Pass');"> <?php echo $this->lang->line('CambiarPass');?> <i class="fa fa-key" aria-hidden="true"></i></a>
                    <br /><a onclick="Ajax_CerrarLogin();"> <i class="fa fa-sign-out" aria-hidden="true" style="display: inline !important;"></i> <?php echo $this->lang->line('LoginSalir');?></a>

                </div>

	</aside>
    
    </div>
<?php } ?> 

