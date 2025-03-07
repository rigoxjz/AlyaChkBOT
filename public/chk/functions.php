<?php

// Definir la URL base del bot de Telegram
$GLOBALS["website"] = "https://api.telegram.org/bot" . $token;

/**
 * Envía un mensaje a un chat de Telegram
 *
 * @param int|string $chatID ID del chat de Telegram
 * @param string $respuesta Mensaje a enviar
 * @param int|null $message_id ID del mensaje al que responder (opcional)
 */
function sendMessage($chatID, $respuesta, $message_id = null) {
    $url = $GLOBALS["website"] . "/sendMessage?disable_web_page_preview=true&chat_id=" . $chatID . "&parse_mode=HTML&text=" . urlencode($respuesta);

    // Agregar el mensaje de respuesta si se proporciona un message_id
    if ($message_id) {
        $url .= "&reply_to_message_id=" . $message_id;
    }

    // Enviar la solicitud y capturar la respuesta
    $cap_message_id = file_get_contents($url);

    // Extraer el ID del mensaje enviado
    if ($cap_message_id) {
        $id_cap = capture($cap_message_id, '"message_id":', ',');
        file_put_contents("ID", $id_cap);
    }
}

/**
 * Extrae una cadena entre dos delimitadores
 *
 * @param string $string Texto de entrada
 * @param string $start Delimitador de inicio
 * @param string $end Delimitador de fin
 * @return string|false Devuelve la cadena capturada o false si no se encuentra
 */
function capture($string, $start, $end) {
    $str = explode($start, $string);
    if (isset($str[1])) {
        $str = explode($end, $str[1]);
        return trim($str[0]);
    }
    return false;
}

?>
