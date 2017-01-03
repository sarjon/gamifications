/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

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
