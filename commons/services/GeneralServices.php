<?php

/**
 * Description of GeneralServices
 * Esta clase ofrece servicios de tipo general para su uso.
 *
 * @author David Andrés Manzano - damanzano
 */
class GeneralServices {
    /**
     * Retorna el valor entero de la cadena pasada por parametro. Si la cadena no reprensenta un número entero
     * el método returnará 0
     *
     * @author damanzano
     * @param string $string Cadena a convertir.
     */
    public static function parseInt($string) {
        // return intval($string);
        if (preg_match('/(\d+)/', $string, $array)) {
            return $array[1];
        } else {
            return 0;
        }
    }

    /**
     * Retorna el indicen en el que se encuentra el objeto buscado dentro del arreglo, -1 si el objeto no se encuntra
     *
     * @author damanzano
     * @param string $object la cadena a buscar sentro del arreglo.
     * @param Array $array arreglo en el que se va a buscar.
     */
    public static  function get_array_index($object,$array){}

}

?>
