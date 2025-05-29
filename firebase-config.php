<?php
// Configuración de Firebase (oculta en el servidor)
$config = [
    "apiKey" => "AIzaSyCxnJ8Ii9OlXzP-kDYxDs8OB0uQP-jpUVM",
    "authDomain" => "goil-community.firebaseapp.com",
    "projectId" => "goil-community",
    "storageBucket" => "goil-community.appspot.com",
    "messagingSenderId" => "546325875679",
    "appId" => "1:546325875679:web:ba325ca83cfef474e2d26a",
    "measurementId" => "G-4WKN4PF3QW"
];

// Convertir el array a JSON y devolverlo
header('Content-Type: application/json');
echo json_encode($config);
?>