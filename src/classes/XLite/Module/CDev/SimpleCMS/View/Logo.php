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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\SimpleCMS\View;

/**
 * Logo
 *
 */
abstract class Logo extends \XLite\View\AView implements \XLite\Base\IDecorator
{
    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        $url = \XLite\Core\Config::getInstance()->CDev->SimpleCMS->logo;

        return $url
            ? \XLite\Core\Layout::getInstance()->getResourceWebPath(
                $url,
                \XLite\Core\Layout::WEB_PATH_OUTPUT_URL,
                \XLite::COMMON_INTERFACE
            )
            : parent::getLogo();
    }

    /**
     * Get invoice logo
     *
     * @return string
     */
    public function getInvoiceLogo()
    {
        $url = \XLite\Core\Config::getInstance()->CDev->SimpleCMS->logo;

        return $url
            ? \XLite\Core\Layout::getInstance()->getResourceWebPath(
                $url,
                \XLite\Core\Layout::WEB_PATH_OUTPUT_URL,
                \XLite::COMMON_INTERFACE
            )
            : parent::getInvoiceLogo();
    }
}
