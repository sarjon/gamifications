<?php

/**
 * Class GamificationsAdminController custom admin controller for module
 */
class GamificationsAdminController extends ModuleAdminController
{
    /**
     * @var Gamifications
     */
    public $module;

    /**
     * @var bool
     */
    public $bootstrap = true;

    /**
     * Initialize with custom methods
     */
    public function init()
    {
        $this->initOptions();
        $this->initList();
        $this->initForm();
        $this->initFieldsValue();

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
    protected function trans($id, array $parameters = [], $domain = 'Modules.Gamifications', $locale = null)
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

    /**
     * Init fields value
     */
    protected function initFieldsValue()
    {
        //@todo: Override to initialize fields value
    }
}
