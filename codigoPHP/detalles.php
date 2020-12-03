<?php
session_start();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Detalles LogingLogoff</title>
        <style type="text/css">
            table{
                position: relative;
                margin-left: 15%;
                width: 70% !important; 
                margin-top: 1%;
            }

            td,th{
                border: 1px solid black;
                padding: 0px;

            }

            th{
                text-align:center;
                padding: 0px;
                background-color: #ccf !important;
            }

            table{
                border:1px solid black;
            }

            td:nth-of-type(1){
                background-color: #ccf;
            }

            td:nth-of-type(2){
                background-color: #ddd;
            }

            h2{
                text-align: center;
            }

        </style>

    </head>
    <body>

        <?php
        /*
         * @autor: Miguel Angel Aranda Garcia <miguela.aragar@educa.jcyl.es>
         * @since 2.0
         * Ejericico Mostramos la variables super globales.
         */
        ?>
        <table>
            <h2>$_SERVER</h2>
            <tbody>
                <tr>
                    <th colspan="2">$_SEVER</th>
                </tr>
                <tr>
                    <th>Variable</th>
                    <th>Valor</th>
                </tr>
                <?php
                foreach ($_SERVER as $variable => $valor) {
                    echo("<tr>");
                    echo("<td>" . $variable . "</td>");
                    echo("<td>" . $valor . "</td>");
                    echo("</tr>");
                }
                ?>
            </tbody>
        </table>
        <h2>$_SESSION</h2>
        <?php
        if (!empty($_SESSION)) {
            ?>
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">$_SESSION</th>
                    </tr>
                    <tr>
                        <th>Variable</th>
                        <th>Valor</th>
                    </tr>
                    <?php
                    foreach ($_SESSION as $variable => $valor) {
                        echo("<tr>");
                        echo("<td>" . $variable . "</td>");
                        echo("<td>" . $valor . "</td>");
                        echo("</tr>");
                    }
                    ?>
                </tbody>
            </table>

            <?php
        }
        ?>
        <h2>$_GET</h2>
        <?php
        if (!empty($_GET)) {
            ?>
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">$_GET</th>
                    </tr>
                    <tr>
                        <th>Variable</th>
                        <th>Valor</th>
                    </tr>
                    <?php
                    foreach ($_GET as $variable => $valor) {
                        echo("<tr>");
                        echo("<td>" . $variable . "</td>");
                        echo("<td>" . $valor . "</td>");
                        echo("</tr>");
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        ?>
        <h2>$_POST</h2>
        <?php
        if (!empty($_POST)) {
            ?>
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">$_POST</th>
                    </tr>
                    <tr>
                        <th>Variable</th>
                        <th>Valor</th>
                    </tr>
                    <?php
                    foreach ($_POST as $variable => $valor) {
                        echo("<tr>");
                        echo("<td>" . $variable . "</td>");
                        echo("<td>" . $valor . "</td>");
                        echo("</tr>");
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        ?>
        <h2>$_COOKIE</h2>
        <?php
        if (!empty($_COOKIE)) {
            ?>
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">$_COOKIE</th>
                    </tr>
                    <tr>
                        <th>Variable</th>
                        <th>Valor</th>
                    </tr>
                    <?php
                    foreach ($_COOKIE as $variable => $valor) {
                        echo("<tr>");
                        echo("<td>" . $variable . "</td>");
                        echo("<td>" . $valor . "</td>");
                        echo("</tr>");
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        ?>
        <h2>$_REQUEST</h2>
        <?php
        if (!empty($_REQUEST)) {
            ?>
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">$_REQUEST</th>
                    </tr>
                    <tr>
                        <th>Variable</th>
                        <th>Valor</th>
                    </tr>
                    <?php
                    foreach ($_REQUEST as $variable => $valor) {
                        echo("<tr>");
                        echo("<td>" . $variable . "</td>");
                        echo("<td>" . $valor . "</td>");
                        echo("</tr>");
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        echo "<br>";
        phpinfo();
        ?>
    </body>
</html>