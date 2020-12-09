<?php
/*
 * @autor: Miguel Angel Aranda Garcia <miguela.aragar@educa.jcyl.es>
 * @version 1.0
 * @since 29/11/2020 1.0:
 */
session_start();
if (!isset($_SESSION['usuarioDAW210DBProyectoTema5'])) { //si el usuario se logeo anteriormente lo dirigimos al programa

    require_once 'core/201130libreriaValidacion.php'; //incluimos la libreria de validación

    define("OBLIGATORIO", 1); //definimos e inicializamos la constante obligatorio a 1

    $entradaOK = true; //declaramos y inicializamos la variable entradaObligatorioK, esta variable decidira si es correcta la entrada de datos del formulario

    $aFormulario = [//declaramos y inicializamos el array de los campos del formulario a null
        "usuario" => null,
        "password" => null
    ];
    $aError = [//declaramos y inicializamos el array de los errores de los campos del formulario a null
        "usuario" => null,
        "password" => null
    ];
    if (isset($_REQUEST["entrar"])) {
        require_once "../config/conexionBDPDO.php"; //incluimos la conexión a la BD
        $aError["usuario"] = validacionFormularios::comprobarAlfabetico($_REQUEST["usuario"], 15, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo textfieldObligatorio siendo este alfabetico
        $aError['password'] = validacionFormularios::validarPassword($_REQUEST['password'], 8, 1, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo password siendo este alfabetico de tamaño max 8 minimo 1

        try {
            $miDB = new PDO(DNS, USER, PASSWORD, CODIFICACION);
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $usuarioInsertUsuario = $_REQUEST["usuario"];
            $passInsertUsuario = $_REQUEST["password"];

            $consultarLogging = "SELECT T01_CodUsuario, T01_Password, T01_FechaHoraUltimaConexion FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Creamos la consulta mysq

            $consultaLogin = $miDB->prepare($consultarLogging); //Preparamos la consulta
            $consultaLogin->bindParam(":CodUsuario", $usuarioInsertUsuario); //Declaramos el parametro bind

            $consultaLogin->execute(); //Ejecutamos la consulta preparada
            $oUsuario = $consultaLogin->fetchObject(); //creamos el objeto PDO de usuario
            if ($consultaLogin->rowCount() == 1) {
                if ($oUsuario->T01_CodUsuario == $usuarioInsertUsuario && $oUsuario->T01_Password == hash("sha256", $usuarioInsertUsuario . $passInsertUsuario)) {//concatenamos el usuario y la password
                    $_SESSION['usuarioDAW210DBProyectoTema5'] = $usuarioInsertUsuario; //asignamos el valor del usuario al objero de session
                    if ($oUsuario->T01_NumConexiones == 0) {
                        $_SESSION['FechaHoraUltimaConexionAnterior'] = $oUsuario->T01_FechaHoraUltimaConexion;
                    }
                    if (!isset($_COOKIE['idioma'])) {
                        setcookie('idioma', 'esp');
                    }
                    //Actualizamos la ultima vez que se conecto con timestamp y el número de conexiones que ha echo ese usuario
                    $actualizarLogUsu = "UPDATE T01_Usuario SET T01_FechaHoraUltimaConexion =" . time() . ",T01_NumConexiones =T01_NumConexiones + 1 WHERE T01_CodUsuario=:CodUsuario"; //Creamos la consulta mysq
                    $updateUsu = $miDB->prepare($actualizarLogUsu); //Preparamos la consulta
                    $updateUsu->bindParam(":CodUsuario", $oUsuario->T01_CodUsuario); //Declaramos el parametro bind
                    $updateUsu->execute(); //Ejecutamos la consulta preparada
                } else {
                    $aError["usuario"] = "Error de credenciales";
                }
            } else {
                $aError["usuario"] = "Error de credenciales";
            }
        } catch (PDOException $miExcepcionPDO) {
            echo "<div class = 'contenedorError'>";
            echo "<div class = 'box'>";
            echo "<p class = 'error'>Error " . $miExcepcionPDO->getMessage() . "</p>";
            echo "<p class = 'error'>Cod.Error " . $miExcepcionPDO->getCode() . "</p>";
            echo "<h2 class = 'error'>Error en la conexión con la base de datos</h2>";
            echo "</div>";
        } finally {
            unset($miConexion); //cerramos la conexión
        }

        foreach ($aError as $errores => $value) { //Recorremos todos los campos del array $aError
            if ($value != null) { //Si algun campo de $aError tiene un valor diferente null entonces entra
                $entradaOK = false; // asignamos el valor a false en caso de que entre
            }
        }
    }else {//si el usuario no ha pulsado el boton de enviar
        $entradaOK = false; //asignamos el valor a false ya que no se a enviado nada.
    }
    if ($entradaOK) {// si el valor es true entra
        header("Location: codigoPHP/programa.php"); //redirigimos
    } else {
        ?>
        <!DOCTYPE html>

        <html>
            <head>
                <title>Login Logoff Tema 5</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="webroot/css/style_1.css">
                <link rel="stylesheet" type="text/css" href="webroot/css/styleLogin.css">
            </head>
            <body>
                <div id="cabecera">
                    <div id="titulo">
                        <h1>Login Logoff Tema 5</h1>
                    </div>
                    <div class="nav">
                        <a href="../../proyectoDWES/indexProyectoDWES.html" class="boton volver"><img class="icoBoton" src="../webroot/img/volver-flecha-izquierda.png"><span class="texto">Volver</span></a>
                    </div>
                </div>
                <div id="contenedor"> 
                    <div id="form">
                        <form class="descript" action= "<?php echo $_SERVER["PHP_SELF"]
        ?>" method= "POST">
                            <div class="campos">
                                <label class="labelTitle" for="usuario">Usuario: </label>
                                <input  class="inputText" type="text" name="usuario" placeholder="Introduzca el nombre del usuario">
                            </div>
                            <div class="campos">
                                <label class="labelTitle" for="password">Password: </label>
                                <input  class="inputText" type="password" name="password">
                                <?php echo isset($aError["password"]) || isset($aError["usuario"]) ? "<span class='error'>" . "<br>Error de credenciales</span>" : null ?>

                            </div>
                            <div class="botonSend">
                                <input class="botonEnvio" type="submit" value="Entrar" name="entrar">
                                <a class="botonEnvio" href="codigoPHP/registro.php">Registro</a>
                            </div>
                        </form>

                    </div>

                    <?php
                }
                ?>
            </div>
            <footer>
                <div class="pie">
                    <a href="../../index.html" class="nombre">Miguel Ángel Aranda García</a>
                    <a href="https://github.com/MiguelAranda-Sauces" class="git" ><img class="git" src="../webroot/img/git.png"></a>
                </div>

            </footer>
        </body>
    </html>
    <?php
} else {
    header("Location: codigoPHP/programa.php"); //redirigimos
}
?>