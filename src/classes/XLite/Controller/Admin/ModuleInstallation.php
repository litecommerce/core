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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Module marketplace installation controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ModuleInstallation extends \XLite\Controller\Admin\AAdmin
{

    /** 
     * Return module identificator
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleId()
    {   
        return \XLite\Core\Request::getInstance()->module_id;    
    }   

    /** 
     * Return LICENSE text for the module
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLicense()
    {
        return \XLite\RemoteModel\Marketplace::getInstance()->getLicense(
            $this->getModuleId()
        );
    }

    /**
     * Return title 
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        $moduleById = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($this->getModuleId());

        return $moduleById->getModuleName() . $this->t(' License agreement');
    }

    /**
     * Action of getting LICENSE text. Redirection to GET request
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionGetLicense()
    {
        $this->setReturnURL(
            $this->buildURL(
                'module_installation',
                'show_license',
                array(
                    'module_id' => $this->getModuleId(),
                )
            )
        );
    }

    /**
     * Action of license key registration 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionRegisterKey()
    {
        $key = \XLite\Core\Request::getInstance()->key;

        $moduleInfo = \XLite\RemoteModel\Marketplace::getInstance()->getModuleInfoByKey($key);

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


    /**
     * Action of getting package from marketplace
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionGetPackage()
    {
        $this->set('returnUrl', $this->buildURL('addons_list'));

        // Checking "Agree" checkbox
        if ('Y' !== \XLite\Core\Request::getInstance()->agree) {
            \XLite\Core\TopMessage::getInstance()->addError(
                'You must agree with License agreement in order to install module'
            );

            // Error return
            return;
        }

        $moduleById = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($this->getModuleId());

        // Check and get license key value for PAID module
        if (!$moduleById->isFree()) {

            $mapFields = array(
                'module' => $moduleById->getName(),
                'author' => $moduleById->getAuthor(),
            );

            // Get module license key from DB for a further use
            $moduleKey = \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey')->findOneBy($mapFields);

            if (is_null($moduleKey)) {
    
                \XLite\Core\TopMessage::getInstance()->addError(
                    'You must pay for the {{module}} module by {{author}} author using "Purchase" button',
                    $mapFields
                );
    
                // Error return
                return;
            }

            $key = $moduleKey->getKeyValue();
        }

        // Retrieve the module package from the Marketplace to Local Repository of the LC
        $result = \XLite\RemoteModel\Marketplace::getInstance()->retrieveToLocalRepository(
            $this->getModuleId(),
            (isset($key) && !empty($key)) ? array('key' => $key) : array()
        );

        if (\XLite\RemoteModel\Marketplace::STATUS_ERROR !== $result) {

            // TODO Refactor with Upload Addons feature... 
            // has the same functionality
            $module = new \XLite\Model\PHARModule($result);

            if (\XLite\Model\PHARModule::STATUS_OK === $module->getStatus()) {

                $module->check();
            }

            if (\XLite\Model\PHARModule::STATUS_OK === $module->getStatus()) {

                $module->deploy();

                \XLite\Core\TopMessage::getInstance()->addInfo(
                    'Module has been uploaded successfully'
                );

                $moduleById->setPurchased(true);

                \XLite\Core\Database::getEM()->flush();

            } else {

                \XLite\Core\TopMessage::getInstance()->addError(
                    'Checking procedure returns with "{{result}}" result for {{file}} file.',
                    array(
                        'result' => $module->getStatus() . '::' . $module->getError(),
                        'file'   => $result,
                    )
                );
            }

            $module->cleanUp();

            @unlink(LC_LOCAL_REPOSITORY . $result);
        }

        $this->setReturnURL($this->buildURL('modules'));
    }

}
