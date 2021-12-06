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

    $settings->add(new admin_setting_configcheckbox('enrol_bkash/mailstudents',
        get_string('mailstudents', 'enrol_bkash'), '', 0));

    $settings->add(new admin_setting_configcheckbox('enrol_bkash/mailteachers',
        get_string('mailteachers', 'enrol_bkash'), '', 0));

    $settings->add(new admin_setting_configcheckbox('enrol_bkash/mailadmins',
        get_string('mailadmins', 'enrol_bkash'), '', 0));

    // Note: let's reuse the ext sync constants and strings here, internally it is very similar,
    // it describes what should happen when users are not supposed to be enrolled any more.
    $options = array(
        ENROL_EXT_REMOVED_KEEP => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL => get_string('extremovedunenrol', 'enrol'),
    );
    $settings->add(new admin_setting_configselect('enrol_bkash/expiredaction',
        get_string('expiredaction', 'enrol_bkash'),
        get_string('expiredaction_help', 'enrol_bkash'), ENROL_EXT_REMOVED_SUSPENDNOROLES, $options));

    // Enrol instance defaults.
    $settings->add(new admin_setting_heading('enrol_bkash_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $options = array(ENROL_INSTANCE_ENABLED => get_string('yes'),
        ENROL_INSTANCE_DISABLED => get_string('no'));

    $settings->add(new admin_setting_configselect('enrol_bkash/status',
        get_string('status', 'enrol_bkash'), get_string('status_desc', 'enrol_bkash'),
        ENROL_INSTANCE_DISABLED, $options));

    $settings->add(new admin_setting_configtext('enrol_bkash/cost',
        get_string('cost', 'enrol_bkash'), '', 0, PARAM_FLOAT, 4));

    $bkashcurrencies = enrol_get_plugin('bkash')->get_currencies();
    $settings->add(new admin_setting_configselect('enrol_bkash/currency',
        get_string('currency', 'enrol_bkash'), '', 'USD', $bkashcurrencies));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_bkash/roleid',
            get_string('defaultrole', 'enrol_bkash'),
            get_string('defaultrole_desc', 'enrol_bkash'),
            $student->id ?? null,
            $options));
    }

    $settings->add(new admin_setting_configduration('enrol_bkash/enrolperiod',
        get_string('enrolperiod', 'enrol_bkash'),
        get_string('enrolperiod_desc', 'enrol_bkash'), 0));
}
