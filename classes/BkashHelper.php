<?php

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
        global $SESSION;

        if ((string)$_POST['amount'] != (string)$SESSION->finalamount) {
            return json_encode([
                'errorMessage' => 'Amount Mismatch',
                'errorCode' => 2006
            ]);
        }

        $token = $_POST["token"];

        $_POST['intent'] = 'sale';
        $_POST['currency'] = 'BDT';
        $_POST['merchantInvoiceNumber'] = uniqid();

        $url = curl_init("$this->baseurl/checkout/payment/create");
        $request_data_json = json_encode($_POST);
        $header = array(
            'Content-Type:application/json',
            "authorization: $token",
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
        $token = $_POST['token'];

        $url = curl_init("$this->baseurl/checkout/payment/execute/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$token",
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

        global $DB;

        $data = new stdClass();
        $data->userid = $_POST['arr']['userid'];
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
