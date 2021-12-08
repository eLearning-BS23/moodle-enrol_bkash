define(['jquery'], function ($)
{

    return {
        setup: function(bkash_information) {


    var paymentID;

    var username = bkash_information.config.username;
        console.log(username);

    var password = bkash_information.config.password;
        console.log(password);

    var app_key = bkash_information.config.appkey;
        console.log(app_key);

    var app_secret = bkash_information.config.appsecretkey;
        console.log(app_secret);

    var amount = bkash_information.amount;
    var currency = bkash_information.currency;
    var courseid = bkash_information.courseid;
    var userid = bkash_information.userid;

        console.log(currency);
        console.log(amount);
        console.log(courseid);
        console.log(userid);

    var grantTokenUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/token/grant';
    var createCheckoutUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/create';
    var executeCheckoutUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/execute';

    $(document).ready(function () {
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
            success: function (result) {

                let headers = {
                    "Content-Type": "application/json",
                    "Authorization": result.id_token, // Contains access token
                    "X-APP-Key": app_key
                };

                let request = {             //create payment API
                    "amount": amount,
                    "intent": "sale",
                    "currency": currency,
                    "merchantInvoiceNumber": "123456"
                };

                initBkash(headers, request);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function initBkash(headers, request) {
        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: request,

            createRequest: function (request) {
                $.ajax({
                    url: createCheckoutUrl,
                    headers: headers,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(request),
                    success: function (data) {

                        if (data && data.paymentID != null) {
                            paymentID = data.paymentID;
                            bKash.create().onSuccess(data);
                        }
                        else {
                            bKash.create().onError(); // Run clean up code
                            alert(data.errorMessage + " Tag should be 2 digit, Length should be 2 digit, Value should be number of character mention in Length, ex. MI041234 , supported tags are MI, MW, RF");
                        }

                    },
                    error: function () {
                        bKash.create().onError(); // Run clean up code
                        alert(data.errorMessage);
                    }
                });
            },
            executeRequestOnAuthorization: function () {
                $.ajax({
                    url: executeCheckoutUrl + '/' + paymentID,
                    headers: headers,
                    type: 'POST',
                    contentType: 'application/json',
                    success: function (data) {

                        if (data && data.paymentID != null) {
                            // On success, perform your desired action
                            console.log(data.paymentID);
                            console.log(data.amount);
                            console.log(data.currency);



                            // $bkash_enrol =


                            myobject = JSON.stringify(data);


                            alert('[SUCCESS] data : ' + myobject);

                            window.location.href = "/success_page.html";

                        } else {
                            alert('[ERROR] data : ' + JSON.stringify(data));
                            bKash.execute().onError();//run clean up code
                        }

                    },
                    error: function () {
                        alert('An alert has occurred during execute');
                        bKash.execute().onError(); // Run clean up code
                    }
                });
            },
            onClose: function () {
                alert('User has clicked the close button');
            }
        });

    }
}

}});
