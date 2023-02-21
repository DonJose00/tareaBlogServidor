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

    // metodo que valida si un usuario esta registrado en el blog
    public function login()
    {
        $login = false;
        $parametrosVistas = ['mensajes' => [],];
        $error = [];

        if (isset($_POST['enviar'])) {
            if (empty($_POST['usuario'])) {
                $error['usuario'] = 'Usuario no puede estar vacio';
            }
            if (empty($_POST['password'])) {
                $error['password'] = 'Contraseña no puede estar vacia';
            }
            if (empty($error)) {
                //comprobamos a través de modelo si está en la base de datos
                $resultadoModelo = $this->modelo->login($_POST['usuario'], $_POST['password']);

                if ($resultadoModelo['correcto']) {
                    if (empty($resultadoModelo['datos'])) {
                        $parametrosVistas['mensajes']['tipo'] = 'alert alert-danger text-center';
                        $parametrosVistas['mensajes']['mensaje'] = "Usuario / constraseña incorrecto";
                    } else {
                        $login = true;
                    }

                    //creamos la sesion de usuario para mostrar los datos de dicho usuario
                    $_SESSION["usuario"] = $resultadoModelo['datos']['nick'];
                    $_SESSION["id"] = $resultadoModelo['datos']['id'];
                } else {
                    $this->mensajes = [
                        'tipo' => 'alert alert-danger',
                        'mensaje' => 'Usuario o Contraseña incorrectos',
                    ];
                    $parametrosVistas['mensajes'] = $this->mensajes;
                }
            }
        } //post acceder
        if ($login == false) {
            include 'vistas/login.php';
        } else {
            $this->index();
        }
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
        if ($resultModelo["correcto"]) {
            $parametros["datos"] = $resultModelo["datos"];
            //Definimos el mensaje para el alert de la vista de que todo fue correctamente
            $this->mensajes[] = [
                "tipo" => "success",
                "mensaje" => "El listado se realizó correctamente",
            ];
        } else {
            //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})",
            ];
        }
        $parametros["mensajes"] = $this->mensajes;
        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        include_once 'vistas/listado.php';
    }

    //metodo que lista solo las entradas del usuario que ha iniciado sesion
    public function listarUsuario()
    {
        $parametrosVistas = [
            'datos' => [],
            'mensajes' => [],
        ];

        $resultadoModelo = $this->modelo->listausuario($_SESSION['id']);
        if ($resultadoModelo['correcto']) {
            $parametrosVistas['datos'] = $resultadoModelo['datos'];
            $this->mensajes = [
                'tipo' => 'alert alert-success text-center',
                'mensaje' => 'El listado se ha realizado correctamente',
            ];
        } else {
            $this->mensajes = [
                'tipo' => 'alert alert-danger text-center',
                'mensaje' => 'El listado no se ha realizado',
            ];
        }
        $parametrosVistas['mensajes'] = $this->mensajes;

        include 'vistas/listarUsuario.php';
    }

    //metodo que muestra los datos de las entradas divididos por páginas. Vamos a mostrar 4 entradas por paginas.
    public function paginado()
    {
        $parametrosVistas = [
            'paginas' => 0,
            'datos' => null,
            'mensajes' => [],
        ];

        if (isset($_GET['pag'])) {
            $resultadoModelo = $this->modelo->paginado($_GET['pag']);
            if ($resultadoModelo['correcto']) {
                $parametrosVistas['paginas'] = $resultadoModelo['paginas'];
                $parametrosVistas['datos'] = $resultadoModelo['datos'];
                $this->mensajes = [
                    'tipo' => 'alert alert-success text-center',
                    'mensaje' => 'El listado se ha realizado correctamente',
                ];
            } else {
                $this->mensajes = [
                    'tipo' => 'alert alert-danger text-center',
                    'mensaje' => 'El listado no se ha realizado',
                ];
            }
        }
        $parametrosVistas['mensajes'] = $this->mensajes;
        include_once 'vistas/listadoPag.php';
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
            if ($resultModelo["correcto"]) :
                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "Se eliminó correctamente el usuario $id",
                ];
            else :
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
        $parametrosVistas = [
            'tipo' => null,
            'mensaje' => null,
        ];
        $error = [];
        $datos = [];
        //Si hemos pulsado en el botón registrar(si hemos enviado datos)
        if (isset($_POST['registrar'])) {
            //Validación de los campos del formulario
            //Comprobamos que no están vacios, sanitizamos, quitamos espacios,caracteres especiales, slashes etc..
            if (isset($_POST['usuario']) && (!empty($_POST['usuario']))) { //Si se ha recibido datos del campo usuario
                $_POST['usuario'] = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
                $_POST['usuario'] = trim($_POST["usuario"]);
                $_POST['usuario'] = htmlspecialchars($_POST["usuario"]);
                $_POST['usuario'] = stripcslashes($_POST["usuario"]);
            } else {
                $error['usuario'] = 'El campo usuario no puede estar vacío'; //Añadimos error en caso de usuario no válido
            }
            if ((!empty($_POST['password']))) {
                if (isset($_POST['password'])) {
                    //poner validador de contraseña
                    $_POST['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                    $_POST['password'] = trim($_POST["password"]);
                    $_POST['password'] = htmlspecialchars($_POST["password"]);
                    $_POST['password'] = stripcslashes($_POST["password"]);
                }
            } else {
                $error['pass'] = 'La contraseña no puede estar vacia';
            }
            if (!empty($_POST['nombre'])) {
                $_POST['nombre'] = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
                $_POST['nombre'] = trim($_POST["nombre"]);
                $_POST['nombre'] = htmlspecialchars($_POST["nombre"]);
                $_POST['nombre'] = stripcslashes($_POST["nombre"]);
            } else {
                $error['nombre'] = 'El nombre no puede estar vacío';
            }
            if (!empty($_POST['apellidos'])) {
                $_POST['apellidos'] = filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING);
                $_POST['apellidos'] = trim($_POST["apellidos"]);
                $_POST['apellidos'] = htmlspecialchars($_POST["apellidos"]);
                $_POST['apellidos'] = stripcslashes($_POST["apellidos"]);
            } else {
                $error['apellidos'] = 'El campo apellido no puede estar vacío';
            }
            if (!empty($_POST['email'])) {
                if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $_POST['email'] = trim($_POST["email"]);
                    $_POST['email'] = htmlspecialchars($_POST["email"]);
                    $_POST['email'] = stripcslashes($_POST["email"]);
                } else {
                    $error['email'] = 'Correo electronico no válido';
                }
            } else {
                $error['email'] = 'El campo email no puede estar vacio';
            }
            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["name"]))) {
                $ruta = "images/";
                move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta . $_FILES["imagen"]['name']);
                $_POST['imagen'] = $_FILES['imagen']['name'];
            } else {
                $_POST['imagen'] = "";
            }

            // Si se han enviado los datos y no existen errores podemos ingresar el usuario
            if (empty($error)) {
                $datos = [
                    'nick' => $_POST['usuario'],
                    'nombre' => $_POST['nombre'],
                    'apellidos' => $_POST['apellidos'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'imagen' => $_POST['imagen'],
                ];
                //Ejecutamos el método adduser de la clase modelo y lo guardamos en la variable $resultadoModelo
                $resultadoModelo = $this->modelo->adduser($datos, $_SESSION['id'], $_SESSION['usuario']);
                if ($resultadoModelo['correcto']) { //Comprobamos que se devuelve true
                    $parametrosVistas['tipo'] = 'alert alert-success text-center';
                    $parametrosVistas['mensaje'] = 'Se ha creado el Usuario';
                } else {
                    $parametrosVistas['tipo'] = 'alert alert-danger text-center';
                    $parametrosVistas['mensaje'] = $resultadoModelo['mensaje'] . ' - No se ha agregado el Usuario';
                }
            }
        }
        include 'vistas/adduser.php';
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
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "La entrada se actualizó correctamente!! :)",
                    ];
                else :
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
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "Los datos del usuario se obtuvieron correctamente!! :)",
                    ];
                    $titulo = $resultModelo["datos"]["titulo"];
                    $descripcion = $resultModelo["datos"]["descripcion"];
                    $fecha = $resultModelo["datos"]["fecha"];
                else :
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

    public function addEntrada()
    {
        $datos = [];
        $parametrosVistas = [
            'numero' => null,
            'categorias' => [],
            'tipo' => null,
            'mensaje' => null,

        ];
        //hacemos una primera consulta para saber el numero de categorias y cuales son
        $resultadoModelo = $this->modelo->numCategorias();

        $parametrosVistas['numero'] = $resultadoModelo['numero'];
        $parametrosVistas['categorias'] = $resultadoModelo['categorias'];

        //comprobamos que se haya iniciado sesion para poder continuar
        if (isset($_SESSION['id'])) {
            if (isset($_POST['ok'])) {

                $error = [];

                if (!empty($_POST['titulo'])) {
                    $_POST['titulo'] = filter_var($_POST['titulo'], FILTER_SANITIZE_STRING);
                    $_POST['titulo'] = trim($_POST["titulo"]);
                    $_POST['titulo'] = htmlspecialchars($_POST["titulo"]);
                    $_POST['titulo'] = stripcslashes($_POST["titulo"]);
                } else {
                    $error['titulo'] = 'El campo Titulo no puede estar vacío';
                }

                if (!empty($_POST['desc'])) {
                    $_POST['desc'] = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
                    $_POST['desc'] = trim($_POST["desc"]);
                    $_POST['desc'] = htmlspecialchars($_POST["desc"]);
                    $_POST['desc'] = stripcslashes($_POST["desc"]);
                } else {
                    $error['desc'] = 'El campo Descripcion no puede estar vacío';
                }

                if (!empty($_POST['fecha'])) {
                    $fecha = $_POST['fecha'];
                    if ($fecha < date('d m y')) {
                        $errores['fecha'] = 'La fecha no puede ser anterior al dia de hoy';
                    }
                }

                if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["name"]))) {
                    $ruta = "img/";
                    move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta . $_FILES["imagen"]['name']);
                    $_POST['imagen'] = $_FILES['imagen']['name'];
                } else {
                    $_POST['imagen'] = ' ';
                }
            }
        } else {
            $error['usuario'] = 'Debe acceder con su usuario para poder añadir Entrada';
        }

        //si hemos pulsado submit y no hay errores podemos guardar los datos en la BD
        if (isset($_POST['ok']) && empty($error)) {
            $datos = [
                'id' => null,
                'titulo' => $_POST['titulo'],
                'imagen' => $_POST['imagen'],
                'descripcion' => $_POST['desc'],
                'id_categoria' => (int) $_POST['cat'],
                'id_usuario' => $_SESSION['id'],
            ];
            //enviamos los datos a modelo para insertarlos en la base de datos
            $resultadoModelo = $this->modelo->agregaEntrada($datos, $_SESSION['id'], $_SESSION['usuario']);

            if ($resultadoModelo['correcto']) {
                $parametrosVistas['tipo'] = 'alert alert-access text-center';
                $parametrosVistas['mensaje'] = 'Se ha insertado correctamente';
            } else {
                $parametrosVistas['tipo'] = 'alert alert-danger text-center';
                $parametrosVistas['mensaje'] = 'No se ha insertado la Entrada';
            }
        }

        include_once 'vistas/addEntrada.php';
    }
}
