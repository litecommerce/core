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
 * @since     1.0.15
 */

namespace XLite\Module\CDev\GoSocial\Controller\Admin;

/**
 * Import / export controller
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class ImportExport extends \XLite\Controller\Admin\ImportExport implements \XLite\Base\IDecorator
{
    /**
     * Define product columns
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function defineProductColumns()
    {
        $list = parent::defineProductColumns();

        $list['ogMeta'] = array('type' => static::TYPE_STRING, 'length' => 65535);
        $list['useCustomOG'] = array('type' => static::TYPE_BOOLEAN);

        return $list;
    }
}

