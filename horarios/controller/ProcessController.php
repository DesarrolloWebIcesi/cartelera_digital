<?php

/**
 * Esta clase se encarga de recibir las solicitudes ajax de los clientes
 * y de transformar los objetos de modelo en ojbetos json que seran utilizados por la vista
 *
 * @author David Andrés Manzano Herrera - damanzano
 */
include_once '../model/ComputerRoom.php';
include_once '../model/Reservation.php';
include_once 'DAController.php';

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
ProcessController::main();

class ProcessController
{

  /**
   * Este es el método principal de este controlador, y es el que ejecuta automaticamente cueando se hace una solicitud al mismo. (Similaar el métod init() de los Servlets de JAVA)
   * 
   * @author damanzano
   * @since 2012-05-15
   * @version 1.1 aorozco Se modificó el método para que validara el parametro floor de que llaga por post.
   * @version 1.2 damanzano Se modificó la línea 31 la función is_int por is_numeric ya qie is_int sólo devuelve true si el typo de dato es entero
   * y lo que se busca es saber si la cadena que llega por post es un número.
   */
  public static function main()
  {
    $building = null;
    if (isset($_POST['building']) && $_POST['building'] != '' && is_numeric($_POST['building']))
    {
      $building = $_POST['building'];
    }
    if(isset($_GET['building'])){
      $building = $_GET['building'];
    }
      
    $floor = null;
    if (isset($_POST['floor']) && $_POST['floor'] != '' && is_numeric($_POST['floor']))
    {
      $floor = $_POST['floor'];
    }
    if(isset($_GET['floor'])){
      $floor = $_GET['floor'];
    }
    
    $rooms = DAController::getAllPhysicalSpaces($floor, $building);
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Content-type: application/json');
    echo self::jsonData($rooms);
  }

  /**
   * Este método transforma un arreglo de salas de computo en un objeto json que será posteriormente procesado por la vista
   * 
   * @param rooms Array El arreglo de ComputerRooms que se desea ver en formato JSON
   * @author damanzano
   * @since 2012-05-15
   * @since 2013-02-24
   * @version 1.1 damanzano Se comentó la linea 67 debido a que no cumplía ninguna funcionalidad y adicionalmente estaba generando errores 
   * en el servidor por el cambio de versión de php
   */
  private static function jsonData($rooms)
  {
    $jsonRooms = '';
//    echo '<pre>';
//    print_r($rooms);
//    echo '</pre>';
    foreach ($rooms as $room)
    {
        if($jsonRooms!=''){
            $jsonRooms.=",".$room->jsonForm();
        }else{
            $jsonRooms.=$room->jsonForm();
        }
    }
    return '{"rooms":[' . $jsonRooms . ']}';
  }

}

?>
