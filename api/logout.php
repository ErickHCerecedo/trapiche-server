<?php

/**
 * Destroys the connection to the database.
 *
 * This script is responsible for destroying the connection to the database
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
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

require 'conexion.php';

function cerrarSesion($conexion)
{
    $token = $_COOKIE['session_token'] ?? null;

    if ($token) {
        try {
            // Eliminar la sesión de la base de datos
            $stmt = $conexion->prepare("DELETE FROM sesion WHERE session_token = :token");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            // Registrar error, pero continuar
        }

        // Borrar cookie segura
        setcookie('session_token', '', time() - 3600, '/', '', true, true);
    }

    // También destruir la sesión PHP, por compatibilidad
    session_start();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    echo json_encode([
        "status" => "success",
        "message" => "Sesión cerrada correctamente."
    ]);
}

cerrarSesion($conexion);

?>