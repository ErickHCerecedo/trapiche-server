<?php
$inputPassword = "adminTrapiche2025"; // Contraseña ingresada por el usuario
$storedHash = '$2b$12$0qKrVx.bnbInRe1TkZUyDus.WRHR887sCFONwCxt07Ak5joHAu77q'; // Hash almacenado

if (password_verify($inputPassword, $storedHash)) {
    echo "La contraseña es válida.";
} else {
    echo "La contraseña es incorrecta.";
}