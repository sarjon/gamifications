$(document).ready(function () {

    var REWARD_TYPE_POINTS = 'points';
    var REWARD_TYPE_DISCOUNT = 'discount';
    var REWARD_TYPE_FREE_SHIPPING = 'free_shipping';
    var REWARD_TYPE_PRIZE = 'prize';

    var $prizeProductInput = $('#prize_name');
    var $prizeHiddenInput = $('#prize');

    /**
     * Autocomplete products search in from
     */
    $prizeProductInput
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

            $prizeProductInput.val($data.name);
            $prizeHiddenInput.val($data.id_product);

        });

    var $rewardType = $('#reward_type');
    var $pointsInput = $('#points').closest('div.form-group');
    var $prizeNameInput = $('#prize_name').closest('div.form-group');
    var $discountReductionTypeInput = $('#discount_reduction_type').closest('div.form-group');
    var $discountValueInput = $('#discount_value').closest('div.form-group');
    var $discountApplyTypeInput = $('#discount_apply_type').closest('div.form-group');
    var $discountValidDaysInput = $('#discount_valid_days').closest('div.form-group');
    var $minimumCartAmountInput = $('#minimum_cart_amount').closest('div.form-group');

    $rewardType.on('change', showRewardTypeFields);

    // Show correct fields on pageload
    showRewardTypeFields();

    /**
     * Show correct form fields by selected reward type
     */
    function showRewardTypeFields()
    {
        switch ($rewardType.val()) {
            case REWARD_TYPE_POINTS:
                $pointsInput.show();
                $prizeNameInput.hide();
                $discountReductionTypeInput.hide();
                $discountValueInput.hide();
                $discountApplyTypeInput.hide();
                $discountValidDaysInput.hide();
                $minimumCartAmountInput.hide();
                break;
            case REWARD_TYPE_DISCOUNT:
                $pointsInput.hide();
                $prizeNameInput.hide();
                $discountReductionTypeInput.show();
                $discountValueInput.show();
                $discountApplyTypeInput.show();
                $discountValidDaysInput.show();
                $minimumCartAmountInput.show();
                break;
            case REWARD_TYPE_FREE_SHIPPING:
                $pointsInput.hide();
                $prizeNameInput.hide();
                $discountReductionTypeInput.hide();
                $discountValueInput.hide();
                $discountApplyTypeInput.hide();
                $discountValidDaysInput.hide();
                $minimumCartAmountInput.show();
                break;
            case REWARD_TYPE_PRIZE:
                $pointsInput.hide();
                $prizeNameInput.show();
                $discountReductionTypeInput.hide();
                $discountValueInput.hide();
                $discountApplyTypeInput.hide();
                $discountValidDaysInput.hide();
                $minimumCartAmountInput.hide();
                break;
        }
    }

});
