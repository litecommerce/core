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

namespace XLite\Module\CDev\ProductOptions\Model;

/**
 * Product
 *
 */
class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Option groups (relation)
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Module\CDev\ProductOptions\Model\OptionGroup", mappedBy="product", cascade={"all"})
     */
    protected $optionGroups;

    /**
     * Product options list (cache)
     *
     * @var array
     */
    protected $productOptions = null;


    /**
     * Check - has product options list or not
     *
     * @return boolean
     */
    public function hasOptions()
    {
        return 0 < count($this->optionGroups);
    }

    /**
     * Get product options list
     *
     * @return array(\XLite\Module\CDev\ProductOptions\Model\OptionGroup)
     */
    public function getActiveOptions()
    {
        if (!isset($this->productOptions)) {
            $this->productOptions = \XLite\Core\Database::getRepo('XLite\Module\CDev\ProductOptions\Model\OptionGroup')
                ->findActiveByProductId($this->getProductId());
        }

        return $this->productOptions;
    }

    /**
     * Check - display price modifier or not
     *
     * @return boolean
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
     * @return array|void
     */
    public function prepareOptions(array $options)
    {
        $prepared = array();
        foreach ($options as $groupId => $data) {
            $optionGroup = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
                ->findOneByGroupIdAndProductId($groupId, $this->getProductId());

            if (!isset($optionGroup)) {
                $prepared = null;
                break;
            }

            if ($optionGroup->getType() == $optionGroup::GROUP_TYPE) {
                $option = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\Option')
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
     */
    public function checkOptionsException(array $options)
    {
        $ids = array();

        foreach ($options as $groupId => $data) {
            if (isset($data['option'])) {
                $ids[] = $data['option']->getOptionId();
            }
        }

        return \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionException')
            ->checkOptions($ids);
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->optionGroups = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}
