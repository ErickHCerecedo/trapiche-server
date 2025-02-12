<?php
    /**
     * Updates a post from the database.
     *
     * This script update a post from the Trapiche database.
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

    $id_entrada = $_POST["id_entrada"];
    $titulo = $_POST["titulo"];
    $subtitulo = $_POST["subtitulo"];
    $portada = $_POST["portada"];
    $contenido = $_POST["contenido"];
    $estado = $_POST["estado"];

    $query = $conexion->prepare("UPDATE publicacion SET titulo = :titulo, subtitulo = :subtitulo, portada = :portada, contenido = :contenido, estado = :estado, updated_at = NOW() WHERE id_entrada = :id_entrada");

    $query->bindParam(':titulo', $titulo);
    $query->bindParam(':subtitulo', $subtitulo);
    $query->bindParam(':portada', $portada);
    $query->bindParam(':contenido', $contenido);
    $query->bindParam(':estado', $estado);
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