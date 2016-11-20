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
     * 
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
        $this->translator = $context->getTranslator();
    }

    public function getDailyReward()
    {

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
                $this->createDiscount($reward);
                $results['message'] = $this->translator->trans('You got', [], 'Modules.Gamifications.Shop');
                $results['message'] .= ' '.$reward->name[$this->context->language->id];
                $results['success'] = true;
                break;
        }

        if ($results['success']) {
            $activityHistory = new GamificationsActivityHistory();
            $activityHistory->id_customer = (int) $this->context->customer->id;
            $activityHistory->id_reward = (int) $reward->id;
            $activityHistory->id_shop = (int) $this->context->shop->id;
            $activityHistory->reward_type = (int) $reward->reward_type;
            $activityHistory->activity_type = (int) $activityType;
            $activityHistory->points = (int) $points;
            $activityHistory->save();
        }

        return $results;
    }

    /**
     * Create discount for customer
     *
     * @param GamificationsReward $reward
     *
     * @return bool
     */
    protected function createDiscount(GamificationsReward $reward)
    {
        $defaultCurrencyId = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $validFrom = new DateTime();
        $validTo = new DateTime();;
        $validTo = $validTo->add(new DateInterval(sprintf('P%dD', (int) $reward->discount_valid_days)));

        $discount = new CartRule();

        $discount->id_customer = $this->context->customer->id;
        $discount->active = true;
        $discount->date_from = $validFrom->format('Y-m-d H:i:s');
        $discount->date_to = $validTo->format('Y-m-d H:i:s');
        $discount->name = $reward->name;
        $discount->partial_use = false;
        $discount->priority = 1;
        $discount->quantity_per_user = 1;
        $discount->quantity = 1;
        $discount->minimum_amount = $reward->minimum_cart_amount;
        $discount->minimum_amount_currency = $defaultCurrencyId;

        switch ($reward->discount_reduction_type) {
            case GamificationsReward::DISCOUNT_REDUCTION_AMOUNT:
                $discount->reduction_amount = $reward->discount_value;
                $discount->reduction_currency = $defaultCurrencyId;
                $discount->reduction_tax = false;
                break;
            case GamificationsReward::DISCOUNT_REDUCTION_PERCENT:
                $discount->reduction_percent = $reward->discount_value;
        }

        if ($reward->discount_apply_type = GamificationsReward::DISCOUNT_TYPE_CODE) {
            $discount->code = Tools::passwdGen();
        }

        return $discount->save();
    }
}
