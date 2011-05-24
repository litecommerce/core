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

namespace XLite\Controller\Admin;

/**
 * Product classes
 *
 * @see   ____class_see____
 * @since 1.0.0
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
     * Update action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addClass($name)
    {
        \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->insert(
            array(static::NAME => strval($name))
        );
    }


}
