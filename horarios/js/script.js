/* Author: David Andrés Manzano Herrera - damanzano

 */

var page=0;
var firstFlip=true;
var data;
var refresh = 0;
jQuery(document).on("ready",function(){
  readData();
  setInterval("readData()", 60000);
  setTimeout("setInterval('flipPage()', 10000)",500);
	setTimeout("window.location.reload()",21600000);
});

function readData(){
  hora_actual = new Date();
  data = null;
  if(!isNaN(floor) || floor==null || !isNaN(building) || building==null){
    $.ajax({
      url: 'controller/ProcessController.php',
      dataType: 'json',
      type: 'POST',
      data: {
        floor: floor,
        building:building
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        if (errorThrown != 'abort' && XMLHttpRequest.status != 0) {
          console.log('unable to get data, error:' + textStatus);
        }
      },
      success: function(jsonData, callback) {
        data=jsonData;
        hora = new Date();
        renderTime(hora.clone());
        renderColumnTitle(jsonData,hora.clone());
        if(firstFlip)
          flipPage();
        else
          refreshPage();
      }
    });
  }else{
    $("#rooms-labels").html('');
    $("#rooms-columns-container").html('');
    $("#rooms-columns-container").append('<div class="ui-state-error no-rooms"><span>El valor ingresado en el par&aacute;metro "floor" no es v&aacute;lido.</span></div>');
  }
}

function renderColumnTitle(data,hora){
  $("#rooms-labels").html('');
  $("#rooms-columns-container").html('');
  var j = 0;
  if(data.rooms.length > 0){
    $.each(data.rooms, function(i,room){
      if(i%5 == 0){
        j = i/5;
        //alert("j:"+j+" i:"+i);
        var $titlePage = $('<div id="title-page-'+j+'" class="page"/>');
        var $columnPage = $('<div id="column-page-'+j+'" class="page"/>');
        $("#rooms-labels").append($titlePage); 
        $("#rooms-columns-container").append($columnPage);
      }
      var titleColumn='<div id="column-title-'+i+'" class="room-header ui-corner-all ui-state-default">'+room.name+'</div>';
      $("#title-page-"+j).append(titleColumn);
      var roomColumn = $('<div id="column-'+i+'" class="room-body ui-widget" />');
      $("#column-page-"+j).append(roomColumn);
      renderReservations(roomColumn,room,hora);
    });
  }else{
    $("#rooms-columns-container").append('<div class="ui-state-error no-rooms"><span>No hay espacios fisicos en el bloque '+building+' piso '+floor+'</span></div>');
  }
//displayPage(true);
}

function displayPage(firstTime){
  efecto = 'clip';
  if(!firstTime){
    if(page>0){
      $('#title-page-'+(page-1)+', #column-page-'+(page-1)).hide(500);
    }else{
      $('#title-page-'+(Math.ceil(data.rooms.length/5)-1)+', #column-page-'+(Math.ceil(data.rooms.length/5)-1)).hide(500);
    }
  }
  $('#title-page-'+page+', #column-page-'+page).show(500);
  page++;
  if(page>(Math.ceil(data.rooms.length/5)-1)){
    page=0;
  }
}
function refreshPage(){
  $('#title-page-'+page+', #column-page-'+page).show(500);
}
function flipPage(){
  if(data!=null && data!=undefined){
    displayPage(firstFlip);
    firstFlip=false;
  }
}

function renderTime(hora){
  $('.hours-body').html('');
  hora_actual = hora;
  hora_actual.setMinutes((hora_actual.getMinutes()>=30)?30:0, 0, 0);
  for(i=0;i<8;i++){
    minutos = '30';
    if(hora_actual.getMinutes() == 0){
      minutos = '00';
    }
    clase = "hours";
    if(i==0){
      clase += " ui-state-highlight";
    }
    $('.hours-body').append('<div class="'+clase+'"><span>'+hora_actual.getHours()+':'+minutos+'</span></div>');
    hora_actual.setMinutes((hora_actual.getMinutes()+30), 0, 0);
  }
}
function renderReservations(column, room, hora){
  hora_actual = hora;
  hora_base = hora_actual.getHours()*3600 + ((hora_actual.getMinutes()>=30)?30:0)*60;
  $.each(room.reservations, function(i,reservation){
    duracion = (reservation.endHour-reservation.initHour)/1800;
    bloque = (reservation.initHour - hora_base)/1800;
    for(j=bloque;j<(bloque+duracion);j++){
      if(j >= 0 && j < 8){
        html = '<div class="reservation ui-state-error " style="top:'+j*62+'px;">';
        html += '<span class="clearfix reservation-title">'+reservation.description+'</span>';
        html += '<span class="reservation-content">'+reservation.requester+'</span>';
        html += '</div>';
        column.append(html);
      }
    }
  });
}
function sleep(ms)
{
  var dt = new Date();
  dt.setTime(dt.getTime() + ms);
  while (new Date().getTime() < dt.getTime());
}