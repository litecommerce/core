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

namespace XLite\View\Upgrade;

/**
 * AUpgrade
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AUpgrade extends \XLite\View\Dialog
{
    /**
     * Return list of allowed targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'upgrade';

        return $result;
    }

    /**
     * Get directory where template is located (body.tpl)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'upgrade';
    }

    /**
     * Return internal list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        $result = parent::getListName();

        if (!empty($result)) {
            $result .= '.';
        }

        return $result . 'upgrade';
    }

    /**
     * Return list of modules and/or core to upgrade
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpgradeEntries()
    {
        return \XLite\Upgrade\Cell::getInstance()->getEntries();
    }

    /**
     * Check if passed entry is a module
     *
     * @param \XLite\Upgrade\Entry\AEntry $entry Object to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isModule(\XLite\Upgrade\Entry\AEntry $entry)
    {
        return $entry instanceof \XLite\Upgrade\Entry\Module\AModule;
    }
}
