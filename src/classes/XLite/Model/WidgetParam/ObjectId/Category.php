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

namespace XLite\Model\WidgetParam\ObjectId;

/**
 * ____description____
 *
 */
class Category extends \XLite\Model\WidgetParam\ObjectId
{
    /**
     * Allowed or not to  use root category id (0)
     *
     * @var boolean
     */
    protected $rootIsAllowed = false;


    /**
     * Constructor
     *
     * @param string  $label         Param label (text)
     * @param mixed   $value         Default value OPTIONAL
     * @param boolean $isSetting     Display this setting in CMS or not OPTIONAL
     * @param boolean $rootIsAllowed Root category id (0) is allowed or not OPTIONAL
     *
     * @return void
     */
    public function __construct($label, $value = null, $isSetting = false, $rootIsAllowed = false)
    {
        parent::__construct($label, $value, $isSetting);

        $this->rootIsAllowed = $rootIsAllowed;
    }


    /**
     * Return object class name
     *
     * @return string
     */
    protected function getClassName()
    {
        return '\XLite\Model\Category';
    }

    /**
     * getIdValidCondition
     *
     * @param mixed $value Value to check
     *
     * @return array
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
     * @param mixed $value Value to check
     *
     * @return array
     */
    protected function getObjectExistsCondition($value)
    {
        $result = parent::getIdValidCondition($value);

        $result[self::ATTR_CONDITION] = 0 < $value && $result[self::ATTR_CONDITION];

        return $result;
    }
}
