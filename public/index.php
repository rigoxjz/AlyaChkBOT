<?php
// Obtener el token del bot de Telegram desde las variables de entorno
$token = getenv('TELEGRAM_BOT_TOKEN') ?: die("❌ Error: No se encontró el token del bot.");

// Obtener las credenciales de la base de datos PostgreSQL desde las variables de entorno
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');

// Validar credenciales antes de conectar
if (!$host || !$port || !$user || !$password || !$database) {
    die("❌ Error: Faltan credenciales de la base de datos.");
}

// Conectar a la base de datos PostgreSQL
$connectionString = "host=$host port=$port dbname=$database user=$user password=$password";
$conn = pg_connect($connectionString);
if (!$conn) {
    die("❌ Error al conectar a la base de datos: " . pg_last_error());
}

// Función para enviar un mensaje a Telegram con cURL
function sendMessage($chatID, $respuesta, $message_id = null) {
    global $token;
    $url = "https://api.telegram.org/bot$token/sendMessage";
    
    $postData = [
        'chat_id' => $chatID,
        'text' => $respuesta,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ];
    if ($message_id) {
        $postData['reply_to_message_id'] = $message_id;
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if (!$response) {
        error_log("Error al enviar mensaje a Telegram");
    }
}

// Obtener y procesar el mensaje recibido
$update = json_decode(file_get_contents("php://input"), true);
if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $messageText = $update['message']['text'];
    $username = $update['message']['chat']['username'] ?? 'Desconocido';
    
    $adminId = 1292171163;
    
    if ($messageText === '/start') {
        $response = "¡Bienvenido! Aquí están los comandos disponibles:\n";
        $response .= "/genkey [cantidad][m/h/d] - Generar una clave.\n";
        $response .= "/keys - Ver claves activas.\n";
        $response .= "/deletekey [key] - Eliminar una clave.\n";
        $response .= "/claim [key] - Reclamar una clave.\n";
        sendMessage($chatId, $response);
    }
    
    // Comando /genkey (solo admin)
    if (preg_match('/^\/genkey (\d+)([mhd])$/', $messageText, $matches) && $chatId == $adminId) {
        $duration = (int)$matches[1];
        $unit = $matches[2];
        $expirationDate = match ($unit) {
            'm' => date("Y-m-d H:i:s", strtotime("+$duration minutes")),
            'h' => date("Y-m-d H:i:s", strtotime("+$duration hours")),
            'd' => date("Y-m-d H:i:s", strtotime("+$duration days")),
        };

        $key = bin2hex(random_bytes(8));
        $query = "INSERT INTO keys (key, expiration, claimed) VALUES ($1, $2, FALSE)";
        $result = pg_query_params($conn, $query, [$key, $expirationDate]);

        sendMessage($chatId, $result ? "✅ Clave generada: $key. Expira en $duration $unit." : "❌ Error al generar clave.");
    }
    
    // Comando /claim
    if (preg_match('/^\/claim (.+)$/', $messageText, $matches)) {
        $keyToClaim = $matches[1];
        $query = "SELECT key FROM keys WHERE key = $1 AND claimed = FALSE AND expiration > NOW() LIMIT 1";
        $result = pg_query_params($conn, $query, [$keyToClaim]);

        if ($result && pg_num_rows($result) > 0) {
            pg_query_params($conn, "UPDATE keys SET claimed = TRUE WHERE key = $1", [$keyToClaim]);
            pg_query_params($conn, "INSERT INTO premium_users (chat_id, username) VALUES ($1, $2) ON CONFLICT DO NOTHING", [$chatId, $username]);
            sendMessage($chatId, "✅ Clave reclamada: $keyToClaim. Ahora eres premium.");
        } else {
            sendMessage($chatId, "❌ Clave inválida o ya reclamada.");
        }
    }

    // Cerrar conexión
    pg_close($conn);
}
