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

namespace XLite\Module\ProductOptions\Validator;

// FIXME - check this class

/**
 * Category selector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class RequiredValidator extends \XLite\Validator\AValidator
{
    const PARAM_OPTION_ID  = 'option_id';
    const PARAM_FIELD_NAME = 'field';
    const PARAM_ACTION     = 'action';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/required_validator.tpl';
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
            self::PARAM_OPTION_ID   => new \XLite\Model\WidgetParam\Int('Option Id', null),
            self::PARAM_FIELD_NAME  => new \XLite\Model\WidgetParam\String('Field', null),
            self::PARAM_ACTION      => new \XLite\Model\WidgetParam\String('Action', null)
        );
    }


    /**
     * isValid 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isValid()
    {
        $result = false;

        if (parent::isValid()) {

            $fieldName = $this->getParam(self::PARAM_FIELD_NAME);

            if (strpos($fieldName, '[') !== false) {
                $fieldName = explode('[', $fieldName, 2);

                if (substr($fieldName[1], 0, 1) == ']') {
                    $fieldName[1] = $this->getParam(self::PARAM_OPTION_ID) . $fieldName[1];
                }

                $fieldName[1] = '[' . $fieldName[1];
                @eval('$fieldData = $_POST[' . $fieldName[0] . ']'. $fieldName[1] . ';');
                $result = !empty($fieldData) || !isset($fieldData);

            } else {
            	$result = !empty(\XLite\Core\Request::getInstance()->$fieldName) || !isset(\XLite\Core\Request::getInstance()->$fieldName);
    	    }

            if (!$result) {

                if (
                     ( isset(\XLite\Core\Request::getInstance()->action)
                      && $this->getParam(self::PARAM_ACTION) != \XLite\Core\Request::getInstance()->action )
                     || 
                     ( isset(\XLite\Core\Request::getInstance()->action)
                       && $this->getParam(self::PARAM_ACTION) == "update_product_option"
                       && isset(\XLite\Core\Request::getInstance()->option_id)
                       && $this->getParam(self::PARAM_OPTION_ID) != \XLite\Core\Request::getInstance()->option_id )
                    ) {
                	$result = true;
            	}
            }
        }

        return $result;
    }
}

