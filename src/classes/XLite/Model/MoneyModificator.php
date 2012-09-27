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

namespace XLite\Model;

/**
 * Money modificator
 * 
 * @Entity
 * @Table  (name="money_modificators")
 */
class MoneyModificator extends \XLite\Model\AEntity
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
     * Class name
     * 
     * @var string
     *
     * @Column (type="string")
     */
    protected $class;

    /**
     * Method-modificator 
     * 
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $modificator = 'modifyMoney';

    /**
     * Method-validator 
     * 
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $validator = '';

    /**
     * Position 
     * 
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $position = 0;

    /**
     * Behavior limitation
     * 
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $behavior = '';

    /**
     * Purpose limitation
     * 
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $purpose = '';

    /**
     * Apply 
     * 
     * @param float                $value     Property value
     * @param \XLite\Model\AEntity $model     Model
     * @param string               $property  Model's property
     * @param array                $behaviors Behaviors
     * @param string               $purpose   Purpose
     *  
     * @return float
     */
    public function apply($value, \XLite\Model\AEntity $model, $property, array $behaviors, $purpose)
    {
        $class = $this->getClass();

        if (\XLite\Core\Operator::isClassExists($class)) {

            $validationMethod = $this->getValidator();
            $calculateMethod = $this->getModificator();

            if (!$validationMethod || $class::$validationMethod($model, $property, $behaviors, $purpose)) {
                $value = $class::$calculateMethod($value, $model, $property, $behaviors, $purpose);
            }
        }

        return $value;
    }
}
