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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductOptions\Model;

/**
 * Product
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Option groups (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Module\ProductOptions\Model\OptionGroup", mappedBy="product", cascade={"all"})
     */
    protected $optionGroups;

    /**
     * Product options list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $productOptions = null;

    /**
     * Check - has product options list or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasOptions()
    {
        return 0 < count($this->optionGroups);
    }

    /**
     * Get product options list
     * 
     * @return array(\XLite\Module\ProductOptions\Model\OptionGroup)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getActiveOptions()
    {
        if (!isset($this->productOptions)) {
            $this->productOptions = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
                ->findActiveByProductId($this->getProductId());
        }

        return $this->productOptions;
    }

    /**
     * Check - display price modifier or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayPriceModifier()
    {
        return true;
    }

    /**
     * Prepare options 
     * 
     * @param array $options Request-based selected options
     *  
     * @return array or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function prepareOptions(array $options)
    {
        $prepared = array();
        foreach ($options as $groupId => $data) {
            $optionGroup = \XLite\Core\Database::getRepo('\XLite\Module\ProductOptions\Model\OptionGroup')
                ->findOneByGroupIdAndProductId($groupId, $this->getProductId());

            if (!isset($optionGroup)) {
                $prepared = null;
                break;
            }

            if ($optionGroup->getType() == $optionGroup::GROUP_TYPE) {
                $option = \XLite\Core\Database::getRepo('\XLite\Module\ProductOptions\Model\Option')
                    ->find(intval($data));
                if (
                    !$option
                    || $option->getGroup()->getGroupId() != $optionGroup->getGroupId()
                ) {
                    $prepared = null;
                    break;
                }

                $prepared[$optionGroup->getGroupId()] = array(
                    'option' => $option,
                    'value'  => intval($data),
                );

            } else {
                $prepared[$optionGroup->getGroupId()] = array(
                    'option' => null,
                    'value'  => $data,
                );
            }
        }

        // Update list from default list
        if (is_array($prepared)) {
            foreach ($this->getDefaultProductOptions() as $groupId => $data) {
                if (!isset($prepared[$groupId])) {
                    $prepared[$groupId] = $data;
                }
            }
        }

        return $prepared;
    }

    /**
     * Get default product options 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultProductOptions()
    {
        $list = $this->getActiveOptions();
        $ids = array();
        foreach ($list as $optionGroup) {
            if ($optionGroup::GROUP_TYPE == $optionGroup->getType()) {
                $ids[$optionGroup->getGroupId()] = array(
                    'start' => 0,
                    'limit' => count($optionGroup->getActiveOptions()) - 1,
                );
            }
        }

        $cnt = 0;
        do {
            $cntCurrent = $cnt;
            foreach ($ids as $i => $id) {
                if ($id['limit'] > $cntCurrent) {
                    $ids[$i]['start'] = $cntCurrent;
                    $cntCurrent = 0;

                } else {
                    $ids[$i]['start'] = $id['limit'];
                    $cntCurrent -= $id['limit'];
                }
            }

            $options = array();
            foreach ($list as $optionGroup) {
                $option = $optionGroup::GROUP_TYPE == $optionGroup->getType()
                    ? $optionGroup->getDefaultOption($ids[$optionGroup->getGroupId()]['start'])
                    : null;

                $value = $optionGroup->getDefaultPlainValue(
                    isset($ids[$optionGroup->getGroupId()]) ? $ids[$optionGroup->getGroupId()]['start'] : 0
                );

                $options[$optionGroup->getGroupId()] = array(
                    'option' => $option,
                    'value'  => $value,
                );
            }
            $cnt++;

        } while (!$this->checkOptionsException($options));

        return $options;
    }

    /**
     * Check options exception 
     * 
     * @param array $options Prepared array
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkOptionsException(array $options)
    {
        $ids = array();

        foreach ($options as $groupId => $data) {
            if (isset($data['option'])) {
                $ids[] = $data['option']->getOptionId();
            }
        }

        return \XLite\Core\Database::getRepo('\XLite\Module\ProductOptions\Model\OptionException')
            ->checkOptions($ids);
    }
}
