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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Tabber is a component allowing to organize your dialog into pages and 
 * switch between the page using Tabs at the top.
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Tabber extends XLite_View_AView
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
            self::PARAM_BODY      => new XLite_Model_WidgetParam_String('Body template file', '', false),
            self::PARAM_SWITCH    => new XLite_Model_WidgetParam_String('Switch', 'page', false),
            self::PARAM_TAB_PAGES => new XLite_Model_WidgetParam_String('Name of function that returns tab pages', 'getTabPages', false)

        );
    }

    /**
     * Get prepared pages array for tabber
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTabberPages()
    {
        $pages = array();

        $url = $this->get('url');
        $switch = $this->getParam(self::PARAM_SWITCH);
        $functionName = $this->getParam(self::PARAM_TAB_PAGES);
        $dialogPages = XLite::getController()->$functionName();

        if (is_array($dialogPages)) {
            foreach ($dialogPages as $page => $title) {
                $p = new XLite_Base();
                $pageURL = preg_replace("/".$switch."=(\w+)/", $switch."=".$page, $url);
                $p->set('url', $pageURL);
                $p->set('title', $title);
                $page_switch = sprintf("$switch=$page");
                $p->set('selected', (preg_match("/" . preg_quote($page_switch) . "(\Z|&)/Ss", $url)));
                $pages[] = $p;
            }
        }

        // if there is only one tab page, set it as a seleted with the default URL
        if (count($pages) == 1) {
            $pages[0]->set('selected', $url);
        }

        return $pages;
    }

    /**
     * Get splitted pages 
     * 
     * @param integer $splitParameter
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

    		$pagesNumber ++;
        }

        $splitParameter = $pagesNumber;

        $pages = $this->split($pages, ($splitParameter > 1 ? $splitParameter : 1));
        krsort($pages);

        $pagesTitlesLengthMax = 0;

        foreach ($pages as $page_idx => $pagesArray) {

            $pagesTitlesLength = 0;

            foreach ($pagesArray as $page) {

                $pagesTitlesLength += (is_null($page) ? 0 : strlen($page->title));

    			if ($pagesTitlesLength > $pagesTitlesLengthMax) {
    				$pagesTitlesLengthMax = $pagesTitlesLength;
    			}
            }

    		$this->tabPagesInfo[$page_idx] = array("titlesLength" => $pagesTitlesLength, "titlesLengthMax" => 0, "titlesFullness" => 0);
        }

        foreach ($this->tabPagesInfo as $page_idx => $pagesInfo) {

    		$this->tabPagesInfo[$page_idx]['titlesLengthMax'] = $pagesTitlesLengthMax;
            $this->tabPagesInfo[$page_idx]['titlesFullness'] = ceil($this->tabPagesInfo[$page_idx]['titlesLength'] * 100 / $this->tabPagesInfo[$page_idx]['titlesLengthMax']);

    	}

    	return $pages;
    }

    /**
     * Check if title is wider than specified percent of total length of all titles
     * 
     * @param string $page_idx      Page identificator (key in the $pages array)
     * @param int    $widthPercents Percent value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isTitleWider($page_idx, $widthPercents = 100)
    {
    	if (!isset($page_idx) || count($this->tabPagesInfo) == 0 || !isset($this->tabPagesInfo[$page_idx])) {
    		return false;
    	}

    	return ($this->tabPagesInfo[$page_idx]['titlesFullness'] > $widthPercents) ? true : false;
    }
}
