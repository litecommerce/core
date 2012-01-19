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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Logic;

/**
 * Mathematic
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Math extends \XLite\Logic\ALogic
{
    /**
     * Storage precision 
     */
    const STORE_PRECISION = 4;

    // {{{ Round

    /**
     * Round
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function round($value, $precision = 0)
    {
        return $this->roundMath($value, $precision);
    }

    /**
     * Round by currency
     *
     * @param float                 $value    Value
     * @param \XLite\Model\Currency $currency Currency
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundByCurrency($value, \XLite\Model\Currency $currency)
    {
        return $this->round($value, $currency->getE());
    }

    /**
     * Round and format by currency
     *
     * @param float                 $value    Value
     * @param \XLite\Model\Currency $currency Currency
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function formatValue($value, \XLite\Model\Currency $currency)
    {
        $config = \XLite\Core\Config::getInstance();

        return number_format(
            $this->roundByCurrency($value, $currency),
            $config->General->decimal_delim ? $currency->getE() : 0,
            $config->General->decimal_delim,
            $config->General->thousand_delim
        );
    }

    /**
     * Round up
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundUp($value, $precision = 0)
    {
        $multi = pow(10, $precision);

        return ceil($value * $multi) / $multi;
    }

    /**
     * Round down
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundDown($value, $precision = 0)
    {
        $multi = pow(10, $precision);

        return floor($value * $multi) / $multi;
    }

    /**
     * Round up (ceiling)
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundCeil($value, $precision = 0)
    {
        return 0 > $value
            ? $this->roundDown($value, $precision)
            : $this->roundUp($value, $precision);
    }

    /**
     * Round down (floor)
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundFloor($value, $precision = 0)
    {
        return 0 > $value
            ? $this->roundUp($value, $precision)
            : $this->roundDown($value, $precision);
    }

    /**
     * Round (half up)
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundHalfUp($value, $precision = 0)
    {
        return $this->isRoundHalf($value, $precision)
            ? $this->roundCeil($value, $precision)
            : $this->roundMath($value, $precision);
    }

    /**
     * Round (half down)
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundHalfDown($value, $precision = 0)
    {
        return $this->isRoundHalf($value, $precision)
            ? $this->roundFloor($value, $precision)
            : $this->roundMath($value, $precision);
    }

    /**
     * Round (half even)
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundHalfEven($value, $precision = 0)
    {
        if ($this->isRoundHalf($value, $precision)) {
            $result = $this->isRoundEven($value, $precision)
                ? $this->roundFloor($value, $precision)
                : $this->roundCeil($value, $precision);

        } else {
            $result = $this->roundMath($value, $precision);
        }

        return $result;
    }

    /**
     * Round (standard)
     *
     * @param float   $value     Value
     * @param integer $precision Precision OPTIONAL
     *
     * @return float|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundMath($value, $precision = 0)
    {
        return round($value, $precision);
    }

    /**
     * Check - value is half-based or not
     *
     * @param float   $value     Value
     * @param integer $precision Precision
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isRoundHalf($value, $precision)
    {
        $result = false;

        $value -= floor($value);
        if (0 != $value) {
            $result = (bool)preg_match('/^50*$/Ss', substr($value, $precision + 2));
        }

        return $result;
    }

    /**
     * Check - value is even or not
     *
     * @param float   $value     Value
     * @param integer $precision Precision
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isRoundEven($value, $precision)
    {
        return 0 == floor($value * pow(10, $precision)) % 2;
    }

    // }}}
}
