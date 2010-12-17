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

namespace XLite\Model;

/**
 * Currency
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity
 * @Table (name="currencies")
 */
class Currency extends \XLite\Model\Base\I18n
{
    /**
     * Currency unique id (ISO 4217 number)
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @Column         (type="uinteger")
     */
    protected $currency_id;

    /**
     * Currency code (ISO 4217 alpha-3)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="fixedstring", length="3", unique=true)
     */
    protected $code;

    /**
     * Symbol
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="16")
     */
    protected $symbol = '';

    /**
     * Number of digits after the decimal separator.
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $e = 0;

    /**
     * Orders
     *
     * @var    \Doctrine\Common\Collections\Collection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Order", mappedBy="currency")
     */
    protected $orders;

    /**
     * Set currency Id 
     * 
     * @param integer $value Currency id
     * TODO - Doctrine is not generate setter for identifier. We must reworkt it
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    /**
     * Round value 
     * 
     * @param float $value Value
     *  
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function roundValue($value)
    {
        return round($value, $this->getE());
    }

    /**
     * Round value as integer
     * 
     * @param float $value Value
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function roundValueAsInteger($value)
    {
        return intval(round($this->roundValue($value) * pow(10, $this->getE())));
    }

    /**
     * Convert integer to float 
     * 
     * @param integer $value Value
     *  
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function convertIntegerToFloat($value)
    {
        return $value / pow(10, $this->getE());
    }

    /**
     * Format value 
     * 
     * @param float $value Value
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function formatValue($value)
    {
        return number_format(
            $this->roundValue($value),
            $this->getE(),
            \XLite\Core\Config::getInstance()->General->decimal_delim,
            \XLite\Core\Config::getInstance()->General->thousand_delim
        );
    }
}
