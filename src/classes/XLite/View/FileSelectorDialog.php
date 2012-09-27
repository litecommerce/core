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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View;

/**
 * File Selector Dialog widget
 *
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class FileSelectorDialog extends \XLite\View\SimpleDialog
{
    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'select_file';

        return $list;
    }

    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return 'Select file';
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getBody()
    {
        return 'file_selector/body.tpl';
    }

    /**
     * Return parameters to use in file dialog form
     *
     * @return array
     */
    protected function getFileDialogParams()
    {
        $modelForm = $this->getModelForm();

        return array(
            // Inner name (inner identification) of object which is joined with file (product, category)
            // It identifies the model to join file with
            'object'        => $modelForm->getObject(),
            // Identificator of object joined with file
            'objectId'      => $modelForm->getObjectId(),
            // Inner name (inner identification) of file (image, attachment)
            // It identifies the model to store file in
            'fileObject'    => $modelForm->getFileObject(),
            // Identificator of file object (zero for NEW file)
            'fileObjectId'  => $modelForm->getFileObjectId(),
        );
    }
}
