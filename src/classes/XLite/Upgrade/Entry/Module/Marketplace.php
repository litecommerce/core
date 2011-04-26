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

namespace XLite\Upgrade\Entry\Module;

/**
 * Marketplace 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Marketplace extends \XLite\Upgrade\Entry\Module\AModule
{
    /**
     * Module ID in database
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $moduleID;

    /**
     * Return entry readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        return $this->getModule()->getModuleName();
    }

    /**
     * Return entry major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMajorVersion()
    {
        return $this->getModule()->getMajorVersion();
    }

    /**
     * Return entry minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMinorVersion()
    {
        return $this->getModule()->getMinorVersion();
    }

    /**
     * Return module author readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAuthor()
    {
        return $this->getModule()->getAuthorName();
    }

    /**
     * Check if module is enabled
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isEnabled()
    {
        return (bool) $this->getModule()->getEnabled();
    }

    /**
     * Constructor
     *
     * @param \XLite\Model\Module $module Module model object
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(\XLite\Model\Module $module)
    {
        $this->moduleID = $module->getModuleID();

        if (is_null($this->getModule()) || !$this->getModule()->getMarketplaceID()) {
            \Includes\ErrorHandler::fireError(
                'Module with ID "' . $this->moduleID . '" is not found in DB or has an invaid markeplace identifier'
            );
        }
    }

    /**
     * Search for module in DB
     * 
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModule()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($this->moduleID);
    }
}
