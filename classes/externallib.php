<?php

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
require_once($CFG->dirroot . "/config.php");
require_once($CFG->dirroot . "/enrol/bkash/lib.php");
require_once($CFG->libdir . "/externallib.php");




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


public static function enrol_bkash_checkout_info_parameters()
{
    return new external_function_parameters (
        array(
            'courseid' => new external_value(PARAM_INT, 'Course Id', VALUE_REQUIRED),
            'userid'  => new external_value(PARAM_INT, 'User id', VALUE_REQUIRED),
            'currency' => new external_value(PARAM_RAW, 'Currency', VALUE_REQUIRED),
            'amount' => new external_value(PARAM_INT, 'amount', VALUE_REQUIRED),
            'bkash_response' => new external_value(PARAM_RAW, 'response', VALUE_REQUIRED)
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
public static function enrol_bkash_checkout_info($courseid, $userid, $currency, $amount, $bkash_response)
{
    global $DB;

    self::validate_parameters(
        self::enrol_bkash_checkout_info_parameters(),
        array(
            'courseid' => $courseid,
            'userid' => $userid,
            'currency' => $currency,
            'amount' => $amount,
            'bkash_response' => $bkash_response

        )
    );

    //query function



//    return $result;
}


/**
 * Returns description of method result value
 * @return external_description
 */
    public static function enrol_bkash_checkout_info_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'courseid' => new external_value(PARAM_INT, 'course id', VALUE_REQUIRED),
                    'userid' => new external_value(PARAM_INT, 'end time of a quiz', VALUE_REQUIRED),
                    'currency' =>  new external_value(PARAM_RAW, 'end time of a quiz', VALUE_REQUIRED),
                    'amount' => new external_value(PARAM_INT, 'end time of a quiz', VALUE_REQUIRED),
                    'payment_status' => new external_value(PARAM_RAW, 'Payment Status', VALUE_REQUIRED),
                    'instanceid' => new external_value(PARAM_RAW, 'Instance ID', VALUE_REQUIRED),
                    'timeupdated' => new external_value(PARAM_RAW, 'time updated', VALUE_REQUIRED),
                    'warnings' => new external_warnings()
                )
            )
        );
    }
}
