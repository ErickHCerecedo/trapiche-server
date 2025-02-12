<?php
    /**
     * Deletes a post into the database.
     *
     * This script delete a post from the Trapiche database.
     *
     * @package    TrapicheDigital
     * @subpackage API
     * @author     allickard
     * @version    1.0
     * @since      1.0
     * @copyright  Trapiche Digital
     */

    header('Content-Type: text/html; charset=UTF-8'); 

    include "valida_sesion.php";
    include "conexion.php";

    validaSesion();

    $id_entrada=$_POST["id_entrada"];

    $query = $conexion->prepare("UPDATE publicacion SET estado = FALSE WHERE id_entrada = :id_entrada");

    $query->bindParam(':id_entrada', $id_entrada);

    if($query)
    {
        $query->execute();
        echo 1;
    }
    else
    {
        echo 0;
    }
?>