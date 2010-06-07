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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Modules
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Modules extends XLite_Controller_Admin_Abstract
{
    protected $modules = null;

    protected $currentModuleType = null;

    function getModules($type = null)
    {
        if (is_null($this->modules) || $type !== $this->currentModuleType) {
            $this->currentModuleType = $type;
            XLite_Model_ModulesManager::getInstance()->updateModulesList();
            $this->modules = XLite_Model_ModulesManager::getInstance()->getModules($type);
        }

        return $this->modules;
    }

    /**
     * Update modules list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $activeModules = isset(XLite_Core_Request::getInstance()->active_modules) ? XLite_Core_Request::getInstance()->active_modules : array();
        $moduleType = isset(XLite_Core_Request::getInstance()->module_type) ? XLite_Core_Request::getInstance()->module_type : null;

        $this->set('returnUrl', $this->buildUrl('modules'));

        if (!XLite_Model_ModulesManager::getInstance()->updateModules($activeModules, $moduleType)) {
            $this->valid = false;
            $this->hidePageHeader();
        }
    }
}
