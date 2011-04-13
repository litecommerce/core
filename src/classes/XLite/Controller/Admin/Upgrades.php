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
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Upgrades 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Upgrades extends \XLite\Controller\Admin\Base\Updater
{
    // {{{ Public methods for viewers

    /**
     * Return major version of core to update/upgrade
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCoreMajorVersionForUpdate()
    {
        $result = null;

        foreach ($this->getAvailableCoreVersions() as $data) {
            $data = $data[\XLite\Core\Marketplace::RESPONSE_FIELD_CORE_VERSION];
            $majorVersion = $data[\XLite\Core\Marketplace::FIELD_VERSION_MAJOR];

            if (!isset($result) || version_compare($majorVersion, $result, '>')) {
                $result = $majorVersion;
            }
        }

        return $result;
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Upgrade to version (' . $this->getCoreMajorVersionForUpdate() . ')';
    }

    // }}}

    /**
     * Common method to set current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'Upgrade';
    }

    /**
     * Method to get module for update/upgrade
     *
     * @param \XLite\Model\Module $module Currently installed module version
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleForUpdate(\XLite\Model\Module $module)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->getModuleForUpgrade(
            $module,
            $this->getCoreMajorVersionForUpdate()
        );
    }
}
