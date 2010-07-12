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

namespace XLite\View\Form\Profile;

/**
 * \XLite\View\Form\Profile\Main 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class Main extends AProfile
{
    /**
     * isRegisterMode 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isRegisterMode()
    {
        return self::getCurrentForm()->isRegisterMode();
    }

    /**
     * getDefaultTarget 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTarget()
    {
        return 'profile';
    }

    /**
     * getDefaultAction 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return 'modify';
    }

    /**
     * getDefaultParams 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultParams()
    {
        $result = parent::getDefaultParams();

        if ($this->isRegisterMode()) {
            // Do not pass the profile ID for new profiles
            unset($result['profile_id']);
            // SEt the appropriate mode
            $result[self::PARAM_MODE] = self::getCurrentForm()->getRegisterMode();
        }

        return $result;
    }
}

