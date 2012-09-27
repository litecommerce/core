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

namespace XLite\Controller\Admin;

/**
 * Product classes
 *
 */
class ProductClasses extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Field name for new 'name' value
     */
    const NEW_NAME = 'new_name';

    /**
     * Field name for 'name' array values
     */
    const NAME = 'name';


    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage catalog');
    }

    /**
     * Update action
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $data = $this->getPostedData();

        if (!empty($data[static::NEW_NAME])) {
            $this->addClass($data[static::NEW_NAME]);
        }

        if (isset($data[static::NEW_NAME])) {
            unset($data[static::NEW_NAME]);
        }

        if (!empty($data)) {
            \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->updateInBatchById($data);
        }
    }

    /**
     * Add product class entry
     *
     * @param string $name Name value
     *
     * @return void
     */
    protected function addClass($name)
    {
        \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->insert(array(static::NAME => strval($name)));
    }
}
