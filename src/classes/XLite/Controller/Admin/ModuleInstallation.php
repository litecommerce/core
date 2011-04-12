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
 * Module marketplace installation controller
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class ModuleInstallation extends \XLite\Controller\Admin\AAdmin
{
    // {{{ Public methods for viewers

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return ($module = $this->getModule()) ? ($module->getModuleName() . ' license agreement') : null;
    }

    /**
     * Return LICENSE text for the module
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getLicense()
    {
        $result = null;
        $marketplaceID = $this->getModule()->getMarketplaceID();

        if (!empty($marketplaceID)) {

            $info = \XLite\Core\Marketplace::getInstance()->getAddonInfo($marketplaceID);

            if ($info) {
                $result = $info[\XLite\Core\Marketplace::RESPONSE_FIELD_MODULE_LICENSE];
            } else {
                list($code, $message) = \XLite\Core\Marketplace::getInstance()->getError();
                \XLite\Core\TopMessage::getInstance()->addError($message, $code);
            }

        } else {

            \XLite\Core\TopMessage::getInstance()->addError('Markeplace ID is not set for module');
        }

        // Since this action is performed in popup
        if (!isset($result)) {
            // :TODO: check if this needed. Or may be empty license is allowed?
            // $this->redirect();
        }

        return $result;
    }

    // }}}

    // {{{ Short-name methods

    /** 
     * Return module identificator
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleId()
    {   
        return \XLite\Core\Request::getInstance()->module_id;    
    }

    /**
     * Search for module
     * 
     * @return \XLite\Model\Module|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModule()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($this->getModuleId());
    }

    /**
     * Search for module license key
     *
     * @param array $data Keys to search
     * 
     * @return \XLite\Model\ModuleKey
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleKey(array $data)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey')->findOneBy($data);
    }

    // }}}

    // {{{ "Get license" action handler

    /**
     * Action of getting LICENSE text. Redirection to GET request
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionGetLicense()
    {
        $this->setReturnURL(
            $this->buildURL('module_installation', 'show_license', array('module_id' => $this->getModuleId()))
        );
    }

    // }}}

    // {{{ "Register key" action handler

    /**
     * Action of license key registration 
     *
     * FIXME: must be completely refactored
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionRegisterKey()
    {
        $key = \XLite\Core\Request::getInstance()->key;

        // :FIXME: [MARKETPLACE]
        $moduleInfo = array(); //\XLite\RemoteModel\Marketplace::getInstance()->getModuleInfoByKey($key);

        if (!isset($moduleInfo['error'])) {

            $moduleKey = \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey')->findOneBy($moduleInfo);

            $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy(
                array(
                    'name'   => $moduleInfo['module'],
                    'author' => $moduleInfo['author'],
                )
            );

            if (is_null($moduleKey)) {

                $moduleKey = new \XLite\Model\ModuleKey();
    
                $moduleKey->map(
                    $moduleInfo + array(
                        'keyValue' => $key,
                    )
                );  

                \XLite\Core\Database::getEM()->persist($moduleKey);

            } else {

                $moduleKey->setKeyValue($key);
            }

            $module->setPurchased(true);

            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::getInstance()->addInfo(
                'License key has been successfully verified for {{module}} module by {{author}} author',
                array(
                    'module' => $moduleInfo['module'],
                    'author' => $moduleInfo['author'],
                )
            );

        } else {

            \XLite\Core\TopMessage::getInstance()->addError(
                'Marketplace error: {{error}}',
                array(
                    'error' => $moduleInfo['error'],
                )
            );
        }

        $this->set('returnUrl', $this->buildURL('addons_list'));
    }

    // }}}

    // {{{ "Get package" action handler

    /**
     * Action of getting package from marketplace
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionGetPackage()
    {
        // Assuming an error
        $this->setReturnUrl($this->buildURL('addons_list'));

        // Checking "Agree" checkbox
        if ('Y' !== \XLite\Core\Request::getInstance()->agree) {

            // License not accepted
            \XLite\Core\TopMessage::getInstance()->addError(
                'You must agree with License agreement in order to install module'
            );

        } elseif ($this->getModule()->isFree() || ($key = $this->checkModuleKey())) {

            // Module key is checked - recieve package
            $this->getPackage(empty($key) ? null : $key);
        }
    }

    /**
     * Check module license key
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkModuleKey()
    {
        $result = null;

        $data = array(
            'module' => $this->getModule()->getName(),
            'author' => $this->getModule()->getAuthor(),
        );
        $key = $this->getModuleKey($data);

        // Search for module key
        if (!isset($key)) {

            // Key not found
            \XLite\Core\TopMessage::getInstance()->addError(
                'You must pay for the {{module}} module by {{author}} author using "Purchase" button',
                $data
            );

        } else {

            // Retrieve key value
            $result = $key->getKeyValue();
        }

        return $result;
    }

    /**
     * Recieve and unpack module
     *
     * @param string $key License key value OPTIONAL
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPackage($key = null)
    {
        // Trying to recieve module source
        /*if (\XLite\RemoteModel\Marketplace::STATUS_ERROR !== ($status = $this->retrieveToLocalRepository($key))) {

            // Check status and deploy module
            $this->checkAndDeployPackage($status);

        } else {

            // An error occured
            \XLite\Core\TopMessage::getInstance()->addError('Error while recieving the module');
        }*/
    }

    /**
     * Fetch module from marketplace
     * 
     * @param string $key License key value OPTIONAL
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function retrieveToLocalRepository($key = null)
    {
        // :FIXME: [MARKETPLACE]
        /*return \XLite\RemoteModel\Marketplace::getInstance()->retrieveToLocalRepository(
            $this->getModuleId(),
            $key ? array('key' => $key) : array()
        );*/
    }

    /**
     * Check status and deploy module
     * 
     * @param integer|string $file Result of the "retireve" action
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAndDeployPackage($file)
    {
        // Create object and extract files into temp dir
        $module = new \XLite\Model\PHARModule($file);

        // If the extraction was successfull
        if ($this->checkModuleIntegrity($module) && $this->deployModule($module)) {

            // Perform some actions
            $this->finishDeployment($module);

            // Set confirmation
            \XLite\Core\TopMessage::getInstance()->addInfo('Module has been uploaded successfully');

        } else {

            // An error occured
            \XLite\Core\TopMessage::getInstance()->addError($module->getMessage());
        }

        // Clean up
        $this->cleanUpOnDeploymentComplete($file, $module);
    }

    /**
     * Check if module is valid and has a correct structure
     * 
     * @param \XLite\Model\PHARModule $module Module to check
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkModuleIntegrity(\XLite\Model\PHARModule $module)
    {
        return $module->isValid();
    }

    /**
     * Copy files
     * 
     * @param \XLite\Model\PHARModule $module Package to deploy
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function deployModule(\XLite\Model\PHARModule $module)
    {
        return $module->deploy() || true;
    }

    /**
     * Perform some actions on complete
     *
     * @param \XLite\Model\PHARModule $module Deployed package OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function finishDeployment(\XLite\Model\PHARModule $module = null)
    {
        // Set flags
        $this->getModule()->setPurchased(true);
        $this->getModule()->setInstalled(true);

        // Modify database record
        if ($module) {
            \XLite\Core\Database::getRepo('\XLite\Model\Module')->update($this->getModule());
        }

        // Success
        $this->setReturnURL($this->buildURL('modules'));
    }

    /**
     * Clean up procedure
     *
     * @param string                  $file   PHAR file 
     * @param \XLite\Model\PHARModule $module Current module OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function cleanUpOnDeploymentComplete($file, \XLite\Model\PHARModule $module = null)
    {
        if ($module) {
            $module->cleanUp();
        }

        // Remove temporary file
        \Includes\Utils\FileManager::delete($file);
    }
}
