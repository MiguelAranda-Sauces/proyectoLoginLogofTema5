<?php
//-------------------------------------Base--------------------------------------------------
/*define("DNS","mysql:host=ip servidor;dbname=nombre base de datos");
define("USER","nombre usuario");
define("PASSWORD","Contraseña"); 
define('CODIFICACION',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));//Codificación utf8 */

//-------------------------------------Heracio--------------------------------------------------
//conexion 1 and 1
/*define("DNS","mysql:host=db771560945.hosting-data.io;dbname=db771560945"); //Variable con el servidor y la base de datos
define("USER","dbo771560945"); //Usuario administrador de esa base de datos
define("PASSWORD","Covid1234$"); //Contraseña del usuario
define('CODIFICACION',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Codificación utf8 */


//-------------------------------------Casa--------------------------------------------------
//no olvidar comentar el hostblind de mysql en su archivo de configuración
define('DNS',"mysql:host=192.168.33.20;dbname=DAW210DBProyectoTema5");
define('USER',"usuarioDAW210DBProyectoTema5");
define('PASSWORD',"paso");
define('CODIFICACION',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Codificación utf8 
?>