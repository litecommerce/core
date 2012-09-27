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

namespace XLite\Logic;

/**
 * Price 
 * 
 */
class Price extends \XLite\Logic\ALogic
{
    /**
     * Modifiers 
     * 
     * @var array
     */
    protected $modifiers;

    /**
     * Apply price modifiers
     * 
     * @param \XLite\Model\AEntity $model     Model
     * @param string               $method    Model's getter
     * @param array                $behaviors Behaviors OPTIONAL
     * @param string               $purpose   Purpose OPTIONAL
     *  
     * @return float
     */
    public function apply(\XLite\Model\AEntity $model, $method, array $behaviors = array(), $purpose = 'net')
    {
        $property = lcfirst(substr($method, 3));
        $value = $model->$method();

        $modifiers = $this->prepareModifiers($this->getModifiers(), $behaviors, $purpose);
        foreach ($modifiers as $modifier) {
            $value = $modifier->apply($value, $model, $property, $behaviors, $purpose);
        }

        return $value;
    }

    /**
     * Get modifiers 
     * 
     * @return array
     */
    protected function getModifiers()
    {
        if (!isset($this->modifiers)) {
            $this->modifiers = $this->defineModifiers();
        }

        return $this->modifiers;
    }

    /**
     * Define modifiers 
     * 
     * @return array
     */
    protected function defineModifiers()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\MoneyModificator')->findActive();
    }

    /**
     * Prepare modifiers 
     * 
     * @param array  $modifiers Modifiers list
     * @param array  $behaviors Behaviors
     * @param string $purpose   Purpose
     *  
     * @return array
     */
    protected function prepareModifiers(array $modifiers, array $behaviors, $purpose)
    {
        foreach($modifiers as $i => $modifier) {
            if (
                ($modifier->getPurpose() && $modifier->getPurpose() != $purpose)
                || ($modifier->getBehavior() && !in_array($modifier->getBehavior(), $behaviors))
            ) {
                unset($modifiers[$i]);
            }
        }

        return $modifiers;
    }
}

