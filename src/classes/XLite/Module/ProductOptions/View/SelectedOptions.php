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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductOptions\View;

/**
 * Selected product options widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class SelectedOptions extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_ITEM       = 'item';
    const PARAM_SOURCE     = 'source';
    const PARAM_STORAGE_ID = 'storage_id';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/selected_options.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ITEM       => new \XLite\Model\WidgetParam\Object('Item', null, false, '\XLite\Model\OrderItem'),
            self::PARAM_SOURCE     => new \XLite\Model\WidgetParam\String('Source', ''),
            self::PARAM_STORAGE_ID => new \XLite\Model\WidgetParam\Int('Storage id', null),
        );
    }

    /**
     * getItem 
     * 
     * @return \XLite\Model\OrderItem
     * @access protected
     * @since  3.0.0
     */
    protected function getItem()
    {
        return $this->getParam(self::PARAM_ITEM);
    }

    /**
     * Check widget visibility 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getItem()->hasOptions();
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/ProductOptions/change_options.js';
        $list[] = 'js/jquery.blockUI.js';
        $list[] = 'popup/popup.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'popup/popup.css';

        return $list;
    }

    /**
     * Get Change options link URL
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getChangeOptionsLink()
    {
        return $this->buildUrl(
            'change_options',
            '',
            array(
                'source'     => $this->getParam('source'),
                'storage_id' => $this->getParam('storage_id'),
                'item_id'    => $this->getItem()->getItemId(),
                'isPopup'    => 1,
            )
        );
    }
}

