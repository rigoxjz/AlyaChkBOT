<?php

function handleCommands($chatId, $message) {


if((strpos($message, "!") === 0)||(strpos($message, "/") === 0)||(strpos($message, ".") === 0)){

$timeout = 60; // Tiempo de espera en segundos
$maxMessages = 3; // Máximo de mensajes permitidos
$file = 'users.txt';
$userId = $id; // Reemplaza con la lógica para obtener el ID del usuario actual

try {
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
    } else {
        $data = array();
    }

    if (!isset($data[$userId])) {
        $data[$userId] = array('lastSend' => 0, 'count' => 0);
    }

    $lastSend = $data[$userId]['lastSend'];
    $count = $data[$userId]['count'];
    $diff = time() - $lastSend;

    if ($diff >= $timeout) {
        $count = 0; // Resetear contador después del timeout
    }

    if ($count >= $maxMessages) {
//        $respuesta = '⏳Por favor, espera ' . ($timeout - $diff) . ' segundos antes de enviar otro mensaje.';
	$respuesta = '[ANTI SPAM] Please try again after ' . ($timeout - $diff) . ' seconds.';
        sendMessage($chatId, $respuesta, $message_id);
 //       echo "$respuesta\n";
        exit;
    }

    // Envía el mensaje...
    $count++;
    $data[$userId] = array('lastSend' => time(), 'count' => $count);
    file_put_contents($file, json_encode($data));
//    echo "Mensaje enviado con éxito.\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}
}


if (preg_match('/^(!|\/|\.)cmds$/', $message)) {
    $respuesta = "🔹 <b>CHECKER COMMANDS</b> 🔹\n━━━━━━━━━━━━━━━━━━━━━\n"
               . "➩ <b>Check User Info</b> ✔\n   └ 💠 /me\n"
               . "➩ <b>Check ID chat</b> ✔\n   └ 💠 /id\n"
               . "➩ <b>List Command Gates</b> ✔\n   └ 💠 /gts\n\n"
               . "🌟 <b>EXTRAS</b> 🌟\n━━━━━━━━━━━━━━━━━━━━━\n"
               . "⌦ <b>Bin Check</b> ✔\n   └ 💠 /bin xxxxxx\n"
               . "⌦ <b>Checker IBAN</b> ✔\n   └ 💠 /iban xxxxxx\n"
               . "⌦ <b>SK Key Check</b> ✔\n   └ 💠 /sk sk_live_xxxx\n"
               . "⌦ <b>Gen ccs</b> ✔\n   └ 💠 /gen xxxxxx\n\n"
               . "📩 <b>Contacto</b> ➜ <a href='t.me/rigo_jz'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n"
               . "🤖 <b>Bot by</b> ➜ <a href='t.me/rigo_jz'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>";  
    sendMessage($chatId, $respuesta, $update['message']['message_id'], "HTML");
}


/*
    function sendMessage($chatID, $respuesta, $message_id) {
    $url = $GLOBALS["website"]."/sendMessage?disable_web_page_preview=true&chat_id=".$chatID."&reply_to_message_id=".$message_id."&parse_mode=HTML&text=".urlencode($respuesta);
//$url = $GLOBALS["website"]."/sendMessage?disable_web_page_preview=true&chat_id=".$chatID."&parse_mode=HTML&text=".urlencode($respuesta);
    $cap_message_id = file_get_contents($url);
//------------EXTRAE EL ID DEL MENSAGE----------//
    $id_cap = capture($cap_message_id, '"message_id":', ',');
    file_put_contents("ID", $id_cap);
    }*/
}
?>
