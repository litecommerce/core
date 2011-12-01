<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\View\Model\Profile;

/**
 * \XLite\Module\CDev\DrupalConnector\View\Model\Profile\AProfile
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AProfile extends \XLite\View\Model\Profile\AProfile implements \XLite\Base\IDecorator
{
    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params   Widget params OPTIONAL
     * @param array $sections Sections list OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        parent::__construct($params, $sections);

        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $this->formFieldNames[] = $this->composeFieldName('cms_profile_id');
            $this->formFieldNames[] = $this->composeFieldName('drupal_roles');
        }
    }

    /**
     * Return current profile ID
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProfileId()
    {
        $result = parent::getProfileId();

        // If current user is admin and 'createNewUser' parameter passed in request...
        if (\XLite\Core\Request::getInstance()->createNewUser && \XLite\Core\Auth::getInstance()->isAdmin()) {

            // ...then profileId for form model object should be null
            $result = null;
        }

        return $result;
    }


    /**
     * Error message - Drupal and LC profiles are not synchronized
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAccessDeniedMessage()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? $this->getIncompleteProfileErrorMessage()
            : parent::getAccessDeniedMessage();
    }

    /**
     * getDefaultModelObject
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        $cmsProfileId = \XLite\Core\Request::getInstance()->cms_profile_id;

        if (!is_null($cmsProfileId) && \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {

            $obj = \XLite\Core\Database::getRepo('XLite\Model\Profile')
                ->findOneBy(
                    array(
                        'cms_profile_id' => $cmsProfileId,
                        'cms_name' => \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName()
                    )
                );
        }

        if (!isset($obj)) {
            $obj = parent::getDefaultModelObject();
        }

        return $obj;
    }

    /**
     * Access denied if user is logged into Drupal but not logged into LC
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAccess()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? !(user_is_logged_in() && !\XLite\Core\Auth::getInstance()->isLogged())
            : parent::checkAccess();
    }

    /**
     * Do not add additional message when update profile via Drupal interface
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addDataSavedTopMessage()
    {
        if (!\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            parent::addDataSavedTopMessage();
        }
    }

    /**
     * Do not add additional message when delete profile via Drupal interface
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addDataDeletedTopMessage()
    {
        if (!\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            parent::addDataDeletedTopMessage();
        }
    }
}
