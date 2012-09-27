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

namespace XLite\Core\Validator\String\ObjectId;

/**
 * Country code
 *
 */
class Country extends \XLite\Core\Validator\String
{
    /**
     * Only-enabled country validation flag
     *
     * @var boolean
     */
    protected $onlyEnabled = false;

    /**
     * Constructor
     *
     * @param boolean $nonEmpty    Non-empty flag OPTIONAL
     * @param boolean $onlyEnabled Only enabled flag OPTIONAL
     *
     * @return void
     */
    public function __construct($nonEmpty = false, $onlyEnabled = false)
    {
        parent::__construct($nonEmpty);

        $this->onlyEnabled = $onlyEnabled;
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
        parent::validate($data);

        if (0 < strlen($data)) {
            $country = $this->sanitize($data);
            if (!$country) {
                throw $this->throwError('Not a country code');

            } else {
                if ($this->onlyEnabled && !$country->getEnabled()) {
                    throw $this->throwError('Country is not enabled');
                }
            }
        }
    }

    /**
     * Sanitize
     *
     * @param mixed $data Daa
     *
     * @return mixed
     */
    public function sanitize($data)
    {
        return 0 < strlen($data)
            ? \XLite\Core\Database::getRepo('XLite\Model\Country')->find($data)
            : null;
    }

}
