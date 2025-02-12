<?php

/**
 * Establishes a connection to the database.
 *
 * This script is responsible for creating a connection to the database
 * used by the Trapiche Digital API.
 *
 * @package    TrapicheDigital
 * @subpackage API
 * @author     Allickard
 * @version    1.0
 * @since      1.0
 * @copyright  Trapiche Digital
 */

$username = sha1($_POST["username"]);
$password = sha1($_POST["password"]);

echo "Username: $username <br>";
echo "Password: $password <br>";

if($username=="dc76e9f0c0006e8f919e0c515c66dbba3982f785" and $password=="7c4a8d09ca3762af61e59520943dc26494f8941b")
{
    session_start();
    $_SESSION["acceso"]=1;
    
    echo 1;
}
else
    echo 0;

?>