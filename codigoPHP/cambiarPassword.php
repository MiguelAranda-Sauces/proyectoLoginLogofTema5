<?php
/*
 * @autor: Miguel Angel Aranda Garcia <miguela.aragar@educa.jcyl.es>
 * @version 1.0
 * @since 08/12/2020 1.0 Cambiar Password :
 */
session_start();
if (!isset($_SESSION['usuarioDAW210DBProyectoTema5'])) { //si el usuario se logeo anteriormente lo dirigimos al programa
    header("Location: ../login.php");
    exit;
}
require_once "../config/conexionBDPDO.php"; //incluimos la conexión a la BD
try {
    $miDB = new PDO(DNS, USER, PASSWORD, CODIFICACION); //Creamos el objeto PDO
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT T01_Password FROM T01_Usuario where T01_CodUsuario=:codUsuario"; //Creamos la consulta mysql con los parametros bind
    $consultaDatosUsuario = $miDB->prepare($sql);
    $consultaDatosUsuario->bindParam(":codUsuario", $_SESSION['usuarioDAW210DBProyectoTema5']); //Declaramos el parametro bind
    $consultaDatosUsuario->execute();

    $oUsuario = $consultaDatosUsuario->fetchObject();

    $passUsu = $oUsuario->T01_Password;
} catch (PDOException $miExcepcionPDO) {
    echo "<p class='error'>Error " . $miExcepcionPDO->getMessage() . "</p>";
    echo "<p class='error'>Cod.Error " . $miExcepcionPDO->getCode() . "</p>";
    echo "<h2 class='error'>Error en la conexión con la base de datos</h2>";
} finally {
    unset($miDB); //cerramos la conexión
}

require_once '../core/libreriaValidacion.php'; //incluimos la libreria de validación

define("OBLIGATORIO", 1); //definimos e inicializamos la constante obligatorio a 1
define("MAX", 8); //definimos e inicializamos la constante amx a 8
define("MIN", 1); //definimos e inicializamos la constante min a 1

$entradaOK = true; //declaramos y inicializamos la variable entradaObligatorioK, esta variable decidira si es correcta la entrada de datos del formulario

$aError = [//declaramos y inicializamos el array de los errores de los campos del formulario a null
    "passwordold" => null,
    "passwordNew" => null,
    "passwordNewRepetido" => null,
];

if (isset($_REQUEST["cambioPass"])) {

    $aError["passwordold"] = validacionFormularios::validarPassword($_REQUEST["passwordold"], MAX, MIN, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo textfieldObligatorio siendo este alfabetico

    $aError["passwordNew"] = validacionFormularios::validarPassword($_REQUEST["passwordNew"], MAX, MIN, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo textfieldObligatorio siendo este alfabetico
    $aError["passwordNewRepetido"] = validacionFormularios::validarPassword($_REQUEST["passwordNewRepetido"], MAX, MIN, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo textfieldObligatorio siendo este alfabetico
    if ($aError["passwordNew"] == null && $aError["passwordNewRepetido"] == null) {
        if ($_REQUEST["passwordNew"] != $_REQUEST["passwordNewRepetido"]) {
            $aError["passwordNewRepetido"] = 'Las password no coinciden';
        } else {
            if ($aError["passwordold"] == null) {
                if ($passUsu != hash("sha256", $_SESSION['usuarioDAW210DBProyectoTema5'] . $_REQUEST["passwordold"])) {
                    $aError["passwordNewRepetido"] = "Error al cambiar el password";
                }
            }
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
        $sqlUpdate = 'UPDATE T01_Usuario SET T01_Password=:password WHERE T01_CodUsuario=:codUsuario'; //Creamos la consulta mysql con los parametros bind

        $actualizarUsu = $miDB->prepare($sqlUpdate); //Preparamos la consulta
//bindeamos los campos para la consulta
        $param = [':codUsuario' => $_SESSION['usuarioDAW210DBProyectoTema5'],
           ':password' => hash('sha256', $_SESSION['usuarioDAW210DBProyectoTema5'].$_REQUEST['passwordNew'])
        ];
        $actualizarUsu->execute($param);

        header("Location: editarPerfil.php"); //redirigimos
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
            <title>Cambiar Password</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="../webroot/css/stylePass.css">
            <link rel="stylesheet" type="text/css" href="../webroot/css/style_1.css">
        </head>
        <body>
            <div id="cabecera">
                <div id="titulo">
                    <h1>Cambiar Password</h1>
                </div>
                <div class="nav">
                    <a href="../../../proyectoDWES/indexProyectoDWES.html" class="boton volver"><img class="icoBoton" src="../webroot/img/volver-flecha-izquierda.png"><span class="texto">Volver</span></a>
                </div>
            </div>
            <div id="contenedor"> 
                <div id="form">
                    <form class="descript" action= "<?php echo $_SERVER["PHP_SELF"] ?>" method= "POST">
                        <div class="campos">
                            <label class="labelTitle" for="passwordold">Password Anterior: </label>
                            <input  class="inputText" type="password" name="passwordold" placeholder="Inserte el password" >
                            <?php echo isset($aError["passwordold"]) ? "<span class='error'>" . "<br>" . $aError["passwordold"] . "</span>" : null ?>
                        </div>
                        <div class="campos">
                            <label class="labelTitle" for="passwordNew">Password Nuevo: </label>
                            <input  class="inputText" type="password" name="passwordNew" placeholder="Inserte el nuevo password"
                                    value="<?php echo isset($_REQUEST["passwordNew"]) ? $aError["passwordNew"] ? null : $_REQUEST["passwordNew"] : null ?>">
                                    <?php echo isset($aError["passwordNew"]) ? "<span class='error'>" . "<br>" . $aError["passwordNew"] . "</span>" : null ?>
                        </div>
                        <div class="campos">
                            <label class="labelTitle" for="passwordNewRepetido">Repite el Password Nuevo: </label>
                            <input  class="inputText" type="password" name="passwordNewRepetido" placeholder="Repita el nuevo password"
                                    value="<?php echo isset($_REQUEST["passwordNewRepetido"]) ? $aError["passwordNewRepetido"] ? null : $_REQUEST["passwordNewRepetido"] : null ?>">
                                    <?php echo isset($aError["passwordNewRepetido"]) ? "<span class='error'>" . "<br>" . $aError["passwordNewRepetido"] . "</span>" : null ?>
                        </div>

                        <div class="botonSend">
                            <input class="botonEnvio" type= "submit" value="Cambiar Password" name= "cambioPass">
                            <a class="botonEnvio" href="editarPerfil.php">Cancelar</a>
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
