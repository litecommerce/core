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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_WidgetParam_List extends XLite_Model_WidgetParam_String
{
    /**
     * Param type
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $type = 'list';

    /**
     * Options 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options = null;


    /**
     * Return list of conditions to check
     *
     * @param mixed $value value to validate
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getValidaionSchema($value)
    {
        return parent::getValidaionSchema($value) + array(
            array(
                self::ATTR_CONDITION => isset($this->options[$value]),
                self::ATTR_MESSAGE   => ' unallowed param value - "' . $value . '"',
            ),
        );
    }


    /**
     * Constructor
     * 
     * @param mixed $label     param label (text)
     * @param mixed $value     default value
     * @param mixed $isSetting display this setting in CMS or not
     * @param array $options   options list
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($label, $value = null, $isSetting = false, array $options = array())
    {
        parent::__construct($label, $value, $isSetting);

        // TODO - check if there are more convinient ways to extend this class
        if (!isset($this->options)) {
            $this->options = $options;
        }
    }
}
