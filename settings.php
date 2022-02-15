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
 * bkash enrolments plugin settings and presets.
 *
 * @package    enrol_bkash
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Settings.
    $settings->add(new admin_setting_heading('enrol_bkash_settings', '',
        get_string('pluginname_desc', 'enrol_bkash')));

    $settings->add(new admin_setting_configtext('enrol_bkash/username',
        get_string('bkash_username', 'enrol_bkash'),
        get_string('bkash_username_desc', 'enrol_bkash'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('enrol_bkash/password',
        get_string('bkash_password', 'enrol_bkash'),
        get_string('bkash_password_desc', 'enrol_bkash'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('enrol_bkash/appkey',
        get_string('bkash_app_key', 'enrol_bkash'),
        get_string('bkash_app_key_desc', 'enrol_bkash'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('enrol_bkash/appsecretkey',
        get_string('bkash_app_secret_key', 'enrol_bkash'),
        get_string('bkash_app_secret_key_desc', 'enrol_bkash'), '', PARAM_TEXT));

    $options = array(
        "sandboz" => get_string('sandbox', 'enrol_bkash'),
        "live" => get_string('live', 'enrol_bkash'),
    );
    $settings->add(new admin_setting_configselect('enrol_bkash/paymentmode',
        get_string('paymentmode', 'enrol_bkash'),
        get_string('paymentmode_help', 'enrol_bkash'), "sandbox", $options));


}
