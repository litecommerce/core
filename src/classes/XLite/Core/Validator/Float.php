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

namespace XLite\Core\Validator;

/**
 * Integer
 *
 */
class Float extends \XLite\Core\Validator\Scalar
{
    /**
     * Range minimum
     *
     * @var float
     */
    protected $min;

    /**
     * Range maximum
     *
     * @var float
     */
    protected $max;

    /**
     * Set range
     *
     * @param float $min Minimum
     * @param float $max Maximum OPTIONAL
     *
     * @return void
     */
    public function setRange($min, $max = null)
    {
        if (isset($min) && is_numeric($min)) {
            $this->min = $min;
        }

        if (isset($max) && is_numeric($max)) {
            $this->max = $max;
        }
    }

    /**
     * Validate
     *
     * @param mixed $data Data
     *
     * @return void
     * @throws \XLite\Core\Validator\Exception
     */
    public function validate($data)
    {
        if (!is_numeric($data)) {
            throw $this->throwError('Not numeric');
        }

        $data = $this->sanitize($data);

        if (isset($this->min) && $data < $this->min) {
            throw $this->throwError('Minimum limit is broken', array('min' => $this->min));
        }

        if (isset($this->max) && $data > $this->max) {
            throw $this->throwError('Maximum limit is broken', array('max' => $this->max));
        }
    }

    /**
     * Sanitaize
     *
     * @param mixed $data Daa
     *
     * @return mixed
     */
    public function sanitize($data)
    {
        return doubleval($data);
    }

}
