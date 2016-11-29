<?php
use PrestaShop\PrestaShop\Core\Foundation\Database\EntityManager;

/**
 * Class GamificationsReferralProgramActivity
 */
class GamificationsReferralProgramActivity
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GamificationsReferralProgramActivity constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Handle referral program logic
     *
     * @param Customer $invitedCustomer
     * @param string $referralCode
     */
    public function play(Customer $invitedCustomer, $referralCode)
    {
        $context = Context::getContext();

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository = $this->em->getRepository('GamificationsCustomer');
        $referralCustomerData = $customerRepository->findByReferralCode($referralCode, $context->shop->id);

        if (null === $referralCustomerData) {
            return;
        }

        $referralGamificationsCustomer = new GamificationsCustomer($referralCustomerData['id_gamifications_customer']);
        $invitedGamificationsCustomer = GamificationsCustomer::create($invitedCustomer, true);

        $referral = new GamificationsReferral();
        $referral->id_invited_customer = (int) $invitedGamificationsCustomer->id_customer;
        $referral->id_referral_customer = (int) $referralGamificationsCustomer->id_customer;
        $referral->id_shop = (int) $context->shop->id;
        $referral->active = true;

        $referral->save();

        $this->handleInvitedCustomerReward($invitedGamificationsCustomer);
        $this->handleReferalCustomerReward($referralGamificationsCustomer);
    }

    /**
     * Reward invited customer
     *
     * @param GamificationsCustomer $customer
     */
    protected function handleInvitedCustomerReward(GamificationsCustomer $customer)
    {
        $isInvitedCustomerRewardEnabled =
            (bool) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED);

        if (!$isInvitedCustomerRewardEnabled) {
            return;
        }

        $idInvitedCustomerReward = (int) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD);
        $reward = new GamificationsReward($idInvitedCustomerReward);

        if (!Validate::isLoadedObject($reward)) {
            return;
        }

        $rewardHandler = new GamificationsRewardHandler();
        $rewardHandler->handleCustomerReward($reward, $customer, GamificationsActivity::TYPE_REFERRAL_PROGRAM);

        $context = Context::getContext();

        //@todo: fix message and add translation
        $context->controller->success[] = 'You got Referral program reward! Check your account now!';
    }

    /**
     * Reward referral customer
     *
     * @param GamificationsCustomer $customer
     */
    protected function handleReferalCustomerReward(GamificationsCustomer $customer)
    {
        $referralRewardTime = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD_TIME);

        if (GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION != $referralRewardTime) {
            return;
        }

        $idReferralCustomerReward = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD);
        $reward = new GamificationsReward($idReferralCustomerReward);

        if (!Validate::isLoadedObject($reward)) {
            return;
        }

        $rewardHandler = new GamificationsRewardHandler();
        $rewardHandler->handleCustomerReward($reward, $customer, GamificationsActivity::TYPE_REFERRAL_PROGRAM);
    }
}
