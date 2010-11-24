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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductOptions\View;

/**
 * Modify option group
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ModifyOptionGroup extends \XLite\View\AView
{
    /**
     * Option group (cache)
     * 
     * @var    \XLite\Module\ProductOptions\Model\OptionGroup
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $group;

    /**
     * Options list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/option_group.tpl';
    }

    /**
     * Get option group 
     * 
     * @return \XLite\Module\ProductOptions\Model\OptionGroup
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGroup()
    {
        if (!isset($this->group)) {
            if (\XLite\Core\Request::getInstance()->groupId) {
                $this->group = \XLite\Core\Database::getRepo('\XLite\Module\ProductOptions\Model\OptionGroup')
                    ->find(\XLite\Core\Request::getInstance()->groupId);
            }

            if (!$this->group) {
                $this->group = new \XLite\Module\ProductOptions\Model\OptionGroup;
            }
        }

        return $this->group;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && ($this->getGroup()->getGroupId() || $this->isNew());
    }

    /**
     * Check - is option group createion procedure or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isNew()
    {
        return '0' === strval(\XLite\Core\Request::getInstance()->groupId);
    }

    /**
     * Get product id 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductId()
    {
        return intval(\XLite\Core\Request::getInstance()->product_id);
    }

    /**
     * Get group id 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @return \XLite\Module\ProductOptions\Model\OptionGroupTranslation
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions()
    {
        if (!isset($this->options)) {
            $this->options = $this->getGroup()->getOptions();
        }

        return $this->options;
    }

    /**
     * Get option translation storage
     *
     * @param \XLite\Module\ProductOptions\Model\Option $option Option
     * @param string                                    $field  Field name
     *
     * @return \XLite\Module\ProductOptions\Model\OptionTranslation
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptionTranslation(\XLite\Module\ProductOptions\Model\Option $option, $field)
    {
        return $option->getSoftTranslation(\XLite::getController()->getCurrentLanguage())
            ->$field;
    }

    /**
     * Get option modifier types names 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param \XLite\Module\ProductOptions\Model\Option $option Options
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptionModifiers(\XLite\Module\ProductOptions\Model\Option $option = null)
    {
        $keys = array_keys($this->getModifiersNames());

        $result = array();

        foreach ($keys as $key) {
            if ($option) {
                $result[$key] = $option->getSurcharge($key);
            }

            if (!isset($result[$key]) || !$result[$key]) {
                $result[$key] = new \XLite\Module\ProductOptions\Model\OptionSurcharge;
                $result[$key]->setType($key);
            }
        }

        return $result;
    }

    /**
     * Get option group types 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptionGroupTypes()
    {
        return array(
            \XLite\Module\ProductOptions\Model\OptionGroup::GROUP_TYPE => 'Options group',
            \XLite\Module\ProductOptions\Model\OptionGroup::TEXT_TYPE  => 'Text option',
        );
    }

    /**
     * Get option group view types 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptionGroupViewTypes()
    {
        return array(
            \XLite\Module\ProductOptions\Model\OptionGroup::SELECT_VISIBLE   => 'Select box',
            \XLite\Module\ProductOptions\Model\OptionGroup::RADIO_VISIBLE    => 'Radio buttons list',
            \XLite\Module\ProductOptions\Model\OptionGroup::TEXTAREA_VISIBLE => 'Textarea',
            \XLite\Module\ProductOptions\Model\OptionGroup::INPUT_VISIBLE    => 'Input box',
        );
    }

    /**
     * Get option surcharge modifier types 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptionSurchargeModifierTypes()
    {
        return array(
            \XLite\Module\ProductOptions\Model\OptionSurcharge::PERCENT_MODIFIER  => 'Percent',
            \XLite\Module\ProductOptions\Model\OptionSurcharge::ABSOLUTE_MODIFIER => 'Absolute',
        );
    }

}
