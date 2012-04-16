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
 * @since     1.0.17
 */

namespace XLite\View\Form;

/**
 * Ecwid data source form
 *
 * @see   ____class_see____
 * @since 1.0.17
 */
class DataSource extends \XLite\View\Form\AForm
{
    /**
     * Get default target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getDefaultTarget()
    {
        return 'test';
    }

    /**
     * Get default action
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getDefaultAction()
    {
        return 'update';
    }

    /**
     * getDefaultClassName
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getDefaultClassName()
    {
        $class = parent::getDefaultClassName();

        $class .= ($class ? ' ' : '') . 'validationEngine';

        return $class;
    }
}
