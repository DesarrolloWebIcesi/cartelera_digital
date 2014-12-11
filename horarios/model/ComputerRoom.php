<?php
require_once 'PhysicalSpace.php';
require_once 'Reservation.php';
/**
 * Representa una Sala de computo
 *
 * @author David Andrés Manzano Herrera - damanzano
 */
class ComputerRoom extends PhysicalSpace{
    private $id;
    private $name;
    private $reservations=array();
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getReservations() {
        return $this->reservations;
    }

    public function setReservations($reservations) {
        $this->reservations = $reservations;
    }
    
    public function addReservation($reservation){
        $this->reservations[$reservation->getId()]=$reservation;                
    }
    
    public function jsonForm(){
        $jsonReservations="";
        foreach ($this->reservations as $reservation) {
            $jsonReservations.=$reservation->jsonForm().',';
        }
        $jsonReservations=  substr($jsonReservations, 0, strlen($jsonReservations)-1);
        
        $json='{"id":"'.$this->id.'", "name":"'.htmlentities($this->name).'", "reservations":['.$jsonReservations.']}';
        return $json;
    }

}

?>
