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

namespace XLite\Module\UPSOnlineTools\Model\Shipping\Processor;

/**
 * Shipping processor model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
class UPS extends \XLite\Model\Shipping\Processor\AProcessor implements \XLite\Base\IDecorator
{
    /**
     * Unique processor Id
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $processorId = 'ups';

    /**
     * getProcessorName 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProcessorName()
    {
        return 'UPS Online Tools';
    }

    /**
     * Returns processor's shipping methods 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingMethods()
    {
        $methods = parent::getShippingMethods();

        if (!empty($methods)) {

            $ups = new \XLite\Module\UPSOnlineTools\Model\Shipping\Ups();

            foreach ($methods as $key => $method) {

                $name = $ups->getNameUPS($method->getName());

                $name = str_replace('<sup>nd', '-nd', $name);
                $name = str_replace('<sup>', ' ', $name);
                $name = str_replace('</sup>', '', $name);

                $methods[$key]->setName($name);
            }
        }

        return $methods;
    }

    /**
     * Returns processor's shipping methods rates
     *                                           
     * @param mixed $order       Order object or an array with input data
     * @param boolean  $ignoreCache Flag: if true then do not get rates from cache
     *                                                                         
     * @return array                                                           
     * @access public                                                          
     * @see    ____func_see____                                                
     * @since  3.0.0                                                           
     */
    public function getRates($order, $ignoreCache = false)
    {
        $rates = array();

        // Return shipping rates list
        return $rates;
    }
}
