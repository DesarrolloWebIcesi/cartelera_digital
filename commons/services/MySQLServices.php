<?php
/**
 * Description of MySQLServices
 * Clase encargada de prestar diferentes servicios con bases de datos MySQL
 *
 * @author David Andrés Manzano Herrera - damanzano
 */
class MySQLServices {
    /**
     * @var $host Hostname o IP del servidor de la base de datos
     */
    private $host;
    /**
     * @var $user Usuario de la base de datos
     */
    private $user;
    /**
     * @var $pass Password del usuatio de la base de datos
     */
    private $pass;
    /**
     * @var $bd Nombre de la base de datos a la cual se va a conectar
     */
    private $db;
    /**
     * @var $myconn Instacia de conexión a la base de datos
     */
    private $myconn;    
    /**
     * @var $error almacena los errores que se hayan podido generar al realizar alguna de las transanciones en la base de datos.
     */
    private $error;

    /**
     * Contructor de la clase
     * Construye un nuevo objeto MySQLServices
     */
     public function MySQLServices($connectionFile){
         $this->setDatosConexion($connectionFile);
     }
     
    /**
     * Lee un archivo de texto con los datos de conexión a la base de datos.
     * El formato del archivo de configuración debe ser el siguiente:
     *
     * @method setDatosConexion($connectionFile) se ejecuta en el momento de crear una nueva instancia
     * de la clase.
     * @author damanzano
     * @param string $connectionFile ruta del archivo de configuración
     */
    private function setDatosConexion($connectionFile){
        $fileData=file($connectionFile);
        $this->host=substr($fileData[0],0,strlen($fileData[0]) - 2);
        $this->user=substr($fileData[1],0,strlen($fileData[1]) - 2);
        $this->pass=substr($fileData[2],0,strlen($fileData[2]) - 2);
        $this->db=substr($fileData[3],0,strlen($fileData[3]) - 2);
    }
    
    /**
     * Se conecta a una base de datos MySQL de acuerdo a los parametros proporcionados en
     * MySQLServices->setConectionData()
     *
     * @method conectar()
     * @author damanzano
     * @see setDatosConexion()
     */
    public function conectar(){
        $this->myconn = @mysql_connect($this->host,$this->user,$this->pass);
        if (! $this->myconn){
            $this->error= "Error tratando de conectar al servidor de base de datos<br>".mysql_error($this->myconn);
            return false;
        }
        if (! @mysql_select_db($this->db,$this->myconn)){
            $this->error="Error tratando de conectar a la base de datos<br>".mysql_error($this->myconn);
            //echo $this->error;
            return false;
        }

        return true;
    }
    
    /**
     * Este método se usa para desconectrase de la base de datos
     *
     * @method desconectar()
     * @author damanzano
     */
    public function desconectar(){
        if(mysql_close($this->myconn)){
                return true;
        }else{
            $this->error="No es posible desconectarse de la base de datos<br>".mysql_error($this->myconn);
            die($this->error);
        }
    }

    /**
     * Ejecuta la consulta pasada por paranetro sobre la base de datos y retorna los resultados
     * de la consulta.
     *
     * @method ejecutarConsulta()
     * @author damanzano
     * @param string $sql Consulta que se desea consultar
     */
    public function ejecutarConsulta($sql){
        $results = mysql_query($sql,$this->myconn);

        if (!$results){
            $this->error="Error al ejecutar la consulta<br>".mysql_error($this->myconn);
            die($this->error);
        }
        return $results;
    }
    
    /**
     * Retorna el número de filas o registros de una consulta
     *
     * @author damanzano
     * @param int $id_sql Identificador de la consulta.
     *
     */
    public function numFilas ($id_sql){
        if($id_sql !=0){
            return mysql_num_rows($id_sql);
        }
        return 0;
    }

    /**
     * Se retornar el n&uacute;mero de filas afectadas de las operaciones: INSERT, DELETE y UPDATE
     *
     * @author damanzano    
     */
    public function filasAfectadas ()
    {
        $numFilasAfectadas=mysql_affected_rows($this->myconn);
        return $numFilasAfectadas;
    }

    /**
     * Busca la siguiente fila (para sentencias SELECT) dentro del statement proporcionado por parametro
     * @autor   damanzano
     * @since   22/10/10
     *
     * @param int $id_sql Identificador de la consulta.
     * @return  La siguiente fila de un set de resultados arrojados por una consulta previamemte ejecutada,
     * como un arreglo enumerado (0-base).
     */
    public function siguienteFila($id_sql) {
        return mysql_fetch_array($id_sql,MYSQL_BOTH);
    }
    
    /**
     * Devuelve el valor de la columna $campo de la fila actual como una cadena
     *
     * @author  damanzano
     * @since   22/10/10
     *
     * @param int $id_sql Identificador de la consulta.
     * @param mixed $campo Puede ser la posici&oacute;n de la columna (1-Based) o su nombre en mayúsculas
     * @return  $this->dat
     */
    public function dato($id_sql, $campo) {
        //return ociresult($id_sql, $campo);
    }

    /**
     * Confirma todas las sentencias pendientes para la conexión
     * @author damanzano
     * @since 22/10/10
     */
    public function commit() {
        //oci_commit($this->myconn);
    }

    /**
     * Restablece todas las transacciones sin confirmar para la conexión
     * @author  damanzano
     * @since 22/10/10
     */
    public function rollback() {
        //oci_rollback($this->myconn);
    }
    /**
     * Se encarga de liberar la memoria utilizada por una consulta
     *
     * @author damanzano
     * @param int $id_sql Identificador de la consulta.
     */
    public function liberarMemoria ($id_sql)
    {
        mysql_free_result($id_sql);
    }
    
    /*Getters*/
    public function getHost() {
        return $this->host;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getDb() {
        return $this->db;
    }

    public function getMyconn() {
        return $this->myconn;
    }    

    public function getError() {
        return $this->error;
    }

}
?>
