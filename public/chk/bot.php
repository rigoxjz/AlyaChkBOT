<?php

function handleCommands($chatId, $message) {

   // Cmds Commands
//if((strpos($message, "!cmds") === 0)||(strpos($message, "/cmds") === 0)||(strpos($message, ".cmds") === 0)) {
 //       $respuesta = "ᴄʜᴇᴄᴋᴇʀ ᴄᴏᴍᴍᴀɴᴅs\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n➩ Check User Info ✔\n⁕ Usage: /me\n➩ Check ID chat ✔\n⁕ Usage: /id\n➩ List Command Gates ✔\n⁕ Usage: /gts\n\n☆. 𝙴𝚇𝚃𝚁𝙰𝚂 .☆\n- - - - - - - - - -- - - - - - - - - -\n⌦ Bin Check ✔\n⁕ Usage ➟ /bin xxxxxx\n⌦ Checker IBAN ✔\n⁕ Usage ➟ /iban xxxxxx\n⌦ SK Key Check ✔\n⁕ Usage ➟ /sk sk_live_xxxx\n⌦ Gen ccs ✔\n⁕ Usage ➟ /gen xxxxxx\n\n☆. 𝙴𝚇𝚃𝚁𝙰𝙿𝙾𝙻𝙰𝙲𝙸𝙾𝙽 .☆\n- - - - - - - - - -- - - - - - - - - -\n° ᭄ Basica ✔\n⁕ Usage ➟ /extb ᴄᴄs\n° ᭄ Indentacion ✔\n⁕ Usage ➟ /extb ᴄᴄs\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
   //     sendMessage($chatId, $respuesta);
//}
if (preg_match('/^(!|\/|\.)cmds$/', $message)) {
    $respuesta = "🔹 <b>CHECKER COMMANDS</b> 🔹\n━━━━━━━━━━━━━━━━━━━━━\n"
               . "➩ <b>Check User Info</b> ✔\n   └ 💠 <code>/me</code>\n"
               . "➩ <b>Check ID chat</b> ✔\n   └ 💠 <code>/id</code>\n"
               . "➩ <b>List Command Gates</b> ✔\n   └ 💠 <code>/gts</code>\n\n"
               . "🌟 <b>EXTRAS</b> 🌟\n━━━━━━━━━━━━━━━━━━━━━\n"
               . "⌦ <b>Bin Check</b> ✔\n   └ 💠 <code>/bin xxxxxx</code>\n"
               . "⌦ <b>Checker IBAN</b> ✔\n   └ 💠 <code>/iban xxxxxx</code>\n"
               . "⌦ <b>SK Key Check</b> ✔\n   └ 💠 <code>/sk sk_live_xxxx</code>\n"
               . "⌦ <b>Gen ccs</b> ✔\n   └ 💠 <code>/gen xxxxxx</code>\n\n"
               . "📩 <b>Contacto</b> ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n"
               . "🤖 <b>Bot by</b> ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>";  
    sendMessage($chatId, $response, $update['message']['message_id'], "HTML");
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
