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

    var REWARD_TYPE_POINTS = 1;
    var REWARD_TYPE_DISCOUNT = 3;
    var REWARD_TYPE_FREE_SHIPPING = 4;
    var REWARD_TYPE_GIFT = 5;
    var REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS = 2;

    var $productProductInput = $('#product_name');
    var $productHiddenInput = $('#id_product');

    /**
     * Autocomplete products search in from
     */
    $productProductInput
        .autocomplete($gamificationsRewardControllerUrl, {
            minChars: 3,
            max: 10,
            width: 300,
            selectFirst: false,
            scroll: false,
            dataType: 'json',
            formatItem: function($data, $i, $max, $value) {
                return $value;
            },
            parse: function ($response) {
                var $products = [];

                if (typeof $response.products == 'undefined') {
                    return $products;
                }

                for (var i = 0; i < $response.products.length; i++) {
                    $products[i] = {
                        data: $response.products[i],
                        value: $response.products[i].name
                    };
                }

                return $products;
            }
        })
        .result(function ($event, $data) {

            $productProductInput.val($data.name);
            $productHiddenInput.val($data.id_product);

        });

    var $rewardType = $('#reward_type');
    var $pointsInput = $('#points').closest('div.form-group');
    var $productNameInput = $('#product_name').closest('div.form-group');
    var $discountReductionTypeInput = $('#discount_reduction_type').closest('div.form-group');
    var $discountValueInput = $('#discount_value').closest('div.form-group');
    var $discountValidDaysInput = $('#discount_valid_days').closest('div.form-group');
    var $minimumCartAmountInput = $('#minimum_cart_amount').closest('div.form-group');
    var $pointsRadiusInput = $('#radius').closest('div.form-group');

    $rewardType.on('change', showRewardTypeFields);

    // Show correct fields on pageload
    showRewardTypeFields();

    /**
     * Show correct form fields by selected reward type
     */
    function showRewardTypeFields()
    {
        switch (parseInt($rewardType.val())) {
            case REWARD_TYPE_POINTS:
                $pointsInput.show();
                $productNameInput.hide();
                $discountReductionTypeInput.hide();
                $discountValueInput.hide();
                $discountValidDaysInput.hide();
                $minimumCartAmountInput.hide();
                $pointsRadiusInput.hide();
                break;
            case REWARD_TYPE_DISCOUNT:
                $pointsInput.hide();
                $productNameInput.hide();
                $discountReductionTypeInput.show();
                $discountValueInput.show();
                $discountValidDaysInput.show();
                $minimumCartAmountInput.show();
                $pointsRadiusInput.hide();
                break;
            case REWARD_TYPE_FREE_SHIPPING:
                $pointsInput.hide();
                $productNameInput.hide();
                $discountReductionTypeInput.hide();
                $discountValueInput.hide();
                $discountValidDaysInput.show();
                $minimumCartAmountInput.show();
                $pointsRadiusInput.hide();
                break;
            case REWARD_TYPE_GIFT:
                $pointsInput.hide();
                $productNameInput.show();
                $discountReductionTypeInput.hide();
                $discountValueInput.hide();
                $discountValidDaysInput.show();
                $minimumCartAmountInput.show();
                $pointsRadiusInput.hide();
                break;
            case REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS:
                $pointsInput.show();
                $productNameInput.hide();
                $discountReductionTypeInput.hide();
                $discountValueInput.hide();
                $discountValidDaysInput.hide();
                $minimumCartAmountInput.hide();
                $pointsRadiusInput.show();
                break;
        }
    }

});
