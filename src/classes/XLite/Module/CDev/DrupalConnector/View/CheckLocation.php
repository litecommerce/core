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
 * Check LiteCommerce location widget
 * This widget checks if LiteCommerce is located within lc_connector module or not
 * (see for details: https://github.com/litecommerce/core/wiki/Moving-LiteCommerce-subdirectory-to-the-Drupal-directory)
 *
 *
 * @ListChild (list="admin.main.page.content.center", zone="admin", weight="400")
 */
class CheckLocation extends \XLite\View\AView
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'main';

        return $result;
    }

    /**
     * Return template of Bestseller widget. It depends on widget type:
     * SIDEBAR/CENTER and so on.
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/DrupalConnector/check_location.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->isWrongLocation();
    }

    /**
     * isWrongLocation 
     * 
     * @return boolean
     */
    protected function isWrongLocation()
    {
        $result = false;

        // Counter of checkings
        $counter = isset(\XLite\Core\Config::getInstance()->Internal->check_location)
            ? intval(\XLite\Core\Config::getInstance()->Internal->check_location)
            : 1;

        // Check location each 10 visits of the admin dashboard page
        if (0 < $counter) {

            // Prepare new value for counter
            $newCounterValue = (10 == $counter ? 1 : $counter + 1);

            if (1 === $counter) {

                // Check directory location
                $result = preg_match('/modules\/lc_connector/', __DIR__);

                if ($result) {

                    // Generate top message
                    \XLite\Core\TopMessage::getInstance()->addWarning(
                        'Warning: LiteCommerce is installed within the "LC Connector" module directory. It is strongly recommended to move LiteCommerce directory from that location to avoid the problem, described <a href="http://www.facebook.com/litecommerce/posts/440928792599823">here</a>. You can find the instructions on how to do it <a href="https://github.com/litecommerce/core/wiki/Moving-LiteCommerce-subdirectory-to-the-Drupal-directory" target="new">here</a>. If you find it difficult to follow the instruction yourself, please contact <a href="mailto:xlite@litecommerce.com">xlite@litecommerce.com</a> or create a ticket at <a href="http://bt.litecommerce.com/">Bugtracker</a>.'
                    );

                } else {
                    $newCounterValue = 0;
                }
            }

            // Create/Update option with new counter value
            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'name'     => 'check_location',
                    'category' => 'Internal',
                    'value'    => $newCounterValue,
                )
            );
        }

        return $result;
    }
}
