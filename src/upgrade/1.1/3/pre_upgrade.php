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
 */

return function()
{
    // Store the profiles into the temporary YAML file
    \Includes\Utils\Operator::saveServiceYAML(
        LC_DIR_VAR . 'temporary.storage.profiles.yaml',
        array_map(
            function ($address) {
                return array(
                    'address_id'    => $address->getAddressId(),
                    'profile_id'    => $address->getProfile()->getProfileId(),
                    'title'         => $address->getTitle(),
                    'firstname'     => $address->getFirstname(),
                    'lastname'      => $address->getLastname(),
                    'phone'         => $address->getPhone(),
                    'street'        => $address->getStreet(),
                    'city'          => $address->getCity(),
                    'state_id'      => $address->getState()->getStateId(),
                    'custom_state'  => $address->getCustomState(),
                    'country_code'  => $address->getCountry()->getCode(),
                    'zipcode'       => $address->getZipcode(),
                );
            },
            \XLite\Core\Database::getRepo('XLite\Model\Address')->findAll()
        )
    );
};
