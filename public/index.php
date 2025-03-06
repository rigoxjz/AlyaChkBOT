<?php
// Obtener el token del bot de Telegram desde las variables de entorno
$token = getenv('TELEGRAM_BOT_TOKEN');
if (empty($token)) {
    die("❌ Error: No se encontró el token del bot.");
}

// Obtener credenciales de PostgreSQL
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');

// Conectar a PostgreSQL
$connectionString = "host=$host port=$port dbname=$database user=$user password=$password";
$conn = pg_connect($connectionString);

if (!$conn) {
    die("❌ Error al conectar a la base de datos: " . pg_last_error());
}

// Función para obtener la hora actual en México
function getCurrentTimeMexico() {
    $now = new DateTime('now', new DateTimeZone('America/Mexico_City'));
    return $now->format('Y-m-d H:i:s');
}

// Función para enviar un mensaje
function sendMessage($chatID, $respuesta, $message_id = null) {
    global $token;
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        'chat_id' => $chatID,
        'text' => $respuesta,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];
    if ($message_id) {
        $data['reply_to_message_id'] = $message_id;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

// Limpiar claves y usuarios expirados
function cleanExpiredData($conn) {
    $now = getCurrentTimeMexico();

    // Eliminar claves no reclamadas que han expirado
    pg_query_params($conn, "DELETE FROM keys WHERE claimed = FALSE AND expiration < $1", array($now));

    // Eliminar usuarios premium expirados
    pg_query_params($conn, "DELETE FROM premium_users WHERE expiration < $1", array($now));
}

// Eliminar todas las claves (solo admin)
function deleteAllKeys($conn) {
    pg_query($conn, "DELETE FROM keys");
}

// Obtener mensaje de Telegram
$update = json_decode(file_get_contents("php://input"), true);

if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $messageText = trim($update['message']['text']);
    $adminId = 1292171163; // Reemplaza con tu ID de Telegram

    // Limpiar datos expirados en cada interacción
    cleanExpiredData($conn);

    // Comando /start
    if ($messageText === '/start') {
        $response = "¡Bienvenido! Comandos disponibles:\n";
        $response .= "/genkey [cantidad][m/h/d] - Generar clave (admin).\n";
        $response .= "/keys - Ver claves (admin).\n";
        $response .= "/deleteallkeys - Eliminar todas las claves (admin).\n";
        $response .= "/mypremium - Ver estado premium.\n";
        $response .= "/claim [key] - Reclamar clave premium.\n";
        $response .= "/clean - Limpiar expirados (admin).\n";
        sendMessage($chatId, $response);
    }

    // Comando /keys (admin)
    if ($messageText === '/keys' && $chatId == $adminId) {
        $result = pg_query($conn, "SELECT \"key\", expiration, claimed FROM keys");
        if (pg_num_rows($result) === 0) {
            sendMessage($chatId, "🔑 No hay claves activas.");
        } else {
            $keysList = "🔑 Claves activas:\n";
            while ($row = pg_fetch_assoc($result)) {
                $estado = $row['claimed'] === 't' ? "✅ Usada" : "❌ Disponible";
                $keysList .= "Clave: <code>{$row['key']}</code>\nExpira: {$row['expiration']}\nEstado: {$estado}\n\n";
            }
            sendMessage($chatId, $keysList);
        }
    }

    // Comando /mypremium
    if ($messageText === '/mypremium') {
        $result = pg_query_params($conn, "SELECT expiration FROM premium_users WHERE chat_id = $1", array($chatId));

        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $expirationDate = $row['expiration'];

            // Verificar si la membresía ya expiró
            if (strtotime($expirationDate) < strtotime(getCurrentTimeMexico())) {
                pg_query_params($conn, "DELETE FROM premium_users WHERE chat_id = $1", array($chatId));
                sendMessage($chatId, "❌ No eres premium.");
            } else {
                sendMessage($chatId, "🌟 Eres premium hasta: {$expirationDate}.");
            }
        } else {
            sendMessage($chatId, "❌ No eres premium.");
        }
    }

    // Comando /genkey (admin)
    if (strpos($messageText, '/genkey') === 0 && $chatId == $adminId) {
        if (!preg_match('/(\d+)([mdh])/', $messageText, $matches)) {
            sendMessage($chatId, "❌ Uso incorrecto. Ejemplo: /genkey 5m");
            return;
        }

        $duration = (int)$matches[1];
        $unit = $matches[2];
        $now = new DateTime(getCurrentTimeMexico());

        switch ($unit) {
            case 'm': $now->modify("+{$duration} minutes"); break;
            case 'h': $now->modify("+{$duration} hours"); break;
            case 'd': $now->modify("+{$duration} days"); break;
        }

        $expirationDate = $now->format('Y-m-d H:i:s');
        $key = bin2hex(random_bytes(8));
        pg_query_params($conn, "INSERT INTO keys (chat_id, \"key\", expiration, claimed) VALUES ($1, $2, $3, FALSE)", array($chatId, $key, $expirationDate));

        sendMessage($chatId, "✅ Clave generada: <code>$key</code>\nExpira: $expirationDate.");
    }

    // Comando /claim [key]
    if (strpos($messageText, '/claim') === 0) {
        $parts = explode(" ", $messageText);
        if (count($parts) < 2) {
            sendMessage($chatId, "❌ Debes proporcionar una clave. Ejemplo: /claim 123456");
            return;
        }

        $key = trim($parts[1]);

        $result = pg_query_params($conn, "SELECT expiration FROM keys WHERE \"key\" = $1 AND claimed = FALSE", array($key));

        if (!$result || pg_num_rows($result) === 0) {
            sendMessage($chatId, "❌ Clave inválida o ya ha sido reclamada.");
            return;
        }

        $row = pg_fetch_assoc($result);
        $expirationDate = $row['expiration'];

        pg_query_params($conn, "UPDATE keys SET claimed = TRUE WHERE \"key\" = $1", array($key));
        pg_query_params($conn, "INSERT INTO premium_users (chat_id, expiration) VALUES ($1, $2) ON CONFLICT (chat_id) DO UPDATE SET expiration = $2", array($chatId, $expirationDate));

        sendMessage($chatId, "✅ ¡Felicidades! Ahora eres usuario premium hasta el $expirationDate.");
    }
}
?>
