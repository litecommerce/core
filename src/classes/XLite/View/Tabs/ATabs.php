<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Tabs;

/**
 * ATabs is a component allowing you to display multiple widgets as tabs depending
 * on their targets
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ATabs extends \XLite\View\AView
{
    /**
     * Information on tab widgets and their targets defined as an array(tab) descriptions:
     *
     *      array(
     *          $target => array(
     *              'title' => $tabTitle,
     *              'widget' => $optionalWidgetClass,
     *              'template' => $optionalWidgetTemplate,
     *          ),
     *          ...
     *      );
     *
     * If a widget class is not specified for a target, the ATabs descendant will be used as the widget class.
     * If a template is not specified for a target, it will be used from the tab widget class.
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $tabs = array();

    /**
     * Cached result of the getTabs() method
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $processedTabs = null;

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $tab  = $this->getSelectedTab();

        if (!empty($tab['jsFiles'])) {
            if (is_array($tab['jsFiles'])) {
                $list = array_merge($list, $tab['jsFiles']);

            } else {
                $list[] = $tab['jsFiles'];
            }
        }

        return $list;
    }

    /**
     * Checks whether no widget class is specified for the selected tab
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isTemplateOnlyTab()
    {
        $tab = $this->getSelectedTab();

        return isset($tab) && empty($tab['widget']) && !empty($tab['template']);
    }

    /**
     * Checks whether both a template and a widget class are specified for the selected tab
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isFullWidgetTab()
    {
        $tab = $this->getSelectedTab();

        return isset($tab) && !empty($tab['widget']) && !empty($tab['template']);
    }

    /**
     * Checks whether no template is specified for the selected tab
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isWidgetOnlyTab()
    {
        $tab = $this->getSelectedTab();

        return isset($tab) && !empty($tab['widget']) && empty($tab['template']);
    }

    /**
     * Checks whether no template is specified for the selected tab
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCommonTab()
    {
        $tab = $this->getSelectedTab();

        return isset($tab) && empty($tab['widget']) && empty($tab['template']);
    }

    /**
     * Returns a widget class name for the selected tab
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTabWidget()
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getSelectedTab(), 'widget');
    }

    /**
     * Returns a template name for the selected tab
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTabTemplate()
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getSelectedTab(), 'template');
    }

    /**
     * Flag: display (true) or hide (false) tabs
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isWrapperVisible()
    {
        return true;
    }

    /**
     * Returns the default widget template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/tabs.tpl';
    }

    /**
     * Returns the current target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrentTarget()
    {
        return \XLite\Core\Request::getInstance()->target;
    }

    /**
     * Returns a list of targets for which the tabs are visible
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTabTargets()
    {
        return array_keys($this->tabs);
    }

    /**
     * Checks whether the widget is visible, or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && in_array($this->getCurrentTarget(), $this->getTabTargets());
    }

    /**
     * Returns tab URL
     *
     * @param string $target Tab target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function buildTabURL($target)
    {
        return $this->buildURL($target);
    }

    /**
     * Checks whether a tab is selected
     *
     * @param mixed $target Tab target
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSelectedTab($target)
    {
        return $target === $this->getCurrentTarget();
    }

    /**
     * Returns default values for a tab description
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTabValues()
    {
        return array(
            'title'    => '',
            'widget'   => '',
            'template' => '',
        );
    }

    /**
     * Returns an array(tab) descriptions
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTabs()
    {
        // Process tabs only once
        if (!isset($this->processedTabs)) {
            $this->processedTabs = array();
            $defaultValues = $this->getDefaultTabValues();

            foreach ($this->tabs as $target => $tab) {
                $tab['selected'] = $this->isSelectedTab($target, $tab);
                $tab['url'] = $this->buildTabURL($target, $tab);

                // Set default values for missing tab parameters
                $tab += $defaultValues;

                $this->processedTabs[$target] = $tab;
            }
        }

        return $this->processedTabs;
    }

    /**
     * getTitle
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTitle()
    {
        return null;
    }

    /**
     * Returns a description of the selected tab. If no tab is selected, returns NULL.
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSelectedTab()
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getTabs(), $this->getCurrentTarget());
    }
}
