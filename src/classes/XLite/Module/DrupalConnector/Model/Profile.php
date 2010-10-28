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

namespace XLite\Module\DrupalConnector\Model;

/**
 * \XLite\Module\DrupalConnector\Model\Profile 
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
class Profile extends \XLite\Model\Profile implements \XLite\Base\IDecorator
{
    /**
     * prepareCreate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCreate()
    {
        parent::prepareCreate();

        if (\XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $this->setCmsName(\XLite\Module\DrupalConnector\Handler::getInstance()->getCMSName());
        }
    }

    /**
     * Get CMS profile 
     * 
     * @return object or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCMSProfile()
    {
        $profile = null;

        if (
            \XLite\Core\CMSConnector::isCMSStarted()
            && $this->getCMSProfileId()
            && $this->getCMSName() == \XLite\Module\DrupalConnector\Handler::getInstance()->getCMSName()
            && function_exists('user_load')
        ) {
            $profile = user_load($this->getCMSProfileId());
            if (!$profile) {
                $profile = null;
            }
        }

        return $profile;
    }

}
