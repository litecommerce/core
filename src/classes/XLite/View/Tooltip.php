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
 * @since     1.0.0
 */

namespace XLite\View;

/**
 * Buy now widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Tooltip extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_TEXT     = 'text';
    const PARAM_CLASS    = 'className';
    const PARAM_CAPTION  = 'caption';
    const PARAM_IS_IMAGE = 'isImage';


    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/tooltip.js';

        return $list;
    }

    /**
     * Return array of help text for JS
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getHelpText()
    {
        return array(
            'help_text' => $this->getParam(self::PARAM_TEXT),
        );
    }


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'tooltip.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_TEXT     => new \XLite\Model\WidgetParam\String('Text to show in tooltip', ''),
            self::PARAM_CLASS    => new \XLite\Model\WidgetParam\String('CSS class for caption', ''),
            self::PARAM_CAPTION  => new \XLite\Model\WidgetParam\String('Caption', ''),
            self::PARAM_IS_IMAGE => new \XLite\Model\WidgetParam\Bool('Does it shown as image?', false),
        );
    }
}
