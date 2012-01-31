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
class FileSelector extends \XLite\View\Button\Popup\Button
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
     * Return default value for widget param
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDefaultTarget()
    {
        return 'select_file';
    }

    /**
     * Return default value for widget param
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDefaultWidget()
    {
        return '\XLite\View\FileSelectorDialog';
    }

    /**
     * Return URL parameters to use in AJAX popup
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAdditionalURLParams()
    {
        $list = parent::getAdditionalURLParams();
        $list['object']       = $this->getParam(static::PARAM_OBJECT);
        $list['objectId']     = $this->getParam(static::PARAM_OBJECT_ID);
        $list['fileObject']   = $this->getParam(static::PARAM_FILE_OBJECT);
        $list['fileObjectId'] = $this->getParam(static::PARAM_FILE_OBJECT_ID);

        return $list;
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
            static::PARAM_OBJECT         => new \XLite\Model\WidgetParam\String('Object', 'product'),
            static::PARAM_OBJECT_ID      => new \XLite\Model\WidgetParam\Int('Object ID', 0),
            static::PARAM_FILE_OBJECT    => new \XLite\Model\WidgetParam\String('File object', 'image'),
            static::PARAM_FILE_OBJECT_ID => new \XLite\Model\WidgetParam\Int('File object ID', 0),
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
        return 'file-selector-button ' . ($this->getParam(static::PARAM_STYLE) ?: '');
    }
}
