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

namespace XLite\Module\CDev\ProductOptions\View;

/**
 * Selected product options widget
 *
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
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/CDev/ProductOptions/change_options.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/ProductOptions/change_options.css';

        return $list;
    }

    /**
     * Get Change options link URL
     *
     * @return string
     */
    public function getChangeOptionsLink()
    {
        return $this->buildURL(
            'change_options',
            '',
            array(
                'source'     => $this->getParam('source'),
                'storage_id' => $this->getParam('storage_id'),
                'item_id'    => $this->getItem()->getItemId(),
            )
        );
    }

    /**
     * Check - item option is empty or not
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\OrderItemOption $option Item option
     *
     * @return boolean
     */
    public function isOptionEmpty(\XLite\Module\CDev\ProductOptions\Model\OrderItemOption $option)
    {
        return 0 == strlen($option->getActualValue());
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/ProductOptions/selected_options.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
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
     */
    protected function getItem()
    {
        return $this->getParam(self::PARAM_ITEM);
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getItem()->hasOptions();
    }
}
