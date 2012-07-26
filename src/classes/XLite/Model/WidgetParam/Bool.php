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
class Bool extends \XLite\Model\WidgetParam\Set
{
    /**
     * Values for TRUE value
     *
     * @var array
     */
    protected $trueValues = array('1', 'true', 1, true);

    /**
     * Options
     *
     * @var array
     */
    protected $options = array(
        'true'  => 'Yes',
        'false' => 'No',
    );

    /**
     * Get value by name
     *
     * @param mixed $name Value to get
     *
     * @return boolean
     */
    public function __get($name)
    {
        return $this->isTrue(parent::__get($name));
    }


    /**
     * Find if it is true value
     *
     * @param mixed $value Value of widget parameter
     *
     * @return boolean
     */
    protected function isTrue($value)
    {
        return in_array($value, $this->trueValues, true);
    }
}
