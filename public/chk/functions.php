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


function array_in_string($str, array $arr) {
    foreach($arr as $arr_value) {
        if (stripos($str,$arr_value) !== false)
    return true;
    }
    return false;
}

function Calculate($ccnumber, $length)
    {
        $sum = 0;
        $pos = 0;
        $reversedCCnumber = strrev($ccnumber);

        while ($pos < $length - 1) {
            $odd = $reversedCCnumber[$pos] * 2;
            if ($odd > 9) {
                $odd -= 9;
            }
            $sum += $odd;

            if ($pos != ($length - 2)) {

                $sum += $reversedCCnumber[$pos + 1];
            }
            $pos += 2;
        }

     //   # Calculate check digit
        $checkdigit = ((floor($sum / 10) + 1) * 10 - $sum) % 10;
        $ccnumber .= $checkdigit;
        return $ccnumber;
    }


///Verifica las repeticiones de una cc///
$archivo_contadores = "contadores.txt";
function handleComando($dato) {
  global $archivo_contadores;
  if (file_exists($archivo_contadores)) {
    $contadores = @unserialize(file_get_contents($archivo_contadores));
    if ($contadores === false) {
      $contadores = array();
    }
  } else {
    $contadores = array();
  }

  if (isset($contadores[$dato])) {
    $contadores[$dato]++;
  } else {
    $contadores[$dato] = 1;
  }

  if (@file_put_contents($archivo_contadores, serialize($contadores)) === false) {
    return "Error!";
  }
  return $contadores[$dato];
}

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


function editMessage($chatId, $respuesta, $id_text){
$url = $GLOBALS["website"]."/editMessageText?disable_web_page_preview=true&chat_id=".$chatID."&message_id=".$id_text."&parse_mode=HTML&text=".urlencode($respuesta);
file_get_contents($url);
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
