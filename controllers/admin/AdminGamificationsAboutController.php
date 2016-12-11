<?php
/**
 * This file is part of the Gamifications module.
 *
 * @author    Sarunas Jonusas, <jonusas.sarunas@gmail.com>
 * @copyright Copyright (c) permanent, Sarunas Jonusas
 * @license   Addons PrestaShop license limitation
 */

/**
 * Class AdminGamificationsAboutController
 */
class AdminGamificationsAboutController extends GamificationsAdminController
{
    /**
     * Custom content
     */
    public function initContent()
    {
        $this->content .= $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/about.tpl'
        );

        parent::initContent();
    }
}
