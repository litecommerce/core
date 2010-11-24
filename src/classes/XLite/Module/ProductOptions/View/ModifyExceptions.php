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
 * Modify option groups exceptions
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ModifyExceptions extends \XLite\View\AView
{
    /**
     * Exceptions (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $exceptions;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/exceptions.tpl';
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
            && \XLite\Core\Database::getRepo('\XLite\Model\Product')
                ->find($this->getProductId())
                ->getOptionGroups()->count();
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
     * Get option groups 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGroups()
    {
        $list = \XLite\Core\Database::getRepo('\XLite\Model\Product')
            ->find($this->getProductId())
            ->getOptionGroups();

        foreach ($list as $i => $group) {
            if ($group::TEXT_TYPE == $group->getType()) {
                unset($list[$i]);
            }
        }

        return $list;
    }

    /**
     * Get exceptions list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param array                                          $exception Exception cell
     * @param \XLite\Module\ProductOptions\Model\OptionGroup $group     Option group
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isNotPartException(array $exception, \XLite\Module\ProductOptions\Model\OptionGroup $group)
    {
        return !isset($exception[$group->getGroupId()]);
    }

    /**
     * Check - is option selected in specified exception or not
     * 
     * @param array                                     $exception Exception cell
     * @param \XLite\Module\ProductOptions\Model\Option $option    Option
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOptionSelected(array $exception, \XLite\Module\ProductOptions\Model\Option $option)
    {
        return isset($exception[$option->getGroup()->getGroupId()])
            && $exception[$option->getGroup()->getGroupId()] == $option->getOptionId();
    }

    /**
     * Get empty exception cell
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEmptyException()
    {
        return array();
    }
}
