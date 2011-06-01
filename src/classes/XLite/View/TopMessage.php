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

namespace XLite\View;

/**
 * Top message
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="layout.main", weight="100")
 */
class TopMessage extends \XLite\View\AView
{
    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }

    /**
     * getDir
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'top_message';
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * getTopMessages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTopMessages()
    {
        \XLite\Core\TopMessage::getInstance()->clear();

        return \XLite\Core\TopMessage::getInstance()->getPreviousMessages();
    }

    /**
     * Get message text
     *
     * @param array $data Message
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getText(array $data)
    {
        return $data[\XLite\Core\TopMessage::FIELD_TEXT];
    }

    /**
     * Get message type
     *
     * @param array $data Message
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getType(array $data)
    {
        return $data[\XLite\Core\TopMessage::FIELD_TYPE];
    }

    /**
     * Get message prefix
     *
     * @param array $data Message
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPrefix(array $data)
    {
        switch ($data[\XLite\Core\TopMessage::FIELD_TYPE]) {

            case \XLite\Core\TopMessage::ERROR:
                $prefix = 'Error';
                break;

            case \XLite\Core\TopMessage::WARNING:
                $prefix = 'Warning';
                break;

            default:
                // ...
        }

        return isset($prefix) ? (static::t($prefix) . ':') : '';
    }

    /**
     * Check id box is visible or not
     *
     * :TODO: check if it's really needed, or it's possible to use "isVisible()" instead
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isHidden()
    {
        return !$this->getTopMessages();
    }
}
