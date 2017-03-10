/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$(document).ready(function () {

    var NEW_CUSTOMER_REGISTRATION = 1;
    var NEW_CUSTOMER_ORDER = 2;

    var $orderStatesFromGroup = $('#fieldset_0 > div.form-wrapper > div:nth-child(3)');
    var $newCustomerRewardFromGroup = $('#GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD').closest('.form-group');


    var $rewardReferrerWhenInput = $('input[name="GAMIFICATIONS_REFERRAL_REWARD_TIME"]');
    var $rewardReferrerWhenInputChecked = $('input[name="GAMIFICATIONS_REFERRAL_REWARD_TIME"]:checked');
    var $newCustomerRewardEnabledInput = $('input[name="GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD_ENABLED"]');
    var $newCustomerRewardEnabledInputChecked =
        $('input[name="GAMIFICATIONS_REFERRAL_NEW_CUSTOMER_REWARD_ENABLED"]:checked');

    toggleOrderStateSwap($rewardReferrerWhenInputChecked.val());
    toggleNewCustomerReward($newCustomerRewardEnabledInputChecked.val());

    $rewardReferrerWhenInput.on('change', function () {
        var $selectedValue = $(this).val();
        toggleOrderStateSwap($selectedValue);
    });

    $newCustomerRewardEnabledInput.on('change', function () {
        var $isNewCustomerRewardEnabled = $(this).val();
        toggleNewCustomerReward($isNewCustomerRewardEnabled);
    });

    /**
     * Show/hide orders state swap box based on selected value
     *
     * @param $selectedValue
     */
    function toggleOrderStateSwap($selectedValue)
    {
        $selectedValue = parseInt($selectedValue);

        if (parseInt($selectedValue) == NEW_CUSTOMER_REGISTRATION) {
            $orderStatesFromGroup.hide();
        } else if (parseInt($selectedValue) == NEW_CUSTOMER_ORDER) {
            $orderStatesFromGroup.show();
        }
    }

    /**
     * Show/hide new customer reward select
     *
     * @param $isNewCustomerRewardEnabled
     */
    function toggleNewCustomerReward($isNewCustomerRewardEnabled)
    {
        console.log($isNewCustomerRewardEnabled);
        $isNewCustomerRewardEnabled = parseInt($isNewCustomerRewardEnabled);

        if ($isNewCustomerRewardEnabled) {
            $newCustomerRewardFromGroup.show();
        } else {
            $newCustomerRewardFromGroup.hide();
        }
    }
});