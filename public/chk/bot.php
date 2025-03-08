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
	return '[ANTI SPAM] Please try again after ' . ($timeout - $diff) . ' seconds.';
        //sendMessage($chatId, $respuesta, $message_id);
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
   return "🔹 <b>CHECKER COMMANDS</b> 🔹\n━━━━━━━━━━━━━━━━━━━━━\n"
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
	
    //sendMessage($chatId, $respuesta, $update['message']['message_id'], "HTML");
}

if (preg_match('/^(!|\/|\.)gts$/', $message)) {
       return "🚀 <b>Command Gates</b> 🚀\n\n"
               . "💠 <b>Gates Chargeds</b> ✔\n"
               . "└ 💎 <code>/chds</code>\n\n"
               . "💠 <b>Gates Auth</b> ✔\n"
               . "└ 🔐 <code>/ats</code>\n\n"
               . "💠 <b>Gates PayPal</b> ✔\n"
               . "└ 💳 <code>/pys</code>\n\n"
               . "📩 <b>Contacto:</b> <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n"
               . "🤖 <b>Bot by:</b> <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>";

    //sendMessage($chat_id, $respuesta, $message_id, "HTML");
}


if (preg_match('/^(!|\/|\.)chds$/', $message)) {
    return "💠 <b>Gates Chargeds</b> 💠\n"
               . "--------------------------------------\n"
               . "🔥 <b>Braintree Charged</b> ($50) ✔\n"
               . "└ 💻 <code>/stp</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Braintree Charged</b> ($5) ✔\n"
               . "└ 💻 <code>/go</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Charged</b> (€1) ✔\n"
               . "└ 💻 <code>/cb</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Charged</b> ($5) ✔\n"
               . "└ 💻 <code>/en</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Charged</b> ($5) ✔\n"
               . "└ 💻 <code>/br</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "📩 <b>Contacto:</b> <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n"
               . "🤖 <b>Bot by:</b> <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>";

 //   sendMessage($chat_id, $respuesta, $message_id, "HTML");
}

if (preg_match('/^(!|\/|\.)ats$/', $message)) {
     return "💠 <b>Gates Auth</b> 💠\n"
               . "--------------------------------------\n"
               . "🔥 <b>Braintree Auth</b> ✔\n"
               . "└ 💻 <code>/bt</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Braintree Auth (Wa)</b> ✔\n"
               . "└ 💻 <code>/tr</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Stripe 3D</b> ✔\n"
               . "└ 💻 <code>/ta</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Woo Stripe</b> ✔\n"
               . "└ 💻 <code>/wo</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "🔥 <b>Braintree CCN</b> ✔\n"
               . "└ 💻 <code>/ho</code>\n"
               . "⁕ <i>Status:</i> ON!✅\n\n"
               . "📩 <b>Contacto:</b> <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n"
               . "🤖 <b>Bot by:</b> <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>";

   // sendMessage($chat_id, $respuesta, $message_id, "HTML");
}


if (preg_match('/^(!|\/|\.)me$/', $message)) {
    return "     [ ↯ ] ᴍʏ ᴀʙᴏᴜᴛ [ ↯ ]\n\n"
           . "‣ ᴜsᴇʀ ɪᴅ: <code>" . $id . "</code>\n"
           . "‣ ғᴜʟʟ ɴᴀᴍᴇ: " . $Name . " " . $last . "\n"
           . "‣ ᴜsᴇʀɴᴀᴍᴇ: @" . $user . "\n"
           . "‣ ᴜsᴇʀ ᴛʏᴘᴇ: " . $tipo . "\n";
}


if (preg_match('/^(!|\/|\.)gen/', $message)) {
    $input = substr($message, 5);
    
    // Verificar si se proporcionó un BIN
    if (empty($input)) {
        $respuesta = "🚫 Oops!\n<b>Use this format:</b> <code>/gen xxxxxx</code>";
        sendMessage($chat_id, $respuesta, $message_id, "HTML");
        die();
    }

    // Mensaje de espera
    sendMessage($chatId, "<b>🕒 Wait for Result...</b>", $message_id, "HTML");

    // Extraer datos de la tarjeta
    $lista = substr($message, 5);
    $binData = explode("|", $lista);
    $bin = $binData[0] ?? 'xxxxxx';
    $mes1 = $binData[1] ?? 'rnd';
    $ano1 = $binData[2] ?? 'rnd';
    $cvv1 = $binData[3] ?? 'rnd';

    // Ajustar formato del año si es de dos dígitos
    if (strlen($ano1) == 2) {
        $ano1 = '20' . $ano1;
    }

    // Definir cantidad de dígitos según tipo de tarjeta
    $isAmex = in_array(substr($bin, 0, 2), ["34", "37"]);
    $cant = $isAmex ? 15 : 16;
    $bin = substr($bin . str_repeat("x", $cant), 0, $cant - 1);
    
    $Bin = substr($bin, 0, 6);
    $amount = 10;
    $ccList = "";

    for ($i = 0; $i < $amount; $i++) {
        // Generar mes, año y CVV aleatorios
        $randMonth = str_pad(rand(1, 12), 2, "0", STR_PAD_LEFT);
        $randYear = "20" . rand(25, 30);
        $randCvv = $isAmex ? rand(1000, 9999) : rand(100, 999);

        $mes = is_numeric($mes1) ? $mes1 : $randMonth;
        $ano = is_numeric($ano1) ? $ano1 : $randYear;
        $cvv = is_numeric($cvv1) ? $cvv1 : $randCvv;

        // Generar número de tarjeta
        $ccNumber = $bin;
        while (strlen($ccNumber) < $cant) {
            $ccNumber .= rand(0, 9);
        }

        $ccNumber = str_split($ccNumber);
        $generatedCard = "";
        foreach ($ccNumber as $char) {
            $generatedCard .= str_replace("x", rand(0, 9), $char);
        }

        // Luhn Algorithm para validar la tarjeta
        $ccs = Calculate($generatedCard, $cant);
        $ccList .= "<code>{$ccs}|{$mes}|{$ano}|{$cvv}</code>\n";
    }

    // Obtener información del BIN
    $binInfo = Bin_Gen_Info($Bin);

    // Respuesta final
    $respuesta = "➭ <b>BIN:</b> <code>$Bin</code>\n"
               . "➭ <b>AMOUNT:</b> 10\n\n"
               . "$ccList\n"
               . "$binInfo";

    sendMessage($chatId, $respuesta, $message_id, "HTML");
    die();
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

	 return null; // Si el mensaje no es un comando válido, devuelve null
}
?>
