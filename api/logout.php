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

session_start();

session_destroy();

header("Location: http://localhost:8101/api");

?>