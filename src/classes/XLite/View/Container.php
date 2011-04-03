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
 * @since     3.0.0
 */

namespace XLite\View;

/**
 * Abstract container widget
 * TODO:  waiting for the multiple inheritance
 * FIXME: must extend the AView class
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class Container extends \XLite\View\RequestHandler\ARequestHandler
{
    /**
     * Return title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getHead();

    /**
     * Return templates directory name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getDir();

    /**
     * isWrapper 
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isWrapper()
    {
        return $this->getParam(self::PARAM_TEMPLATE) == $this->getDefaultTemplate();
    }

    /**
     * Return file name for body template
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBodyTemplate()
    {
        return 'body.tpl';
    }

    /**
     * Return current template 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return $this->useBodyTemplate() ? $this->getBody() : parent::getTemplate();
    }

    /**
     * Return file name for the center part template 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBody()
    {
        return $this->getDir() . LC_DS . $this->getBodyTemplate();
    }

    /**
     * Determines if need to display only a widget body
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function useBodyTemplate()
    {
        return \XLite\Core\CMSConnector::isCMSStarted() && $this->isWrapper();
    }
}
