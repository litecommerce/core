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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\MultiCurrency;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Module name
     *
     * @var    string
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getModuleName()
    {
        return 'Multiple Currencies';
    }

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getVersion()
    {
        return '1.0';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getDescription()
    {
        return 'This module introduces support for multiple currencies';
    }
 	

    public $isFree = true;
    /**
     * Determines if we need to show settings form link
     *
     * @return boolean 
     * @access public
     * @since  3.0
     */
    public static function showSettingsForm()
    {
        return true;
    }

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function init()  
    {
        parent::init();
        \XLite::getInstance()->set('MultiCurrencyEnabled',true);

        $this->defaultCurrency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries();
   		$found = $this->defaultCurrency->find("base = 1");
        if (!$found) {
            $this->defaultCurrency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries();
            $this->defaultCurrency->set('code',"USD");
            $this->defaultCurrency->set('name',"US dollar");
            $this->defaultCurrency->set('exchange_rate',1);
            $this->defaultCurrency->set('price_format', \XLite\Core\Config::getInstance()->General->price_format);
            $this->defaultCurrency->set('base',1);
            $this->defaultCurrency->set('enabled',1);
            $this->defaultCurrency->set('countries',serialize(array()));
            $this->defaultCurrency->create();
        }
    }
    
    function uninstall()  
    {
        func_cleanup_cache('classes');
        func_cleanup_cache('skins');

        parent::uninstall();
    }

}
