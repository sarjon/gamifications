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
 * Class AbstractGamificationsInstaller
 */
abstract class AbstractGamificationsInstaller
{
    /**
     * Get module name
     *
     * @return string
     */
    abstract protected function getModuleName();

    /**
     * Definition of tabs to install
     *
     * @return array
     */
    abstract protected function tabs();

    /**
     * Installs/uninstalls all tabs defined in tabs() method
     *
     * @return bool
     */
    protected function installTabs()
    {
        $tabs = $this->tabs();

        if (empty($tabs) && !is_array($tabs)) {
            return true;
        }

        foreach ($tabs as $tab) {
            $parentId = (int) Tab::getIdFromClassName($tab['parent']);
            $icon = isset($tab['icon']) ? $tab['icon'] : null;

            if (!$this->installTab($tab['name'], $parentId, $tab['class_name'], $icon)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uninstalls all tabs defined in tabs() method
     *
     * @return bool
     */
    protected function uninstallTabs()
    {
        $tabs = $this->tabs();

        if (empty($tabs) && !is_array($tabs)) {
            return true;
        }

        foreach ($tabs as $tab) {
            if (!$this->uninstallTab($tab['class_name'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Install single tab
     *
     * @param string $name Tab name
     * @param int $parentId Tab parent id
     * @param string $className Tab class name
     * @param string|null $icon
     *
     * @return bool
     */
    private function installTab($name, $parentId, $className, $icon = null)
    {
        if (!Tab::getIdFromClassName($className)) {
            $moduleTab = new Tab();
            $languages = Language::getLanguages(true);

            foreach ($languages as $language) {
                $moduleTab->name[$language['id_lang']] = $name;
            }

            $moduleTab->class_name = $className;
            $moduleTab->id_parent = $parentId;
            $moduleTab->module = $this->getModuleName();
            $moduleTab->icon = $icon;

            if (!$moduleTab->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uninstall single tab by name
     *
     * @param string $className Tab class name
     *
     * @return bool
     */
    private function uninstallTab($className)
    {
        if ($tabId = (int) Tab::getIdFromClassName($className)) {
            $tab = new Tab($tabId);
            return $tab->delete();
        }

        return true;
    }
}
