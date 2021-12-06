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
 * bkash enrolment plugin - support for user self unenrolment.
 *
 * @package    enrol_bkash
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require("../../config.php");

require_login();
global $CFG, $USER;
/* PHP */


?>
<!---->
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>bKash PGW Demo</title>-->
<!--    <link rel="stylesheet" href="style.css">-->
<!---->
<!--</head>-->
<!--<body>-->
<!--<button id="bKash_button" class="btn btn-danger">Pay With bKash</button>-->
<!--</body>-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>
<script type="text/javascript">
    let paymentID;

    let username = "sandboxTestUser";
    let password = "hWD@8vtzw0";
    let app_key = "5tunt4masn6pv2hnvte1sb5n3j";
    let app_secret = "1vggbqd4hqk9g96o9rrrp2jftvek578v7d2bnerim12a87dbrrka";

    let grantTokenUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/token/grant';
    let createCheckoutUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/create';
    let executeCheckoutUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/execute';

    $(document).ready(function () {
        getAuthToken();
    });

    function getAuthToken() {
        let body = {
            "app_key": app_key,
            "app_secret": app_secret
        };

        $.ajax({
            url: grantTokenUrl,   // Sandbox checkout
            headers: {
                "username": username,
                "password": password,
                "Content-Type": "application/json"
            },
            type: 'POST',
            data: JSON.stringify(body),   //JS obj to string covert
            success: function (result) {

                let headers = {
                    "Content-Type": "application/json",
                    "Authorization": result.id_token, // Contains access token
                    "X-APP-Key": app_key
                };

                let request = {             //create payment API
                    "amount": "85.50",
                    "intent": "sale",
                    "currency": "BDT",
                    "merchantInvoiceNumber": "123456"
                };

                initBkash(headers, request);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function initBkash(headers, request) {
        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: request,

            createRequest: function (request) {
                $.ajax({
                    url: createCheckoutUrl,
                    headers: headers,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(request),
                    success: function (data) {

                        if (data && data.paymentID != null) {
                            paymentID = data.paymentID;
                            bKash.create().onSuccess(data);
                        }
                        else {
                            bKash.create().onError(); // Run clean up code
                            alert(data.errorMessage + " Tag should be 2 digit, Length should be 2 digit, Value should be number of character mention in Length, ex. MI041234 , supported tags are MI, MW, RF");
                        }

                    },
                    error: function () {
                        bKash.create().onError(); // Run clean up code
                        alert(data.errorMessage);
                    }
                });
            },
            executeRequestOnAuthorization: function () {
                $.ajax({
                    url: executeCheckoutUrl + '/' + paymentID,
                    headers: headers,
                    type: 'POST',
                    contentType: 'application/json',
                    success: function (data) {

                        if (data && data.paymentID != null) {
                            // On success, perform your desired action
                            alert('[SUCCESS] data : ' + JSON.stringify(data));
                            window.location.href = "/success_page.html";

                        } else {
                            alert('[ERROR] data : ' + JSON.stringify(data));
                            bKash.execute().onError();//run clean up code
                        }

                    },
                    error: function () {
                        alert('An alert has occurred during execute');
                        bKash.execute().onError(); // Run clean up code
                    }
                });
            },
            onClose: function () {
                alert('User has clicked the close button');
            }
        });

        // $('#bKash_button').removeAttr('disabled');

    }
</script>
<!--</html>-->