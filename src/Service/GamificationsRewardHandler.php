<?php

/**
 * Class GamificationsRewardHandler
 */
class GamificationsRewardHandler
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var \PrestaShopBundle\Translation\TranslatorComponent
     */
    private $translator;

    /**
     * GamificationsRewardHandler constructor.
     */
    public function __construct()
    {
        $context = Context::getContext();

        $this->context = $context;
        $this->translator = $context->getTranslator();
    }

    /**
     * Handle customer reward (add points, create discount & etc)
     *
     * @param GamificationsReward $reward
     * @param GamificationsCustomer $gamificationsCustomer
     * @param int $activityType
     *
     * @return array
     */
    public function handleCustomerReward(GamificationsReward $reward, GamificationsCustomer $gamificationsCustomer, $activityType)
    {
        $results = [];
        $results['success'] = false;

        $points = 0;

        switch ((int) $reward->reward_type) {
            case GamificationsReward::REWARD_TYPE_POINTS:
                $points = $reward->points;
                $gamificationsCustomer->addPoints($points);
                $results['message'] = $this->translator
                    ->trans('You got %points% points!', ['%points%' => $reward->points], 'Modules.Gamifications.Shop');
                $results['success'] = true;
                break;
            case GamificationsReward::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS:
                $min = $reward->points - $reward->radius;
                $max = $reward->points + $reward->radius;
                $points = rand($min, $max);
                $gamificationsCustomer->addPoints($points);
                $results['message'] = $this->translator
                    ->trans('You got %points% points!', ['%points%' => $points], 'Modules.Gamifications.Shop');
                $results['success'] = true;
                break;
            case GamificationsReward::REWARD_TYPE_DISCOUNT:
            case GamificationsReward::REWARD_TYPE_FREE_SHIPPING:
            case GamificationsReward::REWARD_TYPE_GIFT:
                $this->createVoucher($reward, $gamificationsCustomer);
                $results['message'] = $this->translator->trans(
                    'You got %rewrd_name%!',
                    ['%rewrd_name%' => $reward->name[$this->context->language->id]],
                    'Modules.Gamifications.Shop'
                );
                $results['success'] = true;
                break;
        }

        if ($results['success']) {
            GamificationsActivityHistory::log($reward, $gamificationsCustomer->id_customer, $activityType, $points);
        }

        return $results;
    }

    /**
     * Create discount for customer
     *
     * @param GamificationsReward $reward
     * @param GamificationsCustomer $gamificationsCustomer
     *
     * @return bool
     */
    protected function createVoucher(GamificationsReward $reward, GamificationsCustomer $gamificationsCustomer)
    {
        $customer = new Customer($gamificationsCustomer->id_customer);
        $defaultCurrencyId = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $validFrom = new DateTime();
        $validTo = new DateTime();
        $validTo = $validTo->add(new DateInterval(sprintf('P%dD', (int) $reward->discount_valid_days)));

        $voucher = new CartRule();

        $voucher->id_customer = (int) $customer->id;
        $voucher->active = true;
        $voucher->date_from = $validFrom->format('Y-m-d H:i:s');
        $voucher->date_to = $validTo->format('Y-m-d H:i:s');
        $voucher->name = $reward->name;
        $voucher->partial_use = false;
        $voucher->highlight = false;
        $voucher->priority = 1;
        $voucher->quantity_per_user = 1;
        $voucher->quantity = 1;
        $voucher->minimum_amount = $reward->minimum_cart_amount;
        $voucher->minimum_amount_currency = $defaultCurrencyId;

        if ($reward->discount_apply_type = GamificationsReward::DISCOUNT_TYPE_CODE) {
            $voucher->code = Tools::passwdGen();
        }

        $rewardType = (int) $reward->reward_type;

        if (GamificationsReward::REWARD_TYPE_DISCOUNT == $rewardType) {
            $this->configureDiscount($voucher, $reward);
        } elseif (GamificationsReward::REWARD_TYPE_FREE_SHIPPING == $rewardType) {
            $this->configureFreeShipping($voucher, $reward);
        } elseif (GamificationsReward::REWARD_TYPE_GIFT == $rewardType) {
            $this->configureGift($voucher, $reward);
        }

        return $voucher->save();
    }

    /**
     * Configure discount data from reward
     *
     * @param CartRule $voucher
     * @param GamificationsReward $reward
     */
    protected function configureDiscount(CartRule &$voucher, GamificationsReward $reward)
    {
        $voucher->reduction_tax = true;
        $voucher->reduction_currency = $voucher->minimum_amount_currency;

        switch ($reward->discount_reduction_type) {
            case GamificationsReward::DISCOUNT_REDUCTION_AMOUNT:
                $voucher->reduction_amount = $reward->discount_value;
                break;
            case GamificationsReward::DISCOUNT_REDUCTION_PERCENT:
                $voucher->reduction_percent = $reward->discount_value;
        }
    }

    /**
     * Configure free shipping data from reward
     *
     * @param CartRule $voucher
     * @param GamificationsReward $reward
     */
    protected function configureFreeShipping(CartRule &$voucher, GamificationsReward $reward)
    {
        $voucher->free_shipping = true;
    }

    /**
     * Configure discount data from reward
     *
     * @param CartRule $voucher
     * @param GamificationsReward $reward
     */
    protected function configureGift(CartRule &$voucher, GamificationsReward $reward)
    {
        $product = new Product($reward->id_product);

        $voucher->gift_product = $product->id;
        $voucher->gift_product_attribute = $product->getDefaultIdProductAttribute();
    }
}
