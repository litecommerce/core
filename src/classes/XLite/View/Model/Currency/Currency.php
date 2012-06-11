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

namespace XLite\View\Model\Currency;

/**
 * Currency model widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Currency extends \XLite\View\Model\AModel
{
    /**
     * Default currency to use if no currency in request is provided
     */
    const DEFAULT_CURRENCY = 'USD';

    /**
     * Schema of the currency section
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $currencySchema = array(
        'currency_id' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\Currency',
            self::SCHEMA_LABEL    => 'Store currency',
            self::SCHEMA_REQUIRED => false,
        ),
        'name' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Name',
            self::SCHEMA_REQUIRED => true,
        ),
        'format' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\CurrencyFormat',
            self::SCHEMA_LABEL    => 'Format',
            self::SCHEMA_REQUIRED => true,
        ),
        'prefix' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Prefix',
            self::SCHEMA_REQUIRED => false,
        ),
        'suffix' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Suffix',
            self::SCHEMA_REQUIRED => false,
        ),
    );

    /**
     * Currency (cache)
     *
     * @var   \XLite\Model\Currency
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $currency = null;

    /**
     * getCurrencySchema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCurrencySchema()
    {
        return $this->currencySchema;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFormFieldsForSectionDefault()
    {
        return $this->getFieldsBySchema($this->getCurrencySchema());
    }

    /**
     * Return currency identificator from the request
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestCurrencyId()
    {
        return \XLite\Core\Request::getInstance()->currency_id;
    }

    /**
     * Return currency identificator for the current model of the form
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrencyId()
    {
        return $this->getRequestCurrencyId() ?: null;
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Currency
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        if (!isset($this->currency)) {

            if (is_null($this->getCurrencyId())) {

                $this->currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->findOneBy(array('code' => static::DEFAULT_CURRENCY));

            } else {

                $this->currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find($this->getCurrencyId());
            }
        }

        return $this->currency;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Currency\Currency';
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubmitButtonLabel()
    {
        return 'Save changes';
    }

    /**
     * Return class of button panel widget
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getButtonPanelClass()
    {
        return 'XLite\View\StickyPanel\Currency';
    }

    /**
     * prepareDataForMapping
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareDataForMapping()
    {
        $data = parent::prepareDataForMapping();

        if (isset($data['format'])) {

            $data = $data + $this->getFormatInfo($data);

            unset($data['format']);
        }

        return $data;
    }

    /**
     * Return format value of currency for format selector (depends on thousand and decimal delimiters)
     *
     * @param array $data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormatInfo(array $data)
    {
        $result = array();

        list(
            $result['thousandDelimiter'],
            $result['decimalDelimiter']
        )= \XLite\View\FormField\Select\CurrencyFormat::getDelimiters($data['format']);

        return $result;
    }

    /**
     * Retrieve property from the model object
     *
     * @param mixed $name Field/property name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModelObjectValue($name)
    {
        $value = parent::getModelObjectValue($name);

        if ('format' == $name) {

            $value = \XLite\View\FormField\Select\CurrencyFormat::getFormat(
                $this->getModelObjectValue('thousandDelimiter'),
                $this->getModelObjectValue('decimalDelimiter')
            );
        }

        return $value;
    }

}
