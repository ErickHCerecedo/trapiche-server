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

	function validaSesion()
	{
		session_start();

		if(!isset($_SESSION["acceso"])) 
		{
			header("Location: http://localhost:8101/");
		}
	}
?>