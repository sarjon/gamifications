<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class GamificationsAdminController custom admin controller for module
 */
abstract class GamificationsAdminController extends ModuleAdminController
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
     * Custom init
     */
    public function init()
    {
        $this->initList();
        $this->initOptions();

        parent::init();
    }

    /**
     * Init form before rendering
     *
     * @return string
     */
    public function renderForm()
    {
        $this->initForm();
        $this->initFormFieldsValue();

        return parent::renderForm();
    }

    /**
     * Display additional data in content
     */
    public function initContent()
    {
        $isDisplayExpalanationsOn = (bool) Configuration::get(GamificationsConfig::DISPLAY_EXPLANATIONS);

        if ($isDisplayExpalanationsOn && !in_array($this->display, ['add', 'edit'])) {
            $this->content .= $this->displayHelp();
        }

        parent::initContent();
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
    protected function initFormFieldsValue()
    {
        //@todo: Override to initialize fields value
    }

    /**
     * Display any kind of information if DISPLAY_EXPLANATIONS option is enabled
     *
     * @return string
     */
    protected function displayHelp()
    {
        return '';
    }
}
