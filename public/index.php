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

    // Eliminar claves no reclamadas que hayan expirado
    $deleteKeys = pg_query_params($conn, "DELETE FROM keys WHERE claimed = FALSE AND expiration < $1", array($now));

    // Eliminar usuarios premium cuya membresía haya expirado
    $deleteUsers = pg_query_params($conn, "DELETE FROM premium_users WHERE expiration < $1", array($now));

    if (!$deleteKeys || !$deleteUsers) {
        error_log("Error al eliminar datos expirados: " . pg_last_error($conn));
    }
}


// Eliminar todas las claves
function deleteAllKeys($conn) {
    pg_query($conn, "DELETE FROM keys");
}

// Obtener mensaje de Telegram
$update = json_decode(file_get_contents("php://input"), true);

if (isset($update['message'])) {
    cleanExpiredData($conn); // Limpia los datos expirados antes de procesar cualquier comando

    $chatId = $update['message']['chat']['id'];
    $messageText = trim($update['message']['text']);
    $adminId = 1292171163;

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
    $now = getCurrentTimeMexico(); // Obtener la fecha y hora actual en México

    // Marcar como expiradas las claves cuyo tiempo ya pasó
    pg_query_params($conn, "UPDATE keys SET claimed = TRUE WHERE expiration < $1", array($now));

    // Consultar solo las claves que siguen disponibles
    $result = pg_query($conn, "SELECT \"key\", expiration, claimed FROM keys WHERE expiration >= NOW()");

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
    $now = getCurrentTimeMexico(); // Obtener la hora actual en México

    // Verificar si el usuario es premium
    $result = pg_query_params($conn, "SELECT expiration FROM premium_users WHERE chat_id = $1", array($chatId));

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $expirationDate = DateTime::createFromFormat('Y-m-d H:i:s', $row['expiration'], new DateTimeZone('America/Mexico_City'));

        if ($expirationDate && $expirationDate > new DateTime($now)) {
            sendMessage($chatId, "🌟 Eres premium hasta: " . $expirationDate->format('Y-m-d H:i:s'));
        } else {
            // Si ya expiró, eliminamos al usuario de premium
            pg_query_params($conn, "DELETE FROM premium_users WHERE chat_id = $1", array($chatId));
            sendMessage($chatId, "❌ No eres premium.");
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

    // Verificar si la clave existe y está disponible
    $result = pg_query_params($conn, "SELECT expiration FROM keys WHERE \"key\" = $1 AND claimed = FALSE", array($key));

    if (!$result || pg_num_rows($result) === 0) {
        sendMessage($chatId, "❌ Clave inválida o ya ha sido reclamada.");
        return;
    }

    $row = pg_fetch_assoc($result);
    $expirationDate = $row['expiration'];

    // Marcar la clave como reclamada
    pg_query_params($conn, "UPDATE keys SET claimed = TRUE WHERE \"key\" = $1", array($key));

    // Agregar al usuario a la tabla de usuarios premium
    pg_query_params($conn, "INSERT INTO premium_users (chat_id, expiration) VALUES ($1, $2) ON CONFLICT (chat_id) DO UPDATE SET expiration = $2", array($chatId, $expirationDate));

    sendMessage($chatId, "✅ ¡Felicidades! Ahora eres usuario premium hasta el $expirationDate.");
}

    
    // Comando /deleteallkeys (admin)
    if ($messageText === '/deleteallkeys' && $chatId == $adminId) {
        deleteAllKeys($conn);
        sendMessage($chatId, "🗑 Todas las claves han sido eliminadas.");
    }

    // Comando /clean (admin)
    if ($messageText === '/clean' && $chatId == $adminId) {
        cleanExpiredData($conn);
        sendMessage($chatId, "🗑 Claves y usuarios expirados eliminados.");
    }
}
?>
