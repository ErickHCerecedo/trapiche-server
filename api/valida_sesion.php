<?php
/**
 * Validates the connection with the Trapiche database.
 *
 * This script is responsible for varify the user connection to the database
 * used by the Trapiche Digital API.
 *
 * @package    TrapicheDigital
 * @subpackage API
 * @author     Allickard
 * @version    1.0
 * @since      1.0
 * @copyright  Trapiche Digital
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

require 'conexion.php';

function validarSesionConToken($conexion)
{   
    // Obtener token desde cookie segura
    $token = $_COOKIE['session_token'] ?? null;

    if (!$token) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token de sesión no encontrado."
        ]);
        exit;
    }

    try {
        $stmt = $conexion->prepare("
            SELECT s.usuario_id, u.nombre, u.email
            FROM sesion s
            JOIN usuario u ON s.usuario_id = u.id
            WHERE s.session_token = :token AND s.expires_at > NOW()
            LIMIT 1
        ");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $session = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($session) {
            echo json_encode([
                "status" => "success",
                "message" => "Sesión válida.",
                "user" => [
                    "id" => $session["usuario_id"],
                    "name" => $session["nombre"],
                    "email" => $session["email"]
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Sesión inválida o expirada."
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Error al validar sesión.",
            "details" => $e->getMessage()
        ]);
    }
}

validarSesionConToken($conexion);

?>