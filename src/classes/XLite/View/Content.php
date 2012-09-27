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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View;

/**
 * \XLite\View\Content
 *
 *
 * @ListChild (list="body", zone="customer", weight="100")
 * @ListChild (list="body", zone="admin", weigth="100")
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
     * @param string $template Template file name OPTIONAL
     *
     * @return void
     */
    public function display($template = null)
    {
        isset(\XLite\View\Controller::$bodyContent) ? $this->echoContent() : parent::display($template);
    }


    /**
     * getBufferSize
     *
     * @return void
     */
    protected function getOutputChunkSize()
    {
        return self::BUFFER_SIZE;
    }

    /**
     * Return widget default template
     *
     * @return string
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
     */
    protected function echoChunk(&$chunk)
    {
        \Includes\Utils\Operator::flush($chunk, false, null);
    }

    /**
     * echoContent
     *
     * @return void
     */
    protected function echoContent()
    {
        array_map(
            array($this, 'echoChunk'),
            str_split(\XLite\View\Controller::$bodyContent, $this->getOutputChunkSize())
        );
    }

    /**
     * Check - first sidebar is visible or not
     *
     * @return boolean
     */
    protected function isSidebarFirstVisible()
    {
        return !in_array(\XLite\Core\Request::getInstance()->target, array('cart', 'product', 'checkout'));
    }

    /**
     * Check - second sidebar is visible or not
     *
     * @return boolean
     */
    protected function isSidebarSecondVisible()
    {
        return false;
    }
}
