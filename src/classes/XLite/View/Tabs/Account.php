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

namespace XLite\View\Tabs;

/**
 * Tabs related to user profile section
 *
 *
 * @ListChild (list="center")
 */
class Account extends \XLite\View\Tabs\ATabs
{
    /**
     * User profile object
     *
     * @var \XLite\Model\Profile
     */
    protected $profile;

    /**
     * Description of tabs related to user profile section and their targets
     *
     * @var array
     */
    protected $tabs = array(
        'profile' => array(
            'title'    => 'Account details',
            'template' => 'account/account.tpl',
        ),
        'address_book' => array(
            'title'    => 'Address book',
            'template' => 'account/address_book.tpl',
        ),
        'order_list'   => array(
            'title'   => 'Orders',
            'template' => 'account/order_list.tpl',
        ),
    );

    /**
     * Returns the list of targets where this widget is available
     *
     * @return void
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'profile';
        $list[] = 'address_book';
        $list[] = 'order_list';

        return $list;
    }

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        if (\XLite\Controller\Customer\Profile::getInstance()->isRegisterMode()) {

            foreach ($this->tabs as $key => $tab) {

                if ('profile' != $key) {

                    unset($this->tabs[$key]);
                }
            }
        }
    }

    /**
     * getProfile
     *
     * @return \XLite\Model\Profile
     */
    public function getProfile()
    {
        if (!isset($this->profile)) {

            $profileId = \XLite\Core\Request::getInstance()->profile_id;

            if (isset($profileId)) {

                $this->profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

            } else {

                $this->profile = \XLite\Core\Auth::getInstance()->getProfile();
            }
        }

        return $this->profile;
    }


    /**
     * Returns an URL to a tab
     *
     * @param string $target Tab target
     *
     * @return string
     */
    protected function buildTabURL($target)
    {
        $profileId = \XLite\Core\Request::getInstance()->profile_id;

        return $this->buildURL($target, '', isset($profileId) ? array('profile_id' => $profileId) : array());
    }

    /**
     * getTitle
     *
     * @return string
     */
    protected function getTitle()
    {
        return 'My account';
    }
}
