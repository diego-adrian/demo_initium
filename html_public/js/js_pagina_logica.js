/**
 * @file 
 * Codigo Encargado de rescatar  la informacion de un Formulario y sus Observaciones para la Generacion  del Codigo RAIG
 * @brief The controlador 
 * @author JRAD
 * @date Mar 29, 2011
 * @copyright 2017 JRAD
 */
function Elementos_Menu_EfectosBarraMenu() {
    $('.menu_item').hover(
            function() {
                $('ul', $(this)).css("display", "block");
            },
            function() {
                $('ul', $(this)).css("display", "none");
            }
    );
}
/**
 * Funcion que hace esto y aquello 
 * @param String NombreBotonAHref   nombre del boton
 * @param String NombreForm nombre de otro 
 */
function Elementos_Habilitar_ObjetoARefComoSubmit(NombreBotonAHref, NombreForm) {
    $("#" + NombreBotonAHref).click(function() {
        $("#" + NombreForm).submit();
    });
}
function Ajax_CargadoGeneralPagina(
        strUrlDestino, strDivDestino, strDivError, strParametros, strdata, NameFunction) {
    $('#slider_menu').removeClass('control-sidebar-open');
    $('#divCargarRespuestaScript').empty();
    try{
    var args = [];
    if (strdata === undefined)
    {
        strdata = '';
    }
    if (strParametros === undefined) {
        strParametros = "";
    }
    if (strDivDestino == 'divElementoFlotante') {
        $("input:text, input:radio, select").attr("tabindex", "-1");
        // Si se requiere bloquear todos los elementos cuando se abre la ventana flotante, se debe descomentar la línea de abajo
        //$("input:text, input:radio, select").attr("disabled", true);
    }
    if (strDivError === undefined) {
        strDivError = "divCargarRespuestaScript";
    }
    $('#' + strDivError).empty();
    strdata = strdata + '&csrf_test_name=' + $("input[name=csrf_test_name]").val();
    $.post(strUrlDestino, strdata,
            function (data) {
                try {
                    if ($(data).filter('div[clave="error"]').text() === "error") {
                        $('#' + strDivError).empty().append(data);
                        return;
                    }
                    var resultado = $('*:contains("Fatal error")');
                    if(data.indexOf("Fatal error")>=0){
                        $('#' + strDivError).empty().append("<br />Algo pasó en la Generacion de la Pagina, porfavor revise los Logs<br /><br />");
                        return;
                    }
                } catch (e) {
                    ;
                }
                if (strParametros.indexOf("SIN_CARGA") >= 0) {
                    //NO SE CARGA NADA
                }
                else if (strParametros.indexOf("CARGA_SOLO_SCRIPTS") >= 0) {
                    $('#divCargarRespuestaScript').empty().append(data);
                }
                else {
                    if (strParametros.indexOf("CARGA_Y_AGREGA") >= 0) {
                        $('#' + strDivDestino).hide().append(data);
                    } else {
                        //por defecto carga y reemplaza
                        $('#' + strDivDestino).hide().empty().append(data);
                    }
                    if (strDivDestino == 'divElementoFlotante') {
                        Elementos_General_MostrarElementoFlotante(true);
                        $('#btnModalCerrarVentana').show();
                        if (strParametros.indexOf("MODAL_SIN_CERRAR") >= 0) {
                            $('#btnModalCerrarVentana').hide();
                        }
                    }
                    if (strParametros.indexOf("SLASH") >= 0) {
                        $('#' + strDivDestino).show();
                        EfectoSlash(strDivDestino);
                    }
                    else if (strParametros.indexOf("SIN_EFECTOS") < 0) {
                        $('#' + strDivDestino).fadeIn(500);
                    }
                    if (strParametros.indexOf("SIN_SCROLL") < 0) {

                        if (strDivDestino === 'divElementoFlotante') {
                            $('#divElementoFlotante').scrollTop(0);
                        } else {
                            $(document).scrollTop(0);
                        }
                    }
                    if (strParametros.indexOf("SIN_FOCUS") < 0) {
                        $("input:text:visible:enabled:not([readonly]):first").focus();
                    }
                }
                if (NameFunction !== undefined) {
                    args.push(data);                  
                    NameFunction.apply(this, args);
                }
            })
            .fail(function (data) {
                $('#' + strDivError).empty().append("<br />Algo pasó en la Generacion de la Pagina, porfavor revise los Logs<br /><br />");
                return;

            })
            ;
        }catch(e){            
            $('#' + strDivError).empty().append("<br />Existe un problema en la Carga de la Pagina<br /><br />");
        }
}



function EfectoSlash(strNombrediv){
    $('html,body').animate({scrollTop: $('#' + strNombrediv).show().offset().top - 80}, 600);
}
function Elementos_General_MostrarElementoFlotante(blnOpcion) {
    if (blnOpcion) {
        $('#divFondoElementoFlotante').show();
        $('#divContenidoElementoFlotante').fadeIn(500);
    }
    else {
        $("input:text, input:radio, select").attr("tabindex","");
        $("input:text, input:radio, select").attr("disabled",false);
        $('#divContenidoElementoFlotante').attr("style", "");
        $('#divFondoElementoFlotante').hide();
        $('#divElementoFlotante').empty();
        $('#divContenidoElementoFlotante').fadeOut(500);
        $('#divContenidoElementoFlotante').removeClass("ModalFondoSemiTransparenteFormulario");
    }
}
function Ajax_CargarOpcionMenu(strUrlDestino) {
    Ajax_CargadoGeneralPagina(strUrlDestino, 'divContenidoGeneral','divContenidoGeneral');
}
function Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm(
        NombreForm, strUrlDestino, strDivDestino, strDivError, strParametros,
        strdata, NameFunction, arrInReglasValidacion, arrInMensajesValidacion) {
    //strUrlDestino, strDivDestino, strdata, strParametros, NameFunction
    var arrReglasLocalesValidacion = arrReglasValidacion;
    if (NameFunction == '') {
        NameFunction = undefined;
    }
    if (arrInReglasValidacion !== undefined) {
        arrReglasLocalesValidacion = arrInReglasValidacion;
    }
    var arrMensajesLocalesValidacion = arrMensajesValidacion;
    if (arrInMensajesValidacion !== undefined) {
        arrMensajesLocalesValidacion = arrInMensajesValidacion;
    }
    var validator = $('#' + NombreForm).validate({
        onkeyup: function(element) {
            $(element).valid();
        },
        ignore: ":hidden",
        rules: arrReglasLocalesValidacion,
        messages: arrMensajesLocalesValidacion,
        debug: true,
        submitHandler: function(form) {
            if (strdata === undefined)
                strdata = '';
            Ajax_CargadoGeneralPagina(strUrlDestino, strDivDestino, strDivError,  strParametros,$('#' + NombreForm).serialize() + strdata, NameFunction);
        },
        invalidHandler: function() {
            $("#" + strDivError).html("<br />Existe(n) " + validator.numberOfInvalids() + " error(es) en el formulario <br /> &nbsp");
        },
        onfocusout: function(element)
        {
            //if ($("#" + strDivError).text().charAt(0) !== ".") {
            $("#" + strDivError).empty();
            //}
        }
    });
}
function Elementos_CheckedCambioAprobacion(intCondicion, blnOpcion, strDivError) {
    $('#' + strDivError).html("");
    $('#chkCondicion' + intCondicion).attr('checked', blnOpcion);
    $('#chkCondicion' + intCondicion).checked = blnOpcion;
    if (blnOpcion) {
        //$("#divImgCondicion" + intCondicion).attr('class', strClase + " AlertaAceptada");
        $("#divImgCondicion" + intCondicion).addClass("AlertaAceptada");
    }
    else {
        $("#divImgCondicion" + intCondicion).removeClass("AlertaAceptada");
    }
    //   $("#divImgCondicion" + intCondicion).attr('class', strClase);
}
function Elementos_CheckedRevisionAprobacion(strDivError) {
    var ListaReferencia = $('input:checkbox');
    var PaginasTotal = ListaReferencia.length;
    for (var i = 0; i < PaginasTotal; i++) {
        if (!($(ListaReferencia[i]).attr('checked') == 'checked')) {
            $('#' + strDivError).html("<br />Para continuar debe responder afirmativamente a las condiciones requeridas<br /><br />");
            return false;
        }
    }
    $('#' + strDivError).empty();
    return true;
}
function Ajax_CargarLogin() {
    $("#divSalirSistema").html("");
    $('#divFormularioNuevosUsuarios').empty();
    $('#divFormularioBusquedaPublica').empty();
    $('#divContenidoElementoFlotante').attr("style", "height:auto;");
    Ajax_CargadoGeneralPagina('Login/Formulario', 'divElementoFlotante', "errorMenuPrincipal");
}

function Ajax_CerrarLogin() {
    $("#divSalirSistema").html("");
    $('#divMenuPrincipal').empty();
    Ajax_CargadoGeneralPagina('Login/CerrarLogin', 'divContenidoGeneral');
}
function Ajax_MenuPrincipal(){
    Ajax_CargadoGeneralPagina('Login/MenuPrincipal', 'divContenidoGeneral');    
}
function Elementos_Carga_Interna_Menus(data) {
    try{
    $('#divMenuPrincipal').empty().append($(data).filter('#divVistaMenuPrincipal'));
    $('#divContenidoGeneral').empty().append($(data).filter('#divVistaMenuPantalla'));
    Elementos_General_MostrarElementoFlotante(false);
    }catch(e){
        $('#divErrorRespuestLogin').empty().append("<br />Existe un problema en la Carga de la Pagina<br /><br />");
    }
}
function Elementos_Mostrar_ErrorDiv(strMensaje, strNombreDiv) {
    $('#' + strNombreDiv).html("<br />" + strMensaje + "<br /> &nbsp");
}
function Elementos_Mostrar_Toggle(strNombreBoton, strNombreDiv) {
    $("#" + strNombreBoton).click(function() {
        $("#" + strNombreDiv).toggle("slow");
    });
}
function Ajax_BuscarPermisosUsuario() {
    Ajax_CargadoGeneralPagina('Login/MenusPermisos', 'divCargarRespuestaScript', 'divCargarRespuestaScript', 'SIN_CARGA','', Elementos_Carga_Interna_Menus);
}
function Elemento_MostrarAyuda(strNombreDiv) {
    $('#divElementoFlotante').html($('#' + strNombreDiv).html());
    Elementos_General_MostrarElementoFlotante(true);


    
}

function Elemento_ManejoMenuIzquierda(blnMostrarOcultar) {
        if (!blnMostrarOcultar) {
            //alert('123');
            $('#divMenuIzquierda').hide();
            $('#tdIzquierdo').hide();
        
            //alert('456');
            $('#tdDerecho').animate({
                width: "100%",
                margin: "0 0cm 0 0cm"
//                
            }, 500);
            //
        }else{
            //alert('789');
            $('#divMenuIzquierda').show();
            $('#tdIzquierdo').show();
            $('#tdDerecho').animate({
                width: "80%",
                margin:"0 0 0 0cm"
//                
            }, 500);   
        }
    }
function valida_longitud(id, num_caracteres_permitidos) {
    num_caracteres = $('#' + id).val().length;
    if (num_caracteres === 0) {
        contenido_textarea = "";
    }
    if (num_caracteres > num_caracteres_permitidos) {
        $('#' + id).val(contenido_textarea);
    } else {
        contenido_textarea = $('#' + id).val();
    }
}

function recargar_captcha(divImg) {
    Ajax_CargadoGeneralPagina('Login/RecargarImagenCaptcha', divImg, 'divErrorRespuestaLogin', 'SIN_CARGA', '',
            function(data) {
                $('#' + divImg).empty();
                $('#' + divImg).html(data);
                //mostrar_msjTarjeta(msj);
                //mostrar_msjLogin(msj);
            }
    );
    
}

function recargar_captcha_externo(divImg) {
    Ajax_CargadoGeneralPagina('../../Externo/RecargarImagenCaptcha', divImg, 'divErrorRespuestaLogin', 'SIN_CARGA', '',
            function(data) {
                $('#' + divImg).empty();
                $('#' + divImg).html(data);
                //mostrar_msjTarjeta(msj);
                //mostrar_msjLogin(msj);
            }
    );
    
}

//function Ajax_CargarFormularioConsultaPublica() {
//    $('#divFormularioNuevosUsuarios').empty();
//    Ajax_CargadoGeneralPagina('BusquedaPublica/FormularioBusqueda', 'divFormularioBusquedaPublica', 'errorMenuPrincipal', 'SIN_FOCUS|SLASH');
//}
var idioma_table = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando los registros del _START_ al _END_ de un total de _TOTAL_ ",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "<div class='FormularioTituloCajas'> BÚSQUEDA RÁPIDA:</div>",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}