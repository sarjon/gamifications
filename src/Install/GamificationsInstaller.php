<?php

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
                'name' => $this->module->getTranslator()->trans('Gamification', [], 'Modules.Gamifications'),
                'parent' => 'IMPROVE',
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Preferences', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_PREFERENCE_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Rewards', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_REWARD_CONTROLLER,
            ],
            [
                'name' => $this->module->getTranslator()->trans('Challanges', [], 'Modules.Gamifications'),
                'parent' => Gamifications::ADMIN_GAMIFICATIONS_MODULE_CONTROLLER,
                'class_name' => Gamifications::ADMIN_GAMIFICATIONS_CHALLANGE_CONTROLLER,
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
