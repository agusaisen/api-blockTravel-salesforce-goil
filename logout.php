<?php
session_start();
$login=$_SESSION['login'] ?? "login";
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
header('Location: https://blocktravelagency.com/' . $login);   //redirigir al login
exit();
