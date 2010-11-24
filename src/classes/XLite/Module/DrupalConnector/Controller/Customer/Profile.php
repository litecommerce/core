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

namespace XLite\Module\DrupalConnector\Controller\Customer;

/**
 * \XLite\Module\DrupalConnector\Controller\Customer\Profile 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Profile extends \XLite\Controller\Customer\Profile implements \XLite\Base\IDecorator
{
    /**
     * Types of model form
     */

    const SECTIONS_MAIN      = 'main';
    const SECTIONS_ADDRESSES = 'addresses';
    const SECTIONS_ALL       = 'all';


    /**
     * Return params for the "Personal info" part of the register form
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModelFormPartMain()
    {
        return array(\XLite\View\Model\Profile\Main::SECTION_MAIN);
    }

    /**
     * Return params for the "Addresses" part of the register form
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModelFormPartAddresses()
    {
        return array(\XLite\View\Model\Profile\Main::SECTION_ADDRESSES);
    }

    /**
     * Return params for the whole register form
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModelFormPartAll()
    {
        return $this->getModelFormPartMain() + $this->getModelFormPartAddresses();
    }

    /**
     * Return part of the register form
     * 
     * @param string $type Part(s) identifier
     *  
     * @return \XLite\View\Model\Profile\AProfile
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModelFormPart($type)
    {
        $method = __FUNCTION__ . ucfirst($type);

        // Get the corresponded sections list
        return $this->getModelForm(array(array(), $this->$method()));
    }

    /**
     * Register the account with the basic data
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionRegisterBasic()
    {
        return $this->getModelFormPart(self::SECTIONS_MAIN)->performAction('create');
    }

    /**
     * Update the account with the basic data
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateBasic()
    {
        return $this->getModelFormPart(self::SECTIONS_MAIN)->performAction('update');
    }
}
