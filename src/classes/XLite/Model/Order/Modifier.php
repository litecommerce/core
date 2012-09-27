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

namespace XLite\Model\Order;

/**
 * Order modifier
 *
 *
 * @Entity
 * @Table (name="order_modifiers")
 */
class Modifier extends \XLite\Model\AEntity
{
    /**
     * ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Logic class name
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $class;

    /**
     * Weight
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $weight = 0;

    /**
     * Modifier object (cache)
     *
     * @var \XLite\Logic\Order\Modifier\AModifier
     */
    protected $modifier;

    /**
     * Magic call
     *
     * @param string $method Method name
     * @param array  $args   Arguments list OPTIONAL
     *
     * @return mixed
     */
    public function __call($method, array $args = array())
    {
        $modifier = $this->getModifier();

        return ($modifier && method_exists($modifier, $method))
            ? call_user_func_array(array($modifier, $method), $args)
            : parent::__call($method, $args);
    }

    /**
     * Get modifier object
     *
     * @return \XLite\Logic\Order\Modifier\AModifier
     */
    public function getModifier()
    {
        if (!isset($this->modifier) && \XLite\Core\Operator::isClassExists($this->getClass())) {
            $class = $this->getClass();
            $this->modifier = new $class($this);
        }

        return $this->modifier;
    }
}
