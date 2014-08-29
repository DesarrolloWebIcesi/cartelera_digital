<?php

require_once '../model/Reservation.php';
require_once '../model/ComputerRoom.php';
require_once '../../commons/services/OracleServices.php';

/**
 * Controlador de acdeso a datos
 *
 * @author David Andrés Manzano Herrera - damanzano
 */
class DAController
{

  private static $reservations = array();
  private static $computerRooms = array();

  /**
   * Este método obtiene el array de reservaciones de salas de cómputo
   * @param pFloor string Piso del cual se desean consultar las reservas
   * @return array Retorna un arreglo con las reservaciones de salas de computo realizadas para el piso pasado por parametro.
   * @see DAController::fillData()
   * @author damanzano
   * @since 2012-5-15
   * @version 1.1 aorozco 2012-05-23 Se agregó parámetro para seleccionar el piso
   */
  public static function getReservations($pFloor = null)
  {
    if (self::$reservations == null || empty(self::$reservations))
    {
      self::fillData($pFloor);
    }
    return self::$reservations;
  }
  
  
  /**
   * Este método obtiene el array de salas de cómputo registradas en el sistema
   * @param pFloor string Piso del cual se desean consultar las salas.
   * @return array Retorna un arreglo con las salas de computo en el piso pasado por parametro.
   * @see DAController::fillData()
   * @author damanzano
   * @since 2012-5-15
   * @version 1.1 aorozco 2012-05-23 Se agregó parámetro para seleccionar el piso
   */
  public static function getComputerRooms($pFloor = null)
  {
    if (self::$computerRooms == null || empty(self::$computerRooms))
    {
      self::fillData($pFloor);
    }
    return self::$computerRooms;
  }

  /**
   * Este método trae todas las reservaciones realizadas en una sala de computo
   * 
   * @param roomId string Código de la sala de cómputo para la que se hace la consulta.
   * @return array Retorna un arreglo con las reservas realizadas sobre la sala identificada con el código pasado por parametro.
   * @author damanzano
   * @since 2012-5-15
   * @version 1.1 2012-05-24 damanzano se modicó el método ya que tuviera que recorrer todo el arreglo de reservaciones.
   */
  public static function getReservationsByRoom($roomId)
  {
    return self::$ComputerRooms[$roomId]->getReservations();
    /*
     * @version 1.0
    $filteredReservations = array();
    foreach (self::$reservations as $reservation)
    {
      if ($reservation->getComputerRoom()->getId() == $roomId)
      {
        $filteredReservations[] = $reservation;
      }
    }
    return $filteredReservations;*/
  }

  /**
   * Este método va  a la base datos llena todos los datos de consulta, es el primer método que se debe llamar al utilizar esta objeto.
   * @param pFloor string Piso del cual se desean consultar las salas.
   * @author damanzano
   * @since 2012-5-15
   * @version 1.1 2012-05-23 aorozco Se agregó parámetro para seleccionar el piso
   */
  private static function fillData($pFloor = null)
  {
    $dataBase = new OracleServices("../config/.config");
    if ($dataBase->conectar())
    {
      //1.Obtain the avaiable computer romms
      $pFloor = mysql_escape_string($pFloor);
      $sqlQuery = "SELECT codigo, descripcion
                  FROM tbas_espacios_fisic
                  WHERE tipo IN ('C')
                  AND estado  = 'A'
                  AND codigo NOT LIKE 'SCM%' ";

      if ($pFloor != null)
      {
        $sqlQuery .= "AND codigo LIKE '$pFloor%' ";
      }

      $sqlQuery .= "ORDER BY codigo";
      //echo $sqlQuery;
      $queryId = $dataBase->ejecutarConsulta($sqlQuery);

      while ($dataBase->siguienteFila($queryId))
      {
        $computerRoom = new ComputerRoom();
        $computerRoom->setId($dataBase->dato($queryId, 1));
        $computerRoom->setName($dataBase->dato($queryId, 2));
        self::$computerRooms[$computerRoom->getId()] = $computerRoom;
      }
      //print_r(self::$computerRooms);
      
      //2.Obtain the reservations
      $sqlQuery = "select trunc(dia) dia_reserva,sala,hora,
                  descripcion||decode(reserva,'S',decode(descripcion,null,'.')) descripcion,
                  video_beam,reserva,inicio_res,fin_res
                  from TTEM_RES_GRP_DIA
                  where usuario = 'cvirtual' ";
      if ($pFloor != null)
      {
        $sqlQuery .= "AND sala LIKE '$pFloor%' ";
      }
      $sqlQuery .= "order by sala, hora";
      //echo $sqlQuery;
      $queryId = $dataBase->ejecutarConsulta($sqlQuery);

      $reservation = new Reservation();
      while ($dataBase->siguienteFila($queryId))
      {
        $reservedRoom = self::$computerRooms[$dataBase->dato($queryId, 2)];
        $isReservation = $dataBase->dato($queryId, 6);
        $reservetionInit = $dataBase->dato($queryId, 7);
        $reservationEnd = $dataBase->dato($queryId, 8);
        $description = $dataBase->dato($queryId, 4);
        $time = $dataBase->dato($queryId, 3);

        if (($isReservation == "S" && $reservetionInit == "S") || ($isReservation == "S" && $time == 25200))
        {
          $reservation->setId($dataBase->dato($queryId, 2) . "-" . $time);
          $reservation->setDescription($description);
          $reservation->setInitHour($time);
          $reservation->setComputerRoom($reservedRoom);
        }
        if ($isReservation == "S" && $description != ".")
        {
          $reservation->addRequestData($description);
        }
        if ($isReservation == "S" && $reservationEnd == "S")
        {
          $reservation->setEndHour($time + 1800);
          $reservedRoom->addReservation($reservation);
          self::$reservations[] = $reservation;
          $reservation = new Reservation();
        }
      }
      $dataBase->desconectar();
    }
  }

  /**
   * Este método busca una sala de computo con el identificador dado por parametro y la retorna.
   * 
   * @param roomId string Código de de sala de cómputo para la que se hace la consulta.
   * @return ComputerRoom Sala de cómputo identificada con el código pasado por parametro.
   * @author damanzano
   * @since 2012-5-16
   */
  public static function computerRoomById($roomId)
  {
    return self::$ComputerRooms[$roomId];
  }

}

?>
