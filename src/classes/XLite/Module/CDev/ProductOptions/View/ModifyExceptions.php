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
 * Modify option groups exceptions
 *
 */
class ModifyExceptions extends \XLite\View\AView
{
    /**
     * Exceptions (cache)
     *
     * @var array
     */
    protected $exceptions;


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
     * Get option groups
     *
     * @return array
     */
    public function getGroups()
    {
        $list = array();

        foreach ($this->getProduct()->getOptionGroups() as $group) {
            if ($group::TEXT_TYPE != $group->getType()) {
                $list[] = $group;
            }
        }

        return $list;
    }

    /**
     * Get exceptions list
     *
     * @return array
     */
    public function getExceptions()
    {
        if (!isset($this->exceptions)) {
            $this->exceptions = array();

            foreach ($this->getGroups() as $group) {
                foreach ($group->getOptions() as $option) {
                    foreach ($option->getExceptions() as $e) {
                        $eid = $e->getExceptionId();
                        if (!isset($this->exceptions[$eid])) {
                            $this->exceptions[$eid] = array();
                        }

                        $this->exceptions[$eid][$group->getGroupId()] = $option->getOptionId();
                    }
                }
            }

            ksort($this->exceptions);
        }

        return $this->exceptions;
    }

    /**
     * Check - is not option group part of specified exception or not
     *
     * @param array                                               $exception Exception cell
     * @param \XLite\Module\CDev\ProductOptions\Model\OptionGroup $group     Option group
     *
     * @return boolean
     */
    public function isNotPartException(array $exception, \XLite\Module\CDev\ProductOptions\Model\OptionGroup $group)
    {
        return !isset($exception[$group->getGroupId()]);
    }

    /**
     * Check - is option selected in specified exception or not
     *
     * @param array                                          $exception Exception cell
     * @param \XLite\Module\CDev\ProductOptions\Model\Option $option    Option
     *
     * @return boolean
     */
    public function isOptionSelected(array $exception, \XLite\Module\CDev\ProductOptions\Model\Option $option)
    {
        return isset($exception[$option->getGroup()->getGroupId()])
            && $exception[$option->getGroup()->getGroupId()] == $option->getOptionId();
    }

    /**
     * Get empty exception cell
     *
     * @return array
     */
    public function getEmptyException()
    {
        return array();
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/ProductOptions/exceptions.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getProduct()->getOptionGroups()->count();
    }
}
