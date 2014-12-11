<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Representa un espacio físico en el sistema
 *
 * @author David Andrés Manzano Herrera <damanzano>
 */
class PhysicalSpace {

    private $id;
    private $name;
    private $reservations = array();

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

    public function addReservation($reservation) {
        $this->reservations[$reservation->getId()] = $reservation;
    }

    /***
     * Muestra un objeto de esta clase en formato json
     * @return string Un objeto de esta clase en formato json.
     */
    public function jsonForm() {
        $jsonReservations = "";
        foreach ($this->reservations as $reservation) {
//            if ($reservation->getId() != null) {
//                if (($reservation->getPhysicalSpace() != null) && ($reservation->getPhysicalSpace() instanceof PhysicalSpace)) {
//                    echo "<p>Good reservation with: " . $reservation->getPhysicalSpace()->getId() . " physical space asigned </p>";
//                } else {
//                    echo "<p>Bad reservation - id: " . $reservation->getId() . "</p>";
//                }
//            }else{
//                echo "<p>Bad reservation in room: " . $this->id . "</p>";
//                print_r($reservation);
//                print_r($this->reservations);
//            }
            if($jsonReservations!=""){
                $jsonReservations.= ", ".$reservation->jsonForm();
            }else{
                $jsonReservations.= $reservation->jsonForm();
            }
        }
        $json = '{"id":"' . $this->id . '", "name":"' . utf8_encode($this->name) . '", "reservations":[' . $jsonReservations . ']}';
        return $json;
    }

}
