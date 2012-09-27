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

namespace XLite\Core\Validator\Pair;

/**
 * Country-state validator
 *
 */
class CountryState extends \XLite\Core\Validator\Pair\APair
{
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
        // Check country
        if (!isset($data['country'])) {
            throw $this->throwError('Country is not defined');
        }

        $countryCodeValidator = new \XLite\Core\Validator\Pair\Simple;
        $countryCodeValidator->setName('country');
        $countryCodeValidator->setValidator(
            new \XLite\Core\Validator\String\ObjectId\Country(true)
        );
        $countryCodeValidator->validate($data);

        // Check custom state flag
        $customState = isset($data['is_custom_state']) ? (bool)$data['is_custom_state'] : false;

        // Check state
        if (!isset($data['state'])) {
            throw $this->throwError('State is not defined');
        }

        $stateValidator = new \XLite\Core\Validator\Pair\Simple;
        $stateValidator->setName('state');
        $stateCellValidator = $customState
            ? new \XLite\Core\Validator\String(true)
            : new \XLite\Core\Validator\String\ObjectId\State(true);
        $stateValidator->setValidator($stateCellValidator);
        $stateValidator->validate($data);

        if (!$customState) {
            $data = $this->sanitize($data);
            if ($data['state']->getCountry()->getCode() != $data['country']->getCode()) {
                throw $this->throwError('Country has not specified state');
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
        // Get country
        $countryCodeValidator = new \XLite\Core\Validator\Pair\Simple;
        $countryCodeValidator->setName('country');
        $countryCodeValidator->setValidator(
            new \XLite\Core\Validator\String\ObjectId\Country(true)
        );
        $country = $countryCodeValidator->getValidator()->sanitize($data['country']);

        // Get state
        $customState = isset($data['is_custom_state']) ? (bool)$data['is_custom_state'] : false;

        if ($customState) {
            $state = new \XLite\Model\State;
            $state->setState($data['state']);
            $state->setCountry($country);

        } else {
            $stateValidator = new \XLite\Core\Validator\String\ObjectId\State(true);
            $state = $stateValidator->sanitize($data['state']);
        }

        return array(
            'country' => $country,
            'state'   => $state,
        );
    }
}
