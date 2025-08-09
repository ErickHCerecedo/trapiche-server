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

header('Access-Control-Allow-Origin: *'); // Considerar limitar esto en producción
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'conexion.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Intentar leer como JSON primero
    $input = json_decode(file_get_contents('php://input'), true);
    
    $email = isset($input["email"]) ? trim($input["email"]) : null;
    $password = isset($input["password"]) ? trim($input["password"]) : null;

    if (empty($email) || empty($password)) {
        http_response_code(400);
        $response = [
            "status" => "error",
            "message" => "Correo y contraseña son obligatorios. ",
            "data" => [
                "email" => $email,
                "password" => $password
            ]
        ];
        echo json_encode($response);
        exit;
    }

    try {
        // Buscar el usuario por email
        $stmt = $conexion->prepare("SELECT id, nombre, email, password FROM usuario WHERE email = :email LIMIT 1");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y si la contraseña es correcta
        if ($user && password_verify($password, $user["password"])) {

            // Generar token seguro
            $session_token = bin2hex(random_bytes(64));
            $expires_at = date('Y-m-d H:i:s', strtotime('+3 months'));
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

            // Guardar sesión en la base de datos
            $insert = $conexion->prepare("INSERT INTO sesion 
                (usuario_id, session_token, ip_address, user_agent, expires_at)
                VALUES (:usuario_id, :session_token, :ip_address, :user_agent, :expires_at)");

            $insert->execute([
                ':usuario_id' => $user['id'],
                ':session_token' => $session_token,
                ':ip_address' => $ip_address,
                ':user_agent' => $user_agent,
                ':expires_at' => $expires_at
            ]);

            // Detectar si es local o producción
            $is_local = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);

            $cookie_options = [
                "expires" => time() + 7776000,
                "path" => "/",
                "secure" => !$is_local, // true en producción, false en local
                "httponly" => false,
                "samesite" => $is_local ? "Lax" : "None"
            ];

            // Solo poner domain en producción
            if (!$is_local) {
                $cookie_options["domain"] = ".trapichedigital.com.mx";
            }

            setcookie("session_token", $session_token, $cookie_options);

            // También devolver el token en el JSON (por si lo usas con fetch())
            $response = [
                "status" => "success",
                "message" => "Inicio de sesión exitoso.",
                "session_token" => $session_token,
                "user" => [
                    "id" => $user["id"],
                    "nombre" => $user["nombre"],
                    "email" => $user["email"]
                ]
            ];

        } else {
            http_response_code(401);
            $response = [
                "status" => "error",
                "message" => "Credenciales incorrectas."
            ];
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $response = [
            "status" => "error",
            "message" => "Error en la base de datos.",
            "details" => $e->getMessage()
        ];
    }
} else {
    http_response_code(405);
    $response = [
        "status" => "error",
        "message" => "Método no permitido."
    ];
}

echo json_encode($response);
