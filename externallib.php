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
 * External Web Service Template
 * @package    enrol
 * @subpackage enrol_bkash
 * @author     Brain station 23 ltd <brainstation-23.com>
 * @copyright  2021 Brain station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . "/config.php");
require_once($CFG->dirroot . "/enrol/bkash/lib.php");
require_login();


/**
 * External class.
 *
 * @package gradebookreset
 * @copyright 2021 Brain Station 23 ltd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_bkash_external extends external_api {

    /**
     * Returns description of @link enrol_bkash_checkout_info method parameters
     * @return external_function_parameters
     */


    public static function bkash_enrolment_detail_parameters()
    {
        return new external_function_parameters (
            array(
                'courseid' => new external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
                'payment_status' => new external_value(PARAM_TEXT, 'Payment Status', VALUE_REQUIRED),
                'txn_id' => new external_value(PARAM_TEXT, 'Transaction ID', VALUE_REQUIRED),
                'item_name' => new external_value(PARAM_TEXT, 'Item Name', VALUE_REQUIRED),
                'instanceid'  => new external_value(PARAM_INT, 'Instance id', VALUE_REQUIRED)
                )
        );
    }


    /**
     * @param $courseid
     * @param $userid
     * @param $currency
     * @param $amount
     * @param $bkash_response
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function bkash_enrolment_detail($courseid, $payment_status, $txn_id, $item_name, $instanceid) {
        global $DB, $USER;

        self::validate_parameters(
            self::bkash_enrolment_detail_parameters(),
            array(
                'courseid' => $courseid,
                'payment_status' => $payment_status,
                'txn_id' => $txn_id,
                'item_name' => $item_name,
                'instanceid' => $instanceid,
            )
        );

        $data = new stdClass();
        $data->userid = $USER->id;
        $data->courseid = $courseid;
        $data->payment_status = $payment_status;
        $data->txn_id = $txn_id;
        $data->item_name = $item_name;
        $data->instanceid = $instanceid;

        // Query Function.
        $DB->insert_record("enrol_bkash_log", $data);

        if ($payment_status == 'complete') {

            // Transaction exists already.
            if ($DB->get_record("enrol_bkash", array("txn_id" => $data->txn_id))) {
                $output['message'] = 'error';
            } else {
                $DB->insert_record("enrol_bkash", $data);
                $output['message'] = 'success';
            }
        }

        return $output;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function bkash_enrolment_detail_returns() {
        return new external_single_structure(
             array(
                 'message' => new external_value(PARAM_TEXT, 'Transaction status')
             )
        );
    }
}
