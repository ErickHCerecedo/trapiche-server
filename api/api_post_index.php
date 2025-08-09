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
    header('Content-Type: application/json; charset=UTF-8');

	include "conexion.php";

    function json_response($status, $message, $data = null, $http_code = 200) {
        http_response_code($http_code);
        $response = [
            'status' => $status,
            'message' => $message
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }


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
        
        json_response('success', 'Entradas obtenidas.', $entradas, 200);

    } catch (PDOException $e) {
        json_response('error', 'Error en la base de datos.', ['details' => $e->getMessage()], 500);
    }

?>