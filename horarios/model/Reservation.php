<?php
require_once 'ComputerRoom.php';
/**
 * Representa una reservación realizada sen una sala de computo.
 *
 * @author David Andrés Manzano Herrera - damanzano
 */
class Reservation {
    private $id;
    private $initHour;
    private $endHour;
    private $description;    
    private $computerRoom;
    private $requestData=array();
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getInitHour() {
        return $this->initHour;
    }

    public function setInitHour($initHour) {
        $this->initHour = $initHour;
    }

    public function getEndHour() {
        return $this->endHour;
    }

    public function setEndHour($endHour) {
        $this->endHour = $endHour;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getComputerRoom() {
        return $this->computerRoom;
    }

    public function setComputerRoom($computerRoom) {
        $this->computerRoom = $computerRoom;
    }
    
    public function getRequestData() {
        return $this->requestData;
    }

    public function setRequester($requestData) {
        $this->requestData = $requestData;
    }
    
    public function addRequestData($data){
        $this->requestData[]=$data;
    }
    
    public function jsonForm(){
        $json='{"id":"'.$this->id.'", "description":"'.htmlentities(addslashes(trim(preg_replace('/\s+/', ' ', $this->description)))).'", "initHour":"'.$this->initHour.'", 
            "endHour":"'.$this->endHour.'", "computerRoom":"'.$this->computerRoom->getId().'", "requester":"'.htmlentities(str_replace(':',' ',$this->requestData[1])).'"}';
        return $json;
    }
}

?>