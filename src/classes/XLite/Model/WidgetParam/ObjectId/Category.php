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

namespace XLite\Model\WidgetParam\ObjectId;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Category extends \XLite\Model\WidgetParam\ObjectId
{
    /**
     * Allowed or not to  use root category id (0) 
     * 
     * @var    boolean
     * @access protected
     * @since  3.0.0
     */
    protected $rootIsAllowed = false;


    /**
     * Return object class name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getClassName()
    {
        return '\XLite\Model\Category';
    }

    /**
     * getIdValidCondition 
     * 
     * @param mixed $value value to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getIdValidCondition($value)
    {
        $result = parent::getIdValidCondition($value);

        if ($this->rootIsAllowed) {
            $result = array(
                self::ATTR_CONDITION => 0 > $value,
                self::ATTR_MESSAGE   => ' is a negative number',
            );
        }

        return $result;
    }

    /**
     * getObjectExistsCondition 
     * 
     * @param mixed $value value to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getObjectExistsCondition($value)
    {
        $result = parent::getIdValidCondition($value);

        $result[self::ATTR_CONDITION] = 0 < $value && $result[self::ATTR_CONDITION];

        return $result;
    }


    /**
     * Constructor
     * 
     * @param string  $label         param label (text)
     * @param mixed   $value         default value
     * @param boolean $isSetting     display this setting in CMS or not
     * @param boolean $rootIsAllowed root category id (0) is allowed or not
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($label, $value = null, $isSetting = false, $rootIsAllowed = false)
    {
        parent::__construct($label, $value, $isSetting);
        
        $this->rootIsAllowed = $rootIsAllowed;
    }
}
