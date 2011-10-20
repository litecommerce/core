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

namespace XLite\Core;

/**
 * MySql DBAL platform
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class MySqlPlatform extends \Doctrine\DBAL\Platforms\MySqlPlatform
{
    /**
     * Get boolean-type declaration SQL
     *
     * @param array $field Field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBooleanTypeDeclarationSQL(array $field)
    {
        return 'TINYINT(1) UNSIGNED';
    }

    /**
     * Get binary type declaratio nSQL 
     * 
     * @param array $field Field declaration
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBinaryTypeDeclarationSQL(array $field)
    {
        if (!isset($field['length'])) {
            $field['length'] = $this->getVarcharDefaultLength();
        }

        $fixed = (isset($field['fixed'])) ? $field['fixed'] : false;

        return $field['length'] > $this->getVarcharMaxLength()
            ? $this->getClobTypeDeclarationSQL($field)
            : $this->getBinaryTypeDeclarationSQLSnippet($field['length'], $fixed);
    }

    /**
     * Get binary type declaration SQL snippet 
     * 
     * @param integer $length Field length
     * @param boolean $fixed  Fixed type flag
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBinaryTypeDeclarationSQLSnippet($length, $fixed)
    {
        return ($fixed ? '' : 'VAR') . 'BINARY(' . ($length ?: 255) . ')';
    }

}
