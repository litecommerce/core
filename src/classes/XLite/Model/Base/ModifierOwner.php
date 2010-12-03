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

namespace XLite\Model\Base;

/**
 * Modifier owner 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * MappedSuperclass
 */
abstract class ModifierOwner extends \XLite\Model\AEntity
{
    /**
     * Modifier list cell keys 
     */
    const MODIFIER_ATTR_CALCULATOR   = 'calculator';
    const MODIFIER_ATTR_VISIBILITY   = 'visibility';
    const MODIFIER_ATTR_NAME         = 'name';
    const MODIFIER_ATTR_AVAILABILITY = 'availability';
    const MODIFIER_ATTR_SUMMABLE     = 'summable';


    /**
     * Total 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="14", scale="4")
     */
    protected $total = 0.0000;

    /**
     * Subtotal 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="14", scale="4")
     */
    protected $subtotal = 0.0000;

    /**
     * Modifiers (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $modifiers;

    /**
     * Get modifiers 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModifiers()
    {
        if (!isset($this->modifiers)) {

            $this->modifiers = array();

            $secondaryProperties = array(
                self::MODIFIER_ATTR_VISIBILITY,
                self::MODIFIER_ATTR_NAME,
                self::MODIFIER_ATTR_AVAILABILITY,
                self::MODIFIER_ATTR_SUMMABLE,
            );

            $codes = $this->defineModifiers();
            ksort($codes, SORT_NUMERIC);

            foreach ($codes as $code) {
                $prefix = \XLite\Core\Converter::convertToCamelCase($code);
                $cell = array(
                    self::MODIFIER_ATTR_CALCULATOR   => 'calculate' . $prefix,
                    self::MODIFIER_ATTR_VISIBILITY   => 'is' . $prefix . 'Visible',
                    self::MODIFIER_ATTR_NAME         => 'get' . $prefix . 'Name',
                    self::MODIFIER_ATTR_AVAILABILITY => 'is' . $prefix . 'Available',
                    self::MODIFIER_ATTR_SUMMABLE     => 'is' . $prefix . 'Summable',
                );

                if (method_exists($this, $cell[self::MODIFIER_ATTR_CALCULATOR])) {
    
                    foreach ($secondaryProperties as $property) {
                        if (!method_exists($this, $cell[$property])) {
                            $cell[$property] = null;
                        }
                    }

                    $this->modifiers[$code] = $cell;
                }
            }
        }


        return $this->modifiers;
    }

    /**
     * Define order modifiers 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineModifiers()
    {
        return array();
    }

    /**
     * Get visible saved modifiers 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getVisibleSavedModifiers()
    {
        $list = array();

        foreach ($this->getSavedModifiers() as $m) {
            if ($m->getIsVisible()) {
                $list[] = $m;
            }
        }

        return $list;
    }

    /**
     * Get summable saved modifiers list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSummableSavedModifiers()
    {
        $list = array();

        foreach ($this->getSavedModifiers() as $m) {
            if ($m->getIsSummable()) {
                $list[] = $m;
            }
        }

        return $list;
    }

    /**
     * Get total by modifier 
     * 
     * @param string $code Modifier code
     *  
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTotalByModifier($code)
    {
        $total = 0;

        foreach ($this->getSavedModifiers() as $m) {
            if ($m->getCode() == $code && $m->getIsSummable()) {
                $total += $m->getSurcharge();
            }
        }

        return $total;
    }

    /**
     * Get calculated total 
     * 
     * @return float
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCalculatedTotal()
    {
        $total = $this->getSubtotal();

        foreach ($this->getSummableSavedModifiers() as $m) {
            $total += $m->getSurcharge();
        }

        return $total;
    }

    /**
     * Save modifier 
     * 
     * @param string $code    Modifier code
     * @param float  $value   Value
     * @param string $subcode Saved modifier code OPTIONAL
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveModifier($code, $value, $subcode = null)
    {
        $list = $this->getModifiers();

        $result = false;

        if (isset($list[$code])) {
            $cell = $list[$code];

            if (!$subcode) {
                $subcode = $code;
            }

            $modifier = new \XLite\Model\OrderModifier();

            $modifier->setCode($code);
            $modifier->setSubcode($subcode);
            $modifier->setSurcharge(doubleval($value));
            if (isset($cell[self::MODIFIER_ATTR_NAME])) {
                $modifier->setName($this->{$cell[self::MODIFIER_ATTR_NAME]}($subcode));

            } else {
                $modifier->setName($subcode);
            }
            $modifier->setIsVisible(
                isset($cell[self::MODIFIER_ATTR_VISIBILITY])
                && $this->{$cell[self::MODIFIER_ATTR_VISIBILITY]}($subcode)
            );
            $modifier->setIsSummable(
                !isset($cell[self::MODIFIER_ATTR_SUMMABLE])
                || $this->{$cell[self::MODIFIER_ATTR_SUMMABLE]}($subcode)
            );

            $modifier->setOwner($this); 
            $this->getSavedModifiers()->add($modifier);

            \XLite\Core\Database::getEM()->persist($modifier);

            $result = true;
        }

        return $result;
    }

    /**
     * Unset modifier 
     * 
     * @param string $code Modifier code
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function unsetModifier($code)
    {
        if (isset($this->modifiers[$code])) {
            unset($this->modifiers[$code]);
        }
    }

    /**
     * Calculation
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculate()
    {
        // Clear modifiers cache
        $this->modifiers = null;

        // Clear old saved modifiers
        foreach ($this->getSavedModifiers() as $modifier) {
            if (\XLite\Core\Database::getEM()->contains($modifier)) {
                \XLite\Core\Database::getEM()->remove($modifier);
            }
        }

        $this->getSavedModifiers()->clear();

        $this->calculateSubtotal();

        // Calculate order modifiers
        foreach ($this->getModifiers() as $cell) {
            $this->{$cell[self::MODIFIER_ATTR_CALCULATOR]}();
        }

        // Summarize and save total
        $this->setTotal($this->getCalculatedTotal());
    }

    /**
     * Calculate and save subtotal 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function calculateSubtotal();
}
