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

/**
 * Top message
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_TopMessage extends XLite_View_Abstract
{
    /**
     * getDir 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'top_message';
    }

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * getBlockId 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getBlockId()
    {
        return 'top_messages';
    }

    /**
     * getTopMessages 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getTopMessages() 
    {
        return XLite_Core_TopMessage::getInstance()->getPreviousMessages();
    }

    /**
     * getText 
     * 
     * @param array $data message
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getText(array $data)
    {
        return $data[XLite_Core_TopMessage::FIELD_TEXT];
    }

    /**
     * getType 
     * 
     * @param array $data message
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getType(array $data)
    {
        return $data[XLite_Core_TopMessage::FIELD_TYPE];
    }


    /**
     * isVisible 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getTopMessages();
    }

    /**
     * Get a list of CSS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

    /**
     * Get a list of JS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }

}
