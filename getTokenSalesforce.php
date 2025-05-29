<?php
session_start(); // Inicia la sesión
$login = $_SESSION['login'] ?? "login";
// Configuración
$clientId = '3MVG9YaE55JGKFNM6EyoQ3OS14s3KcNfJ0YZbBMcmZhtCavT4zHsw0p1FGdH_94eCvYseks8GZlHJm3J7QWf7';  // Reemplaza con tu Consumer Key
$clientSecret = '2031D499D37F22B370EE187DC5152F383DE76F5CFC14CF7CB2F48529FAB1F97B';  // Reemplaza con tu Consumer Secret

$username = 'integraciones@block.com'; // Reemplaza con el nombre de usuario de Salesforce
$password = 'Salesforce1!@'; // Reemplaza con la contraseña de Salesforce
$securityToken = 'oyyzopJ8msO2B5PD4RHSxR1B'; // Agrega tu token de seguridad al final de la contraseña
$loginUrl = 'https://login.salesforce.com/services/oauth2/token'; // Usa "https://test.salesforce.com" si es sandbox

// Datos del POST para obtener el token
$postFields = [
    'grant_type' => 'password',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'username' => $username,
    'password' => $password . $securityToken,
];

// Inicializa cURL
$ch = curl_init($loginUrl);

// Configura cURL para enviar la solicitud POST
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));

// Ejecuta la solicitud y obtiene la respuesta
$response = curl_exec($ch);

// Manejo de errores
if (curl_errno($ch)) {
    echo 'Error de cURL: ' . curl_error($ch);
    curl_close($ch);
    //exit;
    sleep(2);
    header('Location: https://blocktravelagency.com/' . $login);   //redirigir al login
}

// Cierra cURL
curl_close($ch);

// Decodifica la respuesta JSON
$data = json_decode($response, true);

// Verifica si la autenticación fue exitosa
if (isset($data['access_token'])) {
    
    $_SESSION['access_token'] = $data['access_token']; // Almacena el token en la sesión
    $_SESSION['instance_url'] = $data['instance_url']; // Almacena la URL de instancia
    //header('Location: getData.php'); // Redirige a la página donde usarás el token solo PHP
    header('Location: https://blocktravelagency.com/internal'); // Redirige a la página donde usarás el token HTML
    exit();
} else {
    // Manejo de errores
    echo "Error al obtener el token de acceso:<br>";
    print_r($data);
    sleep(2);
    header('Location: https://blocktravelagency.com/' . $login);   //redirigir al login
}

?>