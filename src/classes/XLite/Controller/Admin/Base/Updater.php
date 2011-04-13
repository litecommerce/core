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

namespace XLite\Controller\Admin\Base;

/**
 * Updater 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Updater extends \XLite\Controller\Admin\Base\PackManager
{
    /**
     * List of cores recieved from marketplace (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $coreVersions;


    // {{{ Methods for viewers

    /**
     * Return major version of core to update/upgrade
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getCoreMajorVersionForUpdate();

    /**
     * Method to get module for update/upgrade
     * 
     * @param \XLite\Model\Module $module Currently installed module version
     *  
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function getModuleForUpdate(\XLite\Model\Module $module);


    /**
     * Return minor version of core to update/upgrade
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCoreMinorVersionForUpdate()
    {
        $result = null;

        foreach ($this->getAvailableCoreVersions() as $data) {
            $data = $data[\XLite\Core\Marketplace::RESPONSE_FIELD_CORE_VERSION];
            $majorVersion = $data[\XLite\Core\Marketplace::FIELD_VERSION_MAJOR];

            if (version_compare($majorVersion, $this->getCoreMajorVersionForUpdate(), '=')) {
                $minorVersion = $data[\XLite\Core\Marketplace::FIELD_VERSION_MINOR];

                if (!isset($result) || version_compare($minorVersion, $result, '>')) {
                    $result = $minorVersion;
                }
            }
        }

        return $result;
    }

    /**
     * Returns list of upgradable modules
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModulesForUpdate()
    {
        $result = array();

        $cnd = new \XLite\Core\CommonCell();
        $cnd->{\XLite\Model\Repo\Module::P_INSTALLED} = true;

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\Module')->search($cnd) as $module) {
            $result[] = $this->getModuleForUpdate($module);
        }

        return array_filter($result);
    }

    /**
     * Search for installed module
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModuleInstalled(\XLite\Model\Module $module)
    {
        return $module->getRepository()->getModuleInstalled($module);
    }

    // }}}

    // {{{ Marketplace-related methods

    /**
     * Get list of available kernel versions from the marketplace
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAvailableCoreVersions()
    {
        if (!isset($this->coreVersions)) {
            $this->coreVersions = (array) \XLite\Core\Marketplace::getInstance()->getCoreVersions();
        }

        return $this->coreVersions;
    }

    // }}}
}
