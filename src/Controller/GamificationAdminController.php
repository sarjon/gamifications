<?php

/**
 * Class GamificationAdminController custom admin controller for module
 */
class GamificationAdminController extends ModuleAdminController
{
    public $bootstrap = true;

    /**
     * Initialize with custom methods
     */
    public function init()
    {
        $this->initOptions();
        $this->initList();
        $this->initForm();

        parent::init();
    }

    /**
     * Customized translations with default domain
     *
     * @param string $id
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    protected function trans($id, array $parameters = [], $domain = 'Modules.Gamification', $locale = null)
    {
        return parent::trans($id, $parameters, $domain, $locale);
    }

    /**
     * Init options
     */
    protected function initOptions()
    {
        //@todo: Override to initialize options
    }

    /**
     * Init list
     */
    protected function initList()
    {
        //@todo: Override to initialize list
    }

    /**
     * Init form
     */
    protected function initForm()
    {
        //@todo: Override to initialize form
    }
}
