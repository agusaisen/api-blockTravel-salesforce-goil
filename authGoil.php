<?php
session_start(); // Inicia la sesión
$login = $_SESSION['login'] ?? "login"; // Para ver si es en cliente o empresa
if (isset($_SESSION['access_token_goil'])) {
    $accessToken = $_SESSION['access_token_goil'];
    $url = '*******'; // Goil auth url
    $headers = [
        'x-client-platform: webclient-platform',
        'Authorization: Bearer: ' . $accessToken,
    ];
    
    // Inicializa cURL
    $ch = curl_init($url);
    // Configura cURL para enviar la solicitud POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // Ejecuta la solicitud y obtiene la respuesta
    $response = curl_exec($ch);
    // Manejo de errores
    if (curl_errno($ch)) {
        echo 'Error de cURL: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }
    // Cierra cURL
    curl_close($ch);

    // Decodifica la respuesta JSON
    $data = json_decode($response, true);
    // Verifica si la autenticación fue exitosa
    if (isset($data)) {
        
        $accessToken2 = $data['data']['accessToken'] ?? 'Token no encontrado';
        
        $headers2 = [
            'x-client-platform: webclient-platform',
            'Authorization: Bearer: ' . $accessToken2,
        ];
        $endpoint = '*********'; //Goil endpoint url
        $ch2 = curl_init();
        // Configurar las opciones de cURL
        curl_setopt($ch2, CURLOPT_URL, $endpoint);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);
        // Ejecutar la solicitud y obtener la respuesta
        $response2 = curl_exec($ch2);
        // Verificar si ocurrió un error en la solicitud
        if (curl_errno($ch2)) {
            echo 'Error de cURL: ' . curl_error($ch2);
            header('Location: https://blocktravelagency.com/'.$login);   // redirigir al login
        }
        $data2 = json_decode($response2, true);
        // Cerrar la conexión cURL
        curl_close($ch2);
        if (isset($data2)) {
           
            $usr_id = $data2['data']['metadata']['account']['salesforce.id'] ?? 'id no encontrado';
            //echo "Salesforce id: ".$usr_id;
            if($usr_id == 'id no encontrado'){
                echo "Hubo un error al obtener los datos";
                header('Location: https://blocktravelagency.com/'.$login.'?error=1');   // Redirigir al login con mensaje de error de teléfono no encontrado
            }
            $_SESSION['usr_id'] = $usr_id;
            header('Location: getTokenSalesforce.php'); // Redirige a la página donde usarás el token
        } else {
            echo "Hubo un error al obtener los datos";
            header('Location: https://blocktravelagency.com/'.$login);   // Redirigir al login
        }

    }


} else {
    echo "No se encontró el token de acceso.";
    header('Location: https://blocktravelagency.com/'.$login);   // Redirigir al login
}
?>
