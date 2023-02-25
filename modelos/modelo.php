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

    public function getCategorias()
    {
        $parametros = [
            'correcto' => null,
            'error' => null,
            'categorias' => [],
        ];

        try {
            $conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo $ex;
        }
        //ahora nos traemos los nombres de las categorias para mostrarlas en el formulario de nueva entrada
        try {
            $consulta = $conexion->prepare("SELECT * FROM categorias");
            $resultado = $consulta->execute();

            if ($resultado) {
                $parametros['categorias'] = $consulta->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $parametros['correcto'] = false;
            }
        } catch (Exception $ex) {
            $parametros['error'] = $ex;
        }

        return $parametros;
        $conexion = null;
    }

    /**
     * Agrega una nueva datos a la base de datos y registra la operación en una tabla de registros (logs).
     */
    public function agregaEntrada($datos, $id, $usuario)
    {
        $parametros = [
            'correcto' => null,
            'error' => null,
        ];
        try {
            $conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Preparamos la consulta
            $consulta = $conexion->prepare("INSERT INTO datoss (id,usuario_id,categoria_id,titulo,imagen,descripcion)
                                                VALUES (:id, :usuario_id, :categoria_id, :titulo, :imagen, :descripcion)");
            //La ejecutamos
            $consulta->execute(array(':id' => $datos['id'], ':usuario_id' => $datos['usuario_id'], ':categoria_id' => $datos['categoria_id'], ':titulo' => $datos['titulo'],
                ':imagen' => $datos['imagen'], ':descripcion' => $datos['descripcion']));

            //Insertamos en la tabla logs
            $consulta = $conexion->prepare("INSERT INTO logs(usuario, fecha, operaciones) VALUES (:usuario, :fecha, :operaciones)");
            //Le pasamos los datos de la inserción a la tabla logs, cogemos el nick, la fecha del sistema y le decimos que la operación se va a llamar datos nueva
            $consulta->execute(array(':usuario' => $parametros['datos']['nick'], ':fecha' => date("d/m/Y"), ':operaciones' => 'Entrada nueva'));
        } catch (Exception $ex) {
            $parametros['correcto'] = false;
            $parametros['error'] = $ex;
        }
        return $parametros;
        $conexion = null;
    }

    /**
     * Método que elimina la entrada la cual se reciben sus datos por parámetros
     */
    public function delEntrada($datos, $id, $usuario)
    {
        $parametros = [
            'tipo' => null,
            'mensaje' => null,
            'correcto' => null,
        ];

        try {
            //Conectamos con la bd
            $conexion = new PDO("mysql:host=$this->host;dbname=$this->baseDatos", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Preparamos sentencia y la ejecutamos
            $consulta = $conexion->prepare("DELETE FROM entradas WHERE id = $datos");
            $resultado = $consulta->execute();
            if ($resultado) {
                $parametros['correcto'] = true;
                $parametros['tipo'] = 'alert alert-success text-center';
                $parametros['mensaje'] = 'La categoría se ha eliminado';
                $consulta = $conexion->prepare("INSERT INTO logs(tipo, usuario, nombre) VALUES (:tipo, :usuario,:nombre)");
                $consulta->execute(array(':tipo' => 'elimina entrada', ':usuario' => $id, ':nombre' => $usuario));
            } else {
                $parametros['correcto'] = false;
                $parametros['tipo'] = 'alert alert-danger text-center';
                $parametros['mensaje'] = 'No se ha eliminado la categoría seleccionada';
            }
        } catch (Exception $ex) {
            $parametros['correcto'] = false;
            $parametros['tipo'] = 'alert alert-danger text-center';
            $parametros['mensaje'] = $ex;
        }
        $conexion = null;
        return $parametros;
    }

    /**
     * Método que actualiza la entrada recibiendo los datos por parámetro
     */
    public function modEntrada($datos, $id, $usuario)
    {
        $parametros = [
            'correcto' => null,
            'mensaje' => null,
        ];

        try {
            $conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $consulta = $conexion->prepare("UPDATE datoss SET titulo = :titulo, imagen = :imagen, descripcion = :descripcion,
                                                    fecha = :fecha WHERE id = :id");

            $resultado = $consulta->execute(array(':titulo' => $datos['titulo'], ':imagen' => $datos['imagen'], ':descripcion' => $datos['descripcion'],
                ':fecha' => $datos['fecha'], ':id' => $datos['id']));

            if ($resultado) {
                $parametros['correcto'] = true;
                $parametros['mensaje'] = 'Entrada actualizada';
                $consulta = $conexion->prepare("INSERT INTO logs(tipo, usuario, nombre) VALUES (:tipo, :usuario,:nombre)");
                $consulta->execute(array(':tipo' => 'modifica datos', ':usuario' => $id, ':nombre' => $usuario));
            }else{
                $parametros['correcto'] = false;
                $parametros['mensaje'] = 'No se ha podido actualizar la entrada';
            }

        } catch (Exception $ex) {
            $parametros['correcto'] = false;
            $parametros['mensaje'] = $ex;
        }
        $conexion = null;
        return $parametros;
    }

}
