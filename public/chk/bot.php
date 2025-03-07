<?php

function handleCommands($chat_id, $message, $message_id, $admin, $update) {
    global $token;
    
    // Verificar si existe "message" en el update
    if (!isset($update["message"])) return;
    
    // Obtener información del usuario
    $id = $update["message"]["from"]["id"] ?? "Unknown";
    $Name = $update["message"]["from"]["first_name"] ?? "Unknown";
    $last = $update["message"]["from"]["last_name"] ?? "";
    $user = $update["message"]["from"]["username"] ?? null;
    $tipo = ($chat_id > 0) ? "Private" : "Group";
    
   // Cmds Commands
if((strpos($message, "!cmds") === 0)||(strpos($message, "/cmds") === 0)||(strpos($message, ".cmds") === 0)) {
        $respuesta = "ᴄʜᴇᴄᴋᴇʀ ᴄᴏᴍᴍᴀɴᴅs\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n➩ Check User Info ✔\n⁕ Usage: /me\n➩ Check ID chat ✔\n⁕ Usage: /id\n➩ List Command Gates ✔\n⁕ Usage: /gts\n\n☆. 𝙴𝚇𝚃𝚁𝙰𝚂 .☆\n- - - - - - - - - -- - - - - - - - - -\n⌦ Bin Check ✔\n⁕ Usage ➟ /bin xxxxxx\n⌦ Checker IBAN ✔\n⁕ Usage ➟ /iban xxxxxx\n⌦ SK Key Check ✔\n⁕ Usage ➟ /sk sk_live_xxxx\n⌦ Gen ccs ✔\n⁕ Usage ➟ /gen xxxxxx\n\n☆. 𝙴𝚇𝚃𝚁𝙰𝙿𝙾𝙻𝙰𝙲𝙸𝙾𝙽 .☆\n- - - - - - - - - -- - - - - - - - - -\n° ᭄ Basica ✔\n⁕ Usage ➟ /extb ᴄᴄs\n° ᭄ Indentacion ✔\n⁕ Usage ➟ /extb ᴄᴄs\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
        sendMessage($chat_id,$respuesta,$message_id);
}


// Verificar si la función ya existe antes de declararla
if (!function_exists('sendMessage')) {
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
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context  = stream_context_create($options);
        file_get_contents($url, false, $context);
    }
}

?>
