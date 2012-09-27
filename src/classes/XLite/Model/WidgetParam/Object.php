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

namespace XLite\Model\WidgetParam;

/**
 * ____description____
 *
 */
class Object extends \XLite\Model\WidgetParam\AWidgetParam
{
    /**
     * class
     *
     * @var mixed
     */
    protected $class = null;


    /**
     * Constructor
     *
     * @param mixed  $label     Param label (text)
     * @param mixed  $value     Default value OPTIONAL
     * @param mixed  $isSetting Display this setting in CMS or not OPTIONAL
     * @param string $class     Object class OPTIONAL
     *
     * @return void
     */
    public function __construct($label, $value = null, $isSetting = false, $class = null)
    {
        parent::__construct($label, $value, $isSetting);

        // TODO - check if there are more convinient way to extend this class
        if (!isset($this->class)) {
            $this->class = $class;
        }
    }


    /**
     * Return list of conditions to check
     *
     * @param mixed $value Value to validate
     *
     * @return void
     */
    protected function getValidaionSchema($value)
    {
        return array(
            array(
                self::ATTR_CONDITION => is_object($value),
                self::ATTR_MESSAGE   => ' passed value is not an object',
            ),
            array(
                self::ATTR_CONDITION => !isset($this->class) || $value instanceof $this->class,
                self::ATTR_MESSAGE   => ' parameter class is undefined or passed object is not an instance of the param class',
            ),
        );
    }
}
