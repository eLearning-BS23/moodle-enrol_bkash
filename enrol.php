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
    global $DB, $USER;
    ?>
<div align="center">
    <p>
        <?php print_string("paymentrequired") ?>
    </p>
    <p><strong>
            <?php echo $instancename; ?>
        </strong></p>
    <p><strong>
            <?php echo get_string("cost").": {$instance->currency} {$localisedcost}"; ?>
        </strong></p>
    <p> <img alt="<?php print_string('bkashaccepted', 'enrol_bkash') ?>"
            src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjqVfXeM4v-2gJsfCe6x9Lxgy5A5QHTjSu4NWusv0Ih9sKlPMIBFBKnPx37e_fuTo7SqQ&usqp=CAU" />
    </p>
    <p>
        <?php print_string("paymentinstant") ?>
    </p>

    <form method="post" action="<?php echo $CFG->wwwroot; ?>/enrol/bkash/payment.php">
        <input type="hidden" id="custom" name="custom" value="<?php echo $USER->id.'-'.$course->id.'-'.$instance->id; ?>" />
        <input type="hidden" id="courseid" name="courseid" value="<?php echo $course->id; ?>" />
        <input type="hidden" id="userid" name="userid" value="<?php echo $USER->id; ?>" />
        <input type="hidden" id="instanceid" name="instanceid" value="<?php echo $instance->id; ?>" />
        <input type="hidden" value="<?=p($cost) ?>" name="amount" id="amount" required />
        <input type="hidden" name="currency_code" value="<?php p($instance->currency) ?>" />
        <button class="btn btn-danger">Pay With bKash</button>
    </form>


</div>