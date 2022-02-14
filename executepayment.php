<?php
require('../../config.php');
require('./lib.php');

$config = get_config('enrol_bkash');
$createurl  = "https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/create";
$paymentID = required_param('paymentID', PARAM_FLOAT);
$token = required_param('token', PARAM_TEXT);

$executeurl = "https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/execute/";

$url = curl_init($executeurl.$paymentID);

$header=array(
    'Content-Type:application/json',
    'authorization:'.$token,
    'x-app-key:'.$config->appkey
);

curl_setopt($url,CURLOPT_HTTPHEADER, $header);
curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($url, CURLOPT_PROXY, $proxy);

$resultdatax=curl_exec($url);
curl_close($url);
echo $resultdatax;
?>
