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
require_once("lib.php");
global $DB, $USER, $CFG, $PAGE;
require_once($CFG->libdir . '/enrollib.php');

require_login();

$paymentID = required_param('id', PARAM_TEXT);
$instanceid = required_param('instanceid', PARAM_TEXT);
$courseid = required_param('courseid', PARAM_INT);
$userid = required_param('userid', PARAM_INT);

$config = get_config('enrol_bkash');

if ($config->paymentmode == 'live') {
    $baseurl = 'https://checkout.pay.bka.sh/v1.2.0-beta';
} else {
    $baseurl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta';
}

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);


$plugin = enrol_get_plugin('bkash');

$response = queryPayment($paymentID,$config->appkey,$baseurl);
if ($response) {
    $response = json_decode($response);

    $data = new stdClass();
    $data->item_name = $course->fullname;
    $data->userid = (int)$userid;
    $data->courseid = $courseid;
    $data->instanceid = $instanceid;
    $data->cost = $response->amount;
    $data->payment_id = $response->paymentID;
    $data->currency = $response->currency;
    $data->customer_msisdn = $response->customerMsisdn;
    $data->payment_status = $response->transactionStatus;
    $data->merchant_invoice_number = $response->merchantInvoiceNumber;
    $data->txn_id = $response->trxID;


    $DB->insert_record("enrol_bkash", $data);

    $plugininstance = $DB->get_record("enrol", array("id" => $instanceid, "status" => 0));

    $DB->insert_record("enrol_bkash", $data);
    // Enrol user.
    $plugin->enrol_user($plugininstance, $userid, $plugininstance->roleid, $timestart=0, $timeend=0);

    return redirect($CFG->wwwroot . '/course/view.php?id=' . $courseid,"Successfully enrolled in the course",'success');
}

function queryPayment($paymentID, $appkey,$baseurl) {

    global $SESSION;

    $url = curl_init("$baseurl/checkout/payment/query/" . $paymentID);

    $header = array(
        'Content-Type:application/json',
        "authorization:$SESSION->idtoken",
        "x-app-key:$appkey"
    );
    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    $result_data = curl_exec($url);
    curl_close($url);
    return $result_data;
}
