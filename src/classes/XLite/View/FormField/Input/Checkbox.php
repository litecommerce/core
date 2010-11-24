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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\FormField\Input;

/**
 * \XLite\View\FormField\Input\Checkbox 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class Checkbox extends \XLite\View\FormField\Input\AInput
{
    /**
     * Widget param names 
     */

    const PARAM_IS_CHECKED = 'isChecked';


    /**
     * Define widget params
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_IS_CHECKED => new \XLite\Model\WidgetParam\Bool('Is checked', false),
        );
    }

    /**
     * Determines if checkbox is checked
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isChecked()
    {
        return $this->getParam(self::PARAM_IS_CHECKED) || $this->checkSavedValue();
    }

    /**
     * prepareAttributes
     *
     * @param array $attrs Field attributes to prepare
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function prepareAttributes(array $attrs)
    {
        $attrs = parent::prepareAttributes($attrs);

        if ($this->isChecked()) {
            $attrs['checked'] = 'checked';
        }

        return $attrs;
    }


    /**
     * Return field type
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getFieldType()
    {
        return self::FIELD_TYPE_CHECKBOX;
    }
}

