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
     * @var bool If true, then help will be display in forms
     */
    protected $displayHelpInForm = false;

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
        $isDisplayExpalanationsOn = (bool) Configuration::get(GamificationsConfig::DISPLAY_HELP);

        if ($isDisplayExpalanationsOn && (!in_array($this->display, ['add', 'edit']) || $this->displayHelpInForm)) {
            $this->content .= $this->displayHelp();
        }

        parent::initContent();
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
     * Display any kind of information if DISPLAY_HELP option is enabled
     *
     * @return string
     */
    protected function displayHelp()
    {
        return '';
    }
}
