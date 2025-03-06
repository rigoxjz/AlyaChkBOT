<?php
// Obtener el token del bot de Telegram desde las variables de entorno
$token = getenv('TELEGRAM_BOT_TOKEN');
if (empty($token)) {
    die("❌ Error: No se encontró el token del bot.");
}

// Obtener las credenciales de la base de datos PostgreSQL desde las variables de entorno
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');

// Conectar a la base de datos PostgreSQL
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

// Función para enviar un mensaje de Telegram
function sendMessage($chatID, $respuesta, $message_id = null) {
    global $token;
    $url = "https://api.telegram.org/bot$token/sendMessage?disable_web_page_preview=true&chat_id=".$chatID."&parse_mode=HTML&text=".urlencode($respuesta);
    if ($message_id) {
        $url .= "&reply_to_message_id=".$message_id;
    }
    file_get_contents($url);
}

// Obtener el contenido del mensaje recibido desde Telegram
$update = file_get_contents("php://input");
$update = json_decode($update, true);

// Verificar si el mensaje es válido
if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $messageText = $update['message']['text'];
    $username = isset($update['message']['chat']['username']) ? $update['message']['chat']['username'] : "SinUsername";

    // ID del administrador principal (Creador)
    $adminId = 1292171163;

    // Comando /claim (Reclamar una clave)
    if (strpos($messageText, '/claim') === 0) {
        $parts = explode(' ', $messageText);
        if (count($parts) < 2) {
            sendMessage($chatId, "❌ Error: Usa /claim [clave]");
            return;
        }

        $keyToClaim = $parts[1];

        $query = "SELECT key, claimed, expiration FROM keys WHERE key = $1 LIMIT 1";
        $result = pg_query_params($conn, $query, array($keyToClaim));

        if (!$result || pg_num_rows($result) === 0) {
            sendMessage($chatId, "❌ Clave no encontrada.");
            return;
        }

        $row = pg_fetch_assoc($result);
        if ($row['claimed'] === 't') {
            sendMessage($chatId, "❌ La clave ya fue reclamada.");
            return;
        }

        $now = getCurrentTimeMexico();
        if (strtotime($row['expiration']) < strtotime($now)) {
            sendMessage($chatId, "❌ La clave ha expirado.");
            return;
        }

        // Reclamar la clave y hacer premium al usuario
        pg_query_params($conn, "UPDATE keys SET claimed = TRUE WHERE key = $1", array($keyToClaim));
        pg_query_params($conn, "INSERT INTO premium_users (chat_id, username, expiration) VALUES ($1, $2, $3) ON CONFLICT (chat_id) DO UPDATE SET expiration = $3", array($chatId, $username, $row['expiration']));

        sendMessage($chatId, "✅ Has reclamado la clave y ahora eres premium hasta: {$row['expiration']}.");
    }

    // Comando /mypremium
    if ($messageText === '/mypremium') {
        if ($chatId == $adminId) {
            sendMessage($chatId, "🛡 Eres el creador del bot.");
            return;
        }

        $result = pg_query_params($conn, "SELECT expiration FROM premium_users WHERE chat_id = $1", array($chatId));

        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            sendMessage($chatId, "🌟 Eres premium hasta: {$row['expiration']}.");
        } else {
            sendMessage($chatId, "🆓 Eres un usuario normal.");
        }
    }

    // Comando /id
    if ($messageText === '/id') {
        if ($chatId == $adminId) {
            sendMessage($chatId, "🛡 Eres el creador del bot.");
            return;
        }

        $result = pg_query_params($conn, "SELECT expiration FROM premium_users WHERE chat_id = $1", array($chatId));

        if ($result && pg_num_rows($result) > 0) {
            sendMessage($chatId, "🌟 Eres un usuario premium.");
        } else {
            sendMessage($chatId, "🆓 Eres un usuario normal.");
        }
    }
}
?>
