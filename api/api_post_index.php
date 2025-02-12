<?php
	/**
     * Retrives a list of post from the database.
     *
     * This script retrives a list of post from the Trapiche database.
     *
     * @package    TrapicheDigital
     * @subpackage API
     * @author     allickard
     * @version    1.0
     * @since      1.0
     * @copyright  Trapiche Digital
     */

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: text/html; charset=UTF-8'); 

	include "conexion.php";

    try {
        $query=$conexion->prepare("SELECT p.id, p.titulo, p.subtitulo, p.portada, p.resumen, u.nombre AS autor, p.created_at, p.estado
            FROM publicacion p
            JOIN usuario u ON p.usuario_id = u.id
            ORDER BY p.created_at ASC
        ");

        $query->execute();

        $entradas = array();

        while ($entrada = $query->fetch(PDO::FETCH_ASSOC)) {
            $entrada_arr = array(
                'id_entrada' => $entrada['id'],
                'titulo' => mb_convert_encoding($entrada['titulo'], 'UTF-8', 'auto'),
                'subtitulo' => mb_convert_encoding($entrada['subtitulo'], 'UTF-8', 'auto'),
                'portada' => mb_convert_encoding($entrada['portada'], 'UTF-8', 'auto'),
                'autor' => mb_convert_encoding($entrada['autor'], 'UTF-8', 'auto'),
                'resumen' => mb_convert_encoding($entrada['resumen'], 'UTF-8', 'auto'),
                'created_at' => date('d/m/Y', strtotime($entrada['created_at'])),
                'estado' => $entrada['estado']
            );
            array_push($entradas, $entrada_arr);
        }
        
        echo json_encode($entradas);

    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }

?>