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

namespace XLite\Module\CDev\ProductOptions\Controller\Admin;

/**
 * Product modify
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Product extends \XLite\Controller\Admin\Product implements \XLite\Base\IDecorator
{
    /**
     * Constructor
     *
     * @param array $params Parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        if (!in_array('language', $this->params)) {
            $this->params[] = 'language';
        }

    }

    /**
     * Get pages sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPages()
    {
        $pages = parent::getPages();

        if (!$this->isNew()) {
            $pages += array(
                'product_options' => static::t('Product options'),
            );
        }

        return $pages;
    }

    /**
     * Get pages templates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPageTemplates()
    {
        $tpls = parent::getPageTemplates();

        if (!$this->isNew()) {
            $tpls += array(
                'product_options' => 'modules/CDev/ProductOptions/product_options_lander.tpl',
            );
        }

        return $tpls;
    }


    /**
     * Update option groups list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdateOptionGroups()
    {
        $data = \XLite\Core\Request::getInstance()->data;

        if (is_array($data) && $data) {
            $options = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
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

                \XLite\Core\TopMessage::addInfo(
                    'The product option groups have been successfully updated'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::addError(
                'The product option groups have not been successfully updated'
            );
        }
    }

    /**
     * Delete selected option groups
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDeleteOptionGroups()
    {
        $mark = \XLite\Core\Request::getInstance()->mark;
        if (is_array($mark) && $mark) {
            $options = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
                ->findByIds($mark);
            if ($options) {
                foreach ($options as $option) {
                    \XLite\Core\Database::getEM()->remove($option);
                }

                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::addInfo(
                    'The product option groups have been deleted'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::addError(
                'The product option groups have not been deleted'
            );
        }
    }

    /**
     * Update option group
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdateOptionGroup()
    {
        if ('0' === \XLite\Core\Request::getInstance()->groupId) {

            $group = new \XLite\Module\CDev\ProductOptions\Model\OptionGroup;

            $group->setProduct($this->getProduct());

            $this->getProduct()->addOptionGroups($group);

        } else {

            $group = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
                ->find(\XLite\Core\Request::getInstance()->groupId);
        }

        $data = \XLite\Core\Request::getInstance()->data;

        if (!isset($group)) {
            \XLite\Core\TopMessage::addError(
                'The modified option group has not been found'
            );

        } elseif (!$this->getProduct()) {
            \XLite\Core\TopMessage::addError(
                'The modified product has not been found'
            );

        } elseif (!is_array($data)) {
            \XLite\Core\TopMessage::addError(
                'The modified option group data has not been found'
            );

        } elseif (!isset($data['name']) || !$data['name']) {
            \XLite\Core\TopMessage::addError(
                'The modified option group must have a name'
            );

        } elseif (!$group->setType($data['type'])) {
            \XLite\Core\TopMessage::addError(
                'The modified option group has a wrong type'
            );

        } elseif (!$group->setViewType($data['view_type'])) {
            \XLite\Core\TopMessage::addError(
                'The display type is not allowed with this type of option'
            );

        } else {

            $data['orderby'] = abs(intval($data['orderby']));
            $data['enabled'] = isset($data['enabled']) && $data['enabled'];

            $group->setEditLanguageCode($this->getCurrentLanguage());
            $group->map($data);

            $result = true;

            // Update options
            $options = \XLite\Core\Request::getInstance()->options;
            if ($options && \XLite\Core\Request::getInstance()->groupId) {
                foreach ($options as $optionId => $data) {
                    $option = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\Option')
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
                $option = new \XLite\Module\CDev\ProductOptions\Model\Option();
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
                    \XLite\Core\TopMessage::addInfo(
                        'The product option group has been successfully added'
                    );

                } else {
                    \XLite\Core\TopMessage::addInfo(
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDeleteOptions()
    {
        $mark = \XLite\Core\Request::getInstance()->mark;

        if (is_array($mark) && $mark) {
            $options = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\Option')
                ->findByIds($mark);

            if ($options) {
                foreach ($options as $option) {
                    \XLite\Core\Database::getEM()->remove($option);
                }

                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\TopMessage::addInfo(
                    'The options have been deleted'
                );
            }
        }

        if (!isset($options) || !$options) {
            \XLite\Core\TopMessage::addError(
                'The options have not been deleted'
            );
        }
    }

    /**
     * Update option groups exceptions
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdateOptionGroupsExceptions()
    {
        $exceptions = \XLite\Core\Request::getInstance()->exceptions;

        if (!is_array($exceptions) || !$exceptions) {
            \XLite\Core\TopMessage::addError(
                'The modified exceptions data has not been found'
            );

        } else {

            foreach ($exceptions as $eid => $data) {
                if ($eid) {
                    $old = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionException')
                        ->findByExceptionId($eid);
                    if (!$old) {
                        continue;
                    }

                    foreach ($old as $e) {
                        $e->getOption()->setExceptions(array());
                        \XLite\Core\Database::getEM()->remove($e);
                    }
                    \XLite\Core\Database::getEM()->flush();

                } else {
                    $eid = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionException')
                        ->getNextExceptionId();
                }

                $this->saveException($eid, $data);
                \XLite\Core\Database::getEM()->flush();
            }

            \XLite\Core\TopMessage::addInfo(
                'The exceptions have been successfully updated'
            );

        }

    }

    /**
     * Delete option groups exceptions
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDeleteOptionGroupsExceptions()
    {
        $mark = \XLite\Core\Request::getInstance()->mark;

        $exceptions = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionException')
            ->findByExceptionIds($mark);

        if ($exceptions) {
            foreach ($exceptions as $exception) {
                \XLite\Core\Database::getEM()->remove($exception);
            }

            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The exceptions have been deleted'
            );

        } else {
            \XLite\Core\TopMessage::addError(
                'The exceptions have not been deleted'
            );
        }
    }

    /**
     * Save option
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\Option $option Option
     * @param array                                          $data   Data
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function saveOption(\XLite\Module\CDev\ProductOptions\Model\Option $option, array $data)
    {
        $result = false;

        if (!$data['name']) {
            \XLite\Core\TopMessage::addError(
                'The modified option group has a wrong display type'
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
                            $surcharge = new \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge();
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

            $option->setEditLanguageCode($this->getCurrentLanguage());
            $option->map($data);

            \XLite\Core\Database::getEM()->persist($option);

            $result = true;

        }

        return $result;
    }

    /**
     * Save exception
     *
     * @param integer $eid  Exception id
     * @param array   $data Exception cell data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function saveException($eid, array $data)
    {
        foreach ($data as $groupId => $optionId) {
            if ($optionId) {
                $option = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\Option')
                    ->find($optionId);

                if ($option) {
                    $exception = new \XLite\Module\CDev\ProductOptions\Model\OptionException();
                    $exception->setExceptionId($eid);
                    $exception->setOption($option);
                    $option->addExceptions($exception);
                    \XLite\Core\Database::getEM()->persist($exception);
                }
            }
        }
    }
}
