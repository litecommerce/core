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

namespace XLite\Module\DrupalConnector\View\Model\Profile;

/**
 * \XLite\Module\DrupalConnector\View\Model\Profile\AProfile 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AProfile extends \XLite\View\Model\Profile\AProfile implements \XLite\Base\IDecorator
{
    /**
     * Error message - Drupal and LC profiles are not synchronized
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIncompleteProfileErrorMessage()
    {
        return 'Some of the data on this page cannot be displayed, because your profile'
            . ' is not complete. Please contact admin to report this problem.';
    }

    /**
     * Use the specific message in Drupal
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAccessDeniedMessage()
    {
        return \XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? $this->getIncompleteProfileErrorMessage()
            : parent::getAccessDeniedMessage();
    }

    /**
     * Access denied if user is logged into Drupal but not logged into LC
     *
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function checkAccess()
    {
        return \XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? !(user_is_logged_in() && !\XLite\Core\Auth::getInstance()->isLogged())
            : parent::checkAccess();
    }


    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params   widget params
     * @param array $sections sections list
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        parent::__construct($params, $sections);

        if (\XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $this->formFieldNames[] = $this->composeFieldName('cms_profile_id');
        }
    }
}
