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

namespace XLite\Module\CDev\ProductOptions\View\Form\Item;

/**
 * Change options form
 *
 */
class ChangeOptions extends \XLite\View\Form\AForm
{
    /**
     * Widge parameters names
     */

    const PARAM_SOURCE     = 'source';
    const PARAM_STORAGE_ID = 'storage_id';
    const PARAM_ITEM_ID    = 'item_id';


    /**
     * getDefaultTarget
     *
     * @return string
     */
    protected function getDefaultTarget()
    {
        return 'change_options';
    }

    /**
     * getDefaultAction
     *
     * @return string
     */
    protected function getDefaultAction()
    {
        return 'change';
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
            self::PARAM_SOURCE     => new \XLite\Model\WidgetParam\String('Source', \XLite\Core\Request::getInstance()->source),
            self::PARAM_STORAGE_ID => new \XLite\Model\WidgetParam\Int('Storage id', \XLite\Core\Request::getInstance()->storage_id),
            self::PARAM_ITEM_ID    => new \XLite\Model\WidgetParam\Int('Item id', \XLite\Core\Request::getInstance()->item_id),
        );
    }

    /**
     * Initialization
     *
     * @return void
     */
    protected function initView()
    {
        parent::initView();

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue($this->getFormDefaultParams());
    }

    /**
     * Get form default parameters
     *
     * @return array
     */
    protected function getFormDefaultParams()
    {
        return array(
            'source'     => $this->getParam(self::PARAM_SOURCE),
            'storage_id' => $this->getParam(self::PARAM_STORAGE_ID),
            'item_id'    => $this->getParam(self::PARAM_ITEM_ID),
        );
    }
}
