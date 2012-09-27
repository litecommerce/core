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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Customer
 *
 */
class Customer extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Modify login form
     *
     * @param array &$form      Form description
     * @param array &$formState Form state
     *
     * @return void
     */
    public function alterLoginForm(array &$form, array &$formState)
    {
        $form[\XLite\Core\CMSConnector::NO_REDIRECT] = array(
            '#type'  => 'hidden',
            '#value' => true,
        );
    }

    /**
     * Return content for the "Orders" tab
     *
     * @param \stdClass $account Current user descriptor
     *
     * @return void
     */
    public function getOrderHistoryPage(\stdClass $account)
    {
        $this->getHandler()->mapRequest(array('target' => 'order_list'));
        
        drupal_set_title(t('Orders list'));

        return \XLite\Module\CDev\DrupalConnector\Drupal\Controller::getInstance()->getContent();
    }
}
