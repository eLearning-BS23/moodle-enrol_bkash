<?php

require('../../../config.php');



include('BkashHelper.php');

$config = get_config('enrol_bkash');

$bkash_helper = new BkashHelper(
    $config->appkey,
    $config->appsecretkey,
    $config->username,
    $config->password,
    $config->paymentmode
);


$action = $_GET['action'];

echo $bkash_helper->$action();
