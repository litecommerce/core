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

            $moveResult = $this->moveToLocalRepository($addonInfo);

            foreach ($addonInfo['name'] as $index => $name) {

                $moveResult = $this->moveToLocalRepository($addonInfo['tmp_name'][$index], $name);

                if (self::MOVE_OK === $moveResult) {

                    $module = new \XLite\Model\PHARModule($name);

                    $result = $module->check();

                    if (\XLite\Model\PHARModule::STATUS_OK === $result) {

                        $module->deploy();

                    } else {

                        \XLite\Core\TopMessage::addError(
                            'Checking procedure returns with "{{result}}" result for {{index}}: {{file}} file.',
                            array(
                                'result'    => $result,
                                'file'      => $name,
                                'index'     => $index,
                            )
                        );
                    }

                    $module->cleanUp();
                }
            }

        } else {

            \XLite\Core\TopMessage::addError(
                'You should provide .PHAR file to use this form'
            );
        }

        // Redirect admin to the modules list page
        $this->setReturnUrl($this->buildUrl('modules'));
    }


    /**
     * Move the uploaded file to inner local repository.
     * TODO ... Maybe move this method to the doActionUpload... It is not necessary to use it separately.
     * 
     * @param string $uploadedFile full path to uploaded file
     * @param string $newFile      real name of the file
     *  
     * @return string status of moving the uploaded file.
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function moveToLocalRepository($uploadedFile, $newFile)
    {
        $newFile = LC_LOCAL_REPOSITORY . $newFile;

        return @move_uploaded_file($uploadedFile, $newFile)
            ? self::MOVE_OK
            : self::MOVE_ERROR;
    }
}
