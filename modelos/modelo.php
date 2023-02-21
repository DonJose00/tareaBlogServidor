<?php

/**
 *   Clase 'modelo' que implementa el modelo de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la base de datos
 * en una capa especializada
 */
class modelo {

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
  public function __construct() {
    $this->conectar();
  }

  /**
   * Método que realiza la conexión a la base de datos de usuarios mediante PDO.
   * Devuelve TRUE si se realizó correctamente y FALSE en caso contrario.
   * @return boolean
   */
  public function conectar() {
    try {
      $this->conexion = new PDO("mysql:host=$this->host;port=3307;dbname=$this->db", $this->user, $this->pass);
      $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return TRUE;
    } catch (PDOException $ex) {
      return $ex->getMessage();
    }
  }

  /**
   * Función que nos permite conocer si estamos conectados o no a la base de datos.
   * Devuelve TRUE si se realizó correctamente y FALSE en caso contrario.
   * @return boolean
   */
  public function estaConectado() {
    if ($this->conexion) :
      return TRUE;
    else :
      return FALSE;
    endif;
  }

  public function login($usu, $pass)
  {
      $parametros = [
          'correcto' => null,
          'datos' => [],
          'error' => null,
      ];

      try {
          $conexion = new PDO("mysql:host=$this->host;dbname=$this->baseDatos", $this->user, $this->pass);
          $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $ex) {
          echo $ex;
      }
      try {
          $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE nick=(:usuario) AND pass=(:pass)");
          $consulta->setFetchMode(PDO::FETCH_ASSOC);
          $consulta->execute(array(":usuario" => $usu, ":pass" => $pass));
          $parametros['datos'] = $consulta->fetch(PDO::FETCH_ASSOC);
          if (!empty($parametros['datos'])) {
              $parametros["correcto"] = true;

              $consulta = $conexion->prepare("INSERT INTO logs(tipo, usuario, nombre) VALUES (:tipo, :usuario,:nombre)");
              $consulta->execute(array(':tipo' => 'login usuario', ':usuario' => $parametros['datos']['id'], ':nombre' => $parametros['datos']['nick']));
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
   * @return type
   */
  public function listado() {
    $return = [
        "correcto" => FALSE,
        "datos" => NULL,
        "error" => NULL
    ];
    //Realizamos la consulta...
    try { 
      $resultsquery = $this->conexion->prepare("SELECT * FROM entradas");
      $resultsquery->execute();
      //Supervisamos si la inserción se realizó correctamente... 
      if ($resultsquery) :
        $return["correcto"] = TRUE;
        $return["datos"] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
      endif; // o no :(
    } catch (PDOException $ex) {
      $return["error"] = $ex->getMessage();
    }

    return $return;
  }

  /**
   * Método que elimina el usuario cuyo id es el que se le pasa como parámetro 
   * @param $id es un valor numérico. Es el campo clave de la tabla
   * @return boolean
   */
  public function deluser($id) {
    // La función devuelve un array con dos valores:'correcto', que indica si la
    // operación se realizó correctamente, y 'mensaje', campo a través del cual le
    // mandamos a la vista el mensaje indicativo del resulConsulta de la operación
    $return = [
        "correcto" => FALSE,
        "error" => NULL
    ];
    //Si hemos recibido el id y es un número realizamos el borrado...
    if ($id && is_numeric($id)) {
      try {
        //Inicializamos la transacción
        $this->conexion->beginTransaction();
        //Definimos la instrucción SQL parametrizada 
        $sql = "DELETE FROM entradas WHERE id=:id";
        $query = $this->conexion->prepare($sql);
        $query->execute(['id' => $id]);
        //Supervisamos si la eliminación se realizó correctamente... 
        if ($query) {
          $this->conexion->commit();  // commit() confirma los cambios realizados durante la transacción
          $return["correcto"] = TRUE;
        }// o no :(
      } catch (PDOException $ex) {
        $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
        $return["error"] = $ex->getMessage();
      }
    } else {
      $return["correcto"] = FALSE;
    }

    return $return;
  }

  /**
   * 
   * @param type $datos
   * @return type
   */
  public function adduser($datos, $id, $usuario) {
    //Array que recogera los datos obtenidos de hacer las operaciones
    $parametros = [
      'correcto' => null,
      'tipo' => null,
      'mensaje' => null,
  ];

  try {
      $conexion = new PDO("mysql:host=$this->host;dbname=$this->baseDatos", $this->user, $this->pass);
      $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //Comprobamos que no existe ya una cuenta de correo igual
      $consulta = $conexion->prepare("SELECT email FROM usuarios WHERE email=:correo");
      $consulta->execute(array(':correo' => $datos['email']));
      $correoExistente = $consulta->fetch(PDO::FETCH_ASSOC);

      //Si no existe, devuelve false y guardamos el nuevo usuario
      if (!$correoExistente) {
          $consulta = $conexion->prepare("INSERT INTO usuarios (id, nick,nombre, apellidos, email, password, imagen) VALUES (:id , :nick, :nombre, :apellidos, :email, :password, :imagen)");
          $resulConsulta = $consulta->execute(array(':id' => null, ':nick' => $datos['nick'], ':nombre' => $datos['nombre'], ':apellidos' => $datos['apellidos'],':email' => $datos['email'], ':password' => $datos['password'], ':imagen' => $datos['imagen']));
          if ($resulConsulta) { //Si el resultado es correcto
              $parametros['correcto'] = true;
              $parametros['tipo'] = 'alert alert-access';
              $parametros['mensaje'] = 'Usuario añadido correctamente'; //Mostramos mensaje de exito
              //Insertamos en la tabla log el registro de inserción
              // $consulta = $conexion->prepare("INSERT INTO logs(tipo, usuario, nombre) VALUES (:tipo, :usuario,:nombre)");
              // $consulta->execute(array(':tipo' => 'agregar usuario', ':usuario' => $id, ':nombre' => $usuario));
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

  public function actuser($datos) {
    $return = [
        "correcto" => FALSE,
        "error" => NULL
    ];

    try {
      //Inicializamos la transacción
      $this->conexion->beginTransaction();
      //Definimos la instrucción SQL parametrizada 
      $sql = "UPDATE usuarios SET nombre= :nombre, email= :email, imagen= :imagen WHERE id=:id";
      
      $query = $this->conexion->prepare($sql);
      $query->execute([
          'id' => $datos["id"],
          'nombre' => $datos["nombre"],
          'email' => $datos["email"],
          'imagen' => $datos["imagen"]
      ]);
      //Supervisamos si la inserción se realizó correctamente... 
      if ($query) {
        $this->conexion->commit();  // commit() confirma los cambios realizados durante la transacción
        $return["correcto"] = TRUE;
      }// o no :(
    } catch (PDOException $ex) {
      $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
      $return["error"] = $ex->getMessage();
      //die();
    }

    return $return;
  }

  public function listausuario($id) {
    $return = [
        "correcto" => FALSE,
        "datos" => NULL,
        "error" => NULL
    ];

    if ($id && is_numeric($id)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE id=:id";
        $query = $this->conexion->prepare($sql);
        $query->execute(['id' => $id]);
        //Supervisamos que la consulta se realizó correctamente... 
        if ($query) {
          $return["correcto"] = TRUE;
          $return["datos"] = $query->fetch(PDO::FETCH_ASSOC);
        }// o no :(
      } catch (PDOException $ex) {
        $return["error"] = $ex->getMessage();
        //die();
      }
    }

    return $return;
  }

}
