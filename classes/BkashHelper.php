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



class BkashHelper {
    // BKash Merchant API Information.

    /**
     * @var string API Key.
     */
    private $appkey;

    /**
     * @var string API Secret.
     */
    private $appsecret;

    /**
     * @var string User name.
     */
    private $username;

    /**
     * @var string Password.
     */
    private $password;

    /**
     * @var string Payment mode (live or test).
     */
    private $paymentmode;
    /**
     * @var string Payment mode (live or test).
     */
    private $baseurl;


    public function __construct(string $appkey, string $appsecret, string $username, string $password, string $paymentmode) {
        $this->appkey = $appkey;
        $this->appsecret = $appsecret;
        $this->username = $username;
        $this->password = $password;
        $this->paymentmode = $paymentmode;

        if ($this->paymentmode == 'live') {
            $this->baseurl = 'https://checkout.pay.bka.sh/v1.2.0-beta';
        } else {
            $this->baseurl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta';
        }
    }

    public function getToken() {
        require('../../../config.php');
        require_login();
        global $SESSION;
        $posttoken = array(
            'app_key' => $this->appkey,
            'app_secret' => $this->appsecret
        );

        $url = curl_init("$this->baseurl/checkout/token/grant");
        $posttoken = json_encode($posttoken);
        $header = array(
            'Content-Type:application/json',
            "password:$this->password",
            "username:$this->username"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);

        $response = json_decode($resultdata, true);

        if (array_key_exists('msg', $response)) {
            return json_encode($response);
        }
        $SESSION->idtoken = $response['id_token'];
        return json_encode($response);
    }

    public function createPayment() {
        global $SESSION, $DB;
        $courseid = required_param('courseid', PARAM_INT);

        $plugininstance = $DB->get_record("enrol", array("enrol" => 'bkash', "status" => 0, 'courseid' => $courseid));
        if ((string)$plugininstance->cost != (string)$SESSION->finalamount) {
            return json_encode([
                'errorMessage' => 'Amount Mismatch',
                'errorCode' => 2006
            ]);
        }

        $_POST['intent'] = 'sale';
        $_POST['currency'] = $plugininstance->currency;
        $_POST['merchantInvoiceNumber'] = uniqid();

        $url = curl_init("$this->baseurl/checkout/payment/create");
        $requestdatajson = json_encode($_POST);
        $header = array(
            'Content-Type:application/json',
            "authorization: $SESSION->idtoken",
            "x-app-key: $this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestdatajson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);

        return $resultdata;
    }

    public function executePayment() {
        global $SESSION;

        $paymentid = $_POST['paymentID'];

        $url = curl_init("$this->baseurl/checkout/payment/execute/" . $paymentid);
        $header = array(
            'Content-Type:application/json',
            "authorization:$SESSION->idtoken",
            "x-app-key:$this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);

        return $resultdata;
    }

    public function queryPayment() {
        require('../../../config.php');
        require_login();
        global $SESSION;

        $paymentid = $_GET['paymentID'];

        $url = curl_init("$this->baseurl/checkout/payment/query/" . $paymentid);
        $header = array(
            'Content-Type:application/json',
            "authorization:$SESSION->idtoken",
            "x-app-key:$this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);

        return $resultdata;
    }

    public function searchTransaction($trxid) {
        require('../../../config.php');
        require_login();
        global $SESSION;
        $url = curl_init("$this->baseurl/checkout/payment/search/" . $trxid);

        $header = array(
            'Content-Type:application/json',
            'authorization:' . $SESSION->idtoken,
            "x-app-key: $this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);

        return $resultdata;
    }
}
