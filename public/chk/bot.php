<?php

function handleCommands($chat_id, $message, $message_id, $admin) {
    if (strpos($message, ".start") === 0 || strpos($message, "!start") === 0 || strpos($message, "/start") === 0) {
        $respuesta = "─ Checker Panel ─\n\n⁕ Registered as ➞ ".$admin."\n⁕ Use ➞ /cmds to show available commands.\n⁕ Bot by: $admin\n";
        sendMessage($chat_id, $respuesta, $message_id);
    }

    elseif (strpos($message, "!cmds") === 0 || strpos($message, "/cmds") === 0 || strpos($message, ".cmds") === 0) {
        $respuesta = "ᴄʜᴇᴄᴋᴇʀ ᴄᴏᴍᴍᴀɴᴅs\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n
        ➩ Check User Info ✔\n⁕ Usage: /me\n➩ Check ID chat ✔\n⁕ Usage: /id\n➩ List Command Gates ✔\n⁕ Usage: /gts\n\n
        ⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
        sendMessage($chat_id, $respuesta, $message_id);
    }

    elseif (strpos($message, "!gts") === 0 || strpos($message, "/gts") === 0 || strpos($message, ".gts") === 0) {
        $respuesta = "━━━━•⟮ 𝗖𝗼𝗺𝗺𝗮𝗻𝗱𝘀 𝗚𝗮𝘁𝗲𝘀 ⟯•━━━━\n\n
        ➩ Gates Chargeds ✔\n⁕ Usage: /chds\n➩ Gates Auth ✔\n⁕ Usage: /ats\n➩ Gates PayPal ✔\n⁕ Usage: /pys\n\n
        ⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇ𝘇</a>\n";
        sendMessage($chat_id, $respuesta, $message_id);
    }

    
    elseif((strpos($message, "!chds") === 0)||(strpos($message, "/chds") === 0)||(strpos($message, ".chds") === 0)) {
        $respuesta = "𝘼𝙡𝙮𝙖 𝙎𝙖𝙣 ➟ Gates Chargeds\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n🔥 Braintree Charged ($50) ✔\n➣ Command ➟ /stp\n⁕ Status: ON!✅\n\n🔥 Braintree Charged ($5) ✔\n➣ Command ➟ /go\n⁕ Status: ON!✅\n\n🔥 Charged (€1) ✔\n➣ Command ➟ /cb\n⁕ Status: ON!✅\n\n🔥 Charged ($5) ✔\n➣ Command ➟ /en\n⁕ Status: ON!✅\n\n🔥 Charged ($5) ✔\n➣ Command ➟ /br\n⁕ Status: ON!✅\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
        sendMessage($chat_id,$respuesta,$message_id);
    }

    elseif((strpos($message, "!ats") === 0)||(strpos($message, "/ats") === 0)||(strpos($message, ".ats") === 0)) {
        //$respuesta = "\n◤━━━━━ ☆ 𝙶𝙰𝚃𝙴𝚂 𝙰𝚄𝚃𝙷 ☆ ━━━━━◥\n\n🔥 Stripe Auth 3DS ✔\n➣ Checker ➟ !he\n⁕ Usage: !he ccs|month|year|cvv\n\n🔥 Stripe Auth ✔\n➣ Checker ➟ !ho\n⁕ Usage: !ho ccs|month|year|cvv\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
        $respuesta = "𝘼𝙡𝙮𝙖 𝙎𝙖𝙣 ➟ Gates Auth\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n🔥 Braintree Auth ✔\n➣ Command ➟ /bt\n⁕ Status: ON!✅\n\n🔥 Braintree Auth (Wa)✔\n➣ Command ➟ /tr\n⁕ Status: ON!✅\n\n🔥 Stripe 3D ✔\n➣ Command ➟ /ta\n⁕ Status: ON!✅\n\n🔥 Woo Stripe ✔\n➣ Command ➟ /wo\n⁕ Status: ON!✅\n\n🔥 Braintree_CCN ✔\n➣ Command ➟ /ho\n⁕ Status: ON!✅\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
        sendMessage($chat_id,$respuesta,$message_id);
    }


    elseif((strpos($message, "!pys") === 0)||(strpos($message, "/pys") === 0)||(strpos($message, ".pys") === 0)) {
        //$respuesta = "\n◤━━━━ ☆ 𝙶𝙰𝚃𝙴𝚂 𝙿𝚊𝚢𝙿𝚊𝚕 ☆ ━━━━◥\n\n🔥 Paypal ✔\n➣ Checker ➟ !pp\n⁕ Usage: !pp ccs|month|year|cvv\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
        $respuesta = "𝘼𝙡𝙮𝙖 𝙎𝙖𝙣 ➟ Gates PayPal\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n🔥 Paypal ✔\n➣ Command ➟ /pp\n⁕ Status: OFF!❌\n\n⟐ Contact ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n⟐ Bot by ➜ <a href='t.me/D4rkGh0st3'>ʀɪɢᴏ ᴊɪᴍᴇɴᴇᴢ</a>\n";
        sendMessage($chat_id,$respuesta,$message_id);
    }

    elseif((strpos($message, "!me") === 0)||(strpos($message, "/me") === 0)||(strpos($message, ".me") === 0)) {
	$respuesta = "[ ↯ ] ᴍʏ ᴀʙᴏᴜᴛ [ ↯ ]\n\n‣ ᴜsᴇʀ ɪᴅ:".$id."\n‣ ғᴜʟʟ ɴᴀᴍᴇ: ".$Name." ".$last."\n‣ ᴜsᴇʀɴᴀᴍᴇ: @".$user."\n‣ ᴜsᴇʀ ᴛʏᴘᴇ: ".$tipo."\n";
//$respuesta = "⁕ ─ 𝑈𝑆𝐸𝑅 𝐼𝑁𝐹𝑂 ─ ⁕\n➩ 𝚄𝚂𝙴𝚁 𝙸𝙳: <code>".$id."</code>\n➩ 𝙵𝚄𝙻𝙻 𝙽𝙰𝙼𝙴: ".$Name." ".$last."\n➩ 𝚄𝚂𝙴𝚁𝙽𝙰𝙼𝙴: @".$user."\n➩ 𝚄𝚂𝙴𝚁 𝚃𝚈𝙿𝙴: ".$tipo."\n";
	$respuesta = "     [ ↯ ] ᴍʏ ᴀʙᴏᴜᴛ [ ↯ ]\n\n‣ ᴜsᴇʀ ɪᴅ: <code>".$id."</code>\n‣ ғᴜʟʟ ɴᴀᴍᴇ: ".$Name." ".$last."\n‣ ᴜsᴇʀɴᴀᴍᴇ: @".$user."\n‣ ᴜsᴇʀ ᴛʏᴘᴇ: ".$tipo."\n";
	sendMessage($chat_id,$respuesta,$message_id);
    }
}
?>
