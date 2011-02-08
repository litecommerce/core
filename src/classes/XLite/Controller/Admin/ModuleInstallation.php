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
     * Action of getting LICENSE text. Redirection to GET request
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionGetLicense()
    {
        $this->set(
            'returnUrl', 
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
     * Action of getting package from marketplace
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionGetPackage()
    {
        $moduleById = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($this->getModuleId());

        $mapFields = array(
            'module' => $moduleById->getName(),
            'author' => $moduleById->getAuthor(),
        );  

        // Register module license key for a further use
        $moduleKey = \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey')->findOneBy($mapFields);

        if (!is_null(\XLite\Core\Request::getInstance()->key)) {

            if (is_null($moduleKey)) {

                $moduleKey = new \XLite\Model\ModuleKey();
                
                $moduleKey->map(
                    $mapFields + array(
                        'keyValue' => \XLite\Core\Request::getInstance()->key,
                    )
                );

                \XLite\Core\Database::getEM()->persist($moduleKey);

            } else {

                $moduleKey->setKeyValue(\XLite\Core\Request::getInstance()->key);
            }

            $key = \XLite\Core\Request::getInstance()->key;

            \XLite\Core\Database::getEM()->flush();

        } else {

            // Check if there is a working license key for this module
            if (!is_null($moduleKey)) {

                $key = $moduleKey->getKeyValue();
            }
        }

        // Retrieve the module package from the Marketplace to Local Repository of the LC
        $result = \XLite\RemoteModel\Marketplace::getInstance()->retriveToLocalRepository(
            $this->getModuleId(),
            (isset($key) && !empty($key))
                ? array('key' => $key)
                : array()
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
                        'result'    => $module->getStatus() . '::' . $module->getError(),
                        'file'      => $result,
                    )
                );
            }

            $module->cleanUp();

            @unlink(LC_LOCAL_REPOSITORY . $result);
        }

        $this->set('returnUrl', $this->buildURL('modules'));
    }

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

}
