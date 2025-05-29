<?php
session_start(); // Inicia la sesión
$login = $_SESSION['login'] ?? "login";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir el Access Token enviado desde JavaScript
    $phoneVerificationToken = $_POST["accessToken"] ?? '';
    $clientId = "67502b36e2f4680020253de7";
    $loginUrl = 'https://community.goil.app/api/v1/authentication/login';

    if (!empty($phoneVerificationToken)) {
        $postFields = [
            'clientId' => $clientId,
            'phoneVerificationToken' => $phoneVerificationToken,
        ];


        // Inicializa cURL
        $ch = curl_init($loginUrl);
        // Configura cURL para enviar la solicitud POST
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));

        // Agrega el header x-client-platform
        $headers = [
            'x-client-platform: webclient-platform'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Ejecuta la solicitud y obtiene la respuesta
        $response = curl_exec($ch);
        // Manejo de errores
        if (curl_errno($ch)) {
            echo 'Error de cURL: ' . curl_error($ch);
            curl_close($ch);
            // exit;
            sleep(2);
            header('Location: https://blocktravelagency.com/' . $login);   //redirigir al login
        }
        // Cierra cURL
        curl_close($ch);

        // Decodifica la respuesta JSON
        $data = json_decode($response, true);
        // Verifica si la autenticación fue exitosa
        if (isset($data)) {
            $accessToken = $data['data']['userAccounts'][0]['accessToken'] ?? 'Token no encontrado';

           
            $_SESSION['access_token_goil'] = $accessToken;
            header('Location: authGoil.php'); // Redirige a la página donde usarás el token

            exit();
        } else {
            // Manejo de errores
            echo "Error al obtener el token de acceso:<br>";
            print_r($data);
        }

        echo "Token recibido: " . htmlspecialchars($accessToken);

    } else {
        echo "No se recibió un token.";
        sleep(2);
        header('Location: https://blocktravelagency.com/' . $login);   //redirigir al login
    }
} else {
    echo "Método no permitido.";
    sleep(2);
    header('Location: https://blocktravelagency.com/' . $login);   //redirigir al login
}
?>