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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.6
 */

namespace XLite\View\Button;

/**
 * File selector popup button
 *
 * @see   ____class_see____
 * @since 1.0.6
 */
class FileSelector extends \XLite\View\Button\APopupButton
{
    /**
     * Name of object to link uploaded file (e.g. equal to 'product', 'category')
     */
    const PARAM_OBJECT = 'object';

    /**
     * Identificator of linked object.
     */
    const PARAM_OBJECT_ID = 'objectId';

    /**
     * Name of the uploaded file object (e.g. 'image', 'icon', 'file')
     */
    const PARAM_FILE_OBJECT = 'fileObject';

    /**
     * Identificator of the uploaded file object. Used if file must be substituted (update action)
     */
    const PARAM_FILE_OBJECT_ID = 'fileObjectId';

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'button/js/file_selector.js';
        // TODO: dynamic JS inclusion
        $list[] = 'button/js/browse_server.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'file_selector/style.css';

        // TODO: dynamic CSS inclusion
        $list[] = 'browse_server/style.css';

        return $list;
    }

    /**
     * Return URL parameters to use in AJAX popup
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareURLParams()
    {
        return array(
            'target'        => 'select_file',
            'object'        => $this->getParam(self::PARAM_OBJECT),
            'objectId'      => $this->getParam(self::PARAM_OBJECT_ID),
            'fileObject'    => $this->getParam(self::PARAM_FILE_OBJECT),
            'fileObjectId'  => $this->getParam(self::PARAM_FILE_OBJECT_ID),
            'widget'        => '\XLite\View\FileSelectorDialog',
        );
    }

    /**
     * Return default button label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultLabel()
    {
        return 'File upload';
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_OBJECT          => new \XLite\Model\WidgetParam\String('Object', 'product'),
            self::PARAM_OBJECT_ID       => new \XLite\Model\WidgetParam\Int('Object ID', 0),
            self::PARAM_FILE_OBJECT     => new \XLite\Model\WidgetParam\String('File object', 'image'),
            self::PARAM_FILE_OBJECT_ID  => new \XLite\Model\WidgetParam\Int('File object ID', 0),
        );
    }

    /**
     * Return CSS classes
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClass()
    {
        return 'file-selector-button ' . ($this->getParam(self::PARAM_STYLE) ?: '');
    }
}
