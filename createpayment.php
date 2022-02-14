<?php

require('../../config.php');
require('./lib.php');

$config = get_config('enrol_bkash');
$createurl  = "https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/create";
$amount = required_param('amount', PARAM_FLOAT);
$token = required_param('token', PARAM_TEXT);

$invoice = uniqid(); // must be unique
$intent = "sale";

// $proxy = $array["proxy"];
$createpaybody = [
    'amount' => $amount,
    'currency' => 'BDT',
    'merchantInvoiceNumber' => $invoice,
    'intent' => $intent
];
$url = curl_init($createurl);

$createpaybodyx = json_encode($createpaybody);

$header = [
    'Content-Type:application/json',
    'authorization:' . $token,
    'x-app-key:' . $config->appkey
];

curl_setopt($url, CURLOPT_HTTPHEADER, $header);
curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
curl_setopt($url, CURLOPT_POSTFIELDS, $createpaybodyx);
curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($url, CURLOPT_PROXY, $proxy);


$resultdata = curl_exec($url);
echo $resultdata;

curl_close($url);
