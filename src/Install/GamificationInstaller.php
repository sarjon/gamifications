<?php

/**
 * Class GamificationInstaller
 */
class GamificationInstaller extends AbstractGamificationInstaller
{
    /**
     * @var Gamification
     */
    private $module;

    /**
     * @var GamificationDbInstaller
     */
    private $dbInstaller;

    /**
     * GamificationInstaller constructor.
     *
     * @param Gamification $module
     */
    public function __construct(Gamification $module)
    {
        $this->module = $module;
        $this->dbInstaller = new GamificationDbInstaller($module);
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
                'name' => $this->module->getTranslator()->trans('Gamification', [], 'Modules.Gamification'),
                'parent' => 'IMPROVE',
                'class_name' => Gamification::ADMIN_GAMIFICATION_MODULE_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Preferences', [], 'Modules.Gamification'),
                'parent' => Gamification::ADMIN_GAMIFICATION_MODULE_CONTROLLER,
                'class_name' => Gamification::ADMIN_GAMIFICATION_PREFERENCE_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Rewards', [], 'Modules.Gamification'),
                'parent' => Gamification::ADMIN_GAMIFICATION_MODULE_CONTROLLER,
                'class_name' => Gamification::ADMIN_GAMIFICATION_REWARD_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Challanges', [], 'Modules.Gamification'),
                'parent' => Gamification::ADMIN_GAMIFICATION_MODULE_CONTROLLER,
                'class_name' => Gamification::ADMIN_GAMIFICATION_CHALLANGE_CONTROLLER,
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
        ];

        foreach ($hooks as $hookName) {
            if (!$this->module->registerHook($hookName)) {
                return false;
            }
        }

        return true;
    }
}
