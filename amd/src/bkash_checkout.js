define(['jquery', 'core/ajax', 'core/config'], function ($, Ajax, mdlcfg) {

    return {
        setup: function (bkash_information) {


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

            var accessToken = '';
            $(document).ready(function () {

                $.ajax({
                    url: mdlcfg.wwwroot + "/enrol/bkash/token.php",
                    type: 'POST',
                    contentType: 'application/json',
                    success: function (data) {
                        console.log(data);
                        console.log('got data from token  ..');
                        console.log(JSON.stringify(data));
                        accessToken = JSON.stringify(data);
                        token = data;
                    },
                    error: function () {
                        console.log('error');

                    }
                });

                var paymentConfig = {
                    createCheckoutURL: mdlcfg.wwwroot + "/enrol/bkash/createpayment.php",
                    executeCheckoutURL: mdlcfg.wwwroot + "/enrol/bkash/executepayment.php",
                };


                var paymentRequest;
                paymentRequest = { amount: '105', intent: 'sale' };
                console.log(JSON.stringify(paymentRequest));

                bKash.init({
                    paymentMode: 'checkout',
                    paymentRequest: paymentRequest,
                    createRequest: function (request) {
                        console.log('=> createRequest (request) :: ');
                        console.log(request);

                        $.ajax({
                            url: paymentConfig.createCheckoutURL + "?amount=" + paymentRequest.amount,
                            type: 'GET',
                            contentType: 'application/json',
                            success: function (data) {
                                console.log(data);
                                console.log('got data from create  ..');
                                console.log('data ::=>');
                                console.log(data);

                                var obj = JSON.parse(data);
                                console.log(obj);

                                if (data && obj.paymentID != null) {
                                    paymentID = obj.paymentID;
                                    bKash.create().onSuccess(obj);
                                }
                                else {
                                    console.log('error');
                                    bKash.create().onError();
                                }
                            },
                            error: function () {
                                console.log('error');
                                bKash.create().onError();
                            }
                        });
                    },

                    executeRequestOnAuthorization: function () {
                        console.log('=> executeRequestOnAuthorization');
                        $.ajax({
                            url: paymentConfig.executeCheckoutURL + "?paymentID=" + paymentID,
                            type: 'GET',
                            contentType: 'application/json',
                            success: function (data) {
                                console.log(data);
                                console.log(data.paymentID);
                                if (data && data.paymentID != null) {
                                    alert(data.paymentID);

                                    // window.location.href = mdlcfg.wwwroot + "/enrol/bkash/process.php?" + "paymentID=" + data.paymentID + "&amount=" + amount + "&currency=" + currency + "&courseid=" + courseid + "&userid=" + userid + "&instanceid=" + instanceid + "&item_name=" + item_name;

                                    // alert('[SUCCESS] data : ' + JSON.stringify(data));
                                    // window.location.href = "success.html";
                                }
                                else {
                                    bKash.execute().onError();
                                }
                            },
                            error: function () {
                                bKash.execute().onError();
                            }
                        });
                    }
                });

                console.log("Right after init ");


            });

            function callReconfigure(val) {
                bKash.reconfigure(val);
            }

            function clickPayButton() {
                $("#bKash_button").trigger('click');
            }
        }
    }
});
