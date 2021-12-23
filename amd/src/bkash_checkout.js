define(['jquery', 'core/ajax'], function($, Ajax) {

    return {
        setup: function(bkash_information) {


            var paymentID;

            var username = bkash_information.config.username;

            var password = bkash_information.config.password;

            var app_key = bkash_information.config.appkey;


            var app_secret = bkash_information.config.appsecretkey;

            var amount = bkash_information.amount;
            var currency = bkash_information.currency;
            var courseid = bkash_information.courseid;
            var userid = bkash_information.userid;
            var instanceid = bkash_information.instanceid;
            var item_name = bkash_information.item_name;

            var grantTokenUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/token/grant';
            var createCheckoutUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/create';
            var executeCheckoutUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/execute';


            $(document).ready(function() {
                getAuthToken();
            });

            function getAuthToken() {
                let body = {
                    "app_key": app_key,
                    "app_secret": app_secret
                };

                $.ajax({
                    url: grantTokenUrl,   // Sandbox checkout
                    headers: {
                        "username": username,
                        "password": password,
                        "Content-Type": "application/json"
                    },
                    type: 'POST',
                    data: JSON.stringify(body),   //JS obj to string covert
                    success: function(result) {

                        let headers = {
                            "Content-Type": "application/json",
                            "Authorization": result.id_token, // Contains access token
                            "X-APP-Key": app_key
                        };

                        let request = {             //create payment API
                            "amount": amount,
                            "intent": "sale",
                            "currency": "BDT",
                            "merchantInvoiceNumber": "123456"
                        };

                        initBkash(headers, request);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            function initBkash(headers, request) {
                bKash.init({
                    paymentMode: 'checkout',
                    paymentRequest: request,

                    createRequest: function(request) {
                        $.ajax({
                            url: createCheckoutUrl,
                            headers: headers,
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(request),
                            success: function(data) {

                                if (data && data.paymentID != null) {
                                    paymentID = data.paymentID;
                                    bKash.create().onSuccess(data);
                                }
                                else {
                                    bKash.create().onError(); // Run clean up code
                                    alert(data.errorMessage + " Tag should be 2 digit, Length should be 2 digit, Value should be number of character mention in Length, ex. MI041234 , supported tags are MI, MW, RF");
                                }

                            },
                            error: function() {
                                bKash.create().onError(); // Run clean up code
                                alert(data.errorMessage);
                            }
                        });
                    },
                    executeRequestOnAuthorization: function() {
                        $.ajax({
                            url: executeCheckoutUrl + '/' + paymentID,
                            headers: headers,
                            type: 'POST',
                            contentType: 'application/json',
                            success: function(data) {

                                if (data && data.paymentID != null) {
                                    // On success, perform your desired action
                                    alert('[SUCCESS] data : ' + JSON.stringify(data));
                                    window.location.href = "/success_page.html";

                                } else {
                                    alert('[ERROR] data : ' + JSON.stringify(data));
                                    bKash.execute().onError();//run clean up code
                                }

                            },
                            error: function() {
                                alert('An alert has occurred during execute');
                                bKash.execute().onError(); // Run clean up code
                            }
                        });
                    },
                    onClose: function() {
                        alert('User has clicked the close button');
                    }
                });

                function store_enroll_data(params) {
                    var params = {
                        'courseid': courseid,
                        'userid': userid,
                        'payment_status': data.transactionStatus,
                        'txn_id': data.trxID,
                        'item_name': item_name,
                        'instanceid': instanceid

                    };
                    var wsfunction = 'enrol_bkash_bkash_enrolment_detail';
                    var params = params;

                    var request = {
                        methodname: wsfunction,
                        args: params
                    };


                    Ajax.call([request])[0].done(function(data) {

                        console.log(data.message);

                        str.get_string('txn_repeat', 'enrol_bkash').then(function(langString) {
                            notification.addNotification({
                                message: langString,
                                type: 'error'
                            });
                            console.log(data.message);

                        }).catch(Notification.exception);

                    }).fail(Notification.exception);
                }
            }

        }
    }
});
