<?php

/**
 *   Clase 'modelo' que implementa el modelo de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la base de datos
 * en una capa especializada
 */
class modelo
{

    //Atributo que contendrá la referencia a la base de datos
    private $conexion;
    // Parámetros de conexión a la base de datos
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "bdusuarios";

    /**
     * Constructor de la clase que ejecutará directamente el método 'conectar()'
     */
    public function __construct()
    {
        $this->conectar();
    }

    /**
     * Método que realiza la conexión a la base de datos de usuarios mediante PDO.
     * Devuelve TRUE si se realizó correctamente y FALSE en caso contrario.
     * @return boolean
     */
    public function conectar()
    {
        try {
            $this->conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Función que nos permite conocer si estamos conectados o no a la base de datos.
     * Devuelve TRUE si se realizó correctamente y FALSE en caso contrario.
     * @return boolean
     */
    public function estaConectado()
    {
        if ($this->conexion) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * el código se asegura de que el usuario proporcionado sea válido y,
     * en caso afirmativo, inserta un registro de acceso en una tabla de la base de datos.
     */
    public function login($usu, $pass)
    {
        $parametros = [
            'correcto' => null,
            'datos' => [],
            'error' => null,
        ];

        try {
            $conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo $ex;
        }
        try {
            /*Se prepara una consulta a la base de datos para buscar un usuario con un nombre de usuario y una contraseña específicos.*/
            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE nick=(:usuario) AND password=(:password)");
            $consulta->setFetchMode(PDO::FETCH_ASSOC);
            $consulta->execute(array(":usuario" => $usu, ":password" => $pass));
            $parametros['datos'] = $consulta->fetch(PDO::FETCH_ASSOC);
            if (!empty($parametros['datos'])) { //Si se han recibido datos
                $parametros["correcto"] = true; //El mensaje será de exito
                $consulta = $conexion->prepare("INSERT INTO logs(usuario, fecha, operaciones) VALUES (:usuario, :fecha, :operaciones)"); //Insertamos en la tabla logs
                //Le pasamos los datos de la inserción a la tabla logs, cogemos el nick, la fecha del sistema y le decimos que la operación se va a llamar login
                $consulta->execute(array(':usuario' => $parametros['datos']['nick'], ':fecha' => date("d/m/Y"), ':operaciones' => 'Login de usuario'));
            } else {
                $parametros['correcto'] = false;
            }
        } catch (Exception $ex) {
            $parametros["error"] = $ex->getMessage();
        }
        return $parametros;

        $conexion = null;
    }

    /**
     * Función que realiza el listado de todos los usuarios registrados
     * Devuelve un array asociativo con tres campos:
     * -'correcto': indica si el listado se realizó correctamente o no.
     * -'datos': almacena todos los datos obtenidos de la consulta.
     * -'error': almacena el mensaje asociado a una situación errónea (excepción)
     * @return $parametros
     */
    public function listado()
    {
        $parametros = ['correcto' => null, 'datos' => [], 'error' => null];
        try {
            $conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo $ex;
        }
        try {
            $consulta = $conexion->prepare("SELECT * FROM usuarios");
            $consulta->setFetchMode(PDO::FETCH_ASSOC);
            $resultado = $consulta->execute();
            /**
             * Si la consulta se ejecuta correctamente, se almacenan los resultados en el
             * array $parametros["datos"] y se establece $parametros["correcto"] a true.
             * Si la consulta falla, se captura la excepción y se almacena un mensaje de error en $parametros["error"].
             */
            if ($resultado) {
                $parametros["correcto"] = true;
                $parametros['datos'] = $consulta->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $ex) {
            $parametros["error"] = $ex->getMessage();
        }
        return $parametros;
        $conexion = null; //Cierre de conexión
    }

    /**
     *
     * @param type $datos
     * @return type
     */
    public function adduser($datos, $usuario)
    {
        //Array que recogera los datos obtenidos de hacer las operaciones
        $parametros = [
            'correcto' => null,
            'tipo' => null,
            'mensaje' => null,
        ];

        try {
            $conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Comprobamos que no existe ya una cuenta de correo igual
            $consulta = $conexion->prepare("SELECT email FROM usuarios WHERE email=:correo");
            $consulta->execute(array(':correo' => $datos['email']));
            $correoExistente = $consulta->fetch(PDO::FETCH_ASSOC);

            //Si no existe, devuelve false y guardamos el nuevo usuario
            if (!$correoExistente) {
                $consulta = $conexion->prepare("INSERT INTO usuarios (id, nick,nombre, apellidos, email, password, imagen) VALUES (:id , :nick, :nombre, :apellidos, :email, :password, :imagen)");
                $resulConsulta = $consulta->execute(array(':id' => null, ':nick' => $datos['nick'], ':nombre' => $datos['nombre'], ':apellidos' => $datos['apellidos'], ':email' => $datos['email'], ':password' => $datos['password'], ':imagen' => $datos['imagen']));
                if ($resulConsulta) { //Si el resultado es correcto
                    $parametros['correcto'] = true;
                    $parametros['tipo'] = 'alert alert-access';
                    $parametros['mensaje'] = 'Usuario añadido correctamente'; //Mostramos mensaje de exito
                    $consulta = $conexion->prepare("INSERT INTO logs(usuario, fecha, operaciones) VALUES (:usuario, :fecha, :operaciones)"); //Insertamos en la tabla logs
                    //Le pasamos los datos de la inserción a la tabla logs, cogemos el nick, la fecha del sistema y le decimos que la operación se va a llamar Insercion de usuario
                    $consulta->execute(array(':usuario' => $parametros['datos']['nick'], ':fecha' => date("d/m/Y"), ':operaciones' => 'Inserción de usuario'));
                }
            } else {
                $parametros['correcto'] = false;
                $parametros['mensaje'] = 'Ya existe un usuario con el mismo correo';
            }
        } catch (Exception $ex) {
            $parametros['correcto'] = false;
            $parametros['tipo'] = 'alert alert-danger';
            $parametros['mensaje'] = $ex;
        }
        $conexion = null;
        return ($parametros);
    }

    /**
     * Función llamada listausuario que recibe un parámetro $id, y luego realiza una consulta 
     * a una base de datos MySQL para obtener todas las entradas que coinciden con el $id 
     * pasado como parámetro. Los resultados se almacenan en un array asociativo llamado $return,
     * y se devuelve como resultado de la función.
     */
    public function listausuario($id)
    {
        $return = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
        ];

        try {
            $conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo $ex;
        }

        try {
          //Listamos todos los usuarios que coincidan con el id pasado por parámtro y lo guardamos en la variable $consulta
            $consulta = $conexion->prepare("SELECT * FROM entradas WHERE id_usuario = $id");
            $resultado = $consulta->execute();
            /**
             * Si la consulta se ejecuta correctamente, se almacenan los resultados en el
             * array $parametros["datos"] y se establece $parametros["correcto"] a true.
             * Si la consulta falla, se captura la excepción y se almacena un mensaje de error en $parametros["error"].
             */
            if ($resultado) { 
                $parametros["correcto"] = true;
                $parametros['datos'] = $consulta->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $ex) {
            $parametros["error"] = $ex->getMessage();
        }
        $conexion = null; //Cerramos la conexión
        return $parametros; //Devolvemos los datos
    }

}
