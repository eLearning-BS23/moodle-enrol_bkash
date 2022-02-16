<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * bkash enrolment plugin version specification.
 *
 * @package    enrol_bkash
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require('./classes/BkashHelper.php');

require_login();
$config = get_config('enrol_bkash');

$bkash_helper = new BkashHelper(
    $config->appkey,
    $config->appsecretkey,
    $config->username,
    $config->password,
    $config->paymentmode
);

$amount = required_param('amount', PARAM_FLOAT);
$custom = required_param('custom', PARAM_TEXT);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>bKash Payment Gateway</title>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
    </script>
    <?php
    if ($config->paymentmode == 'live') {
        echo '<script id="myScript"
        src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>';
    } else {
        echo '<script id="myScript"
        src="https://scripts.sandbox.bka.sh/versions/1.1.0-beta/checkout/bKash-checkout-sandbox.js"
        ></script>';
    }

    ?>
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

    global $SESSION;
    $SESSION->finalamount = $amount;
    include './classes/bkash-script.php';
    ?>

</body>

</html>