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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class UploadAddons extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Name of the variable in the global $_FILES array
     */
    const UPLOAD_NAME   = 'upload_addon';

    /**
     * Values for the statuses of uploaded file moving 
     */
    const MOVE_OK       = 'ok';
    const MOVE_ERROR    = 'error';


    /**
     * Return page title 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return $this->t('Upload add-ons');
    }

    /**
     * Upload addons procedure.
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpload()
    {
        if (isset($_FILES[self::UPLOAD_NAME])) {

            $addonInfo = $_FILES[self::UPLOAD_NAME];

            foreach ($addonInfo['name'] as $index => $name) {

                $moveResult = $this->moveToLocalRepository($addonInfo['tmp_name'][$index], $name);

                if (self::MOVE_OK === $moveResult) {

                    $module = new \XLite\Model\PHARModule($name);

                    if (\XLite\Model\PHARModule::STATUS_OK === $module->getStatus()) {

                        $module->check();
                    }

                    // Deploy module wrapper (Some additional text or warnings are shown)
                    $this->deployModule($module, $index, $name);

                    // Remove the temporary content and uploaded PHAR file
                    $module->cleanUp();

                    @unlink($name);
                }
            }

        } else {

            \XLite\Core\TopMessage::getInstance()->addError(
                'You should provide .PHAR file to use this form'
            );
        }

        // Redirect admin to the modules list page
        $this->setReturnURL($this->buildURL('modules'));
    }


    /**
     * Deploy module method
     * 
     * @param \XLite\Model\PHARModule $module Model of PHAR module to deploy
     * @param integer                 $index  Index of the PHAR file in the batch
     * @param string                  $name   Name of the PHAR file
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function deployModule(\XLite\Model\PHARModule $module, $index, $name)
    {
        if (\XLite\Model\PHARModule::STATUS_OK === $module->getStatus()) {

            $module->deploy();

            \XLite\Core\TopMessage::getInstance()->addInfo(
                'Module(s) has been uploaded successfully'
            );

        } else {

            \XLite\Core\TopMessage::getInstance()->addError(
                'Checking procedure returns with "{{result}}" result for {{index}}: {{file}} file.',
                array(
                    'result'    => $module->getStatus() . '::' . $module->getError(),
                    'file'      => $name,
                    'index'     => $index,
                )
            );
        }   
    }


    /**
     * Move the uploaded file to inner local repository.
     * 
     * @param string $uploadedFile Full path to uploaded file
     * @param string $newFile      Real name of the file
     *  
     * @return string Status of moving the uploaded file.
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function moveToLocalRepository($uploadedFile, $newFile)
    {
        \Includes\Utils\FileManager::mkdirRecursive(LC_LOCAL_REPOSITORY);

        $newFile = LC_LOCAL_REPOSITORY . $newFile;

        return @move_uploaded_file($uploadedFile, $newFile)
            ? self::MOVE_OK
            : self::MOVE_ERROR;
    }
}
