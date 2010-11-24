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

namespace XLite\View;

/**
 * Tabber is a component allowing to organize your dialog into pages and 
 * switch between the page using Tabs at the top.
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Tabber extends \XLite\View\AView
{
    /*
     * Widget parameters names
     */
    const PARAM_BODY      = 'body';
    const PARAM_SWITCH    = 'switch';
    const PARAM_TAB_PAGES = 'tabPages';

    /**
     * tabPagesInfo 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $tabPagesInfo = array();


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/tabber.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_BODY      => new \XLite\Model\WidgetParam\String('Body template file', '', false),
            self::PARAM_SWITCH    => new \XLite\Model\WidgetParam\String('Switch', 'page', false),
            self::PARAM_TAB_PAGES => new \XLite\Model\WidgetParam\String('Name of function that returns tab pages', 'getTabPages', false)

        );
    }

    /**
     * Get prepared pages array for tabber
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTabberPages()
    {
        $pages = array();

        $url = $this->get('url');
        $switch = $this->getParam(self::PARAM_SWITCH);
        $functionName = $this->getParam(self::PARAM_TAB_PAGES);

        // $functionName - from PARAM_TAB_PAGES parameter
        $dialogPages = \XLite::getController()->$functionName();

        if (is_array($dialogPages)) {
            foreach ($dialogPages as $page => $title) {
                $p = new \XLite\Base();
                $pageURL = preg_replace('/' . $switch . '=(\w+)/', $switch . '=' . $page, $url);
                $p->set('url', $pageURL);
                $p->set('title', $title);
                $pageSwitch = sprintf($switch . '=' . $page);
                $p->set('selected', (preg_match('/' . preg_quote($pageSwitch) . '(\Z|&)/Ss', $url)));
                $pages[] = $p;
            }
        }

        // if there is only one tab page, set it as a seleted with the default URL
        if (1 == count($pages)) {
            $pages[0]->set('selected', $url);
        }

        return $pages;
    }

    /**
     * Get splitted pages 
     * 
     * @param integer $splitParameter Split parameter
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSplittedPages($splitParameter = 0)
    {
        $pages = $this->getTabberPages();
        $pagesTitlesTotalLength = 0;

        foreach ($pages as $page) {
            $pagesTitlesTotalLength += strlen($page->title);
        }

        // Split pages array into {$splitParameter} arrays

          $splitParameter = intval($splitParameter);

           $pagesCurrentLength = 0;
        $pagesNumber = 0;

        foreach ($pages as $page) {

            $pagesCurrentLength += strlen($page->title);

            if ($pagesCurrentLength > $splitParameter) {
                break;
            }

            $pagesNumber++;
        }

        $splitParameter = $pagesNumber;

        $x = 1 < $splitParameter ? $splitParameter : 1;
        $pages = $this->split($pages, $x);
        krsort($pages);

        $pagesTitlesLengthMax = 0;

        foreach ($pages as $pageIdx => $pagesArray) {

            $pagesTitlesLength = 0;

            foreach ($pagesArray as $page) {

                $pagesTitlesLength += (is_null($page) ? 0 : strlen($page->title));

                if ($pagesTitlesLength > $pagesTitlesLengthMax) {
                    $pagesTitlesLengthMax = $pagesTitlesLength;
                }
            }

            $this->tabPagesInfo[$pageIdx] = array(
                'titlesLength'    => $pagesTitlesLength,
                'titlesLengthMax' => 0,
                'titlesFullness'  => 0,
            );
        }

        foreach ($this->tabPagesInfo as $pageIdx => $pagesInfo) {

            $this->tabPagesInfo[$pageIdx]['titlesLengthMax'] = $pagesTitlesLengthMax;
            $this->tabPagesInfo[$pageIdx]['titlesFullness'] = ceil(
                $pagesInfo['titlesLength'] * 100 / $pagesTitlesLengthMax
            );

        }

        return $pages;
    }

    /**
     * Check if title is wider than specified percent of total length of all titles
     * 
     * @param string  $pageIdx       Page identificator (key in the $pages array)
     * @param integer $widthPercents Percent value OPTIONAL
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isTitleWider($pageIdx, $widthPercents = 100)
    {
        return isset($pageIdx)
            && 0 < count($this->tabPagesInfo)
            && isset($this->tabPagesInfo[$pageIdx])
            && $this->tabPagesInfo[$pageIdx]['titlesFullness'] > $widthPercents;
    }
}
