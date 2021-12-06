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

require_login();
require("../../config.php");
global $CFG, $USER;
/* PHP */

$postdata = array();
$postdata['store_id'] = get_config('enrol_bkash')->sslstoreid;
$postdata['store_passwd'] = get_config('enrol_bkash')->sslstorepassword;
$postdata['total_amount'] = $_POST['amount'];
$postdata['currency'] = $_POST['currency_code'];
$postdata['tran_id'] = "MD_COURSE_" . uniqid();
$postdata['success_url'] = $CFG->wwwroot . "/enrol/bkash/success.php?id=" . $_POST['course_id'];
$postdata['fail_url'] = $CFG->wwwroot . "/enrol/bkash/fail.php?id=" . $_POST['course_id'];
$postdata['cancel_url'] = $CFG->wwwroot . "/enrol/bkash/cancel.php?id=" . $_POST['course_id'];
//$postdata['ipn_url'] = $CFG->wwwroot . "/enrol/bkash/ipn.php?id=" . $_POST['course_id'];

$postdata['cus_name'] = $_POST['os0'];
$postdata['cus_email'] = $_POST['email'];
$postdata['cus_add1'] = $_POST['address'];
$postdata['cus_add2'] = "";
$postdata['cus_city'] = $_POST['city'];
$postdata['cus_state'] = "";
$postdata['cus_postcode'] = "1000";
$postdata['cus_country'] = $_POST['country'];
$postdata['cus_phone'] = "";
$postdata['cus_fax'] = "";

// OPTIONAL PARAMETERS.
$postdata['value_a'] = $_POST['custom'];
$postdata['value_b'] = $_POST['course_id'];
$postdata['value_c'] = $_POST['user_id'];
$postdata['value_d'] = $_POST['instance_id'];


$data = new stdClass();

$data->userid = (int)$_POST['user_id'];
$data->courseid = (int)$_POST['course_id'];
$data->instanceid = (int)$_POST['instance_id'];
$data->payment_currency = $_POST['currency_code'];
$data->payment_status = 'Pending';
$data->txn_id = $postdata['tran_id'];
$data->timeupdated = time();

$DB->insert_record("enrol_bkash", $data);


// REQUEST SEND TO bkash.
$directapiurl = get_config("enrol_bkash")->apiurl;
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $directapiurl);
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // KEEP IT FALSE IF YOU RUN FROM LOCAL PC.
$content = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
if ($code == 200 && !(curl_errno($handle))) {
    curl_close($handle);
    $bkashresponse = $content;
} else {
    curl_close($handle);
    echo "FAILED TO CONNECT WITH bkash API";
    exit;
}
// PARSE THE JSON RESPONSE.
$sslcz = json_decode($bkashresponse, true);
if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
    // THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
    // echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
    echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
    // ... header("Location: ". $sslcz['GatewayPageURL']);
    exit;
} else {
    echo "JSON Data parsing error!";
}