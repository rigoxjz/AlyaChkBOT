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

if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $messageText = $update['message']['text'];
    $username = $update['message']['chat']['username'] ?? 'Desconocido';

    // ID del admin
    $adminId = 1292171163;

    // Comando /start
    if ($messageText === '/start') {
        $response = "¡Bienvenido! Soy tu bot. Aquí están los comandos disponibles:\n";
        $response .= "/id - Ver tu tipo de usuario.\n";
        $response .= "/genkey [cantidad][m/h/d] - Generar una nueva clave (solo admin).\n";
        $response .= "/keys - Ver las claves activas (solo admin).\n";
        $response .= "/deletekey [key] - Eliminar una clave (solo admin).\n";
        $response .= "/addpremium [id] - Agregar un usuario premium (solo admin).\n";
        $response .= "/mypremium - Ver tu estado premium.\n";
        $response .= "/claim [key] - Reclamar una clave activa.\n";
        $response .= "/listpremiums - Ver todos los usuarios premium (solo admin).\n";
        sendMessage($chatId, $response);
    }

    // Comando /id (ver tipo de usuario)
    if ($messageText === '/id') {
        if ($chatId == $adminId) {
            sendMessage($chatId, "✅ Eres el <b>Administrador</b>.");
        } else {
            $query = "SELECT chat_id FROM premium_users WHERE chat_id = $1";
            $result = pg_query_params($conn, $query, array($chatId));

            if ($result && pg_num_rows($result) > 0) {
                sendMessage($chatId, "✅ Eres un <b>Usuario Premium</b>.");
            } else {
                sendMessage($chatId, "❌ Eres un <b>Usuario Normal</b>.");
            }
        }
    }

    // Comando /genkey (solo admin)
    if (strpos($messageText, '/genkey') === 0 && $chatId == $adminId) {
        $timeRegex = '/(\d+)([mdh])/';
        preg_match($timeRegex, $messageText, $matches);

        if (count($matches) < 3) {
            sendMessage($chatId, "❌ Error: Debes especificar el tiempo (m=min, h=hora, d=día). Ej: /genkey 5m");
            return;
        }

        $duration = $matches[1];
        $unit = $matches[2];
        $expirationDate = date("Y-m-d H:i:s", strtotime("+{$duration} {$unit}"));

        $key = bin2hex(random_bytes(16));

        $query = "INSERT INTO keys (chat_id, key, expiration, claimed) VALUES ($1, $2, $3, FALSE)";
        $result = pg_query_params($conn, $query, array($chatId, $key, $expirationDate));

        if ($result) {
            sendMessage($chatId, "✅ Clave generada: $key. Expira en {$duration} {$unit}.");
        } else {
            sendMessage($chatId, "❌ Error al generar la clave.");
        }
    }

    // Comando /claim (reclamar clave)
    if (strpos($messageText, '/claim') === 0) {
        $parts = explode(' ', $messageText);
        if (count($parts) < 2) {
            sendMessage($chatId, "❌ Error: Debes proporcionar una clave.");
            return;
        }

        $keyToClaim = $parts[1];

        $query = "SELECT key FROM keys WHERE key = $1 AND claimed = FALSE AND expiration > NOW() LIMIT 1";
        $result = pg_query_params($conn, $query, array($keyToClaim));

        if ($result && pg_num_rows($result) > 0) {
            $updateQuery = "UPDATE keys SET claimed = TRUE WHERE key = $1";
            pg_query_params($conn, $updateQuery, array($keyToClaim));

            $insertPremiumQuery = "INSERT INTO premium_users (chat_id, username) VALUES ($1, $2) ON CONFLICT (chat_id) DO NOTHING";
            pg_query_params($conn, $insertPremiumQuery, array($chatId, $username));

            sendMessage($chatId, "✅ Has reclamado la clave: $keyToClaim y ahora eres un usuario premium.");
        } else {
            sendMessage($chatId, "❌ Clave inválida o ya reclamada.");
        }
    }

    // Comando /mypremium
    if ($messageText === '/mypremium') {
        if ($chatId == $adminId) {
            sendMessage($chatId, "✅ Eres el administrador.");
        } else {
            $query = "SELECT chat_id FROM premium_users WHERE chat_id = $1";
            $result = pg_query_params($conn, $query, array($chatId));

            if ($result && pg_num_rows($result) > 0) {
                sendMessage($chatId, "✅ Eres un usuario premium.");
            } else {
                sendMessage($chatId, "❌ No eres un usuario premium.");
            }
        }
    }

    // Comando /listpremiums (solo admin)
    if ($messageText === '/listpremiums' && $chatId == $adminId) {
        $query = "SELECT chat_id, username FROM premium_users";
        $result = pg_query($conn, $query);

        if ($result) {
            $premiumList = "✅ Usuarios premium:\n";
            while ($row = pg_fetch_assoc($result)) {
                $premiumList .= "ID: {$row['chat_id']}, Usuario: {$row['username']}\n";
            }
            sendMessage($chatId, $premiumList ?: "❌ No hay usuarios premium.");
        } else {
            sendMessage($chatId, "❌ Error al obtener la lista.");
        }
    }
}
?>
