<?php
session_start();
if (!isset($_SESSION['usuarioDAW210DBProyectoTema5'])) {
    header("Location: ../login.php");
    exit;
}
if (isset($_REQUEST['idioma']) && $_REQUEST['idioma'] =='esp'){
    setcookie('idioma', 'esp');
    header("Location: programa.php");
    exit;
}
if ($_COOKIE['idioma'] == 'esp') {
    header("Location: programa.php");
    exit;
}

if (isset($_POST['close'])) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Login Logoff Chaper 5</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../webroot/css/stylePrograma.css">
        <link rel="stylesheet" type="text/css" href="../webroot/css/style_1.css">
    </head>
    <body>
        <div id="cabecera">
            <div id="titulo">
                <h1>Login Logoff Chaper 5</h1>
            </div>
            <div class="nav">
                <a href="../../../proyectoDWES/indexProyectoDWES.html" class="boton volver"><img class="icoBoton" src="../webroot/img/volver-flecha-izquierda.png"><span class="texto">Back</span></a>
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
                    <h3>Welcome <?php echo$oUsuario->T01_DescUsuario; ?></h3>
                    <?php
                    if ($oUsuario->T01_NumConexiones == 1) {
                        echo "<h4>It is your first connection. Thank you very much for trusting us.</h4>";
                    } else {
                        echo "<h4>This is your " . $oUsuario->T01_NumConexiones . " connection.</h4>";
                        echo "<h4>His last connection was " . date('d/m/Y H:i:s', $fechaHoraUltimaConexionAnterior) . ".</h4>";
                    }
                } catch (PDOException $miExcepcionPDO) {
                    echo "<div class = 'contenedorError'>";
                    echo "<div class = 'box'>";
                    echo "<p class = 'error'>Error " . $miExcepcionPDO->getMessage() . "</p>";
                    echo "<p class = 'error'>Cod.Error " . $miExcepcionPDO->getCode() . "</p>";
                    echo "<h2 class = 'error'>Failed to connect to the database</h2>";
                    echo "</div>";
                } finally {
                    unset($miConexion); //cerramos la conexión
                }
                ?>
                <div id="idiomas">
                    <form  name="setIdioma" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <label for="idioma">language</label>
                        <select id="idioma" name="idioma" onchange="this.form.submit()">              
                            <option value="esp" <?php echo ($_COOKIE['idioma']) == 'esp' ? 'selected' : '' ?>>Spanish</option>
                            <option value="eng" <?php echo ($_COOKIE['idioma']) == 'eng' ? 'selected' : '' ?>>English</option>
                        </select>
                    </form>
                </div>
                <?php
                ?>
                <div class="botones">
                    <form  name="logout" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <button class="botonEnvio" type="submit" name='close' value="Cerrar Sesion" >Close Session</button>
                    </form>
                    <a href="../../proyectoTema5.html"><button class="botonEnvio">Back</button></a>
                    <a href="detalles.php"><button class="botonEnvio">Server Details</button></a>
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