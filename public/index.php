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

// Función para limpiar claves y usuarios expirados
function cleanExpiredData($conn) {
    $now = getCurrentTimeMexico();

    // Eliminar claves no reclamadas que hayan expirado
    pg_query_params($conn, "DELETE FROM keys WHERE claimed = FALSE AND expiration < $1", array($now));

    // Eliminar usuarios premium cuya membresía haya expirado
    pg_query_params($conn, "DELETE FROM premium_users WHERE expiration < $1", array($now));
}

// Función para eliminar todas las claves y reiniciar la secuencia de IDs
function deleteAllKeys($conn) {
    pg_query($conn, "DELETE FROM keys");
    pg_query($conn, "ALTER SEQUENCE keys_id_seq RESTART WITH 1"); // Reiniciar la secuencia de IDs
}

// Obtener el contenido del mensaje recibido desde Telegram
$update = file_get_contents("php://input");
$update = json_decode($update, true);

// Verificar si el mensaje es válido
if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $messageText = $update['message']['text'];
    $username = isset($update['message']['chat']['username']) ? $update['message']['chat']['username'] : "SinUsername";

    // ID del administrador principal
    $adminId = 1292171163;

    // Comando /start
    if ($messageText === '/start') {
        $response = "¡Bienvenido! Soy tu bot. Aquí están los comandos disponibles:\n";
        $response .= "/genkey [cantidad][m/h/d] - Generar una clave premium (solo admin).\n";
        $response .= "/keys - Ver claves activas (solo admin).\n";
        $response .= "/deletekey [key] - Eliminar una clave (solo admin).\n";
        $response .= "/deleteallkeys - Eliminar todas las claves (solo admin).\n";
        $response .= "/mypremium - Ver tu estado premium.\n";
        $response .= "/claim [key] - Reclamar una clave premium.\n";
        $response .= "/clean - Eliminar claves y usuarios expirados (solo admin).\n";
        $response .= "/id - Ver tu rol (admin, premium o usuario normal).\n";
        sendMessage($chatId, $response);
    }

    // Comando /keys (solo admin)
    if ($messageText === '/keys' && $chatId == $adminId) {
        $result = pg_query($conn, "SELECT key, expiration, claimed FROM keys");

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
            sendMessage($chatId, "🌟 Eres premium hasta: {$row['expiration']}.");
        } else {
            sendMessage($chatId, "❌ No eres premium.");
        }
    }

    // Comando /deleteallkeys (solo admin)
    if ($messageText === '/deleteallkeys' && $chatId == $adminId) {
        deleteAllKeys($conn);
        sendMessage($chatId, "🗑 Todas las claves han sido eliminadas y la numeración ha sido reiniciada.");
    }

    // Comando /clean (solo admin)
    if ($messageText === '/clean' && $chatId == $adminId) {
        cleanExpiredData($conn);
        sendMessage($chatId, "🗑 Se han eliminado claves y usuarios expirados.");
    }

    // Comando /genkey (solo admin)
    if (strpos($messageText, '/genkey') === 0 && $chatId == $adminId) {
        preg_match('/(\d+)([mdh])/', $messageText, $matches);
        if (count($matches) < 3) {
            sendMessage($chatId, "❌ Error: Usa el formato /genkey [cantidad][m/h/d]. Ej: /genkey 5m");
            return;
        }

        $duration = $matches[1];
        $unit = $matches[2];
        $now = new DateTime(getCurrentTimeMexico());

        switch ($unit) {
            case 'm':
                $now->modify("+{$duration} minutes");
                break;
            case 'h':
                $now->modify("+{$duration} hours");
                break;
            case 'd':
                $now->modify("+{$duration} days");
                break;
        }

        $expirationDate = $now->format('Y-m-d H:i:s');
        $key = bin2hex(random_bytes(8));

        $query = "INSERT INTO keys (chat_id, key, expiration, claimed) VALUES ($1, $2, $3, FALSE)";
        $result = pg_query_params($conn, $query, array($chatId, $key, $expirationDate));

        if ($result) {
            sendMessage($chatId, "✅ Clave generada: <code>$key</code>\nExpira en {$expirationDate} (Hora México).");
        } else {
            sendMessage($chatId, "❌ Error al generar la clave.");
        }
    }
    // Comando /claim [key]
    if (strpos($messageText, '/claim') === 0) {
        $parts = explode(' ', $messageText);
        if (count($parts) < 2) {
            sendMessage($chatId, "❌ Debes proporcionar una clave. Uso: /claim [key]");
            return;
        }

        $key = $parts[1];
        $now = getCurrentTimeMexico();

        // Verificar si la clave es válida
        $result = pg_query_params($conn, "SELECT id, expiration, claimed FROM keys WHERE key = $1", array($key));
        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            if ($row['claimed'] === 't') {
                sendMessage($chatId, "❌ Esta clave ya ha sido utilizada.");
            } elseif ($row['expiration'] < $now) {
                sendMessage($chatId, "❌ Esta clave ha expirado.");
            } else {
                // Marcar la clave como usada
                pg_query_params($conn, "UPDATE keys SET claimed = TRUE WHERE id = $1", array($row['id']));
                // Establecer al usuario como premium
                $expirationDate = date('Y-m-d H:i:s', strtotime('+30 days'));
                pg_query_params($conn, "INSERT INTO premium_users (chat_id, expiration) VALUES ($1, $2) ON CONFLICT (chat_id) DO UPDATE SET expiration = $2", array($chatId, $expirationDate));
                sendMessage($chatId, "✅ Clave reclamada con éxito. Eres premium hasta: $expirationDate.");
            }
        } else {
            sendMessage($chatId, "❌ Clave no válida.");
        }

}
?>
