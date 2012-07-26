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

namespace XLite\View\Model\Currency;

/**
 * Currency model widget
 *
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
     * @var array
     */
    protected $currencySchema = array(
        'trailing_zeroes' => array(
            self::SCHEMA_CLASS      => '\XLite\View\FormField\Input\Checkbox',
            self::SCHEMA_LABEL      => 'Hide trailing zeros in fractional part',
            self::SCHEMA_REQUIRED   => false,
        ),
        'currency_id' => array(
            self::SCHEMA_CLASS      => '\XLite\View\FormField\Select\CurrencyRich',
            self::SCHEMA_LABEL      => 'Store currency',
            self::SCHEMA_REQUIRED   => false,
            self::SCHEMA_ATTRIBUTES => array('data-filter' => '1'),
        ),
        'format' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\CurrencyFormat',
            self::SCHEMA_LABEL    => 'Format',
            self::SCHEMA_REQUIRED => false,
        ),
        'prefix' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Prefix',
            self::SCHEMA_REQUIRED => false,
            self::SCHEMA_MODEL_ATTRIBUTES => array(
                \XLite\View\FormField\Input\Base\String::PARAM_MAX_LENGTH => 'length',
            ),
        ),
        'suffix' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Suffix',
            self::SCHEMA_REQUIRED => false,
            self::SCHEMA_MODEL_ATTRIBUTES => array(
                \XLite\View\FormField\Input\Base\String::PARAM_MAX_LENGTH => 'length',
            ),
        ),
    );

    /**
     * Currency (cache)
     *
     * @var \XLite\Model\Currency
     */
    protected $currency = null;

    /**
     * getCurrencySchema
     *
     * @return array
     */
    public function getCurrencySchema()
    {
        $e = $this->getDefaultModelObject()->getE();

        $this->currencySchema['format'][\XLite\View\FormField\Select\CurrencyFormat::PARAM_E] = $e;

        $this->currencySchema['format'][static::SCHEMA_ATTRIBUTES] = array(
            'data-e'            => $e,
            'data-thousandPart' => \XLite\View\FormField\Select\CurrencyFormat::THOUSAND_PART,
            'data-hundredsPart' => \XLite\View\FormField\Select\CurrencyFormat::HUNDRENDS_PART,
            'data-delimiter'    => \XLite\View\FormField\Select\CurrencyFormat::FORMAT_DELIMITER,
        );

        $this->currencySchema['trailing_zeroes'][\XLite\View\FormField\Input\Checkbox::PARAM_IS_CHECKED]
            = (1 == \XLite\Core\Config::getInstance()->General->trailing_zeroes);

        return $this->currencySchema;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     */
    public function getFormFieldsForSectionDefault()
    {
        return $this->getFieldsBySchema($this->getCurrencySchema());
    }

    /**
     * getDefaultFieldValue
     *
     * @param string $name Field name
     *
     * @return mixed
     */
    public function getDefaultFieldValue($name)
    {
        $value = parent::getDefaultFieldValue($name);

        switch ($name) {

            case 'trailing_zeroes':
                $value = 1;
                break;

            default:
        }

        return $value;
    }

    /**
     * This object will be used if another one is not passed
     *
     * @return \XLite\Model\Currency
     */
    protected function getDefaultModelObject()
    {
        if (!isset($this->currency)) {

            $this->currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')
                ->find(\XLite\Core\Config::getInstance()->General->shop_currency);
        }

        return $this->currency;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Currency\Currency';
    }

    /**
     * Return specific CSS class for dialog wrapper
     *
     * @return string
     */
    protected function getDialogCSSClass()
    {
        return parent::getDialogCSSClass() . ' currency-model-form-dialog';
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     */
    protected function getSubmitButtonLabel()
    {
        return 'Save changes';
    }

    /**
     * Return class of button panel widget
     *
     * @return string
     */
    protected function getButtonPanelClass()
    {
        return 'XLite\View\StickyPanel\Currency';
    }

    /**
     * Prepare request data for mapping into model object.
     * Model object is provided with methods:
     * prepareObjectForMapping <- getModelObject <- getDefaultModelObject (or getParam(self::PARAM_MODEL_OBJECT))
     *
     * @return array
     */
    protected function prepareDataForMapping()
    {
        $data = parent::prepareDataForMapping();

        // Update trailing zeroes config option value
        // TODO? move it to separated method ?
        $trailingZeroes = \XLite\Core\Database::getRepo('XLite\Model\Config')
            ->findOneBy(array('name' => 'trailing_zeroes', 'category' => 'General'));

        \XLite\Core\Database::getRepo('XLite\Model\Config')->update(
            $trailingZeroes,
            array('value' => '1' == $data['trailing_zeroes'])
        );

        // We do not map "trailing zeroes" - it is a config option.
        unset($data['trailing_zeroes']);

        if (isset($data['format'])) {

            // Data format is divided into thousand and decimal separator (or any other if it would be necessary)
            $data = $data + $this->getFormatInfo($data);

            unset($data['format']);
        }

        return $data;
    }

    /**
     * Return format value of currency for format selector (depends on thousand and decimal delimiters)
     *
     * @param array $data return the array of the following format:
     *                    array('thousandDelimiter' => $thousandDelimiter, 'decimalDelimiter' => $decimalDelimiter)
     *
     * @return array
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
     */
    protected function getModelObjectValue($name)
    {
        $value = parent::getModelObjectValue($name);

        if ('format' == $name) {

            $value = \XLite\View\FormField\Select\CurrencyFormat::getFormat(
                $this->getModelObjectValue('thousandDelimiter'),
                $this->getModelObjectValue('decimalDelimiter')
            );

        } elseif ('trailing_zeroes' == $name) {

            $value = ('' !== \XLite\Core\Config::getInstance()->General->trailing_zeroes);
        }

        return $value;
    }

}
