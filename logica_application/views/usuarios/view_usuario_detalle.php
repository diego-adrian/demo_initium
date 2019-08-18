
    <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleRegistroTitulo'); ?></div>
        
    <div style="clear: both"></div>

    <br />

    <form id="FormularioRegistroLista" method="post">

        <?php

        if(count($arrRespuesta[0]) > 0)
        {

        ?>
        
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaGris"; ?>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_user'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_user"]; ?>
                    </td>
                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">
                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_fecha_creacion'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_fecha_creacion"]; ?>
                    </td>
                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">
                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_activo'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_activo_detalle"]; ?>
                    </td>
                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_nombre'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_nombres"]; ?>
                    </td>

                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_app'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_app"]; ?>
                    </td>

                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_apm'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_apm"]; ?>
                    </td>

                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_email'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_email"]; ?>
                    </td>

                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_telefono'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_telefono"]; ?>
                    </td>

                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_direccion'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_direccion"]; ?>
                    </td>

                </tr>

                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('estructura_parent'); ?> <?php echo $this->lang->line('estructura_agencia'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["estructura_agencia_nombre"]; ?>
                    </td>

                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('estructura_regional'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["estructura_regional_nombre"]; ?>
                    </td>

                </tr>
                
                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('Usuario_rol'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrRespuesta[0]["usuario_rol_detalle"]; ?>
                    </td>

                </tr>

            </table>

        <?php

        }
        
        else
        {            
            echo $this->lang->line('TablaNoResultados');
        }

        ?>
        
    </form>

    <br /><br />