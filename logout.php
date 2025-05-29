<?php
session_start();
$login=$_SESSION['login'] ?? "login"; // Para ver si es cliente o empresa
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
header('Location: https://blocktravelagency.com/' . $login);   // Redirigir al login
exit();
