<?php
require_once 'PhysicalSpace.php';
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
    private $physicalSpace;
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

    public function getPhysicalSpace() {
        return $this->physicalSpace;
    }

    public function setPhysicalSpace($physicalSpace) {
        $this->physicalSpace = $physicalSpace;
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
        $requester = '-';
        if(count($this->requestData)>1){
            $requester = htmlentities(addslashes(trim(preg_replace(str_replace(':',' ',$this->requestData[1])))));
        }
        $json='{"id":"'.$this->id.'", "description":"'.htmlentities(addslashes(trim(preg_replace('/\s+/', ' ', $this->description)))).'", "initHour":"'.$this->initHour.'", 
            "endHour":"'.$this->endHour.'", "computerRoom":"'.$this->physicalSpace->getId().'", "requester":"'.$requester.'"}';
            //"endHour":"'.$this->endHour.'", "requester":"'.htmlentities(str_replace(':',' ',$this->requestData[1])).'"}';
        return $json;
    }
}

?>
