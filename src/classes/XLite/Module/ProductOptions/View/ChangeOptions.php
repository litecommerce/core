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
 * Change options widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 *
 * @ListChild (list="center")
 */
class ChangeOptions extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_ITEM = 'item';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/change_options.tpl';
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
            self::PARAM_ITEM => new \XLite\Model\WidgetParam\Object('Item', null, false, '\XLite\Model\OrderItem'),
        );
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
        $result = parent::isVisible() && \XLite::getController()->getItem();

        if ($result) {
            $this->widgetParams[self::PARAM_ITEM]->setValue(\XLite::getController()->getItem());

            $result = $this->getParam(self::PARAM_ITEM)->hasOptions();
        }

        return $result;
    }

    /**
     * Get selected options 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSelectedOptions()
    {
        $options = array();

        foreach ($this->getParam(self::PARAM_ITEM)->getOptions() as $option) {
            $options[$option->getGroupId()] = ($option->getOption() && $option->getOption()->getOptionId())
                ? $option->getOption()->getOptionId()
                : $option->getValue();
        }

        return $options;
    }

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'change_options';
    
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

        $list[] = 'modules/ProductOptions/change_options.css';

        return $list;
    }

}

