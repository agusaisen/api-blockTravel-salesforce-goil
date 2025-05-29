<?php
session_start(); // Inicia la sesión
$accountId = $_SESSION['accountId'];
if (isset($_SESSION['access_token'])) {
    $accessToken = $_SESSION['access_token'];
    $instanceUrl = $_SESSION['instance_url'];    
}


$curl = curl_init();
$endpoint = "/services/data/v62.0/query/?q=SELECT+ContentDocumentId,ContentDocument.Title+FROM+ContentDocumentLink+WHERE+LinkedEntityId='$accountId'";
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
if (curl_errno($curl)) {
    echo 'Error de cURL: ' . curl_error($curl);
}

// Cerrar la conexión cURL
curl_close($curl);

// Decodificar la respuesta JSON
$responseData = json_decode($response, true);

if (!isset($responseData['records']) || empty($responseData['records'])) {
    die("No se encontraron archivos para la cuenta especificada.");
}
// Preparar un arreglo para enviar el ID y el título de cada archivo
$resultado = [];
foreach ($responseData['records'] as $record) {
    // Obtenemos el título del ContentDocument si está disponible
    $titulo = isset($record['ContentDocument']['Title']) ? $record['ContentDocument']['Title'] : 'Sin título';
    $resultado[] = [
        'id'    => $record['ContentDocumentId'],
        'title' => $titulo,
    ];
}

// Devolver el resultado en formato JSON
header('Content-Type: application/json');
echo json_encode($resultado);

?>