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
 * AddonUpload 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class AddonUpload extends \XLite\Controller\Admin\Base\AddonInstall
{
    // {{{ Get package source as string

    /**
     * Method to get package source (data)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPackage()
    {
        return '';
    }

    /// }}}


















    /**
     * Name of the variable in the global $_FILES array
     */
    // const UPLOAD_NAME = 'upload_addon';

    /**
     * Values for the statuses of uploaded file moving 
     */

    /*const MOVE_OK    = 'ok';
    const MOVE_ERROR = 'error';


    /**
     * Return page title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    /*public function getTitle()
    {
        return $this->t('Upload add-ons');
    }

    /**
     * Upload addons procedure.
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    /*protected function doActionUpload()
    {
        if (isset($_FILES[self::UPLOAD_NAME])) {

            $addonInfo = $_FILES[self::UPLOAD_NAME];

            foreach ($addonInfo['name'] as $index => $name) {

                $moveResult = $this->moveToLocalRepository($addonInfo['tmp_name'][$index], $name);

                if (self::MOVE_OK === $moveResult) {

                    $module = new \XLite\Model\PHARModule($name);

                    if ($module->isValid()) {

                        // Deploy module wrapper (Some additional text or warnings are shown)
                        $this->deployModule($module, $index, $name);

                        // Remove the temporary content and uploaded PHAR file
                        $module->cleanUp();

                        @unlink($name);
                    }
                }
            }

        } else {

            \XLite\Core\TopMessage::addError(
                'You should provide .PHAR file to use this form'
            );
        }

        // Redirect admin to the modules list page
        $this->setReturnURL($this->buildURL('addons_list_installed'));
    }


    /**
     * Deploy module method
     * 
     * @param \XLite\Model\PHARModule $module Model of PHAR module to deploy
     * @param integer                 $index  Index of the PHAR file in the batch
     * @param string                  $name   Name of the PHAR file
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    /*protected function deployModule(\XLite\Model\PHARModule $module, $index, $name)
    {
        if ($module->isValid()) {

            $module->deploy();

            \XLite\Core\TopMessage::addInfo(
                'Module(s) has been uploaded successfully'
            );

        } else {

            \XLite\Core\TopMessage::addError(
                'Checking procedure returns with "{{result}}" result for {{index}}: {{file}} file.',
                array(
                    'result' => $module->getStatus() . ' (' . $module->getMessage() . ')',
                    'file'   => $name,
                    'index'  => $index,
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    /*protected function moveToLocalRepository($uploadedFile, $newFile)
    {
        \Includes\Utils\FileManager::mkdirRecursive(LC_LOCAL_REPOSITORY);

        $newFile = LC_LOCAL_REPOSITORY . $newFile;

        return @move_uploaded_file($uploadedFile, $newFile)
            ? self::MOVE_OK
            : self::MOVE_ERROR;
    }*/
}
