<?php
// Obtener el token del bot de Telegram desde las variables de entorno
$token = getenv('TELEGRAM_BOT_TOKEN');
if (empty($token)) {
    die("❌ Error: No se encontró el token del bot.");
}

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Pragma: no-cache");

//------TOKEN DEL BOT MIKASA ACKERMAN--------//
$website = "https://api.telegram.org/bot".$token;
$data = file_get_contents("php://input");
$json = json_decode($data, true);
$update = $json["message"];
$message = $update["text"];
$chatId = $update["chat"]["id"];


    $chatId = $update['message']['chat']['id'];
    $messageText = trim($update['message']['text']);
    $adminId = 1292171163;

include 'chk/functions.php';
include 'chk/bot.php';
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

$comandosReconocidos = ['/start', '/id', '/gts', '/bin', '/iban', '/sk', '/gen', '/extb', '/claim', '/keys', '/deleteallkeys', '/mypremium', '/clean', '/genkey'];

// Comprobar si el mensaje es un comando reconocido
if (in_array($messageText, $comandosReconocidos)) {
    // Manejo de comandos
} else {
    // Si no es un comando reconocido, ejecuta handleCommands()
    handleCommands($chatId, $messageText);
}


// Función para obtener la hora actual en México
function getCurrentTimeMexico() {
    $now = new DateTime('now', new DateTimeZone('America/Mexico_City'));
    return $now->format('Y-m-d H:i:s');
}

/*
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
*/
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


// Eliminar todas las claves y reiniciar el ID
function deleteAllKeys($conn) {
    pg_query($conn, "DELETE FROM keys"); // Eliminar todas las filas
    pg_query($conn, "ALTER SEQUENCE keys_id_seq RESTART WITH 1"); // Reiniciar el ID a 1
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
    $now = getCurrentTimeMexico(); // Obtener la hora actual

    // Consultar todas las claves, incluyendo las expiradas
    $result = pg_query($conn, "SELECT \"key\", expiration, claimed FROM keys");

    if (pg_num_rows($result) === 0) {
        sendMessage($chatId, "🔑 No hay claves activas.");
    } else {
        $keysList = "🔑 Claves activas:\n";
        while ($row = pg_fetch_assoc($result)) {
            $expirationDate = new DateTime($row['expiration'], new DateTimeZone('America/Mexico_City'));
            $nowDate = new DateTime($now);

            // Determinar el estado de la clave
            if ($expirationDate < $nowDate) {
                $estado = "⚫ Expirado";
            } elseif ($row['claimed'] === 't') {
                $estado = "🔴 Reclamado";
            } else {
                $estado = "🟢 Disponible";
            }

            // Agregar a la lista
            $keysList .= "Clave: <code>{$row['key']}</code>\nExpira: {$row['expiration']}\nEstado: {$estado}\n\n";
        }
        sendMessage($chatId, $keysList);
    }

    // Eliminar claves expiradas después de mostrarlas
    pg_query_params($conn, "DELETE FROM keys WHERE expiration < $1", array($now));
}






// Comando /mypremium
if ($messageText === '/mypremium') {
    $adminId = 1292171163; // ID del creador del bot
    $result = pg_query_params($conn, "SELECT expiration FROM premium_users WHERE chat_id = $1", array($chatId));
    
    $now = new DateTime(getCurrentTimeMexico());
    $tipoUsuario = "👤 Usuario Free"; // Por defecto es usuario normal
    
    if ($chatId == $adminId) {
        $tipoUsuario = "👑 Creador del bot";
    } elseif ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $expirationDate = new DateTime($row['expiration'], new DateTimeZone('America/Mexico_City'));

        if ($expirationDate > $now) {
            $tipoUsuario = "🌟 Usuario Premium\nExpira el: " . $expirationDate->format('Y-m-d H:i:s');
        } else {
            // Si la fecha ya pasó, eliminamos al usuario de premium
            pg_query_params($conn, "DELETE FROM premium_users WHERE chat_id = $1", array($chatId));
        }
    }

    sendMessage($chatId, "🔹 Tu estado actual:\n$tipoUsuario");
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

    // Obtener el username del usuario
    $username = $update['message']['from']['username'] ?? 'Desconocido';

    // Agregar al usuario a la tabla de usuarios premium con fecha de expiración
    pg_query_params($conn, "INSERT INTO premium_users (chat_id, username, expiration) 
                            VALUES ($1, $2, $3) 
                            ON CONFLICT (chat_id) 
                            DO UPDATE SET expiration = $3", 
                    array($chatId, $username, $expirationDate));

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
