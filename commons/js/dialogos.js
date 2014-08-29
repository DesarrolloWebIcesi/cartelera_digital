/**
 * Funcion que muestra un di�logo modal usando JQueryUI con un bot�n "Aceptar"
 * @param mensaje Texto que se va a mostrar
 * @param tipo tipo de mensaje 1 (Informaci�n), 2 (Advertencia), 3 (Error), 4 (Confirmaci�n)
 */
function desplegarDialogo(mensaje, titulo, ancho, alto, tipo){
  jQuery("#dialog-message-text").html(mensaje);
  jQuery('#dialog-icon').removeClass();
  jQuery('#dialog-icon').addClass('ui-icon');
  switch(tipo){
    case 1:
      clase = 'ui-state-default';
      jQuery('#dialog-icon').addClass('ui-icon-info');
      break;
    case 2:
      clase = 'ui-state-active';
      jQuery('#dialog-icon').addClass('ui-icon-alert');
      break;
    case 3:
      clase = 'ui-state-error';
      jQuery('#dialog-icon').addClass('ui-icon-circle-close');
      break;
    case 4:
      clase = 'ui-state-default';
      jQuery('#dialog-icon').addClass('ui-icon-circle-check');
      break;
    default:
      clase = 'ui-state-default';
      jQuery('#dialog-icon').addClass('ui-icon-notice');
      break;
  }
  jQuery( "#dialog-message" ).dialog({
    modal: true,
    buttons: {
      "Aceptar": function() {
        jQuery( this ).dialog( "close" );
      }
    },
    title: titulo,
    closeOnEscape: true,
    draggable: false,
    resizable: false,
    show: 'fade',
    hide: 'fade',
    width: ancho,
    minHeight: alto,
    dialogClass: clase
  })
}
function desplegarConfirmacion(mensaje, titulo, ancho, alto){
  jQuery("#dialog-message-text").html(mensaje);
  jQuery('#dialog-icon').addClass('ui-icon-alert');
  jQuery( "#dialog-message" ).dialog({
    modal: true,
    buttons: {
      "Si": function() {
        jQuery( this ).dialog( "close" );
        return true;
      },
      "No": function() {
        jQuery( this ).dialog( "close" );
        return false;
      }
    },
    title: titulo,
    closeOnEscape: true,
    draggable: false,
    resizable: false,
    show: 'fade',
    hide: 'fade',
    width: ancho,
    minHeight: alto,
    dialogClass: 'ui-state-active'
  })
}

