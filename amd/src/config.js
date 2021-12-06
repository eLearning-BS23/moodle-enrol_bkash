define([], function () {
    window.requirejs.config({
        paths: {
            "bkash_jquery": M.cfg.wwwroot + '/enrol/bkash/js/bkash_jquery.js',
            "bkash_checkout_sandbox": M.cfg.wwwroot + '/enrol/bkash/js/bkash_checkout_sandbox.js',
        },
        shim: {
            'bkash_jquery': {exports: 'bkash_jquery'},
            'bkash_checkout_sandbox': {exports: 'bkash_checkout_sandbox'},
        }
    });
});