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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductOptions\Controller\Admin;

/**
 * Product modify
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Controller\Admin\Product implements \XLite\Base\IDecorator
{
    /**
     * Constructor
     * 
     * @param array $params Parameters
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->pages['product_options'] = 'Product options';
        $this->pageTemplates['product_options'] = 'modules/ProductOptions/product_options_lander.tpl';

        if (!in_array('language', $this->params)) {
            $this->params[] = 'language';
        }
        
    }

    /**
     * Update option groups list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateOptionGroups()
    {
        $data = \XLite\Core\Request::getInstance()->data;

        if (is_array($data) && $data) {
            $options = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
                ->findByIds(array_keys($data));

            if ($options) {
                foreach ($options as $option) {
                    $cell = $data[$option->getGroupId()];

                    $cell['enabled'] = isset($cell['enabled']) && $cell['enabled'];
                    $cell['orderby'] = abs(intval($cell['orderby']));

                    $option->map($cell);

                    \XLite\Core\Database::getEM()->persist($option);
                }
                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::getInstance()->add(
                    'The product option groups have been successfully updated'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The product option groups have not been successfully updated',
                \XLite\Core\TopMessage::ERROR
            );
        }
    }

    /**
     * Delete selected option groups 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteOptionGroups()
    {
        $mark = \XLite\Core\Request::getInstance()->mark;
        if (is_array($mark) && $mark) {
            $options = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
                ->findByIds($mark);
            if ($options) {
                foreach ($options as $option) {
                    \XLite\Core\Database::getEM()->remove($option);
                }

                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::getInstance()->add(
                    'The product option groups have been deleted'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The product option groups have not been deleted',
                \XLite\Core\TopMessage::ERROR
            );
        }
    }

    /**
     * Update option group 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doActionUpdateOptionGroup()
    {
        if ('0' === \XLite\Core\Request::getInstance()->groupId) {
            $group = new \XLite\Module\ProductOptions\Model\OptionGroup;
            $group->setProduct($this->getProduct());
            $this->getProduct()->addOptionGroups($group);

        } else {
            $group = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
                ->find(\XLite\Core\Request::getInstance()->groupId);
        }

        $data = \XLite\Core\Request::getInstance()->data;

        if (!isset($group)) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The modified option group has not been found',
                \XLite\Core\TopMessage::ERROR
            );

        } elseif (!$this->getProduct()) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The modified product has not been found',
                \XLite\Core\TopMessage::ERROR
            );

        } elseif (!is_array($data)) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The modified option group data has not been found',
                \XLite\Core\TopMessage::ERROR
            );

        } elseif (!isset($data['name']) || !$data['name']) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The modified option group must have a name',
                \XLite\Core\TopMessage::ERROR
            );

        } elseif (!$group->setType($data['type'])) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The modified option group has a wrong type',
                \XLite\Core\TopMessage::ERROR
            );

        } elseif (!$group->setViewType($data['view_type'])) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The option must have a name',
                \XLite\Core\TopMessage::ERROR
            );

        } else {

            $data['orderby'] = abs(intval($data['orderby']));
            $data['enabled'] = isset($data['enabled']) && $data['enabled'];

            $group->map($data);

            $result = true;

            // Update options
            $options = \XLite\Core\Request::getInstance()->options;
            if ($options && \XLite\Core\Request::getInstance()->groupId) {
                foreach ($options as $optionId => $data) {
                    $option = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\Option')
                        ->find($optionId);

                    if ($option && !$this->saveOption($option, $data)) {
                        $result = false;
                        break;
                    }
                }
            }

            // Save new option
            $newOption = \XLite\Core\Request::getInstance()->newOption;
            if ($newOption['name']) {
                $option = new \XLite\Module\ProductOptions\Model\Option();
                $option->setGroup($group);
                $group->addOptions($option);

                if (!$this->saveOption($option, $newOption)) {
                    $result = false;
                }
            }

            if ($result) {
                \XLite\Core\Database::getEM()->persist($group);
                \XLite\Core\Database::getEM()->flush();

                if ('0' === \XLite\Core\Request::getInstance()->groupId) {
                    \XLite\Core\TopMessage::getInstance()->add(
                        'The product option group has been successfully added'
                    );

                } else {
                    \XLite\Core\TopMessage::getInstance()->add(
                        'The product option group has been successfully updated'
                    );
                }
            }

        }
    }

    /**
     * Delete selected options
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteOptions()
    {
        $mark = \XLite\Core\Request::getInstance()->mark;

        if (is_array($mark) && $mark) {
            $options = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\Option')
                ->findByIds($mark);

            if ($options) {
                foreach ($options as $option) {
                    \XLite\Core\Database::getEM()->remove($option);
                }

                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::getInstance()->add(
                    'The options have been deleted'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The options have not been deleted',
                \XLite\Core\TopMessage::ERROR
            );
        }
    }

    /**
     * Save option 
     * 
     * @param \XLite\Module\ProductOptions\Model\Option $option Option
     * @param array                                     $data   Data
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveOption(\XLite\Module\ProductOptions\Model\Option $option, array $data)
    {
        $result = false;

        if (!$data['name']) {
            \XLite\Core\TopMessage::getInstance()->add(
                'The modified option group has a wrong display type',
                \XLite\Core\TopMessage::ERROR
            );

        } else {

            $data['orderby'] = abs(intval($data['orderby']));
            $data['enabled'] = isset($data['enabled']) && $data['enabled'];

            if (isset($data['modifiers'])) {
                foreach ($data['modifiers'] as $type => $m) {
                    $m['modifier'] = round($m['modifier'], 4);

                    if (0 != $m['modifier']) {
                        $surcharge = $option->getSurcharge($type);
                        if (!$surcharge) {
                            $surcharge = new \XLite\Module\ProductOptions\Model\OptionSurcharge();
                            $surcharge->setOption($option);
                            $option->addSurcharges($surcharge);
                            $surcharge->setType($type);
                        }

                        $surcharge->map($m);
                        \XLite\Core\Database::getEM()->persist($surcharge);

                    } elseif ($option->getSurcharge($type)) {
                        $surcharge = $option->getSurcharge($type);
                        $option->getSurcharges()->removeElement($surcharge);
                        $surcharge->setOption(null);
                        \XLite\Core\Database::getEM()->remove($surcharge);
                    }
                }

                unset($data['modifiers']);
            }

            $option->map($data);

            \XLite\Core\Database::getEM()->persist($option);

            $result = true;

        }

        return $result;
    }

}
