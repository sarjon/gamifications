<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * GamificationsRewardHandler constructor.
     */
    public function __construct()
    {
        $context = Context::getContext();

        $this->context = $context;
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
    public function handleCustomerReward(
        GamificationsReward $reward,
        GamificationsCustomer $gamificationsCustomer,
        $activityType
    ) {
        $results = [];
        $results['success'] = false;

        $module = Module::getInstanceByName('gamifications');

        $points = 0;

        switch ((int) $reward->reward_type) {
            case GamificationsReward::REWARD_TYPE_POINTS:
                $points = $reward->points;
                $gamificationsCustomer->addPoints($points);
                $results['message'] = sprintf($module->l('You got %s points!'), $reward->points);
                $results['success'] = true;
                break;
            case GamificationsReward::REWARD_TYPE_RANDOM_AMOUNT_OF_POINTS:
                $min = $reward->points - $reward->radius;
                $max = $reward->points + $reward->radius;
                $points = rand($min, $max);
                $gamificationsCustomer->addPoints($points);
                $results['message'] = sprintf($module->l('You got %s points!'), $points);
                $results['success'] = true;
                break;
            case GamificationsReward::REWARD_TYPE_DISCOUNT:
            case GamificationsReward::REWARD_TYPE_FREE_SHIPPING:
            case GamificationsReward::REWARD_TYPE_GIFT:
                $this->createVoucher($reward, $gamificationsCustomer);
                $results['message'] = sprintf($module->l('You got %s!'), $reward->name[$this->context->language->id]);
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
        $voucher->code = Tools::passwdGen();

        $rewardType = (int) $reward->reward_type;

        if (GamificationsReward::REWARD_TYPE_DISCOUNT == $rewardType) {
            $this->configureDiscount($voucher, $reward);
        } elseif (GamificationsReward::REWARD_TYPE_FREE_SHIPPING == $rewardType) {
            $this->configureFreeShipping($voucher);
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
     */
    protected function configureFreeShipping(CartRule &$voucher)
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
