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

namespace XLite\Module\CDev\ProductOptions\View;

/**
 * Modify option group
 *
 */
class ModifyOptionGroup extends \XLite\View\AView
{
    /**
     * Option group (cache)
     *
     * @var \XLite\Module\CDev\ProductOptions\Model\OptionGroup
     */
    protected $group;

    /**
     * Options list (cache)
     *
     * @var array
     */
    protected $options;


    /**
     * Check - is option group createion procedure or not
     *
     * @return boolean
     */
    public function isNew()
    {
        return '0' === strval(\XLite\Core\Request::getInstance()->groupId);
    }

    /**
     * Get product id
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->getProduct()->getProductId();
    }

    /**
     * Get group id
     *
     * @return integer
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
                $result[$key] = new \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge();
                $result[$key]->setType($key);
            }
        }

        return $result;
    }

    /**
     * Get option group types
     *
     * @return array
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
     */
    protected function getJSViewTypeOptions()
    {
        $jsCode = '';

        $optionGroupTypes = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
            ->getOptionGroupTypes();

        foreach ($optionGroupTypes as $dataType => $view) {
            $jsCode .= 'lcViewTypeOptions.' . $dataType . ' = ' . json_encode($view['views']) . ';' . PHP_EOL;
        }

        return $jsCode;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/ProductOptions/option_group.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && ($this->getGroup()->getGroupId() || $this->isNew());
    }

    /**
     * Return label for the header
     *
     * @return string
     */
    protected function getHeadLabel()
    {
        return $this->isNew() 
            ? 'Add new option group' 
            : static::t('Modify "{{name}}" option group', array('name' => $this->getGroup()->getName()));
    }
}
