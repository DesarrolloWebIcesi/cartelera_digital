<?php
/**
 * Página inicial de la aplicación horarios del sistema Cartelera Virtual
 *
 * @author David Andrés Manzano - damanzano
 * @since 2012-05-15
 *
 * @method Este aplicación funcioonara bajo la url http://www.icesi.edu.co/cartelera_virtual/horarios?view=XXX
 * donde XXX corresponde al la vista desde la cual se vea la aplicación.
 * 
 * Inicalmente la aplicación se piensa desplegar sobre las pantallas de los pasiilos, sin embargo,
 * se ofrece una vista adicional para aquellas personas que quieran consultarla desde su PC. Los códigos que se deben
 * pasar a la aplicación en el parametro view son:
 * TV Para las pantallas
 * PC para los equipos normales
 * 
 * Debido a que pueden ser más los equipos de computo que consulten la aplicación que las pantallas en las que se 
 * despliegue, la vista por defecto será PC 

 */
include_once 'Config.php';

/* error_reporting(E_ALL);
  ini_set('display_errors', '1'); */
setlocale(LC_TIME, 'es_CO');
$view = "PC";
$floor = null;
if (isset($_GET['view']) && $_GET['view'] != null)
{
  $view = $_GET['view'];
}
if (isset($_GET['floor']) && $_GET['floor'] != null)
{
  $floor = $_GET['floor'];
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta HTTP-EQUIV="Pragma" CONTENT="no-cache" />
    <meta HTTP-EQUIV="Expires" CONTENT="-1" />
    <title>Horario de Salas de computo</title>
    <meta name="description" content=""/>
    <meta name="author" content=""/>

    <meta name="viewport" content="width=device-width"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/start/jquery-ui-1.8.20.custom.css" />
    <!--<link rel="stylesheet" type="text/css" href="css/jquery.weekcalendar.css" />-->
    <script src="js/libs/modernizr-2.5.3.min.js"></script>
  </head>
  <body>
    <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
   chromium.org/developers/how-tos/chrome-frame-getting-started -->
    <!--[if lt IE 7]>
        <p class=chromeframe>
            Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.
        </p>
    <![endif]-->
    <header><h1 class="ui-state-active ui-widget-header">Programaci&oacute;n de salas de c&oacute;mputo - <?php echo utf8_encode(strftime('%A %d de %B de %Y', time())) ?></h1></header>
    <div role="main" id="main">
      <div id="calendar" class="ui-widget ui-corner-all">
        <div id="header-container" class="clearfix">
          <div class="hours-label">
            <div class="hours-header ui-state-active ui-corner-all">Hora</div>
          </div>
          <div id="rooms-labels" class="room-container">
            <div class="room-header ui-corner-all ui-state-default" id="column-title-1"></div>
            <div class="room-header ui-corner-all ui-state-default" id="column-title-2"></div>
            <div class="room-header ui-corner-all ui-state-default" id="column-title-3"></div>
            <div class="room-header ui-corner-all ui-state-default" id="column-title-4"></div>
            <div class="room-header ui-corner-all ui-state-default" id="column-title-5"></div>
          </div>
        </div>
        <div id="body-container" class="clearfix">
          <div id="hours-container" class="hours-label">
            <div class="hours-body ui-widget">

            </div>
          </div>
          <div id="rooms-columns-container" class="room-container">
            <div id="highlight" class="ui-state-highlight"></div>
            <div class="room-body ui-widget" id="column-1"></div>
            <div class="room-body ui-widget" id="column-2"></div>
            <div class="room-body ui-widget" id="column-3"></div>
            <div class="room-body ui-widget" id="column-4"></div>
            <div class="room-body ui-widget" id="column-5"></div>
          </div>
        </div>
      </div>
    </div>
    <div id="conventions" class="ui-widget ui-corner-all ui-state-highlight">
      Convenciones: <span class="ui-state-error">Ocupado</span> | <span class="available">Disponible</span>
    </div>

    <!-- JavaScript at the bottom for fast page loading -->

    <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>
    <script src="js/libs/jquery-ui-1.8.20.custom.min.js"></script>
    <script src="js/libs/date.js"></script>

    <!-- Needed for the bussines scripts -->
    <script>
      var floor;
      <?php if ($floor != null){ ?>
      floor = '<?php echo $floor; ?>';
      <?php } ?>
    </script>

    <!-- scripts concatenated and minified via build script -->
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>
    <!-- end scripts -->

    <!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID.
         mathiasbynens.be/notes/async-analytics-snippet -->
    <script>
      var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
      (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
  </body>
</html>
