<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('../../config.php');
// require('./lib.php');
global $SESSION;

$config = get_config('enrol_bkash');
$paymentID = required_param('paymentID', PARAM_TEXT);

$executeurl = "https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/execute/";

$url = curl_init($executeurl.$paymentID);

$header=array(
    'Content-Type:application/json',
    'authorization:'.$SESSION->idtoken,
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
