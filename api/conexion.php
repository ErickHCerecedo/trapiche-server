<?php 

    /**
     * Database connection script.
     *
     * This script establishes a connection to the database.
     *
     * @package    TrapicheDigital
     * @subpackage API
     * @author     allickard
     * @version    1.0
     * @since      1.0
     * @copyright  Trapiche Digital
     */

$env = 'development';

if ($env === 'development') {
    $server = 'db';
    $database = 'trapiche_db';
    $username = 'root';
    $password = 'S3cur3P@ssw0rd!';
} else if ($env === 'production') {
    $server = 'mysql.trapichedigital.com.mx';
    $database = 'trapiche';
    $username = 'trapiche_dbadmin';
    $password = 'trapiche.db.master';
}

try{
    $db="mysql:host=$server;dbname=$database;charset=utf8mb4";
    $conexion=new PDO($db,$username,$password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}


?>