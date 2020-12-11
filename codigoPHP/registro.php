<?php
/*
 * @autor: Miguel Angel Aranda Garcia <miguela.aragar@educa.jcyl.es>
 * @version 1.0
 * @since 29/11/2020 1.0:
 */
session_start();
if (isset($_SESSION['usuarioDAW210DBProyectoTema5'])) { //si el usuario se logeo anteriormente lo dirigimos al programa
    header("Location: programa.php");
}
require_once '../core/201130libreriaValidacion.php'; //incluimos la libreria de validación

define("OBLIGATORIO", 1); //definimos e inicializamos la constante obligatorio a 1
define("MINIMO", 1); //definimos e inicializamos la constante minimo a 1

$entradaOK = true; //declaramos y inicializamos la variable entradaObligatorioK, esta variable decidira si es correcta la entrada de datos del formulario

$aError = [//declaramos y inicializamos el array de los errores de los campos del formulario a null
    "usuario" => null,
    "descripcion" => null,
    "password" => null,
    "passwordComprobacion" => null
];

if (isset($_REQUEST["registro"])) {
    require_once "../config/conexionBDPDODesarrollo.php"; //incluimos la conexión a la BD
    $aError["usuario"] = validacionFormularios::comprobarAlfabetico($_REQUEST["usuario"], 15, MINIMO, OBLIGATORIO); //Validamos la entrada del formulario para el campo textfieldObligatorio siendo este alfabetico
    if (($aError["usuario"] == null)) {
        try {
            $miDB = new PDO(DNS, USER, PASSWORD, CODIFICACION); //Creamos el objeto PDO
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT T01_CodUsuario FROM T01_Usuario where T01_CodUsuario=:codUsuario"; //Creamos la consulta mysql con los parametros bind

            $consultaUsuario = $miDB->prepare($sql); //Preparamos la consulta
            $consultaUsuario->bindParam("codUsuario", $_REQUEST["usuario"]); //Declaramos el parametro bind
            $consultaUsuario->execute(); //Ejecutamos la consulta preparada                           
            if ($consultaUsuario->rowCount() != 0) {//si es diferente a 0 declaramos un error ya que la primary key estaria creada ya
                $aError["usuario"] = "Ya existe un usuario con ese nombre";
            }
        } catch (PDOException $miExcepcionPDO) {//declaración de excepcionesPDO
            echo "<p class='error'>Error " . $miExcepcionPDO->getMessage() . "</p>";
            echo "<p class='error'>Cod.Error " . $miExcepcionPDO->getCode() . "</p>";
            echo "<h2 class='error'>Error en la conexión con la base de datos</h2>";
        } finally {
            unset($miDB); //cerramos la conexión
        }
    }
    $aError["descripcion"] = validacionFormularios::comprobarAlfabetico($_REQUEST["descripcion"], 255, MINIMO, OBLIGATORIO); //Validamos la entrada del formulario para el campo textfieldObligatorio siendo este alfabetico
    $aError['password'] = validacionFormularios::validarPassword($_REQUEST['password'], 8, MINIMO, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo password siendo este alfabetico de tamaño max 8 minimo 1
    $aError['passwordComprobacion'] = validacionFormularios::validarPassword($_REQUEST['passwordComprobacion'], 8, MINIMO, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo password siendo este alfabetico de tamaño max 8 minimo 1
    if ($aError['password'] == null && $aError['passwordComprobacion'] == null) {
        if ($_REQUEST['password'] != $_REQUEST['passwordComprobacion']) {
            $aError['passwordComprobacion'] = "Las Password no coinciden";
        }
    }
    foreach ($aError as $errores => $value) { //Recorremos todos los campos del array $aError
        if ($value != null) { //Si algun campo de $aError tiene un valor diferente null entonces entra
            $entradaOK = false; // asignamos el valor a false en caso de que entre
        }
    }
} else {//si el usuario no ha pulsado el boton de enviar
    $entradaOK = false; //asignamos el valor a false ya que no se a enviado nada.
}
if ($entradaOK) {// si el valor es true entra
    try {
        $miDB = new PDO(DNS, USER, PASSWORD);
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sqlRegistro = "INSERT INTO T01_Usuario (T01_CodUsuario, T01_DescUsuario, T01_Password,T01_FechaHoraUltimaConexion,T01_NumConexiones) VALUES(:codUsuario, :descripcion, :password, :fechaUltimaConexion, :numConexiones)"; //Creamos la consulta mysql con los parametros bind

        $registrarUsuario = $miDB->prepare($sqlRegistro); //Preparamos la consulta
        //bindeamos los campos para la consulta
        $param = [':codUsuario' => $_REQUEST['usuario'],
            ':descripcion' => $_REQUEST['descripcion'],
            ':password' => hash("sha256", $_REQUEST['usuario'] . $_REQUEST['password']),
            ':fechaUltimaConexion' => time(),
            ':numConexiones' => "1"
        ];
        $registrarUsuario->execute($param);
        session_start();
        $_SESSION['usuarioDAW210DBProyectoTema5'] = $_REQUEST['usuario'];
        $_SESSION['FechaHoraUltimaConexionAnterior'] = null;
        header("Location: programa.php"); //redirigimos
    } catch (PDOException $miExcepcionPDO) {//declaración de excepcionesPDO
        echo "<div class='box'>";
        echo "<p class='error'>Error " . $miExcepcionPDO->getMessage() . "</p>";
        echo "<p class='error'>Cod.Error " . $miExcepcionPDO->getCode() . "</p>";
        echo "<h2 class='error'>Error en la conexión con la base de datos</h2>";
        echo "</div>";
    } finally {
        unset($miDB); //cerramos la conexión
    }
} else {
    ?>
    <!DOCTYPE html>

    <html>
        <head>
            <title>Login Logoff Tema 5</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="../webroot/css/styleRegistro.css">
            <link rel="stylesheet" type="text/css" href="../webroot/css/style_1.css">
        </head>
        <body>
            <div id="cabecera">
                <div id="titulo">
                    <h1>Registro</h1>
                </div>
                <div class="nav">
                    <a href="../../../proyectoDWES/indexProyectoDWES.html" class="boton volver"><img class="icoBoton" src="../webroot/img/volver-flecha-izquierda.png"><span class="texto">Volver</span></a>
                </div>
            </div>
            <div id="contenedor"> 
                <div id="form">
                    <form class="descript" action= "<?php echo $_SERVER["PHP_SELF"] ?>" method= "POST">
                        <div class="campos">
                            <label class="labelTitle" for="usuario">Usuario: </label>
                            <input  class="inputText" type="text" name="usuario" placeholder="Introduzca el nombre del usuario"
                                    value="<?php echo isset($_REQUEST["usuario"]) ? $aError["usuario"] ? null : $_REQUEST["usuario"] : null ?>">
                                    <?php echo isset($aError["usuario"]) ? "<span class='error'>" . "<br>" . $aError["usuario"] . "</span>" : null ?>
                        </div>
                        <div class="campos">
                            <label class="labelTitle" for="descripcion">Descripción: </label>
                            <input  class="inputText" type="text" name="descripcion" placeholder="Introduzca la descripción del usuario"
                                    value="<?php echo isset($_REQUEST["descripcion"]) ? $aError["descripcion"] ? null : $_REQUEST["descripcion"] : null ?>">
                                    <?php echo isset($aError["descripcion"]) ? "<span class='error'>" . "<br>" . $aError["descripcion"] . "</span>" : null ?>
                        </div>

                        <div class="campos">
                            <label class="labelTitle" for="password">Password: </label>
                            <input  class="inputText" type="password" name="password" 
                                    value="<?php echo isset($_REQUEST["password"]) ? $aError["password"] ? null : $_REQUEST["password"] : null ?>">
                                    <?php echo isset($aError["password"]) ? "<span class='error'>" . "<br>" . $aError["password"] . "</span>" : null ?>
                        </div>
                        <div class="campos">
                            <label class="labelTitle" for="passwordComprobacion">Repite el Password: </label>
                            <input  class="inputText" type="password" name="passwordComprobacion"
                                    value="<?php echo isset($_REQUEST["passwordComprobacion"]) ? $aError["passwordComprobacion"] ? null : $_REQUEST["passwordComprobacion"] : null ?>">
                                    <?php echo isset($aError["passwordComprobacion"]) ? "<span class='error'>" . "<br>" . $aError["passwordComprobacion"] . "</span>" : null ?>
                        </div>
                        <div class="campos">
                            <label class="labelTitle" for="img">Imagen de perfil: </label>
                            <input  class="inputText" type="file" name="img" 
                                    value="<?php echo isset($_REQUEST["img"]) ? $aError["img"] ? null : $_REQUEST["img"] : null ?>">
                                    <?php echo isset($aError["img"]) ? "<span class='error'>" . "<br>" . $aError["img"] . "</span>" : null ?>
                        </div>
                        <div class="botonSend">
                            <input class="botonEnvio" type= "submit" value= "registro" name= "registro">
                            <a class="botonEnvio" href="../login.php">Cancelar</a>
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
