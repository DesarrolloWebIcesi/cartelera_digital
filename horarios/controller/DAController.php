<?php

require_once '../model/Reservation.php';
require_once '../model/PhysicalSpace.php';
require_once '../../commons/services/OracleServices.php';

/**
 * Controlador de acdeso a datos
 *
 * @author David Andrés Manzano Herrera - damanzano
 * @version 1.2 2014-08-29 La clase ComputerRoom se reemplaza por PhysicalSpace
 */
class DAController {

    private static $reservations = array();
    private static $computerRooms = array();
    private static $physicalSpaces = array();

    /**
     * Este método obtiene el array de reservaciones de salas de cómputo
     * @param pFloor string Piso del cual se desean consultar las reservas
     * @return array Retorna un arreglo con las reservaciones de salas de computo realizadas para el piso pasado por parametro.
     * @see DAController::fillData()
     * @author damanzano
     * @since 2012-5-15
     * @version 1.1 aorozco 2012-05-23 Se agregó parámetro para seleccionar el piso
     */
    public static function getReservations($pFloor = null) {
        if (self::$reservations == null || empty(self::$reservations)) {
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
    public static function getComputerRooms($pFloor = null) {
        if (self::$computerRooms == null || empty(self::$computerRooms)) {
            self::fillData($pFloor);
        }
        return self::$computerRooms;
    }

    /**
     * Este método obtiene el array de todos los espacios físicos registradas en el sistema, 
     * para un edificio y un piso en particular. Si los parámetros pFlorr y pBuilding son nulos se retornan todos los espacios existentes 
     * @param pFloor string Piso del cual se desean consultar las salas.
     * @return array Retorna un arreglo con las salas de computo en el piso pasado por parametro.
     * @see DAController::fillData()
     * @author damanzano
     * @since 2014-08-29
     */
    public static function getAllPhysicalSpaces($pFloor = null, $pBuilding = null) {
        if (self::$physicalSpaces == null || empty(self::$physicalSpaces)) {
            //echo "There are no physical space fecthcing data from database <br/>";
            self::fillData($pFloor, $pBuilding);
        }
        return self::$physicalSpaces;
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
    public static function getReservationsByRoom($roomId) {
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
          return $filteredReservations; */
    }

    /**
     * Este método va  a la base datos llena todos los datos de consulta, es el primer método que se debe llamar al utilizar esta objeto.
     * @param pFloor string Piso del cual se desean consultar las salas.
     * @author damanzano
     * @since 2012-5-15
     * @version 1.1 2012-05-23 aorozco Se agregó parámetro para seleccionar el piso
     * @version 1.2 2014-08-29 damanzano Se agregó el parámetro para seleccionar el edificio.
     */
    private static function fillData($pFloor = null, $pBuilding = null) {
        $dataBase = new OracleServices("../config/.config");
        if ($dataBase->conectar()) {
            //1.Obtain the avaiable physical spaces
            //$pBuilding = mysql_real_escape_string($pBuilding);
            //$pFloor = mysql_escape_string($pFloor);
            $sqlQuery = "SELECT codigo, descripcion
                  FROM tbas_espacios_fisic
                  WHERE estado  = 'A'
                  AND codigo NOT LIKE 'SCM%' 
                  AND codigo <> 'NIN'
                  AND codigo <> 'PEN'";

            if ($pBuilding != null) {
                $sqlQuery .= "AND bloque = $pBuilding ";
                if ($pFloor != null) {
                    $sqlQuery .= "AND piso = $pFloor ";
                }
            }

            $sqlQuery .= "ORDER BY bloque, piso, codigo";
            //echo $sqlQuery;
            $queryId = $dataBase->ejecutarConsulta($sqlQuery);
            $selectedRooms = "";

            while ($dataBase->siguienteFila($queryId)) {
                $physicalSpace = new PhysicalSpace();
                $physicalSpace->setId($dataBase->dato($queryId, 1));
                $physicalSpace->setName($dataBase->dato($queryId, 2));
                self::$physicalSpaces[$physicalSpace->getId()] = $physicalSpace;
                if ($selectedRooms != "") {
                    $selectedRooms.=",'" . $dataBase->dato($queryId, 1) . "'";
                } else {
                    $selectedRooms.="'" . $dataBase->dato($queryId, 1) . "'";
                }
            }
            //print_r(self::$physicalSpaces);
            //2.Obtain the reservations only if there are rooms for the building and floor selected
            if (count(self::$physicalSpaces) > 0) {
                //echo "Fetching reservation for selected physical space <br/>";
                $sqlQuery = "select trunc(dia) dia_reserva,sala,hora,
                  descripcion||decode(reserva,'S',decode(descripcion,null,'.')) descripcion,
                  video_beam,reserva,inicio_res,fin_res
                  from TTEM_RES_GRP_DIA
                  where usuario = 'cvirtual'";
                
                $sqlQuery .= "and sala in (" . $selectedRooms . ")";


                $sqlQuery .= "order by sala, hora";
                //echo $sqlQuery;
                $queryId = $dataBase->ejecutarConsulta($sqlQuery);

                $reservation = null;
                while ($dataBase->siguienteFila($queryId)) {
                    $reservedRoom = self::$physicalSpaces[$dataBase->dato($queryId, 2)];
                    //echo "<p>Room: ".$dataBase->dato($queryId, 2)."<p>";
                    $isReservation = $dataBase->dato($queryId, 6);
                    $reservetionInit = $dataBase->dato($queryId, 7);
                    $reservationEnd = $dataBase->dato($queryId, 8);
                    $description = $dataBase->dato($queryId, 4);
                    $time = $dataBase->dato($queryId, 3);

                    if (($isReservation == "S" && $reservetionInit == "S") || ($isReservation == "S" && $time == 25200)) {
                        //echo "New reservation found, starting new reservation object instance <br/>";
                        $reservation = new Reservation();
                        $reservation->setId($dataBase->dato($queryId, 2) . "-" . $time);
                        $reservation->setDescription($description);
                        $reservation->setInitHour($time);
                        $reservation->setPhysicalSpace($reservedRoom);
                    }
                    if ($reservation != null && $isReservation == "S" && $description != ".") {
                        $reservation->addRequestData($description);
                    }
                    if ($reservation != null && $isReservation == "S" && $reservationEnd == "S") {
                        //echo "End of reservation found adding this reservation to physical space: ".$reservedRoom->getId()." <br/>";
                        $reservation->setEndHour($time + 1800);
                        $reservedRoom->addReservation($reservation);
                        self::$reservations[] = $reservation;
                        $reservation = null;
                    }
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
    public static function computerRoomById($roomId) {
        return self::$ComputerRooms[$roomId];
    }

}

?>
