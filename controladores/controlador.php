<?php

/**
 * Incluimos el modelo para poder acceder a su clase y a los métodos que implementa
 */
require_once 'C:\xampp\htdocs\tareaBlogServidor\modelos\modelo.php';
/**
 * Clase controlador que será la encargada de obtener, para cada tarea, los datos
 * necesarios de la base de datos, y posteriormente, tras su proceso de elaboración,
 * enviarlos a la vista para su visualización
 */
class controlador
{

    // El el atributo $modelo es de la 'clase modelo' y será a través del que podremos
    // acceder a los datos y las operaciones de la base de datos desde el controlador
    private $modelo;
    //$mensajes se utiliza para almacenar los mensajes generados en las tareas,
    //que serán posteriormente transmitidos a la vista para su visualización
    private $mensajes;

    /**
     * Constructor que crea automáticamente un objeto modelo en el controlador e
     * inicializa los mensajes a vacío
     */
    public function __construct()
    {
        $this->modelo = new modelo();
        $this->mensajes = [];
    }

    /**
     * Método que envía al usuario a la página de inicio del sitio y le asigna
     * el título de manera dinámica
     */
    public function index()
    {
        $parametros = [
            "tituloventana" => "Base de Datos con PHP y PDO",
        ];
        //Mostramos la página de inicio
        include_once 'vistas/inicio.php';
    }

    /**
     * Método que obtiene de la base de datos el listado de usuarios y envía dicha
     * infomación a la vista correspondiente para su visualización
     */
    public function listado()
    {
        // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
        $parametros = [
            "tituloventana" => "Base de Datos con PHP y PDO",
            "datos" => null,
            "mensajes" => [],
        ];
        // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
        $resultModelo = $this->modelo->listado();
        // Si la consulta se realizó correctamente transferimos los datos obtenidos
        // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
        // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
        if ($resultModelo["correcto"]):
            $parametros["datos"] = $resultModelo["datos"];
            //Definimos el mensaje para el alert de la vista de que todo fue correctamente
            $this->mensajes[] = [
                "tipo" => "success",
                "mensaje" => "El listado se realizó correctamente",
            ];
        else:
            //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})",
            ];
        endif;
        //Asignanis al campo 'mensajes' del array de parámetros el valor del atributo
        //'mensaje', que recoge cómo finalizó la operación:
        $parametros["mensajes"] = $this->mensajes;
        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        include_once 'vistas/listado.php';
    }

    /**
     * Método de la clase controlador que realiza la eliminación de un usuario a
     * través del campo id
     */
    public function deluser()
    {
        // verificamos que hemos recibido los parámetros desde la vista de listado
        if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
            $id = $_GET["id"];
            //Realizamos la operación de suprimir el usuario con el id=$id
            $resultModelo = $this->modelo->deluser($id);
            //Analizamos el valor devuelto por el modelo para definir el mensaje a
            //mostrar en la vista listado
            if ($resultModelo["correcto"]):
                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "Se eliminó correctamente el usuario $id",
                ];
            else:
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "La eliminación del usuario no se realizó correctamente!! :( <br/>({$resultModelo["error"]})",
                ];
            endif;
        } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No se pudo acceder al id del usuario a eliminar!! :(",
            ];
        }
        //Relizamos el listado de los usuarios
        $this->listado();
    }

    public function adduser()
    {
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        $id = "";
        $titulo = "";
        $descripcion = "";
        $fecha = "";

        // validar el formulario cuando se envía
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // validar campo titulo
            if (empty($_POST["id"])) {
                $errores["id"] = "Error: El id es obligatorio";
            } else {
                $titulo = $_POST["id"];
            }
            // validar campo titulo
            if (empty($_POST["titulo"])) {
                $errores["titulo"] = "Error: El título es obligatorio";
            } else {
                $titulo = $_POST["titulo"];
            }

            // validar campo descripción
            if (empty($_POST["descripcion"])) {
                $errores["descripcion"] = "Error: La descripción es obligatoria";
            } else {
                $descripcion = $_POST["descripcion"];
            }

            // validar campo fecha
            if (empty($_POST["fecha"])) {
                $errores["fecha"] = "Error: La fecha es obligatoria";
            } else {
                $fecha = $_POST["fecha"];
            }

            // validar campo imagen (opcional)
            if (!empty($_FILES["imagen"]["name"])) {
                $imagen = $_FILES["imagen"]["name"];
                $target_dir = "images/";
                $target_file = $target_dir . basename($imagen);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                // comprueba si es una imagen válida
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $errores["imagen"] = "Error: Solo se permiten archivos JPG, JPEG, PNG y GIF";
                }
            }
            var_dump($errores);
            // Si no se han producido errores realizamos el registro del usuario
            if (count($errores) == 0) {
                $resultModelo = $this->modelo->adduser([
                    'id' => $id,
                    'titulo' => $titulo,
                    "imagen" => $imagen,
                    'descripcion' => $descripcion,
                    'fecha' => $fecha,
                ]);
                if ($resultModelo["correcto"]):
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "La entrada se registró correctamente!! :)",
                    ];
                else:
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "La entrada no pudo registrarse!! :( <br />({$resultModelo["error"]})",
                    ];
                endif;
            } else {
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Datos de registro de entrada erróneos!! :(",
                ];
            }
        }

        $parametros = [
            "tituloventana" => "Base de Datos con PHP y PDO",
            "datos" => [
              "id" => isset($id) ? $id : "",
                "titulo" => isset($titulo) ? $titulo : "",
                "imagen" => isset($imagen) ? $imagen : "",
                "descripcion" => isset($descripcion) ? $descripcion : "",
                "fecha" => isset($fecha) ? $fecha : "",
            ],
            "mensajes" => $this->mensajes,
        ];
        //Visualizamos la vista asociada al registro de usuarios
        include_once 'vistas/adduser.php';
    }

    /**
     * Método de la clase controlador que permite actualizar los datos del usuario
     * cuyo id coincide con el que se pasa como parámetro desde la vista de listado
     * a través de GET
     */
    public function actuser()
    {
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        // Inicializamos valores de los campos de texto
        $nuevoTitulo = "";
        $nuevaDescripcion = "";
        $nuevaFecha = "";

        // Si se ha pulsado el botón actualizar...
        if (isset($_POST['submit'])) { //Realizamos la actualización con los datos existentes en los campos
            $id = $_POST['id']; //Lo recibimos por el campo oculto
            $titulo = $_POST['txtnombre'];
            $descripcion = $_POST['txtemail'];
            $fecha = "";

            // Definimos la variable $imagen que almacenará el nombre de imagen
            // que almacenará la Base de Datos inicializada a NULL
            $imagen = null;

            // validar campo imagen (opcional)
            if (!empty($_FILES["imagen"]["name"])) {
                $imagen = $_FILES["imagen"]["name"];
                $target_dir = "images/";
                $target_file = $target_dir . basename($imagen);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                // comprueba si es una imagen válida
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $errores["imagen"] = "Error: Solo se permiten archivos JPG, JPEG, PNG y GIF";
                }
            }
            $nuevaimagen = $imagen;

            //Comprobamos que no haya errores
            if (count($errores) == 0) {
                //Ejecutamos la instrucción de actualización a la que le pasamos los valores
                $resultModelo = $this->modelo->actuser([
                    'id' => $id,
                    'titulo' => $nuevoTitulo,
                    'descripcion' => $nuevaDescripcion,
                    'fecha' => $nuevaFecha,
                ]);
                //Analizamos cómo finalizó la operación de registro y generamos un mensaje
                //indicativo del estado correspondiente
                if ($resultModelo["correcto"]):
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "La entrada se actualizó correctamente!! :)",
                    ];
                else:
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "La entrada no pudo actualizarse!! :( <br/>({$resultModelo["error"]})",
                    ];
                endif;
            } else {
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Datos de registro de entrada erróneos!! :(",
                ];
            }

            // Obtenemos los valores para mostrarlos en los campos del formulario
            $titulo = $nuevoTitulo;
            $descripcion = $nuevaDescripcion;
            $fecha = $nuevaFecha;

        } else { //Estamos rellenando los campos con los valores recibidos del listado
            if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
                $id = $_GET['id'];
                //Ejecutamos la consulta para obtener los datos del usuario #id
                $resultModelo = $this->modelo->listausuario($id);
                //Analizamos si la consulta se realiz´correctamente o no y generamos un
                //mensaje indicativo
                if ($resultModelo["correcto"]):
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "Los datos del usuario se obtuvieron correctamente!! :)",
                    ];
                    $titulo = $resultModelo["datos"]["titulo"];
                    $descripcion = $resultModelo["datos"]["descripcion"];
                    $fecha = $resultModelo["datos"]["fecha"];
                else:
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "No se pudieron obtener los datos de entrada!! :( <br/>({$resultModelo["error"]})",
                    ];
                endif;
            }
        }
        //Preparamos un array con todos los valores que tendremos que rellenar en
        //la vista adduser: título de la página y campos del formulario
        $parametros = [
            "tituloventana" => "Base de Datos con PHP y PDO",
            "datos" => [
                "titulo" => $titulo,
                "descripcion" => $descripcion,
                "fecha" => $fecha,
            ],
            "mensajes" => $this->mensajes,
        ];
        //Mostramos la vista actuser
        include_once 'vistas/actuser.php';
    }

}
