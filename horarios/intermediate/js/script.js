var page=0,firstFlip=!0,data,refresh=0;jQuery(document).on("ready",function(){readData();setInterval("readData()",6E4);setTimeout("setInterval('flipPage()', 10000)",500)});
function readData(){hora_actual=new Date;data=null;!isNaN(floor)||floor==null?$.ajax({url:"controller/ProcessController.php",dataType:"json",type:"POST",data:{floor:floor},error:function(a,c,b){b!="abort"&&a.status!=0&&alert("unable to get data, error:"+c)},success:function(a){data=a;hora=new Date;renderTime(hora.clone());renderColumnTitle(a,hora.clone());firstFlip?flipPage():refreshPage()}}):($("#rooms-labels").html(""),$("#rooms-columns-container").html(""),$("#rooms-columns-container").append('<div class="ui-state-error no-rooms"><span>El valor ingresado en el par&aacute;metro "floor" no es v&aacute;lido.</span></div>'))}
function renderColumnTitle(a,c){$("#rooms-labels").html("");$("#rooms-columns-container").html("");var b=0;a.rooms.length>0?$.each(a.rooms,function(a,e){if(a%5==0){b=a/5;var d=$('<div id="title-page-'+b+'" class="page"/>'),f=$('<div id="column-page-'+b+'" class="page"/>');$("#rooms-labels").append(d);$("#rooms-columns-container").append(f)}d='<div id="column-title-'+a+'" class="room-header ui-corner-all ui-state-default">'+e.id+"</div>";$("#title-page-"+b).append(d);d=$('<div id="column-'+a+'" class="room-body ui-widget" />');
$("#column-page-"+b).append(d);renderReservations(d,e,c)}):$("#rooms-columns-container").append('<div class="ui-state-error no-rooms"><span>No hay salas disponibles para el piso '+floor+"</span></div>")}
function displayPage(a){efecto="clip";a||(page>0?$("#title-page-"+(page-1)+", #column-page-"+(page-1)).hide(500):$("#title-page-"+(Math.ceil(data.rooms.length/5)-1)+", #column-page-"+(Math.ceil(data.rooms.length/5)-1)).hide(500));$("#title-page-"+page+", #column-page-"+page).show(500);page++;page>Math.ceil(data.rooms.length/5)-1&&(page=0)}function refreshPage(){$("#title-page-"+page+", #column-page-"+page).show(500)}
function flipPage(){data!=null&&data!=void 0&&(displayPage(firstFlip),firstFlip=!1)}function renderTime(a){$(".hours-body").html("");hora_actual=a;hora_actual.setMinutes(hora_actual.getMinutes()>=30?30:0,0,0);for(i=0;i<8;i++)minutos="30",hora_actual.getMinutes()==0&&(minutos="00"),clase="hours",i==0&&(clase+=" ui-state-highlight"),$(".hours-body").append('<div class="'+clase+'"><span>'+hora_actual.getHours()+":"+minutos+"</span></div>"),hora_actual.setMinutes(hora_actual.getMinutes()+30,0,0)}
function renderReservations(a,c,b){hora_actual=b;hora_base=hora_actual.getHours()*3600+(hora_actual.getMinutes()>=30?30:0)*60;$.each(c.reservations,function(c,b){duracion=(b.endHour-b.initHour)/1800;for(j=bloque=(b.initHour-hora_base)/1800;j<bloque+duracion;j++)j>=0&&j<8&&(html='<div class="reservation ui-state-error " style="top:'+j*62+'px;">',html+='<span class="clearfix reservation-title">'+b.description+"</span>",html+='<span class="reservation-content">'+b.requester+"</span>",html+="</div>",
a.append(html))})}function sleep(a){var c=new Date;for(c.setTime(c.getTime()+a);(new Date).getTime()<c.getTime(););};