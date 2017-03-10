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
 * Class GamificationsInstaller
 */
class GamificationsInstaller extends AbstractGamificationsInstaller
{
    /**
     * @var Gamifications
     */
    private $module;

    /**
     * @var GamificationsDbInstaller
     */
    private $dbInstaller;

    /**
     * GamificationInstaller constructor.
     *
     * @param Gamifications $module
     */
    public function __construct(Gamifications $module)
    {
        $this->module = $module;
        $this->dbInstaller = new GamificationsDbInstaller($module);
    }

    /**
     * Install module (register hooks, install tabs & etc)
     *
     * @return bool
     */
    public function install()
    {
        if (!$this->registerHooks()) {
            return false;
        }

        if (!$this->installTabs()) {
            return false;
        }

        if (!$this->dbInstaller->install()) {
            return false;
        }

        if (!$this->installConfiguration()) {
            return false;
        }

        return true;
    }

    /**
     * Uninstall module
     *
     * @return bool
     */
    public function uninstall()
    {
        if (!$this->uninstallTabs()) {
            return false;
        }

        if (!$this->dbInstaller->uninstall()) {
            return false;
        }

        if (!$this->uninstallConfiguration()) {
            return false;
        }

        return true;
    }

    /**
     * Get module name
     *
     * @return string
     */
    protected function getModuleName()
    {
        return $this->module->name;
    }

    /**
     * Definition of tabs to install
     *
     * @return array
     */
    protected function tabs()
    {
        return [
            [
                'name' => $this->module->l('Gamifications', __CLASS__),
                'parent' => 'IMPROVE',
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'icon' => 'loyalty',
            ],
            [
                'name' => $this->module->l('Rewards', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_REWARD_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Points exchange', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_POINT_EXCHANGE_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Loyalty', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Daily rewards', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_DAILY_REWARDS_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Referral program', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_REFERRAL_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Shopping points', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_SHOPPING_POINT_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Statistics', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_STATS_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Customers', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_STATS_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_CUSTOMER_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Activities history', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_STATS_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_ACTIVITY_HISTORY_CONTROLLER,
            ],
            [
                'name' => $this->module->l('Preferences', __CLASS__),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_PREFERENCE_CONTROLLER,
            ],
        ];
    }

    /**
     * Register module hooks
     *
     * @return bool
     */
    private function registerHooks()
    {
        $hooks = [
            'moduleRoutes',
            'displayCustomerAccount',
            'actionObjectCustomerAddAfter',
            'actionObjectOrderAddAfter',
            'actionObjectOrderUpdateAfter',
            'displayReassurance',
            'actionObjectCustomerDeleteAfter',
        ];

        foreach ($hooks as $hookName) {
            if (!$this->module->registerHook($hookName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Install default configuration
     *
     * @return bool
     */
    private function installConfiguration()
    {
        $configuration = GamificationsConfig::getDefaultConfiguration();

        foreach ($configuration as $name => $value) {
            if (!Configuration::updateValue($name, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uninstall configuration
     *
     * @return bool
     */
    private function uninstallConfiguration()
    {
        $configuration = array_keys(GamificationsConfig::getDefaultConfiguration());

        foreach ($configuration as $name) {
            if (!Configuration::deleteByName($name)) {
                return false;
            }
        }

        return true;
    }
}
