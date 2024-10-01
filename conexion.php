<?php



define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'new_password');
define('DB_DATABASE', 'asesoria_asesoria');


class Conexion {
    /*Variables de Conexion*/
    private $baseDatos;
    private $servidor;
    private $usuario;
    private $clave;
    private $conexionID = 0;
    private $consultaID = 0;

    /*Numero de error y texto de error*/
    public $Errno = 0;
    public $Error = '';

    private $posFilaActual = 0;

    function __construct() {
        $this->baseDatos = DB_DATABASE;
        $this->servidor = DB_HOST;
        $this->usuario = DB_USER;
        $this->clave = DB_PASSWORD;
        $this->conectar ();
    }

    /*Conexion a la base de datos*/
    private function conectar() {
        //Conectamos al servidor
        $this->conexionID = mysql_connect($this->servidor, $this->usuario, $this->clave );
        if (! $this->conexionID) {
            $this->Error = 'Ha fallado la conexion';
            return 0;
        }
        //Seleccionamos la base de datos
        if (! mysql_select_db ( $this->baseDatos, $this->conexionID )) {
            $this->Error = 'Imposible abrir la base de datos';
            return 0;
        }
        /*Si hemos tenido exito conectando devuelve el identificador de la conexion*/
        return $this->conexionID;
    }
   
    /*Ejecuta una consulta*/
    public function consultar($sql = '') {
        $this->posFilaActual = 0;
        if ($sql == '') {
            $this->Error = 'No ha especificado la consulta SQL';
            return 0;
        }
        //Ejecutamos la consulta
        $this->consultaID = mysql_query ( $sql, $this->conexionID );
        if (! $this->consultaID) {
            $this->Errno = 0; //mysql_errono();
            $this->Error = 'Hay un error'; //mysql_error();
            return 0;
        }
        $this->Errno = 1;
        return $this->consultaID;
    }

    public function esValida(){
        if($this->consultaID)
                return true;
        return false;
    }

    /*Numero de campos de una consulta*/
    public function numCampos() {
        return mysql_num_fields ( $this->consultaID );
    }

    /*Numero de registros de una consulta*/
    public function numRegistros() {
        return mysql_num_rows ( $this->consultaID );
    }

    /*Numero de un campo de una consulta*/
    public function nombreCampo($numCampo) {
        return mysql_field_name ( $this->consultaID, $numCampo );
    }

    public function obtenerFilaArray() {
        $row = mysql_fetch_row ( $this->consultaID );
        $this->posFilaActual = $this->posFilaActual+1;
        return $row;
    }

    public function obtenerFila() {
        $row = mysql_fetch_array($this->consultaID );
        $this->posFilaActual = $this->posFilaActual+1;
        return $row;
    }

    public function hayDatos() {
        if($this->Errno==1) {
            if($this->posFilaActual < $this->numRegistros() )
                return true;
        }
        return false;
    }
} //class

//Start session
//session_start();

//instancia la clase
$conex = new Conexion();

//Connect to mysql server
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
        die('Fallo la conexion con el servidor: ' . mysql_error());
}

//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
        die("No ha sido seleccionada la base de datos  <a href='index.php'>Inicio</a>");
        
}

mysql_set_charset('utf8');

?>
