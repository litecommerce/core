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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View;

/**
 * Tabber is a component allowing to organize your dialog into pages and 
 * switch between the page using Tabs at the top.
 *
 * @see   ____class_see____
 * @since 1.0.0
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
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/tabber.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
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
     * @see    ____func_see____
     * @since  1.0.0
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
        if (1 == count($pages) || 'default' === $this->getPage()) {
            $pages[0]->set('selected', $url);
        }

        return $pages;
    }
}
