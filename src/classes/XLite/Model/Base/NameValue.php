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

namespace XLite\Model\Base;

/**
 * Name-value abstract storage
 * 
 *
 * @MappedSuperclass
 */
abstract class NameValue extends \XLite\Model\AEntity
{

    /**
     * Unique ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="uinteger")
     */
    protected $id;

    /**
     * Parameter name 
     * 
     * @var string
     *
     * @Column (type="string")
     */
    protected $name;

    /**
     * Semi-serialized parameter value representation
     * 
     * @var string
     *
     * @Column (type="text")
     */
    protected $value;

    /**
     * Get parameter value
     *
     * @return mixed
     */
    public function getValue()
    {
        $value = @unserialize($this->value);

        return false === $value ? $this->value : $value;
    }

    /**
     * Set parameter value
     *
     * @param mixed $value Parameter value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = is_scalar($value) ? $value : serialize($value);
    }
}
