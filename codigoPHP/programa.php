<?php
session_start();
if (!isset($_SESSION['usuarioDAW210DBProyectoTema5'])) {
    header("Location: ../login.php");
    exit;
}

/*if (isset($_REQUEST['idiomac']) && $_REQUEST['idiomac'] =='eng') {
    setcookie('idioma', 'eng');
    header("Location: programaEng.php");
    exit;
}

if ($_COOKIE['idioma'] == 'eng') {
    header("Location: programaEng.php");
    exit;
}*/

if (isset($_POST['close'])) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}
$saludo = [
    "esp" => "Bienvenido",
    "eng" => "Welcome",
    "prt" => "bem-vinda",
];
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Login Logoff Tema 5</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../webroot/css/stylePrograma.css">
        <link rel="stylesheet" type="text/css" href="../webroot/css/style_1.css">
    </head>
    <body>
        <div id="cabecera">
            <div id="titulo">
                <h1>Login Logoff Tema 5</h1>
            </div>
            <div class="nav">
                <a href="../../../proyectoDWES/indexProyectoDWES.html" class="boton volver"><img class="icoBoton" src="../webroot/img/volver-flecha-izquierda.png"><span class="texto">Volver</span></a>
            </div>
        </div>
        <div id="contenedor"> 
            <?php
            require_once "../config/conexionBDPDO.php";
            try {
                $miDB = new PDO(DNS, USER, PASSWORD, CODIFICACION);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $usuarioInsertUsuario = $_SESSION['usuarioDAW210DBProyectoTema5'];
                $fechaHoraUltimaConexionAnterior = $_SESSION['FechaHoraUltimaConexionAnterior'];


                $consultarUsu = "SELECT T01_DescUsuario, T01_NumConexiones FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Creamos la consulta mysq
                $datosUsuario = $miDB->prepare($consultarUsu); //Preparamos la consulta
                $datosUsuario->bindParam(":CodUsuario", $usuarioInsertUsuario);
                $datosUsuario->execute(); //Ejecutamos la consulta preparada
                $oUsuario = $datosUsuario->fetchObject(); //creamos el objeto PDO de usuario
                ?>
                <div id="datos">
                    <h3> <?php echo  $saludo[$_COOKIE['idioma']] ." ". $oUsuario->T01_DescUsuario; ?></h3>
                    <?php
                    if ($oUsuario->T01_NumConexiones == 1) {
                        echo "<h4>Es su primera conexión. Muchas gracias por confiar en nosotros.</h4>";
                    } else {
                        echo "<h4>Esta es su " . $oUsuario->T01_NumConexiones . " conexión.</h4>";
                        echo "<h4>Su ultima conexión fue el " . date('d/m/Y H:i:s', $fechaHoraUltimaConexionAnterior) . ".</h4>";
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
                ?>
                <div class="botones">
                    <form  name="logout" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <button class="botonEnvio" type="submit" name='close' value="Cerrar Sesion" >Cerrar Sesion</button>
                    </form>
                    <a href="../../proyectoTema5.html"><button class="botonEnvio">Volver</button></a>
                    <a href="detalles.php"><button class="botonEnvio">Detalles Servidor</button></a>
                    <a href="editarPerfil.php"><button class="botonEnvio">Editar Perfil</button></a>
                </div>    
            </div>
        </div>
        <footer>
            <div class="pie">
                <a href="../../../index.html" class="nombre">Miguel Ángel Aranda García</a>
                <a href="https://github.com/MiguelAranda-Sauces" class="git" ><img class="git" src="../webroot/img/git.png"></a>
            </div>

        </footer>
    </body>
</html>