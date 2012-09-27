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

namespace XLite\Module\CDev\DrupalConnector\View;

/**
 * 'Powered by' widget
 *
 */
class PoweredBy extends \XLite\View\PoweredBy implements \XLite\Base\IDecorator
{
    /**
     * Advertise phrases
     *
     * @var array
     */
    protected $phrases = array(
        'Powered by [e-commerce CMS]: LiteCommerce plus Drupal',
        'Powered by [e-commerce CMS]: LiteCommerce plus Drupal',
        'Powered by [e-commerce CMS]: LiteCommerce plus Drupal',
        'Powered by [eCommerce CMS]: LiteCommerce plus Drupal',
        'Powered by [eCommerce CMS]: LiteCommerce plus Drupal',
        'Powered by [eCommerce CMS]: LiteCommerce plus Drupal',
        'Powered by [e-commerce CMS software]: LiteCommerce plus Drupal',
        'Powered by [eCommerce CMS software]: LiteCommerce plus Drupal',
        'Powered by [e-commerce CMS solution]: LiteCommerce plus Drupal',
        'Powered by [eCommerce CMS solution]: LiteCommerce plus Drupal',
        'Powered by LiteCommerce [shopping cart] and Drupal CMS',
        'Powered by LiteCommerce [shopping cart software] and Drupal CMS',
        'Powered by LiteCommerce [eCommerce shopping cart] and Drupal CMS',
        'Powered by LiteCommerce [eCommerce software] and Drupal CMS',
        'Powered by LiteCommerce [eCommerce solution] and Drupal CMS',
        'Powered by LiteCommerce [e-commerce software] and Drupal CMS',
        'Powered by LiteCommerce [e-commerce solution] and Drupal CMS',
    );


    /**
     * Check - display widget as link or as box
     *
     * @return boolean
     */
    public function isLink()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? drupal_is_front_page()
            : parent::isLink();
    }

    /**
     * Return a Powered By message
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->isLink()) {
            $phrase = 'Powered by <a href="http://www.litecommerce.com/">LiteCommerce 3</a>'
                . ' integrated with <a href="http://drupal.org/">Drupal</a>';

        } else {
            $phrase = 'Powered by LiteCommerce 3 integrated with Drupal';
        }

        return $phrase;
    }
}
