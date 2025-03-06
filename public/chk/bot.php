<?php

function handleCmdCommand($chatId, $token) {
    $response = "─ Checker Commands ─\n\n"
        . "➣ Checker ✔\n"
        . "⁕ Uso: /chk cc|mm|yy|cvv\n"
        . "➣ Check Info ✔\n"
        . "⁕ Uso: /info\n"
        . "➣ Check BIN Info ✔\n"
        . "⁕ Uso: /bin xxxxxx\n"
        . "➣ Contacto ➤ @D4rkGh0st3\n";

    sendMessage($chatId, $response, $token);
}

?>
