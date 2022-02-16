<?php

require('../../config.php');
global $SESSION;



$request_token=bkash_Get_Token();
$idtoken=$request_token['id_token'];
$SESSION->idtoken=$idtoken;

echo $idtoken;

function bkash_Get_Token(){

    $tokenurl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/token/grant';

    $config = get_config('enrol_bkash');

	$post_token=array(
        'app_key'=>$config->appkey,
		'app_secret'=>$config->appsecretkey
	);

    $url=curl_init($tokenurl);

	$posttoken=json_encode($post_token);
	$header=array(
		'Content-Type:application/json',
		'password:'.$config->password,
        'username:'.$config->username
    );

    curl_setopt($url,CURLOPT_HTTPHEADER, $header);
	curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($url,CURLOPT_POSTFIELDS, $posttoken);
	curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($url, CURLOPT_PROXY, $proxy);
	$resultdata=curl_exec($url);
	curl_close($url);
	return json_decode($resultdata, true);
}
?>
