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
     * GamificationInstaller constructor.
     *
     * @param Gamification $module
     */
    public function __construct(Gamification $module)
    {
        $this->module = $module;
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
            ]
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
