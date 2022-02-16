<?php
    global $DB, $USER;

    $instance_info = [
        'courseid' => $courseid,
        'instanceid' => $instanceid,
        'currency' => $currency,
        'amount' => $amount,
        'timecreated' => $timecreated,
        'timemodified' => $timemodified,
        'userid' => $userid,
        'item_name' => $item_name,
        'config' => $config
    ];

    $PAGE->requires->js_call_amd("enrol_bkash/bkash_checkout", 'setup', [$instance_info]);

?>
<div align="center">

<script src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>

<p><?php print_string("paymentrequired") ?></p>
<p><b><?php echo $instancename; ?></b></p>
<p><b><?php echo get_string("cost").": {$instance->currency} {$localisedcost}"; ?></b></p>
<p> <img alt="<?php print_string('bkashaccepted', 'enrol_bkash') ?>"
    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjqVfXeM4v-2gJsfCe6x9Lxgy5A5QHTjSu4NWusv0Ih9sKlPMIBFBKnPx37e_fuTo7SqQ&usqp=CAU"
/>
</p>
<p><?php print_string("paymentinstant") ?></p>

<button id="bKash_button" class="btn btn-danger">Pay With bKash</button>

</div>