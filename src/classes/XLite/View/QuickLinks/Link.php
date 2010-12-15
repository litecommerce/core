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

namespace XLite\View\QuickLinks;

/**
 * Node 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Link extends \XLite\View\AView
{
    /**
     * Widget param names
     */

    const PARAM_NAME      = 'name';
    const PARAM_LINK      = 'url';
    const PARAM_HIGHLIGHT = 'highlight';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'quicklinks/link.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_NAME      => new \XLite\Model\WidgetParam\String(
                'Name', ''
            ),
            self::PARAM_LINK      => new \XLite\Model\WidgetParam\String(
                'Link', ''
            ),
            self::PARAM_HIGHLIGHT => new \XLite\Model\WidgetParam\Bool(
                'Highlight', false
            ),
        );
    }


    /**
     * Static method to create links in controller classes
     * 
     * @param string  $name Link title
     * @param string  $link Link URL
     * @param boolean $hl   Highlight flag
     *
     * @return object
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function create($name, $link, $hl)
    {
        return new static(
            array(
                self::PARAM_NAME      => $name,
                self::PARAM_LINK      => $link,
                self::PARAM_HIGHLIGHT => $hl
            )
        );
    }
}
