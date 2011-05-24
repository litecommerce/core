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
 * \XLite\View\Content
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Content extends \XLite\View\AView
{
    /**
     * Chunk size
     */
    const BUFFER_SIZE = 8192;


    /**
     * display
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function display()
    {
        isset(\XLite\View\Controller::$bodyContent) ? $this->echoContent() : parent::display();
    }


    /**
     * getBufferSize
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOutputChunkSize()
    {
        return self::BUFFER_SIZE;
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
        return null;
    }

    /**
     * Echo chunk
     *
     * @param string &$chunk Text chunk to output
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function echoChunk(&$chunk)
    {
        \Includes\Utils\Operator::flush($chunk, false, null);
    }

    /**
     * echoContent
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function echoContent()
    {
        array_map(
            array($this, 'echoChunk'),
            str_split(\XLite\View\Controller::$bodyContent, $this->getOutputChunkSize())
        );
    }

    /**
     * Chewck - first sidebar is visible or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSidebarFirstVisible()
    {
        return !in_array(\XLite\Core\Request::getInstance()->target, array('cart', 'product', 'checkout'));
    }

    /**
     * Check - second sidebar is visible or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSidebarSecondVisible()
    {
        return false;
    }
}
