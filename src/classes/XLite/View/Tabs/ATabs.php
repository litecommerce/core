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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Tabs;

/**
 * ATabs is a component allowing you to display multiple widgets as tabs depending
 * on their targets
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ATabs extends \XLite\View\AView
{

    /**
     * Information on tab widgets and their targets defined as an array of tab descriptions:
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
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $tabs = array();


    /**
     * Cached result of the getTabs() method
     * 
     * @var    array
     * @access private
     * @see    ____var_see____
     * @since  3.0.0
     */
    private $processedTabs = null;

    /**
     * Extra information on tabs calculated before displaying the tabs
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $tabsInfo = array();

    /**
     * Returns the default widget template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/tabs.tpl';
    }

    /**
     * Returns the current target
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentTarget()
    {
        return \XLite\Core\Request::getInstance()->target;
    }

    /**
     * Returns a list of targets for which the tabs are visible  
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTabTargets()
    {
        return array_keys($this->tabs);
    }

    /**
     * Checks whether the widget is visible, or not
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && in_array($this->getCurrentTarget(), $this->getTabTargets());
    }


    /**
     * Returns an URL to a tab
     * 
     * @param string $target Tab target
     * @param array  $tab    Tab description (see $this->tabs)
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function buildTabURL($target, $tab)
    {
        return $this->buildURL($target);
    }

    /**
     * Checks whether a tab is selected
     * 
     * @param mixed $target Tab target
     * @param mixed $tab    Tab description (see $this->tabs)
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isSelectedTab($target, $tab)
    {
        return ($target === $this->getCurrentTarget());
    }


    /**
     * Returns default values for a tab description
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTabValues()
    {
        return array('title'=>'', 'widget'=>'', 'template'=>'');
    }

    
    /**
     * Returns an array of tab descriptions
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTabs()
    {
        // Process tabs only once
        if (is_null($this->processedTabs)) {

            $this->processedTabs = array();

            $defaultValues = $this->getDefaultTabValues();

            foreach ($this->tabs as $target=>$tab) {

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
     * Returns a description of the selected tab. If no tab is selected, returns NULL.
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSelectedTab()
    {
        $tabs = $this->getTabs();
        $target = $this->getCurrentTarget();
        
        return (isset($tabs[$target]) ? $tabs[$target] : null);
    }

    /**
     * Checks whether no template is specified for the selected tab
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isWidgetOnlyTab()
    {
        $tab = $this->getSelectedTab();

        if (!is_null($tab)) {
            return !empty($tab['widget']) && empty($tab['template']);
        } else {
            return false;
        }
    }

    /**
     * Checks whether no widget class is specified for the selected tab
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTemplateOnlyTab()
    {
        $tab = $this->getSelectedTab();

        if (!is_null($tab)) {
            return empty($tab['widget']) && !empty($tab['template']);
        } else {
            return false;
        }
    }

    /**
     * Checks whether both a template and a widget class are specified for the selected tab
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isFullWidgetTab()
    {
        $tab = $this->getSelectedTab();

        if (!is_null($tab)) {
            return !empty($tab['widget']) && !empty($tab['template']);
        } else {
            return false;
        }
    }

    /**
     * Returns a widget class name for the selected tab
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTabWidget()
    {
        $tab = $this->getSelectedTab();

        return isset($tab['widget']) ? $tab['widget'] : '';
    }

    /**
     * Returns a template name for the selected tab
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTabTemplate()
    {
        $tab = $this->getSelectedTab();

        return isset($tab['template']) ? $tab['template'] : '';
    }

    /**
     * Returns tabs splitted into multiple chunks
     * 
     * @param integer $splitParameter Maximum number of characters per a line with tab titles
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSplittedTabs($splitParameter = 0)
    {
        $pages = $this->getTabs();

        $pagesTitlesTotalLength = 0;

        foreach ($pages as $page) {
            $pagesTitlesTotalLength += strlen($page['title']);
        }

        // Split tabs into {$splitParameter} arrays

          $splitParameter = intval($splitParameter);

           $pagesCurrentLength = 0;
        $pagesNumber = 0;

        foreach ($pages as $page) {

            $pagesCurrentLength += strlen($page['title']);

            if ($pagesCurrentLength > $splitParameter) {
                break;
            }

            $pagesNumber++;

        }

        $splitParameter = $pagesNumber;

        $pages = $this->split($pages, (1 < $splitParameter ? $splitParameter : 1));
        krsort($pages);

        $pagesTitlesLengthMax = 0;

        foreach ($pages as $pageIdx => $pagesArray) {

            $pagesTitlesLength = 0;

            foreach ($pagesArray as $page) {

                $pagesTitlesLength += (is_null($page) ? 0 : strlen($page['title']));

                if ($pagesTitlesLength > $pagesTitlesLengthMax) {
                    $pagesTitlesLengthMax = $pagesTitlesLength;
                }
            }

            $this->tabsInfo[$pageIdx] = array(
                'titlesLength' => $pagesTitlesLength,
                'titlesLengthMax' => 0,
                'titlesFullness' => 0,
            );
        }

        foreach ($this->tabsInfo as $pageIdx => $pagesInfo) {
            $tab = &$this->tabsInfo[$pageIdx];
            $tab['titlesLengthMax'] = $pagesTitlesLengthMax;
            $tab['titlesFullness'] = ceil($tab['titlesLength'] * 100 / $tab['titlesLengthMax']);
        }

        return $pages;
    }

    /**
     * Checks whether a title is wider than the specified percent of total length of all titles
     * 
     * @param string $pageIdx       Page identificator (key in the $pages array)
     * @param int    $widthPercents Percent value
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isTitleWider($pageIdx, $widthPercents = 100)
    {
        if (!isset($pageIdx) || count($this->tabsInfo) == 0 || !isset($this->tabsInfo[$pageIdx])) {
            return false;
        } else {
            return ($this->tabsInfo[$pageIdx]['titlesFullness'] > $widthPercents) ? true : false;
        }
    }

}
