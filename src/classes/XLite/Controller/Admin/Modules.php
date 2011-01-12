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

namespace XLite\Controller\Admin;

/**
 * Modules
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Modules extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Module')->checkModules();

        parent::handleRequest();
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Manage add-ons';
    }

    /**
     * Method to create quick links
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineQuickLinks()
    {
        parent::defineQuickLinks();

        // Count upgradable add-ons
        $upgradablesCount = count(\XLite\Core\Database::getRepo('\XLite\Model\Module')->findUpgradableModules());
        $upgradablesLabel = 0 < $upgradablesCount
            ? ' <i>(' . $upgradablesCount . ')</i>'
            : '';
        
        $this->addQuickLink(
            $this->t('Manage add-ons') . $upgradablesLabel,
            $this->buildURL('modules'),
            true
        );

        $this->addQuickLink(
            $this->t('Install new add-ons'),
            $this->buildURL('addons_list', '', array('mode' => 'featured'))
        );
    }

    /**
     * Enable module
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionEnable()
    {
        $this->set('returnUrl', $this->buildUrl('modules'));

        $id = \XLite\Core\Request::getInstance()->moduleId;
        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($id);

        if ($module) {
            $module->setEnabled(true);
            \XLite\Core\Database::getEM()->flush();
            \XLite::setCleanUpCacheFlag(true);
        }
    }

    /**
     * Disable module
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDisable()
    {
        $this->set('returnUrl', $this->buildUrl('modules'));

        $id = \XLite\Core\Request::getInstance()->moduleId;
        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($id);

        if ($module) {
            $module->disableModule();
            \XLite::setCleanUpCacheFlag(true);
        }
    }

    /**
     * Uninstall module
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUninstall()
    {
        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find(
            \XLite\Core\Request::getInstance()->moduleId
        );

        if (!$module) {

            \XLite\Core\TopMessage::getInstance()->addError('The module to uninstall has not been found');

        } else {
            $notes = $module->getMainClass()->getPostUninstallationNotes();
            
            // Disable this and depended modules
            $module->disableModule();

            \XLite::setCleanUpCacheFlag(true);

            $status = $module->uninstall();

            \XLite\Core\Database::getEM()->remove($module);
            \XLite\Core\Database::getEM()->flush();

            if ($status) {
                \XLite\Core\TopMessage::getInstance()->addInfo('The module has been uninstalled successfully');

            } else {
                \XLite\Core\TopMessage::getInstance()->addWarning('The module has been partially uninstalled');
            }

            if ($notes) {
                \XLite\Core\TopMessage::getInstance()->add(
                    $notes,
                    \XLite\Core\TopMessage::INFO,
                    true
                );
            }
        }
        
        $this->set('returnUrl', $this->buildUrl('modules'));
    }

}
