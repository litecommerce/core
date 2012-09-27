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
 * Section-based dialog
 *
 */
abstract class SectionDialog extends \XLite\View\SimpleDialog
{
    /**
     * Define sections list
     *
     * @return array
     */
    abstract protected function defineSections();

    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        $mode = strval(\XLite\Core\Request::getInstance()->mode);
        $sections = $this->defineSections();

        return isset($sections[$mode]) ? $sections[$mode]['head'] : parent::getHead();
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getBody()
    {
        $mode = strval(\XLite\Core\Request::getInstance()->mode);
        $sections = $this->defineSections();

        return isset($sections[$mode]) ? $sections[$mode]['body'] : null;
    }
}
