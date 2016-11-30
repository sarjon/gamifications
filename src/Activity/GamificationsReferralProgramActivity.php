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
     * Reward referral customer when new customer reaches configured order state
     *
     * @param Order $order
     */
    public function rewardReferralCustomer(Order $order)
    {
        //@todo: test
        $referralRewardTime = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD_TIME);

        if (GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION != $referralRewardTime) {
            return;
        }

        $newCustomerOrderStates = Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_ORDER_STATES);
        $newCustomerOrderStates = json_decode($newCustomerOrderStates);

        if (!in_array($order->current_state, $newCustomerOrderStates)) {
            return;
        }

        $context = Context::getContext();
        $idNewCustomer = (int) $order->id_customer;

        /** @var GamificationsReferralRepository $referralRepository */
        $referralRepository = $this->em->getRepository('GamificationsReferral');
        $idReferralCustomer = $referralRepository->findReferralCustomerId($idNewCustomer, $context->shop->id);

        if (null === $idReferralCustomer) {
            return;
        }

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository = $this->em->getRepository('GamificationsCustomer');
        $idGamificationsCustomer = $customerRepository->findIdByCustomerId($idReferralCustomer, $context->shop->id);

        $gamificationsCustomer = new GamificationsCustomer($idGamificationsCustomer);

        $idReferralReward = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD);
        $reward = new GamificationsReward($idReferralReward);

        $rewardHandler = new GamificationsRewardHandler();
        $rewardHandler
            ->handleCustomerReward($reward, $gamificationsCustomer, GamificationsActivity::TYPE_REFERRAL_PROGRAM);
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
        $translator = $context->getTranslator();

        $context->controller->success[] = $translator->trans(
            'You received a referral program reward! To check it out, go to your account page',
            [],
            'Modules.Gamifications.Shop'
        );
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
