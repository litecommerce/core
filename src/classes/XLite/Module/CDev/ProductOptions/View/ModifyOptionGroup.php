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

namespace XLite\Module\CDev\ProductOptions\View;

/**
 * Modify option group
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ModifyOptionGroup extends \XLite\View\AView
{
    /**
     * Option group (cache)
     *
     * @var   \XLite\Module\CDev\ProductOptions\Model\OptionGroup
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $group;

    /**
     * Options list (cache)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $options;


    /**
     * Check - is option group createion procedure or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isNew()
    {
        return '0' === strval(\XLite\Core\Request::getInstance()->groupId);
    }

    /**
     * Get product id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProductId()
    {
        return intval(\XLite\Core\Request::getInstance()->product_id);
    }

    /**
     * Get group id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getGroupId()
    {
        return $this->isNew()
            ? 0
            : $this->getGroup()->getGroupId();
    }

    /**
     * Get option group translation storage
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionGroupTranslation
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTranslation()
    {
        return $this->getGroup()->getSoftTranslation(
            \XLite::getController()->getCurrentLanguage()
        );
    }

    /**
     * Get options groups list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptions()
    {
        if (!isset($this->options)) {
            $this->options = $this->getGroup()->getOptions();
        }

        return $this->options;
    }

    /**
     * Check if theare are any options created for the group
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasOptions()
    {
        return 0 < count($this->getOptions());
    }

    /**
     * Get option translation storage
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\Option $option Option
     * @param string                                         $field  Field name
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionTranslation
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptionTranslation(\XLite\Module\CDev\ProductOptions\Model\Option $option, $field)
    {
        return $option->getSoftTranslation(\XLite::getController()->getCurrentLanguage())
            ->$field;
    }

    /**
     * Get option modifier types names
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModifiersNames()
    {
        return array(
            'price'  => 'Price modifier',
            'weight' => 'Weight modifier',
        );
    }

    /**
     * Get option modifiers
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\Option $option Options OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptionModifiers(\XLite\Module\CDev\ProductOptions\Model\Option $option = null)
    {
        $keys = array_keys($this->getModifiersNames());

        $result = array();

        foreach ($keys as $key) {
            if ($option) {
                $result[$key] = $option->getSurcharge($key);
            }

            if (!isset($result[$key]) || !$result[$key]) {
                $result[$key] = new \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge;
                $result[$key]->setType($key);
            }
        }

        return $result;
    }

    /**
     * Get option group types
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptionGroupTypes()
    {
        return array(
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE => 'Options group',
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::TEXT_TYPE  => 'Text option',
        );
    }

    /**
     * Get option group view types
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptionGroupViewTypes()
    {
        return array(
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::SELECT_VISIBLE   => 'Select box',
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::RADIO_VISIBLE    => 'Radio buttons list',
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::TEXTAREA_VISIBLE => 'Textarea',
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::INPUT_VISIBLE    => 'Input box',
        );
    }

    /**
     * Get option surcharge modifier types
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptionSurchargeModifierTypes()
    {
        return array(
            \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge::PERCENT_MODIFIER  => 'Percent',
            \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge::ABSOLUTE_MODIFIER => 'Absolute',
        );
    }

    /**
     * Get option group
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionGroup
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getGroup()
    {
        if (!isset($this->group)) {
            if (\XLite\Core\Request::getInstance()->groupId) {
                $this->group = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
                    ->find(\XLite\Core\Request::getInstance()->groupId);
            }

            if (!$this->group) {
                $this->group = new \XLite\Module\CDev\ProductOptions\Model\OptionGroup;
            }
        }

        return $this->group;
    }

    /**
     * Return JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.5
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'modules/CDev/ProductOptions/option_group.js';

        return $list;
    }

    /**
     * Return JS view type option array
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.5
     */
    protected function getJSViewTypeOptions()
    {
        $jsCode = '';

        $optionGroupTypes = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
            ->getOptionGroupTypes();

        foreach ($optionGroupTypes as $dataType => $view) {
            $jsCode .= 'lcViewTypeOption.' . $dataType . ' = ' . json_encode($view['views']) . ';' . PHP_EOL;
        }

        return $jsCode;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/ProductOptions/option_group.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && ($this->getGroup()->getGroupId() || $this->isNew());
    }
}
