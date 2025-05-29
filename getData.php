<?php
session_start(); // Inicia la sesión
$login = $_SESSION['login'] ?? "login"; // Para ver si es cliente o empresa

if (isset($_SESSION['access_token'])) {
    $accessToken = $_SESSION['access_token'];
    $instanceUrl = $_SESSION['instance_url'];
    $usrId = $_SESSION['usr_id'];
   
} else {
       echo json_encode(["error" => "token_error", "login" => $login], JSON_UNESCAPED_UNICODE); // Error de token
   }

$accountId=$_SESSION['usr_id'];
$curl = curl_init();
$endpoint = "/services/data/v62.0/query/?q=SELECT%20FIELDS(All)%20FROM%20ACCOUNT%20WHERE%20Id='$accountId'%20ORDER%20BY%20Name%20LIMIT%20200"; // Salesforce endpoint
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

$response = curl_exec($curl);
// Decodificar la respuesta JSON
$data = json_decode($response, true);
// Mostrar la respuesta
if (isset($data)) {
    $_SESSION['accountId'] = $accountId;
    $name = $data["records"][0]["Name"];
    $email = $data["records"][0]["Email__c"];
    $phone = $data["records"][0]["Phone"];
    $millas = $data["records"][0]["Total_millas_disponibles__c"] ?? "-";

    $datos = [
        "name" => $name,
        "mail" => $email,
        "millas" => $millas,
        "phone" => $phone
    ];
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;// para levantar en el html
} else {
    $datos = [
        "error" => "response_error",
         "login" => $login,
         "accountId" => $accountId,
    ];
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);

}
?>
