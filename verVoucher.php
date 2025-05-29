<?php
session_start(); // Inicia la sesión
if (isset($_SESSION['access_token'])) {
    $accessToken = $_SESSION['access_token'];
    $instanceUrl = $_SESSION['instance_url'];
    $documentId = $_GET['fileId'];
    //$documentId="069Tg00000FRv3GIAT";
    //$usrId = $_SESSION['usr_id'];
   
// URL para acceder al contenido del archivo
$fileUrl = "$instanceUrl/services/data/v62.0/connect/files/$documentId/content";

// Inicializar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fileUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_HEADER, 1); // Incluir los headers en la respuesta

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("Error en cURL: " . curl_error($ch));
}

// Separar headers del cuerpo
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

curl_close($ch);

// Extraer Content-Type y filename de los headers
preg_match('/Content-Type:\s*([^\s]+)/', $headers, $contentType);
preg_match('/filename="([^"]+)"/', $headers, $filename);

$contentType = $contentType[1] ?? 'application/octet-stream';
$filename = $filename[1] ?? "archivo_$fileId";

// Verificar si el cuerpo está vacío
if (empty($body)) {
    die("El archivo está vacío o no se pudo obtener. Headers recibidos:\n$headers");
}

// Forzar descarga incluso en móviles
header("Content-Description: File Transfer");
header("Content-Type: $contentType");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");
header("Content-Length: " . strlen($body));
echo $body;
}
?>