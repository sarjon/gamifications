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
 * Class Gamifications
 */
class Gamifications extends Module
{
    /**
     * Module admin controllers
     */
    const ADMIN_GAMIFICATIONS_MODULE_CONTROLLER           = 'AdminGamificationsModule';
    const ADMIN_GAMIFICATIONS_PREFERENCE_CONTROLLER       = 'AdminGamificationsPreference';
    const ADMIN_GAMIFICATIONS_REWARD_CONTROLLER           = 'AdminGamificationsReward';
    const ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER         = 'AdminGamificationsActivity';
    const ADMIN_GAMIFICATIONS_DAILY_REWARDS_CONTROLLER    = 'AdminGamificationsDailyRewards';
    const ADMIN_GAMIFICATIONS_POINT_EXCHANGE_CONTROLLER   = 'AdminGamificationsPointExchange';
    const ADMIN_GAMIFICATIONS_STATS_CONTROLLER            = 'AdminGamificationsStats';
    const ADMIN_GAMIFICATIONS_CUSTOMER_CONTROLLER         = 'AdminGamificationsCustomer';
    const ADMIN_GAMIFICATIONS_ACTIVITY_HISTORY_CONTROLLER = 'AdminGamificationsActivityHistory';
    const ADMIN_GAMIFICATIONS_REFERRAL_CONTROLLER         = 'AdminGamificationsReferral';
    const ADMIN_GAMIFICATIONS_SHOPPING_POINT_CONTROLLER   = 'AdminGamificationsShoppingPoint';
    const ADMIN_GAMIFICATIONS_RANKING_CONTROLLER          = 'AdminGamificationsRanking';

    /**
     * Module front controllers
     */
    const FRONT_LOYALITY_CONTROLLER = 'loyality';
    const FRONT_EXCHANGE_POINTS_CONTROLLER = 'exchangepoints';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Gamification constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->name = 'gamifications';
        $this->author = 'Šarūnas Jonušas';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->need_instance = 0;
        $this->controllers = [self::FRONT_LOYALITY_CONTROLLER, self::FRONT_EXCHANGE_POINTS_CONTROLLER];

        parent::__construct();

        $this->requireAutoloader();

        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];
        $this->displayName = $this->l('Gamification: Customers loyalty program');
        $this->description = $this->l('Increase customers loyality by adding various activities to your shop!').' '.
            $this->l('Daily rewards, referral program, shopping points, gifts & more!');

        $this->em = $em;
    }

    /**
     * Process module installation
     *
     * @return bool
     */
    public function install()
    {
        $installer = new GamificationsInstaller($this);

        if (!parent::install() || !$installer->install()) {
            return false;
        }

        return true;
    }

    /**
     * Process module uninstall
     *
     * @return bool
     */
    public function uninstall()
    {
        $installer = new GamificationsInstaller($this);

        if (!$installer->uninstall() || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    /**
     * Redirect to Preference controller
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink(self::ADMIN_GAMIFICATIONS_PREFERENCE_CONTROLLER));
    }

    /**
     * Get entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Get context
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Display gamification page url in my-account page
     *
     * @return string
     */
    public function hookDisplayCustomerAccount()
    {
        $frontOfficeTitle = Configuration::get(GamificationsConfig::FRONT_OFFICE_TITLE, $this->context->language->id);

        $params = [
            'front_office_title' => $frontOfficeTitle,
        ];

        return $this->render('hook/displayCustomerAccount.tpl', $params);
    }

    /**
     * Handle customer referral
     *
     * @param array $params
     */
    public function hookActionObjectCustomerAddAfter(array $params)
    {
        if (!Tools::isSubmit('referral_code')) {
            return;
        }

        $isReferralProgramEnabled = (bool) Configuration::get(GamificationsConfig::REFERRAL_PROGRAM_STATUS);
        if (!$isReferralProgramEnabled) {
            return;
        }

        /** @var Customer $invitedCustomer */
        $invitedCustomer = $params['object'];

        $referralCode = Tools::getValue('referral_code');

        $referralProgramActivity = new GamificationsReferralProgramActivity($this->getEntityManager());
        $referralProgramActivity->processReferralProgram($invitedCustomer, $referralCode);
    }

    /**
     * Reward referral if order is valid
     *
     * @param array $params
     */
    public function hookActionObjectOrderAddAfter(array $params)
    {
        /** @var Order $order */
        $order = $params['object'];

        $this->processReferralProgramActivity($order);
        $this->processShoppingPoints($order, true);
    }

    /**
     * Reward referral if order is valid
     *
     * @param array $params
     */
    public function hookActionObjectOrderUpdateAfter(array $params)
    {
        /** @var Order $order */
        $order = $params['object'];

        $this->processReferralProgramActivity($order);
        $this->processShoppingPoints($order);
    }

    /**
     * Delete gamifications customer on customer delete
     *
     * @param array $params
     */
    public function hookActionObjectCustomerDeleteAfter(array $params)
    {
        /** @var Customer $customer */
        $customer = $params['object'];

        GamificationsCustomer::remove($customer);
    }

    /**
     * Display how many points will be earned
     *
     * @return string
     */
    public function hookDisplayReassurance()
    {
        $isShoppingPointsEnabled = (bool) Configuration::get(GamificationsConfig::SHOPPING_POINTS_STATUS);

        if (!$isShoppingPointsEnabled) {
            return '';
        }

        $shoppingPointActivity = new GamificationsShoppingPointActivity($this->getEntityManager());
        $possiblePoints = $shoppingPointActivity->calculatePossiblePoints();

        return $this->render('hook/displayReassurance.tpl', ['possible_points' => $possiblePoints]);
    }

    /**
     * Process referral program activity
     *
     * @param Order $order
     */
    protected function processReferralProgramActivity(Order $order)
    {
        $isReferralProgramEnabled = (bool) Configuration::get(GamificationsConfig::REFERRAL_PROGRAM_STATUS);

        if (!$isReferralProgramEnabled) {
            return;
        }

        $referralProgramActivity = new GamificationsReferralProgramActivity($this->getEntityManager());
        $referralProgramActivity->processReferralCustomerReward($order);
    }

    /**
     * Process Shopping Points activity
     *
     * @param Order $order
     * @param bool $createObject
     */
    protected function processShoppingPoints(Order $order, $createObject = false)
    {
        static $hasProcessed;

        $orderKey = sprintf('processShoppingPoints_order_%s', $order->id);

        if (isset($hasProcessed[$orderKey])) {
            return;
        }

        $isShoppingPointsEnabled = (bool) Configuration::get(GamificationsConfig::SHOPPING_POINTS_STATUS);

        if (!$isShoppingPointsEnabled) {
            return;
        }

        $shoppingPointActivity = new GamificationsShoppingPointActivity($this->getEntityManager());
        $shoppingPointActivity->processOrder($order, $createObject);

        $hasProcessed[$orderKey] = true;
    }

    /**
     * Render template
     *
     * @param string $path
     * @param array $params
     *
     * @return string
     */
    protected function render($path, array $params = [])
    {
        if (!empty($params)) {
            $this->context->smarty->assign($params);
        }

        $template = sprintf('module:%s/views/templates/'.$path, $this->name);

        return $this->context->smarty->fetch($template);
    }

    /**
     * Require autoloader
     */
    private function requireAutoloader()
    {
        require_once $this->getLocalPath().'vendor/autoload.php';
    }
}
