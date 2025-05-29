<?php
session_start(); // Inicia la sesión
$accountId = $_SESSION['accountId'];
if (isset($_SESSION['access_token'])) {
    $accessToken = $_SESSION['access_token'];
    $instanceUrl = $_SESSION['instance_url'];
    
    }

$curl = curl_init();
$endpoint = "/services/data/v62.0/query/?q=SELECT+ContentDocumentId+FROM+ContentDocumentLink+WHERE+LinkedEntityId='$accountId'";
$url = $instanceUrl . $endpoint;
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $accessToken,  // Autenticación con el token de acceso
        'Content-Type: application/json'
    ),
));
// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($curl);

// Verificar si ocurrió un error en la solicitud
if(curl_errno($curl)) {
    echo 'Error de cURL: ' . curl_error($ch);
}

// Cerrar la conexión cURL
curl_close($curl);

// Decodificar la respuesta JSON
$data = json_decode($response, true);

// Mostrar la respuesta
if (isset($data)) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "$accountId";
    $documentId = $data["records"][2]["ContentDocumentId"];
    $_SESSION['documentId'] = $documentId;
  
} else {
    echo "No se pudo obtener la respuesta de Salesforce.";
}




?>