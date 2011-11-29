<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\View\Model\Profile;

/**
 * \XLite\View\Model\Profile\AdminMain
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class AdminMain extends \XLite\View\Model\Profile\AdminMain implements \XLite\Base\IDecorator
{
    /**
     * List of fields of the "E-mail & Password" section that must be locked
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $mainSchemaReadonlyFields = array('login');

    /**
     * List of fields of the "E-mail & Password" section that must be removed
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $mainSchemaRemovedFields = array('password', 'password_conf');

    /**
     * List of fields of the "User access" section that must be locked
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $accessSchemaReadonlyFields = array('access_level', 'status');


    /**
     * getDefaultFieldValue
     *
     * @param string $name Field name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDefaultFieldValue($name)
    {
        $value = parent::getDefaultFieldValue($name);

        switch ($name) {

            case 'access_level':
                if (\XLite\Core\Auth::getInstance()->getCustomerAccessLevel() == $value) {
                    $value = static::t('Customer');

                } elseif (\XLite\Core\Auth::getInstance()->getAdminAccessLevel() == $value) {
                    $value = static::t('Administrator');

                } else {
                    $value = static::t('Unknown');
                }

                break;

            case 'status':
                $value = 'E' === $value ? static::t('Enabled') : static::t('Disabled');
                break;

            default:
        }

        return $value;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormFieldsForSectionMain()
    {
        // Get main schema from parent class
        $schema = $this->mainSchema;

        // Exclude fields specified in $mainSchemaRemovedFields
        $schema = \Includes\Utils\ArrayManager::filterByKeys($schema, static::$mainSchemaRemovedFields, true);

        // Lock all fields specified in $mainSchemaReadonlyFields
        array_walk($schema, array($this, 'lockSectionField'), static::$mainSchemaReadonlyFields);

        // Modify the main schema
        $this->mainSchema = $schema;

        return parent::getFormFieldsForSectionMain();
    }

    /**
     * lockSectionField
     *
     * @param array  &$data      Field data
     * @param string $key        Field key
     * @param array  $lockFields Array of elements (keys) that must be locked
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function lockSectionField(&$data, $key, $lockFields)
    {
        if (in_array($key, $lockFields)) {
            $data[self::SCHEMA_CLASS] = '\XLite\View\FormField\Label';
            $data[self::SCHEMA_REQUIRED] = false;
        }
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormFieldsForSectionAccess()
    {
        // Get main schema from parent class
        $schema = $this->accessSchema;

        // Lock all fields specified in $mainSchemaReadonlyFields
        array_walk($schema, array($this, 'lockSectionField'), static::$accessSchemaReadonlyFields);

        // Modify the main schema
        $this->accessSchema = $schema;

        return parent::getFormFieldsForSectionAccess();
    }
}
