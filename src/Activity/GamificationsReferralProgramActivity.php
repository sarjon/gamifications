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
    public function processReferralProgram(Customer $invitedCustomer, $referralCode)
    {
        $context = Context::getContext();

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository   = $this->em->getRepository('GamificationsCustomer');
        $referralCustomerData = $customerRepository->findByReferralCode($referralCode, $context->shop->id);

        if (null === $referralCustomerData) {
            return;
        }

        $referralGamificationsCustomer = new GamificationsCustomer($referralCustomerData['id_gamifications_customer']);
        $invitedGamificationsCustomer  = GamificationsCustomer::create($invitedCustomer, true);

        $referral                       = new GamificationsReferral();
        $referral->id_invited_customer  = (int) $invitedGamificationsCustomer->id_customer;
        $referral->id_referral_customer = (int) $referralGamificationsCustomer->id_customer;
        $referral->id_shop              = (int) $context->shop->id;
        $referral->active               = true;

        $referralRewardTime = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD_TIME);
        $isInvitedCustomerRewardEnabled =
            (bool) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD_ENABLED);

        if ($isInvitedCustomerRewardEnabled) {
            $this->handleInvitedCustomerReward($invitedGamificationsCustomer);
        }

        if (GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_REGISTRATION == $referralRewardTime) {
            $this->handleReferralCustomerReward($referralGamificationsCustomer);

            $referral->active = false;
        }

        $referral->save();
    }

    /**
     * Reward referral customer when new customer reaches configured order state
     *
     * @param Order $order
     */
    public function processReferralCustomerReward(Order $order)
    {
        $referralRewardTime = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD_TIME);

        if (GamificationsActivity::REFERRAL_REWARD_ON_NEW_CUSTOMER_ORDER != $referralRewardTime) {
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

        $idReferralObject =
            $referralRepository->findReferralObjectIdByNewCustomerId($idNewCustomer, $context->shop->id);

        if (null === $idReferralObject) {
            return;
        }

        $referral = new GamificationsReferral($idReferralObject);

        /** @var GamificationsCustomerRepository $customerRepository */
        $customerRepository = $this->em->getRepository('GamificationsCustomer');
        $idGamificationsCustomer =
            $customerRepository->findIdByCustomerId($referral->id_referral_customer, $context->shop->id);

        $gamificationsCustomer = new GamificationsCustomer($idGamificationsCustomer);

        if (!$this->handleReferralCustomerReward($gamificationsCustomer)) {
            return;
        }

        $referral->active = false;
        $referral->save();
    }
    
    /**
     * Reward invited customer
     *
     * @param GamificationsCustomer $customer
     *
     * @return bool
     */
    protected function handleInvitedCustomerReward(GamificationsCustomer $customer)
    {
        $idInvitedCustomerReward = (int) Configuration::get(GamificationsConfig::REFERRAL_NEW_CUSTOMER_REWARD);
        $reward = new GamificationsReward($idInvitedCustomerReward);

        if (!Validate::isLoadedObject($reward)) {
            return false;
        }

        $rewardHandler = new GamificationsRewardHandler();
        $response =
            $rewardHandler->handleCustomerReward($reward, $customer, GamificationsActivity::TYPE_REFERRAL_PROGRAM);

        if (!$response['success']) {
            return false;
        }

        $context = Context::getContext();

        $module = Module::getInstanceByName('gamifications');

        $context->controller->success[] =
            $module->l('You received a referral program reward! To check it out, go to your account page', __CLASS__);

        return true;
    }

    /**
     * Reward referral customer
     *
     * @param GamificationsCustomer $customer
     *
     * @return bool
     */
    protected function handleReferralCustomerReward(GamificationsCustomer $customer)
    {
        $idReferralCustomerReward = (int) Configuration::get(GamificationsConfig::REFERRAL_REWARD);
        $reward = new GamificationsReward($idReferralCustomerReward);

        if (!Validate::isLoadedObject($reward)) {
            return false;
        }

        $rewardHandler = new GamificationsRewardHandler();
        $response =
            $rewardHandler->handleCustomerReward($reward, $customer, GamificationsActivity::TYPE_REFERRAL_PROGRAM);

        return (bool) $response['success'];
    }
}
