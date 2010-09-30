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
 * @subpackage Cart
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Cart widget 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @ListChild (list="center")
 */
class Cart extends \XLite\View\Dialog
{
    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return $this->getCart()->isEmpty()
            ? $this->t('Your shopping bag is empty')
            : $this->t('Your shopping bag - X items', array('count' => $this->getCart()->countItems()));
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'shopping_cart';
    }

    /**
     * Return file name for body template
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getBodyTemplate()
    {
        return $this->getCart()->isEmpty()
            ? 'empty.tpl'
            : parent::getBodyTemplate();
    }

    /**
     * Get continue URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getContinueURL()
    {
        $url = $this->session->get('continueURL');

        if (!$url && isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        }

        if (!$url) {
            $url = $this->buildURL('main');
        }

        return $url;
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        return array_merge(
            parent::getCSSFiles(),
            array(
                $this->getDir() . '/cart.css',
            )
        );
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'js/core.controller.js';
        $list[] = 'js/core.loadable.js';
        $list[] = 'js/core.popup.js';
        $list[] = 'js/jquery.blockUI.js';
        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'cart';
    
        return $result;
    }

    /**
     * Check - shipping estimate or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingEstimate()
    {
        return (bool)\XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart());
    }

    /**
     * Get shipping estimate address
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEstimateAddress()
    {
        $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getCart());

        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($address['country']);
        $state = \XLite\Core\Database::getRepo('XLite\Model\State')->find($address['state']);

        $string = $country->getCountry();

        if ($state) {
            $string .= ' ' . $state->getState();
        }

        $string .= ', ' . $address['zipcode'];

        return $string;
    }
}

