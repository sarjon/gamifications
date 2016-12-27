(function() {
    var referralUrl = document.querySelector('.js-gamifications-referral-url-copy');

    referralUrl.addEventListener('click', function(event) {
        event.preventDefault();

        var input = document.querySelector('.js-gamifications-referral-url-input');
        input.select();

        try {
            document.execCommand('copy');
        } catch (error) {
            console.warn('Your browser does not support `copy` command.');
        }
    });

})();
