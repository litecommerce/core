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
        $result = \XLite\RemoteModel\Marketplace::getInstance()->retriveToLocalRepository(
            $this->getModuleId()
        );

        if (\XLite\RemoteModel\Marketplace::STATUS_ERROR !== $result) {

            // TODO Compare with Upload Addons feature... 
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
     * Return module identificator from request
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
