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

namespace XLite\Core\ColumnType;

/**
 * Money (value without currency)
 *
 */
class Money extends \Doctrine\DBAL\Types\DecimalType
{
    /**
     * Get SQL declaration
     *
     * @param array                                     $fieldDeclaration Field declaration
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform         Platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
//        if (!isset($fieldDeclaration['precision'])) {
            $fieldDeclaration['precision'] = 14;
//        }

//        if (!isset($fieldDeclaration['scale'])) {
            $fieldDeclaration['scale'] = 4;
//        }

        return parent::getSQLDeclaration($fieldDeclaration, $platform);
    }

    /**
     * Convert to PHP value
     *
     * @param string                                    $value    Value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform Platform
     *
     * @return float
     */
    public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return (null === $value) ? null : sprintf('%.4f', $value);
    }

}
