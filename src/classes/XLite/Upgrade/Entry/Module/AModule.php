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

namespace XLite\Upgrade\Entry\Module;

/**
 * AModule
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AModule extends \XLite\Upgrade\Entry\AEntry
{
    /**
     * Return module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getActualName();

    /**
     * Update database records
     *
     * @param string $author Module author
     * @param string $name   Module name
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function updateDBRecords($author, $name);

    /**
     * Perform upgrade
     *
     * @param boolean $isTestMode       Flag OPTIONAL
     * @param array   $filesToOverwrite List of custom files to overwrite OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function upgrade($isTestMode = true, array $filesToOverwrite = array())
    {
        parent::upgrade($isTestMode, $filesToOverwrite);

        if (!$isTestMode) {
            list($author, $name) = explode('\\', $this->getActualName());

            $this->updateDBRecords();

            if (!$this->isValid()) {
                \Includes\SafeMode::markModuleAsUnsafe($author, $name);
            }
        }
    }
}
