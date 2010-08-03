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

namespace XLite\View;

/**
 * \XLite\View\Content 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Content extends AView
{
    /**
     * Chunk size
     */
    const BUFFER_SIZE = 8192;


    /**
     * getBufferSize 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getOutputChunkSize()
    {
        return self::BUFFER_SIZE;
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
        return null;
    }

    /**
     * echoChunk
     *
     * @param string $chunk text chunk to output
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function echoChunk(&$chunk)
    {
        \Includes\Utils\Operator::flush($chunk, false);
    }

    /**
     * echoContent 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function echoContent()
    {
        array_map(array($this, 'echoChunk'), str_split(\XLite\View\Controller::$bodyContent, $this->getOutputChunkSize()));
    }


    /**
     * display
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function display()
    {
        isset(\XLite\View\Controller::$bodyContent) ? $this->echoContent() : parent::display();
    }
}

