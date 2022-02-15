<?php

require('../../config.php');
require('./classes/BkashHelper.php');


$bkash_helper = new BkashHelper();

$amount = required_param('amount', PARAM_FLOAT);
$custom = required_param('custom', PARAM_TEXT);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>bKash Payment Gateway</title>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous">
    </script>
    <!-- <script id="myScript"
            src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script> -->
    <script id="myScript"
            src="https://scripts.sandbox.bka.sh/versions/1.1.0-beta/checkout/bKash-checkout-sandbox.js"></script>

    <style>
        .hidden {
            display: none !important;
        }
        #full_page_loading {
            background: url('pix/page-loader.gif') no-repeat scroll center center #fff;
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 9999;
            opacity: 0.5;
            top: 0;
        }
    </style>

</head>
<body>
<div id="full_page_loading" class="hidden"></div>

<?php

// IF CONDITION SUCCESS THEN YOU CAN SHOW bKash Payment Button Here
// GET Amount from your request
$amount = $amount;
global $SESSION;
$SESSION->finalamount = $amount;
include './classes/bkash-script.php';
?>

</body>
</html>
