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
    
    if (!$message) return;

    // Lista de comandos
    $commands = [
        'start' => "─ Checker Panel ─\n\n⁕ Registered as ➞ $admin\n⁕ Use ➞ /cmds to show available commands.\n⁕ Bot by: $admin\n",
        'cmds' => "ᴄʜᴇᴄᴋᴇʀ ᴄᴏᴍᴍᴀɴᴅs\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n\n➩ Check User Info ✔\n⁕ Usage: /me\n➩ Check ID chat ✔\n⁕ Usage: /id\n➩ List Command Gates ✔\n⁕ Usage: /gts\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n",
        'gts' => "━━━━•⟮ 𝗖𝗼𝗺𝗺𝗮𝗻𝗱𝘀 𝗚𝗮𝘁𝗲𝘀 ⟯•━━━━\n\n➩ Gates Chargeds ✔\n⁕ Usage: /chds\n➩ Gates Auth ✔\n⁕ Usage: /ats\n➩ Gates PayPal ✔\n⁕ Usage: /pys\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n",
        'me' => "[ ↯ ] ᴍʏ ᴀʙᴏᴜᴛ [ ↯ ]\n\n‣ ᴜsᴇʀ ɪᴅ: <code>$id</code>\n‣ ғᴜʟʟ ɴᴀᴍᴇ: $Name $last\n‣ ᴜsᴇʀɴᴀᴍᴇ: " . ($user ? "@$user" : "No Username") . "\n‣ ᴜsᴇʀ ᴛʏᴘᴇ: $tipo\n",
        'id' => "Your Chat ID is: <code>$chat_id</code>",
        'chds' => "𝘼𝙡𝙮𝙖 𝙎𝙖𝙣 ➟ Gates Chargeds\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n🔥 Braintree Charged ($50) ✔\n➣ Command ➟ /stp\n⁕ Status: ON!✅\n\n🔥 Braintree Charged ($5) ✔\n➣ Command ➟ /go\n⁕ Status: ON!✅\n",
        'ats' => "𝘼𝙡𝙮𝙖 𝙎𝙖𝙣 ➟ Gates Auth\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n🔥 Braintree Auth ✔\n➣ Command ➟ /bt\n⁕ Status: ON!✅\n",
        'pys' => "𝘼𝙡𝙮𝙖 𝙎𝙖𝙣 ➟ Gates PayPal\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n🔥 Paypal ✔\n➣ Command ➟ /pp\n⁕ Status: OFF!❌\n",
    ];
    
    foreach ($commands as $cmd => $response) {
        if (preg_match("/^[!\/.]{$cmd}$/i", $message)) {
            sendMessage($chat_id, $response, $message_id);
            return;
        }
    }
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
