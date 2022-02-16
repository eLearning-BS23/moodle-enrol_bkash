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
    // bKash Merchant API Information

    private $appkey;
    private $appsecret;
    private $username;
    private $password;
    private $paymentmode;
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
        global $SESSION;
        $post_token = array(
            'app_key' => $this->appkey,
            'app_secret' => $this->appsecret
        );

        $url = curl_init("$this->baseurl/checkout/token/grant");
        $post_token = json_encode($post_token);
        $header = array(
            'Content-Type:application/json',
            "password:$this->password",
            "username:$this->username"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $post_token);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $result_data = curl_exec($url);
        curl_close($url);

        $response = json_decode($result_data, true);

        if (array_key_exists('msg', $response)) {
            return json_encode($response);
        }
        $SESSION->idtoken = $response['id_token'];
        return json_encode($response);
    }

    public function createPayment() {
        global $SESSION,$DB;
        $courseid= required_param('courseid', PARAM_INT);

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
        $request_data_json = json_encode($_POST);
        $header = array(
            'Content-Type:application/json',
            "authorization: $SESSION->idtoken",
            "x-app-key: $this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result_data = curl_exec($url);
        curl_close($url);

        return $result_data;
    }

    public function executePayment() {
        global $SESSION;

        $paymentID = $_POST['paymentID'];

        $url = curl_init("$this->baseurl/checkout/payment/execute/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$SESSION->idtoken",
            "x-app-key:$this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $result_data = curl_exec($url);
        curl_close($url);

        return $result_data;
    }

    public function queryPayment() {
        require('../../../config.php');
        global $SESSION;

        $paymentID = $_GET['paymentID'];

        $url = curl_init("$this->baseurl/checkout/payment/query/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$SESSION->idtoken",
            "x-app-key:$this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $result_data = curl_exec($url);
        curl_close($url);

        return $result_data;
    }

    public function searchTransaction($trxID) {
        require('../../../config.php');
        global $SESSION;
        $url = curl_init("$this->baseurl/checkout/payment/search/" . $trxID);

        $header = array(
            'Content-Type:application/json',
            'authorization:' . $SESSION->idtoken,
            "x-app-key: $this->appkey"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $result_data = curl_exec($url);
        curl_close($url);

        return $result_data;
    }

    public static function paymentSuccess() {
        require('../../../config.php');
        require('../../../lib/setup.php');
        require_once("../lib.php");
        global $DB, $CFG;
        require_once($CFG->libdir.'/enrollib.php');
        require_once($CFG->libdir . '/filelib.php');

        global $DB;

        $plugin = enrol_get_plugin('stripepayment');

        $data = new stdClass();
        $data->userid = (int)$_POST['arr']['userid'];
        $data->courseid = $_POST['arr']['courseid'];
        $data->payment_status = $_POST['arr']['payment_status'];
        $data->txn_id = $_POST['arr']['txn_id'];
        $data->item_name = $_POST['arr']['item_name'];
        $data->instanceid = $_POST['arr']['instanceid'];




        $DB->insert_record("enrol_bkash", $data);
        $output = 'success';
        return $output;
    }



}
