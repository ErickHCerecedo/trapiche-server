<?php
    /**
     * Creates a new post into the database.
     *
     * This script creates a new post into the Trapiche database.
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

    $titulo=$_POST["titulo"];
    $subtitulo=$_POST["subtitulo"];
    $portada=$_POST["portada"];
    $contenido=$_POST["contenido"];

    function generateSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $timestamp = date('YmdHis'); // Formato: YYYYMMDDHHMMSS
        return $timestamp . '-' . $slug;
    }

    $slug = generateSlug($titulo);

    $query = $conexion->prepare("INSERT INTO publicacion (titulo, subtitulo, portada, contenido, created_at, updated_at, slug, estado) VALUES (:titulo, :subtitulo, :portada, :contenido, NOW(), NOW(), :slug, TRUE)");

    $query->bindParam(':titulo', $titulo);
    $query->bindParam(':subtitulo', $subtitulo);
    $query->bindParam(':portada', $portada);
    $query->bindParam(':contenido', $contenido);
    $query->bindParam(':slug', $slug);

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