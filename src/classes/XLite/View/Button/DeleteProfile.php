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

namespace XLite\View\Button;


/**
 * Delete profile button. Admin area.
 *
 */
class DeleteProfile extends \XLite\View\Button\Regular
{

    /**
     * getDefaultAction
     *
     * @return string
     */
    protected function getDefaultAction()
    {
        return 'delete';
    }

    /**
     * Return specified JS code
     *
     * @return string
     */
    protected function getJSCode()
    {
        // We got the default JS code.
        $jsCode = $this->getDefaultJSCode();

        // Message to show admin user. the profile will be removed.
        $message = static::t('Are you sure you want to delete the selected user?');

        // We show confirmation message and remove user profile after admin confirmation only
        return 'if(confirm(\'' . $message . '\')){' . $jsCode . '}';
    }
}
