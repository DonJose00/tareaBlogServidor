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
        $datosVistas = [
            "tituloventana" => "Base de Datos con PHP y PDO",
        ];
        //Mostramos la página de inicio
        include_once 'vistas/inicio.php';
    }

    // metodo que valida si un usuario esta registrado en el blog
    public function login()
    {
        $login = false;
        $datosVistas = ['mensajes' => [],];
        $error = [];

        if (isset($_POST['enviar'])) { //Si se ha pulsao enviar
            if (empty($_POST['usuario'])) { //Si el campo usuario está vacio
                $error['usuario'] = 'Usuario no puede estar vacio';
            }
            if (empty($_POST['password'])) { //Si el campo contraseña está vacio
                $error['password'] = 'Contraseña no puede estar vacia';
            }
            if (empty($error)) {
                /**
                 * esta línea de código llama a una función llamada "login" en un modelo 
                 * y le pasa dos parámetros: el nombre de usuario y la contraseña. 
                 * El resultado de la función se almacena en una variable llamada $resultModelo.
                 */
                $resultModelo = $this->modelo->login($_POST['usuario'], $_POST['password']);
                if ($resultModelo['correcto']) {
                    // Comprobar si el usuario es normal o administrador
                    if ($_POST['usuario'] == 'user' && $_POST['password'] == 'user') {
                        // Guardar los datos en la sesión
                        $_SESSION["usuario"] = $resultModelo['datos']['nick'];
                        $_SESSION["id"] = $resultModelo['datos']['id'];
                        $_SESSION["rol"] = 'user';
                        $login = true;
                    }elseif ($_POST['usuario'] == 'admin' && $_POST['password'] == 'admin') {
                        // Guardar los datos en la sesión
                        $_SESSION["usuario"] = $resultModelo['datos']['nick'];
                        $_SESSION["id"] = $resultModelo['datos']['id'];
                        $_SESSION["rol"] = 'admin';
                        $login = true;
                    }
                    
                } else {
                    $this->mensajes = [
                        'tipo' => 'alert alert-danger',
                        'mensaje' => 'Usuario o Contraseña incorrectos',
                    ];
                    $datosVistas['mensajes'] = $this->mensajes;
                }
            }
        } //post acceder
        if ($login == false) {
            include 'vistas/login.php';
        } else {
            include 'vistas/listEntradas.php';
        }
    }
    
    /**
     * Método que obtiene de la base de datos el listado de usuarios y envía dicha
     * infomación a la vista correspondiente para su visualización
     */
    public function listado()
    {
        /**
         * Inicializamos una matriz (array) llamada $datosVistas. 
         * Esta matriz tiene dos elementos: 'datos' y 'mensajes'. 
         * Ambos elementos se inicializan con un array vacío.
         */
        $datosVistas = [
            "tituloventana" => "Base de Datos con PHP y PDO",
            "datos" => null,
            "mensajes" => [],
        ];
        // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
        $resultModelo = $this->modelo->listado();
        /**
         * Si la llamada al método 'listado' devuelve una matriz en la que el elemento 'correcto' es verdadero, 
         * se asigna el valor de la matriz 'datos' devuelta por el método a $datosVistas['datos']. 
         * También se establece un mensaje de éxito (con tipo 'success') en el atributo 'mensajes' de 
         * la clase actual ($this). Este mensaje se establece para ser mostrado en la vista que se 
         * cargará al final de la función.
         */
        if ($resultModelo["correcto"]){
            $datosVistas["datos"] = $resultModelo["datos"];
            $this->mensajes[] = ["tipo" => "success", "mensaje" => "El listado se realizó correctamente"];
        }else{
            /**
             * Si la llamada al método 'listado' devuelve una matriz en la que el elemento 'correcto' es falso, 
             * se establece un mensaje de error (con tipo 'danger') en el atributo 'mensajes' de la clase actual ($this). 
             * Este mensaje se establece para ser mostrado en la vista que se cargará al final de la función.
             */
            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})"];
        }
        $datosVistas["mensajes"] = $this->mensajes;
        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        include_once 'vistas/listado.php';
    }

    /**
     * Método que lista las entradas filtrando por usuario, es decir, del usuario que inicia sesión.
     */
    // public function listarUsuario()
    // {
    //     //array asociativo con dos claves: datos y mensajes. Ambas claves tienen un array vacío como valor por defecto.
    //     $datosVistas = [
    //         'datos' => [],
    //         'mensajes' => [],
    //     ];

    //     $resultModelo = $this->modelo->listausuario($_SESSION['id']);
    //     /**
    //      * Si el valor correcto en el array $resultModelo es verdadero, 
    //      * se asignan los datos devueltos por el método listausuario a la clave 
    //      * datos en $datosVistas. Además, se define un mensaje de éxito en la clave mensajes.
    //      */
    //     if ($resultModelo['correcto']) {
    //         $datosVistas['datos'] = $resultModelo['datos'];
    //         $this->mensajes = [
    //             'tipo' => 'alert alert-success text-center',
    //             'mensaje' => 'El listado se ha realizado correctamente',
    //         ];
    //     } else {
    //         $this->mensajes = [
    //             'tipo' => 'alert alert-danger text-center',
    //             'mensaje' => 'El listado no se ha realizado',
    //         ];
    //     }
    //     //Se asigna el valor de $this->mensajes a la clave mensajes en $datosVistas.
    //     $datosVistas['mensajes'] = $this->mensajes;

    //     include 'vistas/listarPorUsuario.php';
    // }

    //metodo que muestra los datos de las entradas divididos por páginas. Vamos a mostrar 4 entradas por paginas.
    public function paginado()
    {
        $datosVistas = [
            'paginas' => 0,
            'datos' => null,
            'mensajes' => [],
        ];

        if (isset($_GET['pag'])) {
            $resultModelo = $this->modelo->paginado($_GET['pag']);
            if ($resultModelo['correcto']) {
                $datosVistas['paginas'] = $resultModelo['paginas'];
                $datosVistas['datos'] = $resultModelo['datos'];
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
        $datosVistas['mensajes'] = $this->mensajes;
        include_once 'vistas/listadoPag.php';
    }

    // public function adduser()
    // {
    //     $datosVistas = [
    //         'tipo' => null,
    //         'mensaje' => null,
    //     ];
    //     $error = [];
    //     $datos = [];
    //     //Si hemos pulsado en el botón registrar(si hemos enviado datos)
    //     if (isset($_POST['registrar'])) {
    //         //Validación de los campos del formulario
    //         //Comprobamos que no están vacios, sanitizamos, quitamos espacios,caracteres especiales, slashes etc..
    //         if (isset($_POST['usuario']) && (!empty($_POST['usuario']))) { //Si se ha recibido datos del campo usuario
    //             $_POST['usuario'] = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
    //             $_POST['usuario'] = trim($_POST["usuario"]);
    //             $_POST['usuario'] = htmlspecialchars($_POST["usuario"]);
    //             $_POST['usuario'] = stripcslashes($_POST["usuario"]);
    //         } else {
    //             $error['usuario'] = 'El campo usuario no puede estar vacío'; //Añadimos error en caso de usuario no válido
    //         }
    //         if ((!empty($_POST['password']))) {
    //             if (isset($_POST['password'])) {
    //                 //poner validador de contraseña
    //                 $_POST['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    //                 $_POST['password'] = trim($_POST["password"]);
    //                 $_POST['password'] = htmlspecialchars($_POST["password"]);
    //                 $_POST['password'] = stripcslashes($_POST["password"]);
    //             }
    //         } else {
    //             $error['pass'] = 'La contraseña no puede estar vacia';
    //         }
    //         if (!empty($_POST['nombre'])) {
    //             $_POST['nombre'] = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    //             $_POST['nombre'] = trim($_POST["nombre"]);
    //             $_POST['nombre'] = htmlspecialchars($_POST["nombre"]);
    //             $_POST['nombre'] = stripcslashes($_POST["nombre"]);
    //         } else {
    //             $error['nombre'] = 'El nombre no puede estar vacío';
    //         }
    //         if (!empty($_POST['apellidos'])) {
    //             $_POST['apellidos'] = filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING);
    //             $_POST['apellidos'] = trim($_POST["apellidos"]);
    //             $_POST['apellidos'] = htmlspecialchars($_POST["apellidos"]);
    //             $_POST['apellidos'] = stripcslashes($_POST["apellidos"]);
    //         } else {
    //             $error['apellidos'] = 'El campo apellido no puede estar vacío';
    //         }
    //         if (!empty($_POST['email'])) {
    //             if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    //                 $_POST['email'] = trim($_POST["email"]);
    //                 $_POST['email'] = htmlspecialchars($_POST["email"]);
    //                 $_POST['email'] = stripcslashes($_POST["email"]);
    //             } else {
    //                 $error['email'] = 'Correo electronico no válido';
    //             }
    //         } else {
    //             $error['email'] = 'El campo email no puede estar vacio';
    //         }
    //         if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["name"]))) {
    //             $ruta = "images/";
    //             move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta . $_FILES["imagen"]['name']);
    //             $_POST['imagen'] = $_FILES['imagen']['name'];
    //         } else {
    //             $_POST['imagen'] = "";
    //         }

    //         // Si se han enviado los datos y no existen errores podemos ingresar el usuario
    //         if (empty($error)) {
    //             $datos = [
    //                 'nick' => $_POST['usuario'],
    //                 'nombre' => $_POST['nombre'],
    //                 'apellidos' => $_POST['apellidos'],
    //                 'email' => $_POST['email'],
    //                 'password' => $_POST['password'],
    //                 'imagen' => $_POST['imagen'],
    //             ];
    //             //Ejecutamos el método adduser de la clase modelo y lo guardamos en la variable $resultModelo
    //             $resultModelo = $this->modelo->adduser($datos, $_POST['usuario']);
    //             if ($resultModelo['correcto']) { //Comprobamos que se devuelve true
    //                 $datosVistas['tipo'] = 'alert alert-success text-center';
    //                 $datosVistas['mensaje'] = 'Se ha creado el Usuario';
    //             } else {
    //                 $datosVistas['tipo'] = 'alert alert-danger text-center';
    //                 $datosVistas['mensaje'] = $resultModelo['mensaje'] . ' - No se ha agregado el Usuario';

    //             }
    //         }
    //     }
    //     include 'vistas/adduser.php';
    // }

    public function addEntrada()
    {
        $datos = [];
        $datosVistas = [
            'numero' => null,
            'categorias' => [],
            'tipo' => null,
            'mensaje' => null,

        ];
        //hacemos una primera consulta para saber el numero de categorias y cuales son
        $resultModelo = $this->modelo->numCategorias();

        $datosVistas['numero'] = $resultModelo['numero'];
        $datosVistas['categorias'] = $resultModelo['categorias'];

        //comprobamos que se haya iniciado sesion para poder continuar
        if (isset($_SESSION['id'])) {
            if (isset($_POST['enviar'])) {

                $error = [];

                //Comprobamos que los campos no están vacíos y si lo están, lo añadimos al array error
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
                    $ruta = "images/";
                    move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta . $_FILES["imagen"]['name']);
                    $_POST['imagen'] = $_FILES['imagen']['name'];
                } else {
                    $_POST['imagen'] = ' ';
                }
            }
        } else {
            $error['usuario'] = 'Debe acceder con su usuario para poder añadir Entrada';
        }

        //Si hemos enviado los datos y no contienen errores
        if (isset($_POST['enviar']) && empty($error)) {
            $datos = [
                'id' => null,
                'usuario_id' => $_SESSION['id'],
                'categoria_id' => (int) $_POST['cat'],
                'titulo' => $_POST['titulo'],
                'imagen' => $_POST['imagen'],
                'descripcion' => $_POST['desc'],
                
            ];
            //enviamos los datos a modelo para insertarlos en la base de datos
            $resultModelo = $this->modelo->agregaEntrada($datos, $_SESSION['id'], $_SESSION['usuario']);

            if ($resultModelo['correcto']) {
                $datosVistas['tipo'] = 'alert alert-access text-center';
                $datosVistas['mensaje'] = 'Se ha insertado correctamente';
            } else {
                $datosVistas['tipo'] = 'alert alert-danger text-center';
                $datosVistas['mensaje'] = 'No se ha insertado la Entrada';
            }
        }

        include_once 'vistas/addEntrada.php';
    }
}
