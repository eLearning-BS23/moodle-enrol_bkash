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
 * Strings for component 'enrol_bkash', language 'en'.
 *
 * @package    enrol_bkash
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['assignrole'] = 'Assign role';
$string['businessemail'] = 'bkash business email';
$string['cost'] = 'Enrol cost';
$string['costerror'] = 'The enrolment cost is not numeric';
$string['costorkey'] = 'Please choose one of the following methods of enrolment.';
$string['currency'] = 'Currency';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during bkash enrolments';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'Enrolment end date cannot be earlier than start date';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['errdisabled'] = 'The bkash enrolment plugin is disabled and does not handle payment notifications.';
$string['erripninvalid'] = 'Instant payment notification has not been verified by bkash.';
$string['errbkashconnect'] = 'Could not connect to {$a->url} to verify the instant payment notification: {$a->result}';
$string['expiredaction'] = 'Enrolment expiry action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['mailadmins'] = 'Notify admin';
$string['mailstudents'] = 'Notify students';
$string['mailteachers'] = 'Notify teachers';
$string['messageprovider:enrol_bkash'] = 'bkash enrolment messages';
$string['nocost'] = 'There is no cost associated with enrolling in this course!';
$string['bkash:config'] = 'Configure bkash enrol instances';
$string['bkash:manage'] = 'Manage enrolled users';
$string['bkash:unenrol'] = 'Unenrol users from course';
$string['bkash:unenrolself'] = 'Unenrol self from the course';
$string['bkashaccepted'] = 'bkash payments accepted';
$string['pluginname'] = 'bkash';
$string['pluginname_desc'] = 'The bkash module allows you to set up paid courses.  If the cost for any course is zero, then students are not asked to pay for entry.  There is a site-wide cost that you set here as a default for the whole site and then a course setting that you can set for each course individually. The course cost overrides the site cost.';
$string['privacy:metadata:enrol_bkash:enrol_bkash'] = 'Information about the bkash transactions for bkash enrolments.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:business'] = 'Email address or bkash account ID of the payment recipient (that is, the merchant).';
$string['privacy:metadata:enrol_bkash:enrol_bkash:courseid'] = 'The ID of the course that is sold.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:instanceid'] = 'The ID of the enrolment instance in the course.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:item_name'] = 'The full name of the course that its enrolment has been sold.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:memo'] = 'A note that was entered by the buyer in bkash website payments note field.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:option_selection1_x'] = 'Full name of the buyer.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:parent_txn_id'] = 'In the case of a refund, reversal, or canceled reversal, this would be the transaction ID of the original transaction.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:payment_status'] = 'The status of the payment.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:payment_type'] = 'Holds whether the payment was funded with an eCheck (echeck), or was funded with bkash balance, credit card, or instant transfer (instant).';
$string['privacy:metadata:enrol_bkash:enrol_bkash:pending_reason'] = 'The reason why payment status is pending (if that is).';
$string['privacy:metadata:enrol_bkash:enrol_bkash:reason_code'] = 'The reason why payment status is Reversed, Refunded, Canceled_Reversal, or Denied (if the status is one of them).';
$string['privacy:metadata:enrol_bkash:enrol_bkash:receiver_email'] = 'Primary email address of the payment recipient (that is, the merchant).';
$string['privacy:metadata:enrol_bkash:enrol_bkash:receiver_id'] = 'Unique bkash account ID of the payment recipient (i.e., the merchant).';
$string['privacy:metadata:enrol_bkash:enrol_bkash:tax'] = 'Amount of tax charged on payment.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:timeupdated'] = 'The time of Moodle being notified by bkash about the payment.';
$string['privacy:metadata:enrol_bkash:enrol_bkash:txn_id'] = 'The merchant\'s original transaction identification number for the payment from the buyer, against which the case was registered';
$string['privacy:metadata:enrol_bkash:enrol_bkash:userid'] = 'The ID of the user who bought the course enrolment.';
$string['privacy:metadata:enrol_bkash:bkash_com'] = 'The bkash enrolment plugin transmits user data from Moodle to the bkash website.';
$string['privacy:metadata:enrol_bkash:bkash_com:address'] = 'Address of the user who is buying the course.';
$string['privacy:metadata:enrol_bkash:bkash_com:city'] = 'City of the user who is buying the course.';
$string['privacy:metadata:enrol_bkash:bkash_com:country'] = 'Country of the user who is buying the course.';
$string['privacy:metadata:enrol_bkash:bkash_com:custom'] = 'A hyphen-separated string that contains ID of the user (the buyer), ID of the course, ID of the enrolment instance.';
$string['privacy:metadata:enrol_bkash:bkash_com:email'] = 'Email address of the user who is buying the course.';
$string['privacy:metadata:enrol_bkash:bkash_com:first_name'] = 'First name of the user who is buying the course.';
$string['privacy:metadata:enrol_bkash:bkash_com:last_name'] = 'Last name of the user who is buying the course.';
$string['privacy:metadata:enrol_bkash:bkash_com:os0'] = 'Full name of the buyer.';
$string['processexpirationstask'] = 'bkash enrolment send expiry notifications task';
$string['sendpaymentbutton'] = 'Send payment via bkash';
$string['status'] = 'Allow bkash enrolments';
$string['status_desc'] = 'Allow users to use bkash to enrol into a course by default.';
$string['transactions'] = 'bkash transactions';
$string['unenrolselfconfirm'] = 'Do you really want to unenrol yourself from course "{$a}"?';
$string['paymendue'] = 'Amount paid is not enough "{$a}"?';
$string['coursemissing'] = 'Course "{$a}" doesn\'t exist';
$string['usermissing'] = 'User {$a} doesn\'t exist';
$string['pluginname'] = 'bkash';
$string['paymenterror'] = 'Error updating record: Something went wrong';
$string['paymentfail'] = 'Payment was not valid. Please contact with the merchant.';
$string['paymentinvalid'] = 'Invalid Information.';
$string['apiurl_desc'] = 'bkash Api v3 url';
$string['apiurl'] = 'Api Url';
$string['requestedurl'] = 'Requested Url';
$string['requestedurl_desc'] = 'Requested Url Without parameter';
$string['course'] = 'Course';
$string['txn_repeat'] = 'The transaction is being repeated';

$string['bkash_username'] = 'User Name';
$string['paywithbkash'] = 'Pay With bKash';
$string['bkash_username_desc'] = 'BKash Merchant User Name';
$string['bkash_password'] = 'Password';
$string['bkash_password_desc'] = 'BKash Merchant Password';
$string['bkash_app_key'] = 'BKash App Key';
$string['bkash_app_key_desc'] = 'BKash Merchant App Key';
$string['bkash_app_secret_key'] = 'BKash App Secret Key';
$string['bkash_app_secret_key_desc'] = 'BKash Merchant App Secret Key';

