<?php
/*
 * @autor: Miguel Angel Aranda Garcia <miguela.aragar@educa.jcyl.es>
 * @version 1.0
 * @since 08/12/2020 1.0 Editar perfil:
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

    $sql = "SELECT T01_DescUsuario,T01_FechaHoraUltimaConexion,T01_NumConexiones FROM T01_Usuario where T01_CodUsuario=:codUsuario"; //Creamos la consulta mysql con los parametros bind
    $consultaDatosUsuario = $miDB->prepare($sql);
    $consultaDatosUsuario->bindParam(":codUsuario", $_SESSION['usuarioDAW210DBProyectoTema5']); //Declaramos el parametro bind
    $consultaDatosUsuario->execute();

    $oUsuario = $consultaDatosUsuario->fetchObject();

    $descripUsu = $oUsuario->T01_DescUsuario;
    $fechaUltimaConexion = $oUsuario->T01_FechaHoraUltimaConexion;
    $numConexion = $oUsuario->T01_NumConexiones;
} catch (PDOException $miExcepcionPDO) {
    echo "<p class='error'>Error " . $miExcepcionPDO->getMessage() . "</p>";
    echo "<p class='error'>Cod.Error " . $miExcepcionPDO->getCode() . "</p>";
    echo "<h2 class='error'>Error en la conexión con la base de datos</h2>";
} finally {
    unset($miDB); //cerramos la conexión
}

require_once '../core/201130libreriaValidacion.php'; //incluimos la libreria de validación

define("OBLIGATORIO", 1); //definimos e inicializamos la constante obligatorio a 1

$entradaOK = true; //declaramos y inicializamos la variable entradaObligatorioK, esta variable decidira si es correcta la entrada de datos del formulario

$aError = [//declaramos y inicializamos el array de los errores de los campos del formulario a null
    "descripcion" => null
];

if (isset($_REQUEST["edit"])) {

    $aError["descripcion"] = validacionFormularios::comprobarAlfabetico($_REQUEST["descripcion"], 255, 1, OBLIGATORIO); //Validamos la entrada del formulario para el campo textfieldObligatorio siendo este alfabetico

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
        $sqlUpdate = 'UPDATE T01_Usuario SET T01_DescUsuario=:descripcion WHERE T01_CodUsuario=:codUsuario'; //Creamos la consulta mysql con los parametros bind

        $actualizarUsu = $miDB->prepare($sqlUpdate); //Preparamos la consulta
//bindeamos los campos para la consulta
        $param = [':codUsuario' => $_SESSION['usuarioDAW210DBProyectoTema5'],
            ':descripcion' => $_REQUEST['descripcion'] 
        ];
        $actualizarUsu->execute($param);

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
            <title>Editar Pefil</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="../webroot/css/styleEdit.css">
            <link rel="stylesheet" type="text/css" href="../webroot/css/style_1.css">
        </head>
        <body>
            <div id="cabecera">
                <div id="titulo">
                    <h1>Editar Pefil</h1>
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
                            <input  class="inputText" type="text" name="usuario" placeholder="Introduzca el nombre del usuario" disabled
                                    value="<?php echo $_SESSION['usuarioDAW210DBProyectoTema5'] ?>">
                        </div>

                        <div class="campos">
                            <label class="labelTitle" for="descripcion">Descripción: </label>
                            <input  class="inputText" type="text" name="descripcion" placeholder="Introduzca la descripción del usuario"
                                    value="<?php echo isset($_REQUEST["descripcion"]) ? $aError["descripcion"] ? null : $_REQUEST["descripcion"] : $descripUsu ?>">
                                    <?php echo isset($aError["descripcion"]) ? "<span class='error'>" . "<br>" . $aError["descripcion"] . "</span>" : null ?>
                        </div>

                        <div class="campos">
                            <label class="labelTitle" for="ultimaConexion">Ultima Conexión: </label>
                            <input  class="inputText" type="text" name="ultimaConexion" disabled
                                    value="<?php echo date('d/m/Y H:i:s', $fechaUltimaConexion) ?>">          
                        </div>

                        <div class="campos">
                            <label class="labelTitle" for="numConexion">Número de conexiones: </label>
                            <input  class="inputText" type="text" name="numConexion" disabled
                                    value="<?php echo $numConexion ?>"> 
                        </div>

                        <div class="botonSend">
                            <input class="botonEnvio" type= "submit" value="Editar Perfil" name= "edit">
                            <a class="botonEnvio" href="programa.php">Cancelar</a>
                            <a class="botonEnvio" href="cambiarPassword.php">Cambiar Password</a>
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
