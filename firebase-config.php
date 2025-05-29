<?php
// Configuración de Firebase 
$config = [
    "apiKey" => "*******", 
    "authDomain" => "******",
    "projectId" => "*******",
    "storageBucket" => "*********",
    "messagingSenderId" => "***********",
    "appId" => "**********",
    "measurementId" => "********"
];

// Convertir el array a JSON y devolverlo
header('Content-Type: application/json');
echo json_encode($config);
?>
