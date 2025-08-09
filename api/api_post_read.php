<?php
    /**
     * Read a post from the database.
     *
     * This script read a post from the Trapiche database.
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

    if (!isset($_GET['id_entrada'])) {
        json_response('error', 'No se proporcionó el ID de la entrada.', null, 400);
    }

    $id_entrada = $_GET['id_entrada'];

    try {
        $query = $conexion->prepare(
            "SELECT p.id, p.titulo, p.subtitulo, p.portada, p.contenido, p.resumen, u.nombre AS autor, p.updated_at, p.created_at
            FROM publicacion p
            JOIN usuario u ON p.usuario_id = u.id
            WHERE p.id = :id_entrada"
        );
        $query->bindParam(':id_entrada', $id_entrada, PDO::PARAM_STR);
        $query->execute();

        $entrada = $query->fetch(PDO::FETCH_ASSOC);

        if ($entrada) {
            $entrada_arr = [
                'id_entrada' => $entrada['id'],
                'titulo' => mb_convert_encoding($entrada['titulo'], 'UTF-8', 'auto'),
                'subtitulo' => mb_convert_encoding($entrada['subtitulo'], 'UTF-8', 'auto'),
                'portada' => mb_convert_encoding($entrada['portada'], 'UTF-8', 'auto'),
                'contenido' => mb_convert_encoding($entrada['contenido'], 'UTF-8', 'auto'),
                'resumen' => mb_convert_encoding($entrada['resumen'], 'UTF-8', 'auto'),
                'autor' => mb_convert_encoding($entrada['autor'], 'UTF-8', 'auto'),
                'updated_at' => date('d/m/Y', strtotime($entrada['updated_at'])),
                'created_at' => date('d/m/Y', strtotime($entrada['created_at']))
            ];
            json_response('success', 'Entrada encontrada.', $entrada_arr, 200);
        } else {
            json_response('error', 'No se encontró la entrada.', null, 404);
        }
    } catch (PDOException $e) {
        json_response('error', 'Error en la base de datos.', ['details' => $e->getMessage()], 500);
    }

?>