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

namespace XLite\View\Model\DataSource;

/**
 * Abstract data source model widget
 *
 * @see   ____class_see____
 * @since 1.0.17
 */
class Ecwid extends ADataSource
{

    /**
     * Form fields definition
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.17
     */
    protected $schemaDefault = array(
        'parameter_storeid' => array(
            self::SCHEMA_CLASS      => '\XLite\View\FormField\Input\Text\Integer',
            self::SCHEMA_LABEL      => 'STOREID',
            self::SCHEMA_REQUIRED   => true,
            \XLite\View\FormField\Input\Text\Integer::PARAM_MIN => 1000,
        ),
    );

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\DataSource
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getDefaultModelObject()
    {
        // Always new
        return new \XLite\Model\DataSource();
    }
}
